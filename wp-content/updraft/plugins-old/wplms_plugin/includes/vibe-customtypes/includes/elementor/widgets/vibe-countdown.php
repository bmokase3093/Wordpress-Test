<?php
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wplms_Vibe_Countdown extends \Elementor\Widget_Base  // We'll use this just to avoid function name conflicts 
{


    public function get_name() {
		return 'vibe_countdown';
	}

	public function get_title() {
		return __( 'Vibe Countdown', 'wplms' );
	}

	public function get_icon() {
		return 'vicon vicon-timer';
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
			'date',
			[
				'label' => __( 'Date', 'wplms' ),
				'type' => \Elementor\Controls_Manager::DATE_TIME,
			]
		);

		$this->add_control(
			'days',
			[
				'label' => __( 'Days', 'wplms' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 100,
				'step' => 1,
				'default' => 0
			]
		);

		$this->add_control(
			'hours',
			[
				'label' => __( 'hours', 'wplms' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 23,
				'step' => 1,
				'default' => 0
			]
		);

		$this->add_control(
			'minutes',
			[
				'label' => __( 'minutes', 'wplms' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 59,
				'step' => 1,
				'default' => 0
			]
		);

		$this->add_control(
			'seconds',
			[
				'label' => __( 'seconds', 'wplms' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 59,
				'step' => 1,
				'default' => 0
			]
		);

		$this->add_control(
			'size',
			[
				'label' => __( 'size', 'wplms' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 60,
				'step' => 1,
				'default' => 0
			]
		);

		$this->end_controls_section();

	}

	protected function render() {

		$settings = $this->get_settings_for_display();

		$shortcode = '[countdown_timer 
		date="'.($settings['date']).'" 
		days="'.($settings['days']).'" 
		hours="'.($settings['hours']).'" 
		minutes="'.($settings['minutes']).'" 
		seconds="'.($settings['seconds']).'" 
		size="'.($settings['size']).'"]';

		//echo $shortcode;

		echo do_shortcode($shortcode);
	}
}