<?php
require __DIR__ . '/../vendor/autoload.php';

// Load environment variables
\TessPayments\Core\Config::getInstance();

// Initialize checkout service
$checkout = new \TessPayments\Checkout\CheckoutService();

// Order Details
$orderNumber = date("Ymd").rand('1000000','9999999');
$orderAmount = "1.00";
$orderCurrency = "QAR";

// Perform normal auth
try {
    // $response = $checkout->standardPayment([
    //     "operation" => "purchase",
    //     "methods" => ["applepay", "om-wallet", "card", "naps"],
    //     "order" => [
    //         "number" => $orderNumber,
    //         "amount" => $orderAmount,
    //         "currency" => $orderCurrency,
    //         "description" => "Test 4",
    //     ],
    //     "billing_address" => [
    //         "country" => "QA",
    //         "state" => "QA",
    //         "zip" => "123456",
    //     ],
    //     "cancel_url" => "https://example.com/cancel",
    //     "success_url" => "https://example.com/success",
    //     "customer" => [
    //         "name" => "John Doe",
    //         "email" => "test@gmail.com"
    //     ]
    // ]);
    
    // $response = $checkout->refundPayment([
    //     'payment_id'=>'b1f4a350-07da-11f0-ba48-0242c0a8200b',
    //     'amount'=>'22.00'
    // ]);

    // $response = $checkout->voidPayment([
    //     'payment_id'=>'40da511e-f85e-11ef-b6a6-0242c0a81003'
    // ]);

    // $response = $checkout->inquiryByPaymentId([
    //     'payment_id'=>'40da511e-f85e-11ef-b6a6-0242c0a81003'
    // ]);

    $response = $checkout->inquiryByOrderId([
        'order_id'=>'76f5ed33-5232-4baf-a9fa-047d3e6be888'
    ]);
    
    var_dump($response);
} catch (\Exception $e) {
    // Handle error
}