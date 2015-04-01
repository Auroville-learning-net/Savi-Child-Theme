<?php
$query = new WP_Query(array('post_type'=>'av_unit','posts_per_page'=>'-1'));
$center_lat = '12.00696313124193';
$center_lng='79.81026957401127';
$i = 0;

$shortcode ='[google-map-v3 shortcodeid="TO_BE_GENERATED" width="800" height="800" zoom="12" maptype="hybrid" mapalign="center" directionhint="false" language="default" poweredby="false" maptypecontrol="false" pancontrol="false" zoomcontrol="true" scalecontrol="true" streetviewcontrol="true" scrollwheelcontrol="true" draggable="true" tiltfourtyfive="false" enablegeolocationmarker="true" enablemarkerclustering="true" addmarkermashup="false" addmarkermashupbubble="true" addmarkerlist="';
while ( $query->have_posts() ) { 
	$query->the_post();
	$et_location_lat = get_post_meta( get_the_ID(), '_et_listing_lat', true );
	$et_location_lng = get_post_meta( get_the_ID(), '_et_listing_lng', true );
	if ( '' == $et_location_lat or '' == $et_location_lng ){    
        $et_location_lat = '12.00696313124193';
		$et_location_lng='79.81026957401127';		
	}
	if($i>0) $shortcode = $shortcode.'|'.$et_location_lat.','.$et_location_lng.'{}red-marker.png';
	else $shortcode = $shortcode.$et_location_lat.','.$et_location_lng.'{}red-marker.png';
	
	$i++;
}
$shortcode = $shortcode.'" bubbleautopan="true" distanceunits="miles" showbike="false" showtraffic="false" showpanoramio="false"]';
 echo do_shortcode($shortcode);


?>