<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    MagicForm
 * @subpackage MagicForm/admin
 * @author     MagicLabs
 */

class MagicForm_Forms
{

    // Plugin Name
    private $plugin_name;

    // Plugin Version
    private $version;

    // Table name for forms table
    private $forms_tablename;

    // Table name for submissions table
    private $submissions_tablename;

    // Table name for product table
    private $products_tablename;

    // Global wpdb object
    private $wpdb;

    /**
     * Constructor
     *
     * @param String $plugin_name
     * @param String $version
     * @param String $forms_tablename
     * @param String $submissions_tablename
     */
    public function __construct($plugin_name, $version, $forms_tablename, $submissions_tablename, $products_tablename)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->forms_tablename = $forms_tablename;
        $this->submissions_tablename = $submissions_tablename;
        $this->products_tablename = $products_tablename;

        global $wpdb;
        $this->wpdb = $wpdb;
    }

    /**
     * Set Admin Page
     *
     * @return void
     */
    public function setPages()
    {
        /**
         * Dashboard Page
         * Subpage
         */
        $demo_control = apply_filters("magicform_demo_check_license","notdemo",10,3);
        add_submenu_page("magicform_admin", esc_html__("Forms","magicform"), esc_html__("Forms","magicform"),  $demo_control !== "demo"?"manage_options":"edit_posts", "magicform_forms", array($this, "view"));
    }

    
    /**
     * View
     * 
     * @return String Rendered Html
     */
    public function view()
    {
        $subpage = isset($_GET["subpage"]) ? $_GET["subpage"] : null;
        switch ($subpage) {
            case "create":
                return require_once(MAGICFORM_PATH . "/admin/views/forms/form-create.php");
            default:
                return require_once(MAGICFORM_PATH . "/admin/views/forms/forms.php");
        }
    }

    /**
     * Create form
     *
     * @return object as json object
     */
    function save_form()
    {
        $purchaseCode = get_option("magicform_purchase_code");

        $extended_control = apply_filters("magicform_extended_check_license","notextended",$arg1,$arg2);

        if($purchaseCode == null && $extended_control !== "extended"){
            $countSql = $this->wpdb->prepare("SELECT COUNT(*) AS total FROM " . $this->forms_tablename. " WHERE status = 1" );
            $totalRows = $this->wpdb->get_row($countSql)->total;
            if($totalRows >=2){
                wp_send_json_error(esc_html__("You have to purchase for create limitless forms", "magicform"));
                return false;
            }
        }
       
        // Validation
        if ($_POST["name"] == "" || $_POST['templateId'] == "") {
            wp_send_json_error(esc_html__("Form name is required", "magicform"));
            return false;
        }

        $current_user = wp_get_current_user();


        $this->wpdb->insert($this->forms_tablename, array(
            'form_name' => sanitize_text_field($_POST["name"]),
            'owner_id' => intval($current_user->ID),
            'status' => 1,
            'create_date' => date("Y-m-d H:i:s")
        ));

        $insert_id = $this->wpdb->insert_id;
        $form_data = require_once(MAGICFORM_PATH . "/admin/views/forms/empty-form-data.php");
        $short_code = "[magicform id=" . intval($insert_id) . "]";
        $this->wpdb->update($this->forms_tablename, array(
            'short_code' => $short_code,
            'form_data' => json_encode($form_data),
        ), array("id" => $insert_id));

        wp_send_json_success(array("id" => $insert_id));
    }

    /**
     * Get form data
     */
    function get_form()
    {
        $id = intval($_POST['id']);
        $pluginSettings = $this->get_plugin_settings();
        $result = $this->wpdb->get_row($this->wpdb->prepare("SELECT * FROM $this->forms_tablename WHERE id =%d", $id));
        $json_form_data = json_decode($result->form_data);
        $json_form_data->pluginSettings = json_decode(json_encode($pluginSettings));
        $json_form_data->purchaseCode = json_decode(get_option("magicform_purchase_code"));
        $json_form_data->lang = require_once(MAGICFORM_PATH."/admin/views/forms/client-language.php");
        wp_send_json_success($json_form_data);
    }

    /**
     * Get form data
     */
    function get_plugin_settings()
    {
        return array(
            "emailSettings" => array(
                "mailChimpSettings" => json_decode(get_option("magicform_mailchimp_settings")),
                "emailSystem" => json_decode(get_option("magicform_email_system")),
                "sendgridSettings" => json_decode(get_option("magicform_sendgrid_settings")),
                "mailgunSettings" => json_decode(get_option("magicform_mailgun_settings")),
                "getresponseSettings" => json_decode(get_option("magicform_getresponse_settings")),
                "smtpSettings" => json_decode(get_option("magicform_email_settings")),
            ),
            "payments" => array(
                "paypalSettings" => json_decode(get_option("magicform_paypal_settings")),
                "stripeSettings" => json_decode(get_option("magicform_stripe_settings"))
            ),
            "googleSettings" =>  json_decode(get_option("magicform_google_settings")),
            "recaptchaSettings" => json_decode(get_option("magicform_recaptcha_settings")),
        );
    }

    /**
     * This function base64 images src whitespace replace with "+".
    */

    function img_src_replace($message) {
        $replace_array = array();
        preg_match_all('/(?<=src=")(.*?)(?=">)/', $message, $src_array);
        foreach($src_array[0] as $src)
        {
           $message = str_replace($src, str_replace(" ", "+", $src), $message);
        }
       return $message;
    }

    /**
     * Update form data
     */
    function update_form()
    {

        if (!empty($_POST)) {

            $data = rawurldecode(stripslashes($_POST["data"]));
            $form = json_decode($data);

            $message = $form->pages[count($form->pages) - 1]->settings->message;
           
            $form->pages[count($form->pages) - 1]->settings->message = $this->img_src_replace($message);

            $form_data = "";
            $current_user = wp_get_current_user();
            if (intval($_POST['type']) == 0)
                $form_data = "preview_form_data";
            else
                $form_data = "form_data";

            $this->wpdb->update($this->forms_tablename, array(
                'form_name' => sanitize_text_field($form->settings->name),
                'owner_id' => intval($current_user->ID),
                'status' => intval($form->settings->active),
                $form_data => json_encode($form)
            ), array("id" => intval($_POST["id"])));

            wp_send_json_success(array("formName" => esc_html($form->settings->name)));
        } else
            wp_send_json('error', 404);
    }
    /**
     * Clone Form
     */
    function clone_form()
    {
        if (empty($_POST['form_id']))
            return false;

        $id = intval($_POST['form_id']);
        $result = $this->wpdb->get_row($this->wpdb->prepare("SELECT * FROM " . $this->forms_tablename . " WHERE id =%d", $id));
        $result->form_name .= ' #copy';
        $this->wpdb->insert($this->forms_tablename, array(
            'form_name' => sanitize_text_field($result->form_name),
            'owner_id' => intval($result->owner_id),
            'status' => 1,
            'create_date' => date("Y-m-d H:i:s")
        ));

        $newId =  $this->wpdb->insert_id;
        $jsonData = json_decode($result->form_data);
        $jsonData->settings->name .= " #copy";
        $jsonData->pages = $this->change_form_ids("page", $jsonData->pages, $newId);
        $newFormData =  json_encode($jsonData);
        $short_code = "[magicform id=" . intval($newId) . "]";
        $this->wpdb->update($this->forms_tablename, array(
            'short_code' => $short_code,
            'form_data' => $newFormData,
        ), array("id" =>  $newId));
        wp_send_json_success("ok");
    }

    /**
     * Helper for clone form 
     */
    function change_form_ids($type, $formData, $newId)
    {
        if ($type == "page") {
            foreach ($formData as $page) {
                foreach ($page->elements as $item) {
                    $item->id = preg_replace('/(?<=\_)(\d*)(?=\_)/', $newId, $item->id);
                    if ($item->type == "group" || $item->type == "grid") {
                        $this->change_form_ids($item->type, $item, $newId);
                    }
                }
            }
        }
        if ($type == "group") {
            foreach ($formData->payload->elements as $item) {
                $item->id = preg_replace('/(?<=\_)(\d*)(?=\_)/', $newId, $item->id);
                if ($item->type == "group" || $item->type == "grid") {
                    $this->change_form_ids($item->type, $item, $newId);
                }
            }
        }
        if ($type == "grid") {
            foreach ($formData->payload->columns as $column) {
                foreach ($column->elements as $item) {
                    $item->id = preg_replace('/(?<=\_)(\d*)(?=\_)/', $newId, $item->id);
                    if ($item->type == "group" || $item->type == "grid") {
                        $this->change_form_ids($item->type, $item, $newId);
                    }
                }
            }
        }
        return $formData;
    }

    /**
     * Archive Form
     */
    public function archive_form()
    {
        if (empty($_POST["form_id"])) {
            return false;
        }
        $id = intval($_POST["form_id"]);
        $this->wpdb->update($this->forms_tablename, array(
            'status' => 2
        ), array("id" =>  $id));
        wp_send_json_success("ok");
    }

    /**
     * Delete Form
    */
    public function delete_form() {
      
        if (empty($_POST["form_id"])) {
            return false;
        }

        $form_id = intval($_POST["form_id"]);
        $this->wpdb->delete( $this->forms_tablename, array( 'id' => $form_id), array('%d'));
        $this->wpdb->delete( $this->submissions_tablename, array( 'form_id' => $form_id), array('%d'));
        wp_send_json_success("ok");
    }

    /**
     * Get All Forms
     */
    public function get_forms()
    {
        // Condition
        $cond = "WHERE 1=%d";
        $variables = array(1);
        if (isset($_GET["active"])) {
            $cond .= " AND status=%d";
            $variables[]=intval($_GET["active"]);
        } else {
            $cond .= " AND status=1";
        }

        if (isset($_GET["q"])) {
            $cond .= " AND form_name like '%%%s%%'";
            $variables[]=sanitize_text_field($_GET["q"]);
        }

        // Form Data
        $sql = $this->wpdb->prepare("SELECT id, form_name, short_code, views FROM " . $this->forms_tablename . " " . $cond . " ORDER BY id DESC",$variables);
        $formData = $this->wpdb->get_results($sql, ARRAY_A);

        // Read / Unread submission data
        $submissionSql = "SELECT form_id, read_status, count(id) as total FROM " . $this->submissions_tablename . " GROUP BY form_id, read_status";
        $submissionData = $this->wpdb->get_results($submissionSql, ARRAY_A);
        $submissionStats = array();
        foreach ($submissionData as $s) {
            $submissionStats[$s["form_id"]][$s["read_status"]] = $s["total"];
        }

        // Merge
        foreach ($formData as $key => $form) {
            $formId = $form["id"];
            $formData[$key]["unread"] = 0;
            $formData[$key]["read"] = 0;
            if (array_key_exists($formId, $submissionStats)) {
                $formData[$key]["unread"] = isset($submissionStats[$formId]["0"]) ? $submissionStats[$formId]["0"] : 0;
                $formData[$key]["read"] = isset($submissionStats[$formId]["1"]) ? $submissionStats[$formId]["1"] : 0;
            }
        }
        return $formData;
    }

    /**
     * Preview Form
     *
     * @return string Rendered Html
     */
    public function preview()
    {
        return require_once(MAGICFORM_PATH . "/admin/views/forms/form-preview.php");
    }

    /**
     * Product insert
     * @return string status
    */
    public function insert_product() {
        $product = rawurldecode(stripslashes($_POST['product']));
        $this->wpdb->insert(
            $this->products_tablename,
            array(
                'product_name' => sanitize_text_field($_POST["name"]),
                'product' => $product,
                'status' => 1,
                'create_date' => date("Y-m-d H:i:s")
            )
        );
        $insert_id = $this->wpdb->insert_id;
        wp_send_json_success(array("id" => $insert_id));
    }

    /**
     * Get Product By Id
     * @return object product
    */
    public function get_product_by_id () {
        $sql = $this->wpdb->get_row($this->wpdb->prepare("SELECT * FROM $this->products_tablename WHERE id =%d", intval($_POST['id'])));
        $result = $this->wpdb->get_results($sql);
        wp_send_json_success($result);
    }

    /**
     * List Products 
     * @return array products 
    */

    public function list_product() {
        $sql = $this->wpdb->prepare("SELECT * FROM $this->products_tablename WHERE status = 1");
        $result = $this->wpdb->get_results($sql);
        wp_send_json_success($result);
    }

    /**
     * Update Product
     * @return bool status 
    */
    public function update_product () {
        $product = rawurldecode(stripslashes($_POST['product']));
        $this->wpdb->update($this->products_tablename, 
            array(
                'product_name' => $_POST['name'],
                'product' => $product,
            ), 
            array("id" => intval($_POST['id']))
        );

        wp_send_json_success(true);
    }
}
