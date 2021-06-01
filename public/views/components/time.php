<?php
$payload = $item->payload;
$id = $item->id;
?>
<div class='mf-form-group mf-form-group-<?php echo esc_attr($id) ?>'>
    <?php magicform_getInputLabel($payload,strpos($themeName,"material") === 0) ?>
    <div class="mf-input-container">
        <?php magicform_getInputDescription($payload, "top") ?>
        <?php echo strpos($themeName,"material") === 0 ? '<div class="mf-material-container mf-active ' . ($payload->inputIcon != "" ? "mf-with-icon" : "") . ' ">' : '' ?>
        <?php magicform_getInputIconStart($payload) ?>
        <select 
        class="mf-form-control 
            <?php echo esc_attr($fieldSizes[$payload->fieldSize]); ?> 
            <?php echo esc_attr($fieldWidths[$payload->fieldWidth]); ?> 
            <?php echo esc_attr(implode(" ", $payload->cssClasses)); ?>" 
         name="<?php echo esc_attr($id); ?>"  <?php echo ($payload->disabled) ? "disabled" : "" ?> id="<?php echo esc_attr($id); ?>">
            <?php foreach ($payload->clocks as $clock) { ?>
                <option <?php echo esc_attr(($payload->defaultValueCheck && $payload->defaultValue == $clock)?"selected":"")?> value="<?php echo esc_attr($clock); ?>">
                    <?php echo esc_html($clock) ?>
                </option>
            <?php } ?>
        </select>
        <?php echo strpos($themeName,"material") === 0 ? '<span class="mf-material-label">' . esc_html($payload->labelText) . '</span>' : ''; ?>
        <?php magicform_getInputIconEnd($payload);  ?>
        <?php echo strpos($themeName,"material") === 0 ? '</div>' : '' ?>
        <div class="mf-error"></div>
        <?php magicform_getInputDescription($payload, "bottom") ?>
    </div>
</div>