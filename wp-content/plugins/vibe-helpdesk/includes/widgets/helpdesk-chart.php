<?php

add_action( 'widgets_init', 'vibe_helpdesk_resolution_chart_widget' );

function vibe_helpdesk_resolution_chart_widget() {
    register_widget('vibe_helpdesk_resolution_chart');
}

class vibe_helpdesk_resolution_chart extends WP_Widget {

    /** constructor -- name this the same as the class above */
    function __construct() {
    $widget_ops = array( 'classname' => 'vibe_helpdesk_resolution_chart', 'description' => __('Helpdesk Resolution Chart Widget for Dashboard', 'vibe-helpdesk') );
    $control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'vibe_helpdesk_resolution_chart' );
    parent::__construct( 'vibe_helpdesk_resolution_chart', __(' DASHBOARD : Helpdesk Resolution Chart', 'vibe-helpdesk'), $widget_ops, $control_ops );
        add_filter('vibebp_member_dashboard_widgets',array($this,'add_custom_script'));
    }
    
    function add_custom_script($args){
      $args[]='vibe_helpdesk_resolution_chart';
      return $args;
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

        echo '<div class="wplms_dashboard_helpdesk_chart">Helpdesk Resolution Chart</div>';
        
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
            'title'  => __('Helpdesk Resolution Chart','vibe-helpdesk'),
            'content' => '',
            'width' => 'col-md-6 col-sm-12'
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
        $title  = esc_attr($instance['title']);
        $width = esc_attr($instance['width']);
        ?>
        <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','vibe-helpdesk'); ?></label> 
          <input class="regular_text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <p>
          <label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Select Width','vibe-helpdesk'); ?></label> 
          <select id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>">
            <option value="col-md-3 col-sm-6" <?php selected('col-md-3 col-sm-6',$width); ?>><?php _e('One Fourth','vibe-helpdesk'); ?></option>
            <option value="col-md-4 col-sm-6" <?php selected('col-md-4 col-sm-6',$width); ?>><?php _e('One Third','vibe-helpdesk'); ?></option>
            <option value="col-md-6 col-sm-12" <?php selected('col-md-6 col-sm-12',$width); ?>><?php _e('One Half','vibe-helpdesk'); ?></option>
            <option value="col-md-8 col-sm-12" <?php selected('col-md-8 col-sm-12',$width); ?>><?php _e('Two Third','vibe-helpdesk'); ?></option>
             <option value="col-md-8 col-sm-12" <?php selected('col-md-9 col-sm-12',$width); ?>><?php _e('Three Fourth','vibe-helpdesk'); ?></option>
            <option value="col-md-12" <?php selected('col-md-12',$width); ?>><?php _e('Full','vibe-helpdesk'); ?></option>
          </select>
        </p>
        <?php 
    }
} 

?>