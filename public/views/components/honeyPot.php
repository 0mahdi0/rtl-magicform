<?php
    $payload = $item->payload;
    $id = $item->id;
?>
<div class='mf-form-group-<?php echo esc_attr($id); ?>'>
    <input class='mf-magic-item' name="magic-name" autocomplete="off" type="text" id="<?php echo esc_attr($id) ?>" value=""/>
    <input class='mf-magic-item' name="magic-email" autocomplete="off" type="email" id="<?php echo esc_attr($id) ?>" value=""/>
</div>
