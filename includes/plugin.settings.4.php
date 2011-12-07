<form method="post" id="wsl_setup_form" action="options.php"> 

	<?php settings_fields( 'wsl-settings-group' ); ?>

<p style="margin:20px;">
Except for OpenID providers, each social network and identities provider will require that you create an external application linking your Web site to theirs apis. These external applications ensures that users are logging into the proper Web site and allows identities providers to send the user back to the correct Web site after successfully authenticating their Accounts.
</p>
	
<ul style="list-style:circle inside;margin-left:30px;">
	<li style="color: #000000;font-size: 14px;">To correctly setup these Identity Providers please carefully follow the help section of each one.</li>
	<li style="color: #000000;font-size: 14px;">If a <b>Provider Satus</b> is set to <b style="color:red">NO</b> then users will not be able to login with that provider on you website.</li>
</ul>

<br />

<?php 
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
	<h3 style="margin-left:30px;"><img alt="<?php echo $provider_name ?>" title="<?php echo $provider_name ?>" src="<?php echo $assets_base_url . strtolower( $provider_id ) . '.png' ?>" style="vertical-align: top;" /> <?php echo $provider_name ?></h3> 
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
						<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> Set the <b>Application Type</b> to <em style="color:#CB4B16;">Browser</em>.</p> 
						<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> Set the <b>Default Access Type</b> to <em style="color:#CB4B16;">Read, Write, & Direct Messages</em>.</p> 
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
		Thanks for scrolling this far down! now click the big button to complete the installation.
		<br />
		<br />
		<input type="submit" class="inputsave" value="Save" /> 
	</div> 
</div>

</form>