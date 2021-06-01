<?php
$payload = $item->payload;
$id = $item->id;
$reCaptcha = json_decode(get_option("magicform_recaptcha_settings"));
?>
<div class='mf-form-group mf-form-group-<?php echo esc_attr($id) ?>'>

<?php if(isset($payload->reCaptchaType) && $payload->reCaptchaType == "v3" && $reCaptcha->recaptchaSitev3 && $reCaptcha->recaptchaSecretv3) {?>
    <script src="https://www.google.com/recaptcha/api.js?render=<?php echo esc_html($reCaptcha->recaptchaSitev3)?>&hl=<?=strtolower($formSettings->selectLanguage)?>"></script>
    <script>
  
        grecaptcha.ready(function() {
            grecaptcha.execute("<?php echo esc_html($reCaptcha->recaptchaSitev3)?>", {action: 'homepage'}).then(function(token) {
                jQuery('#recaptchav3').val(token);
            })
    })
    </script>
  <input type="hidden" id="recaptchav3" name="recaptchav3" val=""/>
<?php } else if(isset($payload->reCaptchaType) && $payload->reCaptchaType == 'v2' && isset($reCaptcha->recaptchaSitev2 ) && $reCaptcha->recaptchaSitev2 && $reCaptcha->recaptchaSecretv2) { ?>
<?php magicform_getInputDescription($payload, "top") ?>
    <script src='https://www.google.com/recaptcha/api.js?hl=<?=strtolower($formSettings->selectLanguage)?>'></script>
<div 
    class="g-recaptcha" 
    data-sitekey="<?php echo esc_attr($reCaptcha->recaptchaSitev2)?>" 
    data-theme="<?php echo esc_attr($payload->v2Theme)?>"
    data-size="<?php echo esc_attr($payload->v2Size)?>"
    ></div>
    <?php magicform_getInputDescription($payload, "bottom") ?>
<?php } else {?>
    <div class="alert alert-warning">
        <strong><?php esc_html_e("You have to set recaptcha api keys","magicform");?> </strong> <a target="_blank" href="<?php echo get_admin_url()?>admin.php?page=magicform_settings&sub_page=recaptcha"><?php esc_html_e("Settings Page","")?></a>
    </div>
<?php }?>
</div>