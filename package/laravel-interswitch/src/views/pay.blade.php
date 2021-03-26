<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Interswitch | Quickteller Business</title>
    </head>

    <body>
        <h4>Loading...</h4>
        <form
            action="{{ $transactionData['initializationURL'] }}"
            method="post"
        >
            <input
                name="site_redirect_url"
                value="{{ $transactionData['callbackURL'] }}"
            />
            <input
                name="pay_item_id"
                value="{{ $transactionData['payItemID'] }}"
            />
            <input
                name="txn_ref"
                value="{{ $transactionData['transactionReference'] }}"
            />
            <input name="amount" value="{{ $transactionData['amount'] }}" />
            <input name="currency" value="{{ $transactionData['currency'] }}" />
            <input
                name="cust_name"
                value="{{ $transactionData['customerName'] }}"
            />
            <input
                name="pay_item_name"
                value="{{ $transactionData['payItemName'] }}"
            />
            <input name="display_mode" value="PAGE" />
            <input
                name="merchant_code"
                value="{{ $transactionData['merchantCode'] }}"
            />

            <button type="submit">Submit</button>
        </form>
    </body>
</html>
