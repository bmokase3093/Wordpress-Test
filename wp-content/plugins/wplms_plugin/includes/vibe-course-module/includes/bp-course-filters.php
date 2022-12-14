<?php
/**
 * Filter functions for Course Module
 *
 * @author      VibeThemes
 * @category    Admin
 * @package     Course Module
 * @version     2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class bp_course_filters{

    public static $instance;

    public static function init(){

        if ( is_null( self::$instance ) )
            self::$instance = new bp_course_filters();
        return self::$instance;
    }

    private function __construct(){
    	$this->course_button =[];
		add_action('wp_ajax_course_filter', array($this,'course_filter'));
		add_action('wp_ajax_nopriv_course_filter', array($this,'course_filter'));
		add_action('bp_ajax_querystring', array($this,'filtering_instructor_custom'),20,2);
		add_filter('bp_ajax_querystring', array($this,'bp_course_ajax_querystring'),20,2);
		add_filter('wplms_course_product_id', array($this,'wplms_expired_course_product_id'),10,3);


		add_filter('vibe_course_duration_parameter',array($this,'course_duration_filter'),99,2);
		add_filter('vibe_drip_duration_parameter',array($this,'drip_duration_filter'),99,2);
		add_filter('vibe_unit_duration_parameter',array($this,'unit_duration_filter'),99,2);
		add_filter('vibe_quiz_duration_parameter',array($this,'quiz_duration_filter'),99,2);
		add_filter('vibe_product_duration_parameter',array($this,'product_duration_filter'),99,2);
		add_filter('vibe_assignment_duration_parameter',array($this,'assignment_duration_filter'),99,2);

		// Apply for Course button
		add_filter('wplms_take_this_course_button_label',array($this,'apply_course_button_label'),10,2);
		add_filter('wplms_private_course_button_label',array($this,'apply_course_button_label'),10,2);
		add_filter('wplms_course_product_id',array($this,'apply_course_button_link'),10,2);
		add_filter('wplms_private_course_button',array($this,'apply_course_button_link'),10,2);
		
		add_filter('wplms_course_details_widget',array($this,'hide_price'),10,2);
        
        add_filter('wplms_drip_feed_element_in_message',array($this,'drip_feed_element'),10,2);
        add_filter('wplms_drip_value',array($this,'evaluate_course_drip'),99,6);
        add_filter('vibe_total_drip_duration',array($this,'total_drip_duration'),10,4);

        add_filter('bp_course_next_unit_access',array($this,'next_unit_access'),10,2);

        add_filter('bp_activity_user_can_delete',array($this,'student_restricted_activities'),10,2);
        
        add_filter('bp_get_course_certificate_url',array($this,'certificate_url'),10,3);
		add_filter('bp_course_certificate_class',array($this,'certificate_class'),10,2);
		//(isset($_GET['regenerate_certificate'])?'regenerate_certificate':'')
		add_filter('question_the_content',array($this,'execute_shortcode_in_questions'));
		add_filter('bp_get_profile_field_data',array($this,'check_for_array'),9,2);
		add_filter('get_comment',array($this,'avatar_on_comment_screen_for_units_fix'));
		
		//for news permalink 
		add_filter('wplms_course_nav_menu',array($this,'wplms_course_news_menu'));
		add_filter('wplms_course_locate_template',array($this,'wplms_course_news_template'),10,2);

		//wpml course count
		add_filter('bp_course_total_count',array($this,'wpml_course_count'));

		//Quiz Lock API SITE FILTER
		add_filter('bp_course_api_check_quiz_lock',array($this,'api_site_quiz_lock'),10,4);

		// Check unit/quiz access for the partially free course.
		add_filter('wplms_course_submission_tabs',array($this,'apply_course_submission_tab'),10,2);

		//course video 
		add_filter('wplms_featured_component_filter',array($this,'wplms_featured_component'),10,4);

		//fallback_for_quotes_wplms_text_correct_answer apostrophe fix
		add_filter('wplms_text_correct_answer',array($this,'fallback_for_quotes_wplms_text_correct_answer'),11,2);
		add_filter('oembed_result', array($this,'imp_custom_youtube_querystring'), 10, 3);

		add_filter('wplms_before_course_status_api',array($this,'wplms_before_course_status_api'),10,3);
		add_filter('bp_course_get_avatar',array($this,'video_thumbnail_html'),10,2);
		add_filter('wplms_curriculum_course_lesson',array($this,'course_lesson_details'),10,3);


		add_filter('bp_course_api_get_user_course_status_item',array($this,'check_partial'),10,2);
		add_filter('vibe_get_post_exceprt',array($this,'vibe_get_post_quiz_non_loggedin'),10,2);
    }
   	
   	function vibe_get_post_quiz_non_loggedin($data,$request){

   		$non = get_post_meta($request['post_id'],'vibe_non_loggedin_quiz',true);
   		if(!empty($non) && $non=='S'){
   			$data['non_logged_in'] = true;
   		}
   		return $data;
   	}

    function check_partial($return, $request ){
    	$body = json_decode($request->get_body(),true);
    	$this->user =0;
    	if(!empty($body['token'])){
        	$this->user = apply_filters('vibebp_api_get_user_from_token','',$body['token']);
    	}

        if(!empty($this->user)){
        	$user_id =$this->user->id;
        	$course_id = $request['course'];
        	$item_id = $request['id'];	
        	if(!empty($course_id) && !empty($item_id)){
        		$partial_free_course = get_post_meta($course_id,'vibe_partial_free_course',true);
        		 if( vibe_validate($partial_free_course) ){
        		 	 $check_partial_free_course_purchased = get_user_meta($user_id,'user_subscribed_to_partial_free_course_'.$course_id,true);
        		 	 if(!empty($check_partial_free_course_purchased)){
        		 	 	$allowed_units = get_post_meta($course_id,'vibe_partial_units',true);
        		 	 	if(empty($allowed_units) || !is_Array($allowed_units)){
        		 	 		$allowed_units = [];
        		 	 	}
        		 	 	if(!empty($allowed_units)){
        		 	 		if(!in_array($item_id, $allowed_units)){
        		 	 			$return['content'] = '<div class="vbp_message error">'._x('Unit is not available. Please purchase full course.','','wplms').'</div><style>.course_button.full.button.paid_course.is-loading{ pointer-events: all !important;color:var(--text)!important;} .course_button.full.button.paid_course.is-loading:after { display:none; }</style>';
		        		 	 	ob_start();
		        		 	 	the_course_button($course_id,true);
		        		 	 	$return['content'] .= ob_get_clean();
		        		 	 	$return['meta'] = array();
        		 	 		}
        		 	 	}
        		 	 	
        		 	 }
        		 }
            
        	}

        }

        return $return;
    }

    function course_lesson_details($title,$unit_id,$course_id =null){ 
    	if(empty($course_id)){
    		global $post;
    		if($post->post_type == 'course'){
    			$course_id = get_the_ID();
    		}else{
    			return $title;
    		}
    	}
    	if(empty($this->check_course_lesson)){
    		$this->check_course_lesson = get_post_meta($course_id,'vibe_course_unit_content',true);
    		if(vibe_validate($this->check_course_lesson)){
    			add_action('wp_footer',array($this,'print_css_adjustments'));
    		}
    	}
    	
    	if(vibe_validate($this->check_course_lesson)){
    		$title .= ' <a class="curriculum_unit_popup link" data-id="'.$unit_id.'" data-course="'.$course_id.'">'._x('Details','Unit details link anchor for full unit content','wplms').'</a>';
    	}
    	return $title;
    }
    function print_css_adjustments(){ 
    	
    	


    	if(function_exists('is_wplms_4_0') && is_wplms_4_0()){
    		$data = WPLMS_Course_Component_Init::init();
    		wp_enqueue_script('unitpopup',plugins_url('../../../assets/js/unitpopup.js',__FILE__),array('wp-element','wp-data'),WPLMS_PLUGIN_VERSION,true);
       		wp_localize_script('unitpopup','wplms_course_data',apply_filters('wplms_course_button_script_args',$data->get_wplms_course_data())); 
       		 wp_enqueue_style('wplms-cc',plugins_url('../../../assets/css/wplms.css',__FILE__),array(),WPLMS_PLUGIN_VERSION);
    		?>
    		<style>
    		ul.course_curriculum li.course_lesson .item_title{
			    display:flex;
			    flex-direction:column;
			}
			ul.course_curriculum li.course_lesson .item_title a{
			    margin-top:0.3rem;
			}
			</style>
			<?php
    	}else{

    	
	    	?>
			<style>.course_curriculum .course_lesson{padding-bottom:24px;}.curriculum_unit_popup{position:absolute;}</style>
			<?php
		}
	}
    function video_thumbnail_html($thumb,$args){
    	$defaults = array(
		'id' => get_the_ID(),
		'size'  => 'full'
		);
    	$r = wp_parse_args( $args, $defaults );
		extract($r, EXTR_SKIP );
		$post_link = get_permalink($id);
		$post_class = '';
		$more_html = '';
		$course_video = get_post_meta($id,'post_video',true);


		$type = '';

		if(!empty($course_video)){



			if(is_array($course_video)){
				if(empty($course_video['url'])){
					return $thumb;
				}
				if(!empty($course_video['type'])){
					$type = $course_video['type'];
				}
				
				$post_link = $course_video['url'];
			}else{
				$post_link = $course_video;
			}

			if(empty($type) && !empty($post_link)){
				$type = $this->videoType($post_link);
			}

			$post_class = 'course_video_popup';
			$more_html = '<span class="icon_wrapper"></span>';
		}
		if(has_post_thumbnail($id)){
			$thumb='<a class="'.$post_class.'" type="'.$type.'" href="'.$post_link.'" title="'.the_title_attribute('echo=0').'">'.get_the_post_thumbnail($id,$size).$more_html.'</a>';
		}else{
			$default_course_avatar = vibe_get_option('default_course_avatar');
			if(isset($default_course_avatar) && $default_course_avatar){
				$thumb='<a class="'.$post_class.'"  href="'.$post_link.'" title="'.the_title_attribute('echo=0').'"><img src="'.$default_course_avatar.'" />'.$more_html.'</a>';
			}
		}
		return $thumb;
    }

     /* ==== Apply for Course === */
    function apply_course_button_label($label,$course_id){
    	if( empty($this->course_button[$course_id])){
    		$check = get_post_meta($course_id,'vibe_course_apply',true);
    		$this->course_button[$course_id] = $check;
    	}
    	
    	if(!empty($this->course_button[$course_id]) && vibe_validate($this->course_button[$course_id])){
    		
    		$label = _x('Apply for Course','Apply for Course label for course','wplms');
    		$user_id = get_current_user_id();
    		$check1 = get_user_meta($user_id,'apply_course'.$course_id,true);
    		
    		if( !empty($check1) ){
    			$label = _x('Applied for Course','Apply for Course label for course','wplms');
    			add_action('wp_footer',array($this,'remove_apply_for_course_id'));
    		}
    	}
    	return $label;
    }

    function apply_course_button_link($link,$course_id){

    	if(empty($this->course_button) && empty($this->course_button[$course_id])){
    		$check = get_post_meta($course_id,'vibe_course_apply',true);
    		$this->course_button[$course_id] = $check;
    	}
    	if(!empty($this->course_button[$course_id]) && vibe_validate($this->course_button[$course_id])){
    		if(!is_user_logged_in()){
    			$link = '?error=login#applycourse';
    		}else{
    			$link = '#" id="apply_course_button" data-id="'.$course_id.'" data-security="'.wp_create_nonce('security'.$course_id);
    		}
    	}
    	return $link;
    }

    function course_filter(){
		

		global $bp;
		$args=array('post_type' => BP_COURSE_CPT);
		if(isset($_POST['filter'])){
			$filter = $_POST['filter'];
			switch($filter){
				case 'popular':
					$args['orderby'] = 'meta_value_num';
					$args['meta_key'] = 'vibe_students';
				break;
				case 'newest':
					$args['orderby'] = 'date';
				break;
				case 'rated':
					$args['orderby'] = 'meta_value_num';
					$args['meta_key'] = 'average_rating';
				break;
				case 'alphabetical':
					$args['orderby'] = 'title';
					$args['order'] = 'ASC';
				break;
				case 'start_date':
					$args['orderby'] = 'meta_value';
					$args['meta_key'] = 'vibe_start_date';
					$args['meta_type'] = 'DATE';
					$args['order'] = 'ASC';
					if(empty($order['meta_query'])){
						$args['meta_query']=array(array(
						'key' => 'vibe_start_date',
						'value' => current_time('mysql'),
						'compare'=>'>='  
						));
					}
				break;
				case 'pursuing':
					if(is_user_logged_in() && function_exists('bp_course_get_user_courses')){
						$user_id = get_current_user_id();
						$courses = bp_course_get_user_courses($user_id,'start_course');
						$args['post__in'] = $courses;
					}
				break;
				case 'finished':
					if(is_user_logged_in()){
						$user_id = get_current_user_id();
						$courses = bp_course_get_user_courses($user_id,'course_evaluated');
						$args['post__in'] = $courses;
					}
				break;
				case 'active':
					if(is_user_logged_in()){
						$user_id = get_current_user_id();
						$courses = bp_course_get_user_courses($user_id,'active');
						$args['post__in'] = $courses;
					}
				break;
				case 'expired':
					if(is_user_logged_in()){
						$user_id = get_current_user_id();
						$courses = bp_course_get_user_courses($user_id,'expired');
						$args['post__in'] = $courses;
					}
				break;
				case 'draft':
					$args['post_status'] = 'draft';
				break;
				case 'pending':
					$args['post_status'] = 'pending';
				break;
				case 'published':
					$args['post_status'] = 'publish';
				break;
				default:
					$args['orderby'] = apply_filters('wplms_custom_order_in_course_filter','');
				break;
			}
		}

		if(isset($_POST['search_terms']) && $_POST['search_terms'])
			$args['search_terms'] = $_POST['search_terms'];

		if(isset($_POST['page']))
			$args['paged'] = $_POST['page'];

		if(isset($_POST['scope']) && $_POST['scope'] == 'personal'){
			$uid=get_current_user_id();
			if(empty($args['meta_query'])){
				$args['meta_query'] = array(
					'relation'=>'AND',
					array(
						'key' => $uid,
						'compare' => 'EXISTS'
					)
				);
			}else{
				$args['meta_query'][] = array(
					'key' => $uid,
					'compare' => 'EXISTS'
				);
			}
			
		}

		if(isset($_POST['scope']) && $_POST['scope'] == 'instructor'){
			$uid=get_current_user_id();
			$args['instructor'] = $uid;
		}

		if(isset($_POST['extras'])){

			$extras = json_decode(stripslashes($_POST['extras']));
			$course_categories=array();
			$course_levels=array();
			$course_location=array();
			$type=array();
			if(is_array($extras)){
				foreach($extras as $extra){
					switch($extra->type){
						case 'course-cat':
							$course_categories[]=$extra->value;
						break;
						case 'free':
							$type=$extra->value;
						break;
						case 'offline':
							$offline=$extra->value;
						break;
						case 'instructor':
							$instructors[]=$extra->value;
						break;
						case 'level':
							$course_levels[]=$extra->value;
						break;
						case 'location':
							$course_location[]=$extra->value;
						break;
						case 'start_date':
							$start_date = $extra->value;;
						break;
						case 'end_date':
							$end_date = $extra->value;;
						break;
					}
				}
			}
			
			$args['tax_query']=array();
			if(count($course_categories)){
				$args['tax_query']['relation'] = 'AND';
				$args['tax_query'][]=array(
									'taxonomy' => 'course-cat',
									'terms'    => $course_categories,
									'field'    => 'slug',
								);
			}
			if(isset($instructors) && count($instructors)){
				$args['author__in']=$instructors;
			}
			if($type){
				switch($type){
					case 'free':
					$args['meta_query']['relation'] = 'AND';
					$args['meta_query'][]=array(
						'key' => 'vibe_course_free',
						'value' => 'S',
						'compare'=>'='
					);
					break;
					case 'paid':
					$args['meta_query']['relation'] = 'AND';
					$args['meta_query'][]=array(
						'key' => 'vibe_course_free',
						'value' => 'H',
						'compare'=>'='
					);
					break;
				}
			}

			if(isset($offline) && $offline){
				switch($offline){
					case 'S':
					$args['meta_query']['relation'] = 'AND';
					$args['meta_query'][]=array(
						'key' => 'vibe_course_offline',
						'value' => 'S',
						'compare'=>'='
					);
					break;
					case 'H':
					$args['meta_query']['relation'] = 'AND';
					$args['meta_query'][]=array(
						'key' => 'vibe_course_offline',
						'value' => 'S',
						'compare'=>'!='
					);
					break;
				}
			}
			if(!empty($start_date)){
				$args['meta_query']['relation'] = 'AND';
				$args['meta_query'][]=array(
					'key' => 'vibe_start_date',
					'value' => $start_date,
					'compare'=>'>='  
				);
			}
			if(!empty($end_date)){
				$args['meta_query']['relation'] = 'AND';
				$args['meta_query'][]=array(
					'key' => 'vibe_start_date',
					'value' => $end_date,
					'compare'=>'<='
				);
			}
			if(count($course_levels)){
				$args['tax_query']['relation'] = 'AND';
				$args['tax_query'][]=array(
										'taxonomy' => 'level',
										'field'    => 'slug',
										'terms'    => $course_levels,
									);
			}
			if(count($course_location)){
				$args['tax_query']['relation'] = 'AND';
				$args['tax_query'][]=array(
										'taxonomy' => 'location',
										'field'    => 'slug',
										'terms'    => $course_location,
									);
			}

		}

	$loop_number=vibe_get_option('loop_number');
	isset($loop_number)?$loop_number:$loop_number=5;

	$args['per_page'] = $loop_number;
	if(in_array($filter,array('pursuing','finished','expired','active')) && empty($args['post__in'])){
		echo '<div class="message">'._x('No courses found !','No courses in current filter','wplms').'</div>';
		die();
	}
	?>

	<?php do_action( 'bp_before_course_loop' ); ?>

	<?php 
	if ( bp_course_has_items( $args ) ) : ?>

		<div id="pag-top" class="pagination ">

			<div class="pag-count" id="course-dir-count-top">

				<?php bp_course_pagination_count(); ?>

			</div>

			<div class="pagination-links" id="course-dir-pag-top">

				<?php bp_course_item_pagination(); ?>

			</div>

		</div>

		<?php do_action( 'bp_before_directory_course_list' );
			$class = '';
			$cookie=urldecode($_POST['cookie']);
			if(stripos($cookie,'bp-course-list=grid')){
				$class='grid';
			}
		?>
		<ul id="course-list" class="item-list <?php echo apply_filters('wplms_course_directory_list',$class); ?>" role="main">

		<?php while ( bp_course_has_items() ) : bp_course_the_item(); ?>

				<?php 
				global $post;
				$cache_duration = vibe_get_option('cache_duration'); if(!isset($cache_duration)) $cache_duration=86400;
				if($cache_duration){
					$course_key= 'course_'.$post->ID;
					if(is_user_logged_in()){
						$user_id = get_current_user_id();
						$user_meta = get_user_meta($user_id,$post->ID,true);
						if(isset($user_meta)){
							$course_key= 'course_'.$user_id.'_'.get_the_ID();
						}
					}
					$result = wp_cache_get($course_key,'course_loop');
				}else{$result=false;}

				if ( false === $result) {
					ob_start();
					bp_course_item_view();
					$result = ob_get_clean();
				}
				if($cache_duration)
				wp_cache_set( $course_key,$result,'course_loop',$cache_duration);
				echo $result;
				?>

		<?php endwhile; ?>

		</ul>

		<?php do_action( 'bp_after_directory_course_list' ); ?>

		<div id="pag-bottom" class="pagination">

			<div class="pag-count" id="course-dir-count-bottom">

				<?php bp_course_pagination_count(); ?>

			</div>

			<div class="pagination-links" id="course-dir-pag-bottom">

				<?php bp_course_item_pagination(); ?>

			</div>

		</div>

	<?php else: ?>

		<div id="message" class="info">
			<p><?php _e( 'No Courses found.', 'wplms' ); ?></p>
		</div>

	<?php endif;  ?>


	<?php do_action( 'bp_after_course_loop' ); ?>
	<?php

		die();
	}
    
     function apply_course_submission_tab($tabs,$course_id){
    	if(empty($this->course_button) && empty($this->course_button[$course_id])){
    		$check = get_post_meta($course_id,'vibe_course_apply',true);
    		$this->course_button[$course_id] = $check;
    	}
    	if(vibe_validate($this->course_button[$course_id])){
    		$tabs['applications'] = sprintf(_x('Applications <span>%d</span>','Apply for course applicants in Course - admin - Submissions','wplms'),bp_course_get_course_applicants_count($course_id));
    	}
    	return $tabs;
    }
    
    function wplms_before_course_status_api($stop,$course_id,$user_id){
    	$precourse=get_post_meta($course_id,'vibe_pre_course',true);
    	$cflag=0;
      	if(!empty($precourse)){
            $pre_course_check_status = apply_filters('wplms_pre_course_check_status_filter',2);

            if(is_numeric($precourse)){
                $preid=bp_course_get_user_course_status($user_id,$precourse);
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
                    $preid=bp_course_get_user_course_status($user_id,$pc);
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

      	if(!$cflag){
          	$stop = array('icon'=>'vicon-lock','error_code'=>'precourse','error_message'=>_x('Please complete all the pre-requisite courses first','','wplms'));
      	}

    	return $stop;
    }

    function imp_custom_youtube_querystring( $html, $url, $args ) {
		if(strpos($html, 'youtube')!= FALSE || strpos($html, 'wistia')!= FALSE || strpos($html, 'vimeo')!= FALSE) {
			$html = do_shortcode('<div class="fitvids">'.$html.'</div>');
		}
		return $html;
	}

    function fallback_for_quotes_wplms_text_correct_answer($str_lowered_c_answer,$c_answer){

    	if(strpos( $str_lowered_c_answer,'&#039;') !== false){
    		$str_lowered_c_answer = str_replace('&#039;', '\'', $str_lowered_c_answer);
    	}
    	if(strpos( $str_lowered_c_answer,'&quot;') !== false){
    		$str_lowered_c_answer = str_replace('&quot;', '"', $str_lowered_c_answer);
    	}
    	
    	$str_lowered_c_answer = stripcslashes($str_lowered_c_answer);
    	
	  	return $str_lowered_c_answer;
	}

	function videoType($url) {
	    if (strpos($url, 'youtube') > 0) {
	        return 'youtube';
	    } elseif (strpos($url, 'vimeo') > 0) {
	        return 'vimeo';
	    } else {
	        return 'video';
	    }
	}

    function wplms_featured_component($custom_post_thumbnail,$custom_post_id,$cols,$style){
    	$course_video = get_post_meta($custom_post_id,'post_video',true);
    	$type = '';
		if(!empty($course_video)){
			if(is_array($course_video)){
				if(empty($course_video['url'])){
					return $custom_post_thumbnail;
				}
				if(!empty($course_video['type'])){
					$type = $course_video['type'];
				}
				
				$post_link = $course_video['url'];
			}else{
				$post_link = $course_video;
			}

			if(empty($type) && !empty($post_link)){
				$type = $this->videoType($post_link);
			}

			
			$post_class = 'course_video_popup';
			$more_html = '<span class="icon_wrapper"></span>';
			$custom_post_thumbnail = '';
		    $default_image = vibe_get_option('default_course_avatar');
		    if(!in_array($cols,array('big','small','medium','mini','full'))){
		        switch($cols){
		          case '2':{ $cols = 'big';
		          break;}
		          case '3':{ $cols = 'medium';
		          break;}
		          case '4':{ $cols = 'medium';
		          break;}
		          case '5':{ $cols = 'small';
		          break;}
		          case '6':{ $cols = 'small';
		          break;}  
		          default:{ $cols = 'full';
		          break;}
		        }
		    }
		    
		    if(has_post_thumbnail($custom_post_id)){
		        $custom_post_thumbnail=  '<a class="'.$post_class.'" href="'.$post_link.'" type="'.$type.'">'.get_the_post_thumbnail($custom_post_id,$cols).$more_html.'</a>';
		    }else if(isset($default_image) && $default_image){
		    	$custom_post_thumbnail=  '<a class="'.$post_class.'" href="'.$post_link.'">'.'<img src="'.$default_image.'" />'.$more_html.'</a>';
		    }
		        
		}               
	    return $custom_post_thumbnail; 
    }
    
    // API Site Quiz lock
    function api_site_quiz_lock($flag,$quiz_id,$user_id,$type){
    	if(!empty($user_id)){
	        $status = bp_course_get_user_quiz_status($user_id,$quiz_id);
	    }
	    if($status  >= 3){
	    	delete_user_meta($user_id,'quiz_lock_'.$quiz_id);
	    	return true;
	    }
    	$quiz_lock = bp_course_get_setting( 'quiz_lock', 'api','boolean' );
    	if($quiz_lock){
    		$check = get_user_meta($user_id,'quiz_lock_'.$quiz_id,true);
    		if(empty($check)){
    			$duration = bp_course_get_quiz_duration($quiz_id);
    			update_user_meta($user_id,'quiz_lock_'.$quiz_id,array('type'=>$type,'expires'=>time()+$duration));
    		}else{
    			if($check['type'] != $type && $check['expires'] < time()){
    				return false;
    			}
    		}
    	}

    	return $flag;
    }

    function wpml_course_count($count_course){
    	global $wpdb;
    	if(defined('ICL_LANGUAGE_CODE') && ICL_LANGUAGE_CODE){
    		if(function_exists('vibe_get_option')){
    			$hidden_courses = vibe_get_option('hide_courses');
    		}
    		if(!empty($hidden_courses)){
    			$h_courses = array();
    			foreach ($hidden_courses as $key => $value) {
    				$h_courses[] = "'".$value."'";
    			}
    			$h_courses = implode(",",$h_courses);
    			$h_courses = (empty($h_courses)?"":"AND ID NOT IN({$h_courses})");
    		}
    		global $wpdb;
    		$table = $wpdb->prefix.'icl_translations';
    	
    		$count = $wpdb->get_var($wpdb->prepare("
    		SELECT COUNT(DISTINCT(p.ID))
			FROM {$wpdb->posts} as p
			INNER JOIN {$table} as icl
			ON p.ID = icl.element_id AND p.post_type = 'course' AND icl.language_code = %s AND p.post_status = 'publish' $h_courses",ICL_LANGUAGE_CODE));
			if(empty($count))$count=0;
			$count_course = $count;
    	}
    	return $count_course;
    }

    function wplms_course_news_menu($links){

		if(function_exists('vibe_get_option')){
			$show_news = vibe_get_option('show_news');
			if(!empty($show_news)){
				if(class_exists('Vibe_CustomTypes_Permalinks')){
					$p = Vibe_CustomTypes_Permalinks::init();
		    		$permalinks = $p->permalinks;
		    		$slug = '';
					if(!empty($permalinks) && !empty($permalinks['news_slug'])){
						$slug = str_replace('/', '', $permalinks['news_slug']);
					}else{
						$slug = _x('news','news permalink in course','wplms');
					}
					$links['news'] = array(
	                        'id' => 'news',
	                        'label'=>__('News','wplms'),
	                        'action' => $slug,
	                        'link'=>bp_get_course_permalink(),
	                    );
				}
			}
		}
		return $links;
	}

	function wplms_course_news_template($template,$action){
		if(function_exists('vibe_get_option')){
			$show_news = vibe_get_option('show_news');
			if(!empty($show_news)){
		      	if($action == 'news'){ 
		          $template= array(get_template_directory('course/single/plugins.php'));
		      	}
		  	}
		}
      return $template;
    }

    function avatar_on_comment_screen_for_units_fix($comment){
	    if(is_admin()){
	      if(function_exists('get_current_screen')){
	        $hook = get_current_screen();
	        if(!empty($hook->base) && $hook->base == 'edit-comments'){
	          if(!empty($comment->comment_type)){
	            unset($comment->comment_type);
	          }
	          return $comment; 
	        }
	      }
	    }
	    return $comment;
	}

    function check_for_array($field,$args){
    	if(!empty($field) && is_array($field))
    		$field = implode(',',$field);
    	return $field;
    }
    function execute_shortcode_in_questions($q){
    	return do_shortcode($q);
    }
    
    function certificate_url($url,$course_id,$user_id){

    	if(!empty($url) || empty($course_id) || empty($user_id) || isset($_GET['regenerate_certificate'])){

    		return $url;
    	}
    	

    	global $wpdb;

    	$att = $wpdb->get_row($wpdb->prepare("SELECT ID,post_author FROM {$wpdb->posts} WHERE post_type = 'attachment' AND post_parent = %d AND post_name = %s",$course_id,'certificate_'.$course_id.'_'.$user_id.''),ARRAY_A);
    	

    	if(!empty($att)){

    		//Certificate can be uploaded via Course instructor and User
	    	$course_author = get_post_field('post_author',$course_id);
	    	$authors=array($course_author);
			$certificate_authors = apply_filters('wplms_course_instructors',$authors,$course_id);
			$certificate_authors[] = $user_id;


    		$attachment_id = $att['ID'];

    		if( (in_array($att['post_author'],$certificate_authors) || user_can($att['post_author'],'manage_options'))){

	    		$attachment = wp_get_attachment_image_src($attachment_id,'full');
		    	$url = $attachment[0];
		    	if(!empty($url)){$this->certificate_url[$course_id.'-'.$user_id]=$url;}

	    	}
    	} 

    	return $url;
    }

    function certificate_class($class,$args){

    	extract($args);
    	if(empty($course_id) || empty($user_id))
    		return;

    	if(!empty($this->certificate_url[$course_id.'-'.$user_id])){
    		$class .=' certificate_image';
    	}

    	return $class;
    }

    function student_restricted_activities($flag, $activity){
    	
    	if(!current_user_can('edit_posts') && (isset($activity->component) && $activity->component == 'course')){ 
    	// Student user role
    		$restricted_activites = array('subscribe_course','start_course','submit_course','retake_course','reset_course','student_badge','student_certificate','review_course','course_code','renew_course','unit',
    			'unit_complete','unit_comment','start_quiz','submit_quiz','quiz_evaluated','retake_quiz','reset_quiz','assignment_started','assignment_submitted','evaluate_assignment','reset_assignment');	
    		if(in_array($activity->type,$restricted_activites)){
    			return false;
    		}
    	}

    	return $flag;
    }

	function next_unit_access($next_unit_access,$course_id){ 
		$next_unit_access = true;
		if(function_exists('vibe_get_option')){
			$nextunit_access = vibe_get_option('nextunit_access');	
			if(!empty($nextunit_access)){
				$next_unit_access = false;
			}
		}
		$nextunit_access = get_post_meta($course_id,'vibe_course_prev_unit_quiz_lock',true);

		if(!empty($nextunit_access)){
			if(function_exists('vibe_validate') && vibe_validate($nextunit_access)){
				$next_unit_access = false;
			}else{
				$next_unit_access = true;
			}
		}
		return $next_unit_access;
	}

	function filtering_instructor_custom($args=false,$object=false){
	 	//list of users to exclude
	 	if($object!='members')//hide for members only
		 return $args;

		$qs = $args; 
		$args = wp_parse_args( $args ); 

		 if(!isset($args['scope']) || $args['scope'] != 'instructors')
		 	return $qs;
		 
		 $args=array('role' => 'Instructor','fields' => 'ID');
		 $users = new WP_User_Query($args);

		 $included_user = implode(',',$users->results);
		 //$included_user='1,2,3';//comma separated ids of users whom you want to exclude
		 
		 $args=wp_parse_args($qs);
		 
		 //check if we are searching  or we are listing friends?, do not exclude in this case
		 if(!empty($args['user_id'])||!empty($args['search_terms']))
		 return $qs;
		 
		 if(!empty($args['include']))
		 $args['include']=$args['include'].','.$included_user;
		 else
		 $args['include']=$included_user;

		 $qs=build_query($args);

		 return $qs;
	}

	function bp_course_ajax_querystring($string,$object){

		if(function_exists('vibe_get_option'))
			$loop_number=vibe_get_option('loop_number');
		
		if(!isset($loop_number) || !is_numeric($loop_number))
			$loop_number=5;

		$appended = '&per_page='.$loop_number;
		if($object == 'activity'){
			$appended = apply_filters('wplms_activity_loop',$appended);
			if(is_singular('course')){
				$appended .='&primary_id='.get_the_ID();
				?>
				<script>
				jQuery(document).ready(function($){
					$.cookie('bp-activity-course', <?php echo get_the_ID(); ?>, { expires: 1 ,path: '/'});
					<?php
					if(!empty($_REQUEST['student'])){ 
						$appended .='&user_id='.$_REQUEST['student'];
						?>
						$.cookie('bp-activity-student', <?php echo $_REQUEST['student']; ?>, { expires: 1 ,path: '/'});
						<?php
					}
					?>
				});
				</script>
				<?php
			}else if(is_page() || bp_is_group()){
				?>
				<script>
				jQuery(document).ready(function($){
					$.cookie('bp-activity-course', null, { path: '/' });
				});
				</script>
				<?php
			}
		}
		
		if(!empty($_POST) && !empty($_POST['cookie'])){

			preg_match("/[.+]?bp-activity-course...([0-9]*)/", $_POST['cookie'], $matches);
			
			if(!empty($matches) && !empty($matches[1]) && is_numeric($matches[1])){
				$post_type = get_post_field('post_type',$matches[1]);
				if($post_type == 'course'){
					$appended .='&primary_id='.$matches[1];

					preg_match("/[.+]?bp-activity-student...([0-9]*)/", $_POST['cookie'], $student_matches);
					if(!empty($student_matches) && !empty($student_matches[1]) && is_numeric($student_matches[1])){
						global $wpdb;
					    $count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $wpdb->users WHERE ID = %d", $student_matches[1]));
					    if($count){ 
					    	$appended .='&user_id='.$student_matches[1];
					    }
					}
				}
			}
		}

		$string .=$appended;

		if($object != BP_COURSE_SLUG)
			return $string;

		global $bp; 
		$course_activity = '';
		if(is_singular('course')){
			global $post;
			$course_activity .='&primary_id='.$post->ID;
			if(isset($_GET['student']) && $_GET['student'] && is_numeric($_GET['student']))
				$course_activity .= '&user_id='.$_GET['student'];

			$string .=$course_activity;
		}
		
		if(!empty($_COOKIE)){
			if(!empty($_COOKIE["bp-course-filter"]))
				$course_filters = $_COOKIE["bp-course-filter"];
			if(!empty($_COOKIE["bp-course-extras"]))
				$course_extras=$_COOKIE["bp-course-extras"];
			if(!empty($_COOKIE["bp-course-scope"]))
				$course_scope=$_COOKIE["bp-course-scope"];

			if(isset($course_filters)){
				$string.='&filters='.$course_filters;
			}

			if(isset($course_extras)){
				$string.='&extras='.$course_extras;
			}
			if(isset($course_scope)){
				$string.='&scope='.$course_scope;
			}
		}
		return $string;
	}

	function wplms_expired_course_product_id($pid,$course_id,$status){
		if($status == -1){ // Expired course
			$free = get_post_meta($course_id,'vibe_course_free',true);
			if(vibe_validate($free)){
				$pid = get_permalink($course_id).'?renew';
			}
		}
		return $pid;
	}

	function course_duration_filter($duration,$course_id = NULL){
    	if(empty($course_id)){
    		global $post;
    		if(is_object($post))
    			$course_id = $post->ID;
    	}
    	
		$parameter = get_post_meta($course_id,'vibe_course_duration_parameter',true);
		if(!empty($parameter) && is_numeric($parameter))
			return $parameter;
	
    	return $duration;
    }

    function drip_duration_filter($duration,$course_id = NULL){
    	
    	if(empty($course_id)){
    		global $post;
    		if(is_object($post))
    			$course_id = $post->ID;
    	}
    	$parameter = get_post_meta($course_id,'vibe_drip_duration_parameter',true);
    	if(!empty($parameter)){
    		update_post_meta($course_id,'vibe_course_drip_duration_parameter',$parameter);
    		delete_post_meta($course_id,'vibe_drip_duration_parameter');
    	}else{
    		$parameter = get_post_meta($course_id,'vibe_course_drip_duration_parameter',true);
    	}
		
		if(!empty($parameter) && is_numeric($parameter))
			return $parameter;
    	
    	return $duration;
    }

    function unit_duration_filter($duration,$unit_id = NULL){
    	
    	if(empty($unit_id)){
    		global $post;
    		if(is_object($post))
    			$unit_id = $post->ID;
    	}
   
		$parameter = get_post_meta($unit_id,'vibe_unit_duration_parameter',true);
		if(!empty($parameter) && is_numeric($parameter))
			return $parameter;
    	
    	return $duration;
    }

    function quiz_duration_filter($duration,$quiz_id = NULL){
    	
    	if(empty($quiz_id)){
    		global $post;
    		if(is_object($post))
    			$quiz_id = $post->ID;
    	}

		$parameter = get_post_meta($quiz_id,'vibe_quiz_duration_parameter',true);
		if(!empty($parameter) && is_numeric($parameter))
			return $parameter;
    	
    	return $duration;
    }

    function product_duration_filter($duration,$product_id = NULL){
    	
    	if(empty($product_id)){
    		global $post;
    		if(is_object($post))
    			$product_id = $post->ID;
    	}

		$parameter = get_post_meta($product_id,'vibe_product_duration_parameter',true);
		if(empty($parameter)){
			$parameter = get_post_meta($product_id,'vibe_duration_parameter',true);
			if(!empty($parameter))
				update_post_meta($product_id,'vibe_product_duration_parameter',$parameter);
		}
		if(!empty($parameter) && is_numeric($parameter))
			return $parameter;
    	
    	return $duration;
    }

    function assignment_duration_filter($duration,$assignment_id = NULL){
    	if(empty($assignment_id)){
    		global $post;
    		if(is_object($post))
    			$assignment_id = $post->ID;
    	}
    	
    	$parameter = get_post_meta($assignment_id,'vibe_assignment_duration_parameter',true);
    	if(!empty($parameter) && is_numeric($parameter))
    		return $parameter;
    	
    	return $duration;
    }

    function remove_apply_for_course_id(){
    	?>
    	<script>
			jQuery(document).ready(function($){
				$('body').find('#apply_course_button').removeAttr('id');
			});
		</script>
		<?php
    }

    function hide_price($details,$course_id){
    	if(empty($this->course_button) && empty($this->course_button[$course_id])){
    		$check = get_post_meta($course_id,'vibe_course_apply',true);
    		$this->course_button[$course_id] = $check;
    	}
    	if(vibe_validate($this->course_button[$course_id])){
    		unset($details['price']);
    	}
    	return $details;
    }

    function drip_feed_element($element,$course_id){
    	$course_section_drip = get_post_meta($course_id,'vibe_course_section_drip',true);
    	if(function_exists('vibe_validate') && vibe_validate($course_section_drip)){
    		$element = __('Section','wplms');
    	}
    	return $element;
    }

    function evaluate_course_drip($value,$pre_unit_id,$course_id,$unit_id,$units,$user_id=null){

    	$course_section_drip = get_post_meta($course_id,'vibe_course_section_drip',true);
    	$course_drip_duration_type = get_post_meta($course_id,'vibe_course_drip_duration_type',true);

    	if(function_exists('vibe_validate') && vibe_validate($course_section_drip)){
    		if(empty($user_id)){
    			$user_id = get_current_user_id();
    		}
			
			$drip_duration = (int)get_post_meta($course_id,'vibe_course_drip_duration',true);
			$drip_duration_parameter = (int)apply_filters('vibe_drip_duration_parameter',86400,$course_id);
			$total_drip_duration = $drip_duration*$drip_duration_parameter;
			
			$curriculum= bp_course_get_curriculum($course_id); 
			if(is_array($curriculum)){
				$key = array_search($unit_id,$curriculum);
				if(!isset($key) || !$key)
					return $value;
				//GET Previous Two Sections
				$i=$key;
				
				while($i>=0){
					if(!is_numeric($curriculum[$i])){
						if(!isset($k2)){
							$k2 = $i;
						}else if(!isset($k1)){
							$k1 = $i;
						}
					}
					$i--;
				}
				
				//First section incomplete
				if(!isset($k2) || !isset($k1) || !$k2 || $k1 == $k2 || $k2<$k1)
					return 0;

				//Get first unit in previous section
				for($i=$k1;$i<=$k2;$i++){
					if(is_numeric($curriculum[$i]) && bp_course_get_post_type($curriculum[$i]) == 'unit') 
						break;
				}

				if($i == $k2){
					return 0; // section drip feed disabled if a section has all quizzes
				}

				$start_section_timestamp=bp_course_get_drip_access_time($curriculum[$i],$user_id,$course_id);
				if(empty($start_section_timestamp)){
					$start_section_timestamp = bp_course_get_user_unit_completion_time($user_id,$curriculum[$i],$course_id);
					
					 // If access time not present check the unit completion time.
					if(empty($start_section_timestamp)){ // If completion time not present set the access time as current timestamp.

						$start_section_timestamp = time();
						bp_course_update_unit_user_access_time($curriculum[$i],$user_id,$start_section_timestamp,$course_id);
					}
				}

				if(vibe_validate($course_drip_duration_type)){
					$total_drip_duration = 0;
					for($i=$k1;$i<=$k2;$i++){
						if(is_numeric($curriculum[$i]) && bp_course_get_post_type($curriculum[$i]) == 'unit'){
							$unit_duration = get_post_meta($curriculum[$i],'vibe_duration',true);
							$unit_duration_parameter = apply_filters('vibe_unit_duration_parameter',60,$curriculum[$i]);
							$total_drip_duration += intval($unit_duration)*intval($unit_duration_parameter); // Sum of all unit durations in a section
						}
					}
				}
				
				$value = intval($start_section_timestamp) + intval($total_drip_duration);	
			}
		}
		return $value;
	}

	function total_drip_duration($value,$course_id,$unit_id,$pre_unit_id){
		$course_drip_duration_type = get_post_meta($course_id,'vibe_course_drip_duration_type',true);
		
		if(vibe_validate($course_drip_duration_type)){ //unit duration
			$unit_duration = intval(get_post_meta($pre_unit_id,'vibe_duration',true));
			$unit_duration_parameter = intval(apply_filters('vibe_unit_duration_parameter',60,$pre_unit_id));
			$value = $unit_duration*$unit_duration_parameter;
		}
		return $value;
    }
}

bp_course_filters::init();