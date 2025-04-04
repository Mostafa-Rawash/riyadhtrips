<?php
return [
    'gateways'=>[
        'offline_payment'=>Modules\Booking\Gateways\OfflinePaymentGateway::class,
        'paypal'=>Modules\Booking\Gateways\PaypalGateway::class,
        'clickpay' => Modules\Booking\Gateways\ClickPayGateway::class,
        'stripe'=>Modules\Booking\Gateways\StripeGateway::class,
        'payrexx'=>Modules\Booking\Gateways\PayrexxGateway::class,
        'paystack'=>Modules\Booking\Gateways\PaystackGateway::class,
    ],
];
