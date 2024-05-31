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

if (class_exists('UWP_TR_WPML')) {
    return;
}

class UWP_TR_WPML
{
    /**
     * To add the dynamic strings to WPML.
     */
    public static function addStrings()
    {
        $response = [];
        if (!has_action('wpml_register_single_string')) {
            $response['message'] = __('WPML translation action not found', 'upsellwp-translation');
            wp_send_json_error($response);
        }

        $nonce = sanitize_text_field($_REQUEST['nonce'] ?? '');
        if (!wp_verify_nonce($nonce, 'uwp_tr_nonce')) {
            $response['message'] = __('Security check failed', 'upsellwp-translation');
            wp_send_json_error($response);
        }

        include UWP_TR_PLUGIN_PATH . 'src/UWP_TR_Strings.php';
        $strings = UWP_TR_Strings::getStrings();
        if (!empty($strings)) {
            foreach ($strings as $string) {
                do_action('wpml_register_single_string', 'checkout-upsell-woocommerce', md5($string), $string);
            }
        }

        $response['message'] = __('Synced successfully', 'upsellwp-translation');
        wp_send_json_success($response);
    }

    /**
     * To add sync button in UpsellWP Translation Plugin addon tab.
     *
     * @return void
     */
    public static function addWPMLSyncButton()
    {
        echo '<button type="button" id="uwp-tr-wpml" class="btn btn-primary">' . esc_html__('Sync', 'upsellwp-translation') . '</button>';
    }

    /**
     * To load assets.
     *
     * @return void
     */
    public static function loadAssets()
    {
        if (!class_exists('\CUW\App\Helpers\Config')) {
            return;
        }

        $upsellwp_plugin = \CUW\App\Helpers\Config::get('plugin');
        if (!empty($_GET['page']) && !empty($upsellwp_plugin['slug']) && $_GET['page'] == $upsellwp_plugin['slug']) {
            wp_enqueue_script('uwp_tr_admin_script', plugin_dir_url(UWP_TR_PLUGIN_FILE) . 'assets/admin.js', ['jquery'], UWP_TR_PLUGIN_VERSION, ['in_footer' => true]);
            wp_localize_script('uwp_tr_admin_script', 'uwp_tr_admin_script_data', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('uwp_tr_nonce'),
                'i18n' => [
                  'Synced' => __('Synced', 'upsellwp-translation'),
                ],
            ]);
        }
    }
}