<?php
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wplms_Vibe_Course extends \Elementor\Widget_Base  // We'll use this just to avoid function name conflicts 
{

    public function get_name() {
		return 'vibe_course';
	}

	public function get_title() {
		return __( 'Vibe Course', 'wplms' );
	}

	public function get_icon() {
		return 'vicon vicon-bookmark-alt';
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
			'course_id',
			[
				'label' => __( 'Enter Course Id', 'wplms' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __('enter course id', 'wplms')
			]
		);

		$this->end_controls_section();

	}

	protected function render() {

		$settings = $this->get_settings_for_display();

		$shortcode = '[course id="'.($settings['course_id']).'"]';
		echo do_shortcode($shortcode);
	}
}