<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wplms_Vibe_Form extends \Elementor\Widget_Base  // We'll use this just to avoid function name conflicts 
{


    public function get_name() {
		return 'vibe_form';
	}

	public function get_title() {
		return __( 'Vibe Form', 'wplms' );
	}

	public function get_icon() {
		return 'vicon vicon-layout-cta-left';
	}

	public function get_categories() {
		return [ 'wplms' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_title',
			[
				'label' =>  __( 'Controls', 'wplms' ),
			]
		);
			
		$this->add_control(
			'form_type',
			[
				'label' => __( 'Select Form Type', 'wplms' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'contact',
				'options' => [
					'contact' => 'Contact Form',
					'event' => 'Event Form',
				]
			]
		);
			
		$this->add_control(
			'to',
			[
				'label' => __( 'to', 'wplms' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __('example@example.com', 'wplms'),
				'condition' => [
					'form_type' => 'contact',
				],
			]
		);
	
		$this->add_control(
			'subject',
			[
				'label' => __( 'subject', 'wplms' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __('....', 'wplms'),
				'condition' => [
					'form_type' => 'contact',
				],
			]
		);
	
		$this->add_control(
			'event',
			[
				'label' => __( 'event', 'wplms' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __('event', 'wplms'),
				'condition' => [
					'form_type' => 'event',
				],
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'placeholder',
			[
				'label' => __( 'name', 'wplms' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __( 'name', 'wplms' ),
			]
		);

		$repeater->add_control(
			'type',
			[
				'label' => __( 'Type', 'wplms' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
						'text' => 'Single Line Text Box (Text)',
						'textarea' => 'Multi Line Text Box (TextArea)',
						'select' => 'Select from Options (Select)',
						'checkbox' =>'Checkbox',
						'captcha' => 'Captcha field',
						'upload' => 'Upload File',
						'submit' =>'Submit Button',
					]
			]
		);
		
		$repeater->add_control(
			'upload_options',
			[
				'label' => __( 'upload formate', 'wplms' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
						'PDF' => 'PDF',
						'TEXT' => 'TEXT',
						'DOC' => 'DOC',
						'DOCx' =>'DOCx',
						'PPT' => 'PPT',
						'PPTX' => 'PPTX',
						'ZIP' =>'ZIP',
						'PNG' =>'PNG',
						'JPG' => 'JPG',
						'JPEG' => 'JPEG',
					],
					'condition' => [
					'type' => 'upload',
				]
			]
		);
		
		$repeater->add_control(
			'options',
			[
				'label' => __( 'Enter Select Options', 'wplms' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __( 'Comma seperated options.', 'wplms' ),
			]
		);

		$repeater->add_control(
			'validate',
			[
				'label' => __( 'validate', 'wplms' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
						'none' => 'None',
						'required' => 'Required',
						'email' => 'Email',
						'numeric' =>'Numeric',
						'phone' => 'Phone number'

					]
				
			]
		);

		$this->add_control(
			'elements',
			[
				'label' => __( 'Add Form elements', 'wplms' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'placeholder' => __( 'name', 'wplms' ),
						'type' => __( 'Form elements type', 'wplms' ),
						'validate' => __( 'Click', 'elementor' ),
						'upload_options' => ''
					],
					[
						'placeholder' => __( 'name', 'wplms' ),
						'type' => __( 'Form elements type', 'wplms' ),
						'validate' => __( 'Click', 'elementor' ),
						'upload_options' => ''
					],
				],
				'title_field' => __( 'Form elements', 'wplms' )


			]
		);

	}
//...............

	protected function render() {
		$settings = $this->get_settings_for_display();
// 		add_Action('wp_footer',function (){
// echo '
// <script>
//     $(document).ready(function(){
//          alert($("#elementor-control-default-c760").val());
//     });
// </script>
// ';});
		
		/*[form_element type="text" validate="" options="" upload_options="null" placeholder="Name"] [/form]*/
		
		// echo $shortcode;
		foreach ( $settings['elements'] as $index => $items ) {
				$child_shortcode.= '[form_element type="'.($items['type']).'" validate="'.($items['validate']).'" options="'.($items['options']).'" upload_options="'.($items['upload_options']).'" placeholder="'.($items['placeholder']).'"]';
			}
			$shortcode = '[form to="'.$settings['to'].'" subject="'.$settings['subject'].'" event="'.$settings['event'].'"] '.$child_shortcode.'[/form]';
			echo do_shortcode($shortcode);
	}
}

