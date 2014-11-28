<?php
$taxonomy = 'savi_opp_cat_work_area'; // get_query_var('category');

$term = get_term_by ( 'slug', $wp_query->query [$taxonomy], $taxonomy );

$postType = $_GET ['postType']; // Get the post type ai1ec_event, av_unit, av_opportunity

$display_count = 10;
$page = $wp_query->query ['paged'] ? $wp_query->query ['paged'] : 1;
$offset = ($page - 1) * $display_count;

get_header ();
// $unitIDs = array();
switch ($postType) { // step 1:check if area client wants Events
	case 'ai1ec_event' :
		$postName = 'Events';
		break;
	case 'av_unit' : // step 2:check if client wants unit
		$postName = 'Units';
		break;
	case 'av_project' : // step 2:check if client wants projects
		$postName = 'Projects';
		break;
	default : // default is av_opportunity
		$postName = 'Opportunities';
		break;
}
?>
<div id="main-content">
	<div class="et_pb_section et_section_specialty">

		<div class="et_pb_row">
			<div class="et_pb_column et_pb_column_1_4">
					<?php get_sidebar(); ?>
				</div>
			<div class="et_pb_column et_pb_column_3_4">

				<h2><?php echo $postName;?> for area : <?php single_cat_title(); ?></h2>
					<?php
					
					// Define the query
					$args = array (
							'savi_opp_cat_work_area' => $term->slug,
							'post_type' => 'av_opportunity',
							
							// 'orderby' => 'date',
							// 'order' => 'desc',
							'number' => $display_count,
// 							'page' => $page,
							'offset' => $offset 
					);
					$query = new WP_Query ( $args );
					
					if ($query->have_posts ()) {
						while ( $query->have_posts () ) {
							$query->the_post ();
							?>
							<article id="post-<?php the_ID(); ?>" <?php post_class( 'et_pb_post' ); ?>>
							<?php
							switch ($postType) { // step 1:check if client wants Events
								case 'ai1ec_event' :
									get_template_part ( 'includes/ai1ec', 'event' );
									break;
								case 'av_unit' : // step 2:check if client wants unit
								                 // $parentIDs[] = get_post_meta( get_the_ID(), "av_unit", true );
									get_template_part ( 'includes/summary', 'unit' );
									break;
								case 'av_project' :
									
									// $parentIDs[] = get_post_meta( get_the_ID(), "av_unit", true );
									get_template_part ( 'includes/summary', 'project' );
									break;
								default : // default is av_opportunity
									get_template_part ( 'includes/summary', 'opportunity' );
									$postName = 'Opportunities';
									break;
							}
							?>
							</article>
							<?php														
						}
						if (function_exists ( 'wp_pagenavi' ))
							wp_pagenavi ();
						else {
							get_template_part ( 'includes/navigation', 'index' );
						}
					} else {
						/** nothing found ;( **/
						get_template_part ( 'includes/no', 'opportunity' );
					}
					
					?>
			</div>
		</div>
	</div>
</div>
<!-- end #main-area -->
<?php get_footer(); ?>