<?php

defined( 'ABSPATH' ) or die();

if ( ! class_exists( 'VIBE_BP_API_Rest_XProfile_Controller' ) ) {
	
	class VIBE_BP_API_Rest_XProfile_Controller extends WP_REST_Controller{
		
		public static $instance;
		public static function init(){
	        if ( is_null( self::$instance ) )
	            self::$instance = new VIBE_BP_API_Rest_XProfile_Controller();
	        return self::$instance;
	    }
	    public function __construct( ) {
			$this->namespace = Vibe_BP_API_NAMESPACE;
			$this->type= Vibe_BP_API_XPROFILE_TYPE;
			$this->register_routes();
		}

		public function register_routes() {
			
			register_rest_route( $this->namespace, '/profile', array(
				array(
					'methods'             => 'POST',
					'callback'            =>  array( $this, 'get_profile' ),
					'permission_callback' => array( $this, 'get_xprofile_permissions' ),
				),
			));

			register_rest_route( $this->namespace, '/getProfileCompleteness', array(
				array(
					'methods'             => 'POST',
					'callback'            =>  array( $this, 'getProfileCompleteness' ),
					'permission_callback' => array( $this, 'get_xprofile_permissions' ),
				),
			));

			
			register_rest_route( $this->namespace, '/' .$this->type, array(
				array(
					'methods'             => 'POST',
					'callback'            =>  array( $this, 'get_xprofile' ),
					'permission_callback' => array( $this, 'get_xprofile_permissions' ),
				),
			));
		

			register_rest_route( $this->namespace, '/' .$this->type.'/fields', array(
				array(
					'methods'             => 'POST',
					'callback'            =>  array( $this, 'get_xprofile_fields' ),
					'permission_callback' => array( $this, 'get_xprofile_permissions' ),
				),
			));

			register_rest_route( $this->namespace, '/' .$this->type.'/fields/setvisibility', array(
				array(
					'methods'             => 'POST',
					'callback'            =>  array( $this, 'set_xprofile_field_visibility' ),
					'permission_callback' => array( $this, 'get_xprofile_permissions' ),
				),
			));

			register_rest_route( $this->namespace, '/' .$this->type.'/allfields', array(
				array(
					'methods'             => 'POST',
					'callback'            =>  array( $this, 'get_allxprofile_fields' ),
					'permission_callback' => array( $this, 'get_xprofile_permissions' ),
				),
			));

			register_rest_route( $this->namespace, '/' .$this->type.'/field/options', array(
				array(
					'methods'             => 'POST',
					'callback'            =>  array( $this, 'get_xprofile_field_options' ),
					'permission_callback' => array( $this, 'get_xprofile_permissions' ),
				),
			));

			register_rest_route( $this->namespace, '/' .$this->type.'/field/save', array(
				array(
					'methods'             => 'POST',
					'callback'            =>  array( $this, 'save_xprofile_field' ),
					'permission_callback' => array( $this, 'get_xprofile_permissions' ),
				),
			));
			
		}


		/*
	    PERMISSIONS
	     */
	    function get_xprofile_permissions($request){
	    	$body = json_decode($request->get_body(),true);
	       	
	        if (empty($body['token'])){
	           	$client_id = $request->get_param('client_id');
	           	if($client_id == vibebp_get_setting('client_id')){
	           		return true;
	           	}
	        }else{
	        	$token = $body['token'];
	        }
	        /** Get the Secret Key */
	        $secret_key = defined('JWT_AUTH_SECRET_KEY') ? JWT_AUTH_SECRET_KEY : false;
	        if (!$secret_key) {
	          	return false;
	        }
	        /** Try to decode the token */ /** Else return exception*/
	        try {
	            $user_data = JWT::decode($token, $secret_key, array('HS256'));
	            /*
		        avatar: "//www.gravatar.com/avatar/73745bceffd75a7e5a1203d9f0e9fe44?s=150&#038;r=g&#038;d=mm"
				caps: ["subscriber"]
				displayname: "test"
				email: "q@q.com"
				id: "2"
				profile_link: "http://localhost/appointments/members/test"
				slug: "test"
				username: "test"*/
		        $this->user = $user_data->data->user;
		        /** Let the user modify the data before send it back */
	        	return true;

	        }catch (Exception $e) {
	            /** Something is wrong trying to decode the token, send back the error */
	            return false;
	        }
	    	

	    	return false;
	    }


	    function get_profile($request){

	    	$args = json_decode($request->get_body(),true);

	    	$user_id = $this->user->id;
	    	if(!empty($args['id'])){
	    		$user_id = $args['id'];
	    	}

	    	global $bp;
	    	$bp->displayed_user->id = $user_id;
	    	$layout = new WP_Query(apply_filters('vibebp_public_profile_layout_query',array(
				'post_type'=>'member-profile',
				'post_name'=>bp_get_member_type($user_id),
				'posts_per_page'=>1,
				'meta_query'=>array(
					'relation'=>'AND',
					array(
						'key'=>'member_type',
						'compare'=>'NOT EXISTS'
					)
				)
			)));
			if ( !$layout->have_posts() ){

				$layout = new WP_Query(array(
					'post_type'=>'member-profile',
					'orderby'=>'date',
					'order'=>'ASC',
					'posts_per_page'=>1,
					'meta_query'=>array(
						'relation'=>'AND',
						array(
							'key'=>'member_type',
							'compare'=>'NOT EXISTS'
						)
					)
				));
			}

	    	$return ='';
			if ( $layout->have_posts() ){
				/* Start the Loop */
				while ( $layout->have_posts() ) :
					$layout->the_post();
					ob_start();
					global $post;
					setup_postdata($post);
                    the_content();
                    do_action('wp_head');
                    do_action('wp_footer');
                    do_action('wp_enqueue_scripts');
                    do_action('wp_enqueue_styles');
                    if(class_exists('\Elementor\Frontend')){
                        $elementorFrontend = new \Elementor\Frontend();
						$elementorFrontend->enqueue_scripts();
						$elementorFrontend->enqueue_styles();
					}
					$return = ob_get_clean();
					break;
				endwhile;
			}

			return new WP_REST_Response( do_shortcode($return), 200 );
	    }

	    function getProfileCompleteness($request){
	    	$user_id = $this->user->id;

	    	$completeness = 0;
	    	$completeness = get_user_meta($user_id,'profileCompleteness',true);

	    	if(!empty($completeness) && $completeness >=100){
	    		return new WP_REST_Response( array('status'=>false), 200 );
	    	}

	    	$groups = bp_xprofile_get_groups( array(
				'fetch_fields' => true
			) );

			if(!empty($groups)){

				$return = array('status'=>true);
				$incomplete = $total = 0;
				foreach($groups as $group){
					$return['groups'][]=array(
						'id'=>$group->id,
						'name'=>esc_html( apply_filters( 'bp_get_the_profile_group_name', $group->name ) )
					);
					

					if(!empty($group->fields)){
						foreach ( $group->fields as $field ) {
							$total++;
							$val = xprofile_get_field_data( $field->id, $this->user->id);
							if(empty($val)){
								$incomplete++;
								$types = bp_xprofile_get_meta( $field->id, 'field', 'member_type', false );
								$member_type = bp_get_member_type($this->user->id);
								if(empty($types) || (!empty($types) && in_array($member_type,$types))){
									remove_filter( 'xprofile_get_field_data', 'xprofile_filter_format_field_value_by_field_id', 5, 2 );
									$field = xprofile_get_field( $field->id );
									$details = array(
										'id'=>$field->id,
										'group_id'=>$group->id,
										'name'=>$field->name,
										'description'=>$field->description,
										'type'=>$field->type,
										'value'=>(empty($val)?'':$val),
										'visibility'=>xprofile_get_field_visibility_level( $field->id, $this->user->id )
									);
									if($field->type == 'datebox'){
										$details['date_format'] = bp_xprofile_get_meta($field->id,'field','date_format',true);
									}

									if($field->type == 'upload'){
										$details['upload_size'] = (int)bp_xprofile_get_meta($field->id,'field','vibebp_upload_size',true);
										$details['upload_types'] = bp_xprofile_get_meta($field->id,'field','vibebp_upload_types',true);
										$details['all_upload_types']  = vibebp_getMimeTypes();
										
									}
									if($field->type == 'video'){
										$details['upload_size'] = (int)bp_xprofile_get_meta($field->id,'field','vibebp_video_size',true);
										$details['upload_types'] = bp_xprofile_get_meta($field->id,'field','vibebp_upload_types',true);
										$details['all_upload_types']  = vibebp_getMimeTypes();
										
									}
									if($field->type == 'gallery'){
										$details['upload_size'] = (int)bp_xprofile_get_meta($field->id,'field','vibebp_gallery_size',true);
										$details['upload_types'] = bp_xprofile_get_meta($field->id,'field','vibebp_gallery_types',true);
										$details['all_upload_types']  = vibebp_getMimeTypes();
										
									}
									$return['fields'][]= apply_filters('vibebp_get_allxprofile_fields_field_details',$details);
								}
							}
						}
					}
				}

				$complete = $total-$incomplete;
				if(empty($complete)){$complete=1;}
				$completeness = round($complete*100/$total,2);
				if(empty($incomplete)){
					update_user_meta($user_id,'profileCompleteness',$completeness);	
				}
				$return['completeness']=$completeness;
				$return['total_field_count']=$total;
			}
			return new WP_REST_Response( $return, 200 );
	    }

	    function get_xprofile($request){
	    	// return 'hi';

	    	$args = json_decode(file_get_contents('php://input'));
	    	$args = json_decode(json_encode($args),true);


	    	$groups = BP_XProfile_Group::get( $args );
	/*    	foreach ($groups as $group) {
	    		$ids[] = $group->id;
	    		$data[] = xprofile_get_field($group->id, 1);
	    	}
	    	return $data;


	    	bp_xprofile_format_activity_action_new_avatar( $action, $activity );

	    	$obj = new BP_XProfile_Field;

	    	return $obj->get_field_data(1);

	    	$run = bp_activity_get($args);


    		if( $run){
    	    	$data=array(
	    			'status' => 1,
	    			'data' => $run,
	    			'message' => _x('Activities Found','Activities Found','vibebp')
	    		);
    	    }else{
    	    	$data=array(
	    			'status' => 0,
	    			'data' => $run,
	    			'message' => _x('Activities not Found','Activities not Found','vibebp')
	    		);
    	    }
    		$data=apply_filters( 'vibe_bp_api_get_xprofile', $data , $request ,$args);
    		return new WP_REST_Response( $data, 200 ); */
	    }

	    function get_profile_field_by_id($request){
	    	$args = json_decode(file_get_contents('php://input'));
	    	$args = json_decode(json_encode($args),true);
	    }


	    function get_xprofile_fields($request){

	    	$return = array();
	    	$groups = bp_xprofile_get_groups( array(
				'fetch_fields' => true
			) );

			if(!empty($groups)){
				foreach($groups as $group){
					$return['groups'][]=array(
						'id'=>$group->id,
						'name'=>esc_html( apply_filters( 'bp_get_the_profile_group_name', $group->name ) )
					);
					if(!empty($group->fields)){
						foreach ( $group->fields as $field ) {
							$field = xprofile_get_field( $field->id );
							$return['fields'][]=array(
								'id'=>$field->id,
								'group_id'=>$group->id,
								'name'=>$field->name,
								'type'=>$field->type,
								'visibility'=>xprofile_get_field_visibility_level( $field->id, $this->user->id )
							);
						}
					}
				}
			}

			return new WP_REST_Response( $return, 200 );
	    }

	    function set_xprofile_field_visibility($request){
	    	$body = json_decode($request->get_body(),true);
	    	$return = xprofile_set_field_visibility_level( $body['field_id'], $this->user->id, $body['visibility']);
    		return new WP_REST_Response( $return, 200 );

	    }


	    function get_allxprofile_fields($request){
	    	$return = array('status'=>1);
	    	$groups = bp_xprofile_get_groups( array(
				'fetch_fields' => true
			) );

			if(!empty($groups)){
				foreach($groups as $group){
					$return['groups'][]=array(
						'id'=>$group->id,
						'name'=>esc_html( apply_filters( 'bp_get_the_profile_group_name', $group->name ) )
					);
					if(!empty($group->fields)){
						foreach ( $group->fields as $field ) {

							$types = bp_xprofile_get_meta( $field->id, 'field', 'member_type', false );
							$member_type = bp_get_member_type($this->user->id);
							if(empty($types) || (!empty($types) && in_array($member_type,$types))){
								remove_filter( 'xprofile_get_field_data', 'xprofile_filter_format_field_value_by_field_id', 5, 2 );
								if($field->type=='upload' || $field->type=='video' || $field->type=='gallery'){
									$val =BP_XProfile_ProfileData::get_value_byid( $field->id, $this->user->id);
									if(is_serialized($val)){
										$val = unserialize(stripcslashes($val));
									}
									
								}else{
									$val = xprofile_get_field_data( $field->id, $this->user->id);
								}
								
								$field = xprofile_get_field( $field->id );
								$details = array(
									'id'=>$field->id,
									'group_id'=>$group->id,
									'name'=>$field->name,
									'type'=>$field->type,
									'value'=>(empty($val)?'':$val),
									'visibility'=>xprofile_get_field_visibility_level( $field->id, $this->user->id )
								);
								if($field->type == 'datebox'){
									$details['date_format'] = bp_xprofile_get_meta($field->id,'field','date_format',true);
								}
								if($field->type == 'upload'){
									$details['upload_size'] = (int)bp_xprofile_get_meta($field->id,'field','vibebp_upload_size',true);
									$details['upload_types'] = bp_xprofile_get_meta($field->id,'field','vibebp_upload_types',true);
									$details['all_upload_types']  = vibebp_getMimeTypes();
									
								}
								if($field->type == 'video'){
									$details['upload_size'] = (int)bp_xprofile_get_meta($field->id,'field','vibebp_video_size',true);
									$details['upload_types'] = bp_xprofile_get_meta($field->id,'field','vibebp_video_types',true);
									$details['all_upload_types']  = vibebp_getMimeTypes();
									
								}
								if($field->type == 'gallery'){
									$details['upload_size'] = (int)bp_xprofile_get_meta($field->id,'field','vibebp_gallery_size',true);
									$details['upload_types'] = bp_xprofile_get_meta($field->id,'field','vibebp_gallery_types',true);
									$details['all_upload_types']  = vibebp_getMimeTypes();
									
								}
								$return['fields'][]= apply_filters('vibebp_get_allxprofile_fields_field_details',$details);
							}
						}
					}
				}
			}else{
				$return['status']=0;
			}

			return new WP_REST_Response( $return, 200 );
	    }

	    function save_xprofile_field($request){

	    	$body = json_decode($request->get_body(),true);

	    	$return = array('status'=>1,'message'=>__('Field Saved !','vibebp'));

	    	if(!empty($body['type'])){
	    		if($body['type'] == 'datebox' && !empty($body['value'])){
	    			$body['value'] = date('Y-m-d H:i:s',strtotime($body['value']));
	    		}
	    		if($body['type'] == 'location'){
	    			update_user_meta($this->user->id,'lat',$body['value']['lat']);
	    			update_user_meta($this->user->id,'lng',$body['value']['lng']);
	    		}
	    	}

	    	if($body['type']=='upload' || $body['type']=='video' ){

	    		//remove_all_filters( 'xprofile_data_value_before_save' );
	    		remove_filter( 'xprofile_data_value_before_save','xprofile_sanitize_data_value_before_save', 1, 4 );
	    		//remove_filter( 'bp_xprofile_set_field_data_pre_validate',  'xprofile_filter_pre_validate_value_by_field_type', 10, 3 );
	    	}
	    	
	    	$vv = $body['value'];
	    	if($body['type']=='gallery'){
	    		add_filter( 'xprofile_data_value_before_save',function($value) use($vv){
	    			$value = $vv;

	    			return maybe_serialize($value);
	    		},999,1);
	    	}
	    	$saved = xprofile_set_field_data( $body['field_id'], $this->user->id, $body['value'] );
	    	add_filter( 'xprofile_data_value_before_save',          'xprofile_sanitize_data_value_before_save', 1, 4 );
	    	
	    	if($saved && !in_array($body['type'], array('upload','video','gallery'))){
	    		if(is_Array($body['value'])){
		    		foreach($body['value'] as $key=>$value){
		    			bp_xprofile_update_field_meta($body['field_id'], $key,  wp_filter_nohtml_kses($value));
		    		}
		    	}
	    	}
	    	

	    	if(!$saved){
	    		$return['status']=0;
	    		$return['message']=__('Unable to save','vibebp');
	    	}else{
	    		
	    		vibebp_fireabase_update_stale_requests('global','member_card/'.$this->user->id);
	    		vibebp_fireabase_update_stale_requests('global','member/'.$this->user->id);
	    	}
	    	
	    	return new WP_REST_Response( $return, 200 );
	    }

	    function get_xprofile_field_options($request){
	    	$body = json_decode($request->get_body(),true);

	    	$return = array('status'=>1,'message'=>__('Fetch Options','vibebp'));
	    	if(!empty($body['field_id'] )){
	    		$field_obj = xprofile_get_field( $body['field_id'] );
		    	$return['values']       = $field_obj->get_children();	
	    	}

	    	if(!empty($body['fields'])){
	    		foreach($body['fields'] as $field){
	    			$field_obj = xprofile_get_field( $field['field_id'] );
	    			$return['values'][] = array(
	    				'id'=>$field['field_id'],
	    				'values'=>$field_obj->get_children()
	    			);
	    		}
	    	}


	    	return new WP_REST_Response( $return, 200 );
	    }
	}
}

VIBE_BP_API_Rest_XProfile_Controller::init();