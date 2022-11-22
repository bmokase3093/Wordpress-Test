<?php

/*
Dear ThimPress, please be original and this time do not copy our code !
 */

defined( 'ABSPATH' ) or die();

if ( ! class_exists( 'BP_Course_Rest_Xapi_Controller' ) ) {
	
	class BP_Course_New_Rest_Xapi_Controller{

		
		/**
		 * Register the routes for the objects of the controller.
		 *
		 * @since 3.0.0
		 */
		public function register_routes() {



			//$this->token = '2tz745fwp6d7z1d50euboegms7pgvglbnn5biilw';
			
			$this->namespace = BP_COURSE_API_NAMESPACE;
			$this->type = 'xapi';

			register_rest_route( $this->namespace, '/'. $this->type .'/course/(?P<course>\w+)/statements', array(
				'methods'                   =>   'PUT',
				'callback'                  =>  array( $this, 'update_xapidata' ),
				 'permission_callback' => array( $this, 'get_user_permissions_check' ),
				'args'                     	=>  array(
					
					'course'                       	=>  array(
						'validate_callback'     =>  function( $param, $request, $key ) {
													return is_numeric( $param );
												}
					),
				),
			) );

			//to set initial state of package
			register_rest_route( $this->namespace, '/'. $this->type .'/course/(?P<course>\w+)/activities/state', array(
				'methods'                   =>   'PUT',
				'callback'                  =>  array( $this, 'save_state' ),
				 'permission_callback' => array( $this, 'get_user_permissions_check' ),
				'args'                     	=>  array(
					
					'course'                       	=>  array(
						'validate_callback'     =>  function( $param, $request, $key ) {
													return is_numeric( $param );
												}
					),
				),
			) );



			//to get initial state of package
			register_rest_route( $this->namespace, '/'. $this->type .'/course/(?P<course>\w+)/activities/state', array(
				'methods'                   =>   'GET',
				'callback'                  =>  array( $this, 'get_state' ),
				 'permission_callback' => array( $this, 'get_user_permissions_check' ),
				'args'                     	=>  array(
					
					'course'                       	=>  array(
						'validate_callback'     =>  function( $param, $request, $key ) {
													return is_numeric( $param );
												}
					),
				),
			) );

			register_rest_route( $this->namespace, '/'. $this->type .'/course/(?P<course>\w+)/(?P<item>\w+)', array(
				'methods'                   =>   'POST',
				'callback'                  =>  array( $this, 'update_xapidata' ),
				 'permission_callback' => array( $this, 'get_user_permissions_check' ),
				'args'                     	=>  array(
					
					'course'                       	=>  array(
						'validate_callback'     =>  function( $param, $request, $key ) {
													return is_numeric( $param );
												}
					),
					'item'                       	=>  array(
						'validate_callback'     =>  function( $param, $request, $key ) {
													return !empty( $param );
												}
					),
				),
			) );


			register_rest_route( $this->namespace, '/'. $this->type .'/course/get', array(
				'methods'                   =>   'POST',
				'callback'                  =>  array( $this, 'get_xapidata' ),
				 'permission_callback' => array( $this, 'get_user_permissions_check' ),
				
			) );
			
			register_rest_route( $this->namespace, '/'. $this->type .'/course/finish', array(
				'methods'                   =>   'POST',
				'callback'                  =>  array( $this, 'finish_xapi_module' ),
				 'permission_callback' => array( $this, 'get_user_permissions_check' ),
				'args'                     	=>  array(
					'moduleid'                       	=>  array(
						'validate_callback'     =>  function( $param, $request, $key ) {
													return is_numeric( $param );
												}
					),
				),
			) );
				
	    }

		public function get_user_permissions_check($request){
			 //$this->user_id = 1; $this->user->id = 1;return true;
			$body = json_decode($request->get_body(),true);
			
			if(!empty($body['token'])){
	            $this->user = apply_filters('vibebp_api_get_user_from_token','',$body['token']);
	            if(!empty($this->user)){
	            	$this->user_id = $this->user->id;
	                return true;
	            }
	        }
	        if(!empty($_POST['token'])){
	        	$this->user = apply_filters('vibebp_api_get_user_from_token','',$_POST['token']);
	            if(!empty($this->user)){
	            	$this->user_id = $this->user->id;
	                return true;
	            }
	        }

	         
			$headers = vibe_getallheaders();
			if(isset($headers['Authorization'])){
				$token = $headers['Authorization'];

				$this->token = str_replace('Basic ', '', $token);
				$this->user = apply_filters('vibebp_api_get_user_from_token','',$this->token);
				if(!empty($this->user)){
	            	$this->user_id = $this->user->id;
	                return true;
	            }
			}

			return false;
		}

		function get_user_from_token($token){
			global $wpdb;
			$user_id = $wpdb->get_var(apply_filters('wplms_usermeta_direct_query',"SELECT user_id FROM {$wpdb->usermeta} WHERE meta_key = '$token'"));

			if(is_numeric($user_id)){
				return $user_id;
			}

			return false;
			
		}

		function save_state($request){
			$stateId = $request->get_param('stateId');

			if($stateId=='bookmark'){
				$body = $request->get_body();
			}else{
				$body = json_decode($request->get_body(),true);
				
			}
			$course_id = $request->get_param('course');
			
			$data = get_user_meta($this->user->id,'xapi_data_'.$course_id,true);
			if(empty($data)){
				$data =array();
			}
			if($stateId=='cumulative_time'){
				$body = (string)$body;
			}
			$data[$stateId] = $body;
			update_user_meta($this->user->id,'xapi_data_'.$course_id,$data);

			$data['debug'] = $request->get_body();
			return new WP_REST_Response($data, 200);
		}

		function get_state($request){
			$body = json_decode($request->get_body(),true);
			$course_id = $request->get_param('course');
			$stateId = $request->get_param('stateId');
			$data = get_user_meta($this->user->id,'xapi_data_'.$course_id,true);
			$return = array();
			if(empty($data)){
				$data= array();
			}
			if(!empty($data[$stateId])){
				$return = $data[$stateId];
			}
			if($stateId=='bookmark' || $stateId=='cumulative_time'){
				if(!empty($return)){
					header('Content-Type: application/octet-stream');
					//header('cache-control: no-cache');
					echo (string)$return ;
					exit();
				}else{
					header('Content-Type: application/octet-stream');
					exit();
				}
			}else{
				if(!empty($return)){
					header('Content-Type: application/octet-stream');
					//header('cache-control: no-cache');
					
					echo json_encode($return) ;
					exit();
				}else{
					header('Content-Type: application/octet-stream');
					exit();
				}
			}
			
		}


		function update_xapidata($request){
			$body = json_decode($request->get_body(),true);
			$course_id = $request->get_param('course');
			$type = '';
			$completed= false;
			if(!empty($body)){
				if(!empty($body['verb']) && !empty($body['verb']['display'])){
					$vv = array_values($body['verb']['display']);
					$type = $vv[0];
				}
				if(!empty($body['result']) && !empty($body['result']['completion'])){
					$completed= true;
				}
			}
			if(!empty($type)){
				switch ($type) {
					case 'attempted':
						//whenever course is opened
						//record activity
						
					case 'answered':
						//whenever a question is answered in a quiz
						//record activity
						
					case 'passed':
					case 'failed':
						//whenever user passed/failed the courese
						//record activity

					case 'experienced':
						//when a unit completed
						//record activity
						$object = $body['object']['id'];
						if(in_array($type, $this->get_recordable_xapi_types($body,$this->user_id,$course_id))){
							$args = array(
								'action' => sprintf(__('Student %s ','wplms'),$this->get_type_translation($type)),
							    'content' => sprintf(__('Student %s %s %s','wplms'),bp_core_get_userlink($this->user_id),$this->get_type_translation($type),$object),
							    'type' => $type,
							    'user_id' => $this->user_id,
							    'primary_link' => get_permalink($course_id),
							    'item_id' => $course_id,
							);
							$activity_id =bp_course_record_activity($args);
							bp_course_record_activity_meta(array('id'=>$activity_id,'meta_key'=>'data','meta_value'=>$body));
						}
						break;
					case 'progressed':
						//progresses to new unit
						$progress  = 0;
						if(!empty($body['result']) && !empty($body['result']['extensions'])){
							$progress = array_values($body['result']['extensions'])[0];
							bp_course_update_user_progress($this->user_id,$course_id,$progress);
						}
						if(in_array($type, $this->get_recordable_xapi_types($body,$this->user_id,$course_id))){
							$object = $body['object']['id'];

							$args = array(
								'action' => sprintf(__('Student %s ','wplms'),$this->get_type_translation($type)),
							    'content' => sprintf(__('Student %s %s %s','wplms'),bp_core_get_userlink($this->user_id),$this->get_type_translation($type),$object),
							    'type' => $type,
							    'user_id' => $this->user_id,
							    'primary_link' => get_permalink($course_id),
							    'item_id' => $course_id,
							);
							$activity_id =bp_course_record_activity($args);
							bp_course_record_activity_meta(array('id'=>$activity_id,'meta_key'=>'data','meta_value'=>$body));
						}
						
						break;
					
					
					default:
						
						break;
				}
			}

			if($completed){
				//finish the course dammit
				//update the marks
				/*[score] => Array
                (
                    [raw] => 100
                    [min] => 0
                    [max] => 100
                )*/
				if(!empty($body['result']) ){
					if(!empty($body['result']['score'])){
						$marks = (($body['result']['score']['raw']/$body['result']['score']['max'])*100);
						update_post_meta($course_id,$this->user_id,round($marks));
						$this->finish_course_check($course_id,$this->user_id,round($marks));
					}else{
						update_post_meta($course_id,$this->user_id,100);
						$this->finish_course_check($course_id,$this->user_id,100);
					}
					
				}else{
					update_post_meta($course_id,$this->user_id,100);
					$this->finish_course_check($course_id,$this->user_id,100);
				}
				


			}

			
	    	return new WP_REST_Response($return, 200);
	    }

	    function get_type_translation($type){
	    	$ret_type = '';
	    	if(!empty($type)){
	    		switch ($type) {
	    			case 'experienced':
						$ret_type = _x('experienced','','wplms');
						break;
					
					case 'attempted':
						//whenever course is opened
						//record activity
						
						$ret_type = _x('attempted','','wplms');
						

						break;
					case 'answered':
						//whenever a question is answered in a quiz
						//record activity
						
						$ret_type = _x('answered','','wplms');
						break;
					case 'passed':
						//whenever user passed the courese
						//record activity
						$ret_type = _x('passed','','wplms');
						break;	
					default:
						$ret_type =$type;
						break;
	    		}
	    	}

	    	return $ret_type;
	    }

	    function finish_course_check($course_id,$user_id,$marks){
	    	$course_curriculum = array();
	    	$flag = 0;
	    	$flag = apply_filters('wplms_finish_course_check_upload_course',$flag,$course_curriculum,$course_id);

	        if(!$flag){
	        	$return_array = array('status'=>1);
		          $id = $course_id;
		          $auto_eval = get_post_meta($id,'vibe_course_auto_eval',true);
		          
		          if(vibe_validate($auto_eval)){

		            // AUTO EVALUATION
		            $return_array['course_status']=4;
		            
		            do_action('wplms_submit_course',$id,$user_id);
		            // Apply Filters on Auto Evaluation
		            $u_marks = get_post_meta($id,$user_id,true);
		            if(empty($u_marks)){
		              $u_marks=100;
		            }
		            $u_marks = intval($u_marks);
		            $return .='<div class="message" class="updated"><p>'.__('COURSE EVALUATED ','wplms').'</p></div>';

		            $badge_per = get_post_meta($id,'vibe_course_badge_percentage',true);

		            $passing_cert = get_post_meta($id,'vibe_course_certificate',true); // Certificate Enable
		            $passing_per = get_post_meta($id,'vibe_course_passing_percentage',true); // Certificate Passing Percentage

		            //finish bit for student 1.8.4
		            update_user_meta($user_id,'course_status'.$id,3);
		            //end finish bit
		            
		              do_action('wplms_evaluate_course',$id,$marks,$user_id,1);
		              
		              $badge_filter = 0;
		            if(isset($badge_per) && $badge_per && $marks >= $badge_per)
		                $badge_filter = 1;
		      
		              $badge_filter = apply_filters('wplms_course_student_badge_check',$badge_filter,$course_id,$user_id,$marks,$badge_per);
		              if($badge_filter){  
		                  $badges = array();
		                  $badges= vibe_sanitize(get_user_meta($user_id,'badges',false));

		                  if(isset($badges) && is_array($badges)){
		                    if(!in_array($id,$badges)){
		                      $badges[]=$id;
		                    }
		                  }else{
		                    $badges=array($id);
		                  }

		                  update_user_meta($user_id,'badges',$badges);
		            		
		                  

		                  do_action('wplms_badge_earned',$id,$badges,$user_id,$badge_filter);
		              }
		              $passing_filter =0;
		              if(vibe_validate($passing_cert) && isset($passing_per) && $passing_per && $marks >= $passing_per)
		                $passing_filter = 1;

		              $passing_filter = apply_filters('wplms_course_student_certificate_check',$passing_filter,$course_id,$user_id,$marks,$passing_per);
		              
		              if($passing_filter){
		                  $pass = array();
		                  $pass=vibe_sanitize(get_user_meta($user_id,'certificates',false));
		                  
		                  if(isset($pass) && is_array($pass)){
		                    if(!in_array($id,$pass)){
		                      $pass[]=$id;
		                    }
		                  }else{
		                    $pass=array($id);
		                  }

		                  update_user_meta($user_id,'certificates',$pass);
		                 
		                  do_action('wplms_certificate_earned',$id,$pass,$user_id,$passing_filter);
		              }

		              

		              $course_end_status = apply_filters('wplms_course_status',4);  
		            	update_user_meta( $user_id,'course_status'.$id,$course_end_status);//EXCEPTION  

		              $message = sprintf(__('You\'ve obtained %s in course %s ','wplms'),apply_filters('wplms_course_marks',$marks.'/100',$course_id),' <a href="'.get_permalink($id).'">'.get_the_title($id).'</a>'); 
		              $return_array['message']=$message;
		              $return .='<div class="congrats_message">'.$message.'</div>';

		              

		          }else{
		          	$return_array['course_status']=3;
					 	$return_array['title']= __('Course submitted for Evaluation.','wplms');
					 	$return_array['message']= __('Course submitted for Evaluation.','wplms');
		            $return .='<div class="message" class="updated"><p>'.__('COURSE SUBMITTED FOR EVALUATION','wplms').'</p></div>';
		            bp_course_update_user_course_status($user_id,$id,3);// 2 determines Course is Complete
		            do_action('wplms_submit_course',$post->ID,$user_id);
		          }
	          
	          
	        }
	    }

	    
	    function get_xapidata($request){
	    	$data = array();
	    	//getting $_POST
	    	$module_id = $_POST['module_id'];
	    	$course_id = $_POST['course_id'];
	    	if(empty($module_id) || (!empty($module_id) && $module_id == 'undefined')){
	    		$module_id = $course_id;
	    	}
	    	
	    	$package_name = 'wplms_xapi_'.$module_id.'_'.$_POST['key'];
	    	$data = get_user_meta($this->user_id,$package_name,true);
	    	if(!empty($data)){
	    		return new WP_REST_Response(array('status'=>true,'data'=>$data), 200);
	    	}
	    	return new WP_REST_Response(array('status'=>false,'message'=>_x('No data found','','wplms')), 200);
	    }

	    function update_xapi_course_progress($request){
	    	$data = array();
	    	$course_id = $_POST['course_id'];
	    	$progress = $_POST['progress'];
	    	$progress = round($progress,2);
	    	if(!empty($course_id) && function_exists('bp_course_is_member') && bp_course_is_member($course_id,$this->user_id)){
				bp_course_update_user_progress($this->user_id,$course_id,$progress);

				$data = array(
		    		'status'=>true,
		    		'marks'=>$marks,
		    		'message'=>_x('Course progress set!','','wplms')
		    	);
		    	return new WP_REST_Response($data, 200);
			}else{
				$data = array(
		    		'status'=>false,
		    		'message'=>_x('Not a course member','','wplms')
		    	);
		    	return new WP_REST_Response($data, 200);
			}
	    }

		function finish_xapi_module($request){
	    	$data = array();
	    	//getting $_POST
	    	
	    	$module_id = $_POST['module_id'];
	    	$course_id = $_POST['course_id'];
	    	$total_marks = $_POST['total_marks'];
	    	$user_marks = $_POST['user_marks'];
	    	$package_name = $_POST['key'];
	    	$marks = ($user_marks/$total_marks)*100;
	    	$marks = round($marks);
	    	
    		if(!empty($_POST['type'])){
    			$type = sanitize_text_field($_POST['type']);
    			switch ($type) {
    				case 'course':
    					//handle course
    					if(!empty($course_id) && function_exists('bp_course_is_member') && bp_course_is_member($course_id,$this->user_id)){
    						update_post_meta($course_id,$this->user_id,$marks);
    						bp_course_update_user_progress($this->user_id,$course_id,100);
    						delete_user_meta($this->user_id,$package_name);

    						$data = array(
					    		'status'=>true,
					    		'marks'=>$marks,
					    		'message'=>_x('Quiz marks set!','','wplms')
					    	);
					    	return new WP_REST_Response($data, 200);
    					}else{
    						$data = array(
					    		'status'=>false,
					    		'message'=>_x('Not a course member','','wplms')
					    	);
					    	return new WP_REST_Response($data, 200);
    					}
    					break;
    				case 'quiz':
    					//handle quiz
    					if( !empty($module_id) ){
    						global $wpdb;
      						global $bp;
    						$quiz_id = $module_id;
    						if(empty($course_id)){
    							$course_id = get_post_meta($quiz_id,'vibe_quiz_course',true);
    						}
    						
    						if(empty($course_id)){
    							$course_id= 0;
    						}

					        $time = apply_filters('wplms_xapi_complete_unit',time(),$quiz_id,$course_id,$this->user_id);
					        update_user_meta($this->user_id,$quiz_id,$time);
					        update_post_meta($quiz_id,$this->user_id,$user_marks);
					        $questions = array(
				            	'ques'=>array(_x('quiz','','wplms')),
				            	'marks'=>array($total_marks),
				            );
				          	bp_course_update_quiz_questions($quiz_id,$this->user_id,$questions);

				          	update_post_meta($quiz_id,$this->user_id,$user_marks);

					        do_action('wplms_submit_quiz',$quiz_id,$this->user_id,$questions);

					        if(empty($_POST['is_take_course']) && !empty($course_id)){
					        	$curriculum = bp_course_get_curriculum_units($course_id);
						        $per = round((100/count($curriculum)),2);
						        $progress = bp_course_get_user_progress($this->user_id,$course_id);
						        $new_progress = $progress+$per;

						        if($new_progress > 100){
						          $new_progress = 100;
						        }

						        bp_course_update_user_progress($this->user_id,$course_id,$new_progress);
					        }
					        
					        bp_course_update_user_quiz_status($this->user_id,$quiz_id,4);
					        do_action('wplms_evaluate_quiz',$quiz_id,$user_marks,$this->user_id,$total_marks);
					        $activity_id = $wpdb->get_var($wpdb->prepare( "
					                    SELECT id 
					                    FROM {$bp->activity->table_name}
					                    WHERE secondary_item_id = %d
					                  AND type = 'quiz_evaluated'
					                  AND user_id = %d
					                  ORDER BY date_recorded DESC
					                  LIMIT 0,1
					                " ,$quiz_id,$this->user_id));
					        if(!empty($activity_id)){
					            $user_result = array($package_name.$quiz_id => array(
					                                            'content' => _x('Quiz evaluated','','wplms'),
					                                            'marks' => $user_marks,
					                                            'max_marks' => $total_marks
					                                          )
					                          );
					        	bp_course_record_activity_meta(array('id'=>$activity_id,'meta_key'=>'quiz_results','meta_value'=>$user_result));
					    	}
					    	delete_user_meta($this->user_id,$package_name);
					    	$data = array(
					    		'status'=>true,
					    		'message'=>_x('Quiz marks set!','','wplms')
					    	);
					    	return new WP_REST_Response($data, 200);
					    }else{
					    	$data = array(
					    		'status'=>false,
					    		'message'=>_x('Not a course member','','wplms')
					    	);
					    	return new WP_REST_Response($data, 200);
					    }
    					break;
    				case 'unit':
    					//handle unit
	    				if(empty($_POST['is_take_course'])){
	    					if(!empty($course_id) && !empty($module_id) && function_exists('bp_course_is_member') && bp_course_is_member($course_id,$this->user_id)){

						        $time = apply_filters('wplms_xapi_complete_unit',time(),$module_id,$course_id,$this->user_id);

						        update_user_meta($this->user_id,$module_id,$time);
						        update_post_meta($module_id,$this->user_id,0);

					          	if(function_exists('bp_course_update_user_unit_completion_time')){
					            	bp_course_update_user_unit_completion_time($this->user_id,$module_id,$course_id,$time);
					          	}
					          	
						        $curriculum = bp_course_get_curriculum_units($course_id);
						        $per = round((100/count($curriculum)),2);
						        $progress = bp_course_get_user_progress($this->user_id,$course_id);
						        $new_progress = $progress+$per;

						        if($new_progress > 100){
						          $new_progress = 100;
						        }

						        bp_course_update_user_progress($this->user_id,$course_id,$new_progress);
						        do_action('wplms_unit_complete',$module_id,$new_progress,$course_id,$this->user_id );
						        delete_user_meta($this->user_id,$package_name);
						        $data = array(
						    		'status'=>true,
						    		'message'=>_x('Unit marked complete','','wplms')
						    	);
						    	return new WP_REST_Response($data, 200);
						    }else{
						    	$data = array(
						    		'status'=>false,
						    		'message'=>_x('Not a course member','','wplms')
						    	);
						    	return new WP_REST_Response($data, 200);
						    }
	    				}
    					break;
    				case 'wplms-assignment':

			            $user = get_userdata($this->user_id);
			            $assignment_title = get_the_title($module_id);
			            $args = array(
			                    'comment_post_ID' => $module_id,
			                    'comment_author' => $user->display_name,
			                    'comment_author_email' => $user->user_email,
			                    'comment_content' => $assignment_title.' - '.$user->display_name,
			                    'comment_date' => current_time('mysql'),
			                    'comment_approved' => 1,
			                    'comment_parent' => 0,
			                    'user_id' => $this->user_id,
			            );
			            wp_insert_comment($args);
				          
    					break;
    				default:
    					do_action('wplms_handle_finish_xapi_module',$module_id,$course_id,$type,$request,$_POST);
    					$data = array();
    					$data = apply_filters('wplms_handle_finish_xapi_module_data_filter',$data,$module_id,$course_id,$type,$request,$_POST);
    					return new WP_REST_Response($data, 200);
    					break;
    			}
    		}
	    	


	    	$data = array(
	    		'status'=>false,
	    		'message'=>_x('Something went wrong!','','wplms')
	    	);
	    	return new WP_REST_Response($data, 200);
	    }

	    function get_recordable_xapi_types($body,$user_id,$course_id){
			return apply_filters('recordable_xapi_types',array('experienced','attempted','passed'),$body,$user_id,$course_id);
		}
		
	}

	
}