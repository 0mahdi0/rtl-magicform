<?php
$stripeSettings = json_decode(get_option("magicform_stripe_settings"));?>
var stripe = Stripe("<?php echo $stripeSettings->publishableKey?>");
stripe.redirectToCheckout({
  sessionId: result.data.payment.sessionId
}).then(function (result) {
  console.log(result.error.message);
});
return false;
