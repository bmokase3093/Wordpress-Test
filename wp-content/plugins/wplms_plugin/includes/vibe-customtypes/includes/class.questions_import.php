<?php

 if ( ! defined( 'ABSPATH' ) ) exit;
class WPLMS_Questions_Import{


	function process_csv($file,$questionType,$user_id){
		global $wpdb;
		$id_map=array();

		if (($handle = fopen($file, "r")) !== FALSE) {
			$option_columns = array();

			while ( ($data = fgetcsv($handle,1000,",") ) !== FALSE ) {
		        for ($c=0; $c < count($data); $c++) {
		            if($data[$c] == 'Option' || $data[$c] == 'option'){
		            	$option_columns[]=$c;
		            }
		        }
				break;
			}

			if(stripos($questionType, 'match')){
				$match_columns = array();
				while ( ($data = fgetcsv($handle,1000,",") ) !== FALSE ) {
			        for ($c=0; $c < count($data); $c++) {
			            if($data[$c] == 'Match' || $data[$c] == 'match'){
			            	$match_columns[]=$c;
			            }
			        }
					break;
				}
			}


		    while ( ($data = fgetcsv($handle,1000,",") ) !== FALSE ) {

		    	if(stripos($questionType, 'truefalse')){
		    		
		    		$insert_post = array(
		    			'post_title'=>$data[0],
		    			'post_content'=>$data[2],
		    			'post_type'=>'question',
						'post_status'=>'publish',
						'post_author'=>$user_id,
						'tax_input'=>array(
							'question-tag'=>explode('|', $data[1])
						),
						'meta_input'=>array(
							'vibe_question_type'=>'truefalse',
							'vibe_question_options'=>array(_x('FALSE','question options','wplms'),_x('TRUE','question options','wplms')),
							'vibe_question_answer'=>($data[3] == 'TRUE'?1:0),
							'vibe_question_hint'=>$data[4],
							'vibe_question_explaination'=>$data[5],
						)
		    		);

		    		$post_id = wp_insert_post($insert_post);
		    		if(is_numeric($post_id)){
		    			wp_set_object_terms( $post_id, explode('|', $data[1]),'question-tag');
		    		}
		    	}

		    	if(stripos($questionType, 'text')){
		    		
		    		$insert_post = array(
		    			'post_title'=>sanitize_textarea_field($data[0]),
		    			'post_content'=>sanitize_textarea_field($data[2]),
		    			'post_type'=>'question',
						'post_status'=>'publish',
						'post_author'=>$user_id,
						'tax_input'=>array(
							'question-tag'=>explode('|', $data[1])
						),
						'meta_input'=>array(
							'vibe_question_type'=>'smalltext',
							'vibe_question_answer'=>sanitize_textarea_field($data[3]),
							'vibe_question_hint'=>sanitize_textarea_field($data[4]),
							'vibe_question_explaination'=>sanitize_textarea_field($data[5]),
						)
		    		);

		    		$post_id = wp_insert_post($insert_post);
		    		if(is_numeric($post_id)){
		    			wp_set_object_terms( $post_id, explode('|', $data[1]),'question-tag');
		    		}
		    	}

		    	if(stripos($questionType, 'essay')){
		    		
		    		$insert_post = array(
		    			'post_title'=>sanitize_textarea_field($data[0]),
		    			'post_content'=>sanitize_textarea_field($data[2]),
		    			'post_type'=>'question',
						'post_status'=>'publish',
						'post_author'=>$user_id,
						'tax_input'=>array(
							'question-tag'=>explode('|', $data[1])
						),
						'meta_input'=>array(
							'vibe_question_type'=>'largetext',
							'vibe_question_hint'=>sanitize_textarea_field($data[3]),
							'vibe_question_explaination'=>sanitize_textarea_field($data[5]),
						)
		    		);

		    		$post_id = wp_insert_post($insert_post);
		    		if(is_numeric($post_id)){
		    			wp_set_object_terms( $post_id, explode('|', $data[1]),'question-tag');
		    		}
		    	}

		    	if(stripos($questionType, 'mcq')){
		    		$options = array();
		    		if(!empty($option_columns)){
		    			foreach($option_columns as $col){
		    				if(!empty($data[$col]))
		    					$options[]=wp_slash($data[$col]);
		    			}
		    		}

		    		if(!empty($data) && !empty($data[2])){
			    		$insert_post = array(
			    			'post_title'=>sanitize_textarea_field($data[0]),
			    			'post_content'=>sanitize_textarea_field($data[2]),
			    			'post_type'=>'question',
							'post_status'=>'publish',
							'post_author'=>$user_id,
							'tax_input'=>array(
								'question-tag'=>explode('|', $data[1])
							),
							'meta_input'=>array(
								'vibe_question_type'=>'single',
								'vibe_question_options'=>$options,
								'vibe_question_answer'=>array_search($data[$option_columns[count($option_columns)-1]+1],$options)+1,
								'vibe_question_hint'=>!empty($data[$option_columns[count($option_columns)-1]+2])?$data[$option_columns[count($option_columns)-1]+2]:'',
								'vibe_question_explaination'=>!empty($data[$option_columns[count($option_columns)-1]+3])?$data[$option_columns[count($option_columns)-1]+3]:'',
							)
			    		);

			    		$post_id = wp_insert_post($insert_post);
			    		if(is_numeric($post_id) && !empty($data[1])){
			    			wp_set_object_terms( $post_id, explode('|', $data[1]),'question-tag');
			    		}
			    	}
		    	}

		    	if(stripos($questionType, 'mcc')){
		    		$options = array();
		    		if(!empty($option_columns)){
		    			foreach($option_columns as $col){
		    				$options[]=wp_slash($data[$col]);
		    			}
		    		}

		    		$ans = explode('|',$data[$option_columns[count($option_columns)-1]+1]);
		    		$c =  array();
		    		foreach($ans as $a){
		    			$c[]=array_search($a,$options)+1;
		    		}
		    		

		    		$insert_post = array(
		    			'post_title'=>sanitize_textarea_field($data[0]),
		    			'post_content'=>sanitize_textarea_field($data[2]),
		    			'post_type'=>'question',
						'post_status'=>'publish',
						'post_author'=>$user_id,
						'tax_input'=>array(
							'question-tag'=>explode('|', $data[1])
						),
						'meta_input'=>array(
							'vibe_question_type'=>'multiple',
							'vibe_question_options'=>$options,
							'vibe_question_answer'=>implode(',',$c),
							'vibe_question_hint'=>$data[$option_columns[count($option_columns)-1]+2],
							'vibe_question_explaination'=>$data[$option_columns[count($option_columns)-1]+3],
						)
		    		);

		    		$post_id = wp_insert_post($insert_post);
		    		if(is_numeric($post_id)){
		    			wp_set_object_terms( $post_id, explode('|', $data[1]),'question-tag');
		    		}
		    	}

		    	if(stripos($questionType, 'fill')){
		    		

		    		$insert_post = array(
		    			'post_title'=>sanitize_textarea_field($data[0]),
		    			'post_content'=>sanitize_textarea_field($data[2]),
		    			'post_type'=>'question',
						'post_status'=>'publish',
						'post_author'=>$user_id,
						'tax_input'=>array(
							'question-tag'=>explode('|', $data[1])
						),
						'meta_input'=>array(
							'vibe_question_type'=>'fillblank',
							'vibe_question_answer'=>$data[3],
							'vibe_question_hint'=>$data[4],
							'vibe_question_explaination'=>$data[5],
						)
		    		);

		    		$post_id = wp_insert_post($insert_post);
		    		if(is_numeric($post_id)){
		    			wp_set_object_terms( $post_id, explode('|', $data[1]),'question-tag');
		    		}
		    	}

		    	if(stripos($questionType, 'select')){
		    		$options = array();
		    		if(!empty($option_columns)){
		    			foreach($option_columns as $col){
		    				$options[]=wp_slash($data[$col]);
		    			}
		    		}

		    		$ans = explode('|',$data[$option_columns[count($option_columns)-1]+1]);
		    		$c =  array();
		    		foreach($ans as $a){
		    			$c[]=array_search($a,$options)+1;
		    		}
		    		

		    		$insert_post = array(
		    			'post_title'=>sanitize_textarea_field($data[0]),
		    			'post_content'=>sanitize_textarea_field($data[2]),
		    			'post_type'=>'question',
						'post_status'=>'publish',
						'post_author'=>$user_id,
						'tax_input'=>array(
							'question-tag'=>explode('|', $data[1])
						),
						'meta_input'=>array(
							'vibe_question_type'=>'select',
							'vibe_question_options'=>$options,
							'vibe_question_answer'=>implode(',',$c),
							'vibe_question_hint'=>$data[$option_columns[count($option_columns)-1]+2],
							'vibe_question_explaination'=>$data[$option_columns[count($option_columns)-1]+3],
						)
		    		);

		    		$post_id = wp_insert_post($insert_post);
		    		if(is_numeric($post_id)){
		    			wp_set_object_terms( $post_id, explode('|', $data[1]),'question-tag');
		    		}
		    	}

		    	if(stripos($questionType, 'sort')){
		    		$options = array();
		    		if(!empty($option_columns)){
		    			foreach($option_columns as $col){
		    				$options[]=wp_slash($data[$col]);
		    			}
		    		}

		    		$correct_answers = $options;
		    		shuffle($options);

		    		$c=[];
		    		foreach($correct_answers as $key=>$value){
		    			$c[]=array_search($value,$options)+1;
		    		}
		    		$insert_post = array(
		    			'post_title'=>sanitize_textarea_field($data[0]),
		    			'post_content'=>sanitize_textarea_field($data[2]),
		    			'post_type'=>'question',
						'post_status'=>'publish',
						'post_author'=>$user_id,
						'tax_input'=>array(
							'question-tag'=>explode('|', $data[1])
						),
						'meta_input'=>array(
							'vibe_question_type'=>'sort',
							'vibe_question_options'=>$options,
							'vibe_question_answer'=>implode(',',$c),
							'vibe_question_hint'=>$data[$option_columns[count($option_columns)-1]+2],
							'vibe_question_explaination'=>$data[$option_columns[count($option_columns)-1]+3],
						)
		    		);

		    		$post_id = wp_insert_post($insert_post);
		    		if(is_numeric($post_id)){
		    			wp_set_object_terms( $post_id, explode('|', $data[1]),'question-tag');
		    		}
		    	}

		    	if(stripos($questionType, 'match')){
		    		
		    		$options = array();
		    		if(!empty($match_columns)){
		    			$data[2].='[match]<ul>';
		    			foreach($match_columns as $col){
		    				$data[2].='<li>'.wp_slash($data[$col]).'</li>';
		    			}
		    			$data[2].='</ul>[/match]';
		    		}

		    		if(!empty($option_columns)){
		    			foreach($option_columns as $col){
		    				$options[]=wp_slash($data[$col]);
		    			}
		    		}

		    		$correct_answers = $options;
		    		shuffle($options);

		    		$c=[];
		    		foreach($correct_answers as $key=>$value){
		    			$c[]=array_search($value,$options)+1;
		    		}
		    		$insert_post = array(
		    			'post_title'=>sanitize_textarea_field($data[0]),
		    			'post_content'=>sanitize_textarea_field($data[2]),
		    			'post_type'=>'question',
						'post_status'=>'publish',
						'post_author'=>$user_id,
						'tax_input'=>array(
							'question-tag'=>explode('|', $data[1])
						),
						'meta_input'=>array(
							'vibe_question_type'=>'match',
							'vibe_question_options'=>$options,
							'vibe_question_answer'=>implode(',',$c),
							'vibe_question_hint'=>$data[$option_columns[count($option_columns)-1]+2],
							'vibe_question_explaination'=>$data[$option_columns[count($option_columns)-1]+3],
						)
		    		);

		    		
		    		$post_id = wp_insert_post($insert_post);
		    		if(is_numeric($post_id)){
		    			wp_set_object_terms( $post_id, explode('|', $data[1]),'question-tag');
		    		}
		    	}
		    	
		    }
		    fclose($handle);
		}

		return 1;
	}
}