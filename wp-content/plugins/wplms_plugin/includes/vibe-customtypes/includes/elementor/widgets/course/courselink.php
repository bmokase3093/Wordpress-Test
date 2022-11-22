<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wplms_Course_Link extends \Elementor\Widget_Base 
{


    public function get_name() {
		return 'Wplms_Course_Link';
	}

	public function get_title() {
		return __( 'Course link', 'wplms' );
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
					'{{WRAPPER}} .button' => 'background: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'color',
			[
				'label' => __( 'Text Color', 'wplms' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .button' => 'color: {{VALUE}}',
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
					'{{WRAPPER}} .button' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);


		$this->add_control(
			'content',
			[
				'label' => __( 'Button Text', 'wplms' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __( 'View Details', 'wplms' ),
				'default' => __( 'View Details', 'wplms' ),
			]
		);
		$this->add_control(
			'target',
			[
				'label' => __( 'Target', 'wplms' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options'=>[
					'_blank'=>__('New Page','wplms'),
					'_self'=>__('Same page','wplms'),
					
				]
			]
		);
		$this->end_controls_section();

	}

	protected function render() {
		$id = apply_filters('elementor_course_block_id','');
        if(empty($id))
            return;
		$settings = $this->get_settings_for_display();

		

		echo '<a href="'.get_permalink($id).'" target="'.$settings['target'].'" class="button">'.$settings['content'].'</a>';
	}
}