<?php
/**
 * API\
 *
 * @class       Vibe_Projects_API
 * @author      VibeThemes
 * @category    Admin
 * @package     vibebp
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class Vibe_HelpDesk_API{
	public static $instance;
	public static function init(){
        if ( is_null( self::$instance ) )
            self::$instance = new Vibe_HelpDesk_API();
        return self::$instance;
    }

	private function __construct(){
        add_action('rest_api_init',array($this,'register_routes'));
        $this->namespace= !empty(VIBE_HELPDESK_API_NAMESPACE)?VIBE_HELPDESK_API_NAMESPACE:'vibehd/v1';
        $this->type = !empty(Vibe_BP_API_FORUMS_TYPE)?Vibe_BP_API_FORUMS_TYPE:'bbp';
	}

    public function register_routes() {
            
        register_rest_route( $this->namespace, '/'. $this->type .'/forums', array(
            'methods'                   =>   'POST',
            'callback'                  =>  array( $this, 'get_forums' ),
            'permission_callback' => array( $this, 'get_user_permissions_check' ),
        ) );

        register_rest_route( $this->namespace, '/'. $this->type .'/forums/subscribe', array(
            'methods'                   =>   'POST',
            'callback'                  =>  array( $this, 'subscribe_forums' ),
            'permission_callback' => array( $this, 'get_user_permissions_check' ),
        ) );

        register_rest_route( $this->namespace, '/'. $this->type .'/topics', array(
            'methods'                   =>   'POST',
            'callback'                  =>  array( $this, 'get_topics' ),
            'permission_callback' => array( $this, 'get_user_permissions_check' ),
        ) );
        register_rest_route( $this->namespace, '/'. $this->type .'/topics/subscribe', array(
            'methods'                   =>   'POST',
            'callback'                  =>  array( $this, 'subscribe_topics' ),
            'permission_callback' => array( $this, 'get_user_permissions_check' ),
        ) );
        register_rest_route( $this->namespace, '/'. $this->type .'/topic', array(
            'methods'                   =>   'POST',
            'callback'                  =>  array( $this, 'get_topic' ),
            'permission_callback' => array( $this, 'get_user_permissions_check' ),
        ) );

        register_rest_route( $this->namespace, '/'. $this->type .'/topics/favorite', array(
            'methods'                   =>   'POST',
            'callback'                  =>  array( $this, 'set_topic_my_favourite' ),
            'permission_callback' => array( $this, 'get_user_permissions_check' ),
        ) );

        register_rest_route( $this->namespace, '/'. $this->type .'/topics/unfavorite', array(
            'methods'                   =>   'POST',
            'callback'                  =>  array( $this, 'unset_topic_my_favourite' ),
            'permission_callback' => array( $this, 'get_user_permissions_check' ),
        ) );

        register_rest_route( $this->namespace, '/'. $this->type .'/topics/delete', array(
            'methods'                   =>   'POST',
            'callback'                  =>  array( $this, 'delete_topic' ),
            'permission_callback' => array( $this, 'get_user_create_permissions_check' ),
        ) );

        register_rest_route( $this->namespace, '/'. $this->type .'/engagements', array(
            'methods'                   =>   'POST',
            'callback'                  =>  array( $this, 'get_engagements' ),
            'permission_callback' => array( $this, 'get_user_permissions_check' ),
        ) );


        register_rest_route( $this->namespace, '/'. $this->type .'/replies', array(
            'methods'                   =>   'POST',
            'callback'                  =>  array( $this, 'get_replies' ),
            'permission_callback' => array( $this, 'get_user_permissions_check' ),
        ) );

        register_rest_route( $this->namespace, '/'. $this->type .'/forums/create', array(
            'methods'                   =>   'POST',
            'callback'                  =>  array( $this, 'create_forum' ),
            'permission_callback' => array( $this, 'get_user_create_permissions_check' ),
        ) );

        register_rest_route( $this->namespace, '/'. $this->type .'/topics/create', array(
            'methods'                   =>   'POST',
            'callback'                  =>  array( $this, 'create_topic' ),
            'permission_callback' => array( $this, 'get_user_permissions_check' ),
        ) );

        register_rest_route( $this->namespace, '/'. $this->type .'/replies/create', array(
            'methods'                   =>   'POST',
            'callback'                  =>  array( $this, 'create_reply' ),
            'permission_callback' => array( $this, 'get_user_create_permissions_check_reply' ),
        ) );

        register_rest_route( $this->namespace, '/'. $this->type .'/replies/update', array(
            'methods'                   =>   'POST',
            'callback'                  =>  array( $this, 'update_reply' ),
            'permission_callback' => array( $this, 'get_user_create_permissions_check_reply' ),
        ) );

        register_rest_route( $this->namespace, '/'. $this->type .'/replies/delete', array(
            'methods'                   =>   'POST',
            'callback'                  =>  array( $this, 'delete_reply' ),
            'permission_callback' => array( $this, 'get_user_permissions_check' ),
        ) );

        register_rest_route( $this->namespace, '/'. $this->type .'/sla_open_topics', array(
            'methods'                   =>   'POST',
            'callback'                  =>  array( $this, 'sla_open_topics' ),
            'permission_callback' => array( $this, 'get_user_permissions_check' ),
        ) );

        register_rest_route( $this->namespace, '/'. $this->type .'/topics/topic_labels', array(
            'methods'                   =>   'POST',
            'callback'                  =>  array( $this, 'topic_labels' ),
            'permission_callback' => array( $this, 'get_user_permissions_check' ),
        ) );

        register_rest_route( $this->namespace, '/'. $this->type .'/topics/assign_topic_label', array(
            'methods'                   =>   'POST',
            'callback'                  =>  array( $this, 'assign_topic_label' ),
            'permission_callback' => array( $this, 'get_user_permissions_check' ),
        ) );
            
        register_rest_route( $this->namespace, '/'. $this->type .'/topics/search_topic_assignable_user', array(
            'methods'                   =>   'POST',
            'callback'                  =>  array( $this, 'search_topic_assignable_user' ),
            'permission_callback' => array( $this, 'get_user_permissions_check' ),
        ) ); 

        register_rest_route( $this->namespace, '/'. $this->type .'/topics/assign_topic', array(
            'methods'                   =>   'POST',
            'callback'                  =>  array( $this, 'assign_topic' ),
            'permission_callback' => array( $this, 'get_user_permissions_check' ),
        ) );
        
        register_rest_route( $this->namespace, '/'. $this->type .'/replies/save_canned', array(
            'methods'                   =>   'POST',
            'callback'                  =>  array( $this, 'save_canned' ),
            'permission_callback' => array( $this, 'get_user_permissions_check' ),
        ) );

        register_rest_route( $this->namespace, '/'. $this->type .'/replies/search_canned', array(
            'methods'                   =>   'POST',
            'callback'                  =>  array( $this, 'search_canned' ),
            'permission_callback' => array( $this, 'get_user_permissions_check' ),
        ) );
        
    }

    function get_user_create_permissions_check($request){
        $body = json_decode(stripslashes($_POST['body']),true);
        if(!empty($body['token'])){
            global $wpdb;
            $this->user = apply_filters('vibebp_api_get_user_from_token','',$body['token']);
            if(!empty($this->user)){
                if(user_can($this->user->id,'edit_posts')){
                    return true;    
                }
            }
        }
        return false;
    }

    function get_user_create_permissions_check_reply($request){
        $body = json_decode(stripslashes($_POST['body']),true);
        if(!empty($body['token'])){
            global $wpdb;
            $this->user = apply_filters('vibebp_api_get_user_from_token','',$body['token']);
            if(!empty($this->user) && !empty($this->user->id)){
               return true;
            }
        }
        return false;
    }


    function get_user_permissions_check($request){
        $body = json_decode($request->get_body(),true);
        if (empty($body['token'])){
            $client_id = $request->get_param('client_id');
            if(function_exists('vibebp_get_setting') && $client_id == vibebp_get_setting('client_id')){
                return true;
            }
        }else{
            $token = $body['token'];
        }
        if(!empty($body['token'])){
            global $wpdb;
            $this->user = apply_filters('vibebp_api_get_user_from_token','',$body['token']);
            if(!empty($this->user)){
                return true;
            }
        }
        return false;
    }

    function user_upload_permissions_check($request){
        $body = json_decode(stripslashes($_POST['body']),true);
        
        if(!empty($body['token'])){
            global $wpdb;
            $this->user = apply_filters('vibebp_api_get_user_from_token','',$body['token']);
            if(!empty($this->user)){
                return true;
            }
        }
        return false;
    }

    function get_forums($request){
        $args = json_decode($request->get_body(),true);
        unset($args['token']);
        if(empty($args['s'])){
            unset($args['s']);
        }
        $user_id = $this->user->id;
        $forums = array();
        $return = array(
            'status'=>0,
            'message'=>_x('Forums not found!','Forums not found!','vibe-helpdesk')
        );
        $args['post_type'] = bbp_get_forum_post_type();
        $this->subscriptions = bbp_get_user_subscribed_forum_ids( $user_id );
        if(!empty($args['type'])){
            switch($args['type']){
                case 'subscribed':
                    $args['post__in']= $subscriptions;
                break;
            }   
        }
        
        $args = apply_filters('vibe_helpdesk_forums_args',$args,$this->user);
        
        if(function_exists('bbp_has_forums')){
            if ( bbp_has_forums($args) ) :
                while ( bbp_forums() ) : bbp_the_forum();
                    global $post;
                    $is_public = bbp_is_forum_public( $post->ID );
                    if($is_public){
                        $forums[] = array(
                            'id'=>$post->ID,
                            'title'=>$post->post_title,
                            'description'=>$post->post_content,
                            'private'=>bbp_is_forum_private( $post->ID ),
                            'subscribed'=>in_array($post->ID,$this->subscriptions)?true:false,
                            'access'=> apply_filters('vibebp_forum_access',true,$post->ID,$user_id),
                            'topic_count'=>bbp_get_forum_topic_count( $post->ID, false, true ),
                            'forums_count'=>bbp_get_forum_subforum_count( $post->ID, true )
                        );
                    }
                endwhile;
            endif;
            // set message from topics
            if(!empty($forums)){
                $return=array(
                    'status' => 1,
                    'data' => $forums
                );
            }else{
                $return=array(
                    'status' => 1,
                    'data'=>[],
                    'message' => __('No forums found.','vibe-helpdesk')
                );
            }
        }
        $return = apply_filters('VibeBbp_get_forums_api',$return,$args);
        return new WP_REST_Response($return, 200); 
    }

    function subscribe_forums($request){
        $body = json_decode($request->get_body(),true);
        $return = array(
            'status'=>0,
            'message'=>__('Unable to chage forum subscription status.','vibe-helpdesk')
        );
        if($body['subscribe']){
            if(bbp_add_user_subscription( $this->user->id, $body['forum_id'])){
                $return = array('status'=>1);
            }else{
                $return['message'] = __('Unable to add subscription','vibe-helpdesk');
            }
        }else{
            if(bbp_remove_user_subscription( $this->user->id, $body['forum_id'])){
                $return = array('status'=>1);
            }else{
                $return['message'] = __('Unable to remove subscription','vibe-helpdesk');
            }
        }
        return new WP_REST_Response($return, 200); 
    }

    function get_topics($request){
        $args = json_decode($request->get_body(),true);
        $topics = array();
        $return = array(
            'status'=>0,
            'message'=>_x('Topics not found!','Topics not found!','vibe-helpdesk')
        );
        $favorites = bbp_get_user_favorites_topic_ids( $this->user->id );
        if(empty($favorites)){$favorites=array(0);} //force zero results
        $subscriptions = bbp_get_user_subscribed_topic_ids( $this->user->id );
        if(empty($subscriptions)){$subscriptions=array(0);}//force zero results
        switch($args['type']){
            case 'mine':
                $args['author'] = $this->user->id;
            break;
            case 'favorites':
                $args['post__in'] = $favorites;
            break;
            case 'subscriptions':
                $args['post__in'] = $subscriptions;
            break;
            case 'assigned':
                $args['meta_query'] = array(
                    array(
                        'key'=>'assigned_agent',
                        'value'=>$this->user->id,
                        'compare'=>'='
                    )
                );
            break;
            case 'unassigned':
                $args['meta_query'] = array(
                    array(
                        'key'=>'assigned_agent',
                        'compare'=>'NOT EXISTS'
                    )
                ); 
            break;
            case 'recent_assigned':
                $args['meta_query'] = array(
                    array(
                        'key'=>'assigned_agent',
                        'compare'=>'EXISTS'
                    )
                );
            break;
        }
        // Labels based meta search
        if(!empty($args['label'])){
            $args['meta_query'] = array(
                array(
                    'key'=>'assigned_topic_label',
                    'value'=>$args['label'],
                    'compare'=>'='
                )
            ); 
        }
        $args = apply_filters('vibe_helpdesk_topic_args',$args,$this->user);
        if(function_exists('bbp_has_topics')){
            //fetch all topic by parent
            $index = -1;
            if ( bbp_has_topics($args) ) :
                while ( bbp_topics() ) : bbp_the_topic();
                    global $post;
                    $topic_id = bbp_get_topic_id();
                    $index++;
                    $topics[] = array(
                        'id'=> $topic_id,
                        'post_title'=>bbp_get_topic_title(),
                        'permalink'=>bbp_get_topic_permalink(),
                        'last_update'=>bbp_get_topic_freshness_link($topic_id),
                        'reply_count'=>bbp_get_topic_reply_count(),
                        'author'=> $post->post_author,
                        'post_content'=>bbp_get_topic_content($topic_id),
                        'forum_id'=>$post->post_parent,
                        'favorite'=>in_Array($topic_id,$favorites)?true:false,
                        'subscribed'=>in_Array($topic_id,$subscriptions)?true:false
                    );
                    switch ($args['type']) {
                        case 'assigned':
                                $topics[$index]['assigned_topic_labels'] = $this->get_assigned_topic_labels($topic_id);    
                            break; 
                        default:
                            break;
                    }
                endwhile;
            endif;

            // set message from topics
            if(!empty($topics)){
                $bbp = bbpress();
                $return = array(
                    'status'=>1,
                    'topics'=>$topics,
                    'total'=>$bbp->topic_query->found_posts
                );
            }
        }
        $return = apply_filters('VibeBbp_get_topics_api',$return,$args);
        return new WP_REST_Response($return, 200); 
    }

    function get_assigned_topic_labels($topic_id,$labels=array()){
        $rtn = [];
        if(empty($labels)){
            $labels = get_option(VIBE_BP_SETTINGS)['forums']['bbp_labels'];
        }
        if(!empty($labels) && is_array($labels)){
            $labels_keys = get_post_meta($topic_id,'assigned_topic_label');
            if(!empty($labels_keys) && is_array($labels_keys)){
                foreach ($labels_keys as $key1 => $value1) {
                    foreach ($labels as $key2 => $value2) {
                        if($value2->label == $value1){
                            $rtn[] = $value2;
                            break;
                        }
                    }
                }
            }
        }
        return $rtn; 
    }

    function subscribe_topics($request){
        $body = json_decode($request->get_body(),true);
        $return = array(
            'status'=>0,
            'message'=>__('Unable to chage topic subscription status.','vibe-helpdesk')
        );

        if($body['subscribe']){
            if(bbp_add_user_subscription( $this->user->id, $body['topic_id'])){
                $return = array('status'=>1);
            }else{
                $return['message'] = __('Unable to add subscription','vibe-helpdesk');
            }
        }else{
            if(bbp_remove_user_subscription( $this->user->id, $body['topic_id'])){
                $return = array('status'=>1);
            }else{
                $return['message'] = __('Unable to remove subscription','vibe-helpdesk');
            }
        }
        return new WP_REST_Response($return, 200); 
    }

    function get_topic($request){
        $args = json_decode($request->get_body(),true);
        $topic_id = intval($args['topic_id']);
        $return = array(
            'status'=>0,
            'message'=>_x('Topics not found!','Topics not found!','vibe-helpdesk')
        );
        if($topic_id){
            $topic = $this->get_topic_by_id($topic_id);
            $return = array(
                'status'=>1,
                'topic'=>$topic
            );
        }
        return new WP_REST_Response($return, 200); 
    }

    function get_topic_by_id($topic_id){
        $favorites = bbp_get_user_favorites_topic_ids( $this->user->id );
        $subscriptions = bbp_get_user_subscribed_topic_ids( $this->user->id );
        $topic = get_post( $topic_id,ARRAY_A);
        global $post;
        $arr  = array(
            'id' => $topic_id,
            'post_title'=>$topic['post_title'],
            'last_update' => mysql2date( 'D, d M Y H:i:s +0000', get_post_time( 'Y-m-d H:i:s', true ,$topic_id), false ),
            'author' => $topic['post_author'],
            'post_content'=>$topic['post_content'],
            'reply_count' => (int) get_post_meta( $topic_id, '_bbp_reply_count', true ),
            'forum_id' => $topic['post_parent'],
            'favorite' => in_array($topic_id,$favorites)?true:false,
            'subscribed' => in_array($topic_id,$subscriptions)?true:false
        );
        return $arr;
    }
    
    function get_replies($request){
        $args = json_decode($request->get_body(),true);
        $replies = array();
        $return = array(
            'status'=>0,
            'message'=>_x('Replies not found!','Replies not found!','vibe-helpdesk')
        );
        if($args['type'] === 'mine'){
            $args['author'] = $this->user->id;
        }
        $args = apply_filters('vibe_helpdesk_replies_args',$args,$this->user);
        if(function_exists('bbp_has_replies')){
            //fetch all topic by parent
            $topic_id  = (int)$args['post_parent'];
            if ( bbp_has_replies($args) ) :
                $topic = get_post($topic_id,OBJECT); //current topic object
                while ( bbp_replies() ) : bbp_the_reply();
                    //global post topic object settings
                    global $post;
                    $post = $topic;
                    setup_postdata($topic);

                    $reply_id = bbp_get_reply_id();
                    $reply=apply_filters('vibeBbp_helpdesk_reply',array(
                        'id'=> $reply_id,
                        'permalink'=>bbp_get_reply_url(),
                        'last_update'=> bbp_get_reply_post_date($reply_id),
                        'author'=> get_post_field( 'post_author', $reply_id ),
                        'post_content'=>bbp_get_reply_content(),
                        'topic_id'=> $args['post_parent']
                    ));
                    $attachments = get_post_meta($reply_id,'attachment',false);
                    $reply['attachments'] = $attachments;
                    $replies[] = $reply;
                endwhile;
                wp_reset_postdata();
            endif;

            if(!empty($replies)){
                $bbp = bbpress();
                $return = array(
                    'status'=>1,
                    'replies'=>$replies,
                    'total'=> $bbp->reply_query->found_posts
                );
            }
        }
        $return = apply_filters('VibeBbp_get_replies_api',$return,$args,$this->user);
        return new WP_REST_Response($return, 200); 
    }   
    
    function get_engagements($request){
        $args = json_decode($request->get_body(),true);
        $engagements = bbp_get_user_engagements($args['user_ud']);
        $return =[];
        print_r($engagements);
        $return = apply_filters('VibeBbp_get_replies_api',$return,$args);
        return new WP_REST_Response($return, 200);
    }
    function create_forum($request){
        $args = json_decode($request->get_body(),true);
        $user_id = (int)$this->user->id;
        if(!empty($user_id)){
            $args['post_author'] = $user_id;
            if(function_exists('bbp_insert_forum')){
                $flag = bbp_insert_forum( $args );
                // set message from topics
                if(!empty($flag)){
                    $status = 1;
                    $message = _x('Forum Created','Forum Created','vibe-helpdesk');
                }else{
                    $status = 0;
                    $message = _x('Forum not Created','Forum not Created','vibe-helpdesk');
                }
                $data=array(
                    'status' => $status,
                    'data' => $flag,
                    'message' => $message
                );
            }else{
                $data=array(
                    'status' => 0,
                    'message' => _x('BB-Press Plugin not active!','BB-Press Plugin not active!','vibe-helpdesk')
                );
            }
        }else{
            $data=array(
                'status' => 0,
                'message' => _x('Authorization error!','Authorization error!','vibe-helpdesk')
            );
        }
        $data = apply_filters('VibeBbp_create_forum_api',$data,$args);
        return new WP_REST_Response($data, 200); 
    }

    function create_topic($request){
        $args = json_decode($request->get_body(),true);
        $user_id = (int)$this->user->id;
        // necessary data to pass in bbp_insert_topic( $topic_data, $topic_meta )
        $topic_data = array(
            'post_content' => $args['post_content'],
            'post_title'  =>  $args['post_title'],
            'post_parent' =>  $args['forum_id'],
            'post_author' => $user_id
        );
        $topic_meta = array(
            'forum_id'  =>  $args['forum_id'],
        );

        if(!empty($user_id)){
            $args['post_author'] = $user_id;
            if(!empty($args['topic_id'])){
                // Update the post into the database
                wp_update_post( array(
                    'ID'           => $args['topic_id'],
                    'post_type'    => 'topic',
                    'post_title'   => $args['post_title'],
                    'post_content' => $args['post_content'],
                ) );
                bbp_update_topic( $args['topic_id'], $args['forum_id'], array(), $user_id, true );
                $data=array(
                    'status' => 1,
                    'data' => $this->get_topic_by_id( $args['topic_id'] ),
                    'message' => _x('Topic Updated','Topic Updated','vibe-helpdesk')
                );
            }else{
                if(function_exists('bbp_insert_topic') ){
                    if(!empty($topic_data['post_content'])&& !empty($topic_data['post_title']) && !empty($topic_data['post_parent']) && !empty($topic_data['post_author']) && !empty($topic_meta['forum_id'])){
                        $flag = bbp_insert_topic( $topic_data, $topic_meta );
                        $topic_id = $flag;
                        // set message from topics
                        if(!empty($flag)){
                            $status = 1;
                            $data = $this->get_topic_by_id( $topic_id );
                            $message = _x('Topic Created','Topic Created','vibe-helpdesk');
                        }else{
                            $status = 0;
                            $data = false;
                            $message = _x('Topic not Created','Topic not Created','vibe-helpdesk');
                        }
                        $data=array(
                            'status' => $status,
                            'data' => $data,
                            'message' => $message
                        );
                    }else{
                        $data=array(
                            'status' => 0,
                            'data' => [],
                            'message' => _x('Passing Arguments not valid!','Passing Arguments not valid!','vibe-helpdesk')
                        );
                    } 
                }else{
                    $data=array(
                        'status' => 0,
                        'data' => [],
                        'message' => _x('BB-Press Plugin not active!','BB-Press Plugin not active!','vibe-helpdesk')
                    );
                }
            }
        }else{
            $data=array(
                'status' => 0,
                'data' => [],
                'message' => _x('Authorization error!','Authorization error!','vibe-helpdesk')
            );
        }   
        $data = apply_filters('VibeBbp_create_topic_api',$data,$args);
        return new WP_REST_Response($data, 200); 
    }   

    function create_reply($request){
        $body = json_decode(stripslashes($_POST['body']),true);
        $args=$body['args'];
        $user_id = (int)$this->user->id;
        $reply_data = array(
            'post_content' => $args['post_content'],
            'post_parent' =>  $args['topic_id'],
            'post_author' => $user_id
        );
        $reply_meta = array(
            'forum_id'  => $args['forum_id'],
            'topic_id'  => $args['topic_id']
        );
        
        if(!empty($user_id)){
            if( !empty($reply_meta['forum_id']) && !empty($reply_meta['topic_id']) && !empty($reply_data['post_content']) && !empty($reply_data['post_parent']) && !empty($reply_data['post_author']) ){
                if(function_exists('bbp_insert_reply')){
                    $reply_id = bbp_insert_reply( $reply_data, $reply_meta );
                    // set message from topics
                    if(!empty($reply_id)){
                        do_action('vibeBbp_helpdask_new_reply',$reply_id,$args);
                        $status = 1;
                        global $post;
                        $data=apply_filters('vibeBbp_helpdesk_reply',array(
                            'id'=> $reply_id,
                            'permalink'=>bbp_get_reply_url($reply_id),
                            'last_update'=> bbp_get_reply_post_date($reply_id),
                            'author'=> $user_id,
                            'post_content'=>$args['post_content'],
                            'topic_id'=>$args['topic_id'],
                        ));
                        $attachments =[];
                        if(!empty($_FILES) ){
                            if ( ! function_exists( 'wp_handle_upload' ) ) {
                                require_once( ABSPATH . 'wp-admin/includes/file.php' );
                            }
                                
                            $upload_overrides = array(
                                'test_form' => false
                            );
                            foreach($args['meta'] as $meta){
                                $uploadedfiles = $_FILES['files_'.$meta['value']];
                                $movefile = wp_handle_upload( $uploadedfiles, $upload_overrides );
                                if ( $movefile && ! isset( $movefile['error'] ) ) {
                                    $meta['value'] = $movefile['url'];
                                    $meta_id = add_post_meta( $reply_id, 'attachment',$meta);
                                    if($meta_id){
                                        $attachments[]=$meta;
                                        do_action('vibebp_upload_attachment',$movefile['url'],$user_id);    
                                    }
                                }
                            }
                        }
                        $data['attachments'] = $attachments;
                        $message = _x('Reply Created','Reply Created','vibe-helpdesk');
                    }else{
                        $status = 0;
                        $data = false;
                        $message = _x('Reply not Created','Reply not Created','vibe-helpdesk');
                    }
                    $data=array(
                        'status' => $status,
                        'reply' => $data,
                        'message' => $message
                    );
                }else{
                    $data=array(
                        'status' => 0,
                        'data' => [],
                        'message' => _x('BB-Press Plugin not active!','BB-Press Plugin not active!','vibe-helpdesk')
                    );
                }
            }else{
                $data=array(
                    'status' => 0,
                    'data' => [],
                    'message' => _x('Passing Arguments not valid!','Passing Arguments not valid!','vibe-helpdesk')
                );
            }  
        }else{
            $data=array(
                'status' => 0,
                'data' => [],
                'message' => _x('Authorization error!','Authorization error!','vibe-helpdesk')
            );
        }   
        $data = apply_filters('VibeBbp_create_reply_api',$data,$args);
        return new WP_REST_Response($data, 200); 
    }   

    function update_reply($request){
        $args = json_decode(file_get_contents('php://input'));
        $args = json_decode(json_encode($args),true);
        $user_id = (int)$this->user_id;
        $reply_id = $args['reply_id'];
        $topic_id = $args['topic_id'];
        $forum_id = $args['forum_id'];
        $author_id = $user_id;
        $new_content = $args['new_content'];
        if(!empty($reply_id)){
            if(!empty($user_id)){
                if(function_exists('bbp_update_reply')){
                    if(!empty($topic_id) && !empty($forum_id)){
                        $reply = bbp_get_reply( $reply_id );
                        if ( !empty( $reply ) ){
                            if(($user_id == $reply->post_author) || user_can($user_id,'edit_post') ){
                                /* Update with new content then update forum ->topic ->reply*/
                                $my_post = array(
                                  'ID' =>  $reply_id,
                                  'post_content'  => $new_content
                                );
                                $flag  = wp_update_post( $my_post );
                                if(!empty($flag)){
                                    bbp_update_reply( $reply_id , $topic_id , $forum_id, $anonymous_data = false, $author_id , $is_edit = false, $reply_to = 0 );
                                    // user obj add to reply
                                    $ereply = bbp_get_reply( $reply_id );
                                    $user_id = (int)$ereply->post_author;
                                    $ereply->user = $this->get_user_by_ID($user_id);
                                    $data=array(
                                        'status' => 1,
                                        'data' => $ereply,
                                        'message' => _x('Reply Updated.','Reply Updated','vibe-helpdesk')
                                    );
                                }else{
                                    $data=array(
                                        'status' => 0,
                                        'data' => false,
                                        'message' => _x('Reply not Updated.','Reply not Updated','vibe-helpdesk')
                                    );
                                }
                            }else{
                                $data=array(
                                    'status' => 0,
                                    'data' => false,
                                    'message' =>  _x('You are not a valid user to update this reply','Not success','vibe-helpdesk')
                                );
                            }   
                        }else{
                            $data=array(
                                'status' => 0,
                                'data' => false,
                                'message' => _x('Reply not Exist.','Reply not Exist','vibe-helpdesk')
                            );
                        }
                    }else{
                        $data=array(
                            'status' => 0,
                            'data' => [],
                            'message' => _x('Passing Arguments not valid!','Passing Arguments not valid!','vibe-helpdesk')
                        );
                    }
                }else{
                    $data=array(
                        'status' => 0,
                        'data' => false,
                        'message' => _x('BB-Press Plugin not active!','BB-Press Plugin not active!','vibe-helpdesk')
                    );
                }
            }else{
                $data=array(
                    'status' => 0,
                    'data' => false,
                    'message' => _x('Authorization error!','Authorization error!','vibe-helpdesk')
                );
            }
        }else{
            $data=array(
                'status' => 0,
                'data' => false,
                'message' => _x('Insufficient data','Insufficient data','vibe-helpdesk')
            );
        }
        $data = apply_filters('VibeBbp_update_reply_api',$data,$args);
        return new WP_REST_Response($data, 200); 
    }


    function delete_reply($request){
        $args = json_decode(file_get_contents('php://input'));
        $args = json_decode(json_encode($args),true);
        $sub_action = $args['sub_action']?$args['sub_action']:'trash';
        $reply_id = $args['reply_id'];
        $user_id = (int)$this->user->id;
        /* validate here user to delete reply..  */
        if(!empty($user_id)){
            if(function_exists('bbp_get_reply')){
                $reply = bbp_get_reply( $reply_id );
                if ( empty( $reply ) ){
                    $data=array(
                        'status' => 0,
                        'data' => false,
                        'message' => _x('Reply not Exist.','Reply not Exist','vibe-helpdesk')
                    );
                }else{
                    if($user_id == $reply->post_author || user_can($user_id,'edit_posts')){
                        switch ( $sub_action ) {
                            case 'trash':
                            $success  = wp_trash_post( $reply_id );
                            if($success){
                                $message = _x('Reply trash successfull','Reply trash successfull','vibe-helpdesk');
                            }
                            break;
                        case 'untrash':
                            $success = wp_untrash_post( $reply_id );
                            if($success){
                                $message = _x('Reply untrash successfull','Reply untrash successfull','vibe-helpdesk');
                            }
                            break;
                        case 'delete':
                            $success = wp_delete_post( $reply_id );
                            if($success){
                                $message = _x('Reply delete successfull','Reply delete successfull','vibe-helpdesk');
                            }
                            break;
                        }
                        if($success){
                            $data=array(
                                'status' => 1,
                                'data' => $success,
                                'message' => $message?$message:''
                            );
                        }else{
                            $data=array(
                                'status' => 0,
                                'data' => $success,
                                'message' =>  _x('Not success','Not success','vibe-helpdesk')
                            );
                        }
                    }else{
                        $data=array(
                            'status' => 0,
                            'data' => false,
                            'message' =>  _x('You are not a valid user to delete this reply','Not success','vibe-helpdesk')
                        );
                    }   
                }
            }else{
                $data=array(
                    'status' => 0,
                    'data' => false,
                    'message' => _x('BB-Press Plugin not active!','BB-Press Plugin not active!','vibe-helpdesk')
                );
            }
        }else{
            $data=array(
                'status' => 0,
                'data' => false,
                'message' => _x('Authorization error!','Authorization error!','vibe-helpdesk')
            );
        }
        $data = apply_filters('VibeBbp_delete_reply_api',$data,$args);
        return new WP_REST_Response($data, 200); 
    }

    function set_topic_my_favourite($request){
        $body = json_decode($request->get_body(),true);
        $topic_id = $body['topic_id'];
        $user_id = (int)$this->user->id;
        $data = array(
            'status' => 0,
            'data' => false,
            'message' => _x('Unable to set favorite','Authorization error!','vibe-helpdesk')
        );
        if(!empty($user_id) && !empty($topic_id)){
            $flag = bbp_add_user_favorite( $user_id, $body['topic_id']);
            if($flag){
                $data = array(
                    'status' => 1,
                    'data' => true,
                    'message' => _x('Topic Set as Unfavorite','Topic Set as Unfavorite','vibe-helpdesk')
                );
            }
        }
        $data = apply_filters('VibeBbp_set_topic_my_favorite',$data,$request);
        return new WP_REST_Response($data, 200); 
    }

    function unset_topic_my_favourite($request){
        $body = json_decode($request->get_body(),true);
        $topic_id = $body['topic_id'];
        $user_id = (int)$this->user->id;
        $data = array(
            'status' => 0,
            'data' => false,
            'message' => _x('Unable to set favorite','Authorization error!','vibe-helpdesk')
        );
        if(!empty($user_id) && !empty($topic_id)){
            $flag = bbp_remove_user_favorite( $user_id, $body['topic_id']);
            if($flag){
                $data = array(
                    'status' => 1,
                    'data' => true,
                    'message' => _x('Topic Set as Unfavorite','Topic Set as Unfavorite','vibe-helpdesk')
                );
            }
        }
        $data = apply_filters('VibeBbp_unset_topic_my_favorite',$data,$request);
        return new WP_REST_Response($data, 200); 
    }

    function subscribe($request){
        //bbp_add_user_subscription( $user_id = 0, $object_id = 0 )
        //bbp_remove_user_subscription
    }

    function delete_topic($request){
        $body = json_decode($request->get_body(),true);
        $topic_id = $body['topic_id'];
        $user_id = (int)$this->user->id;
        $data = array(
            'status' => 0
        );
        if(!empty($user_id) && !empty($topic_id)){
            bbp_trash_topic($topic_id);
            $data['status']=1;
        }else{
            $data['message']=_x('Unable to remove topice','Authorization error!','vibe-helpdesk');
        }
        return new WP_REST_Response($data, 200); 
    }

    function sla_open_topics($request){
        $post = json_decode($request->get_body());
        $filter = $post->filter;
        if(function_exists('bbp_has_forums')){
            if(class_exists('Vibe_HelpDesk_Init')){
                $per_page = (!empty($filter->per_page)) ? ($filter->per_page<20?$filter->per_page:20) : 20;
                $paged_temp = (!empty($filter->paged)) ? ($filter->paged<20?$filter->paged:1) : 1;
                $paged = $per_page*($paged_temp-1);
                $search_terms = (!empty($filter->search_terms)?$filter->search_terms:'');
                $like = '%'.$search_terms.'%';
                $type = bbp_get_topic_post_type();
                // Query build
                global $wpdb;
                $query = "SELECT ID,post_title 
                    FROM {$wpdb->posts} 
                    WHERE post_title LIKE '".$like."' AND post_type = '".$type."' AND post_status = 'publish'
                    ORDER BY ID DESC 
                    LIMIT ".$per_page." OFFSET ".$paged;
                $results = array();
                $results = $wpdb->get_results($query,'ARRAY_A');
                // Array data create
                if(!empty($results)){
                    $helpdesk_init = Vibe_HelpDesk_Init::init();
                    foreach ($results as $key => $value) {
                        $results[$key]['sla'] = $helpdesk_init->count_sla_topic($value['ID']);
                    }
                    $data = array(
                        'status' => 1,
                        'message' => _x('SLA counting available!','SLA counting available!','vibe-helpdesk'),
                        'data' => $results
                    ); 
                }
            }else{
               $data = array(
                    'status' => 0,
                    'message' => _x('SLA counting unavailable!','SLA counting unavailable!','vibe-helpdesk')
                ); 
            }   
        }else{
            $data = array(
                'status' => 0,
                'message' => _x('BB-press not active!','BB-press not active!','vibe-helpdesk')
            );
        }  
        $data = apply_filters('vibe_sla_open_topics',$data,$request);      
        return new WP_REST_Response($data, 200); 
    }

    function topic_labels($request){
        $labels = get_option(VIBE_BP_SETTINGS)['helpdesk']['forums']['bbp_labels'];
        if(!empty($labels) && is_array($labels)){
            $data = array(
                'status' => 1,
                'data' => $labels,
                'message' => _x('Topic labels found','Topic labels found','vibe-helpdesk')
            );
        }else{
            $data = array(
                'status' => 0,
                'message' => _x('Topic labels not found','Topic labels not found','vibe-helpdesk')
            );
        }
        $data = apply_filters('vibe_topic_labels',$data,$request);
        return new WP_REST_Response($data, 200); 
    }

    function assign_topic_label($request){
        $post = json_decode($request->get_body());
        $action = $post->action;
        $label = $post->label;
        $topic_id = $post->topic_id;
        if(!empty($action) && !empty($label) && !empty($topic_id)){
            $is_cap = $this->can_assign_topic_label($topic_id);
            if($is_cap){
                switch ($action) {
                    case 'assign':
                        $labels = get_option(VIBE_BP_SETTINGS)['forums']['bbp_labels'];
                        if(!empty($labels) && is_array($labels)){
                            foreach ($labels as $key => $value) {
                                if($value->label == $label){
                                    $flag = 1;
                                    break;
                                }
                            }
                            if($flag){
                                delete_post_meta($topic_id,'assigned_topic_label',$label);
                                add_post_meta($topic_id,'assigned_topic_label',$label);
                                $data = array(
                                    'status' => 1,
                                    'message' => _x('Label assigned','Label assigned','vibe-helpdesk'),
                                    'data' => $this->get_assigned_topic_labels($topic_id,$labels)
                                ); 
                            }else{
                                $data = array(
                                    'status' => 0,
                                    'message' => _x('This Label not present in Admin-panel','This Label not present in Admin-panel','vibe-helpdesk')
                                );
                            }
                        }else{
                            $data = array(
                                'status' => 0,
                                'message' => _x('Labels not present in Admin-panel','Labels not present in Admin-panel','vibe-helpdesk')
                            );
                        }
                        break;
                    case 'unassign':
                        delete_post_meta($topic_id,'assigned_topic_label',$label);
                        $data = array(
                            'status' => 1,
                            'message' => _x('Label unassigned','Label unassigned','vibe-helpdesk'),
                            'data' => $this->get_assigned_topic_labels($topic_id)
                        ); 
                        break;
                    default:
                            $data = array(
                                'status' => 0,
                                'message' => _x('Action not determined','Action not determined','vibe-helpdesk')
                            );
                        break;
                }
            }else{
                $data = array(
                    'status' => 0,
                    'message' => _x('Can not assign label','Can not assign label','vibe-helpdesk')
                );
            }
        }else{
            $data = array(
                'status' => 0,
                'message' => _x('Data missing','Data missing','vibe-helpdesk')
            );
        }
        $data = apply_filters('vibe_assign_topic_label',$data,$request);
        return new WP_REST_Response($data, 200); 
    }

    function can_assign_topic_label($topic_id=0){
        $can = true;
        return apply_filters('vibe_can_assign_topic_label',$can,$topic_id,$this->user); 
    }

    function search_topic_assignable_user($request){
        $post = json_decode($request->get_body(),true);
        $topic_id = $post['topic_id'];
        $search_term = $post['search_term'];
        if(!empty($topic_id) && isset($search_term)){
            $is_cap = $this->can_assign_topic($topic_id); // check cap here to assign
            if($is_cap){
                // Query build
                $per_page = apply_filters('vibe_search_topic_assignable_user_per_page',5);
                global $wpdb;
                $like = '%'.$search_term.'%';
                $query ="SELECT ID as id,user_nicename as name
                    FROM {$wpdb->users} as u
                    WHERE u.ID NOT IN(
                        SELECT meta_value
                        FROM {$wpdb->postmeta} 
                        WHERE meta_key = 'assigned_agent' 
                        AND post_id = {$topic_id}
                    ) AND (user_nicename LIKE '".$like."' OR user_email LIKE '".$like."')
                    LIMIT {$per_page} OFFSET 0";
                $results = $wpdb->get_results($query,'ARRAY_A');
                if(!empty($results) && is_array($results)){
                    $data = array(
                        'status' => 1,
                        'message' => _x('Users found','Users found','vibe-helpdesk'),
                        'data' => $results
                    );
                }else{
                    $data = array(
                        'status' => 0,
                        'message' => _x('Users Not found','Users Not found','vibe-helpdesk')
                    );
                }
            }else{
                $data = array(
                    'status' => 0,
                    'message' => _x('Can not search user','Can not search user','vibe-helpdesk')
                );
            }
        }else{
            $data = array(
                'status' => 0,
                'message' => _x('Data missing','Data missing','vibe-helpdesk')
            );
        }
        $data = apply_filters('vibe_search_topic_assignable_user',$data,$request);
        return new WP_REST_Response($data, 200); 
    }

    function assign_topic($request){
        $post = json_decode($request->get_body(),true);
        $topic_id = $post['topic_id'];
        $user_ids = $post['user_ids'];
        $action = $post['action'];
        if(!empty($topic_id) && !empty($action) && isset($user_ids) && is_array($user_ids)){
            $is_cap = $this->can_assign_topic($topic_id); // check cap here to assign
            if($is_cap){
                switch ($action) {
                    case 'assign':
                        foreach($user_ids as $key => $user_id){
                            $user_id = (int)$user_id;
                            $values = get_post_meta($topic_id,'assigned_agent');
                            if(!in_array($user_id,$values)){
                                add_post_meta($topic_id,'assigned_agent',$user_id);
                            }
                        }
                        $data = array(
                            'status' => 1,
                            'message' => _x('Agents assigned','Agent assigned','vibe-helpdesk')
                        );
                    break;
                    case 'unassign':
                        foreach($user_ids as $key => $user_id){
                            $user_id = (int)$user_id;
                            delete_post_meta($topic_id,'assigned_agent',$user_id);
                        }
                        $data = array(
                            'status' => 1,
                            'message' => _x('Agents unassigned','Agent unassigned','vibe-helpdesk')
                        );
                    break;
                    default:
                        $data = array(
                            'status' => 0,
                            'message' => _x('Action not matched','Action not matched','vibe-helpdesk')
                        );
                    break;
                }
            }else{
                $data = array(
                    'status' => 0,
                    'message' => _x('Not capable','Not capable','vibe-helpdesk')
                );
            }
        }else{
            $data = array(
                'status' => 0,
                'message' => _x('Data missing','Data missing','vibe-helpdesk')
            );
        }
        $data = apply_filters('vibe_assign_topic_label',$data,$request);
        return new WP_REST_Response($data, 200); 
    }

    function can_assign_topic($topic_id=0){
        $user = get_userdata( $this->user->id );
        $roles = $user->roles;
        $needed_role = 'administrator';  // from options(supervisor like)
        $can = in_array($needed_role,$roles);;
        return apply_filters('vibe_can_assign_topic',$can,$topic_id,$this->user); 
    }

    function save_canned($request){
        $post = json_decode($request->get_body(),true);
        $canned_title = $post['canned_title'];
        $canned_response = $post['canned_response'];
        if(!empty($canned_title) && !empty($canned_response)){
            $arr = apply_filters('vibe_save_canned_array',array(
                'post_title' => $canned_title,
                'post_content' =>  $canned_response,
                'post_type' => VIBEHELPDESK_CANNED_POST_TYPE,
                'post_status' => 'publish',
                'post_author' => $this->user->id
            ));
            if(!empty($post['canned_id'])){
                $arr['ID'] = $post['canned_id'];
                unset($arr['post_author']);
                $check = wp_update_post($arr);
                if($check){
                    do_action('vibebp_helpdesk_canned_response_update',$arr,$this->user);    
                }
                
            }else{
                $check = wp_insert_post($arr);
                if($check){
                    do_action('vibebp_helpdesk_canned_response_added',$arr,$this->user);    
                }
                
            }
            if($check){
                $data = array(
                    'status' => 1,
                    'message' => _x('Saved as canned response','Saved as canned response','vibe-helpdesk')
                );    
            }else{
                $data = array(
                    'status' => 0,
                    'message' => _x('Not saved as canned response','Not saved as canned response','vibe-helpdesk')
                ); 
            }
        }else{
            $data = array(
                'status' => 0,
                'message' => _x('Data missing','Data missing','vibe-helpdesk')
            );
        }
        $data = apply_filters('vibe_save_canned',$data,$request);
        return new WP_REST_Response($data, 200); 
    }

    function search_canned($request){
        $post = json_decode($request->get_body(),true);
        $search_terms = $post['search_terms'];
        if(!empty($search_terms)){
            //search here
            $args = apply_filters('vibe_search_canned_array',array(
                'post_type' => VIBEHELPDESK_CANNED_POST_TYPE,
                'post_status' => 'publish',
                'posts_per_page' => 5,
                'author' => $this->user->id,
                's' => $search_terms
            ));
            $query_r = new WP_Query($args);
            if(!empty($query_r->posts) && is_array($query_r->posts)){
                $data = array(
                    'status' => 1,
                    'message' => _x('Canned responses found','Canned responses found','vibe-helpdesk'),
                    'data' => $query_r->posts
                );
            }else{
                $data = array(
                    'status' => 0,
                    'message' => _x('Canned responses not found','Canned responses not found','vibe-helpdesk'),
                );
            }
        }else{
            $data = array(
                'status' => 0,
                'message' => _x('Data missing','Data missing','vibe-helpdesk')
            );
        }
        $data = apply_filters('vibe_search_canned',$data,$request);
        return new WP_REST_Response($data, 200); 
    }  
}

Vibe_HelpDesk_API::init();
