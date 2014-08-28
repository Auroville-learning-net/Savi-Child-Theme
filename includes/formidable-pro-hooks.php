<?php
/*
-------------FORMIDABLE PRO HOOKS-------------------
*/

add_filter('frm_available_fields','sy_add_WA_field');
function sy_add_WA_field($fields){
		$fields['workArea'] = 'Work Areas';
		$fields['workType'] = 'Work Types';
		$fields['selectIcon'] = 'Icon Check';
		$fields['conformedOpportunity'] = 'Confirm Opp';
		return $fields;
}

add_filter('frm_replace_shortcodes','sy_form_shortcodes',10, 3);
function sy_form_shortcodes($html, $field, $args){
	if (!$field) return $html;
	switch($field['type']) {
		case 'workArea':
			$termArgs = array(
			  'orderby'     => 'name',
			  'hide_empty' => false
			);
			//print_r($field['value']);
			
				if($field['value']=="savi0"): // All work area category 
					$checked = "checked='checked'";
				endif;
			$terms = get_terms("savi_opp_cat_work_area",$termArgs);
			$html = '<div id="frm_field_'.$field['id'].'_container" class="frm_form_field form-field  horizontal_radio '.$field['classes'].'">';
			$html =  $html.'<label for="field_'.$field['field_key'].'" class="frm_primary_label">'.$field['name'];
    		$html =  $html.'<span class="frm_required">'.$field['required_indicator'].'</span></label><ul class="form-taxonomy-list"><li><ul class="top-taxonomy-group"><li id="frm_checkbox_467-999" class="frm_checkbox work-area-checkbox1"><input type="checkbox" value="savi0" id="field_467-999" name="item_meta[467][] class="work-area-checkbox1" '. $checked .'  "><label for="field_467-999">Any Work Areas</label></li></ul></li>';
			if ( !empty( $terms ) && !is_wp_error( $terms ) ){
				$termsOrdered = array();
				sort_terms_hierarchicaly($terms, $termsOrdered);
				$idx=0;
				foreach($termsOrdered as $parent){
					$children = $parent->children;
					if(!$children){	
						$html = $html.sy_fpForm_checkbox($field['id'],$idx, $parent->term_id,$parent->name,'work-area-checkbox',$field['value']); //get the new input field
						$idx+=1;
					}else{
					 	$html = $html.'<li><ul class="top-taxonomy-group"><li class="top-level-taxonomy"><h5 class="parent-taxonomy">'.$parent->name.'</h5></li><li><ul class="children-taxonomy-list">';
						foreach($children as $term){
							$html = $html.sy_fpForm_checkbox($field['id'],$idx, $term->term_id,$term->name,'work-area-checkbox',$field['value']); 
							$idx+=1;
						}
						$html = $html.'</ul><!-- children --></li></ul><!-- group --></li>';
					}
				}
			}
			$html =  $html.'</ul><div class="frm_description">'.$field['description'].'</div></div>';
			break;
		case 'workType':
			$termArgs = array(
			  'orderby'     => 'name',
			  'hide_empty' => false
			);
			
			$terms = get_terms("savi_opp_cat_work_type",$termArgs);
			$html = '<div id="frm_field_'.$field['id'].'_container" class="frm_form_field form-field  horizontal_radio '.$field['classes'].'">';
			$html =  $html.'<label for="field_'.$field['field_key'].'" class="frm_primary_label">'.$field['name'];
    		$html =  $html.'<span class="frm_required">'.$field['required_indicator'].'</span></label><ul class="form-taxonomy-list">';
			if ( !empty( $terms ) && !is_wp_error( $terms ) ){
				$termsOrdered = array();
				sort_terms_hierarchicaly($terms, $termsOrdered);
				$idx=0;
				foreach($termsOrdered as $parent){
					$children = $parent->children;
					if(!$children){	
						$html = $html.sy_fpForm_checkbox($field['id'],$idx, $parent->term_id,$parent->name,'work-type-checkbox',$field['value']); //get the new input field
						$idx+=1;
					}else{ 
						$html = $html.'<li><ul class="top-taxonomy-group"><li class="top-level-taxonomy"><h5 class="parent-taxonomy">'.$parent->name.'</h5></li><li><ul class="children-taxonomy-list">';
						foreach($children as $term){
							$html = $html.sy_fpForm_checkbox($field['id'],$idx, $term->term_id,$term->name,'work-type-checkbox',$field['value']); 
							$idx+=1;
						}
						$html = $html.'</ul><!-- children --></li></ul><!-- group --></li>';
					}
				}
			}
			$html =  $html.'</ul><div class="frm_description">'.$field['description'].'</div></div>';
			break;
		case 'selectIcon':
			$html = '<div id="frm_field_'.$field['id'].'_container" class="frm_form_field form-field  horizontal_radio '.$field['classes'].'">';
			$html =  $html.'<label for="field_'.$field['field_key'].'" class="frm_primary_label">'.$field['name'];
    		$html =  $html.'<span class="frm_required">'.$field['required_indicator'].'</span></label><ul class="form-icon-select">';
    		$html =  $html.'<li id="frm_checkbox_'.$field['id'].'-0" class="frm_checkbox">';
			$html =  $html.'<input type="checkbox" name="item_meta['.$field['id'].'][]" value="select" id="select-icon"/><label for="select-icon"></label>';
			$html =  $html.'</li><div class="frm_description">'.$field['description'].'</div></div>';
			break;
		case 'conformedOpportunity':
			if ( is_user_logged_in() ) {
					$user_id = get_current_user_id(); 
					$args = array(
						'author'    => $user_id, // I could also use $user_ID, right?
						'post_type' => 'view_3'
						);
				//echo "<pre>",print_r($args),"</pre>";		
				$the_query = new WP_Query( $args );
				//The Loop
				if ( $the_query->have_posts() ) {
					$html = '<div id="frm_field_'.$field['id'].'_container" class="frm_form_field form-field  frm_left_container'.$field['classes'].'">';
					while ( $the_query->have_posts() ) {
						$the_query->the_post();
						$ordered_opp_IDs = get_post_meta( get_the_ID(), "ordered_opportunity", true );
						$exp_opp = get_post_meta( get_the_ID(), 'express_opportunities', true );
						$html =  $html. '<ol id="sortable">';
						$opp_list = '';
						if($ordered_opp_IDs == ''){
							foreach ($exp_opp as $key=>$exp_opp1) {
								$opp_list.= "opp[]=".$exp_opp1['express_opportunity']."&";
								$html =  $html. '<li id="opp_'.$exp_opp1['express_opportunity'].'" class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>'. get_the_title($exp_opp1['express_opportunity']).'<a href="'. get_permalink($exp_opp1['express_opportunity']) .'" target="_blank" class="dashicons dashicons-info opp-icon-info"></a></li>';
							}
							// $opp_list = substr($opp_list, 0, -1); //remove last '&' char
						}else{
							
							foreach ($ordered_opp_IDs as $ordered_opp) {
								$opp_list.= "opp[]=".$ordered_opp."&";
								//echo "<pre>",print_r($opp_list),"</pre>";
								$ordered_opp_title = get_the_title($ordered_opp);
								$html =  $html. '<li id="opp_'. $ordered_opp .'" class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>'. $ordered_opp_title .'<a href="'. get_permalink($ordered_opp) .'" target="_blank" class="dashicons dashicons-info opp-icon-info"></a></li>';
							}
							$opp_list = substr($opp_list, 0, -1); //remove last '&' char
						}
						$html =  $html. '</ol>';
					}
					$html =  $html.'<label for="field_'.$field['field_key'].'" class="frm_primary_label">'.$field['name'];
					$html =  $html.'<span class="frm_required">'.$field['required_indicator'].'</span></label><input type="hidden" id="orderedOpps" name="item_meta['.$field['id'].']" value="'.$opp_list.'">';
					$html =  $html.'<input type="hidden" name="postID" value="'.get_the_ID().'"><div class="frm_description">'.$field['description'].'</div></div>';
				} else {
					$html =  "no posts found";
				}
				wp_reset_postdata();
			}else{
				$html = "Please login";
			}
			
			break;
		default:
			break;
	}
	return $html;
}
function sy_fpForm_checkbox($fieldId,$idx, $value, $label,$cssClass,$formTerms = array()){
	$html = '<li id="frm_checkbox_'.$fieldId.'-'.$idx.'" class="frm_checkbox '.$cssClass.'">';
	//print_r($formTerms);
   if(is_array($formTerms) && count($formTerms) > 0):	
			
			if(in_array($value,$formTerms)):
	 			$html = $html.'<input class="'.$cssClass.'" type="checkbox" checked="checked" value="'.$value.'" id="field_'.$fieldId.'-'.$idx.'" name="item_meta['.$fieldId.'][]">';
	 	    else:
         	$html = $html.'<input class="'.$cssClass.'" type="checkbox" value="'.$value.'" id="field_'.$fieldId.'-'.$idx.'" name="item_meta['.$fieldId.'][]">'; 		
   	   endif;
   else:
		if($value == $formTerms ){
			$html = $html.'<input class="'.$cssClass.'" type="checkbox" checked="checked" value="'.$value.'" id="field_'.$fieldId.'-'.$idx.'" name="item_meta['.$fieldId.'][]">';
			
		}else{
			$html = $html.'<input class="'.$cssClass.'" type="checkbox"  value="'.$value.'" id="field_'.$fieldId.'-'.$idx.'" name="item_meta['.$fieldId.'][]">';
		}
	endif;
	$html = $html.'<label for="field_'.$fieldId.'-'.$idx.'">'.$label.'</label></li>';
	//echo $html;
	return $html;
}

/**
 * Recursively sort an array of taxonomy terms hierarchically. Child categories will be
 * placed under a 'children' member of their parent term.
 * @param Array   $cats     taxonomy term objects to sort
 * @param Array   $into     result array to put them in
 * @param integer $parentId the current parent ID to put them in
 */
function sort_terms_hierarchicaly(Array &$cats, Array &$into, $parentId = 0)
{
    foreach ($cats as $i => $cat) {
        if ($cat->parent == $parentId) {
        	//echo '<p>'.$cat->name.'</p>';
            $into[$cat->term_id] = $cat;
            unset($cats[$i]);
        }
    }

    foreach ($into as $topCat) {
        $topCat->children = array();
        sort_terms_hierarchicaly($cats, $topCat->children, $topCat->term_id);
    }
}
/*
add_action('frm_after_create_entry', 'savi_after_form_entry', 30, 2);
function savi_after_form_entry($entry_id, $form_id){
  if($form_id == 19){ //change 5 to the ID of your form
  	$workTypeId = 468;
  	$workAreaId = 467;
  	$workType = $workArea = null;
    if(isset($_POST['item_meta'][$workTypeId]))  $workType = $_POST['item_meta'][$workTypeId];
    if(isset($_POST['item_meta'][$workAreaId]))  $workArea = $_POST['item_meta'][$workAreaId];
  }
}
*/
/*
----------- Reset the countries value in select option from IDs to names
*/
add_filter('frm_setup_new_fields_vars', 'savi_reset_countries', 20, 2);
function savi_reset_countries($values, $field){
if($field->id == 737){//Nationality field
	$newValues = array();
	foreach($values['options'] as $key => $value) $newValues[$value]=$value;
   	$values['options']=$newValues;
}
return $values;
}
/*
---------- FORM HOOK: this is for combine the First & Last Name of the Volunteer entire
*/
add_filter('frm_validate_field_entry', 'savi_combine_two_fields', 8, 3);
function savi_combine_two_fields($errors, $posted_field, $posted_value){
  if($posted_field->id == 271){ 
    $_POST['item_meta'][271] = $_POST['item_meta'][227] .' '. $_POST['item_meta'][241];
  }
  if($posted_field->id == 441){ 
    $_POST['item_meta'][441] = $_POST['item_meta'][443] .' '. $_POST['item_meta'][444];
  }
  return $errors;
}
add_filter('frm_validate_field_entry', 'savi_validate_email', 10, 3);
function savi_validate_email($errors, $posted_field, $posted_value){
	if($posted_field->id == 268){ //email id field
		$email = $_POST['item_meta'][268];
		if(email_exists( $email )) $errors['field'. $posted_field->id] = 'This email has already been registered, please contact us!';
	}
	return $errors;
}/*
-----------------------MANAGE DATA Submitted by forms ---------------------------
*/
add_action('frm_after_create_entry', 'save_opportunity_ordered_list', 30, 2);
function save_opportunity_ordered_list($entry_id, $form_id){
 if($form_id == 34){ //replace 5 with the id of the form
    $orderList = array();
    if(isset($_POST['item_meta'][759])){//replace 30 and 31 with the appropriate field IDs from your form
		$orderList = unserialize_jQuery_array($_POST['item_meta'][759]); //change 'data1' to the named parameter to send 
		$arraySize = sizeof($orderList);
        $allorderOpportunities = array();
        for ($arrayIndex=0;$arrayIndex<$arraySize; $arrayIndex++) {
            $order_opportunity = $orderList[$arrayIndex];
            if(trim($order_opportunity)!=""){
               $allorderOpportunityInfo = $order_opportunity;
               $allorderOpportunities[$arrayIndex] = $allorderOpportunityInfo;
            }
        }
		$postID = $_POST['postID'];
		//echo "post=".$postID;
		update_post_meta($postID,'ordered_opportunity',$allorderOpportunities);
	}
 }
}
function unserialize_jQuery_array($strfromPOST){
    $array = "";
    $returndata = "";
    $strArray = explode("&", $strfromPOST);
    $i = 0;
    foreach ($strArray as $str){
        $array = explode("=", $str);
        //$returndata[$i] = $array[0];  this first element is the name of the js array which we can discard
        //$i = $i + 1;
        $returndata[$i] = $array[1]; //the 2nd element is the actual value we want to preserve
        $i = $i + 1;
    }
	//print_r($returndata);
    return $returndata;
}
add_action( 'wp_footer', 'savi_load_form_scripts_in_footer' );
function savi_load_form_scripts_in_footer(){
	switch(true){
		case is_page( 'profile-registration' ): 
			savi_skills_workarea_styles();
			break;
	}
}
function savi_skills_workarea_styles(){ ?>
 <script>		
		jQuery(function($) {
			//Indian Visa requirement make the field disabled
		/*		$('#field_19nbo42').attr('disabled', 'disabled');
			$('#field_jmlqv').attr('readonly', 'readonly'); */
			$('#field_19nbo42').attr('readonly', 'readonly');
			
			//Any work area field in Volunteer Profile Registration Form
			$('#field_467-999').click(function () {
				if ($(this).is(":checked")){
					$(".work-area-checkbox").attr ( "disabled" , true );
					$(".work-area-checkbox").removeAttr('checked');
				}else{
					$(".work-area-checkbox").removeAttr ( "disabled" );
				}
				val = $(this).val();
			});
			if ($('#field_467-999').is(":checked")){
				$(".work-area-checkbox").attr ( "disabled" , true );
			}

			var floatinduration;
			var arrival_date;
			var departure_date;
			var duration; 
			//Planned stay duration Volunteer Profile Registration Form
			$(document).ready(function() {
			  duration =  $('#field_wu6ol42').val();
			  floatinduration = parseFloat(duration);				 
			  //Planned arrival date Volunteer Profile Registration Form
			  $("#field_f8138t2").datepicker({
				dateFormat: "dd/mm/yy",
					onSelect: function(arrival_date, instance){
						date = $.datepicker.parseDate(instance.settings.dateFormat, arrival_date, instance.settings);
						date.setMonth(date.getMonth() + floatinduration);
						$("#field_gg05en2").datepicker("setDate", date);
						arrival_date = $(this).attr('value');
					}
				});
				//Math for month difference departure_date - arrival date (Volunteer Profile Registration Form)
				function monthDiff(d1, d2) {
					var months;
					months = (d2.getFullYear() - d1.getFullYear()) * 12;
					months -= d1.getMonth() + 0;
					months += d2.getMonth();
					days = d2.getDate() - d1.getDate();
					months += Math.round(10* (days / 365) * 12)/10; 
					return months <= 0 ? 0 : months; 
				}
				
				//departure_date for Volunteer Profile Registration Form
				$("#field_gg05en2").datepicker({
					dateFormat: "dd/mm/yy",
					onSelect: function(departure_date, instance) {	
						d1 = $.datepicker.parseDate("dd/mm/yy", $( "#field_f8138t2" ).val());
						d2 = $.datepicker.parseDate("dd/mm/yy", $( "#field_gg05en2" ).val());
						$('#field_wu6ol42').attr('value', monthDiff(d1, d2));
					}
				})
			});
			
			//Nationality field initial Registration Form
				nationality =  $('#field_jmlqv').val();
				if (nationality == "India"){
					$('#field_19nbo42').val('No');
				}else{
					$('#field_19nbo42').val('Yes');
				}
		
			
		});
</script>
<?php
} 

//Auto calculate the age from DOB field
add_filter('frm_validate_field_entry', 'savi_calculate_age', 11, 3);
function savi_calculate_age($errors, $field, $value){
	if($field->id == 780){ //The hidden field you added to hold the calculation
		//global $frmpro_settings;
		$birthDate = str_replace('/','-',$_POST['item_meta'][751]);
		
		$age = floor((time() - strtotime($birthDate)) / 31556926);
		$_POST['frm_wp_post_custom']['780=savi_views_contact-details_age'] =$age;
	}
	return $errors;
}
add_filter('frm_validate_field_entry', 'save_temporary_update', 11, 3);
function save_temporary_update($errors, $field, $value){
		global $wpdb, $frmdb, $frm_field;
		switch($field->id){
				case 385: //Indian phone in profile form
						$value = $_POST['item_meta'][$field->id];
						//$_POST['frm_wp_post_custom']['385=savi_views_contact-details_phone-number-in-india'] =$value;
						//echo "<p>".$_POST['frm_saving_draft']."</p>";
						//print_r($frmdb);
						break;
		}
		return $errors;
}

//Set the DOB Default date
add_action('frm_date_field_js', 'savi_limit_DOB_field'); 
function savi_limit_DOB_field($field_id){
  if($field_id == 'field_qs3p5t'){ //change FIELDKEY to the keys of your date fields
    echo ',defaultDate:"-25y"';
  }
}

//Make default nationality india in Volunteer Profile Registration Form
add_filter('frm_validate_field_entry', 'savi_set_nationality', 11, 3);
function savi_set_nationality($errors, $field, $value){
   if($field->id == 941){ //The hidden field you added to hold the calculation
		//global $frmpro_settings;
		$postID = $_POST['postID'];
		$nationality = $_POST['item_meta'][941];
		//echo $nationality.'Nation';
		if($nationality == "India" || $nationality == "" ){
			//echo $nationality.'here';
			$_POST['frm_wp_post_custom']['941=savi_views_contact-details_nationality'] = "India";
		}else{
			$_POST['frm_wp_post_custom']['941=savi_views_contact-details_nationality'] = $nationality;
		}
		
	}
	return $errors;

}
//The hidden field update the view_0_created meta field with timestamp in view _0 post created
add_filter('frm_validate_field_entry', 'savi_view_0_create_timestamp', 11, 3);
function savi_view_0_create_timestamp($errors, $field, $value){
   if($field->id == 942){ //The hidden field update the view_0_created meta field with timestamp in view _0 post created
		$timestamp = $_POST['item_meta'][942];
	    $blogtime = current_time( 'mysql' ); 
		if($timestamp == ""){
			$_POST['frm_wp_post_custom']['942=view_0_created'] = $blogtime;
		}
	}
	return $errors;
}
//The hidden field update the ordered_opportunity_date meta field with timestamp in ordered opportunity sorted created
add_filter('frm_validate_field_entry', 'ordered_opportunity_timestamp', 11, 3);
function ordered_opportunity_timestamp($errors, $field, $value){
   if($field->id == 1016){ //The hidden field update the ordered_opportunity_date meta field with timestamp in ordered opportunity sorted created
		$current_time_updated = array();
		$postID = $_POST['postID'];
		//echo $postID;
		$current_time_updated = get_post_meta($postID, 'ordered_opportunity_date', true); //get the time from the post meta 
		$current_time = current_time( 'mysql' ); // get the current time  
		$arraySized = sizeof($current_time_updated);	 //check the size of the array
		if ($arraySized > 0 && is_array($current_time_updated)) {
		//	echo 'here';
			$current_time_updated[] = $current_time;
		}else{
			$current_time_updated[0] = $current_time;
		}
		
		update_post_meta( $postID, 'ordered_opportunity_date', $current_time_updated); // Here updating the the post meta of the ordered opportunity date
	}
	return $errors;
}
// this hook when new registration form submitted from frontend

add_action('frm_after_update_entry', 'savi_adjust_profile_info', 30, 2);
function savi_adjust_profile_info($entry_id, $form_id){
	global $wpdb;
  // get the current post user email
  
  if( $form_id == 19 ){
  
   $post_id =   $wpdb->get_var("SELECT post_id FROM wp_frm_items WHERE id =".$entry_id);
   
   $user_email = $wpdb->get_var("SELECT meta_value FROM wp_postmeta WHERE post_id =".$post_id.
                               " AND meta_key ='savi_views_contact-details_email'" );
                               
    
   // get the count of the post by the user email id                         
   $check_old_post = $wpdb->get_var("SELECT count(wp.ID)  FROM wp_postmeta wpm,wp_posts wp WHERE wpm.post_id = wp.ID 
                                    AND wpm.meta_key ='savi_views_contact-details_email' 
                                    AND wpm.meta_value = '".$user_email."'");
     
                                    
   if($check_old_post > 1 ){ // check the new post having old post exits, if exists delete the old post and update post meta
	   // Get the Old post id  
	   $old_post_id = $wpdb->get_var("SELECT  min(wp.ID) FROM wp_postmeta wpm,wp_posts wp WHERE wpm.post_id = wp.ID 
                                    AND wpm.meta_key ='savi_views_contact-details_email' 
                                    AND wpm.meta_value = '".$user_email."'");
       // Get the new post id                             
       $new_post_id = $wpdb->get_var("SELECT  max(wp.ID) FROM wp_postmeta wpm,wp_posts wp WHERE wpm.post_id = wp.ID 
                                    AND wpm.meta_key ='savi_views_contact-details_email' 
                                    AND wpm.meta_value = '".$user_email."'");          
       // getting post meta field from old post                                             
       $old_post_user_id = get_post_meta($old_post_id,'user_id',true);                             
       $old_view_0_created = get_post_meta($old_post_id,'view_0_created',true);                             
       $old_view_1_created = get_post_meta($old_post_id,'view_1_created',true);   
       
       // Updating the old post meta into new post meta field                      
	      update_post_meta($new_post_id,'user_id',$old_post_user_id);                             
          update_post_meta($new_post_id,'view_0_created',$old_view_0_created);                             
          update_post_meta($new_post_id,'view_1_created',$old_view_1_created); 
          update_post_meta($new_post_id,'profile_incomplete','no'); 
       
       // update user meta field profile_post_id from old profile post id to new profile post id
           update_user_meta($old_post_user_id, 'profile_post_id', $new_post_id);
       
           
      // delete the old post and post meta from the database
      
        $wpdb->query("delete from wp_posts where ID =".$old_post_id);
	    $wpdb->query("delete from wp_postmeta where post_id =".$old_post_id);      
   }
  
 }  	
	
}


?>
