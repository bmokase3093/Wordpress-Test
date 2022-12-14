<?php

defined( 'ABSPATH' ) or die();

if ( ! class_exists( 'BP_Course_New_Rest_Controller' ) ) {
	
	class BP_Course_New_Rest_Controller extends WP_REST_Controller {

		/**
		 * The resource type.
		 * 
		 * @since 3.0.0
		 * 
		 * @var mixed|void
		 */
		protected $type;

		protected $id;

		/**
		 * Constructs the REST controller.
		 *
		 * @since 3.0.0
		 *
		 * @param $type
		 */
		public function __construct( $type ) {

			$this->type = $type;
			$this->namespace = BP_COURSE_API_NAMESPACE;
			$this->registered_post_types = apply_filters('bp_course_api_registered_post_types',
				array(
						'course',
						'quiz',
						'unit',
						'question',
						'student',
						'instructor'
					)
				);
		}

		/**
		 * Check if a given request has access to create items.
		 *
		 * @since 3.0.0
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|bool
		 */
		public function create_items_permissions_check( $request ) {

			/**
			 * Filter the response of the get items permission check.
			 *
			 * @since 3.0.0
			 */
			return apply_filters( 'bp_course_api_create_items_capability', current_user_can( 'edit_posts' ), $request, $this->type );
		}

		/**
		 * Check if a given request has access to create a specific item.
		 *
		 * @since 3.0.0
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|bool
		 */
		public function create_item_permissions_check( $request ) {
			return $this->create_items_permissions_check( $request );
		}

		/**
		 * Check if a given request has access to get items.
		 *
		 * @since 3.0.0
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|bool
		 */
		public function get_items_permissions_check( $request ) {

			/**
			 * Filter the response of the get items permission check.
			 *
			 * @since 3.0.0
			 */
			return apply_filters( 'bp_course_api_get_items_capability', true, $request, $this->type );
		}

		/**
		 * Check if a given request has access to get a specific item.
		 *
		 * @since 3.0.0
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|bool
		 */
		public function get_item_permissions_check( $request ) {
			return $this->get_items_permissions_check( $request );
		}

		/**
		 * Check if a given request has access to update items.
		 *
		 * @since 3.0.0
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|bool
		 */
		public function update_items_permissions_check( $request ) {
			return $this->create_items_permissions_check( $request );
		}

		/**
		 * Check if a given request has access to update a specific item.
		 *
		 * @since 3.0.0
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|bool
		 */
		public function update_item_permissions_check( $request ) {
			return $this->create_items_permissions_check( $request );
		}

		/**
		 * Check if a given request has access to delete items.
		 *
		 * @since 3.0.0
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|bool
		 */
		public function delete_items_permissions_check( $request ) {
			return $this->create_items_permissions_check( $request );
		}
		
		/**
		 * Check if a given request has access to delete a specific item.
		 *
		 * @since 3.0.0
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 * @return WP_Error|bool
		 */
		public function delete_item_permissions_check( $request ) {
			return $this->create_items_permissions_check( $request );
		}


		/**
		 * Returns an error when no post could be found.
		 *
		 * @since 3.0.0
		 *
		 * @return WP_Error
		 */
		protected function no_post_error() {
			return new WP_Error(
				'bp-course-api-no-post',
				_x( 'The post could not be found', 'error message', 'wplms' ),
				array( 'status' => 404 )
			);
		}

		/**
		 * Returns an error when no items could be found.
		 *
		 * @since 3.0.0
		 *
		 * @return WP_Error
		 */
		protected function no_items_error() {
			return new WP_Error(
				'bp-course-api-no-items',
				_x( 'No items could be found', 'error message', 'wplms' ),
				array( 'status' => 404 )
			);
		}
		
		/**
		 * Returns an error when no item could be found.
		 *
		 * @since 3.0.0
		 *
		 * @return WP_Error
		 */
		protected function no_item_error() {
			return new WP_Error(
				'bp-course-api-no-item',
				_x( 'No item could be found', 'error message', 'wplms' ),
				array( 'status' => 404 )
			);
		}
		
		/**
		 * Returns an error when the request is improperly formatted.
		 *
		 * @since 3.0.0
		 *
		 * @return WP_Error
		 */
		protected function invalid_request_error() {
			return new WP_Error(
				'bp-course-api-invalid-request',
				_x( 'The request format is invalid', 'error message', 'wplms' ),
				array( 'status' => 500 )
			);
		}

		/**
		 * Prepare the item for create or update operation.
		 *
		 * @param WP_REST_Request $request Request object.
		 * @return WP_Error|object $prepared_item
		 */
		protected function prepare_item_for_database( $request ) {
			return new WP_Error( 'invalid-method', sprintf( __( "Method '%s' not implemented. Must be over-ridden in subclass.", 'wplms' ), __METHOD__ ), array( 'status' => 405 ) );
		}

		/**
		 * Prepare the item for the REST response.
		 *
		 * @param mixed $item WordPress representation of the item.
		 * @param WP_REST_Request $request Request object.
		 * @return WP_REST_Response $response
		 */
		public function prepare_item_for_response( $item, $request ) {
			return new WP_Error( 'invalid-method', sprintf( __( "Method '%s' not implemented. Must be over-ridden in subclass.", 'wplms' ), __METHOD__ ), array( 'status' => 405 ) );
		}

		/**
		 * Prepare a response for inserting into a collection.
		 *
		 * @param WP_REST_Response $response Response object.
		 * @return array Response data, ready for insertion into collection data.
		 */
		public function prepare_response_for_collection( $response ) {
			if ( ! ( $response instanceof WP_REST_Response ) ) {
				return $response;
			}

			$data = (array) $response->get_data();
			$server = rest_get_server();

			if ( method_exists( $server, 'get_compact_response_links' ) ) {
				$links = call_user_func( array( $server, 'get_compact_response_links' ), $response );
			} else {
				$links = call_user_func( array( $server, 'get_response_links' ), $response );
			}

			if ( ! empty( $links ) ) {
				$data['_links'] = $links;
			}

			return $data;
		}

		/**
		 * Filter a response based on the context defined in the schema.
		 *
		 * @param array $data
		 * @param string $context
		 * @return array
		 */
		public function filter_response_by_context( $data, $context ) {

			$schema = $this->get_item_schema();
			foreach ( $data as $key => $value ) {
				if ( empty( $schema['properties'][ $key ] ) || empty( $schema['properties'][ $key ]['context'] ) ) {
					continue;
				}

				if ( ! in_array( $context, $schema['properties'][ $key ]['context'] ) ) {
					unset( $data[ $key ] );
				}

				if ( 'object' === $schema['properties'][ $key ]['type'] && ! empty( $schema['properties'][ $key ]['properties'] ) ) {
					foreach ( $schema['properties'][ $key ]['properties'] as $attribute => $details ) {
						if ( empty( $details['context'] ) ) {
							continue;
						}
						if ( ! in_array( $context, $details['context'] ) ) {
							if ( isset( $data[ $key ][ $attribute ] ) ) {
								unset( $data[ $key ][ $attribute ] );
							}
						}
					}
				}
			}

			return $data;
		}

		/**
		 * Get the item's schema, conforming to JSON Schema.
		 *
		 * @return array
		 */
		public function get_item_schema() {
			return $this->add_additional_fields_schema( array() );
		}

		/**
		 * Get the item's schema for display / public consumption purposes.
		 *
		 * @return array
		 */
		public function get_public_item_schema() {

			$schema = $this->get_item_schema();

			foreach ( $schema['properties'] as &$property ) {
				if ( isset( $property['arg_options'] ) ) {
					unset( $property['arg_options'] );
				}
			}

			return $schema;
		}

		/**
		 * Get the query params for collections.
		 *
		 * @return array
		 */
		public function get_collection_params() {
			return array(
				'context'                => $this->get_context_param(),
				'page'                   => array(
					'description'        => __( 'Current page of the collection.', 'wplms' ),
					'type'               => 'integer',
					'default'            => 1,
					'sanitize_callback'  => 'absint',
					'validate_callback'  => 'rest_validate_request_arg',
					'minimum'            => 1,
				),
				'per_page'               => array(
					'description'        => __( 'Maximum number of items to be returned in result set.', 'wplms' ),
					'type'               => 'integer',
					'default'            => 10,
					'minimum'            => 1,
					'maximum'            => 100,
					'sanitize_callback'  => 'absint',
					'validate_callback'  => 'rest_validate_request_arg',
				),
				'search'                 => array(
					'description'        => __( 'Limit results to those matching a string.', 'wplms' ),
					'type'               => 'string',
					'sanitize_callback'  => 'sanitize_text_field',
					'validate_callback'  => 'rest_validate_request_arg',
				),
			);
		}

		/**
		 * Get the magical context param.
		 *
		 * Ensures consistent description between endpoints, and populates enum from schema.
		 *
		 * @param array     $args
		 * @return array
		 */
		public function get_context_param( $args = array() ) {
			$param_details = array(
				'description'        => __( 'Scope under which the request is made; determines fields present in response.', 'wplms' ),
				'type'               => 'string',
				'sanitize_callback'  => 'sanitize_key',
				'validate_callback'  => 'rest_validate_request_arg',
			);
			$schema = $this->get_item_schema();
			if ( empty( $schema['properties'] ) ) {
				return array_merge( $param_details, $args );
			}
			$contexts = array();
			foreach ( $schema['properties'] as $key => $attributes ) {
				if ( ! empty( $attributes['context'] ) ) {
					$contexts = array_merge( $contexts, $attributes['context'] );
				}
			}
			if ( ! empty( $contexts ) ) {
				$param_details['enum'] = array_unique( $contexts );
				rsort( $param_details['enum'] );
			}
			return array_merge( $param_details, $args );
		}

		/**
		 * Add the values from additional fields to a data object.
		 *
		 * @param array  $object
		 * @param WP_REST_Request $request
		 * @return array modified object with additional fields.
		 */
		protected function add_additional_fields_to_object( $object, $request ) {

			$additional_fields = $this->get_additional_fields();

			foreach ( $additional_fields as $field_name => $field_options ) {

				if ( ! $field_options['get_callback'] ) {
					continue;
				}

				$object[ $field_name ] = call_user_func( $field_options['get_callback'], $object, $field_name, $request, $this->get_object_type() );
			}

			return $object;
		}

		/**
		 * Update the values of additional fields added to a data object.
		 *
		 * @param array  $object
		 * @param WP_REST_Request $request
		 */
		protected function update_additional_fields_for_object( $object, $request ) {

			$additional_fields = $this->get_additional_fields();

			foreach ( $additional_fields as $field_name => $field_options ) {

				if ( ! $field_options['update_callback'] ) {
					continue;
				}

				// Don't run the update callbacks if the data wasn't passed in the request.
				if ( ! isset( $request[ $field_name ] ) ) {
					continue;
				}

				call_user_func( $field_options['update_callback'], $request[ $field_name ], $object, $field_name, $request, $this->get_object_type() );
			}
		}

		/**
		 * Add the schema from additional fields to an schema array.
		 *
		 * The type of object is inferred from the passed schema.
		 *
		 * @param array $schema Schema array.
		 */
		protected function add_additional_fields_schema( $schema ) {
			if ( empty( $schema['title'] ) ) {
				return $schema;
			}

			/**
			 * Can't use $this->get_object_type otherwise we cause an inf loop.
			 */
			$object_type = $schema['title'];

			$additional_fields = $this->get_additional_fields( $object_type );

			foreach ( $additional_fields as $field_name => $field_options ) {
				if ( ! $field_options['schema'] ) {
					continue;
				}

				$schema['properties'][ $field_name ] = $field_options['schema'];
			}

			return $schema;
		}

		/**
		 * Get all the registered additional fields for a given object-type.
		 *
		 * @param  string $object_type
		 * @return array
		 */
		protected function get_additional_fields( $object_type = null ) {

			if ( ! $object_type ) {
				$object_type = $this->get_object_type();
			}

			if ( ! $object_type ) {
				return array();
			}

			global $wp_rest_additional_fields;

			if ( ! $wp_rest_additional_fields || ! isset( $wp_rest_additional_fields[ $object_type ] ) ) {
				return array();
			}

			return $wp_rest_additional_fields[ $object_type ];
		}

		/**
		 * Get the object type this controller is responsible for managing.
		 *
		 * @return string
		 */
		protected function get_object_type() {
			$schema = $this->get_item_schema();

			if ( ! $schema || ! isset( $schema['title'] ) ) {
				return null;
			}

			return $schema['title'];
		}

		/**
		 * Get an array of endpoint arguments from the item schema for the controller.
		 *
		 * @param string $method HTTP method of the request. The arguments
		 *                       for `CREATABLE` requests are checked for required
		 *                       values and may fall-back to a given default, this
		 *                       is not done on `EDITABLE` requests. Default is
		 *                       WP_REST_Server::CREATABLE.
		 * @return array $endpoint_args
		 */
		public function get_endpoint_args_for_item_schema( $method = WP_REST_Server::CREATABLE ) {

			$schema                = $this->get_item_schema();
			$schema_properties     = ! empty( $schema['properties'] ) ? $schema['properties'] : array();
			$endpoint_args = array();

			foreach ( $schema_properties as $field_id => $params ) {

				// Arguments specified as `readonly` are not allowed to be set.
				if ( ! empty( $params['readonly'] ) ) {
					continue;
				}

				$endpoint_args[ $field_id ] = array(
					'validate_callback' => 'rest_validate_request_arg',
					'sanitize_callback' => 'rest_sanitize_request_arg',
				);

				if ( isset( $params['description'] ) ) {
					$endpoint_args[ $field_id ]['description'] = $params['description'];
				}

				if ( WP_REST_Server::CREATABLE === $method && isset( $params['default'] ) ) {
					$endpoint_args[ $field_id ]['default'] = $params['default'];
				}

				if ( WP_REST_Server::CREATABLE === $method && ! empty( $params['required'] ) ) {
					$endpoint_args[ $field_id ]['required'] = true;
				}

				foreach ( array( 'type', 'format', 'enum' ) as $schema_prop ) {
					if ( isset( $params[ $schema_prop ] ) ) {
						$endpoint_args[ $field_id ][ $schema_prop ] = $params[ $schema_prop ];
					}
				}

				// Merge in any options provided by the schema property.
				if ( isset( $params['arg_options'] ) ) {

					// Only use required / default from arg_options on CREATABLE endpoints.
					if ( WP_REST_Server::CREATABLE !== $method ) {
						$params['arg_options'] = array_diff_key( $params['arg_options'], array( 'required' => '', 'default' => '' ) );
					}

					$endpoint_args[ $field_id ] = array_merge( $endpoint_args[ $field_id ], $params['arg_options'] );
				}
			}

			return $endpoint_args;
		}


		public function get_Video_Iframe_Audio_Content_from_post_content($content){
			$video = $meta = array();$audio = array();$iframes =array();
			$supported_audio_formats = apply_filters('bp_course_api_supported_status_item_file_formats',array('mp3','m4a','ogg','wav'));
			preg_match_all( '/' . get_shortcode_regex(array('video','audio')) . '/', $content, $matches, PREG_SET_ORDER );
			if ( !empty( $matches ) ){
				foreach ( $matches as $shortcode ) {
		            if ( in_array($shortcode[2],array('audio','video'))) {
		            	$paths = explode('"', $shortcode[3]);
		            	if(is_array($paths)){
		            		foreach($paths as $path){
		            			if(!empty($path)){
		            				if(strpos($path, ".mp4")){
		                				$video[] = $path;
		                			}
		            				$audio_ext = '';
		            				if(strpos($path, ".") !== false){
		            					$audio_ext = explode(".",$path);
		            					$audio_ext = end($audio_ext);
		            				}
		            				
		            				
		                			if(!empty($audio_ext) && in_array($audio_ext,$supported_audio_formats)){
		                				$audio[] = $path;
		                			}
		            			}
		            		}
		            	}
		            }
				}	
				$content  = str_replace('[/video]', '', $content );
	    		$content  = str_replace('[/audio]', '', $content );
			}

			//for iframes
			if(false !== strpos($content,'iframe')){

				preg_match_all( '/' . get_shortcode_regex(array('iframe')) . '/', $content, $matches2, PREG_SET_ORDER );
				if ( !empty( $matches2 ) ){

					foreach ( $matches2 as $shortcode ) {
						if(!empty($shortcode[5])){
							if(!empty($version) && $version > 2){
								$iframes[] = array('shortcode'=>'iframe','value'=>$shortcode[5]);
							}else{
								$iframes[] = $shortcode[5];
							}
						}
					}	
				}
			}

			//for iframevideo
			preg_match_all( "/\[iframevideo\](.*)\[\/iframevideo\]/", $content, $matches3 ,PREG_SET_ORDER);
			if ( !empty( $matches3 ) ){
				
				foreach ( $matches3 as $shortcode2 ) {
					preg_match('/src="([^"]+)"/', $shortcode2[1], $matchiframeurl);
					if(!empty($matchiframeurl)){
						if(!empty($version) && $version > 2){
							$iframes[] = array('shortcode'=>'iframevideo','value'=>$matchiframeurl[1]);
						}else{
							$iframes[] = $matchiframeurl[1];
						}
					}
					
			       
				}	
			}


			//for iframevideo
			preg_match_all( "/<video(.*)<\/video>/", $content, $matches3 ,PREG_SET_ORDER);
			if ( !empty( $matches3 ) ){
				foreach ( $matches3 as $shortcode2 ) {
					preg_match('/src="([^"]+)"/', $shortcode2[1], $matchiframeurl);
					if(!empty($matchiframeurl)){
						if(!empty($version) && $version > 2){
							$video[] = array('shortcode'=>'video','value'=>$matchiframeurl[1]);
						}else{
							$video[] = $matchiframeurl[1];
						}
					}
					
			       
				}	
			}

			//for iframevideo
			preg_match_all( "/<audio(.*)<\/audio>/", $content, $matches3 ,PREG_SET_ORDER);
			if ( !empty( $matches3 ) ){
				foreach ( $matches3 as $shortcode2 ) {
					preg_match('/src="([^"]+)"/', $shortcode2[1], $matchiframeurl);
					if(!empty($matchiframeurl)){
						if(!empty($version) && $version > 2){
							$audio[] = array('shortcode'=>'audio','value'=>$matchiframeurl[1]);
						}else{
							$audio[] = $matchiframeurl[1];
						}
					}
					
			       
				}	
			}

			//for wplms vimeo
			if(false !== strpos($content,'wplms_vimeo')){
				preg_match_all( '/' . get_shortcode_regex(array('wplms_vimeo')) . '/', $content, $matches4, PREG_SET_ORDER );
				if ( !empty( $matches4 ) ){
					foreach ( $matches4 as $shortcode3 ) {
						preg_match('/[0-9]*[0-9]/',$shortcode3[3],$file_numeric);
						if(!empty($file_numeric[0])){
							if(!empty($version) && $version > 2){
								$iframes[] = array('shortcode'=>'wplms_vimeo','value'=>'https://player.vimeo.com/video/'.$file_numeric[0]);
							}else{
								$iframes[] = 'https://player.vimeo.com/video/'.$file_numeric[0];
							}
						}
					}	
				}
			}

			//for wplms s3
			if(false !== strpos($content,'wplms_s3')){
				
				preg_match_all( '/' . get_shortcode_regex(array('wplms_s3')) . '/', $content, $matches5, PREG_SET_ORDER );
				if ( !empty( $matches5 ) ){
					foreach ( $matches5 as $shortcode4 ) {
						preg_match('/link=[\'|"](.*?)[\'|"]/',$shortcode4[3],$link_s3);

						preg_match('/duration=[\'|"](.*?)[\'|"]/',$shortcode4[3],$duration);

						preg_match('/parameter=[\'|"](.*?)[\'|"]/',$shortcode4[3],$parameter);

						if(!empty($link_s3[1])){
							if(class_exists('Wplms_S3_Init')){
								$s3 =Wplms_S3_Init::init();
								$file_mime = $s3->getMimeType($link_s3[1]);
								$video_mimes = apply_filters('api_allowed_video_mime_types',array(
									'video/mp4','video/ogg','video/webm','video/flv',
									));
								$audio_mimes = apply_filters('api_allowed_audio_mime_types',array(
									'audio/mp4','audio/mp3','audio/mp4a-latm', 'audio/m4a', 'audio/mp4','audio/mpeg','audio/x-mpeg', 'audio/mp3', 'audio/x-mp3', 'audio/mpeg3','audio/x-mpeg3','audio/mpg','audio/x-mpg','audio/x-mpegaudio','audio/mp4a-latm', 'audio/m4a','audio/mp4'
									));
								if(in_array($file_mime,$video_mimes)){
									$duration =floatval($duration[1] );$parameter= floatval($parameter[1]);
									if(method_exists($s3, 'get_s3_url')){
										$url = $s3->get_s3_url($link_s3[1],$duration*$parameter);
									}else{
										if(class_exists('WPLMS_Amazon_S3')){
											$amazon_s3 = WPLMS_Amazon_S3::get_instance();
											if(method_exists($amazon_s3, 'get_s3_url')){
												$url = $amazon_s3->get_s3_url($link_s3[1],$duration*$parameter);
											}
										}
									}
									
									if(!empty($url)){
										if(empty($video)){
											$video = array($url);
										}else{
											$video[] = $url;
										}
									}
								}
								if(in_array($file_mime,$audio_mimes)){
									$duration =floatval($duration[1] );$parameter= floatval($parameter[1]);
									if(method_exists($s3, 'get_s3_url')){
										$url = $s3->get_s3_url($link_s3[1],$duration*$parameter);
									}else{
										if(class_exists('WPLMS_Amazon_S3')){
											$amazon_s3 = WPLMS_Amazon_S3::get_instance();
											if(method_exists($amazon_s3, 'get_s3_url')){
												$url = $amazon_s3->get_s3_url($link_s3[1],$duration*$parameter);
											}
										}
									}
									if(!empty($url)){
										if(empty($meta['audio'])){
											$meta['audio'] = array($url);
										}else{
											$meta['audio'][] = $url;
										}
									}
								}
								
							}
						}
					}	
				}
			}

			//for h5p
			if(false !== strpos($content,'wplms_h5p')){
				preg_match_all( '/' . get_shortcode_regex(array('wplms_h5p')) . '/', $content, $matches6, PREG_SET_ORDER );
				if ( !empty( $matches6 ) ){
					foreach ( $matches6 as $shortcode4 ) {
						preg_match('/id=[\'|"](.*?)[\'|"]/',$shortcode4[3],$id);

						if(!empty($id[1])){
							$url = admin_url('admin-ajax.php?action=h5p_embed&id=' .$id[1]) ;
							if(!empty($url)){
								if(empty($iframes)){
									if(!empty($version) && $version > 2){
										$iframes = array(array('shortcode'=>'wplms_h5p','value'=>$url));
									}else{
										$iframes = array($url);
									}
									
								}else{
									if(!empty($version) && $version > 2){
										$iframes[] = array('shortcode'=>'wplms_h5p','value'=>$url);
									}else{
										$iframes[] = $url;
									}
									
								}
							}
						}
					}	
				}
			}

			if(!empty($video)){$meta['video']=$video;}
	    	if(!empty($audio)){$meta['audio']=$audio;}	
			if(!empty($iframes)){$meta['iframes']=$iframes;}
			$regex = get_shortcode_regex(array('audio','video','iframevideo','iframe','wplms_s3','wplms_vimeo','wplms_h5p'));
			$content = preg_replace("/$regex/s", " ", $content);
			$content = preg_replace ( '/\[[video|audio](.*?)\]/s' , '' , $content );
			$content = preg_replace ( '/<audio(.*)<\/audio>/' , '' , $content );
			$content = preg_replace ( '/<video(.*)<\/video>/' , '' , $content );

			$data = array(
				'meta' => $meta,
				'rest_content' => $content
			);
			return $data;
		}



		function set_current_user_id_by_token($request){
			
			$body = json_decode($request->get_body(),true);

	        if(!empty($body) && !empty($body['token'])){
	            $this->user = apply_filters('vibebp_api_get_user_from_token','',$body['token']);
	            if(!empty($this->user)){
	                $this->user_id = $this->user->id;
	                return $this->user_id;
	            }
	        }

	        if(empty($this->user_id)){
				$headers = vibe_getallheaders();
				if(isset($headers['Authorization'])){
					$token = $headers['Authorization'];
					$this->token = $token;
					$this->user_id = $this->get_user_from_token($token);
					return $this->user_id;
				}else{
					$this->user_id = 0;
					return $this->user_id;
				}
			}
			
		}

		function check_user_is_instructor($course_id,$user){
			$user_id = 0;
			if(is_numeric($user)){

				$user_id = $user;
			}
			if(is_object($user)){
				if(!empty($user->ID)){
					$user_id = $user->ID;
				}else{
					if(!empty($user->id)){
						$user_id = $user->id;
					}
				}
			}
			
			if(!empty($user_id) && !empty($course_id)){
				$instructor_ids = apply_filters('wplms_course_instructors',get_post_field('post_author', $course_id),$course_id);	
				if(!is_array($instructor_ids)){
					$instructor_ids = array($instructor_ids);
				}
				if(empty($instructor_ids)){
					$instructor_ids = array();
				}
				
				if(in_array($user_id, $instructor_ids) || user_can($user_id,'manage_options')){
					return true;
				}
			}
			return false;
		} 

		function get_instructor_tabs($course_id,$user_id=null){
			$tabs = array(
						array(
							'key'=>'admin',
							'type'=> 'tab',
							'label'=>_x('Admin','api','wplms'),
						),
						array(
							'key'=>'activity',
							'type'=> 'tab',
							'label'=>_x('Activity','api','wplms'),
						),	
						array(
							'key'=>'submissions',
							'type'=> 'tab',
							'label'=>_x('Submissions','api','wplms'),
						),
				);

				$tabs[] = array(
					'key'=>'news',
					'type'=> 'tab',
					'label'=>_x('Announcements & News','api','wplms'),
				);
				if(comments_open($course_id)){
					$tabs[]=array(
								'key'=>'reviews',
								'type'=> 'tab',
								'label'=>_x('Reviews','api','wplms'),
								'value'=>'md-analytics',
							);
				}
				$tabs[] = array(
							'key'=>'qna',
							'type'=> 'tab',
							'label'=>_x('Questions/Discussions','api','wplms'),
						);
				$tabs[] = array(
							'key'=>'statistics',
							'type'=> 'tab',
							'label'=>_x('Statistics','api','wplms'),
						);
				$tabs[]=array(
							'key'=>'edit_course',
							'type'=> 'tab',
							'label'=>_x('Edit Course','api','wplms'),
						);
			return $tabs = apply_filters('bp_course_api_get_instructor_tabs',$tabs,$course_id,$user_id);
		}

		function get_instructor_admin_tabs($course_id){
			$tabs = array(
						array(
							'key'=>'submissions',
							'type'=> 'tab',
							'label'=>_x('Submissions','api','wplms'),
							'value'=>'md-analytics',
						),
						array(
							'key'=>'news',
							'type'=> 'tab',
							'label'=>_x('News','api','wplms'),
							'value'=>'md-analytics',
						),
						array(
							'key'=>'stats',
							'type'=> 'tab',
							'label'=>_x('Stats','api','wplms'),
							'value'=>'md-analytics',
						)
				);

			
			return $tabs = apply_filters('bp_course_api_get_instructor_admin_tabs',$tabs,$course_id);
		}
		
		function get_user_by_ID($id){
			if(!empty($id)){
				if(empty($this->users[$id])){
					$user = get_userdata($id);

					$this->users[$id] = array(
						'user_id'=>$id,
						'email'=>$user->user_email,
						'link'=>bp_core_get_user_domain($id,$user->user_nicename,$user->user_login),
						'nickname'=>$user->display_name,
						'image'=>get_avatar_url($id)
					);
				}
				
				return $this->users[$id];
			}else{
				return [];
			}
		}


		
	}
}