<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wplms_Vibe_Tooltip extends \Elementor\Widget_Base  // We'll use this just to avoid function name conflicts 
{


    public function get_name() {
		return 'vibe_tooltip';
	}

	public function get_title() {
		return __( 'Vibe Tooltip', 'wplms' );
	}

	public function get_icon() {
		return 'vicon vicon-light-bulb';
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
			'tip',
			[
				'label' => __( 'tip', 'wplms' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __('tip', 'wplms')
			]
		);

		$this->add_control(
			'anchor',
			[
				'label' => __( 'anchor', 'wplms' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __('anchor', 'wplms')
			]
		);

		$this->end_controls_section();

	}

	protected function render() {

		$settings = $this->get_settings_for_display();

		$shortcode = '[tooltip tip="'.($settings['tip']).'"] '.($settings['anchor']).' [/tooltip]';
		echo do_shortcode($shortcode);
	}
}