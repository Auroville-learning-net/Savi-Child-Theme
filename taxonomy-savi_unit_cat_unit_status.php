<?php get_header(); ?>

<div id="main-content">
	<div class="container">
		<div id="content" class="clearfix">
			<div id="left-area">
				<?php
					if ( have_posts() ) :
						while ( have_posts() ) : the_post(); ?>
						<h2>Status : <?php single_tag_title(); ?></h2>
						<?php get_template_part( 'includes/summary', 'unit' ); 
						endwhile;
						if ( function_exists( 'wp_pagenavi' ) )
							wp_pagenavi();
						else
							get_template_part( 'includes/navigation', 'index' );
					else:
						get_template_part( 'includes', '404' );
					endif;
				?>
			</div> <!-- end #left-area -->
			<?php get_sidebar(); ?>
		</div> <!-- end #content -->
	</div> <!-- end .container -->
</div> <!-- end #main-area -->

<?php get_footer(); ?>