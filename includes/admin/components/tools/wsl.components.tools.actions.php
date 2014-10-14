<?php
/*!
* WordPress Social Login
*
* http://miled.github.io/wordpress-social-login/index.html | http://github.com/hybridauth/WordPress-Social-Login
*    (c) 2011-2014 Mohamed Mrassi and contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 

// --------------------------------------------------------------------	

function wsl_component_tools_do_requirements()
{
?>
<div class="metabox-holder columns-2" id="post-body">
	<div class="stuffbox">
		<h3>
			<label><?php _wsl_e("WSL requirements test", 'wordpress-social-login') ?></label>
		</h3>
		<div class="inside"> 
			<ul style="padding-left:15px;">
				<li>Please include your <a href="options-general.php?page=wordpress-social-login&wslp=tools&do=sysinfo" target="_blank"><b>Website Information</b></a> when posting support requests.</li>
				<li>Make sure to check out <b>WSL</b> <a href="http://miled.github.io/wordpress-social-login/faq.html" target="_blank"><b>frequently asked questions</b></a>.</li> 
			</ul>

			<hr />

			<h4>1. PHP Version</h4> 
			<p>
			<?php 
				if ( version_compare( PHP_VERSION, '5.2.0', '>=' ) ){
					echo "<b style='color:green;'>OK!</b><br />PHP >= 5.2.0 installed.";
				}
				else{ 
					echo "<b style='color:red;'>FAIL!</b><br />PHP >= 5.2.0 not installed.";
				}
			?>
				</p>

				<h4>2. PHP Sessions</h4> 
				<p>
			<?php
				if ( isset( $_SESSION["wsl::plugin"] ) ){
					echo "<b style='color:green;'>OK!</b><br />PHP Sessions are working correctly for {$_SESSION["wsl::plugin"]} to work."; 
				}
				else{
					?>
					<b style='color:red;'>FAIL!</b><br />PHP Sessions are not working correctly or this page has been accessed directly.
			 
					
					<div style="background-color: #FFFFE0;border:1px solid #E6DB55; border-radius: 3px;padding:5px;font-size: 12px;">  
						Most likely an issue with PHP SESSION. The plugin has been made to work with PHP's default SESSION handling (sometimes this occur when the php session is disabled, renamed or when having permissions issues).
						<br /><br />
						If you are using a reverse proxy like Varnish it is possible that WordPress's default user cookies are being stripped. If this is the case, please review your VCL file. You may need to configure this file to allow the needed cookies.
						<br /><br />
						This problem has also been encountered with WP Engine. 
						
						<hr />
					
						If you see an error <strong>"Warning: session_start..."</strong> on the top of this page or in the Error log file, then 

						there is a problem with your PHP server that will prevent this plugin from working with PHP sessions. Sometimes PHP session do not work because of a file permissions problem. The solution is to make a trouble ticket with your web host.
						
						<br />
						<br />
						Alternatively, you can create the sessions folder in your root directory, then add <strong>session_save_path('/path/to/writable/folder')</strong> at the top of the following files:
						
						<br />
						- wp-config.php<br />
						- wp-content/plugins/wordpress-social-login/wp-social-login.php<br />
						- wp-content/plugins/wordpress-social-login/authenticate.php<br />
						- wp-content/plugins/wordpress-social-login/hybridauth/index.php<br />
					</div>
					<?php
				}
			?>
				</p>

				<h4>3. cURL Extension</h4> 
				<p>
			<?php 
				if ( function_exists('curl_init') ) {
					echo "<b style='color:green;'>OK!</b><br />PHP cURL extension installed. [http://www.php.net/manual/en/intro.curl.php]";
			?>
				</p>

				<h4>4. cURL / SSL </h4> 
				<p>
			<?php 
					$curl_version = curl_version();

					if ( $curl_version['features'] & CURL_VERSION_SSL ) {
						echo "<b style='color:green;'>OK!</b><br />PHP cURL/SSL Supported. [http://www.php.net/manual/en/intro.curl.php]";
					}
					else{ 
						echo "<b style='color:red;'>FAIL!</b><br />PHP cURL/SSL Not supported. [http://www.php.net/manual/en/intro.curl.php]";
					}
				}
				else{ 
					echo "<b style='color:red;'>FAIL!</b><br />PHP cURL extension not installed. [http://www.php.net/manual/en/intro.curl.php]";
				}
			?>
			</p>

			<h4>5. JSON Extension</h4> 
			<p>
			<?php 
				if ( function_exists('json_decode') ) {
					echo "<b style='color:green;'>OK!</b><br />PHP JSON extension installed. [http://php.net/manual/en/book.json.php]";
				}
				else{ 
					echo "<b style='color:red;'>FAIL!</b><br />PHP JSON extension is disabled. [http://php.net/manual/en/book.json.php]";
				}
			?>
				</p>

				<h4>6. PHP Register Globals</h4> 
				<p>
			<?php 
				if( ini_get('register_globals') ) { 
					?>
						<b style='color:red;'>FAIL!</b><br />PHP Register Globals are On!
					
						<div style="background-color: #FFFFE0;border:1px solid #E6DB55; border-radius: 3px;padding:5px;font-size: 12px;">  
							<b>What do I do if <b>Register Globals</b> are <b>On</b>?</b>
							<p> 
								If <strong>"PHP Register Globals"</strong> are <b>On</b>, then there is a problem with your PHP server that will prevent this plugin from working properly and will result on an enfinite loop on WSL authentication page. Also, <a href="http://php.net/manual/en/security.globals.php" target="_blank"><b>Register Globals</b> has been DEPRECATED as of PHP 5.3.0 and REMOVED as of PHP 5.4.0.</a>
								<br />
								<br />
								The solution is to make a trouble ticket with your web host to disable it,<br />
								Or, if you have a dedicated server and you know what are you doing then edit php.ini file and turn it Off :
								<br />
								<br />
								<span style="border:1px solid #E6DB55;padding:5px;"> register_globals = Off</span>
							</p>
						</div>
					<?php
				}
				else{ 
					echo "<b style='color:green;'>OK!</b><br />REGISTER_GLOBALS = OFF. [http://php.net/manual/en/security.globals.php]";
				} 
			?>
			</p>  

			<hr />

			<a class="button-secondary" href="options-general.php?page=wordpress-social-login&wslp=tools">&larr; <?php _wsl_e("Back to Tools", 'wordpress-social-login') ?></a>
		</div>
	</div>
</div>
<?php
}

add_action( 'wsl_component_tools_do_requirements', 'wsl_component_tools_do_requirements' );

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
WSL ABS PATH:             <?php echo WORDPRESS_SOCIAL_LOGIN_ABS_PATH . "\n"; ?>

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
	delete_option('wsl_settings_contacts_import_live' );
	delete_option('wsl_settings_contacts_import_linkedin' );

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
