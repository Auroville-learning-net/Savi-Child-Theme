<?php
$template_dir = get_stylesheet_directory_uri ();
wp_enqueue_style ( 'savi_opportunities', $template_dir . '/css/savi_opportunities.css', array (), null );

$terms_daily_tasks = get_post_meta ( get_the_ID (), "daily_tasks", true );

// $terms_skills_gain = get_post_meta ( get_the_ID (), "skills_gain", true );
// if ($terms_skills_gain == "")
// $terms_skills_gain = "<i>None defined.</i>";

/* Work area's */
// $terms = get_the_terms ( get_the_ID (), 'savi_opp_cat_work_area' );
// $workareanames = array ();
// $workarealinks = array ();
// if ($terms && ! is_wp_error ( $terms )) {
// 	foreach ( $terms as $term ) {
// 		$workareanames [] = $term->name;
// 		$workarealinks [] = get_term_link ( $term->slug, 'savi_opp_cat_work_area' );
// 	}
// }

/* work types */
// $term_work_type = get_the_terms ( get_the_ID (), 'savi_opp_cat_work_type' );
// $worktypenames = array ();
// $worktypelinks = array ();
// if ($terms && ! is_wp_error ( $term_work_type )) {
// 	foreach ( $term_work_type as $term ) {
// 		$worktypenames [] = $term->name;
// 		$worktypelinks [] = get_term_link ( $term->slug, 'savi_opp_cat_work_type' );
// 	}
// }

$opportunity_Title = get_the_title ();
$opportunity_permalink = get_permalink ();

/* Get the data to display a thumbnail */
$thumb = '';
$width = ( int ) apply_filters ( 'et_pb_index_blog_image_width', 150 );
$height = ( int ) apply_filters ( 'et_pb_index_blog_image_height', 100 );
$classtext = 'et_pb_post_main_image';
$titletext = get_the_title ();
$thumbnail = get_thumbnail ( $width, $height, $classtext, $titletext, $titletext, false, 'Blogimage' );
$thumb = $thumbnail ["thumb"];
$thumbHTML = print_thumbnail($thumb, $thumbnail["use_timthumb"], $titletext,
		$width, $height, '', false);

/* we're going to search for the unit now. */
$unit_id = get_post_meta ( get_the_ID (), "av_unit", true );

/* we're looking for all units connected to this opportunity */
$query = new WP_Query ( array (
		'post_type' => 'av_unit',
		'post__in' => array (
				$unit_id 
		) 
) );
if ($query->have_posts ()) {
	while ( $query->have_posts () ) {
		$query->the_post ();
		$unit_permalink = get_permalink ();
		$unit_name = get_the_title ();
	}
} 

?>

<div class="opportunity_summary">
	<div class="sum_op_thumbnail">
		<?php     
		if ('on' == et_get_option ( 'divi_thumbnails_index', 'on' ) && '' !== $thumb) :
			?>
			<a href="<?php echo $opportunity_permalink; ?>">
				<?php echo $thumbHTML; ?>
			</a>
		
		
		
		<?php
		endif;
		?>
 	</div>
	<div id="post-<?php the_ID(); ?>" class="sum_op_content">
		<div class="sum_op_headline">
			<a href="<?php echo $opportunity_permalink; ?>" class="op_title"><?php echo $opportunity_Title; ?></a>
		</div>
		<div class="sum_opp_unit">
			at <span class="opp_unit"><a href="<?php echo $unit_permalink; ?>"><?php echo $unit_name; ?></a></span>
		</div>
		<div class="sum_op_dailytasks">
			<span class="sum_op_label">Daily tasks: </span><?php echo $terms_daily_tasks; ?>
		</div>
	</div>
</div>