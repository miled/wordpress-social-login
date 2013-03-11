<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*    (c) 2011-2013 Mohamed Mrassi and contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

/**
* WSL Diagnostics module
*/

// --------------------------------------------------------------------

function wsl_component_diagnostics()
{
	// HOOKABLE: 
	do_action( "wsl_component_diagnostics_start" ); 
?>
<div style="margin-left:20px;">
	<form method="post" id="wsl_setup_form" action="options.php">  
		<?php settings_fields( 'wsl-settings-group-development' ); ?>

		<h3><?php _wsl_e("Requirements test", 'wordpress-social-login') ?></h3> 

		<p style="margin-left:25px;font-size: 14px;"> 
			<?php _wsl_e('In order for <b>WordPress Social Login</b> to work properly, your server should meet certain requirements. These "requirements" <br />and "services" are usually offered by default by most "modern" web hosting providers, however some complications may <br />occur with <b>shared hosting</b> and, or <b>custom wordpress installations</b>', 'wordpress-social-login') ?>. 
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
			<a class="button-primary" href='<?php echo WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL ?>/services/diagnostics.php' target='_blank'><?php _wsl_e("Run the plugin requirements test", 'wordpress-social-login') ?></a> 
			<a class="button-primary" href='<?php echo WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL ?>/services/siteinfo.php' target='_blank'><?php _wsl_e("Website Information", 'wordpress-social-login') ?></a>.
		</p> 

		<br />
		<hr />

		<h3><?php _wsl_e("Development mode", 'wordpress-social-login') ?></h3> 

		<p style="margin-left:25px;"> 
			<?php _wsl_e('By enabling the development mode, this plugin will try generate and display a technical reports when something goes wrong. <br />This report can help your figure out the root of any issues you may runs into, or you can also send it to the plugin developer. <br />Its recommend to set the Development mode to <b style="color:red">Disabled</b> on production.', 'wordpress-social-login') ?>

			<br />
			<br />
			<select name="wsl_settings_development_mode_enabled">
				<option <?php if(   get_option( 'wsl_settings_development_mode_enabled' ) ) echo "selected"; ?> value="1"><?php _wsl_e("Enabled", 'wordpress-social-login') ?></option>
				<option <?php if( ! get_option( 'wsl_settings_development_mode_enabled' ) ) echo "selected"; ?> value="0"><?php _wsl_e("Disabled", 'wordpress-social-login') ?></option> 
			</select>
			<input type="submit" class="button-primary" value="<?php _wsl_e("Save Settings", 'wordpress-social-login') ?>" />
		</p>
	</form>
</div>
<?php
	// HOOKABLE: 
	do_action( "wsl_component_diagnostics_end" );
}

wsl_component_diagnostics();

// --------------------------------------------------------------------	
