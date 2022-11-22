<?php
/**
 * Initfor WPLMS 4
 *
 * @author      VibeThemes
 * @category    Admin
 * @package     WPLMS Plugin
 * @version     4.0
 */

 if ( ! defined( 'ABSPATH' ) ) exit;

class WPLMS_4_Init{

    public static $instance;
    public static function init(){
    if ( is_null( self::$instance ) )
        self::$instance = new WPLMS_4_Init();

        return self::$instance;
    }

    private function __construct(){
    	$this->is_wplms_4_0 = array();
    	add_action('init',array($this,'activate'));

    	//User Roles
        add_action('init',array($this,'vibe_user_roles'));
        add_action( 'admin_init',array($this,'add_theme_caps'));


    	add_post_type_support('course-layout','elementor');
		add_post_type_support('course-card','elementor');

		add_action('admin_notices',array($this,'migrate_4_0'));
		add_action('wp_enqueue_scripts',array($this,'vibe_editor_trigger'));
    }



    /*============================================*/
    /*===========  REMGISTER CUSTOM USER ROLES  ============*/
    /*============================================*/
    function vibe_user_roles(){

    	if(!wp_roles()->is_role( 'student' )){


	      $teacher_capability=array(
	          'delete_posts'=> true,
	          'delete_published_posts'=> true,
	          'edit_posts'=> true,
	          'manage_categories' => true,
	          'edit_published_posts'=> true,
	          'publish_posts'=> true,
	          'read' => true,
	          'upload_files'=> true,
	          'unfiltered_html'=> true,
	          'level_1' => true
	          );
	      $student_capability=array(
	          'read'
	          );
	      
	          add_role( 'student', _x('Student','user role','wplms'), $student_capability );
	          add_role( 'instructor', _x('Instructor','user role','wplms'),$teacher_capability);      
      	}
    }
    function add_theme_caps() {
        // gets the author role
        if(!wp_roles()->is_role( 'instructor' )){
        	$role = get_role( 'instructor' );
        	if(!empty($role) && !is_wp_error($role))
        	$role->add_cap( 'unfiltered_html' ); 
    	}
    }
    

    function vibe_editor_trigger(){
    	wp_add_inline_script( 'vibe-editor-js', 'document.dispatchEvent(new Event("VibeBP_Editor_Content"));',array(),WPLMS_PLUGIN_VERSION,true);
    }

    function migrate_4_0(){

    	if(function_exists ('vibe_customtypes_translations') ){
    		return;
    	}
    	if(is_wplms_4_0() > 1){
    		return;
    	}
    			
    	if(!is_wplms_4_0() && (function_exists('vibe_get_option') && !vibe_get_option('take_course_page'))){
    		update_option('wplms_4_0',1);
    		return;
    	}

    	if(defined('WPLMS_VERSION') && version_compare(WPLMS_VERSION,'4.0' ) >=0 ){
    		
    		
    		if(!empty($_POST['security']) && wp_verify_nonce($_POST['security'],'security')){
    			if(isset($_POST['wplms_migrate_4_0'])){
	    			update_option('wplms_4_0',true);
	    			//deactivate plugins
	    			deactivate_plugins(array(
	    				'vibe-course-module/loader.php',
	    				'vibe-shortcodes/vibe-shortcodes.php',
	    				'wplms-dashboard/wplms-dashboard.php',
	    				'wplms-front-end/wplms-front-end.php',
	    				'vibe-customtypes/vibe-customtypes.php',
	    			),true,true);

	    			deactivate_plugins(array('wplms-assignments/wplms-assignments.php'),true,true);

	    			if(!defined('WPLMS_EVENTON_V4')){
	    				deactivate_plugins(array('wplms-eventon/wplms-eventon.php'));
	    			}
	    			//redirect to install plugins section
	    		}
	    		if(isset($_POST['wplms_revert_4_0'])){
	    			//deactivate_plugins(array('wplms_plugin/loader.php','vibebp/loader.php'));
	    			delete_option('wplms_4_0');
    			}

	    		if(isset($_POST['wplms_confirm_4_0'])){
	    			update_option('wplms_4_0',2);
	    		}
    		}
    	}
    }

    public static function activate(){
    	register_post_type( 'course-layout',
			array(
				'labels' => array(
					'name' => __('Course Layouts','vibebp'),
					'menu_name' => __('Course Layouts','vibebp'),
					'singular_name' => __('Course Layout','vibebp'),
					'add_new_item' => __('Add New Course Layout','vibebp'),
					'all_items' => __('Course Layouts','vibebp')
				),
				'public' => true,
				'show_in_rest' => true,
				'publicly_queryable' => true,
				'show_ui' => true,
				'capability_type' => 'page',
	            'has_archive' => true,
				'show_in_menu' => 'vibebp',
				'show_in_admin_bar' => true,
				'show_in_nav_menus' => true,
				'supports' => array( 'title','editor','custom-fields'),
				'hierarchical' => false,
			)
		);

		register_post_type( 'course-card',
			array(
				'labels' => array(
					'name' => __('Course Card','vibebp'),
					'menu_name' => __('Course Card','vibebp'),
					'singular_name' => __('Course Card','vibebp'),
					'add_new_item' => __('Add New Course Card','vibebp'),
					'all_items' => __('Course Card','vibebp')
				),
				'public' => true,
				'show_in_rest' => true,
				'publicly_queryable' => true,
				'show_ui' => true,
				'capability_type' => 'page',
	            'has_archive' => true,
				'show_in_menu' => 'vibebp',
				'show_in_admin_bar' => true,
				'show_in_nav_menus' => true,
				'supports' => array( 'title','editor','custom-fields'),
				'hierarchical' => false,
			)
		);

		flush_rewrite_rules();

		if(!defined('VIBE_URL')){
			define('VIBE_URL', plugins_url('../','__FILE__'));
		}
    }
    function message(){
    	$active = get_option('ahp_wplms_activation_key');
    	if(empty($active)){
    		$active = get_option('ahp_wplms_plugin_activation_key');
    		if(empty($active)){
    			return 'WPLMS not active';	
			}
    	}
    }
}

WPLMS_4_Init::init();



function is_wplms_4_0($component = null){
	
	if(!empty($_GET['reload_nav'])){
		return true;
	}
	

	if(!defined('WPLMS_VERSION')){
		return true;
	}

	$init = WPLMS_4_Init::init();

	if(empty($init->vibe_option)){
		$init->vibe_option = get_option('wplms');	
	}
	
	if(!empty($init->vibe_option) && !empty($init->vibe_option['disable_vibebp_login'])){
		return false;
	}

	
	if(isset($init->is_wplms_4_0[$component])){
		return $init->is_wplms_4_0[$component];
	}

	$check = get_option('wplms_4_0');
	$init->is_wplms_4_0[$component] = $check;
	if(!empty($component)){
		$check = apply_filters('wplms_4_0_'.$component,$check);
		$init->is_wplms_4_0[$component] = $check;
	}

	if(empty($check)){
		$init->is_wplms_4_0[$component] = false;
	}
	return $init->is_wplms_4_0[$component];
}

function wplms_get_quiz_type($quiz_id){
	$type = get_post_meta($quiz_id,'vibe_type',true);
	if(empty($type)){
		$dy = get_post_meta($quiz_id,'vibe_quiz_dynamic',true);
		if(!empty($dy) && ($dy=='S' || $dy=='dynamic') ){
			$type ='dynamic';
		}else{
			$type ='static';
		}
		update_post_meta($quiz_id,'vibe_type',$type);
	}
	return $type;
}

add_filter('wplms_4_0_course','check_templates');
function check_templates($check){
	if(!function_exists('vibe_get_customizer')){
		return true;
	}
	$vibe_customizer = get_option('vibe_customizer');
	$course_layout =  (!empty($vibe_customizer)?(!empty($vibe_customizer['course_layout'])?$vibe_customizer['course_layout']:''):'');
	if(!empty($course_layout) && $course_layout != 'blank'){

		return false;
	}

	return $check;
}

function wplmsrandstring($n) 
{ 
    // Variable which store final string 
    $generated_string = ""; 
      
    // Create a string with the help of  
    // small letters, capital letters and 
    // digits. 
    $domain = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"; 
      
    // Find the length of created string 
    $len = strlen($domain); 
      
    // Loop to create random string 
    for ($i = 0; $i < $n; $i++) 
    { 
        // Generate a random index to pick 
        // characters 
        $index = rand(0, $len - 1); 
          
        // Concatenating the character  
        // in resultant string 
        $generated_string = $generated_string . $domain[$index]; 
    } 
      
    // Return the random generated string 
    return $generated_string; 
}

function wplms_assign_element($user_id,$quiz_id,$_type){
	$type = $_type;
	if($_type=='quiz'){
		$type='quizzes';//coz it was this
	}
	$quizzes = get_user_meta($user_id,'wplms_assigned_'.$type,true);
	if(empty($quizzes)){
		$quizzes = array();
	}
	if(!in_array($quiz_id, $quizzes)){
		$quizzes[] = $quiz_id;
	}
	do_action('wplms_user_'.$_type.'_assigned',$user_id,$quiz_id);
	return update_user_meta($user_id,'wplms_assigned_'.$type,$quizzes);
}


function wplms_remove_assigned_element($user_id,$quiz_id,$type){
	if($type=='quiz'){
		$type='quizzes';
	}
	$quizzes = get_user_meta($user_id,'wplms_assigned_'.$type,true);
	if(empty($quizzes)){
		$quizzes = array();
	}
	
	foreach ($quizzes as $key => $qq) {
		if($qq==$quiz_id){
			unset($quizzes[$key]);
		}
	}
	return update_user_meta($user_id,'wplms_assigned_'.$type,$quizzes);
}

function wplms_assign_element_to_course($courses,$quiz_id,$type){
	if(!empty($quiz_id)){
		if(!empty($courses)){
			$existing_courses = get_post_meta($quiz_id,'assigned_course',false);
			if(empty($existing_courses)){
				$existing_courses = array();
			}
			foreach ($courses as $key => $course) {
				if(!in_array($course, $existing_courses)){
					do_action('wplms_course_'.$type.'_assigned',$course,$quiz_id);
					add_post_meta($quiz_id,'assigned_course',$course);
					$existing_courses[] = $course;
				}
			}

			$not_in_current_courses = array_diff($existing_courses,$courses);
			if(!empty($not_in_current_courses)){
				foreach ($not_in_current_courses as $key => $cc) {
					delete_post_meta($quiz_id,'assigned_course',$cc);
				}
			}
		}else{
			//remove all metas;
			delete_post_meta($quiz_id,'assigned_course');
		}
	}
}

add_action('init',function(){
	if(!function_exists('vibe_sanitizer')){
		function vibe_sanitizer($a){
			return $a;
		}
	}
});