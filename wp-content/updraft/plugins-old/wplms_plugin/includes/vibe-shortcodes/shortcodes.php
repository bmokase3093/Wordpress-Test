<?php
//Notice : ThimPress do not copy.

if ( ! defined( 'ABSPATH' ) ) exit;
 
Class Vibe_Define_Shortcodes{

    function __construct(){
        $this->rand = rand(0,99);
        add_shortcode('d', array($this,'vibe_dropcaps'));
        add_shortcode('pullquote', array($this, 'vibe_pullquote'));
        add_shortcode('sell_content', array($this,'vibe_sell_content'));
        add_shortcode('social_buttons', array($this,'vibe_social_buttons'));
        add_shortcode('social_icons', array($this, 'vibe_social_sharing_buttons'));
        add_shortcode('number_counter', array($this,'vibe_number_counter'));
        add_shortcode('vibe_container', array($this,'vibe_container'));
        add_shortcode('img', array($this,'vibe_img'));
        add_shortcode('allbadges', array($this,'vibe_allbages'));
        add_shortcode('instructor', array($this,'vibe_instructor'));
        add_shortcode('divider', array($this,'vibe_divider'));
        add_shortcode('course', array($this,'vibe_course'));
        add_shortcode('icon', array($this,'vibe_icon'));
        add_shortcode('iframevideo', array($this,'vibe_iframevideo'));
        add_shortcode('iframe', array($this,'vibe_iframe'));
        add_shortcode('roundprogress', array($this, 'vibe_roundprogress'));
        add_shortcode('pass_fail', array($this,'vibe_pass_fail'));
        add_shortcode('course_search', array($this,'vibe_course_search'));
        add_shortcode('question', array($this,'vibe_question'));
        add_shortcode('vibe_site_stats', array($this,'vibe_site_stats'));
        add_shortcode('course_product', array($this,'vibe_course_product_details'));
        add_shortcode('match', array($this,'vibe_match'));
        add_shortcode('select', array($this,'vibe_select'));
        add_shortcode('fillblank', array($this,'vibe_fillblank'));
        add_shortcode('form_element', array($this,'form_element'));
        add_shortcode('form', array($this,'vibeform'));
        add_shortcode('progressbar', array($this,'progressbar'));
        add_shortcode('heading', array($this,'heading'));
        add_shortcode('gallery', array($this,'gallery'));
        add_shortcode('map', array($this,'gmaps'));
        add_shortcode('popup', array($this,'vibe_popupajax'));
        add_shortcode('tagline', array($this,'tagline'));
        add_shortcode('tooltip', array($this,'tooltip'));
        add_shortcode('tab', array($this,'vibe_tab' ));
        add_shortcode('tabs', array($this,'vibe_tabs' ));
        add_shortcode('note', array($this,'note'));
        add_shortcode('wpml_lang_selector', array($this,'wpml_shortcode_func' ));
        add_shortcode('one_half', array($this,'one_half'));
        add_shortcode('one_third', array($this,'one_third'));
        add_shortcode('one_fourth', array($this,'one_fourth'));
        add_shortcode('three_fourth', array($this,'three_fourth'));
        add_shortcode('two_third', array($this,'two_third'));
        add_shortcode('one_fifth', array($this,'one_fifth'));
        add_shortcode('two_fifth', array($this,'two_fifth'));
        add_shortcode('three_fifth', array($this,'three_fifth'));
        add_shortcode('four_fifth', array($this,'four_fifth'));
        add_shortcode('team_social', array($this,'team_social'));
        add_shortcode('team_member', array($this,'team_member'));
        add_shortcode('button', array($this,'button'));
        add_shortcode('alert', array($this,'alert'));
        add_shortcode('agroup', array($this,'agroup'));
        add_shortcode('accordion', array($this,'accordion'));
        add_shortcode('testimonial', array($this,'testimonial'));
        add_shortcode('user_only', array($this,'vibe_useronly'));
        add_shortcode('instructor_only', array($this,'vibe_instructoronly'));
        add_shortcode('certificate_student_name', array($this,'vibe_certificate_student_name'));
        add_shortcode('certificate_student_photo', array($this,'vibe_certificate_student_photo'));
        add_shortcode('certificate_student_email', array($this,'vibe_certificate_student_email'));
        add_shortcode('certificate_course', array($this,'vibe_certificate_course'));
        add_shortcode('certificate_student_marks', array($this,'vibe_certificate_student_marks'));
        add_shortcode('certificate_student_field', array($this,'vibe_certificate_student_field'));
        add_shortcode('certificate_student_date', array($this,'vibe_certificate_student_date'));
        add_shortcode('course_completion_date', array($this,'vibe_certificate_course_finish_date'));
        add_shortcode('certificate_expiry_date', array($this,'vibe_certificate_expiry_date'));
        add_shortcode('certificate_code', array($this,'vibe_certificate_code'));
        add_shortcode('certificate_course_field', array($this,'vibe_certificate_course_field'));
        add_shortcode('show_certificates', array($this,'vibe_show_certificates'));
        add_shortcode('certificate_course_duration', array($this,'vibe_certificate_course_duration'));
        add_shortcode('wplms_quiz_score',array($this,'vibe_wplms_quiz_score'));
        add_shortcode('course_instructor_emails',array($this,'vibe_course_instructor_emails'));
        add_shortcode('course_instructor', array($this,'vibe_certificate_course_instructor'));
        
        add_shortcode('wplms_registration_form',array($this,'wplms_registration_form'));

        add_shortcode('survey_result',array($this,'survey_result'));
        add_shortcode('countdown_timer',array($this,'countdown_timer'));
        add_shortcode('vibe_current_date',array($this,'vibe_current_date'));
        add_shortcode('vibe_user_field',array($this,'vibe_user_field'));
        add_shortcode('vibe_quiz_retake',array($this,'quiz_retake_shortcode'));
        add_shortcode('wplms_login',array($this,'vibe_wplms_login'));
        /*
        SPECIAL HOOKS IN INTEGRATION WITH SHORTCODES
        */
       add_action('bp_core_activated_user',array($this,'activate_user'),10,3);
       add_filter('bp_get_email_args',array($this,'get_custom_activation_email_args'),99,2);
       /*
        Ajax calls for uploading files via form shortcode.
        */
       add_action('wp_ajax_wplms_form_uploader_plupload',array($this,'wplms_form_uploader_plupload'));
       add_action('wp_ajax_nopriv_wplms_form_uploader_plupload',array($this,'wplms_form_uploader_plupload'));
       add_action('wp_ajax_remove_form_file_plupload',array($this,'remove_form_file_plupload'));
       add_action('wp_ajax_nopriv_remove_form_file_plupload',array($this,'remove_form_file_plupload'));
       add_action('wp_ajax_insert_form_file_final',array($this,'insert_form_file_final'));
       add_action('wp_ajax_nopriv_insert_form_file_final',array($this,'insert_form_file_final'));

       //to show content based on correct and incorrect answers in quiz
       add_shortcode('check_answer_incorrect',array($this,'check_answer_incorrect'));
       add_shortcode('check_answer_correct',array($this,'check_answer_correct'));

       add_shortcode('wplms_login_widget',array($this,'wplms_login_widget'));

       //course template shortcodes:
       
        add_shortcode('course_category', array($this,'vibe_course_category'));
        add_shortcode('course_button', array($this,'vibe_course_button'));

        add_shortcode('course_featured',array($this,'course_featured'));
        add_shortcode('course_info',array($this,'course_info'));
        
        add_shortcode('course_breadcrumbs',array($this,'course_breadcrumbs'));
        add_shortcode('course_reviews',array($this,'course_reviews'));
        add_shortcode('course_taxonomy',array($this,'course_taxonomy'));
        add_shortcode('course_ratings',array($this,'course_ratings'));
        add_shortcode('course_curriculum',array($this,'course_curriculum'));
        add_shortcode('course_instructors',array($this,'course_instructors'));
        add_shortcode('course_pricing',array($this,'course_pricing'));
        add_shortcode('course_instructor_field',array($this,'course_instructor_field'));
        add_shortcode('course_instructor_data',array($this,'course_instructor_data'));


        add_shortcode('instructor_data',array($this,'instructor_data'));

        add_shortcode('user_achievements',array($this,'user_achievements'));
        add_shortcode('user_reviews',array($this,'user_reviews'));

        add_shortcode('edit_course',array($this,'edit_course'));

        add_shortcode('course_codes',array($this,'course_codes'));
        add_shortcode('mygamification_badges',array($this,'gamification_badges'));
    }

    function gamification_badges($atts,$content=null){
        
        if(!empty($atts['user_id'])){
            $user_id = $atts['user_id'];
        }else{
            if(class_exists('VibeBP_Init')){
                $init = VibeBP_Init::init();
                if(!empty($init->user_id)){
                    $user_id = $init->user_id;
                }else{
                    return;
                }
            }else{
                return;
            }
        }
        $badge_ids = array();
        if(function_exists('bp_activity_get')){
            $badge_ids = bp_activity_get(array(
                'filter' => array(
                    'action' => 'gamification_badge',
                    'user_id' => $user_id,
                )
            ));
        }
        if(!empty($badge_ids) && is_array($badge_ids) && is_array($badge_ids['activities'])){
            $r ='<div class="gamification_badges_wrapper">';
            foreach ($badge_ids['activities'] as $badge) {
                $badge_id = $badge->item_id;
                $image =  get_the_post_thumbnail($badge_id);
                $r .= '<a href="'. get_the_permalink($badge_id).'" class="tip" title="'.get_the_title($badge_id).'">
                    <div class="lmsbadge_image">
                    '. $image.'
                    </div>
                </a>';
            }
            $r .= '</div><style>.gamification_badges_wrapper { display: grid; grid-gap: 1rem; grid-template-columns: repeat(auto-fill,minmax(32px,64px)); position: relative; } .gamification_badges_wrapper img { border-radius: 5px; }</style>';
            return $r;
        }
        return;
    }

    function course_codes($atts,$content=null){
        $init = WPLMS_4_Init::init();
        if(!empty($init->course_id)){
            $id = $init->course_id;
        }
        if(empty($id)){
            $id = get_the_ID();
        }
        if(!empty($atts['id'])){
            $id = $atts['id'];
        }
        $blog_id = '';
        if(function_exists('get_current_blog_id')){
            $blog_id = get_current_blog_id();
        }
        $translation_array = array( 
            'api'=>apply_filters('vibebp_rest_api',get_rest_url($blog_id,WPLMS_API_NAMESPACE)),
            'translations' => array(
                'enter_code' => __( 'Enter code to join','wplms' ),
                'submit' => __( 'Submit','wplms' ),  
                'code_invalid' => __( 'Course code invalid','wplms' ),
            )
            
            
        );
        
        wp_enqueue_script('wplms-course-code-js',plugins_url('../../assets/js/course_code.js',__FILE__),array(),WPLMS_PLUGIN_VERSION,true);
        wp_localize_script( 'wplms-course-code-js', 'wplms_course_codes', $translation_array );
        wp_enqueue_style('wplms-course-code-css',plugins_url('../../assets/css/dashboard.css',__FILE__),array(),WPLMS_PLUGIN_VERSION);

        return '<div class="wplms_course_codes" data-course="'.$id.'"></div>';
        
    }

    function enqueue_v4_scripts(){
        if(!function_exists('vibe_get_option') || (function_exists('vibe_get_option') && vibe_get_option('offload_scripts'))){
            $translation_array = array( 
                'sending_mail' => __( 'Sending mail','wplms' ), 
                'error_string' => __( 'Error :','wplms' ),
                'invalid_string' => __( 'Invalid ','wplms' ),
                'captcha_mismatch' => __( 'Captcha Mismatch','wplms' ), 
            );
            wp_localize_script( 'v4shortcodes', 'vibe_shortcode_strings', $translation_array );
            wp_enqueue_script('v4shortcodes',plugins_url('../../assets/js/shortcodes.js',__FILE__),array(),WPLMS_PLUGIN_VERSION,true);
            wp_enqueue_style('v4shortcodes',plugins_url('../../assets/css/shortcodes.css',__FILE__),array(),WPLMS_PLUGIN_VERSION);
        }
    }

    function capture_price_array($credits){
        $this->credits = $credits;
        return $credits;
    }
    function course_pricing($atts,$content=null){
        extract(shortcode_atts(array(
          'style'   => 0,
        ), $atts));

        $id = apply_filters('elementor_course_block_id','');
        if(empty($id))
            return;
        add_filter('wplms_course_credits_array',array($this,'capture_price_array'));
        $html = bp_course_get_course_credits(Array('id'=>$id));
        if($style == 'button'){
            $html ='';
            if(!empty($this->credits)){
                foreach($this->credits as $key=>$credit){
                      $html .= '<a href="'.$key.'">'.$credit.'</a>';  

                    
                }
            }
        }

        return $html;
    }
    function course_curriculum($atts,$content=null){
        extract(shortcode_atts(array(
          'info'   => 0,
        ), $atts));

        $id = apply_filters('elementor_course_block_id','');
        if(empty($id))
            return;
        $html ='<div class="course_curriculum">';
        wp_enqueue_style('vicons');

        $first_section_option=0;
        ob_start();
        do_action('wplms_course_curriculum_section',$id);
        $course_curriculum = bp_course_get_full_course_curriculum($id);
        if(!empty($course_curriculum)){
            
            echo '<ul class="course_curriculum">';
            $section_opened = 0;
            foreach($course_curriculum as $i=>$lesson){ 
                switch($lesson['type']){
                    case 'unit':
                        ?>
                        <li class="course_lesson">
                            <span class="curriculum-icon"><?php
                            
                            if(strlen($lesson['icon']) > 50){
                               echo vibe_sanitizer($lesson['icon'],'raw');
                            }else{
                                ?>
                                <i class="<?php echo vibe_sanitizer($lesson['icon'],'text'); ?>"></i>
                                <?php
                            }
                            ?></span>
                            <span class="item_title"><?php echo apply_filters('wplms_curriculum_course_lesson',(!empty($lesson['link'])?'<a href="'.$lesson['link'].'">':''). $lesson['title']. (!empty($lesson['link'])?'</a>':''),$lesson['id'],$id); ?></span>
                            <span><?php echo vibe_sanitizer($lesson['labels']); ?> </span>
                            <span><?php echo vibe_sanitizer($lesson['duration']); ?></span>
                        </li>
                        <?php
                        do_action('wplms_curriculum_course_unit_details',$lesson);
                    break;
                    case 'quiz':
                        ?>
                        <li class="course_lesson">
                            <span class="curriculum-icon"><?php
                            
                            if(strlen($lesson['icon']) > 50){
                               echo vibe_sanitizer($lesson['icon'],'raw');
                            }else{
                                ?>
                                <i class="<?php echo vibe_sanitizer($lesson['icon'],'text'); ?>"></i>
                                <?php
                            }
                            ?>
                            </span>    
                            <span class="item_title"><?php echo apply_filters('wplms_curriculum_course_quiz',(($lesson['link'])?'<a href="'.$lesson['link'].'">':''). $lesson['title'].(isset($lesson['free'])?$lesson['free']:'') . (!empty($lesson['link'])?'</a>':''),$lesson['id'],$id); ?></span>
                            <span><?php echo vibe_sanitizer($lesson['labels']); ?> </span>
                            <span><?php echo vibe_sanitizer($lesson['duration']); ?></span>
                        </li>
                        <?php
                        do_action('wplms_curriculum_course_quiz_details',$lesson);
                    break;
                    case 'section':
                        
                        if(empty($section_opened)){
                            $section_opened=1;
                        }else{
                            $section_opened=0;
                            
                        }
                        if($first_section_option){
                            echo '</ul>';
                        }
                        ?>
                        <li class="course_section">
                            <input type="checkbox" id="curriculum_<?php echo $i; ?>" <?php if(empty($first_section_option)){$first_section_option=1;echo'checked';}?> />
                            <label for="curriculum_<?php echo $i; ?>"><?php echo vibe_sanitizer($lesson['title'],'text'); ?></label>
                            <?php
                        
                            echo '<ul>';
                            if($section_opened){
                                
                            }else{
                                if(!$first_section_option)
                                ?>

                        </li>
                                <?php
                            }
                        
                    break;
                    default:
                        do_action('wplms_curriculum_course_lesson_line_html',$lesson,$id);
                    break;
                }
            }

            if($section_open ){
                echo '</ul></li>';
            }
            echo '</ul>';
        }
        ?>
        <script>document.addEventListener('DOMContentLoaded',function(){
            if(document.querySelector('.the_course_button .course_button')){
                document.querySelectorAll('.course_curriculum .item_title').forEach(function(el){
                    el.addEventListener('click',function(){
                        document.querySelector('.the_course_button .course_button').click();
                    });
                });
            }
            });
        </script>
        <style>
            .course_curriculum .course_section {
              
                padding: 15px 0;
                border-bottom: 1px solid var(--border);
            }

            .course_curriculum .course_section > td {
                border: none;
                padding: 15px 0;
                font-size: 16px;
                font-weight: 600;
                letter-spacing: 1px;
            }

            .course_curriculum .unit_description {
                display: none;
                padding: 15px 0;
                font-size: 14px;
                line-height: 1.6;
                border: none;
                border: 1px solid var(--border);
                border-top: none;
                background: var(--sidebar);
            }

            .course_curriculum .unit_description > td {
                padding: 15px;
            }

            .course_curriculum .course_lesson {
                margin: 0;
                padding: 15px;
                font-size: 14px;
                line-height: 1.6;
                border: none;
                border: 1px solid var(--border);
                position: relative;
            }

            .course_curriculum .course_lesson .curriculum-icon {
                width: 20px;
            }

            .course_curriculum .course_lesson .curriculum-icon > svg {
                width: 16x;
                height: 16px;
            }

            .course_curriculum .course_lesson .curriculum-icon > i {
                float: left;
                font-size: 16px;
                margin-right: 10px;
            }

            .course_curriculum .course_lesson span {
                font-size: 11px;
                font-weight: 600;
                text-transform: uppercase;
            }

            .course_curriculum .course_lesson span.free {
                padding: 2px 8px;
                margin-left: 10px;
                border-radius: 2px;
                background: var(--success);
                color: var(--success);
            }

            .course_curriculum .course_lesson span.time {
                display: block;
                text-align: right;
                letter-spacing: 1px;
            }

            .course_curriculum .course_lesson span.time i {
                font-size: 1.6em;
                color: var(--sidebar);
            }

            .course_curriculum .course_lesson span.unit_description_expander {
                margin-left: 10px;
                font-size: 10px;
            }

            .course_curriculum .course_lesson span.unit_description_expander:before {
                content: "\e61a";
                font-family: 'vicon';
            }

            .course_curriculum .course_lesson > td {
                border: none;
                vertical-align: middle !important;
                padding: 15px 8px;
            }

            .course_curriculum .course_lesson > td:last-child {
                width: 110px;
            }

            .course_curriculum .course_lesson > td:last-child i {
                float: left;
                margin-right: 5px;
            }

            .course_curriculum .course_lesson .curriculum_unit_popup {
                display: block;
                font-size: 10px;
                cursor: pointer;
                letter-spacing: 1px;
            }

            .course_curriculum.accordion .course_section {
                margin: 0;
            }

            .course_curriculum.accordion .course_section > td {
                position: relative;
            }

            .course_curriculum.accordion .course_section > td:after {
                content: "\e61a";
                font-family: 'vicon';
                color: var(--border);
                font-size: 24px;
                position: absolute;
                right: 0;
                top: 10px;
            }

            .course_curriculum.accordion .course_section.show {
                display: table !important;
                width: 100%;
            }

            .course_curriculum.accordion .course_section.show > td:after {
                content: "\e622";
            }

            .course_curriculum.accordion .course_lesson {
                display: none;
            }

            .course_curriculum.accordion .course_lesson.show {
                display: table !important;
                width: 100%;
            }

            .course_curriculum,.course_curriculum ul {
                padding: 0;
                list-style: none;
                margin: 0;
            }

            .course_curriculum li {
                padding: 0;
            }

            .course_curriculum input[type="checkbox"] {
                display: none;
            }

            .course_curriculum li > ul li {
                display: flex;
                align-items: center;
                padding: 10px;
            }

            .course_curriculum .item_title {
                flex: 1;
            }

            .course_section label {
                font-size: 1.4rem;
                font-weight: 600;
            }
        </style>
        <?php
        $html .= ob_get_clean();
        $html .='</div>';
        return $html;
    }

    function course_info($atts,$content=null){
        extract(shortcode_atts(array(
          'info'   => 0,
        ), $atts));

        $id = apply_filters('elementor_course_block_id','');
        if(empty($id))
            return;

        switch($info){
             case 'title':
                $html = '<a href="'.get_permalink($id).'">'.get_the_title($id).'</a>';
            break;
            case 'excerpt':
                $html = get_the_excerpt($id);
            break;
            case 'description':
                global $post;
                $old_post = $post;
                $post = get_post($id);
                $html = do_shortcode($post->post_content);
                $post = $old_post;
            break;
            case 'average_rating':
                $rating = get_post_meta($id,'average_rating',true);
                $html = bp_course_display_rating($rating);
            break;
            case 'average_rating_count':
                $rating = get_post_meta($id,'average_rating',true);
                $html = '<span>'.$rating.'</span>';
            break;
            case 'review_count':
                $html = get_post_meta($id,'rating_count',true);
            break;
            case 'student_count':
                $html = get_post_meta($id,'vibe_students',true);
            break;
            case 'last_update':
                $html = get_the_modified_time(get_option( 'date_format' ),$id);
            break;
            case 'instructor_name':
                $author = get_post_field('post_author',$id);
                $authors=array($author);
                $authors = apply_filters('wplms_course_instructors',$authors,$id);
                $html ='<div class="course_instructors">';
                foreach($authors as $instructor_id){
                    $html .='<a href="'.bp_core_get_user_domain($instructor_id).'" class="course_instructor">'.bp_core_get_user_displayname($instructor_id).'</a>';    
                }
                $html .='</div>';
            break;
            case 'course_duration':
                $duration = bp_course_get_course_duration($id);
                $duration = intval($duration);
                if($duration >= (9999*86400)){
                    $html = _x('Unlimited Duration','duration shortcode','wplms');
                }else{
                    $html = tofriendlytime($duration);
                }
            
            break;
            case 'course-cat':
                $post_terms = wp_get_object_terms( $id,'course-cat',array( 'fields' => 'ids' ));

                if ( ! empty( $post_terms ) && ! is_wp_error( $post_terms ) ) {
 
                    $term_ids = implode( ',' , $post_terms );
                 
                    $terms = wp_list_categories( array(
                        'title_li' => '',
                        'style'    => 'none',
                        'echo'     => false,
                        'taxonomy' => 'course-cat',
                        'include'  => $term_ids
                    ) );
                    $separator = '';
                    $terms = str_replace( '<br />',  $separator, $terms );
                 
                    // Display post categories.
                    return  '<div class="wplms_course_categories">'.$terms.'</div>';
                }
                return '';
            break;
            case 'level':
            case 'location':
                $post_terms = wp_get_object_terms( $id,$info,array( 'fields' => 'ids' ));

                if ( ! empty( $post_terms ) && ! is_wp_error( $post_terms ) ) {
 
                    $term_ids = implode( ',' , $post_terms );
                 
                    $terms = wp_list_categories( array(
                        'title_li' => '',
                        'style'    => 'none',
                        'echo'     => false,
                        'taxonomy' => $info,
                        'include'  => $term_ids
                    ) );
                    $separator = '';
                    $terms = str_replace( '<br />',  $separator, $terms );
                 
                    // Display post categories.
                    return  '<div class="wplms_course_'.$info.'">'.$terms.'</div>';
                }
                return '';
            break;
            case 'curriculum_item_count':
                $curriculum = bp_course_get_curriculum_units($id);
                $html = is_array($curriculum)?count($curriculum):0;
                //bp_course_get_post_type
            break;
            case 'cumulative_time':
                $course_curriculum = bp_course_get_curriculum($id);
                $duration = 0;
                if(!empty($course_curriculum)){
                    
                    foreach($course_curriculum as $key => $item){       
                        if(is_numeric($item)){ 
                            $post_type = get_post_type($item);
                            if( $post_type == 'unit' && function_exists('bp_course_get_unit_duration')){
                                $duration += bp_course_get_unit_duration($item);
                            }else if($post_type == 'quiz' && function_exists('bp_course_get_quiz_duration')){
                                $duration += bp_course_get_quiz_duration($item);
                            }
                        }
                    }

                    if(function_exists('tofriendlytime')){
                        $html = apply_filters('wplms_cs_get_course_unit_durations',tofriendlytime($duration),$duration);
                    }
                    
                }
                //bp_course_get_post_type
            break;
            case 'pre-required-courses':
                $precourse=get_post_meta($id,'vibe_pre_course',true);
                  $pre_course_html = '';
                  if(!empty($precourse)){
                    if(is_numeric($precourse)){
                      $pre_course_html = '<a href="'.get_permalink($precourse).'">'.get_the_title($precourse).'</a>';
                    }else if(is_array($precourse)){
                       foreach($precourse as $k => $pre_course_id){
                          $pre_course_html .= (empty($k)?'':' , ').'<a href="'.get_permalink($pre_course_id).'">'.get_the_title($pre_course_id).'</a>';
                       }
                    }
                  }
                  if(!empty($pre_course_html)){
                    $html = $pre_course_html;
                }else{
                    $html = __('None','wplms');
                }
                
            break;
            case 'certificate':
                $link =bp_get_course_certificate(array('course_id'=>$id));
                $html ='<a href="'.$link.'" class="bp_course_certificate_link">'.__('Preview Certificate','wplms').'</a>';
            break;
            case 'badge':
                $link =bp_get_course_certificate(array('course_id'=>$id));
                $b=bp_get_course_badge($id);
                $badge=wplms_plugin_wp_get_attachment_info($b); 
                $size = apply_filters('bp_course_badge_thumbnail_size','thumbnail');
                $badge_url=wp_get_attachment_image_src($b,$size);
                $b_title = get_post_meta($id,'vibe_course_badge_title',true);
                $html ='<a class="tip ajax-badge" data-course="'.get_the_title($id).'" title="'.$b_title.'"><img src="'.$badge_url[0].'" title="'.$b_title.'" width="32px" /></a>';
            break;
            default:
                ob_start();
                do_action('wplms_course_info_content',$id,$info);
                $html = ob_get_clean();
            break;

        }

        return $html;
    }


    function course_instructor_field($atts,$content=null){
        extract(shortcode_atts(array(
          'field_id'   => 0,
          'style'  => 0,
          'font-size'   =>'center',
          'id'=>get_the_ID()
        ), $atts));

        $id = apply_filters('elementor_course_block_id','');
        if(empty($id))
            return;
            
        if(empty($this->instructors)){
            $aid = get_post_field('post_author',$id);
            $instructors = apply_filters('wplms_course_instructors',array($aid),$id);
            foreach($instructors as $instructor_id){
                $this->instructors[$instructor_id] = [];    
            }
            //record this instructor
            $this->instructors[$instructors[0]][]=$field_id;
            $user_id = $instructors[0];
           
        }else{
           
            foreach($this->instructors as $instructor_id=>$recorded_fields){
                $user_id = $instructor_id;   
                if(empty($recorded_fields) || (is_array($recorded_fields) && !in_array($field_id,$recorded_fields))){
                    $this->instructors[$instructor_id][] = $field_id;  
                    $user_id = $instructor_id;     
                    break;
                }
            }
        }

        if(empty($user_id))
            return;
        

        $field = xprofile_get_field( $field_id );

        $value = xprofile_get_field_data( $field_id, $user_id);
        if(is_string($value)){
            $json = json_decode($value,true);
            if (json_last_error() === 0) {
               $value = $json;
            }
        }

        if($field->type == 'social'){
            $newvalue = '';
            $newvalue .='<div class="social_icons">';
            if(!empty($value)){
                foreach($value as $social_icon){
                    $newvalue .='<a href="'.$social_icon['url'].'" target="_blank"><span class="'.$social_icon['icon'].'"></span></a>';
                }
            }
            
            $newvalue .='</div>';
            $value = $newvalue;
        }

        if(is_array($value)){
            $newvalue = '';
            foreach($value as $key=>$val){
                if(is_array($val)){
                    foreach($val as $k=>$v){
                        $newvalue .='<div class="'.$k.'">'.$v.'</div>'; 
                    }
                }else{
                    $newvalue .='<div class="'.$key.'">'.$val.'</div>'; 
                }
            }
            $value = $newvalue;
        }
        
        $html ='<div class="vibebp_profile_field field_'.$field_id.'">
            '.(($style== 'nolabel')?'<label>'.$field->name.'</label>'
                :'').' <div class="'.sanitize_title($field->name).'" >';
        $html .=$value;
        $html .='</div></div>';
        return $html;
    }

 
    function user_achievements($atts,$content=null){

        extract(shortcode_atts(array(
          'style' => 'carousel',
          'type'=>'certificate',
          'row'=>3,
          'total'=>10,
          'user_id'=>'',
        ), $atts));

        if(empty($user_id)){
            $user_id = bp_displayed_user_id();
        }
        $elements = [];
        if($type == 'certificates'){
            $certificate_ids = bp_course_get_user_certificates($user_id);
            if(empty($certificate_ids) || !is_array($certificate_ids)){$certificate_ids = array();}
            $tt = count($certificate_ids);
            if($tt <=$total ){
                $total = $tt;
            }
            for($i=0;$i<$total;$i++){
                $certificate_id = $certificate_ids[$i];
                $elements[]='<a href="'.bp_get_course_certificate(array('user_id'=>$user_id,'course_id'=>$certificate_id)).'" target="_blank" class="user_certificate">
                    <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 24 24"><path d="M14.969 9.547l.031.191c0 .193-.096.379-.264.496-.538.372-.467.278-.67.885-.084.253-.33.424-.605.424h-.002c-.664-.002-.549-.038-1.083.338-.112.08-.244.119-.376.119s-.264-.039-.376-.118c-.534-.376-.419-.34-1.083-.338h-.002c-.275 0-.521-.171-.605-.424-.203-.607-.133-.513-.669-.885-.169-.118-.265-.304-.265-.497l.031-.19c.207-.604.208-.488 0-1.094l-.031-.191c0-.193.096-.379.265-.497.536-.372.465-.277.669-.885.084-.253.33-.424.605-.424h.002c.662.002.544.041 1.083-.338.112-.08.244-.119.376-.119s.264.039.376.118c.534.376.419.34 1.083.338h.002c.275 0 .521.171.605.424.203.607.132.513.67.885.168.118.264.304.264.497l-.031.191c-.207.604-.208.488 0 1.094zm-1.469-1.198l-.465-.464-1.41 1.446-.66-.627-.465.464 1.125 1.091 1.875-1.91zm4.5 4.651h-12v1h12v-1zm-1 2h-10v1h10v-1zm1 2h-12v1h12v-1zm1-15h-19v20h24v-20h-5zm3 15.422c-1.151.504-2.074 1.427-2.578 2.578h-14.844c-.504-1.151-1.427-2.074-2.578-2.578v-10.844c1.151-.504 2.074-1.427 2.578-2.578h14.844c.504 1.151 1.427 2.074 2.578 2.578v10.844z"/></svg>
                    <span>'.get_the_title($certificate_id).'</span></a>';

            }
            
        }

        if($type == 'badge'){

            $badge_ids = bp_course_get_user_badges($user_id);
             $tt = count($badge_ids);
            if($tt <=$total ){
                $total = $tt;
            }
            for($i=0;$i<$total;$i++){
                $course_id = $badge_ids[$i];
                $badge_id = bp_get_course_badge($course_id);

                $badge=wplms_plugin_wp_get_attachment_info($badge_id); 
                $badge_url=wp_get_attachment_image_src($badge_id,'full');
                if(is_array($badge_url)){
                    $badge_url = $badge_url[0];
                }
                $elements[]='<a class="badge tip" title="'.get_the_title($course_id).'"><img src="'.$badge_url.'" /></a>';
            }
        }

        if(empty($row)){$row=1;}
        $html ='<div class="vibe_grid "><ul class="grid masonry achievement_blocks" data-width="'.round(1170/$row).'" data-gutter="15px">';
        foreach($elements as $element){
            $html .='<li class="grid-item achievement_block">'.$element.'</li>';
        }
        $html .='</ul></div>';
        
        return $html;
    }

    function user_reviews($atts,$content=null){

        extract(shortcode_atts(array(
          'breakup' => 0,
          'hide_if_none'=>0,
          'total'=>6,
          'user_type'=>'instructor',
          'orderby'=>'',
          'order'=>'',
          'row'=>3,
          'style'=>'carousel'
        ), $atts));

        if(empty($user_id)){
            $user_id = bp_displayed_user_id();
        }
        global $wpdb;
        if($user_type == 'instructor'){
            $courses = bp_course_get_instructor_courses($user_id);

            $elements = [];
            if($hide_id_none && empty($courses))
                return;
            if(empty($courses)){
                return '<div class="message">'.__('No reviews found.','wplms').'</div>';
            }
            $html = '<div class="instructor_reviews">';

            $args = array(
                'post__in'=>$courses,
                'number'=>$total,
                'orderby'=> 'date',
                'order'=>'DESC'
            );


        }else{
            $args = array(
                'number'=>$total,
                'post_type'=>'course',
                'orderby'=> 'date',
                'order'=>'DESC',
                'user_id'=>$user_id
            );
        }

        if($orderby == 'rating'){
                $args['orderby']='meta_value';
                $args['meta_key']='review_rating';
            }
    
        $comments = new WP_Comment_Query($args);        
        $elements = [];
        if(!empty($comments)){
            foreach($comments as $comment){
                
                ob_Start();
                ?>
                <div id="comment-<?php echo $comment->comment_ID; ?>" class="comment-body">
                 <div class="comment-body-inner">
                     <div class="comment-avatar">
                       <?php echo get_avatar($comment, $size = '120', $default = ''); ?>
                     </div><!-- END avatar -->
                     <div class="comment-body-content">
                       <div class="comment-meta">
                         <?php echo get_comment_author_link(); 
                               echo '<a href="'.htmlspecialchars( get_comment_link( $comment->comment_ID ) ) .'">'.sprintf(__('%1$s at %2$s','wplms'), get_comment_date(),  get_comment_time()).'</a>'; 
                               comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))); 
                               edit_comment_link(__('(Edit)','wplms'),'  ','');
                         ?>
                       </div><!-- END comment-author vcard -->
                       <?php if ($comment->comment_approved == '0') : ?>
                         <em><?php _e('Your comment is awaiting moderation.','wplms') ?></em>
                         <br />
                       <?php endif; ?>
                       <div class="comment-text">
                       <?php
                      $commenttitle = get_comment_meta( $comment->comment_ID, 'review_title', true );
                      $commentrating = get_comment_meta( $comment->comment_ID, 'review_rating', true );
                      echo '<h3>'.$commenttitle.'</h3>';
                      echo '<div class="course-star-rating">';
                      if(function_exists('bp_course_display_rating')){
                         echo bp_course_display_rating($commentrating);
                      }else{
                        for($i=1;$i<=5;$i++){
                          if($commentrating >= 1){
                            echo '<span class="fill"></span>';
                          }elseif(($commentrating < 1 ) && ($commentrating >= 0.3 ) ){
                            echo '<span class="half"></span>';
                          }else{
                            echo '<span></span>';
                          }
                          $commentrating--;
                        }
                      }
                      echo '</div>';
                      comment_text(); ?>
                       </div>
                    </div> 
                 </div>
               </div>
               <?php
               $elements[]=ob_get_clean();
            
            }
        }
        if($hide_if_none && empty($elements)){
            return;
        }

        if($style == 'grid'){
            $html ='<div class="vibe_grid"><ul class="grid masonry" data-width="'.round(1170/$row).'" data-gutter="15px">';
            foreach($elements as $element){
                $html .='<li class="grid-item">'.$element.'</li>';
            }
            $html .='</ul></div>';
        }else{
            $html ='<div class="vibe_flickity_carousel"><div class="flickity_slides">';
            foreach($elements as $element){
                $html .='<div class="carousel-cell">'.$element.'</div>';
            }
            $html .='</div></div>';
        }
        $html .='</div>';

        return $html;
    }


    function instructor_data($atts,$content=null){
        
        extract(shortcode_atts(array(
          'data'   => 0,
          'user_id'=>0
        ), $atts));

        if(empty($user_id)){
            $user_id = bp_displayed_user_id();
        }

        $html = '';
        switch($data){
            case 'image':
                $html = bp_core_fetch_avatar( array( 'item_id' => $user_id,'type'=>'full', 'html' => true ) );
            break;
            case 'avarage_rating':
            case 'average_rating':

                $rating = wplms_get_instructor_average_rating($user_id);
                $html = '<span>'.round($rating,2).'</span>';
            break;
            case 'avarage_rating_stars':
            case 'average_rating_stars':
                $rating = wplms_get_instructor_average_rating($user_id);
                $html = bp_course_display_rating($rating);
            break;
            case 'review_count':
                $html = wplms_get_instructor_rating_count($user_id);
            break;
            case 'student_count':
                $html = wplms_get_instructor_student_count($user_id);
            break;
            case 'course_count':
                $html = bp_course_get_instructor_course_count_for_user($user_id);
            break;
        }                    
        
        return $html;
    }


    function course_instructor_data($atts,$content=null){
        
        extract(shortcode_atts(array(
          'data'   => 0,
        ), $atts));

        $id = apply_filters('elementor_course_block_id','');
        if(empty($id))
            return;
        $user_id = get_post_field('post_author',$id);
       

        if(empty($user_id))
            return;
        

        //enable to use vibebp profile data and fields
        $init = VibeBP_Init::init();
        if(empty($init->user_id)){
            $init->user_id = $user_id;
        }

        $html = '';

        switch($data){
            case 'image':
                $html = bp_core_fetch_avatar( array( 'item_id' => $user_id,'type'=>'full', 'html' => true ) );
            break;
            case 'average_rating':
            case 'avarage_rating':
                $rating = wplms_get_instructor_average_rating($user_id);
                $html = '<span style="display:flex;align-items:center"><span>'.round($rating,2).'</span>'.bp_course_display_rating($rating).'</span>';
            break;
            case 'review_count':
                $html = wplms_get_instructor_rating_count($user_id);
            break;
            case 'student_count':
                $html = wplms_get_instructor_student_count($user_id);
            break;
            case 'course_count':
                $html = bp_course_get_instructor_course_count_for_user($user_id);
            break;
        }                    
        
        return $html;
    }

    
    function course_featured($atts,$content=null){
        extract(shortcode_atts(array(
          'width'   => 0,
          'radius'  => 0,
          'align'   =>'center'
        ), $atts));



        $id = apply_filters('elementor_course_block_id','');
        if(empty($id))
            return;
     
        

        $html ='<div class="course_featured '.$align.'"><span style="width:'.$width.'px;border-radius:'.$radius.'%;">';

        
        $html .= featured_component($id);
        $html .='</div>';
        return $html;
    }

    function course_reviews($atts,$content=null){
        
        extract(shortcode_atts(array(
          'hide_if_none'=>0,
          'number'=>5,
          'show_count'=>1,
          'show_breakup'=>1,
          'orderby'=>0,
        ), $atts));
        global $post;
        $html = '';
        $id = apply_filters('elementor_course_block_id','');
        if(empty($id))
            return;
        if($hide_if_none){
            $count = get_comments_number($id);
            if(empty($count)){
                return '';
            }
        }
        ob_start();

        ?>
        <div class="course_reviews_wrapper">

        <?php 
        if($content){
        ?>
        <h3 class="heading"><span><?php echo do_shortcode($content); ?></span></h3>
        <?php
        }
        ?>
        <div class="course_reviews">
        <?php
            if($show_breakup){
        ?>
            <div class="review_breakup">
              <?php
              
              $average_rating = get_post_meta($id,'average_rating',true);
              $count = get_post_meta($id,'rating_count',true);
              $breakup = wplms_plugin_get_rating_breakup($id);
              $ratings = array(1=>0,2=>0,3=>0,4=>0,5=>0);
              foreach($breakup as $value){
                 $ratings[$value->val] = intval($value->count);
              }
              ?>
             
                <div class="rating_snapshot">
                  <h2><?php echo (($average_rating)?$average_rating:__('N.A','wplms')); ?></h2>
                  <?php
                  echo '<div class="modern-star-rating">';
                          if(function_exists('bp_course_display_rating')){
                             echo bp_course_display_rating($average_rating);
                          }
                      echo '</div>';
                      echo '<span>'.sprintf(__('%d ratings','course page rating count','wplms'),$count).'</span>';
                  ?>
                </div>
                <ul class="rating_breakup">
                <?php
                  $ratings=array_reverse($ratings,true);
                  foreach($ratings as $k => $rating){
                    if(empty($count)){$count=1;}
                    echo '<li><span>'.$k.' '.__('stars','wplms').'</span><strong><span style="width:'.(100*($rating/$count)).'%">'.$rating.'</span></strong></li>';
                  }
                ?>
                </ul>
              </div>
        <?php
        }
        ?>    
        </div>
         <?php do_action('wplms_reviews_after_rating_snapshot',$id,$atts,$content);?>
        <div class="show_course_reviews">
        <?php
        $args=array('post_id' =>$id);
        if(!empty($orderby) && $orderby=='highest_rated'){
            $args['meta_key'] = 'review_rating';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';
            $args['number'] = $number;
         
        }

        $comments = get_comments( $args );

        if (empty($comments)) {
            echo '<div id="message" class="message notice"><p>';_e('No Reviews found for this course.','wplms');echo '</p></div>';
        }else{
            
        ?>

            <ol class="reviewlist commentlist"> 
            <?php 
                  wp_list_comments(array(
                     'type'        =>'comment',
                     'avatar_size' =>120,
                     'callback'    => 'wplms_plugin_course_reviews'
                     ),$comments
                   ); 
                  paginate_comments_links( array('prev_text' => '&laquo;', 'next_text' => '&raquo;') )
              ?>  
            </ol> 
        <?php
        if(is_user_logged_in() && !empty($_GET['replytocom'])){
            $fields =  array(
                'author' => '<p><label class="comment-form-author clearfix">'.__( 'Name','wplms' ) . ( $req ? '<span class="required">*</span>' : '' ) . '</label> ' . '<input class="form_field" id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" /></p>',
                'email'  => '<p><label class="comment-form-email clearfix">'.__( 'Email','wplms' ) .  ( $req ? '<span class="required">*</span>' : '' ) . '</label> ' .          '<input id="email" class="form_field" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '"/></p>',
                'url'   => '<p><label class="comment-form-url clearfix">'. __( 'Website','wplms' ) . '</label>' . '<input id="url" name="url" type="text" class="form_field" value="' . esc_attr( $commenter['comment_author_url'] ) . '"/></p>',
                 );
                
           $comment_field='<p>' . '<textarea id="comment" name="comment" class="form_field" rows="8" ">'.$content.'</textarea></p>';
            comment_form(array('fields'=>$fields,'comment_field'=>$comment_field,'label_submit' => __('Post Reply','wplms'),'title_reply'=> '<span>'.__('Post Review','wplms').'</span>','logged_in_as'=>'','comment_notes_after'=>'' ));
        }
          }
        ?>
        </div>
    </div>
    <?php 
    if(!defined('WPLMS_VERSION')){
    ?>
    <style>
    .course_reviews {
     margin: 60px 0;
    }
     .course_reviews .review_breakup {
         margin: 0 -15px;
         text-align: center;
         display:flex;
    }
     .course_reviews .review_breakup .rating_snapshot h2 {
         font-size: 120px;
         line-height: 1.4;
         font-weight: 600;
         margin: 0;
    }
     .course_reviews .review_breakup .rating_snapshot .modern-star-rating {
         display: inline-block;
    }
     .course_reviews .review_breakup .rating_snapshot .modern-star-rating .fa {
         color: #ffcb10;
         float: left;
         font-size: 20px;
    }
     .course_reviews .review_breakup .rating_snapshot span {
         color: #aaa;
         display: block;
    }
     .course_reviews .review_breakup ul.rating_breakup {
         margin-top: 20px;
         display: inline-block;
         width: 100%;
    }
     .course_reviews .review_breakup ul.rating_breakup li {
         display: block;
    }
     .course_reviews .review_breakup ul.rating_breakup li > span {
         float: left;
         font-size: 11px;
         text-transform: uppercase;
         letter-spacing: 1px;
         font-weight: 600;
         color: #aaa;
         margin: 15px 15px 0 0;
    }
     .course_reviews .review_breakup ul.rating_breakup li strong {
         border-bottom: 1px solid #fff;
         background: #fafafa;
         height: 40px;
         border-radius: 2px;
         display: block;
         overflow: hidden;
    }
     .course_reviews .review_breakup ul.rating_breakup li strong > span {
         color: #fff;
         background: #79c989;
         display: block;
         font-weight: 600;
         height: 40px;
         line-height: 2.8;
    }
    .course_reviews_wrapper .hide{display:none;}
     .course_reviews_wrapper ol.reviewlist.commentlist{
        margin: 0;
        list-style:none;
    }
     .course_reviews .show_course_reviews .reviewlist.commentlist .comment {
         padding: 15px 0;
         border-bottom: 1px solid #eee;
    }
     .course_reviews .show_course_reviews .reviewlist.commentlist .comment-body .comment-avatar {
         float: left;
    }
     .course_reviews .show_course_reviews .reviewlist.commentlist .comment-body .comment-avatar img {
         max-width: 80px;
         border-radius: 50%;
         margin-top: 20px;
    }
     .course_reviews .show_course_reviews .reviewlist.commentlist .comment-body .comment-body-content {
         margin-left: 100px;
    }
     .course_reviews .show_course_reviews .reviewlist.commentlist .comment-body .comment-body-content .comment-meta {
         margin: 0;
         display: block;
         opacity: 0;
         font-size: 11px;
         text-transform: uppercase;
         color: #aaa;
    }
     .course_reviews .show_course_reviews .reviewlist.commentlist .comment-body .comment-body-content .comment-meta a, .course_reviews .show_course_reviews .reviewlist.commentlist .comment-body .comment-body-content .comment-meta .comment-reply-link {
         color: #aaa;
         background: none;
         padding: 0;
    }
     .course_reviews .show_course_reviews .reviewlist.commentlist .comment-body .comment-body-content .comment-meta a:before, .course_reviews .show_course_reviews .reviewlist.commentlist .comment-body .comment-body-content .comment-meta .comment-reply-link:before {
         content: ' / ';
    }
     .course_reviews .show_course_reviews .reviewlist.commentlist .comment-body .comment-body-content h3 {
         margin: 0;
         font-size: 14px;
         text-transform: uppercase;
         letter-spacing: 1px;
         padding-bottom: 10px;
         font-weight: 600;
    }
     .course_reviews .show_course_reviews .reviewlist.commentlist .comment-body .comment-body-content .modern-star-rating {
         display: inline-block;
    }
     .course_reviews .show_course_reviews .reviewlist.commentlist .comment-body .comment-body-content .modern-star-rating .fa {
         color: #ffcb10;
         float: left;
    }
     .course_reviews .show_course_reviews .reviewlist.commentlist .comment-body:hover .comment-body-content .comment-meta {
         opacity: 1;
    }
     
    </style>

        <?php
    }

    ?>
    <style>
        .reviewlist.commentlist li .comment-text p span.second_content{
            display:none;
        }
        .reviewlist.commentlist li .comment-text p.show_more span.second_content{
            display:initial;
        }
        .reviewlist.commentlist li .comment-text p.show_more span.dots{
            display:none;
        }
    </style>
     <script>
        var excerpt_len = <?php echo apply_filters('excerpt_length',20);?>;
        var rm_text = '<?php echo _x('Read More','','wplms');?>';
        var rm_text_less = '<?php echo _x('Read Less','','wplms');?>';
        var selectorreviewlist = document.querySelectorAll('.reviewlist.commentlist li');
        if(selectorreviewlist && selectorreviewlist.length){
            for (var i = 0; i < selectorreviewlist.length  ; i++) {
                var words = selectorreviewlist[i].querySelector('.comment-text p').innerText.split(' ');
                if(words && words.length && words.length>excerpt_len){
                    var first = '';var second = '';
                    for (var j = 0; j < words.length; j++) {
                        if(j<excerpt_len){
                            first += words[j]+' ';
                        }else{
                            second += words[j]+' ';
                        }
                        
                    }
                    selectorreviewlist[i].querySelector('.comment-text p').innerHTML = '<span class="first_content">'+first+'</span><span class="dots">...</span><span class="second_content">'+second+'</span><a class="read_more link" onclick="LmsReviewReadMo(event)">'+rm_text+'</a>';
                }
                
                
            }
        }

        function LmsReviewReadMo(event){
          
            if(event.target.parentNode && event.target.parentNode.classList && event.target.parentNode.classList.contains('show_more')){
                event.target.parentNode.classList.remove('show_more');
                event.target.innerText = rm_text;
            }else{
                event.target.parentNode.classList.add('show_more');
                event.target.innerText = rm_text_less;
            }

        }
        
    </script>
    <?php
        $html .= ob_get_clean();

        return $html;
         
    }

    /* Out dated to be rmoeved in 4.2 */
    function course_ratings($atts,$content=null){
        extract(shortcode_atts(array(
          'id'   => get_the_ID(),
        ), $atts));
        global $post;
        $_global = $post;
        if(!empty($id)){
            $post = get_post($id);
            setup_postdata($post);
        }
        ob_start();
        echo '<div class="course_reviews" id="course-reviews">';
             comments_template('/course-review.php',true);
        echo '</div>';
        $html = ob_get_clean();
        $post = $_global;
        return $html;
    }

    function course_instructors($atts,$content=null){
        extract(shortcode_atts(array(
          'id'   => get_the_ID(),
        ), $atts));
        global $post;
        $_global = $post;
        if(!empty($id)){
            $post = get_post($id);
        }
        ob_start();

        echo '<div id="item-admins">';

        echo '<h3>'._e( 'Instructors', 'wplms' ).'</h3>';
            
        bp_course_instructor();

        do_action( 'bp_after_course_menu_instructors' );
            
        echo '</div>';
        $html = ob_get_clean();
        $post = $_global;
        return $html;
    }

    function generate_rand(){
        $this->rand = wp_generate_password(6,false,false);//rand(0,999);
        return $this->rand;
    }

/*-----------------------------------------------------------------------------------*/
/*  Drop Caps
/*-----------------------------------------------------------------------------------*/

    function vibe_dropcaps( $atts, $content = null ) {
            
        $return ='<span class="dropcap">'.$content.'</span>';
        return $return;
    }

/*-----------------------------------------------------------------------------------*/
/*  Pull Quote
/*-----------------------------------------------------------------------------------*/

    function vibe_pullquote( $atts, $content = null ) {
        extract(shortcode_atts(array(
          'style'   => 'left'
        ), $atts));
        $return ='<div class="pullquote '.$style.'">'.do_shortcode($content).'</div>';
        return $return;
    }

/*-----------------------------------------------------------------------------------*/
/*  Child theme Shortcodes
/*-----------------------------------------------------------------------------------*/

    function vibe_wplms_login($atts, $content = null ) {
        $return = '';
        if(is_user_logged_in()){
          $return .= __('Welcome','wplms').' '.bp_get_loggedin_user_fullname();  
        }else{
          $return .= '<a href="#wplms-login-form" class="open-popup-link">'.__('Already registered ? Click here to login','wplms').'</a>';

          $return .= "<script>
                        jQuery(document).ready(function($){
                            
                            $('.open-popup-link').on('click',function(event){
                                event.preventDefault();
                                if($('header').hasClass('app')){

                                    if(jQuery('.global').hasClass('login_open')){
                                      jQuery('.global').removeClass('login_open');
                                    }else{
                                     jQuery('.global').addClass('login_open');
                                      jQuery('body').trigger('global_opened');
                                    }
                                     event.stopPropagation();
                                }else{
                                    $('.vbplogin').trigger('click');
                                    $('#login_modern_trigger').trigger('click');
                                }
                                
                            });
                        });
                    </script>";
        } 
      
        return $return;
    }

/*-----------------------------------------------------------------------------------*/
/*  SELL CONTENT WOOCOMMERCE SHORTCODE
/*-----------------------------------------------------------------------------------*/

    function vibe_sell_content( $atts, $content = null ) {
        extract(shortcode_atts(array(
            'product_id'         => '',
        ), $atts));

        if(is_user_logged_in() && is_numeric($product_id)){
            $user_id = get_current_user_id();
            $check = wc_customer_bought_product('',$user_id,$product_id);
            if($check){
                echo apply_filters('the_content',$content);
            }else{  
                $product = wc_get_product( $product_id );
                if(is_object($product)){
                    $link = get_permalink($product_id);

                    $check=vibe_get_option('direct_checkout');
                    if(isset($check) && $check)
                        $link.='?redirect';

                    $price_html = str_replace('class="amount"','class="amount" itemprop="price"',$product->get_price_html());

                    echo '<div class="message info">'.
                    sprintf(__('You do not have access to this content. <a href="%s" class="button"> Puchase </a> content for %s','wplms'),$link,$price_html).
                        '</div><style>#mark-complete{display:none;}</style>';

                    //Check if its a unit then remove the access of the unit attachments.
                    $this->sell_content_unit_id = get_the_ID();
                    if( bp_course_get_post_type($unit_id) == 'unit' ){
                        add_filter('wplms_unit_attachments',function($flag,$id){
                            if( $this->sell_content_unit_id == $id ){
                                $flag = 0;
                            }
                            return $flag;
                        },10,2);
                    }
                }else{
                    echo '<div class="message info">'.__('You do not have access to this content','wplms').'</div><style>#mark-complete{display:none;}</style>';

                    //Check if its a unit then remove the access of the unit attachments.
                    $this->sell_content_unit_id = get_the_ID();
                    if( bp_course_get_post_type($unit_id) == 'unit' ){
                        add_filter('wplms_unit_attachments',function($flag,$id){
                            if( $this->sell_content_unit_id == $id ){
                                $flag = 0;
                            }
                            return $flag;
                        },10,2);
                    }
                }
            }
        }else{
                $product = wc_get_product( $product_id );
                if(is_object($product)){
                    $link = get_permalink($product_id);

                    $check=vibe_get_option('direct_checkout');
                    if(isset($check) && $check)
                        $link.='?redirect';

                    $price_html = $product->get_price_html();

                    echo '<div class="message info">'.
                    sprintf(__('You do not have access to this content. <a href="%s" class="button"> Puchase </a> content for %s','wplms'),$link,$price_html).
                    '</div><style>#mark-complete{display:none;}</style>';

                    //Check if its a unit then remove the access of the unit attachments.
                    $this->sell_content_unit_id = get_the_ID();
                    if( bp_course_get_post_type($unit_id) == 'unit' ){
                        add_filter('wplms_unit_attachments',function($flag,$id){
                            if( $this->sell_content_unit_id == $id ){
                                $flag = 0;
                            }
                            return $flag;
                        },10,2);
                    }
                }else{
                    echo '<div class="message info">'.__('You do not have access to this content','wplms').'</div>';

                    //Check if its a unit then remove the access of the unit attachments.
                    $this->sell_content_unit_id = get_the_ID();
                    if( bp_course_get_post_type($unit_id) == 'unit' ){
                        add_filter('wplms_unit_attachments',function($flag,$id){
                            if( $this->sell_content_unit_id == $id ){
                                $flag = 0;
                            }
                            return $flag;
                        },10,2);
                    }
                }
        }

        return $return;
    }

/*-----------------------------------------------------------------------------------*/
/*  Social Buttons
/*-----------------------------------------------------------------------------------*/

    function vibe_social_buttons( $atts, $content = null ) {
        $return = social_sharing();
        return $return;
    }

/*-----------------------------------------------------------------------------------*/
/*  Social Sharing Buttons
/*-----------------------------------------------------------------------------------*/

    function vibe_social_sharing_buttons( $atts, $content = null ) {
        $return = vibe_socialicons();
        return $return;
    }

/*-----------------------------------------------------------------------------------*/
/*  Number Counter
/*-----------------------------------------------------------------------------------*/

    function vibe_number_counter( $atts, $content = null ) {
        extract(shortcode_atts(array(
        'min'   => 0,
        'max'   => 100,
        'delay' => 0,
        'increment'=>1,
                ), $atts));

        if(strlen($content)>2){
            $m = do_shortcode($content);
            if(is_numeric($m))
                $max = $m;
        }
        wp_enqueue_script( 'counter-js', WPLMS_PLUGIN_INCLUDES_URL . '/vibe-shortcodes/js/scroller-counter.js',array('jquery'),'1.2',true);
        $return ='<div class="numscroller" data-max="'.$max.'" data-min="'.$min.'" data-delay="'.$delay.'" data-increment="'.$increment.'">'.$min.'</div>';
        return $return;
    }

/*-----------------------------------------------------------------------------------*/
/*  Number Counter
/*-----------------------------------------------------------------------------------*/

    function countdown_timer( $atts, $content = null ) {
        extract(shortcode_atts(array(
            'date' => '',
            'days'   => 0,
            'hours'   => 0,
            'minutes' => 0,
            'seconds'=>0,
            'size' => 2,
            'event'=>'',
            'event_detail'=>''
        ), $atts));

        if( $date ){
            $offset = get_option('gmt_offset');
            $seconds = strtotime($date) - (time()+3600*$offset) ;
        }

        if($seconds > 60){
            $minutes = $minutes+floor($seconds/60);
            $seconds = $seconds%60;
        }

        if($minutes > 60){
            $hours = $hours+floor($minutes/60);
            $minutes = $minutes%60;
        }

        if($hours > 24){
            $days = $days+floor($hours/24);
            $hours = $hours%24;
        }
        $rand = rand(0,999);
        ob_start();
        ?>
        <div id="countdown_<?php echo $rand; ?>" class="vibe_countdown">
            <div class="clock">
                <div class="vibe_countdown_item days">
                    <div class="digit tenday">
                        <span class="base"></span>
                        <div class="flap over front"></div>
                        <div class="flap over back"></div>
                        <div class="flap under"></div>
                    </div>
                    <div class="digit day">
                        <span class="base"></span>
                        <div class="flap over front"></div>
                        <div class="flap over back"></div>
                        <div class="flap under"></div>
                    </div>
                    <span class="countdown_label"><?php _ex('Days','countdown shortcode','wplms'); ?></span>
                </div>
                <div class="vibe_countdown_item hours">
                    <div class="digit tenhour">
                        <span class="base"></span>
                        <div class="flap over front"></div>
                        <div class="flap over back"></div>
                        <div class="flap under"></div>
                    </div>

                    <div class="digit hour">
                        <span class="base"></span>
                        <div class="flap over front"></div>
                        <div class="flap over back"></div>
                        <div class="flap under"></div>
                    </div>
                    <span class="countdown_label"><?php _ex('Hours','countdown shortcode','wplms'); ?></span>
                </div>
                <div class="vibe_countdown_item minutes">
                    <div class="digit tenmin">
                        <span class="base"></span>
                        <div class="flap over front"></div>
                        <div class="flap over back"></div>
                        <div class="flap under"></div>
                    </div>

                    <div class="digit min">
                        <span class="base"></span>
                        <div class="flap over front"></div>
                        <div class="flap over back"></div>
                        <div class="flap under"></div>
                    </div>
                    <span class="countdown_label"><?php _ex('Minutes','countdown shortcode','wplms'); ?></span>
                </div>
                <div class="vibe_countdown_item seconds">
                    <div class="digit tensec">
                        <span class="base"></span>
                        <div class="flap over front"></div>
                        <div class="flap over back"></div>
                        <div class="flap under"></div>
                    </div>
                    <div class="digit sec">
                        <span class="base"></span>
                        <div class="flap over front"></div>
                        <div class="flap over back"></div>
                        <div class="flap under"></div>
                    </div>
                    <span class="countdown_label"><?php _ex('Seconds','countdown shortcode','wplms'); ?></span>
                </div>
            </div>
        </div>
        <script>
        jQuery(document).ready(function($){

                var vibetimer<?php echo $rand; ?>={
                        'days':<?php echo $days; ?>,
                        'hours':<?php echo $hours; ?>,
                        'minutes':<?php echo $minutes; ?>,
                        'seconds':<?php echo $seconds; ?>
                    };

                function getupdatedtimer<?php echo $rand; ?>(){
                    vibetimer<?php echo $rand; ?>.seconds = vibetimer<?php echo $rand; ?>.seconds - 1;

                    if(vibetimer<?php echo $rand; ?>.seconds < 0){
                        vibetimer<?php echo $rand; ?>.minutes = vibetimer<?php echo $rand; ?>.minutes - 1;
                        vibetimer<?php echo $rand; ?>.seconds = 59;
                    }
                    if(vibetimer<?php echo $rand; ?>.minutes < 0){
                        vibetimer<?php echo $rand; ?>.hours = vibetimer<?php echo $rand; ?>.hours - 1;
                        vibetimer<?php echo $rand; ?>.minutes = 59;
                    }
                    if(vibetimer<?php echo $rand; ?>.hours < 0){
                        vibetimer<?php echo $rand; ?>.days = vibetimer<?php echo $rand; ?>.days - 1;
                        vibetimer<?php echo $rand; ?>.hours = 23;
                    }
                    if(vibetimer<?php echo $rand; ?>.days < 0){
                        vibetimer<?php echo $rand; ?>.days = vibetimer<?php echo $rand; ?>.seconds = vibetimer<?php echo $rand; ?>.minutes = vibetimer<?php echo $rand; ?>.hours = 0;
                    }
                }

                function flipTo<?php echo $rand; ?>(digit, n){
                    var current = digit.attr('data-num');
                    digit.attr('data-num', n);
                    digit.find('.front').attr('data-content', current);
                    digit.find('.back, .under').attr('data-content', n);
                    digit.find('.flap').css('display', 'block');
                    setTimeout(function(){
                        digit.find('.base').text(n);
                        digit.find('.flap').css('display', 'none');
                    }, 350);
                }

                function jumpTo<?php echo $rand; ?>(digit, n){
                    digit.attr('data-num', n);
                    digit.find('.base').text(n);
                }

                function updateGroup<?php echo $rand; ?>(group, n, flip){
                    var digit1 = $('#countdown_<?php echo $rand; ?> .ten'+group);
                    var digit2 = $('#countdown_<?php echo $rand; ?> .'+group);
                    n = String(n);
                    if(n.length == 1) n = '0'+n;
                    var num1 = n.substr(0, 1);
                    var num2 = n.substr(1, 1);
                    if(digit1.attr('data-num') != num1){
                        if(flip) flipTo<?php echo $rand; ?>(digit1, num1);
                        else jumpTo<?php echo $rand; ?>(digit1, num1);
                    }
                    if(digit2.attr('data-num') != num2){
                        if(flip) flipTo<?php echo $rand; ?>(digit2, num2);
                        else jumpTo<?php echo $rand; ?>(digit2, num2);
                    }
                }

                function setTime<?php echo $rand; ?>(flip){
                    getupdatedtimer<?php echo $rand; ?>();

                    updateGroup<?php echo $rand; ?>('day', vibetimer<?php echo $rand; ?>.days, flip);
                    updateGroup<?php echo $rand; ?>('hour', vibetimer<?php echo $rand; ?>.hours, flip);
                    updateGroup<?php echo $rand; ?>('min', vibetimer<?php echo $rand; ?>.minutes, flip);
                    updateGroup<?php echo $rand; ?>('sec', vibetimer<?php echo $rand; ?>.seconds, flip);
                    if(vibetimer<?php echo $rand; ?>.days == 0 && vibetimer<?php echo $rand; ?>.hours == 0 && vibetimer<?php echo $rand; ?>.minutes == 0 && vibetimer<?php echo $rand; ?>.seconds == 0){
                        clearInterval(timer_<?php echo $rand; ?>);
                        <?php
                            if(!empty($event)){
                                if(!empty($event_detail)){
                                    ?>
                                        jQuery('body').trigger('<?php echo $event; ?>',[{'event_detail':'<?php echo $event_detail;?>'}]);
                                    <?php
                                }else{
                                    ?>
                                        jQuery('body').trigger('<?php echo $event; ?>');
                                    <?php
                                }
                        
                            }
                        ?>
                    }
                }

                setTime<?php echo $rand; ?>(false);
                var timer_<?php echo $rand; ?> = setInterval(function(){
                    setTime<?php echo $rand; ?>(true);
                }, 1000);

            });
            
        </script>
        <style>
        .digit {
          position: relative;
          float: left;
          width: <?php echo $size?>rem;
          height: <?php echo 1.5*$size?>rem;
          background-color: var(--primary);
          color:var(--primarycolor);
          border-radius: <?php echo 0.1*$size?>rem;
          text-align: center;margin-right:5px;
          font-size: <?php echo 1.1*$size?>rem;
        }
        .digit::after { content: ''; position: absolute; left: 0; top: 50%; width: 100%; height: 1px; display: block; background: rgba(0,0,0,0.2); z-index: 99; }
        .digit::before { content: ''; position: absolute; left: 0; top: 1px; width: 100%; height: 50%; display: block; border-radius: 0.4rem; background: rgba(0,0,0,0.03); }
        .base {
          display: block;
          position: absolute;
          top: 50%;
          left: 50%;
          -webkit-transform: translate(-50%, -50%);
                  transform: translate(-50%, -50%);
        }

        .flap {
          display: none;
          position: absolute;
          width: 100%;
          height: 50%;
          background-color: var(--primary);
          color: var(--primarycolor);
          left: 0;
          top: 0;
          border-radius: <?php echo 0.1*$size?>rem <?php echo 0.1*$size?>rem 0 0;
          -webkit-transform-origin: 50% 100%;
                  transform-origin: 50% 100%;
          -webkit-backface-visibility: hidden;
                  backface-visibility: hidden;
          overflow: hidden;
        }
        .flap::before {
          content: attr(data-content);
          position: absolute;
          left: 50%;
        }
        .flap.front::before, .flap.under::before {
          top: 100%;
          -webkit-transform: translate(-50%, -50%);
                  transform: translate(-50%, -50%);
        }
        .flap.back {
          -webkit-transform: rotateY(180deg);
                  transform: rotateY(180deg);
        }
        .flap.back::before {
          top: 100%;
          -webkit-transform: translate(-50%, -50%) rotateZ(180deg);
                  transform: translate(-50%, -50%) rotateZ(180deg);
        }
        .flap.over {
          z-index: 2;
        }
        .flap.under {
          z-index: 1;
        }
        .flap.front {
          -webkit-animation: flip-down-front 300ms ease-in both;
                  animation: flip-down-front 300ms ease-in both;
        }
        .flap.back {
          -webkit-animation: flip-down-back 300ms ease-in both;
                  animation: flip-down-back 300ms ease-in both;
        }
        .flap.under {
          -webkit-animation: fade-under 300ms ease-in both;
                  animation: fade-under 300ms ease-in both;
        }

        @-webkit-keyframes flip-down-front {
          0% {
            -webkit-transform: rotateX(0deg);
                    transform: rotateX(0deg);
            background-color: var(--primary);
            color: var(--primarycolor);
          }
          100% {
            -webkit-transform: rotateX(-180deg);
                    transform: rotateX(-180deg);
            background-color: var(--primary);
            color: var(--primarycolor);
            opacity:0.4;
          }
        }

        @keyframes flip-down-front {
          0% {
            -webkit-transform: rotateX(0deg);
                    transform: rotateX(0deg);
            background-color: var(--primary);
            color: var(--primarycolor);
          }
          100% {
            -webkit-transform: rotateX(-180deg);
                    transform: rotateX(-180deg);
            background-color: var(--primary);
            color: var(--primarycolor);
            opacity:0.4;
          }
        }
        @-webkit-keyframes flip-down-back {
          0% {
            -webkit-transform: rotateY(180deg) rotateX(0deg);
                    transform: rotateY(180deg) rotateX(0deg);
            background-color: var(--primary);
            color: var(--primarycolor);
            opacity:0.4;
          }
          100% {
            -webkit-transform: rotateY(180deg) rotateX(180deg);
                    transform: rotateY(180deg) rotateX(180deg);
            background-color: var(--primary);
            color: var(--primarycolor);
          }
        }
        @keyframes flip-down-back {
          0% {
            -webkit-transform: rotateY(180deg) rotateX(0deg);
                    transform: rotateY(180deg) rotateX(0deg);
            background-color: var(--primary);
            color: var(--primarycolor);
            opacity:0.4;
          }
          100% {
            -webkit-transform: rotateY(180deg) rotateX(180deg);
                    transform: rotateY(180deg) rotateX(180deg);
            background-color: var(--primary);
            color: var(--primarycolor);
          }
        }
        @-webkit-keyframes fade-under {
          0% {
            background-color: var(--primary);
            color: var(--primarycolor);
          }
          100% {
            background-color: var(--primary);
            color: var(--primarycolor);
          }
        }
        @keyframes fade-under {
          0% {
            background-color: var(--primary);
            color: var(--primarycolor);
          }
          100% {
            background-color: var(--primary);
            color: var(--primarycolor);
          }
        }
        .vibe_countdown{position:relative;
            width:100%;text-align:center;margin: <?php echo $size; ?>rem 0;}
        .clock {
          -webkit-perspective: 100vw;
                  perspective: 100vw;
          -webkit-perspective-origin: 50% 50%;
                  perspective-origin: 50% 50%;
        }
        
        .clock .vibe_countdown_item {
          margin-right: <?php echo 0.5*$size?>rem;
          display:inline-block;position:relative;
        }
        .vibe_countdown_item .countdown_label {
            position:absolute;
            bottom:-<?php echo (($size>2)?0.5*$size:$size); ?>rem;
            <?php echo ($size<=1)?'display:none;':''; ?>
            font-size:1rem;
            text-transform:uppercase;
            color:var(--primary);font-weight:600;
            left:0;width:100%;
        }
        .clock .vibe_countdown_item:last-child {
          margin-right: 0;
        }

        </style>
        <?php
        $return = ob_get_clean();
        return $return;
    }

/*-----------------------------------------------------------------------------------*/
/*  Vibe Current Date
/*-----------------------------------------------------------------------------------*/

    function vibe_current_date($atts, $content = null){
        extract(shortcode_atts(array(
            'format' => 'Y/m/d',
        ), $atts));

        $return = date($format);
        return $return;
    }

/*-----------------------------------------------------------------------------------*/
/*  Vibe User Field
/*-----------------------------------------------------------------------------------*/

    function vibe_user_field($atts, $content = null){
        extract(shortcode_atts(array(
            'user_id' => get_current_user_id(),
            'field' => 'name',
            ), $atts));

            $return ='';
            if(function_exists('bp_get_profile_field_data')){
                $return =  bp_get_profile_field_data('field='.$field.'&user_id='.$user_id);    
            }
            
            return $return;
    }

/*-----------------------------------------------------------------------------------*/
/*  Vibe Quiz retake
/*-----------------------------------------------------------------------------------*/

    function quiz_retake_shortcode($atts){
      if(!is_user_logged_in())
        return;
      $user_id = get_current_user_id();
      ob_start();
      bp_course_quiz_retake_form($atts['quiz'],$user_id,$atts['course']);
      echo '<style>.prev_quiz_results{display:block !important;}</style>';
      $return = ob_get_clean();
      return $return;
    }

/*-----------------------------------------------------------------------------------*/
/*  Vibe Container
/*-----------------------------------------------------------------------------------*/

    function vibe_container( $atts, $content = null ) {
        extract(shortcode_atts(array(
        'style'   => ''
                ), $atts));
        $return ='<div class="container '.$style.'">'.do_shortcode($content).'</div>';
        return $return;
    }

/*-----------------------------------------------------------------------------------*/
/*  IMG
/*-----------------------------------------------------------------------------------*/

    function vibe_img( $atts, $content = null ) {
        extract(shortcode_atts(array(
            'id'   => 0,
            'size' => 'thumb'
        ), $atts));
        $id=trim($id,"'");//intval();
        $image =wp_get_attachment_image_src($id,$size);
        $alt = get_post_meta($id, '_wp_attachment_image_alt', true);
        $return ='<img src="'.$image[0].'" class="'.$size.'" width="'.$image[1].'" height="'.$image[2].'" alt="'.$alt.'" />';
        return $return;
    }

/*-----------------------------------------------------------------------------------*/
/*  Pull Quote
/*-----------------------------------------------------------------------------------*/

    function vibe_allbages( $atts, $content = null ) {
        extract(shortcode_atts(array(
            'size'   => '60'
            ), $atts));
        global $wpdb;

        $all_badges = apply_filters('vibe_all_badges', $wpdb->get_results( "
        SELECT post_id,meta_value FROM $wpdb->postmeta
        WHERE   meta_key    = 'vibe_course_badge'
        AND meta_value REGEXP '^-?[0-9]+$'
        " ) );

        $user_id = get_current_user_id();
        $return ='<div class="allbadges">';
        if(isset($all_badges) && is_array($all_badges)){
            $return .='<ul>';
            foreach($all_badges as $badge){
                if(is_object($badge)){
                    $badge_title=get_post_meta($badge->post_id,'vibe_course_badge_title',true);
                    $badge_image =wp_get_attachment_image_src( $badge->meta_value, 'full');
                    $check = get_user_meta($user_id,$badge->post_id,true);
                    $return .='<li '.(($check)?'class="finished"':'').'><a class="tip" title="'.$badge_title.'"><img src="'.$badge_image[0].'" alt="'.$badge->post_title.'" width="'.$size.'" />'.(($check)?'<span>'.__('EARNED','wplms').'</span>':'').'</a></li>';
                }
            }
            $return .='</ul>';      
        }
        $return .='</div>';
        return $return;
    }

/*-----------------------------------------------------------------------------------*/
/*  Instructor
/*-----------------------------------------------------------------------------------*/

    function vibe_instructor( $atts, $content = null ) {
        extract(shortcode_atts(array(
        'id'   => '1'
            ), $atts));
        $instructor = $id;
        $return ='<div class="course_instructor_widget">';
        $return.= bp_course_get_instructor('instructor_id='.$instructor);
        $return.= '<div class="description">'.bp_course_get_instructor_description('instructor_id='.$instructor).'</div>';
        $return.= '<a href="'.get_author_posts_url($instructor).'" class="tip" title="'.__('Check all Courses created by ','wplms').bp_core_get_user_displayname($instructor).'"><i class="icon-plus-1"></i></a>';
        $return.= '<h5>'.__('More Courses by ','wplms').bp_core_get_user_displayname($instructor).'</h5>';
        $return.= '<ul class="widget_course_list">';
        $query = new WP_Query( 'post_type=course&author='.$instructor.'&posts_per_page=5');
        while($query->have_posts()):$query->the_post();
        global $post;
        $return.= '<li><a href="'.get_permalink($post->ID).'">'.get_the_post_thumbnail($post->ID,'thumbnail').'<h6>'.get_the_title($post->ID).'<span>by '.bp_core_get_user_displayname($post->post_author).'</span></h6></a>';
        endwhile;
        wp_reset_postdata();
        $return.= '</ul>';
        $return.= '</div>'; 
        return $return;
    }

/*-----------------------------------------------------------------------------------*/
/*  Divider
/*-----------------------------------------------------------------------------------*/

    function vibe_divider( $atts, $content = null ) {
        extract(shortcode_atts(array(
            'style'   => ''
            ), $atts));
        $return ='<hr class="divider '.$style.'" />';
        return $return;
    }

/*-----------------------------------------------------------------------------------*/
/*  COURSE
/*-----------------------------------------------------------------------------------*/

    function vibe_course( $atts, $content = null ) {
        extract(shortcode_atts(array(
                'id'   => '',
                'featured_block'=>'course'
            ), $atts));
        $course_query = new WP_Query("post_type=course&p=$id");
        
        if($course_query->have_posts()){
            while($course_query->have_posts()){
                $course_query->the_post();
                        
                if(function_exists('thumbnail_generator'))
                    $return = thumbnail_generator($course_query->posts[0],$featured_block,'medium',1,1,1);

            }
        }

        wp_reset_postdata();
        return $return;
    }

/*-----------------------------------------------------------------------------------*/
/*  COURSE CATEGORY
/*-----------------------------------------------------------------------------------*/

    function vibe_course_category( $atts, $content = null ) {
        extract(shortcode_atts(array(
            'term'   => '',
            'taxonomy'=>'course-cat',
            'description'=>0,
            'padding' => '',
            'background' => '',
            'color'=>'#fff',
            'center'=>1,
            'radius'=>4
        ), $atts));
        
        $term = get_term_by('slug',$term,$taxonomy);
        if(empty($term))
            return;

        if(empty($background)){
            $thumbnail_id = get_term_meta($term->term_id,'course_cat_thumbnail_id',true);
            $background = wp_get_attachment_thumb_url( $thumbnail_id );
        }
        $return = '<div class="course_category" style="'.(empty($padding)?'':(is_numeric($padding)?'padding-top:'.$padding.'px;padding-bottom:'.$padding.'px;':'padding:'.$padding.';')).(empty($radius)?'':'border-radius:'.(is_numeric($radius)?$radius.'px;':$radius.';').';').(empty($center)?'':'text-align:center;').(empty($background)?'':'background:url('.$background.');').'">';
        
        $return .='<a href="'.get_term_link($term).'" style="color:'.$color.';"><strong>'.$term->name.'</strong>';
        $return .= (empty($description)?'':'<p>'.$description.'<p>');
        $return .='</a></div>';    
        return $return;
    }

/*-----------------------------------------------------------------------------------*/
/*  THE COURSE BUTTON
/*-----------------------------------------------------------------------------------*/

    function vibe_course_button( $atts, $content = null ) {
        extract(shortcode_atts(array(
                'id'   => '',
                'bg'=>'',
                'color'=>'',
                'width'=>'',
                'height'=>'',
                'font_size'=>'',
                'show_price'=>0,
                'hide_extras'=>0
            ), $atts));
        $return = '';

        $init = WPLMS_4_Init::init();
        if(!empty($init->course_id)){
            $id = $init->course_id;
        }
        if(empty($id)){
            global $post;
            
            if($post->post_type == 'course-layout' || $post->post_type =='course-card'){
                
                if(empty($this->course)){
                    $course_query = new WP_Query(array('post_type'=>'course','posts_per_page'=>1,'orderby'=>'random'));
                    if($course_query->have_posts()){
                        while($course_query->have_posts()){
                            $course_query->the_post();
                            $id = get_the_ID();
                        }
                    }
                    wp_reset_postdata();
                }
            }

            if($post->post_type == 'course'){
                $id = $post->ID;
            }
        }
       
        $rand = 'course_button_'.rand(0,999);
        $init = WPLMS_4_Init::init();
        $init->show_price=$show_price;
        $init->course_button_text=  $content;

        add_filter('wplms_course_non_loggedin_user',function($string,$course_id){
            $init = WPLMS_4_Init::init();

            if(!empty($init->show_price)){
                $credits = bp_course_get_course_credits(array('id'=>$course_id,'type'=>'array'));
                $init->credits=$credits;
                if(!empty($credits) && is_Array($credits)){
                    if(isset($credits[0]['price'])){
                        $string.=$credits[0]['price'];    
                    }else{
                        $string.=$credits[0];
                    }
                    

                    if(count($credits)>1){
                        $string.='<input type="checkbox" id="course_price" /><label for="course_price" class="vicon vicon-angle-down"></label>';
                        $string.='<div class="wplms_price_dropdown">';
                        foreach($credits as $key=>$credit){
                            $string .= '<a href="'.$key.'">'.$credit.'</a>';
                        }
                        $string .='</div>';
                    }
                    
                }
            }
            $init->show_price=0;//run once
            return $string;
        },10,2);

        add_filter('wplms_take_this_course_button_label',function($string){

            $init = WPLMS_4_Init::init();
            
            if(!empty($init->course_button_text)){
                $string ='<strong>'.$init->course_button_text.'</strong>';
            }
            if(!empty($init->show_price) && !empty($init->credits) && is_array($init->credits)){
                $string.='<small>'.$init->credits[array_keys($init->credits)[0]].'</small>';
            }
            return $string;
        });
        add_filter('wplms_course_button_script_args',function($args){
            $init = WPLMS_4_Init::init();
        
            if(!empty($init->show_price)){
                $args['show_price']=1;
            }
            
            if(!empty($init->course_button_text)){
                $args['translations']['take_this_course']=do_shortcode($init->course_button_text);
            }
           
            return $args;
        });
        ob_start();
        ?><style>
            #<?php echo $rand?> .course_button.button,#<?php echo $rand?> input[type="submit"]{
                    display: flex;flex-wrap:wrap;align-items: center;justify-content: center;padding: 0 10px!important;
                <?php
                echo ($bg?'background:'.$bg.' border:none !important;':'');
                echo ($color?'color:'.$color.' ':'');
                echo ($width?'width:'.$width.';':'');
                echo ($height?'height:'.$height.';':'');
                echo ($font_size?'font-size:'.$font_size.';':'');
                ?>
            }<?php if(!empty($hide_extras)){
                echo '#'. $rand.' .extra_details{display:none;}';
            }?>
            #<?php echo $rand; ?> .course_button.button>a{<?php echo ($color?'color:'.$color.';':'color:#fff;'); ?>justify-content: space-around;} 
            <?php if($show_price){ ?>
            #<?php echo $rand; ?> .course_button.button>a{display: flex;justify-content: space-between;}
            <?php } ?>
            #course_price{display:none;}#course_price:checked+label+.wplms_price_dropdown{display:flex;}.wplms_price_dropdown {
                display:none;position: absolute;top: 100%;width: 100%;
                background: var(--highlight);box-shadow: 0px 2px 4px var(--shadow);
              color: var(--text);flex-direction: column;
              left: 0;padding: 0.5rem 0;}
            .course_button.full.button a {
              flex: 1;display:flex;flex-wrap:wrap;
            }
            .course_button.full.button .amount {
              color: var(--text) !important;
            }.course_button_wrapper.fix .wplms_price_dropdown{top: auto;bottom: 100%;box-shadow: 0px -2px 4px var(--shadow)}
            .wplms_price_dropdown>a {
              padding: 0.5rem 1rem;
            }
            .wplms_price_dropdown > a+a {
              border-top: 1px dashed var(--border);
            }.course_button .extra_details{display: flex;font-size: 0.8rem;width: 100%;justify-content: space-between;}.course_button .extra_details>span{flex:1;}
        </style>
        <?php
        echo '<div id="'.$rand.'" class="course_button_wrapper">';
        the_course_button($id);    
        echo '</div>';
        $return = ob_get_clean();


        
        return $return;
    }

/*-----------------------------------------------------------------------------------*/
/*  Icon
/*-----------------------------------------------------------------------------------*/

    function vibe_icon( $atts, $content = null ) {
        extract(shortcode_atts(array(
            'icon'   => 'icon-facebook',
                    'size' => '',
                    'bg' =>'',
                    'hoverbg'=>'',
                    'padding' =>'',
                    'radius' =>'',
                    'color' => '',
                    'hovercolor' => ''
        ), $atts));
        $rand = 'icon'.rand(1,9999);
        $return ='<style> #'.$rand.'{'.(isset($size)?'font-size:'.$size.';':'').''.((isset($bg))?'background:'.$bg.';':';').''.(isset($padding)?'padding:'.$padding.';':'').''.(isset($radius)?'border-radius:'.$radius.';':'').''.((isset($color))?'color:'.$color.';':'').'}
            #'.$rand.':hover{'.((isset($hovercolor))?'color:'.$hovercolor.';':'').''.((isset($hoverbg))?'background:'.$hoverbg.';':'').'}</style><i class="'.$icon.'" id="'.$rand.'"></i>';
       return $return;
    }

/*-----------------------------------------------------------------------------------*/
/*  Video
/*-----------------------------------------------------------------------------------*/

    function vibe_iframevideo( $atts, $content = null ) {

       /*$content = preg_replace('/(<a.*?>)(.*?)(<\/a>)/', '$2', $link);*/
       $return = '<div class="fitvids">'.html_entity_decode($content).'</div>';     
       return $return;
    }

/*-----------------------------------------------------------------------------------*/
/*  Iframe
/*-----------------------------------------------------------------------------------*/

    function vibe_iframe( $atts, $content = null ) {
        extract(shortcode_atts(array(
        'height'   => '',
        'package_type'=>'',
        'endpoint'=>'',
        'popup'=>0,
        'no_script'=>0,
        'button_label'=>_x('Open','open label in iframe shortcode','wplms')
        ), $atts));

        if(!empty($package_type) && $package_type != 'default'){

            if(!function_exists('WP_Filesystem')){
                require_once(ABSPATH . 'wp-admin/includes/file.php');
            }
            WP_Filesystem();
            global $wp_filesystem;
            if(!$no_script){
                $scorm_script = plugin_dir_path( __FILE__).'js/scorm2.js';
                if($wp_filesystem->exists($scorm_script)){
                    $return .='<script id="scorm_script">';
                    $return .= $wp_filesystem->get_contents($scorm_script);
                    $return .='</script>';
                }


                if($package_type == '2004'){
                    ob_start();
                    ?>
                    <script>
                    var API_1484_11 = LMS_API
                    </script>
                    <?php
                    $return .= ob_get_clean();
                }
            }
            

            
        }


        if(!empty($popup)){
            $return .= '<a class="vibe_iframe_content_popup button link" href="'.html_entity_decode($content).(!empty($endpoint)?'?endpoint='.$endpoint:'').'">'.$button_label.'</a><style>.vibe_iframe_content_popup.mfp-ready .mfp-content { max-width:90%; }</style>';
        }else{

            if(!empty($package_type) && $package_type=='1.1'){
                $return .= '<div class="iframecontent" '.((isset($height) && is_numeric($height))?'style="height:'.$height.'px;"':'').'><iframe data-src="'.html_entity_decode($content).(!empty($endpoint)?'?endpoint='.$endpoint:'').'" width="100%" allowFullScreen="true" webkitallowfullscreen="true" mozallowfullscreen="true"></iframe></div>';
            }else{
                $return .= '<div class="iframecontent" '.((isset($height) && is_numeric($height))?'style="height:'.$height.'px;"':'').'><iframe src="'.html_entity_decode($content).(!empty($endpoint)?'?endpoint='.$endpoint:'').'" width="100%" allowFullScreen="true" webkitallowfullscreen="true" mozallowfullscreen="true"></iframe></div>';
            }
            
        }
        return $return;
    }

/*-----------------------------------------------------------------------------------*/
/*  Round Progress
/*-----------------------------------------------------------------------------------*/

    function vibe_roundprogress( $atts, $content = null ) {
        extract(shortcode_atts(array(
                    'style' => '',
                    'percentage' => '60',
                    'radius' => '',
                    'thickness' =>'',
                    'color' =>'#333',
                    'bg_color' =>'#65ABA6',
        ), $atts));
        $rand = 'icon'.rand(1,9999);
        wp_enqueue_script( 'knobjs', VIBE_URL.'/assets/js/old_files/jquery.knob.js' );
        $return ='<figure class="knob" style="width:'.($radius+10).'px;min-height:'.($radius+10).'px;">
                <input class="dial" data-skin="'.$style.'" data-value="'.$percentage.'" data-fgColor="'.$color.'" data-bgColor="'.$bg_color.'" data-height="'.$radius.'" data-inputColor="'.$color.'" data-width="'.$radius.'" data-thickness="'.($thickness/100).'" value="'.$percentage.'" data-readOnly=true />
                <div class="knob_content"><h3 style="color:'.$color.';">'.do_shortcode($content).'</h3></div>
                </figure>';
        return $return;
    }

/*-----------------------------------------------------------------------------------*/
/*  WPML Language Selector shortcode
/*-----------------------------------------------------------------------------------*/

//[wpml_lang_selector]
    function wpml_shortcode_func(){
        do_action('icl_language_selector');
    }

/*-----------------------------------------------------------------------------------*/
/*  Note
/*-----------------------------------------------------------------------------------*/

    function note( $atts, $content = null ) {
        extract(shortcode_atts(array(
            'style'   => '',
                    'bg' =>'',
                    'border' =>'',
                    'bordercolor' =>'',
                    'color' => ''
        ), $atts));
        return '<div class="notification '.$style.'" style="background-color:'.$bg.';border-color:'.$border.';">
            <div class="notepad" style="color:'.$color.';border-color:'.$bordercolor.';">' .do_shortcode($content).'</div></div>';
    }

/*-----------------------------------------------------------------------------------*/
/*  Column Shortcode
/*-----------------------------------------------------------------------------------*/

    function one_half( $atts, $content = null ) {
        $clear='';
        if (isset($atts['first']) && strpos($atts['first'],'first') !== false)
          $clear='clearfix';
          
        return '<div class="one_half '.$clear.'"><div class="column_content '.(isset($atts['first'])?$atts['first']:'').'">' . do_shortcode($content) . '</div></div>';
    }

    function one_third( $atts, $content = null ) {
        $clear='';
        if (isset($atts['first']) && strpos($atts['first'],'first') !== false)
          $clear='clearfix';
      
        return '<div class="one_third '.$clear.'"><div class="column_content '.(isset($atts['first'])?$atts['first']:'').'">' . do_shortcode($content) . '</div></div>';
    }

    function one_fourth( $atts, $content = null ) {
        $clear='';
        if (isset($atts['first']) && strpos($atts['first'],'first') !== false)
          $clear='clearfix';
        return '<div class="one_fourth '.$clear.'"><div class="column_content '.(isset($atts['first'])?$atts['first']:'').'">' . do_shortcode($content) . '</div></div>';
    }

    function three_fourth( $atts, $content = null ) {
        $clear='';
        if (isset($atts['first']) && strpos($atts['first'],'first') !== false)
            $clear='clearfix';
        return '<div class="three_fourth '.$clear.'"><div class="column_content '.(isset($atts['first'])?$atts['first']:'').'">' . do_shortcode($content) . '</div></div>';
    }

    function two_third( $atts, $content = null ) {
        $clear='';
        if (isset($atts['first']) && strpos($atts['first'],'first') !== false)
           $clear='clearfix';
        return '<div class="two_third"><div class="column_content '.(isset($atts['first'])?$atts['first']:'').'">' . do_shortcode($content) . '</div></div>';
    }

    function one_fifth( $atts, $content = null ) {
        $clear='';
        if (isset($atts['first']) && strpos($atts['first'],'first') !== false)
            $clear='clearfix';
        return '<div class="one_fifth '.$clear.'"><div class="column_content '.(isset($atts['first'])?$atts['first']:'').'">' . do_shortcode($content) . '</div></div>';
    }

    function two_fifth( $atts, $content = null ) {
        return '<div class="two_fifth '.$clear.'"><div class="column_content '.(isset($atts['first'])?$atts['first']:'').'">' . do_shortcode($content) . '</div></div>';
    }

    function three_fifth( $atts, $content = null ) {
        $clear='';
        if (isset($atts['first']) && strpos($atts['first'],'first') !== false)
           $clear='clearfix';
        return '<div class="three_fifth '.$clear.'"><div class="column_content '.(isset($atts['first'])?$atts['first']:'').'">' . do_shortcode($content) . '</div></div>';
    }

    function four_fifth( $atts, $content = null ) {
        $clear='';
        if (isset($atts['first']) && strpos($atts['first'],'first') !== false)
           $clear='clearfix';
        return '<div class="four_fifth '.$clear.'"><div class="column_content '.(isset($atts['first'])?$atts['first']:'').'">' . do_shortcode($content) . '</div></div>';
    }

/*-----------------------------------------------------------------------------------*/
/*  Team
/*-----------------------------------------------------------------------------------*/

    function team_member( $atts, $content = null ) {
        extract(shortcode_atts(array(
                'style' => '',
                'pic' => '',
                'alt'=>'',
                'name'   => '',
                'designation' => ''
        ), $atts));
        
        $output  = '<div class="team_member '.$style.'">';
            
        if(isset($pic) && $pic !=''){
            if(preg_match('!(?<=src\=\").+(?=\"(\s|\/\>))!',$pic, $matches )){
                $output .= '<img src="'.$matches[0].'" alt="'.$name.'" />';
            }else{
                $output .= '<img src="'.$pic.'" alt="'.$name.'" />';
            }
        }

       

        if(!empty($alt)){
            $alt = explode(',',$alt);
            
            $output .='<div class="alt_images">';
            foreach($alt as $img){
                $output .= '<img src="'.$img.'" />';
            }
            $output .='</div>';
        }

        $output .= '<div class="member_info">';
        (isset($name) && $name !='')?$output .= '<h3>'.html_entity_decode($name).''.((isset($designation) && $designation !='')?' <small>[ '.$designation.' ]</small>':'').'</h3>':'';
        
        $output .= '<span class="clear"></span>';
        $output .= '<ul class="team_socialicons">';
        $output .=do_shortcode($content);
        $output .= '</ul></div>
            </div>';
        return $output;
    }

    function team_social( $atts, $content = null ) {
        extract(shortcode_atts(array(
            'icon' => 'vicon-facebook',
            'url' => ''
        ), $atts));
       
       $class=str_replace('icon-','',$icon);

       return '<li><a href="'.$url.'" title="'.apply_filters('vibe_shortcodes_team_social',$class).'" class="'.$class.'"><i class="'.$icon.'"></i></a></li>';;
    }

/*-----------------------------------------------------------------------------------*/
/*  Buttons
/*-----------------------------------------------------------------------------------*/

    function button( $atts, $content = null ) {
        extract(shortcode_atts(array(
            'url' => '#',
            'target' => '_self',
            'class' => 'base',
            'bg' => '',
            'hover_bg' => '',
            'hover_color' => '',
            'color' => '',
            'size' => 0,
            'width' => 0,
            'height' => 0,
            'radius' => 0,
        ), $atts));
        
        $rand = 'button'.rand(1,9999);
        $return ='<style> #'.$rand.'{'.(($bg)?'background-color:'.$bg.' !important;':'').''.(($color)?'color:'.$color.' !important;':'').''.(($size!= '0px')?'font-size:'.$size.' !important;':'').''.(($width!= '0px')?'width:'.$width.';':'').''.(($height!= '0px')?'padding-top:'.$height.';padding-bottom:'.$height.';':'').''.(($radius!= '0px')?'border-radius:'.$radius.';':'').'} #'.$rand.':hover{'.(($hover_bg)?'background-color:'.$hover_bg.' !important;':'').'}#'.$rand.':hover{'.(($hover_color)?'color:'.$hover_color.' !important;':'').'}</style><a target="'.$target.'" id="'.$rand.'" class="button '.$class.'" href="'.$url.'">'.do_shortcode($content) . '</a>';
                
        return $return;
    }

/*-----------------------------------------------------------------------------------*/
/*  Alerts
/*-----------------------------------------------------------------------------------*/

    function alert( $atts, $content = null ) {
        extract(shortcode_atts(array(
            'style'   => 'block',
                        'bg' => '',
                        'border' =>'',
                        'color' => '',
        ), $atts));
        
        return '<div class="alert alert-'.$style.'" style="'.(($color)?'color:'.$color.';':'').''.(($bg)?'background-color:'.$bg.';':'').''.(($border)?'border-color:'.$border.';':'').'">'.do_shortcode($content).'</div>';
    }

/*-----------------------------------------------------------------------------------*/
/*  Accordion Shortcodes
/*-----------------------------------------------------------------------------------*/

    function agroup( $atts, $content = null ) {
        extract(shortcode_atts(array(
            'first'   => '',
            'connect' => '',
        ), $atts));
        $random_number = $this->generate_rand(); 
        if(!empty($connect)){
            $random_number = $connect;
        }  
        if(is_wplms_4_0()){
            $this->enqueue_v4_scripts();
        }
        
        return '<div class="accordion '.(($first)?'load_first':'').'" id="accordion'.$random_number.'">'.do_shortcode($content).'</div>';
    }
    
    function accordion( $atts, $content = null ) {
        extract(shortcode_atts(array(
            'title' => 'Title goes here',
            'connect' => ''
        ), $atts));
        $random_number = $this->rand;   
        if(!empty($connect)){
            $random_number = $connect;
        }
        $new_random_number=strtolower(wp_generate_password(6,false,false));
        $check_url = strpos($content,'http');
        if($check_url !== false && $check_url < 2){
            return '<div class="accordion-group panel">
                     <div class="accordion-heading">
                        <a href="'.$content.'" class="accordion-toggle collapsed" target="_blank">
                            <i></i> '. $title .'</a>
                    </div>
                   </div>';
        }else{
            return '<div class="accordion-group panel">
                     <div class="accordion-heading">
                        <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion'.$random_number.'"  href="#collapse_'.$new_random_number.'">
                            <i></i> '. $title .'</a>
                    </div>
                    <div id="collapse_'.$new_random_number.'" class="accordion-body collapsed collapse">
                        <div class="accordion-inner">
                            <p>'. do_shortcode($content) .'</p>
                        </div>
                   </div>
                   </div>';
        }
    }

/*-----------------------------------------------------------------------------------*/
/*  Testimonial Shortcodes
/*-----------------------------------------------------------------------------------*/

    function testimonial( $atts, $content = null ) {
        global $vibe_options;
        extract(shortcode_atts(array(
            'id'         => '',
            'length'    => 100,
        ), $atts));
        
        if($id == 'random'){
            $args=array('post_type'=>'testimonials', 'orderby'=>'rand', 'posts_per_page'=>'1','fields=ids');
            $testimonials=new WP_Query($args);
            while ($testimonials->have_posts()) : $testimonials->the_post();
                $postdata = get_post(get_the_ID());
            endwhile;   
            wp_reset_postdata();
        }else{
            $postdata=get_post($id);
        }
        
        if(function_Exists('thumbnail_generator')){
            $return = thumbnail_generator($postdata,'testimonial',3,$length,0,0);
        }

       return $return;
    }

/*-----------------------------------------------------------------------------------*/
/*  User Only
/*-----------------------------------------------------------------------------------*/

    function vibe_useronly( $atts, $content = null ) {
        extract(shortcode_atts(array(
            'id'   => ''
        ), $atts));
        $return = '';
        if(is_user_logged_in()){
            if(!empty($id) ){
                if(is_numeric($id)){
                    if($id == get_current_user_id()){
                        $return ='<div class="user_only_content">'.do_shortcode($content).'</div>';
                    }
                }else{
                    $ids = explode(',',$id);
                    foreach($ids as $id){
                        if(is_numeric($id) && $id == get_current_user_id()){
                            $return ='<div class="user_only_content">'.do_shortcode($content).'</div>';
                        }
                    }
                }
            }else{
                $return ='<div class="user_only_content">'.do_shortcode($content).'</div>';
            }
        }
        return $return;
    }

    function vibe_instructoronly( $atts, $content = null ){
        extract(shortcode_atts(array(
            'id'   => ''
        ), $atts));
        $return = '';

        if(is_user_logged_in() && current_user_can('edit_posts')){
            if(!empty($id) ){
                if(is_numeric($id)){
                    if($id == get_current_user_id()){
                        $return ='<div class="instructor_only_content">'.do_shortcode($content).'</div>';
                    }
                }else{
                    $ids = explode(',',$id);
                    foreach($ids as $id){
                        if(is_numeric($id) && $id == get_current_user_id()){
                            $return ='<div class="instructor_only_content">'.do_shortcode($content).'</div>';
                        }
                    }
                }
            }else{
                $return ='<div class="instructor_only_content">'.do_shortcode($content).'</div>';
            }
        }
        return $return;
    }

/*-----------------------------------------------------------------------------------*/
/*  CERTIFICATE SHORTCODES  : Student Name
/*-----------------------------------------------------------------------------------*/

    function vibe_certificate_student_name( $atts, $content = null ) {
        $id = $_GET['u'];
        if(isset($id) && $id)
            return bp_core_get_user_displayname($id);
        else
            return '[certificate_student_name]';
    }

/*-----------------------------------------------------------------------------------*/
/*  CERTIFICATE SHORTCODES  : Student Photo
/*-----------------------------------------------------------------------------------*/

    function vibe_certificate_student_photo( $atts, $content = null ) {
        $id = $_GET['u'];
        if(isset($id) && $id)
            return bp_core_fetch_avatar(array('item_id'=>$id,'type'=>'thumb'));
        else
            return '[certificate_student_photo]';
    }

/*-----------------------------------------------------------------------------------*/
/*  CERTIFICATE SHORTCODES  : Student Email
/*-----------------------------------------------------------------------------------*/

    function vibe_certificate_student_email( $atts, $content = null ) {
        $id = $_GET['u'];
        if(isset($id) && $id)
            return get_the_author_meta('user_email',$id);
        else
            return '[certificate_student_email]';
    }

/*-----------------------------------------------------------------------------------*/
/*  CERTIFICATE SHORTCODES  : COURSE NAME
/*-----------------------------------------------------------------------------------*/

    function vibe_certificate_course( $atts, $content = null ) {
        $id = $_GET['c'];
        if(isset($id) && $id)
            return get_the_title($id);
        else
            return '[certificate_course]';
    }

/*-----------------------------------------------------------------------------------*/
/*  CERTIFICATE SHORTCODES  : COURSE MARKS
/*-----------------------------------------------------------------------------------*/

    function vibe_certificate_student_marks( $atts, $content = null ) {
        $uid=$_GET['u'];
         $cid=$_GET['c'];
        if(isset($uid) && is_numeric($uid) && isset($cid) && is_numeric($cid))
            return get_post_meta($cid,$uid,true);
        else
            return '[certificate_student_marks]';
    }

/*-----------------------------------------------------------------------------------*/
/*  CERTIFICATE SHORTCODES  : STUDENT FIELD
/*-----------------------------------------------------------------------------------*/

    function vibe_certificate_student_field( $atts, $content = null ) {
        extract(shortcode_atts(array(
            'field'      => '',
        ), $atts));
        $uid=$_GET['u'];
        if(isset($uid) && is_numeric($uid) && isset($field) && strlen($field)>3)
            return bp_get_profile_field_data( 'field='.$field.'&user_id=' .$uid);
        else
            return '[certificate_student_field]';
    }

/*-----------------------------------------------------------------------------------*/
/*    CERTIFICATE SHORTCODES  : CERTIFICATE DATE
/*-----------------------------------------------------------------------------------*/

    function vibe_certificate_student_date( $atts, $content = null ) {
       $uid=$_GET['u'];
       $cid=$_GET['c'];
       global $bp,$wpdb;
       $final_date = '';
       if(isset($uid) && is_numeric($uid) && isset($cid) && is_numeric($cid) && get_post_type($cid) == 'course'){
            $course_submission_date = $wpdb->get_var($wpdb->prepare( "
                            SELECT activity.date_recorded FROM {$bp->activity->table_name} AS activity
                            WHERE     activity.component     = 'course'
                            AND     activity.type     = 'student_certificate'
                            AND     user_id = %d
                            AND     item_id = %d
                            ORDER BY date_recorded DESC LIMIT 0,1
                        " ,$uid,$cid));
            if(!empty($course_submission_date)){
                $course_submission_date = strtotime($course_submission_date);
            }else{
                $course_submission_date = null;
            }
            
            $date = $wpdb->get_var($wpdb->prepare( "
                                SELECT activity.date_recorded
                                FROM {$bp->activity->table_name} AS activity 
                                LEFT JOIN {$bp->activity->table_name_meta} as meta ON activity.id = meta.activity_id
                                WHERE     activity.component     = 'course'
                                AND     activity.type     = 'bulk_action'
                                AND     meta.meta_key   = 'add_certificate'
                                AND     meta.meta_value = %d
                                AND     activity.item_id = %d
                                ORDER BY date_recorded DESC LIMIT 0,1
                            " ,$uid,$cid));
            if(!empty($date)){
                $date = strtotime($date);
            }else{
                $date = null;
            }

            if(!empty($course_submission_date) && !empty($date)){
                if($course_submission_date < $date){
                    return date_i18n( get_option( 'date_format' ), $date);
                }else{
                    return date_i18n( get_option( 'date_format' ), $course_submission_date);
                }                 
            }else{
                if(!empty($course_submission_date)){
                    return date_i18n( get_option( 'date_format' ), $course_submission_date);
                }
                if(!empty($date)){
                    return date_i18n( get_option( 'date_format' ), $date);
                }
            }
       }
       return '[certificate_student_date]';
    }

/*-----------------------------------------------------------------------------------*/
/*  CERTIFICATE SHORTCODES  : COURSE COMPLETION DATE
/*-----------------------------------------------------------------------------------*/

    function vibe_certificate_course_finish_date( $atts, $content = null ) {
        $uid=$_GET['u'];
        $cid=$_GET['c'];
        global $bp,$wpdb;

        if(isset($uid) && is_numeric($uid) && isset($cid) && is_numeric($cid) && get_post_type($cid) == 'course'){
            $course_submission_date = $wpdb->get_var($wpdb->prepare( "
                            SELECT activity.date_recorded FROM {$bp->activity->table_name} AS activity
                            WHERE   activity.component  = 'course'
                            AND     activity.type   = 'submit_course'
                            AND     user_id = %d
                            AND     item_id = %d
                            ORDER BY date_recorded DESC LIMIT 0,1
                        " ,$uid,$cid));

            return date_i18n(get_option( 'date_format' ), strtotime($course_submission_date));  
        }
        return '[course_completion_date]';
    }

/*-----------------------------------------------------------------------------------*/
/*  CERTIFICATE SHORTCODES  : CERTIFICATE EXPIRY DATE
/*-----------------------------------------------------------------------------------*/

    function vibe_certificate_expiry_date( $atts, $content = null ) {
        extract(shortcode_atts(array(
            'days' => 0,
            'date_format' => get_option( 'date_format' ),
        ), $atts));

        $course_id = $_GET['c'];
        $user_id = $_GET['u'];
        if($days){
            global $wpdb,$bp;
            $date = $wpdb->get_var($wpdb->prepare("
                        SELECT date_recorded 
                        FROM {$bp->activity->table_name} 
                        WHERE type ='student_certificate' 
                        AND item_id = %d 
                        AND component='course' 
                        AND user_id = %d
                        ORDER BY date_recorded DESC LIMIT 0,1
                    ", $course_id,$user_id) );

            if( empty($date) ){
                $date = $wpdb->get_var($wpdb->prepare( "
                            SELECT activity.date_recorded
                            FROM {$bp->activity->table_name} AS activity 
                            LEFT JOIN {$bp->activity->table_name_meta} as meta ON activity.id = meta.activity_id
                            WHERE   activity.component     = 'course'
                            AND     activity.type     = 'bulk_action'
                            AND     meta.meta_key   = 'add_certificate'
                            AND     meta.meta_value = %d
                            AND     activity.item_id = %d
                            ORDER BY date_recorded DESC LIMIT 0,1
                        " ,$user_id,$course_id));
            }

            if( !empty($date) ){
                $time = strtotime($date) + ($days * 86400);
                return date_i18n( $date_format, $time);
            }
        }

        return '[certificate_expiry_date]';
    }

/*-----------------------------------------------------------------------------------*/
/*  CERTIFICATE SHORTCODES  : CERTIFICATE CODE
/*-----------------------------------------------------------------------------------*/

    function vibe_certificate_code( $atts, $content = null ) {
        $uid=$_GET['u'];
        $cid=$_GET['c'];
        if(isset($uid) && is_numeric($uid) && isset($cid) && is_numeric($cid)){
            $ctemplate=get_post_meta($cid,'vibe_certificate_template',true);
            if(isset($ctemplate) && $ctemplate){
                $code = $ctemplate.'-'.$cid.'-'.$uid;
            }else{
                $code = get_the_ID().'-'.$cid.'-'.$uid;
            }
            return apply_filters('wplms_certificate_code',$code,$cid,$uid);
        }
        else
            return '[certificate_code]';
    }

/*-----------------------------------------------------------------------------------*/
/*  CERTIFICATE SHORTCODES  : CERTIFICATE COURSE INSTRUCTOR
/*-----------------------------------------------------------------------------------*/

    function vibe_certificate_course_instructor( $atts, $content = null ) {
        $cid=$_GET['c'];
        if(isset($cid) && is_numeric($cid) && get_post_type($cid) == 'course'){
            $course=get_post($cid);
            $instructor = apply_filters('wplms_course_instructors',$course->post_author,$course->ID);
            if(!isset($instructor))
                return '[course_instructor]';
            
            $return ='';
            if(is_array($instructor)){
                foreach($instructor as $i){
                    $return .= get_the_author_meta('display_name',$i).',';
                }
                $return = rtrim($return, ',');
            }else{
                $return .= get_the_author_meta('display_name',$instructor).' ';
            }

            return $return;
        }
        else
            return '[course_instructor]';
    }

/*-----------------------------------------------------------------------------------*/
/*  CERTIFICATE SHORTCODES  : CERTIFICATE COURSE FIELD
/*-----------------------------------------------------------------------------------*/

    function vibe_certificate_course_field( $atts, $content = null ) {
        extract(shortcode_atts(array(
        'field'   => '',
        'course_id' =>'',
        ), $atts));

        if(!isset($course) || !is_numeric($course)){
            $course_id=$_GET['c'];
        }

        if(isset($course_id) && is_numeric($course_id)){
            $value = get_post_meta($course_id,$field,true);
            if(isset($value)){
                return apply_filters('vibe_certificate_course_field',$value,$atts);
            }else
                return '[certificate_course_field]';
        }else
            return '[certificate_course_field]';
    }
    
/*-----------------------------------------------------------------------------------*/
/*  CERTIFICATE SHORTCODES  : CERTIFICATE COURSE DURATION
/*-----------------------------------------------------------------------------------*/

    function vibe_certificate_course_duration( $atts, $content = null ) {
        extract(shortcode_atts(array(
        'student_id'   => '',
        'course_id' =>'',
        'force'=>'0'
        ), $atts));

        if(!isset($course) || !is_numeric($course)){
            $course_id=$_GET['c'];
        }
        if(!isset($student) || !is_numeric($student)){
            $student_id=$_GET['u'];
        }
        

        if(isset($course_id) && is_numeric($course_id) && get_post_type($course_id) == 'course'){
            
            if(function_exists('bp_course_get_course_duration')){
                $duration = bp_course_get_course_duration($course_id,$user_id); 
                if(!empty($force)){
                    $return = $duration;
                }
            }else{
                return '[certificate_course_duration]';
            }

            if(empty($force)){
                global $bp,$wpdb;

                $start_time = $wpdb->get_var($wpdb->prepare("
                    SELECT date_recorded 
                    FROM {$bp->activity->table_name} 
                    WHERE type ='subscribe_course' 
                    AND item_id=%d 
                    AND component='course' 
                    AND (user_id=%d OR secondary_item_id=%d) 
                    ORDER BY id DESC LIMIT 1", $course_id,$student_id,$student_id));

                if(empty($start_time)){
                    $return = $duration;
                }else{
                    $start_timestamp = strtotime($start_time);
                    $end_time = $wpdb->get_var($wpdb->prepare("
                        SELECT date_recorded 
                        FROM {$bp->activity->table_name} 
                        WHERE type ='submit_course' 
                        AND item_id=%d 
                        AND component='course' 
                        AND (user_id=%d OR secondary_item_id=%d) 
                        ORDER BY id DESC LIMIT 1", $course_id,$student_id,$student_id));
                    if(empty($end_time)){
                        $return = time()-$start_timestamp;
                    }else{
                        $return = strtotime($end_time) - $start_timestamp;
                    }
                }
            }    
            if(!empty($return) && is_numeric($return)){
                if($return > $duration){
                    $return = $duration;
                }
                if(empty($force) || $force == 1){
                    return tofriendlytime($return);
                }else{
                    return floor($return/$force).' '.calculate_duration_time($force);
                }
            }else{
                return '[certificate_course_duration]';
            }
        }else{
            return '[certificate_course_duration]';
        }
    }

/*-----------------------------------------------------------------------------------*/
/*  DISPLAY QUIZ SCORE FOR A USER
/*-----------------------------------------------------------------------------------*/

  function vibe_wplms_quiz_score($atts, $content = null ){
    extract(shortcode_atts(array(
        'id'   => '',
        'user_id'=>'',
        'marks'=>'1',
        'total' => '1'
          ), $atts));

    if(empty($id)){
        global $post;
        if($post->post_type != 'quiz'){
            return '';
        }
        $id=$post->ID;
    }

    if(empty($user_id)){
        $user_id = get_current_user_id();
    }

    if(function_exists('bp_course_get_quiz_questions') && !empty($user_id) ){
        $questions = bp_course_get_quiz_questions($id,$user_id);
        $total_sum =0;
        foreach($questions['ques'] as $key=>$question){
            $total_sum=$total_sum+intval($questions['marks'][$key]);
        }
        $user_marks=get_post_meta($id,$user_id,true);
        if(isset($user_marks)){
            if(!empty($marks) && empty($total)){
                return $user_marks;
            }elseif(!empty($total) && empty($marks)){
                return $total_sum;
            }elseif(!empty($marks) && !empty($total)){
                return sprintf(__('%d out of %d','wplms'),$user_marks,$total_sum);
            }else{
                return __('N.A','wplms');
            }
        }else{
            return __('N.A','wplms');
        }
    }
}

/*-----------------------------------------------------------------------------------*/
/*  Tabs Shortcodes
/*-----------------------------------------------------------------------------------*/

    function vibe_tabs( $atts, $content = null ) {
        extract(shortcode_atts(array(
            'style'   => '',
            'theme'   => '',
            'connect' => ''
        ), $atts));
            
        $defaults=$tab_icons = array();
                extract( shortcode_atts( $defaults, $atts ) );
        
        // Extract the tab titles for use in the tab widget.
        preg_match_all( '/tab title="([^\"]+)" icon="([^\"]+)"/i', $content, $matches, PREG_OFFSET_CAPTURE );
        
        $tab_titles = array();
        
        if(!count($matches[1])){ 
        preg_match_all( '/tab title="([^\"]+)"/i', $content, $matches, PREG_OFFSET_CAPTURE );

        if( isset($matches[1]) ){ $tab_titles = $matches[1];}
        }else{
        if( isset($matches[1]) ){ $tab_titles = $matches[1]; $tab_icons= $matches[2];}
        }
        
        
        $output = '';

        $random_number = $this->generate_rand();
        if(!empty($connect)){
            $random_number = $connect;
        }
        if( count($tab_titles) ){
            $output .= '<div id="vibe-tabs-'. rand(1, 100) .'" class="tabs tabbable '.$style.' '.$theme.'">';
            $output .= '<ul class="nav nav-tabs clearfix">';

            foreach( $tab_titles as $i=>$tab ){

                $tabstr=crc32($tab[0]); 

                $check_url = strpos($tab_icons[$i][0],'http');

                if(isset($tab_icons[$i][0]) && $check_url !== false && $check_url<2){
                    $href = $tab_icons[$i][0];
                }else{
                    $href='#tab-'. $tabstr .'-'.$random_number;
                }

                $output .= '<li><a href="'.$href.'">';
                
                if(isset($tab_icons[$i][0]))
                    $output.='<span><i class="' . $tab_icons[$i][0] . '"></i></span>';

                $output .= $tab[0] . '</a></li>';
            }
            $output .= '</ul><div class="tab-content">';
            $output .= do_shortcode( $content );
            $output .= '</div></div>';
        } else {
            $output .= do_shortcode( $content );
        }
        
        if(is_wplms_4_0()){
            $this->enqueue_v4_scripts();
        }
        return $output;
    }

    function vibe_tab( $atts, $content = null ) { 
        $defaults = array( 'title' => 'Tab','connect'=>'' );
        extract( shortcode_atts( $defaults, $atts ) );

        $random_number = $this->rand;
        if(!empty($connect)){
            $random_number = $connect;
        }
        
        $tabstr=crc32($title); 
        return '<div id="tab-'. $tabstr .'-'.$random_number.'" class="tab-pane"><p>'. do_shortcode( $content ) .'</p></div>';
    }

/*-----------------------------------------------------------------------------------*/
/*  Tooltips
/*-----------------------------------------------------------------------------------*/

    function tooltip( $atts, $content = null ) {
        extract(shortcode_atts(array(
            'direction'   => 'top',
            'tip' => 'Tooltip',
        ), $atts));
        $istyle='';

        return '<a data-rel="tooltip" class="tip" data-placement="'.$direction.'" data-original-title="'.$tip.'">'.do_shortcode($content).'</a>';
    }

/*-----------------------------------------------------------------------------------*/
/*  Taglines
/*-----------------------------------------------------------------------------------*/

    function tagline( $atts, $content = null ) {
        extract(shortcode_atts(array(
            'style'   => '',
                        'bg'   => '',
                        'border'   => '',
                        'bordercolor'   => '',
                        'color'   => '',
        ), $atts));
        return '<div class="tagline '.$style.'" style="background:'.$bg.';border-color:'.$border.';border-left-color:'.$bordercolor.';color:'.$color.';" >'.do_shortcode($content).'</div>';
    }

/*-----------------------------------------------------------------------------------*/
/*  POPUP
/*-----------------------------------------------------------------------------------*/

    function vibe_popupajax( $atts, $content = null ) {
        extract(shortcode_atts(array(
            'id'   => '',
            'auto' => 0,
            'classes' =>''
        ), $atts));

        $newreturn='';
        if($auto){
            $newreturn .='<script>jQuery(window).load(function(){ jQuery("#anchor_popup_'.$id.'").trigger("click");});</script>'; 
        }
        
        $newreturn .= '<a class="popup-with-zoom-anim ajax-popup-link '.$classes.'" href="'.admin_url('admin-ajax.php').'?ajax=true&action=vibe_popup&id='.$id.'" id="anchor_popup_'.$id.'">'.do_shortcode($content).'</a>';
        return $newreturn;
    }

/*-----------------------------------------------------------------------------------*/
/*  Google Maps shortcode
/*-----------------------------------------------------------------------------------*/

    function gmaps( $atts, $content = null ) { 
        $map ='<div class="gmap">'.$content.'</div>';
        return $map;
    }

/*-----------------------------------------------------------------------------------*/
/*  Gallery shortcode
/*-----------------------------------------------------------------------------------*/

    function gallery( $atts, $content = null ) { 
       extract(shortcode_atts(array(
                    'size' => 'normal',
                    'columns'=>5,
                    'ids' => ''
                        ), $atts));

        $output = apply_filters('post_gallery', '', $atts);
        if ( $output != '' )
            return $output;

        $gallery='<div class="gallery '.$size.' columns'.$columns.'">';
        
        
            if(isset($ids) && $ids!=''){
                $rand='gallery'.rand(1,999);
                $posts=explode(',',$ids);
                foreach($posts as $post_id){
                     // IF Ids are not Post Ids
                       if ( wp_attachment_is_image( $post_id ) ) {
                           $attachment_info = wplms_plugin_wp_get_attachment_info($post_id);
                           
                           $full=wp_get_attachment_image_src( $post_id, 'full' );
                           $thumb=wp_get_attachment_image_src( $post_id, $size );
                           
                           if(is_array($thumb))$thumb=$thumb[0];
                            if(is_array($full))$full=$full[0];
                            
                           $gallery.='<a href="'.$full.'" title="'.$attachment_info['title'].'">
                                        <img src="'.$thumb.'" alt="'.$attachment_info['title'].'" />
                                        </a>';
                        }
                }
            }
        $gallery.='</div>';
        return $gallery;
    }

/*-----------------------------------------------------------------------------------*/
/*  HEADING
/*-----------------------------------------------------------------------------------*/

    function heading( $atts, $content = null ) { 
        extract(shortcode_atts(array(
                'style' => '',
                'alt'=>''
                    ), $atts));
        return '<h3 class="heading '.$style.'" '.(empty($alt)?'':'data-title="'.$alt.'"').'><span>'.do_shortcode($content).'</span></h3>';
    }

/*-----------------------------------------------------------------------------------*/
/*  PROGRESSBARS
/*-----------------------------------------------------------------------------------*/

    function progressbar( $atts, $content = null ) { 
        extract(shortcode_atts(array(
                         'color' => '',
                         'bar_color' => '#009dd8',
                         'bg' => '',
                         'percentage' => '20'
                             ), $atts));
                
        return '<div class="progressbar_wrap"><strong>'.do_shortcode($content).'</strong>
           <div class="progress" '.(($bg)?'style="background-color:'.$bg.';"':'').'>
             <div class="bar animate stretchRight load" style="width: '.$percentage.'%;'.(($color)?'background-color:'.$bar_color.';':'').'"><span>'.$percentage.'%</span></div>
           </div></div>';

    }

/*-----------------------------------------------------------------------------------*/
/*  FORMS
/*-----------------------------------------------------------------------------------*/

    function vibeform( $atts, $content = null ) { 
        extract(shortcode_atts(array(
                     'to' => '',
                     'subject' => '',
                     'event' => '',
                     'isocharset' => '',
                     ), $atts));

        global $post;
        if(is_wplms_4_0()){
            $this->enqueue_v4_scripts();
        }

        if( empty($to) && empty($subject) && !empty($event) ){

            $nonce = wp_create_nonce( 'vibeform_security'.$event);
            return apply_filters('vibe_shortcode_form','<div class="form">
             <form method="post" data-event="'.$event.'" '.(($isocharset)?'class="isocharset"':'').'>'.do_shortcode($content).'<div class="response" data-security="'.$nonce.'"></div></form></div>');
        }else{
            if($to == '{{instructor}}'){
                $to = $this->vibe_course_instructor_emails('');
            }
            $nonce = wp_create_nonce( 'vibeform_security'.$to);
            return apply_filters('vibe_shortcode_form','<div class="form">
             <form method="post" data-to="'.$to.'" data-subject="'.$subject.'" '.(($isocharset)?'class="isocharset"':'').'>'.do_shortcode($content).'<div class="response" data-security="'.$nonce.'"></div></form></div>');
        }


    }

    function form_element( $atts, $content = null ) {
        extract(shortcode_atts(array(
            'type' => 'text',
            'validate' => '',
            'options' => '',
            'upload_options' => '',
            'autofocus'=>0,
            'placeholder' => 'Name'
        ), $atts));
           
        $output='';
        $r =  rand(1,999);

        switch($type){
            case 'text': $output .= '<input type="text" placeholder="'.$placeholder.'" class="form_field text" data-validate="'.$validate.'" '.(empty($autofocus)?'':'autofocus').'/>';
                break;
            case 'textarea': $output .= '<textarea placeholder="'.$placeholder.'" class="form_field  textarea" data-validate="'.$validate.'"></textarea>';
                break;
            case 'checkbox': $output .= '<div class="checkbox">
            <label>'.$placeholder.'</label><input type="checkbox" class="form_field  textarea" data-validate="'.$validate.'" value="1" />';
                break;
            case 'select': $output .= '<select class="form_field  select" placeholder="'.$placeholder.'">';
                            $output .= '<option value="">'.$placeholder.'</option>';
                            $options  = explode(',',$options);
                            foreach($options as $option){
                                $output .= '<option value="'.$option.'">'.$option.'</option>';
                            }
                            $output .= '</select>';
                break;
            case 'captcha': $output .='<i class="math_sum"><span id="num'.$r.'-1">'.rand(1,9).'</span><span> + </span><span id="num'.$r.'-2">'.rand(1,9).'</span><span> = </span></i><input id="num'.$r.'" type="text" placeholder="0" class="form_field text small" data-validate="captcha" />';
                break;
            case 'upload':

                    $output .= '<label class="form_upload_label">'.$placeholder.'</label>';
                    $output .= '<p class="form-attachment">'.
                        '<label for="attachment">'.__('Upload File','wplms').'<span class="required">*</span><small class="attachmentRules">&nbsp;&nbsp;('.__('Allowed file types','wplms').': <strong>'.$upload_options.'</strong>, '.__('maximum file size','wplms').': <strong>'. $this->getmaxium_upload_file_size() .__('MB(s)','wplms').' ).</strong></small></label>'.
                    '</p>';
                    wp_enqueue_script('plupload');

                    $output .= '<div  class="plupload_error_notices notice notice-error is-dismissible"></div>';
                    $output .= '<div id="plupload-upload-ui" class="hide-if-no-js">';
                    $output .= '<div id="drag-drop-area">';
                    $output .= '<div class="drag-drop-inside">';
                    $output .= '<p class="drag-drop-info">'.__('Drop files here','wplms').'</p>';
                    $output .= '<p>'._x('or', 'Uploader: Drop files here - or - Select Files','wplms').'</p>';
                    $output .= '<p class="drag-drop-buttons"><input id="plupload-browse-button" type="button" value="'.__('Select Files','wplms').'" class="button" /></p>';
                    $output .= '</div></div></div>';

                    $output .= '<div class="pl_form_uploader_progress"></div>';
                    $output .= '<div class="warning_plupload">';
                    $output .= '<h3>'.__("Please do not close the window until process is completed","vibe-shortcodes").'</h3>';
                    $output .= '</div>';

                    if ( function_exists( 'ini_get' ) ){
                        $post_size = ini_get('post_max_size') ;
                    }
                    $post_size = preg_replace('/[^0-9\.]/', '', $post_size);
                    $post_size = intval($post_size);
                    if($post_size != 1){
                        $post_size = $post_size-1;
                    }

                    $plupload_init = array(
                      'runtimes'            => 'html5,silverlight,flash,html4',
                      'chunk_size'          =>  (($post_size*1024) - 100).'kb',
                      'max_retries'         => 3,
                      'browse_button'       => 'plupload-browse-button',
                      'container'           => 'plupload-upload-ui',
                      'drop_element'        => 'drag-drop-area',
                      'multiple_queues'     => false,
                      'multi_selection'     => false,
                      'max_file_size'       => ($this->getmaxium_upload_file_size() * 1024).'kb',
                      'filters'             => array( array( 'extensions' => $upload_options ) ),
                      'url'                 => admin_url('admin-ajax.php'),
                      'flash_swf_url'       => includes_url('js/plupload/plupload.flash.swf'),
                      'silverlight_xap_url' => includes_url('js/plupload/plupload.silverlight.xap'),
                      
                      'multipart'           => true,
                      'urlstream_upload'    => true,

                      // additional post data to send to our ajax hook
                      'multipart_params'    => array(
                        '_ajax_nonce' => wp_create_nonce('wplms_form_uploader_plupload'),
                        'action'      => 'wplms_form_uploader_plupload',
                      ),
                    );

                    $plupload_init = apply_filters('plupload_init', $plupload_init);

                    ?>

                    <script type="text/javascript">

                        jQuery(document).ready(function($){
                            $( 'body' ).delegate( '.delete_attachment', "click", function(event){
                                event.preventDefault();
                                var $this = $(this);
                                $.confirm({
                                    text: <?php echo '"'.__("Are you sure you want to remove this attachment ?","vibe-shortcodes").'"'; ?>,
                                    confirm: function() {
                                        $.ajax({
                                            type: "POST",
                                            url: ajaxurl,
                                            data: { action: 'remove_form_file_plupload', 
                                                      id: $this.attr('data-id'),
                                                      security: $this.attr('data-security')
                                                },
                                            cache: false,
                                            success: function (html) {
                                                $this.html(html);
                                                $this.parent().remove();
                                            }
                                        });
                                    },
                                    cancel: function() {
                                        $this.find('i.fa').remove();
                                    },
                                    confirmButton: vibe_course_module_strings.confirm,
                                    cancelButton: vibe_course_module_strings.cancel
                                });
                            });

                            var temp = <?php echo json_encode($plupload_init,JSON_UNESCAPED_SLASHES); ?>;
                            // create the uploader and pass the config from above
                            var uploader = new plupload.Uploader(temp);
                            // checks if browser supports drag and drop upload, makes some css adjustments if necessary
                            uploader.bind('Init', function(up){
                              var uploaddiv = $('#plupload-upload-ui');
                              if(up.features.dragdrop){
                                uploaddiv.addClass('drag-drop');
                                  $('#drag-drop-area')
                                    .bind('dragover.wp-uploader', function(){ uploaddiv.addClass('drag-over'); })
                                    .bind('dragleave.wp-uploader, drop.wp-uploader', function(){ uploaddiv.removeClass('drag-over'); });

                              }else{
                                uploaddiv.removeClass('drag-drop');
                                $('#drag-drop-area').unbind('.wp-uploader');
                              }
                          });
                             
                          uploader.init(); 

                          // a file was added in the queue
                          uploader.bind('FilesAdded', function(up, files){
                            
                            var hundredmb = 100 * 1024 * 1024, max = parseInt(up.settings.max_file_size, 10);
                            plupload.each(files, function(file){
                                if (file.size > max && up.runtime != 'html5'){
                                    console.log('call "upload_to_amazon" not sent');
                                }else{
                                     $('.pl_form_uploader_progress').addClass('visible');
                                    var clone = $('.pl_form_uploader_progress').append('<div class="'+file.id+'">'+file.name+'<i></i><strong><span></span></strong></div>');
                                    $('.pl_form_uploader_progress').append(clone);
                                    $('.warning_plupload').show(300);
                                }
                            });
                            up.refresh();
                            up.start();
                        });

                        uploader.bind('Error', function(up, args) {
                            $('.plupload_error_notices').show();
                            $('.plupload_error_notices').html('<div class="message text-danger danger tada animate load">'+args.message+' for '+args.file.name+'</div>');
                            setTimeout(function(){
                                $('.plupload_error_notices').hide();
                            }, 5000);
                            up.refresh();
                            up.start();
                        });

                        uploader.bind('UploadProgress', function(up, file) {
                            
                            if(file.percent < 100 && file.percent >= 1){
                                $('.pl_form_uploader_progress div.'+file.id+' strong span').css('width', (file.percent)+'%');
                                $('.pl_form_uploader_progress div.'+file.id+' i').html( (file.percent)+'%');
                            }
                            up.refresh();
                            up.start(); 
                        });
                          // a file was uploaded 
                        uploader.bind('FileUploaded', function(up, file, response) {
                            
                            var extensions = <?php echo '"'.$upload_options.'"'; ?>;
                            var allowedExtensions = extensions.split(",");
                             $.ajax({
                              type: "POST",
                              url: ajaxurl,
                              data: { action: 'insert_form_file_final', 
                                      security: '<?php echo wp_create_nonce("wplms_insert_form_file_final"); ?>',
                                      name:file.name,
                                      type:file.type,
                                      fileExtensions:allowedExtensions,
                                      
                                    },
                              cache: false,
                              success: function (html) {
                                if(html){
                                    if(html == '0'){
                                        $('.pl_form_uploader_progress div.'+file.id+' strong span').css('width', '0%');
                                        $('.pl_form_uploader_progress div.'+file.id+' strong').html("<i class='error'><?php echo __("File type not allowed","wplms-assignments"); ?><i>");
                                        setTimeout(function(){
                                            $('.pl_form_uploader_progress div.'+file.id).fadeOut(600);
                                            $('.pl_form_uploader_progress div.'+file.id).remove();
                                        }, 2500);
                                        $('.warning_plupload').hide(300);
                                        return false;
                                    }else{
                                     
                                        $('.pl_form_uploader_progress div.'+file.id+' strong span').css('width', '100%');
                                        $('.pl_form_uploader_progress div.'+file.id+' i').html('100%');
                                        
                                            setTimeout(function(){
                                              $('.pl_form_uploader_progress div.'+file.id+' strong').fadeOut(500);
                                            }, 1200);
                                            
                                            $('.pl_form_uploader_progress div.'+file.id).html(html);
                                            setTimeout(function(){
                                                if($('.pl_form_uploader_progress strong').length < 1){
                                                    $('.warning_plupload').hide(300);
                                                }
                                                }, 1750);
                                    }   
                                }
                                
                              }
                            });
                        });
                    });
    
                    </script>
                    <style>
                    div.plupload_error_notices{
                       display: none;
                       margin:10px 2px ;
                       padding:5px;
                    }
                    #plupload-upload-ui{text-align:center;border:dotted 3px #ddd;padding:20px 0 0;}
                    .error{color:red !important;}
                    .warning_plupload{display:none;}
                    .warning_plupload {
                       text-align: center;
                       background: rgba(0,0,0, 0.45);
                       padding: 1px 10px 10px 10px;
                       vertical-align: middle;
                       width:85%;
                       margin : 10px auto;
                       border-radius:5px;
                    }
                    .warning_plupload h3{color: #FFF;}
                    .pl_form_uploader_progress{display:none;background: #eee;}
                    div.pl_form_uploader_progress.visible{display:block;}
                    div.pl_form_uploader_progress > div{text-align: right;padding: 10px;}
                    div.pl_form_uploader_progress > div > i{padding: 15px;color: green;}

                    div.pl_form_uploader_progress strong{width:240px;background:#fff;height:10px;border-radius:10px;display:block;float:right;clear:both;}
                    div.pl_form_uploader_progress span{
                       display: block;
                       width:0%;border-radius:10px;
                       background: green;
                       height:10px;
                    }
                    .delete_attachment:before {
                        content:'\f00d';
                        font-family:'fontawesome';
                        margin-left:10px;
                        font-weight:600;
                        font-size:14px;
                        color:#ff0000;
                    }
                    </style>
                  <?php

                break;
            case 'submit':
                $output .= '<input type="submit" class="form_submit button primary" value="'.$placeholder.'" />';
                break;
        }

       return $output;
    }

    function getmaxium_upload_file_size(){
        $max_upload = (int)(ini_get('upload_max_filesize'));
        return $max_upload;
    }

    function wplms_form_uploader_plupload(){
        check_ajax_referer('wplms_form_uploader_plupload');

        if (empty($_FILES) || $_FILES['file']['error']) {
            die('{"OK": 0, "info": "Failed to move uploaded file."}');
        }
        $chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
        $chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
        $fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : $_FILES["file"]["name"];

        $upload_dir_base = wp_upload_dir();
        $folderPath = $upload_dir_base['basedir']."/wplms_form_uploader";
        if(function_exists('is_dir') && !is_dir($folderPath)){
            if(function_exists('mkdir')) 
                mkdir($folderPath, 0755, true) || chmod($folderPath, 0755);
        }
        $filePath = $folderPath."/$fileName";

        // Open temp file
        if($chunk == 0) 
            $perm = "wb" ;
        else 
            $perm = "ab";

        $out = @fopen("{$filePath}.part",$perm );

        if ($out) {
          // Read binary input stream and append it to temp file
          $in = @fopen($_FILES['file']['tmp_name'], "rb");
         
          if ($in) {
            while ($buff = fread($in, 4096))
              fwrite($out, $buff);
          } else
            die('{"OK": 0, "info": "Failed to open input stream."}');
         
          @fclose($in);
          @fclose($out);
         
          @unlink($_FILES['file']['tmp_name']);
        } else
          die('{"OK": 0, "info": "Failed to open output stream."}');

        // Check if file has been uploaded
        if (!$chunks || $chunk == $chunks - 1) {
          // Strip the temp .part suffix off
            rename("{$filePath}.part", $filePath);
            
        }
        die('{"OK": 1, "info": "Upload successful."}');
        exit;
    }

    function remove_form_file_plupload(){

        if( !empty($_POST) && !empty($_POST['id']) && isset($_POST['security']) && wp_verify_nonce($_POST['security'],'form_upload'.$_POST['id']) ){

            $id = intval($_POST['id']);
            if(get_post_type($id) != 'attachment'){
                echo __('Invalid ID','wplms');
                die();
            }
            $removed = wp_delete_attachment( $id);
            if(!empty($removed )){
                echo __('Attachment removed!','wplms');    
            }else{
              echo __('Unable to remove previous submissions','wplms');  
            }
        
        }else
        echo __('Unable to remove previous submissions','wplms');

        die();
    }

    function insert_form_file_final(){
        if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'wplms_insert_form_file_final') ){
                
            wp_die( __('Security check failed contact administrator','wplms') );
        }

        $filename = $_POST['name'];
        $upload_dir_base = wp_upload_dir();
        $folderPath = $upload_dir_base['basedir']."/wplms_form_uploader";
        $filePath = $folderPath."/$filename";

        $fileInfo = pathinfo($filePath);
        $fileExtension = strtolower($fileInfo['extension']);
        $fileType = $_POST['type'];
        $allowedFileExtensions = $_POST['fileExtensions'];

        if ( !in_array($fileType, $this->getAllowedMimeTypes($allowedFileExtensions)) || !in_array(strtoupper($fileExtension), $allowedFileExtensions) ) {
            unlink($filePath);
            echo '0';
        }else{
            $attachment = array(
                'guid'           => $filePath, 
                'post_mime_type' => $_POST['type'],
                'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filePath ) ),
                'post_content'   => '',
                'post_status'    => 'inherit'
            );
            $attachment_id = wp_insert_attachment( $attachment,$filePath );
            if( !empty( $attachment_id ) ){
                require_once(ABSPATH . 'wp-admin' . '/includes/image.php');
                require_once(ABSPATH . 'wp-admin' . '/includes/file.php');
                require_once(ABSPATH . 'wp-admin' . '/includes/media.php');

                wp_update_attachment_metadata( $attachment_id, wp_generate_attachment_metadata( $attachment_id, $filePath ) );
                echo "<input class='attachment_ids' name='attachment_ids[]' type='hidden' value='".$attachment_id."'>";
                echo $filename.'<a class="delete_attachment" data-id="'. $attachment_id .'" data-security="'.wp_create_nonce('form_upload'.$attachment_id).'"></a>';
            }
        }
        die();
    }

    function getAllowedMimeTypes($allowedFileExtensions){   

        $return = array();
        $pluginFileTypes = $this->getMimeTypes();
        foreach($allowedFileExtensions as $key){
            if(array_key_exists($key, $pluginFileTypes)){
                if(!function_exists('finfo_file') || !function_exists('mime_content_type')){
                    if( ($key ==  'DOCX') || ($key == 'DOC') || ($key == 'PDF') ||
                        ($key == 'ZIP') ){
                        $return[] = 'application/octet-stream';
                    }
                }
                if(is_array($pluginFileTypes[$key])){
                    foreach($pluginFileTypes[$key] as $fileType){
                        $return[] = $fileType;
                    }
                } else {
                    $return[] = $pluginFileTypes[$key];
                }
            }
        }
        return $return;
    }

    function getMimeTypes(){
        return apply_filters('wplms_form_upload_mimes_array',array(
                'JPG' => array(
                                'image/jpeg',
                                'image/jpg',
                                'image/jp_',
                                'application/jpg',
                                'application/x-jpg',
                                'image/pjpeg',
                                'image/pipeg',
                                'image/vnd.swiftview-jpeg',
                                'image/x-xbitmap'),
                'PNG' => array(
                                'image/png',
                                'application/png',
                                'application/x-png'),
                'DOCX'=> 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'TXT' => 'text/plain',
                'ZIP' => array(
                                'application/zip',
                                'application/x-zip',
                                'application/x-zip-compressed',
                                'application/x-compress',
                                'application/x-compressed',
                                'multipart/x-zip'),
                'DOC' => array(
                                'application/msword',
                                'application/doc',
                                'application/text',
                                'application/vnd.msword',
                                'application/vnd.ms-word',
                                'application/winword',
                                'application/word',
                                'application/x-msw6',
                                'application/x-msword'),
                'PDF' => array(
                                'application/pdf',
                                'application/x-pdf',
                                'application/acrobat',
                                'applications/vnd.pdf',
                                'text/pdf',
                                'text/x-pdf'),
                'PPT' => array(
                                'application/vnd.ms-powerpoint',
                                'application/mspowerpoint',
                                'application/ms-powerpoint',
                                'application/mspowerpnt',
                                'application/vnd-mspowerpoint',
                                'application/powerpoint',
                                'application/x-powerpoint',
                                'application/x-m'),
                'PPTX'=> 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            ));
    }

/*-----------------------------------------------------------------------------------*/
/*  INSTRUCTOR EMAILS FOR COURSE ID
/*-----------------------------------------------------------------------------------*/

    function vibe_course_instructor_emails($atts, $content = null ) {
        extract(shortcode_atts(array(
            'course_id' => ''
        ), $atts));

        if(empty($course_id)){
            global $post;
            $course_id = $post->ID;
            if($post->post_type == 'course'){
                $course_id = $post->ID;
                $course_authors = apply_filters('wplms_course_instructors',$post->post_author,$post->ID);
                if(!is_array($course_authors)){
                    $course_authors = array($course_authors);
                }
            }
        }else{
            $instructor_ids = apply_filters('wplms_course_instructors',get_post_field('post_author', $course_id),$course_id); 
            if(!is_array($instructor_ids)){
                $instructor_ids = array($instructor_ids);
            }
        }

        global $post;

        $to = array();
        if(empty($course_authors)){
            return '';
        }
        foreach($course_authors as $instructor_id){
            $user = get_user_by( 'id', $instructor_id);
            $to[] = $user->user_email;
        }
        
        if(is_array($to))
            $to = implode(',',$to);

        return $to;
    }
/*-----------------------------------------------------------------------------------*/
/*  QUIZ SHORTCODE : FILLBLANK
/*-----------------------------------------------------------------------------------*/

    function vibe_fillblank( $atts, $content = null ) {
        global $post; 
        $user_id=get_current_user_id();
        $answers=get_comments(array(
          'post_id' => $post->ID,
          'status' => 'approve',
          'user_id' => $user_id
          ));

        $content =' ';
        if(isset($answers) && is_array($answers) && count($answers)){
            $answer = reset($answers);
            $content = $answer->comment_content;
        }
        if((function_exists('bp_is_user') && bp_is_user()) || (function_exists('bp_is_member') && bp_is_member()))
            return '____________';


        $return ='<p class="live-edit" data-model="article" data-url="/articles"><span class="vibe_fillblank" data-editable="true" data-name="content" data-max-length="250" data-text-options="true">'.((strlen($content)>2)?$content:'<p></p>').'</span></p>';

        return $return;
    }

/*-----------------------------------------------------------------------------------*/
/*  QUIZ SHORTCODE : SELECT
/*-----------------------------------------------------------------------------------*/

    function vibe_select( $atts, $content = null ) {
        global $post; 

        if(is_user_logged_in()){
            $user_id=get_current_user_id();
            $answers=get_comments(array(
              'post_id' => $post->ID,
              'status' => 'approve',
              'user_id' => $user_id
            ));
        }

        $content ='';
        if(isset($answers) && is_array($answers) && count($answers)){
            $answer = reset($answers);
            $content = $answer->comment_content;
        }   

        $original_options = get_post_meta(get_the_ID(),'vibe_question_options',true);   
        $options = $original_options;

        if(!empty($atts['options']) && strpos($atts['options'], ',') !== false){
            $set_options = explode(',',$atts['options']);
            if(!empty($options)){
                foreach($options as $k=>$option){
                    if(!in_array(($k+1),$set_options)){
                        unset($options[$k]);
                    }
                }
            }
        }

        if(!is_array($options) || !count($options))
            return '&laquo; ______ &raquo;';

        $return = '<select class="vibe_select_dropdown">';
        foreach($options as $key=>$value){
            $t = array_search($value,$original_options);
            $return .= '<option value="'.($t+1).'" '.(($k == $content)?'selected':'').'>'.$value.'</option>';
        }
        $return .= '</select>';
        return $return;
    }

/*-----------------------------------------------------------------------------------*/
/*  QUIZ SHORTCODE : MATCH
/*-----------------------------------------------------------------------------------*/

    function vibe_match( $atts, $content = null ) {
        global $post; 

        //Get the last marked answer
        if(is_user_logged_in()){
            $user_id=get_current_user_id();
            $answers=get_comments(array(
              'post_id' => $post->ID,
              'status' => 'approve',
              'user_id' => $user_id
            ));    
        }
        
        $string ='';
        if(isset($answers) && is_array($answers) && count($answers)){
            $answer = reset($answers);
            $option_matches = explode(',',$answer->comment_content);
            foreach($option_matches as $k=>$option_match){
                $string .= ' data-match'.$k.'="'.$option_match.'"';
            }
        }
        return '<div class="matchgrid_options '.((isset($answers) && is_array($answers) && count($answers))?'saved_answer':'').' "'.$string.'>'.do_shortcode($content).'</div>';
    }

/*-----------------------------------------------------------------------------------*/
/*  Course Product
/*-----------------------------------------------------------------------------------*/

    function vibe_course_product_details( $atts, $content = null ) {
        extract(shortcode_atts(array(
            'id' => '',
            'details' => '',
        ), $atts));
        
        if(isset($id) && is_numeric($id)){
            $course_id = $id;   
        }else{
            if(isset($_GET['c']) && is_numeric($_GET['c']))
                $course_id=$_GET['c']; // For certificate use
            else
                return;
        }

        if(get_post_type($course_id) == BP_COURSE_CPT){
            $product_id = get_post_meta($course_id,'vibe_product',true);
            if(isset($product_id) && is_numeric($product_id)){
                switch($details){
                    case 'sku':
                        $return = get_post_meta($product_id,'_sku',true);
                    break;
                    case 'price':
                        $product = wc_get_product( $product_id );
                        $return = $product->get_price_html();
                    break;
                    case 'sales':
                        $return = get_post_meta($product_id,'total_sales',true);
                    break;
                    case 'note':
                        $return = get_post_meta($product_id,'_purchase_note',true);
                    break;
                }
            }
        }
        return $return;
    }

/*-----------------------------------------------------------------------------------*/
/*  Vibe site stats [vibe_site_stats total=1 courses=1 instructors=1 ]
/*-----------------------------------------------------------------------------------*/

    function vibe_site_stats($atts, $content = null){
        extract(shortcode_atts(array(
            'total'   => 0,
            'courses'   =>0,
            'instructor' => 0,
            'groups' => 0,
            'subscriptions' => 0,
            'sales' => 0,
            'commissions' => 0,
            'posts'=>0,
            'comments'=>0,
            'number'=>0
        ), $atts));
        
        $return = array();
        $users =count_users();
        if($total){
            $return['total'] = $users['total_users'];
            if($number)
                return $return['total'];
        }
        if($instructor){
            $count = $users['avail_roles']['instructor'];
            $flag = apply_filters('wplms_show_admin_in_instructors',1);
            if(isset($flag) && $flag){
                $count += $users['avail_roles']['administrator'];
            }
            $return['instructor'] = $count;

            if($number)
                return $return['instructor'];
        }

        if($courses){
            if(function_exists('bp_course_get_total_course_count')){
                $return['courses'] = bp_course_get_total_course_count( );
            }else{
                $count_posts = wp_count_posts('course');
                $return['courses'] = $count_posts->publish;
            }
            if($number)
                return $return['courses']; 
        }

        if($groups){
            global $wpdb,$bp;
            $count = $wpdb->get_results("SELECT count(*) as count FROM {$bp->groups->table_name}",ARRAY_A);
            if(is_array($count) && isset($count[0]['count']) && is_numeric($count[0]['count'])){
                $return['groups']=$count[0]['count'];
            }else{
                $return['groups']=0;
            }
            if($number)
                return $count[0]['count'];
        }
        if($subscriptions){
            global $wpdb,$bp;
            $count = $wpdb->get_results("SELECT count(*) as count FROM {$wpdb->postmeta} WHERE meta_key REGEXP '^[0-9]+$' AND meta_value REGEXP '^[0-9]+$'",ARRAY_A);
            if(is_array($count) && isset($count[0]['count']) && is_numeric($count[0]['count'])){
                $return['subscriptions']=$count[0]['count'];
            }else{
                $return['subscriptions']=0;
            }
            if($number)
                return $count[0]['count'];
        }
        if($sales){
            global $wpdb;
            $count = $wpdb->get_results($wpdb->prepare("SELECT sum(meta_value) as count FROM {$wpdb->postmeta} WHERE meta_key = %s",'_order_total'),ARRAY_A);
            if(is_array($count) && isset($count[0]['count']) && is_numeric($count[0]['count'])){
                $return['sales']=$count[0]['count'];
            }else{
                $return['sales']=0;
            }
            if($number)
                return $count[0]['count'];
        }
        if($commissions){
            global $wpdb;
            $table_name = $wpdb->prefix.'woocommerce_order_itemmeta';
            $q=$wpdb->prepare("SELECT sum(meta_value) as count FROM {$table_name} WHERE meta_key LIKE %s",'commission%');
            $count = $wpdb->get_results($q,ARRAY_A);
            if(is_array($count) && isset($count[0]['count']) && is_numeric($count[0]['count'])){
                $return['commissions']=$count[0]['count'];
            }else{
                $return['commissions']=0;
            }
            if($number)
                return $count[0]['count'];
        }
        if($posts){
            global $wpdb;
            $count = $wpdb->get_results($wpdb->prepare("SELECT count(*) as count FROM {$wpdb->posts} WHERE post_type = %s AND post_status = %s",'post','publish'),ARRAY_A);
            if(is_array($count) && isset($count[0]['count']) && is_numeric($count[0]['count'])){
                $return['posts']=$count[0]['count'];
            }else{
                $return['posts']=0;
            }
            if($number)
                return $count[0]['count'];
        }
        if($comments){
            global $wpdb;
            $count = $wpdb->get_results($wpdb->prepare("SELECT count(*) as count FROM {$wpdb->comments} WHERE comment_approved = %d AND comment_post_ID IN (SELECT ID FROM {$wpdb->posts} WHERE post_type = %s AND post_status = %s)",1,'post','publish'),ARRAY_A);
            if(is_array($count) && isset($count[0]['count']) && is_numeric($count[0]['count'])){
                $return['comments']=$count[0]['count'];
            }else{
                $return['comments']=0;
            }
            if($number)
                return $count[0]['count'];
        }
        $return_html='';
        if(is_array($return) && count($return)){
            $return_html='<ul class="site_stats">';
            foreach($return as $key=>$value){
                if($value){
                    switch($key){
                        case 'total':
                            $return_html .='<li>'.__('MEMBERS','wplms').'<span>'.$value.'</span></li>';
                        break;
                        case 'courses':
                        $return_html .='<li>'.__('COURSES','wplms').'<span>'.$value.'</span></li>';
                        break;
                        case 'instructor':
                        $return_html .='<li>'.__('INSTRUCTORS','wplms').'<span>'.$value.'</span></li>';
                        break;
                        case 'groups':
                        $return_html .='<li>'.__('GROUPS','wplms').'<span>'.$value.'</span></li>';
                        break;
                        case 'subscriptions':
                        $return_html .='<li>'.__('SUBSCRIPTIONS','wplms').'<span>'.$value.'</span></li>';
                        break;
                        case 'sales':
                        $return_html .='<li>'.__('SALES','wplms').'<span>'.get_woocommerce_currency_symbol("USD").$value.'</span></li>';
                        break;
                        case 'commissions':
                        $return_html .='<li>'.__('EARNINGS','wplms').'<span>'.get_woocommerce_currency_symbol("USD").$value.'</span></li>';
                        break;
                    }
                }
            }
            $return_html .= '</ul>';
        }
        return $return_html;
    }

/*-----------------------------------------------------------------------------------*/
/*  Question
/*-----------------------------------------------------------------------------------*/

    function vibe_question( $atts, $content = null ) {
        extract(shortcode_atts(array(
        'id'   => '',
        ), $atts));
        
        if(!is_numeric($id))
            return '';
        $new_question = apply_filters('wplms_load_new_question_shortcode',true,$atts, $content);
        if($new_question && is_wplms_4_0()){

            wp_enqueue_script('wplms-question-js',plugins_url('/../../assets/js/question.js',__FILE__),array('wp-element','wp-data','wp-redux-routine','wp-hooks'),WPLMS_PLUGIN_VERSION,true);
            wp_localize_script('wplms-question-js','wplms_course_data',WPLMS_Course_Component_Init::get_wplms_course_data());
            wp_enqueue_style( 'wplms-wplms_quiz-css', plugins_url('/../../assets/css/wplms.css',__FILE__),array(),WPLMS_PLUGIN_VERSION);
            $html = '<div class="vibe_single_question" data-id="'.$id.'"></div>';

            
            return $html;
        }

        $question = new WP_Query(array('p'=>$id,'post_type'=>'question'));

        if($question->have_posts()){
            while($question->have_posts()){
                $question->the_post();
                global $post;
                $hint = get_post_meta($id,'vibe_question_hint',true);
                $type = get_post_meta($id,'vibe_question_type',true);
                $return ='<div class="question '.$type.'">';
                $return .='<div class="question_content">'.apply_filters('the_content',$post->post_content);
                if(isset($hint) && strlen($hint)>5){
                    $return .='<a class="show_hint tip" tip="'.__('SHOW HINT','wplms').'"><span></span></a>';
                    $return .='<div class="hint"><i>'.__('HINT','wplms').' : '.apply_filters('the_content',$hint).'</i></div>';
                }
                $return .='</div>';
                switch($type){
                    case 'truefalse': 
                    case 'single': 
                    case 'multiple': 
                    case 'sort':
                    case 'match':
                       $options = vibe_sanitize(get_post_meta($id,'vibe_question_options',false));

                      if($type == 'truefalse')
                        $options = array( 0 => __('FALSE','wplms'),1 =>__('TRUE','wplms'));

                      if(isset($options) || $options){
                    
                        $return .= '<ul class="question_options '.$type.'">';
                          if($type=='single'){
                            foreach($options as $key=>$value){
                              $return .= '<li>
                                        <div class="radio">
                                          <input type="radio" id="'.$post->post_name.$key.'" class="ques'.$id.'" name="'.$id.'" value="'.($key+1).'" />
                                          <label for="'.$post->post_name.$key.'"><span></span> '.do_shortcode($value).'</label>
                                        </div>
                                    </li>';
                            }
                          }else if($type == 'sort'){
                            foreach($options as $key=>$value){
                              $return .= '<li id="'.($key+1).'" class="ques'.$post->ID.' sort_option">
                                          <label for="'.$post->post_name.$key.'"><span></span> '.do_shortcode($value).'</label>
                                      </li>';
                            }        
                          }else if($type == 'match'){
                            foreach($options as $key=>$value){
                              $return .= '<li id="'.($key+1).'" class="ques'.$post->ID.' match_option">
                                          <label for="'.$post->post_name.$key.'"><span></span> '.do_shortcode($value).'</label>
                                      </li>';
                            }        
                          }else if($type == 'truefalse'){
                            foreach($options as $key=>$value){
                              $return .= '<li>
                                        <div class="radio">    
                                        <input type="radio" id="'.$post->post_name.$key.'" class="ques'.$post->ID.'" name="'.$post->ID.'" value="'.$key.'" />
                                        <label for="'.$post->post_name.$key.'"><span></span> '.$value.'</label>
                                        </div>
                                    </li>';
                            }       
                          }else{
                            foreach($options as $key=>$value){
                              $return .= '<li>
                                        <div class="checkbox">
                                        <input type="checkbox" class="ques'.$post->ID.'" id="'.$post->post_name.$key.'" name="'.$post->ID.$key.'" value="'.($key+1).'" />
                                        <label for="'.$post->post_name.$key.'"><span></span> '.do_shortcode($value).'</label>
                                        </div>
                                    </li>';
                            }
                          }  
                        $return .= '</ul>';
                      }
                    break; // End Options
                    case 'fillblank': 
                    break;
                    case 'select': 
                    break;
                    case 'smalltext': 
                      $return .= '<input type="text" name="'.$k.'" class="ques'.$k.' form_field" value="" placeholder="'.__('Type Answer','wplms').'" />';
                    break;
                    case 'largetext': 
                      $return .= '<textarea name="'.$k.'" class="ques'.$k.' form_field" placeholder="'.__('Type Answer','wplms').'"></textarea>';
                    break;
                  }
                  $return .='<ul class="check_options">';
                  
                  
                    $answer = get_post_meta($id,'vibe_question_answer',true);
                    if(isset($answer) && strlen($answer) && in_array($type,array('single','multiple','truefalse','sort','match','smalltext','select','fillblank'))){
                        $return .='<li><a class="check_answer" data-id="'.$id.'">'.__('Check Answer','wplms').'</a></li>';        
                        $ans_json = array('type' => $type);
                        if(in_array($type,array('multiple'))){
                            $ans_array =  explode(',',$answer);
                            $ans_json['answer'] = $ans_array;
                        }else{
                            $ans_json['answer'] = $answer; 
                    }
                    $return .= '<script>
                        var ans_json'.$id.'= '.json_encode($ans_json).';
                     </script>';
                  }

                  $explaination = get_post_meta($id,'vibe_question_explaination',true);
                  if(isset($explaination) && strlen($explaination)>2){
                    $return .= '<li><a href="#question_explaination'.$id.'" class="open_popup_link">'.__('Explanation','wplms').'</a></li>';
                  
                    $return .= '<div id="question_explaination'.$id.'" class="white-popup mfp-hide">
                      '.do_shortcode($explaination).'
                      </div>';
                  }

                $return .='</ul></div>';
            }
        }
        wp_reset_postdata();            
            
        return $return;
    }
    
/*-----------------------------------------------------------------------------------*/
/*  Certificates
/*-----------------------------------------------------------------------------------*/

    function vibe_show_certificates( $atts, $content = null ) {
        extract(shortcode_atts(array(
            'number'=>9,
            'size'=> 'medium',
            'columns'=>3,
            'course' => '',
            'user' => '',
        ), $atts));

        $args = array('post_type'=>'attachment','post_status'=>'inherit','post_mime_type' => 'image/jpeg,image/gif,image/jpg,image/png','posts_per_page'=>9);
        if(!empty($user)){
            if($user == 'current' && is_user_logged_in()){$user = get_current_user_id();}
            $args['author'] = $user;
        }
        if(empty($user)){
            $args['author'] = bp_displayed_user_id();
        }


        if(is_numeric($course)){
            $type = get_post_field('post_type',$course);
            if($type == 'course'){
                 $args['post_parent'] = $course;
            }
        }

        $attachments = new WP_Query($args);

        $ids = array();
        if($attachments->have_posts()){
            while(($attachments->have_posts())){
                $attachments->the_post();
                global $post;
                if(strpos($post->post_name, 'certificate_') !== false){
                    $ids[] = get_the_id();
                }
            }
        }
        wp_reset_postdata();
        if(!empty($ids)){
            $ids = implode(',',$ids);
            return do_shortcode('[gallery size="'.$size.'" columns="'.$columns.'" ids="'.$ids.'"]');    
        }else{
            return;
        }
    }

/*-----------------------------------------------------------------------------------*/
/*  Course Search box
/*-----------------------------------------------------------------------------------*/


    function vibe_course_search( $atts, $content = null ) {
        extract(shortcode_atts(array(
            'style'   => 'left'
        ), $atts));
        
        $html ='<form role="search" method="get" class="'.$style.'" action="'.home_url( '/' ).'">
            <input type="hidden" name="post_type" value="'.BP_COURSE_SLUG.'" />
            <input type="text" value="'.(isset($_GET['s'])?$_GET['s']:'').'" name="s" id="s" placeholder="'.__('Type Keywords..','wplms').'" />
            <input type="submit" id="searchsubmit" value="'.__('Search','wplms').'" />
            </form>';
        return $html;
    }

/*-----------------------------------------------------------------------------------*/
/*  Pass Fail shortcodes
/*-----------------------------------------------------------------------------------*/

    function vibe_pass_fail( $atts, $content = null ) {
        extract(shortcode_atts(array(
                'id'   => '',
                'key'   => '',
                'passing_score'   => '',
                'pass'=>0,
                'fail'=>0
            ), $atts));
        
        if(!is_numeric($id)){ 
            return;
        }
        if(!isset($key) || !$key){
            $key = get_current_user_id();
        }
        if(!isset($passing_score) || !$passing_score){
            $post_type=get_post_type($id);
            if($post_type == 'course'){
                $passing_score = get_post_meta($id,'vibe_course_passing_percentage',true);
            }else if($post_type == 'quiz'){
                $passing_score = get_post_meta($id,'vibe_quiz_passing_score',true);
            }else
                return;
        }
        $score = apply_filters('wplms_pass_fail_shortcode',get_post_meta($id,$key,true));

        if($pass && $score >$passing_score){ 
            return apply_filters('the_content',$content);
        }

        if($fail && $score <= $passing_score){
            return apply_filters('the_content',$content);
        }
        
        return '';
    }

    /*-----------------------------------------------------------------------------------*/
    /*   WPLMS REGISTRATION FORMS
    /*-----------------------------------------------------------------------------------*/

    function survey_result($atts, $content = null){
        extract(shortcode_atts(array(
                    'id'=>'',
                    'user_id'=>'',
                    'lessthan'   => '0',
                    'greaterthan'=>'100'
                ), $atts));
        if(empty($id)){
            global $post;
            if($post->post_type == 'quiz')
                $id = $post->ID;
            else if(isset($_GET['action']) && is_numeric($_GET['action'])){
                $post_type = get_post_type($_GET['action']);
                if($post_type == 'quiz')
                    $id = $post->ID;
            }
        }
        if(!is_numeric($id)){ 
            return;
        }
        if(!isset($user_id) || !$user_id){
            $user_id = get_current_user_id();
        }
        $score = apply_filters('wplms_survey_result_shortcode',get_post_meta($id,$user_id,true));
        
        if(isset($greaterthan)){ 
            if($score >=$greaterthan){
                if(isset($lessthan)){
                    if($score <= $lessthan){
                        return apply_filters('the_content',$content);
                    }
                }else{
                    return apply_filters('the_content',$content);
                }
            }
        }else if(isset($lessthan)){
            if($score <= $lessthan){
                return apply_filters('the_content',$content);
            }
        }

    }
    /*-----------------------------------------------------------------------------------*/
    /*   WPLMS REGISTRATION FORMS
    /*-----------------------------------------------------------------------------------*/ 

    function wplms_registration_form($atts, $content = null){
        extract(shortcode_atts(array(
                    'name'   => '',
                    'field_meta'=>0,
                ), $atts));

        if(empty($name) && function_exists('xprofile_get_field'))
            return;

        if(function_exists('vibe_get_option') && vibe_get_option('offload_scripts')){
            wp_enqueue_script( 'wplms-registration-forms', WPLMS_PLUGIN_INCLUDES_URL . '/vibe-shortcodes/js/registration_forms.js',array(),WPLMS_PLUGIN_VERSION,true);
        }
        
     
        $return = '';
        $forms = get_option('wplms_registration_forms');
        global $bp;
        $types = bp_get_member_types(array(),'objects');
        $member_types = [];
        if(!empty($types)){
            foreach($types as $type => $labels){
                $member_types[]=array('id'=>$type,'sname'=>$labels->labels['name']);
            }
        }


        if(!empty($forms[$name])){
            $fields = $forms[$name]['fields'];
            $settings = $forms[$name]['settings'];

            /*
            STANDARD FIELDS
            */

          
            $return = '<div class="wplms_registration_form" data-form-name="'.$name.'"><form action="/" name="signup_form" id="signup_form" class="standard-form" method="post" enctype="multipart/form-data">

            <ul>';
            if(empty($settings['hide_username'])){
                $return .='<li>'.'<label>'.__('Username','wplms').'</label>'.'<input type="text" name="signup_username" placeholder="'.__('Login Username','wplms').'" required></li>';
            }

            $return .='<li>'.'<label>'.__('Email','wplms').'</label>'.'<input type="email" name="signup_email" placeholder="'.__('Email','wplms').'" required></li>';

            $return .='<li>'.'<label '.(empty($settings['password_meter'])?:'for="signup_password"').'>'.__('Password','wplms').'</label>'.'<input type="password" '.(empty($settings['password_meter'])?'':'id="signup_password" class="form_field"').' name="signup_password" placeholder="'.__('Password','wplms').'" autocomplete="new-password"></li>';
            if(!empty($member_types) && count($member_types) && !empty($settings['member_type']) && $settings['member_type'] == 'enable_user_member_types_select'){
                $return .= '<li>'.'<label>'.__('Member type','wplms').'</label><select name="member_type" id="member_type"><option value="">'._x('None','','wplms').'</option>';

                foreach ($member_types as $member_type) {
                    $return .=  '<option value="'.$member_type['id'].'">'.$member_type['sname'].'</option>';
                }
                $return .= '</select></li>';
            }


            if(function_exists('bp_is_active') && bp_is_active('groups') && class_exists('BP_Groups_Group')){
                $vgroups = BP_Groups_Group::get(array(
                        'type'=>'alphabetical',
                        'per_page'=>999
                        ));

                $vgroups = apply_filters('wplms_custom_reg_form_groups_select_reg_form',$vgroups);
                $all_groups = array();
                foreach ($vgroups['groups'] as $value) {
                     $all_groups[$value->id] = $value->name;
                }
                if(!empty($all_groups ) && count($all_groups ) && !empty($settings['wplms_user_bp_group']) && is_array($settings['wplms_user_bp_group']) ){

                    if($settings['wplms_user_bp_group']===array('enable_user_select_group')){
                        $return .= '<li><label class="field_name">'.__('Group','wplms').'</label><select name="wplms_user_bp_group" id="wplms_user_bp_group"><option value="">'._x('None','','wplms').'</option>';
                        foreach ($all_groups as $key => $group) {
                            $return .= '<option value="'.$key.'">'.$group.'</option>';
                        }
                        $return .= '</select></li>';
                    }elseif(count($settings['wplms_user_bp_group'])>1){
                        $return .= '<li><label class="field_name">'.__('Group','wplms').'</label><select name="wplms_user_bp_group" id="wplms_user_bp_group"><option value="">'._x('None','','wplms').'</option>';
                       foreach($settings['wplms_user_bp_group'] as $group_id){
                            if(array_key_exists($group_id, $all_groups)){
                                 $return .= '<option value="'.$group_id.'">'.$all_groups[$group_id].'</option>';
                            }
                       }
                        $return .= '</select></li>';
                    }
                   
                }
            }


            if ( bp_is_active( 'xprofile' ) ) : 
                if ( bp_has_profile( array( 'fetch_field_data' => false ) ) ) : 
                    while ( bp_profile_groups() ) : bp_the_profile_group(); 

                        $return_fields = $return_heading = '';
                        if(!empty($settings['show_group_label'])){
                            $return_heading .= '</ul><h3 class="heading"><span>'.bp_get_the_profile_group_name();
                            $return_heading .= '</span></h3><p>'.do_shortcode(bp_get_the_profile_group_description()).'</p><ul>';

                        }

                        while ( bp_profile_fields() ) : bp_the_profile_field();
                        global $field;
                        $fname = str_replace(' ','_',$field->name);
                        if(is_array($fields) && in_array($fname,$fields)){


                            $return_fields .='<li>';
                            $field_type = bp_xprofile_create_field_type( bp_get_the_profile_field_type() );
                            ob_start();
                            ?><div<?php bp_field_css_class( 'bp-profile-field' ); ?>>
                            <?php
                            $field_type->edit_field_html();

                            if(!empty($field_meta)){

                                if ( bp_get_the_profile_field_description()){
                                     //now buddypress already show descption below the field since 2.9 
                                    if(function_exists('version_compare') && !empty($bp->version) && version_compare($bp->version, '2.9.0','<')){
                                        
                                        echo '<p class="description">'.bp_the_profile_field_description().'</p>';
                                    }
                                }
                                if(!(function_exists('vibe_get_option') && vibe_get_option('offload_scripts'))){


                                    do_action( 'bp_custom_profile_edit_fields_pre_visibility' );

                                    $can_change_visibility = bp_current_user_can( 'bp_xprofile_change_field_visibility' );?>

                                    <p class="field-visibility-settings-<?php echo $can_change_visibility ? 'toggle' : 'notoggle'; ?>" id="field-visibility-settings-toggle-<?php bp_the_profile_field_id(); ?>">

                                    <?php
                                    printf(
                                        __( 'This field can be seen by: %s', 'buddypress' ),
                                        '<span class="current-visibility-level">' . bp_get_the_profile_field_visibility_level_label() . '</span>'
                                    );
                                    ?>

                                    <?php if ( $can_change_visibility ) : ?>

                                        <a href="#" class="link visibility-toggle-link"><?php esc_html_e( 'Change', 'buddypress' ); ?></a>

                                    <?php endif; ?>
                                    </p>
                                    <?php if ( $can_change_visibility ) : 

                                        //bp_get_the_profile_field_visibility_level
                                        //$field_obj = xprofile_get_field( bp_the_profile_field_id() );
                                        ?>

                                        <div class="field-visibility-settings" id="field-visibility-settings-<?php bp_the_profile_field_id() ?>">
                                            <fieldset>
                                                <legend><?php _e( 'Who can see this field?', 'buddypress' ); ?></legend>

                                                <?php bp_profile_visibility_radio_buttons(); ?> 

                                            </fieldset>
                                            <a class="link field-visibility-settings-close" href="#"><?php esc_html_e( 'Close', 'buddypress' ); ?></a>
                                        </div>

                                    <?php endif; }?>
                                </div>
                            <?php
                            }
                            $check = ob_get_clean();

                            do_action( 'bp_custom_profile_edit_fields_pre_visibility' );

                            $can_change_visibility = bp_current_user_can( 'bp_xprofile_change_field_visibility' );
                            $return_fields .= $check;

                            $return_fields .='</li>';
                        }
                        endwhile;
                        if(!empty($return_fields)){
                            $return .= $return_heading;
                        }
                        $return .= $return_fields;
                    endwhile;
                endif;
            endif; 
           
            
            $form_settings = apply_filters('wplms_registration_form_settings',array(
                    'password_meter'         => __('Show password meter','wplms'),
                    'show_group_label'       => __('Show Field group labels','wplms'),
                    'google_captcha'         => __('Google Captcha','wplms'),
                    'custom_activation_mail' => __('Custom activation email','wplms'),
            ));
           
            foreach($form_settings as $key=>$setting){
                if(!empty($settings[$key])){
                    if(!empty($settings['google_captcha']) && function_exists('vibe_get_option') && $key == 'google_captcha'){
                        require_once('classes/recaptchalib.php');
                        $google_captcha_public_key = vibe_get_option('google_captcha_public_key');
                        if ( ! wp_script_is( 'google-recaptcha', 'enqueued' ) ) {
                            $wp_locale = get_locale();
                            $translate_captcha = apply_filters('translate_wplms_reg_form_captcha',1);
                            if(!empty($wp_locale) && $translate_captcha){
                                preg_match("/[a-z]*/", $wp_locale, $locale);
                                wp_enqueue_script( 'google-recaptcha', 'https://www.google.com/recaptcha/api.js'.(!empty($locale[0])?'?hl='.$locale[0]:'') );
                            }else{
                                wp_enqueue_script( 'google-recaptcha', 'https://www.google.com/recaptcha/api.js' );
                            }
                                
                        }


                        $return .= '<li><div class="g-recaptcha" data-theme="clean" data-sitekey="'.$google_captcha_public_key.'"></div></li>';
                    }
                    if( $key !== 'mailchimp_list' )
                        $return .= '<input type="hidden" name="'.$key.'" value="'.$settings[$key].'"/>';
                }
            }
            
            //SETTINGS
            
            ob_start();
            do_action('wplms_before_registration_form',$name);
            wp_nonce_field( 'bp_new_signup' ,'bp_new_signup');
            $return .= ob_get_clean();

            $return .='<li>'.apply_filters('wplms_registration_form_submit_button','<a href="#" class="submit_registration_form button">'.__('Register','wplms').'</a>').'</li>';
            $return .= '</ul></form></div>';
        }
        return $return;
    }
    /*
    REGISTRATION FROM DEFAULT ROLE
     */
    function activate_user($user_id,$key,$user){
  
        $user_data =  $user;
        if(is_multisite()){
            if(!empty($user_data) && !empty($user_data['meta'])){
                if(!empty($user_data['meta']['wplms_meta'] )){
                    foreach ($user_data['meta']['wplms_meta'] as $key => $value) {
                        if($key = 'default_role'){
                            wp_update_user(array('ID'=>$user_id,'role'=>$value));
                            $new_user = new WP_User( $user_id );
                            $new_user->add_role( $value );
                        }
                        if($key = 'member_type' && function_exists('bp_set_member_type')){
                            bp_set_member_type($user_id,$value);
                        }
                        if(function_exists('groups_join_group') && $key = 'wplms_user_bp_group' && is_numeric($value)){
                            groups_join_group($value, $user_id );
                        }
                    }
                }
                
                if(bp_is_active( 'xprofile' )){
                    unset($user_data['meta']['wplms_meta']);
                    $user_fields = $user_data['meta'];
                    if(!empty($user_fields)){
                        
                        foreach($user_fields as $field_id=>$val){
                            $field_id = explode('_',$field_id);
                            foreach($field_id as $f){
                                if(is_numeric($f)){
                                    $field = $f;
                                }
                            }
                            if(isset($val['value']))
                                xprofile_set_field_data( $field, $user_id, $val['value'] );
                            if(isset($val['visibility']))
                                xprofile_set_field_visibility_level( $field, $user_id, $val['visibility'] );
                        }
                    }
                }


            }

        }else{
            $default_role = get_user_meta($user_id,'default_role',true);
            if(!empty($default_role)){
                wp_update_user(array('ID'=>$user_id,'role'=>$default_role));
                $new_user = new WP_User( $user_id );
                $new_user->add_role( $default_role );
            }
        }
        
       
    }


    /* Custom Activation email */
    function get_custom_activation_email_args($args,$email_type){
        if($email_type != 'core-user-registration' && (empty($_POST) || empty($_POST['settings'])))
            return $args;

        $post_settings = json_decode(stripslashes($_POST['settings']));
        if(empty($post_settings))
            return $args;
        $settings = array();
        foreach($post_settings as $setting){
            $settings[$setting->id]=$setting->value;
        }
        
        if(is_numeric($settings['custom_activation_mail'])){
            
            if(wp_verify_nonce($_POST['security'],'bp_new_signup') ){
                $args['post__in'] = array($settings['custom_activation_mail']);
            }
        }elseif($settings['custom_activation_mail'] == 'no'){
            
            if(wp_verify_nonce($_POST['security'],'bp_new_signup') ){
                $args['post__in'] = array(0); 
            }
        }
        return $args;
    }

    function check_answer_correct($atts,$content){
        $ques_id = $atts['ques_id'];
        $quiz_id = $atts['quiz_id'];
        $return = '';
        if(!is_user_logged_in())
            return $return;
        $user_id = get_current_user_id();
        if(!empty($ques_id) && !empty($quiz_id) && function_exists('bp_course_evaluate_question')){
            if(!empty($_POST) && !empty($_POST['answer'])){
            $marked_answer=$_POST['answer'];
        }
        if(empty($marked_answer)){
            $marked_answer = bp_course_get_question_marked_answer($quiz_id,$ques_id,$user_id); 

        }
        $type = get_post_meta($ques_id,'vibe_question_type',true); 
        $correct_answer = get_post_meta($ques_id,'vibe_question_answer',true);
        $correct = bp_course_evaluate_question(array('question_id'=>$ques_id ,'type'=>$type,'correct_answer'=>$correct_answer),$marked_answer);
        if($correct){
            $return = do_shortcode($content);
        }
      }
        return $return;
    }

    function check_answer_incorrect($atts,$content){
      $ques_id = $atts['ques_id'];
      $quiz_id = $atts['quiz_id'];
      $return = '';
      if(!is_user_logged_in())
        return $return;
      $user_id = get_current_user_id();
      if(!empty($ques_id) && !empty($quiz_id) && function_exists('bp_course_evaluate_question')){
        if(!empty($_POST) && !empty($_POST['answer'])){
           $marked_answer=$_POST['answer'];
        }
        if(empty($marked_answer)){
          $marked_answer = bp_course_get_question_marked_answer($quiz_id,$ques_id,$user_id); 

        }
        $type = get_post_meta($ques_id,'vibe_question_type',true); 
        $correct_answer = get_post_meta($ques_id,'vibe_question_answer',true);
        $correct = bp_course_evaluate_question(array('question_id'=>$ques_id ,'type'=>$type,'correct_answer'=>$correct_answer),$marked_answer);
        if(!$correct){
            $return = do_shortcode($content);
        }
      }
      return $return;
    }

    function wplms_login_widget($atts,$content=null){
        if(!function_exists('vibe_include_template') || !function_exists('bp_loggedin_user_link'))
            return '[wplms_login_widget]';
        ob_start();

        echo '<header class="wplms_login_widget_shortcode">';
        if ( function_exists('bp_loggedin_user_link') && is_user_logged_in() ) :
                                ?>
                <a href="<?php bp_loggedin_user_link(); ?>" class="smallimg vbplogin"><?php $n=vbp_current_user_notification_count(); echo ((isset($n) && $n)?'<em></em>':''); bp_loggedin_user_avatar( 'type=full' ); ?><span><?php bp_loggedin_user_fullname(); ?></span></a>
        <?php
        else :
            ?>  
           <a href="#login" rel="nofollow" class=" vbplogin"><span><?php _e('LOGIN','wplms'); ?></span></a>          
                <?php
        endif;
        $style = vibe_get_login_style();
        if(empty($style)){
            $style='default_login';
        }
        ?>
        <div id="vibe_bp_login" class="<?php echo $style; ?>">
        <?php
            vibe_include_template("login/$style.php");
         ?>
        </div>
        <style>
            .wplms_login_widget_shortcode{background:transparent;}
            .wplms_login_widget_shortcode #vibe_bp_login.bigdrop_login:after{ display:none; }
            .wplms_login_widget_shortcode .vbplogin em { position: absolute; top: -3px; left: -3px; width: 6px; height: 6px; background: #78c8c9; border-radius: 50%; }
            .wplms_login_widget_shortcode #vibe_bp_login { top: 100%; left: 0; }
        </style>
       <?php
        $return = ob_get_clean();
        return $return;
    }

    /*
        EDIT COURSE PAGE
    */
    function edit_course($atts,$content=null){
        ?>
        <script>
            document.addEventListener('DOMContentLoaded',function(){
                if(!document.querySelect('body').classList.contains('loading')){
                    document.querySelect('body').classList.add('loading <?php echo vibe_get_option('page_loader');?>');
                    document.addEventListener('userLoaded',function(event){
                        window.location.href = event.details.userLoaded.profile_link.'#component=manage_courses';
                    });
                }
            });
            
        </script>
        <?php
    }
    

}

new Vibe_Define_Shortcodes;