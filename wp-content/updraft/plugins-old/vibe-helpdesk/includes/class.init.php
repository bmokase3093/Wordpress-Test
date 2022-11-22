<?php
/**
 * PRofile
 *
 * @class       Vibe_Projects_Profile
 * @author      VibeThemes
 * @category    Admin
 * @package     vibe-helpdesk
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}



class Vibe_HelpDesk_Init{


	public static $instance;
	public static function init(){

        if ( is_null( self::$instance ) )
            self::$instance = new Vibe_HelpDesk_Init();
        return self::$instance;
    }

	private function __construct(){
		add_action( 'bp_setup_nav', array($this,'add_projects_tab'), 100 );
        add_action('wp_enqueue_scripts',array($this,'enqueue_script'));
        add_filter('VibeBbp_create_reply_api',array($this,'count_SLA_API'),10,3);  // when reply created successfully API sla update
        add_filter('manage_users_columns', array($this,'vibe_add_bbp_agent_labels_column'));
        add_action('manage_users_custom_column',  array($this,'vibe_show_user_id_column_content'), 10, 3);
		add_action('wp_ajax_assign_agent_label',array($this,'assign_agent_label'));
		add_action( 'init', array($this,'register_custom_post_type'));

		
		
		if(class_exists('BBP_Private_Replies')){ /* private reply plugin */
            if (apply_filters('vibe_helpdesk_override_private_replies',true)) {
				include_once 'bbp-private-replies-4x.php'; //overriding 'bbp_get_reply_content' filter
            }
			add_filter('vibehelpdesk_script_args',array($this,'vibehelpdesk_script_args'));
			add_filter('vibeBbp_helpdesk_reply',array($this,'vibeBbp_helpdesk_reply'));
			add_action('vibeBbp_helpdask_new_reply',array($this,'vibeBbp_helpdask_new_reply'),10,2);
		}
	}

	function add_projects_tab(){
		
	}

	function show_screen(){

	}
	
	function vibebp_save_settings($post,$tab){
		if(class_exists('Vibe_HelpDesk_Settings')){
			$settings = Vibe_HelpDesk_Settings::get_selected_tab_settings_array($tab );
			foreach($post as $kk => $value){
				foreach($settings as $kkk => $setting){
					if($setting['type']=='labels' && $kk == $setting['name']){
						$post[$kk] = urldecode(stripslashes($value));
					}
				}	
			}
		}
		return $post;
	}
					
	function enqueue_script(){

		$blog_id = '';
        if(function_exists('get_current_blog_id')){
            $blog_id = get_current_blog_id();
        }

            
		$helpdesk=apply_filters('vibehelpdesk_script_args',array(
			'api'=>array(
                'url'=>get_rest_url($blog_id,VIBE_HELPDESK_API_NAMESPACE),
            ),
			'label'=>__('HelpDesk','vibe-helpdesk'),
			'settings'=>array(
				'agents_cap'=>function_exists('vibebp_get_setting')?vibebp_get_setting('bbp_agents','helpdesk'):'',
				'supervisor_cap'=>function_exists('vibebp_get_setting')?vibebp_get_setting('bbp_supervisor','helpdesk'):'',
				'canned_responses'=>function_exists('vibebp_get_setting')?vibebp_get_setting('bbp_canned_responses','helpdesk'):'',
				'assigned_tickets'=>array(
					'id'=>'assigned',
					'slug'=>'assigned',
					'parent_slug'=>'forums',
				),
				'assign_tickets'=>array(
					'id'=>'assign',
					'slug'=>'assign',
					'parent_slug'=>'forums',
				)
			),
            'sorters'=>array(
                'recent'=>_x('Recent','login','vibe-helpdesk'),
                'alphabetical'=>_x('Alphabetical','login','vibe-helpdesk'),
                'popular'=>_x('Most Shared','login','vibe-helpdesk')
            ),
            'translations'=>array(
				'assigned_tickets'=>__('Assigned Tickets', 'vibe-helpdesk'),
				'unassigned_tickets'=>__('Unassigned Tickets', 'vibe-helpdesk'),
				'recently_assigned_tickets'=>__('Recently Assigned Tickets', 'vibe-helpdesk'),
				'assign_tickets' =>__('Assign Tickets', 'vibe-helpdesk'),
            	'all_forums'=>__('All Forums', 'vibe-helpdesk'),
                'new_topic'=>__('New Topic', 'vibe-helpdesk'),
                'no_topics_found'=>__('No Topics found !', 'vibe-helpdesk'),
                'no_forums_found'=>__('No Forums found !', 'vibe-helpdesk'),
				'no_replies_found'=>__('No replies found !', 'vibe-helpdesk'),
				'subscribe'=>__('Subscribe', 'vibe-helpdesk'),
				'subscribed'=>__('Subscribed', 'vibe-helpdesk'),
				'assign_label'=>__('Assign Label', 'vibe-helpdesk'),
				'labels' =>__('Labels', 'vibe-helpdesk'),
				'submit' =>__('Submit', 'vibe-helpdesk'),
				'save_as_canned_response' =>__('Save As Canned Response', 'vibe-helpdesk'),
				'enter_canned_title' => __('Enter Canned Title', 'vibe-helpdesk'),
				'enable_canned' => __('Enable canned', 'vibe-helpdesk'),
				'search' => __('Search Canned Responses', 'vibe-helpdesk'),
				'cancel' => __('Cancel', 'vibe-helpdesk'),
				'topic_title'=> __('Topic title', 'vibe-helpdesk'),
				'select_forum'=> __('Select forum', 'vibe-helpdesk'),
				'topic_content'=> __('Topic content', 'vibe-helpdesk'),
				'search_topics'=>__('Search topics', 'vibe-helpdesk'),
				'search_replies'=>__('Search replies', 'vibe-helpdesk'),
				'more'=>__('Load More', 'vibe-helpdesk'),
				'make_reply_private'=>__('Set as private reply', 'vibe-helpdesk'),
				'back_to_all_forums'=>__('Back to forums', 'vibe-helpdesk'),
				'back_to_all_topics'=>__('Back to topics', 'vibe-helpdesk'),
            )
        ));
        if(function_exists('bp_is_user') && bp_is_user() || apply_filters('vibebp_enqueue_profile_script',false)){
            
            wp_enqueue_script('helpdesk',plugins_url('../assets/js/helpdesk.js',__FILE__),array('wp-element','wp-data'),VIBEHELPDESK_VERSION);
            wp_localize_script('helpdesk','helpdesk',$helpdesk);
            wp_enqueue_style('helpdesk',plugins_url('../assets/css/helpdesk.css',__FILE__),array(),VIBEHELPDESK_VERSION);
        }
    }
    
    /** 
		* @param the topic and user id(current_replier)
		* @return $arr = array(
		*		'sla' => sla_count in secound,
		*		'is_replied' => is replied by executive
		*	);
	**/
	function count_sla_topic($topic_id){
		$usertime = 0;
		$agenttime = 0;
		$sla = 0;
		$is_replied = true;
		$arr = array(
			'sla' => $sla,
			'is_replied' => $is_replied
		);
		global $wpdb;
		$replies = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT ID,post_author,post_date
				FROM $wpdb->posts
				WHERE `post_parent` = %d
				AND `post_type` LIKE '".bbp_get_reply_post_type()."'
				AND `post_status` LIKE 'publish'
				",
				$topic_id
			)
		);
		if(!empty($replies) && is_array($replies)){
			$flagU = 1;
			$flagA = 0;
			$topic_author_id = get_post($topic_id)->post_author; //topic creator_id
			foreach ($replies as $key => $value) {
				$timestamp = strtotime($replies[$key]->post_date);
				// user
				if(($topic_author_id == $replies[$key]->post_author) && !empty($flagU)){
					$usertime  = $timestamp;
					$flagU = 0;
					$flagA = 1;
					$is_replied = false;
				}
				// agent id compare
				if(($topic_author_id != $replies[$key]->post_author) && !empty($flagA)){
					$agenttime  = $timestamp;
					if(!empty($agenttime) && !empty($usertime)){
						$sla += ($agenttime-$usertime);
						$flagU = 1;
						$flagA = 0;
						$is_replied = true;
					}
				}
			}
		}
		$sla_remaining = 0;
		$vibebp_settings=get_option(VIBE_BP_SETTINGS);
		if(!empty($vibebp_settings['forums']['bbp_sla'])=='on' && !empty($vibebp_settings['forums']['bbp_sla_forum_hours'])){
			$sla_remaining = ($vibebp_settings['forums']['bbp_sla_forum_hours'])*3600 - $sla;
		}	
		
		$arr = array(
			'sla' => $sla,
			'remaining' => $sla_remaining,
			'is_replied' => $is_replied
		);
		return apply_filters('count_sla_topic',$arr,$topic_id);
	}
    
    function count_SLA_API($data,$args){
		$vibebp_settings=get_option(VIBE_BP_SETTINGS);
		if(!empty($vibebp_settings['forums'] && $vibebp_settings['forums']['bbp_sla']=='on')){
			$topic_id = $args['topic_id'];
			if(!empty($topic_id) && !empty($data['status'])){
				$sla_obj = $this->count_sla_topic($topic_id);
				update_post_meta($topic_id,'sla',$sla_obj);		
			}
		}
		return $data;
	}

	function vibe_add_bbp_agent_labels_column($columns) {
        $columns['bbp_agent_labels'] = __('Agent Labels','vibe');
        wp_enqueue_script('assign_user_label',plugins_url('../assets/js/assign-label.js',__FILE__),array('jquery'),VIBEBP_VERSION,true);
        return $columns;
    }

    function vibe_show_user_id_column_content($value, $column_name, $user_id) {
        $htm = ' ';
        if ( 'bbp_agent_labels' == $column_name ){
			$vibebp_settings=get_option(VIBE_BP_SETTINGS);
			if(!empty($vibebp_settings) && !empty($vibebp_settings['helpdesk']['agents']['bbp_agent_labels'])){
				$labels = $vibebp_settings['helpdesk']['agents']['bbp_agent_labels'];
			}
            $flag = 0;
            if(!empty($labels)){
                $labels = $array = json_decode(json_encode($labels), true);
                if(is_array($labels)){
                   $flag = 1; 
                }
            }
			$selected_label = get_user_meta($user_id,'agent_label',true);
        	$htm='<select class="'.$column_name.'" data-user_id="'.$user_id.'" data-security="'.wp_create_nonce( 'assign_agent_label').'">';
            $htm.='<option  value="none">'.__('None','vibe').'</option>';
            if(!empty($flag)){
                foreach ($labels as $key => $value) {
                    if(!empty($value['label'])){
                        if(!empty($selected_label) && $value['label'] == $selected_label){
                            $htm.='<option selected value="'.$value['label'].'">'.$value['label'].'</option>';
                        }else{
                            $htm.='<option value="'.$value['label'].'">'.$value['label'].'</option>';
                        }	
                    }
                }  
            }
            $htm.='<option  value="none">'.__('None','vibe').'</option>';
			$htm.='</select>';	
        }
        return apply_filters('vibe_show_user_id_column_content',$htm,$value,$column_name,$user_id);
	}
	
	function assign_agent_label(){
		$label = $_POST['label'];
        $user_id = $_POST['user_id'];
        if(is_user_logged_in() && wp_verify_nonce(  $_POST['security'], 'assign_agent_label')){
            if(!empty($user_id) && !empty($label)){
                if($label == 'none'){
                    delete_user_meta($user_id,'agent_label');
                }else{
                    update_user_meta($user_id,'agent_label',$label);
                }
                echo __('Saved','vibe');
                do_action('vibe_assign_agent_label');
            }else{
                echo __('Not Saved','vibe');
            }
        }else{
            echo __('Security Failed','vibe');
        }
		die();
	}

	function register_custom_post_type(){
		register_post_type( VIBEHELPDESK_CANNED_POST_TYPE ,apply_filters('vibe_canned_response_register_post_type',
				array(
					'labels' => array(
						'name' => __('Canned Response','vibe-helpdesk'),
						'menu_name' => __('Canned Response','vibe-helpdesk'),
						'singular_name' => __('Canned Response','vibe-helpdesk'),
						'add_new_item' => __('Add New Canned Response','vibe-helpdesk'),
						'all_items' => __('Canned Responses','vibe-helpdesk')
					),
					'public' => true,
					'publicly_queryable' => true,
					'show_ui' => true,
					'capability_type' => 'post',
					'show_in_admin_bar' => true,
					'show_in_nav_menus' => true,
					'taxonomies' => array( 'canned-cat'),
					'show_in_menu' => defined('VIBEBP_VERSION')?'vibebp':'',
					'supports' => array( 'title','editor','thumbnail','author','comments','excerpt','revisions','custom-fields', 'page-attributes'),
					'hierarchical' => true,
					'rewrite' => array('slug' => 'canned-response'),
				)
			)
		);
	}

	function vibeBbp_helpdesk_reply($reply){
		if(!empty($reply['id'])){
			if(!empty($GLOBALS['bbp_private_replies'])){
				$reply['is_private'] = $GLOBALS['bbp_private_replies']->is_private($reply['id']);
			}
		}
		return $reply;
	}

	function vibehelpdesk_script_args($args){
		$args['settings']['private_plugin_active'] = true;
		return $args;
	}

	function vibeBbp_helpdask_new_reply($reply_id,$args){
		if(!empty($args['is_private'])){
			$_POST['bbp_private_reply']  = true;
			do_action( 'bbp_new_reply', $reply_id);
		}
	}



}
Vibe_HelpDesk_Init::init();