<?php get_header(); ?>


<div id="main-content">
	<div class="container">
		<div id="content-area" class="clearfix">
			<div id="left-area">
			<?php while ( have_posts() ) : the_post(); ?>
				<?php if (et_get_option('divi_integration_single_bottom') <> '' && et_get_option('divi_integrate_singlebottom_enable') == 'on') echo(et_get_option('divi_integration_single_bottom')); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class( 'et_pb_post' ); ?>>
					<h1 class="main_title"><?php the_title(); ?></h1>

					<div id="content_opp">
					<ul>
					<li >
						<div class="left_li">Units</div>
						<div class="right_li">
						<?php 
				
				 
				  
						$query = new WP_Query(array(  'post_type' => 'av_unit',  'post__in' => $av_unit));
						if ( $query->have_posts() ){
						echo '<ul>';
							while ( $query->have_posts() ) : 
								$query->the_post();
								
								
								?>
								<li>
								
										<a href="<?php echo get_permalink(); ?>"><?php echo  the_title(); ?></a> 
								</li>
								
								
								
						 
						 <?php 	//echo get_post_meta( get_the_ID(), "landphone", true ); 
						 	
							endwhile;
							echo '</ul>';
						}
						else{
						?>
						<div class="entry">
<!--If no results are found-->
	<p><?php esc_html_e('No Opportunity Found','Divi'); ?></p>
	
</div>
					<?php	}
						
					
				?>
						
						
						
						
						</div>
						</li>
						<li>
						<div class="left_li">Area</div>
						<div class="right_li">
					<?php
						if ( have_posts() ){
						
						  while ( have_posts() ) : 
								the_post();
								$terms=get_the_terms(get_the_ID() , 'savi_opp_cat_work_area' ); 
								echo '<ul>';
						?>
								<li>
								<?php if ( $terms && ! is_wp_error( $terms ) ) {	
											foreach ( $terms as $term ){  
												if ($i =0)
								?>
												<a href="<?php echo get_term_link( $term->slug, 'savi_opp_cat_work_area' )?>"><?php echo $term->name;?></a>	
									<?php
											} 
											
										}
									?>
								</li> 
						 <?php 		
						 endwhile;
							echo '</ul>';
						}
						?>
						
						</div>
						</li>
						<li>
						<div class="left_li">Type</div>
						<div class="right_li">
						<?php
							$terms=get_the_terms(get_the_ID() , 'savi_opp_cat_work_type' ); 
								echo '<ul>';
								echo '<li>';
								?>
												<a href="<?php echo get_term_link( $term->slug, 'savi_opp_cat_work_type' )?>"><?php echo $term->name;?></a>	
								 <?php 	
								echo '</li>';
								echo '</ul>';
						
						?>
						</div>
						</li>
						
						<li>
						<div class="left_li">Skills Gain</div>
						<div class="right_li"><?php echo get_post_meta( get_the_ID(), "skills_gain", true ); ?></div>
						</li>
						<li>
						<div class="left_li">Daily Tasks</div>
						<div class="right_li"><?php echo get_post_meta( get_the_ID(), "daily_tasks", true ); ?></div>
						</li>
						<li>
						<div class="left_li">Start Date</div>
						<div class="right_li"><?php echo get_post_meta( get_the_ID(), "startdate", true ); ?></div>
						</li>
						<li>
						<div class="left_li">Duration(in hour)</div>
						<div class="right_li"><?php echo get_post_meta( get_the_ID(), "duration", true ); ?></div>
						</li>
						<li>
						<div class="left_li">Morning Timings</div>
						<div class="right_li"><?php echo get_post_meta( get_the_ID(), "morningtimings", true ); ?></div>
						</li>
						<li>
						<div class="left_li">Afternoon Timings</div>
						<div class="right_li"><?php echo get_post_meta( get_the_ID(), "afternoontimings", true ); ?></div>
						</li>
						<li>
						<div class="left_li">Languages</div>
						<div class="right_li">
						
						
						<?php 
				
				   
				  
						
						if ( have_posts() ){
						echo '<ul>';
							while ( have_posts() ) : 
								the_post();
								$terms=get_the_terms(get_the_ID() , 'savi_opp_tag_languages' ); 
								
								?>
								<li>
								
									<?php if ( $terms && ! is_wp_error( $terms ) ) {
										
											
											foreach ( $terms as $term ){  
												if ($i =0)?>
												<a href="<?php echo get_term_link( $term->slug, 'savi_opp_tag_languages' )?>"><?php echo $term->name;?></a>
												
											<?php
											} 
											
										}?>
								</li>
								
								
								
						 
						 <?php 	//echo get_post_meta( get_the_ID(), "landphone", true ); 
						 	
							endwhile;
							echo '</ul>';
						}
						else{
						?>
						<div class="entry">
<!--If no results are found-->
	<p><?php esc_html_e('No Languages Found','Divi'); ?></p>
	
</div>
					<?php	}
						
					
				?>
						
						
						</div>
						</li>
						<li>
						<div class="left_li">Software</div>
						<div class="right_li">
						
						
						
						<?php 
				
				   
				  
						
						if ( have_posts() ){
						echo '<ul>';
							while ( have_posts() ) : 
								the_post();
								$terms=get_the_terms(get_the_ID() , 'savi_opp_tag_soft' ); 
								
								?>
								<li>
								
									<?php if ( $terms && ! is_wp_error( $terms ) ) {
										
											
											foreach ( $terms as $term ){  
												if ($i =0)?>
												<a href="<?php echo get_term_link( $term->slug, 'savi_opp_tag_soft' )?>"><?php echo $term->name;?></a>
												
											<?php
											} 
											
										}?>
								</li>
								
								
								
						 
						 <?php 	//echo get_post_meta( get_the_ID(), "landphone", true ); 
						 	
							endwhile;
							echo '</ul>';
						}
						else{
						?>
						<div class="entry">
<!--If no results are found-->
	<p><?php esc_html_e('No Software Found','Divi'); ?></p>
	
</div>
					<?php	}
						
					
				?>
						
						
						
						
						</div>
						</li>
					<ul>
					</div>
					
	<div id="content_side">
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

<div class="clear"></div>
<h1 class="main_title2">Other Details</h1>
					<div class="entry-content">
					<?php
						the_content();

						wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'Divi' ), 'after' => '</div>' ) );
					?>
					</div> <!-- .entry-content -->

					<?php
					if ( et_get_option('divi_468_enable') == 'on' ){
						echo '<div class="et-single-post-ad">';
						if ( et_get_option('divi_468_adsense') <> '' ) echo( et_get_option('divi_468_adsense') );
						else { ?>
							<a href="<?php echo esc_url(et_get_option('divi_468_url')); ?>"><img src="<?php echo esc_attr(et_get_option('divi_468_image')); ?>" alt="468 ad" class="foursixeight" /></a>
				<?php 	}
						echo '</div> <!-- .et-single-post-ad -->';
					}
				?>

					<?php
						if ( comments_open() && 'on' == et_get_option( 'divi_show_postcomments', 'on' ) )
							comments_template( '', true );
					?>
				</article> <!-- .et_pb_post -->

				<?php if (et_get_option('divi_integration_single_bottom') <> '' && et_get_option('divi_integrate_singlebottom_enable') == 'on') echo(et_get_option('divi_integration_single_bottom')); ?>
			<?php endwhile; ?>
			</div> <!-- #left-area -->

			<?php get_sidebar(); ?>
		</div> <!-- #content-area -->
	</div> <!-- .container -->
</div> <!-- #main-content -->



<?php get_footer(); ?>