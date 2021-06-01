<?php 
$stripeSettings = json_decode(get_option("magicform_stripe_settings"));
\Stripe\Stripe::setApiKey($stripeSettings->secretKey);

$totalAmount = 0;
foreach ($formData as $field_id => $field_value) {
    if($field_id == $action->payload->totalAmount){
        if(strpos($field_id, "productList")!==false) {
            $totalAmount = floatval($field_value["total"]);
        }else if(gettype($field_value) == "array") {
            $totalAmount = floatval($field_value['value']);
        }else if(gettype($field_value) == "object"){
          $totalAmount = floatval($field_value->value);
        }else {
          $totalAmount = floatval($field_value);
        }
    }
}
$returnUrl = (strpos($pageUrl,"?")===false) ? $pageUrl."?" : $pageUrl;

$session = \Stripe\Checkout\Session::create([
  'payment_method_types' => ['card'],
  'line_items' => [[
    'price_data' => [
      'currency' => $action->payload->currency, 
      'product_data' => [
        'name' => 'Custom Payment',
      ],
      'unit_amount' => $totalAmount * 100,
    ],
    'quantity' => 1,
  ]],
  'mode' => 'payment',
  'success_url' => $returnUrl.'&session_id={CHECKOUT_SESSION_ID}&success=1&submission_id='.$submission_id,
  'cancel_url' => $returnUrl.'&session_id={CHECKOUT_SESSION_ID}$&success=0&submission_id='.$submission_id,
]);

if(!empty($session->id)){
    $stripeSessionId = $session->id;
    return true;
}
return false;

