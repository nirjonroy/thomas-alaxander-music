<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Omnipay\Omnipay;
class PaymentController extends Controller
{
    private $gateway;

    public function __construct(){
        $this->gateway = Omnipay::create('paypal_rest');
        $this->gateway->setClientId('Abn30pXnmALmhdYywCg-koIaEb59tJMmKHVo7jthoL2UHm7K47CbOICRf9Df00yWVcRC9cu-skqva2kt');
        $this->gateway->setSecrect('ENIuuY5L5AZeA7d_ZT_P7xq3-50cTSxMlJbCaAROLDEbF05Phs8SI37bbRALxN3tUtEY5vR__fwfl0mI');
        $this->gateway->setTestMode(true);
    }

    public function webpayment(Request $request){
        try {
            $response = $this->gateway->purchase(array(
                'amount' => $request->amount,
                 'currency' => env('PAYPAL_CURRENCY'),
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
