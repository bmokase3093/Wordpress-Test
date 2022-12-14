<?php

add_action( 'widgets_init', 'wplms_dash_stats_widget' );

function wplms_dash_stats_widget() {
    register_widget('wplms_dash_stats');
}

class wplms_dash_stats extends WP_Widget {

    /** constructor -- name this the same as the class above */
  function __construct() {
    $widget_ops = array( 'classname' => 'wplms_dash_stats', 'description' => __('Simple stats scores for students', 'wplms') );
    $control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'wplms_dash_stats' );
    parent::__construct( 'wplms_dash_stats', __(' DASHBOARD : Simple Stats', 'wplms'), $widget_ops, $control_ops );

    add_action('wp_enqueue_scripts',array($this,'enqueue_script'));
    add_filter('vibebp_member_dashboard_widgets',array($this,'add_custom_script'));
    
  }
    
  function add_custom_script($x){
      $x[] = 'wplms_dash_stats';
      return $x;
    } 

    function enqueue_script(){
        if(bp_current_component() == 'dashboard' || apply_filters('vibebp_enqueue_profile_script',false)){
            wp_enqueue_script('wplms_dashboard_simple_stats',WPLMS_PLUGIN_URL.'/assets/js/student_simple_stats.js',array('wp-element','wp-data'),WPLMS_PLUGIN_VERSION);
            wp_enqueue_style('wplms_dashboard_css',WPLMS_PLUGIN_URL.'/assets/css/dashboard.css',array(),WPLMS_PLUGIN_VERSION);
            wp_localize_script('wplms_dashboard_simple_stats','student_simple_stats',array(
              'settings'=>array(),
              'api'=>rest_url(BP_COURSE_API_NAMESPACE.'/dashboard/widget'),
              
              'translations'=>array(
              )
            ));
        }
    }
    function widget( $args, $instance ) {
    extract( $args );
    $user_id= apply_filters('wplms_dashboard_student_id',get_current_user_id());  
    //Our variables from the widget settings.
    $title = apply_filters('widget_title', $instance['title'] );
    $width =  $instance['width'];
    $stats =  $instance['stats'];

    


    echo '<div class="'.$width.'">';
    
    if(is_wplms_4_0()){
        echo '<div class="dash-widget '.$stats.' student_simple_stats" data-type="'.$stats.'">'.$before_widget;
      echo $after_widget.'</div>';
    }else if(bp_is_my_profile()){
        
        echo '<div class="dash-widget '.$stats.'">'.$before_widget;

           global $wpdb; 
        if(isset($stats)){

            switch($stats){
                case 'courses':
                    $marks=$wpdb->get_results(sprintf("
                        SELECT rel.post_id as id,rel.meta_value as val
                        FROM {$wpdb->posts} AS posts
                        LEFT JOIN {$wpdb->postmeta} AS rel ON posts.ID = rel.post_id
                        WHERE   posts.post_type   = 'course'
                        AND   posts.post_status   = 'publish'
                        AND   rel.meta_key   = %d
                        AND   rel.meta_value > 2
                    ",$user_id));
                    if(is_array($marks)){
                        foreach($marks as $k=>$mark){
                            $user_marks[]=$mark->val;
                        }
                    }else{
                        $user_marks=array();
                    }
                    
                    if ( $title )
                        $label = $title;
                    else
                        $label = __('Courses Completed','wplms-dashboard');

                    $value = count($marks);
                    if(is_array($user_marks)){
                        foreach($user_marks as $i=>$mark){
                            if($i<11){
                                if(!$i)
                                    $marks_string = $mark;
                                else
                                $marks_string .= ','.$mark;
                            }
                        }
                    }
                break;
                case 'course_ratio':
                    $course_completed=$wpdb->get_results(sprintf("
                        SELECT rel.post_id as id,rel.meta_value as val
                        FROM {$wpdb->posts} AS posts
                        LEFT JOIN {$wpdb->postmeta} AS rel ON posts.ID = rel.post_id
                        WHERE   posts.post_type   = 'course'
                        AND   posts.post_status   = 'publish'
                        AND   rel.meta_key   = %d
                        AND   rel.meta_value > 2
                    ",$user_id));

                    $course_subscribed= $wpdb->get_results($wpdb->prepare("
                      SELECT ID as courseID,post_title
                      FROM {$wpdb->posts} AS posts
                      LEFT JOIN {$wpdb->postmeta} AS rel ON posts.ID = rel.post_id
                      WHERE   posts.post_type   = 'course'
                      AND   posts.post_status   = 'publish'
                      AND   rel.meta_key   = %d
                      ",$user_id));
      
                    if ( $title )
                        $label = $title;
                    else
                        $label = __('Completed Courses/ Total Number of Courses','wplms-dashboard');
                    
                    $value1 = count($course_completed);
                    $value2 = count($course_subscribed);
                    $value = $value1."/".$value2;
                    if(($value)){
                        echo '<div class="dash-stats2">';
                            echo '<h3>'.$value.'<span>'.$label.'</span></h3>';
                            echo '<div class="sparkline'.$stats.'" style="max-height:20px;">Loading..</div>';
                        echo '</div>';
                       echo '</div>';
                            echo $after_widget.'
                    </div>';
                  echo "<script>jQuery(document).ready(function($){
                  var myvalues = [$marks_string];
                  $('.sparkline$stats').sparkline(myvalues, {
                  type: 'bar',
                  zeroAxis: false,
                  barColor: '#FFF'});
                  });
                  </script>";
                }         

              break;

              case 'assignments':
                    $marks=$wpdb->get_results(sprintf("
                        SELECT rel.post_id as id,rel.meta_value as val
                        FROM {$wpdb->posts} AS posts
                        LEFT JOIN {$wpdb->postmeta} AS rel ON posts.ID = rel.post_id
                        WHERE   posts.post_type   = 'wplms-assignment'
                        AND   posts.post_status   = 'publish'
                        AND   rel.meta_key   = %d
                        AND   rel.meta_value > 0
                    ",$user_id));
                    if(is_array($marks)){
                        foreach($marks as $k=>$mark){
                            $user_marks[]=$mark->val;
                        }
                    }else{
                        $user_marks=array();
                    }
              
                    if ( $title )
                        $label = $title;
                    else
                        $label = __('Assignments Completed','wplms-dashboard');

                    $value = count($marks);
                    if(is_array($user_marks)){
                        foreach($user_marks as $i=>$mark){
                            if($i<11){
                                if(!$i)
                                    $marks_string = $mark;
                                else
                                    $marks_string .= ','.$mark;
                            }
                        }
                    }
                break;
                case 'quizes':
                    $marks=$wpdb->get_results(sprintf("
                            SELECT rel.post_id as id,rel.meta_value as val
                            FROM {$wpdb->posts} AS posts
                            LEFT JOIN {$wpdb->postmeta} AS rel ON posts.ID = rel.post_id
                            WHERE   posts.post_type   = 'quiz'
                            AND   posts.post_status   = 'publish'
                            AND   rel.meta_key   = %d
                            AND   rel.meta_value > 0
                        ",$user_id));
                    if(is_array($marks)){
                        foreach($marks as $k=>$mark){
                            $user_marks[]=$mark->val;
                        }
                    }else{
                        $user_marks=array();
                    }
                    
                    if ( $title )
                        $label = $title;
                    else
                        $label = __('Quizzes Completed','wplms-dashboard');

                    if(is_array($marks)){
                        $value = count($marks);
                    }
                    
                    if(is_array($user_marks)){
                        foreach($user_marks as $i=>$mark){
                            if($i<11){
                                if(!$i)
                                    $marks_string = $mark;
                                else
                                    $marks_string .= ','.$mark;
                            }
                        }
                    }
                break;
                case 'units':
                    
                    $marks_old=$wpdb->get_var($wpdb->prepare("
                        SELECT count(meta_key) as count
                        FROM {$wpdb->posts} AS posts
                        LEFT JOIN {$wpdb->usermeta} AS rel ON posts.ID = rel.meta_key
                        WHERE   posts.post_type   = %s
                        AND   posts.post_status   = %s
                        AND   rel.user_id = %d
                        AND   rel.meta_value > 0",'unit','publish',$user_id));

                    $marks_new=$wpdb->get_var($wpdb->prepare("
                        SELECT count(meta_value) as count
                        FROM {$wpdb->usermeta}
                        WHERE user_id = %d
                        AND   meta_key LIKE %s",$user_id,'%complete_unit_%'));
                
                    $marks = $marks_new + $marks_old;
              
                    if ( $title )
                        $label = $title;
                    else
                        $label = __('Units Completed','wplms-dashboard');
                
                    $value = $marks;
                    break;
            }
            
            if(!is_numeric($value)){
              $value=0;
            }

            if($stats != 'course_ratio'){
                echo '<div class="dash-stats">';
                echo '<h3>'.$value.'<span>'.$label.'</span></h3>';
                echo '<div class="sparkline'.$stats.'" style="max-height:20px;">Loading..</div>';

                echo '</div>'.$after_widget.'</div>';
                      
                echo "<script>jQuery(document).ready(function($){
                  var myvalues = [$marks_string];
                  $('.sparkline$stats').sparkline(myvalues, {
                    type: 'bar',
                    zeroAxis: false,
                    barColor: '#FFF'});
                  });
                    </script>";
            }else{
                echo '<div class="dash-widget '.$stats.' student_simple_stats" data-type="'.$stats.'">'.$before_widget;
                echo $after_widget.'</div>';
            }
            echo '</div>';
            } 
        }else{
            echo '<div class="dash-widget '.$stats.' student_simple_stats" data-type="'.$stats.'">'.$before_widget;
            echo $after_widget.'</div>';
        }
    }
 
    /** @see WP_Widget::update -- do not rename this */
    function update($new_instance, $old_instance) {   
      $instance = $old_instance;
      $instance['title'] = strip_tags($new_instance['title']);
      $instance['stats'] = $new_instance['stats'];
      $instance['width'] = $new_instance['width'];
      return $instance;
    }
 
    /** @see WP_Widget::form -- do not rename this */
    function form($instance) {  
        $defaults = array( 
                        'title'  => __('Contact Instructors','wplms'),
                        'stats' => '',
                        'width' => 'col-md-6 col-sm-12'
                    );
        $instance = wp_parse_args( (array) $instance, $defaults );
        $title  = esc_attr($instance['title']);
        $stats = esc_attr($instance['stats']);
        $width = esc_attr($instance['width']);
        ?>
        <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','wplms'); ?></label> 
          <input class="regular_text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <p>
          <label for="<?php echo $this->get_field_id('stats'); ?>"><?php _e('Select Stats','wplms'); ?>
          </label> 
          <select id="<?php echo $this->get_field_id( 'stats' ); ?>" name="<?php echo $this->get_field_name( 'stats' ); ?>">
          <option value="courses" <?php selected('courses',$stats);?>><?php _e('Finished Courses','wplms'); ?></option>
          <option value="course_ratio" <?php selected('course_ratio',$stats);?>><?php _e('Finished Courses/Total Courses','wplms'); ?></option>
          <option value="quizes" <?php selected('quizes',$stats);?>><?php _e('Finished Quizes','wplms'); ?></option>
          <option value="assignments" <?php selected('assignments',$stats);?>><?php _e('Finished Assignments','wplms'); ?></option>
          <option value="units" <?php selected('units',$stats);?>><?php _e('Finished Units','wplms'); ?></option>
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