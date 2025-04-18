<?php

namespace Modules\Booking\Gateways;

use App\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Mockery\Exception;
use Modules\Booking\Events\BookingCreatedEvent;
use Modules\Booking\Events\BookingUpdatedEvent;
use Modules\Booking\Models\Booking;
use Modules\Booking\Models\Payment;
use Omnipay\Omnipay;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ClickPayGateway extends BaseGateway
{
    public $name = 'ClickPay Checkout';
    /**
     * @var $gateway ExpressGateway
     */
    protected $gateway;

    public function getOptionsConfigs()
    {
        return [
            [
                'type'  => 'checkbox',
                'id'    => 'enable',
                'label' => __('Enable ClickPay Standard?')
            ],
            [
                'type'       => 'input',
                'id'         => 'name',
                'label'      => __('Custom Name'),
                'std'        => __("ClickPay"),
                'multi_lang' => "1"
            ],
            [
                'type'  => 'upload',
                'id'    => 'logo_id',
                'label' => __('Custom Logo'),
            ],
            [
                'type'  => 'editor',
                'id'    => 'html',
                'label' => __('Custom HTML Description'),
                'multi_lang' => "1"
            ],
            [
                'type'  => 'checkbox',
                'id'    => 'test',
                'label' => __('Enable Sandbox Mod?')
            ],
            [
                'type'    => 'select',
                'id'      => 'convert_to',
                'label'   => __('Convert To'),
                'desc'    => __('In case of main currency does not support by ClickPay. You must select currency and input exchange_rate to currency that ClickPay support'),
                'options' => $this->supportedCurrency()
            ],
            [
                'type'       => 'input',
                'id'         => 'exchange_rate',
                'label'      => __('Exchange Rate'),
                'desc'       => __('Example: Main currency is VND (which does not support by ClickPay), you may want to convert it to USD when customer checkout, so the exchange rate must be 23400 (1 USD ~ 23400 VND)'),
            ],
            [
                'type'      => 'input',
                'id'        => 'test_account',
                'label'     => __('Sandbox API Authentication'),
                'condition' => 'g_clickpay_test:is(1)'
            ],
            [
                'type'      => 'input',
                'id'        => 'test_client_id',
                'label'     => __('Sandbox API ClientID'),
                'condition' => 'g_clickpay_test:is(1)'
            ],

            [
                'type'      => 'input',
                'id'        => 'account',
                'label'     => __('API Authentication'),
                'condition' => 'g_clickpay_test:is()'
            ],
            [
                'type'      => 'input',
                'id'        => 'client_id',
                'label'     => __('API ClientID'),
                'condition' => 'g_clickpay_test:is()'
            ],
        ];
    }

    public function process(Request $request, $booking, $service)
    {
        if (in_array($booking->status, [
            $booking::PAID,
            $booking::COMPLETED,
            $booking::CANCELLED
        ])) {

            throw new Exception(__("Booking status does need to be paid"));
        }
        if (!$booking->pay_now) {
            throw new Exception(__("Booking total is zero. Can not process payment gateway!"));
        }

        $payment = new Payment();
        $payment->booking_id = $booking->id;
        $payment->payment_gateway = $this->id;
        $payment->status = 'draft';
        $data = $this->handlePurchaseData([
            'amount'        => (float)$booking->pay_now,
            'transactionId' => $booking->code . '.' . time()
        ], $booking, $payment);

        $response = Http::withHeaders([
            'authorization' => $this->getOption('account'),
        ])->post('https://secure.clickpay.com.sa/payment/request', [
            "profile_id" => $this->getOption('client_id'),
            "tran_type" => "sale",
            "tran_class" => "ecom",
            "cart_id" => $data['transactionId'],
            "cart_description" => "Dummy description",
            "cart_currency" => "SAR",
            "cart_amount" => $data['amount'],
            "return" =>  "https://riyadhtrips.com/gateway/gateway_callback/clickpay" . '?c=' . $booking->code
        ]);
        $resJson = $response->json();
        if ($resJson['redirect_url'] != '') {
            $payment->save();
            $booking->status = $booking::UNPAID;
            $booking->payment_id = $payment->id;
            $booking->save();
            try {
                event(new BookingCreatedEvent($booking));
            } catch (\Swift_TransportException $e) {
                Log::warning($e->getMessage());
            }
            // redirect to offsite payment gateway
            response()->json([
                'url' => $resJson['redirect_url']
            ])->send();
        } else {
            throw new Exception('ClickPay Gateway: ' . $response->getMessage());
        }
    }

    public function confirmPayment(Request $request)
    {
        
        $c = $request->query('c');
        $booking = Booking::where('code', $c)->first();
        if (!empty($booking) and in_array($booking->status, [$booking::UNPAID])) {
            $this->getGateway();
            $data = $this->handlePurchaseData([
                'amount'        => (float)$booking->pay_now,
                'transactionId' => $booking->code . '.' . time()
            ], $booking);
            $response = $this->gateway->completePurchase($data)->send();
            if ($response->isSuccessful()) {
                $payment = $booking->payment;
                if ($payment) {
                    $payment->status = 'completed';
                    $payment->logs = \GuzzleHttp\json_encode($response->getData());
                    $payment->save();
                }
                try {
                    //                    $oldPaynow = (float)$booking->pay_now;
                    $booking->paid += (float)$booking->pay_now;
                    //                    $booking->pay_now = (float)($oldPaynow - $data['originalAmount'] < 0 ? 0 : $oldPaynow - $data['originalAmount']);
                    $booking->markAsPaid();
                } catch (\Swift_TransportException $e) {
                    Log::warning($e->getMessage());
                }
                return redirect($booking->getDetailUrl())->with("success", __("You payment has been processed successfully"));
            } else {

                $payment = $booking->payment;
                if ($payment) {
                    $payment->status = 'fail';
                    $payment->logs = \GuzzleHttp\json_encode($response->getData());
                    $payment->save();
                }
                try {
                    $booking->markAsPaymentFailed();
                } catch (\Swift_TransportException $e) {
                    Log::warning($e->getMessage());
                }
                return redirect($booking->getDetailUrl())->with("error", __("Payment Failed"));
            }
        }
        if (!empty($booking)) {
            return redirect($booking->getDetailUrl(false));
        } else {
            return redirect(url('/'));
        }
    }

    public function confirmNormalPayment()
    {
        
        /**
         * @var Payment $payment
         */
        $request = \request();
        $c = $request->query('pid');
        $payment = Payment::where('code', $c)->first();

        if (!empty($payment) and in_array($payment->status, ['draft'])) {
            $this->getGateway();
            $data = $this->handlePurchaseDataNormal([
                'amount'        => (float)$payment->amount,
                'transactionId' => $payment->code . '.' . time()
            ], $payment);
            $response = $this->gateway->completePurchase($data)->send();
            if ($response->isSuccessful()) {
                return $payment->markAsCompleted(\GuzzleHttp\json_encode($response->getData()));
            } else {
                return $payment->markAsFailed(\GuzzleHttp\json_encode($response->getData()));
            }
        }
        if ($payment) {
            if ($payment->status == 'cancel') {
                return [false, __("Your payment has been canceled")];
            }
        }
        return [false];
    }
    public function callbackPayment(Request $request)
    {


        try {
            $serverKey = $this->getOption('account');
            $signature_fields = filter_input_array(INPUT_POST);
            $requestSignature = $signature_fields["signature"];
            unset($signature_fields["signature"]);
            $signature_fields = array_filter($signature_fields);
            ksort($signature_fields);
            $query = http_build_query($signature_fields);
            $signature = hash_hmac('sha256', $query, $serverKey);
            if (hash_equals($signature, $requestSignature) === TRUE) {

                $c = $request->query('c');
                $booking = Booking::where('code', $c)->first();
                if (!empty($booking) and !empty($booking->create_user))
                    Auth::loginUsingId($booking->create_user);


                if (!empty($booking) and in_array($booking->status, [$booking::UNPAID])) {

                    $data = $request->all();

                    if ($data['respMessage'] == 'Authorised') {

                        $payment = $booking->payment;
                        if ($payment) {
                            $payment->status = 'completed';
                            $payment->logs = \GuzzleHttp\json_encode($data);
                            $payment->save();
                        }
                        try {
                            //                    $oldPaynow = (float)$booking->pay_now;
                            $booking->paid += (float)$booking->pay_now;
                            //                    $booking->pay_now = (float)($oldPaynow - $data['originalAmount'] < 0 ? 0 : $oldPaynow - $data['originalAmount']);
                            $booking->markAsPaid();
                        } catch (\Swift_TransportException $e) {
                            Log::warning($e->getMessage());
                        }
                        return redirect($booking->getDetailUrl())->with("success", __("You payment has been processed successfully"));
                    } else if ($data['respMessage'] == 'Cancelled') {

                        $payment = $booking->payment;
                        if ($payment) {
                            $payment->status = 'fail';
                            $payment->logs = \GuzzleHttp\json_encode($data);
                            $payment->save();
                        }

                        try {
                            $booking->markAsPaymentFailed();
                        } catch (\Swift_TransportException $e) {
                            Log::warning($e->getMessage());
                        }

                        return redirect($booking->getDetailUrl())->with("error", __("Payment Failed"));
                    }
                } else if (!empty($booking)) {

                    return redirect($booking->getDetailUrl(false));
                } else {

                    return redirect(url('/'));
                }
            } else {

                return redirect(url('/'));
            }
        } catch (Exception $e) {
            Log::warning($e->getMessage());
        }
    }

    public function processNormal($payment)
    {
     
        $this->getGateway();
        $payment->payment_gateway = $this->id;
        $data = $this->handlePurchaseDataNormal([
            'amount'        => (float)$payment->amount,
            'transactionId' => $payment->code . '.' . time()
        ],  $payment);

        $response = $this->gateway->purchase($data)->send();

        if ($response->isSuccessful()) {
            return [true];
        } elseif ($response->isRedirect()) {
            return [true, false, $response->getRedirectUrl()];
        } else {
            return [false, $response->getMessage()];
        }
    }

    public function cancelPayment(Request $request)
    {
        
        $c = $request->query(key: 'c');
        $booking = Booking::where('code', $c)->first();
        if (!empty($booking) and in_array($booking->status, [$booking::UNPAID])) {
            $payment = $booking->payment;
            if ($payment) {
                $payment->status = 'cancel';
                $payment->logs = \GuzzleHttp\json_encode([
                    'customer_cancel' => 1
                ]);
                $payment->save();
            }

            // Refund without check status
            $booking->tryRefundToWallet(false);

            return redirect($booking->getDetailUrl())->with("error", __("You cancelled the payment"));
        }
        if (!empty($booking)) {
            return redirect($booking->getDetailUrl());
        } else {
            return redirect(url('/'));
        }
    }

    public function getGateway()
    {

        $this->gateway = Omnipay::create('ClickPay_Express');
        $this->gateway->setUsername($this->getOption('account'));
        $this->gateway->setPassword($this->getOption('client_id'));
        $this->gateway->setSignature($this->getOption('client_secret'));
        $this->gateway->setTestMode(false);
        if ($this->getOption('test')) {
            $this->gateway->setUsername($this->getOption('test_account'));
            $this->gateway->setPassword($this->getOption('test_client_id'));
            $this->gateway->setSignature($this->getOption('test_client_secret'));
            $this->gateway->setTestMode(true);
        }
    }

    public function handlePurchaseDataNormal($data, &$payment = null)
    {
        $main_currency = setting_item('currency_main');
        $supported = $this->supportedCurrency();
        $convert_to = $this->getOption('convert_to');
        $data['currency'] = $main_currency;
        $data['returnUrl'] = $this->getReturnUrl(true) . '?pid=' . $payment->code;
        $data['cancelUrl'] = $this->getCancelUrl(true) . '?pid=' . $payment->code;
        if (!array_key_exists($main_currency, $supported)) {
            if (!$convert_to) {
                throw new Exception(__("ClickPay does not support currency: :name", ['name' => $main_currency]));
            }
            if (!$exchange_rate = $this->getOption('exchange_rate')) {
                throw new Exception(__("Exchange rate to :name must be specific. Please contact site owner", ['name' => $convert_to]));
            }
            if ($payment) {
                $payment->converted_currency = $convert_to;
                $payment->converted_amount = $payment->amount / $exchange_rate;
                $payment->exchange_rate = $exchange_rate;
                $payment->save();
            }
            $data['amount'] = number_format($payment->amount / $exchange_rate, 2);
            $data['currency'] = $convert_to;
        }
        return $data;
    }
    public function handlePurchaseData($data, $booking, &$payment = null): mixed
    {
        $main_currency = setting_item('currency_main');
        $supported = $this->supportedCurrency();
        $convert_to = $this->getOption('convert_to');
        $data['currency'] = $main_currency;
        $data['returnUrl'] = $this->getReturnUrl() . '?c=' . $booking->code;
        $data['cancelUrl'] = $this->getCancelUrl() . '?c=' . $booking->code;
        if (array_key_exists($main_currency, $supported)) {
            if (!$convert_to) {
                throw new Exception(__("ClickPay does not support currency: :name", ['name' => $main_currency]));
            }
            if (!$exchange_rate = $this->getOption('exchange_rate')) {
                throw new Exception(__("Exchange rate to :name must be specific. Please contact site owner", ['name' => $convert_to]));
            }
            if ($payment) {
                $payment->converted_currency = $convert_to;
                $payment->converted_amount = $booking->pay_now / $exchange_rate;
                $payment->exchange_rate = $exchange_rate;
            }
            $data['originalAmount'] = (float)$booking->pay_now;
            $data['amount'] = number_format((float)$booking->pay_now / $exchange_rate, 2);
            $data['currency'] = $convert_to;
        }
        return $data;
    }

    public function supportedCurrency()
    {
        return [
            "sar" => "Saudi Arabia Riyal",
            "aud" => "Australian dollar",
            "brl" => "Brazilian real 2",
            "cad" => "Canadian dollar",
            "cny" => "Chinese Renmenbi 3",
            "czk" => "Czech koruna",
            "dkk" => "Danish krone",
            "eur" => "Euro",
            "hkd" => "Hong Kong dollar",
            "huf" => "Hungarian forint 1",
            "ils" => "Israeli new shekel",
            "jpy" => "Japanese yen 1",
            "myr" => "Malaysian ringgit 2",
            "mxn" => "Mexican peso",
            "twd" => "New Taiwan dollar 1",
            "nzd" => "New Zealand dollar",
            "nok" => "Norwegian krone",
            "php" => "Philippine peso",
            "pln" => "Polish złoty",
            "gbp" => "Pound sterling",
            "rub" => "Russian ruble",
            "sgd" => "Singapore dollar ",
            "sek" => "Swedish krona",
            "chf" => "Swiss franc",
            "thb" => "Thai baht",
            "usd" => "United States dollar",
        ];
    }
}
