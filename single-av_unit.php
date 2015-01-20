<?php get_header(); ?>
<div id="main-content"><!-- main-Content -->
	<div class="et_pb_section et_section_specialty container"><!-- container -->
		<div id="content-area" class="et_pb_row"><!-- content-area -->
			<div class="et_pb_column et_pb_column_1_4"> <!-- col 1/4-->
					<?php get_sidebar(); ?>
			</div>
			<div class="et_pb_column et_pb_column_3_4"><!-- col 3/4-->
			
			<?php the_post (); ?>
			<?php
				$unit_title = get_the_title();
				$unit_abbrev = get_post_meta(get_the_ID(), "unit_short_name", true);
				if ($unit_abbrev <> "" ) $unit_abbrev = "(" . $unit_abbrev . ")";
				$contactperson = get_post_meta ( get_the_ID (), "unit_name", true );
				$contactperson_phone = get_post_meta ( get_the_ID (), "contact_number", true );
				$contactperson_email = get_post_meta ( get_the_ID (), "contact_email", true );
				$landphone = get_post_meta ( get_the_ID (), "landphone", true );
				$unit_url = get_post_meta ( get_the_ID (), "unit_url", true );
				$unit_address = get_post_meta ( get_the_ID (), "_et_listing_custom_address", true );
				$thumb = '';
				$width = ( int ) apply_filters ( 'et_pb_index_blog_image_width', 225 );
				$height = ( int ) apply_filters ( 'et_pb_index_blog_image_height', 155 );
				$classtext = 'et_featured_image';
				$titletext = get_the_title ();
				$thumbnail = get_thumbnail ( $width, $height, $classtext, $titletext, $titletext, false, 'Blogimage' );
				$thumb = $thumbnail ["thumb"];
				$affiliation = get_post_meta(get_the_ID(), "link_with_units", true);
				$affiliation_note = get_post_meta(get_the_ID(), "affiliation_note", true);
				switch ($affiliation) {
					case "Affiliated External":
						$affiliation = "External affiliation";
						break;
					case "Affiliated Internal":
						$affiliation = "Internal affiliation";
						break;
					default:
						$affiliation = "";
					break;
				}
				
				/* Check Public view content whether its shows frontend or not*/
				$showcontent = false;
				$current_user = wp_get_current_user();
				switch($current_user->ID){
					case 0:
						$showcontent=false;
					break;
					default:
						$showcontent=true;
					break;
				}
				?>
				<?php if (et_get_option('divi_integration_single_bottom') <> '' && et_get_option('divi_integrate_singlebottom_enable') == 'on') echo(et_get_option('divi_integration_single_bottom')); ?>
				<!-- Here the new posting format will come -->
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'et_pb_post' ); ?>>
					<div class="et_pb_row_inner">
						<div class="et_pb_column et_pb_column_4_4 et_pb_column_inner">
							<h1><?php echo $unit_title . " " . $unit_abbrev; ?></h1>
						</div>
					</div>
					<div class="et_pb_row_inner">
						<div class="et_pb_column et_pb_column_inner" >
							<?php
							if ('on' === et_get_option ( 'divi_thumbnails', 'on' ) && '' !== $thumb) {
							?>
								<div class="unit_thumbnail">
								<?php 
									print_thumbnail ( $thumb, $thumbnail ["use_timthumb"], $titletext, $width, $height );
								?>
								</div>
							<?php } ?>
							<?php if (has_excerpt()) { ?><div class="unit_text_excerpt"><?php the_excerpt(); ?></div> <?php } ?>
							<div class="unit_text_content"><?php the_content(); ?></div>
						</div>
					</div>
					<div class="et_pb_row_inner">
						<p>&nbsp;</p>
					</div>
					<div class="et_pb_row_inner">
						<div class="et_pb_column et_pb_column_3_8 et_pb_column_inner">
							<!-- First column -->
							<?php
							if ($unit_address != "") {
							?>
							<p>
								<span class="fieldlabel">Location:</span><?php echo $unit_address; ?>
							</p>
							<?php } ?>
							<?php
							if ($unit_url != "" && $showcontent===true) {
							?>
							<p>
								<span class="fieldlabel">Website:</span><a target="_blank" href="<?php echo $unit_url; ?>"><?php echo $unit_url; ?></a>
							</p>
							<?php } ?>
						</div>
						<div class="et_pb_column et_pb_column_3_8 et_pb_column_inner">
							
							<?php
							if ($landphone != "" && $showcontent===true) {
								?>
							<p>
								<span class="fieldlabel">Landline:</span><?php echo $landphone; ?>
							</p>
							<?php } ?>
							
							<?php
							if ($contactperson != "" && $showcontent===true) {
							?>
							<p>
								<span class="fieldlabel">Coordinator:</span><?php echo $contactperson; ?>
								<?php if ($contactperson_phone != "") {?>
								<br/><span class="fieldlabel">&nbsp;</span><?php echo $contactperson_phone; ?>
								<?php } ?>
								<?php if ($contactperson_email != "") {?>
								<br/><span class="fieldlabel">&nbsp;</span><?php echo $contactperson_email; ?>
								<?php } ?>
							</p>
							<?php } ?>
						</div>
					</div>
					<?php if ($affiliation != "") {?>
					<div class="et_pb_row_inner">
						<div class="et_pb_column et_pb_column_4_4 et_pb_column_inner">
							<p></p>
							<p>
								<span class="fieldlabel"><?php echo $affiliation; ?>:&nbsp;</span><?php echo $affiliation_note; ?>
							</p>
						</div>
					</div>
					<?php } ?>
					<div class="et_pb_row_inner">
						<div class="et_pb_column et_pb_column_4_4 et_pb_column_inner">
							<!-- Start Opportunities list -->
							<?php
							$OppQuery = new WP_Query ( array (
									'meta_key' => 'av_unit',
									'meta_value' => get_the_ID (),
									'meta_compare' => '=',
									'post_type' => 'av_opportunity' 
							) );
							if ($OppQuery->have_posts ()) {
								echo "<H2>Opportunities at " . $unit_title . "</H2>";
								while ( $OppQuery->have_posts () ) {
									$OppQuery->the_post ();
									?>
									<article id="post-<?php the_ID(); ?>" <?php post_class( 'et_pb_post' ); ?>>
									<?php 
									get_template_part ( 'includes/summary', 'opportunity' );
									?>
									</article>
									<?php 
								}
							} else {
								?>
							<div class="entry">
								<!--If no results are found-->
								<p><?php esc_html_e('No Opportunity Found','Divi'); ?></p>
							</div>
											<?php	} ?>
							
							<!-- End Opportunities list -->
						</div>
					</div>
				</article>
					<?php
					if (et_get_option ( 'divi_integration_single_bottom' ) != '' && et_get_option ( 'divi_integrate_singlebottom_enable' ) == 'on')
						echo (et_get_option ( 'divi_integration_single_bottom' ));
					?>
			</div> <!-- col 3/4-->
		</div> <!-- #content-area -->
	</div> <!-- .container -->
</div> <!-- #main-content -->

<?php get_footer(); ?>