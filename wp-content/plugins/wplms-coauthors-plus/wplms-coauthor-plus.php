<?php
/**
 * Plugin Name: WPLMS CoAuthor Plus Add-On
 * Plugin URI: http://www.vibethemes.com/
 * Description: Integrates CoAuthor Plus with WPLMS
 * Author: VibeThemes
 * Version: 4.0
 * Author URI: https://vibethemes.com/
 * License: GNU AGPLv3
 * License URI: http://www.gnu.org/licenses/agpl-3.0.html
 */
/* ===== INTEGRATION with WP Coauthor plugin =========
 *==============================================*/
if ( !defined( 'ABSPATH' ) ) exit;

//require_once( dirname( __FILE__ ) . '/../co-authors-plus/co-authors-plus.php' );
class WPLMS_Coauthors_Plus { 
  private $version = 1.0;

  public function __construct(){
    
    if($this->meet_requirements()){
      $this->init();
    }
  }

  function meet_requirements(){
     if ( class_exists('CoAuthors_Plus'))
        return true;
      else
        return false;
  }

  function init(){
    add_filter('wplms_display_course_instructor',array($this,'wplms_coauthor_plus_instructor'),10,2);
    add_filter('wplms_course_instructors',array($this,'wplms_coauthor_plus_course_instructor'),10,2);
    add_filter('wplms_dashboard_courses_instructors',array($this,'wplms_dashboard_instructors_courses'),10,2);
    add_filter('wplms_count_user_posts_by_type',array($this,'wplms_count_user_posts_by_type'),10,3);
  }


    /*

    */
    function wplms_count_user_posts_by_type($count,$user_id,$post_type='course' ){
        if(empty($user_id) || empty($post_type))
            return $count;

        $user = get_userdata( $user_id );
        //TAXONOMY terms taxonomy author 

        $args = apply_filters('wplms_count_user_posts_by_type_args',array(
            'post_type'=>$post_type,
            'author_name'=>$user->user_nicename,
            'posts_per_page'=>-1,
            'post_status'=>'publish'
        ));
        $query = new WP_Query($args);
        return $query->post_count;

    }


  function wplms_coauthor_plus_instructor($instructor, $id,$r = null){

    if ( function_exists('get_coauthors')) {
      $coauthors = get_coauthors( $id );
      $instructor ='';
      foreach($coauthors as $k=>$inst){
        $instructor_id = $inst->ID;
        $displayname = bp_core_get_user_displayname($instructor_id);
        if(function_exists('vibe_get_option')) {
          $field = vibe_get_option('instructor_field');
          if(!isset($field) || $field =='') $field='Speciality';
        }


        $special='';
        if(bp_is_active('xprofile')) {
          $special = bp_get_profile_field_data('field='.$field.'&user_id='.$instructor_id);
          if(empty($special)) $special = '';
          if(is_array($special)) $special = $special[0];
        }
        $r = array('item_id'=>$instructor_id,'object'=>'user');
        $instructor .= '<div class="instructor_course"><div class="item-avatar">'.bp_core_fetch_avatar( $r ).'</div>';
        $instructor .= '<h5 class="course_instructor"><a href="'.bp_core_get_user_domain($instructor_id) .'">'.$displayname.'<span>'.$special.'</span></a></h5>';
        $instructor .= apply_filters('wplms_instructor_meta','',$instructor_id,$r);
        $instructor .=  '</div>';

      }
    }
    return $instructor;
   }

   public function wplms_coauthor_plus_course_instructor($authors,$post_id){
     if ( function_exists('get_coauthors')) {
        $coauthors = get_coauthors( $post_id );
        if(isset($coauthors) && is_array($coauthors)){
          $authors=array();
          foreach($coauthors as $author){
            if(!in_array($author->ID,$authors))
              $authors[]=$author->ID;
          }
        }
    }
    return $authors;
  }
  function wplms_dashboard_instructors_courses($query,$user_id=0){
    if(!isset($user_id) || !is_numeric($user_id) || !$user_id)
      $user_id=get_current_user_id();

    global $wpdb;
    $user_info = get_userdata($user_id);
    $s='cap-'.$user_info->user_nicename;
    $query = $wpdb->prepare("SELECT posts.ID as course_id
                            FROM {$wpdb->posts} AS posts
                            LEFT JOIN {$wpdb->term_relationships} txr ON posts.ID = txr.object_id
                            LEFT JOIN {$wpdb->term_taxonomy} tx ON txr.term_taxonomy_id = tx.term_taxonomy_id
                            LEFT JOIN {$wpdb->terms} trm ON tx.term_id = trm.term_id
                            WHERE (tx.taxonomy= 'author' AND trm.slug LIKE '%s')
                            AND posts.post_status = 'publish'
                            AND posts.post_type = 'course'
                            GROUP BY posts.ID
                            ORDER BY posts.post_date DESC",$s);
    return $query;
  }
}


add_action('init','wplms_coauthors_plus_function');
function wplms_coauthors_plus_function(){
  if(class_exists('WPLMS_Coauthors_Plus')){
    $wplms_events = new WPLMS_Coauthors_Plus();
  }
}

?>