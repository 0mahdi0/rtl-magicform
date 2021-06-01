<?php $demo_control = apply_filters("magicform_demo_check_license","notdemo",10,3);
	if($demo_control !== "demo"){
		$sendgridSettings = json_decode(get_option("magicform_sendgrid_settings"));
	 } ?>
<h4><?php esc_html_e("SendGrid Settings", "magicform") ?></h4>
<div class="mf-result"></div>
<form name="mf-sendgrid-api-form" action-name="magicform_save_sendgrid_api_key">
	<div class="form-group">
		<label for="mf-smtp" class="col-form-label"><?php esc_html_e("SendGrid Api Key", "magicform") ?>:</label>
		<input type="text" class="mf-admin-form-control" name="apikey" value="<?php echo esc_attr(magicform_password_view(isset($sendgridSettings) ? $sendgridSettings->apikey : "")); ?>" id="mf-sendgrid-api-key" placeholder="<?php esc_attr_e("Api Key", "magicform") ?>">
		<small class="form-text text-muted">
			<?php printf(wp_kses(__("You can get api key from <a href='%s' target='_blank'>here</a>", "magicform"), array('a' => array('href' => array(), 'title' => array()))), "https://sendgrid.com/docs/ui/account-and-settings/api-keys/"); ?>
		</small>
	</div>
</form>
<button form-name="mf-sendgrid-api-form" class="mf-admin-btn mf-admin-btn-blue mf-btn-settings-save">
	<i class="far fa-check-circle"></i>
	<?php esc_html_e("Save Settings", "magicform") ?>
</button>