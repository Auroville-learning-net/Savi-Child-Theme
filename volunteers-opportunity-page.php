<?php
/*
Template Name: Volunteers Opportunity Page
*/
$template_dir = get_stylesheet_directory_uri ();
wp_enqueue_style ( 'savi_opportunities', $template_dir . '/css/savi_opportunities.css', array (), null );
 get_header();
 
 ?>

<div id="main-content">
	<div class="et_pb_section et_section_specialty">

		<div class="et_pb_row">
			<div class="et_pb_column et_pb_column_1_4">
					<?php get_sidebar(); ?>
			</div>
			<div class="et_pb_column et_pb_column_3_4">
				<h2><?php echo get_the_title(); ?></h2>
				<?php
				$user_id = get_current_user_id(); // get the current user ID
				$profile_post_id = get_user_meta( $user_id, "profile_post_id", true ); // get the profile  post_id 
				$post_type = get_post_type( $profile_post_id ); // get the profile post type
				//echo $post_type;
				switch($post_type){
					case 'view_1':  
					case 'view_2': 
					//if post type is view 2 
						$args = array(
								'author'    => $user_id, // I could also use $user_ID, right?
								'post_type' => $post_type
								);
						$the_query = new WP_Query( $args );
						if ( $the_query->have_posts() ) :
							while ( $the_query->have_posts() ) : $the_query->the_post();
								//echo "<pre>",print_r($the_query),"</pre>";
								$my_opp_ID = get_the_ID(); //get the my opp ID
								
								$exp_opp = get_post_meta( $profile_post_id, 'express_opportunities', true );
								if($exp_opp){
									foreach($exp_opp as $exp_opp1){
										$terms_daily_tasks = get_post_meta ( $exp_opp1['express_opportunity'], "daily_tasks", true );
										$opportunity_Title = get_the_title ($exp_opp1['express_opportunity']);
										$opportunity_permalink = get_permalink ($exp_opp1['express_opportunity']);
										/* we're going to search for the unit now. */
										$unit_id = get_post_meta ( $exp_opp1['express_opportunity'], "av_unit", true );

										/* we're looking for all units connected to this opportunity */
										$query = new WP_Query ( array (
												'post_type' => 'av_unit',
												'post__in' => array (
														$unit_id 
												) 
										) );
										if ($query->have_posts ()) {
											while ( $query->have_posts () ) {
												$query->the_post ();
												$unit_permalink = get_permalink ();
												$unit_name = get_the_title ();
											}
											wp_reset_postdata();
										} 
										?>

										<div class="opportunity_summary">
											<div class="sum_op_thumbnail">
												<?php
													if ('on' == et_get_option ( 'divi_thumbnails_index', 'on' ) && '' !== $thumb) :
												?>
													<a href="<?php  echo $opportunity_permalink ?>">
														<?php echo get_the_post_thumbnail($exp_opp1['express_opportunity']); ?>
													</a>
												<?php
													endif;
												?>
											</div>
											<div id="post-<?php the_ID(); ?>" class="sum_op_content">
												<div class="sum_op_headline">
													<a href="<?php echo $opportunity_permalink; ?>" class="op_title"><?php echo $opportunity_Title; ?></a>
												</div>
												<div class="sum_opp_unit">
													at <span class="opp_unit"><a href="<?php echo $unit_permalink; ?>"><?php echo $unit_name; ?></a></span>
												</div>
												<div class="sum_op_dailytasks">
													<span class="sum_op_label">Daily tasks: </span><?php echo $terms_daily_tasks; ?>
												</div>
											</div>
										</div>
									<?php 
										//echo '<h4 id="opp_'.$exp_opp1['express_opportunity'].'"><a href="'. get_permalink($exp_opp1['express_opportunity']) .'" target="_blank">'. get_the_title($exp_opp1['express_opportunity']).'</a></h4>'; // Display the title of the Opportunity
									}
								}else{
									echo "<h2>No selected opportunties found</h2>";
									echo "<p>To select an opportunity, simply browse to the opportunity of interest and scroll to the bottom of the page.  Toggle the auroville logo icon to select.</p>";
								}
							endwhile;
							wp_reset_postdata();
						endif;
					break;
					case 'view_3': //if post type is view 3 
						echo do_shortcode('[formidable id=34 title=true description=true]'); 
					break;
					case 'view_4': 
					case 'view_5': 
					case 'view_6':
					case 'view_7':
						//show the confirmed opportunity
						$args = array(
								'author'    => $user_id, // I could also use $user_ID, right?
								'post_type' => $post_type
								);
						$the_query = new WP_Query( $args );
						if ( $the_query->have_posts() ) :
							while ( $the_query->have_posts() ) : $the_query->the_post();
								$my_opp_ID = get_the_ID(); //get the my opp ID
								$vol_opp = get_post_meta( $profile_post_id, 'volunteer_opportunity', true );

								$terms_daily_tasks = get_post_meta ( $vol_opp, "daily_tasks", true );
								$opportunity_Title = get_the_title ($vol_opp);
								$opportunity_permalink = get_permalink ($vol_opp);
								
								/* we're going to search for the unit now. */
								$unit_id = get_post_meta ( $vol_opp, "av_unit", true );

								/* we're looking for all units connected to this opportunity */
								$query = new WP_Query ( array (
										'post_type' => 'av_unit',
										'post__in' => array (
												$unit_id 
										) 
								) );
								if ($query->have_posts ()) {
									while ( $query->have_posts () ) {
										$query->the_post ();
										$unit_permalink = get_permalink ();
										$unit_name = get_the_title ();
									}
								} 
								?>

								<div class="opportunity_summary">
									<div class="sum_op_thumbnail">
										<?php
										if ('on' == et_get_option ( 'divi_thumbnails_index', 'on' ) && '' !== $thumb) :
											?>
											<a href="<?php echo $opportunity_permalink; ?>">
												<?php echo get_the_post_thumbnail($vol_opp); ?>
											</a>
										<?php
										endif;
										?>
									</div>
									<div id="post-<?php the_ID(); ?>" class="sum_op_content">
										<div class="sum_op_headline">
											<a href="<?php echo $opportunity_permalink; ?>" class="op_title"><?php echo $opportunity_Title; ?></a>
										</div>
										<div class="sum_opp_unit">
											at <span class="opp_unit"><a href="<?php echo $unit_permalink; ?>"><?php echo $unit_name; ?></a></span>
										</div>
										<div class="sum_op_dailytasks">
											<span class="sum_op_label">Daily tasks: </span><?php echo $terms_daily_tasks; ?>
										</div>
									</div>
								</div>
							<?php 
							endwhile;
							wp_reset_postdata();
						else :
							echo "<h2>Still Savi Team is selecting the your best opportunties.</h2>";
						endif;
						break;
					default:
						echo "<h2>Are you a volunteer?</h2>";
					break;
				} 
				?>
			</div> 
		</div> <!-- #content-area -->
	</div> <!-- .container -->
</div> <!-- #main-content -->


<?php get_footer(); ?>

</div> <!-- #main-content -->


<?php get_footer(); ?>
