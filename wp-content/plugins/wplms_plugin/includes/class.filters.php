<?php
/**
 * Action functions for WPLMS 4
 *
 * @author      VibeThemes
 * @category    Admin
 * @package     WPLMS Plugin
 * @version     4.0
 */

 if ( ! defined( 'ABSPATH' ) ) exit;

class WPLMS_4_Filters{

    public static $instance;
    public static function init(){
    if ( is_null( self::$instance ) )
        self::$instance = new WPLMS_4_Filters();

        return self::$instance;
    }

    private function __construct(){
    	add_filter('vibebp_component_icon',array($this,'course_component_icon'),10,2);
        add_filter('wplms_course_creation_tabs',array($this,'elementor_installed'));

        add_filter('vibebp_elementor_layout_template_post_types',array($this,'add_post_types'));

        //add_filter('bp_course_single_item_view',array($this,'course_card'));
        add_filter('bp_get_title_parts',array($this,'fix_course_wp_title'),999999,2);
            
        add_action('wplms_vibe_carousel',array($this,'course_carousel'));
        add_action('wplms_vibe_grid',array($this,'course_grid'));

        add_filter('elementor_course_block_id',array($this,'course_block_ID'));
    
    
        add_filter('vibebp_member_dashboard',array($this,'student_instructor_sidebar'),10,2);

        add_filter('vibebp_force_apply_translations',array($this,'apply_translations'),10,2);

        add_filter('wplms_course_info_display_options',array($this,'enable_level_location'));

        add_filter('wplms_meta_box_meta_value',array($this,'check_quiz_dynamicity'),10,3);

        
        add_filter('vibebp_api_get_activity',array($this,'course_filter'),10,3);

        add_filter('wplms_check_course_page_widget_post',array($this,'check12'),10,3);

        add_filter('vibebp_member_profile_data',array($this,'member_profile_data'));
        add_action('vibebp_profile_get_profile_data',array($this,'display_profile_data'),10,2);


        add_filter('wplms_plugin_single_course_layout',array($this,'course_layout'),10,2);

        //Single unit view
        add_filter('the_content',array($this,'single_unit_view'));
        add_filter('wp_enqueue_scripts',array($this,'single_unit_view_scripts'));

        add_filter('bp_course_api_course_link',array($this,'bp_single_page'));


        add_filter('wp_get_attachment_url',array($this,'fix_for_multisite_attachment_blunder_by_wp'));

        //add_filter('wplms_my_quizzes',array($this,'check_assigned_quizzes'),10,3);

        add_filter('wplms_load_single_unit_js',array($this,'check_vc_and_elementor_units'),10,2);

        add_filter('vibe_get_option',array($this,'enable_news_v4'),10,3);


        add_filter( 'posts_search',array($this, 'search_course_by_title_only'), 500, 2 );
        add_filter('members_detail_get_member_stats',array($this,'course_count'),10,3);
        
        add_filter('vibebp_layouts',array($this,'default_layouts'));   

        add_filter('wplms_course_creation_tabs',array($this,'add_wallet_settings'));
    }

    function add_wallet_settings($tabs){
        if(function_exists('vibebp_get_setting') && !empty(vibebp_get_setting('enable_wallet'))){
            array_splice($tabs['course_pricing']['fields'],(count($tabs['course_pricing']['fields'])-2),0,array(
                 array(
                    'label' => __('Credits','wplms'), 
                    'text' => __('Store credis on which course will be sold','wplms'), 
                    'desc'  => __('Set store credits on which course will be sold','wplms'),
                    'id'    => 'vibe_course_credits',
                    'type'  => 'number', 
                    'from'  => 'meta',
                )
            ));
        }

        return $tabs;
    }

    function default_layouts($args){
        $args[]='course-layout';
        $args[]='course-card';
        return $args;
    }

    function search_course_by_title_only( $search, $wp_query ){
        global $wpdb;

        if($wp_query->query_vars['post_type']==BP_COURSE_SLUG){


            if ( empty( $search ) )
                return $search; // skip processing - no search term in query
            $q = $wp_query->query_vars;
            $n = ! empty( $q['exact'] ) ? '' : '%';
            $search = '';
            $searchand = '';
            foreach ( (array) $q['search_terms'] as $term ) {
                $term = esc_sql( like_escape( $term ) );
                $search .= "{$searchand}($wpdb->posts.post_title LIKE '{$n}{$term}{$n}')";
                $searchand = ' AND ';
            }
            if ( ! empty( $search ) ) {
                $search = " AND ({$search}) ";
                if ( ! is_user_logged_in() )
                    $search .= " AND ($wpdb->posts.post_password = '') ";
            }
        }
        return $search;
    }


    function course_count($stats_array,$body,$user_id){

        $stats_array[]=array(
            'key' => 'course_count',
            'type' => 'number',
            'label' => _x('Course Subscribed','members user stats','vibebp'),
            'value' =>  bp_course_get_total_course_count_for_user( $user_id ),
            'extras'=>[
                'api_url'=>'',
                'label'=> _x('Show subscribed courses','members user stats','vibebp'),
            ]
        );
        return $stats_array;
    }

    function enable_news_v4($return,$field,$compare){
        if(is_wplms_4_0() && $field=='show_news'){
            $return = 1;
        }

        return $return;
    }

    function check_vc_and_elementor_units($bool,$post){
        if (!empty($post) && class_exists('Elementor\Plugin') && Elementor\Plugin::instance()->db->is_built_with_elementor( $post->ID ) ) {
            $bool = false;
        }
        if(!empty($post) && class_exists('WPBMap')){
            $vc_enabled = get_post_meta($post->ID, '_wpb_vc_js_status', true);
            if($vc_enabled){
                $bool = false;
            }
        }
        
        return  $bool;
    }

    function check_assigned_quizzes($args,$_args,$user){
        if (!empty($_args['quiz_status']) && $_args['quiz_status']=='assigned') {
            $quizzes = get_user_meta($user->id,'wplms_assigned_quizzes',true);
            if(empty($quizzes)){
                $quizzes = [0];
            }
            global $wpdb;
            $user_courses = [];
            $_user_courses = $wpdb->get_results(apply_filters('wplms_usermeta_direct_query',$wpdb->prepare("
                SELECT posts.ID as id
                FROM {$wpdb->posts} AS posts
                LEFT JOIN {$wpdb->usermeta} AS meta ON posts.ID = meta.meta_key
                WHERE   posts.post_type   = %s
                AND   posts.post_status   = %s
                AND   meta.user_id   = %d
                ",'course','publish',$user->id)));

            if(!empty($_user_courses)){
                foreach($_user_courses as $course){
                    $user_courses[]=$course->id;
                }
                $results  = $wpdb->get_results("SELECT post_id from {$wpdb->postmeta} WHERE meta_value IN (".implode(',',$user_courses).") AND meta_key = 'assigned_course' ");
                $coursequizzes = [];
                if(!empty($results)){
                    foreach ($results as $key => $quiz) {
                        if(!in_array($quiz->post_id,$quizzes)){
                            $quizzes[] = $quiz->post_id;
                        }
                    }
                }
            }
            $args['post__in'] = $quizzes;
        }
        return $args;
    }

    function fix_for_multisite_attachment_blunder_by_wp($url){
        if(function_exists('is_multisite') && is_multisite() &&  strpos($url, 'sites')){
            preg_match('/(\/sites\/[0-9]*){2}/', $url, $output_array);
            if(!empty($output_array)){
                $url = str_replace($output_array[0], $output_array[1], $url);
            }
        }
        return $url;
    }

    function bp_single_page($link){
        if(function_exists('vibebp_get_setting') && vibebp_get_setting('bp_single_page')){
            $link  = explode('#',$link); 
            $singlepage  = vibebp_get_setting('bp_single_page');   
            if(function_exists('icl_object_id')){
                $singlepage = icl_object_id($singlepage,'page',true);
            }
            return get_permalink($singlepage).'#'.$link[1];
        }
        return $link;
    }

    function single_unit_view($content){
        global $post;
        $load_new_framework = apply_filters('wplms_load_single_unit_js',true,$post);
        if(is_wplms_4_0() && !empty($post) && $post->post_type == 'unit' && is_singular('unit') && $load_new_framework){
        
            $content = '<div class="single_unit" data-id="'.$post->ID.'"></div>';
        }

        return $content;
    }

    function single_unit_view_scripts(){
        global $post;
        $load_new_framework = apply_filters('wplms_load_single_unit_js',true,$post);
        if(is_wplms_4_0() &&  !empty($post) && $post->post_type == 'unit' && is_singular('unit') && $load_new_framework){
            wp_enqueue_style('wplms-cc',plugins_url('../assets/css/wplms.css',__FILE__),array(),WPLMS_PLUGIN_VERSION);
            wp_enqueue_script('singleunit-js',WPLMS_PLUGIN_INCLUDES_URL.'/../assets/js/singleunit.js',array('wp-element','wp-data','wp-redux-routine','wp-hooks'),WPLMS_PLUGIN_VERSION,true);
            $data = WPLMS_Course_Component_Init::init();
            wp_localize_script('singleunit-js','wplms_course_data',apply_filters('wplms_course_content_script_args',$data->get_wplms_course_data())); 
            wp_enqueue_script('wplms-scorm',plugins_url('vibe-shortcodes/js/scorm2.js',__FILE__),array('wp-element','wp-data','singleunit-js'),WPLMS_PLUGIN_VERSION);
        }
    }

    function check12($post){
        
        if($post->post_type=='course-layout'){
            $id = apply_filters('elementor_course_block_id','');
            if(!empty($id)){
                $post = get_post($id);
            }
        }

        return $post;
    }


    function course_filter($activity_args,$args,$user_id = null){
        
        if(empty($user_id))
            return $activity_args;

        if(!empty($args['filter']) && $args['filter'] === 'course'){
            $activity_args['filter'] = array('object'=>'course','user_id'=>$user_id); 
            if(!empty($args['primary_id'])){
                $activity_args['filter']['primary_id']=$args['primary_id'];
            }
            if(!empty($args['item_id'])){
                $activity_args['filter']['primary_id']=$args['item_id'];
            }
            if(!empty($args['secondary_item_id'])){
                $activity_args['filter']['secondary_id']=$args['secondary_item_id'];
            }
        }

        if(!empty($args['filter']) && $args['filter'] === 'course_instructor'){

            $course_ids = bp_course_get_instructor_courses($user_id);
            if(empty($course_ids)){
                $activity_args['filter'] = array('primary_id'=>999999,'object'=>'course');    
            }else{
                $activity_args['filter'] = array('primary_id'=>$course_ids,'object'=>'course'); 
            } 
        }

        return $activity_args;
    }

    function check_quiz_dynamicity($value,$post_id,$field_id){

        if(empty($value) && $field_id=='vibe_type'){
            $old_dynamic = get_post_meta($post_id,'vibe_quiz_dynamic',true);
            if(!empty($old_dynamic) && $old_dynamic =='S'){
                $value = 'dynamic';
            }else{
                $value = 'static';
            }
        }
        return $value;
    }

    function apply_translations($name,$item){
        if(!empty($item['parent']) && !empty($name) && $item['parent'] == 'course'){
            $name=translate($name,'wplms');     
        }
        return $name;
    }

    

    function add_post_types($post_types) {

        $post_types[]='course-layout';
        $post_types[]='course-card';

        return $post_types;

    }

    function course_card($view){

        if(is_wplms_4_0()){
            $layout = new WP_Query(apply_filters('wplms_plugin_single_course_card',array(
                    'post_type'=>'course-card',
                    'posts_per_page'=>1,
                    'meta_query'=>array(
                        'relation'=>'AND',
                        array(
                            'key'=>'course-cat',
                            'compare'=>'NOT EXISTS'
                        )
                    )
                )
            ));
            if($layout->have_posts()){
                while($layout->have_posts()){
                    $layout->the_post();
                    global $post;
                    $content=$post->post_content;
                    if(class_exists('\Elementor\Frontend')){
                        $elementorFrontend = new \Elementor\Frontend();
                        $elementorFrontend->enqueue_scripts();
                        $elementorFrontend->enqueue_styles();
                    }
                    return $content;
                }
            }
        }

        return $view;
    }

    function course_carousel($elementor){
        $elementor->add_control(
            'course_type',
            [
                'label' =>__('Profile Courses', 'wplms'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'mycourses' => [
                        'title' => __( 'My courses', 'wplms' ),
                        'icon' => 'vicon vicon-bookmark-alt',
                    ],
                    'instructing_courses' => [
                        'title' => __( 'Instructing Courses', 'wplms' ),
                        'icon' => 'vicon vicon-marker-alt',
                    ],
                ],
            ]
        );
    }

    
    function course_grid($elementor){
        $elementor->add_control(
            'course_type',
            [
                'label' =>__('Profile Courses', 'wplms'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'mycourses' => [
                        'title' => __( 'My Courses', 'wplms' ),
                        'icon' => 'vicon vicon-bookmark-alt',
                    ],
                    'instructing_courses' => [
                        'title' => __( 'Instructing Courses', 'wplms' ),
                        'icon' => 'vicon vicon-marker-alt',
                    ],
                ],
            ]
        );
    }

    function course_block_ID($id){
        
        if(!empty($this->course_id)){
            return $this->course_id;
        }

        $init = WPLMS_4_Init::init();
        if(empty($id) && !empty($init->course_id)){
            $id = $init->course_id;
        }
        
        if(empty($id)){
            global $post;
            if($post->post_type == 'course-layout' || $post->post_type =='course-card'){
                
                if(empty($this->course_id)){
                    $course_query = new WP_Query(array('post_type'=>'course','posts_per_page'=>1,'orderby'=>'random'));
                    if($course_query->have_posts()){
                        while($course_query->have_posts()){
                            $course_query->the_post();
                            $this->course_id = get_the_Id();
                        }
                    }
                    wp_reset_postdata();
                }
                return $this->course_id;
            }
            if($post->post_type == 'course'){
                    $id = $post->ID;                    
            }
        }

        return $id;
    }

    function course_component_icon($icon,$id){
    	if($id == 'course'){
    		$icon = '<svg width="24" height="24" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;">
    <path d="M24,21L21,21L22,18L23,18L24,21ZM23,17L22,17L22,9.74L11.077,15.308L0,8.308L12,3L23,9.231L23,17Z"/>
    <path d="M11.024,16.457L20,11.882L20,18C18.993,20.041 14.393,21 11.5,21C8.325,21 4.111,20.006 3,18L3,11.386L11.024,16.457Z" style="fill-opacity:0.6;"/>
</svg>';
    	}
    	return $icon;
    }


    function elementor_installed($tabs){
        $tabs['course_curriculum']['fields'][0]['curriculum_elements'][1]['types'][] =array(
                'id'=>'elementor',
                'icon'=>'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="72" height="72"><path d="M 5 5 L 5 27 L 27 27 L 27 5 L 5 5 z M 7 7 L 25 7 L 25 25 L 7 25 L 7 7 z M 11 11 L 11 21 L 13 21 L 13 11 L 11 11 z M 15 11 L 15 13 L 21 13 L 21 11 L 15 11 z M 15 15 L 15 17 L 21 17 L 21 15 L 15 15 z M 15 19 L 15 21 L 21 21 L 21 19 L 15 19 z"></path></svg>',
                'label'=>__('Elementor','wplms'),
                'fields'=>array(
                    array(
                        'label'=> __('Unit title','wplms' ),
                        'type'=> 'title',
                        'id' => 'post_title',
                        'from'=>'post',
                        'value_type'=>'single',
                        'style'=>'full',
                        'default'=> __('Unit Name','wplms' ),
                        'desc'=> __('This is the title of the unit which is displayed on top of every unit','wplms' )
                        ),
                    array(
                        'label'=> __('Unit Tag','wplms' ),
                        'type'=> 'taxonomy',
                        'taxonomy'=> 'module-tag',
                        'from'=>'taxonomy',
                        'value_type'=>'single',
                        'style'=>'assign_cat',
                        'id' => 'module-tag',
                        'default'=> __('Select a tag','wplms' ),
                    ),
                    array(
                        'label'=> __('What is the unit about','wplms' ),
                        'type'=> 'elementor',
                        'style'=>'',
                        'value_type'=>'single',
                        'id' => 'post_content',
                        'from'=>'post',
                        'extras' => '',
                        'default'=> __('Enter description about the unit.','wplms' ),
                        ),
                    array(
                        'label'=> __('Unit duration','wplms' ),
                        'type'=> 'duration',
                        'style'=>'course_duration_stick_left',
                        'id' => 'vibe_duration',
                        'from'=> 'meta',
                        'default'=> array('value'=>9999,'parameter'=>86400),
                        'from'=>'meta',
                    ),
                    array( 
                        'label' => __('Free Unit','wplms'),
                        'desc'  => __('Set Free unit, viewable to all','wplms'), 
                        'id'    => 'vibe_free',
                        'type'  => 'switch',
                        'default'   => 'H',
                        'from'=>'meta',
                    ),
                    array(
                        'label' => __('Unit Forum','wplms'),
                        'desc'  => __('Connect Forum with Unit.','wplms'),
                        'id'    => 'vibe_forum',
                        'type'  => 'selectcpt',
                        'post_type' => 'forum',
                        'std'=>0,
                        'from'=>'meta',
                    ),
                    array(
                        'label' => __('Connect Assignments','wplms'),
                        'desc'  => __('Select an Assignment which you can connect with this Unit','wplms'),
                        'id'    => 'vibe_assignment',
                        'type'  => 'selectmulticpt', 
                        'post_type' => 'assignment',
                        'from'=>'meta',
                    ),
                    array(
                        'label' => __('Attachments','wplms'),
                        'desc'  => __('Display these attachments below units to be downloaded by students','wplms'),
                        'id'    => 'vibe_unit_attachments', 
                        'type'  => 'multiattachments', 
                        'from'=>'meta',
                    ),
                    array(
                        'label'=> __('Practice Questions','wplms' ),
                        'text'=> '',
                        'type'=> 'practice_questions',
                        'from'=>'meta',
                        'post_type'=>'question',
                        'id' => 'vibe_practice_questions',
                        'default'=> __('Select a type','wplms' ),
                        'buttons' => array(
                            'question_types'=>wplms_get_question_types(),
                        )
                    ),
                )
            );
        return $tabs;
    }

    function student_instructor_sidebar($sidebar,$user_id){

        $user = get_userdata( $user_id );
        if(!empty($user)){
            if ( in_array( 'administrator', $user->roles, true ) ) {
                return 'instructor_sidebar';
            }
            if ( in_array( 'instructor', $user->roles, true ) ) {
                return 'instructor_sidebar';
            }
            if ( in_array( 'student', $user->roles, true ) ) {
                return 'student_sidebar';
            }    
                
        }

        return $sidebar;
    }

    function enable_level_location($info){

        if(function_exists('vibe_get_option') && vibe_get_option('level')){
            $info['level']=__('Course Levels','wplms');
        }
        if(function_exists('vibe_get_option') && vibe_get_option('location')){
            $info['location']=__('Course Location','wplms');
        }
        return $info;
    }

    function member_profile_data($return){
        $return['my_courses']=__('My Course Count','wplms');
        $return['my_quizzes']=__('My Quiz Count','wplms');
        $return['my_assignments']=__('My Assignment Count','wplms');
        $return['my_certificates']=__('My Certficate Count','wplms');
        $return['my_badges']=__('My Badges Count','wplms');
        $return['instructing_course']=__('Published Course Count','wplms');
        $return['instructing_quiz']=__('Published Quiz Count','wplms');
        $return['instructing_assignment']=__('Published Assignment Count','wplms');
        $return['instructing_question']=__('Published Questions Count','wplms');
        return $return;
    }

    function display_profile_data($type,$user_id){
        global $wpdb;
        if($type == 'my_courses'){
            $courses = $wpdb->get_var(apply_filters('wplms_usermeta_direct_query',$wpdb->prepare("
                SELECT count(posts.ID)
              FROM {$wpdb->posts} AS posts
              LEFT JOIN {$wpdb->usermeta} AS meta ON posts.ID = meta.meta_key
              WHERE   posts.post_type   = %s
              AND   posts.post_status   = %s
              AND   meta.user_id   = %d
              ",'course','publish',$user_id)));
            echo $courses;
        }
        if($type == 'my_quizzes'){
            $my_quizzes = $wpdb->get_var(apply_filters('wplms_usermeta_direct_query',$wpdb->prepare("
                SELECT count(posts.ID)
              FROM {$wpdb->posts} AS posts
              LEFT JOIN {$wpdb->usermeta} AS meta ON posts.ID = meta.meta_key
              WHERE   posts.post_type   = %s
              AND   posts.post_status   = %s
              AND   meta.user_id   = %d
              ",'quiz','publish',$user_id)));
            echo $my_quizzes;
        }
        if($type == 'my_assignments'){
            $my_assignments = $wpdb->get_results(apply_filters('wplms_usermeta_direct_query',$wpdb->prepare("
                SELECT count(posts.ID)
              FROM {$wpdb->posts} AS posts
              LEFT JOIN {$wpdb->usermeta} AS meta ON posts.ID = meta.meta_key
              WHERE   posts.post_type   = %s
              AND   posts.post_status   = %s
              AND   meta.user_id   = %d
              ",'wplms-assignment','publish',$user_id)));
            echo $my_assignments;
        }
        if($type == 'my_certificates'){
            $count = get_user_meta($user_id,'certificates',true);
            echo is_array($count)?count($count):0;
        }
        if($type == 'my_badges'){
            $count = get_user_meta($user_id,'badges',true);
            echo is_array($count)?count($count):0;
        }


        if($type == 'instructing_course'){
            echo bp_course_get_instructor_course_count_for_user($user_id);
        }
        if($type == 'instructing_quiz'){
            echo count_user_posts_by_type($user_id,'quiz');
        }
        if($type == 'instructing_assignment'){
            echo count_user_posts_by_type($user_id,'wplms-assignment');
        }
        if($type == 'instructing_question'){
            echo count_user_posts_by_type($user_id,'question');
        }
    }

    //Course specific layout
    function course_layout($args,$course_id){

        //get_post_meta('') - check if course layout assigned to coruse, if yes, return with that. 
        $layout_id = get_post_meta($course_id,'course_layout',true);
        if(!empty($layout_id)){
            $args['p'] = $layout_id;
            unset($args['meta_query']);
            return $args;
        }
        $cats = get_the_terms($course_id,'course-cat');
        if(!empty($cats)){
            
            global $wpdb; 
            foreach ($cats as $key => $ca) {
                $layout_id = $wpdb->get_var($wpdb->prepare("SELECT post_id FROM {$wpdb->postmeta} as pm LEFT JOIN {$wpdb->posts} as p ON p.ID=pm.post_id WHERE pm.meta_key='course-cat' AND p.post_type='course-layout' AND p.post_status='publish' AND  pm.meta_value=%d",$ca->term_id));
                if(!empty($layout_id)){
                    
                     $args['p'] = $layout_id;
                     unset($args['meta_query']);
                     return $args;
                     break;
                }
            }
            
        }
        // <-- get all categories , check if there is any course layout attached to those categories. and show the first match.
        return $args;
    }

    function fix_course_wp_title( $bp_title_parts){
        global $post;
        if(!empty($post) && $post->post_type=='course'){
            $bp_title_parts = array($post->post_title);
        }
        return  $bp_title_parts;
    }

}

WPLMS_4_Filters::init();

