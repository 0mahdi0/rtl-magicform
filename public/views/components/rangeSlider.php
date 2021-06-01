<?php
$payload = $item->payload;
$id = $item->id;
?>
<div class='mf-form-group mf-form-group-<?php echo esc_attr($id) ?>'>
	<?php magicform_getInputLabel($payload) ?>
	<div class="mf-input-container">
		<?php magicform_getInputDescription($payload, "top") ?>
		<div class="<?= $fieldWidths[$payload->fieldWidth] ?> ">
			<input type="range" name="<?php echo esc_attr($id) ?>" value="<?php echo esc_attr($payload->defaultValue) ?>" class="mf-custom-range   <?php echo esc_html(implode(" ", $payload->cssClasses)) ?>" min="<?php echo esc_attr($payload->minRange) ?>" max="<?php echo esc_attr($payload->maxRange) ?>" step="<?php echo esc_attr($payload->rangeStep) ?>" id="<?php echo esc_attr($id) ?>" />
			<label class="mf-label mf-label-<?php echo esc_attr($payload->valueTitlePos); ?>"> <?php echo esc_html($payload->valueTitle) ?>: <span class='mf-value'><?php echo esc_attr($payload->defaultValue) < esc_attr($payload->minRange) ? esc_attr($payload->minRange) : esc_attr($payload->defaultValue) ?></span></label>
			<div class="mf-error"></div>
		</div>
		<?php magicform_getInputDescription($payload, "bottom") ?>
	</div>
</div>