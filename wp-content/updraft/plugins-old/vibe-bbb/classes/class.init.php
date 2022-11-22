<?php
/**
 * Init in VibeBbb
 *
 * @author 		VibeThemes
 * @category 	Admin
 * @package 	vibe_bbb/Includes
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class Vibe_Bbb_Init{

	public static $instance;
    
    public static function init(){

        if ( is_null( self::$instance ) )
            self::$instance = new Vibe_Bbb_Init();
        return self::$instance;
    }

	private function __construct(){

        add_action('wp_enqueue_scripts',array($this,'enqueue_script'));
        add_action( 'init', array( $this,'register_post_types') );
        add_action( 'bp_setup_nav', array($this,'add_projects_tab'), 101 );

        add_filter('vibebp_component_icon',array($this,'set_icon'),10,2);
        add_filter('wplms_get_element_icon',array($this,'set_icon'),10,2);

        add_filter('wplms_course_creation_tabs',array($this,'bbb_unit'));
        add_action('rest_api_init',function(){
            add_filter('the_content',array($this,'curriculum_bbb_meeting'),10,4);    
        });

        //email reminder 4.x
        add_action('wplms_bbb_meeting_updated',array($this,'wplms_bbb_meeting_updated'),10,2);
        add_action('wplms_send_vibe_bbb_reminders_vibebp',array($this,'wplms_send_vibe_bbb_reminders_vibebp'),10,1);
    
    }

    function set_icon($icon,$component_name){

        if($component_name == 'bbb' || $component_name == 'vibe_bbb' || $component_name == 'bbb_meeting'){
            return '<svg style="width: 100%;height: 100%;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 109.5 109.5"><path d="M54.75,5A49.75,49.75,0,1,1,5,54.75,49.81,49.81,0,0,1,54.75,5m0-5A54.75,54.75,0,1,0,109.5,54.75,54.75,54.75,0,0,0,54.75,0Z" /><path d="M32.4,26.8c5.71,0,11.41,6.58,11.41,14.81V71.85a3.69,3.69,0,0,0,4,3.57H66.11c1.67,0,1.48-1.08,1.48-2.75V57.09c0-.54-.26-.7-.75-.7H63.19c-12.18,0-16.53-8.56-16.53-10.46H66.91c9,0,10.19,5.63,10.19,8.38V72.89c0,8.28-2.66,14.89-9.07,14.89H47.7c-9.07,0-15.3-8.06-15.3-15.71V26.8m0-2.85H29.55V72.07c0,9,7.29,18.56,18.15,18.56H68C75.5,90.63,80,84,80,72.89V54.31c0-5.43-3.43-11.23-13-11.23H46.67V41.61c0-9.41-6.67-17.67-14.27-17.67ZM47.79,72.56c-1.07,0-1.13-.59-1.13-.71V52.09c3,3.61,8.38,7.16,16.52,7.16h1.55V72.56Z" /></svg>';
        }
        return $icon;
    }
    
    function register_post_types(){
        register_post_type( 'vibe_bbb',
			array(
				'labels' => array(
					'name' => __('Wplms-Bbb','vibe-bbb'),
					'menu_name' => __('Wplms-Bbb','vibe-bbb'),
					'singular_name' => __('Metting','vibe-bbb'),
					'add_new_item' => __('Add New Meeting','vibe-bbb'),
					'all_items' => __('Vibe Bbb Meetings','vibe-bbb')
				),
				'public' => true,
				'show_in_rest' => true,
				'publicly_queryable' => true,
				'show_ui' => true,
				'capability_type' => 'page',
	            'has_archive' => true,
				'show_in_menu' => 'vibebp',
				'show_in_admin_bar' => false,
				'show_in_nav_menus' => true,
				'supports' => array( 'title','editor','custom-fields'),
				'hierarchical' => false,
			)
		);
    }

    function add_projects_tab(){
        global $bp;
        $slug = 'bbb_meeting';
        bp_core_new_nav_item( array( 
            'name' => __('Vibe BBB','vibe-bbb'),
	        'slug' => $slug, 
	        'item_css_id' => 'vibebbb',
	        'screen_function' => array($this,'show_screen'),
	        'default_subnav_slug' => 'home', 
	        'position' => 58,
        	'show_for_displayed_user'=>false,
	        'user_has_access' => (bp_is_my_profile() || current_user_can('manage_options'))
	    ) );
		bp_core_new_subnav_item( array(
			'name' 		  => __('My meetings','vibe-bbb'),
			'slug' 		  => 'my_meetings',
			'parent_slug' => $slug,
        	'parent_url' => $bp->displayed_user->domain.$slug.'/',
			'screen_function' => array($this,'show_screen'),
			'user_has_access' => (bp_is_my_profile() || current_user_can('manage_options'))
		) );

	    bp_core_new_subnav_item( array(
			'name' 		  => __('Manage Meetings','vibe-bbb'),
			'slug' 		  => 'manage_bbb',
			'parent_slug' => $slug,
        	'parent_url' => $bp->displayed_user->domain.$slug.'/',
			'screen_function' => array($this,'show_screen'),
			'user_has_access' => (bp_is_my_profile() || current_user_can('manage_options'))
        ) );
        
        if (  apply_filters('show_vibebbb_vibecalendar',true)) {
            bp_core_new_subnav_item( array(
                'name' 		  => __('BBB Meetings','vibe-bbb'),
                'slug' 		  => 'vibe_bbb_meeting',
                'parent_slug' => 'calendar',
                'parent_url' => $bp->displayed_user->domain.$slug.'/',
                'screen_function' => array($this,'show_screen'),
                'user_has_access' => (bp_is_my_profile() || current_user_can('manage_options'))
            ) );
        }
	}

	function enqueue_script(){
        $blog_id = '';
        if(function_exists('get_current_blog_id')){
            $blog_id = get_current_blog_id();
        }

            
		$bbb=apply_filters('vibe_bbb_script_args',array(
			'api'=>array(
				'url'=>get_rest_url($blog_id,VIBE_BBB_API_NAMESPACE),
				'create_caps'=>'edit_posts',
            ),
            'settings'=>array(
                'logout_redirect_url' => get_site_url(),
                'duration_values'=> array(
                    array('value' =>'1','label'=>__('Seconds','vibe-bbb')),
                    array('value' =>'60','label'=>__('Minutes','vibe-bbb')),
                    array('value' =>'3600','label'=>__('Hours','vibe-bbb')),
                    array('value' =>'86400','label'=>__('Days','vibe-bbb')),
                    array('value' =>'604800','label'=>__('Weeks','vibe-bbb')),
                    array('value' =>'2592000','label'=>__('Months','vibe-bbb')),
                ),
                'editor_slug' => apply_filters('vibebbb_editor_slug',array('manage_bbb')),
                'new_vibebbb_cap'=>['edit_posts']
            ),
            'label'=>__('BBB','vibe-bbb'),
            'sorters'=>array(
                'date'=>_x('Recent','api','vibe-bbb'),
				'name'=>_x('Alphabetical','api','vibe-bbb'),
			),
			'order'=>array(
                'DESC'=>_x('Descending ','api','vibe-bbb'),
				'ASC'=>_x('Ascending','api','vibe-bbb'),
            ),
            'shared_tabs'=>array(
                'shared'=>_x('Shared ','api','vibe-bbb'),
                'group'=>_x('Group','api','vibe-bbb'),
                'course'=>_x('Course','api','vibe-bbb'),
            ),
            'shared_types'=>array(
                'shared'=>_x('Shared ','api','vibe-bbb'),
                'group'=>_x('Group','api','vibe-bbb'),
                'course'=>_x('Course','api','vibe-bbb'),
            ),
            'translations'=>array(
				'my_bbbs'=>__('My Meetings', 'vibe-bbb'),
				'create_bbb'=>__('Create New', 'vibe-bbb'),
                'meeting_title'=>__('Meeting Title', 'vibe-bbb'),
                'meeting_content'=>__('Meeting Content', 'vibe-bbb'),
                'bbb_category'=>__('Meeting Category', 'vibe-bbb'),
                'no_bbbs'=>__('No Meetings found.', 'vibe-bbb'),
                'search_text'=>__('Type to Search..', 'vibe-bbb'),
                'submit'=>__('Submit Meeting', 'vibe-bbb'),
                'preview'=>__('Preview Meeting', 'vibe-bbb'),
				'new_bbb_cateogry'=>__('New Meeting Category', 'vibe-bbb'),
				'load_more'=>__('Load more', 'vibe-bbb'),
				'title'=>__('Title', 'vibe-bbb'),
                'description'=>__('Description', 'vibe-bbb'),
                'meeting_start_time'=>__('Meeting Start Time', 'vibe-bbb'),
                'meeting_end_time'=>__('Meeting End Time', 'vibe-bbb'),
                'attendee_password'=>__('Attendee Password', 'vibe-bbb'),
                'recordable'=>__('Recordable', 'vibe-bbb'),
                'logout_redirect_url'=>__('Logout Redirect Url', 'vibe-bbb'),
                'moderator_password'=>__('Moderator Password', 'vibe-bbb'),
                'select_sharing_type'=>__('Select Sharing Type', 'vibe-bbb'),
                'select_shared_value'=>__('Select Shared Items', 'vibe-bbb'),
                'search_shared_values'=>__('Search sharing value', 'vibe-bbb'),
                'shared_with'=>__('Shared With', 'vibe-bbb'),
                'search_results'=>__('Search Result', 'vibe-bbb'),
                'select_duration_value'=>__('Select Duration Value', 'vibe-bbb'),
                'wait_for_moderator'=>__('Wait For Moderator', 'vibe-bbb'),
                'open_meeting'=> __('Open Meeting','vibe-bbb'),
                'start_meeting'=> __('Start Meeting','vibe-bbb'),
                'meeting_over'=>__('Meeting Over','vibe-bbb'),
                'meeting_running'=>__('Meeting Running','vibe-bbb'),
                'days'=>__('Days','vibe-bbb'),
                'hours'=>__('Hours','vibe-bbb'),
                'minutes'=>__('Minutes','vibe-bbb'),
                'seconds'=>__('Seconds','vibe-bbb'),
                'open_meeting_in_new_tab'=>__('Open Meeting In New Tab','vibe-bbb'),
                'today'=>__('Today','vibe-bbb'),
                'month'=>__('Month','vibe-bbb'),
                'week'=>__('Week','vibe-bbb'),
                'day'=>__('Day','vibe-bbb'),
                'list'=>__('List','vibe-bbb'),
                'starts'=>__('Starts','vibe-bbb'),
                'ends'=>__('Ends','vibe-bbb'),
                'cancel'=>__('Cancel','vibe-bbb'),
                'see_meeting'=>__('See meeting','vibe-bbb'),
                'show_recordings_to_user'=>__('Show Recodings To User','vibe-bbb'),
                'no_recordings'=>__('No Recordings Found!','vibe-bbb'),
                'starts'=>__('Starts : ','vibe-bbb'),
                'ends'=>__('Ends : ','vibe-bbb'),
                'view_recording'=>__('View Recording','vibe-bbb'),
            )
        ));
        $required_handle = array('wp-element','wp-data','vibebplogin');
        wp_enqueue_script('createbbb',plugins_url('../js/create_bbb.js',__FILE__),$required_handle,VIBE_BBB_VERSION);
        wp_enqueue_script('bbb_calendar',plugins_url('../js/bbb_calendar.js',__FILE__),array_merge($required_handle,array('fullcalendar')),VIBE_BBB_VERSION);
        wp_localize_script('createbbb','vibebbb',$bbb);
        wp_enqueue_style('vibe-bbb',plugins_url('../css/create_bbb.css',__FILE__),array('vibebp_main'),VIBE_BBB_VERSION);

	}


    function bbb_unit($tabs){
        $tabs['course_curriculum']['fields'][0]['curriculum_elements'][1]['types'][]= array(
            'id'=>'bbb',
            'icon'=>'<svg width="64" height="64" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 109.5 109.5"><path d="M54.75,5A49.75,49.75,0,1,1,5,54.75,49.81,49.81,0,0,1,54.75,5m0-5A54.75,54.75,0,1,0,109.5,54.75,54.75,54.75,0,0,0,54.75,0Z" /><path d="M32.4,26.8c5.71,0,11.41,6.58,11.41,14.81V71.85a3.69,3.69,0,0,0,4,3.57H66.11c1.67,0,1.48-1.08,1.48-2.75V57.09c0-.54-.26-.7-.75-.7H63.19c-12.18,0-16.53-8.56-16.53-10.46H66.91c9,0,10.19,5.63,10.19,8.38V72.89c0,8.28-2.66,14.89-9.07,14.89H47.7c-9.07,0-15.3-8.06-15.3-15.71V26.8m0-2.85H29.55V72.07c0,9,7.29,18.56,18.15,18.56H68C75.5,90.63,80,84,80,72.89V54.31c0-5.43-3.43-11.23-13-11.23H46.67V41.61c0-9.41-6.67-17.67-14.27-17.67ZM47.79,72.56c-1.07,0-1.13-.59-1.13-.71V52.09c3,3.61,8.38,7.16,16.52,7.16h1.55V72.56Z" /></svg>',
            'label'=>__('BigBlueButton Meeting','vibe-bbb'),
            'fields'=>array(
                array(
                    'label'=> __('Unit title','vibe-bbb' ),
                    'type'=> 'title',
                    'id' => 'post_title',
                    'from'=>'post',
                    'value_type'=>'single',
                    'style'=>'full',
                    'default'=> __('Unit Name','vibe-bbb' ),
                    'desc'=> __('This is the title of the unit which is displayed on top of every unit','vibe-bbb' )
                    ),
                array(
                    'label'=> __('Unit Tag','vibe-bbb' ),
                    'type'=> 'taxonomy',
                    'taxonomy'=> 'module-tag',
                    'from'=>'taxonomy',
                    'value_type'=>'single',
                    'style'=>'assign_cat',
                    'id' => 'module-tag',
                    'default'=> __('Select a tag','vibe-bbb' ),
                ),
                array(
                    'label'=> __('Add BigBlueButton Meeting','vibe-bbb' ),
                    'type'=> 'selectcpt',
                    'level'=>'bbb',
                    'post_type'=>'bbbhyphenroom',
                    'value_type'=>'single',
                    'desc'=>__('Select a BigBlueButton Meeting. Create new meetings in BitBlueButton Meetings Menu.','vibe-bbb' ),
                    'upload_button'=>__('Set as unit Meeting','vibe-bbb' ),
                    'style'=>'small_icon',
                    'from'=>'meta',
                    'is_child'=>true,
                    'id' => 'vibe_bbb_meeting',
                    'default'=> '',
                ),
                array(
                    'label'=> __('What is the unit about','vibe-bbb' ),
                    'type'=> 'editor',
                    'style'=>'',
                    'value_type'=>'single',
                    'id' => 'post_content',
                    'from'=>'post',
                    'extras' => '',
                    'default'=> __('Enter description about the unit.','vibe-bbb' ),
                ),
                array(
                    'label'=> __('Unit duration','vibe-bbb' ),
                    'type'=> 'duration',
                    'style'=>'course_duration_stick_left',
                    'id' => 'vibe_duration',
                    'from'=> 'meta',
                    'default'=> array('value'=>9999,'parameter'=>86400),
                    'from'=>'meta',
                ),
                array( 
                    'label' => __('Free Unit','vibe-bbb'),
                    'desc'  => __('Set Free unit, viewable to all','vibe-bbb'), 
                    'id'    => 'vibe_free',
                    'type'  => 'switch',
                    'default'   => 'H',
                    'from'=>'meta',
                ),
                array(
                    'label' => __('Unit Forum','vibe-bbb'),
                    'desc'  => __('Connect Forum with Unit.','vibe-bbb'),
                    'id'    => 'vibe_forum',
                    'type'  => 'selectcpt',
                    'post_type' => 'forum',
                    'std'=>0,
                    'from'=>'meta',
                ),
                array(
                    'label' => __('Connect Assignments','vibe-bbb'),
                    'desc'  => __('Select an Assignment which you can connect with this Unit','vibe-bbb'),
                    'id'    => 'vibe_assignment',
                    'type'  => 'selectmulticpt', 
                    'post_type' => 'assignment',
                    'from'=>'meta',
                ),
                array(
                    'label' => __('Attachments','vibe-bbb'),
                    'desc'  => __('Display these attachments below units to be downloaded by students','vibe-bbb'),
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
            ),
        );
        return $tabs;
    }

    function curriculum_bbb_meeting($post_content,$user_id=null,$item_id=null,$course_id=null){
        if(empty($user_id) || empty($item_id) || empty($course_id)){
            return $post_content;
        }

        $meeting_id = get_post_meta($item_id,'vibe_bbb_meeting',true);

        if(!empty($meeting_id)){
            $Vibe_Bbb_API = Vibe_Bbb_API::init();
            $details = $Vibe_Bbb_API->get_meeting_details($meeting_id,$user_id);
            $post_content .= '<ul class="bbb_meeting_details">
                <li><strong>'.__('Start(UTC)','vibe-bbb').'</strong><span>'.date('Y-m-d H:i:s', $details['start']/1000).'</span></li>
                <li><strong>'.__('End(UTC)','vibe-bbb').'</strong><span>'.date('Y-m-d H:i:s', $details['end']/1000).'</span></li>';
            

            if(!empty($details['can_join'])){
                $meeting_url = $Vibe_Bbb_API->get_meeting_url_by_id($meeting_id,$user_id,true);
                $post_content .= '<li><strong>'.__('Join url','vibe-bbb').'</strong><span><a href="'.$meeting_url.'" target="_blank">'.__('Join','vibe-bbb').'</a></span></li>';
            }else{
               $post_content .= '<div class="vbp_message">'.__('Meeting can not be joined now','vibe-bbb').'</div>';
            }

            $post_content .= '</ul>';

            //recording show
            $show_recordings = get_post_meta($meeting_id,'show_recordings',true);
            if(!empty($show_recordings)){
                include_once 'class-bigbluebutton-api.php';
                $recordings = Vibe_Bigbluebutton_Api::get_recordings(array($meeting_id));
                if(!empty($recordings)){
                    $recordings = json_decode(json_encode($recordings), true);
                    $post_content .= '<div class="bbb_meeting_recordings">';
                    if(!empty($recordings['recording'])){
                        if(!empty($recordings['recording']['recordID'])){  //single recording object gives
                            $recordings['recording']= array($recordings['recording']);
                        }
                        if(is_array($recordings['recording'])){
                            foreach ($recordings['recording'] as $key => $recording) {
                                $recording = (array)$recording;
                                $post_content .= '<div class="meeting_recording">';
                                $post_content .='<div class="meeting_recording_header">';
                                $post_content .= '<span>'.__('Starts(UTC)','vibe-bbb').' : '.date('Y-m-d H:i:s',$recording['startTime']/1000).'</span>';
                                $post_content .= '<span>'.__('Ends(UTC)','vibe-bbb').' : '.date('Y-m-d H:i:s',$recording['endTime']/1000).'</span>';
                                $post_content .= '</div>';

                                $post_content .= '<div class="meeting_recording_body">';
                                if(!empty($recording['playback']['format']['url'])){
                                    $post_content .= '<a class="link" href='.$recording['playback']['format']['url'].' target="_blank">'.__('View recording','vibe-bbb').'</a>';
                                }
                                $post_content .= '</div>';

                                $post_content .= '</div>';
                            }
                        }
                    }else{
                        $post_content .= '<div class="vbp_message">'.__('No recordings found!','vibe-bbb').'</div>';
                    }
                    $post_content .= '</div>';
                } 
                
            }

        }

        return $post_content;
    }


    function wplms_bbb_meeting_updated($post_id,$user_id){
        if(!empty($post_id)){
            $option = get_option('vibe_bbb_settings');
            if(!empty($option['vibe_bbb_enable_reminder'])){

                $start_time = get_post_meta($post_id,'start',true);
                if(!empty( $start_time )){
                    $start_timestamp = $start_time ;

                    $timestamp = $start_timestamp - (int)$option['vibe_bbb_reminder_time'] *1000; //ms
                    $args = array($post_id);

                    wp_clear_scheduled_hook('wplms_send_vibe_bbb_reminders_vibebp',$args);
                    $timestamp_in_sec = $timestamp/1000;
                    wp_schedule_single_event($timestamp_in_sec,'wplms_send_vibe_bbb_reminders_vibebp',$args);
                    
                }
            }
        }
    }

    function new_bp_core_email_register($user_id){

        $post_title =  __( 'BBB meeting reminder', 'vibe-bbb' );
        if ( ! function_exists( 'post_exists' ) ) {
            require_once( ABSPATH . 'wp-admin/includes/post.php' );
        }
        $post_exists = post_exists( $post_title );
    
        if ( $post_exists != 0 && get_post_status( $post_exists ) == 'publish' )
        return;
    
        // Create post object
        $my_post = array(
            'post_title'    => $post_title,
            'post_content'  => sprintf(__('Meeting %s is about to start in %s','vibe-bbb'),'{{meeting.name}}','{{{meeting.timeleft_html}}}'),  // HTML email content.
            'post_excerpt'  => sprintf(__('Meeting %s is about to start in %s','vibe-bbb'),'{{meeting.name}}','{{{meeting.timeleft}}}'),  // Plain text email content.
            'post_status'   => 'publish',
            'post_type' => bp_get_email_post_type(),
            'post_author' => $user_id
        );
    
        $post_id = wp_insert_post( $my_post );
    
        if ( $post_id ) {
            // add our email to the taxonomy term 'bbb_meeting_reminder'
            // Email is a custom post type, therefore use wp_set_object_terms
            $tt_ids = wp_set_object_terms( $post_id, 'bbb_meeting_reminder', bp_get_email_tax_type() );
            foreach ( $tt_ids as $tt_id ) {
                $term = get_term_by( 'term_taxonomy_id', (int) $tt_id, bp_get_email_tax_type() );
                wp_update_term( (int) $term->term_id, bp_get_email_tax_type(), array(
                    'description' => __( 'Vibe BBB meeting reminder', 'vibe-bbb' ),
                ) );
            }
        }
    }
    
    //send mail here to users //do_action('wplms_send_vibe_bbb_reminders_vibebp',array($post_id));
    function wplms_send_vibe_bbb_reminders_vibebp($args){ //array($post_id);
        
        $post_id = $args;

        if(!empty($post_id)){
            $option = get_option('vibe_bbb_settings');
            if(!empty($option['vibe_bbb_enable_reminder'])){
                $user_ids = $this->get_meeting_user_ids($post_id);
                if(is_array($user_ids) && !empty($user_ids)){
                    $title =  get_the_title( $post_id );
                    $timeleft =  $this->calculate_timeleft($post_id);
                    foreach ($user_ids as $key => $user_id) {
                        $bbb_component_url = bp_core_get_user_domain($user_id).'#component=bbb_meeting';
                        $timeleft_html = '<a href="'.$bbb_component_url.'" target="_blank">'.$timeleft.'</a>';
                        $args = array(
                            'tokens' => array(
                                'meeting.name' => $title,
                                'meeting.timeleft' => $timeleft,
                                'meeting.timeleft_html' => $timeleft_html,
                            ),
                        );

                        bp_send_email( 'bbb_meeting_reminder', (int) $user_id, $args );
                    }
                }
            }
        }
    }

    function calculate_timeleft($post_id){
        $timeleft  = '';
        $start_time = get_post_meta($post_id,'start',true);
        if(!empty( $start_time )){
            $date1 = $start_time/1000;  
            $date2 = strtotime("now"); 
            // Formulate the Difference between two dates 
            $diff = abs($date2 - $date1);  
            
            
            $years = floor($diff / (365*60*60*24)); 
            if(!empty($years)){
                $timeleft .= sprintf(__(' %d years,','vibe-bbb'),$years);
            }
            
            
            $months = floor(($diff - $years * 365*60*60*24) 
                                        / (30*60*60*24)); 
            if(!empty($months)){
                $timeleft .= sprintf(__(' %d months,','vibe-bbb'),$months);
            }
            
        
            $days = floor(($diff - $years * 365*60*60*24 -  
                        $months*30*60*60*24)/ (60*60*24)); 
            if(!empty($days)){
                $timeleft .= sprintf(__(' %d days,','vibe-bbb'),$days);
            }
            

            $hours = floor(($diff - $years * 365*60*60*24  
                - $months*30*60*60*24 - $days*60*60*24) 
                                            / (60*60));  
            if(!empty($hours)){
                $timeleft .= sprintf(__(' %d hours,','vibe-bbb'),$hours);
            }
            

            $minutes = floor(($diff - $years * 365*60*60*24  
                    - $months*30*60*60*24 - $days*60*60*24  
                                    - $hours*60*60)/ 60);  
            if(!empty($minutes)){
                $timeleft .= sprintf(__(' %d minutes,','vibe-bbb'),$minutes);
            }
            
            
            $seconds = floor(($diff - $years * 365*60*60*24  
                    - $months*30*60*60*24 - $days*60*60*24 
                            - $hours*60*60 - $minutes*60)); 
            if(!empty($seconds)){
                $timeleft .= sprintf(__(' %d seconds','vibe-bbb'),$seconds);
            }
        }
        return $timeleft;
    }

    function get_meeting_user_ids($id){
        $type = get_post_meta($id,'shared_type',true);
        $users = array();
        switch ($type) {
            case 'shared':
                    $users = get_post_meta($id,'shared_values');
                break;
            case 'course':
                    $courses = get_post_meta($id,'shared_values');
                    if(!empty($courses) && is_array($courses)){
                        global $wpdb;
                        foreach ($courses as $key => $course_id) {
                            $query = "SELECT user_id  FROM {$wpdb->usermeta} where `meta_key` LIKE 'course_status{$course_id}' AND `meta_value` = 2";
                            $ncourse_members = $wpdb->get_results($query,ARRAY_A);
                            if(!empty($ncourse_members) && is_array($ncourse_members)){
                                foreach ($ncourse_members as $ncourse_member) {
                                    if(!in_array($ncourse_member['user_id'],$users)){
                                        $users[] = $ncourse_member['user_id'];
                                    }
                                }
                            }
                        }
                    }
                break;
            case 'group':
                    $groups = get_post_meta($id,'shared_values');
                    if(!empty($groups) && is_array($groups)){
                        foreach ($groups as $key => $group_id) {
                            $args = array( 
                                'group_id' => $group_id,
                                'per_page' => -1,
                                'exclude_admins_mods' => false
                            );
                            $group_members_result = groups_get_group_members( $args );
                            foreach(  $group_members_result['members'] as $member ) {
                                if(!in_array($member->ID,$users)){
                                    $users[] = $member->ID;
                                }
                            }
                        }
                    }
                break;    
            default:
                break;
        }
        return apply_filters('vibe_bbb_get_meeting_user_ids',$users,$id);
    }
}

Vibe_Bbb_Init::init();