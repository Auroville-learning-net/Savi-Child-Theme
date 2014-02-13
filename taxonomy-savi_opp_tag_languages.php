<?php
$postType = $_GET['postType'];
get_header(); 
$unitIDs = array();
 ?>

<div id="main-content">
	<div class="container">
	
        <h2>Languages : <?php single_tag_title(); ?></h2>
		<div id="content" class="clearfix">
		
			<div id="left-area">
				<?php if ( have_posts() ) { 
				while ( have_posts() ) {
						the_post();
						
						if ( $postType == "" ) { // step 1:check if client wants opportunities or units
							get_template_part( 'includes/summary', 'opportunity' ); 
						}else{ // step2: client wants units, so check unit ID in each opportunity, store in array
								//echo "<div> Unit ".get_post_meta( get_the_ID(), "av_unit", true )."</div>";
								$unitIDs[] = get_post_meta( get_the_ID(), "av_unit", true );
							}
						}
						if ( $postType != "" ) { // step 3: build a WP query to get all avunit custom posts from DB
							$units = array_unique($unitIDs);
							//echo "<div> Unit IDs:<pre>",print_r($units),"</pre></div>";
							$query = new WP_Query(array('post__in' => $units, post_type => 'av_unit'));
							if ( $query->have_posts() ){
								while ( $query->have_posts() ) { 
									$query->the_post(); 
									include( 'includes/units-taxonomy.php'); 
								}
							}
						}	
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