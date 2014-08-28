<?php
define( 'SAVI_2014_VERSION', '0.3' );

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
		case is_page_template( 'my-opportunity-page.php' ) :
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
add_action('pre_get_posts','sy_tax_alter_query');
function sy_tax_alter_query($query) {
	if(!$query->is_main_query()) return $query;
	//gets the global query var object
	global $wp_query;
	$postType = $_GET['postType']; // get the post type form url
	$eventType = $_GET['eventType'];	// get the event type form url
	if($eventType == '') $eventType= 'workshop-2'; // if the event category is workshop display workshop by default
	//archive of ai1ec_event event post type
	switch(true){
			case is_tax('savi_opp_cat_work_area'):
			case is_tax('savi_opp_cat_work_type'):
				switch($postType) {
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
						case 'av_unit': //units
							$wpQuery = new WP_Query($wp_query->query_vars);
							$unitIDs = array();
							if($wpQuery->have_posts()) {
								while($wpQuery->have_posts()) {
									$wpQuery->the_post();
									$unitIDs[] = get_post_meta( get_the_ID(), "av_unit", true );
								}
								/* Restore original Post Data 
							 * NB: Because we are using new WP_Query we aren't stomping on the 
							 * original $wp_query and it does not need to be reset with 
							 * wp_reset_query(). We just need to set the post data back up with
							 * wp_reset_postdata().
							 */
							  wp_reset_postdata();
							}
							$units = array_unique($unitIDs);
							//$query-> set('post_type' ,	'av_opportunity');
							$query-> set('post_type' ,	$postType);
							$query-> set('post__in',$units);
						break;	
						case 'project': //projects
						break;
						case 'av_opportunity': //opportunities
						break;	
						default:
						break;	
				}
	}
	/*
	if(is_tax('savi_opp_cat_work_type')  && $query->is_main_query() ){	 // check taxonomy is Work Type
		if($postType == 'ai1ec_event'){  // only for post type ailec event
			
				  $args = array( 'post_type' => $postType, // else seminar
					 'tax_query' => array(
								array(
									'taxonomy' => 'events_categories',
									'field' => 'slug',
									'terms' => $eventType,
								),
							)
						);
			$query->query_vars = $args; 
		}else{
		//	echo 'else';
			$query-> set('post_type' ,	'av_opportunity'); // post type av_opportunity and av_unit
		}		
	}elseif(is_tax('savi_opp_cat_work_area')  && $query->is_main_query() ){ // check taxonomy is Work Area
				if($postType == 'ai1ec_event'){  // only for post type ailec event
						  $args = array( 'post_type' => $postType, // else seminar posts
							 'tax_query' => array(
										array(
											'taxonomy' => 'events_categories',
											'field' => 'slug',
											'terms' => $eventType,
										),
									)
								);
					$query->query_vars = $args; 
			}else{
				$query-> set('post_type' ,	'av_opportunity');  // post type av_opportunity and av_unit
			}	
		}
	elseif(is_archive( ) && $query->is_main_query() ) { // check archive of the 
		if($eventType != '') { //check event type have value(seminar or Workshop) 
		  $args = array( 'post_type' => 'ai1ec_event', // else seminar posts
							'tax_query' => array(
										array(
											'taxonomy' => 'events_categories',
											'field' => 'slug',
											'terms' => $eventType,
										),
									)
								);
		$query->query_vars = $args;
		}
	} */
	return $query;
}


/*
------------ MENU & LOG-IN Functions
*/
add_action( 'init', 'sy_conf_register_menu' ); // register additional menu
function sy_conf_register_menu() {
  register_nav_menu('quick-in-menu',__( 'Quick Loged-in Menu' ));
  register_nav_menu('quick-out-menu',__( 'Quick Loged-out Menu' ));
   register_nav_menu('quick-opp-menu',__( 'Quick opportunity Menu' ));
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
function nav_items( $items, $menu, $args ) 
{
    foreach( $items as $item ) 
    {
        if( 'Work Area (Units)' == $item->post_title)
            $wtParent = $item->ID;
    }
	//echo "Parent".$wtParent;
	foreach( $items as $item ) 
    {
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
      update_post_meta($profilePostId,'express_opportunities',$newexpressOpportunities);
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

?>
