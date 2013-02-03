<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*   (c) 2013 Mohamed Mrassi and other contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

/**
* WSL welcome panel
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------

function wsl_admin_welcome_panel()
{
	if( isset( $_REQUEST["wsldwp"] ) && (int) $_REQUEST["wsldwp"] ){
		$wsldwp = (int) $_REQUEST["wsldwp"];

		update_option( "wsl_settings_welcome_panel_enabled", wsl_version() );

		return;
	}

	// if new user or wsl updated, then we display wsl welcome panel
	if( get_option( 'wsl_settings_welcome_panel_enabled' ) == wsl_version() ){ 
		return;
	}
?> 
<!-- 
	if you want to know if a UI was made by devloper, then here is a tip: he will always use tables

	//> wsl-w-panel is shamelessly borrowered and modified from wordpress welcome-panel
-->
<div id="wsl-w-panel">
	<a href="options-general.php?page=wordpress-social-login&wslp=<?php echo $wslp ?>&wsldwp=1" id="wsl-w-panel-dismiss"><?php _e("Dismiss this notice", 'wordpress-social-login') ?></a>
	
	<table width="100%" border="0" style="margin:0;padding:0;">
		<tr>
			<td width="10" valign="top"></td>
			<td width="300" valign="top">
				<b style="font-size: 16px;"><?php _e("Welcome!", 'wordpress-social-login') ?></b>
				<p>
					<?php _e("If you are still new to WordPress Social Login, we have provided a few walkthroughs to get you started", 'wordpress-social-login') ?>.
				</p>
			</td>
			<td width="40" valign="top"></td>
			<td width="260" valign="top">
				<br />
				<p>
					<b><?php _e("Get Started", 'wordpress-social-login') ?></b>
				</p>
				<ul style="margin-left:25px;">
					<li><?php _e('<a href="http://hybridauth.sourceforge.net/wsl/configure.html" target="_blank">Setup and Configuration</a>', 'wordpress-social-login') ?></li>
					<li><?php _e('<a href="http://hybridauth.sourceforge.net/wsl/customize.html" target="_blank">Customize WSL Widgets</a>', 'wordpress-social-login') ?></li>
					<li><?php _e('<a href="http://hybridauth.sourceforge.net/wsl/userdata.html" target="_blank">Manage users and contacts</a>', 'wordpress-social-login') ?></li> 
					<li><?php _e('<a href="http://hybridauth.sourceforge.net/wsl/index.html" target="_blank">WSL User Guide</a> and <a href="http://hybridauth.sourceforge.net/wsl/faq.html" target="_blank">FAQ</a>', 'wordpress-social-login') ?></li>  
				</ul>
			</td>
			<td width="" valign="top">
				<br />
				<p>
					<b><?php _e( sprintf( "What's New  WSL %s", wsl_version() ), 'wordpress-social-login') ?></b>
				</p>
				<ul style="margin-left:25px;">
					<li><?php _e('In a similar way to WordPress plugins, WSL now have <a href="options-general.php?page=wordpress-social-login&wslp=components">Components</a>', 'wordpress-social-login') ?>,</li>  
					<li><?php _e('<b>Email Validation</b> is replaced with <b>Profile Completion</b>', 'wordpress-social-login') ?>,</li>
					<li><?php _e('<b>User Moderation</b> made compatible with <a href="http://wordpress.org/extend/plugins/theme-my-login/" target="_blank">Theme My Login</a> plugin', 'wordpress-social-login') ?>,</li> 
					<li><?php _e('WSL is now made extensible, so make sure to check out the <a href="http://hybridauth.sourceforge.net/wsl/developer.html" target="_blank">Developer API</a>', 'wordpress-social-login') ?>,</li>
					<li><?php _e('A number of enhancements and new options now available', 'wordpress-social-login') ?>.</li>
				</ul>
			</td>
		</tr>
		<tr id="wsl-w-panel-updates-tr">
			<td colspan="5" style="border-top:1px solid #ccc;" id="wsl-w-panel-updates-td">
				&nbsp;
			</td>
		</tr>
	</table> 
</div>
<?php 
}

// --------------------------------------------------------------------
