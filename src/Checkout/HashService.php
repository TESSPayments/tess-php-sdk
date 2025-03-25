<?php

namespace TessPayments\Checkout;

use TessPayments\Core\Config;
use TessPayments\Core\Enums\Actions;

class HashService
{
    public static function generate(array $params, Actions $action): string
    {
        $config = Config::getInstance();
        switch($action)
        {
            case Actions::AUTHENTICATION:
                $hashParams = array(
                    "order_number" => $params["order"]["number"],
                    "order_amount" => $params["order"]["amount"],
                    "order_currency" => $params["order"]["currency"],
                    "order_description" => $params["order"]["description"]
                );
                break;
            case Actions::REFUND:
                $hashParams = array(
                    "order_number" => $params["payment_id"],
                    "order_amount" => $params["amount"]
                );
                break;
            case Actions::VOID:
                $hashParams = array(
                    "order_number" => $params["payment_id"]
                );
                break;
            case Actions::RECURRING:
                $hashParams = array(
                    "recurring_init_trans_id" => $params["recurring_init_trans_id"],
                    "recurring_token" => $params["recurring_token"],
                    "order_number" => $params["order"]["number"],
                    "order_amount" => $params["order"]["amount"],
                    "order_description" => $params["order"]["description"]
                );
                break;
            case Actions::RETRY_RECURRING:
                $hashParams = array(
                    "payment_id" => $params["payment_id"]
                );
                break;
            case Actions::INQUIRY_BY_PAYMENT_ID:
                $hashParams = array(
                    "payment_id" => $params["payment_id"]
                );
                break;
            case Actions::INQUIRY_BY_ORDER_ID:
                $hashParams = array(
                    "order_id" => $params["order_id"]
                );
                break;
            case Actions::CALLBACK_NOTIFICATION:
                $hashParams = array(
                    "public_id" => $params["id"],
                    "order_number" => $params['order_number'],
                    "order_amount" => $params['order_amount'],
                    "order_currency" => $params['order_currency'],
                    "order_description" => $params['order_description']
                );
                break;
            default:
                throw new \InvalidArgumentException("Invalid action: {$action}");
                break;
        }
        $string = implode('', array_values($hashParams));
        return sha1(md5(strtoupper($string . $config->get('merchant_pass'))));
    }
}