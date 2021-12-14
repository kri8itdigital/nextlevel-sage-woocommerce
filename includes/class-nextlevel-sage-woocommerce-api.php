<?php



class Nextlevel_Sage_Woocommerce_API{





	/*
	DO API CALL
	*/
	public static function DOCALL($_URL, $_TOKEN = null){


		$_URL = trailingslashit($_URL);

		$_ARGS = array(
		    'method' => 'GET',
		    'headers' => array(
		        'Content-Type' => 'application/json',
		        'Accept' => 'application/json'
		    )
		);

		if($_TOKEN):
			$_ARGS['headers']['Authorization'] = 'Bearer '.$_TOKEN;
		endif;

		$_RETURN = wp_remote_post($_URL, $_ARGS);

		$_RESPONSE = json_decode($_RETURN['body'], true);

		return $_RESPONSE;


	}





	/*
	GENERATE SECURE TOKEN
	*/
	public static function TOKEN(){

		$_URL = get_option('nextlevel_sage_woocommerce_endpoint_token');

		$_DATA = self::DOCALL($_URL);

		return $_DATA;

	}





	/*
	DO STOCK UPDATE
	*/
	public static function STOCK(){

		update_option('nextlevel_sage_woocommerce_cron_stock_running', 'yes');

		$_URL = get_option('nextlevel_sage_woocommerce_endpoint_stock');

		$_TOKEN = self::TOKEN();

		$_DATA = self::DOCALL($_URL, $_TOKEN);
		
		if(count($_DATA) > 0):

			self::UPDATESTOCK($_DATA);

		endif;
		
		update_option('nextlevel_sage_woocommerce_cron_stock_running', 'no');

	}





	/*
	DO PRICE UPDATE
	*/
	public static function PRICE(){

		update_option('nextlevel_sage_woocommerce_cron_prices_running', 'yes');

		$_URL = get_option('nextlevel_sage_woocommerce_endpoint_price');

		$_TOKEN = self::TOKEN();

		$_DATA = self::DOCALL($_URL, $_TOKEN);
		
		if(count($_DATA) > 0):

			self::UPDATEPRICE($_DATA);

		endif;
		
		update_option('nextlevel_sage_woocommerce_cron_prices_running', 'no');
		

	}





	/*
	HANDLE STOCK UPDATE
	*/
	public static function UPDATESTOCK($_DATA){

		$_ACTION = get_option('nextlevel_sage_woocommerce_backorders');

		foreach($_DATA as $_ITEM):

			$_PRODUCT = wc_get_product_id_by_sku($_ITEM['Code']);

			if($_PRODUCT > 0):

				$_PRODUCT = wc_get_product($_PRODUCT);
				
				$_PROD->set_stock_quantity($_ITEM['QtyOnHand']);

				if ($_ITEM['QtyOnHand'] > 0):

					$_PROD->set_stock_status('instock');
					$_PROD->set_backorders('no');
					
				else:
					$_PROD->set_stock_status('outofstock');

					if($_ACTION == 'yes'):
						$_PROD->set_backorders('notify');
					else:
						$_PROD->set_backorders('no');
					endif;
				endif;	


				$_PROD->save();

				
			endif;

		endforeach;

	}





	/*
	HANDLE PRICE UPDATE
	*/
	public static function UPDATEPRICE($_DATA){

		$_ACTION = get_option('nextlevel_sage_woocommerce_price_action');

		foreach($_DATA as $_ITEM):

			$_ID = wc_get_product_id_by_sku($_ITEM['Code']);

			if($_ID > 0):

				$_PROD 	= wc_get_product($_ID);
				$_OBJ 		= get_post($_ID);

				if($_ITEM['RetailIncl'] > 0):
					
					$_PROD->set_price($_ITEM['RetailIncl']);
					$_PROD->set_regular_price($_ITEM['RetailIncl']);

					if($_OBJ->post_status != 'publish'):
						wp_update_post(array('ID' => $_ID, 'post_status' => 'publish'));
					endif;

					if($_PROD->get_catalog_visibility() != 'visible'):
						$_PROD->set_catalog_visibility('visible');
					endif;
					
				else:

					switch($_ACTION):

						case "draft":

							wp_update_post(array('ID' => $_ID, 'post_status' => 'draft'));

							$_PROD->set_catalog_visibility('visible');

						break;

						case "hide":

							if($_OBJ->post_status != 'publish'):
								wp_update_post(array('ID' => $_ID, 'post_status' => 'publish'));
							endif;

							$_PROD->set_catalog_visibility('hidden');

						break;

					endswitch;

				endif;

				$_PROD->save();

			endif;

		endforeach;
		
	}












}





?>