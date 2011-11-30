<?php
$WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG = ARRAY(
	ARRAY( 
		"provider_id"       => "Facebook",
		"provider_name"     => "Facebook",
		"require_client_id" => TRUE, 
		"new_app_link"      => "https://www.facebook.com/developers/apps.php", 
	)
	,
	ARRAY(
		"provider_id"       => "Google",
		"provider_name"     => "Google",
		"callback"          => TRUE,
		"require_client_id" => TRUE, 
		"new_app_link"      => "https://code.google.com/apis/console/", 
	) 
	,
	ARRAY( 
		"provider_id"       => "Twitter",
		"provider_name"     => "Twitter",
		"callback"          => TRUE, 
		"new_app_link"      => "https://dev.twitter.com/apps", 
	)
	,
	ARRAY( 
		"provider_id"       => "Live",
		"provider_name"     => "Windows Live", 
		"require_client_id" => TRUE,
		"new_app_link"      => "https://manage.dev.live.com/ApplicationOverview.aspx", 
	)
	,
	ARRAY( 
		"provider_id"       => "MySpace",
		"provider_name"     => "MySpace", 
		"new_app_link"      => "http://www.developer.myspace.com/", 
	)
	,
	ARRAY( 
		"provider_id"       => "Foursquare",
		"provider_name"     => "Foursquare",
		"callback"          => TRUE,
		"require_client_id" => TRUE, 
		"new_app_link"      => "https://www.foursquare.com/oauth/", 
	)
	,
	ARRAY( 
		"provider_id"       => "LinkedIn",
		"provider_name"     => "LinkedIn",
		"callback"          => TRUE, 
		"new_app_link"      => "https://www.linkedin.com/secure/developer?newapp=", 
	)
	,
	ARRAY( 
		"provider_id"       => "Yahoo",
		"provider_name"     => "Yahoo!", 
		"new_app_link"      => NULL, 
	)
	,
	ARRAY( 
		"provider_id"       => "AOL",
		"provider_name"     => "AOL", 
		"new_app_link"      => NULL, 
	)
);

function wsl_admin_menu()
{
	add_options_page('WP Social Login', 'WP Social Login', 'manage_options', 'wordpress-social-login', 'wsl_render_settings' );
	
	add_action( 'admin_init', 'wsl_register_setting' );
}

add_action('admin_menu', 'wsl_admin_menu' );

function wsl_register_setting()
{
	GLOBAL $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG;

	foreach( $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG AS $item ){
		$provider_id          = @ $item["provider_id"]; 
		$require_client_id    = @ $item["require_client_id"];
		$require_registration = @ $item["new_app_link"];

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
}
