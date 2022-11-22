<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wplms_Course_Instructor_Card extends \Elementor\Widget_Base  // We'll use this just to avoid function name conflicts 
{


    public function get_name() {
		return 'wplms_course_instructor_card';
	}

	public function get_title() {
		return __( 'Course Instructor Card', 'wplms' );
	}

	public function get_icon() {
		return 'vicon vicon-user';
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


		$cards = array();
		$args = array(
			'post_type'=>'member-card',
			'post_status'=>'publish',
			'posts_per_page'=>-1
		);
		$results  = new WP_Query($args);
       	if($results->have_posts()){
       		while($results->have_posts()){
       			$results->the_post();global $post;
       			$cards[$post->ID] = $post->post_title; 
	
			}
			wp_reset_postdata();
		}
		$this->add_control(
			'card_id',
			[
				'label' => __( 'Select Card', 'vibebp' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'name',
				'options' => $cards,
			]
		);

		

		$this->end_controls_section();

	}

	protected function render() {

		$settings = $this->get_settings_for_display();
		wp_enqueue_style('wplms_plugin_elementor',plugins_url('../../../../../assets/css/general.css',__FILE__),array(),WPLMS_PLUGIN_VERSION);
		$shortcode = '[course_instructor_card card_id="'.$settings['card_id'].'" ]';

		echo '<div class="course_instructor_card">'.do_shortcode($shortcode).'</div>';
	}
}