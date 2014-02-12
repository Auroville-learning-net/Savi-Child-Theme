<?php 
$postType = $_GET['postType'];
get_header(); 
$unitIDs = array();
?>
<div id="main-area">
	<div class="container">
		<?php get_template_part( 'includes/breadcrumbs', 'index' ); ?>
        <h2>Taxonomy-work_type111.php <?php echo $postType; ?></h2>
		<div id="content" class="clearfix">
			<div id="left-area">
			
				<?php 
				
				if ( have_posts() ) {
				   while ( have_posts() ) {
						the_post(); 
						if ( $postType == "" ) { // step 1:check if client wants opportunities or units
							get_template_part( 'includes/entry', 'index' ); 
						}
						else{ // step2: client wants units, so check unit ID in each opportunity, store in array
							//echo "<div> Unit ".get_post_meta( get_the_ID(), "av_unit", true )."</div>";
							$unitIDs[] = get_post_meta( get_the_ID(), "av_unit", true );
						}
				   }
				   //echo "<div> here 2</div>";
				   if ( $postType != "" ) { // step 3: build a WP query to get all avunit custom posts from DB
						$units = array_unique($unitIDs);
						//echo "<div> Unit IDs:<pre>",print_r($units),"</pre></div>";
						$query = new WP_Query(array('post__in' => $units, post_type => 'av_unit'));
						if ( $query->have_posts() ){
							while ( $query->have_posts() ) { 
								$query->the_post(); 
								get_template_part( 'includes/units', 'index' ); 
							}
						}
					}	
					if ( function_exists( 'wp_pagenavi' ) ) {
						wp_pagenavi();
					} else {
						get_template_part( 'includes/navigation', 'entry' );
					} 
 			}else{
					get_template_part( 'includes/no-results', 'entry' );
			}
				?>
			</div> <!-- end #left-area -->
			
			
			

			<?php get_sidebar(); ?>
		</div> <!-- end #content -->
	</div> <!-- end .container -->
</div> <!-- end #main-area -->

<?php get_footer(); ?>
				   