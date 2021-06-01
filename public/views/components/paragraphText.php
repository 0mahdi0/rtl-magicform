<?php
$payload = $item->payload;
$id = $item->id;
?>
<div class='mf-form-group mf-form-group-<?php echo esc_attr($id); ?>'>
    <?php magicform_getInputLabel($payload,strpos($themeName,"material") === 0) ?>
    <div class="mf-input-container">
        <?php magicform_getInputDescription($payload, "top") ?>
        <?php echo strpos($themeName,"material") === 0 ? '<div class="mf-material-container ' . ($payload->defaultValue != "" ? "mf-active" : "") . '">' : '' ?>
        <textarea maxlength="<?=$payload->maxLength?>"  <?php echo ($payload->disabled) ? "disabled" : "" ?>  name="<?php echo esc_attr($id); ?>" placeholder="<?php echo esc_attr($payload->placeholder) ?>" class="mf-form-control <?php echo esc_attr(implode(" ", $payload->cssClasses)) ?> <?=$fieldWidths[$payload->fieldWidth]?>" id="<?php echo esc_attr($id); ?>" <?php echo ($payload->readOnly) ? "readonly" : "" ?> style="height: <?php echo esc_attr($payload->heightValue) ?>px !important"></textarea>
        <?php echo strpos($themeName,"material") === 0 ? '<span class="mf-material-label">' . esc_html($payload->labelText) . '</span>' : ''; ?>
        <?php echo strpos($themeName,"material") === 0 ? '</div>' : '' ?>
        <div class="mf-error"></div>
        <?php magicform_getInputDescription($payload, "bottom") ?>
    </div>
</div>