# laravel-interswitch

[![Issues](	https://img.shields.io/github/issues/techquest/isw-laravel-sdk)](https://github.com/techquest/isw-laravel-sdk/issues)
[![Forks](	https://img.shields.io/github/forks/techquest/isw-laravel-sdk)](https://github.com/techquest/isw-laravel-sdk/network/members)
[![Stars](	https://img.shields.io/github/stars/techquest/isw-laravel-sdk)](https://github.com/techquest/isw-laravel-sdk/stargazers)

> Interswitch's official laravel package to easily integrate to Quickteller Business to collect payments.
To begin, create an account at https://business.quickteller.com if you haven't already.

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
2. On the payment page, there are multiple payment options:
- **Card Option:** The user enters card details and follows the prompt.
- **USSD Option:** 
    - The user selects a bank
    - A USSD code is generated 
    - User types the USSD code on their mobile device and follows the prompt. 
- Verve Wallet: The user can login to their verve wallet if they have one
3. The user gets a prompt indicating a successful or a failed transaction.

## Usage

### Environments
Quickteller Business provides a test and a live environment. 
The test environment allows you to test your integration without actually charging any bank account. 
After successfully testing your integration, you can easily switch to the live environment (after all required documents have been uploaded).

For the test environment, in your .env file, add:
```php
INTERSWITCH_ENV=TEST
```
For the live environment, in your .env file, add:
```php
INTERSWITCH_ENV=LIVE
```
If none is present, the test environment is assummed by default.

Furthermore, you need to add the following environment variables:
```php
INTERSWITCH_REDIRECT_URL="${APP_URL}/response"
INTERSWITCH_PAY_ITEM_ID= 
INTERSWITCH_MERCHANT_CODE= 
```
'response' as indicated above could be anything. 
The specified value indicates the url the user is redirected to after every transaction.
Don't forget to add this route in your project. In this case, it will be:
```php
 Route::get('response', function(){
  return $_GET;
 });
```

To get your **INTERSWITCH_PAY_ITEM_ID** and **INTERSWITCH_MERCHANT_CODE**,
visit https://business.quickteller.com/developertools


### Test Environment

#### Create payment route and view
Create your payment route in web.php. Something like: 
```php
Route::get('pay', function(){
  return view('payment');
});
```
Then create the view. In this case, 'payment.blade.php'. 
The view can be like so:
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
        Pay Now
    </button>
</form>
```
**Note: 'amount' field must be in kobo**

#### Futher Steps: ####
- Navigate to your newly created route, click the 'Pay Now' button and follow the required steps. 
- Note that the form is submitted to the route 'interswitch-pay', this is predefined in the package. All the fields are required. 
- On clicking the 'Pay Now' button, the user is redirected to interswitch's payment page. Choose a payment option and follow the steps. 
- The user is then redirected back to your website as indicated by 'INTERSWITCH_REDIRECT_URL'.
- This url will return the result of the transaction. Sample response will be like so:
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
#### Note: #### 
- **Please ensure APP_URL is correctly defined. A wrong value will result in unexpected behaviour.**
- To get a list of test cards, visit:
 https://developer.interswitch.com/docs/quickteller-business/web-integrations/#test-cards

#### Supported parameters ####
Below is a list of all the supported parameters. These parameters can be added in your form:

| Parameters           | Data Type                 | Required | Description                                                                                                                                                                                                                                         |
|----------------------|---------------------------|----------|-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| payItemName          | string                    | true     | The name of the item being paid for.                                                                                                                                                                                                                |
| customerName         | string                    | true     | The name of the person making the payment.                                                                                                                                                                                                          |
| amount               | string                    | true     | The cost of the item being paid for in kobo.                                                                                                                                                                                                        |
| transactionReference | string                    | false    | This is a unique reference string required for every transaction. The package generates this automatically. If you will like to generate yours, you can add the field and create your custom method for generating a custom string per transaction. |
| currency             | string                    | false    | The ISO code of the currency being used. If this field is not added, the currency naira is assumed.                                                                                                                                                 |
| customerEmail        | string                    | false    | The email of the person making the payment.                                                                                                                                                                                                         |
| customerID           | string                    | false    | The ID of the person making the payment.                                                                                                                                                                                                            |
| tokeniseCard         | string("true" or "false") | false    | Flag to indicate whether you want the customer's card to be tokenised, a tokenised value would be returned when you requery to confrim the transaction status.                                                                                      |


### Live Environment
To go live, switch to the live environment on your Quickteller Business dashboard by clicking the 'switch' button at the top right corner of the dashboard. Replace **INTERSWITCH_PAY_ITEM_ID** and **INTERSWITCH_MERCHANT_CODE** with the new values. Also add the following to **.env**:
```php
INTERSWITCH_ENV=LIVE
```

 ## License 
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.






