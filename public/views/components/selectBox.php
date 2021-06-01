<?php
$payload = $item->payload;
$id = $item->id;
?>
<div class='mf-form-group mf-form-group-<?php echo esc_attr($id) ?>'>
    <?php magicform_getInputLabel($payload, strpos($themeName, "material") === 0) ?>
    <div class="mf-input-container">
        <?php magicform_getInputDescription($payload, "top") ?>
        <?php echo strpos($themeName, "material") === 0 ? '<div class="mf-material-container mf-active ' . ($payload->inputIcon != "" ? "mf-with-icon" : "") . ' ">' : '' ?>
        <?php magicform_getInputIconStart($payload) ?>
        <select class="mf-form-control 
            <?php echo esc_attr($fieldSizes[$payload->fieldSize]); ?> 
            <?php echo esc_attr($fieldWidths[$payload->fieldWidth]); ?> 
            <?php echo esc_html(implode(" ", $payload->cssClasses)) ?>" id="<?php echo esc_attr($id) ?>" name="<?php echo esc_attr($id) ?>" <?php echo ($payload->disabled) ? "disabled" : "" ?>>
            <?php 
                    if(isset($payload->pleaseSelect) && !empty($payload->pleaseSelect))
                        echo "<option value=''>$payload->pleaseSelect</option>" 
                ?>
            <?php foreach ($payload->options as $option) { ?>
                <option <?= isset($option->default) ? "selected" : "" ?> value="<?php echo esc_attr($option->value) ?>" calcValues="<?php echo (isset($option->calcValues)?esc_attr($option->calcValues):0) ?>">
                    <?php echo esc_html($option->name) ?>
                </option>
            <?php } ?>
        </select>
        <?php echo strpos($themeName, "material") === 0 ? '<span class="mf-material-label">' . esc_html($payload->labelText) . '</span>' : ''; ?>
        <?php magicform_getInputIconEnd($payload);  ?>
        <?php echo strpos($themeName, "material") === 0 ? '</div>' : '' ?>
        <div class="mf-error"></div>
        <?php magicform_getInputDescription($payload, "bottom") ?>
    </div>
</div>