<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
global $wpdb;
$sql = "DROP TABLE " . $wpdb->prefix .'osc_locations';
$wpdb->query($sql);

$sql2 = "DROP TABLE " . $wpdb->prefix .'zmap_settings';
$wpdb->query($sql2);
?>
