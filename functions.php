<?php
/* Disable WordPress Admin Bar for all users but admins. */
add_filter('show_admin_bar', '__return_false');

function savi_header_scripts(){
	$template_dir = get_stylesheet_directory_uri();
	//wp_enqueue_script( 'google-maps-api', 'http://maps.google.com/maps/api/js?sensor=false', array( 'jquery' ), '1.0', false );
	//wp_enqueue_script( 'gmap3', $template_dir . '/js/gmap3.min.js', array( 'jquery' ), '1.0', false );
}
add_action( 'wp_enqueue_scripts', 'savi_header_scripts' );

function savi_header_styles(){
	$template_dir = get_stylesheet_directory_uri();
	//wp_enqueue_style( 'av_unit-map',$template_dir . '/css/av_unit-map.css' , array(), null );
}
add_action( 'wp_enqueue_scripts', 'savi_header_styles' );




function nav_items( $items, $menu, $args ) 
{
    foreach( $items as $item ) 
    {
        if( 'Work Area (Units)' == $item->post_title)
            $wtParent = $item->ID;
    }
	//echo "Parent".$wtParent;
	foreach( $items as $item ) 
    {
        if( $wtParent == $item->menu_item_parent)
            $item->url .= '?postType=av_unit';
    }
    return $items;
}
add_filter( 'wp_get_nav_menu_items','nav_items', 11, 3 );

function custom_post_editor_content( $content, $post ) {
    //ob_start();
    switch( $post->post_type ) {
        case 'av_unit':
            $file = get_stylesheet_directory()."/html/content_unit.html";
            //include $file;
            //$content =ob_get_contents();
            $content = file_get_contents($file);
        break;
        case 'av_project':
            $file = get_stylesheet_directory()."/html/content_project.html";
            $content = file_get_contents($file);
        break;
        case 'av_opportunity':
            $file = get_stylesheet_directory()."/html/content_opportunity.html";
            $content = file_get_contents($file);
        break;
        default:
            $content = '';
        break;
    }
    //ob_end_clean();
    return $content;
}
add_filter( 'default_content', 'custom_post_editor_content', 10, 2 );
?>