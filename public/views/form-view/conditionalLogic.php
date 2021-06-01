<?php 
if (!function_exists("magicform_prepareCondition")) {
    function magicform_prepareCondition($condition)
    {
        $type = substr($condition->field,0,strpos($condition->field,"_"));
        if ($type == "radioButton") {
            $field = "$(\"input[name='".$condition->field."_value']\").filter(':checked').val()";
        } 
        else if($type=="checkBox" || $type=="thumbnailSelector"){
            $field = "$(\"input[name='".$condition->field."_value'][value='".$condition->value."']:checked\").val()";
        }
        else if ($type == "starRating" || $type=="scaleRating") {
            $field = "$(\"input[name='".$condition->field."']\").filter(':checked').val()";
        }
        else if($type == "calculateField"){
            $field = '$("input:hidden[name=' . $condition->field . "_value]" . '").val()';
        }
        else {
            $field = '$("#' . $condition->field . '").val()';
        }

        if ($condition->operator != "in_list" && $condition->operator != "not_in_list") {
            $value = is_numeric($condition->value) ? $condition->value : '"' . $condition->value . '"';
        }
        switch ($condition->operator) {
            case "equals":
                return $field . '==' . $value;
                break;
            case "not_equals":
                return $field . '!=' . $value;
                break;
            case "greater_than":
                return $field . '>' . $value;
                break;
            case "less_than":
                return $field . '<' . $value;
                break;
            case "contains":
                return $field . '.indexOf(' . $value . ')!==-1';
                break;
            case "in_list":
                $values = (array) explode(",", $condition->value);
                $valuesText = json_encode($values);
                return $valuesText . '.indexOf(' . $field . ')!==-1';
                break;
            case "not_in_list":
                $values = (array) explode(",", $condition->value);
                $valuesText = json_encode($values);
                return $valuesText . '.indexOf(' . $field . ')===-1';
                break;
        }
    }
}

if (!function_exists("magicform_prepareAction")) {
    function magicform_prepareAction($action, $formId)
    {
        $result = "";

        // Page
        if (strpos($action->field, "page") === 0) {
            $pageIndex = substr(strrchr($action->field, "-"), 1);
            $page = '$(".mf-' . $action->field . '")';
            $stepItem = '$(".magicform-' . $formId . '").find(".mf-multistep-nav-item[data-index=\'' . $pageIndex . '\']")';

            switch ($action->type) {
                case "show":
                    $result .= $page . ".removeAttr('data-hidden');";
                    $result .= $stepItem . ".show();";
                break;
                case "hide":
                    $result .= $page . ".hide().attr('data-hidden',true);";
                    $result .= $stepItem . ".hide().attr('data-hidden',true);";
                    break;
                case "enable":
                    $result .= $page . ".find('input, textarea, button, select').attr('disabled',false);";
                    break;
                case "disable":
                    $result .= $page . ".find('input, textarea, select').attr('disabled',true);";
                    break;
                case "readonly":
                    $result .= $page . ".find('input, textarea').attr('readonly',true);";
                    break;
            }

            // Field
        } else {
            $field = '$(".mf-form-group-' . $action->field . '")';
            switch ($action->type) {
                case "show":
                    $result .= $field . ".show();";
                    break;
                case "hide":
                    $result .= $field . ".hide().attr('data-hidden',true);";
                    break;
                case "enable":
                    $result .= $field . ".find('input, textarea, button, select').attr('disabled',false);";
                    break;
                case "disable":
                    $result .= $field . ".find('input, textarea, button, select').attr('disabled',true);";
                    break;
                case "readonly":
                    $result .= $field . ".find('input, textarea, button').attr('readonly',true);";
                    break;
                case "setValueOf":
                    $result .= $field . ".find('input, textarea, select').val('".$action->setValue."');";
                    break;
            }
        }
        return $result;
    }
}

$preparedRules = "";

if (count($rules) > 0) {
    foreach ($rules as $rule) {
        if (count($rule->conditions) > 0) {

            $fields = array();
            $conditions = array();
            $actions = array();

            if ($rule->andor == "form") {
                foreach ($rule->actions as $action) {
                    if ($action->field != "") {
                        $actions[] = magicform_prepareAction($action, $formId);
                    }
                }
                $preparedRules .= implode("", $actions);
            } else {
                foreach ($rule->conditions as $condition) {
                    $conditions[] = magicform_prepareCondition($condition);
                    if ($condition->field != "") {
                        $type = substr($condition->field,0,strpos($condition->field,"_"));
                        if ($type == "radioButton" || $type == "checkBox" || $type == "thumbnailSelector" ) {
                            $fields[] = "input[name='".$condition->field."_value']";
                        } else if($type=="starRating" || $type=="scaleRating" ){
                            $fields[] = "input[name='".$condition->field."']";
                        }else if($type == "calculateField"){
                            $fields[] = "input:hidden[name=".$condition->field."_value]";
                        } else {
                            $fields[] = '#' . $condition->field;
                        }
                    }
                }
                foreach ($rule->actions as $action) {
                    $actions[] = magicform_prepareAction($action, $formId);
                }
                if (count($fields) > 0) {
                    $preparedRules .= '$("' . implode(",", $fields) . '").on("change",function(){';
                    $preparedRules .= "if(" . implode($rule->andor == "and" ? "&&" : "||", $conditions) . "){";
                    if (count($fields) > 0) {
                        $preparedRules .= implode("", $actions);
                    }
                    $preparedRules .= "}";
                    $preparedRules .= "});";
                }
            }
        }
    }
}

?>
<script type="text/javascript">
    jQuery(function($) {
        <?php echo ($preparedRules); ?>
    });
</script>