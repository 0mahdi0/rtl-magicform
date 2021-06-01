<?php
$payload = $item->payload;
$id = $item->id;
?>
<div class='mf-form-group-<?php echo esc_attr($id); ?>'>
    <div class='mf-form-group'>
        <?php magicform_getInputLabel($payload, strpos($themeName, "material") === 0) ?>
        <div class="mf-input-container">
            <?php magicform_getInputDescription($payload, "top") ?>
            <?php echo strpos($themeName, "material") === 0 ? '<div class="mf-material-container ' . ($payload->defaultValue != "" ? "mf-active" : "") . ' ' . ($payload->inputIcon != "" ? "mf-with-icon" : "") . ' ">' : '' ?>
            <div class="mf-input-group">
                <?php if ($payload->inputIcon != "") : ?>
                    <div class='mf-input-group-prepend'>
                        <span class='mf-input-group-text'>
                            <i class="fas fa-<?php echo esc_attr($payload->inputIcon); ?>"></i>
                        </span>
                    </div>
                <?php endif; ?>
                <input maxlength="<?php echo esc_attr($payload->maxLength); ?>" <?php echo ($payload->disabled) ? "disabled" : "" ?> <?php echo ($payload->readOnly) ? "readonly" : "" ?> type="password" name="<?php echo esc_attr($id); ?>_password" value="<?php echo esc_attr($payload->defaultValue) ?>" <?php echo ($payload->readOnly) ? "readonly" : "" ?> placeholder="<?php echo esc_attr($payload->placeholder) ?>" id="<?php echo esc_attr($id); ?>" maxlength="<?php echo esc_attr($payload->maxLength) ?>" class="mf-form-control <?php echo esc_attr(implode(" ", $payload->cssClasses)) ?>" />
                <?php echo strpos($themeName, "material") === 0 ? '<span class="mf-material-label">' . esc_html($payload->labelText) . '</span>' : ''; ?>
                <?php if ($payload->viewIcon) : ?>
                    <div class="mf-input-group-append">
                        <span class="mf-input-group-text mf-view-icon-btn">
                            <i id="mf-view-icon" class="fa fa-eye"></i>
                        </span>
                    </div>
                <?php endif; ?>
            </div>
            <?php echo strpos($themeName, "material") === 0 ? '</div>' : '' ?>
            <div class="mf-error"></div>
            <?php magicform_getInputDescription($payload, "bottom") ?>
        </div>
    </div>

    <?php     // Confirm Password
    if ($payload->confirmInput) : ?>
        <div class="mf-form-group">
            <?php
            $payload->labelText = $payload->confirmLabelText; ?>
            <?php magicform_getInputLabel($payload, strpos($themeName, "material") === 0) ?>
            <div class="mf-input-container">
                <?php magicform_getInputDescription($payload, "top") ?>
                <?php echo strpos($themeName, "material") === 0 ? '<div class="mf-material-container ' . ($payload->defaultValue != "" ? "mf-active" : "") . ' ' . ($payload->inputIcon != "" ? "mf-with-icon" : "") . ' ">' : '' ?>
                <div class="mf-input-group">
                    <input maxlength="<?= $payload->maxLength ?>" type="password" name="<?php echo esc_attr($id); ?>_confirm" <?php echo ($payload->disabled) ? "disabled" : "" ?> <?php echo ($payload->readOnly) ? "readonly" : "" ?> placeholder="<?php echo esc_attr($payload->confirmPlaceholder) ?>" id="<?php echo esc_attr($id) ?>_confirm" maxlength="<?php echo esc_attr($payload->maxLength) ?>" class="mf-form-control <?php echo esc_html(implode(" ", $payload->cssClasses)) ?>" />
                    <?php if ($payload->viewIcon) : ?>
                        <div class="mf-input-group-append">
                            <span class="mf-input-group-text mf-view-icon-btn">
                                <i id="mf-view-icon" class="fa fa-eye"></i>
                            </span>
                        </div>
                    <?php endif; ?>
                </div>
                <?php echo strpos($themeName, "material") === 0 ? '</div>' : '' ?>
                <div class="mf-error"></div>
                <?php magicform_getInputDescription($payload, "bottom") ?>
            </div>
        </div>
    <?php endif; ?>
</div>