# laravel-interswitch

> Interswitch's official laravel package to easily integrate Quickteller Business 

## Installation

[PHP](https://php.net) 7.2+ and [Composer](https://getcomposer.org) are required.

To get the latest version of Laravel Interswitch, simply require it

```bash
composer require interswitch/laravel-interswitch
```
Once installed, the package automatically registers its service provider and facade.


## Configuration
You can publish the configuration file using this command:
```bash
php artisan vendor:publish --provider="Interswitch\Interswitch\InterswitchServiceProvider"
```
A configuration file 'interswitch.php' with some defaults is placed in your config directory.


## Payment Flow
The payment flow is described below:

1. User clicks a button to make payment, the user is redirected to the payment page, usually by submitting a form with hidden fields.
2. On the payment page, card details are entered.
3. The user is redirected back with details of the transaction indicating a successful or failed transaction.

## Usage

### Test Environment

### 1. Open .env and add:
```php
INTERSWITCH_REDIRECT_URL="${APP_URL}/response"
INTERSWITCH_PAY_ITEM_ID = 
INTERSWITCH_MERCHANT_CODE = 
```
'response' as indicated above could be anything. The specified value indicates the url the user is redirected to after every transaction.
Don't forget to add this route in your project. In this case, it will be:
```php
 Route::post('response', function(){
  return $_POST;
 });
```

**INTERSWITCH_PAY_ITEM_ID** and **INTERSWITCH_MERCHANT_CODE** can be gotten from your Quickteller Business dashboard.
Note: please ensure APP_URL is correctly defined. A wrong value will result in unexpected behaviour.

### 2. Create payment route and view
Create your payment route in web.php. Something like: 
```php
Route::get('pay', function(){
  return view('payment');
});
```
Then create the view. In this case, 'payment.blade.php'. The view can be like so:
```html
<form action="interswitch-pay" method="post">
    <input type="hidden" name="customerName" value="John Doe" />
    <input type="hidden" name="payItemName" value="Suya" />
    <input type="hidden" name="amount" value="15000" />
    <!-- Amount must be in kobo-->
    <button
        type="submit"
        style="
            padding: 12px 22px;
            background-color: #c80e0e;
            border: none;
            color: #fff;
            font-size: 1em;
            border-radius: 5px;
        "
    >
        Proceed to payment page
    </button>
</form>
```
**Note: 'amount' field must be in kobo**

Navigate to your newly created route, click the 'Pay Now' button and follow the required steps. 
Note that the form is submitted to the route 'interswitch-pay', this is predefined in the package.
All the fields are required. On clicking the 'Pay Now' button, the user is redirected to interswitch's payment page, where card details are entered. The user is then redirected back to your website as indicated by 'INTERSWITCH_REDIRECT_URL'.
This url will return the result of the transaction. Sample response will be like so:
```php
{
  "paymentReference": "FBN|WEB|CDEM|10-12-2020|383104",
  "responseCode": "00",
  "responseDescription": "Approved Successful",
  "amount": "12000",
  "transactionDate": "2020-12-10T15:59:37.827",
  "customerEmail": "johndoe@nomail.com",
  "customerName": "John Doe"
}
```
A list of test cards [can be found here](https://sandbox.interswitchng.com/docbase/docs/webpay/test-cards).


### Live Environment
To go live, switch to the live environment on your Quickteller Business dashboard by clicking the 'switch' button at the top right corner of the dashboard. Replace **INTERSWITCH_PAY_ITEM_ID** and **INTERSWITCH_MERCHANT_CODE** with the new values. Also add the following to **.env**:
```php
INTERSWITCH_ENV=LIVE
```

 ## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.






