<?php
/*
Plugin Name: Genesis Custom Backgrounds
Plugin URI: http://www.wpsmith.net/genesis-custom-backgrounds
Description: The first generation of this plugin will set a default image for post thumbnails for the Genesis framework.
Version: 0.3
Author: Travis Smith
Author URI: http://www.wpsmith.net/
License: GPLv2

    Copyright YEAR  PLUGIN_AUTHOR_NAME  (email : PLUGIN AUTHOR EMAIL)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * The translation domain for __() and _e().
 */
define( 'GCB_DOMAIN' , 'genesis-custom-backgrounds' );
define( 'GCB_SETTINGS_FIELD', 'gcb-settings' );

/**
 * Directory Path and URLs
 */
define( 'GCB_PLUGIN_DIR', dirname( __FILE__ ) );
define( 'GCB_URL' , WP_PLUGIN_URL . '/' . str_replace( basename( __FILE__ ) , "" , plugin_basename( __FILE__ ) ) );
define( 'GCB_BG_DIR', GCB_PLUGIN_DIR . '/lib/bg/' );
define( 'GCB_BG_URL' , GCB_URL . 'lib/bg/' );
define( 'GCB_LIGHT_URL' , GCB_BG_DIR . 'light/' );
define( 'GCB_DARK_URL' , GCB_BG_DIR . 'dark/' );

/* Prevent direct access to the plugin */
if ( !defined( 'ABSPATH' ) ) {
    wp_die( __( "Sorry, you are not allowed to access this page directly.", GCB_DOMAIN ) );
}

register_activation_hook( __FILE__, 'gcb_activation_check' );

/**
 * Checks for minimum Genesis Theme version before allowing plugin to activate
 *
 * @author Nathan Rice
 * @uses gfi_truncate()
 * @since 0.1
 * @version 0.2
 */
function gcb_activation_check() {

    $latest = '1.7';

    $theme_info = get_theme_data( TEMPLATEPATH . '/style.css' );

    if ( basename( TEMPLATEPATH ) != 'genesis' ) {
        deactivate_plugins( plugin_basename( __FILE__ ) ); // Deactivate ourself
        wp_die( sprintf( __( 'Sorry, you can\'t activate unless you have installed and actived %1$sGenesis%2$s or a %3$sGenesis Child Theme%2$s', GCB_DOMAIN ), '<a href="http://wpsmith.net/go/genesis">', '</a>', '<a href="http://wpsmith.net/go/spthemes">' ) );
    }
	
	$theme_info = get_theme_data(TEMPLATEPATH.'/style.css');
	
	if( basename( TEMPLATEPATH ) != 'genesis' ) {
		deactivate_plugins(plugin_basename(__FILE__)); // Deactivate ourself
		wp_die('Sorry, you can\'t activate unless you have installed <a href="http://wpsmith.net/go/genesis">Genesis</a>');
	}

	if ( version_compare( $theme_info['Version'], $latest, '<' ) ) {
		deactivate_plugins(plugin_basename(__FILE__)); // Deactivate ourself
		wp_die( sprintf( __( 'Sorry, you can\'t activate without %1$sGenesis %2$s%3$s or greater', GCB_DOMAIN ), '<a href="http://wpsmith.net/go/genesis">', $latest, '</a>' ) );
	}
}


add_action( 'genesis_init', 'gcb_init', 15 );
/** Loads required files when needed */
function gcb_init() {

	if ( is_admin() ) {
		require_once( GCB_PLUGIN_DIR . '/lib/admin/gcb-settings.php');
		require_once( GCB_PLUGIN_DIR . '/lib/admin/gcb-help.php');
	}
	require_once( GCB_PLUGIN_DIR . '/lib/gcb-functions.php');
}

/**
 * This registers the settings field and adds defaults to the options table
 */
add_action('admin_init', 'gcb_register_settings');
function gcb_register_settings() {
	register_setting( GCB_SETTINGS_FIELD , GCB_SETTINGS_FIELD );
	add_option( GCB_SETTINGS_FIELD , gcb_settings_defaults() );
}

function gcb_settings_defaults() {
	return array( 'cb_default' => '' );
}

?>
