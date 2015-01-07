<?php
/**
 * 20141125 JZ: Basic contents copied from the Divi theme
 */
get_header ();
?>
<div id="et-main-area">
	<div id="main-content">
		<div class="et_pb_section et_section_specialty">

			<div class="et_pb_row">
				<div class="et_pb_column et_pb_column_1_4">
					<?php get_sidebar(); ?>
				</div>
				<div class="et_pb_column et_pb_column_3_4">
		<?php
		if (have_posts ()) :
			while ( have_posts () ) {
				the_post ();
				$post_format = get_post_format ();
				$post_type = get_post_type ();
				?>

					<article id="post-<?php the_ID(); ?>" <?php post_class( 'et_pb_post' ); ?>>


				<?php if ( ! in_array( $post_format, array( 'link', 'audio', 'quote', 'gallery' ) ) ) : ?>
				<?php
					switch ($post_type) {
						case 'ai1ec_event' :
							get_template_part ( 'includes/ai1ec', 'event' );
							break;
						case 'av_unit' :
							get_template_part ( 'includes/summary', 'unit' );
							break;
						case 'av_project' :
							get_template_part ( 'includes/summary', 'project' );
							break;
						case 'av_opportunity' :
							get_template_part ( 'includes/summary', 'opportunity' );
							break;
						/* We will hide the next ones */
						case 'view_7' :
						case 'guest_house' :
							break;
						default :
							?>
						<h2>
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h2>
						<?php
							truncate_post ( 270 );
							
							// et_divi_post_meta ();
							
							// if ('on' !== et_get_option ( 'divi_blog_style', 'false' ))
							// truncate_post ( 270 );
							// else
							// the_content ();
							break;
					}
					?>
				<?php endif; ?>

					</article>
					<!-- .et_pb_post -->
			<?php
			}
			
			if (function_exists ( 'wp_pagenavi' ))
				wp_pagenavi ();
			else
				get_template_part ( 'includes/navigation', 'index' );
		 else :
			get_template_part ( 'includes/no-results', 'index' );
		endif;
		?>
				</div>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>