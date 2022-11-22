<?php
/**
 * Plugin Name: WPLMS
 * Plugin URI: https://wplms.io
 * Description: The most advanced Learning management system for WordPress - wplms.io
 * Author: VibeThemes
 * Author URI: https://vibethemes.com
 * Version: 1.9.0.1
 * Text Domain: wplms
 * Domain Path: /languages
 * Tested up to: 6.1
 *
 * @package WPLMS
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'WPLMS_PLUGIN_VERSION','1.9.0.1');


defined('WPLMS_TOKEN') or define('WPLMS_TOKEN', 'token');
defined('WPLMS') or define('WPLMS', 'Wplms_');

define( 'WPLMS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WPLMS_PLUGIN_URL', untrailingslashit(plugin_dir_url( __FILE__ )) );
define( 'WPLMS_PLUGIN_INCLUDES_URL', untrailingslashit(plugin_dir_url( __FILE__ )).'/includes' );

define( 'WPLMS_PLUGIN_FILE', __FILE__ );
define( 'WPLMS_PLUGIN_BASE', plugin_basename( __FILE__ ) );



defined('WPLMS_DASHBOARD_VERSION') or define('WPLMS_DASHBOARD_VERSION', '2.0');
defined('WPLMS_ASSIGNMENTS_SLUG') or define('WPLMS_ASSIGNMENTS_SLUG', 'assignment');
defined('WPLMS_ASSIGNMENTS_CPT') or define('WPLMS_ASSIGNMENTS_CPT', 'wplms-assignment');
if ( ! defined( 'VIBEBP_CREDITS_SLUG' ) ){
    define ( 'VIBEBP_CREDITS_SLUG','credits' );
}

if(!defined('WPLMS_OPTION_NAME')){
    define( 'WPLMS_OPTION_NAME', 'wplms' );
}

if(!defined('WPLMS_PLUGIN_DIR_NAME')){
    define( 'WPLMS_PLUGIN_DIR_NAME', 'wplms_plugin' );    
}

if ( ! defined( 'BP_COURSE_API_NAMESPACE' ) ){
    define( 'BP_COURSE_API_NAMESPACE', 'wplms/v2' );
}

if ( ! defined( 'WPLMS_API_NAMESPACE' ) ){
    define('WPLMS_API_NAMESPACE','wplms/v2');
}
if ( ! defined( 'VIBEYOUTUBE_VIDEO_FOLDER_NAME' ) ){
    define('VIBEYOUTUBE_VIDEO_FOLDER_NAME','vibeyoutubevideo');
}


defined('WPLMS_SETTINGS') or define('WPLMS_SETTINGS','vibebp_settings');



if(!defined('BP_COURSE_MOD_INSTALLED') 
    && !function_exists ('vibe_customtypes_translations')  
    && !function_exists('vibe_shortcodes_translations')
    && !function_exists('wplms_dashboard_language_setup')
    && !function_exists('vibe_shortcodes_translations')
    && !function_exists('wplms_front_end_language_setup')
    && !function_exists('wplms_assignments_language_setup')){

require_once(dirname(__FILE__).'/includes/create-course/loader.php');
require_once(dirname(__FILE__).'/includes/theme/functions.php');
require_once(dirname(__FILE__).'/includes/theme/reviews.php');
require_once(dirname(__FILE__).'/includes/theme/wplms.init.php');
require_once(dirname(__FILE__).'/includes/theme/wplms.actions.php');
require_once(dirname(__FILE__).'/includes/theme/wplms.filters.php');
require_once(dirname(__FILE__).'/includes/theme/class.course.component.php');

add_action('plugins_loaded',function (){
      

    if(function_exists('pmpro_hasMembershipLevel')){
        require_once(dirname(__FILE__).'/includes/theme/pmpro-connect.php');
    }
});


if(function_exists('bp_is_active')){
	require_once(dirname(__FILE__).'/includes/vibe-customtypes/vibe-customtypes.php');
	require_once(dirname(__FILE__).'/includes/vibe-course-module/loader.php');
	require_once(dirname(__FILE__).'/includes/vibe-shortcodes/vibe-shortcodes.php');
	require_once(dirname(__FILE__).'/includes/wplms-dashboard/loader.php');
	//require_once(dirname(__FILE__).'/includes/assignments/class.assignments.php');
    require_once(dirname(__FILE__).'/includes/assignments/assignment_functions.php');
    require_once(dirname(__FILE__).'/includes/assignments/assignments.php');

	require_once(dirname(__FILE__).'/includes/assignments/class.filters.php');
	require_once(dirname(__FILE__).'/includes/assignments/class.actions.php');	
    require_once(dirname(__FILE__).'/includes/assignments/class.assignment-process.php');

    add_action('plugins_loaded',function (){
        WPLMS_Assignments::init();
    },100);

}
require_once(dirname(__FILE__).'/includes/class.actions.php');
require_once(dirname(__FILE__).'/includes/class.filters.php');
require_once(dirname(__FILE__).'/includes/class.setup.php');

require_once(dirname(__FILE__).'/includes/gutenberg/class.gutenberg.blocks.php');

require_once(dirname(__FILE__).'/includes/class.youtube.download.php');
require_once(dirname(__FILE__).'/includes/stale_requests_map.php');
require_once(dirname(__FILE__).'/includes/class.stale.requests.php');





}else{
    add_action('admin_notices',function(){
        if(!empty($_POST['deactivate_vibe_plugins']) && wp_verify_nonce($_POST['security'],'security')){
            deactivate_plugins(array(
                'vibe-course-module/loader.php',
                'vibe-customtypes/vibe-customtypes.php',
                'vibe-shortcodes/vibe-shortcodes.php',
                'wplms-dashboard/wplms-dashboard.php',
                'wplms-assignments/wplms-assignments.php',
                'wplms-front-end/wplms-front-end.php',
            ));
        }else{
            echo '<div class="error"><p>'._x( 'To migrate to 4.0 framework. Please deactivate plugins VibeCourseModule, Vibe CustomTypes, VibeShortcodes, WPLMS Dashboard,WPLMS Assignments, WPLMS Front End','notice in admin screen', 'wplms' ).'<form method="post" action="'.admin_url().'"><input type="submit" name="deactivate_vibe_plugins" value="Deactivate Plugins" class="button-primary"><input type="hidden" name="security" value="'.wp_create_nonce('security').'" /></form></p></div>'; 
        }
    });
}

require_once(dirname(__FILE__).'/includes/class.init.php');
register_activation_hook( __FILE__, array( 'WPLMS_4_Init', 'activate' ) );

add_action( 'admin_init', 'wplms_plugin_update' );
function wplms_plugin_update() {
    /* Load Plugin Updater */
    require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'includes/autoupdate.php' );

    /* Updater Config */
    $config = array(
        'base'      => plugin_basename( __FILE__ ), //required
        'dashboard' => true,
        'repo_uri'  => 'https://wplms.io/',  //required
        'repo_slug' => 'wplms',  //required
    );

    /* Load Updater Class */
    new WPLMS_Plugin_Auto_Update( $config );
}

add_action('plugins_loaded','wplms_plugin_load_translations');
function wplms_plugin_load_translations(){
    $locale = apply_filters("plugin_locale", get_locale(), 'wplms');
    $lang_dir = dirname( __FILE__ ) . '/languages/';
    $mofile        = sprintf( '%1$s-%2$s.mo', 'wplms', $locale );
    $mofile_local  = $lang_dir . $mofile;
    $mofile_global = WP_LANG_DIR . '/plugins/' . $mofile;

    if ( file_exists( $mofile_global ) ) {
        load_textdomain( 'wplms', $mofile_global );
    } else {
        load_textdomain( 'wplms', $mofile_local );
    }  
}

