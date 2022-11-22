<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wplms_Achievements extends \Elementor\Widget_Base 
{


    public function get_name() {
		return 'wplms_achievements';
	}

	public function get_title() {
		return __( 'Member Achievements', 'wplms' );
	}

	public function get_icon() {
		return 'vicon vicon-medall';
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
			'achievement_type',
			[
				'label' => __('Achievement Type', 'wplms'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'certificates',
				'options'=>[
					'certificates'=>_x('Certificates','elementor','wplms'),
					'badge'=>_x('Badge','elementor','wplms'),
				]
			]
		);
		

		$this->add_control(
			'total',
			[
				'label' => __( 'Maximum Elements to show', 'wplms' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 50,
				'step' => 1,
				'default' => 6
			]
		);

		$this->add_control(
			'block_size',
			[
				'label' => __( 'Block size', 'wplms' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ '%', 'rem','px' ],
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
					'size' => 33,
				],
				'selectors' => [
					'{{WRAPPER}} .achievement_blocks' => 'display:grid;grid-gap:1rem;justify-items:center;align-items:flex-start;grid-template-columns:repeat(auto-fill,minmax({{SIZE}}{{UNIT}},1fr)) ;',
					'{{WRAPPER}} .achievement_block a' => '
					    display:flex;
					    flex-direction:column;
					    justify-content:center;
					    align-items:center;
					',
					 '{{WRAPPER}} .achievement_block a span' => '   margin-top: 1rem;'
					
				],
			]
		);



		$this->end_controls_section();

	}

	protected function render() {

		$settings = $this->get_settings_for_display();

		$shortcode = '[user_achievements type="'.$settings['achievement_type'].'" style="'.$settings['style'].'" row="'.$settings['row'].'" total="'.$settings['total'].'"]';

		echo do_shortcode($shortcode);
	}
}