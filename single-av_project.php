<?php get_header(); ?>
<div id="main-content">
	<div class="et_pb_section et_section_specialty">
		<div class="et_pb_row">
			<div class="et_pb_column et_pb_column_1_4">
					<?php get_sidebar(); ?>
				</div>
			<div class="et_pb_column et_pb_column_3_4">
				<?php while ( have_posts() ) : the_post(); 
					if (et_get_option('divi_integration_single_bottom') <> '' && et_get_option('divi_integrate_singlebottom_enable') == 'on') echo(et_get_option('divi_integration_single_bottom')); 
						
						$proj_head_name = get_post_meta( get_the_ID(), "proj_head_name", true );
						$proj_head_email = get_post_meta( get_the_ID(), "proj_head_email", true );
						$proj_head_number = get_post_meta( get_the_ID(), "proj_head_number", true );
						$team_members = get_post_meta( get_the_ID(), "team_members", true );
						$project_title = get_the_title();
						$proj_abbr = get_post_meta( get_the_ID(), "proj_abbr", true );
						$start_date = get_post_meta( get_the_ID(), "start_date", true );
						$end_date = get_post_meta( get_the_ID(), "end_date", true );
						$parent_units = get_post_meta( get_the_ID(), "parent_unit", true );
						
						//echo "<pre>",print_r($parent_unitsMeta),"</pre>";
					?>
						<article id="post-<?php the_ID(); ?>" <?php post_class( 'et_pb_post' ); ?>>
							<h1 class="main_title"><?php if($proj_abbr !='') echo $project_title.' ('.$proj_abbr.')'; else echo $project_title; ?></h1>		
								<div id="lefts">
									<div class="table">
										<ul>
											<li>
												<div class="left_li">Project Head Name: </div>
												<div class="right_li"><?php if($proj_head_name !='') echo $proj_head_name; else echo '<div class="gray">NAN</div>'; ?></div>
											</li>
											<li>
												<div class="left_li">Project Head Email: </div>
												<div class="right_li"><?php if($proj_head_email !='') echo $proj_head_email;  else echo '<div class="gray">NAN</div>'; ?></div>
											</li>
											<li>
												<div class="left_li">Project Head Phone No: </div>
												<div class="right_li"><?php  if($proj_head_number !='') echo $proj_head_number;  else echo '<div class="gray">NAN</div>'; ?></div>
											</li>
											<li>
												<div class="left_li">Team Members : </div>
												<div class="right_li"><?php if($team_members !='') echo $team_members; else echo '<div class="gray">NAN</div>'; ?></div>
											</li>
											<li>
												<div class="left_li">Project Description : </div>
													<div class="entry-content">
													<?php
														the_content();
														wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'Divi' ), 'after' => '</div>' ) );
													?>
													</div> <!-- .entry-content -->
											</li>
											<?php foreach($parent_units as $key => $parent_unit){  ?>
											
											<li>
												<div class="left_li">Parents Unit : </div>
												<div class="right_li">
												<?php 
													$unit_link = get_post_permalink($parent_unit[parent_unit]); 
													$unit_name = get_the_title($parent_unit[parent_unit]); 
													echo '<a href="'. $unit_link.'">'.$unit_name.'</a>';
												?>
												</div>
											</li>
											<?php 
											$args = array(
													'post_type'  => 'av_opportunity',
													'meta_query' => array(
														'relation' => 'AND',
														array(
															'key'     => 'av_unit',
															'value'   => $parent_unit[parent_unit],
															'type'    => 'numeric',
															'compare' => '=',
														),
														array(
															'key' => 'projectname',
															'value'   => get_the_ID(),
															'type'    => 'numeric',
															'compare' => '=',
														),
													),
												);
											$query = new WP_Query( $args ); ?>
											<li>
												<div class="left_li">Opportunity : </div>
												<div class="right_li">
												<?php 
													if ( $query->have_posts() ) :
														while ( $query->have_posts() ) : $query->the_post();
															$title.= '<a href="'.get_permalink(get_the_ID()).'">'.get_the_title().'</a>'.', ';
														endwhile;
														wp_reset_postdata();
														echo trim($title, ', ');
													else :
														echo 'Sorry, no opportunity found';
													endif;
													
												?>
												</div>
											</li>
											<?php } ?>
										</ul>
									</div>																
								</div>
								<div id="rights">
									<div class="image_in">
									<?php
										$thumb = '';
										$width = (int) apply_filters( 'et_pb_index_blog_image_width', 400 );
										$height = (int) apply_filters( 'et_pb_index_blog_image_height', 270 );
										$classtext = 'et_featured_image';
										$titletext = get_the_title();
										$thumbnail = get_thumbnail( $width, $height, $classtext, $titletext, $titletext, false, 'Blogimage' );
										$thumb = $thumbnail["thumb"];
										if ( 'on' === et_get_option( 'divi_thumbnails', 'on' ) && '' !== $thumb )
											print_thumbnail( $thumb, $thumbnail["use_timthumb"], $titletext, $width, $height );
									?>
									</div>
								</div>
											<?php
											if ( et_get_option('divi_468_enable') == 'on' ){
												echo '<div class="et-single-post-ad">';
												if ( et_get_option('divi_468_adsense') <> '' ) echo( et_get_option('divi_468_adsense') );
												else { ?>
													<a href="<?php echo esc_url(et_get_option('divi_468_url')); ?>"><img src="<?php echo esc_attr(et_get_option('divi_468_image')); ?>" alt="468 ad" class="foursixeight" /></a>
										<?php 	}
												echo '</div> <!-- .et-single-post-ad -->';
											}
										if ( comments_open() && 'on' == et_get_option( 'divi_show_postcomments', 'on' ) )
										//	comments_template( '', true );
									?>
						</article> <!-- .et_pb_post -->
					<?php if (et_get_option('divi_integration_single_bottom') <> '' && et_get_option('divi_integrate_singlebottom_enable') == 'on') echo(et_get_option('divi_integration_single_bottom')); 
				endwhile; ?>
			</div> <!-- #left-area -->
		</div> <!-- #content-area -->
	</div> <!-- .container -->
</div> <!-- #main-content -->
<?php get_footer(); ?>