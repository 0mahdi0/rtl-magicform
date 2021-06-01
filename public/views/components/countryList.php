<?php
$payload = $item->payload;
$id = $item->id;
?>
<div class='mf-form-group mf-form-group-<?php echo esc_attr($id); ?>'>
    <?php magicform_getInputLabel($payload,strpos($themeName,"material") === 0) ?>
    <div class="mf-input-container">
        <?php magicform_getInputDescription($payload, "top") ?>
        <?php echo strpos($themeName,"material") === 0 ? '<div class="mf-material-container mf-active ' . ($payload->inputIcon != "" ? "mf-with-icon" : "") . ' ">' : '' ?>
        <?php magicform_getInputIconStart($payload) ?>

        <?php if ($payload->countryGetJson) { ?>
            <select id="<?php echo esc_attr($id) ?>" <?php echo (isset($payload->disabled) && $payload->disabled) ? "disabled" : "" ?>  class="mf-form-control <?php echo esc_attr($fieldSizes[$payload->fieldSize]); ?> <?php echo esc_attr($fieldWidths[$payload->fieldWidth])?>  <?php echo esc_attr(implode(" ", $payload->cssClasses)) ?>" name="<?php echo esc_attr($id); ?>">
                <?php foreach ($payload->countryList as $option) { ?>  
                    <?php 
                        if(isset($payload->pleaseSelect) && !empty($payload->pleaseSelect))
                            echo "<option value=''>$payload->pleaseSelect</option>" 
                    ?>        
                    <option value="<?php echo esc_attr($option->value) ?>">
                        <?php echo esc_html($option->name) ?>
                    </option>
                <?php } ?>
            </select>
        <?php } else {
            magicform_getCountries($id, $id,$fieldSizes[$payload->fieldSize],$fieldWidths[$payload->fieldWidth],(isset($payload->disabled) && $payload->disabled) ? "disabled" : false, $payload->pleaseSelect);
        }
        ?>
        <?php echo strpos($themeName,"material") === 0 ? '<span class="mf-material-label">' . esc_html($payload->labelText) . '</span>' : ''; ?>

        <?php magicform_getInputIconEnd($payload);  ?>
        <?php echo strpos($themeName,"material") === 0 ? '</div>' : '' ?>

        <div class="mf-error"></div>
        <?php magicform_getInputDescription($payload, "bottom") ?>
    </div>
</div>