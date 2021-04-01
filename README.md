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
- **Transfer(Virtual Accounts) Option:** The user can pay via a bank transfer either using their financial/bank app or USSD to the dynamic account number displayed to complete the transaction.
- **QR Option:** The user can scan the QR displayed on the payment page using their bank app to complete the transaction.
- **USSD Option:** 
    - The user selects a bank
    - A USSD code is generated 
    - User types the USSD code on their mobile device and follows the prompt. 
- **Verve Wallet:** The user can login to their verve wallet if they have one
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
  return Session::get('transactionData');
 });
```
The details of the transaction is returned as a flash message.

To get your **INTERSWITCH_PAY_ITEM_ID** and **INTERSWITCH_MERCHANT_CODE**,
visit https://business.quickteller.com/developertools


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
    <input type="hidden" name="customerEmail" value="johndoe@nomail.com" />
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

#### Supported parameters ####
Below is a list of all the supported parameters. These parameters can be added in your form:

| Parameters           | Data Type                 | Required | Description                                                                                                                                                                                                                                         |
|----------------------|---------------------------|----------|-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| customerEmail        | string                    | true     | The email of the person making the payment.                                                                                                                                                                                                         |
| amount               | string                    | true     | The cost of the item being paid for in kobo.                                                                                                                                                                                                        |
| transactionReference | string                    | true    | This is a unique reference string required for every transaction. The package generates this automatically. If you will like to generate yours, you can add the field and create your custom method for generating a custom string per transaction. |
| currency             | string                    | false    | The ISO code of the currency being used. If this field is not added, the currency naira is assumed.                                                                                                                                                 |
| customerName         | string                    | false    | The name of the person making the payment.                                                                                                                                                                                                          |
| customerID           | string                    | false    | The ID of the person making the payment.                                                                                                                                                                                                            |
|payItemName            | string                    | false   | The name of the item being paid for. |                                                                                   |                                                                                     |

#### Futher Steps: ####
- Navigate to your newly created route, click the 'Pay Now' button and follow the required steps. 
- Note that the form is submitted to the route 'interswitch-pay', this is predefined in the package. All the fields are required. 
- On clicking the 'Pay Now' button, the user is redirected to interswitch's payment page. Choose a payment option and follow the steps. 
- The user is then redirected back to your website as indicated by 'INTERSWITCH_REDIRECT_URL'.
- This url will return the result of the transaction. Sample response will be like so:
```php
{
paymentReference: "FBN|WEB|MX26070|31-03-2021|3511400|927085",
responseCode: "00",
responseDescription: "Approved by Financial Institution",
amount: "15000",
transactionDate: "2021-03-31T08:45:31",
merchantReference: "y84KWu1617176725"
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
| customerEmail        | string                    | true     | The email of the person making the payment.                                                                                                                                                                                                         |
| amount               | string                    | true     | The cost of the item being paid for in kobo.                                                                                                                                                                                                        |
| transactionReference | string                    | false    | This is a unique reference string required for every transaction. The package generates this automatically. If you will like to generate yours, you can add the field and create your custom method for generating a custom string per transaction. |
| currency             | string                    | false    | The ISO code of the currency being used. If this field is not added, the currency naira is assumed.                                                                                                                                                 |
| customerName         | string                    | false    | The name of the person making the payment.                                                                                                                                                                                                          |
| customerID           | string                    | false    | The ID of the person making the payment.                                                                                                                                                                                                            |
|payItemName            | string                    | false   | The name of the item being paid for. |
| tokeniseCard         | string("true" or "false") | false    | Flag to indicate whether you want the customer's card to be tokenised, a tokenised value would be returned when you requery to confrim the transaction status.                                                                                      |                                                                                     |

#### Handling the Response ####
For integrity purpose, a server side request is needed to get the final status of a transaction before giving value. The package already handles this.
After a transaction is completed, a request is made to the 'transaction requery' endpoint (/collections/api/v1/gettransaction.json).
It returns a JSON object containing the status of the transaction.
Consider the sample response below: 

```php
{
paymentReference: "FBN|WEB|MX26070|31-03-2021|3511400|927085",
responseCode: "00",
responseDescription: "Approved by Financial Institution",
amount: "15000",
transactionDate: "2021-03-31T08:45:31",
merchantReference: "y84KWu1617176725"
}
```
**responseCode** '00' indicates a successful transaction.
**responseDescription** gives a full description of the transaction status.
**amount** indicates the total amount transacted in kobo.
**paymentReference** indicates the transaction reference provided by you or the package as the case may be.
**merchantReference** indicates the transaction reference generated by the payment gateway.

There are quite a number of response codes that can be returned, the full list can be viewed [here](https://sandbox.interswitchng.com/docbase/docs/webpay/response-codes/)
### Live Environment
To go live, switch to the live environment on your Quickteller Business dashboard by clicking the 'switch' button at the top right corner of the dashboard. Replace **INTERSWITCH_PAY_ITEM_ID** and **INTERSWITCH_MERCHANT_CODE** with the new values. Also add the following to **.env**:
```php
INTERSWITCH_ENV=LIVE
```

 ## License 
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.






