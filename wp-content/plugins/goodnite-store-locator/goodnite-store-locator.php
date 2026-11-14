<?php
/**
 * Plugin Name: Goodnite Store Locator
 * Plugin URI: https://goodnite.com/
 * Description: Premium store & dealer locator with CSV upload, fast pincode matching, smart regional fallback, and REST API.
 * Version: 1.0.0
 * Author: Goodnite Team
 * Author URI: https://goodnite.com/
 * Text Domain: goodnite-store-locator
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
	exit;
}

define('GOODNITE_SL_VERSION', '1.0.0');
define('GOODNITE_SL_PATH', plugin_dir_path(__FILE__));
define('GOODNITE_SL_URL', plugin_dir_url(__FILE__));
define('GOODNITE_SL_DB_VERSION', '1.0');

require_once GOODNITE_SL_PATH . 'includes/class-db.php';
require_once GOODNITE_SL_PATH . 'includes/class-admin.php';
require_once GOODNITE_SL_PATH . 'includes/class-api.php';
require_once GOODNITE_SL_PATH . 'includes/class-frontend.php';

class Goodnite_Store_Locator
{
	private static $instance = null;

	public static function get_instance()
	{
		if (null === self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct()
	{
		Goodnite_SL_DB::init();

		if (is_admin()) {
			Goodnite_SL_Admin::init();
		}

		Goodnite_SL_API::init();
		Goodnite_SL_Frontend::init();
	}
}

register_activation_hook(__FILE__, array('Goodnite_SL_DB', 'create_tables'));

add_action('plugins_loaded', array('Goodnite_Store_Locator', 'get_instance'));
