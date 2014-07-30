<?php
/*
Template Name: My Opportunity Page
*/
?>
<?php get_header(); ?>

<div id="main-content">
	<div class="container">
		<div id="content-area" class="clearfix">
			<div id="left-area">
				<?php
					$user_ID = get_current_user_id();
				//	echo $user_ID;
					$savi_role = get_user_meta($user_ID, 'savi_role', true);
					if( $savi_role == "opportunity-contact-person"){
						$query = new WP_Query(array(  'post_type' => 'av_opportunity',  'posts_per_page' => '-1' ));
						if ( $query->have_posts() ) :
							while ( $query->have_posts() ) : $query->the_post(); 									
								?>
								<article id="post-<?php the_ID(); ?>" <?php post_class( 'et_pb_post' ); ?>>
									<?php if ( ! in_array( $post_format, array( 'link', 'audio', 'quote', 'gallery' ) ) ) : ?>
										<h5><a class="dashicons dashicons-info" href="<?php the_permalink(); ?>"></a><?php the_title(); ?></h5>
									<?php endif; ?>
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
					}else{
						echo "Mentors only access";
					}
					?>
			</div> <!-- #left-area -->
			<?php get_sidebar(); ?>
		</div> <!-- #content-area -->
	</div> <!-- .container -->
</div> <!-- #main-content -->

<?php get_footer(); ?>