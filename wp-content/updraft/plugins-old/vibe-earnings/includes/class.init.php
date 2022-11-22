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
			'api_url'=> get_rest_url($blog_id,WPLMS_API_NAMESPACE).'/commissions/',
            'settings'=>array(
            	
            ),
            
            'timestamp'=>time(),
            'per_page'=>10,
            'date_format'=>get_option('date_format'),
            'time_format'=>get_option('time_format'),
            'translations'=>array(
                'date'=>_x('Date','api call','vibe'),
                'course'=>_x('Course','api call','vibe'),
                'commissions'=>_x('Commissions','api call','vibe'),
                'commission'=>_x('Commission','api call','vibe'),
                'payout' => _x('Payout','api call','vibe'),
                'present'=>_x('Present','api call','vibe'),
                'absent'=>_x('Absent','api call','vibe'),
                'showing'=>_x('Showing','api call','vibe'),
                'of'=>_x('of','api call','vibe'),
                'go'=>_x('Go','api call','vibe'),
                'student'=>_x('Student','api call','vibe'),
                'select_date'=>_x('Select a date','api call','vibe'),
                'start_date'=>_x('Select start date','api call','vibe'),
                'end_date'=>_x('Select end date','api call','vibe'),
                'select_course'=>_x('Select a course','api call','vibe'),
                'no_records'=>_x('No records found','api call','vibe'),
                'select_student'=>_x('Select a student','api call','vibe'),
                'search_student'=>_x('Search student','api call','vibe'),
                'chart_commission'=>_x('Commission','api call','vibe'),
                'commission_balance'=>_x('Commission Balance: ','api call','vibe'),
                'payouts_balance'=>_x('Payouts Balance: ','api call','vibe'),
                'select_currency'=>_x('Select Currency ','api call','vibe'),
                'commission_payouts_text'=>_x('Congratulation! Commission is above threshold limit. You can request a payout.','api call','vibe'),
                'commission_failed_payouts_text'=>_x('You can request a payout only if commission is above threshold limit.','api call','vibe'),
                'request_payouts'=>_x('Request Payouts','api call','vibe'),
                'requested_payouts'=>_x('Payout Requested','api call','vibe'),
                'last_requested'=>_x('Last requested on','api call','vibe'),
            ),
        ));
        if(function_exists('bp_is_user') && bp_is_user() || apply_filters('vibebp_enqueue_profile_script',false)){
            wp_enqueue_script('vibe-earnings-chartjs',plugins_url('../assets/js/chart.bundle.min.js',__FILE__),array('wp-element'),VIBE_EARNINGS_PLUGIN_VERSION,true);
            wp_enqueue_script('vibe-earnings',plugins_url('../assets/js/earnings.js',__FILE__),array('wp-element','wp-data','vibe-earnings-chartjs'),VIBE_EARNINGS_PLUGIN_VERSION);
            wp_localize_script('vibe-earnings','vibe_earnings',$kb);
            wp_enqueue_style('vibe-earnings',plugins_url('../assets/css/earnings.css',__FILE__),array(),VIBE_EARNINGS_PLUGIN_VERSION);
            echo '<style>.commissionsRecords .chart{width:calc(100vw - 300px);}</style>';
        }
	}
}
Vibe_Earnings_Profile::init();