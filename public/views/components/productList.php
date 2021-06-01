<?php
$payload = $item->payload;
$id = $item->id;
$currency = "";
foreach ($actions as $action){
    if($action->type == "paypal")
        $currency = $action->payload->currency;
}
?>
<div class='mf-form-group mf-form-group-<?php echo esc_attr($id); ?>'>
<?php magicform_getInputLabel($payload,strpos($themeName,"material") === 0) ?>
    <div class="mf-input-container">
    <?php magicform_getInputDescription($payload, "top") ?>
    <?php echo strpos($themeName,"material") === 0 ? '<div class="mf-material-container ' . (isset($payload->defaultValue)? "mf-active" : "") . ' ' . (isset($payload->inputIcon)?"mf-with-icon" : "") . ' ">  <br/>
        <br/>' : '' ?>
        <?php magicform_getInputIconStart($payload) ?>
        <div class="mf-product-list">
            <?php 
                $index = 0;
                foreach ($payload->products as $product){ ?>
                    <div class="mf-product-container" >
                        <div class="mf-custom-control mf-custom-control-inline mf-custom-checkbox">
                            <input class="mf-custom-control-input mf-product-control-input-<?php echo esc_attr($id);?>" type="checkbox" name="<?php echo esc_attr($id)."_".$index; ?>" value="" id="<?php echo esc_attr($id . "_" . $index); ?>"/>
                            <label class="mf-custom-control-label" for="<?php echo esc_attr($id . "_" . $index); ?>"></label>
                        </div>
                        <img class="mf-product-image" src="<?php echo esc_attr($product->views[0])?>" />
                        <div class="mf-product-detail">
                            <span class="mf-product-name"><?php echo esc_html($product->name);?></span>  
                            <span class="mf-product-description"><?php echo esc_html($product->description);?></span>  
                        </div>
                       <label class="mf-product-list-label"> <?php echo esc_html("Price")?>:</label>  
                       <span class="mf-product-list-label mf-product-list-price"  price="<?php echo esc_html($product->price);?>" target-id="<?php echo esc_attr($id . "_" . $index); ?>" id="magicform-product-price-<?php echo esc_attr($id . "_" . $index); ?>" class="mf-product-price"><?php echo esc_html($payload->currency);?> <?php echo esc_html(number_format($product->price,2));?></span>
                    </div>
                    <div class="mf-product-options">
                        <?php if($product->quantity):?>
                            <div class="mf-product-quantity">
                                <label class="mf-product-list-label"><?php echo esc_html("Quantity");?> </label>
                                <input  type='number' value="1" target-id="<?php echo esc_attr($id . "_" . $index); ?>" id="<?php echo esc_attr($id . "_" . $index."_0"); ?>" class="mf-quantity mf-form-control mf-quantity-<?php echo esc_attr($id . "_" . $index."_0"); ?>"/>
                            </div>
                        <?php  endif; ?>
                        <?php 
                        $selectInputIndex = 0;
                        foreach($product->options as $option) { ?>
                            <div class="mf-product-option">
                                <label class="mf-product-list-label"><?php echo esc_attr($option->label)?></label>
                                <select target-id="<?php echo esc_attr($id . "_". $index); ?>" id="<?php echo esc_attr($id . "_". $index."_".$selectInputIndex); ?>" class="mf-option mf-select-option-<?php echo esc_attr($id . "_" . $index); ?> mf-form-control">
                                    <?php $optionIndex = 0; 
                                        foreach($option->items as $item) {?>
                                        <option key="<?php echo esc_attr($optionIndex)?>" <?php echo esc_attr($option->calcValues == true? "calcValue=". $item->calcValues . "":"")?> value="<?php echo esc_attr($item->value);?>"> <?php echo esc_html($item->name); ?> </opttio> 
                                    <?php 
                                        $optionIndex ++;
                                    } ?>
                                </select>
                            </div>
                        <?php
                        $selectInputIndex ++;
                        }?>
                    </div>
                <?php $index++; }?>
        </div>
        <div class="mf-product-list-total" >
            <div class="mf-total-label"><?php echo esc_html("Total");?>:</div>
            <div class="mf-product-list-label" id="mf-total-<?php echo esc_attr($id)?>"></div>
            <input type="hidden"  name="<?php echo esc_attr($id);?>_options" id="mf-selected-options-<?php echo esc_attr($id);?>"/>
            <input type="hidden" name="<?php echo esc_attr($id);?>_total" id="mf-total-input-<?php echo esc_attr($id);?>">
        </div>
        <?php echo strpos($themeName,"material") === 0? '<span class="mf-material-label">' .esc_html($payload->labelText) . '</span>' : ''; ?>
        <?php magicform_getInputIconEnd($payload);  ?>
        <?php echo strpos($themeName,"material") === 0 ? '</div>' : '' ?>
        <div class="mf-error"></div>
        <?php magicform_getInputDescription($payload, "bottom") ?>
    </div>
</div>
  

<script type="text/javascript">
    var totalAmount = 0;
    var currencySymbol = "<?php echo esc_html($payload->currencySymbol);?>";
    var pos = "<?php echo $payload->currencyPos?"left":"right"?>"
    
    jQuery(function($) {
       

        $(".mf-product-control-input-<?php echo esc_js($id);?>").on('change', function () {
            totalHandleChange()
        })

        function totalHandleChange() {
            var total = 0;
            var optionList = []
            $("#mf-selected-options-<?php echo esc_attr($id);?>").val("")
            $(".mf-product-control-input-<?php echo esc_attr($id)?>" ).each(function(){
                if ($(this).is(':checked')) {
                    var id = $(this).attr("id")
                    total += parseFloat($("#"+id).val())
                    getProductOptions(id,optionList);
                }
            })
            totalAmount = parseFloat(total).toFixed(2)
            $("#mf-total-input-<?php echo esc_attr($id);?>").val(totalAmount).change()
            
            if(pos == "left")
                totalAmount = currencySymbol  + totalAmount 
            else 
                totalAmount = totalAmount + currencySymbol;

            $("#mf-total-<?php echo esc_attr($id)?>").html(totalAmount);
        }

        function optionSelected(id) {
            var item = $( ".mf-select-option-"+id )
            var cost = 0;
            if(item){
                $(item).each(function(){
                    if($('option:selected', this).attr('calcvalue') !== undefined){
                        cost +=  parseFloat($('option:selected', this).attr('calcvalue'));
                    }
                });
                return cost;
            }
            return 0;
        }
        // Price update according to options 
       function changePrice () {
            
            $(".mf-product-list-price" ).each(function(){
                var targetId = $(this).attr("target-id");
                var price =  parseFloat($("#magicform-product-price-"+targetId).attr("price"));    
                var total = 0;
                
                if($(".mf-select-option-"+targetId ).length>0){
                    $(".mf-select-option-"+targetId ).each(function(){
                        if($('option:selected', this).attr('calcValue') !== undefined){
                        
                            price += optionSelected(targetId)
                            if($("#"+targetId+"_0").is("input"))
                                total =  parseInt($("#"+targetId+"_0").val())*price
                            else 
                                total = parseInt(price)
                        }
                    })
                }else if($("#"+targetId+"_0").val() !== undefined){
                    total = price + optionSelected(targetId)
                    total *= parseFloat($("#"+targetId+"_0").val());
                }else {
                    total = price + optionSelected(targetId)
                }
                                
                total = parseFloat(total).toFixed(2);

                $("#"+targetId).val(total)
                
                totalLabel = total;
                if(pos == "left")
                    totalLabel = currencySymbol  + total 
                else 
                    totalLabel = total + currencySymbol;

                $("#magicform-product-price-"+targetId).html(totalLabel);
            })
        }
        changePrice();
      

        $(".mf-quantity, .mf-option").on('change', function () {
            changePrice();
            totalHandleChange();
        })

        // The method that collects selected options. 
       function getProductOptions (id, optionList) {
           
            var selectOption = $( ".mf-select-option-"+id )
            var quantity = $(".mf-quantity-"+id+"_0")
          
            if(selectOption){
                $(selectOption).each(function(){
                        optionList.push({
                            id: $(this).attr("id")+"_"+$('option:selected', this).attr("key"),
                            type: "select",
                            val : $('option:selected', this).attr("calcValue")?$('option:selected', this).attr("calcValue"):0
                        })
                });
            }

            if($(quantity).val() !== undefined){
                optionList.push({
                    id:  $(quantity).attr("id")+"_0",
                    type: "quantity",
                    val: $(quantity).val()
                })
            }else {
                optionList.push({
                    id:  id+"_0",
                    type: "quantity",
                    val: 1
                })
            }

            $("#mf-selected-options-<?php echo esc_attr($id);?>").val(JSON.stringify(optionList))
        }
    })
</script>
