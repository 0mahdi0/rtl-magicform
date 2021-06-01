<?php
$payload = $item->payload;
$id = $item->id;
?>
<div class='mf-form-group-<?php echo esc_attr($id); ?>'>
    <div class='mf-form-group '>
        <?php magicform_getInputLabel($payload,strpos($themeName,"material") === 0) ?>
        <div class="mf-input-container">
            <?php magicform_getInputDescription($payload, "top") ?>
            <?php echo strpos($themeName,"material") === 0 ? '<div class="mf-material-container ' . ($payload->defaultValue != "" ? "mf-active" : "") . ' ' . ($payload->inputIcon != "" ? "mf-with-icon" : "") . ' ">' : '' ?>
            <?php magicform_getInputIconStart($payload) ?>
            <input type="email" name="<?php echo esc_attr($id); ?>_email" value="<?php echo esc_attr($payload->defaultValue) ?>" <?php echo ($payload->readOnly) ? "readonly" : "" ?>  <?php echo ($payload->disabled) ? "disabled" : "" ?>  placeholder="<?php echo esc_attr($payload->placeholder) ?>" value="<?php echo esc_attr($payload->defaultValue) ?>" id="<?php echo esc_attr($id); ?>" class="mf-form-control <?php echo esc_attr($fieldSizes[$payload->fieldSize]); ?> <?php echo esc_attr($fieldWidths[$payload->fieldWidth])?> <?php echo esc_html(implode(" ", $payload->cssClasses)) ?>" />
            <?php echo strpos($themeName,"material") === 0 ? '<span class="mf-material-label">' .esc_html( $payload->labelText) . '</span>' : ''; ?>
            <?php magicform_getInputIconEnd($payload);  ?>
            <?php echo strpos($themeName,"material") === 0 ? '</div>' : '' ?>
            <?php magicform_getInputDescription($payload, "bottom") ?>
            <div class="mf-error"></div>
        </div>
    </div>

    <?php     // Confirm Email
    if ($payload->confirmInput) : ?>
        <div class='mf-form-group '>
            <?php // labelText basıldığı için overwrite ediyoruz.
            $payload->labelText = $payload->confirmLabelText; ?>
            <?php magicform_getInputLabel($payload,strpos($themeName,"material") === 0) ?>
            <div class="mf-input-container">
                <?php echo strpos($themeName,"material") === 0 ? '<div class="mf-material-container ' . ($payload->defaultValue != "" ? "mf-active" : "") . ' ' . ($payload->inputIcon != "" ? "mf-with-icon" : "") . ' ">' : '' ?>
                <?php magicform_getInputIconStart($payload) ?>
                <input type="email" name="<?php echo esc_attr($id); ?>_confirm" value="<?php echo esc_attr($payload->defaultValue) ?>" <?php echo ($payload->readOnly) ? "readonly" : "" ?> placeholder="<?php echo esc_attr($payload->placeholder) ?>" value="<?php echo esc_attr($payload->defaultValue) ?>" id="<?php echo esc_attr($id); ?>_confirm" class="mf-form-control <?php echo esc_attr($fieldSizes[$payload->fieldSize]); ?> <?=esc_attr($fieldWidths[$payload->fieldWidth])?> <?php echo esc_html(implode(" ", $payload->cssClasses)) ?>" />
                <?php echo strpos($themeName,"material") === 0 ? '<span class="mf-material-label">' . esc_html($payload->confirmLabelText) . '</span>' : ''; ?>
                <?php magicform_getInputIconEnd($payload); ?>
                <?php echo strpos($themeName,"material") === 0 ? '</div>' : '' ?>
            </div>
            <div class="mf-error"></div>
        </div>
    <?php endif; ?>
</div>