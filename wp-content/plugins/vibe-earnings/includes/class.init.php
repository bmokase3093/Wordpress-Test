<?php
/**
 * PRofile
 *
 * @class       Vibe_Earnings_Profile
 * @author      VibeThemes
 * @category    Admin
 * @package     vibekb
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}



class Vibe_Earnings_Profile{


	public static $instance;
	public static function init(){

        if ( is_null( self::$instance ) )
            self::$instance = new Vibe_Earnings_Profile();
        return self::$instance;
    }

	private function __construct(){
		add_action( 'bp_setup_nav', array($this,'add_earnings_tab'), 101 );
		add_action('wp_enqueue_scripts',array($this,'enqueue_script'));
        add_filter('vibebp_component_icon',array($this,'set_icon'),10,2);
        add_action('init',array($this,'payments_post_type'));
        //add_action( 'save_post',array($this,'save_meta_boxes'));

        add_action( 'add_meta_boxes',array($this,'add_meta_box'));
        //Meta callback function
        
	}

    function add_meta_box() {
        add_meta_box( 'payments-meta', _x('Payments meta','','vibe-earnings'), array($this,'payments_meta'), 'payments' );
    }

    function payments_meta( $post ) {
        global $post;

        $start = get_post_meta($post->ID,'vibe_date_from',true);
         $end = get_post_meta($post->ID,'vibe_date_to',true);
        $meta = apply_filters('wplms_meta_box_meta_value',get_post_meta( $post->ID, 'vibe_instructor_commissions', true),$post->ID,'vibe_instructor_commissions');
        echo '<div class="row"><span>'._x('From','','vibe-earnings').' : '.$start.'</span>
        <span>'._x('To','','vibe-earnings').' : '.$end.'</span></div>';
        echo '<ul id="instructor_payments">

        <li><strong>'.__('Instructor','vibe-earnings').'</strong><span>'.__('Email','vibe-earnings').'</span><span>'.__('Commission','vibe-earnings').'</span><span>'.__('Currency','vibe-earnings').'</span></li>';
        if(is_array($meta))
        foreach($meta as $key=>$row){
            if(isset($row['set']) && $row['set'])
                echo '<li><strong>'.get_the_author_meta('display_name',$key).'</strong><span>'.$row['email'].'</span><span>'.$row['commission'].'</span>'.(!empty($row['currency'])?'<span>'.$row['currency'].'</span>':'').'</li>';
        }
        echo '</ul><style>ul#instructor_payments li {display: grid;grid-template-columns: 1fr 1fr 1fr 1fr;}</style>';
    }

    function payments_post_type(){
        register_post_type( 'payments',
            array(
                'labels' => array(
                    'name' => __('Payments','vibe-earnings'),
                    'menu_name' => __('Payments','vibe-earnings'),
                    'singular_name' => __('Payment','vibe-earnings'),
                    'add_new_item' => __('Add New Payment','vibe-earnings'),
                    'all_items' => __('Payouts','vibe-earnings')
                ),
                'publicly_queryable' => true,
                'show_ui' => true,
                'exclude_from_search' => true,
                'has_archive' => false,
                'query_var'   => false,
                'show_in_menu' => (current_user_can('manage_options')?'lms':false),
                'show_in_nav_menus' => false,
                'supports' => array( 'title'),
                'hierarchical' => false,
                'rewrite' => array( 'slug' => 'payments', 'hierarchical' => false, 'with_front' => false )
            )
         );
    }

    function set_icon($icon,$component_name){

        if($component_name == 'commissions' || $component_name == 'commission' ){
            return '<svg width="100%" height="100%" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;">
    <path d="M12,2C17.514,2 22,6.486 22,12C22,17.514 17.514,22 12,22C6.486,22 2,17.514 2,12C2,6.486 6.486,2 12,2ZM12,0C5.373,0 0,5.373 0,12C0,18.627 5.373,24 12,24C18.627,24 24,18.627 24,12C24,5.373 18.627,0 12,0Z" style="fill-opacity:0.4;fill-rule:nonzero;"/>
    <path d="M12,3C7.029,3 3,7.029 3,12C3,16.971 7.029,21 12,21C16.971,21 21,16.971 21,12C21,7.029 16.971,3 12,3ZM13,16.947L13,18L12,18L12,17.002C10.965,16.984 9.894,16.737 9,16.275L9.455,14.631C10.411,15.002 11.684,15.396 12.68,15.171C13.829,14.911 14.065,13.729 12.794,13.16C11.863,12.726 9.016,12.355 9.016,9.917C9.016,8.554 10.055,7.334 12,7.067L12,6L13,6L13,7.018C13.725,7.037 14.535,7.163 15.442,7.438L15.08,9.086C14.312,8.816 13.464,8.571 12.638,8.621C11.149,8.708 11.018,9.997 12.057,10.537C13.768,11.341 16,11.938 16,14.083C16.002,15.801 14.656,16.715 13,16.947Z" style="fill-rule:nonzero;"/>
</svg>';
        }
        return $icon;
    }
	function add_earnings_tab(){
		global $bp;
		$slug='commissions';
		//Add VibeDrive tab in profile menu
	    bp_core_new_nav_item( array( 
	        'name' => __('Commissions','vibe-earnings'),
	        'slug' => $slug, 
	        'item_css_id' => 'commissions',
	        'screen_function' => array($this,'show_screen'),
	        'default_subnav_slug' => 'home', 
	        'position' => 55,
	        'user_has_access' => (bp_is_my_profile() || current_user_can('manage_options'))
	    ) );

		bp_core_new_subnav_item( array(
			'name' 		  => __('Commission','vibe-earnings'),
			'slug' 		  => 'commission',
			'parent_slug' => $slug,
        	'parent_url' => $bp->displayed_user->domain.$slug.'/',
			'screen_function' => array($this,'show_screen'),
			'user_has_access' => (bp_is_my_profile() || current_user_can('manage_options'))
		) );

	    bp_core_new_subnav_item( array(
			'name' 		  => __('Payouts','vibe-earnings'),
			'slug' 		  => 'payouts',
			'parent_slug' => $slug,
        	'parent_url' => $bp->displayed_user->domain.$slug.'/',
			'screen_function' => array($this,'show_screen'),
			'user_has_access' => (bp_is_my_profile() || current_user_can('manage_options'))
		) );
		
	}

	function show_screen(){

	}

	function enqueue_script(){

		$blog_id = '';
        if(function_exists('get_current_blog_id')){
            $blog_id = get_current_blog_id();
        }

            
		$kb=apply_filters('vibe_earnings_script_args',array(
			'api_url'=> get_rest_url($blog_id,Vibe_BP_API_NAMESPACE).'/commissions/',
            'settings'=>array(
            	
            ),
            
            'timestamp'=>time(),
            'per_page'=>10,
            'date_format'=>get_option('date_format'),
            'time_format'=>get_option('time_format'),
            'translations'=>array(
                'date'=>_x('Date','api call','vibe-earnings'),
                'course'=>_x('Course','api call','vibe-earnings'),
                'commissions'=>_x('Commissions','api call','vibe-earnings'),
                'commission'=>_x('Commission','api call','vibe-earnings'),
                'payout' => _x('Payout','api call','vibe-earnings'),
                'present'=>_x('Present','api call','vibe-earnings'),
                'absent'=>_x('Absent','api call','vibe-earnings'),
                'showing'=>_x('Showing','api call','vibe-earnings'),
                'of'=>_x('of','api call','vibe-earnings'),
                'go'=>_x('Go','api call','vibe-earnings'),
                'student'=>_x('Student','api call','vibe-earnings'),
                'select_date'=>_x('Select a date','api call','vibe-earnings'),
                'start_date'=>_x('Select start date','api call','vibe-earnings'),
                'end_date'=>_x('Select end date','api call','vibe-earnings'),
                'select_course'=>_x('Select a course','api call','vibe-earnings'),
                'no_records'=>_x('No records found','api call','vibe-earnings'),
                'select_student'=>_x('Select a student','api call','vibe-earnings'),
                'search_student'=>_x('Search student','api call','vibe-earnings'),
                'chart_commission'=>_x('Commission','api call','vibe-earnings'),
                'commission_balance'=>_x('Commission Balance: ','api call','vibe-earnings'),
                'payouts_balance'=>_x('Payouts Balance: ','api call','vibe-earnings'),
                'select_currency'=>_x('Select Currency ','api call','vibe-earnings'),
                'commission_payouts_text'=>_x('Congratulation! Commission is above threshold limit. You can request a payout.','api call','vibe-earnings'),
                'commission_failed_payouts_text'=>_x('You can request a payout only if commission is above threshold limit.','api call','vibe-earnings'),
                'request_payouts'=>_x('Request Payouts','api call','vibe-earnings'),
                'requested_payouts'=>_x('Payout Requested','api call','vibe-earnings'),
                'last_requested'=>_x('Last requested on','api call','vibe-earnings'),
            ),
        ));
        if(function_exists('bp_is_user') && bp_is_user() || apply_filters('vibebp_enqueue_profile_script',false)){
            wp_enqueue_script('vibe-earnings-chartjs',plugins_url('../assets/js/chart.bundle.min.js',__FILE__),array('wp-element'),VIBE_EARNINGS_PLUGIN_VERSION,true);
            wp_enqueue_script('vibe-earnings',plugins_url('../assets/js/earnings.js',__FILE__),array('wp-element','wp-data','vibe-earnings-chartjs'),VIBE_EARNINGS_PLUGIN_VERSION);
            wp_localize_script('vibe-earnings','vibe_earnings',$kb);
            wp_enqueue_style('vibe-earnings',plugins_url('../assets/css/earnings.css',__FILE__),array(),VIBE_EARNINGS_PLUGIN_VERSION);
        }
	}
}
Vibe_Earnings_Profile::init();