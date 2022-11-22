<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wplms_Course_Instructor_Field extends \Elementor\Widget_Base  // We'll use this just to avoid function name conflicts 
{


    public function get_name() {
		return 'wplms_course_instructor_field';
	}

	public function get_title() {
		return __( 'Course Instructor Info', 'wplms' );
	}

	public function get_icon() {
		return 'vicon vicon-user';
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


		$groups = array();
		$fields = array();
		if(function_exists('bp_xprofile_get_groups')){
			$groups = bp_xprofile_get_groups( array(
				'fetch_fields' => true
			) );
		}
		

		if(!empty($groups)){
			foreach($groups as $group){
				if(!empty($group->fields)){
					foreach ( $group->fields as $field ) {
						//$field = xprofile_get_field( $field->id );
						$fields[$field->id]=$field->name;
					}
				}
			}
		}


		$this->add_control(
			'field_id',
			[
				'label' => __( 'Profile Field', 'vibebp' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'name',
				'options' => $fields,
			]
		);

		$this->add_control(
			'color',
			[
				'label' => __( 'Text Color', 'wplms' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .course_instructor_field_text' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'font-size',
			[
				'label' =>__('Font Size', 'wplms'),
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
					'{{WRAPPER}} .course_instructor_field_text' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'style',
			[
				'label' => __( 'Style', 'vibebp' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					'' =>__('Default','vibebp'),
					'stacked' =>__('Stacked','vibebp'),
					'spaced'=>__('Spaced','vibebp'),
					'nolabel'=>__('No Label','vibebp'),
					'icon'=>__('Icons','vibebp'),
				)
			]
		);
		$this->end_controls_section();

	}

	protected function render() {

		$settings = $this->get_settings_for_display();
		wp_enqueue_style('wplms_plugin_elementor',plugins_url('../../../../../assets/css/general.css',__FILE__),array(),WPLMS_PLUGIN_VERSION);
		$shortcode = '[course_instructor_field field_id="'.$settings['field_id'].'" style="'.$settings['style'].'"]';

		echo '<div class="course_instructor_field_text">'.do_shortcode($shortcode).'</div>';
	}
}