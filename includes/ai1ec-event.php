<div id="left-grid">
	<div id="left-image">
		<?php
			$thumb = '';
			$width = (int) apply_filters( 'et_pb_index_blog_image_width', 300 );
			$height = (int) apply_filters( 'et_pb_index_blog_image_height', 200 );
			$classtext = 'et_pb_post_main_image';
			$titletext = get_the_title();
			$thumbnail = get_thumbnail( $width, $height, $classtext, $titletext, $titletext, false, 'Blogimage' );
			$thumb = $thumbnail["thumb"];
			if ( 'on' == et_get_option( 'divi_thumbnails_index', 'on' ) && '' !== $thumb ) : 
		?>
			<a href="<?php the_permalink(); ?>">
				<?php print_thumbnail( $thumb, $thumbnail["use_timthumb"], $titletext, $width, $height ); ?>
			</a>
			<?php
				endif;
			?>
	</div>
	<div id="left-Content">
		<h2><?php echo get_the_title(); ?></h2>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'et_pb_post' ); ?>>
			<div class="entry-content">
				<?php
					the_content();
					if ( ! $is_page_builder_used )
						wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'Divi' ), 'after' => '</div>' ) );
				?>
			</div> <!-- .entry-content -->
		</article> <!-- .et_pb_post -->
	</div>
</div>
