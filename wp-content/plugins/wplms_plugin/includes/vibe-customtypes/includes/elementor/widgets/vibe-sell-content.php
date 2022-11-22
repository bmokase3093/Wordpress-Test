<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wplms_Vibe_Sell_Content extends \Elementor\Widget_Base  // We'll use this just to avoid function name conflicts 
{


    public function get_name() {
		return 'vibe sell content';
	}

	public function get_title() {
		return __( 'Vibe Sell Content', 'wplms' );
	}

	public function get_icon() {
		return 'vicon vicon-shopping-cart';
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
			'product_id',
			[
				'label' => __( 'Enter Product Id', 'wplms' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __('enter Product id', 'wplms')
			]
		);

		$this->end_controls_section();

	}

	protected function render() {

		$settings = $this->get_settings_for_display();

		$shortcode = '[sell_content product_id="'.($settings['product_id']).'"]';
		echo do_shortcode($shortcode);
	}
}