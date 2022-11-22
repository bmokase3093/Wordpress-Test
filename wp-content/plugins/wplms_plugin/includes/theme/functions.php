<?php
/**
 * General Functions for WPLMS
 *
 * @author      VibeThemes
 * @category    Admin
 * @package     Initialization
 * @version     2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if(!function_exists('wplms_get_script_src_by_handle')){

function wplms_get_script_src_by_handle($handle) {
    global $wp_scripts;
    if(in_array($handle, $wp_scripts->queue)) {
        return $wp_scripts->registered[$handle]->src;
    }
}
}

if(!function_exists('wplms_plugin_course_cat_menu_correction')){
  function wplms_plugin_course_cat_menu_correction($parent_file) {
    if(!defined('WPLMS_VERSION')){
      global $current_screen;
      $taxonomy = $current_screen->taxonomy;
      if ($taxonomy == 'course-cat' || $taxonomy == 'level' || $taxonomy == 'linkage' || $taxonomy == 'quiz-type')
          $parent_file = 'lms';
    }
      return $parent_file;
  }

  add_action('parent_file', 'wplms_plugin_course_cat_menu_correction');
}

if(!function_exists('wplms_plugin_convert_unlimited_time')){
  add_filter('course_friendly_time','wplms_plugin_convert_unlimited_time',1,3);
  function wplms_plugin_convert_unlimited_time($time_html,$time,$course_id){
      if(!defined('WPLMS_VERSION')){
        $course_duration_parameter = get_post_meta($course_id,'vibe_course_duration_parameter',true);
        if(!empty($course_duration_parameter) && $course_duration_parameter == 31536000){
          //user need to set 99999 days or more to set unlimited
          if(intval($time/86400) > 9998){
            return __('UNLIMITED ACCESS','wplms');
          }
        }else{
          if(intval($time/86400) > 999){
            return __('UNLIMITED ACCESS','wplms');
          }
        }
      }
      return $time_html;
  }
}

if(!function_exists('wplms_plugin_custom_course_parameter')){
  add_filter('course_friendly_time','wplms_plugin_custom_course_parameter',10,3);
  function wplms_plugin_custom_course_parameter($time_html,$time,$id){

    if(get_post_type($id) != BP_COURSE_CPT || defined('WPLMS_VERSION'))
      return $time_html;
    if(function_exists('vibe_get_option')){
      $parameter = vibe_get_option('course_duration_display_parameter');
    }else{
      $parameter = 86400;
    }
    
    if($parameter && intval($time/86400) < 999){
      $time_html = floor($time/$parameter).' '.calculate_duration_time($parameter);
    }
    return $time_html;
  }
}


if(!function_exists('wplms_plugin_custom_curriculum_time_filter')){
  add_filter('wplms_curriculum_time_filter','wplms_plugin_custom_curriculum_time_filter',2,10);
  function wplms_plugin_custom_curriculum_time_filter($html,$min){
    if(!defined('WPLMS_VERSION')){
      $minutes = $min;
      $hours = '00';
      if($minutes > 60){
        $hours = intval($minutes/60);
        $minutes = $minutes - $hours*60;
      }
      if($min > 9998){
        $html = '<span><i class="icon-clock"></i> '.__('UNLIMITED TIME','wplms').'</span>';
      }
    }
    return $html;
  }
}



if(!function_exists('wplms_plugin_my_request_filter')){
  add_filter( 'request', 'wplms_plugin_my_request_filter' );
  function wplms_plugin_my_request_filter( $query_vars ) {
    if(!defined('WPLMS_VERSION')){
      if( isset( $_GET['s'] ) && empty( $_GET['s'] ) ) {
          $query_vars['s'] = " ";
      }
    }
      return $query_vars;
  }
}


if(!function_exists('wplms_plugin_get_all_taxonomy_terms')){
    function wplms_plugin_get_all_taxonomy_terms(){
        $taxonomies=get_taxonomies('','objects'); 
        $termchildren = array();
        foreach ($taxonomies as $taxonomy ) {
            $toplevelterms = get_terms($taxonomy->name, 'hide_empty=0&hierarchical=0&parent=0');
          foreach ($toplevelterms as $toplevelterm) {
                    $termchildren[$toplevelterm->slug] = $taxonomy->name.' : '.$toplevelterm->name;
            }
            }
            
    return $termchildren;  
    }
}

if(!function_exists('wplms_plugin_wp_get_attachment_info')){
  function wplms_plugin_wp_get_attachment_info( $attachment_id ) {
       
	  $attachment = get_post( $attachment_id );
        if(isset($attachment)){
        	return array(
        		'alt' => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
        		'caption' => $attachment->post_excerpt,
        		'description' => $attachment->post_content,
        		'href' => get_permalink( $attachment->ID ),
        		'src' => $attachment->guid,
        		'title' => $attachment->post_title
        	);
        }
  }
}

if(!function_exists('wplms_plugin_validata_certificate_code')){
  add_action('wplms_validate_certificate','wplms_plugin_validata_certificate_code',10,2);
  function wplms_plugin_validata_certificate_code($user_id,$course_id){
    if(!defined('WPLMS_VERSION')){
      bp_course_validate_certificate('user_id='.$user_id.'&course_id='.$course_id);  
    }
  }
}

if(!function_exists('wplms_plugin_get_image_id')){
  function wplms_plugin_get_image_id($image_url) {
      global $wpdb;
      
      $attachment = $wpdb->get_var($wpdb->prepare("SELECT ID FROM {$wpdb->posts} WHERE guid='%s'",$image_url));
    if($attachment)
          return $attachment;
      else
          return false;
  }
}



//==== End Show Values ====



if(!function_exists('wplms_plugin_get_current_post_type')){
function wplms_plugin_get_current_post_type() {
  global $post, $typenow, $current_screen;
  
  //lastly check the post_type querystring
  if( isset( $_REQUEST['post_type'] ) )
    return sanitize_key( $_REQUEST['post_type'] );
  
  elseif ( isset( $_REQUEST['post'] ) )
    return get_post_type($_REQUEST['post']);
  
  elseif ( $post && $post->post_type )
    return $post->post_type;
  
  elseif( $typenow )
    return $typenow;

  //check the global $current_screen object - set in sceen.php
  elseif( $current_screen && $current_screen->post_type )
    return $current_screen->post_type;

  //we do not know the post type!
  return 'post';
}
}

if(!function_exists('vibe_sanitize')){
  function vibe_sanitize($array){
    if(isset($array[0]) && is_array($array[0]))
      return $array[0];
  }
}

if(!function_exists('vibe_validate')){
  function vibe_validate($value){
    if(isset($value) && $value && $value !='H')
      return true;
    else
      return false;
  }
}



if(!function_exists('wplms_plugin_course_max_students_check')){
add_filter('wplms_course_product_id','wplms_plugin_course_max_students_check',10,2);
function wplms_plugin_course_max_students_check($pid,$course_id){
  if(defined('WPLMS_VERSION')){
    return $pid;
  }
    $max_students=get_post_meta($course_id,'vibe_max_students',true);
      if(is_numeric($max_students) && $max_students < 9998){
        $number = bp_course_count_students_pursuing($course_id);
        $check = false;
        if(is_user_logged_in()){
          $user_id = get_current_user_id();
          $check = bp_course_get_user_course_status($user_id,$course_id);
        }
        
        if($number >= $max_students && !$check)
          return '?error=full';
    }

    $pre_course=get_post_meta($course_id,'vibe_pre_course',true);
    if(!empty($pre_course)){
      if(is_user_logged_in()){
        $user_id = get_current_user_id();
        $pre_course_check_status = apply_filters('wplms_pre_course_check_status_filter',2);
        if(is_numeric($pre_course)){
          $user_check = get_user_meta($user_id,'course_status'.$pre_course,true);

          if($user_check > $pre_course_check_status){
            return $pid;
          }

        }elseif(is_array($pre_course)){
            foreach($pre_course as $course_id){
              $flag = 0;
              $user_check = get_user_meta($user_id,'course_status'.$course_id,true);

              if($user_check <= $pre_course_check_status){
                $flag = 1;
                break;
              }
            }
            if(empty($flag))
              return $pid;
        }
      }
      return '?error=precourse';
    }
    return $pid;
}
}

// Below function is used in multiple locations so keeping as it is
if(!function_exists('wplms_plugin_get_course_unfinished_unit')){
function wplms_plugin_get_course_unfinished_unit($course_id,$user_id=null){
    $init = WPLMS_Plugin_Actions::init();
    $unit_id = $init->get_course_unfinished_unit($course_id,$user_id);
    return $unit_id;
}
}
// ====== WOOCOMMERCE FIXES ===== 

if(!function_exists('wplms_plugin_course_finished_course_review_form')){
add_filter('wplms_course_finished','wplms_plugin_course_finished_course_review_form');
function wplms_plugin_course_finished_course_review_form($return){
  if(!defined('WPLMS_VERSION')){
    global $withcomments;
    $withcomments = true;
    ob_start();
    comments_template('/course-review.php',true);
    $return .= ob_get_contents();
    ob_end_clean();
  }
  return $return;
}

}




// Module Tag Meta data
if(!function_exists('wplms_plugin_user_unit_join_module_tag')){
function wplms_plugin_user_unit_join_module_tag($join) {
  if(!defined('WPLMS_VERSION')){
    global $wp_query, $wpdb;
    if (!is_admin() && !empty($wp_query->query_vars['taxonomy']) && $wp_query->query_vars['taxonomy'] == 'module-tag') {
        $join .= "LEFT JOIN $wpdb->usermeta ON $wpdb->usermeta.meta_key LIKE CONCAT('complete_unit_', $wpdb->posts.ID ,'%')";
    }
  }
    return $join;
}
}
if(!function_exists('wplms_plugin_user_unit_where_module_tag')){
function wplms_plugin_user_unit_where_module_tag($where){
  if(!defined('WPLMS_VERSION')){
    global $wp_query, $wpdb;
    $user_id = get_current_user_id();
    if (!is_admin() && !empty($wp_query->query_vars['taxonomy']) && $wp_query->query_vars['taxonomy'] == 'module-tag') {
        $where .= "AND $wpdb->usermeta.meta_value REGEXP '^[0-9]*$'";
    }
  }
  return $where;
}
add_filter('posts_join', 'wplms_plugin_user_unit_join_module_tag');
add_filter('posts_where', 'wplms_plugin_user_unit_where_module_tag');

}

if(!function_exists('wplms_plugin_get_instructor_student_count')){

function wplms_plugin_get_instructor_student_count($instructor_id){
  global $wpdb;
  $count = $wpdb->get_var("SELECT sum(meta_value) FROM {$wpdb->postmeta} as m LEFT JOIN {$wpdb->posts} as p ON p.ID = m.post_id WHERE p.post_author = $instructor_id AND p.post_status = 'publish' AND p.post_type = 'course' AND m.meta_key = 'vibe_students'");
  if(empty($count))
    $count = 0;

  return apply_filters('wplms_plugin_get_instructor_student_count',$count,$instructor_id);
}
}

if(!function_exists('wplms_plugin_get_instructor_average_rating')){

function wplms_plugin_get_instructor_average_rating($instructor_id){
  global $wpdb;
  $count = $wpdb->get_var("SELECT avg(meta_value) FROM {$wpdb->postmeta} as m LEFT JOIN {$wpdb->posts} as p ON p.ID = m.post_id WHERE p.post_author = $instructor_id AND p.post_status = 'publish' AND p.post_type = 'course' AND m.meta_key = 'average_rating'");
  if(empty($count))
    $count = 0;

  return $count;
}
}
/*
// Sanitizer 
if(!function_exists('vibe_sanitizer')){
  function vibe_sanitizer($string,$context=null){
    switch ($context) {
      case 'text':
        $string = esc_attr($string);
        break;
      case 'html':
        
        break;
      case 'url':
      
        break;
      default:
        break;
    }
    return apply_filters('vibe_sanitizer_filter',$string,$context);
  }
}

*/


//Custom Functions Begin

if(!function_exists('wplms_get_element_type')){


  function wplms_get_element_type($id,$post_type){
      switch ($post_type) {
          case 'quiz':
              $type = wplms_get_quiz_type($id);
          break;
          case 'unit':
              $type = get_post_meta($id,'vibe_type',true);
              if($type == 'text-document'){$type = 'general';}
              if($type == 'play'){$type = 'video';}
              if($type == 'music-file-1'){$type = 'audio';}
              if($type == 'podcast'){$type = 'audio';}
          break;
          case 'assignment':
              $type = get_post_meta($id,'vibe_assignment_submission_type',true);
              if($type == 'S'){
                  $type = 'upload';
              }else{
                  $type = 'textarea';
              }
          break;
          default:
             $type = apply_filters('wplms_get_element_type_type','',$id,$post_type);
          break;
      }

      return apply_filters('wplms_get_element_type',$type,$id,$post_type);                
  }
}


function wplms_get_element_icon($type){
    switch($type){
        case 'slides':
            $icon = 'vicon vicon-layout-slider';
        break;
        case 'audio':
            $icon = 'vicon vicon-microphone';
        break;
        case 'video':
            $icon = 'vicon vicon-video-camera';
        break;
        case 'multimedia':
            $icon = 'vicon vicon-video-clapper';
        break;
        case 'textarea':
            $icon = 'vicon vicon-text';
        break;
        case 'upload':
            $icon = 'vicon vicon-upload';
        break;
        case 'dynamic':
          $icon = 'vicon vicon-control-shuffle';
        break;
        case 'static':
          $icon = 'vicon vicon-exchange-vertical';
        break;
        default:
            $icon = 'vicon vicon-text';
        break;        
    }

    return apply_filters('wplms_get_element_icon',$icon,$type);
}


function wplms_get_component_status($status,$component){

    switch($component){
        case 'course':
            switch($status){
                case 1:
                    $status=_x('Not Started','course status','wplms');
                break;
                case 2:
                $status=_x('Pursing Course','course status','wplms');
                break;
                case 3:
                $status=_x('Pending Evaluation','course status','wplms');
                break;
                case 4:
                $status=_x('Finished Course','course status','wplms');
                break;
            }
        break;
        case 'quiz':
            switch($status){
                case 1:
                    $status=_x('Started','quiz status','wplms');
                break;
                case 2:
                $status=_x('On Going','quiz status','wplms');
                break;
                case 3:
                $status=_x('Pending Evaluation','quiz status','wplms');
                break;
                case 4:
                $status=_x('Evaluated','quiz status','wplms');
                break;
            }
        break;
        case 'assignment':
        case 'wplms-assignment':
             switch($status){
                case 0:
                    $status = _x('Pending evaluation','submission status','wplms');
                break;
                case 1:
                    $status = _x('Evaluation complete','submission status','wplms');
                break;
                case 2:
                    $status = _x('Unsubmitted','submission status','wplms');
                break;
            }
        break;
    }

    return apply_filters('wplms_get_component_status',$status,$component);
}



function wplms_get_instructor_student_count($instructor_id){
  global $wpdb;
  $count = $wpdb->get_var("SELECT sum(m.meta_value) FROM {$wpdb->postmeta} as m LEFT JOIN {$wpdb->posts} as p ON p.ID = m.post_id WHERE p.post_author = $instructor_id AND p.post_status = 'publish' AND p.post_type = 'course' AND m.meta_key = 'vibe_students'");
  if(empty($count))
    $count = 0;

  return apply_filters('wplms_get_instructor_student_count',$count,$instructor_id);
}

function wplms_get_instructor_rating_count($instructor_id){
  global $wpdb;
  $count = $wpdb->get_var("SELECT sum(p.comment_count) FROM {$wpdb->posts} as p WHERE p.post_author = $instructor_id AND p.post_status = 'publish' AND p.post_type = 'course'");
  if(empty($count))
    $count = 0;

  return apply_filters('wplms_get_instructor_rating_count',$count,$instructor_id);
}

function wplms_get_instructor_average_rating($instructor_id){
  global $wpdb;
  $count = $wpdb->get_var("SELECT avg(m.meta_value) FROM {$wpdb->postmeta} as m LEFT JOIN {$wpdb->posts} as p ON p.ID = m.post_id WHERE p.post_author = $instructor_id AND p.post_status = 'publish' AND p.post_type = 'course' AND m.meta_key = 'average_rating'");
  
  return $count;
}

function wplms_courses_sort_options(){
  return apply_filters('wplms_courses_sort_options',array(
      'newest' =>__('Recently Added','wplms'),
      'alphabetical' =>__('Alphabetical','wplms'),
      'random'=>__('Random','wplms'),
      'popular'=>__('Popular','wplms'),
      'upcoming'=>__('Upcoming','wplms'),
    ));
}
function wplms_get_featured_cards(){
    $featured_cards = array(
        'course' => _x('Default Course','elementor selector','wplms'),
        'course2' => _x('Course 2','elementor selector','wplms'),
        'course3' => _x('Course 3','elementor selector','wplms'),
        'course4' => _x('Course 4','elementor selector','wplms'),
        'course5' => _x('Course 5','elementor selector','wplms'),
        'course6' => _x('Course 6','elementor selector','wplms'),
        'course7' => _x('Course 7','elementor selector','wplms'),
        'course8' => _x('Course 8','elementor selector','wplms'),
        'course9' => _x('Course 9','elementor selector','wplms'),
        'course10' => _x('Course 10','elementor selector','wplms'),
        'postblock' => _x('Post block','elementor selector','wplms'),
        'testimonial'=> _x('Testimonial','elementor selector','wplms'),
        'testimonial2'=> _x('Testimonial 2','elementor selector','wplms'),
        'event_card'=> _x('Event Card','elementor selector','wplms'),
        'side'=> _x('Side Block','elementor selector','wplms'),
        'blogpost' => _x('Blog post','elementor selector','wplms'),
        'images_only'=> _x('Image','elementor selector','wplms'),
        'general'=> _x('General','elementor selector','wplms'),
        'generic'=> _x('Generic','elementor selector','wplms'),
        'simple'=> _x('Simple','elementor selector','wplms'),
        'blog_card'=> _x('Blog card','elementor selector','wplms'),
        'generic_card'=> _x('Generic card','elementor selector','wplms'),
        'course_card'=> _x('Elementor Course card','elementor selector','wplms'),
    );

    //new WP_Query(array('post_type'=>'course-card','posts_per_page'=>-1));
    return apply_filters('wplms_get_featured_cards',$featured_cards);
}


function vibe_fetch_remote_file( $url,$post,$name=null) {
     if($name){
        $file_name  = basename( $name );
     }else{
        $file_name  = basename( $url );
     }
    
    $upload     = false;

    if ( ! $upload || $upload['error'] ) {
      // get placeholder file in the upload dir with a unique, sanitized filename
      $upload = wp_upload_bits( $file_name, 0, '', $post['upload_date'] );
      if ( $upload['error'] ) {
        return new WP_Error( 'upload_dir_error', $upload['error'] );
      }

      $max_size = (int) apply_filters( 'import_attachment_size_limit', 9999999999 );

     
      $vibe_url = $url;
      $response = wp_remote_get( $vibe_url ,array('timeout' => 120));
      if ( is_array( $response ) && ! empty( $response['body'] ) && $response['response']['code'] == '200' ) {
        //
      }else{
        return new WP_Error( 'upload_dir_error', $response );
      }

      if ( is_array( $response ) && ! empty( $response['body'] ) && $response['response']['code'] == '200' ) {
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
        $headers = $response['headers'];
        WP_Filesystem();
        global $wp_filesystem;
        $wp_filesystem->put_contents( $upload['file'], $response['body'] );
        //
      } else {
        // required to download file failed.
        @unlink( $upload['file'] );

        return new WP_Error( 'import_file_error', esc_html__( 'Remote server did not respond','wplms' ) );
      }

      $filesize = filesize( $upload['file'] );

      if ( isset( $headers['content-length'] ) && $filesize != $headers['content-length'] ) {
        @unlink( $upload['file'] );

        return new WP_Error( 'import_file_error', esc_html__( 'Remote file is incorrect size','wplms' ) );
      }

      if ( 0 == $filesize ) {
        @unlink( $upload['file'] );

        return new WP_Error( 'import_file_error', esc_html__( 'Zero size file downloaded','wplms' ) );
      }

      if ( ! empty( $max_size ) && $filesize > $max_size ) {
        @unlink( $upload['file'] );

        return new WP_Error( 'import_file_error', sprintf( esc_html__( 'Remote file is too large, limit is %s','wplms' ), size_format( $max_size ) ) );
      }
    }


    return $upload;
}
function vibe_url_to_string($url){
    
    $url = str_replace('/', '',$url);
    $url = str_replace('.', '',$url);
    $url = str_replace(':', '',$url);
    $url = str_replace(' ', '-',$url);
    return $url;
}

function vibe_get_ext_from_mime(){

}
if(!function_exists('vibe_include_template')){
    function vibe_include_template( $path, $base = NULL ){

        $file_path = get_stylesheet_directory()."/templates/$path";

        if(!file_exists($file_path)){
            $file_path = get_template_directory()."/templates/$path";

            if(!file_exists($file_path)){

                if(!empty($base)){
                    

                    $custom_path = locate_template( array( $base ) );
                    if(!empty($custom_path))
                        load_template( $custom_path, true );
                    else
                        $file_path = get_template_directory()."/templates/$base";
                }
            }
        }
        
        $file_path= apply_filters('vibe_include_template',$file_path);
       
        if(file_exists($file_path)){
            include_once $file_path;     
        }
    }
}