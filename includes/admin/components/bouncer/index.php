<?php
/*!
* WordPress Social Login
*
* http://miled.github.io/wordpress-social-login/ | https://github.com/miled/wordpress-social-login
*  (c) 2011-2015 Mohamed Mrassi and contributors | http://wordpress.org/plugins/wordpress-social-login/
*/

/**
* The Bouncer our friend whos trying to be funneh
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;  

// --------------------------------------------------------------------

function wsl_component_bouncer()
{
	// HOOKABLE: 
	do_action( "wsl_component_bouncer_start" );

	include "wsl.components.bouncer.setup.php";
	include "wsl.components.bouncer.sidebar.php";
?>
<form method="post" id="wsl_setup_form" action="options.php"> 
	<?php settings_fields( 'wsl-settings-group-bouncer' ); ?> 

	<div class="metabox-holder columns-2" id="post-body">
		<table width="100%">
			<tr valign="top">
				<td>
					<?php
						wsl_component_bouncer_setup();
					?>
				</td>
				<td width="10"></td>
				<td width="400">
					<?php
						wsl_component_bouncer_sidebar();
					?>
				</td>
			</tr>
		</table>
	</div> 
</form>
<?php
	// HOOKABLE: 
	do_action( "wsl_component_bouncer_end" );
}

wsl_component_bouncer();

// --------------------------------------------------------------------	
