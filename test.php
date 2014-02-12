<div id="content_opp">
	<?php 
				
		$query = new WP_Query(array( 'post_type' => 'av_unit',  'post__in' => $av_unit ));
			if ( $query->have_posts() ){
			echo '<ul>';
			echo '<li class="left_li">'.Units.'</li>';
			echo '<li class="right_li">';
			echo '<ul>';				
				while ( $query->have_posts() ) : 			
				$query->the_post();								
					?>
					<li class="right_li1">	
						<a href="<?php echo get_permalink(); ?>"><?php echo  the_title(); ?></a> 
					</li>														
	 <?php 	
						 	
				endwhile;
			echo '</ul>';
			echo '</li>';
			} ?>
			<?php
			
			
			$query = new WP_Query(array('post_type' => 'av_unit',  'post__in' => $savi_opp_cat_work_area ));
				if ( $query->have_posts() ){
						
						echo '<ul>';	
					echo '<li class="left_li">'.Area.'</li>';
					echo '<li class="right_li">';
					echo '<ul>';
					while ( $query->have_posts() ) : 
						$query->the_post();
						$terms=get_the_terms(get_the_ID() , 'savi_opp_cat_work_area' ); 
								
						?>
						
						&nbsp;
						
						<?php 
						 	endwhile;
							echo '</ul>';
							echo '</li>';
							
							
							
							
							
							
							echo '</ul>';
						}				
				?>
				<?php
			
			
			$query = new WP_Query(array('post_type' => 'av_unit',  'post__in' => $savi_opp_cat_work_area ));
				if ( $query->have_posts() ){
						
						echo '<ul>';	
					echo '<li class="left_li">'.Type.'</li>';
					echo '<li class="right_li">';
					echo '<ul>';
					while ( $query->have_posts() ) : 
						$query->the_post();
						$terms=get_the_terms(get_the_ID() , 'savi_opp_cat_work_area' ); 
								
						?>
						&nbsp;
						<?php 
						 	endwhile;
							echo '</ul>';
							echo '</li>';
							
							
							
							
							
							
							echo '</ul>';
						}				
				?>
		
						
					
					</div>