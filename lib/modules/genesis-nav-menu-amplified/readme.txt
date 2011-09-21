=== Plugin Name ===
Contributors: Nick_theGeek
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=RGUXZDAKT7BDW
Tags: genesis, genesiswp, studiopress, menu
Requires at least: 3.0
Tested up to: 3.2
Stable tag: 0.4

Genesis Menu options with custom menu, categories, pages, and primary/secondary nav extras.

== Description ==

Genesis Nav Menu Amplified restores the Genesis 1.5 menu system and extends it.  Specifically it:

* Supports List Pages/Categories
* Supports Exclude/Include by ID
* Supports Nav Extras on Primary and Secondary Navigation

This plugin requires the [Genesis Theme Framework](http://designsbynickthegeek.com/go/genesis) aff link

== Installation ==

1. Upload the entire `genesis-nav-menu-amplified` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Go to Genesis Theme Settings
1. Select desired menu options and save settings

== Frequently Asked Questions ==
= Why is my menu Duplicated? =
The Menu was probably moved using an uncommon menu hooks like
`remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_sidebar', 'genesis_do_nav' );`

You need to change it to
`remove_action( 'genesis_after_header', 'gnma_do_nav' );
add_action( 'genesis_sidebar', 'gnma_do_nav' );`

= How do I move the menu? =
See the code in the previous question.  That is the same code for moving the menu.  If you want to use a different hook, change the "genesis_before_header" part of the code, if you want to move the secondary menu change "gnma_do_nav" to "gnma_do_subnav."

Alternately you can try the normal method for moving the menu, the plugin checks over a dozen of the most likely to be used hooks to see if a Genesis menu is there and then replaces it.

== Change Log ==

0.3 (7-6-2011 : Current)

* Added support for Genesis 1.7 theme options.

0.3 (5-12-2011)

* Added ability for plugin to replace menus automatically on over a dozen common Genesis Hooks including priority

0.1 (5-12-2011)

* First Public Release


== Screenshots ==
To Do: Take and add screen shots

== Special Thanks ==
I owe a huge debt of gratitude to all the folks at StudioPress, their themes make my life easier.