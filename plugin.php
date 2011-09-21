<?php

/*
 * Handles Genesis Version Test, Sets up text domain, defines constants, and loads init file(s)
 */

/* Prevent direct access to the plugin */
if ( !defined( 'ABSPATH' ) ) {
    wp_die( __( "Sorry, you are not allowed to access this page directly.", 'genesis-simple' ) );
}



/** Define Plugin Wide Constants **/
define( 'GENESIS_SIMPLE_SETTINGS_FIELD', 'genesis_simple_settings' );
define( 'GENESIS_SIMPLE_PLUGIN_DIR', dirname( __FILE__ ) );

// translation support
load_plugin_textdomain( 'genesis-simple', false, '/genesis-simple/languages/' );

register_activation_hook( __FILE__, 'genesis_simple_activation_check' );

/**
 * Checks for minimum Genesis Theme version before allowing plugin to activate
 *
 * @author Nathan Rice
 * @uses gsc_truncate()
 * @since 0.1
 * @version 0.2
 */
function genesis_simple_activation_check() {

    $latest = '1.7';

    $theme_info = get_theme_data( TEMPLATEPATH . '/style.css' );

    if ( basename( TEMPLATEPATH ) != 'genesis' ) {
        deactivate_plugins( plugin_basename( __FILE__ ) ); // Deactivate ourself
        wp_die( sprintf( __( 'Sorry, you can\'t activate unless you have installed %1$sGenesis%2$s', 'genesis-simple' ), '<a href="http://designsbynickthegeek.com/go/genesis">', '</a>' ) );
    }

    $version = gsc_truncate( $theme_info['Version'], 3 );

    if ( version_compare( $version, $latest, '<' ) ) {
        deactivate_plugins( plugin_basename( __FILE__ ) ); // Deactivate ourself
        wp_die( sprintf( __( 'Sorry, you can\'t activate without %1$sGenesis %2$s%3$s or greater', 'genesis-simple' ), '<a href="http://designsbynickthegeek.com/go/genesis">', $latest, '</a>' ) );
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
function genesis_simple_truncate( $str, $length=10 ) {

    if ( strlen( $str ) > $length ) {
        return substr( $str, 0, $length );
    } else {
        $res = $str;
    }

    return $res;
}

add_action( 'genesis_init', 'GenesisSimpleInit', 15 );
/**
 * Loads required files and adds image via Genesis Init Hook
 */
function GenesisSimpleInit() {

        require_once( GENESIS_SIMPLE_PLUGIN_DIR . '/lib/init.php' );

}