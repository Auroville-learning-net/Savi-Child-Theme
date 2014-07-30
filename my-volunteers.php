<?php
/*
Template Name: My Volunteers Page
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
							
							</article> <!-- .et_pb_post -->
							<?php }
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