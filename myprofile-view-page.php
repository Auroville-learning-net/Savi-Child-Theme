<?php
/*
Template Name: My Profile View Page
*/
?>
<?php get_header(); ?>

<div id="main-content">
	<div class="container">
		<div id="content-area" class="clearfix">
			<div id="left-area">
				<?php
					if ( have_posts() ) :
						while ( have_posts() ) : the_post();
							$post_format = get_post_format(); 
								if ( is_user_logged_in() ) {
							?>
							<article id="post-<?php the_ID(); ?>" <?php post_class( 'et_pb_post' ); ?>>
								<?php
									$user_ID = get_current_user_id();
									//echo $user_ID;
									$profile_post_id = get_user_meta($user_ID, 'profile_post_id', true);
									//echo $profile_post_id;
									$terms_work_area = FrmProEntriesController::get_field_value_shortcode(array('field_id' => 467, 'user_id' => $user_ID));
									$terms_work_type = FrmProEntriesController::get_field_value_shortcode(array('field_id' => 468, 'user_id' => $user_ID));
									$education = FrmProEntriesController::get_field_value_shortcode(array('field_id' => 414, 'user_id' => $user_ID));
									$languages = FrmProEntriesController::get_field_value_shortcode(array('field_id' => 409, 'user_id' => $user_ID));									
									$first_name = get_post_meta( $profile_post_id, "savi_views_contact-details_first-name", true );
									$last_name = get_post_meta( $profile_post_id, "savi_views_contact-details_last-name", true );
									$stay_duration = get_post_meta( $profile_post_id, "savi_views_stay-details_duration", true );
									$nationality = get_post_meta($profile_post_id, "savi_views_contact-details_nationality", true );
									$skills = get_post_meta($profile_post_id, "savi_views_skills_fields-of-interest", true );
									$motivations = get_post_meta($profile_post_id, "savi_views_motivations_goals-skills-to-be-gained", true );
									$internship = get_post_meta( $profile_post_id, "savi_views_education-details_intership", true );
									$showinfo = FALSE;	
									$profilepost = get_post_type( $profile_post_id );
									
									
									
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
							
							?>
							<?php  if ( $showinfo ) {	 ?>
								<div class="profile-view">
								<?php 
									
									echo '<h2>Contact</h2>';
									echo '<h6><strong>Full Name: </strong>'.$first_name .' '.$last_name.'</h6>';
									echo '<strong>Nationality</strong>: '.$nationality.'<br />';
									echo '<strong>Stay duration</strong>: '.$stay_duration.'<br />';
							//		echo '<strong>Work Area</strong>:'.$terms_work_area.'<br />';
									//echo $terms_work_area;
							
									if( $terms_work_area == 'savi0'){
										echo '<strong>Work Area</strong>: Any Work Areas<br />'; 
									}else{
									   
										
											$terms_wa = explode(", ", $terms_work_area);
											if( count($terms_wa) > 1 && is_array($terms_wa) ){
												 // display array of terms starts 
												if ( $terms_wa && ! is_wp_error( $terms_wa ) ) : 
													$term_wa_names = array();
													foreach ( $terms_wa as $term_wa){
														$term_title = get_term( $term_wa, 'savi_opp_cat_work_type' );
														$term_wa_names[] = $term_title->name;
													}
													$term_wa = join( ", ", $term_wa_names );
													echo '<strong>Work Area</strong>: '. $term_wa.'<br />'; 
												endif;
												 // display array of terms ends
										}
										else{
											 // display single terms starts 
											echo '<strong>Work Area</strong>: '. $terms_work_area.'<br />'; 
										}
									}

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
										echo '<strong>Work Type</strong>: '. $term_wt.'<br />'; 
										endif;
										// display array of terms ends
									}else{
										// display single terms starts 
										echo '<strong>Work Area</strong>: '. $terms_work_type.'<br />'; 
									}
									
									echo '<strong>Skills</strong>: '.$skills.'<br />';
									echo '<strong>Motivation</strong>: '.$motivations.'<br />';
									echo '<strong>Education</strong>: '.$education.'<br />';
									echo '<strong>Languages</strong>: '.$languages.'<br />';
									echo '<strong>Internship</strong>: '.$internship.'<br />';
							
								?>
								</div>
						<?php 	}
						} 
						?>
							</article> <!-- .et_pb_post -->
							<?php
						endwhile;
						if ( function_exists( 'wp_pagenavi' ) )
							wp_pagenavi();
						else
							get_template_part( 'includes/navigation', 'index' );
					else :
						get_template_part( 'includes/no-results', 'index' );
					endif;
				?>
			</div> <!-- #left-area -->
			<?php get_sidebar(); ?>
		</div> <!-- #content-area -->
	</div> <!-- .container -->
</div> <!-- #main-content -->

<?php get_footer(); ?>