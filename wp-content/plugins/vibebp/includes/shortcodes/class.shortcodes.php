<?php


/**
 * Customizer functions for vibeapp
 *
 * @author      VibeThemes
 * @category    Actions
 * @package     vibeapp
 * @version     1.0
 */


if ( ! defined( 'ABSPATH' ) ) exit;


class VibeBp_Shortcodes{

    public static $instance;
    
    public static function init(){

        if ( is_null( self::$instance ) )
            self::$instance = new VibeBp_Shortcodes();

        return self::$instance;
    }

    private function __construct(){

    	add_action('wp_enqueue_scripts',array($this,'enqueue_Scripts'));
    	add_action('template_redirect',array($this,'check_shortcode'),0);
    	add_shortcode('vibebp_login',array($this,'login_shortcode'));
    	add_shortcode('vibebp_profile',array($this,'profile'));
    	add_shortcode('vibebp_carousel',array($this,'vibeapp_carousel'));
    	add_filter('vibebp_service_worker_js_scope',array($this,'check_scope_setting'));
    	add_shortcode('vibebp_registration_form',array($this,'vibebp_registration_form'));
    	add_shortcode('vibebp_member_type_count',array($this,'vibebp_member_type_count'));
    	add_shortcode('profile_field',array($this,'profile_field'));
		add_shortcode('group_field',array($this,'group_field'));
    	add_shortcode('vicon',array($this,'vicon'));
    }

    function enqueue_scripts(){
    	global $post;

    	wp_register_style('swipercss',plugins_url('../../assets/css/swiper-bundle.min.css',__FILE__));
		wp_register_style('swiperjs',plugins_url('../../assets/js/swiper-bundle.min.js',__FILE__),array(),VIBEBP_VERSION,true);

    	if(!empty($post) && has_shortcode('vibebp_carousel',$post->post_content)){
    		wp_enqueue_style('swipercss');
    		wp_enqueue_script('swiperjs');
    	}
    }

    function check_scope_setting($scope){
    	if(!empty(vibebp_get_setting('root_is_scope_for_sw','service_worker'))){
			$scope = '/';
			
		}

		return $scope;
    }

    function check_pwabuilder(){

    	$pwa = vibebp_get_setting('offline_page','service_worker');
    	if(!empty($pwa) && is_page($pwa)){
	    	?>
	    	<script type="module">
			   import 'https://cdn.jsdelivr.net/npm/@pwabuilder/pwaupdate';
			   const el = document.createElement('pwa-update');
			   document.body.appendChild(el);
			</script>
	    	<?php
	    }
    }

    function check_shortcode(){

    	global $post;

    	if(!empty($post)){
    		
    		if(has_shortcode($post->post_content,'vibebp_login') || bp_is_user()){
    			add_filter('vibebp_enqueue_login_script',function($x){return true;});
    		}
    		if(has_shortcode($post->post_content,'vibebp_profile') || bp_is_user()){
    			if(vibebp_get_setting('service_workers')){

    				//Please respect code privacy. Small code but lot of effort. Respect orignal.
    				

    				add_action('wp_head',function(){

    					$upload_dir = wp_get_upload_dir();
    					$path =$upload_dir['baseurl'];
    					$abspath =$upload_dir['basedir'];
    					$app_title =vibebp_get_setting('app_name','service_worker');
	    				if(empty($app_title)){
	    					$app_title = 'WPLMS';
	    				}

	    				$theme_color =vibebp_get_setting('theme_color','service_worker');
	    				if(empty($theme_color)){
	    					$theme_color = '#3ecf8e';
	    				}
    					//<!-- iOS  -->
    					if(file_exists($abspath.'/icon-72x72.png')){
    						echo '<link href="'.$upload_dir['baseurl'].'/icon-72x72.png" rel="apple-touch-icon">';
    					}else{
    						echo '<link href="'.plugins_url('../../assets/images/icons/icon-80x80.png',__FILE__).'" rel="apple-touch-icon">';
    					}

    					if(file_exists($abspath.'/icon-128x128.png')){
    						echo '<link href="'.$upload_dir['baseurl'].'/icon-128x128.png" rel="apple-touch-icon" sizes="120x120">';
    					}else{
    						echo '<link href="'.plugins_url('../../assets/images/icons/icon-120x120.png',__FILE__).'" rel="apple-touch-icon" sizes="120x120">';
    					}

    					if(file_exists($abspath.'/icon-152x152.png')){
    						echo '<link href="'.$upload_dir['baseurl'].'/icon-152x152.png" rel="apple-touch-icon" sizes="152x152">';
    					}else{
    						echo '<link href="'.plugins_url('../../assets/images/icons/icon-152x152.png',__FILE__).'" rel="apple-touch-icon" sizes="152x152">';
    					}

    					
	    				//<!-- Android  -->
	    				if(file_exists($abspath.'/icon-192x192.png')){
    						echo '<link href="'.$upload_dir['baseurl'].'/icon-192x192.png" rel="icon" sizes="192x192">';
    					}else{
    						echo '<link href="'.plugins_url('../../assets/images/icons/icon-192x192.png',__FILE__).'" rel="icon" sizes="192x192">';
    					}
    					if(file_exists($abspath.'/icon-128x128.png')){
    						echo '<link href="'.$upload_dir['baseurl'].'/icon-128x128.png" rel="icon" sizes="128x128">';
    					}else{
    						echo '<link href="'.plugins_url('../../assets/images/icons/icon-128x128.png',__FILE__).'" rel="icon" sizes="128x128">';
    					}

						//<!--  Microsoft  -->
						if(file_exists($abspath.'/icon-144x144.png')){
							echo '<meta name="msapplication-TileImage" content="'.$upload_dir['baseurl'].'/icon-144x144.png" />';
						}else{
							echo '<meta name="msapplication-TileImage" content="'.plugins_url('../../assets/images/icons/icon-144x144.png',__FILE__).'" />';
						}

						if(file_exists($abspath.'/icon-72x72.png')){
    						echo '<meta name="msapplication-square70x70logo" content="'.$upload_dir['baseurl'].'/icon-72x72.png" />';
    					}else{
    						echo '<meta name="msapplication-square70x70logo" content="'.plugins_url('../../assets/images/icons/icon-72x72.png',__FILE__).'" />';
    					}
						if(file_exists($abspath.'/icon-152x152.png')){
    						echo '<meta name="msapplication-square150x150logo" content="'.$upload_dir['baseurl'].'/icon-152x152.png" />';
    					}else{
    						echo '<meta name="msapplication-square150x150logo" content="'.plugins_url('../../assets/images/icons/icon-152x152.png',__FILE__).'" />';
    					}
    					if(file_exists($abspath.'/icon-384x384.png')){
    						echo '<meta name="msapplication-square310x310logo" content="'.$upload_dir['baseurl'].'/icon-384x384.png" />';
    					}else{
    						echo '<meta name="msapplication-square310x310logo" content="'.plugins_url('../../assets/images/icons/icon-384x384.png',__FILE__).'" />';
    					}


    					$splash_sizes = array(
    						'1242x2688'=>array(
    							'width'=>414,'height'=>896,'pixel-ratio'=>3,),
    						'828x1792'=>array(
    							'width'=>414,'height'=>896,'pixel-ratio'=>2),
    						'1125x2436'=>array(
    							'width'=>375,'height'=>812,'pixel-ratio'=>3),
    						'1242x2208'=>array(
    							'width'=>414,'height'=>736,'pixel-ratio'=>3),
    						'750x1334'=>array(
    							'width'=>375,'height'=>667,'pixel-ratio'=>2),
    						'2048x2732'=>array(
    							'width'=>1024,'height'=>1366,'pixel-ratio'=>2),
    						'1668x2224'=>array(
    							'width'=>834,'height'=>1112,'pixel-ratio'=>2),
    						'1536x2048'=>array(
    							'width'=>168,'height'=>1024,'pixel-ratio'=>2),
    					);
 
    					foreach($splash_sizes as $ext=>$size){
    						if(file_exists($path.'/splash-'.$ext.'.png')){
    							echo '<link rel="apple-touch-startup-image" media="(device-width: '.$size['width'].'px) and (device-height: '.$size['height'].'px) and (-webkit-device-pixel-ratio: '.$size['pixel-ratio'].')"  href="'.$upload_dir['baseurl'].'/splash-'.$ext.'.png">';
    						}else{

    							echo '<link rel="apple-touch-startup-image" media="(device-width: '.$size['width'].'px) and (device-height: '.$size['height'].'px) and (-webkit-device-pixel-ratio: '.$size['pixel-ratio'].')"  href="'.plugins_url('../../assets/images/splash/splash-'.$ext.'.png',__FILE__).'">';
    						}
    						
    					}

    					$upload_dir = wp_get_upload_dir();
    					$path = $upload_dir['baseurl'];
    					$serverpath = $upload_dir['basedir'];
    					if ( ! function_exists( 'get_home_path' ) ) {
				            include_once ABSPATH . '/wp-admin/includes/file.php';
				        }
    					$site_root = get_home_path();				          

    					if(file_exists($site_root.'/manifest.json')){

	    					echo '<link rel="manifest" href="'.untrailingslashit(function_exists('network_site_url') && is_multisite()?network_site_url():site_url()).'/manifest.json'.'"><meta name="theme-color" content="'.$theme_color.'"><meta name="theme-color" content="'.$theme_color.'"><meta name="msapplication-TileColor" content="'.$theme_color.'" /><meta name="msapplication-config" content="none"/><meta name="msapplication-navbutton-color" content="'.$theme_color.'">';
	    				}

    					echo '<meta name="apple-mobile-web-app-title" content="'.$app_title.'"><meta name="apple-mobile-web-app-capable" content="yes"><meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"><meta name="mobile-web-app-capable" content="yes">';
    					if(file_exists($serverpath.'/icon-192x192.png')){
    						echo '<link rel="apple-touch-icon" href="'.$upload_dir['baseurl'].'/icon-192x192.png">';
    					}else{
    						echo '<link rel="apple-touch-icon" href="'.plugins_url('../../assets/images/icons/icon-192x192.png',__FILE__).'">';
    					}
    					

						if(file_exists($serverpath.'/icon-144x144.png')){
    						echo '<link rel="apple-touch-icon" href="'.$upload_dir['baseurl'].'/icon-144x144.png">';
    					}else{
    						echo '<link rel="apple-touch-icon" href="'.plugins_url('../../assets/images/icons/icon-144x144.png',__FILE__).'">';
    					}
    				});

    				remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
				    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
				    remove_action( 'wp_print_styles', 'print_emoji_styles' );
				    remove_action( 'admin_print_styles', 'print_emoji_styles' );   
				    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
				    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );     
				    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );

				    //To be decided -----***
				    //remove_action('wp_footer',array('Wplms_Wishlist_Init','append_span'));
				    //remove_action('wp_footer',array('Wplms_Wishlist_Init','append_collection'));

    				//add_action('wp_print_scripts', array($this,'remove_all_scripts'), 100);
					//add_action('wp_print_styles',  array($this,'remove_all_styles'), 100);
					// add_filter( 'style_loader_src', function ( $src ) {
					//     if ( strpos( $src, 'ver=' ) ){
					//         $src = remove_query_arg( 'ver', $src );
					//     }
					//     return $src;
					// }, 9999 );

					// add_filter( 'script_loader_src', function ( $src ) {
					//     if ( strpos( $src, 'ver=' ) )
					//         $src = remove_query_arg( 'ver', $src );
					//     return $src;
					// }, 9999 );
					// add_action('wp_footer',function(){
					// 	echo '<script>document.querySelector("#wpadminbar").remove();
					// 	document.querySelector("body").classList.remove("admin-bar");</script>';
					// },9999);
    			}

    			add_filter('vibebp_enqueue_profile_script',function($x){return true;});
    		}
    	}
    	
    }
    function vibebp_member_type_count($atts,$content){
    	if(!function_exists('bp_get_member_type_tax_name'))
    		return;
    	if(!empty($atts['member_type'])){
    		$term = get_term_by('slug',sanitize_text_field($atts['member_type']),bp_get_member_type_tax_name());
    		if(!empty($term) && !is_wp_error($term)){
    			return $term->count;
	    	}
	    	
    	}
    }
    function login_shortcode($atts,$content=null){

    	$defaults = array(
    		'class'=>'',
    		'button'=>'',
    	);
    	if(empty($content)){
    		$content = _x('Login','','vibebp');
    	}
    	$return = '';
    	
    	if(!empty($atts['button'])){
    		$return .='<span class="'.$atts['class'].' vibebp-login" type="popup">'.$content.'</span>';
    	}else{
    		$return .='<span class="vibebp-login" type="static">'.$content.'</span>';
    	}
    	return $return;
    }

    function profile($atts=array(),$content=null){

    	$return ='<div id="vibebp_member"></div>';

    	add_action('wp_footer',function(){

    		if ( ! function_exists( 'get_home_path' ) ) {
	            include_once ABSPATH . '/wp-admin/includes/file.php';
	        }
        
			$site_root = get_home_path();				          

    		if(file_exists($site_root.'/firebase-messaging-sw.js')){
    			if(vibebp_get_setting('service_workers')){
    				$scope = '';
    				//$url = vibebp_get_setting('offline_page','service_worker');
    				$site_url = site_url();
    				//$scope = str_replace($site_url.'/','',$url);

					if($_SERVER["DOCUMENT_ROOT"] != $site_root){
						$scope =  rtrim(str_replace($_SERVER["DOCUMENT_ROOT"],'', $site_root).$scope,'/').'/';
					}
					$scope = apply_filters('vibebp_service_worker_js_scope',$scope);

			    	?>
			    	<script>if ('serviceWorker' in navigator && window.vibebp.api.sw_enabled) {

							navigator.serviceWorker.getRegistrations().then(registrations => {

							    let check = registrations.findIndex((item)=>{
							    	return (item.active.scriptURL == window.vibebp.api.sw && item.active.state == 'activated')
							    });
							    let sw_first = window.vibebp.api.sw.split('?v=');
							    let index = registrations.findIndex((i) => {return i.active.scriptURL.indexOf(sw_first[0]) > -1 });
							    if(index > -1 && registrations[index].active.scriptURL.indexOf(sw_first[1]) == -1){
							    	//unregister previous version

							    	registrations[registrations.findIndex(i => i.active.scriptURL.indexOf(sw_first[0]) > -1)].unregister();
							    	check = -1;
							    }
								//service worker registration to be called only once.
								if(check == -1){
								  	
								  	navigator.serviceWorker.register(window.vibebp.api.sw,{
								  		scope:'<?php echo $scope; ?>'
								  	}).then(function(registration) {
								      console.log('Vibebp ServiceWorker registration successful with scope: ', registration.scope);
										
								    }, function(err) {
								      console.log('Vibebp ServiceWorker registration failed: ', err);
								    });
							  	}else{
							  		console.log('Vibebp Service worker already registered & active.');
							  	}
						  	});

							navigator.serviceWorker.ready.then(async function(registration) {
								

								if ('periodicSync' in registration) {
									const status = await navigator.permissions.query({
								      name: 'periodic-background-sync',
								    });
								    if (status.state === 'granted') {
								      	try {
									      	registration.periodicSync.register({
										  		tag: 'vibebp-post-data',         // default: ''
										    	minPeriod: 24 * 60 * 1000, // default: 0
										  	}).then(function(periodicSyncReg) {
										    // success
										  	}, function() {
										    // failure
										  	})
								      	}catch(e) {
									        console.error(`Periodic background sync failed:\n${e}`);
									    }
								  	}
									
							  	}
							});

						}</script>
			    	<?php
		    	}else{
		    		unlink($site_root.'/firebase-messaging-sw.js');
	    			$delete_sw = 1; 
	    			//WP_Filesystem_Direct::delete($site_root.'/firebase-messaging-sw.js');

		    		?>
		    		<script>
		    			navigator.serviceWorker.getRegistrations().then(function(registrations) {
						 	for(let registration of registrations) {
						  		registration.unregister();
						  		<?php if($delete_sw){ ?>
						  		setTimeout(function(){
					              window.location.replace(window.location.href);
					            }, 3000);
						  		<?php } ?>
							} 
						});
						if ('caches' in window) {
						    caches.keys()
						      .then(function(keyList) {
						          return Promise.all(keyList.map(function(key) {
						              return caches.delete(key);
						          }));
						      })
						}
		    		</script>
		    		<?php
	    		}
	    	} 
	    },99);
		//wp_enqueue_script('jquery');
		if(function_exists('bp_is_active') && bp_is_active('groups')){
			add_action('wp_footer',function(){ ?><div class="vibebp_group_popups"></div><?php });
		}
    	return $return;
    }


    function remove_all_scripts() {

		if(!vibebp_get_setting('service_workers'))
			return;

	    global $wp_scripts;
	    $queue = $wp_scripts->queue;
	    
	    //$wp_scripts->queue = vibebp_get_pwa_scripts(1);
	}


	function remove_all_styles() {
		if(!vibebp_get_setting('service_workers'))
			return;
	    global $wp_styles;

	    //$wp_styles->queue = vibebp_get_pwa_styles(1);

	}


    function vibebp_grid($atts){

    	$attributes_string = '';
    	$width = '268';
    	if(!empty($atts['gutter'])){
    		$attributes_string .= 'data-gutter="'.$atts['gutter'].'" ';
    	}
    	
    	$grid_control = [];
    	if(!empty($atts['grid_control'])){
    		
    		$grid_control = json_decode($atts['grid_control'],true);
    	
    		$atts['grid_number']  = count($grid_control['grid']);
    	}



    	$randclass = wp_generate_password(8,false,false);
    	$output .= '<div class="vibebp_grid '.$randclass.'" '.$attributes_string.'>';
    	if(!isset($atts['post_ids']) || strlen($atts['post_ids']) < 2){
        
	        if(isset($atts['term']) && isset($atts['taxonomy']) && $atts['term'] !='nothing_selected'){
	            
	            if(!empty($atts['taxonomy'])){ 
	                
	                if(strpos($atts['term'], ',') === false){
	                    $check=term_exists($atts['term'], $atts['taxonomy']);
	                    if($atts['term'] !='nothing_selected'){    
	                        if ($check == 0 || $check == null || !$check) {
	                            $error = new VibeAppErrors();
	                            $output .= $error->get_error('term_taxonomy_mismatch');
	                            $output .='</div>';
	                            return $output;
	                       } 
	                    }
	                }    
	                $check=is_object_in_taxonomy($atts['post_type'], $atts['taxonomy']);
	                if ($check == 0 || $check == null || !$check) {
	                    $error = new VibeAppErrors();
	                    $output .= $error->get_error('term_postype_mismatch');
	                    $output .='</div>';
	                    return $output;
	               }

	                $terms = $atts['term'];
	                if(strpos($terms,',') !== false){
	                    $terms = explode(',',$atts['term']);
	                } 
	            }

	            
	            $query_args=array( 'post_type' => $atts['post_type'],'posts_per_page' => $atts['grid_number']);
	            if(!empty($atts['taxonomy'])){ 
	              $query_args['tax_query'] = array(
	                  'relation' => 'AND',
	                  array(
	                      'taxonomy' => $atts['taxonomy'],
	                      'field'    => 'slug',
	                      'terms'    => $terms,
	                  ),
	              );
	            }
	        }else{
	           $query_args=array('post_type'=>$atts['post_type'], 'posts_per_page' => $atts['grid_number']);
	        }
	        
	        if($atts['post_type'] == 'course' && isset($atts['course_style'])){
	            switch($atts['course_style']){
	                case 'popular':
	                  $query_args['orderby'] = 'meta_value_num';
	                  $query_args['meta_key'] = 'vibe_students';
	                break;
	                case 'featured':
	                  if(empty($query_args['meta_query'])){
	                    $query_args['meta_query'] = array(
	                              array(
	                                  'key'     => 'featured',
	                                  'value'   => 1,
	                                  'compare' => '>='
	                              )
	                          );
	                  }else{
	                    $query_args['meta_query'][] = array(
	                                  'key'     => 'featured',
	                                  'value'   => 1,
	                                  'compare' => '>='
	                          );
	                  }
	                break;
	                case 'rated':
	                  $query_args['orderby'] = 'meta_value_num';
	                  $query_args['meta_key'] = 'average_rating';
	                break;
	                case 'reviews':
	                  $query_args['orderby'] = 'comment_count';
	                break;
	                case 'start_date':
	                  $query_args['orderby'] = 'meta_value';
	                  $query_args['meta_key'] = 'vibe_start_date';
	                  $query_args['meta_type'] = 'DATE';
	                  $query_args['order'] = 'ASC';
	                  $today = date('Y-m-d');
	                  if(empty($query_args['meta_query'])){
	                    $query_args['meta_query'] = array(
	                              array(
	                                  'key'     => 'vibe_start_date',
	                                  'value'   => $today,
	                                  'compare' => '>='
	                              )
	                          );
	                  }else{
	                    $query_args['meta_query'][] = array(
	                                  'key'     => 'vibe_start_date',
	                                  'value'   => $today,
	                                  'compare' => '>='
	                          );
	                  }
	                  
	                break;
	                case 'expired_start_date':
	                  $query_args['orderby'] = 'meta_value';
	                  $query_args['meta_key'] = 'vibe_start_date';
	                  $query_args['meta_type'] = 'DATE';
	                  $query_args['order'] = 'ASC';
	                  $today = date('Y-m-d');
	                  if(empty($query_args['meta_query'])){
	                    $query_args['meta_query'] = array(
	                              array(
	                                  'key'     => 'vibe_start_date',
	                                  'value'   => $today,
	                                  'compare' => '<'
	                              )
	                          );
	                  }else{
	                    $query_args['meta_query'][] = array(
	                                  'key'     => 'vibe_start_date',
	                                  'value'   => $today,
	                                  'compare' => '<'
	                          );
	                  }
	                  
	                break;
	                case 'random':
	                   $query_args['orderby'] = 'rand';
	                break;
	                case 'free':
	                 if(empty($query_args['meta_query'])){
	                  $query_args['meta_query'] =  array(
	                      array(
	                        'key'     => 'vibe_course_free',
	                        'value'   => 'S',
	                        'compare' => '=',
	                      ),
	                    );
	                }else{
	                  $query_args['meta_query'][] =  array(
	                        'key'     => 'vibe_course_free',
	                        'value'   => 'S',
	                        'compare' => '=',
	                    );
	                }
	                break;
	                default:
	                  $query_args['orderby'] = '';
	            }
	            if(empty($query_args['order']))
	              $query_args['order'] = 'DESC';

	            
	        }
	        $query_args =  apply_filters('vibebp_elementor_filters',$query_args);
	        $the_query = new WP_Query($query_args);

        }else{

          $cus_posts_ids=explode(",",$atts['post_ids']);
        	$query_args=array( 'post_type' => $atts['post_type'], 'post__in' => $cus_posts_ids , 'orderby' => 'post__in','posts_per_page'=>count($cus_posts_ids)); 
        	$query_args =  apply_filters('vibebp_elementor_filters',$query_args);
        	$the_query = new WP_Query($query_args);
        }
        if($atts['column_width'] < 311)
             $cols = 'small';
         
        if(($atts['column_width'] >= 311) && ($atts['column_width'] < 460))    
             $cols='medium';
         
        if(($atts['column_width'] >= 460) && ($atts['column_width'] < 769))    
             $cols='big';
         
        if($atts['column_width'] >= 769)    
             $cols='full';
         
        $style ='';
       	if( $the_query->have_posts() ) {
    		$style .= '<style>.'.$randclass.' {
						    width:100%;
						    display:grid;';




			if(!empty($atts['column_align_verticle'])){
				$style .= 'align-items:'.$atts['column_align_verticle'].';';
			}
			if(!empty($atts['column_align_horizontal'])){
				$style .= 'justify-content:'.$atts['column_align_horizontal'].';';
			}
				    
			$style .=	'grid-gap:'.$atts['gutter'].'px;
						}</style>';
	        //$row_logic = $atts['carousel_rows'];
	       // $row_logic = 0; 
			$i = 0;
	        while ( $the_query->have_posts() ) : $the_query->the_post();
	        global $post;
	        $style_string  ='';
	        if(!empty($grid_control['grid']) && !empty($grid_control['grid'][$i])){
	        	 $style_string .= 'grid-column:'.$grid_control['grid'][$i]['col'].';';
	        	 $style_string .= 'grid-row:'.$grid_control['grid'][$i]['row'].';';
	        }
	        if(!empty($atts['grid_width'])){
	        	$width = $atts['grid_width'];
	        	//$style_string .= 'width:'.$atts['grid_width'].'px;';
	        }
	       	$output .= '<div class="grid_item " style="'.$style_string.'">
	       	'.vibebp_render_block_from_style($post,$atts['featured_style'],$cols,$atts['carousel_excerpt_length']).'
	       	</div>';
	       	$i++;
	    	endwhile;
			
       	}else{
       		$output .= '<div class="vbp_message">'._x('No posts Found !','','vibeapp').'</div>';
       	}
       	$output .= '</div>'.$style;
       	wp_reset_postdata();
    	return $output;
    }

    function vibeapp_carousel($atts,$content=null){
    	

    	$output = '';
    	$posts=[];

    	add_filter('excerpt_more','__return_false');
    	add_filter( 'excerpt_length', function($l){return 20;}, 999 );
    	$args='';
    	if(!empty($atts['args'])){
    		$args = json_decode(base64_decode($atts['args']),ARRAY_A);	
    	}
    	
    	
    	switch($atts['type']){
    		case 'taxonomy':
    			$posts = get_terms($args);
    		break;
    		case 'members':
    			$run = bp_core_get_users( $args);

	    		if( $run['total'] ){
	    			foreach($run['users'] as $key=>$user){
	    				$posts[]=$user;
	    			}
	    		}

    		break;
    		case 'groups':
    			if(function_exists('groups_get_groups')){
	    			$run = groups_get_groups( $args);

		    		if( $run['total'] ){
		    			foreach($run['groups'] as $key=>$user){
		    				$posts[]=$user;
		    			}
		    		}
		    	}
    		
    		break;
    		case 'post_type':
    			
    			$query = new WP_Query($args);
    			if($query->have_posts()){
    				while($query->have_posts()){
    					$query->the_post();
    					$posts[]=$query->post;
    				}
    			}
    			wp_reset_postdata();
    		break;
    		case 'slides':

    			$posts=json_decode(base64_decode($atts['slides']),ARRAY_A);
    		break;
    	}
    	
    	
    	wp_enqueue_style('vibebp-swiper',plugins_url('../../assets/css/swiper-bundle.min.css',__FILE__));
		wp_enqueue_script('vibebp-swiper',plugins_url('../../assets/js/swiper-bundle.min.js',__FILE__),array(),VIBEBP_VERSION,true);
    	ob_start();
    	?>
    	<div id="carousel_<?php echo $atts['id']; ?>" class="swiper swiper_style_<?php echo $atts['carousel_style']; ?> swiper_<?php echo $atts['id'].' '.(!empty($atts['show_controlnav'])?'with_pagination':'').' '.(empty($atts['show_controls'])?'':'with_navigation').' '.(empty($atts['scrollbar'])?'':'with_scrollbar');?> ">
		  <div class="swiper-wrapper">
		  	<?php
		  	if(!empty($posts)){
		  		foreach($posts as $k=>$post){
		  			?>
		  			<div class="swiper-slide <?php echo $atts['type'].'_'.$k; ?>">
	  				<?php vibebp_featured_style($post,$atts['featured_style']);	?>
		  			</div>
		  			<?php
		  		}
		  	}
		  	?>
		  </div>
		  <?php 
		  	if(!empty($atts['show_controlnav'])){
		  		?>
		  		<div class="swiper-pagination"></div>
		  		<?php
		  	}
		   ?>
		  <?php 
		  	if(!empty($atts['show_controls'])){
		  		?>
		  		<div class="swiper-button-prev"></div>
		  		<div class="swiper-button-next"></div>
		  		<?php		
		  	}
		   ?>
		   <?php 
		  	if(!empty($atts['scrollbar'])){
		  		?>
		  		<div class="swiper-scrollbar"></div>
		  		<?php		
		  	}
		  	$atts['space'] = empty($atts['space'])?40:intval($atts['space']);
		  	$atts['columns'] = empty($atts['columns'])?1:intval($atts['columns']);
		  	
		  	$args = [

			  	'speed'	=>400,
			  	'autoHeight' =>empty($atts['autoheight'])?true: false,
			  	'direction'=>empty($atts['vertical'])?'horizontal': 'vertical',
			  	'effect'=> $atts['effect'],
			  	'watchOverflow'=> true,
			  	'navigation'=> [
                    'nextEl'=> '.swiper-button-next',
                    'prevEl'=>'.swiper-button-prev',
                    'disabledClass'=> 'disabled_swiper_button'
                 ],
			  	'initialSlide'=>$atts['starting_slide'],
			  	'grid'=>[
			  		'rows'=>$atts['rows'],
			  	],
			  	'slidesPerView'=>$atts['columns'],
			  	'spaceBetween'=>$atts['space'],
			  	'navigation'=> [
			    	'nextEl'=> '.swiper-button-next',
			    	'prevEl'=> '.swiper-button-prev',
			    	'disabledClass'=> 'disabled_swiper_button'
			  	],
			  	'pagination'=>[
			  		 'el'=> ".swiper-pagination",
			  		 'clickable'=> true
			  	],
			  	'loop'=>empty($atts['loop'])?false: true,
			  	'show_controlnav'=>empty($atts['show_controlnav'])?false: true,
			  	'show_controls'=>empty($atts['show_controls'])?false: true,
			  	'auto_slide'=>empty($atts['auto_slide'])?false: true,
			  	'vertical'=>empty($atts['vertical'])?false: true,
			  	'scrollbar'=>[
		          'el'=> ".swiper-scrollbar",
		          'hide'=> true,
		        ]
				
		  	];

		  	if(!empty($atts['columns']) && $atts['columns'] > 1){
		  		
		  		$args['breakpoints'] = [
		  			'1140'=>[
						'slidesPerView'=>$atts['columns'],
			  			'spaceBetween'=>$atts['space']
					]
				];

				if($atts['columns'] >  2){
					//480px and above
					$args['breakpoints']['768']=[
						'slidesPerView'=>2,
						'spaceBetween'=> 10,
					];
				}

				if($atts['columns'] >=  4){
					//960 px and above
					$args['breakpoints']['960']=[
						'slidesPerView'=>3,
						'spaceBetween'=> (($atts['columns'] > 20 )?$atts['columns'] - 10:$atts['columns'])
					];
				}

				$args['breakpoints']['320']=[
					'slidesPerView'=>1,
					'spaceBetween'=>0,
				];
		  	}

		  	if($atts['effect'] == 'coverflow'){
		  		$args['centeredSlides']= true;
		  		$args['initialSlide']=3;
		        $args['coverflowEffect']=[
			          'rotate'=> 50,
			          'stretch'=> 0,
			          'depth'=> 100,
			          'modifier'=> 1,
			          'slideShadows'=> true,
			        ];
		  	}
		  	if(!empty($args['extras'])){
		  		$extras = json_decode($atts['extras']);
		  		if(is_array($extras)){
		  			$args = array_merga($args,$extras);
		  		}
		  	}
		  	$args = apply_filters('vibebp_carousel_args',$args,$atts);
		  	$this->args = $args;
		   ?>
		  
		  
		</div>
		<script>
			document.addEventListener('DOMContentLoaded',function(){
				window.swiper_args_<?php echo $atts['id'];?> = <?php echo json_encode($args); ?>;
				window.swiper_<?php echo $atts['id'];?> = new Swiper('.swiper_<?php echo $atts['id'];?>', window.swiper_args_<?php echo $atts['id'];?> );
			});
		</script>
		<?php if(!empty($atts['autoheight'])){ ?>
		<style>#carousel_<?php echo $atts['id'];?> .swiper-slide{height: auto !important;}#carousel_<?php echo $atts['id'];?> .swiper-slide > *{height: 100%}</style>
    	<?php
    	}
    	do_action('vibebp_carousel_styles_scripts',$atts['featured_style'],$atts['id']);
    	$output = ob_get_clean();



    	return $output;
    }

    function vibebp_registration_form($atts, $content = null){
        extract(shortcode_atts(array(
                    'name'   => '',
                    'field_meta'=>0,
                ), $atts));

        if(empty($name) && function_exists('xprofile_get_field'))
            return;
        wp_dequeue_script('wplms-registration-forms');
        wp_enqueue_script('vibebp-registration-forms',plugins_url('../../assets/js/registration_forms.js',__FILE__),array(),VIBEBP_VERSION,true);
     	wp_localize_script('vibebp-registration-forms','vibebp_reg_forms',apply_filters('vibebp_reg_forms',
     		array(
     			'recaptcha_key'=>vibebp_get_setting('google_captcha_public_key'),
		 		'translations'=>array(
		 			'captcha_mismatch'=>_x('Captcha Mismatch','','vibebp'),
		 		),
		 	)
     	));
        $return = '';
        $forms = get_option('vibebp_registration_forms');
        global $bp;
        $types = bp_get_member_types(array(),'objects');
        $member_types = [];
        if(!empty($types)){
            foreach($types as $type => $labels){
                $member_types[]=array('id'=>$type,'sname'=>$labels->labels['name']);
            }
        }


        if(!empty($forms[$name])){
        	$fields =[];
        	if(!empty($forms[$name]['fields'])){
        		$fields = $forms[$name]['fields'];
        	}

            $settings = [];
            if(!empty($forms[$name]['settings'])){
                $settings = $forms[$name]['settings'];
            }
            
            

            /*
            STANDARD FIELDS
            */

          
            $return = '<div class="vibebp_registration_form" data-form-name="'.$name.'"><form action="/" name="signup_form" id="signup_form" class="standard-form" method="post" enctype="multipart/form-data">

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
                if ( bp_has_profile( array( 'fetch_field_data' => false,'member_type'=>'' ) ) ) : 
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

                                    global $field;

                                    do_action( 'bp_custom_profile_edit_fields_pre_visibility' );

                                    if ( bp_current_user_can( 'bp_xprofile_change_field_visibility' ) ) { ?>
                                        <p class="field-visibility-settings-toggle" id="field-visibility-settings-toggle-<?php bp_the_profile_field_id() ?>"><span id="<?php bp_the_profile_field_input_name(); ?>-2">
                                            <?php
                                            printf(
                                                /* translators: %s: level of visibility */
                                                __( 'This field can be seen by: %s', 'buddypress' ),
                                                '<span class="current-visibility-level">' . bp_get_the_profile_field_visibility_level_label() . '</span>'
                                            );
                                            ?>
                                            </span>
                                            <button type="button" class="visibility-toggle-link" aria-describedby="<?php bp_the_profile_field_input_name(); ?>-2" aria-expanded="false"><?php _ex( 'Change', 'Change profile field visibility level', 'buddypress' ); ?></button>
                                        </p>

                                        <div class="field-visibility-settings" id="field-visibility-settings-<?php bp_the_profile_field_id() ?>">
                                            <fieldset>
                                                <legend><?php _e( 'Who can see this field?', 'buddypress' ) ?></legend>

                                                <?php bp_profile_visibility_radio_buttons() ?>

                                            </fieldset>
                                            <button type="button" class="field-visibility-settings-close"><?php _e( 'Close', 'buddypress' ) ?></button>

                                        </div>
                                    <?php }else { 
                                        $levels = bp_xprofile_get_visibility_levels();

                                        $level = bp_xprofile_get_meta($field->id,'field','default_visibility',true);
                                        if(empty($level)){
                                            $level = 'public';
                                        }
                                      
                                        
                                        ?>
                                        <p class="field-visibility-settings-notoggle" id="field-visibility-settings-toggle-<?php bp_the_profile_field_id() ?>">
                                            <?php
                                            printf(
                                                __( 'This field can be seen by: %s', 'buddypress' ),
                                                '<span class="current-visibility-level">' . apply_filters( 'bp_get_the_profile_field_visibility_level_label', $levels[ $level ]['label'], $level ) . '</span>'
                                            );
                                            ?>
                                        </p>
                                    <?php } ?>
                                </div>
                                <?php
                                }
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
           
            
            $form_settings = apply_filters('vibebp_registration_form_settings',array(
                    'password_meter'         => __('Show password meter','wplms'),
                    'show_group_label'       => __('Show Field group labels','wplms'),
                    'google_captcha'         => __('Google Captcha','wplms'),
                    'custom_activation_mail' => __('Custom activation email','wplms'),
            ));
           
            foreach($form_settings as $key=>$setting){
                if(!empty($settings[$key])){
                    if(!empty($settings['google_captcha']) && $key == 'google_captcha'){
                        
                        $google_captcha_public_key = vibebp_get_setting('google_captcha_public_key');
                        if(!empty($google_captcha_public_key)){
                        	if ( ! wp_script_is( 'google-recaptchav3', 'enqueued' ) ) {
	                            wp_register_script('google-recaptchav3','https://www.google.com/recaptcha/api.js?render='.vibebp_get_setting('google_captcha_public_key'));
	                        }
                        }
                        
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

            $return .='<li>'.apply_filters('vibebp_registration_form_submit_button','<a href="#" class="submit_registration_form button">'.__('Register','wplms').'</a>').'</li>';
            $return .= '</ul></form></div><style>.vibebp_registration_form { clear: both; display: block } .vibebp_registration_form ul li { clear: both } .vibebp_registration_form ul li+li { margin-top: 10px } .vibebp_registration_form ul li label { width: 100%; display: block } .vibebp_registration_form ul li>input,.vibebp_registration_form ul li>select,.vibebp_registration_form ul li>textarea { width: 100% } .vibebp_registration_form ul li fieldset legend { font-size: 16px; font-weight: 600; border: none } .vibebp_registration_form .submit_registration_form.loading:before { content: "\e619"; opacity: .8; font-family: vicon; -webkit-animation: spin 2s infinite linear; animation: spin 2s infinite linear }.bp-profile-field {display:initial;}</style>';
        }
        return $return;
    }

    function profile_field($atts,$content=null){
    	$return = '';

    	if(!empty($atts['user_id'])){
    		$user_id = $atts['user_id'];
    	}else{
    		if(bp_displayed_user_id()){
				$user_id = bp_displayed_user_id();
			}else{
				global $members_template;
				if(!empty($members_template->member)){
					$user_id = $members_template->member->id;
				}
			}
			if(empty($user_id)){
				$init = VibeBP_Init::init();
				if(!empty($init->user_id)){
					$user_id = $init->user_id;
				}else{
					$user_id = get_current_user_id();
				}
			}
    	}
    	

		if(!empty($atts['name'])){
			$id = xprofile_get_field_id_from_name($atts['name']);
		}

		if(!empty($atts['id'])){
			$id = $atts['id'];
		}
		$field = xprofile_get_field( $id );
		$value = xprofile_get_field_data( $id, $user_id);
		if(!empty($atts['flag']) && is_string($value)){
			$value = '<img src="https://flagcdn.com/'.strtolower($value).'.svg" width="'.(empty($atts['size'])?'32':$atts['size']).'" alt="country flag">';
		}
		 
		$return = vibebp_process_profile_field_data($value,$field,$user_id);
		
		if(!empty($field) && $field->type == 'gallery' && null !== json_decode($return)){
			$html = '<div class="vibebp_media_gallery">';

			$return = json_decode($return,true);
                foreach ($return as $key => $image) {
                   
                    if(!empty($image['url'])){
                        if(!empty($image['id'])){

                            if($image['type'] == 'image'){
                                if(!empty($field->full_image)){
                                    $html .= '<div class="full_image"><img src="'.$image['url'].'"></div>';    
                                }else{
                                    $html .= '<div class="vibebp_media_gallery_image" data-url="'.$image['url'].'"><img src="'.wp_get_attachment_image_url($image['id'],'thumbnail').'"></div>';        
                                }
                                $html .= '<div class="vibebp_media_gallery_image" data-url="'.$image['url'].'"><img src="'.wp_get_attachment_image_url($image['id'],'thumbnail').'"></div>';    
                            }else{
                                $html .= '<div class="vibebp_media_gallery_attachment"><a href="'.$image['url'].'" target="_blank">'.$image['name'].'</a></div>'; 
                            }
                            
                        }else{
                            $html .= '<div class="vibebp_media_gallery_image"><img src="'.$image['url'].'"></div>';
                        }
                        
                    }
                }
                $html .= '</div><style>.gallery.field_type_gallery,.vibebp_media_gallery{width:100%;}.vibebp_media_gallery { display: grid; grid-template-columns: repeat( auto-fit, minmax(120px,1fr) ); grid-gap: 0; }.vibebp_media_gallery_image{cursor:pointer;}.vibebp_gallery_popup { display: flex; position: fixed; z-index: 99999; background: rgba(0,0,0,0.4); left: 0; top: 0; width: 100vw; height: 100vh; align-items: center; justify-content: center; } .vibebp_gallery_popup > * { max-width: 80vw; max-height: 80vh; border-radius: 10px; overflow: hidden; box-shadow: 0 5px 10px rgba(0,0,0,0.4); }</style>';
                ob_start();
                ?>
                <script>
                    window.addEventListener('load',function(){
                        (function() {  
                            document.querySelectorAll('.field_<?php echo $field->id;?> .vibebp_media_gallery .vibebp_media_gallery_image').forEach(function(el){
                                el.addEventListener('click',function(ee){
                                    let src = '';
                                    if (event.target.nodeName == 'IMG'){
                                        src = ee.target.getAttribute('src');
                                    }else{
                                        src = ee.target.querySelector('img').getAttribute('src');
                                    }
                                    
                                    if(src.length){
                                        let popup=document.querySelector('.vibebp_gallery_popup');
                                        if(popup){
                                            popup.parentNode.removeChild(popup);    
                                        }
                                        

                                        const element = document.createElement("div");
                                        element.classList.add('vibebp_gallery_popup');   
                                        element.innerHTML = '<div class="vibebp_gallery_popup_content"><img src="'+src+'"></div>';
                                        element.onclick = function(){
                                            element.remove();
                                        };
                                        document.querySelector('body').appendChild(element);
                                    }
                                });
                            });
                        })();  
                    });
                </script>
                <?php
                $html .= ob_get_clean();
                $return =$html;
		}
    	return $return;
    }

    function group_field($atts,$content=null){

    	

    	if(!empty($atts['group_id'])){
    		$group_id = $atts['group_id'];
    	}else{
    		if(bp_get_current_group_id()){
				$group_id = bp_get_current_group_id();
			}
			if(empty($group_id)){
				$init = VibeBP_Init::init();
				if(!empty($init->group_id)){
					$group_id = $init->group_id;
				}
			}
    	}
    	

		if(!empty($atts['field_name']) && !empty($group_id)){
			$data = groups_get_groupmeta($group_id,esc_attr($atts['field_name']),true);
			
			$gfields = vibebp_get_setting('group_custom_fields','bp','groups');
			if(!empty($gfields)){
				
				if(in_Array($atts['field_name'],$gfields['key'])){
					$i = array_search($atts['field_name'],$gfields['type']);
					if(in_array($gfields['type'][$i],['select','radio','checkbox'])){
						$options = explode('|',$gfields['options'][$i]);
						if(!empty($options)){
							foreach($options as $option){
								$vals = explode(';',$option);
								if($vals[0] == $data){
									$data = $vals[1];
								}
							}
						}
					}
				}
			}
			if(is_array($data)){
				$data = implode(',',$data);
			}
			$return = apply_filters('vibebp_group_field', $data, $group_id);
		}
    	return $return;
    }
    function vicon($atts,$content=null){

    	wp_enqueue_style('vicons');

    	$return ='<span class="vicon vicon-'.$atts['vicon'].'" style="'.(empty($atts['size'])?'':'font-size:'.$atts['size'].'px;').' '.(empty($atts['color'])?'':'color:'.$atts['color']).'"></span>';
    	return $return;
    }
    
}

VibeBp_Shortcodes::init();

