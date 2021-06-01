<?php 
    $stripeSettings = json_decode(get_option("magicform_stripe_settings"));
    \Stripe\Stripe::setApiKey($stripeSettings->secretKey);
    $stripe =  new \Stripe\StripeClient($stripeSettings->secretKey);

    $payment = $stripe->checkout->sessions->retrieve(
        $_GET['session_id'],
        []
    );

    global $wpdb;
    $paymentStatus = false;
    $submission_id = intval($_GET['submission_id']);
    $submissions_tablename = $wpdb->prefix . "magicform_submissions";

    $intent = \Stripe\PaymentIntent::retrieve($payment->payment_intent);

    if($intent->status === "succeeded"){
        $paymentStatus = true;
        update_stripe_payment($submission_id, 1, $intent->amount);
        redirect_current_url();
    }else {
        update_stripe_payment($submission_id, 0, $intent->amount, $intent->last_payment_error );
        redirect_current_url();
    }

    function update_stripe_payment($submission_id, $status, $total_amount, $error = "") {
        global $wpdb;
        $submissions_tablename = $wpdb->prefix . "magicform_submissions";

        $wpdb->update($submissions_tablename, array(
            'payment_status' => $status,
            'total_amount' => number_format($total_amount/100,2),
            'payment_error' => $error
        ), array("id" => $submission_id));
    }

    function redirect_current_url() {
        echo "
            <script> 
                setTimeout(function(){ 
                    window.location = window.location.href.replace(/\?.*/,'');
                 }, 3000);
            </script>
        ";
    }