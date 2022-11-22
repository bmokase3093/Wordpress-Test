<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class BBP_Private_Replies_4x {

	/**
	 * The capability required to view private posts.
	 *
	 * @since 1.3.3
	 *
	 * @var string $capability
	 */
    
    public $capability = 'moderate';
    public $user = false; // for 4.x 

	/*--------------------------------------------*
	 * Constructor
	 *--------------------------------------------*/

	/**
	 * Initializes the plugin by setting localization, filters, and administration functions.
	 */
	function __construct() {

		// hide reply content
		add_filter( 'bbp_get_reply_excerpt', array( $this, 'hide_reply' ), 1000, 2 );
		add_filter( 'bbp_get_reply_content', array( $this, 'hide_reply' ), 1000, 2 );
		add_filter( 'the_excerpt', array( $this, 'hide_reply' ), 1000 );

        $this->capability = $GLOBALS['bbp_private_replies']->capability;

		
		add_filter('vibebp_api_get_user_from_token',function($user,$token){ //setting current class user only 			
			$this->user = get_userdata($user->id);
			return $user;
		},999,2);

	} // end constructor

	/**
	 * Determines if a reply is marked as private
	 *
	 * @since 1.0
	 *
	 * @param $reply_id int The ID of the reply
	 *
	 * @return bool
	 */
	public function is_private( $reply_id = 0 ) {

		$retval 	= false;

		// Checking a specific reply id
		if ( !empty( $reply_id ) ) {
			$reply     = bbp_get_reply( $reply_id );
			$reply_id = !empty( $reply ) ? $reply->ID : 0;

		// Using the global reply id
		} elseif ( bbp_get_reply_id() ) {
			$reply_id = bbp_get_reply_id();

		// Use the current post id
		} elseif ( !bbp_get_reply_id() ) {
			$reply_id = get_the_ID();
		}

		if ( ! empty( $reply_id ) ) {
			$retval = get_post_meta( $reply_id, '_bbp_reply_is_private', true );
		}

		return (bool) apply_filters( 'bbp_reply_is_private', (bool) $retval, $reply_id );
	}

	/**
	 * Hides the reply content for users that do not have permission to view it
	 *
	 * @since 1.0
	 *
	 * @param $content string The content of the reply
	 * @param $reply_id int The ID of the reply
	 *
	 * @return string
	 */
	public function hide_reply( $content = '', $reply_id = 0 ) {
        
		if( empty( $reply_id ) )
            $reply_id = bbp_get_reply_id( $reply_id );
            

        $content = get_post_field('post_content',$reply_id);

		if( $this->is_private( $reply_id ) ) {

			$can_view     = false;
			$current_user = is_user_logged_in() ? wp_get_current_user() : $this->user;
			$topic_author = bbp_get_topic_author_id();
			$reply_author = bbp_get_reply_author_id( $reply_id );


			if ( ! empty( $current_user ) && $topic_author === $current_user->ID && user_can( $reply_author, $this->capability ) ) {
				// Let the thread author view replies if the reply author is from a moderator
				$can_view = true;
			}

			if ( ! empty( $current_user ) && $reply_author === $current_user->ID ) {
				// Let the reply author view their own reply
				$can_view = true;
			}

			if( user_can( $current_user , $this->capability ) ) {
				// Let moderators view all replies
				$can_view = true;
			}

			$can_view = apply_filters('user_can_view_private_replies',$can_view,$reply_id,$current_user->ID);

			if( ! $can_view ) {
				$content = __( 'This reply has been marked as private.', 'bbp_private_replies' );
			}
		}

		return $content;
	}
} // end class

// instantiate our plugin's class
$GLOBALS['bbp_private_replies_4x'] = new BBP_Private_Replies_4x();
