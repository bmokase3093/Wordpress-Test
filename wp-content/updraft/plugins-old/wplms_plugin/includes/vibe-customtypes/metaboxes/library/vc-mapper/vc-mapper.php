<?php

if(!class_exists('Wplms_Vc'))
{   
    class Wplms_Vc  // We'll use this just to avoid function name conflicts 
    {
        

        public static $instance;
    
        public static function init(){
            if ( is_null( self::$instance ) )
                self::$instance = new Wplms_Vc;
            return self::$instance;
        }
       


       
        function __construct(){
        	

		    if ( in_array( 'js_composer/js_composer.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || (function_exists('is_plugin_active_for_network') && is_plugin_active_for_network( 'js_composer/js_composer.php'))){

				$this->v_widget_areas = $this->v_post_types =array();
	            
	            global $wp_registered_sidebars;
				foreach($wp_registered_sidebars as $sidebar){
					$this->v_widget_areas[$sidebar['id']]=$sidebar['id'];
				};
			              
			    $this->v_post_types = array(
			    	__('Select Post Type','wplms') => '',
			    	__('Post','wplms') => 'post',
			    	__('Page','wplms') => 'page',
			    	__('Courses','wplms') => 'course',
			    	__('Quiz','wplms') => 'quiz',
			    	__('Assignments','wplms') => 'wplms-assignments',
			    	__('Certificates','wplms') => 'certificate',
			    	__('Testimonials','wplms') => 'testimonials',
			    	__('Events','wplms') => 'ajde-event',
			    	__('Course News','wplms') => 'news',
			    );

			    $this->taxonomy=array(
			    	'course'=>array(
			    		'course-cat'=>__('Course Category','wplms'),
			    		'location'=>__('Course Location','wplms'),
			    		'level'=>__('Course Level','wplms'),
			    	),
			    	'quiz'=>array(
			    		'quiz-type'=>__('Quiz Type','wplms'),
			    	),
			    	'wplms-assignments'=>array(
			    		'assignment-type'=>__('Assignment Type','wplms'),
			    	)
			    );

			     //Get List of All Products
			     
			    
			    $this->v_thumb_styles = apply_filters('vibe_builder_thumb_styles',array(
			                            ''=> plugins_url('../images/thumb_1.png',__FILE__),
			                            'course'=> plugins_url('../images/thumb_2.png',__FILE__),
			                            'course2'=> plugins_url('../images/thumb_8.png',__FILE__),
			                            'course3'=> plugins_url('../images/thumb_8.jpg',__FILE__),
			                            'course4'=> plugins_url('../images/thumb_9.jpg',__FILE__),
			                            'course5'=> plugins_url('../images/thumb_10.jpg',__FILE__),
			                            'course6'=> plugins_url('../images/thumb_13.jpg',__FILE__),
			                            'course7'=> plugins_url('../images/course7.png',__FILE__),
			                            'course8'=> plugins_url('../images/course8.png',__FILE__),
			                            'course9'=> plugins_url('../images/course8.png',__FILE__),
			                            'course10'=> plugins_url('../images/course8.png',__FILE__),
			                            'postblock'=> plugins_url('../images/thumb_11.jpg',__FILE__),
			                            'side'=> plugins_url('../images/thumb_3.png',__FILE__),
			                            'blogpost'=> plugins_url('../images/thumb_6.png',__FILE__),
			                            'images_only'=> plugins_url('../images/thumb_4.png',__FILE__),
			                            'testimonial'=> plugins_url('../images/thumb_5.png',__FILE__),
			                            'testimonial2'=> plugins_url('../images/testimonial2.jpg',__FILE__),
			                            'event_card'=> plugins_url('../images/thumb_7.png',__FILE__),
			                            'general'=> plugins_url('../images/thumb_12.png',__FILE__),
			                            'generic'=> plugins_url('../images/generic.jpg',__FILE__),
			                            'simple'=> plugins_url('../images/simple.jpg',__FILE__),
			                            'blog_card'=> plugins_url('../images/thumb_7.png',__FILE__),
			                            'generic_card'=> plugins_url('../images/thumb_7.png',__FILE__),
			                          ));

			    add_action( 'vc_before_init', array($this,'v_builder_mapper' ));
			}
		    
		}


		function v_builder_mapper() {
		    // Title
		    vc_map(
		        array(
		            'name' => __( 'Vibe Carousel','wplms'),
		            'base' => 'v_carousel',
		            'category' => __( 'Vibe Builder' ,'wplms'),
		            'group' => 'Vibe Builder',
		            'icon'        => 'vibe-builder-icon',
		            'params' => array(
		                array(
							'type'        => 'dropdown',
							'admin_label' => true,
							'heading'     => esc_html__( 'Show Carousel title', 'wplms' ),
							'param_name'  => 'show_title',
							'value'       => array(
								esc_html__( 'No', 'wplms' )  => '0',
								esc_html__( 'Yes', 'wplms' ) => '1',
							),
						),
						array(
		                    'type' => 'textfield',
		                    'holder' => 'div',
		                    'class' => '',
		                    'heading' => __('Title/Heading', 'wplms'),
		                    'param_name' => 'title',
		                    'value' => __('Heading', 'wplms'),
		                    'dependency' => array(
								'element' => 'show_title',
								'value' => '1',
							),
		                ),
						array(
							'type'        => 'dropdown',
							'admin_label' => true,
							'heading'     => esc_html__( 'Show More link', 'wplms' ),
							'param_name'  => 'show_more',
							'value'       => array(
								esc_html__( 'No', 'wplms' )  => '0',
								esc_html__( 'Yes', 'wplms' ) => '1',
							),
						),
						array(
		                    'type' => 'textfield',
		                    'holder' => 'div',
		                    'class' => '',
		                    'heading' => __('More link', 'wplms'),
		                    'param_name' => 'more_link',
		                    'value' => '',
		                    'description' => __('Static link to more units in carousel, plus sign', 'wplms'),
							'dependency' => array(
								'element' => 'show_more',
								'value' => '1',
							),
		                ),
		                array(
							'type'        => 'dropdown',
							'admin_label' => true,
							'heading'     => esc_html__( 'Slider Controls : Direction Arrows', 'wplms' ),
							'param_name'  => 'show_controls',
							'value'       => array(
								esc_html__( 'No', 'wplms' )  => '0',
								esc_html__( 'Yes', 'wplms' ) => '1',
							),
						),
						array(
							'type'        => 'dropdown',
							'admin_label' => true,
							'heading'     => esc_html__( 'Slider Controls : Control Dots', 'wplms' ),
							'param_name'  => 'show_controlnav',
							'value'       => array(
								esc_html__( 'No', 'wplms' )  => '0',
								esc_html__( 'Yes', 'wplms' ) => '1',
							),
						),
						array(
							'type'        => 'dropdown',
							'admin_label' => true,
							'heading'     => esc_html__('Select Carousel Post Type', 'wplms'),
							'param_name'  => 'post_type',
							'value'       => $this->v_post_types,
						),
						array(
							'type'        => 'dropdown',
							'admin_label' => true,
							'heading'     => esc_html__('Order', 'wplms'),
							'param_name'  => 'course_style',
							'value'       => array(
							                __('Recently published','wplms') => 'recent',
				                __('Most Students','wplms') =>'popular' ,
				                __('Featured','wplms') => 'featured',
				                __('Highest Rated','wplms') => 'rated',
				                __('Most Reviews','wplms') => 'reviews',
				                __('Upcoming Courses (Start Date)','wplms') => 'start_date',
				                __('Expired Courses (Past Start Date)','wplms') => 'expired_start_date',
				                __('Free Courses','wplms')=>'free',
				                __('Random','wplms')=>'random'
							                ),
							'dependency' => array(
								'element' => 'post_type',
								'value' => 'course',
							),
						),
						
						array(
		                    'type' => 'textfield',
		                    'holder' => 'div',
		                    'class' => '',
		                    'heading' => __('Taxonomy ', 'wplms'),
		                    'param_name' => 'taxonomy',
		                    'value' => '',
		                    'description' => __('Optionally select a taxonomy to fetch post types from.', 'wplms'),
		                ),
		                array(
		                    'type' => 'textfield',
		                    'holder' => 'div',
		                    'class' => '',
		                    'heading' => __('Taxonomy Term', 'wplms'),
		                    'param_name' => 'term',
		                    'value' => '',
		                    'description' => __('Select a Term if taxonomy is selected', 'wplms'),
		                ),
		                array(
		                    'type' => 'textfield',
		                    'holder' => 'div',
		                    'class' => '',
		                    'heading' => __('Manually add Post IDs', 'wplms'),
		                    'param_name' => 'post_ids',
		                    'value' => '',
		                    'description' => __('Comma separated post ids, ignores taxonomy and terms.', 'wplms'),
		                ),
		                array(
							'type'        => 'radio_images',
							'admin_label' => true,
							'heading'     => esc_html__('Carousel/Rotating Block Style', 'wplms'),
							'param_name'  => 'featured_style',
							'value'       => $this->v_thumb_styles,
							'std'		  => 'course'
						),
						array(
							'type'        => 'dropdown',
							'admin_label' => true,
							'heading'     => esc_html__( 'Auto Slide', 'wplms' ),
							'param_name'  => 'auto_slide',
							'value'       => array(
								esc_html__( 'No', 'wplms' )  => '0',
								esc_html__( 'Yes', 'wplms' ) => '1',
							),
						),
						array(
		                    'type' => 'number',
		                    'class' => '',
		                    'heading' => __('Carousel Block width', 'wplms'),
		                    'param_name' => 'column_width',
		                    'value' => 268,
		                    'description' => __('Optionally set caoursel block width', 'wplms'),
		                ),
		                array(
		                    'type' => 'number',
		                    'class' => '',
		                    'heading' => __('Maximum Number of blocks in One screen', 'wplms'),
		                    'param_name' => 'carousel_max',
		                    'value' => 4,
		                    'description' => __('Responsiveness, for Largest supported screen resolution, set maximum blocks in one screen', 'wplms'),
		                ),
		                array(
		                    'type' => 'number',
		                    'class' => '',
		                    'heading' => __('Minimum Number of blocks in One screen', 'wplms'),
		                    'param_name' => 'carousel_min',
		                    'value' => '',
		                    'description' => __('Responsiveness, for smallest supported screen resolution, set minimum blocks in one screen', 'wplms'),
		                ),
		                array(
		                    'type' => 'number',
		                    'class' => '',
		                    'heading' => __('Number of blocks in one slide', 'wplms'),
		                    'param_name' => 'carousel_move',
		                    'value' => '',
		                    'description' => __('Number of blocks to rotate in one carousel slide moves', 'wplms'),
		                ),
		                array(
		                    'type' => 'number',
		                    'class' => '',
		                    'heading' => __('Total number of blocks in carousel', 'wplms'),
		                    'param_name' => 'carousel_number',
		                    'value' => '',
		                    'description' => __('Total blocks', 'wplms'),
		                ),
		                array(
		                    'type' => 'number',
		                    'class' => '',
		                    'heading' => __('Carousel Rows', 'wplms'),
		                    'param_name' => 'carousel_rows',
		                    'value' => 1,
		                    'description' => __('Rows in Carousel', 'wplms'),
		                ),
		                array(
		                    'type' => 'number',
		                    'class' => '',
		                    'heading' => __('Carousel Excerpt length', 'wplms'),
		                    'param_name' => 'carousel_excerpt_length',
		                    'value' => '',
		                    'description' => __('If carousel has excerpt then set the length of the excerpt', 'wplms'),
		                ),
		                array(
							'type'        => 'dropdown',
							'admin_label' => true,
							'heading'     => esc_html__( 'Show Link button on image hover', 'wplms' ),
							'param_name'  => 'carousel_link',
							'value'       => array(
								esc_html__( 'No', 'wplms' )  => '0',
								esc_html__( 'Yes', 'wplms' ) => '1',
							),
						),
		            )
		        )
		    );
			
			vc_map(
		        array(
		            'name' => __( 'Vibe Taxonomy Carousel' ,'wplms'),
		            'base' => 'v_taxonomy_carousel',
		            'category' => __( 'Vibe Builder' ,'wplms'),
		            'group' => 'Vibe Builder',
		            'icon'        => 'vibe-builder-icon',
		            'params' => array(
			            array(
		                    'type' => 'textfield',
		                    'holder' => 'div',
		                    'class' => '',
		                    'heading' => __('Title/Heading', 'wplms'),
		                    'param_name' => 'title',
		                    'value' => __('Heading', 'wplms'),
		                    
		                ),

		                array(
		                    'type' => 'textfield',
		                    'holder' => 'div',
		                    'class' => '',
		                    'heading' =>  __('Include Term Slugs (optional, comma separated)', 'wplms'),
		                    'param_name' => 'term_slugs',
		                    'value' => '',
		                    'description' => __('Comma separated term slugs.', 'wplms'),
		                ),
			          
			             array(
							'type'        => 'dropdown',
							'admin_label' => true,
							'heading'     => esc_html__('OrderBy', 'wplms'),
							'param_name'  => 'orderby',
							'value'       => array(__('Alphabetical', 'wplms')=>'name',__('Description', 'wplms')=>'description',__('Custom Order','wplms')=>'meta_value_num'),
							'std' => 1
						),
			          
			            array(
							'type'        => 'dropdown',
							'admin_label' => true,
							'heading'     => esc_html__('Order', 'wplms'),
							'param_name'  => 'order',
							'value'       => array(
							                'Descending' => 'DESC',
							                'Ascending' => 'ASC',
							                
							                ),
						),
			            array(
							'type'        => 'dropdown',
							'admin_label' => true,
							'heading'     => esc_html__( 'Slider Controls : Direction Arrows', 'wplms' ),
							'param_name'  => 'show_controls',
							'value'       => array(
								esc_html__( 'No', 'wplms' )  => '0',
								esc_html__( 'Yes', 'wplms' ) => '1',
							),
						),
						array(
							'type'        => 'dropdown',
							'admin_label' => true,
							'heading'     => esc_html__( 'Slider Controls : Control Dots', 'wplms' ),
							'param_name'  => 'show_controlnav',
							'value'       => array(
								esc_html__( 'No', 'wplms' )  => '0',
								esc_html__( 'Yes', 'wplms' ) => '1',
							),
						),

			            array(
							'type'        => 'dropdown',
							'admin_label' => true,
							'heading'     => esc_html__( 'Auto Slide', 'wplms' ),
							'param_name'  => 'auto_slide',
							'value'       => array(
								esc_html__( 'No', 'wplms' )  => '0',
								esc_html__( 'Yes', 'wplms' ) => '1',
							),
						),
						array(
		                    'type' => 'number',
		                    'class' => '',
		                    'heading' => __('Carousel Block width', 'wplms'),
		                    'param_name' => 'column_width',
		                    'value' => 268,
		                    'description' => __('Optionally set caoursel block width', 'wplms'),
		                ),
		                array(
		                    'type' => 'number',
		                    'class' => '',
		                    'heading' => __('Maximum Number of blocks in One screen', 'wplms'),
		                    'param_name' => 'carousel_max',
		                    'value' => 4,
		                    'description' => __('Responsiveness, for Largest supported screen resolution, set maximum blocks in one screen', 'wplms'),
		                ),
		                array(
		                    'type' => 'number',
		                    'class' => '',
		                    'heading' => __('Minimum Number of blocks in One screen', 'wplms'),
		                    'param_name' => 'carousel_min',
		                    'value' => '',
		                    'description' => __('Responsiveness, for smallest supported screen resolution, set minimum blocks in one screen', 'wplms'),
		                ),
		                array(
		                    'type' => 'number',
		                    'class' => '',
		                    'heading' => __('Number of blocks in one slide', 'wplms'),
		                    'param_name' => 'carousel_move',
		                    'value' => '',
		                    'description' => __('Number of blocks to rotate in one carousel slide moves', 'wplms'),
		                ),
		                array(
		                    'type' => 'number',
		                    'class' => '',
		                    'heading' => __('Total number of blocks in carousel', 'wplms'),
		                    'param_name' => 'carousel_number',
		                    'value' => '',
		                    'description' => __('Total blocks', 'wplms'),
		                ),
			            array(
		                    'type' => 'number',
		                    'class' => '',
		                    'heading' => __('Carousel Rows', 'wplms'),
		                    'param_name' => 'carousel_rows',
		                    'value' => 1,
		                    'description' => __('Rows in Carousel', 'wplms'),
		                ),            
			           
			        )
		        )
		    );
		    
		    vc_map(
		        array(
		            'name' => __( 'Vibe Member Carousel' ,'wplms'),
		            'base' => 'v_member_carousel',
		            'category' => __( 'Vibe Builder' ,'wplms'),
		            'group' => 'Vibe Builder',
		            'icon'        => 'vibe-builder-icon',
		            'params' => array(

			             array(
							'type'        => 'dropdown',
							'admin_label' => true,
							'heading'     => esc_html__( 'Show title', 'wplms' ),
							'param_name'  => 'show_title',
							'value'       => array(
								esc_html__( 'No', 'wplms' )  => '0',
								esc_html__( 'Yes', 'wplms' ) => '1',
							),
						),
						array(
		                    'type' => 'textfield',
		                    'holder' => 'div',
		                    'class' => '',
		                    'heading' => __('Title/Heading', 'wplms'),
		                    'param_name' => 'title',
		                    'value' => __('Heading', 'wplms'),
		                    'dependency' => array(
								'element' => 'show_title',
								'value' => '1',
							),
		                ),
						array(
							'type'        => 'dropdown',
							'admin_label' => true,
							'heading'     => esc_html__( 'Show More link', 'wplms' ),
							'param_name'  => 'show_more',
							'value'       => array(
								esc_html__( 'No', 'wplms' )  => '0',
								esc_html__( 'Yes', 'wplms' ) => '1',
							),
						),
						array(
		                    'type' => 'textfield',
		                    'holder' => 'div',
		                    'class' => '',
		                    'heading' => __('More link', 'wplms'),
		                    'param_name' => 'more_link',
		                    'value' => '',
		                    'description' => __('Static link to more units in carousel, plus sign', 'wplms'),
							'dependency' => array(
								'element' => 'show_more',
								'value' => '1',
							),
		                ),

			            array(
							'type'        => 'dropdown',
							'admin_label' => true,
							'heading'     => esc_html__( 'Slider Controls : Direction Arrows', 'wplms' ),
							'param_name'  => 'show_controls',
							'value'       => array(
								esc_html__( 'No', 'wplms' )  => '0',
								esc_html__( 'Yes', 'wplms' ) => '1',
							),
						),
						array(
							'type'        => 'dropdown',
							'admin_label' => true,
							'heading'     => esc_html__( 'Slider Controls : Control Dots', 'wplms' ),
							'param_name'  => 'show_controlnav',
							'value'       => array(
								esc_html__( 'No', 'wplms' )  => '0',
								esc_html__( 'Yes', 'wplms' ) => '1',
							),
						),
			            array(
	                        'heading' => __('Select member type', 'wplms'),
	                        'admin_label' => true,
	                        'param_name'  => 'member_type',
	                        'type' => 'dropdown',
	                        'value' => apply_filters('vibe_editor_member_types',array(_x('All','Select option in Member carousel','wplms')=>'all',_x('Student','Select option in Member carousel','wplms')=>'student',_x('Instructor','Select option in Member carousel','wplms')=>'instructor')),
	                        'std' => '',
			            ),
			            array(
		                    'type' => 'textfield',
		                    'holder' => 'div',
		                    'class' => '',
		                    'heading' =>__('Or Enter Specific Member Ids', 'wplms'),
		                    'param_name' => 'member_ids',
		                    'value' => '',
		                    'description' => __('Comma separated Member ids', 'wplms'),
		                ),    
			             
			            array(
		                    'type' => 'textfield',
		                    'holder' => 'div',
		                    'class' => '',
		                    'heading' =>__('Enter Profile fields (comma separated field "names")', 'wplms'),
		                    'param_name' => 'profile_fields',
		                    'value' => '',
		                ),
			            array(
							'type'        => 'radio_images',
							'admin_label' => true,
							'heading'     =>  __('Display Style', 'wplms'),
							'param_name'  => 'style',
							'value'       => apply_filters('vibe_builder_cmember_styles',			array(
			                                ''=> plugins_url('../images/member_block1.jpg',__FILE__),
			                                'member2'=> plugins_url('../images/member_block2.jpg',__FILE__),
			                                'member3'=> plugins_url('../images/member_block1.jpg',__FILE__),
			                                'member4'=> plugins_url('../images/member_block1.jpg',__FILE__),
			                                'member5'=> plugins_url('../images/member_block1.jpg',__FILE__),
			                            )),
							'std'		  => ''
						),
			            array(
							'type'        => 'dropdown',
							'admin_label' => true,
							'heading'     => esc_html__( 'Auto Slide', 'wplms' ),
							'param_name'  => 'auto_slide',
							'value'       => array(
								esc_html__( 'No', 'wplms' )  => '0',
								esc_html__( 'Yes', 'wplms' ) => '1',
							),
						),
			                        
			            array(
		                    'type' => 'number',
		                    'class' => '',
		                    'heading' => __('Carousel Block width', 'wplms'),
		                    'param_name' => 'column_width',
		                    'value' => 268,
		                    'description' => __('Optionally set caoursel block width', 'wplms'),
		                ),
		                array(
		                    'type' => 'number',
		                    'class' => '',
		                    'heading' => __('Maximum Number of blocks in One screen', 'wplms'),
		                    'param_name' => 'carousel_max',
		                    'value' => 4,
		                    'description' => __('Responsiveness, for Largest supported screen resolution, set maximum blocks in one screen', 'wplms'),
		                ),
		                array(
		                    'type' => 'number',
		                    'class' => '',
		                    'heading' => __('Minimum Number of blocks in One screen', 'wplms'),
		                    'param_name' => 'carousel_min',
		                    'value' => '',
		                    'description' => __('Responsiveness, for smallest supported screen resolution, set minimum blocks in one screen', 'wplms'),
		                ),
		                array(
		                    'type' => 'number',
		                    'class' => '',
		                    'heading' => __('Number of blocks in one slide', 'wplms'),
		                    'param_name' => 'carousel_move',
		                    'value' => '',
		                    'description' => __('Number of blocks to rotate in one carousel slide moves', 'wplms'),
		                ),
		                array(
		                    'type' => 'number',
		                    'class' => '',
		                    'heading' => __('Total number of blocks in carousel', 'wplms'),
		                    'param_name' => 'carousel_number',
		                    'value' => '',
		                    'description' => __('Total blocks', 'wplms'),
		                ),
		                array(
		                    'type' => 'number',
		                    'class' => '',
		                    'heading' => __('Carousel Rows', 'wplms'),
		                    'param_name' => 'carousel_rows',
		                    'value' => 1,
		                    'description' => __('Rows in Carousel', 'wplms'),
		                ),
			            array(
							'type'        => 'dropdown',
							'admin_label' => true,
							'heading'     => esc_html__( 'Show Link button on image hover', 'wplms' ),
							'param_name'  => 'carousel_link',
							'value'       => array(
								esc_html__( 'No', 'wplms' )  => '0',
								esc_html__( 'Yes', 'wplms' ) => '1',
							),
						),
			                         
			        ),
		        )
		    );

		    /* -- gRID -- */
		    vc_map(
		        array(
		            'name' => __( 'Vibe Grid' ,'wplms'),
		            'base' => 'v_grid',
		            'category' => __( 'Vibe Builder','wplms'),
		            'group' => 'Vibe Builder',
		            'icon'        => 'vibe-builder-icon',
		            'params' => array(
		                array(
							'type'        => 'dropdown',
							'admin_label' => true,
							'heading'     => esc_html__( 'Show Grid title', 'wplms' ),
							'param_name'  => 'show_title',
							'value'       => array(
								esc_html__( 'No', 'wplms' )  => '0',
								esc_html__( 'Yes', 'wplms' ) => '1',
							),
						),
						array(
		                    'type' => 'textfield',
		                    'holder' => 'div',
		                    'class' => '',
		                    'heading' => __('Title/Heading', 'wplms'),
		                    'param_name' => 'title',
		                    'value' => __('Heading', 'wplms'),
		                    'dependency' => array(
								'element' => 'show_title',
								'value' => '1',
							),
		                ),
		                array(
							'type'        => 'dropdown',
							'admin_label' => true,
							'heading'     => esc_html__('Select a Post Type', 'wplms'),
							'param_name'  => 'post_type',
							'value'       => $this->v_post_types,
						),
						array(
							'type'        => 'dropdown',
							'admin_label' => true,
							'heading'     => esc_html__('Order', 'wplms'),
							'param_name'  => 'course_style',
							'value'       =>array(
								__('Recently published','wplms') => 'recent',
				                __('Most Students','wplms') =>'popular' ,
				                __('Featured','wplms') => 'featured',
				                __('Highest Rated','wplms') => 'rated',
				                __('Most Reviews','wplms') => 'reviews',
				                __('Upcoming Courses (Start Date)','wplms') => 'start_date',
				                __('Expired Courses (Past Start Date)','wplms') => 'expired_start_date',
				                __('Free Courses','wplms')=>'free',
				                __('Random','wplms')=>'random'
			                ),
							'dependency' => array(
								'element' => 'post_type',
								'value' => 'course',
							),
						),
						
						array(
		                    'type' => 'textfield',
		                    'holder' => 'div',
		                    'class' => '',
		                    'heading' => __('Taxonomy ', 'wplms'),
		                    'param_name' => 'taxonomy',
		                    'value' => '',
		                    'description' => __('Optionally select a taxonomy to fetch post types from.', 'wplms'),
		                ),
		                array(
		                    'type' => 'textfield',
		                    'holder' => 'div',
		                    'class' => '',
		                    'heading' => __('Taxonomy Term', 'wplms'),
		                    'param_name' => 'term',
		                    'value' => '',
		                    'description' => __('Select a Term if taxonomy is selected', 'wplms'),
		                ),
		                array(
		                    'type' => 'textfield',
		                    'holder' => 'div',
		                    'class' => '',
		                    'heading' => __('Manually add Post IDs', 'wplms'),
		                    'param_name' => 'post_ids',
		                    'value' => '',
		                    'description' => __('Comma separated post ids, ignores taxonomy and terms.', 'wplms'),
		                ),
		                array(
							'type'        => 'radio_images',
							'admin_label' => true,
							'heading'     => esc_html__('Featured Media Block Style', 'wplms'),
							'param_name'  => 'featured_style',
							'value'       => $this->v_thumb_styles,
							'std'		  => 'course'
						),
						
		                 array(
							'type'        => 'dropdown',
							'admin_label' => true,
							'heading'     => esc_html__( 'Grid Masonry Layout', 'wplms' ),
							'param_name'  => 'masonry',
							'value'       => array(
								esc_html__( 'No', 'wplms' )  => '0',
								esc_html__( 'Yes', 'wplms' ) => '1',
							),
						),

		                 array(
							'type'        => 'dropdown',
							'admin_label' => true,
							'heading'     => esc_html__( 'Grid Masonry Layout', 'wplms' ),
							'param_name'  => 'grid_columns',
							'value'       => array(
								esc_html__( '1 Columns in FullWidth', 'wplms' )  => 'clear1 col-md-12',
								esc_html__( '2 Columns in FullWidth', 'wplms' ) => 'clear2 col-md-6',
								esc_html__( '3 Columns in FullWidth', 'wplms' ) => 'clear3 col-md-4',
								esc_html__( '4 Columns in FullWidth', 'wplms' ) => 'clear4 col-md-3',
								esc_html__( '6 Columns in FullWidth', 'wplms' ) => 'clear6 col-md-2',
							),
							'dependency' => array(
								'element' => 'masonry',
								'value' => '0',
							),
						),

		                array(
		                    'type' => 'number',
		                    'class' => '',
		                    'heading' => __('Masonry Grid Column Width(in px)', 'wplms'),
		                    'param_name' => 'column_width',
		                    'value' => '268',
		                    'description' => __('Optionally set block width', 'wplms'),
		                    'dependency' => array(
								'element' => 'masonry',
								'value' => '1',
							),
		                ),
		                array(
		                    'type' => 'number',
		                    'class' => '',
		                    'heading' => __('Spacing between Columns (in px)', 'wplms'),
		                    'param_name' => 'gutter',
		                    'value' => 30,
		                ),
		                array(
		                    'type' => 'number',
		                    'class' => '',
		                    'heading' => __('Total Number of Blocks in Grid', 'wplms'),
		                    'param_name' => 'grid_number',
		                    'value' => 2,
		                    'description' => __('Blocks in grid screen/page', 'wplms'),
		                ),
		                array(
							'type'        => 'dropdown',
							'admin_label' => true,
							'heading'     => esc_html__( 'Infinite Scroll', 'wplms' ),
							'param_name'  => 'infinite',
							'value'       => array(
								esc_html__( 'No', 'wplms' )  => '0',
								esc_html__( 'Yes', 'wplms' ) => '1',
							),
						),
            			array(
							'type'        => 'dropdown',
							'admin_label' => true,
							'heading'     => esc_html__( 'Enable Pagination (If infinite scroll is off)', 'wplms' ),
							'param_name'  => 'pagination',
							'value'       => array(
								esc_html__( 'No', 'wplms' )  => '0',
								esc_html__( 'Yes', 'wplms' ) => '1',
							),
						),
		            	array(
		                    'type' => 'number',
		                    'class' => '',
		                    'heading' => __('Excerpt Length (in characters)', 'wplms'),
		                    'param_name' => 'grid_excerpt_length',
		                    'value' => 200,
		                    'description' => __('Number of characters if featured block set has excerpt', 'wplms'),
		                ),
		                array(
							'type'        => 'dropdown',
							'admin_label' => true,
							'heading'     => esc_html__( 'Show Link button on image hover', 'wplms' ),
							'param_name'  => 'grid_link',
							'value'       => array(
								esc_html__( 'No', 'wplms' )  => '0',
								esc_html__( 'Yes', 'wplms' ) => '1',
							),
						),               
		            )
		        )
		    );

		    /* -- fILTERABLE -- */

		    vc_map(
		        array(
		            'name' => __( 'Vibe Filterable' ,'wplms'),
		            'base' => 'v_filterable',
		            'category' => __( 'Vibe Builder','wplms'),
		            'group' => 'Vibe Builder',
		            'icon'        => 'vibe-builder-icon',
		            'params' => array(
		               	array(
							'type'        => 'dropdown',
							'admin_label' => true,
							'heading'     => esc_html__( 'Show title', 'wplms' ),
							'param_name'  => 'show_title',
							'value'       => array(
								esc_html__( 'No', 'wplms' )  => '0',
								esc_html__( 'Yes', 'wplms' ) => '1',
							),
						),
						array(
		                    'type' => 'textfield',
		                    'holder' => 'div',
		                    'class' => '',
		                    'heading' => __('Filterable Block Title', 'wplms'),
		                    'param_name' => 'title',
		                    'value' => __('Heading', 'wplms'),
		                    'dependency' => array(
								'element' => 'show_title',
								'value' => '1',
							),
		                ),
		                array(
							'type'        => 'dropdown',
							'admin_label' => true,
							'heading'     => esc_html__('Select a Post Type', 'wplms'),
							'param_name'  => 'post_type',
							'value'       => $this->v_post_types,
						),
						array(
							'type'        => 'dropdown',
							'admin_label' => true,
							'heading'     => esc_html__('Order', 'wplms'),
							'param_name'  => 'course_style',
							'value'       =>array(
								__('Recently published','wplms') => 'recent',
				                __('Most Students','wplms') =>'popular' ,
				                __('Featured','wplms') => 'featured',
				                __('Highest Rated','wplms') => 'rated',
				                __('Most Reviews','wplms') => 'reviews',
				                __('Upcoming Courses (Start Date)','wplms') => 'start_date',
				                __('Expired Courses (Past Start Date)','wplms') => 'expired_start_date',
				                __('Free Courses','wplms')=>'free',
				                __('Random','wplms')=>'random'
			                ),
							'dependency' => array(
								'element' => 'post_type',
								'value' => 'course',
							),
						),
						
						array(
		                    'type' => 'textfield',
		                    'holder' => 'div',
		                    'class' => '',
		                    'heading' => __('Taxonomy ', 'wplms'),
		                    'param_name' => 'taxonomy',
		                    'value' => '',
		                    'description' => __('Optionally select a taxonomy to fetch post types from.', 'wplms'),
		                ),
		                array(
		                    'type' => 'textfield',
		                    'holder' => 'div',
		                    'class' => '',
		                    'heading' => __('Taxonomy Term', 'wplms'),
		                    'param_name' => 'term',
		                    'value' => '',
		                    'description' => __('Select a Term if taxonomy is selected', 'wplms'),
		                ),
		                array(
		                    'type' => 'textfield',
		                    'holder' => 'div',
		                    'class' => '',
		                    'heading' => __('Manually add Post IDs', 'wplms'),
		                    'param_name' => 'post_ids',
		                    'value' => '',
		                    'description' => __('Comma separated post ids, ignores taxonomy and terms.', 'wplms'),
		                ),
		                array(
							'type'        => 'radio_images',
							'admin_label' => true,
							'heading'     => esc_html__('Featured Media Block Style', 'wplms'),
							'param_name'  => 'featured_style',
							'value'       => $this->v_thumb_styles,
							'std'		  => 'course'
						),
						
		                 array(
							'type'        => 'dropdown',
							'admin_label' => true,
							'heading'     => esc_html__( 'Show All link', 'wplms' ),
							'param_name'  => 'show_all',
							'value'       => array(
								esc_html__( 'No', 'wplms' )  => '0',
								esc_html__( 'Yes', 'wplms' ) => '1',
							),
						),
		                array(
		                    'type' => 'number',
		                    'class' => '',
		                    'heading' => __('Column Width(in px)', 'wplms'),
		                    'param_name' => 'column_width',
		                    'value' => 268,
		                    'description' => __('Optionally set block width', 'wplms'),
		                ),
		                array(
		                    'type' => 'number',
		                    'class' => '',
		                    'heading' => __('Total Number of Blocks in screen', 'wplms'),
		                    'param_name' => 'filterable_number',
		                    'value' => 2,
		                    'description' => __('Blocks in grid screen/page', 'wplms'),
		                ),
            			array(
							'type'        => 'dropdown',
							'admin_label' => true,
							'heading'     => esc_html__( 'Enable Pagination (If infinite scroll is off)', 'wplms' ),
							'param_name'  => 'show_pagination',
							'value'       => array(
								esc_html__( 'No', 'wplms' )  => '0',
								esc_html__( 'Yes', 'wplms' ) => '1',
							),
						),
		            	array(
		                    'type' => 'number',
		                    'class' => '',
		                    'heading' => __('Excerpt Length (in characters)', 'wplms'),
		                    'param_name' => 'filterable_excerpt_length',
		                    'value' => 200,
		                    'description' => __('Number of characters if featured block set has excerpt', 'wplms'),
		                ),
		                array(
							'type'        => 'dropdown',
							'admin_label' => true,
							'heading'     => esc_html__( 'Show Link button on image hover', 'wplms' ),
							'param_name'  => 'filterable_link',
							'value'       => array(
								esc_html__( 'No', 'wplms' )  => '0',
								esc_html__( 'Yes', 'wplms' ) => '1',
							),
						), 
		            )
		        )
		    );



		}
		

		/**
		* Function for displaying Title functionality
		*
		* @param array $atts    - the attributes of shortcode
		* @param string $content - the content between the shortcodes tags
		*
		* @return string $html - the HTML content for this shortcode.
		*/
		function vcas_title_function( $atts, $content ) {
		    $atts = shortcode_atts(
			    array(
			        'title' => __( 'This is the custom shortcode' ),
			        'title_color' => '#000000',
			    ), $atts, 'vcas_title'
			);

			$html = '<h1 class="component title ' . $atts['style']. '" style="color: ' . $atts['title_color'] . '">'. $atts['title'] . '</h1>';
			return $html;
		}
		
                
    } // END class Wplms_Vc
    add_action('init',function(){

    	if (defined('WPB_VC_VERSION') && version_compare(WPB_VC_VERSION, 4.8) >= 0) {
            if (function_exists('vc_add_shortcode_param')) {
            	vc_add_shortcode_param( 'radio_images', 'vibe_radio_images_field' );
                vc_add_shortcode_param( 'number', 'vibe_number_field' );
            }
        } else {
            if (function_exists('add_shortcode_param')) {
                add_shortcode_param( 'radio_images', 'vibe_radio_images_field' );
                add_shortcode_param( 'number', 'vibe_number_field' );
            }
        }


    	
		function vibe_radio_images_field( $settings, $value ) {

			$dependency = function_exists('vc_generate_dependencies_attributes') ? vc_generate_dependencies_attributes($settings) : '';
		   	$return = '<div class="all_radio_images" style="clear:both;">';
		   	$param_name = isset( $settings['param_name'] ) ? $settings['param_name'] : '';
		   	$type       = isset( $settings['type'] ) ? $settings['type'] : '';
			$class      = isset( $settings['class'] ) ? $settings['class'] : '';
		   	if(is_array($settings['value'])){
		   		foreach($settings['value'] as $v => $img){

		   			//$return .='';
		   			$return .= '<label class="radio_images '.(($value == $v)?'clicked':'').'" data-value="'.$v.'"><img src="'.$img.'">'.(($value == $v)?'<span></span>':'').'</label>';
		   		}
		   		$return .='<input type="hidden" name="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value image_value ' . $param_name . ' ' . $class . ' ' . $dependency . '" value="'.$value.'">';
		   	}

		    return $return;
		}
		
		function vibe_number_field( $settings, $value ) {

			$dependency = function_exists('vc_generate_dependencies_attributes') ? vc_generate_dependencies_attributes($settings) : '';
		   	$param_name = isset( $settings['param_name'] ) ? $settings['param_name'] : '';
			$type       = isset( $settings['type'] ) ? $settings['type'] : '';
			$min        = isset( $settings['min'] ) ? $settings['min'] : '';
			$max        = isset( $settings['max'] ) ? $settings['max'] : '';
			$suffix     = isset( $settings['suffix'] ) ? $settings['suffix'] : '';
			$class      = isset( $settings['class'] ) ? $settings['class'] : '';
			
			$return     = '<input type="number"  min="' . $min . '" max="' . $max . '" class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '" ' . $dependency . ' value="' . $value . '" style="max-width:100px; margin-right: 10px;" />' . $suffix;
		    return $return;
		}

    	$wplms = Wplms_Vc::init();
    },-1);
    

}