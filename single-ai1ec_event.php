<?php get_header(); ?>

<div id="main-content">
	<div class="container">
		<div id="content-area" class="clearfix">
			<div id="left-area">
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
			</div> <!-- #left-area -->
			<?php get_sidebar(); ?>
		</div> <!-- #content-area -->
	</div> <!-- .container -->
</div> <!-- #main-content -->
<?php get_footer(); ?>>
<?php get_footer(); ?>