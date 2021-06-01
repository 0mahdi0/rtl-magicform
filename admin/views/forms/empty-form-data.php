<?php
return array(
    "settings" => array(
        "name" => sanitize_text_field($_POST["name"]),
        "title" => "",
        "titleVisible" => true,
        "description" => "",
        "active" => true,
        "labelPosition" => "left",
        "verticalElementSpacing" => 16,
        "selectLanguage" => "EN",
        "formPadding" => 30,
        "subLimit" => 0,
        "endSubDate" => "",
        "dateFormat" => "m/D/YY",
        "dateFormatType" => "backslash",
        "conditionalLogic" => array(
            "advancedMode" => false
        ),
        "multiStep" => array(
            "theme" => "1",
            "type" => "number",
            "visible" => true,
            "buttonPlacement" => "leftright",
            "backButtonIcon" => null,
            "nextButtonIcon" => null
        ),
        "progressBar" => array(
            "show" => false,
            "striped" => false,
            "animated" => false,
            "position" => "top",
            "height" => 20,
            "showPercent" => true,
            "bgColor" => array(
                "r" => 233,
                "g" => 236,
                "b" => 239,
                "a" => 1
            ),
            "color" => array(
                "r" => 33,
                "g" => 116,
                "b" => 200,
                "a" => 1
            ),
        ),
        "loading" => array(
            "type" => "button",
            "icon" => "Loading 1",
            "iconColor" => array(
                "r" => 255,
                "g" => 255,
                "b" => 255,
                "a" => 1
            ),
            "text" => "Saving..."
        ),
        "labelWidth" => 150,
        "labelDisplay" => "show",
        "fieldWidth" => "full",
        "fieldSize" => "default",
        "buttonSize" => "default",
        "buttonWidth" => "default",
        "theme" => array(
            'name' => 'default',
            'themeLabel' => 'Default',
            'bgColor' => NULL,
            'fontFamily' => 'inherit',
            'primaryColor' =>
            array(
                'r' => 0,
                'g' => 123,
                'b' => 255,
                'a' => 1,
            ),
            'input' =>
            array(
                'backgroundColor' =>
                array(
                    'r' => 255,
                    'g' => 255,
                    'b' => 255,
                    'a' => 1,
                ),
                'backgroundColorFocus' => NULL,
                'backgroundColorHover' => NULL,
                'color' =>
                array(
                    'r' => 73,
                    'g' => 80,
                    'b' => 87,
                    'a' => 1,
                ),
                'colorHover' => NULL,
                'colorFocus' => NULL,
                'borderColor' =>
                array(
                    'r' => 206,
                    'g' => 212,
                    'b' => 218,
                    'a' => 1,
                ),
                'borderColorHover' => NULL,
                'borderColorFocus' =>
                array(
                    'r' => 128,
                    'g' => 189,
                    'b' => 255,
                    'a' => 1,
                ),
                'borderWidth' => 1,
                'borderWidthFocus' => NULL,
                'borderWidthHover' => NULL,
                'boxShadow' => NULL,
                'boxShadowHover' => NULL,
                'boxShadowFocus' => '0 0 0 0.2rem rgba(0, 123, 255, 0.25)',
                'placeholderColor' => NULL,
                'borderStyle' => 'solid',
                'fontSize' => 16,
                'fontWeight' => 400,
                'borderRadius' => 4,
                'padding' => '6px 12px',
                'margin' => NULL,
            ),
            'label' =>
            array(
                'color' =>
                array(
                    'r' => 33,
                    'g' => 37,
                    'b' => 41,
                    'a' => 1,
                ),
                'fontSize' => 16,
                'fontWeight' => 500,
            ),
            'button' =>
            array(
                'backgroundColor' =>
                array(
                    'r' => 108,
                    'g' => 117,
                    'b' => 125,
                    'a' => 1,
                ),
                'backgroundColorFocus' => NULL,
                'backgroundColorHover' =>
                array(
                    'r' => 90,
                    'g' => 98,
                    'b' => 104,
                    'a' => 1,
                ),
                'color' =>
                array(
                    'r' => 255,
                    'g' => 255,
                    'b' => 255,
                    'a' => 1,
                ),
                'colorHover' => NULL,
                'colorFocus' => NULL,
                'borderColor' => array(
                    'r' => 108,
                    'g' => 117,
                    'b' => 125,
                    'a' => 1,
                ),
                'borderColorHover' => NULL,
                'borderColorFocus' => NULL,
                'borderWidth' => 1,
                'borderWidthFocus' => 1,
                'borderWidthHover' => 1,
                'boxShadow' => NULL,
                'boxShadowHover' => NULL,
                'boxShadowFocus' => NULL,
                'borderStyle' => 'solid',
                'fontSize' => 16,
                'fontWeight' => 500,
                'borderRadius' => 4,
                'padding' => '6px 12px',
                'margin' => '',
            ),
            'submitButton' =>
            array(
                'backgroundColor' =>
                array(
                    'r' => 0,
                    'g' => 123,
                    'b' => 255,
                    'a' => 1,
                ),
                'backgroundColorFocus' => NULL,
                'backgroundColorHover' => NULL,
                'color' =>
                array(
                    'r' => 255,
                    'g' => 255,
                    'b' => 255,
                    'a' => 1,
                ),
                'colorHover' => NULL,
                'colorFocus' => NULL,
                'borderColor' => array(
                    'r' => 0,
                    'g' => 123,
                    'b' => 255,
                    'a' => 1,
                ),
                'borderColorHover' => NULL,
                'borderColorFocus' => NULL,
                'borderWidth' => 1,
                'borderWidthFocus' => 1,
                'borderWidthHover' => 1,
                'boxShadow' => NULL,
                'boxShadowHover' => NULL,
                'boxShadowFocus' => NULL,
                'borderStyle' => 'solid',
                'fontSize' => 16,
                'fontWeight' => 500,
                'borderRadius' => 4,
                'padding' => '6px 12px',
                'margin' => '',
            ),
            'backButton' =>
            array(
                'backgroundColor' =>
                array(
                    'r' => 108,
                    'g' => 117,
                    'b' => 125,
                    'a' => 1,
                ),
                'backgroundColorFocus' => NULL,
                'backgroundColorHover' =>
                array(
                    'r' => 90,
                    'g' => 98,
                    'b' => 104,
                    'a' => 1,
                ),
                'color' =>
                array(
                    'r' => 255,
                    'g' => 255,
                    'b' => 255,
                    'a' => 1,
                ),
                'colorHover' => NULL,
                'colorFocus' => NULL,
                'borderColor' => array(
                    'r' => 108,
                    'g' => 117,
                    'b' => 125,
                    'a' => 1,
                ),
                'borderColorHover' => NULL,
                'borderColorFocus' => NULL,
                'borderWidth' => 1,
                'borderWidthFocus' => 1,
                'borderWidthHover' => 1,
                'boxShadow' => NULL,
                'boxShadowHover' => NULL,
                'boxShadowFocus' => NULL,
                'borderStyle' => 'solid',
                'fontSize' => 16,
                'fontWeight' => 500,
                'borderRadius' => 4,
                'padding' => '6px 12px',
                'margin' => '',
            ),
            'nextButton' =>
            array(
                'backgroundColor' =>
                array(
                    'r' => 108,
                    'g' => 117,
                    'b' => 125,
                    'a' => 1,
                ),
                'backgroundColorFocus' => NULL,
                'backgroundColorHover' =>
                array(
                    'r' => 90,
                    'g' => 98,
                    'b' => 104,
                    'a' => 1,
                ),
                'color' =>
                array(
                    'r' => 255,
                    'g' => 255,
                    'b' => 255,
                    'a' => 1,
                ),
                'colorHover' => NULL,
                'colorFocus' => NULL,
                'borderColor' => array(
                    'r' => 108,
                    'g' => 117,
                    'b' => 125,
                    'a' => 1,
                ),
                'borderColorHover' => NULL,
                'borderColorFocus' => NULL,
                'borderWidth' => 1,
                'borderWidthFocus' => 1,
                'borderWidthHover' => 1,
                'boxShadow' => NULL,
                'boxShadowHover' => NULL,
                'boxShadowFocus' => NULL,
                'borderStyle' => 'solid',
                'fontSize' => 16,
                'fontWeight' => 500,
                'borderRadius' => 4,
                'padding' => '6px 12px',
                'margin' => '',
            ),
        ),
        "bgColor" => array(
            "r" => 255,
            "g" => 255,
            "b" => 255,
            "a" => 0
        ),
        "colorScheme" => array(
            "name" => "default",
            "label" => "Theme Default"
        ),
        "fontFamily" => "Roboto",
        "customCss" => "",
        "translate" => array(
            "requiredErr" => "This field is required",
            "confirmErr" => "The confirmation input does not match",
            "nextButton" => "Next",
            "previousButton" => "Previous",
            "maxFileSize" => "Max File Size",
            "extensionErr" => "This extension not allowed",
            "fileSizeErr" => "Increase file size",
            "allowedFileTypes" => "Allowed File Types",
            "formLimitMessage" => "Form limit is over",
            "gdprValidationMessage" => "You have to accept terms & conditions",
        ),
        "gdpr" => false,
        "csrfToken" => true,
        "gdprText" => "I agree to the {privacy_policy}",
        "saveIp" => true,
        "saveUserAgent" => true,
        "saveRegisteredUser" => true,
        "advancedMode" => true,
        "emailSettings" => array(
            "smtpAddress" => "",
            "smtpPort" => 0,
            "smtpSsl" => false,
            "smtpMail" => "",
            "smtpPassword" => "",
            "autoName" => ""
        ),
        "rules" => array(
            array(
                "andor" => "form",
                "conditions" => array(
                    array(
                        "field" => "form",
                        "operator" => "equals",
                        "value" => "onLoad"
                    )
                ),
                "actions" => array()
            )
        )
    ),
    "pluginSettings" => array(
        "emailSettings" => array(
            "mailChimpSettings" => json_decode(get_option("magicform_mailchimp_settings")),
            "emailSystem" => json_decode(get_option("magicform_email_system")),
            "sendgridSettings" => json_decode(get_option("magicform_sendgrid_settings")),
            "smtpSettings" => json_decode(get_option("magicform_email_settings"))
        ),
        "googleSettings" =>  json_decode(get_option("magicform_google_settings")),
        "recaptchaSettings" => json_decode(get_option("magicform_recaptcha_settings"))
    ),
    "actions" => array(
        array(
            "type" => "saveDatabase",
            "icon" => "database",
            "label" => "Save to Database",
            "active" => true,
            "payload" => array(
                "logIp" => true,
                "logUserAgent" => true,
                "logRegisteredUser" => true
            )
        )
    ),
    "pages" => array(
        array(
            "name" => "Page 1",
            "type" => "page",
            "index" => 1,
            "step" => array(
                "title" => "Step 1",
                "icon" => null,
                "description" => ""
            ),
            "settings" => array(
                "titleVisible" => true,
                "pageIcon" => "",
                "subTitle" => "",
                "labelPosition" => "inherit",
                "labelDisplay" =>  "inherit",
                "labelWidth" =>  "inherit",
                "fieldWidth" => "inherit",
                "fieldSize" => "inherit",
                "buttonSize" => "default",
                "buttonWidth" => "default",

            ),
            "elements" => array()
        ),
        array(
            "name" => "Thank You",
            "type" => "thankyou",
            "index" => 0,
            "settings" => array(
                "message" => "<h1 class=\"ql-align-center\"><img src=\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAIAAAACACAMAAAD04JH5AAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAACHFBMVEUAAAAA/4AdzKAa0Z8Zz58Zz54bzp4az58az54az58az54az54azp4X0p4A//8Zzpwazp4a0J4az54az54az54azp8azKIgv58az58az54az50azp4bz5wr1aoZ0J8a0J0az54az54az54az58Yzp4az54az54Zzp0ZzZsazp4az54az54az50bz58azp4Zzp0az54cz58Zz54a0J0czZsazJkazp4az54az54V1ZUZ0J4az54az58az58az54bz54az54Y0KAaz54bz50Aqqobz54X0aIazp4a0J0U2J0W05sazp4az54X0aIfzJkaz54b0ZsYz58bz54c0KEaz54Yzp4az54Vyp8az54az58Zzp8bz54cxqobz58az58bzp0az54Yzp0az54b0J0Z0J0a0J0az54az54az54zzJkbzp0Zz54azp4azp0az58az58k25Ib0J8bzp0a0J0bz54a0J0az54az58az54a0Z4az54a0J4c0Jwaz54a0J4a0J4b0J8az54az50azJ8X0Zsbzp0Zz54az54azp0az54a0J4az54c0Z4b0J4ZzaAazp0az50az54Yzp4az54az54b0J4azp4Z0Z8b0J0az58b0J4az54az54az54azp4X0Zwa0J4a0J8az54bzqAaz50Zz58az54dzp0a0J4azJkaz54a0Z0az54az54a0J4Av4Abz54az54az54AAABuBx9xAAAAsnRSTlMAAiNNb46twdTm7vVuIgEfWZHI9PNYHgiU29mTSwZSp/LxpVAq6+mNKUSz/sRV0lPQSrW8LgqI/PsMR+DeRf2Z1iv2YAOQC7K3DRfNyxYZ2Bwg3RvhFdUYsLRd0wmPii+6Sc5WUUa7i+oFc3m9TqS5B3JokqM8+lr4Mu1XNvDHbKyqayghXr/kY+/3djd8M3jRvj/G2kx+PaJ/canoibgsjIffQ6/J3BrXFK4nz0/nBMDlFQzb4QAAAAFiS0dEAIgFHUgAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAAHdElNRQfkAx4LJyBbmdQEAAAFT0lEQVR42sWb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\"></h1><h3 class=\"ql-align-center\">Form Submitted Successfully!</h3><p class=\"ql-align-center\">We will contact you as soon as possible.</p>",
                "action" => "showThankYou",
                "redirectUrl" => "",
                "successMessage" => "Form Submitted Successfully!"
            ),
            "elements" => array()
        )
    )
);