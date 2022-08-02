<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.kri8it.com
 * @since      1.0.0
 *
 * @package    Nextlevel_Sage_Woocommerce
 * @subpackage Nextlevel_Sage_Woocommerce/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Nextlevel_Sage_Woocommerce
 * @subpackage Nextlevel_Sage_Woocommerce/admin
 * @author     Hilton Moore <hilton@kri8it.com>
 */
class Nextlevel_Sage_Woocommerce_Admin {

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
		 * defined in Nextlevel_Sage_Woocommerce_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Nextlevel_Sage_Woocommerce_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/nextlevel-sage-woocommerce-admin.css', array(), $this->version, 'all' );

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
		 * defined in Nextlevel_Sage_Woocommerce_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Nextlevel_Sage_Woocommerce_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/nextlevel-sage-woocommerce-admin.js', array( 'jquery' ), $this->version, false );

	}





	/*
	
	*/
	public function woocommerce_get_settings_pages(){

		include plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-nextlevel-sage-woocommerce-settings.php';

		$settings[] = new Nextlevel_Sage_Woocommerce_Settings();


		return $settings;

	}





	/*
	INCREASE TIMEOUT FOR CALLS
	*/
	public function http_request_timeout(){
		return 600000;
	}





	/*
	CREATE EXTRA TIMINGS
	*/
	public function cron_schedules($schedules){
		
		$schedules['twohours'] = array(
	        'interval' => 7200,
	        'display'  => esc_html__( 'Every Two Hours' ),
	    );

	    return $schedules;
	}





	/*
	SETUP SCHEDULES
	*/
	public function setup_cron_schedules(){

		$_INTERVAL = get_option('nextlevel_sage_woocommerce_interval');
		
		if (! wp_next_scheduled( 'nextlevel_sage_woocommerce_cron_prices')):

			wp_schedule_event(time(), $_INTERVAL, 'nextlevel_sage_woocommerce_cron_prices');


		endif;

		if (! wp_next_scheduled( 'nextlevel_sage_woocommerce_cron_stock')):

			wp_schedule_event(time(), $_INTERVAL, 'nextlevel_sage_woocommerce_cron_stock');

		endif;
	}





	/*
	CRON: DO PRICE UPDATE
	*/
	public function nextlevel_sage_woocommerce_cron_prices(){

		Nextlevel_Sage_Woocommerce_API::PRICE();
		
	}





	/*
	CRON: DO STOCK UPDATE
	*/
	public function nextlevel_sage_woocommerce_cron_stock(){

		Nextlevel_Sage_Woocommerce_API::STOCK();

	}

}
