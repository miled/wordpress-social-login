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

		if( get_option( "wsl_components_" . $component . "_enabled" ) == 1 ){
			$WORDPRESS_SOCIAL_LOGIN_COMPONENTS[ $component ][ "enabled" ] = true;
		}

		if( $WORDPRESS_SOCIAL_LOGIN_COMPONENTS[ $component ][ "type" ] == "core" ){
			$WORDPRESS_SOCIAL_LOGIN_COMPONENTS[ $component ][ "enabled" ] = true;

			update_option( "wsl_components_" . $component . "_enabled", 1 );
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
function wsl_is_https_on ()
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
function wsl_get_current_url ()
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
