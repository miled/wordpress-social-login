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

function wsl_component_loginwidget_setup()
{
	// HOOKABLE: 
	do_action( "wsl_component_loginwidget_setup_start" );
?>
<div  id="post-body-content">

	<div id="namediv" class="stuffbox">
		<h3>
			<label for="name"><?php _wsl_e("Basic Settings", 'wordpress-social-login') ?></label>
		</h3>
		<div class="inside"> 
			<table width="100%" border="0" cellpadding="5" cellspacing="2" > 
			  <tr>
				<td width="135" align="right"><strong><?php _wsl_e("Connect with caption", 'wordpress-social-login') ?> :</strong></td>
				<td> 
				<input type="text" class="inputgnrc" value="<?php echo get_option( 'wsl_settings_connect_with_label' ); ?>" name="wsl_settings_connect_with_label" > 
				</td>
			  </tr>
			  <tr>
				<td align="right"><strong><?php _wsl_e("Social icon set", 'wordpress-social-login') ?> :</strong></td>
				<td> 
					<select name="wsl_settings_social_icon_set" style="width:400px">
						<option <?php if( get_option( 'wsl_settings_social_icon_set' )   == "wpzoom" ) echo "selected"; ?>   value="wpzoom"><?php _wsl_e("WPZOOM social networking icon set", 'wordpress-social-login') ?></option>
						<option <?php if( get_option( 'wsl_settings_social_icon_set' ) == "icondock" ) echo "selected"; ?> value="icondock"><?php _wsl_e("Icondock vector social media icons", 'wordpress-social-login') ?></option> 
					</select> 
				</td>
			  </tr>
			  <tr>
				<td align="right"><strong><?php _wsl_e("Users avatars", 'wordpress-social-login') ?> :</strong></td>
				<td>
					<select name="wsl_settings_users_avatars" style="width:400px">
						<option <?php if( ! get_option( 'wsl_settings_users_avatars' ) ) echo "selected"; ?> value="0"><?php _wsl_e("Display the default users avatars", 'wordpress-social-login') ?></option> 
						<option <?php if(   get_option( 'wsl_settings_users_avatars' ) ) echo "selected"; ?> value="1"><?php _wsl_e("Display users avatars from social networks when available", 'wordpress-social-login') ?></option>
					</select> 
				</td>
			  </tr> 
			</table> 
			<br>  
		</div>
	</div>

	<div id="namediv" class="stuffbox">
		<h3>
			<label for="name"><?php _wsl_e("Advanced Settings", 'wordpress-social-login') ?></label>
		</h3>
		<div class="inside"> 
			<table width="100%" border="0" cellpadding="5" cellspacing="2" >
			  <tr>
				<td width="135" align="right"><strong><?php _wsl_e("Redirect URL", 'wordpress-social-login') ?> :</strong></td>
				<td>
					<input type="text" name="wsl_settings_redirect_url" value="<?php echo get_option( 'wsl_settings_redirect_url' ); ?>" class="inputgnrc"> 
				</td>
			  </tr>
			  <tr>
				<td align="right"><strong><?php _wsl_e("Authentication flow", 'wordpress-social-login') ?> :</strong></td>
				<td>
					<select name="wsl_settings_use_popup" style="width:400px">
						<option <?php if( get_option( 'wsl_settings_use_popup' ) == 1 ) echo "selected"; ?> value="1"><?php _wsl_e("Using popup window", 'wordpress-social-login') ?></option> 
						<option <?php if( get_option( 'wsl_settings_use_popup' ) == 2 ) echo "selected"; ?> value="2"><?php _wsl_e("No popup window", 'wordpress-social-login') ?></option> 
					</select> 
				</td>
			  </tr>
			  <tr>
				<td align="right"><strong><?php _wsl_e("Widget display", 'wordpress-social-login') ?> :</strong></td>
				<td>
					<select name="wsl_settings_widget_display" style="width:400px">
						<option <?php if( get_option( 'wsl_settings_widget_display' ) == 1 ) echo "selected"; ?> value="1"><?php _wsl_e("Display the widget in the comments area, login and register forms", 'wordpress-social-login') ?></option> 
						<option <?php if( get_option( 'wsl_settings_widget_display' ) == 2 ) echo "selected"; ?> value="2"><?php _wsl_e("Display the widget ONLY in the comments area", 'wordpress-social-login') ?></option> 
						<option <?php if( get_option( 'wsl_settings_widget_display' ) == 3 ) echo "selected"; ?> value="3"><?php _wsl_e("Display the widget ONLY in the login form", 'wordpress-social-login') ?></option> 
					</select>  
				</td>
			  </tr>
			  <tr>
				<td align="right"><strong><?php _wsl_e("Notification", 'wordpress-social-login') ?> :</strong></td>
				<td>
					<select name="wsl_settings_users_notification" style="width:400px">
						<option <?php if( ! get_option( 'wsl_settings_users_notification' )      ) echo "selected"; ?> value="0"><?php _wsl_e("No notification", 'wordpress-social-login') ?></option> 
						<option <?php if(   get_option( 'wsl_settings_users_notification' ) == 1 ) echo "selected"; ?> value="1"><?php _wsl_e("Notify ONLY the blog admin of a new user", 'wordpress-social-login') ?></option> 
					</select> 
				</td>
			  </tr> 
			</table>
			<br>  
		</div>
	</div>

	<div id="namediv" class="stuffbox">
		<h3>
			<label for="name"><?php _wsl_e("Custom CSS", 'wordpress-social-login') ?></label>
		</h3>
		<div class="inside"> 
			<table width="100%" border="0" cellpadding="5" cellspacing="2" >
			  <tr>
				<td width="135" align="right" valign="top"><strong><?php _wsl_e("Widget CSS", 'wordpress-social-login') ?> :</strong></td>
				<td>
				<?php _wsl_e("To customize the default widget styles you can either: edit the css file <strong>/wordpress-social-login/assets/css/style.css</strong>, or change it from this text area", 'wordpress-social-login') ?>. 
				<br />
				<textarea style="width:100%;height:120px;margin-top:6px;" name="wsl_settings_authentication_widget_css"><?php echo get_option( 'wsl_settings_authentication_widget_css' );  ?></textarea>
				<br />
				<p><?php _wsl_e("The basic widget markup is the following", 'wordpress-social-login') ?>:</p>
<pre style="background-color: #eaffdc;border:1px solid #60cf4e; border-radius: 3px;padding: 10px;margin-top:5px;margin-bottom:0px;">
&lt;span id=&quot;<code>wp-social-login-connect-with</code>&quot;&gt;{connect_with_caption}&lt;/span&gt;

&lt;div id=&quot;<code>wp-social-login-connect-options</code>&quot;&gt;
    &lt;a class=&quot;<code>wsl_connect_with_provider</code>&quot;&gt;
        &lt;img src=&quot;{provider_icon_facebook}&quot; /&gt;
    &lt;/a&gt;

    &lt;a class=&quot;<code>wsl_connect_with_provider</code>&quot;&gt;
        &lt;img src=&quot;{provider_icon_google}&quot; /&gt;
    &lt;/a&gt;

    etc.
&lt;/div&gt;
</pre>
				</td>
			  </tr> 
			</table>
			<br>  
		</div>
	</div>

	<br />

	<div style="margin-left:5px;margin-top:-20px;"> 
		<input type="submit" class="button-primary" value="<?php _wsl_e("Save Settings", 'wordpress-social-login') ?>" /> 
	</div>

</div>
<?php
	// HOOKABLE: 
	do_action( "wsl_component_loginwidget_setup_end" );
}

// --------------------------------------------------------------------	
