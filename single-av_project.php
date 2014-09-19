<?php
get_header(); 

 ?>
<div id="main-content">
	<div class="container">
		<div id="content-area" class="clearfix">
			<div id="left-area">
				<?php while ( have_posts() ) : the_post(); ?>
					<?php if (et_get_option('divi_integration_single_bottom') <> '' && et_get_option('divi_integrate_singlebottom_enable') == 'on') echo(et_get_option('divi_integration_single_bottom')); 
						$parent_units = get_post_meta( get_the_ID(), "parent_unit", true );
						$parent_unitsMeta = $parent_units[0];
						//echo "<pre>",print_r($parent_unitsMeta),"</pre>";
					?>
						<article id="post-<?php the_ID(); ?>" <?php post_class( 'et_pb_post' ); ?>>
							<h1 class="main_title"><?php the_title(); ?></h1>		
								<div id="lefts">
									<div class="table">
										<ul>
											<li>
												<div class="left_li">Project Head Name : </div>
												<div class="right_li"><?php echo get_post_meta( get_the_ID(), "proj_head_name", true ); ?></div>
											</li>
											<li>
												<div class="left_li">Team Members : </div>
												<div class="right_li"><?php echo get_post_meta( get_the_ID(), "team_members", true ); ?></div>
											</li>
											<li>
												<div class="left_li">Parents Unit : </div>
												<div class="right_li">
												<?php 
													foreach($parent_unitsMeta as $key => $parent_unit){ ?>
														<a href="<?php echo get_post_permalink($parent_unit); ?>"><?php echo get_the_title($parent_unit); ?></a>
												<?php	}
												?></div>
											</li>
										</ul>
									</div>
									<div class="entry-content">
									<?php
										the_content();
										wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'Divi' ), 'after' => '</div>' ) );
									?>
									</div> <!-- .entry-content -->
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
											comments_template( '', true );
									?>
						</article> <!-- .et_pb_post -->
					<?php if (et_get_option('divi_integration_single_bottom') <> '' && et_get_option('divi_integrate_singlebottom_enable') == 'on') echo(et_get_option('divi_integration_single_bottom')); 
				endwhile; ?>
			</div> <!-- #left-area -->
			<?php get_sidebar(); ?>
		</div> <!-- #content-area -->
	</div> <!-- .container -->
</div> <!-- #main-content -->
<?php get_footer(); ?>