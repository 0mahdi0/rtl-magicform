<?php
$formList = $this->getFormListArray();
$data = $this->list_submissions(10);
$submissionData = $data["submissionData"];
$paginator = $data["paginator"];
$totalRows = $data["totalRows"];


?>
<div class="mf-mainContainer mf-mainContainerAdmin">
	<div class="mf-contentContainer">
		<div class="mf-header">
			<div class="mf-header-left">
				<h1>
					<a class="mf-logo" href="?page=magicform_admin">
						<img src="<?php echo esc_url(MAGICFORM_URL . "/assets/images/logo_square.svg") ?>" />
						<?php esc_html_e("Submissions", "magicform"); ?>
					</a>
				</h1>
			</div>
			<div class='mf-header-center'></div>
			<?php require_once(MAGICFORM_PATH . "/admin/views/components/header-right.php"); ?>
		</div>
		<div class="mf-forms">
			<div class="mf-forms-left">
				<?php require_once("filter.php"); ?>
			</div>
			
			<div class="mf-forms-right">
				<div class="text-right">
					<a href="<?php echo esc_url("?action=magicform_print_submissions&" . magicform_getUrlParams(array("page", "p"))) ?>" class="mf-admin-btn mf-admin-btn-ghostblue">
						<i class="fas fa-print"></i>
						<?php esc_html_e("Print", "magicform"); ?>
					</a>
					<button class="mf-admin-btn mf-admin-btn-ghostblue dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="fas fa-download"></i><?php esc_html_e("Exports","magicform")?>
					</button>
					<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						<a class="dropdown-item" href="<?php echo esc_url("?action=magicform_print_submissions&" . magicform_getUrlParams(array("page", "p")) . "&export=excel") ?>" >
							<i class="fas fa-file-excel"></i>
							<?php esc_html_e("Excel", "magicform"); ?>
						</a>
						<a class="dropdown-item" href="<?php echo esc_url("?action=magicform_print_submissions&" . magicform_getUrlParams(array("page", "p")) . "&export=csv") ?>" >
							<i class="fas fa-file-excel"></i>
							<?php esc_html_e("CSV", "magicform"); ?>
						</a>
					</div>
				</div>
				<form method="post">
					<?php
					if (count($submissionData) > 0) :

						if (isset($_GET["form_id"]) && intval($_GET["form_id"]) > 0) {
							$formData = $this->wpdb->get_row($this->wpdb->prepare("SELECT * FROM " . $this->forms_tablename . " WHERE id=%d", intval($_GET["form_id"])));

							$arrayHelper = new MagicForm_ArrayHelper();
							$jsonFormData = json_decode($formData->form_data);
							$arrayHelper->getElements("page", $jsonFormData->pages);
							$allElements = $arrayHelper->allElements;

							$columns = array(
								"id" => array("icon" => "hashtag", "name" => esc_html__("ID", "magicform"), "sortable" => true, "width" => "70"),
								"name" => array("icon" => "list-alt", "name" => esc_html__("Form Name", "magicform"), "sortable" => true, "width" => "200"),
								"date" => array("icon" => "calendar-alt", "name" => esc_html__("Create Date", "magicform"), "sortable" => true, "width" => "180"),
							);
							$additionalColumns = array();
							foreach ($submissionData as $row) {
								$rowData = json_decode($row->data);
								foreach ($rowData as $key => $c) {
									if (!in_array($key, $additionalColumns)) {
										if ($key !== "magicform_token" && strpos($key, "signature") === false && $key !== "_wp_http_referer" && !strstr($key, "recaptcha")) {
											$additionalColumns[] = esc_html($key);
										}
									}
								}
							}

							foreach ($additionalColumns as $key) {
								$columns[$key] = array("icon" => null, "name" => isset($allElements[$key]) ? esc_html($allElements[$key]->payload->labelText) : $key, "sortable" => false);
							}

					?>
							<div class="table-responsive">
								<table class="table table-hover mf-table">
									<thead>
										<tr>
											<th width="40">
												<div class="mf-custom-control mf-custom-checkbox">
													<input class="mf-custom-control-input" type="checkbox" data-id="all" value="1" id="magicform_row_all">
													<label class="mf-custom-control-label" for="magicform_row_all"></label>
												</div>
											</th>
											<?php foreach ($columns as $column_id => $column) : ?>
												<th <?php echo isset($column["width"]) ? "width='".esc_attr($column["width"])."'" : "" ?>>
													<?php if ($column["sortable"]) : ?>
														<a href="<?php echo esc_url("?" . magicform_getUrlParams(array("order", "by")) . "&order=" . $column_id . "&by=" . ((isset($_GET["by"]) && $_GET["by"] == "asc") ? "desc" : "asc")); ?>">
														<?php endif; ?>
														<?php echo ($column["icon"] !== null) ? '<i class="fas fa-' . $column["icon"] . '"></i>' : ''; ?>
														<span><?php echo esc_html($column["name"]) ?></span> <?php echo (isset($_GET["order"]) && $_GET["order"] == $column_id) ? $_GET["by"] == "asc" ? "<i class='fas fa-angle-down'></i>" : "<i class='fas fa-angle-up'></i>" : "" ?>
														<?php if ($column["sortable"]) : ?>
														</a>
													<?php endif; ?>
												</th>
											<?php endforeach; ?>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($submissionData as $row) : ?>
											<tr class="mf-submission <?php echo ($row->read_status == 0) ? "magicform_unread" : "magicform_read" ?>" data-id="<?php echo esc_attr($row->id); ?>">
												<td class="magicform_checkbox_column">
													<div class="mf-custom-control mf-custom-checkbox">
														<input class="mf-custom-control-input" type="checkbox" data-id="<?php echo esc_attr($row->id) ?>" value="1" id="magicform_row_<?php echo esc_attr($row->id) ?>">
														<label class="mf-custom-control-label" for="magicform_row_<?php echo esc_attr($row->id) ?>"></label>
													</div>
												</td>
												<td><?php echo esc_html($row->id) ?></td>
												<td class="mf-submission-formname-column"><?php echo isset($formList[$row->form_id]) ? esc_html($formList[$row->form_id]) : "" ?></td>
												<td><?php echo magicform_time_elapsed_string($row->create_date) ?></td>
												
												<?php
												$rowData = (array) json_decode($row->data);
												foreach ($additionalColumns as $key) : ?>
													<td><?php 
													if(strpos($key, "productList") !== false){
														
														$values = explode(" ", implode(" ",(array)$rowData[$key]));
														echo implode(",",magicform_product_list_options($values, $allElements[$key]));
													}
													else 
													{
														if(gettype($rowData[$key]) == "object" || gettype($rowData[$key]) == "array"){
															if(gettype($rowData[$key]) == "object" && property_exists($rowData[$key], "value")  &&  gettype($rowData[$key] == "object")){
																echo esc_html(implode(", ",(array) magicform_view_inputs($key, $rowData[$key])));
															}else {
																echo isset($rowData[$key]) ? esc_html(implode(",",(array)$rowData[$key])) : "";
															}
														}else
															echo isset($rowData[$key]) ? esc_html($rowData[$key]) : "";
													}
														?>
												</td>
												<?php endforeach; ?>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>

						<?php
						} else {
							$columns = array(
								"id" => array("icon" => "hashtag", "name" => esc_html__("ID", "magicform"), "sortable" => true, "width" => "70"),
								"name" => array("icon" => "list-alt", "name" => esc_html__("Form Name", "magicform"), "sortable" => true, "width" => "200"),
								"data" => array("icon" => "table", "name" => esc_html__("Form Data", "magicform"), "sortable" => false),
								"date" => array("icon" => "calendar-alt", "name" => esc_html__("Create Date", "magicform"), "sortable" => true, "width" => "180"),
							); 
							
							?>
							<div class="table-responsive">
								<table class="table table-hover mf-table">
									<thead>
										<tr>
											<th width="40">
												<div class="mf-custom-control mf-custom-checkbox">
													<input class="mf-custom-control-input" type="checkbox" data-id="all" value="1" id="magicform_row_all">
													<label class="mf-custom-control-label" for="magicform_row_all"></label>
												</div>
											</th>
											<?php foreach ($columns as $column_id => $column) : ?>
												<th <?php echo isset($column["width"]) ? 'width=' . esc_attr($column["width"]) : '' ?>>
													<?php if ($column["sortable"]) : ?>
														<a href="<?php echo esc_url("?" . magicform_getUrlParams(array("order", "by")) . "&order=" . $column_id . "&by=" . ((isset($_GET["by"]) && $_GET["by"] == "asc") ? "desc" : "asc")); ?>">
														<?php endif; ?>
														<i class="fas fa-<?php echo esc_attr($column["icon"]) ?>"></i>
														<span><?php echo esc_html($column["name"]) ?></span> <?php echo (isset($_GET["order"]) && $_GET["order"] == $column_id) ? (isset($_GET["by"]) && $_GET["by"] == "asc") ? "<i class='fas fa-angle-down'></i>" : "<i class='fas fa-angle-up'></i>" : "" ?>
														<?php if ($column["sortable"]) : ?>
														</a>
													<?php endif; ?>
												</th>
											<?php endforeach; ?>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($submissionData as $row) : ?>
											<tr class="mf-submission <?php echo ($row->read_status == 0) ? "magicform_unread" : "magicform_read" ?>" data-id="<?php echo esc_attr($row->id) ?>">
												<td class="magicform_checkbox_column">
													<div class="mf-custom-control mf-custom-checkbox">
														<input class="mf-custom-control-input" type="checkbox" data-id="<?php echo esc_attr($row->id) ?>" value="1" id="magicform_row_<?php echo esc_attr($row->id) ?>">
														<label class="mf-custom-control-label" for="magicform_row_<?php echo esc_attr($row->id) ?>"></label>
													</div>
												</td>
												<td><?php echo esc_html($row->id); ?></td>
												<td class="mf-submission-formname-column"><?php echo isset($formList[$row->form_id]) ? esc_html($formList[$row->form_id]) : "" ?></td>
												<td class="mf-submission-data-column">
													<?php
													$props = array();
													foreach (json_decode($row->data) as $key => $item) :;
														if( strpos($key, "productList") !== false){
															$formData = $this->wpdb->get_row($this->wpdb->prepare("SELECT * FROM " . $this->forms_tablename . " WHERE id=%d", intval($row->form_id)));
															$arrayHelper = new MagicForm_ArrayHelper();
															$jsonFormData = json_decode($formData->form_data);
															$arrayHelper->getElements("page", $jsonFormData->pages);
															$allElements = $arrayHelper->allElements;
															$values = explode(" ", implode(" ",(array)$item));
															$props[] = implode(", ",magicform_product_list_options($values, $allElements[$key]));
														}
														else if ($key !== "magicform_token" && strpos($key, "signature") === false && $key !== "_wp_http_referer" && !strstr($key, "recaptcha") && $item != "")
															$props[] = magicform_view_inputs($key, $item);
													endforeach;
														echo "<i>" . esc_html(implode(", ", $props)) . "</i>";
													?>
												</td>
												<td><?php echo magicform_time_elapsed_string($row->create_date) ?></td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						<?php } ?>

						<div class="mf-pagination">
							<div class="mf-total">
								<select class="mf-admin-form-control mf-multi-action">
									<option value=""><?php esc_html_e("Select Action", "magicform") ?></option>
									<option value="delete"><?php esc_html_e("Delete Selected", "magicform") ?></option>
								</select>
								<?php printf(esc_html__("Total %d Submissions", "magicform"), $totalRows); ?>
							</div>
							<?php echo ($paginator); ?>
						</div>
					<?php else : ?>
						<?php esc_html_e("There is no submission now.", "magicform") ?>
					<?php endif; ?>
				</form>
			</div>
		</div>
	</div>
</div>

<?php
/**
 * Delete Submission Modal
 */
?>
<div class="modal fade in" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header danger">
				<h5 class="modal-title" id="exampleModalLongTitle"> <?php esc_html_e("Delete Submissions", "magicform") ?></h5>
			</div>
			<div class="modal-body">
				<?php printf(esc_html__("Are you sure to delete %s submission(s)?", "magicform"), "<b class='submissionCount'></b>"); ?>
			</div>
			<div class="modal-footer">
				<a data-dismiss="modal" href="#" class="mf-admin-btn"> <?php esc_html_e("Cancel", "magicform") ?></a>
				<a id="mf-delete-submission-btn" href="javascript:void(0)" class="mf-admin-btn mf-admin-btn-red">
					<i class="fas fa-trash-alt"></i>
					<?php esc_html_e("Delete", "magicform") ?>
				</a>
			</div>
		</div>
	</div>
</div>