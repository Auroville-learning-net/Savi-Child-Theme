<?php
function savi_nice_date_format($date){
	$dateObj = date_create($date);
	return date_format($dateObj, 'l jS F Y');
}
add_shortcode( 'SAVI_profile_full_name', 'SAVI_name_func' );
function SAVI_name_func($atts){
	//print_r($atts);
   $a = shortcode_atts( array(
        'profile_id' => '0',
        'user_id'=> '0',
        'user_pwd'=>'',
        'need_visa'=>'false',
        'post_type'=>'',
        'opportunity_id'=>'',
	    'error_message' => 'Hello, Profile full name is an error, please contact the Savi team!'
    ), $atts );
	$user_id=$a['user_id'];
	if($user_id<1) return $a['error_message']; 
	$user = get_user_by('id',$user_id);
	$user_display_name = $user->display_name;  
	return $user_display_name;
}

add_shortcode( 'SAVI_user_name', 'SAVI_profile_username_func' );
function SAVI_profile_username_func($atts){
	//print_r($atts);
   $a = shortcode_atts( array(
        'profile_id' => '0',
        'user_id'=> '0',
        'user_pwd'=>'',
        'need_visa'=>'false',
        'post_type'=>'',
        'opportunity_id'=>'',
		'error_message' => 'Hello, profile username is an error, please contact the Savi team!'
    ), $atts );
	$user_id=$a['user_id'];
	if($user_id<1) return $a['error_message']; 
	$user = get_user_by('id',$user_id);
	$user_login= $user->user_login ;//$wpdb->get_var("SELECT user_login FROM wp_users WHERE ID =".$user_id);
    return $user_login;
}

add_shortcode( 'SAVI_user_password', 'SAVI_profile_password_func' );
function SAVI_profile_password_func($atts){
	//print_r($atts);
   $a = shortcode_atts( array(
        'profile_id' => '0',
        'user_id'=> '0',
        'user_pwd'=>'',
        'need_visa'=>'false',
        'post_type'=>'',
        'opportunity_id'=>'',
		'error_message' => 'Hello, Profile password is an error, please contact the Savi team!'
    ), $atts );
	$user_pwd=$a['user_pwd'];
    if($user_pwd=="") return $a['error_message'];
	return $user_pwd;
}



add_shortcode( 'SAVI_profile_work_area', 'SAVI_profile_work_area_func' );
function SAVI_profile_work_area_func($atts){
	//print_r($atts);
	global $wpdb;
   $a = shortcode_atts( array(
        'profile_id' => '0',
        'user_id'=> '0',
        'user_pwd'=>'',
        'need_visa'=>'false',
        'post_type'=>'',
        'opportunity_id'=>'',
	    'error_message' => 'Hello, Profile work area is an error, please contact the Savi team!'
    ), $atts );
	$postID = $a['profile_id'];
	if($postID<1) return $a['error_message']; 
	$sql467="SELECT meta_value FROM wp_frm_item_metas WHERE field_id = 467 AND item_id
		=(SELECT id FROM wp_frm_items WHERE form_id = 19 AND post_id=$postID)";
	$results467=$wpdb->get_results($sql467,ARRAY_A);
	$meta_value467 =$results467[0]['meta_value'];
	$iterm_value467=unserialize($meta_value467);
	if( !empty( $iterm_value467 ) && is_array( $iterm_value467 ) ):
		$workarea = "<ul style='display:block'>";
		for($i=0;$i<count($iterm_value467);$i++){
			$term = get_term( $iterm_value467[$i], 'savi_opp_cat_work_area' );
			$workarea.="<li>".$term->name."</li>";
		}
		$workarea.= "</ul>";
		if($workarea=="") return $a['error_message'];
		return $workarea;
	else:
		$workarea = ( strtolower( $meta_value467 ) == "savi0" )?"Any Workareas":get_term_by('id', $meta_value467, 'savi_opp_cat_work_area')->name;
		if($workarea=="") return $a['error_message'];
		return $workarea;
	endif;
}

add_shortcode( 'SAVI_form_oppOrder', 'SAVI_form_oppOrder_func' );
function SAVI_form_oppOrder_func($atts){
	//print_r($atts);
   $a = shortcode_atts( array(
        'profile_id' => '0',
        'user_id'=> '0',
        'user_pwd'=>'',
        'need_visa'=>'false',
        'post_type'=>'',
        'opportunity_id'=>''
    ), $atts );
	$site_url = get_bloginfo('wpurl');
	$SAVI_form_oppOrder = "<a href='".$site_url."/opportunity-selection-preference/"."'>Click here</a>";
	return $SAVI_form_oppOrder;
}

add_shortcode( 'SAVI_profile_opportunity', 'SAVI_profile_opportunity_func' );
function SAVI_profile_opportunity_func($atts){
	//print_r($atts);
   $a = shortcode_atts( array(
        'profile_id' => '0',
        'user_id'=> '0',
        'user_pwd'=>'',
        'need_visa'=>'false',
        'post_type'=>'',
        'opportunity_id'=>''
    ), $atts );
	$postID = $a['profile_id'];
	if($postID<1) return $a['error_message']; 
	$volunteer_opportunity_id = get_post_meta($postID,'volunteer_opportunity',true);
	$SAVI_profile_opportunity__permalink = get_permalink($volunteer_opportunity_id);
	$SAVI_profile_opportunity = "<a href='".$SAVI_profile_opportunity__permalink ."'>".get_the_title($volunteer_opportunity_id)."</a>";
	return $SAVI_profile_opportunity;
}

add_shortcode( 'SAVI_profile_arrival_date', 'SAVI_profile_arrival_date_func' );
function SAVI_profile_arrival_date_func($atts){
	//print_r($atts);
   $a = shortcode_atts( array(
        'profile_id' => '0',
        'user_id'=> '0',
        'user_pwd'=>'',
        'need_visa'=>'false',
        'post_type'=>'',
        'opportunity_id'=>'',
		'error_message' => 'Hello, Profile arrival date is an error, please contact the Savi team!'
    ), $atts );
	$postID = $a['profile_id'];
	if($postID<1) return $a['error_message']; 
    $SAVI_profile_arrival_date = get_post_meta($postID,'savi_views_stay-details_planned-arrival',true);
	if($SAVI_profile_arrival_date=="") return $a['error_message'];
	return savi_nice_date_format($SAVI_profile_arrival_date);
}

add_shortcode( 'SAVI_profile_induction_date', 'SAVI_profile_induction_date_func' );
function SAVI_profile_induction_date_func($atts){
	//print_r($atts);
   $a = shortcode_atts( array(
        'profile_id' => '0',
        'user_id'=> '0',
        'user_pwd'=>'',
        'need_visa'=>'false',
        'post_type'=>'',
        'opportunity_id'=>'',
		'error_message' => 'Hello, Profile induction date is an error, please contact the Savi team!'
    ), $atts );
    $postID = $a['profile_id'];
	if($postID<1) return $a['error_message']; 
    $SAVI_profile_induction_date = get_post_meta($postID,'induction_date',true);
	if($SAVI_profile_induction_date=="") return $a['error_message'];
	return savi_nice_date_format($SAVI_profile_induction_date);
}

add_shortcode( 'SAVI_profile_onboard_date', 'SAVI_profile_onboard_date_func' );
function SAVI_profile_onboard_date_func($atts){
	//print_r($atts);
   $a = shortcode_atts( array(
        'profile_id' => '0',
        'user_id'=> '0',
        'user_pwd'=>'',
        'need_visa'=>'false',
        'post_type'=>'',
        'opportunity_id'=>'',
		'error_message' => 'Hello, Profile onboard date is an error, please contact the Savi team!'
    ), $atts );
    $postID = $a['profile_id'];
	if($postID<1) return $a['error_message']; 
    $SAVI_profile_onboard_date = get_post_meta($postID,'onboard_date',true);
	if($SAVI_profile_onboard_date=="") return $a['error_message'];
	return savi_nice_date_format($SAVI_profile_onboard_date);
}

add_shortcode( 'SAVI_profile_registered_mail', 'SAVI_profile_registered_mail_func' );
function SAVI_profile_registered_mail_func($atts){
	//print_r($atts);
   $a = shortcode_atts( array(
        'profile_id' => '0',
        'user_id'=> '0',
        'user_pwd'=>'',
        'need_visa'=>'false',
        'post_type'=>'',
        'opportunity_id'=>'',
		'error_message' => 'Hello, Profile registered mail is an error, please contact the Savi team!'
    ), $atts );
	$postID = $a['profile_id'];
	if($postID<1) return $a['error_message']; 
    $SAVI_profile_registered_mail = get_post_meta($postID,'registered_mail',true);
	if($SAVI_profile_registered_mail=="") return $a['error_message'];
	return $SAVI_profile_registered_mail;
}

add_shortcode( 'SAVI_profile_registered_date', 'SAVI_profile_registered_date_func' );
function SAVI_profile_registered_date_func($atts){
	//print_r($atts);
   $a = shortcode_atts( array(
        'profile_id' => '0',
        'user_id'=> '0',
        'user_pwd'=>'',
        'need_visa'=>'false',
        'post_type'=>'',
        'opportunity_id'=>'',
		'error_message' => 'Hello, Profile registered date is an error, please contact the Savi team!'
    ), $atts );
	$postID = $a['profile_id'];
	if($postID<1) return $a['error_message']; 
    $SAVI_profile_registered_date = get_post_meta($postID,'registered_mail_date',true);
	if($SAVI_profile_registered_date=="") return $a['error_message'];
	return savi_nice_date_format($SAVI_profile_registered_date);
}
add_shortcode( 'SAVI_profile_registered_confirm', 'SAVI_profile_registered_confirm_func' );
function SAVI_profile_registered_confirm_func($atts){
	//print_r($atts);
   $a = shortcode_atts( array(
        'profile_id' => '0',
        'user_id'=> '0',
        'user_pwd'=>'',
        'need_visa'=>'false',
        'post_type'=>'',
        'opportunity_id'=>'0',
		'error_message' => 'Hello, Profile Registered confirm is an error, please contact the Savi team!'
    ), $atts );
	$site_url = get_bloginfo('wpurl');
	$SAVI_profile_registered_confirm = "<a href='".$site_url."/confirm-registration/"."'>Confirm Registration</a>";
	return $SAVI_profile_registered_confirm;
}

add_shortcode( 'SAVI_Mentor_contact_name', 'SAVI_Mentor_contact_name_func' );
function SAVI_Mentor_contact_name_func($atts){
	//print_r($atts);
   $a = shortcode_atts( array(
        'profile_id' => '0',
        'user_id'=> '0',
        'user_pwd'=>'',
        'need_visa'=>'false',
        'post_type'=>'',
		'opportunity_id'=>'0',
		'error_message' => 'Hello, Profile Registered confirm is an error, please contact the Savi team!'
    ), $atts );
	$oppID = $a['opportunity_id'];
	if($oppID<1) return $a['error_message']; 
   $mentorName = get_post_meta($oppID,'contactPerson',true);
   if($mentorName=="") return $a['error_message'];
   return $mentorName;
}

add_shortcode( 'SAVI_Opportunity_title_link', 'SAVI_Opportunity_title_link_func' );
function SAVI_Opportunity_title_link_func($atts){
	//print_r($atts);
   $a = shortcode_atts( array(
        'profile_id' => '0',
        'user_id'=> '0',
        'user_pwd'=>'',
        'need_visa'=>'false',
        'post_type'=>'',
		'opportunity_id'=>'0',
		'error_message' => 'Hello, Profile Registered confirm is an error, please contact the Savi team!'
    ), $atts );
	$oppID = $a['opportunity_id'];
	if($oppID<1) return $a['error_message']; 
	$oppTitle = get_the_title($oppID);
	$SAVI_Opportunity_title_permalink = get_permalink($oppID);
	$SAVI_Opportunity_title_link ="<a href='".$SAVI_Opportunity_title_permalink."'>".$oppTitle."</a>";
	return $SAVI_Opportunity_title_link;

}
add_shortcode( 'SAVI_profile_nationality', 'SAVI_profile_nationality_func' );
function SAVI_profile_nationality_func($atts){
	//print_r($atts);
   $a = shortcode_atts( array(
        'profile_id' => '0',
        'user_id'=> '0',
        'user_pwd'=>'',
        'need_visa'=>'false',
        'post_type'=>'',
        'opportunity_id'=>'',
		'error_message' => 'Hello, Profile Nationality is an error, please contact the Savi team!'
    ), $atts );
	$postID = $a['profile_id'];
	if($postID<1) return $a['error_message']; 
    $SAVI_profile_nationality = get_post_meta($postID,'savi_views_contact-details_nationality',true);
	if($SAVI_profile_nationality=="") return $a['error_message'];
	return $SAVI_profile_nationality;
}
add_shortcode( 'SAVI_profile_age', 'SAVI_profile_age_func' );
function SAVI_profile_age_func($atts){
	//print_r($atts);
   $a = shortcode_atts( array(
        'profile_id' => '0',
        'user_id'=> '0',
        'user_pwd'=>'',
        'need_visa'=>'false',
        'post_type'=>'',
        'opportunity_id'=>'',
		'error_message' => 'Hello, Profile Age is an error, please contact the Savi team!'
    ), $atts );
   	$postID = $a['profile_id'];
	if($postID<1) return $a['error_message']; 
    $SAVI_profile_age = get_post_meta($postID,'savi_views_contact-details_age',true);
	if($SAVI_profile_age=="") return $a['error_message'];
	return $SAVI_profile_age;
}

add_shortcode( 'SAVI_profile_skills', 'SAVI_profile_skills_func' );
function SAVI_profile_skills_func($atts){
	//print_r($atts);
   $a = shortcode_atts( array(
        'profile_id' => '0',
        'user_id'=> '0',
        'user_pwd'=>'',
        'need_visa'=>'false',
        'post_type'=>'',
        'opportunity_id'=>'',
		'error_message' => 'Hello, Profile skills is an error, please contact the Savi team!'
    ), $atts );
	$postID = $a['profile_id'];
	if($postID<1) return $a['error_message']; 
    $Skills = get_post_meta($postID,'savi_views_skills_fields-of-interest',true);
	$Skills = str_replace('&quot;', '"', $Skills);
	$Skills = htmlspecialchars( $Skills);
	$Skills =nl2br( $Skills );
	if($Skills=="") return $a['error_message'];
	return $Skills;
 
}
add_shortcode( 'SAVI_profile_page_link', 'SAVI_profile_page_link_func' );
function SAVI_profile_page_link_func($atts){
	//print_r($atts);
   $a = shortcode_atts( array(
        'profile_id' => '0',
        'user_id'=> '0',
        'user_pwd'=>'',
        'need_visa'=>'false',
        'post_type'=>'',
        'opportunity_id'=>''
    ), $atts );
	$site_url = get_bloginfo('wpurl');
	$SAVI_profile_page_link = "<a href='".$site_url."/my-opportunity/"."'>My Opportunities</a>";
	return $SAVI_profile_page_link;
}
add_shortcode( 'SAVI_VISA_process', 'SAVI_VISA_process_func' );
function SAVI_VISA_process_func($atts){
	//print_r($atts);
   $a = shortcode_atts( array(
        'profile_id' => '0',
        'user_id'=> '0',
        'user_pwd'=>'',
        'need_visa'=>'false',
        'post_type'=>'',
        'opportunity_id'=>'',
		'error_message' => 'Hello, there is an error, please contact the Savi team!',
        'message' => 'Hello, there is an error, please contact the Savi team!'
    ), $atts );
	
	if($a['profile_id']<1) return $a['error_message'];
	if($a['need_visa']==="false") return "";
    return "<strong>".$a['message']."</strong>";
}
add_shortcode( 'SAVI_opportunity_title', 'SAVI_opportunity_title_func' );
function SAVI_opportunity_title_func($atts){
	//print_r($atts);
   $a = shortcode_atts( array(
        'profile_id' => '0',
        'user_id'=> '0',
        'user_pwd'=>'',
        'need_visa'=>'false',
        'post_type'=>'',
		'opportunity_id'=>'0',
		'error_message' => 'Hello, Profile Opportunity Title is an error, please contact the Savi team!'
    ), $atts );
	$oppID = $a['opportunity_id'];
	if($oppID<1) return $a['error_message']; 
	$oppTitle = get_the_title($oppID);
	return $oppTitle;

}
add_shortcode( 'SAVI_opportunity_unit', 'SAVI_opportunity_unit_func' );
function SAVI_opportunity_unit_func($atts){
	//print_r($atts);
   $a = shortcode_atts( array(
        'profile_id' => '0',
        'user_id'=> '0',
        'user_pwd'=>'',
        'need_visa'=>'false',
        'post_type'=>'',
		'opportunity_id'=>'0',
		'error_message' => 'Hello, Profile Opportunity Unit is an error, please contact the Savi team!'
    ), $atts );
	$oppID = $a['opportunity_id'];
	if($oppID<1) return $a['error_message']; 
	$unitID = get_post_meta($oppID,'av_unit',true);
	$unitTitle = get_the_title($unitID);
	return $unitTitle;
}
add_shortcode( 'SAVI_profile_passport', 'SAVI_profile_passport_func' );
function SAVI_profile_passport_func($atts){
	//print_r($atts);
   $a = shortcode_atts( array(
        'profile_id' => '0',
        'user_id'=> '0',
        'user_pwd'=>'',
        'need_visa'=>'false',
        'post_type'=>'',
		'opportunity_id'=>'0',
		'error_message' => 'Hello, Profile Passport Number is an error, please contact the Savi team!'
    ), $atts );
	$profile_id = $a['profile_id'];
	if($profile_id<1) return $a['error_message']; 
	$profile_passport = get_post_meta($profile_id,'savi_views_visa-details_passport-number',true);
	return $profile_passport;
}
add_shortcode( 'SAVI_profile_address', 'SAVI_profile_address_func' );
function SAVI_profile_address_func($atts){
	//print_r($atts);
   $a = shortcode_atts( array(
        'profile_id' => '0',
        'user_id'=> '0',
        'user_pwd'=>'',
        'need_visa'=>'false',
        'post_type'=>'',
		'opportunity_id'=>'0',
		'error_message' => 'Hello, Profile Address is an error, please contact the Savi team!'
    ), $atts );
	$profile_id = $a['profile_id'];
	if($profile_id<1) return $a['error_message']; 
	$profile_address = get_post_meta($profile_id,'savi_views_contact-details_address',true);
	return $profile_address;
}

?>
