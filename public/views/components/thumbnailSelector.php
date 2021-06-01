<?php
$payload = $item->payload;
$id = $item->id;
$disabled = (isset($payload->disabled) && $payload->disabled) ? "disabled" : "";
$defaultImage = MAGICFORM_URL . "assets/images/default-image.jpg";
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
            <div class="mf-thumbnail-container">
                <div class="mf-thumbnail-inner <?php echo esc_attr($payload->cbLabelPos)?>">
                    <input type="<?php echo esc_attr($payload->inputType=="radio"?"radio":"checkbox");?>" calcValues="<?php echo (isset($option->calcValues)?esc_attr($option->calcValues):0) ?>" <?php echo esc_attr($disabled); ?> name="<?php echo esc_attr($id); ?>_value" value="<?php echo esc_attr($option->value) ?>" <?php echo (isset($option->default) && $option->default) ? 'checked' : '' ?> class="mf-css-checkbox <?php echo esc_html(implode(" ", $payload->cssClasses)) ?>" id="<?php echo esc_attr($item->id . "_" . esc_attr($key)); ?>" />
                    
                    <label for="<?php echo esc_attr($id."_".$key)?>" class="mf-thumbnail">
                        <img class="mf-thumbnail-img" src="<?php echo esc_attr($option->image!=""?$option->image:$defaultImage)?>" style="<?php echo esc_attr( $payload->autoSize?"height:auto; width:auto;":"height:".$payload->heightValue."px; width:".$payload->widthValue."px;"); ?>"  alt="image"/>
                    </label>
                    <?php if($payload->cbLabelDisplay):?>
                            <div class="mf-thumbnail-label form-check form-check-inline">
                            <label class="form-check-label"
                                    placeholder="Image Label"
                                    for="<?php echo esc_attr($id."_".$option->value)?>"><?php echo esc_html($option->name);?>
                            </label>
                        </div>
                    <?php endif;?>
                </div>
            </div>
        <?php
            echo ($payload->optionsDisplay != "inline") ? '</div>' : '';
            $i++;
        } ?>
        <?php echo ($payload->optionsDisplay != "inline") ? '</div>' : '' ?>
        <div class="mf-error"></div>
        <?php magicform_getInputDescription($payload, "bottom") ?>
    </div>
</div>