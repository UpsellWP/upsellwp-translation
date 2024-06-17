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

if (class_exists('UWP_TR_Loco')) {
    return;
}

class UWP_TR_Loco
{
    /**
     * To add dynamic strings in Loco translator.
     *
     * @param $extraction
     * @param $domain
     * @return void
     */
    public static function addStrings($extraction, $domain)
    {
        if (!class_exists('Loco_gettext_String') || $domain != 'checkout-upsell-woocommerce') {
            return;
        }

        include UWP_TR_PLUGIN_PATH . 'src/UWP_TR_Strings.php';
        $dynamic_strings = UWP_TR_Strings::getStrings();
        if (!empty($dynamic_strings)) {
            foreach ($dynamic_strings as $string) {
                $custom = new \Loco_gettext_String($string);
                $extraction->addString($custom, $domain);
            }
        }
    }

    /**
     * To add buttons in UpsellWP Translation Plugin addon tab.
     *
     * @return void
     */
    public static function addLocoSyncAndEditButtons()
    {
        $cuw_has_pro = CUW()->plugin->has_pro;
        $url = admin_url('admin.php') . '?' . http_build_query([
            'page' => 'loco-plugin',
            'action' => 'view',
            'bundle' => ($cuw_has_pro ? 'upsellwp' : 'checkout-upsell-and-order-bumps') . '/' . ($cuw_has_pro ? 'checkout-upsell-woocommerce' : 'checkout-upsell-and-order-bumps') . '.php',
        ]);
        echo '<button type="button" id="uwp-tr-loco-sync" class="btn btn-primary">' . esc_html__('Sync', 'upsellwp-translation') . '</button>';
        echo '<a type="button" id="uwp-tr-loco-edit" class="btn btn-primary" href=" '. esc_url($url) . ' " style="display: none;">' . esc_html__('Edit', 'upsellwp-translation') . '</a>';
    }

    /**
     * To add Loco Translator data.
     *
     * @param $data
     * @return mixed
     */
    public static function addAssetsData($data)
    {
        if (!isset($data['loco_translate'])) {
            $cuw_has_pro = CUW()->plugin->has_pro;
            $data = array_merge($data, [
                'loco_translate' => [
                    'endpoint_url' => 'admin-ajax.php',
                    'bundle' => 'plugin.' . ($cuw_has_pro ? 'upsellwp' : 'checkout-upsell-and-order-bumps') . '/' . ($cuw_has_pro ? 'checkout-upsell-woocommerce' : 'checkout-upsell-and-order-bumps') . '.php',
                    'domain' => 'checkout-upsell-woocommerce',
                    'path' => 'plugins/' . ($cuw_has_pro ? 'upsellwp' : 'checkout-upsell-and-order-bumps') . '/i18n/languages/checkout-upsell-woocommerce.pot',
                    'action' => 'loco_json',
                    'sync_data' => [
                        'type' => 'pot',
                        'sync' => '',
                        'mode' => '',
                        'route' => 'sync',
                        'loco_nonce' => '',
                    ],
                    'save_data' => [
                        'locale' => '',
                        'po' => '(binary)',
                        'route' => 'save',
                        'loco_nonce' => '',
                    ],
                ],
            ]);
        }
        return $data;
    }
}