<?php

if (!function_exists("magicform_getValidationType")) {
    function magicform_getValidationType($inputId, $validation, $onChange, $elementSettings)
    {
        if ($elementSettings->type == "radioButton" || $elementSettings->type == "checkBox" || $elementSettings->type == "thumbnailSelector") {
            $result = $onChange ? '$("input[name=\'' . esc_js($inputId) . '_value\']").on("blur change", function() {' : '';
        } else if ($elementSettings->type == "starRating" || $elementSettings->type == "scaleRating") {
            $result = $onChange ? '$("input[name=\'' . esc_js($inputId) . '\']").on("blur change", function() {' : '';
        } else {
            $result = $onChange ? '$("#' . esc_js($inputId) . '").on("blur change", function() {' : '';
        }

        $result .= "var errorMessages = [];";

        $type = substr($inputId, 0, strpos($inputId, "_"));
        if ($type == "radioButton" || $type == "checkBox" || $type == "thumbnailSelector") {
            $result .= "var input = $(\"input[name='" . esc_js($inputId) . "_value']\");";
            $result .= "var val = input.filter(':checked').val();";
            $result .= 'val = typeof val == "undefined" ? "" : val;';
        } else if ($type == "starRating" || $type == "scaleRating" || $type == "termsOfUse") {
            $result .= "var input = $(\"input[name='" . esc_js($inputId) . "']\");";
            $result .= "var val = input.filter(':checked').val();";
            $result .= 'val = typeof val == "undefined" ? "" : val;';
        }else if ($type =="multiSelect") {
            $result .= 'var input = $("#' . esc_js($inputId) . '");';
            $result .= 'var val =  $("#' . esc_js($inputId) . ' option:selected").val();';
            $result .= 'val = typeof val == "undefined" ? "" : val;';
        }  
        else {
            $result .= 'var input = $("#' . esc_js($inputId) . '");';
            $result .= 'var val = input.val();';
        }
        $result .= 'var formGroup = input.closest(".mf-form-group");';
        $result .= 'if(formGroup.is(":visible") || input.attr("type")=="hidden" ){';
        $result .= 'val = String(val).trim();';

        switch ($validation->type) {
            case "required":
                $result .= 'if (val == "") {';
                break;
                // eşitlik
            case "match":
                $result .= 'if (val != String($("#' . esc_js($validation->payload->id) . '").val()).trim()) {';
                break;
            case "alpha":
                $result .= 'var pattern = new RegExp(/^([A-Za-z\u00C0-\u00ff\u0100-\u017f\u0180-\u024f\u1e00-\u1eff' . ($validation->payload->whitespace ? " " : "") . ']*)$/i);';
                $result .= 'if (val != "" && !pattern.test(val)) {';
                break;
            case "alphanumeric":
                $result .= 'var pattern = new RegExp(/^[a-z0-9' . ($validation->payload->whitespace ? " " : "") . ']+$/i);';
                $result .= 'if (val != "" && !pattern.test(val)) {';
                break;
            case "numeric":
                $result .= 'var pattern = new RegExp(/^[0-9' . ($validation->payload->whitespace ? " " : "") . ']+$/i);';
                $result .= 'if (val != "" && !pattern.test(val)) {';
                break;
            case "email":
                $result .= 'var pattern = new RegExp(/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/i);';
                $result .= 'if (val != "" && !pattern.test(String(val).toLowerCase())) {';
                break;
            case "regex":
                $result .= 'var pattern = new RegExp(/^(' .$validation->payload->regex . ')+$/i);';
                $result .= 'if (val != "" && !pattern.test(val)) {';
                break;
            case "greaterthan":
                $result .= "val = parseInt(val);\n";
                $result .= 'if (val != "" && (isNaN(val) || val' . ($validation->payload->include ? "<" : "<=") . esc_js($validation->payload->value) . ')) {';
                break;
            case "lessthan":
                $result .= "val = parseInt(val);\n";
                $result .= 'if ( val != "" && (isNaN(val) || val' . ($validation->payload->include ? ">" : ">=") . esc_js($validation->payload->value) . ')) {';
                break;
            case "inlist":
                $result .= 'var list = ' . json_encode($validation->payload->list) . ';';
                $result .= 'if (val != "" && ' . ($validation->payload->type == "white" ? "!" : "") . 'list.includes(val)) {';
                break;
            case "domain":
                $result .= 'var pattern = new RegExp(/^(?!(https:\/\/|http:\/\/|www\.|mailto:|smtp:|ftp:\/\/|ftps:\/\/))(((([a-zA-Z0-9])|([a-zA-Z0-9][a-zA-Z0-9\-]{0,86}[a-zA-Z0-9]))\.(([a-zA-Z0-9])|([a-zA-Z0-9][a-zA-Z0-9\-]{0,73}[a-zA-Z0-9]))\.(([a-zA-Z0-9]{2,12}\.[a-zA-Z0-9]{2,12})|([a-zA-Z0-9]{2,25})))|((([a-zA-Z0-9])|([a-zA-Z0-9][a-zA-Z0-9\-]{0,162}[a-zA-Z0-9]))\.(([a-zA-Z0-9]{2,12}\.[a-zA-Z0-9]{2,12})|([a-zA-Z0-9]{2,25}))))$/i);';
                $result .= 'if (val != "" && !pattern.test(String(val).toLowerCase())) {';
                break;
            case "ip":
                $result .= 'var pattern = new RegExp(/^((25[0-5]|(2[0-4]|1[0-9]|[1-9]|)[0-9])(\.(?!$)|$)){4}$/i);';
                $result .= 'if (val != "" && !pattern.test(val)) {';
                break;
            case "creditCard":
                $result .= 'var pattern = new RegExp(/^(?:4[0-9]{12}(?:[0-9]{3})?|[25][1-7][0-9]{14}|6(?:011|5[0-9][0-9])[0-9]{12}|3[47][0-9]{13}|3(?:0[0-5]|[68][0-9])[0-9]{11}|(?:2131|1800|35\d{3})\d{11})$/i);';
                $result .= 'if (val != "" && !pattern.test(val.replace(/\s/g,""))){';
                break;
            case "contains":
                $result .= 'var countainList =' . esc_js(json_encode($validation->payload->containList)) . ';';
                $result .= 'var countainControl = false;';
                $result .= 'countainList.map((item) => {var regex = new RegExp(`${item}`) ;if(regex.test(val)){countainControl = true;}});';
                $result .= 'if (val != "" && ' . ($validation->payload->type == "contains" ? "!" : "") . 'countainControl){';
                break;
            case "multiSelectCount":
                $result .= 'var countLimit =' . esc_js(json_encode($validation->payload->selectCount)) . ';';
                $result .= "var numberOfChecked = ". ($elementSettings->type =="multiSelect"?"input.find('option:selected').length":"input.filter(':checked').length") ." ;";
                if ($validation->payload->conditionType == "equal")
                    $result .= 'if (numberOfChecked != countLimit){';
                else if ($validation->payload->conditionType == "greaterThan")
                    $result .= 'if (numberOfChecked <= countLimit){';
                else if ($validation->payload->conditionType == "lessThan")
                    $result .= 'if (numberOfChecked >= countLimit){';
                break;
            case "date":
                $result .= 'var dateFormats = [ 
                        {"format":"dd.mm.yyyy","pattern":/^\s*(3[01]|[12][0-9]|0?[1-9])\.(1[012]|0?[1-9])\.((?:19|20)\d{2})\s*$/ },
                        {"format":"dd/mm/yyyy","pattern":/^([0-2][0-9]|(3)[0-1])(\/)(((0)[0-9])|((1)[0-2]))(\/)\d{4}$/ },
                        {"format":"mm/dd/yyyy","pattern":/^(0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])[- /.](19|20)\d\d/ },
                        {"format":"yyyy-mm-dd", "pattern":/([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))/}
            ];';
                $result .= 'var regex = dateFormats.find(item=>item.format == "' . esc_js($validation->payload->format) . '");';
                $result .= 'var pattern = new RegExp(regex.pattern);';
                $result .= 'if (val != "" && !pattern.test(val)) {';
                break;
        }
        $result .= 'var errorMessage = buildErrorMessage("' . esc_js(addslashes($validation->payload->errorMessage)) . '",val);';
        $result .= 'errorMessages.push(errorMessage);';
        $result .= '}';
        $result .= magicform_buildError();
        $result .= '}';
        $result .= $onChange ?  '});' : '';
        return $result;
    }
}

if (!function_exists("magicform_buildError")) {
    function magicform_buildError()
    {
        $result = 'if (errorMessages.length > 0) {';
        $result .= 'isFormValid=false;';
        $result .= 'var formGroup = input.closest(".mf-form-group");';
        $result .= 'formGroup.addClass("mf-has-error").find(".mf-error").html(errorMessages[0]).show();';
        $result .= '}';
        return $result;
    }
}

if (!function_exists("magicform_getValidation")) {
    function magicform_getValidation($inputId, $validations, $elementSettings, $onChange = false)
    {
        $result = "";
        foreach ($validations as $validation) {
            $result .= magicform_getValidationType($inputId, $validation, $onChange, $elementSettings);

            if ($validation->type == "match") {
                switch ($elementSettings->type) {
                    case "password":
                    case "email":
                        $result .= magicform_getValidationType($inputId . "_confirm", $validation, $onChange, $elementSettings);
                        break;
                }
            }

            // birden fazla input içeren elemanlar için validasyon
            if ($validation->type == "required") {
                switch ($elementSettings->type) {
                    case "password":
                    case "email":
                        if ($elementSettings->payload->confirmInput) {
                            $result .= magicform_getValidationType($inputId . "_confirm", $validation, $onChange, $elementSettings);
                        }
                        break;
                    case "name":
                        if ($elementSettings->payload->surname) {
                            $result .= magicform_getValidationType($inputId . "_surname", $validation, $onChange, $elementSettings);
                        }
                        break;
                    case "address":
                        if ($elementSettings->payload->address1Check) {
                            $result .= magicform_getValidationType($inputId . "_address1", $validation, $onChange, $elementSettings);
                        }
                        if ($elementSettings->payload->cityCheck) {
                            $result .= magicform_getValidationType($inputId . "_city", $validation, $onChange, $elementSettings);
                        }
                        if ($elementSettings->payload->countryCheck) {
                            $result .= magicform_getValidationType($inputId . "_country", $validation, $onChange, $elementSettings);
                        }
                        if ($elementSettings->payload->stateCheck) {
                            $result .= magicform_getValidationType($inputId . "_state", $validation, $onChange, $elementSettings);
                        }
                        break;
                }
            }
        }
        return $result;
    }
}
?>

<?php 

    if(!function_exists('magicform_file_input_validation')){
        function magicform_file_input_validation($extensionErr, $fileSizeErr) {
                $result = 'var fileTypes = [{"type":"aac","mime":"audio/aac"},{"type":"abw","mime":"application/x-abiword"},
                {"type":"arc","mime":"application/octet-stream"},{"type":"avi","mime":"video/x-msvideo"},
                {"type":"azw","mime":"application/vnd.amazon.ebook"},{"type":"bin","mime":"application/octet-stream"},
                {"type":"bmp","mime":"image/bmp"},{"type":"bz","mime":"application/x-bzip"},
                {"type":"bz2","mime":"application/x-bzip2"},{"type":"csh","mime":"application/x-csh"},
                {"type":"css","mime":"text/css"},{"type":"csv","mime":"text/csv"},{"type":"doc","mime":"application/msword"},
                {"type":"docx","mime":"application/vnd.openxmlformats-officedocument.wordprocessingml.document"},
                {"type":"eot","mime":"application/vnd.ms-fontobject"},{"type":"epub","mime":"application/epub+zip"},
                {"type":"gif","mime":"image/gif"},{"type":"htm","mime":"text/html"},{"type":"html","mime":"text/html"},
                {"type":"ico","mime":"image/x-icon"},{"type":"ics","mime":"text/calendar"},{"type":"jar","mime":"application/java-archive"},
                {"type":"jpeg","mime":"image/jpeg"},{"type":"jpg","mime":"image/jpeg"},{"type":"js","mime":"application/javascript"},
                {"type":"json","mime":"application/json"},{"type":"mid","mime":"audio/midi audio/x-midi"},{"type":"mpeg","mime":"video/mpeg"},
                {"type":"mp4","mime":"video/mp4"},{"type":"mp3","mime":"audio/mp3"},{"type":"mov","mime":"video/quicktime"},
                {"type":"mpkg","mime":"application/vnd.apple.installer+xml"},{"type":"odp","mime":"application/vnd.oasis.opendocument.presentation"},
                {"type":"ods","mime":"application/vnd.oasis.opendocument.spreadsheet"},{"type":"odt","mime":"application/vnd.oasis.opendocument.text"},
                {"type":"oga","mime":"audio/ogg"},{"type":"ogv","mime":"video/ogg"},{"type":"ogx","mime":"application/ogg"},
                {"type":"otf","mime":"font/otf"},{"type":"png","mime":"image/png"},{"type":"pdf","mime":"application/pdf"},
                {"type":"ppt","mime":"application/vnd.ms-powerpoint"},{"type":"pptx","mime":"application/vnd.openxmlformats-officedocument.presentationml.presentation"},
                {"type":"rar","mime":"application/rar"},{"type":"rar","mime":"application/octet-stream"},{"type":"rar","mime":"application/vnd.rar"},{"type":"rar","mime":"application/x-rar-compressed"},{"type":"rtf","mime":"application/rtf"},{"type":"sh","mime":"application/x-sh"},
                {"type":"svg","mime":"image/svg+xml"},{"type":"swf","mime":"application/x-shockwave-flash"},{"type":"tar","mime":"application/x-tar"},
                {"type":"tif","mime":"image/tiff"},{"type":"tiff","mime":"image/tiff"},{"type":"ts","mime":"application/typescript"},
                {"type":"ttf","mime":"font/ttf"},{"type":"txt","mime":"text/plain"},{"type":"vsd","mime":"application/vnd.visio"},
                {"type":"wav","mime":"audio/wav"},{"type":"weba","mime":"audio/webm"},{"type":"webp","mime":"image/webp"},
                {"type":"woff","mime":"font/woff"},{"type":"woff2","mime":"font/woff2"},{"type":"xhtml","mime":"application/xhtml+xml"},
                {"type":"xls","mime":"application/vnd.ms-excel"},{"type":"xlsx","mime":"application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"},
                {"type":"xml","mime":"application/xml"},{"type":"xul","mime":"application/vnd.mozilla.xul+xml"},
                {"type":"zip","mime":"application/zip"},{"type":"3gp","mime":"video/3gpp"},{"type":"3g2","mime":"video/3gpp2"},
                {"type":"7z","mime":"application/x-7z-compressed"}];';
                $result .=  'var input = $(this);';
                $result .= 'var fileSizeType = $(this).attr("file-size-type");';
                $result .= 'var allowFileType = $(this).attr("allow-file-types").split(",");';
                $result .= 'var allowFileSize = $(this).attr("allow-size");';
                $result .= 'var mimes = [];';
                $result .= 'allowFileType.map((item) => {
                    mimes.push(fileTypes.find(i=>i.type == item).mime);
                });';
                $result .= 'if(this.files.length<=0){
                                return false;
                            }';
                $result .= 'var fileSize = this.files[0].size;';
                $result .= 'var fileType = this.files[0].type;';
                $result .= 'var fileName = this.files[0].name;';
                $result .= "var extensionErr = '".$extensionErr. "';";
                $result .= "var fileSizeErr = '". $fileSizeErr . "';";
            
                $result .= 'switch(fileSizeType){';
                $result .=    'case "KB":';
                $result .=        'allowFileSize *= 1024;';
                $result .=        ' break;';
                $result .=    'case "MB":';
                $result .=         'allowFileSize *= Math.pow(1024, 2);';
                $result .=         'break;';
                $result .=     'case"GB":';
                $result .=         'allowFileSize *= Math.pow(1024, 3);';
                $result .=         'break;';
                $result .=  '}';

                $result .=  'var errorMessage = "";';
                $result .=  'var errorMessages = [];';

                $result .=  'if(fileSize >= allowFileSize){';
                $result .=   'errorMessage = buildErrorMessage(fileSizeErr,fileName);';
                $result .=  '}';

                $result .=  'if(!mimes.includes(fileType)){';
                $result .=    'errorMessage = buildErrorMessage(extensionErr,fileName);';
                $result .=  '}';

                $result .=  'if(errorMessage != ""){';
                $result .=     'errorMessages.push(errorMessage);';
                $result .= magicform_buildError();
                $result .=   '}';
                return $result;
        }
    }
?>
<script type="text/javascript">
    (function($) {

        /**
         * Form serialize as object
         */
        $.fn.serializeObject = function() {
            var result = {};
            var extend = function(i, element) {
                var node = result[element.name];

                // If node with same name exists already, need to convert it to an array as it
                // is a multi-value field (i.e., checkboxes)
                if ('undefined' !== typeof node && node !== null) {
                    if ($.isArray(node)) {
                        node.push(element.value);
                    } else {
                        result[element.name] = [node, element.value];
                    }
                } else {
                    result[element.name] = element.value;
                }
            };

            $.each(this.serializeArray(), extend);
            return result;
        };

        var formId = <?php echo intval($formId); ?>;
        var pageCount = 0;

        const buildErrorMessage = function(errorMessage, value) {
            errorMessage = errorMessage.replace(/{[^{}]+}/g, function(key) {
                var key = key.replace(/[{}]+/g, "");
                if (key == "value") {
                    return value;
                }
            });
            return errorMessage;
        }

        var showPage = function(index, direction = "next") {
            var form = $("#magicform-" + formId);
            form.find(".mf-pages>.mf-page").hide();
            var page = form.find(".mf-pages>.mf-page[data-index='" + index + "']");
            if (page.attr("data-hidden")) {
                if (direction == "next") {
                    showPage(index + 1);
                } else {
                    showPage(index - 1, "back");
                }
                return false;
            }
            page.show();
            pageCount = form.find(".mf-pages>.mf-page:not([data-hidden])").length - 1;

            var prevHiddenPages = page.prevAll("[data-hidden]").length;
            var prevHiddenPagesCount = prevHiddenPages > 0 ? parseFloat(prevHiddenPages) : 0;

            var percent = index == 0 ? 0 : parseFloat((parseFloat(index - prevHiddenPagesCount) / pageCount) * 100).toFixed(0);
            if (form.find(".mf-progress-bar").is('[percent]')) {
                form.find(".mf-progress-bar").css("width", percent + "%").attr("aria-valuenow", percent).text(percent + "%");
            } else {
                form.find(".mf-progress-bar").css("width", percent + "%")
            }
            form.find(".mf-multistep-nav>li").removeClass("mf-multistep-nav-item-active");
            form.find(".mf-multistep-nav>li[data-index='" + index + "']").addClass("mf-multistep-nav-item-active");
        }

        $(document).ready(function() {
           
            var form = $("#magicform-" + formId);
            pageCount = form.find(".mf-pages>.mf-page").length - 1;
        
            /**
             * Show First Page
             */
            showPage(0, "next");
            <?php $fileUploadElement = false; 
                foreach ($allElements as $element){
                        if($element->type == "fileUpload")
                            $fileUploadElement = true;
                } 
            
            if($fileUploadElement ){
                echo "$('.mf-custom-file-input').on('change', function() {";
                    echo magicform_file_input_validation($formSettings->translate->extensionErr, $formSettings->translate->fileSizeErr );
                 echo "});";
            }
            ?>

            form.on("submit", function(e) {
                e.preventDefault();
                var isFormValid = true;

                <?php
                if ($validationCheck) {
                    foreach ($validations as $inputId => $validation) :
                        echo magicform_getValidation($inputId, $validation, $allElements[$inputId]);
                    endforeach;
                } ?>

                <?php
                if ($formSettings->gdpr) {
                    echo 'if(!form.find(".mf-gdpr-id").prop("checked")){';
                    echo 'var input=form.find(".mf-gdpr-id").parent();';
                    echo 'errorMessages=["' . $formSettings->translate->gdprValidationMessage . '"];';
                    echo magicform_buildError();
                    echo '}';
                } ?>

                <?php 
                    if($fileUploadElement){
                        echo  '$.each(form.find("input[type=file]"), function() {';
                        echo magicform_file_input_validation($formSettings->translate->extensionErr, $formSettings->translate->fileSizeErr );
                        echo "});"; 
                    }
                ?>
               

                var submitType = "<?php echo esc_js($submitType) ?>";
                var validationCheck = <?php echo ($validationCheck) ? 1 : 0 ?>;
                if (isFormValid) {

                    // get hidden inputs conditional logic
                    var hiddenInputs = [];
                    $.each(form.find("div[data-hidden='true'] :input"), function(k, v) {
                        var name = $(v).attr("name");
                        if (name && !magicform_inArray(name, hiddenInputs)) {
                            hiddenInputs.push(name);
                        }
                    });
                    var formData = new FormData();
                    // Upload file loading
                    $.each(form.find("input[type='file']"), function() {
                        var nativeInput = $(this)[0];
                        if (nativeInput.files.length > 0) {
                            formData.append(nativeInput.id, nativeInput.files[0]);
                        }
                    });

                    formData.append("action", "magicform_save_submission");
                    formData.append("formId", formId);
                    formData.append("pageTitle", document.title);
                    formData.append("pageUrl", window.location.href);
                    formData.append("submitType", submitType);
                    formData.append("validationCheck", validationCheck);
                    formData.append("data", JSON.stringify(form.serializeObject()));
                    formData.append("hiddenInputs", JSON.stringify(hiddenInputs));
                    form.addClass("mf-form-loading");
                    $.ajax({
                        url: magicFormSettings.ajaxUrl,
                        data: formData,
                        type: "POST",
                        contentType: false,
                        processData: false,
                        dataType: "json",
                        success: function(result) {
                            form.removeClass("mf-form-loading");
                            if (result.success) {

                                // Trigger custom event
                                form.trigger("magicform:submitForm");
                                var submissionId = result.data.submission_id;
                                // Execute frontend actions
                                <?php
                                if (isset($actions) && is_array($actions) && count($actions) > 0) {
                                    foreach ($actions as $action) {
                                        if ($action->active){
                                            switch ($action->type) {
                                                case "googleAnalytics":
                                                    include "actions/google-analytics.php";
                                                break;
                                                case "stripe":
                                                    include "actions/stripe.php";
                                                break;
                                                case "paypal":
                                                    include "actions/paypal.php";
                                                break;
                                            }
                                        }
                                    }
                                }
                                ?>

                                var action = result.data.action;
                                if (action == "stayOnForm") {
                                    form.get(0).reset();
                                    form.find(".mf-result").html('<div class="mf-alert mf-alert-success"><i class="fas fa-check"></i>' + result.data.successMessage + '</div>');

                                } else if (action == "redirectUrl") {
                                    window.location = result.data.redirectUrl;

                                } else if (action == "showThankYou") {
                                    var thankYouHtml = form.find(".mf-thankyou").html();
                                    thankYouHtml = form.find(".mf-thankyou").html().replace(/\{([^{}]*)(\|){1}([A-Za-z0-9_]+)\}/g, function(key) {
                                        var text = key.replace(/[{}]+/g, "");
                                        var inputId = text.split("|")[1];
                                       
                                       if(inputId.search("all_form_data") != -1){
                                            var value = "";
                                            var formElements = form.serializeObject();
                                            for (const element in formElements){
                                                if(element.search("g-recaptcha-response") == -1 && element.search("_wp_http_referer") == -1 && element.search("magicform_token") == -1){
                                                    if(Array.isArray(formElements[element]))
                                                        value += "<div>" + formElements[element].join() + "</div>";
                                                    else 
                                                        value += "<div>" + formElements[element] + "</div>";
                                                }
                                            }
                                            return value;
                                       } 
                                       else if(inputId.search("checkBox") !=-1 || inputId.search("radioButton") !=-1 || inputId.search("thumbnailSelector") !=-1){
                                            inputId += "_value";
                                            var checked = [];
                                            $.each($("input[name='"+ inputId +"']:checked"), function(){
                                                checked.push($(this).val());
                                            });
                                            return checked.join()
                                        }else
                                            return $("#" + inputId).length > 0 ? $("#" + inputId).val() : "";                                        
                                    });
                                    form.find(".mf-thankyou").html(thankYouHtml);
                                    showPage(form.find(".mf-pages>.mf-page").length - 1);
                                    form.find(".mf-progress-bar-animated").removeClass("mf-progress-bar-animated");
                                    $('html,body').animate({
                                        scrollTop: form.offset().top - 40
                                    }, 500, 'linear');
                                }
                            } else {
                                // Validation not passed
                                if (result.data && result.data.type == "validation") {
                                    if (result.data.errors) {
                                        $.each(result.data.errors, function(k, v) {
                                            var input = $("#" + k);
                                            if (input.length == 0) {
                                                input = $("input[name='" + k + "']");
                                            }
                                            if (input.is(":visible")) {
                                                var val = String(input.val()).trim();
                                                if (input.attr("type") == "radio" || input.attr("type") == "checkbox" || input.attr("type") == "thumbnailSelector" ) {
                                                    val = input.filter(":checked").val();
                                                    val = typeof val == "undefined" ? "" : val;
                                                }
                                                var errorMessages = [];
                                                if (v) errorMessages.push(v);
                                                <?php echo magicform_buildError() ?>
                                            }
                                        });
                                    }
                                } else if (result.data && result.data.type == "fileuploads") {
                                    var input = $("#" + result.data.id);
                                    var errorMessages = [];
                                    if (result.data.error) errorMessages.push(result.data.error);
                                    <?php echo magicform_buildError(); ?>
                                }
                            }
                        },
                        error: function(error) {
                            form.removeClass("mf-form-loading");
                            if (error.responseJSON) {
                                alert(error.responseJSON.data);
                            } else {
                                alert("Check your internet connection!");
                            }
                        }
                    });
                }
            });
            var paymentStatus = <?php echo isset($paymentStatus) && $paymentStatus ?1:0?>;
            if(paymentStatus) {
                var thankYouHtml = form.find(".mf-thankyou").html();
                    thankYouHtml = form.find(".mf-thankyou").html().replace(/\{([^{}]*)(\|){1}([A-Za-z0-9_]+)\}/g, function(key) {
                        var text = key.replace(/[{}]+/g, "");
                        var inputId = text.split("|")[1];
                        return $("#" + inputId).length > 0 ? $("#" + inputId).val() : "";
                });
                form.find(".mf-thankyou").html(thankYouHtml);
                showPage(form.find(".mf-pages>.mf-page").length - 1);
                form.find(".mf-progress-bar-animated").removeClass("mf-progress-bar-animated");
                $('html,body').animate({
                    scrollTop: form.offset().top - 40
                }, 500, 'linear');
            }
            /**
             * Next button click on multi page forms
             */
            form.find(".mf-next-btn").on("click", function() {
                var isFormValid = true;
                var currentPage = parseFloat(form.find(".mf-page:visible").attr("data-index"));
                // var id = "#magicform-<?php echo intval($formId) ?>";
                $('html,body').animate({
                    scrollTop: form.offset().top - 40
                }, 500, 'linear');

                <?php
                if ($validationCheck) {
                    foreach ($pageElements as $pageIndex => $elements) {
                        echo "if(currentPage==" . $pageIndex . "){";
                        foreach ($elements as $inputId => $input) {
                            if (isset($validations[$inputId])) {
                                echo magicform_getValidation($inputId, $validations[$inputId], $allElements[$inputId]);
                            }
                        }
                        echo "}";
                    }
                }
                ?>

                $.each($('.mf-page-'+currentPage).find("input[type='file']"), function() {
                    <?php echo magicform_file_input_validation($formSettings->translate->extensionErr, $formSettings->translate->fileSizeErr );?>
                });

                if (isFormValid) {
                    showPage(currentPage + 1);
                }
            });
            var paymentSucces = "<?php echo (isset($_GET['success']) && $_GET['success'] == 1)?$_GET['success']:""?>";
            if(paymentSucces == "1"){
                var form = $("#magicform-" + formId);
                var thankYouHtml = form.find(".mf-thankyou").html();
                thankYouHtml = form.find(".mf-thankyou").html().replace(/\{([^{}]*)(\|){1}([A-Za-z0-9_]+)\}/g, function(key) {
                    var text = key.replace(/[{}]+/g, "");
                    var inputId = text.split("|")[1];
                    return $("#" + inputId).length > 0 ? $("#" + inputId).val() : "";
                });
                
                form.find(".mf-thankyou").html(thankYouHtml);
                showPage(form.find(".mf-pages>.mf-page").length - 1);

                form.find(".mf-progress-bar-animated").removeClass("mf-progress-bar-animated");
                
                $('html,body').animate({
                    scrollTop: form.offset().top - 40
                }, 500, 'linear');
            }
            

            /**
             * Previous button click on multi page forms
             */
            form.find(".mf-back-btn").on("click", function() {
                var currentPage = parseFloat($("#magicform-<?php echo intval($formId); ?> .mf-page:visible").attr("data-index"));
                showPage(currentPage - 1, "back");
            });

            <?php
            if ($validationCheck) {
                foreach ($validations as $inputId => $validation) :
                    echo magicform_getValidation($inputId, $validation, $allElements[$inputId], true);
                endforeach;
            } ?>
        });
    })(jQuery);
</script>