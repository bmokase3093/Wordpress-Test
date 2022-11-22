<?php
add_action( 'widgets_init', 'wplms_dash_tasks_widget' );

function wplms_dash_tasks_widget() {
    register_widget('wplms_dash_tasks');
}

class wplms_dash_tasks extends WP_Widget {

    /** constructor -- name this the same as the class above */
    function __construct() {
    $widget_ops = array( 'classname' => 'wplms_dash_tasks', 'description' => __('To Do list Widget', 'wplms') );
    $control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'wplms_dash_tasks' );
    parent::__construct( 'wplms_dash_tasks', __(' DASHBOARD : To Do Tasks', 'wplms'), $widget_ops, $control_ops );

    add_action('wp_ajax_save_tasks',array($this,'save_tasks'));

    add_action('wp_enqueue_scripts',array($this,'enqueue_script'));
    add_filter('vibebp_member_dashboard_widgets',array($this,'add_custom_script'));
    
  }
    function add_custom_script($x){
      $x[] = 'wplms_dash_tasks';
      return $x;
    } 
    function enqueue_script(){
        if(bp_current_component() == 'dashboard' || apply_filters('vibebp_enqueue_profile_script',false)){
            wp_enqueue_script(
                'wplms_todo_task',
                WPLMS_PLUGIN_URL.'/assets/js/todo_task.js', 
                array('wp-element'),
                WPLMS_PLUGIN_VERSION,
                true
            );
            wp_enqueue_style('wplms_dashboard_css',WPLMS_PLUGIN_URL.'/assets/css/dashboard.css',array(),WPLMS_PLUGIN_VERSION);
            wp_localize_script('wplms_todo_task', 'todo_task', apply_filters('wplms_todo_task', array(
                'settings' => array('today'=>date('m-d-Y')),
                'api' => rest_url(BP_COURSE_API_NAMESPACE . '/dashboard/widget'),
                'user_id' => get_current_user_id(),
                'translations' => array(
                    'submit' => __('Message', 'wplms'),
                    'save'   => __('SAVE', 'wplms'),
                    'add_new' =>_x('ADD NEW TASK','','wplms')
                )
            )));
        }
    }
 
    /** @see WP_Widget::widget -- do not rename this */
    function widget( $args, $instance ) {
    extract( $args );

    //Our variables from the widget settings.
    $title =  $instance['title'];
    $width =  $instance['width'];
    $date =  $instance['date'];
    $priority =  $instance['priority'];
    if(!empty($before_title)) $instance['before_title'] = $before_title; else $instance['before_title'] = '';
    if(!empty($after_title)) $instance['after_title'] = $after_title; else $instance['after_title'] = '';
    $instance['today'] = date("M-d", strtotime("today"));
    $instance['status'] = "normal";

    echo '<div class="'.$width.'">
            <div class="dash-widget">'.$before_widget;
        echo '<div class="wplms_todo_task"></div>';

                

        echo $after_widget.'
        </div>
        </div>';
                
    }
 
    /** @see WP_Widget::update -- do not rename this */
    function update($new_instance, $old_instance) {   
	    $instance = $old_instance;
	    $instance['title'] = strip_tags($new_instance['title']);
	    $instance['date'] = $new_instance['date'];
      $instance['priority'] = $new_instance['priority'];
	    $instance['width'] = $new_instance['width'];
	    return $instance;
    }
 
    /** @see WP_Widget::form -- do not rename this */
    function form($instance) {  
        $defaults = array( 
                        'title'  => __('To do List','wplms'),
                        'date' => 1,
                        'priority' => 1,
                        'width' => 'col-md-6 col-sm-12'
                    );
  		  $instance = wp_parse_args( (array) $instance, $defaults );
        $title  = esc_attr($instance['title']);
        $date = esc_attr($instance['date']);
        $priority = esc_attr($instance['priority']);
        $width = esc_attr($instance['width']);
        ?>
        <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','wplms'); ?></label> 
          <input class="regular_text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <p>
          <label for="<?php echo $this->get_field_id('date'); ?>"><?php _e('Show task date','wplms'); ?></label> 
          <input class="checkbox" id="<?php echo $this->get_field_id( 'date' ); ?>" name="<?php echo $this->get_field_name( 'date' ); ?>" type="checkbox" value="1"  <?php checked($date,1,true) ?>/>
        </p>
        <p>
          <label for="<?php echo $this->get_field_id('priority'); ?>"><?php _e('Enable task priority','wplms'); ?></label> 
          <input class="checkbox" id="<?php echo $this->get_field_id( 'priority' ); ?>" name="<?php echo $this->get_field_name( 'priority' ); ?>" type="checkbox" value="1"  <?php checked($priority,1,true) ?>/>
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

    // function save_tasks(){
    //   $user_id= apply_filters('wplms_dashboard_student_id',get_current_user_id());    
      
    //   if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'vibe_security')){
    //          _e('Security issue.','wplms');
    //          die();
    //     }

    //   $tasks = json_decode(stripslashes($_POST['tasks']));

    //   if(update_user_meta($user_id,'tasks',$tasks)){
    //     return 1;
    //   }else{
    //     return __('Unable to Save','wplms');
    //   }
    //   die();
    // }
} 

?>