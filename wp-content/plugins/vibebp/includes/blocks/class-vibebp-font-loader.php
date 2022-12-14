<?php
/**
 * Load google fonts.
 *
 * @package VibeBP
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Load google fonts for our blocks.
 *
 * @since 1.6.0
 */
class VibeBP_Font_Loader {


	/**
	 * This plugin's instance.
	 *
	 * @var VibeBP_Font_Loader
	 */
	private static $instance;

	/**
	 * Registers the plugin.
	 */
	public static function register() {
		if ( null === self::$instance ) {
			self::$instance = new VibeBP_Font_Loader();
		}
	}

	/**
	 * The Plugin version.
	 *
	 * @var string $_version
	 */
	private $_version;


	/**
	 * The Constructor.
	 */
	public function __construct() {
		$this->_version = VIBEBP_VERSION;

		add_action( 'wp_enqueue_scripts', array( $this, 'fonts_loader' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'fonts_loader' ) );
	}

	/**
	 * Load fonts.
	 *
	 * @access public
	 */
	public function fonts_loader() {
		global $post;

		if ( $post && isset( $post->ID ) ) {

			$fonts = get_post_meta( $post->ID, '_vibebp_attr', true );

			if ( ! empty( $fonts ) ) {

				$fonts = array_unique( explode( ',', $fonts ) );

				$system = array(
					'Arial',
					'Tahoma',
					'Verdana',
					'Helvetica',
					'Times New Roman',
					'Trebuchet MS',
					'Georgia',
				);

				$gfonts = '';

				$gfonts_attr = ':100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic';

				foreach ( $fonts as $font ) {
					if ( ! in_array( $font, $system, true ) && ! empty( $font ) ) {
						$gfonts .= str_replace( ' ', '+', trim( $font ) ) . $gfonts_attr . '|';
					}
				}

				if ( ! empty( $gfonts ) ) {
					$query_args = array(
						'family' => $gfonts,
					);

					wp_register_style(
						'vibebp-block-fonts',
						add_query_arg( $query_args, '//fonts.googleapis.com/css' ),
						array(),
						$this->_version
					);

					wp_enqueue_style( 'vibebp-block-fonts' );
				}

				// Reset.
				$gfonts = '';
			}
		}
	}
}

VibeBP_Font_Loader::register();
