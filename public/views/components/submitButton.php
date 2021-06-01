<?php
$payload = $item->payload;
$id = $item->id;
$translate = $formSettings->translate;
?>

<?php if ($formSettings->gdpr) magicform_getGdprText($formSettings->gdprText, $this->formId); ?>

<div class="mf-result"></div>
<?php if ($formSettings->loading->type == "bar") : ?>
    <div class="mf-loading-bar">
        <div class="mf-alert mf-alert-info"><?php echo magicform_getLoadingIcon($formSettings->loading->icon, $formSettings->loading->iconColor) . " " . $formSettings->loading->text ?></div>
    </div>
<?php endif;  ?>

<?php if ($this->formLimit || $this->submitType == "preview") { ?>
        <div class='mf-form-group mf-form-group-<?php echo esc_attr($id) ?> <?php echo ($isMultiPage) ? "mf-button-placement-" . $formSettings->multiStep->buttonPlacement : "" ?> '>
            <?php if ($isMultiPage && ($pageIndex + 2) == $pageCount) : ?>
                <button type="button" class="mf-back-btn">
                    <?php if ($formSettings->multiStep->backButtonIcon) : ?>
                        <i class="fas fa-<?php echo esc_attr($formSettings->multiStep->backButtonIcon); ?>"></i>
                    <?php endif; ?>
                    <?php echo esc_html($translate->previousButton) ?></button>
            <?php endif; ?>
            <div class="mf-button-<?php echo esc_attr($payload->buttonPosition); ?>">
            <button id="<?php echo esc_attr($id) ?>" class="mf-submit-btn  <?php echo esc_attr($buttonSizes[$payload->buttonSize]); ?> 
        <?php echo ($payload->buttonWidth != "default") ? "mf-btn-width-" . esc_attr($payload->buttonWidth) : "" ?>
        mf-btn-<?php echo esc_attr($payload->buttonType); ?> <?php echo esc_html(implode(" ", $payload->cssClasses)) ?>" type="submit">
                <?php if ($payload->buttonIcon) : ?>
                    <i class="fas fa-<?php echo esc_attr($payload->buttonIcon); ?>"></i>
                <?php endif; ?>
                <?php if ($formSettings->loading->type == "button") : ?>
                    <span class="mf-loading-icon"><?php echo magicform_getLoadingIcon($formSettings->loading->icon, $formSettings->loading->iconColor) ?></span>
                <?php endif;  ?>
                <?php echo esc_html($payload->buttonName) ?>
            </button>
        </div>
    </div>
<?php } ?>