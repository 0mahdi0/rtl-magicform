<?php

/**
 * @package MagicForm
 * Validation Class
 */

class MagicForm_Validation
{

    public $validations = array();
    public $validationErrors = array();
    public $allElements = array();

    function getValidation($type, $data, $translate)
    {
        if ($type == "page") {
            foreach ($data as $page) {
                foreach ($page->elements as $item) {
                    $this->allElements[$item->id] = $item;
                    // Add to validation list if  has a required field
                    if (isset($item->payload->required) && $item->payload->required) {
                        $requiredObject = array("type" => "required", "payload" => (object) array("errorMessage" => $translate->requiredErr));
                        $this->validations[$item->id] = [(object) $requiredObject];
                    }
                    
                    // Add to validation list if has confirm field
                    if (isset($item->payload->confirmInput) && $item->payload->confirmInput) {
                        $confirmError = array("type" => "match", "payload" => (object) array("errorMessage" => $translate->confirmErr, "id" => $item->id . "_" . $item->type));
                        $this->validations[$item->id] = [(object) $confirmError];
                    }

                    //  Add to validation list if validation rules
                    if ( isset($item->payload->validations) && is_array($item->payload->validations) && count($item->payload->validations) > 0)
                        foreach ($item->payload->validations as $v) {
                            $this->validations[$item->id][] = $v;
                        }
                    if ($item->type == "group" || $item->type == "grid") {
                        $this->getValidation($item->type, $item, $translate);
                    }
                }
            }
        }
        if ($type == "group") {
            foreach ($data->payload->elements as $item) {
                $this->allElements[$item->id] = $item;

                if (isset($item->payload->required) && $item->payload->required) {
                    $requiredObject = array("type" => "required", "payload" => (object) array("errorMessage" => $translate->requiredErr));
                    $this->validations[$item->id] = [(object) $requiredObject];
                }

                if (isset($item->payload->confirmInput) && $item->payload->confirmInput) {
                    $confirmError = array("type" => "match", "payload" => (object) array("errorMessage" => $translate->confirmErr, "id" => $item->id . "_" . $item->type));
                    $this->validations[$item->id] = [(object) $confirmError];
                }

                if (isset($item->payload->validations) && is_array($item->payload->validations) && count($item->payload->validations) > 0)
                    foreach ($item->payload->validations as $v) {
                        $this->validations[$item->id][] = $v;
                    }

                if ($item->type == "group" || $item->type == "grid") {
                    $this->getValidation($item->type, $item,  $translate);
                }
            }
        }
        if ($type == "grid") {
            foreach ($data->payload->columns as $column) {
                foreach ($column->elements as $item) {
                    $this->allElements[$item->id] = $item;

                    if (isset($item->payload->required) && $item->payload->required) {
                        $requiredObject = array("type" => "required", "payload" => (object) array("errorMessage" => $translate->requiredErr));
                        $this->validations[$item->id] = [(object) $requiredObject];
                    }

                    if (isset($item->payload->confirmInput) && $item->payload->confirmInput) {
                        $confirmError = array("type" => "match", "payload" => (object) array("errorMessage" => $translate->confirmErr, "id" => $item->id . "_" . $item->type));
                        $this->validations[$item->id] = [(object) $confirmError];
                    }

                    if ( isset($item->payload->validations) && is_array($item->payload->validations) && count($item->payload->validations) > 0)
                        foreach ($item->payload->validations as $v) {
                            $this->validations[$item->id][] = $v;
                        }

                    if ($item->type == "group" || $item->type == "grid") {
                        $this->getValidation($item->type, $item, $translate);
                    }
                }
            }
        }
    }

    function validationList($submitData, $hiddenIputs)
    {
        $result = array();
        foreach ($this->validations as $inputId => $validation) {
            $result = array_merge($result, $this->validationControl($inputId, $validation, $this->allElements[$inputId], $submitData,  $hiddenIputs));
        }
        return $result;
    }

    function validationControl($inputId, $validations, $elementSettings, $submitData,  $hiddenIputs)
    {
        $result = array();
        $type = $elementSettings->type;

        foreach ($validations as $validation) {
            $id = $inputId;
            if ($type == "email" || $type == "password" || $type == 'name' || $type == "phone")
                $id = $inputId . "_" . $type;

            if ($type == 'radioButton' || $type == 'checkBox' || $type == 'thumbnailSelector' )
                $id = $inputId . "_value";
            
            $validationControlType = $this->validationControlType($id, $validation, $elementSettings, $submitData[$id],  $hiddenIputs);
            $result = array_merge($result, is_array($validationControlType)?$validationControlType:[]);

            if ($validation->type == "match") {
                switch ($type) {
                    case "password":
                    case "email":
                        $validationControlType = is_array($this->validationControlType($inputId . "_confirm", $validation, $elementSettings, $submitData,  $hiddenIputs));
                        $result = array_merge($result, is_array($validationControlType)?$validationControlType:[]);
                        break;
                }
            }

            // More than one include element

            if ($validation->type == "required") {
                switch ($elementSettings->type) {
                    case "password":
                    case "email":
                        $validationControlType = $this->validationControlType($inputId . "_confirm", $validation, $elementSettings, isset($submitData[$inputId . "_confirm"])?$submitData[$inputId . "_confirm"]:"",  $hiddenIputs);
                        if (isset($elementSettings->payload->confirmInput) && $elementSettings->payload->confirmInput) {
                            $result = array_merge($result,is_array($validationControlType)?$validationControlType:[]);
                        }
                        break;
                    case "name":
                        $validationControlType = $this->validationControlType($inputId . "_surname", $validation, $elementSettings, isset($submitData[$inputId . "_surname"])?$submitData[$inputId . "_surname"]:"",  $hiddenIputs);
                        if (isset($elementSettings->payload->surname) && $elementSettings->payload->surname) {
                            $result = array_merge($result, is_array($validationControlType)?$validationControlType:[]);
                        }
                        break;
                    case "address":
                        
                        if (isset($elementSettings->payload->address1Check) && $elementSettings->payload->address1Check) {
                            $validationControlType = $this->validationControlType($inputId . "_address1", $validation, $elementSettings, isset($submitData[$inputId . "_address1"])?$submitData[$inputId . "_address1"]:"",  $hiddenIputs);
                            $result = array_merge($result, is_array($validationControlType)?$validationControlType:[]);
                        }
                        if (isset($elementSettings->payload->cityCheck) && $elementSettings->payload->cityCheck) {
                            $validationControlType = $this->validationControlType($inputId . "_city", $validation, $elementSettings, isset($submitData[$inputId . "_city"])?$submitData[$inputId . "_city"]:"",  $hiddenIputs);
                            $result = array_merge($result, is_array($validationControlType)?$validationControlType:[]);
                        }
                        if (isset($elementSettings->payload->countryCheck) && $elementSettings->payload->countryCheck) {
                            $validationControlType = $this->validationControlType($inputId . "_country", $validation, $elementSettings, isset($submitData[$inputId . "_country"])?$submitData[$inputId . "_country"]:"",  $hiddenIputs);
                            $result = array_merge($result, is_array($validationControlType)?$validationControlType:[]);
                        }
                        if (isset($elementSettings->payload->stateCheck) && $elementSettings->payload->stateCheck) {
                            $validationControlType = $this->validationControlType($inputId . "_state", $validation, $elementSettings, isset($submitData[$inputId . "_state"])?$submitData[$inputId . "_state"]:"",  $hiddenIputs);
                            $result = array_merge($result, $this->validationControlType($inputId . "_state", $validation, $elementSettings,  isset($submitData[$inputId . "_state"])?$submitData[$inputId . "_state"]:"",  $hiddenIputs));
                        }
                        break;
                }
            }
        }
        return $result;
    }

    function validationControlType($inputId, $validation, $elementSettings, $val,  $hiddenIputs)
    {
        if(in_array($inputId,$hiddenIputs)){
           return;
        }
            
        $errorMessages = array();
        switch ($validation->type) {
            case "required":
                if ($val === "") {
                    $err = array(
                        $inputId => $validation->payload->errorMessage
                    );
                    $errorMessages = array_merge($errorMessages, $err);
                }
                break;
            case "match":
                if (gettype($val) == "array") {
                    if ($val[$inputId] !== $val[$validation->payload->id]) {
                        $err = array(
                            $inputId => $validation->payload->errorMessage
                        );
                        $errorMessages = arrayerrorMessages_merge($errorMessages, $err);
                    }
                }
                break;
            case "alpha":
                $regex = '/^([A-Za-zx{00C0}-\x{00ff}x{0100}-\x{017f}-\x{0180}-\x{024f}\x{1e00}-\x{1eff}' . ($validation->payload->whitespace ? " " : "") . ']*)$/u';
                if ($val != "" && !preg_match($regex, $val)) {
                    $err = array(
                        $inputId => $validation->payload->errorMessage
                    );
                    $errorMessages = array_merge($errorMessages, $err);
                }
                break;
            case "alphanumeric":
                if ($val != "" && !preg_match('/^[a-z0-9' . (isset($validation->payload->whitespace) ? " " : "") . ']+$/i', $val[0])) {
                    $err = array(
                        $inputId => $validation->payload->errorMessage
                    );
                    $errorMessages = array_merge($errorMessages, $err);
                }
                break;
            case "numeric":
                if ($val != "" && !preg_match('/^[0-9' . ($validation->payload->whitespace ? " " : "") . ']+$/i', $val)) {
                    $err = array(
                        $inputId => $validation->payload->errorMessage
                    );
                    $errorMessages = array_merge($errorMessages, $err);
                }
                break;
            case "email":
                if ($val != "" && !preg_match('/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/i', $val)) {
                    $err = array(
                        $inputId => $validation->payload->errorMessage
                    );
                    $errorMessages = array_merge($errorMessages, $err);
                }
                break;
            case "regex":
                $regex = "/^(" . $validation->payload->regex . ")+$/i";
                if ($val != "" && !preg_match($regex, $val)) {
                    $err = array(
                        $inputId => $validation->payload->errorMessage
                    );
                    $errorMessages = array_merge($errorMessages, $err);
                }
                break;
            case "greaterthan":
                $control = $validation->payload->include ? $validation->payload->value : $validation->payload->value + 1;
                if ($val != "" && (is_nan($val) || $val < $control)) {
                    $err = array(
                        $inputId => $validation->payload->errorMessage
                    );
                    $errorMessages = array_merge($errorMessages, $err);
                }
                break;
            case "lessthan":
                $control = $validation->payload->include ? $validation->payload->value : $validation->payload->value - 1;
                if ($val != "" && (is_nan($val) || $val > $control)) {
                    $err = array(
                        $inputId => $validation->payload->errorMessage
                    );
                    $errorMessages = array_merge($errorMessages, $err);
                }
                break;
            case "inlist":
                $list = $validation->payload->list;
                $control = false;
                if ($validation->payload->type == 'white' && !in_array($val, $list))
                    $control = true;
                else if ($validation->payload->type != 'white' && in_array($val, $list))
                    $control = true;
                if ($val != "" && $control) {
                    $err = array(
                        $inputId => $validation->payload->errorMessage
                    );
                    $errorMessages = array_merge($errorMessages, $err);
                }
                break;
            case "domain":
                if ($val != "" && !preg_match('/^(?!(https:\/\/|http:\/\/|www\.|mailto:|smtp:|ftp:\/\/|ftps:\/\/))(((([a-zA-Z0-9])|([a-zA-Z0-9][a-zA-Z0-9\-]{0,86}[a-zA-Z0-9]))\.(([a-zA-Z0-9])|([a-zA-Z0-9][a-zA-Z0-9\-]{0,73}[a-zA-Z0-9]))\.(([a-zA-Z0-9]{2,12}\.[a-zA-Z0-9]{2,12})|([a-zA-Z0-9]{2,25})))|((([a-zA-Z0-9])|([a-zA-Z0-9][a-zA-Z0-9\-]{0,162}[a-zA-Z0-9]))\.(([a-zA-Z0-9]{2,12}\.[a-zA-Z0-9]{2,12})|([a-zA-Z0-9]{2,25}))))/', strtolower($val))) {
                    $err = array(
                        $inputId => $validation->payload->errorMessage
                    );
                    $errorMessages = array_merge($errorMessages, $err);
                }
                break;
            case "ip":
                if ($val != "" && !preg_match('/^((25[0-5]|(2[0-4]|1[0-9]|[1-9]|)[0-9])(\.(?!$)|$)){4}$/i', $val)) {
                    $err = array(
                        $inputId => $validation->payload->errorMessage
                    );
                    $errorMessages = array_merge($errorMessages, $err);
                }
                break;
            case "creditCard":
                if ($val != "" && !preg_match('/^(?:4[0-9]{12}(?:[0-9]{3})?|[25][1-7][0-9]{14}|6(?:011|5[0-9][0-9])[0-9]{12}|3[47][0-9]{13}|3(?:0[0-5]|[68][0-9])[0-9]{11}|(?:2131|1800|35\d{3})\d{11})$/i', preg_replace('/\s/', "", $val))) {
                    $err = array(
                        $inputId => $validation->payload->errorMessage
                    );
                    $errorMessages = array_merge($errorMessages, $err);
                }
                break;
            case "contains":
                $containList = $validation->payload->containList;
                $containControl = 0;
                $check = 1;
                if ($validation->payload->type == "contains")
                    $check = 0;
                foreach ($containList as $item) {
                    if (strstr($val, $item)) $containControl = 1;
                }
                if ($val != "" && $containControl === $check) {
                    $err = array(
                        $inputId => $validation->payload->errorMessage
                    );
                    $errorMessages = array_merge($errorMessages, $err);
                }
                break;
            case "multiSelectCount":
                $type = $validation->payload->conditionType;
                $limit = $validation->payload->selectCount;
                $selected = count($val);
                $err = array(
                    $inputId => $validation->payload->errorMessage
                );
                if( $type == "equal" && $limit != $selected )
                    $errorMessages = array_merge($errorMessages, $err);
                else if( $type == "greaterThan" && $selected <= $limit)
                    $errorMessages = array_merge($errorMessages, $err);
                else if(  $type == "lessThan" && $selected >= $limit )
                    $errorMessages = array_merge($errorMessages, $err);

            break;
            case "date":
                $dataFormats = array(
                    array(
                        "format" => "dd.mm.yyyy",
                        "pattern" => '/\s*(3[01]|[12][0-9]|0?[1-9])\.(1[012]|0?[1-9])\.((?:19|20)\d{2})/'
                    ),
                    array(
                        "format" => "dd/mm/yyyy",
                        "pattern" => '/^([0-2][0-9]|(3)[0-1])(\/)(((0)[0-9])|((1)[0-2]))(\/)\d{4}/'
                    ),
                    array(
                        "format" => "mm/dd/yyyy",
                        "pattern" => '/(0[1-9]|1[012])[- \/.](0[1-9]|[12][0-9]|3[01])[- \/.](\d\d)\d\d/'
                    ),
                    array(
                        "format" => "yyyy-mm-dd",
                        "pattern" => '/([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))/'
                    )
                );
                $pattern = "";
                foreach ($dataFormats as $item) {
                    if ($item['format'] == $validation->payload->format) $pattern = $item['pattern'];
                }
                if ($val != "" && !preg_match($pattern, $val)) {
                    $err = array(
                        $inputId => $validation->payload->errorMessage
                    );
                    $errorMessages = array_merge($errorMessages, $err);
                }
                break;
        }
        return $errorMessages;
    }
}
