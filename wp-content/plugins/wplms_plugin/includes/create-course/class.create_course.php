<?php
if ( ! defined( 'ABSPATH' ) ) exit;




if(!class_exists('WPLMS_Create_Course'))
{   
    class WPLMS_Create_Course
    {
    	
        public static $instance;
        public static function init(){
            if ( is_null( self::$instance ) )
                self::$instance = new WPLMS_Create_Course();
            return self::$instance;
        }

    	function __construct(){
           add_shortcode('wplms_create_course',array($this,'wplms_create_course'));
           add_action('wp_footer',array($this,'wplms_create_course_data'),99);
		} 

        function wplms_create_course(){

            if(!is_page() || !function_exists('bp_is_member') || !bp_is_member() || !is_wplms_4_0()){
                return;
            }

            if(is_page()){
                if(!has_shortcode( $post->post_content, 'vibebp_profile')){
                    return;
                }
            }

            ob_start();
            echo '<div id="wplms_create_course"></div>';
            $this->wplms_create_course_data();
            $html = ob_get_clean();
            return $html;
        }

        function wplms_create_course_data(){
            $blog_id = '';
            if(function_exists('get_current_blog_id')){
                $blog_id = get_current_blog_id();
            }
            $tips = WPLMS_tips::init();
            
            $wplms_create_course_data = apply_filters('wplms_create_course_settings',array(
                'api_url'=> apply_filters('vibebp_rest_api',get_rest_url($blog_id,WPLMS_API_NAMESPACE)),
                'timestamp'=>time(),
                'settings'=>array(
                    'assigments_enabled'=>(function_exists('wplms_assignments_stats')),
                    'create_taxonomy_term_caps'=>'edit_posts',
                    'course_approval'=>false,
                    'advanced_video_format_hls'=>empty($tips->lms_settings['general']['advanced_video_format_hls'])?0:1,
                    'advanced_video_format_360'=>empty($tips->lms_settings['general']['advanced_video_format_360'])?0:1,
                    'advanced_video_format_dash'=>empty($tips->lms_settings['general']['advanced_video_format_dash'])?0:1,
                ),
                'duration'=>array(
                        array('value'=>0,'label'=>__('Select option','wplms')),
                        array('value'=>1,'label'=>__('Seconds','wplms')),
                        array('value'=>60,'label'=>__('Minutes','wplms')),
                        array('value'=>3600,'label'=>__('Hours','wplms')),
                        array('value'=>86400,'label'=>__('Days','wplms')),
                        array('value'=>604800,'label'=>__('Weeks','wplms')),
                        array('value'=>2592000,'label'=>__('Months','wplms')),
                        array('value'=>31536000,'label'=>__('Years','wplms')),
                ),
                'translations'=>array(
                    'send_for_approval'=>_x('Send for approval','','wplms'),
                    'date'=>_x('Date','','wplms'),
                    'load_instrcutor_edit'=>_x('Load Instrcutor Edit','','wplms'),
                    'select_option'=>_x('Select Option','','wplms'),
                    'course'=>_x('Course','','wplms'),
                    'course_autosaved'=>_x('Course auto-saved','','wplms'),
                    'reset'=>_x('Reset','','wplms'),
                    'load_draft' => _x('Load Draft','api','wplms'),
                    'expired' => _x('Expired','','wplms'),
                    'days' => _x('Days','','wplms'),
                    'hours' => _x('Hours','','wplms'),
                    'minutes' => _x('Minutes','','wplms'),
                    'seconds' => _x('Seconds','','wplms'),
                    'type_keyword'=>_x('Type a keyword','','wplms'),
                    'yes'=>_x('Yes','','wplms'),
                    'no'=>_x('No','','wplms'),
                    'add' => _x('Add','','wplms'),
                    'add_unit' => _x('Add unit','','wplms'),
                    'add_section' => _x('Add section','','wplms'),
                    'add_quiz' => _x('Add quiz','','wplms'),
                    'add_assignment' => _x('Add assignment','','wplms'),
                    'section_name' => _x('Section Name','','wplms'),
                    'set' => _x('Set','','wplms'),
                    'add_option' => _x('Add Option','','wplms'),
                    'add_dynamic_questions_tag' => _x('ADD DYNAMIC QUESTION SECTION TAGS','','wplms'),
                    'question_tags' => _x('Question Tags','','wplms'),
                    'per_marks' => _x('per Marks','','wplms'),
                    'number' => _x('Number','','wplms'),
                    'remove' => _x('Remove','','wplms'),
                    'description' => _x('Description','','wplms'),
                    'sale_price_error' => _x('Sale Price cannot be greater than Regular Price','','wplms'),
                    'add_new'=> _x('Add New','','wplms'),
                    'type_here'=> _x('Type here','API','wplms'),
                    'unlimited_duration'=> _x('Unlimited Duration','API','wplms'),
                    'changes'=> _x('changes','create course','wplms'),
                    'private'=>_x('Private','create course','wplms'),
                    'public'=>_x('Public','create course','wplms'),
                    'hidden'=>_x('Hidden','create course','wplms'),
                    'build_curriculum'=>_x('Build Curriculum',' curriculum type','wplms'),
                    'upload_package'=>_x('Upload Package',' curriculum type','wplms'),
                    'select'=>_x('Select','create course','wplms'),
                    'add_new'=>_x('Add New','create course','wplms'),
                    'change_curriculum_type'=>_x('Change curriculum type',' create course','wplms'),
                    'cancel'=>_x('Cancel','create course','wplms'),
                    'course_plan'=>_x('Course Plan','create course','wplms'),
                    'save_draft'=>_x('Save Draft','create course','wplms'),
                    'save'=>_x('Save','create course','wplms'),
                    'type_to_search'=>_x('Title , type to search... ',' create course','wplms'),
                    'create_group'=> _x('Create Group','create course','wplms'),
                    'select_group'=>_x('Select Group','create course','wplms'),
                    'create_forum'=> _x('Create Forum','create course','wplms'),
                    'select_forum'=>_x('Select Forum','create course','wplms'),
                    'enter_question_marks'=>_x('Enter marks',' create course','wplms'),
                    'total_marks'=>_x('Total marks',' create course','wplms'),
                    'add_cpt'=>array(
                        'assignment'=>array('add'=>__('Add Assignment','wplms'),'create'=>__('Create Assignment','wplms'),'search'=>__('Search Assignment','wplms')),
                        'quiz'=>array('add'=>__('Add Quiz','wplms'),'create'=>__('Create Quiz','wplms'),'search'=>__('Search Quiz','wplms')),
                        'unit'=>array('add'=>__('Add Unit','wplms'),'create'=>__('Create Unit','wplms'),'search'=>__('Search Unit','wplms')),
                        'question'=>array('add'=>__('Add Question','wplms'),'create'=>__('Create Question','wplms'),'search'=>__('Search Question','wplms'),'upload'=>__('Upload Questions')),
                        'product'=>array('add'=>__('Add Product','wplms'),'create'=>__('Create Product','wplms'),'search'=>__('Search Product','wplms'),'add_bundle'=>_x('Add bundle','','wplms'),'bundle'=>_x('Bundle','','wplms')),
                    ),
                    'select_quesiton_type'=>_x('Select Question Type',' create course','wplms'),
                    'no_price' =>_x('No Price',' create course','wplms'),
                    'missing_title'=>_x('Title Missing',' create course','wplms'),
                    'package_file_missing'=>_x('Package file Missing',' create course','wplms'),
                    'search_package'=>_x('Search Package',' create course','wplms'),
                    'select_existing_package'=>_x('Select existing package',' create course','wplms'),
                    'set_package'=>_x('Set package',' create course','wplms'),
                    'complete_course'=>_x('Complete',' create course','wplms'),
                    'saving_content_message'=>_x('Saving the element does not add in curriculum. To add to curriculum make sure you save the curriculum.',' create course','wplms'),
                    'enter_video_link'=>_x('Copy & Paste video link',' create course','wplms'),
                    'save_post_to_edit_with_elementor'=>_x('Save unit to edit with elementor',' create course','wplms'),
                    'collapse_into_sections'=>_x('Collapse into sections',' create course','wplms'),
                    'duplicate'=>_x('Duplicate Module',' create course','wplms'),
                ),
            ));
            $color = '#54bbff';
            if(function_exists('bp_wplms_get_theme_color') && !empty(bp_wplms_get_theme_color())){
                $color = bp_wplms_get_theme_color();
            }
            echo '<script>var wplms_create_course_data = '.json_encode($wplms_create_course_data).'</script>';

            
        }
	}
	
}

WPLMS_Create_Course::init();
