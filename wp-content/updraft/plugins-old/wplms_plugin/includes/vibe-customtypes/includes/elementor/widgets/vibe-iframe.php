<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wplms_Vibe_Iframe extends \Elementor\Widget_Base  // We'll use this just to avoid function name conflicts 
{


    public function get_name() {
		return 'vibe_iframe';
	}

	public function get_title() {
		return __( 'Vibe Iframe', 'wplms' );
	}

	public function get_icon() {
		return 'vicon vicon-layout-width-full';
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
			'height',
			[
				'label' => __( 'Enter iframe Height', 'wplms' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __( 'enter height', 'wplms' ),
			]
		);

		$this->add_control(
			'src',
			[
				'label' => __( 'Enter iframe URL', 'wplms' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __( 'URl', 'wplms' ),
			]
		);

		$this->end_controls_section();

	}

	protected function render() {

		$settings = $this->get_settings_for_display();

		$shortcode = '[iframe height="'.($settings['height']).'"] '.($settings['src']).' [/iframe]';

		echo do_shortcode($shortcode);
	}
}