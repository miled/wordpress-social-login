<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*   (c) 2013 Mohamed Mrassi and other contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

/**
* Social networks configuration and setup
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 

// --------------------------------------------------------------------

wsl_admin_welcome_panel();

// --------------------------------------------------------------------

global $wpdb;

if( isset( $_REQUEST["enable"] ) && $_REQUEST["enable"] ){
	$provider_id = $_REQUEST["enable"];

	update_option( 'wsl_settings_' . $provider_id . '_enabled', 1 );
}
?>

<script>
	function toggleproviderkeys(idp){
		if(typeof jQuery=="undefined"){
			alert( "Error: WordPress Social Login require jQuery to be installed on your wordpress in ordezr to works!" );

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
			alert( "Error: WordPress Social Login require jQuery to be installed on your wordpress in ordezr to works!" );

			return false;
		}

		jQuery('.wsl_div_settings_help_' + idp).show();
		
		return false;
	}
</script>

<form method="post" id="wsl_setup_form" action="options.php"> 

	<?php settings_fields( 'wsl-settings-group' ); ?>
	
	
	
	
	
	
<div class="metabox-holder columns-2" id="post-body">

<table width="100%">
<tbody>
<tr valign="top">
<td>
							
<div  id="post-body-content">
 
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
					
					<?php _wsl_e(sprintf( "<b>%s</b> do not provide their user's email address and by default a random email will then be generated for them instead", $provider_name ), 'wordpress-social-login') ?>. 
					
					<?php _wsl_e('To change this behaviour and to force new registered users to provide their emails before they get in, goto <b><a href="options-general.php?page=wordpress-social-login&wslp=bouncer">Bouncer</a></b> and enable <b>Email Validation</b>', 'wordpress-social-login') ?>.
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
						
						<?php _wsl_e( sprintf( 'This application will link your website <code>%s</code> to <code>%s API</code> and these credentials are needed in order for <b>%s</b> users to access your website', $_SERVER["SERVER_NAME"], $provider_name, $provider_name ), 'wordpress-social-login') ?>. 
						<br />
						
						<?php _wsl_e("These credentials may also differ in format, name and content depending on the social network.", 'wordpress-social-login') ?> 
						<br />
						<br />
						
						<?php _wsl_e(sprintf( 'To enable authentication with this provider and to register a new <b>%s API Application</b>, carefully follow the steps', $provider_name ), 'wordpress-social-login') ?>
						:<br />
					<?php else: ?>  
							<p><?php _wsl_e(sprintf('<b>Done.</b> Nothing more required for <b>%s</b>', $provider_name), 'wordpress-social-login') ?>.</p> 
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
								<?php _wsl_e( sprintf( 'If for some reason you still can\'t figure it out, first try to a) <a class="button-primary" href="https://www.google.com/search?q=%s API create application" target="_blank">Google it</a>, then check it on b) <a class="button-primary" href="http://www.youtube.com/results?search_query=%s API create application " target="_blank">Youtube</a> and if nothing works c) <a class="button-primary" href="options-general.php?page=wordpress-social-login&wslp=help">ask for support</a>', $provider_name, $provider_name ), 'wordpress-social-login') ?>
								.
							</p> 
						<?php endif; ?> 
					</div>  

				</div>  
				
			</div>
		</div>  
 
<?php
	endforeach;
?>

<table width="100%" border="0">
  <tr>
    <td width="120" valign="top"><input type="submit" class="button-primary" value="Save Settings" /></td>
  </tr> 
  <tr> 
    <td align="left">
		<p><?php _wsl_e("And you could add even more of them, <b>Just Click</b> and we will guide you through", 'wordpress-social-login') ?> :</p>
		<?php 
			$assets_base_url = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . '/assets/img/32x32/icondock/';

			$nb_used = count( $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG );
			foreach( $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG AS $item ){
				$provider_id                = @ $item["provider_id"];
				$provider_name              = @ $item["provider_name"];
				$provider_cat               = @ $item["cat"];

				if( isset( $item["default_network"] ) && $item["default_network"] ){
					continue;
				}

				if( ! get_option( 'wsl_settings_' . $provider_id . '_enabled' ) ){
					?>
						<a href="options-general.php?page=wordpress-social-login&wslp=networks&enable=<?php echo $provider_id ?>#setup<?php echo strtolower( $provider_id ) ?>"><img src="<?php echo $assets_base_url . strtolower( $provider_id ) . '.png' ?>" alt="<?php echo $provider_name ?>" title="<?php echo $provider_name ?>" /></a>
					<?php

					$nb_used--;
				}
			}

			if( $nb_used == count( $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG ) ){
				_wsl_e("Well! none left.", 'wordpress-social-login');
			}
		?> 
	</td>
  </tr> 
</table>

<a name="wslsettings"></a> 

</div>

</td>
<td width="10"></td>
<td width="400">


<div class="postbox " id="linksubmitdiv"> 
	<div class="inside">
		<div id="submitlink" class="submitbox"> <h3 style="cursor: default;"><?php _wsl_e("Why, hello there", 'wordpress-social-login') ?></h3>
			<div id="minor-publishing"> 

				<div style="display:none;"><input type="submit" value="Save" class="button" id="save" name="save"></div>

				<div id="misc-publishing-actions">
					<div class="misc-pub-section"> 
						<p style="line-height: 19px;font-size: 13px;" align="justify">
							<?php _wsl_e('If you are still new to things, we recommend that you read the <b><a href="http://hybridauth.sourceforge.net/wsl/index.html" target="_blank">Plugin User Guide</a></b> and to make sure your server settings meet this <b><a href="options-general.php?page=wordpress-social-login&amp;wslp=diagnostics">Plugin Requirements</a></b>', 'wordpress-social-login') ?>.
						</p>
						<p style="line-height: 19px;" align="justify">
							<?php _wsl_e('If you run into any issue then refer to <b><a href="options-general.php?page=wordpress-social-login&wslp=help">Help & Support</a></b> to konw how to reach me', 'wordpress-social-login') ?>.
						</p> 
					</div>
				</div> 
			</div>

			<div id="major-publishing-actions"> 
				<div id="publishing-action">
					<input type="submit" value="Save Settings" accesskey="p" id="publish" class="button-large button-primary" name="save">
				</div>
				<div class="clear"></div>
			</div> 
		</div>
	</div>
</div>


<?php 
	$sql = "SELECT count( * ) as items FROM `{$wpdb->prefix}users`"; 
	$rs1 = $wpdb->get_results( $sql );  

	$sql = "SELECT count( * ) as items FROM `{$wpdb->prefix}usermeta` where meta_key = 'wsl_user'"; 
	$rs2 = $wpdb->get_results( $sql );  

	$total_users      = (int) $rs1[0]->items;
	$total_users_wsl  = (int) $rs2[0]->items;
	$users_conversion = ( 100 * $total_users_wsl ) / $total_users;

	if( $total_users_wsl && wsl_is_component_enabled( "basicinsights" ) ){
?> 
<div class="postbox " id="linksubmitdiv"> 
	<div class="inside">
		<div id="submitlink" class="submitbox"> 
			<h3 style="cursor: default;"><?php _wsl_e("Insights", 'wordpress-social-login') ?></h3>
			<div id="minor-publishing">  
				<div id="misc-publishing-actions"> 

					<div style="padding:20px;padding-top:0px;"> 
						<h4 style="border-bottom:1px solid #ccc"><?php _wsl_e("Conversions", 'wordpress-social-login') ?></h4>
						<table width="90%"> 
							<tr>
								<td width="60%"><?php _wsl_e("WP users", 'wordpress-social-login') ?></td><td><?php echo $total_users; ?></td>
							<tr>
							</tr>
								<td><?php _wsl_e("WSL users", 'wordpress-social-login') ?></td><td><?php echo $total_users_wsl; ?></td>
							<tr>
							</tr>
								<td><?php _wsl_e("Conversions", 'wordpress-social-login') ?></td><td style="border-top:1px solid #ccc">+<b><?php echo number_format($users_conversion, 2, '.', ''); ?></b> %</td>
							</tr>
						</table>
						
						<?php 
							$sql = "SELECT meta_value, count( * ) as items FROM `{$wpdb->prefix}usermeta` where meta_key = 'wsl_user' group by meta_value order by items desc ";

							$rs1 = $wpdb->get_results( $sql );  

							$assets_base_url = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . '/assets/img/16x16/';
						?> 
						<h4 style="border-bottom:1px solid #ccc"><?php _wsl_e("By provider", 'wordpress-social-login') ?></h4>
						<table width="90%">
							<?php 
								foreach( $rs1 as $item ){
									if( ! $item->meta_value ) $item->meta_value = "Unknown"; 
								?>
									<tr>
										<td width="60%">
											<img src="<?php echo $assets_base_url . strtolower( $item->meta_value ) . '.png' ?>" style="vertical-align:top;width:16px;height:16px;" /> <?php echo $item->meta_value; ?> 
										</td>
										<td>
											<?php echo $item->items; ?>
										</td>
									</tr>
								<?php
								}
							?> 
							</tr>
								<td align="right">&nbsp;</td><td style="border-top:1px solid #ccc"><b><?php echo $total_users_wsl; ?></b> <?php _wsl_e("WSL users", 'wordpress-social-login') ?></td>
							</tr>
						</table>

						<?php 
							$sql = "SELECT meta_value, count( * ) as items FROM `{$wpdb->prefix}usermeta` where meta_key = 'wsl_user_gender' group by meta_value order by items desc "; 

							$rs = $wpdb->get_results( $sql ); 
						?>
						<h4 style="border-bottom:1px solid #ccc"><?php _wsl_e("By gender", 'wordpress-social-login') ?></h4>
						<table width="90%">
							<?php
								foreach( $rs as $item ){
									if( ! $item->meta_value ) $item->meta_value = "Unknown";
								?>
									<tr>
										<td width="60%">
											<?php echo ucfirst( $item->meta_value ); ?>
										</td>
										<td>
											<?php echo $item->items; ?>
										</td>
									</tr>
								<?php
								}
							?>
						</table>

						<?php 
							$sql = "SELECT meta_value, count( * ) as items FROM `{$wpdb->prefix}usermeta` where meta_key = 'wsl_user_age' group by meta_value order by items desc limit 21"; 

							$rs = $wpdb->get_results( $sql ); 
						?>
						<h4 style="border-bottom:1px solid #ccc"><?php _wsl_e("By age", 'wordpress-social-login') ?></h4>
						<table width="90%">
							<?php
								$t_ages = 0;
								$n_ages = 0;
								foreach( $rs as $item ){
									if( ! $item->meta_value ){
										$item->meta_value = "Unknown";
									}
									else{
										$t_ages += (int) $item->meta_value;
										$n_ages++;
									}
								?>
									<tr>
										<td width="60%">
											<?php echo $item->meta_value; ?>
										</td>
										<td>
											<?php echo $item->items; ?>
										</td>
									</tr>
								<?php
								}
								if( $n_ages ) $a_ages = $t_ages/$n_ages;
							?>
						</td>
						</tr>
						</tr>
							<td align="right">&nbsp;</td><td style="border-top:1px solid #ccc"><b><?php echo number_format($a_ages, 1, '.', ''); ?></b> <?php _wsl_e("yrs in average", 'wordpress-social-login') ?></td>
						</tr> 
						</table>
					</div> 
				
				</div> 
			</div> 
		</div>
	</div>
</div> 

<?php
	} //if( $total_users_wsl ){
?>


</td>
</tr>
</table>

</div>

</form>
