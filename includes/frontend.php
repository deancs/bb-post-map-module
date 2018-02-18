<?php

$mapTag = '<div id="map-'.$id.'" style="height:'.$settings->map_height.'px;width:100%;"></div>';

// Get the query data.
$query = FLBuilderLoop::query($settings);

// Render the posts.
if($query->have_posts()) :

	#include apply_filters( 'fl_builder_posts_module_layout_path', $module->dir . 'includes/header-map.php', $settings->layout );
	include($module->dir . 'includes/header-map.php');
	echo $mapTag;
	while($query->have_posts()) {

		$query->the_post();
		$ptitle = get_the_title();
		$plink = get_permalink();
		$imgurl = get_the_post_thumbnail_url(null,'thumbnail');
		$field = get_field('geo_location');
		if (empty($field)) {
			$f2 = get_field('start_location');
			if (empty($f2)) {
				//Assign location to TNE Beechworth office if not specified.
				$field['lat'] = "-36.360022";
				$field['lng'] = "146.687449";
			} else {
				$field['lat'] = $f2['lat'];
				$field['lng'] = $f2['lng'];
			}
		}
		array_push($locarray,['lat' => $field['lat'],'lng' => $field['lng'],'icon' => 'svg','title' => $ptitle,'url' => $plink,'img' => $imgurl]);

	}
	echo '<script>var mapMarkers = '.json_encode($locarray).';</script>';
	echo '<div class="fl-clear"></div>';
	
endif;

wp_reset_postdata();

?>
