<?php
/**
 * Plugin Name: Vibe HelpDesk
 * Plugin URI: https://vibebp.com
 * Description: HelpDesk for VibeApp
 * Author: VibeThemes
 * Author URI: https://www.vibethemes.com
 * Version: 1.3.3
 * Text Domain: vibe-helpdesk
 * Domain Path: /languages
 * Tested up to: 5.6
 *
 * @package Vibe-Helpdesk
 */

if ( ! defined( 'ABSPATH' ) ) exit;


if( !defined('VIBE_HELPDESK_SLUG')){
	define( 'VIBE_HELPDESK_SLUG', 'helpdesk' ); 
}
if( !defined('VIBE_HELPDESK_API_NAMESPACE')){
	define( 'VIBE_HELPDESK_API_NAMESPACE', 'vibehd/v1' ); 
}
if( !defined('VIBE_HELPDESK_PLUGIN_DIR')){
    define( 'VIBE_HELPDESK_PLUGIN_DIR', plugin_dir_path( __FILE__ ) ); 
}


if(!defined('VIBEHELPDESK_VERSION')){
    define('VIBEHELPDESK_VERSION','1.3.3');
}

defined('Vibe_BP_API_FORUMS_TYPE') or define('Vibe_BP_API_FORUMS_TYPE', 'bbp');
defined('VIBEHELPDESK_CANNED_POST_TYPE') or define('VIBEHELPDESK_CANNED_POST_TYPE', 'canned_response');
defined('VIBEHELPDESK_TOPIC_PRIORITY') or define('VIBEHELPDESK_TOPIC_PRIORITY', 'vibehd-topic-priority');

include_once 'includes/class.api.php';
include_once 'includes/class.settings.php';
include_once 'includes/class.init.php';
include_once 'includes/class.component.php';
include_once 'includes/class.touchpoints.php';
include_once 'includes/widgets/helpdesk-stats.php';
include_once 'includes/widgets/helpdesk-chart.php';

add_action('plugins_loaded','vibe_helpdesk_translations');
function vibe_helpdesk_translations(){
    $locale = apply_filters("plugin_locale", get_locale(), 'vibe-helpdesk');
    $lang_dir = dirname( __FILE__ ) . '/languages/';
    $mofile        = sprintf( '%1$s-%2$s.mo', 'vibe-helpdesk', $locale );
    $mofile_local  = $lang_dir . $mofile;
    $mofile_global = WP_LANG_DIR . '/plugins/' . $mofile;

    if ( file_exists( $mofile_global ) ) {
        load_textdomain( 'vibe-helpdesk', $mofile_global );
    } else {
        load_textdomain( 'vibe-helpdesk', $mofile_local );
    }  
}


add_action( 'init', 'vibebp_helpdesk_plugin_update' );
function vibebp_helpdesk_plugin_update() {
    /* Load Plugin Updater */
    require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'includes/autoupdate.php' );

    /* Updater Config */
    $config = array(
        'base'      => plugin_basename( __FILE__ ), //required
        'dashboard' => true,
        'repo_uri'  => 'https://wplms.io/',  //required
        'repo_slug' => 'vibe-helpdesk',  //required
    );

    /* Load Updater Class */
    new VibeBp_Helpdesk_Auto_Update( $config );
}
