<?php
/**
 * Creates options and outputs admin menu and options page
 */

/**
 * Return the defaults array
 *
 * @since 0.1
 */
function gsc_defaults() {
	return array(
			'title_wrap'                                            => '<h3>%s</h3>',
                        'genesis_title_comments'				=> __( 'Comments', 'genesis' ),
			'genesis_no_comments_text'				=> '',
			'genesis_comments_closed_text'				=> '',
			'genesis_title_pings'					=> __( 'Trackbacks', 'genesis' ),
			'genesis_no_pings_text'					=> '',

                            'genesis_comment_list_args_avatar_size'             => 48,

                        'comment_author_says_text'                              => __( 'says', 'genesis' ),
			'genesis_comment_awaiting_moderation'			=> __( 'Your comment is awaiting moderation.', 'genesis' ),

                            'genesis_comment_form_args_fields_aria_display'     => TRUE,
                            'genesis_comment_form_args_fields_author_display'    => TRUE,
                            'genesis_comment_form_args_fields_author_label'	=> __( 'Name', 'genesis' ),
                            'genesis_comment_form_args_fields_email_display'	=> TRUE,
                            'genesis_comment_form_args_fields_email_label'	=> __( 'Email', 'genesis' ),
                            'genesis_comment_form_args_fields_url_display'	=> TRUE,
                            'genesis_comment_form_args_fields_url_label'	=> __( 'Website', 'genesis' ),

                            'genesis_comment_form_args_title_reply'             => __( 'Speak Your Mind', 'genesis' ),
                            'genesis_comment_form_args_comment_notes_before'	=> '',
                            'genesis_comment_form_args_comment_notes_after'	=> '',
                            'genesis_comment_form_args_label_submit'            => __( 'Post Comment' )

	);

}


add_option(GSC_SETTINGS_FIELD, gsc_defaults(), '', 'yes');

/**
 * This is a necessary go-between to get our scripts and boxes loaded
 * on the theme settings page only, and not the rest of the admin
 */
add_action('admin_menu', 'gsc_settings_init', 15);
function gsc_settings_init() {
	global $_gsc_settings_pagehook;

	// Add "Design Settings" submenu
	$_gsc_settings_pagehook = add_submenu_page('genesis', __('Simple Comments', 'gsc'), __('Simple Comments', 'gsc'), 'manage_options', 'gsc', 'gsc_settings_admin');

	add_action('load-'.$_gsc_settings_pagehook, 'gsc_settings_scripts');
	add_action('load-'.$_gsc_settings_pagehook, 'gsc_settings_boxes');
}

/**
 * loads scripts used in optiosn page
 */
function gsc_settings_scripts() {
	wp_enqueue_script('common');
	wp_enqueue_script('wp-lists');
	wp_enqueue_script('postbox');
}

/**
 * Adds meta box to plugin options page
 *
 * @global string $_gsc_settings_pagehook
 */
function gsc_settings_boxes() {
	global $_gsc_settings_pagehook;

	add_meta_box('gsc-defaults', __('Commments Defaults', 'gsc'), 'gsc_defaults_box', $_gsc_settings_pagehook, 'column1');
}

/**
 * Tell WordPress that we want only 1 column available for our meta-boxes
 */
add_filter('screen_layout_columns', 'gsc_settings_layout_columns', 10, 2);
function gsc_settings_layout_columns($columns, $screen) {
	global $_gsc_settings_pagehook;
	if ($screen == $_gsc_settings_pagehook) {
		// This page should have 3 column options
		$columns[$_gsc_settings_pagehook] = 1;
	}
	return $columns;
}

/**
 * This function is what actually gets output to the page. It handles the markup,
 * builds the form, outputs necessary JS stuff, and fires <code>do_meta_boxes()</code>
 */
function gsc_settings_admin() {
global $_gsc_settings_pagehook, $screen_layout_columns;

	$width = "width: 99%;";
	$hide2 = $hide3 = " display: none;";

?>
	<div id="gsc" class="wrap genesis-metaboxes">
	<form method="post" action="options.php">

		<?php wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false ); ?>
		<?php wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false ); ?>
		<?php settings_fields(GSC_SETTINGS_FIELD); // important! ?>

		<?php screen_icon('plugins'); ?>
                <h2>
			<?php echo esc_html( get_admin_page_title() ); ?>
			<input type="submit" class="button-primary genesis-h2-button" value="<?php _e('Save Settings', 'genesis') ?>" />
			<input type="submit" class="button-highlighted genesis-h2-button" name="<?php echo GSC_SETTINGS_FIELD; ?>[reset]" value="<?php _e('Reset Settings', 'genesis'); ?>" onclick="return genesis_confirm('<?php echo esc_js( __('Are you sure you want to reset?', 'genesis') ); ?>');" />
		</h2>

		<?php
		if(genesis_get_option('reset', GSC_SETTINGS_FIELD)) {
			update_option(GSC_SETTINGS_FIELD, gsc_defaults());
			echo '<div id="message" class="updated" id="message"><p><strong>'.__('Modifications Reset', 'gsc').'</strong></p></div>';
		}
		elseif( isset($_REQUEST['updated']) && $_REQUEST['updated'] == 'true') {
			echo '<div id="message" class="updated" id="message"><p><strong>'.__('Modifications Saved', 'gsc').'</strong></p></div>';
		}
		?>

		<div class="metabox-holder">
			<div class="postbox-container" style="<?php echo $width; ?>">
				<?php do_meta_boxes($_gsc_settings_pagehook, 'column1', null); ?>
			</div>
		</div>

                <h2>
			<?php echo esc_html( get_admin_page_title() ); ?>
			<input type="submit" class="button-primary genesis-h2-button" value="<?php _e('Save Settings', 'genesis') ?>" />
			
		</h2>

	</form>
	</div>
	<script type="text/javascript">
		//<![CDATA[
		jQuery(document).ready( function($) {
			// close postboxes that should be closed
			$('.if-js-closed').removeClass('if-js-closed').addClass('closed');
			// postboxes setup
			postboxes.add_postbox_toggles('<?php echo $_gsc_settings_pagehook; ?>');
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
function gsc_defaults_box() {

?>

	<h4>Default Settings</h4>

            <p><?php _e("Title Wrap:", 'gsc'); ?><sup><abbr title="<?php _e( 'This is the html tag used around the Comment Title and Pings Title.  Make sure you keep the <tag>%s</tag> format for the wrap to work correctly.', 'gsc' ); ?>">*</abbr></sup>
		<input type="text" name="<?php echo GSC_SETTINGS_FIELD; ?>[title_wrap]" value="<?php echo esc_attr( gsc_get_option('title_wrap') ); ?>" size="27" /></p>

            <p><?php _e("Comment Title:", 'gsc'); ?>
		<input type="text" name="<?php echo GSC_SETTINGS_FIELD; ?>[genesis_title_comments]" value="<?php echo esc_attr( gsc_get_option('genesis_title_comments') ); ?>" size="27" /></p>

            <p><?php _e("No Comments Text:", 'gsc'); ?>
		<input type="text" name="<?php echo GSC_SETTINGS_FIELD; ?>[genesis_no_comments_text]" value="<?php echo esc_attr( gsc_get_option('genesis_no_comments_text') ); ?>" size="27" /></p>

            <p><?php _e("Comments Closed Text:", 'gsc'); ?>
		<input type="text" name="<?php echo GSC_SETTINGS_FIELD; ?>[genesis_comments_closed_text]" value="<?php echo esc_attr( gsc_get_option('genesis_comments_closed_text') ); ?>" size="27" /></p>

            <p><?php _e("Pings Title:", 'gsc'); ?>
		<input type="text" name="<?php echo GSC_SETTINGS_FIELD; ?>[genesis_title_pings]" value="<?php echo esc_attr( gsc_get_option('genesis_title_pings') ); ?>" size="27" /></p>

            <p><?php _e("No Pings Text:", 'gsc'); ?>
		<input type="text" name="<?php echo GSC_SETTINGS_FIELD; ?>[genesis_no_pings_text]" value="<?php echo esc_attr( gsc_get_option('genesis_no_pings_text') ); ?>" size="27" /></p>

	<hr class="div" />

            <p><?php _e("Avatar Size:", 'gsc'); ?>
		<input type="text" name="<?php echo GSC_SETTINGS_FIELD; ?>[genesis_comment_list_args_avatar_size]" value="<?php echo esc_attr( gsc_get_option('genesis_comment_list_args_avatar_size') ); ?>" size="27" /></p>

            <p><?php _e("Author Says Text:", 'gsc'); ?>
		<input type="text" name="<?php echo GSC_SETTINGS_FIELD; ?>[comment_author_says_text]" value="<?php echo esc_attr( gsc_get_option('comment_author_says_text') ); ?>" size="27" /></p>

            <p><?php _e("Comment Awaiting Moderation Text:", 'gsc'); ?>
		<input type="text" name="<?php echo GSC_SETTINGS_FIELD; ?>[genesis_comment_awaiting_moderation]" value="<?php echo esc_attr( gsc_get_option('genesis_comment_awaiting_moderation') ); ?>" size="27" /></p>

        <hr class="div" />

        <h5>Form Fields</h5>

            <p><input type="checkbox" name="<?php echo GSC_SETTINGS_FIELD; ?>[genesis_comment_form_args_fields_aria_display]" id="<?php echo GSC_SETTINGS_FIELD; ?>[genesis_comment_form_args_fields_aria_display]" value="1" <?php checked(1, gsc_get_option('genesis_comment_form_args_fields_aria_display')); ?> /> <label for="<?php echo GSC_SETTINGS_FIELD; ?>[genesis_comment_form_args_fields_aria_display]"><?php _e("Enable Aria Require True Attribute?", 'gsc'); ?><sup><abbr title="<?php _e( 'This is enabled by default and adds an attribute to the required comment fields that adds a layout of accesibility for visually impaired site visitors.  This attribute is not technically valid XHTML but works in all browsers. Unless you need 100% valid markup at the expense of accesability, leave this option enabled.', 'gsc' ); ?>">*</abbr></sup></label>
		</p>

            <p><input type="checkbox" name="<?php echo GSC_SETTINGS_FIELD; ?>[genesis_comment_form_args_fields_author_display]" id="<?php echo GSC_SETTINGS_FIELD; ?>[genesis_comment_form_args_fields_author_display]" value="1" <?php checked(1, gsc_get_option('genesis_comment_form_args_fields_author_display')); ?> /> <label for="<?php echo GSC_SETTINGS_FIELD; ?>[genesis_comment_form_args_fields_author_display]"><?php _e("Display Author Field?", 'gsc'); ?></label>
		</p>

            <p><?php _e("Author Label:", 'gsc'); ?>
                    <input type="text" name="<?php echo GSC_SETTINGS_FIELD; ?>[genesis_comment_form_args_fields_author_label]" value="<?php echo esc_attr( gsc_get_option('genesis_comment_form_args_fields_author_label') ); ?>" size="27" /></p>

            <p><input type="checkbox" name="<?php echo GSC_SETTINGS_FIELD; ?>[genesis_comment_form_args_fields_email_display]" id="<?php echo GSC_SETTINGS_FIELD; ?>[genesis_comment_form_args_fields_email_display]" value="1" <?php checked(1, gsc_get_option('genesis_comment_form_args_fields_email_display')); ?> /> <label for="<?php echo GSC_SETTINGS_FIELD; ?>[genesis_comment_form_args_fields_email_display]"><?php _e("Display Email Field?", 'gsc'); ?></label>
		</p>

            <p><?php _e("Email Label:", 'gsc'); ?>
                    <input type="text" name="<?php echo GSC_SETTINGS_FIELD; ?>[genesis_comment_form_args_fields_email_label]" value="<?php echo esc_attr( gsc_get_option('genesis_comment_form_args_fields_email_label') ); ?>" size="27" /></p>

            <p><input type="checkbox" name="<?php echo GSC_SETTINGS_FIELD; ?>[genesis_comment_form_args_fields_url_display]" id="<?php echo GSC_SETTINGS_FIELD; ?>[genesis_comment_form_args_fields_url_display]" value="1" <?php checked(1, gsc_get_option('genesis_comment_form_args_fields_url_display')); ?> /> <label for="<?php echo GSC_SETTINGS_FIELD; ?>[genesis_comment_form_args_fields_url_display]"><?php _e("Display URL Field?", 'gsc'); ?></label>
		</p>

            <p><?php _e("URL Label:", 'gsc'); ?>
                    <input type="text" name="<?php echo GSC_SETTINGS_FIELD; ?>[genesis_comment_form_args_fields_url_label]" value="<?php echo esc_attr( gsc_get_option('genesis_comment_form_args_fields_url_label') ); ?>" size="27" /></p>

            <p><?php _e("Reply Label:", 'gsc'); ?>
                    <input type="text" name="<?php echo GSC_SETTINGS_FIELD; ?>[genesis_comment_form_args_title_reply]" value="<?php echo esc_attr( gsc_get_option('genesis_comment_form_args_title_reply') ); ?>" size="27" /></p>

            <p><?php _e("Notes Before Comment:", 'gsc'); ?>
                    <input type="text" name="<?php echo GSC_SETTINGS_FIELD; ?>[genesis_comment_form_args_comment_notes_before]" value="<?php echo esc_attr( gsc_get_option('genesis_comment_form_args_comment_notes_before') ); ?>" size="27" /></p>

            <p><?php _e("Notes After Comment:", 'gsc'); ?>
                    <input type="text" name="<?php echo GSC_SETTINGS_FIELD; ?>[genesis_comment_form_args_comment_notes_after]" value="<?php echo esc_attr( gsc_get_option('genesis_comment_form_args_comment_notes_after') ); ?>" size="27" /></p>

            <p><?php _e("Submit Button Label:", 'gsc'); ?>
                    <input type="text" name="<?php echo GSC_SETTINGS_FIELD; ?>[genesis_comment_form_args_label_submit]" value="<?php echo esc_attr( gsc_get_option('genesis_comment_form_args_label_submit') ); ?>" size="27" /></p>

<?php
}

/**
 * Creates the Submit Button
 */
function gsc_form_submit( $args = array() ) {
	echo '<p><input type="submit" class="button-primary" value="'. __('Save Changes', 'gsc') .'" /></p>';
}