<?php

if(!function_exists("magicform_password_view")){
    function magicform_password_view($password){
        
        if(empty($password))
            return;

        $passwordLenght = strlen($password);
        $maskPassword = substr($password, 0, 3);
        
        for($i = 0; $i<$passwordLenght; $i++)
            $maskPassword .= "*";

        return $maskPassword;
    }
}


if(!function_exists("magicform_mail_list_fields_parse")){
    function magicform_mail_list_fields_parse($formData, $payload) {
        $data = array();
         foreach ($formData as $field_id => $field_value) {
            if ($field_id == $payload->selectMailInput){
                if(gettype($field_value) == "object" || gettype($field_value) == "array"){
                    $data["email"] = ((array)$field_value)['email'];
                }else
                    $data["email"]  = $field_value;
            }
            else if ($field_id == $payload->selectFirstName){
                if(gettype($field_value) == "object" || gettype($field_value) == "array"){
                    $data["firstName"]  = implode("",(array)$field_value);
                }else
                    $data["firstName"]  = $field_value;
            }
            else if ($field_id == $payload->selectLastName){
                if(gettype($field_value) == "object" || gettype($field_value) == "array"){
                    $data["lastName"] = implode("",(array)$field_value);
                }else
                    $data["lastName"] = $field_value;
            }
        }
        return $data;
    }
}

if(!function_exists("magicform_product_list_mail")){
        function magicform_product_list_mail($details, $allElements) {
            $result = "";
            foreach($details as $key => $value){
                $result .= "<div style='display: flex; flex-wrap:wrap;  justify-content:space-around; flex-direction:row;'>";
                $result .= $details[$key]["Props"]['Image']?("<img style=' height:50px; width:50px;  object-fit: cover;' src='". $details[$key]["Props"]['Image'] ."'>"):"";
                $result .= "<div style='margin-left: 5px;'>". esc_html("Name", "magicform") . " : " . $details[$key]["Props"]["Name"] . "</div>";

                foreach($details[$key]['Select'] as $value){
                    $result .= "<div style='margin-left: 5px;'> " . array_keys($value)[0]. " : " .array_values($value)[0] . "</div>";
                    $result .= "<div style='margin-left: 5px;'> " . array_keys($value)[1]. " : " .array_values($value)[1] . "</div>";
                }

                $result .= "<div style='margin-left: 5px;'>". esc_html("Quantity", "magicform") . " : " . $details[$key]['Quantity'] . "</div>";
                $result .= "<div style='margin-left: 5px;'>" . esc_html("Price", "magicform") . " : " .$details[$key]["Props"]['Price'] . "</div>";
                
                $result .= "</div>";
                $result .= "<hr>";
            }	
    
            $result .= "<div style='margin-left: 5px;'>" .esc_html("Total", "magicform"). " : " . $allElements[$field_id]->payload->currencySymbol . number_format(magicform_product_list_total($values, $allElements[$field_id]),2) ."</div>";
            $result .= "<div style='margin-left: 5px;'>" . esc_html("Payment Status", "magicform"). " : " . ($detail->payment_status?esc_html("Paid", "magicform"):esc_html("Not Paid", "magicform")) . "</div>";
            return $result;
        }
        
    }


    if(!function_exists("magicform_calculate_field")){

    function magicform_calculate_field($formData, $calculateElement, $allElements) {

        $stringCalc = new ChrisKonnertz\StringCalc\StringCalc();
        $calculateVariables = $calculateElement->payload->calculateVariables;

        $calculateVariables = preg_replace_callback('/\{([^{}]+)\}/', function ($match) {
            $variable = $match[1];
            if (strpos($variable, "|") !== false) {
                $parts = explode("|", $variable);
                $inputId = $parts[1];
                return "{" . $inputId . "}";
            }
        },$calculateVariables);

        foreach($formData as $field_id => $field_value)
        {
            $value = 0;
            if(strpos($field_id, "selectBox") !==false){
                foreach($allElements[$field_id]->payload->options as $option){
                    if($option->value == $field_value){
                        $value = floatval($option->calcValues);
                    }
                }
            }
            else if(strpos($field_id, "checkBox") !==false || strpos($field_id, "radioButton") !==false  || strpos($field_id, "multiSelect") !==false || strpos($field_id, "thumbnailSelector") !==false){
                foreach($allElements[$field_id]->payload->options as $option){
                    if(gettype( $field_value['value']) == "array"){
                        if(in_array($option->value, $field_value['value']))
                            $value += floatval($option->calcValues);
                    }else if(gettype($field_value) == "array"){
                        if(in_array($option->value, $field_value))
                            $value += floatval($option->calcValues);
                    }
                    else {
                        if($option->value == $field_value){
                            $value += floatval($option->calcValues);
                        }
                    }
                }
            }else if(strpos($field_id, "productList") !==false ){
                $value += floatval($field_value['total']);
            }else{
                $value = floatval($field_value);
            }
           
            $calculateVariables = preg_replace("/\{$field_id\}/", $value, $calculateVariables);
        }

        // Calculated field içerisinde hesaplanması istenen fakat submit edilirken gelmeyen 
        // fieldların yerine 0 konuluyor.
        foreach($allElements as $element){
            if(!isset($formData[$element->id])){
                $calculateVariables = preg_replace("/$element->id/",0, $calculateVariables);
            }
        }
        if(empty($calculateVariables))
            return 0;
        
        return $stringCalc->calculate($calculateVariables);
    }
}

if (!function_exists("magicform_product_list_total")){
    function magicform_product_list_total($values, $productList) {
        $totalAmount = 0;
        
        $options = $values[count($values)-2];
        $options = json_decode(htmlspecialchars_decode($options));
        $products = $productList->payload->products;

        $totalProduct = magicform_total_product($options, $products);
        
        foreach($totalProduct as $product){
            $totalAmount += $product['price'];
        }
       
       return floatval($totalAmount);
    }
} 

if(!function_exists("magicform_product_list_options") ){
    function magicform_product_list_options($values, $productList){
        $options = $values[count($values)-2];
        $options = json_decode(htmlspecialchars_decode($options));
        $products = $productList->payload->products;
		$totalAmount = 0;
        $detail = array();
        $totalProduct = magicform_total_product($options, $products);
        
        $name = "";
		if (is_array($options) || is_object($options))
		{
			foreach($options as $option){
				preg_match('/(?:\d).+(?!\d)/', $option->id, $output_array);
				$arr = explode("_",$output_array[0]);

				if($name != $products[$arr[2]]->name){
					$detail[] = $products[$arr[2]]->name .": ";
					$detail[] = "Price: " .$totalProduct[$arr[2]]["price"];
					$name = $products[$arr[2]]->name;
				}

				if($option->type === "select"){
					$optionDetail = $products[$arr[2]]->options[$arr[3]]->label .": Value: ";
					$optionDetail .= $products[$arr[2]]->options[$arr[3]]->items[$arr[4]]->value." Calc Value: ";
					$optionDetail .= $products[$arr[2]]->options[$arr[3]]->items[$arr[4]]->calcValues;
				}else if($option->type === "quantity"){
					$optionDetail = "Quantity: ". $option->val;
				}

				$detail[] = $optionDetail;
				$optionDetail = "";
			}
		}
		
		if (is_array($totalProduct) || is_object($totalProduct))
		{
        	foreach($totalProduct as $product){
            	$totalAmount += $product['price'];
        	}
		}

        $detail[] = "Total:" . $totalAmount;
        return $detail;
    }
}

if(!function_exists("magicform_product_list_detail")){
    function magicform_product_list_detail($values, $productList) {
        if(count((array)$productList)==0)
            return array();
        $options = $values[count($values)-2];
        $options = json_decode(htmlspecialchars_decode($options));
        $products = $productList->payload->products;
        $currencySymbol = $productList->payload->currencySymbol;
        $detail = array();
        $name = "";
        $totalProduct = magicform_total_product($options, $products);
		  if (is_array($options) || is_object($options))
		{
			foreach($options as $option){
				preg_match('/(?:\d).+(?!\d)/', $option->id, $output_array);
				$arr = explode("_",$output_array[0]);

				$optionDetail = array();
				if($name != $products[$arr[2]]->name){
					$optionDetail["Name"] = $products[$arr[2]]->name;

					$optionDetail["Image" ] = $products[$arr[2]]->views[0];

					$optionDetail["Price"] = $currencySymbol . number_format($products[$arr[2]]->price,2);

					$name = $products[$arr[2]]->name;
					$detail[$arr[2]]["Props"] = $optionDetail;
				}
		}
            
            if($option->type === "select"){
                $detail[$arr[2]]["Select"][] = array(
                    $products[$arr[2]]->options[$arr[3]]->label => $products[$arr[2]]->options[$arr[3]]->items[$arr[4]]->value,
                    "Calc. Value" => $products[$arr[2]]->options[$arr[3]]->calcValues == true?  ($currencySymbol . $products[$arr[2]]->options[$arr[3]]->items[$arr[4]]->calcValues):  $currencySymbol . "0"
                );
            }else if($option->type === "quantity"){
                $detail[$arr[2]]["Quantity"] =  $option->val;
            }
        }
        return $detail;
    }
}

if(!function_exists("magicform_total_product")){
    function magicform_total_product ($options, $products) {
        $totalProduct = array();
         if (is_array($options) || is_object($options)){
        foreach($options as $option){
            preg_match('/(?:\d).+(?!\d)/', $option->id, $output_array);
            $arr = explode("_",$output_array[0]);

            if(magicform_product_option_search($products[$arr[2]],$totalProduct)){
                
                array_push(
                    $totalProduct,
                    array(
                            "name" => $products[$arr[2]]->name,
                            "price" => $products[$arr[2]]->price
                        )
                );
            }
        }
	
        foreach($options as $option){

            preg_match('/(?:\d).+(?!\d)/', $option->id, $output_array);
            $arr = explode("_",$output_array[0]);

            if($option->type === "select" && count($totalProduct) >= $arr[2]+1){
               $totalProduct[$arr[2]]["price"] += $products[$arr[2]]->options[$arr[3]]->calcValues == true?
               $products[$arr[2]]->options[$arr[3]]->items[$arr[4]]->calcValues:0;
            }else if($option->type === "quantity" && count($totalProduct) >= $arr[2]+1){
               $totalProduct[$arr[2]]["price"] *= $option->val;
            }
        }
	}
        return $totalProduct;
    }
}
if(!function_exists("magicform_product_option_search")){
    function magicform_product_option_search($needle , $haystack){
        foreach($haystack as $item){
            if ($item["name"] == $needle->name && $item["price"] == $needle->price)
                return false;
        }
        return true;
    }   
}

if(!function_exists("magicform_view_inputs")){
    function magicform_view_inputs($field_id, $field_value) {
        
        if(gettype($field_value) != "array" && gettype($field_value)!= "object" )
            return $field_value;
        
        $field_value = (array)$field_value;
        $type = substr($field_id, 0, strpos($field_id, "_"));
                switch ($type) {
                    case "multiSelect":
                        $field_value = implode(", ", $field_value);
                    break;
                    case "checkBox":
                    case "thumbnailSelector":
                        if (!is_array($field_value["value"])) {
                            $field_value["value"] = array($field_value["value"]);
                        }
                        if (($key = array_search('other', $field_value["value"])) !== false) {
                            unset($field_value["value"][$key]);
                            $field_value["value"][] = "(Other) " . $field_value["other"];
                            $field_value = implode(", ", $field_value["value"]);
                        } else {
                            $field_value = implode(", ", $field_value["value"]);
                        }
                        break;
                    case "radioButton":
                        // If not selected other, delete!
                        if ($field_value["value"] != "other") {
                            unset($field_value["other"]);
                            $field_value = $field_value["value"];
                        } else {
                            $field_value = "(Other) " . $field_value["other"];
                        }
                        break;
                    case "email":
                        $field_value = $field_value['email'];
                        break;
                    case "password":
                        $field_value = $field_value["password"];
                        break;
                    default:
                        $field_value = implode(" ", $field_value);
                }
                return $field_value;
    }
}

if(!function_exists("magicform_xss_protection")){
    function magicform_xss_protection($field_value) {
        if(gettype($field_value) != "array" && gettype($field_value)!= "object" )
         return htmlspecialchars($field_value, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        if(is_array($field_value["value"])) {
            foreach ($field_value["value"] as &$item){
                $item = htmlspecialchars($item, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            }
        }else{
            foreach ($field_value as &$value){
                $value = htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            }
        }

        return $field_value;
    }
}

if(!function_exists("magicform_variable_parser")) {
    
    function magicform_variable_parser($value, $formData, $formSettings, $sub_id, $pageTitle ="", $pageUrl ="") {

        $result = preg_replace_callback('/\{([^{}]+)\}/', function ($match) use ($formData, $formSettings,$sub_id, $pageTitle, $pageUrl) {
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
                return  $sub_id;
            }
            
            if (strpos($variable, "|") !== false) {
                $parts = explode("|", $variable);
                $inputId = $parts[1];
                return isset($formData[$inputId]) ? implode(" ",(array)$formData[$inputId]) : "";
            }

        }, $value);
        return $result;
    }

}

if(!function_exists("magicform_date_format")) {

    function magicform_date_format($settings, $date = "") {
        if(!isset($settings->dateFormatType) && !isset($settings->dateFormat)){
            if($date == "")
                return date("r");
            return $date;
        }   
           
        $dateFormat = $settings->dateFormat;
        $formatType = $settings->dateFormatType;

       // echo strtotime($date) . "ppppppppp";
        if($date != "")
            $newDate = date($dateFormat, strtotime($date)); 
        else 
            $newDate = date(strtolower($dateFormat)); 

           
        if($formatType == "dash")
            $newDate =  str_replace('/', '-', $newDate);  
        else if($formatType == "dot")
            $newDate =  str_replace('/', '.', $newDate);  
        
           
        return $newDate;
    }

}

if (!function_exists("magicform_get_field_value")) {
    function magicform_get_field_value($fieldId, $formData)
    {
        $fieldValue = "";
        foreach ($formData as $field_id => $field_value) {
            if ($field_id == $fieldId) {
                $fieldValue = $field_value;
            }
        }
        return $fieldValue;
    }
}

if (!function_exists("magicform_pdf_generator")) {

    include MAGICFORM_PATH . "/includes/class-magicform-pdf.php";

    function magicform_pdf_generator($formData, $sub_id, $formSettings, $allElements, $action, $pageTitle, $pageUrl, $submissions_tablename) {
        $id = intval($sub_id);
        global $wpdb;
        $result = $wpdb->get_row($wpdb->prepare("SELECT payment_status FROM  $submissions_tablename WHERE id =%d", $id));

        $normalizedFormData = array();
        
        $emptyField = false;

        if(isset($action->payload)){
           $emptyField = $action->payload->emptyField;
        }

        foreach ($formData as $field_id => $field_value) {
            $field_name = isset($allElements[$field_id]) ? $allElements[$field_id]->payload->labelText : $field_id;
            if ($field_name != "magicform_token" && $field_name != "mf-gdpr" && $field_name != "_wp_http_referer" && strpos($field_id, "g-recaptcha-response") === false) {
                if(strpos($field_id,"productList") !== false){
                    $values = explode(" ", implode(" ",(array)$formData[$field_id]));
                    $normalizedFormData[$field_id] = (implode(" ", magicform_product_list_options($values, $allElements[$field_id])) . ($result->payment_status?"\nPayment Status :Paid":"\nPayment Status : Not Paid"));
                }else{
                    if(gettype($field_value) == "array" || gettype($field_value) == "object"){
                        if(gettype($field_value) == "object" && property_exists($field_value, "value")){
                            if($emptyField && count((array) $field_value->value)>0)
                                $normalizedFormData[$field_id] = implode(", ",(array) magicform_view_inputs($field_id, $field_value));
                            else if(!$emptyField)
                                $normalizedFormData[$field_id] = implode(", ",(array) magicform_view_inputs($field_id, $field_value));
                        }
                        else if(gettype($field_value) == "array" && array_key_exists("value",$field_value)){
                            if($emptyField && count((array) $field_value['value']) > 0)
                                $normalizedFormData[$field_id] = implode(", ",(array) magicform_view_inputs($field_id, $field_value));
                            else if(!$emptyField)
                                $normalizedFormData[$field_id] = implode(", ",(array) magicform_view_inputs($field_id, $field_value));
                        }                        
                        else{
                            if($emptyField && count((array) $field_value) > 0)
                                $normalizedFormData[$field_id] = implode(", ",(array) $field_value);
                            else if(!$emptyField)
                                $normalizedFormData[$field_id] = implode(", ",(array) $field_value);
                        }
                      }
                      else {
                          if($emptyField && !empty( $field_value))
                             $normalizedFormData[$field_id] = $field_value;
                         else if(!$emptyField)
                            $normalizedFormData[$field_id] = $field_value;
                      }
                }
            }
        }
       
        $pdf = new MagicForm_PDF();
        $pdf->AddFont('DejaVu', '', 'DejaVuSansCondensed.ttf', true);
        $pdf->AddFont('DejaVu', 'B', 'DejaVuSansCondensed-Bold.ttf', true);
        
       if(isset($action->pyload)){
            $pdf->title = magicform_variable_parser($action->payload->title,(array)$formData, $formSettings, $sub_id, $pageTitle, $pageUrl, $allElements);
            $action->payload->header = magicform_variable_parser($action->payload->header, (array)$formData, $formSettings, $sub_id, $pageTitle, $pageUrl, $allElements);
            $action->payload->footer = magicform_variable_parser($action->payload->footer,(array) $formData, $formSettings, $sub_id, $pageTitle, $pageUrl, $allElements);
        }

        $pdf->action = $action;
        $pdf->submission_id = $sub_id;
        $pdf->form_name = $formSettings->settings->name;
        $pdf->date = magicform_date_format($formSettings->settings);
        $pdf->AddPage();
       // $pdf->SetTitle($title);
        
        $pdf->FormDataPrint($normalizedFormData, $allElements);
        $uploads = wp_upload_dir();

        $generatedPdfFile = $uploads["path"] . "/" . $formSettings->settings->name . "-Submission-" . $sub_id . ".pdf";
        $pdf->Output("F", $generatedPdfFile);
        
        return $generatedPdfFile;
    }
}

if (!function_exists("magicform_email_validation")) {
    function magicform_email_validation($email)
    {
        if (!preg_match('/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/i', $email)) {
            return false;
        }
        return true;
    }
}

if (!function_exists("magicform_time_elapsed_string")) {
    function magicform_time_elapsed_string($ptime)
    {
        $etime = time() - strtotime($ptime);
        if ($etime < 1) {
            return '0 seconds';
        }
        $a = array(
            365 * 24 * 60 * 60  =>  'year',
            30 * 24 * 60 * 60  =>  'month',
            24 * 60 * 60  =>  'day',
            60 * 60  =>  'hour',
            60  =>  'minute',
            1  =>  'second'
        );
        $a_plural = array(
            'year'   => 'years',
            'month'  => 'months',
            'day'    => 'days',
            'hour'   => 'hours',
            'minute' => 'minutes',
            'second' => 'seconds'
        );
        foreach ($a as $secs => $str) {
            $d = $etime / $secs;
            if ($d >= 1) {
                $r = round($d);
                return "<span title='" . $ptime . "'>" . $r . ' ' . ($r > 1 ? $a_plural[$str] : $str) . ' ago' . "</span>";
            }
        }
    }
}

/**
 * Get Url Params
 */
if (!function_exists("magicform_getUrlParams")) {
    function magicform_getUrlParams($exceptions = array())
    {
        $getData = array();
        foreach ($_GET as $key => $item) {
            if (!in_array($key, $exceptions)) {
                $key = sanitize_text_field($key);
                $getData[$key] = sanitize_text_field($item);
            }
        }
        return http_build_query($getData);
    }
}

/**
 * Get User IP Address
 */
if (!function_exists("magicform_getUserIpAddr")) {
    function magicform_getUserIpAddr()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            //ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            //ip pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
}

/**
 * Converts extensions to mime types
 */
if (!function_exists("magicform_convertExtensionsToMimeTypes")) {
    function magicform_convertExtensionsToMimeTypes($exts)
    {
        $allowedMimeTypes = array();
        foreach ($exts as $ext) {
            foreach(magicform_getMimeType("." . $ext) as $type)
            $allowedMimeTypes[] = $type;
        }
        
        return $allowedMimeTypes;
    }
}

/**
 * Get mime type according to extension
 */
if (!function_exists("magicform_getMimeType")) {
    function magicform_getMimeType($ext)
    {
        $ext = strtolower($ext);
        if (!(strpos($ext, '.') !== false)) {
            $ext = '.' . $ext;
        }
    
        $fileTypes = [
            [
                'type'=>'.aac',
                'mime' => 'audio/aac'
            ],
            [
                'type'=>'.abw',
                'mime' => 'application/x-abiword'
            ],
            [
                'type'=>'.arc',
                'mime' => 'application/octet-stream'
            ],
            [
                'type'=>'.avi',
                'mime' => 'video/x-msvideo'
            ],
            [
                'type'=>'.azw',
                'mime' => 'application/vnd.amazon.ebook'
            ],
            [
                'type'=>'.bin',
                'mime' => 'application/octet-stream'
            ],
            [
                'type'=>'.bmp',
                'mime' => 'image/bmp'
            ] ,
            [
                'type'=>'.bz',
                'mime' => 'application/x-bzip'
            ] ,
            [
                'type'=>'.csh',
                'mime' => 'application/x-csh'
            ],
            [
                'type'=>'.css',
                'mime' => 'text/css'
            ],
            [
                'type'=>'.csv',
                'mime' => 'text/csv'
            ],
            [
                'type'=>'.doc',
                'mime' => 'application/msword'
            ],
            [
                'type'=>'.docx',
                'mime' => 'application/vnd.ms-fontobject'
            ],
            [
                'type'=>'.epub',
                'mime' => 'application/epub+zip'
            ],
            [
                'type'=>'.gif',
                'mime' => 'image/gif'
            ],
            [
                'type'=>'.htm',
                'mime' => 'text/html'
            ],
            [
                'type'=>'.ico',
                'mime' => 'image/x-icon'
            ],
            [
                'type'=>'.ics',
                'mime' => 'text/calendar'
            ],
            [
                'type'=>'.jar',
                'mime' => 'application/java-archive'
            ],
            [
                'type'=>'.jpeg',
                'mime' => 'image/jpeg'
            ],
            [
                'type'=>'.jpg',
                'mime' => 'image/jpeg'
            ],
            [
                'type'=>'.js',
                'mime' => 'application/javascript'
            ],
            [
                'type'=>'.json',
                'mime' => 'application/json'
            ],
            [
                'type'=>'.',
                'mime' => ''
            ],
            [
                'type'=>'.mid',
                'mime' => 'audio/midi audio/x-midi'
            ],
            [
                'type'=>'.midi',
                'mime' => 'audio/midi audio/x-midi'
            ],
            [
                'type'=>'.mpeg',
                'mime' => 'video/mpeg'
            ],
            [
                'type'=>'.mp4',
                'mime' => 'video/mp4'
            ],
            [
                'type'=>'.mp3',
                'mime' => 'audio/mp3'
            ],
            [
                'type'=>'.mov',
                'mime' => 'video/quicktime'
            ],
            [
                'type'=>'.mpkg',
                'mime' =>  'application/vnd.apple.installer+xml'
            ],
            [
                'type'=>'.odp',
                'mime' => 'application/vnd.oasis.opendocument.presentation'
            ],
            [
                'type'=>'.ods',
                'mime' => 'application/vnd.oasis.opendocument.spreadsheet'
            ],
            [
                'type'=>'.odt',
                'mime' => 'application/vnd.oasis.opendocument.text'
            ],
            [
                'type'=>'.oga',
                'mime' => 'audio/ogg'
            ],
            [
                'type'=>'.ogv',
                'mime' => 'video/ogg'
            ],
            [
                'type'=>'.ogx',
                'mime' => 'application/ogg'
            ],
            [
                'type'=>'.otf',
                'mime' => 'font/otf'
            ],
            [
                'type'=>'.png',
                'mime' => 'image/png'
            ],
            [
                'type'=>'.pdf',
                'mime' => 'application/pdf'
            ],
            [
                'type'=>'.ppt',
                'mime' => 'application/vnd.ms-powerpoint'
            ],
            [
                'type'=>'.pptx',
                'mime' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation'
            ],
            [
                'type'=>'.rar',
                'mime' => 'application/vnd.rar'
            ],
            [
                'type'=>'.rar',
                'mime' => 'application/x-rar-compressed'
            ],
            [
                'type'=>'.rar',
                'mime' => 'application/rar'
            ],
            [
                'type'=>'.rtf',
                'mime' => 'application/rtf'
            ],
            [
                'type'=>'.sh',
                'mime' => 'application/x-sh'
            ],
            [
                'type'=>'.svg',
                'mime' => 'image/svg+xml'
            ],
            [
                'type'=>'.swf',
                'mime' => 'application/x-shockwave-flash'
            ],
            [
                'type'=>'.tar',
                'mime' => 'application/x-tar'
            ],
            [
                'type'=>'.tif',
                'mime' => 'image/tiff'
            ],
            [
                'type'=>'.tiff',
                'mime' => 'image/tiff'
            ],
            [
                'type'=>'.ts',
                'mime' => 'application/typescript'
            ],
            [
                'type'=>'.ttf',
                'mime' => 'text/plain'
            ],
            [
                'type'=>'.vsd',
                'mime' => 'application/vnd.visio'
            ],
            [
                'type'=>'.wav',
                'mime' => 'audio/wav'
            ],
            [
                'type'=>'.weba',
                'mime' => 'audio/webm'
            ],
            [
                'type'=>'.webm',
                'mime' => 'video/webm'
            ],
            [
                'type'=>'.webp',
                'mime' => 'image/webp'
            ],
            [
                'type'=>'.woff',
                'mime' => 'font/woff'
            ],
            [
                'type'=>'.xhtml',
                'mime' => 'application/xhtml+xml'
            ],
            [
                'type'=>'.xls',
                'mime' => 'application/vnd.ms-excel'
            ],
            [
                'type'=>'.xlsx',
                'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            ],
            [
                'type'=>'.xml',
                'mime' => 'application/xml'
            ],
            [
                'type'=>'.xul',
                'mime' => 'application/vnd.mozilla.xul+xml'
            ],
            [
                'type'=>'.zip',
                'mime' => 'application/zip'
            ] ,
            [
                'type'=>'.3gp',
                'mime' => 'video/3gpp'
            ]  ,
            [
                'type'=>'.3g2',
                'mime' => 'video/3gpp2'
            ],
            [
                'type'=>'.7z',
                'mime' => 'application/x-7z-compressed'
            ]  
        ];
        
        $mimes = array();

        foreach ($fileTypes as $file) 
        {
            if($file['type'] == $ext)
                $mimes[] = $file['mime'];
        }
        
        return $mimes;
    }
}

/**
 * Get FileSize in KB
 */
if (!function_exists("magicform_getFileSizeKB")) {
    function magicform_getFileSizeKB($fileSize, $fileSizeType)
    {
        switch ($fileSizeType) {
            case "MB":
                return $fileSize * 1024 * 1024;
            case "GB":
                return $fileSize * 1024 * 1024 * 1024;
            default:
                return $fileSize * 1024;
        }
    }
}

/**
 * Convert Color Object to String Rgba
 */
if (!function_exists("magicform_convertRgba")) {
    function magicform_convertRgba($color, $opacity = 1, $returnType = "text")
    {
        if ($color == null) {
            return null;
        }
        if ($color == "transparent")
            return $color;
        if ($returnType == "text") {
            return "rgba(" . intval($color->r) . "," . intval($color->g) . "," . intval($color->b) . "," . ($opacity == 1 ? $color->a : $opacity) . ")";
        }
        return null;
    }
}

/**
 * Detect Device Type
 */
if (!function_exists("magicform_checkDevice")) {
    function magicform_checkDevice()
    {
        if (is_numeric(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), "mobile"))) {
            return is_numeric(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), "tablet")) ? "Tablet" : "Mobile";
        } else {
            return "Desktop";
        }
    }
}

/**
 * Set Font Family
 */

if (!function_exists("magicform_setFontFamily")) {

    function magicform_setFontFamily($fontFamily = "")
    {
        switch ($fontFamily) {
            case "Arial":
                return MAGICFORM_URL . "assets/css/fonts/arial.ttf";
                break;
            case "Century Gothic":
                return "//fonts.googleapis.com/css?family=Century+Gothic";
                break;
            case "Times New Roman":
                return "//fonts.googleapis.com/css?family=Times+New+Roman";
                break;
            case "Patrick Hand":
                return "//fonts.googleapis.com/css2?family=Patrick+Hand&display=swap";
                break;
            case "Roboto":
                return "//fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap";
                break;
            case "Roboto Condensed":
                return "//fonts.googleapis.com/css2?family=Roboto+Condensed:wght@400;700&display=swap";
                break;
            case "Open Sans":
                return "//fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;700&display=swap";
                break;
            case "Raleway":
                return  "//fonts.googleapis.com/css2?family=Raleway:wght@400;500;700&display=swap";
                break;
            case "Nunito":
                return "//fonts.googleapis.com/css2?family=Nunito:wght@300;400;700&display=swap";
                break;
            default:
                return "";
        }
    }
}

/**
 * Detect User OS and Browser
 */
if (!function_exists("magicform_getBrowser")) {
    function magicform_getBrowser($u_agent)
    {
        if ($u_agent == null)
            return null;
        $bname = 'Unknown';
        $platform = 'Unknown';
        $version = "";

        //First get the platform?
        if (preg_match('/linux/i', $u_agent)) {
            $platform = 'Linux';
        } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = 'Mac';
        } elseif (preg_match('/windows|win32/i', $u_agent)) {
            $platform = 'Windows';
        }

        // Next get the name of the useragent yes seperately and for good reason
        if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        } elseif (preg_match('/Firefox/i', $u_agent)) {
            $bname = 'Mozilla Firefox';
            $ub = "Firefox";
        } elseif (preg_match('/OPR/i', $u_agent)) {
            $bname = 'Opera';
            $ub = "Opera";
        } elseif (preg_match('/Chrome/i', $u_agent) && !preg_match('/Edge/i', $u_agent)) {
            $bname = 'Google Chrome';
            $ub = "Chrome";
        } elseif (preg_match('/Safari/i', $u_agent) && !preg_match('/Edge/i', $u_agent)) {
            $bname = 'Apple Safari';
            $ub = "Safari";
        } elseif (preg_match('/Netscape/i', $u_agent)) {
            $bname = 'Netscape';
            $ub = "Netscape";
        } elseif (preg_match('/Edge/i', $u_agent)) {
            $bname = 'Edge';
            $ub = "Edge";
        } elseif (preg_match('/Trident/i', $u_agent)) {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        }

        // finally get the correct version number
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) .
            ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
        }
        // see how many we have
        $i = count($matches['browser']);
        if ($i != 1) {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
                $version = $matches['version'][0];
            } else {
                $version = $matches['version'][1];
            }
        } else {
            $version = $matches['version'][0];
        }

        // check if we have a number
        if ($version == null || $version == "") {
            $version = "?";
        }

        return array(
            'userAgent' => $u_agent,
            'name'      => $bname,
            'version'   => $version,
            'platform'  => $platform,
            'pattern'    => $pattern
        );
    }
}
/**
 * 
 */
if (!function_exists("magicform_add_notification")) {
    function magicform_add_notification($formId, $title, $description)
    {
        global $wpdb;
        $wpdb->insert($wpdb->prefix . "magicform_notifications", array(
            "form_id" => $formId,
            "title" => $title,
            "data" => $description,
            "create_date" => date("Y-m-d H:i:s"),
            "read_status" => 0
        ));
    }
}
