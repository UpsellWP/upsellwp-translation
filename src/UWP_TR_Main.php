<?php
/**
 * UpsellWP: Translation
 *
 * @package   upsellwp-translation
 * @author    Team UpsellWP <team@upsellwp.com>
 * @license   GPL-3.0-or-later
 * @link      https://upsellwp.com
 */

defined('ABSPATH') || exit;

if (class_exists('UWP_TR_Main')) {
    return;
}

class UWP_TR_Main
{
    /**
     * To add translation hooks.
     *
     * @return void
     */
    public static function init()
    {
        if (!method_exists('\CUW\App\Helpers\Plugin', 'isActive')) {
            return;
        }

        $activated_translators = [
            'loco_translator' => \CUW\App\Helpers\Plugin::isActive('loco-translate/loco.php'),
            'wpml' => \CUW\App\Helpers\Plugin::isActive('wpml-string-translation/plugin.php'),
        ];

        if (!empty($activated_translators['loco_translator'])) {
            include UWP_TR_PLUGIN_PATH . 'src/UWP_TR_Loco.php';
            add_filter('uwp_tr_admin_script_data', [UWP_TR_Loco::class, 'addAssetsData']);
            add_action('cuw_translation_addon_actions', [UWP_TR_Loco::class, 'addLocoSyncAndEditButtons']);
            add_action('loco_extracted_template', [UWP_TR_Loco::class, 'addStrings'], 10, 2);
        }

        if (!empty($activated_translators['wpml'])) {
            include UWP_TR_PLUGIN_PATH . 'src/UWP_TR_WPML.php';
            add_action('cuw_translation_addon_actions', [UWP_TR_WPML::class, 'addWPMLSyncButton']);
            add_action('wp_ajax_uwp_tr_add_custom_strings_to_wpml', [UWP_TR_WPML::class, 'addStrings']);
        }

        if (!empty($activated_translators['loco_translator']) || !empty($activated_translators['wpml'])) {
            add_action('admin_enqueue_scripts', [self::class, 'loadAsset']);
        }
    }

    /**
     * To load Assets.
     *
     * @return void
     */
    public static function loadAsset() {
        if (!class_exists('\CUW\App\Helpers\Config')) {
            return;
        }

        $upsellwp_plugin = \CUW\App\Helpers\Config::get('plugin');
        if (!empty($_GET['page']) && !empty($upsellwp_plugin['slug']) && $_GET['page'] == $upsellwp_plugin['slug']) {
            wp_enqueue_script('uwp_tr_admin_script', plugin_dir_url(UWP_TR_PLUGIN_FILE) . 'assets/admin.js', ['jquery'], UWP_TR_PLUGIN_VERSION, ['in_footer' => true]);
            wp_localize_script('uwp_tr_admin_script', 'uwp_tr_admin_script_data', apply_filters('uwp_tr_admin_script_data', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('uwp_tr_nonce'),
                'i18n' => [
                    'synced' => __('Synced', 'upsellwp-translation'),
                    'syncing' => __('Syncing...', 'upsellwp-translation'),
                ],
            ]));
        }
    }
}