<?php
/*
Plugin Name: Vibe ShortCodes
Plugin URI: http://www.vibethemes.com
Description: Create unlimited shortcodes
Author: VibeThemes
Version: 3.9.8.1
Author URI: http://www.vibethemes.com
Text Domain: vibe-shortcodes
Domain Path: /languages/
*/

if ( ! defined( 'ABSPATH' ) ) exit;

if( !defined('WPLMS_PLUGIN_INCLUDES_URL')){
    define('WPLMS_PLUGIN_INCLUDES_URL',plugins_url().'/'.WPLMS_PLUGIN_DIR_NAME.'/includes');
}

/*====== BEGIN VSLIDER======*/

include_once('classes/vibeshortcodes.class.php');

include_once('shortcodes.php');
include_once('ajaxcalls.php');
include_once('upload_handler.php');
if(class_exists('WPLMS_ZIP_UPLOAD_HANDLER')){
	new WPLMS_ZIP_UPLOAD_HANDLER();
}
