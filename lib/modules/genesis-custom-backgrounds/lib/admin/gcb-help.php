<?php

// documentation for sliders manager page
function gcb_help( $contextual_help , $screen_id , $screen ) {	

		$contextual_help = __( '
			<h3>Genesis Custom Backgrounds</h3>		
			<p>Genesis Custom Backgrounds creates an option to enable selection of a default background from the sets of backgrounds provided by <a href="http://wpsmith.net/go/studiopress" alt="StudioPress">StudioPress</a>). Currently, it is broken down to Dark and Light backgrounds with subfolders. You can easily provide the user more options by simply uploading more backgrounds with in either the light or dark folders. Upload using the following structure, folder: camo & files: camo-1.png, camo-2.png, and camo-3.png (in all lowercase).</p>
			<h4>How do I add more backgrounds?</h4>
			<ol>
			<li>Access  /plugins/genesis-custom-backgrounds/lib/dark (or light)</li>
			<li>Create a new directory for you files, making sure it is lower case (e.g., camo so that /plugins/genesis-custom-backgrounds/lib/dark/camo/)</li>
			<li>FTP your files to the new directory - NOTE: all file names must be lower case - i.e. camo-1.png, camo-2.png, and camo-3.png</li>
			</ol>

			<h4>If files are FTPed to the plugin folder (/plugins/genesis-custom-backgrounds/lib/dark), will they be lost on a plugin update?</h4>
			<p>No, updates only affect files being updated.</p>

			<h4>Do you have future plans for this plugin?</h4>
			<p>Not currently. If you have suggestions, please feel free to <a href="http://wpsmith.net/contact/" title="Contact Travis">contact me</a>.</p>
        ' , GCB_DOMAIN );
		return $contextual_help;
}

if ( isset( $_GET['page'] ) && $_GET['page'] == 'gcb-settings' ) {
	add_action( 'contextual_help' , 'gcb_help' , 10 , 3 );
}

?>