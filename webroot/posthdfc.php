<?php
error_reporting(0);
$enc_request = $_POST['encRequest'];
$accesscode = $_POST['access_code'];
?>
<form action="https://test.ccavenue.com/transaction/transaction.do?command=initiateTransaction" method="POST" name="payuForm" id="payform">
                <input type="hidden" name="encRequest" value="<?php echo $enc_request;?>" />
<input type="hidden" name="access_code" value="<?php echo $accesscode;?>" />

                <input type="submit" class="button" id="submit_payu_in_payment_form" value="Pay via HDFC" />
                <a class="button cancel" href="http://demo-gujarat.ahasolar.in/payutransfer/cancel">Cancel order &amp; restore cart</a>
                <script type="text/javascript">
                    var payuForm = document.forms.payuForm;
                    payuForm.submit();
                </script>
            </form>
