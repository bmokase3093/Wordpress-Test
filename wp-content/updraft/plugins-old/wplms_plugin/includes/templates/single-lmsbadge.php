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

$count = 0;
global $wpdb,$bp;
$count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(a.user_id) from {$bp->activity->table_name} as a WHERE item_id = %d",get_the_ID()));

?>
<div class="container">
<?php
  
    ?>
        <div class="single_lmsbadge">
           <div class="lmsbadge_image">
                <?php the_post_thumbnail(); ?>
            </div>
            <div class="lmsbadge_about">
                <h3><?php the_title(); ?></h3>
                <span> <?php echo get_post_meta(get_the_ID(),'subtitle',true); ?> <span>
                <div class="lmsbadge_meta_wrapper">
                    <span class="lmsbadge_meta">
                        <span class="vicon vicon-server"></span>
                        <?php echo get_post_meta(get_the_ID(),'points',true);?>
                    </span>
                    <span class="lmsbadge_meta">
                        <span class="vicon vicon-user"></span>
                        <?php echo $count;?>
                    </span>
                </div>
                <div class="lmsbadge_types">
                    <?php
                        $badgeTypes = wp_get_object_terms(get_the_ID(),'lmsbadge-type');
                        if(!empty($badgeTypes) && is_array($badgeTypes)){
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
                                    <span><?php echo $badgeType->name; ?></span>
                                </div>
                                <?php
                            }
                        }
                    ?>
                </div>
            </div>
        </div>
        <div class="single_lmsbadge_content">
            <?php
                the_content();
            ?>
        </div>
    <?php
?>
</div>
<style>
.single_lmsbadge { background: #eee; padding: 1rem; border-radius: 5px; display: flex; margin: -8px; flex-wrap: wrap; margin: 15px 0; } .single_lmsbadge > * { flex: 1 0 120px; margin: 8px; } .lmsbadge_about >h1 { margin: 0 0 1rem; } .lmsbadge_about .lmsbadge_meta_wrapper { display: flex; margin: 10px -5px; flex-wrap: wrap; } .lmsbadge_about > span > * { flex: 1 0 10px; } .lmsbadge_about .lmsbadge_meta_wrapper > * { flex:  1; margin: 5px; } .lmsbadge_types { display: flex; align-items: center; flex-wrap: wrap; gap: 1rem; } .lmsbadge_types >.badge_type { display: flex; gap: 1rem; justify-content: center; border: 1px solid var(--darkborder); padding: .5rem; color: #ffff; background: var(--blue); border-radius: .5rem; } .lmsbadge_types .badge_type>img { width: 2rem; height: 2rem; }
</style>

<?php
if(function_exists('vibe_get_footer')){
	get_footer(vibe_get_footer());
}else{
	get_footer();
}