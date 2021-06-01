<?php
$payload = $item->payload;
$id = $item->id;
?>
<div class='mf-form-group mf-form-group-<?php echo esc_attr($id); ?>'>
    <?php magicform_getInputLabel($payload, strpos($themeName, "material") === 0) ?>

    <div class="mf-input-container">
        <?php magicform_getInputDescription($payload, "top") ?>
        <?php echo strpos($themeName, "material") === 0 ? '<div class="mf-material-container ' . ($payload->defaultValue != "" ? "mf-active" : "") . ' ' . ($payload->inputIcon != "" ? "mf-with-icon" : "") . ' ">' : '' ?>
        <div class="mf-input-group <?php echo ($payload->fieldWidth !=="full" && $payload->fieldWidth !=="large")?"mf-flex-initial":"" ;?>">
            <?php if ($payload->inputIcon != "") : ?>
                <div class='mf-input-group-prepend'>
                    <span class='mf-input-group-text'>
                        <i class="fas fa-<?php echo esc_attr($payload->inputIcon); ?>"></i>
                    </span>
                </div>
            <?php endif; ?>

            <?php if ($payload->phoneCodeControl) : ?>
                <?php if ($payload->countryCodeGetJson) { ?>
                    <select <?php echo ($payload->disabled) ? "disabled" : "" ?> class="mf-phone-element mf-form-control <?php echo esc_attr($fieldSizes[$payload->fieldSize]); ?>  <?php echo esc_attr($fieldWidths[$payload->fieldWidth]); ?>  <?php echo esc_html(implode(" ", $payload->cssClasses)) ?>" name="<?php echo esc_attr($id); ?>_phoneCode">
                        <option value="">Please Select</option>
                        <?php foreach ($payload->phoneCodes as $option) { ?>
                            <option value="+<?= $option->phoneCode ?>">
                                <?php echo esc_html($option->name) . "(" . $option->phoneCode . ")" ?>
                            </option>
                        <?php } ?>
                    </select>
                <?php } else {
                    include("sub-components/countryCodes.php");
                } ?>
            <?php endif; ?>
            <input maxlength="<?= $payload->maxLength ?>" <?php echo ($payload->disabled) ? "disabled" : "" ?> type="text" name="<?php echo esc_attr($id); ?>_phone" value="<?php echo esc_attr($payload->defaultValue) ?>" <?php echo ($payload->readOnly) ? "readonly" : "" ?> placeholder="<?php echo esc_html($payload->placeholder) ?>" id="<?php echo esc_attr($id); ?>" class="mf-form-control <?php echo esc_attr($fieldSizes[$payload->fieldSize]); ?> <?php echo esc_attr($fieldWidths[$payload->fieldWidth]); ?> <?php echo esc_attr(implode(" ", $payload->cssClasses)) ?>" />
            <?php echo strpos($themeName, "material") === 0 ? '<span class="mf-material-label" style="' . ($payload->phoneCodeControl ? ($payload->inputIcon ? "left:250px" : "left:210px;") : "") . '">' . $payload->labelText . '</span>' : ''; ?>
        </div>
        <?php echo strpos($themeName, "material") === 0 ? '</div>' : '' ?>
        <div class="mf-error"></div>
        <?php magicform_getInputDescription($payload, "bottom") ?>
    </div>
</div>