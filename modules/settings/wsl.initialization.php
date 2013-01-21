<?php
function wsl_admin_menu()
{
	add_options_page('WP Social Login', 'WP Social Login', 'manage_options', 'wordpress-social-login', 'wsl_render_settings' );
	
	add_action( 'admin_init', 'wsl_register_setting' );
}

add_action('admin_menu', 'wsl_admin_menu' ); 

function wsl_admin_menu_sidebar()
{
	
	add_menu_page( 'WP Social Login', 'WP Social Login', 'manage_options', 'wordpress-social-login', 'wsl_render_settings' ); 
}
 
add_action('admin_menu', 'wsl_admin_menu_sidebar');

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

	register_setting( 'wsl-settings-group-customize'  , 'wsl_settings_connect_with_label' ); 
	register_setting( 'wsl-settings-group-customize'  , 'wsl_settings_social_icon_set' ); 
	register_setting( 'wsl-settings-group-customize'  , 'wsl_settings_users_avatars' ); 
	register_setting( 'wsl-settings-group-customize'  , 'wsl_settings_use_popup' ); 
	register_setting( 'wsl-settings-group-customize'  , 'wsl_settings_widget_display' ); 
	register_setting( 'wsl-settings-group-customize'  , 'wsl_settings_redirect_url' ); 
	register_setting( 'wsl-settings-group-customize'  , 'wsl_settings_users_notification' ); 
	register_setting( 'wsl-settings-group-customize'  , 'wsl_settings_authentication_widget_css' ); 
	
	register_setting( 'wsl-settings-group-contacts-import'  , 'wsl_settings_contacts_import_facebook' ); 
	register_setting( 'wsl-settings-group-contacts-import'  , 'wsl_settings_contacts_import_google' ); 
	register_setting( 'wsl-settings-group-contacts-import'  , 'wsl_settings_contacts_import_twitter' ); 
	register_setting( 'wsl-settings-group-contacts-import'  , 'wsl_settings_contacts_import_live' ); 
	register_setting( 'wsl-settings-group-contacts-import'  , 'wsl_settings_contacts_import_linkedin' ); 

	register_setting( 'wsl-settings-group-bouncer'  , 'wsl_settings_bouncer_registration_enabled' ); 
	register_setting( 'wsl-settings-group-bouncer'  , 'wsl_settings_bouncer_authentication_enabled' ); 
	register_setting( 'wsl-settings-group-bouncer'  , 'wsl_settings_bouncer_email_validation_enabled' ); 
	register_setting( 'wsl-settings-group-bouncer'  , 'wsl_settings_bouncer_email_validation_text_notice' ); 
	register_setting( 'wsl-settings-group-bouncer'  , 'wsl_settings_bouncer_email_validation_text_submit_button' ); 
	register_setting( 'wsl-settings-group-bouncer'  , 'wsl_settings_bouncer_email_validation_text_connected_with' ); 
	register_setting( 'wsl-settings-group-bouncer'  , 'wsl_settings_bouncer_email_validation_text_email' ); 
	register_setting( 'wsl-settings-group-bouncer'  , 'wsl_settings_bouncer_email_validation_text_username' ); 
	register_setting( 'wsl-settings-group-bouncer'  , 'wsl_settings_bouncer_email_validation_text_email_invalid' ); 
	register_setting( 'wsl-settings-group-bouncer'  , 'wsl_settings_bouncer_email_validation_text_username_invalid' ); 
	register_setting( 'wsl-settings-group-bouncer'  , 'wsl_settings_bouncer_email_validation_text_email_exists' ); 
	register_setting( 'wsl-settings-group-bouncer'  , 'wsl_settings_bouncer_email_validation_text_username_exists' ); 
	register_setting( 'wsl-settings-group-bouncer'  , 'wsl_settings_bouncer_new_users_moderation_enabled' ); 
	register_setting( 'wsl-settings-group-bouncer'  , 'wsl_settings_bouncer_new_users_membership_default_role' ); 
	register_setting( 'wsl-settings-group-bouncer'  , 'wsl_settings_bouncer_new_users_passcode_enabled' ); 
	register_setting( 'wsl-settings-group-bouncer'  , 'wsl_settings_bouncer_new_users_passcode' ); 
	register_setting( 'wsl-settings-group-bouncer'  , 'wsl_settings_bouncer_new_users_passcode_text_notice' ); 
	register_setting( 'wsl-settings-group-bouncer'  , 'wsl_settings_bouncer_new_users_passcode_text_passcode' ); 
	register_setting( 'wsl-settings-group-bouncer'  , 'wsl_settings_bouncer_new_users_passcode_text_error' ); 
	register_setting( 'wsl-settings-group-bouncer'  , 'wsl_settings_bouncer_new_users_passcode_text_submit_button' ); 
	register_setting( 'wsl-settings-group-bouncer'  , 'wsl_settings_bouncer_new_users_agreement_enabled' ); 
	register_setting( 'wsl-settings-group-bouncer'  , 'wsl_settings_bouncer_new_users_agreement_text' ); 
	register_setting( 'wsl-settings-group-bouncer'  , 'wsl_settings_bouncer_agreement_text_submit_button' ); 
	register_setting( 'wsl-settings-group-bouncer'  , 'wsl_settings_bouncer_new_users_restrict_domain_enabled' ); 
	register_setting( 'wsl-settings-group-bouncer'  , 'wsl_settings_bouncer_new_users_restrict_domain_list' ); 
	register_setting( 'wsl-settings-group-bouncer'  , 'wsl_settings_bouncer_new_users_restrict_domain_text_bounce' ); 
	register_setting( 'wsl-settings-group-bouncer'  , 'wsl_settings_bouncer_new_users_restrict_email_enabled' ); 
	register_setting( 'wsl-settings-group-bouncer'  , 'wsl_settings_bouncer_new_users_restrict_email_list' );
	register_setting( 'wsl-settings-group-bouncer'  , 'wsl_settings_bouncer_new_users_restrict_email_text_bounce' );
	register_setting( 'wsl-settings-group-bouncer'  , 'wsl_settings_bouncer_new_users_restrict_profile_enabled' );
	register_setting( 'wsl-settings-group-bouncer'  , 'wsl_settings_bouncer_new_users_restrict_profile_list' );
	register_setting( 'wsl-settings-group-bouncer'  , 'wsl_settings_bouncer_new_users_restrict_profile_text_bounce' );

	register_setting( 'wsl-settings-group-development', 'wsl_settings_development_mode_enabled' ); 

	add_option( 'wsl_settings_welcome_panel_enabled' ); 

	// update old/all default wsl-settings
	wsl_check_compatibilities();
}

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

	return true;
}
