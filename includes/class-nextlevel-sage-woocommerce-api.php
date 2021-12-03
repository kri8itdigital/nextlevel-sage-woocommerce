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

		foreach($_DATA as $_ITEM):

			$_PRODUCT = wc_get_product_id_by_sku($_ITEM['Code']);

			if($_PRODUCT > 0):

				$_PRODUCT = wc_get_product($_PRODUCT);

				/*
				$_PROD->set_stock_quantity($_ITEM['QtyOnHand']);

				if ($_ITEM['QtyOnHand'] > 0):
					$_PROD->set_stock_status('instock');
				else:
					$_PROD->set_stock_status('outofstock');
				endif;	

				$_PROD->save();
				*/

				//echo 'FOUND: '.$_ITEM['Code'].' -- '.$_PRODUCT->get_id().' -- '.$_ITEM['QtyOnHand'].'<br/>';

			else:

				//echo 'NOT: '.$_ITEM['Code'].' --  -- '.$_ITEM['QtyOnHand'].'<br/>';
			endif;

		endforeach;

	}





	/*
	HANDLE PRICE UPDATE
	*/
	public static function UPDATEPRICE($_DATA){

		foreach($_DATA as $_ITEM):

			$_PRODUCT = wc_get_product_id_by_sku($_ITEM['Code']);

			if($_PRODUCT > 0):

				$_PRODUCT = wc_get_product($_PRODUCT);

				if($_ITEM['RetailIncl'] > 0):

					/*
					$_PROD->set_price($_ITEM['RetailIncl']);
					$_PROD->set_regular_price($_ITEM['RetailIncl']);

					$_PROD->save();
					*/
				else:

					// DO WHAT?

				endif;

				//echo 'FOUND: '.$_ITEM['Code'].' -- '.$_PRODUCT->get_id().' -- '.$_ITEM['RetailIncl'].'<br/>';

			else:

				//echo 'NOT: '.$_ITEM['Code'].' --  -- '.$_ITEM['RetailIncl'].'<br/>';
			endif;

		endforeach;
		
	}












}





?>