<?php

if (!defined('ABSPATH')) { exit; }

class WPLMS_Assignments_Filterss{

    public static $instance;
    
    public static function init(){

        if ( is_null( self::$instance ) )
            self::$instance = new WPLMS_Assignments_Filterss();

        return self::$instance;
    }

    public function __construct(){ 
        
        
        add_filter('wplms_finish_course_check',array($this,'incourse_assignment_check'),9999,3);
        add_filter('bp_course_check_quiz_complete',array($this,'bp_course_check_quiz_complete'),10,4);
        add_filter('bp_course_get_user_unit_completion_time',array($this,'bp_course_get_user_unit_completion_time'),10,4);
        //add_filter('wplms_plugin_get_course_unfinished_unit',array($this,'check_assignment'),10,2);
        //add_filter('wplms_plugin_get_course_unfinished_unit_key',array($this,'check_assignment_key'),10,3);
    }

    function bp_course_get_user_unit_completion_time($time,$unit_id,$user_id,$course_id){
        if(get_post_type($unit_id) == 'wplms-assignment'){
            $marks = get_post_meta($unit_id,$user_id,true);
            $answers=get_comments(array(
                'post_id' => $unit_id,
                'status' => 'approve',
                'number' => 1,
                'user_id' => $user_id
            ));
            if(isset($answers) && is_array($answers) && count($answers) && !empty($marks)){
                $time = get_user_meta($user_id,$unit_id,true);
            }else{
                $time =0;
            }
        }
        return $time;
    }

    
    function bp_course_check_quiz_complete($bool,$assignment_id,$user_id,$course_id){
        if(get_post_type($assignment_id) == 'wplms-assignment'){
            $marks = get_post_meta($assignment_id,$user_id,true);
            $answers=get_comments(array(
                'post_id' => $assignment_id,
                'status' => 'approve',
                'number' => 1,
                'user_id' => $user_id
            ));
            if(isset($answers) && is_array($answers) && count($answers) && !empty($marks)){
                $bool =true;
            }else{
                $bool =false;
            }
        }
        return $bool;
    }

    function incourse_assignment_check($flag,$course_curriculum,$user_id=null){
        if(empty($user_id)){
            $user_id = get_current_user_id();
        }
        if(!empty($flag) && get_post_type($flag) == 'wplms-assignment'){
            
            $marks = get_post_meta($flag,$user_id,true);
            if(!empty($marks))
                return 0;
        }
        return $flag;
    } 
}

WPLMS_Assignments_Filterss::init();