<?php


 if ( ! defined( 'ABSPATH' ) ) exit;
 
function meta_box_find_field_type( $needle, $haystack ) {
    foreach ( $haystack as $item )
        if ( $item['type'] == $needle )
            return true;
    return false;
}

class custom_add_meta_box {
	
	var $id; // string meta box id
	var $title; // string title
	var $fields; // array fields
	var $page; // string|array post type to add meta box to
	var $js; // bool including javascript or not
	
    public function __construct( $id, $title, $fields, $page, $js ) {
		$this->id = $id;
		$this->title = $title;
		$this->fields = $fields;
		$this->page = $page;
		$this->js = $js;
		
		if(!is_array($this->page)) {
			$this->page = array($this->page);
		}
		
        add_action( 'admin_print_scripts-post-new.php',  array( $this, 'admin_enqueue_scripts' ),100 ); 
        add_action( 'admin_print_scripts-post.php',  array( $this, 'admin_enqueue_scripts' ),100);

		add_action( 'admin_enqueue_scripts', array( $this, 'persistent_admin_scripts' ),10,1);
		add_action( 'admin_head',  array( $this, 'admin_head' ) );
		add_action( 'admin_menu', array( $this, 'add_box' ) );
		add_action( 'save_post',  array( $this, 'save_box' ),999);
    }
	
	function admin_enqueue_scripts() {

		$post_type = get_post_type();

		$vc_post_types = apply_filters('vibe_custom_types_enqueue_scripts',array('course','quiz','unit','news','wplms-assignment','wplms-event','question','post','testimonial','popup','certificate','dwqa-question','forum','download'));

		if(in_array($post_type,$vc_post_types)){

	        wp_enqueue_script( 'jquery-ui-datepicker', array( 'jquery', 'jquery-ui-core' ) );
			wp_enqueue_script( 'jquery-ui-slider', array( 'jquery', 'jquery-ui-core' ) );
			wp_enqueue_script( 'timepicker_box', WPLMS_PLUGIN_INCLUDES_URL . '/vibe-customtypes/metaboxes/js/jquery.timePicker.min.js', array( 'jquery' ) );
			wp_enqueue_script( 'graph_box', WPLMS_PLUGIN_INCLUDES_URL . '/vibe-customtypes/metaboxes/js/jquery.flot.min.js', array( 'jquery' ) );
			wp_enqueue_script( 'graph_resize_box', WPLMS_PLUGIN_INCLUDES_URL . '/vibe-customtypes/metaboxes/js/jquery.flot.resize.min.js', array( 'jquery' ) );
	        wp_register_style( 'jqueryui', WPLMS_PLUGIN_INCLUDES_URL . '/vibe-customtypes/metaboxes/css/jqueryui.css' );

	        wp_deregister_script('badgeos-select2');
	        wp_dequeue_script('badgeos-select2');
	        wp_deregister_script('select2');
	        wp_dequeue_script('select2');
	        wp_enqueue_style( 'select2', WPLMS_PLUGIN_INCLUDES_URL .'/vibe-customtypes/metaboxes/css/select2.min.css');
			wp_enqueue_script( 'select2', WPLMS_PLUGIN_INCLUDES_URL .'/vibe-customtypes/metaboxes/js/select2.min.js');
			
		}
	}
	
	function persistent_admin_scripts($hook){

		if(in_array($hook,array('lms_page_lms-settings','post-new.php','post.php','lms_page_lms-stats'))){
			wp_enqueue_script( 'meta_box_js', WPLMS_PLUGIN_INCLUDES_URL . '/vibe-customtypes/metaboxes/js/scripts.js', array( 'jquery','jquery-migrate','iris','jquery-ui-core','jquery-ui-sortable','jquery-ui-slider','jquery-ui-datepicker'),WPLMS_PLUGIN_VERSION );
			wp_enqueue_media();
			wp_enqueue_style( 'meta_box_css', WPLMS_PLUGIN_INCLUDES_URL . '/vibe-customtypes/metaboxes/css/meta_box.css',array(),WPLMS_PLUGIN_VERSION);
		}
		if($hook == 'term.php'){
			wp_enqueue_media();
		}
	}
	
	// scripts
	function admin_head() {
		global $post, $post_type;
		$hook = get_current_screen();

		if (in_array($post_type, $this->page) && $this->js == true && $hook->base == 'post') { 

			echo '<script type="text/javascript">
						jQuery(function() {';
			if(is_array($this->fields))
			foreach ( $this->fields as $field ) {
				// date
				if( $field['type'] == 'date' )
					echo 'jQuery("#' . $field['id'] . '").datepicker({
							dateFormat: \'yy-mm-dd\'});';
				// slider
				if ( $field['type'] == 'slider' ) {
					$value = get_post_meta( $post->ID, $field['id'], true );
					if ( $value == '' ) $value = $field['min'];
					echo 'jQuery( "#' . $field['id'] . '-slider" ).slider({
								value: ' . $value . ',
								min: ' . $field['min'] . ',
								max: ' . $field['max'] . ',
								step: ' . $field['step'] . ',
								slide: function( event, ui ) {
									jQuery( "#' . $field['id'] . '" ).val( ui.value );
								}
							});';
				}
                    if ( $field['type'] == 'gmap' ) {

                    	if(function_exists('vibe_get_option')){
                    		$zoom=vibe_get_option('zoom_level');
                    		$contact_ll=vibe_get_option('contact_ll');
                    		$ll = explode(',',$contact_ll);
                    		if(isset($ll) && is_array($ll)){
                    			$lat=$ll[0];
                        		$lng=$ll[1];
                    		}
                    	}
                                $value = get_post_meta( $post->ID, $field['id'], true );	

								if(isset($value['latitude']) && $value['latitude'])
									$lat= $value['latitude'];
								else
								  (!(isset($lat) && $lat)?$lat = '+37.0625' :'');
								 
								if(isset($value['longitude']) && $value['longitude'])
									$lng= $value['longitude'];
								else	
									((!isset($lng) && $lng)?$lng = '-95.677068' :'');
                                
                                if(!isset($zoom))
									$zoom= 17;

                                    echo "if(typeof google != 'undefined'){
									var geocoder = new google.maps.Geocoder();

									function geocodePosition(pos) {
									  geocoder.geocode({
									    latLng: pos
									  }, function(responses) {
									    if (responses && responses.length > 0) {
									        var address = '',city = '', state = '', zip = '', country = '', formattedAddress = '';
									                     for (var i = 0; i < responses[0].address_components.length; i++) {
									                          var addr = responses[0].address_components[i];
									                          // check if this entry in address_components has a type of country
									                          if (addr.types[0] == 'country'){
									                              document.getElementById('country').value = addr.long_name;
									                              country = addr.long_name;
									                          }else if (addr.types[0] == 'postal_code'){       // Zip
									                              document.getElementById('pincode').value = addr.short_name;
									                              zip = addr.long_name;
									                          }else if (addr.types[0] == ['administrative_area_level_1']){       // State
									                              document.getElementById('state').value = addr.long_name;
									                              state = addr.long_name;
									                          }else if (addr.types[0] == ['locality']){       // City
									                              document.getElementById('city').value = addr.long_name;
									                              city = addr.long_name;
									                              }
									                      }
									                      
									      var staddr=responses[0].formatted_address;
									      staddr=staddr.replace(country,'');staddr=staddr.replace(zip,'');staddr=staddr.replace(city,'');staddr=staddr.replace(state,'');
									      staddr=staddr.replace(', ,','');
									      document.getElementById('staddress').value = staddr;                
									      updateMarkerAddress(responses[0].formatted_address);
									    } else {
									      updateMarkerAddress('Cannot determine address at this location.');
									    }
									  });
									}

									function updateMarkerStatus(str) { 
									}

									function updateMarkerPosition(latLng) {

									  document.getElementById('latitude').value = latLng.lat();
									  document.getElementById('longitude').value =  latLng.lng();

									}

									function updateMarkerAddress(str) {
									    if(str != null)
									    document.getElementById('address').innerHTML = str;
									}

									function initialize() {
									var lat;var lng;
									lat = $lat;
									lng = $lng;



									  var latLng = new google.maps.LatLng(lat,lng);
									  var map = new google.maps.Map(document.getElementById('mapCanvas'), {
									    zoom: ".$zoom.",
									    center: latLng,
									    mapTypeId: google.maps.MapTypeId.ROADMAP
									  });
									  var marker = new google.maps.Marker({
									    position: latLng,
									    title: 'Property',
									    map: map,
									    draggable: true
									  });
									  
									  // Update current position info.
									  updateMarkerPosition(latLng);
									  geocodePosition(latLng);
									  
									  // Add dragging event listeners.
									  google.maps.event.addListener(marker, 'dragstart', function() {
									    updateMarkerAddress('Dragging...');
									  });
									  
									  google.maps.event.addListener(marker, 'drag', function() {
									    updateMarkerStatus('Dragging...');
									    updateMarkerPosition(marker.getPosition());
									  });
									  
									  google.maps.event.addListener(marker, 'dragend', function() {
									    updateMarkerStatus('Drag ended');
									    geocodePosition(marker.getPosition());
									    
									  });
									}
									    
									// Onload handler to fire off the app.
									google.maps.event.addDomListener(window, 'load', initialize);
									}";
                                }
			}
			
			echo '});
				</script>';
		};
	}
	
	function add_box() {
		foreach ($this->page as $page) {
			add_meta_box( $this->id, $this->title, array( $this, 'meta_box_callback' ), $page, 'normal', 'high');
		}
	}
	
	function meta_box_callback() {
		global $post, $post_type;
		// Use nonce for verification
		echo '<input type="hidden" name="' . $post_type . '_meta_box_nonce" value="' . wp_create_nonce( basename( __FILE__) ) . '" />';
		
		// Begin the field table and loop
		echo '<table class="form-table meta_box">';
		if(is_array($this->fields))
		foreach ( $this->fields as $field) {
			
			// get data for this field
			extract( $field );
			if ( !empty( $desc ) )
				$desc = '<span class="description">' . $desc . '</span>';
				
			// get value of this field if it exists for this post

			$meta = apply_filters('wplms_meta_box_meta_value',get_post_meta( $post->ID, $id, true),$post->ID,$id);
			

			if(function_exists('vibe_get_option')){
				$cache_duration = vibe_get_option('cache_duration');
				if(!isset($cache_duration) || !$cache_duration)
					$cache_duration = 0;
			}
			
			// begin a table row with
			echo '<tr>
					<th><label for="' . $id . '">' . $label . '</label></th>
					<td>';
					switch( $type ) {

                        case 'number':
                        	if($meta == '' || !isset($meta)){ $meta = $std; }
							echo '<input type="number" name="' . $id . '" id="' . $id . '" value="' . esc_attr( $meta ) . '" size="20" />
									<br />' . $desc;
						break;
						// text
						case 'text':
							echo '<input type="text" name="' . $id . '" id="' . $id . '" value="' . esc_attr( $meta ) . '" size="20" />
									<br />' . $desc;
						break;
						case 'duration':
							echo '<select name="' . $id . '" id="' . $id . '" class="select">';
                            if($meta == '' || !isset($meta)){$meta=$std;}
                            $options = array(
                            		array('value' =>'1','label'=>__('Seconds','wplms')),
                            		array('value' =>'60','label'=>__('Minutes','wplms')),
									array('value' =>'3600','label'=>__('Hours','wplms')),
									array('value' =>'86400','label'=>__('Days','wplms')),
									array('value' =>'604800','label'=>__('Weeks','wplms')),
									array('value' =>'2592000','label'=>__('Months','wplms')),
									array('value' =>'31536000','label'=>__('Years','
										vibe-customtypes'))
                            	);
							foreach ( $options as $option )
								echo '<option' . selected( esc_attr( $meta ), $option['value'], false ) . ' value="' . $option['value'] . '">' . $option['label'] . '</option>';
							echo '</select><br />' . $desc;
						
						break;
						// textarea
						case 'textarea':
							echo '<textarea name="' . $id . '" id="' . $id . '" cols="60" rows="4">' . esc_attr( $meta ) . '</textarea>
									<br />' . $desc;
						break;
						// editor
						case 'editor':
							wp_editor(  $meta, $id , array(
							        'wpautop' => true,
							        'media_buttons' => true,
							        'teeny' => true,
							        'textarea_rows' => '4',
							        'textarea_cols' => '30',
							        'tinymce' => array(
							                        'theme_advanced_buttons1' => 'save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,cut,copy,paste,pastetext,pasteword,',
							                        'theme_advanced_buttons2' => "styleselect,formatselect,fontselect,fontsizeselect,",
							                        'theme_advanced_buttons3' => ",bullist,numlist,|,outdent,indent,blockquote,|,link,anchor,image,|,insertdate,forecolor,backcolor,|,tablecontrols,|,hr,|,fullscreen",
							                        'theme_advanced_buttons4' => "",
							                        'theme_advanced_text_colors' => '0f3156,636466,0486d3',
							                    ),
							        'quicktags' => array(
							            'buttons' => 'b,i,ul,ol,li,link,close'
							        )
							    ) );
							echo '<br />' . $desc;
						break;
						case 'faicons':
							echo "<ul class='the-icons unstyled'><li><i class='fa fa-var-venus-mars'></i><span class='i-name'>fa fa-var-venus-mars</span></li></ul>";
							if($meta == '' || !isset($meta)){$meta=$std;}
						   	echo '<input type="text" class="capture-input vibe-form-text vibe-input" name="' . $id . '" id="' . $id . '" value="' . esc_attr( $meta ) . '" />' . "\n";
							echo $desc;
						break;
						case 'icon':
							echo '<ul class="the-icons unstyled"><li></li></ul>';
						   if($meta == '' || !isset($meta)){$meta=$std;}
						   	echo '<input type="text" class="capture-input vibe-form-text vibe-input" name="' . $id . '" id="' . $id . '" value="' . esc_attr( $meta ) . '" />' . "\n";
							echo $desc;
						break;
                                                // color
                        case 'color':
							echo '<input type="text" name="' . $id . '" id="' . $id . '" value="' . esc_attr( $meta ) . '" size="10" class="color" />
									<br />' . $desc;
						break;  
						case 'checkbox':
                                                       if(!isset($std))$std=0;
                                                        if(!isset($meta)){$meta=$std;}
							echo '<div class="checkbox_button"></div>
                                                              <input type="checkbox" name="' . $id . '" id="' . $id . '" ' . checked( esc_attr( $meta ), 1, 0 ) . ' class="checkbox_val" value="1" />
								<label for="' . $id . '">' . $desc . '</label>';
						break;
						case 'showhide':
						case 'yesno':

							echo '<div class="select_button yesno">';
							foreach ( $options as $key => $option ){
								echo '<span>'.$option['label'].'</span>';
							}
							echo '</div>';
                            echo '<select name="' . $id . '" id="' . $id . '" class="select_val">';
                            
                            if($meta == '' || !isset($meta)){$meta=$std;}

							foreach ( $options as $key => $option ){
								echo '<option ' . selected( esc_attr( $meta ), $option['value'], false ) . ' value="' .$option['value'] . '">' .  $option['label'] . '</option>';
							}
							echo '</select><br />' . $desc;
						break;
						// select
						case 'select':
							echo '<select name="' . $id . '" id="' . $id . '" class="select">';
                            if($meta == '' || !isset($meta)){$meta=$std;}
							foreach ( $options as $option )
								echo '<option' . selected( esc_attr( $meta ), $option['value'], false ) . ' value="' . $option['value'] . '">' . $option['label'] . '</option>';
							echo '</select><br />' . $desc;
						break;
						// select
						case 'selectcpt':
							echo '<select name="' . $id . '" id="' . $id . '" data-id="'.$post->ID.'" data-placeholder="'.sprintf(__('Select a %s','wplms'),$post_type).'" data-cpt="'.$post_type.'" class="selectcpt">';

                            if($meta == '' || !isset($meta)){$meta=$std;}

							if(!empty($meta)){
								echo '<option value="' . $meta . '" selected="selected">' . get_the_title($meta) . '</option>';
							}
							echo '</select><br />' . $desc;
						break;
						
						case 'selectmulticpt': 
							echo '<select name="' . $id . '[]" id="' . $id . '" data-id="'.$post->ID.'" class="selectcpt" data-cpt="'.$post_type.'" data-placeholder="'.sprintf(__('Select multiple %s','wplms'),$post_type).'" multiple>';
                            if($meta == '' || !isset($meta)){$meta=$std;}
                            if(is_array($meta)){
                            	foreach($meta as $id){
                            		echo '<option value="' . $id . '" selected="selected">' . get_the_title($id) . '</option>';		
                            	}
                            }
							echo '</select><br />' . $desc;
						break;
                       	// Multiselect
						case 'multiselect':
							echo '<select name="' . $id . '[]" id="' . $id . '" multiple class="select2-select">';
                                                        if($meta == '' || !isset($meta)){$meta=array();}
							if(isset($options) && is_array($options))                                                        
							foreach ( $options as $option )
								echo '<option value="' . $option['value'] . '" '.(in_array($option['value'],$meta)?'SELECTED':'').'>' . $option['label'] . '</option>';
							echo '</select><br />' . $desc;
						break;
						// radio
						case 'radio':
							foreach ( $options as $option )
								echo '<input type="radio" name="' . $id . '" id="' . $id . '-' . $option['value'] . '" value="' . $option['value'] . '" ' . checked( esc_attr( $meta ), $option['value'], false ) . ' />
										<label for="' . $id . '-' . $option['value'] . '">' . $option['label'] . '</label><br />';
							echo '' . $desc;
						break;
                        case 'radio_img': 
                            if($meta == '' || !isset($meta)){$meta=$std;}
							foreach ( $options as $option )
								echo '<div class="radio-image-wrapper">
                                                                        <label for="' . $option['value'] . '">
                                                                            <img src="'.$option['image'].'">
                                                                            <div class="select '.((esc_attr( $meta ) == $option['value'])?"selected":"").'"></div>
                                                                        </label>
                                                                        <input type="radio" class="radio_img" name="' . $id . '" id="' . $id . '-' . $option['value'] . '" value="' . $option['value'] . '" ' . checked( esc_attr( $meta ), $option['value'], false ) . ' />
                                                                     </div>';
							echo '' . $desc;
						break;
						// checkbox_group
						case 'checkbox_group':
							foreach ( $options as $option )
								echo '<input type="checkbox" value="' . $option['value'] . '" name="' . $id . '[]" id="' . $id . '-' . $option['value'] . '"' , is_array( $meta ) && in_array( $option['value'], $meta ) ? ' checked="checked"' : '' , ' /> 
										<label for="' . $id . '-' . $option['value'] . '">' . $option['label'] . '</label><br />';
							echo '' . $desc;
						break;
						case 'date':
							echo '<input type="text" class="datepicker" name="' . $id . '" id="' . $id . '" value="' . esc_attr( $meta ) . '" size="30" />
									<br />' . $desc;
						break;
						case 'time':
							echo '<input type="text" class="timepicker" name="' . $id . '" id="' . $id . '" value="' . esc_attr( $meta ) . '" size="30" />
									<br />' . $desc;
						break;
						case 'gmap':
                            if(is_admin())
                    			wp_enqueue_script( 'meta_box-gmap','http://maps.google.com/maps/api/js?sensor=false');            
                                                   
                                                    $city = $state = 'New York';$country = 'United States'; $pincode='22005';$staddress ='';
                                                       if(isset($meta)){
                                                           if(isset($meta['latitude']))
                                                              $lat =  esc_attr( $meta['latitude'] );
                                                           if(isset($meta['latitude']))     
                                                               $long =  esc_attr( $meta['longitude'] );
                                                           if(isset($meta['staddress']))     
                                                               $staddress =  esc_attr( $meta['staddress'] );
                                                           if(isset($meta['city']))     
                                                               $city =  esc_attr( $meta['city'] );
                                                           if(isset($meta['state']))     
                                                               $state =  esc_attr( $meta['state'] );
                                                           if(isset($meta['pincode']))     
                                                               $pincode =  esc_attr( $meta['pincode'] );
                                                           if(isset($meta['country']))     
                                                               $country =  esc_attr( $meta['country'] );
                                                           
                                                       }
							echo '<div id="mapCanvas"></div>
                                                                    <div id="infoPanel">
                                                                    <h4>Current position:</h4>
                                                                    <div class="markerStatus"></div>
                                                                                <label  style="display:block;width:200px;float:left;">'.__('Latitude','wplms').'</label><input type="text" class="text" id="latitude" name="' . $field['id'] . '[latitude] value="' . $lat . '" size="20"  />
                                                                                <label  style="display:block;width:200px;float:left;">'.__('Longitude','wplms').'</label><input type="text" class="text" id="longitude" name="' . $field['id'] . '[longitude]" value="' . $long . '" size="20"  />     
                                                                                <br /><b  style="width:200px;float:left;">'.__('Closest Matching Address','wplms').'</b>
                                                                                <div id="address"></div>    
                                                                                <br />
                                                                                <label style="width:200px;float:left;">'.__('Street Address','wplms').'</label><input type="text" class="text" id="staddress" name="' . $field['id'] . '[staddress]" value="' . $staddress . '" size="20"  />     <br />
                                                                                <label style="width:200px;float:left;">'.__('City','wplms').'</label><input type="text" class="text" id="city" name="' . $field['id'] . '[city]" value="' . $city . '" size="20"  />     <br />
                                                                                <label style="width:200px;float:left;">'.__('State','wplms').'</label><input type="text" class="text" id="state" name="' . $field['id'] . '[state]" value="' . $state . '" size="20"  />     <br />
                                                                                <label style="width:200px;float:left;">'.__('Zip/Pin Code','wplms').'</label><input type="text" class="text" id="pincode" name="' . $field['id'] . '[pincode]" value="' . $pincode . '" size="20"  />     <br />
                                                                                <label style="width:200px;float:left;">'.__('Country','wplms').'</label><input type="text" class="text" id="country" name="' . $field['id'] . '[country]" value="' . $country . '" size="20"  />         <br />
                                                                              </div>
									<br />' . $desc;
						break;
						case 'groups':

						if(class_exists('BP_Groups_Group')){
							
							echo '<select name="' . $id . '" id="' . $id . '" class="selectgroup" data-placeholder="'.__('Select a group','wplms').'">';
							
							if(!empty($meta)){
								$group = groups_get_group( array( 'group_id' => $meta ) );
								echo '<option value="'.$meta.'" selected="SELECTED">'.$group->name.'</option>';
							}	

							echo '</select>';
						}else{
							_e('Buddypress Groups is not Active','wplms');
						}
						
						
						echo '<br />' . $desc;
						break;
						case 'selectmultigroups':
						    if(class_exists('BP_Groups_Group')){
						        echo '<select name="' . $id . '[]" id="' . $id . '" class="select select2-select" data-placeholder="'.__('Select groups','wplms').'" multiple>';
						        if($meta == '' || !isset($meta)){$meta=$std;}
						        $vgroups =  groups_get_groups(array(
						        'type'=>'alphabetical',
						        'per_page'=>999,
						        'show_hidden'=>true
						        ));
						        foreach($vgroups['groups'] as $vgroup){
						            if(is_array($meta))
						                echo '<option  '.(in_array($vgroup->id,$meta)?'SELECTED':'').' value="' . $vgroup->id . '">' . $vgroup->name . '</option>';
						            else
						                echo '<option value="' . $vgroup->id . '" '.selected($vgroup->id,$meta).'>' . $vgroup->name . '</option>';
						        }
						        echo '</select>';
						    }else{
						        _e('Buddypress Groups is not Active','wplms');
						    }
						    echo '<br />' . $desc;
						break; 
						case 'curriculum':

							echo '<a class="meta_box_add_section button-primary button-large" href="#">'.__('Add Section','wplms').'</a>
									<a class="meta_box_add_posttype1 button-primary button-large" href="#">Add '.$post_type1.'</a>
									<a class="meta_box_add_posttype2 button button-primary button-large" href="#">Add '.$post_type2.'</a>
									<a class="meta_box_add_posttype3 button button-primary button-large" href="#">Add '.$post_type3.'</a>
									<ul id="' . $field['id'] . '-repeatable" class="meta_box_repeatable">';
							$i = 0;
							if ( $meta ) {
								foreach( $meta as $row ) {
									echo '<li><span class="sort handle dashicons dashicons-sort"></span>
												 <input type="text" name="' . $field['id'] . '[' . $i . ']" id="' . $field['id'] . '" class="'.(is_numeric($row)?'small postid':'').'" value="' . esc_attr( $row ) . '" size="30" READONLY /> <a href="'.get_edit_post_link($row).'"><span>'.(is_numeric($row)?get_the_title($row):'').'</span></a>
												<a class="meta_box_repeatable_remove" href="#"><span class="dashicons dashicons-no"></span></a></li>';
									$i++;
								}
							} 
							echo '<li class="section hide"><span class="sort handle dashicons dashicons-sort"></span>
										<input type="text" rel-name="' . $field['id'] . '[]" id="' . $field['id'] . '" value="" size="30" />
										<a class="meta_box_repeatable_remove" href="#"><span class="dashicons dashicons-no"></span></a></li>';

							echo '<li class="posttype1 hide">
									<select rel-name="' . $field['id'] . '[]"  data-id="'.$post->ID.'" class="" data-cpt="'. $post_type1.'" data-placeholder="'.sprintf(__('Select a %s','wplms'),$post_type1).'">
									</select>';
							echo '<a class="meta_box_repeatable_remove" href="#"><span class="dashicons dashicons-no"></span></a></li>';											
							echo '<li class="posttype2 hide">
									<select rel-name="' . $field['id'] . '[]" class=""  data-id="'.$post->ID.'" data-cpt="'. $post_type2.'" data-placeholder="'.sprintf(__('Select a %s','wplms'),$post_type2).'">
									</select>
									<a class="meta_box_repeatable_remove" href="#"><span class="dashicons dashicons-no"></span></a></li>';	
							echo '<li class="posttype3 hide">
									<select rel-name="' . $field['id'] . '[]" class=""  data-id="'.$post->ID.'" data-cpt="'. $post_type3.'" data-placeholder="'.sprintf(__('Select a %s','wplms'),$post_type3).'">
									</select>
									<a class="meta_box_repeatable_remove" href="#"><span class="dashicons dashicons-no"></span></a></li>';											
							echo '</ul>
								<span class="description">' . $field['desc'] . '</span>';
						break;
                                            
                        case 'tax_select':
							echo '<select name="' . $id . '" id="' . $id . '" class="select2-select">
									<option value="">'.__('Select ','wplms').'</option>'; // Select One
							$terms = get_terms( $id, 'get=all' );
							$selected = wp_get_object_terms( $post->ID, $id );
							foreach ( $terms as $term ) 
									echo '<option value="' . $term->slug . '"' . selected( $selected[0]->slug, $term->slug, false ) . '>' . $term->name . '</option>'; 
							$taxonomy = get_taxonomy( $id);
							echo '</select> &nbsp;<span class="description"><a href="' . home_url() . '/wp-admin/edit-tags.php?taxonomy=' . $id . '&post_type=' . $post_type . '">Manage ' . $taxonomy->label . '</a></span>
								<br />' . $desc;
						break;
						case 'dynamic_taxonomy':
							echo '<select name="' . $id . '[]" id="' . $id . '" multiple class="select2-select">
									<option value="">'.__('Select Taxonomy','wplms').'</option>'; // Select One
							$terms = get_terms( $taxonomy, array('fields' => 'id=>name') );
							if($meta == '' || !isset($meta)){$meta=array();}

							if(isset($terms) && is_array($terms))                                                        
							foreach ($terms as $key=>$term )
								echo '<option value="' . $key . '" '.(in_array($key,$meta)?'SELECTED':'').'>' . $term . '</option>';
							echo '</select><br />' . $desc;
						break;
						case 'dynamic_quiz_questions':
							if($meta == '' || !isset($meta)){$meta=array();}
							$terms = get_terms($taxonomy);
							$terms_array  = array();
							if(!empty($terms)){
								foreach($terms as $term){
									$terms_array[$term->term_id] = array('id'=>$term->term_id,'name'=>$term->name,'count'=>$term->count);
								}
							}
							echo '<a class="meta_box_question_tags_add button button-primary button-large" href="#">'.__('Add More','wplms').'</a>
									<ul id="' . $field['id'] . '-repeatable" class="meta_box_repeatable">';
							$total_marks=0;

							if ( $meta ) {
								if(!empty($meta)){
									if(!isset($meta['tags'])){
										$newmeta = array();
										$newmeta['tags'][] = $meta;
										$newmeta['number'][] = get_post_meta($post->ID,'vibe_quiz_number_questions',true);
										$newmeta['marks'][] = get_post_meta($post->ID,'vibe_quiz_marks_per_question',true);
										$meta = $newmeta;
									}
								}
								if(!empty($meta['tags']) && !empty($meta['numbers'])){
									$tags = $meta['tags'];
									$numbers = $meta['numbers'];
									$marks = $meta['marks'];
									foreach( $tags as $i=>$tag ) {

										if(is_string($tag))
											$tag = unserialize($tag);

										if(!isset($numbers[$i]) || !$numbers[$i]) $numbers[$i]=0;

										if(!isset($marks[$i]) || !$marks[$i]){
											$marks[$i]=get_post_meta($post->ID,'vibe_quiz_marks_per_question',true);
											if(empty($marks[$i])){$marks[$i]=0;}
										} 
										$total_number = $total_number+intval(esc_attr( $numbers[$i] ));
										$total_marks = $total_marks+intval(esc_attr( $marks[$i] ))*intval(esc_attr( $numbers[$i] ));
										$tags_string ='';

										if(is_array($tag) && !empty($terms_array)){
											foreach($tag as $t){
												$tags_string .= $terms_array[$t]['name'].' ('.$terms_array[$t]['count'].')&nbsp;,&nbsp;'	;
											}
										}else{
											$tags_string .= $terms_array[$tag]['name'].' ('.$terms_array[$tag]['count'].')&nbsp;,&nbsp;'	;
										}
										
										echo '<li><span class="sort handle dashicons dashicons-sort"></span>';

										if(is_array($tag)){
											foreach($tag as $tid => $t){
												echo '<input type="hidden" name="' . $field['id'] . '[tags]['.$i.'][]" value="'.(isset($t['id'])?$t['id']:(is_numeric($t)?$t:'')).'" />';	
											}
										}else{
											echo '<input type="hidden" name="' . $field['id'] . '[tags]['.$i.'][]" value="'. $tag.'" />';
										}
										echo '<strong>'.$tags_string. '</strong>
											  	<input type="number" name="' . $field['id'] . '[numbers]['.$i.']" placeholder="'.__('Number of questions to pull','wplms').'" class="count" value="'.esc_attr( $numbers[$i] ).'"/>
											  	<input type="number" name="' . $field['id'] . '[marks]['.$i.']" placeholder="'.__('Marks for pulled questions','wplms').'" class="marks" value="'.esc_attr( $marks[$i] ).'"/>
											  	<a class="meta_box_repeatable_remove" href="#"><span class="dashicons dashicons-no"></span></a>
												</li>';
									}
								}
							} 
							echo '<li class="hide">
									<select rel-name="' . $field['id'] . '[tags]" multiple data-placeholder="'.__('Select Tag','wplms').'">';
									if(!empty($terms_array)){
										foreach($terms_array as $term){
											echo '<option value="'.$term['id'].'">'.$term['name'].' ('.$term['count'].')</option>';
										}
									}
								  echo '</select>
								 <input type="number" class="count" rel-name="' . $field['id'] . '[numbers]" placeholder="'.__('Number of questions to pull','wplms').'" value="0" />
								 <input type="number" class="marks" rel-name="' . $field['id'] . '[marks]" placeholder="'.__('Marks per question','wplms').'" value="0" /> 
								 <a class="meta_box_repeatable_remove" href="#"><span class="dashicons dashicons-no"></span></a></li>';
							echo '</ul>

							    <strong>'.__('Total Question Count :','wplms').' <span id="total_question_number"> '.(empty($total_number)?0:$total_number).'</span> , '.__('Total Marks :','wplms').' <span id="total_question_marks"> '.$total_marks.'</span></strong><br />
								<span class="description">' . $field['desc'] . '</span>';
						break;
						// slider
						case 'slider':
						$value = $meta != '' ? intval( $meta ) : '0';
							echo '<div id="' . $id . '-slider"></div>
									<input type="text" name="' . $id . '" id="' . $id . '" value="' . $value . '" size="5" />
									<br />' . $desc;
						break;

						// image
						case 'image':
							$image = WPLMS_PLUGIN_INCLUDES_URL.'/vibe-customtypes/metaboxes/images/image.png';	
							echo '<span class="meta_box_default_image" style="display:none">' . $image . '</span>';
                            if ( $meta ) {
								$image = wp_get_attachment_image_src( intval( $meta ), 'full' );
								$image = $image[0];
							}else
                                $meta='';
							echo	'<input name="' . $id . '" id="'.$id.'" type="hidden" class="meta_box_upload_image" value="' . intval( $meta ) . '" />
								<img src="' . $image . '" id="image_'.$id.'" class="meta_box_preview_image" alt="" /><br />
								<input class="meta_box_upload_image_button button" type="button" rel="' . $post->ID . '" data-title="'.$label.'" data-save="#'.$id.'" data-target="#image_'.$id.'" value="'.__('Choose Image','wplms').'" />
								<small>&nbsp;<a href="#" class="meta_box_clear_image_button">'.__('Remove Image','wplms').'</a></small>
								<br clear="all" />' . $desc;
						break;
						// repeatable
						case 'questions_repeatable':
							echo '<a class="meta_box_repeatable_add button button-primary button-large" href="#">'.__('Add Question','wplms').'</a>
									<ul id="' . $field['id'] . '-repeatable" class="meta_box_repeatable">';
							$i = 0;
							if ( $meta ) {
								foreach( $meta as $row ) {
									$user_info = get_userdata($row);
									echo '<li><span class="sort handle dashicons dashicons-sort"></span>
												<input type="text" name="' . $field['id'] . '[]" id="' . $field['id'] . $i .'" value="' . esc_attr( $row ) . '" size="30" /><span>'. $user_info->user_login.'</span>
												<a class="meta_box_repeatable_remove" href="#"><span class="dashicons dashicons-no"></span></a></li>';
									$i++;
								}
							} 
								echo '<li class="hide"><span class="sort handle dashicons dashicons-sort"></span>
										<input type="text" rel-name="' . $field['id'] . '[question][]" id="' . $field['id'] .$i .'" value="" placeholder="'.__('Type Question','wplms').'" size="30" />
										<input type="text" rel-name="' . $field['id'] . '[option][]" id="' . $field['id'] .$i .'" value="" placeholder="'.__('Type Question','wplms').'" size="30" />
										<a class="meta_box_repeatable_remove" href="#"><span class="dashicons dashicons-no"></span></a></li>';
							
							echo '</ul>
								<span class="description">' . $field['desc'] . '</span>';
						break;
						case 'user_repeatable':
							echo '<a class="meta_box_repeatable_add button button-primary button-large" href="#">'.__('Add More','wplms').'</a>
									<ul id="' . $field['id'] . '-repeatable" class="meta_box_repeatable">';
							$i = 0;
							if ( $meta ) {
								foreach( $meta as $row ) {
									$user_info = get_userdata($row);
									echo '<li><span class="sort handle dashicons dashicons-sort"></span>
												<input type="text" name="' . $field['id'] . '[]" id="' . $field['id'] . $i .'" value="' . esc_attr( $row ) . '" size="30" /><span>'. $user_info->user_login.'</span>
												<a class="meta_box_repeatable_remove" href="#"><span class="dashicons dashicons-no"></span></a></li>';
									$i++;
								}
							} 
								echo '<li class="hide"><span class="sort handle dashicons dashicons-sort"></span>
											<input type="text" rel-name="' . $field['id'] . '[]" id="' . $field['id'] .$i .'" value="" size="30" />
											<a class="meta_box_repeatable_remove" href="#"><span class="dashicons dashicons-no"></span></a></li>';
							
							echo '</ul>
								<span class="description">' . $field['desc'] . '</span>';
						break;

						case 'repeatable':
							echo '<a class="meta_box_repeatable_add button button-primary button-large" href="#">'.__('Add More','wplms').'</a>
									<ul id="' . $field['id'] . '-repeatable" class="meta_box_repeatable">';
	
							if ( $meta ) {
								foreach( $meta as $row ) {
									echo '<li><span class="sort handle dashicons dashicons-sort"></span>
												<input type="text" name="' . $field['id'] . '[]" id="' . $field['id'] . '" value="' . esc_attr( $row ) . '" size="30" />
												<a class="meta_box_repeatable_remove" href="#"><span class="dashicons dashicons-no"></span></a></li>';
								}
							} 
								echo '<li class="hide"><span class="sort handle dashicons dashicons-sort"></span>
											<input type="text" rel-name="' . $field['id'] . '[]" id="' . $field['id'] . '" value="" size="30" />
											<a class="meta_box_repeatable_remove" href="#"><span class="dashicons dashicons-no"></span></a></li>';
							
							echo '</ul>
								<span class="description">' . $field['desc'] . '</span>';
						break;
						case 'repeatable_count':
							echo '<a class="meta_box_repeatable_add button button-primary button-large" href="#">'.__('Add More','wplms').'</a>
									<ul id="' . $field['id'] . '-repeatable" class="meta_box_repeatable">';
							$i=1;
							
								echo '<li class="hide"><span class="sort handle dashicons dashicons-sort"></span><span class="count">'.$i.'</span>
											<input type="text" rel-name="' . $field['id'] . '[]" id="' . $field['id'] . '" value="" size="30" />
											<a class="meta_box_repeatable_remove" href="#"><span class="dashicons dashicons-no"></span></a></li>';
							
							if ( !empty($meta) && is_array($meta) ) {
								foreach( $meta as $row ) {
									echo '<li><span class="sort handle dashicons dashicons-sort"></span><span class="count">'.$i.'</span>
												<input type="text" name="' . $field['id'] . '[]" id="' . $field['id'] . '" value="' . esc_attr( $row ) . '" size="30" />
												<a class="meta_box_repeatable_remove" href="#"><span class="dashicons dashicons-no"></span></a></li>';
								$i++;}
							} 
							echo '</ul>
								<span class="description">' . $field['desc'] . '</span>';
						break;
						// repeatable
						case 'repeatable_select':
							echo '<a class="meta_box_repeatable_add button button-primary button-large" href="#">'.__('Add More','wplms').'</a>
									<ul id="' . $field['id'] . '-repeatable" class="meta_box_repeatable">';
							$i = 0;
							if ( $meta ) {

								foreach( $meta as $row ) {

									echo '<li><span class="sort handle dashicons dashicons-sort"></span>
											  <select name="' . $field['id'] . '[' . $i . ']" id="' . $field['id'] . '">';
											  foreach ( $options as $option )
													echo '<option value="' . $option['value'] . '" '.selected($option['value'],esc_attr( $row )).'>' . $option['label'] . '</option>';

											  echo '</select>
												<a class="meta_box_repeatable_remove" href="#"><span class="dashicons dashicons-no"></span></a>
												</li>';
									$i++;
								}
							} 
								echo '<li  class="hide"><span class="sort handle dashicons dashicons-sort"></span>
											<select name="' . $field['id'] . '[' . $i . ']" id="' . $field['id'] . '">';
											  foreach ( $options as $option )
													echo '<option value="' . $option['value'] . '">' . $option['label'] . '</option>';

											  echo '</select>
											<a class="meta_box_repeatable_remove" href="#"><span class="dashicons dashicons-no"></span></a></li>';
							
							echo '</ul>
								<span class="description">' . $field['desc'] . '</span>';
						break;
						case 'repeatable_selectcpt':
							echo '<a class="meta_box_repeatable_add button button-primary button-large" href="#">'.__('Add More','wplms').'</a>
									<ul id="' . $field['id'] . '-repeatable" class="meta_box_repeatable">';
							$total_marks=0;
							if ( $meta ) {
								
								if(!empty($meta['ques']) && !empty($meta['marks'])){
									$quess = $meta['ques'];
									$marks = $meta['marks'];
									
									foreach( $quess as $i => $ques ) {
										if(!isset($marks[$i]) || !$marks[$i]) $marks[$i]=0;
										$total_marks = $total_marks+intval(esc_attr( $marks[$i] ));
										echo '<li><span class="sort handle dashicons dashicons-sort"></span>
												<input type="hidden" name="' . $field['id'] . '[ques][]" value="'. $ques.'" />
												<strong>'.get_the_title($ques). '</strong>
											  	<input type="number" name="' . $field['id'] . '[marks][]" placeholder="'.__('Marks for a Correct answer','wplms').'" value="'.esc_attr( $marks[$i] ).'"/>
											  	<a class="meta_box_repeatable_remove" href="#"><span class="dashicons dashicons-no"></span></a>
												</li>';
									}
								}
							} 
							echo '<li class="hide">
									<select rel-name="' . $field['id'] . '[ques][]"  data-id="'.$post->ID.'" data-cpt="'.$post_type.'" data-placeholder="'.__('Select','wplms').'">';
								  echo '</select>
								 <input type="number" rel-name="' . $field['id'] . '[marks][]" placeholder="'.__('Marks for a Correct answer','wplms').'" value="0" /> 
								 <a class="meta_box_repeatable_remove" href="#"><span class="dashicons dashicons-no"></span></a></li>';
							echo '</ul>

							    <strong>'.__('Total marks for the Quiz :','wplms').' <span id="total_quiz_marks"> '.$total_marks.'</span></strong><br />
								<span class="description">' . $field['desc'] . '</span>';
						break;
						case 'payments':
						echo '<ul id="instructor_payments"><li><strong>'.__('Instructor','wplms').'</strong><span>'.__('Email','wplms').'</span><span>'.__('Commission','wplms').'</span><span>'.__('Currency','wplms').'</span></li>';
						if(is_array($meta))
						foreach($meta as $key=>$row){
							if(isset($row['set']) && $row['set'])
								echo '<li><strong>'.get_the_author_meta('display_name',$key).'</strong><span>'.$row['email'].'</span><span>'.$row['commission'].'</span>'.(!empty($row['currency'])?'<span>'.$row['currency'].'</span>':'').'</li>';
						}
						echo '</ul>';
						break;
                        case 'gallery':
                            global $post;
                        ?>
                        <div id="vibe_gallery_container">
                        <ul class="vibe_gallery">
						<?php
                        if(!$meta || $meta == 'Array') $meta = '';
                        if($meta){
						$attachments = array_filter( explode( ',', $meta ) );
						if ( is_array($attachments ) && $attachments)
							foreach ( $attachments as $attachment_id ) {
								echo '<li class="slider_image" data-attachment_id="' . $attachment_id . '">
									' . wp_get_attachment_image( $attachment_id, 'full' ) . '
									<ul class="actions">
										<li><a href="#" class="delete" title="' . __( 'Delete image', 'wplms' ) . '">' . __( 'Delete', 'wplms' ) . '</a></li>
									</ul>
								</li>';
							}
                        }
					?>
				</ul>
	            <?php
                echo '<input type="hidden" name="' . $id . '" id="' . $id . '" value="' . esc_attr( $meta ) . '" />';
                ?>
		

	</div>
	<p class="add_gallery hide-if-no-js">
		<a href="#" class="button-primary"><?php _e( 'Add Gallery images', 'wplms' ); ?></a>
	</p>
	<script type="text/javascript">
		jQuery(document).ready(function($){

			// Uploading files
			var media_frame;
			var $image_gallery_ids = $('#<?php echo $id;?>');
			var $media = $('#vibe_gallery_container ul.vibe_gallery');

			jQuery('.add_gallery').on( 'click', 'a', function( event ) {

				var $el = $(this);
				var attachment_ids = $image_gallery_ids.val();

				event.preventDefault();

				// If the media frame already exists, reopen it.
				if ( media_frame ) {
					media_frame.open();
					return;
				}

				// Create the media frame.
				media_frame = wp.media.frames.downloadable_file = wp.media({
					// Set the title of the modal.
					title: '<?php _e( 'Add Images to Gallery', 'wplms' ); ?>',
					button: {
						text: '<?php _e( 'Add to Gallery', 'wplms' ); ?>',
					},
					multiple: true
				});

				// When an image is selected, run a callback.
				media_frame.on( 'select', function() {

					var selection = media_frame.state().get('selection');

					selection.map( function( attachment ) {

						attachment = attachment.toJSON();

						if ( attachment.id ) {
							attachment_ids = attachment_ids ? attachment_ids + "," + attachment.id : attachment.id;

							$media.append('\
								<li class="slider_image" data-attachment_id="' + attachment.id + '">\
									<img src="' + attachment.url + '" />\
									<ul class="actions">\
										<li><a href="#" class="delete" title="<?php _e( 'Delete', 'wplms' ); ?>"><?php _e( 'Delete', 'wplms' ); ?></a></li>\
									</ul>\
								</li>');
						}

					} );

					$image_gallery_ids.val( attachment_ids );
				});

				// Finally, open the modal.
				media_frame.open();
			});

			// Image ordering
			$media.sortable({
				items: 'li.slider_image',
				cursor: 'move',
				scrollSensitivity:40,
				forcePlaceholderSize: true,
				forceHelperSize: false,
				helper: 'clone',
				opacity: 0.65,
				placeholder: 'wc-metabox-sortable-placeholder',
				start:function(event,ui){
					ui.item.css('background-color','#f6f6f6');
				},
				stop:function(event,ui){
					ui.item.removeAttr('style');
				},
				update: function(event, ui) {
					var attachment_ids = '';

					$('#vibe_media_container ul li.image').css('cursor','default').each(function() {
						var attachment_id = jQuery(this).attr( 'data-attachment_id' );
						attachment_ids = attachment_ids + attachment_id + ',';
					});

					$image_gallery_ids.val( attachment_ids );
				}
			});

			// Remove images
			$('#vibe_gallery_container').on( 'click', 'a.delete', function() {

				$(this).closest('li.slider_image').remove();

				var attachment_ids = '';

				$('#vibe_gallery_container ul li.slider_image').css('cursor','default').each(function() {
					var attachment_id = jQuery(this).attr( 'data-attachment_id' );
					attachment_ids = attachment_ids + attachment_id + ',';
				});

				$image_gallery_ids.val( attachment_ids );

				return false;
			} );

		});
	</script>
	<?php
						break;
                                                 case 'audio':
                                                    global $post;
                                                    ?>
                                                <div id="vibe_audio_container">
                        <ul class="vibe_audio">
			<?php
                                if(!$meta || $meta == 'Array') $meta = '';
                                if($meta){
				$attachments = array_filter( explode( ',', $meta ) );
                                
                                
				if ( is_array($attachments ) && $attachments)
					foreach ( $attachments as $attachment_id ) {
						echo '<li class="audio_file" data-attachment_id="' . $attachment_id . '">
							' . wp_get_attachment_image( $attachment_id, 'full' ) . '
							<ul class="actions">
								<li><a href="#" class="delete" title="' . __( 'Delete audio file', 'wplms' ) . '">' . __( 'Delete', 'wplms' ) . '</a></li>
							</ul>
						</li>';
					}
                                }
			?>
		</ul>
            <?php
                echo '<input type="hidden" name="' . $id . '" id="' . $id . '" value="' . esc_attr( $meta ) . '" />';
              ?>
		

	</div>
	<p class="add_audio hide-if-no-js">
		<a href="#" class="button-primary"><?php _e( 'Add Audio Files', 'wplms' ); ?></a>
	</p>
	<script type="text/javascript">
		jQuery(document).ready(function($){

			// Uploading files
			var media_frame;
			var $image_gallery_ids = $('#<?php echo $id;?>');
			var $media = $('#vibe_audio_container ul.vibe_audio');

			jQuery('.add_audio').on( 'click', 'a', function( event ) {

				var $el = $(this);
				var attachment_ids = $image_gallery_ids.val();

				event.preventDefault();

				// If the media frame already exists, reopen it.
				if ( media_frame ) {
					media_frame.open();
					return;
				}

				// Create the media frame.
				media_frame = wp.media.frames.downloadable_file = wp.media({
					// Set the title of the modal.
					title: '<?php _e( 'Add Audio', 'wplms' ); ?>',
					button: {
						text: '<?php _e( 'Add Audio', 'wplms' ); ?>',
					},
					multiple: true
				});

				// When an image is selected, run a callback.
				media_frame.on( 'select', function() {

					var selection = media_frame.state().get('selection');

					selection.map( function( attachment ) {

						attachment = attachment.toJSON();

						if ( attachment.id ) {
							attachment_ids = attachment_ids ? attachment_ids + "," + attachment.id : attachment.id;

							$media.append('\
								<li class="audio_file" data-attachment_id="' + attachment.id + '">\
									<img src="' + attachment.url + '" />\
									<ul class="actions">\
										<li><a href="#" class="delete" title="<?php _e( 'Delete', 'wplms' ); ?>"><?php _e( 'Delete', 'wplms' ); ?></a></li>\
									</ul>\
								</li>');
						}

					} );

					$image_gallery_ids.val( attachment_ids );
				});

				// Finally, open the modal.
				media_frame.open();
			});

			// Image ordering
			$media.sortable({
				items: 'li.audio_file',
				cursor: 'move',
				scrollSensitivity:40,
				forcePlaceholderSize: true,
				forceHelperSize: false,
				helper: 'clone',
				opacity: 0.65,
				placeholder: 'wc-metabox-sortable-placeholder',
				start:function(event,ui){
					ui.item.css('background-color','#f6f6f6');
				},
				stop:function(event,ui){
					ui.item.removeAttr('style');
				},
				update: function(event, ui) {
					var attachment_ids = '';

					$('#vibe_audio_container ul li.audio_file').css('cursor','default').each(function() {
						var attachment_id = jQuery(this).attr( 'data-attachment_id' );
						attachment_ids = attachment_ids + attachment_id + ',';
					});

					$image_gallery_ids.val( attachment_ids );
				}
			});

			// Remove images
			$('#vibe_audio_container').on( 'click', 'a.delete', function() {

				$(this).closest('li.audio_file').remove();

				var attachment_ids = '';

				$('#vibe_audio_container ul li.audio_file').css('cursor','default').each(function() {
					var attachment_id = jQuery(this).attr( 'data-attachment_id' );
					attachment_ids = attachment_ids + attachment_id + ',';
				});

				$image_gallery_ids.val( attachment_ids );

				return false;
			} );

		});
	</script>
	<?php
						break;
                                                 case 'video':
                                                    global $post;
                                                    ?>
                                                <div id="vibe_media_container">
                        <ul class="vibe_media">
			<?php
                                if(!$meta || $meta == 'Array') $meta = '';
                                if($meta){
				$attachments = array_filter( explode( ',', $meta ) );
                                
                                
				if ( is_array($attachments ) && $attachments)
					foreach ( $attachments as $attachment_id ) {
						echo '<li class="slider_image" data-attachment_id="' . $attachment_id . '">
							' . wp_get_attachment_image( $attachment_id, 'full' ) . '
							<ul class="actions">
								<li><a href="#" class="delete" title="' . __( 'Delete video file', 'wplms' ) . '">' . __( 'Delete', 'wplms' ) . '</a></li>
							</ul>
						</li>';
					}
                                }
			?>
		</ul>
            <?php
                echo '<input type="hidden" name="' . $id . '" id="' . $id . '" value="' . esc_attr( $meta ) . '" />';
              ?>
		

	</div>
	<p class="add_video hide-if-no-js">
		<a href="#" class="button-primary"><?php _e( 'Add Video Files', 'wplms' ); ?></a>
	</p>
	<script type="text/javascript">
		jQuery(document).ready(function($){

			// Uploading files
			var media_frame;
			var $image_gallery_ids = $('#<?php echo $id;?>');
			var $media = $('#vibe_media_container ul.vibe_media');

			jQuery('.add_video').on( 'click', 'a', function( event ) {

				var $el = $(this);
				var attachment_ids = $image_gallery_ids.val();

				event.preventDefault();

				// If the media frame already exists, reopen it.
				if ( media_frame ) {
					media_frame.open();
					return;
				}

				// Create the media frame.
				media_frame = wp.media.frames.downloadable_file = wp.media({
					// Set the title of the modal.
					title: '<?php _e( 'Add Video Files', 'wplms' ); ?>',
					button: {
						text: '<?php _e( 'Add Video', 'wplms' ); ?>',
					},
					multiple: true
				});

				// When an image is selected, run a callback.
				media_frame.on( 'select', function() {

					var selection = media_frame.state().get('selection');

					selection.map( function( attachment ) {

						attachment = attachment.toJSON();

						if ( attachment.id ) {
							attachment_ids = attachment_ids ? attachment_ids + "," + attachment.id : attachment.id;

							$media.append('\
								<li class="slider_image" data-attachment_id="' + attachment.id + '">\
									<img src="' + attachment.url + '" />\
									<ul class="actions">\
										<li><a href="#" class="delete" title="<?php _e( 'Delete', 'wplms' ); ?>"><?php _e( 'Delete', 'wplms' ); ?></a></li>\
									</ul>\
								</li>');
						}

					} );

					$image_gallery_ids.val( attachment_ids );
				});

				// Finally, open the modal.
				media_frame.open();
			});

			// Image ordering
			$media.sortable({
				items: 'li.slider_image',
				cursor: 'move',
				scrollSensitivity:40,
				forcePlaceholderSize: true,
				forceHelperSize: false,
				helper: 'clone',
				opacity: 0.65,
				placeholder: 'wc-metabox-sortable-placeholder',
				start:function(event,ui){
					ui.item.css('background-color','#f6f6f6');
				},
				stop:function(event,ui){
					ui.item.removeAttr('style');
				},
				update: function(event, ui) {
					var attachment_ids = '';

					$('#vibe_media_container ul li.image').css('cursor','default').each(function() {
						var attachment_id = jQuery(this).attr( 'data-attachment_id' );
						attachment_ids = attachment_ids + attachment_id + ',';
					});

					$image_gallery_ids.val( attachment_ids );
				}
			});

			// Remove images
			$('#vibe_media_container').on( 'click', 'a.delete', function() {

				$(this).closest('li.slider_image').remove();

				var attachment_ids = '';

				$('#vibe_media_container ul li.slider_image').css('cursor','default').each(function() {
					var attachment_id = jQuery(this).attr( 'data-attachment_id' );
					attachment_ids = attachment_ids + attachment_id + ',';
				});

				$image_gallery_ids.val( attachment_ids );

				return false;
			} );

		});
	</script>
	<?php
						break;

						case 'note':
							echo $desc;
						break;
						case 'multiattachments':

							if(!$meta || $meta == 'Array') $meta = '';
                            $attachments = array();

                            if(!empty($meta)){
								$attachments = $meta;
							}
							
							echo '<ul class="' . $field['id'] . '_attachments attachment_list">';
							if(!empty($attachments)){
								
								foreach($attachments as $attachment_id){
									echo '<li><span class="sort dashicons dashicons-move"></span>';
									echo '<strong>'.get_the_title($attachment_id).'</strong>';
									echo '<input type="hidden" name="' . $field['id'] . '[]" value="'.$attachment_id.'">';
									echo '<span class="remove_attachment dashicons dashicons-no"></span>';
									echo '</li>';
								}
							}
							echo '</ul>';
							?>
							<a class="add_attachments button-primary" data-add="<?php echo $field['id']; ?>_attachments"><?php _e( 'Add Attachments', 'wplms' ); ?></a>
							<?php

							?>
							<script type="text/javascript">
								jQuery(document).ready(function($){

									// Uploading files
									var attachment_frame;
									//var $image_gallery_ids = $('#<?php echo $id;?>');
									var $media = $('.<?php echo $field['id']; ?>_attachments');

									jQuery('.add_attachments').on( 'click',function( event ) {

										var $el = $(this);
										var attachment_ids = $("input[name=\'<?php echo $field['id']; ?>\']").val();

										event.preventDefault();

										// If the media frame already exists, reopen it.
										if ( attachment_frame ) {
											attachment_frame.open();
											return;
										}

										// Create the media frame.
										attachment_frame = wp.media.frames.downloadable_file = wp.media({
											// Set the title of the modal.
											title: '<?php _e( 'Add Attachments', 'wplms' ); ?>',
											button: {
												text: '<?php _e( 'Add Attachment', 'wplms' ); ?>',
											},
											multiple: true
										});

										// When an image is selected, run a callback.
										attachment_frame.on( 'select', function() {

											var selection = attachment_frame.state().get('selection');

											selection.map( function( attachment ) {

												attachment = attachment.toJSON();
												console.log(attachment.title);

												if ( attachment.id ) {
													
													$media.append('\
														<li><span class="sort dashicons dashicons-move"></span>\
															<strong>'+attachment.title+'</strong>\
															<input type="hidden" name="<?php echo $field['id']; ?>[]" value="' + attachment.id + '"/>\
															<span class="remove_attachment dashicons dashicons-no"></span>\
														</li>');
												}

											} );

										});

										// Finally, open the modal.
										attachment_frame.open();
									});

									// Image ordering
									$media.sortable({
										items: 'li',
										handle:'.sort',
										cursor: 'move',
										scrollSensitivity:40,
										opacity: 0.65,
										start:function(event,ui){
											ui.item.css('background-color','#f6f6f6');
										},
										stop:function(event,ui){
											ui.item.removeAttr('style');
										},
									});

									// Remove images
									$('.attachment_list .remove_attachment').on( 'click', function() {
										$(this).parent('li').remove();
									} );

								});
							</script>
						<?php	
						break;
						default:
							$type=apply_filters('custom_meta_box_type',$type,$meta,$id,$desc,$post_type);
						break;
					} //end switch
			echo '</td></tr>';
		} // end foreach
		echo '</table>'; // end table
		wp_nonce_field('vibe_security','vibe_security');
	}
	
	// Save the Data
	function save_box( $post_id ) {
		global $post, $post_type;
		
		// verify nonce
		if ( ! ( in_array($post_type, $this->page) && @wp_verify_nonce( $_POST[$post_type . '_meta_box_nonce'],  basename( __FILE__ ) ) ) )
			return $post_id;
		// check autosave
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return $post_id;
		// check permissions
		if ( !current_user_can( 'edit_posts', $post_id ) )
			return $post_id;

		// loop through fields and save the data
		foreach ( $this->fields as $field ) {
			                  
			if(isset($field['id'])){

				if( $field['type'] == 'tax_select' ) {
				// save taxonomies
					if ( isset( $_POST[$field['id']] ) )
						$term = $_POST[$field['id']];
					wp_set_object_terms( $post_id, $term, $field['id'] );
				}
				// save the rest
                //print_r($_POST[$field['id']]);

              	//$old = get_post_meta( $post_id, $field['id'], true );
                $old='';
                $new='';
                                
				if ( isset( $_POST[$field['id']] ) )
					$new = $_POST[$field['id']];

				print_r($field['id'].'==>'.$_POST[$field['id']].' = '.isset($_POST[$field['id']]) );

				if(isset($field['type']) && ($field['type'] == 'checkbox' || $field['type'] == 'available' || $field['type'] == 'featured')){ 
					if ( !isset( $_POST[$field['id']] ) ){
                        $new = 0;
                    }
					
				}	
				if(isset($field['type']) && $field['type'] == 'gmap') { 
					if ( isset($_POST[$field['id']]) && is_array( $_POST[$field['id']])){
                        if(isset($_POST[$field['id']]['city']))
							update_post_meta($post_id,'vibe_gmap_city',$_POST[$field['id']]['city']);
                        if(isset($_POST[$field['id']]['state']))
                            update_post_meta($post_id,'vibe_gmap_state',$_POST[$field['id']]['state']);
                        if(isset($_POST[$field['id']]['pincode']))
                            update_post_meta($post_id,'vibe_gmap_pincode',$_POST[$field['id']]['pincode']);
                        if(isset($_POST[$field['id']]['country'])) 
                            update_post_meta($post_id,'vibe_gmap_country',$_POST[$field['id']]['country']);
                    }
				}//end-gmap
				if(isset($field['type']) && $field['type'] == 'image') { 
					if ( !isset( $_POST[$field['id']] ) || !$_POST[$field['id']]){
						$new = ' ';
                                        }
				}
				if(isset($field['type']) && ($field['type'] == 'textarea' || $field['type'] == 'editor')) { 
					if ( !isset( $_POST[$field['id']] ) || !$_POST[$field['id']])
						$new = ' ';
				}
				/*if( $field['type'] == 'sliders' ) {
					$disable = get_post_meta( $post_id, 'vibe_disable_featured', true );
					if((isset($disable) && $disable =='disable') || (!isset($new[0]['image']) || $new[0]['image'] == '')){
						$new=$old;
					}
				}*/
				
				if ( $new && $new != $old ) {
					if ( is_array( $new ) ) {
						foreach ( $new as $item ) {

                            if(is_array($item)){
                                foreach ( $item as $item2 ) {


                                	if($field['type'] == 'editor')
                                		$item2 =  $item2 ;
                                	else{
                                		if(is_Array($item2)){
                                			$item2 = serialize($item2);	
                                		}else{
                                			$item2 = esc_attr( $item2 );	
                                		}
                                	}
                            	}
                            	unset($item2);
                            }else{
                              	if($field['type'] == 'editor')
                            		$item =  $item ;
                                else{
                                	$item = esc_attr( $item );  
                                }		
                              		
                            }
						} //end foreach
						unset( $item );
					} else {
						if(isset($field['type']) && $field['type'] != 'editor')
							$new = esc_attr( $new );
					}
					if(!isset($_POST[$field['id']])){
						update_post_meta( $post_id, $field['id'], '' );
					}else{
						update_post_meta( $post_id, $field['id'], $new );	
					}
					
				} elseif ( !isset($new) && $old ) {
					delete_post_meta( $post_id, $field['id'], $old );
				}elseif(!$new){
                	update_post_meta( $post_id, $field['id'], $new );
                }
            }//isset field['id']
		} // end foreach fields
		
	} // end save-box

} //end class

class vibe_meta_box_ajax {

	public static $instance;
    
    public static function init(){
        if ( is_null( self::$instance ) )
            self::$instance = new vibe_meta_box_ajax;
        return self::$instance;
    }

	private function __construct(){
		add_action('wp_ajax_get_admin_select_cpt',array($this,'get_admin_select_cpt'));
		add_Action('wp_ajax_get_groups',array($this,'get_groups'));
	}

	function get_admin_select_cpt(){
        $user_id = get_current_user_id();

        if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'vibe_security') || !current_user_can('edit_posts')){
             _e('Security check Failed. Contact Administrator.','wplms');
             die();
        } 
        $id = $_POST['post_id'];
        $q = $_POST['q'];
        $args = array(
            'post_type'=>$_POST['cpt'],
            'posts_per_page'=>99,
            's'=>$q['term'],
            'post_status'=>'publish'
            );

        $args = apply_filters('wplms_backend_cpt_query',$args,$id);
        
        $query = new WP_Query($args);
        $return = array();
        if($query->have_posts()){
            while($query->have_posts()){
                $query->the_post();
                $return[] = array('id'=>get_the_ID(),'text'=>get_the_title());
            }
        }
        wp_reset_postdata();
        print_r(json_encode($return));
        die();
    }

    function get_groups(){

    	 if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'vibe_security') || !current_user_can('edit_posts')){
             _e('Security check Failed. Contact Administrator.','wplms');
             die();
        } 
        $id = $_POST['id'];
        $q = $_POST['q'];

        global $bp,$wpdb;
        $return = array();
        $search_terms_like = '%'.$q['term'].'%';
		$groups = $wpdb->get_results( $wpdb->prepare( "SELECT id , name  FROM {$bp->groups->table_name} WHERE ( name LIKE %s OR description LIKE %s )", $search_terms_like, $search_terms_like ) );
    	
    	if(!empty($groups)){
    		foreach($groups as $group){
	    		$return[] = array('id'=>$group->id,'text'=>$group->name);
	    	}
    	}
    	print_r(json_encode($return));
        die();
    }
}

vibe_meta_box_ajax::init();