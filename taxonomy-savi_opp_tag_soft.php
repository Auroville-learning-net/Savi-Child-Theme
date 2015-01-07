<?php 
$postType = $_GET['postType'];
get_header(); 
switch( $postType ) { // step 1:check if client wants Events		
	case 'ai1ec_event':
		$postName = 'Events';
		break;
	case 'av_unit': // step 2:check if client wants unit			 				
		$postName = 'Units';
		break;
	case 'av_project': // step 2:check if client wants projects								
		$postName = 'Projects';
		break;
	default: // default is av_opportunity
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
				<h2><?php echo $postName;?> for Software : <?php single_tag_title(); ?></h2>
				<div id="opportunities">
				<?php 
					if ( have_posts() ) { 
						while ( have_posts() ) {
							the_post();
							?>
							<article id="post-<?php the_ID(); ?>" <?php post_class( 'et_pb_post' ); ?>>
							<?php
							switch( $postType ) { // step 1:check if client wants Events		
								case 'ai1ec_event':
									get_template_part( 'includes/ai1ec', 'event' );	
									break;
								case 'av_unit': // step 2:check if client wants unit	
									// echo get_the_ID();
									get_template_part( 'includes/summary', 'unit' );			 				
									break;
								case 'av_project': // step 2:check if client wants projects	
									get_template_part( 'includes/summary', 'project' );												
									break;
								default: // default is av_opportunity
									get_template_part( 'includes/summary', 'opportunity' );	
									break;
							}
							?>
							</article>
							<?php	
						}
						if ( function_exists( 'wp_pagenavi' ) )
								wp_pagenavi();
						else{
							get_template_part ( 'includes/navigation', 'index' );
						}
					}else{
							switch( $postType ) { // step 1:check if client wants Events		
								case 'ai1ec_event':
									get_template_part( 'includes/no', 'events' ); 
									break;
								case 'av_unit': // step 2:check if client wants unit	
									get_template_part( 'includes/no', 'units' ); 
									break;
								case 'av_project': // step 2:check if client wants projects	
									get_template_part( 'includes/no', 'projects' ); 
									break;
								default: // default is av_opportunity
									get_template_part( 'includes/no', 'opportunity' ); 
									break;
							}
					}?>
				</div> <!-- end #opportunities -->
			</div> <!-- end .et_pb_column_3_4 -->
		</div> <!-- end .et_pb_row -->
	</div> <!-- end et_pb_section -->
</div> <!-- end #main-area -->

<?php get_footer(); ?>