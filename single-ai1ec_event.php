<?php get_header(); ?>

<div id="main-content">
	<div class="et_pb_section et_section_specialty">

		<div class="et_pb_row">
			<div class="et_pb_column et_pb_column_1_4">
					<?php get_sidebar(); ?>
			</div>
			<div class="et_pb_column et_pb_column_3_4">
			<?php if ( have_posts() ) : the_post(); 
			$org_unit_id = get_post_meta(get_the_ID(), "savi_events_details_av_unit", true);
			$org_unit_name = get_the_title($org_unit_id);
			$domain_content = get_post_meta(get_the_ID(), "savi_events_description_domain_content", true);
			$duration = get_post_meta(get_the_ID(), "savi_events_details_duration", true);
			$audiences_array  = wp_get_post_terms(get_the_ID(), "savi_events_tag_int_aud", array("fields" => "names"));
			if ( $audiences_array){
				$audiences = array();
				foreach ( $audiences_array as $audience ) {
				 $audiences[] = $audience;
				}
				$audience_name = join( ", ", $audiences );
				//echo $audience_name;
			}
		//	$description = get_post_meta(get_the_ID(), "unit_short_name", true);
			$cost_description = get_post_meta(get_the_ID(), "savi_events_description_cost_of_description", true);
			$prerequisites = get_post_meta(get_the_ID(), "savi_events_details_prerequisites", true);
			$max_participants = get_post_meta(get_the_ID(), "savi_events_details_max_participants", true);
			$min_participants = get_post_meta(get_the_ID(), "savi_events_details_min_participants", true);
			$purpose = get_post_meta(get_the_ID(), "savi_events_description_purpose", true);
			$approach = get_post_meta(get_the_ID(), "savi_events_description_approach", true);
			$team = get_post_meta(get_the_ID(), "savi_events_description_team", true);
			$assessment = get_post_meta(get_the_ID(), "savi_events_description_assesment", true);
			$other_source_funding = get_post_meta(get_the_ID(), "savi_events_description_other_sources_of_funding", true);
			?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				
					<div class="et_pb_row_inner">
						<h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
						<span class="et_project_categories"><?php echo get_the_term_list( get_the_ID(), 'project_category', '', ', ' ); ?></span>
						<?php
							if ($org_unit_name != "") {
						?>
							<p><span class="fieldlabel">Organizing Unit:</span><a href="<?php echo get_the_permalink($org_unit_id); ?>"><?php echo $org_unit_name; ?></a></p>
						<?php } ?>
						<?php
							if ($domain_content != "") {
						?>
							<p><span class="fieldlabel">Domain &amp; contents:</span><?php echo $domain_content; ?></p>
						<?php } ?>
						<?php
							if ($duration != "") {
						?>
							<p><span class="fieldlabel">Duration:</span><?php echo $duration; ?></p>
						<?php } ?>
						<?php
							if ($audience_name != "") {
						?>
							<p><span class="fieldlabel">Intended Audiences :</span><?php echo $audience_name; ?></p>
						<?php } ?>
						<?php
							if ($cost_description != "") {
						?>
							<p><span class="fieldlabel">Description of cost:</span><?php echo $cost_description; ?></p>
						<?php } ?>
						<?php
							if ($prerequisites != "") {
						?>
							<p><span class="fieldlabel">Prerequisites:</span><?php echo $prerequisites; ?></p>
						<?php } ?>
						<?php
							if ($max_participants != "") {
						?>
							<p><span class="fieldlabel">Maximum particispants:</span><?php echo $max_participants; ?></p>
						<?php } ?>
						<?php
							if ($min_participants != "") {
						?>
							<p><span class="fieldlabel">Minimum participants :</span><?php echo $min_participants; ?></p>
						<?php } ?>
						<?php
							if ($purpose != "") {
						?>
							<p><span class="fieldlabel">Purpose :</span><?php echo $purpose; ?></p>
						<?php } ?>
						<?php
							if ($approach != "") {
						?>
							<p><span class="fieldlabel">Approach :</span><?php echo $approach; ?></p>
						<?php } ?>
						<?php
							if ($team != "") {
						?>
							<p><span class="fieldlabel">Team :</span><?php echo $team; ?></p>
						<?php } ?>
						<?php
							if ($assessment != "") {
						?>
							<p><span class="fieldlabel">Assessment :</span><?php echo $assessment; ?></p>
						<?php } ?>
						<?php
							if ($other_source_funding != "") {
						?>
							<p><span class="fieldlabel">Other Source Funding :</span><?php echo $other_source_funding; ?></p>
						<?php } ?>
						<div class="entry-content">
							<?php the_content(); ?>
						</div> <!-- .entry-content -->				
					</div>
				</article> <!-- .et_pb_post -->
			<?php endif;
			
			?>
			</div> <!-- col 3/4-->
		</div> <!-- #content-area -->
	</div> <!-- .container -->
</div> <!-- #main-content -->
<?php get_footer(); ?>