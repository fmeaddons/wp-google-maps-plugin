<?php
/**
 * Plugin Name:       FMA Google Maps
 * Plugin URI:        http://fmeaddons.com/wordpress/zgoogle-maps
 * Description:       Add google map and place your address with markers on that.
 * Version:           1.0.0
 * Author:            FME Addons
 * Developed By:      Hanan Ali, Raja Usman Mehmood
 * Author URI:        http://fmeaddons.com/
 * Support:           http://support.fmeaddons.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
if (!defined("ZMAPS_PLUGIN_DIR")) define("ZMAPS_PLUGIN_DIR",  plugin_dir_path( __FILE__ ));
include ZMAPS_PLUGIN_DIR . 'includes/zfunctions.php';

function zmaps_manage() {
	include ZMAPS_PLUGIN_DIR . 'admin/zmaps_manage.php';
}

function zmaps_add(){
	
	include ZMAPS_PLUGIN_DIR . 'admin/zmaps_add.php';
	
}	

function map_setting(){
	
	include ZMAPS_PLUGIN_DIR . 'admin/zmap_setting.php';
	
}	
function zmaps_admin_menu_actions(){  	
	add_menu_page('Z Google Maps', 'Z Google Maps', 'read', 'zmaps_manage','', '');
	add_submenu_page( 'zmaps_manage', 'Manange Markers', 'Manange Markers', 'read', 'zmaps_manage','zmaps_manage','');
	add_submenu_page( 'zmaps_manage', 'Add Marker', 'Add Marker', 'read', 'zmaps_add','zmaps_add','');
	add_submenu_page( 'zmaps_manage', 'Map Settings', 'Map Settings', 'read', 'map_setting','map_setting','');
}

function zmaps_create_installation_tables(){
	include ZMAPS_PLUGIN_DIR . "/admin/create-db.php";
}

function zmaps_drop_installation_tables(){
	include ZMAPS_PLUGIN_DIR . "/admin/drop-db.php";
}

function zmaps_shortcodeplace( $arguments = array() ) {
	include ZMAPS_PLUGIN_DIR . '/front/show_map.php';
}

function zmaps_front_css_loads()
{
    wp_enqueue_style("z-googlemaps.css", plugins_url("z-googlemaps/css/z-googlemaps.css",dirname(__FILE__)));
}

function zmaps_admin_css_loads(){
	wp_enqueue_style( 'zgooglemaps.css', plugins_url("z-googlemaps/css/jquery-ui.css",dirname(__FILE__)) );
}

function zmaps_admin_js_loads(){
    wp_enqueue_script("jquery");
	wp_enqueue_script('jquery-ui-tabs');
	wp_enqueue_script('jquery-ui-slider');
}



register_activation_hook(__FILE__, "zmaps_create_installation_tables");
register_uninstall_hook(__FILE__, "zmaps_drop_installation_tables");

add_shortcode('osc-locations','zmaps_shortcodeplace');
add_action('admin_menu', 'zmaps_admin_menu_actions');
add_action("wp_enqueue_scripts", "zmaps_front_css_loads",22);
add_action("admin_init", "zmaps_admin_css_loads");
add_action("admin_init", "zmaps_admin_js_loads");
?>
