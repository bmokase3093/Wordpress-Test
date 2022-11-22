<?php


add_action( 'widgets_init', 'wplms_dash_news' );

function wplms_dash_news() {
  //if ( post_type_exists( 'news' ) ){
    register_widget('wplms_dash_news');
  //}
}

class wplms_dash_news extends WP_Widget {
 
    /** constructor -- name this the same as the class above */
    function __construct() {
    $widget_ops = array( 'classname' => 'wplms_dash_news', 'description' => __('News for Members', 'wplms') );
    $control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'wplms_dash_news' );
    parent::__construct( 'wplms_dash_news', __(' DASHBOARD : Member News', 'wplms'), $widget_ops, $control_ops );

      add_action('wp_enqueue_scripts',array($this,'enqueue_script'));
        
    
      add_filter('vibebp_member_dashboard_widgets',array($this,'add_custom_script'));
    }
    
    function add_custom_script($args){
      $args[]='wplms_dash_news';
      return $args;
    }

    function enqueue_script(){
        if(bp_current_component() == 'dashboard' || apply_filters('vibebp_enqueue_profile_script',false)){
              wp_enqueue_style('wplms_dashboard_css',WPLMS_PLUGIN_URL.'/assets/css/dashboard.css',array(),WPLMS_PLUGIN_VERSION);
              wp_enqueue_script('flickity',WPLMS_PLUGIN_URL.'/assets/js/flickity.min.js',array('wp-element'),WPLMS_PLUGIN_VERSION,true);
              wp_enqueue_script('wplms_dashboard_news',WPLMS_PLUGIN_URL.'/assets/js/dashboard_news.js',array('wp-element','flickity'),WPLMS_PLUGIN_VERSION,true);
              
              wp_enqueue_style('flickity',WPLMS_PLUGIN_URL.'/assets/css/flickity.min.css',array(),WPLMS_PLUGIN_VERSION);
              
              
              wp_localize_script('wplms_dashboard_news','dashboard_news',apply_filters('wplms_dashboard_news',array(
                'settings'      => array(),
                'api'           => rest_url(BP_COURSE_API_NAMESPACE . '/dashboard/widget'),
                'user_id'       => get_current_user_id(),
                'translations'  => array(
                                      
                                      'not_found'=>__('Not Found','wplms'),
                                    )
              )));    
        }
    }   

    /** @see WP_Widget::widget -- do not rename this */
    function widget( $args, $instance ) {
      extract( $args );

      //Our variables from the widget settings.
      $title = apply_filters('widget_title', $instance['title'] );
      $num =  $instance['number'];
      $width =  $instance['width'];
      echo '<div class="'.$width.'"><div class="dash-widget '.(($title)?'':'notitle').'">'.$before_widget;
        if ( $title )
          echo $before_title . $title . $after_title;
  			echo '<div class="news_block">';

       
      echo '<div class="wplms_dash_news"></div>';

      
      

      echo '</div>'.$after_widget.'</div></div>';
              
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
                        'title'  => __('News','wplms'),
                        'number'  => 5,
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
          <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Maximum Number of New blocks in carousel','wplms'); ?></label> 
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
