<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentChannel extends Model
{
    protected $table = 'payment_channels';
    protected $guarded = ['id'];
    public $timestamps = false;

    static $classes = [
        'Alipay', 'Authorizenet', 'Bitpay', 'Braintree', 'Cashu', 'Flutterwave',
        'Instamojo', 'Iyzipay', 'Izipay', 'KlarnaCheckout', 'MercadoPago', 'Midtrans',
        'Mollie', 'Ngenius', 'Payfort', 'Payhere', 'Payku', 'Paylink', 'Paypal',
        'Paysera', 'Paystack', 'Paytm', 'Payu', 'Razorpay', 'Robokassa', 'Sslcommerz',
        'Stripe', 'Toyyibpay', 'Voguepay', 'YandexCheckout', 'Zarinpal', 'JazzCash',
        'Redsys'
    ];

    static $gatewayIgnoreRedirect = [
        'Paytm', 'Payu', 'Zarinpal', 'Stripe', 'Paysera', 'Cashu',
        'MercadoPago', 'Payhere', 'Authorizenet', 'Voguepay', 'Payku', 'KlarnaCheckout', 'Izipay', 'Iyzipay',
        'JazzCash', 'Redsys'
    ];

    static $paypal = 'Paypal';
    static $paystack = 'Paystack';
    static $paytm = 'Paytm';
    static $payu = 'Payu';
    static $razorpay = 'Razorpay';
    static $zarinpal = 'Zarinpal';
    static $stripe = 'Stripe';
    static $paysera = 'Paysera';
    static $fastpay = 'Fastpay';
    static $yandexcheckout = 'YandexCheckout';
    static $twoCheckout = '2checkout';
    static $bitpay = 'Bitpay';
    static $midtrans = 'Midtrans';
    static $adyen = 'Adyen';
    static $flutterwave = 'Flutterwave';
    static $payfort = 'Payfort';
    static $sslcommerz = 'Sslcommerz';
    static $instamojo = 'Instamojo';
    static $payhere = 'Payhere';
    static $ngenius = 'Ngenius';
    static $authorizenet = 'Authorizenet';
    static $voguepay = 'Voguepay';
    static $payku = 'Payku';
    static $toyyibpay = 'Toyyibpay';
    static $robokassa = 'Robokassa';
    static $klarnaCheckout = 'KlarnaCheckout';
    static $mollie = 'Mollie';
    static $alipay = 'Alipay';
    static $braintree = 'Braintree';
    static $izipay = 'Izipay';
    static $paylink = 'Paylink';
    static $jazzCash = 'JazzCash';
    static $redsys = 'Redsys';
}
