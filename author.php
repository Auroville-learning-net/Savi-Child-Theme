<?php
$curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));
$user_ID = $curauth->ID; // get the user id
$savi_role = get_usermeta($user_ID, 'savi_role', true);
 get_header(); ?>
 <div id="main-content">
	<div class="et_pb_section et_section_specialty">

		<div class="et_pb_row">
			<div class="et_pb_column et_pb_column_1_4">
					<?php get_sidebar(); ?>
			</div>
			<div class="et_pb_column et_pb_column_3_4">
				<?php
					if ( is_user_logged_in() && $savi_role == "volunteers" ) {
							?>
							<article id="post-<?php the_ID(); ?>" <?php post_class( 'et_pb_post' ); ?>>
								<?php
									$profile_post_id = get_user_meta($user_ID, 'profile_post_id', true); // get the profile post id
								//	echo $profile_post_id;
									$user_email = get_the_author_meta( 'user_email', $user_ID );
									$terms_work_area = FrmProEntriesController::get_field_value_shortcode(array('field_id' => 467, 'user_id' => $user_ID)); // get the work area
									$terms_work_type = FrmProEntriesController::get_field_value_shortcode(array('field_id' => 468, 'user_id' => $user_ID)); // get the work type
									$education = FrmProEntriesController::get_field_value_shortcode(array('field_id' => 414, 'user_id' => $user_ID)); // get the education
									$languages = FrmProEntriesController::get_field_value_shortcode(array('field_id' => 409, 'user_id' => $user_ID)); // get the languages									
									$first_name = get_post_meta( $profile_post_id, "savi_views_contact-details_first-name", true ); // get the first_name
									$last_name = get_post_meta( $profile_post_id, "savi_views_contact-details_last-name", true ); // get the last_name
									$stay_duration = get_post_meta( $profile_post_id, "savi_views_stay-details_duration", true ); // get the stay_duration
									$nationality = get_post_meta($profile_post_id, "savi_views_contact-details_nationality", true ); // get the nationality
									$skills = get_post_meta($profile_post_id, "savi_views_skills_fields-of-interest", true ); // get the skills
									$motivations = get_post_meta($profile_post_id, "savi_views_motivations_goals-skills-to-be-gained", true ); // get the motivations
									$internship = get_post_meta( $profile_post_id, "savi_views_education-details_intership", true ); // get the internship
									$phone_number = get_post_meta( $profile_post_id, "savi_views_contact-details_phone-number", true ); // get the phone number
									$phone_number_in_india = get_post_meta( $profile_post_id, "savi_views_contact-details_phone-number-in-india", true ); // get the phone number in india
									$showinfo = FALSE;	
									$profilepost = get_post_type( $profile_post_id ); // get the post type
									$message = "Volunteer profile details.";
									switch($profilepost){
										case 'view_0':
											$showinfo=false;
											$message = "This volunteer is still filling up their profile";
										break;
										case 'view_1':
											$showinfo=false;
											$message = "This volunteer is still filling up their profile";
										break;
										case 'view_2':
											$showinfo=true;
										break;
										case 'view_3':
											$showinfo=true;
										break;
										case 'view_4':
											$showinfo=false;
											$message = "This volunteer is no longer active, please contact the Savi team.";
										break;
										case 'view_5':
											$showinfo=true;
										break;
										case 'view_6':
											$showinfo=true;
										break;
										case 'view_7':
											$showinfo=true;
										break;
									}
								if ( $showinfo ) {	 ?>
									<div class="profile-view">
								<?php 
									echo '<h2>'.$message.'</h2>';
									echo '<h6><span class="bold">Full Name: '.$first_name .' '.$last_name.'</h6>';
									echo '<h6><span class="bold">Nationality:</span> '.$nationality.'</h6>';
									echo '<h6><span class="bold">Phone Number:</span> '.$phone_number.'</h6>';
									if($phone_number_in_india) echo '<h6><span class="bold">Phone Number in India:</span> '.$phone_number_in_india.'</h6>';
									echo '<p><span class="bold">Email:</span> <a href="mailto:'.$user_email.'">'.$user_email.'</a></p>';
									echo '<h6><span class="bold">Stay duration:</span> '.$stay_duration.'</h6>';
									// Displaying Work area
									if( $terms_work_area == 'savi0'){
										echo '<h6><span class="bold">Work Area: Any Work Areas<br />'; 
									}else{
										$terms_wa = explode(", ", $terms_work_area);
										if( count($terms_wa) > 1 && is_array($terms_wa) ){
											 // display array of terms starts 
											if ( $terms_wa && ! is_wp_error( $terms_wa ) ) : 
												$term_wa_names = array();
												foreach ( $terms_wa as $term_wa){
													$term_title = get_term( $term_wa, 'savi_opp_cat_work_area' );
													$term_wa_names[] = $term_title->name;
												}
												$term_wa = join( ", ", $term_wa_names );
												echo '<h6><span class="bold">Work Area:</span> '. $term_wa.'</h6>'; 
											endif;
											 // display array of terms ends
										}
										else{
											 // display single terms starts 
											echo '<h6><span class="bold">Work Area:</span> '. get_term( $terms_work_area, 'savi_opp_cat_work_type' )->name.'</h6>'; 
										}
									}
									// Displaying Work type
									$terms_wt = explode(", ", $terms_work_type);
									if( count($terms_wt) > 1 && is_array($terms_wt) ){
										// display array of terms starts
										if ( $terms_wt && ! is_wp_error( $terms_wt ) ) : 
											$term_wt_names = array();
										foreach ( $terms_wt as $term_wt){
											$term_title = get_term( $term_wt, 'savi_opp_cat_work_type' );
											$term_wt_names[] = $term_title->name;
										}
										$term_wt = join( ", ", $term_wt_names );
										echo '<h6><span class="bold">Work Type:</span> '. $term_wt.'</h6>'; 
										endif;
										// display array of terms ends
									}else{
										// display single terms starts 
										echo '<h6><span class="bold">Work Area:</span> '. get_term( $terms_work_type, 'savi_opp_cat_work_type' )->name.'</h6>'; 
									}
									echo '<h6><span class="bold">Skills:</span> '.$skills.'</h6>';
									echo '<h6><span class="bold">Motivation:</span> '.$motivations.'</h6>';
									echo '<h6><span class="bold">Education:</span> '.$education.'</h6>';
									echo '<h6><span class="bold">Languages:</span> '.$languages.'</h6>';
									echo '<h6><span class="bold">Internship:</span> '.$internship.'</h6>';
								?>
								</div>
						<?php 	}else echo '<h2>'.$message.'</h2>';
						 
						}else{
									echo '<h2>This page only for logged in mentor</h2>';
						}
						?>
							</article> <!-- .et_pb_post -->
							
			</div> <!-- et_pb_column_3_4 -->
		</div> <!-- et_pb_row -->
	</div> <!-- et_pb_section -->
</div> <!-- #main-content -->
 
<?php get_footer(); ?> ?>
		</div> <!-- #content-area -->
	</div> <!-- .container -->
</div> <!-- #main-content -->
 
<?php get_footer(); ?>
