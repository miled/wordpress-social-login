<style>
#wsl_setup_form .inputgnrc, #wsl_setup_form select { 
    font-size: 14px; 
}
.wsl_aside { 
    float: right;
    margin: 6px; 
    margin-top:0px;
    margin-right:10px;
    position: relative;
    width: 250px;
    z-index: 200;
} 
.wsl_help {
    line-height: 1;
    padding: 8px;
	
	background-color: #FFFFE0;
	border:1px solid #E6DB55; 
	border-radius: 3px;
	padding: 10px; 
}
.wsl_notice {
    line-height: 1;
    padding: 8px;

	background-color: #eaffdc;
	border:1px solid #60cf4e; 
	border-radius: 3px;
	padding: 10px;      
}
.wsl_alert {
    line-height: 1;
    padding: 8px;

	background-color: #FFEBE8;
	border:1px solid #CC0000; 
	border-radius: 3px;
	padding: 10px;      
}
</style>
<div style="margin-left:10px;">

<div class="wsl_help wsl_aside">
    <h3 style="margin: 0 0 5px;">Need Help?</h3>

	<p style="line-height: 19px;" align="justify">
		If you are still new to things, we recommend that you read the <b><a href="options-general.php?page=wordpress-social-login&wslp=2">Plugin User Guide</a></b>
		and to make sure your server settings meet this <b><a href="options-general.php?page=wordpress-social-login&amp;wslp=3">Plugin Requirements</a></b>.
	</p>
</div> 

<div style="clear:both" class="wsl_notice wsl_aside">
    <h3 style="margin: 0 0 5px;">Need Support?</h3>

	<p style="line-height: 19px;">
		If you run into any issue, please join us on the <b><a href="https://groups.google.com/forum/#!forum/hybridauth-plugins">discussion group</a></b> or feel free to contact me at <b><a href="mailto:hybridauth@gmail.com">hybridauth@gmail.com</a></b>
	</p>
</div>



<form method="post" id="wsl_setup_form" action="options.php">  
<?php settings_fields( 'wsl-settings-group-customize' ); ?>

<h3>Basic Settings </h3> 


<table width="600" border="0" cellpadding="5" cellspacing="2" >

  <tr>
    <td width="135" align="right"><strong>Connect with caption :</strong></td>
    <td>

<?php 
	$wsl_settings_connect_with_label = get_option( 'wsl_settings_connect_with_label' );

	if( empty( $wsl_settings_connect_with_label ) ){
		$wsl_settings_connect_with_label = "Connect with:";
	}
?>
	<input type="text" class="inputgnrc" style="padding: 4px;width: 400px;" value="<?php echo $wsl_settings_connect_with_label; ?>" name="wsl_settings_connect_with_label" >
    
    </td>
  </tr>

  <tr>
    <td align="right"><strong>Social icon set :</strong></td>
    <td> 
		<select name="wsl_settings_social_icon_set" style="width: 400px;">
			<option <?php if( get_option( 'wsl_settings_social_icon_set' )   == "wpzoom" ) echo "selected"; ?>   value="wpzoom">WPZOOM social networking icon set</option>
			<option <?php if( get_option( 'wsl_settings_social_icon_set' ) == "icondock" ) echo "selected"; ?> value="icondock">Icondock vector social media icons</option> 
		</select> 
    </td>
  </tr>
  
  <tr>
    <td align="right"><strong>Users avatars :</strong></td>
    <td>
		<select name="wsl_settings_users_avatars" style="width: 400px;">
			<option <?php if( ! get_option( 'wsl_settings_users_avatars' ) ) echo "selected"; ?> value="0">Display the default users avatars</option> 
			<option <?php if(   get_option( 'wsl_settings_users_avatars' ) ) echo "selected"; ?> value="1">Display users avatars from social networks when available</option>
		</select> 
    </td>
  </tr>
  
  <tr>

<?php 
	$wsl_settings_redirect_url = get_option( 'wsl_settings_redirect_url' );

	if( empty( $wsl_settings_redirect_url ) ){
		$wsl_settings_redirect_url = site_url();
	}
?>
    <td align="right" valign="top"><strong>Redirect URL :</strong></td>
    <td>
		<input type="text" name="wsl_settings_redirect_url" value="<?php echo $wsl_settings_redirect_url; ?>" class="inputgnrc" style="width: 400px;">
		<br />
		<small>
			Where should users be redirected to after registring? (<b>By default its set to your blog's homepage</b>)
		</small>
    </td>
  </tr>

  <tr>
    <td>&nbsp;</td>
    <td><input type="submit" class="button-primary" value="Save" /> </td>
  </tr>
</table>

</form>

<br />

<h3>Preview</h3> 

<p style="margin:10px;"> 
	This is a preview of what should be on the comments section. <strong>Please do not test it here!</strong>
</p>

<div style="width: 600px;background-color: #FFFFE0;border:1px solid #E6DB55; border-radius: 3px;padding: 10px;margin-left:10px;">
<?php 
	wsl_render_login_form()
?>
</div>

<br />

<h3>Custom integration</h3> 
<p style="margin:10px;"> 
WordPress Social Login will attempts to work with the default WordPress comment, login and registration forms. 
</p>

<ul style="list-style:disc inside;margin-left:25px;"> 
	
	<li>If you want to add the social login widget to another location in your theme, you can insert the following code in that location:
	<pre style="width: 400px;background-color: #FFFFE0;border:1px solid #E6DB55; border-radius: 3px;padding: 10px;margin-top:15px;margin-left:10px;"> &lt;?php do_action( 'wordpress_social_login' ); ?&gt; </pre> 
	<br />
	</li> 

	<li>Also, if you are a developer or designer then you can customize it to your heart's content: 
		<ul style="list-style:circle inside;margin-left:25px;margin-top:10px;">
			<li>The default css styles are found at <strong>/wordpress-social-login/assets/css/style.css</strong></li> 
			<li>Social icons are found at <strong>/wordpress-social-login/assets/img/32x32/</strong></li> 
			<li>The widget view can be found at <strong>/wordpress-social-login/includes/plugin.ui.php</strong>, function <strong>wsl_render_login_form()</strong></li> 
			<li>The popup and loading screens are found at <strong>/wordpress-social-login/authenticate.php</strong></li> 
		</ul>
	</li> 
</ul>
</div>
