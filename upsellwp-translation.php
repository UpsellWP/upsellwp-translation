<?php
/**
 * Plugin Name:          UpsellWP: Dynamic String Translation
 * Plugin URI:           https://upsellwp.com/add-ons/translation
 * Description:          Dynamic string translation addon. Helpful to sync dynamic strings. Supported plugins: WPML and Loco Translate.
 * Version:              1.0.1
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
defined('UWP_TR_PLUGIN_VERSION') || define('UWP_TR_PLUGIN_VERSION', '1.0.1');

// load plugin
add_action('plugins_loaded', function () {
    $requires = [
        'php' => '7.0',
        'wordpress' => '5.3',
        'woocommerce' => '4.4',
        'upsellwp' => '2.1',
    ];
    $addon_name = 'UpsellWP: Dynamic String Translation';
    include UWP_TR_PLUGIN_PATH . 'src/UWP_TR_Helper.php';
    if (class_exists('UWP_TR_Helper') && UWP_TR_Helper::checkDependencies($requires, $addon_name)) {
        include UWP_TR_PLUGIN_PATH . 'src/UWP_TR_Main.php';
        UWP_TR_Main::init();
    }
    $i18n_path = dirname(plugin_basename(__FILE__)) . '/i18n/languages';
    load_plugin_textdomain('upsellwp-translation', false, $i18n_path);
});

// run updater
add_action('admin_init', function () {
    include UWP_TR_PLUGIN_PATH . 'src/UWP_TR_Updater.php';
    if (class_exists('UWP_TR_Updater')) {
        new UWP_TR_Updater(__FILE__, 'upsellwp-translation');
    }
});
