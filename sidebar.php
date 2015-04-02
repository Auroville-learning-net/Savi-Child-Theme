<?php
if ((is_single () || is_page ()) && 'et_full_width_page' === get_post_meta ( get_the_ID (), '_et_pb_page_layout', true ))
	return false;

if (is_active_sidebar ( 'sidebar-1' )) :?>
	<div class="et_pb_widget_area et_pb_widget_area_left clearfix et_pb_bg_layout_light">
		<?php dynamic_sidebar( 'sidebar-1' ); ?>
	</div>
	<!-- end #sidebar -->
<?php endif; ?>