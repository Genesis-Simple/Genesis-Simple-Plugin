<?php
/**
 * Creates options and outputs admin menu and options page
 */

/**
 * Return the defaults array
 *
 * @since 0.1
 */
function gsb_defaults() {
	return array(
			'home'						=> __( 'Home', 'genesis' ),
			'sep'						=> ' / ',
			'list_sep'					=> ', ',
			'prefix'					=> '<div class="breadcrumb">',
			'suffix'					=> '</div>',
			'heirarchial_attachments'	=> true,
			'heirarchial_categories'	=> true,
			'display'					=> true,
				'label_prefix'	=> __( 'You are here: ', 'genesis' ),
				'author'	=> __( 'Archives for ', 'genesis' ),
				'category'	=> __( 'Archives for ', 'genesis' ),
				'tag'		=> __( 'Archives for ', 'genesis' ),
				'date'		=> __( 'Archives for ', 'genesis' ),
				'search'	=> __( 'Search for ', 'genesis' ),
				'tax'		=> __( 'Archives for ', 'genesis' ),
				'post_type'	=> __( 'Archives for ', 'genesis' ),
				'404'		=> __( 'Not found: ', 'genesis' )
                            
	);

}


add_option(GSB_SETTINGS_FIELD, gsb_defaults(), '', 'yes');

/**
 * This is a necessary go-between to get our scripts and boxes loaded
 * on the theme settings page only, and not the rest of the admin
 */
add_action('admin_menu', 'gsb_settings_init', 15);
function gsb_settings_init() {
	global $_gsb_settings_pagehook;

	// Add "Design Settings" submenu
	$_gsb_settings_pagehook = add_submenu_page('genesis', __('Simple Breadcrumbs','GSB'), __('Simple Breadcrumbs','GSB'), 'manage_options', 'gsb', 'gsb_settings_admin');

	add_action('load-'.$_gsb_settings_pagehook, 'gsb_settings_scripts');
	add_action('load-'.$_gsb_settings_pagehook, 'gsb_settings_boxes');
}

function gsb_settings_scripts() {
	wp_enqueue_script('common');
	wp_enqueue_script('wp-lists');
	wp_enqueue_script('postbox');
}

function gsb_settings_boxes() {
	global $_gsb_settings_pagehook;

	add_meta_box('gsb-defaults', __('Breadcrumb Defaults', 'GSB'), 'gsb_defaults_box', $_gsb_settings_pagehook, 'column1');
}

/**
 * Tell WordPress that we want only 1 column available for our meta-boxes
 */
add_filter('screen_layout_columns', 'gsb_settings_layout_columns', 10, 2);
function gsb_settings_layout_columns($columns, $screen) {
	global $_gsb_settings_pagehook;
	if ($screen == $_gsb_settings_pagehook) {
		// This page should have 3 column options
		$columns[$_gsb_settings_pagehook] = 1;
	}
	return $columns;
}

/**
 * This function is what actually gets output to the page. It handles the markup,
 * builds the form, outputs necessary JS stuff, and fires <code>do_meta_boxes()</code>
 */
function gsb_settings_admin() {
global $_gsb_settings_pagehook, $screen_layout_columns;

	$width = "width: 99%;";
	$hide2 = $hide3 = " display: none;";

?>
	<div id="gsb" class="wrap genesis-metaboxes">
	<form method="post" action="options.php">

		<?php wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false ); ?>
		<?php wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false ); ?>
		<?php settings_fields(GSB_SETTINGS_FIELD); // important! ?>

		<?php screen_icon('plugins'); ?>
		<h2>
			<?php _e('Genesis - Simple Breadcrumbs', 'genesis'); ?>
			<input type="submit" class="button-primary add-new-h2" value="<?php _e('Save Changes', 'genesis') ?>" />
			<?php $reset_onclick = 'onclick="if ( confirm(\'' . esc_js( __('Are you sure you want to reset?', 'genesis') ) . '\') ) {return true;}return false;"'; ?>
			<input type="submit" <?php echo $reset_onclick; ?> class="button-highlighted add-new-h2" name="<?php echo GSB_SETTINGS_FIELD; ?>[reset]" value="<?php _e('Reset All', 'genesis'); ?>" />
		</h2>

		<?php
		if(genesis_get_option('reset', GSB_SETTINGS_FIELD)) {
			update_option(GSB_SETTINGS_FIELD, gsb_defaults());
			echo '<div id="message" class="updated" id="message"><p><strong>'.__('Modifications Reset', 'genesis').'</strong></p></div>';
		}
		elseif( isset($_REQUEST['updated']) && $_REQUEST['updated'] == 'true') {
			echo '<div id="message" class="updated" id="message"><p><strong>'.__('Modifications Saved', 'genesis').'</strong></p></div>';
		}
		?>

		<div class="metabox-holder">
			<div class="postbox-container" style="<?php echo $width; ?>">
				<?php do_meta_boxes($_gsb_settings_pagehook, 'column1', null); ?>
			</div>
		</div>

	</form>
	</div>
	<script type="text/javascript">
		//<![CDATA[
		jQuery(document).ready( function($) {
			// close postboxes that should be closed
			$('.if-js-closed').removeClass('if-js-closed').addClass('closed');
			// postboxes setup
			postboxes.add_postbox_toggles('<?php echo $_gsb_settings_pagehook; ?>');
		});
		//]]>
	</script>

<?php
}

/**
 * This function generates the form code to be used in the metaboxes
 *
 * @since 0.1
 */
function gsb_defaults_box() {

?>

	<h4>Default Settings</h4>

            <p><?php _e("Home Link Text:", 'GSB'); ?>
		<input type="text" name="<?php echo GSB_SETTINGS_FIELD; ?>[home]" value="<?php echo esc_attr( gsb_get_option('home') ); ?>" size="27" /></p>

            <p><?php _e("Trail Seperator:", 'GSB'); ?>
		<input type="text" name="<?php echo GSB_SETTINGS_FIELD; ?>[sep]" value="<?php echo esc_attr( gsb_get_option('sep') ); ?>" size="27" /></p>

            <p><?php _e("List Seperator:", 'genesis'); ?>
		<input type="text" name="<?php echo GSB_SETTINGS_FIELD; ?>[list_sep]" value="<?php echo esc_attr( gsb_get_option('list_sep') ); ?>" size="27" /></p>

            <p><?php _e("Prefix:", 'genesis'); ?>
		<input type="text" name="<?php echo GSB_SETTINGS_FIELD; ?>[prefix]" value="<?php echo esc_attr( gsb_get_option('prefix') ); ?>" size="27" /></p>

            <p><?php _e("Suffix:", 'genesis'); ?>
		<input type="text" name="<?php echo GSB_SETTINGS_FIELD; ?>[suffix]" value="<?php echo esc_attr( gsb_get_option('suffix') ); ?>" size="27" /></p>

	<hr class="div" />

            <p><input type="checkbox" name="<?php echo GSB_SETTINGS_FIELD; ?>[heirarchial_attachments]" id="<?php echo GSB_SETTINGS_FIELD; ?>[heirarchial_attachments]" value="1" <?php checked(1, gsb_get_option('heirarchial_attachments')); ?> /> <label for="<?php echo GSB_SETTINGS_FIELD; ?>[heirarchial_attachments]"><?php _e("Enable Hierarchial Attachments?", 'GSB'); ?></label>
		</p>

            <p><input type="checkbox" name="<?php echo GSB_SETTINGS_FIELD; ?>[heirarchial_categories]" id="<?php echo GSB_SETTINGS_FIELD; ?>[heirarchial_categories]" value="1" <?php checked(1, gsb_get_option('heirarchial_categories')); ?> /> <label for="<?php echo GSB_SETTINGS_FIELD; ?>[heirarchial_categories]"><?php _e("Enable Hierarchial Categories?", 'GSB'); ?></label>
		</p>

            <?php /* <p><input type="checkbox" name="<?php echo GSB_SETTINGS_FIELD; ?>[display]" id="<?php echo GSB_SETTINGS_FIELD; ?>[display]" value="1" <?php checked(1, gsb_get_option('display')); ?> /> <label for="<?php echo GSB_SETTINGS_FIELD; ?>[display]"><?php _e("Display?", 'GSB'); ?></label>
		</p> */ ?>

        <hr class="div" />

        <h5>Labels</h5>

            <p><?php _e("Prefix:", 'GSB'); ?>
                    <input type="text" name="<?php echo GSB_SETTINGS_FIELD; ?>[label_prefix]" value="<?php echo esc_attr( gsb_get_option('label_prefix') ); ?>" size="27" /></p>

            <p><?php _e("Author:", 'GSB'); ?>
                    <input type="text" name="<?php echo GSB_SETTINGS_FIELD; ?>[author]" value="<?php echo esc_attr( gsb_get_option('author') ); ?>" size="27" /></p>

            <p><?php _e("Category:", 'GSB'); ?>
                    <input type="text" name="<?php echo GSB_SETTINGS_FIELD; ?>[category]" value="<?php echo esc_attr( gsb_get_option('category') ); ?>" size="27" /></p>

            <p><?php _e("Tag:", 'GSB'); ?>
                    <input type="text" name="<?php echo GSB_SETTINGS_FIELD; ?>[tag]" value="<?php echo esc_attr( gsb_get_option('tag') ); ?>" size="27" /></p>

            <p><?php _e("Date:", 'GSB'); ?>
                    <input type="text" name="<?php echo GSB_SETTINGS_FIELD; ?>[date]" value="<?php echo esc_attr( gsb_get_option('date') ); ?>" size="27" /></p>

            <p><?php _e("Search:", 'GSB'); ?>
                    <input type="text" name="<?php echo GSB_SETTINGS_FIELD; ?>[search]" value="<?php echo esc_attr( gsb_get_option('search') ); ?>" size="27" /></p>

            <p><?php _e("Taxonomy:", 'GSB'); ?>
                    <input type="text" name="<?php echo GSB_SETTINGS_FIELD; ?>[tax]" value="<?php echo esc_attr( gsb_get_option('tax') ); ?>" size="27" /></p>

            <p><?php _e("Post Type:", 'GSB'); ?>
                    <input type="text" name="<?php echo GSB_SETTINGS_FIELD; ?>[post_type]" value="<?php echo esc_attr( gsb_get_option('post_type') ); ?>" size="27" /></p>

            <p><?php _e("404:", 'GSB'); ?>
                    <input type="text" name="<?php echo GSB_SETTINGS_FIELD; ?>[404]" value="<?php echo esc_attr( gsb_get_option('404') ); ?>" size="27" /></p>

<?php
}
function gsb_form_submit( $args = array() ) {
	echo '<p><input type="submit" class="button-primary" value="'. __('Save Changes', 'GSB') .'" /></p>';
}