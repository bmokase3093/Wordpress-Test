<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wplms_Course_Instructor_Data extends \Elementor\Widget_Base  // We'll use this just to avoid function name conflicts 
{


    public function get_name() {
		return 'wplms_course_instructor_data';
	}

	public function get_title() {
		return __( 'Course Instructor Data', 'wplms' );
	}

	public function get_icon() {
		return 'vicon vicon-user';
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
			'data',
			[
				'label' => __( 'Profile Data', 'vibebp' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'image'=>__('Instructor Image','wplms'),
					'avarage_rating'=>__('Average Rating','wplms'),
					'review_count'=>__('Review Count','wplms'),
					'student_count'=>__('Student Count','wplms'),
					'course_count'=>__('Course Count','wplms')
				],
			]
		);

		$this->add_control(
			'color',
			[
				'label' => __( 'Text Color', 'wplms' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .course_instructor_data' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'font_size',
			[
				'label' => __( 'Font Size', 'wplms' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'rem' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'rem',
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .course_instructor_data' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);


		$this->end_controls_section();

	}

	protected function render() {

		$settings = $this->get_settings_for_display();
		wp_enqueue_style('wplms_plugin_elementor',plugins_url('../../../../../assets/css/general.css',__FILE__),array(),WPLMS_PLUGIN_VERSION);
		$shortcode = '[course_instructor_data data="'.$settings['data'].'"]';
		echo '<div class="course_instructor_data">'.do_shortcode($shortcode).'</div>';
		
	}

}