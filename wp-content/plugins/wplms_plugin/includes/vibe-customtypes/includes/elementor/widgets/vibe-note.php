<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wplms_Vibe_Note extends \Elementor\Widget_Base  // We'll use this just to avoid function name conflicts 
{


    public function get_name() {
		return 'vibe_note';
	}

	public function get_title() {
		return __( 'Vibe Note', 'wplms' );
	}

	public function get_icon() {
		return 'vicon vicon-layout-media-overlay-alt-2';
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
			'bg',
			[
				'label' => __( 'Background color', 'wplms' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'placeholder' => '',
			]
		);

		$this->add_control(
			'bordercolor',
			[
				'label' => __( 'Border Color', 'wplms' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'placeholder' => '',
			]
		);

		$this->add_control(
			'color',
			[
				'label' => __( 'Text Color', 'wplms' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'placeholder' => '',
			]
		);

		$this->add_control(
			'content',
			[
				'label' => __( 'content', 'wplms' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __( 'content', 'wplms' ),
			]
		);

		$this->end_controls_section();

	}

	protected function render() {

		$settings = $this->get_settings_for_display();

		$shortcode = '[note style="other" bg="'.($settings['bg']).'" border="" bordercolor="'.($settings['bordercolor']).'" color="'.($settings['color']).'"]'.($settings['content']).'[/note]';

		echo do_shortcode($shortcode);
	}
}