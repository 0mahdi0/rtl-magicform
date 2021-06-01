<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    MagicForm
 * @subpackage MagicForm/admin
 * @author     MagicLabs
 */

class MagicForm_Admin
{
	// Plugin name
	private $plugin_name;

	// Plugin version
	private $version;

	/**
	 * Constructor
	 *
	 * @param String $plugin_name Plugin Name
	 * @param String $version Plugin Version
	 */
	public function __construct($plugin_name, $version)
	{
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @return void
	 */
	public function enqueue_styles()
	{
		/**
		 * Include only in our plugin
		 */
		if (isset($_GET["page"]) && (strpos($_GET['page'], 'magicform') !== false)) {
			wp_enqueue_style('roboto', '//fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap&subset=latin-ext');
			wp_enqueue_style('font-awesome-free', MAGICFORM_URL . 'assets/css/font-awesome.css', array(), $this->version);
			wp_dequeue_style('bootstrap');
			wp_enqueue_style('bootstrap', 		MAGICFORM_URL . 'admin/css/bootstrap.min.css', array(), "4.4.1");
			wp_enqueue_style('magicform-general', 	MAGICFORM_URL . 'assets/css/general.css', array(), $this->version);
			wp_enqueue_style('magicform-theme', 	MAGICFORM_URL . 'assets/css/theme.css', array(), $this->version);
			wp_enqueue_style('magicform-admin-css', 		MAGICFORM_URL . 'client/src/App.css', array(), $this->version);
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @return void
	 */
	public function enqueue_scripts()
	{

		/**
		 * Include only in our plugin
		 */
		if (isset($_GET["page"]) && (strpos($_GET['page'], 'magicform') !== false)) {
			$lang = require_once(MAGICFORM_PATH . "/admin/views/forms/client-language.php");

			wp_enqueue_script('jquery');
			wp_enqueue_script('popper', MAGICFORM_URL . "admin/js/popper.min.js", array(), "1.2");
			wp_deregister_script('bootstrap');
			wp_dequeue_script('bootstrap');
			wp_enqueue_script('bootstrap-4.4.1', MAGICFORM_URL . "admin/js/bootstrap.min.js", array(), "4.4.1", true);
			wp_enqueue_script($this->plugin_name, MAGICFORM_URL . 'admin/js/magicform-admin.js', array('jquery'), $this->version, 9999);
			wp_localize_script($this->plugin_name, 'magicFormSettings', array('ajaxUrl' => admin_url('admin-ajax.php'), "lang" => $lang));

			wp_enqueue_media();

			$src = $this->enqueueStyleAndScript();
			
			foreach ($src['styles'] as $style) {
				if (strpos($style, "https") !== 0) {
					wp_enqueue_style($this->plugin_name . "-" . $style, MAGICFORM_URL . 'client/dist/static/css/' . $style, array(), $this->version);
				}
			}
			
			if (isset($_GET["subpage"]) && $_GET['subpage'] === "create") {
				foreach ($src['scripts'] as $script) {
					wp_enqueue_script($this->plugin_name . '-' . $script, MAGICFORM_URL . 'client/dist/static/js/' . $script, null, $this->version, true);
				}
			}
			//wp_add_inline_script($this->plugin_name . '-' . $script, '!function (l) { function e(e) { for (var r, t, n = e[0], o = e[1], u = e[2], f = 0, i = []; f < n.length; f++)t = n[f], p[t] && i.push(p[t][0]), p[t] = 0; for (r in o) Object.prototype.hasOwnProperty.call(o, r) && (l[r] = o[r]); for (s && s(e); i.length;)i.shift()(); return c.push.apply(c, u || []), a() } function a() { for (var e, r = 0; r < c.length; r++) { for (var t = c[r], n = !0, o = 1; o < t.length; o++) { var u = t[o]; 0 !== p[u] && (n = !1) } n && (c.splice(r--, 1), e = f(f.s = t[0])) } return e } var t = {}, p = { 1: 0 }, c = []; function f(e) { if (t[e]) return t[e].exports; var r = t[e] = { i: e, l: !1, exports: {} }; return l[e].call(r.exports, r, r.exports, f), r.l = !0, r.exports } f.m = l, f.c = t, f.d = function (e, r, t) { f.o(e, r) || Object.defineProperty(e, r, { enumerable: !0, get: t }) }, f.r = function (e) { "undefined" != typeof Symbol && Symbol.toStringTag && Object.defineProperty(e, Symbol.toStringTag, { value: "Module" }), Object.defineProperty(e, "__esModule", { value: !0 }) }, f.t = function (r, e) { if (1 & e && (r = f(r)), 8 & e) return r; if (4 & e && "object" == typeof r && r && r.__esModule) return r; var t = Object.create(null); if (f.r(t), Object.defineProperty(t, "default", { enumerable: !0, value: r }), 2 & e && "string" != typeof r) for (var n in r) f.d(t, n, function (e) { return r[e] }.bind(null, n)); return t }, f.n = function (e) { var r = e && e.__esModule ? function () { return e.default } : function () { return e }; return f.d(r, "a", r), r }, f.o = function (e, r) { return Object.prototype.hasOwnProperty.call(e, r) }, f.p = "/"; var r = window.webpackJsonp = window.webpackJsonp || [], n = r.push.bind(r); r.push = e, r = r.slice(); for (var o = 0; o < r.length; o++)e(r[o]); var s = n; a() }([])', true);
		}
	}

	/**
	 * Client extract js and css
	 *
	 * @return void
	 */
	function enqueueStyleAndScript()
	{
		$styles = array();
		$scripts = array();
		
		$dir    = '/client/dist/static/css';
		if(file_exists(MAGICFORM_PATH.$dir)){

			$files = scandir(MAGICFORM_PATH . $dir);
			foreach ($files as $file) {
				if ($file != "." && $file != "..") {
					$parts = explode(".", $file);
					$extension = end($parts);
					if ($extension == "css") {
						$styles[]=$file;
					}
				}
			}
		}

		$dir    = '/client/dist/static/js';
		$files = scandir(MAGICFORM_PATH . $dir);
		foreach ($files as $file) {
			if ($file != "." && $file != "..") {
				$parts = explode(".", $file);
				$extension = end($parts);
				if ($extension == "js") {
					$scripts[]=$file;
				}
			}
		}

		return array(
			"styles" => $styles,
			"scripts" => $scripts
		);
	}

	/**
	 * Table name that includes forms with WordPress prefix
	 *
	 * @return String Magic Form table name for forms
	 */
	public function get_forms_tablename()
	{
		global $wpdb;
		return   $wpdb->prefix . "magicform_forms";
	}

	/**
	 * Table name that includes submissions with WordPress prefix
	 *
	 * @return String Form table name for submissions
	 */
	public function get_submission_tablename()
	{
		global $wpdb;
		return  $wpdb->prefix . "magicform_submissions";
	}

	/**
	 * Table name that includes prefix with WordPress prefix
	 * @return String Form table name for products
	*/
	public function get_products_tablename() 
	{
		global $wpdb;
		return $wpdb->prefix . "magicform_products";
	}
}
