<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wplms_Vibe_Popup extends \Elementor\Widget_Base  // We'll use this just to avoid function name conflicts 
{


    public function get_name() {
		return 'vibe_popup';
	}

	public function get_title() {
		return __( 'Vibe Popup', 'wplms' );
	}

	public function get_icon() {
		return 'vicon vicon-layout-media-overlay-alt';
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
			'pop_up_id',
			[
				'label' => __( 'popup id', 'wplms' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => '',
			]
		);

		$this->add_control(
			'auto',
			[
				'label' => __( 'Show Popup on Page-load', 'wplms' ),
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
			'classes',
			[
				'label' => __( 'Anchor Style', 'wplms' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
						'default' =>'Default',
						'btn'=> 'Button',
						'btn primary' => 'Primary'
				],
			]
		);

		$this->add_control(
			'content',
			[
				'label' => __( 'Popup/Modal Anchor', 'wplms' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __( 'content', 'wplms' ),
			]
		);

		$this->end_controls_section();

	}

	protected function render() {

		$settings = $this->get_settings_for_display();

		$shortcode = '[popup id="'.($settings['pop_up_id']).'" auto="'.($settings['auto']).'" classes="'.($settings['classes']).'"] '.($settings['content']).' [/popup]';

		echo do_shortcode($shortcode);
	}
}