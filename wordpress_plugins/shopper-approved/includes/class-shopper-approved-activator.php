<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.gordonjackson.org
 * @since      1.0.0
 *
 * @package    Shopper_Approved
 * @subpackage Shopper_Approved/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Shopper_Approved
 * @subpackage Shopper_Approved/includes
 * @author     Gordon Jackson  <gordon@gordonjackson.org>
 */
class Shopper_Approved_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
        //uncomment and hook to use a custom table instead of posts for reviews
        //require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        //sa_shopper_approved_create_reviews_table();
	}

}
