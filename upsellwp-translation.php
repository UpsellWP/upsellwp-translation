<?php
/**
 * Plugin Name:          UpsellWP: Dynamic String Translation
 * Plugin URI:           https://upsellwp.com/add-ons/translation
 * Description:          Dynamic string translation addon.
 * Version:              1.0.0
 * Requires at least:    5.3
 * Requires PHP:         7.0
 * Author:               UpsellWP
 * Author URI:           https://upsellwp.com
 * Text Domain:          upsellwp-translation
 * Domain Path:          /i18n/languages
 * License:              GPL v3 or later
 * License URI:          https://www.gnu.org/licenses/gpl-3.0.html
 */

defined('ABSPATH') || die;

// define basic plugin constants
defined('UWP_TR_PLUGIN_FILE') || define('UWP_TR_PLUGIN_FILE', __FILE__);
defined('UWP_TR_PLUGIN_PATH') || define('UWP_TR_PLUGIN_PATH', plugin_dir_path(__FILE__));
defined('UWP_TR_PLUGIN_NAME') || define('UWP_TR_PLUGIN_NAME', 'UpsellWP Dynamic String Translation');
defined('UWP_TR_PLUGIN_SLUG') || define('UWP_TR_PLUGIN_SLUG', 'upsellwp-translation');
defined('UWP_TR_PLUGIN_VERSION') || define('UWP_TR_PLUGIN_VERSION', '1.0.0');

add_action('plugins_loaded', function () {
    if (class_exists('\CUW\App\Core')) {
        include UWP_TR_PLUGIN_PATH . 'src/UWP_TR_Main.php';
        UWP_TR_Main::init();
    }
}, 10);
