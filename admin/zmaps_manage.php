<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
global $wpdb;
$table_name = $wpdb->prefix . "osc_locations";

$locations_data = $wpdb->get_results( "SELECT * FROM  ".$table_name);	
 
if(isset($_GET['act']) && $_GET['act']=='delete')
{
	if ( !current_user_can( apply_filters( 'fmegooglemaps_capability', 'manage_options' ) ) )
			die( '-1' );
			$retrieved_nonce = $_REQUEST['_fmagooglemapswpnonce'];
			if (!wp_verify_nonce($retrieved_nonce, 'delete_my_rec' ) ) die( 'Failed security check' );
     $id = intval($_GET['id']);
	 $result = $wpdb->query( "DELETE  FROM  ".$table_name." where location_id='$id'" );
	 echo("<script>location.href = 'admin.php?page=zmaps_manage&deleted=1';</script>");
	 exit;
}
?>
<style type="text/css">
.form-table th {
    margin-bottom: 9px;
    padding: 15px 10px;
    line-height: 1.3;
    vertical-align: middle;
	font-weight:bold;
}
</style>
<div class="wrap">
	<h2>Manage Markers <a href="admin.php?page=zmaps_add" class="add-new-h2">Add New Marker</a></h2>
	<?php 
	if(isset($_GET['added']) && $_GET['added']==1)
		echo '<div class="updated below-h2"><p>Marker added succesfully!<p></div>';

	if(isset($_GET['edited']) && $_GET['edited']==1)
		echo '<div class="updated below-h2"><p>Marker updated succesfully!<p></div>';

	if(isset($_GET['deleted']) && $_GET['deleted']==1)
		echo '<div class="updated below-h2"><p>Marker deleted succesfully!</p></div>';
	?>
<div id="poststuff">
<table class="form-table">
 <tr>
 	
	<td>
    	<table class="wp-list-table widefat" cellspacing="0">
			<thead style="text-align:center;">
				<tr>
				<th class="manage-column column-name" style="width:5%;">ID</th>
				<th class="manage-column column-name">Marker Title</th>
				<th class="manage-column column-name">Address</th>
				<th class="manage-column column-name">Action</th>
	
				</tr>
			</thead>
	<tbody id="the-list">
	<?php
	if(!empty($locations_data)){	
		$i = 1;
		$my_nonce = wp_create_nonce('delete_my_rec');
		foreach($locations_data as $lo_data) {
		
	?>
 	<tr<?php if($i%2==1){?> class="alternate"<?php } ?>>
		<td><?php echo $i; ?></td>
		<td><?php echo esc_attr($lo_data->location_name); ?></td>
		<td><?php echo esc_attr($lo_data->address); ?></td>
		<td><a href="admin.php?page=zmaps_add&id=<?php echo $lo_data->location_id; ?>">Edit</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="return deleteq(<?php echo $lo_data->location_id; ?>)" href="admin.php?page=zmaps_manage&id=<?php echo $lo_data->location_id; ?>&act=delete&_fmagooglemapswpnonce=<?php echo $my_nonce ?>">Delete</a></td>
    </tr>
	<?php 
		$i++;
		}
	}else{  
	?>
		<tr class="no-items"><td class="colspanchange" colspan="4">No marker found</td></tr>
 	<?php 
	}  
	?>
	</tbody>
 </table>
    </td>
  </tr>
 </table>
</div></div>
 <script>
 function deleteq(id)
{

var r=confirm("Are You Sure You Want To Delete this marker");
if (r==true)
  {
	window.location.href="admin.php?page=zmaps_manage&id="+id+"&act=delete";
  }
else
  {
  return false;
  }
}
 
 </script>
