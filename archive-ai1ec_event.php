<?php 

get_header();

 ?>
<div id="main-content"><!-- main-Content -->
	<div class="container"><!-- container -->
		<div id="content-area" class="clearfix"><!-- content-area -->
			<div id="left-area"><!-- left-area -->
			
				<?php
					if ( have_posts() ) :
						while ( have_posts() ) : the_post();
						$terms_work_area=get_the_terms(get_the_ID() , 'savi_opp_cat_work_area' ); 
						$terms_skills_gain=get_post_meta( get_the_ID(), "skills_gain", true );
						?>
							<div id="left-grid"><!-- left-grid -->
								<div id="left-image"><!-- left-image -->
								<?php
								$thumb = '';
								$width = (int) apply_filters( 'et_pb_index_blog_image_width', 300 );
								$height = (int) apply_filters( 'et_pb_index_blog_image_height', 200 );
								$classtext = 'et_pb_post_main_image';
								$titletext = get_the_title();
								$thumbnail = get_thumbnail( $width, $height, $classtext, $titletext, $titletext, false, 'Blogimage' );
								$thumb = $thumbnail["thumb"];
								if ( 'on' == et_get_option( 'divi_thumbnails_index', 'on' ) && '' !== $thumb ) : ?>
									<a href="<?php the_permalink(); ?>">
										<?php print_thumbnail( $thumb, $thumbnail["use_timthumb"], $titletext, $width, $height ); ?>
									</a>
							<?php endif; ?>
								</div> <!-- left-image -->
								<div id="left-Content"> <!-- left-Content -->
									<article id="post-<?php the_ID(); ?>" <?php post_class( 'et_pb_post' ); ?>><!-- left-Content -->
										<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
										
											  
											<?php the_content(); ?>
									</article> <!-- article -->
								</div><!-- left-Content -->
							</div><!-- left-grid -->
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