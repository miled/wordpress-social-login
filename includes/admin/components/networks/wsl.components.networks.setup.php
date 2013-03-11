<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*    (c) 2011-2013 Mohamed Mrassi and contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

/**
* Social networks configuration and setup
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 

// --------------------------------------------------------------------

function wsl_component_networks_setup()
{
	// HOOKABLE: 
	do_action( "wsl_component_networks_setup_start" );

	GLOBAL $wpdb;
	GLOBAL $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG;

	if( isset( $_REQUEST["enable"] ) && $_REQUEST["enable"] ){
		$provider_id = $_REQUEST["enable"];

		update_option( 'wsl_settings_' . $provider_id . '_enabled', 1 );
	}
?> 
<script>
	function toggleproviderkeys(idp){
		if(typeof jQuery=="undefined"){
			alert( "Error: WordPress Social Login require jQuery to be installed on your wordpress in order to work!" );

			return;
		}

		if(jQuery('#wsl_settings_' + idp + '_enabled').val()==1){
			jQuery('.wsl_tr_settings_' + idp).show();
		}
		else{
			jQuery('.wsl_tr_settings_' + idp).hide();
			jQuery('.wsl_div_settings_help_' + idp).hide();
		}
		
		return false;
	}

	function toggleproviderhelp(idp){
		if(typeof jQuery=="undefined"){
			alert( "Error: WordPress Social Login require jQuery to be installed on your wordpress in order to work!" );

			return false;
		}

		jQuery('.wsl_div_settings_help_' + idp).show();
		
		return false;
	}
</script>
<?php 
	$nbprovider = 0;

	$assets_base_url = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . '/assets/img/16x16/';
	
	foreach( $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG AS $item ):
		$provider_id                = @ $item["provider_id"];
		$provider_name              = @ $item["provider_name"];

		$require_client_id          = @ $item["require_client_id"];
		$provide_email              = @ $item["provide_email"];
		
		$provider_new_app_link      = @ $item["new_app_link"];
		$provider_userguide_section = @ $item["userguide_section"];

		$provider_callback_url      = "" ;

		if( ! ( ( isset( $item["default_network"] ) && $item["default_network"] ) || get_option( 'wsl_settings_' . $provider_id . '_enabled' ) ) ){
			continue;
		}

		// default endpoint_url
		$endpoint_url = WORDPRESS_SOCIAL_LOGIN_HYBRIDAUTH_ENDPOINT_URL;

		// overwrite endpoint_url if need'd
		if( get_option( 'wsl_settings_base_url' ) ){
			$endpoint_url = strtolower( get_option( 'wsl_settings_base_url' ) . '/hybridauth/' );
		}

		if( isset( $item["callback"] ) && $item["callback"] ){
			$provider_callback_url  = '<span style="color:green">' . $endpoint_url . '?hauth.done=' . $provider_id . '</span>';
		}

		$setupsteps = 0;  
?>  
		<a name="setup<?php echo strtolower( $provider_id ) ?>"></a> 
		<div class="stuffbox" id="namediv">
			<h3>
				<label for="name" class="wp-neworks-label">
					<img alt="<?php echo $provider_name ?>" title="<?php echo $provider_name ?>" src="<?php echo $assets_base_url . strtolower( $provider_id ) . '.png' ?>" style="vertical-align: top;width:16px;height:16px;" /> <?php echo $provider_name ?>
				</label>
			</h3>
			<div class="inside">
				<table class="form-table editcomment">
					<tbody>
						<tr>
							<td style="width:110px"><?php _wsl_e("Enabled", 'wordpress-social-login') ?>:</td>
							<td>
								<select 
									name="<?php echo 'wsl_settings_' . $provider_id . '_enabled' ?>" 
									id="<?php echo 'wsl_settings_' . $provider_id . '_enabled' ?>" 
									onChange="toggleproviderkeys('<?php echo $provider_id; ?>')" 
								>
									<option value="1" <?php if(   get_option( 'wsl_settings_' . $provider_id . '_enabled' ) ) echo "selected"; ?> >Yes</option>
									<option value="0" <?php if( ! get_option( 'wsl_settings_' . $provider_id . '_enabled' ) ) echo "selected"; ?> >No</option>
								</select>
							</td>
							<td style="width:140px">&nbsp;</td>
						</tr>

						<?php if ( $provider_new_app_link ){ ?>
							<?php if ( $require_client_id ){ // key or id ? ?>
								<tr valign="top" <?php if( ! get_option( 'wsl_settings_' . $provider_id . '_enabled' ) ) echo 'style="display:none"'; ?> class="wsl_tr_settings_<?php echo $provider_id; ?>" >
									<td><?php _wsl_e("Application ID", 'wordpress-social-login') ?>:</td>
									<td><input type="text" name="<?php echo 'wsl_settings_' . $provider_id . '_app_id' ?>" value="<?php echo get_option( 'wsl_settings_' . $provider_id . '_app_id' ); ?>" ></td>
									<td><a href="javascript:void(0)" onClick="toggleproviderhelp('<?php echo $provider_id; ?>')"><?php _wsl_e("Where do I get this info?", 'wordpress-social-login') ?></a></td>
								</tr> 
							<?php } else { ?>
								<tr valign="top" <?php if( ! get_option( 'wsl_settings_' . $provider_id . '_enabled' ) ) echo 'style="display:none"'; ?> class="wsl_tr_settings_<?php echo $provider_id; ?>" >
									<td><?php _wsl_e("Application Key", 'wordpress-social-login') ?>:</td>
									<td><input type="text" name="<?php echo 'wsl_settings_' . $provider_id . '_app_key' ?>" value="<?php echo get_option( 'wsl_settings_' . $provider_id . '_app_key' ); ?>" ></td>
									<td><a href="javascript:void(0)" onClick="toggleproviderhelp('<?php echo $provider_id; ?>')"><?php _wsl_e("Where do I get this info?", 'wordpress-social-login') ?></a></td>
								</tr>  
							<?php }; ?>	 
								<tr valign="top" <?php if( ! get_option( 'wsl_settings_' . $provider_id . '_enabled' ) ) echo 'style="display:none"'; ?> class="wsl_tr_settings_<?php echo $provider_id; ?>" >
									<td><?php _wsl_e("Application Secret", 'wordpress-social-login') ?>:</td>
									<td><input type="text" name="<?php echo 'wsl_settings_' . $provider_id . '_app_secret' ?>" value="<?php echo get_option( 'wsl_settings_' . $provider_id . '_app_secret' ); ?>" ></td>
									<td><a href="javascript:void(0)" onClick="toggleproviderhelp('<?php echo $provider_id; ?>')"><?php _wsl_e("Where do I get this info?", 'wordpress-social-login') ?></a></td>
								</tr>
						<?php } // if require registration ?> 
					</tbody>
				</table> 
				<?php if ( in_array( $provider_id, array( "Twitter", "Identica", "Tumblr", "Goodreads", "500px", "Vkontakte", "Gowalla", "Steam" ) ) ) : ?>
				<br />
				<hr />
				<p style="margin-left:12px;margin-bottom:0px;"> 
					<b  style="color:#CB4B16;"><?php _wsl_e("Note", 'wordpress-social-login') ?>:</b> 
					
					<?php echo sprintf( _wsl__("<b>%s</b> do not provide their user's email address and by default a random email will then be generated for them instead", 'wordpress-social-login'), $provider_name ) ?>. 
					
					<?php _wsl_e('To change this behaviour and to force new registered users to provide their emails before they get in, goto <b><a href="options-general.php?page=wordpress-social-login&wslp=bouncer">Bouncer</a></b> and enable <b>Profile Completion</b>', 'wordpress-social-login') ?>.
				</p>
				<?php endif; ?> 
				<br />
				<div
					class="wsl_div_settings_help_<?php echo $provider_id; ?>" 
					style="<?php if( isset( $_REQUEST["enable"] ) && ! isset( $_REQUEST["settings-updated"] ) && $_REQUEST["enable"] == $provider_id ) echo "-"; // <= lolz ?>display:none;" 
				> 
					<hr class="wsl" />
					<?php if ( $provider_new_app_link  ) : ?> 
						<?php _wsl_e('<span style="color:#CB4B16;">Application</span> id and secret (also sometimes referred as <span style="color:#CB4B16;">Customer</span> key and secret or <span style="color:#CB4B16;">Client</span> id and secret) are what we call an application credentials', 'wordpress-social-login') ?>. 
						
						<?php echo sprintf( _wsl__( 'This application will link your website <code>%s</code> to <code>%s API</code> and these credentials are needed in order for <b>%s</b> users to access your website', 'wordpress-social-login'), $_SERVER["SERVER_NAME"], $provider_name, $provider_name ) ?>. 
						<br />
						
						<?php _wsl_e("These credentials may also differ in format, name and content depending on the social network.", 'wordpress-social-login') ?> 
						<br />
						<br />
						
						<?php echo sprintf( _wsl__('To enable authentication with this provider and to register a new <b>%s API Application</b>, carefully follow the steps', 'wordpress-social-login'), $provider_name ) ?>
						:<br />
					<?php else: ?>  
							<p><?php echo sprintf( _wsl__('<b>Done.</b> Nothing more required for <b>%s</b>', 'wordpress-social-login'), $provider_name) ?>.</p> 
					<?php endif; ?>  
					<div class="wsl_div_settings_help_<?php echo $provider_id; ?>" style="margin-left:40px;">
						<?php if ( $provider_new_app_link  ) : ?> 
								<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> Go to <a href="<?php echo $provider_new_app_link ?>" target ="_blanck"><?php echo $provider_new_app_link ?></a> and <b>create a new application</b>.</p>

								<?php if ( $provider_id == "Google" ) : ?>
										<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> On the <b>Dashboard sidebar</b> click on <b>API Access</b> then Click <em style="color:#CB4B16;">"Create an OAuth 2.0 client ID..."</em>.</p> 
								<?php endif; ?>  

								<?php if ( $provider_id == "Google" ) : ?>  
												</p>
												<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> On the <b>"Create Client ID"</b> popup :
												<br />&nbsp;&nbsp; - Enter a product name (the name of your website will do).
												<br />&nbsp;&nbsp; - Enter the URL for a logo if you like.
												<br />&nbsp;&nbsp; - Click Next.
												<br />&nbsp;&nbsp; - Select <em style="color:#CB4B16;">"Web application"</em> as the application type.
												<br />&nbsp;&nbsp; - Then switch to advanced settings by clicking on <b>(more options)</b>
												.</p>
								<?php else: ?>  
										<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> Fill out any required fields such as the application name and description.</p> 
								<?php endif; ?> 

								<?php if ( $provider_callback_url ) : ?>
										<p>
												<?php echo "<b>" . ++$setupsteps . "</b>." ?> Provide this URL as the <b>Callback URL</b> for your application:
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
								<?php endif; ?> 
								
								<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> Once you have registered, copy and past the created application credentials into this setup page.</p>  
						<?php endif; ?> 
						
						<?php if ( $provider_id == "Facebook" ) : ?>
							<hr /> 
							<table style="text-align: center;margin-bottom:12px;">
								<td><a class="span4 thumbnail" href="http://hybridauth.sf.net/userguide/img/setup/facebook/1.png" target="_blank"><img src="http://hybridauth.sf.net/userguide/img/setup/facebook/1.png"></a></td>
								<td><a class="span4 thumbnail" href="http://hybridauth.sf.net/userguide/img/setup/facebook/2.png" target="_blank"><img src="http://hybridauth.sf.net/userguide/img/setup/facebook/2.png"></a></td>
								<td><a class="span4 thumbnail" href="http://hybridauth.sf.net/userguide/img/setup/facebook/3.png" target="_blank"><img src="http://hybridauth.sf.net/userguide/img/setup/facebook/3.png"></a></td>
							</table> 
							<hr />
						<?php endif; ?> 
						<?php if ( $provider_id == "Google" ) : ?>
							<hr /> 
							<table style="text-align: center;margin-bottom:12px;">
								<td><a class="span4 thumbnail" href="http://hybridauth.sf.net/userguide/img/setup/google/1.png" target="_blank"><img src="http://hybridauth.sf.net/userguide/img/setup/google/1.png"></a></td>
								<td><a class="span4 thumbnail" href="http://hybridauth.sf.net/userguide/img/setup/google/2.png" target="_blank"><img src="http://hybridauth.sf.net/userguide/img/setup/google/2.png"></a></td>
								<td><a class="span4 thumbnail" href="http://hybridauth.sf.net/userguide/img/setup/google/3.png" target="_blank"><img src="http://hybridauth.sf.net/userguide/img/setup/google/3.png"></a></td>
							</table> 
							<hr />
						<?php endif; ?> 
						<?php if ( $provider_id == "Twitter" ) : ?>
							<hr /> 
							<table style="text-align: center;margin-bottom:12px;">
								<td><a class="span4 thumbnail" href="http://hybridauth.sf.net/userguide/img/setup/twitter/1.png" target="_blank"><img src="http://hybridauth.sf.net/userguide/img/setup/twitter/1.png"></a></td>
								<td><a class="span4 thumbnail" href="http://hybridauth.sf.net/userguide/img/setup/twitter/2.png" target="_blank"><img src="http://hybridauth.sf.net/userguide/img/setup/twitter/2.png"></a></td>
								<td><a class="span4 thumbnail" href="http://hybridauth.sf.net/userguide/img/setup/twitter/3.png" target="_blank"><img src="http://hybridauth.sf.net/userguide/img/setup/twitter/3.png"></a></td>
							</table> 
							<hr />
						<?php endif; ?> 
						<?php if ( $provider_new_app_link  ) : ?> 
							<p>
								<b><?php _wsl_e("And that's it!", 'wordpress-social-login') ?></b> 
								<br />
								<?php echo sprintf( _wsl__( 'If for some reason you still can\'t figure it out, first try to a) <a class="button-primary" href="https://www.google.com/search?q=%s API create application" target="_blank">Google it</a>, then check it on b) <a class="button-primary" href="http://www.youtube.com/results?search_query=%s API create application " target="_blank">Youtube</a> and if nothing works c) <a class="button-primary" href="options-general.php?page=wordpress-social-login&wslp=help">ask for support</a>', 'wordpress-social-login'), $provider_name, $provider_name ) ?>.
							</p> 
						<?php endif; ?> 
					</div>
				</div>
			</div>
		</div>
<?php
	endforeach;
?>
	<input type="submit" class="button-primary" value="<?php _wsl_e("Save Settings", 'wordpress-social-login') ?>" />
<?php
	// HOOKABLE: 
	do_action( "wsl_component_networks_setup_end" );	
} 

// --------------------------------------------------------------------	
