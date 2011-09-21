=== Genesis Custom Backgrounds ===
Contributors: wpsmith
Plugin URI: http://www.wpsmith.net/genesis-custom-backgrounds
Donate link: http://www.wpsmith.net/donation
Tags: custom background, custom backgrounds, backgrounds, genesis, studiopress, genesiswp
Requires at least: 3.0
Tested up to: 3.2

This plugin provides the option to select a default custom background.

== Description ==

By default, [Genesis](http://wpsmith.net/go/genesis "Genesis") doesn't turn on WordPress Custom Backgrounds. Genesis Custom Backgrounds enables custom backgrounds and creates an option to enable selection of a default background from the sets of backgrounds provided by [StudioPress](http://wpsmith.net/go/studiopress "StudioPress"). Currently, it is broken down to Dark and Light backgrounds with subfolders. You can easily provide the user more options by simply uploading more backgrounds with in either the light or dark folders. Upload using the following structure, folder: camo & files: camo-1.png, camo-2.png, and camo-3.png (in all lowercase). Furthermore, you can also upload a custom background via the WordPress Custom Background via Appearance > Background. You can easily add customization ability for the site to easily switch default backgrounds or upload their own (which will always overwrite the default).

IMPORTANT: 
**You must have [Genesis](http://wpsmith.net/go/genesis "Purchase Genesis") installed. Click [here](http://wpsmith.net/go/genesis "Purchase Genesis") to purchase [Genesis](http://wpsmith.net/go/genesis "Purchase Genesis")**


== Installation ==

1. Upload the entire `genesis-custom-backgrounds` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Make Selections via Genesis > Genesis Backgrounds

== Frequently Asked Questions ==

= How do I add more backgrounds? =
1. Access  /plugins/genesis-custom-backgrounds/lib/dark (or light)
1. Create a new directory for you files, making sure it is lower case (e.g., camo so that /plugins/genesis-custom-backgrounds/lib/dark/camo/)
1. FTP your files to the new directory - NOTE: all file names must be lower case - i.e. camo-1.png, camo-2.png, and camo-3.png

= If files are FTPed to the plugin folder (/plugins/genesis-custom-backgrounds/lib/dark), will they be lost on a plugin update? =
No, updates only affect files being updated.

= Do you have future plans for this plugin? =
Only one thing: Add the ability to deactivate backgrounds for administrators ('manage_options') so only "active" backgrounds appear for other members (those who cannot manage_options).
If you have suggestions, please feel free to [contact me](http://wpsmith.net/contact/ "Contact Travis")


== Screenshots ==

1. Genesis Backgrounds

== Changelog ==

= 0.3 =
* Added more information to readme.txt, removed auto-default

= 0.1-0.2 =
* Private Beta

== Special Thanks ==
I owe a huge debt of gratitude to all the folks at [StudioPress](http://wpsmith.net/go/studiopress "StudioPress"), their [themes](http://wpsmith.net/go/spthemes "StudioPress Themes") make life easier.