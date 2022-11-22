<?php
/**
 * SETTINGS
 *
 * @class       Vibe_Helpdesk_Settings
 * @author      VibeThemes
 * @category    Admin
 * @package     vibe-helpdesk
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}



class Vibe_HelpDesk_Settings{


	public static $instance;
	public static function init(){

        if ( is_null( self::$instance ) )
            self::$instance = new Vibe_HelpDesk_Settings();
        return self::$instance;
    }

	private function __construct(){
		
		add_filter('vibebp_settings_tabs',array($this,'add_tab'));
		add_filter('vibebp_settings_type',array($this,'labels_type'),10,2);
		
	}

	function add_tab($tabs){
		if(function_exists('bbpress')){
			$tabs['helpdesk']=__('Helpdesk','vibe-helpdesk');
		}

		return $tabs;
	}

	function settings(){


		echo '<h3>'.__('Helpdesk General Settings','vibe-helpdesk').'</h3>';

		$template_array = apply_filters('vibebp_bbpress_general_settings_tabs',array(
			'helpdesk'=> __('General Settings','vibe-helpdesk'),
			'agents'=> __('Agents','vibe-helpdesk'),
			'forums'=> __('Forums','vibe-helpdesk'),
		));
		echo '<ul class="subsubsub">';

		foreach($template_array as $k=>$value){
			if(empty($_GET['sub']) && empty($current)){
				$current = $k;
			}else if(!empty($_GET['sub']) && empty($current)){
				$current = $_GET['sub'];
			}
			echo '<li><a href="?page='.VIBE_BP_SETTINGS.'&tab=helpdesk&sub='.$k.'" '.(($k == $current)?'class="current"':'').'>'.$value.'</a>  &#124; </li>';
		}
		echo '</ul><div class="clear"><hr/>';

		$vibebp_settings = VibeBP_Settings::init();

		
		$settings = $this->get_selected_tab_settings_array($_GET['sub']);
		if(isset($_GET['sub'])){
			$vibebp_settings->vibebp_settings_generate_form('helpdesk',$settings,$_GET['sub']);
		}else{
			$vibebp_settings->vibebp_settings_generate_form('helpdesk',$settings);
		}
		
	}

	function get_selected_tab_settings_array($tab){
		$review_options = array(
			''=>__('Select User Role','vibe-helpdesk'),
			'all'=>__('All','vibe-helpdesk')
		);
		global $wp_roles;
		$roles = array_keys($wp_roles->roles);
		if(is_array($roles)){
			foreach($roles as $role){
				$review_options[$role]=$wp_roles->roles[$role]['name'];
			}
		}
		
		$settings = array();
		switch($tab){

			case 'agents':
				$settings = apply_filters('vibebp_helpdesk_agent_settings',array(
					array(
						'label'=>__('Agents','vibe-helpdesk'),
						'type'=> 'heading',
					),
					array(
						'label' => __('Agent User Role','vibe-helpdesk'),
						'name' => 'bbp_agents',
						'type' => 'select',
						'options'=>$review_options,
						'desc' => __('User role who are classified as agents','vibe-helpdesk'),
						'default'=>''
					),
					array(
						'label' => __('Supervisor Ticket User Role','vibe-helpdesk'),
						'name' => 'bbp_supervisor',
						'type' => 'select',
						'options'=>$review_options,
						'desc' => __('User role who are can assign tickets to agents','vibe-helpdesk'),
						'default'=>''
					),
					array(
						'label' => __('Automatic Assignment','vibe-helpdesk'),
						'name' => 'bbp_automatic_topic_assign',
						'type' => 'checkbox',
						'desc' => __('Automatically assign topics to agents, maintaining similar work load.','vibe-helpdesk'),
						'default'=>''
					),
					array(
						'label' => __('Enable Response timer','vibe-helpdesk'),
						'name' => 'bbp_response_timer',
						'type' => 'checkbox',
						'desc' => __('Timer to record time spent in resolution. Excludes user response times.','vibe-helpdesk'),
						'default'=>1
					),
					array(
						'label' => __('Show Kudo count','vibe-helpdesk'),
						'name' => 'bbp_show_kudo_count',
						'type' => 'checkbox',
						'desc' => __('Kudos is awarded by customers on good or helpful support.','vibe-helpdesk'),
						'default'=>1
					),
					array(
						'label' => __('Agent Labels','vibe-helpdesk'),
						'name' => 'bbp_agent_labels',
						'cpt'  => 'agents1',
						'type' => 'labels',
						'desc' => __('Assign labels from WP admin - Users','vibe-helpdesk'),
						'default'=>1
					)
				));
			break;
			case 'forums':
				$settings = apply_filters('vibebp_helpdesk_forums_settings',array(
					array(
						'label'=>__('Labels','vibebp' ),
						'type'=> 'heading',
					),
					array(
						'label' => __('Select Topic Labels','vibe-helpdesk'),
						'name' => 'bbp_labels',
						'type' => 'labels',
						'cpt'  => 'forum',
						'desc' => '',
						'default'=>150
					),
					array(
						'label'=>__('SLA','vibebp' ),
						'type'=> 'heading',
					),
					array(
						'label' => __('Select SLA','vibe-helpdesk'),
						'name' => 'bbp_sla',
						'type' => 'checkbox',
						'desc' => '',
						'default'=>150
					),
					array(
						'label' => __('SLA forums hours','vibe-helpdesk'),
						'name' => 'bbp_sla_forum_hours',
						'type' => 'number',
						'desc' => __('SLA forums in hours','vibe-helpdesk'),
						'default'=>0
					)
				));
			break;
			default:
				$settings = apply_filters('vibebp_helpdesk_general_settings',array(
					array(
						'label'=>__('General Settings','vibebp' ),
						'type'=> 'heading',
					),
					array(
						'label' => __('Private BBPress','vibe-helpdesk'),
						'name' => 'bbp_private',
						'type' => 'checkbox',
						'value' => 1,
						'desc' => __('Forums are not publically accessible.','vibe-helpdesk'),
					),
					array(
						'label' => __('Restrict Forum browsing','vibe-helpdesk'),
						'name' => 'bbp_forum_browsing',
						'type' => 'checkbox',
						'value' => 1,
						'desc' => __('Users can not browse other user topics.','vibe-helpdesk'),
					),
					array(
						'label' => __('Enable Mark Reply as Solution','vibe-helpdesk'),
						'name' => 'bbp_solutions',
						'type' => 'checkbox',
						'value' => 1,
						'desc' => __('Solutions close topics/tickets.','vibe-helpdesk'),
					),
					array(
						'label' => __('Enable Canned Responses','vibe-helpdesk'),
						'name' => 'bbp_canned_responses',
						'type' => 'checkbox',
						'value' => 1,
						'desc' => __('Solutions close topics/tickets.','vibe-helpdesk'),
					),
					array(
						'label' => __('Enable Kudos on responses','vibe-helpdesk'),
						'name' => 'bbp_kudos',
						'type' => 'checkbox',
						'value' => 1,
						'desc' => '',
					),
				));
			break;
		}
		return $settings;
	}

				
	function labels_type($return,$setting){

		if($setting['type'] != 'labels')
			return $return;

		echo '<th scope="row" class="titledesc"><label>'.$setting['label'].'</label></th>';
		echo '<td class="forminp"><a class="button-primary repeatable_label" data-type="label" 
			data-cpt="'.$setting['cpt'].'" 
			data-field="'.$setting['name'].'">+</a>
			<ul id="'.$setting['name'].'"></ul>
			<input type="hidden" class="vibebp_settings_labels" name="'.$setting['name'].'" 
			value=""/><span>'.$setting['desc'].'</span></td><script>
			var '.$setting['name'].'='.json_encode($setting['value']).';
			</script>';
			
		wp_enqueue_script('vibe_helpdesk_settings',plugins_url('../assets/js/main.js',__FILE__),array('jquery'),VIBEHELPDESK_VERSION,true);
		return 1;
	}

}
Vibe_HelpDesk_Settings::init();

function helpdesk(){
	$settings = Vibe_HelpDesk_Settings::init();
	$settings->settings();
}