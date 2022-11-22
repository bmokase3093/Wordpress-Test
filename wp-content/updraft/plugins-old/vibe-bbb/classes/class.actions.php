<?php
/**
 * Initialise WPLMS Bbb
 *
 * @class       Wplms_Bbb_Actions
 * @author      VibeThemes
 * @category    Admin
 * @package     WPLMS-Bbb/classes
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class Wplms_Bbb_Actions{

	public static $instance;
	
	public static function init(){
        if ( is_null( self::$instance ) )
            self::$instance = new Wplms_Bbb_Actions();
        return self::$instance;
    }

	private function __construct(){
        if(function_exists('bp_activity_add')){
            add_action('wplms_bbb_meeting_connected',array($this,'wplms_bbb_meeting_connected'),10,4);
            add_action('wplms_bbb_record_join_activity',array($this,'wplms_bbb_record_join_activity'),10,2);
            add_action('wplms_bbb_meeting_created',array($this,'wplms_bbb_meeting_created'),10,2);
        }
       
    }
    
    function wplms_bbb_meeting_connected($meeting_id,$user_id,$shared_type,$shared_values){
        $type = 'vibe_bbb_connected_'.$shared_type;
        switch ($shared_type) {
            case 'course':
                    foreach ($shared_values as $key => $shared_value) {
                        bp_activity_add( 
                            apply_filters('bp_course_record_activity',array( 
                                    'user_id' => $user_id, 
                                    'action' => __('Course connected with BBB meeting','vibe-bbb'),
                                    'content' => sprintf(__('Course %s is connected with BBB meeting %s','vibe-bbb'),get_the_title($shared_value),get_the_title($meeting_id)),
                                    'primary_link' => get_permalink($meeting_id), 
                                    'component' => $this->get_component_name($meeting_id,$user_id),
                                    'type' => $type, 
                                    'item_id' => $shared_value, 
                                    'secondary_item_id' => $meeting_id,
                                ) 
                            )
                        );
                    }
                break;
            case 'shared':
                break;
            case 'group':
                break;
            default:
                break;
        }
        
    }

    function wplms_bbb_record_join_activity($meeting_id,$user_id){
        $type = 'vibe_bbb_join';
        bp_activity_add( 
            apply_filters('bp_vibe_bbb_join_record_activity',array( 
                    'user_id' => $user_id, 
                    'action' => __('User joined the BBB meeting','vibe-bbb'),
                    'content' => sprintf(__('User %s joined the BBB meeting %s','vibe-bbb'),bp_core_get_userlink($user_id),get_the_title($meeting_id)),
                    'primary_link' => get_permalink($meeting_id), 
                    'component' =>  $this->get_component_name($meeting_id,$user_id),
                    'type' => $type, 
                    'item_id' => $meeting_id, 
                    'secondary_item_id' => $user_id,
                ) 
            )
        );
    }

    function wplms_bbb_meeting_created($meeting_id,$user_id){
        $type = 'vibe_bbb_created';
        bp_activity_add( 
            apply_filters('bp_vibe_bbb_created_record_activity',array( 
                    'user_id' => $user_id, 
                    'action' => __('User created the BBB meeting','vibe-bbb'),
                    'content' => sprintf(__('User %s created the BBB meeting %s','vibe-bbb'),bp_core_get_userlink($user_id),get_the_title($meeting_id)),
                    'primary_link' => get_permalink($meeting_id), 
                    'component' => $this->get_component_name($meeting_id,$user_id),
                    'type' => $type, 
                    'item_id' => $meeting_id, 
                    'secondary_item_id' => $user_id,
                ) 
            )
        );
    }


    function get_component_name($meeting_id,$user_id){
        $component = 'vibe_bbb';
        $shared_type = get_post_meta($meeting_id,'shared_type',true);
        if(!empty($shared_type)){
            switch ($shared_type) {
                case 'course':
                        $component = 'course';
                    break;
                case 'group':
                        $component = 'group';
                    break; 
                case 'shared':
                        $component = 'shared';
                    break;
            }
        }
        return $component;
    }
	
}

add_action('bp_include',function(){
    Wplms_Bbb_Actions::init();
});

