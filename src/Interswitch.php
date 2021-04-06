<?php
/**
 * This file is part of the Laravel Interswitch package
 * (c) Interswitch Group | 2021
 */

 namespace Interswitch\Interswitch;

 use Interswitch\Interswitch\Exceptions\ConfirmTransactionException;

 class Interswitch
 {
     /**
      * The current environment (test or live)
      */
     private $env;

     /**
      * Redirect URL after transaction is completed
      */
     private $redirectURL;

     private $callbackURL;

     /**
      * Unique reference for the transaction
      */
     private $transactionReference;

     /**
      * The currency being used. Naira is the default
      */
     private $currency;

     private $initializationBaseURL;

     /**
      * The URL the user is redirected to to make payment
      */
     private $initializationURL;

     /**
      * The URL to verifiy the status of a transaction
      */
     private $transactionStatusURL;

     /**
      * Name of the item being paid for
      */
     private $payItemName;

     /**
      * Pay Item ID can be gotten from the quickteller business dashboard
      */
     private $payItemID;

     /**
      * Merchange Code can be gotten from the quickteller business dashboard
      */
     private $merchantCode;


     public function __construct()
     {
         $this->env = config('interswitch.env');
         $this->redirectURL = config('interswitch.redirectURL');
         $this->callbackURL = config('app.url') . '/interswitch-callback';
         $this->transactionReference = config('interswitch.transactionReference');
         $this->initializationBaseURL = (strtolower($this->env) === 'live') ?
                'https://webpay.interswitchng.com' :
                'https://qa.interswitchng.com';
         $this->initializationURL = $this->initializationBaseURL . '/collections/w/pay';
         $this->payItemID = config('interswitch.payItemID');
         $this->merchantCode = config('interswitch.merchantCode');
     }

     /**
      * This method gets all the required data to be supplied to the Interswitch Payment Page
      */
     public function initializeTransaction($request)
     {
         $request = (object) $request;
         $transactionData =  [
            'transactionReference' => $request->transactionReference,
            'merchantCode' => $this->merchantCode,
            'payItemID' => $this->payItemID,
            'payItemName' => isset($request->payItemName) ? $request->payItemName : null,
            'amount' => $request->amount,
            'callbackURL' => $this->callbackURL,
            'currency' => isset($request->currency) ? $request->currency : 566,
            'customerName' => isset($request->customerName) ? $request->customerName : null,
            'customerEmail' => isset($request->customerEmail) ? $request->customerEmail : null,
            'customerID' => isset($request->customerID) ? $request->customerID : null,
            'initializationURL' =>  $this->initializationURL,
            'tokeniseCard' => isset($request->tokeniseCard) ? $request->tokeniseCard : 'false',
            'accessToken' => isset($request->accessToken) ? $request->accessToken : 'false'
         ];

         return $transactionData;
     }


     /**
      * Overload the 'confirmTransaction' method.
      */
     public function __call($name, $arg)
     {
         if ($name == 'confirmTransaction') {
             switch (count($arg)) {
                case 0:
                    return $this->getTransactionResponse();
                
                case 2:
                    return $this->requeryTransaction($arg[0], $arg[1]);

                default:
                    throw new ConfirmTransactionException("Wrong implemention of the method ConfirmTransaction");

             }
         }
     }

     public function requeryTransaction($transactionReference, $amount)
     {
         $queryString = '?merchantcode=' . $this->merchantCode .
                            '&transactionreference=' . $transactionReference .
                            '&amount=' . $amount;
                            
         $this->transactionStatusURL = $this->initializationBaseURL .
                                        '/collections/api/v1/gettransaction.json' . $queryString;
        
         $curl = curl_init();
         curl_setopt_array($curl, array(
         CURLOPT_URL => $this->transactionStatusURL,
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_CUSTOMREQUEST => "GET",
         CURLOPT_SSL_VERIFYHOST => 2,
         CURLOPT_SSL_VERIFYPEER => false,
         CURLOPT_TIMEOUT => 60,
         CURLOPT_POST => false,
         CURLOPT_HTTPHEADER => [
             "content-type: application/json",
             "cache-control: no-cache",
             "Connection: keep-alive"
             ],
         ));
       
         $response = json_decode(curl_exec($curl), true);

         $rebuiltResponse = [
            'paymentReference' => $response['PaymentReference'],
            'responseCode' => $response['ResponseCode'],
            'responseDescription' => $response['ResponseDescription'],
            'amount' => $response['Amount'],
            'transactionDate' => $response['TransactionDate'],
            'merchantReference' => $response['MerchantReference'],
        ];

         return $rebuiltResponse;
     }

     public function getTransactionResponse()
     {
         return \Illuminate\Support\Facades\Session::get('transactionData');
     }


     /**
      * This method is deprecated. It was used when the final response was
      * in form a query string.
      */
     public function attachQueryString($rebuiltResponse)
     {
         $queryString = '/?';
         foreach ($rebuiltResponse as $key => $response) {
             $queryString .= $key . '=' . $response . '&';
         }

         /**
          * Form the complete url and remove the last character which is '&'
          */
         return substr($this->redirectURL . $queryString, 0, -1);
     }

     /**
      * This method is deprecated. Merchant should generate their own transaction reference.
      */
     private function generateTransactionReference()
     {
         $length = 6;
         $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
         $charactersLenth = strlen($characters);
         $generatedTransactionReference = '';
         for ($i = 0; $i < $length; $i++) {
             $generatedTransactionReference .= $characters[rand(0, $charactersLenth - 1)];
         }
         return $generatedTransactionReference . time();
     }
 }