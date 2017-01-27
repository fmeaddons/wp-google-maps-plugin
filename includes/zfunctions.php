<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
/*Function to return markers*/
function zmaps_markers_list() {
	global $wpdb;
    $table_name = $wpdb->prefix . "osc_locations";
    $sql = "SELECT * FROM $table_name ";
    $markers_result = $wpdb->get_results($sql);
    $markers_array = array();
    $m = 0;
    foreach($markers_result as $result){
		$id = $result->location_id;
		$address = stripslashes($result->address);
		$description = stripslashes($result->description);
		$title = stripslashes($result->location_name);
		$phone = stripslashes($result->phone);
		$lat = $result->lat;
		$lng = $result->lng;
		$markers_array[$m] = array(
			'id' => $id,
			'title' => $title,
			'address' => $address,
			'description' => $description,
			'phone' => $phone,
			'lat' => $lat,
			'lng' => $lng,
		);
		$m++;
    }
  
    return $markers_array;
}






?>
