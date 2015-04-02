<!-- Workshops & Training -->
	<?php
	$args = array(
		'post_type' => 'ai1ec_event',
		'meta_query' => array(
			array(
				'key' => 'savi_events_details_av_unit',
				'value'    => get_the_ID(),
				'compare' => '='
			),
		),
	);
	$query = new WP_Query( $args );
	$template_dir = get_stylesheet_directory_uri ();
	wp_enqueue_style ( 'savi_events', $template_dir . '/css/savi_events.css', array (), null );
	echo "<h2>Workshops & Training</h2>";
	if ($query->have_posts ()) { 
		while ( $query->have_posts () ) {
			$query->the_post (); 
			$events_Title = get_the_title ();
			$events_permalink = get_permalink ();
			$savi_events_description_domain_content = get_post_meta ( get_the_ID (), "savi_events_description_domain_content", true );
			
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'et_pb_post' ); ?>>
				<div class="events_summary">
					<div class="sum_events_thumbnail">
					</div>
					<div id="post-<?php the_ID(); ?>" class="sum_events_content">
						<div class="sum_events_headline">
							<a href="<?php echo $events_permalink; ?>" class="events_title"><?php echo $events_Title; ?></a>
						</div>
						<div class="sum_events_content">
							<?php echo $savi_events_description_domain_content; ?>
						</div>
						
					</div>
				</div>
			</article>
	<?php	}
	} else { ?>
	<div class="entry">
		<!--If no results are found-->
		<p><?php esc_html_e('No Workshops & Training Found','Divi'); ?></p>
	</div>
	<?php	} ?>
	<!-- End Workshops & Training -->