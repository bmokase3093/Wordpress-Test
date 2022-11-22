<?php
/**
 * Action functions for WPLMS
 *
 * @author      VibeThemes
 * @category    Admin
 * @package     Initialization
 * @version     2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if(!class_exists('WPLMS_Plugin_Actions')){

class WPLMS_Plugin_Actions{

    public static $instance;
    
    public static function init(){

        if ( is_null( self::$instance ) )
            self::$instance = new WPLMS_Plugin_Actions();

        return self::$instance;
    }

    private function __construct(){
    	
		add_action('woocommerce_order_item_name',array($this,'vibe_view_woocommerce_order_course_details'),2,100);
		add_action( 'course-cat_add_form_fields', array( $this, 'add_category_fields' ));
		add_action( 'course-cat_edit_form_fields', array( $this, 'edit_category_fields' ));
		add_action( 'created_term', array($this,'save_category_meta'), 10, 2 );
		add_action( 'edited_term', array($this,'save_category_meta'), 10, 2 );
		add_action('bp_template_content',array($this,'show_bp_error'),1);

		add_action('wp_enqueue_scripts',array($this,'script_fx'));
		add_action('wplms_before_start_course',array($this,'handle_the_token'),1);
		add_action('init',array($this,'remove_default_filters_theme'));

		add_action('template_include', array($this,'add_lmsbadge_template_page'));

    }

    function remove_default_filters_theme(){
    	if(class_exists('WPLMS_Filters')){
    		$init = WPLMS_Filters::init();
    		remove_filter('wplms_certificate_code_template_id',array($init,'wplms_get_template_id_from_certificate_code'));
			remove_filter('wplms_certificate_code_user_id',array($init,'wplms_get_user_id_from_certificate_code'));
			remove_filter('wplms_certificate_code_course_id',array($init,'wplms_get_course_id_from_certificate_code'));
			
    	}
    	if(class_exists('WPLMS_Actions')){
    		$actions = WPLMS_Actions::init();
			remove_action('woocommerce_order_item_name',array($actions,'vibe_view_woocommerce_order_course_details'),2,100);
    	}
    	if(is_wplms_4_0()){
			remove_filter('wplms_course_product_id','vibe_course_max_students_check',10,2);
		}
    }

    function handle_the_token(){
    	$token = $_POST['token'];
    	if(!empty($token) && !is_user_logged_in()){



    		$user = apply_filters('vibebp_api_get_user_from_token','',$token);
            if(!empty($user)){
            	remove_action('wplms_before_start_course','wplms_start_course_go_home');
            	wp_clear_auth_cookie();
            	
			    wp_set_current_user( $user->id, $user->user_login );

				$remember = 0;
				

				wp_set_auth_cookie( $user->id,$remember );
				
				$redirect_link = apply_filters('login_redirect','','',$user);
				do_action( 'wp_login', $user->user_login,$user );





			    $coursetaken=1;
		          $cflag=0;
		          $precourse=get_post_meta($course_id,'vibe_pre_course',true);

		        if(!empty($precourse)){
		                $pre_course_check_status = apply_filters('wplms_pre_course_check_status_filter',2);

		                if(is_numeric($precourse)){
		                    $preid=bp_course_get_user_course_status($user->ID,$precourse);
		                    if(!empty($preid) && $preid >  $pre_course_check_status){ 
		                        // COURSE STATUSES : Since version 1.8.4
		                        // 1 : START COURSE
		                        // 2 : CONTINUE COURSE
		                        // 3 : FINISH COURSE : COURSE UNDER EVALUATION
		                        // 4 : COURSE EVALUATED
		                          $cflag=1;
		                      }
		                }else if(is_array($precourse)){
		                    foreach($precourse as $pc){
		                        $preid=bp_course_get_user_course_status($user->ID,$pc);
		                        if(!empty($preid) && $preid > $pre_course_check_status){ 
		                              $cflag=1;
		                        }else{
		                            //Break from loop
		                            break;
		                        }
		                    }
		                }
		        }else{
		              $cflag=1;
		        }

		        if($cflag){
		              
		              $course_duration_parameter = apply_filters('vibe_course_duration_parameter',86400,$course_id);
		              $expire=time()+$course_duration_parameter; // One Unit logged in Limit for the course
		              setcookie('course',$course_id,$expire,'/');
		              bp_course_update_user_course_status($user->ID,$course_id,1);//Since version 1.8.4
		              do_action('wplms_start_course',$course_id,$user->ID);
		        }else{
		              
		              header('Location: ' . get_permalink($course_id) . '?error=precourse');
		              
		        }

            }
    	}


    }

    function script_fx(){
    	if(!wp_script_is('plyr') && defined('VIBEBP_PLUGIN_URL')){
  			wp_enqueue_script('plyr',VIBEBP_PLUGIN_URL.'/assets/js/plyr.js',array(),VIBEBP_VERSION,true);
  			 wp_enqueue_style('plyr',VIBEBP_PLUGIN_URL.'/assets/css/plyr.css',array(),VIBEBP_VERSION);
    	}
    	wp_enqueue_script('wplms-course-video-js',plugins_url('../../assets/js/course_video.js',__FILE__),array('wp-element','plyr'),WPLMS_PLUGIN_VERSION,true);

    	$blog_id = '';
	    if(function_exists('get_current_blog_id')){
	        $blog_id = get_current_blog_id();
	    }
    	
	    
    	if(is_singular('course')){
    		$translation_array = array( 
	    		'api'=>apply_filters('vibebp_rest_api',get_rest_url($blog_id,WPLMS_API_NAMESPACE)),
	    		'translations'=>array(
	    			'ok' => __( 'Ok ','wplms' ), 
	    		),
		      
		      
		    );
	    	wp_enqueue_script('wplms-course-progress-js',plugins_url('../../assets/js/course_progress.js',__FILE__),array('wp-element','wp-data','wp-redux-routine','wp-hooks'),WPLMS_PLUGIN_VERSION,true);
	    	wp_localize_script( 'wplms-course-progress-js', 'wplms_course_progress', $translation_array );
	    }

    	wp_enqueue_style('wplms-course-video-css',plugins_url('../../assets/css/course_video.css',__FILE__),array(),WPLMS_PLUGIN_VERSION);

    	if(is_singular('certificate') && is_wplms_4_0()){
    		wp_nonce_field('certificate_security','certificate_security');
    		if(!empty($_GET) && !empty($_GET['u'])){
    			echo '<input type="hidden" name="certificate-user" value="'.$_GET['u'].'">';
    			echo '<input type="hidden" name="certificate-course" value="'.$_GET['c'].'">';
    		}
    		if(function_exists('vibe_get_option') && !empty(vibe_get_option('offload_scripts')) || 1){
    			wp_enqueue_script('html2canvas-js',plugins_url('../../assets/js/html2canvas.min.js',__FILE__),array('wp-element'),WPLMS_PLUGIN_VERSION,true);
    			wp_enqueue_script('jspdf-plly-js',plugins_url('../../assets/js/jspdfpolyfills.umd.js',__FILE__),array('wp-element'),WPLMS_PLUGIN_VERSION,true);
    			
    			wp_enqueue_script('jspdf-js',plugins_url('../../assets/js/jspdf.umd.min.js',__FILE__),array('wp-element'),WPLMS_PLUGIN_VERSION,true);
    			wp_enqueue_style('single-certificate-css',plugins_url('../../assets/css/single-certificate.css',__FILE__),array(),WPLMS_PLUGIN_VERSION);
    			wp_enqueue_script('single-certificate-js',plugins_url('../../assets/js/single-certificate.js',__FILE__),array('wp-element','wp-data','html2canvas-js'),WPLMS_PLUGIN_VERSION,true);
    		}

    		
    	}
    	

    }

    function show_bp_error(){
		global $bp;
    	if(!empty($bp->template_message)){
        	echo '<div class="message '.$bp->template_message_type.'">'.$bp->template_message.'</div>';
    	}
	}

    function add_category_fields(){
    	$default ='';
    	if(function_exists('vibe_get_option')){
    		$default = vibe_get_option('default_avatar');
    	}
    	

    	?>
    	<div class="form-field">
    	<label><?php _e( 'Display Order', 'wplms' ); ?></label>
    	<input type="number" name="course_cat_order" id="course_cat_order" value="" />
    	</div>
    	<div class="form-field">
			<label><?php _e( 'Thumbnail', 'wplms' ); ?></label>
			<div id="course_cat_thumbnail" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url( $default ); ?>" width="60px" height="60px" /></div>
			<div style="line-height: 60px;">
				<input type="hidden" id="course_cat_thumbnail_id" name="course_cat_thumbnail_id" />
				<button type="button" class="upload_image_button button"><?php _e( 'Upload/Add image', 'wplms' ); ?></button>
				<button type="button" class="remove_image_button button"><?php _e( 'Remove image', 'wplms' ); ?></button>
			</div>
			<script type="text/javascript">
				if ( ! jQuery( '#course_cat_thumbnail_id' ).val() ) {
					jQuery( '.remove_image_button' ).hide();
				}
				// Uploading files
				var file_frame;

				jQuery( document ).on( 'click', '.upload_image_button', function( event ) {
					event.preventDefault();
					// If the media frame already exists, reopen it.
					if ( file_frame ) {
						file_frame.open();
						return;
					}

					// Create the media frame.
					file_frame = wp.media.frames.downloadable_file = wp.media({
						title: '<?php _e( "Choose an image", "vibe" ); ?>',
						button: {
							text: '<?php _e( "Use image", "vibe" ); ?>'
						},
						multiple: false
					});
					file_frame.on( 'select', function() {
						var attachment = file_frame.state().get( 'selection' ).first().toJSON();
						jQuery( '#course_cat_thumbnail_id' ).val( attachment.id );
						if( attachment.sizes){
						    if(   attachment.sizes.thumbnail !== undefined  ) url_image=attachment.sizes.thumbnail.url; 
						    else if( attachment.sizes.medium !== undefined ) url_image=attachment.sizes.medium.url;
						    else url_image=attachment.sizes.full.url;
						}

						jQuery( '#course_cat_thumbnail' ).find( 'img' ).attr( 'src', url_image );
						
						jQuery( '.remove_image_button' ).show();
					});
					file_frame.open();
				});

				jQuery( document ).on( 'click', '.remove_image_button', function() {
					jQuery( '#course_cat_thumbnail' ).find( 'img' ).attr( 'src', '<?php echo esc_js( $default ); ?>' );
					jQuery( '#course_cat_thumbnail_id' ).val( '' );
					jQuery( '.remove_image_button' ).hide();
					return false;
				});

			</script>
			<div class="clear"></div>
		</div>
		<?php
    }
    /*
    *	Edit Course Category Featured thubmanils
    *	Use WP 4.4 Term meta for storing information
    * 	@reference : WooCommerce (GPLv2)
    */
    function edit_category_fields($term){


    	$thumbnail_id = absint( get_term_meta( $term->term_id, 'course_cat_thumbnail_id', true ) );
    	$order = get_term_meta( $term->term_id, 'course_cat_order', true ); 
    	$default = '';
		if ( $thumbnail_id ) {
			$image = wp_get_attachment_thumb_url( $thumbnail_id );
		} else {
			if(function_exists('vibe_get_option')){
				$default = vibe_get_option('default_avatar');
				$image = $default;
			}
			
		}

    	?>
    	<tr class="form-field">
    		<th scope="row" valign="top"><label><?php _e( 'Display Order', 'wplms' ); ?></label></th>
			<td><input type="number" name="course_cat_order" id="course_cat_order" value="<?php echo (empty($order)?0:$order); ?>" /></td>
    	</tr>
    	<tr class="form-field">
			<th scope="row" valign="top"><label><?php _e( 'Thumbnail', 'wplms' ); ?></label></th>
			<td>
				<div id="course_cat_thumbnail" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url( $image ); ?>" width="60px" height="60px" /></div>
				<div style="line-height: 60px;">
					<input type="hidden" id="course_cat_thumbnail_id" name="course_cat_thumbnail_id" value="<?php echo vibe_sanitizer($thumbnail_id,'text'); ?>" />
					<button type="button" class="upload_image_button button"><?php _e( 'Upload/Add image', 'wplms' ); ?></button>
					<button type="button" class="remove_image_button button"><?php _e( 'Remove image', 'wplms' ); ?></button>
				</div>
				<script type="text/javascript">

					// Only show the "remove image" button when needed
					if ( '0' === jQuery( '#course_cat_thumbnail_id' ).val() ) {
						jQuery( '.remove_image_button' ).hide();
					}

					// Uploading files
					var file_frame;

					jQuery( document ).on( 'click', '.upload_image_button', function( event ) {

						event.preventDefault();

						// If the media frame already exists, reopen it.
						if ( file_frame ) {
							file_frame.open();
							return;
						}

						// Create the media frame.
						file_frame = wp.media.frames.downloadable_file = wp.media({
							title: '<?php _e( "Choose an image", "vibe" ); ?>',
							button: {
								text: '<?php _e( "Use image", "vibe" ); ?>'
							},
							multiple: false
						});

						// When an image is selected, run a callback.
						file_frame.on( 'select', function() {
							var attachment = file_frame.state().get( 'selection' ).first().toJSON();

							jQuery( '#course_cat_thumbnail_id' ).val( attachment.id );
							let url_image = '';
							if( attachment.sizes){
							    if(   attachment.sizes.thumbnail !== undefined  ) url_image=attachment.sizes.thumbnail.url; 
							    else if( attachment.sizes.medium !== undefined ) url_image=attachment.sizes.medium.url;
							    else url_image=attachment.sizes.full.url;
							}else{
								url_image = attachment.url;
							}

							jQuery( '#course_cat_thumbnail' ).find( 'img' ).attr( 'src', url_image );
							jQuery( '.remove_image_button' ).show();
						});

						// Finally, open the modal.
						file_frame.open();
					});

					jQuery( document ).on( 'click', '.remove_image_button', function() {
						jQuery( '#course_cat_thumbnail' ).find( 'img' ).attr( 'src', '<?php echo esc_js( $image ); ?>' );
						jQuery( '#course_cat_thumbnail_id' ).val( '' );
						jQuery( '.remove_image_button' ).hide();
						return false;
					});

				</script>
				<div class="clear"></div>
			</td>
		</tr>
		<?php
    }


	function save_category_meta( $term_id, $tt_id ){
		global $wpdb;
	    if( isset( $_POST['course_cat_thumbnail_id'] )){
	        $thumb_id = intval( $_POST['course_cat_thumbnail_id'] );
	        update_term_meta( $term_id, 'course_cat_thumbnail_id', $thumb_id );
	    }
	    if( isset( $_POST['course_cat_order'] ) &&is_numeric($_POST['course_cat_order'])){
	        update_term_meta( $term_id, 'course_cat_order', $_POST['course_cat_order'] );
	        $wpdb->update($wpdb->terms, array('term_group' => $_POST['course_cat_order']), array('term_id'=>$term_id));
	    }
	}

    function vibe_view_woocommerce_order_course_details($html, $item ){
    	
    	if(!empty($item['item_meta']) && !empty($item['item_meta']['_product_id'])){
    		$product_id=$item['item_meta']['_product_id'][0];
    	}
	  	if(empty($product_id)){
	  		$product_id = $item->get_product_id();
	  	}
	  	if(isset($product_id) && is_numeric($product_id)){
	      	$courses = get_post_meta($product_id,'vibe_courses',true);
	      	if(!empty($courses) && is_Array($courses)){
		        $html .= ' [ <i>'.__('COURSE : ','wplms');
	        	foreach($courses as $course){ 
	          		if(is_numeric($course)){ 
	           			$html .= '<a href="'.get_permalink($course).'"><strong><i>'.get_post_field('post_title',$course).'</i></strong></a> ';
	          		}
	        	}
	        	$html .=' </i> ]';
	      	}
	  	}
	  	return $html;

	}

	function get_course_unfinished_unit($course_id,$user_id=null){
		
		if(empty($user_id))
	    	$user_id = get_current_user_id();

	  	if(empty($user_id))
	  		return;  

	  	if(isset($_COOKIE['course'])){
	      	$coursetaken=1;
	  	}else{
	      	$coursetaken=get_user_meta($user_id,$course_id,true);      
	  	}
	  	

	  	$course_curriculum = array();
	  	if(function_exists('bp_course_get_curriculum_units'))
	    	$course_curriculum=bp_course_get_curriculum_units($course_id);	

	  	$uid='';
	  	$key = $pre_unit_key = 0;
	  	if(isset($coursetaken) && $coursetaken){
	      	if(isset($course_curriculum) && is_array($course_curriculum) && count($course_curriculum)){
	        
	        	foreach($course_curriculum as $key => $uid){
	            	$unit_id = $uid; // Only number UIDS are unit_id
	            	//Check if User has taken the Unit
	            	$unittaken=bp_course_get_user_unit_completion_time($user_id,$uid,$course_id);//
					
	            	if(!isset($unittaken) || !$unittaken){
	              		break; // If not taken, we've found the last unfinished unit.
	            	}
	        	}

	      	}else{
	          	//echo '<div class="error"><p>'.__('Course Curriculum Not Set','wplms').'</p></div>';
	          	return;
	      	}    
	  	}

	  	$units = $course_curriculum;
	  	$unit_id = apply_filters('wplms_plugin_get_course_unfinished_unit',$unit_id,$course_id);
	  	$key = apply_filters('wplms_plugin_get_course_unfinished_unit_key',$key,$unit_id,$course_id);
	  	$unitkey = $key; // USE FOR BACKUP


	  	$flag = apply_filters('wplms_skip_course_status_page',false,$course_id);
	  	if($flag && (isset($_POST['start_course']) || isset($_POST['continue_course'])) && $unitkey == 0){
	  		return $unit_id;
	  	}

	  	/*=======
	  	* NON_AJAX COURSE USECASE
	  	* PROVIDE ACCESS IF CURRENT UNIT IS COMPLETE.
	  	=======*/
	    if(function_exists('bp_course_check_unit_complete')){ 
	        if(!empty($course_id)){
	            $x = bp_course_check_unit_complete($unit_id,$user_id,$course_id);            
	        }else{
	            $x = bp_course_check_unit_complete($unit_id,$user_id);
	        }
	    
	        if($x)
	           return $unit_id;
	    } //end function exists check
	    


	  	$flag=apply_filters('wplms_next_unit_access',true,$units[$pre_unit_key]);
	  	$drip_enable= apply_filters('wplms_course_drip_switch',get_post_meta($course_id,'vibe_course_drip',true),$course_id);


	  	if(vibe_validate($drip_enable)){


	  		// BY PASS 
	  		// DRIP FOR FIRST UNIT
	  		if($key == 0){ 
	  		//SET DRIP ACCESS TIME FOR FIRST UNIT
		  		if(!empty($course_id)){
	            	$x=bp_course_get_drip_access_time($units[$key],$user_id,$course_id);
	        	}else{
	            	$x=bp_course_get_drip_access_time($units[$key],$user_id);
	        	}
	        	// SET DRIP TIME IF NOT EXISTS
	        	if(empty($x)){	
			  		if(!empty($course_id)){
		            	bp_course_update_unit_user_access_time($units[$key],$user_id,time(),$course_id);
		        	}else{
		            	bp_course_update_unit_user_access_time($units[$key],$user_id,time());
		        	}	
		        }

		  		return $unit_id;
		  	}

	  		/*=======
		  	* NON_AJAX COURSE USECASE &  RANDOM UNIT ACCESS
		  	* GET CURRENT & PREVIOUS UNIT KEY
		  	=======*/
		    for($i=($key-1);$i>=0;$i--){
		    	if(function_exists('bp_course_check_unit_complete')){

		        	//CHECK IF PRE_UNIT MARKED COMPLETE
		        	//IF YES THEN RECALCULATE CURRENT UNIT AND PREV_UNIT
		            if(!empty($course_id)){
		                $x = bp_course_check_unit_complete($units[$i],$user_id,$course_id);
		            }else{
		                $x = bp_course_check_unit_complete($units[$i],$user_id);
		            }
		            // ABOVE IS REQUIRED BECAUSE INSTRUCTOR CAN 
		            // MARK THE UNIT COMPLETE FROM THE BACKEND
		            if(!empty($x)){
		                $pre_unit_key = $i;
		                // IF PREVIOUS UNIT IS COMPLETE
		                // CHECK IF DRIP TIME EXISTS
		                if(!empty($course_id)){
			            	$x=bp_course_get_drip_access_time($units[$i],$user_id,$course_id);
			        	}else{
			            	$x=bp_course_get_drip_access_time($units[$i],$user_id);
			        	}
			        	// SET DRIP TIME IF NOT EXISTS
			        	if(empty($x)){	
			        		if(!empty($course_id)){
				            	bp_course_update_unit_user_access_time($units[$pre_unit_key],$user_id,time(),$course_id);
				        	}else{
				            	bp_course_update_unit_user_access_time($units[$pre_unit_key],$user_id,time());
				        	}	
			        	}
		                
		                
		                $unitkey = $pre_unit_key+1;
		                break;
		            }else{
		            	//IF NOT MARKED COMPELTE, 
		            	//CHECK IF PRE-UNIT DRIP ACCESS TIME EXISTS
		            	if(!empty($course_id)){
			            	$x=bp_course_get_drip_access_time($units[$i],$user_id,$course_id);
			        	}else{
			            	$x=bp_course_get_drip_access_time($units[$i],$user_id);
			        	}

			        	if(!empty($x) && ($x < time())){ // NOT SET AS FUTURE FOR DRIP ORIGIN
			                $pre_unit_key = $i; // UNIT ACCESSED BUT NOT MARKED COMPLETE
			                $unitkey = $pre_unit_key+1;
			                break;
			            }
		            }
		        }
		    }//end for
			
			//Set the NEW KEY 
			if(!empty($unitkey)){
				$key = $unitkey;	
				$unit_id = $units[$key];
			}
			
			if(empty($pre_unit_key)){
				$pre_unit_key = 0;
			}
	
	      	$drip_duration_parameter = apply_filters('vibe_drip_duration_parameter',86400,$course_id);
	      	$drip_duration = get_post_meta($course_id,'vibe_course_drip_duration',true);
	      
	      	$total_drip_duration = apply_filters('vibe_total_drip_duration',(intval($drip_duration)*intval($drip_duration_parameter)),$course_id,$unit_id,$units[$pre_unit_key]);

	      	$this->element = apply_filters('wplms_drip_feed_element_in_message',__('Unit','wplms'),$course_id);

	      	if($key > 0){

	        	if(!empty($course_id)){
	            	$pre_unit_time=bp_course_get_drip_access_time($units[$pre_unit_key],$user_id,$course_id);
	        	}else{
	            	$pre_unit_time=bp_course_get_drip_access_time($units[$pre_unit_key],$user_id);
	        	}
	        	
	        	if(!empty($pre_unit_time)){
	          
	            	$value = $pre_unit_time + $total_drip_duration;
	            
	            	$value = apply_filters('wplms_drip_value',$value,$units[$pre_unit_key],$course_id,$units[$key],$units,$user_id);
	            	
	            	if($value > time()){
	                	$flag=0;
	                	$this->value = $value;
	                	$drip_unit_v = $units[$key];
	                	add_action('wplms_before_start_course_content',function()use($drip_unit_v){
	                    	
	                    	$remaining_secs = $this->value - time();
	                    	$remaining = tofriendlytime($remaining_secs);
	                    	echo '<div class="container top30"><div class="row"><div class="col-md-9"><div class="message"><p>'.sprintf(__('Next %s will be available in %s','wplms'),$this->element,$remaining).'</p></div>'.do_shortcode('[countdown_timer event="drip_feed_end" event_detail="'.$drip_unit_v.'" seconds="'.($remaining_secs).'" size="3"]').'</div></div></div>';
	                	});
	              		return $units[$pre_unit_key];
	            	}else{

	                	if(!empty($course_id)){
	                    	$cur_unit_time=bp_course_get_drip_access_time($units[$key],$user_id,$course_id);
	                	}else{
	                    	$cur_unit_time=bp_course_get_drip_access_time($units[$key],$user_id);
	                	}

	                	
	                	if(!isset($cur_unit_time) || $cur_unit_time ==''){

	                    	if(!empty($course_id)){
	                        	bp_course_update_unit_user_access_time($units[$key],$user_id,time(),$course_id);
	                    	}else{
	                        	bp_course_update_unit_user_access_time($units[$key],$user_id,time());      
	                    	}

	                    	//Parmas : Next Unit, Next timestamp, course_id, userid
	                    	do_action('wplms_start_unit',$units[$key],$course_id,$user_id,$units[$key+1],(time()+$total_drip_duration));
	                	}
	                	
	                	return $units[$pre_unit_key];
	                	
	            	} 
	        	}else{

	            	if(isset($pre_unit_key )){

	                	if(!empty($course_id)){
	                    	$completed = bp_course_get_user_unit_completion_time($user_id,$units[$pre_unit_key],$course_id);
	                	}else{
	                    	$completed = get_user_meta($user_id,$units[$pre_unit_key],true);
	                	}
	                
	                
	                	if(!empty($completed)){
	                    	if(!empty($course_id)){
	                        	bp_course_update_unit_user_access_time($units[$pre_unit_key],$user_id,time(),$course_id);  
	                    	}else{
	                        	bp_course_update_unit_user_access_time($units[$pre_unit_key],$user_id,time());  
	                    	}
	                    
	                    	$pre_unit_time = time();
	                    	$value = $pre_unit_time + $total_drip_duration;
	                    	$value = apply_filters('wplms_drip_value',$value,$units[$pre_unit_key],$course_id,$units[$key],$units,$user_id);
	                    	
	                    	$this->value = $value-$pre_unit_time;
	                    	$drip_unit_v = $units[$key];

	                    	add_action('wplms_before_start_course_content',function()use($drip_unit_v){
	                        	echo '<div class="container top30"><div class="row"><div class="col-md-9"><div class="message"><p>'.sprintf(__('Next %s will be available in %s','wplms'),$this->element,tofriendlytime($this->value)).'</p></div>'.do_shortcode('[countdown_timer event="drip_feed_end" event_detail="'.$drip_unit_v.'" seconds="'.($this->value).'" size="3"]').'</div></div></div>';
	                    	});
	                   
	                    	return $units[$pre_unit_key];
	                	}else{
	                   		add_action('wplms_before_start_course_content',function(){
	                        	echo '<div class="container top30"><div class="row"><div class="col-md-9"><div class="message"><p>'.sprintf(__('Requested %s can not be accessed.','wplms'),$this->element).'</p></div></div></div></div>';
	                    	});
	                  
	                  		return $units[$pre_unit_key];
	                	}
	            	}else{
	            		add_action('wplms_before_start_course_content',function(){  
	                        echo '<div class="container top30"><div class="row"><div class="col-md-9"><div class="message"><p>'.sprintf(__('Requested %s can not be accessed.','wplms'),$this->element).'</p></div></div></div></div>';
	                    });
	                 
	                    return $units[$pre_unit_key];
	            	}
	            	die();
	        	} //Empty pre-unit time

	    	}
	    }  // End Drip Enable check

  
	  	if(isset($unit_id) && $flag && isset($key)){// Should Always be set 
		    if($key == 0){
		      	$unit_id =''; //Show course start if first unit has not been started
		    }else{
		      	$unit_id=$unit_id; // Last un finished unit
		    }
	  	}else{
		    if(isset($key) && $key > 0){ 
		       $unit_id=$units[($key-1)];
		    }else{
		      	$unit_id = '' ;
		    }
	  	} 
		return $unit_id;
	}

	function add_lmsbadge_template_page($original_template) {
		// print_r(get_template_directory_uri());
		// die();
		
		$file = trailingslashit(get_template_directory()) . 'archive-lmsbadge.php';
		if(is_post_type_archive('lmsbadge')) {
			if(file_exists($file)) {
				return trailingslashit(get_template_directory()).'archive-lmsbadge.php';
			} else {
				return plugin_dir_path(__DIR__) . 'templates/archive-lmsbadge.php';
			}
		} elseif(is_singular('lmsbadge')) {
			if(file_exists(get_template_directory_uri() . '/single-lmsbadge.php')) {
				return get_template_directory_uri() . '/single-lmsbadge.php';
			} else {
				return plugin_dir_path(__DIR__) . 'templates/single-lmsbadge.php';
			}
		}
		return $original_template;
	}
}

WPLMS_Plugin_Actions::init();
}
