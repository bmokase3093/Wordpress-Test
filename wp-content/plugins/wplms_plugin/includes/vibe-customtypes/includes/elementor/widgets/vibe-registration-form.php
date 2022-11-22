<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class wplms_Vibe_registration_form extends \Elementor\Widget_Base  // We'll use this just to avoid function name conflicts 
{


    public function get_name() {
		return 'vibe registration form';
	}

	public function get_title() {
		return __( 'Vibe registration form', 'wplms' );
	}

	public function get_icon() {
		return 'vicon vicon-agenda';
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
			'name',
			[
				'label' => __( 'Enter name', 'wplms' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __('enter name', 'wplms')
			]
		);

		$this->add_control(
			'field_meta',
			[
				'label' => __( 'Enter field_meta', 'wplms' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __('enter field_meta', 'wplms')
			]
		);		

		$this->end_controls_section();

	}

	protected function render() {

		$settings = $this->get_settings_for_display();

		$shortcode = '[wplms_registration_form name="'.($settings['name']).'" field_meta="'.($settings['field_meta']).'"]';
		echo do_shortcode($shortcode);
	}
}