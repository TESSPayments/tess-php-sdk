<?php

namespace TessPayments\Checkout;

use TessPayments\Core\HttpClient;
use TessPayments\Checkout\HashService;
use TessPayments\Core\Config;
use TessPayments\Core\Enums\Actions;

class CheckoutService
{
    private $httpClient;
    private $merchantKey;

    public function __construct()
    {
        $config = Config::getInstance();
        $baseUrl = $config->getBaseUrl('CHECKOUT');
        $this->httpClient = new HttpClient($baseUrl);
        $this->merchantKey = $config->get('merchant_key');
    }

    // Authentication and Payment Session
    public function standardPayment(array $params): array
    {
        return $this->handleAuthRequest($params, []);
    }

    // 3DS token generate and first payment
    public function threeDsInitPayment(array $params): array
    {
        return $this->handleAuthRequest($params, [
            'req_token'
        ]);
    }

    // 3DS following payment using token
    public function threeDsTokenPayment(array $params): array
    {
        return $this->handleAuthRequest($params, [
            'card_token'
        ]);
    }

    // recurring token generate and first payment
    public function recurringInitPayment(array $params): array
    {
        return $this->handleAuthRequest($params, [
            'recurring_init'
        ]);
    }

    // recurring following payment using token
    public function recurringTokenPayment(array $params): array
    {
        $params['hash'] = HashService::generate($params, Actions::RECURRING);
        $this->validateParams($params, ['recurring_init_trans_id', 'recurring_token', 'hash', 'order.number', 'order.amount', 'order.description']);
        return $this->sendRequest('/api/v1/payment/recurring', $params);
    }

    // RETRY request is used to retry funds charging for secondary recurring payments in case of soft decline
    public function retryRecurringTokenPayment(array $params): array
    {
        $params['hash'] = HashService::generate($params, Actions::RETRY_RECURRING);
        $this->validateParams($params, ['payment_id', 'hash']);
        return $this->sendRequest('/api/v1/payment/retry', $params);
    }

    // Refund
    public function refundPayment(array $params): array
    {
        $params['hash'] = HashService::generate($params, Actions::REFUND);
        $this->validateParams($params, ['payment_id', 'amount', 'hash']);
        return $this->sendRequest('/api/v1/payment/refund', $params);
    }

    // Void
    public function voidPayment(array $params): array
    {
        $params['hash'] = HashService::generate($params, Actions::VOID);
        $this->validateParams($params, ['payment_id', 'hash']);
        return $this->sendRequest('/api/v1/payment/void', $params);
    }

    // GET_TRANS_STATUS by payment_id
    public function inquiryByPaymentId(array $params): array
    {
        $params['hash'] = HashService::generate($params, Actions::INQUIRY_BY_PAYMENT_ID);
        $this->validateParams($params, ['payment_id', 'hash']);
        return $this->sendRequest('/api/v1/payment/status', $params);
    }

    // GET_TRANS_STATUS by order_id
    public function inquiryByOrderId(array $params): array
    {
        $params['hash'] = HashService::generate($params, Actions::INQUIRY_BY_ORDER_ID);
        $this->validateParams($params, ['order_id', 'hash']);
        return $this->sendRequest('/api/v1/payment/status', $params);
    }

    private function handleAuthRequest(array $params, array $additionalFields): array
    {
        $baseFields = ['operation', 'success_url', 'hash', 'order.number', 'order.amount', 'order.currency', 'order.description'];
        $params['hash'] = HashService::generate($params, Actions::AUTHENTICATION);
        
        $this->validateParams(
            $params,
            array_merge($baseFields, $additionalFields)
        );
        
        return $this->sendRequest('/api/v1/session', $params);
    }

    private function sendRequest(string $endpoint, array $params): array
    {
        $payload = array_merge($params, ['merchant_key' => $this->merchantKey]);
        
        return $this->httpClient->post($endpoint, $payload);
    }

    private function validateParams(array $params, array $required): void
    {
        foreach ($required as $field) {
            $value = $this->getNestedValue($params, $field);
            
            if ($value === null || $value === '') {
                throw new \InvalidArgumentException("Missing required parameter: {$field}");
            }
        }
    }

    private function getNestedValue(array $data, string $key)
    {
        $keys = explode('.', $key);
        $current = $data;

        foreach ($keys as $k) {
            if (!is_array($current) || !array_key_exists($k, $current)) {
                return null;
            }
            $current = $current[$k];
        }

        return $current;
    }
}
