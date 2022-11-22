<?php

if ( !defined( 'ABSPATH' ) ) exit;


class BP_Course_Component extends BP_Component {

	
	function __construct() {
		global $bp;
		parent::start(
			BP_COURSE_SLUG,
			__( 'Course', 'wplms' ),
			BP_COURSE_MOD_PLUGIN_DIR
		);

		if ( ! defined( 'BP_COURSE_RESULTS_SLUG' ) )
			define( 'BP_COURSE_RESULTS_SLUG', 'course-results' );

		if ( ! defined( 'BP_COURSE_STATS_SLUG ' ) )
			define( 'BP_COURSE_STATS_SLUG', 'course-stats' );

		
		 $this->includes();

		
		$bp->active_components[$this->id] = '1';

		
		add_action( 'init', array( &$this, 'register_post_types' ) );
	}

	function includes($includes = array()) {

		// Files to include
		$includes = array(
			'includes/bp-course-actions.php', 
				'includes/bp-course-screens.php',
			'includes/bp-course-filters.php',
			'includes/bp-course-classes.php',
			'includes/bp-course-activity.php',
			'includes/bp-course-template.php',
			'includes/bp-course-functions.php',
				'includes/bp-course-tour.php',
			'includes/bp-course-notifications.php',
				'includes/bp-course-widgets.php',
				'includes/bp-course-cssjs.php',
				'includes/bp-course-ajax.php',
			'includes/bp-course-offline.php',
			'includes/bp-course-mailer.php',
			'includes/bp-course-scheduler.php',
			'includes/bp-course-api.php',
			'includes/bp-course-api-legacy.php',
			'includes/api/class-api-tracker-controller.php',
			'includes/bp-course-commissions.php',
			'includes/bp-course-quiz.php',
		);
		if(function_exists('vibe_get_option')){
			$includes[] = 'includes/tincan/tincan.php';
		}

		parent::includes( $includes );

		// As an course of how you might do it manually, let's include the functions used
		// on the WordPress Dashboard conditionally:
		if ( is_admin() || is_network_admin() ) {
			include( BP_COURSE_MOD_PLUGIN_DIR . '/includes/bp-course-admin.php' );
		}
	}

	
	public function setup_globals($args=array()) {
		global $bp;

		// Defining the slug in this way makes it possible for site admins to override it
		if ( !defined( 'BP_COURSE_SLUG' ) )
			define( 'BP_COURSE_SLUG', $this->id );

		if ( !defined( 'BP_COURSE_INSTRUCTOR_SLUG' ) )
			define( 'BP_COURSE_INSTRUCTOR_SLUG', 'instructor-courses');
		
		
		$globals = array(
			'slug'                  => BP_COURSE_SLUG,
			'root_slug'             => isset( $bp->pages->{$this->id}->slug ) ? $bp->pages->{$this->id}->slug : BP_COURSE_SLUG,
			'has_directory'         => true, // Set to false if not required
			'directory_title'       => _x( 'Course Directory', 'Course directory title', 'wplms' ),
			'notification_callback' => 'bp_course_format_notifications',
			'search_string'         => __( 'Search courses ...', 'wplms' ),
			//'global_tables'         => $global_tables
		);
		parent::setup_globals( $globals );

	}

	function old_setup_nav($main_nav = array(), $sub_nav = array()) {

		$show_for_displayed_user=apply_filters('wplms_user_profile_courses',false);
		$main_nav = array(
			'name'            => sprintf( __( 'Courses <span>%s</span>', 'wplms' ), bp_course_get_total_course_count_for_user() ),
			'slug' 		      => BP_COURSE_SLUG,
			'position' 	      => 5,
			'screen_function'     => 'bp_course_my_courses',
			'show_for_displayed_user' => $show_for_displayed_user, //Change for admin
			'default_subnav_slug' => BP_COURSE_SLUG,
		);

		// Add 'course' to the main navigation
		if(function_exists('vibe_get_option')){
			$course_view = vibe_get_option('course_view');
			if(isset($course_view) && $course_view){
				$main_nav['show_for_displayed_user']=$show_for_displayed_user; //Change for admin
			}
		}

		$course_link = trailingslashit( bp_loggedin_user_domain() . BP_COURSE_SLUG );

		// Determine user to use
		if ( bp_displayed_user_domain() ) {
			$user_domain = bp_displayed_user_domain();
		} elseif ( bp_loggedin_user_domain() ) {
			$user_domain = bp_loggedin_user_domain();
		} else {
			$user_domain = false;
		}

		if ( !empty( $user_domain ) ) {
			$user_access = bp_is_my_profile();
			$user_access = apply_filters('wplms_user_profile_courses',$user_access);
			$sub_nav[] = array(
				'name'            =>  __('My Courses','old setup', 'wplms' ),
				'slug'            => BP_COURSE_SLUG,
				'parent_url'      => $course_link,
				'parent_slug'     => BP_COURSE_SLUG,
				'screen_function' => 'bp_course_my_courses',
				'user_has_access' => $user_access,
				'position'        => 10
			);
			
			bp_core_new_subnav_item( array(
				'name' 		  => __( 'Results', 'wplms' ),
				'slug' 		  => BP_COURSE_RESULTS_SLUG,
				'parent_slug'     => BP_COURSE_SLUG,
				'parent_url'      => $course_link,
				'screen_function' => 'bp_course_my_results',
				'position' 	  => 30,
				'user_has_access' => $user_access // Only the logged in user can access this on his/her profile
			) );

			bp_core_new_subnav_item( array(
				'name' 		  => __( 'Stats', 'wplms' ),
				'slug' 		  => BP_COURSE_STATS_SLUG,
				'parent_slug'     => BP_COURSE_SLUG,
				'parent_url'      => $course_link,
				'screen_function' => 'bp_course_stats',
				'position' 	  => 40,
				'user_has_access' => $user_access // Only the logged in user can access this on his/her profile
			) );

			$sub_nav[] = array(
				'name'            =>  sprintf( __( 'Instructing Courses <span>%s</span>', 'wplms' ), bp_course_get_instructor_course_count_for_user( bp_loggedin_user_id() ) ),
				'slug'            => BP_COURSE_INSTRUCTOR_SLUG,
				'parent_slug'     => BP_COURSE_SLUG,
				'parent_url'      => $course_link,
				'screen_function' => 'bp_course_instructor_courses',
				'position'        => 50,
				'user_has_access' => bp_is_my_profile_intructor(),
			);
			
			parent::setup_nav( $main_nav, $sub_nav );
		}

		// If your component needs additional navigation menus that are not handled by
	
		if ( bp_is_course_component() && bp_is_single_item() ) {

			// Reset sub nav
			$sub_nav = array();

			// Add 'courses' to the main navigation
			$main_nav = array(
				'name'                => __( 'Home', 'wplms' ),
				'slug'                => get_current_course_slug(),
				'position'            => -1, // Do not show in BuddyBar
				'screen_function'     => 'bp_screen_course_home',
				'default_subnav_slug' => $this->default_extension,
				'item_css_id'         => $this->id
			);

			parent::setup_nav( $main_nav, $sub_nav );
		}

		if ( isset( $this->current_course->user_has_access ) ) {
			do_action( 'courses_setup_nav', $this->current_course->user_has_access );
		} else {
			do_action( 'courses_setup_nav');
		}
		
	}//End setup nav

	function setup_nav($main_nav = array(), $sub_nav = array()) {

		if( defined('WPLMS_VERSION') && empty($_GET['reload_nav'])){
			$this->old_setup_nav();
			return;
		}
		
		
		$show_for_displayed_user=apply_filters('wplms_user_profile_courses',false);
		$main_nav = array(
			'name'            => __( 'Courses', 'wplms' ),
			'slug' 		      => BP_COURSE_SLUG,
			'position' 	      => 5,
			'screen_function'     => 'bp_course_my_courses',
			'show_for_displayed_user' => $show_for_displayed_user, //Change for admin
			'default_subnav_slug' => BP_COURSE_SLUG,
		);

		// Add 'course' to the main navigation
		if(function_exists('vibe_get_option')){
			$course_view = vibe_get_option('course_view');
			if(isset($course_view) && $course_view){
				$main_nav['show_for_displayed_user']=$show_for_displayed_user; //Change for admin
			}
		}

		$course_link = trailingslashit( bp_loggedin_user_domain() . BP_COURSE_SLUG );

		// Determine user to use
		if ( bp_displayed_user_domain() ) {
			$user_domain = bp_displayed_user_domain();
		} elseif ( bp_loggedin_user_domain() ) {
			$user_domain = bp_loggedin_user_domain();
		} else {
			$user_domain = false;
		}

		if ( !empty( $user_domain ) ) {
			$user_access = 1;
			$user_access = apply_filters('wplms_user_profile_courses',$user_access);
			$sub_nav[] = array(
				'name'            =>  _x('Enrolled Courses','buddypress navigation', 'wplms' ),
				'slug'            => BP_COURSE_SLUG,
				'parent_url'      => $course_link,
				'parent_slug'     => BP_COURSE_SLUG,
				'screen_function' => 'bp_course_my_courses',
				'user_has_access' => 'read',
				'position'        => 10
			);

			$sub_nav[] = array(
				'name' 		  => __( 'Achievements', 'wplms' ),
				'slug' 		  	=> BP_COURSE_STATS_SLUG,
				'parent_slug'     => BP_COURSE_SLUG,
				'parent_url'      => $course_link,
				'screen_function' => 'bp_course_stats',
				'position' 	  => 20,
				'user_has_access' => 'read' // Only the logged in user can access this on his/her profile
			) ;



			$sub_nav[] = array(
				'name' 		  		=> __( 'My Quizzes', 'wplms' ),
				'slug' 		  		=> 'quiz_results',
				'parent_slug'     => BP_COURSE_SLUG,
				'parent_url'      => $course_link,
				'screen_function' => 'bp_course_my_results',
				'position' 	  	=> 30,
				'user_has_access' => 'read' // Only the logged in user can access this on his/her profile
			) ;

			$sub_nav[] = array(
				'name' 		      => __( 'My Assignments', 'wplms' ),
				'slug' 		  	  => 'assignment_results',
				'parent_slug'     => BP_COURSE_SLUG,
				'parent_url'      => $course_link,
				'screen_function' => 'bp_course_my_results',
				'position' 	  		=> 40,
				'user_has_access' => 'read' // Only the logged in user can access this on his/her profile
			) ;
			
			$sub_nav[] = array(
				'name' 		      => __( 'Notes & Reviews', 'wplms' ),
				'slug' 		  	  => 'notes_reviews',
				'parent_slug'     => BP_COURSE_SLUG,
				'parent_url'      => $course_link,
				'screen_function' => 'bp_course_my_results',
				'position' 	  	  => 40,
				'user_has_access' => 'read' // Only the logged in user can access this on his/her profile
			) ;
			
			$tips = WPLMS_tips::init();
			if((!empty($tips->settings['gamification'])?true:null)){ //localize issue
				$sub_nav[] = array(
					'name' 		      => __('Points & Badges', 'wplms' ),
					'slug' 		  	  => 'points_badges',
					'parent_slug'     => BP_COURSE_SLUG,
					'parent_url'      => $course_link,
					'screen_function' => 'bp_course_my_results',
					'position' 	  	  => 50,
					'user_has_access' => 'read' // Only the logged in user can access this on his/her profile
				);
			}
			

			$sub_nav[] = array(
				'name'            =>  __( 'Instructor Controls', 'wplms' ), 
				'slug'            => 'instructor_controls',
				'parent_slug'     => BP_COURSE_SLUG,
				'parent_url'      => $course_link,
				'screen_function' => 'bp_course_instructor_courses',
				'position'        => 150,
				'user_has_access' => 'edit_posts',
			);

			$sub_nav[] = array(
				'name'            =>  __( 'Manage Courses', 'wplms' ), 
				'slug'            => 'manage_courses',
				'parent_slug'     => BP_COURSE_SLUG,
				'parent_url'      => $course_link,
				'screen_function' => 'bp_course_instructor_courses',
				'position'        => 160,
				'user_has_access' => 'edit_posts',
			);

			$sub_nav[] = array(
				'name'            =>  __( 'Manage Quizzes', 'wplms' ), 
				'slug'            => 'manage_quizzes',
				'parent_slug'     => BP_COURSE_SLUG,
				'parent_url'      => $course_link,
				'screen_function' => 'bp_course_instructor_courses',
				'position'        => 170,
				'user_has_access' => 'edit_posts',
			);

			$sub_nav[] = array(
				'name'            =>  __( 'Manage Assignments', 'wplms' ), 
				'slug'            => 'manage_assignments',
				'parent_slug'     => BP_COURSE_SLUG,
				'parent_url'      => $course_link,
				'screen_function' => 'bp_course_instructor_courses',
				'position'        => 180,
				'user_has_access' => 'edit_posts',
			);


			$sub_nav[] = array(
				'name'            =>  __( 'Manage Students', 'wplms' ), 
				'slug'            => 'manage_students',
				'parent_slug'     => BP_COURSE_SLUG,
				'parent_url'      => $course_link,
				'screen_function' => 'bp_course_instructor_courses',
				'position'        => 190,
				'user_has_access' => 'edit_posts',
			);

			$sub_nav[] = array(
				'name'            =>  __( 'Manage Questions', 'wplms' ), 
				'slug'            => 'manage_questions',
				'parent_slug'     => BP_COURSE_SLUG,
				'parent_url'      => $course_link,
				'screen_function' => 'bp_course_instructor_courses',
				'position'        => 200,
				'user_has_access' => 'edit_posts',
			);

			$sub_nav[] = array(
				'name'            =>  __( 'Question & Discussions', 'wplms' ), 
				'slug'            => 'qna',
				'parent_slug'     => BP_COURSE_SLUG,
				'parent_url'      => $course_link,
				'screen_function' => 'bp_course_instructor_courses',
				'position'        => 210,
				'user_has_access' => 'edit_posts',
			);

			$sub_nav[] = array(
				'name'            =>  __( 'Manage Reports', 'wplms' ).'<span>Beta</span>', 
				'slug'            => 'manage_reports',
				'parent_slug'     => BP_COURSE_SLUG,
				'parent_url'      => $course_link,
				'screen_function' => 'bp_course_instructor_courses',
				'position'        => 250,
				'user_has_access' => bp_is_my_profile_intructor(),
			);
			
			$sub_nav = apply_filters('wplms_course_sub_nav',$sub_nav);
			parent::setup_nav( $main_nav, $sub_nav );
		}

		// If your component needs additional navigation menus that are not handled by
	
		if ( bp_is_course_component() && bp_is_single_item() ) {

			// Reset sub nav
			$sub_nav = array();

			// Add 'courses' to the main navigation
			$main_nav = array(
				'name'                => __( 'Home', 'wplms' ),
				'slug'                => get_current_course_slug(),
				'position'            => -1, // Do not show in BuddyBar
				'screen_function'     => 'bp_screen_course_home',
				'default_subnav_slug' => $this->default_extension,
				'item_css_id'         => $this->id
			);

			parent::setup_nav( $main_nav, $sub_nav );
		}

		if ( isset( $this->current_course->user_has_access ) ) {
			do_action( 'courses_setup_nav', $this->current_course->user_has_access );
		} else {
			do_action( 'courses_setup_nav');
		}
		
	}
}

function bp_course_load_core_component() {
	global $bp;	
	$bp->course = new BP_Course_Component;
}
add_action( 'bp_loaded', 'bp_course_load_core_component' );
