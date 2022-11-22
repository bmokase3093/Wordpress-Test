<?php
/**
 * Initialization functions for WPLMS
 *
 * @author      VibeThemes
 * @category    Admin
 * @package     Initialization
 * @version     2.0
 */


if ( ! defined( 'ABSPATH' ) ) exit;


class WPLMS_Plugin_Init{

    public static $instance;
    
    public static function init(){

        if ( is_null( self::$instance ) )
            self::$instance = new WPLMS_Plugin_Init();

        return self::$instance;
    }

    private function __construct(){
        //add_theme_support( 'buddypress' );
        add_theme_support( 'bp-default-responsive' );


        
        add_post_type_support( 'course', 'buddypress-activity' );
        add_post_type_support( 'unit', 'buddypress-activity' );
        add_post_type_support( 'quiz', 'buddypress-activity' );
        add_post_type_support( 'question', 'buddypress-activity' );
        add_post_type_support( 'wplms-event', 'buddypress-activity' );
        add_post_type_support( 'wplms-assignment', 'buddypress-activity' );
        add_post_type_support( 'news', 'buddypress-activity' );
        add_filter('bp_after_has_activities_parse_args',array($this,'course_activities'));
        add_action( 'bp_init', array($this,'activity_filter_options') );
        add_filter( 'bp_activity_set_course_scope_args', array($this,'filter_activity_scope'), 10, 2 );
        add_filter('bp_activity_custom_update',array($this,'bp_activity_course_update'),10,3);
    }

    function bp_activity_course_update($object,$item_id,$content){
        global $bp;

        $activity_id =  bp_course_record_activity(array(
                'action' => sprintf(__('%s posted an update in the course %s','wplms'),bp_core_get_userlink($bp->loggedin_user->id),'<a href="'.get_permalink($item_id).'">'.get_the_title($item_id).'</a>'),
                'content' => $content,
                'primary_link' => get_permalink($item_id),
                'item_id' => $item_id,
                'type' => $object
            ));
        return $activity_id;
    }

    function course_activities($args){
        if(is_singular('course')){
            $args['object'] = 'course';
            $args['primary_id'] = get_the_ID();
        }
        if(strpos($args['scope'],'course') !== false){ 
            if(strpos($args['scope'],'personal') !== false){
                preg_match("/course_personal_([0-9]+)/", $args['scope'],$matches); 
                $args['scope'] = 'personal';
                $args['object'] = 'course';
                $args['user_id'] = get_current_user_id();
                $args['primary_id'] = $matches[1];
            }else{
                preg_match("/course_([0-9]+)/", $args['scope'],$matches); 
                $args['scope'] = '';
                $args['object'] = 'course';
                $args['primary_id'] = $matches[1];
            }
        }

        return $args;
    }

    function filter_activity_scope( $retval = array(), $filter = array() ) {
        global $post;
        // Determine the user_id
        if ( ! empty( $filter['user_id'] ) ) {
            $user_id = $filter['user_id'];
        } else {
            $user_id = bp_displayed_user_id()
                ? bp_displayed_user_id()
                : bp_loggedin_user_id();
        }

        // Determine groups of user
        $groups = groups_get_user_groups( $user_id );
        if ( empty( $groups['groups'] ) ) {
            $groups = array( 'groups' => 0 );
        }

        // Should we show all items regardless of sitewide visibility?
        $show_hidden = array();
        if ( ! empty( $user_id ) && ( $user_id !== bp_loggedin_user_id() ) ) {
            $show_hidden = array(
                'column' => 'hide_sitewide',
                'value'  => 0
            );
        }

        $retval = array(
            'relation' => 'AND',
            array(
                'relation' => 'AND',
                array(
                    'column' => 'component',
                    'value'  => 'course'
                ), 
                array(
                    'column'  => 'item_id',
                    'compare' => 'IN',
                    'value'   => $post->ID
                ),
            ),
            $show_hidden,

            // overrides
            'override' => array(
                'filter'      => array( 'user_id' => 0 ),
                'show_hidden' => true
            ),
        );

        return $retval;
    }

    function activity_filter_options() {
        
        $dropdowns = apply_filters( 'vibe_projects_activity_filter_locations', array(
            'bp_activity_filter_options',
            'bp_member_activity_filter_options',
            'bp_course_activity_filter_options',
        ) );
        foreach( $dropdowns as $hook ) {
            add_action( $hook, array($this,'activity_filters' ));
        }
    }
    

}