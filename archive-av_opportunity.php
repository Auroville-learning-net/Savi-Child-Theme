<?php get_header(); ?>
<div id="main-content">
	<div class="container">
		<div id="content-area" class="clearfix">
			<div id="left-area">
			
        		<?php
        			if ( have_posts() ) :
        				while ( have_posts() ) : the_post();
        				$terms_daily_tasks=get_post_meta( get_the_ID(), "daily_tasks", true );
        				$terms_skills_gain=get_post_meta( get_the_ID(), "skills_gain", true );
        				$no_of_opportunities=get_post_meta( get_the_ID(), "no_of_opportunities", true );
					if($no_of_opportunities > 0 ){	
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

            					if ( 'on' == et_get_option( 'divi_thumbnails_index', 'on' ) && '' !== $thumb ) : ?>
            						<a href="<?php the_permalink(); ?>">
            							<?php print_thumbnail( $thumb, $thumbnail["use_timthumb"], $titletext, $width, $height ); ?>
            						</a>
        						<?php
        							endif;
        						?>
				    	</div>
				    	<div id="left-Content">
				          	<article id="post-<?php the_ID(); ?>" <?php post_class( 'et_pb_post' ); ?>>
				            	<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				                	<div class="table1">
			                    		<ul>
			                        		<li>
						                        <div class="left_li_arc">Unit Name :</div>
				                            		<div class="right_li">
					                                	<?php 
				                                    		$id= get_post_meta(get_the_ID(), "av_unit", true );
				                                    		$query = new WP_Query(array(  'post_type' => 'av_unit',  'post__in' => array($id)));
				                                            	if ( $query->have_posts() ){
			                                                		echo '<ul>';
                                            							while ( $query->have_posts() ) : 
                                            								$query->the_post();	?>
                                            								<li>
                                            										<a href="<?php echo get_permalink(); ?>"><?php echo  the_title(); ?></a> 
                                            								</li>
                                            						 <?php 	//echo get_post_meta( get_the_ID(), "landphone", true ); 
        						 	
                                        							endwhile;
                                        							echo '</ul>';
                                        						}
    					                                    	else{
					                                	?>
                					                            	<div class="entry">
                                                                        <!--If no results are found-->
                                                                        	<p><?php esc_html_e('No Opportunity Specified','Divi'); ?></p>
                                                                        	
                                                                    </div>
                                            					<?php	}	?>	
			                                	</div>
                        					</li>
                        					<li>
                        						<div class="left_li_arc">Daily Tasks</div>
                        						<div class="right_li"><?php echo $terms_daily_tasks ?></div>
                        					</li>
                        					<li>
                        						<div class="left_li_arc">Skills Gain</div>
                        						<div class="right_li"><?php echo $terms_skills_gain ?></div>
                        					</li>
                        					<li>
                        						<div class="left_li_arc"> Work area : </div>
                        						<div class="right_li">
                        		    	<?php 
                        					    	$query = new WP_Query(array( 'meta_key' => 'av_unit', 'meta_value' => get_the_ID(),'meta_compare' => '=' , 'post_type' => 'av_opportunity' ));
                        					    	if ( $query->have_posts() ){
                        					
                        						    	while ( $query->have_posts() ) : 
                        						    		$query->the_post();
                        						    		$terms=get_the_terms(get_the_ID() , 'savi_opp_cat_work_area' ); 
                        						    		$term_work_type=get_the_terms(get_the_ID() , 'savi_opp_cat_work_type' ); 
                                                            if ( $terms && ! is_wp_error( $terms ) ) {
                        											echo '(';
                        											$i=0;
                        											foreach ( $terms as $term ){  
                        												if ($i >0) echo ',';?>
                        												<a href="<?php echo get_term_link( $term->slug, 'savi_opp_cat_work_area' )?>"><?php echo $term->name;?></a>
                        											<?php $i++;
                        											} 
                        											echo ')';
                        										}
                            							endwhile;
                        							
                        						    }
                        					    	else{
                        					?>
                            						<div class="entry">
                            
                            							<p><?php esc_html_e('No Work area Specified','Divi'); ?></p>
                            	
                            						</div>
                            					<?php	}
                            				?>
				                    	</div>
				                	</li>
				                	<li>
                						<div class="left_li_arc"> Work Type : </div>
                					    	<div class="right_li"><?php 
                        						if ( $terms && ! is_wp_error( $terms ) ) {
                        											echo '(';
                        											$i=0;
                        						foreach($term_work_type as $value){
                        						if ($i >0) echo ',';?>
                        								<a href="<?php echo get_term_link( $value->slug, 'savi_opp_cat_work_type' )?>"><?php echo $value->name;?></a>
                        								<?php
                        								 $i++;
                        											} 
                        											echo ')';
                        						}
                        					
                        						?>
                											
                				        	</div>
				                	</li>
				            	</ul>
				        	</div>
				    	</article> <!-- .et_pb_post -->
					</div>
				</div>
			    <?php
				}
					endwhile;
					if ( function_exists( 'wp_pagenavi' ) )
						wp_pagenavi();
					else
						get_template_part( 'includes/navigation', 'index' );
				else :
					get_template_part( 'includes/no-results', 'index' );
				endif;
		    	?>
			
			</div> <!-- #left-area -->

			<?php get_sidebar(); ?>
		</div> <!-- #content-area -->
	</div> <!-- .container -->
</div> <!-- #main-content -->

<?php get_footer(); ?>