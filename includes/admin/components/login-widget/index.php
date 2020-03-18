<?php
/*!
* WordPress Social Login
*
* https://miled.github.io/wordpress-social-login/ | https://github.com/miled/wordpress-social-login
*   (c) 2011-2020 Mohamed Mrassi and contributors | https://wordpress.org/plugins/wordpress-social-login/
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
