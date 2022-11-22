<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wplms_Course_Pricing extends \Elementor\Widget_Base  // We'll use this just to avoid function name conflicts 
{


    public function get_name() {
		return 'wplms_course_pricing';
	}

	public function get_title() {
		return __( 'Course Price', 'wplms' );
	}

	public function get_icon() {
		return 'vicon vicon-money';
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
			'color',
			[
				'label' => __( 'Text Color', 'wplms' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .course_pricing > a' => 'color: {{VALUE}}',
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
					'{{WRAPPER}} .course_pricing>a' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);


		$this->end_controls_section();

	}

	protected function render() {

		$settings = $this->get_settings_for_display();
		wp_enqueue_style('wplms_plugin_elementor',plugins_url('../../../../../assets/css/general.css',__FILE__),array(),WPLMS_PLUGIN_VERSION);
		if(empty($settings['pricing_display'])){$settings['pricing_display']='';}
		$shortcode = '[course_pricing  style="'.($settings['pricing_display']).'"]';

		echo '<div class="course_pricing">'.do_shortcode($shortcode).'</div>';
	}
}