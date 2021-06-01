<?php
$payload = $item->payload;
$id = $item->id;
$disabled = (isset($payload->disabled) && $payload->disabled) ? "disabled" : "";
?>
<div class='mf-form-group mf-form-group-<?php echo esc_attr($id); ?>'>
    <?php magicform_getInputLabel($payload) ?>
    <div class="mf-input-container">
        <?php magicform_getInputDescription($payload, "top") ?>
        <?php echo ($payload->optionsDisplay != "inline") ? '<div class="mf-row">' : '' ?>
        <?php $i = 0;
        foreach ($payload->options as $key => $option) { ?>
            <?php if ($payload->optionsDisplay != "inline") :
                echo ($i % $payload->optionsDisplay == 0) ? "</div><div class='mf-row'>" : "";
                echo "<div class='mf-col'>";
            endif; ?>
            <div class="mf-custom-control mf-custom-control-inline mf-custom-checkbox">
                <input type="checkbox" <?php echo esc_attr($disabled); ?> name="<?php echo esc_attr($id); ?>_value" calcValues="<?php echo (isset($option->calcValues)?esc_attr($option->calcValues):0) ?>" value="<?php echo esc_attr($option->value) ?>" <?php echo (isset($option->default)) ? 'checked' : '' ?> class="mf-custom-control-input <?php echo esc_html(implode(" ", $payload->cssClasses)) ?>" id="<?php echo esc_attr($item->id . "_" . esc_attr($key)); ?>" />
                <label class="mf-custom-control-label" for="<?php echo esc_attr($item->id . "_" . esc_attr($key)); ?>"><?php echo esc_html($option->name) ?></label>
            </div>
        <?php
            echo ($payload->optionsDisplay != "inline") ? '</div>' : '';
            $i++;
        } ?>
        <?php if ($payload->otherCheck) : ?>
            <?php if ($payload->optionsDisplay != "inline") :
                echo ($i % $payload->optionsDisplay == 0) ? "</div><div class='mf-row'>" : "";
                echo "<div class='mf-col'>";
            endif; ?>
            <div class="mf-custom-control mf-custom-control-inline mf-custom-checkbox">
                <input type="checkbox"  <?php echo esc_attr($disabled); ?> name="<?php echo esc_attr($id); ?>_value" value="other" class="mf-custom-control-input <?php echo esc_html(implode(" ", $payload->cssClasses)) ?>" id="<?php echo esc_attr($item->id . "_other"); ?>" />
                <label class="mf-custom-control-label" for="<?php echo esc_attr($id . "_other"); ?>"><?php echo esc_html($payload->otherLabel) ?></label>
                <input type="text" <?php echo esc_attr($disabled); ?> class="mf-form-control mf-other-input mf-other-display-<?php echo esc_attr($payload->optionsDisplay); ?>" placeholder="<?php echo esc_attr($payload->otherPlaceholder) ?>" value="<?php echo esc_attr($payload->otherDefaultValue) ?>" id="<?php echo esc_attr($id . "_other") ?>" name="<?php echo esc_attr($id . "_other") ?>" />
            </div>
            <?php echo ($payload->optionsDisplay != "inline") ? '</div>' : '' ?>
        <?php endif; ?>
        <?php echo ($payload->optionsDisplay != "inline") ? '</div>' : '' ?>
        <div class="mf-error"></div>
        <?php magicform_getInputDescription($payload, "bottom") ?>
    </div>
</div>