<?php

add_action( 'widgets_init', 'wplms_dash_instructing_modules_widget' );

function wplms_dash_instructing_modules_widget() {
    register_widget('wplms_dash_instructing_modules');
}

class wplms_dash_instructing_modules extends WP_Widget {

    /** constructor -- name this the same as the class above */
    function __construct() {
      $widget_ops = array( 'classname' => 'wplms_dash_instructing_modules', 'description' => __('Instructing Modules  Widget for Dashboard', 'wplms') );
      $control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'wplms_dash_instructing_modules' );
      parent::__construct( 'wplms_dash_instructing_modules', __(' DASHBOARD : Instructing Modules', 'wplms'), $widget_ops, $control_ops );

      add_action('wp_enqueue_scripts',array($this,'enqueue_script'));
      add_filter('vibebp_member_dashboard_widgets',array($this,'add_custom_script'));
    }
    
    function add_custom_script($args){
      $args[]='wplms_dash_instructing_modules';
      return $args;
    }
        
    function enqueue_script(){
        if(is_active_widget(false, false, 'wplms_dash_instructing_modules', true) || apply_filters('vibebp_enqueue_profile_script',false)){
          
            wp_enqueue_script('wplms_dash_instructing_modules',WPLMS_PLUGIN_URL.'/assets/js/instructing_module.js',array('wp-element'),WPLMS_PLUGIN_VERSION,true);
            wp_enqueue_style('wplms_dashboard_css',WPLMS_PLUGIN_URL.'/assets/css/dashboard.css',array(),WPLMS_PLUGIN_VERSION);
            wp_localize_script('wplms_dash_instructing_modules','instructing_module',apply_filters('wplms_dash_instructing_modules',array(
                'api'         => rest_url(BP_COURSE_API_NAMESPACE . '/dashboard/widget'),
                'user_id'     => get_current_user_id(),
                'translations'=>array(
                  'course'    =>__('Course','wplms'),
                  'quiz'      =>__('Quiz','wplms'),
                  'unit'      =>__('Units','wplms'),
                  'post'      =>__('Post','wplms'),
                  'page'      =>__('Page','wplms'),
                  'attachment'=>__('Attachment','wplms'),
                  'forum'     =>__('Forum','wplms'),
                  'topic'     =>__('Topic','wplms'),
                  'reply'     =>__('Reply','wplms'),
                  'question'  =>__('Question','wplms'),
                  'product'   =>__('Product','wplms'),
                  'popups'    =>__('Popups','wplms'),
                  'not_found' =>__('Not Found','wplms'),
                  'certificate'       =>__('Certificate','wplms'),
                  'testimonials'      =>__('Testimonials','wplms'),
                  'wplms_assignment'  =>__('Assignments','wplms'),
                  'elementor_library' =>__('Elementor Library','wplms'),
                )
            )));
        }
    }

    /** @see WP_Widget::widget -- do not rename this */
    function widget( $args, $instance ) {
    extract( $args );
      $defaults = array(
        'title'     => '',
        'post_types'=>array(),
        'number'    =>5,
        'orderby'   => 'post_date',
        'order'     => 'DESC'
        );

    $title      = apply_filters('widget_title', $instance['title'] );
    
    echo '<div class="'.$width.'">
            <div class="dash-widget">'.$before_widget;

            if(is_wplms_4_0()){
              echo '<div class="wplms_dash_instructing_modules"></div>';
            }else if(bp_is_my_profile()){
              
              $width      =  $instance['width'];
              $post_type  = $instance['post_types'];

              $all_post_types = array();

              foreach($post_type as $p_type){
                $all_post_types[$p_type] = $p_type;
                if ($p_type == 'wplms-assignment') {
                  $all_post_types['wplms_assignment'] = $p_type;
                } else $all_post_types[$p_type] = $p_type;
              }
              $instance['post_types'] = $all_post_types;
              extract($all_post_types);


              $user_id = get_current_user_id();

              echo '<div id="vibe-tabs-instructing-modules" class="tabs tabbable">
                    <ul class="nav nav-tabs clearfix">';

              if(!empty($post_types)){
                foreach($post_types as $post_type){
                  if(function_exists('post_type_exists') && post_type_exists($post_type)){
                      $posttype_obj=get_post_type_object($post_type);
                      echo '<li><a href="#tab-my'. $post_type.'" data-toggle="tab">'.$posttype_obj->labels->name.'</a></li>';
                  }
                }
                 echo '</ul><div class="tab-content">';
                  foreach($post_types as $post_type){
                    if(function_exists('post_type_exists') && post_type_exists($post_type)){
                      $posttype_obj=get_post_type_object($post_type);
                        echo '<div id="tab-my'.$post_type.'" class="tab-pane">';
                        $args = apply_filters('wplms_dashboard_instrcuting_modules_args',array(
                            'post_type' => $post_type,
                            'post_status' => 'publish',
                            'author' => $user_id,
                            'posts_per_page' => $number,
                            'orderby' =>$orderby,
                            'order' => $order
                             ));
           
                      $the_posts= new Wp_Query($args);
                      if($the_posts->have_posts()){
                        echo '<ul class="dashboard-my'.$post_type.'">';
                        while($the_posts->have_posts()){
                            $the_posts->the_post();
                            global $post;
                            echo '<li><a href="'.get_permalink($post->ID).'">'.$post->post_title.'</a></li>';
                        }
                        echo '</ul>';  
                      }else{
                        echo '<div class="message error">'.sprintf(__('No %s found','wplms-dashboard'),$posttype_obj->labels->name).'</div>';
                      }
                      wp_reset_postdata();
                      echo '</div>';
                    }
                  }
                   echo '</div>';
              }
             echo '</div></div>';
          }else{
            echo '<div class="wplms_dash_instructing_modules"></div>';
          }
              

    
       echo $after_widget.'</div></div>';
                
    }
 
    /** @see WP_Widget::update -- do not rename this */
    function update($new_instance, $old_instance) {   
      $instance = $old_instance;
      $instance['title'] = strip_tags($new_instance['title']);
      $instance['post_types'] = $new_instance['post_types'];
      $instance['number'] = $new_instance['number'];
      $instance['orderby'] = $new_instance['orderby'];
      $instance['order'] = $new_instance['order'];
      $instance['width'] = $new_instance['width'];
      
      return $instance;
    }
 
    /** @see WP_Widget::form -- do not rename this */
    function form($instance) { 
     $ptypes = get_post_types(array('public'   => true),'objects');
     $posts = array();
     foreach($ptypes as $k => $p){
      $posts[]=$p->name;
     } 
     
        $defaults = array( 
            'title'  => __('Instructing Modules','wplms'),
            'width' => 'col-md-6 col-sm-12',
            'post_types' => array('course')
        );
      $instance = wp_parse_args( (array) $instance, $defaults );
        $title  = esc_attr($instance['title']);
        
        $number= esc_attr($instance['number']);
        $orderby= esc_attr($instance['orderby']);
        $order= esc_attr($instance['order']);
        $width = esc_attr($instance['width']);
        $post_types = $instance['post_types'];
        if(empty($post_types)){
          $post_types = array();
        }

        ?>
        <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','wplms'); ?></label> 
          <input class="regular_text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <p>
          <label for="<?php echo $this->get_field_id('post_types'); ?>"><?php _e('Select Post Types:','wplms'); ?></label> 
          <select class="select" id="<?php echo $this->get_field_id('post_types'); ?>" name="<?php echo $this->get_field_name('post_types'); ?>[]" multiple>
          <?php
           
            
            foreach($ptypes as $ptype){

              echo '<option value="'.$ptype->name.'" '.(in_array($ptype->name,$post_types)?'selected':'').'>'.$ptype->label.'</option>';
            }
          ?>
          </select>
        </p>
        <p>
          <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of items','wplms'); ?></label> 
          <input class="regular_text" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" />
        </p>
        <p>
        <label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Order by','wplms'); ?></label> 
          <select id="<?php echo $this->get_field_id( 'orderby' ); ?>" name="<?php echo $this->get_field_name( 'orderby' ); ?>">
              <?php
              $orderby_array=apply_filters('wplms_dashboard_instructing_modules_settings',array(
                  'date' => __('Publish Date','wplms'),
                  'title' =>__('Alphabetical','wplms'),
                  'menu_order' => __('Menu order','wplms'),
                  
                ));
              foreach($orderby_array as $key => $value){
                echo '<option value="'.$key.'" '.selected($key,$orderby,false).'>'.$value.'</option>';
              }
              ?>
            </select>
            </p>
             <p>
             <label for="<?php echo $this->get_field_id('order'); ?>"><?php _e('Order','wplms'); ?></label> 
            <select id="<?php echo $this->get_field_id( 'order' ); ?>" name="<?php echo $this->get_field_name( 'order' ); ?>">
              <?php
                echo '<option value="DESC" '.selected("DESC",$order,false).'>DESC</option><option value="ASC" '.selected("ASC",$order,false).'>ASC</option>';
              ?>
            </select>
            
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