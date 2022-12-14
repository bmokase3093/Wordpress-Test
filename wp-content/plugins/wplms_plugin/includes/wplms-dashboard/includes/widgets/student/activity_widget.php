<?php

add_action('widgets_init', 'wplms_dash_activity');

function wplms_dash_activity()
{
    register_widget('wplms_dash_activity');
}

class wplms_dash_activity extends WP_Widget
{

    /** constructor -- name this the same as the class above */
    public function __construct()
    {
        $widget_ops = array('classname' => 'wplms_dash_activity', 'description' => __('Recent activity from Student', 'wplms'));
        $control_ops = array('width' => 300, 'height' => 350, 'id_base' => 'wplms_dash_activity');
        parent::__construct('wplms_dash_activity', __(' DASHBOARD : Recent Activity', 'wplms'), $widget_ops, $control_ops);

        add_action('wp_enqueue_scripts',array($this,'enqueue_script'));
        
    
        add_filter('vibebp_member_dashboard_widgets',array($this,'add_custom_script'));
    }
    
    function add_custom_script($args){
      $args[]='wplms_dash_activity';
      return $args;
    }

    function enqueue_script(){
        if(bp_current_component() == 'dashboard' || apply_filters('vibebp_enqueue_profile_script',false)){
            wp_enqueue_script('wplms_dash_activity', WPLMS_PLUGIN_URL.'/assets/js/activity_widget.js', array('wp-element', 'wp-data'), WPLMS_PLUGIN_VERSION,true);
            wp_enqueue_style('wplms_dashboard_css',WPLMS_PLUGIN_URL.'/assets/css/dashboard.css',array(),WPLMS_PLUGIN_VERSION);
            wp_localize_script('wplms_dash_activity', 'student_dash_activity', array(
                'settings' => array(),
                'api' => rest_url(BP_COURSE_API_NAMESPACE . '/dashboard/widget'),
                'translations' => array(
                    'no_messages' => __('No Unread messages found', 'wplms'),
                    'no_friends' => __('No Friends found', 'wplms'),
                    'no_activity' => __('No Activity found', 'wplms'),
                    'friends_online' => __('Friends Online', 'wplms'),
                    'recent_activity' => __('Recent Activity', 'wplms'),
                    'unread_messages' => __('Unread Messages', 'wplms'),
                    'ago' => __('Ago', 'wplms'),
                ),
            ));
        }
    }
    /** @see WP_Widget::widget -- do not rename this */
    public function widget($args, $instance)
    {
        extract($args);

        //Our variables from the widget settings.
        $title = apply_filters('widget_title', $instance['title']);
        $num = $instance['number'];
        $activity = $instance['activity'];
        $messages = $instance['messages'];
        $friends = $instance['friends'];
        $width = $instance['width'];
        $user_id = apply_filters('wplms_dashboard_student_id', get_current_user_id());

        
		echo $args['before_title'] . $title . $args['after_title'];
        echo '<div class="' . $width . '">';
        if(is_wplms_4_0()){
            echo '<div class="dash-widget wplms_dash_activity"></div>';
        }else if(bp_is_my_profile()){
                
            echo '<div id="vibe-tabs-student-activity" class="tabs tabbable">
                      <ul class="nav nav-tabs clearfix">';

            if(isset($messages) && $messages && function_exists('messages_get_unread_count')){
              echo '<li><a href="#tab-messages" data-toggle="tab"><i class="icon-bubble-talk-1"></i>'.messages_get_unread_count($user_id).'</a></li>';
            }   

            if(isset($friends) && $friends){
              $searchArgs  = array(
                            'type'     => 'online',
                            'page'     => 1,
                            'per_page' => $num,
                            'user_id'  => $user_id
                        );
              if ( bp_has_members($searchArgs)){
                  while ( bp_members() ) : bp_the_member();
                    $user_friends[]=array(
                      'avatar'=>bp_get_member_avatar(),
                      'name'  => bp_get_member_name(),
                      'last_active' => bp_get_member_last_active()
                      );
                endwhile;
              }
              if(!is_array($user_friends))
                $user_friends=array();

              echo '<li><a href="#tab-friends" data-toggle="tab"><i class="icon-myspace-alt"></i>'.count($user_friends).'</a></li>';
            }   

            if(isset($activity) && $activity){
              echo '<li><a href="#tab-activity" data-toggle="tab"><i class="icon-atom"></i>&nbsp;</a></li>';
            }   

            echo '</ul><div class="tab-content">';

            // Display the widget title 
            
                global $wpdb,$bp;

            if(isset($messages) && $messages && function_exists('bp_has_message_threads')){
              echo '<div id="tab-messages" class="tab-pane">
              <h4>'.__('Unread Messages','wplms-dashboard').'</h4>';
              $message_args= array(
                'user_id' => $user_id,
                'box' => 'inbox',
                'type' => 'unread',
                'max' => $num
                );
              if(bp_has_message_threads($message_args)){
                  echo '<ul class="dash-unread-messages">';
                  while ( bp_message_threads() ) : bp_message_thread();
                    echo '<li>'.bp_get_message_thread_avatar().'<a href="'.bp_get_message_thread_view_link().'">'.bp_get_message_thread_subject().'<span>'.bp_get_message_thread_from().'</span></a></li>';
                  endwhile;
                  echo '</ul>';
              }else{
                echo '<div class="message error">'.__('No messages found','wplms-dashboard').'</div>';
              }
              echo '</div>';
            }

            if(isset($friends) && $friends){
              echo '<div id="tab-friends" class="tab-pane">
              <h4>'.__('Friends Online','wplms-dashboard').'</h4>';
              if(count($user_friends)){
                echo '<ul class="dash-user-friends">';
                foreach($user_friends as $user_friend){
                  echo '<li>'.$user_friend['avatar'].' '.$user_friend['name'].'<span>'.$user_friend['last_active'].'</span></li>';
                }
                echo '</ul>';
              }else{
                echo '<div class="message error">'.__('No friends online','wplms-dashboard').'</div>';
              }
              echo '</div>';
            }
            if(isset($activity) && $activity){

                $activities=apply_filters('wplms_dashboard_activity', $wpdb->get_results($wpdb->prepare("
                    SELECT *
                    FROM {$bp->activity->table_name} AS activity
                    WHERE   activity.user_id IN (%d)
                    AND     (activity.action != '' OR activity.action IS NOT NULL)
                    ORDER BY activity.date_recorded DESC
                    LIMIT 0,$num
                ",$user_id)));

                
                        echo '<div id="tab-activity" class="tab-pane student_activity">
                  <h4>'.__('Recent Activity','wplms-dashboard').'</h4>';
                  if(isset($activities) && is_array($activities)){
                            echo '<ul class="dash-activities">';
                        foreach($activities as $activity){
                            if(isset($activity->action) && $activity->action != ''){
                            $time=tofriendlytime(time()-strtotime($activity->date_recorded));
                            echo '<li class="'.$activity->component.' '.$activity->type.'">
                                    <div class="dash-activity">
                                        <span class="dash-activity-time">'.$time.' '.__('AGO','wplms-dashboard').'</span>
                                            <strong>'.$activity->action.'</strong>
                                    </div>
                                  </li>';
                            }             
                        }   
                          echo '</ul>';
                    }else{
                echo '<div class="message error">'.__('No activity found','wplms-dashboard').'</div>';
                }

                echo'</div>';
              }
                

                echo '</div></div>';
        }else{
            echo '<div class="dash-widget wplms_dash_activity"></div>';
        }
		
		echo '</div>';
    }

    /** @see WP_Widget::update -- do not rename this */
    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['number'] = $new_instance['number'];
        $instance['activity'] = $new_instance['activity'];
        $instance['messages'] = $new_instance['messages'];
        $instance['friends'] = $new_instance['friends'];
        $instance['width'] = $new_instance['width'];
        return $instance;
    }

    /** @see WP_Widget::form -- do not rename this */
    public function form($instance)
    {
        $defaults = array(
            'title' => __('Student Activity', 'wplms'),
            'number' => 5,
            'friends' => 1,
            'width' => 'col-md-6 col-sm-12',
        );
        $instance = wp_parse_args((array) $instance, $defaults);
        $title = esc_attr($instance['title']);
        $number = esc_attr($instance['number']);
        $activity = false;
        if(!empty($instance['activity'])){
          $activity = esc_attr($instance['activity']);
        }
        $messages = false;
        if(!empty($instance['messages'])){
          $messages = esc_attr($instance['messages']);
        }
        $friends = esc_attr($instance['friends']);
        $width = esc_attr($instance['width']);
        ?>
        <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'wplms');?></label>
          <input class="regular_text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <p>
          <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of items', 'wplms');?></label>
          <input class="regular_text" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" />
        </p>
        <p>
          <label for="<?php echo $this->get_field_id('messages'); ?>"><?php _e('Show recent messages', 'wplms');?></label>
          <input class="checkbox" id="<?php echo $this->get_field_id('messages'); ?>" name="<?php echo $this->get_field_name('messages'); ?>" type="checkbox" value="1"  <?php checked($messages, 1, true)?>/>
        </p>
        <p>
          <label for="<?php echo $this->get_field_id('activity'); ?>"><?php _e('Show recent activity', 'wplms');?></label>
          <input class="checkbox" id="<?php echo $this->get_field_id('activity'); ?>" name="<?php echo $this->get_field_name('activity'); ?>" type="checkbox" value="1"  <?php checked($activity, 1, true)?>/>
        </p>
        <p>
          <label for="<?php echo $this->get_field_id('friends'); ?>"><?php _e('Show online Friends', 'wplms');?></label>
          <input class="checkbox" id="<?php echo $this->get_field_id('friends'); ?>" name="<?php echo $this->get_field_name('friends'); ?>" type="checkbox" value="1"  <?php checked($friends, 1, true)?>/>
        </p>
        <p>
          <label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Select Width', 'wplms');?></label>
          <select id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>">
          	<option value="col-md-3 col-sm-6" <?php selected('col-md-3 col-sm-6', $width);?>><?php _e('One Fourth', 'wplms');?></option>
          	<option value="col-md-4 col-sm-6" <?php selected('col-md-4 col-sm-6', $width);?>><?php _e('One Third', 'wplms');?></option>
          	<option value="col-md-6 col-sm-12" <?php selected('col-md-6 col-sm-12', $width);?>><?php _e('One Half', 'wplms');?></option>
            <option value="col-md-8 col-sm-12" <?php selected('col-md-8 col-sm-12', $width);?>><?php _e('Two Third', 'wplms');?></option>
             <option value="col-md-8 col-sm-12" <?php selected('col-md-9 col-sm-12', $width);?>><?php _e('Three Fourth', 'wplms');?></option>
          	<option value="col-md-12" <?php selected('col-md-12', $width);?>><?php _e('Full', 'wplms');?></option>
          </select>
        </p>
        <?php
	}
}

?>