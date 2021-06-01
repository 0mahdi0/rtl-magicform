<?php

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @package    MagicForm
 * @subpackage MagicForm/includes
 * @author     MagicLabs
 */
class MagicForm
{

	/**
	 * Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 **/
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 */
	public function __construct()
	{
		if (defined('MAGICFORM_VERSION')) {
			$this->version = MAGICFORM_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'magicform';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - MagicForm_Loader. Orchestrates the hooks of the plugin.
	 * - MagicForm_i18n. Defines internationalization functionality.
	 * - MagicForm_Admin. Defines all hooks for the admin area.
	 * - MagicForm_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 */
	private function load_dependencies()
	{

		/**
		 * Magicform Helper
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/helper-magicform.php';
		
		/**
		 * Email Class
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-magicform-email.php';

		/**
		 * Validation Class
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-magicform-validation.php';
		/**
		 * Array Helper Class
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-magicform-arrayhelper.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-magicform-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-magicform-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-magicform-admin.php';

		/**
		 * Dashboard Module
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-magicform-dashboard.php';

		/**
		 * Forms Module
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-magicform-forms.php';

		/**
		 * Submission Module
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-magicform-submissions.php';

		/**
		 * Settings Module
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-magicform-settings.php';

		/**
		 * Templates Module
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-magicform-templates.php';

		/**
		 * ImportExport Module
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-magicform-importexport.php';
		
		/**
		 * Notifications Module
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-magicform-notifications.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-magicform-public.php';
		/**
		 * Form Viewer Class
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-magicform-viewer.php';

		$this->loader = new MagicForm_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the MagicForm_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 */
	private function set_locale()
	{

		$plugin_i18n = new MagicForm_i18n();

		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 */
	private function define_admin_hooks()
	{

		$admin_module = new MagicForm_Admin($this->get_plugin_name(), $this->get_version());
		$this->loader->add_action('admin_enqueue_scripts', $admin_module, 'enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $admin_module, 'enqueue_scripts');

		$dashboard_module = new MagicForm_Dashboard($this->get_plugin_name(), $this->get_version(), $admin_module->get_forms_tablename(), $admin_module->get_submission_tablename());
		$this->loader->add_action('admin_menu', $dashboard_module, 'setPages');
		$this->loader->add_action('admin_bar_menu', $dashboard_module, 'custom_toolbar_link',999);
		$this->loader->add_action('wp_ajax_magicform_verify', $dashboard_module, 'verify');
		$this->loader->add_action('wp_ajax_magicform_check_license', $dashboard_module, 'check_license');
		$this->loader->add_action('wp_ajax_magicform_deactivate', $dashboard_module, 'deactivate');
	
		$this->loader->add_action('wp_ajax_magicform_send_feedback', $dashboard_module, 'save_feedback');

		$forms_module = new MagicForm_Forms($this->get_plugin_name(), $this->get_version(), $admin_module->get_forms_tablename(), $admin_module->get_submission_tablename(), $admin_module->get_products_tablename());
		$this->loader->add_action('admin_menu', $forms_module, 'setPages');
		$this->loader->add_action('wp_ajax_magicform_save_form', $forms_module, 'save_form');
		$this->loader->add_action('wp_ajax_magicform_clone_form', $forms_module, 'clone_form');
		$this->loader->add_action('wp_ajax_magicform_archive_form', $forms_module, 'archive_form');
		$this->loader->add_action('wp_ajax_magicform_delete_form', $forms_module, 'delete_form');
		$this->loader->add_action('wp_ajax_magicform_get_form', $forms_module, 'get_form');
		$this->loader->add_action('wp_ajax_magicform_get_lang', $forms_module, 'get_lang');
		$this->loader->add_action('wp_ajax_magicform_update_form', $forms_module, 'update_form');
		$this->loader->add_action('wp_ajax_magicform_insert_product', $forms_module, 'insert_product');
		$this->loader->add_action('wp_ajax_magicform_get_product_by_id', $forms_module, 'get_product_by_id');
		$this->loader->add_action('wp_ajax_magicform_list_product', $forms_module, 'list_product');
		$this->loader->add_action('wp_ajax_magicform_update_product', $forms_module, 'update_product');
		$this->loader->add_action('wp_ajax_magicform_delete_product', $forms_module, 'delete_product');
		$this->loader->add_action('admin_action_magicform_preview', $forms_module, 'preview');

		$submissions_module = new MagicForm_Submissions($this->get_plugin_name(), $this->get_version(), $admin_module->get_forms_tablename(), $admin_module->get_submission_tablename());
		$this->loader->add_action('admin_menu', $submissions_module, 'setPages');
		$this->loader->add_action('wp_ajax_magicform_save_submission',  $submissions_module, 'save_submission');
		$this->loader->add_action('wp_ajax_nopriv_magicform_save_submission', $submissions_module, 'save_submission');
		$this->loader->add_action('wp_ajax_nopriv_magicform_paypal_payment', $submissions_module, 'paypal_payment');
		$this->loader->add_action('wp_ajax_magicform_paypal_payment', $submissions_module, 'paypal_payment');
		$this->loader->add_action('wp_ajax_nopriv_magicform_paypal_order_capture', $submissions_module, 'paypal_order_capture');
		$this->loader->add_action('wp_ajax_magicform_paypal_order_capture', $submissions_module, 'paypal_order_capture');
		$this->loader->add_action('wp_ajax_magicform_delete_submissions',  $submissions_module, 'delete_submissions');
		$this->loader->add_action('admin_action_magicform_print', $submissions_module, 'print_submission');
		$this->loader->add_action('admin_action_magicform_print_submissions', $submissions_module, 'print_submissions');
		

		$templates_module = new MagicForm_Templates($this->get_plugin_name(), $this->get_version(), $admin_module->get_forms_tablename(), $admin_module->get_submission_tablename());
		$this->loader->add_action('admin_menu', $templates_module, 'setPages');
		$this->loader->add_action('wp_ajax_magicform_import_form', $templates_module, 'import_form');

		$import_export = new MagicForm_ImportExport($this->get_plugin_name(), $this->get_version(), $admin_module->get_forms_tablename(), $admin_module->get_submission_tablename());
		$this->loader->add_action('admin_menu', $import_export, 'setPages');
		$this->loader->add_action("admin_action_magicform_export", $import_export, 'form_export_base64');
		$this->loader->add_action('wp_ajax_magicform_form_import',  $import_export, 'form_import_base64');

		/*$notifications = new MagicForm_Notifications($this->get_plugin_name(), $this->get_version(), $admin_module->get_forms_tablename(), $admin_module->get_submission_tablename());
		$this->loader->add_action('admin_menu', $notifications, 'setPages');*/

		$settings_module = new MagicForm_Settings($this->get_plugin_name(), $this->get_version(), $admin_module->get_forms_tablename(), $admin_module->get_submission_tablename());
		$this->loader->add_action('admin_menu', $settings_module, 'setPages');
		$this->loader->add_action('wp_ajax_magicform_save_email_settings',  $settings_module, 'save_email_settings');
		$this->loader->add_action('wp_ajax_magicform_test_email_settings',  $settings_module, 'test_email_settings');
		$this->loader->add_action('wp_ajax_magicform_save_google_settings',  $settings_module, 'save_google_settings');
		$this->loader->add_action('wp_ajax_magicform_save_recaptcha_settings',  $settings_module, 'save_recaptcha_settings');
		$this->loader->add_action('wp_ajax_magicform_save_sendgrid_settings',  $settings_module, 'save_sendgrid_settings');
		$this->loader->add_action('wp_ajax_magicform_save_getresponse_settings',  $settings_module, 'save_getresponse_settings');
		$this->loader->add_action('wp_ajax_magicform_get_table_name',  $settings_module, 'get_table_name');
		$this->loader->add_action('wp_ajax_magicform_save_sendgrid_api_key', $settings_module, 'save_sendgrid_api_key');
		$this->loader->add_action('wp_ajax_magicform_save_mailchimp_settings', $settings_module, 'save_mailcimp_settings');
		$this->loader->add_action('wp_ajax_magicform_get_mailchimp_list', $settings_module, 'get_mailchimp_list');
		$this->loader->add_action('wp_ajax_magicform_get_sendgrid_list', $settings_module, 'get_sendgrid_list');
		$this->loader->add_action('wp_ajax_magicform_get_getresponse_list', $settings_module, 'get_getresponse_list');
		$this->loader->add_action('wp_ajax_magicform_get_googlemap_settings', $settings_module, 'get_googlemap_settings');
		$this->loader->add_action('wp_ajax_magicform_get_recaptcha_settings', $settings_module, 'get_recaptcha_settings');
		$this->loader->add_action('wp_ajax_magicform_post_constants', $settings_module, 'magicform_post_constants');
		$this->loader->add_action('wp_ajax_magicform_smtp_test', $settings_module, 'magicform_smtp_test');
		$this->loader->add_action('wp_ajax_magicform_save_mailgun_settings', $settings_module, 'save_mailgun_settings');
		$this->loader->add_action('wp_ajax_magicform_save_stripe_api_key', $settings_module, 'save_stripe_api_key');
		$this->loader->add_action('wp_ajax_magicform_save_paypal_api_key', $settings_module, 'save_paypal_api_key');
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 */
	private function define_public_hooks()
	{

		$plugin_public = new MagicForm_Public($this->get_plugin_name(), $this->get_version());
		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');

		$viewer_module = new MagicForm_Viewer($this->get_plugin_name(), $this->get_version());
		add_shortcode('magicform',  array($viewer_module, 'shortcode'));
		add_action( 'wp_head', array($viewer_module, 'register_fonts' ));
	}

	

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 */
	public function run()
	{
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 */
	public function get_plugin_name()
	{
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 */
	public function get_loader()
	{
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 */
	public function get_version()
	{
		return $this->version;
	}
}
