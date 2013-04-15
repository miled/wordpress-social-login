<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*    (c) 2011-2013 Mohamed Mrassi and contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

/**
* Social networks configuration and setup
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 

// --------------------------------------------------------------------

function wsl_component_networks()
{
	// HOOKABLE: 
	do_action( "wsl_component_networks_start" );

	include "wsl.components.networks.setup.php";
	include "wsl.components.networks.addmore.php";
	include "wsl.components.networks.whyhello.php";
	include "wsl.components.networks.basicinsights.php";

	wsl_admin_welcome_panel();
?>
<form method="post" id="wsl_setup_form" action="options.php"> 
	<?php settings_fields( 'wsl-settings-group' ); ?>

	<div class="metabox-holder columns-2" id="post-body">
		<table width="100%"> 
			<tr valign="top">
				<td> 
					<div id="post-body-content">
						<?php
							wsl_component_networks_setup();
						?>
						<a name="wslsettings"></a> 
					</div>
				</td>
				<td width="10"></td>
				<td width="400">
					<?php
						wsl_component_networks_whyhello();

						wsl_component_networks_addmore();

						wsl_component_networks_basicinsights();
					?>
				</td>
			</tr>
		</table> 
	</div>
</form>
<?php
	// HOOKABLE: 
	do_action( "wsl_component_networks_end" );
}

wsl_component_networks();

// --------------------------------------------------------------------	
