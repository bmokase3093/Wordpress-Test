<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wplms_Course_featured extends \Elementor\Widget_Base  // We'll use this just to avoid function name conflicts 
{


    public function get_name() {
		return 'wplms_course_featured';
	}

	public function get_title() {
		return __( 'Course Image', 'wplms' );
	}

	public function get_icon() {
		return 'vicon vicon-image';
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
			'align',
			[
				'label' => __( 'Alignment', 'wplms' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'wplms' ),
						'icon' => 'vicon vicon-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'wplms' ),
						'icon' => 'vicon vicon-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'wplms' ),
						'icon' => 'vicon vicon-align-right',
					],
				],
			]
		);

		$this->add_control(
			'width',
			[
				'label' => __( 'Image Width', 'wplms' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
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
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .course_featured img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'radius',
			[
				'label' => __( 'Border Radius', 'wplms' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
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
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}}  .course_featured img' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

	}

	protected function render() {

		$settings = $this->get_settings_for_display();

		$shortcode = '[course_featured]';

		echo do_shortcode($shortcode);
	}
}