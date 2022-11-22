<?php

add_action( 'widgets_init', 'wplms_dash_text_widget' );

function wplms_dash_text_widget() {
    register_widget('wplms_dash_text');
}

class wplms_dash_text extends WP_Widget {

    /** constructor -- name this the same as the class above */
    function __construct() {
    $widget_ops = array( 'classname' => 'wplms_dash_text', 'description' => __('Text Widget for Dashboard', 'wplms') );
    $control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'wplms_dash_text' );
    parent::__construct( 'wplms_dash_text', __(' DASHBOARD : Text Widget', 'wplms'), $widget_ops, $control_ops );

    add_action('wp_enqueue_scripts',array($this,'enqueue_script'));
        
    
    add_filter('vibebp_member_dashboard_widgets',array($this,'add_custom_script'));
  }
    
    function add_custom_script($args){
      $args[]='wplms_dash_text';
      return $args;
    }
        
    function enqueue_script(){
        if(bp_current_component() == 'dashboard' || apply_filters('vibebp_enqueue_profile_script',false)){
            wp_enqueue_script('wplms_dash_text',WPLMS_PLUGIN_URL.'/assets/js/text_widget.js',array('wp-element'),WPLMS_PLUGIN_VERSION,true);
            wp_enqueue_style('wplms_dashboard_css',WPLMS_PLUGIN_URL.'/assets/css/dashboard.css',array(),WPLMS_PLUGIN_VERSION);
            wp_localize_script('wplms_dash_text','text_widget',apply_filters('wplms_dash_text',array(
              'settings'      => array(),
              'translations'  => array(
                                    'not_found'=>__('Not Found','wplms')
                                )
            )));
        }
    }
    /** @see WP_Widget::widget -- do not rename this */
    function widget( $args, $instance ) {
        extract( $args );
        $title    = $instance['title'];
        $width    = $instance['width'];
        $content  = $instance['content'];
        // print_r($args);
       
        echo '<div class="dash-widget">'.$before_widget;
        echo '<div class="wplms_dash_text"></div>';
        echo '</div>';
        echo $after_widget.'
        </div>';
                
    }
 
    /** @see WP_Widget::update -- do not rename this */
    function update($new_instance, $old_instance) {   
	    $instance = $old_instance;
	    $instance['title'] = strip_tags($new_instance['title']);
	    $instance['content'] = $new_instance['content'];
	    $instance['width'] = $new_instance['width'];
	    return $instance;
    }
 
    /** @see WP_Widget::form -- do not rename this */
    function form($instance) {  
        $defaults = array( 
                        'title'  => __('Contact Instructors','wplms'),
                        'content' => '',
                        'width' => 'col-md-6 col-sm-12'
                    );
  		  $instance = wp_parse_args( (array) $instance, $defaults );
        $title  = esc_attr($instance['title']);
        $content = esc_attr($instance['content']);
        $width = esc_attr($instance['width']);
        ?>
        <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','wplms'); ?></label> 
          <input class="regular_text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <p>
          <label for="<?php echo $this->get_field_id('content'); ?>"><?php _e('Text Content','wplms'); ?>
          </label> 
          <textarea id="<?php echo $this->get_field_id( 'content' ); ?>" name="<?php echo $this->get_field_name( 'content' ); ?>"><?php echo $content; ?></textarea>
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