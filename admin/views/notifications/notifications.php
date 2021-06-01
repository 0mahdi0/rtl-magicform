<?php
$formList = $this->getFormListArray();

$data = $this->list_notifications(10);
$notificationsData = $data["data"];
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
					<a href="<?php echo esc_url("?action=magicform_print_submissions&" . magicform_getUrlParams(array("page", "p")) . "&excel") ?>" class="mf-admin-btn mf-admin-btn-ghostblue">
						<i class="fas fa-file-excel"></i>
						<?php esc_html_e("Export to Excel", "magicform"); ?>
					</a>
				</div>
				<form method="post">
					<?php
					if (count($notificationsData) > 0) :

							$columns = array(
								"id" => array("icon" => "hashtag", "name" => esc_html__("ID", "magicform"), "sortable" => true, "width" => "70"),
								"name" => array("icon" => "list-alt", "name" => esc_html__("Form Name", "magicform"), "sortable" => true, "width" => "200"),
								"title" => array("icon" => "list-alt", "name" => esc_html__("Title", "magicform"), "sortable" => false, "width" => "200"),
								"description" => array("icon" => "list-alt", "name" => esc_html__("Description", "magicform"), "sortable" => false, "width" => "200"),
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
										<?php foreach ($notificationsData as $row) : ?>
											<tr class="mf-submission <?php echo ($row->read_status == 0) ? "magicform_unread" : "magicform_read" ?>" data-id="<?php echo esc_attr($row->id); ?>">
												<td class="magicform_checkbox_column">
													<div class="mf-custom-control mf-custom-checkbox">
														<input class="mf-custom-control-input" type="checkbox" data-id="<?php echo esc_attr($row->id) ?>" value="1" id="magicform_row_<?php echo esc_attr($row->id) ?>">
														<label class="mf-custom-control-label" for="magicform_row_<?php echo esc_attr($row->id) ?>"></label>
													</div>
												</td>
												<td><?php echo esc_html($row->id) ?></td>
												<td class="mf-submission-formname-column"><?php echo isset($formList[$row->form_id]) ? esc_html($formList[$row->form_id]) : "" ?></td>
												<td><?php echo $row->title ?></td>
												<td><?php echo $row->data ?></td>
												<td><?php echo magicform_time_elapsed_string($row->create_date) ?></td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>

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
						<?php esc_html_e("There is no notifications.", "magicform") ?>
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