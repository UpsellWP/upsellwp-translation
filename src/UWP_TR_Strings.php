<?php
defined('ABSPATH') || exit;

class UWP_TR_Strings
{
    /**
     * To hold Collections list.
     */
    private static $collections = [];

    /**
     * To get the UpsellWP dynamic strings.
     *
     * @return array
     */
    public static function getStrings(): array
    {
        $offer_strings = self::getOfferTemplateStrings();
        $product_strings = self::getCampaignTemplateStrings();
        $setting_strings = self::getSettingStrings();
        return array_unique(array_merge(array_merge($offer_strings, $product_strings), $setting_strings));
    }

    /**
     * To get the UpsellWP offers dynamic strings.
     *
     * @return array
     */
    public static function getOfferTemplateStrings(): array
    {
        if (!method_exists('\CUW\App\Models\Offer', 'all')) {
            return [];
        }
        $strings = [];
        $offer_data = \CUW\App\Models\Offer::all(['columns' => ['data']]);
        $string_keys = self::getCollections('offer_data_keys');
        if (!empty($offer_data) && !empty($string_keys)) {
            foreach (array_column($offer_data, 'data') as $data) {
                if (!empty($data)) {
                    $strings = array_merge($strings, self::getStringsRecursively($data, $string_keys));
                }
            }
        }
        return $strings;
    }

    /**
     * To get the dynamic strings.
     *
     * @param $data
     * @param $keys
     * @return array
     */
    private static function getStringsRecursively($data, $keys): array
    {
        $strings = [];
        foreach ($keys as $key) {
            if (is_array($key)) {
                if (!empty($data)) {
                    foreach ($data as $sub_data) {
                        $strings = array_merge($strings, self::getStringsRecursively($sub_data, $key));
                    }
                }
            } elseif (!empty($data[$key])) {
                if (is_array($data[$key])) {
                    $strings = array_merge($strings, self::getStringsRecursively($data[$key], array_keys($data[$key])));
                } else {
                    $strings[] = $data[$key];
                }
            }
        }
        return $strings;
    }

    /**
     * To get the UpsellWP campaign dynamic strings.
     *
     * @return array
     */
    public static function getCampaignTemplateStrings(): array
    {
        if (!method_exists('\CUW\App\Models\Campaign', 'all')) {
            return [];
        }
        $strings = [];
        $campaign_data = \CUW\App\Models\Campaign::all(['columns' => ['data']]);
        $string_keys = self::getCollections('campaign_data_keys');
        if (!empty($campaign_data) && !empty($string_keys)) {
            foreach (array_column($campaign_data, 'data') as $data) {
                if (!empty($data)) {
                    $strings = array_merge($strings, self::getStringsRecursively($data, $string_keys));
                }
            }
        }
        return $strings;
    }

    /**
     * To get the UpsellWP setting dynamic strings.
     *
     * @return array
     */
    public static function getSettingStrings(): array
    {
        $keys = self::getCollections('setting_keys');
        $data = get_option('cuw_settings', []);
        $strings = [];
        if (!empty($data) && !empty($keys)) {
            foreach ($keys as $key) {
                if (!empty($data[$key])) {
                    $strings[] = $data[$key];
                }
            }
        }
        return $strings;
    }

    /**
     * To get collections.
     *
     * @param string $key
     * @return array
     */
    public static function getCollections($key = ''): array
    {
        if (empty(self::$collections)) {
            self::$collections = require_once UWP_TR_PLUGIN_PATH . '/collections.php';
        }
        return self::$collections[$key] ?? [];
    }
}