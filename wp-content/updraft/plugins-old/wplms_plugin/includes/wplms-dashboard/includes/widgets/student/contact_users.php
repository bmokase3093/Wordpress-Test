<?php

add_action( 'widgets_init', 'wplms_dash_contact_users_widget' );

function wplms_dash_contact_users_widget() {
    register_widget('wplms_dash_contact_users');
}

class wplms_dash_contact_users extends WP_Widget {
 
 
    /** constructor -- name this the same as the class above */
    function __construct() {
    $widget_ops = array( 'classname' => 'wplms_dash_contact_users', 'description' => __('Contact form Widget', 'wplms') );
    $control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'wplms_dash_contact_users' );
    parent::__construct( 'wplms_dash_contact_users', __(' DASHBOARD : Contact Form', 'wplms'), $widget_ops, $control_ops );
    

      add_action('wp_enqueue_scripts',array($this,'enqueue_script'));

    
      add_filter('vibebp_member_dashboard_widgets',array($this,'add_custom_script'));
    }
    
    function add_custom_script($args){
      $args[]='wplms_dash_contact_users';
      return $args;
    }
        
    function enqueue_script(){
        if(bp_current_component() == 'dashboard' || apply_filters('vibebp_enqueue_profile_script',false)){
            wp_enqueue_script( 'contact_users', WPLMS_PLUGIN_URL.'/assets/js/contact_user.js' ,array('wp-element','wp-data'),WPLMS_PLUGIN_VERSION,true);
            wp_enqueue_style('wplms_dashboard_css',WPLMS_PLUGIN_URL.'/assets/css/dashboard.css',array(),WPLMS_PLUGIN_VERSION);
            
            wp_localize_script('contact_users','contact_users',array(
              'settings'=>array(),
              'api'=>rest_url(BP_COURSE_API_NAMESPACE.'/dashboard/widget/contact_users'),
              
              'translations'=>array(
                'select_user_groups' => __('Select user groups','wplms'),
                'select_a_user_groups' => __('Select a user groups','wplms'),
                'select_instructor' =>  __('Select Instructor','wplms'),
                'friends' => __('Friends','wplms'),
                'administrator' => __('Administrator','wplms'),
                'course_students' => __('Course Students','wplms'),
                'instructor' => __('Instructor','wplms'),
                'type_name_to_autocomplete' => __('Type name to auto complete','wplms'),
                'enter_message' => __('Enter message','wplms'),
                'enter_subject' => __('Enter subject','wplms'),
                'send_message' => __('Send message','wplms'),
                'remove' => __('Remove','wplms'),
              )
            ));
        }
    }

    /** @see WP_Widget::widget -- do not rename this */
    function widget( $args, $instance ) {
    extract( $args );

    //Our variables from the widget settings.
    $title = apply_filters('widget_title', $instance['title'] );
    
    $users =  $instance['users'];
    $width =  $instance['width'];

    
    $user_id= apply_filters('wplms_dashboard_student_id',get_current_user_id());  
              
    // wp_enqueue_style( 'wplms-magic-suggest-css', plugins_url( '../../../css/magicsuggest-min.css' , __FILE__ ));
    // wp_enqueue_script( 'wplms-magic-suggest-js', plugins_url( '../../../js/magicsuggest-min.js' , __FILE__ ));
    
    //React start 
    

    echo '<div class="'.$width.'">';
    echo '<div class="dash-widget wplms_dash_contact_users">'.$before_widget;
      echo $after_widget.'
    </div>
    </div>';
    // React end
   
                
    }
 
    /** @see WP_Widget::update -- do not rename this */
    function update($new_instance, $old_instance) {   
	    $instance = $old_instance;
	    $instance['title'] = strip_tags($new_instance['title']);
	    $instance['users'] = $new_instance['users'];
      $instance['show_instructors_only'] = $new_instance['show_instructors_only'];
	    $instance['width'] = $new_instance['width'];
	    return $instance;
    }
 
    /** @see WP_Widget::form -- do not rename this */
    function form($instance) {  
        $defaults = array( 
                        'title'  => __('Contact Instructors','wplms'),
                        'users' => 1,
                        'show_instructors_only'=>0,
                        'width' => 'col-md-6 col-sm-12'
                    );
  		  $instance = wp_parse_args( (array) $instance, $defaults );
        $title  = esc_attr($instance['title']);
        $users = esc_attr($instance['users']);
        $width = esc_attr($instance['width']);
        $show_instructors_only = esc_attr($instance['show_instructors_only']);
        
        ?>
        <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','wplms'); ?></label> 
          <input class="regular_text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <p>
          <label for="<?php echo $this->get_field_id('users'); ?>"><?php _e('Show User select dropdown','wplms'); ?></label> 
          <input class="checkbox" id="<?php echo $this->get_field_id( 'users' ); ?>" name="<?php echo $this->get_field_name( 'users' ); ?>" type="checkbox" value="1"  <?php checked($users,1,true) ?>/>
        </p>
        <p>
          <label for="<?php echo $this->get_field_id('show_instructors_only'); ?>"><?php _e('Show Only instructors to send message','wplms'); ?></label> 
          <input class="checkbox" id="<?php echo $this->get_field_id( 'show_instructors_only' ); ?>" name="<?php echo $this->get_field_name( 'show_instructors_only' ); ?>" type="checkbox" value="1"  <?php checked($show_instructors_only,1,true) ?>/>
        </p>
        <p>
          <label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Select Width','wplms'); ?></label> 
          <select id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>">
          	<option value="col-md-3 col-sm-6" <?php selected('col-md-3 col-sm-6',$width); ?>><?php _e('One Fourth','wplms'); ?></option>
          	<option value="col-md-4 col-sm-6" <?php selected('col-md-4 col-sm-6',$width); ?>><?php _e('One Third','wplms'); ?></option>
          	<option value="col-md-6 col-sm-12" <?php selected('col-md-6 col-sm-12',$width); ?>><?php _e('One Half','wplms'); ?></option>
            <option value="col-md-8 col-sm-12" <?php selected('col-md-8 col-sm-12',$width); ?>><?php _e('Two Third','wplms'); ?></option>
             <option value="col-md-8 col-sm-12" <?php selected('col-md-9 col-sm-12',$width); ?>><?php _e('Three Fourth','wplms'); ?></option>
          	<option value="col-md-12" <?php selected('col-md-12',$width); ?>><?php _e('Full','wplms'); ?></option>
          </select>
        </p>
        <?php 
    }

    function get_friends(){
        $user_id = get_current_user_id();
        if(function_exists('friends_get_friend_user_ids')){
        $friends = friends_get_friend_user_ids( $user_id );
        foreach($friends as $key=>$friend){
          $friends[$key] = array(
            'id' => $friend,
            'pic' => bp_core_fetch_avatar ( array( 'item_id' => $friend, 'type' => 'thumb' ) ),
            'name' => bp_core_get_user_displayname($friend),
            );
        }
        echo json_encode($friends);
        }
        die();
    }

    function get_instructors(){
      $user_query = new WP_User_Query( array( 'role' => 'Instructor' ) );
      $instructors =array();
      if ( isset($user_query) && !empty( $user_query->results ) ) {
          foreach ( $user_query->results as $user ) {
              $instructors[]=array(
                'id' => $user->ID,
                'pic' => bp_core_fetch_avatar( array( 'item_id' => $user->ID,'type'=>'thumb')),
                'name' => bp_core_get_user_displayname($user->ID),
                );
          }
          echo json_encode($instructors);
      }
      die();
    }
    function get_admins(){
      $user_query = new WP_User_Query( array( 'role' => 'administrator' ) );
      $admins =array();
      if ( isset($user_query) && !empty( $user_query->results ) ) {
          foreach ( $user_query->results as $user ) {
              $admins[]=array(
                'id' => $user->ID,
                'pic' => bp_core_fetch_avatar( array( 'item_id' => $user->ID,'type'=>'thumb')),
                'name' => bp_core_get_user_displayname($user->ID),
                );
          }
          echo json_encode($admins);
      }
      die();
    }
    function get_course_students(){
      global $wpdb;
      $user_id=get_current_user_id();
      $query = apply_filters('wplms_dashboard_courses_instructors',$wpdb->prepare("
              SELECT posts.ID as course_id
                FROM {$wpdb->posts} AS posts
                WHERE   posts.post_type   = 'course'
                AND   posts.post_author   = %d
            ",$user_id));

        $instructor_courses=$wpdb->get_results($query,ARRAY_A);
        $course_ids=array();
        if(isset($instructor_courses) && count($instructor_courses)){
          foreach($instructor_courses as $key => $value){
              $course_ids[]=$value['course_id'];
            }
        }
      $course_ids_string = implode(',',$course_ids);

      $course_students = $wpdb->get_results("
        SELECT user_id
          FROM {$wpdb->usermeta} as rel
          WHERE  rel.meta_key  IN ($course_ids_string)
          AND   rel.meta_value >= 0
      ",ARRAY_A);

      $unique=array();
      if ( isset($course_students) && is_array( $course_students) ) {
          foreach ( $course_students as $user ) {
            if(!in_array($user['user_id'],$unique)){
              $mycourse_students[]=array(
                'id' => $user['user_id'],
                'pic' => bp_core_fetch_avatar( array( 'item_id' => $user['user_id'],'type'=>'thumb')),
                'name' => bp_core_get_user_displayname($user['user_id']),
                );
              $unique[]=$user['user_id'];
            }
          }
          echo json_encode($mycourse_students);
      }
      die();
    }
    
    function dash_contact_message(){
      if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'vibe_security')){
             _e('Security error','wplms');
             die();
      }

      $members = json_decode(stripslashes($_POST['to']));
      $subject=$_POST['subject'];
      $message = $_POST['message'];

      if ( !$members || !$subject || !$message){
           echo _e('Please enter to/subject/message','wplms');
             die();
      }
      $sender_id = get_current_user_id();
      $sent=0;
      if(bp_is_active('messages'))
        foreach($members as $member){
        if( messages_new_message( array('sender_id' => $sender_id, 'subject' => $subject, 'content' => $message,   'recipients' => $member ) ) ){
        $sent++;
       }}
       echo sprintf(__('Message sent to %s members','wplms'),$sent);
       die();
    }
} 

?>