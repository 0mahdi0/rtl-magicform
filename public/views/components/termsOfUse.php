<?php
$payload = $item->payload;
$id = $item->id;
?>
<div class='mf-form-group mf-form-group-<?php echo esc_attr($id); ?>'>
    <?php magicform_getInputLabel($payload) ?>

    <div class="mf-input-container">
        <?php magicform_getInputDescription($payload, "top") ?>
        <div class="mf-form-control mf-termsofuse" style="height:<?php echo esc_attr($payload->heightValue) ?>px !important;">
            <?php echo nl2br($payload->textAreaValue) ?>
        </div>

        <div class="mf-termsofuse-bottom mf-label mf-label-<?= $payload->checkBoxPos ?>">
            <div class="mf-custom-control mf-custom-control-inline mf-custom-checkbox">
                <input type="checkbox" name="<?php echo esc_attr($item->id); ?>" class="mf-custom-control-input" id="<?php echo esc_attr($item->id); ?>" />
                <label for="<?php echo esc_attr($item->id); ?>" class="mf-custom-control-label">
                    <?php echo esc_html($payload->title); ?>
                </label>
            </div>
        </div>
        <div class="mf-error"></div>
        <?php magicform_getInputDescription($payload, "bottom") ?>
    </div>
</div>