<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Omnipay\Omnipay;
use App\Models\PaypalPayment;
class PaymentController extends Controller
{
    private $gateway;
    private $paypalCurrency;

    public function __construct(){
        $this->gateway = Omnipay::create('paypal_rest');
        $paypal = PaypalPayment::first();
        if ($paypal) {
            $accountMode = strtolower((string) $paypal->account_mode);
            $this->gateway->setClientId($paypal->client_id);
            $this->gateway->setSecret($paypal->secret_id);
            $this->gateway->setTestMode($accountMode !== 'live');
            $this->paypalCurrency = $paypal->currency_code ?: env('PAYPAL_CURRENCY', 'USD');
        } else {
            $this->gateway->setClientId(env('PAYPAL_CLIENT_ID'));
            $this->gateway->setSecret(env('PAYPAL_CLIENT_SECRET'));
            $this->gateway->setTestMode(true);
            $this->paypalCurrency = env('PAYPAL_CURRENCY', 'USD');
        }
    }

    public function webpayment(Request $request){
        try {
            $response = $this->gateway->purchase(array(
                'amount' => $request->amount,
                 'currency' => $this->paypalCurrency,
                 'returnUrl' => url('success'),

                 'cancelUrl' => url('error'),

            ));
            if($response->isRedirect()){
                $response->redirect();
            }
            else{
                return $response->getMessage();
            }
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }
}
