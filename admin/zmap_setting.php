<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
global $wpdb;
$zgallery_settings = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix. "zgmap_settings");
$zsettings = array();
foreach($zgallery_settings as $zgallery_setting){
	$zsettings[$zgallery_setting->setting_key] = $zgallery_setting->setting_value;
}

if(isset($_POST['submit'])) {
	foreach($_POST as $key=>$value){
		$wpdb->query("Update " . $wpdb->prefix . "zgmap_settings set setting_value='".sanitize_text_field($value)."' where setting_key='".sanitize_text_field($key)."';");
	}
}


?>
<h1><?php _e('Z Google Map Settings', 'wordpress'); ?></h1>
<p><?php _e('Enter your settings below:', 'wordpress'); ?></p>

<form method="post" action="" accept-charset="utf-8">
	<table class="form-table">
		<tbody>
			<tr>
                <th scope="row">
                    <?php _e('Map Title:','wordpress'); ?>
                    <p class="description">(<?php _e('This title will be shown on frontend.', 'wordpress'); ?>)</p>
                </th>
                <td>
                    <input type="text" class="regular-text" value="<?php echo $zsettings['map_title']; ?>" name="map_title" placeholder="<?php _e( 'Google Map', 'wordpress' ); ?>"  />

                </td>
            </tr>

            <tr>
                <th scope="row">
                    <?php _e('Map Type:','wordpress'); ?>
                    <p class="description">(<?php _e('This is type of map that you want to show on frontend.', 'wordpress'); ?>)</p>
                </th>
                <td>
                    
                    <select id="map_type" name="map_type">
                        <option value="roadmap" <?php selected('roadmap', $zsettings['map_type']); ?>>Roadmap</option>
                        <option value="satellite" <?php selected('satellite', $zsettings['map_type']); ?>>Satellite</option>
                        <option value="hybrid" <?php selected('hybrid', $zsettings['map_type']); ?>>Hybrid</option>
                        <option value="terrain" <?php selected('terrain', $zsettings['map_type']); ?>>Terrain</option>
                    </select>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <?php _e('Width:','wordpress'); ?>
                    <p class="description">(<?php _e('Width of map on frontend. 100% width means responsive map.', 'wordpress'); ?>)</p>
                </th>
                <td>
                    <input type="number" min="0" class="regular-text" value="<?php echo $zsettings['width']; ?>" name="width" placeholder="<?php _e( '100', 'wordpress' ); ?>"  />
            
                    <select id="width_unit" name="width_unit">
                        <option value="px"<?php selected('px', $zsettings['width_unit']); ?>>px</option>
                        <option value="%"<?php selected('%', $zsettings['width_unit']); ?>>%</option>
                    </select>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <?php _e('Height:','wordpress'); ?>
                    <p class="description">(<?php _e('Height of map on frontend.', 'wordpress'); ?>)</p>
                </th>
                <td>
                    <input type="number" min="0" class="regular-text" value="<?php echo $zsettings['height']; ?>" name="height" placeholder="<?php _e( '500', 'wordpress' ); ?>"  />
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <?php _e('Zoom:','wordpress'); ?>
                    <p class="description">(<?php _e('Zoom of map on frontend.', 'wordpress'); ?>)</p>
                </th>
                <td>
                    <input type="number" min="0" class="regular-text" value="<?php echo $zsettings['zoom']; ?>" name="zoom" placeholder="<?php _e( '8', 'wordpress' ); ?>"  />
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <?php _e('Show Bycycle Layer?:','wordpress'); ?>
                    <p class="description">(<?php _e('Show bycycle layer on map.', 'wordpress'); ?>)</p>
                </th>
                <td>
                    
                    <select id="bicycle" name="bicycle">
                        <option value="yes" <?php selected('yes', $zsettings['bicycle']); ?>>Yes</option>
                        <option value="no" <?php selected('no', $zsettings['bicycle']); ?>>No</option>
                    </select>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <?php _e('Show Traffic Layer?:','wordpress'); ?>
                    <p class="description">(<?php _e('Show traffic layer on map.', 'wordpress'); ?>)</p>
                </th>
                <td>
                    
                    <select id="traffic" name="traffic">
                        <option value="yes" <?php selected('yes', $zsettings['traffic']); ?>>Yes</option>
                        <option value="no" <?php selected('no', $zsettings['traffic']); ?>>No</option>
                    </select>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <?php _e('Show Public Transport Layer?:','wordpress'); ?>
                    <p class="description">(<?php _e('Show public transport layer on map.', 'wordpress'); ?>)</p>
                </th>
                <td>
                    
                    <select id="public_transport" name="public_transport">
                        <option value="yes" <?php selected('yes', $zsettings['public_transport']); ?>"">Yes</option>
                        <option value="no" <?php selected('no', $zsettings['public_transport']); ?>>No</option>
                    </select>
                </td>
            </tr>

			

		</tbody>
	</table>
	<p class="submit">
        <input type="submit" value="<?php _e( 'Save Changes', 'wordpress' ); ?>" class="button-primary" name="submit">
    	
    </p>
</form>
