<?php

if (!function_exists("magicform_textParser")) {
    /**
     * Text parser for output data
     *
     * @param string $value
     * @param object $formData
     * @param object $allElements
     * @param array $formSettings
     * @param string $pageTitle
     * @param string $pageUrl
     * @return void
     */
    function magicform_textParser($value, $formData, $allElements, $formSettings, $pageTitle, $pageUrl, $submission_id)
    {
        $result = preg_replace_callback('/\{([^{}]+)\}/', function ($match) use ($formData, $allElements, $formSettings, $pageTitle, $pageUrl, $submission_id) {
            $variable = $match[1];
            $current_user = wp_get_current_user();
            if (strpos($variable, 'form_name')!== false) {
                return $formSettings->settings->name;
            }
            if (strpos($variable, 'page_title') !== false) {
                return $pageTitle;
            }
            if (strpos($variable, 'page_url')!== false) {
                return $pageUrl;
            }
            if (strpos($variable, "time")!==false) {
                return magicform_date_format($formSettings->settings);
            }
            if (strpos($variable, "user_name")!==false) {
                return $current_user->user_login;
            }
            if (strpos($variable, "user_id")!==false) {
                return $current_user->ID;
            }  
            if (strpos($variable, "user_email")!==false) {
                return $current_user->user_email;
            }
            if (strpos($variable, "user_agent")!==false) {
                return  isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;
            }
            if (strpos($variable, "submission_id")!==false) {
                return  $submission_id;
            }
            
            if (strpos($variable, "|") !== false) {
                $parts = explode("|", $variable);
                $inputId = $parts[1];
                return isset($formData[$inputId]) ? magicform_view_inputs($inputId, $formData[$inputId]) : "";
            }

            if ($variable == "all_form_data") {
                // used inline style because of used in email and print page
                $table = '<table width="100%" cellspacing="0" cellpadding="4" style="border-collapse:collapse; max-width: 800px; margin: 0 auto; border-top:1px dashed #999; border-bottom:1px dashed #999" class="container">';
                $table .= '<tr>';
                $table .= '<td class="mobile mobileOff" valign="top" width="200" style="padding:6px 15px; border-bottom:1px solid #CCC;"><b>Field Name</b></td>';
                $table .= '<td class="mobile mobileOff" valign="top" style="padding:6px 15px; border-bottom:1px solid #CCC;"><b>Field Value</b></td>';
                $table .= '</tr>';
                foreach ($formData as $field_id => $field_value) :
                    $field_name = isset($allElements[$field_id]) ? $allElements[$field_id]->payload->labelText : $field_id;
                    if(!strpos($field_name, 'recaptcha') && !strpos($field_name, 'magicform_token')){
                        $field_name = $field_name == "mf-gdpr" ? "Gdpr Consent" : $field_name;
                        $table .= '<tr>';
                        $table .= '<td class="mobile fieldname" valign="top" style="padding:6px 15px;"><b>' . $field_name . '</b></td>';
                        if(strpos($field_id, "productList")!==false){
                            $values = explode(" ", implode(" ",(array)$field_value));
                            $details = magicform_product_list_detail($values, $allElements[$field_id]);
                            $table .= '<td class="mobile fieldvalue" valign="top" style="padding:6px 15px;">' . magicform_product_list_mail($details, $allElements); '</td>';
                        }else {
                            if(gettype($field_value) == "array" || gettype($field_value) == "object"){
                                $table .= '<td class="mobile fieldvalue" valign="top" style="padding:6px 15px;">' . implode(", ",(array) magicform_view_inputs($field_id, $field_value)) . '</td>';
                            }else {
                                $table .= '<td class="mobile fieldvalue" valign="top" style="padding:6px 15px;">' . $field_value . '</td>';							
                            }
                        }
                        $table .= '</tr>';
                    }
                endforeach;
                $table .= '</table>';
                return $table;
            }
        }, $value);
        return $result;
    }
}
