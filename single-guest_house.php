<?php get_header(); ?>
<?php $et_full_post = 'on' == get_post_meta( get_the_ID(), '_et_full_post', true ) ? true : false; ?>
<div id="main-content">
	<div class="et_pb_section et_section_specialty">
		<div class="et_pb_row">
			<div class="et_pb_column et_pb_column_1_4">
					<?php get_sidebar(); ?>
				</div>
			<div class="et_pb_column et_pb_column_3_4">
				<?php if (et_get_option('explorable_integration_single_top') <> '' && et_get_option('explorable_integrate_singletop_enable') == 'on') echo (et_get_option('explorable_integration_single_top')); 
					  while ( have_posts() ) : the_post(); 
						get_template_part( 'content', get_post_format() );  
						if ( comments_open() && 'on' == et_get_option( 'explorable_show_postcomments', 'on' ) )
							comments_template( '', true );
					?>
            				
				
				<?php endwhile; ?>

				<?php if (et_get_option('explorable_integration_single_bottom') <> '' && et_get_option('explorable_integrate_singlebottom_enable') == 'on') echo(et_get_option('explorable_integration_single_bottom')); ?>

				<?php
					if ( et_get_option('explorable_468_enable') == 'on' ){
						if ( et_get_option('explorable_468_adsense') <> '' ) echo( et_get_option('explorable_468_adsense') );
						else { ?>
						   <a href="<?php echo esc_url(et_get_option('explorable_468_url')); ?>"><img src="<?php echo esc_attr(et_get_option('explorable_468_image')); ?>" alt="468 ad" class="foursixeight" /></a>
				<?php 	}
					}
				?>
			</div> <!-- #left-area -->
		</div> <!-- #content-area -->
	</div> <!-- .container -->
</div> <!-- #main-content -->

<?php get_footer(); ?>