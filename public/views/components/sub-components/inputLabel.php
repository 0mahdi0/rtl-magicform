<?php 
if (!function_exists("magicform_getInputLabel")) {
function magicform_getInputLabel($payload,$isMaterial=false)
{
    if ($payload->labelDisplay == "show" && !$isMaterial) : ?>
        <label class="mf-label mf-label-<?php echo esc_attr($payload->labelPosition); ?>" style="<?php echo ($payload->labelPosition=="inline" && (isset($payload->optionsDisplay) || (isset($payload->fieldSize) && $payload->fieldSize=="small"))) ? "padding-top:0;" : ""?>">
            <?php if ($payload->labelIcon != "") : ?>
                <i class="fas fa-<?php echo esc_attr($payload->labelIcon) ?>"></i>
            <?php endif; ?>
            <?php echo esc_html($payload->labelText); ?>
            <?php if (isset($payload->required) && $payload->required): ?> <span class="mf-required">*</span> <?php endif; ?>
        </label>
<?php endif;
}
}