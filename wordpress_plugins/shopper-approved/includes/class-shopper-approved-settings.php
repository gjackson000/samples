<?php
/**
 * A helper class to get and set various settings in the WordPress options table.
 */

namespace ShopperApproved;


class ShopperApprovedSettings {

    const SA_OPTIONS_PREFIX = 'shopper_approved_option_';

    public static function getSiteID() {
        $site_id = get_option(ShopperApprovedSettings::SA_OPTIONS_PREFIX.'site_id');
        return $site_id;
    }

    public static function setSiteID($site_id) {
        update_option(ShopperApprovedSettings::SA_OPTIONS_PREFIX.'site_id', $site_id);
    }

    public static function getToken() {
        $token = get_option(ShopperApprovedSettings::SA_OPTIONS_PREFIX.'token');
        return $token;
    }

    public static function setToken($token) {
        update_option(ShopperApprovedSettings::SA_OPTIONS_PREFIX.'token', $token);
    }


}