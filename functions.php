<?php
add_filter( 'default_content', 'custom_post_editor_content', 10, 2 );

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
?>