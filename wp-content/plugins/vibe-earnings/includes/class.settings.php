<?php
/**
 * Settings in Admin
 *
 * @author 		VibeThemes
 * @category 	Admin
 * @package 	vibe-earnings/Includes
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Vibe_Earnings_Settings{

	public static $instance;
    
    public static function init(){

        if ( is_null( self::$instance ) )
            self::$instance = new Vibe_Earnings_Settings();
        return self::$instance;
    }

	private function __construct(){

		add_filter('vibebp_settings_tab',array($this,'tab'));
		add_filter('vibebp_settings_tabs',array($this,'setting_tab'));

		add_filter('wplms_lms_settings_tabs',array($this,'setting_tab'));
		add_filter('lms_settings_tab',array($this,'tab'));
	}

	function setting_tab($tabs){
		$tabs['commissions'] = __('Commissions','vibe-earnings');
		return $tabs;
	}

	function tab($name){
		if($name == 'commissions')
			return 'lms_commissions';
		
		return $name;
	}

	function lms_commission_payments(){
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-datepicker');
		?>
		<script>
		jQuery(document).ready(function($){
     		jQuery( ".date-picker-field" ).datepicker({
                dateFormat: "yy-mm-dd",
                numberOfMonths: 1,
                showButtonPanel: true,
            });
     	});
		</script>
		<style>
			/*==== Datepicker ===*/ /* DatePicker Container */ .ui-datepicker { width: 245px; height: auto; margin: 5px auto 0; font: 9pt Arial, sans-serif; -webkit-box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, .5); -moz-box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, .5); box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, .5); } .ui-datepicker a { text-decoration: none; } /* DatePicker Table */ .ui-datepicker table { width: 100%; } .ui-datepicker-header { background: #444; color: #e0e0e0; font-weight: bold; -webkit-box-shadow: inset 0px 1px 1px 0px rgba(250, 250, 250, 2); -moz-box-shadow: inset 0px 1px 1px 0px rgba(250, 250, 250, .2); box-shadow: inset 0px 1px 1px 0px rgba(250, 250, 250, .2); text-shadow: 1px -1px 0px #000; filter: dropshadow(color=#000, offx=1, offy=-1); line-height: 30px; border-width: 1px 0 0 0; border-style: solid; border-color: #111; } .ui-datepicker-title { text-align: center; } .ui-datepicker-prev, .ui-datepicker-next { display: inline-block; width: 30px; height: 30px; text-align: center; cursor: pointer; background-repeat: no-repeat; line-height: 600%; overflow: hidden; font-family:'dashicons'; position: relative; } .ui-datepicker-prev { float: left; background-position: center -30px; } .ui-datepicker-prev:before{ content: "\f341"; color: #FFF; font-size: 20px; position: absolute; margin-top: -20px; left: 0; } .ui-datepicker-next { float: right; background-position: center 0px; } .ui-datepicker-next:before{ content: "\f345"; color: #FFF; font-size: 20px; position: absolute; margin-top: -20px; right: 0; } .ui-datepicker thead { background-color: #f7f7f7; background-image: -moz-linear-gradient(top,  #f7f7f7 0%, #f1f1f1 100%); background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#f7f7f7), color-stop(100%,#f1f1f1)); background-image: -webkit-linear-gradient(top,  #f7f7f7 0%,#f1f1f1 100%); background-image: -o-linear-gradient(top,  #f7f7f7 0%,#f1f1f1 100%); background-image: -ms-linear-gradient(top,  #f7f7f7 0%,#f1f1f1 100%); background-image: linear-gradient(top,  #f7f7f7 0%,#f1f1f1 100%); filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f7f7f7', endColorstr='#f1f1f1',GradientType=0 ); border-bottom: 1px solid #bbb; } .ui-datepicker th { text-transform: uppercase; font-size: 6pt; padding: 5px 0; color: #666666; text-shadow: 1px 0px 0px #fff; filter: dropshadow(color=#fff, offx=1, offy=0); } .ui-datepicker tbody td { padding: 0; } .ui-datepicker tbody td:last-child { border-right: 0px; } .ui-datepicker tbody tr { border-bottom: 1px solid #bbb; } .ui-datepicker tbody tr:last-child { border-bottom: 0px; } .ui-datepicker td span, .ui-datepicker td a { display: inline-block; font-weight: bold; text-align: center; width: 28px; height: 28px; line-height: 28px; color: #666666; text-shadow: 1px 1px 0px #fff; filter: dropshadow(color=#fff, offx=1, offy=1); } .ui-datepicker-calendar .ui-state-default { background: #ededed; background: -moz-linear-gradient(top,  #ededed 0%, #dedede 100%); background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ededed), color-stop(100%,#dedede)); background: -webkit-linear-gradient(top,  #ededed 0%,#dedede 100%); background: -o-linear-gradient(top,  #ededed 0%,#dedede 100%); background: -ms-linear-gradient(top,  #ededed 0%,#dedede 100%); background: linear-gradient(top,  #ededed 0%,#dedede 100%); filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ededed', endColorstr='#dedede',GradientType=0 ); -webkit-box-shadow: inset 1px 1px 0px 0px rgba(250, 250, 250, .5); -moz-box-shadow: inset 1px 1px 0px 0px rgba(250, 250, 250, .5); box-shadow: inset 1px 1px 0px 0px rgba(250, 250, 250, .5); } .ui-datepicker-calendar .ui-state-hover { background: #f7f7f7; } .ui-datepicker-calendar .ui-state-active { background: #6eafbf; -webkit-box-shadow: inset 0px 0px 10px 0px rgba(0, 0, 0, .1); -moz-box-shadow: inset 0px 0px 10px 0px rgba(0, 0, 0, .1); box-shadow: inset 0px 0px 10px 0px rgba(0, 0, 0, .1); color: #e0e0e0; text-shadow: 0px 1px 0px #4d7a85; filter: dropshadow(color=#4d7a85, offx=0, offy=1); border: 1px solid #55838f; position: relative; margin: -1px; } .ui-datepicker-unselectable .ui-state-default { background: #f4f4f4; color: #b4b3b3; } .ui-datepicker-calendar td:first-child .ui-state-active { width: 29px; margin-left: 0; } .ui-datepicker-calendar td:last-child .ui-state-active { width: 29px; margin-right: 0; } .ui-datepicker-calendar tr:last-child .ui-state-active { height: 29px; margin-bottom: 0; } .ultable{ margin:30px 0 0; padding:0; border-top:1px solid #DDD; } .ultable li{ padding:10px 0; border-bottom:1px solid #DDD; clear:both; width:100%; display: inline-block; } .ultable li:first-child{ background:#EFEFEF; } .ultable strong, .ultable span{ display: inline-block; min-width:120px; width:20%; } .ultable strong{float:left;} .ultable span{float:right;}
		</style>
		<?php
		echo '<h3>'.__('Pay Instructor Commisions','wplms').'</h3>';
		$migrated_to_activity = apply_filters('wplms_commissions_migrate_to_activity',1);
		if(isset($_POST['set_time'])){
			$start_date=$_POST['start_date'];
			$end_date=$_POST['end_date'];
		}
		if(isset($_POST['payment_complete'])){
			$post = array();
			$post['post_title'] = sprintf(__('Commission Payments on %s','wplms'),date('Y-m-d H:i:s'));
			$post['post_status'] = 'publish';
			$post['post_type'] = 'payments';
			$post_id = wp_insert_post( $post );
			if(isset($post_id) && $post_id){
				if(!empty($_POST['instructor'])){
					$new_meta = [];
					foreach($_POST['instructor'] as $user_id => $commission){
						if(!empty($commission['set'])){


	                    	update_post_meta( $post_id, 'payout_'.$user_id, $commission['commission'] );
	                    	if(isset($commission['currency'])){
	                            update_post_meta( $post_id, 'currency_'.$user_id, $commission['currency'] );
	                        }
	                        if( $commission['commission'] ){ 
								do_action('wplms_payout_paid',$user_id,$commission['commission'],get_woocommerce_currency_symbol($commission['currency']));
								if(isset($commission['currency'])){
									$currency = $commission['currency'];

									delete_user_meta( $user_id, 'vibe_request_payouts', $currency );
								}
	                        }
	                        $new_meta[$user_id] = $commission;
                    	}
					}

					update_post_meta($post_id,'vibe_instructor_commissions',$new_meta);
				}
				update_post_meta($post_id,'vibe_date_from',$_POST['start_date']);
				update_post_meta($post_id,'vibe_date_to',$_POST['end_date']);

				echo '<div id="moderated" class="updated below-h2"><p>'.__(' Commission Payments Saved','wplms').'</p></div>';
			}else
				echo '<div id="moderated" class="error below-h2"><p>'.__('Commission payments not saved !','wplms').'</p></div>';

			
		}
		
//MANISH's CODE --- 
		global $wpdb;
		$arr = Array();
		$lists  = $wpdb->get_results("SELECT `user_id`, meta_value as currency FROM {$wpdb->usermeta} 
							WHERE `meta_key` = 'vibe_request_payouts'
							AND user_id NOT IN (
								SELECT a.ID 
								FROM {$wpdb->users} as a INNER JOIN {$wpdb->usermeta} as b 
								WHERE a.ID = b.user_id 
								AND b.meta_key = 'wp_capabilities' 
								AND b.meta_value LIKE '%\"Administrator\"%'
							)"
						, ARRAY_A);

		// print_r($lists);
		$curLists = Array();
		if(!empty($lists) && isset($lists)) {
			foreach($lists as $list) {
				$arr[$list['user_id']][] = $list['currency'];
				if(!in_array($list['currency'], $curLists)) {
					$curLists[] = $list['currency'];
				}
			}
		}

		$count  = count($arr);

		$paid_payouts = array();

		$posts=$wpdb->get_results("SELECT id FROM {$wpdb->posts} WHERE post_type = 'payments'",ARRAY_A);

        foreach($posts as $post){
        	$commission_recieved = get_post_meta($post['id'],'vibe_instructor_commissions', true);
        	if(!empty($commission_recieved)){
	        	foreach($commission_recieved as $key => $commissions){
	        		if(empty($paid_payouts[$key])){
	        			$paid_payouts[$key]=[];
	        		}
	        		if(empty($paid_payouts[$key][$commissions['currency']])){
	        			$paid_payouts[$key][$commissions['currency']]=0;
	        		}
	        		$paid_payouts[$key][$commissions['currency']] += $commissions['commission'];
	        	}
	        }
        }


		$order_data = new WPLMS_Commissions;
		$currencies = $order_data->get_currencies();

		echo "<style>
			.request_payouts .instructor_list{ 
				line-height: 30px;
			    padding: 10px 10px 10px 10px;
			    max-width: 600px;
			}

			.request_payouts .instructor_list input{
			    float: right;
			}
			.request_payouts .instructor_list span{
			    margin-left: 40px;
			}
			.instructor_info h3 label {
			    padding-left: 40px;
			}
		</style>";


		echo "<div class ='request_payouts' style='display: block; clear: both;'>";

		if(isset($_POST['threshold_btn'])){
			foreach($currencies as $value){
				$cur = $value['currency'];

				if(isset($_POST['threshold_commission_'.$cur])){
					update_option('threshold_commission_'.$cur,$_POST['threshold_commission_'.$cur]);
				}
			}
		}
		echo '<form method="POST" name="save_threshold">';

		if(!empty($currencies)){
			echo '<p><strong>'.__('Set Threshold Commissions','wplms').':</strong></p>';
			foreach ($currencies as $key => $currency) {
				$cur = $currency['currency'];
				$threshold_commission = (!empty(get_option('threshold_commission_'.$cur)))? get_option('threshold_commission_'.$cur) : 0;
		
				
				echo '<strong>'.$cur.'('.get_woocommerce_currency_symbol($cur).')'.' :</strong><input type="text" name="threshold_commission_'.$cur.'" value="'.$threshold_commission.'" ><br>';

			}
		}

		echo '<input type="submit" class="button" name="threshold_btn" value="'.__('Save','wplms').'">';
			

		echo '</form><br>'; 
		if($count) {
			echo '<div class="postbox instructor_list">';
			echo '<form method="post">';
			if($count == 1){
				echo sprintf(_x("%s instructor requested for payouts","payouts requests","vibe-customtypes"),$count);
			} else{
				echo sprintf(_x("%s instructors requested for payouts","payouts requests","vibe-customtypes"),$count);
			}

			
			if(!empty($arr)){
				foreach($arr as $user_id => $currencies){
					echo '<input type="hidden" value="'.$user_id.'" name="payout_user_ids[]" />';
				}
			}
			$select = '';
			if(!empty($curLists) && isset($curLists)) {
				$select = '<select name ="selectCurrency">';
				foreach($curLists as $currency) {
					$select .='<option value=  '.$currency.'>'.$currency.'('.get_woocommerce_currency_symbol($currency).')</option>';
				}
				$select .= '</select>';
			}

			echo '<span> Select Currency '.$select.'</span>';
			echo '<input type="submit" class="button show_request_payouts" name="show_payouts" value="'.__('Show','wplms').'"></form></div>';

		}
		
		echo "</div><br><br>";
//MANISH's CODE END ----	
		
		

		echo '<form method="POST" name="payment">';
		$posts = get_posts( array ('post_type'=>'payments', 'orderby' => 'date','order'=>'DESC', 'numberposts' => '1' ) );
		foreach($posts as $post){
			$date=$post->post_date;
			$id=$post->ID;
		}
		if(isset($date))
		echo '<strong>LAST PAYMENT : '.date("G:i | D , M j Y", strtotime($date)).'</strong> <a href="'.get_edit_post_link( $id ).'" class="small_link">'.__('CHECK NOW','wplms').'</a><br /><br />';
		if(!isset($start_date))
			$start_date =  date('Y-m-d', strtotime( date('Ym', current_time('timestamp') ) . '01' ) );
		if(!isset($end_date))
			$end_date = date('Y-m-d', current_time( 'timestamp' )+(24*60*60) );	
		if(!$migrated_to_activity){
			echo '<strong>'.__('SET TIME PERIOD','wplms').' :</strong><input type="text" name="start_date" id="from" value="'.$start_date.'" class="date-picker-field">
					 <label for="to">&nbsp;&nbsp; To:</label> 
					<input type="text" name="end_date" id="to" value="'.$end_date.'" class="date-picker-field">
					<input type="submit" class="button" name="set_time" value="Show"></p>';
		}
			
		if(!$migrated_to_activity){
			if(isset($_POST['set_time'])){	
				echo '<div class="postbox instructor_info">
						<h3><label>'.__('Instructor Name','wplms').'</label><span>'.__('Commission','wplms').' ('.get_woocommerce_currency_symbol().')</span><span>'.__('PAYPAL EMAIL','wplms').'</span><span>'.__('Select','wplms').'</span><span>'.__('Pay via PayPal','wplms').'</span></h3>
						<div class="inside">
							<ul>';

					$order_data = new WPLMS_Commissions;
					$instructor_data=$order_data->instructor_data($start_date,$end_date);
						
					$instructors = get_users(array('capability'=>'edit_posts'));
							
						foreach ($instructors as $instructor) {
							$instructor_email = $instructor->user_email;
							if(function_exists('xprofile_get_field_data')){
								$field= vibe_get_option('instructor_paypal_field');
								if( xprofile_get_field_data( $field, $instructor->ID )){
									 $instructor_email=xprofile_get_field_data( $field, $instructor->ID );
								}
							}

					        echo '<li><label>'. $instructor->user_nicename.'</label>
	                        <span><input type="number" id="'.$instructor->ID.'_amount" name="instructor['.$instructor->ID.'][commission]" class="text" value="'.(isset($instructor_data[$instructor->ID])?$instructor_data[$instructor->ID]:0).'" /></span>
	                        <span><input type="text" id="'.$instructor->ID.'_email" name="instructor['.$instructor->ID.'][email]"  value="' . $instructor_email . '" /></span>
	                        <span><input type="checkbox" name="instructor['.$instructor->ID.'][set]" class="checkbox" value="1" /></span>
	                        <span>
	                        <a id="'.$instructor->ID.'_payment" class="button">'.__('Pay via PayPal','wplms').'</a>
	                        
	                        </span>
	                        <input type="hidden" name="instructor['.$instructor->ID.'][currency]" value="'.get_woocommerce_currency().'" />
	                        <script>
	                            jQuery(document).ready(function($){
	                                $("#'.$instructor->ID.'_payment").click(function(){
	                                    var amount =$("#'.$instructor->ID.'_amount").val();
	                                    var email =$("#'.$instructor->ID.'_email").val();
	                                    $(\'<form name="_xclick" action="https://www.paypal.com/in/cgi-bin/webscr" method="post" target="_blank"><input type="hidden" name="cmd" value="_xclick"><input type="hidden" name="business" value="\'+email+\'"><input type="hidden" name="currency_code" value="'.get_woocommerce_currency().'"><input type="hidden" name="item_name" value="'.__('Instructor Commission','wplms').'"><input type="hidden" name="amount" value="\'+amount+\'"></form>\').appendTo($(this)).submit();
	                                });
	                            });
	                        </script>
	                        </li>';
						}
					
						
					   echo '</ul>
						</div>
					</div>
					<input type="submit" class="button-primary" name="payment_complete" value="'.__('Mark as Paid','wplms').'">
					
			   ';
		   }
		}else{
			
			$new_instructor_data = array();
			$currencies = array();
			$postusers = array();

			if(isset($_POST['show_payouts']) && isset($_POST['payout_user_ids'])){
				$_GET['currency'] = $_POST['selectCurrency'];
				$postusers = $_POST['payout_user_ids'];
				$start_date = '';
				$end_date = '';
			}
			if(empty($postusers) && isset( $_POST['postusers'])){
				$postusers = $_POST['postusers'];
			}

			$order_data = new WPLMS_Commissions;
			$instructor_data=$order_data->instructor_data($start_date,$end_date);
			foreach ($instructor_data as $key => $inst_data) {
				if(isset($inst_data['currency'])){
					if(empty($new_instructor_data[$inst_data['currency']])){
						$new_instructor_data[$inst_data['currency']]=[];
					}
					if(empty($new_instructor_data[$inst_data['currency']][$inst_data['user_id']])){
						$new_instructor_data[$inst_data['currency']][$inst_data['user_id']]=0;
					}
					$new_instructor_data[$inst_data['currency']][$inst_data['user_id']] += $inst_data['commission'];
				}
			}
			echo '<ul class="subsubsub">';
			$currencies = $order_data->get_currencies();
			if(empty($_GET['currency'])){
				$_GET['currency'] = '';
			}
			$current_currency = $_GET['currency'];
			
			if(isset($_POST['selectCurrency']) && isset($_POST['selectCurrency'])){
				$current_currency = $_POST['selectCurrency'];
			}
			if(!empty($currencies)){
				foreach ($currencies as $key => $currency) {
					if(empty($current_currency ) && $key==0){
						$current_currency = $currency['currency'];
					}

					if($current_currency == $currency['currency']) {
						echo '<li><span>'.$currency['currency'].' ('.get_woocommerce_currency_symbol($currency['currency']).')</span></li>  |  ';
					} else {
						echo '<li><a href="?page=vibebp_settings&tab=commissions&sub=pay&currency='.$currency['currency'].'">'.$currency['currency'].' ('.get_woocommerce_currency_symbol($currency['currency']).')</a></li>  |  ';
					}
				}
				echo  '</ul>';
			}
			

			if($migrated_to_activity){

				echo '<br><br><br><strong>'.__('SET TIME PERIOD','wplms').' :</strong><input type="text" name="start_date" id="from" value="'.$start_date.'" class="date-picker-field">
						 <label for="to">&nbsp;&nbsp; To:</label> 
						<input type="text" name="end_date" id="to" value="'.$end_date.'" class="date-picker-field">';
				foreach($postusers as $users){
					echo '<input type="hidden" value="'.$users.'" name="postusers[]" />';
				}
				if(!empty($_POST['selectCurrency'])){
					echo '<input type="hidden" value="'.$_POST['selectCurrency'].'" name="selectCurrency" />';
				}

				echo '<input type="submit" class="button" name="set_time" value="Show"></p><br>';
			}
			if(!empty($new_instructor_data) && is_array($new_instructor_data)){
				if(!empty($new_instructor_data[$current_currency])){
					$instructor_c_commissions = $new_instructor_data[$current_currency];
				}else{
					$instructor_c_commissions = [];
				}
				
				if(!empty($instructor_c_commissions)){
					echo '<div class="postbox instructor_info" style="display: block; clear: both;">
					
					<table>
					  <caption>Commission Data</caption>
					  <thead>
					    <tr>
					      <th scope="col">'.__('Instructor Name','wplms').'</th>
					      <th scope="col">'.__('Commission','wplms').' ('.get_woocommerce_currency_symbol($current_currency).')</th>
					      <th scope="col">'.__('Payouts','wplms').' ('.get_woocommerce_currency_symbol($current_currency).')</th>
					      <th scope="col">'.__('Commission to pay','wplms').' ('.get_woocommerce_currency_symbol($current_currency).')</th>
					      <th scope="col">'.__('PAYPAL EMAIL','wplms').'</th>
					      <th scope="col">'.__('Select','wplms').'</th>
					      <th scope="col">'.__('Pay via PayPal','wplms').'</th>
					    </tr>
					  </thead>
					  <tbody>';
					//loop 
					

					if(isset($postusers) &&!empty($postusers)){
						$instructors = get_users(array('include'=>$postusers));
					}else{
						$instructors = get_users(array('capability'=>'edit_posts'));
					}
					foreach ($instructors as $instructor) {
						$instructor_email ='';
						if(function_exists('xprofile_get_field_data')){
							$field= vibe_get_option('instructor_paypal_field');
							if( xprofile_get_field_data( $field, $instructor->ID )){
								 $instructor_email=xprofile_get_field_data( $field, $instructor->ID );
							}
						}
						if(empty($instructor_c_commissions[$instructor->ID])){
							$instructor_c_commissions[$instructor->ID]=0;
						}
						if(empty($paid_payouts[$instructor->ID][$current_currency])){
							$paid_payouts[$instructor->ID][$current_currency]=0;
						}
						$instpayout = $instructor_c_commissions[$instructor->ID]- $paid_payouts[$instructor->ID][$current_currency];
				        echo '<tr>
					      <td scope="row" data-label="'.__('Instructor Name','wplms').'">'. $instructor->user_nicename.'</td>

					      <td data-label="'.__('Commission','wplms').'">'.(isset($instructor_c_commissions[$instructor->ID])?$instructor_c_commissions[$instructor->ID]:0).'</td>

					      <td data-label="'.__('Payouts','wplms').'">'. (isset($paid_payouts[$instructor->ID][$current_currency])?$paid_payouts[$instructor->ID][$current_currency]:0).'</td>

					      <td data-label="'.__('Commission to pay','wplms').'"><input type="text" id="'.$instructor->ID.'_amount" name="instructor['.$instructor->ID.'][commission]" class="text" value="'.(isset($instructor_c_commissions[$instructor->ID])?($instpayout>=0)?$instpayout:0:0).'" /></td>

					      <td data-label="'.__('PAYPAL EMAIL','wplms').'"><input type="text" id="'.$instructor->ID.'_email" name="instructor['.$instructor->ID.'][email]"  value="' . $instructor_email . '" /></td>

					      <td data-label="'.__('Select','wplms').'"><input type="checkbox" name="instructor['.$instructor->ID.'][set]" class="checkbox" value="1" /></td>

					      <td data-label="'.__('Pay via PayPal','wplms').'"><a id="'.$instructor->ID.'_payment" class="button">'.__('Pay via PayPal','wplms').'</a></td>
					    </tr>





						'.wp_nonce_field("request_payout_status","request_payout_status").'

                        <input type="hidden" name="instructor['.$instructor->ID.'][currency]" value="'.$current_currency.'" />
                        <script>
                            jQuery(document).ready(function($){
                                $("#'.$instructor->ID.'_payment").click(function(){
                                    var amount =$("#'.$instructor->ID.'_amount").val();
                                    var email =$("#'.$instructor->ID.'_email").val();
                                    var $this = $(this);
                                    $.ajax({
				                      	type: "POST",
				                      	url: ajaxurl,
				                      	data: { action: "disable_payout_request", 
				                              	security: $("#request_payout_status").val(),
				                              	currency: "'.$current_currency.'",
				                              	instructor: '.$instructor->ID.',
				                              	amount: amount,
				                            	},
				                      	cache: false,
				                      	success: function (html) {
                                    		$(\'<form name="_xclick" action="https://www.paypal.com/in/cgi-bin/webscr" method="post" target="_blank"><input type="hidden" name="cmd" value="_xclick"><input type="hidden" name="business" value="\'+email+\'"><input type="hidden" name="currency_code" value="'.$current_currency.'"><input type="hidden" name="item_name" value="'.__('Instructor Commission','wplms').'"><input type="hidden" name="amount" value="\'+amount+\'"></form>\').appendTo($this).submit();
				                      	}
				                    });


                                });
                            });
                        </script>
                        ';
					}
					echo '</tbody>
					</table>';

					echo '</div>
					</div>
					<input type="submit" class="button-primary" name="payment_complete" value="'.__('Mark as Paid','wplms').'">';
				}
				
			} else {
				echo '<div class="postbox" instructor_info" style="display: block; clear: both;"><div class="inside">'.__("NO RECORD FOR SELECTED DATE","vibe-customtypes").'</div></div>';
			}
			
		}

		

		echo '</form>'; 			
	}

	function lms_commission_settings(){
		echo '<h3>'.__('Set Instructor Commisions','wplms').'</h3>';

		if(isset($_POST['set_commission'])){
			if(update_option('instructor_commissions',$_POST['commission']))
				echo '<div id="moderated" class="updated below-h2"><p>'.__('Instructor Commissions Saved','wplms').'</p></div>';
			else
				echo '<div id="moderated" class="error below-h2"><p>'.__('Instructor Commissions not saved, contact Site-Admin !','wplms').'</p></div>';
			$commission = $_POST['commission'];
		}else{
			$commission = get_option('instructor_commissions');
		} 

		$courses = get_posts('post_type=course&post_status=any&posts_per_page=-1');
		echo '<form method="POST"><div class="postbox instructor_info">
						<h3><label>'.__('Course Name','wplms').'</label><span>'.__('Instructor','wplms').'</span><span>'.__('PERCENTAGE','wplms').'</span></h3>
						<div class="inside">
							<ul>';
		foreach($courses as $course){
				$instructors=apply_filters('wplms_course_instructors',$course->post_author,$course->ID);
				$cval=array();
				if(isset($commission) && is_array($commission)){
					$instructor_commission = vibe_get_option('instructor_commission');
					if(empty($instructor_commission)){
						$instructor_commission=0;
					}
					if(isset($instructors) && is_array($instructors)){
						foreach($instructors as $k=>$instructor){
							if(!isset($commission[$course->ID][$instructor])){
								$cval[$k] = $instructor_commission;
							}else{
								$cval[$k] = $commission[$course->ID][$instructor];		
							}
							
						}
					}else{
						if(!isset($commission[$course->ID][$course->post_author])){
							$val = $instructor_commission;	
						}else{
							$val = $commission[$course->ID][$course->post_author];	
						}
					}
				}else{
					$val = 0;
				}

			 	if(isset($instructors) && is_array($instructors)){
					foreach($instructors as $k=>$instructor){
						echo '<li><label>'.$course->post_title.'</label><span>'.get_the_author_meta('display_name',$instructor).'</span><span><input type="number" name="commission['.$course->ID.']['.$instructor.']" class="small-text" value="'.(!empty($cval[$k])?$cval[$k]:0).'" /></span></li>';
					}	
				}else	
					echo '<li><label>'.$course->post_title.'</label><span>'.get_the_author_meta('display_name',$course->post_author).'</span><span><input type="number" name="commission['.$course->ID.']['.$course->post_author.']" class="small-text" value="'.$val.'" /></span></li>';
		}

		echo '</ul>
						</div>
					</div>
					<input type="submit" class="button-primary" name="set_commission" value="'.__('Set Commisions','wplms').'">
			   </form><style>.instructor_info label { width: 200px; display: inline-block; } .instructor_info span { width: 240px; display: inline-block; }</style>';
	}

}

function lms_commissions(){
	$commissions = Vibe_Earnings_Settings::init();
	include_once('commissions/wplms_commissions_class.php');

	echo '<h3>'.__('Instructor Commissions','wplms').'</h3>';
	echo '<p>'.__('Configure and pay commissions to instructors.','wplms').'</p>';

	$template_array = apply_filters('wplms_lms_commission_tabs',array(
		''=> __('Set Commissions','wplms'),
		'pay'=> __('Pay Commissions','wplms'),
		));
	echo '<ul class="subsubsub">';
	if(empty($_GET['sub'])){
		$_GET['sub']='';
	}
	foreach($template_array as $k=>$value){
		echo '<li><a href="?page=vibebp_settings&tab=commissions&sub='.$k.'" '.((!empty($_GET['sub']) && $k == $_GET['sub'])?'class="current"':'').'>'.$value.'</a> '.(($k=='template')?'':' &#124; ').' </li>';
	}
	echo '</ul><div class="clear"><hr/>';

	
	switch($_GET['sub']){
		case 'pay':
			$commissions->lms_commission_payments();
		break;
		default:
			if(function_exists('wplms_'.$_GET['sub'])){
				$fx = 'wplms_'.$_GET['sub'];
				$fx();
			}else{
				$commissions->lms_commission_settings();
			}
		break;
	}
	
	
}

Vibe_Earnings_Settings::init();

