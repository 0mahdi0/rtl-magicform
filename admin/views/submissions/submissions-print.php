<?php
if(isset($_GET['export'])){
    header('Content-Encoding: UTF-8');
    header('Content-Type: text/plain; charset=utf-8');
    
    if ($_GET['export'] == "excel") {
        header("Content-disposition: attachment; filename=export-" . time() . ".xls");
        echo "\xEF\xBB\xBF"; // UTF-8 BOM
    }
    
    if ($_GET['export'] == "csv") {
        header("Content-disposition: attachment; filename=export-" . time() . ".csv");
        echo "\xEF\xBB\xBF"; // UTF-8 BOM
    }
}

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
      
        if(isset($_GET["export"]) && $_GET['export'] == "csv"){
            $this->create_submissions_csv_file($submissionData, $formList, $jsonFormData->settings, $allElements,  $columns, $additionalColumns );
        }

?>
        <table class="table table-hover mf-table">
            <thead>
                <tr>
                    <?php foreach ($columns as $column_id => $column) : ?>
                        <th <?php echo isset($column["width"]) ? "width='".esc_attr($column["width"])."'" : "" ?>>
                            <?php echo ($column["icon"] !== null) ? '<i class="fas fa-' . $column["icon"] . '"></i>' : ''; ?>
                            <span><?php echo esc_html($column["name"]) ?></span>
                        </th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($submissionData as $row) : ?>
                    <tr class="mf-submission <?php echo ($row->read_status == 0) ? "magicform_unread" : "magicform_read" ?>" data-id="<?php echo esc_attr($row->id); ?>">
                        <td><?php echo esc_html($row->id) ?></td>
                        <td class="mf-submission-formname-column"><?php echo isset($formList[$row->form_id]) ? esc_html($formList[$row->form_id]) : "" ?></td>
                        <td><?php echo esc_html(magicform_date_format( $jsonFormData->settings, $row->create_date)); ?></td>
                        <?php
                        $rowData = (array) json_decode($row->data);
                        foreach ($additionalColumns as $key) :
                            if( strpos($key, "productList") !== false){
                                $values = explode(" ", implode(" ",(array)$rowData[$key]));
                            ?>
                                <td> <?php echo implode(", ",magicform_product_list_options($values, $allElements[$key])) ?> </td> 
                            <?php }else { ?> 
                                <td> <?php
										 if(gettype($rowData[$key]->value) == "object" || gettype($rowData[$key]->value) == "array"){
											 echo isset($rowData[$key]) ? esc_html(implode(",",(array)$rowData[$key]->value)) : "";
										 }else if(gettype($rowData[$key]) == "object" || gettype($rowData[$key]->value) == "array"){
											 echo isset($rowData[$key]) ? esc_html(implode(",",(array)$rowData[$key])) : "";
										 }else
											 echo isset($rowData[$key]) ? esc_html($rowData[$key]) : "";
									?> </td>
                           <?php }
                        endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php
    } else {
        $columns = array(
            "id" => array("icon" => "hashtag", "name" => esc_html__("ID", "magicform"), "sortable" => true, "width" => "70"),
            "name" => array("icon" => "list-alt", "name" => esc_html__("Form Name", "magicform"), "sortable" => true, "width" => "200"),
            "data" => array("icon" => "table", "name" => esc_html__("Form Data", "magicform"), "sortable" => false),
            "date" => array("icon" => "calendar-alt", "name" => esc_html__("Create Date", "magicform"), "sortable" => true, "width" => "180"),
        ); 

        if(isset($_GET["export"]) && $_GET['export'] == "csv"){
            $this->create_submissions_csv_file($submissionData, $formList, $jsonFormData->settings);
        }

        ?>
        <div class="table-responsive">
            <table class="table table-hover mf-table">
                <thead>
                    <tr>
                        <?php foreach ($columns as $column_id => $column) : ?>
                            <th <?php echo isset($column["width"]) ? "width='".esc_attr($column["width"])."'" : "" ?>>
                                <i class="fas fa-<?php echo esc_attr($column["icon"]) ?>"></i>
                                <span><?php echo esc_html($column["name"]) ?></span>
                            </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($submissionData as $row) : ?>
                        <tr class="mf-submission <?php echo ($row->read_status == 0) ? "magicform_unread" : "magicform_read" ?>" data-id="<?php echo esc_attr($row->id) ?>">
                            <td><?php echo esc_html($row->id); ?></td>
                            <td class="mf-submission-formname-column"><?php echo isset($formList[$row->form_id]) ? esc_html($formList[$row->form_id]) : "" ?></td>
                            <td class="mf-submission-data-column">
                            <?php
                                $props = array();
                                foreach (json_decode($row->data) as $key => $item) :
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
                            <td><?php echo esc_html( magicform_date_format( "", $row->create_date) ); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php } ?>

    <?php printf(esc_html__("Total %d Submissions", "magicform"), $totalRows); ?>
<?php else : ?>
    <?php esc_html_e("There is no submission now.", "magicform") ?>
<?php endif; ?>
<script>
  window.onload = function() {
    window.print();
  }
</script>