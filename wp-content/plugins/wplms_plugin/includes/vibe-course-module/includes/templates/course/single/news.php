<?php

$course_id = get_the_id();
$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
$num = get_option('posts_per_page');
$args = array(
	'post_type' => 'news',
	'paged' => $paged,
	'post_per_page'=> $num,
  'post_status' => 'publish',
	'meta_query'=> array(
		array(
            'meta_key' => 'vibe_news_course',
            'compare' => '=',
            'value' => $course_id,
            'type' => 'numeric'
            ),
          )
	);
$news = new WP_Query($args);
global $wp_query;

echo '<h3 class="heading">'.__('Course News','wplms').'</h3>';
if(current_user_can('manage_options')){
  $key = 1;
}else if(current_user_can('edit_posts')){
  $user_id = get_current_user_id();
  $instructors = array();
  if(function_exists('get_coauthors')){
    $coauthors = get_coauthors($course_id);
    if(isset($coauthors)){
      $i = 1;
      foreach($coauthors as $k => $inst){
        $instructors[$i] = $inst->ID;
        $i++;
      }
    }
  }else{
    $instructors[] = get_the_author_meta( 'ID' );
  }
  $key = array_search($user_id,$instructors);
  if(in_array($user_id, $instructors)) $key = 1;
}else{
  $key = 0;
}

if($key){
  echo '<a class="create_news_front_end button primary-button" data-text="'.__('Cancel','wplms').'">'.__('Add News','wplms').'</a>';
  ?>
  <div id="create_news" class="hide" data-id="<?php echo $course_id; ?>">
    <div class="container-fluid">
      <div class="row">
        <ul>
          <li>
            <label><?php echo __('News Title','wplms'); ?></label>
            <input type="text" class="news_title form_field" placeholder="<?php echo __('Title','wplms'); ?>" />
          </li>
          <li>
            <label><?php echo __('News Sub-Title','wplms'); ?></label>
            <textarea class="news_sub_title form_field" name="vibe_subtitle" id="vibe_subtitle" placeholder="<?php echo __('Sub Title','wplms'); ?>"></textarea>
          </li>
          <li>
            <label><?php echo __('News Format','wplms'); ?></label>
            <select class="news_format form_field">
              <option value="post-format-0" class="post-format-standard"><?php echo __('Standard','wplms'); ?></option>
              <option value="post-format-aside" class="post-format-aside"><?php echo __('Aside','wplms'); ?></option>
              <option value="post-format-image" class="post-format-image"><?php echo __('Image','wplms'); ?></option>
              <option value="post-format-quote" class="post-format-quote"><?php echo __('Quote','wplms'); ?></option>
              <option value="post-format-status" class="post-format-status"><?php echo __('Status','wplms'); ?></option>
              <option value="post-format-video" class="post-format-video"><?php echo __('Video','wplms'); ?></option>
              <option value="post-format-audio" class="post-format-audio"><?php echo __('Audio','wplms'); ?></option>
              <option value="post-format-chat" class="post-format-chat"><?php echo __('Chat','wplms'); ?></option>
              <option value="post-format-gallery" class="post-format-gallery"><?php echo __('Gallery','wplms'); ?></option>
            </select>
          </li>
          <li>
            <label><?php echo __('News Content','wplms'); ?></label>
            <?php 
              wp_editor('','news_content',array('editor_class'=>'news_content')); 
            ?>
          </li>
        </ul>
        <a id="save_news_front_end" class="button primary-button" data-id=""><?php echo __('Add News','wplms'); ?></a>
        <a class="cancel_news_front_end button primary-button"><?php echo __('Cancel','wplms'); ?></a>
        <?php wp_nonce_field('front_end_news_vibe_'.$course_id,'news_security'); ?>
      </div>
    </div>
  </div>
  <?php
}

if($news->have_posts()){
	echo '<ul>';
  $wp_query=$news;
	while($news->have_posts()){
		$news->the_post();
		$format=get_post_format(get_the_ID());
          if(!isset($format) || !$format)
            $format = 'standard';

          echo '<li>';
          echo '<div class="'.$format.'-block news"><span class="right">'.sprintf('%02d', get_the_time('j')).' '.get_the_time('M').'\''.get_the_time('y').'</span>
                  <h4><a href="'.get_permalink().'">'.get_the_title().'</a></h4>';
            echo '<div class="news_thumb"><a href="'.get_permalink().'">'.get_the_post_thumbnail().'</a></div>';
                  the_excerpt();
            if($key){
              echo '<a class="edit_news_front_end link" data-id="'.$course_id.'" data-news="'.get_the_ID().'">'.__('Edit','wplms').'</a>';
              echo '<a class="delete_news_front_end link" data-id="'.$course_id.'" data-news="'.get_the_ID().'" data-text="'.__('Are You sure, you want to delete this news','wplms').'">'.__('Delete','wplms').'</a>';
            }
            echo '<a href="'.get_permalink().'" class="right link">'.__('Read More','wplms').'</a><ul class="tags">'.get_the_term_list(get_the_ID(),'news-tag','<li>','</li><li>','</li>').'</ul>
            </div></li>';
	}
	echo '</ul>';
   pagination();
}else{
	echo '<div class="message error">'.__('No news available for Course','wplms').'</div>';
}
wp_reset_postdata();
wp_reset_query();
?>