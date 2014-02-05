
<?php get_header(); ?>

<?php $et_full_post = 'on' == get_post_meta( get_the_ID(), '_et_full_post', true ) ? true : false; ?>


<div id="main-area">
	<div class="container<?php if ( $et_full_post ) echo ' fullwidth'; ?>">
		<?php get_template_part( 'includes/breadcrumbs', 'single' ); ?>

		<div id="content" class="clearfix">
			<div id="left-area">
				<?php if (et_get_option('explorable_integration_single_top') <> '' && et_get_option('explorable_integrate_singletop_enable') == 'on') echo (et_get_option('explorable_integration_single_top')); ?>

				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'content', get_post_format() ); ?>

					<?php
						if ( comments_open() && 'on' == et_get_option( 'explorable_show_postcomments', 'on' ) )
							comments_template( '', true );
					?>
                <p> Welcome to AV Opportunity</p>
				<?php echo the_meta();?>
<?php $post->ID = 104;?>
<?php echo esc_html(get_post_meta($post->ID, 'afternoontimings', true));?>
<?php echo esc_html(get_post_meta($post->ID, 'architect_semester', true));?>
<?php echo esc_html(get_post_meta($post->ID, 'av_unit', true));?>
<?php echo esc_html(get_post_meta($post->ID, 'computerrequired', true));?>
<?php echo esc_html(get_post_meta($post->ID, 'contactEmail', true));?>
<?php echo esc_html(get_post_meta($post->ID, 'contactPerson', true));?>
<?php echo esc_html(get_post_meta($post->ID, 'contactPhone', true));?>
<?php echo esc_html(get_post_meta($post->ID, 'duration', true));?>
<?php echo esc_html(get_post_meta($post->ID, 'enddate', true));?>
<?php echo esc_html(get_post_meta($post->ID, 'morningtimings', true));?>
<?php echo esc_html(get_post_meta($post->ID, 'number_of_volunteers', true));?>
<?php echo esc_html(get_post_meta($post->ID, 'projectname', true));?>
<?php echo esc_html(get_post_meta($post->ID, 'revisions', true));?>
<?php echo esc_html(get_post_meta($post->ID, 'startdate', true));?>
<?php echo esc_html(get_post_meta($post->ID, 'timing', true));?>

                <p> Bye to AV Opportunity</p>				
				
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
			</div> <!-- end #left-area -->

			<?php if ( ! $et_full_post ) get_sidebar(); ?>
		</div> <!-- end #content -->
	</div> <!-- end .container -->
</div> <!-- end #main-area -->

<?php get_footer(); ?>