<?php

if (!function_exists("magicform_getGdprText")) {
    function magicform_getGdprText($gdprText, $formId)
    {
        if (preg_match("/{privacy_policy}/", $gdprText)) {
            $pp_id = get_option('wp_page_for_privacy_policy');
            $privacy_policyLink = "<a target='_blank' href='" .esc_url(get_site_url() . "/?page_id=" . $pp_id) . "'>".__("Privacy Policy","magicform")."</a>  ";
            $gdprText = preg_replace("/{privacy_policy}/", $privacy_policyLink, $gdprText);
        }
?>
        <div class="mf-form-group">
            <div class="mf-custom-control mf-custom-control-inline mf-custom-checkbox">
                <input type="checkbox" name="mf-gdpr" id="mf-gdpr-<?php echo esc_attr($formId) ?>" class="mf-gdpr-id mf-custom-control-input" />
                <label class="mf-custom-control-label" for="mf-gdpr-<?php echo esc_attr($formId) ?>"><?php echo wp_kses($gdprText,array("a"=>array("href"=>array(),"target"=>array(),"title"=>array()))) ?></label>
            </div>
            <div class="mf-error"></div>
        </div>
<?php }
}
