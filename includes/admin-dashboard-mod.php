<?php
/*
 * 
 * */
add_filter( 'cac/column/value', 'myplugin_cac_column_featured_image_value', 10, 4 );
 
function myplugin_cac_column_featured_image_value( $value, $postid, $column, $post_type ) {
 
if ( $column->properties->type == 'column-modified' && $post_type==='view_0') {
 
 //$value = '<em>' . __( 'No featured image', 'myplugin' ) . '</em>';
 $value = date('D jS M',strtotime($value));
 
}
 
return $value;
}
/*
----------- ADMIN DASHBOARD CSS STYLING --------------------
*/
add_action( 'admin_enqueue_scripts', 'savi_admin_scripts_styles', 10, 1 );
function savi_admin_scripts_styles( $hook ) {
	global $typenow;
	$post_types = apply_filters( 'savi_adminTool_post_types', array(
		'view_0',
		'view_1',
		'view_2',
		'view_3',
		'view_4',
		'view_5',
		'view_6',
		'view_7',
		'view_8',
		
	) );
	if ( $hook == 'toplevel_page_enquiry_review' ) savi_add_view_page_js_css();
	if ( isset( $typenow ) && in_array( $typenow, $post_types ) ) savi_add_view_page_js_css();
}
//following function is called in previous function above
function savi_add_view_page_js_css(){
	$template_dir = get_stylesheet_directory_uri();
	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'savi-pop-js', $template_dir . '/js/pop.js', array( 'jquery'), SAVI_2014_VERSION, false );
	wp_enqueue_style( 'savi-pop', $template_dir . '/css/pop.css', array(), 1.0 );
}
/*
-------------- ADMIN DASHBOARD VIEWS Actions ---------------
*/
add_filter( 'post_row_actions', 'savi_remove_row_actions', 10, 1 );
function savi_remove_row_actions ( $actions) {
  global $post;
   $ID = $post->ID;
   $type = get_post_type();
    switch($type) { //remove "slider" post_type to whatever post_type you want the row-actions to hide
      
       case 'view_0':
        unset( $actions['inline hide-if-no-js'] );  // quick edit
        //unset( $actions['edit'] );    // edit
        unset( $actions['view'] );    // view
       break;
       case 'view_1':
        unset( $actions['inline hide-if-no-js'] );  // quick edit
        //unset( $actions['edit'] );   // edit
        unset( $actions['view'] );    // view
        unset( $actions['trash'] );    // trash
        $profile_incomplete = get_post_meta($ID,'profile_incomplete',true);
        if($profile_incomplete =="yes"){
			unset( $actions['edit'] ); 
		}
	    else{
			$actions['approve-user'] ="<a href='edit.php?post_type=view_1&action=inline-approval&post=$ID' >Approve User</a>";
	    }
        
       break; 
       case 'view_2':
        unset( $actions['inline hide-if-no-js'] );  // quick edit
        unset( $actions['edit'] );   // edit
        unset( $actions['view'] );    // view
        unset( $actions['trash'] );    // trash
        $actions['edit-opportunities'] ="<a href='post.php?post=$ID&action=edit' >Edit Opportunity</a>";
            
       break;
       case 'view_3':
        unset( $actions['inline hide-if-no-js'] );  // quick edit
        //unset( $actions['edit'] );   // edit
        unset( $actions['view'] );    // view
        unset( $actions['trash'] );    // trash
        break;
        case 'view_4':
        unset( $actions['inline hide-if-no-js'] );  // quick edit
      //  unset( $actions['edit'] );   // edit
        unset( $actions['view'] );    // view
        unset( $actions['trash'] );    // trash
        case 'view_5':
        case 'view_6':
        case 'view_7':
        unset( $actions['inline hide-if-no-js'] );  // quick edit
		  //unset( $actions['edit'] );   // edit
        unset( $actions['view'] );    // view
        unset( $actions['trash'] );    // trash
        
           
       break; 
    }
  /*	$allowed_types = array('vew_0','view_1','view_2','view_3','view_4','view_5','view_6','view_7');
  	if(is_admin() && in_array(get_post_type(),$allowed_types))
   		$actions['view-details'] ="<a href='admin.php?page=savi_vw_pro_view_profile&id=$ID&post_type=$type' class='google_link'>View Details</a>";
*/
	//return $actions array
    return $actions;
}
/*
-------------CREATE DEFAULT CONTENT in custom post types -------------
*/
add_filter( 'default_content', 'custom_post_editor_content', 10, 2 );
function custom_post_editor_content( $content, $post ) {
    //ob_start();
    switch( $post->post_type ) {
        case 'av_unit':
            $file = get_stylesheet_directory()."/html/content_unit.html";
            //include $file;
            //$content =ob_get_contents();
            $content = file_get_contents($file);
        break;
        case 'av_project':
            $file = get_stylesheet_directory()."/html/content_project.html";
            $content = file_get_contents($file);
        break;
        case 'av_opportunity':
            $file = get_stylesheet_directory()."/html/content_opportunity.html";
            $content = file_get_contents($file);
        break;
        default:
            $content = '';
        break;
    }
    //ob_end_clean();
    return $content;
}


function savi_is_plugin_active( $plugin ) {
    return in_array( $plugin, (array) get_option( 'active_plugins', array() ) );
}

if ( savi_is_plugin_active( 'all-in-one-event-calendar/all-in-one-event-calendar.php' ) ) {
delete_option( 'ai1ec_deactivate_message' );
add_action( 'init', 'ai1ec_post_type_init', 99 );
add_action( 'init', 'ai1ec_custom_categories_init', 100 );
add_action( 'init', 'ai1ec_custom_tags_init', 101 );
add_action( 'add_meta_boxes', 'ai1ec_custom_event_meta_box_container', 102);
add_action( 'save_post', 'ai1ec_custom_event_save_postdata',1,2); 

}
function ai1ec_post_type_init() {
	
	if ( function_exists( 'ai1ec_initiate_constants' ) && defined( 'AI1EC_POST_TYPE' ) ) :
	
        // globalize the post types array and some plugin settings we'll need
        global $wp_post_types, $ai1ec_settings, $ai1ec_app_helper;
        global $submenu;
        // unset the original post type created by the plugin
        unset( $wp_post_types[ AI1EC_POST_TYPE ] );
		 //$labels_tax = &$wp_taxonomies['events_tags']->labels;
	//	$submenu['']

		$labels = array(
			'name'               => Ai1ec_I18n::_x( 'Events', 'Custom post type name' ),
			'singular_name'      => Ai1ec_I18n::_x( 'Events', 'Custom post type name (singular)' ),
			'add_new'            => Ai1ec_I18n::__( 'Add New Workshop' ),
			'add_new_item'       => Ai1ec_I18n::__( 'Add New Workshop' ),
			'edit_item'          => Ai1ec_I18n::__( 'Edit Workshop & Seminar' ),
			'new_item'           => Ai1ec_I18n::__( 'New Workshop & Seminar' ),
			'view_item'          => Ai1ec_I18n::__( 'View Workshop & Seminar' ),
			'search_items'       => Ai1ec_I18n::__( 'Search Workshop & Seminar' ),
			'not_found'          => Ai1ec_I18n::__( 'No Workshops & Seminars found' ),
			'not_found_in_trash' => Ai1ec_I18n::__( 'No Workshops & Seminars found in Trash' ),
			'parent_item_colon'  => Ai1ec_I18n::__( 'Parent Work Shop & Seminar' ),
			'menu_name'          => Ai1ec_I18n::__( 'Events' ),
			'all_items'          => 'All Events',
		);
			
		$supports = array( 'title', 'editor', 'custom-fields', 'thumbnail' );
		
		$args = array(
			'labels'              => $labels,
			'public'              => true,
			'publicly_queryable'  => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'query_var'           => true,
			'rewrite'             => array( 'slug' => 'ai1ec_event' ),
			'map_meta_cap'        => true,
			'capability_type'     => 'ai1ec_event',
			'has_archive'         => true,
			'hierarchical'        => false,
			'menu_position'       => 5,
			'supports'            => $supports,
			'exclude_from_search' 	=> $ai1ec_settings->exclude_from_search,
		);
		
		register_post_type( AI1EC_POST_TYPE, $args );
		
             //   unset( $wp_taxonomies[ 'events_tags' ] );
		
	endif; // AI1EC_POST_TYPE // ai1ec_initiate_constants()
 
}
function ai1ec_custom_categories_init() {
	
	
      // ========================================
		// = labels for event Work Areas categories taxonomy =
		// ========================================
		$work_areas_events_categories_labels = array(
		 'name'                      => Ai1ec_I18n::_x( 'Work Areas', 'Work Areas taxonomy' ),
		'singular_name'              => Ai1ec_I18n::_x( 'Work Area', 'Work Areas taxonomy (singular)' ),
		'menu_name'                  => __( 'Work Areas' ),
		'all_items'                  => __( 'All Work Areas' ),
		'parent_item'                => __( 'Parent Work Area' ),
		'parent_item_colon'          => __( 'Parent Work Area:' ),
		'new_item_name'              => __( 'New Work Area' ),
		'add_new_item'               => __( 'Add new work area' ),
		'edit_item'                  => __( 'Edit work area' ),
		'update_item'                => __( 'Update work area' ),
		'separate_items_with_commas' => __( 'Separate Work Areas with commas' ),
		'search_items'               => __( 'Search Work Areas' ),
		'add_or_remove_items'        => __( 'Add or remove Work Area' ),
		'choose_from_most_used'      => __( 'Choose from the most used Work Areas' )
		);
     
        // ========================================
		// = labels for event Work Areas categories taxonomy =
		// ========================================  
       
       $work_type_events_categories_labels= array(
		'name'                       => Ai1ec_I18n::_x( 'Work Type', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => Ai1ec_I18n::_x( 'Work Type', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Work Type', 'text_domain' ),
		'all_items'                  => __( 'All Work Type', 'text_domain' ),
		'parent_item'                => __( 'Parent Work Type', 'text_domain' ),
		'parent_item_colon'          => __( 'Parent Work Type:', 'text_domain' ),
		'new_item_name'              => __( 'New Work Type', 'text_domain' ),
		'add_new_item'               => __( 'Add new work type', 'text_domain' ),
		'edit_item'                  => __( 'Edit work type', 'text_domain' ),
		'update_item'                => __( 'Update work type', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate Work Type with commas', 'text_domain' ),
		'search_items'               => __( 'Search Work Type', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove Work Type', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from the most used Work Type', 'text_domain' ),
	);
	
		// ======================================
		// = args for event Work Areas categories taxonomy =
		// ======================================
		$work_areas_events_categories_args = array(
			'labels'       => $work_areas_events_categories_labels,
			'hierarchical' => true,
			'rewrite'      => array( 'slug' => 'savi_events_cat_work_area' ),
			'capabilities' => array(
				'manage_terms' => 'manage_events_categories',
				 'add_new_iterm' => __( 'Add new work area' ),
				'edit_terms'   => 'manage_events_categories',
				'delete_terms' => 'manage_events_categories',
				'assign_terms' => 'edit_ai1ec_events'
			)
		);
		
		// ======================================
		// = args for event Work Type categories taxonomy =
		// ======================================
		$work_type_events_categories_args = array(
			'labels'       => $work_type_events_categories_labels,
			'hierarchical' => true,
			'rewrite'      => array( 'slug' => 'savi_events_cat_work_type' ),
			'capabilities' => array(
				'manage_terms' => 'manage_events_categories',
				 'add_new_iterm' => __( 'Add new work area' ),
				'edit_terms'   => 'manage_events_categories',
				'delete_terms' => 'manage_events_categories',
				'assign_terms' => 'edit_ai1ec_events'
			)
		);

		// ======================================
		// = register event work areas categories taxonomy =
		// ======================================
	/*	register_taxonomy(
			'savi_events_cat_work_area',
			array( AI1EC_POST_TYPE ),
			$work_areas_events_categories_args
		); */
		// ======================================
		// = register event work areas categories taxonomy =
		// ======================================
	/*	register_taxonomy(
			'savi_events_cat_work_type',
			array( AI1EC_POST_TYPE ),
			$work_type_events_categories_args
		);     */    
}
function ai1ec_custom_tags_init() {
	
		// ==================================
		// = labels for event langauge tag taxonomy =
		// ==================================
	
	$languages_events_tag_labels = array(
		'name'                       => Ai1ec_I18n::_x( 'Languages', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => Ai1ec_I18n::_x( 'Language', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Languages', 'text_domain' ),
		'all_items'                  => __( 'All languages', 'text_domain' ),
		'parent_item'                => __( 'Parent language', 'text_domain' ),
		'parent_item_colon'          => __( 'Parent language:', 'text_domain' ),
		'new_item_name'              => __( 'New language', 'text_domain' ),
		'add_new_item'               => __( 'Add new languages', 'text_domain' ),
		'edit_item'                  => __( 'Edit languages', 'text_domain' ),
		'update_item'                => __( 'Update languages', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate languages with commas', 'text_domain' ),
		'search_items'               => __( 'Search languages', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove languages', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from the most used languages', 'text_domain' ),
	);
	
	// ==================================
		// = labels for event Intended Audience tag taxonomy =
		// ==================================
	
	$intended_audience_events_tag_labels = array(
		'name'                       => Ai1ec_I18n::_x( 'Intended Audiences', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => Ai1ec_I18n::_x( 'Intended Audience', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Intended Audiences', 'text_domain' ),
		'all_items'                  => __( 'All Intended Audiences', 'text_domain' ),
		'parent_item'                => __( 'Parent Intended Audience', 'text_domain' ),
		'parent_item_colon'          => __( 'Parent Intended Audience:', 'text_domain' ),
		'new_item_name'              => __( 'New Intended Audience', 'text_domain' ),
		'add_new_item'               => __( 'Add new Intended Audience', 'text_domain' ),
		'edit_item'                  => __( 'Edit Intended Audience', 'text_domain' ),
		'update_item'                => __( 'Update Intended Audience', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate Intended Audiences with commas', 'text_domain' ),
		'search_items'               => __( 'Search Intended Audiences', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove Intended Audience', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from the most used Intended Audience', 'text_domain' ),
	);
	
	// ================================
		// = args for event feeds taxonomy =
		// ================================
		$languages_events_tag_args = array(
			'labels'       => $languages_events_tag_labels,
			'hierarchical' => false,
			'rewrite'      => array( 'slug' => 'savi_events_tag_languages' ),
			'capabilities' => array(
				'manage_terms' => 'manage_events_categories',
				'edit_terms'   => 'manage_events_categories',
				'delete_terms' => 'manage_events_categories',
				'assign_terms' => 'edit_ai1ec_events'
			)
		);
		
		
		// ================================
		// = args for event Intended Audience taxonomy =
		// ================================
		$intended_audience_events_tag_args = array(
			'labels'       => $intended_audience_events_tag_labels,
			'hierarchical' => false,
			'rewrite'      => array( 'slug' => 'savi_events_tag_int_aud' ),
			'capabilities' => array(
				'manage_terms' => 'manage_events_categories',
				'edit_terms'   => 'manage_events_categories',
				'delete_terms' => 'manage_events_categories',
				'assign_terms' => 'edit_ai1ec_events'
			)
		);

    // ================================
		// = register event language tags taxonomy =
		// ================================
		register_taxonomy(
			'savi_events_tag_languages',
			array( AI1EC_POST_TYPE ),
			$languages_events_tag_args
		);
		
		// ================================
		// = register event Intended Audience tags taxonomy =
		// ================================
		register_taxonomy(
			'savi_events_tag_int_aud',
			array( AI1EC_POST_TYPE ),
			$intended_audience_events_tag_args
		);
                
                $savi_events_tag_labels = array(
		'name'                       => Ai1ec_I18n::_x( 'Event Types', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => Ai1ec_I18n::_x( 'Event Type', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Event Type', 'text_domain' ),
		'all_items'                  => __( 'All Event Types', 'text_domain' ),
		'parent_item'                => __( 'Parent Event Type', 'text_domain' ),
		'parent_item_colon'          => __( 'Parent Event Type:', 'text_domain' ),
		'new_item_name'              => __( 'New Event Type', 'text_domain' ),
		'add_new_item'               => __( 'Add new Event Type', 'text_domain' ),
		'edit_item'                  => __( 'Edit Event Type', 'text_domain' ),
		'update_item'                => __( 'Update Event Type', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate Event Types with commas', 'text_domain' ),
		'search_items'               => __( 'Search Event Types', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove Event Type', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from the most used Event Type', 'text_domain' ),
	);
	
	// ================================
		// = args for event feeds taxonomy =
		// ================================
		$savi_events_tag_args = array(
			'labels'       => $savi_events_tag_labels,
			'hierarchical' => false,
			'rewrite'      => array( 'slug' => 'events-tags' ),
			'capabilities' => array(
				'manage_terms' => 'manage_events_categories',
				'edit_terms'   => 'manage_events_categories',
				'delete_terms' => 'manage_events_categories',
				'assign_terms' => 'edit_ai1ec_events'
			)
		);
	        // ================================
		// = register event tags
		// ================================
	/*	register_taxonomy(
			'events_tags',
			array( AI1EC_POST_TYPE ),
			$savi_events_tag_args
		);*/
}
function ai1ec_custom_event_meta_box_container() {
	       remove_meta_box( 'commentsdiv',AI1EC_POST_TYPE,'normal' );
	add_meta_box(
			AI1EC_POST_TYPE."_2",
			Ai1ec_I18n::__( 'Work shop & Seminar Details' ),
			'meta_box__details_view',
			AI1EC_POST_TYPE,
			'normal',
			'high'
		);

		add_meta_box(
			AI1EC_POST_TYPE."_3",
			Ai1ec_I18n::__( 'Work shop & Seminar Description' ),
			'meta_box_description_view',
			AI1EC_POST_TYPE,
			'normal',
			'high'
		);
		add_meta_box(
			AI1EC_POST_TYPE."_4",
			Ai1ec_I18n::__( 'Admin' ),
			'meta_box_admin_view',
			AI1EC_POST_TYPE,
			'normal',
			'high'
		);
}
function meta_box_description_view() {
	//global $post;
		 $post_id = $_GET['post'];//$wp_query->post->ID;
        $postmetaArray = get_post_meta($post_id);
      //echo "<pre>",print_r($postmetaArray),"</pre>";
        if (sizeof($postmetaArray) > 0) {
         	$saved_savi_events_description_purpose = $postmetaArray['savi_events_description_purpose'][0];         
        		$saved_savi_events_description_domain_content = $postmetaArray['savi_events_description_domain_content'][0];
        		$saved_savi_events_description_approach = $postmetaArray['savi_events_description_approach'][0];
        		$saved_savi_events_description_team = $postmetaArray['savi_events_description_team'][0];
        		$saved_savi_events_description_other_sources_of_funding = $postmetaArray['savi_events_description_other_sources_of_funding'][0];
        		$saved_savi_events_description_cost_of_description = $postmetaArray['savi_events_description_cost_of_description'][0];
        		$saved_savi_events_description_assesment = $postmetaArray['savi_events_description_assesment'][0];
        }
        else {
            $saved_savi_events_description_purpose = "";         
        		$saved_savi_events_description_domain_content = "";
        		$saved_savi_events_description_approach = "";
        		$saved_savi_events_description_team = "";
        		$saved_savi_events_description_other_sources_of_funding = "";
        		$saved_savi_events_description_cost_of_description = "";
        		$saved_savi_events_description_assesment = "";
        		
        }
            echo "<div class='disp-row rwmb-textarea-wrapper'>";
            
                echo " <div class='rwmb-label'>";
                    echo "<label for='Purpose'>Purpose</label>\n";
                echo " </div>";
                echo "<div class='rwmb-input'>\n";
                  echo "<textarea rows='4' cols='50' name='savi_events_description_purpose' id='savi_events_description_purpose' 
                        class='rwmb-textarea large-text'>$saved_savi_events_description_purpose</textarea>";
                echo "</div>";
            echo "</div>";
 
             echo "<div class='disp-row'>";
            echo " <div class='rwmb-label'>";
                echo "<label for='domain_content'>Domain & Content</label>\n";
            echo " </div>";
            echo "<div class='rwmb-input'>\n";
               echo "<textarea rows='4' cols='50' name='savi_events_description_domain_content' 
                       id='savi_events_description_domain_content' 
                        class='rwmb-textarea large-text'>$saved_savi_events_description_domain_content</textarea>";
            echo "</div>";
        echo "</div>";
        
         echo "<div class='disp-row'>";
            echo " <div class='rwmb-label'>";
                echo "<label for='Approach'>Approach</label>\n";
            echo " </div>";
            echo "<div class='rwmb-input'>\n";
               echo "<textarea rows='4' cols='50' name='savi_events_description_approach' 
                       id='savi_events_description_approach' 
                        class='rwmb-textarea large-text'>$saved_savi_events_description_approach</textarea>";
            echo "</div>";
        echo "</div>";  
          echo "<div class='disp-row'>";
            echo " <div class='rwmb-label'>";
                echo "<label for='team'>Team</label>\n";
            echo " </div>";
            echo "<div class='rwmb-input'>\n";
               echo "<textarea rows='4' cols='50' name='savi_events_description_team' 
                       id='savi_events_description_team' 
                        class='rwmb-textarea large-text'>$saved_savi_events_description_team</textarea>";
            echo "</div>";
        echo "</div>";  
        echo "<div class='disp-row'>";
            echo " <div class='rwmb-label'>";
                echo "<label for='funding'>Other sources of funding</label>\n";
            echo " </div>";
            echo "<div class='rwmb-input'>\n";
               echo "<textarea rows='4' cols='50' name='savi_events_description_other_sources_of_funding' 
                       id='savi_events_description_other_sources_of_funding' 
                        class='rwmb-textarea large-text'>$saved_savi_events_description_other_sources_of_funding</textarea>";
            echo "</div>";
        echo "</div>"; 
         echo "<div class='disp-row'>";
            echo " <div class='rwmb-label'>";
                echo "<label for='Description_of_cost'>Description of cost</label>\n";
            echo " </div>";
            echo "<div class='rwmb-input'>\n";
               echo "<textarea rows='4' cols='50' name='savi_events_description_cost_of_description' 
                       id='savi_events_description_cost_of_description' 
                        class='rwmb-textarea large-text'>$saved_savi_events_description_cost_of_description</textarea>";
            echo "</div>";
        echo "</div>"; 
         echo "<div class='disp-row'>";
            echo " <div class='rwmb-label'>";
                echo "<label for='assesment'>Assesment</label>\n";
            echo " </div>";
            echo "<div class='rwmb-input'>\n";
               echo "<textarea rows='4' cols='50' name='savi_events_description_assesment' 
                       id='savi_events_description_assesment' 
                        class='rwmb-textarea large-text'>$saved_savi_events_description_assesment</textarea>";
            echo "</div>";
        echo "</div>";  
	}
function meta_box_admin_view() {
	//global $post;
	 
		 $post_id = $_GET['post'];//$wp_query->post->ID;
        $postmetaArray = get_post_meta($post_id);
        if (sizeof($postmetaArray) > 0) {
         	$saved_savi_events_admin_timestamp_comments = $postmetaArray['savi_events_admin_timestamp_comments'][0];         
        	
        }
        else {
            $saved_savi_events_admin_timestamp_comments = "";   
               
          	
        }
        if(!isset($_GET['post'])) :
            $saved_savi_events_type = ($_REQUEST['event_type'] == 'seminar')?"seminar":"workshop";	
        else :
            
            $saved_savi_events_type = get_post_meta($post_id,'savi_events_type',true);
        endif;  
            echo "<div class='disp-row rwmb-textarea-wrapper'>";
            
                echo " <div class='rwmb-label'>";
                    echo "<label for='admin_notes_revisions'>Admin notes/Revisions</label>\n";
                echo " </div>";
                echo "<div class='rwmb-input'>\n";
                  echo "<textarea rows='4' cols='50' name='savi_events_admin_timestamp_comments'
                        id='savi_events_admin_timestamp_comments' 
                        class='rwmb-textarea large-text'>$saved_savi_events_admin_timestamp_comments</textarea>";
                echo "</div>";
                echo "<div class='rwmb-input'>\n";
                  echo "<input type='hidden' name='savi_events_type'
                        id='savi_events_type' value='". $saved_savi_events_type."' />";
                echo "</div>";
            echo "</div>";
 
            
	}
function meta_box__details_view() {
	//global $post;
	 $post_id = $_GET['post'];//$wp_query->post->ID;
        $postmetaArray = get_post_meta($post_id);
        if (sizeof($postmetaArray) > 0):
         	$saved_savi_events_details_abbreviation = $postmetaArray['savi_events_details_abbreviation'][0];   
         	$saved_savi_events_details_av_unit = $postmetaArray['savi_events_details_av_unit'][0];
            $saved_savi_events_details_projectname = $postmetaArray['savi_events_details_projectname'][0]; 
            $saved_savi_events_details_max_participants = $postmetaArray['savi_events_details_max_participants'][0];      
            $saved_savi_events_details_min_participants = $postmetaArray['savi_events_details_min_participants'][0];      
            $saved_savi_events_details_duration = $postmetaArray['savi_events_details_duration'][0];          
            $saved_savi_events_details_prerequisites = $postmetaArray['savi_events_details_prerequisites'][0];          
       else:
           $saved_savi_events_details_abbreviation = "";         
           $saved_savi_events_details_av_unit      = "";
           $saved_savi_events_details_projectname  = "";   
           $saved_savi_events_details_max_participants = "";      
           $saved_savi_events_details_min_participants = "";      
           $saved_savi_events_details_duration = "";             
           $saved_savi_events_details_prerequisites = "";             
       endif;
       $AVUnitQuery = new WP_Query( array(
                    'post_type' => 'av_unit',
                    'posts_per_page' => -1,
                    'post_status' => array( 'publish' ),
                    ));
        $postIndex = 0;
        $projIndex = 0;
       // echo"<pre>",print_r($AVUnitQuery),"</pre>";
        // Start constructing the Select options for AV Units
        $unitSelectHTML = "<select class='combobox' name='savi_events_details_av_unit' id='savi_events_details_av_unit' onchange='unitChanged()' name='inline'> \n<option></option>\n";

        if ($AVUnitQuery->found_posts > 0) {
            while ($AVUnitQuery->have_posts()) {
                $AVUnitQuery->the_post();
                $unitname = get_the_title();
				    $unitID = get_the_ID();
                $firstrow = true;

                // Construct the options for the select for AV Unit
                $unitSelectHTML .= "<option value='$unitID'";

                // If the exisitng value of the AV Unit is equal to the current loop value then select the option
                if ($unitID == $saved_savi_events_details_av_unit  ||  $unitname == $saved_savi_events_details_av_unit)
                    $unitSelectHTML .= "selected";

                // End the Construct for the option
                $unitSelectHTML .= ">$unitname</option>\n";
                $unitProjectArray[] = new unitProject($unitID, 0);
            }

        }
     //echo"<pre>",print_r($unitProjectArray),"</pre>";
        $unitSelectHTML .= "</Select>";
        wp_reset_query();
       // echo"<pre>",print_r($unitProjectArray),"</pre>".sizeof( $AVUnitQuery);
        // Now we start on the Projects - The confusing mama ....
        $AVProjectQuery = new WP_Query( array(
                    'post_type' => 'av_project',
                    'posts_per_page' => -1,
                    'post_status' => array( 'publish' ),
                    ));


        // First we assemble the list of projects and the associated AV Units in an object Array
        if ($AVProjectQuery->found_posts > 0) {
            while ($AVProjectQuery->have_posts()) {
                $AVProjectQuery->the_post();
                $projname = get_the_title();
                $postID = get_the_ID();

                $parentUnitsMeta = get_post_meta($postID, 'parent_unit', false);
                $allParentUnits = $parentUnitsMeta[0];
                if (sizeof($allParentUnits) > 0) {
                    foreach($allParentUnits as $key=>$parentUnits) {
                        $unitProjectArray[] = new unitProject($parentUnits['parent_unit'], $postID);
                    }

                }
            }
          wp_reset_query();
        }

        // Now We add a "General" project to all the unitSelectHTML


        // Get the Object Array sorted on UnitName
        //usort($unitProjectArray, 'psort');

        if (sizeof($unitProjectArray) > 0) {
            foreach ($unitProjectArray as $key => $row) {
              $unit[$key]  = $row->unit;
              $project[$key] = $row->project;
            }

            array_multisort($unit, SORT_ASC, $project, SORT_ASC, $unitProjectArray);
        }

        // Start Constructing the select options for all projects (to be used as the template when the user select any AV Unit
        $projhiddenSelectHTML = "<select style='display:none;' id='projectname_all'>\n";
        $projSelectHTML = "<select class='combobox' id='savi_events_details_projectname' name='savi_events_details_projectname'> \n<option></option>\n";
        $pr_avUnitName = "";

        for ($arrayIndex=0;$arrayIndex < sizeof($unitProjectArray);$arrayIndex++) {
            $avUnitName = $unitProjectArray[$arrayIndex]->unit;
            $tmpUnit = str_replace(" ", "_", $avUnitName);
            $projID   = $unitProjectArray[$arrayIndex]->project; 
            $projname = ($unitProjectArray[$arrayIndex]->project) == 0?"General":get_post($unitProjectArray[$arrayIndex]->project)->post_title;
            if ($avUnitName == $saved_savi_events_details_av_unit) {
                $projSelectHTML .= "<option value='$projID'";
                if ($projID == $saved_savi_events_details_projectname) {
                    $projSelectHTML .= "selected";
                }
                // End the construct of the option
                $projSelectHTML .= ">$projname</option>\n";
            }


            if ($avUnitName != $pr_avUnitName) {
                if (!firstrow) {
                    $projhiddenSelectHTML .= "</optgroup>\n";
                }

                $projhiddenSelectHTML .= "<optgroup id='optgroup{$tmpUnit}'>";
                // $projhiddenSelectHTML .= "<option value='General'>General</option>\n";
                $pr_avUnitName = $avUnitName;
            }

            $projhiddenSelectHTML .= "<option value='$projID'>$projname</option>\n";
        }

        $projhiddenSelectHTML .= "</select>";

        wp_reset_query();

       echo "<div class='disp-row'>";
            
                echo " <div class='rwmb-label'>";
                    echo "<label for='Abbreviation'>Abbreviation</label>\n";
                echo " </div>";
                 echo "<div class='input'>\n";
                 echo "<input type='text' name='savi_events_details_abbreviation' id='savi_events_details_abbreviation'
                     value='$saved_savi_events_details_abbreviation' />\n";
                echo "</div>";
     echo "</div>";
      // Field definition for AV Units
        echo "<div class='disp-row'>";
          echo " <div class='rwmb-label'>";
             echo "<label for='av_unit'>Unit filter</label>";
           echo "</div>";
            echo "<div class='rwmb-input'>\n";
                echo $unitSelectHTML; // We have constructed this earlier
                echo "<input type='hidden' name='previous_unit' value='$saved_AVUnit' />\n";
            echo "</div>";
        echo "</div>";

        // This is used for filtering the project name based on the AV Unit Selected
        echo $projhiddenSelectHTML;


        // Field Defintion for Project Name
        echo "<div class='disp-row'>";
          echo " <div class='rwmb-label'>";
            echo "<label for='project_name'>Project</label>\n";
          echo " </div>";
            echo "<div class='input'>\n";
                echo $projSelectHTML; // We have constructed this earlier
                 echo "<input type='hidden' name='previous_project' value='".($saved_projname ==""?0:$saved_projname)."' />\n"; 
            echo "</div>";
        echo "</div>"; 
       
     echo "<div class='disp-row'>";
            
                echo " <div class='rwmb-label'>";
                    echo "<label for='Maximum_particispants'>Maximum particispants</label>\n";
                echo " </div>";
                 echo "<div class='input'>\n";
                 echo "<input type='text' name='savi_events_details_max_participants' id='savi_events_details_max_participants'
                     value='$saved_savi_events_details_max_participants' />\n";
                echo "</div>";
     echo "</div>"; 
     
     echo "<div class='disp-row'>";
            
                echo " <div class='rwmb-label'>";
                    echo "<label for='Minimum_participants'>Minimum participants</label>\n";
                echo " </div>";
                 echo "<div class='input'>\n";
                 echo "<input type='text' name='savi_events_details_min_participants' id='savi_events_details_min_participants'
                     value='$saved_savi_events_details_min_participants' />\n";
                echo "</div>";
     echo "</div>"; 
     echo "<div class='disp-row'>";
            
                echo " <div class='rwmb-label'>";
                    echo "<label for='duration'>Duration</label>\n";
                echo " </div>";
                 echo "<div class='input'>\n";
                 echo "<input type='text' name='savi_events_details_duration' id='savi_events_details_duration'
                     value='$saved_savi_events_details_duration' />(days)\n";
                echo "</div>";
     echo "</div>"; 
      echo "<div class='disp-row'>";
            
                echo " <div class='rwmb-label'>";
                    echo "<label for='prerequisites'>Prerequisites</label>\n";
                echo " </div>";
                 echo "<div class='input'>\n";
                 echo "<input type='text' name='savi_events_details_prerequisites' id='savi_events_details_prerequisites'
                     value='$saved_savi_events_details_prerequisites' />\n";
                echo "</div>";
     echo "</div>"; 
        
          ?>

            <script>

            //jQuery(document).ready(function(){
               // jQuery('.combobox').combobox();
               // jQuery('#av_unit').combobox('selectByText', '<?php echo $saved_AVUnit; ?>');
               // jQuery("#projname").combobox('selectByText', '<?php echo $saved_projname ?>');
            //});

            function unitChanged() {
                // this is called when AV Unit is changed.
                $unitname = jQuery("#savi_events_details_av_unit").val();
                // Remove the current set of options in the Project Select
                jQuery("#projectname options").remove();
                if ($unitname != '') {
                    //
                    // Get the options from the hidden project selects which pertains to the current AV Unit
                    $unitname = $unitname.replace(" ", "_");
                    $newselect = jQuery("#optgroup"+$unitname).html();
                } else {
                    $newselect = "<option value=''> </option>";
                }

                // Assign the new set of options pertaining to the current AV Unit
                jQuery("#savi_events_details_projectname").html($newselect);
                jQuery('#savi_events_details_projectname').data('combobox').refresh();

            }
            </script>
        <?php




}
function ai1ec_custom_event_save_postdata() {
	global  $post;
	if( get_post_type() != "ai1ec_event" ) return $post->ID;
 
      $savi_events_details_abbreviation = sanitize_text_field( $_POST['savi_events_details_abbreviation'] );      
      $savi_events_details_av_unit = sanitize_text_field( $_POST['savi_events_details_av_unit'] );      
      $savi_events_details_projectname = sanitize_text_field( $_POST['savi_events_details_projectname'] ); 
      $savi_events_details_max_participants =sanitize_text_field( $_POST['savi_events_details_max_participants']);      
      $savi_events_details_min_participants = sanitize_text_field( $_POST['savi_events_details_min_participants']);      
      $savi_events_details_duration = sanitize_text_field( $_POST['savi_events_details_duration']);          
      $savi_events_details_prerequisites = sanitize_text_field( $_POST['savi_events_details_prerequisites']);
                 
      $savi_events_description_purpose = sanitize_text_field( $_POST['savi_events_description_purpose'] );      
      $savi_events_description_domain_content = sanitize_text_field( $_POST['savi_events_description_domain_content'] );      
      $savi_events_description_approach = sanitize_text_field( $_POST['savi_events_description_approach'] );      
      $savi_events_description_team = sanitize_text_field( $_POST['savi_events_description_team'] );  
      $savi_events_description_other_sources_of_funding = sanitize_text_field($_POST['savi_events_description_other_sources_of_funding']);
      $savi_events_description_cost_of_description = sanitize_text_field($_POST['savi_events_description_cost_of_description']);
      $savi_events_description_assesment = sanitize_text_field($_POST['savi_events_description_assesment']);
        		    
      $savi_events_admin_timestamp_comments = sanitize_text_field($_POST['savi_events_admin_timestamp_comments']);      
       
      update_post_meta( $post->ID, 'savi_events_details_abbreviation', $savi_events_details_abbreviation);
      update_post_meta( $post->ID, 'savi_events_details_av_unit', $savi_events_details_av_unit);
      update_post_meta( $post->ID, 'savi_events_details_projectname',$savi_events_details_projectname );
      update_post_meta( $post->ID, 'savi_events_details_max_participants' ,$savi_events_details_max_participants);      
      update_post_meta( $post->ID, 'savi_events_details_min_participants' , $savi_events_details_min_participants);      
      update_post_meta( $post->ID, 'savi_events_details_duration' ,$savi_events_details_duration);          
      update_post_meta( $post->ID, 'savi_events_details_prerequisites' , $savi_events_details_prerequisites);
      
      update_post_meta( $post->ID, 'savi_events_description_purpose', $savi_events_description_purpose);
      update_post_meta( $post->ID, 'savi_events_description_domain_content', $savi_events_description_domain_content);
      update_post_meta( $post->ID, 'savi_events_description_approach', $savi_events_description_approach);
      update_post_meta( $post->ID, 'savi_events_description_team', $savi_events_description_team);
      update_post_meta( $post->ID, 'savi_events_description_other_sources_of_funding',$savi_events_description_other_sources_of_funding);
      update_post_meta( $post->ID, 'savi_events_description_cost_of_description' , $savi_events_description_cost_of_description);
      update_post_meta( $post->ID, 'savi_events_description_assesment', $savi_events_description_assesment);
      
      update_post_meta( $post->ID, 'savi_events_admin_timestamp_comments', $savi_events_admin_timestamp_comments);
    
       $savi_events_type= ($_REQUEST['savi_events_type'] =='seminar' )?"seminar":"workshop";
       update_post_meta( $post->ID, 'savi_events_type', $savi_events_type);
    
      
	
}  
add_action( 'admin_menu', 'savi_custom_add_menu_page' );
add_action( 'admin_menu', 'savi_custom_add_submenu_pages' );
/*=========================editor view menus ==========================================================================*/
add_action( 'admin_menu', 'savi_custom_editor_add_menu_page' );
add_action( 'admin_menu', 'savi_custom_editor_add_submenu_pages' );

function savi_custom_add_menu_page(){
	add_menu_page( __( 'Enquires', 'savi' ), __( 'Enquiries', 'savi' ), 'manage_options','main-menu_page', '','',3 );
}
function savi_custom_add_submenu_pages() {
	add_submenu_page( 'main-menu_page', __( 'Intial Enquiry', 'savi' ), __( 'Intial Enquiry', 'savi' ), 'manage_options', 'view_0_show_posts', '' );
	add_submenu_page( 'main-menu_page', __( 'Profile Reviews', 'savi' ), __( 'Profile Reviews', 'savi' ), 'manage_options', 'view_1_show_posts', '' );
	add_submenu_page( 'main-menu_page', __( 'Opportunity Reviews', 'savi' ), __( 'Opporttunity Reviews', 'savi' ), 'manage_options', 'view_2_show_posts', '' );
	add_submenu_page( 'main-menu_page', __( 'Opportunities Selection', 'savi' ), __( 'Opporttunities Selection', 'savi' ), 'manage_options', 'view_3_show_posts', '' );
	add_submenu_page( 'main-menu_page', __( 'Dormant', 'savi' ), __( 'Dormant', 'savi' ), 'manage_options', 'view_4_show_posts', '' );
	add_submenu_page( 'main-menu_page', __( 'Pre-Visa', 'savi' ), __( 'Pre-Visa', 'savi' ), 'manage_options', 'view_5_show_posts', '' );
	add_submenu_page( 'main-menu_page', __( 'Confirm Visa', 'savi' ), __( 'Confirm Visa', 'savi' ), 'manage_options', 'view_6_show_posts', '' );
	add_submenu_page( 'main-menu_page', __( 'Arrival', 'savi' ), __( 'Arrival', 'savi' ), 'manage_options', 'view_7_show_posts', '' );

}
function savi_custom_editor_add_menu_page(){
	add_menu_page( __( 'Enquires', 'savi' ), __( 'Enquiries', 'savi' ), 'editor','main-menu_page', '','',3 );
}
function savi_custom_editor_add_submenu_pages() {
	add_submenu_page( 'main-menu_page', __( 'Intial Enquiry', 'savi' ), __( 'Intial Enquiry', 'savi' ), 'editor', 'view_0_show_posts', '' );
	add_submenu_page( 'main-menu_page', __( 'Profile Reviews', 'savi' ), __( 'Profile Reviews', 'savi' ), 'editor', 'view_1_show_posts', '' );
	add_submenu_page( 'main-menu_page', __( 'Opportunity Reviews', 'savi' ), __( 'Opporttunity Reviews', 'savi' ), 'editor', 'view_2_show_posts', '' );
	add_submenu_page( 'main-menu_page', __( 'Opportunities Selection', 'savi' ), __( 'Opporttunities Selection', 'savi' ), 'editor', 'view_3_show_posts', '' );
	add_submenu_page( 'main-menu_page', __( 'Dormant', 'savi' ), __( 'Dormant', 'savi' ), 'editor', 'view_4_show_posts', '' );
	add_submenu_page( 'main-menu_page', __( 'Pre-Visa', 'savi' ), __( 'Pre-Visa', 'savi' ), 'editor', 'view_5_show_posts', '' );
	add_submenu_page( 'main-menu_page', __( 'Confirm Visa', 'savi' ), __( 'Confirm Visa', 'savi' ), 'editor', 'view_6_show_posts', '' );
	add_submenu_page( 'main-menu_page', __( 'Arrival', 'savi' ), __( 'Arrival', 'savi' ), 'editor', 'view_7_show_posts', '' );

}
add_action('admin_head', 'savi_custom_correct_current_menu', 50);
function savi_custom_correct_current_menu(){
	$screen = get_current_screen();
	$screenID = $screen->id;
        $type = $_REQUEST['event_type'];
	?>
	<script type="text/javascript">
	jQuery(document).ready(function($) {
		var screen_id ='<?php echo $screenID ?>';
                var type ='<?php echo $type ?>';
		var post_type = ["view_0", "view_1", "view_2", "view_3", "view_4", "view_5", "view_6", "view_7", "edit-view_0"
		          , "edit-view_1", "edit-view_2", "edit-view_3", "edit-view_4", "edit-view_5", "edit-view_6", "edit-view_7"]; // ETC...
		
		jQuery('#toplevel_page_main-menu_page > a').attr('href','edit.php?post_type=view_0');
		jQuery('a[href$="view_0_show_posts"]').addClass("wp-menu-image dashicons-before dashicons-info");
		jQuery('a[href$="view_1_show_posts"]').addClass("wp-menu-image dashicons-before dashicons-id");
		jQuery('a[href$="view_2_show_posts"]').addClass("wp-menu-image dashicons-before dashicons-format-aside");
		jQuery('a[href$="view_3_show_posts"]').addClass("wp-menu-image dashicons-before dashicons-yes");
		jQuery('a[href$="view_4_show_posts"]').addClass("wp-menu-image dashicons-before dashicons-clock");
		jQuery('a[href$="view_5_show_posts"]').addClass("wp-menu-image dashicons-before dashicons-admin-site");
		jQuery('a[href$="view_6_show_posts"]').addClass("wp-menu-image dashicons-before dashicons-editor-help");
		jQuery('a[href$="view_7_show_posts"]').addClass("wp-menu-image dashicons-before dashicons-groups");
		
		jQuery('a[href$="view_0_show_posts"]').attr('href','edit.php?post_type=view_0');
		jQuery('a[href$="view_1_show_posts"]').attr('href','edit.php?post_type=view_1');
		jQuery('a[href$="view_2_show_posts"]').attr('href','edit.php?post_type=view_2');
		jQuery('a[href$="view_3_show_posts"]').attr('href','edit.php?post_type=view_3');
		jQuery('a[href$="view_4_show_posts"]').attr('href','edit.php?post_type=view_4');
		jQuery('a[href$="view_5_show_posts"]').attr('href','edit.php?post_type=view_5');
		jQuery('a[href$="view_6_show_posts"]').attr('href','edit.php?post_type=view_6');
		jQuery('a[href$="view_7_show_posts"]').attr('href','edit.php?post_type=view_7');
		
		if(jQuery.inArray(screen_id, post_type) != -1) {
			jQuery('#toplevel_page_main-menu_page').addClass('wp-has-current-submenu wp-menu-open menu-top menu-top-first').removeClass('wp-not-current-submenu');
			jQuery('#toplevel_page_main-menu_page > a').addClass('wp-has-current-submenu').removeClass('wp-not-current-submenu');
	   }
      if (screen_id.indexOf("edit") >= 0) 
		{
 			//string contains Hello
 			//alert(screen_id);
 			var arr = screen_id.split('-');
 		   var current_post_type = arr[1];
		}
	   else {
	       var current_post_type = screen_id;
		}
	   //alert(current_post_type);
	  
          if(current_post_type == "ai1ec_event" && type == "seminar"){
             jQuery('li#menu-posts-ai1ec_event ul.wp-submenu li').removeClass('current');
             jQuery('a[href$="post-new.php?post_type=ai1ec_event&event_type=seminar"]').parent().addClass('current');
             jQuery('a[href$="post-new.php?post_type=ai1ec_event&event_type=seminar"]').addClass('current');
           }		
	   else{
	          if(jQuery.inArray(screen_id, post_type) != -1) {
	              jQuery('a[href$="edit.php?post_type='+current_post_type+'"]').parent().addClass('current');
		jQuery('a[href$="edit.php?post_type='+current_post_type+'"]').addClass('current');
			
	           }
	      
	   }                                                              		 
      /*  jQuery("h3.hndle,div.handlediv").click(function () {
       
      	 	 if (jQuery(this).parents('.postbox').hasClass("closed")) {
	
						jQuery(this).parents('.postbox').removeClass("closed");
				 }
				else {
					  jQuery(this).parents('.postbox').addClass("closed");
				}
    		});     	*/
		jQuery("#mymetabox_ContactDetails").addClass("closed");
		jQuery("#mymetabox_StayDetails").addClass("closed");
		jQuery("#mymetabox_Motivations").addClass("closed");
		jQuery("#mymetabox_Skills").addClass("closed");
		jQuery("#mymetabox_EducationDetails").addClass("closed");
		jQuery("#mymetabox_ProfessionalExp").addClass("closed");
		jQuery("#mymetabox_VisaDetails").addClass("closed");
		jQuery("#mymetabox_InitialContact").addClass("closed");
		jQuery("#mymetabox_OtherDetails").addClass("closed");
		jQuery("#mymetabox_AdminDetails").addClass("closed");
		
		<?php if(($_REQUEST["taxonomy"] == "savi_opp_cat_work_area") && ($_REQUEST["post_type"] == "av_opportunity") ) :?>
		           
		    jQuery("#edittag .form-table #slug").attr('disabled','disabled');
		    jQuery("#posts-filter table.tags a.editinline").click(function(){
			       jQuery("input[name='slug']").attr('disabled','disabled');
		    });  
		<?php endif; ?>
		
	});
	
	</script>
	
	<?php
	
}
add_action( 'admin_menu', 'savi_custom_remove_menu_items' );

function savi_custom_remove_menu_items() {
global $submenu;

	remove_submenu_page( 'main-menu_page','main-menu_page' );
	remove_menu_page( 'edit.php?post_type=project');
	remove_menu_page( 'edit.php?post_type=view_0');
	remove_menu_page( 'edit.php?post_type=view_1');
	remove_menu_page( 'edit.php?post_type=view_2');
	remove_menu_page( 'edit.php?post_type=view_3');
	remove_menu_page( 'edit.php?post_type=view_4');
	remove_menu_page( 'edit.php?post_type=view_5');
	remove_menu_page( 'edit.php?post_type=view_6');
	remove_menu_page( 'edit.php?post_type=view_7');
	unset($submenu['edit.php?post_type=ai1ec_event'][15]);
        //unset($submenu['edit.php?post_type=ai1ec_event'][16]);
      //  remove_meta_box('tagsdiv-events_tags', 'ai1ec_event', 'side');
        add_submenu_page( 'edit.php?post_type=ai1ec_event', 'Add New Seminar', 'Add New Seminar', 'manage_categories', 'post-new.php?post_type=ai1ec_event&event_type=seminar','', 10); 
add_submenu_page( 'edit.php?post_type=ai1ec_event', 'Work Areas', 'Work Areas', 'manage_categories', 'edit-tags.php?taxonomy=savi_opp_cat_work_area&post_type=av_opportunity', '' );  

//echo"<pre>",print_r($submenu['edit.php?post_type=ai1ec_event']),"</pre>"; die;
}

add_action( 'admin_head', 'add_menu_icons_styles' );
function add_menu_icons_styles(){
?>
 
<style>
.dashicons, .dashicons-before:before{
padding-left:8px;
padding-right:8px;
}
</style>
 
<?php
}
function gravatar_url($id_or_email, $size) {
    if ($size==null) $size= 96;
    //id or email code borrowed from wp-includes/pluggable.php
    $email = '';
    if ( is_numeric($id_or_email) ) {
    $id = (int) $id_or_email;
    $user = get_userdata($id);
    if ( $user )
    $email = $user->user_email;
    } elseif ( is_object($id_or_email) ) {
    // No avatar for pingbacks or trackbacks
    $allowed_comment_types = apply_filters( 'get_avatar_comment_types', array( 'comment' ) );
    if ( ! empty( $id_or_email->comment_type ) && ! in_array( $id_or_email->comment_type, (array) $allowed_comment_types ) )
    return false;
    
    if ( !empty($id_or_email->user_id) ) {
    $id = (int) $id_or_email->user_id;
    $user = get_userdata($id);
    if ( $user)
    $email = $user->user_email;
    } elseif ( !empty($id_or_email->comment_author_email) ) {
    $email = $id_or_email->comment_author_email;
    }
    } else {
    $email = $id_or_email;
    }
    
    $hashkey = md5(strtolower(trim($email)));
    $uri = 'http://www.gravatar.com/avatar/' . $hashkey . '?d=404';
    
    $data = wp_cache_get($hashkey);
    if (false === $data) {
    $response = wp_remote_head($uri);
    if( is_wp_error($response) ) {
    $data = 'not200';
    } else {
    $data = $response['response']['code'];
    }
    wp_cache_set($hashkey, $data, $group = '', $expire = 60*5);
    
    }
    //print_r($response);
    if ($data == '200'){
        return 'http://www.gravatar.com/avatar/' . $hashkey . '?s='.$size;
    } else {
        return null;
    }
}
 add_action( 'admin_menu', 'savi_change_admin_menu' );
function savi_change_admin_menu() {
   
    //echo "<pre>",print_r($menu),"</pre>";
    //echo "<pre>",print_r($submenu),"</pre>";
    
    //let's add the work type category sub-menu to the post_type research
      register_taxonomy_for_object_type( 'savi_opp_cat_work_type', 'research' );
    //let's add the work type category sub-menu to the post_type research
    	register_taxonomy_for_object_type( 'savi_opp_cat_work_area', 'research' );
    	
    	    //let's add the work type category sub-menu to the post_type ai1ec_event
    //  register_taxonomy_for_object_type( 'savi_opp_cat_work_type', 'ai1ec_event' );
    //let's add the work type category sub-menu to the post_type ai1ec_event
    //	register_taxonomy_for_object_type( 'savi_opp_cat_work_area', 'ai1ec_event' );

    add_submenu_page( 'edit.php?post_type=research', 'Work Type', 'Work Type', 'manage_categories', 'edit-tags.php?taxonomy=savi_opp_cat_work_type&post_type=av_opportunity', '' );
    add_submenu_page( 'edit.php?post_type=research', 'Work Areas', 'Work Areas', 'manage_categories', 'edit-tags.php?taxonomy=savi_opp_cat_work_area&post_type=av_opportunity', '' );  
    
  /* 
    add_submenu_page( 'edit.php?post_type=ai1ec_event', 'Work Type', 'Work Type', 'manage_categories', 'edit-tags.php?taxonomy=savi_opp_cat_work_type&post_type=av_opportunity', '' );
    add_submenu_page( 'edit.php?post_type=ai1ec_event', 'Work Areas', 'Work Areas', 'manage_categories', 'edit-tags.php?taxonomy=savi_opp_cat_work_area&post_type=av_opportunity', '' );  
   */   
        
 }
 add_action( 'admin_menu', 'savi_page_settings_menu' );
 add_action( 'admin_init', 'savi_page_settings' );
function savi_page_settings() {
	$labels = array("Inital_Enquiry","Profile_Reviews","Opportunity_Selection","Opportunity_Confirmation",
                       "Reminder1","Reminder2","Reminder3","Pre_Visa_Entry_Letter","Pre_Visa","Confirm_Visa"
                       ,"Induction_Instructions","Volunteer_Profile_Offer","Instruction_for_Auroville_VISA_Application");
   for($i=0;$i<=count($labels);$i++){
     register_setting( 'default', $labels[$i] );   	
   	
   }
   register_setting( 'default', "test_mentor_email" );
    
}
function validate_page_options($input) {
 	
 	$iterms =array(
  						array("Inital Enquiry","Approval of Volunteer's Registration by the Adminstrator"),
  						array("Profile Reviews","When the profile has been approved and the volunteer is ready to select opportunities"),
  						array("Opportunity Selection","When the Opportunity has been selected for the volunteer"  ),
  						array("Opportunity Confirmation","When the Opportunity has been confirmed mail send to the volunteer"),
  						array("Reminder1","When the volunteer has not respond after approval of rergistration -reminder1"),
  						array("Reminder2","When the volunteer has not respond after approval of rergistration -reminder2"),
  						array("Reminder3","When the volunteer has not respond after approval of rergistration -reminder3"),
  						array("Pre Visa Entry Letter","Generate of Entry Letter"),
  						array("Pre Visa","Sending the soft copy of the Visa Letter to Voluteer"),
  						array("Confirm Visa","Confirmation of the Visa and fixing of the  Arrival Date. Informing what to expect upon arrival at Auroville (india of course))"),
  						array("Induction Instructions","Sending email of Induction instructions to the vlounteer"),
  						array("Volunteer Profile Offer","Sending email of mentor when opportunites reviews selected for the volunteer"),
  						array("Instruction for Auroville VISA application","Instruction to volunteer about the Auroville VISA application")
  );

        $valid = array();
        
        $pages_new = $input['custom_page_option'];
  		 //echo "<pre>",print_r($pages_new),"</pre>";
  		 for($i=0;$i<count($pages_new);$i++){
       
		   $array_pages[] = $pages_new[$i] ;  		 	
  		 	
  		 }
        $valid = $array_pages;
        return $valid;
    }

function savi_page_settings_menu (){

	add_options_page( 'Savi Settings', 'Savi Settings', 'manage_options', 'todo_page_options', 'savi_page_settings_pdf_email' );
}

function savi_page_settings_pdf_email() {
	$iterms =array(
  						array("Inital Enquiry","Approval of Volunteer's Registration by the Adminstrator"),
  						array("Profile Reviews","When the profile has been approved and the volunteer is ready to select opportunities"),
  						array("Opportunity Selection","When the Opportunity has been selected for the volunteer"  ),
  						array("Opportunity Confirmation","When the Opportunity has been confirmed mail send to the volunteer"),
  						array("Reminder1","When the volunteer has not respond after approval of rergistration -reminder1"),
  						array("Reminder2","When the volunteer has not respond after approval of rergistration -reminder2"),
  						array("Reminder3","When the volunteer has not respond after approval of rergistration -reminder3"),
  						array("Pre Visa Entry Letter","Generate of Entry Letter"),
  						array("Pre Visa","Sending the soft copy of the Visa Letter to Voluteer"),
  						array("Confirm Visa","Confirmation of the Visa and fixing of the  Arrival Date. Informing what to expect upon arrival at Auroville (india of course))"),
  						array("Induction Instructions","Sending email of Induction instructions to the vlounteer"),
  						array("Volunteer Profile Offer","Sending email of mentor when opportunites reviews selected for the volunteer"),
  						array("Instruction for Auroville VISA application","Instruction to volunteer about the Auroville VISA application")
  );
 $labels = array("Inital_Enquiry","Profile_Reviews","Opportunity_Selection","Opportunity_Confirmation",
                       "Reminder1","Reminder2","Reminder3","Pre_Visa_Entry_Letter","Pre_Visa","Confirm_Visa"
                       ,"Induction_Instructions","Volunteer_Profile_Offer","Instruction_for_Auroville_VISA_Application");
                       
  $pages_array = array();
   $pages = get_pages(array( 'post_status' => 'private')); 
 //  print_r($pages);
 $i =0;
   foreach ( $pages as $page ) {
  	
	$pages_array[$i]['title'] = $page->post_title;
	$pages_array[$i]['ID'] = $page->ID;
	$i++;
  }
 //echo"<pre>", print_r($pages_array),"</pre>";
	echo"<h1 style='padding:30px;'>Savi Settings For PDF or Email Page Template</h1>";
	echo '<form method="post" action="options.php"> ';
	 settings_fields('default'); 
	echo "<table class='wp-list-table widefat fixed posts' style='width:95%;margin-top:40px'>";
 	echo "<thead>";
  	echo "<tr>";
   echo "<th>View Table</th>";
   echo "<th>Description of content</th>";
   echo "<th>Page Template to be used</th>";
   echo "</tr>";
   echo "</thead>";
  	echo "<tfoot>";
  	echo "<tr>";
   echo "<th>View Table</th>";
   echo "<th>Description of content</th>";
   echo "<th>Page Template to be used</th>";
   echo "</tr>";
   echo "</tfoot>";
   echo"<tbody>";
   for($i=0;$i<=12;$i++){
   	  $template_assigned = get_option($labels[$i]);
  		 echo"<tr>";
  		 echo "<td>".$iterms[$i][0]."</td>";
  		 echo "<td>".$iterms[$i][1]."</td>";
   	 echo "<td><select name='".$labels[$i]."' style='width:250px;'>";
   	  for($j=0;$j < count($pages_array);$j++) {
   	  	if($template_assigned == $pages_array[$j]['ID']):
	  			echo "<option value =".$pages_array[$j]['ID']." selected='selected'>".$pages_array[$j]['title']."</option>";
	  		else:	
	  			echo "<option value =".$pages_array[$j]['ID']." >".$pages_array[$j]['title']."</option>";
         endif;
 			}
   	 echo  "</select></td>";
   	 echo"</tr>";
   }
   echo"</tbody>";
   echo"</table>";
   
  echo "<br><br>";
  $test_mentor_email = get_option("test_mentor_email");
   echo "<div class='disp-row rwmb-textarea-wrapper'>";
            // Field Definition for Comments
                echo " <div class='rwmb-label'>";
                    echo "<label for='revisions'>Test Mentor Email</label>\n";
                echo " </div>";
                echo "<div class='rwmb-input'>\n";
                    echo "<input type='text' name='test_mentor_email' id='test_mentor_email' value='$test_mentor_email'/>";
                echo "</div>";
  echo "</div>";
  
  submit_button();
   echo '</form>';
}
add_filter( 'custom_menu_order', 'savi_admin_submenu_order' );

function savi_admin_submenu_order( $menu_ord ) 
{
    global $submenu,$menu,$current_user;
    global $wp_taxonomies;
    $events_tags_labels = &$wp_taxonomies['events_tags']->labels;
    $user_role = $current_user->roles[0];
    // Enable the next line to see all menu orders
    //echo '<pre>',print_r($submenu['edit.php?post_type=ai1ec_event']),'</pre>';
  
    $arr = array();
    $arr1 = array();
    /* Modify the Event tags to Event Type by syllogic -sathya on 22 Jan 2015 */
      $submenu['edit.php?post_type=ai1ec_event'][16][0] ="Event Type";
      $events_tags_labels->name ="Event Types";
      $events_tags_labels->singular_name ="Event Type";
      $events_tags_labels->search_items ="Search Types";
      $events_tags_labels->popular_items ="Popular Types";
      $events_tags_labels->all_items ="Event Types";
      $events_tags_labels->edit_item ="Edit Type";
      $events_tags_labels->view_item ="View Type";
      $events_tags_labels->update_item ="Update Type";
      $events_tags_labels->add_new_item ="Add New Type ";
      $events_tags_labels->new_item_name ="New Type Name";
      $events_tags_labels->add_or_remove_items ="Add or remove types";
      $events_tags_labels->choose_from_most_used ="Choose from the most used types";
      $events_tags_labels->not_found ="No types found";
      $events_tags_labels->menu_name ="Event Types";
      $events_tags_labels->name_admin_bar ="Event Type";
      
    /* Modify the Event tags to Event Type by syllogic -sathya on 22 Jan 2015 */
    if($user_role == "editor" )
    {
   		$arr1[] = $menu[2];   
   		$arr1[] = $menu[3];   
   		$arr1[] = $menu[4];   
   		$arr1[] = $menu[28];   
   		$arr1[] = $menu[6];   
   		$arr1[] = $menu[29];   
   		$arr1[] = $menu[30];   
   		$arr1[] = $menu[27];   
   		$arr1[] = $menu[32];   
 		$arr1[] = $menu[59];   
   		$menu = $arr1;
   		
   		  $arr[] = $submenu['edit.php?post_type=ai1ec_event'][5];     
          $arr[] = $submenu['edit.php?post_type=ai1ec_event'][10];
          $arr[] = $submenu['edit.php?post_type=ai1ec_event'][20];
          $arr[] = $submenu['edit.php?post_type=ai1ec_event'][16];
          $arr[] = $submenu['edit.php?post_type=ai1ec_event'][27];
          $arr[] = $submenu['edit.php?post_type=ai1ec_event'][28];
          $arr[] = $submenu['edit.php?post_type=ai1ec_event'][17];
          $arr[] = $submenu['edit.php?post_type=ai1ec_event'][18];
          $arr[] = $submenu['edit.php?post_type=ai1ec_event'][19];
          $arr[] = $submenu['edit.php?post_type=ai1ec_event'][26];
  } 
  else{
   
    $arr[] = $submenu['edit.php?post_type=ai1ec_event'][5];     
    $arr[] = $submenu['edit.php?post_type=ai1ec_event'][10];
    $arr[] = $submenu['edit.php?post_type=ai1ec_event'][23];
    $arr[] = $submenu['edit.php?post_type=ai1ec_event'][16];
    //$arr[]= array("Event Type","manage_events_categories","edit-tags.php?taxonomy=events_tags&post_type=ai1ec_event");
    $arr[] = $submenu['edit.php?post_type=ai1ec_event'][27];
    $arr[] = $submenu['edit.php?post_type=ai1ec_event'][28];
    $arr[] = $submenu['edit.php?post_type=ai1ec_event'][17];
    $arr[] = $submenu['edit.php?post_type=ai1ec_event'][18];
    $arr[] = $submenu['edit.php?post_type=ai1ec_event'][19];
    $arr[] = $submenu['edit.php?post_type=ai1ec_event'][20];
    $arr[] = $submenu['edit.php?post_type=ai1ec_event'][21];
    $arr[] = $submenu['edit.php?post_type=ai1ec_event'][22];
    $arr[] = $submenu['edit.php?post_type=ai1ec_event'][26];
   
  
  }		
   $submenu['edit.php?post_type=ai1ec_event'] = $arr;  
    //echo "<pre>", print_r($submenu['edit.php?post_type=ai1ec_event']); die;
    return $menu_ord;
}

function mfields_set_default_object_terms( $post_id ) {
 global $post;
 //echo $_REQUEST['event_type']; die;
 
 $type = get_post_meta($post->ID,'savi_events_type',true);
//print_r($meta); die;
    if ('ai1ec_event' === $post->post_type ) {
          $terms = get_the_terms( $post->ID, 'events_tags' );
          if ( $terms && ! is_wp_error( $terms ) ) : 
               $draught_links = array();
                foreach ( $terms as $term ) {
		     $draught_links[] = $term->name;
                }
          endif;
          if( count($draught_links) > 0 && is_array($draught_links)):
                // do nothing here
          else:

                 $defaults_value = (($type == "seminar")?308:307);
                 $defaults = array(
                                   'events_tags' => array( $defaults_value ), //set the taxonomy and tag ID    
                             );
                 $taxonomies = get_object_taxonomies( $post->post_type ); // check the object of term ( tags or category )
                 foreach ( (array) $taxonomies as $taxonomy ) {
                        $terms = wp_get_post_terms( $post_id, $taxonomy );
                         if ( empty( $terms ) && array_key_exists( $taxonomy, $defaults ) ) {
                               wp_set_object_terms( $post_id, $defaults[$taxonomy], $taxonomy ); //if term is not set declare the default term
                          }
                 }
           endif;
       }
}
add_action( 'save_post', 'mfields_set_default_object_terms', 100, 2 ); 
add_action( 'create_savi_opp_cat_work_area', 'workarea_create', 10, 2 );

add_action( 'delete_savi_opp_cat_work_area', 'workarea_delete', 10, 3 );

function workarea_create($term_id,$t_id ){     
	$wa_term = get_term_by('id',$term_id,'savi_opp_cat_work_area');
	$parent_id = $wa_term->parent;
	$ec_parent_id=0;
	if($parent_id!=0) $ec_parent_id=savi_get_event_child($parent_id);
	wp_insert_term( $wa_term->name,'events_categories',array('slug' => $wa_term->slug."wa_".$term_id, 'parent'=>$ec_parent_id));
	write_log("New WA term create, id:".$term_id);
	write_log("Inserted new Event term, slug:".$wa_term->slug."wa_".$term_id);
	write_log("Parent WA term, id:".$parent_id);
	write_log("Event parent set to term ID:".$ec_parent_id);
}
add_action( 'edited_savi_opp_cat_work_area', 'workarea_edit', 10, 2 );
function workarea_edit($term_id,$t_id){
	//clean_term_cache( '','savi_opp_cat_work_area' );
	$wa_term = get_term_by('id',$term_id,'savi_opp_cat_work_area');
	//name change - do nothing
	$ec_child_termID = savi_get_event_child($term_id);
	wp_update_term( $ec_child_termID,'events_categories',array('name' => $wa_term->name));
	//slug change - not allowed, grey out slug field
	//parent change, reset ec parent
	$wa_parent_id = $wa_term->parent;
	
	if($wa_parent_id != savi_get_wa_parent($ec_child_termID)){
		//$ec_parent_termID = savi_get_event_parent($term_id);
		$ec_parent_termID = savi_get_event_child($wa_parent_id);
		wp_update_term( $ec_child_termID,'events_categories',array('parent' => $ec_parent_termID));
		write_log("UPDATE WA term, id:".$term_id);
		write_log("UPDATE Event term, id:".$ec_child_termID);
		write_log("UPDATE Parent WA term, id:".$wa_parent_id);
		write_log("UPDATE Event parent set to term ID:".$ec_parent_termID);
	}
}
function workarea_delete($term_id,$t_id,$deleted_term){     

	$event_term_slug = $deleted_term->slug.'wa_'.$term_id;
	$ev_term = get_term_by('slug',$event_term_slug,'events_categories');
	wp_delete_term( $ev_term->term_id,'events_categories');
	write_log("DELETED WA term, id:".$term_id);
	write_log($deleted_term);
	write_log("DELETED Event term:".$ev_term->term_id);
	write_log($ev_term);

}
function savi_get_event_child($wa_child_termID){
	$wa_term = get_term_by('id',$wa_child_termID,'savi_opp_cat_work_area');
	$wa_child_slug = $wa_term->slug;
	$ec_term_slug = $wa_child_slug.'wa_'.$wa_child_termID;
	$ev_term = get_term_by('slug',$ec_term_slug,'events_categories');
	$ec_term_id = $ev_term->term_id;//get id for slug $ec_term_slug
	return $ec_term_id;
}
function savi_get_event_parent($wa_child_termID){
	//global $wpdb;
	$ec_term_id = savi_get_event_child($wa_child_termID);
	$ev_term = get_term_by('id',$ec_term_id,'events_categories');
	$parent_ec_term_id= $ev_term->parent;
	return $parent_ec_term_id;
}

function savi_set_event_parent_term_id($ec_child_termID, $ec_parent_termID){
	wp_update_term( $ec_child_termID,'events_categories',array('parent' => $ec_parent_termID));
}
function savi_get_wa_parent($ec_child_termID){
	$ev_child_term = get_term_by('id',$ec_child_termID,'events_categories');
	$ec_child_slug = $ev_child_term->slug; //get slug from term id $ec_child_termID
	$slug_explode = explode('wa_',$ec_child_slug);
	$wa_child_termID = $slug_explode[1];  //<slug>wa_<id>
	
	$ec_parent_termID = savi_get_event_parent($wa_child_termID);
	$ev_parent_term = get_term_by('id',$ec_parent_termID,'events_categories');
	$ec_parent_slug = $ev_parent_term->slug; //get slug from term id $ec_parent_termID
	
	$slug_explode = explode('wa_',$ec_parent_slug);
	$wa_parent_termID = $slug_explode[1];  //<slug>wa_<id>
	return $wa_parent_termID;
}

/* Generate the custom quick view link to the custom post view_0 to view_7  starts  */

foreach( array( 'edit-view_1', 'edit-view_2','edit-view_3' ) as $hook ){
    add_filter( "views_$hook" , 'savi_quick_view_count', 10, 1);
}
function savi_quick_view_count( $views ) 
{
    global $current_screen;
    switch( $current_screen->id ) 
    {
        case 'edit-view_1':
            $views = savi_manipulate_views( 'view_1', $views );
            break;
        case 'edit-view_2':
            $views = savi_manipulate_views( 'view_2', $views );
            break;
        case 'edit-view_3':
            $views =savi_manipulate_views( 'view_3', $views );
            break;
      
    }
    return $views;
}

function savi_manipulate_views( $what, $views )
{
    global $user_ID, $wpdb;

    /*
     * This is not working for me, 'artist' and 'administrator' are passing this condition (?)
     */
    if ( !current_user_can('administrator') ) 
        return $views;

   
      $intership = $wpdb->get_var("SELECT COUNT(*) FROM wp_posts wp, wp_postmeta wpm WHERE wp.post_type = '$what' AND wpm.meta_key='savi_views_education-details_intership' AND wpm.meta_value='Yes' AND wp.ID = wpm.post_id");
      $visa = $wpdb->get_var("SELECT COUNT(*) FROM wp_posts wp, wp_postmeta wpm WHERE wp.post_type = '$what' AND wpm.meta_key='savi_views_stay-details_special-visa' AND wpm.meta_value='Yes' AND wp.ID = wpm.post_id");
      $monthduration = $wpdb->get_var("SELECT COUNT(*) FROM wp_posts wp, wp_postmeta wpm WHERE wp.post_type = '$what' AND wpm.meta_key='savi_views_stay-details_duration'  AND wpm.meta_value > 6 AND wp.ID = wpm.post_id");
      $currentdate = date("Y-m-d");
      $two_months_from_now = strtotime("+2 months",strtotime($currentdate));
      $checked_date = date("Y-m-d",$two_months_from_now);
      $two_month_arrival = $wpdb->get_var("SELECT COUNT(*) FROM wp_posts wp, wp_postmeta wpm WHERE wp.post_type = '$what' AND wpm.meta_key='savi_views_stay-details_planned-arrival'  AND wpm.meta_value <= '$checked_date' AND wp.ID = wpm.post_id");
      
     if( $intership > 0 && ( $what == "view_1" || $what == "view_2" ) ) :
    	 $views['Intership'] = '<a href="edit.php?post_type='.$what.'&amp;post_meta=intership">Intership <span class="count">('.$intership.')</span></a>'; 
     endif;	
     if( $visa > 0 && $what == "view_1" ) : 
   	  $views['Visa'] = '<a href="edit.php?post_type='.$what.'&amp;post_meta=visa">Visa <span class="count">('.$visa.')</span></a>'; 
      endif;
      
    if( $monthduration > 0 && $what == "view_1" ) : 
    	 $views['6 Month Duration'] = '<a href="edit.php?post_type='.$what.'&amp;post_meta=duration">6 Month Duration <span class="count">
    	 ('.$monthduration.')</span></a>'; 
     endif;  
     if( $two_month_arrival > 0 && $what == "view_2" ) : 
    	 $views['two_month_arrival'] = '<a href="edit.php?post_type='.$what.'&amp;post_meta=two_month_arrival">2 Months Arrival Date <span class="count">
    	 ('.$two_month_arrival.')</span></a>'; 
     endif;
     unset($views['publish']);
    return $views;
}


/* Generate the custom quick view link to the custom post view_0 to view_7 ends  */

/* Get All custom post filtering by the meta values starts  */

function savi_quick_view_pre_get_posts( $query ) {
    if ( !is_admin() )
        return;
	$postType = $_GET['post_type'];
	$meta_key = $meta_value = "";
    if ( isset( $query->query_vars[ 'post_type' ] ) ) {
        $post_type = $query->query_vars[ 'post_type' ];
        switch($postType){
         
           case 'view_1' :
            case 'view_2' :   
	             if($_REQUEST['post_meta'] == "duration"):
		             $meta_key ="savi_views_stay-details_duration";
		             $meta_value = 6; 
		             $meta_compare ='>';
		             $meta_type ='NUMERIC';
	            endif; 
	              if($_REQUEST['post_meta'] == "intership"):
		             $meta_key ="savi_views_education-details_intership";
		             $meta_value = "Yes"; 
		             $meta_compare ='=';
		             $meta_type ='CHAR';
	            endif; 
	              if($_REQUEST['post_meta'] == "visa"):
		             $meta_key ="savi_views_stay-details_special-visa";
		             $meta_value = "Yes"; 
		             $meta_compare ='=';
		             $meta_type ='CHAR';
	            endif; 
	             if($_REQUEST['post_meta'] == "two_month_arrival"):
	                  $currentdate = date("Y-m-d");
                      $two_months_from_now = strtotime("+2 months",strtotime($currentdate));
                      $checked_date = date("Y-m-d",$two_months_from_now);
		              $meta_key ="savi_views_stay-details_planned-arrival";
		              $meta_value = $checked_date ; 
		              $meta_compare ='<=';
		              $meta_type ='DATE';
	            endif; 
	           
           break;
        
        }
        if( $meta_key !="" && $meta_value !="" ) {
        
             $args = array( 'post_type' => $postType, // else seminar
			   'meta_query' => array(array('key' => $meta_key,'value' => $meta_value,'compare' =>  $meta_compare,
							                'type'=> $meta_type
				                      )
					        )
				          );
			$query->query_vars = $args; 
          }
	 return $query;                
    }
}

add_action('pre_get_posts','savi_quick_view_pre_get_posts');

/* =======================================================
  this ajax hook is used for to updated the confirm that 
  you have received Sealed VISA Request letter from 
  register courier.
  ========================================================*/
function savi_received__visa_request_letter() {
   
	   $post_id = $_REQUEST['post_id'];
	   $action = $_REQUEST['ajax_visa_action'];
      	$confirm_receipt_sealed_envelop = get_post_meta($post_id, 'confirm_receipt_sealed_envelop', true);
      	if($action =='confirm_receipt_sealed_envelop') :
      	     if(empty($confirm_receipt_sealed_envelop)):
				  $blogtime = current_time( 'mysql' ); 
				  update_post_meta($post_id,'confirm_receipt_sealed_envelop',$blogtime); 
				  
				 $user_id= get_post_meta($post_id,'user_id',true);
				 $user_info = get_userdata( $user_id );
				 $clientEmail = $user_info->user_email;
				 $site_url = get_bloginfo('wpurl');
				 $option_name = 'Instruction_for_Auroville_VISA_Application';
				 $templatePage = (int) get_option($option_name);
				 $printTemplate = get_post($templatePage);
				 $content = $printTemplate->post_content;
				 $savi_shortcodes = array();
				 foreach($GLOBALS['shortcode_tags'] as $keys => $values){
					if( substr( $keys, 0, 4 ) === "SAVI" ) $savi_shortcodes[] = $keys;
				 }
				$default_atts = 'profile_id="'.$post_id.'" user_id="'.$user_id.'" user_pwd="'.$random_password.'" need_visa=="'.$requires_visa.'" post_type=="'.$post_type.'" opportunity_id="'.$oppID.'"';
				foreach($savi_shortcodes as $saviCodes){
					if (has_shortcode($content, $saviCodes)) 
						$content = str_replace('['.$saviCodes,'['.$saviCodes.' '.$default_atts,$content);
				}
			   $printContent = apply_filters('the_content',$content);
				 add_filter( 'wp_mail_content_type', create_function('', 'return "text/html";') );
				 $blog_title = get_bloginfo('name');
				 $TemplateTitle = get_the_title($templatePage);
				 $subject = $blog_title." - ".$TemplateTitle;
     			 wp_mail($clientEmail, $subject, $printContent);	
				  
				  echo $action;
			  else:
			     echo "already_receipt_sealed_enveloped_confirmed";
			  endif;	  
      			exit;			
			endif;  		

}

add_action( 'wp_ajax_savi_received__visa_request_letter', 'savi_received__visa_request_letter' );
/*==============================================================================
 * hook to called the file having the serialize work area and work type
 * this hook work once
 * ===========================================================================*/

add_action( 'init', 'savi_create_work_area_type', 100 );
function savi_create_work_area_type(){
	 $upload_dir = wp_upload_dir();
	 $file_path =  $upload_dir ['basedir'] . "/savi_customtaxonomy" ;
	 if(file_exists($file_path."/WorkAreas.csv")){
		 savi_create_work_area();
		
		 if (@unlink($file_path."/WorkAreas.csv"))
		  {
			 //  echo ("Deleted $file_path/WorkAreas.csv");
		 }
		
	 }
	 if(file_exists($file_path."/WorkType.csv")){
		 savi_create_work_type();
		 
		 if (@unlink($file_path."/WorkType.csv"))
		  {
			 // echo "<br>".("Deleted $file_path/WorkType.csv");
		   }
		
	 }

}
function savi_create_work_area(){
	
	$upload_dir = wp_upload_dir();
	$file_path =  $upload_dir ['basedir'] . "/savi_customtaxonomy" ;
	$work_area_array = array();
	$row1 = 1;
	if (($handle1 = fopen($file_path."/WorkAreas.csv", "r")) !== FALSE) {
		while (($data1 = fgetcsv($handle1) ) !== FALSE ) {
		   if($row1!=1){
				$key1 = trim($data1[0]);
				$value1 = trim($data1[1]);
				$work_area_array[$key1] = $value1;
				//echo $key." ".$value."<br>";
		   }
		   $row1++;
		 }
	fclose($handle1);
   }
  foreach($work_area_array as $new_work_area_key => $new_work_area_value){
		
			if ($new_work_area_value != '') {
				$parent = get_term_by('slug',sanitize_title($new_work_area_value),'savi_opp_cat_work_area');
				if(empty($parent)){
					$parent = get_term_by('name',$new_work_area_value,'savi_opp_cat_work_area');
				}
			} else {
			   $parent = 0;
			}
			if($parent == 0){
				wp_insert_term ($new_work_area_key,'savi_opp_cat_work_area');
			}else{
				wp_insert_term ($new_work_area_key,'savi_opp_cat_work_area',array('parent'=> $parent->term_id));
				
			} 
  }		 
    
}
function savi_create_work_type(){
	
	$upload_dir = wp_upload_dir();
	$file_path =  $upload_dir ['basedir'] . "/savi_customtaxonomy" ;
	$work_type_array = array();
	$row = 1;
	if (($handle = fopen($file_path."/WorkType.csv", "r")) !== FALSE) {
		while (($data = fgetcsv($handle) ) !== FALSE ) {
		   if($row!=1){
				$key = trim($data[0]);
				$value = trim($data[1]);
				$work_type_array[$key] = $value;
				//echo $key." ".$value."<br>";
		   }
		   $row++;
		 }
	fclose($handle);
   }
   foreach($work_type_array as $new_work_type_key => $new_work_type_value){
		
			if ($new_work_type_value != '') {
				$parent = get_term_by('slug',sanitize_title($new_work_type_value),'savi_opp_cat_work_type');
				if(empty($parent)){
					$parent = get_term_by('name',$new_work_type_value,'savi_opp_cat_work_type');
				}
			} else {
			   $parent = 0;
			}
			if($parent == 0){
				wp_insert_term ($new_work_type_key,'savi_opp_cat_work_type');
			}else{
				wp_insert_term ($new_work_type_key,'savi_opp_cat_work_type',array('parent'=> $parent->term_id));
				
			} 
  }		 
   
}
add_action( 'before_delete_post', 'savi_before_delete_post_func' );
/*==============================================================================
 * hook to called the to check the user id available in the ordered_new_volunteer
 * post meta field from expressed_opportunities and delete the user id from the
 * ordere_new_volunteer list before the the post delete
 * ===========================================================================*/
function savi_before_delete_post_func( $postid ){
    
    // We check if the global post type isn't ours and just return
    global $post_type,$wpdb;  
   // echo $post_type.$postid;
   //  die;
    if ( $post_type != 'view_0' && $post_type != 'view_1' ){
     
	     	$expressOpportunitiesIDs = array();
            $ordered_new_volunteerMeta = array();
            $removed_ordered_new_volunteerIDs = array();
            $expressedVolunteerMeta = array();
            $removed_expressedVolunteerIDs = array();
            $profile_user_id =  get_post_meta($postid,'user_id',true);
            $expressOpportunitiesMeta = get_post_meta( $postid, 'express_opportunities', false );
            $allexpressOpportunities = $expressOpportunitiesMeta[0];
            if (sizeof($allexpressOpportunities) > 0 && is_array($allexpressOpportunities)) {
            	foreach($allexpressOpportunities as $key=>$expressOpportunity) {
               	  $expressOpportunitiesIDs[] = $expressOpportunity['express_opportunity'];
                 }
            } 
            foreach($expressOpportunitiesIDs as $expressOpportunitiesID){
				$ordered_new_volunteerMeta = get_post_meta( $expressOpportunitiesID, 'ordered_new_volunteer',true);
        		 foreach($ordered_new_volunteerMeta as $vol_user_id){
					if($vol_user_id != $profile_user_id ):
						$removed_ordered_new_volunteerIDs[] = $vol_user_id;				
					endif; 
				 }
                 update_post_meta($expressOpportunitiesID,'ordered_new_volunteer',$removed_ordered_new_volunteerIDs);
                 $expressedVolunteerMeta = get_post_meta( $expressOpportunitiesID, 'expressed_volunteer', true );
                  foreach($expressedVolunteerMeta as $expressedVolunteer_user_id){
					if($expressedVolunteer_user_id != $profile_user_id ):
						$removed_expressedVolunteerIDs[] = $expressedVolunteer_user_id;				
					endif; 
				 } 
				 update_post_meta($expressOpportunitiesID,'expressed_volunteer',$removed_expressedVolunteerIDs);   
				  unset($removed_ordered_new_volunteerIDs);
			      unset($removed_expressedVolunteerIDs);
	        }   
	       
   }

    // My custom stuff for deleting my custom post type here
}

?>
