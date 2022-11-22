<?php
/**
 * Register blocks.
 *
 * @package VibeBP
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Load registration for our blocks.
 *
 * @since 1.6.0
 */
class Wplms_Plugin_Register_Guten_Blocks {


	/**
	 * This plugin's instance.
	 *
	 * @var VibeBP_Register_Blocks
	 */
	private static $instance;

	/**
	 * Registers the plugin.
	 */
	public static function register() {
		if ( null === self::$instance ) {
			self::$instance = new Wplms_Plugin_Register_Guten_Blocks();
		}
	}

	/**
	 * The Plugin version.
	 *
	 * @var string $_slug
	 */
	private $_slug;

	/**
	 * The Constructor.
	 */
	public function __construct() {
		$this->counter = 0;
		$this->_slug = 'wplms';

		add_action( 'init', array( $this, 'register_blocks' ), 99 );
		add_action( 'admin_enqueue_scripts', array($this,'vibebp_block_assets'));
		add_filter( 'block_categories', array($this,'block_category'), 1, 2);


		// Hook: Frontend assets.
		//add_action( 'enqueue_block_assets', array($this,'block_assets'),9 );

		//FOR TESTING

	}

	/**
	 * Add actions to enqueue assets.
	 *
	 * @access public
	 */
	public function register_blocks() {

		// Return early if this function does not exist.
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		// Shortcut for the slug.
		$slug = $this->_slug;



		register_block_type(
			$slug . '/coursefeatured',
			array(
				'editor_script' => $slug . '-editor',
				'editor_style'  => $slug . '-editor',
				'style'         => $slug . '-frontend',
				'render_callback' => array($this,'coursefeatured'),
				'attributes'      => [
					'radius' => [
						'default' => '2',
						'type'    => 'string'
					],
					'width'  => [
						'type'  => 'string',
					]
				]
			)
		);

		register_block_type(
			$slug . '/courseinfo',
			array(
				'editor_script' => $slug . '-editor',
				'editor_style'  => $slug . '-editor',
				'style'         => $slug . '-frontend',
				'render_callback' => array($this,'courseinfo'),
				
			)
		);

		register_block_type(
			$slug . '/coursebutton',
			array(
				'editor_script' => $slug . '-editor',
				'editor_style'  => $slug . '-editor',
				'style'         => $slug . '-frontend',
				'render_callback' => array($this,'coursebutton')
			)
		);

		register_block_type(
			$slug . '/coursecurriculum',
			array(
				'editor_script' => $slug . '-editor',
				'editor_style'  => $slug . '-editor',
				'style'         => $slug . '-frontend',
				'render_callback' => array($this,'coursecurriculum')
			)
		);
		register_block_type(
			$slug . '/courseinstructorfield',
			array(
				'editor_script' => $slug . '-editor',
				'editor_style'  => $slug . '-editor',
				'style'         => $slug . '-frontend',
				'render_callback' => array($this,'courseinstructorfield')
			)
		);
		register_block_type(
			$slug . '/courseinstructorcard',
			array(
				'editor_script' => $slug . '-editor',
				'editor_style'  => $slug . '-editor',
				'style'         => $slug . '-frontend',
				'render_callback' => array($this,'courseinstructorcard')
			)
		);
		register_block_type(
			$slug . '/courseinstructordata',
			array(
				'editor_script' => $slug . '-editor',
				'editor_style'  => $slug . '-editor',
				'style'         => $slug . '-frontend',
				'render_callback' => array($this,'courseinstructordata')
			)
		);
		register_block_type(
			$slug . '/coursepricing',
			array(
				'editor_script' => $slug . '-editor',
				'editor_style'  => $slug . '-editor',
				'style'         => $slug . '-frontend',
				'render_callback' => array($this,'coursepricing')
			)
		);

		register_block_type(
			$slug . '/coursereviews',
			array(
				'editor_script' => $slug . '-editor',
				'editor_style'  => $slug . '-editor',
				'style'         => $slug . '-frontend',
				'render_callback' => array($this,'coursereviews')
			)
		);
		register_block_type(
			$slug . '/userreviews',
			array(
				'editor_script' => $slug . '-editor',
				'editor_style'  => $slug . '-editor',
				'style'         => $slug . '-frontend',
				'render_callback' => array($this,'userreviews')
			)
		);

		register_block_type(
			$slug . '/coursedirectory',
			array(
				'editor_script' => $slug . '-editor',
				'editor_style'  => $slug . '-editor',
				'style'         => $slug . '-frontend',
				'render_callback' => array($this,'coursedirectory')
			)
		);

		register_block_type(
			$slug . '/relatedcourses',
			array(
				'editor_script' => $slug . '-editor',
				'editor_style'  => $slug . '-editor',
				'style'         => $slug . '-frontend',
				'render_callback' => array($this,'related_courses')
			)
		);

	}

	function coursedirectory($settings){
		$html='';
		$defaults = array(
			'courses_per_page' => array('size'=>6),
			'card_style' => 'course_card',
			'per_row'=>array('size'=>100,'unit'=>'%'),
			'max_per_row'=>array('size'=>100,'unit'=>'%'),
			'show_filters'=>true,
			'instructor'=>true,
			'price'=>true,
			'pagination'=>true,
			'search_courses'=>true,
			'sort_options'=>true,
			'order'=>'alphabetical',
			'show_course_popup'=>false,

		);

		$settings = wp_parse_args($settings,$defaults);
		if(!empty($settings['courses_per_page'])){
			$settings['courses_per_page'] = array('size'=>$settings['courses_per_page']);
		}
		$this->settings = $settings;

		wp_enqueue_script('nouislider',plugins_url('../../assets/js/nouislider.min.js',__FILE__),array(),WPLMS_PLUGIN_VERSION,true);
		wp_enqueue_style('nouislider_css',plugins_url('../../assets/css/nouislider.min.css',__FILE__),array(),WPLMS_PLUGIN_VERSION);
		wp_enqueue_script('flatpickr',plugins_url('../../assets/js/flatpickr.min.js',__FILE__),array(),WPLMS_PLUGIN_VERSION,true);
		wp_enqueue_script('wnumb',plugins_url('../../assets/js/wNumb.min.js',__FILE__),array(),WPLMS_PLUGIN_VERSION,true);
		
		wp_enqueue_script('wplms-course-directory-js',plugins_url('../../assets/js/course_directory.js',__FILE__),array('wp-element','wp-data'),WPLMS_PLUGIN_VERSION,true);
		
		wp_enqueue_style('vicons');

		wp_enqueue_style('wplms-front',plugins_url('../../assets/css/course_directory.css',__FILE__),array(),WPLMS_PLUGIN_VERSION);
		ob_start();	
		if($settings['show_course_popup']){
			wp_enqueue_script('singlecourse',plugins_url('../../assets/js/singlecourse.js',__FILE__),array('wp-element','wp-data'),WPLMS_PLUGIN_VERSION,true);
			add_filter('wplms_load_dynamic_scripts',function($scripts){
	        	$scripts[] = array('id'=>'course_button','src'=>plugins_url('../../assets/js/course_button.js',__FILE__) );
	        	return $scripts;
	        });
	        if(class_exists('WPLMS_Course_Component_Init')){
	            $init = WPLMS_Course_Component_Init::init();
	            wp_localize_script('singlecourse','wplms_course_data',$init->get_wplms_course_data()); 

	        }
	        

	      	$init = WPLMS_4_Init::init();
	      	if(empty($init->footer_course_button_scripts)){
	        $init->footer_course_button_scripts=1;
	        ?>
	        <style>#wplms_popup .popup_wrapper{z-index:999999 !important;}</style>
	        <script>
	            
	            var wplms_course_data = <?php echo json_encode($jsondata) ?>;

	            document.addEventListener('single_course_loaded', (e) => {

	            	if(document.querySelectorAll('.course_button')){
		                document.querySelectorAll('.course_button').forEach(function(el){
		                  if(el.querySelector('a')){
		                    el.querySelector('a').addEventListener('click',function(){
		                      if(!el.classList.contains('paid_course')){
		                        const event = new Event('vibebp_show_login_popup');
		                        document.dispatchEvent(event);
		                      }
		                    });
		                  }
		                });
		              }
		              if(document.querySelectorAll('.course_button .is-loading,.course_button.is-loading')){
		                setTimeout(function(){  document.querySelectorAll('.course_button .is-loading,.course_button.is-loading').forEach(function(el){
		                    el.classList.remove('is-loading');},2000); 
		                  });
		              }

					

				    if(window.hasOwnProperty('wplms_course_data') && window.wplms_course_data.hasOwnProperty('dynamic_scripts') && window.wplms_course_data.dynamic_scripts && window.wplms_course_data.dynamic_scripts.length){
						window.wplms_course_data.dynamic_scripts.map(function(_script,i){
							if(document.getElementById(_script.id)){
								document.getElementById(_script.id).remove();
							}	
						    const script = document.createElement("script");
						    script.src =_script.src;
						    script.setAttribute("id", _script.id);
							document.body.appendChild(script);
							if(script.id=='course_button'){
								script.onload = () => {
							        // script has loaded, you can now use it safely
							        
							        // ... do something with the newly loaded script
							        var event = new CustomEvent('userLoaded');
									document.dispatchEvent(event);
							    } 
								
							}
						});
						
					}
					

				});

	        	</script>
	            <style>.course_button.is-loading{opacity:0.4;}</style>
	        <?php
	        }
		    

		}


		if($settings['card_style'] == 'course_card'){
			$a=array(
				'post_type'=>'course-card',
				'posts_per_page'=>-1
			);
			
			$qargs = new WP_Query($a);
			
			if($qargs->have_posts()){
				
				while($qargs->have_posts()){
					$qargs->the_post();
					$upload_dir   = wp_upload_dir();
					
					if(file_exists($upload_dir['basedir'].'/elementor/css/post-'.get_the_ID().'.css')){
						wp_enqueue_style('wplms-course-card-'.get_the_ID(),$upload_dir['baseurl'].'/elementor/css/post-'.get_the_ID().'.css?v='.rand(0,999) ,array());	
					}
				}
			}
			wp_reset_postdata();
		}
		
		$blog_id = '';
	    if(function_exists('get_current_blog_id')){
	        $blog_id = get_current_blog_id();
	    }
	    $this->args = array(
			'api'=>array( 
				'url'=>apply_filters('vibebp_rest_api',get_rest_url($blog_id,BP_COURSE_API_NAMESPACE)),
				'client_id'=>function_exists('vibebp_get_setting')?vibebp_get_setting('client_id'):'',
				'google_maps_api_key'=>function_exists('vibebp_get_setting')?vibebp_get_setting('google_maps_api_key'):'',
				'map_marker'=>plugins_url('../../../assets/images/marker.png',__FILE__)
			),
			'settings'=>$settings,
			'directory_sorters'=>wplms_courses_sort_options(),
			'translations'=>array(
				'select_option' => _x('Select Option','','wplms'),
				'search_text'=>__('Type to search','wplms'),
				'all'=>__('All','wplms'),
				'no_courses_found'=>__('No Courses found !','wplms'),
				'course_types'=>__('course Type','wplms'),
				'map_search'=>__('Map Search','wplms'),
				'show_filters'=>__('Show Filters','wplms'),
				'close_filters'=>__('Close Filters','wplms'),
				'clear_all'=>__('Clear All','wplms'),
				'error_loading_data'=>_x('Error loading data','','wplms'),
			)
		);
		wp_localize_Script('wplms-course-directory-js','course_directory',$this->args);
		$args = array(
			'post_type'		=> 'course',
			'posts_per_page'	=>$settings['courses_per_page']['size']
		);
		
		$course_query = new WP_Query($args);
		
    		
		
		?>
		<div id="wplms_courses_directory" <?php  echo isset($settings['show_map'])?'class="with_map"':''?>>
			<div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
			<div class="wplms_courses_directory_wrapper opacity_0">
			
			<div class="wplms_courses_directory_main">
				<div class="wplms_courses_directory_header">
				<?php
					if($settings['search_courses']){
						?>
						<div class="wplms_course_search">
							<input type="text" placeholder="<?php _e('Type to search','wplms'); ?>" />
						</div>
						<?php
					}

					if($settings['sort_options']){

						$default_sorters = wplms_courses_sort_options();
						?>
						<div class="wplms_courses_sort">
							<select>
								<?php
								foreach($default_sorters as $key => $val){
									echo '<option value="'.$key.'">'.$val.'</option>';
								}
								?>
							</select>
						</div>
						<?php
					
					}
				?>
				</div>
				<div class="wplms_courses_directory <?php echo $settings['card_style'];?>">
					<?php 
					if( $course_query->have_posts() ) {

						while($course_query->have_posts()){
							$course_query->the_post();
							global $post;
							echo '<div class=""course_block"><h3>'.get_the_title().'</h3>';
							echo get_the_excerpt().'</div>';
						}
						wp_reset_postdata();
					}
					
					?>
				</div>
				<?php
				if( $course_query->found_posts > $settings['courses_per_page']){
					if($settings['courses_pagination']){
						?>
						<div class="wplms_courses_directory_pagination">
							<span>1</span>
							<a class="page_name">2</a>
							<?php
								$end = ceil($course_query->found_posts/$settings['courses_per_page']);
								if($end === 3){
									echo '<a class="page_name">'.$end.'</a>';
								}else if($end > 3){
									echo '<span>...</span><a class="page_name">'.$end.'</a>';
								}
							?>
						</div>
						<?php
					}
				}
				?>
			</div>
		</div>
		</div>
		<?php
		$html .= ob_get_clean();
		global $post;
		$this->post_id = $post->ID;
		add_filter('vibebp_inside_pwa_scripts',function($scripts){

			$scripts['nouislider']= plugins_url('../../assets/js/nouislider.min.js',__FILE__);
			//
			$scripts['wnumb']=plugins_url('../../assets/js/wNumb.min.js',__FILE__);
			$scripts['course_directory']= plugins_url('../../assets/js/course_directory.js',__FILE__).'?v='.WPLMS_PLUGIN_VERSION; 
			return $scripts;
		});
		add_filter('vibebp_inside_pwa_styles',array($this,'pwa_styles'),10,2);
		add_filter('vibebp_inside_pwa_objects',array($this,'pwa_object'));

		return $html;
	}

	function pwa_styles($styles,$post_id){
		$styles['nouislider_css']=plugins_url('../../assets/css/nouislider.min.css',__FILE__);
		$styles['course_directory_css']=plugins_url('../../assets/css/course_directory.css',__FILE__).'?v='.WPLMS_PLUGIN_VERSION;		
		$init = VibeBP_Init::init();

		$upload_dir   = wp_upload_dir();
		if(file_exists($upload_dir['basedir'].'/elementor/css/post-'.$post_id.'.css')){
			$styles['elementor_specific_css']=$upload_dir['baseurl'].'/elementor/css/post-'.$post_id.'.css?v='.WPLMS_PLUGIN_VERSION;	
		}

		if($this->settings['card_style'] == 'course_card'){
			$a=array(
				'post_type'=>'course-card',
				'posts_per_page'=>-1
			);
			
			$qargs = new WP_Query($a);
			
			if($qargs->have_posts()){
				
				while($qargs->have_posts()){
					$qargs->the_post();
					$upload_dir   = wp_upload_dir();
					
					if(file_exists($upload_dir['basedir'].'/elementor/css/post-'.get_the_ID().'.css')){
						$styles['wplms-course-card-'.get_the_ID()]=$upload_dir['baseurl'].'/elementor/css/post-'.get_the_ID().'.css?v='.WPLMS_PLUGIN_VERSION;	
					}
				}
			}
			wp_reset_postdata();
		}
		return $styles;
	}
	function pwa_object($objects){
		$objects['course_directory']= $this->args; 
		return $objects;
	}

	function userreviews($settings){
	
		$shortcode = '[user_reviews hide_if_none="'.(empty($settings['hide_if_none'])?'0':$settings['hide_if_none']).'" total="'.(empty($settings['total'])?10:$settings['total']).'" breakup="'.(empty($settings['total'])?1:$settings['breakup']).'" orderby="'.(empty($settings['orderby'])?'':$settings['orderby']).'" row="'.(empty($settings['row'])?'':$settings['row']).' style="'.(empty($settings['style'])?'':$settings['style']).'"]';

		return do_shortcode($shortcode);
	}

	function coursereviews($settings){

		$defaults = array(
			'number'=>5,
			'color'=>'inherit',
			'hide_if_none'=>0,
			'rating_count'=>0,
			
			'orderby'=>'',
			'title'=>_x('Reviews','','wplms'),
		);

		$settings = wp_parse_args($settings,$defaults);

		$shortcode = '[course_reviews hide_if_none="'.$settings['hide_if_none'].'" number="'.$settings['number'].'" show_count="'.$settings['rating_count'].'" show_breakup="'.$settings['hide_if_none'].'" orderby="'.$settings['orderby'].'" show_helpfulness="'.$settings['orderby'].'"]'.$settings['title'].'[/course_reviews]';

		return do_shortcode($shortcode);
	}

	function coursepricing($settings){
		wp_enqueue_style('wplms_plugin_elementor',plugins_url('../../assets/css/general.css',__FILE__),array(),WPLMS_PLUGIN_VERSION);
		if(empty($settings['pricing_display'])){$settings['pricing_display']='';}
		$shortcode = '[course_pricing  style="'.($settings['pricing_display']).'"]';

		return '<div class="course_pricing">'.do_shortcode($shortcode).'</div>';
	}

	function courseinstructordata($settings){
		wp_enqueue_style('wplms_plugin_elementor',plugins_url('../../assets/css/general.css',__FILE__),array(),WPLMS_PLUGIN_VERSION);
		$defaults = array(
			'data'=>'avarage_rating',
			
		);

		$settings = wp_parse_args($settings,$defaults);
		$shortcode = '[course_instructor_data data="'.$settings['data'].'"]';
		return '<div class="course_instructor_data">'.do_shortcode($shortcode).'</div>';
	}

	function courseinstructorcard($settings){

		wp_enqueue_style('wplms_plugin_elementor',plugins_url('../../assets/css/general.css',__FILE__),array(),WPLMS_PLUGIN_VERSION);
		$defaults = array(
			'card_id'=>0,
		);

		$settings = wp_parse_args($settings,$defaults);
		$shortcode = '[course_instructor_card card_id="'.$settings['card_id'].'" ]';

		return '<div class="course_instructor_card">'.do_shortcode($shortcode).'</div>';
	}

	function courseinstructorfield($settings){
		wp_enqueue_style('wplms_plugin_elementor',plugins_url('../../assets/css/general.css',__FILE__),array(),WPLMS_PLUGIN_VERSION);
		$defaults = array(
			'field_id'=>1,
			'style'=>''
			
		);

		$settings = wp_parse_args($settings,$defaults);
		$shortcode = '[course_instructor_field field_id="'.$settings['field_id'].'" style="'.$settings['style'].'"]';

		return '<div class="course_instructor_field_text">'.do_shortcode($shortcode).'</div>';
	}

	function coursecurriculum($settings){

		$shortcode = '[course_curriculum]';

		return do_shortcode($shortcode);
	}

	function coursebutton($settings){
		$defaults = array(
			'bg'=>'inherit',
			'color'=>'inherit',
			'show_price'=>0,
			'hide_extras'=>0,
			'font_size'=>array('size'=>'100','unit'=>'%'),
			'height'=>array('size'=>'100','unit'=>'%'),
			'radius'=>array('size'=>'100','unit'=>'%'),
			'content'=>_x('Take this course','','wplms'),
			'width'=>array('size'=>'100','unit'=>'%'),
			'element'=>'',
		);

		$settings = wp_parse_args($settings,$defaults);
		$shortcode = '[course_button bg="'.$settings['bg'].'" width="'.$settings['width']['size'].$settings['width']['unit'].'" height="'.$settings['height']['size'].$settings['height']['unit'].'" radius="'.$settings['radius']['size'].$settings['radius']['unit'].'" font_size="'.$settings['font_size']['size'].$settings['font_size']['unit'].'" color="'.$settings['color'].'" show_price="'.$settings['show_price'].'" hide_extras="'.$settings['hide_extras'].'"]'.$settings['content'].'[/course_button]';

		return do_shortcode($shortcode);
	}

	function coursefeatured($settings){

		$attr='';
		foreach($settings as $k=>$v){
			$attr.=' '.$k.'='.$v;
		}
		$shortcode = '[course_featured settings='.$attr.']';

		return do_shortcode($shortcode);
	}

	function courseinfo($settings){
		$defaults = array(
			'color'=>'inherit',
			'info'=>'title',
			'font_size'=>array('size'=>'22','unit'=>'px'),
			'element'=>'',
		);

		$settings = wp_parse_args($settings,$defaults);

		$shortcode = '[course_info info="'.$settings['info'].'" font_size="'.$settings['font_size']['size'].$settings['font_size']['unit'].'" color="'.$settings['color'].'"]';
		$html = '';

		switch($settings['element']){
			case 'h1':
				$html = '<h1 class="course_element_text">'.do_shortcode($shortcode).'</h1>';
			break;
			case 'h2':
				$html = '<h2 class="course_element_text">'.do_shortcode($shortcode).'</h2>';
			break;
			case 'h3':
				$html = '<h3 class="course_element_text">'.do_shortcode($shortcode).'</h3>';
			case 'h4':
				$html = '<h4 class="course_element_text">'.do_shortcode($shortcode).'</h4>';
			case 'h5':
				$html = '<h5 class="course_element_text">'.do_shortcode($shortcode).'</h5>';
			break;
			case 'p':
				$html = '<p class="course_element_text">'.do_shortcode($shortcode).'</p>';
			break;
			default:
				$html = '<div class="course_element_text">'.do_shortcode($shortcode).'</div>';
			break;
		}

		return '<div class="" style="font-size:'.$settings['font_size']['size'].$settings['font_size']['unit'].';color:'.$settings['color'].'">'.$html.'</div>';
	}

	function block_category( $categories, $post ) {
		
        return array_merge(
                $categories,
                array(
                        array(
                                'slug' => 'wplms',
								'title' => __( 'Wplms', 'wplms' ),
                        ),
                )
        );
	}

	function vibebp_block_assets(){
	
		// Shortcut for the slug.
		$slug = $this->_slug;
		$color_options = apply_filters('vibe_bp_gutenberg_text_color_options',array(

			array(
				'name'=>_x('Black','','vibebp'),
				'color'=>'#000000'
			),
			array(
				'name'=>_x('White','','vibebp'),
				'color'=>'#ffffff'
			),

		));

		$fontsizeunit_options = apply_filters('vibe_bp_gutenberg_text_color_options',array(
			'px','em','rem','pt','vh','vw','%'
		));
		$groups = array();
		$fields = array();
		if(function_exists('bp_xprofile_get_groups')){
			$groups = bp_xprofile_get_groups( array(
				'fetch_fields' => true
			) );
		}
		

		if(!empty($groups)){
			foreach($groups as $group){
				if(!empty($group->fields)){
					foreach ( $group->fields as $field ) {
						//$field = xprofile_get_field( $field->id );
						$fields[$field->id]=$field->name;
					}
				}
			}
		}


		$profile_field_styles = apply_filters('wplms_course_field_styles',array(
					'' =>__('Default','vibebp'),
					'stacked' =>__('Stacked','vibebp'),
					'spaced'=>__('Spaced','vibebp'),
					'nolabel'=>__('No Label','vibebp'),
					'icon'=>__('Icons','vibebp'),
				));

		$fontwieght_options = apply_filters('vibe_bp_gutenberg_text_color_options',array(
			'100','200','300','400','500','600','700','800','900'
		));

		$cards = array();

		$args = array(
			'post_type'=>'member-card',
			'post_status'=>'publish',
			'posts_per_page'=>-1
		);

		global $post;
		$temp_post = $post;
		$cards[] = array('value'=>0,'label'=>_x('Select','','wplms'));
		$results  = new WP_Query($args);
       	if($results->have_posts()){
       		while($results->have_posts()){
       			$results->the_post();
       			
       			$cards[] = array('value'=>$post->ID,'label'=>$post->post_title); 
	
			}
		}
		wp_reset_postdata();
		$post = $temp_post;


		$course_taxes = [];
		$taxonomies = get_taxonomies( [ 'object_type' => [ 'course' ] ] );
		$course_taxonomies = [];
		foreach ($taxonomies as $key => $tax) {
			$tax_name = str_replace('-', '', $tax);
			$tax_name = strtoupper($tax_name);
			$course_taxonomies[$tax] = $tax_name;
		}
		$course_taxonomies = apply_filters('wplms_course_taxonomies',$course_taxonomies);

		foreach($course_taxonomies as $taxonomy=>$label){
			

			$term_query = new WP_Term_Query( array(
				'taxonomy'=>$taxonomy,
				'hide_empty'=>false
			) );

			$terms = [array('value'=>'all','label'=>__('All','wplms')),array('value'=>'','label'=>__('None','wplms'))];

			if ( ! empty( $term_query->terms ) ) {
				foreach ( $term_query ->terms as $term ) {
					$terms[]= array('value'=>$term->term_id,'label'=>$term->name);
				}
			}
			$course_taxes[] = array(
				'id'=>'taxonomy__'.$taxonomy,
				'label'=>sprintf(__( '%s select', 'wplms' ),$taxonomy),
				'options' => $terms
			);

		}
		$course_meta_fields = [];
		$course_metabox = vibe_meta_box_arrays('course');
		foreach($course_metabox as $metabox){
			if(in_array($metabox['type'],array('showhide','number','date'))){
				$course_meta_fields[] = array(
					'id'=>'meta__'.$metabox['id'],
					'label'=>$metabox['label'],
				);
			}
		}


		$settings = apply_filters('wplms_gutenberg_data',array(
			'default_avatar'=>plugins_url( '../../assets/images/avatar.jpg',  __FILE__ ),
			'default_profile_value'=>_x('default value','','vibebp'),
			'default_name'=>_x('default name','','vibebp'),
			'cards' => $cards,
			'course_info_options'=>apply_filters('wplms_course_info_display_options',[
					'title'=>__('Course Title','wplms'),
					'excerpt'=>__('Course short description','wplms'),
					'description'=>__('Course Full description','wplms'),
					'average_rating'=>__('Average Rating','wplms'),
					'average_rating_count'=>__('Average Rating Score','wplms'),
					'review_count'=>__('Review Count','wplms'),
					'student_count'=>__('Student Count','wplms'),
					'last_update'=>__('Last updated','wplms'),
					'instructor_name'=>__('Instructor name','wplms'),
					'course_duration'=>__('Course Duration','wplms'),
					'cumulative_time'=>__('Cumulative time of Units & Quiz','wplms'),
					'curriculum_item_count'=>__('Curriculum Item count','wplms'),
					'certificate'=>__('Certificate link','wplms'),
					'badge'=>__('Badge ','wplms'),
					'course-cat'=>__('Course Category ','wplms'),
				]),
			'profile_field_styles' => $profile_field_styles,
			'course_inst_reviews_orderby' =>[
					'0' => __('Recent rating first','wplms'),
					'highest_rated' => __('Highest rating first','wplms'),
				],
			'user_reviews_row' => [
					'25'=>_x('4 in Row','elementor','wplms'),
					'33'=>_x('3 in Row','elementor','wplms'),
					'25'=>_x('2 in Row','elementor','wplms'),
				],
			'user_reviews_style' =>[
					'carousel'=>_x('Carousel','elementor','wplms'),
					'grid'=>_x('Grid','elementor','wplms'),
				],
			'user_types_options' => [
					'instructor'=>_x('Reviews posted for Instructor','elementor','wplms'),
					'student'=>_x('Reviews Posted by Student','elementor','wplms'),
				],
			'reviews_orderby' => [
					'0' => __('Recent rating first','wplms'),
					'highest_rated' => __('Highest rating first','wplms'),
					'helpful' => __('Most helpful first (if enabled)','wplms'),
				],
			'instructor_data_options'=>[
					'image'=>__('Instructor Image','wplms'),
					'avarage_rating'=>__('Average Rating','wplms'),
					'review_count'=>__('Review Count','wplms'),
					'student_count'=>__('Student Count','wplms'),
					'course_count'=>__('Course Count','wplms')
				],
			'profile_fields' => $fields,
			'font_size_units'=>$fontsizeunit_options,
			'elements' =>apply_filters('wplms_course_info_display_elements_options', [
					'h1'=>'H1',
					'h2'=>'H2',
					'h3'=>'H3',
					'h4'=>'H4',
					'h5'=>'H5',
					'p'=>'p',
					'div'=>'div',
				]),
			'align_options' => [
					 [
						'label' => __( 'Left', 'wplms' ),
						'value' => 'left',
					],
					 [
						'label' => __( 'Center', 'wplms' ),
						'value' => 'center',
					],
					 [
						'label' => __( 'Right', 'wplms' ),
						'value' => 'right',
					],
				],
			'current_user'=>wp_get_current_user(),
			'api_url'=>home_url().'/wp-json/'.WPLMS_API_NAMESPACE,
			'course_card_styles'=> wplms_get_featured_cards(),
			'course_sort_options'=>wplms_courses_sort_options(),
			'course_taxonomy_fields' => $course_taxes,
			'course_meta_fields' => $course_meta_fields,
		));

		

		// Register block editor script for backend.
		wp_enqueue_script(
			'wplms-blocks-block-js', // Handle.
			plugins_url( '../assets/js/new_blocks.js', dirname( __FILE__ ) ),
			array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ),
			WPLMS_PLUGIN_VERSION, 
			true 
		);

		// Register block editor styles for backend.
		wp_register_style(
			'wplms-blocks-block-editor-css',
			plugins_url( '../assets/css/new_blocks.css', dirname( __FILE__ ) ),
			array( 'wp-edit-blocks' ), 
			WPLMS_PLUGIN_VERSION 
		);
		wp_localize_script( 'wplms-blocks-block-js', 'wplms_gutenberg_data', $settings );
	}


	function related_courses(){

		return do_shortcode('[related_courses]');
	}
	
	
}

Wplms_Plugin_Register_Guten_Blocks::register();
