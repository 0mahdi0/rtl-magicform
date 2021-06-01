<?php
$payload = $item->payload;
$id = $item->id;
$maxFileSize = $formSettings->translate->maxFileSize;
$allowedFileTypes = $formSettings->translate->allowedFileTypes;
$extensionErr = $formSettings->translate->extensionErr;
$fileSizeErr = $formSettings->translate->fileSizeErr;
?>
<div class='mf-form-group mf-form-group-<?php echo esc_attr($id); ?>'>
    <?php magicform_getInputLabel($payload, strpos($themeName, "material") === 0) ?>
    <div class="mf-input-container">
        <?php magicform_getInputDescription($payload, "top") ?>
        <?php echo strpos($themeName, "material") === 0 ? '<div class="mf-material-container mf-active">' : '' ?>
        <?php magicform_getInputIconStart($payload) ?>
        <div class="mf-custom-file <?php echo esc_attr($fieldSizes[$payload->fieldSize]) ?>  <?php echo esc_attr($fieldWidths[$payload->fieldWidth]) ?> ">
            <input type="file" allow-size="<?php echo esc_attr($payload->fileSize); ?>" file-size-type="<?php echo esc_attr($payload->fileSizeType);?>"  allow-file-types="<?php echo esc_attr(implode(",", $payload->allowedFileTypes))?>" name="<?php echo esc_attr($id); ?>" class="mf-custom-file-input <?php echo esc_html(implode(" ", $payload->cssClasses)) ?>" id="<?php echo esc_attr($id); ?>" />
            <label class="mf-custom-file-label" for="<?php echo esc_attr($id); ?>" data-placeholder="<?php echo esc_attr($payload->placeholder); ?>">
                <?php echo esc_html($payload->placeholder) ?>
            </label>
        </div>
        <?php echo strpos($themeName, "material") === 0 ? '<span class="mf-material-label">' . esc_html($payload->labelText) . '</span>' : ''; ?>
        <?php echo strpos($themeName, "material") === 0 ? '</div>' : '' ?>
        <small class="mf-form-text mf-text-muted"> <?php echo esc_html($maxFileSize) ?>: <?php echo esc_html($payload->fileSize) ?><?php echo esc_html($payload->fileSizeType) ?> <b>&middot;</b> <?php echo esc_html($allowedFileTypes) ?>: <?php echo esc_html(implode(",", $payload->allowedFileTypes)) ?> </small>
        <div class="mf-error"></div>
    </div>
    <?php magicform_getInputIconEnd($payload); ?>
    <?php magicform_getInputDescription($payload, "bottom") ?>

    <style>
        #<?php echo esc_html($id); ?>+label:after {
            content: "<?php echo esc_html($payload->buttonName) ?>"
        }
    </style>
</div>