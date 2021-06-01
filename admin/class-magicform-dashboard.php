<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    MagicForm
 * @subpackage MagicForm/admin
 * @author     MagicLabs
 */

class MagicForm_Dashboard
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

    private $magicFormServer;

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
        $this->magicFormServer  = "https://us-central1-magic-form-6f51a.cloudfunctions.net";
    }

    /**
     * Set pages for main menu and dashboard sub page
     *
     * @return void
     */
    public function setPages()
    {
        $demo_control = apply_filters("magicform_demo_check_license","notdemo",10,3);
    
        $submission_count = $this->new_submissions();
        $title = $submission_count > 0 ? sprintf('Magic Form <span class="awaiting-mod">%d</span>', intval($submission_count)) : 'Magic Form';

        $page_title = "Magic Form";
        $slug       = "magicform_admin";
        $capability = $demo_control != "demo"?"manage_options":"edit_posts";
        $icon       = MAGICFORM_URL . "assets/images/logo_light_square.svg";
        $position   = 42;
        $callback   = null;

        /**
         * Main Menu
         */
        add_menu_page($page_title, $title, $capability, $slug, $callback, $icon, $position);

        /**
         * Dashboard Page
         * Subpage
         */
        add_submenu_page($slug, esc_html__("Dashboard","magicform"), esc_html__("Dashboard","magicform"), $capability, $slug, array($this, "view"));
    }

    /**
     * ToolBar Link
     *
     * @param [type] $wp_admin_bar
     * @return void
     */
    function custom_toolbar_link($wp_admin_bar) {
        $args = array(
            'id' => 'wpbeginner',
            'title' => esc_html__("Unread Submissions","magicform").": ".intval($this->new_submissions()), 
            'href' => esc_url(admin_url()."admin.php?page=magicform_submissions&read_status=0"), 
        );
        $wp_admin_bar->add_node($args);
    }

    /**
     *  View
     *
     * @return String Rendered Html
     */
    public function view()
    {
        if (isset($_GET['subpage']) && $_GET['subpage'] == "feedback") {
            return require_once(MAGICFORM_PATH . "/admin/views/dashboard/feedback.php");
        }
        return require_once(MAGICFORM_PATH . "/admin/views/dashboard/dashboard.php");
    }

    /**
     * Get Total forms count
     *
     * @return int Total forms count
     */
    public function total_forms()
    {
        return $this->wpdb->get_var("SELECT count(id) FROM " . $this->forms_tablename);
    }

    /**
     * Get Total submissions count
     *
     * @return int
     */
    public function total_submissions()
    {
        return $this->wpdb->get_var("SELECT count(id) FROM " . $this->submissions_tablename);
    }

    /**
     * Get new submissions count
     *
     * @return int
     */
    public function new_submissions()
    {
        return $this->wpdb->get_var("SELECT count(read_status) FROM " . $this->submissions_tablename . " WHERE read_status=0");
    }

    /**
     * Get Latest 10 submissions
     *
     * @return object
     */
    public function latest_submissions()
    {
        $sql = "SELECT f.form_name as form_name ,s.id, f.form_data, s.data, s.create_date, s.read_status
		FROM " . $this->submissions_tablename . " as s
		INNER JOIN " . $this->forms_tablename . " as f ON f.id = s.form_id ORDER BY s.create_date DESC LIMIT 10";

        return $this->wpdb->get_results($sql);
    }

    /**
     * Get Deactivated forms count
     *
     * @return int
     */
    public function passive_forms_count()
    {
        return $this->wpdb->get_var("SELECT count(id) FROM " . $this->forms_tablename . " WHERE `status` = 0");
    }

    /**
     * License Check
     * @return object as json
    */

    public function check_license() {

        $purchaseData = json_decode(get_option("magicform_purchase_code"));

        $extended_control = apply_filters("magicform_extended_check_license","notextended",$arg1,$arg2);

        if($extended_control === "extended"){
            $purchaseData = array(
                "code"=>"extended",
                "domain" => get_site_url()
            );
            wp_send_json_success($purchaseData);
            return;
        }
           
        if (empty($purchaseData)) {
            wp_send_json_error(esc_html__("License is free", "magicform"));
            return;
        }
        
        $postData =  "domain=" . sanitize_text_field($purchaseData->domain) . "&code=" . sanitize_text_field($purchaseData->code);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->magicFormServer . "/verifyLicense");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        $output = curl_exec($ch);

        if ($output) {
            $data = json_decode($output);
            if ($data->success) {
                wp_send_json_success($purchaseData);
            } else {
                delete_option("magicform_purchase_code");
                wp_send_json_error($data->msg);
            }
        } else {
            wp_send_json_error(esc_html__("An error occurred when reach license server!", "magicform"));
        }
        curl_close($ch);
    }

    /**
     * License Verify
     *
     * @return object as json
     */
    public function verify()
    {

        if (!isset($_POST["code"]) || $_POST["code"] == "") {
            wp_send_json_error(esc_html__("License code is required", "magicform"));
            return false;
        }
        if (!isset($_POST["domain"]) || $_POST["domain"] == "") {
            wp_send_json_error(esc_html__("Domain is required", "magicform"));
            return false;
        }

        $postData = "domain=" . sanitize_text_field($_POST["domain"]) . "&code=" . sanitize_text_field($_POST["code"]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->magicFormServer . "/verifyLicense");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

        $output = curl_exec($ch);
       
        if ($output) {
            $data = json_decode($output);
            if ($data->success) {
                update_option("magicform_purchase_code", json_encode(array("code" => trim($_POST["code"]), "domain" => $_POST["domain"])));
                wp_send_json_success($data->msg);
            } else {
                wp_send_json_error($data->msg);
            }
        } else {
            wp_send_json_error(esc_html__("An error occurred when reach license server!", "magicform"));
        }
        curl_close($ch);
    }

    /**
     * License deactivate
     */
    function deactivate() {
        
        $license = json_decode(get_option("magicform_purchase_code"));
 
        $postData = "domain=" . sanitize_text_field($license->domain) . "&code=" . sanitize_text_field($license->code);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->magicFormServer . "/deactivateLicense");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

        $output = curl_exec($ch);

        if ($output) {
            $data = json_decode($output);
            if ($data->success) {
                delete_option("magicform_purchase_code");
                wp_send_json_success($data->msg);
            } else {
                wp_send_json_error($data->msg);
            }
        } else {
            wp_send_json_error(esc_html__("An error occurred when reach license server!", "magicform"));
        }
        curl_close($ch);
    }

    /**
     * Save feedback
     *
     * @return object as json
     */
    function save_feedback()
    {
        if (!isset($_POST["message"]) || $_POST["message"] == "") {
            wp_send_json_error(esc_html__("Message is required", "magicform"));
            return false;
        }

        $postData = "email=" . sanitize_text_field($_POST["email"]) . "&message=" . sanitize_text_field($_POST["message"]) . "&rating=" . intval($_POST['rating']);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->magicFormServer . "/feedback");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

        $output = curl_exec($ch);

        if ($output) {
            $data = json_decode($output);
            if ($data->success) {
                wp_send_json_success(esc_html__("Thank you for your feedback.", "magicform"));
            } else {
                wp_send_json_error($data->msg);
            }
        } else {
            wp_send_json_error(esc_html__("An error occurred when reach feedback server!", "magicform"));
        }
        curl_close($ch);
    }
}
