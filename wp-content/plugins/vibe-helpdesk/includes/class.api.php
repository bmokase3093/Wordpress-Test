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

        register_rest_route( $this->namespace, '/'. $this->type .'/taxonomy', array(
            'methods'                   =>   'POST',
            'callback'                  =>  array( $this, 'get_helpdesk_taxonomy' ),
            'permission_callback' => array( $this, 'get_user_permissions_check' ),
        ) );

        register_rest_route( $this->namespace, '/'. $this->type .'/user-info/create', array(
            'methods'                   =>   'POST',
            'callback'                  =>  array( $this, 'create_user_info' ),
            'permission_callback' => array( $this, 'get_user_permissions_check' ),
        ) );

        register_rest_route( $this->namespace, '/'. $this->type .'/user-info', array(
            'methods'                   =>   'POST',
            'callback'                  =>  array( $this, 'get_user_info' ),
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

        register_rest_route( $this->namespace, '/'. $this->type .'/topics/notes', array(
            'methods'                   =>   'POST',
            'callback'                  =>  array( $this, 'get_topic_notes' ),
            'permission_callback' => array( $this, 'get_user_permissions_check' ),
        ) );

        register_rest_route( $this->namespace, '/'. $this->type .'/topics/notes/create', array(
            'methods'                   =>   'POST',
            'callback'                  =>  array( $this, 'create_topic_notes' ),
            'permission_callback' => array( $this, 'get_user_permissions_check' ),
        ) );

        register_rest_route( $this->namespace, '/'. $this->type .'/topics/notes/delete', array(
            'methods'                   =>   'POST',
            'callback'                  =>  array( $this, 'delete_not_notes' ),
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
            'permission_callback' => array( $this, 'get_user_permissions_check' ),
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

        // register_rest_route( $this->namespace, '/'. $this->type .'/forums/create', array(
        //     'methods'                   =>   'POST',
        //     'callback'                  =>  array( $this, 'create_forum' ),
        //     'permission_callback' => array( $this, 'get_user_create_permissions_check' ),
        // ) );

        register_rest_route( $this->namespace, '/'. $this->type .'/topics/create', array(
            'methods'                   =>   'POST',
            'callback'                  =>  array( $this, 'create_topic' ),
            'permission_callback' => array( $this, 'get_user_permissions_check' ),
        ) );

        register_rest_route( $this->namespace, '/'. $this->type .'/replies/create', array(
            'methods'                   =>   'POST',
            'callback'                  =>  array( $this, 'create_reply' ),
            'permission_callback' => array( $this, 'get_user_permissions_check' ),
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

        register_rest_route( $this->namespace, '/'. $this->type .'/topics/assign_topic', array(
            'methods'                   =>   'POST',
            'callback'                  =>  array( $this, 'assign_topic' ),
            'permission_callback' => array( $this, 'get_user_permissions_check' ),
        ) );

        register_rest_route( $this->namespace, '/'. $this->type .'/topic/resolve', array(
            'methods'                   =>   'POST',
            'callback'                  =>  array( $this, 'resolve_topic' ),
            'permission_callback' => array( $this, 'get_user_permissions_check' ),
        ) );

        register_rest_route( $this->namespace, '/'. $this->type .'/topics/assign_topic_label', array(
            'methods'                   =>   'POST',
            'callback'                  =>  array( $this, 'assign_topic_label' ),
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

        register_rest_route( $this->namespace, '/'. $this->type .'/users/canned-responses', array(
            'methods'                   =>   'POST',
            'callback'                  =>  array( $this, 'get_canned' ),
            'permission_callback' => array( $this, 'get_user_permissions_check' ),
        ) );

         register_rest_route( $this->namespace, '/'. $this->type .'/users/canned-responses/delete', array(
            'methods'                   =>   'POST',
            'callback'                  =>  array( $this, 'delete_canned' ),
            'permission_callback' => array( $this, 'get_user_permissions_check' ),
        ) );

        register_rest_route( $this->namespace, '/'. $this->type .'/search', array(
            array(
                'methods'             =>  'POST',
                'callback'            =>  array( $this, 'search_sharing_values' ),
                'permission_callback' => array( $this, 'get_user_permissions_check' ),
            ),
        ));

        register_rest_route( $this->namespace, '/'. $this->type .'/dashboard-widget/stats', array(
            array(
                'methods'             =>  'POST',
                'callback'            =>  array( $this, 'get_dashboard_widget_data_stats' ),
                'permission_callback' => array( $this, 'get_user_permissions_check' ),
            ),
        ));

        register_rest_route( $this->namespace, '/'. $this->type .'/dashboard-widget/chart', array(
            array(
                'methods'             =>  'POST',
                'callback'            =>  array( $this, 'get_dashboard_widget_data_chart' ),
                'permission_callback' => array( $this, 'get_user_permissions_check' ),
            ),
        ));
        
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
                    $args['post__in']= $this->subscriptions;
                break;
            }   
        }
        
        $args = apply_filters('vibe_helpdesk_forums_args',$args,$this->user);
        $args['post_parent'] = 'any';
        
        if(function_exists('bbp_has_forums')){
            if ( bbp_has_forums($args) ) :
                while ( bbp_forums() ) : bbp_the_forum();
                    global $post;
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
                endwhile;
            endif;
            // set message from topics
            if(!empty($forums)){
                $bbp = bbpress();
                $return=array(
                    'status' => 1,
                    'forums' => $forums,
                    'total'=>$bbp->forum_query->found_posts
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

    function get_helpdesk_taxonomy($request){
        $args = json_decode($request->get_body(),true);
        $data = array( 'status' => 0 );
        if(!empty($args['taxonomy']) && $args['taxonomy']=== VIBEHELPDESK_TOPIC_PRIORITY){
            $terms = get_terms( array(
                'taxonomy' => $args['taxonomy'],
                'hide_empty' => false,
            ) );
            if(!empty($terms)){
                foreach ($terms as $key => $term) {
                    $terms[$key]->color = get_term_meta( $term->term_id, 'priority_color', true ); 
                }
                $data = array(
                    'status' => 1,
                    'data' => $terms
                );
            }
        }
        return new WP_REST_Response($data, 200); 
    }

    function get_user_info($request){
        $args = json_decode($request->get_body(),true);
        $user_id = $this->user->id;
        if(!empty($args['topic_id'])){ //topic id
            $topic_id = $args['topic_id'];
            if($this->is_topic_agent($topic_id) || $this->can_assign_topic($topic_id)){
                $user_id = get_post_field( 'post_author', $topic_id);
            }
        }
        $info = array(
            'post_content' => get_user_meta($user_id,'vhd_user_info_content',true),
            'raw' => get_user_meta($user_id,'vhd_user_info_raw',true),
        );
        $data = arraY('status' => 1, 'info' => $info);
        return new WP_REST_Response($data, 200); 
    }

    function create_user_info($request){
        $args = json_decode($request->get_body(),true);
        $data = array( 'status' => 0 , 'message'=> __('Info not updated!','vibe-helpdesk') );
        if(isset($args['post_content']) && isset($args['raw'])){
            update_user_meta($this->user->id,'vhd_user_info_content',$args['post_content']);
            update_user_meta($this->user->id,'vhd_user_info_raw',$args['raw']);
            $data = array( 'status' => 1 , 'message'=> __('Info updated!','vibe-helpdesk') );
        }
        return new WP_REST_Response($data, 200); 
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
            case 'topics':
                $args['author'] = $this->user->id;
            break;
            case 'favorites':
                $args['post__in'] = $favorites;
            break;
            case 'subscriptions':
                $args['post__in'] = $subscriptions;
            break;
            case 'unassigned': //supervisor
                $args['meta_query'] = array(
                    array(
                        'key'=>'assigned_agent',
                        'compare'=>'NOT EXISTS'
                    )
                ); 
            break;
            case 'recent_assigned': //supervisor
                $args['meta_query'] = array(
                    array(
                        'key'=>'assigned_agent',
                        'compare'=>'EXISTS'
                    )
                );
            break;
            case 'assigned': //agent
                $args['meta_query'] = array(
                    array(
                        'key'=>'assigned_agent',
                        'value'=>$this->user->id,
                        'compare'=>'='
                    ),
                    array(
                        'key'=>'resolved',
                        'compare'=>'NOT EXISTS'
                    )
                );
            break; 
            case 'resolved': //agent
                $args['meta_query'] = array(
                    array(
                        'key'=>'resolved',
                        'value'=>1,
                        'compare'=>'='
                    ),
                    array(
                        'key'=>'assigned_agent',
                        'value'=>$this->user->id,
                        'compare'=>'='
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

        if(!empty($args['priorities'])){
            $tax[] = array(
                'taxonomy' => VIBEHELPDESK_TOPIC_PRIORITY,
                'field'    => 'term_id',
                'terms'    => $args['priorities'],
                'operator' => 'IN',
            );
            $args['tax_query'][] = $tax;
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
                        'reply_count'=>bbp_get_topic_reply_count(bbp_get_topic_id()),
                        'author'=> $post->post_author,
                        'post_content'=>bbp_get_topic_content($topic_id),
                        'forum_id'=>$post->post_parent,
                        'favorite'=>in_array($topic_id,$favorites)?true:false,
                        'subscribed'=>in_array($topic_id,$subscriptions)?true:false,
                        'is_resolved' => (int)get_post_meta($topic_id,'resolved',true),
                        'priorities' => wp_get_post_terms($topic_id,VIBEHELPDESK_TOPIC_PRIORITY,array('fields'=>'ids'))
                    );
                    switch ($args['type']) {
                        case 'recent_assigned':
                        case 'unassigned':
                            $topics[$index]['assigned_agents'] = $this->get_assigned_user($topic_id);
                        break;
                        case 'resolved':
                        case 'assigned':
                            $topics[$index]['resolved'] = $this->get_resolved($topic_id);
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

    function get_assigned_user($topic_id){
        return get_post_meta($topic_id,'assigned_agent');
    }

    function get_resolved($topic_id){
        return get_post_meta($topic_id,'resolved',true);
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

    function get_topic_notes($request){
        $args = json_decode($request->get_body(),true);
        $data = array('status'=>0);
        if(!empty($args['id']) && ($this->is_topic_agent($args['id']) || $this->can_assign_topic($args['id'])) ){
            global $wpdb;
            $query = $wpdb->prepare ("SELECT meta_id,meta_value FROM {$wpdb->postmeta} WHERE post_id = %d  AND  meta_key='topic_notes'",$args['id']);
            $results = $wpdb->get_results($query,'ARRAY_A');
            if(!empty($results)){
                $data = array(
                    'status' => 1,
                    'data' => $results
                );
            }
        }
        return new WP_REST_Response($data, 200); 
    }

    function create_topic_notes($request){
        $args = json_decode($request->get_body(),true);
        $data = array('status'=>0 ,'message' => __('Note not added!','vibe-helpdesk'));
        if(!empty($args['id']) && isset($args['post_content']) && ($this->is_topic_agent($args['id']) || $this->can_assign_topic($args['id'])) ){
            $meta_id = add_post_meta($args['id'],'topic_notes',$args['post_content']);
            $data = array(
                'status' => 1,
                'message' => __('Note added!','vibe-helpdesk'),
                'data' => array(
                    'meta_id' => $meta_id,
                    'meta_value' => $args['post_content']
                )
            ); 
        }
        return new WP_REST_Response($data, 200); 
    }

    function delete_not_notes($request){
        $args = json_decode($request->get_body(),true);
        $data = array('status'=>0 ,'message' => __('Note not deleted!','vibe-helpdesk'));
        if(!empty($args['meta_id']) && $this->is_agent()) {
            delete_metadata_by_mid('post',$args['meta_id']);
            $data = array(
                'status' => 1,
                'message' => __('Note deleted!','vibe-helpdesk')
            ); 
        }
        return new WP_REST_Response($data, 200); 
    }

    

    function is_agent($topic_id=0){
        $user = get_userdata( $this->user->id );
        $roles = $user->roles;
        $agents = vibebp_get_setting('agents','helpdesk');
        $needed_role = $agents['bbp_agents'];
        $can = in_array($needed_role,$roles);;
        return apply_filters('vibe_is_agent_topic',$can,$topic_id,$this->user->id); 
    }

    function can_assign_topic($topic_id=0){
        $user = get_userdata( $this->user->id );
        $roles = $user->roles;
        $agents = vibebp_get_setting('agents','helpdesk');
        $needed_role = $agents['bbp_supervisor'];
        $can = in_array($needed_role,$roles);;
        return apply_filters('vibe_can_assign_topic',$can,$topic_id,$this->user->id); 
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
            'permalink'=>bbp_get_topic_permalink($topic_id),
            'last_update' => mysql2date( 'D, d M Y H:i:s +0000', get_post_time( 'Y-m-d H:i:s', true ,$topic_id), false ),
            'author' => $topic['post_author'],
            'post_content'=>$topic['post_content'],
            'reply_count' => (int) get_post_meta( $topic_id, '_bbp_reply_count', true ),
            'forum_id' => $topic['post_parent'],
            'favorite' => in_array($topic_id,$favorites)?true:false,
            'subscribed' => in_array($topic_id,$subscriptions)?true:false,
            'is_resolved' => get_post_meta($topic_id,'resolved',true),
            'priorities' => wp_get_post_terms($topic_id,VIBEHELPDESK_TOPIC_PRIORITY,array('fields'=>'ids'))
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
        if(!empty($args['type']) && $args['type'] === 'mine'){
            $args['author'] = $this->user->id;
        }
        $args = apply_filters('vibe_helpdesk_replies_args',$args,$this->user);
        if(function_exists('bbp_has_replies')){
            //fetch all topic by parent
            $forum_id = null;
            $topic_id = 0;
            $topic = [];
            if(!empty($args['post_parent'])){
                $topic_id  = (int)$args['post_parent'];
                $topic = get_post($topic_id);
            }
            
            if ( bbp_has_replies($args) ) :
                while ( bbp_replies() ) : bbp_the_reply();
                    //global post topic object settings
                    global $post;
                    if(!empty($topic)){
                        $post = $topic;
                        setup_postdata($topic);
                    }
                    

                    $reply_id = bbp_get_reply_id();
                    if ( empty( $topic_id ) ) {
                        $topic_id = bbp_get_reply_topic_id( $reply_id );
                    }
                    if ( ! empty( $topic_id ) && empty( $forum_id ) ) {
                        $forum_id = bbp_get_topic_forum_id( $topic_id );
                    }
                    $reply=apply_filters('vibeBbp_helpdesk_reply',array(
                        'id'=> $reply_id,
                        'permalink'=>bbp_get_reply_url(),
                        'last_update'=> bbp_get_reply_post_date($reply_id),
                        'author'=> get_post_field( 'post_author', $reply_id ),
                        'post_content'=>apply_filters('the_content',bbp_get_reply_content($reply_id)),
                        'topic_id'=> $topic_id,
                        'forum_id'=> $forum_id,
                        'raw' => get_post_meta($reply_id,'raw',true),

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
                        do_action( 'bbp_new_topic', $topic_id, $topic_meta['forum_id'], array(), $user_id );
                        // set message from topics
                        if(!empty($flag)){
                            if(isset($args['priorities']) && is_array($args['priorities'])){
                                wp_set_post_terms( $topic_id, $args['priorities'], VIBEHELPDESK_TOPIC_PRIORITY);
                            }
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
        $args = json_decode($request->get_body(),true);
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
        
        $topic_id = $args['topic_id'];
        $forum_id = $args['forum_id'];

        $updated = false; //if chnages happen

        if(!empty($user_id)){
            if( !empty($forum_id) && !empty($reply_meta['topic_id']) && !empty($reply_data['post_content']) && !empty($reply_data['post_parent']) && !empty($reply_data['post_author']) ){
                if(function_exists('bbp_insert_reply')){

                    remove_filter('content_save_pre', 'wp_filter_post_kses');
                    remove_filter('content_filtered_save_pre', 'wp_filter_post_kses');
                    //ad hoc for core function bbp_insert_reply and bbp_update_reply removing user susbcriptin
                    add_filter('bbp_is_subscriptions_active',function(){return false;},99999);
                    if(empty($args['id'])){
                        remove_action( 'bbp_insert_reply', 'bbp_insert_reply_update_counts', 10, 3 );
                        $reply_id = bbp_insert_reply( $reply_data, $reply_meta );
                        $updated  = true;
                        $message = _x('Reply Created','Reply Created','vibe-helpdesk');
                    }else{
                        $reply_id  = $args['id'];
                        if(function_exists('bbp_update_reply')){
                            if(!empty($topic_id) && !empty($forum_id)){
                                $reply = bbp_get_reply( $reply_id );
                                if ( !empty( $reply ) ){
                                    if($user_id == $reply->post_author || user_can($user_id,'edit_post') ){
                                        $my_post = array(
                                            'ID' =>  $reply_id,
                                            'post_content'  => $reply_data['post_content']
                                        );
                                        $flag  = wp_update_post( $my_post );
                                        if(!empty($flag)){
                                            bbp_update_reply( $reply_id , $topic_id , $forum_id, $anonymous_data = false, $user_id , $is_edit = false, $reply_to = 0 );
                                            $status = 1;
                                            $message = _x('Reply Updated.','Reply Updated','vibe-helpdesk');
                                            $updated = true;
                                        }
                                    } 
                                }
                            }
                        }  
                    }
                    add_filter('content_save_pre', 'wp_filter_post_kses');
                    add_filter('content_filtered_save_pre', 'wp_filter_post_kses');
                   
                    if(!empty($reply_id) && $updated){
                        if(isset( $args['raw'])){
                            update_post_meta($reply_id,'raw',$args['raw']);
                        }
                        /** Update counts, etc... *********************************************/
                        $anonymous_data = array();
                        /** Additional Actions (After Save) ***********************************/

                        //do_action( 'bbp_new_reply_post_extras', $reply_id );

                        do_action('vibebp_helpdesk_new_reply',$reply_id,$args,$reply_meta['topic_id'], $reply_meta['forum_id'],$user_id );
                        $status = 1;
                        global $post;
                        $data=apply_filters('vibeBbp_helpdesk_reply',array(
                            'id'=> $reply_id,
                            'permalink'=>bbp_get_reply_url($reply_id),
                            'last_update'=> bbp_get_reply_post_date($reply_id),
                            'author'=> $user_id,
                            'post_content'=>apply_filters('the_content',$args['post_content']),
                            'topic_id'=>$args['topic_id'],
                            'forum_id'=>$forum_id,
                        ));
                        if(empty($args['id'])){
                            if(!empty($_POST['bbp_private_reply'])){
                                $anonymous_data['bbp_private_reply'] = true;
                            }else{

                                do_action( 'bbp_new_reply', $reply_id, $reply_meta['topic_id'], $reply_meta['forum_id'], $anonymous_data,$user_id, false, 1 );
                                
                            }
                        }
                        
                        
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
        $sub_action = !empty($args['sub_action'])?$args['sub_action']:'trash';
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
                                'message' =>  _x('Not Deleted!','Not success','vibe-helpdesk')
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
            'message' => _x('Unable to set Fovorite','Fovorite','vibe-helpdesk')
        );
        if(!empty($user_id) && !empty($topic_id)){
            $flag = bbp_add_user_favorite( $user_id, $body['topic_id']);
            if($flag){
                $data = array(
                    'status' => 1,
                    'data' => true,
                    'message' => _x('Topic Set as Fovorite','Topic Set as Fovorite','vibe-helpdesk')
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
            'message' => _x('Unable to set Unfavorite','Unfavorite','vibe-helpdesk')
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
        $args = json_decode($request->get_body(),true);
        $sub_action = $args['sub_action']?$args['sub_action']:'trash';
        $topic_id = $args['topic_id'];
        $user_id = (int)$this->user->id;
        /* validate here user to delete topic..  */
        if(!empty($user_id)){
            if(function_exists('bbp_get_topic')){
                $topic = bbp_get_topic( $topic_id );
                if ( empty( $topic ) ){
                    $data=array(
                        'status' => 0,
                        'data' => false,
                        'message' => _x('Topic not Exist.','Topic not Exist','vibe-helpdesk')
                    );
                }else{
                    if($user_id == $topic->post_author || user_can($user_id,'edit_posts')){
                        switch ( $sub_action ) {
                            case 'trash':
                            $success  = wp_trash_post( $topic_id );
                            if($success){
                                $message = _x('Topic trash successfull','Topic trash successfull','vibe-helpdesk');
                            }
                            break;
                        case 'untrash':
                            $success = wp_untrash_post( $topic_id );
                            if($success){
                                $message = _x('Topic untrash successfull','Topic untrash successfull','vibe-helpdesk');
                            }
                            break;
                        case 'delete':
                            $success = wp_delete_post( $topic_id );
                            if($success){
                                $message = _x('Topic delete successfull','Topic delete successfull','vibe-helpdesk');
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
                            'message' =>  _x('You are not a valid user to delete this topic','Not success','vibe-helpdesk')
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
        $data = apply_filters('VibeBbp_delete_topic_api',$data,$args);
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

    function is_topic_agent($topic_id){
        $assigned_users = $this->get_assigned_user($topic_id);
        return !empty($assigned_users) && is_array($assigned_users) && in_array($this->user->id,$assigned_users) && $this->is_agent($topic_id);
    }

    function resolve_topic($request){
        $post = json_decode($request->get_body(),true);
        $topic_id = $post['topic_id'];
        $action = $post['action'];

        $status = 0;
        $message = __('Not valid data','vibe-helpdesk');
        if(!empty($topic_id) && !empty($action)){
            
            if($this->is_topic_agent($topic_id) || $this->can_assign_topic()){
                 switch ($action) {
                    case 'resolve':
                        $resolution_time_sec = strtotime("now") - strtotime(get_post_field('post_date',$topic_id));
                        update_post_meta($topic_id,'resolved',true);
                        update_post_meta($topic_id,'resolved_time',(int)$resolution_time_sec);
                        update_post_meta($topic_id,'resolved_timestamp',(int)strtotime("now"));
                        $status = 1;
                        $message = __('Topic marked as resolved!','vibe-helpdesk');
                    break;
                    case 'unresolve':
                       delete_post_meta($topic_id,'resolved');
                       delete_post_meta($topic_id,'resolved_time');
                       delete_post_meta($topic_id,'resolved_timestamp');
                       $status = 1; 
                       $message = __('Topic marked as unresolved!','vibe-helpdesk');
                    break;
                    default:
                        $status = 0;
                        $message = __('Action missing!','vibe-helpdesk');
                    break;
                }
            }
        }
        $data = array(
            'status' => $status,
            'message' => $message
        );
        return new WP_REST_Response($data, 200); 
    }

    function can_assign_topic_label($topic_id=0){
        $can = true;
        return apply_filters('vibe_can_assign_topic_label',$can,$topic_id,$this->user); 
    }

    function assign_topic($request){
        $post = json_decode($request->get_body(),true);
        $topic_id = $post['topic_id'];
        $user_ids = $post['user_ids'];
        $action = $post['action'];
        if(!empty($topic_id) && !empty($action) && isset($user_ids) && is_array($user_ids)){
            $is_cap = $this->can_assign_topic($topic_id); // check cap here to assign
            if($is_cap){
                $topic = get_post($topic_id);
                $topic->url = get_post_permalink($topic_id);
                switch ($action) {
                    case 'assign':
                        foreach($user_ids as $key => $user_id){
                            $user_id = (int)$user_id;
                            $values = get_post_meta($topic_id,'assigned_agent');
                            if(!in_array($user_id,$values)){
                                add_post_meta($topic_id,'assigned_agent',(int)$user_id);
                                do_action('bbp_topic_assign',$topic,$this->user,$user_id,'assigned');
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
                            delete_post_meta($topic_id,'assigned_agent',(int)$user_id);
                            do_action('bbp_topic_assign',$topic,$this->user,$user_id,'unassigned');
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


    function save_canned($request){
        $post = json_decode($request->get_body(),true);
        $canned_title = $post['post_title'];
        $canned_response = $post['post_content'];
        if(!empty($canned_title) && !empty($canned_response)){
            $arr = apply_filters('vibe_save_canned_array',array(
                'post_title' => $canned_title,
                'post_content' =>  $canned_response,
                'post_type' => VIBEHELPDESK_CANNED_POST_TYPE,
                'post_status' => 'publish',
                'post_author' => $this->user->id
            ));
            remove_filter('content_save_pre', 'wp_filter_post_kses');
            remove_filter('content_filtered_save_pre', 'wp_filter_post_kses');
            if(!empty($post['id'])){
                $arr['ID'] = $post['id'];
                $post_id = wp_update_post($arr);
                if($post_id){
                    do_action('vibebp_helpdesk_canned_response_update',$arr,$this->user);    
                }
                
            }else{
                $post_id = wp_insert_post($arr);
                if($post_id){
                    do_action('vibebp_helpdesk_canned_response_added',$arr,$this->user);    
                }
            }
            add_filter('content_save_pre', 'wp_filter_post_kses');
            add_filter('content_filtered_save_pre', 'wp_filter_post_kses');

            if($post_id){
                update_post_meta($post_id,'raw',$post['raw']);
                $post =  get_post($post_id);
                $data = array(
                    'status' => 1,
                    'message' => _x('Saved as canned response','Saved as canned response','vibe-helpdesk'),
                    'data' => $post
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

    function get_canned($request){
        $args = json_decode($request->get_body(),true);
        $args = apply_filters('vibe_search_canned_array',array(
            'post_type' => VIBEHELPDESK_CANNED_POST_TYPE,
            'post_status' => 'publish',
            'posts_per_page' => 5,
            'paged'=>empty($args['paged'])?1:$args['paged'],
            'author' => $this->user->id,
            's' => empty($args['s'])?'':$args['s'],
        ));
        $query = new WP_Query($args);
        if(!empty($query->posts) && is_array($query->posts)){
            $responses = [];
            foreach ($query->posts as $key => $npost) {
                $npost->raw = get_post_meta($npost->ID,'raw',true);
                $responses[$key] = $npost;
            }
            $data = array(
                'status' => 1,
                'message' => _x('Canned responses found','Canned responses found','vibe-helpdesk'),
                'data' => $responses
            );
        }else{
            $data = array(
                'status' => 0,
                'message' => _x('Canned responses not found','Canned responses not found','vibe-helpdesk'),
            );
        }
        return new WP_REST_Response($data, 200); 
    }

    function delete_canned($request){
        $args = json_decode($request->get_body(),true);
        $data = array(
            'status' => 0,
            'message' => __('Response not deleted!','vibe-helpdesk')
        );
        
        if(!empty($args['id'])){
            if($this->user->id == get_post_field( 'post_author', $args['id'] )) {
                if(wp_trash_post( $args['id'] )){
                    $data = array(
                        'status' => 1,
                        'message' => _x('Response Deleted!','Canned response','vibe-helpdesk')
                    );
                }
            }
        }
        return new WP_REST_Response($data, 200); 
    }

    function search_sharing_values($request){
        $args = json_decode($request->get_body(),true);
        $return = array( 'status' => 0 );
        if(!empty($args['s'])){
            $search = $args['s'];
            global $wpdb;
            $results = $wpdb->get_results( "SELECT ID,display_name FROM {$wpdb->users} WHERE `user_nicename` LIKE '%{$search}%' OR 
                `user_email` LIKE '%{$search}%' OR `user_login` LIKE '%{$search}%' OR `display_name` LIKE '%{$search}%'", ARRAY_A );
            if(!empty($results)){
                $return['status']=1;
                foreach($results as $result){
                    $return['users'][]=array('id'=>(int)$result['ID'],'label'=>$result['display_name']);
                }
            }  
        }
        return new WP_REST_Response(apply_filters('vibe_helpdesk_search_sharing_values',$return,$request,$this->user), 200);
    }

    function get_dashboard_widget_data_stats($request){
        $args = json_decode($request->get_body(),true);
        $r = array( 'status' => 0 );
        if(!empty($args['role'])){
            $role = $args['role'];
            if($args['role'] === 'agent' && $this->is_agent()){
                $data =  $this->get_stats('agent');
            }elseif($args['role'] === 'supervisor' && $this->can_assign_topic()){
                $data =  $this->get_stats('supervisor');
            }else{
                $data = $this->get_stats('student');
            }
            $r = array(
                'status' => 1 ,
                'data' => $data
            );
        }
        return new WP_REST_Response($r,200);
    }

    function get_stats($role){
        $stats = array();
        $query = new WP_Query($args);
        $results = [];
        
        global $wpdb;
        $select = " SELECT COUNT(p.ID) FROM {$wpdb->posts} AS p";
        $pm1 = " INNER JOIN {$wpdb->postmeta} AS pm ON p.ID = pm.post_id";
        $pm2 = " INNER JOIN {$wpdb->postmeta} AS pm2 ON p.ID = pm2.post_id";
        $where = " WHERE p.post_type = 'topic' AND p.post_status = 'publish' ";
        $resolved_v = " AND pm.meta_key = 'resolved' AND pm.meta_value = 1";

        switch ($role) {
            case 'agent':
                $total_query = $wpdb->prepare ("
                    {$select}
                    {$pm1}
                    {$where}
                    AND pm.meta_key='assigned_agent' AND pm.meta_value = %d
                ",$this->user->id);
                $resolved_query = $wpdb->prepare ("
                    {$select}
                    {$pm1}
                    {$pm2}
                    {$where}
                    AND pm.meta_key = 'assigned_agent' AND pm.meta_value = %d 
                    AND pm2.meta_key = 'resolved' AND pm2.meta_value = 1
                ",$this->user->id);
                break;
            case 'supervisor':
                $total_query = $wpdb->prepare ("
                    {$select}
                    {$where}
                " );
                $resolved_query = $wpdb->prepare ("
                    {$select}
                    {$pm1}
                    {$where}
                    {$resolved_v}
                ");
                break;
            default: //student
                $total_query = $wpdb->prepare ("
                    {$select}
                    {$where}
                    AND p.post_author = %d
                ",$this->user->id);
                $resolved_query = $wpdb->prepare ("
                    {$select}
                    {$pm1}
                    {$where}
                    {$resolved_v}
                    AND p.post_author = %d 
                ",$this->user->id);
                break;
        }

        $total = $wpdb->get_var($total_query);
        $resolved_count = $wpdb->get_var($resolved_query);
        $stats = array(
            'total' => (int)$total,
            'resolved' => (int)$resolved_count
        );
        return $stats;
    }


    function get_dashboard_widget_data_chart($request){
        $args = json_decode($request->get_body(),true);
        $r = array( 'status' => 0 );
        if(!empty($args['role']) && !empty($args['view']) && !empty($args['start']) && !empty($args['end']) && ($args['start'] <= $args['end'])){
            $role = $args['role'];
            if($args['role'] === 'agent' && $this->is_agent()){
                $data =  $this->get_chart($args);
            }elseif($args['role'] === 'supervisor' && $this->can_assign_topic()){
                $data =  $this->get_chart($args);
            }else{
                $data = $this->get_chart($args);
            }
            $r = array(
                'status' => 1 ,
                'data' => $data
            );
        }
        return new WP_REST_Response($r,200);
    }

    function get_chart($args){
        $stats = array();
        $results = [];
        $role = $args['role'];

        $start = gmdate("Y-m-d H:i:s", $args['start']/1000);
        $end = gmdate("Y-m-d H:i:s", $args['end']/1000);
        
        global $wpdb;
        $select_val = " MONTH(p.post_date_gmt) as key1,YEAR(p.post_date_gmt) as key2";
        $where = " WHERE p.post_type='topic' AND p.post_status='publish' AND p.post_date_gmt>='{$start}' AND p.post_date_gmt<='{$end}'";
        $group_val = " MONTH(p.post_date_gmt),YEAR(p.post_date_gmt)";
        $pm1 = " INNER JOIN {$wpdb->postmeta} AS pm ON p.ID = pm.post_id";
        $pm2 = " INNER JOIN {$wpdb->postmeta} AS pm2 ON p.ID = pm2.post_id";
        switch ($args['view']) {
            case 'daily':
                $select_val = "DAY(p.post_date_gmt) as key1,MONTH(p.post_date_gmt) as key2";
                $group_val = "DAY(p.post_date_gmt),MONTH(p.post_date_gmt)";
                break;
            case 'weekly':
                $select_val = "WEEK(p.post_date_gmt) as key1,YEAR(p.post_date_gmt) as key2";
                $group_val = "WEEK(p.post_date_gmt),YEAR(p.post_date_gmt)";
            break;
            default: //yearly
                break;
        }
        $select = " SELECT ${select_val} ,count(*) as count FROM {$wpdb->posts} as p";
        $group_by = " GROUP BY {$group_val}";



        switch ($role) {
            case 'agent':
                global $wpdb;
                $query1 = $wpdb->prepare ("{$select}
                        {$pm1}
                        {$where}
                        AND meta_key='assigned_agent' AND meta_value=%d
                        {$group_by}",
                        $this->user->id
                );
                $query2 = $wpdb->prepare ("{$select}
                    {$pm1}
                    {$pm2}
                    {$where}
                    AND pm.meta_key = 'assigned_agent' AND pm.meta_value = %d AND pm2.meta_key = 'resolved' AND pm2.meta_value = 1
                    {$group_by}",
                    $this->user->id
                );
                break;
            case 'supervisor':
                $query1 = $wpdb->prepare ("{$select}{$where}{$group_by}" );
                $query2 = $wpdb->prepare ("{$select}{$pm1}{$where}
                    AND pm.meta_key = 'resolved' AND pm.meta_value = 1
                    {$group_by}"
                );
                break;
            default: //student
                $query1 = $wpdb->prepare ("{$select}
                    {$where} 
                    AND p.post_author = %d
                    {$group_by}",
                    $this->user->id
                );
                $query2 = $wpdb->prepare ("{$select}
                    {$pm1}
                    {$where} 
                    AND p.post_author = %d AND pm.meta_key = 'resolved' AND pm.meta_value = 1
                    {$group_by}",
                    $this->user->id
                );
                break;
        }

        $total = $wpdb->get_results($query1);
        $resolved = $wpdb->get_results($query2);
        $stats = array(
            'total' => $total,
            'resolved' => $resolved
        );
        return $stats;
    }
}

Vibe_HelpDesk_API::init();
