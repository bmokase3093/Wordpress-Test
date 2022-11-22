<?php
/*
Plugin Name: Vibe BigBluebutton
Plugin URI: http://www.Vibethemes.com
Description: Integrates Bigbluebutton with VibeBP
Version: 1.6.3
Author: VibeThemes
Author URI: http://www.vibethemes.com
License: GPLv3
Text Domain: vibe-bbb
Domain Path: /languages/
*/

define( 'VIBE_BBB_API_NAMESPACE', 'vibebbb/v1'  );
define( 'VIBE_BBB_VERSION','1.6.3');

include_once 'classes/autoupdate.php';
include_once 'classes/class.init.php';
include_once 'classes/class.api.php';
include_once 'classes/class.actions.php';
include_once 'classes/class.settings.php';
include_once 'classes/functions.php';


add_action('admin_notices','vibe_bigbluebutton_inactive_notice');
function vibe_bigbluebutton_inactive_notice(){
    if(!is_plugin_active('bigbluebutton/bigbluebutton.php')){

    
    $class = 'notice notice-error is-dismissible';
        $message = sprintf(__( 'Vibe bbb needs %s plugin to be activated and its version should be 3.0.0 or above', 'vibe-bbb' ),'<a href="https://wordpress.org/plugins/bigbluebutton/">Bigbluebutton</a>');

      printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message ); 
    }
}

add_action('plugins_loaded','vibe_bbb_translations');
function vibe_bbb_translations(){
    $locale = apply_filters("plugin_locale", get_locale(), 'vibe-bbb');
    $lang_dir = dirname( __FILE__ ) . '/languages/';
    $mofile        = sprintf( '%1$s-%2$s.mo', 'vibe-bbb', $locale );
    $mofile_local  = $lang_dir . $mofile;
    $mofile_global = WP_LANG_DIR . '/plugins/' . $mofile;

    if ( file_exists( $mofile_global ) ) {
        load_textdomain( 'vibe-bbb', $mofile_global );
    } else {
        load_textdomain('vibe-bbb', $mofile_local );
    }  
}


add_action( 'admin_init', 'vibebp_bbb_plugin_update' );
function vibebp_bbb_plugin_update() {
    /* Load Plugin Updater */
    require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'classes/autoupdate.php' );

    /* Updater Config */
    $config = array(
        'base'      => plugin_basename( __FILE__ ), //required
        'dashboard' => true,
        'repo_uri'  => 'https://wplms.io/',  //required
        'repo_slug' => 'vibe-bbb',  //required
    );

    /* Load Updater Class */
    new Vibe_BBB_Auto_Update( $config );
}