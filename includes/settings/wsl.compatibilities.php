<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*    (c) 2011-2013 Mohamed Mrassi and contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

/**
* Check and upgrade compatibilities from old WSL versions
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------

/**
* Check and upgrade compatibilities from old WSL versions 
*
* Here we attempt to:
*	- set to default all settings when WSL is installed
*	- make wsl compatible when updating from old versions by registring any option
*
* Side note: the things here are not optimal and the list is kind of long. If you have any 
* better idea on how to tackle this issue, please don't hesitate to share it!
*/
function wsl_check_compatibilities()
{
	# widget settings / customize
	if( ! get_option( 'wsl_settings_redirect_url' ) ){ 
		update_option( 'wsl_settings_redirect_url', site_url() );
	}

	if( ! get_option( 'wsl_settings_connect_with_label' ) ){ 
		update_option( 'wsl_settings_connect_with_label', "Connect with:" );
	}

	if( ! get_option( 'wsl_settings_use_popup' ) ){ 
		update_option( 'wsl_settings_use_popup', 2 );
	}

	if( ! get_option( 'wsl_settings_widget_display' ) ){ 
		update_option( 'wsl_settings_widget_display', 1 );
	}

	if( ! get_option( 'wsl_settings_authentication_widget_css' ) ){ 
		update_option( 'wsl_settings_authentication_widget_css', "#wp-social-login-connect-with {font-weight: bold;}\n#wp-social-login-connect-options {padding:10px;}\n#wp-social-login-connect-options a {text-decoration: none;}\n#wp-social-login-connect-options img {border:0 none;}\n.wsl_connect_with_provider {}" );
	}

	# bouncer
	if( ! get_option( 'wsl_settings_bouncer_registration_enabled' ) ){ 
		update_option( 'wsl_settings_bouncer_registration_enabled', 1 );
	}

	if( ! get_option( 'wsl_settings_bouncer_authentication_enabled' ) ){ 
		update_option( 'wsl_settings_bouncer_authentication_enabled', 1 );
	}

	if( ! get_option( 'wsl_settings_bouncer_linking_accounts_enabled' ) ){ 
		update_option( 'wsl_settings_bouncer_linking_accounts_enabled', 2 );
	}

	if( get_option( 'wsl_settings_bouncer_email_validation_enabled' ) == 1 ){ 
		update_option( 'wsl_settings_bouncer_profile_completion_require_email', 1 );

		delete_option( 'wsl_settings_bouncer_email_validation_enabled' );
		delete_option( 'wsl_settings_bouncer_email_validation_text_notice' );
		delete_option( 'wsl_settings_bouncer_email_validation_text_submit_button' );
		delete_option( 'wsl_settings_bouncer_email_validation_text_email' );
		delete_option( 'wsl_settings_bouncer_email_validation_text_username' );
		delete_option( 'wsl_settings_bouncer_email_validation_text_email_invalid' );
		delete_option( 'wsl_settings_bouncer_email_validation_text_username_invalid' );
		delete_option( 'wsl_settings_bouncer_email_validation_text_email_exists' );
		delete_option( 'wsl_settings_bouncer_email_validation_text_username_exists' );
		delete_option( 'wsl_settings_bouncer_email_validation_text_connected_with' ); 
	}

	if( ! get_option( 'wsl_settings_bouncer_profile_completion_require_email' ) ){ 
		update_option( 'wsl_settings_bouncer_profile_completion_require_email', 2 );
	}

	if( ! get_option( 'wsl_settings_bouncer_profile_completion_change_email' ) ){ 
		update_option( 'wsl_settings_bouncer_profile_completion_change_email', 2 );
	}

	if( ! get_option( 'wsl_settings_bouncer_profile_completion_change_username' ) ){ 
		update_option( 'wsl_settings_bouncer_profile_completion_change_username', 2 );
	}

	if( ! get_option( 'wsl_settings_bouncer_profile_completion_text_notice' ) ){
		update_option( 'wsl_settings_bouncer_profile_completion_text_notice', _wsl__("Almost there, we just need to check a couple of things", 'wordpress-social-login') );
	}

	if( ! get_option( 'wsl_settings_bouncer_profile_completion_text_submit_button' ) ){
		update_option( 'wsl_settings_bouncer_profile_completion_text_submit_button', _wsl__("Continue", 'wordpress-social-login') );
	} 

	if( ! get_option( 'wsl_settings_bouncer_profile_completion_text_email' ) ){
		update_option( 'wsl_settings_bouncer_profile_completion_text_email', _wsl__("E-mail", 'wordpress-social-login') );
	} 

	if( ! get_option( 'wsl_settings_bouncer_profile_completion_text_username' ) ){
		update_option( 'wsl_settings_bouncer_profile_completion_text_username', _wsl__("Username", 'wordpress-social-login') );
	} 

	if( ! get_option( 'wsl_settings_bouncer_profile_completion_text_email_invalid' ) ){
		update_option( 'wsl_settings_bouncer_profile_completion_text_email_invalid', _wsl__("E-mail is not valid!", 'wordpress-social-login') );
	} 

	if( ! get_option( 'wsl_settings_bouncer_profile_completion_text_username_invalid' ) ){
		update_option( 'wsl_settings_bouncer_profile_completion_text_username_invalid', _wsl__("Username is not valid!", 'wordpress-social-login') );
	} 

	if( ! get_option( 'wsl_settings_bouncer_profile_completion_text_email_exists' ) ){
		update_option( 'wsl_settings_bouncer_profile_completion_text_email_exists', _wsl__("That E-mail is already registered!", 'wordpress-social-login') );
	} 

	if( ! get_option( 'wsl_settings_bouncer_profile_completion_text_username_exists' ) ){
		update_option( 'wsl_settings_bouncer_profile_completion_text_username_exists', _wsl__("That Username is already registered!", 'wordpress-social-login') );
	} 

	if( ! get_option( 'wsl_settings_bouncer_profile_completion_text_connected_with' ) ){
		update_option( 'wsl_settings_bouncer_profile_completion_text_connected_with', _wsl__("You are now connected via", 'wordpress-social-login') );
	} 

	if( ! get_option( 'wsl_settings_bouncer_new_users_moderation_level' ) ){ 
		update_option( 'wsl_settings_bouncer_new_users_moderation_level', 1 );
	}

	if( ! get_option( 'wsl_settings_bouncer_new_users_membership_default_role' ) ){ 
		update_option( 'wsl_settings_bouncer_new_users_membership_default_role', "default" );
	}

	if( ! get_option( 'wsl_settings_bouncer_new_users_restrict_domain_enabled' ) ){ 
		update_option( 'wsl_settings_bouncer_new_users_restrict_domain_enabled', 2 );
	} 

	if( ! get_option( 'wsl_settings_bouncer_new_users_restrict_domain_text_bounce' ) ){
		update_option( 'wsl_settings_bouncer_new_users_restrict_domain_text_bounce', _wsl__("Bouncer says no.", 'wordpress-social-login') );
	}

	if( ! get_option( 'wsl_settings_bouncer_new_users_restrict_email_enabled' ) ){ 
		update_option( 'wsl_settings_bouncer_new_users_restrict_email_enabled', 2 );
	} 

	if( ! get_option( 'wsl_settings_bouncer_new_users_restrict_email_text_bounce' ) ){
		update_option( 'wsl_settings_bouncer_new_users_restrict_email_text_bounce', _wsl__("Bouncer say he refuses.", 'wordpress-social-login') );
	}

	if( ! get_option( 'wsl_settings_bouncer_new_users_restrict_profile_enabled' ) ){ 
		update_option( 'wsl_settings_bouncer_new_users_restrict_profile_enabled', 2 );
	}

	if( ! get_option( 'wsl_settings_bouncer_new_users_restrict_profile_text_bounce' ) ){
		update_option( 'wsl_settings_bouncer_new_users_restrict_profile_text_bounce', _wsl__("Bouncer say only Mundo can go where he pleases!", 'wordpress-social-login') );
	}

	# contacts import
	if( ! get_option( 'wsl_settings_contacts_import_facebook' ) ){ 
		update_option( 'wsl_settings_contacts_import_facebook', 2 );
	}

	if( ! get_option( 'wsl_settings_contacts_import_google' ) ){ 
		update_option( 'wsl_settings_contacts_import_google', 2 );
	}

	if( ! get_option( 'wsl_settings_contacts_import_twitter' ) ){ 
		update_option( 'wsl_settings_contacts_import_twitter', 2 );
	}

	if( ! get_option( 'wsl_settings_contacts_import_live' ) ){ 
		update_option( 'wsl_settings_contacts_import_live', 2 );
	}

	if( ! get_option( 'wsl_settings_contacts_import_linkedin' ) ){ 
		update_option( 'wsl_settings_contacts_import_linkedin', 2 );
	}

	// enable default providers
	GLOBAL $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG;
	$nok = true; 
	foreach( $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG AS $item ){
		$provider_id = $item["provider_id"];
		
		if( get_option( 'wsl_settings_' . $provider_id . '_enabled' ) ){
			$nok = false;
		}
	}

	if( $nok ){
		foreach( $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG AS $item ){
			$provider_id = $item["provider_id"];
			
			if( isset( $item["default_network"] ) && $item["default_network"] ){
				update_option( 'wsl_settings_' . $provider_id . '_enabled', 1 );
			} 
		} 
	} 
}

// --------------------------------------------------------------------
