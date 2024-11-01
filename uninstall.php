<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


// if not uninstalled plugin
if( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) 
	exit(); // out.


/*esle:
	if uninstalled plugin, this options will be deleted.
*/
delete_option('obi_the_media_widget_height');

?>