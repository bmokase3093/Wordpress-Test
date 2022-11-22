<?php

if (!defined('ABSPATH')) { exit; }

class WPLMS_Assignments_Actionss{

    public static $instance;
    
    public static function init(){

        if ( is_null( self::$instance ) )
            self::$instance = new WPLMS_Assignments_Actionss();

        return self::$instance;
    }

    public function __construct(){ 
        
        //handle course reset assignments reset and course retake assignment reset
        add_action('wplms_course_reset',array($this,'course_assignments_reset'),10,2);
        add_action('wplms_course_retake',array($this,'course_assignments_reset'),10,2); 
        
    }
    function course_assignments_reset($course_id,$user_id){
        if(!function_exists('bp_course_get_curriculum_units'))
            return;
        $curriculum_units = bp_course_get_curriculum_units($course_id);
        if(empty($curriculum_units))
            return;
        global $wpdb;
        foreach ($curriculum_units as $key => $unit_id) {
            if(get_post_type($unit_id) == 'unit'){
                $assignments = get_post_meta($unit_id,'vibe_assignment',true);
                if(!empty($assignments)){
                    foreach ($assignments as $k => $assignment_id) {
                        
                        delete_user_meta($user_id,$assignment_id);
                        delete_post_meta($assignment_id,$user_id);
                        $wpdb->query($wpdb->prepare("UPDATE $wpdb->comments SET comment_approved='trash' WHERE comment_post_ID=%d AND user_id=%d",$assignment_id,$user_id));
                    }
                }
            }elseif(get_post_type($unit_id) == 'wplms-assignment'){
                delete_user_meta($user_id,$unit_id);
                delete_post_meta($unit_id,$user_id);
                $wpdb->query($wpdb->prepare("UPDATE $wpdb->comments SET comment_approved='trash' WHERE comment_post_ID=%d AND user_id=%d",$unit_id,$user_id));
            }
        }
    }
}

WPLMS_Assignments_Actionss::init();