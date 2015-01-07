<?php get_header(); ?>



<div id="main-content">
	<div class="container">
		<div id="content-area" class="clearfix">
			<div id="left-area">
			
		<?php
			if ( have_posts() ) :
				while ( have_posts() ) : the_post();
					get_template_part( 'includes/summary', 'project' ); 
			
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



<?php get_footer(); ?>t -->



<?php get_footer(); ?>