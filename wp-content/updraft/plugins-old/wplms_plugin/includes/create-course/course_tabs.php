<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_filter('wplms_course_creation_tabs',function($tabs){

    if(function_exists('vibe_get_option')){
        $level = vibe_get_option('level');
        if(!empty($level)){
            $levels[] = array(
                            'label'=> __('Course Level','wplms' ),
                            'type'=> 'taxonomy',
                            'taxonomy'=> 'level',
                            'from'=>'taxonomy',
                            'value_type'=>'single',
                            'style'=>'col-md-12',
                            'id' => 'level',
                            'is_child'=>true,
                            'default'=> __('Select a Course Level','wplms' ),
                            );
            array_splice( $tabs['create_course']['fields'],1, 0, $levels );
        }
        $location = vibe_get_option('location');
        if(!empty($location)){
            $locations[] = array(
                            'label'=> __('Course Location','wplms' ),
                            'type'=> 'taxonomy',
                            'taxonomy'=> 'location',
                            'from'=>'taxonomy',
                            'value_type'=>'single',
                            'style'=>'col-md-12',
                            'id' => 'location',
                            'is_child'=>true,
                            'default'=> __('Select a Course location','wplms' ),
                            );
            array_splice( $tabs['create_course']['fields'],1, 0, $locations );
            $tabs['create_course']['fields'][0]['children']=array('level','location');
        }
    }

    return $tabs;
},10);

function wplms_get_question_types(){
    return apply_filters('wplms_question_types',array(
          array( 'label' =>__('True or False','wplms'),'value'=>'truefalse',
            'fields'=>array(
                array(
                    'label'=> __('Question title','wplms' ),
                    'type'=> 'title',
                    'id' => 'post_title',
                    'from'=>'post',
                    'value_type'=>'single',
                    'style'=>'full',
                    'default'=> __('Question Title','wplms' ),
                    'desc'=> __('This is the title of the unit which is displayed on top of every unit','wplms' )
                    ),
                array(
                    'label'=> __('Question tag','wplms' ),
                    'text'=> __('Question tag','wplms' ),
                    'type'=> 'taxonomy',
                    'taxonomy'=> 'question-tag',
                    'from'=>'taxonomy',
                    'value_type'=>'multiple',
                    'style'=>'assign_cat',
                    'id' => 'question-tag',
                    'default'=> __('Select a tag','wplms' ),
                ),
                array(
                    'label'=> __('Question description','wplms' ),
                    'text'=> __('Write the question statement','wplms' ),
                    'type'=> 'editor',
                    'style'=>'',
                    'value_type'=>'single',
                    'id' => 'post_content',
                    'from'=>'post',
                    'extras' => '',
                    'default'=> __('Enter a short description about the question.','wplms' ),
                ),
                array( // Text Input
                    'label' => __('Correct Answer','wplms'),
                    'text'  => __('Enter (1 = True, 0 = false )','wplms'),
                    'id'    => 'vibe_question_answer',
                    'type'  => 'text',
                    'from'  => 'meta',
                    'default'   => 0
                ),
                array( // Text Input
                    'label' => __('Answer Hint','wplms'),
                    'default'  => __('Add a Hint/clue to the question','wplms'),
                    'id'    => 'vibe_question_hint',
                    'type'  => 'textarea',
                    'from'  => 'meta',
                    'default'   => ''
                ),
                array( 
                    'label' => __('Answer Explanation','wplms'), 
                    'text'=> __('Explain the correct answer','wplms' ),
                    'id'    => 'vibe_question_explaination',
                    'type'  => 'editor',
                    'from'  => 'meta',
                    'default'   => ''
                ),
            )),  
          array( 'label' =>__('Multiple Choice','wplms'),'value'=>'single','fields'=>array(
                array(
                    'label'=> __('Question title','wplms' ),
                    'type'=> 'title',
                    'id' => 'post_title',
                    'from'=>'post',
                    'value_type'=>'single',
                    'style'=>'full',
                    'default'=> __('Question Title','wplms' ),
                    'desc'=> __('This is the title of the unit which is displayed on top of every unit','wplms' )
                    ),
                array(
                    'label'=> __('Question tag','wplms' ),
                    'type'=> 'taxonomy',
                    'taxonomy'=> 'question-tag',
                    'from'=>'taxonomy',
                    'value_type'=>'multiple',
                    'style'=>'assign_cat',
                    'id' => 'question-tag',
                    'default'=> __('Select a tag','wplms' ),
                ),
                array(

                    'label'=> __('Question description','wplms' ),
                    'type'=> 'editor',
                    'style'=>'',
                    'value_type'=>'single',
                    'id' => 'post_content',
                    'from'=>'post',
                    'extras' => '',
                    'default'=> __('Enter a short description about the quiz.','wplms' ),
                ),
                array(
                    'label'=> __('Question Choices','wplms' ),
                    'type'=> 'repeatable',
                    'style'=>'',
                    'value_type'=>'single',
                    'id' => 'vibe_question_options',
                    'from'=>'meta',
                    'extras' => '',
                    'default'=> __('Enter a short description about the quiz.','wplms' ),
                ),
                array( // Text Input
                    'label' => __('Correct Answer','wplms'),
                    'text'  => __('Correct Choice Number (1,2..)','wplms'),
                    'id'    => 'vibe_question_answer',
                    'type'  => 'text',
                    'from'  => 'meta',
                    'std'   => 0
                ),
                array( // Text Input
                    'label' => __('Answer Hint','wplms'),
                    'default'  => __('Add a Hint/clue','wplms'),
                    'id'    => 'vibe_question_hint',
                    'type'  => 'textarea',
                    'from'  => 'meta',
                    'std'   => ''
                ),
                array( 
                    'label' => __('Answer Explanation','wplms'), 
                    'text'=> __('Explain the correct answer','wplms' ),
                    'id'    => 'vibe_question_explaination',
                    'type'  => 'editor',
                    'from'  => 'meta',
                    'std'   => ''
                ),
            )),
          array( 'label' =>__('Multiple Correct','wplms'),'value'=>'multiple','fields'=>array(
                array(
                    'label'=> __('Question title','wplms' ),
                    'type'=> 'title',
                    'id' => 'post_title',
                    'from'=>'post',
                    'value_type'=>'single',
                    'style'=>'full',
                    'default'=> __('Question Title','wplms' ),
                    'desc'=> __('This is the title of the unit which is displayed on top of every unit','wplms' )
                    ),
                array(
                    'label'=> __('Question tag','wplms' ),
                    'type'=> 'taxonomy',
                    'taxonomy'=> 'question-tag',
                    'from'=>'taxonomy',
                    'value_type'=>'multiple',
                    'style'=>'assign_cat',
                    'id' => 'question-tag',
                    'default'=> __('Select a tag','wplms' ),
                ),
                array(

                    'label'=> __('Question description','wplms' ),
                    'type'=> 'editor',
                    'style'=>'',
                    'value_type'=>'single',
                    'id' => 'post_content',
                    'from'=>'post',
                    'extras' => '',
                    'default'=> __('Enter a short description about the quiz.','wplms' ),
                ),
                array(
                    
                    'label'=> __('Question Choices','wplms' ),
                    'type'=> 'repeatable',
                    'style'=>'',
                    'value_type'=>'single',
                    'id' => 'vibe_question_options',
                    'from'=>'meta',
                    'extras' => '',
                    'default'=> __('Enter a short description about the quiz.','wplms' ),
                ),
                array( // Text Input
                    'label' => __('Correct Answer','wplms'),
                    'text'  => __('Comma separated Choice numbers (1,2..)','wplms'),
                    'id'    => 'vibe_question_answer',
                    'from'  => 'meta',
                    'type'  => 'text',
                    'std'   => 0
                ),
                array( // Text Input
                    'label' => __('Answer Hint','wplms'),
                    'default'  => __('Add a Hint/clue','wplms'),
                    'id'    => 'vibe_question_hint',
                    'type'  => 'textarea',
                    'from'  => 'meta',
                    'std'   => ''
                ),
                array( 
                    'label' => __('Answer Explanation','wplms'), 
                    'text'=> __('Explain the correct answer','wplms' ),
                    'id'    => 'vibe_question_explaination',
                    'type'  => 'editor',
                    'from'  => 'meta',
                    'std'   => ''
                ),
            )),
          array( 'label' =>__('Sort Answers','wplms'),'value'=>'sort','fields'=>array(
                array(
                    'label'=> __('Question title','wplms' ),
                    'type'=> 'title',
                    'id' => 'post_title',
                    'from'=>'post',
                    'value_type'=>'single',
                    'style'=>'full',
                    'default'=> __('Question Title','wplms' ),
                    'desc'=> __('This is the title of the unit which is displayed on top of every unit','wplms' )
                    ),
                array(
                    'label'=> __('Question tag','wplms' ),
                    'type'=> 'taxonomy',
                    'taxonomy'=> 'question-tag',
                    'from'=>'taxonomy',
                    'value_type'=>'multiple',
                    'style'=>'assign_cat',
                    'id' => 'question-tag',
                    'default'=> __('Select a tag','wplms' ),
                ),
                array(
                    'label'=> __('Question description','wplms' ),
                    'type'=> 'editor',
                    'style'=>'',
                    'value_type'=>'single',
                    'id' => 'post_content',
                    'from'=>'post',
                    'extras' => '',
                    'default'=> __('Enter a short description about the quiz.','wplms' ),
                    'value' => 'Question Statement : Arrange the below options in following order: 4,3,2,1',
                ),
                array(
                    
                    'label'=> __('Question Sort Options','wplms' ),
                    'type'=> 'repeatable',
                    'style'=>'',
                    'value_type'=>'single',
                    'id' => 'vibe_question_options',
                    'from'=>'meta',
                    'extras' => '',
                    'default'=> __('Enter a short description about the quiz.','wplms' ),
                    'value' => array (
                                'Option 1',
                                'Option 2',
                                'Option 3',
                                'Option 4',
                                )
                ),
                array( // Text Input
                    'label' => __('Correct Answer','wplms'),
                    'text'  => __('Comma separated correct order of choice numbers (1,2..)','wplms'),
                    'id'    => 'vibe_question_answer',
                    'type'  => 'text',
                    'from'  => 'meta',
                    'std'   => 0,
                    'value' => '4,3,2,1'


                ),
                array( // Text Input
                    'label' => __('Answer Hint','wplms'),
                    'default'  => __('Add a Hint/clue','wplms'),
                    'id'    => 'vibe_question_hint',
                    'type'  => 'textarea',
                    'std'   => '',
                    'from' => 'meta'
                ),
                array( 
                    'label' => __('Answer Explanation','wplms'), 
                    'text'=> __('Explain the correct answer','wplms' ),
                    'id'    => 'vibe_question_explaination',
                    'type'  => 'editor',
                    'from'  => 'meta',
                    'std'   => ''
                ),
            )),
          array( 'label' =>__('Match Answers','wplms'),'value'=>'match','fields'=>array(
                array(
                    'label'=> __('Question title','wplms' ),
                    'type'=> 'title',
                    'id' => 'post_title',
                    'from'=>'post',
                    'value_type'=>'single',
                    'style'=>'full',
                    'default'=> __('Question Title','wplms' ),
                    'desc'=> __('This is the title of the unit which is displayed on top of every unit','wplms' ),
                    ),
                array(
                    'label'=> __('Question tag','wplms' ),
                    'type'=> 'taxonomy',
                    'taxonomy'=> 'question-tag',
                    'from'=>'taxonomy',
                    'value_type'=>'multiple',
                    'style'=>'assign_cat',
                    'id' => 'question-tag',
                    'default'=> __('Select a tag','wplms' ),
                ),
                array(

                    'label'=> __('Question description','wplms' ),
                    'type'=> 'editor',
                    'style'=>'',
                    'value_type'=>'single',
                    'id' => 'post_content',
                    'from'=>'post',
                    'extras' => '',
                    'default'=> __('Enter a short description about the quiz.','wplms' ),
                    'value'=>'Question Statement : Arrange the below options in following order: 4,3,2,1
                        [match]
                        <ul>
                            <li>First Order</li>
                            <li>Second Order</li>
                            <li>Third order</li>
                            <li>Fourth Order</li>
                        </ul>
                        [/match]',
                ),
                array(
                    
                    'label'=> __('Question Match Options','wplms' ),
                    'type'=> 'repeatable',
                    'style'=>'',
                    'value_type'=>'single',
                    'id' => 'vibe_question_options',
                    'from'=>'meta',
                    'extras' => '',
                    'default'=> __('Enter a short description about the quiz.','wplms' ),
                    'value' => array (
                                'Option 1',
                                'Option 2',
                                'Option 3',
                                'Option 4',
                                )
                ),
                array( // Text Input
                    'label' => __('Correct Answer','wplms'),
                    'text'  => __('Comma separated correct order of choice numbers (1,2..)','wplms'),
                    'id'    => 'vibe_question_answer',
                    'type'  => 'text',
                    'from'  => 'meta',
                    'std'   => 0,
                    'value' => '4,3,2,1'
                ),
                array( // Text Input
                    'label' => __('Answer Hint','wplms'),
                    'text'  => __('Add a Hint/clue','wplms'),
                    'id'    => 'vibe_question_hint',
                    'type'  => 'textarea',
                    'from'  => 'meta',
                    'std'   => ''
                ),
                array( 
                    'label' => __('Answer Explanation','wplms'), 
                    'text'=> __('Explain the correct answer','wplms' ),
                    'id'    => 'vibe_question_explaination',
                    'type'  => 'editor',
                    'from'  => 'meta',
                    'std'   => ''
                ),
            )),
          array( 'label' =>__('Fill in the Blank','wplms'),'value'=>'fillblank','fields'=>array(
                array(
                    'label'=> __('Question title','wplms' ),
                    'type'=> 'title',
                    'id' => 'post_title',
                    'from'=>'post',
                    'value_type'=>'single',
                    'style'=>'full',
                    'default'=> __('Question Title','wplms' ),
                    'desc'=> __('This is the title of the unit which is displayed on top of every unit','wplms' ),

                    
                    ),
                array(
                    'label'=> __('Question tag','wplms' ),
                    'type'=> 'taxonomy',
                    'taxonomy'=> 'question-tag',
                    'from'=>'taxonomy',
                    'value_type'=>'multiple',
                    'style'=>'assign_cat',
                    'id' => 'question-tag',
                    'default'=> __('Select a tag','wplms' ),
                ),
                array(

                    'label'=> __('Question description','wplms' ),
                    'type'=> 'editor',
                    'style'=>'',
                    'value_type'=>'single',
                    'id' => 'post_content',
                    'from'=>'post',
                    'extras' => '',
                    'default'=> __('Enter question description','wplms' ),
                    'value'=>'Question Statement : Fill in the blank [fillblank] and another [fillblank]',
                ),
                array( // Text Input
                    'label' => __('Correct Answer','wplms'),
                    'text'  => __('Multiple Blanks Correct Answer separated by pipe | and comma separated variations','wplms'),
                    'id'    => 'vibe_question_answer',
                    'type'  => 'text',
                    'from'=>'meta',
                    'std'   => 0,
                    'value'=>'somevalue|anothervalue'
                ),
                array( // Text Input
                    'label' => __('Answer Hint','wplms'),
                    'text'  => __('Add a Hint/clue','wplms'),
                    'id'    => 'vibe_question_hint',
                    'type'  => 'textarea',
                    'from'=>'meta',
                    'std'   => 'somevalue and anothervalue'
                ),
                array( 
                    'label' => __('Answer Explanation','wplms'), 
                    'text'=> __('Explain the correct answer','wplms' ),
                    'id'    => 'vibe_question_explaination',
                    'type'  => 'editor',
                    'from'=>'meta',
                    'std'   => ''
                ),
            )),
          array( 'label' =>__('Dropdown Select','wplms'),'value'=>'select','fields'=>array(
                array(
                    'label'=> __('Question title','wplms' ),
                    'type'=> 'title',
                    'id' => 'post_title',
                    'from'=>'post',
                    'value_type'=>'single',
                    'style'=>'full',
                    'default'=> __('Question Title','wplms' ),
                    'desc'=> __('This is the title of the unit which is displayed on top of every unit','wplms' ),
                   
                    ),
                array(
                    'label'=> __('Question tag','wplms' ),
                    'type'=> 'taxonomy',
                    'taxonomy'=> 'question-tag',
                    'from'=>'taxonomy',
                    'value_type'=>'multiple',
                    'style'=>'assign_cat',
                    'id' => 'question-tag',
                    'default'=> __('Select a tag','wplms' ),
                ),
                array(

                    'label'=> __('Question description','wplms' ),
                    'type'=> 'editor',
                    'style'=>'',
                    'value_type'=>'single',
                    'id' => 'post_content',
                    'from'=>'post',
                    'extras' => '',
                    'default'=> __('Enter a short description about the quiz.','wplms' ),
                     'value'=>'Question Statement : Select correct answer out of the following [select] and another [select]',
                ),
                array(
                    
                    'label'=> __('Question Select Options','wplms' ),
                    'type'=> 'repeatable',
                    'style'=>'',
                    'value_type'=>'single',
                    'id' => 'vibe_question_options',
                    'from'=>'meta',
                    'extras' => '',
                    'default'=> __('Enter a short description about the quiz.','wplms' ),
                    'value' => array (
                                'Option 1',
                                'Option 2',
                                'Option 3',
                                'Option 4',
                                'Option 5',
                                'Option 6',
                                )
                ),
                array( // Text Input
                    'label' => __('Correct Answer','wplms'),
                    'text'  => __('Multiple Select Correct Answer separated by pipe |','wplms'),
                    'id'    => 'vibe_question_answer',
                    'type'  => 'text',
                    'from'=>'meta',
                    'std'   => 0,
                    'value'=>'1|4'
                ),
                array( // Text Input
                    'label' => __('Answer Hint','wplms'),
                    'text'  => __('Add a Hint/clue','wplms'),
                    'id'    => 'vibe_question_hint',
                    'type'  => 'textarea',
                    'from'=>'meta',
                    'std'   => '',
                    'value' => 'Option 1 and Option 4'
                ),
                array( 
                    'label' => __('Answer Explanation','wplms'), 
                    'text'=> __('Explain the correct answer','wplms' ),
                    'from'=>'meta',
                    'id'    => 'vibe_question_explaination',
                    'type'  => 'editor',
                    'std'   => ''
                ),
            )),
          array( 'label' =>__('Small Text','wplms'),'value'=>'smalltext','fields'=>array(
                array(
                    'label'=> __('Question title','wplms' ),
                    'type'=> 'title',
                    'id' => 'post_title',
                    'from'=>'post',
                    'value_type'=>'single',
                    'style'=>'full',
                    'default'=> __('Question Title','wplms' ),
                    'desc'=> __('This is the title of the unit which is displayed on top of every unit','wplms' )
                    ),
                array(
                    'label'=> __('Question tag','wplms' ),
                    'type'=> 'taxonomy',
                    'taxonomy'=> 'question-tag',
                    'from'=>'taxonomy',
                    'value_type'=>'multiple',
                    'style'=>'assign_cat',
                    'id' => 'question-tag',
                    'default'=> __('Select a tag','wplms' ),
                ),
                array(

                    'label'=> __('Question description','wplms' ),
                    'type'=> 'editor',
                    'style'=>'',
                    'value_type'=>'single',
                    'id' => 'post_content',
                    'from'=>'post',
                    'extras' => '',
                    'default'=> __('Enter a short description about the quiz.','wplms' ),
                ),
                array( // Text Input
                    'label' => __('Correct Answer','wplms'),
                    'text'  => __('Comma separated variations','wplms'),
                    'from'=>'meta',
                    'id'    => 'vibe_question_answer',
                    'type'  => 'text',
                    'std'   => 0
                ),
                array( // Text Input
                    'label' => __('Answer Hint','wplms'),
                    'text'  => __('Add a Hint/clue','wplms'),
                    'id'    => 'vibe_question_hint',
                    'from'=>'meta',
                    'type'  => 'textarea',
                    'std'   => ''
                ),
                array( 
                    'label' => __('Answer Explanation','wplms'), 
                    'text'=> __('Explain the correct answer','wplms' ),
                    'from'=>'meta',
                    'id'    => 'vibe_question_explaination',
                    'type'  => 'editor',
                    'std'   => ''
                ),
            )),
          array( 'label' =>__('Large Text','wplms'),'value'=>'largetext','fields'=>array(
                array(
                    'label'=> __('Question title','wplms' ),
                    'type'=> 'title',
                    'id' => 'post_title',
                    'from'=>'post',
                    'value_type'=>'single',
                    'style'=>'full',
                    'default'=> __('Question Title','wplms' ),
                    'desc'=> __('This is the title of the unit which is displayed on top of every unit','wplms' )
                    ),
                array(
                    'label'=> __('Question tag','wplms' ),
                    'type'=> 'taxonomy',
                    'taxonomy'=> 'question-tag',
                    'from'=>'taxonomy',
                    'value_type'=>'multiple',
                    'style'=>'assign_cat',
                    'id' => 'question-tag',
                    'default'=> __('Select a tag','wplms' ),
                ),
                array(

                    'label'=> __('Question description','wplms' ),
                    'type'=> 'editor',
                    'style'=>'',
                    'value_type'=>'single',
                    'id' => 'post_content',
                    'from'=>'post',
                    'extras' => '',
                    'default'=> __('Enter a short description about the quiz.','wplms' ),
                ),
                array( // Text Input
                    'label' => __('Correct Answer','wplms'),
                    'text'  => __('Comma separated variations','wplms'),
                    'from'=>'meta',
                    'id'    => 'vibe_question_answer',
                    'type'  => 'text',
                    'std'   => 0
                ),
                array( // Text Input
                    'label' => __('Answer Hint','wplms'),
                    'text'  => __('Add a Hint/clue','wplms'),
                    'id'    => 'vibe_question_hint',
                    'from'=>'meta',
                    'type'  => 'textarea',
                    'std'   => ''
                ),
                array( 
                    'label' => __('Answer Explanation','wplms'), 
                    'text'=> __('Explain the correct answer','wplms' ),
                    'from'=>'meta',
                    'id'    => 'vibe_question_explaination',
                    'type'  => 'editor',
                    'std'   => ''
                ),
            )),
          array( 'label' =>__('Survey Type','wplms'),'value'=>'survey','fields'=>array(
                array(
                    'label'=> __('Question title','wplms' ),
                    'type'=> 'title',
                    'id' => 'post_title',
                    'from'=>'post',
                    'value_type'=>'single',
                    'style'=>'full',
                    'default'=> __('Question Title','wplms' ),
                    'desc'=> __('This is the title of the unit which is displayed on top of every unit','wplms' )
                    ),
                array(
                    'label'=> __('Question tag','wplms' ),
                    'type'=> 'taxonomy',
                    'taxonomy'=> 'question-tag',
                    'from'=>'taxonomy',
                    'value_type'=>'multiple',
                    'style'=>'assign_cat',
                    'id' => 'question-tag',
                    'default'=> __('Select a tag','wplms' ),
                ),
                array(

                    'label'=> __('Question description','wplms' ),
                    'type'=> 'editor',
                    'style'=>'',
                    'value_type'=>'single',
                    'id' => 'post_content',
                    'from'=>'post',
                    'extras' => '',
                    'default'=> __('Enter a short description about the quiz.','wplms' ),
                ),
            ))
    ));
}

function get_wplms_create_course_tabs($course_id=null,$user_id=null){
    $status = 0;
    $product_id = 0;
    
    if(!empty($course_id)){
        $status = get_post_field($course_id,'post_status');
        $product_id = get_post_meta($course_id,'vibe_product',true);
    }

    $course_duration_parameter = apply_filters('vibe_course_duration_parameter',86400,$course_id);
    $drip_duration_parameter = apply_filters('vibe_drip_duration_parameter',86400,$course_id);
    $max_upload = (int)(ini_get('upload_max_filesize'));
    $max_post = (int)(ini_get('post_max_size'));
    $memory_limit = (int)(ini_get('memory_limit'));
    $upload_mb = min($max_upload, $max_post, $memory_limit);


    $vibe_subscription = 'H';$vibe_product_duration =9999;$sale_price = null;
    $price = 1;
    $product_duration_parameter = apply_filters('vibe_product_duration_parameter',86400,$product_id);
    if(!empty($product_id) && is_numeric($product_id)){

        if(function_exists('wc_get_product')){
            $product = wc_get_product($product_id);
        }
        if(!empty($product)){
            if( $product->is_on_sale() ) {
                $sale_price = $product->get_sale_price();
            }
            $price = $product->get_regular_price();
            if(empty($price)){
                $price = $product->get_price();
                if(empty($price)){
                    $price = 0;
                }
                
            }  
        }
        
        $vibe_subscription = get_post_meta($product_id,'vibe_subscription',true);
        $vibe_product_duration = get_post_meta($product_id,'vibe_duration',true);
        
    }

    

    $course_tabs = apply_filters('wplms_course_creation_tabs',array(
    'create_course'=>array(
            'icon'=> 'vicon vicon-bookmark-alt',
            'title'=> (isset($course_id)?__('EDIT COURSE','wplms' ):__('CREATE COURSE','wplms' )),
            'subtitle'=>  __('Start building a course','wplms' ),
            'fields'=> array(
                array(
                    'label'=> __('Course Category','wplms' ),
                    'type'=> 'taxonomy',
                    'taxonomy'=> 'course-cat',
                    'from'=>'taxonomy',
                    'value_type'=>'single',
                    'style'=>'assign_cat',
                    'id' => 'course-cat',
                    'default'=> __('Select a Course Category','wplms' ),
                    ),
                array(
                    'label'=> __('Course title','wplms' ),
                    'type'=> 'title',
                    'id' => 'post_title',
                    'from'=>'post',
                    'value_type'=>'single',
                    'style'=>'full',
                    'default'=> __('Course Name','wplms' ),
                    'desc'=> __('This is the title of the course which is displayed on top of every course','wplms' )
                    ),
                array(
                    'label'=> __('Course Image','wplms' ),
                    'type'=> 'course_featured',
                    'level'=>'thumbnail',
                    'value_type'=>'single',
                    'upload_title'=>__('Upload a Course Image','wplms' ),
                    'upload_button'=>__('Set as Course Image','wplms' ),
                    'style'=>'',
                    'from'=>'meta',
                    'id' => '_thumbnail_id',
                    'default'=> '',
                    'children'=>array('post_video')
                ),
                array(
                    'label'=> __('Add course video','wplms' ),
                    'type'=> 'featured_video',
                    'level'=>'video',
                    'value_type'=>'single',
                    'upload_title'=>__('Upload a Video','wplms' ),
                    'desc'=>__('Select or Upload a video','wplms' ),
                    'upload_button'=>__('Set as Course Video','wplms' ),
                    'style'=>'small_icon',
                    'from'=>'meta',
                    'is_child'=>true,
                    'id' => 'post_video',
                    'default'=> '',
                ),
                array(
                    'label'=> __('What is the course about','wplms' ),
                    'type'=> 'textarea',
                    'style'=>'',
                    'value_type'=>'single',
                    'id' => 'post_excerpt',
                    'from'=>'post',
                    'extras' => '',
                    'default'=> __('Enter a short description about the course.','wplms' ),
                    'children'=>array('post_content')
                    ),
                array(
                    'label'=> __('Detailed Description of the Course','wplms' ),
                    'type'=> 'editor',
                    'style'=>'tag_open',
                    'value_type'=>'single',
                    'id' => 'post_content',
                    'from'=>'post',
                    'is_child'=>true,
                    'noscript'=>true,
                    'default'=> __('Enter full description of the course.','wplms' ),
                ),
                array(
                    'label'=> __('Course duration','wplms' ),
                    'type'=> 'duration',
                    'style'=>'course_duration_stick_left',
                    'id' => 'vibe_duration',
                    'from'=> 'meta',
                    'extra' => '<span data-connect="vibe_course_duration_parameter">'.wplms_calculate_duration_time($course_duration_parameter).'</span>',
                    'default'=> 9999,
                    'value'=> array('value'=>9999,'parameter'=>86400),
                    'desc'=> sprintf(__('Enter the maximum duration for the course in %s. This is the maximum duration within which the student should complete the course. Use 9999 for unlimited access to course.','wplms' ),wplms_calculate_duration_time($course_duration_parameter)),
                    'children'=>array('vibe_max_students','vibe_start_date','vibe_course_auto_eval')
                ),
                array(
                    'label'=> __('Maximum Seats in Course','wplms' ),
                    'text'=>__('Maximum students that can join the Course','wplms' ),
                    'type'=> 'number',
                    'style'=>'',
                    'id' => 'vibe_max_students',
                    'default'=> 9999,
                    'is_child'=>true,
                    'from'=> 'meta',
                    'desc'=> __('Maximum number of seats in course (blank to disable, 9999 for infinite)','wplms' ),
                    ),
                array(
                    'label'=> __('Course Start Date','wplms' ),
                    'text'=>__('Start date','wplms' ),
                    'type'=> 'date',
                    'style'=>'',
                    'id' => 'vibe_start_date',
                    'default'=> the_date('Y-m-d','','',false),
                    'from'=> 'meta',
                    'is_child'=>true,
                    'desc'=> __('Set a Course start date.','wplms' ),
                ),
                array(
                    'label'=> __('Automatic Evaluation','wplms' ),
                    'text'=>__('Course Evaluation Mode','wplms' ),
                    'type'=> 'switch',
                    'options'  => array('H'=>__('MANUAL','wplms' ),'S'=>__('AUTOMATIC','wplms' )),
                    'style'=>'',
                    'id' => 'vibe_course_auto_eval',
                    'from'=> 'meta',
                    'default'=>'H',
                    'is_child'=>true,
                    'desc'=> __('User gets the course result instantly upon submission.','wplms' )
                    ),
                array(
                    'label'=>__('Move to Advanced Settings','wplms' ),
                    'id'=>'save_course_button',
                    'type'=>'next_button'
                    ),
                ),
        ),
    'course_settings'=>array(
            'icon'=> 'vicon vicon-settings',
            'title'=> __('SETTINGS','wplms' ),
            'subtitle'=>  __('Advance settings','wplms' ),
            'fields'=>array(
                array(
                    'label'=> __('Course Prerequisites','wplms' ),
                    'text'=>__('Students must finish following Courses to access this course.','wplms' ),
                    'type'=> 'selectmulticpt',
                    'cpt'=> 'course',
                    'style'=>'',
                    'id' => 'vibe_pre_course',
                    'placeholder'=> __('Search course','wplms' ),
                    'from'=> 'meta', 
                    'desc'=> __('Pre-required course which the user needs to complete before subscribing to this course.','wplms' ),
                    ),
                array(
                    'label'=> __('Locks','wplms' ),
                    'text'=>__('Previous Units/Quiz must be Complete before next unit/quiz access','wplms' ),
                    'type'=> 'switch',
                    'options'  => array('H'=>__('NO','wplms' ),'S'=>__('YES','wplms' )),
                    'style'=>'',
                    'id' => 'vibe_course_prev_unit_quiz_lock',
                    'from'=> 'meta',
                    'default'=>'H',
                    'desc'=> __('Force previous unit access lock.','wplms' )
                    ),
                array(
                    'label'=> __('Course Type','wplms' ),
                    'text'=>__('Offline Course','wplms' ),
                    'type'=> 'switch',
                    'default'=>'H',
                    'options'  => array('H'=>__('ONLINE','wplms' ),'S'=>__('OFFLINE','wplms' )),
                    'style'=>'',
                    'id' => 'vibe_course_offline',
                    'from'=> 'meta',
                    
                    'children'=>array('vibe_course_unit_content','vibe_course_button','vibe_course_progress','vibe_course_auto_progress','vibe_course_review'),
                    'desc'=> __('Offline Courses can be filtered in the Course directory.','wplms' )
                    ),
                array(
                    'label'=> __('Unit Content (Offline Courses)','wplms' ),
                    'text'=>__('Full units in Curriculum','wplms' ),
                    'type'=> 'switch',
                    'options'  => array('H'=>__('HIDE','wplms' ),'S'=>__('SHOW','wplms' )),
                    'style'=>'',
                    'id' => 'vibe_course_unit_content',
                    'from'=> 'meta',
                    'default'=>'H',
                    'is_child'=>true,
                    'desc'=> __('Full Unit content is available for users subscribed to the course, directly from Course curriculum. Recommended for Offline Courses.','wplms' )
                    ),
                array(
                    'label'=> __('Course Button (Offline Courses)','wplms' ),
                    'text'=>__('Hide Course Button after subscription','wplms' ),
                    'type'=> 'switch',
                    'options'  => array('H'=>__('NO','wplms' ),'S'=>__('YES','wplms' )),
                    'style'=>'',
                    'id' => 'vibe_course_button',
                    'from'=> 'meta',
                    'default'=>'H',
                    'is_child'=>true,
                    'desc'=> __('Hide the Course button after user is subscribed to the Course.','wplms' )
                    ),
                array(
                    'label'=> __('Course Progress (Offline Courses)','wplms' ),
                    'text'=>__('Progress on Course home','wplms' ),
                    'type'=> 'switch',
                    'options'  => array('H'=>__('HIDE','wplms' ),'S'=>__('SHOW','wplms' )),
                    'style'=>'',
                    'id' => 'vibe_course_progress',
                    'from'=> 'meta',
                    'default'=>'H',
                    'is_child'=>true,
                    'desc'=> __('Display Course progress on Course home page.','wplms' )
                    ),
                array(
                    'label'=> __('Auto Progress (Offline Courses)','wplms' ),
                    'text'=>__('Time based Course progress','wplms' ),
                    'type'=> 'switch',
                    'options'  => array('H'=>__('NO','wplms' ),'S'=>__('YES','wplms' )),
                    'style'=>'',
                    'id' => 'vibe_course_auto_progress',
                    'from'=> 'meta',
                    'default'=>'H',
                    'is_child'=>true,
                    'desc'=> __('Automatically calculate progress based on Time elapsed in Course / Total course duration.','wplms' )
                    ),
                array(
                    'label'=> __('Post Reivews (Offline Courses)','wplms' ),
                    'text'=>__('Post Course reviews from Course Home','wplms' ),
                    'type'=> 'switch',
                    'options'  => array('H'=>__('NO','wplms' ),'S'=>__('YES','wplms' )),
                    'style'=>'',
                    'id' => 'vibe_course_review',
                    'from'=> 'meta',
                    'default'=>'H',
                    'is_child'=>true,
                    'desc'=> __('Enable course subscribed students to post reviews from Course home.','wplms' )
                    ),
                
                array(
                    'label'=> __('Drip Feed','wplms' ),
                    'text'=>__('Drip Feed','wplms' ),
                    'type'=> 'conditionalswitch',
                    'hide_nodes'=> array('vibe_course_section_drip','vibe_course_drip_origin','vibe_course_drip_duration','vibe_course_drip_duration_type','vibe_drip_duration_parameter'),
                    'options'  => array('H'=>__('DISABLE','wplms' ),'S'=>__('ENABLE','wplms' )),
                    'style'=>'',
                    'id' => 'vibe_course_drip',
                    'from'=> 'meta',
                    'default'=>'H',
                    'children'=>array('vibe_course_drip_origin','vibe_course_section_drip','vibe_course_drip_duration_type','vibe_course_drip_duration'),
                    'desc'=> __('Drip Feed courses, units are released one by one after certain duration of time.','wplms' ),
                    ),
                array(
                    'label'=> __('Drip Feed Origin','wplms' ),
                    'text'=>__('Starting Point','wplms' ),
                    'type'=> 'switch',
                    'options'  => array('H'=>__('PREVIOUS UNIT','wplms' ),'S'=>__('STARTING POINT','wplms' )),
                    'style'=>'',
                    'id' => 'vibe_course_drip_origin', 
                    'from'=> 'meta',
                    'default'=>'H',
                    'is_child'=>true,
                    'desc'=> __('Drip Feed origin, count time from Previous Unit Access Time (default) OR Course starting date/time (if start date not set) .','wplms' )
                    ),
                array(
                    'label'=> __('Drip Feed Type','wplms' ),
                    'text'=>__('Section Feed','wplms' ),
                    'type'=> 'switch',
                    'options'  => array('H'=>__('UNIT','wplms' ),'S'=>__('SECTION','wplms' )),
                    'style'=>'',
                    'id' => 'vibe_course_section_drip', 
                    'from'=> 'meta',
                    'default'=>'H',
                    'is_child'=>true,
                    'desc'=> __('Drip Feed type, release units or sections.','wplms' )
                    ),
                array(
                    'label'=> __('Drip Feed Duration Type','wplms' ),
                    'text'=>__('Unit Duration','wplms' ),
                    'type'=> 'reverseconditionalswitch',
                    'hide_nodes'=> array('vibe_course_drip_duration','vibe_drip_duration_parameter'),
                    'options'  => array('H'=>__('STATIC','wplms' ),'S'=>__('UNIT DURATION','wplms' )),
                    'style'=>'',
                    'id' => 'vibe_course_drip_duration_type',
                    'from'=> 'meta',
                    'default'=>'H',
                    'is_child'=>true,
                    'desc'=> __('Time gap between adjacent Units/Sections release.','wplms' )
                    ),
                array(
                    'label'=> __('Drip Duration','wplms' ),
                    'text'=>__('Set Duration between two successive Course elements','wplms' ),
                    'type'=> 'duration',
                    'style'=>'',
                    'id' => 'vibe_course_drip_duration',
                    'from'=> 'meta',
                    'extra' => '<span data-connect="vibe_drip_duration_parameter">'.wplms_calculate_duration_time($drip_duration_parameter).'</span>',
                    'default'=> array('value'=>1,'parameter'=>86400),
                    'is_child'=>true,
                    'desc'=> sprintf(__('Enter the drip duration for the course in %s. This is the duration after which the next unit/section unlocks for the user after viewing the previous unit/section.','wplms' ),wplms_calculate_duration_time($drip_duration_parameter)),
                    ),
                array(
                    'label'=> __('Course Certificate','wplms' ),
                    'text'=>__('Course Certificate','wplms' ),
                    'type'=> 'conditionalswitch',
                    'hide_nodes'=> array('vibe_course_passing_percentage','vibe_certificate_template'),
                    'options'  => array('H'=>__('DISABLE','wplms' ),'S'=>__('ENABLE','wplms' )),
                    'style'=>'',
                    'id' => 'vibe_course_certificate',
                    'from'=> 'meta',
                    'default'=>'H',
                    'children'=>array('vibe_course_passing_percentage','vibe_certificate_template'),
                    'desc'=> __('Award Course completion Certificates to student on course completion.','wplms' ),
                    ),
                array(
                    'label'=> __('Passing Percentage','wplms' ),
                    'text'=>__('Set Certificate Percentage','wplms' ),
                    'type'=> 'number',
                    'style'=>'',
                    'id' => 'vibe_course_passing_percentage',
                    'from'=> 'meta',
                    'extra' => __(' out of 100','wplms' ),
                    'default'=> 40,
                    'is_child'=>true,
                    'desc'=> __('Any user achieving more marks (weighted average of Quizzes/assignments in course) than this gets the Course certificate.','wplms' ),
                    ),
                array(
                    'label'=> __('Certificate Template','wplms' ),
                    'text'=>__('Select Certificate template','wplms' ),
                    'type'=> 'selectcpt',
                    'cpt'=> 'certificate',
                    'style'=>'',
                    'id' => 'vibe_certificate_template',
                    'placeholder'=> __('Enter first 3 letters to search course template','wplms' ),
                    'from'=> 'meta',
                    'is_child'=>true,
                    'desc'=> __('Connect a custom Certificate template for this Course.','wplms' ),
                    ),
                array(
                    'label'=> __('Course Badge','wplms' ),
                    'text'=>__('Course Badge','wplms' ),
                    'type'=> 'conditionalswitch',
                    'hide_nodes'=> array('vibe_course_badge_percentage','vibe_course_badge_title','vibe_course_badge'),
                    'options'  => array('H'=>__('DISABLE','wplms' ),'S'=>__('ENABLE','wplms' )),
                    'style'=>'',
                    'id' => 'vibe_badge',
                    'from'=> 'meta',
                    'default'=>'H',
                    'children'=>array('vibe_course_badge_percentage','vibe_course_badge_title','vibe_course_badge'),
                    'desc'=> __('Award Excellence badges to student on course completion.','wplms' ),
                    ),
                array(
                    'label'=> __('Badge Percentage','wplms' ),
                    'text'=>__('Set Excellence Badge Percentage','wplms' ),
                    'type'=> 'number',
                    'style'=>'',
                    'id' => 'vibe_course_badge_percentage',
                    'from'=> 'meta',
                    'extra' => __(' out of 100','wplms' ),
                    'default'=>75,
                    'is_child'=>true,
                    'desc'=> __('Any user achieving more marks (weighted average of Quizzes/assignments in course) than this gets the Course Badge.','wplms' ),
                    ),
                array(
                    'label'=> __('Badge Title','wplms' ),
                    'text'=>__('Set Badge title','wplms' ),
                    'type'=> 'text',
                    'style'=>'',
                    'id' => 'vibe_course_badge_title',
                    'from'=> 'meta',
                    'is_child'=>true,
                    'default'=>__('Course Badge Title','wplms' ),
                    'desc'=> __('Course Badge Title','wplms' ),
                    ),
                array(
                    'label'=> __('Course Badge','wplms' ),
                    'text'=>__('Upload Course Badge','wplms' ),
                    'type'=> 'media',
                    'style'=>'',
                    'title'=>__('Select or Upload a Course badge.','wplms' ),
                    'button'=>__('Add Course badge.','wplms' ),
                    'id' => 'vibe_course_badge',
                    'is_child'=>true,
                    'default'=> VIBE_URL.'/images/add_image.png',
                    'from'=> 'meta',
                    'desc'=> __('Upload a course badge.','wplms' ),
                    ),
                array(
                    'label'=> __('Course Retakes','wplms' ),
                    'text'=>__('Student Course Retakes','wplms' ),
                    'type'=> 'number',
                    'style'=>'',
                    'id' => 'vibe_course_retakes',
                    'default'=> 0,
                    'from'=> 'meta',
                    'desc'=> __('Set number of times a student can re-take the course (0 to disable)','wplms' ),
                    ),
                array(
                    'label'=> __('Course Instructions','wplms' ),
                    'text'=>__('Add Course specific instructions','wplms' ),
                    'type'=> 'editor',
                    'noscript'=>true,
                    'style'=>'',
                    'id' => 'vibe_course_instructions',
                    'from'=> 'meta',
                    'desc'=> __('Course instructions are displayed when the user starts a course.','wplms' ),
                    ),
                array(
                    'label'=> __('Course Completion Message','wplms' ),
                    'text'=>__('Completion Message','wplms' ),
                    'type'=> 'editor',
                    'noscript'=>true,
                    'style'=>'',
                    'id' => 'vibe_course_message',
                    'from'=> 'meta',
                    'desc'=> __('Completion message is shown to the student when she finishes the course.','wplms' ),
                    ),
                array(
                    'label'=>__('Move to Course Components','wplms' ),
                    'id'=>'save_course_settings_button',
                    'type'=>'next_button',
                    'value'=>'1',
                    'children'=>array('back_create_course_button')
                ),
                array(
                    'label'=>__('Back to Create Course','wplms' ),
                    'id'=>'back_create_course_button',
                    'type'=>'prev_button',
                    'is_child'=>1
                ),
            ),
        ),
    'course_components'=>array(
            'icon'=> 'vicon vicon-briefcase',
            'title'=> __('COMPONENTS','wplms' ),
            'subtitle'=>  __('Course settings','wplms' ),
            'fields'=>array(
                array(
                    'label'=> __('Course Group','wplms' ),
                    'type'=> 'group',
                    'style'=>'',
                    'id' => 'vibe_group',
                    'from'=> 'meta',
                    'desc'=> __('Set a course specific group.','wplms' ),
                    'privacy_options' => apply_filters('course_forum_privacy_options',array(
                            array('value' => 'public','label'=>_x('Public','','wplms')),
                            array('value' => 'private','label'=>_x('Private','','wplms')),
                            array('value' => 'hidden','label'=>_x('Hidden','','wplms'))

                        )),
                    ),
                array(
                    'label'=> __('Course Forum','wplms' ),
                    'type'=> 'forum',
                    'style'=>'',
                    'id' => 'vibe_forum',
                    'from'=> 'meta',
                    'desc'=> __('Set a course forum.','wplms' ),
                    'privacy_options' => apply_filters('course_forum_privacy_options',array(
                            array('value' => 'public','label'=>_x('Public','','wplms')),
                            array('value' => 'private','label'=>_x('Private','','wplms')),
                            array('value' => 'hidden','label'=>_x('Hidden','','wplms'))

                        )),
                ),
                array(
                    'label'=>__('Move to Curriculum','wplms' ),
                    'id'=>'save_course_components_button',
                    'type'=>'next_button',
                    'value'=>'1',
                    'children'=>array('back_course_settings_button')
                ),
                array(
                    'label'=>__('Back to Advance Settings','wplms' ),
                    'id'=>'back_course_settings_button',
                    'type'=>'prev_button',
                    'is_child'=>1
                ),
            )
        ),
    'course_curriculum'=>array(
            'icon'=> 'vicon vicon-layers-alt',
            'title'=> __('SET CURRICULUM','wplms' ),
            'subtitle'=>  __('Add Units and Quizzes','wplms' ),
            'fields'=>array(
                array( //0th element is required to be curriculum always
                    'label'=>'',
                    'id'=>'vibe_course_curriculum',
                    'type'=> 'curriculum',
                    'style'=>'',
                    'curriculum_elements'=>array(
                        array(
                            'type'=>'section',
                            'curriculum_type'=>'label',
                            'label'=>_x('Section','curriculum_element','wplms')
                        ),
                        array( //Curriculum elments 1 is fixed for units, do not change
                            'type'=>'unit',
                            'curriculum_type'=>'post_type',
                            'label'=>_x('Unit','curriculum_element','wplms'),
                            'types'=>array(
                                array(
                                    'id'=>'video',
                                    'icon'=>'vicon vicon-video-camera',
                                    'label'=>__('Video','wplms'),
                                    'fields'=>array(
                                        array(
                                            'label'=> __('Unit title','wplms' ),
                                            'type'=> 'title',
                                            'id' => 'post_title',
                                            'from'=>'post',
                                            'value_type'=>'single',
                                            'style'=>'full',
                                            'default'=> __('Unit Name','wplms' ),
                                            'desc'=> __('This is the title of the unit which is displayed on top of every unit','wplms' )
                                            ),
                                        array(
                                            'label'=> __('Unit Tag','wplms' ),
                                            'type'=> 'taxonomy',
                                            'taxonomy'=> 'module-tag',
                                            'from'=>'taxonomy',
                                            'value_type'=>'single',
                                            'style'=>'assign_cat',
                                            'id' => 'module-tag',
                                            'default'=> __('Select a tag','wplms' ),
                                        ),
                                        array(
                                            'label'=> __('Add Unit video','wplms' ),
                                            'type'=> 'featured_video',
                                            'level'=>'video',
                                            'value_type'=>'single',
                                            'upload_title'=>__('Upload a Video','wplms' ),
                                            'desc'=>__('Select or Upload a video','wplms' ),
                                            'upload_button'=>__('Set as unit Video','wplms' ),
                                            'style'=>'small_icon',
                                            'from'=>'meta',
                                            'is_child'=>true,
                                            'id' => 'vibe_post_video',
                                            'default'=> '',
                                        ),
                                        array(
                                            'label'=> __('What is the unit about','wplms' ),
                                            'type'=> 'editor',
                                            'style'=>'',
                                            'value_type'=>'single',
                                            'id' => 'post_content',
                                            'from'=>'post',
                                            'extras' => '',
                                            'default'=> __('Enter description about the unit.','wplms' ),
                                            ),
                                        array(
                                            'label'=> __('Unit duration','wplms' ),
                                            'type'=> 'duration',
                                            'style'=>'course_duration_stick_left',
                                            'id' => 'vibe_duration',
                                            'from'=> 'meta',
                                            'default'=> array('value'=>9999,'parameter'=>86400),
                                            'from'=>'meta',
                                        ),
                                        array( 
                                            'label' => __('Free Unit','wplms'),
                                            'desc'  => __('Set Free unit, viewable to all','wplms'), 
                                            'id'    => 'vibe_free',
                                            'type'  => 'switch',
                                            'default'   => 'H',
                                            'from'=>'meta',
                                        ),
                                        array(
                                            'label' => __('Unit Forum','wplms'),
                                            'desc'  => __('Connect Forum with Unit.','wplms'),
                                            'id'    => 'vibe_forum',
                                            'type'  => 'selectcpt',
                                            'post_type' => 'forum',
                                            'std'=>0,
                                            'from'=>'meta',
                                        ),
                                        array(
                                            'label' => __('Connect Assignments','wplms'),
                                            'desc'  => __('Select an Assignment which you can connect with this Unit','wplms'),
                                            'id'    => 'vibe_assignment',
                                            'type'  => 'selectmulticpt', 
                                            'post_type' => 'assignment',
                                            'from'=>'meta',
                                        ),
                                        array(
                                            'label' => __('Attachments','wplms'),
                                            'desc'  => __('Display these attachments below units to be downloaded by students','wplms'),
                                            'id'    => 'vibe_unit_attachments', 
                                            'type'  => 'multiattachments', 
                                            'from'=>'meta',
                                        ),

                                        array(
                                            'label'=> __('Practice Questions','wplms' ),
                                            'text'=> '',
                                            'type'=> 'practice_questions',
                                            'from'=>'meta',
                                            'post_type'=>'question',
                                            'id' => 'vibe_practice_questions',
                                            'default'=> __('Select a type','wplms' ),
                                            'buttons' => array(
                                                'question_types'=>wplms_get_question_types(),
                                            )
                                        ),

                                    ),
                                ),
                                array(
                                    'id'=>'audio',
                                    'icon'=>'vicon vicon-microphone',
                                    'label'=>__('Audio','wplms'),
                                    'fields'=>array(
                                        array(
                                            'label'=> __('Unit title','wplms' ),
                                            'type'=> 'title',
                                            'id' => 'post_title',
                                            'from'=>'post',
                                            'value_type'=>'single',
                                            'style'=>'full',
                                            'default'=> __('Unit Name','wplms' ),
                                            'desc'=> __('This is the title of the unit which is displayed on top of every unit','wplms' )
                                            ),
                                        array(
                                            'label'=> __('Unit Tag','wplms' ),
                                            'type'=> 'taxonomy',
                                            'taxonomy'=> 'module-tag',
                                            'from'=>'taxonomy',
                                            'value_type'=>'single',
                                            'style'=>'assign_cat',
                                            'id' => 'module-tag',
                                            'default'=> __('Select a tag','wplms' ),
                                        ),
                                        array(
                                            'label'=> __('Add Unit Audio','wplms' ),
                                            'type'=> 'featured_audio',
                                            'level'=>'audio',
                                            'value_type'=>'single',
                                            'upload_title'=>__('Upload a audio','wplms' ),
                                            'desc'=>__('Select or Upload a audio','wplms' ),
                                            'upload_button'=>__('Set as unit audio','wplms' ),
                                            'style'=>'small_icon',
                                            'from'=>'meta',
                                            'is_child'=>true,
                                            'id' => 'vibe_post_audio',
                                            'default'=> '',
                                        ),
                                        array(
                                            'label'=> __('What is the unit about','wplms' ),
                                            'type'=> 'editor',
                                            'style'=>'',
                                            'value_type'=>'single',
                                            'id' => 'post_content',
                                            'from'=>'post',
                                            'extras' => '',
                                            'default'=> __('Enter description about the unit.','wplms' ),
                                            ),
                                        array(
                                            'label'=> __('Unit duration','wplms' ),
                                            'type'=> 'duration',
                                            'style'=>'course_duration_stick_left',
                                            'id' => 'vibe_duration',
                                            'from'=> 'meta',
                                            'default'=> array('value'=>9999,'parameter'=>86400),
                                        ),
                                        array( 
                                            'label' => __('Free Unit','wplms'),
                                            'desc'  => __('Set Free unit, viewable to all','wplms'), 
                                            'id'    => 'vibe_free',
                                            'type'  => 'switch',
                                            'default'   => 'H',
                                            'from'=>'meta',
                                        ),
                                        array(
                                            'label' => __('Unit Forum','wplms'),
                                            'desc'  => __('Connect Forum with Unit.','wplms'),
                                            'id'    => 'vibe_forum',
                                            'type'  => 'selectcpt',
                                            'post_type' => 'forum',
                                            'from'=>'meta',
                                            'std'=>0,
                                        ),
                                        array(
                                            'label' => __('Connect Assignments','wplms'),
                                            'desc'  => __('Select an Assignment which you can connect with this Unit','wplms'),
                                            'id'    => 'vibe_assignment',
                                            'type'  => 'selectmulticpt', 
                                            'from'=>'meta',
                                            'post_type' => 'assignment'
                                        ),
                                        array(
                                            'label' => __('Attachments','wplms'),
                                            'desc'  => __('Display these attachments below units to be downloaded by students','wplms'),
                                            'id'    => 'vibe_unit_attachments',
                                            'from'=>'meta', 
                                            'type'  => 'multiattachments', 
                                        ),
                                        array(
                                            'label'=> __('Practice Questions','wplms' ),
                                            'text'=> '',
                                            'type'=> 'practice_questions',
                                            'from'=>'meta',
                                            'post_type'=>'question',
                                            'id' => 'vibe_practice_questions',
                                            'default'=> __('Select a type','wplms' ),
                                            'buttons' => array(
                                                'question_types'=>wplms_get_question_types(),
                                            )
                                        ),
                                    ),
                                ),
                                array(
                                    'id'=>'multimedia',
                                    'icon'=>'vicon vicon-video-clapper',
                                    'label'=>__('MultiMedia','wplms'),
                                    'fields'=>array(
                                        array(
                                            'label'=> __('Unit title','wplms' ),
                                            'type'=> 'title',
                                            'id' => 'post_title',
                                            'from'=>'post',
                                            'value_type'=>'single',
                                            'style'=>'full',
                                            'default'=> __('Unit Name','wplms' ),
                                            'desc'=> __('This is the title of the unit which is displayed on top of every unit','wplms' )
                                            ),
                                        array(
                                            'label'=> __('Unit Tag','wplms' ),
                                            'type'=> 'taxonomy',
                                            'taxonomy'=> 'module-tag',
                                            'from'=>'taxonomy',
                                            'value_type'=>'single',
                                            'style'=>'assign_cat',
                                            'id' => 'module-tag',
                                            'default'=> __('Select a tag','wplms' ),
                                        ),
                                        array(
                                            'label'=> __('Detailed Description of the Unit','wplms' ),
                                            'type'=> 'editor',
                                            'style'=>'tag_open',
                                            'value_type'=>'single',
                                            'id' => 'post_content',
                                            'from'=>'post',
                                            'is_child'=>true,
                                            'noscript'=>true,
                                            'default'=> __('Enter full description of the unit.','wplms' ),
                                        ),
                                        array(
                                            'label'=> __('Unit duration','wplms' ),
                                            'type'=> 'duration',
                                            'style'=>'course_duration_stick_left',
                                            'id' => 'vibe_duration',
                                            'from'=> 'meta',
                                            'default'=> array('value'=>9999,'parameter'=>86400),
                                        ),
                                        array( 
                                            'label' => __('Free Unit','wplms'),
                                            'desc'  => __('Set Free unit, viewable to all','wplms'), 
                                            'id'    => 'vibe_free',
                                            'type'  => 'switch',
                                            'from'=>'meta',
                                            'default'   => 'H'
                                        ),
                                        array(
                                            'label' => __('Unit Forum','wplms'),
                                            'desc'  => __('Connect Forum with Unit.','wplms'),
                                            'id'    => 'vibe_forum',
                                            'type'  => 'selectcpt',
                                            'from'=>'meta',
                                            'post_type' => 'forum',
                                            'std'=>0,
                                        ),
                                        array(
                                            'label' => __('Connect Assignments','wplms'),
                                            'desc'  => __('Select an Assignment which you can connect with this Unit','wplms'),
                                            'id'    => 'vibe_assignment',
                                            'type'  => 'selectmulticpt',
                                            'from'=>'meta', 
                                            'post_type' => 'assignment'
                                        ),
                                        array(
                                            'label' => __('Attachments','wplms'),
                                            'desc'  => __('Display these attachments below units to be downloaded by students','wplms'),
                                            'id'    => 'vibe_unit_attachments', 
                                            'from'=>'meta',
                                            'type'  => 'multiattachments', 
                                        ),
                                        array(
                                            'label'=> __('Practice Questions','wplms' ),
                                            'text'=> '',
                                            'type'=> 'practice_questions',
                                            'from'=>'meta',
                                            'post_type'=>'question',
                                            'id' => 'vibe_practice_questions',
                                            'default'=> __('Select a type','wplms' ),
                                            'buttons' => array(
                                                'question_types'=>wplms_get_question_types(),
                                            )
                                        ),
                                    ),
                                ),
                                array(
                                    'id'=>'general',
                                    'icon'=>'vicon vicon-text',
                                    'label'=>__('Text','wplms'),
                                    'fields'=>array(
                                        array(
                                            'label'=> __('Unit title','wplms' ),
                                            'type'=> 'title',
                                            'id' => 'post_title',
                                            'from'=>'post',
                                            'value_type'=>'single',
                                            'style'=>'full',
                                            'default'=> __('Unit Name','wplms' ),
                                            'desc'=> __('This is the title of the unit which is displayed on top of every unit','wplms' )
                                            ),
                                        array(
                                            'label'=> __('Unit Tag','wplms' ),
                                            'type'=> 'taxonomy',
                                            'taxonomy'=> 'module-tag',
                                            'from'=>'taxonomy',
                                            'value_type'=>'single',
                                            'style'=>'assign_cat',
                                            'id' => 'module-tag',
                                            'default'=> __('Select a tag','wplms' ),
                                        ),
                                        array(
                                            'label'=> __('Detailed Description of the Unit','wplms' ),
                                            'type'=> 'editor',
                                            'style'=>'tag_open',
                                            'value_type'=>'single',
                                            'id' => 'post_content',
                                            'from'=>'post',
                                            'is_child'=>true,
                                            'noscript'=>true,
                                            'default'=> __('Enter full description of the unit.','wplms' ),
                                        ),
                                        array(
                                            'label'=> __('Unit duration','wplms' ),
                                            'type'=> 'duration',
                                            'style'=>'course_duration_stick_left',
                                            'id' => 'vibe_duration',
                                            'from'=> 'meta',
                                            'default'=> array('value'=>9999,'parameter'=>86400),
                                        ),
                                        array( 
                                            'label' => __('Free Unit','wplms'),
                                            'desc'  => __('Set Free unit, viewable to all','wplms'), 
                                            'id'    => 'vibe_free',
                                            'type'  => 'switch',
                                            'from'=>'meta',
                                            'default'   => 'H'
                                        ),
                                        array(
                                            'label' => __('Unit Forum','wplms'),
                                            'desc'  => __('Connect Forum with Unit.','wplms'),
                                            'id'    => 'vibe_forum',
                                            'type'  => 'selectcpt',
                                            'from'=>'meta',
                                            'post_type' => 'forum',
                                            'std'=>0,
                                        ),
                                        array(
                                            'label' => __('Connect Assignments','wplms'),
                                            'desc'  => __('Select an Assignment which you can connect with this Unit','wplms'),
                                            'id'    => 'vibe_assignment',
                                            'type'  => 'selectmulticpt', 
                                            'from'=>'meta',
                                            'post_type' => 'assignment'
                                        ),
                                        array(
                                            'label' => __('Attachments','wplms'),
                                            'desc'  => __('Display these attachments below units to be downloaded by students','wplms'),
                                            'id'    => 'vibe_unit_attachments', 
                                            'from'=>'meta',
                                            'type'  => 'multiattachments', 
                                        ),
                                        array(
                                            'label'=> __('Practice Questions','wplms' ),
                                            'text'=> '',
                                            'type'=> 'practice_questions',
                                            'from'=>'meta',
                                            'post_type'=>'question',
                                            'id' => 'vibe_practice_questions',
                                            'default'=> __('Select a type','wplms' ),
                                            'buttons' => array(
                                                'question_types'=>wplms_get_question_types(),
                                            )
                                        ),
                                    ),
                                ),
                                array(
                                    'id'=>'upload',
                                    'icon'=>'vicon vicon-upload',
                                    'label'=>__('Upload Package','wplms'),
                                    'fields'=>array(
                                        array(
                                            'label'=> __('Unit title','wplms' ),
                                            'type'=> 'title',
                                            'id' => 'post_title',
                                            'from'=>'post',
                                            'value_type'=>'single',
                                            'style'=>'full',
                                            'default'=> __('Unit Name','wplms' ),
                                            'desc'=> __('This is the title of the unit which is displayed on top of every unit','wplms' )
                                            ),
                                        array(
                                            'label'=> __('Unit Tag','wplms' ),
                                            'type'=> 'taxonomy',
                                            'taxonomy'=> 'module-tag',
                                            'from'=>'taxonomy',
                                            'value_type'=>'single',
                                            'style'=>'assign_cat',
                                            'id' => 'module-tag',
                                            'default'=> __('Select a tag','wplms' ),
                                        ),
                                        array(
                                            'label'=> __('Add Unit Package','wplms' ),
                                            'type'=> 'upload_package',
                                            'value_type'=>'single',
                                            'style'=>'small_icon',
                                            'from'=>'meta',
                                            'is_child'=>true,
                                            'id' => 'vibe_upload_package',
                                            'default'=> '',
                                            'upload_elements'=>array(
                                                array(
                                                    'icon'=> 'vicon vicon-upload',
                                                    'type'=>'1.1',
                                                    'label'=>__('SCORM 1.2 Package','curriculum_element','wplms')
                                                ),
                                                array(
                                                    'icon'=> 'vicon vicon-cloud-up',
                                                    'type'=>'xapi',
                                                    'label'=>__('TinCan Package','curriculum_element','wplms')
                                                ),
                                                array(
                                                    'icon'=> 'vicon vicon-vector',
                                                    'type'=>'html',
                                                    'label'=>__('HTML Package','curriculum_element','wplms')
                                                ),
                                            ),
                                        ),
                                        array(
                                            'label'=> __('Unit duration','wplms' ),
                                            'type'=> 'duration',
                                            'style'=>'course_duration_stick_left',
                                            'id' => 'vibe_duration',
                                            'from'=> 'meta',
                                            'default'=> array('value'=>9999,'parameter'=>86400),
                                        ),
                                        array( 
                                            'label' => __('Free Unit','wplms'),
                                            'desc'  => __('Set Free unit, viewable to all','wplms'), 
                                            'id'    => 'vibe_free',
                                            'type'  => 'switch',
                                            'from'=>'meta',
                                            'default'   => 'H'
                                        ),
                                        array(
                                            'label' => __('Unit Forum','wplms'),
                                            'desc'  => __('Connect Forum with Unit.','wplms'),
                                            'id'    => 'vibe_forum',
                                            'type'  => 'selectcpt',
                                            'from'=>'meta',
                                            'post_type' => 'forum',
                                            'std'=>0,
                                        ),
                                        array(
                                            'label' => __('Connect Assignments','wplms'),
                                            'desc'  => __('Select an Assignment which you can connect with this Unit','wplms'),
                                            'id'    => 'vibe_assignment',
                                            'type'  => 'selectmulticpt', 
                                            'from'=>'meta',
                                            'post_type' => 'assignment'
                                        ),
                                        array(
                                            'label' => __('Attachments','wplms'),
                                            'desc'  => __('Display these attachments below units to be downloaded by students','wplms'),
                                            'id'    => 'vibe_unit_attachments', 
                                            'from'=>'meta',
                                            'type'  => 'multiattachments', 
                                        ),
                                        array(
                                            'label'=> __('Practice Questions','wplms' ),
                                            'text'=> '',
                                            'type'=> 'practice_questions',
                                            'from'=>'meta',
                                            'post_type'=>'question',
                                            'id' => 'vibe_practice_questions',
                                            'default'=> __('Select a type','wplms' ),
                                            'buttons' => array(
                                                'question_types'=>wplms_get_question_types(),
                                            )
                                        ),
                                    ),
                                ),
                            ) 
                        ),
                        array(
                            'type'=>'quiz',
                            'curriculum_type'=>'post_type',
                            'label'=>_x('Quiz','curriculum_element','wplms'),
                            'types'=>array(
                                array(
                                    'id'=>'static',
                                    'icon'=>'vicon vicon-exchange-vertical',
                                    'label'=>__('Simple','wplms'),
                                    'fields'=>array(
                                        array(
                                            'label'=> __('Quiz title','wplms' ),
                                            'type'=> 'title',
                                            'id' => 'post_title',
                                            'from'=>'post',
                                            'value_type'=>'single',
                                            'style'=>'full',
                                            'default'=> __('Quiz Title','wplms' ),
                                            'desc'=> __('This is the title of the unit which is displayed on top of every unit','wplms' )
                                            ),
                                        array(
                                            'label'=> __('Quiz type','wplms' ),
                                            'type'=> 'taxonomy',
                                            'taxonomy'=> 'quiz-type',
                                            'from'=>'taxonomy',
                                            'value_type'=>'single',
                                            'style'=>'assign_cat',
                                            'id' => 'quiz-type',
                                            'default'=> __('Select a type','wplms' ),
                                        ),
                                        array(
                                            'label'=> __('What is the quiz about','wplms' ),
                                            'type'=> 'editor',
                                            'style'=>'',
                                            'value_type'=>'single',
                                            'id' => 'post_content',
                                            'from'=>'post',
                                            'extras' => '',
                                            'default'=> __('Enter a short description about the quiz.','wplms' ),
                                        ),
                                        array(
                                            'label' => __('Course','wplms'), // <label>
                                            'id'    => 'vibe_quiz_course',
                                            'type'  => 'selectcpt', // type of field
                                            'post_type' => 'course',
                                            'from'=>'meta',
                                            'post_status'=>array('publish','draft'),
                                            'desc'=> __('Connecting a quiz with a course would force the quiz to be available to users who have taken the course.','wplms'),
                                        ),
                                        array( // Text Input
                                            'label' => __('Quiz Duration','wplms'), // <label>
                                            'desc'  =>__(' Enables Timer & auto submits on expire. 9999 to disable.','wplms'), // description
                                            'id'    => 'vibe_duration', // field id and name
                                            'type'  => 'duration',
                                            'from'=>'meta',
                                            'default'=> array('value'=>5,'parameter'=>60),
                                        ),
                                        array(
                                            'label' => __('Auto Evaluate Results','wplms'),
                                            'desc'  => __('Evaluate results as soon as quiz is complete. (* No Large text questions ), Diable for manual evaluate','wplms'),
                                            'id'    => 'vibe_quiz_auto_evaluate',
                                            'type'  => 'switch',
                                            'default'   => 'H',
                                            'from'=>'meta',
                                        ), 
                                        array( // Text Input
                                            'label' => __('Number of questions per page','wplms'), // <label>
                                            'desc'  => __('Number of questions. to be loaded on one screen in quiz','wplms'), // description
                                            'id'    => 'vibe_question_number_react', // field id and name
                                            'type'  => 'number', // type of field
                                            'from'=>'meta',
                                            'default'   => 1
                                        ),
                                        array(
                                            'label' => __('Number of Extra Quiz Retakes','wplms'),
                                            'desc'  => __('Student can reset and start the quiz all over again. Number of Extra retakes a student can take.','wplms'),
                                            'id'    => 'vibe_quiz_retakes',
                                            'type'  => 'number',
                                            'from'=>'meta',
                                            'default'   => 0
                                        ), 
                                        array(
                                            'label' => __('Post Quiz Message','wplms'),
                                            'desc'  => __('This message is shown to users when they submit the quiz','wplms'),
                                            'id'    => 'vibe_quiz_message',
                                            'type'  => 'editor',
                                            'from'=>'meta',
                                            'default'   => 'Thank you for Submitting the Quiz. Check Results in your Profile.'
                                        ),
                                        array(
                                            'label' => __('Show results after submission','wplms'),
                                            'desc'  => __('This will show the quiz results right after submitting the quiz below quiz completion message.','wplms'), 
                                            'id'    => 'vibe_results_after_quiz_message',
                                            'type'  => 'switch',
                                            'from'=>'meta',
                                            'default'   => 'H'
                                        ),
                                        array( // Text Input
                                            'label' => __('Add Check Answer Switch','wplms'), 
                                            'desc'  => __('Instantly check answer answer when question is marked','wplms'), 
                                            'id'    => 'vibe_quiz_check_answer',
                                            'type'  => 'switch',
                                            'from'=>'meta',
                                            'default'   => 'H'
                                        ),
                                        array(
                                            'label' => __('Randomize Quiz Questions','wplms'),
                                            'desc'  => __('Random Question sequence for every quiz','wplms'),
                                            'id'    => 'vibe_quiz_random', // field id and name
                                            'type'  => 'switch',
                                            'from'=>'meta',
                                            'default'   => 'H'
                                        ),
                                        array(
                                            'label' => __('Enable access to quiz to non logged in users','wplms'),
                                            'desc'  => __('Non logged in users can take quiz?','wplms'),
                                            'id'    => 'vibe_non_loggedin_quiz', // field id and name
                                            'type'  => 'switch',
                                            'from'=>'meta',
                                            'default'   => 'H'
                                        ),
                                        array(
                                            'label' => __('Show submit button on last question','wplms'),
                                            'desc'  => __('Hide submit button until user reaches to last question?','wplms'),
                                            'id'    => 'vibe_hide_submit_button', // field id and name
                                            'type'  => 'switch',
                                            'from'=>'meta',
                                            'default'   => 'S'
                                        ),
                                        array(
                                            'label'=> __('Questions','wplms' ),
                                            'text'=> __('Questions set ','wplms' ),
                                            'type'=> 'quiz_questions',
                                            'from'=>'meta',
                                            'post_type'=>'question',
                                            'id' => 'vibe_quiz_questions',
                                            'default'=> __('Select a type','wplms' ),
                                            'buttons' => array(
                                                'question_types'=>wplms_get_question_types(),
                                            )
                                        ),
                                    ),
                                ),
                                array(
                                    'id'=>'dynamic',
                                    'icon'=>'vicon vicon-control-shuffle',
                                    'label'=>__('Dynamic','wplms'),
                                    'fields'=>array(
                                        array(
                                            'label'=> __('Quiz title','wplms' ),
                                            'type'=> 'title',
                                            'id' => 'post_title',
                                            'from'=>'post',
                                            'value_type'=>'single',
                                            'style'=>'full',
                                            'default'=> __('Quiz Name','wplms' ),
                                            'desc'=> __('This is the title of the unit which is displayed on top of every unit','wplms' )
                                            ),
                                        array(
                                            'label'=> __('Quiz type','wplms' ),
                                            'type'=> 'taxonomy',
                                            'taxonomy'=> 'quiz-type',
                                            'from'=>'taxonomy',
                                            'value_type'=>'single',
                                            'style'=>'assign_cat',
                                            'id' => 'quiz-type',
                                            'default'=> __('Select a type','wplms' ),
                                        ),
                                        array(
                                            'label'=> __('What is the quiz about','wplms' ),
                                            'type'=> 'editor',
                                            'style'=>'',
                                            'value_type'=>'single',
                                            'id' => 'post_content',
                                            'from'=>'post',
                                            'extras' => '',
                                            'default'=> __('Enter a short description about the quiz.','wplms' ),
                                        ),
                                         array(
                                            'label' => __('Course','wplms'), // <label>
                                            'id'    => 'vibe_quiz_course',
                                            'type'  => 'selectcpt', // type of field
                                            'post_type' => 'course',
                                            'from'=>'meta',
                                            'post_status'=>array('publish','draft'),
                                            'desc'=> __('Connecting a quiz with a course would force the quiz to be available to users who have taken the course.','wplms'),
                                        ),
                                        array( // Text Input
                                            'label' => __('Quiz Duration','wplms'), // <label>
                                            'desc'  =>__(' Enables Timer & auto submits on expire. 9999 to disable.','wplms'), // description
                                            'id'    => 'vibe_duration', // field id and name
                                            'type'  => 'duration',
                                            'from'=>'meta',
                                            'default'=> array('value'=>5,'parameter'=>60),
                                        ),
                                        array(
                                            'label' => __('Auto Evaluate Results','wplms'),
                                            'desc'  => __('Evaluate results as soon as quiz is complete. (* No Large text questions ), Diable for manual evaluate','wplms'),
                                            'id'    => 'vibe_quiz_auto_evaluate',
                                            'type'  => 'switch',
                                            'from'=>'meta',
                                            'default'   => 'H'
                                        ), 
                                        array( // Text Input
                                            'label' => __('Number of questions per page','wplms'), // <label>
                                            'desc'  => __('Number of questions. to be loaded on one screen in quiz','wplms'), // description
                                            'id'    => 'vibe_question_number_react', // field id and name
                                            'type'  => 'number', // type of field
                                            'from'=>'meta',
                                            'default'   => 1
                                        ),
                                        array(
                                            'label' => __('Number of Extra Quiz Retakes','wplms'),
                                            'desc'  => __('Student can reset and start the quiz all over again. Number of Extra retakes a student can take.','wplms'),
                                            'id'    => 'vibe_quiz_retakes',
                                            'from'=>'meta',
                                            'type'  => 'number',
                                            'std'   => 0
                                        ), 
                                        array(
                                            'label' => __('Post Quiz Message','wplms'),
                                            'desc'  => __('This message is shown to users when they submit the quiz','wplms'),
                                            'id'    => 'vibe_quiz_message',
                                            'type'  => 'editor',
                                            'from'=>'meta',
                                            'default'   => 'Thank you for Submitting the Quiz. Check Results in your Profile.'
                                        ),
                                        array(
                                            'label' => __('Show results after submission','wplms'),
                                            'desc'  => __('This will show the quiz results right after submitting the quiz below quiz completion message.','wplms'), 
                                            'id'    => 'vibe_results_after_quiz_message',
                                            'from'=>'meta',
                                            'type'  => 'switch',
                                            'default'   => 'H'
                                        ),
                                        array( // Text Input
                                            'label' => __('Add Check Answer Switch','wplms'), 
                                            'desc'  => __('Instantly check answer answer when question is marked','wplms'), 
                                            'id'    => 'vibe_quiz_check_answer',
                                            'from'=>'meta',
                                            'type'  => 'switch',
                                            'default'   => 'H'
                                        ),
                                        array(
                                            'label' => __('Enable access to quiz to non logged in users','wplms'),
                                            'desc'  => __('Non logged in users can take quiz?','wplms'),
                                            'id'    => 'vibe_non_loggedin_quiz', // field id and name
                                            'type'  => 'switch',
                                            'from'=>'meta',
                                            'default'   => 'H'
                                        ),
                                        array(
                                            'label' => __('Show submit button on last question','wplms'),
                                            'desc'  => __('Hide submit button until user reaches to last question?','wplms'),
                                            'id'    => 'vibe_hide_submit_button', // field id and name
                                            'type'  => 'switch',
                                            'from'=>'meta',
                                            'default'   => 'S'
                                        ),
                                        array(
                                            'label'=> __('Set Questions','wplms' ),
                                            'type'=> 'dynamic_quiz_questions',
                                            'from'=>'meta',
                                            'id' => 'vibe_quiz_tags',
                                            'default'=> __('Select question categories type','wplms' ),
                                        ),
                                    )
                                ),
                                array(
                                    'id'=>'scorm',
                                    'icon'=>'vicon vicon-upload',
                                    'label'=>__('Scorm','wplms'),
                                    'fields'=>array(
                                        array(
                                            'label'=> __('Quiz title','wplms' ),
                                            'type'=> 'title',
                                            'id' => 'post_title',
                                            'from'=>'post',
                                            'value_type'=>'single',
                                            'style'=>'full',
                                            'default'=> __('Quiz Title','wplms' ),
                                            'desc'=> __('This is the title of the unit which is displayed on top of every unit','wplms' )
                                            ),
                                        array(
                                            'label'=> __('Quiz type','wplms' ),
                                            'type'=> 'taxonomy',
                                            'taxonomy'=> 'quiz-type',
                                            'from'=>'taxonomy',
                                            'value_type'=>'single',
                                            'style'=>'assign_cat',
                                            'id' => 'quiz-type',
                                            'default'=> __('Select a type','wplms' ),
                                        ),
                                        array(
                                            'label'=> __('Add Quiz Package','wplms' ),
                                            'type'=> 'upload_package',
                                            'value_type'=>'single',
                                            'style'=>'small_icon',
                                            'from'=>'meta',
                                            'is_child'=>true,
                                            'id' => 'vibe_upload_package',
                                            'default'=> '',
                                            'upload_elements'=>array(
                                                array(
                                                    'icon'=> 'vicon vicon-upload',
                                                    'type'=>'1.1',
                                                    'label'=>__('SCORM 1.2 Package','curriculum_element','wplms')
                                                ),
                                                /*sooner it will be bro keep watching
                                                array(
                                                    'icon'=> 'vicon vicon-cloud-up',
                                                    'type'=>'xapi',
                                                    'label'=>__('TinCan Package','curriculum_element','wplms')
                                                ),*/
                                            ),
                                        ),
                                        array(
                                            'label' => __('Course','wplms'), // <label>
                                            'id'    => 'vibe_quiz_course',
                                            'type'  => 'selectcpt', // type of field
                                            'post_type' => 'course',
                                            'from'=>'meta',
                                            'post_status'=>array('publish','draft'),
                                            'desc'=> __('Connecting a quiz with a course would force the quiz to be available to users who have taken the course.','wplms'),
                                        ),
                                        
                                    ),
                                ),
                                 
                            ),
                        ),
                        array(
                            'type'=>'assignment',
                            'curriculum_type'=>'post_type',
                            'label'=>_x('Assignment','curriculum_element','wplms'),
                            'types'=>array(
                                array(
                                    'id'=>'textarea',
                                    'icon'=>'vicon vicon-text',
                                    'label'=>__('Simple','wplms'),
                                    'fields'=>array(
                                        array(
                                            'label'=> __('Assignment title','wplms' ),
                                            'type'=> 'title',
                                            'id' => 'post_title',
                                            'from'=>'post',
                                            'value_type'=>'single',
                                            'style'=>'full',
                                            'default'=> __('Assignment Name','wplms' ),
                                            'desc'=> __('This is the title of the unit which is displayed on top of  the assignment','wplms' )
                                            ),
                                        array(
                                            'label'=> __('Assignment type','wplms' ),
                                            'type'=> 'taxonomy',
                                            'taxonomy'=> 'assignment-type',
                                            'from'=>'taxonomy',
                                            'value_type'=>'single',
                                            'style'=>'assign_cat',
                                            'id' => 'assignment-type',
                                            'desc'=> __('Select assignment type','wplms' ),
                                        ),
                                        array(
                                            'label'=> __('Assignment Marks','wplms' ),
                                            'type'=> 'number',
                                            'from'=>'meta',
                                            'value_type'=>'single',
                                            'id' => 'vibe_assignment_marks',
                                            'default'=> __('Set Maximum Score','wplms' ),
                                        ),
                                        array(
                                            'label'=> __('Assignment Duration','wplms' ),
                                            'type'=> 'duration',
                                            'from'=>'meta',
                                            'value_type'=>'single',
                                            'id' => 'vibe_assignment_duration',
                                            'default'=> array('value'=>10,'parameter'=>86400),
                                            'desc'=> __('Set maximum assignment duration','wplms' ),
                                        ),
                                        array( // Text Input
                                            'label' => __('Include in Course','wplms'), // <label>
                                            'desc'  => __('Assignments marks will be shown/used in course evaluation','wplms'), // description
                                            'id'    => 'vibe_assignment_course', // field id and name
                                            'value_type'=>'single',
                                            'from'=>'meta',
                                            'type'  => 'selectcpt', // type of field
                                            'post_type' => 'course',
                                            'cpt' => 'course',
                                            'placeholder'=> __('Enter first 3 letters to search course ','wplms' ),
                                        ),
                                        array(
                                            'label'=> __('Include in Evaluation','wplms' ),
                                            'type'=> 'switch',
                                            'from'=>'meta',
                                            'value_type'=>'single',
                                            'id' => 'vibe_assignment_evaluation',
                                            'default'=>'H',
                                            'desc'=> __('Free assignment or included in Course evaluation','wplms' ),
                                        ),
                                        array(
                                            'label'=> __('Assignment statement','wplms' ),
                                            'type'=> 'editor',
                                            'style'=>'tag_open',
                                            'value_type'=>'single',
                                            'id' => 'post_content',
                                            'from'=>'post',
                                            'is_child'=>true,
                                            'noscript'=>true,
                                            'desc'=> __('Enter full statement of the assignment.','wplms' ),
                                        ),
                                    ),
                                ),
                                array(
                                    'id'=>'upload',
                                    'icon'=>'vicon vicon-upload',
                                    'label'=>__('Upload','wplms'),
                                    'fields'=>array(
                                        array(
                                            'label'=> __('Assignment title','wplms' ),
                                            'type'=> 'title',
                                            'id' => 'post_title',
                                            'from'=>'post',
                                            'value_type'=>'single',
                                            'style'=>'full',
                                            'default'=> __('Unit Name','wplms' ),
                                            'desc'=> __('This is the title of the unit which is displayed on top of the assignment','wplms' )
                                            ),
                                        array(
                                            'label'=> __('Assignment type','wplms' ),
                                            'type'=> 'taxonomy',
                                            'taxonomy'=> 'assignment-type',
                                            'from'=>'taxonomy',
                                            'value_type'=>'single',
                                            'style'=>'assign_cat',
                                            'id' => 'assignment-type',
                                            'desc'=> __('Select Assignment type','wplms' ),
                                        ),

                                        array(
                                            'label'=> __('Assignment Marks','wplms' ),
                                            'type'=> 'number',
                                            'from'=>'meta',
                                            'value_type'=>'single',
                                            'id' => 'vibe_assignment_marks',
                                            'desc'=> __('Set Maximum Score','wplms' ),
                                        ),
                                        array(
                                            'label'=> __('Assignment Duration','wplms' ),
                                            'type'=> 'duration',
                                            'from'=>'meta',
                                            'value_type'=>'single',
                                            'id' => 'vibe_assignment_duration',
                                            'default'=> array('value'=>86400,'parameter'=>86400),
                                            'desc'=> __('Set maximum assignment duration','wplms' ),
                                        ),
                                        array( // Text Input
                                            'label' => __('Include in Course','wplms'), // <label>
                                            'desc'  => __('Assignments marks will be shown/used in course evaluation','wplms'), // description
                                            'id'    => 'vibe_assignment_course', // field id and name
                                            'value_type'=>'single',
                                            'from'=>'meta',
                                            'type'  => 'selectcpt', // type of field
                                            'post_type' => 'course',
                                            'cpt' => 'course',
                                            'placeholder'=> __('Enter first 3 letters to search course ','wplms' ),
                                        ),
                                        array(
                                            'label'=> __('Include in Evaluation','wplms' ),
                                            'type'=> 'switch',
                                            'from'=>'meta',
                                            'value_type'=>'single',
                                            'id' => 'vibe_assignment_evaluation',
                                            'default'=>'H',
                                            'desc'=> __('Free assignment or included in Course evaluation','wplms' ),
                                        ),
                                        array( 
                                            'label' => __('Attachment Type','wplms'), 
                                            'text'  => __('Select attachment types ','wplms'),
                                            'desc'  => __('Only specified attachment types can be uploaded','wplms'),
                                            'id'    => 'vibe_attachment_type', 
                                            'type'  => 'multiselect',
                                            'from'  => 'meta',
                                            'options' => array(
                                                array('value'=> 'JPG','label' =>'JPG'),
                                                array('value'=> 'GIF','label' =>'GIF'),
                                                array('value'=> 'PNG','label' =>'PNG'),
                                                array('value'=> 'PDF','label' =>'PDF'),
                                                array('value'=>'PSD','label'=>'PSD'),
                                                array('value'=> 'DOC','label' =>'DOC'),
                                                array('value'=> 'DOCX','label' => 'DOCX'),
                                                array('value'=> 'PPT','label' =>'PPT'),
                                                array('value'=> 'PPTX','label' => 'PPTX'),
                                                array('value'=> 'PPS','label' =>'PPS'),
                                                array('value'=> 'PPSX','label' => 'PPSX'),
                                                array('value'=> 'ODT','label' =>'ODT'),
                                                array('value'=> 'XLS','label' =>'XLS'),
                                                array('value'=> 'XLSX','label' => 'XLSX'),
                                                array('value'=> 'MP3','label' =>'MP3'),
                                                array('value'=> 'M4A','label' =>'M4A'),
                                                array('value'=> 'OGG','label' =>'OGG'),
                                                array('value'=> 'WAV','label' =>'WAV'),
                                                array('value'=> 'WMA','label' =>'WMA'),
                                                array('value'=> 'MP4','label' =>'MP4'),
                                                array('value'=> 'M4V','label' =>'M4V'),
                                                array('value'=> 'MOV','label' =>'MOV'),
                                                array('value'=> 'WMV','label' =>'WMV'),
                                                array('value'=> 'AVI','label' =>'AVI'),
                                                array('value'=> 'MPG','label' =>'MPG'),
                                                array('value'=> 'OGV','label' =>'OGV'),
                                                array('value'=> '3GP','label' =>'3GP'),
                                                array('value'=> '3G2','label' =>'3G2'),
                                                array('value'=> 'FLV','label' =>'FLV'),
                                                array('value'=> 'WEBM','label' =>'WEBM'),
                                                array('value'=> 'APK','label' =>'APK '),
                                                array('value'=> 'RAR','label' =>'RAR'),
                                                array('value'=> 'ZIP','label' =>'ZIP'),
                                            ),
                                            'std'   => 'single'
                                        ),
                                        array(
                                            'label' => __('Attachment Size (in MB)','wplms'), 
                                            'text' => __('Attachment Size (in MB)','wplms'), 
                                            'desc'  => __('Set Maximum Attachment size for upload ( set less than ','wplms' ).$upload_mb.' MB)',
                                            'id'    => 'vibe_attachment_size',
                                            'type'  => 'number', 
                                            'from'  => 'meta',
                                            'default' => '2'
                                        ),
                                        array(
                                            'label'=> __('Assignment statement','wplms' ),
                                            'type'=> 'editor',
                                            'style'=>'tag_open',
                                            'value_type'=>'single',
                                            'id' => 'post_content',
                                            'from'=>'post',
                                            'is_child'=>true,
                                            'noscript'=>true,
                                            'desc'=> __('Enter full statement of the assignment.','wplms' ),
                                        ),
                                    )
                                )
                            ),
                        ),
                    )
                ),
                array(
                    'label'=>'',
                    'type'=> 'upload_package',
                    'id'=>'vibe_course_package',
                    'from'=>'meta',
                    'style'=>'',
                    'upload_elements'=>array(
                        array(
                            'icon'=> 'vicon vicon-upload',
                            'type'=>'1.1',
                            'label'=>__('SCORM 1.2 Package','curriculum_element','wplms')
                        ),
                        array(
                            'icon'=> 'vicon vicon-cloud-up',
                            'type'=>'xapi',
                            'label'=>__('TinCan Package','curriculum_element','wplms')
                        ),
                        array(
                            'icon'=> 'vicon vicon-vector',
                            'type'=>'html',
                            'label'=>__('HTML Package','curriculum_element','wplms')
                        ),
                    ),
                ),
                array(
                    'label'=>__('Move to Accessibility','wplms' ),
                    'id'=>'save_curriculum_button',
                    'type'=>'next_button',
                    'value'=>'1',
                    'children'=>array('back_course_components_button')
                ),
                array(
                    'label'=>__('Back to Components','wplms' ),
                    'id'=>'back_course_components_button',
                    'type'=>'prev_button',
                    'is_child'=>1
                ),
            ),
        ),
    'course_pricing' => array(
            'icon'=> 'vicon vicon-key',
            'title'=>  __('ACCESSIBILITY','wplms' ),
            'subtitle'=>  __('Set Price for Course','wplms' ),
            'fields'=>array(
                array(
                    'label'=> __('Free Course','wplms' ),
                    'text'=>__('Anyone can access this course','wplms' ),
                    'type'=> 'switch',
                    'options'  => array('H'=>__('No','wplms' ),'S'=>__('Yes','wplms' )),
                    'style'=>'',
                    'id' => 'vibe_course_free',
                    'from'=> 'meta',
                    'default'=>'H',
                    'desc'=> __('Free Course any member can pursue.','wplms' )
                ),
                array(
                    'label'=> __('Product ','wplms' ),
                    'text'=>__('Course Price','wplms' ),
                    'cpt'=>'product',
                    'type'=> 'selectproduct',
                    'style'=>'',
                    'id' => 'vibe_product',
                    'from'=> 'meta',
                    'default'=>'',
                    'fields'=>array(
                        array(
                            'label'=> __('Price','wplms' ),
                            'type'=> 'text',
                            'style'=>'',
                            'id' => '_regular_price',
                            'from'=> 'meta',
                            'default'=>'',
                            'desc'=> __('Price of Course.','wplms' ),
                            'value' => $price,
                        ),
                        array(
                            'label'=> __('Sale Price','wplms' ),
                            'type'=> 'text',
                            'style'=>'',
                            'id' => '_sale_price',
                            'from'=> 'meta',
                            'default'=>'',
                            'desc'=> __('Set Sale Price of Course.','wplms' ),
                            'value' => $sale_price,
                        ),
                        array(
                            'label'=> __('Subscription','wplms' ),
                            'type'=> 'switch',
                            'hide_nodes'=> array('vibe_product_duration'),
                            'options'  => array('H'=>__('FullCourse Access','wplms' ),'S'=>__('Subscription Access','wplms' )),
                            'style'=>'',
                            'id' => 'vibe_subscription',
                            'from'=> 'meta',
                            'default'=>'H',
                            'children'=>array('vibe_product_duration'),
                            'desc'=> __('Subscription to this course expires after set time.','wplms' ),
                            'value' => $vibe_subscription,
                        ),
                        array(
                            'label'=> __('Subscription Duration','wplms' ),
                            'type'=> 'duration',
                            'style'=>'',
                            'is_child'=>'1',
                            'id' => 'vibe_product_duration',
                            'from'=> 'meta',
                            'default'=> array('value'=>9999,'parameter'=>86400),
                            'value' => array('value'=>$vibe_product_duration,'parameter'=>$product_duration_parameter)
                        ),
                    ),
                    'desc'=> __('Give course access to users who purchase this product','wplms' ),
                ),
                array( // Text Input
                    'label' => __('Enable Partial Free Course','wplms'),  // <label>
                    'text'  =>  __('Allows users to start the course for free, but can only see allowed items for free.','wplms'),
                    'desc'  => __('Enroll user to course to access only first section for free.They have to pay for later sections.','wplms'),// description// description
                    'id'    => 'vibe_partial_free_course', // field id and name
                    'type'  => 'conditionalswitch', // type of field
                    'from'=> 'meta',
                    'options'  => array('H'=>__('DISABLE','wplms' ),'S'=>__('ENABLE','wplms' )),
                    'default'   => 'H',
                    'children'=>array('vibe_partial_units'),
                    'hide_nodes'=> array('vibe_partial_units'),
                ),
                array( // Text Input
                    'label' => __('Select items for partial free course','wplms'),  // <label>
                    'text'  =>  __('Select units/sections to be free for partial free course','wplms'),
                    'style'=>'',
                    'desc'  => __('Selected unit will be available for free to student','wplms'),// description// description
                    'from'=> 'meta',
                    'id'    => 'vibe_partial_units', // field id and name
                    'type'  => 'vibe_partial_units', // type of field
                    'is_child' =>true
                ),
                
                array( // Text Input
                    'label' => __('Apply for Course','wplms'), // <label>
                    'text'  => __('Invite Student applications for Course','wplms'),
                    'desc'  => __('Students are required to Apply for course and instructor would manually approve them to course. Do not enable "Free" course with this setting.','wplms'), // description
                    'id'    => 'vibe_course_apply', // field id and name
                    'type'  => 'switch', // type of field
                    'from'=> 'meta',
                    'options'  => array('H'=>__('No','wplms' ),'S'=>__('Yes','wplms' )),
                    'default'   => 'H'
                ),

                array(
                    'label'=>__('Publish','wplms' ),
                    'id'=>'publish_course',
                    'type'=>'publish_button',
                    'value'=>'1',
                    'children'=>array('back_course_curriculum_button')
                ),
                array(
                    'label'=>__('Back to Accessibility','wplms' ),
                    'id'=>'back_course_curriculum_button',
                    'type'=>'prev_button',
                    'is_child'=>1
                )
            ),
        )
    ),$course_id,$user_id);

    foreach ($course_tabs as $key => $value) {
        if(!empty($value) && !empty($value['fields'])){
            foreach ($value['fields'] as $k => $set) {
                if(empty($set)){
                    array_splice($course_tabs[$key]['fields'],$k,1);
                }
            }
        }
    }
    return $course_tabs;
}