<?php
/**
 * SETUP WIZARD WPLMS 4
 *
 * @author      VibeThemes
 * @category    Admin
 * @package     WPLMS Plugin
 * @version     4.0
 */

 if ( ! defined( 'ABSPATH' ) ) exit;

class WPLMS_4_Setup_Wizard{

    public static $instance;
    public static function init(){
    if ( is_null( self::$instance ) )
        self::$instance = new WPLMS_4_Setup_Wizard();

        return self::$instance;
    }

    private function __construct(){

    	add_filter('vibebp_setup_wizard',array($this,'wplms_setup_wizard'));
        add_action('vibebp_import_layout_course',array($this,'import_course_layout'));
        add_action('vibebp_import_layout_course_directory',array($this,'import_course_directory_layout'));
        
    }

    function wplms_setup_wizard($steps){
    	
        

        $steps['installation']['steps'][0]['features'][]=array(
                                    'type'=>'wplms',
                                    'key' => 'course',
                                    'icon'=> '<svg width="100%" height="100%" viewBox="0 0 100 100" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;">
    <g transform="matrix(4.16667,0,0,4.16667,0,0)">
        <path d="M22,0L22,13C22,16.419 16.753,16.745 13.744,16C13.744,16 15.266,24 10.409,24L2,24L2,0L22,0ZM15.909,18.223C17.956,18.701 20.714,17.944 22,17.044C20.506,19.041 16.769,22.751 14.568,23.925C15.723,22.757 16.131,19.691 15.909,18.223ZM11,17L6,17L6,18L11,18L11,17ZM11,15L6,15L6,16L11,16L11,15ZM18,13L6,13L6,14L18,14L18,13ZM18,11L6,11L6,12L18,12L18,11ZM10.691,7.515L8.636,7.517L8.246,8.689L7,8.69L9.113,3.001L10.199,3L12.332,8.686L11.086,8.687L10.691,7.515ZM15.064,5.501L16.474,5.5L16.475,6.519L15.065,6.52L15.066,8.114L13.992,8.115L13.991,6.521L12.577,6.522L12.576,5.502L13.99,5.501L13.989,3.974L15.063,3.973L15.064,5.501ZM8.952,6.567L10.374,6.566L9.657,4.438L8.952,6.567Z"/>
    </g>
</svg>',
                                    'label'=>__('Courses','wplms'),
                                    'required'=>1,
                                    'is_active'=>1
                                );
        $steps['installation']['steps'][0]['features'][]=array(
                                    'type'=>'wplms',
                                    'key' => 'quiz',
                                    'icon'=> '<svg width="100%" height="100%" viewBox="0 0 100 100" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;">
    <g transform="matrix(4.16667,0,0,4.16667,0,0)">
        <path d="M15.812,4.819C15.482,4.478 15.5,3.942 15.84,3.612L19.309,0.247C19.479,0.083 19.696,0 19.912,0C20.131,0 20.35,0.085 20.516,0.257L15.812,4.819ZM10.107,13.391C10.037,13.46 10,13.553 10,13.646C10,13.84 10.158,14 10.354,14C10.443,14 10.532,13.967 10.601,13.9L11.184,13.333L10.691,12.824L10.107,13.391ZM15.031,6.839L13.037,8.772C11.965,9.811 11.418,10.818 10.913,12.223L11.794,13.132C13.213,12.671 14.236,12.156 15.308,11.116L17.302,9.182L15.031,6.839ZM20.847,0.881L15.71,5.863L18.296,8.534L23.434,3.554C23.811,3.188 24,2.703 24,2.217C24,0.593 22.032,-0.269 20.847,0.881ZM9,13L5,13L5,14L9,14L9,13ZM18,11.65L18,13.543C18,17.65 12,16 12,16C12,16 13.518,22 9.362,22L2,22L2,2L14.629,2L16.691,0L0,0L0,24L10.189,24C13.352,24 20,16.777 20,14.386L20,9.699L18,11.65Z" style="fill-rule:nonzero;"/>
    </g>
</svg>',
                                    'label'=>__('Quizzes','wplms'),
                                    'required'=>1,
                                    'is_active'=>1
                                );
        $steps['installation']['steps'][0]['features'][]=array(
                                    'type'=>'wplms',
                                    'key' => 'assignments',
                                    'icon'=> '<svg width="100%" height="100%" viewBox="0 0 100 100" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;">
    <g transform="matrix(4.16667,0,0,4.16667,0,0)">
        <path d="M5.48,10.089L7.063,8.625C8.917,9.521 10.091,10.203 12.173,11.688C16.089,7.246 18.676,4.992 23.485,2L24,3.186C20.035,6.646 17.13,10.5 12.949,18C10.37,14.962 8.648,13.026 5.48,10.089ZM18,13.406L18,20L2,20L2,4L17.141,4C17.987,3.317 18.875,2.659 19.832,2L0,2L0,22L20,22L20,10.491C19.344,11.379 18.682,12.345 18,13.406Z" style="fill-rule:nonzero;"/>
    </g>
</svg>',
                                    'label'=>__('Assignments','wplms'),
                                    'required'=>1,
                                    'is_active'=>1
                                );
        $steps['installation']['steps'][0]['features'][]=array(
                                    'type'=>'wplms',
                                    'key' => 'drip',
                                    'icon'=> '<svg width="100%" height="100%" viewBox="0 0 100 100" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;">
    <g transform="matrix(4.16667,0,0,4.16667,0,0)">
        <path d="M4.713,17.644C5.186,17.35 5.863,17.034 6.734,16.755C7.658,17.344 9.438,18 12,18C14.562,18 16.342,17.344 17.266,16.755C18.137,17.033 18.815,17.35 19.288,17.644C17.714,19.386 14.367,20 12,20C9.633,20 6.287,19.386 4.713,17.644ZM18.921,13.104C18.696,13.744 18.387,14.345 18.001,14.89C20.534,15.729 22,17.001 22,18C22,19.631 18.104,22 12,22C5.896,22 2,19.631 2,18C2,17.001 3.466,15.729 5.999,14.89C5.613,14.345 5.305,13.744 5.08,13.104C2.01,14.19 0,15.977 0,18C0,21.313 5.373,24 12,24C18.629,24 24,21.313 24,18C24,15.977 21.99,14.191 18.921,13.104ZM17.334,10.716C17.334,13.635 14.948,16 12,16C9.053,16 6.667,13.635 6.667,10.716C6.667,7.799 8.754,4.798 12,0C15.247,4.798 17.334,7.799 17.334,10.716ZM10,7.073C10,8.659 12.667,8.232 12.667,5.626C12.667,4.737 12.293,3.897 11.982,3.413C11.326,4.377 10,5.985 10,7.073Z" style="fill-rule:nonzero;"/>
    </g>
</svg>',
                                    'label'=>__('Drip Courses','wplms'),
                                    'required'=>1,
                                    'is_active'=>1
                                );
        $steps['installation']['steps'][0]['features'][]=array(
                                    'type'=>'wplms',
                                    'key' => 'certificates',
                                    'icon'=> '<svg width="100%" height="100%" viewBox="0 0 100 100" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;">
    <g transform="matrix(4.16667,0,0,4.16667,0,0)">
        <path d="M14.969,7.547L15,7.738C15,7.931 14.904,8.117 14.736,8.234C14.198,8.606 14.269,8.512 14.066,9.119C13.982,9.372 13.736,9.543 13.461,9.543L13.459,9.543C12.795,9.541 12.91,9.505 12.376,9.881C12.264,9.961 12.132,10 12,10C11.868,10 11.736,9.961 11.624,9.882C11.09,9.506 11.205,9.542 10.541,9.544L10.539,9.544C10.264,9.544 10.018,9.373 9.934,9.12C9.73,8.513 9.801,8.606 9.265,8.234C9.096,8.117 9,7.931 9,7.738L9.031,7.548C9.238,6.944 9.239,7.06 9.031,6.454L9,6.263C9,6.07 9.096,5.884 9.265,5.766C9.801,5.394 9.73,5.489 9.934,4.881C10.018,4.628 10.264,4.457 10.539,4.457L10.541,4.457C11.203,4.459 11.085,4.498 11.624,4.119C11.736,4.039 11.868,4 12,4C12.132,4 12.264,4.039 12.376,4.118C12.91,4.494 12.795,4.458 13.459,4.456L13.461,4.456C13.736,4.456 13.982,4.627 14.066,4.88C14.269,5.487 14.198,5.393 14.736,5.765C14.904,5.883 15,6.069 15,6.262L14.969,6.453C14.762,7.057 14.761,6.941 14.969,7.547ZM13.5,6.349L13.035,5.885L11.625,7.331L10.965,6.704L10.5,7.168L11.625,8.259L13.5,6.349ZM16,11L8,11L8,12L16,12L16,11ZM15,13L9,13L9,14L15,14L15,13ZM16,15L8,15L8,16L16,16L16,15ZM8.229,19C7.459,22.458 5,22.5 5,20.375L5,5.042C5,3.709 4.938,2.656 4,2L17,2C18.354,2 19,2.625 19,4L19,17L21,17L21,4C21,1.979 20.156,0 17,0L5,0C1.506,0 0,2.906 0,5L3,5L3,21C3,22.657 4.343,24 6,24L20,24C22.688,23.469 23.875,21.062 24,19L8.229,19Z" style="fill-rule:nonzero;"/>
    </g>
</svg>',
                                    'label'=>__('Certificates','wplms'),
                                    'required'=>1,
                                    'is_active'=>1
                                );
        $steps['installation']['steps'][0]['features'][]=array(
                                    'type'=>'wplms',
                                    'key' => 'badges',
                                    'icon'=> '<svg width="100%" height="100%" viewBox="0 0 100 100" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;">
    <g transform="matrix(4.16667,0,0,4.16667,0,0)">
        <path d="M23.873,9.81C23.96,9.559 24,9.301 24,9.046C24,8.276 23.62,7.532 22.945,7.065C20.792,5.573 21.077,5.948 20.266,3.522C19.926,2.509 18.947,1.825 17.842,1.825L17.835,1.825C15.182,1.834 15.642,1.976 13.501,0.471C13.055,0.157 12.527,0 12,0C11.473,0 10.945,0.157 10.498,0.471C8.342,1.986 8.812,1.833 6.164,1.824L6.157,1.824C5.053,1.824 4.073,2.509 3.735,3.521C2.923,5.953 3.201,5.577 1.057,7.065C0.38,7.534 0,8.277 0,9.048C0,9.302 0.042,9.559 0.127,9.81C0.958,12.238 0.956,11.772 0.127,14.19C0.042,14.441 0,14.697 0,14.952C0,15.722 0.38,16.466 1.057,16.935C3.203,18.425 2.925,18.048 3.736,20.478C4.074,21.491 5.053,22.175 6.158,22.175L6.165,22.175C8.818,22.166 8.358,22.023 10.499,23.528C10.945,23.842 11.473,24 12,24C12.527,24 13.055,23.842 13.502,23.529C15.643,22.025 15.181,22.167 17.836,22.176L17.843,22.176C18.947,22.176 19.927,21.491 20.267,20.479C21.078,18.052 20.792,18.427 22.946,16.936C23.62,16.467 24,15.723 24,14.953C24,14.699 23.96,14.441 23.873,14.19C23.042,11.762 23.046,12.227 23.873,9.81ZM12,20.5C7.306,20.5 3.5,16.694 3.5,12C3.5,7.306 7.306,3.5 12,3.5C16.694,3.5 20.5,7.306 20.5,12C20.5,16.694 16.694,20.5 12,20.5ZM10.75,15.958L7,12.321L8.549,10.773L10.75,12.861L15.451,8.042L17,9.59L10.75,15.958Z" style="fill-rule:nonzero;"/>
    </g>
</svg>',
                                    'label'=>__('Badges','wplms'),
                                    'required'=>1,
                                    'is_active'=>1
                                );
        $steps['installation']['steps'][0]['features'][]=array(
                                    'type'=>'wplms',
                                    'key' => 'instructors',
                                    'icon'=> '<svg width="100%" height="100%" viewBox="0 0 100 100" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;">
    <g transform="matrix(4.16667,0,0,4.16667,0,0)">
        <path d="M24,17.99L16.269,17.989L19,21.989L17.689,21.989L14.953,17.989L13,17.989L10.264,21.989L9,21.989L11.732,17.989L8.782,17.989L8.782,16.989L17,16.989L17,15.989L20,15.989L20,16.989L23,16.989L23,2.989L6,2.989L6,3.436L5,3.436L5,1.989L24,1.989L24,17.99ZM6.759,8.99C7.408,8.99 8.052,8.777 8.451,8.554C9.206,8.134 11.146,6.911 11.936,6.43C12.151,6.3 12.432,6.348 12.59,6.544L12.599,6.554C12.763,6.759 12.744,7.054 12.556,7.234L9.185,10.448C8.664,10.946 8.363,11.631 8.332,12.35C8.237,14.557 8.071,19.262 8,21.184C7.984,21.634 7.614,21.99 7.164,21.99L7.163,21.99C6.719,21.99 6.377,21.642 6.327,21.202C6.216,20.22 5.998,17.923 5.9,16.99C5.86,16.606 5.621,16.377 5.316,16.376C5.012,16.374 4.793,16.602 4.768,16.984C4.706,17.905 4.502,20.233 4.426,21.205C4.392,21.646 4.029,21.99 3.586,21.99L3.585,21.99C3.133,21.99 2.762,21.634 2.743,21.181C2.646,18.841 2.374,12.218 2.374,12.218L1.087,14.549C0.947,14.803 0.642,14.913 0.372,14.809L0.371,14.808C0.143,14.72 0,14.503 0,14.268L0.022,14.111L1.266,9.718C1.388,9.288 1.781,8.991 2.229,8.991L6.759,8.991L6.759,8.99ZM14,10.99L19,10.99L19,9.99L14,9.99L14,10.99ZM14,8.99L21,8.99L21,7.99L14,7.99L14,8.99ZM5.374,3.99C6.615,3.99 7.624,4.998 7.624,6.24C7.624,7.482 6.615,8.49 5.374,8.49C4.132,8.49 3.124,7.482 3.124,6.24C3.124,4.998 4.132,3.99 5.374,3.99ZM14,6.99L21,6.99L21,5.99L14,5.99L14,6.99Z"/>
    </g>
</svg>',
                                    'label'=>__('Instructors','wplms'),
                                    'required'=>1,
                                    'is_active'=>1
                                );
        $steps['installation']['steps'][1]['layouts'][]=array(
                                    'key'=>'course',
                                    'type'=>'checkbox',
                                    'label'=>_x('Import Course layout and Card','installation','vibebp'),
                                );
        $steps['installation']['steps'][1]['layouts'][]=array(
                                    'key'=>'course_directory',
                                    'type'=>'checkbox',
                                    'label'=>_x('Import Course Directory','installation','vibebp'),
                                );
        $steps['installation']['steps'][2]['access'][]=array(
                                    'key'=>'course_directory',
                                    'type'=>'checkbox',
                                    'label'=>_x('Disable Public Course Directories','wplms'),
                                );
        
    	return $steps;
    }

    function import_course_layout(){
        $path = plugin_dir_path(__FILE__).'../sampledata/course_layouts.json';
        if(file_exists($path)){
            $content = file_get_contents($path);
            $content = json_decode($content,true);
            foreach($content as $post_type=>$posts){
                foreach($posts as $post){
                    $post['post_type'] = $post_type;
                    $post['post_title'] = sanitize_text_field($post['post_title']);
                    $post['post_content'] = sanitize_text_field($post['post_content']);
                    wp_insert_post($post);
                }
            }
        }
    } 

    function import_course_directory_layout(){

        $path = plugin_dir_path(__FILE__).'../sampledata/course_directory.json';
        if(file_exists($path)){
            $content = file_get_contents($path);
            $content = json_decode($content,true);
            foreach($content as $post_type=>$posts){
                foreach($posts as $post){
                    $post['post_type'] = $post_type;
                    $post['post_title'] = sanitize_text_field($post['post_title']);
                    $post['post_content'] = sanitize_text_field($post['post_content']);
                    wp_insert_post($post);
                }
            }
        }
    }
}

WPLMS_4_Setup_Wizard::init();