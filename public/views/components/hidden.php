<?php
$payload = $item->payload;
$id = $item->id;
?>
<div class='mf-form-group-<?php echo esc_attr($id); ?>'>
<input type="hidden" name="<?php echo esc_attr($id) ?>" id="<?php echo esc_attr($id) ?>" value="<?=esc_attr($payload->defaultValue)?>"/>
</div>
