<?php
$payload = $item->payload;
$id = $item->id;
// 
?>
<div class='mf-form-group mf-form-group-<?php echo esc_attr($id) ?>'>
    <?php magicform_getInputLabel($payload) ?>
    <div class="mf-input-container">
    <?php magicform_getInputDescription($payload, "top") ?>
    <?php  $starCount = count($payload->ratings); ?>
  <div class="mf-rate-container">
    <div class="mf-rate">
      <?php for ($i=$starCount; $i>0; $i--) {
        $rating = $payload->ratings[$i-1];
        $key = $i;
        ?>
        <input type="radio" id="<?php echo esc_attr($id."-".$key); ?>" <?php echo ($payload->defaultRating ==  $key) ? "checked" : "" ?> name="<?php echo esc_attr($id) ?>" value="<?php echo intval($key); ?>" />
        <label for="<?php echo esc_attr($id."-".$key); ?>" title="<?php echo esc_attr($rating->name); ?>"> <?php echo esc_html($key . "-" . $rating->name); ?></label>
      <?php } ?>
    </div>
  </div>
  <div class="mf-error"></div>
  <?php magicform_getInputDescription($payload, "bottom") ?>
      </div>
</div>