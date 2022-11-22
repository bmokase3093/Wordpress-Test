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


class Vibe_Bbb_API{


	public static $instance;
	public static function init(){

        if ( is_null( self::$instance ) )
            self::$instance = new Vibe_Bbb_API();
        return self::$instance;
    }

	private function __construct(){

		add_action('rest_api_init',array($this,'register_api_endpoints'));
	}


	function register_api_endpoints(){

		register_rest_route( VIBE_BBB_API_NAMESPACE, '/user/meetings', array(
            array(
                'methods'             =>  'POST',
                'callback'            =>  array( $this, 'get_meetings' ),
                'permission_callback' => array( $this, 'user_permissions_check' ),
            ),
        ));
        register_rest_route( VIBE_BBB_API_NAMESPACE, '/user/meetings/new', array(
            array(
                'methods'             =>  'POST',
                'callback'            =>  array( $this, 'new_meeting' ),
                'permission_callback' => array( $this, 'get_user_create_permissions_check' ),
            ),
        ));

        register_rest_route( VIBE_BBB_API_NAMESPACE, '/user/meetings/get_meeting_url', array(
            array(
                'methods'             =>  'POST',
                'callback'            =>  array( $this, 'get_meeting_url' ),
                'permission_callback' => array( $this, 'user_permissions_check' ),
            ),
        ));

        register_rest_route( VIBE_BBB_API_NAMESPACE, '/user/meetings/record_join_activity', array(
            array(
                'methods'             =>  'POST',
                'callback'            =>  array( $this, 'record_join_activity' ),
                'permission_callback' => array( $this, 'user_permissions_check' ),
            ),
        ));

        register_rest_route( VIBE_BBB_API_NAMESPACE, '/user/meetings/trash', array(
            array(
                'methods'             =>  'POST',
                'callback'            =>  array( $this, 'trash_meeting' ),
                'permission_callback' => array( $this, 'get_user_create_permissions_check' ),
            ),
        ));

        register_rest_route( VIBE_BBB_API_NAMESPACE, '/search', array(
            array(
                'methods'             =>  'POST',
                'callback'            =>  array( $this, 'search_sharing_values' ),
                'permission_callback' => array( $this, 'user_permissions_check' ),
            ),
        ));

        register_rest_route( VIBE_BBB_API_NAMESPACE, '/user/meetings/bbbmeetings', array(
            'methods'                   =>   'POST',
            'callback'                  =>  array( $this, 'get_events_vibebbb' ),
            'permission_callback' => array( $this, 'user_permissions_check' ),
        ) );

        register_rest_route( VIBE_BBB_API_NAMESPACE, '/user/meetings/recordings', array(
            'methods'                   =>   'POST',
            'callback'                  =>  array( $this, 'get_meeting_recording' ),
            'permission_callback' => array( $this, 'user_permissions_check' ),
        ) );

    }



    function user_permissions_check($request){
        
        $body = json_decode($request->get_body(),true);
        if (empty($body['token'])){
            $client_id = $request->get_param('client_id');
            if($client_id == vibebp_get_setting('client_id')){
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

        if(empty($this->user)){
            new WP_REST_Response(array(), 401);
        }
        global $wpdb,$bp;


        $args = json_decode($request->get_body(),true);
        $return = array(
            'status'=>0,
            'meetings'=>[]
        );

        $meeting_args = array(
            'post_type'=>'bbb-room',
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
            $meetings = new WP_Query(apply_filters('vibe_bbb_get_meetings_scope',$meeting_args,$args,$this->user->id));
            $return = array(
                'status'=>1,
                'meetings'=>[]
            );

            if($meetings->have_posts()){
                $return['total']=(int)$meetings->found_posts;
                while($meetings->have_posts()){
                    $meetings->the_post();
                    global $post;
                    $meeting=array(
                        'id'=>get_the_ID(),
                        'post_title'=>get_the_title(),
                        'post_date'=>get_the_date(),
                        'post_content'=>get_the_content(),
                        'post_author'=>$post->post_author,
                        'meta' => get_post_meta(get_the_ID(),'vibe_bbb_meeting_settings',true),
                        'shared' => $this->get_shared_details(get_the_ID()),
                        'meeting_details' => $this->get_meeting_details($post->ID,$post->post_author)
                    );
                    $return['meetings'][]=$meeting;
                }
            }
        }else{
            $return = array(
                'status'=>0,
                'meetings'=>[]
            );
        }    
        return new WP_REST_Response($return, 200);
    }

    function get_shared_details($id){
        $shared_values = get_post_meta($id,'shared_values');
        $shared_type = get_post_meta($id,'shared_type',true);
        return array(
            'shared_type' => empty($shared_type)?'shared':$shared_type,
            'shared_values' => empty($shared_values)?array():$shared_values
        );
    }

    function get_meeting_url($request){
        $args = json_decode($request->get_body(),true);
        $post_id = $args['id'];
        $return = array(
            'status'=>0,
            'url' =>'',
            'message'=>__('Meeting url not found!','vibe-bbb')
        );
        if(!empty($post_id)){
            $meeting_url  = $this->get_meeting_url_by_id($post_id,$this->user->id);
            if(!empty($meeting_url)){
                $return = array(
                    'status'=>1,
                    'url' =>$meeting_url,
                    'message'=>__('Meeting url found!','vibe-bbb')
                );
            }
        }
        return new WP_REST_Response($return, 200);
    }

    function get_meeting_url_by_id($post_id,$user_id,$force_get_url=false){
        $url = '';
        if($user_id === get_post_field('post_author',$post_id)){
            $url = Bigbluebutton_Api::get_join_meeting_url($post_id,bp_core_get_user_displayname($user_id),get_post_meta($post_id,'bbb-room-moderator-code',true),get_post_meta($post_id,'logoutUrl',true));  
        }else{
            if($this->can_view_post($post_id) || $force_get_url){
                $url = Bigbluebutton_Api::get_join_meeting_url($post_id,bp_core_get_user_displayname($user_id),get_post_meta($post_id,'bbb-room-viewer-code',true),get_post_meta($post_id,'logoutUrl',true));    
            }
        }
        return $url;
    }


    function get_meeting_details($id,$post_author=0){
        $start = (int)get_post_meta($id,'start',true);
        $end = (int)get_post_meta($id,'end',true);
        $time = time() * 1000;
        $can_join = false;
        if($time>=$start && $time<=$end){
            $can_join = true;
        }
        $arr= array( 
            'start' => $start,
            'end' => $end,
            'meeting_id' => get_post_meta($id,'bbb-room-meeting-id',true),
            'can_join' => $can_join
        );
        return $arr;
    }


    function new_meeting($request){
        $args = json_decode($request->get_body(),true);

        if(!empty($this->user) && class_exists('Bigbluebutton_Api') && !empty($args['post_title']) && $args['meta']['start'] &&  $args['meta']['duration'] && $args['meta']['duration_value'] ){
            
            $meeting_args = array(
                'post_type'=>'bbb-room',
                'post_status'=>'publish',
                'post_title'=>$args['post_title'],
                'post_content'=>$args['post_content'],
                'post_author'=>$this->user->id
            );
            if(!empty($args['id'])){
                if($this->user->id !== get_post_field('post_author',$args['id'])){
                    return new WP_REST_Response(array('status'=>0,'message'=>__('Meeting author does not match.','vibe-bbb')), 200);
                }
                $post_id = intval($args['id']);
                $meeting_args['ID'] = $post_id;
                $update = wp_update_post($meeting_args);
                if(empty($update)){
                    return new WP_REST_Response(array('status'=>0,'message'=>__('Meeting could not be updated.','vibe-bbb')),200);
                }
            }else{
                $post_id = wp_insert_post($meeting_args);
                if(empty($post_id)){
                    return new WP_REST_Response(array('status'=>0,'message'=>__('Meeting could not be created.','vibe-bbb')),200);
                }
                $args['id'] = $post_id;   
                do_action('wplms_bbb_meeting_created',$post_id,$this->user->id);   
            }
            if(!empty($post_id)){
                if ( ! get_post_meta( $post_id, 'bbb-room-meeting-id', true ) ) {
                    update_post_meta( $post_id, 'bbb-room-meeting-id', sha1( home_url() . Bigbluebutton_Admin_Helper::generate_random_code( 12 ) ) );
                }
                if(!empty($args['meta'])){
                    foreach ($args['meta'] as $key => $value) {
                        update_post_meta($post_id,$key,$value);
                    }
                    $end = $args['meta']['start'] + ($args['meta']['duration']*$args['meta']['duration_value']*1000); // end value update
                    update_post_meta($post_id,'end',$end);
                    update_post_meta($post_id,'vibe_bbb_meeting_settings',$args['meta']);// all settings in one
                }

                // create/update meeting
                $logout_url = apply_filters('vibebbb_logout_redirect_url',get_post_meta($post_id,'logoutUrl',true),$post_id);
                if(empty($logout_url)){ $logout_url = get_site_url($post_id); }
                $is_created = $this->create_edit_bbb_server_meeting($post_id,$logout_url);
                if($is_created !== 200){
                    return new WP_REST_Response(array('status'=>0,'message'=>__('Meeting could not be updated on server.','vibe-bbb')),200);
                }

                
                do_action('wplms_bbb_meeting_updated',$post_id,$this->user->id); //cron

                if(!empty($args['shared']['shared_type'])){
                    update_post_meta($post_id,'shared_type',$args['shared']['shared_type']);
                    delete_post_meta($post_id,'shared_values');
                    if(!empty($args['shared']['shared_values']) && is_array($args['shared']['shared_values'])){
                        foreach ($args['shared']['shared_values'] as $key => $id) {
                           add_post_meta($post_id,'shared_values',(int)$id);
                        }
                        do_action('wplms_bbb_meeting_connected',$post_id,$this->user->id,$args['shared']['shared_type'],$args['shared']['shared_values']);
                    }
                }else{
                    update_post_meta($post_id,'shared_type','');
                } 
            }
            return new WP_REST_Response(array('status'=>1,'meeting'=>$args,'followermessage'=>sprintf(__('%s published a new meeting %s','vibe-bbb'),$this->user->display_name,get_the_title($meeting_args['id']))), 200);
        }
        return new WP_REST_Response(array(), 401);
    }



    function create_edit_bbb_server_meeting($post_id,$logout_url=null){
        return Bigbluebutton_Api::create_meeting($post_id,$logout_url);
    }

    function trash_meeting($request){
        $args = json_decode($request->get_body(),true);
        if($this->user->id === get_post_field('post_author',$args['id']) || user_can($this->user->id,'manage_options')){
            if(wp_trash_post($args['id'])){
                return new WP_REST_Response(array('status'=>1,'message'=>__('Moved to trash','vibe-bbb')), 200);
            }else{
                return new WP_REST_Response(array('status'=>1,'message'=>__('Can not be moved to trash!','vibe-bbb')), 200);
            }
        }
    }

    function search_sharing_values($request){
        $args = json_decode($request->get_body(),true);
        $return = array( 'status' => 0 );
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
        return new WP_REST_Response(apply_filters('vibe_bbb_search_sharing_values',$return,$request,$this->user), 200);
    }


    function can_view_post($post_id){
        
        $shared_type = get_post_meta($post_id,'shared_type',true);
        if(empty($shared_type)){
            return false;
        }
        
        $shared_values = get_post_meta($post_id,'shared_values',false);
        if(empty($shared_values) || !is_array($shared_values)){
            return false;
        }
        
        switch ($shared_type) {
            case 'shared':
                return in_array($this->user->id,$shared_values);
                break;
            case 'group':
                global $wpdb,$bp;
                $mygroups = $wpdb->get_results($wpdb->prepare("
                    SELECT group_id as id
                    FROM {$bp->groups->table_name_members} 
                    WHERE user_id = %d",
                    $this->user->id),ARRAY_A);
                if(!empty($mygroups)){
                    $nmygroups = array();

                    if(!empty($mygroups) && is_array($mygroups)){
                        foreach ($mygroups as $value) {
                            $nmygroups[] = $value['id'];
                        }
                    }

                    foreach ($nmygroups as $key => $value) {
                        $exist = in_array($value,$shared_values);
                        if($exist){
                            return true;
                        }
                    }
                }
                return false;
                break;
            case 'course':
            case 'courses':
                $courses = bp_course_get_user_courses($this->user->id,'active');
                if(!empty($courses)){
                    foreach ($courses as $key => $value) {
                        if(in_array($value,$shared_values)){
                            return true;
                        }
                    }
                }
                return false;
                break;
            default:
                    return false;
                break;
        }
        return false;
    }

    function record_join_activity($request){
        $args = json_decode($request->get_body(),true);
        $post_id = $args['id'];
        $return = array(
            'status'=>0,
            'message'=>__('Join activity not recorded','vibe-bbb')
        );
        if(!empty($post_id)){
            if(function_exists('bp_activity_add')){
                do_action('wplms_bbb_record_join_activity',$post_id,$this->user->id);
                $return = array(
                    'status'=>1,
                    'message'=>__('Join activity recorded','vibe-bbb')
                );
            }
        }
        return new WP_REST_Response($return, 200);
    }


    function get_events_vibebbb($request){
        $body = json_decode($request->get_body(),true);
        $filter = $body['filter'];
        $results = array();
        $return = array(
            'status' => 0,
            'message' => _x('No Meeting found!','No Meeting found!','vibe-bbb')
        );
        
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
                'post_type'=>'bbb-room',
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

            $query = new WP_Query(apply_filters('vibe_calendar_bbb_args',$args,$this->user,$body));
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
                        'meta' => $this->get_vibebbb_meta($post->ID),
                        'meeting_details' => $this->get_meeting_details($post->ID,$post->post_author)
                    );
                }
                $data = array(
                    'status' => 1,
                    'data' => $results,
                    'total'=>$query->found_posts,
                    'message' => _x('Vibe Bbb Meeting found','Vibe Bbb Meeting found','vibe-bbb'),
                );                
            }else{
                $data = $return;
            }
        }else{
            $data = array(
                'status' => 0,
                'message' => _x('Data missing!','Data missing!','vibe-bbb')
            );
        }
        return new WP_REST_Response(apply_filters('vibe_get_events_vibebbb',$data,$request), 200);
    }


    function get_vibebbb_meta($id){
        $color = get_post_meta($id,'evcal_event_color',true);
        return array(
            array('meta_key'=>'start','meta_value'=>(int)get_post_meta($id,'start',true) ),
            array('meta_key'=>'end','meta_value'=>(int)get_post_meta($id,'end',true)),
            array('meta_key'=>'color','meta_value'=>apply_filters('vibe_bbb_color','#FF5B5C'))
        );
    }

    function get_meeting_recording($request){
        $body = json_decode($request->get_body(),true);
        $id = $body['id']; //custom bbb post id
        $return  = array(
            'status'=>0
        );
        if(!empty($id)){
            $show_recordings = get_post_meta($id,'show_recordings',true);
            $is_author = $this->user->id == get_post_field('post_author',$id);
            if(!($show_recordings || $is_author)){
                return new WP_REST_Response(array('status'=>0,'message'=>__('Meeting author or access does not match.','vibe-bbb')), 200);
            }
            if(!empty($id)){
                include_once 'class-bigbluebutton-api.php';
                $recordings = Vibe_Bigbluebutton_Api::get_recordings(array($id));
                $return = array(
                    'status' => 1,
                    'data' => $recordings
                );
            } 
        }
        return new WP_REST_Response($return, 200);
    }
}

Vibe_Bbb_API::init();