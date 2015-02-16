<?php get_header(); ?>
<div id="main-content">
	<div class="et_pb_section et_section_specialty">

		<div class="et_pb_row">
			<div class="et_pb_column et_pb_column_1_4">
					<?php get_sidebar(); ?>
				</div>
			<div class="et_pb_column et_pb_column_3_4">
			<?php
			
			while ( have_posts () ) :
				the_post ();
				$term_work_type = get_the_terms ( get_the_ID (), 'savi_opp_cat_work_type' );
				$terms_work_area = get_the_terms ( get_the_ID (), 'savi_opp_cat_work_area' );
				$terms_skills_gain = get_post_meta ( get_the_ID (), "skills_gain", true );
				$terms_daily_tasks = get_post_meta ( get_the_ID (), "daily_tasks", true );
				$terms_startdate = get_post_meta ( get_the_ID (), "startdate", true );
				$terms_startdate_formatted = date ( "M d, Y", strtotime ( $terms_startdate ) );
				$terms_enddate = get_post_meta ( get_the_ID (), "enddate", true );
				$terms_enddate_formatted = date ( "M d, Y", strtotime ( $terms_enddate ) );
				$terms_prerequisites = get_post_meta ( get_the_ID (), "prerequisites", true );		
				$terms_languages = get_the_terms ( get_the_ID (), 'savi_opp_tag_languages' );
				$terms_soft = get_the_terms ( get_the_ID (), 'savi_opp_tag_soft' );
				$terms_duration = get_post_meta ( get_the_ID (), "duration", true );
				$terms_timing = get_post_meta ( get_the_ID (), "timing", true );
				$terms_morning = get_post_meta ( get_the_ID (), "morningtimings", true );
				$terms_afternoon = get_post_meta ( get_the_ID (), "afternoontimings", true );		
				$terms_architect_semester = get_post_meta ( get_the_ID (), "architect_semester", true );
				$terms_computer_required = get_post_meta ( get_the_ID (), "computerrequired", true );
				$terms_number_of_volunteers = get_post_meta ( get_the_ID (), "number_of_volunteers", true );			
				$contactName = get_post_meta ( get_the_ID (), "contactPerson", true );
				$contactNumber = get_post_meta ( get_the_ID (), "contactPhone", true );
				$contactEmail = get_post_meta ( get_the_ID (), "contactEmail", true );
				$content = get_the_content ();
				
				$thumb = '';
				$template_dir = get_stylesheet_directory_uri ();
				$loading_image = $template_dir . "/images/ajax-loader.gif";
				$width = ( int ) apply_filters ( 'et_pb_index_blog_image_width', 400 );
				$height = ( int ) apply_filters ( 'et_pb_index_blog_image_height', 270 );
				$classtext = 'et_featured_image';
				$titletext = get_the_title ();
				$thumbnail = get_thumbnail ( $width, $height, $classtext, $titletext, $titletext, false, 'Blogimage' );
				$thumb = $thumbnail ["thumb"];
				
				$oppID = get_the_ID ();
				
				/*By default hide info and select button*/
				$showcontact = FALSE;
				$express_opp_button = FALSE;
				$is_opportunity_selected =false; //in case the button is show, by default this opp is not selected
				//is the visitor logged in?
				if ( is_user_logged_in() ) { //if user is loggrd in
					$user_ID = get_current_user_id ();
					//let's check if this is a volunteers
					$userSaviRole = get_user_meta($user_ID,'savi_role',true);
					$isVolunteer = false;
					if($userSaviRole=="volunteers") $isVolunteer = true;
					//if this is a volunteers, let's find out which view_x they current are in
					if($isVolunteer){
						/* get the users's profile post*/
						$profile_post_id = get_user_meta($user_ID, 'profile_post_id', true); //user profile post id
						// get the post type for this profile post
						$profilePostType = get_post_type( $profile_post_id );
						switch ($profilePostType) {
							case 'view_0' :
								$showcontact = false;
								$express_opp_button = true;
								break;
							case 'view_1' :
								$showcontact = false;
								$express_opp_button = true;
								break;
							case 'view_2' :
								$showcontact = false;
								$express_opp_button = true;
								break;
							case 'view_3' :
								$showcontact = true;
								$express_opp_button = true;
								break;
							case 'view_4' :
								$showcontact = false;
								$express_opp_button = false;
								break;
							case 'view_5' :
								$showcontact = false;
								$express_opp_button = false;
								break;
							case 'view_6' :
								$showcontact = false;
								$express_opp_button = false;
								break;
							case 'view_7' :
								$showcontact = false;
								$express_opp_button = false;
								break;
						}
						if($express_opp_button){ //we need to see if this opportunity has already been selected
							$allexpressOpportunities = get_post_meta( $profile_post_id, 'express_opportunities', true ); // All Express Opportunities
							$allexpressOpportunities_id = array();
							if (sizeof($allexpressOpportunities) > 0 && is_array($allexpressOpportunities)) {
								foreach($allexpressOpportunities as $key=>$expressOpportunity) {
									$expressOpportunitiesID = $expressOpportunity['express_opportunity'];
									$allexpressOpportunities_id[] = $expressOpportunitiesID;                             
								}
							}
							//chekc if the current oppotunity being displayed, id=$oppID, is in expresses Oppornities of volunteer profile
							if(in_array($oppID,$allexpressOpportunities_id)):
								$is_opportunity_selected =true; // Check here Opportunity Exists or Not
							endif;
						}
					}else if ( current_user_can( 'manage_options' ) ) {
						/** SHOW THE CONTACT INFORMATION ALWAYS IF USER IS AN ADMIN **/
						$showcontact = true;
						$express_opp_button = false;
					}		
				}// else user is not logged in
				
				if (et_get_option ( 'divi_integration_single_bottom' ) != '' && et_get_option ( 'divi_integrate_singlebottom_enable' ) == 'on')
					echo (et_get_option ( 'divi_integration_single_bottom' ));
				$no_of_opportunities = get_post_meta ( get_the_ID (), "no_of_opportunities", true );
				if ($no_of_opportunities > 0) {
					?>
				<style>
				.et_pb_column_2_4 {
					width: 450px;
				}
				
				.et_pb_row_inner {
					margin-top: 10px;
				}
				</style>
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'et_pb_post' ); ?>>
					<div class="et_pb_row_inner">
						<!-- Row 1 -->
						<div class="et_pb_column et_pb_column_2_4 et_pb_column_inner">
							<h1><?php the_title(); ?></h1>
						</div>
						<div class="et_pb_column et_pb_column_1_4 et_pb_column_inner">
							<!-- Duration -->
							<div class="opportunity_duration">
								<span class="fieldlabel">Duration:</span>&nbsp;<?php echo $terms_duration; ?>&nbsp;months
							</div>
						</div>
						<!-- end Row 1 -->
					</div>
					<div class="et_pb_row_inner">
						<!-- Row 2 -->
						<div class="et_pb_column et_pb_column_2_4 et_pb_column_inner">
							<div class="opportunity_units">
								<!-- Units -->
							<?php
					$id = get_post_meta ( get_the_ID (), "av_unit", true );
					$query = new WP_Query ( array (
							'post_type' => 'av_unit',
							'post__in' => array (
									$id 
							) 
					) );
					if ($query->have_posts ()) {
						while ( $query->have_posts () ) :
							$query->the_post ();
							?>
								<h3>
									<a href="<?php echo get_permalink(); ?>"><?php echo the_title(); ?></a>
								</h3>
							<?php
						endwhile
						;
					} else {
						?>
								<div>No organisation specified</div>
					<?php }	?>	
							</div>
							<?php if ($terms_daily_tasks) : ?>
							<div>
								<p>
									<strong>Daily Tasks</strong>
								</p>
								<div>
									<p>
										<?php echo $terms_daily_tasks; ?>
									</p>
									<p></p>
								</div>
							</div>
							<?php endif;?>
							<?php if ($terms_skills_gain && $showcontent===true) : ?>
							<div>
								<p>
									<strong>Skills gained</strong>
								</p>
								<div>
									<p>
										<?php echo $terms_skills_gain; ?>
									</p>
									<p></p>
								</div>
							</div>
							<?php endif; ?>
						</div>
						<div class="et_pb_column et_pb_column_1_4 et_pb_column_inner">
							<!-- Thumbnail -->
							<div class="opportunity_image">
								<?php //echo '1. divi_thumbnails_index '.et_get_option ( 'divi_thumbnails_index', 'on' ).'</br>2. thumb '. $thumb.'</br>3. width '.$width.'</br>4. height '.$height. '</br>5. classtext '. $classtext. '</br>6. titletext '. $titletext. '</br>7. thumbnail '. $thumbnail. '</br>8. thumb  '. $thumb. '</br>9. thumbHTML  '. $thumbHTML;
					if ('on' === et_get_option ( 'divi_thumbnails', 'on' ) && '' !== $thumb)
						print_thumbnail ( $thumb, $thumbnail ["use_timthumb"], $titletext, $width, $height );
					?>
							</div>
						</div>
						<!-- End Row 2 -->
					</div>
					<?php if ($showcontent===true) { ?>
					<div class="et_pb_row_inner">
						<!-- Row 3 -->
						<div class="et_pb_column et_pb_column_3_8 et_pb_column_inner">
							<p>
								<strong>Area</strong>
							</p>
													<?php
					if ($terms_work_area && ! is_wp_error ( $terms_work_area )) {
						$i = 0;
						foreach ( $terms_work_area as $terms_work_area ) {
							if ($i > 0)
								echo ',';
							?>
								<a href="<?php echo get_term_link( $terms_work_area->slug, 'savi_opp_cat_work_area' )?>"><?php echo $terms_work_area->name;?></a>			
							<?php
							
							$i ++;
						}
					} else
						esc_html_e ( 'No Areas Specified', 'Divi' );
					?>
						</div>
						<div class="et_pb_column et_pb_column_3_8 et_pb_column_inner">
							<p>
								<strong>Type</strong>
							</p>
											<?php
					
					if ($term_work_type && ! is_wp_error ( $term_work_type )) {
						$i = 0;
						foreach ( $term_work_type as $term_work_type ) {
							if ($i > 0)
								echo ',';
							?>
								<a href="<?php echo get_term_link( $term_work_type->slug, 'savi_opp_cat_work_type' )?>"><?php echo $term_work_type->name;?></a>
							<?php
							
							$i ++;
						}
					} else
						esc_html_e ( 'No Areas Specified', 'Divi' );
					?>
						</div>
						<!-- End Row 3 -->
					</div>
					<div class="et_pb_row_inner">
						<!-- Row 4 -->
						<div class="et_pb_column et_pb_column_4_4 et_pb_column_inner">
							<p>
								<strong>Opportunity Description</strong>
							</p>
						<?php echo $content; ?>
						</div>
						<!-- End Row 4 -->
					</div>
					<div class="et_pb_row_inner">
						<!-- Row 5 Timing details -->
						<div class="et_pb_column et_pb_column_3_8 et_pb_column_inner">
							<p>
								<strong>Timing</strong>
							</p>
							<div>
								<p>
									<strong>Start date </strong><?php if ($terms_startdate) echo $terms_startdate_formatted; else echo "No specific startdate"; ?>
									<br /> <strong>End date </strong><?php if ($terms_enddate) echo $terms_enddate_formatted; else echo "No specific enddate mentioned"; ?>
								</p>
							</div>
						</div>
						<div class="et_pb_column et_pb_column_3_8 et_pb_column_inner">
							<p>
								<strong>&nbsp;</strong>
							</p>
							<div>
								<p>
									<strong>Morning hours </strong><?php echo $terms_morning; ?>
									<br /> <strong>Afternoon hours </strong><?php echo $terms_afternoon; ?>
								</p>
							</div>
						</div>
						<!-- End Row 5 Timing details -->
					</div>
					<div class="et_pb_row_inner">
						<!-- Row 6 prerequisites -->
						<div class="et_pb_column et_pb_column_4_4 et_pb_column_inner">
							<p>
								<strong>Prerequisites</strong>
							</p>
							<div>
								<p>								
							<?php echo $terms_prerequisites; ?>
								</p>
							</div>
						</div>
						<!-- Row 6 -->
					</div>
					<div class="et_pb_row_inner">
						<!-- Row 7 Language skills -->
						<div class="et_pb_column et_pb_column_4_4 et_pb_column_inner">
							<p>
								<strong>Language skills</strong>
							</p>
							<div>
								<p>								
								<?php
					if ($terms_languages && ! is_wp_error ( $terms_languages )) {
						$i = 0;
						foreach ( $terms_languages as $terms_languages ) {
							if ($i > 0)
								echo ',';
							?>
											<a href="<?php echo get_term_link( $terms_languages->slug, 'savi_opp_tag_languages' )?>"><?php echo $terms_languages->name;?></a>												
											<?php
							$i ++;
						}
					} else
						esc_html_e ( 'No Languages Specified', 'Divi' );
					?>								
								</p>
							</div>
						</div>
						<!-- Row 7 -->
					</div>
					<div class="et_pb_row_inner">
						<!-- Row 8 Software skills -->
						<div class="et_pb_column et_pb_column_4_4 et_pb_column_inner">
							<p>
								<strong>Software skills</strong>
							</p>
							<div>
								<p>								
								<?php
					if ($terms_soft && ! is_wp_error ( $terms_soft )) {
						$i = 0;
						foreach ( $terms_soft as $terms_soft ) {
							if ($i > 0)
								echo ',';
							?>
								<a href="<?php echo get_term_link( $terms_soft->slug, 'savi_opp_tag_soft' )?>"><?php echo $terms_soft->name;?></a>											
							<?php
							
							$i ++;
						}
					} else
						esc_html_e ( 'No Software Specified', 'Divi' );
					
					?>								
								</p>
							</div>
						</div>
						<!-- Row 8 -->
					</div>
					<div class="et_pb_row_inner">
						<!-- Row 9 -->
						<?php if ($terms_architect_semester && ! is_wp_error($terms_architect_semester)) { ?>
						<div class="et_pb_column et_pb_column_3_8 et_pb_column_inner">
							<p>
								<strong>Minimal semester for architectural students</strong>
							</p>
							<div>
								<p>
									<?php echo $terms_architect_semester; ?>
								</p>
							</div>
						</div>
						<?php } ?>
						<?php if ($terms_computer_required && ! is_wp_error($terms_computer_required)) { ?>
						<div class="et_pb_column et_pb_column_3_8 et_pb_column_inner">
							<p>
								<strong>Computer required</strong>
							</p>
							<div>
								<p>
									<?php echo $terms_computer_required; ?>
								</p>
							</div>
						</div>
						<?php } ?>
						<?php if ($terms_number_of_volunteers && ! is_wp_error($terms_number_of_volunteers)) { ?>
						<div class="et_pb_column et_pb_column_3_8 et_pb_column_inner">
							<p>
								<strong>Number of volunteers needed</strong>
							</p>
							<div>
								<p>
									<?php echo $terms_number_of_volunteers; ?>
								</p>
							</div>
						</div>
						<?php } ?>
						
						<!-- end Row 9 -->
					</div>
					<?php } ?>
					<?php if ( $showcontact ) {	 ?>
					<div class="et_pb_row_inner">
						<!-- Row 10 -->
						<div class="et_pb_column et_pb_column_4_4 et_pb_column_inner">
							<div class="contact_info">
							<?php
								echo '<h2>Contact</h2>';
								echo '<strong>Mentor</strong>: ' . $contactName . '<br />';
								echo '<strong>Email</strong>: ' . $contactEmail . '<br />';
								echo '<strong>Phonenumber</strong>: ' . $contactNumber . '<br />';
							?>
							</div>
						</div>
						<!-- end Row 10 -->
					</div>
					<?php } ?>
					
					<?php if ($express_opp_button) { ?> 
					<div class="et_pb_row_inner">
						<div id="select-icon-container">
							<div id="loading_image" style="display: none">
								<img src="<?php echo $loading_image ?>" alt="">
							</div>
							<input type="checkbox" name="<?php echo wp_get_current_user()->ID ?>" value="<?php echo $oppID; ?>" id="select-icon" <?php if($is_opportunity_selected): ?> checked='checked' <?php endif;?> /> 
								<label for="select-icon"></label>
								<label class="opp-click-info">Click to select this opportunity</label>
								<label class="oppselected">Click to unselect this opportunity.</label>
								<p class="opp-click-info">By toggling the logo on, you can register interest in this opportunity.
									You will be able to find a list of your bookmarked opportunities in your <a href="/opportunity-selection-preference/">Opportunity page</a>.
									Your shortlisted opportunities will be taken into considereation when assigning you an opportunity. </p>
								<p  class="oppselected">By toggling the logo off, you can remove this opportunity form your list
									of bookmarked opportunities.</p>
								<input type="hidden" id="hidden_blog_url" value="<?php bloginfo('url'); ?>" />
						</div>
					</div>
					<?php } ?>						
				</article>
				<!-- .et_pb_post -->
				<?php
				} else {
					echo "Sorry, This Opportunity is already taken";
				}
				if (et_get_option ( 'divi_integration_single_bottom' ) != '' && et_get_option ( 'divi_integrate_singlebottom_enable' ) == 'on')
					echo (et_get_option ( 'divi_integration_single_bottom' ));
			endwhile
			;
			?>
			</div>
		</div>
		<!-- #content-area -->
	</div>
	<!-- .container -->
</div>
<script>
jQuery( document ).ready(function() {
	jQuery('#select-icon').click(function() {
		if(this.checked){
			jQuery(".opp-click-info").css("display", "none");
			jQuery("label.oppselected").css("display", "inline");
			jQuery("p.oppselected").css("display", "block");
		}else{
			jQuery(".oppselected").css("display", "none");
			jQuery("label.opp-click-info").css("display", "inline");
			jQuery("p.opp-click-info").css("display", "block");
		}
	});
	if(jQuery("#select-icon").is(':checked')){
		jQuery(".opp-click-info").css("display", "none");
		jQuery("label.oppselected").css("display", "inline");
		jQuery("p.oppselected").css("display", "block");
	}else{
		jQuery(".oppselected").css("display", "none");
		jQuery("label.opp-click-info").css("display", "inline");
		jQuery("p.opp-click-info").css("display", "block");
	}
});
</script>
<!-- #main-content -->
<?php get_footer(); ?>