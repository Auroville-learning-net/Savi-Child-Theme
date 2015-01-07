<?php
/*
Template Name: Volunteers Opportunity Page
*/
 get_header(); 
 ?>

<div id="main-content">
	<div class="container">
		<div id="content-area" class="clearfix">
			<div id="left-area">
			<h2><?php echo get_the_title(); ?></h2>
			<?php
			$user_id = get_current_user_id(); // get the current user ID
			$profile_post_id = get_user_meta( $user_id, "profile_post_id", true ); // get the profile  post_id 
			$post_type = get_post_type( $profile_post_id ); // get the profile post type
			//echo $post_type;
			switch($post_type){
				case 'view_1':  
					echo "<h2>My Profile Form is Not Full Fill still Now</h2>";
				break;
				case 'view_2':   //if post type is view 2 
					$args = array(
							'author'    => $user_id, // I could also use $user_ID, right?
							'post_type' => 'view_2'
							);
					$the_query = new WP_Query( $args );
					if ( $the_query->have_posts() ) :
						while ( $the_query->have_posts() ) : $the_query->the_post();
							$my_opp_ID = get_the_ID(); //get the my opp ID
							$exp_opp = get_post_meta( $profile_post_id, 'express_opportunities', true );
							foreach($exp_opp as $exp_opp1){
								echo '<h4 id="opp_'.$exp_opp1['express_opportunity'].'"><a href="'. get_permalink($exp_opp1['express_opportunity']) .'" target="_blank">'. get_the_title($exp_opp1['express_opportunity']).'</a></h4>'; // Display the title of the Opportunity
							}
						endwhile;
						wp_reset_postdata();
					else :
						echo "<h2>You Currently have no opportunity registered in this data base</h2>";
					endif;
				break;
				case 'view_3': //if post type is view 3 
					echo do_shortcode('[formidable id=34 title=true description=true]'); 
				break;
				case 'view_4': 
				case 'view_5': 
				case 'view_6':
				case 'view_7':
				default:
					echo "<h2>Your Profile is under savi team</h2>";
				break;
			} 
			?>
				</div> <!-- #left-area -->
			<?php get_sidebar(); ?>
		</div> <!-- #content-area -->
	</div> <!-- .container -->
</div> <!-- #main-content -->


<?php get_footer(); ?>

</div> <!-- #main-content -->


<?php get_footer(); ?>
