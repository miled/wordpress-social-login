<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*    (c) 2011-2013 Mohamed Mrassi and contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

/**
* Widget Customization
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 

// --------------------------------------------------------------------

function wsl_component_loginwidget()
{
	// HOOKABLE: 
	do_action( "wsl_component_loginwidget_start" );

	include "wsl.components.loginwidget.setup.php";
	include "wsl.components.loginwidget.sidebar.php";

	wsl_admin_welcome_panel();
?>
<form method="post" id="wsl_setup_form" action="options.php"> 
	<?php settings_fields( 'wsl-settings-group-customize' ); ?> 

	<div class="metabox-holder columns-2" id="post-body">
		<table width="100%"> 
			<tr valign="top">
				<td>
					<?php
						wsl_component_loginwidget_setup();
					?> 
				</td>
				<td width="10"></td>
				<td width="400">
					<?php 
						wsl_component_loginwidget_sidebar();
					?>
				</td>
			</tr>
		</table>
	</div>
</form>
<?php
	// HOOKABLE: 
	do_action( "wsl_component_loginwidget_end" );
}

wsl_component_loginwidget();

// --------------------------------------------------------------------	
