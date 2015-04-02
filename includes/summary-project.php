<?php
$terms_daily_tasks=get_post_meta( get_the_ID(), "daily_tasks", true );
if ( $terms_daily_tasks =="") $terms_daily_tasks="<i>None defined.</i>";
$terms_skills_gain=get_post_meta( get_the_ID(), "skills_gain", true );
if ( $terms_skills_gain =="") $terms_skills_gain="<i>None defined.</i>";
$terms=get_the_terms(get_the_ID() , 'savi_opp_cat_work_area' ); 
$term_work_type=get_the_terms(get_the_ID() , 'savi_opp_cat_work_type' ); 
?>

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
		<h2><a href="<?php the_permalink(); ?>"><?php echo get_the_title(); ?></a></h2>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'et_pb_post' ); ?>>
			<div class="table1">
				<ul>
					<li>
						<?php echo get_the_content(); ?>
					</li>
				</ul>
			</div>
				

		</article> <!-- .et_pb_post -->
	</div>
</div>
	