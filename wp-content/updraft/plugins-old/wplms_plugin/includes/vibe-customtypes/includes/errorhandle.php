<?php

 if ( ! defined( 'ABSPATH' ) ) exit;
class VibeErrors {
	function __construct() {
		$this->localizionName = '';
		$this->errors = new WP_Error();
		$this->initialize_errors();
	}
	/* get_error - Returns an error message based on the passed code
	Parameters - $code (the error code as a string)
	Returns an error message */
	function get_error($code = '') {
	global $vibe_options;
	if(isset($vibe_options['disable_errors']) && $vibe_options['disable_errors']){return '';}
	    $errorMessage ='<div class="alert alert-block"><span class="error"></span><button type="button" class="close" data-dismiss="alert">×</button>';
		$errorMessage .= $this->errors->get_error_message($code);
		if ($errorMessage == null) {
			return __("Unknown error.", 'wplms');
		}
		$errorMessage .= '  ..<a href="http://vibethemes.com/documentation/wplms/" target="_blank">more..</a></div>';
		return $errorMessage;
	}
	/* Initializes all the error messages */
	function initialize_errors() {
	    $this->errors->add('initialize', __('Please save the changes again.', 'wplms'));
	    $this->errors->add('unknown', __('Some Uknown issue appeared, please contact us.', 'wplms'));
	    $this->errors->add('unsaved_editor', __('Please save the Page Builder changes !', 'wplms'));
	    $this->errors->add('slider_not_found', __('Requested Slider was not found, Please check slider post id !', 'wplms'));
	    $this->errors->add('no_posts', __('No Posts Id\'s found in given Custom Post Type.', 'wplms'));
	    $this->errors->add('author_not_found', __('Author Information missing.', 'wplms'));
        $this->errors->add('access_denied', __('You do not have permission to do that.','wplms'));
        $this->errors->add('error_tweets', __('Unable to get tweets from Twitter !','wplms'));
        $this->errors->add('no_tweets', __('No public tweets found on Twitter !','wplms'));
        $this->errors->add('term_taxonomy_mismatch', __('Term : Taxonomy Mismatch: Selected Term does not exist in Taxonomy !','wplms'));
        $this->errors->add('term_postype_mismatch', __('Post Type : Taxonomy Mismatch: Selected Taxonomy does not exist in Post Type !','wplms'));
        $this->errors->add('no_featured', __('Featured Component does not Exist !','wplms'));
        $this->errors->add('incorrect_audio', __('Incorrect or Incompatible Audio Format !','wplms'));
        $this->errors->add('incorrect_video', __('Incorrect or Incompatible Video Embed Code !','wplms'));
        $this->errors->add('incorrect_modal', __('Modal ID Incorrect, please recheck !','wplms'));
        $this->errors->add('incorrect_testimonial', __('Testimonial ID Incorrect, please recheck !','wplms'));
        $this->errors->add('incorrect_post', __('Post ID Incorrect, please recheck !','wplms'));
	} //end function initialize_errors
}
/*
$error = new VibeErrors();$error->get_error('unsaved_editor');
 if(!isset($atts) || !isset($atts['post_type'])){
   return $error->get_error('unsaved_editor');
 }
*/

 if(!function_exists('calculate_duration_time')){
  function calculate_duration_time($seconds) {
  	switch($seconds){
  		case 1: $return = __('Seconds','wplms');break;
  		case 60: $return = __('Minutes','wplms');break;
  		case 3600: $return = __('Hours','wplms');break;
  		case 86400: $return = __('Days','wplms');break;
  		case 604800: $return = __('Weeks','wplms');break;
  		case 2592000: $return = __('Months','wplms');break;
  		case 31104000: $return = __('Years','wplms');break;
  		default:
  		$return = apply_filters('vibe_calculation_duration_default',$return,$seconds);
  		break;
  	}
  	return $return;
	} 
}
?>