<?php
/**
 * FILTER functions for WPLMS
 *
 * @author      VibeThemes
 * @category    Admin
 * @package     Initialization
 * @version     2.0
 */

if ( !defined( 'ABSPATH' ) ) exit;


if(!class_exists('WPLMS_Plugin_Filters')){

class WPLMS_Plugin_Filters{

    public static $instance;
    public $subscription_duration_parameter = 86400;
    public static function init(){

        if ( is_null( self::$instance ) )
            self::$instance = new WPLMS_Plugin_Filters();

        return self::$instance;
    }

    private function __construct(){

		add_filter('wpseo_title',array($this,'remove_wpseo_from_buddypress'));
		add_filter('wpseo_head',array($this,'add_wpseo_meta_desc_buddypress'));
		add_filter('comments_open',array($this,'manage_course_reviews'));
		add_filter('wplms_certificate_code_template_id',array($this,'wplms_get_template_id_from_certificate_code'));
		add_filter('wplms_certificate_code_user_id',array($this,'wplms_get_user_id_from_certificate_code'));
		add_filter('wplms_certificate_code_course_id',array($this,'wplms_get_course_id_from_certificate_code'));
		add_filter( 'manage_edit-course-cat_columns', array( $this, 'course_cat_columns' ),9 );
		add_filter( 'manage_course-cat_custom_column', array( $this, 'course_cat_column' ),9, 3 );
		add_filter('get_terms_orderby',array($this,'course_cat_orderby'),10,3);
		add_filter('wplms_course_filters_course_cat',array($this,'course_cat_nav_orderby'),10);
		add_filter('bp_get_the_profile_field_value', 'do_shortcode');
		add_filter('bp_get_profile_field_data', 'do_shortcode');
		add_filter('woocommerce_order_items_meta_display',array($this,'woocommerce_commission_display'));
		add_filter('wplms_frontend_cpt_query',array($this,'wplms_instructor_privacy_filter'),9,2);
		add_filter('wplms_backend_cpt_query',array($this,'wplms_instructor_privacy_filter2')); // Modified to 
		add_action('pre_get_posts', array($this,'wplms_instructor_privacy_filter_attachments'));

		add_action('plugins_loaded',function(){
			add_filter('wplms_course_creation_tabs',array($this,'course_front_mycred_setting'),12,2);

			add_filter('wplms_get_field_value',array($this,'wplms_get_field_value_mycred'),11,3)	;	
		});
		add_filter('wplms_quiz_metabox',array($this,'wplms_question_number_react'),10);
		add_filter('vibebp_user_can_create_groups',array($this,'conditionaly_bp_user_can_create_groups'),11,3);
		

	
    }

    function conditionaly_bp_user_can_create_groups($can_create,$restricted,$user_id=null){
    	if(!function_exists('vibe_get_option'))
    		return $can_create;
		$capability=vibe_get_option('group_create');
		if(isset($capability)){
			switch($capability){
				case 2: 
					if(!user_can($user_id,'edit_posts'))
						$can_create  = false;
				break;
				case 3:
					if(!user_can($user_id,'manage_options'))
						$can_create  = false;
				break;
			}
		}

		return $can_create;
	}

    function wplms_question_number_react($metabox){
		$metabox['vibe_question_number_react'] = array( // Text Input
			'label'	=> __('Number of questions per page','wplms'), // <label>
			'desc'	=> __('Number of questions. to be loaded on one screen in quiz','wplms'), // description
			'id'	=> 'vibe_question_number_react', // field id and name
			'type'	=> 'text', // type of field
			'std'   => 0
		);
		return $metabox;
	}
	function wplms_get_field_value_mycred($field,$id,$user_id){
		if($field['id']=='vibe_mycred_duration'){
			$meta = 9999;
			$parameter = 86400;
    		if(!empty($id)){
    			$meta = intval(get_post_meta($id,'vibe_mycred_duration',true));
				$parameter = intval(get_post_meta($id,'vibe_mycred_duration_parameter',true));
    		}
    		$field['value'] = array('value'=>$meta,'parameter'=>$parameter);;
		}
		return $field;
	}

    function course_front_mycred_setting($settings,$course_id){
    	if(defined('myCRED_VERSION')){
    		$prefix = 'vibe_';
    		$meta = 9999;
			$parameter = 86400;
    		if(!empty($course_id)){
    			$meta = get_post_meta($course_id,'vibe_mycred_duration',true);
				$parameter = get_post_meta($course_id,'vibe_mycred_duration_parameter',true);
    		}
    		$readparameter = __('DAYS','wplms');
			if(function_exists('wplms_calculate_duration_time')){
				$readparameter = wplms_calculate_duration_time($parameter);
			}

			$mycred_metabox = apply_filters('wplms_mycred_ metabox',array(  
				 array( // Text Input
					'label'	=> __('MyCred Points','wplms'), // <label>
					'desc'	=> __('MyCred Points required to take this course.','wplms'),
					'id'	=> $prefix.'mycred_points', // field id and name
					'from' => 'meta',
					'type'	=> 'number', // type of field
				),
			     array( // Text Input
					'label'	=> __('MyCred Subscription ','wplms'), // <label>
					'desc'	=> __('Enable subscription mode for this Course','wplms'), // description
					'id'	=> $prefix.'mycred_subscription', // field id and name
					'from' => 'meta',
					'children'=>array('vibe_mycred_duration','vibe_mycred_duration_parameter'),
			        'type'=> 'switch',
                    'default'=>'H',
                    'options'  => array('H'=>__('Yes','wplms' ),'S'=>__('No','wplms' )),
				),
			      array( // Text Input
					'label'	=> __('Subscription Duration','wplms'), // <label>
					'desc'	=> __('Duration for Subscription Products (in ','wplms').$readparameter.')', // description
					'from' => 'meta',
					'id'	=> $prefix.'mycred_duration', // field id and name
					'type'=> 'duration',
					'is_child'=>true,
					'value' => array('value'=>$meta,'parameter'=>$parameter)
				),
			));
			array_splice($settings['course_pricing']['fields'],(count($settings)-2),0,$mycred_metabox);
    	}
    	
		return $settings;
    }

    function wplms_instructor_privacy_filter_attachments($wp_query){

    	if(function_exists('vibe_get_option'))
			$instructor_privacy = vibe_get_option('instructor_content_privacy');
		if(empty($instructor_privacy) || current_user_can('manage_options'))
		  return;

		if ( $wp_query->query['post_type'] != 'attachment' || !current_user_can('edit_posts')) {
		  return;
		}

		$user_id = get_current_user_id();
		$wp_query->set( 'author', $user_id );
	}

    function wplms_instructor_privacy_filter2($args=array()){
    	if(function_exists('vibe_get_option'))
	    	$instructor_privacy = vibe_get_option('instructor_content_privacy');
	    if(isset($instructor_privacy) && $instructor_privacy && !current_user_can('manage_options')){
	        $current_user = wp_get_current_user();
	        if($args['post_type'] != 'product' || $args['post_type'] != 'certificate')
	          $args['author'] = $current_user->ID;
	    }
	    return $args;
	}

    function wplms_instructor_privacy_filter($args=array(),$user=null){
   		if(class_exists('Wplms_Filters')){
   			$filters = Wplms_Filters::init();
   			remove_filter('wplms_frontend_cpt_query',array($filters,'wplms_instructor_privacy_filter'),10,2);
   		}
		$user_id = 0;
		if(!empty($user)){
			$user_id = $user->id;
		}else{
			$instructor_privacy = vibe_get_option('instructor_content_privacy');
			if(isset($instructor_privacy) && $instructor_privacy){
				$user_id = get_current_user_id();
			}
		}
		if(!empty($user_id) && !user_can($user_id,'manage_options')){
			if($args['post_type']!='certificate'){
        		$args['author'] = $user_id;
			}
		}
	    return $args;
	}

    function woocommerce_commission_display($output){
		$output=preg_replace("/(.​*)(commission[0-9]+\:\s?[0-9]+\.?[0-9]{0,9})(.*​)/", " ", $output);
		return  $output;
	}

    function course_cat_orderby($orderby,$args,$taxonomies){
    	if ( is_admin() || ('course-cat' != $taxonomies[0] || !empty($orderby)))
        	return $orderby;

        $orderby = 'term_group';
    	$args['order'] = 'DESC';

    	return $orderby;
    }

    function course_cat_nav_orderby($args){
    	if(empty($args['orderby'])){
           $args['orderby'] = 'term_group';
           $args['order'] = 'DESC';
       	}
    	return $args;
    }
    
    public function course_cat_columns( $columns ) {
    	if(class_exists('Wplms_Filters')){
    		$filters = Wplms_Filters::init();
    		remove_filter( 'manage_edit-course-cat_columns', array( $filters, 'course_cat_columns' ) );
    	}
		$new_columns          = array();
		$new_columns['cb']    = $columns['cb'];
		$new_columns['thumb'] = __( 'Image', 'wplms' );
		$new_columns['order'] = __( 'Order', 'wplms' );
		unset( $columns['cb'] );

		return array_merge( $new_columns, $columns );
	}

    public function course_cat_column( $columns, $column, $id ) {
    	if(class_exists('Wplms_Filters')){
    		$filters = Wplms_Filters::init();
    		remove_filter( 'manage_course-cat_custom_column', array( $filters, 'course_cat_column' ),10, 3 );
    	}
		if ( 'thumb' == $column ) {

			$thumbnail_id = get_term_meta( $id, 'course_cat_thumbnail_id', true );

			if ( $thumbnail_id ) {
				$image = wp_get_attachment_thumb_url( $thumbnail_id );
			} else {
				$image = vibe_get_option('default_avatar');
				if(empty($image)){
					$image = VIBE_URL.'/assets/images/avatar.jpg';
				}
			}
			$image = str_replace( ' ', '%20', $image );
			
			$columns .= '<img src="' . esc_url( $image ) . '" alt="' . esc_attr__( 'Thumbnail', 'wplms' ) . '" class="wp-post-image" height="48" width="48" />';

		}

		if('order' == $column){
			$course_cat_order = get_term_meta($id,'course_cat_order',true);
			if(empty($course_cat_order))
				$course_cat_order = 0;
			$columns .= $course_cat_order;
		}
		return $columns;
	}

    function wplms_get_user_id_from_certificate_code($code){
	  	$codes = explode('-',$code);
	  	if(isset($codes[2]) && is_numeric($codes[2])){
	    	$user_id = intval($codes[2]);
	    	$user = get_userdata( $user_id );
			if ( $user === false ) {
				return 0;
			}
	    	return $user_id;
	  	}
	}

	function wplms_get_course_id_from_certificate_code($code){
	  	$codes = explode('-',$code);
	  	if(isset($codes[1]) && is_numeric($codes[1]) && get_post_type($codes[1]) == 'course'){
	    	$course_id = intval($codes[1]);
	    	if(get_post_type($course_id) == 'course')
	    		return $course_id;
	    	else
	    		return 0;
	  	}
	}


    function wplms_get_template_id_from_certificate_code($code){
	  	$codes = explode('-',$code);
	  	$template = intval($codes[0]);
	  	$post_type = get_post_type($template);
	  	if(in_array($post_type,array('page','certificate'))){
	  		return $template;	
	  	}else
	  		return 0;	  	
	}

    function manage_course_reviews($open,$post_id = null){
		if(get_post_type($post_id) == 'course' && isset($_POST['review'])){
			return true;
		}else{
			global $post;
			if(is_object($post) && $post->post_type == 'course' && $post->comment_status == 'open'){
				return true;
			}
		}
		return $open;
	}
	
  	function remove_wpseo_from_buddypress($title){
    	global $bp,$post;
    	
    	if((function_exists('bp_is_directory') && bp_is_directory()) || ( !empty($this->bp_pages) && in_array($post->ID,$this->bp_pages))){
    		$id = vibe_get_bp_page_id(bp_current_component());
    		
			$title = get_post_meta($id,'_yoast_wpseo_title',true);
			if(!empty($title))	{
				return sprintf('%s - %s',$title,get_bloginfo('name'));
			}else{
    			$title = sprintf(_x('%s Directory - %s','Directory Title format','wplms'),ucfirst(bp_current_component()),get_bloginfo('name'));
    		}
    	}

    	if (function_exists('bp_is_user') && bp_is_user()){
    		$title = ucfirst(bp_get_displayed_user_fullname()).' - '.get_bloginfo('name');
    	}
    	if (function_exists('bp_is_group') && bp_is_group()){
    		$title = ucfirst(bp_get_current_group_name()).' - '.get_bloginfo('name');
    	}
    	return $title;
    }

    function add_wpseo_meta_desc_buddypress(){
    	global $bp,$post;
    	$_post = $post;
    	if((function_exists('bp_is_directory') && bp_is_directory()) || ( !empty($this->bp_pages) && in_array($post->ID,$this->bp_pages))){
    		$id = vibe_get_bp_page_id(bp_current_component());
    		if(!empty($id)){
    			$metadesc = get_post_meta($id,'_yoast_wpseo_metadesc',true);
				if(!empty($metadesc))	{

					$metadesc_tag .= '<meta name="description" content="'. esc_attr( wp_strip_all_tags( stripslashes( $metadesc ) ) ).'"/>';
					echo vibe_sanitizer($metadesc_tag);

					
					if(function_exists('wpseo_frontend_head_init')){
						$GLOBALS['post'] = get_post($id);
						wpseo_frontend_head_init();
						$GLOBALS['post'] = $_post;
					}
					
				}
    		}
    	}
    	
    }

}

WPLMS_Plugin_Filters::init();

function wplms_plugin_get_directory_page($component){
	$wf = WPLMS_Plugin_Filters::init();
	return $wf->get_directory_page_id($component);
}


}