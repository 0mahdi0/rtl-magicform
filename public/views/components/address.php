<?php
$payload = $item->payload;
$id = $item->id;
?>
<div class='mf-form-group-<?php echo esc_attr($id); ?>'>
    <?php magicform_getInputLabel($payload,strpos($themeName,"material") === 0) ?>
    <div class="mf-input-container">
        <?php magicform_getInputDescription($payload, "top") ?>
        <?php if ($payload->address1Check) : ?>
            <div class="mf-form-group">
        <?php if($payload->labelDisplay == "show"):?>  <?php echo strpos($themeName,"material") === 0 ? '<div class="mf-material-container">' : '<label class="mf-label mf-label-' .esc_attr($payload->labelPosition) . '">' . esc_html($payload->address1Label) . '</label>' ?> <?php endif;?>
                <input type="text" name="<?php echo esc_attr($id); ?>_address1" <?php echo (isset($payload->readOnly) && $payload->readOnly) ? "readonly" : "" ?> <?php echo esc_attr((isset($payload->disabled) && $payload->disabled) ? "disabled" : "") ?> placeholder="<?php echo esc_attr($payload->address1Placeholder) ?>" id="<?php echo esc_attr($id); ?>_address1" class="mf-form-control <?php echo isset($payload->fieldSize) ? esc_attr($fieldSizes[$payload->fieldSize]) : "" ?>  <?php echo esc_html(implode(" ", $payload->cssClasses)) ?>" />
                <?php echo strpos($themeName,"material") === 0 ? '<span class="mf-material-label">' . esc_html($payload->address1Label) . '</span>' : ''; ?>
                <?php echo strpos($themeName,"material") === 0 ? '</div>' : '' ?>
                <div class="mf-error"></div>
            </div>
        <?php endif; ?>
        <?php if ($payload->address2Check) : ?>
            <div class="mf-form-group">
            <?php if($payload->labelDisplay == "show"):?>  <?php echo strpos($themeName,"material") === 0 ? '<div class="mf-material-container">' : '<label class="mf-label mf-label-' . esc_attr($payload->labelPosition) . '">' . esc_html($payload->address2Label) . '</label>' ?> <?php endif;?>
                <input type="text" name="<?php echo esc_attr($id); ?>_address2" <?php echo (isset($payload->readOnly) && $payload->readOnly) ? "readonly" : "" ?> <?php echo esc_attr((isset($payload->disabled) && $payload->disabled) ? "disabled" : "") ?> placeholder="<?php echo esc_attr($payload->address2Placeholder) ?>" id="<?php echo esc_attr($id); ?>_address2" class="mf-form-control <?php echo isset($payload->fieldSize) ? esc_attr($fieldSizes[$payload->fieldSize]) : "" ?> <?php echo esc_html(implode(" ", $payload->cssClasses)) ?>" />
                <?php echo strpos($themeName,"material") === 0 ? '<span class="mf-material-label">' . esc_html($payload->address2Label) . '</span>' : ''; ?>
                <?php echo strpos($themeName,"material") === 0 ? '</div>' : '' ?>
                <div class="mf-error"></div>
            </div>
        <?php endif; ?>
        <div class="mf-row">
            <?php if ($payload->cityCheck) : ?>
                <div class="mf-form-group mf-col">
                <?php if($payload->labelDisplay == "show"):?>   <?php echo strpos($themeName,"material") === 0 ? '<div class="mf-material-container">' : '<label class="mf-label mf-label-' . esc_attr($payload->labelPosition) . '">' . esc_html($payload->cityLabel) . '</label>' ?><?php endif;?>
                    <input type="text" name="<?php echo esc_attr($id); ?>_city" placeholder="<?php echo esc_attr($payload->cityPlaceholder) ?>" <?php echo esc_attr((isset($payload->disabled) && $payload->disabled) ? "disabled" : "") ?> class="mf-form-control <?php echo isset($payload->fieldSize) ? esc_attr($fieldSizes[$payload->fieldSize]) : "" ?>  <?php echo esc_html(implode(" ", $payload->cssClasses)) ?>" <?php echo (isset($payload->readOnly) && $payload->readOnly) ? "readonly" : "" ?> id="<?php echo esc_attr($id); ?>_city" />
                    <?php echo strpos($themeName,"material") === 0 ? '<span class="mf-material-label">' . esc_html($payload->cityLabel) . '</span>' : ''; ?>
                    <?php echo strpos($themeName,"material") === 0 ? '</div>' : '' ?>
                    <div class="mf-error"></div>
                </div>
            <?php endif; ?>
            <?php if ($payload->stateCheck) : ?>
                <div class="mf-form-group mf-col">
                <?php if($payload->labelDisplay == "show"):?>   <?php echo strpos($themeName,"material") === 0 ? '<div class="mf-material-container">' : '<label class="mf-label mf-label-' . esc_attr($payload->labelPosition) . '">' . esc_html($payload->stateLabel) . '</label>' ?><?php endif;?>
                    <input type="text" name="<?php echo esc_attr($id); ?>_state" placeholder="<?php echo esc_attr($payload->statePlaceholder) ?>" class="mf-form-control <?php echo isset($payload->fieldSize) ? esc_attr($fieldSizes[$payload->fieldSize]) : "" ?>  <?php echo esc_html(implode(" ", $payload->cssClasses)) ?>" <?php echo (isset($payload->readOnly) && $payload->readOnly) ? "readonly" : "" ?> <?php echo esc_attr((isset($payload->disabled) && $payload->disabled) ? "disabled" : "") ?> id="<?php echo esc_attr($id); ?>_state" />
                    <?php echo strpos($themeName,"material") === 0 ? '<span class="mf-material-label">' . $payload->stateLabel . '</span>' : ''; ?>
                    <?php echo strpos($themeName,"material") === 0 ? '</div>' : '' ?>
                    <div class="mf-error"></div>
                </div>
            <?php endif; ?>
            <?php if ($payload->zipCheck) : ?>
                <div class="mf-form-group mf-col">
                <?php if($payload->labelDisplay == "show"):?>   <?php echo strpos($themeName,"material") === 0 ? '<div class="mf-material-container">' : '<label class="mf-label mf-label-' . esc_attr($payload->labelPosition) . '">' . esc_html($payload->zipLabel) . '</label>' ?><?php endif;?>
                    <input type="text" name="<?php echo esc_attr($id); ?>_zip" placeholder="<?php echo esc_attr($payload->zipPlaceholder) ?>" class="mf-form-control <?php echo isset($payload->fieldSize) ? esc_attr($fieldSizes[$payload->fieldSize]) : "" ?>  <?php echo esc_html(implode(" ", $payload->cssClasses)) ?>" <?php echo (isset($payload->readOnly) && $payload->readOnly) ? "readonly" : "" ?> <?php echo esc_attr((isset($payload->disabled) && $payload->disabled) ? "disabled" : "") ?> id="<?php echo esc_attr($id); ?>_zip" />
                    <?php echo strpos($themeName,"material") === 0 ? '<span class="mf-material-label">' . esc_html($payload->zipLabel) . '</span>' : ''; ?>
                    <?php echo strpos($themeName,"material") === 0 ? '</div>' : '' ?>
                    <div class="mf-error"></div>
                </div>
            <?php endif; ?>
            <?php if ($payload->countryCheck) : ?>
                <div class="mf-form-group mf-col">
                <?php if($payload->labelDisplay == "show"):?>   <?php echo strpos($themeName,"material") === 0 ? '<div class="mf-material-container mf-active">' : '<label class="mf-label mf-label-' . esc_attr($payload->labelPosition) . '">' . esc_html($payload->countryLabel) . '</label>' ?><?php endif;?>
                    <?php echo magicform_getCountries($id . "_country", $id . "_country", isset($payload->fieldSize) ? $fieldSizes[$payload->fieldSize] : "", "", $payload->disabled, property_exists($payload,"pleaseSelect")?$payload->pleaseSelect:"") ?>
                    <?php echo strpos($themeName,"material") === 0 ? '<span class="mf-material-label">' . esc_html($payload->countryLabel) . '</span>' : ''; ?>
                    <?php echo strpos($themeName,"material") === 0 ? '</div>' : '' ?>
                    <div class="mf-error"></div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php magicform_getInputDescription($payload, "bottom") ?>
</div>