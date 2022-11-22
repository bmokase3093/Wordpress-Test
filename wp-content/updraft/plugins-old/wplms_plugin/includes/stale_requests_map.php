<?php 

function wplms_stale_requests_map($args=null){
	 $blog_id = '';
    if(function_exists('get_current_blog_id')){
        $blog_id = get_current_blog_id();
    }
	$base_api_url = untrailingslashit(apply_filters('vibebp_rest_api',get_rest_url($blog_id,WPLMS_API_NAMESPACE))); 
	//NOTE: do not start or end with "/" as it will add node in root 
	$map = apply_filters('wplms_stale_requests_map',array(
		'course_updated' => array(
			'student' => array(
				'coursestatus/{course_id}',
				'student/getcourseTabs/{course_id}',
				
			),
			'instructor' => array(
				
			)
		),

		'course_announcements' => array(
			'student'=>array(
				'student/announcement/{course_id}'
			),
		),
		'course_meta_changed'=>array(
			'student/courses',
		),
		'quiz_updated' =>  array(
			'student' => array(
				'user/quiz/{quiz_id}',
			),
			'instructor' => array(
				
			)
		),
		'assignment_updated' => array(
			'student' => array(
				'user/content/assignmentId/{assignment_id}',
			),
			'instructor' => array(
				
			)
			
		),
		//works for 'wplms_unit_instructor_complete'/uncomplete
		'unit_updated' =>  array(
			'student' => array(
				'item/{unit_id}'
			),
			'instructor' => array(
				
			)
		),
		'news_updated'=>array(
			'student'=>array(
				'student/news/{course_id}'
			),
		),
		'course_curriculum_updated' =>  array(
			'student' => array(
				'student/courseTab/{course_id}?args=curriculum',
				'coursestatus/{course_id}',
			),
			'instructor' => array(
				
			)
		),
		'wplms_course_reset' =>  array(
			'student' => array(
				'coursestatus/{course_id}',
				'student/courses'
			),
			'instructor' => array(
				
			)
		),
		'wplms_course_subscribed' =>  array(
			'student' => array(
				'student/courses'
			),
			'instructor' => array(
				
			)
		),
		//will also work for change status
		'wplms_course_extend_subscription_change_status' =>  array(
			'student' => array(
				'student/courses',
				'coursestatus/{course_id}',
			),
			'instructor' => array(
				
			)
		),
		'wplms_course_removed' =>  array(
			'student' => array(

			),
			'instructor' => array(
				
			)
		),
		'wplms_badge_earned' =>  array(
			'student' => array(
				'student/badges',
			),
			'instructor' => array(
				
			)
		),
		'wplms_certificate_earned'=>  array(
			'student' => array(
				'student/certificates',
			),
			'instructor' => array(
				
			)
		),
		'wplms_course_unit_comment'=> array(
			'student' => array(

			),
			'instructor' => array(
				
			)
		),
		'wplms_submit_quiz' =>  array(
			'student' => array(
				'student/quiz',
			),
			'instructor' => array(
				
			)
		),
		
		'wplms_evaluate_quiz' =>array(
			'student' => array(
				'student/quiz',
				'user/quiz/{quiz_id}',
			),
			'instructor' => array(
				
			)
		),
		'wplms_quiz_assigned' => array(
			'student'=>array(
				'student/quiz'
			),
			'instructor'=>array()
		),
		'wplms_submit_assignment' =>  array(
			'student' => array(
				'student/assignments',
			),
			'instructor' => array(
				
			)
		),
		'wplms_evaluate_assignment' =>array(
			'student' => array(
				'student/assignments',
				'user/content/assignmentId/{assignment_id}',
			),
			'instructor' => array(
				
			)
		),
		'wplms_unit_instructor_uncomplete',
		'wplms_unit_instructor_complete',

	),$args);
	return $map;
}