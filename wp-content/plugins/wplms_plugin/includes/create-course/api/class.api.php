<?php
/**
 * 
 *
 * @author 		Anshuman Sahu,VibeThemes
 * @category 	Init
 * @package 	wplms_plugin
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}



class WPLMS_Create_Course_Api{


	public static $instance;

    public static function init(){

        if ( is_null( self::$instance ) )
            self::$instance = new WPLMS_Create_Course_Api();
        return self::$instance;
    }


    function __construct(){
    	add_action('rest_api_init',array($this,'register_routes'));
    }


    function register_routes(){
        
    	register_rest_route( WPLMS_API_NAMESPACE, '/selectcpt/(?P<cpt>\w+)', array(
            array(
                'methods'             =>  'POST',
                'callback'            =>  array( $this, 'selectcpt' ),
                'permission_callback'       => array( $this, 'get_user_permissions_check' ),
                'args'                      =>  array(
                'cpt'                        =>  array(
                    'validate_callback'     =>  function( $param, $request, $key ) {
                                                return !empty( $param );
                                            }
                    ),
                ),
            ),
        ));

        register_rest_route( WPLMS_API_NAMESPACE, '/getDraft/', array(
            array(
                'methods'             =>  'POST',
                'callback'            =>  array( $this, 'get_draft' ),
                'permission_callback'       => array( $this, 'get_user_permissions_check' ),
            ),
        ));

        register_rest_route( WPLMS_API_NAMESPACE, '/getapproval/', array(
            array(
                'methods'             =>  'POST',
                'callback'            =>  array( $this, 'getapproval' ),
                'permission_callback'       => array( $this, 'get_user_permissions_check' ),
            ),
        ));

        register_rest_route( WPLMS_API_NAMESPACE, '/saveDraft/', array(
            array(
                'methods'             =>  'POST',
                'callback'            =>  array( $this, 'save_draft' ),
                'permission_callback'       => array( $this, 'get_user_permissions_check' ),
            ),
        ));

        


        register_rest_route( WPLMS_API_NAMESPACE, '/create/(?P<course>\d+)', array(
            array(
                'methods'             =>  'POST',
                'callback'            =>  array( $this, 'create_course' ),
                'permission_callback'       => array( $this, 'get_create_user_permissions_check' ),
            ),
        ));
        
        register_rest_route( WPLMS_API_NAMESPACE, '/taxonomy', array(
            array(
                'methods'             =>  'POST',
                'callback'            =>  array( $this, 'get_taxonomy' ),
                'permission_callback'       => array( $this, 'get_user_permissions_check' ),
                'args'                      =>  array(
                'taxonomy'                        =>  array(
                    'validate_callback'     =>  function( $param, $request, $key ) {
                                                return !empty( $param );
                                            }
                    ),
                ),
            ),
        ));


        register_rest_route( WPLMS_API_NAMESPACE, '/createElement/(?P<cpt>\w+)', array(
            array(
                'methods'             =>  'POST',
                'callback'            =>  array( $this, 'create_element' ),
                'permission_callback'       => array( $this, 'get_create_user_permissions_check' ),
                'args'                      =>  array(
                'cpt'                        =>  array(
                    'validate_callback'     =>  function( $param, $request, $key ) {
                                                return !empty( $param );
                                            }
                    ),
                ),
            ),
        ));

        register_rest_route( WPLMS_API_NAMESPACE, '/deleteElement/(?P<postID>\w+)', array(
            array(
                'methods'             =>  'POST',
                'callback'            =>  array( $this, 'delete_element' ),
                'permission_callback'       => array( $this, 'get_create_user_permissions_check' ),
                'args'                      =>  array(
                'cpt'                        =>  array(
                    'validate_callback'     =>  function( $param, $request, $key ) {
                                                return !empty( $param );
                                            }
                    ),
                ),
            ),
        ));
        
        register_rest_route( WPLMS_API_NAMESPACE, '/gettabs/(?P<course>\w+)', array(
            array(
                'methods'             =>  'POST',
                'callback'            =>  array( $this, 'get_tabs' ),
                'permission_callback'       => array( $this, 'get_user_permissions_check' ),
                'args'                      =>  array(
                'cpt'                        =>  array(
                    'validate_callback'     =>  function( $param, $request, $key ) {
                                                return isset( $param );
                                            }
                    ),
                ),
            ),
        ));

        register_rest_route( WPLMS_API_NAMESPACE, '/curriculum/getElementFields/', array(
            array(
                'methods'             =>  'POST',
                'callback'            =>  array( $this, 'edit_element_fields' ),
                'permission_callback'       => array( $this, 'get_user_permissions_check' ),
            ),
        ));



        register_rest_route( WPLMS_API_NAMESPACE, '/component/get_group/', array(
            array(
                'methods'             =>  'POST',
                'callback'            =>  array( $this, 'get_group_component' ),
                'permission_callback'       => array( $this, 'get_user_permissions_check' ),
            ),
        ));
        register_rest_route( WPLMS_API_NAMESPACE, '/component/get_forum/', array(
            array(
                'methods'             =>  'POST',
                'callback'            =>  array( $this, 'get_forum_component' ),
                'permission_callback'       => array( $this, 'get_user_permissions_check' ),
            ),
        ));

        register_rest_route( WPLMS_API_NAMESPACE, '/getQuizQuestions/', array(
            array(
                'methods'             =>  'POST',
                'callback'            =>  array( $this, 'get_quiz_questions' ),
                'permission_callback'       => array( $this, 'get_user_permissions_check' ),
            ),
        ));

        
        register_rest_route( WPLMS_API_NAMESPACE, '/create/component/(?P<cpt>\w+)', array(
            array(
                'methods'             =>  'POST',
                'callback'            =>  array( $this, 'create_component' ),
                'permission_callback'       => array( $this, 'get_create_user_permissions_check' ),
                'args'                      =>  array(
                'cpt'                        =>  array(
                    'validate_callback'     =>  function( $param, $request, $key ) {
                                                return !empty( $param );
                                            }
                    ),
                ),
            ),
        ));


        register_rest_route( WPLMS_API_NAMESPACE, '/curriculum/getElement', array(
            array(
                'methods'             =>  'POST',
                'callback'            =>  array( $this, 'get_element' ),
                'permission_callback'       => array( $this, 'get_user_permissions_check' ),
            ),
        ));

        register_rest_route( WPLMS_API_NAMESPACE, '/unit/elementorLink/', array(
            array(
                'methods'             =>  'POST',
                'callback'            =>  array( $this, 'get_elementor_link' ),
                'permission_callback'       => array( $this, 'get_user_permissions_check' ),
            ),
        ));

        

        register_rest_route( WPLMS_API_NAMESPACE, '/product/(?P<id>\d+)', array(
            array(
                'methods'             =>  'POST',
                'callback'            =>  array( $this, 'get_product' ),
                'permission_callback'       => array( $this, 'get_user_permissions_check' ),
                'args'                      =>  array(
                'id'                        =>  array(
                    'validate_callback'     =>  function( $param, $request, $key ) {
                                                return is_numeric( $param );
                                            }
                    ),
                ),
            ),
        ));

        register_rest_route( WPLMS_API_NAMESPACE, '/getUploadedPackages', array(
            array(
                'methods'             =>  'POST',
                'callback'            =>  array( $this, 'get_packages' ),
                'permission_callback'       => array( $this, 'get_user_permissions_check' ),
            ),
        ));

        register_rest_route( WPLMS_API_NAMESPACE, '/uploadPackage', array(
            array(
                'methods'             =>  'POST',
                'callback'            =>  array( $this, 'upload_package' ),
                'permission_callback'       => array( $this, 'get_create_user_permissions_check' ),
            ),
        ));
         register_rest_route( WPLMS_API_NAMESPACE, '/deletePackage', array(
            array(
                'methods'             =>  'POST',
                'callback'            =>  array( $this, 'delete_package' ),
                'permission_callback'       => array( $this, 'get_create_user_permissions_check' ),
            ),
        ));
        
	}



    function create_course($request){
        
        $body = json_decode($request->get_body(),true);
        $real_object = $body['object'];
        $is_bundle = 0;
        for ($i=(count($real_object)-1);$i>0;$i--) {
            if(!empty($real_object[$i]['fields']) && $real_object[$i]['id'] == 'course_pricing'){
                foreach ($real_object[$i]['fields'] as $l => $field) {
                    if ($field['id'] =='vibe_product') {
                        if(!empty($field['is_bundle'])){
                            $is_bundle = 1;
                        }
                    }
                }
            }
        }

        $return = array('status'=>0,'message'=>__('Could not save course','wplms'));

        $args = apply_filters('wplms_front_end_create_course',$body['course'],$body);
        $course_id = $request->get_param('course');

        if(!apply_filters('wplms_can_edit',true,$body['course'],$this->user->id)){
            $return['message']=__('Missing create/edit access to course.','wplms');
            return new WP_REST_Response( $return, 200 );
        }

        

        $nargs = array();
        foreach($args as $key=>$value){
            if($key != 'meta' && $key != 'taxonomy'){
                $nargs[$key]=$value;    
            }
        }
        $admin_approval = 0;

        if(function_exists('vibe_get_option') && vibe_get_option('new_course_status')=='pending' && get_post_status($course_id)!='publish'){
            $admin_approval = 1;
        }
        $manage_options = user_can($this->user->id,'manage_options');
        
        remove_filter( 'content_save_pre', 'wp_filter_post_kses' );
        $status = 0;
        if(!empty($course_id)){
            $status = get_post_status($course_id);
        }
        if($admin_approval  && !$manage_options){
            $nargs['post_status']='pending';
        }else{
            $nargs['post_status']='publish';
        }
        
        if($nargs['post_status']=='publish'){
            $check_can_create = apply_filters('wplms_user_can_create_element',false,'course',$this->user->id,$body);
            if($check_can_create){
                $return = array('status'=>false,'message'=>$check_can_create);
                return new WP_REST_Response( $return, 200 ); 
            }
        }
        
        

        if(!empty($course_id)){
            
            if($status=='publish'){
                $admin_approval = 0;
            }
            $nargs['ID']=$course_id;
            $nargs['post_title'] = sanitize_text_field($nargs['post_title']);
            $nargs['post_content'] = wp_slash($nargs['post_content']);
            $id = wp_update_post($nargs);

        }else{
            unset($nargs['ID']);
            $nargs['post_author']=$this->user->id;
            
            $nargs['post_type']='course';
            
            $nargs['post_title'] = sanitize_text_field($nargs['post_title']);
            $id = wp_insert_post($nargs);
            do_action('wplms_course_go_live',$course_id,$nargs,$body);
            

        }
        add_filter( 'content_save_pre', 'wp_filter_post_kses' );
        $return['nargs']=$nargs;
        $return['args']=$args;
        $return['course_id']=$id;
        if($admin_approval  && !$manage_options){
            update_post_meta($id,'vibe_draft',$body);

            $return['status']=true;
            $return['message']=__('Successfully saved !Pending for admin approval!','wplms');

            
            return new WP_REST_Response( apply_filters('wplms_course_created_updated',$return,$body), 200 );
        }

        if(is_wp_error($id)){
            $return['message']=$id->get_error_message();
            return new WP_REST_Response( $return, 200 );
        }
        
        $course_thumbnail_id = 0;
        if(!empty($id)){
            $return['status']=true;
            $return['message']=__('Successfully saved !','wplms');
            if(!empty($args['meta']) && count($args['meta'])){
                foreach ($args['meta'] as  $meta) {
                    if($meta['meta_key'] == '_thumbnail_id' && is_array($meta['meta_value'])){
                        
                        $meta['meta_value']=$meta['meta_value']['id'];
                        if(empty($course_thumbnail_id)){
                            
                            $course_thumbnail_id= $meta['meta_value'];
                        }
                        
                    }

                    if(isset($meta['meta_value'])){

                        if($meta['meta_key']=='vibe_duration_parameter'){
                            $meta['meta_key'] = 'vibe_course_duration_parameter';
                        }
                        if($meta['meta_key']=='vibe_forum'){
                            if(is_array($meta['meta_value'])){
                                $meta['meta_value'] = $meta['meta_value']['id'];
                            }
                        }
                        if($meta['meta_key'] == 'raw'){
                           $meta['meta_value']=wp_slash($meta['meta_value']);
                        }
                        update_post_meta($id,$meta['meta_key'],$meta['meta_value']);
                        if($meta['meta_key'] == 'vibe_product'){
                            if(empty($meta['meta_value'])){
                                wp_set_object_terms($meta['meta_value'] ,'simple', 'product_type');
                                update_post_meta($meta['meta_value'],'_visibility','visible');
                                update_post_meta($meta['meta_value'],'_virtual','yes');
                                update_post_meta($meta['meta_value'],'_downloadable','yes');
                                update_post_meta($meta['meta_value'],'_sold_individually','yes');
                            }

                            if(!empty($id)){
                                $max_seats = get_post_meta($id,'vibe_max_students',true);
                                if(!empty($max_seats) && $max_seats < 9999){
                                    update_post_meta($meta['meta_value'],'_manage_stock','yes');
                                    update_post_meta($meta['meta_value'],'_stock',$max_seats);
                                }
                            }
                            if($is_bundle){
                                $courses = get_post_meta($meta['meta_value'],'vibe_courses',true);
                                if(empty($courses)){
                                    $courses = array();
                                }
                                if(is_numeric($courses)){
                                    $courses = array($courses);
                                }
                                if(is_array($courses)){
                                    if(!in_array($id, $courses)){
                                        $courses[] = $id;
                                    }
                                }
                                
                            }else{
                                $courses = array($id);
                            }
                            
                            update_post_meta($meta['meta_value'],'vibe_courses',$courses);
                            
                            update_post_meta($id,'vibe_product',$meta['meta_value']);
                            $thumbnail_id = get_post_thumbnail_id($meta['meta_value']);

                            if(!empty($course_thumbnail_id) && empty($thumbnail_id)){
                                set_post_thumbnail($meta['meta_value'],$course_thumbnail_id);
                            }
                        }
                    }else{
                        delete_post_meta($id,$meta['meta_key']);
                    }
                    do_action('wplms_front_end_field_save',$id,$meta);
                }
            }
            if(!empty($args['taxonomy']) && count($args['taxonomy'])){
                $_cat_ids = array();
                foreach ($args['taxonomy'] as  $taxonomy) {
                    if(!empty($taxonomy['value'])){
                        foreach($taxonomy['value'] as $k=>$cat_id){
                            if(!is_numeric($cat_id) && strpos($cat_id, 'new_') === 0){
                                $new_cat = explode('new_',$cat_id);
                                $cid = wp_insert_term($new_cat[1],$taxonomy['taxonomy']);
                                if(is_array($cid)){
                                    $taxonomy['value'][$k] = $cid['term_id'];
                                }else{
                                    unset($taxonomy['value'][$k]);
                                }
                            }
                        }
                        
                    }
                    wp_set_object_terms( $id, $taxonomy['value'], $taxonomy['taxonomy'] );
                }
            }
            do_action('wplms_front_end_field_saved',$id,$args);
        }
        
        return new WP_REST_Response( apply_filters('wplms_course_created_updated',$return,$body), 200 );
    }

    function get_draft($request){
        $body = json_decode($request->get_body(),true);
        
        if(!empty($body['course_id'])){
            $draft = get_post_meta($body['course_id'],'draft_course',true);
        }else{
             $draft = get_user_meta($this->user->id,'draft_course',true);
        }
        $return = array('status'=>0,'message'=>__('No drafts found','wplms'));
        if(!empty($draft)){
            if(is_serialized($draft)){
                $draft = unserialize_recursive($draft);
            }
            $return = array('status'=>1,'draft'=>$draft);
        }
        return new WP_REST_Response($return, 200 );
    }

    function save_draft($request){
        $body = json_decode($request->get_body(),true);
        if(!empty($body['course_id'])){
            update_post_meta($body['course_id'],'draft_course',$body['course']);
               
        }else{
            $nargs = [];
            if(!empty($body['course'])){
                foreach ($body['course'] as $key => $tab) {
                    if(!empty($tab['fields'])){
                        foreach ($tab['fields'] as $ky => $field) {
                            if($field['from']=='post'){
                                if($field['id']=='post_title' && !empty($field['value'])){
                                    $nargs['post_title'] = $field['value'];
                                }
                                if($field['id']=='_thumbnail_id' && !empty($field['value'])){
                                    update_post_meta($id,$field['id'],$field['value']);
                                }
                                if($field['id']=='post_excerpt' && !empty($field['value'])){
                                    $nargs['post_excerpt'] = $field['value'];
                                }
                                if($field['id']=='post_content' && !empty($field['value'])){
                                    $nargs['post_content'] = wp_slash($field['value']);
                                }
                            }
                        }
                    }
                }
            }
            if(!empty($nargs['post_title'])){
                $nargs['post_author']=$this->user->id;
                $nargs['post_type']='course';
                $nargs['post_status']='draft';
                $nargs['post_title'] = sanitize_text_field($nargs['post_title']);
                $id = wp_insert_post($nargs);
                if(!empty($id) && !is_wp_error($id)){
                    update_post_meta($id,'draft_course',$body['course']);
                }

                return new WP_REST_Response( array('status'=>1,'message'=>__('Course draft saved !','wplms'),'user'=>$this->user,'course'=>$id), 200 );

            }else{
                update_user_meta($this->user->id,'draft_course',$body['course']);     
            }
            
        }
        return new WP_REST_Response( array('status'=>1,'message'=>__('Course draft saved !','wplms')), 200 );
    }

    function getapproval($request){
        $body = json_decode($request->get_body(),true);
        $status = get_post_status($body['courseid']);
        if(!empty($body['courseid']) &&  $status != 'publish'){
            $approval = get_post_meta($body['courseid'],'vibe_draft',true);
        }
        $return = array('status'=>0,'message'=>__('No drafts found','wplms'),'is_draft' => (($status!='publish')?true:false));
        if(!empty($approval)){
            if(is_serialized($approval)){
                $approval = unserialize_recursive($approval);
            }
            $return = array('status'=>1,'approval'=>$approval,'is_draft' => (($status!='publish')?true:false));
        }
        return new WP_REST_Response($return, 200 );
    }

    function save_field_if_changed($setting,$course_id){

        if(isset($setting['is_changed']) && $setting['is_changed'] ){
            $this->save_field($setting,$course_id);
        }
    }

    function save_field($setting,$course_id){

        $post_settings = array();

        if($course_id){
            $post_settings['ID'] = $course_id;
        }

        switch ($setting['type']) {
            case 'media':
                if(empty($setting['value'])){
                    delete_post_meta($course_id,$setting['id']);
                }else{
                    update_post_meta($course_id,$setting['id'],$setting['value']['id']);
                }
                
                break;
            case 'title':
                $post_settings['post_title'] = sanitize_textarea_field($setting['value']);
            break;
            case 'taxonomy':
                if(empty($post_settings['tax_input'])){
                    $post_settings['tax_input'] = array();
                }
                if($setting->value != 'new' && is_numeric($setting['value'])){
                    $post_settings['tax_input'][$setting['id']] = sanitize_textarea_field($setting['value']);    
                }
            break;
            case 'taxonomy_new':
                if(empty($post_settings['tax_input'])){
                    $post_settings['tax_input'] = array();
                }
                //$setting->id is taxonomy
                if(!empty($setting->value)){
                    $term = term_exists($setting->value, $setting['id']);
                    if ($term !== 0 && $term !== null) {
                       
                    }else{
                        $new_term = wp_insert_term(sanitize_textarea_field($setting->value), $setting['id']);
                        $setting->value = $new_term['term_id'];
                    }
                    $post_settings['tax_input'][$setting['id']] = $setting['value'];    
                }
            break;
            case 'featured_image':

                if(isset($setting['value'])){
                    $featured_thumb = $setting['value']['id'];
                }
            break;

            case 'featured_video':
                if(isset($setting['value'])){
                    update_post_meta($course_id,$setting['id'],$setting['value']['id']);
                }else{
                    delete_post_meta($course_id,$setting['id']);
                }
                

            break;
            case 'curriculum':
                $_curriculum = [];
                if(empty($setting['curriculum'])){
                    delete_post_meta($course_id,'vibe_course_curriculum');
                }else{
                    foreach ($setting['curriculum'] as $k => $c) {
                        if(isset($c['data']['id'])){
                            if(!empty($c['type'])){
                                $unit_id = $c['data']['id'];
                                $_curriculum[] = $unit_id;
                            }
                            
                        }else{
                            if($c['data']){
                                $_curriculum[] = $c['data'];
                            }
                            
                        }

                        if(isset($unit_id) && !empty($c['settings'])){
                            foreach ($c['settings'] as $key => $s) {
                                $this->save_field_if_changed($s,$unit_id);
                            }
                        }
                    }
                    update_post_meta($course_id,$setting['id'],$_curriculum);
                }
                
            break;

            case 'assignment':
                $assignments = [];
                if(empty($setting['value'])){
                    delete_post_meta($course_id,'vibe_assignment');
                }else{
                    foreach ($setting['value'] as $k => $c) {
                        if(isset($c['data']['id'])){
                            $unit_id = $c['data']['id'];
                            $assignments[] = $unit_id;
                        }

                        if(isset($unit_id) && !empty($c['settings'])){
                            foreach ($c['settings'] as $key => $s) {
                                $this->save_field_if_changed($s,$unit_id);
                            }
                        }
                    }
                    update_post_meta($course_id,$setting['id'],$assignments);
                }
                
            break;

            case 'multiattachments':
                if(empty($setting['value'])){
                    delete_post_meta($course_id,$setting['id']);
                }else{
                    if(is_array($setting['value'])){
                        $attachments = [];
                        foreach ($setting['value'] as  $v) {
                            if(isset($v['id'])){
                                $attachments[] = $v['id'];
                            }
                            
                        }
                    }
                    update_post_meta($course_id,$setting['id'],$attachments);
                }

            break;
            case 'quiz_questions':
                $questions = ['ques'=>[],'marks'=>[]];
                if(empty($setting['value'])){
                    delete_post_meta($course_id,'vibe_assignment');
                }else{
                    foreach ($setting['value'] as $k => $c) {
                        if(isset($c['data']['id'])){
                            $unit_id = $c['data']['id'];
                            $questions['ques'][] = $unit_id;
                            $questions['marks'][] = ($c['marks'])?$c['marks']:0;

                            if(!empty($unit_id) && !empty($c['settings'])){
                                foreach ($c['settings'] as $key => $s) {
                                    $this->save_field_if_changed($s,$unit_id);
                                }
                            }
                        }
                    }
                    update_post_meta($course_id,$setting['id'],$questions);
                }
            break;
            default:
                if(empty($setting['value'])){
                    delete_post_meta($course_id,$setting['id']);
                }else{
                    update_post_meta($course_id,$setting['id'],$setting['value']);
                }
                break;
        }
        $post_id = wp_update_post($post_settings);
        if(is_numeric($post_id) && $post_id){
            if(isset($featured_thumb) && is_numeric($featured_thumb))
                set_post_thumbnail($post_id,$featured_thumb);
            ob_start();
            do_action('wplms_front_end_save_course',$post_id,$settings);
            ob_get_clean();

        }
    }

    function get_tabs($request){
        $course_id = $request->get_param('course');
        $return=array();
        if(!empty($course_id)){
            $tabs = get_wplms_create_course_tabs($course_id,$this->user->id);
            
            foreach ($tabs as $key => $tab) {
                foreach ($tabs[$key]['fields'] as $k => $field) { 
                    $tabs[$key]['fields'][$k] = wplms_get_field_value($field,$course_id,$this->user->id);
                }
            }
        }else{
            $tabs = get_wplms_create_course_tabs(null,$this->user->id);
        }
        
        foreach ($tabs as $key => $tab) {
            $tab['id']=$key;
            $return[]=$tab;
        }    
        
        return new WP_REST_Response( $return, 200 );
    }

    function get_product($request){
        $product_id= $request->get_param('id');
        $body = $request->get_body();
        $body = json_decode($body);
        
        $return = array();
        $data = wplms_get_product_fields($product_id);
        if(!empty($data)){
            $return =array('status'=>true,'data'=>$data);
        }else{
            $return =array('status'=>false,'message'=>_x('Error fetching!','','wplms'));
        }
        return new WP_REST_Response( $return, 200 );
    }

    function get_elementor_link($request){
        $body = json_decode($request->get_body(),true);
        return new WP_REST_Response( array('status'=>1,'link'=>admin_url('post.php?post='.$body['id'].'&action=elementor')),200);
    }

    function get_element($request){
        
        $body = json_decode($request->get_body(),true);
        $init = BP_Course_Action::init();
        $user = new BP_Course_New_Rest_User_Controller('user');
        $user->user = $this->user;
        $user->user_id = $this->user->id;
        return $user->get_course_status_item($request,array('item_id'=>$body['id']));
        

        //return print_r(stripslashes($return));
        //return new WP_REST_Response(stripslashes($return), 200 );
    }

    function delete_element($request){
        $body = json_decode($request->get_body(),true);
        $post_id= $request->get_param('postID');
        $author_tobe_checked = $post_id;
        if(!empty($body['course_id'])){
            $author_tobe_checked =$body['course_id'];
        }

        $is_author = 0;
        if(user_can($this->user->id,'manage_options')){
            $is_author =1;
        }else{
            if(function_exists('get_coauthors')){
                $authors = get_coauthors( $author_tobe_checked );
                foreach ($authors as $key => $author) {
                    if($author->ID == $this->user->id){
                        $is_author =1;
                        break;
                    }
                }
            }else{
                $post  = get_post($author_tobe_checked);
                if($post->post_author == $this->user->id){
                    $is_author =1;
                }
            }
        }
        
        if( $is_author){
            $result = wp_trash_post($post_id);
            if(!$result){
                 $return =array('status'=>false,'message'=>_x('Error deleting element!','','wplms'));
             }else{
                if(!empty($body['course_id']) && !empty($body['delete_content'])){
                    $curriculum = bp_course_get_curriculum($body['course_id']);
                    if(!empty($curriculum )){
                        $new_crr = [];
                        foreach ($curriculum as $key => $cc) {
                            if(is_numeric($cc)){
                                wp_trash_post( $cc);
                            }
                        }
                    }
                }

                 $return =array('status'=>true,'message'=>_x('Item Deleted','','wplms'));
             }
         }else{
            $return =array('status'=>false,'message'=>_x('You are not allowed to delete this item','','wplms'));
         }
        return new WP_REST_Response( $return, 200 ); 
    }

    function create_element($request){
        $id = 0;
     
        $post = json_decode(file_get_contents('php://input'));
        $body = json_decode($request->get_body(),true);
        $cpt= $request->get_param('cpt');
        if($cpt=='assignment'){
            $cpt = 'wplms-assignment';
        }


        $return = array();
        $return = array('status'=>false,'message'=>__('Not saved.','wplms'));

        if(!in_array($cpt,apply_filters('wplms_create_element_cpts',array('unit','quiz','wplms-assignment','course','question','product','certificate')))){
            //PAge editing not allowed

            return new WP_REST_Response( $return, 200 ); 
        }

        do_action('wplms_create_course_create_element');
        
        $admin_approval = 0;

        if(function_exists('vibe_get_option') && vibe_get_option('new_course_status')=='pending' && $cpt 
            != 'product' && $cpt !='question'){
            $admin_approval = 1;
        }
        $manage_options = user_can($this->user->id,'manage_options');
        

        if(empty($body['id'] )){
            $check_can_create = apply_filters('wplms_user_can_create_element',false,$cpt,$this->user->id ,$body);
            if($check_can_create){
                $return = array('status'=>false,'message'=>$check_can_create);
                return new WP_REST_Response( $return, 200 ); 
            }
            $args = apply_filters('wplms_front_end_create_curriculum',array(
                'post_type' => $cpt,
                'post_title' => sanitize_textarea_field($body['post_title']),
                'post_content' => (!empty($body['post_content'])?wp_slash($body['post_content']):sanitize_textarea_field($body['post_title'])),
                'post_status'=>'publish',
                'post_author'=>$this->user->id
            ));
            if($admin_approval  && !$manage_options){
                $args['post_status']='pending';
                unset($args['post_content']); 
            }else{
                $args['post_status']='publish';
            }
            remove_filter('content_save_pre', 'wp_filter_post_kses');
            remove_filter('content_filtered_save_pre', 'wp_filter_post_kses');
            $id = wp_insert_post($args);
            if(is_wp_error($id)){
                return new WP_REST_Response( array('status'=>0,'message'=>$id->get_error_message()), 200 );
            }

            add_filter('content_save_pre', 'wp_filter_post_kses');
            add_filter('content_filtered_save_pre', 'wp_filter_post_kses');

        }else{
           
            $id =$body['id'];
            $can_edit = apply_filters('wplms_can_edit',true,$id,$this->user);
            if(empty($can_edit)){
                return new WP_REST_Response( array('status'=>false,'message'=>__('Can not make changes.','wplms')), 200 );
            }
            if(!empty($body['post_title']) || !empty($body['post_content'])){
                $args = apply_filters('wplms_front_end_create_curriculum',array(
                    'ID'=>$body['id'],
                    'post_type' => $cpt,
                    'post_title' => sanitize_textarea_field($body['post_title']),
                    'post_content' => !empty($body['post_content'])?wp_slash($body['post_content']):sanitize_textarea_field($body['post_title']),
                    'post_status'=>'publish',
                    'post_author'=>$this->user->id
                ));
                $status = get_post_status($id);
                if($status=='pending'){
                    if($admin_approval  && !$manage_options){
                        $args['post_status']='pending';
                        unset($args['post_content']); 

                    }else{
                        $args['post_status']='publish';
                    }
                }else{
                    if($status=='publish'){
                        $admin_approval=0;
                    }
                }

                remove_filter('content_save_pre', 'wp_filter_post_kses');
                remove_filter('content_filtered_save_pre', 'wp_filter_post_kses');
                $id = wp_update_post($args);
                add_filter('content_save_pre', 'wp_filter_post_kses');
                add_filter('content_filtered_save_pre', 'wp_filter_post_kses');
            }
        }
        


        if($admin_approval  && !$manage_options){
            update_post_meta($id,'vibe_draft',$body['editfields']);

            $return = array('status'=>true,'data'=>array('id'=>$id,'text'=>$body['post_title'],'type'=>$body['type']),'message'=>__('Successfully saved !Pending for approval!','wplms'));

            
            return new WP_REST_Response( apply_filters('wplms_create_cpt_return',$return,$body,$cpt), 200 );
        }

        if(!empty($id)){

            $return = array(
                'status'=>true,
                'data'=>array(
                    'id'=>$id,
                    'text'=>(empty($body['post_title'])?'':$body['post_title']),
                ),
                'message'=>__('Successfully saved !','wplms')
            );
            //handle product:
            

            if(!empty($body['raw'])){
                update_post_meta($id,'raw',wp_slash($body['raw']));
            }
            if(!empty($body['meta']) && count($body['meta'])){
                foreach ($body['meta'] as  $meta) {
                    if(isset($meta['meta_value'])){
                        if($meta['meta_key'] == 'vibe_type' && $cpt == 'wplms-assignment'){
                            $meta['meta_key']='vibe_assignment_submission_type';
                            
                        }


                        if($meta['meta_key'] == 'vibe_quiz_tags'){
                            if(!empty($meta['meta_value'])){
                                $_val = [];
                                foreach ($meta['meta_value'] as $key => $value) {
                                    if(!empty($value['count'])){
                                        $_val['tags'][$key] = $value['tagfield']['value'];
                                        $_val['numbers'][$key] = $value['count'];
                                        $_val['marks'][$key] = $value['marks'];
                                    }
                                    
                                }
                                $meta['meta_value'] = $_val;
                            }
                        }

                        if($meta['meta_key'] == 'vibe_practice_questions'){
                            if(!empty($meta['meta_value'])){
                                if(is_array($meta['meta_value']) && !empty($meta['meta_value']['type'])){
                                    if($meta['meta_value']['type']=='tags'){
                                        $_val = [];
                                        foreach ($meta['meta_value']['value'] as $key => $value) {
                                            if(!empty($value['count'])){
                                                $_val['tags'][$key] = $value['tagfield']['value'];
                                                $_val['numbers'][$key] = $value['count'];
                                            }
                                        }
                                        
                                    }
                                    elseif($meta['meta_value']['type']=='questions'){
                                        $_val = [];
                                        foreach ($meta['meta_value']['value'] as $key => $value) {
                                            $_val[] = $value['data']['id'];
                                        }
                                    }
                                    $meta['meta_value'] = array('type'=>$meta['meta_value']['type'],'value'=>$_val);
                                }
                            }
                        }
                        
                        if($meta['meta_key'] == 'vibe_duration_parameter'){
                            $_cpt = $cpt;
                            if($_cpt=='wplms-assignment'){
                                $_cpt =='assignment';
                            }
                            $meta['meta_key']='vibe_'.$_cpt.'_duration_parameter';
                        }

                        update_post_meta($id,$meta['meta_key'],wp_slash($meta['meta_value']));


                        if($meta['meta_key'] =='vibe_product_duration'){
                            update_post_meta($id,'vibe_duration',$meta['meta_value']['value']);
                            
                            update_post_meta($id,'vibe_product_duration_parameter',$meta['meta_value']['parameter']);
                        }

                    }else{
                        delete_post_meta($id,$meta['meta_key']);
                    }
                    if(in_Array($cpt,array('unit','quiz','wplms-assignment','question'))){

                        if($cpt == 'unit' && $meta['meta_key'] == 'vibe_type'){
                           $return['data']['type']=$meta['meta_value']; 
                        }
                        if($cpt == 'quiz' && $meta['meta_key'] == 'vibe_type'){
                            $return['data']['type']=$meta['meta_value']; 
                        }
                        if($cpt == 'wplms-assignment' && $meta['meta_key'] == 'vibe_assignment_submission_type'){
                            if($meta['meta_value'] == 'upload'){
                                $return['data']['type']='upload';
                            }else{
                                $return['data']['type']='textarea';
                            }
                        }

                        if($cpt == 'question' && $meta['meta_key'] == 'vibe_question_type'){
                            $return['data']['type']=$meta['meta_value'];
                        }
                    }
                }
            }
            if(!empty($body['taxonomy']) && count($body['taxonomy'])){
                $_cat_ids = array();
                foreach ($body['taxonomy'] as  $taxonomy) {
                    if(!empty($taxonomy['value'])){
                        foreach($taxonomy['value'] as $k=>$cat_id){
                            if(!is_numeric($cat_id) && strpos($cat_id, 'new_') === 0){
                                $new_cat = explode('new_',$cat_id);
                                $cid = wp_insert_term(sanitize_textarea_field($new_cat[1]),$taxonomy['taxonomy']);
                                if(is_array($cid)){
                                    $taxonomy['value'][$k] = $cid['term_id'];
                                }else{
                                    unset($taxonomy['value'][$k]);
                                }
                            }
                        }
                        wp_set_object_terms( $id, $taxonomy['value'], $taxonomy['taxonomy'] );
                    }
                }
            }

            if(function_exists('wc_get_product') && $cpt == 'product'){
                wp_set_object_terms($id, 'simple', 'product_type');
                update_post_meta($id,'vibe_wplms',1);
                $product = wc_get_product($id);
                if(!empty( $product)){
                    
                    $sale_price = $product->get_sale_price();
                  

                    $regular_price = $product->get_regular_price();

                    if(empty($regular_price)){
                        $price = $product->get_price();
                        if(empty($price)){
                            $price = 0;
                        }
                        
                    }  
                    if(!empty($sale_price)){
                        update_post_meta($id,'_price',$sale_price);
                        $product->set_price($sale_price);//to show correct value in get_price_html
                        
                    }else{
                        if(isset($regular_price)){
                            update_post_meta($id,'_price',$regular_price);
                            $product->set_price($regular_price);
                            
                        }else{
                            update_post_meta($id,'_price',$price);
                            $product->set_price($price);
                            
                        }
                    }
                    if(empty($return['data']['text'])){
                        $return['data']['text'] = $product->get_title();
                    }
                    $return['data']['text'] .= ' - '.$product->get_price_html();




                }else{
                    $return = array('status'=>false,'message' => _x('Some error occured','',''));
                }
                
            }
        }

        $return = apply_filters('wplms_create_cpt_return',$return,$body,$cpt);
        return new WP_REST_Response( $return, 200 );
    }

    function edit_element_fields($request){

        $body = json_decode($request->get_body(),true);
        $id = $body['id'];
        $cpt= $body['type'];
        if($cpt=='assignment'){
            $cpt = 'wplms-assignment';
        }
        $fields = $body['fields'];
        
        $post = get_post($body['id']);
        $post=(Array)$post;
        foreach($fields as $i=>$field){

            switch($field['from']){
                case 'post':
                    if($field['id'] == 'post_content'){
                        $fields[$i]['raw']=get_post_meta($id,'raw',true);
                    }
                    $fields[$i]['value']=$post[$field['id']];
                break;
                case 'meta':
                $meta_value = get_post_meta($id,$field['id'],true);
                if(is_serialized($meta_value)){
                    $meta_value = unserialize(($meta_value));
                }
                if($field['type'] == 'duration' && !empty($meta_value)){
                    if($cpt=='wplms-assignment'){
                        $cpt = 'assignment';
                    }
                    $param_key = 'vibe_'.$cpt.'_duration_parameter';
                    $fields[$i]['value']=array(
                        'value'=>$meta_value,
                        'parameter'=>get_post_meta($id,$param_key,true)
                    );
                }else{

                    
                    $fields[$i]['value']=$meta_value;
                    if($field['type']=='selectcpt'){
                        $title = apply_filters('wplms_selectcpt_title',get_the_title($meta_value),$meta_value,$field);//h5p uses this
                        $fields[$i]['show_value']=apply_filters('wplms_selectcpt_field_options',array('id'=>$meta_value,'text'=>get_the_title($meta_value)),$field,$id);
                    }
                    if($field['type']=='selectmulticpt'){
                        if(is_array($meta_value) && count($meta_value)){
                            $fields[$i]['show_value'] = [];
                            foreach ($meta_value as $key => $vv) {
                                $fields[$i]['show_value'][]=array('id'=>$vv,'text'=>get_the_title($vv));
                            }
                        }
                        
                    }
                    if($field['type']=='multiattachments'){
                        if(is_array($meta_value) && count($meta_value)){
                            $fields[$i]['show_value'] = [];
                            foreach ($meta_value as $key => $vv) {
                                $at = get_post($vv);
                                $fields[$i]['show_value'][]=$this->get_single_attachment($at);
                                
                            }
                        }
                        if(!empty($fields[$i]['show_value'])){
                            $fields[$i]['value'] = $fields[$i]['show_value']; 
                        }
                        
                    }
                    if($field['type'] == 'editor'){
                        $raw = get_post_meta($id,$field['id'].'_raw',true);
                        if(!empty($raw)){
                            $fields[$i]['raw'] = $raw;    
                        }
                    }

                    if($field['type']=='dynamic_quiz_questions' && !empty($meta_value) && !empty($meta_value['tags'])){
                        $fields[$i]['value'] = [];
                        foreach ($meta_value['tags'] as $key => $value) {
                            if(!empty($value)){
                                $_val = [];
                                foreach ($value as $k => $v) {
                                   $_val[] = array(
                                        'id' => $v,
                                        'text' =>  get_term( $v )->name,
                                   );
                                }
                                $fields[$i]['value'][] = array(
                                    'marks' => $meta_value['marks'][$key],
                                    'count' => $meta_value['numbers'][$key],
                                    'tagfield'=>array(
                                        
                                        'taxonomy' => 'question-tag',
                                        'value' => $value,
                                        'show_value'=>$_val,
                                    ),

                                );
                            }
                            
                        }
                        
                    }


                    if($field['type']=="practice_questions" ){
                        if(!empty($meta_value) && !empty($meta_value['type'])){
                            if($meta_value['type']=='tags'){
                                $__value = [];
                                foreach ($meta_value['value']['tags'] as $key => $value) {
                                    if(!empty($value)){
                                        $_val = [];
                                        foreach ($value as $k => $v) {
                                           $_val[] = array(
                                                'id' => $v,
                                                'text' =>  get_term( $v )->name,
                                           );
                                        }
                                        $__value[] = array(
                                            'count' => $meta_value['value']['numbers'][$key],
                                            'tagfield'=>array(
                                                
                                                'taxonomy' => 'question-tag',
                                                'value' => $value,
                                                'show_value'=>$_val,
                                            ),

                                        );
                                    }
                                    
                                }
                                $fields[$i]['value'] = array('value'=>$__value,'type' => 'tags');
                            }
                        }else{
                            if(!empty($meta_value) && is_array($meta_value)){
                                $fields[$i]['value'] = array('value'=>$meta_value,'type' => 'questions');
                            }
                        }
                        
                        
                    }


                    if($field['id']=='vibe_quiz_dynamic'){

                        if($meta_value=='S' || $meta_value=='dynamic'){
                            $fields[$i]['value'] = 'dynamic';
                        }else{
                            $fields[$i]['value'] = 'static';
                        }
                        $fields[$i]['value'] = apply_filters('wplms_quiz_type',$fields[$i]['value'],$body['id']);
                    }

                    if($field['id']=='vibe_question_options'){

                        $fields[$i]['value'] = $meta_value;
                        $fields[$i]['raw'] = get_post_meta($id,$field['id'].'_raw',true);
                    }
                }
                break;
                case 'taxonomy':
                    $fields[$i]['value'] = wp_get_object_terms($id,$field['taxonomy'],array('fields'=>'ids'));
                break;
            }
        }

        return new WP_REST_Response( array('status'=>1,'fields'=>$fields), 200 );
    }


    function get_single_attachment($post){
        $attachment_id = $post->ID;
        $data = array(
            'name' => $post->post_name,
            'id' => $attachment_id,
            'url' => wp_get_attachment_url($attachment_id)
        );
        $post_mime_type = get_post_mime_type($post);
        if(!empty($post_mime_type)){
            if(!isset($this->keyed_mime_types)){
                $this->keyed_mime_types = $this->keyed_mime_types();
            }
            if(!empty($this->keyed_mime_types[$post_mime_type])){
                $data['type'] = $this->keyed_mime_types[$post_mime_type];
            }else{
                $data['type'] = null;
            }
        }
        return $data;
    }

    function keyed_mime_types(){
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

    function create_component($request){
        $id = 0;
        $post = json_decode(file_get_contents('php://input'));
        $body = $request->get_body();
        $body = json_decode($body,true);
        $cpt= $request->get_param('cpt');
        if($cpt=='assignment'){
            $cpt = 'wplms-assignment';
        }
        $return = array();
        $name=$body['name'];
        if(!empty($name ) && !empty($cpt) && !empty($this->user->id) && is_numeric($this->user->id)){
            if(user_can($this->user->id,'edit_posts')){
                if($cpt == 'groups'){

                    $group_settings = array(
                        'creator_id' => $this->user->id,
                        'name' => $name,
                        'description' => $body['description'],
                        'status' => $body['privacy'],
                        'date_created' => current_time('mysql')
                    );

                    $group_settings = apply_filters('wplms_front_end_group_vars',$group_settings);

                    $new_group_id = groups_create_group( $group_settings);

                    if(is_numeric($new_group_id)){
                        groups_update_groupmeta( $new_group_id, 'total_member_count', 1 );
                        groups_update_groupmeta( $new_group_id, 'last_activity', gmdate( "Y-m-d H:i:s" ) );
                    
                        $return = array('status'=>true,'data'=>$new_group_id);
                    }else{
                        $return = array('status'=>false,'message'=>_x('Group could not be created.','api','wplms'));
                    }
                    
                }else{

                    if(function_exists('bbp_insert_forum')){

                        $new_forum_id = bbp_insert_forum( apply_filters('wplms_front_end_forum_vars',array(
                            'post_parent'  => bbp_get_group_forums_root_id(),
                            'post_title'   => $name,
                            'post_content' => (!empty($body['description'])?$body['description']:$name),
                            'post_status'  => (!empty($body['privacy'])?$body['privacy']:'public'),
                            'post_author' => $this->user->id
                        ) ));

                        if ( $body['privacy'] == 'private' ) {
                            bbp_privatize_forum( $new_forum_id );
                        } elseif ( $body['privacy'] == 'hidden' ) {
                            bbp_hide_forum( $new_forum_id );
                        } else {
                            bbp_publicize_forum( $new_forum_id );
                        }

                        if(is_numeric($new_forum_id)){
                            bbp_add_user_forum_subscription( $this->user->id, $new_forum_id);
                        }
                        
                    }

                    if(!empty($new_forum_id)){
                        $return = array('status'=>true,'data'=>$new_forum_id);
                    }else{
                        $return = array('status'=>false,'message'=>_x('Sorry you do not have capability to edit posts!','','wplms'));
                    }
                }
            }else{
                $return = array('status'=>false,'message'=>_x('Sorry you do not have capability to edit posts!','','wplms'));
            }
        }
        if(!empty($id)){
            if(!empty($body['meta']) && count($body['meta'])){
                foreach ($body['meta'] as  $meta) {
                    if(!empty($meta['meta_value']) && !empty($meta['meta_key'])){
                        if($meta['meta_key'] == 'raw'){
                            $meta['meta_value']=wp_slash($meta['meta_value']);
                        }

                        update_post_meta($id,$meta['meta_key'],$meta['meta_value']);
                    }else{
                        delete_post_meta($id,$meta['meta_key']);
                    }
                    do_action('wplms_front_end_field_save',$id,$meta);
                }
            }
        }

        return new WP_REST_Response( $return, 200 );
    }

    function get_taxonomy($request){
        $post = json_decode(file_get_contents('php://input'));
        $body = $request->get_body();
        $body = json_decode($body);
        $return = array();
        $taxonomy=$body->taxonomy;
        $posts = array();

        if(!empty($body ) && !empty($taxonomy) && !empty($this->user->id) && is_numeric($this->user->id)){
            $terms = get_terms( $taxonomy, array('hide_empty' => false,'orderby'=>'name','order'=>'ASC') );
            if(!empty($terms) && is_array($terms)){
                foreach ($terms as $key=>$term ){
                    
                    $posts[] = array('id'=>$term->term_id,'text'=>$this->get_taxonomy_name('',$term,$terms));
                      
                    
                }
            }
            wp_reset_postdata();
        }else{
            $return = array('status'=>false,'message'=>_x('Sorry Something went wrong or invalid post type','','wplms'));
        }

        if(empty($posts)){
            return new WP_REST_Response( array('status'=>false,'message'=>_x('Sorry no results found!Try another search keyword!','API request','wplms')), 200 );
        }
        return new WP_REST_Response( array('status'=>true,'posts'=>$posts), 200 );
    }

    function get_taxonomy_name($name='',$term,$terms){
        if(!empty($term->parent)){
            
            $name .= $this->get_term_name_from_taxonomies($term->parent,$terms).' > '.$term->name;
        }else{
            $name .= $term->name;
        }
        return $name;
    }

    function  get_term_name_from_taxonomies($term_id,$terms){
        if(!empty($terms)){
            foreach ($terms as $key => $tt) {
                if($tt->term_id==$term_id){
                    return $this->get_taxonomy_name('',$tt,$terms);
                }
            }
        }
    }

    function selectcpt($request){

        $cpt= $request->get_param('cpt');
        $body = json_decode($request->get_body());
        $return = array();
        if($cpt=='assignment'){
            $cpt = 'wplms-assignment';
        }
        $cpt= str_replace('hyphen', '-', $cpt);
        $results = apply_filters('wplms_selectcpt_field_results',array(),$body->search,$cpt,$request,$this->user);
        if(empty($results) && !empty($body ) && !empty($cpt) && !empty($this->user->id) && is_numeric($this->user->id)){
            
            if($cpt == 'groups'){
                if(function_exists('groups_get_group')){

                    $args=apply_filters('selectcpt_wplms_groups',array(
                    'per_page'=>999,
                    'search_terms'=>$body->search,
                    'search_columns'=>['name']
                    ),$this->user,$request);
                    

                    $vgroups =  groups_get_groups($args);
                    $return = array();
                    foreach($vgroups['groups'] as $vgroup){
                        $results[] = array('id'=>$vgroup->id,'text'=>$vgroup->name,
                            'link'=>bp_core_get_user_domain($this->user->id).'#component=groups&action=view&id='.$vgroup->id,'permalink'=>bp_get_group_permalink( $vgroup ));
                    }
                }
            }else{
                $args = array(
                    'post_type'=>$cpt,
                    'posts_per_page'=>99,
                    's'=>$body->search,
                );
                

                $args = apply_filters('wplms_frontend_cpt_query',$args,$this->user);
                $query = new WP_Query($args);
                
                if($query->have_posts()){
                    while($query->have_posts()){
                        $query->the_post();
                        global $post;
                        $preturn = array('id'=>$post->ID,'text'=>$post->post_title,'link'=>get_permalink($post->ID));
                        if($cpt == 'unit'){
                            $type = get_post_meta($post->ID,'vibe_type',true);
                            if(empty($type) || $type == 'unit'){$type = 'general';}
                            if($type == 'text-document'){$type = 'general';}
                            if($type == 'play'){$type = 'video';}
                            if($type == 'music-file-1'){$type = 'audio';}
                            if($type == 'podcast'){$type = 'audio';}

                            $preturn['type']=$type;
                        }
                        if($cpt == 'quiz'){
                            $preturn['type'] = wplms_get_quiz_type($post->ID);
                        }
                        if($cpt == 'wplms-assignment'){
                            $preturn['type']=get_post_meta($post->ID,'vibe_assignment_submission_type',true);
                            if(empty($preturn['type'])){
                                $preturn['type']='textarea';
                            }
                        }
                        if($cpt == 'question'){
                            
                            $type = get_post_meta($post->ID,'vibe_question_type',true);
                            if(empty($type)){$type = 'multiple';}
                            $preturn['type']=$type;
                        }

                        if($cpt == 'product'){
                            $product = wc_get_product($post->ID);
                            $preturn['text'] .= ' - '.$product->get_price_html();

                            $preturn['fields'] = apply_filters('wplms_product_fields',array(
                                'ID'=>$post->ID,
                                'post_title'=>$post->post_title,
                                'meta'=>array(
                                    array('meta_key'=>'_price','meta_value'=>get_post_meta($post->ID,'_price',true)),
                                    array('meta_key'=>'vibe_subscription','meta_value'=>get_post_meta($post->ID,'vibe_subscription',true)),
                                    array('meta_key'=>'vibe_duration','meta_value'=>array('value'=>get_post_meta($post->ID,'vibe_duration',true),'parameter'=>get_post_meta($post->ID,'vibe_duration_parameter',true))
                                    )
                                )
                            ));
                        }
                        $results[] = $preturn;
                    }
                }
                wp_reset_postdata();
            }
        }else{
            $return = array('status'=>false,'message'=>_x('Sorry Something went wrong or invalid post type','','wplms'));
        }

        if(empty($results)){
            return new WP_REST_Response( array('status'=>false,'message'=>_x('Sorry not results found!Try another search keyword!','no results in search api request','wplms')), 200 );
        }
        return new WP_REST_Response( array('status'=>true,'posts'=>$results), 200 );
    }

    function get_group_component($request){
        $body = json_decode($request->get_body(),true);
        if(!function_exists('groups_get_group')){
            return new WP_REST_Response( array('status'=>false,'message'=>__('Groups not active','vibebp')), 200 );
        }
        $group = groups_get_group(array('group_id'=>$body['id'],'populate_extras'=>true));

        if(!empty($group)){
            return new WP_REST_Response( array('status'=>true,'group'=>apply_filters('wplms_get_group',array(
                'id'=>$group->id,
                'image'=>bp_core_fetch_avatar(array(
                            'item_id' => $group->id,
                            'object'  => 'group',
                            'type'     => 'full',
                            'html'    => false
                        )),
                'title'=>$group->name,
                'description'=>$group->description,
                'status'=>$group->status,
                'type'=>bp_groups_get_group_type( $group->id ),
                'member_count'=>groups_get_groupmeta($group->id,'total_member_count',true),
                'creator_id'=>$group->creator_id,
                'last_activity'=>strtotime(groups_get_groupmeta($group->id,'last_activity',true)),
            ))), 200 );
        }
        return new WP_REST_Response( array('status'=>false,'message'=>__('Could not load group','vibebp')), 200 );
    }

    function get_forum_component($request){
        $body = json_decode($request->get_body(),true);
        if(!post_type_exists('forum')){
            return new WP_REST_Response( array('status'=>false,'message'=>__('Forums not active','vibebp')), 200 );
        }
        $id = $body['id'];
        $post = get_post($id);
        if(!empty($post)){

            return new WP_REST_Response( array('status'=>true,'forum'=>apply_filters('wplms_get_forum',array(
                'id'=>$id,
                'title'=>$post->post_title,
                'description'=>$post->post_content,
                'private'=>bbp_is_forum_private( $id ),
                'topic_count'=>bbp_get_forum_topic_count( $id, false, true ),
                'forums_count'=>bbp_get_forum_subforum_count( $id, true ),
                'last_active'=>bbp_get_forum_last_active_time($id)
            ))));
        }
                
        
           
        return new WP_REST_Response( array('status'=>false,'message'=>__('Could not load forum','vibebp')), 200 );
    }

    function get_quiz_questions($request){
        $body = json_decode($request->get_body(),true);
        $questions = array();
        if(!empty($body['questions'])){
            
            foreach($body['questions'] as $question_id){
                $questions[]=array(
                    'id'=> $question_id,
                    'text'=>get_the_title($question_id),
                    'type'=>get_post_meta($question_id,'vibe_question_type',true)
                );
            }
        }
        return new WP_REST_Response( array('status'=>true,'questions'=>$questions), 200 );
    }

    function get_packages($request){


        $body = json_decode($request->get_body(),true);

        $wplmsthis = WPLMS_ZIP_UPLOAD_HANDLER::init();

        $dirs = $wplmsthis->getDirs();
        $packages = array();
        if(count($dirs)){
            $uploadDirUrl=$wplmsthis->getUploadsUrl();
            $uploadDirpath=$wplmsthis->getUploadsPath();
            
            $total = count($dirs);
            

            $lower_bound=($body['paged']-1)*20;
            
            $dirs = array_slice($dirs, $lower_bound, 20);
            
               
            foreach ($dirs as $i=>$dir){
                extract($dir);
                $package_name = str_replace("_"," " ,$dir);

                if(!empty($body['s'])){ 
                    $total=0;
                    if(strpos($package_name,$body['s']) != false){
                        $packages[] = array(
                            'name'=>$package_name,
                            'type'=>'1.1',
                            'package_type'=>'1.1',
                            'folderpath'=> $uploadDirpath.$dir,
                            'path'=>$uploadDirUrl.$dir."/".$file,
                        );
                        $total++;
                    }
                }else{
                    $packages[] = array(
                        'name'=>$package_name,
                        'type'=>'1.1',
                        'package_type'=>'1.1',
                        'folderpath'=> $uploadDirpath.$dir,
                        'path'=>$uploadDirUrl.$dir."/".$file,
                    );
                }
            }
        }
        if(!empty($packages)){
            $return = array('status'=>1,'packages'=> $packages,'total'=>$total);
        }else{
            $return = array('status'=>0,'message'=> __('No packages available','wplms'));
        }

        return new WP_REST_Response( $return, 200 );
    }

    function upload_package($request){
        $body = json_decode(stripslashes($_POST['body']),true);
        $return = array('status'=>0,'message'=> __('Missing Details','wplms'));

        $wplmsthis = WPLMS_ZIP_UPLOAD_HANDLER::init();

        if(!empty($_FILES['file'])){

            $file = $_FILES['file']['tmp_name'];
            $dir = explode(".",$_FILES['file']['name']);
            $dir[0] = str_replace(" ","_",$dir[0]);
            $target = $wplmsthis->getUploadsPath().$dir[0];
            $index = count($dir) -1;

            if (!isset($dir[$index]) || $dir[$index] != "zip"){
                return new WP_REST_Response(array('status'=>0,'message'=>__('The Upload file must be zip archive','wplms')), 200);
            }else{
                while(file_exists($target)){
                    $r = rand(1,10);
                    $target .= $r;
                    $dir[0] .= $r;
                }
                if (!empty($file)){

                    $arr = $wplmsthis->extractZip($file,$target,$dir[0]);
                    if($arr[0] != 'uploaded'){
                        $return['message']=$arr[0];
                        $wplmsthis->rrmdir($target);
                    }else{

                        $return['status']=1;
                        $return['package']=array(
                            'package_type'=>$body['args']['package_type'],
                            'type'=>$body['args']['package_type'],
                            'folderpath'=> $arr[4],
                            'path'=>$arr[1],
                            'name'=>$arr[2],
                            'file'=>$arr[3]
                        );

                        unlink($file);
                    }
                }else{
                    $return['message'] = __('File too big','wplms');
                }
            }

        }else{
            $return=array('status'=> 0,'message'=>_x('File not found','wplms'));
        }
        return new WP_REST_Response( $return, 200 );
    }

    function delete_package($request){
        $body = json_decode($request->get_body(),true);
        $wplmsthis = WPLMS_ZIP_UPLOAD_HANDLER::init();
        $return = array('status'=>0,'message'=> __('Could not delete','wplms'));
        if(!empty($body['package']) && !empty($body['package']['folderpath'])){

            $wplmsthis->rrmdir($body['package']['folderpath']);
            $return=array('status'=> 1,'message'=>_x('package deleted','wplms'));
        }
        return new WP_REST_Response( $return, 200 );
    }

    function get_user_permissions_check($request){
        
        $body = json_decode($request->get_body(),true);

        if(!empty($body['token'])){
            
            $this->user = apply_filters('vibebp_api_get_user_from_token','',$body['token']);
            if(!empty($this->user)){
                return true;
            }
        }

        return false;
    }

    function get_create_user_permissions_check($request){
        $body = json_decode($request->get_body(),true);
   
        if(empty($body)){
           $body =  json_decode(stripslashes($_POST['body']),true);
        }

        if(!empty($body['token'])){
            
            $this->user = apply_filters('vibebp_api_get_user_from_token','',$body['token']);

            if(!empty($this->user) && user_can($this->user->id,'edit_posts')){
                return true;
            }
        }

        return false;
    }

    function get_post_user_permissions_check($request){
        $body = json_decode(stripslashes($_POST['body']),true);
        if(!empty($body['token'])){
            
            $this->user = apply_filters('vibebp_api_get_user_from_token','',$body['token']);
            if(!empty($this->user)){
                return true;
            }
        }

        return false;
    }


    function generate_token($user_id,$client_id){

        $access_token = wp_generate_password(40);
        do_action( 'wplms_auth_set_access_token', array(
            'access_token' => $access_token,
            'client_id'    => $client_id,
            'user_id'      => $user_id
        ) );

        $expires = time()+86400*7;
        $expires = date( 'Y-m-d H:i:s', $expires );

        $tokens = get_user_meta($user_id,'access_tokens',true);
        if(empty($tokens)){$tokens = array();}else if(in_array($access_token,$tokens)){$k = array_search($access_token, $tokens);unset($tokens[$k]);delete_user_meta($user_id,$access_token);
        }
        
        $tokens[] = $access_token;
        update_user_meta($user_id,'access_tokens',$tokens);

        $token = array(
            'access_token'=> $access_token,
            'client_id' => $client_id,
            'user_id'   =>  $user_id,
            'expires'   => $expires,
            'scope'     => $scope,
            );
        
        update_user_meta($user_id,$access_token,$token);

        return $token;
    }


}
WPLMS_Create_Course_Api::init();
