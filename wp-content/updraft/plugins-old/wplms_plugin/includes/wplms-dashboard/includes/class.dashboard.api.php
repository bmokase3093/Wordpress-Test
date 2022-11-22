<?php
/**
 * DASHBOARD API
 *
 * @author      VibeThemes
 * @category    Admin
 * @package     WPLMS Dashboard
 * @version     4.0
 */

if (!defined('ABSPATH')) {
    exit();
}

class WPLMS_Dashboard_Api
{
    public static $instance;

    public static function init()
    {
        if (is_null(self::$instance)) {
            self::$instance = new WPLMS_Dashboard_Api();
        }

        return self::$instance;
    }

    private function __construct()
    {
        add_action('rest_api_init', array($this, 'register_rest_routes'));
    }

    function register_rest_routes()
    {
        register_rest_route(
            BP_COURSE_API_NAMESPACE,
            '/dashboard/widget/student_simple_stats',
            array(
                array(
                    'methods' => 'POST',
                    'callback' => array($this, 'get_student_simple_stats'),
                    'permission_callback' => array($this, 'get_permissions')
                )
            )
        );

        register_rest_route(
            BP_COURSE_API_NAMESPACE,
            '/dashboard/widget/instructor_simple_stats',
            array(
                array(
                    'methods' => 'POST',
                    'callback' => array($this, 'get_instructor_simple_stats'),
                    'permission_callback' => array($this, 'get_permissions')
                )
            )
        );

        register_rest_route(
            BP_COURSE_API_NAMESPACE,
            '/dashboard/widget/instructor_stats',
            array(
                array(
                    'methods' => 'POST',
                    'callback' => array($this, 'instructor_stats'),
                    'permission_callback' => array($this, 'get_permissions')
                )
            )
        );
        register_rest_route(
            BP_COURSE_API_NAMESPACE,
            '/dashboard/widget/recalculate_stats',
            array(
                array(
                    'methods' => 'POST',
                    'callback' => array($this, 'recalculate_stats'),
                    'permission_callback' => array($this, 'get_permissions')
                )
            )
        );
        register_rest_route(
            BP_COURSE_API_NAMESPACE,
            '/dashboard/widget/load_quiz_assignment',
            array(
                array(
                    'methods' => 'POST',
                    'callback' => array($this, 'load_quiz_assignment'),
                    'permission_callback' => array($this, 'get_permissions')
                )
            )
        );

        register_rest_route(
            BP_COURSE_API_NAMESPACE,
            '/dashboard/widget/generate_ranges',
            array(
                array(
                    'methods' => 'POST',
                    'callback' => array($this, 'generate_ranges'),
                    'permission_callback' => array($this, 'get_permissions')
                )
            )
        );
        
        register_rest_route(
            BP_COURSE_API_NAMESPACE,
            '/dashboard/widget/get_currencies',
            array(
                array(
                    'methods' => 'POST',
                    'callback' => array($this, 'get_currencies_data'),
                    'permission_callback' => array($this, 'get_permissions')
                )
            )
        );

        register_rest_route(
            BP_COURSE_API_NAMESPACE,
            '/dashboard/widget/instructor_commissions',
            array(
                array(
                    'methods' => 'POST',
                    'callback' => array($this, 'get_instructor_commissions'),
                    'permission_callback' => array($this, 'get_permissions')
                )
            )
        );

        register_rest_route(
            BP_COURSE_API_NAMESPACE,
            '/dashboard/widget/instructor_commissions/generate',
            array(
                array(
                    'methods' => 'POST',
                    'callback' => array($this, 'generate_commission_data'),
                    'permission_callback' => array($this, 'get_permissions')
                )
            )
        );

        register_rest_route(
            BP_COURSE_API_NAMESPACE,
            '/dashboard/widget/get_notes',
            array(
                array(
                    'methods' => 'POST',
                    'callback' => array($this, 'get_notes'),
                    'permission_callback' => array($this, 'get_permissions')
                )
            )
        );

        register_rest_route(
            BP_COURSE_API_NAMESPACE,
            '/dashboard/widget/instructor_courses',
            array(
                array(
                    'methods' => 'POST',
                    'callback' => array($this, 'get_instructor_courses'),
                    'permission_callback' => array($this, 'get_permissions')
                )
            )
        );
        register_rest_route(
            BP_COURSE_API_NAMESPACE,
            '/dashboard/widget/instructor_announcements',
            array(
                array(
                    'methods' => 'POST',
                    'callback' => array($this, 'get_instructor_announcement'),
                    'permission_callback' => array($this, 'get_permissions')
                )
            )
        );

        
        
        register_rest_route(
            BP_COURSE_API_NAMESPACE,
            '/dashboard/widget/instructor_announcements/submit',
            array(
                array(
                    'methods' => 'POST',
                    'callback' => array($this, 'new_instructor_announcement'),
                    'permission_callback' => array($this, 'get_permissions')
                )
            )
        );
        register_rest_route(
            BP_COURSE_API_NAMESPACE,
            '/dashboard/widget/instructor_announcements/remove',
            array(
                array(
                    'methods' => 'POST',
                    'callback' => array(
                        $this,
                        'remove_instructor_announcement'
                    ),
                    'permission_callback' => array($this, 'get_permissions')
                )
            )
        );
        register_rest_route(
            BP_COURSE_API_NAMESPACE,
            '/dashboard/widget/course_progress',
            array(
                array(
                    'methods' => 'POST',
                    'callback' => array($this, 'get_course_progress'),
                    'permission_callback' => array($this, 'get_permissions')
                )
            )
        );


        register_rest_route(
            BP_COURSE_API_NAMESPACE,
            '/dashboard/widget/mymodules',
            array(
                array(
                    'methods' => 'POST',
                    'callback' => array($this, 'get_modules'),
                    'permission_callback' => array($this, 'get_permissions')
                )
            )
        );


        register_rest_route(
            BP_COURSE_API_NAMESPACE,
            '/dashboard/widget/instructing_module',
            array(
                array(
                    'methods' => 'POST',
                    'callback' => array($this, 'get_instructing_module'),
                    'permission_callback' => array($this, 'get_permissions')
                )
            )
        );

        register_rest_route(
            BP_COURSE_API_NAMESPACE,
            '/dashboard/widget/contact_user',
            array(
                array(
                    'methods' => 'POST',
                    'callback' => array($this, 'contact_user'),
                    'permission_callback' => array($this, 'get_permissions')
                )
            )
        );

        register_rest_route(
            BP_COURSE_API_NAMESPACE,
            '/dashboard/widget/todo_task',
            array(
                array(
                    'methods' => 'POST',
                    'callback' => array($this, 'get_todo_task'),
                    'permission_callback' => array($this, 'get_permissions')
                )
            )
        );

        register_rest_route(
            BP_COURSE_API_NAMESPACE,
            '/dashboard/widget/student_stats',
            array(
                array(
                    'methods' => 'POST',
                    'callback' => array($this, 'get_student_stats'),
                    'permission_callback' => array($this, 'get_permissions')
                )
            )
        );
        register_rest_route(
            BP_COURSE_API_NAMESPACE,
            '/dashboard/widget/activity',
            array(
                array(
                    'methods' => 'POST',
                    'callback' => array($this, 'get_activity'),
                    'permission_callback' => array($this, 'get_permissions')
                )
            )
        );

        register_rest_route(
            BP_COURSE_API_NAMESPACE,
            '/dashboard/widget/get_news',
            array(
                array(
                    'methods' => 'POST',
                    'callback' => array($this, 'get_news'),
                    'permission_callback' => array($this, 'get_permissions')
                )
            )
        );

        register_rest_route(
            BP_COURSE_API_NAMESPACE,
            '/dashboard/widget/instructor_reply_comment',
            array(
                array(
                    'methods' => 'POST',
                    'callback' => array($this, 'instructor_reply_comment'),
                    'permission_callback' => array($this, 'get_permissions')
                )
            )
        );

        register_rest_route(
            BP_COURSE_API_NAMESPACE,
            '/dashboard/widget/contact_users/check',
            array(
                array(
                    'methods' => 'POST',
                    'callback' => array($this, 'get_contact_user_check'),
                    'permission_callback' => array($this, 'get_permissions')
                )
            )
        );
        register_rest_route(
            BP_COURSE_API_NAMESPACE,
            '/dashboard/widget/contact_users/users',
            array(
                array(
                    'methods' => 'POST',
                    'callback' => array($this, 'get_contact_user'),
                    'permission_callback' => array($this, 'get_permissions')
                )
            )
        );
        register_rest_route(
            BP_COURSE_API_NAMESPACE,
            '/dashboard/widget/contact_users/send_message',
            array(
                array(
                    'methods' => 'POST',
                    'callback' => array($this, 'dash_contact_message'),
                    'permission_callback' => array($this, 'get_permissions')
                )
            )
        );
        register_rest_route(
            BP_COURSE_API_NAMESPACE,
            '/dashboard/widget/instrutor_students',
            array(
                array(
                    'methods' => 'POST',
                    'callback' => array($this, 'get_instrutor_students'),
                    'permission_callback' => array($this, 'get_permissions')
                )
            )
        );
        register_rest_route(
            BP_COURSE_API_NAMESPACE,
            '/dashboard/widget/make_comment_public',
            array(
                array(
                    'methods' => 'POST',
                    'callback' => array($this, 'make_comment_public'),
                    'permission_callback' => array($this, 'get_permissions')
                )
            )
        );
        register_rest_route(
            BP_COURSE_API_NAMESPACE,
            '/dashboard/widget/make_comment_private',
            array(
                array(
                    'methods' => 'POST',
                    'callback' => array($this, 'make_comment_private'),
                    'permission_callback' => array($this, 'get_permissions')
                )
            )
        );

        register_rest_route(
            BP_COURSE_API_NAMESPACE,
            '/dashboard/widget/delete_comment',
            array(
                array(
                    'methods' => 'POST',
                    'callback' => array($this, 'delete_comment'),
                    'permission_callback' => array($this, 'get_permissions')
                )
            )
        );
        
        register_rest_route(
            BP_COURSE_API_NAMESPACE,
            '/dashboard/widget/add_edit_new_unit_comments',
            array(
                array(
                    'methods' => 'POST',
                    'callback' => array($this, 'add_edit_new_unit_comments'),
                    'permission_callback' => array($this, 'get_permissions')
                )
            )
        );
        
    }

    function get_permissions($request){
        $body = json_decode($request->get_body(),true);


        if(!empty($body['token'])){
            global $wpdb;

            $this->user = apply_filters('vibebp_api_get_user_from_token','',$body['token']);
            if(!empty($this->user)){
                if(!empty($body['user_id'])){
                    $this->user = apply_filters('wplms_verify_user_id',$this->user,$body['user_id']);
                  
                }
                return true;
            }
        }

        return false;
    }

    function add_edit_new_unit_comments($request){

        $body = json_decode($request->get_body(), true);
        $user_id = $this->user->id;
        $type = $body['type'];

        switch($type){

            case 'edit':
                    $comment_id= $body['comment_ID'];
                    $comment=$body['comment_content'];
                    $old_comment_user_id=get_comment($comment_id, ARRAY_A)['user_id'];
                    
                    if($user_id==$old_comment_user_id){
                        $commentarr = array();
                        $commentarr['comment_ID'] = $comment_id;
                        $commentarr['comment_content'] = $comment;

                        if(wp_update_comment($commentarr)){

                            $structured_comment=get_comment($comment_id, ARRAY_A);
                            $structured_comment['avatar'] = get_avatar_url($structured_comment['user_id']);

                            $structured_comment['comment_username'] = '<a href="'.bp_core_get_user_domain($structured_comment['user_id']).'" class="unit_comment_author"> '.bp_core_get_user_displayname( $structured_comment['user_id']) .'</a>';
                            $structured_comment['comment_meta'] = '<span ><span>'.__('UNIT','wplms').' : '.get_the_title($structured_comment['comment_post_ID']).'</span><br /><i class="icon-clock"></i>&nbsp;'.human_time_diff(strtotime($structured_comment['comment_date']),current_time('timestamp')).'</span>';

                            $data = array(
                                'status'=>true,
                                'comment_id'=> $comment_id,
                                'message'=>_x('Comment updated','API call','wplms'),
                                'comment_data'=>$structured_comment
                            );
                            return  new WP_REST_Response( $data, 200 );
                        }
                        else{
                        $message= _x('Comment already updated successfully','API call','wplms');
                        $data = array('status'=>false,'comment_id'=> $comment_id, 'message'=>$message);
                        return  new WP_REST_Response( $data, 200 );
                        }
                    }
                    else{
                        $data = array('status'=>false, 'message'=>__('Comment can only be edited by poster.','API','wplms'));
                        return  new WP_REST_Response( $data, 200 );
                    }
            break;   

            case 'reply': 
                        $unit_id= $body['unit'];
                        $parent_id=$body['comment_parent'];

                        $comment=$body['comment_content'];

                        $comment_data = array(
                            'comment_post_ID' => $unit_id,
                            'comment_content' => sanitize_textarea_field($comment),
                            'comment_type' => 'public',
                            'user_id' => $user_id,
                            'comment_parent'=>$parent_id,
                                    
                        );
                        $parent_user_id=get_comment($parent_id, ARRAY_A)['user_id'];


                        if( $new_comment_id=wp_insert_comment($comment_data) )
                        {   
                            $structured_comment=get_comment($new_comment_id, ARRAY_A);


                            $structured_comment['avatar'] = get_avatar_url($structured_comment['user_id']);

                            $structured_comment['comment_username'] = '<a href="'.bp_core_get_user_domain($structured_comment['user_id']).'" class="unit_comment_author"> '.bp_core_get_user_displayname( $structured_comment['user_id']) .'</a>';
                            $structured_comment['comment_meta'] = '<span ><span>'.__('UNIT','wplms').' : '.get_the_title($structured_comment['comment_post_ID']).'</span><br /><i class="icon-clock"></i>&nbsp;'.human_time_diff(strtotime($structured_comment['comment_date']),current_time('timestamp')).'</span>';

                            $data = array(
                                'status'=>true,
                                'comment_id'=>$new_comment_id, 
                                'message'=>_x('Replied on comment.','API message','wplms'),
                                'comment_data'=>$structured_comment);

                            // do_action('wplms_course_unit_comment',$unit_id,$user_id,$comment_id,$args);
                            //for updates in app purpose  start

                            return  new WP_REST_Response( $data, 200 );

                        }else{

                            $data = array(
                                'status'=>false, 
                                'comment_id'=>$new_comment_id, 
                                'message'=>_x('Reply failed.','API message','wplms'),
                                'comment_data'=>$comment_data);

                            return  new WP_REST_Response( $data, 200 );
                        }
            break;

            case 'new': 
                        $unit_id= $request['unit'];
                        $parent_id=0;
                        $comment=$body['$post->comment_content'];
                        $comment_data = array(
                            'comment_post_ID' => $unit_id,
                            'comment_content' => sanitize_textarea_field($comment),
                            'comment_type' => 'public',
                            'user_id' => $this->user->id,
                            'comment_parent'=>$parent_id,
                                    
                        );

                        if( $new_comment_id=wp_insert_comment($comment_data) )
                        {   
                            $structured_comment=get_comment($new_comment_id, ARRAY_A);
                            $structured_comment['avatar'] = get_avatar_url($structured_comment['user_id']);

                            $structured_comment['comment_username'] = '<a href="'.bp_core_get_user_domain($structured_comment['user_id']).'" class="unit_comment_author"> '.bp_core_get_user_displayname( $structured_comment['user_id']) .'</a>';
                            $structured_comment['comment_meta'] = '<span ><span>'.__('UNIT','wplms').' : '.get_the_title($structured_comment['comment_post_ID']).'</span><br /><i class="icon-clock"></i>&nbsp;'.human_time_diff(strtotime($structured_comment['comment_date']),current_time('timestamp')).'</span>';
                            $data = array(
                                'status'=>true,
                                'comment_id'=>$new_comment_id, 
                                'message'=>_x('Comment added.','API message','wplms'),
                                'comment_data'=>$structured_comment);
                            
                            do_action('wplms_course_unit_comment',$unit_id,$user_id,$new_comment_id,$args);  
                            
                            return  new WP_REST_Response( $data, 200 );
                        }
                        else
                        {
                            
                            $data = array(
                                'status'=>false, 
                                'comment_id'=>$new_comment_id, 
                                'message'=>_x('Failed Comment added.','API message','wplms'),
                                'comment_data'=>$comment_data);

                            return  new WP_REST_Response( $data, 200 );
                        }

            break;          
        }

            
    }

    function delete_comment($request){
        $body = json_decode($request->get_body(), true);
        $user_id = $this->user->id;
        $data = array('status'=>0);

        $id=intval($body['comment_id']);
       
     
        if(user_can($user_id,'edit_posts')){
          wp_delete_comment($id);
          global $wpdb;
          $wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->commentmeta} WHERE comment_id = %d",$id));
          $data['status'] = true;
        }else{
          $user_id = get_current_user_id();
          $comment = get_comment($id,ARRAY_A);
          if($comment['user_id'] == $user_id){
            wp_delete_comment($id);
            global $wpdb;
            $wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->commentmeta} WHERE comment_id = %d AND meta_key LIKE %s",$id,'%unit%'.$user_id.'%'));
            $data['status'] = true;
          }
        }
        return new WP_REST_Response($data, 200);
    }

    function make_comment_private($request){

        $body = json_decode($request->get_body(), true);
        $user_id = $this->user->id;
        $data = array('status'=>0);

        $id=intval($body['comment_id']);
       
     
        if(user_can($user_id,'edit_posts')){
          global $wpdb;

          $wpdb->query($wpdb->prepare("UPDATE {$wpdb->comments} SET comment_type=%s WHERE comment_ID=%d",'note',$id));
          $wpdb->query($wpdb->prepare("UPDATE {$wpdb->commentmeta} SET meta_key=replace(meta_key,%s,%s) WHERE comment_ID=%d",'_public','',$id));
          $data['status'] = true;
        }else{
          $comment = get_comment($id,ARRAY_A);
          if($comment['user_id'] == $user_id){
            global $wpdb;
            $wpdb->query($wpdb->prepare("UPDATE {$wpdb->comments} SET comment_type=%s WHERE comment_ID=%d",'public',$id));
            $wpdb->query($wpdb->prepare("UPDATE {$wpdb->commentmeta} SET meta_key=replace(meta_key,%s,%s) WHERE comment_ID=%d",'_public','',$id));
            $data['status'] = true;
          }
        }
        return new WP_REST_Response($data, 200);
        
    }

    function make_comment_public($request){
        $body = json_decode($request->get_body(), true);
        $user_id = $this->user->id;
        $data = array('status'=>0);

        $id=intval($body['comment_id']);
       
     
        if(user_can($user_id,'edit_posts')){
          global $wpdb;
          $wpdb->query($wpdb->prepare("UPDATE {$wpdb->comments} SET comment_type=%s WHERE comment_ID=%d",'public',$id));
          $wpdb->query($wpdb->prepare("UPDATE {$wpdb->commentmeta} SET meta_key=CONCAT(meta_key,%s) WHERE comment_ID=%d",'_public',$id));
          $data['status'] = true;
        }else{
          $comment = get_comment($id,ARRAY_A);
          if($comment['user_id'] == $user_id){
            global $wpdb;
            $wpdb->query($wpdb->prepare("UPDATE {$wpdb->comments} SET comment_type=%s WHERE comment_ID=%d",'public',$id));
            $wpdb->query($wpdb->prepare("UPDATE {$wpdb->commentmeta} SET meta_key=CONCAT(meta_key,%s) WHERE comment_ID=%d",'_public',$id));
            $data['status'] = true;
          }
        }
        return new WP_REST_Response($data, 200);
    }

    function instructor_reply_comment($request){
        $body = json_decode($request->get_body(), true);
        $user_id = $this->user->id;
        $data = array('status'=>0);

        $id=intval($body['unit_id']);

        if(function_exists('bp_is_active') && bp_is_active('messages')){
          
          $instructor_ids = apply_filters('wplms_course_instructors',get_post_field('post_author',$id),$id);

          if(!is_array($instructor_ids))
            $instructor_ids=array($instructor_ids);

          $message .=' <a href="'.get_permalink($id).'">'.get_the_title($id).'</a>';
          foreach($instructor_ids as $instructor_id){
            $data['status'] = true;
            messages_new_message( array('sender_id' => $user_id, 'subject' => sprintf(__('Instructor reply requested for unit %s','wplms'),get_the_title($id)), 'content' => $message,   'recipients' => $instructor_id ) );
          }
        }
        return new WP_REST_Response($data, 200);
    }

    function get_notes($request){

        $body = json_decode($request->get_body(), true);
        $user_id = $this->user->id;
        $data = array('status'=>0);
        $num = intval($body['number']); 
        $type = $body['type'];

        $page = $body['page'];
        if(empty($page)){
            $page = 1;
        }
        $args = apply_filters('wplms_notes_dicussion_dashboard_args',array(
                'number'              => $num,
                'post_status'         => 'publish',
                'post_type'           => 'unit',
                'status'              => 'approve',
                'type'                => $type,
                'user_id'             => $user_id,
                'paged'               => $page,
            ));
        $comments_query = new WP_Comment_Query;
        $comments = $comments_query->query( $args );
        $current_user_id = get_current_user_id();
        if(!empty($comments)){
            $data['data'] = [];
            $data['status'] = true;
            foreach ($comments as $key => $comment) {
                $comment_data = (array)$comment;
                $comment_data['avatar'] = get_avatar_url($comment->user_id);

                $comment_data['comment_username'] = '<a href="'.bp_core_get_user_domain($comment->user_id).'" class="unit_comment_author"> '.bp_core_get_user_displayname( $comment->user_id) .'</a>';
                $comment_data['comment_meta'] = '<span ><span>'.__('UNIT','wplms').' : '.$comment->post_title.'</span><br /><i class="icon-clock"></i>&nbsp;'.human_time_diff(strtotime($comment->comment_date),current_time('timestamp')).'</span>';
                
                $data['data'][] = $comment_data;
            }
        }



        return new WP_REST_Response($data, 200);
    }

    function get_news($request){
        
        global $wpdb;
        
        $body = json_decode($request->get_body(), true);
        $user_id = $this->user->id;
        $data = array('status'=>0,'data'=>array());
        $num = intval($body['number']); 
        $user_courses=$wpdb->get_results($wpdb->prepare("
            SELECT posts.ID as ID
            FROM {$wpdb->posts} AS posts
            LEFT JOIN {$wpdb->postmeta} AS rel ON posts.ID = rel.post_id
            WHERE   posts.post_type   = 'course'
            AND   posts.post_status   = 'publish'
            AND   rel.meta_key   = %s
        ",$user_id));
        $course_ids=array();
        if(isset($user_courses) && is_array($user_courses)){
            foreach($user_courses as $course){
                $course_ids[]=$course->ID;
            }
        }

          
          if(!isset($course_ids) || !is_array($course_ids))
          $course_ids = array();

                $query_args = apply_filters('wplms-dashboard-news_filter',array(
            'post_type'=> 'news',
            'post_per_page'=> $num,
            'post_status' => 'publish',
            'meta_query'=>array(
              array(
                'meta_key' => 'vibe_news_course',
                'compare' => 'IN',
                'value' => $course_ids,
                'type' => 'numeric'
                ),
              )
            )); 
          $the_query = new WP_Query($query_args);

          //print_r($the_query);

          switch($width){
            case 'col-md-12':
             $size = 'full';
             break;
            case 'col-md-6 col-sm-12':
            $size = 'medium';
            break;
            case 'col-md-8 col-sm-12':
            case 'col-md-9 col-sm-12':
            $size = 'big';
            break;
            default:
            $size = 'small';
            break;
          }
          if(function_exists('vibe_get_option'))
            $instructor_field = vibe_get_option('instructor_field');
          
          if($the_query->have_posts()){
            $data['status'] = true;
            while($the_query->have_posts()){
              $the_query->the_post();
              $html = '';
              global $post;
              $format=get_post_format(get_the_ID());
              if(!isset($format) || !$format)
                $format = 'standard';

              $post_author = get_the_author_meta('ID');
              $displayname = bp_core_get_user_displayname($post_author);
              $special='';
              $html .= $field;
              if(bp_is_active('xprofile'))
                $special = bp_get_profile_field_data('field='.$instructor_field.'&user_id='.$post_author);

              $html .= '<li>';
              switch($format){
                case 'aside':
                $html .= get_the_post_thumbnail($post->ID,$size);
                $html .= '<div class="'.$format.'-block">';
                $html .= apply_filters('the_content',$post->post_content);
                $html .= '<div class="news_author">'.bp_core_fetch_avatar(array( 'item_id' => $post_author, 'type' => 'thumb')).'
                <h5><a href="'.bp_core_get_user_domain($post_author) .'">'.$displayname.'<span>'.$special.'</span></a></h5>
                <ul>'.get_the_term_list($post->ID,'news-tag','<li>','</li><li>','</li>').'</ul>
                </div>';
                $html .= '</div>';
                break;
                case 'image':
                $html .= get_the_post_thumbnail($post->ID,$size);
                $html .= '<div class="'.$format.'-block"><h4>'.get_the_title().'</h4>';
                $html .= apply_filters('the_content',$post->post_content);
                $html .= '<div class="news_author">'.bp_core_fetch_avatar(array( 'item_id' => $post_author, 'type' => 'thumb')).'
                <h5><a href="'.bp_core_get_user_domain($post_author) .'">'.$displayname.'<span>'.$special.'</span></a></h5>
                <ul>'.get_the_term_list($post->ID,'news-tag','<li>','</li><li>','</li>').'</ul>
                </div>';
                $html .= '</div>';
                break;
                case 'chat':
                $html .= '<div class="'.$format.'-block">';
                $html .= apply_filters('the_content',$post->post_content);
                $html .= '<a href="'.get_comments_link().'" class="chat_comments">';
                ob_start();
                comments_number( '0', '1', '%' );
                $html .= ob_get_clean();
                $html .= '<i class="icon-bubble"></i></a>';
                $html .= '<div class="news_author">'.bp_core_fetch_avatar(array( 'item_id' => $post_author, 'type' => 'thumb')).'
                <h5><a href="'.bp_core_get_user_domain($post_author) .'">'.$displayname.'<span>'.$special.'</span></a></h5>
                <ul>'.get_the_term_list($post->ID,'news-tag','<li>','</li><li>','</li>').'</ul>
                </div>';
                $html .= '</div>';
                break;
                case 'quote':
                case 'status':
                case 'gallery':
                case 'audio':
                case 'video':
                $html .= '<div class="'.$format.'-block">';
                $html .= apply_filters('the_content',$post->post_content);
                $html .= '<div class="news_author">'.bp_core_fetch_avatar(array( 'item_id' => $post_author, 'type' => 'thumb')).'
                <h5><a href="'.bp_core_get_user_domain($post_author) .'">'.$displayname.'<span>'.$special.'</span></a></h5>
                <ul>'.get_the_term_list($post->ID,'news-tag','<li>','</li><li>','</li>').'</ul>
                </div>';
                $html .= '</div>';
                break;
                default:
                $html .= '<div class="'.$format.'-block">
                      <h3 class="heading">'.get_the_title().'</h3>';
                $html .= '<div class="news_thumb">'.get_the_post_thumbnail().'</div>';
                $html .= apply_filters('the_content',$post->post_content);
                $html .= '<div class="news_author">'.bp_core_fetch_avatar(array( 'item_id' => $post_author, 'type' => 'thumb')).'
                <h5><a href="'.bp_core_get_user_domain($post_author) .'">'.$displayname.'<span>'.$special.'</span></a></h5>
                <ul>'.get_the_term_list($post->ID,'news-tag','<li>','</li><li>','</li>').'</ul>
                </div>';      
                $html .= '</div>';
                break;
              }
           
              $html .= '</li>';
              $data['data'][] =  $html;
            }
            
            
            
          }else{
            
            $data['data'][] = '<li><div class="error-block">'.__('No news for you !','wplms').'</div></li>';
            
          }
          wp_reset_postdata();
        
          if(user_can($user_id,'edit_posts' ))
          $data['extras'] = '<div class="extras"><a href="'.get_post_type_archive_link('news').'" target="_blank" class="small button archive-link'.(( $title )?'withtitle':'').'"><i class="vicon vicon-eye"></i></a><a href="'.admin_url( 'post-new.php?post_type=news').'" target="_blank" class="small button add_news'.(( $title )?'withtitle':'').'"><i class="vicon vicon-plus"></i></a></div>';

        return new WP_REST_Response($data, 200);
    }

    function generate_ranges($request){
        global $wpdb;
        
        $body = json_decode($request->get_body(), true);
        $id = intval($body['unit']);
        $range = intval($body['range']);
        if(empty($range )){
            $range = 10;
        }
        $rdata = array('status'=>0);

        if(!empty($id )){
            
            $post_type = get_post_type($id);
           
            $student_marks_array=array();
            $query = $wpdb->get_results($wpdb->prepare("
                  SELECT rel.meta_key as student,rel.meta_value as marks
                    FROM {$wpdb->posts} AS posts
                    LEFT JOIN {$wpdb->postmeta} AS rel ON posts.ID = rel.post_id
                    WHERE   posts.post_type   = '%s'
                    AND posts.ID = %d
                    AND   rel.meta_key REGEXP '^-?[0-9]+$'
                    AND   rel.meta_value > 1
                    ",$post_type,$id),ARRAY_A);  

            if(is_array($query) && count($query)){
                
                foreach($query as $k => $value){
                  $student_marks_array[$value['student']]=$value['marks'];
                }

                asort($student_marks_array);

                $max=max($student_marks_array);
                $min=min($student_marks_array);

                $range_val = round(($max-$min)/$range);
                $student_range=array();

                $begin = $min;
                $end = $min+$range_val;
                if($range_val >= 1){
                  $i=0;
                  foreach($student_marks_array as $key=>$value){

                    if(isset($student_range[$begin.'-'.$end]['value']))
                      $i=$student_range[$begin.'-'.$end]['value'];
                    else
                      $i=0;

                     if($value >= $begin && $value <= $end){
                       $i++;
                       $student_range[$begin.'-'.$end]=array(
                        'range'=>$begin.'-'.$end,
                        'value'=> $i 
                        );
                     }else{
                      $i=0;
                       while($value > $end){
                          $begin = $begin+$range_val; 
                          $end=$end+$range_val;
                          if($end > $max)
                          $end=$max;
                       }
                       $i++;
                       $student_range[$begin.'-'.$end]=array(
                        'range'=>$begin.'-'.$end,
                        'value'=> $i 
                      );
                     }
                  }//end for
                }else{
                  if(is_array($student_marks_array)){
                  foreach($student_marks_array as $key=>$value){
                    $student_range[$value]=array(
                      'range' => $value,
                      'value' => 1
                      );
                    }
                  }
                }

                if(!empty($student_range)){
                    $rdata['status'] = 1;
                    $ndata = [];
                    foreach ($student_range as $key => $dat) {
                        $ndata[] = $dat;
                    }
                    $rdata['data'] = $ndata;
                }
                
            }
        }
        return new WP_REST_Response($rdata, 200);
    }

    function load_quiz_assignment($request){
        
        global $wpdb;
        
        $data = array('status'=>0);
       
        $body = json_decode($request->get_body(), true);
        $id = intval($body['course']);
        if(!empty($id )){
            $data['list'] = array();
            $quiz_list = $wpdb->get_results($wpdb->prepare("
                        SELECT posts.ID as id,posts.post_title as title
                        FROM {$wpdb->posts} AS posts
                        LEFT JOIN {$wpdb->postmeta} AS rel ON posts.ID = rel.post_id
                        WHERE   posts.post_type   = 'quiz'
                        AND   rel.meta_key = 'vibe_quiz_course'
                        AND   posts.post_status   = 'publish'
                        AND   rel.meta_value = %d
                        ",$id),ARRAY_A);

            $assignment_list = $wpdb->get_results($wpdb->prepare("
                        SELECT posts.ID as id,posts.post_title as title
                        FROM {$wpdb->posts} AS posts
                        LEFT JOIN {$wpdb->postmeta} AS rel ON posts.ID = rel.post_id
                        WHERE   posts.post_type   = 'wplms-assignment'
                        AND   rel.meta_key = 'vibe_assignment_course'
                        AND   posts.post_status   = 'publish'
                        AND   rel.meta_value = %d
                        ",$id),ARRAY_A);  

            if(is_array($quiz_list) || is_array($assignment_list)){
                
                if(is_array($quiz_list))
                foreach($quiz_list as $quiz){
                    $data['curriculum'][] = array('id'=>$quiz['id'],'title'=>$quiz['title']);
                 
                }
                if(is_array($assignment_list))
                foreach($assignment_list as $assignment){
                    $data['curriculum'][] = array('id'=>$assignment['id'],'title'=>$assignment['title']);
                }
            } 
        }
        
        if(!empty($data['curriculum']) && count($data['curriculum'])){
            $data['status'] = 1;
        }
        return new WP_REST_Response($data, 200);
    }

    function recalculate_stats($request){
        $flag=0;
        $data = array('status'=>0);
       
        $body = json_decode($request->get_body(), true);
        $course_id = intval($body['course']);
        if ( isset($course_id) && $course_id){
            $data = array('status'=>1);
        }
        $badge=$pass=$total_qmarks=$gross_qmarks=0;
        $users=array();
        global $wpdb;

        $badge_val=get_post_meta($course_id,'vibe_course_badge_percentage',true);
        $pass_val=get_post_meta($course_id,'vibe_course_passing_percentage',true);

        $members_course_grade = $wpdb->get_results(apply_filters('wplms_usermeta_direct_query', $wpdb->prepare("SELECT meta_value,meta_key FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key IN (SELECT DISTINCT user_id FROM {$wpdb->usermeta} WHERE meta_key = %s)",$course_id,'course_status'.$course_id)), ARRAY_A);
      
        if(count($members_course_grade)){
        $cmarks=$i=0;
            foreach($members_course_grade as $meta){
                if(is_numeric($meta['meta_key']) && $meta['meta_value'] > 2){
           
                            if($meta['meta_value'] >= $badge_val)
                                $badge++;

                            if($meta['meta_value'] >= $pass_val)
                                $pass++;

                            $users[]=$meta['meta_key'];

                if(isset($meta['meta_value']) && is_numeric($meta['meta_value']) && $meta['meta_value'] > 2 && $meta['meta_value']<101){
                  $cmarks += $meta['meta_value'];
                  $i++;
                }
                        }
                }  // META KEY is NUMERIC ONLY FOR USERIDS
        }

        if($pass)
            update_post_meta($course_id,'pass',$pass);


        if($badge)
            update_post_meta($course_id,'badge',$badge);

        if($i==0)$i=1;
        $avg = round(($cmarks/$i));

        update_post_meta($course_id,'average',$avg);

        if($flag !=1){
            $curriculum=bp_course_get_curriculum($course_id);
                foreach($curriculum as $c){
                    if(is_numeric($c)){

                        if(bp_course_get_post_type($c) == 'quiz'){
                  $i=$qmarks=0;

                            foreach($users as $user){
                                $k=get_post_meta($c,$user,true);
                    if(is_numeric($k)){
                                $qmarks +=$k;
                      $i++;
                                $gross_qmarks +=$k;
                    }
                            }
                  if($i==0)$i=1;
                            
                  $qavg=round(($qmarks/$i),1);

                            if($qavg)
                                update_post_meta($c,'average',$qavg);
                            else{
                                $flag=1;
                                break;
                            }
                        }
                    }
            }
        }

        if(class_exists('WPLMS_Assignments')){ // Assignment is active
          $assignments_query = $wpdb->get_results( $wpdb->prepare("select post_id from {$wpdb->postmeta} where meta_value = %d AND meta_key = 'vibe_assignment_course'",$course_id), ARRAY_A);
          foreach($assignments_query as $assignment_query){
            $assignments[]=$assignment_query['post_id'];
          }

          if(!empty($assignments) && count($assignments)){ // If any connected assignments
            $assignments_string = implode(',',$assignments);
            $assignments_marks_query = $wpdb->get_results("select post_id,meta_value from {$wpdb->postmeta} where post_id IN ($assignments_string) AND meta_key REGEXP '^[0-9]+$' AND meta_value REGEXP '^[0-9]+$'", ARRAY_A);
            
            foreach($assignments_marks_query as $marks){
              $user_assignments[$marks['post_id']]['total'] += $marks['meta_value'];
              $user_assignments[$marks['post_id']]['number']++;
            }

            foreach($user_assignments as $key=>$user_assignment){
              if(isset($user_assignment['number']) && $user_assignment['number']){
                $avg = $user_assignment['total']/$user_assignment['number'];  
                update_post_meta($key,'average',$avg);
              }
            }
          }
        }

        $data['average'] = $avg;
        return new WP_REST_Response($data, 200);

    }

    function instructor_stats($request){
        $body = json_decode($request->get_body(), true);
        $data = array(
            'status' => 0
        );
        
        $user_id = $this->user->id;
        $number = intval($body['number']);
        if (!empty($user_id) && !empty($number)) {
            $data['status'] = 1;
        }
        global $wpdb;
        
        $query = apply_filters('wplms_dashboard_courses_instructorss',$wpdb->prepare("
              SELECT posts.ID as course_id,post_title as title
                FROM {$wpdb->posts} AS posts
                WHERE   posts.post_type   = 'course'
                AND   posts.post_author   = %d
                AND   posts.post_status   = 'publish'
                ORDER BY posts.post_modified_gmt DESC
                LIMIT 0,%d
            ",$user_id,$number),$user_id,array('max'=>$number));

        $instructor_courses=$wpdb->get_results($query,ARRAY_A);
   
        if(!empty($instructor_courses) && count($instructor_courses)){
            $data['stats'] = 1;
            $data['data'] = array();
            foreach ($instructor_courses as $key => $c) {
                $avg = get_post_meta($c['course_id'],'average',true);
                $data['data'][] = array(
                    'id'=>$c['course_id'],
                    'title'=>$c['title'],
                    'average' => $avg,
                    );
            }
        }
        return new WP_REST_Response($data, 200);
    }

    function get_student_simple_stats($request){
        $body = json_decode($request->get_body(), true);
        $data = array(
            'status' => 0
        );
        if (!empty($body['type']) && !empty($this->user->id)) {
            $data['status'] = 1;
        }
        $user_id = $this->user->id;
        $title = '';
        global $wpdb;
        switch ($body['type']) {
            case 'courses':
                $query = "SELECT pm.post_id as id, pm.meta_value as val 
                    FROM {$wpdb->posts} AS p
                    LEFT JOIN {$wpdb->postmeta} AS pm ON p.ID = pm.post_id
                    WHERE   p.post_type   = 'course'
                    AND   p.post_status   = 'publish'
                    AND   pm.meta_key   = %d
                    AND   pm.meta_value > 2 ";
                $marks = $wpdb->get_results($wpdb->prepare($query,$user_id),ARRAY_A);
                $label = !empty($title)?$title:__('Courses Completed', 'wplms');
                $value = count($marks);
                /* Not used
                    $user_marks = array();
                    if (is_array($marks)) {
                        foreach ($marks as $k => $mark) {
                            $user_marks[$mark['id']] = $mark['val'];
                        }
                    }    
                    if (is_array($user_marks)) {
                        foreach ($user_marks as $i => $mark) {
                            if ($i < 11) {
                                if (!$i) {
                                    $marks_string = $mark;
                                } else {
                                    $marks_string .= ',' . $mark;
                                }
                            }
                        }
                    } 
                */
                break;
            case 'course_ratio':
                // completed course
                $query = "SELECT pm.post_id as id, pm.meta_value as val 
                    FROM {$wpdb->posts} AS p
                    LEFT JOIN {$wpdb->postmeta} AS pm ON p.ID = pm.post_id
                    WHERE   p.post_type   = 'course'
                    AND   p.post_status   = 'publish'
                    AND   pm.meta_key   = %d
                    AND   pm.meta_value > 2 ";
                $course_completed = $wpdb->get_results($wpdb->prepare($query,$user_id),ARRAY_A);
                // subscribed course
                $query = "SELECT pm.post_id as id, pm.meta_value as val 
                    FROM {$wpdb->posts} AS p
                    LEFT JOIN {$wpdb->postmeta} AS pm ON p.ID = pm.post_id
                    WHERE   p.post_type   = 'course'
                    AND   p.post_status   = 'publish'
                    AND   pm.meta_key   = %d";
                $course_subscribed = $wpdb->get_results($wpdb->prepare($query,$user_id),ARRAY_A);
                $label = !empty($title)?$title:__('Completed Courses/ Total Number of Courses', 'wplms');
                $value1 = count($course_completed);
                $value2 = count($course_subscribed);
                $value = $value1 . '/' . $value2;
                break;
            case 'assignments':
                $query = "SELECT rel.post_id as id,rel.meta_value as val
                    FROM {$wpdb->posts} AS posts
                    LEFT JOIN {$wpdb->postmeta} AS rel ON posts.ID = rel.post_id
                    WHERE  posts.post_type   = 'wplms-assignment'
                    AND   posts.post_status   = 'publish'
                    AND   rel.meta_key   = %d
                    AND   rel.meta_value > 0";
                $marks = $wpdb->get_results($wpdb->prepare($query,$user_id),ARRAY_A);
                $label = !empty($title)?$title: __('Assignments Completed', 'wplms');
                $value = count($marks);
                break;
            case 'quizes':
                $query = "SELECT rel.post_id as id,rel.meta_value as val
                    FROM {$wpdb->posts} AS posts
                    LEFT JOIN {$wpdb->postmeta} AS rel ON posts.ID = rel.post_id
                    WHERE  posts.post_type   = 'quiz'
                    AND   posts.post_status   = 'publish'
                    AND   rel.meta_key   = %d
                    AND   rel.meta_value >= 0";
                $marks = $wpdb->get_results($wpdb->prepare($query,$user_id),ARRAY_A);
                $label = !empty($title)?$title: __('Quizzes Completed', 'wplms');
                $value = count($marks);
                /* Not used
                    $user_marks = array();
                    if (is_array($marks)) {
                        foreach ($marks as $k => $mark) {
                            $user_marks[$mark['id']] = $mark['val'];
                        }
                    }
                    if (is_array($user_marks)) {
                        foreach ($user_marks as $i => $mark) {
                            if ($i < 11) {
                                if (!$i) {
                                    $marks_string = $mark;
                                } else {
                                    $marks_string .= ',' . $mark;
                                }
                            }
                        }
                    }
                */
                break;
            case 'units':
                $query = "SELECT meta_key,meta_value
                    FROM {$wpdb->usermeta} as um
                    WHERE user_id = %d
                    AND  meta_key LIKE '%complete_unit_%'
                    AND meta_value > 0";
                $marks = $wpdb->get_results($wpdb->prepare($query,$user_id),ARRAY_A);
                $label = !empty($title)?$title:__('Units Completed', 'wplms');
                $value = count($marks);  
                break;
        }
        $data['data'] = array(
            'label' => $label,
            'value' => $value
        );

        return new WP_REST_Response($data, 200);
    }

    function get_instructor_simple_stats($request)
    {
        $body = json_decode($request->get_body(), true);
        $data = array(
            'status' => 0
        );
        if (!empty($body['type'])) {
            $data['status'] = 1;
            $user_id = $this->user->id;
            global $wpdb;
            switch ($body['type']) {
                case 'woo_commission':
                    $migrated_to_activity = apply_filters(
                        'wplms_commissions_migrate_to_activity',
                        1
                    );
                    if (!$migrated_to_activity) {
                        $total_commission = get_user_meta(
                            $user_id,
                            'total_commission',
                            true
                        );
                        if (
                            function_exists('get_woocommerce_currency_symbol')
                        ) {
                            $symbol = get_woocommerce_currency_symbol();
                        }

                        if (!isset($symbol)) {
                            $symbol = '$';
                        }

                        if (function_exists('wc_price')) {
                            $value = str_replace(
                                'span',
                                'strong',
                                wc_price(round($total_commission, 0))
                            );
                        }
                        //$value = $symbol.round($total_commission,0);
                        if (!is_numeric($total_commission)) {
                            $value = __('N.A', 'wplms');
                        }


                        if ($title) {
                            $label = $title;
                        } else {
                            $label = __(
                                'Total Commission Earned',
                                'wplms'
                            );
                        }
                    } else {
                        //means migrated to activity for multicurrency
                        $value = array();
                        if ($title) {
                            $label = $title;
                        } else {
                            $label = __(
                                'Total Commission Earned',
                                'wplms'
                            );
                        }
                       
                        $total_commission_cur = get_user_meta(
                            $user_id,
                            'total_commission_cur',
                            true
                        );

                        if (!empty($total_commission_cur)  ) {
                            foreach (
                                $total_commission_cur
                                as $key => $total_commission
                            ) {
                                $multi_html .=
                                    '<div id="total-commission-widget-tab-' .
                                    $key .
                                    '" class="tab-pane" ' .
                                    (count($currencies_data) < 2
                                        ? 'style="display:block"'
                                        : '') .
                                    '>';


                                if (
                                    function_exists(
                                        'get_woocommerce_currency_symbol'
                                    )
                                ) {
                                    $symbol = get_woocommerce_currency_symbol(
                                        $key
                                    );
                                }

                                if (!isset($symbol)) {
                                    $symbol = '$';
                                }
                                $value[] = array(
                                    'currency'=>$key,
                                    'value'=>round($total_commission, 0),
                                    'symbol'=>$symbol
                                    );
                                

                                
                                
                            } 
                        }else{
                            $value = 0;
                        }

                        
                    }

                    break;
                case 'courses':
                    $query = apply_filters(
                        'wplms_dashboard_instructors_course_count',
                        $wpdb->prepare(
                            "
		              SELECT count(posts.ID) as num
		                FROM {$wpdb->posts} AS posts
		                WHERE   posts.post_type   = 'course'
		                AND   posts.post_author   = %d
		            ",
                            $user_id
                        )
                    );

                    $instructor_courses = $wpdb->get_results($query, ARRAY_A);

                    if (
                        isset($instructor_courses[0]['num']) &&
                        is_numeric($instructor_courses[0]['num'])
                    ) {
                        $value = $instructor_courses[0]['num'];
                    } else {
                        $value = 0;
                    }

                    if ($title) {
                        $label = $title;
                    } else {
                        $label = __('Courses Instructing', 'wplms');
                    }
                    break;
                case 'quizes':
                    $query = apply_filters(
                        'wplms_dashboard_instructors_quiz_count',
                        $wpdb->prepare(
                            "
		              SELECT count(posts.ID) as num
		                FROM {$wpdb->posts} AS posts
		                WHERE   posts.post_type   = 'quiz'
		                AND   posts.post_author   = %d
		            ",
                            $user_id
                        )
                    );

                    $instructor_courses = $wpdb->get_results($query, ARRAY_A);
                    if (
                        isset($instructor_courses[0]['num']) &&
                        is_numeric($instructor_courses[0]['num'])
                    ) {
                        $value = $instructor_courses[0]['num'];
                    } else {
                        $value = 0;
                    }

                    if ($title) {
                        $label = $title;
                    } else {
                        $label = __('Quizzes Created', 'wplms');
                    }
                    break;
                case 'units':
                    $query = apply_filters(
                        'wplms_dashboard_instructors_unit_count',
                        $wpdb->prepare(
                            "
		              SELECT count(posts.ID) as num
		                FROM {$wpdb->posts} AS posts
		                WHERE   posts.post_type   = 'unit'
		                AND   posts.post_author   = %d
		            ",
                            $user_id
                        )
                    );

                    $instructor_courses = $wpdb->get_results($query, ARRAY_A);
                    if (
                        isset($instructor_courses[0]['num']) &&
                        is_numeric($instructor_courses[0]['num'])
                    ) {
                        $value = $instructor_courses[0]['num'];
                    } else {
                        $value = 0;
                    }

                    if ($title) {
                        $label = $title;
                    } else {
                        $label = __('Units Created', 'wplms');
                    }
                    break;
                case 'assignments':
                    $query = apply_filters(
                        'wplms_dashboard_instructors_assignment_count',
                        $wpdb->prepare(
                            "
		              SELECT count(posts.ID) as num
		                FROM {$wpdb->posts} AS posts
		                WHERE   posts.post_type   = 'wplms-assignment'
		                AND   posts.post_author   = %d
		            ",
                            $user_id
                        )
                    );

                    $instructor_courses = $wpdb->get_results($query, ARRAY_A);
                    if (
                        isset($instructor_courses[0]['num']) &&
                        is_numeric($instructor_courses[0]['num'])
                    ) {
                        $value = $instructor_courses[0]['num'];
                    } else {
                        $value = 0;
                    }

                    if ($title) {
                        $label = $title;
                    } else {
                        $label = __('Assignments Created', 'wplms');
                    }
                    break;
                case 'questions':
                    $query = apply_filters(
                        'wplms_dashboard_instructors_question_count',
                        $wpdb->prepare(
                            "
		              SELECT count(posts.ID) as num
		                FROM {$wpdb->posts} AS posts
		                WHERE   posts.post_type   = 'question'
		                AND   posts.post_author   = %d
		            ",
                            $user_id
                        )
                    );

                    $instructor_courses = $wpdb->get_results($query, ARRAY_A);
                    if (
                        isset($instructor_courses[0]['num']) &&
                        is_numeric($instructor_courses[0]['num'])
                    ) {
                        $value = $instructor_courses[0]['num'];
                    } else {
                        $value = 0;
                    }

                    if ($title) {
                        $label = $title;
                    } else {
                        $label = __('Questions Created', 'wplms');
                    }
                    break;
                case 'badges':
                    $bg = apply_filters(
                        'wplms_dashboard_instructors_course_badges',
                        $wpdb->get_results(
                            $wpdb->prepare(
                                "
		                  SELECT SUM(rel.meta_value) as total_badge
		                    FROM {$wpdb->posts} AS posts
		                    LEFT JOIN {$wpdb->postmeta} AS rel ON posts.ID = rel.post_id
		                    WHERE   posts.post_type   = 'course'
		                    AND posts.post_author = %d
		                  AND   posts.post_status   = 'publish'
		                  AND   rel.meta_key   = 'badge'
		                ",
                                $user_id
                            )
                        )
                    );
                    if (
                        isset($bg[0]->total_badge) &&
                        is_numeric($bg[0]->total_badge)
                    ) {
                        $value = $bg[0]->total_badge;
                    } else {
                        $value = 0;
                    }

                    if ($title) {
                        $label = $title;
                    } else {
                        $label = __('Badges Awarded', 'wplms');
                    }

                    break;
                case 'certificates':
                    $ps = apply_filters(
                        'wplms_dashboard_instructors_course_certificates',
                        $wpdb->get_results(
                            $wpdb->prepare(
                                "
		                  SELECT SUM(rel.meta_value) as total_pass
		                    FROM {$wpdb->posts} AS posts
		                    LEFT JOIN {$wpdb->postmeta} AS rel ON posts.ID = rel.post_id
		                    WHERE   posts.post_type   = 'course'
		                     AND posts.post_author = %d
		                  AND   posts.post_status   = 'publish'
		                  AND   rel.meta_key   = 'pass'
		                ",
                                $user_id
                            )
                        )
                    );

                    if (
                        isset($ps[0]->total_pass) &&
                        is_numeric($ps[0]->total_pass)
                    ) {
                        $value = $ps[0]->total_pass;
                    } else {
                        $value = 0;
                    }

                    if ($title) {
                        $label = $title;
                    } else {
                        $label = __('Certificates awarded', 'wplms');
                    }
                    break;
                case 'students':
                    $ps = apply_filters(
                        'wplms_dashboard_instructors_course_students',
                        $wpdb->get_results(
                            $wpdb->prepare(
                                "
		                    SELECT SUM(rel.meta_value) as total_students
		                    FROM {$wpdb->posts} AS posts
		                    LEFT JOIN {$wpdb->postmeta} AS rel ON posts.ID = rel.post_id
		                    WHERE   posts.post_type   = 'course'
		                    AND posts.post_author = %d
		                    AND   posts.post_status   = 'publish'
		                    AND   rel.meta_key   = 'vibe_students'
		                    ",
                                $user_id
                            )
                        )
                    );

                    if (
                        isset($ps[0]->total_students) &&
                        is_numeric($ps[0]->total_students)
                    ) {
                        $value = $ps[0]->total_students;
                    } else {
                        $value = 0;
                    }

                    if ($title) {
                        $label = $title;
                    } else {
                        $label = __(
                            'Total Students in Courses',
                            'wplms'
                        );
                    }
                    break;
                default:
                    $value = apply_filters(
                        'wplms_instructor_dash_stats_default_value',
                        '',
                        $stats
                    );
                    $value_string = apply_filters(
                        'wplms_instructor_dash_stats_default_value_string',
                        '',
                        $stats
                    );
                    if ($title) {
                        $label = $title;
                    }
                    break;
            }

            $data['data'] = array(
                'label' => $label,
                'value' => $value,
                'message' => __(
                    'Student dashboard data recieved.',
                    'wplms'
                )
            );
        }

        return new WP_REST_Response($data, 200);
    }

    function get_currencies()
    {
        global $wpdb, $bp;
        $commissions = array();
        $results = $wpdb->get_results( "
                                      SELECT meta2.meta_value as currency
                                      FROM  {$bp->activity->table_name_meta} as meta2 
                                      WHERE  meta2.meta_key LIKE '_currency%'
                                      AND meta2.meta_value IS NOT NULL
                                      GROUP BY meta2.meta_value
                                      
                                      ",ARRAY_A);
        return $results;
    }

    function get_inst_course_commission($user_id){
      // date format in php would be : Y-m-d
        //$start_date = date('Y-m-d',strtotime($start_date));
        $start_date = date("Y")."-01-01";
        //$end_date = date('Y-m-d',strtotime($end_date));
        $end_date = date("Y")."-12-31";
        global $wpdb,$bp;
        $commissions = array();
        $results = $wpdb->get_results( "
                                      SELECT activity.user_id,activity.item_id as course_id,meta.meta_value as commission,meta2.meta_value as currency,MONTH(activity.date_recorded) as date
                                      FROM {$bp->activity->table_name} AS activity 
                                      LEFT JOIN {$bp->activity->table_name_meta} as meta ON activity.id = meta.activity_id
                                      LEFT JOIN {$bp->activity->table_name_meta} as meta2 ON activity.id = meta2.activity_id
                                      WHERE     activity.component     = 'course'
                                      AND     activity.type     = 'course_commission'
                                      AND     activity.user_id     = {$user_id}
                                      AND     meta.meta_key   LIKE '_commission%'
                                      AND     meta2.meta_key   LIKE '_currency%'
                                      AND activity.date_recorded BETWEEN '$start_date' AND '$end_date' ORDER BY activity.date_recorded ASC
                                      ",ARRAY_A);
        return $results;
      
    }

    function generate_commission_data($request){
      global $wpdb;
      $body = json_decode($request->get_body(), true);
      $user_id = $this->user->id;


      $migrated_to_activity = apply_filters('wplms_commissions_migrate_to_activity',1);
   
      if(!$migrated_to_activity){
        if(function_exists('vibe_get_option'))
        $instructor_commission = vibe_get_option('instructor_commission');
      
        if(!isset($instructor_commission) || !$instructor_commission)
          $instructor_commission = 70;

        $commissions = get_option('instructor_commissions');


        $query = apply_filters('wplms_dashboard_courses_instructors',$wpdb->prepare("
          SELECT posts.ID as course_id
            FROM {$wpdb->posts} AS posts
            WHERE   posts.post_type   = 'course'
            AND   posts.post_author  = %d
          ",$user_id));

        $instructor_courses=$wpdb->get_results($query,ARRAY_A);
        $total_commission=0;
        $commision_array=array();
        $course_product_map=array();
        $daily_val = array();
        if(count($instructor_courses)){

          foreach($instructor_courses as $key => $value){
            $course_id=$value['course_id'];

            $pid=get_post_meta($course_id,'vibe_product',true);
            if(is_numeric($pid)){
              if(isset($commissions[$course_id][$user_id])){
                $course_commission[$course_id]=$commissions[$course_id][$user_id];
              }else{
                $course_commission[$value['course_id']] = $instructor_commission;
              }
              $product_ids[]= $pid;
              $course_product_map[$pid]=$course_id;
            }
          }
          if(!is_array($product_ids))
            die();
          
          $product_id_string=implode(',',$product_ids);
          $item_meta_table = $wpdb->prefix.'woocommerce_order_itemmeta';
          $order_items_table= $wpdb->prefix.'woocommerce_order_items';

          // CALCULATED COMMISSIONS

          $product_sales=$wpdb->get_results("
           SELECT order_meta.meta_value as value,order_meta.order_item_id as item_id,MONTH(post_meta.meta_value) as date,order_items.order_id as order_id
           FROM $item_meta_table AS order_meta
           LEFT JOIN $order_items_table as order_items ON order_items.order_item_id = order_meta.order_item_id
           LEFT JOIN {$wpdb->postmeta} as post_meta ON post_meta.post_id = order_items.order_id
            LEFT JOIN {$wpdb->posts} as posts ON posts.ID = order_items.order_id
           WHERE  (order_meta.meta_key = 'commission$user_id' 
           OR order_meta.meta_key = '_commission$user_id')
           AND  post_meta.meta_key = '_completed_date' 
           AND posts.post_status='wc-completed'
           AND post_meta.meta_value!='NULL'
           ",ARRAY_A);
          $earned_months = array();
          $sales_pie=array();
          $i=count($product_sales);
          if(is_array($product_sales) && $i){

            foreach($product_sales as $sale){
              $pid=wc_get_order_item_meta( $sale['item_id'],'_product_id',true);
              $ctitle=get_the_title($course_product_map[$pid]);
              $sales_pie[$course_product_map[$pid]] += $sale['value'];
              $val=$sale['value'];
              $order_ids[]=$sale['order_id'];
              $total_commission += $val;
              $daily_val[$sale['date']]+=$val;
              $earned_months[] = $sale['date'];
            }
          }

          if(is_array($daily_val)){

            if(count($daily_val)){
              ksort($daily_val);
              foreach($daily_val as $key => $value){
                $commission_array[$key]=array(
                  'date' => date('M', mktime(0, 0, 0, $key, 10)),
                  'sales'=>$value);
              }
            }
            update_user_meta($user_id,'commission_data',$commission_array);
            update_user_meta($user_id,'sales_pie',$sales_pie);
            update_user_meta($user_id,'total_commission',$total_commission);
          }

          // Commission Paid out calculation
          $flag = 0;
          $commission_recieved = array();
          $commissions_paid = $wpdb->get_results($wpdb->prepare("
            SELECT meta_value,post_id FROM {$wpdb->postmeta} 
            WHERE meta_key = %s
           ",'vibe_instructor_commissions'));

          if(isset($commissions_paid) && is_Array($commissions_paid) && count($commissions_paid)){
            foreach($commissions_paid as $commission){
                $commission->meta_value = unserialize($commission->meta_value);
                if(isset($commission->meta_value[$user_id]) && isset($commission->meta_value[$user_id]['commission'])){
                  $flag=1;
                  $date = $wpdb->get_var($wpdb->prepare("SELECT MONTH(post_date) FROM {$wpdb->posts} WHERE ID = %d",$commission->post_id));
                  $k = date('n', mktime(0, 0, 0, $date, 10));
                  $commission_recieved[$k]=array(
                      'date' => date('M', mktime(0, 0, 0, $date, 10)),
                      'commission'=>$commission->meta_value[$user_id]['commission']);
                }
            }
          }

          if($flag || !count($commission_recieved)){
            update_user_meta($user_id,'commission_recieved',$commission_recieved);
          }  
          echo 1;
          die();
        }// End count courses

        _e('No courses found for Instructor','wplms');
        die();

      }else{

        //migrated to activities with currencies 

        $ist_commissions = $this->get_inst_course_commission($user_id);
        if(!empty($ist_commissions)){
            $commision_array_cur = array();
            $sales_pie_cur = array();
            $total_commission_cur = array(); 
            $com = array();
            foreach ($ist_commissions as $key => $ist_commission) {
              $total_commission_cur[$ist_commission['currency']] += $ist_commission['commission'];
              

              $com[$ist_commission['currency']][$ist_commission['date']] = $com[$ist_commission['currency']][$ist_commission['date']]+$ist_commission['commission'];
             
              $commision_array_cur[$ist_commission['currency']][$ist_commission['date']]=  array(
                   'date' =>date('M', mktime(0, 0, 0, $ist_commission['date'], 10)),
                   'sales' =>$com[$ist_commission['currency']][$ist_commission['date']]
                  
                  );
              $sales_pie_cur[$ist_commission['currency']][$ist_commission['course_id']] += $ist_commission['commission'];
            }
            update_user_meta($user_id,'commission_data_cur',$commision_array_cur);
            update_user_meta($user_id,'sales_pie_cur',$sales_pie_cur);
            
            update_user_meta($user_id,'total_commission_cur',$total_commission_cur);
            
            // Commission Paid out calculation
            $flag = 0;
            $commission_recieved_cur = array();
            $commissions_paid = $wpdb->get_results($wpdb->prepare("
              SELECT meta_value,post_id FROM {$wpdb->postmeta} 
              WHERE meta_key = %s
             ",'vibe_instructor_commissions'));

            if(isset($commissions_paid) && is_Array($commissions_paid) && count($commissions_paid)){
              foreach($commissions_paid as $commission){
                  $commission->meta_value = unserialize($commission->meta_value);
                  if(isset($commission->meta_value[$user_id]) && isset($commission->meta_value[$user_id]['commission'])){
                    $flag=1;
                    $date = $wpdb->get_var($wpdb->prepare("SELECT MONTH(post_date) FROM {$wpdb->posts} WHERE ID = %d",$commission->post_id));
                    $k = date('n', mktime(0, 0, 0, $date, 10));

                    //if currency not set then set default currency 
                    if(empty($commission->meta_value[$user_id]['currency']) && function_exists('get_woocommerce_currency')){
                      $commission->meta_value[$user_id]['currency'] = get_woocommerce_currency();
                    }

                    $commission_recieved_cur[$commission->meta_value[$user_id]['currency']][$k]=array(
                        'date' => date('M', mktime(0, 0, 0, $date, 10)),
                        'commission'=>$commission->meta_value[$user_id]['commission']);
                  }
              }
            }
            if($flag || !count($commission_recieved_cur)){

              update_user_meta($user_id,'commission_recieved_cur',$commission_recieved_cur);
            }
            echo 1;
            die();
        }
        
        _e('No data found for Instructor','wplms');
        die();

      }

    }

    function get_currencies_data($request){
        $currencies_data = $this->get_currencies();
        $data = array('status'=>false,'message'=>_x('No currency data','','wplms'));
        if(!empty($currencies_data)){
            $currencies = [];
            foreach ($currencies_data as $key => $cc) {
                if(function_exists('get_woocommerce_currency_symbol')){
                    $currencies[] = array('label'=>get_woocommerce_currency_symbol($cc['currency']),
                        'value' =>$cc['currency'],
                        );
                
                }
            }
            $data = array('status'=>true,'message'=>_x('Currencies data found','','wplms'),'currencies'=>$currencies);
        }
        return new WP_REST_Response($data, 200);
    }

    function get_instructor_commissions($request)
    {
        $body = json_decode($request->get_body(), true);
        $user_id = $this->user->id;

        $data = array(
            'status' => 1,
            'message' => __('No commission data found', 'wplms'),
            'data'=>array()
        );
        $_cur = array();
        $_cur = $body['currency'];
        $currencies_data = $this->get_currencies();
        
        if(!empty($currencies_data) ){
            foreach ($currencies_data as $key => $cc) {
                if(function_exists('get_woocommerce_currency_symbol')){
                    $data['data']['currencies'][] = array('label'=>get_woocommerce_currency_symbol($cc['currency']),
                        'value' =>$cc['currency'],
                        );
                
                }
            }
        }


        
        if(!empty($currencies_data)){
             
            $commision_array_cur =  vibe_sanitize(get_user_meta($user_id,'commission_data_cur',false));
            $commission_recieved_cur = vibe_sanitize(get_user_meta($user_id,'commission_recieved_cur',false));
            $sales_pie_cur = vibe_sanitize(get_user_meta($user_id,'sales_pie_cur',false));
            $total_commission_cur = get_user_meta($user_id,'total_commission_cur',true);
          
            //foreach ($currencies_data as $key => $currency) {
            
                $commision_array = array();
                if(!empty($commision_array_cur) && isset($commision_array_cur[$_cur])){
                  $commision_array =  $commision_array_cur[$_cur];
                }
                $commission_recieved = array();

                if(!empty($commission_recieved_cur) && isset($commission_recieved_cur[$_cur])){
                  $commission_recieved = $commission_recieved_cur[$_cur];
                }

                $sales_pie =array();
                if(!empty($sales_pie_cur) && isset($sales_pie_cur[$_cur])){
                  $sales_pie = $sales_pie_cur[$_cur];
                }
                $total_commission =array();
                if(!empty($total_commission_cur) && isset($total_commission_cur[$_cur])){
                  $total_commission = $total_commission_cur[$_cur];
                }
                
                if(function_exists('get_woocommerce_currency_symbol')){
                  $symbol= get_woocommerce_currency_symbol($_cur);  
                }
                
                if(function_exists('wc_price')){
                  $value = wc_price($total_commission);
                }
                
                
                $sales_pie_array=array();
                if(isset($sales_pie) && is_array($sales_pie) && count($sales_pie)){
                    foreach($sales_pie as $cid=>$sales){
                        $sales_pie_array[]=array(
                          'label'=>get_the_title($cid),
                          'value' => $sales
                        );
                    }
                }

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
            //}
        }
        $data['data']['sales_pie']=$sales_pie_array;
        $data['data']['commissions']=$commision_array;
        return new WP_REST_Response($data, 200);
    }

    function get_instructor_courses($request)
    {
        $data = array(
            'status' => 1
        );

        $body = json_decode($request->get_body(), true);

        global $wpdb;
        
        $courses = $wpdb->get_results(apply_filters('wplms_dashboard_courses_instructors',
            $wpdb->prepare("SELECT ID as course_id from {$wpdb->posts} WHERE post_status='publish' AND post_type ='course' AND post_author = %d",
                $this->user->id
            ),$this->user->id));

        $_Courses = [];
        foreach ($courses as $key => $cc) {
            $_Courses[] = array('label'=>get_the_title($cc->course_id) , 'value'=>$cc->course_id);
        }
        if(empty($_Courses)){
            $data = array('status'=>'0','message'=>__('No courses found','wplms'));
        }else{
            $data['courses'] = $_Courses;   
        }
        
        return new WP_REST_Response($data, 200);
    }

    function get_instructor_announcement($request)
    {
        global $wpdb;
        $body = json_decode($request->get_body(), true);
        $user_id = $this->user->id;
        $query = apply_filters(
            'wplms_dashboard_courses_instructors',
            $wpdb->prepare(
                "
              SELECT posts.ID as course_id
                 FROM {$wpdb->posts} AS posts
                 WHERE   posts.post_type   = 'course'
                AND   posts.post_author   = %d
            ",
                $user_id
            ),
            $user_id
        );

        $instructor_courses = $wpdb->get_results($query, ARRAY_A);
        $announcements = array();

        if (isset($instructor_courses) && count($instructor_courses)) {
            foreach ($instructor_courses as $key => $value) {
                $course_id = $value['course_id'];
                $a = get_post_meta($course_id, 'announcement', true);
                $course_link = get_the_permalink($course_id);
                if ($a) {
                    $announcements[] = array(
                        'id' => $course_id,
                        'course_title' => get_the_title($course_id),
                        'course_link' => $course_link,
                        'announcement' => $a
                    );
                }
            }
            return new WP_REST_Response(
                array(
                    'status' => 1,
                    'announcements' => $announcements,
                    'message' => __(
                        'Courses found for Instructor',
                        'wplms'
                    )
                ),
                200
            );
        }

        return new WP_REST_Response(
            array(
                'status' => 0,
                'message' => __('No courses for Instructor', 'wplms')
            ),
            200
        );
    }

    function new_instructor_announcement($request)
    {
        $body = json_decode($request->get_body(), true);
        $user_id = $this->user->id;
        $course_id = $body['announcement']['course_id'];
        $student_type = $body['announcement']['student_type'];
        $announcement = $body['announcement']['message'];
        $course_link = get_the_permalink($course_id);

        update_post_meta($course_id, 'announcement', $announcement);
        if ($student_type) {
            update_post_meta(
                $course_id,
                'announcement_student_type',
                $student_type
            );
        }
        do_action(
            'wplms_dashboard_course_announcement',
            $course_id,
            $student_type,
            1,
            $announcement
        );

        return new WP_REST_Response(
            array(
                'status' => 1,
                'announcement' => array(
                    'id' => $course_id,
                    'course_title' => get_the_title($course_id),
                    'course_link' => $course_link,
                    'announcement' => $announcement
                )
            ),
            200
        );
    }

    function remove_instructor_announcement($request)
    {
        $body = json_decode($request->get_body(), true);
        $user_id = $this->user->id;
        $course_id = $body['course_id'];

        if (!empty($course_id)) {
            if (delete_post_meta($course_id, 'announcement')) {
                delete_post_meta($course_id, 'announcement_student_type');
            } else {
                _e('Unable to remove announcement', 'wplms');
            }

            $data = array(
                'status' => 1,
                'message' => __('Announcement Removed.', 'wplms')
            );

            return new WP_REST_Response($data, 200);
        }
    }

    function get_course_progress($request)
    {
        global $wpdb;
        $body = json_decode($request->get_body(), true);
        $user_id = $this->user->id;
        $_course_id = $body['course_id'];
        $num = $body['number'];
        $finished = $body['finished'];
        $data = array();
        if(!empty($_course_id)){
            if(bp_course_is_member($_course_id, $user_id))
            $query = "SELECT ID as id FROM {$wpdb->posts} WHERE ID = $_course_id";
        }else{
            $query = apply_filters(
                'wplms_usermeta_direct_query',
                $wpdb->prepare(
                    "
              SELECT posts.ID as id,meta.meta_value as status
              FROM {$wpdb->posts} AS posts
              LEFT JOIN {$wpdb->usermeta} AS meta ON CONCAT('course_status','',posts.ID) = meta.meta_key
              WHERE   posts.post_type   = %s
              AND   posts.post_status   = %s
              AND   meta.user_id   = %d  ORDER BY meta.umeta_id DESC
              LIMIT 0,{$num}
              ",
                    'course',
                    'publish',
                    $user_id
                )
            );

            if (!isset($finished) || !$finished) {
                $query = apply_filters(
                    'wplms_usermeta_direct_query',
                    $wpdb->prepare(
                        "
              SELECT posts.ID as id,meta.meta_value as status
              FROM {$wpdb->posts} AS posts
              LEFT JOIN {$wpdb->usermeta} AS meta ON CONCAT('course_status','',posts.ID) = meta.meta_key
              WHERE   posts.post_type   = %s
              AND   posts.post_status   = %s
              AND   meta.user_id   = %d
              AND   meta.meta_value < %d  ORDER BY meta.umeta_id DESC
              LIMIT 0,{$num}
              ",
                        'course',
                        'publish',
                        $user_id,
                        3
                    )
                );
            }
        }
        

        $results = $wpdb->get_results($query);
        if (!empty($results) && !is_wp_error($results)) {
            $i = 0;
            foreach ($results as $key => $course) {
                $course_id = $course->id;
                $percentage = bp_course_get_user_progress($user_id, $course_id);
                $percentage = apply_filters(
                    'wplms_course_progress_display',
                    $percentage,
                    $course_id,
                    $user_id
                );
                if (empty($percentage)) {
                    $percentage = 0;
                }
                if(!empty($_course_id)){
                    $data[] = array(
                        'percentage' => round($percentage, 2),
                    );
                }else{
                    $title = get_the_title($course_id);
                    $background = wplms_get_random_color($i);
                    $data[] = array(
                        'title' => $title,
                        'course_id' => $course_id,
                        'percentage' => round($percentage, 2),
                        'background' => $background,
                        'message' => __(
                            'Course Progress recieved.',
                            'wplms'
                        )
                    );
                }
                $i++;
                // push array in data (array push)
                
            }
            return new WP_REST_Response(
                array(
                    'status' => 1,
                    'data' => $data,
                    'message' => __(
                        'Progress Found for the course',
                        'wplms'
                    )
                ),
                200
            );
        }
        return new WP_REST_Response(
            array(
                'status' => 0,
                'message' => __(
                    'No progress found for any course',
                    'wplms'
                )
            ),
            200
        );
    }


    function get_modules($request){

        $body = json_decode($request->get_body(), true);

        if(empty($body['type'])){
            return new WP_REST_Response(array('status'=>0,'message'=>__('No type property in request','wplms')),200);
        }

        global $wpdb;
        switch($body['type']){
            case 'course':
                $data = $wpdb->get_results($wpdb->prepare("
                      SELECT DISTINCT ID,post_title
                      FROM {$wpdb->posts} AS posts
                      LEFT JOIN {$wpdb->postmeta} AS rel ON posts.ID = rel.post_id
                      WHERE   posts.post_type   = 'course'
                      AND   posts.post_status   = 'publish'
                      AND   rel.meta_key   = %d
                      LIMIT 0,%d",$this->user->id,$body['number']),ARRAY_A);
            break;
            case 'quiz':
                $data= $wpdb->get_results($wpdb->prepare("
                      SELECT DISTINCT ID,post_title
                      FROM {$wpdb->posts} AS posts
                      LEFT JOIN {$wpdb->postmeta} AS rel ON posts.ID = rel.post_id
                      WHERE   posts.post_type   = 'quiz'
                      AND   posts.post_status   = 'publish'
                      AND   rel.meta_key   = %d
                      LIMIT 0,%d",$this->user->id,$body['number']),ARRAY_A);
            break;
            case 'assignment':
                $data= $wpdb->get_results($wpdb->prepare("
                      SELECT DISTINCT ID,post_title
                      FROM {$wpdb->posts} AS posts
                      LEFT JOIN {$wpdb->postmeta} AS rel ON posts.ID = rel.post_id
                      WHERE   posts.post_type   = 'wplms-assignment'
                      AND   posts.post_status   = 'publish'
                      AND   rel.meta_key   = %d
                      LIMIT 0,%d",$this->user->id,$body['number']),ARRAY_A);
            break;
            case 'unit':
                $data= $wpdb->get_results($wpdb->prepare("
                      SELECT DISTINCT ID,post_title
                      FROM {$wpdb->posts} AS posts
                      LEFT JOIN {$wpdb->usermeta} AS rel ON posts.ID = rel.meta_key
                      WHERE   posts.post_type   = 'unit'
                      AND   posts.post_status   = 'publish'
                      AND   rel.user_id   = %d
                      LIMIT 0,%d",$this->user->id,$body['number']),ARRAY_A);
            break;
            case 'download':
                $data = $wpdb->get_results($wpdb->prepare("
                      SELECT DISTINCT ID,post_title
                      FROM {$wpdb->posts} AS wpposts
                      WHERE   wpposts.post_type   = 'attachment'
                      AND wpposts.post_parent IN (
                          SELECT ID
                          FROM {$wpdb->posts} AS posts
                          LEFT JOIN {$wpdb->usermeta} AS rel ON posts.ID = rel.meta_key
                          WHERE   posts.post_type   = 'unit'
                          AND   posts.post_status   = 'publish'
                          AND   rel.user_id   = %d
                      )
                      LIMIT 0,%d",$this->user->id,$body['number']),ARRAY_A);
            break;
            case 'finished':
                $data= $wpdb->get_results($wpdb->prepare("
                      SELECT DISTINCT ID,post_title
                      FROM {$wpdb->posts} AS posts
                      LEFT JOIN {$wpdb->postmeta} AS rel ON posts.ID = rel.post_id
                      WHERE   posts.post_type   = 'course'
                      AND   posts.post_status   = 'publish'
                      AND   rel.meta_key   = %d
                      AND   rel.meta_value > 2
                      LIMIT 0,%d",$this->user->id,$body['number']),ARRAY_A);
            break;
        }
        if(!empty($data)){
            foreach($data as $key=>$item){
                $data[$key]['link']=get_permalink($item['ID']);
            }
        }
        return new WP_REST_Response(array('status'=>1,'data'=>$data),200);
    }

    function get_instructing_module($request) {
        $body = json_decode($request->get_body(), true);
        if(empty($body['type'])){
            return new WP_REST_Response(array('status'=>0,'message'=>__('No type property in request','wplms')),200);
        }
        global $wpdb;
        if(function_exists('post_type_exists') && post_type_exists($body['type'])){
            $posttype_obj=get_post_type_object($post_type);
            $args = apply_filters('wplms_dashboard_instrcuting_modules_args',array(
                  'post_type' => $body['type'],
                  'post_status' => 'publish',
                  'author' => $this->user->id,
                  'posts_per_page' => $body['number'],
                ));
            $all_posts= new Wp_Query($args);
            if($all_posts->have_posts()){
                $data = array();
              while($all_posts->have_posts()){
                  $all_posts->the_post();
                  global $post;
                  $item = array('ID' => $post->ID, 'link' => get_permalink($post->ID), 'title' => $post->post_title);
                  array_push($data, $item);
              }
            }

            wp_reset_postdata();
        }
        if (empty($data)) {
            $data = [];
        }
        return new WP_REST_Response(array('status'=>1,'data'=>$data),200);
    }

    function contact_user($request) {
        $body = json_decode($request->get_body(), true);
    }

    function get_student_stats($request) {
        $body = json_decode($request->get_body(), true);
        if(empty($body['type'])){
            return new WP_REST_Response(array('status'=>0,'message'=>__('No type property in request','wplms')),200);
        }

        $r = rand(1,999);
        global $wpdb;
        switch($body['type']){
            case 'course':
                $course = array();
                $user_courses = array();
                $marks=$wpdb->get_results($wpdb->prepare("
                        SELECT rel.post_id as id,rel.meta_value as val
                        FROM {$wpdb->posts} AS posts
                        LEFT JOIN {$wpdb->postmeta} AS rel ON posts.ID = rel.post_id
                        WHERE   posts.post_type   = 'course'
                        AND   posts.post_status   = 'publish'
                        AND   rel.meta_key   = %d
                        AND   rel.meta_value > 2
                        ",$this->user->id));
                // print_r($marks);
                if(is_array($marks)){
                    
                    foreach($marks as $k=>$mark){  
                        $course[] = $mark->id;
                        $user_courses[$mark->id] = array('id'=>$mark->id,'label'=>($k+1).' '.get_the_title($mark->id), 'marks'=>$mark->val);
                    }
                    if(is_array($course)){
                        $user_course=implode(',',$course);
                        $average_marks=$wpdb->get_results("
                                        SELECT c.post_id as id,c.meta_value as average
                                        FROM {$wpdb->postmeta} AS c
                                        WHERE c.post_id IN ($user_course)
                                        AND c.meta_key   = 'average'
                                        ");
                        
                        foreach($average_marks as $average_mark){
                          if(isset($average_mark->average))
                            $user_courses[$average_mark->id]["average"]=$average_mark->average;
                          else
                            $user_courses[$average_mark->id]["average"]=0;
                        }
                    }
                    $result = array();
                    if(!empty($user_courses)){
                        foreach($user_courses as $course){
                            $result[]=$course;
                        }
                        return new WP_REST_Response(array('status'=>1,'result'=>$result),200);
                    }
                }
                return new WP_REST_Response(array('status'=>0,'message'=>_x('No Data Available','','wplms')),200);
            break;
            case 'quiz':
                $marks=$wpdb->get_results($wpdb->prepare("
                        SELECT rel.post_id as id,rel.meta_value as val
                        FROM {$wpdb->posts} AS posts
                        LEFT JOIN {$wpdb->postmeta} AS rel ON posts.ID = rel.post_id
                        WHERE   posts.post_type   = 'quiz'
                        AND   posts.post_status   = 'publish'
                        AND   rel.meta_key   = %d
                        AND   rel.meta_value >= 0
                        ",$this->user->id));
                
                // print_r($marks);
                if(is_array($marks)){
                    foreach($marks as $k=>$mark){
                        $quiz[] = $mark->id;
                        $user_quizes[$mark->id]=array("id"=>$mark->id,"label"=>($k+1)." ".get_the_title($mark->id), "marks"=>$mark->val);
                    }
                    if(is_array($quiz)){
                        $user_quiz=implode(',',$quiz);
                        $average_marks=$wpdb->get_results("
                                        SELECT c.post_id as id,c.meta_value as average
                                        FROM {$wpdb->postmeta} AS c
                                        WHERE c.post_id IN ($user_quiz)
                                        AND c.meta_key   = 'average'
                                        ");
                        
                        foreach($average_marks as $average_mark){
                            if(isset($average_mark->average))
                              $user_quizes[$average_mark->id]["average"]=$average_mark->average;
                            else
                              $user_quizes[$average_mark->id]["average"]=0;
                        }
                    }
                    $result = array();
                    if(!empty($user_quizes)){
                        foreach($user_quizes as $quiz){
                            $result[]=$quiz;
                        }
                    }
                    return new WP_REST_Response(array('status'=>1,'result'=>$result),200);
                }
                return new WP_REST_Response(array('status'=>0,'message'=>_x('No Data Available','','wplms')),200);
            break;
            case 'assignments':
                $marks=$wpdb->get_results($wpdb->prepare("
                        SELECT rel.post_id as id,rel.meta_value as val
                        FROM {$wpdb->posts} AS posts
                        LEFT JOIN {$wpdb->postmeta} AS rel ON posts.ID = rel.post_id
                        WHERE   posts.post_type   = 'wplms-assignment'
                        AND   posts.post_status   = 'publish'
                        AND   rel.meta_key   = %d
                        AND   rel.meta_value >= 0
                        ",$user_id));
                // print_r($marks);
                      if(is_array($marks)){
                        foreach($marks as $k=>$mark){
                          $assignment[] = $mark->id;
                          $user_assignments[$mark->id]=array('id'=>$k,'label'=>($k+1).' '.get_the_title($mark->id), 'marks'=>$mark->val);
                        }
                        if(is_array($assignment)){
                          $user_assignment=implode(',',$assignment);
                          $average_marks=$wpdb->get_results("
                                        SELECT c.post_id as id,c.meta_value as average
                                        FROM {$wpdb->postmeta} AS c
                                        WHERE c.post_id IN ($user_assignment)
                                        AND c.meta_key   = 'average'
                                        ");
                          if(is_array($average_marks))
                          foreach($average_marks as $average_mark){
                              if(isset($average_mark->average))
                                $user_assignments[$average_mark->id]['average']=$average_mark->average;
                              else
                                $user_assignments[$average_mark->id]['average']=0;
                          }
                        }
                        $result = array();
                        if(!empty($user_assignments)){
                            foreach($user_assignments as $assignment){
                                $result[]=$assignment;
                            }
                        }
                        return new WP_REST_Response(array('status'=>1,'result'=>$result),200);
                      }
                      return new WP_REST_Response(array('status'=>0,'message'=>_x('No Data Available','','wplms')),200);
            break;
        }
    }

    function get_todo_task($request) {
        $body = json_decode($request->get_body(), true);
        if(empty($body['type'])){
            return new WP_REST_Response(array('status'=>0,'message'=>__('No type property in request','wplms')),200);
        } else if ($body['type'] == 'get_todo') {
            $result = get_user_meta($this->user->id,'tasks',true);
        } else if ($body['type'] == 'update_todo') {
            $todo_list = array();
            foreach ($body['todo_data'] as $task) {
                $task_obj = new stdClass();
                $task_obj->status = $task['status'];
                $task_obj->text = $task['content'];
                $task_obj->date = $task['date'];
                array_push($todo_list, $task_obj);
            }
            $result = update_user_meta($this->user->id,'tasks',$todo_list);
        }
        return new WP_REST_Response(array('status'=>1,'result'=>$result),200);
    }

    function get_activity($request){
        $body = json_decode($request->get_body(), true);
        $types = $body['type'];
        $num = $body['num'];
        $user_id = $this->user->id;

        $data = [];

        if(!empty($types) && !empty($user_id) && !empty($num)){
            foreach ($types as $key => $type) {
                $num = $num<=20 ? $num : 20;
                switch ($type) {
                    case 'friends':
                        // online friends
                        $searchArgs  = array(
                            'type'     => 'online',
                            'page'     => 1,
                            'per_page' => $num,
                            'user_id'  => $user_id
                        );
                        $r=array();
                        if ( bp_has_members($searchArgs)){
                            while ( bp_members() ) : bp_the_member();
                                $r[]=array(
                                'avatar'=>bp_get_member_avatar(),
                                'name'  => bp_get_member_name(),
                                'last_active' => bp_get_member_last_active()
                            );
                            endwhile;
                        }
                        if(!empty($r)){
                            $data['friends'] = array('status' => 1, 'message'=>__('Online friends','wplms'),'data' => $r);
                        }else{
                            $data['friends'] = array('status' => 0,'message'=>__('No online friends','wplms') );
                        }
                    break;
                    case 'messages':
                    
                       // recent message
                        $message_args= array(
                            'user_id' => $user_id,
                            'box' => 'inbox',
                            'type' => 'unread',
                            'max' => $num
                        );
                        $r = array();
                        if(bp_has_message_threads($message_args)){
                            
                            while ( bp_message_threads() ) : bp_message_thread();
                                $r[] = array(
                                    'avatar' => bp_get_message_thread_avatar(),
                                    'link' => bp_get_message_thread_view_link(),
                                    'subject' => bp_get_message_thread_subject(),
                                    'form' => bp_get_message_thread_from()
                                );
                            endwhile;
                        }
                        if(!empty($r)){
                            $data['messages'] = array('status' => 1,'message'=>__('Message found','wplms'),'data' => $r);
                        }else{
                            $data['messages'] = array('status' => 0,'message'=>__('No message found','wplms'));
                        }
                    break;
                    case 'activity':
                        // recent activity
                        global $wpdb,$bp;
                        $activities=apply_filters('wplms_dashboard_activity', $wpdb->get_results($wpdb->prepare("
                            SELECT *
                            FROM {$bp->activity->table_name} AS activity
                            WHERE   activity.user_id IN (%d)
                            AND     (activity.action != '' OR activity.action IS NOT NULL)
                            ORDER BY activity.date_recorded DESC
                            LIMIT 0,$num
                        ",$user_id)));
                        if(!empty($activities) && is_array($activities)){
                            $r = array();
                            foreach($activities as $activity){
                                $r[] = array(
                                    'action' => $activity->action,
                                    'component' => $activity->component,
                                    'type' => $activity->date_recorded,
                                    'time' => tofriendlytime(time()-strtotime($activity->date_recorded))
                                );
                            }
                            $data['activity'] = array('status' => 1,'message'=>__('Activities found','wplms'),'data' => $r);   
                        }else{
                            $data['activity'] = array('status' => 0,'message'=>__('No Activity found','wplms'));
                        } 
                    break;
                    default:
                        $data = array('status' => 0,'message'=>__('Type not matched','wplms'));
                    break;
                }
            } 
        }else{
            $data = array('status' => 0,'message'=>__('Data missing!','wplms') );
        }
        return new WP_REST_Response(apply_filters('wplms_dashboard_get_activity',$data,$request),200);
    }


    function get_contact_user_check($request){
        $body = json_decode($request->get_body(), true);
        $user_id = $this->user->id;
        if(!empty($user_id)){
            $disable_instructor_display = true;
            if(class_exists('WPLMS_tips')){
                $tips = WPLMS_tips::init();
                if(empty($tips->settings['disable_instructor_display'])){$disable_instructor_display =false;}
            }
            $user_can_edit_post = true; // check here
            $friends_active = bp_is_active('friends');
            $data = array(
                'status' => true,
                'data'=> array(
                    'disable_instructor_display' => $disable_instructor_display,
                    'user_can_edit_post' => $user_can_edit_post,
                    'friends_active' => $friends_active
                )
            );
        }else{
            $data = array('status' => 0,'message'=>__('Data missing!','wplms') );
        }
        return new WP_REST_Response(apply_filters('get_contact_user_check',$data,$request),200);
    }

    function get_contact_user($request){
        $body = json_decode($request->get_body(), true);
        $user_id = $this->user->id;
        $type = $body['type']; 
        if(!empty($user_id) && !empty($type)){
            switch ($type) {
                case 'get_friends':
                    $data = array('status' => 1,'data'=>$this->get_friends($user_id));
                break;
                case 'get_course_students':
                    $data = array('status' => 1,'data'=>$this->get_course_students($user_id));
                break;
                case 'get_instructors':
                    $data = array('status' => 1,'data'=>$this->get_instructors());
                break;
                case 'get_admins':
                    $data = array('status' => 1,'data'=>$this->get_admins());
                break;  
                default:
                    $data = array('status' => 0,'message'=>__('Type not matched','wplms') );
                break;
            }
        }else{
            $data = array('status' => 0,'message'=>__('Data missing!','wplms') );
        }
        return new WP_REST_Response(apply_filters('get_contact_user',$data,$request),200);
    }

    function get_course_students($user_id){
        global $wpdb;
        $query = apply_filters('wplms_dashboard_courses_instructors',$wpdb->prepare("
                SELECT posts.ID as course_id
                FROM {$wpdb->posts} AS posts
                WHERE   posts.post_type   = 'course'
                AND   posts.post_author   = %d
              ",$user_id));
  
        $instructor_courses=$wpdb->get_results($query,ARRAY_A);
        $course_ids=array();
        if(isset($instructor_courses) && count($instructor_courses)){
            foreach($instructor_courses as $key => $value){
                $course_ids[]=$value['course_id'];
            }
        }
        $course_ids_string = implode(',',$course_ids);
        $course_students = $wpdb->get_results("
            SELECT user_id
            FROM {$wpdb->usermeta} as rel
            WHERE  rel.meta_key  IN ($course_ids_string)
            AND   rel.meta_value >= 0
        ",ARRAY_A);
        $unique=array();
        $mycourse_students = array();
        if ( isset($course_students) && is_array( $course_students) ) {
            foreach ( $course_students as $user ) {
              if(!in_array($this->user->id,$unique)){
                $mycourse_students[]=array(
                  'id' => $user['user_id'],
                  'pic' => bp_core_fetch_avatar( array( 'item_id' => $user['user_id'],'type'=>'thumb')),
                  'name' => bp_core_get_user_displayname($user['user_id']),
                  );
                $unique[]=$user['user_id'];
              }
            }
        }
        return $mycourse_students;
    }
    function get_admins(){
        $user_query = new WP_User_Query( array( 'role' => 'administrator' ) );
        $admins =array();
        if ( isset($user_query) && !empty( $user_query->results ) ) {
            foreach ( $user_query->results as $user ) {
                $admins[]=array(
                  'id' => $user->ID,
                  'pic' => bp_core_fetch_avatar( array( 'item_id' => $user->ID,'type'=>'thumb')),
                  'name' => bp_core_get_user_displayname($user->ID),
                  );
            }  
        }
        return $admins;
    }

    function get_instructors(){
        $user_query = new WP_User_Query( array( 'role' => 'Instructor' ) );
        $instructors =array();
        if ( isset($user_query) && !empty( $user_query->results ) ) {
            foreach ( $user_query->results as $user ) {
                $instructors[]=array(
                  'id' => $user->ID,
                  'pic' => bp_core_fetch_avatar( array( 'item_id' => $user->ID,'type'=>'thumb')),
                  'name' => bp_core_get_user_displayname($user->ID),
                  );
            }
        }
        return $instructors;
    }

    function get_friends($user_id){
        if(function_exists('friends_get_friend_user_ids')){
            $friends = friends_get_friend_user_ids( $user_id );
            $new_friends = array();
            foreach($friends as $key=>$friend){
                $new_friends[$key] = array(
                    'id' => $friend,
                    'pic' => bp_core_fetch_avatar ( array( 'item_id' => $friend, 'type' => 'thumb' ) ),
                    'name' => bp_core_get_user_displayname($friend),
                );
            }
        }
        return $new_friends;
    }

    function dash_contact_message($request){
        $body = json_decode($request->get_body(), true);
        $user_id =$this->user->id;
        $subject = $body['subject']; 
        $message = $body['message'];
        $member_ids = $body['member_ids'];
        if(!empty($user_id) && !empty($subject) && !empty($message) && !empty($member_ids)){
            if(bp_is_active('messages')){
                $sent_users = array();
                foreach($member_ids as $member){
                    if( messages_new_message( array('sender_id' => $user_id, 'subject' => $subject, 'content' => $message,   'recipients' => $member ) ) ){
                        $sent_users[] = $member;
                    }
                }
                $count = count($sent_users);
                $data = array(
                    'status' => 0,
                    'message'=>__('Message sent to ','wplms').$count.__('member','wplms'),
                    'data' => $sent_users
                );
            }else{
                $data = array('status' => 0,'message'=>__('Message not active','wplms') );
            }
        }else{
            $data = array('status' => 0,'message'=>__('Data missing!','wplms') );
        }
        return new WP_REST_Response(apply_filters('dash_contact_message',$data,$request),200);
       
    }

    function get_instrutor_students($request){
        // issue in api
        $body = json_decode($request->get_body(), true);
        $user_id = (int)$this->user->id;
        $per_page = (int)$body['per_page'];
        $per_page = 20;
        $page = (int)$body['page'];
        $data = array('status' => 0);
        if(!empty($user_id) && !empty($per_page) && !empty($page)){
            if(class_exists('CoAuthors_Plus')){
                $nickname = get_user_meta($user_id,'nickname',true);
                $args = apply_filters('wplms_instructor_courses_args',array( 
                    'post_type'=>'course',
                    'posts_per_page' => -1,
                    'author_name' => $nickname,
                    'fields' => 'ids',
                    ), $user_id);
            }else{
                $args = apply_filters('wplms_instructor_courses_args', array( 
                    'post_type'=>'course',
                    'posts_per_page' => -1,
                    'author'=>$user_id,
                    'fields' => 'ids'
                ),$user_id);
            }
            $courses = new WP_Query($args);
            if(!empty($courses->posts)){
                $inst_courses = implode(',',$courses->posts);
                global $wpdb,$bp;
                $per_page = apply_filters('get_instrutor_students_limit',$per_page);
                $offset = ($page-1)*$per_page;
                $query = $wpdb->prepare("
                    SELECT user_id,item_id
                    FROM {$bp->activity->table_name}
                    WHERE item_id IN ($inst_courses) AND type = 'subscribe_course' GROUP BY user_id,item_id  LIMIT %d OFFSET %d",
                    $per_page,$offset);
                $query_students = $wpdb->get_results($query,ARRAY_A);
                if(!empty($query_students)){
                    $students=array();
                    $course = array();
                    foreach ($query_students as $student) {
                        $uid = (int)$student['user_id'];  $cid = (int)$student['item_id'];
                        $coures[$uid][] = array('id' => $cid,'title' => get_the_title($cid));
                    }
                    $temp = array();
                    foreach ($query_students as $student) {
                        $uid = (int)$student['user_id']; 
                        if(!in_array($uid,$temp)){
                            $students[] = array(
                                'id' => $uid,
                                'name' => bp_core_get_user_displayname($uid),
                                'courses' =>  $coures[$uid]
                            );
                            $temp[] = $uid;
                        }
                    }
                    $data = array(
                        'status' => 1,
                        'message'=>__('Students found','wplms'),
                        'data' => $students
                    );
                }else{
                    $data = array('status' => 0,'message'=>__('No students found','wplms') );
                }  
            }
        }else{
            $data = array('status' => 0,'message'=>__('Data missing!','wplms') );
        }
        return new WP_REST_Response(apply_filters('get_instrutor_students',$data,$request),200);
    }
  

}
WPLMS_Dashboard_Api::init();