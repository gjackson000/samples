<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.gordonjackson.org
 * @since      1.0.0
 *
 * @package    Shopper_Approved
 * @subpackage Shopper_Approved/includes
 */
namespace ShopperApproved;
/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Shopper_Approved
 * @subpackage Shopper_Approved/includes
 * @author     Gordon Jackson  <gordon@gordonjackson.org>
 */
class Shopper_Approved_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'shopper-approved',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
