<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
global $wpdb;
$site_url = site_url();
$sql2 = "CREATE TABLE `" . $wpdb->prefix . "osc_locations` (                         
                    `location_id` int(11) NOT NULL auto_increment,          
                    `location_name` varchar(255) default NULL, 
                    `lat` varchar(255) default NULL,              
                    `lng` varchar(255) default NULL,                        
                    `address` text,
                    `description` text,                                         
                    `phone` varchar(255) default NULL,            
                    PRIMARY KEY  (`location_id`)                            
                  ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8";
$wpdb->query($sql2);

$sql = "DROP TABLE IF EXISTS `" . $wpdb->prefix . "zgmap_settings`";
$wpdb->query($sql);

$sql1 = "CREATE TABLE " . $wpdb->prefix . "zgmap_settings (
          `setting_id` int(11) unsigned NOT NULL auto_increment,  
          `setting_key` varchar(100) NOT NULL,                    
          `setting_value` text NOT NULL,                          
          PRIMARY KEY  (`setting_id`)                             
       ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8";
$wpdb->query($sql1);

$settings = array();

$settings["map_title"] = "Google Map";
$settings["map_type"] = "roadmap";
$settings["width"] = 100;
$settings["width_unit"] = "%";
$settings["height"] = 500;
$settings["zoom"] = 8;
$settings["bicycle"] = "yes";
$settings["traffic"] = "yes";
$settings["public_transport"] = "yes";


foreach ($settings as $val => $innerKey)
{
    $wpdb->query
    (
        $wpdb->prepare
        (
            "INSERT INTO " . $wpdb->prefix . "zgmap_settings (setting_key, setting_value) VALUES(%s, %s)",
            $val,
            $innerKey
        )
    );
}

// Creating the pages for the osc-locations

$postH = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'posts WHERE post_content="[osc-locations]"' );
if ( empty( $postH ) ) {

	$osc_locations = 'INSERT INTO ' . $wpdb->prefix . 'posts( `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count` )
					VALUES
					( 1, NOW(), NOW(), "[osc-locations]", "Locations", "", "publish", "open", "open", "", "osc-locations", "", "", "2015-01-10 10:42:06",
					"2015-01-10 10:42:06", "",0, "'.$site_url.'/?page_id=",0, "page", "", 0 )';

	$wpdb->query( $osc_locations );
	$homeId  = $wpdb->get_var( 'SELECT ID FROM ' . $wpdb->prefix . 'posts ORDER BY ID DESC LIMIT 0,1' );
	$homeUpd = 'UPDATE ' . $wpdb->prefix . 'posts SET guid="'.$site_url.'/?page_id='.$homeId.'" WHERE ID="'.$homeId.'"';
	$wpdb->query( $homeUpd );			
}

?>
