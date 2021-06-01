<?php
$id = esc_sql($_GET['id']);
if (isset($_GET["export"]) && $_GET['export'] == "excel") {
  $this->excel_or_csv_export($id,$_GET['export']);
}

if(isset($_GET["export"]) && $_GET['export'] == "pdf"){
  $this->pdf_export($id);
}

if(isset($_GET["export"]) && $_GET['export'] == "csv"){
  $this->excel_or_csv_export($id, $_GET['export']);
}
$arrayHelper = new MagicForm_ArrayHelper();
$detail = $this->getFormSubmission($id);
$jsonData = json_decode($detail->data);
$jsonFormData = json_decode($detail->form_data);
$arrayHelper->getElements("page", $jsonFormData->pages);
$allElements = $arrayHelper->allElements;

?>
<script>
window.onload = function() {
  window.print();
 }
 </script>
<div class="mf-settings-right" id="mf-printable">
  <div class="mf-submission-header">
    <h2>
      <?php esc_html_e("Submission Data", "magicform"); ?>
      <span>:#<?php echo esc_html($detail->id) ?>
    </h2>
  </div>
  <ul class="mf-submission-data">
    <?php foreach ($jsonData as $field_id => $field_value) :
         if(strpos($field_id, "recaptcha")!= false || $field_id == "magicform_token" || $field_id=="_wp_http_referer")
          continue;
      ?>
      <li>
        <span>
          <?php
          if (isset($allElements[$field_id])) {
            echo esc_html($allElements[$field_id]->payload->labelText);
          } else {
              echo esc_html($field_id);
            }
          ?>
        </span>
        <div>
        <?php if ((gettype($field_value) == "object" && property_exists($field_value, "value") && strstr(implode(",",(array)$field_value->value), "http"))
									|| (gettype($field_value) == "array") && array_key_exists("value", $field_value) && strstr(implode(",",$field_value["value"]), "http")) {
        
        $str = (gettype($field_value) == "object" && property_exists($field_value, "value")) ?	strstr(implode(",",(array)$field_value->value), "http"):strstr(implode(",",$field_value["value"]), "http");
        $value = gettype($field_value) == "object" ? $field_value->value:$field_value['value'];
        if(strstr($str, "http")){
          printf(
            wp_kses(
              "%s<a href='%s' target='_blank'>%s</a>",
              array('a' => array('href' => array(), 'title' => array()))
            ),
            strstr($value, "http", true),
            strstr($value, "http"),
            strstr($value, "http")
          );
        }
		} else if(gettype($field_value) == "string" && strstr($field_value, "http")) {
			printf(
				wp_kses(
					"%s<a href='%s' target='_blank'>%s</a>",
					array('a' => array('href' => array(), 'title' => array()))
				),
				strstr($field_value, "http", true),
				strstr($field_value, "http"),
				strstr($field_value, "http")
			);
			}	else {
            if (strstr($field_id, "signature") != false) {
              preg_match("/src=.*/", $field_value, $output);
              printf("<img style='width:150px; height:auto' %s", $output[0]);
            } else if(strstr($field_id, "productList") != false) {
              
              $values = explode(" ", implode(" ",(array)$field_value));
              $element = array_key_exists($field_id, $allElements)?$allElements[$field_id]:array();
              $details = magicform_product_list_detail($values, $element);
              if (count((array)$details) > 0 && (is_array($details) || is_object($details)) )
              {
                foreach($details as $key => $value){
                  echo "<div class='mf-product-submission-detail'>";
                  echo $details[$key]["Props"]['Image']?("<img class='mf-product-submission-detail-image' src='". $details[$key]["Props"]['Image'] ."'>"):"";
                  echo "<div class='mf-product-submission-label'>". esc_html("Name", "magicform") . " : " . $details[$key]["Props"]["Name"] . "</div>";
                
                  if(!empty($details[$key]['Select']) && (is_array($details[$key]['Select']) || is_object($details[$key]['Select']))){
                    foreach($details[$key]['Select'] as $value){
                      echo "<div class='mf-product-submission-label'> " . array_keys($value)[0]. " : " .array_values($value)[0] . "</div>";
                      echo "<div class='mf-product-submission-label'> " . array_keys($value)[1]. " : " .array_values($value)[1] . "</div>";
                    }
                  }
                  echo "<div class='mf-product-submission-label'>". esc_html("Quantity", "magicform") . " : " . $details[$key]['Quantity'] . "</div>";
                  echo "<div class='mf-product-submission-label'>" . esc_html("Price", "magicform") . " : " .$details[$key]["Props"]['Price'] . "</div>";
                  echo "</div>";
                  echo "<hr>";
                }
              }	
              
              echo "<div class='mf-product-submission-label'>" .esc_html("Total", "magicform"). " : " . (count($element)>0? $element->payload->currencySymbol . number_format(magicform_product_list_total($values, $allElements[$field_id]),2):0) ."</div>";
              echo "<div class='mf-product-submission-label'>" . esc_html("Payment Status", "magicform"). " : " . ($detail->payment_status?esc_html("Paid", "magicform"):esc_html("Not Paid", "magicform")) . "</div>";
            } else
            {
              if(gettype($field_value) == "array" || gettype($field_value) == "object"){
                if(gettype($field_value) == "object" && property_exists($field_value, "value")  &&  gettype($field_value->value == "object")){
                  echo esc_html(implode(", ",(array) magicform_view_inputs($field_id, $field_value)));
                }
                else {
                  echo esc_html(implode(", ",(array) $field_value));	
                }
              }else {
                echo esc_html($field_value);									
              }
            }
          } ?>
          <label>
            <?php
            if (strstr($field_id, "google") != false) {
              preg_match('/(?<=q=).*/', $field_value, $output);
              echo esc_html__("Coordinates", "magicform") . ": " . $output[0];
            }
            ?></div>
      </li>
    <?php endforeach; ?>
  </ul>
</div>

<div class="mf-useraget-footer">
  <ul class="mf-useragentul">
    <li><Label><?php esc_html_e("Form Name", "magicform"); ?>: <?php echo esc_html($detail->form_name); ?></label></li>
    <li><Label><?php esc_html_e("Date", "magicform"); ?>:<?php echo magicform_date_format($jsonFormData->settings, $detail->create_date); ?></label></li>
    <li><Label><?php esc_html_e("IP", "magicform"); ?>: <?php echo esc_html($detail->ip); ?></label></li>
  </ul>
  <ul class="mf-useragentul">
    <li><Label><?php esc_html_e("User Id", "magicform"); ?>:#<?php echo esc_html($detail->user_id); ?></label></li>
    <li><Label><?php esc_html_e("User Name", "magicform"); ?>: <?php echo esc_html($detail->user_username); ?></label></li>
    <li><Label><?php esc_html_e("User Email", "magicform"); ?>: <?php echo esc_html($detail->user_email); ?></label></li>
  </ul>
</div>
<div class="mf-useraget-footer">
  <ul class="mf-useragentul">
    <li><Label><?php esc_html_e("Browser", "magicform"); ?>: <?php echo esc_html($detail->browser); ?></label></li>
    <li><Label><?php esc_html_e("Device", "magicform"); ?>: <?php echo esc_html($detail->device); ?></label></li>
    <li><Label><?php esc_html_e("OS", "magicform"); ?>: <?php echo esc_html($detail->os); ?></label></li>
  </ul>
  <ul class="mf-useragentul">
    <li><Label><?php esc_html_e("GDPR", "magicform"); ?>: <?php echo esc_html($detail->gdpr == 1 ? "on" : "off"); ?></label></li>
    <li><Label><?php esc_html_e("User Agent", "magicform"); ?>: <?php echo esc_html($detail->user_agent); ?></label></li>
  </ul>
</div>
<style>
  html,
  body {
    margin: 0 !important;
    padding: 0 !important;
    font-family: Arial;
  }

  .mf-submission-left-list {
    list-style: none;
    margin: 0;
    padding: 0;
  }

  .mf-submission-left-list li {
    margin-bottom: 15px;
    font-size: 13px;
    font-weight: 400;
    color: #333;
  }

  .mf-submission-left-list li span {
    display: block;
    font-size: 12px;
    color: #999;
    font-weight: 500;
  }

  .mf-submission-data {}

  .mf-submission-data li {
    display: flex;
    border-bottom: 1px solid #e6ecf1;
    padding: 10px;
    margin: 0;
  }

  .mf-submission-data li:hover {
    background: #eee;
    cursor: pointer;
  }

  .mf-submission-data li span {
    font-size: 14px;
    font-weight: bold;
    color: #999;
    flex: 0 0 200px;
  }

  .mf-submission-data li div {
    color: #333;
    font-size: 15px;
    flex: 1;
  }

  .mf-useraget-footer {
    flex-wrap: wrap;
    justify-content: center;
    background: #f5f7f9;
    display: flex;
  }

  .mf-useragentul {
    flex: 1;
  }

  .mf-useragent li {
    list-style: none;
  }

  .mf-product-submission-detail{
    display: flex;
    flex-wrap:wrap;    
    justify-content:space-around;
    flex-direction:row;
}
.mf-product-submission-label{
     margin-left: 5px;
}
.mf-product-submission-detail-image{
    height:50px; 
    width:50px; 
    object-fit: cover;
}
</style>