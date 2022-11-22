<?php
/**
 * Template Name: CourseLayout (No Header,Footer)
 */

if ( !defined( 'ABSPATH' ) ) exit;

if(function_exists('vibe_get_header')){
	get_header(vibe_get_header());
}else{
	get_header();
}

if ( have_posts() ) : while ( have_posts() ) : the_post();
    the_content();
endwhile;
endif;
?>
<?php
if(function_exists('vibe_get_footer')){
	get_footer(vibe_get_footer());
}else{
	get_footer();
}