<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wplms_Course_Curriculum extends \Elementor\Widget_Base 
{


    public function get_name() {
		return 'wplms_course_curriculum';
	}

	public function get_title() {
		return __( 'Course Curriculum', 'wplms' );
	}

	public function get_icon() {
		return 'vicon vicon-layout-accordion-merged';
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

		

		$this->end_controls_section();

	}

	protected function render() {

		$settings = $this->get_settings_for_display();


		$shortcode = '[course_curriculum]';

		echo do_shortcode($shortcode);
	}
}