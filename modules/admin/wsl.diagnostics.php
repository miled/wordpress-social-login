<div style="margin-left:20px;">
<form method="post" id="wsl_setup_form" action="options.php">  
<?php settings_fields( 'wsl-settings-group-development' ); ?>

	
<h3>Requirements test</h3> 

<p style="margin-left:25px;font-size: 14px;"> 
	In order for <b>WordPress Social Login</b> to work properly, your server should meet certain requirements. These "requirements" 
	<br />
	and "services" are usually offered by default by most "modern" web hosting providers, however some complications may 
	<br />
	occur with <b>shared hosting</b> and, or <b>custom wordpress installations</b>. 
	<br />
	The minimum server requirements are:
</p>
<ul style="margin-left:60px;">
	<li>PHP >= 5.2.0 installed</li> 
	<li>WSL Endpoint URLs reachable</li>
	<li>PHP's default SESSION handling</li>
	<li>PHP/CURL Extension enabled</li> 
	<li>PHP/JSON Extension enabled</li> 
	<li>PHP/REGISTER_GLOBALS Off</li> 
	<li>jQuery installed on WordPress backoffice</li> 
</ul>
<p style="margin-left:25px;margin-top:25px;"> 
	You can run the <b>WordPress Social Login Requirements Test</b> by clicking the button bellow:
	
	<br />
	<br />
	<a class="button-primary" href='<?php echo WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL ?>/services/diagnostics.php' target='_blank'>Run the plugin requirements test</a> 
</p> 

<br />
<hr />

<h3>Development mode</h3> 

<p style="margin-left:25px;"> 
	By enabling the development mode, this plugin will try generate and display a technical reports when something goes wrong.  
	<br />
	This report can help your figure out the root of any issues you may runs into, or you can also send it to the plugin developer. 
	<br />
	Its recommend to set the Development mode to <b style="color:red">Disabled</b> on production.
	

	<br />
	<br />
	<select name="wsl_settings_development_mode_enabled">
		<option <?php if(   get_option( 'wsl_settings_development_mode_enabled' ) ) echo "selected"; ?> value="1">Enabled</option>
		<option <?php if( ! get_option( 'wsl_settings_development_mode_enabled' ) ) echo "selected"; ?> value="0">Disabled</option> 
	</select>
	<input type="submit" class="button-primary" value="Save" />
</p>

</form>
</div>
