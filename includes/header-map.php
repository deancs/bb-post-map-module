<?php 
wp_enqueue_script('rdp-google-maps-api');
wp_enqueue_script('rdp-goolge-maps-spidify');
$locarray = array();
?>

<script>

      var map_<?php echo $id; ?>;
      //var bounds_<?php echo $id; ?> = new google.maps.LatLngBounds();
      var bounds_<?php echo $id; ?>; 
      var mapMarkers = [];
      var markers = [];
      var oms_<?php echo $id; ?>;

      function initMap() {
		var gm = google.maps;
        	//map = new google.maps.Map(jQuery('map-<?php echo $id; ?>')[0], {
		bounds_<?php echo $id; ?> = new google.maps.LatLngBounds();

		var map_center = new gm.LatLng(-36.0,148.0);
	        map_<?php echo $id; ?> = new gm.Map(document.getElementById('map-<?php echo $id; ?>'), {
				center: map_center,
				scaleControl: false,
				scrollwheel: false,
				streetViewControl: false,
				rotateControl: false,
				mapTypeControl: true,
				mapTypeControlOptions: {
				  style: google.maps.MapTypeControlStyle.DROPDOWN_MENU,
				  mapTypeIds: ['roadmap', 'terrain','satellite','hybrid']
				},
				mapTypeId: '<?php echo $settings->map_type;?>'
        	});
		//oms_<?php echo $id; ?> = new OverlappingMarkerSpiderfier(map_<?php echo $id; ?> );
		mapMarkers.forEach(function(markerObj) { addLocation(markerObj);});

		scaleToBound();

		<?php if ($settings->map_zoom != "auto") :?>

 		zoomChangeBoundsListener = google.maps.event.addListenerOnce(map_<?php echo $id; ?>, 'bounds_changed', function(event) {
        		if (this.getZoom()){
	            		this.setZoom(<?php echo $settings->map_zoom;?>);
        		}
        	});

		//setTimeout(function(){google.maps.event.removeListener(zoomChangeBoundsListener)}, 2000);
		<?php endif; ?>

		var markersOptions = {
			imagePath: '<?php echo RDP_MAP_LISTING_URL;?>'+'images/m'
			//imagePath: '<?php echo plugin_dir_url(__FILE__);?>'+'images/m'
		};

		<?php if ($settings->map_clusters == "true") :?>
		var markerCluster = new MarkerClusterer(map_<?php echo $id; ?>, markers, markersOptions);
		<?php endif; ?>

	}

	function addLocation(markerObj) {
		loc = new google.maps.LatLng(parseFloat(markerObj['lat']),parseFloat(markerObj['lng']));
		bounds_<?php echo $id; ?>.extend(loc);
		//addMarker(loc,markerObj['icon']);
		addMarker(loc,markerObj);
	}

        function addMarker(location,markerObj) {

	        	var svgicon = {
		        	path: "M156.831,70.804c0,13.473-10.904,24.396-24.357,24.396c-13.434,0-24.357-10.923-24.357-24.396 c0-13.434,10.904-24.337,24.357-24.337C145.927,46.467,156.831,57.37,156.831,70.804z M203.298,70.795 c0,8.764-1.661,17.098-4.563,24.836c-9.282,27.571-70.736,169.307-70.736,169.307S70.14,110.403,65.118,92.68 c-2.237-6.868-3.478-14.196-3.478-21.866C61.64,31.743,93.354,0,132.474,0C171.593-0.01,203.307,31.733,203.298,70.795z M177.661,71.078c0-24.953-20.214-45.197-45.187-45.197c-24.953,0-45.177,20.234-45.177,45.187s20.224,45.187,45.177,45.187 C157.446,116.255,177.661,96.031,177.661,71.078z",
				fillColor: '#<?php echo $settings->map_pipcolor;?>',
		        	fillOpacity: 1,
	        		anchor: new google.maps.Point(120,300),
				strokeWeight: 0,
		        	scale: 0.16
    			}

		        var iconBase = 'https://maps.google.com/mapfiles/kml/shapes/';
		        var icons = {
				parking: {
					icon: iconBase + 'parking_lot_maps.png'
			        },
				library: {
					icon: iconBase + 'library_maps.png'
				},
				info: {
					icon: iconBase + 'info-i_maps.png'
				},
				svg: {
					icon: svgicon
				},
 			};

			var contentString = '<div class="popup">'+
				  '<div class="container">'+
				  '<div class="featured-image"><img src="'+markerObj['img']+'"></div>'+
				  '<div class="description"><h3>'+markerObj['title']+'</h3><a class="rdp-button" href="'+markerObj['url']+'">MORE</a></div>'+
				  '</div>'+
				  '</div>';

			var infowindow = new google.maps.InfoWindow({
				content: contentString,
				maxWidth: 200
			});

			var marker = new google.maps.Marker({
				position: location,
				icon: icons[markerObj['icon']].icon,
				animation: google.maps.Animation.DROP,
				title: markerObj['title'],
				map: map_<?php echo $id; ?>
			});

/*
			var infowindow = new google.maps.InfoWindow();
			oms_<?php echo $id; ?>.addListener('click', function(marker, event) {
				infowindow.setContent(contentString);
				infowindow.open(map_<?php echo $id; ?>, marker);
			});

			oms_<?php echo $id; ?>.addMarker(marker);
*/
			markers.push(marker);



			marker.addListener('click', function() {
				infowindow.open(map_<?php echo $id; ?>, marker);
			});

        }

		function scaleToBound() {
			map_<?php echo $id;?>.fitBounds(bounds_<?php echo $id; ?>);
			map_<?php echo $id;?>.panToBounds(bounds_<?php echo $id; ?>);
		}

	document.addEventListener("DOMContentLoaded", function () {initMap();}, false);


</script>

