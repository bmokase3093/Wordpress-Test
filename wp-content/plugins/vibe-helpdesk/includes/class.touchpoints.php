<?php

class vibe_Helpdesk_Tc {


	public static $instance;
    
    public static function init(){
        if ( is_null( self::$instance ) )
            self::$instance = new vibe_Helpdesk_Tc;
        return self::$instance;
    }

	private function __construct(){

		add_filter('vibebp_touch_points',array($this,'touch_points'));
    	add_filter('vibebp_touch_all_mails',array($this,'email_templates'),9999);


    	//add_filter('vibe_appointments_appointment_id_html',array($this,'vibe_appointments_appointment_id_html'),10,3);
		$this->run_touchpoints();

	}

	

	

	function run_touchpoints(){
		if(!defined('VIBE_BP_SETTINGS'))
			return;
		if(empty($this->vibebp_settings))
			$this->vibebp_settings = get_option(VIBE_BP_SETTINGS);
		if(!empty($this->vibebp_settings['touch']) && isset($this->vibebp_settings) && isset($this->vibebp_settings['touch']) && is_array($this->vibebp_settings['touch'])){

			foreach($this->vibebp_settings['touch'] as $key => $value){
				if(!empty($this->get_touch_points()[$key])){
					$hook = $this->get_touch_points()[$key]['vibe_helpdesk_hook'];
					if(!empty($hook)){
						
						if(!empty($value['student']['message'])){
							$student_fx = 'student_message_'.$key;
							//print_r($hook);print_r('----------');print_r($student_fx);print_r('----------');
							if(function_exists($student_fx)){
								add_action($hook,$student_fx,10,$this->get_touch_points()[$key]['params']);
							}else{
								add_action($hook,array($this,$student_fx),10,$this->get_touch_points()[$key]['params']);	
							}
						}

						if(!empty($value['student']['notification'])){
							$student_fx = 'student_notification_'.$key;
							if(function_exists($student_fx)){
								add_action($hook,$student_fx,9,$this->get_touch_points()[$key]['params']);
							}else{
								add_action($hook,array($this,$student_fx),9,$this->get_touch_points()[$key]['params']);	
							}	
						}

						if(!empty($value['student']['email'])){
							$student_fx = 'student_email_'.$key;
							if(function_exists($student_fx)){
								add_action($hook,$student_fx,10,$this->get_touch_points()[$key]['params']);
							}else{
								add_action($hook,array($this,$student_fx),10,$this->get_touch_points()[$key]['params']);	
							}
						}

						if(!empty($value['instructor']['message'])){
							$instructor_fx = 'instructor_message_'.$key;
							if(function_exists($instructor_fx)){
								add_action($hook,$instructor_fx,15,$this->get_touch_points()[$key]['params']);
							}else{
								add_action($hook,array($this,$instructor_fx),15,$this->get_touch_points()[$key]['params']);	
							}
						}

						if(!empty($value['instructor']['notification'])){
							$instructor_fx = 'instructor_notification_'.$key;
							if(function_exists($instructor_fx)){
								add_action($hook,$instructor_fx,15,$this->get_touch_points()[$key]['params']);
							}else{
								add_action($hook,array($this,$instructor_fx),15,$this->get_touch_points()[$key]['params']);	
							}
						}

						if(!empty($value['instructor']['email'])){
							$instructor_fx = 'instructor_email_'.$key;
							if(function_exists($instructor_fx)){
								add_action($hook,$instructor_fx,15,$this->get_touch_points()[$key]['params']);
							}else{
								add_action($hook,array($this,$instructor_fx),15,$this->get_touch_points()[$key]['params']);	
							}
						}

						if(!empty($value['admin']['message'])){
							$admin_fx = 'admin_message_'.$key;

							if(function_exists($admin_fx)){
								add_action($hook,$admin_fx,25,$this->get_touch_points()[$key]['params']);
							}else{
								add_action($hook,array($this,$admin_fx),25,$this->get_touch_points()[$key]['params']);	
							}
						}

						if(!empty($value['admin']['notification'])){
							$admin_fx = 'admin_notification_'.$key;
							if(function_exists($admin_fx)){

								add_action($hook,$admin_fx,25,$this->get_touch_points()[$key]['params']);
							}else{
								add_action($hook,array($this,$admin_fx),25,$this->get_touch_points()[$key]['params']);	
							}
						}

						if(!empty($value['admin']['email'])){
							$admin_fx = 'admin_email_'.$key;
							if(function_exists($admin_fx)){
								add_action($hook,$admin_fx,25,$this->get_touch_points()[$key]['params']);
							}else{
								add_action($hook,array($this,$admin_fx),25,$this->get_touch_points()[$key]['params']);	
							}
						}
					}
				}
				
				
			}
		}
	}

	function get_admins(){
		$admin_ids=array();
		if(empty($this->admin_ids)){
			$this->admin_ids = array();
		 	$user_query = new WP_User_Query( array( 'role' => 'Administrator' ,'fields' => array('ID','user_email')) );
			foreach( $user_query->results as $user){
				$admin_ids[] = array('ID' => $user->ID,'email'=> $user->user_email);
			}
			$this->admin_ids = $admin_ids;
		}

		return $this->admin_ids;
	}

	function touch_points($args){
		foreach ($this->get_touch_points() as $key => $touchpoint) {
			$args[$key] = $touchpoint;
		}
    	return $args;
    }

    function get_touch_points(){
    	$args['bbp_new_topic'] = array(
			'label' => __('Forum subscription','vibe-helpdesk'),
			'name' =>'bbp_new_topic',
			'value' => array(
				'student' => admin_url('edit.php?taxonomy=bp-email-type&term=bbp_new_topic&post_type=bp-email'),
			),
			'type' => 'touchpoint',
			'hook' => '',
			'vibe_helpdesk_hook'=>'bbp_new_topic',
			'params'=>4,
		);

    	$args['bbp_new_reply'] = array(
			'label' => __('Topic subscription','vibe-helpdesk'),
			'name' =>'bbp_new_reply',
			'value' => array(
				'student' => admin_url('edit.php?taxonomy=bp-email-type&term=bbp_new_reply&post_type=bp-email'),
			),
			'type' => 'touchpoint',
			'hook' => '',
			'vibe_helpdesk_hook'=>'bbp_new_reply',
			'params'=>5,
		);
    	return apply_filters('vibe_helpdesk_touch_points_array',$args);
    }

    function email_templates($email){
    	$email['bbp_new_topic'] = array(
	            'description'=> __('New Topic created in forum','vibe-helpdesk'),
	            'subject' =>  sprintf(__('New Topic created in forum %s','vibe-helpdesk'),'{{forum.name}}'),
	            'message' =>  sprintf(__('New Topic %s created by %s in forum %s','vibe-helpdesk'),'{{{topic.link}}}','{{user.name}}','{{{forum.link}}}')
	        );

    	$email['bbp_new_reply'] = array(
            'description'=> __('New Reply created in topic','vibe-helpdesk'),
            'subject' =>  sprintf(__('%s posted reply on topic %s','vibe-helpdesk'),'{{user.name}}','{{topic.name}}'),
            'message' =>  sprintf(__('%s posted reply on topic %s in forum %s : %s','vibe-helpdesk'),'{{user.name}}','{{{topic.link}}}','{{{forum.link}}}','{{{reply.content}}}')
        );

    	return $email;
    }

    //reply
    function student_message_bbp_new_reply($reply_id, $topic_id, $forum_id, $anonymous_data,$user_id){
    	
    	//print_r($reply_id.'----'.$topic_id.'----'.$forum_id.'----'.$user_id);
    	if(empty($this->reply_user_ids[$reply_id])){
    		$this->reply_user_ids[$reply_id] = get_post_meta($topic_id,'_bbp_subscription',false);
    		if(!empty($this->reply_user_ids[$reply_id])){
    			$this->reply_user_ids[$reply_id] = array_unique($this->reply_user_ids[$reply_id]);
    		}else{
    			$this->reply_user_ids[$reply_id]=[];
    		}
    	}
    	if(empty($this->reply_user_ids[$reply_id]))
    		return;

    	$new_user_ids = [];
    	for ($i=0; $i < count($this->reply_user_ids[$reply_id]); $i++) { 
    		if($this->reply_user_ids[$reply_id][$i]!=$user_id){
    			$new_user_ids[] = $this->reply_user_ids[$reply_id][$i];
    		}
    	}
		$this->reply_user_ids[$reply_id] = $new_user_ids;


    	$content = do_shortcode(get_post_field('post_content',$reply_id));

    	if(!empty($anonymous_data['bbp_private_reply'])){
    		$content = _x('This is a private reply','','vibe-helpdesk');
    	}

		$message = sprintf(__('%s posted reply on topic %s in forum %s : %s','vibe-helpdesk'),bp_core_get_user_displayname($user_id),'<a href="'.get_permalink($topic_id).'">'.get_the_title($topic_id).'</a>','<a href="'.get_permalink($forum_id).'">'.get_the_title($forum_id).'</a>',$content);
	    if(bp_is_active('messages') )
	      vibe_helpdesk_messages_new_message( array('sender_id' => $user_id, 'subject' => sprintf(__('%s posted reply on topic %s','vibe-helpdesk'),bp_core_get_user_displayname($user_id),get_the_title($topic_id)), 'content' => $message,   'recipients' => $this->reply_user_ids[$reply_id] ) );
    }

    function student_notification_bbp_new_reply($reply_id, $topic_id, $forum_id, $anonymous_data,$user_id){
    	if(empty($this->reply_user_ids[$reply_id])){
    		$this->reply_user_ids[$reply_id] = get_post_meta($topic_id,'_bbp_subscription',false);
    		if(!empty($this->reply_user_ids[$reply_id])){
    			$this->reply_user_ids[$reply_id] = array_unique($this->reply_user_ids[$reply_id]);
    		}else{
    			$this->reply_user_ids[$reply_id]=[];
    		}
    	}
    	if(empty($this->reply_user_ids[$reply_id]))
    		return;
    


    	foreach($this->reply_user_ids[$reply_id] as $u_id){
    		if($u_id!=$user_id){
	    		vibe_helpdesk_add_notification( array(
					'user_id'          => $u_id,
					'item_id'          => $topic_id,
					'secondary_item_id' => $user_id,
					'component_action' => 'bbp_new_reply'
				));
    		}
    	} 
    	
    }

    function student_email_bbp_new_reply($reply_id, $topic_id, $forum_id, $anonymous_data,$user_id){
    	if(empty($this->reply_user_ids[$reply_id])){
    		$this->reply_user_ids[$reply_id] = get_post_meta($topic_id,'_bbp_subscription',false);
    		if(!empty($this->reply_user_ids[$reply_id])){
    			$this->reply_user_ids[$reply_id] = array_unique($this->reply_user_ids[$reply_id]);
    		}else{
    			$this->reply_user_ids[$reply_id]=[];
    		}
    	}
    	if(empty($this->reply_user_ids[$reply_id]))
    		return;
    	

		$tos = [];
		
      	/*$tos = array(
			'user1@ma.il' => 'User 1',
			'user2@ma.il' => 'User 2',
			'user3@ma.il' => 'User 3',
		);*/ 
		foreach($this->reply_user_ids[$reply_id] as $uid){
			if($uid!=$user_id){
				$user = get_user_by('id',$uid);
				if(!empty($user) && !is_wp_error($user)){
					$tos[] = $user->data->user_email;
				}
			}
		}
		if(empty($tos))
			return;
		$content = do_shortcode(get_post_field('post_content',$reply_id));

    	if(!empty($anonymous_data['bbp_private_reply'])){
    		$content = _x('This is a private reply','','vibe-helpdesk');
    	}
		vibebp_wp_mail($tos,'','',array('action'=>'bbp_new_reply','item_id'=>$topic_id ,
			'tokens'=>array(
			'user.name'=>bp_core_get_user_displayname($user_id),
			'topic.link'=>'<a href="'.get_permalink($topic_id).'">'.get_the_title($topic_id).'</a>',
			'forum.link'=>'<a href="'.get_permalink($forum_id).'">'.get_the_title($forum_id).'</a>',
			'reply.content'=>$content,
			'topic.name'=>get_the_title($topic_id),
		)));
		
    }

    //NEW TOPIC
    //NEW TOPIC
    function student_message_bbp_new_topic($topic_id, $forum_id, $pata_nahi_ye_kya_hai, $user_id){
    	
    	
    	if(empty($this->topic_user_ids[$topic_id])){
    		$this->topic_user_ids[$topic_id] = get_post_meta($forum_id,'_bbp_subscription',false);
    		if(!empty($this->topic_user_ids[$topic_id])){
    			$this->topic_user_ids[$topic_id] = array_unique($this->topic_user_ids[$topic_id]);
    		}else{
    			$this->topic_user_ids[$topic_id]=[];
    		}
    	}
    	if(empty($this->topic_user_ids[$topic_id]))
    		return;


		$message = sprintf(__('New Topic %s created by %s in forum %s','vibe-helpdesk'),'<a href="'.get_permalink($topic_id).'">'.get_the_title($topic_id).'</a>',bp_core_get_user_displayname($user_id),'<a href="'.get_permalink($forum_id).'">'.get_the_title($forum_id).'</a>');
	    if(bp_is_active('messages') )
	      vibe_helpdesk_messages_new_message( array('sender_id' => $user_id, 'subject' => sprintf(__('New Topic created in forum %s','vibe-helpdesk'),get_the_title($forum_id)), 'content' => $message,   'recipients' => $this->topic_user_ids[$topic_id] ) );
    }

    function student_notification_bbp_new_topic($topic_id, $forum_id, $pata_nahi_ye_kya_hai, $user_id){
    	if(empty($this->topic_user_ids[$topic_id])){
    		$this->topic_user_ids[$topic_id] = get_post_meta($forum_id,'_bbp_subscription',false);
    		if(!empty($this->topic_user_ids[$topic_id])){
    			$this->topic_user_ids[$topic_id] = array_unique($this->topic_user_ids[$topic_id]);
    		}else{
    			$this->topic_user_ids[$topic_id]=[];
    		}
    	}
    	if(empty($this->topic_user_ids[$topic_id]))
    		return;

    	foreach($this->topic_user_ids[$topic_id] as $u_id){
    		vibe_helpdesk_add_notification( array(
				'user_id'          => $u_id,
				'item_id'          => $forum_id,
				'secondary_item_id' => $topic_id,
				'component_action' => 'bbp_new_topic'
			));
    	} 
    	
    }

    function student_email_bbp_new_topic($topic_id, $forum_id, $pata_nahi_ye_kya_hai, $user_id){
    	if(empty($this->topic_user_ids[$topic_id])){
    		$this->topic_user_ids[$topic_id] = get_post_meta($forum_id,'_bbp_subscription',false);
    		if(!empty($this->topic_user_ids[$topic_id])){
    			$this->topic_user_ids[$topic_id] = array_unique($this->topic_user_ids[$topic_id]);
    		}else{
    			$this->topic_user_ids[$topic_id]=[];
    		}
    	}
    	if(empty($this->topic_user_ids[$topic_id]))
    		return;


		$tos = [];
		
      	/*$tos = array(
			'user1@ma.il' => 'User 1',
			'user2@ma.il' => 'User 2',
			'user3@ma.il' => 'User 3',
		);*/ 
		foreach($this->topic_user_ids[$topic_id] as $uid){
			$user = get_user_by('id',$uid);
			if(!empty($user) && !is_wp_error($user)){
				$tos[] = $user->data->user_email;
			}
			
		}

		if(empty($tos))
			return;
		//'{{{topic.link}}}','{{user.name}}','{{{forum.link}}}'
		vibebp_wp_mail($tos,'','',array('action'=>'bbp_new_topic','item_id'=>$forum_id ,
			'tokens'=>array(
				'user.name'=>bp_core_get_user_displayname($user_id),
				'topic.link'=>'<a href="'.get_permalink($topic_id).'">'.get_the_title($topic_id).'</a>',
				'forum.link'=>'<a href="'.get_permalink($forum_id).'">'.get_the_title($forum_id).'</a>',
				'forum.name'=>get_the_title($forum_id),
			)));
		
    }

}
function bp_helpdesk_format_notifications( $action, $item_id, $secondary_item_id, $total_items, $format = 'string' ) {
		
	switch ($action) {
		case 'bbp_new_topic':
			$notification = sprintf(__('New topic %s created in forum %s','vibe-helpdesk'),'<a href="'.get_permalink($item_id).'">'.get_the_title($item_id).'</a>','<a href="'.get_permalink($secondary_item_id).'">'.get_the_title($secondary_item_id).'</a>');
		break;

		case 'bbp_new_reply':
			$notification = sprintf(__('%s posted reply on topic %s','vibe-helpdesk'),bp_core_get_user_displayname($secondary_item_id),'<a href="'.get_permalink($item_id).'">'.get_the_title($item_id).'</a>');
		break;
		

		
	}
	return $notification;
}

add_action('bp_init',function(){

	$vibe_tc = vibe_Helpdesk_Tc::init();
});


function vibe_helpdesk_add_notification($args =''){
	if ( ! bp_is_active( 'notifications' ) || !function_exists('bp_notifications_add_notification')) 
		return;
	global $bp;
	$defaults = array(
		'user_id' => $bp->loggedin_user->id,
		'item_id' => false,
		'secondary_item_id' => false,
		'component_name' => VIBE_HELPDESK_SLUG,
		'component_action'  => '',
		'date_notified'     => bp_core_current_time(),
		'is_new'            => 1,
	);

	$r = wp_parse_args( $args, $defaults );
	extract( $r );

	return  bp_notifications_add_notification( array(
		'user_id'           => $user_id,
		'item_id'           => $item_id,
		'secondary_item_id' => $secondary_item_id,
		'component_name'    => $component_name,
		'component_action'  => $component_action,
		'date_notified'     => $date_notified,
		'is_new'            => $is_new,
	) );
	
}
function vibe_helpdesk_messages_new_message($args = null){
	if(!function_exists('bp_is_active') || !bp_is_active('messages') || !function_exists('messages_new_message'))
		return;
	global $bp;
	$defaults = array(
		'sender_id' => $bp->loggedin_user->id,
		'subject' => '',
		'content' => '',
		'recipients' => '',
	);
	$r = wp_parse_args( $args, $defaults );
	extract( $r );
	return  messages_new_message( 
			array('sender_id' =>  $sender_id,
			  'subject' => $subject,
			  'content' => $content,
			  'recipients' => $recipients
			  )
		);
}