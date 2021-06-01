<?php
$payload = $item->payload;
$id = $item->id;
?>
<div class='mf-form-group mf-form-group-<?php echo esc_attr($id); ?>'>
<div id="<?php echo esc_attr($id) ?>" class='<?php echo esc_html(implode(" ", $payload->cssClasses))?>'>
    <?=$payload->htmlContent?>
</div>
</div>