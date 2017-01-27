<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
global $wpdb;
$table_name = $wpdb->prefix . "osc_locations";



	$id = $_GET['id'];
	$result = $wpdb->get_row( "SELECT * FROM  ".$table_name." WHERE location_id = ".$id );


if(isset($_POST['zmap-save']) && $_POST['zmap-save']!='')
	{

		if ( !current_user_can( apply_filters( 'fmegooglemaps_capability', 'manage_options' ) ) )
			die( '-1' );
	
		check_admin_referer( 'fmagooglemaps_nonce_action', 'fmagooglemaps_nonce_field' );
	
	$address = sanitize_text_field($_POST['marker_address']);
	$address = str_replace(" ", "+", $address);
	$json = file_get_contents("http://maps.google.com/maps/api/geocode/json?address=$address&sensor=false");
    $json = json_decode($json);

    $lat = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
    $long = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
    

	$query="INSERT INTO ".$table_name." SET location_name ='".sanitize_text_field($_POST['title'])."', lat = '".$lat."', lng = '".$long."', address = '".sanitize_text_field($_POST['marker_address'])."', description = '".sanitize_text_field($_POST['description'])."', phone = '".sanitize_text_field($_POST['phone'])."'";
	$wpdb->query($query);
	echo("<script>location.href = 'admin.php?page=zmaps_manage&id=".$wpdb->insert_id."&added=1';</script>");
	exit;
}
if(isset($_POST['zmap-update']) && $_POST['zmap-update']!='')
	{

		if ( !current_user_can( apply_filters( 'fmegooglemaps_capability', 'manage_options' ) ) )
			die( '-1' );
	
		check_admin_referer( 'fmagooglemaps_nonce_action', 'fmagooglemaps_nonce_field' );
		
	$id=intval($_POST['id']);
	
	$address = sanitize_text_field($_POST['marker_address']);
	$address = str_replace(" ", "+", $address);
	$json = file_get_contents("http://maps.google.com/maps/api/geocode/json?address=$address&sensor=false");
    $json = json_decode($json);

    $lat = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
    $long = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};

	$query ="UPDATE  ".$table_name." SET location_name ='".sanitize_text_field($_POST['title'])."', lat = '".$lat."', lng = '".$long."', address = '".sanitize_text_field($_POST['marker_address'])."', description = '".sanitize_text_field($_POST['description'])."', phone = '".sanitize_text_field($_POST['phone'])."' WHERE location_id = ".$id;
	$wpdb->query($query);
	echo("<script>location.href = 'admin.php?page=zmaps_manage&id=".$id."&edited=1';</script>");
	exit;
}




?>

<div class="wrap">
	<h2><?php if($id==""){?>Add Marker<?php }else{ ?>Edit Marker<?php } ?></h2>
	<p>Enter Marker Data below:</p>
	
<form method="post" name="maps_form">
	
	<?php wp_nonce_field('fmagooglemaps_nonce_action','fmagooglemaps_nonce_field'); ?>
	<table class="form-table" id="maps-markers">
		<tr>
			<td valign="top">Address: </td>
			<td><input id="marker_address" name="marker_address" type="text" size="35" maxlength="200" value="<?php echo esc_attr($result->address); ?>" placeholder="Enter a location" autocomplete="off" /></td>
		</tr>

		<tr>
			<td valign="top">Title: </td>
			<td><input  name="title" type="text" size="35" maxlength="200" value="<?php echo esc_attr($result->location_name); ?>" placeholder="Enter a title" autocomplete="off" /></td>
		</tr>

		<tr>
			<td valign="top">Phone: </td>
			<td><input  name="phone" type="text" size="35" maxlength="200" value="<?php echo esc_attr($result->phone); ?>" placeholder="Enter a phone number" autocomplete="off" /></td>
		</tr>

		<tr>
			<td valign="top">Description: </td>
			<td>
				<textarea name="description" placeholder"Enter description" rows="10" cols="70"><?php echo esc_attr($result->description); ?></textarea>
			</td>
		</tr>

	</table>
	
	<p class="submit"><?php if(isset($_GET['id']) && $_GET['id']!=''){ ?>
		<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" id="id" />
		<input type="submit" name="zmap-update" class="button-primary" value="Update Marker" />
		<?php }else { ?>
		<input type="submit" name="zmap-save" class="button-primary" value="Save Marker" />
		<?php } ?></p>	
	
	

</form>
</div>
<script type="text/javascript">
    var httpOrHttps = (("https:" == document.location.protocol) ? "https://" : "http://");
    document.write(unescape("%3Cscript src='" + httpOrHttps + "maps.google.com/maps/api/js?v=3.14&sensor=false&libraries=places' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
	function fillInAddress() {
	  var place = autocomplete.getPlace();
	}
	jQuery(document).ready(function() {
		var geocoder = new google.maps.Geocoder();
			
		if (typeof document.getElementById('marker_address') !== "undefined") {
			/* initialize the autocomplete form */
			autocomplete = new google.maps.places.Autocomplete(
			  /** @type {HTMLInputElement} */(document.getElementById('marker_address')),
			  { types: ['geocode'] });
			google.maps.event.addListener(autocomplete, 'place_changed', function() {
			fillInAddress();
			});
	}
	});
</script>
