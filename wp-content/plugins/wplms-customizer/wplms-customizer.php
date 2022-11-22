<?php
/*
Plugin Name: WPLMS Customizer Plugin
Plugin URI: http://www.Vibethemes.com
Description: A simple WordPress plugin to modify WPLMS template
Version: 1.0
Author: VibeThemes
Author URI: http://www.vibethemes.com
License: GPL2
*/


include_once 'classes/customizer_class.php';

if(class_exists('WPLMS_Customizer_Plugin_Class'))
{	
    // instantiate the plugin class
    $wplms_customizer = new WPLMS_Customizer_Plugin_Class();
    add_filter('wplms_login_threshold',function() { return 10; });
}

// mail issue fixed

add_filter('wp_mail_from', 'new_mail_fromqw');
add_filter('wp_mail_from_name', 'new_mail_from_nameqw');

function new_mail_fromqw($old) {
return 'kopanom@qubitengineering.co.za';
}

function new_mail_from_nameqw($old) {
return 'SASRIA';
}

/*
*
* place BP customizations here
*
*/
 
// Set BP to use wp_mail
add_filter( 'bp_email_use_wp_mail', '__return_true' );
 
// Set messages to HTML
remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
add_filter( 'wp_mail_content_type', 'set_html_content_type' );
function set_html_content_type() {
    return 'text/html';
}
 
// Use HTML template
add_filter( 'bp_email_get_content_plaintext', 'get_bp_email_content_plaintext', 10, 4 );
function get_bp_email_content_plaintext( $content = '', $property = 'content_plaintext', $transform = 'replace-tokens', $bp_email ) {
    if ( ! did_action( 'bp_send_email' ) ) {
        return $content;
    }
    return $bp_email->get_template( 'add-content' );
}




add_action('wp_footer', function(){
?>
<style>
@import url('https://sasria.fundanisa.com/wp-content/uploads/2021/07/Helvetica.ttf');
</style>
<?php
});

add_action( 'admin_head', function () { ?>
<style>
	div#certificate-builder {
    	display: block;
	}
</style>
<?php } );

add_action('wplms_course_stats_process','process_custom_course_stat',10,8);
add_filter('wplms_course_stats_list','add_custom_course_stat');

 function add_custom_course_stat($list){
   $new_list = array(
                    'Identity Number'=>'Identity Number'
                     );
   
   $list=array_merge($list,$new_list);
    return $list;
}
          
function process_custom_course_stat(&$csv_title, &$csv,&$i,&$course_id,&$user_id,&$field,&$ccsv,&$k){
  if($field != 'Identity Number') // Ensures the field was checked.
  return;
  $title=__('Identity Number','wplms');
	if(!in_array($title,$csv_title))
	 $csv_title[$k]=array('title'=>$title,'field'=>'Identity Number');
		$ifield = 'Identity Number'; 
  	if(bp_is_active('xprofile'))
   		$field_val= bp_get_profile_field_data( 'field='.$ifield.'&user_id=' .$user_id );
	if(isset($field_val) &&  $field_val){
	   		$csv[$i][]= $field_val;
	      	$ccsv[$i]['Identity Number'] =  $field_val; 
    }else{
      $csv[$i][]= 'NA';
      $ccsv[$i]['Identity Number'] = 'NA';
    }
 }