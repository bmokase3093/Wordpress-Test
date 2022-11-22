<?php
/**
 * Initfor WPLMS 4
 *
 * @author      VibeThemes
 * @category    Admin
 * @package     WPLMS Plugin
 * @version     4.0
 */

 if ( ! defined( 'ABSPATH' ) ) exit;

class Wplms_Upload_Course{

    public static $instance;
    public static function init(){
    if ( is_null( self::$instance ) )
        self::$instance = new Wplms_Upload_Course();

        return self::$instance;
    }

    private function __construct(){
    	add_filter('bp_get_course_check_course_complete_stop', array($this,'check_upload'),10,3);
		
    }

    function check_upload($flag,$course_id,$user_id){
		$upload_course = get_post_meta($course_id,'vibe_course_package',true);
		$cc = get_post_meta($course_id,'vibe_course_curriculum',true);
		if(empty($cc) && !empty($upload_course)){
	    	$flag =  $this->check_course($course_id,$user_id);
		}
		return $flag;
  	}

    function check_course($course_id,$user_id){
                  
                
        $id = $course_id;
        $upload_course = get_post_meta($course_id,'vibe_course_package',true);
        $flag = 0;
        $course_curriculum = array();
        $flag = apply_filters('wplms_finish_course_check_upload_course',$flag,$course_curriculum,$course_id);

        if(!$flag){
        	$return_array = array('status'=>1);
          $course_id = $id;
          $auto_eval = get_post_meta($id,'vibe_course_auto_eval',true);
          

          if(vibe_validate($auto_eval)){

            // AUTO EVALUATION
            $return_array['course_status']=4;
            
            do_action('wplms_submit_course',$post->ID,$user_id);
            // Apply Filters on Auto Evaluation
            $u_marks = get_post_meta($id,$user_id,true);
            if(empty($u_marks)){
              $u_marks=100;
            }
            $u_marks = intval($u_marks);


            $student_marks=apply_filters('wplms_course_student_marks',$u_marks,$id,$user_id);
            $total_marks=apply_filters('wplms_course_maximum_marks',100,$id,$user_id);

            if(!$total_marks){$total_marks=$student_marks=1; }// Avoid the Division by Zero Error

            $marks = round(($student_marks*100)/$total_marks);
            $return_array['score'] = $student_marks;
			$return_array['total_score'] = $total_marks;
			$return_array['percentage'] = $marks;
			$return_array['title'] = __('COURSE EVALUATED ','wplms');
			$return_array['awards'] = array();
            $return .='<div class="message" class="updated"><p>'.__('COURSE EVALUATED ','wplms').'</p></div>';

            $badge_per = get_post_meta($id,'vibe_course_badge_percentage',true);

            $passing_cert = get_post_meta($id,'vibe_course_certificate',true); // Certificate Enable
            $passing_per = get_post_meta($id,'vibe_course_passing_percentage',true); // Certificate Passing Percentage

            //finish bit for student 1.8.4
            update_user_meta($user_id,'course_status'.$id,3);
            //end finish bit
            
              
              
              $badge_filter = 0;
            if(isset($badge_per) && $badge_per && $marks >= $badge_per)
                $badge_filter = 1;
      
              $badge_filter = apply_filters('wplms_course_student_badge_check',$badge_filter,$course_id,$user_id,$marks,$badge_per);
              if($badge_filter){  
                  $badges = array();
                  $badges= vibe_sanitize(get_user_meta($user_id,'badges',false));

                  if(isset($badges) && is_array($badges)){
                    if(!in_array($id,$badges)){
                      $badges[]=$id;
                    }
                  }else{
                    $badges=array($id);
                  }

                  update_user_meta($user_id,'badges',$badges);

                  $b=bp_get_course_badge($id);
                    if(!empty($b) && is_array($b) && !empty($b['url'])){
            			$badge_url = array();
            			$badge_url[]=$b['url'];
            		}
            		if(empty($badge_url)){
            			$badge=wplms_plugin_wp_get_attachment_info($b); 
	            		$size = apply_filters('bp_course_badge_thumbnail_size','thumbnail');
	            		$badge_url=wp_get_attachment_image_src($b,$size);
            		}
            		$b_title = get_post_meta($id,'vibe_course_badge_title',true);

            		$return_array['awards']['badge']=array('url'=>$badge_url[0],'title'=>$b_title);
                    if(isset($badge) && is_numeric($b))
                    $return .='<div class="congrats_badge">'.__('Congratulations ! You\'ve earned the ','wplms').' <strong>'.get_post_meta($id,'vibe_course_badge_title',true).'</strong> '.__('Badge','wplms').'<a class="tip ajax-badge" data-course="'.get_the_title($id).'" title="'.get_post_meta($id,'vibe_course_badge_title',true).'"><img src="'.$badge_url[0].'" title="'.$badge['title'].'"/></a></div>';
                  

                  do_action('wplms_badge_earned',$id,$badges,$user_id,$badge_filter);
              }
              $passing_filter =0;
              if(vibe_validate($passing_cert) && isset($passing_per) && $passing_per && $marks >= $passing_per)
                $passing_filter = 1;

              $passing_filter = apply_filters('wplms_course_student_certificate_check',$passing_filter,$course_id,$user_id,$marks,$passing_per);
              
              if($passing_filter){
                  $pass = array();
                  $pass=vibe_sanitize(get_user_meta($user_id,'certificates',false));
                  
                  if(isset($pass) && is_array($pass)){
                    if(!in_array($id,$pass)){
                      $pass[]=$id;
                    }
                  }else{
                    $pass=array($id);
                  }

                  update_user_meta($user_id,'certificates',$pass);
                  $return_array['awards']['certificate']=array('url'=>bp_get_course_certificate(array('user_id'=>$user_id,'course_id'=>$id)));
                  $return .='<div class="congrats_certificate">'.__('Congratulations ! You\'ve successfully passed the course and earned the Course Completion Certificate !','wplms').'<a href="'.bp_get_course_certificate(array('user_id'=>$user_id,'course_id'=>$id)).'" class="ajax-certificate right '.apply_filters('bp_course_certificate_class','',array('course_id'=>$id,'user_id'=>$user_id)).'" data-user="'.$user_id.'" data-course="'.$id.'"><span>'.__('View Certificate','wplms').'</span></a></div>';
                  do_action('wplms_certificate_earned',$id,$pass,$user_id,$passing_filter);
              }

              update_post_meta( $id,$user_id,$marks);

              $course_end_status = apply_filters('wplms_course_status',4);  
            update_user_meta( $user_id,'course_status'.$id,$course_end_status);//EXCEPTION  

              $message = sprintf(__('You\'ve obtained %s in course %s ','wplms'),apply_filters('wplms_course_marks',$marks.'/100',$course_id),' <a href="'.get_permalink($id).'">'.get_the_title($id).'</a>'); 
              $return_array['message']=$message;
              $return .='<div class="congrats_message">'.$message.'</div>';

              do_action('wplms_evaluate_course',$id,$marks,$user_id,1);

          }else{
          	$return_array['course_status']=3;
			 	$return_array['title']= __('Course submitted for Evaluation.','wplms');
			 	$return_array['message']= __('Course submitted for Evaluation.','wplms');
            $return .='<div class="message" class="updated"><p>'.__('COURSE SUBMITTED FOR EVALUATION','wplms').'</p></div>';
            bp_course_update_user_course_status($user_id,$id,2);// 2 determines Course is Complete
            do_action('wplms_submit_course',$post->ID,$user_id);
          }
          
          // Show the Generic Course Submission
          $content=get_post_meta($id,'vibe_course_message',true);
          $return .=apply_filters('the_content',$content);
          $return = apply_filters('wplms_course_finished',$return);
          $return_array['post_message'] = do_shortcode($content);
        }else{
          $type=bp_course_get_post_type($flag);
          switch($type){
            case 'unit':
            $type= __('UNIT','wplms');
            break;
            case 'assignment':
            $type= __('ASSIGNMENT','wplms');
            break;
            case 'quiz':
            $type= __('QUIZ','wplms');
            break;
          }//Default for other customized options
          $return_array = array('status'=>0,'message'=>apply_filters('wplms_unfinished_unit_quiz_message',$message,$flag));
          $message = __('PLEASE COMPLETE THE ','wplms').$type.' : <a href="'.get_permalink($flag).'">'.get_the_title($flag).'</a>';
          $return .='<div class="message"><p>'.apply_filters('wplms_unfinished_unit_quiz_message',$message,$flag).'</p></div>';
        }
        return $return_array;
    }

      
		
		

}


Wplms_Upload_Course::init();
			