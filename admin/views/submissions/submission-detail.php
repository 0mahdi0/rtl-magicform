<?php
$id = esc_sql($_GET['id']);
$arrayHelper = new MagicForm_ArrayHelper();
$detail = $this->getFormSubmission($id);
if (!isset($detail)) {
	require_once(MAGICFORM_PATH . "/admin/views/components/404.php");
	return;
}

$jsonData = json_decode($detail->data);
$jsonFormData = json_decode($detail->form_data);
$arrayHelper->getElements("page", $jsonFormData->pages);
$allElements = $arrayHelper->allElements;
$this->updateReadStatus();
?>

<div class="mf-mainContainer mf-mainContainerAdmin">
	<div class="mf-contentContainer">
		<div class="mf-header">
			<div class="mf-header-left">
				<h1><a class="mf-logo" href="<?php echo esc_url("?page=magicform_admin") ?>">
						<img src="<?php echo esc_url(MAGICFORM_URL . "/assets/images/logo_square.svg") ?>" />
						<?php esc_html_e("Submission Detail", "magicform"); ?>
					</a>
				</h1>
			</div>
		</div>
		<div class="mf-settings">
			<div class="mf-settings-left">
				<div class="mf-settings-left-inner">
					<a class="mf-admin-btn mf-submission-back-btn">
						<i class="fas fa-chevron-circle-left"></i>
						<?php esc_html_e("Back", "magicform"); ?>
					</a>
					<ul class="mf-submission-left-list">
						<li>
							<span><?php esc_html_e("FORM", "magicform"); ?></span>
							<?php echo esc_html($detail->form_name) ?>
						</li>
						<li>
							<span><?php esc_html_e("DATE", "magicform"); ?></span>
							<?php echo esc_html(magicform_date_format($jsonFormData->settings, $detail->create_date)); ?>
						</li>
						<li>
							<span><?php esc_html_e("IP", "magicform"); ?></span>
							<?php echo esc_html($detail->ip) ?>
						</li>
						<li>
							<span><?php esc_html_e("USER ID", "magicform"); ?></span>
							#<?php echo esc_html($detail->user_id); ?>
						</li>
						<li>
							<span><?php esc_html_e("USER NAME", "magicform"); ?></span>
							<?php echo esc_html($detail->user_username); ?>
						</li>
						<li>
							<span><?php esc_html_e("USER EMAIL", "magicform"); ?></span>
							<?php echo esc_html($detail->user_email); ?>
						</li>
						<li>
							<span><?php esc_html_e("BROWSER", "magicform"); ?></span>
							<?php echo esc_html($detail->browser); ?>
						</li>
						<li>
							<span><?php esc_html_e("DEVICE", "magicform"); ?></span>
							<?php echo esc_html($detail->device); ?>
						</li>
						<li>
							<span><?php esc_html_e("OS", "magicform"); ?></span>
							<?php echo esc_html($detail->os); ?>
						</li>
						<li>
							<span><?php esc_html_e("GDPR", "magicform"); ?></span>
							<?php echo esc_html($detail->gdpr); ?>
						</li>
						<li>
							<span><?php esc_html_e("USER AGENT", "magicform"); ?></span>
							<?php echo esc_html($detail->user_agent); ?>
						</li>
					</ul>

				</div>
			</div>
			<div class="mf-settings-right" id="mf-printable">
				<div class="mf-submission-header">
					<h1>
						<?php esc_html_e("Submission Data", "magicform"); ?>
						<span><?php esc_html_e("Submission Id", "magicform"); ?>: #<?php echo esc_html($detail->id) ?>
					</h1>
					<a class="mf-admin-btn" target="_blank" href="<?php echo esc_url("?action=magicform_print&id=" . intval($detail->id)) ?>" id="mf-print-button">
						<i class="fas fa-print"></i>
						<?php esc_html_e("Print", "magicform"); ?>
					</a>
					<div class="dropdown">
					<button class="mf-admin-btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="fas fa-download"></i><?php esc_html_e("Exports","magicform")?>
					</button>
					<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
							
							<a class="dropdown-item" href="<?php echo esc_url("?action=magicform_print&id=" . intval($detail->id) . "&export=excel"); ?>">
								<i class="fas fa-file-excel"></i>
								<?php esc_html_e("Excel", "magicform"); ?>
							</a>
							<a class="dropdown-item" href="<?php echo esc_url("?action=magicform_print&id=" . intval($detail->id) . "&export=csv"); ?>">
								<i class="fas fa-file-excel"></i>
								<?php esc_html_e("CSV", "magicform"); ?>
							</a>
							<a class="dropdown-item" href="<?php echo esc_url("?action=magicform_print&id=" . intval($detail->id) . "&export=pdf"); ?>">
								<i class="fas fa-file-pdf"></i>
								<?php esc_html_e("PDF", "magicform"); ?>
							</a>
					</div>
					</div>
					
				</div>
				<ul class="mf-submission-data">
					<?php foreach ($jsonData as $field_id => $field_value) : 
						?>
						
						<?php if (!strstr($field_id, "recaptcha")) : ?>
							<li>
								<span><?php
										if (isset($allElements[$field_id]->payload->labelText)) {
											echo esc_html($allElements[$field_id]->payload->labelText);
										} else {
											switch ($field_id) {
												case "magicform_token":
													esc_html_e("Csrf Token", "magicform");
													break;
												case "_wp_http_referer":
													esc_html_e("Referer", "magicform");
													break;
												default:
													echo esc_html($field_id);
											}
										}
										?></span>
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
									}else {
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
										<?php
										if (strstr($field_id, "google") != false) {
											if(gettype($field_value) == "object"){
												printf(
													wp_kses(
														"%s<a href='%s' target='_blank'>%s</a>",
														array('a' => array('href' => array(), 'title' => array()))
													),
													strstr($field_value->map_link, "http", true),
													strstr($field_value->map_link, "http"),
													$field_value->map
												);
												preg_match('/(?<=q=).*/', $field_value->map_link, $output);
												echo "<label style='margin-left:50px;'>". esc_html__("Coordinates", "magicform") . ": " . $output[0] ."</label>";
											}else {
												preg_match('/(?<=q=).*/', $field_value, $output);
												echo "<label style='margin-left:50px;'>". esc_html__("Coordinates", "magicform") . ": " . $output[0] ."</label>";
											}
											
										}
										?>
								</div>
							</li>
						<?php endif; ?>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</div>
</div>