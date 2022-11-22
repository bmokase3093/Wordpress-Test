<?php

defined( 'ABSPATH' ) or die();

if ( ! class_exists( 'BP_Course_Rest_Instructor_Controller' ) ) {
	
	class BP_Course_Rest_Admin_Controller extends BP_Course_New_Rest_Controller {

		public function register_routes() {
			// instructor app
			$this->type= 'admin';
			register_rest_route( $this->namespace, '/' . $this->type . '/courses', array(
				'methods'                   =>  'POST',
				'callback'                  =>  array( $this, 'get_user_courses' ),
				'permission_callback' 		=> array( $this, 'get_admin_permissions_check' ),
			) );
		}

		function get_admin_permissions_check($request){
			$body = json_decode($request->get_body(),true);
			$token = $body['token'];

			if(!empty($body['token'])){
				global $wpdb;
				$this->user = apply_filters('vibebp_api_get_user_from_token','',$body['token']);
				if( (!empty($this->user) && vibebp_can_access_member_details($this->user)) ) {
					return true;
				}
			}
			return false;
		}

		function get_user_courses($request){

		}
	}
}