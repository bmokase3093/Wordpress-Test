<?php

if ( !defined( 'ABSPATH' ) ) exit;

if( !defined('WPLMS_DASHBOARD_URL')){
    define('WPLMS_DASHBOARD_URL',plugins_url().'/wplms-dashboard');
}


include_once 'includes/functions.php';
include_once 'includes/dashboard.php';

include_once 'includes/class.dashboard.api.php';
include_once 'includes/widgets/student/activity_widget.php';
include_once 'includes/widgets/student/course_progress.php';
include_once 'includes/widgets/student/contact_users.php';
include_once 'includes/widgets/student/text_widget.php';
include_once 'includes/widgets/student/todo_task.php';
include_once 'includes/widgets/student/student_stats.php';
include_once 'includes/widgets/student/student_simple_stats.php';
include_once 'includes/widgets/student/notes_discussions.php';
include_once 'includes/widgets/student/my_modules.php';
include_once 'includes/widgets/student/news.php';
include_once 'includes/widgets/instructor/break.php';
include_once 'includes/widgets/instructor/dash_instructor_stats.php';
include_once 'includes/widgets/instructor/instructor_stats.php';
include_once 'includes/widgets/instructor/instructor_commissions.php';
include_once 'includes/widgets/instructor/announcements.php';
include_once 'includes/widgets/instructor/instructing_modules.php';
include_once 'includes/widgets/instructor/instructor_students.php';

include_once 'includes/widgets/student/weather.php';
