<?php



class Nextlevel_Sage_Woocommerce_API{





	/*
	DO API CALL
	*/
	public static function DOCALL($_URL, $_TOKEN = null){


		$_URL = trailingslashit($_URL);

		$_ARGS = array(
		    'method' => 'GET',
		    'timeout' => 60000,
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

		set_time_limit(0);

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

		set_time_limit(0);

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

				$_PROD = wc_get_product($_PRODUCT);
				$_PROD->set_manage_stock(true);
				$_PROD->save();
				$_PROD->set_stock_quantity((int)trim($_ITEM['QtyOnHand']));

				if ((int)trim($_ITEM['QtyOnHand'] > 0)):

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

		foreach($_DATA as $_ITEM):

			$_ID = wc_get_product_id_by_sku($_ITEM['Code']);

			if($_ID > 0):

				$_PROD 	= wc_get_product($_ID);
				$_OBJ	= get_post($_ID);

				if((float)trim($_ITEM['RetailIncl']) > 0):
					
					$_PROD->set_price((float)trim($_ITEM['RetailIncl']));
					$_PROD->set_regular_price((float)trim($_ITEM['RetailIncl']));

					if($_PROD->get_catalog_visibility() != 'visible'):
						$_PROD->set_catalog_visibility('visible');
					endif;

					$_PROD->set_status('publish');
					
				else:

					$_PROD->set_price('');
					$_PROD->set_regular_price('');

					$_PROD->set_status('private');

					if($_PROD->get_catalog_visibility() == 'visible'):
						$_PROD->set_catalog_visibility('hidden');
					endif;

				endif;

				$_PROD->save();

			endif;

		endforeach;
		
	}












}





?>