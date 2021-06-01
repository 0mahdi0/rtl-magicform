<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    MagicForm
 * @subpackage MagicForm/admin
 * @author     MagicLabs
 */

class MagicForm_ImportExport
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

    public $array_helper;

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
        $this->array_helper = new MagicForm_ArrayHelper();

    }

    /**
     * Set Admin Pages
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
        add_submenu_page("magicform_admin", esc_html__("Import / Export","magicform"), esc_html__("Import / Export","magicform"), $demo_control !== "demo"?"manage_options":"edit_posts", "magicform_importexport", array($this, "view"));
    }

    /**
     * View
     * 
     * @return String Rendered Html
     */
    public function view()
    {
        return require_once(MAGICFORM_PATH . "/admin/views/importexport/importexport.php");
    }

    /**
     * Get All Forms
     *
     * @return object array
     */
    public function get_forms() {
        return $this->wpdb->get_results("SELECT id, form_name FROM " . $this->forms_tablename ." ORDER BY id DESC", ARRAY_A);
    }

    /**
     * Export form data base64 txt file.
     *
     * @return file  
     */
    public function form_export_base64() {
      
        $form_id = isset($_GET['id']) ? intval($_GET['id']) : null;
    
        if(empty($form_id))
            return false;
        
        $form_data = $this->wpdb->get_var($this->wpdb->prepare("SELECT form_data FROM " . $this->forms_tablename . " WHERE id=%d", $form_id));
        if(empty($form_data))
            return false;
            
        $json_data = json_decode($form_data);
        $file_content =  base64_encode($form_data);
        $file_name = $json_data->settings->name;
        
        header('Content-type: application/x-download');
        header('Content-Disposition: attachment; filename="magicform_export_'.$file_name.'.txt"');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: '.strlen($file_content));
        set_time_limit(0);
        echo ($file_content);
        exit();
    }

    /**
     * Import form data base64 txt file.
     *
     * @return void
     */
    public function form_import_base64() {

        WP_Filesystem();
        global $wp_filesystem;
        
        if (empty($wp_filesystem)) {
            require_once (ABSPATH . '/wp-admin/includes/file.php');
        }

        if($_FILES['upload_file']['type'] != "text/plain"){
            wp_send_json_error(esc_html_e("This file type is not allowed","magicform"));
            return;
        }
        
        if($_POST['name'] == ""){
            wp_send_json_error(esc_html_e("You have to add form name","magicform"));
            return false;
        }

       $form_data = $wp_filesystem->get_contents($_FILES['upload_file']['tmp_name']);
       if(!base64_decode($form_data)){
        wp_send_json_error(esc_html_e("Your data is not valid","magicform"));
        return false;
       }

       $array_form_data =  json_decode(base64_decode($form_data));
       
     
       $this->wpdb->insert($this->forms_tablename, array(
        'form_name' => sanitize_text_field($_POST["name"]),
        'owner_id' => intval($current_user->ID),
        'status' => 1,
        'create_date' => date("Y-m-d H:i:s")
        ));

        $insert_id = $this->wpdb->insert_id;
        $short_code = "[magicform id=" . $insert_id . "]";

        $array_form_data->pages = $this->array_helper->form_set_id("page", $array_form_data->pages, $insert_id);
        $array_form_data->settings->rules = $this->array_helper->rules_set_id($array_form_data->settings->rules, $insert_id);
        $array_form_data->settings->name = sanitize_text_field($_POST["name"]);
        
        $this->wpdb->update($this->forms_tablename, array(
            'short_code' => $short_code,
            'form_data' => json_encode($array_form_data),
        ), array("id" => $insert_id));
        
        wp_send_json_success(array("id" => $insert_id));
    }
}