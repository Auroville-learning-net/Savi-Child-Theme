<?php get_header(); ?>
<div id="main-content">
	<div class="et_pb_section et_section_specialty">

		<div class="et_pb_row">
			<div class="et_pb_column et_pb_column_1_4">
					<?php get_sidebar(); ?>
				</div>
			<div class="et_pb_column et_pb_column_3_4">
			<?php the_post (); ?>
			<?php
				$unit_title = get_the_title();
				$contactperson = get_post_meta ( get_the_ID (), "unit_name", true );
				$landphone = get_post_meta ( get_the_ID (), "landphone", true );
				$unit_url = get_post_meta ( get_the_ID (), "unit_url", true );
				$unit_address = get_post_meta ( get_the_ID (), "_et_listing_custom_address", true );
			?>
				<?php if (et_get_option('divi_integration_single_bottom') <> '' && et_get_option('divi_integrate_singlebottom_enable') == 'on') echo(et_get_option('divi_integration_single_bottom')); ?>
				<!-- Here the new posting format will come -->
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'et_pb_post' ); ?>>
					<div class="et_pb_row_inner">
						<div class="et_pb_column et_pb_column_4_4 et_pb_column_inner">
							<h1><?php echo $unit_title; ?></h1>
						</div>
					</div>
					<div class="et_pb_row_inner">
						<div class="et_pb_column et_pb_column_1_4 et_pb_column_inner" style="width: 473px">
							<p><?php the_content(); ?></p>
						</div>
						<div class="et_pb_column et_pb_column_1_4 et_pb_column_inner">
							<?php
							$thumb = '';
							$width = ( int ) apply_filters ( 'et_pb_index_blog_image_width', 225 );
							$height = ( int ) apply_filters ( 'et_pb_index_blog_image_height', 155 );
							$classtext = 'et_featured_image';
							$titletext = get_the_title ();
							$thumbnail = get_thumbnail ( $width, $height, $classtext, $titletext, $titletext, false, 'Blogimage' );
							$thumb = $thumbnail ["thumb"];
							if ('on' === et_get_option ( 'divi_thumbnails', 'on' ) && '' !== $thumb)
								print_thumbnail ( $thumb, $thumbnail ["use_timthumb"], $titletext, $width, $height );
							?>
						</div>
					</div>
					<div class="et_pb_row_inner">
						<p>&nbsp;</p>
					</div>
					<div class="et_pb_row_inner">
						<div class="et_pb_column et_pb_column_1_4 et_pb_column_inner">
							<!-- First column -->
							<?php
							if ($contactperson != "") {
								?>
							<p>
								<span class="left_li">Contactperson:</span><?php echo $contactperson; ?>
							</p>
							<?php
							}
							
							if ($landphone != "") {
								?>
							<p>
								<span class="left_li">Landphone:</span><?php echo $landphone; ?>
							</p>
							<?php
							}
							?>
							<p>
								<span class="left_li">Website:</span> <a href="<?php echo $unit_url; ?>" target="_blank"><?php echo $unit_url; ?></a>
							</p>
						</div>
						<div class="et_pb_column et_pb_column_1_4 et_pb_column_inner">
							<p>
								<span class="left_li">Address:</span><br><?php echo $unit_address; ?>
							</p>
						</div>
						<div class="et_pb_column et_pb_column_1_4 et_pb_column_inner"></div>
					</div>
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
			</div>
			<!-- #left-area -->
		</div>
		<!-- #content-area -->
	</div>
	<!-- .container -->
</div>
<!-- #main-content -->
<?php get_footer(); ?>