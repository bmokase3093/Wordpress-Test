<?php
/**
 * Plugin Name: Vibe Earnings
 * Plugin URI: https://wplms.io
 * Description: Vibe Earnings 
 * Author: VibeThemes
 * Author URI: https://wplms.io
 * Version: 1.4
 * Text Domain: vibe-earnings
 * Domain Path: /languages
 * Tested up to: 5.6
 *
 * @package WPLMS
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'VIBE_EARNINGS_PLUGIN_VERSION', '1.4' );


define( 'VIBE_EARNINGS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'VIBE_EARNINGS_PLUGIN_URL', untrailingslashit(plugin_dir_url( __FILE__ )) );
define( 'VIBE_EARNINGS_PLUGIN_INCLUDES_URL', untrailingslashit(plugin_dir_url( __FILE__ )).'/includes' );
define( 'VIBE_EARNINGS_PLUGIN_FILE', __FILE__ );
define( 'VIBE_EARNINGS_PLUGIN_BASE', plugin_basename( __FILE__ ) );



if(function_exists('bp_is_active')){
	require_once(VIBE_EARNINGS_PLUGIN_DIR.'/includes/class.init.php');
	require_once(VIBE_EARNINGS_PLUGIN_DIR.'/includes/class.settings.php');
    add_action('plugins_loaded',function(){
        require_once(VIBE_EARNINGS_PLUGIN_DIR.'/includes/class.api.php');
    });
}



add_action( 'admin_init', 'vibebp_earnings_plugin_update' );
function vibebp_earnings_plugin_update() {
    /* Load Plugin Updater */
    require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'includes/autoupdate.php' );

    /* Updater Config */
    $config = array(
        'base'      => plugin_basename( __FILE__ ), //required
        'dashboard' => true,
        'repo_uri'  => 'https://wplms.io/',  //required
        'repo_slug' => 'vibe-earnings',  //required
    );

    /* Load Updater Class */
    new Vibe_Earnings_Auto_Update( $config );
}