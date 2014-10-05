<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*    (c) 2011-2014 Mohamed Mrassi and contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
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
<style>
.wp-social-login-provider-list {
	padding:10px;
}

.wp-social-login-provider-list a{
	text-decoration: none;
}

.wp-social-login-provider-list img{
	border:0 none;
}
</style>

<div  id="post-body-content">

	<div id="namediv" class="stuffbox">
		<h3>
			<label><?php _wsl_e("Basic Settings", 'wordpress-social-login') ?></label>
		</h3>
		<div class="inside"> 

			<p> 
				<?php _wsl_e("<b>Connect with caption :</b> Change the content of the label to display above WSL widget", 'wordpress-social-login') ?>. 
			</p>

			<p> 
				<?php _wsl_e("<b>Social icon set :</b> WSL provides two set of icons to display on the widget", 'wordpress-social-login') ?>.
				<?php _wsl_e("You can also display the providers names instead of icons. This allow the customization of the widget to a great extent", 'wordpress-social-login') ?>.
			</p>

			<p> 
				<?php _wsl_e("<b>Users avatars :</b> Determines whether to show users avatars from social networks or to display the default ones", 'wordpress-social-login') ?>.

				<?php _wsl_e("Avatars display should work right out of the box with most WordPress themes, BuddyPress and bbPress", 'wordpress-social-login') ?>.
			</p>

			<table width="100%" border="0" cellpadding="5" cellspacing="2" style="border-top:1px solid #ccc;">
			  <tr>
				<td width="180" align="right"><strong><?php _wsl_e("Connect with caption", 'wordpress-social-login') ?> :</strong></td>
				<td> 
				<input type="text" class="inputgnrc" style="width:535px" value="<?php _wsl_e( get_option( 'wsl_settings_connect_with_label' ), 'wordpress-social-login' ); ?>" name="wsl_settings_connect_with_label" > 
				</td>
			  </tr>
			  <tr>
				<td align="right"><strong><?php _wsl_e("Social icon set", 'wordpress-social-login') ?> :</strong></td>
				<td> 
					<select name="wsl_settings_social_icon_set" style="width:535px">
						<option <?php if( get_option( 'wsl_settings_social_icon_set' ) ==   "wpzoom" ) echo "selected"; ?>   value="wpzoom"><?php _wsl_e("WPZOOM social networking icon set", 'wordpress-social-login') ?></option>
						<option <?php if( get_option( 'wsl_settings_social_icon_set' ) == "icondock" ) echo "selected"; ?> value="icondock"><?php _wsl_e("Icondock vector social media icons", 'wordpress-social-login') ?></option> 
						<option <?php if( get_option( 'wsl_settings_social_icon_set' ) ==     "none" ) echo "selected"; ?>     value="none"><?php _wsl_e("None, display providers names instead of icons", 'wordpress-social-login') ?></option> 
					</select> 
				</td>
			  </tr>
			  <tr>
				<td align="right"><strong><?php _wsl_e("Users avatars", 'wordpress-social-login') ?> :</strong></td>
				<td>
					<select name="wsl_settings_users_avatars" style="width:535px">
						<option <?php if( ! get_option( 'wsl_settings_users_avatars' ) ) echo "selected"; ?> value="0"><?php _wsl_e("Display the default WordPress avatars", 'wordpress-social-login') ?></option> 
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
			<label><?php _wsl_e("Advanced Settings", 'wordpress-social-login') ?></label>
		</h3>
		<div class="inside"> 
			<p> 
				<?php _wsl_e("<b>Redirect URL :</b> By default and after they authenticate, users will be automatically redirected to the page where they come from. If WSL wasn't able to identify where they come from (or if they used wp-login page to connect), then they will be redirected to <code>Redirect URL</code> instead", 'wordpress-social-login') ?>.
			</p>

			<p> 
				<?php _wsl_e("<b>Force redirection :</b> When set to <b>Yes</b>, users will be <b>always</b> redirected to <code>Redirect URL</code>", 'wordpress-social-login') ?>.
			</p>

			<p> 
				<?php _wsl_e("<b>Authentication display :</b> Determines how the authentication dialog is rendered. You can chose to open the dialog in a <b>popup</b> or to <b>in page</b>. (Authentication display was previously known as Authentication flow)", 'wordpress-social-login') ?>.
			</p>

			<p> 
				<?php _wsl_e("<b>Widget display :</b> Determines where you want to show the authentication widget", 'wordpress-social-login') ?>. 
			</p>

			<p> 
				<?php _wsl_e("<b>Notification :</b> Determines whether you want to receive a notification by mail when a new user is logged in via WSL", 'wordpress-social-login') ?>. 
			</p>

			<table width="100%" border="0" cellpadding="5" cellspacing="2" style="border-top:1px solid #ccc;">
			  <tr>
				<td width="180" align="right"><strong><?php _wsl_e("Redirect URL", 'wordpress-social-login') ?> :</strong></td>
				<td>
					<input type="text" name="wsl_settings_redirect_url" class="inputgnrc" style="width:535px" value="<?php echo get_option( 'wsl_settings_redirect_url' ); ?>"> 
				</td>
			  </tr>
			  <tr>
				<td align="right"><strong><?php _wsl_e("Force redirection", 'wordpress-social-login') ?> :</strong></td>
				<td>
					<select name="wsl_settings_force_redirect_url" style="width:100px">
						<option <?php if( get_option( 'wsl_settings_force_redirect_url' ) == 1 ) echo "selected"; ?> value="1"><?php _wsl_e("Yes", 'wordpress-social-login') ?></option> 
						<option <?php if( get_option( 'wsl_settings_force_redirect_url' ) == 2 ) echo "selected"; ?> value="2"><?php _wsl_e("No", 'wordpress-social-login') ?></option> 
					</select> 
				</td>
			  </tr>
			  <tr>
				<td align="right"><strong><?php _wsl_e("Authentication display", 'wordpress-social-login') ?> :</strong></td>
				<td>
					<select name="wsl_settings_use_popup" style="width:100px">
						<option <?php if( get_option( 'wsl_settings_use_popup' ) == 1 ) echo "selected"; ?> value="1"><?php _wsl_e("Popup", 'wordpress-social-login') ?></option> 
						<option <?php if( get_option( 'wsl_settings_use_popup' ) == 2 ) echo "selected"; ?> value="2"><?php _wsl_e("In Page", 'wordpress-social-login') ?></option> 
					</select> 
				</td>
			  </tr>
			  <tr>
				<td align="right"><strong><?php _wsl_e("Widget display", 'wordpress-social-login') ?> :</strong></td>
				<td>
					<select name="wsl_settings_widget_display" style="width:535px">
						<option <?php if( get_option( 'wsl_settings_widget_display' ) == 4 ) echo "selected"; ?> value="4"><?php _wsl_e("Do not display the widget anywhere, I'll use shortcodes", 'wordpress-social-login') ?></option> 
						<option <?php if( get_option( 'wsl_settings_widget_display' ) == 1 ) echo "selected"; ?> value="1"><?php _wsl_e("Display the widget in the comments area, login and register forms", 'wordpress-social-login') ?></option>
						<option <?php if( get_option( 'wsl_settings_widget_display' ) == 3 ) echo "selected"; ?> value="3"><?php _wsl_e("Display the widget only in the login and register forms", 'wordpress-social-login') ?></option> 
						<option <?php if( get_option( 'wsl_settings_widget_display' ) == 2 ) echo "selected"; ?> value="2"><?php _wsl_e("Display the widget only in the comments area", 'wordpress-social-login') ?></option> 
					</select>  
				</td>
			  </tr>
			  <tr>
				<td align="right"><strong><?php _wsl_e("Notification", 'wordpress-social-login') ?> :</strong></td>
				<td>
					<select name="wsl_settings_users_notification" style="width:535px">
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
			<label><?php _wsl_e("Custom CSS", 'wordpress-social-login') ?></label>
		</h3>
		<div class="inside"> 
			<p>
				<?php _wsl_e("To customize the default widget styles you can either: change the css in the <b>text area</b> bellow or add it to your website <b>theme</b> files", 'wordpress-social-login') ?>. 
			</p>
			<table width="100%" border="0" cellpadding="5" cellspacing="2" style="border-top:1px solid #ccc;">
			  <tr>  
				<td>
				<textarea style="width:100%;height:120px;margin-top:6px;" name="wsl_settings_authentication_widget_css"><?php echo get_option( 'wsl_settings_authentication_widget_css' );  ?></textarea>
				<br />
				<p><?php _wsl_e("The basic widget markup is the following", 'wordpress-social-login') ?>:</p>
<pre style="background-color: #eaffdc;border:1px solid #60cf4e; border-radius: 3px;padding: 10px;margin-top:5px;margin-bottom:0px;">
&lt;div class=&quot;<code>wp-social-login-widget</code>&quot;&gt;

   &lt;div class=&quot;<code>wp-social-login-connect-with</code>&quot;&gt;{connect_with_caption}&lt;/div&gt;

   &lt;div class=&quot;<code>wp-social-login-provider-list</code>&quot;&gt;

       &lt;a class=&quot;<code>wp-social-login-provider wp-social-login-provider-facebook</code>&quot;&gt;
          &lt;img src=&quot;{provider_icon_facebook}&quot; /&gt;
       &lt;/a&gt;

       &lt;a class=&quot;<code>wp-social-login-provider wp-social-login-provider-google</code>&quot;&gt;
          &lt;img src=&quot;{provider_icon_google}&quot; /&gt;
       &lt;/a&gt;

       &lt;a class=&quot;<code>wp-social-login-provider wp-social-login-provider-twitter</code>&quot;&gt;
          &lt;img src=&quot;{provider_icon_twitter}&quot; /&gt;
       &lt;/a&gt;

   &lt;/div&gt; &lt;!-- / div.wp-social-login-connect-options --&gt;

   &lt;div class=&quot;<code>wp-social-login-widget-clearing</code>&quot;&gt;&lt;/div&gt;

&lt;/div&gt; &lt;!-- / div.wp-social-login-widget --&gt;
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
