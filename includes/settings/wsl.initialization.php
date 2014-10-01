<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*    (c) 2011-2014 Mohamed Mrassi and contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

/**
* Check WSL requirements and register WSL settings 
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------

/**
* Check WSL minimum requirements. Display fail page if they are not met.
*/
function wsl_check_requirements()
{
	if
	(
		   ! version_compare( PHP_VERSION, '5.2.0', '>=' )
		|| ! isset( $_SESSION["wsl::plugin"] )
		|| ! function_exists('curl_init')
		|| ! function_exists('json_decode')
		||   ini_get('register_globals')
	)
		return false;

	$curl_version = curl_version();

	if ( ! ( $curl_version['features'] & CURL_VERSION_SSL ) )
		return false;

	return true;
}

// --------------------------------------------------------------------

/** list of WSL components */
$WORDPRESS_SOCIAL_LOGIN_COMPONENTS = ARRAY(
	"core"           => array( "type" => "core"  , "label" => __("WSL Core"       , 'wordpress-social-login'), "description" => __("WordPress Social Login core.", 'wordpress-social-login') ),
	"networks"       => array( "type" => "core"  , "label" => __("Networks"       , 'wordpress-social-login'), "description" => __("Social networks setup.", 'wordpress-social-login') ),
	"login-widget"   => array( "type" => "core"  , "label" => __("Widget"         , 'wordpress-social-login'), "description" => __("Authentication widget customization.", 'wordpress-social-login') ),
	"bouncer"        => array( "type" => "core"  , "label" => __("Bouncer"        , 'wordpress-social-login'), "description" => __("WordPress Social Login advanced configuration.", 'wordpress-social-login') ),
	"diagnostics"    => array( "type" => "core"  , "label" => __("Diagnostics"    , 'wordpress-social-login'), "description" => __("WordPress Social Login diagnostics.", 'wordpress-social-login') ), 
	"basicinsights"  => array( "type" => "plugin", "label" => __("Basic Insights" , 'wordpress-social-login'), "description" => __("WordPress Social Login basic insights. When enabled, <b>Basic Insights</b> will be shown in the right sidebar of the <b>Networks</b> page.", 'wordpress-social-login') ), 
	"users"          => array( "type" => "plugin", "label" => __("Users"          , 'wordpress-social-login'), "description" => __("WordPress Social Login users manager.", 'wordpress-social-login') ),
	"contacts"       => array( "type" => "plugin", "label" => __("Contacts"       , 'wordpress-social-login'), "description" => __("WordPress Social Login users contacts manager", 'wordpress-social-login') ),
	"buddypress"     => array( "type" => "plugin", "label" => __("BuddyPress"     , 'wordpress-social-login'), "description" => __("Makes WordPress Social Login compatible with BuddyPress: Widget integration, Users avatars and xProfiles mapping.", 'wordpress-social-login') ),
);

/** list of WSL admin tabs */
$WORDPRESS_SOCIAL_LOGIN_ADMIN_TABS = ARRAY(  
	"networks"     => array( "label" => __("Networks"      , 'wordpress-social-login') , "enabled" => true ,  "visible" => true  , "component" => "networks"      , "default" => true ),
	"login-widget" => array( "label" => __("Widget"        , 'wordpress-social-login') , "enabled" => true ,  "visible" => true  , "component" => "login-widget" ), 
	"bouncer"      => array( "label" => __("Bouncer"       , 'wordpress-social-login') , "enabled" => true ,  "visible" => true  , "component" => "bouncer"      ), 

	"buddypress"   => array( "label" => __("BuddyPress"    , 'wordpress-social-login') , "enabled" => false,  "visible" => true  , "component" => "buddypress"   ),

	"users"        => array( "label" => __("Users"         , 'wordpress-social-login') , "enabled" => false,  "visible" => true  , "component" => "users"        ),
	"contacts"     => array( "label" => __("Contacts"      , 'wordpress-social-login') , "enabled" => false,  "visible" => true  , "component" => "contacts"     ),

	"diagnostics"  => array( "label" => __("Diagnostics"   , 'wordpress-social-login') , "enabled" => true ,  "visible" => false , "component" => "diagnostics"   , "pull-right" => true , "welcome-panel" => false ), 
	"help"         => array( "label" => __('?'             , 'wordpress-social-login') , "enabled" => true ,  "visible" => true  , "component" => "core"          , "pull-right" => true , "welcome-panel" => false ), 
	"components"   => array( "label" => __("Components"    , 'wordpress-social-login') , "enabled" => true ,  "visible" => true  , "component" => "core"          , "pull-right" => true , "welcome-panel" => false ), 
);

// --------------------------------------------------------------------

/**
* Register a new WSL component 
*/
function wsl_register_component( $component, $config, $tabs )
{
	GLOBAL $WORDPRESS_SOCIAL_LOGIN_COMPONENTS;

	// sure it can be overwritten.. just not recommended
	if( isset( $WORDPRESS_SOCIAL_LOGIN_COMPONENTS[ $component ] ) ){
		return wsl_render_notices_pages( _wsl__("An installed plugin is trying to o-ver-write WordPress Social Login config in a bad way.", 'wordpress-social-login') );
	}

	$config["type"] = "plugin";
	$WORDPRESS_SOCIAL_LOGIN_COMPONENTS[ $component ] = $config;  

	if( is_array( $tabs ) && count( $tabs ) ){
		foreach( $tabs as $tab => $config ){
			$config["component"] = $component; 

			wsl_register_admin_tab( $tab, $config );
		}
	}
}

add_action( 'wsl_register_component', 'wsl_register_component', 10, 3 );

// --------------------------------------------------------------------

/**
* Register new WSL admin tab
*/
function wsl_register_admin_tab( $tab, $config ) 
{ 
	GLOBAL $WORDPRESS_SOCIAL_LOGIN_ADMIN_TABS;

	// sure it can be overwritten.. just not recommended
	if( isset( $WORDPRESS_SOCIAL_LOGIN_ADMIN_TABS[ $tab ] ) ){
		return wsl_render_notices_pages( _wsl__("An installed plugin is trying to o-ver-write WordPress Social Login config in a bad way.", 'wordpress-social-login') );
	}

	$WORDPRESS_SOCIAL_LOGIN_ADMIN_TABS[ $tab ] = $config;  
}

add_action( 'wsl_register_admin_tab', 'wsl_register_admin_tab', 10, 2 );

// --------------------------------------------------------------------

/**
* Check if a component is enabled
*/
function wsl_is_component_enabled( $component )
{ 
	if( get_option( "wsl_components_" . $component . "_enabled" ) == 1 ){
		return true;
	}

	return false;
}

// --------------------------------------------------------------------

/**
* Register WSL components (Bulk action)
*/
function wsl_register_components()
{
	GLOBAL $WORDPRESS_SOCIAL_LOGIN_COMPONENTS;
	GLOBAL $WORDPRESS_SOCIAL_LOGIN_ADMIN_TABS;

	foreach( $WORDPRESS_SOCIAL_LOGIN_ADMIN_TABS as $tab => $config ){
		$WORDPRESS_SOCIAL_LOGIN_ADMIN_TABS[ $tab ][ "enabled" ] = false; 
	}

	foreach( $WORDPRESS_SOCIAL_LOGIN_COMPONENTS as $component => $config ){
		$WORDPRESS_SOCIAL_LOGIN_COMPONENTS[ $component ][ "enabled" ] = false;

		$is_component_enabled = get_option( "wsl_components_" . $component . "_enabled" );
		
		if( $is_component_enabled == 1 ){
			$WORDPRESS_SOCIAL_LOGIN_COMPONENTS[ $component ][ "enabled" ] = true;
		}

		if( $WORDPRESS_SOCIAL_LOGIN_COMPONENTS[ $component ][ "type" ] == "core" ){
			$WORDPRESS_SOCIAL_LOGIN_COMPONENTS[ $component ][ "enabled" ] = true;

			if( $is_component_enabled != 1 ){
				update_option( "wsl_components_" . $component . "_enabled", 1 );
			}
		}

		foreach( $WORDPRESS_SOCIAL_LOGIN_ADMIN_TABS as $tab => $tconfig ){ 
			if( $tconfig["component"] == $component ){
				
				if( $WORDPRESS_SOCIAL_LOGIN_COMPONENTS[ $component ][ "enabled" ] ){
					$WORDPRESS_SOCIAL_LOGIN_ADMIN_TABS[ $tab ][ "enabled" ] = true;
				}
			}
		}
	}
}

// --------------------------------------------------------------------

/**
* Register WSL core settings ( options; components )
*/
function wsl_register_setting()
{
	GLOBAL $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG;
	GLOBAL $WORDPRESS_SOCIAL_LOGIN_COMPONENTS;
	GLOBAL $WORDPRESS_SOCIAL_LOGIN_ADMIN_TABS;

	// HOOKABLE:
	do_action( 'wsl_register_setting_begin' );

	wsl_register_components();

	// idps credentials
	foreach( $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG AS $item ){
		$provider_id          = isset( $item["provider_id"]       ) ? $item["provider_id"]       : null; 
		$require_client_id    = isset( $item["require_client_id"] ) ? $item["require_client_id"] : null;
		$require_registration = isset( $item["new_app_link"]      ) ? $item["new_app_link"]      : null;

		register_setting( 'wsl-settings-group', 'wsl_settings_' . $provider_id . '_enabled' );

		if ( $require_registration ){ // require application?
			if ( $require_client_id ){ // key or id ?
				register_setting( 'wsl-settings-group', 'wsl_settings_' . $provider_id . '_app_id' ); 
			}
			else{
				register_setting( 'wsl-settings-group', 'wsl_settings_' . $provider_id . '_app_key' ); 
			}

			register_setting( 'wsl-settings-group', 'wsl_settings_' . $provider_id . '_app_secret' ); 
		}
	}

	register_setting( 'wsl-settings-group-customize'        , 'wsl_settings_connect_with_label'                               ); 
	register_setting( 'wsl-settings-group-customize'        , 'wsl_settings_social_icon_set'                                  ); 
	register_setting( 'wsl-settings-group-customize'        , 'wsl_settings_users_avatars'                                    ); 
	register_setting( 'wsl-settings-group-customize'        , 'wsl_settings_use_popup'                                        ); 
	register_setting( 'wsl-settings-group-customize'        , 'wsl_settings_widget_display'                                   ); 
	register_setting( 'wsl-settings-group-customize'        , 'wsl_settings_redirect_url'                                     ); 
	register_setting( 'wsl-settings-group-customize'        , 'wsl_settings_force_redirect_url'                               ); 
	register_setting( 'wsl-settings-group-customize'        , 'wsl_settings_users_notification'                               ); 
	register_setting( 'wsl-settings-group-customize'        , 'wsl_settings_authentication_widget_css'                        ); 

	register_setting( 'wsl-settings-group-contacts-import'  , 'wsl_settings_contacts_import_facebook'                         ); 
	register_setting( 'wsl-settings-group-contacts-import'  , 'wsl_settings_contacts_import_google'                           ); 
	register_setting( 'wsl-settings-group-contacts-import'  , 'wsl_settings_contacts_import_twitter'                          ); 
	register_setting( 'wsl-settings-group-contacts-import'  , 'wsl_settings_contacts_import_live'                             ); 
	register_setting( 'wsl-settings-group-contacts-import'  , 'wsl_settings_contacts_import_linkedin'                         ); 

	register_setting( 'wsl-settings-group-bouncer'          , 'wsl_settings_bouncer_registration_enabled'                     ); 
	register_setting( 'wsl-settings-group-bouncer'          , 'wsl_settings_bouncer_authentication_enabled'                   ); 

	register_setting( 'wsl-settings-group-bouncer'          , 'wsl_settings_bouncer_profile_completion_require_email'         );
	register_setting( 'wsl-settings-group-bouncer'          , 'wsl_settings_bouncer_profile_completion_change_email'          );
	register_setting( 'wsl-settings-group-bouncer'          , 'wsl_settings_bouncer_profile_completion_change_username'       );

	register_setting( 'wsl-settings-group-bouncer'          , 'wsl_settings_bouncer_new_users_moderation_level'               );
	register_setting( 'wsl-settings-group-bouncer'          , 'wsl_settings_bouncer_new_users_membership_default_role'        );

	register_setting( 'wsl-settings-group-bouncer'          , 'wsl_settings_bouncer_new_users_restrict_domain_enabled'        );
	register_setting( 'wsl-settings-group-bouncer'          , 'wsl_settings_bouncer_new_users_restrict_domain_list'           );
	register_setting( 'wsl-settings-group-bouncer'          , 'wsl_settings_bouncer_new_users_restrict_domain_text_bounce'    );
	register_setting( 'wsl-settings-group-bouncer'          , 'wsl_settings_bouncer_new_users_restrict_email_enabled'         );
	register_setting( 'wsl-settings-group-bouncer'          , 'wsl_settings_bouncer_new_users_restrict_email_list'            );
	register_setting( 'wsl-settings-group-bouncer'          , 'wsl_settings_bouncer_new_users_restrict_email_text_bounce'     );
	register_setting( 'wsl-settings-group-bouncer'          , 'wsl_settings_bouncer_new_users_restrict_profile_enabled'       );
	register_setting( 'wsl-settings-group-bouncer'          , 'wsl_settings_bouncer_new_users_restrict_profile_list'          );
	register_setting( 'wsl-settings-group-bouncer'          , 'wsl_settings_bouncer_new_users_restrict_profile_text_bounce'   );

	register_setting( 'wsl-settings-group-buddypress'       , 'wsl_settings_buddypress_enable_mapping' ); 
	register_setting( 'wsl-settings-group-buddypress'       , 'wsl_settings_buddypress_xprofile_map' ); 
	
	register_setting( 'wsl-settings-group-development'      , 'wsl_settings_development_mode_enabled' ); 

	add_option( 'wsl_settings_welcome_panel_enabled' );

	// HOOKABLE:
	do_action( 'wsl_register_setting_end' );
}

// --------------------------------------------------------------------

/**
 * Check if the current connection is being made over https
 */
function wsl_is_https_on()
{
	if (!empty ($_SERVER ['SERVER_PORT']))
	{
		if (trim ($_SERVER ['SERVER_PORT']) == '443')
		{
			return true;
		}
	}

	if (!empty ($_SERVER ['HTTP_X_FORWARDED_PROTO']))
	{
		if (strtolower (trim ($_SERVER ['HTTP_X_FORWARDED_PROTO'])) == 'https')
		{
			return true;
		}
	}

	if (!empty ($_SERVER ['HTTPS']))
	{
		if (strtolower (trim ($_SERVER ['HTTPS'])) == 'on' OR trim ($_SERVER ['HTTPS']) == '1')
		{
			return true;
		}
	}

	return false;
}

// --------------------------------------------------------------------

/**
 * Return the current url
 */
function wsl_get_current_url()
{
	//Extract parts
	$request_uri = (isset ($_SERVER ['REQUEST_URI']) ? $_SERVER ['REQUEST_URI'] : $_SERVER ['PHP_SELF']);
	$request_protocol = (wsl_is_https_on () ? 'https' : 'http');
	$request_host = (isset ($_SERVER ['HTTP_X_FORWARDED_HOST']) ? $_SERVER ['HTTP_X_FORWARDED_HOST'] : (isset ($_SERVER ['HTTP_HOST']) ? $_SERVER ['HTTP_HOST'] : $_SERVER ['SERVER_NAME']));

	//Port of this request
	$request_port = '';

	//We are using a proxy
	if (isset ($_SERVER ['HTTP_X_FORWARDED_PORT']))
	{
		// SERVER_PORT is usually wrong on proxies, don't use it!
		$request_port = intval ($_SERVER ['HTTP_X_FORWARDED_PORT']);
	}
	//Does not seem like a proxy
	elseif (isset ($_SERVER ['SERVER_PORT']))
	{
		$request_port = intval ($_SERVER ['SERVER_PORT']);
	}

	// Remove standard ports
	$request_port = (!in_array ($request_port, array (80, 443)) ? $request_port : '');

	//Build url
	$current_url = $request_protocol . '://' . $request_host . ( ! empty ($request_port) ? (':'.$request_port) : '') . $request_uri;

	// HOOKABLE: 
	$current_url = apply_filters ( 'wsl_hook_alter_current_url', $current_url );

	//Done
	return $current_url;
}

// --------------------------------------------------------------------

/**
* Display a debugging area.
*
* This function is highly inspired by the Query Monitor.
* https://wordpress.org/plugins/query-monitor/
*
* IMPORTANT: Use this only if Dev mode is enabled
*
* Note: in order for this function to display the sql queries, 'SAVEQUERIES' should be defined as true in 'wp-config.php'
*/
function wsl_display_debugging_area()
{
	global $wpdb, $wp_actions , $wp_filter, $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG, $WORDPRESS_SOCIAL_LOGIN_COMPONENTS;
?>
	<style>
		.wsl-dev-nonselectsql {
			color: #a0a !important;
		}
		.wsl-dev-expensivesql {
			color: #f44 !important;
		}
		.wsl-dev-optionfunc {
			color: #4a4 !important;
		}
		.wsl-dev-wslfunc {
			color: #1468fa !important;
		}
		.wsl-dev-nonwslfunc {
			color: #a0a !important;
		}
		.wsl-dev-table { 
			width:100%
			border: 1px solid #e5e5e5;
			box-shadow: 0 1px 1px rgba(0, 0, 0, 0.04);	
			border-spacing: 0;
			clear: both;
			margin: 0;
			width: 100%;
		}
		.wsl-dev-table td, .wsl-dev-table th {
			border: 1px solid #dddddd;
			padding: 8px 10px; 
			background-color: #fff;
			text-align: left;
		}
	</style>

	<h5>Host</h5>
	<table class="wsl-dev-table">
		<tbody>
			<tr><th width="150"><label>IP</label></th><td><?php echo $_SERVER['SERVER_ADDR']; ?></td></tr>  
			<tr><th><label>Domain</label></th><td><?php echo $_SERVER['HTTP_HOST']; ?></td></tr>  
			<tr><th><label>Port</label></th><td><?php echo isset( $_SERVER['SERVER_PORT'] ) ? 'On (' . $_SERVER['SERVER_PORT'] . ')' : 'N/A'; ?></td></tr>  
			<tr><th><label>X Forward</label></th><td><?php echo isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) ? 'On (' . $_SERVER['HTTP_X_FORWARDED_PROTO'] . ')' : 'N/A';; ?></td></tr>   
		</tbody>
	</table>
	
	<h5>Software</h5>
	<table class="wsl-dev-table">
		<tbody>
			<tr><th width="150"><label>Server</label></th><td><?php echo $_SERVER['SERVER_SOFTWARE']; ?></td></tr>  
			<tr><th><label>PHP</label></th><td><?php echo PHP_VERSION; ?></td></tr>  
			<tr><th><label>MySQL</label></th><td><?php echo $wpdb->db_version(); ?></td></tr>   
		</tbody>
	</table>

	<h5>MySQL</h5>
	<table class="wsl-dev-table">
		<tbody>
			<tr><th width="150"><label>Host</label></th><td><?php echo $wpdb->dbhost; ?></td></tr>  
			<tr><th><label>User</label></th><td><?php echo $wpdb->dbuser; ?></td></tr>  
			<tr><th><label>Database</label></th><td><?php echo $wpdb->dbname; ?></td></tr>  
			<tr><th><label>Prefix</label></th><td><?php echo $wpdb->prefix; ?></td></tr>  
			<tr><th><label>Base_prefix</label></th><td><?php echo $wpdb->prefix; ?></td></tr>  
			<tr><th><label>Num_queries</label></th><td><?php echo$wpdb->num_queries; ?></td></tr>  
		</tbody>
	</table>

	<h5>Wordpress</h5>
	<table class="wsl-dev-table">
		<tbody>
			<tr><th width="150"><label>Version</label></th><td><?php echo get_bloginfo( 'version' ); ?></td></tr>   
			<tr><th><label>Multi-site</label></th><td><?php echo is_multisite() ? 'Yes' . "\n" : 'No'; ?></td></tr>
			<tr><th><label>Site url</label></th><td><?php echo site_url(); ?></td></tr>   
			<tr><th><label>Plugins url</label></th><td><?php echo plugins_url(); ?></td></tr>    
		</tbody>
	</table>

	<h5>WSL</h5>
	<table class="wsl-dev-table">
		<tbody>
			<tr><th width="150"><label>Version</label></th><td><?php echo wsl_get_version(); ?></td></tr>  
			<tr><th><label>Providers</label></th><td><?php echo count( $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG ); ?></td></tr>   
			<tr><th><label>Components</label></th><td><?php echo count( $WORDPRESS_SOCIAL_LOGIN_COMPONENTS ); ?></td></tr>   
			<tr><th><label>Plugin path</label></th><td><?php echo WORDPRESS_SOCIAL_LOGIN_ABS_PATH; ?></td></tr>  
			<tr><th><label>Plugin url</label></th><td><?php echo WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL; ?></td></tr>  
			<tr><th><label>HA endpoint</label></th><td><?php echo WORDPRESS_SOCIAL_LOGIN_HYBRIDAUTH_ENDPOINT_URL; ?></td></tr>   
		</tbody>
	</table>

	<h5>SQL Queries <?php if( ! defined('SAVEQUERIES') || ! SAVEQUERIES ) echo " ('SAVEQUERIES' should be defined and set to TRUE: http://codex.wordpress.org/Editing_wp-config.php#Save_queries_for_analysis)"; ?></h5>
	<table class="wsl-dev-table">
		<tbody>
			<?php
				$queries = $wpdb->queries;
				
				$total_wsl_queries = 0;
				$total_wsl_queries_time = 0;
				
				if( $queries )
				foreach( $queries as $item ){
					$sql    = $item[0];
					$time   = $item[1];
					$stack  = $item[2];
					
					$sql = str_ireplace( array( ' FROM ', ' WHERE ' , ' LIMIT ' , ' GROUP BY ' , ' ORDER BY ' , ' SET ' ), ARRAY( "\n" . 'FROM ', "\n" . 'WHERE ', "\n" . 'LIMIT ', "\n" . 'GROUP BY ', "\n" . 'ORDER BY ', "\n" . 'SET ' ), $sql );

					# https://wordpress.org/plugins/query-monitor/
					$callers   = explode( ',', $stack );
					$caller    = trim( end( $callers ) );

					if ( false !== strpos( $caller, '(' ) )
						$caller_name = substr( $caller, 0, strpos( $caller, '(' ) ) . '()';
					else
						$caller_name = $caller;

					if( stristr( $caller_name, 'wsl_' ) || stristr( $sql, 'wsl_' ) || stristr( $stack, 'wsl_' ) ){
					?>
						<tr>
							<td valign="top" width="450">
								<span class="<?php if( stristr( $caller_name, '_option' ) ) echo 'wsl-dev-optionfunc'; elseif( stristr( $caller_name, 'wsl_' ) ) echo 'wsl-dev-wslfunc'; else echo 'wsl-dev-nonwslfunc'; ?>"><?php echo $caller_name; ?></span>
								<p style="font-size:11px; margin-left:10px">
								<?php
									# God damn it
									if(  count( $callers ) ){
										for( $i = count( $callers ) - 1; $i > 0; $i-- ){
											echo "#$i &nbsp; " . $callers[$i] . '<br />';
										}
									}
								?>
								</p>
							</td>
							<td valign="top" class="<?php if( ! stristr( '#' . $sql, '#select ' ) ) echo 'wsl-dev-nonselectsql'; ?>"><?php echo nl2br( $sql ); ?></td>
							<td valign="top" width="50" nowrap class="<?php if( $time > 0.05 ) echo 'wsl-dev-expensivesql'; ?>"><?php echo number_format( $time, 4, '.', '' ); ?></td>
						</tr>   
					<?php 
						$total_wsl_queries++;
						$total_wsl_queries_time += $time;
					}
				}
			?>
			<tr>
				<td colspan="2">Total SQL Queries by WSL : <?php echo $total_wsl_queries; ?></td>
				<td width="50" nowrap><?php echo number_format( $total_wsl_queries_time, 4, '.', '' ); ?></td>
			</tr>   
		</tbody>
	</table>

	<h5>Hooks</h5>
	<table class="wsl-dev-table">
		<tbody>
			<?php	
				if( $wp_actions )
				foreach( $wp_actions as $name => $count ){
					if ( isset( $wp_filter[$name] ) ) {
						$action = $wp_filter[$name]; 

						if ( 
							$name == 'init' ||
							stristr( $name, 'wsl_' ) ||
							stristr( $name, 'admin_' ) ||
							stristr( $name, 'plugins_' )  ||
							stristr( $name, 'wp_' ) ||
							stristr( $name, 'comment_' ) 
							// 1
						) {
							?>
								<tr>
									<td valign="top" width="150">
										<pre><?php echo $name ; ?></pre>
									</td>
									<td valign="top">
										<?php 
											if( $action )
											foreach( $action as $priority => $callbacks ) {
												foreach( $callbacks as $callback ) { 
													if( isset( $callback['function'] ) && is_string( $callback['function'] ) ){
														if( stristr( $callback['function'], 'wsl_' ) ){
															echo $callback['function'] . " ( $priority ) <br />" ; 
														} 
													} 
												}
											} 
										?>
									</td>
								</td>
							<?php 
						}
					}
				}
			?>
		</tbody>
	</table>
<?php
}

// --------------------------------------------------------------------
