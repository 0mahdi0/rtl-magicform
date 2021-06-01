<?php
$payload = $item->payload;
$id = $item->id;
?>
<div class='mf-form-group mf-form-group-<?php echo esc_attr($id); ?>'>
    <?php magicform_getInputLabel($payload, strpos($themeName, "material") === 0) ?>
    <div class="mf-input-container">
        <?php magicform_getInputDescription($payload, "top") ?>
        <?php echo strpos($themeName, "material") === 0 ? '<div class="mf-material-container ' . ($payload->defaultValue != "" ? "mf-active" : "") . ' ' . ($payload->inputIcon != "" ? "mf-with-icon" : "") . ' ">' : '' ?>
        <?php magicform_getInputIconStart($payload) ?>
        <input <?php echo ($payload->disabled) ? "disabled" : "" ?> <?php echo ($payload->readOnly) ? "readonly" : "" ?> type="number" name="<?php echo esc_attr($id); ?>" min="<?= esc_attr($payload->minValue) ?>"  max="<?php echo esc_attr($payload->maxValue) ?>" value="<?php echo esc_attr($payload->defaultValue) ?>" <?php echo ($payload->readOnly) ? "readonly" : "" ?> placeholder="<?php echo esc_attr($payload->placeholder) ?>" id="<?php echo esc_attr($id); ?>" class="mf-form-control <?php echo esc_attr($fieldSizes[$payload->fieldSize]) ?> <?php echo esc_attr($fieldWidths[$payload->fieldWidth]); ?> <?php echo esc_html(implode(" ", $payload->cssClasses)) ?>" /> 
        <?php echo strpos($themeName, "material") === 0 ? '<span class="mf-material-label">' . esc_html($payload->labelText) . '</span>' : ''; ?>
        <?php magicform_getInputIconEnd($payload) ?>
        <?php echo strpos($themeName, "material") === 0 ? '</div>' : '' ?>
        <div class="mf-error"></div>
        <?php magicform_getInputDescription($payload, "bottom") ?>
    </div>
</div>