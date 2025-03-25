# TESS Payments PHP SDK
First configure `MERCHANT_KEY` and `MERCHANT_PASSWORD` in `.env` file.

Now run

```console
composer install
```

## Checkout Integration
### Methods
1. standardPayment
[Request Parameters](https://docs.tesspayments.com/#request-parameters)
2. threeDsInitPayment
[Request Parameters](https://docs.tesspayments.com/#request-parameters)
3. threeDsTokenPayment
[Request Parameters](https://docs.tesspayments.com/#request-parameters)
4. recurringInitPayment
[Request Parameters](https://docs.tesspayments.com/#request-parameters)
5. recurringTokenPayment
[Request Parameters](https://docs.tesspayments.com/#request-parameters-1)
6. retryRecurringTokenPayment
[Request Parameters](https://docs.tesspayments.com/)
7. refundPayment
[Request Parameters](https://docs.tesspayments.com/#request-parameters-2)
8. voidPayment
[Request Parameters](https://docs.tesspayments.com/)
9. inquiryByPaymentId
[Request Parameters](https://docs.tesspayments.com/#request-parameters-by-payment_id)
10. inquiryByOrderId
[Request Parameters](https://docs.tesspayments.com/#request-parameters-by-order_id)

### Usage

```console
php example/checkout.php
```