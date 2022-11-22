<?php

defined( 'ABSPATH' ) or die();

if ( ! class_exists( 'WPLMS_Firebase_Stale_Requests' ) ) {
	
	class WPLMS_Firebase_Stale_Requests{

		public $tracker;
		public $usertracker;
		
		public static $instance;
		public static function init(){

	        if ( is_null( self::$instance ) )
	            self::$instance = new WPLMS_Firebase_Stale_Requests();
	        return self::$instance;
	    }

	    function __construct(){
	    	//allcourses
	    	//add_action('wp_insert_post',array($this,'check_post_updates'),10,2);
	    	add_action('save_post',array($this,'check_post_updates'),10,2);
	    	//add_filter('wplms_front_end_field_saved',array($this,'check_course_updates_front'),10,2); //coz above do that

	    	
	    	add_action('wplms_course_subscribed',array($this,'reload_mycourses'),10,2);
	    	add_action('wplms_course_unsubscribed',array($this,'reload_mycourses'),10,3);

	    	add_action('wplms_course_retake',array($this,'reload_mycourses'),10,2);
			add_action('wplms_evaluate_course',array($this,'reload_mycourses_sub_eval'),10,3); 
			add_action('wplms_submit_course',array($this,'reload_mycourses'),10,3); 
			
			add_action('wplms_course_reset',array($this,'reload_mycourses_reset'),10,2);
			
			add_action('wplms_bulk_action',array($this,'check_extend_status_change'),10,3);
			
	 		add_action('wplms_badge_earned',array($this,'reload_badges'),10,3);
	 		add_action('wplms_certificate_earned',array($this,'reload_certis'),10,3);

	    	add_action('wplms_submit_quiz',array($this,'reload_quiz'),10,2);
	    	add_action('wplms_quiz_reset',array($this,'reload_my_quiz_reset'),10,2);
	    	add_action('wplms_evaluate_quiz',array($this,'reload_my_quiz_evaluate'),10,3);
	    	add_action('wplms_quiz_retake',array($this,'reload_quiz'),10,2);

	    	add_action('wplms_submit_assignment',array($this,'reload_myassignment'),10,2);
	    	add_action('wplms_assignment_reset',array($this,'reload_myassignment_reset'),10,3);
	    	add_action('wplms_evaluate_assignment',array($this,'reload_myassignment_evaluate'),10,3);

			
			add_action('wplms_unit_instructor_complete',array($this,'reload_statusitem'),10,3);
			add_action('wplms_unit_instructor_uncomplete',array($this,'reload_statusitem'),10,3);
			add_action('wplms_dashboard_course_announcement',array($this,'check_announcements'),10);

			add_action('wplms_user_quiz_assigned',array($this,'check_assigned_user_quizzes'),10,2);
			add_action('wplms_course_quiz_assigned',array($this,'check_assigned_course_quizzes'),10,2);

			add_action('wplms_course_offlined',array($this,'wplms_course_offlined'),10,2);

			add_action('wplms_front_end_field_save',array($this,'check_curriculum'),10,2);
	    }

	    function update_stale_requests($user_id,$urls=null){
	    	if(!function_exists('vibebp_fireabase_update_stale_requests'))return;
	    	if(!empty( vibebp_get_setting('firebase_service_email') ) && !empty(vibebp_get_setting('firebase_private_key') )  && !empty(vibebp_get_setting('firebase_UID'))){
	    		if(!empty($urls)){
	    			vibebp_fireabase_update_stale_requests($user_id,$urls);
	    		}
	    	}
	    }

	    function process_stale_requests($key,$role,$find=null,$replace=null){
	    	$reqs = wplms_stale_requests_map();
    		if(!empty($reqs) && !empty($reqs[$key])){
    			foreach($reqs[$key][$role] as $k => $req){
    				if($find){
    					$req=str_replace($find, $replace, $req);
    				}
    				$reqs[$key][$role][$k]=$req;
    			}
    			return $reqs[$key][$role];
    		}
	    }
	  

	    // All course count
	    function check_post_updates($post_id,$post=null){
	    	if ($post->post_status!=='publish') {
	    		return;
	    	}
	    	if(!empty($post) && $post->post_type == 'course'){
	    		$reqs = $this->process_stale_requests('course_updated','student','{course_id}',$post_id);
	    		if(!empty($reqs)){
	    			$this->update_stale_requests('global',$reqs);
	    		}
	    		if(!empty($post->author)){
	    			//should be handled at the js level by updating or removing localforage
	    		}
	    		$data = array(
    				'property'=>'id',
    				'time'=>time(),
    			);
	    		if(function_exists('vibebp_fireabase_update_stale_request_data')){
    				vibebp_fireabase_update_stale_request_data('global','details|courses',$post_id,$data);
    			}
	    	}

	    	if(!empty($post) && $post->post_type == 'quiz'){
	    		$reqs = $this->process_stale_requests('quiz_updated','student','{quiz_id}',$post_id);
	    		if(!empty($reqs)){
	    			$this->update_stale_requests('global',$reqs);
	    		}
	    		$data = array(
    				'property'=>'id',
    				'time'=>time(),
    			);
    			if(function_exists('vibebp_fireabase_update_stale_request_data')){
    				vibebp_fireabase_update_stale_request_data('global','details|quizzes',$post_id,$data);
    			}
	    		

	    	}
	    	if(!empty($post) && $post->post_type == 'unit'){
	    		$reqs = $this->process_stale_requests('unit_updated','student','{unit_id}',$post_id);
	    		if(!empty($reqs)){
	    			$this->update_stale_requests('global',$reqs);
	    		}
	    	}
	    	

	    	if(!empty($post) && $post->post_type == 'wplms-assignment'){
	    		$reqs = $this->process_stale_requests('assignment_updated','student','{assignment_id}',$post_id);
	    		if(!empty($reqs)){
	    			$this->update_stale_requests('global',$reqs);
	    		}
	    		$data = array(
    				'property'=>'id',
    				'time'=>time(),
    			);
    			if(function_exists('vibebp_fireabase_update_stale_request_data')){
    				vibebp_fireabase_update_stale_request_data('global','details|assignments',$post_id,$data);
    			}

	    	}

	    	if(!empty($post) && $post->post_type == 'news'){
	    		$course_id = get_post_meta($post_id,'vibe_news_course',true);
	    		if(!empty($course_id)){
	    			$reqs = $this->process_stale_requests('news_updated','student','{course_id}',$course_id);
		    		if(!empty($reqs)){
		    			$this->update_stale_requests('global',$reqs);
		    		}
	    		}
	    	}
	    }

	    function wplms_course_offlined($course_id){
	    	$data = array(
				'property'=>'id',
				'time'=>time(),
			);
    		if(function_exists('vibebp_fireabase_update_stale_request_data')){
				vibebp_fireabase_update_stale_request_data('global','details|courses',$course_id,$data);
			}
	    }

	    function check_course_updates_front($post_id,$args){
	    	$reqs = $this->process_stale_requests('course_updated','student','{course_id}',$post_id);
    		if(!empty($reqs)){
    			$this->update_stale_requests('global',$reqs);
    		}
    		if(!empty($post->author)){
    			//should be handled at the js level by updating or removing localforage
    		}
	    }

	    
	    function reload_mycourses($course_id,$user_id){
	    	$reqs = $this->process_stale_requests('wplms_course_subscribed','student','{course_id}',$course_id);
    		if(!empty($reqs)){
    			$this->update_stale_requests($user_id,$reqs);
    		}
	    	
    	}
    	function reload_mycourses_sub_eval($course_id,$marks,$user_id){
	    	$reqs = $this->process_stale_requests('wplms_course_subscribed','student','{course_id}',$course_id);
    		if(!empty($reqs)){
    			$this->update_stale_requests($user_id,$reqs);
    		}
	    	
    	}
    	

	    function reload_mycourses_reset($course_id,$user_id){
	    	$reqs = $this->process_stale_requests('wplms_course_reset','student','{course_id}',$course_id);
    		if(!empty($reqs)){
    			$this->update_stale_requests($user_id,$reqs);
    		}
	    	
    	}

    	function check_extend_status_change($action,$course_id,$members){
    		$reqs = '';
    		if($action == 'extend_course_subscription' || $action == 'change_course_status'){
    			$reqs = $this->process_stale_requests('wplms_course_extend_subscription_change_status','student','{course_id}',$course_id);
    		}
    		if($action =='add_badge'  || $action == 'remove_badge'){
    			$reqs = $this->process_stale_requests('wplms_badge_earned','student','{course_id}',$course_id);
    		}
    		if($action=='add_certificate' || $action=='remove_certificate'){
    			$reqs = $this->process_stale_requests('wplms_certificate_earned','student','{course_id}',$course_id);
    		}
    		foreach($members as $member){
    			$this->update_stale_requests($member,$reqs);
    		}
    		
    	}
 		function reload_badges($course_id,$badges,$user_id){
 			$reqs = $this->process_stale_requests('wplms_badge_earned','student','{course_id}',$course_id);
    		if(!empty($reqs)){
    			$this->update_stale_requests($user_id,$reqs);
    		}
    		if(!empty($post->author)){
    			//should be handled at the js level by updating or removing localforage
    		}
 		}


 		function reload_certis($course_id,$badges,$user_id){
 			$reqs = $this->process_stale_requests('wplms_certificate_earned','student','{course_id}',$course_id);
    		if(!empty($reqs)){
    			$this->update_stale_requests($user_id,$reqs);
    		}
    		if(!empty($post->author)){
    			//should be handled at the js level by updating or removing localforage
    		}
 		}

		function reload_statusitem($unit_id,$course_id,$user_id){
	    	$reqs = $this->process_stale_requests('unit_updated','student','{unit_id}',$post_id);
    		if(!empty($reqs)){
    			$this->update_stale_requests($user_id,$reqs);
    		}
		}
    	function reload_myassignment($assignment_id,$user_id){
	    	
	    	$reqs = $this->process_stale_requests('wplms_submit_assignment','student','{assignment_id}',$assignment_id);
    		if(!empty($reqs)){
    			$this->update_stale_requests($user_id,$reqs);
    		}
    	}
    	function reload_myassignment_evaluate($assignment_id,$marks,$user_id){
	    	$reqs = $this->process_stale_requests('wplms_evaluate_assignment','student','{assignment_id}',$assignment_id);
    		if(!empty($reqs)){
    			$this->update_stale_requests($user_id,$reqs);
    		}
    	}
    	
    	function reload_myassignment_reset($assignment_id,$user_id){
    		$reqs = $this->process_stale_requests('wplms_evaluate_assignment','student','{assignment_id}',$assignment_id);
    		if(!empty($reqs)){
    			$this->update_stale_requests($user_id,$reqs);
    		}
    	}

    	function reload_quiz($quiz_id,$user_id){
	    	
	    	$reqs = $this->process_stale_requests('wplms_submit_quiz','student','{quiz_id}',$quiz_id);
    		if(!empty($reqs)){
    			$this->update_stale_requests($user_id,$reqs);
    		}
    	}
    	function reload_my_quiz_reset($quiz_id,$user_id){
    		$reqs = $this->process_stale_requests('wplms_evaluate_quiz','student','{quiz_id}',$quiz_id);
    		if(!empty($reqs)){
    			$this->update_stale_requests($user_id,$reqs);
    		}
    	}

    	function reload_my_quiz_evaluate($quiz_id,$marks,$user_id){
	    	$reqs = $this->process_stale_requests('wplms_evaluate_quiz','student','{quiz_id}',$quiz_id);
    		if(!empty($reqs)){
    			$this->update_stale_requests($user_id,$reqs);
    		}
    	}
    	function check_announcements($course_id){
    		
    		$reqs = $this->process_stale_requests('course_announcements','student','{course_id}',$course_id);
    		if(!empty($reqs)){
    			$this->update_stale_requests('global',$reqs);
    		}
    	}

    	function check_assigned_user_quizzes($user_id,$quiz_id){
    		$reqs = $this->process_stale_requests('wplms_quiz_assigned','student','{quiz_id}',$quiz_id);
    		if(!empty($reqs)){
    			$this->update_stale_requests($user_id,$reqs);
    		}
    	}

    	function check_assigned_course_quizzes($course,$quiz_id){
    		//yaha fas gye
    	}

    	function check_curriculum($course_id,$meta){
    		if(!empty($meta) && $meta['meta_key']=='vibe_course_curriculum' && $meta['is_changed']){
    			
    			$reqs = $this->process_stale_requests('course_curriculum_updated','student','{course_id}',$course_id);
	    		if(!empty($reqs)){
	    			$this->update_stale_requests('global',$reqs);
	    		}
    		}
    	}
    }
}

WPLMS_Firebase_Stale_Requests::init();