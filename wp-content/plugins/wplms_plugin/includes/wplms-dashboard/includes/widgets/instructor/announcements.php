<?php
//DASH ANNOUNCEMENTS : meta_key wplms_accouncement
//
add_action( 'widgets_init', 'wplms_announcement_widget' );

function wplms_announcement_widget() {
    register_widget('wplms_announcement');
}

class wplms_announcement extends WP_Widget {

    /** constructor -- name this the same as the class above */
    function __construct() {
        $widget_ops = array( 'classname' => 'wplms_announcement', 'description' => __('announcement Widget for Dashboard', 'wplms') );
        $control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'wplms_announcement' );
        parent::__construct( 'wplms_announcement', __(' DASHBOARD : Instructor announcement Widget', 'wplms'), $widget_ops, $control_ops );
        add_action('wp_ajax_send_announcements',array($this,'send_announcements'));
        add_action('wp_ajax_remove_announcement',array($this,'remove_announcement'));
        add_action('bp_before_dashboard_body',array($this,'check_announcements'));


        add_action('wp_enqueue_scripts',array($this,'enqueue_script'));
        add_filter('vibebp_dashboard_widget_custom_script',array($this,'has_custom_script'),10,2);
        add_filter('vibebp_member_dashboard_widgets',array($this,'add_custom_script'));
    }
    
    function add_custom_script($args){
      $args[]='wplms_announcement';
      return $args;
    }
    function has_custom_script($return,$tag){
        if($tag == 'wplms_announcement'){
            return false;
        }
        return $return;
    }

    function enqueue_script(){

          if(apply_filters('vibebp_enqueue_profile_script',false)){


              wp_enqueue_script('wplms_dashboard_instructor_announcements',WPLMS_PLUGIN_URL.'/assets/js/instructor_announcements.js',array('wp-element'),WPLMS_PLUGIN_VERSION,true);

              wp_enqueue_style('wplms_dashboard_css',WPLMS_PLUGIN_URL.'/assets/css/dashboard.css',array(),WPLMS_PLUGIN_VERSION);

              wp_localize_script('wplms_dashboard_instructor_announcements','instructor_announcements',apply_filters('wplms_dashboard_instructor_announcements_window_args',array(
                  'settings'=>array(),
                  'api'=>rest_url(BP_COURSE_API_NAMESPACE.'/dashboard/widget'),
                  'user_id'  => get_current_user_id(),
                  'student_types'=>array(
                    'all_students'=>__('All Students','wplms'),
                    'students_pursuing'=>__('Students who are pursuing the course','wplms'),
                    'students_finished'=>__('Students who finished the course','wplms'),
                  ),
                  'translations'=>array(
                    'add_announcement'=>__('Add Announcement','wplms'),
                    'loading_options'=>__('Loading Options...','wplms'),
                    'are_you_sure'=>__('Are you sure! you wish to remove this announcement?','wplms'),
                    'no_announcements'=>__('No announcements.','wplms'),
                    'missing_data'=>__('Missing data.','wplms'),
                    'select_course'=>__('Select Course','wplms'),
                  )
              )));
          }
    }
    /** @see WP_Widget::widget -- do not rename this */
    function widget( $args, $instance ) {

    extract( $args );
    global $wpdb;
    //Our variables from the widget settings.
    $title = apply_filters('widget_title', $instance['title'] );
    $width =  $instance['width'];
    $user_id = get_current_user_id();

    echo '<div class="'.$width.'">
            <div class="dash-widget">'.$before_widget;

      
        if(is_wplms_4_0()){
            echo '<div class="wplms_dashboard_instructor_announcements"></div>';
        }else if(bp_is_my_profile()){
            
            global $wpdb;
            //Our variables from the widget settings.
            $title = apply_filters('widget_title', $instance['title'] );
            $width =  $instance['width'];
            $user_id = get_current_user_id();

      

            // Display the widget title 
            if ( $title )
                echo $before_title . $title . $after_title;
          
            $query = apply_filters('wplms_dashboard_courses_instructors',$wpdb->prepare("
                SELECT posts.ID as course_id
                  FROM {$wpdb->posts} AS posts
                  WHERE   posts.post_type   = 'course'
                  AND   posts.post_author   = %d
              ",$user_id));

          $instructor_courses=$wpdb->get_results($query,ARRAY_A);
          $announcements=array();
          if(isset($instructor_courses) && count($instructor_courses)){
            echo '<ul class="my_anouncements">';
            foreach($instructor_courses as $key => $value){
                $course_id=$value['course_id'];
                $course_array[$course_id]=get_the_title($course_id);
                
                $announcement=get_post_meta($course_id,'announcement',true);

                if(isset($announcement) && strlen($announcement)>5){
                  echo '<li data-course="'.$course_id.'"><a class="remove_announcement"><i class="icon-x"></i></a><strong>'.$course_array[$course_id].'</strong> '.$announcement.'<li>';  
                }
            }
            echo '</ul>';
          }
          echo '<textarea id="add_announcement" placeholder="'.__('Add announcement','wplms-dashboard').'"></textarea>';
          echo '<select class="chosen" id="course_list" data-placeholder="'.__('Send announcement to student in courses','wplms-dashboard').'"  multiple>';
          foreach($course_array as $key => $value){
            echo '<option value="'.$key.'">'.$value.'</option>';
          }
          echo '</select>';
          $student_types = apply_filters('wplms_annoucement_student_types',array(
            1 => __('All Students','wplms-dashboard'),
            2 =>__('Students who are pursuing the course','wplms-dashboard'),
            3 =>__('Students who finished the course','wplms-dashboard'),
            ));
          echo '<select id="student_type">';
          foreach($student_types as $key => $value){
              echo ' <option value="'.$key.'">'.$value.'</option>';
          }         
          echo '</select>';
          //echo '<strong class="right"><input type="checkbox" id="email_announcement" value="1"/>&nbsp;<label for="email_announcement">'.__('Send email','wplms-dashboard').'</label></strong><br class="clear"/>';
            echo '<a id="submit_announcement" class="button">'.__('Submit','wplms-dashboard').'</a>';
        }else{
            echo '<div class="wplms_dashboard_instructor_announcements"></div>';
        }

      

      echo $after_widget.'
        </div>
        </div>';
              
    }
 
    /** @see WP_Widget::update -- do not rename this */
    function update($new_instance, $old_instance) {   
	    $instance = $old_instance;
	    $instance['title'] = strip_tags($new_instance['title']);
	    $instance['width'] = $new_instance['width'];
	    return $instance;
    }
 
    /** @see WP_Widget::form -- do not rename this */
    function form($instance) {  
        $defaults = array( 
                        'title'  => __('announcement','wplms'),
                        'max' => 5,
                        'width' => 'col-md-6 col-sm-12'
                    );
  		  $instance = wp_parse_args( (array) $instance, $defaults );
        $title  = esc_attr($instance['title']);
        $width = esc_attr($instance['width']);
        ?>
        <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','wplms'); ?></label> 
          <input class="regular_text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
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

    function send_announcements(){
      if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'vibe_security') || !current_user_can('edit_posts')){
             _e('Security error','wplms');
             die();
      }
      global $wpdb;
      $announcement=$_POST['announcement'];
      $course_list=$_POST['course_list'];
      if(!isset($announcement) || strlen($announcement)< 5){
        _e('Please enter some text for announcement','wplms');
        die();
      }
      if(!isset($course_list) || !is_array($course_list)){
        _e('Course list not set','wplms');
        die();
      }

      if(isset($_POST['student_type']))
        $student_type=$_POST['student_type'];
      else
        $student_type=0;
      foreach($course_list as $list){
        if(is_numeric($list)){
          update_post_meta($list,'announcement',$announcement);
          if($student_type){
            update_post_meta($list,'announcement_student_type',$_POST['student_type']);
          }
          do_action('wplms_dashboard_course_announcement',$list,$student_type,1,$announcement);
        }
      }
       _e('announcement successfully delivered','wplms'); 
      die();
    }

    function check_announcements(){
        $user_id=get_current_user_id();
        $announcements = wplms_dashboards_get_user_annoncements($user_id);
        $k=count($announcements);
        if($k){
          echo '<div class="col-md-12"><div class="announcement_message message" data-count="'.$k.'">
          <span>'.__('CLICK TO EXPAND','wplms').'</span>
          <p>'.sprintf(__('You have %s announcements','wplms'),'<strong>'.$k.'</strong>').'</p>
          <ul class="announcements">';

          foreach($announcements as $announcement){
             echo '<li data-course="'.$announcement['id'].'"><strong>'.$announcement['title'].'</strong>'.$announcement['announcement'].'<span><i class="icon-x"></i></span></li>';
          }
          echo '</ul>
          </div></div>';
        }
    }

    function remove_announcement(){
        
        if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'vibe_security') || !current_user_can('edit_posts') || !is_numeric($_POST['id'])){
               _e('Security error','wplms');
               die();
        }

        $course_id = $_POST['id'];
        if(delete_post_meta($course_id,'announcement')){
           delete_post_meta($course_id,'announcement_student_type');
           echo 1;
        }else{
          _e('Unable to remove annoucement','wplms');
        }
        
        die();
    }
} 


function wplms_dashboards_get_user_annoncements($user_id){
  global $wpdb;
  
    $user_courses=$wpdb->get_results($wpdb->prepare("
          SELECT rel.post_id as id,posts.post_title as title,rel.meta_value as score
            FROM {$wpdb->posts} AS posts
            LEFT JOIN {$wpdb->postmeta} AS rel ON posts.ID = rel.post_id
            WHERE   posts.post_type   = 'course'
            AND   posts.post_status   = 'publish'
            AND   rel.meta_key   = %d
        ",$user_id),ARRAY_A);
    $announcements=array();
    if(is_Array($user_courses) && count($user_courses)){
      foreach($user_courses as $course){
        $announcement = get_post_meta($course['id'],'announcement',true);
        $announcement_type = get_post_meta($course['id'],'announcement_student_type',true);
        if(isset($announcement) && $announcement){
            if(isset($announcement_type)){
                switch($announcement_type){
                    case 1:
                        $announcements[]=array(
                        'id' => $course['id'],
                        'title' => $course['title'],
                        'announcement'=>$announcement);
                    break;
                    case 2:
                    if($course['score']<=2){
                        $announcements[]=array(
                            'id' => $course['id'],
                            'title' => $course['title'],
                            'announcement'=>$announcement);
                    }
                    break;
                    case 3:
                        if($course['score']>2){
                            $announcements[]=array(
                            'id' => $course['id'],
                            'title' => $course['title'],
                            'announcement'=>$announcement);
                        }
                        break;
                    }
                }else{
                    $announcements[]=array(
                    'id' => $course['id'],
                    'title' => $course['title'],
                    'announcement'=>$announcement);
                }
           
            }
        }
    }

    return $announcements;
}

?>