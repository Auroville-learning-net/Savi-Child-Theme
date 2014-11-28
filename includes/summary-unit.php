<?php
/* make sure the css is loaded */
$template_dir = get_stylesheet_directory_uri ();
wp_enqueue_style ( 'savi_units', $template_dir . '/css/savi_units.css', array (), null );

/* Post fields */
$titletext = get_the_title ();
$unit_permalink = get_permalink();
$excerpt = get_the_excerpt();

/* Thumbnail specifics */
$thumb = '';
$width = ( int ) apply_filters ( 'et_pb_index_blog_image_width', 150 );
$height = ( int ) apply_filters ( 'et_pb_index_blog_image_height', 100 );
$classtext = 'et_pb_post_main_image';
$thumbnail = get_thumbnail ( $width, $height, $classtext, $titletext, $titletext, false, 'Blogimage' );
$thumb = $thumbnail ["thumb"];

?>
<div class="unit_summary">
	<div class="sum_unit_thumbnail">
		<?php
		if ('on' == et_get_option ( 'divi_thumbnails_index', 'on' ) && '' !== $thumb) :
			?>
			<a href="<?php echo $unit_permalink; ?>">
				<?php print_thumbnail( $thumb, $thumbnail["use_timthumb"], $titletext, $width, $height ); ?>
			</a>
		
		<?php
		endif;
		?>
 	</div>
	<div id="post-<?php the_ID(); ?>" class="sum_unit_content">
		<div class="sum_unit_headline">
			<a href="<?php echo $unit_permalink; ?>" class="unit_title"><?php echo $titletext; ?></a>
		</div>
		<div class="sum_unit_excerpt">
			<?php echo $excerpt; ?>
		</div>
	</div>
</div>
