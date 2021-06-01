<?php
$latestSubmissions = $this->latest_submissions();
?>
<div class="mf-card mf-mt-30">
    <div class="mf-card-body">
        <h6><?php esc_html_e("Latest 10 Submissions", "magicform") ?></h6>
        <div class="table-responsive">
            <?php
            $columns = array(
                "id" => array("icon" => "hashtag", "name" => esc_html__("ID", "magicform"), "sortable" => true, "width" => "70"),
                "name" => array("icon" => "list-alt", "name" => esc_html__("Form Name", "magicform"), "sortable" => true, "width" => "200"),
                "data" => array("icon" => "table", "name" => esc_html__("Form Data", "magicform"), "sortable" => false),
                "date" => array("icon" => "calendar-alt", "name" => esc_html__("Create Date", "magicform"), "sortable" => true, "width" => "180"),
            );
            if (count($latestSubmissions) > 0) :
            ?>
                <table class="table table-hover mf-table">
                    <thead>
                        <tr>
                            <?php foreach ($columns as $column_id => $column) : ?>
                                <th <?php echo isset($column["width"]) ? 'width=' . esc_attr($column["width"]) : '' ?>>
                                    <i class="fas fa-<?php echo esc_attr($column["icon"]) ?>"></i>
                                    <span><?php echo esc_html($column["name"]); ?></span>
                                </th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($latestSubmissions as $sub) : ?>
                            <tr class="mf-submission <?php echo esc_attr(intval($sub->read_status) == 0 ? "mf_unread" : "mf_read"); ?>" data-id="<?php echo esc_attr(intval($sub->id)) ?>">
                                <td><?php echo intval($sub->id) ?></td>
                                <td class="mf-submission-formname-column"><?php echo esc_html($sub->form_name) ?></td>
                                <td class="mf-submission-data-column">
                                    <?php
                                    $props = array();
                                    foreach (json_decode($sub->data) as $key => $item) :
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
                                    ?></td>
                                <td><?php echo magicform_time_elapsed_string(esc_html($sub->create_date)); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="text-right">
                    <a href="<?php echo esc_url("?page=magicform_submissions" )?>"class="mf-admin-btn mf-admin-btn-ghostblue">
                        <?php esc_html_e("See all submissions", "magicform") ?>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            <?php else : ?>
                <div class="text-center text-muted">
                    <div class="mf-p-20">
                        <img src="<?php echo esc_url(MAGICFORM_URL . "assets/images/empty.svg"); ?>" /><br />
                        <?php esc_html_e("There is no submission right now.", "magicform") ?>
                    </div>
                </div>
            <?php
            endif;
            ?>
        </div>
    </div>
</div>