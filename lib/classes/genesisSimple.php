<?php

/*
 * Loads Default Module for activateing other modules, creates base for extending via modules
 */

/**
 * Default Module
 *
 * @author Nick
 */
class genesisSimple {
    
    var $instance;
    var $modules;

    /**
     * Construct
     */
    function genesisSimple() {
        
        $this->instance =& $this;
        $this->$modules = array (
            'admin_bar_plus'            => 'Admin Bar Plus',
            'custom_background'         => 'Custom Backgrounds',
            'custom_header'             => 'Custom Header',
            'featured_widget_amplified' => 'Featured Widget Amplified',
            'simple_breadcrumbs'        => 'Simple Breadcrumbs',
            'simple_comments'           => 'Simple Comments',
            'simple_edits'              => 'Simple Edits',
            'simple_hooks'              => 'Simple Hooks'
        );
        
        add_action( 'init', array( $this, 'setting_defaults' ) );
        add_action( 'genesis_settings_sanitizer_init', array( $this, 'sanitization' ) );
        
        if( is_admin() ){
            
            add_action( 'init', array( $this, 'admin_menu' ), 50 );
            add_action( 'init', array( $this, 'admin_page' ), 50 );
                       
            add_action( 'init', array( $this, 'admin' ) );

        }
        else {
            add_action( 'init', array( $this, 'output' ) );
        }
        
    }
    
    /**
     * Creates Menus, Meta Boxes and Other Admin options
     */
    function admin() {
        
        add_action( 'genesis_theme_settings_metaboxes', array( $this, 'register_metabox' ) );
        add_filter( 'cmb_meta_boxes', array( $this, 'create_metaboxes' ) );
        add_action( 'init', array( $this, 'initialize_cmb_meta_boxes' ), 50 );
        
    }
    
    /**
     * Handles Front End Output
     */
    function output() {
        
    }
    
    /**
     * Sitewide Setting - Register Defaults
     * @link http://www.billerickson.net/genesis-theme-options/
     *
     * @param array $defaults
     * @return array modified defaults
     *
     */
    function setting_defaults( $defaults ) {

    }
    
    /**
     * Add settings to Genesis sanitization 
     * 
     * Maybe this should be moved to the module class so each module can extend the method with their own options
     *
     */
    function sanitization() {
            genesis_add_option_filter( 'one_zero', GENESIS_SIMPLE_SETTINGS_FIELD,
                    array(

                    ) );
            genesis_add_option_filter( 'no_html', GENESIS_SIMPLE_SETTINGS_FIELD,
                    array(

                    ) );
    }
    
    /**
	 * Sitewide Setting - Register Metabox
	 * @link http://www.billerickson.net/genesis-theme-options/
	 *
	 * @param string, Genesis theme settings page hook
	 */
	
	function register_metabox( $_genesis_theme_settings_pagehook ) {
		add_meta_box( 'be-title-toggle', __( 'Title Toggle', 'genesis-title-toggle' ), array( $this, 'create_sitewide_metabox' ), $_genesis_theme_settings_pagehook, 'main', 'high' );
	}
	
	/**
	 * Sitewide Setting - Create Metabox
	 * @link http://www.billerickson.net/genesis-theme-options/
	 *
	 */
	function create_sitewide_metabox() {
		$post_types = apply_filters( 'be_title_toggle_post_types', array( 'page' ) );
		foreach ( $post_types as $post_type )
			echo '<p><input type="checkbox" name="' . GENESIS_SETTINGS_FIELD . '[be_title_toggle_' . $post_type . ']" id="' . GENESIS_SETTINGS_FIELD . '[be_title_toggle_' . $post_type . ']" value="1" ' . checked( genesis_get_option( 'be_title_toggle_' . $post_type ), false ) .' /> <label for="' . GENESIS_SETTINGS_FIELD . '[be_title_toggle_' . $post_type . ']"> ' . sprintf( __( 'By default, remove titles in the <strong>%s</strong> post type.', 'genesis-title-toggle' ), $post_type ) .'</label></p>';

	
	}
	 
	/**
	 * Create Page Specific Metaboxes
	 * @link http://www.billerickson.net/wordpress-metaboxes/
	 *
	 * @param array $meta_boxes, current metaboxes
	 * @return array $meta_boxes, current + new metaboxes
	 *
	 */
	function create_metaboxes( $meta_boxes ) {
		
		// Get all post types used by plugin and split them up into show and hide.
		// Sitewide default checked = hide by default, so metabox should let you override that and show the title
		// Sitewide default empty = display by default, so metabox should let you override that and hide the title
		
		$show = array();
		$hide = array();
		$post_types = apply_filters( 'be_title_toggle_post_types', array( 'page' ) );
		foreach ( $post_types as $post_type ) {
			$default = genesis_get_option( 'be_title_toggle_' . $post_type );
			if ( ! empty( $default ) ) $show[] = $post_type;
			else $hide[] = $post_type;
		}
		
		
		// Create the show and hide metaboxes that override the default
		
		if ( ! empty( $show ) ) {
			$meta_boxes[] = array(
				'id'         => 'be_title_toggle_show',
				'title'      => __( 'Title Toggle', 'genesis-title-toggle' ),
				'pages'      => $show,
				'context'    => 'normal',
				'priority'   => 'high',
				'show_names' => true,
				'fields'     => array(
					array(
							'name' => __( 'Show Title', 'genesis-title-toggle' ),
							'desc' => __( 'By default, this post type is set to remove titles. This checkbox lets you show this specific page&rsquo;s title', 'genesis-title-toggle' ),
							'id'   => 'be_title_toggle_show',
							'type' => 'checkbox'
					)
				)
			);
		}

		if ( ! empty( $hide ) ) {
			$meta_boxes[] = array(
				'id'         => 'be_title_toggle_hide',
				'title'      => __( 'Title Toggle', 'genesis-title-toggle' ),
				'pages'      => $hide,
				'context'    => 'normal',
				'priority'   => 'high',
				'show_names' => true,
				'fields' => array(
					array(
							'name' => __( 'Hide Title', 'genesis-title-toggle' ),
							'desc' => __( 'By default, this post type is set to display titles. This checkbox lets you hide this specific page&rsquo;s title', 'genesis-title-toggle' ),
							'id'   => 'be_title_toggle_hide',
							'type' => 'checkbox'
					)
				)
			);
		}
		
		return $meta_boxes;
	}

	function initialize_cmb_meta_boxes() {
	    if ( !class_exists('cmb_Meta_Box') ) {
	        require_once( GENESIS_SIMPLE_PLUGIN_DIR . '/lib/classes/cmbMetaBox.php' );
	    }
	}

    /**
     * Get the Genesis Simple options from the database
     * 
     * @param string $key option name/key to retrieve value
     * @return string 
     */
    function get_the_option( $key ) {
            return genesis_get_option( $key, GENESIS_SIMPLE_SETTINGS_FIELD );
    }

    /**
     * Echos Genesis Simple Option
     *
     * @param string $key key value for option
     */
    function the_option( $key ) {

            if ( ! $this->get_the_option( $key ) )
                    return false;

            echo $this->get_the_option( $key );
    }
    
    
}