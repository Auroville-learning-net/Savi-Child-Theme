<?php
define( 'SAVI_2014_VERSION', '1.5' );
/*functionality to display current template being loaded when admin is logged in*/
function echo_admin($echoStr=null){
	$isAdmin = current_user_can( 'manage_options' );
	if($echoStr && $isAdmin) echo $echoStr;
    return $isAdmin;
	//return false;
}
add_action('template_redirect','beta_site_redirect');
function beta_site_redirect() {
	$admin = $_GET ['admin'];
    if (!is_user_logged_in() && !is_admin() && isBetaSite() ) {
		if($admin) return;
        //&& !'redirect.html'==get_current_template()
        wp_redirect('/redirect.html');
        exit();
    }
}
function sy_get_domain_name(){
	$domain_name =  preg_replace('/^www\./','',$_SERVER['SERVER_NAME']);
	return $domain_name; 
}
function isBetaSite(){
	if("beta.auroville-learning.net" == sy_get_domain_name()) return true;
	if("localhost" == sy_get_domain_name()) return true;
	if("savi.pe.hu" == sy_get_domain_name()) return true;
	return false;
}

/* Disable WordPress Admin Bar for all users but admins. */
add_filter('show_admin_bar', '__return_false');
/*
functions to access the current page being viewed for development purpose,
when admin is logged in these fucntions are called from the header file to display the page being seen.
*/
add_filter( 'template_include', 'var_template_include', 1000 );
function var_template_include( $t ){
    $GLOBALS['current_theme_template'] = basename($t);
    return $t;
}

function get_current_template( $echo = false ) {
    if( !isset( $GLOBALS['current_theme_template'] ) )
        return false;
    if( $echo )
        echo $GLOBALS['current_theme_template'];
    else
        return $GLOBALS['current_theme_template'];
}

/*add WP 3.8 dashicons for use in css*/
function child_theme_styles() {
	wp_enqueue_style( 'dashicons' ); //for child themes
	//wp_enqueue_style( 'themename-style', get_stylesheet_uri(), array( 'dashicons' ), '1.0' ); //for new themes
}
add_action( 'wp_enqueue_scripts', 'child_theme_styles' );

/*
----------------- Include other functions files----------
*/
require_once(get_stylesheet_directory().'/includes/formidable-pro-hooks.php');
require_once(get_stylesheet_directory().'/includes/admin-dashboard-mod.php');
require_once(get_stylesheet_directory().'/includes/shortcode-savi.php');

/*
 ---------------- BOOKKEEPING - Scripts, Stylessheets, wp_query modification---------
*/
add_action( 'wp_enqueue_scripts', 'savi_scripts' );
function savi_scripts(){
	$template_dir = get_stylesheet_directory_uri();
	wp_enqueue_script('jquery');
	switch (true){
		case is_page_template( 'mentor-opportunity-page.php' ) :
			wp_enqueue_script( 'jquery-ui',  '//code.jquery.com/ui/1.11.0/jquery-ui.js', array( 'jquery' ), '1.0', false );
			wp_enqueue_script( 'jquery-inline-editable', $template_dir . '/js/jquery.inlineedit.js', array( 'jquery' ), '1.0', true );
			break;
		case is_singular( 'av_unit' ) :
			wp_enqueue_script( 'google-maps-api', 'http://maps.google.com/maps/api/js?sensor=false', array( 'jquery' ), '1.0', false );
			wp_enqueue_script( 'gmap3', $template_dir . '/js/gmap3.min.js', array( 'jquery' ), '1.0', false );
			break;
		case is_page( 'opportunity-selection-preference' ):
			wp_enqueue_script( 'jquery-ui',  '//code.jquery.com/ui/1.11.0/jquery-ui.js', array( 'jquery' ), '1.0', false );
			break;
	}
	//required for all pages
	wp_enqueue_script( 'savi-ajax', $template_dir . '/js/ajax-function-calls.js', array( 'jquery' ), '1.0', true );	
	
}
add_action( 'wp_footer', 'savi_load_scripts_in_footer' );
function savi_load_scripts_in_footer(){
	switch(true){
		case is_page( 'opportunity-selection-preference' ): 
			savi_javascript_sortable_list();
			break;
		case is_front_page():
			?>
 <script>
		jQuery(document).ready(function($) {
			var slideTextH = $(".home .et_pb_slide_description").height();
			var headerH = $("header").height();
			var slideTextTopPadding= parseInt($(".home .et_pb_slide_description").css('padding-top'));
			var windowReminder = $( window ).height() - slideTextH - headerH - slideTextTopPadding;
			$(".home .et_pb_slide_description").css('padding-bottom',windowReminder+'px');
		 });
</script>
		<?php
			break;
	}
}

function savi_javascript_sortable_list($id="sortable", $fieldId="orderedOpps", $editable=false){ ?>
 <script>
		jQuery(document).ready(function($) {
			$("#<?php echo $id; ?>").sortable({
				stop : function(event, ui){
					var list = $(this).sortable('serialize');
					$("#<?php echo $fieldId; ?>").val(list); 
				}
				});
			$( "#<?php echo $id; ?>" ).disableSelection();
		});
		/* This js only for target blank in left-area <a> this js */
		<?php if($editable):?>
		jQuery(function($) {
			var $removeLink = $('#<?php echo $id; ?> li a.delete'),
				$itemList = $('#<?php echo $id; ?>');
		 
			// Remove todo
			$itemList.delegate("a.delete", "click", function(e) {
				var $this = $(this);
				// Fade out the list item then remove from DOM
				$this.parent().fadeOut(function() { 
					$this.parent().remove();
					var list = $("#<?php echo $id; ?>").sortable('serialize');
					$("#<?php echo $fieldId; ?>").val(list);
				});
			});
		 });
		<?php endif;?>
</script>
<?php
} 
add_action( 'wp_enqueue_scripts', 'savi_header_styles' );
function savi_header_styles(){
	$template_dir = get_stylesheet_directory_uri();
	wp_enqueue_style( 'av_unit-map',$template_dir . '/css/av_unit-map.css' , array(), null );
	wp_enqueue_style('media-test',$template_dir . '/css/media-below-980.css' , array('divi-style'), '1.0', '(max-width: 980px)');
	wp_enqueue_style('media-portrait',$template_dir . '/css/media-portrait.css' , array('divi-style'), '1.0', '(orientation:portrait)');
}


// hook add_query_vars function into query_vars, in order to be able to show case data on a fullwidth google maps.
add_filter('query_vars', 'sy_add_query_vars');
function sy_add_query_vars($aVars) {
	$aVars[] = "full-map-posts"; // represents the unit of data to show on a fullwidth map
	return $aVars;
}
// hook add_rewrite_rules function into rewrite_rules_array and pick up our full-map variable
add_filter('rewrite_rules_array', 'sy_add_rewrite_rules');
function sy_add_rewrite_rules($aRules) {
	$aNewRules = array('full-map/([^/]+)/?$' => 'index.php?pagename=full-map&full-map-posts=$matches[1]');
	$aRules = $aNewRules + $aRules;
	return $aRules;
}
/* Custom wp_query for taxonomy template for work area and work type */
add_action('pre_get_posts','savi_taxonomy_alter_query',1000);
function savi_taxonomy_alter_query($query) {
	if(is_admin() || !$query->is_main_query()) return $query;
	//gets the global query var object
	global $wp_query;
	$postType = $_GET['postType']; // get the post type form url
	//$eventType = $_GET['eventType'];	// get the event type form url
	//if($eventType == '') $eventType= 'workshop-2'; // if the event category is workshop display workshop by default
	/* Removed the following case as the events_categories is a spearare taxonomy
	 case 'ai1ec_event': //events
					$args = array( 'post_type' => $postType, 
								 'tax_query' => array(
											array(
												'taxonomy' => 'events_categories',
												'field' => 'slug',
												'terms' => $eventType,
											)
										)
									);
					$query->query_vars = $args; 
					break;	
	 */
	//archive of ai1ec_event event post type
	switch(true){
		case is_tax('savi_opp_cat_work_area'):
		case is_tax('savi_opp_cat_work_type'):
		case is_tax('savi_opp_tag_soft'):
		case is_tax('savi_opp_tag_languages'):
			switch($postType) {
				case 'av_unit': //units
				case 'av_project': //projects
					$wpQuery = new WP_Query($wp_query->query_vars);
					$parentIDs = array();
					$metaKey='av_unit';
					if($postType === 'av_project') $metaKey='projectname';
					//echo $metaKey;
					if($wpQuery->have_posts()) {
						while($wpQuery->have_posts()) {
							$wpQuery->the_post();
							if( get_post_meta( get_the_ID(),'opportunity_status' , true )==="opened")
								$parentIDs[] = get_post_meta( get_the_ID(),$metaKey , true );
						}
						/* Restore original Post Data 
						 * NB: Because we are using new WP_Query we aren't stomping on the 
						 * original $wp_query and it does not need to be reset with 
						 * wp_reset_query(). We just need to set the post data back up with
						 * wp_reset_postdata().
						 */
					  wp_reset_postdata();
					}
					$units = array_unique($parentIDs);
					$args = array( 'post_type' => $postType, 
								 'tax_query' => array(),
								 'post__in'=>$units);
					$query->query_vars = $args; 
					break;	
				default: //opportunities
					$args = array( 'meta_key' => 'opportunity_status', 
								 'meta_value' => 'opened');
					$query->query_vars = $args; 
					break;	
			}
			break;
	}
	return $query;
}


/*
------------ MENU & LOG-IN Functions
*/
add_action( 'init', 'sy_conf_register_menu' ); // register additional menu
function sy_conf_register_menu() {
	register_nav_menu('quick-in-menu',__( 'Quick Volunteers Loged-in Menu' ));
	register_nav_menu('quick-out-menu',__( 'Quick Loged-out Menu' ));
	register_nav_menu('quick-opp-menu',__( 'Quick Mentors Loged-in Menu' ));
	register_nav_menu('quick-def-menu',__( 'Quick Loged-in Menu' ));
}
add_filter( 'wp_nav_menu_items','sy_quick_nav_items', 10, 2 );
function sy_quick_nav_items( $items, $args ) 
{ //$items, $menu,
    if( 'quick-in-menu' == $args->theme_location )
        $items.= '<li><a href="'.wp_logout_url(get_permalink()).'" title="Logout">Logout</a></li>';
	if( 'quick-opp-menu' == $args->theme_location )
		$items.= '<li><a href="'.wp_logout_url(get_permalink()).'" title="Logout">Logout</a></li>';
     if( 'quick-out-menu' == $args->theme_location ) {
           $logInitem = '<a id="show_login" href="">Login</a>';
        $logInitem.= '<form id="login" action="login" method="post">';
        $logInitem.= '    <p class="status"></p>';
        $logInitem.= '    <div id="loginFields"><p><label for="username">Username</label><input id="username" type="text" name="username"></p>';
        $logInitem.= '    <p><label for="password">Password</label><input id="password" type="password" name="password"></p>';
        $logInitem.= '    <p id="lostPass"><a href="'.wp_lostpassword_url().'">Lost your password?</a></p>';
        $logInitem.= '    <p id="loginButton"><a href="javascript:void(0)" class="close">Cancel</a><input type="submit" value="Login" name="submit"></p></div>';
        $logInitem.= wp_nonce_field( 'ajax-login-nonce', 'security' );
        $logInitem.= '</form>';
        $items.= $logInitem;
    }
    return $items;
}
/* 
--------- Modify menus to access the units --------------
*/
add_filter( 'wp_get_nav_menu_items','nav_items', 11, 3 );
function nav_items( $items, $menu, $args ) {
	$wtParent="";
    foreach( $items as $item ) {
        if( 'Work Area (Units)' == $item->post_title)
            $wtParent = $item->ID;
    }
	//echo "Parent".$wtParent;
	foreach( $items as $item ) {
        if( $wtParent == $item->menu_item_parent)
            $item->url .= '?postType=av_unit';
    }
    return $items;
}

// Execute the action only if the user isn't logged in
if (!is_user_logged_in()) {
    add_action('init', 'ajax_login_init');
}
//ajax call handler
function ajax_login(){
    // First check the nonce, if it fails the function will break
    check_ajax_referer( 'ajax-login-nonce', 'security' );
    // Nonce is checked, get the POST data and sign user on
    $info = array();
    $info['user_login'] = $_POST['username'];
    $info['user_password'] = $_POST['password'];
    $info['remember'] = true;
    $user_signon = wp_signon( $info, false );
    if ( is_wp_error($user_signon) ){
        echo json_encode(array('loggedin'=>false, 'message'=>__('Wrong username or password.')));
    } else {
        echo json_encode(array('loggedin'=>true, 'message'=>__('Login successful, redirecting...')));
    }
    die();
}
function ajax_login_init(){
	//echo "here";
	$template_dir = get_stylesheet_directory_uri();
	wp_enqueue_style( 'ajax-login-css',$template_dir . '/css/ajax-login.css' , array(), null );
    wp_register_script('ajax-login-script', $template_dir . '/js/ajax-login-script.js', array('jquery') ); 
    wp_enqueue_script('ajax-login-script');
    wp_localize_script( 'ajax-login-script', 'ajax_login_object', array( 
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'redirecturl' => home_url(),
        'loadingmessage' => __('Logging in, please wait...')
    ));
    // Enable the user with no privileges to run ajax_login() in AJAX
    add_action( 'wp_ajax_nopriv_ajaxlogin', 'ajax_login' );
}
/**
------- SIMPLE AJAX CALLS
**/
add_action('wp_ajax_nopriv_sy_register_opportunity', 'sy_register_opportunity');// use this for non-login users
add_action('wp_ajax_sy_register_opportunity', 'sy_register_opportunity');
function sy_register_opportunity(){
	$postId = isset($_POST['post_id'])? $_POST['post_id']: 0;
	$userId = isset($_POST['user_id'])? $_POST['user_id']: 0;
	$selection = isset($_POST['selection'])? $_POST['selection']: '';
	$checked = isset($_POST['selection'])? $_POST['selection']: 'Unchecked';
	//echo "<p>-User: ".$userId." (".$selection.") post :".$postId."</p>";
	if($postId>0 && $userId>0){
		//echo "<p>User: ".$userId." (".$checked.") post :".$postId."</p>";
		$profilePostId = get_user_meta($userId, 'profile_post_id', true);
		$expressOpportunitiesMeta = get_post_meta($profilePostId,'express_opportunities',false);
		$allexpressOpportunities = array();
		$newexpressOpportunities = array();
		$allexpressOpportunities = $expressOpportunitiesMeta[0];
		$arraySize = sizeof($allexpressOpportunities);
      
      //$preferences = get_post_meta($profilePostId,'volunteer_opportunity',false);
	if($selection){
			if ($arraySize > 0 && is_array($allexpressOpportunities)) {
		     //********* checks if express opportunity having opportunity add the selected opportunity end of the array **
            	$express_opportunity = $postId;
					$allexpressOpportunityInfo = array (  "express_opportunity" => $express_opportunity, );
               $allexpressOpportunities[] = $allexpressOpportunityInfo;
         }
      	else {
      	//********* else express opportunity having no opportunities add the selected opportunity begining of the array **
      	      $express_opportunity = $postId;
					$allexpressOpportunityInfo = array (  "express_opportunity" => $express_opportunity, );
               $allexpressOpportunities[0] = $allexpressOpportunityInfo;
				      	
      	}
         $newexpressOpportunities = $allexpressOpportunities;
         $textResult = "Selected!";
		}else{
			$i =0;
		 if ($arraySize > 0 && is_array($allexpressOpportunities)) {
		 	foreach($allexpressOpportunities as $key=>$expressOpportunity) {
                 $expressOpportunitiesID = $expressOpportunity['express_opportunity'];
                 if($expressOpportunitiesID != $postId){
              		 $allexpressOpportunityInfo = array (  "express_opportunity" => $expressOpportunitiesID, );     
					 $newexpressOpportunities[$i++] = $allexpressOpportunityInfo;
                }
		 	}
		   $textResult = "Unselected!";
		}	
	   }
	  if(expressOpportunitiesMeta == ""){
		add_post_meta($profilePostId,'express_opportunities',$newexpressOpportunities);
	  }else{
		update_post_meta($profilePostId,'express_opportunities',$newexpressOpportunities);
	  }
	die();
 }	
}


add_action( 'show_user_profile', 'extra_user_profile_fields' );
add_action( 'edit_user_profile', 'extra_user_profile_fields' );
function extra_user_profile_fields( $user ) { ?>
<h3><?php _e("Extra profile information", "blank"); ?></h3>

<table class="form-table">
	<tr>
		<th>
			<label for="title"><?php _e("Position Title"); ?></label>
		</th>
	<td>
		<input type="text" name="title" id="title" value="<?php echo esc_attr( get_the_author_meta( 'title', $user->ID ) ); ?>" class="regular-text" /><br />
		<span class="description"><?php _e("Please enter your title."); ?></span>
	</td>
	</tr>
	<tr>
		<th>
			
			<label for="title"><?php _e("SAVI Role"); ?></label>
		</th>
		<td>
			<?php 
            //get dropdown saved value
			$selected = get_the_author_meta( 'savi_role', $user->ID ); //there was an extra ) here that was not needed 
            ?>
			<select name="savi_role" id="savi_role">
				  <option value="volunteers" <?php echo ($selected == "volunteers")?  'selected="selected"' : '' ?>>Volunteers</option>
				  <option value="opportunity-owner" <?php echo ($selected == "opportunity-owner")?  'selected="selected"' : '' ?>>Opportunity Owner</option>  
			</select>
			<span class="description"><?php _e("Please enter your Savi Role."); ?></span>
		</td>
	</tr>
</table>
<?php }

add_action( 'personal_options_update', 'save_extra_user_profile_fields' );
add_action( 'edit_user_profile_update', 'save_extra_user_profile_fields' );
function save_extra_user_profile_fields( $user_id ) {

if ( !current_user_can( 'edit_user', $user_id ) ) { return false; }
  update_user_meta( $user_id, 'title', $_POST['title'] );
  update_user_meta( $user_id, 'savi_role', $_POST['savi_role'] ); 
}

/* =======================================================
  this ajax hook is used for to updated post meta when mentor
  my volunteer filter the volunteer.
  ========================================================*/
add_action( 'wp_ajax_savi_volunteer_update_order', 'savi_volunteer_update_order' );
	function savi_volunteer_update_order() {
	$opp_id = $_REQUEST['oppID'];
	$vol_IDs = $_REQUEST['vol_IDs'];
	/*==============================================
	this code for updating post meta
	===============================================*/
	$current_time_updated = array();
	$current_time_updated = get_post_meta($opp_id,'ordered_new_volunteer_date',true);
	
	$current_time = current_time( 'mysql' );
	//$current_time_updated = $current_timeMeta[0];
	$arraySized = sizeof($current_time_updated);	
	if ($arraySized > 0 && is_array($current_time_updated)) {
		$current_time_updated[] = $current_time;
	}
	else {
		$current_time_updated[0] = $current_time;
	}
	$orderList = array();
	$strArray = explode("&", $vol_IDs);
	$i = 0;
	foreach ($strArray as $str){
		$array = explode("=", $str);
		$returndata[$i] = trim($array[1]); //the 2nd element is the actual value we want to preserve
		$i = $i + 1;
	}

	update_post_meta( $opp_id, 'ordered_new_volunteer',$returndata);
	update_post_meta( $opp_id, 'ordered_new_volunteer_date', $current_time_updated);
	exit();
}
/**/
// [OpportunityCount]
function get_opportunity_count() { 
	$post_type = 'av_opportunity';
	$term_slug = 'opportunity_status';
	global $wpdb;
	$count = $wpdb->get_col($wpdb->prepare("SELECT COUNT(*) FROM wp_posts wp
								INNER JOIN wp_postmeta wpm
								ON (wp.ID = wpm.post_id AND wpm.meta_key ='%s' AND wpm.meta_value = 'opened')
								AND wp.post_status='publish'
								WHERE wp.post_type ='%s'",$term_slug,$post_type));
    return $count[0];
}
add_shortcode( 'OpportunityCount', 'get_opportunity_count' );

//[OpportunityCounter color="" bgColor="" textColor=""]
function display_opp_counter($atts){
	wp_enqueue_script( 'easypiechart' );
	$a = shortcode_atts( array(
        'color' => '#ff7200',
		'bgColor' => 'transparent',
		'textColor' => '#606060',
		'title' => 'Opportunities',
    ), $atts );
	$count=get_opportunity_count();
	$output ='
	<div class="et_pb_number_counter et_pb_bg_layout_light opportunityCounter" data-number-value="'.$count.'" style="background-color:'.$a["bgColor"].';">
		<div class="percent" style="color:'.$a["color"].';"><p style="visibility: hidden;"><span class="percent-value">'.$count.'</span></p></div>
		<h3 style="color:'.$a["textColor"].';">'.$a["title"].'</h3>
		<canvas width="0" height="0"></canvas>
	</div>';
	return $output;
}
add_shortcode( 'OpportunityCounter', 'display_opp_counter' );

//[UnitsCounter color="" bgColor="" textColor=""]
function display_unit_counter($atts){
	wp_enqueue_script( 'easypiechart' );
	$atts = shortcode_atts( array(
        'color' => '#ff7200',
		'bgColor' => 'transparent',
		'textColor' => '#606060',
		'title' => 'Units',
    ), $atts );
	$count=get_unit_count();
	$output ='
	<div class="et_pb_number_counter et_pb_bg_layout_light unitCounter" data-number-value="'.$count.'" style="background-color:'.$atts["bgColor"].';">
		<div class="percent" style="color:'.$atts["color"].';"><p style="visibility: hidden;"><span class="percent-value">'.$count.'</span></p></div>
		<h3 style="color:'.$atts["textColor"].';">'.$atts["title"].'</h3>
		<canvas width="0" height="0"></canvas>
	</div>';
	return $output;
}
add_shortcode( 'UnitsCounter', 'display_unit_counter' );

//[WorkshopCounter color="" bgColor="" textColor=""]
function display_workshop_counter($atts){
	wp_enqueue_script( 'easypiechart' );
	$a = shortcode_atts( array(
        'color' => '#ff7200',
		'bgColor' => 'transparent',
		'textColor' => '#606060',
		'title' => 'Workshops',
    ), $atts );
	$count=0;
	$events = wp_count_posts('ai1ec_event');
	if( isset($events) ) $count=$events->publish;
	$output ='
	<div class="et_pb_number_counter et_pb_bg_layout_light unitCounter" data-number-value="'.$count.'" style="background-color:'.$a["bgColor"].';">
		<div class="percent" style="color:'.$a["color"].';">
			<p style="visibility: hidden;"><span class="percent-value">'.$count.'</span></p>
		</div>
		<h3 style="color:'.$a["textColor"].';">'.$a["title"].'</h3>
		<canvas width="0" height="0"></canvas>
	</div>';
	return $output;
}
add_shortcode( 'WorkshopCounter', 'display_workshop_counter' );

// [UnitCount]
function get_unit_count() {
	$post_type = 'av_unit';
	global $wpdb;
	$count = $wpdb->get_col($wpdb->prepare("SELECT COUNT(*) FROM wp_posts wp WHERE wp.post_type ='%s'",$post_type));
	
    return $count[0];
}
add_shortcode( 'UnitCount', 'get_unit_count' );

//custom taxonomy custom post counts
function get_work_area_count($post_type, $term_id){
	$sql="";
	switch($post_type){
		case "av_project":
			$sql =  get_savi_taxonomy_count_sql("savi_opp_cat_work_area", $term_id, "projectname");
			break;
		case "av_unit":
			$sql =  get_savi_taxonomy_count_sql("savi_opp_cat_work_area", $term_id, "av_unit");
			break;
		case "ai1ec_event":
			$sql=get_event_taxonomy_count_sql_default($term_id);
			break;
		default : //"av_opportunity"
			$sql=get_savi_taxonomy_count_sql_default("savi_opp_cat_work_area", $term_id);  
			break;
	}
	//echo '<pre>'.$sql.'</pre>';
	global $wpdb;
	$count = $wpdb->get_col($wpdb->prepare($sql,$term_id,$term_id));
	return $count[0];
}
function get_work_type_count($post_type, $term_id){
	$sql="";
	switch($post_type){
		case "av_project":
			$sql =  get_savi_taxonomy_count_sql("savi_opp_cat_work_type", $term_id, "projectname");
			break;
		case "av_unit":
			$sql =  get_savi_taxonomy_count_sql("savi_opp_cat_work_type", $term_id, "av_unit");
			break;
		default : //"av_opportunity"
			$sql=get_savi_taxonomy_count_sql_default("savi_opp_cat_work_type", $term_id);  
			break;
	}
	//echo '<pre>'.$sql.'</pre>';
	global $wpdb;
	$count = $wpdb->get_col($wpdb->prepare($sql,$term_id,$term_id));
	return $count[0];
}
/*
 * Function to retrieve the sql string for projects/units with opportunities either in work type, work_area, languages, softwares
 * @param string $taxonomy the taxonomy slug required, eg savi_opp_cat_work_area for work areas
 * @param int $term_ID the id of the specific term required for requested $taxonomy
 * @param string $meta_key 'projectname' for projects, 'av_unit' for units
 * @return string sql string required to get count of opportunities
 */
function get_savi_taxonomy_count_sql($taxonomy, $term_ID, $meta_key){
	$sql_query = "SELECT COUNT(DISTINCT parentmeta.meta_value) ";
	$sql_query .="FROM	wp_postmeta parentmeta,";
	$sql_query .="	wp_postmeta opstatus,";
	$sql_query .="	wp_posts,";
	$sql_query .="	(select DISTINCT object_id from wp_term_relationships, wp_term_taxonomy ";
	$sql_query .="		where  	wp_term_taxonomy.term_id = %d OR wp_term_taxonomy.parent = %d)";
	$sql_query .="		and 	wp_term_taxonomy.taxonomy = '".$taxonomy."'";
	$sql_query .="		and		wp_term_relationships.term_taxonomy_id = wp_term_taxonomy.term_taxonomy_id) termtaxonomy ";
	$sql_query .="WHERE	opstatus.post_id = termtaxonomy.object_id ";
	$sql_query .="AND		opstatus.meta_key = 'opportunity_status' ";
	$sql_query .="AND		opstatus.meta_value = 'opened' ";
	$sql_query .="AND		parentmeta.post_id = termtaxonomy.object_id ";
	$sql_query .="AND		parentmeta.meta_key = '".$meta_key."' ";
	$sql_query .="AND		wp_posts.ID = termtaxonomy.object_id ";
	$sql_query .="AND		wp_posts.post_status = 'publish'";
	$sql_query .="AND		wp_posts.post_type = 'av_opportunity' ";
	if($meta_key==="projectname") $sql_query .="AND     parentmeta.meta_value > 0";
	return $sql_query;
}
/*
 * Function to retrieve the sql string for opportunities either in work type, work_area, languages, softwares
 * @param string $taxonomy the taxonomy slug required, eg savi_opp_cat_work_area for work areas
 * @param int $term_ID the id of the specific term required for requested $taxonomy
 * @return string sql string required to get count of opportunities
 */
function get_savi_taxonomy_count_sql_default($taxonomy, $term_ID){
	$sql_query = "SELECT count(DISTINCT wp_posts.ID) ";
	$sql_query .="FROM	wp_postmeta opstatus,wp_posts,";
	$sql_query .="	(select DISTINCT object_id from wp_term_relationships, wp_term_taxonomy ";
	$sql_query .="		where  	(wp_term_taxonomy.term_id = %d OR wp_term_taxonomy.parent = %d)";
	$sql_query .="		and 	wp_term_taxonomy.taxonomy = '".$taxonomy."'";
	$sql_query .="		and		wp_term_relationships.term_taxonomy_id = wp_term_taxonomy.term_taxonomy_id) termtaxonomy ";
	$sql_query .="WHERE	opstatus.post_id = termtaxonomy.object_id ";
	$sql_query .="AND		opstatus.meta_key = 'opportunity_status' ";
	$sql_query .="AND		opstatus.meta_value = 'opened' ";
	$sql_query .="AND		wp_posts.ID = termtaxonomy.object_id ";
	$sql_query .="AND		wp_posts.post_status = 'publish' ";
	$sql_query .="AND		wp_posts.post_type = 'av_opportunity' ";
	return $sql_query;
}
/*
 * Function to retrieve the sql string for events either in  work_area
 * @param string $taxonomy the taxonomy slug required, eg savi_opp_cat_work_area for work areas
 * @param int $term_ID the id of the specific term required for requested $taxonomy
 * @return string sql string required to get count of events
 */
function get_event_taxonomy_count_sql_default($opp_work_area_term_ID){
	$sql_query = "SELECT count(DISTINCT wp_posts.ID) ";
	$sql_query .="FROM	wp_term_taxonomy, wp_terms, wp_term_relationships,wp_posts,";
	$sql_query .="	(select concat(wp_terms.slug , 'wa_' , wp_terms.term_id) slug from wp_terms ";
	$sql_query .="		where	wp_terms.term_id = ".$opp_work_area_term_ID." and wp_terms.term_id = ".$opp_work_area_term_ID."	) eventsSlug";
	$sql_query .="WHERE wp_terms.slug = eventsSlug.slug ";
	$sql_query .="AND   (wp_term_taxonomy.term_id = wp_terms.term_id OR wp_term_taxonomy.parent = wp_terms.term_id)";
	$sql_query .="AND   wp_term_taxonomy.term_taxonomy_id = wp_term_relationships.term_taxonomy_id ";
	$sql_query .="AND   wp_term_relationships.object_id = wp_posts.ID ";
	$sql_query .="AND	wp_posts.post_status = 'publish' ";
	return $sql_query;
}
/*
 * Function to return the events_categories slug for a given work area term.
 * This function takes a term ID and slug for the savi_opp_cat_work_area taxonomy and
 * returns the equivalent term slug for the events_categories taxonomy
 */
function workArea_event_category_equivalent_slug($work_area_term_slug, $work_area_term_id){
	return $work_area_term_slug."wa_".$work_area_term_id;
}
?>