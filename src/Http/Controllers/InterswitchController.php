<?php
/**
 * This file is part of the Laravel Interswitch package
 * (c) Interswitch Group | 2021
 */

 namespace Interswitch\Interswitch\Http\Controllers;

 use App\Http\Controllers\Controller;
 use Illuminate\Http\Request;
 use Illuminate\Support\Facades\Validator;
 use Illuminate\Support\Facades\Redirect;
 use Interswitch\Interswitch\Facades\Interswitch;

 class InterswitchController extends Controller
 {
     public function pay(Request $request)
     {
         $validator = Validator::make($request->all(), [
            'payItemName' => 'string',
            'amount' => 'required|gt:0|numeric',
            'customerName' => 'string',
            'customerID' => 'string',
            'customerEmail' => 'required|string',
            'transactionReference' => 'required|string',
            'tokeniseCard' => 'string',
            'accessToken' => 'string',
            'currency' => 'string'

         ]);

         if ($validator->fails()) {
             return $validator->errors();
         }

         $transactionData = Interswitch::initializeTransaction($request->all());
         return view('interswitch::pay', compact('transactionData'));
     }

     public function callback()
     {
         $rebuiltResponse = [
            'transactionReference' => $_POST['txnref'],
            'responseCode' => $_POST['resp'],
            'responseDescription' => $_POST['desc'],
            'paymentReference' => $_POST['payRef'],
            'returnedReference' => $_POST['retRef'],
            'cardNumber' => $_POST['cardNum'],
            'approvedAmount' => $_POST['apprAmt'],
            'amount' => $_POST['amount'],
            'mac' => $_POST['mac']
         ];
         return redirect(config('interswitch.redirectURL'))->with(['transactionData' => $rebuiltResponse]);
     }
 }
