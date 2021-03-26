<?php
/**
 * This file is part of the Laravel Interswitch package
 * (c) Interswitch Group | 2021
 */

 return [
     /**
      * Current Environment ('TEST' or 'LIVE')
      */

      'env' => env('INTERSWITCH_ENV', 'TEST'),
      
     /**
      * ISO 4217 code of the currency being used. Naira(566) is the default
      */
      'currency' => env('INTERSWITCH_CURRENCY', 566),

      /**
       * Redirect url after a successful transaction
       */
      'redirectURL' => env('INTERSWITCH_REDIRECT_URL'),

      /**
       * Pay Item ID has gotten from the Quickteller Business dashboard
       */
      'payItemID' => env('INTERSWITCH_PAY_ITEM_ID'),

      /**
       * Merchant Code has gotten from the Quickteller Business dashboard
       */
      'merchantCode' => env('INTERSWITCH_MERCHANT_CODE')

 ];