<?php
/**
 * Register blocks.
 *
 * @package VibeBP
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Load registration for our blocks.
 *
 * @since 1.6.0
 */
class VibeBP_Register_Blocks {


	/**
	 * This plugin's instance.
	 *
	 * @var VibeBP_Register_Blocks
	 */
	private static $instance;

	/**
	 * Registers the plugin.
	 */
	public static function register() {
		if ( null === self::$instance ) {
			self::$instance = new VibeBP_Register_Blocks();
		}
	}

	/**
	 * The Plugin version.
	 *
	 * @var string $_slug
	 */
	private $_slug;

	/**
	 * The Constructor.
	 */
	public function __construct() {
		$this->counter = 0;
		$this->_slug = 'vibebp';

		add_action( 'init', array($this,'register_blocks'));
		add_filter( 'block_categories', array($this,'block_category'), 1, 2);


		// Hook: Frontend assets.
		add_action( 'enqueue_block_assets', array($this,'block_assets'),9 );

		add_action( 'enqueue_block_editor_assets', array($this,'editor_assets' ));
	}

	/**
	 * Add actions to enqueue assets.
	 *
	 * @access public
	 */
	

	function get_profiile_fields_array(){
		$groups = bp_xprofile_get_groups( array(
			'fetch_fields' => true
		) );
 		$options_array = array(0=>array('value'=>0,'label'=>_x('Select field','','vibebp')));
 		if(!empty($groups)){
 			foreach($groups as $group){
			
				if ( !empty( $group->fields ) ) {
					//CHECK IF FIELDS ENABLED
					foreach ( $group->fields as $field ) {
						$field = xprofile_get_field( $field->id );
						$options_array[] = array(
							'value'=>$field->id,
							'label'=>$field->name,
							'admin_value'=>bp_get_profile_field_data(array('field'=>$field->id,'user_id'=>get_current_user_id()))
						);
						
					} // end for
					
				}
				
			}
 		}

 		return apply_filters('vibebp_get_profile_fields_array',$options_array);
	}

	function block_assets(){


		
    }



	function editor_assets() {

		/*wp_enqueue_script(
			'vibe-shortcodes-gutenblocks-js', // Handle.
			plugins_url( 'blocks.build.js', __FILE__ ), 
			array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ), VIBE_SHORTCODES_VERSION,
			true 
		);*/

		$color_options = apply_filters('vibe_bp_gutenberg_text_color_options',array(

			array(
				'name'=>_x('Black','','vibebp'),
				'color'=>'#000000'
			),
			array(
				'name'=>_x('White','','vibebp'),
				'color'=>'#ffffff'
			),

		));

		$fontsizeunit_options = apply_filters('vibe_bp_gutenberg_text_color_options',array(
			'px','em','rem','pt','vh','vw','%'
		));
		$groups_data_options = array(
					'group_status' =>__('Group Status','vibebp'),
					'last_active' =>__('Last Active','vibebp'),
					'create_date' =>__('Creation Date','vibebp'),
					'last_status_update' =>__('Last Status update','vibebp'),
					'moderator_count' =>__('Moderator Count','vibebp'),
					'admin_count' =>__('Administrator Count','vibebp'),
					'member_count' =>__('Member Count','vibebp'),
					'join_button' =>__('Join/Leave Button','vibebp'),
				);
		$profile_field_styles = apply_filters('vibebp_profile_field_styles',array(
			array(
				'label' => _x('Default','','vibebp'),
				'value' => ''
			),
			array(
				'label' => _x('Stacked','','vibebp'),
				'value' => 'stacked'
			),
			array(
				'label' => _x('No Label','','vibebp'),
				'value' => 'nolabel'
			),
			array(
				'label' => _x('Icons','','vibebp'),
				'value' => 'icon'
			),
		));
		$avatar_style_options = apply_filters('vibebp_avatar_style_options',array(
			array(
				'label' => _x('Default','','vibebp'),
				'value' => ''
			),
			array(
				'label' => _x('Perspective','','vibebp'),
				'value' => 'image_perspective'
			),
			array(
				'label' => _x('Shadow','','vibebp'),
				'value' => 'image_shadow'
			),
			
		));

		$member_data_options = apply_filters('vibebp_member_profile_data',array(
					'member_type' =>__('Member Type','vibebp'),
					'last_active' =>__('Last Active','vibebp'),
					'time_in_site' =>__('Register Time','vibebp'),
					'last_status_update' =>__('Last Status update','vibebp'),
				));

		if(bp_is_active('friends')){
			$member_data_options['count_friends'] =__('Friends Count','vibebp');
		}
		if(bp_is_active('groups')){
			$member_data_options['count_groups'] = __('Group Count','vibebp');
		}
		if(vibebp_get_setting('bp_followers','bp')){
			$member_data_options['count_followers'] = __('Followers Count','vibebp');
			$member_data_options['count_following'] = __('Following Count','vibebp');
		}
		$groups_members_style_options =  array(
					'' =>__('Default','vibebp'),
					'names' =>__('Names','vibebp'),
					'pop_names' =>__('Names Popup','vibebp'),
				);
		$profile_data = apply_filters('vibebp_member_profile_actions',array(
			'view'=>__('View Profile','vibebp')
		));

		if(function_exists('bp_is_active') && bp_is_active('messages')){
			$profile_data['send_message'] =__('Send Message','vibebp');
		}

		if(bp_is_active('friends')){
			$profile_data['add_friend'] =__('Add Friend','vibebp');
		}
		if(vibebp_get_setting('bp_followers','bp')){
			$profile_data['follow'] = __('Follow Member','vibebp');
		}

		if(vibebp_get_setting('firebase_config')){
			$profile_data['chat'] = __('Live Chat with Member','vibebp');
		}

		$fontwieght_options = apply_filters('vibe_bp_gutenberg_text_color_options',array(
			'100','200','300','400','500','600','700','800','900'
		));

		$default_last_active = 0;
		$user_id = bp_displayed_user_id();
		if(!empty($user_id)){
			$default_last_active = $this->vibe_bp_get_user_last_activity( $user_id );
		}else{
			$user_id = get_current_user_id();
			$default_last_active = $this->vibe_bp_get_user_last_activity( $user_id );
		}

		$settings = apply_filters('vibe_bp_gutenberg_data',array(
			'default_avatar'=>plugins_url( '../../assets/images/avatar.jpg',  __FILE__ ),
			'default_profile_value'=>_x('default value','','vibebp'),
			'default_group_name'=>_x('Yoga Champs','','vibebp'),
			
			'default_name'=>_x('default name','','vibebp'),
			'profile_fields' => (array)$this->get_profiile_fields_array(),
			'default_text_color_options' => $color_options,
			'fontsizeunit_options' => $fontsizeunit_options,
			'fontwieght_options'=>$fontwieght_options,
			'default_last_active' => $default_last_active,
			'profile_field_styles' => $profile_field_styles,
			'avatar_style_options' => $avatar_style_options,
			'member_data_options' => $member_data_options,
			'profile_action_options' => $profile_data,
			'groups_data_options' => apply_filters('vibebp_elementor_group_data',$groups_data_options),
			'groups_members_style_options' => $groups_members_style_options,
			'groups_members_options' => array(
					''=>__('All','vibebp'),
					'members' =>__('Members [Excluding Admin & Mods]','vibebp'),
					'mods' =>__('Moderators','vibebp'),
					'admin' =>__('Administrators','vibebp'),
				),
			'friends_sort_options' => array(
					'' =>__('Active','vibebp'),
					'newest' =>__('Recently Added','vibebp'),
					'alphabetical' =>__('Alphabetical','vibebp'),
					'random'=>__('Random','vibebp'),
					'popular'=>__('Popular','vibebp'),
				),
			'friends_style_options' =>  array(
					'' =>__('Default','vibebp'),
					'names' =>__('Names','vibebp'),
					'pop_names' =>__('Hover Names','vibebp'),
				),
			'groups_style_options' =>  array(
					'' =>__('Default','vibebp'),
					'names' =>__('Name','vibebp'),
					'pop_names' =>__('Pop Names','vibebp'),
					'card' =>__('Card','vibebp'),
				),
			'current_user'=>wp_get_current_user(),
			'api_url'=>home_url().'/wp-json/'.Vibe_BP_API_NAMESPACE,
		));

		wp_localize_script( 'vibebp-editor', 'vibe_bp_gutenberg_data', $settings );
		

		/*wp_enqueue_script(
			'vibe-shortcodes-gutenblocks-editor-js', // Handle.
			plugins_url( 'vibeshortcodes.blockeditor.js',  __FILE__ ), 
			array( 'jquery' ), VIBE_SHORTCODES_VERSION,
			true 
		);*/
	
		// Styles.
		/*wp_enqueue_style(
			'vibe-bp-gutenblocks-css', // Handle.
			plugins_url( '../../../assets/css/vibebp.blockseditor.css', __FILE__ ),
			array( 'wp-edit-blocks' ) ,VIBE_SHORTCODES_VERSION
		);*/

		wp_enqueue_style('vicons',plugins_url('../../assets/vicons.css',__FILE__),array(),VIBEBP_VERSION);
		wp_enqueue_style('vibebp-front',plugins_url('../../assets/css/front.css',__FILE__),array(),VIBEBP_VERSION);	
	}

	function block_category( $categories, $post ) {
		$categories[] = array(
							'slug' => 'vibebp',
							'title' => __( 'Vibe Buddypress', 'vibebp' ),
						);
		return $categories;
	}

	function register_blocks(){
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}
		$this->_slug = 'vibebp';
		// Shortcut for the slug.
		$slug = $this->_slug;
		wp_register_style(
			'vibebp-style-css', // Handle.
			plugins_url( 'dist/blocks.style.build.css', dirname( __FILE__ ) ), 
			array( 'wp-editor' ), 
			null 
		);

		// Register block editor script for backend.
		wp_register_script(
			'vibebp-block-js', // Handle.
			plugins_url( '/dist/new_blocks.js', dirname( __FILE__ ) ),
			array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ),
			null, 
			true 
		);

		// Register block editor styles for backend.
		wp_register_style(
			'vibebp-block-editor-css',
			plugins_url( 'dist/blocks.editor.build.css', dirname( __FILE__ ) ),
			array( 'wp-edit-blocks' ), 
			null 
		);


		register_block_type(
			'vibebp/bpavatar', 
			array(
				'editor_script' => $slug . '-editor',
				'editor_style'  => $slug . '-editor',
				'style'         => $slug . '-frontend',
				'render_callback' => array($this,'bpavatar')
			)
		);

		register_block_type(
			'vibebp/bpprofilefield', 
			array(
				'editor_script' => $slug . '-editor',
				'editor_style'  => $slug . '-editor',
				'style'         => $slug . '-frontend',
				'render_callback' => array($this,'bpprofilefield'),
			)
		);

		register_block_type(
			'vibebp/memberprofiledata', 
			array(
				'editor_script' => $slug . '-editor',
				'editor_style'  => $slug . '-editor',
				'style'         => $slug . '-frontend',
				'render_callback' => array($this,'member_profile_data')
			)
		);

		register_block_type(
			'vibebp/lastactive',
			array(
				'editor_script' => $slug . '-editor',
				'editor_style'  => $slug . '-editor',
				'style'         => $slug . '-frontend',
				'render_callback' => array($this,'lastactive')
			)
		);

		register_block_type(
			'vibebp/memberfriends',
			array(
				'editor_script' => $slug . '-editor',
				'editor_style'  => $slug . '-editor',
				'style'         => $slug . '-frontend',
				'render_callback' => array($this,'memberfriends')
			)
		);
		register_block_type(
			'vibebp/membergroups',
			array(
				'editor_script' => $slug . '-editor',
				'editor_style'  => $slug . '-editor',
				'style'         => $slug . '-frontend',
				'render_callback' => array($this,'membergroups')
			)
		);
		register_block_type(
			'vibebp/memberactions',
			array(
				'editor_script' => $slug . '-editor',
				'editor_style'  => $slug . '-editor',
				'style'         => $slug . '-frontend',
				'render_callback' => array($this,'memberactions')
			)
		);
		register_block_type(
			'vibebp/groupsavatar',
			array(
				'editor_script' => $slug . '-editor',
				'editor_style'  => $slug . '-editor',
				'style'         => $slug . '-frontend',
				'render_callback' => array($this,'groupsavatar')
			)
		);
		register_block_type(
			'vibebp/groupsdata',
			array(
				'editor_script' => $slug . '-editor',
				'editor_style'  => $slug . '-editor',
				'style'         => $slug . '-frontend',
				'render_callback' => array($this,'groupsdata')
			)
		);

		register_block_type(
			'vibebp/groupsdescription',
			array(
				'editor_script' => $slug . '-editor',
				'editor_style'  => $slug . '-editor',
				'style'         => $slug . '-frontend',
				'render_callback' => array($this,'groupsdescription')
			)
		);
		
		register_block_type(
			'vibebp/groupsmembers',
			array(
				'editor_script' => $slug . '-editor',
				'editor_style'  => $slug . '-editor',
				'style'         => $slug . '-frontend',
				'render_callback' => array($this,'groupsmembers')
			)
		);

		register_block_type(
			'vibebp/groupstitle',
			array(
				'editor_script' => $slug . '-editor',
				'editor_style'  => $slug . '-editor',
				'style'         => $slug . '-frontend',
				'render_callback' => array($this,'groupstitle')
			)
		);
	}

	function groupstitle(){
		if(bp_get_current_group_id()){
			$group_id = bp_get_current_group_id();
		}else{
			global $groups_template;
			if(!empty($groups_template->group)){
				$group_id = $groups_template->group->id;
			}
		}
		if(empty($group_id)){
			$init = VibeBP_Init::init();
			if(!empty($init->group_id)){
				$group_id = $init->group_id;
			}
		}

		if(empty($group_id) && empty($_GET['action'])){
			$init = VibeBP_Init::init();
			if(empty($init->group_id)){
				global $wpdb,$bp;
				$group_id = $wpdb->get_var("SELECT id FROM {$bp->groups->table_name} LIMIT 0,1");
				$init->group_id = $group_id;
			}else{
				$group_id = $init->group_id;
			}
		}

		$init = VibeBP_Init::init();
		if($init->group->id == $group_id){
			$group = $init->group;
		}else if(empty($init->group)){
			$init->group = groups_get_group($group_id);
			$group = $init->group;
		}
		

		$title = bp_get_group_name($group);
		if(empty($title)){
			$title = 'Group Title';
		}
		

        $style ='';
        if(!empty($settings['font_size'])){
        	$style .= 'font_size:'.$settings['font_size'].'px;';
        }

        return '<h2 class="title '.$settings['style'].'" style="font-family: ' . $settings['font_family'] . ' '.$style.'"><a href="'.bp_get_group_permalink( $group ).'">'.$title.'</a></h2>';
	}

	function groupsmembers($settings){
		if(bp_get_current_group_id()){
			$group_id = bp_get_current_group_id();
		}else{
			global $groups_template;
			if(!empty($groups_template->group)){
				$group_id = $groups_template->group->id;
			}
		}
		if(empty($group_id)){
			$init = VibeBP_Init::init();
			if(!empty($init->group_id)){
				$group_id = $init->group_id;
			}
		}

		if(empty($group_id) && empty($_GET['action'])){
			$init = VibeBP_Init::init();
			if(empty($init->group_id)){
				global $wpdb,$bp;
				$group_id = $wpdb->get_var("SELECT id FROM {$bp->groups->table_name} LIMIT 0,1");
				$init->group_id = $group_id;
			}else{
				$group_id = $init->group_id;
			}
		}

		
		
		global $wpdb,$bp;

		
		$q = $wpdb->prepare("
			SELECT user_id as id FROM {$bp->groups->table_name_members} WHERE group_id = %d",$group_id);

		if($settings['role'] == 'members'){
			$q = $wpdb->prepare("
			SELECT user_id as id FROM {$bp->groups->table_name_members} WHERE group_id = %d AND is_mod !=1 AND is_admin !=1",$group_id);
		}
		if($settings['role'] == 'mods'){
			$q = $wpdb->prepare("
			SELECT user_id as id FROM {$bp->groups->table_name_members} WHERE group_id = %d AND is_mod = 1",$group_id);
		}
		if($settings['role'] == 'admin'){
			$q = $wpdb->prepare("
			SELECT user_id as id FROM {$bp->groups->table_name_members} WHERE group_id = %d AND is_admin = 1",$group_id);
		}

		$members = $wpdb->get_results($q,ARRAY_A);
		
        $html .= '<div class="vibebp_group_members '.$style.'"> ';
        if(!empty($members)){
        	foreach($members as $member){ 
        		$html .= '<div class="vibebp_member">';
        		$html .= '<a href="'.bp_core_get_user_domain($member['id']).'"><img src="'.bp_core_fetch_avatar(array('item_id'=>$member['id'],'object'  => 'user','type'=>'full','html'    => false)).'" ></a>';
        		$html .= '<span>'.bp_core_get_user_displayname($member['id']).'</span>';
        		$html .= '</div>';
        	}
        }
        $html .= ' </div><style>.vibebp_group_members{display:flex;flex-wrap:wrap;margin:-.5rem}.vibebp_group_members>*{flex:1 0 80px;display:flex;align-items:center;background:var(--border);border-radius:32px;margin:.5rem}.vibebp_group_members>* img{width:32px;height:32px;border-radius:50% !important;margin-right:10px}.vibebp_group_members>* span{text-overflow: ellipsis; white-space: nowrap; max-width: 64px; overflow: hidden;}</style>';

        return $html;
	}

	function groupsdescription($settings){
		if(bp_get_current_group_id()){
			$group_id = bp_get_current_group_id();
		}else{
			global $groups_template;
			if(!empty($groups_template->group)){
				$group_id = $groups_template->group->id;
			}
		}
		if(empty($group_id)){
			$init = VibeBP_Init::init();
			if(!empty($init->group_id)){
				$group_id = $init->group_id;
			}
		}
		$init = VibeBP_Init::init();
		if($init->group->id == $group_id){
			$group = $init->group;
		}else if(empty($init->group)){
			$init->group = groups_get_group($group_id);
			$group = $init->group;
		}

		

		$description = bp_get_group_description($group);
		if(empty($description)){
			$description = "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.";
		}
		$style='';
		if(!empty($settings['text_color'])){
			$style .= 'color:'.$settings['text_color'].';';
		}
		if(!empty($settings['font_size'])){
			$style .= 'font-size:'.$settings['font_size'].'px';
		}

        return '<div class="group_description" style="'.$style.'"> '.$description.' </div>';
	}

	function groupsdata($settings){
		if(function_exists('bp_get_current_group_id') && !empty(bp_get_current_group_id())){
			$group_id = bp_get_current_group_id();
		}
		$init = VibeBP_Init::init();
		if(empty($group_id)){
			if(!empty($init->group_id)){
				$group_id = $init->group_id;
			}
		}

		if(empty($group_id) && empty($_GET['action'])){
			
			if(empty($init->group_id)){
				global $wpdb,$bp;
				$group_id = $wpdb->get_var("SELECT id FROM {$bp->groups->table_name} LIMIT 0,1");
				$init->group_id = $group_id;
			}else{
				$group_id = $init->group_id;
			}
		}

		if($init->group->id == $group_id){
			$group = $init->group;
		}else if(empty($init->group)){
			$init->group = groups_get_group($group_id);
			$group = $init->group;
		}
		$style='';
		if(!empty($settings['text_color'])){
			$style .= 'color:'.$settings['text_color'].';';
		}
		if(!empty($settings['font_size'])){
			$style .= 'font-size:'.$settings['font_size'].'px';
		}
        $html = '<div class="group_data_field" style="'.$style.'">';
        
        $html .= $this->get_group_data($settings['data'],$group_id);
        $html .= '</div>';

        return $html;
	}

	function get_group_data($type,$group_id){


		if(empty($this->group) || $this->group->id != $group_id){
			$this->group = groups_get_group($group_id);	
		}

		switch($type){
			case 'last_active':
				$time = groups_get_groupmeta( $group_id, 'last_activity',true);
				if(empty($time)){
					$time = bp_core_current_time();
				}
				return bp_core_time_since($time);
			break;
			case 'create_date':
				return bp_get_group_date_created($this->group);
			break; 
			case 'creator_name':
				return bp_get_group_creator_username();
			break;
			case 'last_status_update':
				$activity = bp_get_user_last_activity($user_id);
				return $activity->content;
			break; 
			case 'admin_count':
				return count( groups_get_group_admins( $group_id ) );
			break; 
			case 'moderator_count':
				return count( groups_get_group_mods( $group_id ) );
			break; 
			case 'member_count':
				return bp_get_group_total_members($this->group);
			break;
			case 'group_status':
				return bp_get_group_status( $group_id );
			break;
			case 'join_button':
				return '<a class="button is-primary join_group_button" data-status="'.$this->group->status.'" data-id="'.$group_id.'">'.__('Join','vibebp').'</a>';
				$vibebp_elementor=VibeBP_Elementor_Init::init();
				add_action('wp_footer',array($vibebp_elementor,'join_button'));
			break;
			default:
				do_action('vibebp_get_group_data',$type,$group_id);
			break;
		}
	}

	function groupsavatar($settings){
		if(bp_get_current_group_id()){
			$group_id = bp_get_current_group_id();
		}else{
			global $groups_template;
			if(!empty($groups_template->group)){
				$group_id = $groups_template->group->id;
			}
		}

		$init = VibeBP_Init::init();

		if(empty($group_id)){
			
			if(!empty($init->group_id)){
				$group_id = $init->group_id;
			}
		}

		if(empty($group_id) && empty($_GET['action'])){
			
			if(empty($init->group_id)){
				global $wpdb,$bp;
				$group_id = $wpdb->get_var("SELECT id FROM {$bp->groups->table_name} LIMIT 0,1");
				$init->group_id = $group_id;
			}else{
				$group_id = $init->group_id;
			}
		}

		if($init->group->id == $group_id){
			$group = $init->group;
		}else if(empty($init->group)){
			$init->group = groups_get_group($group_id);
			$group = $init->group;
		}

		

		$src =  bp_core_fetch_avatar(array(
            'item_id' => $group_id,
            'object'  => 'group',
            'type'=>'full',
            'html'    => false
        ));

        if(empty($src)){
        	$src = plugins_url('../../assets/images/avatar.jpg',__FILE__);
        }

        $style ='';
        if(!empty($settings['width'])){
        	$style .= 'width:'.$settings['width'].'px;';
        }
        if(!empty($settings['radius'])){
        	$style .= 'border-radius:'.$settings['radius'].'%;';
        }
        
        return '<a href="'.bp_get_group_permalink().'"><img src="'.$src.'" class="'.$settings['style'].'" '.(empty($style)?'':'style="'.$style.'"').' /></a>';
	}

	function memberactions($settings){
		if(bp_displayed_user_id()){
			$user_id = bp_displayed_user_id();
		}else{
			$user_id = get_current_user_id();
		}

        

        
		$vibebp_elementor=VibeBP_Elementor_Init::init();
		add_action('wp_footer',array($vibebp_elementor,'event_button'),999);

		wp_localize_script('vibebp-members-actions','vibebpactions',apply_filters('vibebpactions_translations',array(
			'api_url'=>apply_filters('vibebp_rest_api',get_rest_url($blog_id,Vibe_BP_API_NAMESPACE)),
			'friends'=>bp_is_active('friends')?1:0,
			'followers'=>vibebp_get_setting('followers','bp')?1:0,
			'translations'=>array(
				'message_text'=>__('Type message','vibebp'),
				'message_subject'=>__('Message subject','vibebp'),
				'cancel'=>__('Cancel','vibebp'),
				'offline'=>__('Offline','vibebp'),
			)
		)));

       	wp_enqueue_script('vibebp-members-actions',plugins_url('../../assets/js/actions.js',__FILE__),array('wp-element','wp-data'),VIBEBP_VERSION);
       	return '<span class="profile_data_action">'.$this->get_profile_action($settings['action'],$settings['action_label'],$settings['post_action_label']).'</span>';
	}

	function get_profile_action($action,$label,$post_action_label){

		if(bp_displayed_user_id()){
			$user_id = bp_displayed_user_id();
		}else{
			global $members_template;
			if(!empty($members_template->member)){
				$user_id = $members_template->member->id;
			}
		}
		if(empty($user_id)){
			$init = VibeBP_Init::init();
			if(!empty($init->user_id)){
				$user_id = $init->user_id;
			}else{
				$user_id = get_current_user_id();
			}
		}
		
		
		switch($action){
			case 'view':
				return '<a class="member_action view_profile" href="'.bp_core_get_user_domain($user_id).'"><span class="button is-primary">'.$label.'</span></a>';
			break;
			case 'send_message':
				return '<a class="member_action send_message" data-member="'.$user_id.'"><span class="button is-primary">'.$label.'</span><span class="hide">'.$post_action_label.'</span></a>';
			break;
			case 'add_friend':
				return '<a class="member_action friend" data-member="'.$user_id.'"><span class="button is-primary">'.$label.'</span><span class="hide">'.$post_action_label.'</span></a>';
			break;
			case 'follow':
				return '<a class="member_action follow" data-member="'.$user_id.'"><span class="button is-primary">'.$label.'</span><span class="hide">'.$post_action_label.'</span></a>';
			break;
			case 'chat':
				return '<a class="member_action chat" data-member="'.$user_id.'"><span class="button is-primary">'.$label.'</span><span class="hide">'.$post_action_label.'</span></a>';
			break;
		}
	}

	function membergroups($settings){
		if(bp_displayed_user_id()){
			$user_id = bp_displayed_user_id();
		}else{
			global $members_template;
			if(!empty($members_template->member)){
				$user_id = $members_template->member->id;
			}
		}
		if(empty($user_id)){
			$init = VibeBP_Init::init();
			if(!empty($init->user_id)){
				$user_id = $init->user_id;
			}else{
				$user_id = get_current_user_id();
			}
		}
		
		$args = array(
			'type'		=>$settings['order'],
			'user_id'	=>$user_id,
			'per_page'	=>$settings['show_groups']['size']
		);
		$run = groups_get_groups($args);
    		
		if( count($run['groups']) ) {

			foreach($run['groups'] as $k=>$group){
				$run['groups'][$k]->avatar = bp_core_fetch_avatar(array(
                        'item_id' => $run['groups'][$k]->id,
                        'object'  => 'group',
                        'type'=>'thumb',
                        'html'    => false
                    ));
			}
		}
		ob_start();
		?>
		<div class="vibebp_user_groups <?php echo $settings['style'];?>">
			<?php 
			if( $run['total'] ){
				foreach($run['groups'] as $key=>$group){
					echo '<div class="vibebp_user_group">';
					echo '<img src="'.$group->avatar.'" />';
					if($settings['style'] == 'names' || $settings['style'] == 'pop_names'){
						echo '<span>'.$group->name.'</span>';
					}
					echo '</div>';
				}
				if($settings['groups_count'] && ($run['total'] - count($run['groups']))){
					echo '<span>'.($run['total'] - count($run['groups'])).' '.__('more','vibebp').'</span>';	
				}
				
			}
			?>
		</div>
		<?php
		return ob_get_clean();
	}

	function memberfriends($settings){

		if(bp_displayed_user_id()){
			$user_id = bp_displayed_user_id();
		}else{
			global $members_template;
			if(!empty($members_template->member)){
				$user_id = $members_template->member->id;
			}
		}
		if(empty($user_id)){
			$init = VibeBP_Init::init();
			if(!empty($init->user_id)){
				$user_id = $init->user_id;
			}else{
				$user_id = get_current_user_id();
			}
		}

		
		$run = bp_core_get_users( array(
				'type'         => $settings['order'],
				'per_page'     => $settings['show_friends']['size'],
				'user_id'      => $user_id,
			) );

		if( $run['total'] ){

			foreach($run['users'] as $key=>$user){
				$run['users'][$key]->latest_update = maybe_unserialize($user->latest_update);
				$run['users'][$key]->avatar = bp_core_fetch_avatar(array(
                    'item_id' 	=> $user->ID,
                    'object'  	=> 'user',
                    'type'		=>'thumb',
                    'html'    	=> false
                ));
			}
		}
		ob_start();
		?>
		<div class="vibebp_user_friends <?php echo $settings['style'];?>">
			<?php 
			if( $run['total'] ){
				foreach($run['users'] as $key=>$user){
					echo '<div class="vibebp_user_friend">';
					echo '<img src="'.$user->avatar.'" />';
					if($settings['style'] == 'names' || $settings['style'] == 'pop_names'){
						echo '<span>'.$user->display_name.'</span>';
					}
					echo '</div>';
				}
				if($settings['friends_count'] && ($run['total'] - count($run['users']))){
					echo '<span>'.($run['total'] - count($run['users'])).' '.__('more','vibebp').'</span>';	
				}
				
			}
			?>
		</div>
		<?php
		return ob_get_clean();
	}

	function member_profile_data($settings){

		if(bp_displayed_user_id()){
			$user_id = bp_displayed_user_id();
		}else{
			$user_id = get_current_user_id();
		}

        return '<span class="profile_data_field '.(isset($settings['style'])?$settings['style']:'').'" style="'.(isset($settings['text_color'])?'color:'.$settings['text_color']:'').';'.(isset($settings['font_size'])?'font-size:'.$settings['font_size'].'px':'').'">'.$this->get_profile_data($settings['data']).'</span>';
	}

	function get_profile_data($type){
		if(bp_displayed_user_id()){
			$user_id = bp_displayed_user_id();
		}else{
			global $members_template;
			if(!empty($members_template->member)){
				$user_id = $members_template->member->id;
			}
		}
		if(empty($user_id)){
			$init = VibeBP_Init::init();
			if(!empty($init->user_id)){
				$user_id = $init->user_id;
			}else{
				$user_id = get_current_user_id();
			}
		}
		
		
		switch($type){
			case 'member_type':
				$mtype =  bp_get_member_type($user_id);
				$member_type_object = bp_get_member_type_object( $mtype );
				if(is_object($member_type_object)){
					return $member_type_object->labels['singular_name'];	
				}else{
					return '';
				}
			break;
			case 'last_active':
				$activity = bp_get_user_last_activity($user_id);

				return bp_core_time_since($activity);
			break;
			case 'time_in_site':
				echo bp_core_time_since(get_userdata($user_id)->user_registered);
			break; 
			case 'last_status_update':
				$activity = bp_get_user_last_activity($user_id);
				return $activity->content;
			break; 
			case 'count_friends':
				if ( bp_is_active( 'friends' ) ) {
					return BP_Friends_Friendship::total_friend_count( $user_id );
				}
			break; 
			case 'count_groups':
				if ( bp_is_active( 'groups' ) ) {
					return BP_Groups_Member::total_group_count( $user_id );
				}
			break;
			case 'count_followers':
				global $wpdb;
				$count = $wpdb->get_var($wpdb->prepare("
    			SELECT count(user_id) 
    			FROM {$wpdb->usermeta}
    			WHERE meta_key ='vibebp_follow' 
    			AND meta_value = %d",
    			$user_id));
				return intval($count);
			break;
			case 'count_following':
				$following = get_user_meta($user_id,'vibebp_follow',false);
				return count($following);
			break;
			default:
				ob_start();
				do_action('vibebp_profile_get_profile_data',$type,$user_id);
				return ob_get_clean();
			break;
		}
	}

	function bpprofilefield($atts){

		$settings = $atts;
		
		if(bp_displayed_user_id()){
			$user_id = bp_displayed_user_id();
		}else{
			global $members_template;
			if(!empty($members_template->member)){
				$user_id = $members_template->member->id;
			}
		}
		if(empty($user_id)){
			$init = VibeBP_Init::init();
			if(!empty($init->user_id)){
				$user_id = $init->user_id;
			}else{
				$user_id = get_current_user_id();
			}
		}

		
		
		$field = xprofile_get_field( $settings['field_id'] );
		$value = xprofile_get_field_data( $settings['field_id'], $user_id);
		if(is_string($value)){
			$json = json_decode($value,true);
			if (json_last_error() === 0) {
			   $value = $json;
			}
		}

		if($field->type == 'social'){
			$newvalue = '';
			$newvalue .='<div class="social_icons">';
			if(!empty($value)){
				foreach($value as $social_icon){
					$newvalue .='<a href="'.$social_icon['url'].'" target="_blank" style="color:'.$settings['text_color'].';font-size:'.$settings['font_size'].'px"><span class="'.$social_icon['icon'].'"></span></a>';
				}
			}
			
			$newvalue .='</div>';
			$value = $newvalue;
		}

		if($field->type == 'repeatable'){
			$newvalue = '';
			$newvalue .='<div class="repeatable_icons">';
			if(!empty($value)){
				foreach($value as $social_icon){

					$newvalue .='<div class="repeatable_icon">
					<span>'.(strlen($social_icon['icon']) > 100?stripslashes($social_icon['icon']):'<span class="'.$social_icon['icon'].'"></span>').'</span>
					<div class="repeatable_icon_data">
						<h4>'.$social_icon['title'].'</h4>
						<p>'.$social_icon['description'].'</p>
					</div>
					</div>';
				}
			}
			
			$newvalue .='</div>';
			$value = $newvalue;
		}

		if(is_array($value)){
			$newvalue = '';
			foreach($value as $key=>$val){
				if(is_array($val)){
					foreach($val as $k=>$v){
						$newvalue .='<div class="'.$k.'">'.$v.'</div>';	
					}
				}else{
					$newvalue .='<div class="'.$key.'">'.$val.'</div>';	
				}
			}
			$value = $newvalue;
		}

		$value =apply_filters('vibebp_profile_block_field_value',$value,$user_id,$field);
		ob_start();
		?>
		<div class="vibebp_profile_field field_<?php echo $settings['field_id'].' '.$settings['style']; ?> " <?php  echo 'style="color:'.$settings['text_color'].';font-size:'.$settings['font_size'].'px"'; ?>>
			<?php
				if($settings['style'] == 'icon'){
					?><label class="<?php echo $field->name ?>"></label><?php
				}else if($settings['style'] != 'nolabel' ){
					?><label><?php echo $field->name ?></label><?php
				}
			?>
			<div class="<?php echo sanitize_title($field->name).' field_type_'.$field->type;?> "><?php echo do_shortcode(apply_filters('vibebp_profile_field_block_value',$value,$field)); ?>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}


	function bpavatar($settings){

		if(bp_displayed_user_id()){
			$user_id = bp_displayed_user_id();
		}else{
			global $members_template;
			if(!empty($members_template->member)){
				$user_id = $members_template->member->id;
			}
		}
		if(empty($user_id)){
			$init = VibeBP_Init::init();
			if(!empty($init->user_id)){
				$user_id = $init->user_id;
			}else{
				$user_id = get_current_user_id();
			}
		}
		
		
		$src =  bp_core_fetch_avatar(array(
            'item_id' => $user_id,
            'object'  => 'user',
            'type'=>'full',
            'html'    => false
        ));

        

        $style ='';
        if(!empty($settings['width'])){
        	$style .= 'width:'.$settings['width'].'px;';
        }
        if(!empty($settings['radius'])){
        	$style .= 'border-radius:'.$settings['radius'].'%;';
        }
        return '<a href="'.bp_core_get_user_domain( $user_id ).'"><img src="'.$src.'" class="'.$settings['style'].'" '.(empty($style)?'':'style="'.$style.'"').' /></a>';
	}

	function lastactive($atts){
		$styles = array(

			'font-size'=>$atts['customFontSize'].'px',
			'font-weight'=>$atts['fontWeight'],
			'color'=>$atts['font']['Color'],
			'font-family'=>$atts['fontFamily'],
			//'line-height'=>props.attributes.lineHeight,
			'letter-spacing'=>$atts['letterSpacing'].'px',
			'text-transform'=>$atts['textTransform']
		);


		$stylestring = '';
		foreach ($styles as $key => $style) {
			$stylestring .= $key.':'.$style.';';
		}
		$time = 0;
		$user_id = bp_displayed_user_id();
		if(!empty($user_id)){
			$time = $this->vibe_bp_get_user_last_activity( $user_id );
		}else{
			$user_id = get_current_user_id();
			$time = $this->vibe_bp_get_user_last_activity( $user_id );
		}

		return '<div className="lastactive_member" style="'.$stylestring.'">
	                 <span className="bp_last_active"><label>'.$time.'</label></span>
	            </div>';

	}

	


	function vibe_bp_get_user_last_activity($user_id){
		ob_start();
		bp_last_activity($user_id);
		$html = ob_get_clean();
		return $html;
	}
	
}

VibeBP_Register_Blocks::register();
