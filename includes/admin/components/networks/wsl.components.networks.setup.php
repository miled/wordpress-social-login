<?php
/*!
* WordPress Social Login
*
* http://miled.github.io/wordpress-social-login/ | https://github.com/miled/wordpress-social-login
*  (c) 2011-2014 Mohamed Mrassi and contributors | http://wordpress.org/plugins/wordpress-social-login/
*/

/**
* Social networks configuration and setup
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 

// --------------------------------------------------------------------

/**
* This should be reworked somehow.. the code has become spaghettis
*/
function wsl_component_networks_setup()
{
	// HOOKABLE: 
	do_action( "wsl_component_networks_setup_start" );

	GLOBAL $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG;
	
	$assets_base_url = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . '/assets/img/16x16/';

	// save settings?
	if( isset( $_REQUEST["enable"] ) && $_REQUEST["enable"] )
	{
		$provider_id = $_REQUEST["enable"];

		update_option( 'wsl_settings_' . $provider_id . '_enabled', 1 );
	}
?> 
<script> 
	function toggleproviderkeys(idp)
	{
		if(typeof jQuery=="undefined")
		{
			alert( "Error: WordPress Social Login require jQuery to be installed on your wordpress in order to work!" );

			return;
		}

		if(jQuery('#wsl_settings_' + idp + '_enabled').val()==1)
		{
			jQuery('.wsl_tr_settings_' + idp).show();
		}
		else
		{
			jQuery('.wsl_tr_settings_' + idp).hide();
			jQuery('.wsl_div_settings_help_' + idp).hide();
		}
		
		return false;
	}

	function toggleproviderhelp(idp)
	{
		if(typeof jQuery=="undefined")
		{
			alert( "Error: WordPress Social Login require jQuery to be installed on your wordpress in order to work!" );

			return false;
		}

		jQuery('.wsl_div_settings_help_' + idp).toggle();

		return false;
	}
</script>
<?php
	foreach( $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG AS $item ):
		$provider_id                = isset( $item["provider_id"]       ) ? $item["provider_id"]       : '';
		$provider_name              = isset( $item["provider_name"]     ) ? $item["provider_name"]     : '';

		$require_client_id          = isset( $item["require_client_id"] ) ? $item["require_client_id"] : '';
		$require_api_key            = isset( $item["require_api_key"]   ) ? $item["require_api_key"]   : '';
		$default_api_scope          = isset( $item["default_api_scope"] ) ? $item["default_api_scope"] : '';
		$provide_email              = isset( $item["provide_email"]     ) ? $item["provide_email"]     : '';

		$provider_new_app_link      = isset( $item["new_app_link"]      ) ? $item["new_app_link"]      : '';
		$provider_userguide_section = isset( $item["userguide_section"] ) ? $item["userguide_section"] : '';

		$provider_callback_url      = "" ;

		if( ! ( ( isset( $item["default_network"] ) && $item["default_network"] ) || get_option( 'wsl_settings_' . $provider_id . '_enabled' ) ) )
		{
			continue;
		}

		// default endpoint_url
		$endpoint_url = WORDPRESS_SOCIAL_LOGIN_HYBRIDAUTH_ENDPOINT_URL;

		if( isset( $item["callback"] ) && $item["callback"] )
		{
			$provider_callback_url  = '<span style="color:green">' . $endpoint_url . '?hauth.done=' . $provider_id . '</span>';
		}

		if( isset( $item["custom_callback"] ) && $item["custom_callback"] )
		{
			$provider_callback_url  = '<span style="color:green">' . $endpoint_url . 'endpoints/' . strtolower( $provider_id ) . '.php</span>';
		}

		$setupsteps = 0;  
?>  
		<a name="setup<?php echo strtolower( $provider_id ) ?>"></a> 
		<div class="stuffbox" id="namediv">
			<h3>
				<label class="wp-neworks-label">
					<img alt="<?php echo $provider_name ?>" title="<?php echo $provider_name ?>" src="<?php echo $assets_base_url . strtolower( $provider_id ) . '.png' ?>" style="vertical-align: top;width:16px;height:16px;" /> <?php _wsl_e( $provider_name, 'wordpress-social-login' ) ?>
				</label>
			</h3>
			<div class="inside">
				<table class="form-table editcomment">
					<tbody>
						<tr>
							<td style="width:125px"><?php _wsl_e("Enabled", 'wordpress-social-login') ?>:</td>
							<td>
								<select 
									name="<?php echo 'wsl_settings_' . $provider_id . '_enabled' ?>" 
									id="<?php echo 'wsl_settings_' . $provider_id . '_enabled' ?>" 
									onChange="toggleproviderkeys('<?php echo $provider_id; ?>')" 
								>
									<option value="1" <?php if(   get_option( 'wsl_settings_' . $provider_id . '_enabled' ) ) echo "selected"; ?> ><?php _wsl_e("Yes", 'wordpress-social-login') ?></option>
									<option value="0" <?php if( ! get_option( 'wsl_settings_' . $provider_id . '_enabled' ) ) echo "selected"; ?> ><?php _wsl_e("No", 'wordpress-social-login') ?></option>
								</select>
							</td>
							<td style="width:160px">&nbsp;</td>
						</tr>

						<?php if ( $provider_new_app_link ){ ?>
							<?php if ( $require_client_id ){ // key or id ? ?>
								<tr valign="top" <?php if( ! get_option( 'wsl_settings_' . $provider_id . '_enabled' ) ) echo 'style="display:none"'; ?> class="wsl_tr_settings_<?php echo $provider_id; ?>" >
									<td><?php _wsl_e("Application ID", 'wordpress-social-login') ?>:</td>
									<td><input dir="ltr" type="text" name="<?php echo 'wsl_settings_' . $provider_id . '_app_id' ?>" value="<?php echo get_option( 'wsl_settings_' . $provider_id . '_app_id' ); ?>" ></td>
									<td><a href="javascript:void(0)" onClick="toggleproviderhelp('<?php echo $provider_id; ?>')"><?php _wsl_e("Where do I get this info?", 'wordpress-social-login') ?></a></td>
								</tr> 
							<?php } else { ?>
								<tr valign="top" <?php if( ! get_option( 'wsl_settings_' . $provider_id . '_enabled' ) ) echo 'style="display:none"'; ?> class="wsl_tr_settings_<?php echo $provider_id; ?>" >
									<td><?php _wsl_e("Application Key", 'wordpress-social-login') ?>:</td>
									<td><input dir="ltr" type="text" name="<?php echo 'wsl_settings_' . $provider_id . '_app_key' ?>" value="<?php echo get_option( 'wsl_settings_' . $provider_id . '_app_key' ); ?>" ></td>
									<td><a href="javascript:void(0)" onClick="toggleproviderhelp('<?php echo $provider_id; ?>')"><?php _wsl_e("Where do I get this info?", 'wordpress-social-login') ?></a></td>
								</tr>  
							<?php }; ?>	 

							<?php if( ! $require_api_key ) { ?>
								<tr valign="top" <?php if( ! get_option( 'wsl_settings_' . $provider_id . '_enabled' ) ) echo 'style="display:none"'; ?> class="wsl_tr_settings_<?php echo $provider_id; ?>" >
									<td><?php _wsl_e("Application Secret", 'wordpress-social-login') ?>:</td>
									<td><input dir="ltr" type="text" name="<?php echo 'wsl_settings_' . $provider_id . '_app_secret' ?>" value="<?php echo get_option( 'wsl_settings_' . $provider_id . '_app_secret' ); ?>" ></td>
									<td><a href="javascript:void(0)" onClick="toggleproviderhelp('<?php echo $provider_id; ?>')"><?php _wsl_e("Where do I get this info?", 'wordpress-social-login') ?></a></td>
								</tr>
							<?php } ?>

							<?php if( get_option( 'wsl_settings_development_mode_enabled' ) ) { ?>
								<?php if( $default_api_scope ) { ?>
									<tr valign="top" <?php if( ! get_option( 'wsl_settings_' . $provider_id . '_enabled' ) ) echo 'style="display:none"'; ?> class="wsl_tr_settings_<?php echo $provider_id; ?>" >
										<td><?php _wsl_e("Application Scope", 'wordpress-social-login') ?>:</td>
										<td><input dir="ltr" type="text" name="<?php echo 'wsl_settings_' . $provider_id . '_app_scope' ?>" value="<?php echo get_option( 'wsl_settings_' . $provider_id . '_app_scope' ); ?>" ></td>
									</tr>
								<?php } ?>

								<?php if( $provider_callback_url ) { ?>
									<tr valign="top" <?php if( ! get_option( 'wsl_settings_' . $provider_id . '_enabled' ) ) echo 'style="display:none"'; ?> class="wsl_tr_settings_<?php echo $provider_id; ?>" >
										<td><?php _wsl_e("Callback URL", 'wordpress-social-login') ?>:</td>
										<td><input dir="ltr" type="text" name="" value="<?php echo  strip_tags( $provider_callback_url ); ?>" readonly="readonly"></td>
									</tr>
								<?php } ?>
							<?php } ?>
						<?php } // if require registration ?> 
					</tbody>
				</table> 

				<?php if ( get_option( 'wsl_settings_' . $provider_id . '_enabled' ) ) : ?>
					<?php if (  $provider_id == "Steam" ) : ?> 
						<div class="fade updated">
							<p>
								<b><?php _wsl_e("Notes", 'wordpress-social-login') ?>:</b> 
							</p>
							<p>
								      1. <?php echo sprintf( _wsl__("<b>%s</b> do not require an external application, however if the Web API Key is provided, then WSL will be able to get more information about the connected %s users", 'wordpress-social-login'), $provider_name , $provider_name ) ?>. 
								<br />2. <?php echo sprintf( _wsl__("<b>%s</b> do not provide their user's email address and by default a random email will then be generated for them instead", 'wordpress-social-login'), $provider_name ) ?>. 
								
								<?php _wsl_e('To change this behaviour and to force new registered users to provide their emails before they get in, goto <b><a href="options-general.php?page=wordpress-social-login&wslp=bouncer">Bouncer</a></b> and enable <b>Profile Completion</b>', 'wordpress-social-login') ?>.
							</p>
						</div>
					<?php elseif ( $provider_new_app_link && strlen( trim( get_option( 'wsl_settings_' . $provider_id . '_app_secret' ) ) ) == 0 ) : ?>
						<div class="fade error">
							<p>
								<?php echo sprintf( _wsl__('<b>%s</b> requires that you create an external application linking your website to their API. To know how to create this application, click on &ldquo;Where do I get this info?&rdquo; and follow the steps', 'wordpress-social-login'), $provider_name, $provider_name ) ?>.
							</p>
						</div>
					<?php elseif ( in_array( $provider_id, array( "Twitter", "Identica", "Tumblr", "Goodreads", "500px", "Vkontakte", "Gowalla", "Steam" ) ) ) : ?>
						<div class="fade updated">
							<p>
								<b><?php _wsl_e("Note", 'wordpress-social-login') ?>:</b> 
								
								<?php echo sprintf( _wsl__("<b>%s</b> do not provide their user's email address and by default a random email will then be generated for them instead", 'wordpress-social-login'), $provider_name ) ?>. 
								
								<?php _wsl_e('To change this behaviour and to force new registered users to provide their emails before they get in, goto <b><a href="options-general.php?page=wordpress-social-login&wslp=bouncer">Bouncer</a></b> and enable <b>Profile Completion</b>', 'wordpress-social-login') ?>.
							</p>
						</div>
					<?php endif; ?> 
				<?php endif; ?> 

				<br />
				<div
					class="wsl_div_settings_help_<?php echo $provider_id; ?>" 
					style="<?php if( isset( $_REQUEST["enable"] ) && ! isset( $_REQUEST["settings-updated"] ) && $_REQUEST["enable"] == $provider_id ) echo "-"; // <= lolz ?>display:none;" 
				> 
					<hr class="wsl" />
					<?php if (  $provider_id == "Steam" ) : ?> 
					<?php elseif ( $provider_new_app_link  ) : ?> 
						<?php _wsl_e('<span style="color:#CB4B16;">Application</span> id and secret (also sometimes referred as <span style="color:#CB4B16;">Consumer</span> key and secret or <span style="color:#CB4B16;">Client</span> id and secret) are what we call an application credentials', 'wordpress-social-login') ?>. 
						
						<?php echo sprintf( _wsl__( 'This application will link your website <code>%s</code> to <code>%s API</code> and these credentials are needed in order for <b>%s</b> users to access your website', 'wordpress-social-login'), $_SERVER["SERVER_NAME"], $provider_name, $provider_name ) ?>. 
						<br />
						
						<?php _wsl_e("These credentials may also differ in format, name and content depending on the social network.", 'wordpress-social-login') ?> 
						<br />
						<br />
						
						<?php echo sprintf( _wsl__('To enable authentication with this provider and to register a new <b>%s API Application</b>, follow the steps', 'wordpress-social-login'), $provider_name ) ?>
						:<br />
					<?php else: ?>  
							<p><?php echo sprintf( _wsl__('<b>Done.</b> Nothing more required for <b>%s</b>', 'wordpress-social-login'), $provider_name) ?>.</p> 
					<?php endif; ?>  
					<div style="margin-left:40px;"> 
						<?php if ( $provider_new_app_link  ) : ?> 
							<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> <?php echo sprintf( _wsl__( 'First go to: <a href="%s" target ="_blank">%s</a>', 'wordpress-social-login'), $provider_new_app_link, $provider_new_app_link ) ?></p>

							<?php if ( $provider_id == "Google" ) : ?>   
								<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> <?php _wsl_e('On the <b>Dashboard sidebar</b> click on <b>Project</b> then click <em style="color:#0147bb;">&ldquo;Create Project&rdquo;</em>', 'wordpress-social-login') ?>.</p> 
								<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> <?php _wsl_e("Once the project is created. Select that project, then <b>APIs & auth</b> &gt; <b>Consent screen</b> and fill the required information", 'wordpress-social-login') ?>.</p>
								<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> <?php _wsl_e('Then <b>APIs & auth</b> &gt; <b>APIs</b> and enable <em style="color:#0147bb;">&ldquo;Google+ API&rdquo;</em>. If you want to import the user contatcs enable <em style="color:#0147bb;">&ldquo;Contacts API&rdquo;</em> as well', 'wordpress-social-login') ?>.</p>
								<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> <?php _wsl_e("After that you will need to create an new application: <b>APIs & auth</b> &gt; <b>Credentials</b> and then click <em style=\"color:#0147bb;\">&ldquo;Create new Client ID&rdquo;</em>", 'wordpress-social-login') ?>.</p>
								</p>
								<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> <?php _wsl_e("On the <b>&ldquo;Create Client ID&rdquo;</b> popup", 'wordpress-social-login') ?> :</p>
								<ul style="margin-left:35px">
									<li><?php _wsl_e('Select <em style="color:#0147bb;">&ldquo;Web application&rdquo;</em> as your application type', 'wordpress-social-login') ?>.</li>
									<li><?php _wsl_e("Put your website domain in the <b>Authorized JavaScript origins</b> field. This should match with the current hostname", 'wordpress-social-login') ?> <em style="color:#CB4B16;"><?php echo $_SERVER["SERVER_NAME"]; ?></em>.</li>
									<li><?php _wsl_e("Provide this URL as the <b>Authorized redirect URI</b> for your application", 'wordpress-social-login') ?>: <br /><?php echo $provider_callback_url ?></li> 
								</ul>
							<?php elseif ( $provider_id == "Facebook" ) : ?>   
								<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> <?php _wsl_e("Select <b>Add a New App</b> from the <b>Apps</b> menu at the top", 'wordpress-social-login') ?>.</p> 
								<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> <?php _wsl_e("Fill out Display Name, Namespace, choose a category and click <b>Create App</b>", 'wordpress-social-login') ?>.</p> 
								<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> <?php _wsl_e("Go to Settings page and click on <b>Add Platform</b>. Choose website and enter in the new screen your website url in <b>App Domains</b> and <b>Site URL</b> fields", 'wordpress-social-login') ?>.
									<?php _wsl_e("They should match with the current hostname", 'wordpress-social-login') ?> <em style="color:#CB4B16;"><?php echo $_SERVER["SERVER_NAME"]; ?></em>.</p> 
								<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> <?php _wsl_e("Go to the <b>Status & Review</b> page and choose <b>yes</b> where it says <b>Do you want to make this app and all its live features available to the general public?</b>", 'wordpress-social-login') ?>.</p>  
							<?php else: ?>  
								<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> <?php _wsl_e("Create a new application", 'wordpress-social-login') ?>.</p> 
								<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> <?php _wsl_e("Fill out any required fields such as the application name and description", 'wordpress-social-login') ?>.</p> 
							<?php endif; ?> 

							<?php if ( $provider_callback_url && $provider_id != "Google" && $provider_id != "Facebook"  ) : ?> 
								<p>
									<?php echo "<b>" . ++$setupsteps . "</b>." ?> <?php _wsl_e("Provide this URL as the <b>Callback URL</b> for your application", 'wordpress-social-login') ?>:
									<br />
									<?php echo $provider_callback_url ?>
								</p> 
							<?php endif; ?> 

							<?php if ( $provider_id == "Live" ) : ?>
								<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> <?php _wsl_e("Put your website domain in the <b>Redirect Domain</b> field. This should match with the current hostname", 'wordpress-social-login') ?> <em style="color:#CB4B16;"><?php echo $_SERVER["SERVER_NAME"]; ?></em>.</p>
							<?php endif; ?> 

							<?php if ( $provider_id == "LinkedIn" ) : ?>
								<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> <?php _wsl_e('Choose <b>Live</b> on <b>Live Status</b>.', 'wordpress-social-login') ?></p> 
							<?php endif; ?> 

							<?php if ( $provider_id == "Google" ) : ?>
								<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> <?php _wsl_e("Once you have registered past the created application credentials (Client ID and Secret) into the boxes above", 'wordpress-social-login') ?>.</p> 
							<?php elseif ( $provider_id == "Twitter" ) : ?>
								<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> <?php _wsl_e("Once you have registered, past the created application credentials (Consumer Key and Secret) into the boxes above", 'wordpress-social-login') ?>.</p> 
							<?php elseif ( $provider_id == "Facebook" ) : ?>
								<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> <?php _wsl_e("Go back to the <b>Dashboard</b> page and past the created application credentials (APP ID and Secret) into the boxes above", 'wordpress-social-login') ?>.</p> 
							<?php else: ?>
								<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> <?php _wsl_e("Once you have registered, past the created application credentials into the boxes above", 'wordpress-social-login') ?>.</p> 
							<?php endif; ?> 

						<?php endif; ?> 
						
						<?php if ( $provider_id == "Facebook" ) : ?>
							<table style="text-align: center;margin-bottom:12px;">
								<td><a class="span4 thumbnail" href="http://hybridauth.sf.net/userguide/img/setup/facebook/1.png" target="_blank"><img src="http://hybridauth.sf.net/userguide/img/setup/facebook/1.png"></a></td>
								<td><a class="span4 thumbnail" href="http://hybridauth.sf.net/userguide/img/setup/facebook/2.png" target="_blank"><img src="http://hybridauth.sf.net/userguide/img/setup/facebook/2.png"></a></td>
								<td><a class="span4 thumbnail" href="http://hybridauth.sf.net/userguide/img/setup/facebook/3.png" target="_blank"><img src="http://hybridauth.sf.net/userguide/img/setup/facebook/3.png"></a></td>
							</table> 
						<?php endif; ?> 

						<?php if ( $provider_id == "Google" ) : ?>
							<table style="text-align: center;margin-bottom:12px;">
								<td><a class="span4 thumbnail" href="http://hybridauth.sf.net/userguide/img/setup/google/1.png" target="_blank"><img src="http://hybridauth.sf.net/userguide/img/setup/google/1.png"></a></td>
								<td><a class="span4 thumbnail" href="http://hybridauth.sf.net/userguide/img/setup/google/2.png" target="_blank"><img src="http://hybridauth.sf.net/userguide/img/setup/google/2.png"></a></td>
								<td><a class="span4 thumbnail" href="http://hybridauth.sf.net/userguide/img/setup/google/3.png" target="_blank"><img src="http://hybridauth.sf.net/userguide/img/setup/google/3.png"></a></td>
							</table> 
						<?php endif; ?> 

						<?php if ( $provider_id == "Twitter" ) : ?>
							<table style="text-align: center;margin-bottom:12px;">
								<td><a class="span4 thumbnail" href="http://hybridauth.sf.net/userguide/img/setup/twitter/1.png" target="_blank"><img src="http://hybridauth.sf.net/userguide/img/setup/twitter/1.png"></a></td>
								<td><a class="span4 thumbnail" href="http://hybridauth.sf.net/userguide/img/setup/twitter/2.png" target="_blank"><img src="http://hybridauth.sf.net/userguide/img/setup/twitter/2.png"></a></td>
								<td><a class="span4 thumbnail" href="http://hybridauth.sf.net/userguide/img/setup/twitter/3.png" target="_blank"><img src="http://hybridauth.sf.net/userguide/img/setup/twitter/3.png"></a></td>
							</table> 
						<?php endif; ?> 

						<?php if ( $provider_id == "WordPress" ) : ?>
							<table style="text-align: center;margin-bottom:12px;">
								<td><a class="span4 thumbnail" href="http://hybridauth.sf.net/userguide/img/setup/wordpress/1.png" target="_blank"><img src="http://hybridauth.sf.net/userguide/img/setup/wordpress/1.png"></a></td>
								<td><a class="span4 thumbnail" href="http://hybridauth.sf.net/userguide/img/setup/wordpress/2.png" target="_blank"><img src="http://hybridauth.sf.net/userguide/img/setup/wordpress/2.png"></a></td>
								<td><a class="span4 thumbnail" href="http://hybridauth.sf.net/userguide/img/setup/wordpress/3.png" target="_blank"><img src="http://hybridauth.sf.net/userguide/img/setup/wordpress/3.png"></a></td>
							</table> 
						<?php endif; ?> 
					</div>

					<?php if ( $provider_new_app_link  ) : ?> 
						<hr />
						<p>
							<b><?php _wsl_e("And that's it!", 'wordpress-social-login') ?></b> 
							<br />
							<?php echo sprintf( _wsl__( 'If for some reason you still can\'t manage to create an application for %s, first try to <a href="https://www.google.com/search?q=%s API create application" target="_blank">Google it</a>, then check it on <a href="http://www.youtube.com/results?search_query=%s API create application " target="_blank">Youtube</a>, and if nothing works <a href="options-general.php?page=wordpress-social-login&wslp=help">ask for support</a>', 'wordpress-social-login'), $provider_name, $provider_name, $provider_name ) ?>.
						</p> 
					<?php endif; ?> 
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
