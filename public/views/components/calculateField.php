<?php
$payload = $item->payload;
$id = $item->id;
?>

<div class='mf-form-group mf-form-group-<?php echo esc_attr($id); ?>'>
    <?php magicform_getInputLabel($payload) ?>
    <div class="mf-input-container">
        <label class="mf-calculate-field" id="<?php echo esc_attr($id)?>"></label>
        <input type="hidden" value="0" name="<?php echo esc_attr($id);?>_value" id="<?php echo esc_attr($id);?>_value" />
        <input type="hidden" value="<?php echo esc_attr($payload->currency);?>" name="<?php echo esc_attr($id);?>_currency" id="<?php echo esc_attr($id);?>_currency" />
    </div>
</div>
<?php
preg_match_all('/\{([^{}]+)\}/', $payload->calculateVariables, $matches);
$inputs = array();
foreach ($matches[1] as $match) {
    if (strpos($match, "|") !== false) {
        $parts = explode("|", $match);
        $inputs[] = $parts[1];
    }
}

if (count($inputs) > 0) {
    $inputWithNames = [];
    foreach ($inputs as $input) {
        $type = substr($input, 0, strpos($input, "_"));
        switch ($type) {
            case "radioButton":
            case "checkBox":
            case "thumbnailSelector":
                $inputWithNames[] = "input[name='" . $input . "_value']";
                break;
            case "starRating":
            case "scaleRating":
            case "productList":
                $inputWithNames[] = "input[name='" . $input . "_total']";
                break;
            default:
                $inputWithNames[] = "#" . $input;
                break;
        }
    }
}
$checkboxCalcValue = "";
$selectCalcValues = "";
$thumbnailCalcValue = "";
$calcResult = preg_replace_callback('/\{([^{}]+)\}/', function ($match) use(&$checkboxCalcValue, &$selectCalcValues, &$thumbnailCalcValue) {
    $variable = $match[1];
    if (strpos($variable, "|") !== false) {
        $parts = explode("|", $variable);
        $inputId = $parts[1];
        $type = substr($inputId, 0, strpos($inputId, "_"));
        
        switch ($type) {
            case "selectBox":
                return '(parseFloat($("#'.$inputId .' :selected").attr("calcValues")) || 0)';
            case "radioButton":
                return '(parseFloat($("input[name=\'' . $inputId . '_value\']:checked").attr("calcValues")) || 0)';
                break;
            case "multiSelect":
                $selectCalcValues .= "var ".$inputId."CalcValues = 0;";
                $selectCalcValues .= ' $.each($("#'.$inputId .' option:selected"), function(){
                    '.$inputId.'CalcValues += (parseFloat($(this).attr("calcValues")) || 0);
                });';
                return $inputId.'CalcValues';
            break;
            case "thumbnailSelector":
                $thumbnailCalcValue .= "var ".$inputId."CalcValue = 0;";
                $thumbnailCalcValue .= ' $.each($("input[name=\'' . $inputId . '_value\']:checked"), function(){
                    '.$inputId.'CalcValue += (parseFloat($(this).attr("calcValues")) || 0);
                });';
                return $inputId.'CalcValue';
            break;
            case "checkBox":
                $checkboxCalcValue .= "var ".$inputId."CalcValue = 0;";
                $checkboxCalcValue .= ' $.each($("input[name=\'' . $inputId . '_value\']:checked"), function(){
                    '.$inputId.'CalcValue += (parseFloat($(this).attr("calcValues")) || 0);
                });';
                return $inputId.'CalcValue';
            break;
            case "starRating":
            case "scaleRating":
                return '(parseFloat($("input[name=\'' . $inputId . '\']").val()) || 0)';
                break;
            case "productList":
                return '(parseFloat($("input[name=\'' . $inputId . '_total\']").val()) || 0)';
                break;
            default:
                return '(parseFloat($("#' . $inputId . '").val()) || 0)';
                break;
        }
    }
}, $payload->calculateVariables);

?>
<script type="text/javascript">
    jQuery(function($) {
      
        $("<?php echo implode(',', $inputWithNames) ?>").on("change input", function() {
            <?php echo !empty($checkboxCalcValue)?$checkboxCalcValue:""; ?>
            <?php echo !empty($thumbnailCalcValue)?$thumbnailCalcValue:""; ?>
            <?php echo !empty($selectCalcValues)?$selectCalcValues:"";?>
            var total = (parseFloat(<?php echo $calcResult ?>) || 0)
            $("#<?php echo esc_attr($id);?>_value").val(total).trigger('change');
            total = calculation_formating(total);
            $("#<?php echo esc_attr($id)?>").text(total);
        });

        function  calculation_formating(total) {
            
            var symbolStart = "<?php echo ((!empty($payload->symbolStart) && !empty($payload->customSymbol))?$payload->symbolStart:"")?>";
            var symbolEnd = "<?php echo  ((!empty($payload->symbolEnd) && !empty($payload->customSymbol))?$payload->symbolEnd:"") ?>";
            var symbolGroup = "<?php echo (!empty($payload->symbolGroup)?$payload->symbolGroup:",")?>";
            var symbolDecimal = "<?php echo (!empty($payload->symbolDecimal)?$payload->symbolDecimal:".")?>";
            var decimalCount = <?php echo (!empty($payload->decimalCount)?$payload->decimalCount:0)?>;

            var currencySymbol = "<?php echo !$payload->customSymbol?$payload->currencySymbol:"" ?>";
            var currencyPos = "<?php echo $payload->currencyPos?"left":"right"?>";
          
           
            total = total.toFixed(decimalCount).toString().replace(/\./g, symbolDecimal);

            total = total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, symbolGroup);          
            
           
            if(currencyPos == "left")
                total = currencySymbol + " " + total;
            else 
                total += " " + currencySymbol;

            total = symbolStart + " " + total + " " + symbolEnd;

            return total;
        }
    })
</script>