<?php
$formId = intval($this->formId);
$colorScheme = $formSettings->colorScheme;
$theme = $formSettings->theme;



if ($theme) {
    $input = $theme->input;
    $label = $theme->label;
    $button = $theme->button;
    $submitButton = $theme->submitButton;
    $backButton = $theme->backButton;
    $nextButton = $theme->nextButton;
    $primaryColor = $colorScheme->name == "default" ? $theme->primaryColor : $colorScheme->primaryColor;
?>
 
    <style>
        form.magicform-<?php echo esc_attr($formId) ?> ,
        form.magicform-<?php echo esc_attr($formId) ?> * {
            font-family: <?php echo esc_html($formSettings->fontFamily) ?>;
        }

      
        .magicform-<?php echo esc_attr($formId) ?>  {
            <?php echo ($formSettings->formPadding !== null) ? "padding: 1px " . intval($formSettings->formPadding) . "px;" : "" ?>
        }

        .magicform-<?php echo esc_attr($formId) ?> .mf-form-group {
            margin-bottom: <?php echo intval($formSettings->verticalElementSpacing); ?>px !important;
        }
       

        .magicform-<?php echo esc_attr($formId) ?> .mf-form-control {
            <?php echo ($input->backgroundColor) ? "background-color: " . magicform_convertRgba($input->backgroundColor) . " !important;" : "" ?>
            <?php echo ($input->color) ? "color: " . magicform_convertRgba($input->color) . " !important;" : "" ?>
            <?php echo ($input->borderColor) ? "border-color: " . magicform_convertRgba($input->borderColor) . " !important;" : "" ?>
            <?php echo ($input->borderWidth) ? "border-width: " . intval($input->borderWidth) . "px !important;" : "" ?>
            <?php echo ($input->boxShadow) ? "box-shadow: " . $input->boxShadow . "!important;" : "" ?>
            <?php echo ($input->borderStyle) ? "border-style: " . $input->borderStyle . "!important;" : "" ?>
            <?php echo ($input->fontSize) ? "font-size: " . intval($input->fontSize) . "px!important;" : "" ?>
            <?php echo ($input->fontWeight) ? "font-weight: " . intval($input->fontWeight) . "!important;" : "" ?>
            <?php echo ($input->borderRadius !== null) ? "border-radius: " . intval($input->borderRadius) . "px!important;" : "" ?>
            <?php echo ($input->padding) ? "padding: " . $input->padding . "!important;" : "" ?>
            <?php echo ($input->margin) ? "margin: " . $input->margin . "!important;" : "" ?>
        }

        .magicform-<?php echo esc_attr($formId) ?> .mf-form-control::-webkit-input-placeholder {
            <?php echo ($input->placeholderColor) ? "color: " . magicform_convertRgba($input->placeholderColor) . "!important;" : "" ?>
        }

        .magicform-<?php echo esc_attr($formId) ?> .mf-form-control:-ms-input-placeholder {
            <?php echo ($input->placeholderColor) ? "color: " . magicform_convertRgba($input->placeholderColor) . "!important;" : "" ?>
        }

        .magicform-<?php echo esc_attr($formId) ?> .mf-form-control::placeholder {
            <?php echo ($input->placeholderColor) ? "color: " . magicform_convertRgba($input->placeholderColor) . "!important;" : "" ?>
        }

        .magicform-<?php echo esc_attr($formId) ?> .mf-custom-control-label {
            <?php echo ($input->fontSize) ? "font-size: " . intval($input->fontSize) . "px!important;" : "" ?>
        }

        .magicform-<?php echo esc_attr($formId) ?> .mf-custom-file-label {
            <?php echo ($input->fontSize) ? "font-size: " . intval($input->fontSize) . "px!important;" : "" ?>
    }

        .magicform-<?php echo esc_attr($formId) ?> .mf-input-group-text {
            <?php echo ($input->borderColor) ? "border-color: " . magicform_convertRgba($input->borderColor) . "!important;" : "" ?>
            <?php echo ($input->backgroundColor) ? "background-color: " . magicform_convertRgba($input->backgroundColor) . "!important;" : "" ?>
            <?php echo ($input->borderRadius !== null) ? "border-radius: " . intval($input->borderRadius) . "px!important;" : "" ?>
            <?php echo ($input->borderWidth) ? "border-width: " . intval($input->borderWidth) . "px!important;" : "" ?>
        }

        .magicform-<?php echo esc_attr($formId) ?> .mf-form-control:hover {
            <?php echo ($input->backgroundColorHover) ? "background-color: " . magicform_convertRgba($input->backgroundColorHover) . "!important;" : "" ?>
            <?php echo ($input->colorHover) ? "color: " . magicform_convertRgba($input->colorHover) . "!important;" : "" ?>
            <?php echo ($input->borderColorHover) ? "border-color: " . magicform_convertRgba($input->borderColorHover) . "!important;" : "" ?>
            <?php echo ($input->borderWidthHover) ? "border-width: " . $input->borderWidthHover . "px!important;" : "" ?>
            <?php echo ($input->boxShadowHover) ? "box-shadow: " . $input->boxShadowHover . "!important;" : "" ?>
        }
        
        .magicform-<?php echo esc_attr($formId) ?> .mf-form-control:focus {
            <?php echo ($input->backgroundColorFocus) ? "background-color: " . magicform_convertRgba($input->backgroundColorFocus) . "!important;" : "" ?>
            <?php echo ($input->colorFocus) ? "color: " . magicform_convertRgba($input->colorFocus) . "!important;" : "" ?>
            <?php echo ($input->borderColorFocus) ? "border-color: " . magicform_convertRgba($input->borderColorFocus) . "!important;" : "" ?>
            <?php echo ($input->borderWidthFocus) ? "border-width: " . intval($input->borderWidthFocus) . "px!important;" : "" ?>
            <?php echo ($input->boxShadowFocus) ? "box-shadow: " . $input->boxShadowFocus . "!important;" : "" ?>
        }
      

        .magicform-<?php echo esc_attr($formId) ?> .mf-label {
            <?php echo ($label->color) ? "color: " . magicform_convertRgba($label->color) . "!important;" : "" ?>
            <?php echo ($label->fontSize) ? "font-size: " . intval($label->fontSize) . "px!important;" : "" ?>
            <?php echo ($label->fontWeight) ? "font-weight: " . intval($label->fontWeight) . "!important;" : "" ?>
        }

        .magicform-<?php echo esc_attr($formId) ?> .mf-btn {
            <?php echo ($button->backgroundColor) ? "background-color: " . magicform_convertRgba($button->backgroundColor) . "!important;" : "" ?>
            <?php echo ($button->color) ? "color: " . magicform_convertRgba($button->color) . "!important;" : "" ?>
            <?php echo ($button->borderColor) ? "border-color: " . magicform_convertRgba($button->borderColor) . "!important;" : "" ?>
            <?php echo ($button->borderWidth) ? "border-width: " . intval($button->borderWidth) . "px!important;" : "" ?>
            <?php echo ($button->boxShadow) ? "box-shadow: " . $button->boxShadow . "!important;" : "" ?>
            <?php echo ($button->borderStyle) ? "border-style: " . $button->borderStyle . "!important;" : "" ?>
            <?php echo ($button->fontSize) ? "font-size: " . intval($button->fontSize) . "px!important;" : "" ?>
            <?php echo ($button->fontWeight) ? "font-weight: " . intval($button->fontWeight) . "!important;" : "" ?>
            <?php echo ($button->borderRadius) ? "border-radius: " . intval($button->borderRadius) . "px!important;" : "" ?>
            <?php echo ($button->padding) ? "padding: " . $button->padding . "!important;" : "" ?>
            <?php echo ($button->margin) ? "margin: " . $button->margin . "!important;" : "" ?>
            padding-top: 0 !important;
            padding-bottom: 0 !important;
            height: 40px !important;
            min-height: 40px !important;
            max-height: 40px !important;
            line-height: 40px !important;
        }

        .magicform-<?php echo esc_attr($formId) ?> .mf-btn:focus {
            <?php echo ($button->backgroundColorFocus) ? "background-color: " . magicform_convertRgba($button->backgroundColorFocus) . "!important;" : "" ?>
            <?php echo ($button->colorFocus) ? "color: " . magicform_convertRgba($button->colorFocus) . "!important;" : "" ?>
            <?php echo ($button->borderColorFocus) ? "border-color: " . magicform_convertRgba($button->borderColorFocus) . "!important;" : "" ?>
            <?php echo ($button->borderWidthFocus) ? "border-width: " . intval($button->borderWidthFocus) . "px!important;" : "" ?>
            <?php echo ($button->boxShadowFocus) ? "box-shadow: " . $button->boxShadowFocus . "!important;" : "" ?>
        }

        .magicform-<?php echo esc_attr($formId) ?> .mf-btn:hover {
            <?php echo ($button->backgroundColorHover) ? "background-color: " . magicform_convertRgba($button->backgroundColorHover) . "!important;" : "" ?>
            <?php echo ($button->colorHover) ? "color: " . magicform_convertRgba($button->colorHover) . "!important;" : "" ?>
            <?php echo ($button->borderColorHover) ? "border-color: " . magicform_convertRgba($button->borderColorHover) . "!important;" : "" ?>
            <?php echo ($button->borderWidthHover) ? "border-width: " . intval($button->borderWidthHover) . "px!important;" : "" ?>
            <?php echo ($button->boxShadowHover) ? "box-shadow: " . $button->boxShadowHover . "!important;" : "" ?>
        }

        .magicform-<?php echo esc_attr($formId) ?> .mf-submit-btn {
            <?php echo ($submitButton->backgroundColor) ? "background-color: " . magicform_convertRgba($submitButton->backgroundColor) . "!important;" : "" ?>
            <?php echo ($submitButton->color) ? "color: " . magicform_convertRgba($submitButton->color) . "!important;" : "" ?>
            <?php echo ($submitButton->borderColor) ? "border-color: " . magicform_convertRgba($submitButton->borderColor) . "!important;" : "" ?>
            <?php echo ($submitButton->borderWidth) ? "border-width: " . intval($submitButton->borderWidth) . "px!important;" : "" ?>
            <?php echo ($submitButton->boxShadow) ? "box-shadow: " . $submitButton->boxShadow . "!important;" : "" ?>
            <?php echo ($submitButton->borderStyle) ? "border-style: " . $submitButton->borderStyle . "!important;" : "" ?>
            <?php echo ($submitButton->fontSize) ? "font-size: " . intval($submitButton->fontSize) . "px!important;" : "" ?>
            <?php echo ($submitButton->fontWeight) ? "font-weight: " . intval($submitButton->fontWeight) . "!important;" : "" ?>
            <?php echo ($submitButton->borderRadius) ? "border-radius: " . intval($submitButton->borderRadius) . "px!important;" : "" ?>
            <?php echo ($submitButton->padding) ? "padding: " . $submitButton->padding . "!important;" : "" ?>
            <?php echo ($submitButton->margin) ? "margin: " . $submitButton->margin . "!important;" : "" ?>
            text-decoration: none!important;
            padding-top: 0!important;
            padding-bottom: 0!important;
            height: 40px!important;
            min-height: 40px!important;
            max-height: 40px!important;
            line-height: 40px!important;
        }

        .magicform-<?php echo esc_attr($formId) ?> .mf-custom-range::-webkit-slider-thumb {
            <?php echo ($submitButton->backgroundColor) ? "background-color: " . magicform_convertRgba($submitButton->backgroundColor) . "!important;" : "" ?>
        }

        .magicform-<?php echo esc_attr($formId) ?> .mf-submit-btn:focus {
            <?php echo ($submitButton->backgroundColorFocus) ? "background-color: " . magicform_convertRgba($submitButton->backgroundColorFocus) . "!important;" : "" ?>
            <?php echo ($submitButton->colorFocus) ? "color: " . magicform_convertRgba($submitButton->colorFocus) . "!important;" : "" ?>
            <?php echo ($submitButton->borderColorFocus) ? "border-color: " . magicform_convertRgba($submitButton->borderColorFocus) . "!important;" : "" ?>
            <?php echo ($submitButton->borderWidthFocus) ? "border-width: " . intval($submitButton->borderWidthFocus) . "px!important;" : "" ?>
            <?php echo ($submitButton->boxShadowFocus) ? "box-shadow: " . $submitButton->boxShadowFocus . "!important;" : "" ?>
        }

        .magicform-<?php echo esc_attr($formId) ?> .mf-submit-btn:hover {
            <?php echo ($submitButton->backgroundColorHover) ? "background-color: " . magicform_convertRgba($submitButton->backgroundColorHover) . "!important;" : "" ?>
            <?php echo ($submitButton->colorHover) ? "color: " . magicform_convertRgba($submitButton->colorHover) . "!important;" : "" ?>
            <?php echo ($submitButton->borderColorHover) ? "border-color: " . magicform_convertRgba($submitButton->borderColorHover) . "!important;" : "" ?>
            <?php echo ($submitButton->borderWidthHover) ? "border-width: " . intval($submitButton->borderWidthHover) . "px!important;" : "" ?>
            <?php echo ($submitButton->boxShadowHover) ? "box-shadow: " . $submitButton->boxShadowHover . "!important;" : "" ?>
        }

        .magicform-<?php echo esc_attr($formId) ?> .mf-next-btn {
            <?php echo ($nextButton->backgroundColor) ? "background-color: " . magicform_convertRgba($nextButton->backgroundColor) . "!important;" : "" ?>
            <?php echo ($nextButton->color) ? "color: " . magicform_convertRgba($nextButton->color) . "!important;" : "" ?>
            <?php echo ($nextButton->borderColor) ? "border-color: " . magicform_convertRgba($nextButton->borderColor) . "!important;" : "" ?>
            <?php echo ($nextButton->borderWidth) ? "border-width: " . intval($nextButton->borderWidth) . "px!important;" : "" ?>
            <?php echo ($nextButton->boxShadow) ? "box-shadow: " . $nextButton->boxShadow . "!important;" : "" ?>
            <?php echo ($nextButton->borderStyle) ? "border-style: " . $nextButton->borderStyle . "!important;" : "" ?>
            <?php echo ($nextButton->fontSize) ? "font-size: " . intval($nextButton->fontSize) . "px!important;" : "" ?>
            <?php echo ($nextButton->fontWeight) ? "font-weight: " . intval($nextButton->fontWeight) . "!important;" : "" ?>
            <?php echo ($nextButton->borderRadius) ? "border-radius: " . intval($nextButton->borderRadius) . "px!important;" : "" ?>
            <?php echo ($nextButton->padding) ? "padding: " . $nextButton->padding . "!important;" : "" ?>
            <?php echo ($nextButton->margin) ? "margin: " . $nextButton->margin . "!important;" : "" ?>
            padding-top: 0!important;
            padding-bottom: 0!important;
            height: 40px!important;
            min-height: 40px!important;
            max-height: 40px!important;
            line-height: 40px!important;
        }

        .magicform-<?php echo esc_attr($formId) ?> .mf-next-btn:focus {
            <?php echo ($nextButton->backgroundColorFocus) ? "background-color: " . magicform_convertRgba($nextButton->backgroundColorFocus) . "!important;" : "" ?>
            <?php echo ($nextButton->colorFocus) ? "color: " . magicform_convertRgba($nextButton->colorFocus) . "!important;" : "" ?>
            <?php echo ($nextButton->borderColorFocus) ? "border-color: " . magicform_convertRgba($nextButton->borderColorFocus) . "!important;" : "" ?>
            <?php echo ($nextButton->borderWidthFocus) ? "border-width: " . intval($nextButton->borderWidthFocus) . "px!important;" : "" ?>
            <?php echo ($nextButton->boxShadowFocus) ? "box-shadow: " . $nextButton->boxShadowFocus . "!important;" : "" ?>
        }

        .magicform-<?php echo esc_attr($formId) ?> .mf-next-btn:hover {
            <?php echo ($nextButton->backgroundColorHover) ? "background-color: " . magicform_convertRgba($nextButton->backgroundColorHover) . "!important;" : "" ?>
            <?php echo ($nextButton->colorHover) ? "color: " . magicform_convertRgba($nextButton->colorHover) . "!important;" : "" ?>
            <?php echo ($nextButton->borderColorHover) ? "border-color: " . magicform_convertRgba($nextButton->borderColorHover) . "!important;" : "" ?>
            <?php echo ($nextButton->borderWidthHover) ? "border-width: " .intval( $nextButton->borderWidthHover) . "px!important;" : "" ?>
            <?php echo ($nextButton->boxShadowHover) ? "box-shadow: " . $nextButton->boxShadowHover . "!important;" : "" ?>
        }

        .magicform-<?php echo esc_attr($formId) ?> .mf-back-btn {
            <?php echo ($backButton->backgroundColor) ? "background-color: " . magicform_convertRgba($backButton->backgroundColor) . "!important;" : "" ?>
            <?php echo ($backButton->color) ? "color: " . magicform_convertRgba($backButton->color) . "!important;" : "" ?>
            <?php echo ($backButton->borderColor) ? "border-color: " . magicform_convertRgba($backButton->borderColor) . "!important;" : "" ?>
            <?php echo ($backButton->borderWidth) ? "border-width: " . intval($backButton->borderWidth) . "px!important;" : "" ?>
            <?php echo ($backButton->boxShadow) ? "box-shadow: " . $backButton->boxShadow . "!important;" : "" ?>
            <?php echo ($backButton->borderStyle) ? "border-style: " . $backButton->borderStyle . "!important;" : "" ?>
            <?php echo ($backButton->fontSize) ? "font-size: " . intval($backButton->fontSize) . "px!important;" : "" ?>
            <?php echo ($backButton->fontWeight) ? "font-weight: " . intval($backButton->fontWeight) . "!important;" : "" ?>
            <?php echo ($backButton->borderRadius) ? "border-radius: " . intval($backButton->borderRadius) . "px!important;" : "" ?>
            <?php echo ($backButton->padding) ? "padding: " . $backButton->padding . "!important;" : "" ?>
            <?php echo ($backButton->margin) ? "margin: " . $backButton->margin . "!important;" : "" ?>
            margin-right:6px !important;
            padding-top: 0 !important;
            padding-bottom: 0 !important;
            height: 40px !important;
            min-height: 40px !important;
            max-height: 40px !important;
            line-height: 40px !important;
        }

        .magicform-<?php echo esc_attr($formId) ?> .mf-back-btn:focus {
            <?php echo ($backButton->backgroundColorFocus) ? "background-color: " . magicform_convertRgba($backButton->backgroundColorFocus) . "!important;" : "" ?>
            <?php echo ($backButton->colorFocus) ? "color: " . magicform_convertRgba($backButton->colorFocus) . "!important;" : "" ?>
            <?php echo ($backButton->borderColorFocus) ? "border-color: " . magicform_convertRgba($backButton->borderColorFocus) . "!important;" : "" ?>
            <?php echo ($backButton->borderWidthFocus) ? "border-width: " . intval($backButton->borderWidthFocus) . "px!important;" : "" ?>
            <?php echo ($backButton->boxShadowFocus) ? "box-shadow: " . $backButton->boxShadowFocus . "!important;" : "" ?>
        }

        .magicform-<?php echo esc_attr($formId) ?> .mf-back-btn:hover {
            <?php echo ($backButton->backgroundColorHover) ? "background-color: " . magicform_convertRgba($backButton->backgroundColorHover) . "!important;" : "" ?>
            <?php echo ($backButton->colorHover) ? "color: " . magicform_convertRgba($backButton->colorHover) . "!important;" : "" ?>
            <?php echo ($backButton->borderColorHover) ? "border-color: " . magicform_convertRgba($backButton->borderColorHover) . "!important;" : "" ?>
            <?php echo ($backButton->borderWidthHover) ? "border-width: " . intval($backButton->borderWidthHover) . "px!important;" : "" ?>
            <?php echo ($backButton->boxShadowHover) ? "box-shadow: " . $submitButton->boxShadowHover . "!important;" : "" ?>
        }
      
        .magicform-<?php echo esc_attr($formId) ?> .mf-custom-control-input:checked~.mf-custom-control-label::before {
            background-color: <?php echo magicform_convertRgba($primaryColor) ?>!important;
            border-color: <?php echo magicform_convertRgba($primaryColor) ?>!important;
        }

        .magicform-<?php echo esc_attr($formId) ?> .mf-custom-control-input:focus:not(:checked)~.mf-custom-control-label::before {
            border-color: <?php echo magicform_convertRgba($primaryColor) ?>!important;
        }

        .magicform-<?php echo esc_attr($formId) ?> .mf-custom-control-input:focus~.mf-custom-control-label::before {
            box-shadow: 0 0 0 3.2px <?php echo magicform_convertRgba($primaryColor, 0.25) ?>!important;
        }

        .magicform-<?php echo esc_attr($formId) ?> .mf-custom-control-input:not(:disabled):active~.mf-custom-control-label::before {
            background-color: <?php echo magicform_convertRgba($primaryColor, 0.25) ?>!important;
            border-color: <?php echo magicform_convertRgba($primaryColor, 0.25) ?>!important;
        }

        .magicform-<?php echo esc_attr($formId) ?> .mf-custom-file-label {
            <?php echo ($input->backgroundColor) ? "background-color: " . magicform_convertRgba($input->backgroundColor) . "!important;" : "" ?>
            <?php echo ($input->color) ? "color: " . magicform_convertRgba($input->color) . "!important;" : "" ?>
            <?php echo ($input->borderColor) ? "border-color: " . magicform_convertRgba($input->borderColor) . "!important;" : "" ?>
            <?php echo ($input->borderWidth) ? "border-width: " . intval($input->borderWidth) . "px!important;" : "" ?>
            <?php echo ($input->boxShadow) ? "box-shadow: " . $input->boxShadow . "!important;" : "" ?>
            <?php echo ($input->borderStyle) ? "border-style: " . $input->borderStyle . "!important;" : "" ?>
            <?php echo ($input->fontSize) ? "font-size: " . intval($input->fontSize) . "px!important;" : "" ?>
            <?php echo ($input->fontWeight) ? "font-weight: " . intval($input->fontWeight) . "!important;" : "" ?>
            <?php echo ($input->borderRadius) !== null ? "border-radius: " . intval($input->borderRadius) . "px!important;" : "" ?>
            <?php echo ($input->margin) ? "margin: " . $input->margin . "!important;" : "" ?>
            padding: 0 12px !important;
        }

        .magicform-<?php echo esc_attr($formId) ?> .mf-custom-file-input:focus~.mf-custom-file-label {
            <?php echo ($input->borderColorFocus) ? "border-color: " . magicform_convertRgba($input->borderColorFocus) . "!important;" : "" ?>
            <?php echo ($input->borderWidthFocus) ? "border-width: " . intval($input->borderWidthFocus) . "px!important;" : "" ?>
            <?php echo ($input->boxShadowFocus) ? "box-shadow: " . $input->boxShadowFocus . "!important;" : "" ?>
        }

        .magicform-<?php echo esc_attr($formId) ?> .mf-multistep-nav-item-active .mf-multistep-page-icon {
            background-color: <?php echo magicform_convertRgba($primaryColor) ?>!important;
            border-color: <?php echo magicform_convertRgba($primaryColor) ?>!important;
        }

        <?php /* Multi Step Theme 1 */  ?>.magicform-<?php echo esc_attr($formId) ?> .mf-multistep-header.mf-multistep-1 .mf-multistep-nav-item:before {
            background-color: <?php echo magicform_convertRgba($primaryColor) ?>!important;
        }

        .magicform-<?php echo esc_attr($formId) ?> .mf-multistep-header.mf-multistep-1 .mf-multistep-page-icon {
            background-color: <?php echo magicform_convertRgba($primaryColor) ?>!important;
            border-color: <?php echo magicform_convertRgba($primaryColor) ?>!important;
        }

        <?php /* Multi Step Theme 2 */  ?>.magicform-<?php echo esc_attr($formId) ?> .mf-multistep-header.mf-multistep-2 .mf-multistep-nav-item:before {
            background-color: <?php echo magicform_convertRgba($primaryColor) ?>!important;
        }

        .magicform-<?php echo esc_attr($formId) ?> .mf-multistep-header.mf-multistep-2 .mf-multistep-page-icon {
            background-color: <?php echo magicform_convertRgba($primaryColor) ?>!important;
            border-color: <?php echo magicform_convertRgba($primaryColor) ?>!important;
        }

        <?php /* Multi Step Theme 3 */  ?>.magicform-<?php echo esc_attr($formId) ?> .mf-multistep-header.mf-multistep-3 .mf-multistep-nav-item:before {
            background-color: <?php echo magicform_convertRgba($primaryColor) ?>!important;
        }

        .magicform-<?php echo esc_attr($formId) ?> .mf-multistep-header.mf-multistep-3 .mf-multistep-page-icon {
            border-color: <?php echo magicform_convertRgba($primaryColor) ?>!important;
        }

        .magicform-<?php echo esc_attr($formId) ?> .mf-multistep-header.mf-multistep-3 .mf-multistep-nav-item-active .mf-multistep-page-icon {
            background-color: <?php echo magicform_convertRgba($primaryColor) ?>!important;
        }

        <?php /* Multi Step Theme 4 */  ?>.magicform-<?php echo esc_attr($formId) ?> .mf-multistep-header.mf-multistep-4 .mf-multistep-nav-item:before {
            background-color: <?php echo magicform_convertRgba($primaryColor) ?>!important;
        }

        .magicform-<?php echo esc_attr($formId) ?> .mf-multistep-header.mf-multistep-4 .mf-multistep-page-icon {
            background-color: <?php echo magicform_convertRgba($primaryColor) ?>!important;
            border-color: <?php echo magicform_convertRgba($primaryColor) ?>!important;
        }

        .magicform-<?php echo esc_attr($formId) ?> .mf-multistep-header.mf-multistep-4 .mf-multistep-nav-item-active {
            color: <?php echo magicform_convertRgba($primaryColor) ?>!important;
        }

        .magicform-<?php echo esc_attr($formId) ?>.magicform-material .mf-material-container .mf-form-control:not([disabled]):focus~.mf-material-label {
            color: <?php echo magicform_convertRgba($primaryColor) ?>!important;
        }

        .magicform-<?php echo esc_attr($formId) ?>.magicform-material .mf-custom-radio .mf-custom-control-input:checked~.mf-custom-control-label::after {
            background-color: <?php echo magicform_convertRgba($primaryColor) ?>!important;
        }

        .magicform-<?php echo esc_attr($formId) ?>.magicform-material-outlined .mf-material-container .mf-form-control:not([disabled]):focus~.mf-material-label {
            color: <?php echo magicform_convertRgba($primaryColor) ?>!important;
        }

        .magicform-<?php echo esc_attr($formId) ?>.magicform-material-outlined .mf-custom-radio .mf-custom-control-input:checked~.mf-custom-control-label::after {
            background-color: <?php echo magicform_convertRgba($primaryColor) ?>!important;
        }

        .magicform-<?php echo esc_attr($formId) ?> .mf-custom-control-input:disabled:checked~.mf-custom-control-label::before {
            background-color: <?php echo magicform_convertRgba($primaryColor,0.3) ?>!important;
            border-color: <?php echo magicform_convertRgba($primaryColor,0.5) ?>!important;
        }

        .magicform-<?php echo esc_attr($formId) ?> .mf-css-checkbox:checked+label.mf-thumbnail {
            border-color: <?php echo magicform_convertRgba($primaryColor) ?>!important;
        }
        .magicform-<?php echo esc_attr($formId) ?> .mf-css-checkbox:checked+label.mf-thumbnail:after {
            background-color: <?php echo magicform_convertRgba($primaryColor) ?>!important;
        }
        .magicform-<?php echo esc_attr($formId) ?> .mf-thumbnail-container {
            <?php echo ($input->fontSize) ? "font-size: " . intval($input->fontSize) . "px!important;" : "" ?>
        }
        

        .mf-form-width-xs,
        .magicform .mf-form-width-sm,
        .magicform .mf-form-width-md,
        .magicform .mf-form-width-lg {
            max-width: 100% !important;
        }

        .magicform .mf-form-width-xs {
            white-space: nowrap !important;
            width: 150px !important;
        }

        .magicform .mf-form-width-sm {
            white-space: nowrap !important;
            width: 200px !important;
        }

        .magicform .mf-form-width-md {
            white-space: nowrap !important;
            width: 300px!important;
        }

        .magicform .mf-form-width-lg {
            white-space: nowrap!important;
            width: 400px!important;
        }

        .magicform .mf-form-width-full {
            width: 100%!important;
        }

        .magicform .mf-btn-sm {
            height: 30px!important;
            padding: 0 10px!important;
            line-height: 30px!important;
            min-height: 30px!important;
            max-height: 30px!important;
            font-size: 14px!important;
        }

        .magicform .mf-btn-lg {
            height: 50px!important;
            line-height: 50px!important;
            min-height: 50px!important;
            max-height: 50px!important;
            padding: 0 30px!important;
            font-size: 18px!important;
        }

        .magicform .mf-btn-width-tiny {
            white-space: nowrap!important;
            width: 100px!important;
        }

        .magicform .mf-btn-width-small {
            white-space: nowrap!important;
            width: 200px!important;
        }

        .magicform .mf-btn-width-medium {
            white-space: nowrap!important;
            width: 300px!important;
        }

        .magicform .mf-btn-width-large {
            white-space: nowrap!important;
            width: 400px!important;
        }

        .magicform .mf-btn-width-full {
            width: 100%!important;
        }

        .magicform .mf-btn-primary {
            color: #fff!important;
            background-color: #007bff!important;
            border-color: #007bff!important;
        }

        .magicform .mf-btn-primary:hover {
            color: #fff!important;
            background-color: #0069d9!important;
            border-color: #0062cc!important;
        }

        .magicform .mf-btn-secondary {
            color: #fff!important;
            background-color: #6c757d!important;
            border-color: #6c757d!important;
        }

        .magicform .mf-btn-secondary:hover {
            color: #fff!important;
            background-color: #5a6268!important;
            border-color: #545b62!important;
        }

        .magicform .mf-btn-success {
            color: #fff!important;
            background-color: #28a745!important;
            border-color: #28a745!important;
        }

        .magicform .mf-btn-success:hover {
            color: #fff!important;
            background-color: #218838!important;
            border-color: #1e7e34!important;
        }

        .magicform .mf-btn-danger {
            color: #fff!important;
            background-color: #dc3545!important;
            border-color: #dc3545!important;
        }

        .magicform .mf-btn-danger:hover {
            color: #fff!important;
            background-color: #c82333!important;
            border-color: #bd2130!important;
        }

        .magicform .mf-btn-warning {
            color: #212529!important;
            background-color: #ffc107!important;
            border-color: #ffc107!important;
        }

        .magicform .mf-btn-warning:hover {
            color: #212529!important;
            background-color: #e0a800!important;
            border-color: #d39e00!important;
        }

        .magicform .mf-btn-info {
            color: #fff!important;
            background-color: #17a2b8!important;
            border-color: #17a2b8!important;
        }

        .magicform .mf-btn-info:hover {
            color: #fff!important;
            background-color: #138496!important;
            border-color: #117a8b!important;
        }

        .magicform .mf-btn-light {
            color: #212529!important;
            background-color: #f8f9fa!important;
            border-color: #f8f9fa!important;
        }

        .magicform .mf-btn-light:hover {
            color: #212529!important;
            background-color: #e2e6ea!important;
            border-color: #dae0e5!important;
        }

        .magicform .mf-btn-dark {
            color: #fff!important;
            background-color: #343a40!important;
            border-color: #343a40!important;
        }

        .magicform .mf-btn-dark:hover {
            color: #fff!important;
            background-color: #23272b!important;
            border-color: #1d2124!important;
        }

        .magicform .mf-btn-link {
            font-weight: 400!important;
            color: #007bff!important;
            background-color: transparent!important;
        }

        .magicform .mf-btn-link:hover {
            color: #0056b3!important;
            text-decoration: underline!important;
            background-color: transparent!important;
            border-color: transparent!important;
        }


        .magicform .mf-btn-outline-primary {
            color: #007bff!important;
            background-color: transparent!important;
            background-image: none!important;
            border-color: #007bff!important;
        }

        .magicform .mf-btn-outline-primary:hover {
            color: #fff!important;
            background-color: #007bff!important;
            border-color: #007bff!important;
        }

        .magicform .mf-btn-outline-secondary {
            color: #6c757d!important;
            background-color: transparent!important;
            background-image: none!important;
            border-color: #6c757d!important;
        }

        .magicform .mf-btn-outline-secondary:hover {
            color: #fff!important;
            background-color: #6c757d!important;
            border-color: #6c757d!important;
        }

        .magicform .mf-btn-outline-success {
            color: #28a745!important;
            background-color: transparent!important;
            background-image: none!important;
            border-color: #28a745!important;
        }

        .magicform .mf-btn-outline-success:hover {
            color: #fff!important;
            background-color: #28a745!important;
            border-color: #28a745!important;
        }

        .magicform .mf-btn-outline-danger {
            color: #dc3545!important;
            background-color: transparent!important;
            background-image: none!important;
            border-color: #dc3545!important;
        }

        .magicform .mf-btn-outline-danger:hover {
            color: #fff!important;
            background-color: #dc3545!important;
            border-color: #dc3545!important;
        }

        .magicform .mf-btn-outline-warning {
            color: #ffc107!important;
            background-color: transparent!important;
            background-image: none!important;
            border-color: #ffc107!important;
        }

        .magicform .mf-btn-outline-warning:hover {
            color: #212529!important;
            background-color: #ffc107!important;
            border-color: #ffc107!important;
        }

        .magicform .mf-btn-outline-info {
            color: #17a2b8!important;
            background-color: transparent!important;
            background-image: none!important;
            border-color: #17a2b8!important;
        }

        .magicform .mf-btn-outline-info:hover {
            color: #fff!important;
            background-color: #17a2b8!important;
            border-color: #17a2b8!important;
        }

        .magicform .mf-btn-outline-light {
            color: #f8f9fa!important;
            background-color: transparent!important;
            background-image: none!important;
            border-color: #f8f9fa!important;
        }

        .magicform .mf-btn-outline-light:hover {
            color: #212529!important;
            background-color: #f8f9fa!important;
            border-color: #f8f9fa!important;
        }

        .magicform .mf-btn-outline-dark {
            color: #343a40!important;
            background-color: transparent!important;
            background-image: none!important;
            border-color: #343a40!important;
        }

        .magicform .mf-btn-outline-dark:hover {
            color: #fff!important;
            background-color: #343a40!important;
            border-color: #343a40!important;
        }
    </style>
<?php }
