<?php
if (!function_exists("magicform_getCustomCss")) {
    function magicform_getCustomCss($customCss)
    { ?>
        <style>
            <?php echo str_replace('\n', '', esc_js($customCss)) ?>
        </style>
<?php }
}
