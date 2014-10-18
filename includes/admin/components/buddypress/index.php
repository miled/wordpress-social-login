<?php
/*!
* WordPress Social Login
*
* http://miled.github.io/wordpress-social-login/ | https://github.com/miled/wordpress-social-login
*  (c) 2011-2014 Mohamed Mrassi and contributors | http://wordpress.org/plugins/wordpress-social-login/
*/

/**
* BuddyPress integration.
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 

// --------------------------------------------------------------------

function wsl_component_buddypress()
{
	// HOOKABLE: 
	do_action( "wsl_component_buddypress_start" ); 

	include "wsl.components.buddypress.setup.php";
	include "wsl.components.buddypress.sidebar.php";

	if( ! function_exists( 'bp_has_profile' ) ){
		include "wsl.components.buddypress.notfound.php";

		return wsl_component_buddypress_notfound();
	}
?>
<form method="post" id="wsl_setup_form" action="options.php"> 
	<?php settings_fields( 'wsl-settings-group-buddypress' ); ?> 

	<div class="metabox-holder columns-2" id="post-body">
		<table width="100%"> 
			<tr valign="top">
				<td>
					<?php
						wsl_component_buddypress_setup();
					?> 
				</td>
				<td width="10"></td>
				<td width="400">
					<?php 
						wsl_component_buddypress_sidebar();
					?>
				</td>
			</tr>
		</table>
	</div>
</form>
<?php
	// HOOKABLE: 
	do_action( "wsl_component_buddypress_end" );
}

wsl_component_buddypress();

// --------------------------------------------------------------------	
