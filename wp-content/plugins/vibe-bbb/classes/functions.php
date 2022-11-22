<?php
/**
 * Initialise WPLMS Bbb
 *
 * @class       Wplms_Bbb_Actions
 * @author      VibeThemes
 * @category    Admin
 * @package     WPLMS-Bbb/classes
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


function bbb_reminder_options(){
    return apply_filters('zoom_reminder_options',array(
        '3600'=>__('1 Hour','vibe-bbb'),
        '7200'=>__('2 Hour','vibe-bbb'),
        '21600'=>__('6 Hour','vibe-bbb'),
        '43200'=>__('12 Hour','vibe-bbb'),
        '86400'=>__('1 Day','vibe-bbb')
    ));
}