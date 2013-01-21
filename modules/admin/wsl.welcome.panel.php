<?php
	// wsl welcome panel
	// if new user or wsl updated, then we display wsl welcome panel
	if( get_option( 'wsl_settings_welcome_panel_enabled' ) != $WORDPRESS_SOCIAL_LOGIN_VERSION ){ 
?>
<!-- 
	if you want to know if a UI was made by devloper, then here is a tip: he will always use tables

	//> wsl-w-panel is shamelessly borrowered and modified from wordpress welcome-panel
-->
<div id="wsl-w-panel">
	<a href="options-general.php?page=wordpress-social-login&wslp=<?php echo $wslp ?>&wsldwp=1" id="wsl-w-panel-dismiss">Dismiss this notice</a>
	
	<table width="100%" border="0" style="margin:0;padding:0;">
		<tr>
			<td width="10" valign="top"></td>
			<td width="300" valign="top">
				<b style="font-size: 16px;">Welcome!</b>
				<p>
					If you are still new to WordPress Social Login, we have provided a few walkthroughs to get you started.
				</p>
			</td>
			<td width="40" valign="top"></td>
			<td width="260" valign="top">
				<b>Get Started</b>

				<ul style="margin-left:25px;">
					<li><a href="http://hybridauth.sourceforge.net/wsl/configure.html" target="_blank">Setup and Configuration</a></li>
					<li><a href="http://hybridauth.sourceforge.net/wsl/customize.html" target="_blank">Customize WSL Widgets</a></li>
					<li><a href="http://hybridauth.sourceforge.net/wsl/userdata.html" target="_blank">Manage users and contacts</a></li> 
					<li><a href="http://hybridauth.sourceforge.net/wsl/index.html" target="_blank">WSL User Guide</a> and <a href="http://hybridauth.sourceforge.net/wsl/faq.html" target="_blank">FAQ</a></li>  
				</ul>
			</td>
			<td width="" valign="top">
				<b>What's New  WSL <?php echo $WORDPRESS_SOCIAL_LOGIN_VERSION ?></b>

				<ul style="margin-left:25px;">
					<li>Managing WSL users,</li> 
					<li>Import WSL users contact list from Google Gmail, Facebook, Live and LinkedIn,</li>  
					<li>An entirely reworked user interface,</li> 
					<li>Improving the documentation and guides,</li> 
					<li>Introducing a new module, and the long awaited, <a href="http://hybridauth.sourceforge.net/wsl/bouncer.html" target="_blank">The bouncer</a>,</li> 
					<li>And even more customization options are now available.</li>  
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
?>
<script>
	// check for new versions and updates
	jQuery.getScript("http://hybridauth.sourceforge.net/wsl/wsl.version.check.and.updates.php?v=<?php echo $WORDPRESS_SOCIAL_LOGIN_VERSION ?>");
</script> 
