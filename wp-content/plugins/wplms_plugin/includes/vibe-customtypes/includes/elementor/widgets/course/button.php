<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wplms_Course_Button extends \Elementor\Widget_Base 
{


    public function get_name() {
		return 'wplms_course_button';
	}

	public function get_title() {
		return __( 'Course Button', 'wplms' );
	}

	public function get_icon() {
		return 'vicon vicon-bag';
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
				'selectors' => [
					'{{WRAPPER}} .course_button_wrapper >.button,.course_button_wrapper input[type="submit"].button' => 'background: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'color',
			[
				'label' => __( 'Text Color', 'wplms' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .course_button_wrapper >.button,.course_button_wrapper input[type="submit"].button' => 'color: {{VALUE}}',
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
					'{{WRAPPER}} .course_button_wrapper >.button,.course_button_wrapper input[type="submit"].button' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'width',
			[
				'label' => __( 'Button Width', 'wplms' ),
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
					'{{WRAPPER}} .course_button_wrapper >.button,.course_button_wrapper input[type="submit"].button' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'height',
			[
				'label' => __( 'Button Height', 'wplms' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 36,
				],
				'selectors' => [
					'{{WRAPPER}} .course_button_wrapper >.button,.course_button_wrapper input[type="submit"].button' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'font_size',
			[
				'label' => __( 'Font Size', 'wplms' ),
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
					'{{WRAPPER}} .course_button_wrapper >.button,.course_button_wrapper input[type="submit"].button' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'content',
			[
				'label' => __( 'Button Text', 'wplms' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __( 'Take this Course', 'wplms' ),
			]
		);


		$this->add_control(
			'show_price',
			[
				'label' => __( 'Show Pricing dropdown', 'wplms' ),
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
			'hide_extras',
			[
				'label' => __( 'Hide Extras [Seats/Date]', 'wplms' ),
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

		$this->end_controls_section();

	}

	protected function render() {

		$settings = $this->get_settings_for_display();

		$shortcode = '[course_button bg="'.$settings['bg'].'" width="'.$settings['width']['size'].$settings['width']['unit'].'" height="'.$settings['height']['size'].$settings['height']['unit'].'" radius="'.$settings['radius']['size'].$settings['radius']['unit'].'" font_size="'.$settings['font_size']['size'].$settings['font_size']['unit'].'" color="'.$settings['color'].'" show_price="'.$settings['show_price'].'" hide_extras="'.$settings['hide_extras'].'"]'.$settings['content'].'[/course_button]';

		echo do_shortcode($shortcode);
	}
}