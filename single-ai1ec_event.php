<?php get_header(); ?>

<div id="main-content">
	<div class="et_pb_section et_section_specialty">

		<div class="et_pb_row">
			<div class="et_pb_column et_pb_column_1_4">
					<?php get_sidebar(); ?>
			</div>
			<div class="et_pb_column et_pb_column_3_4">
			<?php if ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				
					<div class="et_main_title">
						<h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
						<span class="et_project_categories"><?php echo get_the_term_list( get_the_ID(), 'project_category', '', ', ' ); ?></span>
					</div>
					<div class="entry-content">
						<?php the_content(); ?>
					</div> <!-- .entry-content -->	
				</article> <!-- .et_pb_post -->
			<?php endif;
			
			?>
			</div> <!-- col 3/4-->
		</div> <!-- #content-area -->
	</div> <!-- .container -->
</div> <!-- #main-content -->
<?php get_footer(); ?>