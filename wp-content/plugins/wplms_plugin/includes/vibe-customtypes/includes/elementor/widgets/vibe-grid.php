<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

 class Wplms_Vibe_Grid extends \Elementor\Widget_Base  // We'll use this just to avoid function name conflicts 
{


    public function get_name() {
		return 'post grid';
	}

	public function get_title() {
		return __( 'Vibe Grid', 'wplms' );
	}

	public function get_icon() {
		return 'vicon vicon-layout-grid3';
	}

	public function get_categories() {
		return [ 'wplms' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Controls', 'wplms' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'title',
			[
				'label' => __( 'Title', 'wplms' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'text',
				'placeholder' => __( 'Enter Title', 'wplms' ),
			]
		);
		
		$this->add_control(
			'grid_title',
			[
				'label' => __( 'Show Grid title', 'wplms' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'0' => [
						'title' => __( 'No', 'wplms' ),
						'icon' => 'vicon vicon-close',
					],
					'1' => [
						'title' => __( 'Yes', 'wplms' ),
						'icon' => 'fa fa-check',
					],
				],
			]
		);

		$this->add_control(
			'taxonomy',
			[
				'label' => __( 'Enter Taxonomy Slug', 'wplms' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'text',
				'placeholder' => __( 'Enter Taxonomy Slug', 'wplms' ),
			]
		);


		$terms = get_terms( 'post_tag', array(
		    'hide_empty' => false,
		) );
		$termarray = array();
		foreach($terms as $term){
			$termarray[$term->slug]=$term->name;
		}
		$this->add_control(
			'term',
			[
				'label' => __('Enter Taxonomy Term Slug <br />(optional, only if above is selected, comma separated for multiple terms): ', 'wplms'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'text',
				'placeholder' => __( 'Enter Taxonomy Terms', 'wplms' ),
			]
		);

		$this->add_control(
			'post_ids',
			[
				'label' => __( 'Or Enter Specific Post Ids (comma separated)', 'wplms' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'text',
				'placeholder' => __( 'Enter post ids', 'wplms' ),
			]
		);

		$this->add_control(
			'course_style',
			[
				'label' => __('Course Types', 'wplms'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'rated',
				'options' => array(
	                'recent' => 'Recently published',
	                'popular' => 'Most Students',
	                'featured' => 'Featured',
	                'rated'  => 'Highest Rated',
	                'reviews' => 'Most Reviews',
	                'start_date' => 'Upcoming Courses (Start Date)',
	                'expired_start_date'=>'Expired Courses (Past Start Date)',
	                'free'=> 'Free Courses',
	                'random' => 'Random'
                ),
			]
		);

		$v_post_types = array();
	    $post_types=get_post_types('','objects'); 

	    foreach ( $post_types as $post_type ){
	        if( !in_array($post_type->name, array('attachment','revision','nav_menu_item','sliders','modals','shop','shop_order','shop_coupon','forum','topic','reply')))
	           $v_post_types[$post_type->name]=$post_type->label;
	    }
	    
	    if(!array_key_exists('news',$v_post_types)){
	        $v_post_types['news'] = __('Course News','wplms');
	    }

		$this->add_control(
			'post_type',
			[
				'label' => __('Enter Post Type', 'wplms'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'post',
				'options' => $v_post_types,
			]
		);

			$this->add_control(
			'carousel_excerpt_length',
			[
				'label' =>__('Excerpt Length in Block (in characters)', 'wplms'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 10,
				'max' => 200,
				'step' => 5,
				'default' => 100,
			]
		);

		$this->add_control(
			'featured_style',
			[
				'label' => __( 'Featured Style', 'wplms' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => plugins_url('../images/thumb_2.png',__FILE__),
				'options' => wplms_get_featured_cards(),
			]
		);


		$this->add_control(
			'masonry',
			[
				'label' =>__('Grid Masonry Layout [jQuery]', 'wplms'),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'0' => [
						'title' => __( 'No', 'wplms' ),
						'icon' => 'vicon vicon-close',
					],
					'1' => [
						'title' => __( 'Yes', 'wplms' ),
						'icon' => 'fa fa-check',
					],
				],
			]
		);

		$this->add_control(
			'grid_columns',
			[
				'label' => __('Grid Layout', 'wplms'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'clear4 col-md-3',
				'options' => array(
	                'clear1 col-md-12' => '1 Columns in FullWidth',
	                'clear2 col-md-6' => '2 Columns in FullWidth',
	                'clear3 col-md-4' => '3 Columns in FullWidth',
	                'clear4 col-md-3' => '4 Columns in FullWidth',
	                'clear6 col-md-2' => '6 Columns in FullWidth',
	               
                ),
			]
		);

		$this->add_control(
			'column_width',
			[
				'label' => __('Width each masonry grid block', 'wplms'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 5,
				'max' => 1200,
				'step' => 5,
				'default' => 268,
			]
		);

		$this->add_control(
			'grid_number',
			[
				'label' =>__('Total Number of Blocks in Grid', 'wplms'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 200,
				'step' => 1,
				'default' => 4,
			]
		);
		$this->add_control(
			'gutter',
			[
				'label' =>__('Spacing between Columns (in px)', 'wplms'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 200,
				'step' => 1,
				'default' => 30,
			]
		);
		$this->add_control(
			'infinite',
			[
				'label' =>__('Infinite Scroll', 'wplms'),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'0' => [
						'title' => __( 'No', 'wplms' ),
						'icon' => 'vicon vicon-close',
					],
					'1' => [
						'title' => __( 'Yes', 'wplms' ),
						'icon' => 'fa fa-check',
					],
				],
			]
		);
		$this->add_control(
			'pagination',
			[
				'label' =>__('Enable Pagination (If infinite scroll is off)', 'wplms'),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'0' => [
						'title' => __( 'No', 'wplms' ),
						'icon' => 'vicon vicon-close',
					],
					'1' => [
						'title' => __( 'Yes', 'wplms' ),
						'icon' => 'fa fa-check',
					],
				],
			]
		);

		$this->add_control(
			'grid_search',
			[
				'label' => __( 'Show Search', 'wplms' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'0' => [
						'title' => __( 'No', 'wplms' ),
						'icon' => 'vicon vicon-close',
					],
					'1' => [
						'title' => __( 'Yes', 'wplms' ),
						'icon' => 'fa fa-check',
					],
				],
			]
		);
		
		do_action('wplms_vibe_grid',$this);

		$this->end_controls_section();
	}

	protected function render() {

		$settings = $this->get_settings_for_display();

		
	    $shortcode = '[v_grid 
		title="'.($settings['title']).'"
		show_title="'.(empty($settings['grid_title'])?0:1).'"  
		post_type="'.($settings['post_type']).'" 
		taxonomy="'.(empty($settings['taxonomy'])?"":$settings['taxonomy']).'" 
		term="'.(empty($settings['term'])?0:$settings['term']).'" 
	    post_ids="'.($settings['post_ids']).'" 
	    course_style="'.($settings['course_style']).'" 
	    featured_style="'.($settings['featured_style']).'"
		masonry="'.($settings['masonry']).'"
		grid_columns="'.($settings['grid_columns']).'" 
		column_width="'.($settings['column_width']).'"
		gutter="'.($settings['gutter']).'"
		grid_number="'.($settings['grid_number']).'" 
		infinite="'.($settings['infinite']).'" 
		pagination="'.($settings['pagination']).'" 
		grid_excerpt_length="'.($settings['carousel_excerpt_length']).'"
		grid_link="1" 
		grid_search="'.(empty($settings['grid_search'])?0:1).'"
		course_type="'.(empty($settings['course_style'])?'':$settings['course_type']).'"
		css_class="" 
		container_css="" 
		custom_css=""][/v_grid]';

		do_action('wplms_vibe_grid_settings',$settings);

		echo do_shortcode($shortcode); 
	}

}