<?php
/*
Template Name: Mentor Opportunities
*/
 get_header(); 
 ?>

<div id="main-content">
	<div class="et_pb_section et_section_specialty">

		<div class="et_pb_row">
			<div class="et_pb_column et_pb_column_1_4">
					<?php get_sidebar(); ?>
			</div>
			<div class="et_pb_column et_pb_column_3_4">
			<?php
			$user_id = get_current_user_id();
			$mentor = get_user_meta($user_id,'savi_role',true);
			//get the savi role mentor or volunteers 
			if ( is_user_logged_in() ) { //if user is loggrd in 
				if($mentor=="opportunity-owner"){   //if mentor role is mentor
					$args = array(
						'post_type' => 'av_opportunity',
						'meta_query' => array(
											array(
												'key' => 'contact_user',
												'value' => $user_id,
												'compare' => '=',
												'type' => NUMERIC,
											)
								),									
					);
					query_posts($args);		
					//query_postsis passing all opportunity posts with contact user id = logged in users
					if ( have_posts() ) :
						while ( have_posts() ) : the_post();
							$post_format = get_post_format();
							$contact_user = get_post_meta( get_the_ID(), "contact_user", true ); //get the contact user
							$expressed_volunteer = get_post_meta( get_the_ID(), "expressed_volunteer", true );  //get the expressed volunteer
							$my_opp_ID = get_the_ID(); //get the my opp ID
							$loading_image  = get_stylesheet_directory_uri()."/images/ajax-loader.gif";
							if (isset($_GET["submit".$my_opp_ID])  ){
								 $current_time_updatedMeta = array();
								 $current_time_updatedMeta = get_post_meta($my_opp_ID,'ordered_new_volunteer_date',true);
								 $current_time = current_time( 'mysql' );
								 $arraySized = sizeof($current_time_updatedMeta);	
								 if ($arraySized > 0 && is_array($current_time_updated)) {
									$current_time_updatedMeta[] = $current_time;
								 }
								 else {
									$current_time_updatedMeta[0] = $current_time;
								 }
								 $orderList = array();
								 $orderList = unserialize_jQuery_array($_GET["volList-".$my_opp_ID]);
								 $arraySize = sizeof($orderList);
								 $allexpressvolunteers = array();
								 for ($arrayIndex=0;$arrayIndex<$arraySize; $arrayIndex++) {
									$express_volunteer = $orderList[$arrayIndex];
									if(trim($express_volunteer)!=""){
									   $allexpressvolunteerInfo = $express_volunteer;
									   $allexpressvolunteers[$arrayIndex] = $allexpressvolunteerInfo;
									}
								 }
								  update_post_meta( $my_opp_ID, 'ordered_new_volunteer', $allexpressvolunteers);
								  update_post_meta( $my_opp_ID, 'ordered_new_volunteer_date', $current_time_updated);
							}
							?>
							<article id="post-<?php the_ID(); ?>" <?php post_class( 'et_pb_post' ); ?>>
							<?php
							echo "<h4 class='opp_title'><a class='dashicons dashicons-info' href=". get_the_permalink() .">". get_the_title() ."</a></h4>";  //Display the title of the opportunity
							echo '<ul id="mentor-opportunities">';
							if( !empty($expressed_volunteer) && is_array($expressed_volunteer)){
								//set up fresh array for each opp
								$new_volunteers = array(); //store new volunteer expressed interest in this opp
								$my_opp_volunteers = array(); // store volunteers active on this opportunity
								$grey_volunteers = array(); //store volunteers from past who are now not available any more, they will be shown as greyed out.
								$ordered_new_volunteer_IDs = get_post_meta( $my_opp_ID, "ordered_new_volunteer", true );
								$ordered_new_volunteer = array();
								foreach($expressed_volunteer as $vol_user_ID){ // get the volunteers user id
									$volunteer_user = get_userdata($vol_user_ID); // get the volunteers userdata
									$profile_post_id = get_user_meta( $vol_user_ID, "profile_post_id", true ); // get the profile  post_id 
									$volunteer_opportunity_ID = get_post_meta($profile_post_id,'volunteer_opportunity',true); // get the volunteer opportunity
									$post_type = get_post_type( $profile_post_id ); // get the profile post type
									switch($post_type){
										//here we get the view_3 new volunteer ID's
										case 'view_1':
										case 'view_2':
										break;
										case 'view_3': //volunteer has expressed interest in this opportunity
											$new_volunteers[$vol_user_ID] = $volunteer_user;		 // get the new volunteer ID's	
										break;
										//here we get the view_4,5,6,7,default new volunteer ID's
										case 'view_4': //volunteer is already mapped to an opportunity
										case 'view_5': 
										case 'view_6':
										case 'view_7':
										default:
											//check if the mapped opportunity is this one
											if($volunteer_opportunity_ID == $my_opp_ID){
												$my_opp_volunteers[] = $volunteer_user;  
											}else{  //this volunteer is mapped to another opportunity hence show as greyed out
												$grey_volunteers[] = $volunteer_user;																		
											}
										break;
									}
								 }		
								//Display the Active Profiles volunteers				
								if( !empty($my_opp_volunteers)){
									echo '<li><h5>Active Profiles</h5><ul class="active-volunteers">';
									foreach($my_opp_volunteers as  $vUser){
										if(!empty($vUser->display_name)){ //Incase the User has been delete it has filter here
											echo  '<li><a href="'. get_author_posts_url($vUser->ID).'">'.$vUser->display_name.'</a></li>';
										}
									}
									echo '</ul></li>';
								}
								//Display sortable form of New volunteers Profiles
								if( !empty($new_volunteers) ){
									$volunteerList = "";
									echo '<li><h5>New Profiles</h5><form class="my_opp_vol" id="vols_'.$my_opp_ID.'" action="" type="get">';
									echo '<ol id="sortable-'.$my_opp_ID.'" class="ui-sortable">';
								    if(is_array($ordered_new_volunteer_IDs) && !empty($ordered_new_volunteer_IDs)){ 
										foreach($ordered_new_volunteer_IDs as $vol_user_ID){
											$ordered_new_volunteer[]=$new_volunteers[$vol_user_ID];
											unset($new_volunteers[$vol_user_ID]);
										}
										$new_volunteers = $ordered_new_volunteer + $new_volunteers;
									 }
									if(count($new_volunteers)>0){
										foreach($new_volunteers as  $vUser){
											$volunteerList.= "vol[]=".$vUser->ID."&";
											if(!empty($vUser->display_name)){ //Incase the User has been delete it has filter here
												echo '<li id="vol_'.$vUser->ID.'" class="ui-state-default ui-sortable-handle"  style=""><span class="ui-icon ui-icon-arrowthick-2-n-s"></span><a href="'. get_author_posts_url($vUser->ID).'">'.$vUser->display_name.'</a><a href="javascript:void(0)" class="dashicons dashicons-no delete"></a></li>';
											}
										}
										$volunteerList = substr($volunteerList, 0, -1); //remove last '&' char
									}else{
										$volunteerList = "vol[]=".$new_volunteers[0]->ID;
										if(!empty($new_volunteers[0]->display_name)){ //Incase the User has been delete it has filter here
											echo '<li><a  href="'. get_author_posts_url($new_volunteers[0]->ID).'">'.$new_volunteers[0]->display_name.'</a><a href="javascript:void(0)" class="dashicons dashicons-no delete"></a></li>';
										}
									}
									echo '</ol>';
									echo ' <div class="ajax_image" id="loading_image_'.$my_opp_ID.'" style="display:none"><img src="'. $loading_image .'" alt="" ></div>';
									echo '<input class="my_volunteers" id="orderedVol-'.$my_opp_ID.'" type="hidden" value="'.$volunteerList.'" name="volList-'.$my_opp_ID.'">';
									echo ' <input class="my_vol" type="button" name="submit'.$my_opp_ID.'" value="Save my preference"> ';
									echo ' <input class="wait_for_prof" type="submit" name="wait'.$my_opp_ID.'" value="Wait for more profiles"> ';
									echo '</form></li>';
									savi_javascript_sortable_list('sortable-'.$my_opp_ID,'orderedVol-'.$my_opp_ID, true );
								}
								//Display the Post volunteers Profiles
								if( !empty($grey_volunteers) ){
									//echo "<pre>",print_r($grey_volunteers),"</pre>";
									echo '<li><h5>Past Profiles</h5><ul class="past-volunteers">';
									foreach($grey_volunteers as  $vUser){
										if(!empty($vUser->display_name)){ //Incase the User has been delete it has filter here
											echo  '<li>'.$vUser->display_name.'</li>';
										}
									}
									echo '</ul></li>';
								}
							}else{
								echo  '<li class="no-volunteer">Currently No volunteers has shown interest for this opportunity</li>';
							} 
							 echo '</ul>';  
							?>
							</article> <!-- .et_pb_post -->
							<?php
						endwhile;
						if ( function_exists( 'wp_pagenavi' ) )
							wp_pagenavi();
						else
							get_template_part( 'includes/navigation', 'index' );
					else :
						echo "<h2>You Currently have no opportunity registered in this data base</h2>";
					endif;
				}else{
					echo "<h2>You dont have access to this page</h2>";
				}
			}else{
				echo "<h2>Please Log in to view this page</h2>";
			}
			?>
			</div> <!-- et_pb_column_3_4 -->
		</div> <!-- et_pb_row -->
	</div> <!-- et_pb_section -->
</div> <!-- #main-content -->

<script type="text/javascript" >
	jQuery('.my_vol').click(function(e){
		var my_opp_ID = jQuery(this).parent('form').attr('id');
		opp_ID = my_opp_ID.split('_');
		vol_IDs = jQuery('#orderedVol-'+opp_ID[1]).val();
		var oppID = opp_ID[1];
	    jQuery('#ajax_loader').show();
		jQuery("input[name='submit"+oppID+"']").hide();
		jQuery('#sortable-'+oppID).css({ opacity: 0.3 });
		jQuery('#loading_image_'+oppID).show();
		jQuery.ajax({ 
		 data: {action: 'savi_volunteer_update_order', 
				oppID:oppID,
				 vol_IDs:vol_IDs,
				},
		 type: 'post',
		 url: "<?php echo get_bloginfo('url')?>/wp-admin/admin-ajax.php",
		 success: function(data) {
		  jQuery('#ajax_loader').hide();
		  if(data != 0) {
			  jQuery( "#vols_"+opp_ID).replaceWith(data);
		  }
		jQuery('#loading_image_'+oppID).hide();
		jQuery('.my_vol').show();
		jQuery('#sortable-'+oppID).css({ opacity: 1.0 });
		  }	
		});
	});
	jQuery('.wait_for_prof').click(function(e){
		alert('Please wait for more profile');
	});
</script>

<?php get_footer(); ?>

		});
	});
	jQuery('.wait_for_prof').click(function(e){
		alert('Please wait for more profile');
	});
</script>

<?php get_footer(); ?>
