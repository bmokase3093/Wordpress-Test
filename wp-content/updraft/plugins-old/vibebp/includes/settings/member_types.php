<?php

 if ( ! defined( 'ABSPATH' ) ) exit;

 class VibeBP_Member_Type_Settings{

 	protected $option = 'vibebp_member_types';
	public static $instance;
    public static function init(){
        if ( is_null( self::$instance ) )
            self::$instance = new VibeBP_Member_Type_Settings();
        return self::$instance;
    }

    public function __construct(){
        $this->bp_member_type_synced = get_option('bp_member_type_synced');
    	add_action('wp_ajax_save_member_types',array($this,'save_member_types'));
        add_action('wp_ajax_reset_member_types',array($this,'reset_member_types'));
        add_action('admin_enqueue_scripts',array($this,'add_vue_scripts'));
        if(empty($this->bp_member_type_synced)){
            add_action( 'bp_register_member_types',array($this, 'register_member_types_with_directory' ));
        }
        add_action('user_register',array($this,'assign_member_type_new_user'));


    	add_action('admin_head',array($this,'check_migration'));

        add_action('wp_ajax_get_unassigned_members',array($this,'get_unassigned_members'));
        add_action('wp_ajax_change_bp_member_type',array($this,'change_bp_member_type'));

    }

    function change_bp_member_type(){
        global $wpdb;
        $results = [];
        if(current_user_can('manage_options') && wp_verify_nonce($_POST['security'],'change_bp_member_type') ){
        $get_default_mtype = vibebp_get_setting('default_member_type','bp');
            if(!empty($_POST['ID'])){
                foreach($_POST['ID'] as $user_id){
                    bp_set_member_type($user_id, $get_default_mtype);
                }
            }
        }
    }

    function get_unassigned_members(){
        global $wpdb;
        $results = [];
        if(current_user_can('manage_options') && wp_verify_nonce($_POST['security'], 'assign_default_member_type') ){
            $userid_has_member_type = $wpdb->get_results("SELECT wp_term_relationships.object_id as user_id FROM wp_term_relationships
                INNER JOIN wp_term_taxonomy
                ON wp_term_relationships.term_taxonomy_id = wp_term_taxonomy.term_id
                WHERE wp_term_taxonomy.taxonomy = 'bp_member_type' ",ARRAY_A);
            if(!empty($userid_has_member_type)){
                $get_default_mtype = vibebp_get_setting('default_member_type','bp');
                $user_ids = wp_list_pluck( $userid_has_member_type, 'user_id' );
                $no_member_type_user_ids = $wpdb->get_results("SELECT ID FROM {$wpdb->users} WHERE ID NOT IN (".implode(',',$user_ids).")");
                if(!empty($no_member_type_user_ids)){
                    $alluids = wp_list_pluck($no_member_type_user_ids,'ID');
                    if(count($alluids) > 10){
                        for($i=0;$i<count($alluids);$i=$i+$_POST['batch']){
                            $arr = [];
                            for($k=0;$k<$_POST['batch'];$k++){
                                $arr[]=$alluids[$i+$k];
                            }
                            $results[]=array(
                                'action'=>'change_bp_member_type',
                                'security'=>wp_create_nonce('change_bp_member_type'),
                                'ID'=>$arr
                            );
                        }
                    }
                }
            }

        }
        print_r(json_encode($results));
        die();
    }

    function assign_member_type_new_user($user_id){
             $set_default_mtype = vibebp_get_setting('default_member_type','bp');
             return bp_set_member_type($user_id, $set_default_mtype);
    }

    function check_migration(){
        //taxonomy=bp_member_type&migrate_vibebp=1
        if(!empty($_GET['taxonomy']) && $_GET['taxonomy']=='bp_member_type'  && !empty($_GET['migrate_vibebp'])){
            update_option('bp_member_type_synced',1);
        }
    }

    function get_member_types(){

        if(empty($this->member_types))
            $this->member_types = get_option($this->option);

        return $this->member_types;
    }

    function show_member_type_field($name){
        $member_types = get_option($this->option);
        $forms = get_option('wplms_registration_forms');
        if(!empty($member_types)){
            echo '<li><label class="field_name">'.__('Assign member type','vibe-customtypes').'</label><select name="member_type|'.$name.'"><option value="">'._x('None','member types','vibe-customtypes').'</option><option value="enable_user_member_types_select" '.((isset($forms[$name]['settings']['member_type']) && $forms[$name]['settings']['member_type'] == 'enable_user_member_types_select')?'selected':'').'>'._x('Enable User to select one','member types','vibe-customtypes').'</option>';
            foreach($member_types as  $key => $member_type){
                echo '<option value="'.$member_type['id'].'" '.((isset($forms[$name]['settings']['member_type']) && $forms[$name]['settings']['member_type'] == $member_type['id'])?'selected':'').'>'.$member_type['sname'].'</option>';
            }
            echo '</select></li>';
        }
    }

    function add_member_types_settings($tabs){
    	if(!isset($_GET['tab']) || $_GET['tab'] == 'general'){
    		
	    	$tabs['member_types'] = _x('Member Types','configure Course menu in LMS - Settings','vibe-customtypes');
 		}
 		return $tabs;
    }

   function register_member_types_with_directory() {
        $member_types = get_option($this->option);
        if(!empty($member_types)){
            foreach($member_types as $key => $member_type){
                if(!empty($member_type)){
                    bp_register_member_type( $member_type['id'], array(
                        'labels' => array(
                            'name'          => $member_type['pname'],
                            'singular_name' => $member_type['sname'],
                        ),
                        'has_directory' => $member_type['id']
                    ));
                }
                
            }
        }
    }   

    function show(){
    	if(!isset($_GET['sub']) || $_GET['sub'] != 'members')
    		return;
            //Fetch Member types >
            $types = bp_get_member_types(array(),'objects');
                $mtypes = [''=>__('No default member type','vibebp')];
                if(!empty($types)){
                    foreach($types as $type => $labels){
                        $mtypes[$type]=$labels->labels['name'];
                    }
                }
            $settings = apply_filters('VibeBP_Settings_Buddypress_members',array(
                array(
                    'label' => __('Default Member','vibebp'),
                    'name' => 'default_member_type',
                    'type' => 'select',
                    'options'=>$mtypes,
                    'desc' => __('Select default member type').'<a id="assign_default_member_type" class="button">Apply to existing members who do not have anyu member type assigned</a>',
                    'default'=>''
                )
            ));
            //JS call assign_default_member_type
            ;

            ?>
            <script>
                jQuery(document).ready(function($){
                    $('#assign_default_member_type').on('click',function(){
                        var $this = $(this);
                        $this.after('<span class="member_type_status">Starting ...</span><div class="progress_wrap" style="margin: 30px 0;width: 300px;"><div class="progress" style="height: 10px;border-radius: 5px;"><div class="bar" style="width: 5%;"></div></div></div>');
                        //Show progress bar
                        $.ajax({
                            type: "POST",
                            url: ajaxurl,
                            dataType: "json",
                            data: { 
                                    action: 'get_unassigned_members', 
                                    security: '<?php echo wp_create_nonce('assign_default_member_type'); ?>',
                                    batch:10
                                },
                            cache: false,
                            success: function (json) {
                                $this.parent().find('.progress_wrap .bar').css('width','10%');
                                $this.parent().find('span.member_type_status').text('fetched '+Object.keys(json).length*10+' unassigned members, sync in progress...');
                                    var defferred = [];
                                    var current = 0;
                                    $.each(json,function(i,item){
                                        defferred.push(item);
                                    });
                                    recursive_step(current,defferred,$this);
                                    //$.each() RUN loop on json and increment progress bar
                                    $('body').on('end_recursive_member_type_sync',function(){
                                        $this.parent().find('span.member_type_status').text(text);
                                        $this.parent().find('.progress_wrap .bar').css('width','100%');
                                        setTimeout(function(){$this.parent().find('.progress_wrap,.member_type_status').hide(200);},3000);
                                    });     
                            }
                        });
                    });

                    function recursive_step(current,defferred,$this){
                        if(current < defferred.length){
                            $.ajax({
                                type: "POST",
                                url: ajaxurl,
                                data: defferred[current],
                                cache: false,
                                success: function(){ 
                                    current++;
                                    $this.find('span.member_type_status').text(current+'/'+defferred.length+' complete, sync in progress...');
                                    var width = 10 + 90*current/defferred.length;
                                    $this.parent().find('.bar').css('width',width+'%');
                                    if(defferred.length == current){
                                        $('body').trigger('end_recursive_member_type_sync');
                                    }else{
                                        recursive_step(current,defferred,$this);
                                    }
                                }
                            });
                        }else{
                            $('body').trigger('end_recursive_member_type_sync');
                        }
                    }//End of function

                });
            </script>
            <?php
            $s = VibeBP_Settings::init();
            $s->vibebp_settings_generate_form('bp',$settings,'members');
            
        if(defined('BP_VERSION') && version_compare( BP_VERSION, '7.1', '>=' )){
            if(!empty($this->bp_member_type_synced)){
                echo '<p>'._x('You have migrated to buddypress member type panel','','vibebp').'</p>';
                echo '<a href="'.admin_url('edit-tags.php?taxonomy=bp_member_type').'" class="button is-primary" style="margin-bottom:50px;">'._x('Goto buddypress panel','','vibebp').'</a>';

                //Query Member types >
                $settings = apply_filters('VibeBP_Settings_Buddypress_members',array(
                    array(
                        'label' => __('Editor Interface','vibebp'),
                        'name' => 'editor_interface',
                        'type' => 'select',
                        'options'=>array(
                            'full'=>__('Full Editor [ All Shortcodes, Media Library, Math, Columns','vibebp'),
                            'advanced'=>__('Advanced Editor [ All Shortcodes, Media Library, Columns','vibebp'),
                            'basic'=>__('Basic Editor [ Media library, No Shortcodes,No Columns ]','vibebp'),
                        ),
                        'desc' => __('What kind of editor is suitable for your site','vibebp').'<a href="" style="text-decoration:none;"><span class="dashicons dashicons-editor-help"></span></a>',
                        'default'=>''
                    )
                ));
                $s = VibeBP_Settings::init();
                $s->vibebp_settings_generate_form('bp',$settings,'members');

                return;
            }else{
                echo '<a href="'.admin_url('edit-tags.php?taxonomy=bp_member_type&migrate_vibebp=1').'" class="button is-primary" style="margin-bottom:50px;">'._x('Migrate to buddypress panel','','vibebp').'</a>';
            }
        }
        
        if(function_exists('bp_get_member_types')){
            $member_types_array=array();
            $member_types = bp_get_member_types( array(), 'objects' );
            if(!empty($member_types)){
                foreach ($member_types as $key => $member_type) {
                    $member_types_array[] = array(
                        'show_settings'=>false,
                        'id'=> $key,
                        'sname'=>$member_type->labels['singular_name'],
                        'pname'=>$member_type->labels['name'],
                        'error'=>'',

                        );
                }

                echo '<script>var existing_member_types = '.json_encode($member_types_array).';</script>';
            }
        }
        
       ?>
        <section  id="member_types_wrapper">

            <button @click="add" id="add" class="button button-primary"><?php echo _x('Add another','member types','vibe-customtypes');?></button>
          
            <ul v-sortable id="member_types_cont">
                <li v-for="(listing,index) in listings" class="mt_listing">
                    <small class="dashicons dashicons-menu"></small>
                    <span>{{listing.sname}}</span>
                    <ul v-bind:class="{ show: listing.show_settings }" class="mt_listing_settings">
                        <li><label><?php echo _x('Slug','','vibe-customtypes');?></label>{{listing.id}}</li>
                        <li><label><?php echo _x('Singular Name','','vibe-customtypes');?></label><input type="text" name="slisting" placeholder="<?php echo _x('Member type singular name','','vibe-customtypes')?>" class="form-control" v-model="listing.sname" @keyup="check_data(listing,listing.sname)" ></li>

                        <li><label><?php echo _x('Plural Name','','vibe-customtypes');?></label><input type="text" name="plisting" placeholder="<?php echo _x('Member type plural name','','vibe-customtypes')?>" class="form-control" v-model="listing.pname" @keyup="check_data(listing,listing.pname)"></li>

                        
                        <li class="ithaserror red" v-html="listing.error"></li>
                    </ul>
                    <em @click="remove_data(index)" class="red remove_member_type action_point dashicons dashicons-no"></em>
                    <em @click="listing.show_settings = !listing.show_settings" class="mt_setting_toggle action_point dashicons dashicons-edit"></em>
                </li>
            </ul>
            <?php wp_nonce_field('vibe_security','vibe_security');?>
            <button v-bind:class="{ loading: loading }" @click="save_mts"  class="button button-primary">{{save_settings_text}}</button>
            <button v-bind:class="{ loading: loading_rs }" class="reset_member_types button"  @click="reset_mts">{{reset_settings_text}}</button>


        </section>
        <style>
            .mt_listing {
                display: block;
                margin: 15px;
                background: #fff;
                width: 85%;
                position: relative;
                padding: 15px;
                border-radius: 3px;
            }
            .mt_listing .action_point {
                right: 10px;
                position: absolute;
                top: 15px;
            }
            em.mt_setting_toggle.action_point {
                right: 40px;
            }
            .red{
                color:red;
            }
        	.remove_member_type,.mt_setting_toggle{
        		cursor: pointer;
        	}
            .ithaserror {
                display: block;
                color: red;
                margin: 10px 2px;
            }
            .loading{
                opacity: 0.5;
            }
            .mt_listing_settings{
                display: none;
            }
            .mt_listing_settings.show{
                display: block;
                margin: 10px;
            }
            .mt_listing_settings>li>label {
                width: 100px;
                display: inline-block;
            }
            

        </style>
        <?php
       
        return array();
    }

    function add_vue_scripts(){
        if(!isset($_GET['sub']) || $_GET['sub'] != 'members')
            return;
        wp_enqueue_script('vue-js',plugins_url('vue.min.js',__FILE__));
        wp_enqueue_script('vue-Sortable-js',plugins_url('Sortable.min.js',__FILE__));
        
        add_action('admin_footer',function(){
            ?>
            <script>
                if(typeof existing_member_types != 'undefined'){
                    
                    var member_types = existing_member_types;        
                }else{
                    var member_types = [{
                                show_settings:false,
                                id:"<?php echo _x('N.A','member types','vibe-customtypes')?>",
                                sname: "<?php echo _x('N.A','member types','vibe-customtypes')?>",
                                pname:"",
                                error:""
                            }];
                }
                var vlm =  new Vue({
                    el:"#member_types_wrapper",
                    data: {
                        listings: member_types,
                        hasError: false,
                        disabled_saving:false,
                        loading:false,
                        save_settings_text:"<?php echo _x('Save setings','member types','vibe-customtypes');?>",
                        reset_settings_text:"<?php echo _x('Reset setings','member types','vibe-customtypes');?>",
                        loading_rs:false,
                        show_mt_setting:0,
                    },
                    methods: {
                        add: function (event) {
                            this.listings.push({
                                show_settings:false,
                                id:"<?php echo _x('N.A','member types','vibe-customtypes')?>",
                                sname: "<?php echo _x('N.A','member types','vibe-customtypes')?>",
                                pname:"",
                                error:""
                            });
                        },
                        show_mt:function(index,event){
                            if(this.show_mt_setting)
                            this.show_mt_setting = 1;
                            return !(show_mt_setting+index);
                        },
                        save_mts: function(event) {
                            var listing_data=[];
                            var vuethis = this;
                            if(this.disabled_saving == true){
                                alert("<?php echo _x('Please remove special characters.','member types','vibe-customtypes')?>");
                                return false;  
                            }
                            if(this.loading == true || this.disabled_saving == true){
                                return false;
                            }
                            this.loading = true;
                            var old_sv_text = this.save_settings_text;
                            jQuery.each(this.listings,function(k,listing){
                                if(typeof listing == 'undefined' || typeof listing.sname == 'undefined' || listing.sname == ''){
                                }else{
                                    listing_data.push({id:listing.id,sname:listing.sname,pname:listing.pname});
                                }
                            });
                            console.log(listing_data);
                            jQuery.ajax({
                                url: ajaxurl,
                                data: { action: 'save_member_types', 
                                        security: jQuery('#vibe_security').val(),
                                        member_types : JSON.stringify(listing_data),
                                      },
                                method: 'POST',
                                success:function(html){
                                    newtext = html;
                                    vuethis.save_settings_text = newtext;
                                    setTimeout(function(){
                                        vuethis.loading = false;
                                        vuethis.save_settings_text  = old_sv_text;
                                    },3000);
                                }
                            });
                        },
                        reset_mts:function(event){
                            if(this.loading_rs == true){
                                return false;
                            }
                            this.loading_rs = true;
                            var old_rs_text = this.reset_settings_text;
                            jQuery.ajax({
                                url: ajaxurl,
                                data: { action: 'reset_member_types', 
                                        security: jQuery('#vibe_security').val(),
                                      },
                                method: 'POST',
                                success:function(html){
                                    location.reload();

                                }
                            });
                        },
                        remove_data:function(index,event){
                            this.listings.splice(index,1);
                            var that = this;
                            jQuery.each(this.listings,function(k,listing){
                                if(typeof listing.sname != 'undefined' || listing.sname != '' || typeof listing.pname != 'undefined' || listing.pname != ''){
                                    var found1 =  listing.sname.match(/\`|\~|\!|\@|\#|\$|\%|\^|\&|\*|\(|\)|\+|\=|\[|\{|\]|\}|\||\\|\'|\<|\,|\.|\>|\?|\/|\""|\;|\:/g);
                                    var found2 = listing.pname.match(/\`|\~|\!|\@|\#|\$|\%|\^|\&|\*|\(|\)|\+|\=|\[|\{|\]|\}|\||\\|\'|\<|\,|\.|\>|\?|\/|\""|\;|\:/g);
                                    if(found1 || found2){
                                        that.disabled_saving = true;
                                        return false; 
                                    }else{
                                        that.disabled_saving = false;
                                    }
                                }else{
                                    that.disabled_saving = false;
                                } 
                            });
                            if(that.disabled_saving){
                                this.disabled_saving = true;
                            }
                            
                        },
                        check_data:function(listing,value,event){
                            if(typeof value != 'undefined' || value != ''){
                                var found =  value.match(/\`|\~|\!|\@|\#|\$|\%|\^|\&|\*|\(|\)|\+|\=|\[|\{|\]|\}|\||\\|\'|\<|\,|\.|\>|\?|\/|\""|\;|\:/g);
                                if(found){
                                    listing.error = "<?php echo _x('Special Characters are not allowed','member types','vibe-customtypes');?>";
                                    this.disabled_saving = true;
                                }else{
                                    listing.error = "";
                                    this.disabled_saving = false;
                                }
                            }else{
                                this.disabled_saving = false;
                                listing.error = "";
                            }
                        }
                    },
                    watch: {
                        loading: function(){
                            console.log('Loading Changed');
                        }
                    },
                    mounted: function(){
                        var self = this;
                        self.$nextTick(function(){
                          var sortable = Sortable.create(document.getElementById('member_types_cont'), {
                            onEnd: function(e) {
                              var clonedItems = self.listings.filter(function(item){
                               return item;
                              });
                              clonedItems.splice(e.newIndex, 0, clonedItems.splice(e.oldIndex, 1)[0]);
                              self.listings = [];
                              self.$nextTick(function(){
                                self.listings = clonedItems;
                              });
                            }
                          }); 
                        });
                    }
                });
            </script>
            <?php
        },999);
    }

    function save_member_types(){
    	if ( !isset($_POST['security']) || !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'vibe_security') || !is_user_logged_in() || !current_user_can('edit_posts')){
                _e('Security check Failed. Contact Administrator.','vibe-customtypes');
                die();
        }
        $member_types = stripcslashes($_POST['member_types']);
        $member_types = json_decode($member_types,true);
        $final_member_types = array();
        if(!empty($member_types)){
        	foreach($member_types as $member_type){
	        	$sname = sanitize_text_field($member_type['sname']);
	        	$final_member_types[] = array(
	        		'id'=>sanitize_title($sname),
	        		'sname'=>$sname,
                    'pname'=>sanitize_text_field($member_type['pname'])
	        		);
	        }
        }
        
        update_option($this->option,$final_member_types);
        echo _x('Saved','','vibe-customtypes');
        die();
    }

    function reset_member_types(){
        if ( !isset($_POST['security']) || !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'vibe_security') || !is_user_logged_in() || !current_user_can('edit_posts')){
                _e('Security check Failed. Contact Administrator.','vibe-customtypes');
                die();
        }
        
        
        delete_option($this->option);
        echo _x('Reset','','vibe-customtypes');
        die();
    }
}	

VibeBP_Member_Type_Settings::init();
