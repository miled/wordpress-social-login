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
				<?php
					$test = version_compare( PHP_VERSION, '5.2.0', '>=' );
					// $test = 0;
				?>
				<tr>
					<th width="200">
						<label>PHP Version</label>
					</th>
					<td>
						<p>PHP >= 5.2.0 installed.</p>
						<?php
							if( ! $test )
							{ 
								?>
									<div class="fade error" style="margin: 20px  0;"> 
										<p><b>Error</b>: An old version of PHP is installed.</p>
										<p>The solution is to make a trouble ticket to your web host and request them to upgrade to newer version of PHP.</p>
									</div>
								<?php
							}
						?>
					</td>
					<td width="60">
						<?php 
							if( $test )
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

				<?php
					$test = isset( $_SESSION["wsl::plugin"] ) && $_SESSION["wsl::plugin"];
					// $test = 0;
				?>
				<tr>
					<th width="200">
						<label>PHP Sessions</label>
					</th>
					<td>
						<p>PHP/Session must be enabled and working.</p>
						<?php
							if( ! $test )
							{ 
								?>
								<div class="fade error" style="margin: 20px  0;"> 
									<p><b>Error</b>: PHP Sessions are not working as expected.</p>

									<p>
										This error may occur for many reasons:
									</p> 

									<p>
										1. PHP session are either disabled, renamed or there is files permissions issues.
									</p> 
									<p>
										2. When using a reverse proxy like Varnish or a caching engine that might strip cookies. On this case, WSL will requires these two urls to be white-listed:
									</p>
									<div style="background-color: #FFFFE0;border:1px solid #E6DB55; border-radius: 3px;padding: 10px;margin:2px;">
										<?php
											echo '<a href="' . site_url( 'wp-login.php', 'login_post' ) . '" target="_blank">' . site_url( 'wp-login.php', 'login_post' ) . '</a>';
											echo '<br />';
											echo '<a href="' . WORDPRESS_SOCIAL_LOGIN_HYBRIDAUTH_ENDPOINT_URL . '" target="_blank">' . WORDPRESS_SOCIAL_LOGIN_HYBRIDAUTH_ENDPOINT_URL . '</a>';
										?>
									</div>
								</div>
								<?php
							}
							else
							{
						?> 
							<hr />
							<h4>Notes:</h4>
							<p>
								1. If you're hosting your website on <b>WP Engine</b>, refer this topic: <a href="https://wordpress.org/support/topic/500-internal-server-error-when-redirecting" target="_blank">https://wordpress.org/support/topic/500-internal-server-error-when-redirecting</a>
							</p> 
							<p>2. In case you're using a reverse proxy like Varnish or a caching engine that might strip cookies, WSL will requires these two urls to be white-listed:</p>
							<div style="background-color: #FFFFE0;border:1px solid #E6DB55; border-radius: 3px;padding: 10px;margin:2px;">
								<?php
									echo '<a href="' . site_url( 'wp-login.php', 'login_post' ) . '" target="_blank">' . site_url( 'wp-login.php', 'login_post' ) . '</a>';
									echo '<br />';
									echo '<a href="' . WORDPRESS_SOCIAL_LOGIN_HYBRIDAUTH_ENDPOINT_URL . '" target="_blank">' . WORDPRESS_SOCIAL_LOGIN_HYBRIDAUTH_ENDPOINT_URL . '</a>';
								?>
							</div>
						<?php
							}
						?>
					</td>
					<td>
						<?php 
							if( $test )
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

				<?php
					$test = false;

					if ( function_exists('curl_init') )
					{
						$curl_version = curl_version();

						if ( $curl_version['features'] & CURL_VERSION_SSL )
						{
							$test = true;
						}
					}
					// $test = 0;
				?>
				<tr>
					<th width="200">
						<label>PHP CURL/SSL Extension</label>
					</th>
					<td>
						<p>PHP CURL extension with SSL must be enabled and working.</p>
						<?php 
							if( ! $test )
							{
								?>
									<div class="fade error" style="margin: 20px  0;"> 
										<p><b>Error</b>: CURL library is either not installed or SSL is not enabled.</p>
										<p>The solution is to make a trouble ticket to your web host and request them to enable the PHP CURL.</p>
									</div>
								<?php
							}
						?>
					</td>
					<td>
						<?php 
							if( $test )
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

				<?php
					$test = ! ini_get('register_globals') ? true : false;
					// $test = 0;
				?>
				<tr>
					<th width="200">
						<label>PHP Register Globals</label>
					</th>
					<td>
						<p>PHP Register Globals must be OFF.</p>
					<?php 
						if(  ! $test )
						{ 
							?>
								<div class="fade error" style="margin: 20px  0;"> 
									<p><b>Error</b>: REGISTER_GLOBALS are On.</p>
									<p>This will prevent WSL from working properly and will result on an infinite loop on the authentication page.</p>
									<p>The solution is to make a trouble ticket with your web host to disable it, Or, if you have a dedicated server and you know what are you doing then edit php.ini file and turn it Off.</p>
								</div>
							<?php
						}
					?>
					</td>
					<td>
						<?php 
							if( $test )
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

						<div id="end_points_warn" class="fade error" style="margin: 20px  0;display:none;">
							<p><b>Error</b>: Your web server returned <span id="end_points_error"></span> when checking WSL end-points.</p>

							<p>This issue usually happen when :</p>
							<p>1. Your web host uses <code>mod_security</code> to block requests containing URLs (eg. hosts like HostGator, GoDaddy and The Planet). On this case, you should contact your provider to have WSL end-points urls white-listed.</p>
							<p>2. There is a <code>.htaccess</code> file that prevent direct access to the WordPress plugins directory.</p>

							<p>In any case, WSL requires this url to be white-listed:</p>

							<div style="background-color: #FFFFE0;border:1px solid #E6DB55; border-radius: 3px;padding: 10px;margin:2px;">
								<?php 
									echo '<a href="' . WORDPRESS_SOCIAL_LOGIN_HYBRIDAUTH_ENDPOINT_URL . '" target="_blank">' . WORDPRESS_SOCIAL_LOGIN_HYBRIDAUTH_ENDPOINT_URL . '</a>';
								?>
							</div> 
						</div>

						<div id="end_points_note" style="margin: 20px  0;">
							<hr />

							<p><b>Note</b>: In case you're using <code>mod_security</code> to block requests containing URLs or a <code>.htaccess</code> file to protect the WordPress plugins directory, WSL will requires this url to be white-listed:</p>

							<div style="background-color: #FFFFE0;border:1px solid #E6DB55; border-radius: 3px;padding: 10px;margin:2px;">
								<?php
									echo '<a href="' . WORDPRESS_SOCIAL_LOGIN_HYBRIDAUTH_ENDPOINT_URL . '" target="_blank">' . WORDPRESS_SOCIAL_LOGIN_HYBRIDAUTH_ENDPOINT_URL . '</a>';
								?>
							</div> 
						</div>

						<p>You may double-check this test manually by clicking this <a href="<?php echo WORDPRESS_SOCIAL_LOGIN_HYBRIDAUTH_ENDPOINT_URL; ?>?test=http://example.com" target="_blank">direct link</a>.</p>
					</td>
					<td width="60">
						<span id="end_points">testing..</span>
						<script>
							jQuery(document).ready(function($) {
								jQuery.ajax({
									url: '<?php echo WORDPRESS_SOCIAL_LOGIN_HYBRIDAUTH_ENDPOINT_URL; ?>',
									data: 'url=http://example.com',
									success: function () {
										jQuery('#end_points').html( '<b style="color:green;">OK!</b>' );
									},
									error: function (xhr, ajaxOptions, thrownError) {
										// console.log( xhr );
										jQuery('#end_points_error').html( '"<b style="color:red;">' + xhr.status + ' ' + xhr.statusText + '</b>"' );
										jQuery('#end_points').html( '<b style="color:red;">FAIL!</b>' );
										jQuery('#end_points_warn').show();
										jQuery('#end_points_note').hide();
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

					$test = $db_check_profiles && $db_check_contacts ? true : false;
				?>
				<tr>
					<th width="200">
						<label>WSL database tables</label>
					</th>
					<td>
						<p>Check if WSL database tables (<code>wslusersprofiles</code> and <code>wsluserscontacts</code>) exist.</p>
						<?php 
							if( ! $test )
							{
								?>
									<div class="fade error" style="margin: 20px  0;"> 
										<p><b>Error:</b> One or more of WordPress Social Login tables do not exist.</p>
										<p>This may prevent this plugin form working correctly. To fix this, navigate to <b>Tools</b> tab then <b><a href="options-general.php?page=wordpress-social-login&wslp=tools#repair-tables">Repair WSL tables</a></b>.</p>
									</div>
								<?php
							}
						?>
					</td>
					<td width="60">
						<?php
							if( $test )
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
			
				<?php
					$test = class_exists( 'Hybrid_Auth', false ) ? false : true;
				?> 				
				<tr>
					<th width="200">
						<label>Hybridauth Library</label>
					</th>
					<td>
						<p>Check if the Hybridauth Library is auto-loaded by another plugin.</p>
						<?php 
							if( ! $test )
							{
								?>
									<div class="fade error" style="margin: 20px  0;"> 
										<p>Hybridauth Library is auto-included by another plugin.</p>
										<p>This is not critical but it may prevent WSL from working.</p>
										<p>Please, inform the developer of that plugin not to auto-include the file below and to use Hybridauth Library only when required.</p> 
										<div style="background-color: #FFFFE0;border:1px solid #E6DB55; border-radius: 3px;padding: 10px;margin:2px;">
										<?php try{$reflector = new ReflectionClass( 'Hybrid_Auth' ); echo $reflector->getFileName(); } catch( Exception $e ){} ?>
										</div>
									</div>
								<?php
							}
						?>
					</td>
					<td>
						<?php 
							if( $test )
							{
								echo "<b style='color:green;'>OK!</b>";
							}
							else
							{
								echo "<b style='color:orange;'>PASS</b>";
							}
						?>
					</td>
				</tr>
	
				<?php
					$test = class_exists( 'OAuthConsumer', false ) ? false : true;
				?> 
				<tr>
					<th width="200">
						<label>OAUTH Library</label>
					</th>
					<td>
						<p>Check if OAUTH Library is auto-loaded by another plugin.</p>
						<?php 
							if( ! $test )
							{
								?>
									<div class="fade error" style="margin: 20px  0;"> 
										<p>OAUTH Library is auto-included by another plugin.</p>
										<p>This is not critical but it may prevent Twitter, LinkedIn and few other providers from working.</p>
										<p>Please, inform the developer of that plugin not to auto-include the file below and to use OAUTH Library only when required.</p> 
										<div style="background-color: #FFFFE0;border:1px solid #E6DB55; border-radius: 3px;padding: 10px;margin:2px;">
										<?php try{$reflector = new ReflectionClass( 'OAuthConsumer' ); echo $reflector->getFileName(); } catch( Exception $e ){} ?>
										</div>
									</div>
								<?php
							}
						?>
					</td>
					<td>
						<?php 
							if( $test )
							{
								echo "<b style='color:green;'>OK!</b>";
							}
							else
							{
								echo "<b style='color:orange;'>PASS</b>";
							}
						?>
					</td>
				</tr> 

				<?php
					$test = class_exists( 'BaseFacebook', false ) ? false : true;
				?> 
				<tr>
					<th width="200">
						<label>Facebook SDK</label>
					</th>
					<td>
						<p>Check if Facebook SDK is auto-loaded by another plugin.</p>
						<?php 
							if( ! $test )
							{
								?>
									<div class="fade error" style="margin: 20px  0;"> 
										<p><b>Error:</b> Facebook SDK is auto-included by another plugin.</p>
										<p>This will prevent Facebook from working.</p>
										<p>Please, inform the developer of that plugin not to auto-include the file below and to use Facebook SDK only when required.</p> 
										<div style="background-color: #FFFFE0;border:1px solid #E6DB55; border-radius: 3px;padding: 10px;margin:2px;">
										<?php try{$reflector = new ReflectionClass( 'BaseFacebook' ); echo $reflector->getFileName(); } catch( Exception $e ){} ?>
										</div>
									</div>
								<?php
							}
						?>
					</td>
					<td>
						<?php 
							if( $test )
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

				<?php
					$test = class_exists( 'LightOpenID', false ) ? false : true;
				?> 				
				<tr>
					<th width="200">
						<label>Class LightOpenID</label>
					</th>
					<td>
						<p>Check if the LightOpenID Class is auto-loaded by another plugin.</p>
						<?php 
							if( ! $test )
							{
								?>
									<div class="fade error" style="margin: 20px  0;"> 
										<p>Class LightOpenID is auto-included by another plugin.</p>
										<p>This is not critical but it may prevent Yahoo, Steam, and few other providers from working.</p>
										<p>Please, inform the developer of that plugin not to auto-include the file below and to use Class LightOpenID only when required.</p> 
										<div style="background-color: #FFFFE0;border:1px solid #E6DB55; border-radius: 3px;padding: 10px;margin:2px;">
										<?php try{$reflector = new ReflectionClass( 'LightOpenID' ); echo $reflector->getFileName(); } catch( Exception $e ){} ?>
										</div>
									</div>
								<?php
							}
						?>
					</td>
					<td>
						<?php 
							if( $test )
							{
								echo "<b style='color:green;'>OK!</b>";
							}
							else
							{
								echo "<b style='color:orange;'>PASS</b>";
							}
						?>
					</td>
				</tr>

				<?php
					$curl = '';
					$test = true;

					if( ! class_exists( 'Hybrid_Auth', false ) )
					{
						include_once WORDPRESS_SOCIAL_LOGIN_ABS_PATH . "/hybridauth/Hybrid/Auth.php";

						$curl = Hybrid_Auth::getCurrentUrl();
					}

					$headers = array( 'HTTP_VIA', 'HTTP_X_FORWARDED_FOR', 'HTTP_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_FORWARDED', 'HTTP_CLIENT_IP', 'HTTP_FORWARDED_FOR_IP', 'VIA', 'X_FORWARDED_FOR', 'FORWARDED_FOR', 'X_FORWARDED', 'FORWARDED', 'CLIENT_IP', 'FORWARDED_FOR_IP', 'HTTP_PROXY_CONNECTION' );
					foreach( $headers as $v )
					{
						if( isset( $_SERVER[ $v ] ) ) $test = true;
					}
				?>
				<tr>
					<th width="200">
						<label>HTTP Proxies</label>
					</th>
					<td>
						<p>Check for proxified urls.</p>
						<?php 
							if( ! $test )
							{
								?>
									<div class="fade error" style="margin: 20px  0;"> 
										<p>WSL has detected that you are using a proxy in your website. The URL shown below should match the URL on your browser address bar.</p>
										<div style="background-color: #FFFFE0;border:1px solid #E6DB55; border-radius: 3px;padding: 10px;margin:2px;">
											<?php
												echo $curl;
											?>
										</div>
									</div>
								<?php
							}
						?>
					</td>
					<td>
						<?php 
							if( $test )
							{
								echo "<b style='color:green;'>OK!</b>";
							}
							else
							{
								echo "<b style='color:orange;'>PASS</b>";
							}
						?>
					</td>
				</tr> 

				<?php 
					$test = ! stristr( plugins_url(), site_url() ) ? false : true;
				?>
				<tr>
					<th width="200">
						<label>WordPress functions</label>
					</th>
					<td>
						<p>Check for WordPress directories functions.</p>
						<?php 
							if( ! $test )
							{
								?>
									<hr />
									<p><code>plugins_url()</code> is not returning an expected result : <?php echo plugins_url(); ?></p>
								<?php
							}
						?>
					</td>
					<td>
						<?php 
							if( $test )
							{
								echo "<b style='color:green;'>OK!</b>";
							}
							else
							{
								echo "<b style='color:orange;'>PASS</b>";
							}
						?>
					</td>
				</tr> 

				<?php
					$test = true;
					$used = array();

					$depreciated = array( 
					// auth
						'wsl_hook_process_login_alter_userdata', 
						'wsl_hook_process_login_before_insert_user', 
						'wsl_hook_process_login_after_create_wp_user', 
						'wsl_hook_process_login_before_set_auth_cookie', 
						'wsl_hook_process_login_before_redirect',

					// widget
						'wsl_render_login_form_start',
						'wsl_alter_hook_provider_icon_markup',
						'wsl_render_login_form_alter_provider_icon_markup',
						'wsl_render_login_form_end',
					);

					foreach( $depreciated as $v )
					{
						if( has_filter( $v ) || has_action( $v ) )
						{
							$test = false;
							$used[] = $v;
						}
					}
				?> 				
				<tr>
					<th width="200">
						<label>WSL depreciated hooks</label>
					</th>
					<td>
						<p>Check for depreciated WSL actions and filters in use.</p>
						<?php 
							if( ! $test )
							{
								?>
									<div class="fade error" style="margin: 20px  0;"> 
										<p>WSL has detected that you are using depreciated WSL: <code><?php echo implode( '</code>, <code>', $used ); ?></code></p>
										<p>Please update the WSL hooks you were using accordingly to the new developer API at <a href="http://miled.github.io/wordpress-social-login/documentation.html" target="_blank">http://miled.github.io/wordpress-social-login/documentation.html</a></p>
									</div>
								<?php
							}
						?>
						<p> Note: this test is not reliable 100% as we simply match the depreciated hooks against <code>has_filter</code> and <code>has_action</code>.</p>
					</td>
					<td>
						<?php 
							if( $test )
							{
								echo "<b style='color:green;'>OK!</b>";
							}
							else
							{
								echo "<b style='color:orange;'>PASS</b>";
							}
						?>
					</td>
				</tr> 

				<?php
					$itsec_tweaks = get_option( 'itsec_tweaks' );

					$test = $itsec_tweaks && $itsec_tweaks['long_url_strings'] ? false : true;
				?> 				
				<tr>
					<th width="200">
						<label>iThemes Security</label>
					</th>
					<td>
						<p>Check if 'Prevent long URL strings' option is enabled.</p>
						<?php 
							if( ! $test )
							{
								?>
									<div class="fade error" style="margin: 20px  0;"> 
										<p><b>Error:</b> 'Prevent long URL strings' option is in enabled.</p>
										<p>This may prevent Facebook and few other providers from working.</p>
									</div>
								<?php
							}
						?>
					</td>
					<td>
						<?php 
							if( $test )
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

				<?php
					/**
					* Check twitter timestamp
					*
					* Thanks much Joe for the cool idea
					* https://wordpress.org/plugins/wp-to-twitter/
					*/
					$test         = true;
					$error        = '';
					$hint         = '';
					$server_time  = date( DATE_COOKIE );
					$response     = wp_remote_get( "https://api.twitter.com/1.1/help/test.json", array( 'timeout' => 2, 'redirection' => 1 ) );

					if ( is_wp_error( $response ) )
					{
						$test = false;
						$error = __("There was an error querying Twitter's servers", 'wordpress-social-login');
					}
					else
					{
						if( time() < strtotime( $response['headers']['date'] )- 300 || time() > strtotime( $response['headers']['date'] ) + 300 )
						{
							$test  = false;
							$error = _wsl__("Your web server date is set incorrectly. This may prevent Twitter and LinkedIn and few other providers from working", 'wordpress-social-login');
							$hint = sprintf( _wsl__("Please check if your web server time is correct: <code>%s</code>", 'wordpress-social-login'), $server_time );
						}
					}
				?>
				<tr>
					<th width="200">
						<label>Server Timestamp</label>
					</th>
					<td>
						<p>Check if your web server clock is in sync.</p>
						<?php 
							if( ! $test )
							{
								?>
									<div class="fade error" style="margin: 20px  0;"> 
										<p><b>Error:</b> <?php echo $error; ?>.</p>
										<?php if( $hint ) echo '<p>' . $hint . '.</p>'; ?>
									</div>
								<?php
							}
						?>
					</td>
					<td>
						<?php 
							if( $test )
							{
								echo "<b style='color:green;'>OK!</b>";
							}
							else
							{
								echo "<b style='color:orange;'>PASS</b>";
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

	wsl_database_install();
	
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
	wsl_database_uninstall();
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
