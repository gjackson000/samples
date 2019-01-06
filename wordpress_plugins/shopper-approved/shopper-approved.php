<?php

/**
 * Plugin bootstrap file for Shopper Approved
 *
 * @link              https://www.gordonjackson.org
 * @since             1.0.0
 * @package           Shopper_Approved
 *
 * @wordpress-plugin
 * Plugin Name:       Shopper Approved
 * Plugin URI:        https://www.gordonjackson.org/shopper-approved/
 * Description:       A plugin that uses the Shopper Approved API to import reviews into WordPress Custom Posts
 * Version:           1.0.0
 * Author:            Gordon Jackson 
 * Author URI:        https://www.gordonjackson.org
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       shopper-approved
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-shopper-approved-activator.php
 */
function activate_shopper_approved() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-shopper-approved-activator.php';
	Shopper_Approved_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-shopper-approved-deactivator.php
 */
function deactivate_shopper_approved() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-shopper-approved-deactivator.php';
	Shopper_Approved_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_shopper_approved' );
register_deactivation_hook( __FILE__, 'deactivate_shopper_approved' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-shopper-approved.php';

/**
 * Load plugin and execute.
 * @since    1.0.0
 */
function run_shopper_approved() {

	$plugin = new \ShopperApproved\Shopper_Approved();
	$plugin->run();

}
run_shopper_approved();
