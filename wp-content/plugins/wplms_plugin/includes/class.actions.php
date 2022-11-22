<?php
/**
 * Action functions for WPLMS 4
 *
 * @author      VibeThemes
 * @category    Admin
 * @package     WPLMS Plugin
 * @version     4.0
 */

 if ( ! defined( 'ABSPATH' ) ) exit;

class WPLMS_4_Actions{

    public static $instance;
    public static function init(){
    if ( is_null( self::$instance ) )
        self::$instance = new WPLMS_4_Actions();

        return self::$instance;
    }

    private function __construct(){

    	add_action('wplms_evaluate_quiz',array($this,'clear_cached_quiz'),10,4);
        add_action('template_redirect',array($this,'load_wplms_course_layout'),0);
        add_filter('the_content',array($this,'course_content'),1);
        add_action('init',array($this,'add_elementor_support'));

        add_action('wp_head',array($this,'add_taxonomies_json'),99);
        add_action('wp_enqueue_scripts',array($this,'enqueue_general'));

        add_action( 'add_meta_boxes', array($this,'course_layout_card'));
        add_action( 'save_post_course-layout', array($this,'save_course_cat_card' ),10,1);
        add_action( 'save_post_course', array($this,'save_course_layout_card' ),10,1);

        add_action('wp_head',array($this,'load_ajax_menus'));
        add_action( 'wp_ajax_load_new_menus',array($this,'load_new_menus' ));
        add_action( 'wp_ajax_nopriv_load_new_menus',array($this,'load_new_menus' ));

        add_filter('template_redirect',array($this,'check_iframe_unit'));

        add_action('init',array($this,'check_theme_hooks'));

        add_action('after_setup_theme',function(){
            if(!function_exists('wpa83704_adjust_the_wp_menu')){
                add_action( 'admin_menu', array($this,'wpa83704_adjust_the_wp_menu'), 999 );
                add_action( 'course-cat_add_form_fields', array( $this, 'add_category_fields' ));
                add_action( 'course-cat_edit_form_fields', array( $this, 'edit_category_fields' ));
                add_action( 'created_term', array($this,'save_category_meta'), 10, 2 );
                add_action( 'edited_term', array($this,'save_category_meta'), 10, 2 );
            }
        });

        add_action('bp_course_record_instructor_commission_custom',array($this,'record_credits_commission'),10,4);
    }


        // Adding Course Categories in LMS Menu
    function wpa83704_adjust_the_wp_menu() {
        add_submenu_page(
            'lms',
            __('Course Categories','wplms'),
            __('Course Category','wplms'),
            'edit_posts',
            'edit-tags.php?post_type=course&taxonomy=course-cat'
        );
        add_submenu_page(
            'lms',
            __('Quiz type','wplms'),
            __('Quiz type','wplms'),
            'edit_posts',
            'edit-tags.php?post_type=quiz&taxonomy=quiz-type'
        );
        add_submenu_page(
            'lms',
            __('Unit Tag','wplms'),
            __('Unit Tag','wplms'),
            'edit_posts',
            'edit-tags.php?post_type=unit&taxonomy=module-tag'
        );
        if(function_exists('vibe_get_option')){
            $level = vibe_get_option('level');
            if(isset($level) && $level){
              add_submenu_page(
                'lms',
                __('Levels','wplms'),
                __('Level','wplms'),
                'edit_posts',
                'edit-tags.php?post_type=course&taxonomy=level'
            );
            }
            $location = vibe_get_option('location');
            if(isset($location) && $location){
              add_submenu_page(
                'lms',
                __('Locations','wplms'),
                __('Location','wplms'),
                'edit_posts',
                'edit-tags.php?post_type=course&taxonomy=location'
            );
            }
            $linkage = vibe_get_option('linkage');
            if(isset($linkage) && $linkage){
              add_submenu_page(
                'lms',
                __('Linkage','wplms'),
                __('Linkage','wplms'),
                'edit_posts',
                'edit-tags.php?post_type=course&taxonomy=linkage'
            );
            }
        }
    }

    /*
    *   Add Course Category Featured thubmanils
    *   Use WP 4.4 Term meta for storing information
    *   @reference : WooCommerce (GPLv2)
    */
    function add_category_fields(){
        $default ='';
        if(function_exists('vibe_get_option')){
            $default = vibe_get_option('default_avatar');
        }
        

        ?>
        <div class="form-field">
        <label><?php _e( 'Display Order', 'vibe' ); ?></label>
        <input type="number" name="course_cat_order" id="course_cat_order" value="" />
        </div>
        <div class="form-field">
            <label><?php _e( 'Thumbnail', 'vibe' ); ?></label>
            <div id="course_cat_thumbnail" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url( $default ); ?>" width="60px" height="60px" /></div>
            <div style="line-height: 60px;">
                <input type="hidden" id="course_cat_thumbnail_id" name="course_cat_thumbnail_id" />
                <button type="button" class="upload_image_button button"><?php _e( 'Upload/Add image', 'vibe' ); ?></button>
                <button type="button" class="remove_image_button button"><?php _e( 'Remove image', 'vibe' ); ?></button>
            </div>
            <script type="text/javascript">
                if ( ! jQuery( '#course_cat_thumbnail_id' ).val() ) {
                    jQuery( '.remove_image_button' ).hide();
                }
                // Uploading files
                var file_frame;

                jQuery( document ).on( 'click', '.upload_image_button', function( event ) {
                    event.preventDefault();
                    // If the media frame already exists, reopen it.
                    if ( file_frame ) {
                        file_frame.open();
                        return;
                    }

                    // Create the media frame.
                    file_frame = wp.media.frames.downloadable_file = wp.media({
                        title: '<?php _e( "Choose an image", "vibe" ); ?>',
                        button: {
                            text: '<?php _e( "Use image", "vibe" ); ?>'
                        },
                        multiple: false
                    });
                    file_frame.on( 'select', function() {
                        var attachment = file_frame.state().get( 'selection' ).first().toJSON();
                        jQuery( '#course_cat_thumbnail_id' ).val( attachment.id );
                        if( attachment.sizes){
                            if(   attachment.sizes.thumbnail !== undefined  ) url_image=attachment.sizes.thumbnail.url; 
                            else if( attachment.sizes.medium !== undefined ) url_image=attachment.sizes.medium.url;
                            else url_image=attachment.sizes.full.url;
                        }

                        jQuery( '#course_cat_thumbnail' ).find( 'img' ).attr( 'src', url_image );
                        
                        jQuery( '.remove_image_button' ).show();
                    });
                    file_frame.open();
                });

                jQuery( document ).on( 'click', '.remove_image_button', function() {
                    jQuery( '#course_cat_thumbnail' ).find( 'img' ).attr( 'src', '<?php echo esc_js( $default ); ?>' );
                    jQuery( '#course_cat_thumbnail_id' ).val( '' );
                    jQuery( '.remove_image_button' ).hide();
                    return false;
                });

            </script>
            <div class="clear"></div>
        </div>
        <?php
    }
    /*
    *   Edit Course Category Featured thubmanils
    *   Use WP 4.4 Term meta for storing information
    *   @reference : WooCommerce (GPLv2)
    */
    function edit_category_fields($term){


        $thumbnail_id = absint( get_term_meta( $term->term_id, 'course_cat_thumbnail_id', true ) );
        $order = get_term_meta( $term->term_id, 'course_cat_order', true ); 
        if ( $thumbnail_id ) {
            $image = wp_get_attachment_thumb_url( $thumbnail_id );
        } else {
            $default ='';
            if(function_exists('vibe_get_option')){
                $default = vibe_get_option('default_avatar');
            }
            
            $image = $default;
        }

        ?>
        <tr class="form-field">
            <th scope="row" valign="top"><label><?php _e( 'Display Order', 'vibe' ); ?></label></th>
            <td><input type="number" name="course_cat_order" id="course_cat_order" value="<?php echo (empty($order)?0:$order); ?>" /></td>
        </tr>
        <tr class="form-field">
            <th scope="row" valign="top"><label><?php _e( 'Thumbnail', 'vibe' ); ?></label></th>
            <td>
                <div id="course_cat_thumbnail" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url( $image ); ?>" width="60px" height="60px" /></div>
                <div style="line-height: 60px;">
                    <input type="hidden" id="course_cat_thumbnail_id" name="course_cat_thumbnail_id" value="<?php echo vibe_sanitizer($thumbnail_id,'text'); ?>" />
                    <button type="button" class="upload_image_button button"><?php _e( 'Upload/Add image', 'vibe' ); ?></button>
                    <button type="button" class="remove_image_button button"><?php _e( 'Remove image', 'vibe' ); ?></button>
                </div>
                <script type="text/javascript">

                    // Only show the "remove image" button when needed
                    if ( '0' === jQuery( '#course_cat_thumbnail_id' ).val() ) {
                        jQuery( '.remove_image_button' ).hide();
                    }

                    // Uploading files
                    var file_frame;

                    jQuery( document ).on( 'click', '.upload_image_button', function( event ) {

                        event.preventDefault();

                        // If the media frame already exists, reopen it.
                        if ( file_frame ) {
                            file_frame.open();
                            return;
                        }

                        // Create the media frame.
                        file_frame = wp.media.frames.downloadable_file = wp.media({
                            title: '<?php _e( "Choose an image", "vibe" ); ?>',
                            button: {
                                text: '<?php _e( "Use image", "vibe" ); ?>'
                            },
                            multiple: false
                        });

                        // When an image is selected, run a callback.
                        file_frame.on( 'select', function() {
                            var attachment = file_frame.state().get( 'selection' ).first().toJSON();

                            jQuery( '#course_cat_thumbnail_id' ).val( attachment.id );

                            if( attachment.sizes){
                                if(   attachment.sizes.thumbnail !== undefined  ) url_image=attachment.sizes.thumbnail.url; 
                                else if( attachment.sizes.medium !== undefined ) url_image=attachment.sizes.medium.url;
                                else url_image=attachment.sizes.full.url;
                            }

                            jQuery( '#course_cat_thumbnail' ).find( 'img' ).attr( 'src', url_image );
                            jQuery( '.remove_image_button' ).show();
                        });

                        // Finally, open the modal.
                        file_frame.open();
                    });

                    jQuery( document ).on( 'click', '.remove_image_button', function() {
                        jQuery( '#course_cat_thumbnail' ).find( 'img' ).attr( 'src', '<?php echo esc_js( $image ); ?>' );
                        jQuery( '#course_cat_thumbnail_id' ).val( '' );
                        jQuery( '.remove_image_button' ).hide();
                        return false;
                    });

                </script>
                <div class="clear"></div>
            </td>
        </tr>
        <?php
    }


    function save_category_meta( $term_id, $tt_id ){
        global $wpdb;
        if( isset( $_POST['course_cat_thumbnail_id'] )){
            $thumb_id = intval( $_POST['course_cat_thumbnail_id'] );
            update_term_meta( $term_id, 'course_cat_thumbnail_id', $thumb_id );
        }
        if( isset( $_POST['course_cat_order'] ) &&is_numeric($_POST['course_cat_order'])){
            update_term_meta( $term_id, 'course_cat_order', $_POST['course_cat_order'] );
            $wpdb->update($wpdb->terms, array('term_group' => $_POST['course_cat_order']), array('term_id'=>$term_id));
        }
    }
    function check_theme_hooks(){
        if(class_exists('WPLMS_Filters')){
            $init = WPLMS_Filters::init();
            remove_action('pre_get_posts', array($init,'wplms_instructor_privacy_filter_attachments'));
        }
    }

    function check_iframe_unit($template){

        if(function_exists('vibebp_expand_token') && class_exists('WPLMS_Actions') &&  is_singular(array('unit','question'))){
            if(!empty($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['token'])){
                $token = $_GET['token'];
                $user = vibebp_expand_token($token); 
                if(!empty($user)){
                    $user = $user['data']->data->user;
                    if(bp_course_is_member($_GET['id'],$user->id)){
                        $actions = WPLMS_Actions::init();
                        remove_action( 'template_redirect',array($actions,'vibe_check_access_check'),11);
                        add_filter( 'show_admin_bar' , '__return_false' );
                        add_action('template_include',function(){
                            ob_start();
                            ?>
                                <!DOCTYPE html>
                                <html <?php language_attributes(); ?>>
                                <head>
                                <meta charset="<?php bloginfo( 'charset' ); ?>">
                                <?php
                                    wp_head();
                                ?>
                                </head>
                                <body <?php body_class(); ?>>
                                <?php
                                if ( have_posts() ) : while ( have_posts() ) : the_post();
                                    the_content();
                                endwhile;
                                endif;
                                ?>
                                <?php
                                wp_footer(); 
                                ?>
                                </body>
                                </html>
                            <?php
                            echo ob_get_clean();
                            exit();
                        });
                    }
                    
                } 
            }
        }

        return $template;
    }

    function load_new_menus(){
        if(!wp_verify_nonce($_POST['security'],'menus_security')){
            echo json_encode(array('message'=>_x('Security check failed','','wplms')));
            die();
        }

        if(class_exists('Wplms_tips')){
            $tips = Wplms_tips::init();
            $user = vibebp_expand_token($_POST['token']); //-- Expand token
            if(!empty($user) ){
                if(!empty($user['data']->data->user->caps) && $user['data']->data->user->caps){
                    $role = $this->check_user_role($user['data']->data->user->caps);
                    if($role=='student' && !empty($tips->settings['enable_student_menus'])){
                        $args = array(
                             'theme_location'  => 'student-main-menu',
                             'container'       => 'nav',
                             'menu_class'      => 'menu',
                             'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s<li><a id="new_searchicon"><i class="vicon vicon-search"></i></a></li></ul>',
                             'walker'          => new vibe_walker,
                             'fallback_cb'     => 'vibe_set_menu'
                         );
                        ob_start();
                        wp_nav_menu( $args ); 
                        $main = ob_get_clean();

                        $args = array(
                             'theme_location'  => 'student-top-menu',
                             'container'       => '',
                                'menu_class'      => 'topmenu',
                                'fallback_cb'     => 'vibe_set_menu',
                         );
                        ob_start();
                        wp_nav_menu( $args ); 
                        $top = ob_get_clean();



                        $args = array(
                             'theme_location'  => 'student-mobile-menu',
                             'container'       => '',
                                'menu_class'      => 'sidemenu',
                                'items_wrap' => '<div class="mobile_icons"><a id="mobile_searchicon"><i class="vicon vicon-search"></i></a>'.( (function_exists('WC')) ?'<a href="'.esc_url( wc_get_cart_url() ).'"><span class="vicon vicon-shopping-cart"><em>'.WC()->cart->cart_contents_count.'</em></span></a>':'').'</div><ul id="%1$s" class="%2$s">%3$s</ul>',
                                'fallback_cb'     => 'vibe_set_menu',
                         );
                        ob_start();
                        wp_nav_menu( $args ); 
                        $mobile = ob_get_clean();

                    }elseif($role=='instructor' && !empty($tips->settings['enable_instructor_menus'])){
                        $args = array(
                             'theme_location'  => 'instructor-main-menu',
                             'container'       => 'nav',
                             'menu_class'      => 'menu',
                             'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s<li><a id="new_searchicon"><i class="vicon vicon-search"></i></a></li></ul>',
                             'walker'          => new vibe_walker,
                             'fallback_cb'     => 'vibe_set_menu'
                         );
                        ob_start();
                        wp_nav_menu( $args ); 
                        $main = ob_get_clean();



                        $args = array(
                             'theme_location'  => 'instructor-top-menu',
                             'container'       => '',
                                'menu_class'      => 'topmenu',
                                'fallback_cb'     => 'vibe_set_menu',
                         );
                        ob_start();
                        wp_nav_menu( $args ); 
                        $top = ob_get_clean();


                        $args = array(
                             'theme_location'  => 'instructor-mobile-menu',
                             'container'       => '',
                                'menu_class'      => 'sidemenu',
                                'items_wrap' => '<div class="mobile_icons"><a id="mobile_searchicon"><i class="vicon vicon-search"></i></a>'.( (function_exists('WC')) ?'<a href="'.esc_url( wc_get_cart_url() ).'"><span class="vicon vicon-shopping-cart"><em>'.WC()->cart->cart_contents_count.'</em></span></a>':'').'</div><ul id="%1$s" class="%2$s">%3$s</ul>',
                                'fallback_cb'     => 'vibe_set_menu',
                         );
                        ob_start();
                        wp_nav_menu( $args ); 
                        $mobile = ob_get_clean();
                    }else{
                        $args = array(
                             'theme_location'  => 'main-menu',
                             'container'       => 'nav',
                             'menu_class'      => 'menu',
                             'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s<li><a id="new_searchicon"><i class="vicon vicon-search"></i></a></li></ul>',
                             'walker'          => new vibe_walker,
                             'fallback_cb'     => 'vibe_set_menu'
                         );
                        ob_start();
                        wp_nav_menu( $args ); 
                        $main = ob_get_clean();

                        $args = array(
                             'theme_location'  => 'top-menu',
                             'container'       => '',
                                'menu_class'      => 'topmenu',
                                'fallback_cb'     => 'vibe_set_menu',
                         );
                        ob_start();
                        wp_nav_menu( $args ); 
                        $top = ob_get_clean();


                        $args = array(
                             'theme_location'  => 'mobile-menu',
                             'container'       => '',
                                'menu_class'      => 'sidemenu',
                                'items_wrap' => '<div class="mobile_icons"><a id="mobile_searchicon"><i class="vicon vicon-search"></i></a>'.( (function_exists('WC')) ?'<a href="'.esc_url( wc_get_cart_url() ).'"><span class="vicon vicon-shopping-cart"><em>'.WC()->cart->cart_contents_count.'</em></span></a>':'').'</div><ul id="%1$s" class="%2$s">%3$s</ul>',
                                'fallback_cb'     => 'vibe_set_menu',
                         );
                        ob_start();
                        wp_nav_menu( $args ); 
                        $mobile = ob_get_clean();
                    }
                    //regex ka khel
                    $pages=get_site_option('bp-pages');
                    if(is_array($pages) && isset($pages['members'])){
                        $members_page_id =$pages['members'];
                        $slug = get_post_field('post_name',$members_page_id);
                        $pattern = "/{$slug}\/(.*?)\//";
                        $main = preg_replace($pattern, $slug.'/'.$user['data']->data->user->username.'/', $main);
                        $top = preg_replace($pattern, $slug.'/'.$user['data']->data->user->username.'/', $top);
                        $mobile = preg_replace($pattern, $slug.'/'.$user['data']->data->user->username.'/', $mobile);
                    }

                    echo json_encode(array(
                        'main_menu'=>$main,
                        'top_menu'=>$top,
                        'mobile_menu'=>$mobile,
                        'role'=>$role));
                    
                }
            }
        }
        die();
    }

    function load_ajax_menus(){
        if(!function_exists('bp_current_component'))
            return;

        if(class_exists('Wplms_tips')){
            $tips = Wplms_tips::init();
            if(empty($tips->settings['enable_student_menus']) && empty($tips->settings['enable_instructor_menus']))
                return;
        }
        
        if(!empty(bp_current_component())){
            ?>
            <script>
                let url = window.location.href;
                let arr = url.split('#');
                if(arr && arr.length < 2 && '<?php echo bp_current_component()?>' !== 'profile'){
                    url = arr[0]+'#component=<?php echo bp_current_component()?>';
               
                    window.location.href = url;
                }
                
            </script>
            <?php
        }
        
        ?>
        <script>
            if(typeof localforage == 'object'){
                localforage.getItem('bp_login_token').then(function(token){ 
                    if(token){ //Check if token exists in browser, means he is logged in
                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', ajaxurl);
                        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                        xhr.onload = function() {
                            if (xhr.status === 200) {
                                let check = JSON.parse(xhr.responseText);
                                if(check && check.hasOwnProperty('role')){
                                    let header =document.querySelector('header');
                                    if(header){
                                        document.querySelector('header nav').outerHTML = check.main_menu;
                                        if(header.classList && header.classList.contains('univ')){
                                            if(document.querySelector('#headertop .topmenu:first-child'))
                                                document.querySelector('#headertop .topmenu:first-child').outerHTML = check.top_menu;
                                        }else{
                                            if(document.querySelector('#headertop .topmenu:last-child'))
                                                document.querySelector('#headertop .topmenu:last-child').outerHTML = check.top_menu;
                                        }
                                    }
                                    
                                    if(document.querySelector('.pagesidebar .mobile_icons')){

                                        document.querySelector('.pagesidebar .mobile_icons').remove();
                                        document.querySelector('.pagesidebar .sidemenu').outerHTML = check.mobile_menu;
                                    }
                                    
                                    if(document.querySelector('#new_searchicon')){
                                      document.querySelector('#new_searchicon').addEventListener('click',function(event) {
                                          document.querySelector('body').classList.add('search_active');
                                      });
                                    }
                                    if(document.querySelector('#mobile_searchicon')){
                                      document.querySelector('#mobile_searchicon').addEventListener('click',function(event) {
                                          document.querySelector('body').classList.add('search_active');
                                      });
                                    }
                                    
                                    
                                }
                            }
                        };
                        xhr.send(encodeURI('action=load_new_menus&token='+token+'&security=<?php echo wp_create_nonce('menus_security');?>')); //Send Ajax call to WordPress, Token will identify user 
                    }
                });
            }
        </script>
        <?php
    }

    function check_user_role($caps){
        if(!empty($caps)){
            if(is_object($caps))
                $caps = (array)$caps;
            if(is_array($caps)){
                $caps = array_keys($caps);
            }

            if(!empty($caps)){
                foreach ($caps as $key => $cap) {
                    if($cap==='edit_posts'){
                        return 'instructor';
                    }
                }
                return 'student';
            }
            
        }
    }

    function add_taxonomies_json(){


        if(function_exists('vibe_get_option') && !empty(vibe_get_option('redirect_course_cat_directory'))){
            $taxonomies = get_taxonomies( [ 'object_type' => [ 'course' ] ] );
            $course_taxonomies = [];
            foreach ($taxonomies as $key => $tax) {
                $course_taxonomies[] = $tax;
            }
            $course_taxonomies = apply_filters('wplms_course_taxonomies',$course_taxonomies);
            $object = get_queried_object();
            if(!empty($object) && !empty($object->taxonomy) && in_array($object->taxonomy, $course_taxonomies)){
                $json_data = array(
                    'property' => 'taxonomy',
                    'id'       => $object->taxonomy,
                    'values'   => array((string)$object->term_id),

                );
                ?>
                <script>
                    if(typeof course_directory_filters==='undefined'){
                        var course_directory_filters = [];
                    }
                    course_directory_filters.push(<?php echo json_encode($json_data);?>);
                </script>
                <?php
                
            }

        }
    }

    function add_elementor_support(){
        $post_types = apply_filters('wplms_elementor_post_type_supports',array('unit'));
        foreach ($post_types as $key => $cpt_slug) {
            add_post_type_support( $cpt_slug, 'elementor' );
        }
    }

    function clear_cached_quiz($quiz_id,$marks,$user_id,$max){
        $type = get_post_field('post_type',$quiz_id);
    	delete_user_meta($user_id,'quiz_cached_results');
        wplms_remove_assigned_element($user_id,$quiz_id,$type);
    }
    function load_wplms_course_layout(){
        global $bp,$post;
       
        if(!defined('WPLMS_VERSION')){
            $init = Vibe_CustomTypes_Permalinks::init();

            if( (empty($init->permalinks) && $bp->unfiltered_uri[0] == 'course')
                || (!empty($init->permalinks) && trim($init->permalinks['course_base'],'/') == $bp->unfiltered_uri[0])){
                $bp->current_component='course';
                $bp->is_directory = 0;
            }
        }
        
    }

    function course_content($content){
        global $bp,$post;
        
        $init = Vibe_CustomTypes_Permalinks::init();
       
        if(!empty($post) && $post->post_type == 'course' 
            && ( $bp->unfiltered_uri[0] == 'course'
                || (!empty($init->permalinks) && trim($init->permalinks['course_base'],'/') == $bp->unfiltered_uri[0])) 
        ){
           
            $layout = 'blank';
            if(function_exists('vibe_get_customizer')){
                $layout = vibe_get_customizer('course_layout');
            }
            
            if((empty($layout) || $layout == 'blank') ){

                $post = get_page_by_path($bp->unfiltered_uri[1],'OBJECT','course');

                if(!function_exists('elementor_load_plugin_textdomain') || !\Elementor\Plugin::$instance->db->is_built_with_elementor($post->ID)){

                    $init = WPLMS_4_Init::init();
                    $init->course_id = get_the_ID();


                    $args = apply_filters('wplms_plugin_single_course_layout',array(
                        'post_type'=>'course-layout',
                        'posts_per_page'=>1,
                        'meta_query'=>array(
                            'relation'=>'AND',
                            array(
                                'key'=>'course-cat',
                                'compare'=>'IN',
                                'value'=>wp_get_post_terms( $init->course_id, 'course-cat', array( 'fields' => 'ids' ) )
                            )
                        )
                    ),$init->course_id);

                    
                    $layout = new WP_Query($args);

                    
                    if(!$layout->have_posts()){
                        
                        $args = array(
                            'post_type'=>'course-layout',
                            'posts_per_page'=>1,
                            'meta_query'=>array(
                                'relation'=>'AND',
                                array(
                                    'key'=>'default_course-layout',
                                    'compare'=>'=',
                                    'value'=>1
                                )
                            )
                        );

                        $layout = new WP_Query($args);
                        if(!$layout->have_posts()){
                            $args = array(
                                'post_type'=>'course-layout',
                                'posts_per_page'=>1,
                                'meta_query'=>array(
                                    'relation'=>'AND',
                                    array(
                                        'key'=>'course-cat',
                                        'compare'=>'NOT EXISTS'
                                    )
                                )
                            );
                        }

                        $layout = new WP_Query($args);
                    }

                    if($layout->have_posts()){
                        while($layout->have_posts()){
                            $layout->the_post();
                            global $post;
                            setup_postdata($post);
                            $content=$post->post_content;
                            if(class_exists('\Elementor\Frontend')){
                                $elementorFrontend = new \Elementor\Frontend();
                                $elementorFrontend->enqueue_scripts();
                                $elementorFrontend->enqueue_styles();
                            }
                            
                        }
                    }
                }
            }
        }
        return $content;
    }


    function enqueue_general(){
        if(is_wplms_4_0('course') && is_singular('course')){
            wp_enqueue_style('wplms_plugin_elementor',plugins_url('../assets/css/general.css',__FILE__),array(),WPLMS_PLUGIN_VERSION);
        }
    }


    function course_layout_card() {
        add_meta_box( 'course_category_selector', __( 'Apply on Course Category', 'wplms' ), array($this,'course_category_selector'), 'course-layout','side' );
        add_meta_box( 'course_category_selector', __( 'Apply on  Course Category', 'wplms' ), array($this,'course_category_selector'), 'course-card' ,'side');
        add_meta_box( 'course_layout_selector', __( 'Apply Course Layout', 'wplms' ), array($this,'course_layout_selector'), 'course' ,'side');
    }

    function save_course_cat_card(){
        global $post;

        if(!current_user_can('manage_options'))
            return;

        if(empty($_POST['wpadmin_check']))
            return;

        $course_cat = stripslashes( $_POST['course-cat']);


        if(empty($course_cat)){
            delete_post_meta($post->ID,'course-cat');
            return;
        }

        update_post_meta($post->ID,'course-cat', $course_cat);
    }

    function course_category_selector(){
        $cats = get_terms('course-cat',array('hide_empty'=>false));
        global $post;
        $selected_cat = get_post_meta($post->ID,'course-cat',true);
        ?>
        <select name="course-cat">
            <option value=""><?php _ex('Select Course Category','wplms'); ?></option>
            <?php
                if(!empty($cats)){
                    foreach ( $cats as $cat ) {
                        echo '<option value="'.$cat->term_id.'" '.($selected_cat == $cat->term_id?'selected':'').'>'.$cat->name.'</option>';  
                    }
                }
            ?>
        </select>
        <input type="hidden" name="wpadmin_check" value="1" />
        <?php
        
    }

    function course_layout_selector(){
        global $post;
        $course_layout = get_post_meta($post->ID,'course_layout',true);
        ?>
        <label for="course-layout" class="screen-reader-text"><?php
            esc_html_e( 'Select Course Layout', 'wplms' );
        ?></label>
        <select name="course_layout">
            <option value=""><?php _ex('Select Course Layout','wplms'); ?></option>
            <?php
            global $post;
            $ppost = $post;
            $query = new WP_Query(array(
                'post_type'=>'course-layout',
                'posts_per_page'=>-1
            ));
            if($query->have_posts()){
                while($query->have_posts()){
                    $query->the_post();
                    echo '<option value="'.get_the_ID().'" '.($course_layout == get_the_ID()?'selected':'').'>'.get_the_title().'</option>';
                }
            }
            $post = $ppost;
            ?>
        </select>
        <input type="hidden" name="wpadmin_check" value="1" />
        <?php
        
        echo wp_nonce_field( 'course-layout-change-' . $post->ID, 'course-layout-nonce' );

    }

    function save_course_layout_card($post_id){

        if ( ! isset( $_POST['course-layout-nonce'] ) || ! isset( $_POST['course_layout'] )) {
            return;
        }
        if(!current_user_can('manage_options'))
            return;

        if(empty($_POST['wpadmin_check']))
            return;

        $course_layout = stripslashes( $_POST['course_layout']);

        if(empty($course_layout)){
            delete_post_meta($post_id,'course_layout');
            return;
        }
        
        update_post_meta($post_id,'course_layout',$course_layout);

    }

    function record_credits_commission($instructor_id,$commission,$meta,$course_id){
        if(!empty($meta) && !empty($meta['origin']) && $meta['origin']==VIBEBP_CREDITS_SLUG){
            $secondary_item_id = $meta['item_id'];
            if(!empty($instructor_id) &&  !empty($course_id)){
                $commission = intval($commission);
                if(!empty($commission)){
                    if($commission > 0){
                        $commission_html = $commission.' '._x('Credits','','wplms');
                        $activity_id = bp_course_record_activity(apply_filters('bp_course_record_instructor_commission_activity',array(
                            'user_id' => $instructor_id,
                            'action' => _x('You earned commission','Instructor earned commission activity','wplms'),
                            'content' => sprintf(_x('%s commission earned for course %s','Instructor earned commission activity','wplms'),$commission_html,get_the_title($course_id)),
                            'component' => 'course',
                            'type' => 'course_commission',
                            'item_id' => $course_id,
                            'secondary_item_id' => $secondary_item_id,
                            'hide_sitewide' => true,
                        )));
                        if(!empty($activity_id)){
                          bp_course_record_activity_meta(array('id'=>$activity_id,'meta_key'=>'_commission'.$instructor_id,'meta_value'=>$commission));
                          bp_course_record_activity_meta(array('id'=>$activity_id,'meta_key'=>'_currency'.$instructor_id,'meta_value'=>$meta['currency']));
                        }
                    }else{
                        $commission_html = $commission.' '._x('Credits','','wplms');
                        $activity_id = bp_course_record_activity(apply_filters('bp_course_record_instructor_commission_activity',array(
                            'user_id' => $instructor_id,
                            'action' => _x('Commission reversed.','Instructor earned commission activity','wplms'),
                            'content' => sprintf(_x('%s commission reversed for course %s  for order ID %d as order cancelled/refunded','Instructor earned commission activity','wplms'),$commission_html,get_the_title($course_id),$meta['order_id']),
                            'component' => 'course',
                            'type' => 'course_commission',
                            'item_id' => $course_id,
                            'secondary_item_id' => $secondary_item_id,
                            'hide_sitewide' => true,
                        )));
                        if(!empty($activity_id)){
                          bp_course_record_activity_meta(array('id'=>$activity_id,'meta_key'=>'_commission'.$instructor_id,'meta_value'=>$commission));
                          bp_course_record_activity_meta(array('id'=>$activity_id,'meta_key'=>'_currency'.$instructor_id,'meta_value'=>$meta['currency']));
                        }
                    }
                }
                
            }
        }
    }
}

WPLMS_4_Actions::init();

