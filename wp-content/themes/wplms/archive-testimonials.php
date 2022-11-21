<?php
if ( ! defined( 'ABSPATH' ) ) exit;
get_header(vibe_get_header());
?>
<section id="title">
    <?php do_action('wplms_before_title'); ?>
    <div class="<?php echo vibe_get_container(); ?>">
        <div class="row">
            <div class="col-md-12">
                <div class="pagetitle">
                    <?php vibe_breadcrumbs(); ?>  
                    <h1><?php

                    if(is_month()){
                        single_month_title(' ');
                    }elseif(is_year()){
                        echo get_the_time('Y');
                    }else if(is_category()){
                        echo single_cat_title();
                    }else if(is_tag()){
                         single_tag_title();
                    }else if(is_tax()){
                        single_term_title();
                    }else{
                        post_type_archive_title();
                    }
                     ?></h1>
                    <h5><?php echo term_description(); ?></h5>
                </div>
            </div>
        </div>
    </div>
</section>
<section id="content">
	<div class="<?php echo vibe_get_container(); ?>">
        <div class="row">
            <div class="col-md-8">
            <?php

                if ( have_posts() ) : while ( have_posts() ) : the_post();
            ?>
            <br>
    		<div class="testimonial">
                <div class="testimonial_content">
                    <?php
                        the_excerpt();
                     ?>
                     <a href="<?php echo get_the_permalink(); ?>" class="link"><?php _e('Read more','vibe');?></a>
                </div>
                <div class="testimonial_author">
                    <?php
                        if(has_post_thumbnail()){
                            echo get_the_post_thumbnail();    
                        }else{
                            echo get_avatar( 'email@example.com', 96 );
                        }
                        
                        echo '<h4>'.get_post_meta(get_the_ID(),'vibe_testimonial_author_name',true).'
                        <span>'.get_post_meta(get_the_ID(),'vibe_testimonial_author_designation',true).'</span></h4>';
                    ?>
                </div>
            </div>
            <br><br>
            <hr />
            <?php
            
                endwhile;
                endif;
                pagination();
            ?>
            </div>
            <div class="col-md-4">
                <div class="sidebar">
                    <?php
                    $sidebar = apply_filters('wplms_sidebar','mainsidebar',get_the_ID());
                    if ( !function_exists('dynamic_sidebar')|| !dynamic_sidebar($sidebar) ) : ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

	</div>
</section>

<?php
get_footer(vibe_get_footer());
?>