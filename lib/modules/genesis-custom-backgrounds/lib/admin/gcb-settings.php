<?php

/**
 * This returns an array of directory names.
 *
 * @author Travis Smith
 */
function gcb_dir_list( $d ){ 
	foreach( array_diff( scandir( $d ) , array( '.' , '..' , '.DS_Store' ) ) as $f ) {
		if( is_dir( $d . '/' . $f ) ) {
			$l[]=$f; 
		}
	}
   return $l; 
}

/**
 * This returns an array of file names.
 *
 * @author Travis Smith
 */
function gcb_file_list( $d ){ 
	foreach( array_diff( scandir( $d ) , array( '.' , '..' , '.DS_Store' ) ) as $f ) {
			$l[]=$f;
	}
   return $l; 
}

/**
 * This is a necessary go-between to get our scripts and boxes loaded
 * on the theme settings page only, and not the rest of the admin.
 *
 * @author Travis Smith
 * @global string $_gcb_settings_pagehook value set in this function
 */
add_action('admin_menu', 'gcb_settings_init');
function gcb_settings_init() {
	global $_gcb_settings_pagehook;
        
	// Add "Genesis Backgrounds" submenu
	$_gcb_settings_pagehook = add_submenu_page( 'genesis' , __( 'Genesis Backgrounds' , GCB_DOMAIN ), __( 'Genesis Backgrounds' , GCB_DOMAIN ) , 'manage_options' , 'gcb-settings' , 'gcb_settings_admin' );

	add_action('load-'.$_gcb_settings_pagehook, 'gcb_settings_boxes' );
}

/**
 * This function is what actually gets output to the page. It handles the markup,
 * builds the form, fires do_meta_boxes().
 *
 * @author Travis Smith
 * @global string $_gcb_settings_pagehook
 * @global integer $screen_layout_columns
 * @version 1.0
 */
function gcb_settings_admin() { 
global $_gcb_settings_pagehook, $screen_layout_columns;

$screen_layout_columns = 1;
$width = "width: 99%;";
$hide2 = $hide3 = " display: none;";

?>	
	<div id="gcb-settings" class="wrap gcb-metaboxes">
	<form method="post" action="options.php">
		
		<?php wp_nonce_field( 'closedpostboxes' , 'closedpostboxesnonce' , false ); ?>
		<?php wp_nonce_field( 'meta-box-order' , 'meta-box-order-nonce' , false ); ?>
		<?php settings_fields( GCB_SETTINGS_FIELD ); // important! ?>
		
		<?php screen_icon( 'plugins' ); ?>
		<h2 id="top-buttons">
			<?php _e('Genesis Background Settings', GCB_DOMAIN ); ?>
			<input type="submit" class="button-primary" value="<?php _e('Save Settings', GCB_DOMAIN ) ?>" />
		</h2>
		
		<div class="metabox-holder">
			<div class="postbox-container" style="<?php echo $width; ?>">
				
				<?php do_meta_boxes( $_gcb_settings_pagehook , 'column1' , null ); ?>
				
			</div>
		</div>
		
		<div class="bottom-buttons">
			<input type="submit" class="button-primary" value="<?php _e( 'Save Settings' , GCB_DOMAIN ) ?>" />
		</div>
		<div class="clear" style="margin-top:50px;"></div>
	</form>
	</div>
<?php
}

/**
 * Tell WordPress that we want only 1 column available for our meta-boxes.
 *
 * @author Travis Smith
 * @global string $_gcb_settings_pagehook
 * @param array $columns WordPress array containing column settings for each page.
 * @param string $screen Name of the design settings page.
 * @return array
 */
add_filter( 'screen_layout_columns', 'gcb_settings_layout_columns', 10, 2 );
function gcb_settings_layout_columns($columns, $screen) {
	global $_gcb_settings_pagehook;
	if ( $screen == $_gcb_settings_pagehook ) {
		$columns[$_gcb_settings_pagehook] = 1;
	}
	return $columns;
}

/**
 * Adds all the settngs boxes to the design settings page.
 *
 * @author Travis Smith
 * @global string $_gcb_settings_pagehook
 * @version 1.0
 */
function gcb_settings_boxes() {
	global $_gcb_settings_pagehook;
	add_meta_box( 'gcb-settings' , __( 'Genesis Backgrounds' , GCB_DOMAIN ) , 'gcb_settings' , $_gcb_settings_pagehook , 'column1' );
}

/**
 * Add settings to the Genesis Backgrounds metabox.
 * 
 * @author Travis Smith
 * @version 1.0
 */
function gcb_settings() {
	$setting = genesis_get_option( 'cb_default' , GCB_SETTINGS_FIELD );
	
	$bg_styles = ' background-image: url(\'' . gcb_setting_url( $setting ) . '\');'
					. ' background-repeat: repeat;'
					. ' background-position: top left;'
					. ' width: 100%;'
					. ' float: left;'
					. ' margin: 0 10px 10px 0;';
	?>
	<h4><?php _e( 'Selected Default Background' , GCB_DOMAIN ); ?></h4>
	<div id="custom-background-image" style="<?php echo $bg_styles; ?>"><?php // must be double quote, see above ?>
		<p style="visibility:hidden;"><?php _e( 'default' , GCB_DOMAIN ); ?></p>
		<img class="custom-background-image" src="<?php echo get_theme_mod('background_image_thumb', ''); ?>" style="visibility:hidden;" alt="" />
	</div>
	<div class="clear"></div>
	<?php
	$imagefolders = gcb_dir_list( GCB_BG_DIR );
	
	$lights = gcb_dir_list( GCB_LIGHT_URL );
	foreach( $lights as $key => $folder ) {
		$light[$folder] = gcb_file_list( GCB_BG_DIR . 'light/' . $folder );
	}
	
	$darks = gcb_dir_list( GCB_DARK_URL );
	foreach( $darks as $folder ) {
		$dark[$folder] = gcb_file_list( GCB_BG_DIR . 'dark/' . $folder );
	}
	
	foreach ( $imagefolders as $key => $folder) : ?>
		<h2><?php _e( ucfirst( $folder ) . ' Backgrounds' , GCB_DOMAIN ); ?></h2>
		
		<?php		
		foreach ( ${$folder} as $fkey => $types ) : ?>
			<h4><?php _e( ucfirst( $fkey ) . ' Backgrounds' , GCB_DOMAIN ); ?></h4>
		<?php 
			$i = 0;
			foreach ( ${$folder}[$fkey] as $ikey => $img ) : 
				$i++;
				$bg_styles = '';

				// background-image URL must be single quote, see below
				$bg_styles .= ' background-image: url(\'' . GCB_BG_URL . $folder . '/' . $fkey . '/' . $img . '\');'
					. ' background-repeat: repeat;'
					. ' background-position: top left;'
					. ' width: 48%;'
					. ' float: left;'
					. ' margin: 0 10px 10px 0;';
			
				?>
				<div id="custom-background-image" style="<?php echo $bg_styles; ?>"><?php // must be double quote, see above ?>
					
					<input type="radio" class="cbi-option" id="<?php echo GCB_SETTINGS_FIELD; ?>[cb_default]" name="<?php echo GCB_SETTINGS_FIELD; ?>[cb_default]" 
						value="<?php echo $folder .'-' . $fkey . '-' . $i; ?>" <?php checked( $folder .'-' . $fkey . '-' . $i , $setting ); ?>>
							<p style="visibility:hidden;"><?php _e( ucfirst( $folder ) . ' ' . ucfirst( $fkey ) , GCB_DOMAIN ); ?></p>
					</input>
					<img class="custom-background-image" src="<?php echo get_theme_mod('background_image_thumb', ''); ?>" style="visibility:hidden;" alt="" />
				</div>
				
			<?php
			endforeach;
			?><div class="clear"></div><?php
		endforeach;

	endforeach;
}
