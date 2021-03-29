<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Interswitch | Quickteller Business</title>
</head>
<h4 class="text-center" style="margin-top: 25%">Please wait...</h4>

<body onload="document.interswitchForm.submit()">
    <form action="{{ $transactionData['initializationURL'] }}" method="post" name="interswitchForm"
        style="display:none">
        <input name="site_redirect_url" value="{{ $transactionData['callbackURL'] }}" />
        <input name="pay_item_id" value="{{ $transactionData['payItemID'] }}" />
        <input name="txn_ref" value="{{ $transactionData['transactionReference'] }}" />
        <input name="amount" value="{{ $transactionData['amount'] }}" />
        <input name="currency" value="{{ $transactionData['currency'] }}" />
        <input name="cust_name" value="{{ $transactionData['customerName'] }}" />
        <input name="cust_email" value="{{ $transactionData['customerEmail'] }}" />
        <input name="cust_id" value="{{ $transactionData['customerID'] }}" />
        <input name="pay_item_name" value="{{ $transactionData['payItemName'] }}" />
        <input name="merchant_code" value="{{ $transactionData['merchantCode'] }}" />
        <input name="tokenise_card" value="{{ $transactionData['tokeniseCard'] }}" />
        <!-- <input name="access_token" value="{{ $transactionData['accessToken'] }}" /> -->
        <input name="display_mode" value="PAGE" />
    </form>
</body>

</html>