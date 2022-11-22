<?php 

class BP_Helpdesk_Component extends BP_Component {

	
	function __construct() {
		global $bp;
		parent::start(
			VIBE_HELPDESK_SLUG,
			__( 'Helpdesk', 'vibe-helpdesk' ),
			VIBE_HELPDESK_PLUGIN_DIR
		);
		$this->includes();

		
		$bp->active_components[$this->id] = '1';

		
		add_action( 'init', array( &$this, 'register_post_types' ) );
		
	}
	
	public function setup_globals($args=array()) {
		global $bp;

		// Defining the slug in this way makes it possible for site admins to override it
		if ( !defined( 'VIBE_HELPDESK_SLUG' ) )
			define( 'VIBE_HELPDESK_SLUG', $this->id );

		
		
		
		$globals = array(
			'slug'                  => VIBE_HELPDESK_SLUG,
			'root_slug'             => isset( $bp->pages->{$this->id}->slug ) ? $bp->pages->{$this->id}->slug : VIBE_HELPDESK_SLUG,
			'has_directory'         => false, // Set to false if not required
			'notification_callback' => 'bp_helpdesk_format_notifications',
			'search_string'         => __( 'Search Helpdesk ...', 'vibe-helpdesk' ),
			//'global_tables'         => $global_tables
		);
		parent::setup_globals( $globals );

	}

	function includes($includes=[]) {
		$includes = array(
		);
		parent::includes( $includes );
	 }
}

function bp_helpdesk_load_core_component() {
	global $bp;

	$bp->helpdesk = new BP_Helpdesk_Component;
	//print_r($bp);die();
}
add_action( 'bp_loaded', 'bp_helpdesk_load_core_component' );