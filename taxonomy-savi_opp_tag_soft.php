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
	<div class="container">
	<h2><?php echo $postName;?> for Software : <?php single_tag_title(); ?></h2>
		<div id="content" class="clearfix">
		
			<div id="left-area">
				<?php 
					if ( have_posts() ) { 
						while ( have_posts() ) {
							the_post();
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
						}
						if ( function_exists( 'wp_pagenavi' ) )
								wp_pagenavi();
						else{ ?>
							<div class="pagination clearfix">
								<div class="alignleft"><?php next_posts_link('&laquo; More '.$postName) ?></div>
								<div class="alignright"><?php previous_posts_link('Previous '.$postName.' &raquo;') ?></div>
							</div>
						<?php }
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
				</div> <!-- end #left-area -->
			<?php get_sidebar(); ?>
		</div> <!-- end #content -->
	</div> <!-- end .container -->
</div> <!-- end #main-area -->

<?php get_footer(); ?>