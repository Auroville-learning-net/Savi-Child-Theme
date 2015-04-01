<?php
$curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));
$user_ID = $curauth->ID; // get the user id
 get_header(); ?>
 <div id="main-content">
	<div class="container">
		<div id="content-area" class="clearfix">
			<div id="left-area">
				<?php
					if ( is_user_logged_in() ) {
							?>
							<article id="post-<?php the_ID(); ?>" <?php post_class( 'et_pb_post' ); ?>>
								<?php
									$profile_post_id = get_user_meta($user_ID, 'profile_post_id', true); // get the profile post id
								//	echo $profile_post_id;
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
									$showinfo = FALSE;	
									$profilepost = get_post_type( $profile_post_id ); // get the post type
									switch($profilepost){
										case 'view_0':
											$showinfo=true;
										break;
										case 'view_1':
											$showinfo=true;
										break;
										case 'view_2':
											$showinfo=true;
										break;
										case 'view_3':
											$showinfo=true;
										break;
										case 'view_4':
											$showinfo=true;		
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
									echo '<h2>Contact</h2>';
									echo '<h6><span class="bold">Full Name: '.$first_name .' '.$last_name.'</h6>';
									echo '<h6><span class="bold">Nationality:</span> '.$nationality.'</h6>';
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
											echo '<h6><span class="bold">Work Area:</span> '. $terms_work_area.'</h6>'; 
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
										echo '<h6><span class="bold">Work Area:</span> '. $terms_work_type.'</h6>'; 
									}
									echo '<h6><span class="bold">Skills:</span> '.$skills.'</h6>';
									echo '<h6><span class="bold">Motivation:</span> '.$motivations.'</h6>';
									echo '<h6><span class="bold">Education:</span> '.$education.'</h6>';
									echo '<h6><span class="bold">Languages:</span> '.$languages.'</h6>';
									echo '<h6><span class="bold">Internship:</span> '.$internship.'</h6>';
								?>
								</div>
						<?php 	}
						 
						}else{
									echo '<h2>This page only for logged in mentor</h2>';
						}
						?>
							</article> <!-- .et_pb_post -->
							
			</div> <!-- #left-area -->
			<?php get_sidebar(); ?>
		</div> <!-- #content-area -->
	</div> <!-- .container -->
</div> <!-- #main-content -->
 
<?php get_footer(); ?> ?>
		</div> <!-- #content-area -->
	</div> <!-- .container -->
</div> <!-- #main-content -->
 
<?php get_footer(); ?>