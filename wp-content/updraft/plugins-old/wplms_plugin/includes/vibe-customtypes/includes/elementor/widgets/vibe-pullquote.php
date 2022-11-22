<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wplms_Vibe_Pullquote extends \Elementor\Widget_Base  // We'll use this just to avoid function name conflicts 
{


    public function get_name() {
		return 'vibe-pullquote';
	}

	public function get_title() {
		return __( 'Vibe Pullquote', 'wplms' );
	}

	public function get_icon() {
		return 'vicon vicon-layout-media-right';
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
			'content',
			[
				'label' => __( 'Contents', 'wplms' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'text',
				'placeholder' => __( 'Enter Content', 'wplms' ),
			]
		);

		$this->add_control(
			'style',
			[
				'label' => __( 'Style', 'wplms' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'left' => 'Left',
					'right' => 'Right'
				],
			]
		);
		$this->end_controls_section();

	}

	protected function render() {

		$settings = $this->get_settings_for_display();

		$shortcode = '[pullquote  
	    style="'.($settings['style']).'"] "'.$settings['content'].'"[/pullquote]';

		echo do_shortcode($shortcode);
	}
}


