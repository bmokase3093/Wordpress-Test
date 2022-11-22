<?php 
/**
 * WPLMS Commissions API
 *
 * @author 		VibeThemes
 * @category 	Admin
 * @package 	Vibe-course-module/Includes
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) {  
	exit;
}

class Vibe_Earnings_API extends WP_REST_Controller	{

	public static $instance;
    public static function init(){

        if ( is_null( self::$instance ) )
            self::$instance = new Vibe_Earnings_API();
        return self::$instance;
        
    }

	private function __construct(){
		if(!defined('Vibe_BP_API_NAMESPACE'))
			return;
		$this->namespace = Vibe_BP_API_NAMESPACE.'/commissions';
		add_action( 'rest_api_init', array($this,'commissions_endpoints'));
 
	}


	function commissions_endpoints(){
		
		register_rest_route( $this->namespace, '/instructor/(?P<id>\d+)?', array(
			array(
				'methods'             =>  WP_REST_Server::READABLE,
				'callback'            =>  array( $this, 'get_instructor_commissions_rows' ),
				'permission_callback' => array( $this, 'commissions_request_validate' ),
				'args'                     	=>  array(
					'id'                       	=>  array(
						'validate_callback'     =>  function( $param, $request, $key ) {
													return is_numeric( $param );
												}
					),
				),
			),
		));

		register_rest_route( $this->namespace, '/instructor/(?P<id>\d+)?/courses', array(
			array(
				'methods'             =>  WP_REST_Server::READABLE,
				'callback'            =>  array( $this, 'get_instructor_courses' ),
				'permission_callback' => array( $this, 'commissions_request_validate' ),
				'args'                     	=>  array(
					'id'                       	=>  array(
						'validate_callback'     =>  function( $param, $request, $key ) {
													return is_numeric( $param );
												}
					),
				), 
			),
		));
		

		register_rest_route( $this->namespace, '/course/(?P<id>\d+)?', array(
			array(
				'methods'             =>  WP_REST_Server::READABLE,
				'callback'            =>  array( $this, 'get_course_details' ),
				'permission_callback' => array( $this, 'course_validate' ),
				'args'                     	=>  array(
					'id'                       	=>  array(
						'validate_callback'     =>  function( $param, $request, $key ) {
													return is_numeric( $param );
												}
					),
				),
			),
		));


		register_rest_route( $this->namespace, '/instructor/(?P<id>\d+)/students/', array(
			array(
				'methods'             =>  WP_REST_Server::READABLE,
				'callback'            =>  array( $this, 'get_instructor_students' ),
				'permission_callback' => array( $this, 'commissions_request_validate' ),
				'args'                     	=>  array(
					'id'                       	=>  array(
						'validate_callback'     =>  function( $param, $request, $key ) {
													return is_numeric( $param );
												}
					),
				),
			),
		));


		register_rest_route( $this->namespace, '/instructor/(?P<id>\d+)/search/', array(
			array(
				'methods'             =>  WP_REST_Server::READABLE,
				'callback'            =>  array( $this, 'search_instructor_students' ),
				'permission_callback' => array( $this, 'commissions_request_validate' ),
				'args'                     	=>  array(
					'id'                       	=>  array(
						'validate_callback'     =>  function( $param, $request, $key ) {
													return is_numeric( $param );
												}
					),
				), 
			),
		));

		register_rest_route( $this->namespace, '/instructor/(?P<id>\d+)/chart/', array(
			array(
				'methods'             =>  WP_REST_Server::READABLE,
				'callback'            =>  array( $this, 'get_instructor_commissions_chart' ),
				'permission_callback' => array( $this, 'commissions_request_validate' ),
				'args'                     	=>  array(
					'id'                       	=>  array(
						'validate_callback'     =>  function( $param, $request, $key ) {
													return is_numeric( $param );
												}
					),
				),
			),
		));


		register_rest_route( $this->namespace, '/instructor/(?P<id>\d+)/thresholdCommission/', array(
			array(
				'methods'             =>  WP_REST_Server::READABLE,
				'callback'            =>  array( $this, 'get_threshold_commission' ),
				'permission_callback' => array( $this, 'commissions_request_validate' ),
				'args'                     	=>  array(
					'id'                       	=>  array(
						'validate_callback'     =>  function( $param, $request, $key ) {
													return is_numeric( $param );
												}
					),
				),
			),
		));


		register_rest_route( $this->namespace, '/instructor/(?P<id>\d+)/requestPayouts/', array(
			array(
				'methods'             =>  WP_REST_Server::READABLE,
				'callback'            =>  array( $this, 'request_payouts' ),
				'permission_callback' => array( $this, 'commissions_request_validate' ),
				'args'                     	=>  array(
					'id'                       	=>  array(
						'validate_callback'     =>  function( $param, $request, $key ) {
													return is_numeric( $param );
												}
					),
				),
			),
		));


		register_rest_route( $this->namespace, '/instructor/(?P<id>\d+)/currency/', array(
			array(
				'methods'             =>  WP_REST_Server::READABLE,
				'callback'            =>  array( $this, 'get_currency' ),
				'permission_callback' => array( $this, 'commissions_request_validate' ),
				'args'                     	=>  array(
					'id'                       	=>  array(
						'validate_callback'     =>  function( $param, $request, $key ) {
													return is_numeric( $param );
												}
					),
				),
			),
		));

		register_rest_route( $this->namespace, '/instructor/(?P<id>\d+)/payouts/?', array(
			array(
				'methods'             =>  WP_REST_Server::READABLE,
				'callback'            =>  array( $this, 'get_instructor_payouts' ),
				'permission_callback' => array( $this, 'commissions_request_validate' ),
				'args'                     	=>  array(
					'id'                       	=>  array(
						'validate_callback'     =>  function( $param, $request, $key ) {
													return is_numeric( $param );
												}
					),
				),
			),
		));

		register_rest_route( $this->namespace, '/instructor/(?P<id>\d+)/payoutChart/', array(
			array(
				'methods'             =>  WP_REST_Server::READABLE,
				'callback'            =>  array( $this, 'get_instructor_payout_chart' ),
				'permission_callback' => array( $this, 'commissions_request_validate' ),
				'args'                     	=>  array(
					'id'                       	=>  array(
						'validate_callback'     =>  function( $param, $request, $key ) {
													return is_numeric( $param );
												}
					),
				),
			),
		));

		register_rest_route( $this->namespace, '/instructor/(?P<id>\d+)/last_payout_request/', array(
			array(
				'methods'             =>  WP_REST_Server::READABLE,
				'callback'            =>  array( $this, 'get_last_payout_request' ),
				'permission_callback' => array( $this, 'commissions_request_validate' ),
				'args'                     	=>  array(
					'id'                       	=>  array(
						'validate_callback'     =>  function( $param, $request, $key ) {
													return is_numeric( $param );
												}
					),
				),
			),
		));


		///widget api 
		register_rest_route(
            Vibe_BP_API_NAMESPACE,
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
            Vibe_BP_API_NAMESPACE,
            '/dashboard/widget/instructor_earnings',
            array(
                array(
                    'methods' => 'POST',
                    'callback' => array($this, 'get_instructor_commissions'),
                    'permission_callback' => array($this, 'get_permissions')
                )
            )
        );

        register_rest_route(
            Vibe_BP_API_NAMESPACE,
            '/dashboard/widget/instructor_earnings/generate',
            array(
                array(
                    'methods' => 'POST',
                    'callback' => array($this, 'generate_commission_data'),
                    'permission_callback' => array($this, 'get_permissions')
                )
            )
        );
	}

	function get_last_payout_request($request){

		$user_id = $request->get_param('id');
		global $wpdb,$bp;

		$return = array('status'=>0);
		$results = $wpdb->get_results( "SELECT  DATE(activity.date_recorded) as last_time
	                                      FROM {$bp->activity->table_name} as activity
	                                      WHERE     activity.component     = 'course'
	                                      AND     activity.type     = 'wplms_commissions_request_payout'
	                                      AND     activity.user_id     = {$user_id}
	                                      ORDER BY last_time DESC LIMIT 1",ARRAY_A);

		if(!empty($results)) {
			$last_payout_request = $results[0]['last_time'];
			$return = array('status'=>1, 'last_payout_request' => date_i18n(get_option( 'date_format' ), strtotime($last_payout_request)));
		}

		  
		return new WP_REST_Response( $return, 200 );

	}

	function get_currency($request){

		$default = '';
		global $wpdb,$bp;
		$return = array('status'=>0);
		$results = $wpdb->get_results( "
            SELECT meta2.meta_value as currency
            FROM  {$bp->activity->table_name_meta} as meta2 
            WHERE  meta2.meta_key   LIKE '_currency%'
            AND meta2.meta_value IS NOT NULL
            GROUP BY meta2.meta_value
            
            ",ARRAY_A);

		if(!empty($results)) {
			$default = $results[0]['currency'];
			$return = array('status'=>1, 'default' => $default, 'data'=>$results);
		}

	 	
		return new WP_REST_Response( $return, 200 );

	}

	function get_threshold_commission($request){

		$data = Array();
		$commissions = Array();
		$results = Array();
		$user_id = $request->get_param('id');
		global $wpdb,$bp;
		$commissions = $wpdb->get_results( "
	                                      SELECT  sum(meta.meta_value) as commissions, meta2.meta_value as currency
	                                      FROM {$bp->activity->table_name} AS activity 
	                                      LEFT JOIN {$bp->activity->table_name_meta} as meta ON activity.id = meta.activity_id
	                                      LEFT JOIN {$bp->activity->table_name_meta} as meta2 ON activity.id = meta2.activity_id
	                                      WHERE     activity.component     = 'course'
	                                      AND     activity.type     = 'course_commission'
	                                      AND     activity.user_id     = {$user_id}
	                                      AND     meta.meta_key   LIKE '_commission%'
	                                      AND     meta2.meta_key   LIKE '_currency%'
	                                      GROUP BY currency",ARRAY_A);

		$commissions = array_column($commissions,'commissions','currency');

		$results = $wpdb->get_results( "
	                                    SELECT meta2.meta_value as currency
	                                    FROM  {$bp->activity->table_name_meta} as meta2 
	                                    WHERE  meta2.meta_key   LIKE '_currency%'
	                                    AND meta2.meta_value IS NOT NULL
	                                    GROUP BY meta2.meta_value
	                                    
	                                    ",ARRAY_A);

		if(!empty($results)) {
			foreach($results as $key => $result) {
				$data[$key]['threshold'] = (int)get_option('threshold_commission_'.$result['currency']);
				$data[$key]['payout'] = $this->get_total_payouts($user_id, $result['currency']);
				$data[$key]['currency'] = $result['currency'];
				$data[$key]['total_commission'] = (!empty($commissions[$result['currency']]))? $commissions[$result['currency']] : 0;
			}
		}
		return new WP_REST_Response( $data, 200 );

	}

	function get_total_payouts($user_id, $currency) {
		global $wpdb;
		$paid = 0;


        $posts=$wpdb->get_results("SELECT post_id as id FROM {$wpdb->postmeta} WHERE meta_key = 'vibe_instructor_commissions' AND meta_value LIKE '%i:$user_id;%'",ARRAY_A);

        if(!empty($posts) && isset($posts)){
        	foreach($posts as $post){
	        	$commission_recieved = get_post_meta($post['id'],'vibe_instructor_commissions', true);
	        	if(!empty($commission_recieved[$user_id]) && $commission_recieved[$user_id]['currency'] == $currency && !empty($commission_recieved[$user_id]['set'])){
	        		$paid += $commission_recieved[$user_id]['commission'];
	        	}
	        }
        }
        
        return $paid;
	}
	

	function request_payouts($request){
		$user_id = $request->get_param('id');
		$currency = $request->get_param('currency');
		$arr = get_user_meta($user_id, 'vibe_request_payouts');
		if(!in_array($currency, $arr)){
			add_user_meta( $user_id, 'vibe_request_payouts', $currency);
		}

	    do_action('wplms_request_payouts',$user_id,time());
		return $currency;
	}


	function get_instructor_courses($request){
		$user_id = $request->get_param('id');

		global $wpdb;
		$query = apply_filters('wplms_commission_instructor_courses',$wpdb->prepare("
          SELECT posts.ID as course_id
            FROM {$wpdb->posts} AS posts
            WHERE   posts.post_type   = 'course'
            AND   posts.post_author  = %d
          ",$user_id));

        $course_ids=$wpdb->get_results($query,ARRAY_A);


		$courses = array();
		if(!empty($course_ids)){
			foreach($course_ids as $course_id){
				$courses[]=array('id'=>$course_id['course_id'],'name'=>get_the_title($course_id['course_id']));
			}
		}
		return new WP_REST_Response( $courses, 200 );

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

	function commissions_request_validate($request){

		$user_id = $request->get_param('id');
		
		$user = get_userdata( $user_id );
		if ( $user === false ) {
		    return false;
		} else {
		   return true;
		}
		if(!empty($user) && !is_wp_error($user) && !empty($user->ID)){
			$this->user_id = $user->ID;
			return true;
		}
		
		//$headers = $request->get_headers();
		$headers = vibe_getallheaders();
		if(isset($headers['Authorization'])){
			$token = $headers['Authorization'];
			$this->token = $token;
			$this->user = apply_filters('vibebp_api_get_user_from_token','',$body['token']);
			if(!empty($this->user)){
				$this->user_id = $this->user->ID;
			}else{
				$this->user_id = $this->get_user_from_token($token);
			}
			
			if($this->user_id){
				return true;
			}
		}
		return false;
	}

	function get_instructor_commissions_rows($request){
		
		$user_id = $request->get_param('id');
		$count = $request->get_param('count');
		$paged = $request->get_param('page');
		$per_page = $request->get_param('per_page');	
		$course_id =$request->get_param('course_id');	
		$date_start = $request->get_param('date_start');	
		$date_end = $request->get_param('date_end');
		$student_id = $request->get_param('student_id');
		$currency = $request->get_param('currency');
		if(empty($paged)){
			$paged = 1;
		}
		$offset= ($paged-1)*$per_page;

		$return = array('status'=>0,'message'=>__('No earnings found.','vibe-earnings'));
		$and_where = "";
		if(!empty($date_start) && !empty($date_end)){
			
			$start_date = date('Y-m-d h:m:s',$date_start);
			$end_date = date('Y-m-d h:m:s',$date_end);
			$and_where  .= " AND activity.date_recorded BETWEEN '$start_date' AND '$end_date' ";
		}
		else{
			$and_where .= ' AND YEAR(activity.date_recorded) = YEAR(CURRENT_TIMESTAMP)';
		}

		if(!empty($currency)) {
			$and_where .= " AND meta2.meta_value = '".$currency."' ";
		}
		if(function_exists('wplms_plugin_load_translations')){
			if(!empty($course_id)){
				$and_where .= " AND activity.item_id = $course_id ";
			}else{
				
				global $wpdb;
				$query = apply_filters('wplms_commission_instructor_commissions',$wpdb->prepare("
		          SELECT posts.ID as course_id
		            FROM {$wpdb->posts} AS posts
		            WHERE   posts.post_type   = 'course'
		            AND   posts.post_author  = %d
		          ",$user_id));

		        $course_ids=$wpdb->get_results($query,ARRAY_A);

		        if(empty($course_ids)){
		        	return new WP_REST_Response( array('status'=>0,'message'=>_x('Instructor does not have courses.','no courses for instructor','vibe-earnings')), 200 );
		        }
			}
		}

		global $wpdb;
		global $bp;

		if(!empty($count)){
			
			$count = $wpdb->get_results( "
	                                      SELECT count(*) as count
	                                      FROM {$bp->activity->table_name} AS activity 
	                                      LEFT JOIN {$bp->activity->table_name_meta} as meta ON activity.id = meta.activity_id
	                                      LEFT JOIN {$bp->activity->table_name_meta} as meta2 ON activity.id = meta2.activity_id
	                                      WHERE     activity.component     = 'course'
	                                      AND     activity.type  IN ('course_commission','booking_confirmed')
	                                      AND     activity.user_id     = {$user_id}
	                                      AND     meta.meta_key   LIKE '_commission%'
	                                      AND     meta2.meta_key   LIKE '_currency%'
	                                      ".$and_where."
	                                      ORDER BY activity.date_recorded ASC",ARRAY_A);


			$count[0]['status'] =1;

			$return = $count[0];

		}else{
	        $results = $wpdb->get_results( "
	                                      SELECT activity.secondary_item_id as order_item_id,activity.item_id as course_id,meta.meta_value as commission,meta2.meta_value as currency,activity.date_recorded as date
	                                      FROM {$bp->activity->table_name} AS activity 
	                                      LEFT JOIN {$bp->activity->table_name_meta} as meta ON activity.id = meta.activity_id
	                                      LEFT JOIN {$bp->activity->table_name_meta} as meta2 ON activity.id = meta2.activity_id
	                                      WHERE     activity.component     IN ('course','appointments')
	                                      AND     activity.type     IN ('course_commission','booking_confirmed')
	                                      AND     activity.user_id     = {$user_id}
	                                      AND     meta.meta_key   LIKE '_commission%'
	                                      AND     meta2.meta_key   LIKE '_currency%'
	                                      ".$and_where."
	                                      ORDER BY activity.date_recorded ASC
	                                      LIMIT ".$offset.", ".$per_page,ARRAY_A);
	        
	        $data = $results;
	        if(!empty($data) && isset($data)) {
	        	foreach($data as $key =>$value) {
	        		if(!empty($value['currency']) && !empty($value['secondary_item_id']) && $value['currency']=='credits'){
	        			$user_id = $value['secondary_item_id'];
	        		}else{
	        			$user_id = $this->get_user_id_by_order_item_id($value['order_item_id']);
	        		}
	        		
	        		$results[$key]['user_id'] = $user_id;
	        	}
	        }
	        
	        
		}
		$return = array('status'=>1,'data'=>$results);
	    $return = apply_filters('vibe_get_instructor_commissions_data',$return,$request);
		return new WP_REST_Response( $return, 200 );
	}

	function get_instructor_payouts($request){
		
		$user_id = $request->get_param('id');
		$count = $request->get_param('count');
		$paged = $request->get_param('page');
		$per_page = $request->get_param('per_page');	
		$date_start = $request->get_param('date_start');	
		$date_end = $request->get_param('date_end');
		$currency = $request->get_param('currency');
		if(empty($paged)){
			$paged = 1;
		}
		$offset= ($paged-1)*$per_page;

		$and_where = "";
		if(!empty($date_start) && !empty($date_end)){
			
			$start_date = date('Y-m-d h:m:s',$date_start);
			$end_date = date('Y-m-d h:m:s',$date_end);
			$and_where  .= " AND posts.post_date_gmt BETWEEN '$start_date' AND '$end_date' ";
		}
		else{
			$and_where .= ' AND YEAR(posts.post_date_gmt) = YEAR(CURRENT_TIMESTAMP)';
		}

		if(!empty($currency)) {
			$and_where .= " AND meta2.meta_value = '".$currency."' ";
		}

		global $wpdb;
		global $bp;

		if(!empty($count)){
			
			$count = $wpdb->get_results( "
	                                      SELECT count(*) as count
	                                      FROM {$wpdb->posts} as posts
	                                      LEFT JOIN {$wpdb->postmeta} as meta on posts.id = meta.post_id
	                                      LEFT JOIN {$wpdb->postmeta} as meta2 on posts.id = meta2.post_id
	                                      WHERE meta.meta_key LIKE 'payout_".$user_id."' 
	                                      WHERE meta2.meta_key LIKE 'currency_".$user_id."' 
	                                      ".$and_where."
	                                      LIMIT ".$offset.", ".$per_page,ARRAY_A);
			$count[0]['status'] =1;

			$return = $count[0];

		}else{
	        $results = $wpdb->get_results( "
	                                      SELECT  posts.post_date_gmt as date, meta.meta_value as payout,meta2.meta_value as currency 
	                                      FROM {$wpdb->posts} as posts
	                                      LEFT JOIN {$wpdb->postmeta} as meta on posts.id = meta.post_id
	                                      LEFT JOIN {$wpdb->postmeta} as meta2 on posts.id = meta2.post_id
	                                      WHERE meta.meta_key LIKE 'payout_".$user_id."' 
	                                      AND meta2.meta_key LIKE 'currency_".$user_id."' 
	                                      ".$and_where."
	                                      ORDER BY date ASC
	                                      LIMIT ".$offset.", ".$per_page,ARRAY_A);
	        
	        $return = array('status'=>1,'data'=>$results);
		}
		$return = apply_filters('vibe_get_instructor_payouts_data',$return,$request);
		return new WP_REST_Response( $return, 200 );
	}


	function get_instructor_commissions_chart($request){

		$user_id = $request->get_param('id');
		$course_id =$request->get_param('course_id');	
		$date_start = $request->get_param('date_start');	
		$date_end = $request->get_param('date_end');
		$currency = $request->get_param('currency');
		$chart_data = Array();
		$cdata = Array();
		$months=Array(
			_x('January','api call','vibe-earnings'),
			_x('February','api call','vibe-earnings'),
			_x('March','api call','vibe-earnings'),
			_x('April','api call','vibe-earnings'),
			_x('May','api call','vibe-earnings'),
			_x('June','api call','vibe-earnings'),
			_x('July','api call','vibe-earnings'),
			_x('August','api call','vibe-earnings'),
			_x('September','api call','vibe-earnings'),
			_x('October','api call','vibe-earnings'),
			_x('November','api call','vibe-earnings'),
			_x('December','api call','vibe-earnings')
		);

		foreach($months as $key =>  $value){
			$chart_data[$key+1]['name'] = $value;
			$chart_data[$key+1]['commission'] = 0;
		}

		$and_where = '';
		$start_date = '';
		$end_date = '';
		$group_by = ' GROUP BY select_parameter';
		$select = 'MONTH(activity.date_recorded) as select_parameter';

		if(!empty($course_id)){
			$and_where .= " AND activity.item_id = $course_id ";
		}else{
			
			global $wpdb;
			$query = apply_filters('wplms_commission_instructor_chart',$wpdb->prepare("
	          SELECT posts.ID as course_id
	            FROM {$wpdb->posts} AS posts
	            WHERE   posts.post_type   = 'course'
	            AND   posts.post_author  = %d
	          ",$user_id));

			
	        $course_ids=$wpdb->get_results($query,ARRAY_A);
	        if(empty($course_ids)){
	        	return new WP_REST_Response( array('status'=>0,'message'=>_x('Instructor does not have courses.','no courses for instructor','vibe-earnings')), 200 );
	        }
		}
		if(!empty($currency)) {
			$and_where .= " AND meta2.meta_value = '".$currency."' ";
		}

		if(!empty($date_start) && !empty($date_end)){
			$date_range  = round(($date_end - $date_start)/60/60/24);
			$start_date = date('Y-m-d H:i:s',$date_start);
			$end_date = date('Y-m-d H:i:s',$date_end);
			$and_where  .= " AND activity.date_recorded BETWEEN '$start_date' AND '$end_date' ";

			if($date_range <= 1 && !empty($start_date)){
	//time On Day

				$chart_data = Array();
				$group_by = ' GROUP BY select_parameter';
				$select = 'activity.date_recorded as select_parameter';	
			}
			if($date_range >= 2 && $date_range <= 7){
	//daily
				$chart_data = Array();
				$startDate = (int)date('j', $date_start);
				$endDate = (int)date('j', $date_end);
				
				$date_format = get_option('date_format');	
				for($i = $date_start; $i <= $date_end; $i+=86400) {

					$value = date($date_format, $i);
					$index = date("Y-m-d", $i);
					$chart_data[$index]['name'] =$value;
					$chart_data[$index]['commission'] =0;
				}
				$group_by = ' GROUP BY select_parameter';
				$select = 'DATE(activity.date_recorded) as select_parameter';
			}
			if($date_range >= 8 && $date_range <= 30) {
	//weekly

				$chart_data = Array();
				$startWeek = (int)date('W', $date_start);
				$endWeek = (int)date('W', $date_end);
				for($i = $startWeek; $i <= $endWeek; $i++) {
					$value = _x('Week','api call','vibe-earnings').($i+1-$startWeek);
					$chart_data[$i-1]['name'] =$value;
					$chart_data[$i-1]['commission'] =0;
				}

				$group_by = ' GROUP BY select_parameter';
				$select = 'WEEK(activity.date_recorded) as select_parameter';
			}
			else if($date_range >= 31 && $date_range <= 365) {
	//Monthly
				$chart_data = Array();
				$startMonth = (int)date('m', $date_start);
				$endMonth = (int)date('m', $date_end);
				for($i = $startMonth; $i <= $endMonth; $i++) {
					$value = $months[$i-1];
					$chart_data[$i]['name'] =$value;
					$chart_data[$i]['commission'] =0;
				}
				$group_by = ' GROUP BY select_parameter';
				$select = 'MONTH(activity.date_recorded) as select_parameter';
			}

			else if($date_range >= 366) {
	//Yearly
				$chart_data = Array();
				$startYear = (int)date('Y', $date_start);
				$endYear = (int)date('Y', $date_end);
				for($i = $startYear; $i <= $endYear; $i++) {
					$value = $i;
					$chart_data[$i-1]['name'] =$value;
					$chart_data[$i-1]['commission'] =0;
				}
				$group_by = ' GROUP BY select_parameter';
				$select = 'YEAR(activity.date_recorded) as select_parameter';
			}

		}
		else{
			$and_where .= ' AND YEAR(activity.date_recorded) = YEAR(CURRENT_TIMESTAMP)';
		}

		global $wpdb;
		global $bp;
		$results = $wpdb->get_results( "
                      SELECT ".$select.", sum(meta.meta_value) as commission
                      FROM {$bp->activity->table_name} AS activity 
                      LEFT JOIN {$bp->activity->table_name_meta} as meta ON activity.id = meta.activity_id
                      LEFT JOIN {$bp->activity->table_name_meta} as meta2 ON activity.id = meta2.activity_id
                      WHERE     activity.component     = 'course'
                      AND     activity.type     = 'course_commission'
                      AND     activity.user_id     = {$user_id}
                      AND     meta.meta_key   LIKE '_commission%'
                      AND     meta2.meta_key   LIKE '_currency%'
                      ".$and_where."
                      ".$group_by,ARRAY_A
                	);

		if(!empty($results)) {
			foreach($results as $result) {
				$chart_data[$result['select_parameter']]['commission']= (float)$result['commission'];
				
			}
		}

		foreach($chart_data as $value) {
			$cdata[] = $value;
		}
        $return = array('status'=>1,'data'=>$cdata);
        $return = apply_filters('vibe_get_instructor_commissions_chart',$return,$request);
		return new WP_REST_Response( $return, 200 );
	}

	function get_instructor_payout_chart($request){

		$user_id = $request->get_param('id');
		$date_start = $request->get_param('date_start');	
		$date_end = $request->get_param('date_end');
		$currency = $request->get_param('currency');
		$chart_data = Array();
		$cdata = Array();
		$months=Array(
			_x('January','api call','vibe-earnings'),
			_x('February','api call','vibe-earnings'),
			_x('March','api call','vibe-earnings'),
			_x('April','api call','vibe-earnings'),
			_x('May','api call','vibe-earnings'),
			_x('June','api call','vibe-earnings'),
			_x('July','api call','vibe-earnings'),
			_x('August','api call','vibe-earnings'),
			_x('September','api call','vibe-earnings'),
			_x('October','api call','vibe-earnings'),
			_x('November','api call','vibe-earnings'),
			_x('December','api call','vibe-earnings')
		);

		foreach($months as $key =>  $value){
			$chart_data[$key+1]['name'] = $value;
			$chart_data[$key+1]['payout'] = 0;
		}

		$and_where = '';
		$start_date = '';
		$end_date = '';
		$group_by = ' GROUP BY select_parameter';
		$select = 'MONTH(posts.post_date_gmt) as select_parameter';
		
		if(!empty($currency)) {
			$and_where .= " AND meta2.meta_value = '".$currency."' ";
		}

		if(!empty($date_start) && !empty($date_end)){
			$date_range  = round(($date_end - $date_start)/60/60/24);
			$start_date = date('Y-m-d H:i:s',$date_start);
			$end_date = date('Y-m-d H:i:s',$date_end);
			$and_where  .= " AND posts.post_date_gmt BETWEEN '$start_date' AND '$end_date' ";

			if($date_range <= 1 && !empty($start_date)){
	//time On Day

				$chart_data = Array();
				$group_by = ' GROUP BY select_parameter';
				$select = 'posts.post_date_gmt as select_parameter';	
			}
			if($date_range >= 2 && $date_range <= 7){
	//daily
				$chart_data = Array();
				$startDate = (int)date('j', $date_start);
				$endDate = (int)date('j', $date_end);
				
				$date_format = get_option('date_format');	
				for($i = $date_start; $i <= $date_end; $i+=86400) {

					$value = date($date_format, $i);
					$index = date("Y-m-d", $i);
					$chart_data[$index]['name'] =$value;
					$chart_data[$index]['payout'] =0;
				}
				$group_by = ' GROUP BY select_parameter';
				$select = 'DATE(posts.post_date_gmt) as select_parameter';
			}
			if($date_range >= 8 && $date_range <= 30) {
	//weekly

				$chart_data = Array();
				$startWeek = (int)date('W', $date_start);
				$endWeek = (int)date('W', $date_end);
				for($i = $startWeek; $i <= $endWeek; $i++) {
					$value = _x('Week','api call','vibe-earnings').($i+1-$startWeek);
					$chart_data[$i-1]['name'] =$value;
					$chart_data[$i-1]['payout'] =0;
				}

				$group_by = ' GROUP BY select_parameter';
				$select = 'WEEK(posts.post_date_gmt) as select_parameter';
			}
			else if($date_range >= 31 && $date_range <= 365) {
	//Monthly
				$chart_data = Array();
				$startMonth = (int)date('m', $date_start);
				$endMonth = (int)date('m', $date_end);
				for($i = $startMonth; $i <= $endMonth; $i++) {
					$value = $months[$i-1];
					$chart_data[$i]['name'] =$value;
					$chart_data[$i]['payout'] =0;
				}
				$group_by = ' GROUP BY select_parameter';
				$select = 'MONTH(posts.post_date_gmt) as select_parameter';
			}

			else if($date_range >= 366) {
	//Yearly
				$chart_data = Array();
				$startYear = (int)date('Y', $date_start);
				$endYear = (int)date('Y', $date_end);
				for($i = $startYear; $i <= $endYear; $i++) {
					$value = $i;
					$chart_data[$i-1]['name'] =$value;
					$chart_data[$i-1]['payout'] =0;
				}
				$group_by = ' GROUP BY select_parameter';
				$select = 'YEAR(posts.post_date_gmt) as select_parameter';
			}

		}
		else{
			$and_where .= ' AND YEAR(posts.post_date_gmt) = YEAR(CURRENT_TIMESTAMP)';
		}

		global $wpdb;
		$results = $wpdb->get_results( "
                                      SELECT ".$select.", sum(meta.meta_value) as payout
                                      FROM {$wpdb->posts} AS posts 
                                      LEFT JOIN {$wpdb->postmeta} as meta ON posts.id = meta.post_id
                                      LEFT JOIN {$wpdb->postmeta} as meta2 ON posts.id = meta2.post_id

	                                  WHERE   meta.meta_key   LIKE 'payout_".$user_id."'
	                                  AND     meta2.meta_key   LIKE 'currency_".$user_id."'
                                      ".$and_where."
                                      ".$group_by,ARRAY_A);

		if(!empty($results)) {
			foreach($results as $result) {
				$chart_data[$result['select_parameter']]['payout']= (float)$result['payout'];
			}
		}

		foreach($chart_data as $value) {
			$cdata[] = $value;
		}
        $return = array('status'=>1,'data'=>$cdata);
        $return = apply_filters('vibe_get_instructor_payout_chart',$return,$request);
		return new WP_REST_Response( $return, 200 );
	}

	function get_user_id_by_order_item_id($order_item_id) {
		global $wpdb;
		$user_id = 0;
		$result = $wpdb->get_results( "
                                      SELECT order_id 
                                      FROM {$wpdb->prefix}woocommerce_order_items 
                                      where order_item_id = $order_item_id", ARRAY_A);
		$user_id = 0;
		if(!empty($result) && !empty($result[0]) && !empty($result[0]['order_id'])){
			$order_id = $result[0]['order_id'];
			$order = wc_get_order( $order_id );
			if($order) {
				$user_id = $order->get_user_id();

			}
		}
		
		return $user_id;
	}

	function course_validate($request){
		return true;
	}

	function get_instructor_students($request){

		$user_ids = json_decode($request->get_param('students'));
		global $wpdb;

		$names = $wpdb->get_results("SELECT ID,display_name FROM {$wpdb->users} WHERE ID IN (".implode(',',$user_ids).")");

		$return = array();
		if(!empty($names)){
			foreach($names as $name){
				$return[] = array('id'=>$name->ID,'name'=>$name->display_name);
			}
		}
		return new WP_REST_Response( $return, 200 );
	}


	function get_course_details($request){
		$course_id = $request->get_param('id');
		return new WP_REST_Response(array('id'=>$course_id,'name'=>get_the_title($course_id)), 200 );
	}


	function search_instructor_students($request){
		$user_ids = json_decode($request->get_param('students'));
		$s = sanitize_text_field($request->get_param('s'));

		global $wpdb;

		$users = array();
		$results = $wpdb->get_results("SELECT ID, display_name FROM {$wpdb->users} WHERE user_login LIKE '%$s%' OR user_email LIKE '%$s%' OR display_name LIKE '%$s%' ");


		if(!empty($results)){
			foreach($results as $result){
				$users[]=array('id'=>$result->ID,'name'=>$result->display_name,'image'=>bp_core_fetch_avatar(array(
								'item_id' => $result->ID,
								'object'  => 'user',
								'type'=>'thumb',
								'html'	  => false
							)) );
			}
		}

		return new WP_REST_Response( $users, 200 );
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
                                      activity.component as source
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
        return apply_filters('vibe_earnings_generate_commission_data',$results,$user_id);
      
    }

    function generate_commission_data($request){
      global $wpdb;
      $body = json_decode($request->get_body(), true);
      $user_id = $this->user->id;


	    //migrated to activities with currencies 

	    $ist_commissions = $this->get_inst_course_commission($user_id);
	    if(!empty($ist_commissions)){
	        $commision_array_cur = array();
	        $sales_pie_cur = array();
	        $total_commission_cur = array(); 
	        $com = array();
	        $commission_item_id_source_map = [];
	        foreach ($ist_commissions as $key => $ist_commission) {
	            if(!isset($total_commission_cur[$ist_commission['currency']])){
	                $total_commission_cur[$ist_commission['currency']] = 0;
	            }
		          $total_commission_cur[$ist_commission['currency']] += intval($ist_commission['commission']);
		          
		          if(!isset($com[$ist_commission['currency']])){
		            $com[$ist_commission['currency']] = [];
		          }
		          if(!isset($com[$ist_commission['currency']][$ist_commission['date']])){
		            $com[$ist_commission['currency']][$ist_commission['date']] = 0;
		          }
		          $com[$ist_commission['currency']][$ist_commission['date']] = $com[$ist_commission['currency']][$ist_commission['date']]+$ist_commission['commission'];
		         
		          $commision_array_cur[$ist_commission['currency']][$ist_commission['date']]=  array(
		               'date' =>date('M', mktime(0, 0, 0, $ist_commission['date'], 10)),
		               'sales' =>$com[$ist_commission['currency']][$ist_commission['date']]
		              
		              );
		          if(!isset($sales_pie_cur[$ist_commission['currency']])){
		            $sales_pie_cur[$ist_commission['currency']] = [];
		          }
		          if(!isset($sales_pie_cur[$ist_commission['currency']][$ist_commission['course_id']])){
		            $sales_pie_cur[$ist_commission['currency']][$ist_commission['course_id']] = 0;
		          }
		          $sales_pie_cur[$ist_commission['currency']][$ist_commission['course_id']] += $ist_commission['commission'];
		          if(!empty($ist_commission['source'])){
		          	$commission_item_id_source_map[$ist_commission['course_id']] = $ist_commission['source'];
		          }
	        }
	        update_user_meta($user_id,'commission_item_id_source_map',$commission_item_id_source_map);
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
	    
	    _e('No data found for Instructor','vibe-earnings');
	    die();

    }

    function get_currencies_data($request){
        $currencies_data = $this->get_currencies();
        $data = array('status'=>false,'message'=>_x('No currency data','','vibe-earnings'));
        if(!empty($currencies_data)){
            $currencies = [];
            foreach ($currencies_data as $key => $cc) {
                if(function_exists('get_woocommerce_currency_symbol')){
                    $symbol= get_woocommerce_currency_symbol($cc['currency']); 
                    if(empty($symbol)){
                        $symbol = _x(strtoupper($cc['currency']),'currency variable label','vibe-earnings');
                    } 

                    $currencies[] = array(
                        'label'=>$symbol,
                        'value' =>$cc['currency'],
                    );
                
                }
            }
            $data = array('status'=>true,'message'=>_x('Currencies data found','','vibe-earnings'),'currencies'=>$currencies);
        }
        return new WP_REST_Response($data, 200);
    }

    function get_instructor_commissions($request)
    {
        if(!function_exists('vibe_sanitize'))
          return;
        $body = json_decode($request->get_body(), true);
        $user_id = $this->user->id;

        $data = array(
            'status' => 1,
            'message' => __('No commission data found', 'vibe-earnings'),
            'data'=>array()
        );
        $_cur = array();
        $_cur = $body['currency'];
        $currencies_data = $this->get_currencies();
        
        if(!empty($currencies_data) ){
            foreach ($currencies_data as $key => $cc) {
                if(function_exists('get_woocommerce_currency_symbol')){
                    $symbol= get_woocommerce_currency_symbol($cc['currency']); 
                    if(empty($symbol)){
                        $symbol = _x(strtoupper($cc['currency']),'currency variable label','vibe-earnings');
                    }

                    $data['data']['currencies'][] = array(
                        'label'=>$symbol,
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
                if(empty($symbol)){
                    $symbol = _x(strtoupper($_cur),'currency variable label','vibe-earnings');
                }
            }
            
            if(function_exists('wc_price')){
              $value = wc_price($total_commission);
            }
            
            
            $sales_pie_array=array();
            if(isset($sales_pie) && is_array($sales_pie) && count($sales_pie)){
            	$commission_item_id_source_map = get_user_meta($user_id,'commission_item_id_source_map',true);
            	if(empty($commission_item_id_source_map)){
            		$commission_item_id_source_map = [];
            	}
                foreach($sales_pie as $cid=>$sales){
                	$label = '';
                	if(!empty($commission_item_id_source_map[$cid])){
                		switch($commission_item_id_source_map[$cid]){
                			case 'course':
                				$label =get_the_title($cid);
                			break;
                			default:
                				$label = apply_filters('vibe_eranings_get_instructor_commissions_data_label',get_the_title($cid),$cid,$commission_item_id_source_map[$cid],$sales);
                			break;
                		}
                	}else{
                		$label =get_the_title($cid);
                	}
                    $sales_pie_array[]=array(
                      'label'=>$label,
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
        }
        $data['data']['sales_pie']=$sales_pie_array;
        $data['data']['commissions']=$commision_array;
        return new WP_REST_Response($data, 200);
    }
}

Vibe_Earnings_API::init();
