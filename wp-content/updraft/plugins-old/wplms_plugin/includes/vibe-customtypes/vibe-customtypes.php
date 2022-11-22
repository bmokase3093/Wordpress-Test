<?php
/*
Plugin Name: Vibe Custom Types
Plugin URI: http://www.vibethemes.com/
Description: This plugin creates Custom Post Types and Custom Meta boxes for WPLMS theme.
Version: 3.9.8.1
Author: Mr.Vibe
Author URI: http://www.vibethemes.com/
Text Domain: vibe-customtypes
Domain Path: /languages/
*/
if ( !defined( 'ABSPATH' ) ) exit;
/*  Copyright 2013 VibeThemes  (email: vibethemes@gmail.com) */

if( !defined('WPLMS_PLUGIN_INCLUDES_URL')){
    define('WPLMS_PLUGIN_INCLUDES_URL',plugins_url().'/'.WPLMS_PLUGIN_DIR_NAME.'/includes');
}
if( !defined('VIBE_PLUGIN_PATH')){
    define('VIBE_PLUGIN_PATH',plugin_dir_path(__FILE__));
}

/*====== BEGIN INCLUDING FILES ======*/

include_once('custom-post-types.php');
include_once('includes/errorhandle.php');
include_once('includes/featured.php');
include_once('includes/statistics.php');
include_once('includes/musettings.php');
include_once('includes/member_types.php');
include_once('includes/group_types.php');
include_once('includes/course_settings.php');

include_once('includes/permalinks.php');
include_once('includes/caching.php');
include_once('includes/tips.php');
include_once('metaboxes/meta_box.php');
include_once('metaboxes/library/vibe-editor.php');
include_once('includes/reports/commissions.php');


include_once('custom_meta_boxes.php');
include_once('includes/api/class-api-wp.php');
/*====== INSTALLATION HOOKs ======*/        

if(defined('ELEMENTOR_VERSION')){
    include_once('includes/elementor/elementor_connect.php');    
    include_once('includes/elementor/fix.php');    
}

include_once('metaboxes/library/vc-mapper/vc-mapper.php');

register_activation_hook(__FILE__,'register_lms');
register_activation_hook(__FILE__,'register_popups', 11);
register_activation_hook(__FILE__,'register_testimonials', 12);
register_activation_hook(__FILE__,'flush_rewrite_rules', 20);
