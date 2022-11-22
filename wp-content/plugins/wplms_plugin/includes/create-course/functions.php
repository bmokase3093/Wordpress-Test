<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class WPLMS_Functions{

    public $posts=[];
    public static $instance;
    public static function init(){
        if ( is_null( self::$instance ) )
            self::$instance = new WPLMS_Functions();
        return self::$instance;
    }

    function get_post($id){
        if(is_array($id)){
            return;
        }
        if(empty($this->posts[$id])){
            $this->posts[$id] = (Array)get_post($id);
        }
        return $this->posts[$id];
    }

    function get_post_meta($id,$key,$single){
        if(empty($this->meta[$id][$key])){
            $this->meta[$id][$key] = get_post_meta($id,$key,$single);
        }
        return $this->meta[$id][$key];
    }

    function get_the_title($id){
        if(empty($this->title[$id])){
            $this->title[$id] = get_the_title($id);
        }
        return $this->title[$id];
    }
    function get_permalink($id){
        if(empty($this->permalink[$id])){
            $this->permalink[$id] = get_permalink($id);
        }
        return $this->permalink[$id];
    }
}

/*
IN CASE PHP CORE FUNCTION IS NOT DEFINED //Happened in TEST CASE 1
 */
if(!function_exists('wplms_getallheaders')){
    function wplms_getallheaders(){ 
        $headers = array (); 
        if (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION']) && !isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $_SERVER['HTTP_AUTHORIZATION'] = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
        }
        foreach ($_SERVER as $name => $value) 
        { 
           if (substr($name, 0, 5) == 'HTTP_') 
           { 
               $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value; 
           } 
        } 
       return $headers; 
    }
}
if(!function_exists('unserialize_recursive')){
    function unserialize_recursive($val) {
        //$pattern = "/.*\{(.*)\}/";
        if(is_serialized($val)){
            $val = trim($val);
            $ret = unserialize($val);
            if (is_array($ret)) {
                foreach($ret as &$r) $r = unserialize_recursive($r); 
            }
            return $ret; 
        } elseif (is_array($val)) {
            foreach($val as &$r) $r = unserialize_recursive($r);
            return $val;
        } else { return $val; } 
    }
}
function wplms_videoType($url) {
    if (strpos($url, 'youtube') > 0) {
        return 'youtube';
    } elseif (strpos($url, 'vimeo') > 0) {
        return 'vimeo';
    } else {
        return 'video';
    }
}
if(!function_exists('wplms_get_field_value')){
    function wplms_get_field_value($field,$id,$user_id=null){ 

        if(!empty($id)){
            $from ='';
            $functions = WPLMS_Functions::init();
            
            if(!empty($field['from']))
            switch($field['from']){
                case 'post':
                    $post = $functions->get_post($id);
                    $field['value'] = $post[$field['id']];
                    if($field['type'] == 'editor'){
                        $field['raw'] = $functions->get_post_meta($id,'raw',true);
                    }
                break;
                case 'taxonomy':
                    $terms    = wp_get_post_terms($id,$field['taxonomy']);
                    if(!empty($terms)){
                        foreach ($terms  as $key => $value) {
                            if(empty($field['show_value'])){
                                $field['show_value'] = array();
                            }
                            if(empty($field['value'])){
                                $field['value'] = array();
                            }
                            $field['show_value'][] = array('id'=>$value->term_id,'text'=>$value->term_name);
                            $field['value'][] = $value->term_id;
                        }
                    }   
                break;
                case 'meta':
                    $field['value'] = $functions->get_post_meta($id,$field['id'],true);
                    if(is_serialized($field['value'])){
                        $field['value']=unserialize($field['value']);
                    }
                    switch($field['type']){
                        case 'duration':
                            $field['value'] = array('value'=>$field['value']);
                            $type = get_post_type($id);
                            if($type=='wplms-assignment'){
                                $type = 'assignment';
                            }
                            if($field['id']=='vibe_course_drip_duration'){
                                $field['value']['parameter'] = vibe_get_course_drip_duration_parameter($id);
                            }else{
                                $param_key = 'vibe_'.$type.'_duration_parameter';
                                $field['value']['parameter'] = intval($functions->get_post_meta($id,$param_key,true));
                            }
                            
                            
                        break;
                        case 'featured_video':
                            if(!empty($field['value']) && !is_array($field['value'])){
                                $type = wplms_videoType($field['value']);
                                $field['value'] = array('type'=>$type,'url'=>$field['value']);
                            }
                        break;
                        case 'course_featured':
                        case 'featured_image':

                        case 'media':
                        if(!empty($field['value'])){
                            $field['value'] = wplms_get_single_attachment($field['value']);
                        }
                        break;
                        case 'selectcpt':
                            
                            $data = [];
                            if(!empty($field['value']) && is_numeric($field['value'])){

                                $post = $functions->get_post($field['value']);
                                $data['text'] = empty($post['post_title'])?'':$post['post_title'];
                                $data['id'] = $field['value'];
                                $field['show_value'] = $data;
                            }
                        break;
                        case 'selectproduct':
                            $data = [];
                            if(!empty($field['value']) && is_numeric($field['value'])){
                                
                                if(function_exists('wc_get_product') && $field['cpt'] == 'product'){
                                    $product = wc_get_product($field['value']);
                                    if($product){
                                        $data['text'] = $product->get_name().' - '.$product->get_price_html();
                                        $courses = get_post_meta($field['value'],'vibe_courses',true);
                                        $is_bundle=0;
                                        if(!empty($courses) && is_array($courses) && count($courses)>1 && apply_filters(user_can($user_id,'manage_options'),$user_id,$field)){
                                            $field['is_bundle'] = 1;
                                        }
                                    }
                                }

                                $data['id'] = $field['value'];
                                $data['link'] = get_permalink($field['value']) ;
                                $field['show_value'] = apply_filters('wplms_selectproduct_show_value',$data,$field);
                            }
                        break;

                        case 'selectmulticpt':
                            $data = [];
                            if(!empty($field['value'])){
                               foreach ($field['value'] as $key => $dd) {
                                    $data[] = array('id'=>$dd,'text'=>get_the_title($dd),'link'=>get_permalink($dd));
                                }
                                $field['show_value'] = $data; 
                            }
                        break;
                        case 'multiselect':
                            $data = [];
                            if(!empty($field['value'])){
                               foreach ($field['value'] as $key => $post) {
                                    $post = $functions->get_post($field['value']);
                                    $data[] = array('value'=>$post,'label'=>$post['post_title']);
                                }
                                $field['show_value'] = $data; 
                            }
                        break;
                        case 'assignment':
                            $assignments = $field['value'];
                            $data = array();
                            if(!empty($assignments) && count($assignments)){
                                foreach ($assignments as $key => $assignment) {
                                    $data[] = array('type'=>'assignment','data'=>array('id'=>$assignment,'text'=>$functions->get_the_title($assignment),'link'=>$functions->get_permalink($assignment)));
                                }
                            }
                            $field['value'] = $data;
                        break;
                        case 'quiz_questions':
                            $questions = $field['value'];
                            $data = array();
                            if(!empty($questions) && !empty($questions['ques'])){
                                foreach ($questions['ques'] as $key => $question_id) {
                                    $data[] = array(   
                                        'type'=>'question',
                                        'data'=>array(
                                            'id'=>$question_id,
                                            'type'=>$functions->get_post_meta($question_id,'vibe_question_type',true),
                                            'text'=>$functions->get_the_title($question_id),
                                            'link'=>$functions->get_permalink($question_id)
                                        ),
                                        'marks'=>$questions['marks'][$key]
                                    );
                                }
                            }
                            $field['value'] = $data;
                        break;
                        case 'multiattachments':
                            $data = array();
                            if(!empty($field['value']) && is_array($field['value']) && count($field['value']) ){
                                foreach ($field['value'] as $v) {
                                    $data[] = wplms_get_single_attachment($v);
                                }
                            }
                            $field['value'] = $data;
                        break;
                        case 'editor':
                            //$field['raw'] = $functions->get_post_meta($id,'raw',true);
                        break;
                        default:
                            $field = apply_filters('wplms_front_end_generate_fields_default',$field,$id,$user_id);
                        break;
                    }
                break;
            }

            if(!empty($field['type']) && $field['type'] == 'curriculum'){
                $data = []; 
                $field['value'] = $functions->get_post_meta($id,$field['id'],true);
                $curriculum = $field['value'];
                if(!empty($curriculum)){
                    $data = wplms_process_curriculum($curriculum);
                }
                $field['curriculum'] = $data;
            }
        }else{
            if(!isset($field['value']))
                $field['value'] = $field['default'];
        }

        return apply_filters('wplms_get_field_value',$field,$id,$user_id);
    }
    
}

function wplms_process_curriculum($curriculum){
    $data=[];
    $functions = WPLMS_Functions::init();
    foreach ($curriculum as $key => $c) {
        if(is_numeric($c)){
            
            $post = $functions->get_post($c);

            if(!empty($post['post_type']) && $post['post_type']=='wplms-assignment'){
                $post['post_type'] = 'assignment';
            }
            if(!empty($post) && !empty($post['post_type'])){
                switch($post['post_type']){
                    case 'unit':
                        $type = $functions->get_post_meta($c,'vibe_type',true);
                        if(empty($type)){
                            $type = 'general';
                        }
                    break;
                    case 'quiz':
                        //do not change its fallback
                        $type = wplms_get_quiz_type($c);
                        
                    
                    break;
                    case 'assignment':
                        $type = $functions->get_post_meta($c,'vibe_assignment_submission_type',true);
                        if(empty($type)){
                            $type = 'upload';
                        }
                    break;
                    default:
                        $type = apply_filters('wplms_curriculum_element_type','general');
                    break;
                }
                $data[] = array(
                    'type'=>$post['post_type'],
                    'data'=>array(
                        'id'=>$c,
                        'text'=>$post['post_title'],
                        'type'=>$type
                    )
                );
            }else{

                $data[] = array(
                    'type'=>'unit',
                    'data'=>array(
                        'id'=>$c,
                        'text'=>_x('NA [DELETED]','','wplms'),
                        'type'=>'general'
                    )
                );
            }
            
        }else{
            $data[] = array('type'=>'section','data'=>$c);
        }
    }

    return $data;
}

function wplms_keyed_mime_types(){
    $key_pair = array();
    $mime_types = wp_get_mime_types();
    $a_mime_types = array();
    if(!empty($mime_types)){
        foreach ($mime_types as $key=>$value) {
            $expoloed_keys = explode("|",$key);
            foreach($expoloed_keys as $key1=>$value1){
                $a_mime_types[$value1] = $value;
            }
        }
    }
    $ext_types = wp_get_ext_types();
    if(!empty($ext_types)){
        foreach ($ext_types as $key=>$value) {
            foreach($value  as $key1=>$value1){
                if(!empty($a_mime_types[$value1])){
                    $key_pair[$a_mime_types[$value1]] = $key;
                }   
            }
        }
    }
    return  $key_pair;
}

function wplms_get_single_attachment($post){
    if(is_numeric($post)){
        $post = get_post($post);
        
    }
    $attachment_id =0;$data = array();
    if(!empty($post) && !empty($post->ID)){
        $attachment_id = $post->ID;    
    
        $data = array(
            'name' => $post->post_name,
            'id' => $attachment_id,
            'url' => wp_get_attachment_url($attachment_id)
        );
        $post_mime_type = get_post_mime_type($post);
        if(!empty($post_mime_type)){
            
            if(!empty(wplms_keyed_mime_types()[$post_mime_type])){
                $data['type'] = wplms_keyed_mime_types()[$post_mime_type];
            }else{
                $data['type'] = null;
            }
        }
    }
    return $data;
}

if(!function_exists('wplms_get_element_settings')){
    function wplms_get_element_settings($post_type,$post_id=null){
        if($post_type=='assignment'){
            $post_type = 'wplms-assignment';
        }
        if(!function_exists('vibe_meta_box_arrays'))
            return array();
        $flag = apply_filters('wplms_front_end_get_element_check',1,$post_id);

        if(empty($flag)){
            return array();
        }

        $course_id = 0;
        $settings = apply_filters('wplms_front_end_metaboxes',vibe_meta_box_arrays($post_type),$post_type,$course_id);
        $post_type_label = '';
        if(!empty($post_type)){
            $postType = get_post_type_object(get_post_type($post_type));
            if ($postType) {
                $post_type_label = $postType->labels->singular_name;
            }
        }
        
        if(!empty($settings)){
            array_splice($settings,0,0,array(
                array(
                    'label'=> $post_type_label.' '.__('title','wplms' ),
                    'type'=> 'title',
                    'id' => 'post_title',
                    'from'=>'post',
                    'value_type'=>'single',
                    'style'=>'col-md-12',
                    'default'=> sprintf(__('ENTER A %s TITLE','wplms' ),$post_type_label),
                    'help'=> __('This is the title of the course which is displayed on top of every course','wplms' )
                ),

                array(
                    'label'=> __('Full Description','wplms' ),
                    'type'=> 'editor',
                    'style'=>'col-md-12',
                    'value_type'=>'single',
                    'id' => 'post_content',
                    'from'=>'post',
                    'noscript'=>true,
                    'default'=> sprintf(__('Enter full description of the %s.','wplms' ),$post_type_label)
                )
            ));
        }
                       
        if($post_type == 'quiz'){
            $settings['vibe_quiz_dynamic']=array( // Text Input
                'label' => __('Dynamic Quiz','wplms'), // <label>
                'desc'  => __('Dynamic quiz automatically selects questions.','wplms'), // description
                'id'    => 'vibe_quiz_dynamic', // field id and name
                'type'  => 'conditionalswitch', // type of field
                'hide_nodes'=> array('vibe_quiz_tags','vibe_quiz_number_questions','vibe_quiz_marks_per_question'),
                'options'  => array('H'=>__('DISABLE','wplms'),'S'=>__('ENABLE','wplms')),
                'style'=>'',
                'from'=> 'meta',
                'default'=>'H',
            );

            $settings['vibe_quiz_questions'] = apply_filters('wplms_front_end_quiz_questions',array(
                'label' => __('Quiz Questions','wplms'), // <label>
                'desc'  => __('Static Quiz questions','wplms'), // description
                'id'    => 'vibe_quiz_questions', // field id and name
                'type'  => 'quiz_questions', // type of field
                'cpt'=> 'question',
                'from'=> 'meta',
                'buttons' => array(
                        'question_types'=>apply_filters('wplms_question_types',array(
                              array( 'label' =>__('True or False','wplms'),'value'=>'truefalse'),  
                              array( 'label' =>__('Multiple Choice','wplms'),'value'=>'single'),
                              array( 'label' =>__('Multiple Correct','wplms'),'value'=>'multiple'),
                              array( 'label' =>__('Sort Answers','wplms'),'value'=>'sort'),
                              array( 'label' =>__('Match Answers','wplms'),'value'=>'match'),
                              array( 'label' =>__('Fill in the Blank','wplms'),'value'=>'fillblank'),
                              array( 'label' =>__('Dropdown Select','wplms'),'value'=>'select'),
                              array( 'label' =>__('Small Text','wplms'),'value'=>'smalltext'),
                              array( 'label' =>__('Large Text','wplms'),'value'=>'largetext'),
                              array( 'label' =>__('Survey Type','wplms'),'value'=>'survey')
                        )
                    ))
                ));
            

            $settings['vibe_quiz_duration_parameter']['options'] = wplms_get_duration_parameters_labels();
        }

        if($post_type == 'unit'){
            if(!empty($settings['vibe_assignment'])){
                unset($settings['vibe_assignment']);
            }
            $settings['vibe_unit_duration_parameter']['options'] = wplms_get_duration_parameters_labels();

        }

        if($post_type == 'wplms-assignment'){
            
                $assignment_duration_parameter = apply_filters('vibe_assignment_duration_parameter',86400);
                $settings[3]['extra'] = calculate_duration_time($assignment_duration_parameter);
            
        }

        if($post_type == 'unit' && class_exists('WPLMS_Assignments')){
            $settings['vibe_assignment'] = apply_filters('wplms_front_end_unit_assignments',array(
                'label' => __('Unit assignments','wplms'), // <label>
                'desc'  => __('Select assignment for Unit','wplms'), // description
                'id'    => 'vibe_assignment', // field id and name
                'type'  => 'assignment', // type of field
                'cpt'=> 'assignment',
                'from'=> 'meta',
                'buttons' => array('add_assignment'=>__('ADD ASSIGNMENT','wplms'))
                ));
            if(!empty($post_id)){
                $settings['vibe_assignment']['value'] = get_post_meta($_POST['element_id'],'vibe_assignment',true);
            }
        }

        $new_settings = array();
        foreach ($settings as $key => $set) {
            if($set['type'] == 'duration'){
                $set['options'] = array(
                            array('value'=>0,'label'=>__('Select option','wplms')),
                            array('value'=>1,'label'=>__('Seconds','wplms')),
                            array('value'=>60,'label'=>__('Minutes','wplms')),
                            array('value'=>3600,'label'=>__('Hours','wplms')),
                            array('value'=>86400,'label'=>__('Days','wplms')),
                            array('value'=>604800,'label'=>__('Weeks','wplms')),
                            array('value'=>2592000,'label'=>__('Months','wplms')),
                            array('value'=>31536000,'label'=>__('Years','wplms')),
                        ); 
            }
            if(!is_numeric($key)){
                $set['id'] = $key;
            }

            $set['key'] = $key;
            $new_settings[]  = $set;
            
        }
        return apply_filters('wplms_get_element_settings',$new_settings,$post_type,$post_id);
    }
}
if(!function_exists('wplms_get_duration_parameters_labels')){

    function wplms_get_duration_parameters_labels(){
        return array(
                    array('value'=>0,'label'=>__('Select option','wplms')),
                    array('value'=>1,'label'=>__('Seconds','wplms')),
                    array('value'=>60,'label'=>__('Minutes','wplms')),
                    array('value'=>3600,'label'=>__('Hours','wplms')),
                    array('value'=>86400,'label'=>__('Days','wplms')),
                    array('value'=>604800,'label'=>__('Weeks','wplms')),
                    array('value'=>2592000,'label'=>__('Months','wplms')),
                    array('value'=>31536000,'label'=>__('Years','wplms')),
                ); 
    }
}

if(!function_exists('wplms_calculate_duration_time')){
    function wplms_calculate_duration_time($seconds) {
        $return = '';
        switch($seconds){
            case 1: $return = __('Seconds','wplms');break;
            case 60: $return = __('Minutes','wplms');break;
            case 3600: $return = __('Hours','wplms');break;
            case 86400: $return = __('Days','wplms');break;
            case 604800: $return = __('Weeks','wplms');break;
            case 2592000: $return = __('Months','wplms');break;
            case 31104000: $return = __('Years','wplms');break;
            default:
            $return = apply_filters('vibe_calculation_duration_default',$return,$seconds);
            break;
        }
        return $return;
    } 
}
if(!function_exists('vibe_get_course_drip_duration_parameter')){
    function vibe_get_course_drip_duration_parameter($course_id){
        $meta = get_post_meta($course_id,'vibe_course_drip_duration_parameter',true);
        if(!empty( $meta)){
            return  $meta;
        }else{
            $meta = get_post_meta($course_id,'vibe_drip_duration_parameter',true);
            if(!empty($meta)){
                update_post_meta($course_id,'vibe_course_drip_duration_parameter',$meta);
            }
        }
        return $meta;
    }
}
if(!function_exists('wplms_get_product_fields')){
    function wplms_get_product_fields($product_id=null){
        $vibe_subscription = 0;$title = '';$vibe_subscription = 0;$vibe_product_duration =0;$sale_price = 0;
        if(!empty($product_id) && is_numeric($product_id)){
            $title = get_the_title($product_id);
            $product_duration_parameter = apply_filters('vibe_product_duration_parameter',86400,$product_id); 
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
        
        $product_fields= apply_filters('wplms_front_end_new_product',array(
            array(
            'label'=> __('Title','wplms' ),
            'placeholder'=>__('Product Title','wplms' ),
            'type'=> 'text',
            'style'=>'',
            'from'=>'post',
            'id' => 'post_title',
            'value'=>$title,
            'desc'=> __('Product title is useful to identify courses connected to this product.','wplms' ),
            ),
            array(
            'label'=> __('Price','wplms' ),
            'text'=>__('Price','wplms' ),
            'type'=> 'text',
            'style' => 'col-md-6',
            'id' => '_regular_price',
            'value' => $price,
            'extra' => (function_exists('get_woocommerce_currency')?get_woocommerce_currency():'$'),
            'desc'=> __('Set price of the course','wplms' ),
            ),
            array(
            'label'=> __('Sale','wplms' ),
            'text'=>__('Sale Price','wplms' ),
            'type'=> 'text',
            'style' => 'col-md-6',
            'value'=>$sale_price,
            'id' => '_sale_price',
            'extra' => (function_exists('get_woocommerce_currency')?get_woocommerce_currency():'$'),
            'desc'=> __('Blank if product not in sale','wplms' ),
            ),
            array(
            'label'=> __('Subscription','wplms' ),
            'type'=> 'conditionalswitch',
            'text'=>__('Subscription Type','wplms' ),
            'hide_nodes'=> array('vibe_product_duration','vibe_product_duration_parameter'),
            'options'  => array('H'=>__('FULL DURATION','wplms' ),'S'=>__('LIMITED','wplms' )),
            'style'=>'',
            'default'=>'H',
            'value'=>$vibe_subscription,
            'id' => 'vibe_subscription',
            'desc'=> __('Set subscription type of product.','wplms' ),
            ),
            array(
            'label'=> __('Subscription Duration','wplms' ),
            'type'=> 'text',
            'text' => __('Set subscription duration','wplms'),
            'style'=>'',
            'id' => 'vibe_product_duration',
            'from'=> 'meta',
            'value'=>$vibe_product_duration,
            'default'=> __('Must not be 0','wplms'),
            ),
            array(
            'label'=> __('Subscription Duration Parameter','wplms' ),
            'text' => __('Set subscription duration parameter','wplms'),
            'type'=> 'duration',
            'style'=>'',
            'id' => 'vibe_product_duration_parameter',
            'default' => $product_duration_parameter,
            'from'=> 'meta',
            'value' => $product_duration_parameter,
            'options'=>wplms_get_duration_parameters_labels(),
            ),
        ));

        return $product_fields;
    }
}

