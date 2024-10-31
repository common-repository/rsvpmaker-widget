<?php
/*
Plugin Name: RSVPMaker Widget
Plugin URI: http://www.rsvpmaker.com
Description: Fetch and display event listings managed via the RSVPMaker plugin on a remote site. Allows the widget to be implemented independently of the full RSVPMaker plugin. Loads events using a JSON url such as https://rsvpmaker.com/wp-json/rsvpmaker/v1/future or https://rsvpmaker.com/wp-json/rsvpmaker/v1/type/featured
Author: David F. Carr
Author URI: http://www.carrcommunications.com
Version: 1.1
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
add_action('admin_notices','rsvpmakerbyJSONnotice',1);

function rsvpmakerbyJSONnotice() {
    if ( is_plugin_active( 'rsvpmaker/rsvpmaker.php' ) )
    {
        echo '<div class="notice error"><p>Deactivating RSVPMaker via JSON widget plugin - not needed on sites that include RSVPMaker.</p></div>';
        deactivate_plugins('rsvpmaker-by-json-widget/rsvpmaker-by-json-widget.php');
    }
}

if ( !is_plugin_active( 'rsvpmaker/rsvpmaker.php' ) )
   include 'ui.php';
?>
