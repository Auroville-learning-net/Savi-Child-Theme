<?php get_header(); ?>


<?php
	$args = array(
		'post_type' => 'av_unit'
	);
	query_posts($args);

?>

<?php
if ( have_posts() ) :
	while ( have_posts() ) : the_post();
	
	$av_units[$post->ID]= array
	(
			'post_id'=>$post->ID,
			'thumbnail'=>get_the_post_thumbnail($post->ID, 'thumbnail'),
	         'post_link'=>get_page_link($post->ID),
	         'post_title'=>get_the_title(),
	);
	endwhile;
else :
	echo wpautop( 'Sorry, no posts were found' );
endif;

?>

<?php wp_reset_postdata(); ?>


<?php
	$args = array(
		'posts_per_page' => 20,
		'post_type' => 'av_opportunity'
	);
	query_posts($args);

?>
	
<?php
if ( have_posts() ) :
	while ( have_posts() ) : the_post();
	echo '<div class="oppor_post">';	

	echo '<a href="' . get_page_link($post->ID) . '"<h2>' . get_the_title() . '</h2> </a>';
	
	echo get_the_post_thumbnail( );
	
	the_excerpt("More...");
	
	
	$av_unit = get_post_meta($post->ID, 'av_unit', true );

	$temp_av_units = $av_units;
	
	foreach ($av_units as $post_values) {
		if (in_array($av_unit, $post_values)) {
			$av_post_id = $post_values[post_id];
			echo '<div class = "av_init_info">';
			echo '<a href="' . $av_units[$av_post_id]['post_link'] . '">' . $av_units[$av_post_id]['thumbnail'] . '</a>';
			echo '</div>';

			break;
		}
	}	
	
	echo '<div class="av_unit">'; echo '<b>AV Unit: </b>';	echo esc_html(get_post_meta($post->ID, 'av_unit', true));              				echo '</div>';
	echo '<div class="contactPerson">'; echo '<b>Contact Person:</b>';	echo esc_html(get_post_meta($post->ID, 'contactPerson', true));        			echo '</div>';
	echo '<div class="contactPhone">'; echo '<b>Contact Phone:</b>'; echo esc_html(get_post_meta($post->ID, 'contactPhone', true));        	 		echo '</div>';
	echo '<div class="contactEmail">'; echo '<b>Contact Email:</b>';	echo esc_html(get_post_meta($post->ID, 'contactEmail', true));          		echo '</div>';
	echo '<div class="projectname">'; echo '<b>Project Name:</b>';	echo esc_html(get_post_meta($post->ID, 'projectname', true));          			echo '</div>';
	echo '<div class="duration">'; echo '<b>Duration:</b>';	echo esc_html(get_post_meta($post->ID, 'duration', true));              			echo '</div>';
	echo '<div class="number_of_volunteers">'; echo '<b>Number of Volunteers:</b>';	 echo esc_html(get_post_meta($post->ID, 'number_of_volunteers', true)); echo '</div>';
	echo '<div class="architect_semester">'; echo '<b>Architect Semester: </b>';	echo esc_html(get_post_meta($post->ID, 'architect_semester', true));   echo '</div>';	
	echo '<div class="computerrequired">'; echo '<b>Computer Required:</b>';	echo esc_html(get_post_meta($post->ID, 'computerrequired', true));   	   echo '</div>';
	echo '<div class="revisions">';echo '<b>Revisions:</b>'; echo esc_html(get_post_meta($post->ID, 'revisions', true));            				echo '</div>';
	echo '<div class="startdate">';echo '<b>Start Date:</b>'; echo esc_html(get_post_meta($post->ID, 'startdate', true));            				echo '</div>';
	echo '<div class="enddate">'; echo '<b>End Date</b>';	echo esc_html(get_post_meta($post->ID, 'enddate', true));              				echo '</div>';
	echo '<div class="timing">';	echo '<b>Timing:</b>'; echo esc_html(get_post_meta($post->ID, 'timing', true));               				echo '</div>';
	echo '<div class="morningtimings">'; echo '<b>Morning Timing:</b>';	echo esc_html(get_post_meta($post->ID, 'morningtimings', true));        	echo '</div>';
	echo '<div class="timings">';	echo 'Timings: </b>'; echo esc_html(get_post_meta($post->ID, 'afternoontimings', true));    echo '</div>';
	echo '</div>';
	endwhile;
else :
	echo wpautop( 'Sorry, no posts were found' );
endif;
?>

<?php get_footer(); ?>