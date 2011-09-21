<?php

/*
 * Removes the Genesis Nav Menu and adds the GNMA menus
 */

add_action( 'genesis_meta', 'gnma_add_menu' );

/** Replaces Existing Genesis Menu */
function gnma_add_menu() {
    $hooks = array( 'genesis_before', 'genesis_after', 'genesis_before_header', 'genesis_header', 'genesis_after_header', 'genesis_before_content_sidebar_wrap', 'genesis_after_content_sidebar_wrap', 'genesis_before_content', 'genesis_after_content', 'genesis_before_loop', 'genesis_after_loop', 'genesis_before_footer', 'genesis_footer', 'genesis_after_footer' );

    foreach ( $hooks as $hook ) {
        if ( has_action( $hook, 'genesis_do_nav' ) ) {
            $priority = has_action( $hook, 'genesis_do_nav' );
            remove_action( $hook, 'genesis_do_nav', $priority );
            add_action( $hook, 'gnma_do_nav', $priority );
            //echo $hook . ' nav ' . $priority . '<br />';
        }

        if ( has_action( $hook, 'genesis_do_subnav' ) ) {
            $priority = has_action( $hook, 'genesis_do_subnav' );
            remove_action( $hook, 'genesis_do_subnav', $priority );
            add_action( $hook, 'gnma_do_subnav', $priority );
            //echo $hook . ' subnav ' . $priority;
        }
    }
}

/** Removes Genesis Menus Filters */
remove_filter( 'genesis_nav_items', 'genesis_nav_right', 10, 2 );
remove_filter( 'wp_nav_menu_items', 'genesis_nav_right', 10, 2 );

/**
 * This function is responsible for displaying the "Primary Navigation" bar.
 *
 * @uses genesis_nav(), genesis_get_option(), wp_nav_menu()
 * @since 1.0
 */
function gnma_do_nav() {
    if ( genesis_get_option( 'nav' ) ) {

        if ( genesis_get_option( 'nav_type' ) == 'nav-menu' && function_exists( 'wp_nav_menu' ) ) {

            $nav = wp_nav_menu( array(
                        'theme_location' => 'primary',
                        'container' => '',
                        'menu_class' => genesis_get_option( 'nav_superfish' ) ? 'nav superfish' : 'nav',
                        'echo' => 0
                            ) );
        } else {

            $nav = genesis_nav( array(
                        'theme_location' => 'primary',
                        'menu_class' => genesis_get_option( 'nav_superfish' ) ? 'nav superfish' : 'nav',
                        'show_home' => genesis_get_option( 'nav_home' ),
                        'type' => genesis_get_option( 'nav_type' ),
                        'sort_column' => genesis_get_option( 'nav_pages_sort' ),
                        'orderby' => genesis_get_option( 'nav_categories_sort' ),
                        'depth' => genesis_get_option( 'nav_depth' ),
                        'exclude' => genesis_get_option( 'nav_exclude' ),
                        'include' => genesis_get_option( 'nav_include' ),
                        'echo' => false
                            ) );
        }

        echo '<div id="nav"><div class="wrap">' . $nav . '</div></div>';
    }
}

//add_action( 'genesis_after_header', 'gnma_do_subnav' );

/**
 * This function  is responsible for displaying the "Secondary Navigation" bar.
 *
 * @uses genesis_nav(), genesis_get_option(), wp_nav_menu
 * @since 1.0.1
 *
 */
function gnma_do_subnav() {
    if ( genesis_get_option( 'subnav' ) ) {

        if ( genesis_get_option( 'subnav_type' ) == 'nav-menu' && function_exists( 'wp_nav_menu' ) ) {

            $subnav = wp_nav_menu( array(
                        'theme_location' => 'secondary',
                        'container' => '',
                        'menu_class' => genesis_get_option( 'subnav_superfish' ) ? 'nav superfish' : 'nav',
                        'echo' => 0
                            ) );
        } else {

            $subnav = genesis_nav( array(
                        'theme_location' => 'secondary',
                        'menu_class' => genesis_get_option( 'subnav_superfish' ) ? 'nav superfish' : 'nav',
                        'show_home' => genesis_get_option( 'subnav_home' ),
                        'type' => genesis_get_option( 'subnav_type' ),
                        'sort_column' => genesis_get_option( 'subnav_pages_sort' ),
                        'orderby' => genesis_get_option( 'subnav_categories_sort' ),
                        'depth' => genesis_get_option( 'subnav_depth' ),
                        'exclude' => genesis_get_option( 'subnav_exclude' ),
                        'include' => genesis_get_option( 'subnav_include' ),
                        'echo' => false
                            ) );
        }

        echo '<div id="subnav"><div class="wrap">' . $subnav . '</div></div>';
    }
}

add_filter( 'genesis_nav_items', 'gnma_nav_right', 10, 2 );
add_filter( 'wp_nav_menu_items', 'gnma_nav_right', 10, 2 );

/**
 * This function filters the Primary Navigation menu items, appending
 * either RSS links, search form, twitter link, or today's date.
 *
 * @uses genesis_get_option(), get_bloginfo(), get_search_form(),
 * @since 1.0
 */
function gnma_nav_right( $menu, $args ) {

    $args = ( array ) $args;

    if ( !genesis_get_option( 'nav_extras_enable' ) || $args['theme_location'] != 'primary' )
        return $menu;

    if ( genesis_get_option( 'nav_extras' ) == 'rss' ) {
        $rss = '<a rel="nofollow" href="' . get_bloginfo( 'rss_url' ) . '">' . __( 'Posts', 'genesis' ) . '</a>';
        $rss .= '<a rel="nofollow" href="' . get_bloginfo( 'comments_rss2_url' ) . '">' . __( 'Comments', 'genesis' ) . '</a>';

        $menu .= '<li class="right rss">' . $rss . '</li>';
    } elseif ( genesis_get_option( 'nav_extras' ) == 'search' ) {
        // I hate output buffering, but I have no choice
        ob_start();
        get_search_form();
        $search = ob_get_clean();

        $menu .= '<li class="right search">' . $search . '</li>';
    } elseif ( genesis_get_option( 'nav_extras' ) == 'twitter' ) {

        $menu .= sprintf( '<li class="right twitter"><a href="%s">%s</a></li>', esc_url( 'http://twitter.com/' . genesis_get_option( 'nav_extras_twitter_id' ) ), esc_html( genesis_get_option( 'nav_extras_twitter_text' ) ) );
    } elseif ( genesis_get_option( 'nav_extras' ) == 'date' ) {

        $menu .= '<li class="right date">' . date_i18n( get_option( 'date_format' ) ) . '</li>';
    }

    return $menu;
}

add_filter( 'genesis_nav_items', 'gnma_subnav_right', 10, 2 );
add_filter( 'wp_nav_menu_items', 'gnma_subnav_right', 10, 2 );

/**
 * This function filters the Primary Navigation menu items, appending
 * either RSS links, search form, twitter link, or today's date.
 *
 * @uses genesis_get_option(), get_bloginfo(), get_search_form(),
 * @since 1.0
 */
function gnma_subnav_right( $menu, $args ) {

    $args = ( array ) $args;

    if ( !genesis_get_option( 'subnav_extras_enable' ) || $args['theme_location'] != 'secondary' )
        return $menu;

    if ( genesis_get_option( 'subnav_extras' ) == 'rss' ) {
        $rss = '<a rel="nofollow" href="' . get_bloginfo( 'rss_url' ) . '">' . __( 'Posts', 'genesis' ) . '</a>';
        $rss .= '<a rel="nofollow" href="' . get_bloginfo( 'comments_rss2_url' ) . '">' . __( 'Comments', 'genesis' ) . '</a>';

        $menu .= '<li class="right rss">' . $rss . '</li>';
    } elseif ( genesis_get_option( 'subnav_extras' ) == 'search' ) {
        // I hate output buffering, but I have no choice
        ob_start();
        get_search_form();
        $search = ob_get_clean();

        $menu .= '<li class="right search">' . $search . '</li>';
    } elseif ( genesis_get_option( 'subnav_extras' ) == 'twitter' ) {

        $menu .= sprintf( '<li class="right twitter"><a href="%s">%s</a></li>', esc_url( 'http://twitter.com/' . genesis_get_option( 'subnav_extras_twitter_id' ) ), esc_html( genesis_get_option( 'subnav_extras_twitter_text' ) ) );
    } elseif ( genesis_get_option( 'subnav_extras' ) == 'date' ) {

        $menu .= '<li class="right date">' . date_i18n( get_option( 'date_format' ) ) . '</li>';
    }

    return $menu;
}

if ( !function_exists( 'genesis_nav' ) ) {

    function genesis_nav( $args = array( ) ) {

        if ( isset( $args['context'] ) ) // deprecated argument
            _deprecated_argument( __FUNCTION__, '1.2', __( 'The argument, "context", has been replaced with "theme_location"' ) );

        $defaults = array(
            'theme_location' => '',
            'type' => 'pages',
            'sort_column' => 'menu_order, post_title',
            'menu_id' => false,
            'menu_class' => 'nav',
            'echo' => true,
            'link_before' => '',
            'link_after' => ''
        );

        $defaults = apply_filters( 'genesis_nav_default_args', $defaults );
        $args = wp_parse_args( $args, $defaults );

        // Allow child theme to short-circuit this function
        $pre = apply_filters( 'genesis_pre_nav', false, $args );
        if ( $pre )
            return $pre;

        $menu = '';

        $list_args = $args;

        // Show Home in the menu (mostly copied from WP source)
        if ( isset( $args['show_home'] ) && !empty( $args['show_home'] ) ) {
            if ( true === $args['show_home'] || '1' === $args['show_home'] || 1 === $args['show_home'] )
                $text = apply_filters( 'genesis_nav_home_text', __( 'Home', 'genesis' ), $args );
            else
                $text = $args['show_home'];

            $class = '';

            if ( is_front_page() && !is_paged() )
                $class = 'class="home current_page_item"';
            else
                $class = 'class="home"';

            $home = '<li ' . $class . '><a href="' . trailingslashit( get_bloginfo( 'url' ) ) . '" title="' . esc_attr( $text ) . '">' . $args['link_before'] . $text . $args['link_after'] . '</a></li>';

            $menu .= genesis_get_seo_option( 'nofollow_home_link' ) ? genesis_rel_nofollow( $home ) : $home;

            // If the front page is a page, add it to the exclude list
            if ( get_option( 'show_on_front' ) == 'page' && $args['type'] == 'pages' ) {
                if ( !empty( $list_args['exclude'] ) ) {
                    $list_args['exclude'] .= ',';
                } else {
                    $list_args['exclude'] = '';
                }
                $list_args['exclude'] .= get_option( 'page_on_front' );
            }
        }

        $list_args['echo'] = false;
        $list_args['title_li'] = '';

        if ( $args['type'] == 'pages' )
            $menu .= str_replace( array( "\r", "\n", "\t" ), '', wp_list_pages( $list_args ) );
        elseif ( $args['type'] == 'categories' )
            $menu .= str_replace( array( "\r", "\n", "\t" ), '', wp_list_categories( $list_args ) );

        // Apply filters to the nav items
        $menu = apply_filters( 'genesis_nav_items', $menu, $args );

        $menu_class = ( $args['menu_class'] ) ? ' class="' . esc_attr( $args['menu_class'] ) . '"' : '';
        $menu_id = ( $args['menu_id'] ) ? ' id="' . esc_attr( $args['menu_id'] ) . '"' : '';

        if ( $menu )
            $menu = '<ul' . $menu_id . $menu_class . '>' . $menu . '</ul>';

        // Apply filters to the final nav output
        $menu = apply_filters( 'genesis_nav', $menu, $args );

        if ( $args['echo'] )
            echo $menu;
        else
            return $menu;
    }

}