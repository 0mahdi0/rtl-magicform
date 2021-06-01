<?php
$payload = $item->payload;
$id = $item->id;
?>

<div class='mf-form-group mf-form-group-<?php echo esc_attr($id); ?>'>
    <?php magicform_getInputLabel($payload,strpos($themeName,"material") === 0) ?>
    <div class="mf-input-container">
        <?php magicform_getInputDescription($payload, "top") ?>
        <?php echo strpos($themeName, "material") === 0 ? '<div class="mf-material-container mf-active ' . (isset($payload->inputIcon) && $payload->inputIcon != "" ? "mf-with-icon" : "") . ' ">' : '' ?>
        <?php magicform_getInputIconStart($payload) ?>
        <input type="hidden" value="" name="<?php echo esc_attr($id); ?>" id="date-<?php echo esc_attr($id); ?>" />
        <input id='<?php echo esc_attr($id); ?>' autocomplete="off" type='text' min-date="<?php echo esc_attr($payload->minDate==""?false:$payload->minDate)?>" max-date="<?php echo esc_attr($payload->maxDate==""?false:$payload->maxDate)?>" placeholder="<?php echo esc_attr($payload->datePlaceholder)?>" date-format="<?php echo esc_attr(strtolower($payload->dateFormat))?>" data-timepicker="<?php echo esc_attr($payload->showTime)?>" data-language='<?php echo esc_attr(strtolower($formSettings->selectLanguage)); ?>' class="airdatepicker-here mf-datepicker mf-form-control  <?php echo esc_attr($fieldSizes[$payload->fieldSize]); ?> <?php echo esc_attr($fieldWidths[$payload->fieldWidth]) ; ?>  <?php echo esc_html(implode(" ", $payload->cssClasses)) ?>" />
        <?php echo strpos($themeName,"material") === 0 ? '<span class="mf-material-label">' . esc_html($payload->labelText) . '</span>' : ''; ?>
        <?php magicform_getInputIconEnd($payload);  ?>
        <?php echo strpos($themeName, "material") === 0 ? '</div>' : '' ?>
        <div class="mf-error"></div>
        <?php magicform_getInputDescription($payload, "bottom") ?>
    </div>
</div>
