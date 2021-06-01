<?php 
	$demo_control = apply_filters("magicform_demo_check_license","notdemo",10,3);
	if($demo_control !== "demo"){
		$paypalSettings = json_decode(get_option("magicform_paypal_settings"));
	 } ?>
<h4><?php esc_html_e("Paypal Settings", "magicform") ?></h4>
<div class="mf-result"></div>
<form name="mf-paypal-api-form" action-name="magicform_save_paypal_api_key">
	<div class="form-group">
		<label class="col-form-label"><?php esc_html_e("Paypal Client Secret Key", "magicform") ?>:</label>
		<input type="text" class="mf-admin-form-control" name="clientSecret" value="<?php echo esc_attr(magicform_password_view(isset($paypalSettings) ? $paypalSettings->clientSecret : "")); ?>" id="mf-secret-key" placeholder="<?php esc_attr_e("Secret Key", "magicform") ?>">
		<small class="form-text text-muted">
			<?php printf(wp_kses(__("You can get api keys from <a href='%s' target='_blank'>here</a>", "magicform"), array('a' => array('href' => array(), 'title' => array()))), "https://paypal.com"); ?>
		</small>
	</div>
	<div class="form-group">
		<label class="col-form-label"><?php esc_html_e("Paypal Client Id", "magicform") ?>:</label>
		<input type="text" class="mf-admin-form-control" name="clientId" value="<?php echo esc_attr(magicform_password_view(isset($paypalSettings) ? $paypalSettings->clientId : "")); ?>" id="mf-publishable-key" placeholder="<?php esc_attr_e("Client Id", "magicform") ?>">
	</div>
	<div class="form-group row">
			<label for="mf-number" class="col-sm-12 col-form-label"><?php esc_html_e("Payment Type", "magicform"); ?>:</label>
			<div class="col-sm-12">
				<div class="mf-custom-control mf-custom-control-inline mf-custom-radio">
					<input class="mf-custom-control-input" <?php echo (isset($paypalSettings) && ($paypalSettings->paymentType == "live" && isset($paypalSettings->paymentType))) ? "checked" : "" ?> type="radio" value="live" name="paymentType" id="mf-live">
					<label class="mf-custom-control-label" for="mf-live"><?php esc_html_e("Live", "magicform"); ?></label>
				</div>
				<div class="mf-custom-control mf-custom-control-inline mf-custom-radio">
					<input class="mf-custom-control-input" <?php echo (isset($paypalSettings) && ($paypalSettings->paymentType == "sandbox")) ? "checked" : "" ?> type="radio" value="sandbox" name="paymentType" id="mf-sandbox">
					<label class="mf-custom-control-label" for="mf-sandbox"><?php esc_html_e("Sandbox", "magicform"); ?></label>
				</div>
				<small class="form-text text-muted">
					<?php printf(wp_kses(__("You can more information <a href='%s' target='_blank'>here</a>", "magicform"), array('a' => array('href' => array(), 'title' => array()))), "https://developer.paypal.com/docs/business/test-and-go-live/"); ?>
				</small>
			</div>
		</div>
</form>
<button form-name="mf-paypal-api-form" class="mf-admin-btn mf-admin-btn-blue mf-btn-settings-save">
	<i class="far fa-check-circle"></i>
	<?php esc_html_e("Save Settings", "magicform") ?>
</button>