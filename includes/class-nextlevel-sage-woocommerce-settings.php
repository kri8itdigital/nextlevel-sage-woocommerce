<?php
/**
 * WooCommerce Account Settings.
 *
 * @package WooCommerce/Admin
 */
/**
 * WC_Settings_Accounts.
 */
class Nextlevel_Sage_Woocommerce_Settings extends WC_Settings_Page {


	public function __construct() {
		$this->id    = 'nextlevel-sage-woocommerce';
		$this->label = __( 'NEXTLEVEL Sage Settings', 'nextlevel-sage-woocommerce' );
		parent::__construct();
	} 



	public function get_settings($current_section = '') {


		$_SETTINGS = array(

			array(
			'title' => __( 'NEXTLEVEL Sage Settings', 'nextlevel-sage-woocommerce' ),
				'type'  => 'title',
				'id'    => 'nextlevel_sage_woocommerce_settings',
			),
			array(
				'title'         => __( 'Enable Sage sync', 'nextlevel-sage-woocommerce' ),
				'id'            => 'nextlevel_sage_woocommerce_enabled',
				'default'       => 'no',
				'type'          => 'checkbox'
			),
			array(
				'title'         => __( 'Sage Token Endpoint', 'nextlevel-sage-woocommerce' ),
				'id'            => 'nextlevel_sage_woocommerce_endpoint_token',
				'default'       => 'http://105.247.25.36:9002/api/JwtToken',
				'type'          => 'text'
			),
			array(
				'title'         => __( 'Sage Price Endpoint', 'nextlevel-sage-woocommerce' ),
				'id'            => 'nextlevel_sage_woocommerce_endpoint_price',
				'default'       => 'http://105.247.25.36:9002/api/PriceList',
				'type'          => 'text'
			),
			array(
				'title'         => __( 'Sage Stock Endpoint', 'nextlevel-sage-woocommerce' ),
				'id'            => 'nextlevel_sage_woocommerce_endpoint_stock',
				'default'       => 'http://105.247.25.36:9002/api/WarehouseStock',
				'type'          => 'text'
			),
			array(
				'title'         => __( 'Sage Allow Backorders', 'nextlevel-sage-woocommerce' ),
				'id'            => 'nextlevel_sage_woocommerce_backorders',
				'default'       => 'no',
				'type'          => 'checkbox'
			),
			array(
				'title'         => __( 'Sage No Price Action', 'nextlevel-sage-woocommerce' ),
				'id'            => 'nextlevel_sage_woocommerce_price_action',
				'default' 		=> 'None',
					'type' 		=> 'select',
				'options'   	=> array(
					'draft'		=> __( 'Set to Draft', 'nextlevel-sage-woocommerce' ),
					'hide'	=> __( 'Hide From Catalog', 'nextlevel-sage-woocommerce' )
				)
			),
			array(
					'title' 	=> __('Time Interval', 'nextlevel-sage-woocommerce'),
					'type' 		=> 'select',
					'default' 	=> '',
					'id' 		=> 'nextlevel_sage_woocommerce_interval',
					'default' 	=> 'None',
					'options'   => array(
						'hourly'	=> __( 'Hourly', 'nextlevel-sage-woocommerce' ),
						'twohours'	=> __( 'Every 2 Hours', 'nextlevel-sage-woocommerce' ),
						'daily'	=> __( 'Once Daily', 'nextlevel-sage-woocommerce' ),
						'twicedaily' => __( 'Twice Daily', 'nextlevel-sage-woocommerce' ),
					)
			),

		);



		$_FILTER = apply_filters(
			'woocommerce_' . $this->id . '_settings',
			$_SETTINGS
		);

		return apply_filters( 'woocommerce_get_settings_' . $this->id, $_FILTER );


	}




	public function save(){

		$_SETTINGS = $this->get_settings();

		$_ERRORS = false;

		if($_POST['nextlevel_sage_woocommerce_endpoint_token'] == ''):
			$_POST['nextlevel_sage_woocommerce_enabled'] = 0;
			WC_Admin_Settings::add_error('SAGE Token Endpoint is required');
			$_ERRORS = true;
		endif;

		if($_POST['nextlevel_sage_woocommerce_endpoint_price'] == ''):
			$_POST['nextlevel_sage_woocommerce_enabled'] = 0;
			WC_Admin_Settings::add_error('SAGE Price Endpoint is required');
			$_ERRORS = true;
		endif;

		if($_POST['nextlevel_sage_woocommerce_endpoint_stock'] == ''):
			$_POST['nextlevel_sage_woocommerce_enabled'] = 0;
			WC_Admin_Settings::add_error('SAGE Stock Endpoint is required');
			$_ERRORS = true;
		endif;


		WC_Admin_Settings::save_fields( $_SETTINGS );

	}



}