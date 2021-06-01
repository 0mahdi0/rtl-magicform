<?php
/* 
 * Plugin Name:       Magic Form
 * Plugin URI:        https://magicform3.com
 * Description:       With the Magic Form Form Builder that has lots of features, you can create forms in a fast and easy way.
 * Version:           1.5.6
 * Author:            MagicLabs
 * Author URI:        https://magicform3.com
 * Text Domain:       magicform
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Constants
 */
define('MAGICFORM_VERSION', '1.5.6');
define('MAGICFORM_PATH', dirname(__FILE__));
define('MAGICFORM_URL', plugin_dir_url(  __FILE__ ));

/**
 * Activate Plugin
 *
 * @return void
 */
function magicform_activate()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-magicform-activator.php';
    MagicForm_Activator::activate();
}

/**
 * Deactivate Plugin
 *
 * @return void
 */
function magicform_deactivate()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-magicform-deactivator.php';
    MagicForm_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'magicform_activate');
register_deactivation_hook(__FILE__, 'magicform_deactivate');

/**
 * Composer Vendor AutoLoad
 */
if (file_exists(MAGICFORM_PATH . '/vendor/autoload.php')) {
    require_once MAGICFORM_PATH . '/vendor/autoload.php';
}

/**
 * The core plugin that bootstrap
 */
require plugin_dir_path(__FILE__) . 'includes/class-magicform.php';

/**
 * Start the plugin
 */
function magicform_start()
{
    $plugin = new MagicForm();
    $plugin->run();
}
magicform_start();
