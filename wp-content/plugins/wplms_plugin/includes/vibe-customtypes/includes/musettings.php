<?php

 if ( ! defined( 'ABSPATH' ) ) exit;


class lms_settings{

	var $option; 

	public static $instance;
    public static function init(){

        if ( is_null( self::$instance ) )
            self::$instance = new lms_settings();
        return self::$instance;
    }

	private function __construct(){
		$this->option = 'lms_settings';

	}

	public function vibe_lms_settings() {
	    $tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'general';
		$this->lms_settings_tabs($tab); 
		$this->get_lms_settings($tab);
	}
	public function vibe_lms_tree() {
		$this->lms_tree(); 
	}
	function lms_tree(){
		global $wpdb;
		$args = array(
			'post_type' => 'course',
			'posts_per_page'=>99,
		);
		if(!current_user_can('manage_options')){
			$args['author'] = get_current_user_id();
		}
		$query = new WP_Query($args);
		if($query->have_posts()){
			?><div class="metabox-holder" ><div class="postbox-container" style="width:80%">
				<div class="meta-box-sortables sortable course_list"><?php
			while($query->have_posts()){
				$query->the_post();
				$status = get_post_status();
				?>
					<div class="coursebox postbox closed  <?php echo (!empty($status) && $status!='publish')?'red danger':''; ?>" data-id="<?php the_ID(); ?>">
						<div class="handlediv" title="Click to toggle"><br></div>
						<h3 class="hndle ui-sortable-handle"><span><?php the_title(); ?> (<?php echo strtoupper($status); ?>)</span></h3>
						
						<div class="inside">
							<div class="main coursedata">
							</div>
						</div>
					</div>	
				<?php
			}
			wp_nonce_field('security','security');
			?></div></div><?php
		}
        wp_reset_postdata();
        ?>
        <script>
        jQuery(document).ready(function($){
        	$('.coursebox').on('click',function(){
        		var course_id = $(this).attr('data-id');
        		var $this = $(this);
        		if($this.hasClass('loaded')){
        			$this.toggleClass('closed');
        		}else{
	                $.ajax({
	                  type: "POST",
	                  url: ajaxurl,
	                  dataType: 'html',
	                  data: { action: 'load_coursetree', 
	                          security: $('#security').val(),
	                          course_id:course_id,
	                        },
	                  cache: false,
	                  success: function (html) {
	                  	$this.find('.coursedata').append(html);
	                    $this.removeClass('closed');
	                    $this.addClass('loaded');
	                  }
	                });        			
        		}
        	});
        });
        </script>
        <style>
        .red.danger {
		    background: rgba(255, 0, 0, 0.36);
		}
        </style>
        <?php
	}

	function lms_settings_tabs( $current = 'general' ) {
	    $tabs = apply_filters('wplms_lms_settings_tabs',array( 
	    		'general' => __('General','wplms'), 
	    		'api' => __('API','wplms'), 
	    		'functions' => __('Functions','wplms'),
	    		'import-export' => __('Import/Export','wplms'),
	    		'touch' => __('Touch Points','wplms'),
	    		'emails' => __('Emails','wplms'),
	    		'live'=> __('Live','wplms'),
	    		'addons' => __('AddOns','wplms'),
	    		));
	    echo '<div id="icon-themes" class="icon32"><br></div>';
	    echo '<h2 class="nav-tab-wrapper">';
	    foreach( $tabs as $tab => $name ){
	        $class = ( $tab == $current ) ? ' nav-tab-active' : '';
	        echo "<a class='nav-tab$class' href='?page=lms-settings&tab=$tab'>$name</a>";

	    }
	    echo '</h2>';
	}

	function get_lms_settings($tab){
		if(isset($_POST['save'])){
			echo $this->lms_save_settings($tab);
		}		
		switch($tab){
			
			case 'api':
				$this->lms_api();
			break; 
			case 'commission_history':
				$this->lms_commission_history();
			break; 
			case 'instructor':
				$this->lms_instructor_settings();
			break; 
			case 'functions':
				$this->lms_resolve_adhoc_function();
				$this->lms_functions();
			break; 
			case 'import-export':
				$this->lms_import_export();
			break; 
			case 'touch':
				$this->lms_touch_points();
			break; 
			case 'emails':
				$this->lms_emails();
			break; 
			case 'live':
				$this->live();
			break;
			case 'addons':
				$this->lms_addons();
			break; 
			default:
				$function_name = apply_filters('lms_settings_tab',esc_attr($tab));
				if(!empty($tab) && function_exists($function_name) && $tab != 'general'){
					$function_name();
				}else{
					$this->lms_general_settings();
				}
				
			break;
		}

		do_action('wplms_get_lms_settings',$tab);
	}

	function lms_api(){
		echo '<h3>'.__('WPLMS API Settings','wplms').'</h3>';
		echo '<p>'.__('WPLMS API settings and configuration','wplms').'</p>';
		$template_array = apply_filters('wplms_lms_api_tabs',array(
			'general'=> __('General Settings','wplms'),
			'apps'=> __('Keys/Apps','wplms'),
			'clients'=> __('Connected Clients','wplms'),
			'updates' => __('Notifications/Updates','wplms')
			));
		echo '<ul class="subsubsub">';
		foreach($template_array as $k=>$value){
			if(empty($_GET['sub']) && empty($current)){
				$current = $k;
			}else if(!empty($_GET['sub']) && empty($current)){
				$current = $_GET['sub'];
			}
			echo '<li><a href="?page=lms-settings&tab=api&sub='.$k.'" '.(($k == $current)?'class="current"':'').'>'.$value.'</a>  &#124; </li>';
		}
		echo '</ul><hr class="clear"/>';
		if(!isset($_GET['sub'])){$_GET['sub']='';}
		switch($_GET['sub']){
			case 'apps':
				$this->lms_apps();
			break;
			case 'updates':
				$this->send_updates();
			break;
			case 'clients':
				$this->lms_connected_clients();
			break;
			default:
				$html = '';
				ob_start();
				do_action('lms_api_settings_sub',$_GET);
				$html = ob_get_clean();
				if(empty($html)){

				
					$settings= apply_filters('lms_api_settings',array(
					array(
						'label'=>__('WPLMS API Settings','wplms' ).'[Legacy, API is enabled by default]',
						'type'=> 'heading',
					),
					array(
							'label' => __('Enable API','wplms'),
							'name' => 'api',
							'type' => 'checkbox',
							'desc' => __('WPLMS API, enables Mobile apps, WebApps and unlimited possibilities in WPLMS.','wplms')
						),
					array(
							'label' => __('API Version','wplms'),
							'name' => 'api_version',
							'type' => 'number',
							'desc' => __('Version controls cached data in Apps.','wplms'),
							'default'=>4,
						),
					array(
							'label' => __('API Security State','wplms'),
							'name' => 'api_security_state',
							'type' => 'text',
							'desc' => __('API security, used in authentications, the bigger the better.','wplms'),
							'default'=> wp_generate_password(8),
						),
					array(
							'label' => __('Enable oAuth2 Server','wplms'),
							'name' => 'oauth',
							'type' => 'checkbox',
							'desc' => __('WPLMS oAuth2 server for user registration and logins from Apps. Only required if your app supports login and registration.','wplms')
						),
					array(
							'label' => __('Enable Registrations via API','wplms'),
							'name' => 'api_registrations',
							'type' => 'checkbox',
							'desc' => __('Registrations via Mobile apps and WebApps.','wplms')
						),
					array(
							'label' => __('Enable Quiz Lock on APP and Site','wplms'),
							'name' => 'quiz_lock',
							'type' => 'checkbox',
							'desc' => __('Avoid cheating in quizzes by enabling this option.','wplms')
						),
					array(
							'label' => __('App Version','wplms'),
							'name' => 'app_version',
							'type' => 'select',
							'options'=>array(
										1 => 1,
										2 => 2,
										3 =>3,
									),
							'desc' => __('WPLMS App version, required for App sold on codecanyon.','wplms')
						),
					array(
							'label' => __('Enable Wallet','wplms'),
							'name' => 'wallet',
							'type' => 'checkbox',
							'desc' => __('Enable Wallet in App.','wplms')
						),
					));
					$this->lms_settings_generate_form('api',$settings);
				}else{
					echo $html;
				}
				break;
			break;
		}

	}

	function lms_apps(){
		echo '<h3>'.__('WPLMS Apps','wplms').'</h3>';
		echo '<p>'.__('Connected clients with WPLMS oAuth server','wplms').'</p>';
		include_once('api/class-apps.php');
		$app = WPLMS_oAuth_Apps::init();
		$app->display();
	}

	function send_updates(){
		echo '<h3>'.__('Updates','wplms').'</h3>';
		echo '<p>'.__('Send updates to all of your app users','wplms').'</p>';
		include_once('api/class-apps.php');
		$app = WPLMS_oAuth_Apps::init();
		$app->send_updates();
	}

	function lms_connected_clients(){
		echo '<h3>'.__('Clients','wplms').'</h3>';
		echo '<p>'.__('Clients with WPLMS oAuth server','wplms').'</p>';
		include_once('api/class-apps.php');
		$app = WPLMS_oAuth_Apps::init();
		$app->clients();
	}


	function lms_save_settings($tab){
		if ( !empty($_POST) && check_admin_referer('vibe_lms_settings','_wpnonce') ){
			$lms_settings=array();

			$lms_settings = get_option($this->option);	

			unset($_POST['_wpnonce']);
			unset($_POST['_wp_http_referer']);
			unset($_POST['save']);
			switch($tab){
				case 'instructor':
					$lms_settings['instructor'] = $_POST;
				break;
				case 'api':
					$lms_settings['api'] = $_POST;
					$this->update_api_settings($_POST);
					
				break;
				case 'student':
					$lms_settings['student'] = $_POST;
				break;
				case 'functions':
					$this->lms_functions();
				break;
				case 'touch':
					$lms_settings['touch'] = $_POST;
				break;
				case 'emails':
					if(empty($_GET['sub'])){
						$option = 'email_settings';
					}else{
						switch($_GET['sub']){
							case 'schedule':
								$option = 'schedule';
							break;
							case 'scheduled_emails':
								$option = 'scheduled_emails';
							break;
							default:
								$option = 'email_settings';
							break;
						}
					}
					
					
					if(!empty($_POST['enable_welcome_email']) && !empty($_GET['tab']) && $_GET['tab'] == 'emails'){

						update_option('wplms_bp_emails',0);
					}
					
					$lms_settings[$option] = $_POST;
				break;
				default:
					$lms_settings['general'] = $_POST;
				break;
			}
			
			update_option($this->option,$lms_settings);

			echo '<div class="updated"><p>'.__('Settings Saved','wplms').'</p></div>';
		}else{
			echo '<div class="error"><p>'.__('Unable to Save settings','wplms').'</p></div>';
		}
	}

	function update_api_settings($_post){
		$settings = get_option('lms_settings');
		
		if(!empty($settings['api']['api_version']) && !empty($_post['api_version']) && $settings['api']['api_version'] != $_post['api_version']){
			global $wpdb;
			delete_option('wplms_api_tracker');
			$wpdb->delete( $wpdb->usermeta, array( 'meta_key' => 'wplms_api_tracker' ) );
		}
	}

	function lms_general_settings(){
		echo '<h3>'.__('LMS General Settings','wplms').'</h3>';
		echo '<p>'.__('Import LMS functions can be managed from here.','wplms').'</p>';

		$template_array = apply_filters('wplms_lms_commission_tabs',array(
			'general'=> __('General Settings','wplms'),
			'registration'=> __('Registration Forms','wplms'),
			));
		echo '<ul class="subsubsub">';

		foreach($template_array as $k=>$value){
			if(empty($_GET['sub']) && empty($current)){
				$current = $k;
			}else if(!empty($_GET['sub']) && empty($current)){
				$current = $_GET['sub'];
			}
			echo '<li><a href="?page=lms-settings&tab=general&sub='.$k.'" '.(($k == $current)?'class="current"':'').'>'.$value.'</a>  &#124; </li>';
		}

		echo '</ul><div class="clear"><hr/>';
		if(!isset($_GET['sub'])){$_GET['sub']='';}
		switch($_GET['sub']){
			case 'registration':
				$this->lms_registration_forms();
			break;
			default:
				$settings= apply_filters('lms_general_settings',array(
				array(
					'label'=>__('Version 4 Specific Settings','wplms' ),
					'type'=> 'heading',
				),
				array(
						'label' => __('Remove Student Controls from Instructor Items','wplms'),
						'name' =>'remove_instructor_student_controls',
						'class' => '',
						'type' => 'checkbox',
						'desc' => __('All users by default are considered as students','wplms') 
					),
				array(
						'label' => __('Full Course Status in Course pages. [No BP Single Page]','wplms'),
						'name' =>'show_course_status',
						'class' => '',
						'type' => 'checkbox',
						'desc' => __('Show Full course status in Course Page. Script size on course page increases from 8kb to 595kb. Does not work with BuddyPress Single Page.','wplms').'<a href="https://youtu.be/VRg1cjVDhXo">?</a>'
				),
				array(
						'label' => __('Advanced Video Format - MPEG Dash','wplms'),
						'name' =>'advanced_video_format_dash',
						'class' => '',
						'type' => 'checkbox',
						'desc' => __('Enable advanced video format support. Mpeg DASH, compatible scripts are enqeued on pages.','wplms')
				),
				array(
						'label' => __('Advanced Video Format - HLS','wplms'),
						'name' =>'advanced_video_format_hls',
						'class' => '',
						'type' => 'checkbox',
						'desc' => __('Enable advanced video format support. HLS, compatible scripts are enqeued on pages.','wplms')
				),
				array(
						'label' => __('Advanced Video Format - 360','wplms'),
						'name' =>'advanced_video_format_360',
						'class' => '',
						'type' => 'checkbox',
						'desc' => __('Enable advanced video format support. 360, compatible scripts are enqeued on pages.','wplms')
				),
				array(
						'label' => __('Enable Assign Feature','wplms'),
						'name' =>'enable_assign_quiz',
						'class' => '',
						'type' => 'checkbox',
						'desc' => __('Will allow instructor to assign quizzes/assignments to users','wplms') 
					),
					array(
						'label' => __('Gamification','wplms'),
						'name' =>'gamification',
						'class' => '',
						'type' => 'checkbox',
						'desc' => __('Enable gamification','wplms') 
					),
					array(
						'label' => __('Instructor can set game points','wplms'),
						'name' =>'gamification_points',
						'class' => '',
						'type' => 'checkbox',
						'desc' => __('Enable instructors to set points for Course elements.','wplms') 
					),
				array(
						'label' => __('Hide complete course button until all units completed','wplms'),
						'name' =>'hide_complete_course_button_curriculum',
						'class' => '',
						'type' => 'checkbox',
						'desc' => __('Will hide complete course button until all units completed','wplms') 
					),
				array(
						'label' => __('Default orderby in my courses','wplms'),
						'name' =>'default_my_courses_orderby',
						'type' => 'select',
						'options'=>apply_filters('wplms_my_courses_default_order_options',array(
							'date' => __('Recent','wplms'),
							'title' => __('Alphabetical','wplms'),
							//'start_date' => __('Upcoming courses via Start date','wplms'),
							)),
						'desc' => __('Default is menu order','wplms')
					),
				array(
						'label' => __('Default order in my courses','wplms'),
						'name' =>'default_my_courses_order',
						'type' => 'select',
						'options'=>array(
							'ASC' => __('Ascending','wplms'),
							'DESC' => __('Descending','wplms'),
							),
						'desc' => __('Default is Descending','wplms')
					),
				array(
						'label' => __('Default orderby in manage courses','wplms'),
						'name' =>'default_manage_courses_orderby',
						'type' => 'select',
						'options'=>apply_filters('wplms_manage_courses_default_order_options',array(
							'date' => __('Recent','wplms'),
							'title' => __('Alphabetical','wplms'),
							'comment_count' => __('Popular','wplms'),
							)),
						'desc' => __('Default is recent','wplms')
					),
				array(
						'label' => __('Default order in manage courses','wplms'),
						'name' =>'default_manage_courses_order',
						'type' => 'select',
						'options'=>array(
							'ASC' => __('Ascending','wplms'),
							'DESC' => __('Descending','wplms'),
							),
						'desc' => __('Default is Descending','wplms')
					),
				array(
					'label'=>__('User Login & Registration Settings','wplms' ),
					'type'=> 'heading',
				),
				
				array(
						'label' => __('Student Login redirect','wplms'),
						'name' =>'student_login_redirect',
						'class' => '',
						'type' => 'select',
						'options'=>apply_filters('wplms_student_login_redirect_filters',array(
							'' => __('Disable','wplms'),
							'home' => __('Home page','wplms'),
							'profile' => __('Profile page','wplms'),
							'mycourses'=> __('My Courses page','wplms'),
							'dashboard'=> __('Dashboard page','wplms'),
							'same' => __('Same page','wplms'),
							)),
						'desc' => __('Default is home page','wplms') 
					),
				array(
						'label' => __('Instructor Login redirect','wplms'),
						'name' =>'instructor_login_redirect',
						'type' => 'select',
						'options'=>apply_filters('wplms_instructor_login_redirect_filters',array(
							'' => __('Disable','wplms'),
							'home' => __('Home page','wplms'),
							'profile' => __('Profile page','wplms'),
							'mycourses'=> __('My Courses page','wplms'),
							'instructing_courses'=> __('Instructing Courses page','wplms'),
							'dashboard'=> __('Dashboard page','wplms'),
							'same' => __('Same page','wplms'),
							)),
						'desc' => __('Default is home page','wplms')
					),
				array(
						'label' => __('Enable One session per user','wplms').'[ Legacy ]',
						'name' => 'one_session_per_user',
						'type' => 'checkbox',
						'desc' => __('A User can login from one unique user id (excludes administrators)','wplms')
					),
				array(
						'label' => __('Hide Administrators in Instructors','wplms'),
						'name' =>'admin_instructor',
						'type' => 'checkbox',
						'desc' => __('Hide Administrator in all instructors page & elsewhere','wplms')
					),
				array(
						'label' => __('Enable message to Instructor in Course Page','wplms').'[ Legacy ]',
						'name' =>'show_message_instructor',
						'type' => 'checkbox',
						'desc' => sprintf(__('Enables a Message icon to send message to Instructor, see %s tutorial %s','wplms'),'<a href="http://vibethemes.com/documentation/wplms/knowledge-base/show-contact-instructor-in-course/" target="_blank">','</a>'),
					),
				array(
			            'label' => __('Enable Create course button in instructor profile menu', 'wplms').'[ Legacy ]',
			            'name' => 'enable_inst_create_course',
			            'desc' => __('Adds a create course link in intructor profile menu', 'wplms'),
			            'type' => 'checkbox',
					),
				array(
			            'label' => __('Show WooCommerce/Pmpro account in profile', 'wplms').'[ Legacy ]',
			            'name' => 'woocommerce_account',
			            'desc' => __('Display WooCommerce account in profile', 'wplms'),
			            'type' => 'checkbox',
					),
				array(
						'label' => __('Set a terms and conditions page for BuddyPress registration','wplms'),
						'name' => 'terms_conditions_in_registration',
						'type' => 'cptselect',
						'cpt'=>'page',
						'desc' => __('Set a terms and conditions page in BuddPress registration.','wplms')
					),
				array(
			            'label' => __('Enable Student menus', 'wplms').'[ Legacy ]',
			            'name' => 'enable_student_menus',
			            'desc' => __('Adds New menu locations for Students', 'wplms'),
			            'type' => 'checkbox',
					),
				array(
			            'label' => __('Enable Instructor menus', 'wplms').'[ Legacy ]',
			            'name' => 'enable_instructor_menus',
			            'desc' => __('Adds New menu locations for Instructors', 'wplms'),
			            'type' => 'checkbox',
					),
				array(
			            'label' => __('Assign Free courses to students on account activation', 'wplms'),
			            'name' => 'assign_free_courses',
			            'desc' => __('Enables auto-subscription to all the "free" courses in site to students when they signup/register and activate their account.', 'wplms'),
			            'type' => 'checkbox',
					),
				array(
					'label'=>__('Course Home Settings','wplms' ).'[ Legacy ]',
					'type'=> 'heading',
				),
				array(
					'label'=>__('Course Members Visibility','wplms' ).'[ Legacy ]',
					'type'=> 'select',
					'style'=>'',
					'name' => 'vibe_display_course_members',
					'options'=>array(
						0=>__('Everyone','wplms'),
						1=>__('Logged in Users','wplms'),
						2=>__('Course Users','wplms'),
						3=>__('Instructors and Admins','wplms'),
					),
					'desc'=> __('Set Course/Members Visibility','wplms' ).'[ Legacy ]',
				),
				array(
					'label'=>__('Course Curriculum Visibility','wplms' ).'[ Legacy ]',
					'type'=> 'select',
					'style'=>'',
					'name' => 'vibe_display_course_curriculum',
					'options'=>array(
						0=>__('Everyone','wplms'),
						1=>__('Logged in Users','wplms'),
						2=>__('Course Users','wplms'),
						3=>__('Instructors and Admins','wplms'),
					),
					'desc'=> __('Set Course/Curriculum Visibility','wplms' ),
				),
				array(
					'label'=>__('Course Events Visibility','wplms' ).'[ Legacy ]',
					'type'=> 'select',
					'style'=>'',
					'name' => 'vibe_display_course_events',
					'options'=>array(
						0=>__('Everyone','wplms'),
						1=>__('Logged in Users','wplms'),
						2=>__('Course Users','wplms'),
						3=>__('Instructors and Admins','wplms'),
					),
					'desc'=> __('Set Course/Events Visibility','wplms' ),
				),
				array(
					'label'=>__('Course Activity Visibility','wplms' ).'[ Legacy ]',
					'type'=> 'select',
					'style'=>'',
					'name' => 'vibe_display_course_activity',
					'options'=>array(
						0=>__('Everyone','wplms'),
						1=>__('Logged in Users','wplms'),
						2=>__('Course Users','wplms'),
						3=>__('Instructors and Admins','wplms'),
					),
					'desc'=> __('Set Course/Activity Visibility','wplms' ),
				),
				array(
					'label'=>__('Course Drive Visibility','wplms' ).'[ Legacy ]',
					'type'=> 'select',
					'style'=>'',
					'name' => 'vibe_display_course_drive',
					'options'=>array(
						0=>__('Everyone','wplms'),
						1=>__('Logged in Users','wplms'),
						2=>__('Course Users','wplms'),
						3=>__('Instructors and Admins','wplms'),
					),
					'desc'=> __('Set Course/Drive Visibility','wplms' ),
				),
				array(
						'label' => __('Hide Instructor in whole Site (single instructors)','wplms'),
						'name' => 'disable_instructor_display',
						'type' => 'checkbox',
						'desc' => __('Disables display of instructor in the site. Suitable for 1 instructors','wplms')
					),
				array(
						'label' => __('Change Pre-Requisite Course Condition from submitted to Evaluated','wplms'),
						'name' => 'enable_pre_required_on_evaluation',
						'type' => 'checkbox',
						'desc' => __('After enabling this students will have to wait for course evaluation by the instructor to move on to courses which have pre-requisite course under evaluation. Default : Students get access on course finish.','wplms')
					),
				array(
						'label' => __('Hide Members section in Single Course page','wplms').'[ Legacy ]',
						'name' =>	'hide_course_members',
						'type' => 'checkbox',
						'desc' => __(' Hides member section in course pages','wplms')
					),
				array(
						'label' => __('Show curriculum below Course description','wplms').'[ Legacy ]',
						'name' =>'course_curriculum_below_description',
						'type' => 'checkbox',
						'desc' => __('Show curriculum below course description','wplms')
					),
				array(
			            'label' => __('Disable Course Certificate image mode', 'wplms').'[ Legacy ]',
			            'name' => 'disable_certificate_screenshot',
			            'desc' => sprintf(__('Disable course certificate in image mode. %s tutorial %s', 'wplms'),'<a href="http://vibethemes.com/documentation/wplms/knowledge-base/disable-certificate-image-mode/" target="_blank">','</a>'),
			            'type' => 'checkbox',
					),
				array(
						'label' => __('Free units should only be accessible to logged in members','wplms').'[ Legacy ]',
						'name' =>'force_free_unit_access',
						'type' => 'checkbox',
						'desc' => __('Disable free unit access for the world, only logged in users can view free units.','wplms')
					),
				array(
			            'label' => __('Remove Finished Courses from directory', 'wplms'),
			            'name' => 'remove_finished_course',
			            'desc' => __('Auto remove finished courses from course directory for user', 'wplms'),
			            'type' => 'checkbox',
					),
				array(
			            'label' => __('Remove expired Courses from directory', 'wplms'),
			            'name' => 'remove_expired_course',
			            'desc' => __('Auto remove expired courses from course directory for user', 'wplms'),
			            'type' => 'checkbox',
					),
				
				array(
						'label' => __('Fix Course Menu on Scroll','wplms').'[ Legacy ]',
						'name' =>'fix_course_menu_on_scroll',
						'type' => 'checkbox',
						'desc' => __('Fix the course menu on scroll only in c2, c3 and c5 layout','wplms')
					),
				array(
						'label' => __('Show Course Badge & Certificate in PopUp on course details','wplms').'[ Legacy ]',
						'name' =>'show_course_badge_certificate_popup_in_course_details',
						'type' => 'checkbox',
						'desc' => __('Display the course badge and certificate in popup in the course details section.','wplms')
					),
				array(
						'label' => __('Open Login popup for non logged in users when they click on take this course button(For all courses)','wplms'),
						'name' =>'open_popup_for_non_logged_users',
						'type' => 'checkbox',
						'desc' => __('Open Login popup for non logged in users when they click on take this course button for paid courses also','wplms')
					),
				array(
						'label' => __('Open Login popup for non logged in users when they click on take this course button(For Free courses only)','wplms').'[ Legacy ]',
						'name' =>'open_popup_for_non_logged_users_free',
						'type' => 'checkbox',
						'desc' => __('Open Login popup for non logged in users when they click on take this course button for free courses','wplms')
					),
				array(
						'label' => __('Enable Course Duration from Start Course','wplms'),
						'name' =>'calculate_course_duration_from_start_course',
						'type' => 'checkbox',
						'desc' => __('The course duration will be calculated from the time the user clicks on start course button.','wplms')
					),
				array(
					'label'=>__('Course Status Settings','wplms' ),
					'type'=> 'heading',
				),
				array(
						'label' => __('Course Timeline Accordion style','wplms').'[ Legacy ]',
						'name' =>'curriculum_accordion',
						'type' => 'checkbox',
						'desc' => __('Show curriculum accordion style','wplms')
					),
				array(
						'label' => __('Disable ajax in Course unit load','wplms').'[ Legacy ]',
						'name' => 'disable_ajax',
						'type' => 'checkbox',
						'desc' => __('Ajax disabled in course unit loads','wplms')
					),	

				array(
						'label' => __('Show unit slug and title in Course Status page','wplms').'[ Legacy ]',
						'name' =>'show_unit_title_course_status',
						'type' => 'checkbox',
						'desc' => __('Unit slug and title is displayed in the title of the course status page when user is viewing a unit','wplms').'<a href="http://vibethemes.com/documentation/wplms/knowledge-base/show-unit-title-and-url-on-course-status-page/" target="_blank"><i class="dashicons  dashicons-editor-help"></i></a>'
					),

				array(
						'label' => __('Enable Direct link to completed units in curriculum','wplms').'[ Legacy ]',
						'name' =>'completed_unit_link_course_status',
						'type' => 'checkbox',
						'desc' => __('Enable direct link to completed unit from curriculum','wplms').'<a href=" https://wplms.io/support/knowledge-base/direct-unit-link-from-curriculum/ " target="_blank"><i class="dashicons  dashicons-editor-help"></i></a>(NEEDS "Show unit slug and title in Course Status page" to be enabled)'
					),
				array(
						'label' => __('Enable Direct link to all units in curriculum(if student subscribed to course)','wplms').'[ Legacy ]',
						'name' =>'completed_unit_link_course_status_for_all_units',
						'type' => 'checkbox',
						'desc' => __('Enable direct link to all units from curriculum (NEEDS "Show unit slug and title in Course Status page" to be enabled)','wplms')
					),
				array(
		            'label' => __('Auto-mark unit complete when user proceeds to next unit', 'wplms').'[ Legacy ]',
		            'name' => 'mark_unit_complete_when_next_unit',
		            'desc' => __('Hides "Mark Unit Complete" button and auto marks the unit as completed when user proceeds to next unit.', 'wplms'),
		            'type' => 'checkbox',
				),
				array(
						'label' => __('Auto trigger finish course button','wplms'),
						'name' =>'finish_course_auto_trigger',
						'type' => 'checkbox',
						'desc' => __('Hides Finish course button and is automatically triggered on completion of all the units and quizzes.','wplms')
					),
				array(
			            'label' => __('Skip Course status page', 'wplms').'[ Legacy ]',
			            'name' => 'skip_course_status',
			            'desc' => __('Skip the introductory page, course status description on course start or continue', 'wplms'),
			            'type' => 'checkbox',
					),			
				array(
					'label'=>__('Course Pricing Settings','wplms' ),
					'type'=> 'heading',
				),
				array(
			            'label' => __('Coming soon courses', 'wplms'),
			            'name' => 'course_coming_soon',
			            'desc' => __('Enable coming soon option for courses', 'wplms'),
			            'type' => 'checkbox',
					),
				array(
			            'label' => __('Enable Course Codes', 'wplms'),
			            'name' => 'course_codes',
			            'desc' => __('Student can purchase/access courses by using custom defined codes for courses in course pricing section. Requires BuddyPress Activity.', 'wplms'),
			            'type' => 'checkbox',
					),
				array(
			            'label' => __('Enable Course external link', 'wplms'),
			            'name' => 'course_external_link',
			            'desc' => __('Connect "Take this Course" button with an external link, defined in Course Pricing section.', 'wplms'),
			            'type' => 'checkbox',
					),
				array(
			            'label' => __('Disable Auto allocation of Free courses', 'wplms').'[ Legacy ]',
			            'name' => 'disable_autofree',
			            'desc' => __('Disables auto allocation of free courses', 'wplms'),
			            'type' => 'checkbox',
					),
				array(
			            'label' => __('Revert to old commissions graphs(from orders table)', 'wplms'),
			            'name' => 'wplms_commissions_migrate_to_activity_revert',
			            'desc' => __('It will revert to old commissions graphs in dashboard(We encourage you to migrate the commissions to activity from wp-admin -> lms -> settings -> functions -> Sync Areas )', 'wplms'),
			            'type' => 'checkbox',
					),
				
				array(
					'label'=>__('Unit settings','wplms' ),
					'type'=> 'heading',
				),

				array(
						'label' => __('Show Unit Description in Course curriculum','wplms'),
						'name' =>'course_curriculum_unit_description',
						'type' => 'checkbox',
						'desc' => __('Unit descriptions appear below Unit titles in Course curriculum','wplms')
					),
				
				array(
						'label' => __('Show User progress in Course Admin','wplms'),
						'name' =>'user_progress_course_admin',
						'type' => 'checkbox',
						'desc' => __('Small progress bar is displayed for every user below her name in course -> admin section','wplms')
					),
				array(
						'label' => __('Enable Unit/Quiz Start Date time','wplms'),
						'name' =>'unit_quiz_start_datetime',
						'type' => 'checkbox',
						'desc' => __('Units and Quizzes start at a particular date and time','wplms')
					),				
				array(
			            'label' => __('Unit Comments/Notes', 'wplms'),
			            'name' => 'unit_comments',
			            'desc' => __('Enable Unit Comments only where Unit comments are enabled in post settings.', 'wplms'),
			            'type' => 'checkbox',
					),
				
				array(
					'label'=>__('Quiz/Assignment Settings','wplms' ),
					'type'=> 'heading',
				),
				array(
						'label' => __('Enable Question Advanced Stats','wplms'),
						'name' => 'question_advanced_stats',
						'type' => 'checkbox',
						'desc' => __('Only available for react quizzes','wplms')
					),
				array(
						'label' => __('Enable In-Course Quiz','wplms').'[ Legacy ]',
						'name' => 'in_course_quiz',
						'type' => 'checkbox',
						'desc' => __('Quizzes open inside course like units','wplms')
					),
				array(
						'label' => __('In-Course Quiz questions per page','wplms').'[ Legacy ]',
						'name' => 'in_course_quiz_paged',
						'type' => 'number',
						'desc' => __('set number of questions appearing per page in in-course quizzes','wplms')
					),
				array(
			            'label' => __('Enable passing score for Quiz', 'wplms'),
			            'name' => 'quiz_passing_score',
			            'desc' => __('Set a passing score for every quiz, Student progress to next unit/quiz is restricted if user fails in quiz', 'wplms'),
			            'type' => 'checkbox',
					),
				array(
			            'label' => __('Enable partialy marking for Quiz', 'wplms'),
			            'name' => 'quiz_partial_marks',
			            'desc' => __('Enables the ability to give partial marks in quizzes.', 'wplms'),
			            'type' => 'checkbox',
					),
				array(
			            'label' => __('Force Quiz availability to Course students', 'wplms'),
			            'name' => 'course_students_quiz',
			            'desc' => __('Only Course students can take the quiz. Quiz must be connected to the course in quiz settings.', 'wplms'),
			            'type' => 'checkbox',
					),
				array(
			            'label' => __('Hide correct answers', 'wplms'),
			            'name' => 'quiz_correct_answers',
			            'desc' => __('Correct answers in quizzes are not displayed unless student has finished/submitted the course.', 'wplms'),
			            'type' => 'checkbox',
					),
				array(
			            'label' => __('Randomize Quiz Question Options(for multiple correct type)', 'wplms'),
			            'name' => 'randomize_question_options',
			            'desc' => __('Randomize Quiz question options', 'wplms'),
			            'type' => 'checkbox',
					),
				array(
			            'label' => __('Enable negative marking', 'wplms'),
			            'name' => 'quiz_negative_marking',
			            'desc' => __('Enables negative marking for questions in quizzes', 'wplms'),
			            'type' => 'checkbox',
					),
				array(
			            'label' => __('Exclude quizzes not connected to course from evaluation', 'wplms'),
			            'name' => 'exclude_not_connected_quiz',
			            'desc' => __('Will not consider quizzes that are not connected to course in quiz settings in course evaluation', 'wplms'),
			            'type' => 'checkbox',
					),
				array(
			            'label' => __('Show Assignments in Course Curriculum', 'wplms').'[ Legacy ]',
			            'name' => 'wplms_course_assignments',
			            'desc' => __('Assignments will be displayed in Course Curriculum', 'wplms'),
			            'type' => 'checkbox',
					),
				array(
						'label' => __('Display Submission time in Course/Quiz/Assignment submissions','wplms'),
						'name' => 'submission_meta',
						'type' => 'checkbox',
						'desc' => __('Displays time (eg 2 hrs) with manual submissions, * requires activity to be enabled','wplms')
					),
				array(
					'label'=>__('Front End Settings','wplms' ),
					'type'=> 'heading',
				),
				array(
			            'label' => __('Force Administrator Approval on every setting', 'wplms'),
			            'name' => 'wplms_force_admin_approval',
			            'desc' => __('Instructors courses will go to pending mode when settings/pricing/curriculum is saved. *Requires Admin Approval enabled in WPLMS - Course Manager', 'wplms'),
			            'type' => 'checkbox',
					),
				array(
			            'label' => __('Enable Front end course deletion', 'wplms'),
			            'name' => 'wplms_course_delete',
			            'desc' => __('Instructors will be able to delete course and related content from front end', 'wplms'),
			            'type' => 'checkbox',
					),
				
				array(
					'label'=>__('Miscellaneous Settings','wplms' ),
					'type'=> 'heading',
				),
				array(
						'label' => __('Enter Guest User id','wplms'),
						'name' => 'guest_user_id',
						'type' => 'text',
						'desc' => __('This user will be used for all guest sessions','wplms')
					),
				array(
						'label' => __('Enable Unsubscribe link for students','wplms'),
						'name' => 'unsubscribe_student_link',
						'type' => 'checkbox',
						'desc' => __('Students will be able to unsubscribe from courses from profile -> courses -> stats','wplms')
					),
				array(
						'label' => __('Revert pretty permalinks for Courses','wplms').'[ Legacy ]',
						'name' => 'revert_permalinks',
						'type' => 'checkbox',
						'desc' => __('Revert permalinks from coursname/admin to coursename/?action-','wplms')
					),

				array(
			            'label' => __('Enable Course forum privacy', 'wplms'),
			            'name' => 'enable_forum_privacy',
			            'desc' => __('Only course students can access course forums', 'wplms'),
			            'type' => 'checkbox',
					),
				
				array(
						'label' => __('Default order in course directory','wplms').'[ Legacy ]',
						'name' =>'default_order',
						'type' => 'select',
						'options'=>apply_filters('wplms_course_directory_default_order_options',array(
							'date' => __('Recent','wplms'),
							'title' => __('Alphabetical','wplms'),
							'popular' => __('Number of Students','wplms'),
							'rated' => __('Rating','wplms'),
							'start_date' => __('Upcoming courses via Start date','wplms'),
							'rand'  => __('Random','wplms'),
							)),
						'desc' => __('Default is menu order','wplms')
					),
				array(
						'label' => __('Default order in Members directory (clear cookies to test)','wplms').'[ Legacy ]',
						'name' =>'members_default_order',
						'type' => 'select',
						'options'=>array(
							''=> __('None','wplms'),
							'active' => __('Last active','wplms'),
							'newest' => __('Newest registered','wplms'),
							'alphabetical' => __('Alphabetical','wplms'),
							),
						'desc' => __('Default is menu order','wplms')
					),
				array(
						'label' => __('Limit Number of Courses per Instructor','wplms'),
						'name' =>'course_limit',
						'type' => 'number',
						'desc' => __('( 0 for unlimited course per instructor )','wplms')
					),
				array(
					'label' => __('Limit Number of Units Created per Instructor','wplms'),
					'name' =>'unit_limit',
					'type' => 'number',
					'desc'=>__(' ( 0 for unlimited )','wplms')
					),
				array(
					'label' => __('Limit Number of Quiz Created per Instructor ','wplms'),
					'name' =>'quiz_limit',
					'type' => 'number',
					'desc' =>__('(0 for unlimited course per instructor )','wplms'),
					),
				));

				$this->lms_settings_generate_form('general',$settings);
				break;
		}
	}

	function limit_courses_per_month($monthly_limit){
		if(!$monthly_limit)
			return;
		//Limit posts per month
	    $time_in_days = 30; // 1 means in last day
	    $count = $wpdb->get_var(
	        $wpdb->prepare("
	            SELECT COUNT(*) 
	            FROM $wpdb->posts 
	            WHERE post_status = 'publish' 
	            AND post_type = %s 
	            AND post_author = %s
	            AND post_date >= DATE_SUB(CURDATE(),INTERVAL %s DAY)",
	            'course',
	            get_current_user_id(),
	            $time_in_days
	        )
	    );
	    if ( 0 < $count ) 
	    $count = number_format( $count );

	    if ( $monthly_limit <=$count ) {
	         $errors[] = __('You have reached your monthly post limit','wplms');
	    }
	}
	/*
		REGISTRATION FORMS in LMS - SETTINGS
	*/
	function lms_registration_forms(){

		echo '<h3>'.__('Registration Forms','wplms').'</h3>';
		echo '<p>'.sprintf(__('Build registration forms for Students and Instructors, refer %s tutorial %s','wplms'),'<a href="https://wplms.io/support/knowledge-base/custom-registration-forms-in-wplms/">','</a>').'</p>';
		if(!function_exists('bp_xprofile_get_groups')){
			echo _x('xProfile fields not enabled','error message displayed in registration forms when xprofile are disabled','wplms');
			return;
		}

		//for groups selection select2
		?>

		<script type="text/javascript">
			jQuery(document).ready(function($){
				$('#wplms_user_bp_group').select2({
			      allowClear: true,
			      placeholder: "<?php echo _x('Select groups setting ','','wplms');?>"
			    });

				$('#wplms_user_bp_group').on("change", function() {
			       var values = $(this).val();
			       var $this = $(this);
			       console.log(values);
			       var check = ['enable_user_select_group'];
			       if(typeof values != 'undefined' && values != null && jQuery.inArray('enable_user_select_group',values) > -1){
			       		$.each($this.find('option:not(.all)'),function(){
			       			var $option = $(this);
			       			$option.removeAttr('selected');
			       		});
			       		if(!compareArrays(values,check)){
			       			$this.trigger('change');
			       		}
			       }
			    });
			    
				function compareArrays(a, b) {
				    return !a.some(function (e, i) {
				        return e != b[i];
				    });
				}
			});
		</script>
		<?php
		$groups = bp_xprofile_get_groups( array(
			'fetch_fields' => true
		) );

		if(empty($groups)){
			echo _x('No fields found !','error message displayed in registration forms when no xprofile fields exist','wplms');
			return;
		}
		global $wp_roles;

		if ( ! isset( $wp_roles ) )
		    $wp_roles = new WP_Roles();

		$registration_emails = new WP_Query(array(
			'post_type'=>'bp-email',
			'posts_per_page'=>-1,
			'tax_query' => array(
				array(
					'taxonomy' => 'bp-email-type',
					'field'    => 'slug',
					'terms'    => 'core-user-registration',
				),
			),
		));
		$registration_mails = array('no'=>__('Disable activation email and manually approve accounts.','wplms'));
		if ( $registration_emails->have_posts() ) {
			
			while ( $registration_emails->have_posts() ) {
				$registration_emails->the_post();
				$registration_mails[get_the_ID()]=get_the_title();
			}
			
			/* Restore original Post Data */
			wp_reset_postdata();
		}
		//Sync with Vibe shortcodes Ajax calls and Shortcode.php
		
		$types = bp_get_member_types(array(),'objects');
		$mtypes = [];
		if(!empty($types)){
			$mtypes['enable_user_member_types_select'] = _x('Enable user to select','','wplms');
			foreach($types as $type => $labels){
				$mtypes[$type]=$labels->labels['name'];
			}
		}

		$form_settings = array(
			'hide_username' =>  __('Auto generate username from email','wplms'),
			'password_meter' =>  __('Show password meter','wplms'),
			'show_group_label' =>  __('Show Field group labels','wplms'),
			'google_captcha' => __('Google Captcha','wplms'),
			'auto_login'=> __('Register & Login simultaneously','wplms'),
			'skip_mail' =>  __('Skip Mail verification','wplms'),
			'custom_activation_mail' =>  array( 
								'label' => __('Custom Activation Mail ','wplms'),
								'default_option' => _x('Default activation email is sent','registration form','wplms'),
								'options' => $registration_mails
							),
			'default_role' =>  array( 
								'label' => __('Assign User role','wplms'),
								'default_option' => _x('Default role','role in registration form','wplms'),
								'options' => $wp_roles->get_names()

							),
			'member_type' =>  array( 
								'label' => __('Assign Member Type','wplms'),
								'default_option' => _x('None','','wplms'),
								'options' => $mtypes
							),
		);
		
		$form_settings=apply_filters('wplms_registration_form_settings',$form_settings);
		/*
			FORM CREATION
		*/
		
		$forms = get_option('wplms_registration_forms');
		
		if(!empty($_POST['wplms_create_registration_from']) && !empty($_POST['wplms_registration_form_security']) && !empty($_POST['wplms_add_registration_form'])){
			if(wp_verify_nonce($_POST['wplms_registration_form_security'],'wplms_security')){
				if(empty($forms)){$forms=array();}
				$name = strtolower(strip_tags($_POST['wplms_add_registration_form']));
				$name = str_replace(' ','_',$name);
				$forms[$name] = array();
				update_option('wplms_registration_forms',$forms);
			}
		}

		// SAVE FORM FIELDS
		if(!empty($_POST['wplms_save_registration_fields']) && !empty($_POST['wplms_save_registration_form_fields'])){
			if(wp_verify_nonce($_POST['wplms_save_registration_form_fields'],'wplms_fields_security')){
				if(!empty($forms) && !empty($_POST)){

					foreach($forms as $k=>$v){
						$k = str_replace(' ','_',$k); //Sanitize form names
						$forms[$k]=$v;
					}
					$form_names = array_keys($forms);
					foreach($form_names as $name){
						unset($forms[$name]['fields']);
					}
					
					foreach($_POST as $label=>$value){
						if(!in_array($label,array('wplms_save_registration_form_fields','_wp_http_referer','wplms_save_registration_fields'))){
							$names = explode('|',$label);							
							if(!empty($names) && isset($forms[$names[1]])){
								if(empty($forms[$names[1]])){
									$forms[$names[1]] = array('fields'=>array($names[0]));
								}else if(empty($forms[$names[1]]['fields'])) {
									$forms[$names[1]]['fields'] = array($names[0]);
								}else if(!in_array($names[0],$forms[$names[1]]['fields'])){
									$forms[$names[1]]['fields'][] = $names[0];
								}
							}
						}
					}
					update_option('wplms_registration_forms',$forms);
				}
			}
		}

		if(!empty($_POST['wplms_registration_form_sub_security']) && !empty($_POST['registration_form_name'])){
			if(wp_verify_nonce($_POST['wplms_registration_form_sub_security'],'wplms_sub_security')){
				
				if(isset($_POST['default_registration_form'])){ 
					// UNSET ALL DEFAULT KEYS
					foreach($forms as $key=>$f){
						if(!empty($f) && isset($f['default'])){
							unset($forms[$key]['default']);
						}
					}
					//SET THE CURRENT DEFAULT KEY
					if(empty($forms[strip_tags($_POST['registration_form_name'])])){
						$forms[strip_tags($_POST['registration_form_name'])] = array('default'=>1);
					}else{
						$forms[strip_tags($_POST['registration_form_name'])]['default'] = 1;
					}
				}else if(!empty($_POST['remove_registration_form'])){
					if(isset($forms[strip_tags($_POST['registration_form_name'])])){
						unset($forms[strip_tags($_POST['registration_form_name'])]);
					}
				}
				update_option('wplms_registration_forms',$forms);
			}
		}

		if(!empty($_POST['save_form_settings']) && !empty($_POST['registration_form_name'])){
			if(wp_verify_nonce($_POST['wplms_registration_form_sub_security'],'wplms_sub_security')){
				$forms[$_POST['registration_form_name']]['settings'] = array();
				foreach($_POST as $k => $la){
					if(!in_array($k,array('registration_form_name','wplms_registration_form_sub_security','_wp_http_referer'))){
						$sv = explode('|',$k);
						$forms[$_POST['registration_form_name']]['settings'][$sv[0]]=$la;
					}
				}
				update_option('wplms_registration_forms',$forms);
			}
		}
		if(!empty($forms))
			$form_names = array_keys($forms);

		if(!empty($forms)){
			$default = 0;
			foreach($form_names as $i=>$name){
				if(!empty($forms[$name]['default']) && $forms[$name]['default'] == 1){
					$default = $name;
				}
			}
			echo '<h3>'._x('Existing Registration forms','Forms registered in site','wplms').'</h3>
			<ul class="registration_field_groups">';
			foreach($form_names as $i=>$name){
				if(empty($default) && $i ==0){$default = $name;}
				$name = str_replace(' ','_',$name);
				echo '<li><form method="post"><label class="field_name">'.$name.'&nbsp;<br>

				<span style="font-weight:400;text-transform:none;"><code id="'.$name.'" onclick="copyToClipboard(\'#'.$name.'\')">[wplms_registration_form name="'.$name.'" field_meta=1]</code>

				</span> <small style="font-weight:200; font-size:12px;text-transform:none;">
				<br>('.__('field_meta for field description & visbility','wplms').')</small></label><input type="hidden" value="'.$name.'" name="registration_form_name"><input type="submit" name="default_registration_form" class="button '.(($default == $name)?'button-primary':'').'"  value="'.(($default == $name)?__('Default','wplms'):_x('Set as default','set a default registration form','wplms')).'">&nbsp;<a class="button" onClick="jQuery(this).parent().find(\'.registration_form_settings\').toggle(200);">'._x('Settings','delete button label','wplms').'</a>&nbsp;<input type="submit" name="remove_registration_form" class="button" value="'._x('Delete','delete button label','wplms').'">';
				echo '<div class="registration_form_settings" style="display:none;">';
				echo '<ul class="registration_field_groups" style="padding:10px;">';
				
				foreach($form_settings as $key => $label){
					$key = str_replace(' ','_',$key);
					echo '<li>';
					if(is_array($label)){
						echo '<label class="field_name">'.$label['label'].'</label><select name="'.$key.'|'.$name.'"><option value="">'.$label['default_option'].'</option>';
						foreach($label['options'] as $k=>$l){
							echo '<option value="'.$k.'" '.((isset($forms[$name]['settings'][$key]) && $forms[$name]['settings'][$key] == $k)?'selected':'').'>'.$l.'</option>';
						}
						echo '</select>';
					}else{
						echo '<label class="field_name">'.$label.'</label><input type="checkbox" name="'.$key.'|'.$name.'" '.(isset($forms[$name]['settings'][$key])?'checked':'').'/></li>';	
					}
				}
				// groups select
				if(function_exists('bp_is_active') && bp_is_active('groups') && class_exists('BP_Groups_Group')){
					$vgroups = BP_Groups_Group::get(array(
							'type'=>'alphabetical',
							'per_page'=>999
							));
					$vgroups = apply_filters('wplms_custom_registration_form_groups_select_settings_form',$vgroups);
					if(!empty($vgroups['groups'] ) && count($vgroups['groups'] )){
						echo '<li><label class="field_name">'.__('Add to Buddypress group','wplms').'</label><select multiple class="select2 chosen" name="wplms_user_bp_group|'.$name.'[]" id="wplms_user_bp_group">
						

						<optgroup  label="'._x('All groups','','wplms').'">
						<option  class="all" value="enable_user_select_group" '.((isset($forms[$name]['settings']['wplms_user_bp_group']) && is_array($forms[$name]['settings']['wplms_user_bp_group']) && in_array('enable_user_select_group',$forms[$name]['settings']['wplms_user_bp_group']))?'selected="selected"':'').'>'._x('Enable user to select from all groups','','wplms').'</option>

						</optgroup>';

						echo '<optgroup groupid="selected_groups" label="'._x('Selected Groups','','wplms').'">';
						foreach ($vgroups['groups'] as $key => $group) {
							echo '<option value="'.$group->id.'"  '.((isset($forms[$name]['settings']['wplms_user_bp_group']) && is_array($forms[$name]['settings']['wplms_user_bp_group']) && in_array($group->id,$forms[$name]['settings']['wplms_user_bp_group']))?'selected="selected"':'').'>'.$group->name.'</option>';
						}
						echo '</optgroup></select></li>';
					}
				}
				?>
				
				<?php
				do_action('wplms_registration_form_setting',$name);
				echo '<li><input type="submit" name="save_form_settings" class="button-primary" value="'._x('Save','save form settings','wplms').'" /></li>';
				echo '</ul>';
				echo '</div>';
				wp_nonce_field('wplms_sub_security','wplms_registration_form_sub_security');
			echo '</form></li>';
			}
			echo '</ul>';
			?>
			<script>
			function copyToClipboard(element) {
			    var $temp = jQuery("<input>");
			    jQuery("body").append($temp);
			    $temp.val(jQuery(element).text()).select();
			    document.execCommand("copy");
			    $temp.remove();
			    alert('<?php _e('Shortcode Copied !','wplms'); ?>');
			}
			</script>
			<?php
			
		}
		echo '<a id="create_registration_form" onClick="jQuery(this).next().toggle(200);" class="button-primary">'._x('Add Registration Form','create registration form button label','wplms').'</a><form method="post" style="display:none;"><br>';
		echo '<input type="text" name="wplms_add_registration_form" style="width:50%;" placeholder="'._x('Type the name of the form, avoid spaces and special characters','enter form name placeholder','wplms').'"><input type="submit" name="wplms_create_registration_from" class="button" value="'._x('Add Form','Add form submit button label','wplms').'" >';
		wp_nonce_field('wplms_security','wplms_registration_form_security');
		echo '</form>';

		if(empty($forms)){
			echo '<div class="message error"><p>'._x('No Registration forms found !','warning message when no registration forms are found','wplms').'</p></div>';
			return;
		}

		
		echo '<br><hr><h3>'._x('Connect Forms with Fields','connect form heading','wplms').'</h3>
		<form method="post"><ul class="registration_field_groups">';
		foreach($groups as $group){
			echo '<h4>'._x('Field Group','field group prefix in registration form','wplms').' : '.esc_html( apply_filters( 'bp_get_the_profile_group_name', $group->name ) ).'</h4>';
			if ( !empty( $group->fields ) ) {
				echo '<ul class="profile_fields">';
				
				//Form NAMES
				echo '<li><label class="field_name">'._x('Field Name','','wplms').'</label>';
				if(!empty($form_names)){
					foreach($form_names as $name){
						echo '<label>'.$name.'</label>';
					}
				}
				echo '</li>';

				//CHECK IF FIELDS ENABLED
				foreach ( $group->fields as $field ) {
					$field = xprofile_get_field( $field->id );
					echo '<li>';
					echo '<label class="field_name">'.$field->name.' ( '.$field->type.''.(empty($field->can_delete)?', '._x('Necessary','necessary fields for buddypress registration','wplms'):'').
					')</label>';
					if(!empty($form_names)){
						foreach($form_names as $name){
							$k = str_replace(' ','_',$field->name);
							$name = str_replace(' ','_',$name);
							echo '<label><input type="checkbox" name="'.$k.'|'.$name.'" '.((isset($forms[$name]['fields']) && in_array($k,$forms[$name]['fields']) || empty($field->can_delete))?'checked':'').' value="1"></label>';
						}
					}
					echo '</li>';
				} // end for
				echo '</ul>';
			}else {
				?>

					<p class="nodrag nofields"><?php _e( 'There are no fields in this group.', 'buddypress' ); ?></p>
				<?php
			}
		}
		echo '</ul><br>';

		wp_nonce_field('wplms_fields_security','wplms_save_registration_form_fields');
		echo '<input type="submit" name="wplms_save_registration_fields" value="'._x('Save Form fields','save form fields label in registration forms lms - settings','wplms').'" class="button-primary"/>';
		echo '</form>';
	}
	
	/*
	FUNCTIONS TAB IN LMS - SETTINGS
	 */
	function lms_functions(){
		do_action('wplms_admin_custom_admin_panel');
		echo '<h3>'.__('LMS Functions [ For Ad-Hoc Management]','wplms').'</h3>';
		echo '<p>'.__('Import LMS functions can be managed from here.','wplms').'</p>';

		$template_array = apply_filters('wplms_lms_commission_tabs',array(
			'sync'=> __('Sync Areas','wplms'),
			'adhoc'=> __('Ad Hoc','wplms'),
			));
		if(empty($_GET['sub'])){$_GET['sub']='sync';}
		echo '<ul class="subsubsub">';
		foreach($template_array as $k=>$value){
			echo '<li><a href="?page=lms-settings&tab=functions&sub='.$k.'" '.((!empty($_GET['sub']) && $k == $_GET['sub'])?'class="current"':'').'>'.$value.'</a> '.(($k=='template')?'':' &#124; ').' </li>';
		}
		echo '</ul><div class="clear"><hr/>';
		
		switch($_GET['sub']){
			case 'adhoc':
				echo '<form method="post"><ul class="lms-settings">';
				echo '<li><label>'.__('Custom Field Value','wplms').'</label><input type="text" name="id" placeholder="ID"><input type="text" name="field_name" placeholder="Field Name"><input type="text" name="field_value" placeholder="Field Value"><input type="submit" name="set_field" class="button button-primary" value="Set Field" />';
				echo '<li><label>'.__('Custom Field for Student Value','wplms').'</label><input type="text" name="student_id" placeholder="Student ID"><input type="text" name="field_name_student" placeholder="Field Name"><input type="text" name="field_value_student" placeholder="Field Value"><input type="submit" name="set_field_for_student" class="button button-primary" value="Set Field" />';
				echo '<li><label>'.__('Custom Field for Option','wplms').'</label><input type="text" name="option_name" placeholder="Options Name"><input type="text" name="option_value" placeholder="Options Value"><input type="submit" name="set_field_for_option" class="button button-primary" value="Set Field" />';
				echo '<li><label>'.__('Custom Field Value for Post','wplms').'</label><input type="text" name="post_id" placeholder="Post ID"><input type="text" name="post_field_name" placeholder="Post Field Name"><input type="text" name="post_field_value" placeholder="Post Field Value"><input type="submit" name="set_field_for_post" class="button button-primary" value="Set Field" />';
				echo '<li><label>'.__('Current Time Stamp ','wplms').'</label><span>'.time().'</span></li>';
				wp_nonce_field('vibe_admin_adhoc','_vibe_admin_adhoc');
				echo '</ul></form>';
			break;
			default:
				echo '<h3>'.__('Synchronise LMS Data','wplms').'</h3><p>'.__('Only required in special cases when data goes out of sync.','wplms').'</p>';
				echo '<table class="form-table">
						<tbody>';
				$sync_settings = wplms_get_sync_settings();

				foreach($sync_settings as $setting){
					echo '<tr valign="top">
							<th scope="row" class="titledesc">
								<label>'.$setting['label'].'</label>
								<p style="font-weight: 400;color: #888;">'.$setting['description'].'</p>
							</th>
							<td class="forminp"><a class="button sync_resync" data-id="'.$setting['id'].'">'.__('Sync Now','wplms').'</a></td>
						</tr>';
				}
				
				//sync_resync_js  JS CALL
						wp_nonce_field('sync_resync','sync_security');
				?>
				<script>
					jQuery(document).ready(function($){

						$('.sync_resync').on('click',function(){
							var $this = $(this);
							$this.after('<span class="status">Starting ...</span><div class="progress_wrap" style="margin: 30px 0;width: 300px;"><div class="progress" style="height: 10px;border-radius: 5px;"><div class="bar" style="width: 5%;"></div></div></div>');
							//Show progress bar
							$.ajax({
					          	type: "POST",
					          	url: ajaxurl,
					          	dataType: "json",
					          	data: { action: 'sync_resync', 
					                  id: $this.attr('data-id'),
					                  security: $('#sync_security').val(),
					                },
					          	cache: false,
					          	success: function (json) {

					            	$this.parent().find('.progress_wrap .bar').css('width','10%');
					            	$this.parent().find('span.status').text('fetch '+Object.keys(json).length+' results, sync in progress...');
					            	var defferred = [];
					            	var current = 0;
					            	$.each(json,function(i,item){
					            		defferred.push(item);
					            	});
					            	recursive_step(current,defferred,$this);
					            	//$.each() RUN loop on json and increment progress bar
					            	$('body').on('end_recursive_sync',function(){
					            		$.ajax({
								          	type: "POST",
								          	url: ajaxurl,
								          	data: { action: $this.attr('data-id'), 
								                  security: $('#sync_security').val(),
								                },
								          	cache: false,
								          	success: function (text) {
								          		$this.parent().find('span.status').text(text);
								            	//Complete the progress
								            	$this.parent().find('.progress_wrap .bar').css('width','100%');
								            	setTimeout(function(){$this.parent().find('.progress_wrap,.status').hide(200);},3000);
								          	}
								        });
					            	});
					          	}
					        });
						});

						function recursive_step(current,defferred,$this){
						    if(current < defferred.length){
						        $.ajax({
						            type: "POST",
						            url: ajaxurl,
						            data: defferred[current],
						            cache: false,
						            success: function(){ 
						                current++;
						                $this.parent().find('span.status').text(current+'/'+defferred.length+' complete, sync in progress...');
						                var width = 10 + 90*current/defferred.length;
						                $this.parent().find('.bar').css('width',width+'%');
						                if(defferred.length == current){
						                    $('body').trigger('end_recursive_sync');
						                }else{
						                    recursive_step(current,defferred,$this);
						                }
						            }
						        });
						    }else{
						    	$('body').trigger('end_recursive_sync');
						    }
						}//End of function

					});
				</script>
				<?php		
			break;
		}

	}

	function lms_settings_generate_form($tab,$settings=array()){

		if(empty($settings))
			return;
	
		echo '<form method="post">';
		wp_nonce_field('vibe_lms_settings','_wpnonce');
		echo '<table class="form-table">
				<tbody>';

		$lms_settings=get_option($this->option);

		foreach($settings as $setting ){
			echo '<tr valign="top" '.(empty($setting['class'])?'':'class="'.$setting['class'].'"').'>';
			switch($setting['type']){
				case 'heading':
					echo '<th scope="row" class="titledesc" colspan="2"><h3>'.$setting['label'].'</h3></th>';
				break;
				case 'link':
					echo '<th scope="row" class="titledesc"><label>'.$setting['label'].'</label></th>';
					echo '<td class="forminp"><a href="'.$setting['value'].'" class="button">'.$setting['button_label'].'</a>';
					echo '<span>'.$setting['desc'].'</span></td>';
				break;
				case 'select':
					echo '<th scope="row" class="titledesc"><label>'.$setting['label'].'</label></th>';
					echo '<td class="forminp"><select name="'.$setting['name'].'">';
					foreach($setting['options'] as $key=>$option){
						echo '<option value="'.$key.'" '.(isset($lms_settings[$tab][$setting['name']])?selected($key,$lms_settings[$tab][$setting['name']]):'').'>'.$option.'</option>';
					}
					echo '</select>';
					echo '<span>'.empty($setting['desc'])?'':$setting['desc'].'</span></td>';
				break;
				case 'checkbox':
					echo '<th scope="row" class="titledesc"><label>'.$setting['label'].'</label></th>';
					echo '<td class="forminp"><input type="checkbox" name="'.$setting['name'].'" '.(isset($lms_settings[$tab][$setting['name']])?'CHECKED':'').' />';
					echo '<span>'.$setting['desc'].'</span>';
				break;
				case 'number':
					echo '<th scope="row" class="titledesc"><label>'.$setting['label'].'</label></th>';
					echo '<td class="forminp"><input type="number" name="'.$setting['name'].'" value="'.(isset($lms_settings[$tab][$setting['name']])?$lms_settings[$tab][$setting['name']]:(isset($setting['default'])?$setting['default']:'')).'" />';
					echo '<span>'.$setting['desc'].'</span></td>';
				break;
				case 'cptselect':
					echo '<th scope="row" class="titledesc"><label>'.$setting['label'].'</label></th>';
					echo '<td class="forminp">';
					echo '<select name="'.$setting['name'].'"><option value="">'.__('Select','wplms').' '.$setting['cpt'].'</option>';
					global $wpdb;
					$cpts = '';
					if($setting['cpt']){
						$cpts = $wpdb->get_results("
							SELECT ID,post_title 
							FROM {$wpdb->posts} 
							WHERE post_type = '".$setting['cpt']."' 
							AND post_status='publish' 
							ORDER BY post_title DESC LIMIT 0,999");	
					}
					if(is_array($cpts)){
						foreach($cpts as $cpt){
							echo '<option value="'.$cpt->ID.'" '.((isset($lms_settings[$tab][$setting['name']]) && $lms_settings[$tab][$setting['name']] == $cpt->ID)?'selected="selected"':'').'>'.$cpt->post_title.'</option>';
						}
					}
					echo '</select>';
					echo '<span>'.$setting['desc'].'</span></td>';
				break;
				case 'clpstepselect2':
					echo '<th scope="row" class="titledesc"><label>'.$setting['label'].'</label></th>';
					echo '<td class="forminp">';
					echo '<select id="'.$setting['name'].'" name="'.$setting['name'].'[]" class="clpstepselect2" multiple>';
					if( function_exists('wplms_clp_get_step_types') ){
						$all_steps = wplms_clp_get_step_types();
						if( !empty($all_steps) ){
							$selected = null;
							if( isset($lms_settings[$tab][$setting['name']]) ){
								$selected = $lms_settings[$tab][$setting['name']];
							}
							foreach ($all_steps as $step) {
								echo '<option '.((is_array($selected) && in_array($step['type'], $selected))?'selected="selected"':'').' value="'.$step['type'].'">'.$step['label'].'</option>';
							}
						}
					}
					echo '</select>';
					echo '<span>'.$setting['desc'].'</span><script>jQuery("#'.$setting['name'].'").select2();</script></td>';
				break;
				case 'title':
					echo '<th scope="row" class="titledesc"><h3>'.$setting['label'].'</h3></th>';
					echo '<td class="forminp"><hr /></td>';
				break;
				case 'color':
					echo '<th scope="row" class="titledesc"><label>'.$setting['label'].'</label></th>';
					echo '<td class="forminp forminp-color"><input type="text" name="'.$setting['name'].'" class="colorpicker" value="'.(isset($lms_settings[$tab][$setting['name']])?$lms_settings[$tab][$setting['name']]:'').'" />';
					echo '<span>'.$setting['desc'].'</span></td>';
				break;
				case 'hidden':
					echo '<td><input type="hidden" name="'.$setting['name'].'" value="1"/></td>';
				break;
				case 'touchpoint': 
					echo '<th scope="row" class="titledesc"><label>'.$setting['label'].'</label></th>';
					echo '<td class="forminp"><strong>'.__('STUDENT','wplms').'</strong></td>';
					echo '<td class="forminp">';
					echo __('Message','wplms').'&nbsp; <select '.((function_exists('bp_is_active') && bp_is_active('messages'))?'':'disabled').' name="'.$setting['name'].'[student][message]">';
					echo '<option value="0" '.(isset($lms_settings[$tab][$setting['name']]['student']['message'])?selected(0,$lms_settings[$tab][$setting['name']]['student']['message']):'').'>'.__('No','wplms').'</option>';
					echo '<option value="1" '.(isset($lms_settings[$tab][$setting['name']]['student']['message'])?selected(1,$lms_settings[$tab][$setting['name']]['student']['message']):'').'>'.__('Yes','wplms').'</option>';
					echo '</select>';
					echo '&nbsp;&nbsp;'.__('Notification','wplms').'&nbsp; <select '.((function_exists('bp_is_active') && bp_is_active('notifications'))?'':'disabled').' name="'.$setting['name'].'[student][notification]">';
					echo '<option value="0" '.(isset($lms_settings[$tab][$setting['name']]['student']['notification'])?selected(0,$lms_settings[$tab][$setting['name']]['student']['notification']):'').'>'.__('No','wplms').'</option>';
					echo '<option value="1" '.(isset($lms_settings[$tab][$setting['name']]['student']['notification'])?selected(1,$lms_settings[$tab][$setting['name']]['student']['notification']):'').'>'.__('Yes','wplms').'</option>';
					echo '</select>';
					echo '&nbsp;&nbsp;'.__('Email','wplms').'&nbsp; <select name="'.$setting['name'].'[student][email]">';
					echo '<option value="0" '.(isset($lms_settings[$tab][$setting['name']]['student']['email'])?selected(0,$lms_settings[$tab][$setting['name']]['student']['email']):'').'>'.__('No','wplms').'</option>';
					echo '<option value="1" '.(isset($lms_settings[$tab][$setting['name']]['student']['email'])?selected(1,$lms_settings[$tab][$setting['name']]['student']['email']):'').'>'.__('Yes','wplms').'</option>';
					echo '</select>';

					/**/
					if(!empty($lms_settings[$tab][$setting['name']])){
						do_action('wplms_student_touchpoint_setting_html',$lms_settings[$tab][$setting['name']]['student'],$lms_settings[$tab][$setting['name']],$setting['name']);
	
					}
					

					echo '&nbsp;&nbsp;'.sprintf(__('%s Edit Email Template %s','wplms'),'<a href="'.$setting['value']['student'].'" class="button">','</a>');
					echo '</td></tr><tr valign="top">';
					echo '<th scope="row"></th>';
					echo '<td class="forminp"><strong>'.__('INSTRUCTOR','wplms').'</strong></td>';
					echo '<td class="forminp">';
					echo __('Message','wplms').'&nbsp; <select '.((function_exists('bp_is_active') && bp_is_active('messages'))?'':'disabled').' name="'.$setting['name'].'[instructor][message]">';
					echo '<option value="0" '.(isset($lms_settings[$tab][$setting['name']]['instructor']['message'])?selected(0,$lms_settings[$tab][$setting['name']]['instructor']['message']):'').'>'.__('No','wplms').'</option>';
					echo '<option value="1" '.(isset($lms_settings[$tab][$setting['name']]['instructor']['message'])?selected(1,$lms_settings[$tab][$setting['name']]['instructor']['message']):'').'>'.__('Yes','wplms').'</option>';
					echo '</select>';
					echo '&nbsp;&nbsp;'.__('Notification','wplms').'&nbsp; <select '.((function_exists('bp_is_active') && bp_is_active('notifications'))?'':'disabled').' name="'.$setting['name'].'[instructor][notification]">';
					echo '<option value="0" '.(isset($lms_settings[$tab][$setting['name']]['instructor']['notification'])?selected(0,$lms_settings[$tab][$setting['name']]['instructor']['notification']):'').'>'.__('No','wplms').'</option>';
					echo '<option value="1" '.(isset($lms_settings[$tab][$setting['name']]['instructor']['notification'])?selected(1,$lms_settings[$tab][$setting['name']]['instructor']['notification']):'').'>'.__('Yes','wplms').'</option>';
					echo '</select>';
					echo '&nbsp;&nbsp;'.__('Email','wplms').'&nbsp; <select name="'.$setting['name'].'[instructor][email]">';
					echo '<option value="0" '.(isset($lms_settings[$tab][$setting['name']]['instructor']['email'])?selected(0,$lms_settings[$tab][$setting['name']]['instructor']['email']):'').'>'.__('No','wplms').'</option>';
					echo '<option value="1" '.(isset($lms_settings[$tab][$setting['name']]['instructor']['email'])?selected(1,$lms_settings[$tab][$setting['name']]['instructor']['email']):'').'>'.__('Yes','wplms').'</option>';
					echo '</select>';

					if(!empty($lms_settings[$tab][$setting['name']])){
						do_action('wplms_instructor_touchpoint_setting_html',$lms_settings[$tab][$setting['name']]['instructor'],$lms_settings[$tab][$setting['name']],$setting['name']);
					}
					
					echo '&nbsp;&nbsp;'.sprintf(__('%s Edit Email Template %s','wplms'),'<a href="'.$setting['value']['instructor'].'" class="button">','</a>');
					echo '</td>
						<tr><td colspan="3"><hr></td>';
				break;
				case 'touchpoint_admin': 
					echo '<th scope="row" class="titledesc"><label>'.$setting['label'].'</label></th>';
					echo '<td class="forminp"><strong>'.__('INSTRUCTOR','wplms').'</strong></td>';
					echo '<td class="forminp">';
					echo __('Message','wplms').'&nbsp; <select '.((function_exists('bp_is_active') && bp_is_active('messages'))?'':'disabled').' name="'.$setting['name'].'[instructor][message]">';
					echo '<option value="0" '.(isset($lms_settings[$tab][$setting['name']]['instructor']['message'])?selected(0,$lms_settings[$tab][$setting['name']]['instructor']['message']):'').'>'.__('No','wplms').'</option>';
					echo '<option value="1" '.(isset($lms_settings[$tab][$setting['name']]['instructor']['message'])?selected(1,$lms_settings[$tab][$setting['name']]['instructor']['message']):'').'>'.__('Yes','wplms').'</option>';
					echo '</select>';
					echo '&nbsp;&nbsp;'.__('Notification','wplms').'&nbsp; <select '.((function_exists('bp_is_active') && bp_is_active('notifications'))?'':'disabled').' name="'.$setting['name'].'[instructor][notification]">';
					echo '<option value="0" '.(isset($lms_settings[$tab][$setting['name']]['instructor']['notification'])?selected(0,$lms_settings[$tab][$setting['name']]['instructor']['notification']):'').'>'.__('No','wplms').'</option>';
					echo '<option value="1" '.(isset($lms_settings[$tab][$setting['name']]['instructor']['notification'])?selected(1,$lms_settings[$tab][$setting['name']]['instructor']['notification']):'').'>'.__('Yes','wplms').'</option>';
					echo '</select>';
					echo '&nbsp;&nbsp;'.__('Email','wplms').'&nbsp; <select name="'.$setting['name'].'[instructor][email]">';
					echo '<option value="0" '.(isset($lms_settings[$tab][$setting['name']]['instructor']['email'])?selected(0,$lms_settings[$tab][$setting['name']]['instructor']['email']):'').'>'.__('No','wplms').'</option>';
					echo '<option value="1" '.(isset($lms_settings[$tab][$setting['name']]['instructor']['email'])?selected(1,$lms_settings[$tab][$setting['name']]['instructor']['email']):'').'>'.__('Yes','wplms').'</option>';
					echo '</select>';
					echo '&nbsp;&nbsp;'.sprintf(__('%s Edit Email Template %s','wplms'),'<a href="'.$setting['value']['instructor'].'" class="button">','</a>');
					echo '</td></tr><tr valign="top">';
					echo '<th scope="row"></th>';
					echo '<td class="forminp"><strong>'.__('ADMINISTRATOR','wplms').'</strong></td>';
					echo '<td class="forminp">';
					echo __('Message','wplms').'&nbsp; <select '.((function_exists('bp_is_active') && bp_is_active('messages'))?'':'disabled').' name="'.$setting['name'].'[admin][message]">';
					echo '<option value="0" '.(isset($lms_settings[$tab][$setting['name']]['admin']['message'])?selected(0,$lms_settings[$tab][$setting['name']]['admin']['message']):'').'>'.__('No','wplms').'</option>';
					echo '<option value="1" '.(isset($lms_settings[$tab][$setting['name']]['admin']['message'])?selected(1,$lms_settings[$tab][$setting['name']]['admin']['message']):'').'>'.__('Yes','wplms').'</option>';
					echo '</select>';
					echo '&nbsp;&nbsp;'.__('Notification','wplms').'&nbsp; <select '.((function_exists('bp_is_active') && bp_is_active('notifications'))?'':'disabled').' name="'.$setting['name'].'[admin][notification]">';
					echo '<option value="0" '.(isset($lms_settings[$tab][$setting['name']]['admin']['notification'])?selected(0,$lms_settings[$tab][$setting['name']]['admin']['notification']):'').'>'.__('No','wplms').'</option>';
					echo '<option value="1" '.(isset($lms_settings[$tab][$setting['name']]['admin']['notification'])?selected(1,$lms_settings[$tab][$setting['name']]['admin']['notification']):'').'>'.__('Yes','wplms').'</option>';
					echo '</select>';
					echo '&nbsp;&nbsp;'.__('Email','wplms').'&nbsp; <select name="'.$setting['name'].'[admin][email]">';
					echo '<option value="0" '.(isset($lms_settings[$tab][$setting['name']]['admin']['email'])?selected(0,$lms_settings[$tab][$setting['name']]['admin']['email']):'').'>'.__('No','wplms').'</option>';
					echo '<option value="1" '.(isset($lms_settings[$tab][$setting['name']]['admin']['email'])?selected(1,$lms_settings[$tab][$setting['name']]['admin']['email']):'').'>'.__('Yes','wplms').'</option>';
					echo '</select>';
					echo '&nbsp;&nbsp;'.sprintf(__('%s Edit Email Template %s','wplms'),'<a href="'.$setting['value']['admin'].'" class="button">','</a>');
					echo '</td>
						<tr><td colspan="3"><hr></td>';
				break;
				default:
					echo '<th scope="row" class="titledesc"><label>'.$setting['label'].'</label></th>';
					echo '<td class="forminp"><input type="text" name="'.$setting['name'].'" value="'.(isset($lms_settings[$tab][$setting['name']])?$lms_settings[$tab][$setting['name']]:(isset($setting['default'])?$setting['default']:'')).'" />';
					echo '<span>'.$setting['desc'].'</span></td>';
				break;
			}
			
			echo '</tr>';
		}
		echo '</tbody>
		</table>';
		if(!empty($settings))
			echo '<input type="submit" name="save" value="'.__('Save Settings','wplms').'" class="button button-primary" /></form>';
	}	

	// Functioning ===== of SETTINGS
	function lms_resolve_adhoc_function(){
		if ( !isset($_POST['_vibe_admin_adhoc']) || !wp_verify_nonce($_POST['_vibe_admin_adhoc'],'vibe_admin_adhoc') )
		 return;
		else{
			do_action('wplms_admin_custom_admin_process');
			if(isset($_POST['set_field'])){
				$id=$_POST['id'];
				$field_name=$_POST['field_name'];
				$field_value=$_POST['field_value'];
				if(isset($id)){
					if(update_post_meta($id,$field_name,$field_value))
						echo '<div id="moderated" class="updated below-h2"><p>'.__('Field Value Changed','wplms').'</p></div>';
					else
						echo '<div id="moderated" class="error below-h2"><p>'.__('Error Field value not changed','wplms').'</p></div>';
				}else{
					echo '<div id="moderated" class="error below-h2"><p>'.__('Error Field value not entered','wplms').'</p></div>';
				}
			}
			if(isset($_POST['set_field_for_student'])){
				$student_id=$_POST['student_id'];
				$field_name=$_POST['field_name_student'];
				$field_value=$_POST['field_value_student'];
				if(strpos($field_value,'|')){
					$field_value=explode('|',$field_value);
				}

				if(isset($student_id)){
					if(update_user_meta($student_id,$field_name,$field_value))
						echo '<div id="moderated" class="updated below-h2"><p>'.__('Student Value Changed','wplms').'</p></div>';
					else
						echo '<div id="moderated" class="error below-h2"><p>'.__('Student value not changed','wplms').'</p></div>';
				}else{
					echo '<div id="moderated" class="error below-h2"><p>'.__('Student value not entered','wplms').'</p></div>';
				}
			}

			if(isset($_POST['set_field_for_option'])){
				$option_name=$_POST['option_name'];
				$option_value=$_POST['option_value'];
				if(strpos($option_value,'|')){
					$option_value=explode('|',$option_value);
				}

				if(isset($option_name)){
					if(update_option($option_name,$option_value))
						echo '<div id="moderated" class="updated below-h2"><p>'.__('Option Value Changed','wplms').'</p></div>';
					else
						echo '<div id="moderated" class="error below-h2"><p>'.__('There was a problem','wplms').'</p></div>';
				}else{
					echo '<div id="moderated" class="error below-h2"><p>'.__('Option value not entered','wplms').'</p></div>';
				}
			}

			//pakode
			if(isset($_POST['set_field_for_post'])){
				$id=$_POST['post_id'];
				$field_name=$_POST['post_field_name'];
				$field_value=$_POST['post_field_value'];
				if(isset($id)){
					$post_array = array(
				      'ID'           => $id,
				      $field_name   => $field_value
				  	);

					$update = wp_update_post($post_array);
				
					if(!is_wp_error($update)){
						echo '<div id="moderated" class="updated below-h2"><p>'.__('Post Field Value Changed','wplms').'</p></div>';
					}else{
						echo '<div id="moderated" class="error below-h2"><p>'.__('Error Post Field value not changed','wplms').'</p></div>';
					}
				}else{
					echo '<div id="moderated" class="error below-h2"><p>'.__('Error Post Field value not entered','wplms').'</p></div>';
				}
			}


		}
	}


	function lms_commission_history(){

	}

	function lms_import_export(){
		$url='';
		include_once('class.export.php');
		include_once('class.import.php');
		$wplms_export= new wplms_export();
		$wplms_import = new wplms_import();
		if(isset($_POST['export'])){
			$url=$wplms_export->generate_report();
		}

		echo '<h3>'.__('Import/Export WPLMS Elements','wplms').'</h3>';
		echo '<p>'.__('Download and upload in CSV format. Import/Export WPLMS elements with user statuses: Courses, Quizzes, Units, Assignments, Questions and Events.','wplms').'</p>';
		
		echo '<hr/><h3>'.__('EXPORT SETTINGS','wplms').'</h3>';

		$wplms_export->generate_form($url);

		echo '<hr/>';
		echo '<div style="background:#FFF;display:inline-block;padding:20px 30px 30px; margin:30px 0;border-radius:2px;">';
		if(isset($_POST['import'])){
			if(current_user_can('manage_options'))
				$wplms_import->process_upload();
		}
			$wplms_import->generate_form();
		echo '</div>';	
	}

	function lms_touch_points(){
		echo '<h3>'.__('User Touch Points','wplms').'</h3>';
		echo '<p>'.__('Set touch points for Students and Instructors in WPLMS. Connect with Student or Instructor via following touch points','wplms').' <a href="http://vibethemes.com/documentation/wplms/knowledge-base/touch-points-emails-messages-notifications/">'.__('Learn more','wplms').'</a></p>';
		
			$this->settings = $this->get_touch_points();
			$this->lms_settings_generate_form('touch',$this->settings);
	}

	function live(){
		echo '<h3>'.__('Live','wplms').'</h3>';
		echo '<p>'.__('WPLMS Live settings and configuration','wplms').'</p>';
		$template_array = apply_filters('wplms_lms_live_tabs',array(
			'addons'=> __('Live addons','wplms'),
		));
		echo '<ul class="subsubsub">';
		foreach($template_array as $k=>$value){
			if(empty($_GET['sub']) && empty($current)){
				$current = $k;
			}else if(!empty($_GET['sub']) && empty($current)){
				$current = $_GET['sub'];
			}
			echo '<li><a href="?page=lms-settings&tab=live&sub='.$k.'" '.(($k == $current)?'class="current"':'').'>'.$value.'</a>  &#124; </li>';
		}
		echo '</ul><hr class="clear"/>';
		if(!isset($_GET['sub'])){$_GET['sub']='';}
		switch($_GET['sub']){
			case 'addons':
				$this->live_addons();
			break;
			default:
				$function_name = apply_filters('lms_live_settings_tab',$_GET['sub']);

				if(!empty($_GET['sub']) && function_exists($function_name) && $_GET['sub'] != 'addons'){
					$function_name();
				}else{
					$this->live_addons();
				}
			break;
		}

		
	}

	function live_addons(){
		$addons = apply_filters('wplms_live_addons',array(
				'wplms_chat' =>array(
					'label'=> __('Chat','wplms'),
					'sub'=> __('Live Chat in WPLMS.','wplms'),
					'icon'=> '<span class="dashicons dashicons-portfolio"></span>',
					'requires'=> '',
					'license_key'=>'wplms_chat_license_key',
					'link' => 'http://wplms.io/downloads/wplms_chat',
					'extra'=>array('Course Chat','Personal Chat', 'Pre-Sale Chat'),
					'activated'=> (is_plugin_active('wplms-chat/wplms-chat.php')?true:false),
					'price'=>'BUY $29',
					'class'=>'featured'
				),
				'wplms_push_notifications' =>array(
					'label'=> __('Push Notifications','wplms'),
					'sub'=> __('Push Notifications for WPLMS.','wplms'),
					'icon'=> '<span class="dashicons dashicons-portfolio"></span>',
					'requires'=> '',
					'license_key'=>'wplms_push_notifications_license_key',
					'link' => 'https://wplms.io/downloads/wplms-push-notifications/',
					'extra'=>array('Touch Point','Personalised & Group Notifications', 'Reminders'),
					'activated'=> (is_plugin_active('wplms-push-notifications/wplms-push-notifications.php')?true:false),
					'price'=>'Buy $19',
					'class'=>'featured'
				),
				'wplms_phone_auth' =>array(
					'label'=> __('Wplms Phone Auth','wplms'),
					'sub'=> __('Integrates Firebase phone verification system with wplms.','wplms'),
					'icon'=> '<span class="dashicons dashicons-portfolio"></span>',
					'requires'=> '',
					'license_key'=>'wplms_phone_auth_license_key',
					'link' => 'http://wplms.io/downloads/wplms-phone-auth',
					'extra'=>array('Wplms Phone Auth','Embed'),
					'activated'=> (is_plugin_active('wplms-phone-auth/wplms-phone-auth.php')?true:false),
					'price'=>'$19',
					'class'=>'featured'
				),
				'wplms_whiteboard' =>array(
					'label'=> __('White Board','wplms'),
					'sub'=> __('White boards in WPLMS.','wplms'),
					'icon'=> '<span class="dashicons dashicons-portfolio"></span>',
					'requires'=> '',
					'license_key'=>'wplms_whiteboard_license_key',
					'link' => 'http://wplms.io/downloads/wplms_chat',
					'extra'=>array('White Boards','Embed'),
					'activated'=> (is_plugin_active('wplms-whiteboard/wplms-whiteboard.php')?true:false),
					'price'=>'COMING SOON',
					'class'=>'featured'
				),
				'wplms_video_conference' =>array(
					'label'=> __('Video Conference','wplms'),
					'sub'=> __('Video Conferencing in WPLMS.','wplms'),
					'icon'=> '<span class="dashicons dashicons-portfolio"></span>',
					'requires'=> '',
					'license_key'=>'wplms_videoconference_license_key',
					'link' => 'http://wplms.io/downloads/wplms_chat',
					'extra'=>array('White Boards','Embed'),
					'activated'=> (is_plugin_active('wplms-videoconference/wplms-videoconference.php')?true:false),
					'price'=>'COMING SOON',
					'class'=>'featured'
				),

			));
		
		echo '<div class="wplms_addons">';
		foreach($addons as $key=>$addon){ 
			if(!empty($addon) && !empty($addon['label'])){

			$class = apply_filters('wplms_addon_class','',$addon);

			?>
				<div class="wplms_addon_block">
					<div class="inside <?php echo $class.' '.(($addon['activated'])?'active':''); ?>">
						<?php echo (empty($addon['price'])?'<span class="free">FREE</span>':'<span class="free premium">'.$addon['price'].'</span>'); ?>
						<h3 class=""><?php echo $addon['label']; ?><span><?php echo $addon['sub']; ?></span></h3>
						<?php 
						if(!empty($addon['extra'])){
							if(is_array($addon['extra'])){
								echo '<ul>';
								foreach($addon['extra'] as $ex){
									echo '<li>'.$ex.'</li>';
								}
								echo '</ul>';
							}else{
								echo $addon['extra'];
							}
						}
						if(!empty($addon['license_key']) && $addon['activated']){
							$val = get_option($addon['license_key']);
							?>
							<div class="activate_license">
                                <form action="<?php  echo admin_url( 'admin.php?page=lms-settings&tab=live'); ?>" method="post">
                                    <input type="text" id="<?php echo $addon['license_key']; ?>" name="license_key" class="vibe_license_key" value="<?php echo $val ?>" placeholder="<?php _e('Enter License Key','wplms'); ?>" />
                                    <?php 
                                    if(!empty($val) && strpos($class,'invalid') === false){    ?>
                                    <input type="submit" class="button primary" name="<?php echo $addon['license_key']; ?>" value="Deactivate" />
                                    <?php
                                    }else{
                                        ?>
                                    <input type="submit" class="button primary" name="<?php echo $addon['license_key']; ?>" value="Activate" />
                                    <?php
                                    }
                                    wp_nonce_field( $key, $key);
                                    ?>
                                </form>
                            </div>
							<a target="_blank" class="button button-primary activate_license_toggle"><?php _e('License Key','wplms'); ?></a>
							<?php
						}
						?>
						<a href="<?php echo $addon['link']; ?>" target="_blank" class="button"><?php _e('Learn more','wplms'); ?></a>
					</div>
				</div>
		<?php
			}
		}
		?>
		</div>
		<div class="clear">	</div>
		</div>
		<?php
	}
	static function get_touch_points(){
		$settings=array(
				'course_announcement'=>array(
									'label' => __('Announcements','wplms'),
									'name' =>'course_announcement',
									'value' => array(
										'student' => admin_url('edit.php?taxonomy=bp-email-type&term=student_course_announcement&post_type=bp-email'),
										'instructor' => admin_url('edit.php?taxonomy=bp-email-type&term=instructor_course_announcement&post_type=bp-email'),
									),
									'type' => 'touchpoint',
									'hook' => 'wplms_dashboard_course_announcement',
									'params'=>4,
								),
				'course_news'=>array(
									'label' => __('News','wplms'),
									'name' =>'course_news',
									'value' => array(
										'student' => admin_url('edit.php?taxonomy=bp-email-type&term=student_course_news&post_type=bp-email'),
										'instructor' => admin_url('edit.php?taxonomy=bp-email-type&term=instructor_course_news&post_type=bp-email'),
									),
									'type' => 'touchpoint',
									'hook' => 'publish_news',
									'params'=>2,
								),
				'course_subscribed'=>array(
									'label' => __('Course Subscribed','wplms'),
									'name' =>'course_subscribed',
									'value' => array(
										'student' => admin_url('edit.php?taxonomy=bp-email-type&term=student_course_subscribed&post_type=bp-email'),
										'instructor' => admin_url('edit.php?taxonomy=bp-email-type&term=instructor_course_subscribed&post_type=bp-email'),
									),
									'type' => 'touchpoint',
									'hook' => 'wplms_course_subscribed',
									'params'=>3,
								),
				'course_added'=>array(
									'label' => __('User added to Course','wplms'),
									'name' =>'course_added',
									'value' => array(
										'student' => admin_url('edit.php?taxonomy=bp-email-type&term=student_course_added&post_type=bp-email'),
										'instructor' => admin_url('edit.php?taxonomy=bp-email-type&term=instructor_course_added&post_type=bp-email'),
									),
									'type' => 'touchpoint',
									'hook' => 'wplms_bulk_action',
									'params'=>3,
								),
				'course_start'=>array(
									'label' => __('User Starts a Course ','wplms'),
									'name' =>'course_start',
									'value' => array(
										'student' => admin_url('edit.php?taxonomy=bp-email-type&term=student_course_start&post_type=bp-email'),
										'instructor' => admin_url('edit.php?taxonomy=bp-email-type&term=instructor_course_start&post_type=bp-email'),
									),
									'type' => 'touchpoint',
									'hook' => 'wplms_start_course',
									'params'=> 2,
								),

				'course_certificate'=>array(
									'label' => __('Course Certificate','wplms'),
									'name' =>'course_certificate',
									'value' => array(
										'student' => admin_url('edit.php?taxonomy=bp-email-type&term=student_course_certificate&post_type=bp-email'),
										'instructor' => admin_url('edit.php?taxonomy=bp-email-type&term=instructor_course_certificate&post_type=bp-email'),
									),
									'type' => 'touchpoint',
									'hook' => 'wplms_certificate_earned',
									'params'=>4,
								),
				'course_badge'=>array(
									'label' => __('Course Badge','wplms'),
									'name' =>'course_badge',
									'value' => array(
										'student' => admin_url('edit.php?taxonomy=bp-email-type&term=student_course_badge&post_type=bp-email'),
										'instructor' => admin_url('edit.php?taxonomy=bp-email-type&term=instructor_course_badge&post_type=bp-email'),
									),
									'type' => 'touchpoint',
									'hook' => 'wplms_badge_earned',
									'params'=>4,
								),
				
				'course_reset'=>array(
									'label' => __('Course Reset by Instructor','wplms'),
									'name' =>'course_reset',
									'value' => array(
										'student' => admin_url('edit.php?taxonomy=bp-email-type&term=student_course_reset&post_type=bp-email'),
										'instructor' => admin_url('edit.php?taxonomy=bp-email-type&term=instructor_course_reset&post_type=bp-email'),
									),
									'type' => 'touchpoint',
									'hook' => 'wplms_course_reset',
									'params'=>2,
								),
				'course_retake'=>array(
									'label' => __('Course Retake by User','wplms'),
									'name' =>'course_retake',
									'value' => array(
										'student' => admin_url('edit.php?taxonomy=bp-email-type&term=student_course_retake&post_type=bp-email'),
										'instructor' => admin_url('edit.php?taxonomy=bp-email-type&term=instructor_course_retake&post_type=bp-email'),
									),
									'type' => 'touchpoint',
									'hook' => 'wplms_course_retake',
									'params'=>2,
								),
				'course_submit'=>array(
									'label' => __('Course Submit','wplms'),
									'name' =>'course_submit',
									'value' => array(
										'student' => admin_url('edit.php?taxonomy=bp-email-type&term=student_course_submit&post_type=bp-email'),
										'instructor' => admin_url('edit.php?taxonomy=bp-email-type&term=instructor_course_submit&post_type=bp-email'),
									),
									'type' => 'touchpoint',
									'hook' => 'wplms_submit_course',
									'params'=>2,
								),
				'course_evaluation'=>array(
									'label' => __('Course Evaluation','wplms'),
									'name' =>'course_evaluation',
									'value' => array(
										'student' => admin_url('edit.php?taxonomy=bp-email-type&term=student_course_evaluation&post_type=bp-email'),
										'instructor' => admin_url('edit.php?taxonomy=bp-email-type&term=instructor_course_evaluation&post_type=bp-email'),
									),
									'type' => 'touchpoint',
									'hook' => 'wplms_evaluate_course',
									'params'=>3,
								),
				'course_review'=>array(
									'label' => __('Course Reviews','wplms'),
									'name' =>'course_review',
									'value' => array(
										'student' => admin_url('edit.php?taxonomy=bp-email-type&term=student_course_review&post_type=bp-email'),
										'instructor' => admin_url('edit.php?taxonomy=bp-email-type&term=instructor_course_review&post_type=bp-email'),
									),
									'type' => 'touchpoint',
									'hook' => 'wplms_course_review',
									'params'=>3,
								),
				'course_unsubscribe'=>array(
									'label' => __('Unsubscribe Course','wplms'),
									'name' =>'course_unsubscribe',
									'value' => array(
										'student' => admin_url('edit.php?taxonomy=bp-email-type&term=student_course_unsubscribe&post_type=bp-email'),
										'instructor' => admin_url('edit.php?taxonomy=bp-email-type&term=instructor_course_unsubscribe&post_type=bp-email'),
									),
									'type' => 'touchpoint',
									'hook' => 'wplms_course_unsubscribe',
									'params'=>3,
								),
				'unit_complete'=>array(
									'label' => __('Unit marked complete by User','wplms'),
									'name' =>'unit_complete',
									'value' => array(
										'student' => admin_url('edit.php?taxonomy=bp-email-type&term=student_unit_complete&post_type=bp-email'),
										'instructor' => admin_url('edit.php?taxonomy=bp-email-type&term=instructor_unit_complete&post_type=bp-email'),
									),
									'type' => 'touchpoint',
									'hook' => 'wplms_unit_complete',
									'params'=>4,
								),
				'unit_instructor_complete'=>array(
									'label' => __('Unit marked complete by Instructor for Student','wplms'),
									'name' =>'unit_instructor_complete',
									'value' => array(
										'student' => admin_url('edit.php?taxonomy=bp-email-type&term=student_unit_instructor_complete&post_type=bp-email'),
										'instructor' => admin_url('edit.php?taxonomy=bp-email-type&term=instructor_unit_instructor_complete&post_type=bp-email'),
									),
									'type' => 'touchpoint',
									'hook' => 'wplms_unit_instructor_complete',
									'params'=>4,
								),
				'unit_instructor_uncomplete'=>array(
									'label' => __('Unit marked incomplete by Instructor for Student','wplms'),
									'name' =>'unit_instructor_uncomplete',
									'value' => array(
										'student' => admin_url('edit.php?taxonomy=bp-email-type&term=student_unit_instructor_uncomplete&post_type=bp-email'),
										'instructor' => admin_url('edit.php?taxonomy=bp-email-type&term=instructor_unit_instructor_uncomplete&post_type=bp-email'),
									),
									'type' => 'touchpoint',
									'hook' => 'wplms_unit_instructor_uncomplete',
									'params'=>4,
								),
				'unit_comment'=>array(
									'label' => __('Unit comment added by User','wplms'),
									'name' =>'unit_comment',
									'value' => array(
										'student' => admin_url('edit.php?taxonomy=bp-email-type&term=student_unit_comment&post_type=bp-email'),
										'instructor' => admin_url('edit.php?taxonomy=bp-email-type&term=instructor_unit_comment&post_type=bp-email'),
									),
									'type' => 'touchpoint',
									'hook' => 'wplms_course_unit_comment',
									'params'=>4,
								),
				'start_quiz'=>array(
									'label' => __('Quiz Start by user','wplms'),
									'name' =>'start_quiz',
									'value' => array(
										'student' => admin_url('edit.php?taxonomy=bp-email-type&term=student_start_quiz&post_type=bp-email'),
										'instructor' => admin_url('edit.php?taxonomy=bp-email-type&term=instructor_start_quiz&post_type=bp-email'),
									),
									'type' => 'touchpoint',
									'hook' => 'wplms_start_quiz',
									'params'=>2,
								),
				'quiz_submit'=>array(
									'label' => __('Quiz Submitted by user','wplms'),
									'name' =>'quiz_submit',
									'value' => array(
										'student' => admin_url('edit.php?taxonomy=bp-email-type&term=student_quiz_submit&post_type=bp-email'),
										'instructor' => admin_url('edit.php?taxonomy=bp-email-type&term=instructor_quiz_submit&post_type=bp-email'),
									),
									'type' => 'touchpoint',
									'hook' => 'wplms_submit_quiz',
									'params'=>2,
								),
				'quiz_reset'=>array(
									'label' => __('Quiz Reset by Instructor','wplms'),
									'name' =>'quiz_reset',
									'value' => array(
										'student' => admin_url('edit.php?taxonomy=bp-email-type&term=student_quiz_reset&post_type=bp-email'),
										'instructor' => admin_url('edit.php?taxonomy=bp-email-type&term=instructor_quiz_reset&post_type=bp-email'),
									),
									'type' => 'touchpoint',
									'hook' => 'wplms_quiz_reset',
									'params'=>2,
								),
				'quiz_retake'=>array(
									'label' => __('Quiz Retake by User','wplms'),
									'name' =>'quiz_retake',
									'value' => array(
										'student' => admin_url('edit.php?taxonomy=bp-email-type&term=student_quiz_retake&post_type=bp-email'),
										'instructor' => admin_url('edit.php?taxonomy=bp-email-type&term=instructor_quiz_retake&post_type=bp-email'),
									),
									'type' => 'touchpoint',
									'hook' => 'wplms_quiz_retake',
									'params'=>2,
								),
				'quiz_evaluation'=>array(
									'label' => __('Quiz Evaluation','wplms'),
									'name' =>'quiz_evaluation',
									'value' => array(
										'student' => admin_url('edit.php?taxonomy=bp-email-type&term=student_quiz_evaluation&post_type=bp-email'),
										'instructor' => admin_url('edit.php?taxonomy=bp-email-type&term=instructor_quiz_evaluation&post_type=bp-email'),
									),
									'type' => 'touchpoint',
									'hook' => 'wplms_evaluate_quiz',
									'params'=>4,
								),
				'question_feedback_given'=>array(
									'label' => __('Question feedback given','wplms'),
									'name' =>'question_feedback_given',
									'value' => array(
										'student' => admin_url('edit.php?taxonomy=bp-email-type&term=instructor_question_feedback_email&post_type=bp-email'),
										'instructor' => admin_url('edit.php?taxonomy=bp-email-type&term=student_question_feedback_email&post_type=bp-email'),
									),
									'type' => 'touchpoint',
									'hook' => 'question_feedback_given',
									'params'=>3,
								),
				'start_assignment'=>array(
									'label' => __('Assignment Start by user','wplms'),
									'name' =>'start_assignment',
									'value' => array(
										'student' => admin_url('edit.php?taxonomy=bp-email-type&term=student_start_assignment&post_type=bp-email'),
										'instructor' => admin_url('edit.php?taxonomy=bp-email-type&term=instructor_start_assignment&post_type=bp-email'),
									),
									'type' => 'touchpoint',
									'hook' => 'wplms_start_assignment',
									'params'=>2,
								),
				'assignment_submit'=>array(
									'label' => __('Assignment Submitted by user','wplms'),
									'name' =>'assignment_submit',
									'value' => array(
										'student' => admin_url('edit.php?taxonomy=bp-email-type&term=student_assignment_submit&post_type=bp-email'),
										'instructor' => admin_url('edit.php?taxonomy=bp-email-type&term=instructor_assignment_submit&post_type=bp-email'),
									),
									'type' => 'touchpoint',
									'hook' => 'wplms_submit_assignment',
									'params'=>2,
								),
				'assignment_evaluation'=>array(
									'label' => __('Assignment Evaluation','wplms'),
									'name' =>'assignment_evaluation',
									'value' => array(
										'student' => admin_url('edit.php?taxonomy=bp-email-type&term=student_assignment_evaluation&post_type=bp-email'),
										'instructor' => admin_url('edit.php?taxonomy=bp-email-type&term=instructor_assignment_evaluation&post_type=bp-email'),
									),
									'type' => 'touchpoint',
									'hook' => 'wplms_evaluate_assignment',
									'params'=>4,
								),
				'assignment_reset'=>array(
										'label' => __('Assignment Reset by Instructor','wplms'),
										'name' =>'assignment_reset',
										'value' => array(
											'student' => admin_url('edit.php?taxonomy=bp-email-type&term=student_assignment_reset&post_type=bp-email'),
											'instructor' => admin_url('edit.php?taxonomy=bp-email-type&term=instructor_assignment_reset&post_type=bp-email'),
										),
										'type' => 'touchpoint',
										'hook' => 'wplms_assignment_reset',
										'params'=>2,
								),
				'user_course_application'=> array(
									'label' => __('Student applied for Course','wplms'),
									'name' =>'user_course_application',
									'value' => array(
										'student' => admin_url('edit.php?taxonomy=bp-email-type&term=student_user_course_application&post_type=bp-email'),
										'instructor' => admin_url('edit.php?taxonomy=bp-email-type&term=instructor_user_course_application&post_type=bp-email'),
									),
									'type' => 'touchpoint',
									'hook' => 'wplms_user_course_application',
									'params'=>2,
								),
				'manage_user_application'=> array(
									'label' => __('Instructor approves/rejects user application','wplms'),
									'name' =>'manage_user_application',
									'value' => array(
										'student' => admin_url('edit.php?taxonomy=bp-email-type&term=student_manage_user_application&post_type=bp-email'),
										'instructor' => admin_url('edit.php?taxonomy=bp-email-type&term=instructor_manage_user_application&post_type=bp-email'),
									),
									'type' => 'touchpoint',
									'hook' => 'wplms_manage_user_application',
									'params'=>3,
								),
				'course_go_live'=> array(
									'label' => __('Instructor Publishes a Course or Sends for Approval','wplms'),
									'name' =>'course_go_live',
									'value' => array(
										'instructor' => admin_url('edit.php?taxonomy=bp-email-type&term=instructor_course_go_live&post_type=bp-email'),
										'admin' => admin_url('edit.php?taxonomy=bp-email-type&term=admin_course_go_live&post_type=bp-email'),
									),
									'type' => 'touchpoint_admin',
									'hook' => 'wplms_course_go_live',
									'params'=>2,
								),
							);
		return apply_filters('wplms_touch_points',$settings);
	}
	function lms_emails(){
		echo '<h3>'.__('Email Settings','wplms').'</h3>';
		echo '<p>'.__('Configure email template for Emails.','wplms').'</p>';

		$template_array = apply_filters('wplms_email_template_array',array(
			''=> __('Email Options','wplms'),
			'schedule'=> __('Email Schedule','wplms'),
			'scheduled_emails'=> __('Scheduled Emails','wplms'),
			));
		echo '<ul class="subsubsub">';
		foreach($template_array as $k=>$value){
			echo '<li><a href="?page=lms-settings&tab=emails&sub='.$k.'" '.((isset($_GET['sub']) && $k == $_GET['sub'])?'class="current"':'').'>'.$value.'</a> '.(($k=='template')?'':' &#124; ').' </li>';
		}
		echo '</ul>';
		if(!isset($_GET['sub'])){
			$_GET['sub'] =' ';
		}
		switch($_GET['sub']){
			case 'schedule':
				$this->email_schedule();
			break;
			case 'scheduled_emails':
				$this->scheduled_emails();
			break;
			default:
				$this->lms_email_settings();
			break;
		}
	}

	function lms_email_settings(){

			$settings=array(
						
						array(
							'label' => __('FROM "Name"','wplms'),
							'name' =>'from_name',
							'type' => 'text',
							'desc' => __('Name from which the email will be sent','wplms')
						),
						array(
							'label' => __('FROM "Email"','wplms'),
							'name' =>'from_email',
							'type' => 'text',
							'desc' => __('Email from which the email will be sent','wplms')
						),
						array(
							'label' => __('Contact Form email','wplms'),
							'type' => 'link',
							'value'=>admin_url('edit.php?taxonomy=bp-email-type&term=wplms_contact_form_email&post_type=bp-email'),
							'button_label'=> _x('Edit email template','lms settings button','wplms'),
							'desc' => __('Contact Form email sent when using contact form shortcode.','wplms')
						),
						array(
							'label' => __('Edit Activation email','wplms'),
							'type' => 'link',
							'value'=>admin_url('edit.php?taxonomy=bp-email-type&term=core-user-registration&post_type=bp-email'),
							'button_label'=> _x('Edit email template','lms settings button','wplms'),
							'desc' => __('Activation email sent by BuddyPress on user registration.','wplms')
						),
						array(
							'label' => __('Edit Forgot Password email','wplms'),
							'type' => 'link',
							'value'=>admin_url('edit.php?taxonomy=bp-email-type&term=wplms_forgot_password&post_type=bp-email'),
							'button_label'=> _x('Edit email template','lms settings button','wplms'),
							'desc' => __('Forgot password email sent by WordPress when user clicks on forgot password link.','wplms')
						),
						array(
							'label' => __('Enable welcome email','wplms'),
							'name' =>'enable_welcome_email',
							'type' => 'checkbox',
							'desc' => __('Welcome email is sent when user activates his account or logs in the WPLMS site for the first time.','wplms')
						),
				);
			$this->settings = $settings =  apply_filters('wplms_email_settings',$settings);
			$this->lms_settings_generate_form('email_settings',$settings);
	}
	
	function email_schedule(){
		$settings=array(
						array(
							'label' => sprintf(__('Schedule Drip Feed Email %s Edit Email %s','wplms'),'<a href="'.admin_url('edit.php?taxonomy=bp-email-type&term=wplms_drip_mail&post_type=bp-email').'" class="button">','</a>'),
							'name' =>'drip_schedule',
							'type' => 'title'
						),
						array(
							'label' => __('Enable Drip Feed Email','wplms'),
							'name' =>'drip',
							'type' => 'select',
							'options'=>array(
								'no' => __('No','wplms'),
								'yes' => __('Yes','wplms'),
								),
							'desc' => __('Email students when the drip feed units are available','wplms')
						),
						array(
							'label' => __('Schedule Email','wplms'),
							'name' =>'drip_schedule',
							'type' => 'select',
							'options'=>array(
								'24' => __('Before 24 Hours of unit availability','wplms'),
								'12' => __('Before 12 Hours of unit availability','wplms'),
								'6' => __('Before 6 Hours of unit availability','wplms'),
								'1' => __('Before 1 Hours of unit availability','wplms'),
								'0' => __('When Unit is available','wplms'),
							),
							'desc' => __('Accuracy of email depends upon site traffic and resources.','wplms')
						),

						array(
							'label' => sprintf(__('Schedule Course Expiry Email %s Edit Email %s','wplms'),'<a href="'.admin_url('edit.php?taxonomy=bp-email-type&term=wplms_expire_mail&post_type=bp-email').'" class="button">','</a>'),
							'name' =>'e_s',
							'type' => 'title'
						),
						array(
							'label' => __('Enable Course Expire Email','wplms'),
							'name' =>'expire',
							'type' => 'select',
							'options'=>array(
								'no' => __('No','wplms'),
								'yes' => __('Yes','wplms'),
								),
							'desc' => __('Email students when the course expires','wplms')
						),
						array(
							'label' => __('Schedule Email','wplms'),
							'name' =>'expire_schedule',
							'type' => 'select',
							'options'=>apply_filters('wplms_course_expire_schedule_options',array(
								'24' => __('Before 24 Hours of Course expiry','wplms'),
								'12' => __('Before 12 Hours of Course expiry','wplms'),
								'6' => __('Before 6 Hours of Course expiry','wplms'),
								'1' => __('Before 1 Hours of Course expiry','wplms'),
								'0' => __('When Course expires','wplms'),
							)),
							'desc' => __('Accuracy of email depends upon site traffic and resources.','wplms')
						),
						
						array(
							'label' => sprintf(__('Schedule Inactive Users Email %s Edit Email %s','wplms'),'<a href="'.admin_url('edit.php?taxonomy=bp-email-type&term=wplms_inactive_user&post_type=bp-email').'" class="button">','</a>'),
							'name' =>'inactive_schedule',
							'type' => 'title'
						),
						array(
							'label' => __('Enable Inactive Users Email','wplms'),
							'name' =>'inactive',
							'type' => 'select',
							'options'=>array(
								'no' => __('No','wplms'),
								'yes' => __('Yes','wplms'),
								),
							'desc' => __('Email users when they are inactive on the website for a specifc time period','wplms')
						),
						array(
							'label' => __('Schedule Inactivity Email (in days)','wplms'),
							'name' =>'inactivity_schedule',
							'type' => 'number',
							'desc' => __('Accuracy of email depends upon site traffic and resources.','wplms')
						),
						array(
							'label' => __('Number of days, email is to be sent daily','wplms'),
							'name' =>'inactivity_days',
							'type' => 'number',
							'desc' => __('Number of days the email will be sent daily.','wplms')
						),
						array(
							'label' => __('Number of weeks, email is to be sent weekly','wplms'),
							'name' =>'inactivity_weeks',
							'type' => 'number',
							'desc' => __('Number of Weeks the email will be sent weekly.','wplms')
						),
						array(
							'label' => __('Number of months, email is to be sent monthly','wplms'),
							'name' =>'inactivity_months',
							'type' => 'number',
							'desc' => __('Number of Months the email will be sent monthly.','wplms')
						),

						array(
							'label' => sprintf(__('Schedule Course Review Email %s Edit Email %s','wplms'),'<a href="'.admin_url('edit.php?taxonomy=bp-email-type&term=wplms_course_review_email&post_type=bp-email').'" class="button">','</a>'),
							'name' =>'course_review_schedule',
							'type' => 'title'
						),
						array(
							'label' => __('Enable Course Review Email','wplms'),
							'name' =>'review_course',
							'type' => 'select',
							'options'=>array(
								'no' => __('No','wplms'),
								'yes' => __('Yes','wplms'),
								),
							'desc' => __('Email students when the course is finished and the user has not given any review to the course','wplms')
						),
						array(
							'label' => __('Schedule Course Review Email (in days)','wplms'),
							'name' =>'review_course_schedule',
							'type' => 'number',
							'desc' => __('Accuracy of email depends upon site traffic and resources.','wplms')
						),
				);
			$this->settings = apply_filters('wplms_email_schedule',$settings);
			$this->lms_settings_generate_form('schedule',$this->settings);
			echo '<style>.form-table th{width:250px;}</style>';
	}

	function scheduled_emails(){

		if(!function_exists('_get_cron_array'))
			return;
		if(!empty($_POST) && isset($_POST['remove_schedule'])){
			if( wp_verify_nonce($_POST['remove_scheduled_email_security'],'remove_scheduled_email_'.$_POST['timestamp']) && !empty($_POST['cron_key'])){
				$key = $_POST['cron_key'];
				$crons = _get_cron_array();
				$timestamp =  $_POST['timestamp'];
				$hook = $_POST['hook'];
			   
			    unset( $crons[$timestamp][$hook][$key] );
			    if ( empty($crons[$timestamp][$hook]) ){
			        unset( $crons[$timestamp][$hook] );
			    }
			    if ( empty($crons[$timestamp]) ){
			        unset( $crons[$timestamp] );
			    }
			    _set_cron_array( $crons );
			    unset($_POST['remove_scheduled_email_security']);
			    echo '<div class="clear"></div><div class="updated notice is-dismissible"><p>'.__('Cron removed.','wplms').'</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">'.__('Dismiss this notice.','wplms').'</span></button></div>';
			}else{
				echo '<div class="clear"></div><div class="notice notice-error"><p>'.__('There was an error while removing cron.','wplms').'</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">'.__('Dismiss this notice.','wplms').'</span></button></div>';
			}
		}
		echo '<br style="clear:both"><table class="form-table">';
		
		$crons =  _get_cron_array();
		if(!empty($crons)){
			echo '<script>var confirm_message_cron= "'.__('Are you sure you want to remove this schedule?','wplms').'";</script>';
			$format = get_option('date_format') . ' - '. get_option('time_format');
			$data = '';
			$check_emails = 0;
			foreach($crons as $timestamp => $cron){
				if(isset($cron['wplms_send_course_expiry_mail']) || isset($cron['wplms_send_drip_mail'])){
					$check_emails++;
					if(isset($cron['wplms_send_course_expiry_mail'])){
						$value = $cron['wplms_send_course_expiry_mail'];
						$data = '<form method="post" id="expiry" class="remove_schedule" action="?page=lms-settings&tab=emails&sub=scheduled_emails"><input name="hook" value="wplms_send_course_expiry_mail" type="hidden"  data-hook="wplms_send_course_expiry_mail" >';
						$data .= '<input type="hidden" name="remove_scheduled_email_security" value="'.wp_create_nonce('remove_scheduled_email_'.$timestamp).'" id="remove_scheduled_email_security">';
						foreach($value as $v){
							
							$data .= '<input type="hidden" name="cron_key" value="'.md5(serialize($v['args'])).'"> ';
							$data .= '<input name="timestamp" type="hidden" value="'.$timestamp.'">';
							$data .= '<input type="submit" class="button-primary remove_schedule_submit button" name="remove_schedule" value="'.__('Remove Schedule','wplms').'"></form>';
							$course_id = $v['args'][0];
							$user_id = $v['args'][1];
							break;
						}
					}else{
						$value = $cron['wplms_send_drip_mail'];
						$data = '<form method="post" id="drip" class="remove_schedule" action="?page=lms-settings&tab=emails&sub=scheduled_emails"><input name="hook" value="wplms_send_drip_mail" type="hidden"  data-hook="wplms_send_drip_mail" >';
						$data .='<input type="hidden" name="remove_scheduled_email_security" value="'.wp_create_nonce('remove_scheduled_email_'.$timestamp).'" id="remove_scheduled_email_security">';
						foreach($value as $v){
							
							$data .= '<input name="timestamp" type="hidden" value="'.$timestamp.'">';
							$data .= '<input type="hidden" name="cron_key" value="'.md5(serialize($v['args'])).'"> ';
							$data .= '<input type="submit" class="button-primary remove_schedule_submit button" name="remove_schedule" value="'.__('Remove Schedule','wplms').'"></form>';
							
							$course_id = $v['args'][1];
							$user_id = $v['args'][2];
							break;
						}
					}
					 
					echo '<tr><th><label>'.(isset($cron['wplms_send_course_expiry_mail'])?__('Course Expiry mail','wplms'):__('Drip feed mail','wplms')).'</label></th><td>'.get_date_from_gmt ( date( 'Y-m-d H:i:s', $timestamp ), $format ).'</td><td>'.get_the_title($course_id).'</td><td>'.(!empty($user_id)?bp_core_get_user_displayname($user_id):__('N.A','wplms')).'</td><td>'.$data.'</td></tr>';

				}
			}
			
			if($check_emails == 0){
				echo '<div class="message"><h3>'.__('No Scheduled emails','wplms').'</h3></div>';
			}
		}else{
			echo '<div class="message"><h3>'.__('No Scheduled emails','wplms').'</h3></div>';
		}
		echo '</table>';
	}

	function get_addons(){
		return apply_filters('wplms_lms_addons',array(
			'wplms-pre-course-quiz' =>array(
					'label'=> __(' Pre Course Quiz','wplms'),
					'sub'=> __('Take a Pre Test to enroll users in courses','wplms'),
					'icon'=> '<span class="dashicons dashicons-portfolio"></span>',
					'requires'=> '',
					'license_key'=>'wplms_pre_course_quiz_license_key',
					'link' => 'https://wplms.io/downloads/wplms-pre-course-quiz/',
					'extra'=>array('Pre Assessment','Auto-assign to courses'),
					'activated'=> (is_plugin_active('wplms-pre-course-quiz/loader.php')?true:false),
					'file'=>'wplms-pre-course-quiz/loader.php',
					'price'=>'BUY $9',
					'class'=>'featured'
				),
			'wplms-sphereengine' =>array(
					'label'=> __('WPLMS Sphereengine','wplms'),
					'sub'=> __('Coding platform in WPLMS','wplms'),
					'icon'=> '<span class="dashicons dashicons-portfolio"></span>',
					'requires'=> '',
					'license_key'=>'wplms_sphereengine_license_key',
					'link' => 'https://wplms.io/downloads/wplms-sphereengine/',
					'extra'=>array('Teach code','Auto-evaluate code questions','Code quizzes'),
					'activated'=> (is_plugin_active('wplms-sphereengine/wplms-sphereengine.php')?true:false),
					'file'=>'wplms-sphereengine/wplms-sphereengine.php',
					'price'=>'BUY $29',
					'class'=>'featured'
				),
			'vibe-appointments' =>array(
					'label'=> __('Vibe Appointments','wplms'),
					'sub'=> __('Book Instructors in WPLMS','wplms'),
					'icon'=> '<span class="dashicons dashicons-portfolio"></span>',
					'requires'=> '',
					'license_key'=>'vibe_appointments_license_key',
					'link' => 'https://wplms.io/downloads/vibe-appointments/',
					'extra'=>array('Book Instructors','Set Schedules for Bookings','Booking Calendar'),
					'activated'=> (is_plugin_active('vibe-appointments/loader.php')?true:false),
					'file'=>'vibe-appointments/loaders.php',
					'price'=>'BUY $59',
					'class'=>'featured'
				),
			'wplms-vdocipher' =>array(
					'label'=> __('WPLMS Vdocipher','wplms'),
					'sub'=> __('Vdocipher in WPLMS','wplms'),
					'icon'=> '<span class="dashicons dashicons-portfolio"></span>',
					'requires'=> '',
					'license_key'=>'wplms_vdocipher_license_key',
					'link' => 'https://wplms.io/downloads/wplms-vdocipher/',
					'extra'=>array('Vdocipher encrypted videos integration'),
					'activated'=> (is_plugin_active('wplms-vdocipher/wplms-vdocipher.php')?true:false),
					'file'=>'wplms-vdocipher/wplms-vdocipher.php',
					'price'=>'Free',
					'class'=>'featured'
				),
			'wplms-lessonspace' =>array(
					'label'=> __('WPLMS Lessonspace','wplms'),
					'sub'=> __('Lessonspace in WPLMS','wplms'),
					'icon'=> '<span class="dashicons dashicons-portfolio"></span>',
					'requires'=> '',
					'license_key'=>'wplms_lessonspace_license_key',
					'link' => 'https://wplms.io/downloads/wplms-lessonspace/',
					'extra'=>array('Lessonspace interactive whiteboard'),
					'activated'=> (is_plugin_active('wplms-lessonspace/wplms-lessonspace.php')?true:false),
					'file'=>'wplms-lessonspace/wplms-lessonspace.php',
					'price'=>'BUY $29',
					'class'=>'featured'
				),
			'wplms-appointments' =>array(
					'label'=> __('WPLMS Appointments','wplms'),
					'sub'=> __('Book Instructors in WPLMS','wplms'),
					'icon'=> '<span class="dashicons dashicons-portfolio"></span>',
					'requires'=> '',
					'license_key'=>'wplms_appointments_license_key',
					'link' => 'https://wplms.io/downloads/wplms-appointments/',
					'extra'=>array('Book Instructors','Set Schedules for Bookings','Booking Calendar'),
					'activated'=> (is_plugin_active('wplms-appointments/wplms-appointments.php')?true:false),
					'file'=>'wplms-appointments/wplms-appointments.php',
					'price'=>'BUY $39',
					'class'=>'featured'
				),
			'wplms-pdf-certificates' =>array(
					'label'=> __('WPLMS PDF Certificates','wplms'),
					'sub'=> __('High Quality Pdf certificates','wplms'),
					'icon'=> '<span class="dashicons dashicons-portfolio"></span>',
					'requires'=> '',
					'license_key'=>'wplms_pdf_certificates_license_key',
					'link' => 'https://wplms.io/downloads/wplms-pdf-certificates/',
					'extra'=>array('Add support to generate the high quality certificates in pdf format'),
					'activated'=> (is_plugin_active('wplms-pdf-certificates/wplms-pdf-certificates.php')?true:false),
					'file'=>'wplms-pdf-certificates/wplms-pdf-certificates.php',
					'price'=>'<del>BUY $29</del> FREE',
					'class'=>'featured'
				),
			'wplms-quiz-certificates' =>array(
					'label'=> __('WPLMS Quiz Certificates','wplms'),
					'sub'=> __('Enable Quiz only certificates','wplms'),
					'icon'=> '<span class="dashicons dashicons-portfolio"></span>',
					'requires'=> '',
					'license_key'=>'wplms_quiz_certificates_license_key',
					'link' => 'https://wplms.io/downloads/wplms-quiz-certificates/',
					'extra'=>array('Allow users to get certificates for Quizzes'),
					'activated'=> (is_plugin_active('wplms-quiz-certificates/wplms-quiz-certificates.php')?true:false),
					'file'=>'wplms-quiz-certificates/wplms-quiz-certificates.php',
					'price'=>'<del>BUY $19</del> FREE',
					'class'=>'featured'
				),
			'wplms-parent-user' =>array(
					'label'=> __('WPLMS Parent User','wplms'),
					'sub'=> __('Parent/Guardian user in WPLMS','wplms'),
					'icon'=> '<span class="dashicons dashicons-portfolio"></span>',
					'requires'=> '',
					'license_key'=>'wplms_parent_user_license_key',
					'link' => 'https://wplms.io/downloads/wplms-parent-user/',
					'extra'=>array('Assign Parent/Guardian Users','See Student reports and Statistics'),
					'activated'=> (is_plugin_active('wplms-parent-user/wplms-parent-user.php')?true:false),
					'file'=>'wplms-parent-user/wplms-parent-user.php',
					'price'=>'<del>BUY $19</del> FREE',
					'class'=>'featured'
				),
			'wplms-attendance' =>array(
					'label'=> __('WPLMS Attendance','wplms'),
					'sub'=> __('Adds attendance feature in courses of wplms','wplms'),
					'icon'=> '<span class="dashicons dashicons-portfolio"></span>',
					'requires'=> '',
					'license_key'=>'wplms_attendance_license_key',
					'link' => 'https://wplms.io/downloads/wplms-attendance/',
					'extra'=>array('Attendance module for wplms courses'),
					'activated'=> (is_plugin_active('wplms-attendance/wplms-attendance.php')?true:false),
					'file'=>'wplms-attendance/wplms-attendance.php',
					'price'=>'BUY $29',
					'class'=>'featured'
				),
			'wplms-unit-timings' =>array(
					'label'=> __('WPLMS UNIT TIMINGS','wplms'),
					'sub'=> __('Know the time spent by users on the course.','wplms'),
					'icon'=> '<span class="dashicons dashicons-portfolio"></span>',
					'requires'=> '',
					'license_key'=>'wplms_unit_timings_license_key',
					'link' => 'https://wplms.io/downloads/wplms-unit-timings/',
					'extra'=>array('Check users time spent in course','Download time spent by users stats','Show graph for users time spent'),
					'file'=>'wplms_unit_timings/wplms_unit_timings.php',
					'activated'=> (is_plugin_active('wplms_unit_timings/wplms_unit_timings.php')?true:false),
					'price'=>'BUY $29',
					'class'=>'featured'
				),
			'wplms-grade-book' =>array(
					'label'=> __('WPLMS GRADE BOOK','wplms'),
					'sub'=> __('Find all the grades at one place and update them accordingly','wplms'),
					'icon'=> '<span class="dashicons dashicons-portfolio"></span>',
					'requires'=> '',
					'license_key'=>'wplms_grade_book_license_key',
					'link' => 'https://wplms.io/downloads/wplms-grade-book/',
					'extra'=>array('Check all the grades at one place','Update the grades from one place','All the students grades in all instructor courses are shown'),
					'activated'=> (is_plugin_active('wplms-grade-book/wplms-grade-book.php')?true:false),
					'file'=>'wplms-grade-book/wplms-grade-book.php',
					'price'=>'BUY $19',
					'class'=>'featured'
				),
			'wplms-custom-learning-paths' =>array(
					'label'=> 'WPLMS Custom Learning Paths',
					'sub'=> 'Create Learning Paths for goals',
					'icon'=> '<span class="dashicons dashicons-portfolio"></span>',
					'requires'=> '',
					'license_key'=>'wplms_clp_license_key',
					'link' => 'https://wplms.io/downloads/custom-learning-paths/',
					'extra'=>array('Learning Paths like Lynda, Udemy, Pluralsight','Points based progress','Certificates and Badges at Steps'),
					'activated'=> (is_plugin_active('wplms-custom-learning-paths/wplms-custom-learning-paths.php')?true:false),
					'file'=>'wplms-custom-learning-paths/wplms-custom-learning-paths.php',
					'price'=>'BUY $29',
					'class'=>'featured'
				),
			'videovibe' =>array(
					'label'=> __('Video Vibe','wplms'),
					'sub'=> __('Upload videos directly from WP media panel to your Video account.','wplms'),
					'icon'=> '<span class="dashicons dashicons-portfolio"></span>',
					'requires'=> '',
					'license_key'=>'videovibe_license_key',
					'link' => 'https://wplms.io/downloads/videovibe/',
					'extra'=>array('Supports Vimeo','Instructor Privacy, Seek Lock'),
					'activated'=> (is_plugin_active('videovibe/video-vibe.php')?true:false),
					'file'=>'videovibe/video-vibe.php',
					'price'=>'BUY $29',
					'class'=>'featured'
				),
			'wplms-gift-course' =>array(
					'label'=> __('WPLMS Gift Course','wplms'),
					'sub'=> __('Gift Courses to users via emails in WPLMS','wplms'),
					'icon'=> '<span class="dashicons dashicons-portfolio"></span>',
					'requires'=> '',
					'license_key'=>'wplms_gift_course_license_key',
					'link' => 'https://wplms.io/downloads/wplms-gift-course/',
					'extra'=>array('Gift Courses to users'),
					'activated'=> (is_plugin_active('wplms-gift-course/wplms-gift-course.php')?true:false),
					'file'=>'wplms-gift-course/wplms-gift-course.php',
					'price'=>'BUY $19',
					'class'=>'featured'
				),
			'wplms-batches' =>array(
					'label'=> __('WPLMS Batches','wplms'),
					'sub'=> __('Course Batches for WPLMS','wplms'),
					'icon'=> '<span class="dashicons dashicons-portfolio"></span>',
					'requires'=> '',
					'license_key'=>'wplms_batches_license_key',
					'link' => 'https://wplms.io/downloads/wplms-batches/',
					'extra'=>array('Enable Course Batches','Supports Class TimeTable'),
					'activated'=> (is_plugin_active('wplms-batches/wplms-batches.php')?true:false),
					'file'=>'wplms-batches/wplms-batches.php',
					'price'=>'BUY $29',
					'class'=>'featured'
				),
			'wplms-s3' =>array(
					'label'=> __('WPLMS S3','wplms'),
					'sub'=> __('Secure files using Amazon S3.','wplms'),
					'icon'=> '<span class="dashicons dashicons-portfolio"></span>',
					'requires'=> '',
					'license_key'=>'wplms_s3_license_key',
					'link' => 'https://wplms.io/downloads/wplms-s3/',
					'extra'=>array('Secure Files with expiring Links','Host Videos/Audios/Files on Amazon S3','Supports Instructor Privacy'),
					'activated'=> (is_plugin_active('wplms-s3/wplms-s3.php')?true:false),
					'file'=>'wplms-s3/wplms-s3.php',
					'price'=>'BUY $29',
					'class'=>'featured'
				),
			'wplms-mailchimp' =>array(
					'label'=> __('WPLMS Mailchimp','wplms'),
					'sub'=> __('Mailchimp Lists for WPLMS','wplms'),
					'icon'=> '<span class="dashicons dashicons-portfolio"></span>',
					'requires'=> '',
					'license_key'=>'wplms_mailchimp_license_key',
					'link' => 'https://wplms.io/downloads/wplms-mailchimp/',
					'extra'=>array('Sync Email Lists','Auto subscribe lists'),
					'activated'=> (is_plugin_active('wplms-mailchimp/wplms-mailchimp.php')?true:false),
					'file'=>'wplms-mailchimp/wplms-mailchimp.php',
					'price'=>'BUY $19',
					'class'=>'featured'
				),
			'wplms-woocommerce' =>array(
					'label'=> __('WPLMS WooCommerce','wplms'),
					'sub'=> __('Integrate WPLMS with WooCommerce.','wplms'),
					'icon'=> '<span class="dashicons dashicons-portfolio"></span>',
					'requires'=> 'woocommerce',
					'license_key'=>'wplms_woocommerce_license_key',
					'link' => 'https://wplms.io/downloads/wplms-woocommerce/',
					'extra'=>array('Full WooCommerce support','Supports Variable Pricing'),
					'activated'=> (is_plugin_active('wplms-woocommerce/wplms-woocommerce.php')?true:false),
					'file'=>'wplms-woocommerce/wplms-woocommerce.php',
					'price'=>'BUY $19',
					'class'=>'featured'
				),
			'wplms-wishlist' =>array(
					'label'=> __('WPLMS Wishlists','wplms'),
					'sub'=> __('Create unlimited, sharable wishlists.','wplms'),
					'icon'=> '<span class="dashicons dashicons-portfolio"></span>',
					'requires'=> '',
					'license_key'=>'wplms_wishlist_license_key',
					'link' => 'https://wplms.io/downloads/wplms-wishlist/',
					'extra'=>array('Add to WishList','Create Collections'),
					'activated'=> (is_plugin_active('wplms-wishlist/wplms-wishlist.php')?true:false),
					'file'=>'wplms-wishlist/wplms-wishlist.php',
					'price'=>'<del>BUY $29</del> FREE',
					'class'=>'featured'
				),
			'wplms-application-forms' =>array(
					'label'=> __('WPLMS Application Forms','wplms'),
					'sub'=> __('Create application form which students can use for applying to a course.','wplms'),
					'icon'=> '<span class="dashicons dashicons-portfolio"></span>',
					'requires'=> '',
					'license_key'=>'wplms_application_forms_license_key',
					'link' => 'http://www.wplms.io/downloads/wplms-application-forms/',
					'extra'=>array('Create Application Forms','Create Collections'),
					'activated'=> (is_plugin_active('wplms-application-forms/wplms_application_forms.php')?true:false),
					'file'=>'wplms-application-forms/wplms_application_forms.php',
					'price'=>'<del>BUY $29</del> FREE',
					'class'=>'featured'
				),
			
			'wplms-course-custom-nav' => array(
					'label'=> __('WPLMS Custom Course Navigation','wplms'),
					'sub'=> __('Customise Course Sections','wplms'),
					'icon'=> '<span class="dashicons dashicons-portfolio"></span>',
					'requires'=> '',
					'link' => 'http://vibethemes.com/documentation/wplms/knowledge-base/wplms-course-custom-nav-plugin/',
					'extra'=>array('Add Custom sections in Course','Customise Course creation process'),
					'activated'=> (is_plugin_active('wplms-course-custom-nav/wplms-course-custom-nav.php')?true:false),
					'price'=>0,
					'class'=>''
				),
			'wplms-coauthors-plus' => array(
					'label'=> __('WPLMS CoAuthors Plus','wplms'),
					'sub'=> __('Integrate  WP CoAuhors plus plugin','wplms'),
					'icon'=> '<span class="dashicons dashicons-portfolio"></span>',
					'requires'=> 'co-authors-plus',
					'link' => 'https://wordpress.org/plugins/wplms-coauthors-plus/',
					'extra'=>array('Enable Multiple instructors per course'),
					'activated'=> (is_plugin_active('wplms-coauthors-plus/wplms-coauthor-plus.php')?true:false),
					'price'=>0,
					'class'=>''
				),
			'wplms-ga' => array(
					'label'=> __('WPLMS GA (Google Analytics)','wplms'),
					'sub'=> __('Integrate Google Analytics with WPLMS','wplms'),
					'icon'=> '<span class="dashicons dashicons-portfolio"></span>',
					'requires'=> '',
					'link' => 'https://wordpress.org/plugins/integrate-wplms-ga/',
					'extra'=>'',
					'activated'=> (is_plugin_active('integrate-wplms-ga/loader.php')?true:false),
					'price'=>0,
					'class'=>''
				),
			'wplms-bbb' => array(
					'label'=> __('WPLMS BBB (Big Blue Button)','wplms'),
					'sub'=> __('Integrate Big Blue Button with WPLMS','wplms'),
					'icon'=> '<span class="dashicons dashicons-portfolio"></span>',
					'requires'=> '',
					'link' => 'https://wordpress.org/plugins/bbb-wplms/',
					'extra'=>'',
					'activated'=> (is_plugin_active('bbb-wplms/wplms-bbb.php')?true:false),
					'price'=>0,
					'class'=>''
				),
			'wplms-h5p' => array(
					'label'=> __('WPLMS H5P Plugin','wplms'),
					'sub'=> __('Integrate H5P with WPLMS','wplms'),
					'icon'=> '<span class="dashicons dashicons-portfolio"></span>',
					'requires'=> '',
					'link' => 'https://wordpress.org/plugins/wplms-h5p-plugin/',
					'extra'=>'',
					'activated'=> (is_plugin_active('wplms-h5p-plugin/wplms-h5p.php')?true:false),
					'price'=>0,
					'class'=>''
				),
			'wplms-courseware-migrate' => array(
					'label'=> __('WP Courseware to WPLMS Migration','wplms'),
					'sub'=> __('Migrate Courses from WP Courseware to WPLMS.','wplms'),
					'icon'=> '<span class="dashicons dashicons-portfolio"></span>',
					'requires'=> '',
					'link' => 'https://wordpress.org/plugins/wplms-courseware-migration/',
					'extra'=>array('One Click migration process'),
					'activated'=> (is_plugin_active('wplms-courseware-migrate/wplms_courseware_migrate.php')?true:false),
					'price'=>0,
					'class'=>''
				),
			'wplms-clevercourse-migrate' => array(
					'label'=> __('Clevercourse to WPLMS Migration','wplms'),
					'sub'=> __('Migrate Courses from Clevercourse to WPLMS.','wplms'),
					'icon'=> '<span class="dashicons dashicons-portfolio"></span>',
					'requires'=> '',
					'link' => 'https://wordpress.org/plugins/wplms-clevercourse-migration/',
					'extra'=>array('One Click migration process'),
					'activated'=> (is_plugin_active('wplms-clevercourse-migrate/wplms_clevercourse_migrate.php')?true:false),
					'price'=>0,
					'class'=>''
				),
			'wplms-academy-migrate' => array(
					'label'=> __('Academy to WPLMS Migration','wplms'),
					'sub'=> __('Migrate Courses from Academy theme to WPLMS.','wplms'),
					'icon'=> '<span class="dashicons dashicons-portfolio"></span>',
					'requires'=> '',
					'link' => 'https://wordpress.org/plugins/wplms-academy-migration/',
					'extra'=>array('One Click migration process'),
					'activated'=> (is_plugin_active('wplms-academy-migrate/wplms_academy_migrate.php')?true:false),
					'price'=>0,
					'class'=>''
				),
			'wplms-sensei-migrate' => array(
					'label'=> __('Woo Sensei to WPLMS Migration','wplms'),
					'sub'=> __('Migrate Courses from Woo Sensei to WPLMS.','wplms'),
					'icon'=> '<span class="dashicons dashicons-portfolio"></span>',
					'requires'=> '',
					'link' => 'https://wordpress.org/plugins/wplms-sensei-migration/',
					'extra'=>array('One Click migration process'),
					'activated'=> (is_plugin_active('wplms-sensei-migrate/wplms-sensei-migrate.php')?true:false),
					'price'=>0,
					'class'=>''
				),
			'wplms-learndash-migrate' => array(
					'label'=> __('Learndash to WPLMS Migration','wplms'),
					'sub'=> __('Migrate Courses from Learndash to WPLMS.','wplms'),
					'icon'=> '<span class="dashicons dashicons-portfolio"></span>',
					'requires'=> '',
					'link' => 'https://wordpress.org/plugins/wplms-learndash-migration/',
					'extra'=>array('One Click migration process'),
					'activated'=> (is_plugin_active('wplms-learndash-migrate/wplms_learndash_migrate.php')?true:false),
					'price'=>0,
					'class'=>''
				),
			'wplms-learnpress-migrate' => array(
					'label'=> __('Learnpress to WPLMS Migration','wplms'),
					'sub'=> __('Migrate Courses from Learnpress to WPLMS.','wplms'),
					'icon'=> '<span class="dashicons dashicons-portfolio"></span>',
					'requires'=> '',
					'link' => 'https://wordpress.org/plugins/wplms-learnpress-migration/',
					'extra'=>array('One Click migration process'),
					'activated'=> (is_plugin_active('wplms-learnpress-migrate/wplms_learnpress_migrate.php')?true:false),
					'price'=>0,
					'class'=>''
				),
			'wplms-badgeos' => array(
					'label'=> __('WPLMS BadgeOS','wplms'),
					'sub'=> __('Connect BadgeOS badges with WPLMS','wplms'),
					'icon'=> '<span class="dashicons dashicons-portfolio"></span>',
					'requires'=> 'badgeos',
					'link' => 'https://wordpress.org/plugins/wplms-badgeos/',
					'extra'=>array('Create Custom badges','Award Badges on various Course tasks'),
					'activated'=> (is_plugin_active('wplms-badgeos/wplms-badgeos.php')?true:false),
					'price'=>0,
					'class'=>''
				),
			'wplms-dwqa' => array(
					'label'=> __('WPLMS DW Q&A','wplms'),
					'sub'=> __('Integrate DW Questions & Answer with WPLMS','wplms'),
					'icon'=> '<span class="dashicons dashicons-portfolio"></span>',
					'requires'=> 'dw-question-answer',
					'link' => 'https://wordpress.org/plugins/wplms-dwqa/',
					'extra'=>'',
					'activated'=> (is_plugin_active('wplms-dwqa/wplms-dwqa.php')?true:false),
					'price'=>0,
					'class'=>''
				),
			'wplms-edd' => array(
					'label'=> __('WPLMS Easy Digital Downloads','wplms'),
					'sub'=> __('Connect Easy Digital downloads with WPLMS','wplms'),
					'icon'=> '<span class="dashicons dashicons-cart"></span>',
					'requires'=> 'easy-digital-downloads',
					'link' => 'http://vibethemes.com/documentation/wplms/knowledge-base/wplms-edd-addon/',
					'extra'=>'',
					'activated'=> (is_plugin_active('easy-digital-downloads/easy-digital-downloads.php')?true:false),
					'price'=>0,
					'class'=>''
				),
			'bp-social-connect' => array(
					'label'=> __('BP Social Connect','wplms'),
					'sub'=> __('Connect your BuddyPress site with Social networks Login &  Registration','wplms'),
					'icon'=> '<span class="dashicons dashicons-portfolio"></span>',
					'requires'=> '',
					'link' => 'https://wordpress.org/plugins/bp-social-connect/',
					'extra'=>array('Integrate Facebook Login/Register','Integrate Google Plus Login/Register'),
					'activated'=> (is_plugin_active('bp-social-connect/bp_social_connect.php')?true:false),
					'price'=>0,
					'class'=>''
				),
			'bp-profile-cover' => array(
					'label'=> __('BP Profile Cover','wplms'),
					'sub'=> __('Add cover images to Profiles','wplms'),
					'icon'=> '<span class="dashicons dashicons-portfolio"></span>',
					'requires'=> '',
					'link' => 'https://wordpress.org/plugins/bp-profile-cover/',
					'extra'=>'',
					'price'=>0,
					'activated'=> (is_plugin_active('bp-profile-cover/loader.php')?true:false),
					'class'=>''
				),
			'wplms-custom-certificate-codes'=> array(
					'label'=> __('WPLMS Custom Certificate Codes','wplms'),
					'sub'=> __('Define custom certificate codes for Certificates','wplms'),
					'icon'=> '<span class="dashicons dashicons-media-interactive"></span>',
					'requires'=> '',
					'link' => 'http://vibethemes.com/documentation/wplms/knowledge-base/custom-certificate-codes-plugin/',
					'extra'=>array('Define Custom Certificate code pattern','Generate codes for existing certificates'),
					'price'=>0,
					'activated'=> (is_plugin_active('wplms-custom-certificate-codes/wplms_custom_certificate_codes.php')?true:false),
					'class'=>''
				),
			));
	}

	function is_free_trial($addon){
		$free_trial_plugins = get_option('wplms_free_trial_plugins');
		if(!empty($addon['file']) && isset($free_trial_plugins[$addon['file']])){
			return 'Free Trial - expires on '.date_i18n( get_option('date_format'), $free_trial_plugins[$addon['file']] );
		}

		return false;
	}

	function lms_addons(){
		
		$addons = $this->get_addons();
		?>
		<div class="wplms_addons">
		<?php
		foreach($addons as $key=>$addon){ 
			if(!empty($addon) && !empty($addon['label'])){

			$class = apply_filters('wplms_addon_class','',$addon);

			?>
				<div class="wplms_addon_block">
					<div class="inside <?php echo $class.' '.(($addon['activated'])?'active':''); ?>">
						<?php
							$free_trial = $this->is_free_trial($addon);
						 	echo (empty($addon['price'])?'<span class="free">FREE</span>':(!empty($free_trial)?'<span class="free premium">'.$free_trial.'</span>':'<span class="free premium">'.$addon['price'].'</span>')); 
						 ?>
						<h3 class=""><?php echo $addon['label']; ?><span><?php echo $addon['sub']; ?></span></h3>
						<?php 
						if(!empty($addon['extra'])){
							if(is_array($addon['extra'])){
								echo '<ul>';
								foreach($addon['extra'] as $ex){
									echo '<li>'.$ex.'</li>';
								}
								echo '</ul>';
							}else{
								echo $addon['extra'];
							}
						}
						if(!empty($addon['license_key']) && $addon['activated']){
							$val = get_option($addon['license_key']);
							?>
							<div class="activate_license">
                                <form action="<?php  echo admin_url( 'admin.php?page=lms-settings&tab=addons'); ?>" method="post">
                                    <input type="text" id="<?php echo $addon['license_key']; ?>" name="license_key" class="vibe_license_key" value="<?php echo $val ?>" placeholder="<?php _e('Enter License Key','wplms'); ?>" />
                                    <?php 
                                    if(!empty($val) && strpos($class,'invalid') === false){    ?>
                                    <input type="submit" class="button primary" name="<?php echo $addon['license_key']; ?>" value="Deactivate" />
                                    <?php
                                    }else{
                                        ?>
                                    <input type="submit" class="button primary" name="<?php echo $addon['license_key']; ?>" value="Activate" />
                                    <?php
                                    }
                                    wp_nonce_field( $key, $key);
                                    ?>
                                </form>
                            </div>
							<a target="_blank" class="button button-primary activate_license_toggle"><?php _e('License Key','wplms'); ?></a>
							<?php
						}else{
							if(!empty($addon['price'])){
								if(file_exists(WP_PLUGIN_DIR.'/'.$addon['file'])){
									echo '<a href="'.admin_url('plugins.php').'" class="button-primary">Plugin installed but not active</a>';
								}else{
									?>
									<a href="#" target="_blank" class="button-primary addon_free_trial" data-key="<?php echo $key; ?>" style="background:#71d27a;word-break:break-all;box-shadow: 0 1px 0 #46b450;border-color:#46b450 #46b450 #46b450;"><?php _e('Free Trial','wplms'); ?></a>
									<?php
								}
							}	
						}
						?>
						<a href="<?php echo $addon['link']; ?>" target="_blank" class="button"><?php _e('Learn more','wplms'); ?></a>
						
					</div>
				</div>
		<?php
			}
		}
		?>
		<script>
					jQuery(document).ready(function($){
						$('.addon_free_trial').on('click',function(event){
							event.preventDefault();
							var $this = $(this);
							if($this.hasClass('disabled'))
								return;

							var r = confirm("Free Trial can only be taken once.");
							if (r == true) {
							} else {
								$this.removeClass('disabled');
							    return;
							}
							$this.hasClass('disabled');
							$this.text('validating....');
							$.ajax({
					          	type: "POST",
					          	url: ajaxurl,
					          	dataType: 'json',
					          	data: { action: 'initiate_free_trial',
					                  security:'<?php echo wp_create_nonce('wplms_addon_free_trial'); ?>',
					                  addon: $this.attr('data-key'),
					                },
					          	cache: false,
					          	success: function (json) {
					          		
					          		if(typeof json !== 'object'  || json === null){

					          			$this.text('Unable to validate, check connection.');
						          		setTimeout(function(){
					          				$this.text('Free trial');
					          				$this.removeClass('disabled');
					          			},5000);
					          			return;
					          		}else if(typeof json === 'object' && json.status){
					          			console.log(json);
					          			$this.text('downloading....');
					          			$.ajax({
								          	type: "POST",
								          	url: ajaxurl,
								          	dataType: 'json',
								          	data: { 
								          			action: 'download_plugin_trial',
								                  	slug: $this.attr('data-key'),
								                  	username:'<?php echo vibe_get_option('username'); ?>',
								                  	pass:'<?php echo vibe_get_option('security_key'); ?>',
								                  	domain:window.location.hostname
								                },
								          	cache: false,
								          	success: function (json) {
								          		console.log(json);
								          		if(json.status){
								          			$this.text('installing....');
									          		setTimeout(function(){
									          			$this.text('Activating....');
									          			$.ajax({
												          	type: "POST",
												          	url: ajaxurl,
												          	data: { 
												          			action: 'activate_plugin_trial',
												                  	slug: $this.attr('data-key'),
												                },
												          	cache: false,
												          	success: function (message) {
												          		$this.text(message);
												          		setTimeout(function(){
											          				$this.text('Free trial active');
											          				$this.addClass('active');
											          				$this.removeClass('disabled');
											          			},5000);
												          	}
												          });
									          		},2000);	
								          		}else{
								          			$this.text(json.message);
								          			setTimeout(function(){
								          				$this.text('Free Trial');
								          			},5000);
								          		}
								          	}
								          	});
					          		}else{
					          			if(typeof json === 'object'){
					          				$this.text(json.message);	
					          			}
					          			
					          			setTimeout(function(){
					          				$this.removeClass('disabled');
					          				$this.text('Free Trial');
					          			},5000);
					          		}
					          	}
					        });
						});
					});
				</script>
		</div>
		<div class="clear">	</div>
		</div>
		<?php
	}
}

class wplms_miscellaneous_settings{

	var $option; 

	public static $instance;
    public static function init(){

        if ( is_null( self::$instance ) )
            self::$instance = new wplms_miscellaneous_settings();
        return self::$instance;
    }

	private function __construct(){
		$this->settings = get_option('lms_settings');
		add_action('admin_notices',array($this,'admin_notices'));
		add_action('wplms_before_create_course_header',array($this,'front_end_check_course_limit'));
		add_action( 'admin_head-post-new.php', array($this,'check_course_limit' ));
		
		add_action('wp_ajax_load_coursetree',array($this,'load_coursetree'));
		add_action('wp_ajax_vibe_update_license_key',array($this,'update_license_key'));
		add_action('wp_ajax_lms_import_wplms_emails',array($this,'import_wplms_emails'));

		/*==== Sync Functions ====*/
		add_action('wp_ajax_sync_resync',array($this,'sync_resync'));
		add_action('wp_ajax_sync_resync_course_students',array($this,'sync_resync_course_students'));
		add_action('wp_ajax_course_students',array($this,'end_course_students_sync'));

		add_action('wp_ajax_sync_resync_quiz_results',array($this,'sync_resync_quiz_results'));
		add_action('wp_ajax_quiz_results',array($this,'end_quiz_results_sync'));

		add_action('wp_ajax_sync_resync_unit_students',array($this,'sync_resync_unit_students'));
		add_action('wp_ajax_unit_students',array($this,'end_unit_students_sync'));

		add_action('wp_ajax_sync_resync_course_forums',array($this,'sync_resync_course_forums'));
		add_action('wp_ajax_course_forums',array($this,'end_course_forums_sync'));


		add_action('wp_ajax_sync_resync_unit_attachments',array($this,'sync_resync_unit_attachments'));
		add_action('wp_ajax_unit_attachments',array($this,'end_unit_attachments_sync'));

		
		add_action('wp_ajax_sync_resync_vibe_payouts',array($this,'sync_resync_vibe_payouts'));
		add_action('wp_ajax_vibe_payouts',array($this,'end_vibe_payouts_sync'));

		add_action('wp_ajax_sync_resync_calculate_question_correct_percentage',array($this,'sync_resync_calculate_question_correct_percentage'));
		add_action('wp_ajax_calculate_question_correct_percentage',array($this,'end_calculate_question_correct_percentage_sync'));

		add_action('wp_ajax_sync_resync_quiz_types_sync',array($this,'sync_resync_quiz_types_sync'));
		add_action('wp_ajax_quiz_types_sync',array($this,'end_quiz_types_sync'));

		add_action('wp_ajax_sync_resync_unit_types_sync',array($this,'sync_resync_unit_types_sync'));
		
		add_action('wp_ajax_unit_types_sync',array($this,'end_unit_types_sync'));

		add_action('wp_ajax_sync_resync_batch_sync',array($this,'sync_resync_batch_sync'));
		
		add_action('wp_ajax_batch_sync',array($this,'end_batch_sync'));


		add_action('wp_ajax_sync_resync_woocommerce_instructor_commissions',array($this,'sync_resync_woocommerce_instructor_commissions'));
		add_action('wp_ajax_woocommerce_instructor_commissions',array($this,'end_woocommerce_instructor_commissions_sync'));


		add_action('wp_ajax_sync_resync_woocommerce_instructor_commissions_bundle',array($this,'sync_resync_woocommerce_instructor_commissions_bundle'));
		add_action('wp_ajax_woocommerce_instructor_commissions_bundle',array($this,'end_woocommerce_instructor_commissions_bundle_sync'));


		add_action('wp_ajax_sync_resync_delete_old_question_comments',array($this,'sync_resync_delete_old_question_comments'));
		add_action('wp_ajax_delete_old_question_comments',array($this,'end_delete_old_question_comments_sync'));


		add_action('wp_ajax_select_users_api_clients',array($this,'select_users_api_clients'));
		// Download export file. Moved here as class.export.php file is included after init.
		add_action('init',array($this,'wplms_download_export_file'));

		// App version
		//add_filter('wplms_ionic_app_version',array($this,'wplms_ionic_app_version'));
		// Wallet
		add_filter('wplms_lms_api_tabs',array($this,'check_wallet'));
		add_action('lms_api_settings_sub',array($this,'user_wallets'),10,1);

		add_action('wp_ajax_update_user_wallet_amount',array($this,'update_user_wallet_amount'));

		add_action('wp_ajax_initiate_free_trial',array($this,'initiate_free_trial'));
		add_action('wp_ajax_download_plugin_trial',array($this,'download_plugin_trial'));
		add_action('wp_ajax_activate_plugin_trial',array($this,'activate_plugin_trial'));
		add_action('wplms_free_trial_expires',array($this,'free_trial_expires'),10,1);
		add_action('wplms_extended_free_trial_expires',array($this,'extended_free_trial_expires'),10,1);

		add_action('deleted_plugin',array($this,'check_deleted_plugins'),10,2);


		add_action('wp_ajax_disable_payout_request',array($this,'disable_payout_request'));
	}

	function disable_payout_request(){
		if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'request_payout_status') || !is_user_logged_in()){
            _e('Security check Failed. Contact Administrator.','wplms');
            die();
        }
		if(isset($_POST['currency'])){
			$currency = $_POST['currency'];
			$instructor_id = $_POST['instructor'];
			$amount = $_POST['amount'].''.$_POST['currency'];


			if(delete_user_meta( $instructor_id, 'vibe_request_payouts', $currency ) ){
				return true; 
			}
		}
		return false;
	}

	function admin_notices(){
		//$notices = get_option('wplms_admin_notices');

		//$free_trials = get_option('wplms_free_trial_plugins');
		//$addons = $this->get_addons();

		/*

		foreach($addons as $addon){
			if(!empty($addon['price']) && $this->check_plugin_active($addon['file'])) {
				if(!empty($free_trials)){
					foreach($free_trials as $plugin=>$expires){
						if($expires < time()){

						}
					}
				}
			}
		}*/
		
		
		if(!empty($notices)){
			foreach($notices as $k=>$notice){

				$continue = true;

				if(!empty($notice['for'])){
					switch($notice['for']){
						case 'plugin_license':
						$status = get_option($notice['license_status_key']);
						if($status == 'valid'){
							$continue = false;
							unset($notices[$k]);
							update_option('wplms_admin_notices',$notices);
						}
						break;
					}
				}

				if($continue){
					echo '<div class="message '.$notice['type'].'"><p>'.$notice['message'].'</p>';
					if(!empty($notice['actions'])){
						foreach($notice['actions'] as $action){
							switch($action['type']){

								case 'hide':
								break;
								case 'remove':
								break;
								case 'dismiss':
								break;
								case 'link':
									echo '<a href="'.$action['url'].'" class="button">'.$action['html'].'</a>';
								break;
								default:
									echo $action['html'];
								break;
							}
						}
					}
					
					echo '</div>';
				}
			}
		}
	}

	function check_plugin_active($path){

		if(is_multisite()){
			if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
			    require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
			}	
			if ( is_plugin_active_for_network( $path ) ) {
		    	return true;
			}else{
				return false;
			}
		}else{

			if(is_plugin_active($path)){
				return true;
			}
		}
		
	 	return false;
		
	}

	function initiate_free_trial(){
		if(!is_user_logged_in() || (is_user_logged_in() && !current_user_can('manage_options'))){
            die();
        }
        
        if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'wplms_addon_free_trial') ){
            echo 'Security check failed !';
            die();
        }

        if(function_exists('vibe_get_option')){
        	$username = vibe_get_option('username');
        	
        	if( empty($username)){
        		echo json_encode(array('status'=>0,'message'=>'Themeforest Username missing in WPLMS.'));
        		die();
        	}

        	if(Empty($this->token)){
        		$this->token = get_option('envato_token');	
        	}
        	

			if(empty($this->token)){
				echo json_encode(array('status'=>0,'message'=>'Missing Authentication. Please check WPLMS - Getting Started'));
				die();
			}

			if(time() > $this->token['expires']){
				//Refetch toekn
				$post = wp_remote_get('https://wplms.io/envato?refresh_token='.$token['refresh_token'],array('timeout' => 45));
				if(wp_remote_retrieve_response_code($post) == 200){
					$token_json = json_decode(wp_remote_retrieve_body($post));
					if(!empty($token_json)){
						$this->token['access_token'] = $token_json->access_token;
						$this->token['expires'] = time()+$token_json->expires_in;

						update_option('envato_token',$this->token);
					}else{
						echo json_encode(array('status'=>0,'message'=>'Unable to contact envato for authentication. Re-confirm from WPLMS - Getting Started'));
						die();
					}
				}
			}
			$access_token = $this->token['access_token'];
			//get Purchases

			$verify_purchase = wp_remote_request( 'https://api.envato.com/v3/market/buyer/list-purchases?filter_by=wordpress-themes&include_all_item_details=false', array(
				'headers' => array(
					'Authorization' => 'Bearer ' . $access_token, 
				),
				'method'  => 'GET',
			) );
			$purchases = json_decode(wp_remote_retrieve_body($verify_purchase));

			if(empty($purchases) || empty($purchases->results) || !is_array($purchases->results)){
				echo json_encode(array('status'=>0,'message'=>'Purchase unavailable.'));
				die();
			}

			

			foreach((array)$purchases->results as $purchase ){
				if($purchase->item->id == 6780226){
    				echo json_encode(array('status'=>1,'message'=>'Validated..','token'=>$access_token));
    				die();
		        }
		    }
	    }
        die();
	}

	function download_plugin_trial(){

		if(empty($_POST['domain']) || empty($_POST['username']) || empty($_POST['pass'])){
			echo json_encode(array('status'=>0,'message'=>'Missing Values'));
			die(); 
		}
		if(!current_user_can('manage_options')){
			echo json_encode(array('status'=>0,'message'=>'Current user not permitted.'));
			die(); 
		}

		if(Empty($this->token)){
    		$this->token = get_option('envato_token');	
    	}
        	
		$url = 'https://wplms.io/addon-free-trial/?addon='.$_POST['slug'].'&username='.$_POST['username'].'&pass='.$this->token['access_token'].'&domain='.$_POST['domain'];
		
		$response = wp_remote_get( $url ,array('timeout' => 200));
		$body = json_decode(wp_remote_retrieve_body($response),true);

		if(!$body['status']){
			echo json_encode(array('status'=>0,'message'=>$body['message']));
			die();
		}
       
  		
		if ( !function_exists( 'wp_ajax_install_plugin' ) ) { 
		    require_once ABSPATH . '/wp-admin/includes/ajax-actions.php'; 
		} 
		
		// NOTICE! Understand what this does before running. 
		
		$status = array(
			'install' => 'plugin',
			'slug'    => sanitize_key( wp_unslash( $_POST['slug'].'/'.$_POST['slug'].'.php' ) ),
		);

		if ( ! current_user_can( 'install_plugins' ) ) {
			echo json_encode(array('status'=>0,'message'=>'Sorry, you are not allowed to install plugins on this site.'));
			die(); 
		}
		
		$this->download_link = $body['url'];
		include_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
		include_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
		
		add_filter('plugins_api',function($args){
			$res = new stdClass();
			$res->download_link = $this->download_link;
			return $res;
		});
		$api = plugins_api( 'plugin_information', array(
			'slug'   => sanitize_key( wp_unslash( $_POST['slug'].'/'.$_POST['slug'].'.php' ) ),
			'fields' => array(
				'sections' => false,
			),
		) );
		if ( is_wp_error( $api ) ) {
			echo json_encode(array('status'=>0,'message'=>$api->get_error_message()));
			die();
		}

		$skin     = new WP_Ajax_Upgrader_Skin();
		$upgrader = new Plugin_Upgrader( $skin );
		
		
        $api->download_link = str_replace('&#038;','&',$api->download_link);
		$result   = $upgrader->install($api->download_link);
				
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			$status['debug'] = $skin->get_upgrade_messages();
		}

		if ( is_wp_error( $result ) ) {
			echo json_encode(array('status'=>0,'message'=>$result->get_error_message() ));
			die();
		} elseif ( is_wp_error( $skin->result ) ) {
			echo json_encode(array('status'=>0,'message'=>$skin->result->get_error_message() ));
			die();
		} elseif ( $skin->get_errors()->get_error_code() ) {
			echo json_encode(array('status'=>0,'message'=>'Some error' ));
			die();
		} elseif ( is_null( $result ) ) {
			global $wp_filesystem;

			$status['errorCode']    = 'unable_to_connect_to_filesystem';
			$status['errorMessage'] = __( 'Unable to connect to the filesystem. Please confirm your credentials.' );
			
			// Pass through the error from WP_Filesystem if one was raised.
			if ( $wp_filesystem instanceof WP_Filesystem_Base && is_wp_error( $wp_filesystem->errors ) && $wp_filesystem->errors->get_error_code() ) {
				$status['errorMessage'] = esc_html( $wp_filesystem->errors->get_error_message() );
			}
			
			echo json_encode(array('status'=>0,'message'=>$status['errorMessage'] ));
			die();
		}

		$install_status = install_plugin_install_status( $api );
		$pagenow = isset( $_POST['pagenow'] ) ? sanitize_key( $_POST['pagenow'] ) : '';

		// If installation request is coming from import page, do not return network activation link.
		$plugins_url = ( 'import' === $pagenow ) ? admin_url( 'plugins.php' ) : network_admin_url( 'plugins.php' );

		if ( current_user_can( 'activate_plugin', $install_status['file'] ) && is_plugin_inactive( $install_status['file'] ) ) {
			
			if(!function_exists('activate_plugin')){
				require_once(ABSPATH .'/wp-admin/includes/plugin.php');				
			}
			activate_plugin($install_status['file']);
		}

		
		echo json_encode(array('status'=>1,'message'=>'Plugin installed' ));
		die();
	}

	function activate_plugin_trial(){
		if(!current_user_can('manage_options')){
			echo json_encode(array('status'=>0,'message'=>'Current user not permitted.'));
			die(); 
		}

		$addons = lms_settings::get_addons();

		if ( current_user_can( 'activate_plugin',$addons[$_POST['slug']]['file'] ) && is_plugin_inactive( $addons[$_POST['slug']]['file'] ) ) {
			
			if(!function_exists('activate_plugin')){
				require_once(ABSPATH .'/wp-admin/includes/plugin.php');				
			}

			$free_trial_plugins = get_option('wplms_free_trial_plugins');

			if(empty($free_trial_plugins)){$free_trial_plugins=array();}

			

			activate_plugin($addons[$_POST['slug']]['file']);

			
			$free_trial_period = time()+30*2;
			$extended_free_trial_period = time()+60*2;

			$free_trial_plugins[$addons[$_POST['slug']]['file']]=$free_trial_period;

			wp_schedule_single_event($free_trial_period,'wplms_free_trial_expires',array($_POST['slug']));
			wp_schedule_single_event($extended_free_trial_period,'wplms_extended_free_trial_expires',array($_POST['slug']));
			echo 'Plugin Activated';

			update_option('wplms_free_trial_plugins',$free_trial_plugins);
		}
		die();
	}
	/*
	'wplms-gift-course' =>array(
					'label'=> __('WPLMS Gift Course','wplms'),
					'sub'=> __('Gift Courses to users via emails in WPLMS','wplms'),
					'icon'=> '<span class="dashicons dashicons-portfolio"></span>',
					'requires'=> '',
					'license_key'=>'wplms_gift_course_license_key',
					'link' => 'http://www.vibethemes.com/downloads/wplms-gift-course/',
					'extra'=>array('Gift Courses to users'),
					'activated'=> (is_plugin_active('wplms-gift-course/wplms-gift-course.php')?true:false),
					'file'=>'wplms-gift-course/wplms-gift-course.php',
					'price'=>'BUY $19',
					'class'=>'featured'
				),
				*/

	function free_trial_expires($addon_slug){
		$notices = get_option('wplms_admin_notices');
		if(empty($notices)){$notices = array();}

		$addons = lms_settings::get_addons();
		$addon = $addons[$addon_slug];
		$license_status = str_replace('key','status',$addon['license_key']);

		$notices[$addon['file']]=array(
			'type'=>'error',
			'for'=>'plugin_license',
			'license_status_key' => $license_status,
			'message'=>'Trial period for plugin '.$addon['label'].' has expired. Kindly purchase the plugin ! Not yet decided ? No problem, the plugin will remain active for grace period of 29 days. Purchase now for full activation.',
			'action'=>array(
				array(
					'type'=>'link',
					'url'=>$addon['link'],
					'html'=>'Purchase now - '.$addon['price'],
				)
			),

		);

		
		$status = get_option($license_status);
		if($status != 'valid'){
			update_option('wplms_admin_notices',$notices);
		}

	}

	function extended_free_trial_expires($addon){

		$addons = lms_settings::get_addons();
		$addon = $addons[$addon_slug];
		

		$license_status = str_replace('key','status',$addon['license_key']);
		$status = get_option($license_status);
		if($status != 'valid'){

			if(is_multisite()){
				if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
				    require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
				}
				 
				if ( is_plugin_active_for_network( $addon['file'] ) ) {
				    deactivate_plugins( $addon['file'] );
				}
			}else{
				if(is_plugin_active($addon['file'])){
					deactivate_plugins( $addon['file'] );	
				}	
			}
			
			
			delete_plugins(array($addon['file']));

			
		}
		$notices = get_option('wplms_admin_notices');
		unset($notices[$addon['file']]);
		update_option('wplms_admin_notices',$notices);
	}


	function check_deleted_plugins($file,$deleted){

		if($deleted == false){
			$addons = $this->get_addons();
			foreach($addons as $addon){
				if($addon['file'] == $file){
					$notices = get_option('wplms_admin_notices');
					$notices[$addon['file']]=array(
						'type'=>'error',
						'for'=>'plugin_license',
						'license_status_key' => $license_status,
						'message'=>'Trial period for plugin '.$addon['label'].' has expired. Kindly purchase the plugin ! Or manually remove the plugin <a href="'.admin_url('plugins.php').'" class="button">Remove Plugin</a>',
						'action'=>array(
							array(
								'type'=>'link',
								'url'=>$addon['link'],
								'html'=>'Purchase now - '.$addon['price'],
							)
						),

					);
					update_option('wplms_admin_notices',$notices);
					break;
				}
			}
		}
	}

	function update_user_wallet_amount(){
		if(!is_user_logged_in() || (is_user_logged_in() && !current_user_can('edit_posts'))){
            die();
        }
        
        if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'vibe_security') ){
            echo 'Security check failed !';
            die();
        }

        if(!empty($_POST['wallet_user_id']) && !empty($_POST['wallet_amount']) && is_numeric($_POST['wallet_amount']) && is_numeric($_POST['wallet_user_id'])){
        	update_user_meta($_POST['wallet_user_id'],'wallet',$_POST['wallet_amount']);
        	echo _x('Updated!','updated wallet amount label','wplms');
        }
        die();
	}

	function select_users_api_clients(){
	
        if(!is_user_logged_in() || (is_user_logged_in() && !current_user_can('manage_options'))){
            die();
        }
        
        if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'vibe_security') ){
            echo 'Security check failed !';
            die();
        }
        global $wpdb;
        $term= '';
        if(!empty($_POST['q']['term'])){
            $term = sanitize_text_field($_POST['q']['term']);
        }
        
        $q = "
            SELECT ID, display_name FROM {$wpdb->users} 
            WHERE (
                user_login LIKE '%$term%'
                OR user_nicename LIKE '%$term%'
                OR user_email LIKE '%$term%' 
                OR user_url LIKE '%$term%'
                OR display_name LIKE '%$term%'
                )";
        $users = $wpdb->get_results($q);

        $user_list = array();
          // Check for results
        if (!empty($users)) {
            foreach($users as $user){
                $user_list[] = array(
                  'id'=>$user->ID,
                  'image'=>bp_core_fetch_avatar(array('item_id' => $user->ID, 'type' => 'thumb', 'width' => 32, 'height' => 32, 'html'=>false)),
                  'text'=>$user->display_name
                );
            }
            echo json_encode($user_list);
        } else {
            echo json_encode(array('id'=>'','text'=>_x('No Users found !','No users found in Course - admin - add users area','wplms')));
        }
        die();
    }

	function wplms_download_export_file(){
		if(!empty($_GET['download']) && current_user_can('manage_options') && is_admin() && !isset($_POST['export'])){ //Export Downloads for Admin only

			$file = urldecode($_GET['download']);
			if(!file_exists($file)){
				return;
			}
			$filesize=filesize($file);
			if ($filesize > 0) {
				header("Pragma: public"); // required
				header("Expires: 0");
				header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
				header("Cache-Control: private",false); // required for certain browsers 
				header("Content-Type: image/gif");
				header("Content-Length: ".filesize($file));
				header("Content-Disposition: attachment; filename=\"".basename($file)."\";" );
				readfile($file);
			}
		}
	}

	function import_wplms_emails(){
		if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'vibe_lms_settings') ){
		    _e('Security check Failed. Contact Administrator.','wplms');
		    die();
		}
	}
	
	function check_course_limit() {

		if(empty($this->settings)){
			$this->settings=get_option('lms_settings');	
		}
		
		$lms_settings = $this->settings;
		if(!isset($lms_settings) || !is_array($lms_settings))
			return;

	    global $userdata;
	    global $post_type;
	    global $wpdb;

	    if(in_array('instructor',$userdata->roles)){ 
			if( $post_type === 'course' && isset($lms_settings['general']['course_limit']) && $lms_settings['general']['course_limit']) {
				$course_count = $wpdb->get_var( "SELECT count(*) FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'course' AND post_author = $userdata->ID" );
				if( $course_count >= $lms_settings['general']['course_limit'] ) { wp_die( "Course Limit Exceeded" ); }
			} elseif( $post_type === 'unit' && isset($lms_settings['general']['unit_limit']) && $lms_settings['general']['unit_limit']) {
				$unit_count = $wpdb->get_var( "SELECT count(*) FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'unit' AND post_author = $userdata->ID" );
				if( $unit_count >= $lms_settings['general']['unit_limit'] ) { wp_die( "Unit Limit Exceeded" ); }
			} elseif( $post_type === 'quiz' && isset($lms_settings['general']['quiz_limit']) && $lms_settings['general']['quiz_limit']) {
				$quiz_count = $wpdb->get_var( "SELECT count(*) FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'quiz' AND post_author = $userdata->ID" );
				if( $quiz_count >= $lms_settings['general']['quiz_limit'] ) { wp_die( "Quiz Limit Exceeded" ); }
			}
		}
		return;
	}

	function front_end_check_course_limit(){
		
		if(empty($this->settings)){
			$this->settings=get_option('lms_settings');	
		}

		if(!isset($lms_settings) || !is_array($lms_settings))
			return;

	    global $userdata;
	    global $wpdb;
	    if( isset($lms_settings['general']['course_limit']) && $lms_settings['general']['course_limit']) {
		    if(in_array('instructor',$userdata->roles) && !isset($GET['action'])){ 
				
					$course_count = $wpdb->get_var( "SELECT count(*) FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'course' AND post_author = $userdata->ID" );
					if( $course_count >= $lms_settings['general']['course_limit'] ) { wp_die( "Course Limit Exceeded" ); }
			}
		}
		return;
	}

	function load_coursetree(){
		$course_id = $_POST['course_id'];
	    if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'security') || !is_numeric($course_id)){
	         _e('Security check Failed. Contact Administrator.','wplms');
	        die();
		}
		$curriculum = get_post_meta($course_id,'vibe_course_curriculum',true);
		if(isset($curriculum) && is_array($curriculum)){
			echo '<ul class="course_curriculum">';
			foreach ($curriculum as $key => $value) {
				if(is_numeric($value)){
					echo '<li><a href="'.get_edit_post_link($value).'">'.get_post_type($value).' : '.get_the_title($value).'</a>';
					if(get_post_type($value) == 'unit'){
						$assignments = get_post_meta($value,'vibe_assignment',true);
						if(!empty($assignments) && is_array($assignments)){
							echo '<ul class="assignments">';
							foreach($assignments as $assignment){
								echo '<li><a href="'.get_edit_post_link($assignment).'">'.__('Assignment','wplms').' : '.get_the_title($assignment).'</a></li>';
							}
							echo '</ul>';
						}
					}
					echo '</li>';
				}else{
					echo '<li><strong>'.$value.'</strong></li>';
				}
			}
			echo '</ul>';
		}
		die();
	}

	function update_license_key(){
		if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'security')){
	         _e('Security check Failed. Contact Administrator.','wplms');
	        die();
		}
		if(empty($_POST['addon']) || empty($_POST['key'])){
			_e('Unable to update key.','wplms');
			die();
		}
		update_option($_POST['addon'],$_POST['key']);
		echo apply_filters('wplms_addon_license_key_updated',__('Key Updated.','wplms'));
		die();
	}
	/*
	SYNC FUNCTIONS
	 */
	function sync_resync(){
		if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'sync_resync')){
	         _e('Security check Failed. Contact Administrator.','wplms');
	        die();
		}
		$this->deleted= 0;
		switch($_POST['id']){
			case 'course_students':
				global $wpdb;

				$course_students = get_transient('sync_course_students');
				if(empty($course_students)){
					$course_students= 0;	
				}

				$data = $wpdb->get_results("SELECT p.ID as course_id,m.user_id as user_id FROM {$wpdb->posts} as p LEFT JOIN {$wpdb->usermeta} as m ON p.ID = m.meta_key WHERE p.post_type = 'course' AND p.post_status = 'publish' AND p.ID > $course_students");
				$course_data = array();
				if(!empty($data)){
					$security = wp_create_nonce('sync_resync_course_students');
					foreach($data as $d){

						if(empty($course_data[$d->course_id])){
							$course_data[$d->course_id]=array('action'=>'sync_resync_course_students','security'=>$security,'course_id'=>$d->course_id,'users' => array($d->user_id));
						}else{
							if(!in_array($d->user_id,$course_data[$d->course_id]['users'])){
								$course_data[$d->course_id]['users'][] = $d->user_id;
							}
						}
					}
				}
				echo json_encode($course_data);
			break;
			case 'quiz_results':
				global $wpdb;

				$quiz_results = get_transient('sync_quiz_results');
				if(empty($quiz_results)){
					$quiz_results= 0;	
				}

				$data = $wpdb->get_results("SELECT p.ID as quiz_id,m.user_id as user_id FROM {$wpdb->posts} as p LEFT JOIN {$wpdb->usermeta} as m ON p.ID = m.meta_key WHERE p.post_type = 'quiz' AND p.post_status = 'publish' AND p.ID > $quiz_results");
				$quiz_data = array();
				if(!empty($data)){
					$security = wp_create_nonce('sync_resync_quiz_results');
					foreach($data as $d){

						if(empty($quiz_data[$d->quiz_id]) && !empty($d->user_id)){
							$quiz_data[$d->quiz_id]=array('action'=>'sync_resync_quiz_results','security'=>$security,'quiz_id'=>$d->quiz_id,'users' => array($d->user_id));
						}else{
							if(!in_array($d->user_id,$quiz_data[$d->quiz_id]['users']) && !empty($d->user_id)){
								$quiz_data[$d->quiz_id]['users'][] = $d->user_id;
							}
						}
					}
				}
				echo json_encode($quiz_data);
			break;
			case 'unit_students':
				global $wpdb;

				$unit_students = get_transient('sync_unit_students');
				if(empty($unit_students)){
					$unit_students= 0;	
				}

				$data = $wpdb->get_results("SELECT p.ID as unit_id,m.user_id as user_id FROM {$wpdb->posts} as p LEFT JOIN {$wpdb->usermeta} as m ON p.ID = m.meta_key WHERE p.post_type = 'unit' AND p.post_status = 'publish' AND p.ID > $unit_students");
				$unit_data = array();
				if(!empty($data)){
					$security = wp_create_nonce('sync_resync_unit_students');
					foreach($data as $d){
						if(empty($unit_data[$d->unit_id]) && !empty($d->user_id)){
							$unit_data[$d->unit_id]=array('action'=>'sync_resync_unit_students','security'=>$security,'unit_id'=>$d->unit_id,'users' => array($d->user_id));
						}else{
							if(!in_array($d->user_id,$unit_data[$d->unit_id]['users']) && !empty($d->user_id)){
								$unit_data[$d->unit_id]['users'][] = $d->user_id;
							}
						}
					}
				}
				echo json_encode($unit_data);
			break;
			case 'course_forums':
				global $wpdb;
				$course_forums = get_transient('sync_course_forums');
				if(empty($course_forums)){
					$course_forums= 0;	
				}
				$data = $wpdb->get_results("SELECT p.ID as course_id, m.meta_value as forum_id FROM {$wpdb->posts} as p LEFT JOIN {$wpdb->postmeta} as m ON p.ID = m.post_id WHERE p.post_type = 'course' AND p.post_status = 'publish' AND meta_key = 'vibe_forum' AND p.ID > $course_forums");
				$course_data = array();
				if(!empty($data)){
					$security = wp_create_nonce('sync_resync_course_forums');
					foreach($data as $d){
						if(!empty($d->forum_id)){
							$course_data[$d->course_id]=array('action'=>'sync_resync_course_forums','security'=>$security,'course_id'=>$d->course_id,'forum_id' => $d->forum_id);
						}
					}
				}
				echo json_encode($course_data);
			break;
			case 'woocommerce_instructor_commissions':
				global $wpdb,$bp;
				
				$woocommerce_instructor_commissions = get_transient('sync_woocommerce_instructor_commissions');
				if(empty($woocommerce_instructor_commissions)){
					$woocommerce_instructor_commissions= 0;	
				}

				$order_item_meta_table=$wpdb->prefix.'woocommerce_order_itemmeta';
				$order_items_table=$wpdb->prefix.'woocommerce_order_items';

				/*$inst_commissions = $wpdb->get_results("
                            SELECT activity.id,activity.secondary_item_id,activity.item_id,activity.user_id FROM {$bp->activity->table_name} AS activity
                            WHERE     activity.component     = 'course'
                            AND     activity.type     = 'course_commission'
                        ");*/


                $inst_commissions = $wpdb->get_results("
                SELECT item.order_id as order_id,meta.order_item_id as item_id, meta.meta_key as instructor, meta.meta_value as commission
                FROM {$order_item_meta_table} as meta
                INNER JOIN {$order_items_table} as item 
                ON meta.order_item_id = item.order_item_id
                WHERE meta.meta_key like '%commission%'
                AND item.order_item_id > $woocommerce_instructor_commissions
                "); 
                
				$commissions_data = array();
				if(!empty($inst_commissions) && is_array($inst_commissions)){
					$security = wp_create_nonce('sync_resync_woocommerce_instructor_commissions');

					foreach($inst_commissions as $key=>$inst_commission){

						$instructor_id= explode('commission',$inst_commission->instructor)[1];
						if(is_numeric($instructor_id)){
							$commissions_data[$key]=array(
								'action'=>'sync_resync_woocommerce_instructor_commissions',
								'security'=>$security,
								'order_id'=>$inst_commission->order_id,
								'instructor_id'=> $instructor_id,
								'item_id'=> $inst_commission->item_id,
								'commission'=>$inst_commission->commission
							);
						}
					}
				}
				echo json_encode($commissions_data);
			break;
			case 'woocommerce_instructor_commissions_bundle':
				global $wpdb,$bp;
				
				$order_item_meta_table=$wpdb->prefix.'woocommerce_order_itemmeta';
				$order_items_table=$wpdb->prefix.'woocommerce_order_items';

				$woocommerce_instructor_commissions_bundle = get_transient('sync_woocommerce_instructor_commissions_bundle');
				if(empty($woocommerce_instructor_commissions_bundle)){
					$woocommerce_instructor_commissions_bundle= 0;	
				}

                $inst_commissions = $wpdb->get_results("
                SELECT item.order_id as order_id,meta.order_item_id as item_id, meta.meta_key as instructor, meta.meta_value as commission
                FROM {$order_item_meta_table} as meta
                INNER JOIN {$order_items_table} as item 
                ON meta.order_item_id = item.order_item_id
                WHERE meta.meta_key like '%commission%' 
                AND item.order_item_id > $woocommerce_instructor_commissions_bundle
                GROUP BY item.order_id
                "); 
                
				$commissions_data = array();
				if(!empty($inst_commissions) && is_array($inst_commissions)){
					$security = wp_create_nonce('sync_resync_woocommerce_instructor_commissions_bundle');

					foreach($inst_commissions as $key=>$inst_commission){

						$instructor_id= explode('commission',$inst_commission->instructor)[1];
						if(is_numeric($instructor_id)){
							$commissions_data[$inst_commission->order_id]=array(
								'action'=>'sync_resync_woocommerce_instructor_commissions_bundle',
								'security'=>$security,
								'order_id'=>$inst_commission->order_id,
								'instructor_id'=> $instructor_id,
								'item_id'=> $inst_commission->item_id,
								'commission'=>$inst_commission->commission
							);
						}
					}
				}
				echo json_encode($commissions_data);
			break;
			case 'unit_attachments':
				global $wpdb;

				$unit_attachments = get_transient('sync_unit_attachments');
				if(empty($unit_attachments)){
					$unit_attachments= 0;	
				}

				$data = $wpdb->get_results("SELECT p.ID as unit_id FROM {$wpdb->posts} as p LEFT JOIN {$wpdb->postmeta} as pm ON p.ID = pm.post_id WHERE p.post_type = 'unit' AND p.ID > $unit_attachments");
				$unit_data = array();
				if(!empty($data)){
					$security = wp_create_nonce('sync_resync_unit_attachments');
					foreach($data as $d){
						if(!empty($d->unit_id)){
							$unit_data[$d->unit_id]=array('action'=>'sync_resync_unit_attachments','security'=>$security,'unit_id'=>$d->unit_id);
						}
					}
				}
				echo json_encode($unit_data);
			break;
			case 'vibe_payouts':
				global $wpdb;

				$vibe_payouts = get_transient('sync_vibe_payouts');
				if(empty($vibe_payouts)){
					$vibe_payouts= 0;	
				}

				$data = $wpdb->get_results("SELECT p.ID as payout_id FROM {$wpdb->posts} as p WHERE p.post_type = 'payments' AND p.ID > $vibe_payouts");
				$payouts = array();
				if(!empty($data)){
					$security = wp_create_nonce('sync_resync_vibe_payouts');
					foreach($data as $d){
						if(!empty($d->payout_id)){
							$payouts[$d->payout_id]=array('action'=>'sync_resync_vibe_payouts','security'=>$security,'payout_id'=>$d->payout_id);
						}
					}
				}
				echo json_encode($payouts);
			break;
			case 'calculate_question_correct_percentage':
			global $wpdb;

				$calculate_question_correct_percentage = get_transient('sync_calculate_question_correct_percentage');
				if(empty($calculate_question_correct_percentage)){
					$calculate_question_correct_percentage= 0;	
				}
				


				$data = $wpdb->get_results("SELECT c.comment_ID as comment_id,p.ID as id FROM {$wpdb->comments} AS c LEFT JOIN {$wpdb->posts} as p ON p.ID = c.comment_post_ID WHERE p.post_type = 'question' AND p.ID  > $calculate_question_correct_percentage");
				$payouts = array();
				if(!empty($data)){
					$security = wp_create_nonce('sync_resync_calculate_question_correct_percentage');
					foreach($data as $d){
						if(!empty($d->id)){
							if(empty($comments[$d->id])){
								$comments[$d->id]=array('action'=>'sync_resync_calculate_question_correct_percentage','security'=>$security,'comments'=>array($d->comment_id),'question_id'=>$d->id);
							}else{
								$comments[$d->id]['comments'][]= $d->comment_id;
							}
							
						}
					}
				}
				echo json_encode($comments);
			break;
			
			case 'quiz_types_sync':
			global $wpdb;
				global $wpdb,$bp;
				$quiz_types_sync = get_transient('sync_quiz_types');
				if(empty($quiz_types_sync)){
					$quiz_types_sync=0;
				}
				$data = $wpdb->get_results("SELECT ID from {$wpdb->posts} WHERE post_type = 'quiz' AND ID > $quiz_types_sync");
				
				
				$quiz_data = array();
				if(!empty($data)){
					$security = wp_create_nonce('sync_resync_quiz_types_sync');
					foreach($data as $d){
						if(!empty($d->ID)){
							$quiz_data[$d->ID]=array('action'=>'sync_resync_quiz_types_sync','security'=>$security,'quiz_id'=>$d->ID);
						}
					}
				}
				echo json_encode($quiz_data);
			break;
			case 'unit_types_sync':
			global $wpdb;
				global $wpdb,$bp;
				$unit_types_sync = get_transient('sync_unit_types');
				if(empty($unit_types_sync)){
					$unit_types_sync=0;
				}
				$data = $wpdb->get_results("SELECT ID from {$wpdb->posts} WHERE post_type = 'unit' AND ID > $unit_types_sync");
				$quiz_data = array();
				if(!empty($data)){
					$security = wp_create_nonce('sync_resync_unit_types_sync');
					foreach($data as $d){
						if(!empty($d->ID)){
							$quiz_data[$d->ID]=array('action'=>'sync_resync_unit_types_sync','security'=>$security,'unit_id'=>$d->ID);
						}
					}
				}
				echo json_encode($quiz_data);
			break;
			case 'batch_sync':
				global $wpdb,$bp;
				$data=[];
				$batch_ids = get_transient('sync_complete_batch_ids');
				if(empty($batch_ids) || !is_array($batch_ids)){
					$batch_ids=[];
				}
				$groups = $wpdb->get_results("SELECT group_id from {$bp->groups->table_name_groupmeta} WHERE meta_key = 'course_batch' AND meta_value = 1 AND group_id NOT IN (".implode(',',$batch_ids).")");
				
				if(!empty($groups)){
					$security = wp_create_nonce('sync_resync_batch_sync');
					foreach($groups as $group){
						$course_ids = groups_get_groupmeta($group->group_id,'batch_course',false);
						
						$user_ids = $wpdb->get_results("SELECT user_id from {$bp->groups->table_name_members} WHERE group_id = ".$group->group_id." AND is_confirmed = 1");
						
						foreach ($course_ids as $course_id) {
							foreach($user_ids as $user_id){
								$data[$course_id.'-'.$user_id->user_id]=['action'=>'sync_resync_batch_sync','security'=>$security,'course_id'=>$course_id,'group_id'=>$group->group_id,'user_id'=>$user_id->user_id,];	
							}
						}
						
					}
				}

				echo json_encode($data);
			break;
			case 'delete_old_question_comments':
				global $wpdb,$bp;
				$data = $wpdb->get_results("SELECT ID from {$wpdb->posts} WHERE post_type = 'quiz'");
				$quiz_data = array();
				if(!empty($data)){
					$security = wp_create_nonce('sync_resync_delete_old_question_comments');
					foreach($data as $d){
						if(!empty($d->ID)){
							$quiz_data[$d->ID]=array('action'=>'sync_resync_delete_old_question_comments','security'=>$security,'quiz_id'=>$d->ID);
						}
					}
				}
				echo json_encode($quiz_data);
			break;
			default:
				do_action('wplms_sync_areas_default_action',$_POST['id'],$_POST);
				
			break;
		}
		die();
	}

	function sync_resync_course_students(){
		if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'sync_resync_course_students')){
	         _e('Security check Failed. Contact Administrator.','wplms');
	        die();
		}
		$course_id = $_POST['course_id'];
		$users = $_POST['users'];
		$users_string = implode(',',$users);
		if(empty($users_string)){$users_string='0';}
		global $wpdb;

		//Remove Redundant users
		$c = $wpdb->query("DELETE FROM {$wpdb->postmeta} WHERE post_id = $course_id AND meta_key REGEXP '^[0-9]+$' AND meta_value REGEXP '^[0-9]+$' AND meta_key NOT IN ($users_string)");
		if($c && is_numeric($c)){$this->deleted += $c;};

		$x = $wpdb->query("DELETE FROM {$wpdb->usermeta} WHERE user_id NOT IN ($users_string) AND meta_key = 'course_status$course_id'");	
		if($x && is_numeric($x)){$this->deleted += $x;};

		if(!empty($users) && is_array($users)){
			foreach($users as $user_id){
				$status = get_user_meta($user_id,'course_status'.$course_id,true);
				if(empty($status)){
					update_user_meta($user_id,'course_status'.$course_id,1);
				}
				$course_marks = get_post_meta($course_id,$user_id,true);
				if(!isset($course_marks)){
					update_post_meta($course_id,$user_id,0);
				}
			}
		}

		set_transient('sync_course_students',$course_id,3600);
		die();
	}

	function end_course_students_sync(){
		if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'sync_resync')){
	         _e('Security check Failed. Contact Administrator.','wplms');
	        die();
		}
		if(empty($this->deleted)){$this->deleted=0;}
		delete_transient('sync_course_students');
		printf(__('%s redundant records removed. Course Student verification completed.','wplms'),$this->deleted);
		die();
	}

	function sync_resync_quiz_results(){
		if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'sync_resync_quiz_results')){
	         _e('Security check Failed. Contact Administrator.','wplms');
	        die();
		}
		global $wpdb;
		$quiz_id = $_POST['quiz_id'];
		$users = $_POST['users'];
		if(!empty($users) && function_exists('bp_course_get_quiz_questions')){
			foreach($users as $user_id){
				$questions = bp_course_get_quiz_questions($quiz_id,$user_id);
				if(!empty($questions)){
		    		$quess=$questions['ques'];
		    		$marks=$questions['marks'];
		    		$ques_string = implode(',',$quess);
		    		$answer_ids = $wpdb->get_results("SELECT comment_ID FROM {$wpdb->comments} WHERE comment_post_ID IN ($ques_string) AND user_id = $user_id LIMIT 0,1");
		    		if(!empty($answer_ids)){
		    			foreach($answer_ids as $answer_id){
		    				update_comment_meta($answer_id->comment_ID,'quiz_id',$quiz_id);
		    			}
		    		}
		    	}
			}
		}
		set_transient('sync_quiz_results',$quiz_id,3600);
		die();
	}

	function end_quiz_results_sync(){
		if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'sync_resync')){
	         _e('Security check Failed. Contact Administrator.','wplms');
	        die();
		}
		delete_transient('sync_quiz_results');
		echo __('Quiz results sync complete !','wplms');
		die();
	}

	function end_calculate_question_correct_percentage_sync(){
		if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'sync_resync')){
	         _e('Security check Failed. Contact Administrator.','wplms');
	        die();
		}
		delete_transient('sync_calculate_question_correct_percentage');
		echo __('Quiz correct percentage stats calculated','wplms');
		die();
	}
	
	function end_quiz_types_sync(){
		if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'sync_resync')){
	         _e('Security check Failed. Contact Administrator.','wplms');
	        die();
		}
		delete_transient('sync_quiz_types');
		echo __('Quiz types synced','wplms');
		die();
	}

	function end_unit_types_sync(){
		if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'sync_resync')){
	         _e('Security check Failed. Contact Administrator.','wplms');
	        die();
		}
		delete_transient('sync_unit_types');
		echo __('Unit types synced','wplms');
		die();
	}
	

	function sync_resync_unit_attachments(){
		if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'sync_resync_unit_attachments')){
	         _e('Security check Failed. Contact Administrator.','wplms');
	        die();
		}
		global $wpdb;
		$unit_id = $_POST['unit_id'];
		
		if(!empty($unit_id)){
			$attachments = get_children( 'post_type=attachment&output=ARRAY_N&orderby=menu_order&order=ASC&post_parent='.$unit_id);
	       	if($attachments && count($attachments)){
	           	$actual_attached_attachments = array();
	          	foreach( $attachments as $attachmentsID => $attachmentsPost ){
	          		$actual_attached_attachments[] =$attachmentsID;
	          	}
	      	}
	      	$meta_attachments = get_post_meta($id,'vibe_unit_attachments',true);
	      	if(!empty($meta_attachments) && count($meta_attachments)){
	      		foreach ($meta_attachments as $attid) {
	      			if(!in_array($attid,$meta_attachments)){
	      				$actual_attached_attachments[] = $attid;
	      			}
	      		}
	      	}
	      	update_post_meta($unit_id,'vibe_unit_attachments',$actual_attached_attachments);
		}
		set_transient('sync_unit_attachments',$unit_id,3600);
		die();
	}

	function sync_resync_vibe_payouts(){
		if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'sync_resync_vibe_payouts')){
	         _e('Security check Failed. Contact Administrator.','wplms');
	        die();
		}
		global $wpdb;
		$payout_id = $_POST['payout_id'];
		
		if(!empty($payout_id)){
			$data = get_post_meta($payout_id,'vibe_instructor_commissions',true);
			if(is_array($data)){
				foreach ($data as $inst_id => $commission) {
					$payout_value = get_post_meta($payout_id,'payout_'.$inst_id,true);
					if($payout_value == null || $payout_value == ''){
						if(isset($commission['commission'])){
							update_post_meta($payout_id,'payout_'.$inst_id,$commission['commission']);
						}
					}

					$currency = get_post_meta($payout_id,'currency_'.$inst_id,true);
					if(empty($currency)){
						if(!empty($commission['currency'])){
							update_post_meta($payout_id,'currency_'.$inst_id,$commission['currency']);
						}
					}
					
				}
			}
			set_transient('sync_vibe_payouts',$quiz_id,3600);
		}
		die();
	}


	function sync_resync_quiz_types_sync(){
		if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'sync_resync_quiz_types_sync')){
	         _e('Security check Failed. Contact Administrator.','wplms');
	        die();
		}
		global $wpdb;
		$quiz_id = $_POST['quiz_id'];

		$content = get_post_field('post_content',$quiz_id);
		$pattern = get_shortcode_regex(['wplms_h5p']);

		
		if (   preg_match_all( '/'. $pattern .'/s', $content, $matches ) ){

			
			$shortcode = $matches[0][0];
			$keys = array();
		    $result = array();
		    foreach( $matches[0] as $key => $value) {
		        $get = str_replace(" ", "&" , $matches[3][$key] );
		        parse_str($get, $output);
		        $keys = array_unique( array_merge(  $keys, array_keys($output)) );
		        $result[] = $output;

		    }
		    if( $keys && $result ) {
		        foreach ($result as $key => $value) {
		            foreach ($keys as $attr_key) {
		                $result[$key][$attr_key] = isset( $result[$key][$attr_key] ) ? $result[$key][$attr_key] : NULL;
		            }
		            ksort( $result[$key]);              
		        }
		    }
		    $content = str_replace($shortcode, '', $content);
		   	$type = 'h5p';
		   	$id = str_replace('"', '', $result[0]['id']);
		    wp_update_post(array('ID'=>$quiz_id,'post_content'=>$content));
		    update_post_meta($quiz_id,'wplms_h5p_content',intval($id));
		    update_post_meta($quiz_id,'vibe_type','h5p_quiz');
		}

		set_transient('sync_quiz_types_sync',$quiz_id,3600);
		wplms_get_quiz_type($quiz_id);
		die();
	}

	function sync_resync_batch_sync(){
		if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'sync_resync_batch_sync')){
	         _e('Security check Failed. Contact Administrator.','wplms');
	        die();
		}
		if(empty($this->sync_complete_batch_ids)){
			$this->sync_complete_batch_ids = get_transient('sync_complete_batch_ids');	
			if(empty($this->sync_complete_batch_ids)){
				$this->sync_complete_batch_ids=[];
			}
		}

		if(!in_array($_POST['group_id'],$this->sync_complete_batch_ids)){
			$this->sync_complete_batch_ids[]=$_POST['group_id'];
			set_transient('sync_complete_batch_ids',$this->sync_complete_batch_ids,3600);
		}
		
		if(!bp_course_is_member($_POST['course_id'], $_POST['user_id'])){
			bp_course_add_user_to_course($_POST['user_id'],$_POST['course_id']);
		}
		die();

	}
	function end_batch_sync(){
		if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'sync_resync_batch_sync')){
	         _e('Security check Failed. Contact Administrator.','wplms');
	        die();
		}
		echo __('Batch user sync complete !','wplms');
		die();
	}

	function sync_resync_unit_types_sync(){
		if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'sync_resync_unit_types_sync')){
	         _e('Security check Failed. Contact Administrator.','wplms');
	        die();
		}
		global $wpdb;
		$unit_id = $_POST['unit_id'];
		$type = wplms_get_element_type($unit_id,'unit');
		
		if(class_exists('Elementor\Plugin') && Elementor\Plugin::instance()->db->is_built_with_elementor( $unit_id ) ){
			$type = 'elementor';
		}

		$vc_enabled = get_post_meta($unit_id, '_wpb_vc_js_status', true);
		if(!empty($vc_enabled) && $vc_enabled){
			$type = 'text';
		}

		$content = get_post_field('post_content',$unit_id);
		$pattern = get_shortcode_regex(['wplms_h5p']);
	
		//checking question shortcodes
		if (  preg_match_all( '/' . get_shortcode_regex(array('question')) . '/', $content, $qmatches, PREG_SET_ORDER ) ){
			
			if ( !empty( $qmatches ) ){

				foreach ( $qmatches as $shortcode ) {
					preg_match('/id=?["|\']([0-9]*)?["|\']/',$shortcode[3],$question_id);
					if(!empty($question_id) && !empty($question_id[1])){
						$question_id = $question_id[1];
						$regex = get_shortcode_regex(array('question'));
	        			$content = preg_replace("/$regex/s", " ", $content);
	        			wp_update_post(array('ID'=>$unit_id,'post_content'=>$content));
			    		$practice_ques = get_post_meta($unit_id,'vibe_practice_questions',true);
			    		if(!empty($practice_ques)){
			    			if(!empty($practice_ques['type']) && $practice_ques['type']=='questions'){
			    				if(empty($practice_ques['value'])){
			    					$practice_ques['value']=[];
			    				}
			    				if(!in_array($question_id, $practice_ques['value'])){
		    						$practice_ques['value'][] = $question_id;
		    					}
			    			}
			    		}else{
			    			$practice_ques = array('type'=>'questions','value'=>[$question_id]);
			    		}
			    		update_post_meta($unit_id,'vibe_practice_questions',$practice_ques);
					}
				}	
			}
		}

		
		if (   preg_match_all( '/'. $pattern .'/s', $content, $matches ) ){
			$shortcode = $matches[0][0];
			$keys = array();
		    $result = array();
		    foreach( $matches[0] as $key => $value) {
		        $get = str_replace(" ", "&" , $matches[3][$key] );
		        parse_str($get, $output);
		        $keys = array_unique( array_merge(  $keys, array_keys($output)) );
		        $result[] = $output;

		    }
		    if( $keys && $result ) {
		        foreach ($result as $key => $value) {
		            foreach ($keys as $attr_key) {
		                $result[$key][$attr_key] = isset( $result[$key][$attr_key] ) ? $result[$key][$attr_key] : NULL;
		            }
		            ksort( $result[$key]);              
		        }
		    }
		    $content = str_replace($shortcode, '', $content);
		   	$type = 'h5p';
		   	$id = str_replace('"', '', $result[0]['id']);
		    wp_update_post(array('ID'=>$unit_id,'post_content'=>$content));
		    update_post_meta($unit_id,'wplms_h5p_content',intval($id));
		}else{
			$package_type = $url = $name = '';
	
			preg_match_all( '/' . get_shortcode_regex(array('iframe')) . '/', $content, $matches, PREG_SET_ORDER );
			if ( !empty( $matches ) ){

				foreach ( $matches as $shortcode ) {
					preg_match('/package_type=[\'|"](.*?)[\'|"]/',$shortcode[3],$_package_type);
					if(!empty($_package_type) && !empty($_package_type[1])){
						$package_type = $_package_type[1];
						if(!empty($shortcode[5])){
							$url = $shortcode[5];
							$name = basename($shortcode[5]);

					 	}
					}
					
				}
				if(!empty($url) && !empty($package_type)){
					$meta = array (
					  'package_type' => $package_type,
					  'path' => $url,
					  'name' => $name,
					  'file' => $name,
					);
					$regex = get_shortcode_regex(array('iframe'));
        			$content = preg_replace("/$regex/s", " ", $content);
        			wp_update_post(array('ID'=>$unit_id,'post_content'=>$content));
		    		update_post_meta($unit_id,'vibe_upload_package',$meta);
		    		$type = 'upload';
				}	
			}
		}



		if(empty($type)){
			$type='general';
		}
		update_post_meta($unit_id,'vibe_type',$type);
		set_transient('sync_unit_types',$unit_id,3600);
		die();
	}

	function sync_resync_calculate_question_correct_percentage(){
		if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'sync_resync_calculate_question_correct_percentage')){
	         _e('Security check Failed. Contact Administrator.','wplms');
	        die();
		}
		global $wpdb;
		
		$comments = $_POST['comments'];
		$question_id = $_POST['question_id'];
		
		if(!empty($comments) && !empty($question_id)){
			$_comments = implode(',', $comments);
			$results = $wpdb->get_results(
				"
				SELECT SUM(CASE 
				             WHEN t.meta_value > 0 THEN 1
				             ELSE 0
				           END) AS correct,
				       SUM(CASE 
				             WHEN t.meta_value <= 0 THEN 1
				             ELSE 0
				           END) AS incorrect
				  FROM {$wpdb->commentmeta} AS t WHERE meta_key = 'marks'
				  AND comment_id IN ({$_comments})
				"
			);
			if(!empty($results) && !empty($results[0])){
				if(!isset($results[0]->correct)){
					update_post_meta($question_id,'correct_count',0);
				}else{
					update_post_meta($question_id,'correct_count',$results[0]->correct);
					
				}
				if(!isset($results[0]->incorrect)){
					update_post_meta($question_id,'incorrect_count',0);
				}else{
					update_post_meta($question_id,'incorrect_count',$results[0]->incorrect);
				}

				set_transient('sync_calculate_question_correct_percentage',$question_id,3600);
			}
		}
		
		die();
	}

	function check_and_record_commission_activity_and_meta($instructor_id,$course_id,$item_id,$commission,$date_recorded,$currency=null){
		if(!empty($instructor_id) && !empty($item_id) &&  !empty($course_id) &&  isset($commission)){
			global $wpdb,$bp;
			$activity_id = $wpdb->get_var($wpdb->prepare( "
	                            SELECT activity.id
	                            FROM {$bp->activity->table_name} AS activity 
	                            WHERE     activity.component     = 'course'
	                            AND     activity.type     = 'course_commission'
	                            AND     activity.user_id = %d
	                            AND     activity.item_id = %d
	                            AND     activity.secondary_item_id = %d
	                            ORDER BY activity.id DESC LIMIT 0,1
	                        " ,$instructor_id,$course_id,$item_id));

			
			if(empty($activity_id)){
				$commission_html = '';
	            if(function_exists('wc_price')){
	                $price = round($commission,0);
	                $commission_html = wc_price($price);
	            }
	            
				$activity_id = bp_course_record_activity(apply_filters('bp_course_record_instructor_commission_activity',array(
	                'user_id' => $instructor_id,
	                'action' => _x('You earned commission','Instructor earned commission activity','wplms'),
	                'content' => sprintf(_x('%s commission earned for course %s','Instructor earned commission activity','wplms'),$commission_html,get_the_title($course_id)),
	                'component' => 'course',
	                'type' => 'course_commission',
	                'item_id' => $course_id,
	                'secondary_item_id' => $item_id,
	                'hide_sitewide' => true,
	                'date_recorded' => $date_recorded,
	            )));
			}else{
				$activity_date = $wpdb->get_var($wpdb->prepare( "
	                            SELECT activity.date_recorded
	                            FROM {$bp->activity->table_name} AS activity 
	                            WHERE     activity.component     = 'course'
	                            AND     activity.type     = 'course_commission'
	                            AND     activity.user_id = %d
	                            AND     activity.item_id = %d
	                            AND     activity.secondary_item_id = %d
	                            ORDER BY activity.id DESC LIMIT 0,1
	                        " ,$instructor_id,$course_id,$item_id));
				$activity_timestamp = strtotime($activity_date);
				$date_recorded_timestamp = strtotime($date_recorded);
				if(!empty($activity_date) && !empty($activity_timestamp) && function_exists('bp_activity_add')){
					$difference = $activity_timestamp - $date_recorded_timestamp;
					
					$threshold_value = apply_filters('woocommerce_inst_commissions_activity_date_threshold_value',1800,$instructor_id,$course_id,$item_id,$commission,$date_recorded);
					if($difference > $threshold_value){
						
						bp_activity_add(array(
							'activity_id'=>$activity_id,
			                'user_id' => $instructor_id,
			                'action' => _x('You earned commission','Instructor earned commission activity','wplms'),
			                'content' => sprintf(_x('%s commission earned for course %s','Instructor earned commission activity','wplms'),$commission_html,get_the_title($course_id)),
			                'component' => 'course',
			                'type' => 'course_commission',
			                'item_id' => $course_id,
			                'secondary_item_id' => $item_id,
			                'hide_sitewide' => true,
			                'date_recorded' => $date_recorded,
			            ));
					}
				}
			}

			$activity_meta_id = $wpdb->get_var($wpdb->prepare( "
	                            SELECT activitymeta.id
	                            FROM {$bp->activity->table_name} AS activity  
	                            LEFT JOIN {$bp->activity->table_name_meta} AS activitymeta
	                            ON activity.id = activitymeta.activity_id
	                            WHERE     activity.component     = 'course'
	                            AND     activity.type     = 'course_commission'
	                            AND     activity.user_id = %d
	                            AND     activity.item_id = %d
	                            AND     activity.secondary_item_id = %d
	                            AND     activitymeta.meta_key = %s 
	                            AND     activitymeta.meta_value = %s
	                            ORDER BY activitymeta.id DESC LIMIT 0,1
	                        " ,$instructor_id,$course_id,$item_id,'_commission'.$instructor_id,$commission));
			
			if(!empty($activity_id) && empty($activity_meta_id)){
				bp_course_record_activity_meta(array('id'=>$activity_id,'meta_key'=>'_commission'.$instructor_id,'meta_value'=>$commission));
			}

			//fallbacak for currency :
			if(!empty($currency)){
				$activity_meta_id_c = $wpdb->get_var($wpdb->prepare( "
	                            SELECT activitymeta.id
	                            FROM {$bp->activity->table_name} AS activity  
	                            LEFT JOIN {$bp->activity->table_name_meta} AS activitymeta
	                            ON activity.id = activitymeta.activity_id
	                            WHERE     activity.component     = 'course'
	                            AND     activity.type     = 'course_commission'
	                            AND     activity.user_id = %d
	                            AND     activity.item_id = %d
	                            AND     activity.secondary_item_id = %d
	                            AND     activitymeta.meta_key = %s 
	                            AND     activitymeta.meta_value = %s
	                            ORDER BY activitymeta.id DESC LIMIT 0,1
	                        " ,$instructor_id,$course_id,$item_id,'_currency'.$instructor_id,$currency));
				if(!empty($activity_id) && empty($activity_meta_id_c)){
					bp_course_record_activity_meta(array('id'=>$activity_id,'meta_key'=>'_currency'.$instructor_id,'meta_value'=>$currency));
				}
			}

		}
		
	}

	function sync_resync_woocommerce_instructor_commissions_bundle(){

		if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'sync_resync_woocommerce_instructor_commissions_bundle')){
	         _e('Security check Failed. Contact Administrator.','wplms');
	        die();
		}

		$order_id= $_POST['order_id'];
		$instructor_id = $_POST['instructor_id'];
	
		
		if(!empty($instructor_id) && !empty($order_id) && class_exists('WC_Order')){
			$order = new WC_Order( $order_id );
			if(empty($order) || is_wp_error($order)){
				die();
			}
			$currency = '';
			if(method_exists($order,'get_currency'))
            	$currency = $order->get_currency();
			$items = $order->get_items();
			$order_total = $order->get_total();
			$commission_array=array();
			$date_recorded = !empty($order->get_date_completed()) ? $order->get_date_completed()->date('Y-m-d H:i:s') : '';
			foreach($items as $item_id=>$item){
			  	$instructors=array();
			  	$courses=get_post_meta($item['product_id'],'vibe_courses',true);
			  	$line_total=$item['line_total'];
			  	if(isset($courses) && is_array($courses) && count($courses) > 1){
					foreach($courses as $course){
						$instructors[$course]=apply_filters('wplms_course_instructors',get_post_field('post_author',$course,'raw'),$course);
						$commission_array[$item_id]=array(
							'instructor'=>$instructors,
							'course'=>$courses,
							'total'=>$line_total,
							'currency'=>$currency
						);
				        
					}
			  	}//End If courses
			}// End Item for loop

			
			if(!empty($commission_array)){
				if(function_exists('vibe_get_option'))
			      	$instructor_commission = vibe_get_option('instructor_commission');
			    
			    if($instructor_commission == 0)
			      		return;
			      	
			    if(!isset($instructor_commission) || !$instructor_commission)
			      $instructor_commission = 70;

			    $commissions = get_option('instructor_commissions');
				foreach($commission_array as $item_id=>$commission_item){

					foreach($commission_item['course'] as $course_id){
						
						if(count($commission_item['instructor'][$course_id]) > 1){     // Multiple instructors
							
							$calculated_commission_base=round(($commission_item['total']*($instructor_commission/100)/count($commission_item['instructor'][$course_id])),0); // Default Slit equal propertion

							foreach($commission_item['instructor'][$course_id] as $instructor){
								if(empty($commissions[$course_id][$instructor]) && !is_numeric($commissions[$course_id][$instructor])){
									$calculated_commission_base = round(($commission_item['total']*$instructor_commission/100),2);
								}else{
									$calculated_commission_base = round(($commission_item['total']*$commissions[$course_id][$instructor]/100),2);
								}
								$calculated_commission_base = apply_filters('wplms_calculated_commission_base',$calculated_commission_base,$instructor);

		                        //bp_course_record_instructor_commission($instructor,$calculated_commission_base,$course_id,array('origin'=>'woocommerce','order_id'=>$order_id,'item_id'=>$item_id));

								$this->check_and_record_commission_activity_and_meta($instructor,$course_id,$item_id,$calculated_commission_base,$date_recorded,$commission_item['currency']);
		                        
							}
						}else{
							if(is_array($commission_item['instructor'][$course_id]))                                    // Single Instructor
								$instructor=$commission_item['instructor'][$course_id][0];
							else
								$instructor=$commission_item['instructor'][$course_id]; 
							
							if(isset($commissions[$course_id][$instructor]) && is_numeric($commissions[$course_id][$instructor]))
								$calculated_commission_base = round(($commission_item['total']*$commissions[$course_id][$instructor]/100),2);
							else
								$calculated_commission_base = round(($commission_item['total']*$instructor_commission/100),2);

							$calculated_commission_base = apply_filters('wplms_calculated_commission_base',$calculated_commission_base,$instructor);

		                    // bp_course_record_instructor_commission($instructor,$calculated_commission_base,$course_id,array('origin'=>'woocommerce','order_id'=>$order_id,'item_id'=>$item_id));
		                    $this->check_and_record_commission_activity_and_meta($instructor,$course_id,$item_id,$calculated_commission_base,$date_recorded,$commission_item['currency']);
						}   
					}

				} // End Commissions_array
			}
			  
		}
	}

	function sync_resync_woocommerce_instructor_commissions(){
		if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'sync_resync_woocommerce_instructor_commissions')){
	         _e('Security check Failed. Contact Administrator.','wplms');
	        die();
		}

		$order_id= $_POST['order_id'];
		$instructor_id = $_POST['instructor_id'];
		$item_id = $_POST['item_id'];
		$commission = $_POST['commission'];
		$order ='';$date_recorded = '';
		if(function_exists('wc_get_order')){
			$order = wc_get_order($order_id);
		}
		$currency = '';
		if(method_exists($order,'get_currency'))
        	$currency = $order->get_currency();
		
		if(!empty($order) && !is_wp_error($order))
			$date_recorded = !empty($order->get_date_completed()) ? $order->get_date_completed()->date('Y-m-d H:i:s') : '';
		if(!empty($instructor_id) && !empty($item_id) && !empty($commission)){
			$product_id = wc_get_order_item_meta($item_id,'_product_id',true);

			//$price = get_post_meta($product_id,'_regular_price',true);
			$courses = get_post_meta($product_id,'vibe_courses',true);

			global $wpdb,$bp;
			if(!empty($courses)){
				if(count($courses) == 1){
					//update activity if exists or create a new

					$course_id = $courses[0];
					$this->check_and_record_commission_activity_and_meta($instructor_id,$course_id,$item_id,$commission,$date_recorded,$currency);
				}
			}
		}
		
	}

	function sync_resync_unit_students(){
		if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'sync_resync_unit_students')){
	         _e('Security check Failed. Contact Administrator.','wplms');
	        die();
		}
		global $wpdb;
		$unit_id = $_POST['unit_id'];
		$users = $_POST['users'];
		if(!empty($users) && function_exists('bp_course_get_unit_course_id')){
			$course_id = bp_course_get_unit_course_id($unit_id);

			if(!empty($course_id)){
				foreach($users as $user_id){
					$time = get_user_meta($user_id,$unit_id,true); 
					if(!empty($time)){
						bp_course_update_user_unit_completion_time($user_id,$unit_id,$course_id,$time);
						delete_user_meta($user_id,$unit_id);
					}
				}
			}
			set_transient('unit_students',$course_id,3600);
		}
		die();
	}

	function sync_resync_delete_old_question_comments(){
		if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'sync_resync_delete_old_question_comments')){
	         _e('Security check Failed. Contact Administrator.','wplms');
	        die();
		}
		$quiz_id = $_POST['quiz_id'];
		global $wpdb,$bp;

		$activity_meta_count = $wpdb->get_var($wpdb->prepare( "
                                    SELECT COUNT(meta.meta_value)
                                    FROM {$bp->activity->table_name} AS activity 
                                    LEFT JOIN {$bp->activity->table_name_meta} as meta ON activity.id = meta.activity_id
                                    WHERE     activity.component     = 'course'
                                    AND     activity.type     = 'quiz_evaluated'
                                    AND     meta.meta_key   = 'quiz_results'
                                    AND     activity.secondary_item_id = %d
                                " ,$quiz_id));
		$activity_counts = $wpdb->get_results($wpdb->prepare( "
                                    SELECT COUNT(activity.id) as count,activity.type
                                    FROM {$bp->activity->table_name} AS activity 
                                    WHERE     activity.component     = 'course'
                                    AND     (activity.type='quiz_evaluated' || activity.type='retake_quiz')
                                    AND     activity.secondary_item_id = %d
                                   	GROUP BY activity.type
                                " ,$quiz_id),ARRAY_A);
		$evaluate_count = 0;
		$retake_count = 0;
		
		if(!empty($activity_counts )){
			foreach ($activity_counts as $key => $acount) {
				if(!empty($acount)){
					if($acount['type'] == 'retake_quiz'){
						$retake_count = intval($acount['count']);
					}
					if($acount['type'] == 'quiz_evaluated'){
						$evaluate_count = intval($acount['count']);
					}
					
				}
				
			}
		}
		
		if($retake_count > $evaluate_count){
			$actual_activity_count = $retake_count - $evaluate_count;
		}else{
			$actual_activity_count = $evaluate_count - $retake_count;
		}
		
		if(!empty($activity_meta_count) && !empty($actual_activity_count) && $activity_meta_count==$actual_activity_count){
			//now delete the comments .
			$comment_ids = $wpdb->get_results($wpdb->prepare("SELECT c.comment_ID
											FROM {$wpdb->comments} as c
											LEFT JOIN {$wpdb->commentmeta} as cm
											ON c.comment_ID = cm.comment_id
											WHERE cm.meta_value = %d
											AND cm.meta_key = 'quiz_id'

											",$quiz_id));
			if(!empty($comment_ids)){
				foreach ($comment_ids as $key => $comment) {
					wp_delete_comment($comment->comment_ID,true);
				}
			}
		}
		die();
	}

	function end_delete_old_question_comments_sync(){
		if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'sync_resync')){
	         _e('Security check Failed. Contact Administrator.','wplms');
	        die();
		}
		echo __('Deleting old question comments complete !','wplms');
		die();
	}

	function end_unit_students_sync(){
		if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'sync_resync')){
	         _e('Security check Failed. Contact Administrator.','wplms');
	        die();
		}
		delete_transient('sync_unit_students');
		echo __('Course - Units - Student sync complete !','wplms');
		die();
	}

	function end_unit_attachments_sync(){
		if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'sync_resync')){
	         _e('Security check Failed. Contact Administrator.','wplms');
	        die();
		}
		delete_transient('sync_unit_attachments');
		echo __('Unit - attachments sync complete !','wplms');
		die();
	}

	function end_vibe_payouts_sync(){
		if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'sync_resync')){
	         _e('Payouts structure upgraded.','wplms');
	        die();
		}
		delete_transient('sync_vibe_payouts');
		echo __('Unit - attachments sync complete !','wplms');
		die();
	}
	

	function sync_resync_course_forums(){
		if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'sync_resync_course_forums')){
	         _e('Security check Failed. Contact Administrator.','wplms');
	        die();
		}
		$course_id = $_POST['course_id'];
		$forum_id = $_POST['forum_id'];
		if(function_exists('bp_course_get_course_students') && !empty($forum_id) && function_exists('bbp_add_user_forum_subscription')){
			$instructor_id = get_post_field('post_author',$course_id);
			bbp_add_user_forum_subscription( $instructor_id, $forum_id);
			$students = bp_course_get_course_students($course_id,0,9999);
			if(!empty($students['students'])){
				foreach($students['students'] as $user_id){
				      bbp_add_user_forum_subscription( $user_id, $forum_id);
				}
			}
		}
		die();
	}

	function end_course_forums_sync(){
		if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'sync_resync')){
	         _e('Security check Failed. Contact Administrator.','wplms');
	        die();
		}
		delete_transient('sync_course_forums');
		echo __('Course - Forums sync complete !','wplms');
		die();
	}

	function end_woocommerce_instructor_commissions_sync(){
		if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'sync_resync')){
	         _e('Security check Failed. Contact Administrator.','wplms');
	        die();
		}
		delete_transient('sync_woocommerce_instructor_commissions');
		echo __('Instructror commissions migrated  !','wplms');
		die();
	}

	/*
	WALLET */
	function check_wallet($tabs){
		
		if(empty($this->settings)){
			$this->settings=get_option('lms_settings');	
		}

		if(!empty($this->settings['api'])){
			if(!empty($this->settings['api']['wallet']) && $this->settings['api']['wallet'] == 'on'){
				$tabs['wallet']=  __('Wallet','wplms');
			}
		}
		return $tabs;

	}

	function user_wallets($get){
		if($get['sub'] == 'wallet'){
			echo '<h3>'.__('User Wallets','wplms').'</h3>';
			echo '<p>'.__('Manage user wallets for your APP','wplms').'</p>';
			global $wpdb;
			if(!function_exists('bp_core_get_user_displayname')){
				echo ' BuddyPress is Inactive';
			}

			wp_deregister_script('badgeos-select2');
	        wp_dequeue_script('badgeos-select2');
	        wp_deregister_script('select2');
	        wp_dequeue_script('select2');
	        wp_dequeue_style('badgeos-select2-css');
	        wp_deregister_style('badgeos-select2-css');
	        
	        wp_enqueue_script('wplms-api-clients-select2-js',WPLMS_PLUGIN_INCLUDES_URL.'/vibe-customtypes/metaboxes/js/select2.min.js');
			wp_enqueue_style('wplms-api-clients-select2-css',WPLMS_PLUGIN_INCLUDES_URL.'/vibe-customtypes/metaboxes/css/select2.min.css');
			
			wp_nonce_field('vibe_security','vibe_security');
			//order options 
			$order_options = apply_filters('wplms_api_connected_cleints_order_options',array(
					'DESC'=>_x('Latest','','wplms'),
					'ASC'=>_x('Oldest','','wplms'),
					
				)
			);
			if(isset($_GET['wplms_api_wallet_userid'])){
				$wplms_api_wallet_userid = $_GET['wplms_api_wallet_userid'];
			}
			$customPagHTML     = "";
			$query             = apply_filters('wplms_usermeta_direct_query',"SELECT * from {$wpdb->usermeta} WHERE meta_key = 'wallet'");
			$total_query     = apply_filters('wplms_usermeta_direct_query',"SELECT COUNT(*) from {$wpdb->usermeta} WHERE meta_key = 'wallet'");
			if(!empty($wplms_api_wallet_userid) && (is_numeric($wplms_api_wallet_userid) || is_array($wplms_api_wallet_userid))){
				if(is_array($wplms_api_wallet_userid)){
					$user_ids = implode(',', $wplms_api_wallet_userid);
					$total_query .= 'AND user_id IN ('.$user_ids.')';
					
				}else{
					
					$user_ids = $wplms_api_wallet_userid;
					$total_query .= 'AND user_id = '.$user_ids;
				}
			}


			$total             = $wpdb->get_var( $total_query );
			$items_per_page = apply_filters('wplms_api_wallet_pagination_count',10);
			$page             = isset( $_GET['w_page'] ) ? abs( (int) $_GET['w_page'] ) : 1;
			$offset         = ( $page * $items_per_page ) - $items_per_page;

			$add_where = '';
			if(!empty($wplms_api_wallet_userid) && (is_numeric($wplms_api_wallet_userid) || is_array($wplms_api_wallet_userid))){
				
				if(is_array($wplms_api_wallet_userid)){
					$user_ids = implode(',', $wplms_api_wallet_userid);
					$add_where .= 'AND user_id IN ('.$user_ids.')';
					
				}else{
					
					$user_ids = $wplms_api_wallet_userid;
					$add_where .= 'AND user_id = '.$user_ids;
				}
			}

			if(isset($_GET['wplms_api_wallet_orderby'])){
				$wplms_api_wallet_orderby = sanitize_text_field($_GET['wplms_api_wallet_orderby']);
			}else{
				$wplms_api_wallet_orderby = 'DESC';
			}

			$users         = $wpdb->get_results( $query . " {$add_where} ORDER BY meta_value {$wplms_api_wallet_orderby} LIMIT ${offset}, ${items_per_page}" );


			$totalPage         = ceil($total / $items_per_page);
			if($totalPage > 1){

				$clients_pagination     =  '<div class="w_page"><span>Page '.$page.' of '.$totalPage.'</span>'.paginate_links( array(
				'base' => add_query_arg( 'w_page', '%#%' ),
				'format' => '',
				'prev_text' => __('&laquo;'),
				'next_text' => __('&raquo;'),
				'total' => $totalPage,
				'current' => $page
				)).'</div>';
				
			}

			?>
			<script>
				
				jQuery(document).ready(function($){
					jQuery(".edit_user_wallet").click(function(){
						jQuery(this).parent().find(".hide_box").toggle(200);
					});
					jQuery(".update_user_wallet").on("click",function(){
						var $this = jQuery(this);
						var wallet_amount =jQuery(this).parent().find(".wallet_amount").val();
						jQuery.ajax({
			              type: "POST",
			              url: ajaxurl,
			              data: { action: "update_user_wallet_amount",
			                      wallet_user_id: jQuery(this).parent().find(".wallet_user_id").val(),
			                      wallet_amount: wallet_amount,
			                      security:jQuery('#vibe_security').val(),
			                    },
			              cache: false,
			              success: function (html) {
			              	var d = $this.html();
			              	$this.html(html);
			                setTimeout(function(){
			                	$this.html(d);
			                },4000);
			                
			                $this.closest('.wallet_user_row').find('.wallet_amount_td').html(wallet_amount);
			              }
			            });
					});
					jQuery('.selectusers_clients').each(function(){
				    	var $this = jQuery(this);
					    $this.select2({
					        minimumInputLength: 4,
					        placeholder: jQuery(this).attr('data-placeholder'),
					        closeOnSelect: true,
					        language: {
					          inputTooShort: function() {
					            return '<?php echo _x('Please type atleast four characters','','wplms');?>';
					          }
					        },
					        ajax: {
					            url: ajaxurl,
					            type: "POST",
					            dataType: 'json',
					            delay: 250,
					            data: function(term){ 
					                    return  {   action: 'select_users_api_clients', 
					                                security: jQuery('#vibe_security').val(),
					                                q: term,
					                            }
					            },
					            processResults: function (data) {
					                return {
					                    results: data
					                };
					            },       
					            cache:true  
					        },
					        templateResult: function(data){
					            return '<img width="32" src="'+data.image+'">'+data.text;
					        },
					        templateSelection: function(data){
					            return '<img width="32" src="'+data.image+'">'+data.text;
					        },
					        escapeMarkup: function (m) {
					            return m;
					        }
					    });
				  	});
				});
			</script>
			<style>
				.w_page {
				    margin: 15px 0;
				    float:right;
				}
				.hide_box{display:none;}
				input.button.button-primary {
				    margin-top: 5px;
				}
				.w_page span {margin:5px;}
				.w_page a:not(.current),.w_page span.page-numbers{background:#006799;color:#FFF;padding:5px 8px;border-radius:3px;font-size:1.2em;text-decoration:none;}
				.w_page span.page-numbers.current{opacity:0.6;}
			</style>
			<form id="wallet_username_orderby_form" method="get" action="?<?php echo $_SERVER['QUERY_STRING']; ?>">
				<?php
				
					foreach($_GET as $key => $value){
						if(!in_array($key,array('wplms_api_wallet_userid[]','wplms_api_wallet_orderby','w_page'))){
							echo '<input type="hidden" name="'.$key.'" value="'.$value.'" />';
						}
					}
				?>
				<?php echo $clients_pagination; ?>
				<select name="wplms_api_wallet_userid[]" id="wplms_api_wallet_userid" class="selectusers_clients" data-placeholder="<?php echo __('Enter Student Usernames/Emails, separated by comma','wplms');?>" multiple>
					<?php
					$user_ids = $_GET['wplms_api_wallet_userid'];
	                if(!empty($user_ids) ){
	                	if(is_array($user_ids)){
	                		foreach ($user_ids as $userid) {
		                       $user =  get_user_by($userid);
		                       echo '<option value="'.$userid.'" selected="selected">'.bp_core_fetch_avatar(array('item_id' => $userid, 'type' => 'thumb', 'width' => 32, 'height' => 32)).''.bp_core_get_user_displayname($userid).'</option>';
		                    }
	                	}else{
	                		echo '<option value="'.$user_ids.'" selected="selected">'.bp_core_fetch_avatar(array('item_id' => $user_ids, 'type' => 'thumb', 'width' => 32, 'height' => 32)).''.bp_core_get_user_displayname($user_ids).'</option>';
	                	}
	                    
	                }
	                ?>
				</select>
				<label><?php echo _x('Order by','search text api connected client','wplms');?></label>
				<select name="wplms_api_wallet_orderby">
					<?php

					foreach ($order_options as $key => $value) {
						echo '<option value="'.$key.'" '.((!empty($_GET['wplms_api_wallet_orderby']) && $key==$_GET['wplms_api_wallet_orderby'])?'selected="selected"':'').'>'.$value.'</option>';
					}
					?>
				</select>
				<input type="submit" class="button button-primary" value="<?php echo _x('Go','go button label','wplms'); ?>">

			</form>


			<?php


			
			if(!empty($users)){
				echo '<table class="wp-list-table widefat fixed striped wp_list_test_links">
						<thead>
							<tr>
								<th scope="col" id="name" class="manage-column column-name column-primary">'._x('User','wallet table','wplms').'</th>
								<th scope="col" id="description" class="manage-column column-description">'._x('Wallet','wallet table','wplms').'</th>
								<th scope="col" id="description" class="manage-column column-description">'._x('Actions','wallet table','wplms').'</th>
							</tr>
						</thead>
						<tbody>';
				foreach($users as $user){
					$user_activity_url = '';
					if(function_exists('bp_core_get_userlink')){
						$user_activity_url =  bp_core_get_user_domain($user->user_id );
					}
					if(defined('BP_ACTIVITY_SLUG')){
						$user_activity_url .= BP_ACTIVITY_SLUG;
					}
					echo '<tr valign="top" class="wallet_user_row">
							<td>
								'.bp_core_get_user_displayname($user->user_id).'
							</td>
							<td class="wallet_amount_td">
								'.$user->meta_value.'
							</td>
							<td>
								<ul class="user_wallet_extras">
									<li><a class="edit_user_wallet" href="'.$user_activity_url.'" title="'._x('See user activity','see user activity label','wplms').'"><span class="dashicons dashicons-visibility"></span></a> / </li>
									<li><a class="edit_user_wallet"><span class="dashicons dashicons-edit"></span>
									</a>
									<div class="hide_box">
										<input type="text" class="wallet_amount" value="'.$user->meta_value.'">
										<input type="hidden" class="wallet_user_id" value="'.$user->user_id.'">
										<a class="update_user_wallet button">'.__('Update','wplms').'</a>
									</div>

									</li>
								</ul>
								
							</td>
						</tr>';
				}
				echo '</tbody><tfoot>
							<tr>
								<th scope="col" id="name" class="manage-column column-name column-primary">'._x('User','wallet table','wplms').'</th>
								<th scope="col" id="description" class="manage-column column-description">'._x('Wallet','wallet table','wplms').'</th>
								<th scope="col" id="description" class="manage-column column-description">'._x('Actions','wallet table','wplms').'</th>
							</tr>
						</tfoot></table>';
			}else{
				echo '<div class="message"><p>'._x('No users have created wallet in APP.','wallet','wplms').'</p></div>';
			}
		}
	}
}

add_action('plugins_loaded',function(){
	wplms_miscellaneous_settings::init();
},2);


function wplms_get_sync_settings(){
	return apply_filters('wplms_sync_settings',array(
		array(
			'id'=>'course_students',
			'label'=>__('Course - Students','wplms'),
			'description'=>__('Verify Course Student Status and expiry for all students and courses','wplms'),

		),
		array(
			'id'=>'unit_students',
			'label'=>__('Unit - Students','wplms'),
			'description'=>__('Verify student unit completion for reusability','wplms'),
		),
		array(
			'id'=>'quiz_results',
			'label'=>__('Quiz Results','wplms'),
			'description'=>__('Verify Quiz Student Status and results for all students and quizzes','wplms'),
		),
		array(
			'id'=>'course_forums',
			'label'=>__('Course Forums','wplms'),
			'description'=>__('Auto-subscribe course users to course forums.','wplms'),
		),
		array(
			'id'=>'unit_attachments',
			'label'=>__('Unit attachments','wplms'),
			'description'=>__('Re-link the unit attachments in proper way.','wplms'),
		),
		array(
			'id'=>'woocommerce_instructor_commissions',
			'label'=>__('Woocommerce Intructor commissions(for non-bundled products)','wplms'),
			'description'=>__('Migrates woocommerce instructor commissions to activity meta from order meta for non-bundled(products having single course associated) products only.','wplms'),
		),
		array(
			'id'=>'woocommerce_instructor_commissions_bundle',
			'label'=>__('Woocommerce Intructor commissions(for bundled products)','wplms'),
			'description'=>__('Migrates woocommerce instructor commissions to activity meta from order meta for bundled(products having more than one course associated) products only.','wplms'),
		),
		array(
			'id'=>'delete_old_question_comments',
			'label'=>__('Delete Old Question Comments','wplms'),
			'description'=>__('Deletes unnecessary comments from your site helps optmizing comments table','wplms'),
		),
		array(
			'id'=>'vibe_payouts',
			'label'=>__('Migrate payouts','wplms'),
			'description'=>__('Migrate payouts to new structure for graphs','wplms'),
		),
		array(
			'id'=>'calculate_question_correct_percentage',
			'label'=>__('Calculate question correct percentages','wplms'),
			'description'=>__('Calculate question\'s correct percentage to show in quiz stats','wplms'),
		),
		array(
			'id'=>'quiz_types_sync',
			'label'=>__('Sync Quiz types from V3 to V4','wplms'),
			'description'=>__('Changes old Quiz types according to new unit types by checking data','wplms'),
			'required_for_upgrade'=>true,
		),
		array(
			'id'=>'unit_types_sync',
			'label'=>__('Sync unit types from V3 to V4','wplms'),
			'description'=>__('Changes old unit types according to new unit types by checking data','wplms'),
			'required_for_upgrade'=>true,
		),
		array(
			'id'=>'batch_sync',
			'label'=>__('Sync Batch Students Sync from V3 to V4','wplms'),
			'description'=>__('Syncs batch students from version3 to version 4','wplms'),
		),
	));
}