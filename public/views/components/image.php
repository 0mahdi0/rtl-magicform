<?php
$payload = $item->payload;
$id = $item->id;
$defaultImage = MAGICFORM_URL . "assets/images/default-image.jpg";
?>
<div class='mf-form-group mf-form-group-<?php echo esc_attr($id); ?>'>
    <div class="mf-input-container">
        <?php magicform_getInputDescription($payload, "top") ?>
            <div class="mf-image-<?php echo esc_attr($payload->elementPosition)?>">
                <img 
                    src="<?php echo esc_attr($payload->imageSrc!=""?$payload->imageSrc:$defaultImage)?>" 
                    style="height:<?php echo esc_attr($payload->heightValue)?>px;width:<?php echo esc_attr($payload->widthValue)?>px;"
                    class="mf-image <?php echo esc_html(implode(" ", $payload->cssClasses)) ?>"
                >       
            </div>
        <?php magicform_getInputDescription($payload, "bottom") ?>
    </div>
</div>