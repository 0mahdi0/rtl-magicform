<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @package    MagicForm
 * @subpackage MagicForm/includes
 * @author     MagicLabs
 */
class MagicForm_Public
{

	private $plugin_name;
	private $version;

	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 */
	public function enqueue_styles()
	{
		wp_register_style('font-awesome-5', MAGICFORM_URL . 'assets/css/font-awesome.css', array(), "5.2.0");
		wp_register_style('jquery-datepicker', MAGICFORM_URL . 'assets/css/datepicker.min.css', array(), $this->version);
		wp_register_style('magicform-general', MAGICFORM_URL . 'assets/css/general.css', array(), $this->version);
		wp_register_style('magicform-rtl', MAGICFORM_URL . 'assets/css/assets-rtl.css', array(), $this->version);
		wp_register_style('magicform-theme', MAGICFORM_URL . 'assets/css/theme.css', array(), $this->version);
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 */
	public function enqueue_scripts()
	{
		wp_register_script('signature', MAGICFORM_URL . "assets/js/signature.js", $this->version);
		wp_register_script('jquery-datepicker', MAGICFORM_URL . "assets/js/datepicker.min.js", array("jquery"), $this->version);
		wp_register_script('magicform-main', MAGICFORM_URL . "public/js/magicform-public.js", array("jquery"), $this->version);
		wp_localize_script(
			'magicform-main',
			'magicFormSettings',
			array('ajaxUrl' => admin_url('admin-ajax.php'))
		);
	}
}
