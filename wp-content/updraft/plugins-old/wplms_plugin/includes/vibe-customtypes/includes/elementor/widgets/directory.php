<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}



 class WPLMS_Course_Directory extends \Elementor\Widget_Base  // We'll use this just to avoid function name conflicts 
{

    public function get_name() {
		return 'course_directory';
	}

	public function get_title() {
		return __( 'Course Directory', 'wplms' );
	}

	public function get_icon() {
		return 'vicon vicon-layout-tab-v';
	}

	public function get_categories() {
		return [ 'wplms' ];
	}

	protected function _register_controls() {


		

		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Controls', 'wplms' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'courses_per_page',
			[
				'label' =>__('Total Number of Courses in view', 'wplms'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range'=>[
					'min' => 1,
					'max' => 20,
					'step' => 1,
				],
				'default' => [
					'size'=>1,
				]
			]
		);
		$this->add_control(
			'per_row',
			[
				'label' =>__('Min-width of Course block', 'vibebp'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'=>[
					'px' => [
						'min' => 200,
						'max' => 760,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'size'=>240,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .wplms_courses_directory' => 'grid-template-columns: repeat(auto-fit,minmax({{SIZE}}{{UNIT}},1fr));',
				],
			]
		);

		$this->add_control(
			'max_per_row',
			[
				'label' =>__('Max-width of Course block', 'vibebp'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'=>[
					'px' => [
						'min' => 200,
						'max' => 760,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'size'=>100,
					'unit' => '%',
				],
				'selectors' => [
					'{{WRAPPER}} .wplms_courses_directory >*' => 'max-width:{{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'show_filters',
			[
				'label' => __( 'Show Filters', 'wplms' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'0' => [
						'title' => __( 'No', 'wplms' ),
						'icon' => 'fa fa-x',
					],
					'1' => [
						'title' => __( 'Yes', 'wplms' ),
						'icon' => 'fa fa-check',
					],
				],
			]
		);
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

			$terms = ['all'=>__('All','wplms'),''=> __('None','wplms')];

			if ( ! empty( $term_query->terms ) ) {
				foreach ( $term_query ->terms as $term ) {
					$terms[$term->term_id]=$term->name;
				}
			}
			
			$this->add_control(
				'taxonomy__'.$taxonomy,
				[
					'label' => sprintf(__( '%s select', 'wplms' ),$taxonomy),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => '',
					'options' => $terms
				]
			);

		}
		
		$this->add_control(
			'instructor',
			[
				'label' => __( 'Show Instructor Filter', 'wplms' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'0' => [
						'title' => __( 'No', 'wplms' ),
						'icon' => 'fa fa-x',
					],
					'1' => [
						'title' => __( 'Yes', 'wplms' ),
						'icon' => 'fa fa-check',
					],
				],
			]
		);


		$this->add_control(
			'price',
			[
				'label' => __( 'Show Price Filter', 'wplms' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'0' => [
						'title' => __( 'No', 'wplms' ),
						'icon' => 'fa fa-x',
					],
					'1' => [
						'title' => __( 'Yes', 'wplms' ),
						'icon' => 'fa fa-check',
					],
				],
			]
		);

		

		$course_metabox = vibe_meta_box_arrays('course');
		foreach($course_metabox as $metabox){
			if(in_array($metabox['type'],array('showhide','number','date'))){
				$this->add_control(
					'meta__'.$metabox['id'],
					[
						'label' => $metabox['label'],
						'type' => \Elementor\Controls_Manager::CHOOSE,
						'options' => [
							'0' => [
								'title' => __( 'No', 'wplms' ),
								'icon' => 'fa fa-x',
							],
							'1' => [
								'title' => __( 'Yes', 'wplms' ),
								'icon' => 'fa fa-check',
							],
						],
					]
				);
			}
		}


		
		
		
		$this->add_control(
			'card_style',
			[
				'label' => __( 'Card Style', 'wplms' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => wplms_get_featured_cards()
			]
		);


		$this->add_control(
			'pagination',
			[
				'label' => __( 'Show Pagination', 'wplms' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'0' => [
						'title' => __( 'No', 'wplms' ),
						'icon' => 'fa fa-x',
					],
					'1' => [
						'title' => __( 'Yes', 'wplms' ),
						'icon' => 'fa fa-check',
					],
				],
			]
		);

		$this->add_control(
			'search_courses', [
				'label' => __( 'Show Search', 'wplms' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'0' => [
						'title' => __( 'No', 'wplms' ),
						'icon' => 'fa fa-x',
					],
					'1' => [
						'title' => __( 'Yes', 'wplms' ),
						'icon' => 'fa fa-check',
					],
				],
			]
		);
		$this->add_control(
			'sort_options', [
				'label' => __( 'Show Sort options', 'wplms' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'0' => [
						'title' => __( 'No', 'wplms' ),
						'icon' => 'fa fa-x',
					],
					'1' => [
						'title' => __( 'Yes', 'wplms' ),
						'icon' => 'fa fa-check',
					],
				],
			]
		);

		$this->add_control(
			'order',
			[
				'label' => __( 'Default Order', 'wplms' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'recent',
				'options' => wplms_courses_sort_options()
			]
		);

		$this->add_control(
			'show_course_popup',
			[
				'label' => __( 'Show course popup', 'wplms' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'0' => [
						'title' => __( 'No', 'wplms' ),
						'icon' => 'fa fa-x',
					],
					'1' => [
						'title' => __( 'Yes', 'wplms' ),
						'icon' => 'fa fa-check',
					],
				],
			]
		);
		$this->add_control(
			'hide_filters',
			[
				'label' => __( 'Hide filters by default', 'wplms' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'0' => [
						'title' => __( 'No', 'wplms' ),
						'icon' => 'fa fa-x',
					],
					'1' => [
						'title' => __( 'Yes', 'wplms' ),
						'icon' => 'fa fa-check',
					],
				],
			]
		);
	}



	protected function render() {

		$settings = $this->get_settings_for_display();
		
		$this->settings = $settings;

		wp_enqueue_script('nouislider',plugins_url('../../../../../assets/js/nouislider.min.js',__FILE__),array(),WPLMS_PLUGIN_VERSION,true);
		wp_enqueue_style('nouislider_css',plugins_url('../../../../../assets/css/nouislider.min.css',__FILE__),array(),WPLMS_PLUGIN_VERSION);
		wp_enqueue_script('flatpickr',plugins_url('../../../../../assets/js/flatpickr.min.js',__FILE__),array(),WPLMS_PLUGIN_VERSION,true);
		wp_enqueue_script('wnumb',plugins_url('../../../../../assets/js/wNumb.min.js',__FILE__),array(),WPLMS_PLUGIN_VERSION,true);
		
		wp_enqueue_script('wplms-course-directory-js',plugins_url('../../../../../assets/js/course_directory.js',__FILE__),array('wp-element','wp-data'),WPLMS_PLUGIN_VERSION,true);
		
		wp_enqueue_style('vicons');

		wp_enqueue_style('wplms-front',plugins_url('../../../../../assets/css/course_directory.css',__FILE__),array(),WPLMS_PLUGIN_VERSION);	
		if($settings['show_course_popup']){
			wp_enqueue_script('singlecourse',plugins_url('../../../../../assets/js/singlecourse.js',__FILE__),array('wp-element','wp-data'),WPLMS_PLUGIN_VERSION,true);
			add_filter('wplms_load_dynamic_scripts',function($scripts){
	        	$scripts[] = array('id'=>'course_button','src'=>plugins_url('../../../../../assets/js/course_button.js',__FILE__) );
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

					if($settings['sort_courses']){

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
				<div class="wplms_courses_directory <?php echo $settings['style'];?>">
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
		global $post;
		$this->post_id = $post->ID;
		add_filter('vibebp_inside_pwa_scripts',function($scripts){

			$scripts['nouislider']= plugins_url('../../../../../assets/js/nouislider.min.js',__FILE__);
			//
			$scripts['wnumb']=plugins_url('../../../../../assets/js/wNumb.min.js',__FILE__);
			$scripts['course_directory']= plugins_url('../../../../../assets/js/course_directory.js',__FILE__).'?v='.WPLMS_PLUGIN_VERSION; 
			return $scripts;
		});
		add_filter('vibebp_inside_pwa_styles',array($this,'pwa_styles'),10,2);
		add_filter('vibebp_inside_pwa_objects',array($this,'pwa_object'));
	}

	function pwa_styles($styles,$post_id){
		$styles['nouislider_css']=plugins_url('../../../../../assets/css/nouislider.min.css',__FILE__);
		$styles['course_directory_css']=plugins_url('../../../../../assets/css/course_directory.css',__FILE__).'?v='.WPLMS_PLUGIN_VERSION;		
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
}