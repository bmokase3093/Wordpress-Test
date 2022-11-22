<?php

 if ( ! defined( 'ABSPATH' ) ) exit;
//Only registration froms compatibility is there, rest moved to VibeBP
class Wplms_group_types{

 	protected $option = 'wplms_group_types';
	public static $instance;
    public static function init(){
        if ( is_null( self::$instance ) )
            self::$instance = new Wplms_group_types();
        return self::$instance;
    }

    public function __construct(){
        add_action( 'bp_groups_register_group_types',array($this, 'wplms_register_group_types_with_directory' ));
    	
    }

    
   function wplms_register_group_types_with_directory() {
        $group_types = get_option($this->option);
        if(!empty($group_types)){
            foreach($group_types as $key => $group_type){
                if(!empty($group_type)){
                     bp_groups_register_group_type( $group_type['id'], array(
                        'labels' => array(
                            'name'          => $group_type['pname'],
                            'singular_name' => $group_type['sname'],
                        ),
                        'has_directory' => $group_type['id'],
                        // New parameters as of BP 2.7.
                        'show_in_create_screen' => apply_filters('wplms_group_type_show_in_create_screen_param',true),
                        'show_in_list' => apply_filters('wplms_group_type_show_in_list_param',true),
                        'description' => $group_type['pname'],
                        'create_screen_checked' => apply_filters('wplms_group_type_create_screen_checked_param',false)

                    ));
                }
                
            }
        }
    }   
}	
