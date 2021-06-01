<?php
if (!function_exists("magicform_getInputIconStart")) {
    function magicform_getInputIconStart($payload)
    {
        if (isset($payload->inputIcon) && $payload->inputIcon != "") : ?>
            <div class="mf-input-group">
                <div class='mf-input-group-prepend'>
                    <span class='mf-input-group-text'>
                        <i class="fas fa-<?=esc_attr($payload->inputIcon) ?>"></i>
                    </span>
                </div>
            <?php endif;
    }
}

if (!function_exists("magicform_getInputIconEnd")) {
    function magicform_getInputIconEnd($payload)
    {
        if (isset($payload->inputIcon) && $payload->inputIcon != "") : ?>
            </div>
<?php endif;
    }
}
