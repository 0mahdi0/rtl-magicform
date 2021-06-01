<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    MagicForm
 * @subpackage MagicForm/admin
 * @author     MagicLabs
 */
use JasonGrimes\Paginator;

class MagicForm_Notifications
{

    // Plugin Name
    private $plugin_name;

    // Plugin Version
    private $version;

    // Table name for forms table
    private $forms_tablename;

    // Table name for submissions table
    private $submissions_tablename;

    private $table = "";
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
    public function __construct($plugin_name, $version, $forms_tablename, $submissions_tablename)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->forms_tablename = $forms_tablename;
        $this->submissions_tablename = $submissions_tablename;
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table = $this->wpdb->prefix."magicform_notifications";

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
        add_submenu_page("magicform_admin", esc_html__("Notifications","magicform"), esc_html__("Notifications","magicform"),  $demo_control !== "demo"?"manage_options":"edit_posts", "magicform_notifications", array($this, "view"));
    }

    /**
     * View
     * 
     * @return String Rendered Html
     */
    public function view()
    {
        return require_once(MAGICFORM_PATH . "/admin/views/notifications/notifications.php");
    }

     /**
     * List all notifications
     */
    function list_notifications($perPage)
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

        $sql = $this->wpdb->prepare("SELECT * FROM " . $this->table . " " . $cond . " " . $order . " " . $limit, $variables);
        $data = $this->wpdb->get_results($sql);
        $countSql = $this->wpdb->prepare("SELECT count(id) as total FROM " . $this->table . " " . $cond, $variables);
        $totalRows = $this->wpdb->get_row($countSql)->total;

        //Pagination
        $paginator = new Paginator($totalRows, $perPage, $page, esc_url("?" . magicform_getUrlParams(array("p"))) . "&p=(:num)");

        return array(
            "data" => $data,
            "totalRows" => $totalRows,
            "paginator" => $paginator
        );
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

}