<?php
/*
Plugin Name: Vibe Course Module
Plugin URI: http://www.VibeThemes.com
Description: This is the Course module for WPLMS WordPress Theme by VibeThemes
Version: 3.9.8.2
Requires at least: WP 3.8, BuddyPress 1.9 
Tested up to: 4.8
License: (Themeforest License : http://themeforest.net/licenses)
Author: Mr.Vibe 
Author URI: http://www.VibeThemes.com
Network: false
Text Domain: vibe
Domain Path: /languages/
*/


// FILE PATHS of Course Module
define( 'BP_COURSE_MOD_PLUGIN_DIR', dirname( __FILE__ ) );

/* Database Version for Course Module */
define ( 'BP_COURSE_DB_VERSION', '4.0' );
define ( 'BP_COURSE_CPT', 'course' );

if ( ! defined( 'BP_COURSE_SLUG' ) ){
    define ( 'BP_COURSE_SLUG','course' );
}

if( !defined('VIBE_CM_PLUGIN_URL')){
    define('VIBE_CM_PLUGIN_URL',plugins_url().'/'.WPLMS_PLUGIN_DIR_NAME.'/includes');
}
/* Only load the component if BuddyPress is loaded and initialized. */
function bp_course_init() {
	// Because our loader file uses BP_Component, it requires BP 1.5 or greater.
	if ( version_compare( BP_VERSION, '1.8', '>' ) )
		require( dirname( __FILE__ ) . '/includes/bp-course-loader.php' );
}
add_action( 'bp_include', 'bp_course_init' );

function bp_course_version(){
    return WPLMS_PLUGIN_VERSION;
}
