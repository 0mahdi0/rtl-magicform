<?php
$payload = $item->payload;
$id = $item->id;
?>
<div class='mf-form-group mf-form-group-<?php echo esc_attr($id) ?>'>
    <?php magicform_getInputLabel($payload) ?>
    <div class="mf-input-container">
        <?php magicform_getInputDescription($payload, "top") ?>
        <div class="mf-scale-container">
            <label class="mf-scale-label"> <?php echo esc_html($payload->worstLabel) ?></label>
            <div class="mf-scale-rating-container">
                <?php foreach ($payload->ratings as $key => $rating) { ?>
                    <div class="mf-scale-element">
                        <div><?php echo intval($key); ?></div>
                        <div class="mf-custom-control mf-custom-radio">
                            <input type="radio" name="<?php echo esc_attr($id) ?>" id="<?php echo esc_attr($id . "_" . $key); ?>" value="<?php echo intval($key) ?>" <?php echo ($payload->defaultRating == $key) ? "checked" : "" ?> class="mf-custom-control-input" />
                            <label class="mf-custom-control-label" for="<?php echo esc_attr($id . "_" . $key); ?>"></label>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <label class="mf-scale-label"> <?php echo esc_html($payload->bestLabel) ?></label>
        </div>
        <div class="mf-error"></div>
        <?php magicform_getInputDescription($payload, "bottom") ?>
    </div>
</div>