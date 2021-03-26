<?php
/**
 * This file is part of the Laravel Interswitch package
 * (c) Interswitch Group | 2021
 */

 namespace Interswitch\Interswitch\Http\Controllers;

 use App\Http\Controllers\Controller;
 use Illuminate\Http\Request;
 use Illuminate\Support\Facades\Validator;
 use Interswitch\Interswitch\Facades\Interswitch;

 class InterswitchController extends Controller
 {
     public function pay(Request $request)
     {
         $validator = Validator::make($request->all(), [
             'payItemName' => 'required|string',
             'amount' => 'required|gt:0|numeric',
             'customerName' => 'required|string',
             'customerID' => 'string'
         ]);

         if ($validator->fails()) {
             return $validator->errors();
         }

         $transactionData = Interswitch::initializeTransaction($request->all());

         return view('interswitch::pay', compact('transactionData'));
     }

     public function callback()
     {
         $response = Interswitch::queryTransaction($_POST);
         $rebuiltResponse = [
             'paymentReference' => $response['PaymentReference'],
             'responseCode' => $response['ResponseCode'],
             'responseDescription' => $response['ResponseDescription'],
             'amount' => $response['Amount'],
             'transactionDate' => $response['TransactionDate'],
             'merchantReference' => $response['MerchantReference'],
         ];

         $redirectURL = Interswitch::attachQueryString($rebuiltResponse);

         return redirect()->to($redirectURL);
     }
 }