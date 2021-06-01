<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    MagicForm
 * @subpackage MagicForm/admin
 * @author     MagicLabs
 */

use JasonGrimes\Paginator;

class MagicForm_Submissions
{

    // Plugin Name
    private $plugin_name;

    // Plugin Version
    private $version;

    // Table name for forms table
    private $forms_tablename;

    // Table name for submissions table
    private $submissions_tablename;

    // Global wpdb object
    private $wpdb;
    public $pages = array();
    private $allElements = array();
    private $uploadedFiles = array();
    private $formColumn = "form_data";
    public $formValidation;

    /**
     * Constructor
     *
     * @param String $plugin_name
     * @param String $version
     * @param String $forms_tablename
     * @param String $submissions_tablename
     */
    public function __construct($plugin_name, $version, $forms_tablename, $submissions_tablename)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->forms_tablename = $forms_tablename;
        $this->submissions_tablename = $submissions_tablename;
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->formValidation = new MagicForm_Validation();
    }

    public function setPages()
    {
        /**
         * Dashboard Page
         * Subpage
         */
        $demo_control = apply_filters("magicform_demo_check_license","notdemo",10,3);
        add_submenu_page("magicform_admin", esc_html__("Submissions","magicform"), esc_html__("Submissions","magicform"), $demo_control !== "demo"?"manage_options":"edit_posts", "magicform_submissions", array($this, "view"));
    }

    /**
     * View
     */
    public function view()
    {
        $subpage = isset($_GET["subpage"]) ? $_GET["subpage"] : null;
        switch ($subpage) {
            case "detail":
                return require_once(MAGICFORM_PATH . "/admin/views/submissions/submission-detail.php");
            default:
                return require_once(MAGICFORM_PATH . "/admin/views/submissions/submissions.php");
        }
    }

    /**
     * List all submissions
     */
    function list_submissions($perPage)
    {
        $page = isset($_GET["p"]) ? $_GET["p"] : 1;
        $pageLimit = intval($page) > 0 ? ($page - 1) * $perPage : 0;
        $limit = "LIMIT " . intval($pageLimit) . "," . $perPage;
        $variables = array(1);
        // Limitsiz
        if ($perPage == 0) {
            $limit = "";
        }
        $cond = "WHERE 1=%d ";

        $orderBy = isset($_GET["by"]) ? $_GET["by"] == "asc" ? "asc" : "desc" : "";
        $orderField = isset($_GET["order"]) ? sanitize_text_field($_GET["order"]) : "";
        switch ($orderField) {
            case "id":
                $order = "ORDER BY `id` ". sanitize_key($orderBy);
                break;
            case "name":
                $order = "ORDER BY `form_id` ".sanitize_key($orderBy);
                break;
            case "date":
                $order = "ORDER BY `create_date` ".sanitize_key($orderBy);
                break;
            default:
                $order = "ORDER BY id DESC";
        }

        if (isset($_GET["form_id"]) && intval($_GET["form_id"]) > 0) {
            $cond .= " AND form_id=%d";
            $variables[] = intval($_GET["form_id"]);
        }
        if (isset($_GET["read_status"]) && $_GET["read_status"] != "") {
            $cond .= " AND read_status=%d";
            $variables[] = intval($_GET["read_status"]);
        }
        if (isset($_GET["q"]) && $_GET["q"] != "") {
            $cond .= " AND data like '%%%s%%' ";
            $variables[] = sanitize_text_field(($_GET["q"]));
        }

        $sql = $this->wpdb->prepare("SELECT * FROM " . $this->submissions_tablename . " " . $cond . " " . $order . " " . $limit, $variables);
        $submissionData = $this->wpdb->get_results($sql);
        $countSql = $this->wpdb->prepare("SELECT count(id) as total FROM " . $this->submissions_tablename . " " . $cond, $variables);
        $totalRows = $this->wpdb->get_row($countSql)->total;

        //Pagination
        $paginator = new Paginator($totalRows, $perPage, $page, esc_url("?" . magicform_getUrlParams(array("p"))) . "&p=(:num)");

        return array(
            "submissionData" => $submissionData,
            "totalRows" => $totalRows,
            "paginator" => $paginator
        );
    }

    /**
     * Single submission print
     */
    public function print_submission()
    {
        require_once(MAGICFORM_PATH . "/admin/views/submissions/submission-print.php");
    }

    /**
     * Print submissions
     */
    function print_submissions()
    {
        $formList = $this->getFormListArray();
        $data = $this->list_submissions(0);
        $submissionData = $data["submissionData"];
        $totalRows = $data["totalRows"];
        require_once(MAGICFORM_PATH . "/admin/views/submissions/submissions-print.php");
    }

    /**
     * Form List
     */
    function getFormListArray()
    {
        $formList = $this->wpdb->get_results("SELECT id, form_name FROM " . $this->forms_tablename);
        $data = array();
        foreach ($formList as $item) {
            $data[$item->id] = $item->form_name;
        }
        return $data;
    }

    /**
     * Pdf Export
    */
    public function pdf_export($id){
        $result = (array) $this->getFormSubmission($id);
        $formSettings = json_decode($result['form_data']);
        $formData = json_decode($result['data']);
        $this->getElements('page', $formSettings->pages);
        
        $actionArr = array();
        foreach($formSettings->actions as $action)
            if($action->type == "generatePdf")
                $actionArr = $action;

 
        $file = magicform_pdf_generator((array)$formData, $id, $formSettings, $this->allElements,$actionArr,$result['page_title'], $result['page_url'], $this->submissions_tablename);
        
        header('Content-Description: File Transfer');
        header("Content-Disposition:attachment;filename=" .$result['form_name'] . "-Submission-" . $id . ".pdf");   
        header("Content-type:application/pdf");
        header("Content-Length: " . filesize($file));     
        ob_clean();
        flush();
        readfile($file);
        die();
     }

    /**
     * Excel Export
     */
    public function excel_or_csv_export($id, $type)
    {
        $result = (array) $this->getFormSubmission($id);
        $formSettings = json_decode($result['form_data']);
        $row = array();
    
        foreach ($result as $key => $item) {
            if ($key != 'data' && $key != 'form_data') {
                if($key == "create_date"){
                   array_push($row, magicform_date_format($formSettings->settings, $item));
                }else 
                    array_push($row, $item);
            }
        }
        $columns = array("Form Name", "Id", "Form Id", "Create Date", "Ip", "User Agent", "Os", "Browser", "Device", "User Id", "User Name", "User Email", "Gdpr", "Page Title", "Page Url", "Read Status","Payment Status", "Total Amount", "Payment Error");
        $formData = json_decode($result['data']);
        $filename = $id . "-" . $formSettings->settings->name;

        $this->getElements('page', $formSettings->pages);
        $this->merge_submit_data($formData, $columns, $row);
        
        ob_end_clean();
        header('Content-Encoding: UTF-8');
        header('Content-Type: text/plain; charset=utf-8');

        if($type == "excel"){
            header("Content-disposition: attachment; filename=" . $filename . ".xls");
            echo "\xEF\xBB\xBF"; // UTF-8 BOM
            $this->create_excel_file($columns, $row);
        }
        else if($type == "csv"){
            header("Content-disposition: attachment; filename=" . $filename . ".csv");
            echo "\xEF\xBB\xBF"; // UTF-8 BOM
            $this->create_csv_file($columns, $row);
            
        }
            
        die();
    }

    /**
     * Merge Data
     */
    function merge_submit_data($formData, &$columns, &$row)
    {
        // print_r("test");
        // die();
        foreach ($formData as $field_id => $field_value) {
            if (strpos($field_id, "signature") === false && strpos($field_id, "g-recaptcha-response") === false){
                $field_name = isset($this->allElements[$field_id]) ? $this->allElements[$field_id]->payload->labelText : $field_id;
                array_push($columns, $field_name);
                array_push($row, magicform_view_inputs($field_id, $field_value));
            }
        }
    }

    /**
     * Create Excel File
     */
    private function create_excel_file($columns, $row)
    {
        $colCount = count($columns);

        echo '<table border="1"><tr>';
        foreach ($columns as $col) {
            echo '<th>' . esc_html($col) . '</th>';
        }
        echo '</tr>';

        echo '<tr>';
        for ($i = 0; $i < $colCount; $i++) {
            echo '<td>' . esc_html($row[$i]) . '</td>';
        }
        echo '</tr>';
        echo '</table>';
    }

    /**
     * Create Csv File
    */
    function create_csv_file($columns, $row){
        
        $fp = fopen('php://output','w');

        fputcsv($fp, $columns,";");
        fputcsv($fp, $row,';');

        fclose($fp);
    }

    /**
     * Create Submissions Csv File
    */

    function create_submissions_csv_file ($submissionData, $formList, $formSettings, $allElements = array(), $columns = array() ,$additionalColumns = array()){
        ob_end_clean();
        $fp = fopen('php://output','w');
        $titles = array();
        $parent = array();
        if(empty($columns)) {        
             
            $titles = array("ID","Form Name" ,"Form Data", "Create Date");
            fputcsv($fp, $titles,';');
             
            foreach($submissionData as $row){
                $formData = array();
                $child = array();
                array_push($child,$row->id);
                array_push($child, isset($formList[$row->form_id]) ? esc_html($formList[$row->form_id]) : "");
                foreach (json_decode($row->data) as $key => $item) {
                    if ($key !== "magicform_token" && strpos($key, "signature") === false && $key !== "_wp_http_referer" && !strstr($key, "recaptcha") && $item != ""){
                        if( strpos($key, "productList") !== false){
                            $getFormById = $this->wpdb->get_row($this->wpdb->prepare("SELECT * FROM " . $this->forms_tablename . " WHERE id=%d", intval($row->form_id)));
                            $jsonFormData = json_decode($getFormById->form_data);
                            $this->getElements("page", $jsonFormData->pages);
                            $values = explode(" ", implode(" ",(array)$item));
                            array_push($formData, implode(", ",magicform_product_list_options($values, $this->allElements[$key])));
                        }else {
                            array_push($formData, magicform_view_inputs($key, $item));
                        }
                    }
                        
                }
                array_push($child,implode(";",$formData));
                array_push($child, magicform_date_format( $formSettings, $row->create_date) );
                $parent[] = $child;
            }
            foreach($parent as $value)
                fputcsv($fp, $value,';');

        }else {
           
            foreach($columns as $column_id => $column){
                array_push($titles,$column["name"]);
            }

            fputcsv($fp, $titles,';');

            $parent = array();
            foreach($submissionData as $row){
                $child = array();
                array_push($child,$row->id);
                array_push($child, isset($formList[$row->form_id]) ? $formList[$row->form_id] : "");
                array_push($child,  magicform_date_format( $formSettings, $row->create_date));
                $rowData = (array) json_decode($row->data);
                foreach ($additionalColumns as $key){
                    if ($key !== "magicform_token" && strpos($key, "signature") === false && $key !== "_wp_http_referer" && !strstr($key, "recaptcha"))
                    {
                        if( strpos($key, "productList") !== false){
                           
                            $values = explode(" ", implode(" ",(array)$rowData[$key]));
                            array_push($child, implode(",",magicform_product_list_options($values, $allElements[$key])));
                        }else {
                            array_push($child,isset($rowData[$key]) ? magicform_view_inputs($key,$rowData[$key]) : "");
                        }
                    }
                }
                $parent[] = $child;
            }
        }

        foreach($parent as $value)
                fputcsv($fp, $value,';');
           
        fclose($fp);
        die();
    }

    /**
     * Normalize Form
     */
    function normalizeForm($formData)
    {
        $inputs = array();
        $pattern = "/([a-zA-Z]+)_{1}([0-9]+)_([0-9]+)/";
        foreach ($formData as $inputName => $value) {
            preg_match_all($pattern, $inputName, $results);
            if (isset($results[0][0]) && $results[0][0] != "") {
                if ($results[0][0] == $inputName) {
                    $type = substr($inputName, 0, strpos($inputName, "_"));
                    switch ($type) {
                        case "signature":
                            // used inline style to save to db
                            $value = "<img style='width:150px; height:auto' src='" . $value . "'/>";
                            break;
                    }
                    $inputs[$inputName] = $value;
                } else {
                    $subInput = trim(str_replace($results[0][0], "", $inputName), "_");
                    $inputs[$results[0][0]][$subInput] = $value;
                }
            } else {
                //other inputs
                $inputs[$inputName] = $value;
            }
        }

        return $inputs;
    }

    /**
     * Upload Files
     *
     * @param object $formData
     * @param object $translate
     * 
     * @return object
     */
    function uploadFiles($formData, $translate)
    {
        $this->uploadedFiles = array();
        WP_Filesystem();
        global $wp_filesystem;

        foreach ($_FILES as $field_id => $file) {
            $field = $this->allElements[$field_id];
            $allowedExts = magicform_convertExtensionsToMimeTypes($field->payload->allowedFileTypes);

            // File Extension Control
            if (!in_array($file['type'], $allowedExts)) {
                return (object) array('success' => false, 'id' => $field_id , 'message' => $translate->extensionErr);
            }

            if ($file["size"] > magicform_getFileSizeKB($field->payload->fileSize, $field->payload->fileSizeType)) {
                return (object) array('success' => false,  'id' => $field_id, 'message' => $translate->fileSizeErr);
            }
           
            $result = wp_upload_bits($file["name"], null, $wp_filesystem->get_contents($file["tmp_name"]));
            $result['name'] = $file["name"];
            $formData[$field_id] = $result["url"];
            $this->uploadedFiles[$field_id] = $result;
        }
        return (object) array('success' => true, 'formData' => $formData);
    }

    /**
     * Save submission
     *
     * @return void
     */
    function save_submission()
    {
        $formId = intval($_POST["formId"]);
        if (!isset($formId) || !$formId > 0 || empty($_POST['data'])) {
            wp_send_json_error('error', 404);
            return false;
        }

        $formData = stripslashes($_POST["data"]);
        $hiddenInputs =  json_decode(stripslashes($_POST['hiddenInputs']));
        $formData = (array) json_decode($formData);

        if ($_POST["submitType"] == 'preview')
            $this->formColumn = "preview_form_data";

        // get form settings
    
        $formSettings = $this->wpdb->get_row($this->wpdb->prepare("SELECT " . $this->formColumn . " FROM " . $this->forms_tablename . " WHERE id =%d", $formId), ARRAY_A);
        $formSettings = json_decode($formSettings[$this->formColumn]);
        $errorMessages = [];
        if ($_POST["submitType"] !== 'preview' || (isset($_POST['validationCheck']) && $_POST['validationCheck'])) {
            $this->formValidation->getValidation('page', $formSettings->pages, $formSettings->settings->translate);
            $errorMessages = $this->formValidation->validationList($formData,$hiddenInputs);
        }

        if (count($errorMessages) > 0) {
            wp_send_json_error(array("type" => "validation", "errors" => $errorMessages));
            return false;
        }

        if (isset($formData['g-recaptcha-response'])) {
            if (empty($formData['g-recaptcha-response'])) {
                wp_send_json_error('Recaptcha is empty', 403);
                return false;
            }

            if ($this->verifyRecaptcha($formData['g-recaptcha-response'], 'recaptchaSecretv2')) {
                wp_send_json_error('Recaptcha verify is invalid', 401);
                return false;
            }
        } else if (isset($formData['recaptchav3'])) {

            if (empty($formData['recaptchav3'])) {
                wp_send_json_error('Recaptcha is empty', 403);
                return false;
            }

            if ($this->verifyRecaptcha($formData['recaptchav3'], 'recaptchaSecretv3')) {
                wp_send_json_error('Recaptcha verify is invalid', 401);
                return false;
            }
        }

        if(isset($formData['magic-name'])){
            if(!empty($formData['magic-name'])){
                wp_send_json_error('Posted by a spawner', 403);
                return false;
            }
        }

        if(isset($formData['magic-email'])){
            if(!empty($formData['magic-email'])){
                wp_send_json_error('Posted by a spawner', 403);
                return false;
            }
        }

        // Use page variables
        $pageTitle = htmlspecialchars($_POST["pageTitle"], ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $pageUrl = $_POST["pageUrl"];

        $this->getElements("page", $formSettings->pages);
       

        $formData = $this->normalizeForm($formData);

        $uploadResult = $this->uploadFiles($formData, $formSettings->settings->translate);
        if (!$uploadResult->success) {
            wp_send_json_error(array("type" => "fileuploads", "id" => $uploadResult->id, "error" => $uploadResult->message));
            return false;
        }
        $formData = $uploadResult->formData;
       
        // csrf token check
        if ($formSettings->settings->csrfToken) {
            $nonce = $formData["magicform_token"];
            if (!wp_verify_nonce($nonce, 'submit_magicform_form_nonce')) {
                wp_send_json_error(esc_html__('Something is wrong with CSRF token', "magicform"), 403);
                return false;
            }
        }
        
        
        // Xss protection
        foreach ($formData as $field_id => &$item) {
            $type = substr($field_id, 0, strpos($field_id, "_"));
            if ($type != "signature" && $type != "productList" && $type != "multiSelect" && $type != "checkBox") {
                $item = magicform_xss_protection($item);
            }
        }
      
        foreach($formData as $field_id => $field_value){
            $type = substr($field_id, 0, strpos($field_id, "_"));
            
            if($type == "calculateField"){
                $frontTotal = $field_value['value'];
              
                $decimalCount =  $this->allElements[$field_id]->payload->decimalCount;
                $total = magicform_calculate_field($formData,  $this->allElements[$field_id] ,$this->allElements);
                if(number_format($frontTotal,$decimalCount) != number_format($total,$decimalCount)){
                    wp_send_json_error(esc_html__('Error Calculated Field', "magicform"), 403);
                    return false;
                }
            }
            else if ($type == "productList") {
                $frontTotal = floatval($field_value["total"]);
                $values = explode(" ", implode(" ",(array)$field_value));
                $total = magicform_product_list_total($values, $this->allElements[$field_id]);
                
                if ($frontTotal !== $total){
                    wp_send_json_error(esc_html__("Error Product List","magicform"),403);
                    return false;
                }
            }
        }
      
            // Form Sub limit control  
            $subCount = $this->wpdb->get_var($this->wpdb->prepare("SELECT COUNT(id) FROM " . $this->submissions_tablename . " WHERE form_id=%d", intval($formId)));
            if ($formSettings->settings->subLimit != 0) {
                if ($subCount >= $formSettings->settings->subLimit) {
                    wp_send_json_error($formSettings->settings->translate->formLimitMessage, 403);
                    return false;
                }
            }
            if ($formSettings->settings->endSubDate != "") {
                if (strtotime(date("Y-m-d H:i:s")) >= strtotime($formSettings->settings->endSubDate)) {
                    wp_send_json_error($formSettings->settings->translate->formLimitMessage, 403);
                    return false;
                }
            }

            // Actions
            $actions = $formSettings->actions;
            $orderedKeys = array("saveDatabase", "generatePdf", "sendEmail", "autoResponder", "formToPost", "mailChimp", "sendgrid", "getresponse","stripe");
            $orderedActions = array();
            foreach ($orderedKeys as $key) {
                foreach ($actions as $action) {
                    if ($action->type == $key) {
                        $orderedActions[] = $action;
                    }
                }
            }
            $generatedPdfFile = "";
            $actionResults = array();
            $actionPath = MAGICFORM_PATH . '/admin/views/submissions/actions/';
            if (count($orderedActions) > 0) {
                foreach ($orderedActions as $action) {
                    if ($action->active) {
                        switch ($action->type) {
                            case "saveDatabase":
                                // Submission Id will set after savedatabase
                                $submission_id = 0;
                                $actionResults[] = array("saveDatabase" => include(MAGICFORM_PATH . '/admin/views/submissions/actions/SaveDatabase.php'));
                                break;
                            case "generatePdf":
                                $generatedPdfFile = magicform_pdf_generator((array)$formData, $submission_id, $formSettings, $this->allElements, $action, $pageTitle, $pageUrl, $this->submissions_tablename);
                                break;
                            case "sendEmail":
                                $actionResults[] = array("sendEmail" => include(MAGICFORM_PATH . '/admin/views/submissions/actions/SendEmail.php'));
                                break;
                            case "autoResponder":
                                $actionResults[] = array("autoResponder" => include(MAGICFORM_PATH . '/admin/views/submissions/actions/AutoResponder.php'));
                                break;
                            case "mailChimp":
                                $actionResults[] = array("mailChimp" => include(MAGICFORM_PATH . '/admin/views/submissions/actions/MailChimp.php'));
                                break;
                            case "sendgrid":
                                $actionResults[] = array("sendgrid" => include(MAGICFORM_PATH . '/admin/views/submissions/actions/SendGridMailList.php'));
                                break;
                            case "getresponse":
                                $actionResults[] = array("getresponse" => include(MAGICFORM_PATH . '/admin/views/submissions/actions/GetResponse.php'));
                                break;
                            case "formToPost":
                                $actionResults[] = array("formToPost" => include(MAGICFORM_PATH . '/admin/views/submissions/actions/FormToPost.php'));
                                break;
                            case "stripe":
                                $actionResults[] = array("stripe" => include(MAGICFORM_PATH . '/admin/views/submissions/actions/Stripe.php'));
                                break;
                        }
                    }
                }
            }

            if(!empty($stripeSessionId)){
                $result["payment"]=array(
                    "type"=>"stripe",
                    "sessionId"=>$stripeSessionId
                );
            }

        $thankYouPage = $this->getThankYou($formSettings);
        $result["action"] = $thankYouPage->action;
        $result["submission_id"] = $submission_id;
        switch ($thankYouPage->action) {
            case "showThankYou":
                $result["message"] = $thankYouPage->message;
                break;
            case "redirectUrl":
                $result["redirectUrl"] = magicform_variable_parser($thankYouPage->redirectUrl, $formData, $formSettings, 0, $pageTitle, $pageUrl, $this->allElements);
                break;
            case "stayOnForm":
                $result["successMessage"] = $thankYouPage->successMessage;
                break;
        }
        wp_send_json_success($result);
    }

    /**
     * Delete Submissions
     *
     * @return object json object
     */
    function delete_submissions()
    {
        $ids = explode(",", $_POST["ids"]);
        if (is_array($ids) && count($ids) > 0) {
            foreach ($ids as $id) {
                $this->wpdb->delete($this->submissions_tablename, array('id' => intval($id)));
            }
            wp_send_json_success();
        } else {
            wp_send_json_error(esc_html_e("Please select a submission at least", "magicform"));
        }
    }

    /**
     * Verify Recaptcha from backend
     *
     * @param string $responseToken
     * @param string $type
     * @return void
     */
    function verifyRecaptcha($responseToken, $type)
    {
        WP_Filesystem();
        global $wp_filesystem;
        
        $recaptchaKeys = json_decode(get_option('magicform_recaptcha_settings'), true);
        $data = array(
            "secret" => $recaptchaKeys["$type"],
            "response" => $responseToken,
            'remoteip' => $_SERVER['REMOTE_ADDR']
        );
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context  = stream_context_create($options);
        $result = $wp_filesystem->get_contents($url, false, $context);
        return $result->success;
    }

    /**
     * Ger Form submission by id
     *
     * @param int $sub_id
     * 
     * @return object
     */
    function getFormSubmission($sub_id)
    {
        $id = intval($sub_id);
        $result = $this->wpdb->get_row($this->wpdb->prepare("SELECT f.form_name as form_name, f.form_data,s.*
		  FROM " . $this->submissions_tablename . " as s 
		  INNER JOIN " . $this->forms_tablename . " as f ON f.id = s.form_id
		  WHERE s.id =%d", $id));
        return $result;
    }
    

    /**
     * Update React Status
     *
     * @return void
     */
    function updateReadStatus()
    {
        $id = intval($_GET['id']);
        $this->wpdb->update($this->submissions_tablename, array('read_status' => 1), array('id' => $id));
    }

    /**
     * Return mailer type that selected
     *
     * @param string $type
     * 
     * @return string
     */
    function selectMailerType($type)
    {
        switch ($type) {
            case "smtp":
                return "SendEmail.php";
                break;
            case "sendgrid":
                return "SendGrid.php";
                break;
        }
    }

    /**
     * Get thank you page in pages
     *
     * @param object $formSettings
     * @return void
     */
    function getThankYou($formSettings)
    {
        // Thank You
        foreach ($formSettings->pages as $page) {
            if ($page->type == "thankyou") {
                return $page->settings;
            }
        }
        return false;
    }

    /**
     * All elements loop
     *
     * @param string $type
     * @param array $data
     * @return void
     */
    function getElements($type, $data)
    {
        if ($type == "page") {
            foreach ($data as $page) {
                foreach ($page->elements as $item) {
                    $this->allElements[$item->id] = $item;
                    if ($item->type == "group" || $item->type == "grid") {
                        $this->getElements($item->type, $item);
                    }
                }
            }
        }
        if ($type == "group") {
            foreach ($data->payload->elements as $item) {
                $this->allElements[$item->id] = $item;
                if ($item->type == "group" || $item->type == "grid") {
                    $this->getElements($item->type, $item);
                }
            }
        }
        if ($type == "grid") {
            foreach ($data->payload->columns as $column) {
                foreach ($column->elements as $item) {
                    $this->allElements[$item->id] = $item;
                    if ($item->type == "group" || $item->type == "grid") {
                        $this->getElements($item->type, $item);
                    }
                }
            }
        }
    }

    function paypal_purchase_data($submission_id) {

        $detail = $this->getFormSubmission($submission_id);
        $formSettings = json_decode($detail->form_data);
        $submissionData = json_decode($detail->data);
        $actions = $formSettings->actions;
        $action = array();
        $totalAmount = 0;

        foreach($actions as $item){
            if($item->type == "paypal")
                $action = $item;
        }

        foreach ($submissionData as $field_id => $field_value) {
    
            if($field_id == $action->payload->totalAmount){
              if(strpos($field_id, "productList")!==false) {
                  $totalAmount = floatval($field_value->total);
              }else if(strpos($field_id, "calculateField")!==false) {
                  $totalAmount = floatval($field_value->value);
              }else{
                $totalAmount = floatval($field_value);
              }
            }
        }

        $purchase_data = array("totalAmount" => $totalAmount, "currency" => $action->payload->currency);
        return (object) $purchase_data;

    }

    function paypal_authentication() {

        $paymentSettings = json_decode(get_option("magicform_paypal_settings"));

        $request_url = "";
        if($paymentSettings->paymentType === "live"){
            $request_url = "https://api.paypal.com";
        }
        else {
            $request_url = "https://api.sandbox.paypal.com";
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $request_url . "/v1/oauth2/token");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
        curl_setopt($ch, CURLOPT_USERPWD, $paymentSettings->clientId . ':' . $paymentSettings->clientSecret);

        $headers = array();
        $headers[] = 'Accept: application/json';
        $headers[] = 'Accept-Language: en_US';
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $resultAuth = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        return  json_decode($resultAuth);
    }

   

    public function paypal_payment() {
     
        $resultAuth = $this->paypal_authentication();
        $submission_id = $_POST['submission_id'];
        $purchase_data = $this->paypal_purchase_data($submission_id);
        
        $paymentSettings = json_decode(get_option("magicform_paypal_settings"));

        $request_url = "";
        if($paymentSettings->paymentType === "live"){
            $request_url = "https://api.paypal.com";
        }
        else {
            $request_url = "https://api.sandbox.paypal.com";
        }

        $post_data = array(
            "intent" => "CAPTURE",
            "purchase_units" => array(
                array(
                    "amount" => array(
                        "currency_code" => strtoupper($purchase_data->currency),
                        "value" => "".number_format($purchase_data->totalAmount, 2) .""
                    )
                )
            )
        );

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $request_url . '/v2/checkout/orders');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,  json_encode($post_data, JSON_UNESCAPED_SLASHES) );

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer '.$resultAuth->access_token;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        $result = curl_exec($ch);
       
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
    
        wp_send_json_success($result);
    }

    public function paypal_order_capture() {

        $order_id = $_POST['order_id'];
        $resultAuth = $this->paypal_authentication();
        $submission_id = $_POST['submission_id'];

        $paymentSettings = json_decode(get_option("magicform_paypal_settings"));
      
        $request_url = "";
        if($paymentSettings->paymentType === "live"){
            $request_url = "https://api.paypal.com";
        }
        else {
            $request_url = "https://api.sandbox.paypal.com";
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $request_url . '/v2/checkout/orders/'.  $order_id .'/capture');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer '. $resultAuth->access_token;
        $headers[] = 'Paypal-Request-Id: 7b92603e-77ed-4896-8e78-5dea2050476a';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }

        $response = json_decode($result);
        
        curl_close($ch);
        $purchase_data = $this->paypal_purchase_data($submission_id);
        if($response->status === "COMPLETED")
        {
            $this->update_submission_payment($submission_id, 1, $purchase_data->totalAmount);
        }
        else {
            $this->update_submission_payment($submission_id, 0, $purchase_data->totalAmount, $response->status);
        }
        wp_send_json_success($result);  
       
    }

    function update_submission_payment($submission_id, $status, $total_amount, $error = "") {
        $this->wpdb->update($this->submissions_tablename, array(
            'payment_status' => $status,
            'total_amount' => number_format($total_amount,2),
            'payment_error' => $error
        ), array("id" => $submission_id));
    }
}
