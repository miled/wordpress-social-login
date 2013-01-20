<form method="post" id="wsl_setup_form" action="options.php"> 
<?php settings_fields( 'wsl-settings-group-customize' ); ?> 

<div class="metabox-holder columns-2" id="post-body">

<table width="100%">
<tbody>
<tr valign="top">
<td>
							
<div  id="post-body-content"> 
 
	<div id="namediv" class="stuffbox">
		<h3>
			<label for="name">Basic Settings</label>
		</h3>
		<div class="inside"> 
			<table width="100%" border="0" cellpadding="5" cellspacing="2" > 
			  <tr>
				<td width="135" align="right"><strong>Connect with caption :</strong></td>
				<td> 
				<input type="text" class="inputgnrc" value="<?php echo get_option( 'wsl_settings_connect_with_label' ); ?>" name="wsl_settings_connect_with_label" > 
				</td>
			  </tr>

			  <tr>
				<td align="right"><strong>Social icon set :</strong></td>
				<td> 
					<select name="wsl_settings_social_icon_set" style="width:400px">
						<option <?php if( get_option( 'wsl_settings_social_icon_set' )   == "wpzoom" ) echo "selected"; ?>   value="wpzoom">WPZOOM social networking icon set</option>
						<option <?php if( get_option( 'wsl_settings_social_icon_set' ) == "icondock" ) echo "selected"; ?> value="icondock">Icondock vector social media icons</option> 
					</select> 
				</td>
			  </tr>
			  
			  <tr>
				<td align="right"><strong>Users avatars :</strong></td>
				<td>
					<select name="wsl_settings_users_avatars" style="width:400px">
						<option <?php if( ! get_option( 'wsl_settings_users_avatars' ) ) echo "selected"; ?> value="0">Display the default users avatars</option> 
						<option <?php if(   get_option( 'wsl_settings_users_avatars' ) ) echo "selected"; ?> value="1">Display users avatars from social networks when available</option>
					</select> 
				</td>
			  </tr> 
			</table> 
			<br>  
		</div>
	</div>
	
	<div id="namediv" class="stuffbox">
		<h3>
			<label for="name">Advanced Settings</label>
		</h3>
		<div class="inside"> 
			<table width="100%" border="0" cellpadding="5" cellspacing="2" >
			  <tr>
				<td width="135" align="right"><strong>Redirect URL :</strong></td>
				<td>
					<input type="text" name="wsl_settings_redirect_url" value="<?php echo get_option( 'wsl_settings_redirect_url' ); ?>" class="inputgnrc"> 
				</td>
			  </tr>

			  <tr>
				<td align="right"><strong>Authentication flow :</strong></td>
				<td>
					<select name="wsl_settings_use_popup" style="width:400px">
						<option <?php if( get_option( 'wsl_settings_use_popup' ) == 1 ) echo "selected"; ?> value="1">Using popup window</option> 
						<option <?php if( get_option( 'wsl_settings_use_popup' ) == 2 ) echo "selected"; ?> value="2">No popup window</option> 
					</select> 
				</td>
			  </tr> 
			 
			  <tr>
				<td align="right"><strong>Widget display :</strong></td>
				<td>
					<select name="wsl_settings_widget_display" style="width:400px">
						<option <?php if( get_option( 'wsl_settings_widget_display' ) == 1 ) echo "selected"; ?> value="1">Display the widget in the comments area, login and register forms</option> 
						<option <?php if( get_option( 'wsl_settings_widget_display' ) == 2 ) echo "selected"; ?> value="2">Display the widget ONLY in the comments area</option> 
						<option <?php if( get_option( 'wsl_settings_widget_display' ) == 3 ) echo "selected"; ?> value="3">Display the widget ONLY in the login form</option> 
					</select>  
				</td>
			  </tr> 

			  <tr>
				<td align="right"><strong>Notification :</strong></td>
				<td>
					<select name="wsl_settings_users_notification" style="width:400px">
						<option <?php if( ! get_option( 'wsl_settings_users_notification' )      ) echo "selected"; ?> value="0">No notification</option> 
						<option <?php if(   get_option( 'wsl_settings_users_notification' ) == 1 ) echo "selected"; ?> value="1">Notify ONLY the blog admin of a new user</option> 
					</select> 
				</td>
			  </tr> 
			</table>

			<br>  
		</div>
	</div>
	
	<div id="namediv" class="stuffbox">
		<h3>
			<label for="name">Custom CSS</label>
		</h3>
		<div class="inside"> 
			<table width="100%" border="0" cellpadding="5" cellspacing="2" >
			  <tr>
				<td width="135" align="right" valign="top"><strong>Widget CSS :</strong></td>
				<td>
				To customize the default widget styles you can either: edit the css file <strong>/wordpress-social-login/assets/css/style.css</strong>, or change it from this text area. 
				<br />
				<textarea style="width:100%;height:120px;margin-top:6px;" name="wsl_settings_authentication_widget_css"><?php echo get_option( 'wsl_settings_authentication_widget_css' );  ?></textarea>
				<br />
				The basic widget markup is the following:
<pre style="background-color: #eaffdc;border:1px solid #60cf4e; border-radius: 3px;padding: 10px;margin-top:5px;margin-bottom:0px;">
&lt;span id=&quot;<code>wp-social-login-connect-with</code>&quot;&gt;{connect_with_caption}&lt;/span&gt;
&lt;div id=&quot;<code>wp-social-login-connect-options</code>&quot;&gt;
	&lt;a class=&quot;<code>wsl_connect_with_provider</code>&quot;&gt;&lt;img src=&quot;{provider_icon_facebook}&quot; /&gt;
	&lt;a class=&quot;<code>wsl_connect_with_provider</code>&quot;&gt;&lt;img src=&quot;{provider_icon_google}&quot; /&gt;
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
		<input type="submit" class="button-primary" value="Save Settings" /> 
	</div>

</div>

</td>
<td width="10"></td>
<td width="400">


<div class="postbox " id="linksubmitdiv"> 
	<div class="inside">
		<div id="submitlink" class="submitbox"> 
			<h3 style="cursor: default;">What's This?</h3>
			<div id="minor-publishing">  
				<div id="misc-publishing-actions"> 
					<div style="padding:20px;padding-top:0px;">
						<h4 style="cursor: default;border-bottom:1px solid #ccc;font-size: 13px;">Widget Customization</h4>

						<p style="margin:10px;font-size: 13px;" align="justify"> 
						On this section you can fully customize <b>WordPress Social Login Widget</b> and define the way you want it to look and behave.
						</p>

						<p style="margin:10px;font-size: 13px;" align="justify"> 
						<b>WordPress Social Login Widget</b> will be generated into the comments, login and register forms enabling your website vistors and customers to login via social networks.
						</p>

						<p style="margin:10px;"> 
						If this widget does not show up on your custom theme or you want to add it somewhere else then refer to the next section.
						</p>

						<h4 style="cursor: default;border-bottom:1px solid #ccc;">Custom integration</h4>

						<p style="margin:10px;"> 
							If you want to add the social login widget to another location in your theme, you can insert the following code in that location:
							<pre style="width: 380px;background-color: #eaffdc;border:1px solid #60cf4e; border-radius: 3px;padding: 10px;margin-top:15px;margin-left:10px;"> &lt;?php do_action( 'wordpress_social_login' ); ?&gt; </pre> 
						</p> 
						<p style="margin:10px;"> 
						Also, if you are a developer or designer then you can customize it to your heart's content then refer to <b><a href="options-general.php?page=wordpress-social-login&wslp=help">Help & Support</a></b>.
						</p>

						<h4 style="cursor: default;border-bottom:1px solid #ccc;">Widget preview</h4>
						
						<p style="margin:10px;"> 
							This is a preview of what should be on the comments area. <strong>Do not test it here!</strong>
						</p>

						<div style="width: 380px;background-color: #FFFFE0;border:1px solid #E6DB55; border-radius: 3px;padding: 10px;margin-left:10px;">
						<?php 
							wsl_render_login_form()
						?>
						</div>
					</div> 
				</div> 
			</div> 
		</div>
	</div>
</div> 

</td>
</tr>
</table>

</div> 


</form>
