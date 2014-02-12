<?php
get_header(); 

 ?>
<div id="main-content">
	<div class="container">
		<div id="content-area" class="clearfix">
			<div id="left-area">
			
				
			<?php while ( have_posts() ) : the_post(); ?>
				<?php if (et_get_option('divi_integration_single_bottom') <> '' && et_get_option('divi_integrate_singlebottom_enable') == 'on') echo(et_get_option('divi_integration_single_bottom')); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class( 'et_pb_post' ); ?>>
					<h1 class="main_title"><?php the_title(); ?></h1>

				
				
		<div id="lefts">
				<div class="excerpt_post">
				<?php the_excerpt(); ?>
				</div>
				<div class="table">
				<ul>
					<li>
						<div class="left_li">Contact Person : </div>
						<div class="right_li"><?php echo get_post_meta( get_the_ID(), "unit_name", true ); ?></div>
					</li><li>
						<div class="left_li">Landphone : </div>
						<div class="right_li"><?php echo get_post_meta( get_the_ID(), "landphone", true ); ?></div>
					</li>
					<li>
						<div class="left_li">Location : </div>
						<div class="right_li"><?php echo get_post_meta( get_the_ID(), "_et_listing_custom_address", true ); ?></div></li>
					<li>
						<div class="left_li"><a href="<?php echo get_post_meta( get_the_ID(), "unit_url", true ); ?>">Website :</a> </div>
						<div class="right_li"> &nbsp;</div>
					</li>
			</ul>
</div>
				
				
				
				
				
				
				
				
				
				
				
				
				
				
					<div class="entry-content">
					<?php
						the_content();

						wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'Divi' ), 'after' => '</div>' ) );
					?>
					</div> <!-- .entry-content -->
</div>
<div id="rights">
<div class="image_in">
<?php
					

					$thumb = '';

					$width = (int) apply_filters( 'et_pb_index_blog_image_width', 400 );

					$height = (int) apply_filters( 'et_pb_index_blog_image_height', 270 );
					$classtext = 'et_featured_image';
					$titletext = get_the_title();
					$thumbnail = get_thumbnail( $width, $height, $classtext, $titletext, $titletext, false, 'Blogimage' );
					$thumb = $thumbnail["thumb"];

					if ( 'on' === et_get_option( 'divi_thumbnails', 'on' ) && '' !== $thumb )
						print_thumbnail( $thumb, $thumbnail["use_timthumb"], $titletext, $width, $height );
				?>
</div>
<div class="shortcode">
<?php echo do_shortcode('[google-map-v3 shortcodeid="TO_BE_GENERATED" width="400" height="270" zoom="14" maptype="roadmap" mapalign="center" directionhint="false" language="default" poweredby="false" maptypecontrol="false" pancontrol="false" zoomcontrol="true" scalecontrol="false" streetviewcontrol="false" scrollwheelcontrol="false" draggable="false" tiltfourtyfive="false" enablegeolocationmarker="false" enablemarkerclustering="false" addmarkermashup="false" addmarkermashupbubble="false" addmarkerlist="12.006879178316174, 79.81031248935551{}1-default.png" bubbleautopan="false" distanceunits="km" showbike="false" showtraffic="false" showpanoramio="false"]'); ?>

</div>

</div>

<div id="Containter_opp">
			<h1 class="main_title">Opportunities</h1>
				<?php 
				
				   
				  
						$query = new WP_Query(array( 'meta_key' => 'av_unit', 'meta_value' => get_the_ID(),'meta_compare' => '=' , 'post_type' => 'av_opportunity' ));
						if ( $query->have_posts() ){
						echo '<ul>';
							while ( $query->have_posts() ) : 
								$query->the_post();
								$terms=get_the_terms(get_the_ID() , 'savi_opp_cat_work_area' ); 
								
								?>
								<li>
								<a href="<?php echo get_permalink(); ?>"><?php echo  the_title(); ?></a> 
									<?php if ( $terms && ! is_wp_error( $terms ) ) {
											echo '(';
											$i=0;
											foreach ( $terms as $term ){  
												if ($i >0) echo ',';?>
												<a href="<?php echo get_term_link( $term->slug, 'savi_opp_cat_work_area' )?>"><?php echo $term->name;?></a>
												
											<?php $i++;
											} 
											echo ')';
										}?>
								</li>
								
								
								
						 
						 <?php 	//echo get_post_meta( get_the_ID(), "landphone", true ); 
						 	
							endwhile;
							echo '</ul>';
						}
						else{
						?>
						<div class="entry">
<!--If no results are found-->
	<p><?php esc_html_e('No Opportunity Found','Divi'); ?></p>
	
</div>
					<?php	}
						
					
				?>
			</div> 




					<?php
					if ( et_get_option('divi_468_enable') == 'on' ){
						echo '<div class="et-single-post-ad">';
						if ( et_get_option('divi_468_adsense') <> '' ) echo( et_get_option('divi_468_adsense') );
						else { ?>
							<a href="<?php echo esc_url(et_get_option('divi_468_url')); ?>"><img src="<?php echo esc_attr(et_get_option('divi_468_image')); ?>" alt="468 ad" class="foursixeight" /></a>
				<?php 	}
						echo '</div> <!-- .et-single-post-ad -->';
					}
				?>

					<?php
						if ( comments_open() && 'on' == et_get_option( 'divi_show_postcomments', 'on' ) )
							comments_template( '', true );
					?>
				</article> <!-- .et_pb_post -->

				<?php if (et_get_option('divi_integration_single_bottom') <> '' && et_get_option('divi_integrate_singlebottom_enable') == 'on') echo(et_get_option('divi_integration_single_bottom')); ?>
			<?php endwhile; ?>
			</div> <!-- #left-area -->

			<?php get_sidebar(); ?>
		</div> <!-- #content-area -->
	</div> <!-- .container -->
</div> <!-- #main-content -->
<?php get_footer(); ?>