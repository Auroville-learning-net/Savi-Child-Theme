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
			if ($field['value'] != ''){
				$checked = "";
			}else{
				$checked = "checked='checked'";
			}
			$terms = get_terms("savi_opp_cat_work_area",$termArgs);
			$html = '<div id="frm_field_'.$field['id'].'_container" class="frm_form_field form-field  horizontal_radio '.$field['classes'].'">';
			$html =  $html.'<label for="field_'.$field['field_key'].'" class="frm_primary_label">'.$field['name'];
    		$html =  $html.'<span class="frm_required">'.$field['required_indicator'].'</span></label><ul class="form-taxonomy-list">
			<ul class="children-taxonomy-list">
			<li id="frm_checkbox_'.$field['id'].'" class="frm_checkbox work-area-checkbox"><input id="field_'.$field['id'].'" class="work-area-checkbox1" type="checkbox"'. $checked .'  name="item_meta['.$field['id'].'][]" value="savi0" ><label  for="field_'.$field['id'].'">Any Work Areas</label></li></ul>';
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
		case 'conformedOpportunity';
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
						//echo get_the_ID();
						$exp_opp = get_post_meta( get_the_ID(), 'express_opportunities', true );
						///echo "<pre>",print_r($exp_opp),"</pre>";
						$html =  $html. '<ol id="sortable">';
						foreach ($exp_opp as $key=>$exp_opp1) {
							$html =  $html. '<li id="opp_'.$exp_opp1['express_opportunity'].'" class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>'. get_the_title($exp_opp1['express_opportunity']).'<a href="'. get_permalink($exp_opp1['express_opportunity']) .'" target="_blank" class="dashicons dashicons-info opp-icon-info"></a></li>';
						}
						$html =  $html. '</ol>';
					}
					$html =  $html.'<label for="field_'.$field['field_key'].'" class="frm_primary_label">'.$field['name'];
					$html =  $html.'<span class="frm_required">'.$field['required_indicator'].'</span></label><input type="hidden" id="orderedOpps" name="item_meta['.$field['id'].']" value="">';
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
   if(is_array($formTerms) && count($formTerms) > 0):	
			if(in_array($value,$formTerms)):
	 			$html = $html.'<input class="'.$cssClass.'" type="checkbox" checked="checked" value="'.$value.'" id="field_'.$fieldId.'-'.$idx.'" name="item_meta['.$fieldId.'][]">';
	 	    else:
         	$html = $html.'<input class="'.$cssClass.'" type="checkbox" value="'.$value.'" id="field_'.$fieldId.'-'.$idx.'" name="item_meta['.$fieldId.'][]">'; 		
   	   endif;
   else:
       	$html = $html.'<input class="'.$cssClass.'" type="checkbox" value="'.$value.'" id="field_'.$fieldId.'-'.$idx.'" name="item_meta['.$fieldId.'][]">';
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
add_action('frm_after_create_entry', 'sy_after_form_entry', 30, 2);
function sy_after_form_entry($entry_id, $form_id){
  if($form_id == 19){ //change 5 to the ID of your form
  	$workTypeId = 468;
  	$workAreaId = 467;
  	$workType = $workArea = null;
    if(isset($_POST['item_meta'][$workTypeId]))  $workType = $_POST['item_meta'][$workTypeId];
    if(isset($_POST['item_meta'][$workAreaId]))  $workArea = $_POST['item_meta'][$workAreaId];
    //echo "AREAS=".$workArea;
    //echo "TYPES=".$workType;
  }
}
/*
----------- Reset the countries value in select option from IDs to names
*/
add_filter('frm_setup_new_fields_vars', 'sy_reset_countries', 20, 2);
function sy_reset_countries($values, $field){
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
add_filter('frm_validate_field_entry', 'combine_two_fields', 8, 3);
function combine_two_fields($errors, $posted_field, $posted_value){
  if($posted_field->id == 271){ 
    $_POST['item_meta'][271] = $_POST['item_meta'][227] .' '. $_POST['item_meta'][241];
  }
  return $errors;
}
add_filter('frm_validate_field_entry', 'validate_custom_file_size', 10, 3);
function validate_custom_file_size($errors, $posted_field, $posted_value){
	if($posted_field->id == 268){ //email id field
		$email = $_POST['item_meta'][268];
		if(email_exists( $email )) $errors['field'. $posted_field->id] = 'This email has already been registered, please contact us!';
	}
	return $errors;
}
/*
-----------------------MANAGE DATA Submitted by forms ---------------------------
*/
add_action('frm_after_create_entry', 'save_opportunity_ordered_list', 30, 2);
function save_opportunity_ordered_list($entry_id, $form_id){
 if($form_id == 34){ //replace 5 with the id of the form
    $orderList = array();
    if(isset($_POST['item_meta'][759])){//replace 30 and 31 with the appropriate field IDs from your form
		$orderList = unserialize_jQuery_array($_POST['item_meta'][759]); //change 'data1' to the named parameter to send 
		$arraySize = sizeof($orderList);
        $allexpressOpportunities = array();
        for ($arrayIndex=0;$arrayIndex<$arraySize; $arrayIndex++) {
            $express_opportunity = $orderList[$arrayIndex];
            if(trim($express_opportunity)!=""){
               $allexpressOpportunityInfo = array (  "express_opportunity" => $express_opportunity);
               $allexpressOpportunities[$arrayIndex] = $allexpressOpportunityInfo;
            }
        }
		$postID = $_POST['postID'];
		//echo "post=".$postID;
		update_post_meta($postID,'express_opportunities',$allexpressOpportunities);
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
add_action( 'wp_footer', 'savi_skills_workarea_styles' );
function savi_skills_workarea_styles(){ ?>
 <script>
		jQuery(function($) {
			$('#field_467').click(function () {
			if ( $(this).is ( ":checked" ) )
				{
					$(".work-area-checkbox").attr ( "disabled" , true );
					$(".work-area-checkbox").removeAttr('checked');
				} else {
					$(".work-area-checkbox").removeAttr ( "disabled" );
					$(".work-area-checkbox").attr('checked');
				}
			})
		});
		
</script>
<?php
} 
//Auto calculate the age from DOB field
add_filter('frm_validate_field_entry', 'calculate_age', 11, 3);
function calculate_age($errors, $field, $value){
	if($field->id == 780){ //The hidden field you added to hold the calculation
	global $frmpro_settings;
	$birthDate = $_POST['item_meta'][751];
	$age = floor((time() - strtotime($birthDate)) / 31556926);
	//echo "<p>".$age."</p>";
	//$value = $_POST['item_meta'][780] = $age;
	$_POST['frm_wp_post_custom']['780=savi_views_contact-details_age'] =$age;
	}
	return $errors;
}

//Set the DOB Default date
add_action('frm_date_field_js', 'limit_DOB_field');
function limit_DOB_field($field_id){
  if($field_id == 'field_qs3p5t'){ //change FIELDKEY to the keys of your date fields
    echo ',defaultDate:"-25y"';
  }
}
?>