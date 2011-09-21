<?php
//If uninstall not called from WordPress exit
if( !defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit();

$theme_settings = get_option( GCB_SETTINGS_FIELD );

foreach ($theme_settings as $setting => $data) {
	
	if ( $setting == 'cb_default' )
		unset( $theme_settings[$setting] );
		
}

unregister_setting( GCB_SETTINGS_FIELD , GCB_SETTINGS_FIELD );

?>