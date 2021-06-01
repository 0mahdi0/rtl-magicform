<?php
	$demo_control = apply_filters("magicform_demo_check_license","notdemo",10,3);
	if($demo_control !== "demo"){ 
	 	$stripeSettings = json_decode(get_option("magicform_stripe_settings"));
	} ?>
<h4><?php esc_html_e("Stripe Settings", "magicform") ?></h4>
<div class="mf-result"></div>
<form name="mf-stripe-api-form" action-name="magicform_save_stripe_api_key">
	<div class="form-group">
		<label class="col-form-label"><?php esc_html_e("Stripe Secret Key", "magicform") ?>:</label>
		<input type="text" class="mf-admin-form-control" name="secretKey" value="<?php echo esc_attr(magicform_password_view(isset($stripeSettings) ? $stripeSettings->secretKey : "")); ?>" id="mf-secret-key" placeholder="<?php esc_attr_e("Secret Key", "magicform") ?>">
		<small class="form-text text-muted">
			<?php printf(wp_kses(__("You can get api keys from <a href='%s' target='_blank'>here</a>", "magicform"), array('a' => array('href' => array(), 'title' => array()))), "https://stripe.com/docs/keys"); ?>
		</small>
	</div>
	<div class="form-group">
		<label class="col-form-label"><?php esc_html_e("Stripe Publishable Key", "magicform") ?>:</label>
		<input type="text" class="mf-admin-form-control" name="publishableKey" value="<?php echo esc_attr(magicform_password_view(isset($stripeSettings) ? $stripeSettings->publishableKey : "")); ?>" id="mf-publishable-key" placeholder="<?php esc_attr_e("Publishable Key", "magicform") ?>">
	</div>
</form>
<button form-name="mf-stripe-api-form" class="mf-admin-btn mf-admin-btn-blue mf-btn-settings-save">
	<i class="far fa-check-circle"></i>
	<?php esc_html_e("Save Settings", "magicform") ?>
</button>