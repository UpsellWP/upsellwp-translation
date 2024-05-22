<?php
defined('ABSPATH') || exit;

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
}