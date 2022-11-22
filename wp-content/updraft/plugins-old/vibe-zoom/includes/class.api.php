<?php
/**
 * API\
 *
 * @class       Vibe_Projects_API
 * @author      VibeThemes
 * @category    Admin
 * @package     vibemeeting
 * @version     1.0
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class Vibe_Zoom_API{


	public static $instance;
	public static function init(){

        if ( is_null( self::$instance ) )
            self::$instance = new Vibe_Zoom_API();
        return self::$instance;
    }

	private function __construct(){

		add_action('rest_api_init',array($this,'register_api_endpoints'));
	}


	function register_api_endpoints(){

		register_rest_route( VIBE_ZOOM_API_NAMESPACE, '/user/meetings', array(
            array(
                'methods'             =>  'POST',
                'callback'            =>  array( $this, 'get_meetings' ),
                'permission_callback' => array( $this, 'user_permissions_check' ),
            ),
        ));
        register_rest_route( VIBE_ZOOM_API_NAMESPACE, '/user/meetings/new', array(
            array(
                'methods'             =>  'POST',
                'callback'            =>  array( $this, 'new_meeting' ),
                'permission_callback' => array( $this, 'get_user_create_permissions_check' ),
            ),
        ));

        register_rest_route( VIBE_ZOOM_API_NAMESPACE, '/user/get_new_meeting_pre', array(
            array(
                'methods'             =>  'POST',
                'callback'            =>  array( $this, 'get_new_meeting_pre' ),
                'permission_callback' => array( $this, 'user_permissions_check' ),
            ),
        ));

        register_rest_route( VIBE_ZOOM_API_NAMESPACE, '/user/get_signature', array(
            array(
                'methods'             =>  'POST',
                'callback'            =>  array( $this, 'get_signature' ),
                'permission_callback' => array( $this, 'user_permissions_check' ),
            ),
        ));

        register_rest_route( VIBE_ZOOM_API_NAMESPACE, '/user/meetings/trash', array(
            array(
                'methods'             =>  'POST',
                'callback'            =>  array( $this, 'trash_meeting' ),
                'permission_callback' => array( $this, 'get_user_create_permissions_check' ),
            ),
        ));

        register_rest_route( VIBE_ZOOM_API_NAMESPACE, '/search', array(
            array(
                'methods'             =>  'POST',
                'callback'            =>  array( $this, 'search_sharing_values' ),
                'permission_callback' => array( $this, 'user_permissions_check' ),
            ),
        ));

        register_rest_route( VIBE_ZOOM_API_NAMESPACE, '/user/meetings/record_join_activity', array(
            array(
                'methods'             =>  'POST',
                'callback'            =>  array( $this, 'record_join_activity' ),
                'permission_callback' => array( $this, 'user_permissions_check' ),
            ),
        ));

        register_rest_route( VIBE_ZOOM_API_NAMESPACE, '/user/meetings/zoommeetings', array(
            'methods'                   =>   'POST',
            'callback'                  =>  array( $this, 'get_events_vibezoom' ),
            'permission_callback' => array( $this, 'user_permissions_check' ),
        ) );

        register_rest_route( VIBE_ZOOM_API_NAMESPACE, '/user/meetings/recordings', array(
            'methods'                   =>   'POST',
            'callback'                  =>  array( $this, 'get_meeting_recording' ),
            'permission_callback' => array( $this, 'user_permissions_check' ),
        ) );

        register_rest_route( VIBE_ZOOM_API_NAMESPACE, '/getZoomDetails', array(
            'methods'                   =>   'POST',
            'callback'                  =>  array( $this, 'get_zoom_details_for_user' ),
            'permission_callback' => array( $this, 'user_permissions_check' ),
        ) );

        register_rest_route( VIBE_ZOOM_API_NAMESPACE, '/setZoomDetails', array(
            'methods'                   =>   'POST',
            'callback'                  =>  array( $this, 'set_zoom_details_for_user' ),
            'permission_callback' => array( $this, 'user_permissions_check' ),
        ) );


    }



    function user_permissions_check($request){
        
        $body = json_decode($request->get_body(),true);
        

        if(!empty($body['token'])){
            global $wpdb;

            $this->user = apply_filters('vibebp_api_get_user_from_token','',$body['token']);
            if(!empty($this->user)){
                return true;
            }
        }

        return false;
    }

    function get_user_create_permissions_check($request){
        $body = json_decode($request->get_body(),true);
        if(!empty($body['token'])){
            global $wpdb;
            $this->user = apply_filters('vibebp_api_get_user_from_token','',$body['token']);
            if(!empty($this->user)){
                if(user_can($this->user->id,'edit_posts')){ //<---- additional security for creating , deleting and other things impacting database
                    return true;    
                }
            }
        }
        return false;
    }



    function get_meetings($request){

        
        global $wpdb,$bp;

        $args = json_decode($request->get_body(),true);
        $return = array(
            'status'=>0,
            'meetings'=>[]
        );

        $meeting_args = array(
            'post_type'=>'vibe_zoom',
            'posts_per_page'=>20,
            'paged'=>empty($args['page'])?'':$args['page'],
            's'=>empty($args['s'])?'':$args['s'],
            'orderby'=>empty($args['orderby'])?'none':$args['orderby'],
            'order'=>empty($args['order'])?'':$args['order'],
        );
        
        if(!empty($args['meeting_type']) && is_numeric($args['meeting_type'])){
            $meeting_args['tax_query']=array(
                array(
                'taxonomy'=>'meeting_type',
                'field'=>'term_id',
                'terms'=>$args['meeting_type'],
                ),
            );
        }

        $scope = $args['scope'];
        if(!empty($args['type']) && !empty($args['scope'])){
            switch($args['type']){
                case 'mine':  // manage_meeting
                    $meeting_args['author']=$this->user->id;
                break;
                case 'joined':    //shared,group
                    
                    switch($scope){
                        case 'shared':
                            $meeting_args['meta_query']['relation']= 'AND';
                            $meeting_args['meta_query'][]=array(
                                'key'=>'shared_type',
                                'value'=>'shared',
                                'compare'=>'='
                            );
    
                            $meeting_args['meta_query'][]= array(
                                'key'=>'shared_values',
                                'value'=>$this->user->id,
                                'compare'=>'='
                            );
                        break;
                        case 'course':
                            $meeting_args['meta_query']['relation']= 'AND';
                            $meeting_args['meta_query'][]=array(
                                'key'=>'shared_type',
                                'value'=>'course',
                                'compare'=>'='
                            );
                            $courses = bp_course_get_user_courses($this->user->id,'active');

                            if(empty($courses)){
                                $courses = [1999999];
                            }
                            $meeting_args['meta_query'][]= array(
                                'key'=>'shared_values',
                                'value'=> $courses,
                                'compare'=>'IN'
                            );
                        break;
                        case 'group':
                            $meeting_args['meta_query']['relation']= 'AND';
                            $meeting_args['meta_query'][]=array(
                                'key'=>'shared_type',
                                'value'=>'group',
                                'compare'=>'='
                            );
                           
                            $mygroups = $wpdb->get_results($wpdb->prepare("
                                SELECT group_id as id
                                FROM {$bp->groups->table_name_members} 
                                WHERE user_id = %d",
                                $this->user->id),ARRAY_A);
    
                            $nmygroups = array();
                            if(!empty($mygroups) && is_array($mygroups)){
                                foreach ($mygroups as $value) {$nmygroups[] = $value['id'];}
                            }else{  $nmygroups[] = 19999999;} //force empty 
                            
                            $meeting_args['meta_query'][]= array(
                                'key'=>'shared_values',
                                'value'=> $nmygroups,
                                'compare'=>'IN'
                            );
                        break;
                    }
                break;
            }
            $meeting_args = apply_filters('vibe_zoom_get_meetings_scope',$meeting_args,$args,$this->user->id);
            
            $meetings = new WP_Query($meeting_args);
            $return = array(
                'status'=>1,
                'meetings'=>[]
            );

            if($meetings->have_posts()){
                $return['total']=(int)$meetings->found_posts;
                while($meetings->have_posts()){
                    $meetings->the_post();
                    global $post;
                    
                    $meeting_details = get_post_meta(get_the_ID(),'vibe_zoom_meeting_details',true);
                    $meta = get_post_meta(get_the_ID(),'vibe_zoom_meeting_settings',true);
                    
                    $meeting=array(
                        'id'=>get_the_ID(),
                        'post_title'=>get_the_title(),
                        'post_date'=>get_the_date(),
                        'post_content'=>get_the_content(),
                        'post_author'=>$post->post_author,
                        'meta' => $meta,
                        'shared' => $this->get_shared_details(get_the_ID()),
                        'meeting_details' => $this->get_meeting_details($post->ID,$post->post_author)
                    );
                    $return['meetings'][]=$meeting;
                }
            }else{
                return new WP_REST_Response($return, 200);
            }
        }
        
        return new WP_REST_Response($return, 200);
    }

    function get_meeting_by_id($post_id){
        $post = get_post($post_id);
        $meta = get_post_meta($post_id,'vibe_zoom_meeting_settings',true);
        $meeting=array(
            'id'=>$post_id,
            'post_title'=>$post->$post_title,
            'post_date'=>$post->$post_date,
            'post_content'=>$post->$post_content,
            'post_author'=>$post->post_author,
            'meta' => $meta,
            'shared' => $this->get_shared_details($post_id),
            'meeting_details' => $this->get_meeting_details($post->ID,$post->post_author)
        );
        return $meeting;
    }

    function get_shared_details($id){
        $shared_values = get_post_meta($id,'shared_values');
        $shared_type = get_post_meta($id,'shared_type',true);
        return array(
            'shared_type' => empty($shared_type)?'shared':$shared_type,
            'shared_values' => empty($shared_values)?array():$shared_values
        );
    }

    function get_meeting_details($id,$post_author=0){
        $details = get_post_meta($id,'vibe_zoom_meeting_details',true);
        
        if(!empty($details) && isset($details['start_url']) && isset($details['join_url']) &&isset($details['start_time']) && isset($details['duration'])){
            $start_timestamp = strtotime($details['start_time'])*1000;
            $end_timestamp = (strtotime($details['start_time']) + $details['duration']*60 )*1000;
            $time = time() * 1000;
            $can_join = false;
            if($time>=$start_timestamp && $time<=$end_timestamp){
                $can_join = true;
            }
            $meeting_id = $details['id'];
            $arr= array(
                'start' => $start_timestamp, //actual timestamp,
                'end' => $end_timestamp,
                'duration' => $details['duration'],
                'timezone' => $details['timezone'],
                'meeting_id' => $meeting_id
            );
            if($can_join){
                $arr['password'] = $details['password'];
                $host = 0; 
                $key_secret = get_zoom_meeting_key_secret($id);
                if(!empty($this->user->id) && $this->user->id == $post_author){ 
                    $host = 1;
                    $arr['join_url'] = $details['start_url'];
                }else{
                    $arr['join_url'] = $details['join_url'];
                }
                
                $signature =  zoom_generate_signature($key_secret['api_key'],$key_secret['api_secret'],$meeting_id,$host);
                $arr = array_merge($arr,array(
                    'signature' => $signature,
                    'api_key' => $key_secret['api_key']
                ));
            }
            return $arr;
        }
        return false;
    }



    function get_new_meeting_pre($request){
        $args = json_decode($request->get_body(),true);

        $vibe_zoom = Vibe_Zoom::init();

        $multi_zoom_credential = get_option('multi_zoom_credential');
        if(!is_array($multi_zoom_credential)){
            $multi_zoom_credential = array();
        }
        $multihosts = array(
            array(
                'key' => '',
                'title' => __('Admin(default)','vibe-zoom'),
            )
        );
        foreach ($multi_zoom_credential as $key => $value) {
            if(!empty($value['api_key']) && !empty($value['api_secret'])){
                $multihosts[] = array(
                    'key' => $value['key'],
                    'title' => $value['title']
                );
            }
        }

        $arr = array(
            'time_zones' => $vibe_zoom->vibe_zoom_get_timezone_options(),
            'hosts' => apply_filters('get_new_meeting_pre_hosts',$vibe_zoom->video_conferencing_zoom_api_get_user_transients(),$args,$this->user->id),
            'multihosts' => $multihosts
        );
        return new WP_REST_Response(apply_filters('get_new_meeting_pre',$arr,$request,$this->user->id), 200);  
    }

    function get_meeting_url($post_id,$user_id){
        if($user_id !== get_post_field('post_author',$post_id)){
            //return user meeting url
        }else{
            //return author meeting url
        }
    }

    function new_meeting($request){
        $args = json_decode($request->get_body(),true);

        if(!empty($this->user) && class_exists('Vibe_Zoom_Video_Conferencing_Api') && !empty($args['post_title']) &&  !empty($args['meta']['start_date']) && !empty($args['meta']['duration'])){
            
            $meeting_args = array(
                'post_type'=>'vibe_zoom',
                'post_status'=>'publish',
                'post_title'=>$args['post_title'],
                'post_content'=>$args['post_content'],
                'post_author'=>$this->user->id
            );
            if(!empty($args['id'])){
                if($this->user->id !== get_post_field('post_author',$args['id'])){
                    return new WP_REST_Response(array('status'=>0,'message'=>__('Meeting author does not match.','vibe-zoom')), 200);
                }
                $post_id = intval($args['id']);
                $meeting_args['ID'] = $post_id;
                $server_meeting = get_post_meta($post_id,'vibe_zoom_meeting_details',true);
                $server_meeting_id  = 0;
                if(!empty($server_meeting)){
                    $server_meeting_id = $server_meeting['id'];
                }

                $meeting_created = $this->create_edit_zoom_server_meeting($args,$server_meeting_id,$this->user->id);

                if(!empty($meeting_created['id'])){
                    $update = wp_update_post($meeting_args);
                    if(empty($update)){
                        return new WP_REST_Response(array('status'=>0,'message'=>__('Meeting could not be updated.','vibe-zoom')),200);
                    }
                    update_post_meta($post_id,'vibe_zoom_meeting_details',$meeting_created);
                    do_action('wplms_zoom_meeting_edited',$post_id,$this->user->id); 
                }else{
                    return new WP_REST_Response(array('status'=>0,'message'=>__('Meeting could not be updated on server.','vibe-zoom'),'debug'=>$meeting_created),200);
                } 
            }else{
                $meeting_created = $this->create_edit_zoom_server_meeting($args,'',$this->user->id);
                if(!empty($meeting_created)){
                    $post_id = wp_insert_post($meeting_args);
                    if(empty($post_id)){
                        return new WP_REST_Response(array('status'=>0,'message'=>__('Meeting could not be created.','vibe-zoom')),200);
                    }
                    $args['id'] = $post_id;
                    update_post_meta($post_id,'vibe_zoom_meeting_details',$meeting_created);
                    do_action('wplms_zoom_meeting_created',$post_id,$this->user->id);     
                }else{
                    return new WP_REST_Response(array('status'=>0,'message'=>__('Meeting could not be created on server.','vibe-zoom')),200);
                }
            }
            

            if(!empty($post_id)){
                if(!empty($args['meta'])){
                    foreach ($args['meta'] as $key => $value) {
                        if($key == 'start_date'){ // forcalendar search
                            $start = strtotime($meeting_created['start_time']); //actual timestamp
                            update_post_meta($post_id,'start',$start);
                            update_post_meta($post_id,'end',$start+($meeting_created['duration']*60));
                        }
                        update_post_meta($post_id,$key,$value);
                    }
                    // all settings in one
                    update_post_meta($post_id,'vibe_zoom_meeting_settings',$args['meta']);
                }
                
                do_action('wplms_zoom_meeting_updated',$post_id,$this->user->id);
                
                if(!empty($args['shared']['shared_type'])){
                    update_post_meta($post_id,'shared_type',$args['shared']['shared_type']);
                    delete_post_meta($post_id,'shared_values');
                    if(!empty($args['shared']['shared_values']) && is_array($args['shared']['shared_values'])){
                        foreach ($args['shared']['shared_values'] as $val) {
                           add_post_meta($post_id,'shared_values',(int)$val);
                        }
                        do_action('wplms_zoom_meeting_connected',$post_id,$this->user->id,$args['shared']['shared_type'],$args['shared']['shared_values']);
                    }
                }else{
                    update_post_meta($post_id,'shared_type','');
                }
                
            }
            return new WP_REST_Response(array('status'=>1,'meeting'=>$args,'followermessage'=>sprintf(__('%s published a new meeting %s','vibe-zoom'),$this->user->displayname,get_the_title($meeting_args['id']))), 200);
        }
        return new WP_REST_Response(array(), 401);
    }



    function create_edit_zoom_server_meeting($args,$server_meeting_id=null,$user_id=0){
        //create and update server meeting
        $moderator_code = $args['meta']['password']; 
        $option_auto_recording = $args['meta']['auto_recording'];
        $host_video     = $args['meta']['host_video'];
        $participant_video     = $args['meta']['participant_video'];
        $join_before_host     = $args['meta']['join_before_host'];
        $mute_upon_entry     = $args['meta']['mute_upon_entry'];
        $start_date = $args['meta']['start_date'];
        $duration = ! empty( $args['meta']['duration'] ) ? $args['meta']['duration'] : 60;
        $host = $args['meta']['host'];
        $alternative_host_ids = $args['meta']['alternative_host_ids'];
        $enforce_login =  $args['meta']['enforce_login'];
        $meetingTopic = $args['post_title'];
        // creating array
        $mtg_param = array(
            'userId'                    => $host,
            'meetingTopic'              => $meetingTopic,
            'start_date'                => sanitize_text_field( $start_date ),
            'timezone'                  => sanitize_text_field($args['meta']['time_zone'] ),
            'duration'                  => $duration,
            'password'                  => $moderator_code,
            'join_before_host'          => $join_before_host,
            'option_host_video'         => $host_video,
            'option_participants_video' => $participant_video,
            'option_mute_participants'  => $mute_upon_entry,
            'option_auto_recording'     => $option_auto_recording,
            'option_enforce_login'      => $enforce_login
        );

        $vibe_zoom_api_init = get_zoom_api_object($args['meta']['multihostkey']);
        
        if(!empty($server_meeting_id)){
            //update_meeting
            $mtg_param['meeting_id'] = $server_meeting_id;
            $temp = $vibe_zoom_api_init->updateMeetingInfo( $mtg_param );
            $meeting_created = json_decode( $temp,true );
            if ( empty( $meeting_created->error )) {
                $meeting_info = json_decode( $vibe_zoom_api_init->getMeetingInfo( $server_meeting_id ) ,true);
                if(!empty($meeting_info)){
                    return $meeting_info;
                }
            }
            return false;
        }else{
            //create meeting
            $meeting_created = json_decode( $vibe_zoom_api_init->createAMeeting( $mtg_param ),true );
            if ( empty( $meeting_created->error ) && !empty($meeting_created['id'])) {
                return $meeting_created;
            } 
            return $meeting_created;
        }
    }

    function trash_meeting($request){
        $args = json_decode($request->get_body(),true);
        if($this->user->id === get_post_field('post_author',$args['meeting_id'])){
            if(wp_trash_post($args['meeting_id'])){
                return new WP_REST_Response(array('status'=>1,'message'=>__('Moved to trash','vibe-zoom')), 200);
            }else{
                return new WP_REST_Response(array('status'=>1,'message'=>__('Can not be moved to trash!','vibe-zoom')), 200);
            }
        }
    }

    function search_sharing_values($request){
        $args = json_decode($request->get_body(),true);
        $return = array( 'status' => 1,'values'=>[] );
        if(!empty($args['s']) && !empty($args['shared_type'])){
            $scope = $args['shared_type'];
            $search = $args['s'];
            switch($scope){
                case 'personal':
                    global $wpdb;
                    $results = $wpdb->get_results( "SELECT ID,display_name FROM {$wpdb->users} WHERE `user_nicename` LIKE '%{$search}%' OR 
                        `user_email` LIKE '%{$search}%' OR `user_login` LIKE '%{$search}%' OR `display_name` LIKE '%{$search}%'", ARRAY_A );
                    if(!empty($results)){
                        $return['status']=1;
                        foreach($results as $result){
                            $return['values'][]=array('id'=>$result['ID'],'label'=>$result['display_name']);
                        }
                    }
                break;
                case 'shared':
                    global $wpdb;
                    $results = $wpdb->get_results( "SELECT ID,display_name FROM {$wpdb->users} WHERE `user_nicename` LIKE '%{$search}%' OR 
                        `user_email` LIKE '%{$search}%' OR `user_login` LIKE '%{$search}%' OR `display_name` LIKE '%{$search}%'", ARRAY_A );
                    if(!empty($results)){
                        $return['status']=1;
                        foreach($results as $result){
                            $return['values'][]=array('id'=>$result['ID'],'label'=>$result['display_name']);
                        }
                    }
                break;
                case 'group':
                    if(function_exists('bp_is_active') && bp_is_active('groups')){
                        global $wpdb, $bp;
                        $results = $wpdb->get_results( "SELECT id,name FROM {$bp->groups->table_name} WHERE `name` LIKE '%{$search}%' OR 
                            `slug` LIKE '%{$search}%'", ARRAY_A );
                        if(!empty($results)){
                            $return['status']=1;
                            foreach($results as $result){
                                $return['values'][]=array('id'=>$result['id'],'label'=>$result['name']);
                            }
                        }
                    }
               break;
               case 'course':
                    
                    global $wpdb, $bp;
                    $results = $wpdb->get_results( "SELECT ID,post_title FROM {$wpdb->posts} WHERE `post_type` = 'course' AND (`post_title` LIKE '%{$search}%' OR 
                        `post_name` LIKE '%{$search}%')", ARRAY_A );
                    if(!empty($results)){
                        $return['status']=1;
                        foreach($results as $result){
                            $return['values'][]=array('id'=>$result['ID'],'label'=>$result['post_title']);
                        }
                    }
               break;
            }
        }
        return new WP_REST_Response(apply_filters('vibe_zoom_search_sharing_values',$return,$request,$this->user), 200);
    }

    function record_join_activity($request){
        $args = json_decode($request->get_body(),true);
        $post_id = $args['id'];
        $return = array(
            'status'=>0,
            'message'=>__('Join activity not recorded','vibe-zoom')
        );
        if(!empty($post_id)){
            if(function_exists('bp_activity_add')){
                do_action('wplms_zoom_record_join_activity',$post_id,$this->user->id);
                $return = array(
                    'status'=>1,
                    'message'=>__('Join activity recorded','vibe-zoom')
                );
            }
        }
        return new WP_REST_Response($return, 200);
    }


    function get_events_vibezoom($request){
        $body = json_decode($request->get_body(),true);
        $filter = $body['filter'];
        $results = array();
        $return = array(
            'status' => 0,
            'message' => _x('No Meeting found!','No Meeting found!','api','vibe-zoom')
        );
        if(class_exists( 'Vibe_Zoom' )){
            if(isset($filter) && $filter['start'] && $filter['end']){
                // Query build
                global $wpdb,$bp;

                //share type:user shared
                $query = "SELECT p1.post_id  FROM {$wpdb->postmeta} as p1
                    LEFT JOIN {$wpdb->postmeta} as p2 On p1.post_id = p2.post_id
                    WHERE p1.meta_key LIKE 'shared_type' AND p1.meta_value LIKE 'shared'
                    AND p2.meta_key LIKE 'shared_values' AND p2.meta_value = {$this->user->id}";
                $results1 = $wpdb->get_results($query,'ARRAY_A');
                if(empty($results1)){ $results1 = array(); }

                //group type : group shared
                $mygroups = $wpdb->get_results($wpdb->prepare("
                SELECT group_id as id
                FROM {$bp->groups->table_name_members} 
                WHERE user_id = %d",
                $this->user->id),ARRAY_A);
                $nmygroups = array();
                if(!empty($mygroups) && is_array($mygroups)){
                    foreach ($mygroups as $value) {$nmygroups[] = $value['id'];}
                }
                $str_in = '('.implode(',',$nmygroups).')';
                // group id shared post ids making array
                $query = "SELECT p1.post_id  FROM {$wpdb->postmeta} as p1
                LEFT JOIN {$wpdb->postmeta} as p2 On p1.post_id = p2.post_id
                WHERE p1.meta_key LIKE 'shared_type' AND p1.meta_value LIKE 'group'
                AND p2.meta_key LIKE 'shared_values' AND p2.meta_value IN {$str_in}";
                $results2 = $wpdb->get_results($query,'ARRAY_A');
                if(empty($results2)){ $results2 = array(); }


                // course shared
                $courses = bp_course_get_user_courses($this->user->id,'active');
                $results3 = [];
                if(!empty($courses) && is_array($courses)){
                    $str_in = '('.implode(',',$courses).')';
                    // course id shared post ids making array
                    $query = "SELECT p1.post_id  FROM {$wpdb->postmeta} as p1
                    LEFT JOIN {$wpdb->postmeta} as p2 On p1.post_id = p2.post_id
                    WHERE p1.meta_key LIKE 'shared_type' AND p1.meta_value LIKE 'course'
                    AND p2.meta_key LIKE 'shared_values' AND p2.meta_value IN {$str_in}";
                    $results3 = $wpdb->get_results($query,'ARRAY_A');
                    if(empty($results3)){ $results3 = array(); }
                }
            
                $results = array_merge($results1,$results2,$results3);
            
                $post_in = array(); // all shared meeting ids
                if(!empty($results) && is_array($results)){
                    foreach ($results as $key => $value) { $post_in[] = $value['post_id']; }
                }
                $post_in = array_unique($post_in);

                // no meeting is found
                if(empty($post_in)){
                    return new WP_REST_Response($return, 200); 
                }

                $args = array(
                    'post_type'=>'vibe_zoom',
                    's'=>!empty($body['s'])?$body['s']:'',
                    'post__in' => $post_in ,
                    'meta_query'=>array(
                        'meta_query'=>array(
                        'relation'=>'AND', 
                            array(
                                'key'=>'start',
                                'value'=>$filter['end'],
                                'compare'=>'<='
                            ),
                            array(
                                'key'=>'end',
                                'value'=>$filter['start'],
                                'compare'=>'>='
                            ),
                        )
                    )
                );

                $query = new WP_Query(apply_filters('vibe_calendar_zoom_args',$args,$this->user,$body));
                $results = [];
                if($query->have_posts()){
                    while($query->have_posts()){
                        $query->the_post();
                        global $post;
                        
                        $results[]=array(
                            'id'=>$post->ID,
                            'post_title'=>$post->post_title,
                            'post_content'=>$post->post_content,
                            'post_author'=>$post->post_author,
                            'meta' => $this->get_vibezoom_meta($post->ID),
                            'meeting_details' => $this->get_meeting_details($post->ID,$post->post_author)
                        );
                    }
                    $data = array(
                        'status' => 1,
                        'data' => $results,
                        'total'=>$query->found_posts,
                        'message' => _x('Vibe Zoom Meeting found','Vibe Zoom Meeting found','vibe-zoom'),
                    );                
                }else{
                    $data = $return;
                }
            }else{
                $data = array(
                    'status' => 0,
                    'message' => _x('Data missing!','Data missing!','vibe-zoom')
                );
            }
        }else{
            $data = array(
                'status' => 0,
                'message' => _x('Vibe Zoom Plugin not active!','Vibe Zoo Plugin not active!','vibe-zoom')
            );
        }
        return new WP_REST_Response(apply_filters('vibe_get_events_vibezoom',$data,$request), 200);
    }


    function get_vibezoom_meta($id){
        $color = get_post_meta($id,'evcal_event_color',true);
        return array(
            array('meta_key'=>'start','meta_value'=>(int)get_post_meta($id,'start',true) * 1000),
            array('meta_key'=>'end','meta_value'=>(int)get_post_meta($id,'end',true)  * 1000),
            array('meta_key'=>'color','meta_value'=>apply_filters('vibe_zoom_color','#FF5B5C'))
        );
    }

    function get_meeting_recording($request){
        $body = json_decode($request->get_body(),true);
        $id = $body['id'];
        $return  = array(
            'status'=>0
        );
        if(!empty($id)){
            $show_recordings = get_post_meta($id,'show_recordings',true);
            $is_author = $this->user->id == get_post_field('post_author',$body['id']);
            if(!($show_recordings || $is_author)){
                return new WP_REST_Response(array('status'=>0,'message'=>__('Meeting author or access does not match.','vibe-zoom')), 200);
            }
            $details = get_post_meta($id,'vibe_zoom_meeting_details',true);
            $meeting_id =   $details['id'];
            if(!empty($meeting_id)){
                $recordings = get_vibe_zoom_recordings($id,$meeting_id);
                $return = array(
                    'status' => 1,
                    'data' => json_decode($recordings,true)
                );
            } 
        }
        return new WP_REST_Response($return, 200);
    }

    function get_zoom_details_for_user($request){
        $return  = array(
            'status'=>1,
            'data' => get_user_meta($this->user->id,'vibe_zoom_credential',true)
        );
        return new WP_REST_Response($return, 200);
    }

    function set_zoom_details_for_user($request){
        $body = json_decode($request->get_body(),true);
        $credential = $body['credential'];
        $return  = array(
            'status'=>1,
        );
        update_user_meta($this->user->id,'vibe_zoom_credential',$credential);
        return new WP_REST_Response($return, 200);
    }

}

Vibe_Zoom_API::init();