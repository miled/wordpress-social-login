<?php
function wsl_render_settings()
{
	GLOBAL $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG;
?>
<style type="text/css"> 
#wsl_setup_form .inputgnrc, #wsl_setup_form select {
    font-size: 15px;
    padding: 6px 3px; 
    border: 1px solid #CCCCCC;
    border-radius: 4px 4px 4px 4px;
    color: #444444;
    font-family: arial;
    font-size: 16px;
    margin: 0;
    padding: 3px;
    width: 300px;
} 
#wsl_setup_form .inputsave {
    font-size: 15px;
    padding: 6px 3px;  
    color: #444444;
    font-family: arial;
    font-size: 18px;
    margin: 0;
    padding: 3px;
    width: 400px;
	height: 40px;
} 
#wsl_setup_form ul {
    list-style: none outside none; 
}
#wsl_setup_form .cgfparams ul {
    padding: 0;
	margin: 0;
}
#wsl_setup_form ul li {
    color: #555555;
    font-size: 12px;
    margin-bottom: 10px;
    padding: 0;
}
#wsl_setup_form ul li label {
    color: #000000;
    display: block;
    font-size: 14px;
    font-weight: bold;
	padding-bottom: 5px;
}
#wsl_setup_form .cfg { 
	background: #f5f5f5;
	float: left;
	border-radius: 2px 2px 2px 2px;
	border: 1px solid #AAAAAA;
	margin: 0 0 0 30px;
}
#wsl_setup_form .cgfparams {
   padding: 20px;
   float: left;
}
#wsl_setup_form .cgftip {
   border-left: 1px solid #aaa;
   margin-left: 340px;
   padding: 20px;
   min-height: 202px; 
   width: 770px;
   width: 600px; 
   padding-top: 1px;


   width: 450px; 
} 

/* tobe fixed .. */
#footer {
    display:none; 
}
</style> 
<form method="post" id="wsl_setup_form" action="options.php">

	<?php settings_fields( 'wsl-settings-group' ); ?>
	
	<h1 style="margin-bottom: 15px;">WordPress Social Login Settings</h1>  

	<h4 style="border-bottom: 1px solid #CCCCCC;margin:10px;">Overview</h4> 
	<p style="margin-left:25px;margin:10px;">
		WordPress Social Login plugin allow your visitors to comment and login with social networks and identities providers such as Twitter, Facebook, Google, Yahoo, LinkedIn, MySpace, Foursquare and AOL. 
	
		<br />
		<strong>WordPress Social Login</strong> = <a href="http://wordpress.org/extend/plugins/social-connect/" target='_blank'>Social Connect Plugin</a> + <a href="http://hybridauth.sourceforge.net/" target='_blank'>HybridAuth Library</a> + Hack and rework.
	</p>
	
	<br />

	<h4 style="border-bottom: 1px solid #CCCCCC;margin:10px;">Rewrite Diagnostics</h4>
	<p style="margin-left:25px;margin:10px;">
		Click on the link below to confirm your URL rewriting and query string parameter passing are setup correctly on your server. If you see a "Test was successful" message after clicking the link then you are good to go. If you see a 404 error or some other error then you need to update rewrite rules or ask your service provider to configure your server settings such that the below URL works correctly
		<br />
		<br />
		<a class="button-primary" href='<?php echo WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL ?>/callback.php?testing=http://www.example.com' target='_blank'>Test server redirection settings</a>
	</p>

	<br />
	
	<h4 style="border-bottom: 1px solid #CCCCCC;margin:10px;">Providers setup</h4> 

	<ul style="list-style:circle inside;margin-left:25px;">
		<li style="color: #000000;font-size: 14px;">To correctly setup these Identity Providers please carefully follow the help section of each one.</li>
		<li style="color: #000000;font-size: 14px;">If <b>Provider Adapter Satus</b> is set to <b style="color:red">Disabled</b> then users will not be able to login with this provider on you website.</li>
	</ul>

<?php
	$nb_provider = 0;
	
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

		$nb_provider                ++;
?> 
	<h4 style="margin-left:18px;"><?php echo $nb_provider ?> - <?php echo $provider_name ?></h4> 
	<div> 
		<div class="cfg">
		   <div class="cgfparams">
			  <ul>
				 <li><label><?php echo $provider_name ?> Adapter Satus</label>
					<select name="<?php echo 'wsl_settings_' . $provider_id . '_enabled' ?>">
						<option value="1" <?php if(   get_option( 'wsl_settings_' . $provider_id . '_enabled' ) ) echo "selected"; ?> >Enabled</option>
						<option value="0" <?php if( ! get_option( 'wsl_settings_' . $provider_id . '_enabled' ) ) echo "selected"; ?> >Disabled</option>
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
					<p>In order to set up <?php echo $provider_name ?>, <b>you need to register your website with <?php echo $provider_name ?></b></p>
					
					<p>- Go to <a href="<?php echo $provider_new_app_link ?>" target ="_blanck"><?php echo $provider_new_app_link ?></a></p>

					<?php if ( $provider_id == "myspace" ) : ?>
						<p>- Make sure to put your correct website adress in the "External Url" and "External Callback Validation" fields. This adresse must match with the current hostname "<em style="color:#CB4B16;"><?php echo $_SERVER["SERVER_NAME"] ?></em>".</p>
					<?php endif; ?> 

					<?php if ( $provider_id == "live" ) : ?>
						<p>- Make sure to put your correct website adress in the "Redirect Domain" field. This adresse must match with the current hostname "<em style="color:#CB4B16;"><?php echo $_SERVER["SERVER_NAME"] ?></em>".</p>
					<?php endif; ?> 

					<?php if ( $provider_id == "facebook" ) : ?>
						<p>- Make sure to put your correct website adress in the "Site Url" field. This adresse must match with the current hostname "<em style="color:#CB4B16;"><?php echo $_SERVER["SERVER_NAME"] ?></em>".</p>
						<p>- Once you have registered, copy the created application ID and Secret into this setup page.</p> 
					<?php elseif ( $provider_id == "google" ) : ?>
						<p>- On the <b>"Create Client ID"</b> popup switch to advanced settings by clicking on <b>(more options)</b>.</p>
						<p>- Once you have registered, copy the created application client ID and client secret into this setup page.</p> 
					<?php else: ?>	
						<p>- Once you have registered, copy the created application consumer key and Secret into this setup page.</p> 
					<?php endif; ?>
				<?php else: ?>	
					<p>- No registration required for OpenID based providers</p> 
				<?php endif; ?> 

				<?php if ( $provider_callback_url ) : ?>
					<p>- Provide this URL as the <b>Callback URL</b> for your application: <br /><?php echo $provider_callback_url ?></p>
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
	<div style="text-align:center">
		Thanks for scrolling this far down! now click the big button to complete the installation.
		<br />
		<br />
		<input type="submit" class="inputsave" value="Setup WordPress Social Login" /> 
	</div> 
</div>
</form>
<div class="clear"></div>
<?php
}
