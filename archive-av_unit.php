<?php get_header(); ?>



<div id="main-content"><!-- main-Content -->
	<div class="et_pb_section et_section_specialty container"><!-- container -->
		<div id="content-area" class="et_pb_row"><!-- content-area -->
			<div class="et_pb_column et_pb_column_1_4"> <!-- col 1/4-->
					<?php get_sidebar(); ?>
			</div>
			<div id="content" class="et_pb_column et_pb_column_3_4"><!-- col 3/4, #content for infinite scroll-->
		<?php
			if ( have_posts() ) :
				while ( have_posts() ) : the_post();
					get_template_part( 'includes/summary', 'unit' ); 
			
					endwhile;
					

					if ( function_exists( 'wp_pagenavi' ) )
						wp_pagenavi();
					else
						get_template_part( 'includes/navigation', 'index' );
				else :
					get_template_part( 'includes/no-results', 'index' );
				endif;
			?>
			
			</div> <!-- col 3/4-->
		</div> <!-- #content-area -->
	</div> <!-- .container -->
</div> <!-- #main-content -->
<?php get_footer(); ?>