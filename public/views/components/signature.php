<script src=""></script>
<?php
$payload = $item->payload;
$id = esc_attr($item->id);
?>
<div class='mf-form-group mf-form-group-<?php echo esc_attr($id) ?>'>
    <?php magicform_getInputLabel($payload) ?>
    <div class="mf-input-container">
        <?php magicform_getInputDescription($payload, "top") ?>
        <div style="border:1px solid <?php echo magicform_convertRgba($payload->borderColor)?>; background:<?php echo magicform_convertRgba($payload->backgroundColor)?>; display:inline-block; ">
        <canvas class="mf-signature" pencolor="<?=magicform_convertRgba($payload->penColor)?>"  target="<?php echo esc_attr($id) ?>" id="<?php echo esc_attr($id) ?>_signature" width="<?=$payload->widthValue?>" height="<?=$payload->heightValue?>"></canvas>
        </div>
        <i id="<?php echo esc_attr($id) ?>_signature" style=""  class="fas fa-eraser mf-remove-signature"></i>
        <input type="hidden" id="<?php echo esc_attr($id) ?>" name="<?php echo esc_attr($id) ?>" value=""/>
        <div class="mf-error"></div>
        <?php magicform_getInputDescription($payload, "bottom") ?>
    </div>
</div>
