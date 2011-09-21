<?php

/*
  Plugin Name: Genesis Nav Menu Amplified
  Plugin URI: http://DesignsByNicktheGeek.com
  Version: 0.4
  Author: Nick_theGeek
  Author URI: http://DesignsByNicktheGeek.com
  Description: Restores the traditional Genesis Menu with options for the Secondary Menu to have navigation extras
 */

/*
 * To Do:
 *      Create and setup screen shots
 */


define( 'GNMA_PLUGIN_DIR', dirname( __FILE__ ) );
define( 'GNMA_TEXTDOMAIN', 'GNMA' );

/* Prevent direct access to the plugin */
if ( !defined( 'ABSPATH' ) ) {
    wp_die( __( "Sorry, you are not allowed to access this page directly.", GNMA_TEXTDOMAIN ) );
}

register_activation_hook( __FILE__, 'gnma_activation_check' );

/**
 * Checks for minimum Genesis Theme version before allowing plugin to activate
 *
 * @author Nathan Rice
 * @uses gnma_truncate()
 * @since 0.1
 * @version 0.2
 */
function gnma_activation_check() {

    $latest = '1.7';

    $theme_info = get_theme_data( TEMPLATEPATH . '/style.css' );

    if ( basename( TEMPLATEPATH ) != 'genesis' ) {
        deactivate_plugins( plugin_basename( __FILE__ ) ); // Deactivate ourself
        wp_die( sprintf( __( 'Sorry, you can\'t activate unless you have installed %1$sGenesis%2$s', GNMA_TEXTDOMAIN ), '<a href="http://designsbynickthegeek.com/go/genesis">', '</a>' ) );
    }

    $version = gnma_truncate( $theme_info['Version'], 3 );

    if ( version_compare( $version, $latest, '<' ) ) {
        deactivate_plugins( plugin_basename( __FILE__ ) ); // Deactivate ourself
        wp_die( sprintf( __( 'Sorry, you can\'t activate without %1$sGenesis %2$s%3$s or greater', GNMA_TEXTDOMAIN ), '<a href="http://designsbynickthegeek.com/go/genesis">', $latest, '</a>' ) );
    }
}

/**
 *
 * Used to cutoff a string to a set length if it exceeds the specified length
 *
 * @author Nick Croft
 * @since 0.1
 * @version 0.2
 * @param string $str Any string that might need to be shortened
 * @param string $length Any whole integer
 * @return string
 */
function gnma_truncate( $str, $length=10 ) {

    if ( strlen( $str ) > $length ) {
        return substr( $str, 0, $length );
    } else {
        $res = $str;
    }

    return $res;
}

add_action( 'genesis_init', 'gnma_init', 15 );
/** Loads required files when needed */
function gnma_init() {

    if ( is_admin ( ) )
        require_once(GNMA_PLUGIN_DIR . '/admin.php');

    else
        require_once(GNMA_PLUGIN_DIR . '/structure.php');

}
