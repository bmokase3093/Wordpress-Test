<?php

add_action( 'widgets_init', 'wplms_instructor_earning_stats_widget' );

function wplms_instructor_earning_stats_widget() {
    register_widget('wplms_instructor_earning_stats');
}

class wplms_instructor_earning_stats extends WP_Widget {


    /** constructor -- name this the same as the class above */
    function __construct() {
      $widget_ops = array( 'classname' => 'wplms_instructor_earning_stats', 'description' => __('WooCommerce earnings Stats  for instructors', 'vibe-earnings') );
      $control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'wplms_instructor_earning_stats' );
      parent::__construct( 'wplms_instructor_earning_stats', __(' DASHBOARD : Instructor earnings Stats', 'vibe-earnings'), $widget_ops, $control_ops );

      add_action('wp_enqueue_scripts',array($this,'enqueue_script'));
        
      add_filter('vibebp_member_dashboard_widgets',array($this,'add_custom_script'));
    }
    
    function add_custom_script($args){
      $args[]='wplms_instructor_earning_stats';
      return $args;
    }
    
    function enqueue_script(){
        if(is_active_widget(false, false, 'wplms_instructor_earning_stats', true) || apply_filters('vibebp_enqueue_profile_script',false)){

            wp_enqueue_script('wplms_dashboard_instructor_earnings',VIBE_EARNINGS_PLUGIN_URL.'/assets/js/instructor_earnings.js',array('wp-element','wp-data'),VIBE_EARNINGS_PLUGIN_VERSION,true);
            wp_enqueue_style('wplms_dashboard_css',VIBE_EARNINGS_PLUGIN_URL.'/assets/css/earnings.css',array(),VIBE_EARNINGS_PLUGIN_VERSION);
            wp_localize_script('wplms_dashboard_instructor_earnings','instructor_earnings',array(
              'settings'=>array(),
              'api'=>rest_url(Vibe_BP_API_NAMESPACE.'/dashboard/widget'),
              'user_id'  => get_current_user_id(),
              'translations'=>array(
                'error_loading_data'=>_x('Error loading data','','vibe-earnings'),
                'earnings'=>__('Earnings','vibe-earnings'),
                'payouts'=>__('Payouts','vibe-earnings'),
                'no_data'=>__('No data found','vibe-earnings'),
                'calculated_commissions' => __('Commissions Calculated','vibe-earnings')
              )
            ));
        }
    }
    function widget( $args, $instance ) {
        extract( $args );
        if(!function_exists('vibe_sanitize'))
          return;
        global $wpdb;
        $user_id=get_current_user_id();
        //Our variables from the widget settings.
        $title = apply_filters('widget_title', $instance['title'] );
        $width =  $instance['width'];

        echo '<div class="'.$width.'">
                <div class="dash-widget">'.$before_widget;
        if ( $title )
            echo $before_title . $title . $after_title;

          if(function_exists('is_wplms_4_0') && is_wplms_4_0()){
            echo '<div class="wplms_instructor_earning_stats"></div>';
          }else if(bp_is_my_profile()){
            $user_id = get_current_user_id();
              $migrated_to_activity = apply_filters('wplms_commissions_migrate_to_activity',1);

              if(!$migrated_to_activity){

                //if not migrated to activitty not enabled multicurrency

                $commision_array =  vibe_sanitize(get_user_meta($user_id,'commission_data',false));
                $commission_recieved = vibe_sanitize(get_user_meta($user_id,'commission_recieved',false));
                $sales_pie = vibe_sanitize(get_user_meta($user_id,'sales_pie',false));
                $total_commission = get_user_meta($user_id,'total_commission',true);

                if(function_exists('get_woocommerce_currency_symbol')){
                  $symbol= get_woocommerce_currency_symbol();  
                }
                
                if(function_exists('wc_price')){
                  $value = wc_price($total_commission);
                }
                  //$value = $symbol.$total_commission;
                  if(empty($sales_pie))
                 {
                    echo '<a class="commission_reload"><i class="icon-reload"></i></a>';
                    echo '<div id="instructor_earnings" class="morris" style="padding:10px 20px 20px;text-align:center;"><div class="message"><p>'.__('No data found','wplms-dashboard').'</p></div></div>';
                   
                 } 
                     
                echo '<a class="commission_reload"><i class="icon-reload"></i></a>';
                echo '<div id="instructor_earnings" class="morris"></div>';
                echo '<div class="row">
                      <div class="col-md-6">
                      <label class="sales_labels">'.__('Course','wplms-dashboard').'<strong>'.__('Earnings','wplms-dashboard').' ('.$symbol.')</strong></label>
                      <div class="course_list">';
                      $sales_pie_array=array();
                    if(isset($sales_pie) && is_array($sales_pie) && count($sales_pie)){
                      echo '<ul class="course_sales_list">';
                      
                      foreach($sales_pie as $cid=>$sales){
                        if($cid == 'commission'){
                          echo '<li>'.__('Commissions Paid','wplms-dashboard').'<strong>'.$sales.'</strong></li>';
                        }
                        $ctitle=get_the_title($cid);
                        echo '<li>'.$ctitle.'<strong>'.$sales.'</strong></li>';
                        $sales_pie_array[]=array(
                          'label'=>$ctitle,
                          'value' => $sales
                          );
                      }
                      echo '</ul>';
                    }
                    // else{
                    //   echo '<div class="message"><p>'.__('No data found','wplms-dashboard').'</p></div>';
                    // }  
                echo '</div></div><div class="col-md-6">
                        <div id="commission_breakup" class="morris"></div>
                      </div></div>';
                echo '</div>';
                echo $after_widget.'
                </div>';
                if(isset($commision_array) && is_array($commision_array )){
                    foreach($commision_array as $key=>$commission){ 
                        if(isset($commission_recieved[$key])){ 
                          $commision_array[$key]['commission'] = $commision_array[$key]['commission']+$commission_recieved[$key]['commission'];
                        }else{
                          $commision_array[$key]['commission'] = 0;
                        }
                    }
                }
                if(isset($commission_recieved) && is_array($commission_recieved )){
                    foreach($commission_recieved as $key=>$commission2){ 
                        if(!isset($commision_array[$key])){ 
                          $commision_array[$key]['sales'] =  0;
                          $commision_array[$key]['date'] =  $commission_recieved[$key]['date'];
                          $commision_array[$key]['commission'] = $commission_recieved[$key]['commission'];
                        }
                    }
                }
                echo '<script>
                        var instructor_commission_data=[';$first=0;
                
                if(isset($commision_array) && is_array($commision_array)) {       

                foreach($commision_array as $data){
                  if($first)
                    echo ',';
                  $first=1;
                  echo str_replace('"','\'',json_encode($data,JSON_NUMERIC_CHECK));
                }}
                echo  '];
                var commission_breakup =[';$first=0;
                if(isset($sales_pie_array) && is_Array($sales_pie_array))
                foreach($sales_pie_array as $data){
                  if($first)
                    echo ',';
                  $first=1;
                  echo str_replace('"','\'',json_encode($data,JSON_NUMERIC_CHECK));
                }
                echo  '];
                </script>';
              }else{

                //if  migrated to activitty and enabled multicurrency

                $currencies_data = $this->get_currencies();
                
                if(!empty($currencies_data) && count($currencies_data) > 1){
                  echo '<div id="vibe-tabs-student-activity" class="tabs tabbable">
                  <ul class="nav nav-tabs clearfix">';
                  foreach ($currencies_data as $key => $currency) {
                    echo '<li id="'.$currency['currency'].'" class=""><a href="#tab-'.$currency['currency'].'" data-toggle="tab" class="currency_tabs">'.$currency['currency'].'</a></li>';
                  }
                  echo '</ul>
                  </div>';
                }

                
                if(!empty($currencies_data)){
                  echo '<div class="tab-content">';
                  $commision_array_cur =  vibe_sanitize(get_user_meta($user_id,'commission_data_cur',false));
                  echo '<script>window.instructor_commission_data = {};window.commission_breakup = {};</script>';
                  $commission_recieved_cur = vibe_sanitize(get_user_meta($user_id,'commission_recieved_cur',false));
                  $sales_pie_cur = vibe_sanitize(get_user_meta($user_id,'sales_pie_cur',false));
                  $total_commission_cur = get_user_meta($user_id,'total_commission_cur',true);
                  foreach ($currencies_data as $key => $currency) {
                    
                    echo '<div id="tab-'.$currency['currency'].'" class="tab-pane" '.((count($currencies_data) < 2 )?'style="display:block"':"").'>';
                        $commision_array = array();
                        if(!empty($commision_array_cur) && isset($commision_array_cur[$currency['currency']])){
                          $commision_array =  $commision_array_cur[$currency['currency']];
                        }
                        $commission_recieved = array();
                        if(!empty($commission_recieved_cur) && isset($commission_recieved_cur[$currency['currency']])){
                          $commission_recieved = $commission_recieved_cur[$currency['currency']];
                        }
                        $sales_pie =array();
                        if(!empty($sales_pie_cur) && isset($sales_pie_cur[$currency['currency']])){
                          $sales_pie = $sales_pie_cur[$currency['currency']];
                        }
                        $total_commission =array();
                        if(!empty($total_commission_cur) && isset($total_commission_cur[$currency['currency']])){
                          $total_commission = $total_commission_cur[$currency['currency']];
                        }
                        

                        if(function_exists('get_woocommerce_currency_symbol')){

                            $symbol= get_woocommerce_currency_symbol($currency['currency']); 
                            if(empty($symbol)){
                                $symbol = _x(strtoupper($currency['currency']),'currency variable label','vibe-earnings');
                            } 
                        }
                        
                        if(function_exists('wc_price')){
                          $value = wc_price($total_commission);
                        }
                          //$value = $symbol.$total_commission;
                          if(empty($sales_pie))
                         {
                            echo '<a class="commission_reload"><i class="icon-reload"></i></a>';
                            echo '<div id="instructor_earnings_'.$currency['currency'].'" class="morris" style="padding:10px 20px 20px;text-align:center;"><div class="message"><p>'.__('No data found','wplms-dashboard').'</p></div></div>';
                           
                         } 
                             
                        echo '<a class="commission_reload"><i class="icon-reload"></i></a>';
                        echo '<div id="instructor_earnings_'.$currency['currency'].'" class="morris"></div>';
                        echo '<div class="row">
                              <div class="col-md-6">
                              <label class="sales_labels">'.__('Course','wplms-dashboard').'<strong>'.__('Earnings','wplms-dashboard').' ('.$symbol.')</strong></label>
                              <div class="course_list">';
                              $sales_pie_array=array();
                            if(isset($sales_pie) && is_array($sales_pie) && count($sales_pie)){
                              echo '<ul class="course_sales_list">';
                              
                              foreach($sales_pie as $cid=>$sales){
                                if($cid == 'commission'){
                                  echo '<li>'.__('Commissions Paid','wplms-dashboard').'<strong>'.$sales.'</strong></li>';
                                }
                                $ctitle=get_the_title($cid);
                                echo '<li>'.$ctitle.'<strong>'.$sales.'</strong></li>';
                                $sales_pie_array[]=array(
                                  'label'=>$ctitle,
                                  'value' => $sales
                                  );
                              }
                              echo '</ul>';
                            }
                            // else{
                            //   echo '<div class="message"><p>'.__('No data found','wplms-dashboard').'</p></div>';
                            // }  
                        echo '</div></div><div class="col-md-6">
                                <div id="commission_breakup_'.$currency['currency'].'" class="morris"></div>
                              </div></div>';

                        if(isset($commision_array) && is_array($commision_array )){
                            foreach($commision_array as $key=>$commission){ 
                                if(isset($commission_recieved[$key])){ 
                                  $commision_array[$key]['commission'] = $commission_recieved[$key]['commission'];
                                }else{
                                  $commision_array[$key]['commission'] = 0;
                                }
                            }
                        }

                        if(isset($commission_recieved) && is_array($commission_recieved )){
                            foreach($commission_recieved as $key=>$commission2){ 
                                if(!isset($commision_array[$key])){ 
                                  $commision_array[$key]['sales'] =  0;
                                  $commision_array[$key]['date'] =  $commission_recieved[$key]['date'];
                                  $commision_array[$key]['commission'] = $commission_recieved[$key]['commission'];
                                }
                            }
                        }

                        echo '<script>
                        
                        window.instructor_commission_data.'.$currency['currency'].'=[';$first=0;
                        
                        if(isset($commision_array) && is_array($commision_array)) {       

                          foreach($commision_array as $data){
                            if($first)
                              echo ',';
                            $first=1;
                            echo str_replace('"','\'',json_encode($data,JSON_NUMERIC_CHECK));
                          }
                        }
                        echo  '];
                        window.commission_breakup.'.$currency['currency'].' =[';$first=0;
                        if(isset($sales_pie_array) && is_Array($sales_pie_array))
                        foreach($sales_pie_array as $data){
                          if($first)
                            echo ',';
                          $first=1;
                          echo str_replace('"','\'',json_encode($data,JSON_NUMERIC_CHECK));
                        }
                        echo  '];
                        </script>';
                    echo '</div>';
                  }
                echo '</div>';
                }
              }
          }else{
            echo '<div class="wplms_instructor_earning_stats"></div>';
          }

          
        echo '</div>';
        echo $after_widget.'
        </div>';
        return;

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
                        'title'  => __('Instructor Stats','vibe-earnings'),
                        'width' => 'col-md-6 col-sm-12'
                    );
  		  $instance = wp_parse_args( (array) $instance, $defaults );
        $title  = esc_attr($instance['title']);
        $width = esc_attr($instance['width']);
        ?>
        <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','vibe-earnings'); ?></label> 
          <input class="regular_text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <p>
          <label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Select Width','vibe-earnings'); ?></label> 
          <select id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>">
          	<option value="col-md-3 col-sm-6" <?php selected('col-md-3 col-sm-6',$width); ?>><?php _e('One Fourth','vibe-earnings'); ?></option>
          	<option value="col-md-4 col-sm-6" <?php selected('col-md-4 col-sm-6',$width); ?>><?php _e('One Third','vibe-earnings'); ?></option>
          	<option value="col-md-6 col-sm-12" <?php selected('col-md-6 col-sm-12',$width); ?>><?php _e('One Half','vibe-earnings'); ?></option>
            <option value="col-md-8 col-sm-12" <?php selected('col-md-8 col-sm-12',$width); ?>><?php _e('Two Third','vibe-earnings'); ?></option>
             <option value="col-md-8 col-sm-12" <?php selected('col-md-9 col-sm-12',$width); ?>><?php _e('Three Fourth','vibe-earnings'); ?></option>
          	<option value="col-md-12" <?php selected('col-md-12',$width); ?>><?php _e('Full','vibe-earnings'); ?></option>
          </select>
        </p>
        <?php 
    }

    function get_currencies(){
        global $wpdb,$bp;
        $commissions = array();
        $results = $wpdb->get_results( "
                                        SELECT meta2.meta_value as currency
                                        FROM  {$bp->activity->table_name_meta} as meta2 
                                        WHERE  meta2.meta_key   LIKE '_currency%'
                                        GROUP BY meta2.meta_value
                                        ",ARRAY_A);
      return $results;
    }
}