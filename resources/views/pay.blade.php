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
