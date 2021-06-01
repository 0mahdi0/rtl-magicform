<?php
    $demo_control = apply_filters("magicform_demo_check_license","notdemo",10,3);
	if($demo_control !== "demo"){
        $recaptchaSettings = json_decode(get_option("magicform_recaptcha_settings")); 
    }?>
<h1><?php esc_html_e("Recaptcha Settings", "magicform") ?></h1>
<div class="mf-result"></div>
<form name="mf-recaptcha-settings-form" action-name="magicform_save_recaptcha_settings">
    <div class="form-group">
        <label for="mf-smtp" class="col-form-label"><?php esc_html_e("v2 Site Key", "magicform") ?>:</label>
        <input type="text" class="mf-admin-form-control" name="recaptchaSitev2" value="<?php echo esc_attr(magicform_password_view(isset($recaptchaSettings) ? $recaptchaSettings->recaptchaSitev2 : "")) ?>" id="mf-recaptchav2-site-key" placeholder="<?php esc_attr_e("Recapthca V2 Site Key", "magicform") ?>">
        <small class="form-text text-muted">
            <?php printf(
                wp_kses(
                    __("You can get api key from <a href='%s' target='_blank'>here</a>", "magicform"),
                    array('a' => array('href' => array(), 'title' => array()))
                ),
                "https://developers.google.com/recaptcha/intro"
            ); ?>
        </small>
    </div>
    <div class="form-group">
        <label for="mf-smtp" class="col-form-label"><?php esc_html_e("v2 Secret Key", "magicform") ?>:</label>
        <input type="text" class="mf-admin-form-control" name="recaptchaSecretv2" value="<?php echo esc_attr(magicform_password_view(isset($recaptchaSettings) ? $recaptchaSettings->recaptchaSecretv2  : "")) ?>" id="mf-recaptchav2-secret-key" placeholder="<?php esc_attr_e("Recapthca V2 Secret Key", "magicform") ?>">
    </div>
    <div class="form-group">
        <label for="mf-smtp" class="col-form-label"><?php esc_html_e("v3 Site Key", "magicform") ?>:</label>
        <input type="text" class="mf-admin-form-control" name="recaptchaSitev3" value="<?php echo esc_attr(magicform_password_view(isset($recaptchaSettings) ? $recaptchaSettings->recaptchaSitev3  : "")) ?>" id="mf-recaptcha-sitev3-api-key" placeholder="<?php esc_attr_e("Recapthca V3 Site Key", "magicform") ?>">
        <small class="form-text text-muted">
            <?php printf(
                wp_kses(
                    __("You can get api key from <a href='%s' target='_blank'>here</a>", "magicform"),
                    array('a' => array('href' => array(), 'title' => array()))
                ),
                "https://developers.google.com/recaptcha/intro"
            ); ?>
        </small>
    </div>
    <div class="form-group">
        <label for="mf-smtp" class="col-form-label"><?php esc_html_e("v3 Secret Key", "magicform") ?>:</label>
        <input type="text" class="mf-admin-form-control" name="recaptchaSecretv3" value="<?php echo esc_attr(magicform_password_view(isset($recaptchaSettings) ? $recaptchaSettings->recaptchaSecretv3  : "")) ?>" id="mf-recaptchav3-secret-api-key" placeholder="<?php esc_attr_e("Recapthca V3 Secret Key", "magicform") ?>">
    </div>
</form>
<button form-name="mf-recaptcha-settings-form" class="mf-admin-btn mf-admin-btn-blue mf-btn-settings-save">
    <i class="far fa-check-circle"></i>
    <?php esc_html_e("Save Settings", "magicform") ?>
</button>