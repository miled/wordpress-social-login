<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*    (c) 2011-2014 Mohamed Mrassi and contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

/**
* WSL Diagnostics module
*/

// --------------------------------------------------------------------

function wsl_component_diagnostics()
{
	// HOOKABLE: 
	do_action( "wsl_component_diagnostics_start" ); 
	
	$wsl_settings_development_mode_enabled = get_option( 'wsl_settings_development_mode_enabled' ); 
?> 
<div style="padding: 20px; border: 1px solid #ddd; background-color: #fff;">
	<h3><?php _wsl_e("Requirements test", 'wordpress-social-login') ?></h3> 

	<p style="margin-left:25px;font-size: 14px;"> 
		<?php _wsl_e('In order for <b>WordPress Social Login</b> to work properly, your server should meet certain requirements. These "requirements" and "services" are usually offered by default by most "modern" web hosting providers, however some complications may occur with <b>shared hosting</b> and, or <b>custom wordpress installations</b>', 'wordpress-social-login') ?>. 
	</p>
	<p style="margin-left:25px;font-size: 14px;"> 
		<?php _wsl_e("The minimum server requirements are", 'wordpress-social-login') ?>:
	</p>
	<ul style="margin-left:60px;">
		<li><?php _wsl_e("PHP >= 5.2.0 installed", 'wordpress-social-login') ?></li> 
		<li><?php _wsl_e("WSL Endpoint URLs reachable", 'wordpress-social-login') ?></li>
		<li><?php _wsl_e("PHP's default SESSION handling", 'wordpress-social-login') ?></li>
		<li><?php _wsl_e("PHP/CURL/SSL Extension enabled", 'wordpress-social-login') ?></li> 
		<li><?php _wsl_e("PHP/JSON Extension enabled", 'wordpress-social-login') ?></li> 
		<li><?php _wsl_e("PHP/REGISTER_GLOBALS Off", 'wordpress-social-login') ?></li> 
		<li><?php _wsl_e("jQuery installed on WordPress backoffice", 'wordpress-social-login') ?></li> 
	</ul>
	<p style="margin-left:25px;margin-top:25px;"> 
		<?php _wsl_e("You can run the <b>WordPress Social Login Requirements Test</b> by clicking the button bellow", 'wordpress-social-login') ?>:
		
		<br />
		<br />
		<a class="button-primary" href='<?php echo WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL ?>/utilities/diagnostics.php' target='_blank'><?php _wsl_e("Run the plugin requirements test", 'wordpress-social-login') ?></a> 
		<a class="button-primary" href='<?php echo WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL ?>/utilities/siteinfo.php' target='_blank'><?php _wsl_e("Website Information", 'wordpress-social-login') ?></a>.
	</p> 
</div>

<br />

<a name="devmode"></a>

<div style="padding: 20px; border: 1px solid #ddd; background-color: #fff;">
	<h3 style="color: #da4f49;"><?php _wsl_e("Development mode", 'wordpress-social-login') ?></h3> 

	<p>
		<?php _wsl_e('When <b>Development Mode</b> is enabled, this plugin will display a debugging area on the footer of admin interfaces. <b>Development Mode</b> will also try generate and display a technical reports when something goes wrong. This report can help you figure out the root of the issues you may runs into', 'wordpress-social-login') ?>.
	</p>

	<p>
		<?php _wsl_e('Please, do not enable <b>Development Mode</b>, unless you are a developer or you have basic PHP knowledge', 'wordpress-social-login') ?>.
	</p>

	<p>
		<?php _wsl_e('For security reasons, <b>Development Mode</b> will auto switch to <b>Disabled</b> each time the plugin is <b>reactivated</b>', 'wordpress-social-login') ?>.
	</p>

	<p>
		<?php _wsl_e('It\'s highly recommended to keep the <b>Development Mode</b> <b style="color:#da4f49">Disabled</b> on production as it would be a security risk otherwise', 'wordpress-social-login') ?>.
	</p>

	<form method="post" id="wsl_setup_form" action="options.php" <?php if( ! $wsl_settings_development_mode_enabled ) { ?>onsubmit="return confirm('Do you really want to enable Development Mode?\n\nPlease confirm that you have read and understood the abovementioned by clicking OK.');"<?php } ?>>  
		<?php settings_fields( 'wsl-settings-group-development' ); ?>

		<select name="wsl_settings_development_mode_enabled">
			<option <?php if(   $wsl_settings_development_mode_enabled ) echo "selected"; ?> value="1"><?php _wsl_e("Enabled", 'wordpress-social-login') ?></option>
			<option <?php if( ! $wsl_settings_development_mode_enabled ) echo "selected"; ?> value="0"><?php _wsl_e("Disabled", 'wordpress-social-login') ?></option> 
		</select>

		<input type="submit" class="button-primary" style="background-color: #da4f49;border-color: #bd362f;text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);color: #ffffff;" value="<?php _wsl_e("Save Settings", 'wordpress-social-login') ?>" />
	</form>
</div>

<?php
	// HOOKABLE: 
	do_action( "wsl_component_diagnostics_end" );
}

wsl_component_diagnostics();

// --------------------------------------------------------------------	
