<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wplms_Elementor_Course_Reviews extends \Elementor\Widget_Base 
{


    public function get_name() {
		return 'wplms_elementor_course_reviews';
	}

	public function get_title() {
		return __( 'Course Reviews', 'wplms' );
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
			'title',
			[
				'label' => __( 'Title', 'wplms' ),
				'type' => \Elementor\Controls_Manager::TEXT,
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
			'rating_count',
			[
				'label' => __( 'Show Rating count', 'wplms' ),
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
			'number',
			[
				'label' => __( 'Number of reviews', 'wplms' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'placeholder' => '5',
			]
		);

		$this->add_control(
			'helpfulness',
			[
				'label' => __( 'Enable Review helpfulness', 'wplms' ),
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
					'helpful' => __('Most helpful first (if enabled)','wplms'),
				],
			]
		);
		$this->end_controls_section();

	}

	protected function render() {

		$settings = $this->get_settings_for_display();

		$shortcode = '[course_reviews hide_if_none="'.$settings['hide_if_none'].'" number="'.$settings['number'].'" show_count="'.$settings['rating_count'].'" show_breakup="'.$settings['hide_if_none'].'" orderby="'.$settings['orderby'].'" show_helpfulness="'.$settings['orderby'].'"]'.$settings['title'].'[/course_reviews]';

		echo do_shortcode($shortcode);
	}
}