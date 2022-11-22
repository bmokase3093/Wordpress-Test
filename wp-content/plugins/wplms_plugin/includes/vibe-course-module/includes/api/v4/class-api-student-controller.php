<?php

defined( 'ABSPATH' ) or die();

if ( ! class_exists( 'BP_Course_Rest_Student_Controller' ) ) {
	
	class BP_Course_Rest_Student_Controller extends BP_Course_New_Rest_Controller {

		public function register_routes() {
			// instructor app
			$this->type= 'student';


			register_rest_route( $this->namespace, '/'.$this->type.'/courses', array(
				'methods'                   =>  'POST',
				'callback'                  =>  array( $this, 'get_student_courses' ),
				'permission_callback' 		=> array( $this, 'get_student_permissions_check' ),
			) );

			register_rest_route( $this->namespace, '/'.$this->type.'/getcourseTabs/(?P<course>\w+)', array(
				'methods'                   =>  'POST',
				'callback'                  =>  array( $this, 'get_course_tabs' ),
				'permission_callback' 		=> array( $this, 'get_student_permissions_check' ),
				'args'                     	=>  array(
					
					'course'                       	=>  array(
						'validate_callback'     =>  function( $param, $request, $key ) {
													return is_numeric( $param );
												}
					),
				),

			) );

			register_rest_route( $this->namespace, '/'.$this->type.'/courseTab/(?P<course>\w+)', array(
				'methods'                   =>  'POST',
				'callback'                  =>  array( $this, 'get_course_tab' ),
				'permission_callback' 		=> array( $this, 'get_student_permissions_check' ),
				'args'                     	=>  array(
					
					'course'                       	=>  array(
						'validate_callback'     =>  function( $param, $request, $key ) {
													return is_numeric( $param );
												}
					),
				),
			) );
			
			register_rest_route( $this->namespace, '/'.$this->type.'/quiz', array(
				'methods'                   =>  'POST',
				'callback'                  =>  array( $this, 'get_student_quizzes' ),
				'permission_callback' 		=> array( $this, 'get_student_permissions_check' ),
			) );

			register_rest_route( $this->namespace, '/'.$this->type.'/assignments', array(
				'methods'                   =>  'POST',
				'callback'                  =>  array( $this, 'get_student_assignments' ),
				'permission_callback' 		=> array( $this, 'get_student_permissions_check' ),
			) );

			register_rest_route( $this->namespace, '/'.$this->type.'/badges', array(
				'methods'                   =>  'POST',
				'callback'                  =>  array( $this, 'get_student_badges' ),
				'permission_callback' 		=> array( $this, 'get_student_permissions_check' ),
			) );

			register_rest_route( $this->namespace, '/'.$this->type.'/lmsbadges', array(
				'methods'                   =>  'POST',
				'callback'                  =>  array( $this, 'get_student_lmsbadges' ),
				'permission_callback' 		=> array( $this, 'get_student_permissions_check' ),
			) );

			register_rest_route( $this->namespace, '/'.$this->type.'/lmsbadge', array(
				'methods'                   =>  'POST',
				'callback'                  =>  array( $this, 'get_student_lmsbadge' ),
				'permission_callback' 		=> array( $this, 'get_student_permissions_check' ),
			) );

			register_rest_route( $this->namespace, '/'.$this->type.'/gamification_point', array(
				'methods'                   =>  'POST',
				'callback'                  =>  array( $this, 'get_student_gamification_point' ),
				'permission_callback' 		=> array( $this, 'get_student_permissions_check' ),
			) );

			register_rest_route( $this->namespace, '/'.$this->type.'/lmsbadges_activitities', array(
				'methods'                   =>  'POST',
				'callback'                  =>  array( $this, 'get_student_lmsbadges_activities' ),
				'permission_callback' 		=> array( $this, 'get_student_permissions_check' ),
			) );

			register_rest_route( $this->namespace, '/'.$this->type.'/certificates', array(
				'methods'                   =>  'POST',
				'callback'                  =>  array( $this, 'get_student_certificates' ),
				'permission_callback' 		=> array( $this, 'get_student_permissions_check' ),
			) );

			register_rest_route( $this->namespace, '/'.$this->type.'/finishedCourses', array(
				'methods'                   =>  'POST',
				'callback'                  =>  array( $this, 'get_student_results' ),
				'permission_callback' 		=> array( $this, 'get_student_permissions_check' ),
			) );
			register_rest_route( $this->namespace, '/'.$this->type.'/finishedCourseDetails', array(
				'methods'                   =>  'POST',
				'callback'                  =>  array( $this, 'get_student_finished_course_details' ),
				'permission_callback' 		=> array( $this, 'get_student_permissions_check' ),
			) );
			
			register_rest_route( $this->namespace, '/'.$this->type.'/comments/(?P<course>\w+)', array(
				'methods'                   =>  'POST',
				'callback'                  =>  array( $this, 'get_student_comments' ),
				'permission_callback' 		=> array( $this, 'get_student_permissions_check' ),
				'args'                     	=>  array(
					
					'course'                       	=>  array(
						'validate_callback'     =>  function( $param, $request, $key ) {
													return is_numeric( $param );
												}
					),
				),
			) );

			register_rest_route( $this->namespace, '/'.$this->type.'/askQuestion', array(
				'methods'                   =>  'POST',
				'callback'                  =>  array( $this, 'ask_question' ),
				'permission_callback' 		=> array( $this, 'get_student_permissions_check' ),
			) );


			register_rest_route( $this->namespace, '/'. $this->type .'/announcement/(?P<course>\w+)', array(
				array(
					'methods'                   =>  'POST',
					'permission_callback' 		=> array( $this, 'get_student_permissions_check' ),
					'callback'                  =>  array( $this, 'get_announcement' ),
					'args'                     	=>  array(
					
						'course'                       	=>  array(
							'validate_callback'     =>  function( $param, $request, $key ) {
														return is_numeric( $param );
													}
						),
					),
				),
			));

			register_rest_route( $this->namespace, '/'. $this->type .'/news/(?P<course>\w+)', array(
				array(
					'methods'                   =>  'POST',
					'permission_callback' 		=> array( $this, 'get_student_permissions_check' ),
					'callback'                  =>  array( $this, 'get_news' ),
					'args'                     	=>  array(
					
						'course'                       	=>  array(
							'validate_callback'     =>  function( $param, $request, $key ) {
														return is_numeric( $param );
													}
						),
					),
				),
			));
			
			register_rest_route( $this->namespace, '/' . $this->type.'/courseButton', array(
				array(
					'methods'                   =>  'POST',
					'callback'                  =>  array( $this, 'course_button' ),
					'permission_callback' => array( $this, 'get_student_permissions_check' ),
				),
			));

			register_rest_route( $this->namespace, '/' . $this->type.'/courseButton/applycourse', array(
				array(
					'methods'                   =>  'POST',
					'callback'                  =>  array( $this, 'apply_course' ),
					'permission_callback' => array( $this, 'get_student_permissions_check' ),
				),
			));

			register_rest_route( $this->namespace, '/' . $this->type.'/wallet/assigncourse', array(
				array(
					'methods'                   =>  'POST',
					'callback'                  =>  array( $this, 'wallet_assign_course' ),
					'permission_callback' => array( $this, 'get_student_permissions_check' ),
				),
			));
		}

		function get_student_permissions_check($request){
			$body = json_decode($request->get_body(),true);
			
			if(!empty($body['token'])){
	            $this->user = apply_filters('vibebp_api_get_user_from_token','',$body['token']);
	            if(!empty($this->user)){
	                return true;
	            }
	        }

	        return false;
		}

		function get_student_courses($request){
			
			$args = json_decode($request->get_body(),true);

			$return = array('status'=>0,'message'=>__('No Courses found.','wplms'));
			
			if(empty($args['access'])){
				$args['access'] = 'active';
			}
			global $wpdb;
			
			if($args['access'] == 'active'){
				$courses_with_types = apply_filters('wplms_usermeta_direct_query',$wpdb->prepare("
		  		SELECT posts.ID as id
		      	FROM {$wpdb->posts} AS posts
		      	LEFT JOIN {$wpdb->usermeta} AS meta ON posts.ID = meta.meta_key
		      	WHERE   posts.post_type   = %s
		      	AND   posts.post_status   = %s
		      	AND   meta.user_id   = %d
		      	AND   meta.meta_value > %d
		      	",'course','publish',$this->user->id,time()));
			}else{
				$courses_with_types = apply_filters('wplms_usermeta_direct_query',$wpdb->prepare("
		  		SELECT posts.ID as id
		      	FROM {$wpdb->posts} AS posts
		      	LEFT JOIN {$wpdb->usermeta} AS meta ON posts.ID = meta.meta_key
		      	WHERE   posts.post_type   = %s
		      	AND   posts.post_status   = %s
		      	AND   meta.user_id   = %d
		      	AND  meta.meta_value < %d
		      	",'course','publish',$this->user->id,time()));
			}
			if(!empty($args['post__in'])){
				$courses_with_types.=' AND posts.ID IN ('.implode(',',$args['post__in']).')';

			}
			
			$courses_with_types = $wpdb->get_results($courses_with_types);
			
			if(!empty($courses_with_types) || !empty($args['post__in'])){
				$course_ids = $statuses = array();
				if(empty($args['post__in'])){
					foreach($courses_with_types as $course){
						$args['post__in'][]=$course->id;
						$type = bp_course_get_user_course_status($this->user->id,$course->id);
						
						$statuses[$course->id]= intval($type);
					}
				}else{
					foreach($args['post__in'] as $id){
						$type = bp_course_get_user_course_status($this->user->id,$id);
						$statuses[$id]= intval($type);
					}
				}

				$query_args = apply_filters('wplms_mycourses',array(
					'post_type'=>'course',
					'posts_per_page'=>!empty($args['per_page'])?$args['per_page']:12,
					'paged'=>$args['paged'],
					's'=>$args['s'],
					'post__in'=>$args['post__in']
				),$this->user->id);
				$course_query = new WP_Query($query_args);
				global $bp,$wpdb;
				if($course_query->have_posts()){
					$return['status']=1;
					$courses = array();
					while($course_query->have_posts()){
						$course_query->the_post();
						global $post;
						$retakes = bp_course_get_course_retakes($post->ID);
			            $course_retakes = bp_course_get_course_retakes($post->ID,$this->user->id);
						$authors=array($post->post_author);
						$authors = apply_filters('wplms_course_instructors',$authors,$post->ID);
						$progress = bp_course_get_user_progress($this->user->id,$post->ID);
						if($statuses[$post->ID]>2){$progress = 100;}
						
						$start_date = bp_course_get_start_date($post->ID,$this->user->id);
						if(strpos($start_date,'-') !== false){
							$start_date = strtotime($start_date);
						}

						if(class_exists('WPBMap')){
									
							WPBMap::addAllMappedShortcodes();
							$post->post_content = apply_filters('the_content',$post->post_content);
							//$post->post_content = do_shortcode($post->post_content);
							
							$vccss = get_post_meta($post->ID, '_wpb_shortcodes_custom_css', true);
							if(!empty($vccss)) 
							{
							    $vccss = strip_tags($vccss);
							    $post->post_content.='<style type="text/css" data-type="vc_shortcodes-custom-css">';
							    $post->post_content.=$vccss;
							    $post->post_content.='</style>';
							}
							if(function_exists('vc_asset_url')){  
								$front_css_file = vc_asset_url( 'css/js_composer.min.css' );
								$upload_dir = wp_upload_dir();
								$vc_upload_dir = vc_upload_dir();
								if ( '1' === vc_settings()->get( 'use_custom' ) && is_file( $upload_dir['basedir'] . '/' . $vc_upload_dir . '/js_composer_front_custom.css' ) ) {
									$front_css_file = $upload_dir['baseurl'] . '/' . $vc_upload_dir . '/js_composer_front_custom.css';
									$front_css_file = vc_str_remove_protocol( $front_css_file );
								}
								$post->post_content .= '<link rel="stylesheet" id="js_composer_front-css"  href="'.$front_css_file.'" type="text/css" media="all"/>';
								$post->post_content .= '<link rel="stylesheet" id="js_composer_front-css"  href="'.vc_asset_url( 'css/js_composer_tta.min.css' ).'" type="text/css" media="all"/>';
							}
						}

						
						$_course_data = array(
							'id'                    => $post->ID,
							'name'                  => $post->post_title,
							'excerpt'				=> $post->post_excerpt,
							'description'			=> apply_filters('the_content',$post->post_content),
							'user_progress'         => empty($progress)?0:intval($progress),
							'user_status'           => $statuses[$post->ID],
							'duration'				=> bp_course_get_course_duration($post->ID,$this->user->id),
							'user_expiry'           => bp_course_get_user_expiry_time($this->user->id,$post->ID),
							'start_date'            => $start_date,
							'display_start_date'    => $start_date?date(get_option('date_format'),$start_date):'',
							'featured_image'		=> $this->get_course_featured_image($post->ID),	
							'instructor'            => $authors,	
							'menu_order'            => $post->menu_order,
							'link'					=> get_permalink($post->ID),
							'course_retakes'        => bp_course_get_course_retakes($post->ID,$this->user->id),
							'user_retakes'        	=> bp_course_get_user_course_retakes($post->ID,$this->user->id),
							'can_download_offline'        	=> ((get_post_meta($post->ID,'vibe_offline_download',true)==='S')?true:false),
						);
						$stop_course_status = apply_filters('wplms_before_course_status_api',false,$post->ID,$this->user->id);

						if($stop_course_status && is_array($stop_course_status) && !empty($stop_course_status['error_code'])){
							$_course_data['error'] = $stop_course_status;
						}
						$courses[]=$_course_data;

					}
					unset($return['message']);
					$return['courses']=$courses;
					$return['total']=$course_query->found_posts;
				}
			}

		    return new WP_REST_Response( $return, 200 );
		}

		public function get_course_featured_image($course){

			$post_thumbnail_id = get_post_thumbnail_id( $course );
			if(!empty($post_thumbnail_id) ){
				$image = wp_get_attachment_image_src($post_thumbnail_id,'medium');
				if(is_Array($image))
					$image = $image[0];
			}

			if(empty($image)){
                $image = vibe_get_option('default_course_avatar');
                if(empty($image)){
                    $image = VIBE_URL.'/assets/images/avatar.jpg';
                }
            }

            return $image;
		}

		function get_student_quizzes($request){

			$args = json_decode($request->get_body(),true);
			$_args =$args;
			unset($args['quiz_status']);
			unset($args['token']);
			$return = array('status'=>0,'message'=>__('Data missing','wplms'));
			if(!function_exists('bp_is_active')){
				$return = array('status'=>0,'message'=>__('BuddyPress not active.','wplms'));
				return new WP_REST_Response( $return, 200 );
			}
			if(!bp_is_active('activity')){
				$return = array('status'=>0,'message'=>__('BuddyPress Activity not active.','wplms'));
				return new WP_REST_Response( $return, 200 );
			}

			$user_id = $this->user->id;

			global $bp,$wpdb;
			$activity_ids = get_user_meta($user_id,'quiz_cached_results',true);
			if(empty($activity_ids)){
				$activity_ids = $wpdb->get_results($wpdb->prepare( "
					SELECT a.secondary_item_id,MAX(a.id) AS id,a.date_recorded as attempted_date, am.meta_value as result
					FROM {$bp->activity->table_name} AS a
					LEFT JOIN {$bp->activity->table_name_meta}  AS am
					ON a.id = am.activity_id
					WHERE a.type = 'quiz_evaluated'
					AND a.user_id = %d
					AND am.meta_key = 'quiz_results'
					AND am.meta_value IS NOT NULL
					GROUP BY a.secondary_item_id
					ORDER BY a.date_recorded DESC
					LIMIT 0,999
				" ,$user_id));
				update_user_meta($user_id,'quiz_cached_results',$activity_ids);
			}
			$assigned_quizzes= $_assigned_quizzes= get_user_meta($user_id,'wplms_assigned_quizzes',true);
            if(empty($assigned_quizzes)){
                $assigned_quizzes = 0;
                $_assigned_quizzes= [9999999999];
            }else{
            	$assigned_quizzes = count($assigned_quizzes);
            }
			
            $_user_courses = $wpdb->get_results(apply_filters('wplms_usermeta_direct_query',$wpdb->prepare("
                SELECT posts.ID as id
                FROM {$wpdb->posts} AS posts
                LEFT JOIN {$wpdb->usermeta} AS meta ON posts.ID = meta.meta_key
                WHERE   posts.post_type   = %s
                AND   posts.post_status   = %s
                AND   meta.user_id   = %d
                AND meta.meta_value > %d
                ",'course','publish',$this->user->id,time())));

            if(!empty($_user_courses)){
                foreach($_user_courses as $course){
                    $user_courses[]=$course->id;
                }
                $results1  = $wpdb->get_results("SELECT DISTINCT post_id from {$wpdb->postmeta} as pm LEFT JOIN {$wpdb->posts} as p ON p.ID=pm.post_id WHERE meta_value IN (".implode(',',$user_courses).") AND meta_key = 'assigned_course' AND p.post_type='quiz'");
                	
                if(!empty($results1)){
                    foreach ($results1 as $key => $quiz) {
                        if(!in_array($quiz->post_id,$_assigned_quizzes)){
                        	$assigned_quizzes++;
                            $_assigned_quizzes[] = $quiz->post_id;
                        }
                    }
                }
            }
			
			if(!empty($activity_ids) || $_args['quiz_status']=='assigned'){
			    
			    $quiz_ids = $aids = $attempts= $results = array();
			    if(!empty($activity_ids)){
			    	foreach($activity_ids as $activity_id){
						$quiz_ids[] = $activity_id->secondary_item_id;
						$aids[$activity_id->secondary_item_id] = $activity_id->id;
						$attempts[$activity_id->secondary_item_id] = $activity_id->attempted_date;
						$results[$activity_id->secondary_item_id] = unserialize($activity_id->result);
					}
			    }
				$user_courses = [];
				$args = array(
			    	'post_type'=>'quiz',
			    	'post_status'=>'publish',
			    	's'=>$args['s'],
			    	'post__in'=>$quiz_ids,
			    	'paged'=>$args['paged']?$args['paged']:1,
			    	'posts_per_page'=>$args['per_page']?$args['per_page']:12,
			    );
			 	
		        if (!empty($_args['quiz_status']) && $_args['quiz_status']=='assigned') {
		            $args['post__in'] = $_assigned_quizzes;
		        }

		        $args = apply_filters('wplms_my_quizzes',$args,$_args,$this->user);
			    $quizzes = new WP_Query($args);
			   
			    $all_quiz=array();
			    if($quizzes->have_posts()){
			    	while($quizzes->have_posts()){
			    		$quizzes->the_post();
			    		$marks = $max = $count = 0;
			    		if(!empty($results[get_the_ID()])){
			    			foreach($results[get_the_ID()] as $result){
				    			$marks+=$result['marks'];
				    			$max+=$result['max_marks'];
				    			$count++;
				    		}
			    		}
			    		

			    		$quiz = array(
			    			'id'=>get_the_ID(),
			    			'activity_id'=>(!empty($aids[get_the_ID()])?$aids[get_the_ID()]:0),
			    			'title'=>get_the_title(),
			    			'quiz-type'=>wp_get_object_terms(get_the_ID(),'quiz-type',array('fields'=>'names')),
			    			'attempted_date'=>(!empty($attempts[get_the_ID()])?$attempts[get_the_ID()]:0),
			    			'count'=>$count,
			    			'marks'=>$marks,
			    			'max'=>$max,
			    			'result'=>(!empty($results[get_the_ID()])?$results[get_the_ID()]:array())
			    		);
			    		$quiz_type = wplms_get_element_type(get_the_ID(),'quiz');
					    if($quiz_type=='scorm'){
					        $quiz['quiz_type'] = $quiz_type;
					        $package = get_post_meta(get_the_ID(),'vibe_upload_package',true);
					        if(!empty($package)){
					          $pack =  do_shortcode('[iframe package_type="'.$package['package_type'].'" no_script="1"]'.$package['path'].'[/iframe]');
					          $quiz['content_id'] = $pack ;
					        }
					        
					    }
			    		
			    		$all_quiz[]=$quiz;
			    	}

			    	$return = array('status'=>1,'quizzes'=>apply_filters('wplms_student_my_quizzes',$all_quiz,$args,$user_id),'args'=>$args,'total'=>$quizzes->found_posts,'assigned_quizzes'=>$assigned_quizzes);
			    }else{
			    	$return = array('status'=>1,'message'=>__('No Quizzes found, matching your criteria.','wplms'),'args'=>$args,'assigned_quizzes'=>$assigned_quizzes);
			    }
		    }else{
		    	$return = array('status'=>0,'message'=>__('No Quizzes found.','wplms'),'args'=>$args,'assigned_quizzes'=>$assigned_quizzes);
		    }

		    return new WP_REST_Response( $return, 200 );
		}


		function get_student_assignments($request){

			$args = json_decode($request->get_body(),true);

			if(!function_exists('bp_is_active')){
				$return = array('status'=>0,'message'=>__('BuddyPress not active.','wplms'));
				return new WP_REST_Response( $return, 200 );
			}
			if(!bp_is_active('activity')){
				$return = array('status'=>0,'message'=>__('BuddyPress Activity not active.','wplms'));
				return new WP_REST_Response( $return, 200 );
			}

			$return = array('status'=>0,'message'=>__('Data missing','wplms'));

			$query_args =array(
            'post_type'=>'wplms-assignment',
            'post_status'=>'publish',
            
	    	'paged'=>$args['page']?$args['page']:1,
	    	'per_page'=>($args['per_page']?$args['per_page']:12),
            'meta_query'=>array(
                array(
                    'key' => $this->user->id,
                    'compare' => 'EXISTS'
                    ),
                ),
            );
            if(!empty($args['s'])){
            	$query_args['s'] = $args['s'];
            }
            $user_id = $this->user->id;
            global $bp,$wpdb;
            $assigned_assigns= $_assigned_assigns= get_user_meta($user_id,'wplms_assigned_assignment',true);

            if(empty($assigned_assigns)){
                $assigned_quizzes = 0;
                $_assigned_assigns= [9999999999];
            }else{
            	$assigned_assigns = count($assigned_assigns);
            }
			
            $_user_courses = $wpdb->get_results(apply_filters('wplms_usermeta_direct_query',$wpdb->prepare("
                SELECT posts.ID as id
                FROM {$wpdb->posts} AS posts
                LEFT JOIN {$wpdb->usermeta} AS meta ON posts.ID = meta.meta_key
                WHERE   posts.post_type   = %s
                AND   posts.post_status   = %s
                AND   meta.user_id   = %d
                AND meta.meta_value > %d
                ",'course','publish',$this->user->id,time())));


            if(!empty($_user_courses)){
                foreach($_user_courses as $course){
                    $user_courses[]=$course->id;
                }
                $results1  = $wpdb->get_results("SELECT DISTINCT post_id from {$wpdb->postmeta} as pm LEFT JOIN {$wpdb->posts} as p ON p.ID=pm.post_id WHERE meta_value IN (".implode(',',$user_courses).") AND meta_key = 'assigned_course' AND p.post_type='wplms-assignment'");
                if(!empty($results1)){
                    foreach ($results1 as $key => $quiz) {
                        if(!in_array($quiz->post_id,$_assigned_assigns)){
                        	$assigned_assigns++;
                            $_assigned_assigns[] = $quiz->post_id;
                        }
                    }
                }
            }
	        if (!empty($args['assignment_status']) && $args['assignment_status']=='assigned') {
	            $query_args['post__in'] = $_assigned_assigns;
	            unset($query_args['meta_query']);
	        }

            $query_args = apply_filters('wplms_my_assignments',$query_args,$args,$this->user->id);

			$assignments_query=new WP_QUERY($query_args);
			$assignments=array();
		    if($assignments_query->have_posts()){
		    	$aids = array();
		    	while($assignments_query->have_posts()){
		    		$assignments_query->the_post();

		    		$aids[]=get_the_ID();
		    		$assignment = array(
		    			'id'=>get_the_ID(),
		    			'title'=>get_the_title(),
		    			'assignment-type'=>wp_get_object_terms(get_the_ID(),'assignment-type',array('fields'=>'names')),
		    			'marks'=>intval(get_post_meta(get_the_id(),$user_id,true)),
		    			'max'=>intval(get_post_meta(get_the_id(),'vibe_assignment_marks',true)),
		    		);
		    		$assignments[get_the_ID()]=$assignment;
		    	}

		    	$date_format=get_option( 'date_format' );

		    	if(function_exists('bp_is_active') && bp_is_active('activity')){
		    		global $wpdb,$bp;
	    			$attempts = $wpdb->get_results($wpdb->prepare("
	    				SELECT date_recorded,secondary_item_id
	    				FROM {$bp->activity->table_name} 
	    				WHERE secondary_item_id IN (".implode(',', $aids).")
	    				AND component = %s AND type = %s 
	    				AND user_id = %d LIMIT 0,999",
	    				'course','assignment_started',$this->user->id),ARRAY_A);

	    			
	    			if(!empty($attempts)){
	    				foreach($attempts as $attempt){
	    					$assignments[$attempt['secondary_item_id']]['attempted_date']=$attempt['date_recorded'];
	    				}
	    			}
	    		}

		    	$return = array('status'=>1,'assignments'=>array_values($assignments),'total'=>$assignments_query->found_posts,'assigned_assignments'=>$assigned_assigns,'$query_args'=>$query_args);
		    }else{
		    	$return = array('status'=>1,'message'=>__('No assignments found!','wplms'),'assigned_assignments'=>$assigned_assigns,'$query_args'=>$query_args);
		    }
			return new WP_REST_Response( $return, 200 );
		}
		

		function get_student_badges($request){

			$all_badges = bp_course_get_user_badges($this->user->id);
			$badges = array();
			if(!empty($all_badges)){
				foreach($all_badges as $badge_course_id){
					$meta = get_post_meta($badge_course_id,'vibe_badge',true);
					if('publish' == get_post_status ( $badge_course_id ) && !empty($meta) && $meta=='S'){
						$b=bp_get_course_badge($badge_course_id);
						if(is_numeric($b)){
							$b = array('url'=>wp_get_attachment_url($b));
						}
	            		$b_title = get_post_meta($badge_course_id,'vibe_course_badge_title',true);
	            		$badges[]=array('value'=>$b['url'],'label'=>$b_title);
					}
					
				}
			}
			return new WP_REST_Response( array('status'=>1,'badges'=>$badges), 200 );
		}

		function get_student_certificates($request){
			$user_certificates = bp_course_get_user_certificates($this->user->id);
			$certificates = array();
			if (!empty($user_certificates)) {
				foreach($user_certificates as $certificate){
					$certificates[]=array('label'=>get_the_title($certificate),'value'=>bp_get_course_certificate(array('user_id'=>$this->user->id,'course_id'=>$certificate)),'course_id'=>$certificate);
				}
			}
			
			return new WP_REST_Response( array('status'=>1,'certificates'=>$certificates), 200 );
		}

		function get_student_results($request){
			
			$args = json_decode($request->get_body(),true);
			$finished_courses = bp_course_get_user_courses($this->user->id,'course_evaluated');

			if(!empty($finished_courses)){
				
				
				$query_args = array(
					'post_type'=>'course',
					'post_status'=>'publish',
					's'=>$args['s'],
					'page'=>$args['paged'],
					'posts_per_page'=>12,
					'orderby'=>$args['orderby'],
					'order'=>$args['order'],
					'post__in'=>$finished_courses
				);
				$results  = new WP_query($query_args);

				
				
				$courses = array();
				if($results->have_posts()){
					$badges = bp_course_get_user_badges($this->user->id); 
					$certificates = bp_course_get_user_certificates($this->user->id);


					while($results->have_posts()){
						$results->the_post();
						$marks = bp_course_get_marks($this->user->id,get_the_ID());
						$has_certificate = (!empty($certificates) && in_Array(get_the_ID(),$certificates))?1:0;
						$has_badge = (!empty($badges) && in_array(get_the_ID(),$badges)?1:0);

						
						$grade = array(
							'label'=>'',
							'key'=>'',
							'value'=>$marks
						);
						if($has_certificate){
							$grade['label']= _x('Passed','certificate status','wplms');
							$grade['key']= 'passed';
						}
						if($has_badge){
							$grade['label'] .= ' '._x('Excelled','certificate status','wplms');
							$grade['key'] .= 'excel';
						}
						$authors = apply_filters('wplms_course_instructor',get_the_author_id());
						if(!is_array($authors)){
							$authors=array($authors);
						}
						$courses[]=array(
							'id'=>get_the_ID(),
							'img'=>get_the_post_thumbnail_url(),
							'title'=>get_the_title(),
							'instructor'=>$authors,
							'certificate'=>$has_certificate,
							'badge'=>$has_badge,
							'grade'=>$grade,
						);
					}
				}else{
					return new WP_REST_Response( array('status'=>0,'message'=>__('No courses','wplms')), 200 );
				}
				$return = array('status'=>1,'courses'=>$courses,'total'=>$results->found_posts);
			}else{
				$return = array('status'=>0,'message'=>__('No finished courses','wplms'));
			}


			return new WP_REST_Response( $return, 200 );
		}

		function get_student_finished_course_details($request){
			
			$body = json_decode($request->get_body(),true);

			
			if(bp_course_get_user_course_status($this->user->id,$body['course_id']) != 4){
				return new WP_REST_Response( array('status'=>0,'message'=>__('Course not complete.','wplms')), 200 );	
			}
			$course_id = $body['course_id'];
			$course_curriculum = bp_course_get_curriculum($course_id,$this->user->id);
	    	$curriculum = array();
	    	if(!empty($course_curriculum)){
    			foreach($course_curriculum as $c){
    				if(is_numeric($c)){
    					$c = (int)$c;
    					
		                $type = bp_course_get_post_type($c);
						if($type == 'unit'){
						  	$curriculum[]=array(
					      		'id' => $c,
					      		'title'=>get_the_title($c),
					      		'type' => 'unit',
					      		'time'	=> bp_course_get_user_unit_completion_time($user_id,$c,$course_id),
					      		'icon'=>wplms_get_element_icon(wplms_get_element_type($c,$type)),
					      		'completed' => true
					      	);
						}
  						if($type == 'quiz'){
  							$marks = (int)get_post_meta($c,$user_id,true);
  							$qmax = bp_course_get_quiz_questions($c,$user_id);
            				if(!empty($qmax) && !empty($qmax['marks']) && is_array($qmax['marks'])){
            					$max=array_sum($qmax['marks']);
            				}
            				$q_data = array(
					      		'id' => $c,
					      		'title'=>get_the_title($c),
					      		'type' => 'quiz',
					      		'marks' => (int)$marks,
					      		'status' => $status,
					      		'icon'=>wplms_get_element_icon(wplms_get_element_type($c,$type)),
					      		'max' => $max,
					      	);

  							$status = (int)bp_course_get_user_quiz_status($user_id,$c);
		                	if(!empty($status) && $status == 4){
		                    	$q_data['completed'] = true;
		                    }else{
		                        $q_data['completed'] = false;
		                    }
		                    $curriculum[]= $q_data;
		                }
		                if($type == 'wplms-assignment'){
  							$marks = (int)get_post_meta($c,$user_id,true);
  							$max = (int)get_post_meta($c,'vibe_assignment_marks',true);
            				
            				$q_data = array(
					      		'id' => $c,
					      		'title'=>get_the_title($c),
					      		'type' => 'assignment',
					      		'marks' => (int)$marks,
					      		'status' => $status,
					      		'icon'=>wplms_get_element_icon(wplms_get_element_type($c,$type)),
					      		'max' => $max,
					      	);

  							
		                	if(!empty($marks)){
		                    	
		                    	$q_data['completed'] = true;
		                    }else{
		                        $q_data['completed'] = false;
		                    }
		                    $curriculum[]= $q_data;
		                }
		            }else{
		            	$curriculum[] = array(
				      		'title'=>$c,
				      		'type' => 'section',
				      	);
		            } 		
		    	}
	    	}

	    	$return = array('status'=>1,'curriculum'=>$curriculum);

	    	$retakes = bp_course_get_course_retakes($course_id,$this->user->id);

            if($retakes){
            	$course_retakes = bp_course_get_user_course_retakes($course_id,$this->user->id);
            	$return['retakes']=array(
            		'total'=>$retakes,
            		'consumed'=>$course_retakes
            	);

            }
			return new WP_REST_Response( $return, 200 );
		}

		function get_student_comments($request){
			$course_id = $request->get_param('course');
			$body = json_decode($request->get_body(),true);
			$args = array(
				'search' => $body['s'],
				'number'=>20,
				'status'=>'approve',
				'paged'=>$body['page'],
				'parent'=>0,
				'orderby'=>(!empty($body['orderby'])?$body['orderby']:''),
				'order'=>(!empty($body['order'])?$body['order']:''),
				'user_id' => $this->user->id,
			);

			$args['type'] = 'public';
			
			if($body['type'] == 'discussions'){
				$args['type'] = 'public';
				$args['post_type'] = 'unit';
			}

			if($body['type'] == 'notes'){
				$args['type'] = 'note';
				$args['post_type'] = 'unit';
			}

			if($body['type'] == 'reviews'){
				$args['type'] = 'comment';
				$args['post_type'] = 'course';
			}

			if(!empty($body['post_id'])){
				$args['post_id']=$body['post_id'];
			}


			if(!empty($course_id)){
				if($args['post_type']=='course'){
					$args['post_id']=$course_id;
					unset($args['user_id']);
				}else{
					unset($args['post_type']);
					$args['meta_query'] = array(
						'relation'=>'and',
						array(
							'key'=>'course_id',
							'value'=>$course_id,
							'compare'=>'='
						)
					);
				}
			}


			$comments_query = new WP_Comment_Query;
			$args = apply_filters('wplms_get_student_comments',$args,$body,$this->user->id);
			$comment_results = $comments_query->query($args);


			if ( !empty($comment_results ) ){
				$comments = array();
				$loaded_discussion_chain = array();
				$cargs = $args;
				$cargs['count']=1;
				$total = $comments_query->query($cargs);

			
			    foreach ( $comment_results as $comment_result ) {

			    	$comment=array(
			    			'id'=>$comment_result->comment_ID,
			    			'comment_content'=>$comment_result->comment_content,
			    			'comment_date'=>strtotime($comment_result->comment_date),
			    			'user_id'=>$comment_result->user_id,
			    		);

			    	if(!empty($body['fetch_meta'])){
						foreach($body['fetch_meta'] as $meta_key){
							$comment[$meta_key] = get_comment_meta($comment_result->comment_ID,$meta_key,true);
						}
					}
			    	if($body['type'] == 'notes'){
		    			$comment['unit']=array('id'=>$comment_result->comment_post_ID,'title'=>get_the_title($comment_result->comment_post_ID),'icon'=>wplms_get_element_icon(wplms_get_element_type($comment_result->comment_post_ID,'unit')));
		    			$comment['context'] = get_comment_meta($comment_result->comment_ID,'context',true);
		    			$course_id = get_comment_meta($comment_result->comment_ID,'course_id',true);
		    			if(!empty($course_id)){
		    				$comment['course']=array('id'=>$course_id,'title'=>get_the_title($course_id));
		    			}
			    	}
			    	if($body['type'] == 'discussions'){


		    			$comment['unit']=array('id'=>$comment_result->comment_post_ID,'title'=>get_the_title($comment_result->comment_post_ID),'icon'=>wplms_get_element_icon(wplms_get_element_type($comment_result->comment_post_ID,'unit')));
		    			$course_id = get_comment_meta($comment_result->comment_ID,'course_id',true);
		    			if(!empty($course_id)){
		    				$comment['course']=array('id'=>$course_id,'title'=>get_the_title($course_id));
		    			}
		    			
	    				$nargs = array(
				            'parent' => $comment_result->comment_ID,
				            'hierarchical' => true,
			           	);
			           	
				        $chain = get_comments($nargs);
				        if(!empty($chain)){
				        	foreach($chain as $el){
				        		$loaded_discussion_chain[] = $el->comment_ID;
				        	}
				        }
				        $comment['chain'] = $chain;
					    
			    	}
			    	if($body['type'] == 'reviews'){
		    			$comment['course']=array('id'=>$comment_result->comment_post_ID,'title'=>get_the_title($comment_result->comment_post_ID));
		    			$comment['rating'] = get_comment_meta($comment_result->comment_ID,'review_rating',true);
		    			$comment['title'] = get_comment_meta($comment_result->comment_ID,'review_title',true);

		    			$nargs = array(
				            'parent' => $comment_result->comment_ID,
				            'hierarchical' => true,
			           	);
			           	
				        $chain = get_comments($nargs);
				        if(!empty($chain)){
				        	foreach($chain as $k=>$el){
				        		$chain[$k]['comment_date']=strtotime($comment_result->comment_date);
				        	}
				        }
				        $comment['chain'] = $chain;
			    	}

			    	$comments[]=$comment;
			    }

			    $return = array('status'=>1,'comments'=>$comments,'args'=>$args);
			} else {
				if($body['type'] == 'reviews'){
					$message = __('No reviews found.','wplms');
				}else if($body['type'] == 'discussions'){
					$message = __('No discussions found.','wplms');
				}else{
					$message = __('No notes found.','wplms');
				}
			    $return = array('status'=>1,'message'=>$message,'args'=>$args);
			}

			return new WP_REST_Response( $return, 200 );
		}

		function get_course_tabs($request){
			$body = json_decode($request->get_body(),true);
			$course_id = $request->get_param('course');
			$tabs = apply_filters('wplms_get_student_mmy_course_tabs',array(
                'overview'=>__('Overview','wplms'),
                'curriculum'=>__('Curriculum','wplms'),
                'announcementsnews'=>__('Announcements & News','wplms'),
                'qna'=>__('QnA','wplms'),
                'notes'=>__('Notes','wplms'),
            ),$body,$request);

			if(function_exists('bp_is_active') && bp_is_active('groups')){
				$group_id = get_post_meta($course_id,'vibe_group',true);
				if(!empty($group_id) && is_numeric($group_id)){
					$tabs['external__groups_view_'.$group_id] = _x('Group','student course view in pwa','wplms');	
				}
			}

			if(function_exists('bbpress')){
				$forum_id = get_post_meta($course_id,'vibe_forum',true);
				if(!empty($forum_id) && is_numeric($forum_id)){
					$tabs['external__forums_forums_'.$forum_id] = _x('Forum','student course view in pwa','wplms');
				}
			}			
            
			$tabs = apply_filters('wplms_get_course_tabs',$tabs,$course_id,$this->user->id);

			$curriculum = bp_course_get_curriculum($course_id,$this->user->id);
			if(empty($curriculum)){
				unset($tabs['curriculum']);
			}
            
            return new WP_REST_Response( array('status'=>1,'tabs'=>$tabs), 200 );
		}

		function get_course_tab($request){
			$body = json_decode($request->get_body(),true);
			$course_id = $request->get_param('course');
			if($body['tab'] == 'curriculum'){ 
				$curriculum_items = bp_course_get_curriculum($course_id,$this->user->id);
				if(empty($curriculum_items)){
					return new WP_REST_Response( array('status'=>0,'curriculum'=>false), 200 );
				}
				$curriculum = array();
				foreach($curriculum_items as $kk => $item){

					if(is_numeric($item)){
						$type = bp_course_get_post_type($item);
						if($type == 'unit'){

							$user_progress = bp_course_check_unit_complete($item,$this->user->id,$course_id);
							if($user_progress == 1){$user_progress = 100;}
	                        
	                    }else if($type == 'quiz'){

	                    	$status = bp_course_get_user_quiz_status($this->user->id,$item);
	                    	switch($status){
	                    		case 1:
	                    			$user_progress = 10;
	                    		break;
	                    		case 2:
	                    			$user_progress = 50;
	                    		break;
	                    		case 3:
	                    		case 4:
	                    			$user_progress = 100;
	                    		break;
	                    		default:
	                    			$user_progress = 0;
	                    		break;
	                    	}
	                    	
	                        
	                    }else if($type == 'wplms-assignment'){
	                    	$unittaken = get_post_meta($item,$this->user->id,true);
	                    	if($unittaken == 0){
								$user_progress = 50;
	                    	}else if($unittaken > 0){
	                			$user_progress = 100;
	                    	}else{
	                    		$user_progress = 0;
	                    	}
	                    }

	                    $duration = get_post_meta($item,'vibe_duration',true);
						if( empty($duration) )
							$duration = 0;
						$_type = $type;
						if($_type=='wplms-assignment'){
							$_type = 'assignment';
						}
						
						$duration_parameter = apply_filters("vibe_".$_type."_duration_parameter",60,$item);
						$total_duration = $duration*$duration_parameter;

						$curriculum[]=array(
							'key' => $kk,
							'type'=>$type,
							'label'=>get_the_title($item),
							'duration'=>$total_duration,
							'icon'=>wplms_get_element_icon(wplms_get_element_type($item,bp_course_get_post_type($item))),
							'progress'=>$user_progress
						);	
					}else{
						if(strpos($item,'--') === 0){
							$curriculum[]=array('key' => $kk,'type'=>'sub_section','label'=>$item);	
						}else{	
							$curriculum[]=array('key' => $kk,'type'=>'section','label'=>$item);	
						}
						
					}
					
				}
				return new WP_REST_Response( array('status'=>1,'curriculum'=>$curriculum), 200 );
			}

			$data = apply_filters('wplms_course_tab_data','',$body,$course_id);
			return new WP_REST_Response( array('status'=>1,'data'=>$data), 200 );

		}


		function ask_question($request){
			$body = json_decode($request->get_body(),true);
			$comment = $body['comment'];
			if($comment['user_id'] != $this->user->id){
				return new WP_REST_Response( array('status'=>0,'message'=>__('Can only convert your comment to question.','wplms')), 200 );
			}

			$instructor_id = get_post_field( 'post_author', $body['course_id'] );
			update_comment_meta($comment['comment_ID'],'question',1);
			update_comment_meta($comment['comment_ID'],'instructor',$instructor_id);
			update_comment_meta($comment['comment_ID'],'course_id',$body['course_id']);

			do_action('wplms_course_unit_comment',$comment['comment_post_ID'],$this->user->id,$comment,$body['course_id']);
			
			return new WP_REST_Response( array('status'=>1,'message'=>__('Instructor notified about this question.','wplms')), 200 );
		}


		function get_announcement($request){
			$course_id = $request->get_param('course');
			$body = json_decode($request->get_body(),true);
			if(!empty($course_id)){
				$announcement = get_post_meta($course_id,'announcement',true);
		        $announcement_type = get_post_meta($course_id,'announcement_student_type',true);
		       
	            if(!empty($announcement)){
	            	return new WP_REST_Response( array('status'=>1,'announcement'=>$announcement,'student_type'=>$announcement_type), 200 );
	            }
			}
			return new WP_REST_Response( array('status'=>0,'message'=>__('No announcements for course.','wplms')), 200 );
		}

		function get_news($request){
			
			$args = json_decode($request->get_body(),true);
			$course_id = $request->get_param('course');
	        $news_args = array(
	            'post_type'=>'news',
	            'posts_per_page'=>20,
	            'paged'=>empty($args['paged'])?'':$args['paged'],
	            's'=>empty($args['s'])?'':$args['s'],
	            'orderby'=>$args['orderby'],
	            'order'=>$args['order'],	            
	        );

	        if(!empty($course_id)){
	        	$news_args['meta_query']=array(
	            	'relation'=>'AND',
	            	array(
	            		'key'=>'vibe_news_course',
	            		'value'=>$course_id,
	            		'compare'=>'='
	            	)
	            );
	        }

	        $news_query = new WP_Query($news_args);

	        $return = array(
	            'status'=>1,
	            'articles'=>[]
	        );

	        if($news_query->have_posts()){
	            $return['total']=$news_query->found_posts;
	            while($news_query->have_posts()){
	                $news_query->the_post();
	                
	                global $post;
	                $news=array(
	                    'id'=>$post->ID,
	                    'post_title'=>$post->post_title,
	                    'post_date'=>strtotime($post->post_date),
	                    'post_content'=>wp_slash($post->post_content),
	                    'raw'=>get_post_meta($post->ID,'raw',true),
	                    'post_author'=>$post->post_author,
	                    'img'=>get_the_post_thumbnail_url($post->ID,'large')
	                );

	                $return['news'][]=$news;
	            }
	        }else{
	            $return = array(
	                'status'=>0,
	                'message'=>_x('No News found !','api','wplms')
	            );
	        }

	        return new WP_REST_Response($return, 200 );
		}

		function apply_course($request){
			$body = json_decode($request->get_body(),true);
			$course_id = $body['id'];

			if(!empty($course_id) && !empty($this->user->id)){
				update_user_meta($this->user->id,'apply_course'.$course_id,$course_id);
			    do_action('wplms_user_course_application',$course_id,$this->user->id);
			    $return  =array('status'=>true,'message'=>_x('Applied for course','','wplms'));
			}else{
				$return  =array('status'=>false,'message'=>_x('Data missing','','wplms'));
			}
			
		    return new WP_REST_Response( $return, 200 );
		}

		function wallet_assign_course($request){
			$body = json_decode($request->get_body(),true);
			$course_id = $body['id'];
			if(!empty($course_id) && !empty($this->user->id)){
				//check credits
				$balance = intval(get_user_meta($this->user->id,'wallet',true));
				if(empty($balance) || $balance < 0){
					$return  =array('status'=>false,'message'=>_x('Not enough credits!','','wplms'));
				}
				$course_credits = intval(get_post_meta($course_id,'vibe_course_credits',true));
				if(empty($course_credits) || $course_credits < 0){
					$return  =array('status'=>false,'message'=>_x('Course credits not set by instructor','','wplms'));
				}
				if($course_credits <=  $balance){
					

					$activity_id = bp_activity_add( array( 
						'user_id' => $this->user->id, 
						'action' => 'debit', 
						'content' => sprintf(_x('%s credits debited for purchase of course %s','wallet','wplms'),$course_credits,get_the_title($course_id)), 
						'component' => 'wallet', 
						'type' => 'debit', 
					));
					bp_activity_update_meta($activity_id,'transaction',array(
						'status'=>'debit',
						'amount'=>$course_credits,
					));
					
					$wallet = $balance - ($course_credits);
					update_user_meta($this->user->id,'wallet',$wallet); //Amount
					
					
					do_action('wplms_wallet_transaction',array('user_id' => $this->user->id,'post'=>(Array)$body));
					bp_course_add_user_to_course($this->user->id,$course_id);
					$instructors= $commission_array=[];
					$instructors[$course_id]=apply_filters('wplms_course_instructors',get_post_field('post_author',$course_id,'raw'),$course_id);
					$courses = array($course_id);
					$commission_array[$this->user->id]=array(
						'instructor'=>$instructors,
						'course'=>$courses,
						'total'=>$course_credits,
		                'currency'=>VIBEBP_CREDITS_SLUG
					);
					$instructor_commission=0;
					if(function_exists('vibe_get_option'))
				      $instructor_commission = vibe_get_option('instructor_commission');
				    
				      	
				    if(!isset($instructor_commission) || !$instructor_commission)
				      $instructor_commission = 70;

				    $commissions = get_option('instructor_commissions');
					foreach($commission_array as $item_id=>$commission_item){

						foreach($commission_item['course'] as $course_id){
							
							if(!empty($commission_item['instructor'][$course_id]) && is_Array($commission_item['instructor'][$course_id]) && count($commission_item['instructor'][$course_id]) > 1){     // Multiple instructors
								
								$calculated_commission_base=round(($commission_item['total']*($instructor_commission/100)/count($commission_item['instructor'][$course_id])),0); // Default Slit equal propertion

								foreach($commission_item['instructor'][$course_id] as $instructor){
									if(empty($commissions[$course_id][$instructor]) && !is_numeric($commissions[$course_id][$instructor])){
										$calculated_commission_base = round(($commission_item['total']*$instructor_commission/100),2);
									}else{
										$calculated_commission_base = round(($commission_item['total']*$commissions[$course_id][$instructor]/100),2);
									}
									$calculated_commission_base = apply_filters('wplms_calculated_commission_base',$calculated_commission_base,$instructor);
			                        bp_course_record_instructor_commission($instructor,$calculated_commission_base,$course_id,array('origin'=>VIBEBP_CREDITS_SLUG,'order_id'=>0,'item_id'=>$item_id,'currency'=>VIBEBP_CREDITS_SLUG));
			                        
								}
							}else{
								if(is_array($commission_item['instructor'][$course_id]))                                    // Single Instructor
									$instructor=$commission_item['instructor'][$course_id][0];
								else
									$instructor=$commission_item['instructor'][$course_id]; 
								
								if(isset($commissions[$course_id][$instructor]) && is_numeric($commissions[$course_id][$instructor]) && !empty($commissions[$course_id][$instructor])){
									$calculated_commission_base = round(($commission_item['total']*$commissions[$course_id][$instructor]/100),2);
						
								}else{
									$calculated_commission_base = round(($commission_item['total']*$instructor_commission/100),2);
							
								}

								$calculated_commission_base = apply_filters('wplms_calculated_commission_base',$calculated_commission_base,$instructor);

			                    bp_course_record_instructor_commission($instructor,$calculated_commission_base,$course_id,array('origin'=>VIBEBP_CREDITS_SLUG,'order_id'=>0,'item_id'=>$item_id,'currency'=>VIBEBP_CREDITS_SLUG));
							}   
						}

					} // End Commissions_array  
					$return  =array('status'=>true,'message'=>_x('Course added','','wplms'));
				}else{
					$return  =array('status'=>false,'message'=>sprintf(_x('Not enough credits! Add %s more credits!','','wplms'),($course_credits -  $balance)));
				}

			    
			}else{
				$return  =array('status'=>false,'message'=>_x('Data missing','','wplms'));
			}
			
		    return new WP_REST_Response( $return, 200 );
		}

		function course_button($request){
			$body = json_decode($request->get_body(),true);
			$course_id = $body['id'];

			
			$return = array('status'=>1,'text'=>'','course_status'=>-1,'link'=>apply_filters('bp_course_api_course_link',bp_core_get_user_domain($this->user->id).'#component=course&action=course&id='.$course_id),'extras'=>[]);


			if(bp_course_is_member($course_id, $this->user->id)){


				$time = bp_course_get_user_expiry_time($this->user->id,$course_id);
				
				if($time > time()){

					$hide_button = get_post_meta($course_id,'vibe_course_button',true);

					if($hide_button && $hide_button=='S'){
						$return['status'] = 1;
						$return['hide_button'] = 1;
					}else{
						$stop_course_status = apply_filters('wplms_before_course_status_api',false,$course_id,$this->user->id);

						if($stop_course_status && is_array($stop_course_status) && !empty($stop_course_status['error_code'])){
							$return['error'] = $stop_course_status;

						}


						$statuses = bp_course_get_user_statuses();
						$status = bp_course_get_user_course_status($this->user->id,$course_id);
						$return['course_status']=$status;

						$start_date = bp_course_get_start_date($course_id,$this->user->id);
						if(strpos($start_date,'-') !== false){
							$start_date = strtotime($start_date);
						}

						$progress = bp_course_get_user_progress($this->user->id,$course_id);
						//Course Status openning on Course Page
						$_course_data = array(
							'id'                    => $course_id,
							'name'                  => get_the_title($course_id),
							'excerpt'				=> '',
							'description'			=> '',
							'user_progress'         => empty($progress)?0:intval($progress),
							'user_status'           => $status,
							'duration'				=> bp_course_get_course_duration($course_id,$this->user->id),
							'user_expiry'           => bp_course_get_user_expiry_time($this->user->id,$course_id),
							'start_date'            => $start_date,
							'display_start_date'    => $start_date?date(get_option('date_format'),$start_date):'',
							'featured_image'		=> get_the_post_thumbnail_url($course_id),	
							'instructor'            => array(),	
							'menu_order'            => 0,
							'link'					=> get_permalink($course_id),
							'course_retakes'        => bp_course_get_course_retakes($course_id,$this->user->id),
							'user_retakes'        	=> bp_course_get_user_course_retakes($course_id,$this->user->id),
						);
						$return['course'] = $_course_data;
						$return['text'] = $statuses[$status];
						if(function_exists('vibe_get_option')){
							$take_course = vibe_get_option('take_course_page');
							if(!empty($take_course)){
								if(function_exists('vibe_get_customizer')){
					                $layout = vibe_get_customizer('course_layout');
					                if($layout!=='blank'){
					                	$return['form'] =  get_permalink($take_course);
					                	
					                }
					            }
							}
						}
					}

					
				}else{

					$free=get_post_meta($course_id,'vibe_course_free',true);
					if( !empty($free) && $free=='S'){
						$t = bp_course_add_user_to_course($this->user->id,$course_id);
				        if($t){

				            $new_duration = apply_filters('wplms_free_course_check',$t);
				            $coursetaken = $new_duration;
				            $statuses = bp_course_get_user_statuses();
							$status = bp_course_get_user_course_status($this->user->id,$course_id);
							$return['text'] = $statuses[$status];
							$return['course_status']=$status;
				        }
					}else{
						$pid=get_post_meta($course_id,'vibe_product',true);
						$pid=apply_filters('wplms_course_product_id',$pid,$course_id,-1); // $id checks for Single Course page or Course page in the my courses section
						if(!empty($body['price'])){
							$pid=get_post_meta($course_id,'vibe_product',true);
							$pid=apply_filters('wplms_course_product_id',$pid,$course_id,-1); 
							$links =[];
							if(!empty($pid) && !is_numeric($pid)){
								$links[]=array('link'=>$pid);
							}else{

								$credits = bp_course_get_course_credits(array('id'=>$course_id,'type'=>'array'));
								foreach($credits as $link=>$price){
									$links[]=array('link'=>$link,'price'=>$price);
								}
							}
							
							
							$return['link'] = $links;
				            $return['course_status']=-1;
				            $return['text'] = __('Course Expired','wplms');
				            $return['meta'][]= __('Click to renew','wplms');
						}else{
							if(!empty($pid)){
							
					            if(is_numeric($pid)){
					              $pid=get_permalink($pid);
					              $check=vibe_get_option('direct_checkout');
					              $check =intval($check);

								  if(strpos($pid,'?') > -1){$pid .= '&';}else{$pid .= '?';}
	    				          $pid .= 'redirect';
					            }

					            $return['link'] = $pid;
					            $return['course_status']=-1;
					            $return['text'] = __('Course Expired','wplms');
				            	$return['meta'][]= __('Click to renew','wplms');
							}else{
								if ( in_array( 'paid-memberships-pro/paid-memberships-pro.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

									$membership_ids = vibe_sanitize(get_post_meta($course_id,'vibe_pmpro_membership',false));
									if(isset($membership_ids) && is_Array($membership_ids) && count($membership_ids) && function_exists('pmpro_getAllLevels')){
									//$membership_id = min($membership_ids);
									$levels=pmpro_getAllLevels();
										foreach($levels as $level){
											if(in_array($level->id,$membership_ids)){
												$link = get_option('pmpro_levels_page_id');
												$link = get_permalink($link).'#'.$level->id;
												$return['link'] = $link;
												$return['course_status']=-1;
					            				$return['text'] = __('Course Expired','wplms');
				            					$return['meta'][]= __('Click to renew','wplms');
					            				break;
											}
										}
								    }
								}
							}
						}
		            }

				}
			}else{
				//check course is free. Click to enroll
				
				$free=get_post_meta($course_id,'vibe_course_free',true);
		        if( !empty($free) && $free=='S'){
		        	$auto_subscribe = 1;
	            	$auto_subscribe = apply_filters('wplms_auto_subscribe',$auto_subscribe,$course_id);
				    if($auto_subscribe){  
				        $t = bp_course_add_user_to_course($this->user->id,$course_id);
				        if($t){

				            $new_duration = apply_filters('wplms_free_course_check',$t);
				            $coursetaken = $new_duration;
				            $statuses = bp_course_get_user_statuses();
							$status = bp_course_get_user_course_status($this->user->id,$course_id);
							$return['text'] = $statuses[$status];
							$return['course_status']=$status;
				        }      
				    }else{
				    	//send bit to buy on click
				    	$return['is_free']=true;
				    }
					
	            }else{
	            	$apply=get_post_meta($course_id,'vibe_course_apply',true);
					if(!empty($apply) && $apply=='S'){

						$applied=get_user_meta($this->user->id,'apply_course'.$course_id,true);
						if(empty($applied)){
							$return['link'] = '#apply';
					        $return['text'] = __('Apply for Course','wplms');
						}else{
							$return['link'] = '#applied';
					        $return['text'] = __('Applied for Course','wplms');
						}
						
					}else{

						
						if(!empty($body['price'])){
							$pid=get_post_meta($course_id,'vibe_product',true);
							$pid=apply_filters('wplms_course_product_id',$pid,$course_id,-1); 
							$links =[];
							if(!empty($pid) && !is_numeric($pid)){
								$links[]=array('link'=>$pid);
							}else{

								$credits = bp_course_get_course_credits(array('id'=>$course_id,'type'=>'array'));
								foreach($credits as $link=>$price){
									$links[]=array('link'=>$link,'price'=>$price);
								}
							}
							
							
							$return['link'] = $links;
				            $return['course_status']=-1;
				            $return['text'] = __('Take this course','wplms');
						}else{
							if(empty($pid)){
								$pid=get_post_meta($course_id,'vibe_product',true);
								$pid=apply_filters('wplms_course_product_id',$pid,$course_id,-1); // $id checks for Single Course page or Course page in the my courses section
							}


							
							
							if(!empty($pid)){
								
					            if(is_numeric($pid)){
					              $pid=get_permalink($pid);
					              $check=vibe_get_option('direct_checkout');
					              $check =intval($check);

					              if(strpos($pid,'?') > -1){$pid .= '&';}else{$pid .= '?';}
    							  $pid .= 'redirect';
					              
					            }
					            $return['link'] = $pid;
					            $return['course_status']=-1;
					            $return['text'] = __('Take this course','wplms');	
					            
							}else{
								if ( in_array( 'paid-memberships-pro/paid-memberships-pro.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

									$membership_ids = vibe_sanitize(get_post_meta($course_id,'vibe_pmpro_membership',false));
									if(isset($membership_ids) && is_Array($membership_ids) && count($membership_ids) && function_exists('pmpro_getAllLevels')){
									//$membership_id = min($membership_ids);
									$levels=pmpro_getAllLevels();
										foreach($levels as $level){
											if(in_array($level->id,$membership_ids)){
												$link = get_option('pmpro_levels_page_id');
												$link = get_permalink($link).'#'.$level->id;
												$return['link'] = $link;$return['course_status']=-1;
					            				$return['text'] = __('Take this course','wplms');
					            				break;
											}
										}
								    }
								}
							}
						}
			            
					}
	            }
				//get seats or start date
			}
			
			$starts = bp_course_get_start_date($course_id,$this->user->id);
			$seats = bp_course_get_max_students($course_id,$this->user->id);
			if(!empty($starts) && strtotime($starts) > time()){
				$return['course_status']=-1;
				$return ['extras'][]= sprintf(_x('Starts %s','button','wplms'),date_i18n( get_option('date_format'), strtotime($starts) ));
			}
			if(!empty($seats) && $seats < 9999 ){
				$return ['extras'][]= sprintf(_x('Seats %d','button','wplms'),$seats);
			}			
			return new WP_REST_Response( apply_filters('wplms_course_button_rest',$return,$this->user,$course_id), 200 );
		}

		function get_student_lmsbadges($request){
			$body = json_decode($request->get_body(),true);
			$return = array( 'status' => 0 );

			$filter = $body['filter'];
			$offset = isset($filter['offset'])?$filter['offset']:0;

			$number = 5;
			$left_badges = $right_badges = array();
			$new_offset = array(
				'left' => null,
				'right' => null			
			);

			if(empty($filter['direction'])){
				//left badges
				$args = array(
					'post_type'=>'lmsbadge',
					'posts_per_page'=>$number,
					'search_terms' => (!empty($filter['s'])) ? $filter['s']:false,
					'fields' => 'ids',
					'meta_key'   => 'points',
					'orderby'    => 'meta_value_num',
					'order'      => 'DESC',
					'meta_query'=>array(
						array(
							'key'=> 'points',
							'value'=> $filter['starting_point'],
							'compare'=> '<',
							'type'=>'numeric'
						)
					)
				);
				$query = new WP_Query( $args );
				$badge_ids = array_reverse(is_array($query->posts) && count($query->posts) ? $query->posts : array());
				foreach ($badge_ids as $id) {
					$left_badges[] = BP_Course_New_Rest_User_Controller::get_single_badge($id);
				}
				$left_offset = count($left_badges) === $number ? $number : null;

				//right badges
				$args = array(
					'post_type'=>'lmsbadge',
					'posts_per_page'=>$number,
					'search_terms' => (!empty($filter['s'])) ? $filter['s']:false,
					'fields' => 'ids',
					'meta_key'   => 'points',
					'orderby'    => 'meta_value_num',
					'order'      => 'ASC',
					'meta_query'=>array(
						array(
							'key'=> 'points',
							'value'=> $filter['starting_point'],
							'compare'=> '>=',
							'type'=>'numeric'
						)
					)
				);
				$query = new WP_Query( $args );
				$badge_ids = is_array($query->posts) && count($query->posts) ? $query->posts : array();
				foreach ($badge_ids as $id) {
					$right_badges[] = BP_Course_New_Rest_User_Controller::get_single_badge($id);
				}

				$new_offset = array(
					'left' =>  count($left_badges) === $number ? $number : null,	
					'right' =>  count($right_badges) === $number ? $number : null,		
				);
			}else if($filter['direction'] == '<' && !empty($offset)){ //left badges
				$args = array(
					'post_type'=>'lmsbadge',
					'posts_per_page'=>$number,
					'search_terms' => (!empty($filter['s'])) ? $filter['s']:false,
					'offset' => $offset,
					'fields' => 'ids',
					'meta_key'   => 'points',
					'orderby'    => 'meta_value_num',
					'order'      => 'DESC',
					'meta_query'=>array(
						array(
							'key'=> 'points',
							'value'=> $filter['starting_point'],
							'compare'=> '<',
							'type'=>'numeric'
						)
					)
				);
				$query = new WP_Query( $args );
				$badge_ids = array_reverse(is_array($query->posts) && count($query->posts) ? $query->posts : array());
				foreach ($badge_ids as $id) {
					$left_badges[] = BP_Course_New_Rest_User_Controller::get_single_badge($id);
				}

				$new_offset = array(
					'left' => count($left_badges) === $number ? ($number+$offset) : null,		
				);
			}else if(!empty($offset)){ 	//right badges
				$args = array(
					'post_type'=>'lmsbadge',
					'posts_per_page'=>$number,
					'search_terms' => (!empty($filter['s'])) ? $filter['s']:false,
					'offset' => $offset,
					'fields' => 'ids',
					'meta_key'   => 'points',
					'orderby'    => 'meta_value_num',
					'order'      => 'ASC',
					'meta_query'=>array(
						array(
							'key'=> 'points',
							'value'=> $filter['starting_point'],
							'compare'=> '>=',
							'type'=>'numeric'
						)
					)
				);
				$query = new WP_Query( $args );
				$badge_ids = is_array($query->posts) && count($query->posts) ? $query->posts : array();
				foreach ($badge_ids as $id) {
					$right_badges[] = BP_Course_New_Rest_User_Controller::get_single_badge($id);
				}

				$new_offset = array(
					'right' => count($right_badges) === $number ? ($number+$offset) : null,		
				);
			}

			$all_badges = array_merge($left_badges,$right_badges);
			if(!empty($all_badges)){
				$return = array( 'status' => 1, 'badges' => $all_badges , 'offset' => $new_offset);
			}else{
				$return['offset'] = $new_offset;
			}
		    return new WP_REST_Response( $return, 200 );
		}

		function get_student_lmsbadge($request){
			$args = json_decode($request->get_body(),true);
			$badge_id = $args['badge_id'];
			$user_id = $this->user->id;
			$return = array( 'status' => 0 );
			if(function_exists('bp_activity_get')  && get_post_type($badge_id) === 'lmsbadge'){
				global $wpdb,$bp;
				$activities = $wpdb->get_results($wpdb->prepare("SELECT DISTINCT a.user_id from {$bp->activity->table_name} as a WHERE item_id = %d LIMIT 20",$badge_id),ARRAY_A);
				$users_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(a.user_id) from {$bp->activity->table_name} as a WHERE item_id = %d",$badge_id));
				

				$ids = array();
				foreach ($activities as $key => $value) {
					$ids[] = (int)$value['user_id'];
				}
				$data = array(
					'badge' => array(
						'user_point' => floatval($this->get_users_gamification_point($user_id)),
						'users' => array(
							'ids' => $ids,
							'total_users' => (int)$users_count
						),
						'description' => get_post_field('post_content', $badge_id)
					)
				);
				$return = array(
					'status' => 1,
					'data' => $data
				);
			}
			return new WP_REST_Response( $return, 200 );
		}

		function get_student_gamification_point($request){
			$args = json_decode($request->get_body(),true);
			$user_id = $this->user->id;
			$return = array( 'status' => 1 , 'points' => $this->get_users_gamification_point($user_id));
		    return new WP_REST_Response( $return, 200 );
		}

		function get_users_gamification_point($user_id){	
			return floatval(get_user_meta($user_id,'gamification_point',true));
		}

		function get_student_lmsbadges_activities($request){
			$post = json_decode($request->get_body(),true);
			$filter = $post['filter'];
			$user_id = $this->user->id;

			$return = array( 'status' => 0 );
			if(!empty($filter)){
				$args = array(
					'page' => (!empty($filter['paged'])) ? $filter['paged']:1,
					'per_page' => (!empty($filter['per_page'])) ? ($filter['per_page']<20?$filter['per_page']:20) : 20,
					'search_terms' => (!empty($filter['s'])) ? $filter['s']:false,
					'sort' => (!empty($filter['order'])) ? $filter['order']:false,
					'filter' => array(
						'action' => array('gamification_badge','added_gamification_point'),	
						'user_id' => $user_id
					)
				);
				$results = bp_activity_get($args);
				if(!empty($results['activities'])){
					$activities = array();
					foreach ($results['activities'] as $key => $activity) {
						$activities[] = $activity;
					}
					$return = array( 'status' => 1 , 'activities'=> $activities ,'has_more_items'=> $results['has_more_items']);
				}
			}
	    	return new WP_REST_Response($return, 200);
		}
	}//end of class
}
