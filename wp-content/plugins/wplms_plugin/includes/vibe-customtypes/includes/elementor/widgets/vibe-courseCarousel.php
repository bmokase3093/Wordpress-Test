<?php
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

 class Wplms_Vibe_CourseCarousel extends \Elementor\Widget_Base  // We'll use this just to avoid function name conflicts 
{


    public function get_name() {
		return 'vibe_courseCarousel';
	}

	public function get_title() {
		return __( 'Course Category Carousel', 'wplms' );
	}

	public function get_icon() {
		return 'vicon vicon-layout-slider';
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
				'placeholder' => __( 'Title/Heading', 'wplms' ),
			]
		);
		
		$this->add_control(
			'show_title',
			[
				'label' => __( 'Show Title', 'wplms' ),
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


		$terms = get_terms( 'post_tag', array(
		    'hide_empty' => false,
		) );
		$termarray = array();
		foreach($terms as $term){
			$termarray[$term->slug]=$term->name;
		}
		$this->add_control(
			'term_slugs',
			[
				'label' => __('Include Term Slugs (optional, comma separated)', 'wplms'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'text',
				'placeholder' => __( 'terms_slug', 'wplms' ),
			]
		);

		$this->add_control(
			'orderby',
			[
				'label' => __('OrderBy', 'wplms'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'rated',
				'options' => array(
	                'DESC' => 'Decending',
	                'ASC' => 'Ascending'
                ),
			]
		);

		$this->add_control(
			'show_controls',
			[
				'label' =>__('Slider Controls : Direction Arrows', 'wplms'),
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
			'hide_child',
			[
				'label' =>__('Remove child categories', 'wplms'),
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
			'show_controlnav',
			[
				'label' =>__('Slider Controls : Control Dots', 'wplms'),
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
			'auto_slide',
			[
				'label' =>__('Auto Slide', 'wplms'),
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
			'column_width',
			[
				'label' => __('Carousel block width', 'wplms'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 5,
				'max' => 1200,
				'step' => 5,
				'default' => 268,
			]
		);

		$this->add_control(
			'carousel_max',
			[
				'label' =>__('Maximum Number of blocks in One screen', 'wplms'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 12,
				'step' => 1,
				'default' => 4,
			]
		);

		$this->add_control(
			'carousel_min',
			[
				'label' =>__('Minimum Number of blocks in one Screen', 'wplms'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 12,
				'step' => 1,
				'default' => 2,
			]
		);

		$this->add_control(
			'carousel_move',
			[
				'label' =>__('Number of blocks in one slide', 'wplms'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 12,
				'step' => 1,
				'default' => 1,
			]
		);

		$this->add_control(
			'carousel_rows',
			[
				'label' =>__('Carousel Rows', 'wplms'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 99,
				'step' => 1,
				'default' => 1,
			]
		);

		$this->add_control(
			'carousel_number',
			[
				'label' =>__('Total Number of Blocks', 'wplms'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 99,
				'step' => 1,
				'default' => 6,
			]
		);

	}

	protected function render() {

		$settings = $this->get_settings_for_display();

		$shortcode = '[v_taxonomy_carousel 
		title="'.($settings['title']).'"
		hide_child="'.($settings['hide_child']).'"
		show_title="'.($settings['show_title']).'"
		term_slugs="'.(empty($settings['term_slugs'])?0:$settings['term_slugs']).'" 
	    orderby="'.(empty($settings['orderby'])?'':$settings['orderby']).'" 
	    order="'.(empty($settings['order'])?'':$settings['order']).'" 
	    show_controls="'.(empty($settings['show_controls'])?$settings['show_controls']:'').'"
		show_controlnav="'.(empty($settings['show_controlnav'])?'':$settings['show_controlnav']).'"
		auto_slide="'.(empty($settings['auto_slide'])?'':$settings['auto_slide']).'" 
		column_width="'.(empty($settings['column_width'])?'332':$settings['column_width']).'"
		carousel_max="'.(empty($settings['carousel_max'])?4:$settings['carousel_max']).'"
		carousel_min="'.(empty($settings['carousel_min'])?1:$settings['carousel_min']).'" 
		carousel_move="'.(empty($settings['carousel_move'])?1:$settings['carousel_move']).'" 
		carousel_number="'.(empty($settings['carousel_number'])?4:$settings['carousel_number']).'" 
		carousel_rows="'.(empty($settings['carousel_rows'])?1:$settings['carousel_rows']).'" 
		css_class="" 
		container_css="" 
		custom_css=""][/v_taxonomy_carousel]';

		echo do_shortcode($shortcode);
	}

}