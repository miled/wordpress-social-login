<?php
/*!
* WordPress Social Login
*
* https://miled.github.io/wordpress-social-login/ | https://github.com/miled/wordpress-social-login
*   (c) 2011-2020 Mohamed Mrassi and contributors | https://wordpress.org/plugins/wordpress-social-login/
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

	$assets_base_url = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . 'assets/img/';
	$assets_setup_base_url = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . 'assets/img/setup/';

	// save settings?
	if( isset( $_REQUEST["enable"] ) && $_REQUEST["enable"] && ! isset( $_REQUEST["settings-updated"] ))
	{
		$provider_id = $_REQUEST["enable"];

        update_option( 'wsl_settings_' . $provider_id . '_enabled', 1 );
	}
?>
<div class="postbox">
	<div class="inside">
		<h3><?php _wsl_e("Add more providers", 'wordpress-social-login') ?></h3>

		<div style="padding:0 20px;">
			<p style="padding:0;margin:0 0 12px;">
				<?php _wsl_e('We have enabled <b>Facebook</b>, <b>Google</b> and <b>Twitter</b> by default, however you may add even more. <b>Just Click</b> on the icons and we will guide you through', 'wordpress-social-login') ?>.
			</p>

			<div style="padding: 10px; border: 1px solid #ddd; background-color: #fff; text-align: center;">
				<?php
					$nb_used = count( $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG );

					foreach( $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG AS $item )
					{
						$provider_id   = isset( $item["provider_id"]   ) ? $item["provider_id"]   : '';
						$provider_name = isset( $item["provider_name"] ) ? $item["provider_name"] : '';

						if( isset( $item["default_network"] ) && $item["default_network"] )
						{
							continue;
						}

						if( ! get_option( 'wsl_settings_' . $provider_id . '_enabled' ) )
						{
							?>
								<a href="options-general.php?page=wordpress-social-login&wslp=networks&enable=<?php echo $provider_id ?>&_=<?php echo time(); ?>#setup<?php echo strtolower( $provider_id ) ?>"><img src="<?php echo $assets_base_url . '32x32/icondock/' . strtolower( $provider_id ) . '.png' ?>" alt="<?php echo $provider_name ?>" title="<?php echo $provider_name ?>" /></a>
							<?php

							$nb_used--;
						}
					}

					if( $nb_used == count( $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG ) )
					{
						_wsl_e("Well! none left.", 'wordpress-social-login');
					}
				?>
			</div>
		</div>
	</div>
</div>

<!-- -->

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
			$provider_callback_url  = '<span style="color:green">' . $endpoint_url . 'callbacks/' . strtolower($provider_id) . '.php</span>';
		}

		$setupsteps = 0;
?>
		<a name="setup<?php echo strtolower( $provider_id ) ?>"></a>
		<div class="stuffbox" id="namediv">
			<h3>
				<label class="wp-neworks-label">
					<img alt="<?php echo $provider_name ?>" title="<?php echo $provider_name ?>" src="<?php echo $assets_base_url . '16x16/' . strtolower( $provider_id ) . '.png' ?>" style="vertical-align: top;width:16px;height:16px;" /> <?php _wsl_e( $provider_name, 'wordpress-social-login' ) ?>
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
							<?php } ?>

							<?php if( !$require_client_id || $require_client_id === 'both' ) { ?>
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
					style="<?php if( isset( $_REQUEST["enable"] ) && ! isset( $_REQUEST["settings-updated"] ) && $_REQUEST["enable"] == $provider_id ) echo "-"; // <= lolz ?>display:none;padding-left: 12px;padding-right: 12px;"
				>
					<hr class="wsl" />
					<?php if (  $provider_id == "Steam" ) : ?>
					<?php elseif ( $provider_new_app_link  ) : ?>
						<?php _wsl_e('<span style="color:#CB4B16;">Application <strong>ID</strong> and <strong>Secret</strong></span> (also sometimes referred as <span style="color:#CB4B16;">API</span> key and secret or <span style="color:#CB4B16;">Consumer</span> key and secret or <span style="color:#CB4B16;">Client</span> ID and secret) are what we call an application credentials', 'wordpress-social-login') ?>.

						<?php echo sprintf( _wsl__( 'The application will link your website to <b>%s\'s API</b> and it\'s needed in order for <b>%s\'s Users</b> to access your website', 'wordpress-social-login'), $provider_name, $provider_name ) ?>.
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

                            <p><?php echo "<b>" . ++$setupsteps . "</b>." ?> <?php _wsl_e("Create a new application and fill out any required fields such as the application name, description, your domanin name, etc", 'wordpress-social-login') ?>.</p>

                            <p>
                                <?php echo "<b>" . ++$setupsteps . "</b>." ?> <?php _wsl_e("If asked about an authorized <b>Callback URL</b> for your application, you may provide this link", 'wordpress-social-login') ?>:
                                <br />
                                <?php echo $provider_callback_url ?>
                            </p>

                            <p><?php echo "<b>" . ++$setupsteps . "</b>." ?> <?php _wsl_e("Once you have registered, past the created application credentials into the boxes above", 'wordpress-social-login') ?>.</p>
						<?php endif; ?>
					</div>

					<?php if ( $provider_new_app_link  ) : ?>
						<hr />
						<p>
							<?php _wsl_e("And that's pretty much all.", 'wordpress-social-login') ?>
							<?php echo sprintf( _wsl__( 'If for whatever reason you can\'t manage to create an application for %s, first try to <a href="https://www.google.com/search?q=%s API create application" target="_blank">Google it</a>, then check it on <a href="http://www.youtube.com/results?search_query=%s API create application " target="_blank">Youtube</a>, and if nothing works you may ask the WordPress community for <a href="http://miled.github.io/wordpress-social-login/support.html" target="_blank">Help & Support</a>', 'wordpress-social-login'), $provider_name, $provider_name, $provider_name ) ?>.
						</p>
					<?php endif; ?>
				</div>
			</div>
		</div>
<?php
	endforeach;
?>
	<input type="submit" class="button-primary" value="<?php _wsl_e("Save Settings", 'wordpress-social-login') ?>" />

    &nbsp; <a href="javascript:window.scrollTo(0, 0);"><?php _wsl_e("↑ Scroll back to top", 'wordpress-social-login') ?></a>
<?php
	// HOOKABLE:
	do_action( "wsl_component_networks_setup_end" );
}

// --------------------------------------------------------------------
