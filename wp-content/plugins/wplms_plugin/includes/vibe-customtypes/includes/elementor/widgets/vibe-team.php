<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wplms_Vibe_Team extends \Elementor\Widget_Base  // We'll use this just to avoid function name conflicts 
{


    public function get_name() {
		return 'vibe_team';
	}

	public function get_title() {
		return __( 'Vibe Team', 'wplms' );
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

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'icon',
			[
				'label' => __( 'Social icon', 'wplms' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __( 'icons', 'wplms' ),
			]
		);

		$repeater->add_control(
			'url',
			[
				'label' => __( 'Icon link', 'wplms' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'http://www.vibethemes.com', 'wplms' ),
				'default' => [
					'url' => 'http://www.vibethemes.com',
					'is_external' => true,
					'nofollow' => true,
				],
			]
		);
		$this->add_control(
			'pic',
			[
				'label' => __( 'member image', 'wplms' ),
				'type' => \Elementor\Controls_Manager::GALLERY,
				'placeholder' => '',
			]
		);

		$this->add_control(
			'name',
			[
				'label' => __( 'member name', 'wplms' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => '',
			]
		);

		$this->add_control(
			'designation',
			[
				'label' => __( 'member designation', 'wplms' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => '',
			]
		);

		$this->add_control(
			'social_info',
			[
				'label' => __( 'Add Social information', 'wplms' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'icon' => '',
						'url' => 'http://www.vibethemes.com'
					],
				],
				'title_field' => __( 'Add Social information', 'wplms' )
			]
		);

		$this->end_controls_section();

	}

	protected function render() {

		$settings = $this->get_settings_for_display();
		foreach ( $settings['social_info'] as $index => $items ) {
			$child_shortcode.= '[team_social url="'.($items['url']['url']).'" icon="'.($items['icon']).'"]';
		}
		$pic = '';
		$alt=[];
		if(!empty($settings['pic'])){
			foreach($settings['pic'] as $img){
				if(empty($pic)){
					$pic = $img['url'];	
				}else{
					$alt[]=$img['url'];	
				}
				
			}
		}
		$shortcode = '[team_member pic="'.($settings['pic']['0']['url']).'" alt="'.($alt?implode(',',$alt):'').'" name="'.($settings['name']).'" designation="'.($settings['designation']).'" ]'.$child_shortcode.'[/team_member]';
		echo do_shortcode($shortcode);
	}
}