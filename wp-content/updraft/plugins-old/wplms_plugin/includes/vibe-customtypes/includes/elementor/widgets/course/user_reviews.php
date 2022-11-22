<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wplms_User_Reviews extends \Elementor\Widget_Base 
{


    public function get_name() {
		return 'wplms_instructor_reviews';
	}

	public function get_title() {
		return __( 'User Reviews', 'wplms' );
	}

	public function get_icon() {
		return 'vicon vicon-layout-media-overlay-alt-2';
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
			'breakup',
			[
				'label' => __( 'Show Rating Breakup', 'wplms' ),
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
			'user_type',
			[
				'label' => __('User Type', 'wplms'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'carousel',
				'options'=>[
					'instructor'=>_x('Reviews posted for Instructor','elementor','wplms'),
					'student'=>_x('Reviews Posted by Student','elementor','wplms'),
				]
			]
		);

		$this->add_control(
			'style',
			[
				'label' => __('Style', 'wplms'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'carousel',
				'options'=>[
					'carousel'=>_x('Carousel','elementor','wplms'),
					'grid'=>_x('Grid','elementor','wplms'),
				]
			]
		);

		$this->add_control(
			'row',
			[
				'label' => __('Elements in Row / Elements in Screen', 'wplms'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'carousel',
				'options'=>[
					'25'=>_x('4 in Row','elementor','wplms'),
					'33'=>_x('3 in Row','elementor','wplms'),
					'25'=>_x('2 in Row','elementor','wplms'),
				]
			]
		);

		$this->add_control(
			'total',
			[
				'label' => __( 'Number of reviews', 'wplms' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 50,
				'step' => 1,
				'default' => 6
			]
		);

		$this->add_control(
			'hide_if_none',
			[
				'label' => __( 'Hide if no reviews', 'wplms' ),
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
			'orderby',
			[
				'label' => __( 'Orderby', 'wplms' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'0' => __('Recent rating first','wplms'),
					'highest_rated' => __('Highest rating first','wplms'),
				],
			]
		);
		$this->end_controls_section();

	}

	protected function render() {

		$settings = $this->get_settings_for_display();

		$shortcode = '[user_reviews hide_if_none="'.$settings['hide_if_none'].'" total="'.$settings['total'].'" breakup="'.$settings['breakup'].'" orderby="'.$settings['orderby'].'" row="'.$settings['row'].'" style="'.$settings['style'].'"]';

		echo do_shortcode($shortcode);
	}
}