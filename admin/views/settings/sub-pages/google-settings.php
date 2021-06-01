<?php $demo_control = apply_filters("magicform_demo_check_license","notdemo",10,3);
	if($demo_control !== "demo"){
		$googleSettings = json_decode(get_option("magicform_google_settings"));
	} ?>
<h1><?php esc_html_e("Google Map", "magicform") ?></h1>
<div class="mf-result"></div>
<form name="mf-google-settings-form" action-name="magicform_save_google_settings">
	<div class="form-group row">
		<label for="mf-smtp" class="col-sm-12 col-form-label"><?php esc_html_e("Google Api Key", "magicform") ?>:</label>
		<div class="col-sm-12">
			<input type="text" class="mf-admin-form-control" name="googleApiKey" value="<?php echo esc_attr(magicform_password_view(isset($googleSettings) ? $googleSettings->googleApiKey : "")); ?>" id="mf-google-api-key" placeholder="<?php esc_attr_e("Api Key", "magicform") ?>">
			<small class="form-text text-muted">
				<?php printf(
					wp_kses(
						__("You can get api key from <a href='%s' target='_blank'>Google Map</a>", "magicform"),
						array('a' => array('href' => array(), 'title' => array()))
					),
					"https://developers.google.com/maps/documentation/javascript/get-api-key"
				); ?>
				</a>
			</small>
		</div>
	</div>
</form>
<button form-name="mf-google-settings-form" class="mf-admin-btn mf-admin-btn-blue mf-btn-settings-save">
	<i class="far fa-check-circle"></i>
	<?php esc_html_e("Save Settings", "magicform") ?>
</button>