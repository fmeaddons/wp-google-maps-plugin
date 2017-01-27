<?php if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
} ?>


<?php 
	global $wpdb;
	$zmap_settings = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix. "zgmap_settings");
	$zsettings = array();
	foreach($zmap_settings as $zmap_setting){
		$zsettings[$zmap_setting->setting_key] = $zmap_setting->setting_value;
	}

?>


<div id="zmap_wrapper">
	
	<h2><?php echo $zsettings['map_title']; ?></h2>
	<div id="zmap_holder" style="width:<?php echo $zsettings['width']; ?><?php echo $zsettings['width_unit']; ?>; height:<?php echo $zsettings['height']; ?>px;"></div>
</div>
<input type="hidden" name="latlan" id="latlan" value="37.0625,-95.677068" />

<script language="javascript">

		<?php

		$markers = json_encode(zmaps_markers_list());
		if (isset($markers) && strlen($markers) > 0 && $markers != "[]"){ ?>var db_marker_array = JSON.stringify(<?php echo $markers; ?>);<?php } else { echo "var db_marker_array = '';"; }
		$zoom = 5;
		if($zsettings['zoom']!='') {
			$zoom = $zsettings['zoom'];
		}

		$mtype = strtoupper($zsettings['map_type']);
		?>
		var zmaps = {
            map: null,
            bounds: null
        }
        zmaps.init = function(selector, latLng) { 
            var myOptions = {
                zoom:<?php echo $zoom; ?>,
                center: latLng,
                zoomControl: true,
                panControl: true,
                mapTypeControl: true,
                draggable: true,
                disableDoubleClickZoom: false,
                scrollwheel: true,
                streetViewControl: false,
                mapTypeId: google.maps.MapTypeId.<?php echo $mtype; ?>
            }
            this.map = new google.maps.Map(jQuery(selector)[0], myOptions);
            this.bounds = new google.maps.LatLngBounds();
            var infoWindows = new google.maps.InfoWindow();
            infoWindows.close();

            <?php if($zsettings['traffic'] == 'yes') { ?>
            	var trafficLayer = new google.maps.TrafficLayer();
  				trafficLayer.setMap(this.map);
  			<?php } ?>

  			<?php if($zsettings['bicycle'] == 'yes') { ?>
  				var bikeLayer = new google.maps.BicyclingLayer();
  				bikeLayer.setMap(this.map);
  			<?php } ?>

  			<?php if($zsettings['public_transport'] == 'yes') { ?>
  				var transitLayer = new google.maps.TransitLayer();
  				transitLayer.setMap(this.map);
  			<?php } ?>

			
			
			google.maps.event.addListener(zmaps.map, 'click', function() {
                infoWindows.close();
            });
            
			

			
			
			zmaps.placeMarkers = function() {



				marker_array = [];
					
					var iconBase = '<?php echo plugins_url("images/marker1.png" ,dirname(__FILE__)) ?>';
					if (db_marker_array.length > 0) {
					var dec_marker_array = jQuery.parseJSON(db_marker_array);
					jQuery.each(dec_marker_array, function(i, val) {
		
					
						
						var title = val.title;
						var phone = val.phone;
						var desc = val.description;
						var address = val.address;
						var lat = val.lat;
						var lng = val.lng;
						var point = new google.maps.LatLng(parseFloat(lat),parseFloat(lng));
						zmaps.bounds.extend(point);
						var marker = new google.maps.Marker({
								position: point,
								map: zmaps.map,
								icon: iconBase

						});

						
						google.maps.event.addListener(zmaps.map, 'idle', function() {
			                infoWindows.close();
			            });
	
						var html = '';
						html += '<div style="width:250px;">';
							html += '<p style="font-weight:bold; margin:10px 0px;">'+title+'</p>';
							html += '<p style="font-weight:normal">'+desc+'</p>';
							html += '<p style="font-weight:normal"><b>Address: </b>'+address+'</p>';
							html += '<p style="font-weight:normal"><b>Phone: </b>'+phone+'</p>';
						html += '</div>';
						
						infoWindows.close();
						infoWindows.setContent(html);
						infoWindows.open(zmaps.map, marker);

						
						
						google.maps.event.addListener(marker, 'click', function() {
							infoWindows.close();
							infoWindows.setContent(html);
							infoWindows.open(zmaps.map, marker);
	
						});
						
					});
				}
			}
        }
    </script>
	<script type="text/javascript">
        var httpOrHttps = (("https:" == document.location.protocol) ? "https://" : "http://");
        document.write(unescape("%3Cscript src='" + httpOrHttps + "maps.google.com/maps/api/js?v=3.14&sensor=false&libraries=places' type='text/javascript'%3E%3C/script%3E"));
    </script>
    <script type="text/javascript">

    jQuery(document).ready(function() {
	
	<?php
		
		$lat = 37.0625;
		$lan = -95.677068;
		
	?>
	/*Map Initialize*/
	function zmaps_init() {
		var zLatLang = new google.maps.LatLng(<?php echo $lat; ?>,<?php echo $lan; ?>);
		zmaps.init('#zmap_holder', zLatLang);
		zmaps.placeMarkers();
	}
	var geocoder = new google.maps.Geocoder();
	zmaps_init();
});

    </script>
