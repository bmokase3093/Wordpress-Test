<?php
if (!class_exists("VibeThemeUpdate")) {
	class VibeThemeUpdate {
		
		public static $instance;

		public static function init(){

	        if ( is_null( self::$instance ) )
	            self::$instance = new VibeThemeUpdate();

	        return self::$instance;
	    }

    	private function __construct(){

			add_filter("pre_set_site_transient_update_themes", array($this,"check"));

		}

		function check($updates){


			$token = get_option('envato_token');

			if(empty($token)){
				return $updates;
			}

			if(time() > $token['expires']){
				//Refetch toekn
				$post = wp_remote_get('https://wplms.io/envato?refresh_token='.$token['refresh_token'],array('timeout' => 45));
				if(wp_remote_retrieve_response_code($post) == 200){
					$token_json = json_decode(wp_remote_retrieve_body($post));
					if(!empty($token_json)){
						$token['access_token'] = $token_json->access_token;
						$token['expires'] = time()+$token_json->expires_in;
						update_option('envato_token',$token);
					}else{
						return $updates;
					}
				}
			}
			$access_token = $token['access_token'];
			//get Purchases

			$verify_purchase = wp_remote_request( 'https://api.envato.com/v3/market/buyer/list-purchases?filter_by=wordpress-themes&include_all_item_details=false', array(
				'headers' => array(
					'Authorization' => 'Bearer ' . $access_token, 
				),
				'method'  => 'GET',
			) );
			$purchases = json_decode(wp_remote_retrieve_body($verify_purchase));

			if(empty($purchases) || empty($purchases->results) || !is_array($purchases->results)){
				return $updates;
			}

			$theme = wp_get_theme('wplms');

			$current_version = $theme->get( 'Version' );

			foreach((array)$purchases->results as $purchase ){
				if($purchase->item->id == 6780226){
					$continue = true;
					$supported_until = $purchase->supported_until;
					$purchase_code = $purchase->code;
					
					if (version_compare($current_version,$purchase->item->wordpress_theme_metadata->version, '<')) {
						// bingo, inject the update
						
						$download = wp_remote_get('https://api.envato.com/v3/market/buyer/download?item_id=6780226',array(
							'headers' => array(
								'Authorization' => 'Bearer ' . $access_token, 
							),
							'timeout' => 45
						));
						
						if(wp_remote_retrieve_response_code($download) == 200){
							$url = json_decode(wp_remote_retrieve_body($download));
							
							if(!empty($url) && !empty($url->wordpress_theme)){
								$update = array(
											"url" => "http://themeforest.net/item/theme/6780226",
											"new_version" => $purchase->item->wordpress_theme_metadata->version,
											"package" => $url->wordpress_theme
											);

								$updates->response[$theme->Stylesheet] = $update;

							}
							

						}
					}
					break;
				}
			}
			return $updates;
		}
	}
}
?>