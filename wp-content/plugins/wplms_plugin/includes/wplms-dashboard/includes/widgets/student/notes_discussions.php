<?php

add_action( 'widgets_init', 'wplms_notes_discussion_widget' );

function wplms_notes_discussion_widget() {
    register_widget('wplms_notes_discussion');
}

class wplms_notes_discussion extends WP_Widget {

    /** constructor -- name this the same as the class above */
    function __construct() {
    $widget_ops = array( 'classname' => 'wplms_notes_discussion', 'description' => __('Notes & Discussion Widget for Dashboard', 'wplms') );
    $control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'wplms_notes_discussion' );
    parent::__construct( 'wplms_notes_discussion', __(' DASHBOARD : Notes & Discussion', 'wplms'), $widget_ops, $control_ops );

        add_action('wp_enqueue_scripts',array($this,'enqueue_script'));
        
    
        add_filter('vibebp_member_dashboard_widgets',array($this,'add_custom_script'));
    }
    
    function add_custom_script($args){
      $args[]='wplms_notes_discussion';
      return $args;
    }
        
    
    function enqueue_script(){
        if(bp_current_component() == 'dashboard' || apply_filters('vibebp_enqueue_profile_script',false)){
            wp_enqueue_script('wplms_dashboard_notes_discussions',WPLMS_PLUGIN_URL.'/assets/js/notes.js',array('wp-element'),WPLMS_PLUGIN_VERSION,true);
            wp_enqueue_style('wplms_dashboard_css',WPLMS_PLUGIN_URL.'/assets/css/dashboard.css',array(),WPLMS_PLUGIN_VERSION);
            wp_localize_script('wplms_dashboard_notes_discussions','notes_discussions',apply_filters('wplms_dashboard_notes_discussions',array(
              'settings'      => array(),
              'api'           => rest_url(BP_COURSE_API_NAMESPACE . '/dashboard/widget'),
              'user_id'       => get_current_user_id(),
              'translations'  => array(
                                    'course'      =>__('Course','wplms'),
                                    'quiz'        =>__('Quiz','wplms'),
                                    'assignments' =>__('Assignment','wplms'),
                                    'no_data'=>__('Not Data','wplms'),
                                    'my_notes'=>__('My Notes','wplms'),
                                    'my_discussions'=>__('My Discussions','wplms'),
                                    'reply' => _x('Reply','','wplms'),
                                    'cancel' => _x('Cancel','','wplms'),
                                    'edit' => _x('Edit','','wplms'),
                                    'enter_some_content' => _x('Please enter comment content','','wplms'),
                                    'request_sent' => _x('Request Sent','','wplms'),
                                    'already_requested' => _x('Already Requested','','wplms'),
                                    'load_more' => _x('Load More','','wplms')
                                  )
            )));
        }
    }

    /** @see WP_Widget::widget -- do not rename this */
    function widget( $args, $instance ) {
        extract( $args );

        //Our variables from the widget settings.
        $title = apply_filters('widget_title', $instance['title'] );
        $width =  $instance['width'];
        $number = $instance['number'];
        echo '<div class="'.$width.'">
                <div class="dash-widget">'.$before_widget;

        // Display the widget title 
        if ( $title )
            echo $before_title . $title . $after_title;
        
        $unit_comments = vibe_get_option('unit_comments');
        if(isset($unit_comments) && is_numeric($unit_comments)){
            if(function_exists('icl_object_id')){
                $unit_comments = icl_object_id($unit_comments,'page',true);
            }
            $link = get_permalink($unit_comments);
        }else
            $link = '#';

        echo '<div class="wplms_dashboard_notes_discussions"></div>';
        
        echo $after_widget.'
        </div>
        </div>';
                
    }
 
    /** @see WP_Widget::update -- do not rename this */
    function update($new_instance, $old_instance) {   
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['number'] = $new_instance['number'];
        $instance['width'] = $new_instance['width'];
        return $instance;
    }
 
    /** @see WP_Widget::form -- do not rename this */
    function form($instance) {  
        $defaults = array( 
                        'title'  => __('Notes& Discussion','wplms'),
                        'content' => '',
                        'width' => 'col-md-6 col-sm-12'
                    );
          $instance = wp_parse_args( (array) $instance, $defaults );
        $title  = esc_attr($instance['title']);
        $number = esc_attr($instance['number']);
        $width = esc_attr($instance['width']);
        ?>
        <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','wplms'); ?></label> 
          <input class="regular_text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
         <p>
          <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of Notes/Dicussions','wplms'); ?></label> 
          <input class="regular_text" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" />
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
} 

?>