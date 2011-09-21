<?php
/*
 * Replaces the Menu Options in the Genesis Theme Settings
 */

add_filter( 'genesis_theme_settings_defaults', 'gnma_theme_settings_defaults' );

/** Sets Defaults for the New Menu Options */
function gnma_theme_settings_defaults( $defaults ) {
    $gnma_defaults = array(
        'nav' => 1,
        'nav_superfish' => 1,
        'nav_home' => 1,
        'nav_type' => 'pages',
        'nav_pages_sort' => 'menu_order',
        'nav_categories_sort' => 'name',
        'nav_depth' => 0,
        'nav_extras_enable' => 0,
        'nav_extras' => 'date',
        'nav_extras_twitter_id' => '',
        'nav_extras_twitter_text' => 'Follow me on Twitter',
        'subnav' => 0,
        'subnav_superfish' => 1,
        'subnav_home' => 0,
        'subnav_type' => 'categories',
        'subnav_pages_sort' => 'menu_order',
        'subnav_categories_sort' => 'name',
        'subnav_depth' => 0,
        'subnav_extras_enable' => 0,
        'subnav_extras' => 'date',
        'subnav_extras_twitter_id' => '',
        'subnav_extras_twitter_text' => 'Follow me on Twitter'
    );

    $defaults = wp_parse_args( $defaults, $gnma_defaults );

    return $defaults;
}

add_action( 'admin_menu', 'gnma_theme_settings_init', 15 );

/**
 * This is a necessary go-between to get our scripts and boxes loaded
 * on the theme settings page only, and not the rest of the admin
 */
function gnma_theme_settings_init() {
    global $_genesis_theme_settings_pagehook;

    add_action( 'load-' . $_genesis_theme_settings_pagehook, 'gnma_theme_settings_boxes' );
}

function gnma_theme_settings_boxes() {
    global $_genesis_theme_settings_pagehook;

    remove_meta_box( 'genesis-theme-settings-nav', $_genesis_theme_settings_pagehook, 'column1' );
    remove_meta_box( 'genesis-theme-settings-subnav', $_genesis_theme_settings_pagehook, 'column1' );
    remove_meta_box('genesis-theme-settings-nav', $_genesis_theme_settings_pagehook, 'main');

    add_meta_box( 'gnma-theme-settings-nav', __( 'Primary Navigation', 'genesis' ), 'gnma_theme_settings_nav_box', $_genesis_theme_settings_pagehook, 'column1' );
    add_meta_box( 'gnma-theme-settings-subnav', __( 'Secondary Navigation', 'genesis' ), 'gnma_theme_settings_subnav_box', $_genesis_theme_settings_pagehook, 'column1' );

    add_meta_box('gnma-theme-settings-nav', __('Primary Navigation', 'genesis'), 'gnma_theme_settings_nav_box', $_genesis_theme_settings_pagehook, 'main');
    add_meta_box('gnma-theme-settings-subnav', __('Secondary navigation', 'genesis'), 'gnma_theme_settings_subnav_box', $_genesis_theme_settings_pagehook, 'main');
}

function gnma_theme_settings_nav_box() {
?>
    <p><input type="checkbox" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[nav]" id="<?php echo GENESIS_SETTINGS_FIELD; ?>[nav]" value="1" <?php checked( 1, genesis_get_option( 'nav' ) ); ?> /> <label for="<?php echo GENESIS_SETTINGS_FIELD; ?>[nav]"><?php _e( "Include Primary Navigation Menu?", 'genesis' ); ?></label>
    </p>

    <div id="genesis_nav_settings">

        <p><input type="checkbox" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[nav_superfish]" id="<?php echo GENESIS_SETTINGS_FIELD; ?>[nav_superfish]" value="1" <?php checked( 1, genesis_get_option( 'nav_superfish' ) ); ?> /> <label for="<?php echo GENESIS_SETTINGS_FIELD; ?>[nav_superfish]"><?php _e( "Enable Fancy Dropdowns?", 'genesis' ); ?></label>
        </p>

        <hr class="div" />

        <p><?php _e( "Display the following (left side):", 'genesis' ); ?></p>

        <p>

<?php if ( function_exists( 'wp_nav_menu' ) ) : ?>

        <label><input type="radio" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[nav_type]" value="nav-menu" <?php checked( 'nav-menu', genesis_get_option( 'nav_type' ) ); ?> />
        <?php _e( 'Custom Nav Menu', 'genesis' ); ?></label><br />

<?php endif; ?>

        <label><input type="radio" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[nav_type]" value="pages" <?php checked( 'pages', genesis_get_option( 'nav_type' ) ); ?> />
<?php _e( 'List of Pages sorted by', 'genesis' ); ?></label>
        <select name="<?php echo GENESIS_SETTINGS_FIELD; ?>[nav_pages_sort]">
            <option style="padding-right:10px;" value="menu_order" <?php selected( 'menu_order', genesis_get_option( 'nav_pages_sort' ) ); ?>>Menu Order</option>
            <option style="padding-right:10px;" value="post_title" <?php selected( 'post_title', genesis_get_option( 'nav_pages_sort' ) ); ?>>Title</option>
            <option style="padding-right:10px;" value="ID" <?php selected( 'ID', genesis_get_option( 'nav_pages_sort' ) ); ?>>ID</option>
            <option style="padding-right:10px;" value="post_date" <?php selected( 'post_date', genesis_get_option( 'nav_pages_sort' ) ); ?>>Date Created</option>
            <option style="padding-right:10px;" value="post_modified" <?php selected( 'post_modified', genesis_get_option( 'nav_pages_sort' ) ); ?>>Date Modified</option>
            <option style="padding-right:10px;" value="post_author" <?php selected( 'post_author', genesis_get_option( 'nav_pages_sort' ) ); ?>>Author</option>
            <option style="padding-right:10px;" value="post_name" <?php selected( 'post_name', genesis_get_option( 'nav_pages_sort' ) ); ?>>Slug</option>
        </select>

        <br />

        <label><input type="radio" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[nav_type]" value="categories" <?php checked( 'categories', genesis_get_option( 'nav_type' ) ); ?> />
<?php _e( 'List of Categories sorted by', 'genesis' ); ?></label>
        <select name="<?php echo GENESIS_SETTINGS_FIELD; ?>[nav_categories_sort]">
            <option style="padding-right:10px;" value="name" <?php selected( 'name', genesis_get_option( 'nav_categories_sort' ) ); ?>>Name</option>
            <option style="padding-right:10px;" value="ID" <?php selected( 'ID', genesis_get_option( 'nav_categories_sort' ) ); ?>>ID</option>
            <option style="padding-right:10px;" value="slug" <?php selected( 'slug', genesis_get_option( 'nav_categories_sort' ) ); ?>>Slug</option>
            <option style="padding-right:10px;" value="count" <?php selected( 'count', genesis_get_option( 'nav_categories_sort' ) ); ?>>Count</option>
            <option style="padding-right:10px;" value="term_group" <?php selected( 'term_group', genesis_get_option( 'nav_categories_sort' ) ); ?>>Term Group</option>
        </select>

    </p>

<?php if ( function_exists( 'wp_nav_menu' ) ) : ?>

            <p><span class="description"><?php printf( __( '<b>NOTE:</b> In order to use the "Custom Nav Menu" option, you must build a <a href="%s">custom menu</a>. Also, make sure that you assign it to the "Primary Navigation Menu" Location.', 'genesis' ), admin_url( 'nav-menus.php' ) ); ?></span></p>

<?php endif; ?>

            <div class="nav-opts <?php if ( genesis_get_option( 'nav_type' ) == 'nav-menu' )
                echo 'hidden' ?>">

                    <hr class="div" />

                    <p><input type="checkbox" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[nav_home]" id="<?php echo GENESIS_SETTINGS_FIELD; ?>[nav_home]" value="1" <?php checked( 1, genesis_get_option( 'nav_home' ) ); ?> /> <label for="<?php echo GENESIS_SETTINGS_FIELD; ?>[nav_home]"><?php _e( 'Display Home Link?', 'genesis' ); ?></label></p>

                    <p><?php _e( 'Navigation Depth', 'genesis' ); ?>:
                        <select name="<?php echo GENESIS_SETTINGS_FIELD; ?>[nav_depth]">
                            <option style="padding-right: 10px;" value="0" <?php selected( 0, genesis_get_option( 'nav_depth' ) ); ?>>No Limit</option>
                            <option style="padding-right: 10px;" value="1" <?php selected( 1, genesis_get_option( 'nav_depth' ) ); ?>>1</option>
                            <option style="padding-right: 10px;" value="2" <?php selected( 2, genesis_get_option( 'nav_depth' ) ); ?>>2</option>
                            <option style="padding-right: 10px;" value="3" <?php selected( 3, genesis_get_option( 'nav_depth' ) ); ?>>3</option>
                            <option style="padding-right: 10px;" value="4" <?php selected( 4, genesis_get_option( 'nav_depth' ) ); ?>>4</option>
                        </select>
                    </p>

                    <p><?php _e( 'Include the following ID\'s:', 'genesis' ); ?><br />
                        <input type="text" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[nav_include]" value="<?php echo esc_attr( genesis_get_option( 'nav_include' ) ); ?>" size="40" /><br />
                        <small><strong><?php _e( "Comma separated - 1,2,3 for example", 'genesis' ); ?></strong></small></p>

                    <p><?php _e( 'Exclude the following ID\'s', 'genesis' ); ?><br />
                        <input type="text" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[nav_exclude]" value="<?php echo esc_attr( genesis_get_option( 'nav_exclude' ) ); ?>" size="40" /><br />
                        <small><strong><?php _e( "Comma separated - 1,2,3 for example", 'genesis' ); ?></strong></small></p>

                </div><!-- end .nav-opts -->

                <hr class="div" />

                <p><input type="checkbox" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[nav_extras_enable]" id="<?php echo GENESIS_SETTINGS_FIELD; ?>[nav_extras_enable]" value="1" <?php checked( 1, genesis_get_option( 'nav_extras_enable' ) ); ?> /> <label for="<?php echo GENESIS_SETTINGS_FIELD; ?>[nav_extras_enable]"><?php _e( 'Enable Extras on Right Side?', 'genesis' ); ?></label></p>

                <div id="genesis_nav_extras_settings">
                    <p><?php _e( "Display the following:", 'genesis' ); ?>
                        <select name="<?php echo GENESIS_SETTINGS_FIELD; ?>[nav_extras]" id="<?php echo GENESIS_SETTINGS_FIELD; ?>[nav_extras]">
                            <option value="date" <?php selected( 'date', genesis_get_option( 'nav_extras' ) ); ?>><?php _e( "Today's date", 'genesis' ); ?></option>
                            <option value="rss" <?php selected( 'rss', genesis_get_option( 'nav_extras' ) ); ?>><?php _e( "RSS feed links", 'genesis' ); ?></option>
                            <option value="search" <?php selected( 'search', genesis_get_option( 'nav_extras' ) ); ?>><?php _e( "Search form", 'genesis' ); ?></option>
                            <option value="twitter" <?php selected( 'twitter', genesis_get_option( 'nav_extras' ) ); ?>><?php _e( "Twitter link", 'genesis' ); ?></option>
                        </select></p>
                    <div id="genesis_nav_extras_twitter">
                        <p><?php _e( "Enter Twitter ID:", 'genesis' ); ?>
                            <input type="text" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[nav_extras_twitter_id]" value="<?php echo esc_attr( genesis_get_option( 'nav_extras_twitter_id' ) ); ?>" size="27" /></p>
                        <p><?php _e( "Twitter Link Text:", 'genesis' ); ?>
                            <input type="text" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[nav_extras_twitter_text]" value="<?php echo esc_attr( genesis_get_option( 'nav_extras_twitter_text' ) ); ?>" size="27" /></p>
                    </div>
                </div>
            </div>

<?php
}

function gnma_theme_settings_subnav_box() {
?>
<p><input type="checkbox" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[subnav]" id="<?php echo GENESIS_SETTINGS_FIELD; ?>[subnav]" value="1" <?php checked( 1, genesis_get_option( 'subnav' ) ); ?> /> <label for="<?php echo GENESIS_SETTINGS_FIELD; ?>[subnav]"><?php _e( "Include Secondary Navigation Menu?", 'genesis' ); ?></label>
</p>
<div id="genesis_subnav_settings">
<p><input type="checkbox" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[subnav_superfish]" id="<?php echo GENESIS_SETTINGS_FIELD; ?>[subnav_superfish]" value="1" <?php checked( 1, genesis_get_option( 'subnav_superfish' ) ); ?> /> <label for="<?php echo GENESIS_SETTINGS_FIELD; ?>[subnav_superfish]"><?php _e( "Enable Fancy Dropdowns?", 'genesis' ); ?></label>
</p>

<hr class="div" />

<p><?php _e( "Display the following:", 'genesis' ); ?></p>

<p>

<?php if ( function_exists( 'wp_nav_menu' ) ) : ?>

<label><input type="radio" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[subnav_type]" value="nav-menu" <?php checked( 'nav-menu', genesis_get_option( 'subnav_type' ) ); ?> />
<?php _e( 'Custom Nav Menu', 'genesis' ); ?></label><br />

<?php endif; ?>

<label><input type="radio" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[subnav_type]" value="pages" <?php checked( 'pages', genesis_get_option( 'subnav_type' ) ); ?> />
<?php _e( 'List of Pages sorted by', 'genesis' ); ?></label>
<select name="<?php echo GENESIS_SETTINGS_FIELD; ?>[subnav_pages_sort]">
<option style="padding-right:10px;" value="menu_order" <?php selected( 'menu_order', genesis_get_option( 'subnav_pages_sort' ) ); ?>>Menu Order</option>
<option style="padding-right:10px;" value="post_title" <?php selected( 'post_title', genesis_get_option( 'subnav_pages_sort' ) ); ?>>Title</option>
<option style="padding-right:10px;" value="ID" <?php selected( 'ID', genesis_get_option( 'subnav_pages_sort' ) ); ?>>ID</option>
<option style="padding-right:10px;" value="post_date" <?php selected( 'post_date', genesis_get_option( 'subnav_pages_sort' ) ); ?>>Date Created</option>
<option style="padding-right:10px;" value="post_modified" <?php selected( 'post_modified', genesis_get_option( 'subnav_pages_sort' ) ); ?>>Date Modified</option>
<option style="padding-right:10px;" value="post_author" <?php selected( 'post_author', genesis_get_option( 'subnav_pages_sort' ) ); ?>>Author</option>
<option style="padding-right:10px;" value="post_name" <?php selected( 'post_name', genesis_get_option( 'subnav_pages_sort' ) ); ?>>Slug</option>
</select>

<br />

<label><input type="radio" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[subnav_type]" value="categories" <?php checked( 'categories', genesis_get_option( 'subnav_type' ) ); ?> />
<?php _e( 'List of Categories sorted by', 'genesis' ); ?></label>
<select name="<?php echo GENESIS_SETTINGS_FIELD; ?>[subnav_categories_sort]">
<option style="padding-right:10px;" value="name" <?php selected( 'name', genesis_get_option( 'subnav_categories_sort' ) ); ?>>Name</option>
<option style="padding-right:10px;" value="ID" <?php selected( 'ID', genesis_get_option( 'subnav_categories_sort' ) ); ?>>ID</option>
<option style="padding-right:10px;" value="slug" <?php selected( 'slug', genesis_get_option( 'subnav_categories_sort' ) ); ?>>Slug</option>
<option style="padding-right:10px;" value="count" <?php selected( 'count', genesis_get_option( 'subnav_categories_sort' ) ); ?>>Count</option>
<option style="padding-right:10px;" value="term_group" <?php selected( 'term_group', genesis_get_option( 'subnav_categories_sort' ) ); ?>>Term Group</option>
</select>
</p>

<?php if ( function_exists( 'wp_nav_menu' ) ) : ?>

<p><span class="description"><?php printf( __( '<b>NOTE:</b> In order to use the "Custom Nav Menu" option, you must build a <a href="%s">custom menu</a>. Also, make sure that you assign it to the "Secondary Navigation Menu" Location.', 'genesis' ), admin_url( 'nav-menus.php' ) ); ?></span></p>

<?php endif; ?>

<div class="nav-opts <?php if ( genesis_get_option( 'subnav_type' ) == 'nav-menu' )
    echo 'hidden' ?>">

        <hr class="div" />

        <p><input type="checkbox" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[subnav_home]" id="<?php echo GENESIS_SETTINGS_FIELD; ?>[subnav_home]" value="1" <?php checked( 1, genesis_get_option( 'subnav_home' ) ); ?> /> <label for="<?php echo GENESIS_SETTINGS_FIELD; ?>[subnav_home]"><?php _e( 'Display Home Link?', 'genesis' ); ?></label></p>

        <p><?php _e( 'Sub Navigation Depth', 'genesis' ); ?>:
            <select name="<?php echo GENESIS_SETTINGS_FIELD; ?>[subnav_depth]">
                <option style="padding-right: 10px;" value="0" <?php selected( 0, genesis_get_option( 'subnav_depth' ) ); ?>>No Limit</option>
                <option style="padding-right: 10px;" value="1" <?php selected( 1, genesis_get_option( 'subnav_depth' ) ); ?>>1</option>
                <option style="padding-right: 10px;" value="2" <?php selected( 2, genesis_get_option( 'subnav_depth' ) ); ?>>2</option>
                <option style="padding-right: 10px;" value="3" <?php selected( 3, genesis_get_option( 'subnav_depth' ) ); ?>>3</option>
                <option style="padding-right: 10px;" value="4" <?php selected( 4, genesis_get_option( 'subnav_depth' ) ); ?>>4</option>
            </select>
        </p>

        <p><?php _e( "Include the following ID's:", 'genesis' ); ?><br />
            <input type="text" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[subnav_include]" value="<?php echo esc_attr( genesis_get_option( 'subnav_include' ) ); ?>" size="40" /><br />
            <small><strong><?php _e( "Comma separated - 1,2,3 for example", 'genesis' ); ?></strong></small></p>

        <p><?php _e( "Exclude the following ID's:", 'genesis' ); ?><br />
            <input type="text" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[subnav_exclude]" value="<?php echo esc_attr( genesis_get_option( 'subnav_exclude' ) ); ?>" size="40" /><br />
            <small><strong><?php _e( "Comma separated - 1,2,3 for example", 'genesis' ); ?></strong></small></p>

    </div><!-- end .nav-opts -->

    <hr class="div" />

    <p><input type="checkbox" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[subnav_extras_enable]" id="<?php echo GENESIS_SETTINGS_FIELD; ?>[subnav_extras_enable]" value="1" <?php checked( 1, genesis_get_option( 'subnav_extras_enable' ) ); ?> /> <label for="<?php echo GENESIS_SETTINGS_FIELD; ?>[subnav_extras_enable]"><?php _e( 'Enable Extras on Right Side?', 'genesis' ); ?></label></p>

    <div id="genesis_subnav_extras_settings">
        <p><?php _e( "Display the following:", 'genesis' ); ?>
            <select name="<?php echo GENESIS_SETTINGS_FIELD; ?>[subnav_extras]" id="<?php echo GENESIS_SETTINGS_FIELD; ?>[subnav_extras]">
                <option value="date" <?php selected( 'date', genesis_get_option( 'subnav_extras' ) ); ?>><?php _e( "Today's date", 'genesis' ); ?></option>
                <option value="rss" <?php selected( 'rss', genesis_get_option( 'subnav_extras' ) ); ?>><?php _e( "RSS feed links", 'genesis' ); ?></option>
                <option value="search" <?php selected( 'search', genesis_get_option( 'subnav_extras' ) ); ?>><?php _e( "Search form", 'genesis' ); ?></option>
                <option value="twitter" <?php selected( 'twitter', genesis_get_option( 'subnav_extras' ) ); ?>><?php _e( "Twitter link", 'genesis' ); ?></option>
            </select></p>
        <div id="genesis_subnav_extras_twitter">
            <p><?php _e( "Enter Twitter ID:", 'genesis' ); ?>
                <input type="text" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[subnav_extras_twitter_id]" value="<?php echo esc_attr( genesis_get_option( 'subnav_extras_twitter_id' ) ); ?>" size="27" /></p>
            <p><?php _e( "Twitter Link Text:", 'genesis' ); ?>
                <input type="text" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[subnav_extras_twitter_text]" value="<?php echo esc_attr( genesis_get_option( 'subnav_extras_twitter_text' ) ); ?>" size="27" /></p>
            </div>
        </div>
    </div>

<?php
}