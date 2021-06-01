<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    MagicForm
 * @subpackage MagicForm/admin
 * @author     MagicLabs
 */

class MagicForm_Templates
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

    public function setPages()
    {
        /**
         * Dashboard Page
         * Subpage
         */
        $demo_control = apply_filters("magicform_demo_check_license","notdemo",10,3);
        add_submenu_page("magicform_admin", esc_html__("Templates","magicform"), esc_html__("Templates","magicform"), $demo_control !== "demo"?"manage_options":"edit_posts", "magicform_templates", array($this, "view"));
    }

    /**
     * View
     */
    public function view()
    {
        return require_once(MAGICFORM_PATH . "/admin/views/templates/templates.php");
    }

    /**
     * Import Form
     */

    public function import_form()
    {
        if ($_POST["name"] == "" && $_POST['templateId'] == "") {
            wp_send_json(esc_html_e("Form name is required", "magicform"), 404);
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
        $form_data = $this->get_template($_POST["templateId"], $insert_id, $_POST["name"]);
        $short_code = "[magicform id=" . $insert_id . "]";
        $this->wpdb->update($this->forms_tablename, array(
            'short_code' => $short_code,
            'form_data' => json_encode($form_data),
        ), array("id" => $insert_id));

        wp_send_json_success(array("id" => $insert_id));
    }

    /**
     * Get Template Lists
     */
    public function get_templates()
    {
        WP_Filesystem();
        global $wp_filesystem;

        $dir = new DirectoryIterator(MAGICFORM_PATH . "/admin/views/templates/form-templates");
        $categoriesJson = $wp_filesystem->get_contents(MAGICFORM_PATH . "/admin/views/templates/form-templates/categories.json");
        $categories = json_decode($categoriesJson);
        $fileNames = array();
        foreach ($dir as $fileinfo) {
            array_push($fileNames, $fileinfo->getFilename());
        }
        natcasesort($fileNames);

        $category = isset($_GET["category"]) ? sanitize_text_field($_GET['category']) : null;
        $templateObject = (object) array("categories" => $categories->categories, 'templates' => array());

        foreach ($fileNames as $name) {
            if ($name !== "." && $name !== ".." && $name !== "categories.json" && $name !== "index.php") {
                $templateJson = $wp_filesystem->get_contents(MAGICFORM_PATH . "/admin/views/templates/form-templates/" . $name);
                $template = json_decode($templateJson);
                if (($category !== null && $template->category == $category) || $category === null) {
                    array_push($templateObject->templates, (object) array("name" => $template->content->settings->name, "id" => $template->id, "desription" => $template->description));
                }
            }
        }
        return $templateObject;
    }

    /**
     * Get Template Data
     */
    function get_template($id, $insert_id, $form_name)
    {
        WP_Filesystem();
        global $wp_filesystem;

        $dir = new DirectoryIterator(MAGICFORM_PATH . "/admin/views/templates/form-templates");
        $content = array();

        foreach ($dir as $fileinfo) {
            $templateJson = $wp_filesystem->get_contents(MAGICFORM_PATH . "/admin/views/templates/form-templates/" . $fileinfo->getFilename());
            $template = json_decode($templateJson);
            if ($template->id == $id)
                $content = $template->content;
        }

        $content->settings->name = $form_name;
        $content->settings->rules = $this->array_helper->rules_set_id($content->settings->rules, $insert_id);
        $content->pages = $this->array_helper->form_set_id("page", $content->pages, $insert_id);
        return $content;
    }
}
