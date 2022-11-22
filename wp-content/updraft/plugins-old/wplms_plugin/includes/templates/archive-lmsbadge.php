<?php
/**
 * Template Name: LmsBadge Archive Page
 */

if ( !defined( 'ABSPATH' ) ) exit;

if(function_exists('vibe_get_header')){
	get_header(vibe_get_header());
}else{
	get_header();
}
?>
<div class="container">
<?php
//get all badge Types
$badgeTypes = get_terms(apply_filters('lmsbadge_type_args',array(
    'taxonomy' => 'lmsbadge-type',
    'hide_empty' => false,
    'orderby' => 'meta_value_num',
    'order' => 'ASC',
    'hierarchical' => false,
    'meta_query' => [[
        'key' => 'lmsbadge_type_order',
        'type' => 'NUMERIC',
    ]],
)));


if(!empty($badgeTypes )){
    ?>
    <h3 class="all_badges_title title"><span><?php echo _x('Badge Types','Badge archive page heading','wplms');?><span></h3>
    <div class="badge_types_wrapper">
    <?php

    foreach($badgeTypes as $badgeType){
        ?>
        <div class="badge_type">
            <?php
                $thumbnail_id = absint( get_term_meta( $badgeType->term_id, 'lmsbadge_type_thumbnail_id', true ) );
                if ( $thumbnail_id ) {
                    $image = wp_get_attachment_thumb_url( $thumbnail_id );
                    echo '<img src="'.esc_url($image).'" />';
                }
            ?>
            <h3><?php echo $badgeType->name; ?></h3>
            <p><?php echo $badgeType->description; ?></p>
        </div>
        <?php
    }
    ?>
    </div>
    <div class="badgetype_badges_wrapper">
        <?php
            foreach($badgeTypes as $badgeType){
                $badge_Query = new WP_Query(apply_filters('lmsbadges_query',array(
                    'post_type' => 'lmsbadge',
                    'posts_per_page'=>-1,
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'lmsbadge-type',
                            'field'    => 'slug',
                            'terms'    => $badgeType->slug
                        ),
                    )
                )));
                if($badge_Query->have_posts()){
                    ?>
                        <div class="badgetype_badges">
                            <h3 class="all_badges_title title"><span><?php echo $badgeType->name;?><span></h3>
                            <div class="badges_wrapper">
                            <?php
                                while($badge_Query->have_posts()){
                                    $badge_Query->the_post();
                                    ?>
                                        <div class="lmsbadge">
                                            <a href="<?php echo get_the_permalink(); ?>">
                                            <div class="lmsbadge_image"><?php the_post_thumbnail(); ?></div>
                                            <div class="lmsbadge_about">
                                                <h3><?php the_title(); ?></h3>
                                                <span><?php echo get_post_meta(get_the_ID(),'subtitle',true); ?><span>
                                                <span class="lmsbadge_points">
                                                    <span class="vicon vicon-server"></span>
                                                    <span><?php echo get_post_meta(get_the_ID(),'points',true);?></span>
                                                </span>
                                            </div>
                                            </a>
                                        </div>
                                    <?php
                                }
                                wp_reset_postdata();
                            ?>
                            </div>
                        </div>
                    <?php
                }
            }
        ?>
    </div>
    </div>
    <?php
}

?>
</div>
<style>
.badge_types_wrapper { display: flex; flex-wrap: wrap; margin: -10px; } .badge_types_wrapper > * { flex: 1 0 80px; display: flex; flex-direction: column; align-items: center; margin: 10px; text-align: center; border-radius: 5px; padding: 1rem 0; } .badge_types_wrapper > * img { max-width: 128px; } h3.all_badges_title.title { display: flex; align-items: center; justify-content: center; padding: 10px; position: relative; z-index: 2; } h3.all_badges_title.title:after { content: ''; width: 100%; position: absolute; left: 0; top: 50%; display: block; background: #eee; height: 2px; z-index: -1; } h3.all_badges_title.title > span { background: #fff; padding: 0 1rem; } .badges_wrapper { display: flex; flex-wrap: wrap; margin: -10px; } .badges_wrapper > * { margin: 15px; flex: 1 0 160px; max-width: 320px; border:1px solid #eee; padding:10px; border-radius:5px; overflow:hidden; display:flex; position: relative; } .badgetype_badges_wrapper { display: flex; flex-direction: column; } .badgetype_badges { display: flex; flex-direction: column; } .lmsbadge > a{ display: flex; flex-wrap:wrap; } .lmsbadge > a >*{ margin:8px } .lmsbadge_image{ flex:1 0 64px; } .lmsbadge_image img{ border-radius:5px; } .lmsbadge_about{ flex:1 0 120px; } .lmsbadge_about h3 { margin: 0; padding: 0; font-size:1.4rem; } .lmsbadge_about span.lmsbadge_points { position: absolute; top: 0; right: 0; padding: .25rem .5rem; background: #eee; display:flex; gap:.2rem; align-items: center; justify-content: center; } .lmsbadge:hover,.badge_type:hover { box-shadow: 0 0 11px rgb(33 33 33 / 20%); transition: .2s; }
</style>
<?php
if(function_exists('vibe_get_footer')){
	get_footer(vibe_get_footer());
}else{
	get_footer();
}