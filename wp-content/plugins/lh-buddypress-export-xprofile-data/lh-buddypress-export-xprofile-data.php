<?php
/**
 * Plugin Name: LH Buddypress Export Xprofile Data
 * Plugin URI:  https://lhero.org/portfolio/lh-buddypress-export-xprofile-data/
 * Description: Export profile field data from BuddyPress.
 * Author:      Peter Shaw
 * Author URI:  https://shawfactor.com
 * Version:     2.00
 * Text Domain: lh_bexd
 */

/**
* LH Buddypress Export Xprofile Data plugin class
*/


if (!class_exists('LH_Buddypress_export_xprofile_data_plugin')) {


class LH_Buddypress_export_xprofile_data_plugin {
    
        private static $instance;
    

    
    static function return_plugin_namespace(){

    return 'lh_bexd';

    }
    
/**
 * Generate a report.
 *
 * Outputs to the browser as a file requesting download.
 *
 * @since 1.0
 *
 * @param array $profile_field_ids Array of integers.
 */
static function generate_report( $profile_field_ids ) {
	$filename   = sanitize_file_name( sprintf( 'lh_bexp-report-%s', date( 'Y-m-d-U' ) ) );
	$first_loop = true;
	$headings   = array( __( 'user_id', self::return_plugin_namespace() ) );
	$report     = array();
	$users      = get_users( array( 'fields' => array('ID' ) ) );


	// Generate the report.
	foreach ( $users as $user ) {
		if ( ! bp_is_user_active( $user->ID ) ) {
			continue;
		}

		$report[ "{$user->ID}" ] = array( $user->ID );

		foreach ( $profile_field_ids as $field_id ) {
			$field      = xprofile_get_field( $field_id );
			$field_data = xprofile_get_field_data( $field_id, $user->ID, 'comma' );
			$field_data = apply_filters( 'lh_bexd_profile_field_value', $field_data, $field->type, $field->id );

			$report[ "{$user->ID}" ][] = $field_data;

			if ( $first_loop ) {
				$headings[] = $field->name;
			}
		}

		$first_loop = false;
	}


	// Send the report.
	header( 'Content-Description: File Transfer' );
	header( "Content-Disposition: attachment; filename={$filename}.csv" );
	header( 'Content-Type: text/csv; charset=' . get_option( 'blog_charset' ), true );

	$fh = @fopen( 'php://output', 'w' );
	fwrite( $fh, "\xEF\xBB\xBF" );
	fputcsv( $fh, $headings );

	foreach ( $report as $data ) {
		fputcsv( $fh, $data );
	}

	fclose( $fh );
	exit;
}
    
/**
 * Draw the reports screen.
 *
 * @since 1.0
 */
public function reports_screen() {
	$form_url     = remove_query_arg( array( 'action' ), $_SERVER['REQUEST_URI'] );
	$form_url     = add_query_arg( 'action', 'report', $form_url );
	$field_groups = array();
	$select_html  = '<option></option>';

	wp_enqueue_script( 'lh_bexd-js', plugin_dir_url( __FILE__ ) . 'scripts/lh-bexd.js', array( 'underscore', 'wp-util' ) );
	wp_enqueue_style( 'lh_bexd-css', plugin_dir_url( __FILE__ ) . 'styles/lh-bexd.css' );


	// Fields groups
	$raw_groups = bp_profile_get_field_groups();
	foreach ( $raw_groups as $raw_fields ) {

		// Handle bug: https://buddypress.trac.wordpress.org/ticket/7154
		if ( ! isset( $raw_fields->fields  )) {
			continue;
		}

		// Fields
		$field_groups[ $raw_fields->name ] = wp_list_pluck( $raw_fields->fields, 'name', 'id' );
	}
	unset( $raw_groups );


	// Build HTML for select box.
	foreach ( $field_groups as $group_name => $fields ) {
		$options = '';

		foreach ( $fields as $field_id => $field_name ) {
			$options .= sprintf( '<option value="%s">%s</option>', esc_attr( $field_id ), esc_html( $field_name ) );
		}

		$select_html .= sprintf( '<optgroup label="%s">%s</optgroup>', esc_attr( $group_name ), $options );
	}
	?>

	<div class="wrap">
		<h1>
			<?php echo esc_html( _x( 'Member Profile Data Reports', 'wp-admin screen title', 'lh_bexd' ) ); ?>
			<button class="page-title-action"><?php esc_html_e( 'Add Field', 'lh_bexd' ); ?></button>
		</h1>

		<form action="<?php echo esc_url( $form_url ); ?>" method="post" target="_blank">
			<table class="wp-list-table widefat fixed striped lh_bexd" id="lh_bexd-table">
				<thead>
					<tr>
						<td class="manage-column column-action action-column"></th>
						<th scope="col" class="column-primary column-field"><?php esc_html_e( 'Profile Field', 'lh_bexd' ); ?></th>
					</tr>
				</thead>

				<tbody>
					<tr>
						<th scope="row" class="action-column disabled">
							<span class="lh_bexd-delete-icon" title="<?php esc_attr_e( 'Delete', 'lh_bexd' ); ?>">
								<span class="screen-reader-text"><?php esc_html_e( 'Delete', 'lh_bexd' ); ?></span>
							</span>
						</th>
						<td class="select-column"><select name="ids[]"><?php echo $select_html; ?></select></td>
					</tr>
				</tbody>
			</table>

			<?php wp_nonce_field( 'lh_bexd', self::return_plugin_namespace().'-nonce' ); ?>
			<input type="submit" class="button button-primary button-large" id="lh_bexd-submit" value="<?php esc_attr_e( 'Generate Report', 'lh_bexd' ); ?>">
		</form>
	</div>

	<script type="text/html" id="tmpl-lh_bexd-row">
		<tr>
			<th scope="row" class="action-column">
				<span class="lh_bexd-delete-icon" title="<?php esc_attr_e( 'Delete', 'lh_bexd' ); ?>">
					<span class="screen-reader-text"><?php esc_html_e( 'Delete', 'lh_bexd' ); ?></span>
				</span>
			</th>
			<td class="select-column"><select name="ids[]"><?php echo $select_html; ?></select></td>
		</tr>
	</script>

	<?php
}


/**
 * Handle form submission/report generation.
 *
 * @since 1.0
 */
public function maybe_generate_report() {
	if ( ! bp_current_user_can( 'bp_moderate' ) ) {
		return;
	}

	if ( empty( $_GET['action'] ) || $_GET['action'] !== 'report' || empty( $_POST['ids'] ) ) {
		return;
	}

	check_admin_referer( 'lh_bexd', self::return_plugin_namespace().'-nonce' );
	self::generate_report( array_filter( wp_parse_id_list( $_POST['ids'] ) ) );

	exit;
}
    
/**
 * Add wp-admin UI menu to access the reports page.
 *
 * @since 1.0
 */
public function add_admin_menu() {
	$hook = add_users_page(
		_x( 'Member Profile Data Reports', 'wp-admin screen title', 'lh_bexd' ),
		_x( 'Export Xprofile Data', 'wp-admin menu label', 'lh_bexd' ),
		'manage_options',
		'lh_bexd',
		array($this,'reports_screen')
	);

	add_action( "load-$hook",array($this,'maybe_generate_report') );
}

    
    public function plugin_init(){
        
    if (!function_exists('bp_is_active') or !bp_is_active( 'xprofile' ) ) {
		return;
	}
	
	add_action( bp_core_admin_hook(), array($this,'add_admin_menu'));

	add_filter( 'lh_bexd_profile_field_value', 'convert_chars'      );
	add_filter( 'lh_bexd_profile_field_value', 'force_balance_tags' );
	add_filter( 'lh_bexd_profile_field_value', 'xprofile_filter_format_field_value',         1, 2 );
	add_filter( 'lh_bexd_profile_field_value', 'xprofile_filter_format_field_value_by_type', 8, 3 );

        
        
    }
    
    
    
     /**
     * Gets an instance of our plugin.
     *
     * using the singleton pattern
     */
    public static function get_instance(){
        if (null === self::$instance) {
            self::$instance = new self();
        }
 
        return self::$instance;
    }   
    
    
public function __construct() {
    
 add_action( 'bp_init', array($this,'plugin_init'));
    



}
    
    
    
}

$lh_buddypress_export_xprofile_data_instance = LH_Buddypress_export_xprofile_data_plugin::get_instance();


}

?>