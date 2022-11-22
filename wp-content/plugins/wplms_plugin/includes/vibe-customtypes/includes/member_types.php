<?php

 if ( ! defined( 'ABSPATH' ) ) exit;

//Only registration froms compatibility is there, rest moved to VibeBP
 class Wplms_Member_types{

 	protected $option = 'wplms_member_types';
	public static $instance;
    public static function init(){
        if ( is_null( self::$instance ) )
            self::$instance = new Wplms_Member_types();
        return self::$instance;
    }

    public function __construct(){
    	add_action('wplms_registration_form_setting',array($this,'show_member_type_field'));
    	
    }

    function get_member_types(){

        if(empty($this->member_types))
            $this->member_types = get_option($this->option);

        return $this->member_types;
    }

    function show_member_type_field($name){
        $member_types = get_option($this->option);
        $forms = get_option('wplms_registration_forms');
        if(!empty($member_types)){
            echo '<li><label class="field_name">'.__('Assign member type','wplms').'</label><select name="member_type|'.$name.'"><option value="">'._x('None','member types','wplms').'</option><option value="enable_user_member_types_select" '.((isset($forms[$name]['settings']['member_type']) && $forms[$name]['settings']['member_type'] == 'enable_user_member_types_select')?'selected':'').'>'._x('Enable User to select one','member types','wplms').'</option>';
            foreach($member_types as  $key => $member_type){
                echo '<option value="'.$member_type['id'].'" '.((isset($forms[$name]['settings']['member_type']) && $forms[$name]['settings']['member_type'] == $member_type['id'])?'selected':'').'>'.$member_type['sname'].'</option>';
            }
            echo '</select></li>';
        }
    }
}	
