<?php 
	$demo_control = apply_filters("magicform_demo_check_license","notdemo",10,3);
	if($demo_control !== "demo"){
		$mailchimpSettings = json_decode(get_option("magicform_mailchimp_settings")); 
	}?>
<h4><?php esc_html_e("MailChimp Settings", "magicform") ?></h4>
<div class="mf-result"></div>
<form name="mf-mailchimp-settings-form" action-name="magicform_save_mailchimp_settings">
	<div class="form-group row">
		<label for="mf-smtp" class="col-sm-12 col-form-label"><?php esc_html_e("MailChimp Api Key", "magicform") ?>:</label>
		<div class="col-sm-12">
			<input type="text" class="mf-admin-form-control" name="apikey" value="<?php echo esc_attr(magicform_password_view(isset($mailchimpSettings) ? $mailchimpSettings->apikey : "")); ?>" id="mf-mailchimp-api-key" placeholder="<?php esc_attr_e("Api Key", "magicform") ?>">
			<small class="form-text text-muted">
				<?php printf(
					wp_kses(
						__("You can get api key from <a href='%s' target='_blank'>here</a>", "magicform"),
						array('a' => array('href' => array(), 'title' => array()))
					),
					"https://mailchimp.com/help/about-api-keys/#Find-or-Generate-Your-API-Key"
				); ?>
			</small>
		</div>
	</div>
</form>
<button form-name="mf-mailchimp-settings-form" class="mf-admin-btn mf-admin-btn-blue mf-btn-settings-save">
	<i class="far fa-check-circle"></i>
	<?php esc_html_e("Save Settings", "magicform") ?>
</button>