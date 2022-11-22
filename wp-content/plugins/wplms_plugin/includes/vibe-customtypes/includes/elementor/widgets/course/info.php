<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wplms_Course_Info extends \Elementor\Widget_Base 
{


    public function get_name() {
		return 'wplms_course_info';
	}

	public function get_title() {
		return __( 'Course Information', 'wplms' );
	}

	public function get_icon() {
		return 'vicon vicon-info';
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
			'info',
			[
				'label' => __( 'Element', 'wplms' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options'=>apply_filters('wplms_course_info_display_options',[
					'title'=>__('Course Title','wplms'),
					'excerpt'=>__('Course short description','wplms'),
					'description'=>__('Course Full description','wplms'),
					'average_rating'=>__('Average Rating','wplms'),
					'average_rating_count'=>__('Average Rating Score','wplms'),
					'review_count'=>__('Review Count','wplms'),
					'student_count'=>__('Student Count','wplms'),
					'last_update'=>__('Last updated','wplms'),
					'instructor_name'=>__('Instructor name','wplms'),
					'course_duration'=>__('Course Duration','wplms'),
					'cumulative_time'=>__('Cumulative time of Units & Quiz','wplms'),
					'curriculum_item_count'=>__('Curriculum Item count','wplms'),
					'certificate'=>__('Certificate link','wplms'),
					'badge'=>__('Badge ','wplms'),
					'course-cat'=>__('Course Category ','wplms'),
					'pre-required-courses'=>__('Pre-Required courses ','wplms'),
				])
			]
		);


		$this->add_control(
			'element',
			[
				'label' => __( 'Element', 'wplms' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options'=>[
					'h1'=>'H1',
					'h2'=>'H2',
					'h3'=>'H3',
					'p'=>'p',
					'div'=>'div',
				],
			]
		);

		$this->add_control(
			'color',
			[
				'label' => __( 'Text Color', 'wplms' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .course_element_text,{{WRAPPER}} .course_element_text>a' => 'color: {{VALUE}}',
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
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .course_element_text' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);


		$this->end_controls_section();

	}

	protected function render() {

		$settings = $this->get_settings_for_display();

		$shortcode = '[course_info info="'.$settings['info'].'" font_size="'.$settings['font_size']['size'].$settings['font_size']['unit'].'" color="'.$settings['color'].'"]';
		switch($settings['element']){
			case 'h1':
				echo '<h1 class="course_element_text">'.do_shortcode($shortcode).'</h1>';
			break;
			case 'h2':
				echo '<h2 class="course_element_text">'.do_shortcode($shortcode).'</h2>';
			break;
			case 'h3':
				echo '<h3 class="course_element_text">'.do_shortcode($shortcode).'</h3>';
			break;
			case 'p':
				echo '<p class="course_element_text">'.do_shortcode($shortcode).'</p>';
			break;
			default:
				echo '<div class="course_element_text">'.do_shortcode($shortcode).'</div>';
			break;
		}
		
	}
}