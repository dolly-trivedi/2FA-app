<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect;
use URL;
use Session;
use Input;
use Omnipay\Omnipay;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
// use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;
use App\Models\Payment;


class PaypalController extends Controller
{
    // private $apiContext;

    // public function __construct()
    // {
    //     $paypalConfig = \Config::get('paypal');
    //     $this->apiContext = new ApiContext(new OAuthTokenCredential($paypalConfig['client_id'],$paypalConfig['secret']));
    //     $this->apiContext->setConfig( $paypalConfig['settings']);
    // }

    private $gateway;

    public function __construct() {
        $this->gateway = Omnipay::create('PayPal_Rest');
        $this->gateway->setClientId(env('PAYPAL_CLIENT_ID'));
        $this->gateway->setSecret(env('PAYPAL_CLIENT_SECRET'));
        $this->gateway->setTestMode(true);
    }

    public function payWithPaypal()
    {
       return view ('paywithpaypal');
    }
    public function postPayWithPaypal(Request $req)
    {

        try {

            $response = $this->gateway->purchase(array(
                'amount' => $req->amount,
                'currency' => env('PAYPAL_CURRENCY'),
                'returnUrl' => url('success'),
                'cancelUrl' => url('error')
            ))->send();

            if ($response->isRedirect()) {
                $response->redirect();
            }
            else{
                return $response->getMessage();
            }

        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        #commented code created with PAYPAL RESE-API-SDK package. And reset is created with OMNIPAY package.

    //    $payer = new Payer();
    //    $payer->setPaymentMethod('paypal');

    //    $items = new Item();
    //    $items->setName('Watch')
    //          ->setCurrency('USD')
    //          ->setQuantity(1)
    //          ->setPrice($req->amount);
    
    //   $itemList = new ItemList();
    //   $itemList->setItems([$items]);

    //   $amount = new Amount();
    //   $amount->setCurrency('USD')
    //         ->setTotal($req->amount);
    
    //  $transaction = new Transaction();
    //  $transaction->setAmount($amount)
    //              ->setItemList($itemList)
    //              ->setDescription('Test');

    // $redirectUrls = new RedirectUrls();
    // $redirectUrls->setReturnUrl(route('status'))
    //             ->setCancelUrl(route('status'));

    // $payment = new Payment();
    // $payment ->setIntent('sale')
    //          ->setPayer($payer)
    //          ->setRedirectUrls($redirectUrls)
    //          ->setTransactions(array($transaction));

    // try {
    //     $payment->create($this->apiContext);

    // } catch (\PayPal\Exception\PPConnectionException $th) {
    //     if(\Config::get('app.debug')){
    //         \Session::put('error','Connection Timeout');
    //          return redirect()->route('paywithpaypal');
    //     }
    //     else{
    //         \Session::put('error','Some Error Occured');
    //          return redirect()->route('paywithpaypal');
    //     }
       
    // }
    // foreach($payment->getLinks() as $link) {
    //     if($link->getRel() == 'approval_url') {
    //         $redirect_url = $link->getHref();
    //         break;
    //     }
    }
    
    // Session::put('paypal_payment_id', $payment->getId());

    // if(isset($redirect_url)) {            
    //     return Redirect::away($redirect_url);
    // }

    // \Session::put('error','Unknown error occurred');
    // return Redirect::route('paywithpaypal');
    // }
    // public function paymentStatus(Request $request)
    // {
    //     $payment_id = Session::get('paypal_payment_id');

    //     Session::forget('paypal_payment_id');
    //     if (empty($request->input('PayerID')) || empty($request->input('token'))) {
    //         \Session::put('error','Payment failed');
    //         return Redirect::route('paywithpaypal');
    //     }
    //     $payment = Payment::get($payment_id, $this->apiContext);        
    //     $execution = new PaymentExecution();
    //     $execution->setPayerId($request->input('PayerID'));        
    //     $result = $payment->execute($execution, $this->apiContext);
        
    //     if ($result->getState() == 'approved') {         
    //         \Session::put('success','Payment success !!');
    //         return Redirect::route('paywithpaypal');
    //     }

    //     \Session::put('error','Payment failed !!');
	// 	return Redirect::route('paywithpaypal');
    // }

    public function success(Request $request)
    {
        if ($request->input('paymentId') && $request->input('PayerID')) {
            $transaction = $this->gateway->completePurchase(array(
                'payer_id' => $request->input('PayerID'),
                'transactionReference' => $request->input('paymentId')
            ));

            $response = $transaction->send();

            if ($response->isSuccessful()) {

                $arr = $response->getData();

                $payment = new Payment();
                $payment->payment_id = $arr['id'];
                $payment->payer_id = $arr['payer']['payer_info']['payer_id'];
                $payment->payer_email = $arr['payer']['payer_info']['email'];
                $payment->amount = $arr['transactions'][0]['amount']['total'];
                $payment->currency = env('PAYPAL_CURRENCY');
                $payment->payment_status = $arr['state'];

                $payment->save();
                
                \Session::put('success','Payment success !!Your Transaction Id is : " '. $arr['id']);
                return Redirect::route('paywithpaypal');

            }
            else{
                return $response->getMessage();
            }
        }
        else{
            return 'Payment declined!!';
        }
    }

    public function error()
    {
        return 'User declined the payment!';   
    }

}
