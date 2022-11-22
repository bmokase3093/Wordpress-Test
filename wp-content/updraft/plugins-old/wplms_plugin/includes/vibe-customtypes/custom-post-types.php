<?php
/**
 * FILE: custom-post-types.php 
 * Created on Feb 18, 2013 at 7:47:20 PM 
 * Author: Mr.Vibe 
 * Credits: www.VibeThemes.com
 * Project: WPLMS
 * License: GPLv2
 */
 
if ( !defined( 'ABSPATH' ) ) exit;

add_action( 'admin_menu', 'register_lms_menu_page' );

function register_lms_menu_page(){
	$settings = lms_settings::init();
    add_menu_page( __('Learning Management System','wplms'), 'LMS', 'edit_posts', 'lms', 'vibe_lms_dashboard','dashicons-welcome-learn-more',7 );
    add_submenu_page( 'lms', __('Statistics','wplms'), __('Statistics','wplms'),  'edit_posts', 'lms-stats', 'vibe_lms_stats' );
    add_submenu_page( 'lms', __('Settings','wplms'), __('Settings','wplms'),  'manage_options', 'lms-settings', array($settings,'vibe_lms_settings'));
    add_submenu_page( 'lms', __('Lms Tree','wplms'), __('Lms Tree','wplms'),  'edit_posts', 'lms-tree', array($settings,'vibe_lms_tree'));
    add_submenu_page( 'lms', __('Live','wplms'), __('Live','wplms'),  'edit_posts', 'lms-settings&tab=live', array($settings,'live'));
    add_submenu_page( 'lms', __('Add Ons','wplms'), __('Add Ons','wplms'),  'edit_posts', 'lms-settings&tab=addons', array($settings,'lms_addons'));
 
	add_action( 'admin_menu', 'vibe_customtypes_add_question_tags', 999 );
}

   
// Adding Course Categories in LMS Menu
function vibe_customtypes_add_question_tags() {
    add_submenu_page(
        'lms',
        __('Question Tags','wplms'),
        __('Question tags','wplms'),
        'edit_posts',
        'edit-tags.php?post_type=question&taxonomy=question-tag'
    );
}


/*== PORTFOLIO == */
if(!function_exists('register_lms')){

	function register_lms() {
		if ( ! defined( 'WPLMS_COURSE_SLUG' ) )
			define( 'WPLMS_COURSE_SLUG', 'course' );

		if ( ! defined( 'BP_COURSE_SLUG' ) )
			define( 'BP_COURSE_SLUG', 'course' );

		if ( ! defined( 'WPLMS_COURSE_CATEGORY_SLUG' ) )
			define( 'WPLMS_COURSE_CATEGORY_SLUG', 'course-cat' );

		if ( ! defined( 'WPLMS_UNIT_SLUG' ) )
			define( 'WPLMS_UNIT_SLUG', 'unit' );

		if ( ! defined( 'WPLMS_QUIZ_SLUG' ) )
			define( 'WPLMS_QUIZ_SLUG', 'quiz' );

		if ( ! defined( 'WPLMS_QUESTION_SLUG' ) )
			define( 'WPLMS_QUESTION_SLUG', 'question' );

		if ( ! defined( 'WPLMS_EVENT_SLUG' ) )
			define( 'WPLMS_EVENT_SLUG', 'event' );

		if ( ! defined( 'WPLMS_ASSIGNMENT_SLUG' ) )
			define( 'WPLMS_ASSIGNMENT_SLUG', 'assignment' );

		if ( ! defined( 'WPLMS_TESTIMONIAL_SLUG' ) )
			define( 'WPLMS_TESTIMONIAL_SLUG', _x('testimonial','Testimonial slug in permalink','wplms'));

		$permalinks = get_option( 'vibe_course_permalinks' );
		$course_permalink = empty( $permalinks['course_base'] ) ? WPLMS_COURSE_SLUG : $permalinks['course_base'];
		$quiz_permalink = empty( $permalinks['quiz_base'] ) ? WPLMS_QUIZ_SLUG : $permalinks['quiz_base'];
		$unit_permalink = empty( $permalinks['unit_base'] ) ? WPLMS_UNIT_SLUG : $permalinks['unit_base'];
		$show_news = '';
		$news_permalink = '';
		if(function_exists('vibe_get_option')){
			$show_news = vibe_get_option('show_news');
			if(!empty($show_news)){
				if( !defined('WPLMS_NEWS_SLUG')){
			      define('WPLMS_NEWS_SLUG',_x('news','News slug in permalink','wplms'));
			    }
				$news_permalink = empty( $permalinks['news_base'] ) ? WPLMS_NEWS_SLUG : $permalinks['news_base'];
			}
			
		}

		$bp_pages = get_option('bp-pages');
		if(isset($bp_pages) && is_array($bp_pages) && isset($bp_pages['course'])){
			 $projects_page_id = $bp_pages['course'];
			if(get_post_type( $projects_page_id ) == 'page'){
				$uri = get_page_uri( $projects_page_id );
			}
		}

		register_taxonomy( 'course-cat', array( 'course'),
			array(
				'labels' => array(
					'name' => __('Course Category','wplms'),
					'menu_name' => __('Category','wplms'),
					'singular_name' => __('Category','wplms'),
					'add_new_item' => __('Add New Course Category','wplms'),
					'all_items' => __('All Categories','wplms')
				),
				'public' => true,
				'hierarchical' => true,
				'show_ui' => true,
				'show_in_menu' => 'lms',
				'show_admin_column' => true,
		        'query_var' => 'course-cat',           
				'show_in_nav_menus' => true,
				'rewrite' => array( 
					'slug' => empty( $permalinks['course_category_base'] ) ? WPLMS_COURSE_CATEGORY_SLUG : $permalinks['course_category_base'],
					'hierarchical' => true, 
					'with_front' => false ),
			)
		);

		register_post_type( 'course',
			array(
				'labels' => array(
					'name' => __('Courses','wplms'),
					'menu_name' => __('Courses','wplms'),
					'singular_name' => __('Course','wplms'),
					'add_new_item' => __('Add New Course','wplms'),
					'all_items' => __('All Courses','wplms')
				),
				'public' => true,
				'publicly_queryable' => true,
				'show_ui' => true,
				'capability_type' => 'post',
	            'has_archive' => empty($uri)?true:$uri,
				'show_in_menu' => 'lms',
				'show_in_admin_bar' => true,
				'show_in_nav_menus' => true,
				'taxonomies' => array( 'course-cat'),
				'supports' => array( 'title','editor','thumbnail','author','comments','excerpt','revisions','custom-fields', 'page-attributes'),
				'hierarchical' => true,
				'rewrite' => array( 'slug' => $course_permalink, 'hierarchical' => true, 'with_front' => false )
			)
		);

	    register_post_type( 'unit',
			array(
				'labels' => array(
					'name' => __('Units','wplms'),
					'menu_name' => __('Units','wplms'),
					'singular_name' => __('Unit','wplms'),
					'add_new_item' => __('Add New Unit','wplms'),
					'all_items' => __('All Units','wplms')
				),
				'public' => true,
				'publicly_queryable' => true,
				'show_ui' => true,
	            'has_archive' => false,
				'show_in_menu' => 'lms',
				'show_in_admin_bar' => true,
				'exclude_from_search' => true, 
				'show_in_nav_menus' => true,
				'supports' => array( 'title', 'editor', 'thumbnail','author','comments', 'post-formats', 'revisions','custom-fields' ),
				'hierarchical' => true,
				'rewrite' => array( 'slug' => $unit_permalink, 'hierarchical' => true, 'with_front' => false )
			)
		 );

	     register_taxonomy( 'module-tag', array( 'unit'),
			array(
				'labels' => array(
					'name' => __('Tag','wplms'),
					'menu_name' => __('Tag','wplms'),
					'singular_name' => __('Tag','wplms'),
					'add_new_item' => __('Add New Tag','wplms'),
					'all_items' => __('All Tags','wplms')
				),
				'public' => true,
				'hierarchical' => false,
				'show_in_menu' => 'lms',
				'show_ui' => true,
				'show_admin_column' => true,
				'show_in_nav_menus' => true,
				'rewrite' => array( 'slug' => 'module-tag', 'hierarchical' => true, 'with_front' => true ),
			)
		);

		 register_post_type( 'quiz',
			array(
				'labels' => array(
					'name' => __('Quizzes','wplms'),
					'menu_name' => __('Quizzes','wplms'),
					'singular_name' => __('Quiz','wplms'),
					'all_items' => __('All Quizzes','wplms')
				),
				'public' => true,
				'publicly_queryable' => true,
				'show_ui' => true,
	            'has_archive' => false,
				'show_in_menu' => 'lms',
				'exclude_from_search' => true, 
				'show_in_admin_bar' => true,
				'show_in_nav_menus' => true,
				'supports' => array( 'title','author','thumbnail','editor', 'revisions','custom-fields' ),
				'hierarchical' => true,
				'rewrite' => array( 'slug' => $quiz_permalink,'hierarchical' => true, 'with_front' => false )
			)
		 );  

		 register_taxonomy( 'quiz-type', array( 'quiz'),
				array(
					'labels' => array(
						'name' => __('Quiz type','wplms'),
						'menu_name' => __('Quiz type','wplms'),
						'singular_name' => __('Quiz type','wplms'),
						'add_new_item' => __('Add New Quiz type','wplms'),
						'all_items' => __('All Quiz types','wplms')
					),
					'public' => true,
					'hierarchical' => true,
					'show_ui' => true,
					'show_in_menu' => 'lms',
					'show_admin_column' => true,
					'show_in_admin_bar' => true,
					'show_in_nav_menus' => true,
					'rewrite' => array( 'slug' => 'quiz-type', 'hierarchical' => true, 'with_front' => false ),
				)
			);
	    	 
		 register_post_type( 'question',
			array(
				'labels' => array(
					'name' => __('Question Bank','wplms'),
					'menu_name' => __('Question Bank','wplms'),
					'singular_name' => __('Question','wplms'),
					'all_items' => __('All Questions','wplms')
				),
				'public' => true,
				'publicly_queryable' => true,
				'show_ui' => true,
	            'has_archive' => false,
				'show_in_menu' => 'lms',
				'exclude_from_search' => true, 
				'show_in_admin_bar' => true,
				'show_in_nav_menus' => true,
				'supports' => array( 'title','author','editor', 'comments','revisions' ,'custom-fields'),
				'hierarchical' => true,
				'rewrite' => array( 'slug' => WPLMS_QUESTION_SLUG,'hierarchical' => true, 'with_front' => false )
			)
		 ); 

		 register_taxonomy( 'question-tag', array( 'question'),
			array(
				'labels' => array(
					'name' => __('Question Tag','wplms'),
					'menu_name' => __('Tag','wplms'),
					'singular_name' => __('Tag','wplms'),
					'add_new_item' => __('Add New Tag','wplms'),
					'all_items' => __('All Tags','wplms')
				),
				'public' => true,
				'hierarchical' => false,
				'show_ui' => true,
				'show_admin_column' => 'true',
				'show_in_nav_menus' => true,
				'rewrite' => array( 'slug' => 'question-tag', 'hierarchical' => false, 'with_front' => false ),
			)
		); 

		add_post_type_support('question','comments');

		/*====== Version 1.4 EVENTS =====*/

		if ( in_array( 'wplms-events/wplms-events.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {     

		register_post_type( 'wplms-event',
				array(
					'labels' => array(
						'name' => __('Events','wplms'),
						'menu_name' => __('Events','wplms'),
						'singular_name' => __('Event','wplms'),
						'add_new_item' => __('Add New Events','wplms'),
						'all_items' => __('All Events','wplms')
					),
					'public' => true,
					'taxonomies' => array( 'event-type'),
					'publicly_queryable' => true,
					'show_ui' => true,
		            'has_archive' => true,
					'show_in_menu' => 'lms',
					'show_in_admin_bar' => true,
					'show_in_nav_menus' => true,
					'supports' => array( 'title', 'editor', 'thumbnail','author', 'post-formats', 'revisions','custom-fields' ),
					'hierarchical' => true,
					'rewrite' => array( 'slug' => WPLMS_EVENT_SLUG, 'hierarchical' => true, 'with_front' => true )
				)
			 );

		 register_taxonomy( 'event-type', array( 'wplms-event'),
				array(
					'labels' => array(
						'name' => __('Event type','wplms'),
						'menu_name' => __('Event type','wplms'),
						'singular_name' => __('Event type','wplms'),
						'add_new_item' => __('Add New Event type','wplms'),
						'all_items' => __('All Event types','wplms')
					),
					'public' => true,
					'hierarchical' => true,
					'show_ui' => true,
					'show_admin_column' => true,
					'show_in_admin_bar' => true,
					'show_in_nav_menus' => true,
					'rewrite' => array( 'slug' => 'event-type', 'hierarchical' => true, 'with_front' => false ),
				)
			);
		 add_post_type_support('wplms-event','comments');
		}

		/*====== Version 1.5 ASSIGNMENTS =====*/

		//if ( in_array( 'wplms-assignments/wplms-assignments.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {     

		register_post_type( 'wplms-assignment',
				array(
					'labels' => array(
						'name' => __('Assignments','wplms'),
						'menu_name' => __('Assignments','wplms'),
						'singular_name' => __('Assignment','wplms'),
						'add_new_item' => __('Add New Assignment','wplms'),
						'all_items' => __('All Assignments','wplms')
					),
					'public' => true,
					'taxonomies' => array( 'assignment-type'),
					'publicly_queryable' => true,
					'show_ui' => true,
		            'has_archive' => true,
					'show_in_menu' => 'lms',
					'exclude_from_search' => true, 
					'show_in_admin_bar' => true,
					'show_in_nav_menus' => true,
					'supports' => array( 'title', 'editor','author', 'post-formats', 'revisions','custom-fields' ),
					'hierarchical' => true,
					'rewrite' => array( 'slug' => WPLMS_ASSIGNMENT_SLUG, 'hierarchical' => true, 'with_front' => true )
				)
			 );

		 register_taxonomy( 'assignment-type', array( 'wplms-assignment'),
				array(
					'labels' => array(
						'name' => __('Assignment type','wplms'),
						'menu_name' => __('Assignment type','wplms'),
						'singular_name' => __('Assignment type','wplms'),
						'add_new_item' => __('Add New Assignment type','wplms'),
						'all_items' => __('All Assignment types','wplms')
					),
					'public' => true,
					'hierarchical' => true,
					'show_ui' => true,
					'show_admin_column' => true,
					'show_in_admin_bar' => true,
					'show_in_nav_menus' => true,
					'rewrite' => array( 'slug' => 'Assignment-type', 'hierarchical' => true, 'with_front' => false ),
				)
			);
		 add_post_type_support('wplms-assignment','comments');
		//}

		//news 
		if(!empty($show_news)){
		    register_post_type( 'news',
			    array(
			      'labels' => array(
			        'name' => __('Course News','wplms'),
			        'menu_name' => __('Course News','wplms'),
			        'singular_name' => __('News','wplms'),
			        'add_new_item' => __('Add News','wplms'),
			        'all_items' => __('Course News','wplms')
			      ),
			      'public' => true,
			      'publicly_queryable' => true,
			      'show_ui' => true,
			      'capapbility_type' => 'post',
			      'has_archive' => true,
			      'show_in_admin_bar' => true,
			      'show_in_menu'=>'lms',
			      'show_in_nav_menus' => true,
			      'taxonomies' => array( 'news-tag'),
			      'supports' => array( 'title','editor','thumbnail','author','post-formats','comments','excerpt','revisions','custom-fields'),
			      'hierarchical' => true,
			      'rewrite' => array( 'slug' => $news_permalink, 'hierarchical' => true, 'with_front' => false )
			    )
			 );

			register_taxonomy( 'news-tag', array( 'news'),
			  array(
			    'labels' => array(
			      'name' => __('News Tag','wplms'),
			      'menu_name' => __('News Tag','wplms'),
			      'singular_name' => __('News Tag','wplms'),
			      'add_new_item' => __('Add New Tag','wplms'),
			      'all_items' => __('All News Tags','wplms')
			    ),
			    'public' => true,
			    'hierarchical' => false,
			    'show_ui' => true,
			    'show_admin_column' => 'true',
			    'show_in_nav_menus' => true,
			    'rewrite' =>  array( 'slug' => 'news-tag', 'hierarchical' => true, 'with_front' => false ),
			  )
			);
		}

	/*====== Version 1.3 RECORD PAYMENTS =====*/
		register_post_type( 'payments',
			array(
				'labels' => array(
					'name' => __('Payments','wplms'),
					'menu_name' => __('Payments','wplms'),
					'singular_name' => __('Payment','wplms'),
					'add_new_item' => __('Add New Payment','wplms'),
					'all_items' => __('Payouts','wplms')
				),
				'publicly_queryable' => true,
				'show_ui' => true,
				'exclude_from_search' => true,
	            'has_archive' => false,
	            'query_var'   => false,
				'show_in_menu' => (current_user_can('manage_options')?'lms':false),
				'show_in_nav_menus' => false,
				'supports' => array( 'title'),
				'hierarchical' => false,
				'rewrite' => array( 'slug' => 'payments', 'hierarchical' => false, 'with_front' => false )
			)
		 );   
	   
		/*====== Version 1.3.2 CERTIFICATE TEMPLATES =====*/
		register_post_type( 'certificate',
			array(
				'labels' => array(
					'name' => __('Certificate Template','wplms'),
					'menu_name' => __('Certificates Template','wplms'),
					'singular_name' => __('Certificate Template','wplms'),
					'add_new_item' => __('Add New Certificate','wplms'),
					'all_items' => __('Certificate Templates','wplms')
				),
				'public' => true,
				'publicly_queryable' => true,
				'show_ui' => true,
	            'has_archive' => false,
				'show_in_menu' => 'lms',
				'exclude_from_search' => true,
				'show_in_nav_menus' => false,
				'supports' => array( 'title','editor'),
				'hierarchical' => false,
				'rewrite' => array( 'slug' => 'certificates', 'hierarchical' => false, 'with_front' => false )
			)
		 );
		/*====== Version 1.6.2 LINKAGE =====*/
		$linkage = vibe_get_option('linkage');
		if(isset($linkage) && $linkage){
			register_taxonomy( 'linkage', array( 'course','unit','quiz','question','certificate','wplms-assignment','wplms-event','forum','product'),
				array(
					'labels' => array(
						'name' => __('Linkage','wplms'),
						'menu_name' => __('Linkage','wplms'),
						'singular_name' => __('Linkage','wplms'),
						'add_new_item' => __('Add New Link','wplms'),
						'all_items' => __('All Links','wplms')
					),
					'public' => true,
					'hierarchical' => false,
					'show_ui' => true,
					'show_in_menu' => 'lms',
					'show_admin_column' => true,
					'show_in_nav_menus' => true,
					'rewrite' => array( 'slug' => 'linkage', 'hierarchical' => true, 'with_front' => false ),
				)
			);
		}
		/*====== Version 1.6.5 LEVEL =====*/	
		$level = vibe_get_option('level');
		if(isset($level) && $level){
			register_taxonomy( 'level', array( 'course'),
				array(
					'labels' => array(
						'name' => __('Level','wplms'),
						'menu_name' => __('Level','wplms'),
						'singular_name' => __('Level','wplms'),
						'add_new_item' => __('Add New Level','wplms'),
						'all_items' => __('All Levels','wplms')
					),
					'public' => true,
					'hierarchical' => true,
					'show_ui' => true,
					'show_in_menu' => 'lms',
					'show_admin_column' => true,
		            'query_var' => 'level',           
					'show_in_nav_menus' => true,
					'rewrite' => array( 'slug' => WPLMS_LEVEL_SLUG, 'hierarchical' => true, 'with_front' => false ),
				)
			);
		}	
		/*====== Version 1.6.5 LEVEL =====*/	
		$location = vibe_get_option('location');
		if(isset($location) && $location){
			register_taxonomy( 'location', array( 'course'),
				array(
					'labels' => array(
						'name' => __('Location','wplms'),
						'menu_name' => __('Location','wplms'),
						'singular_name' => __('Location','wplms'),
						'add_new_item' => __('Add New Location','wplms'),
						'all_items' => __('All Locations','wplms')
					),
					'public' => true,
					'hierarchical' => true,
					'show_ui' => true,
					'show_in_menu' => 'lms',
					'show_admin_column' => true,
		            'query_var' => 'location',           
					'show_in_nav_menus' => true,
					'rewrite' => array( 'slug' => WPLMS_LOCATION_SLUG, 'hierarchical' => true, 'with_front' => false ),
				)
			);
		}
	}// End REgister LMS
}

/*== Testimonials == */
if(!function_exists('register_testimonials')){
	function register_testimonials() {
		register_post_type( 'testimonials',
			array(
				'labels' => array(
					'name' => __('Testimonials','wplms'),
					'menu_name' => __('Testimonials','wplms'),
					'singular_name' => __('Testimonial','wplms'),
					'all_items' => __('All Testimonials','wplms')
				),
				'public' => true,
				'publicly_queryable' => true,
				'show_ui' => true,
				'show_in_menu' => true,
				'show_in_nav_menus' => true,
				'supports' => array( 'title', 'editor','excerpt', 'thumbnail'),
				'hierarchical' => false,
				'has_archive' => true,
	            'menu_position' => 10,
				'rewrite' => array( 'slug' => WPLMS_TESTIMONIAL_SLUG, 'hierarchical' => true, 'with_front' => false )
			)
		);
	}
}
/*== Popups == */
if(!function_exists('register_popups')){
	function register_popups(){
		register_post_type( 'popups',
			array(
				'labels' => array(
					'name' => __('Popups','wplms'),
					'menu_name' => __('Popups','wplms'),
					'singular_name' => __('Popup','wplms'),
					'all_items' => __('All Popups','wplms')
				),
				'public' => true,
				'publicly_queryable' => true,
				'show_ui' => true,
				'show_in_menu' => true,
				'show_in_nav_menus' => false,
				'supports' => array( 'title', 'editor','excerpt' ),
				'hierarchical' => false,
				'has_archive' => false,
	            'menu_position' => 8,
				'rewrite' => array( 'slug' => 'popup', 'hierarchical' => true, 'with_front' => false )
			)
		);
	}
}

add_action( 'init', 'register_lms',5 );
add_action( 'init', 'register_testimonials' );
add_action( 'init', 'register_popups' );

if(!function_exists('vibe_get_option')){ // Defining GET OPTION function
	function vibe_get_option($field,$compare = NULL){
		if(defined('THEME_SHORT_NAME')){
			$option=get_option(THEME_SHORT_NAME);
		}else{
			$option=get_option('wplms');
		}
	    
	    $return = isset($option[$field])?$option[$field]:NULL;
	    if(isset($return)){
	        if(isset($compare)){
	        if($compare === $return){
	            return true;
	        }else
	            return false;
	    }
	        return apply_filters('vibe_get_option',$return,$field,$compare);
	    }else
	    	return NULL;
   }   
}



add_filter( 'post_row_actions', 'remove_payments_row_actions', 10, 1 );
function remove_payments_row_actions( $actions ){

    if( get_post_type() === 'payments' ){
    	unset( $actions['view'] );
        unset( $actions['inline hide-if-no-js'] );
    }

    return $actions;
}
