<?php
/**
 * Block Patterns for Tutorly
 *
 * @author      VibeThemes
 * @category    Admin
 * @package     Initialization
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;


class Wplms_Theme_Block_Patterns{

    public static $instance;
    
    public static function init(){

        if ( is_null( self::$instance ) )
            self::$instance = new Wplms_Theme_Block_Patterns();

        return self::$instance;
    }

    private function __construct(){
    	add_action( 'init', array($this,'block_patterns'), 9 );
    }

    function block_patterns() {
		$block_pattern_categories = array(
			'featured' => array( 'label' => esc_html__( 'Featured', 'tutorly' ) ),
			'footer'   => array( 'label' => esc_html__( 'Footers', 'tutorly' ) ),
			'header'   => array( 'label' => esc_html__( 'Headers', 'tutorly' ) ),
			'query'    => array( 'label' => esc_html__( 'Query', 'tutorly' ) ),
			'pages'    => array( 'label' => esc_html__( 'Pages', 'tutorly' ) ),
		);

		$block_pattern_categories = apply_filters( 'ttutorly_block_pattern_categories', $block_pattern_categories );

		foreach ( $block_pattern_categories as $name => $properties ) {
			if ( ! WP_Block_Pattern_Categories_Registry::get_instance()->is_registered( $name ) ) {
				register_block_pattern_category( $name, $properties );
			}
		}

		$block_patterns = array(
			'footer-default',
			'header-default',
		);

	
		$block_patterns = apply_filters( 'tutorly_block_patterns', $block_patterns );

		foreach ( $block_patterns as $block_pattern ) {
			$pattern_file = get_theme_file_path( '/includes/patterns/' . $block_pattern . '.php' );

			register_block_pattern(
				'tutorly/' . $block_pattern,
				require $pattern_file
			);
		}
	}
}

Wplms_Theme_Block_Patterns::init();

Tutorly_Block_Patterns::init();


