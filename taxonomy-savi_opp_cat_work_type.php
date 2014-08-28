<?php
$postType = $_GET['postType']; // Get the post type ai1ec_event, av_unit, av_opportunity
get_header(); 
$unitIDs = array();
?>
<div id="main-content">
	<div class="container">
		<div id="content" class="clearfix">
			<h2>Work Type : <?php single_cat_title(); ?></h2>
				<div id="left-area">
					<?php if ( have_posts() ) { 
							while ( have_posts() ) {
								the_post();	
									switch( $postType ) { // step 1:check if client wants Events		
										case 'ai1ec_event':
												get_template_part( 'includes/ai1ec', 'event' );	
											break;
										case 'av_unit': // step 2:check if client wants unit	
												include( 'includes/units-taxonomy.php'); 												
												//$unitIDs[] = get_post_meta( get_the_ID(), "av_unit", true );
											break;
										default: // default is av_opportunity
												get_template_part( 'includes/summary', 'opportunity' );	
											break;
								}
							}// end while loop
							/*if ( $postType == "av_unit" ) { // step 3: build a WP query to get all avunit custom posts from DB		
									$units = array_unique($unitIDs);
									$query = new WP_Query(array('post__in' => $units, post_type => 'av_unit'));
									if ( $query->have_posts() ){
										while ( $query->have_posts() ) { 
											$query->the_post(); 
											include( 'includes/units-taxonomy.php'); 
										}
									}
								}	*/
						}else{
							if ( $postType == "" ) {	
								get_template_part( 'includes/no', 'opportunity' ); 
							}else{
								get_template_part( 'includes/no', 'units' ); 
							}
						}?>
				   </div> <!-- end #left-area -->
			<?php get_sidebar(); ?>
		</div> <!-- end #content -->
	</div> <!-- end .container -->
</div> <!-- end #main-area -->
<?php get_footer(); ?>			   