<?php
defined('ABSPATH') || exit;

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

        if (\CUW\App\Helpers\Plugin::isActive('loco-translate/loco.php')) {
            include UWP_TR_PLUGIN_PATH . 'src/UWP_TR_Loco.php';
            add_action('loco_extracted_template', [UWP_TR_Loco::class, 'addStrings'], 10, 2);
        }

        if (\CUW\App\Helpers\Plugin::isActive('wpml-string-translation/plugin.php')) {
            include UWP_TR_PLUGIN_PATH . 'src/UWP_TR_WPML.php';
            add_action('cuw_translation_addon_actions', [UWP_TR_WPML::class, 'addWPMLSyncButton']);
            add_action('admin_enqueue_scripts', [UWP_TR_WPML::class, 'loadAssets']);
            add_action('wp_ajax_uwp_tr_add_custom_strings_to_wpml', [UWP_TR_WPML::class, 'addStrings']);
        }
    }
}