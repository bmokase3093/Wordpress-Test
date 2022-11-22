<?php
/**
 * Register blocks.
 *
 * @package VibeBP
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Load registration for our blocks.
 *
 * @since 1.6.0
 */
class Wplms_Plugin_Register_Guten_Blocks {


	/**
	 * This plugin's instance.
	 *
	 * @var VibeBP_Register_Blocks
	 */
	private static $instance;

	/**
	 * Registers the plugin.
	 */
	public static function register() {
		if ( null === self::$instance ) {
			self::$instance = new Wplms_Plugin_Register_Guten_Blocks();
		}
	}

	/**
	 * The Plugin version.
	 *
	 * @var string $_slug
	 */
	private $_slug;

	/**
	 * The Constructor.
	 */
	public function __construct() {
		$this->counter = 0;
		$this->_slug = 'wplms';

		add_action( 'init', array( $this, 'register_blocks' ), 99 );
		add_action( 'admin_enqueue_scripts', array($this,'vibebp_block_assets'));
		add_filter( 'block_categories', array($this,'block_category'), 1, 2);


		// Hook: Frontend assets.
		//add_action( 'enqueue_block_assets', array($this,'block_assets'),9 );

		//FOR TESTING

	}

	/**
	 * Add actions to enqueue assets.
	 *
	 * @access public
	 */
	public function register_blocks() {

		// Return early if this function does not exist.
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		// Shortcut for the slug.
		$slug = $this->_slug;



		register_block_type(
			$slug . '/coursefeatured',
			array(
				'editor_script' => $slug . '-editor',
				'editor_style'  => $slug . '-editor',
				'style'         => $slug . '-frontend',
				'render_callback' => array($this,'coursefeatured')
			)
		);

		register_block_type(
			$slug . '/courseinfo',
			array(
				'editor_script' => $slug . '-editor',
				'editor_style'  => $slug . '-editor',
				'style'         => $slug . '-frontend',
				'render_callback' => array($this,'courseinfo')
			)
		);

		register_block_type(
			$slug . '/coursebutton',
			array(
				'editor_script' => $slug . '-editor',
				'editor_style'  => $slug . '-editor',
				'style'         => $slug . '-frontend',
				'render_callback' => array($this,'coursebutton')
			)
		);

		register_block_type(
			$slug . '/coursecurriculum',
			array(
				'editor_script' => $slug . '-editor',
				'editor_style'  => $slug . '-editor',
				'style'         => $slug . '-frontend',
				'render_callback' => array($this,'coursecurriculum')
			)
		);
		register_block_type(
			$slug . '/courseinstructorfield',
			array(
				'editor_script' => $slug . '-editor',
				'editor_style'  => $slug . '-editor',
				'style'         => $slug . '-frontend',
				'render_callback' => array($this,'courseinstructorfield')
			)
		);
		register_block_type(
			$slug . '/courseinstructorcard',
			array(
				'editor_script' => $slug . '-editor',
				'editor_style'  => $slug . '-editor',
				'style'         => $slug . '-frontend',
				'render_callback' => array($this,'courseinstructorcard')
			)
		);
		register_block_type(
			$slug . '/courseinstructordata',
			array(
				'editor_script' => $slug . '-editor',
				'editor_style'  => $slug . '-editor',
				'style'         => $slug . '-frontend',
				'render_callback' => array($this,'courseinstructordata')
			)
		);
		register_block_type(
			$slug . '/coursepricing',
			array(
				'editor_script' => $slug . '-editor',
				'editor_style'  => $slug . '-editor',
				'style'         => $slug . '-frontend',
				'render_callback' => array($this,'coursepricing')
			)
		);

		register_block_type(
			$slug . '/coursereviews',
			array(
				'editor_script' => $slug . '-editor',
				'editor_style'  => $slug . '-editor',
				'style'         => $slug . '-frontend',
				'render_callback' => array($this,'coursereviews')
			)
		);
		register_block_type(
			$slug . '/userreviews',
			array(
				'editor_script' => $slug . '-editor',
				'editor_style'  => $slug . '-editor',
				'style'         => $slug . '-frontend',
				'render_callback' => array($this,'userreviews')
			)
		);

	}

	function userreviews($settings){
	
		$shortcode = '[user_reviews hide_if_none="'.$settings['hide_if_none'].'" total="'.$settings['total'].'" breakup="'.$settings['breakup'].'" orderby="'.$settings['orderby'].'" row="'.$settings['row'].' style="'.$settings['style'].'"]';

		return do_shortcode($shortcode);
	}

	function coursereviews($settings){
		$shortcode = '[course_reviews hide_if_none="'.$settings['hide_if_none'].'" number="'.$settings['number'].'" show_count="'.$settings['rating_count'].'" show_breakup="'.$settings['hide_if_none'].'" orderby="'.$settings['orderby'].'" show_helpfulness="'.$settings['orderby'].'"]'.$settings['title'].'[/course_reviews]';

		return do_shortcode($shortcode);
	}

	function coursepricing($settings){
		wp_enqueue_style('wplms_plugin_elementor',plugins_url('../../assets/css/general.css',__FILE__),array(),WPLMS_PLUGIN_VERSION);
		if(empty($settings['pricing_display'])){$settings['pricing_display']='';}
		$shortcode = '[course_pricing  style="'.($settings['pricing_display']).'"]';

		return '<div class="course_pricing">'.do_shortcode($shortcode).'</div>';
	}

	function courseinstructordata($settings){
		wp_enqueue_style('wplms_plugin_elementor',plugins_url('../../assets/css/general.css',__FILE__),array(),WPLMS_PLUGIN_VERSION);
		$shortcode = '[course_instructor_data data="'.$settings['data'].'"]';
		return '<div class="course_instructor_data">'.do_shortcode($shortcode).'</div>';
	}

	function courseinstructorcard($settings){

		wp_enqueue_style('wplms_plugin_elementor',plugins_url('../../assets/css/general.css',__FILE__),array(),WPLMS_PLUGIN_VERSION);
		$shortcode = '[course_instructor_card card_id="'.$settings['card_id'].'" style="'.$settings['style'].'"]';

		return '<div class="course_instructor_card_text">'.do_shortcode($shortcode).'</div>';
	}

	function courseinstructorfield($settings){
		wp_enqueue_style('wplms_plugin_elementor',plugins_url('../../assets/css/general.css',__FILE__),array(),WPLMS_PLUGIN_VERSION);
		$shortcode = '[course_instructor_field field_id="'.$settings['field_id'].'" style="'.$settings['style'].'"]';

		return '<div class="course_instructor_field_text">'.do_shortcode($shortcode).'</div>';
	}

	function coursecurriculum($settings){

		$shortcode = '[course_curriculum]';

		return do_shortcode($shortcode);
	}

	function coursebutton($settings){
		$shortcode = '[course_button bg="'.$settings['bg'].'" width="'.$settings['width']['size'].$settings['width']['unit'].'" height="'.$settings['height']['size'].$settings['height']['unit'].'" radius="'.$settings['radius']['size'].$settings['radius']['unit'].'" font_size="'.$settings['font_size']['size'].$settings['font_size']['unit'].'" color="'.$settings['color'].'" show_price="'.$settings['show_price'].'" hide_extras="'.$settings['hide_extras'].'"]'.$settings['content'].'[/course_button]';

		return do_shortcode($shortcode);
	}

	function coursefeatured($settings){

		$shortcode = '[course_featured]';

		return do_shortcode($shortcode);
	}

	function courseinfo($settings){

		$shortcode = '[course_info info="'.$settings['info'].'" font_size="'.$settings['font_size']['size'].$settings['font_size']['unit'].'" color="'.$settings['color'].'"]';
		$html = '';
		switch($settings['element']){
			case 'h1':
				$html = '<h1 class="course_element_text">'.do_shortcode($shortcode).'</h1>';
			break;
			case 'h2':
				$html = '<h2 class="course_element_text">'.do_shortcode($shortcode).'</h2>';
			break;
			case 'h3':
				$html = '<h3 class="course_element_text">'.do_shortcode($shortcode).'</h3>';
			case 'h4':
				$html = '<h4 class="course_element_text">'.do_shortcode($shortcode).'</h4>';
			case 'h5':
				$html = '<h5 class="course_element_text">'.do_shortcode($shortcode).'</h5>';
			break;
			case 'p':
				$html = '<p class="course_element_text">'.do_shortcode($shortcode).'</p>';
			break;
			default:
				$html = '<div class="course_element_text">'.do_shortcode($shortcode).'</div>';
			break;
		}

		return '<div class="" style="font-size:'.$settings['font_size']['size'].$settings['font_size']['unit'].';color:'.$settings['color'].'">'.$html.'</div>';
	}

	function block_category( $categories, $post ) {
		$categories[] = array(
							'slug' => $this->_slug,
							'title' => __( 'Wplms', 'wplms' ),
						);
		return $categories;
	}

	function vibebp_block_assets(){
	
		// Shortcut for the slug.
		$slug = $this->_slug;
		$color_options = apply_filters('vibe_bp_gutenberg_text_color_options',array(

			array(
				'name'=>_x('Black','','vibebp'),
				'color'=>'#000000'
			),
			array(
				'name'=>_x('White','','vibebp'),
				'color'=>'#ffffff'
			),

		));

		$fontsizeunit_options = apply_filters('vibe_bp_gutenberg_text_color_options',array(
			'px','em','rem','pt','vh','vw','%'
		));
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


		$profile_field_styles = apply_filters('wplms_course_field_styles',array(
					'' =>__('Default','vibebp'),
					'stacked' =>__('Stacked','vibebp'),
					'spaced'=>__('Spaced','vibebp'),
					'nolabel'=>__('No Label','vibebp'),
					'icon'=>__('Icons','vibebp'),
				));

		$fontwieght_options = apply_filters('vibe_bp_gutenberg_text_color_options',array(
			'100','200','300','400','500','600','700','800','900'
		));

		$cards = array();

		$args = array(
			'post_type'=>'member-card',
			'post_status'=>'publish',
			'posts_per_page'=>-1
		);

		global $post;
		$temp_post = $post;
		$results  = new WP_Query($args);
       	if($results->have_posts()){
       		while($results->have_posts()){
       			$results->the_post();
       			
       			$cards[] = array('value'=>$post->ID,'label'=>$post->post_title); 
	
			}
		}
		wp_reset_postdata();
		$post = $temp_post;

		$settings = apply_filters('wplms_gutenberg_data',array(
			'default_avatar'=>plugins_url( '../../assets/images/avatar.jpg',  __FILE__ ),
			'default_profile_value'=>_x('default value','','vibebp'),
			'default_name'=>_x('default name','','vibebp'),
			'cards' => $cards,
			'course_info_options'=>apply_filters('wplms_course_info_display_options',[
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
				]),
			'profile_field_styles' => $profile_field_styles,
			'course_inst_reviews_orderby' =>[
					'0' => __('Recent rating first','wplms'),
					'highest_rated' => __('Highest rating first','wplms'),
				],
			'user_reviews_row' => [
					'25'=>_x('4 in Row','elementor','wplms'),
					'33'=>_x('3 in Row','elementor','wplms'),
					'25'=>_x('2 in Row','elementor','wplms'),
				],
			'user_reviews_style' =>[
					'carousel'=>_x('Carousel','elementor','wplms'),
					'grid'=>_x('Grid','elementor','wplms'),
				],
			'user_types_options' => [
					'instructor'=>_x('Reviews posted for Instructor','elementor','wplms'),
					'student'=>_x('Reviews Posted by Student','elementor','wplms'),
				],
			'reviews_orderby' => [
					'0' => __('Recent rating first','wplms'),
					'highest_rated' => __('Highest rating first','wplms'),
					'helpful' => __('Most helpful first (if enabled)','wplms'),
				],
			'instructor_data_options'=>[
					'image'=>__('Instructor Image','wplms'),
					'avarage_rating'=>__('Average Rating','wplms'),
					'review_count'=>__('Review Count','wplms'),
					'student_count'=>__('Student Count','wplms'),
					'course_count'=>__('Course Count','wplms')
				],
			'profile_fields' => $fields,
			'font_size_units'=>$fontsizeunit_options,
			'elements' =>apply_filters('wplms_course_info_display_elements_options', [
					'h1'=>'H1',
					'h2'=>'H2',
					'h3'=>'H3',
					'h4'=>'H4',
					'h5'=>'H5',
					'p'=>'p',
					'div'=>'div',
				]),
			'align_options' => [
					 [
						'label' => __( 'Left', 'wplms' ),
						'value' => 'left',
					],
					 [
						'label' => __( 'Center', 'wplms' ),
						'value' => 'center',
					],
					 [
						'label' => __( 'Right', 'wplms' ),
						'value' => 'right',
					],
				],
			'current_user'=>wp_get_current_user(),
			'api_url'=>home_url().'/wp-json/'.Vibe_BP_API_NAMESPACE,
		));

		

		// Register block editor script for backend.
		wp_enqueue_script(
			'wplms-blocks-block-js', // Handle.
			plugins_url( '../assets/js/new_blocks.js', dirname( __FILE__ ) ),
			array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ),
			null, 
			true 
		);

		// Register block editor styles for backend.
		wp_register_style(
			'wplms-blocks-block-editor-css',
			plugins_url( '../assets/css/new_blocks.css', dirname( __FILE__ ) ),
			array( 'wp-edit-blocks' ), 
			null 
		);
		wp_localize_script( 'wplms-blocks-block-js', 'wplms_gutenberg_data', $settings );
	}


	
	
}

Wplms_Plugin_Register_Guten_Blocks::register();
