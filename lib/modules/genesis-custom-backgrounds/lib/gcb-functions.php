<?php


function gcb_setting_url( $setting = '' ){
	if ( $setting == '' )
		$setting = genesis_get_option( 'cb_default' );
	$setting = explode( '-' , $setting );
	return GCB_BG_URL . $setting[0] . "/" . $setting[1] . "/" . $setting[1] . "-" . $setting[2] . '.png';
}

/**
 * Enables Genesis support of custom backgrounds
 *
 * @author	Travis Smith
 *
 * @uses	add_custom_background()	calls standard, filterable callback
 * @uses	apply_filters()			filters callback
 *
 */
function gcb_custom_background() {
	add_custom_background( apply_filters( 'gcb_args' , 'gcb_do_theme_background' ) );	
}
add_action( 'init' , 'gcb_custom_background' );

/**
 * Outputs custom backgrounds inline
 *
 * @author	Travis Smith
 *
 * @uses	$genesis_settings		for custom background default image
 * @uses	apply_filters()			filters defaults
 *
 */
function gcb_do_theme_background() {
	
	$defaults = array(
		'default_img' => genesis_get_option( 'cb_default' , GCB_SETTINGS_FIELD ),
		'bgimage' => get_background_image(),
		'bgcolor' => get_background_color(),
	);
	$defaults = apply_filters( 'gcb_defaults' , $defaults );
	extract( $defaults , EXTR_SKIP );

	// begin output
	$output = "<style type='text/css'>\n";

	if( !empty( $bgimage ) ) {
		$bg_styles = 'background-image: url(\'' . get_theme_mod( 'background_image' , '' ) . '\');'
		. ' background-repeat: ' . get_theme_mod( 'background_repeat' , 'repeat' ) . ';'
		. ' background-position: top ' . get_theme_mod( 'background_position_x' , 'left' ) .  ';' . 'background-attachment: '. get_theme_mod( 'background_attachment' , 'scroll' );
		
		$output .= "body { " . $bg_styles . "; } \n";
	} 
	
	if( !empty( $bgcolor ) ) {
		$output .= "body { background-color: #" . $bgcolor . "; } \n";
	}
	
	// for child themes to set a default bg img
	if( !empty( $default_img ) && empty( $bgimage ) ) {
		$output .= "body { background: url('" . gcb_setting_url( $default_img ) . "'); } \n";
	}
	$output .= "</style>";
	
	echo apply_filters( 'gcb_output' , $output , $bgimage , $bgcolor );
	return $output;
}

