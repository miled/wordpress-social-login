<?php
/*!
* WordPress Social Login
*
* http://miled.github.io/wordpress-social-login/ | https://github.com/miled/wordpress-social-login
*  (c) 2011-2015 Mohamed Mrassi and contributors | http://wordpress.org/plugins/wordpress-social-login/
*/

/**
* WSL tools
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 

// --------------------------------------------------------------------

function wsl_component_tools()
{
	// HOOKABLE:
	do_action( "wsl_component_tools_start" );

	include "wsl.components.tools.actions.php"; 
	include "wsl.components.tools.sidebar.php";

	$action = isset( $_REQUEST['do'] ) ? $_REQUEST['do'] : null ;

	if( in_array( $action, array( 'diagnostics', 'sysinfo', 'uninstall' , 'repair' ) ) )
	{
		if( isset( $_REQUEST['_wpnonce'] ) && wp_verify_nonce( $_REQUEST['_wpnonce'] ) )
		{
			include "wsl.components.tools.actions.job.php";

			do_action( 'wsl_component_tools_do_' . $action );
		}
		else
		{
			?>
				<div style="margin: 4px 0 20px;" class="fade error wsl-error-db-tables">
					<p>
						<?php _wsl_e('The URL nonce is not valid', 'wordpress-social-login') ?>! 
					</p>
				</div>	
			<?php
		}
	}
	else
	{
		?> 
			<div class="metabox-holder columns-2" id="post-body">
				<table width="100%"> 
					<tr valign="top">
						<td> 
							<?php
								wsl_component_tools_sections();
							?>
						</td>
						<td width="10"></td>
						<td width="400">
							<?php 
								wsl_component_tools_sidebar();
							?>
						</td>
					</tr>
				</table>
			</div>
		<?php
	}

	// HOOKABLE: 
	do_action( "wsl_component_tools_end" );
}

// --------------------------------------------------------------------	

wsl_component_tools();
