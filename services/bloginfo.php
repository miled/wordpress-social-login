<?php
	// load wp-load.php
	require_once( dirname( dirname( dirname( dirname( __FILE__ )))) . '/../wp-load.php' ); 

	// only display for admin
	if ( ! current_user_can('manage_options') ) {
		wsl_render_notices_pages( 'You do not have sufficient permissions to access this page.' );
	}
?>
<pre>
name        = <?php bloginfo("name") ?> 
description = <?php bloginfo("description") ?> 
admin_email = <?php bloginfo("admin_email") ?> 
url         = <?php bloginfo("url") ?> 

charset     = <?php bloginfo("charset") ?> 
html_type   = <?php bloginfo("html_type") ?> 
language    = <?php bloginfo("language") ?> 
version     = <?php bloginfo("version") ?>
</pre>