<style>
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
	background-color: #EDEFF4;
	border:1px solid #6B84B4; 
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
.wsl_donate {
    line-height: 1;
    padding: 8px;
	background-color: #eaffdc;
	border:1px solid #60cf4e;  
	border-radius: 3px;
	padding: 10px;      
}
</style>

<div class="wsl_help wsl_aside">
    <h3 style="margin: 0 0 5px;">Need Help?</h3>

	<p style="line-height: 19px;" align="justify">
		If you are still new to things, we recommend that you read the <b><a href="options-general.php?page=wordpress-social-login&wslp=2">Plugin User Guide</a></b>
		and to make sure your server settings meet this <b><a href="options-general.php?page=wordpress-social-login&amp;wslp=3">Plugin Requirements</a></b>.
	</p>
</div> 

<div style="clear:both" class="wsl_donate wsl_aside">
    <h3 style="margin: 0 0 5px;">Need Support?</h3>

	<p style="line-height: 19px;">
		If you run into any issue, please join us on the <b><a href="options-general.php?page=wordpress-social-login&wslp=8">discussion group</a></b> or feel free to contact me at <b><a href="mailto:hybridauth@gmail.com">hybridauth@gmail.com</a></b>
	</p>
</div>
 
<form method="post" id="wsl_setup_form" action="options.php"> 

	<?php settings_fields( 'wsl-settings-group' ); ?>

<p style="margin:10px;line-height: 22px;" align="justify">
Except for OpenID providers, each social network and identities provider will require that you create an external application linking your Web site to theirs apis. These external applications ensures that users are logging into the proper Web site and allows identities providers to send the user back to the correct Web site after successfully authenticating their Accounts.
</p>

<p style="margin:10px;line-height: 22px;" align="justify">
<b>Important:</b>
</p>
<ul style="list-style:circle inside;margin-left:30px;">
	<li style="color: #000000;font-size: 14px;">To correctly setup these Identity Providers please carefully follow the help section of each one.</li>
	<li style="color: #000000;font-size: 14px;">If the <b>Allow users to sign on with provider?</b> is set to <b style="color:red">NO</b> then users will not be able to login with that provider on you website.</li>
	<li style="color: #000000;font-size: 14px;">Some social networks like Twitter and LinkedIn does NOT give out their user's email. A random email will then be generated for them.</li>
	<li style="color: #000000;font-size: 14px;">WSL will try to link existing blog accounts to social network users profiles by matching their verified emails. Currently this only works for Facebook, Google, Yahaoo and Foursquare.</li>
</ul>

<br />

<?php 
	$nbprovider = 0;
	
	foreach( $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG AS $item ):
		$provider_id                = @ $item["provider_id"];
		$provider_name              = @ $item["provider_name"];

		$require_client_id          = @ $item["require_client_id"];
		$provide_email              = @ $item["provide_email"];
		
		$provider_new_app_link      = @ $item["new_app_link"];
		$provider_userguide_section = @ $item["userguide_section"];

		$provider_callback_url      = "" ;

		if( isset( $item["callback"] ) && $item["callback"] ){
			$provider_callback_url  = '<span style="color:green">' . WORDPRESS_SOCIAL_LOGIN_HYBRIDAUTH_ENDPOINT_URL	 . '?hauth.done=' . $provider_id . '</span>';
		}

		$setupsteps = 0; 

		$assets_base_url = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . '/assets/img/16x16/';
?> 
	<h3 style="margin-left:30px;"><img alt="<?php echo $provider_name ?>" title="<?php echo $provider_name ?>" src="<?php echo $assets_base_url . strtolower( $provider_id ) . '.png' ?>" style="vertical-align: top;width:16px;height:16px;" /> <?php echo ++$nbprovider ?>. <?php echo $provider_name ?></h3> 
	<div> 
		<div class="cfg">
		   <div class="cgfparams">
			  <ul>
				 <li><label>Allow users to sign on with <?php echo $provider_name ?>?</label>
					<select name="<?php echo 'wsl_settings_' . $provider_id . '_enabled' ?>">
						<option value="1" <?php if(   get_option( 'wsl_settings_' . $provider_id . '_enabled' ) ) echo "selected"; ?> >Yes</option>
						<option value="0" <?php if( ! get_option( 'wsl_settings_' . $provider_id . '_enabled' ) ) echo "selected"; ?> >No</option>
					</select>
				</li>
				
				<?php if ( $provider_new_app_link ){ ?>
					<?php if ( $require_client_id ){ // key or id ? ?>
						<li><label>Application ID</label>
						<input type="text" class="inputgnrc"
						value="<?php echo get_option( 'wsl_settings_' . $provider_id . '_app_id' ); ?>"
						name="<?php echo 'wsl_settings_' . $provider_id . '_app_id' ?>" ></li>
					<?php } else { ?>
						<li><label>Application Key</label>
						<input type="text" class="inputgnrc" 
							value="<?php echo get_option( 'wsl_settings_' . $provider_id . '_app_key' ); ?>"
							name="<?php echo 'wsl_settings_' . $provider_id . '_app_key' ?>" ></li>
					<?php }; ?>	 

					<li><label>Application Secret</label>
					<input type="text" class="inputgnrc"
						value="<?php echo get_option( 'wsl_settings_' . $provider_id . '_app_secret' ); ?>" 
						name="<?php echo 'wsl_settings_' . $provider_id . '_app_secret' ?>" ></li>
				<?php } // if require registration ?>
			  </ul> 
		   </div>
		   <div class="cgftip">
				<?php if ( $provider_new_app_link  ) : ?> 
					<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> Go to <a href="<?php echo $provider_new_app_link ?>" target ="_blanck"><?php echo $provider_new_app_link ?></a> and <b>create a new application</b>.</p>

					<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> Fill out any required fields such as the application name and description.</p>

					<?php if ( $provider_id == "google" ) : ?>
						<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> On the <b>"Create Client ID"</b> popup switch to advanced settings by clicking on <b>(more options)</b>.</p>
					<?php endif; ?>	

					<?php if ( $provider_callback_url ) : ?>
						<p>
							<?php echo "<b>" . ++$setupsteps . "</b>." ?> Provide this URL as the Callback URL for your application:
							<br />
							<?php echo $provider_callback_url ?>
						</p>
					<?php endif; ?> 

					<?php if ( $provider_id == "MySpace" ) : ?>
						<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> Put your website domain in the <b>External Url</b> and <b>External Callback Validation</b> fields. This should match with the current hostname <em style="color:#CB4B16;"><?php echo $_SERVER["SERVER_NAME"] ?></em>.</p>
					<?php endif; ?> 

					<?php if ( $provider_id == "Live" ) : ?>
						<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> Put your website domain in the <b>Redirect Domain</b> field. This should match with the current hostname <em style="color:#CB4B16;"><?php echo $_SERVER["SERVER_NAME"] ?></em>.</p>
					<?php endif; ?> 

					<?php if ( $provider_id == "Facebook" ) : ?>
						<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> Put your website domain in the <b>Site Url</b> field. This should match with the current hostname <em style="color:#CB4B16;"><?php echo $_SERVER["SERVER_NAME"] ?></em>.</p> 
					<?php endif; ?>	

					<?php if ( $provider_id == "LinkedIn" ) : ?>
						<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> Put your website domain in the <b>Integration URL</b> field. This should match with the current hostname <em style="color:#CB4B16;"><?php echo $_SERVER["SERVER_NAME"] ?></em>.</p> 
						<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> Set the <b>Application Type</b> to <em style="color:#CB4B16;">Web Application</em>.</p> 
					<?php endif; ?>	

					<?php if ( $provider_id == "Twitter" ) : ?>
						<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> Put your website domain in the <b>Application Website</b> and <b>Application Callback URL</b> fields. This should match with the current hostname <em style="color:#CB4B16;"><?php echo $_SERVER["SERVER_NAME"] ?></em>.</p>  
						<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> Set the <b>Default Access Type</b> to <em style="color:#CB4B16;">Read</em>.</p> 
					<?php endif; ?>	
					
					<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> Once you have registered, copy and past the created application credentials into this setup page.</p>  
				<?php else: ?>	
					<p>No registration required for OpenID based providers</p> 
				<?php endif; ?> 
		   </div>
		</div>   
	</div> 
	<br style="clear:both;"/> 
	<br />
<?php
	endforeach;
?>
	<br /> 
	<div style="margin-left:30px;">
		<b>Thanks for scrolling this far down!</b> Now click the save button to complete the setup.
		<br />
		<br />
		<input type="submit" class="button-primary" value="Save Changes" /> 
	</div> 
</div>

</form>