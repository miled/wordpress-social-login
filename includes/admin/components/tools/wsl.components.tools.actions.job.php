<?php
/*!
* WordPress Social Login
*
* http://miled.github.io/wordpress-social-login/ | https://github.com/miled/wordpress-social-login
*  (c) 2011-2014 Mohamed Mrassi and contributors | http://wordpress.org/plugins/wordpress-social-login/
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 

// --------------------------------------------------------------------	

function wsl_component_tools_do_diagnostics()
{
?>
<style>
	table td, table th { border: 1px solid #DDDDDD; }
	table th label { font-weight: bold; }
</style>
<div class="metabox-holder columns-2" id="post-body">
	<div class="stuffbox">
		<h3>
			<label><?php _wsl_e("WordPress Social Login Diagnostics", 'wordpress-social-login') ?></label>
		</h3>
		<div class="inside"> 
			<br />
			<table class="wp-list-table widefat">
				<tr>
					<th width="200">
						<label>PHP Version</label>
					</th>
					<td>
						<p>PHP >= 5.2.0 installed.</p>
					</td>
					<td width="60">
						<?php 
							if ( version_compare( PHP_VERSION, '5.2.0', '>=' ) )
							{
								echo "<b style='color:green;'>OK!</b>";
							}
							else
							{ 
								echo "<b style='color:red;'>FAIL!</b>";
							}
						?>
					</td>
				</tr> 
				<tr>
					<th width="200">
						<label>PHP Sessions</label>
					</th>
					<td>
						<p>PHP/Session enabled and working.</p>
						<?php
							if( ! ( isset( $_SESSION["wsl::plugin"] ) && $_SESSION["wsl::plugin"] ) )
							{ 
								?>
								<hr />
								
								<p><b>Error</b>: PHP Sessions are not working as expected.</p>

								<p>
									WSL has been made to work with PHP's default SESSION handling (sometimes this occur when the php session is disabled, renamed or when having permissions issues).
							
									If you are using a reverse proxy like Varnish it is possible that WordPress's default user cookies are being stripped. If this is the case, please review your VCL file.
								</p>
								<p>By default, WSL will requires these two urls to be white-listed:</p>
<pre style="background-color: #FFFFE0;border:1px solid #E6DB55; border-radius: 3px;padding: 10px;margin:2px;">
<?php
	echo site_url( 'wp-login.php', 'login_post' );
	echo '<br />';
	echo WORDPRESS_SOCIAL_LOGIN_HYBRIDAUTH_ENDPOINT_URL;
?>
</pre>
								<?php
							}
						?>
					</td>
					<td>
						<?php 
							if( isset( $_SESSION["wsl::plugin"] ) && $_SESSION["wsl::plugin"] )
							{
								echo "<b style='color:green;'>OK!</b>";
							}
							else
							{ 
								echo "<b style='color:red;'>FAIL!</b>";
							}
						?>
					</td>
				</tr> 
				<tr>
					<th width="200">
						<label>PHP CURL Extension</label>
					</th>
					<td>
						<p>PHP CURL extension enabled.</p>
					</td>
					<td>
						<?php 
							if ( function_exists('curl_init') )
							{
								echo "<b style='color:green;'>OK!</b>";
							}
							else
							{ 
								echo "<b style='color:red;'>FAIL!</b>";
							}
						?>
					</td>
				</tr> 
				<tr>
					<th width="200">
						<label>PHP CURL/SSL Extension</label>
					</th>
					<td>
						<p>PHP CURL extension with SSL enabled.</p>
					</td>
					<td>
						<?php 
							if ( function_exists('curl_init') )
							{
								$curl_version = curl_version();

								if ( $curl_version['features'] & CURL_VERSION_SSL )
								{
									echo "<b style='color:green;'>OK!</b>";
								}
								else
								{ 
									echo "<b style='color:red;'>FAIL!</b>";
								}
							}
							else
							{ 
								echo "<b style='color:red;'>FAIL!</b>";
							}
						?>
					</td>
				</tr> 
				<tr>
					<th width="200">
						<label>PHP Register Globals</label>
					</th>
					<td>
						<p>PHP REGISTER_GLOBALS OFF.</p>
					<?php 
						if( ini_get('register_globals') )
						{ 
							?>
								<hr />
								<p><b>Error</b>: REGISTER_GLOBALS are On. This will prevent WSL from working properly and will result on an infinite loop on WSL authentication page.</p>
								<div style="background-color: #FFFFE0;border:1px solid #E6DB55; border-radius: 3px;padding:5px;font-size: 12px;">  
										<p>The solution is to make a trouble ticket with your web host to disable it, Or, if you have a dedicated server and you know what are you doing then edit php.ini file and turn it Off :</p>

										<span style="border:1px solid #E6DB55;padding:5px;"> register_globals = Off</span>
									</p>
								</div>
							<?php
						}
					?>
					</td>
					<td>
						<?php 
							if( ! ini_get('register_globals') )
							{
								echo "<b style='color:green;'>OK!</b>";
							}
							else
							{ 
								echo "<b style='color:red;'>FAIL!</b>";
							}
						?>
					</td>
				</tr>

				<!-- this should keep Mika happy -->
				<tr>
					<th width="200">
						<label>WSL end-points</label>
					</th>
					<td>
						<p>Check if WSL end-points urls are reachable.</p>

						<div id="mod_security_warn" style="display:none;">
							<hr />
							<p><b>Error</b>: WSL end-points urls are not reachable! If your hosting provider is using mod_security then request to whitelist your domain.</p>
							<p>* This problem has been encountered with Host Gator and GoDaddy.</p>
							<p>By default, WSL will requires these two urls to be white-listed:</p>
<pre style="background-color: #FFFFE0;border:1px solid #E6DB55; border-radius: 3px;padding: 10px;margin:2px;">
<?php
	echo site_url( 'wp-login.php', 'login_post' );
	echo '<br />';
	echo WORDPRESS_SOCIAL_LOGIN_HYBRIDAUTH_ENDPOINT_URL;
?>
</pre>
						</div>
					</td>
					<td width="60">
						<span id="mod_security">testing..</span>
						<script>
							jQuery(document).ready(function($) {
								jQuery.ajax({
									url: '<?php echo WORDPRESS_SOCIAL_LOGIN_HYBRIDAUTH_ENDPOINT_URL; ?>',
									data: 'url=http://example.com',
									success: function () {
										jQuery('#mod_security').html( '<b style="color:green;">OK!</b>' );
									},
									error: function (xhr, ajaxOptions, thrownError) {
										jQuery('#mod_security').html( '<b style="color:red;">FAIL!</b>' );
										jQuery('#mod_security_warn').show();
									}
								});
							});
						</script>
					</td>
				</tr> 

				<?php
					global $wpdb;

					$db_check_profiles = $wpdb->get_var( "SHOW TABLES LIKE '{$wpdb->prefix}wslusersprofiles'" ) === $wpdb->prefix . 'wslusersprofiles' ? 1 : 0;
					$db_check_contacts = $wpdb->get_var( "SHOW TABLES LIKE '{$wpdb->prefix}wsluserscontacts'" ) === $wpdb->prefix . 'wsluserscontacts' ? 1 : 0;

					$test = $db_check_profiles && $db_check_contacts ? false : true;
				?>
				<tr>
					<th width="200">
						<label>WSL database tables</label>
					</th>
					<td>
						<p>Check if WSL database tables (<code>wslusersprofiles</code> and <code>wsluserscontacts</code>) exist.</p>
						<?php 
							if( $test )
							{
								?>
									<hr />
									<p>
										<b>Error:</b> One or more of WordPress Social Login tables do not exist. This may prevent this plugin form working correctly. To fix this, navigate to <b>Tools</b> tab then <b><a href="options-general.php?page=wordpress-social-login&wslp=tools#repair-tables">Repair WSL tables</a></b>.
									</p>
								<?php
							}
						?>
					</td>
					<td width="60">
						<?php
							if( $test )
							{
								echo "<b style='color:red;'>FAIL!</b>";
							}
							else
							{
								echo "<b style='color:green;'>OK!</b>";
							}
						?>
					</td>
				</tr> 
				
				<?php
					$test = class_exists( 'OAuthConsumer', false ) ? true : false;
				?> 
				<tr>
					<th width="200">
						<label>OAUTH Library</label>
					</th>
					<td>
						<p>Check if OAUTH Library is auto-loaded by another plugin.</p>
						<?php 
							if( $test )
							{
								?>
									<hr />
									<p>
										<b>Error:</b> OAUTH Library is in use. This will prevent Twitter, LinkedIn and few other providers from working. Please, inform the developer of that plugin not to auto-include the file below and to use OAUTH Library only when required.
									</p>
<pre style="background-color: #FFFFE0;border:1px solid #E6DB55; border-radius: 3px;padding: 10px;margin:2px;">
<?php
	$reflector = new ReflectionClass( 'OAuthConsumer' );
	echo $reflector->getFileName();
?>
</pre>
								<?php
							}
						?>
					</td>
					<td>
						<?php 
							if( $test )
							{
								echo "<b style='color:red;'>FAIL!</b>";
							}
							else
							{
								echo "<b style='color:green;'>OK!</b>";
							}
						?>
					</td>
				</tr> 

				<?php
					$test = class_exists( 'Hybrid_Auth', false ) ? true : false;
				?> 				
				<tr>
					<th width="200">
						<label>Hybridauth Library</label>
					</th>
					<td>
						<p>Check if the Hybridauth Library is auto-loaded by another plugin.</p>
						<?php 
							if( $test )
							{
								?>
									<hr />
									<p>
										<b>Error:</b> Hybridauth Library is in use. This MAY prevent WSL from working. Please, inform the developer of that plugin not to auto-include the file below and to use Hybridauth only when required.
									</p>
<pre style="background-color: #FFFFE0;border:1px solid #E6DB55; border-radius: 3px;padding: 10px;margin:2px;">
<?php
	$reflector = new ReflectionClass( 'Hybrid_Auth' );
	echo $reflector->getFileName();
?>
</pre>
								<?php
							}
						?>
					</td>
					<td>
						<?php 
							if( $test )
							{
								echo "<b style='color:red;'>FAIL!</b>";
							}
							else
							{
								echo "<b style='color:green;'>OK!</b>";
							}
						?>
					</td>
				</tr>

				<?php
					$test = class_exists( 'BaseFacebook', false ) ? true : false;
				?> 
				<tr>
					<th width="200">
						<label>Facebook SDK</label>
					</th>
					<td>
						<p>Check if Facebook SDK is auto-loaded by another plugin.</p>
						<?php 
							if( $test )
							{
								?>
									<hr />
									<p>
										<b>Error:</b> Facebook SDK is in use. This will prevent Facebook from working. Please, inform the developer of that plugin not to auto-include the file below and to use Facebook SDK only when required.
									</p>
<pre style="background-color: #FFFFE0;border:1px solid #E6DB55; border-radius: 3px;padding: 10px;margin:2px;">
<?php
	$reflector = new ReflectionClass( 'BaseFacebook' );
	echo $reflector->getFileName();
?>
</pre>
								<?php
							}
						?>
					</td>
					<td>
						<?php 
							if( $test )
							{
								echo "<b style='color:red;'>FAIL!</b>";
							}
							else
							{
								echo "<b style='color:green;'>OK!</b>";
							}
						?>
					</td>
				</tr> 

				<?php
					$test = class_exists( 'LightOpenID', false ) ? true : false;
				?> 				
				<tr>
					<th width="200">
						<label>Class LightOpenID</label>
					</th>
					<td>
						<p>Check if the LightOpenID Class is auto-loaded by another plugin.</p>
						<?php 
							if( $test )
							{
								?>
									<hr />
									<p>
										<b>Error:</b> Class LightOpenID is in use. This will prevent Yahoo, Steam, and few other providers from working. Please, inform the developer of that plugin not to auto-include the file below and to use LightOpenID only when required.
									</p>
<pre style="background-color: #FFFFE0;border:1px solid #E6DB55; border-radius: 3px;padding: 10px;margin:2px;">
<?php
	$reflector = new ReflectionClass( 'LightOpenID' );
	echo $reflector->getFileName();
?>
</pre>
								<?php
							}
						?>
					</td>
					<td>
						<?php 
							if( $test )
							{
								echo "<b style='color:red;'>FAIL!</b>";
							}
							else
							{
								echo "<b style='color:green;'>OK!</b>";
							}
						?>
					</td>
				</tr>

				<?php
					$itsec_tweaks = get_option( 'itsec_tweaks' );
					
					$test = $itsec_tweaks && $itsec_tweaks['long_url_strings'] ? true : false;
				?> 				
				<tr>
					<th width="200">
						<label>iThemes Security</label>
					</th>
					<td>
						<p>Check if 'Prevent long URL strings' option is enabled.</p>
						<?php 
							if( $test )
							{
								?>
									<hr />
									<p>
										<b>Error:</b> 'Prevent long URL strings' option is in enabled. This will prevent Facebook and few other providers from working.
									</p>
								<?php
							}
						?>
						
					</td>
					<td>
						<?php 
							if( $test )
							{
								echo "<b style='color:red;'>FAIL!</b>";
							}
							else
							{
								echo "<b style='color:green;'>OK!</b>";
							}
						?>
					</td>
				</tr> 
			</table> 

			<br />
			<hr />

			<a class="button-secondary" href="options-general.php?page=wordpress-social-login&wslp=tools">&larr; <?php _wsl_e("Back to Tools", 'wordpress-social-login') ?></a>
		</div>
	</div>
</div>
<?php
}

add_action( 'wsl_component_tools_do_diagnostics', 'wsl_component_tools_do_diagnostics' );

// --------------------------------------------------------------------	

function wsl_component_tools_do_sysinfo()
{
	global $wpdb;
	global $WORDPRESS_SOCIAL_LOGIN_VERSION;
	global $WORDPRESS_SOCIAL_LOGIN_COMPONENTS;
	global $WORDPRESS_SOCIAL_LOGIN_ADMIN_TABS;
	global $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG;
?>
<div class="metabox-holder columns-2" id="post-body">
	<div class="stuffbox">
		<h3>
			<label><?php _wsl_e("System information", 'wordpress-social-login') ?></label>
		</h3>
		<div class="inside"> 
			<ul style="padding-left:15px;">
				<li>Please include this information when posting support requests. It will help me immensely to better understand any issues.</li>
				<li>These information should be communicated to the plugin developer <b>PRIVATELY VIA EMAIL</b> : Miled &lt;<a href="mailto:hybridauth@gmail.com">hybridauth@gmail.com</a>&gt;</li>
				<li>Make sure to check out <b>WSL</b> <a href="http://miled.github.io/wordpress-social-login/faq.html" target="_blank"><b>frequently asked questions</b></a>.</li> 
			</ul>
<textarea onclick="this.focus(); this.select()" style="height: 500px;overflow: auto;white-space: pre;width: 100%;font-family: Menlo,Monaco,monospace;">
# GENERAL

SITE_URL:                 <?php echo site_url() . "\n"; ?>
PLUGIN_URL:               <?php echo plugins_url() . "\n"; ?>

# WORDPRESS SOCIAL LOGIN

WSL VERSION:              <?php echo $WORDPRESS_SOCIAL_LOGIN_VERSION . "\n"; ?>
WSL PROFILES TABLE:       <?php echo $wpdb->get_var( "SHOW TABLES LIKE '{$wpdb->prefix}wslusersprofiles'" ) . ' (' . $wpdb->get_var( "SELECT COUNT( * ) FROM {$wpdb->prefix}wslusersprofiles" ) . ")\n"; ?>
WSL CONTACTS TABLE:       <?php echo $wpdb->get_var( "SHOW TABLES LIKE '{$wpdb->prefix}wsluserscontacts'" ) . ' (' . $wpdb->get_var( "SELECT COUNT( * ) FROM {$wpdb->prefix}wsluserscontacts" ) . ")\n"; ?>
WSL COMPONENTS:           <?php foreach( $WORDPRESS_SOCIAL_LOGIN_COMPONENTS as $name => $settings ){ if( $settings["enabled"] ){ echo strtoupper( $name ) . ' '; } } echo "\n"; ?>
WSL TABS:                 <?php foreach( $WORDPRESS_SOCIAL_LOGIN_ADMIN_TABS as $name => $settings ){ if( $settings["enabled"] && $settings["visible"] ){ echo strtoupper( $name ) . ' '; } } echo "\n"; ?>
WSL NETWORKS:             <?php foreach( $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG as $provider ){ if( get_option( 'wsl_settings_' . $provider['provider_id'] . '_enabled' ) ){ echo strtoupper( $provider['provider_id'] ) . ' '; } } echo "\n"; ?>
WSL ABS URL:              <?php echo WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . "\n"; ?>
WSL ENDPOINT:             <?php echo WORDPRESS_SOCIAL_LOGIN_HYBRIDAUTH_ENDPOINT_URL . "\n"; ?>

# WORDPRESS

WORDPRESS VERSION:        <?php echo get_bloginfo( 'version' ) . "\n"; ?>
WORDPRESS MULTI-SITE:     <?php echo is_multisite() ? 'Yes' . "\n" : 'No' . "\n" ?>

# SOFTWARE

USER AGENT:               <?php echo esc_html( $_SERVER['HTTP_USER_AGENT'] ) . "\n"; ?>
SERVER INFO:              <?php echo esc_html( $_SERVER['SERVER_SOFTWARE'] ) . "\n"; ?>
SERVER TIME:              <?php echo date( DATE_RFC2822 ) . ' / ' . time() . "\n"; ?>
PHP VERSION:              <?php echo PHP_VERSION . "\n"; ?>
MYSQL VERSION:            <?php echo $wpdb->db_version() . "\n"; ?>

# SESSION and COOKIEIS

SESSION:                  <?php echo isset( $_SESSION ) ? 'Enabled' : 'Disabled'; echo "\n"; ?>
SESSION:NAME:             <?php echo esc_html( ini_get( 'session.name' ) ); echo "\n"; ?>
SESSION:WSL               <?php echo $_SESSION["wsl::plugin"]; echo "\n"; ?>

COOKIE PATH:              <?php echo esc_html( ini_get( 'session.cookie_path' ) ); echo "\n"; ?>
SAVE PATH:                <?php echo esc_html( ini_get( 'session.save_path' ) ); echo "\n"; ?>
USE COOKIES:              <?php echo ini_get( 'session.use_cookies' ) ? 'On' : 'Off'; echo "\n"; ?>
USE ONLY COOKIES:         <?php echo ini_get( 'session.use_only_cookies' ) ? 'On' : 'Off'; echo "\n"; ?>

# REQUIRED PHP EXTENSIONS

PHP/CURL:                 <?php echo function_exists( 'curl_init'   ) ? "Supported" : "Not supported"; echo "\n"; ?>
<?php if( function_exists( 'curl_init' ) ): ?>
PHP/CURL/VER:             <?php $v = curl_version(); echo $v['version']; echo "\n"; ?>
PHP/CURL/SSL:             <?php $v = curl_version(); echo $v['ssl_version']; echo "\n"; ?><?php endif; ?>
PHP/FSOCKOPEN:            <?php echo function_exists( 'fsockopen'   ) ? "Supported" : "Not supported"; echo "\n"; ?>
PHP/JSON:                 <?php echo function_exists( 'json_decode' ) ? "Supported" : "Not supported"; echo "\n"; ?>

# HTTP

HTTP_HOST:                <?php echo $_SERVER['HTTP_HOST'] . "\n"; ?>
SERVER_PORT:              <?php echo isset( $_SERVER['SERVER_PORT'] ) ? 'On (' . $_SERVER['SERVER_PORT'] . ')' : 'N/A'; echo "\n"; ?>
HTTP_X_FORWARDED_PROTO:   <?php echo isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) ? 'On (' . $_SERVER['HTTP_X_FORWARDED_PROTO'] . ')' : 'N/A'; echo "\n"; ?>

# ACTIVE PLUGINS

<?php  
if( function_exists("get_plugins") ):
	$plugins = get_plugins();
	foreach ( $plugins as $plugin_path => $plugin ): 
		echo str_pad( $plugin['Version'], 10, " ", STR_PAD_LEFT ); ?>. <?php echo $plugin['Name']."\n"; 
	endforeach;
else:
	$active_plugins = get_option( 'active_plugins', array() );
	foreach ( $active_plugins as $plugin ): 
		echo $plugin ."\n"; 
	endforeach;
endif; ?>

# CURRENT THEME

<?php
if ( get_bloginfo( 'version' ) < '3.4' ) {
	$theme_data = get_theme_data( get_stylesheet_directory() . '/style.css' );
	echo str_pad( $theme_data['Version'], 10, " ", STR_PAD_LEFT ) . '. ' . $theme_data['Name'];
} else {
	$theme_data = wp_get_theme();
	echo str_pad( $theme_data->Version, 10, " ", STR_PAD_LEFT ) . '. ' . $theme_data->Name;
}
?>


# EOF</textarea> 

			<br />
			<br />
			<a class="button-secondary" href="options-general.php?page=wordpress-social-login&wslp=tools">&larr; <?php _wsl_e("Back to Tools", 'wordpress-social-login') ?></a>
		</div>
	</div>
</div>
<?php
}

add_action( 'wsl_component_tools_do_sysinfo', 'wsl_component_tools_do_sysinfo' );

// --------------------------------------------------------------------	

function wsl_component_tools_do_repair()
{
	global $wpdb;

	wsl_database_migration_process();
	
	// update_option( 'wsl_settings_development_mode_enabled', 1 );
?>
<div class="metabox-holder columns-2" id="post-body">
	<div class="stuffbox">
		<h3>
			<label><?php _wsl_e("Repair Wordpress Social Login tables", 'wordpress-social-login') ?></label>
		</h3>
		<div class="inside"> 
			<p>
				<?php _wsl_e("All Wordpress Social Login tables and fields <em>should</em> be now restored", 'wordpress-social-login') ?>.
			</p>
			<p>
				<?php _wsl_e("If this still didn't work, please report this as a issue", 'wordpress-social-login') ?>.
			</p>
			<hr />
			<a class="button-secondary" href="options-general.php?page=wordpress-social-login&wslp=tools">&larr; <?php _wsl_e("Back to Tools", 'wordpress-social-login') ?></a>
		</div>
	</div>
</div>
<?php 
	# ain't this clever :p
	$db_check_profiles = $wpdb->get_var( "SHOW TABLES LIKE '{$wpdb->prefix}wslusersprofiles'" ) === $wpdb->prefix . 'wslusersprofiles' ? 1 : 0;
	$db_check_contacts = $wpdb->get_var( "SHOW TABLES LIKE '{$wpdb->prefix}wsluserscontacts'" ) === $wpdb->prefix . 'wsluserscontacts' ? 1 : 0;

	if( $db_check_profiles && $db_check_contacts )
	{
		?>
			<style>.wsl-error-db-tables{display:none;}</style>
		<?php
	}

}

add_action( 'wsl_component_tools_do_repair', 'wsl_component_tools_do_repair' );

// --------------------------------------------------------------------	

function wsl_component_tools_do_uninstall()
{
	global $wpdb;
	global $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG;

// 1. Delete wslusersprofiles, wsluserscontacts and wslwatchdog

	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}wslusersprofiles" ); 
	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}wsluserscontacts" );
	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}wslwatchdog" );

// 2. Delete user metadata from usermeta

	$wpdb->query( "DELETE FROM $wpdb->usermeta WHERE meta_key = 'wsl_current_provider'"   );
	$wpdb->query( "DELETE FROM $wpdb->usermeta WHERE meta_key = 'wsl_current_user_image'" );

// 3. Delete registered options

	delete_option('wsl_database_migration_version' ); 

	delete_option('wsl_settings_development_mode_enabled' ); 
	delete_option('wsl_settings_debug_mode_enabled' ); 
	delete_option('wsl_settings_welcome_panel_enabled' );

	delete_option('wsl_components_core_enabled' );
	delete_option('wsl_components_networks_enabled' );
	delete_option('wsl_components_login-widget_enabled' );
	delete_option('wsl_components_bouncer_enabled' );
	delete_option('wsl_components_diagnostics_enabled' );
	delete_option('wsl_components_users_enabled' );
	delete_option('wsl_components_contacts_enabled' );
	delete_option('wsl_components_buddypress_enabled' );

	delete_option('wsl_settings_redirect_url' );
	delete_option('wsl_settings_force_redirect_url' );
	delete_option('wsl_settings_connect_with_label' );
	delete_option('wsl_settings_use_popup' );
	delete_option('wsl_settings_widget_display' );
	delete_option('wsl_settings_authentication_widget_css' );
	delete_option('wsl_settings_social_icon_set' );
	delete_option('wsl_settings_users_avatars' );
	delete_option('wsl_settings_users_notification' );

	delete_option('wsl_settings_bouncer_registration_enabled' );
	delete_option('wsl_settings_bouncer_authentication_enabled' );
	delete_option('wsl_settings_bouncer_linking_accounts_enabled' );
	delete_option('wsl_settings_bouncer_profile_completion_require_email' );
	delete_option('wsl_settings_bouncer_profile_completion_change_email' );
	delete_option('wsl_settings_bouncer_profile_completion_change_username' );  
	delete_option('wsl_settings_bouncer_new_users_moderation_level' );
	delete_option('wsl_settings_bouncer_new_users_membership_default_role' );
	delete_option('wsl_settings_bouncer_new_users_restrict_domain_enabled' );
	delete_option('wsl_settings_bouncer_new_users_restrict_domain_text_bounce' );
	delete_option('wsl_settings_bouncer_new_users_restrict_email_enabled' );
	delete_option('wsl_settings_bouncer_new_users_restrict_email_text_bounce' );
	delete_option('wsl_settings_bouncer_new_users_restrict_profile_enabled' );
	delete_option('wsl_settings_bouncer_new_users_restrict_profile_text_bounce' );
	delete_option('wsl_settings_bouncer_new_users_restrict_domain_list' );
	delete_option('wsl_settings_bouncer_new_users_restrict_email_list' );
	delete_option('wsl_settings_bouncer_new_users_restrict_profile_list' );

	delete_option('wsl_settings_contacts_import_facebook' );
	delete_option('wsl_settings_contacts_import_google' );
	delete_option('wsl_settings_contacts_import_twitter' );
	delete_option('wsl_settings_contacts_import_linkedin' );
	delete_option('wsl_settings_contacts_import_live' );
	delete_option('wsl_settings_contacts_import_vkontakte' );

	foreach( $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG as $provider )
	{
		delete_option( 'wsl_settings_' . $provider['provider_id'] . '_enabled' );
		delete_option( 'wsl_settings_' . $provider['provider_id'] . '_app_id' );
		delete_option( 'wsl_settings_' . $provider['provider_id'] . '_app_key' );
		delete_option( 'wsl_settings_' . $provider['provider_id'] . '_app_secret' );
	}
?>
<div class="metabox-holder columns-2" id="post-body">
	<div class="stuffbox">
		<h3>
			<label><?php _wsl_e("Uninstall", 'wordpress-social-login') ?></label>
		</h3>
		<div class="inside"> 
			<p>
				<?php _wsl_e("All Wordpress Social Login tables and stored options are permanently deleted from your WordPress database", 'wordpress-social-login') ?>.
			</p>
			<p>
				<?php _wsl_e("To delete Wordpress Social Login files from your WordPress website, you may deactivate and uninstall it through the 'Plugins' menu in WordPress", 'wordpress-social-login') ?>.
			</p>
			<p>
				<?php _wsl_e("Thank you for using Wordpress Social Login", 'wordpress-social-login') ?>.
			</p>
			<p>
				<?php _wsl_e("Good bye", 'wordpress-social-login') ?>.
			</p>
			<hr />
			<a class="button-secondary" href="options-general.php?page=wordpress-social-login&wslp=tools">&larr; <?php _wsl_e("Back to Tools", 'wordpress-social-login') ?></a>
		</div>
	</div>
</div>
<?php
}

add_action( 'wsl_component_tools_do_uninstall', 'wsl_component_tools_do_uninstall' );

// --------------------------------------------------------------------	
