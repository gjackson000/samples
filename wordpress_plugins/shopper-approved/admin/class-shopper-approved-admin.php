<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.gordonjackson.org
 * @since      1.0.0
 *
 * @package    Shopper_Approved
 * @subpackage Shopper_Approved/admin
 */
namespace ShopperApproved;
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Shopper_Approved
 * @subpackage Shopper_Approved/admin
 * @author     Gordon Jackson  <gordon@gordonjackson.org>
 */
class Shopper_Approved_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

    public function add_menu_items() {
        add_submenu_page ( 'edit.php?post_type=sa_review', 'Shopper Approved Settings', 'Shopper Approved Settings', 'edit_posts', 'sasettings', 'sa_page_settings');
    }

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Shopper_Approved_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Shopper_Approved_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/shopper-approved-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Shopper_Approved_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Shopper_Approved_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/shopper-approved-admin.js', array( 'jquery' ), $this->version, false );

	}

}
