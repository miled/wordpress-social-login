<?php
/*!
* WordPress Social Login
*
* http://miled.github.io/wordpress-social-login/ | https://github.com/miled/wordpress-social-login
*  (c) 2011-2015 Mohamed Mrassi and contributors | http://wordpress.org/plugins/wordpress-social-login/
*/

/**
* Documentation and stuff
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 

// --------------------------------------------------------------------

function wsl_component_help()
{
	// HOOKABLE: 
	do_action( "wsl_component_help_start" ); 

	include "wsl.components.help.reference.php";
	include "wsl.components.help.sidebar.php";
?>
<div class="metabox-holder columns-2" id="post-body">
	<table width="100%"> 
		<tr valign="top">
			<td>
				<?php
					wsl_component_help_reference();
				?> 
			</td>
			<td width="10"></td>
			<td width="400">
				<?php 
					wsl_component_help_sidebar();
				?>
			</td>
		</tr>
	</table>
</div>
<?php
	// HOOKABLE: 
	do_action( "wsl_component_help_end" );
}

wsl_component_help();

// --------------------------------------------------------------------	
