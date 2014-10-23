<?php
/*!
* WordPress Social Login
*
* http://miled.github.io/wordpress-social-login/ | https://github.com/miled/wordpress-social-login
*  (c) 2011-2014 Mohamed Mrassi and contributors | http://wordpress.org/plugins/wordpress-social-login/
*/

/**
* Check and upgrade compatibilities from old WSL versions
*
* Here we attempt to:
*	- set to default all settings when WSL is installed
*	- make WSL compatible when updating from older versions, by registering new options
*
* Side note: Over time, the number of options have become too long, and as you can notice
*            things are not optimal. If you have any better idea on how to tackle this issue,
*            please don't hesitate to share it.
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------

/**
* Check and upgrade compatibilities from old WSL versions 
*/
function wsl_check_compatibilities()
{
	delete_option( 'wsl_settings_development_mode_enabled' );
	delete_option( 'wsl_settings_debug_mode_enabled' );
	delete_option( 'wsl_settings_welcome_panel_enabled' );

	if( ! get_option( 'wsl_settings_redirect_url' ) )
	{ 
		update_option( 'wsl_settings_redirect_url', site_url() );
	}

	if( ! get_option( 'wsl_settings_force_redirect_url' ) )
	{ 
		update_option( 'wsl_settings_force_redirect_url', 2 );
	}

	if( ! get_option( 'wsl_settings_connect_with_label' ) )
	{ 
		update_option( 'wsl_settings_connect_with_label', _wsl__("Connect with:", 'wordpress-social-login') );
	}

	if( ! get_option( 'wsl_settings_users_avatars' ) )
	{ 
		update_option( 'wsl_settings_users_avatars', 1 );
	}

	if( ! get_option( 'wsl_settings_use_popup' ) )
	{ 
		update_option( 'wsl_settings_use_popup', 2 );
	}

	if( ! get_option( 'wsl_settings_widget_display' ) )
	{ 
		update_option( 'wsl_settings_widget_display', 1 );
	}

	if( ! get_option( 'wsl_settings_authentication_widget_css' ) )
	{ 
		update_option( 'wsl_settings_authentication_widget_css', ".wp-social-login-connect-with {}\n.wp-social-login-provider-list {}\n.wp-social-login-provider-list a {}\n.wp-social-login-provider-list img {}\n.wsl_connect_with_provider {}" );
	}

	# bouncer settings
	if( ! get_option( 'wsl_settings_bouncer_registration_enabled' ) )
	{ 
		update_option( 'wsl_settings_bouncer_registration_enabled', 1 );
	}

	if( ! get_option( 'wsl_settings_bouncer_authentication_enabled' ) )
	{ 
		update_option( 'wsl_settings_bouncer_authentication_enabled', 1 );
	}

	if( get_option( 'wsl_settings_bouncer_email_validation_enabled' ) == 1 )
	{ 
		update_option( 'wsl_settings_bouncer_profile_completion_require_email', 1 );
	}

	if( ! get_option( 'wsl_settings_bouncer_profile_completion_require_email' ) )
	{ 
		update_option( 'wsl_settings_bouncer_profile_completion_require_email', 2 );
	}

	if( ! get_option( 'wsl_settings_bouncer_profile_completion_change_email' ) )
	{ 
		update_option( 'wsl_settings_bouncer_profile_completion_change_email', 2 );
	}

	if( ! get_option( 'wsl_settings_bouncer_profile_completion_change_username' ) )
	{ 
		update_option( 'wsl_settings_bouncer_profile_completion_change_username', 2 );
	}

	if( ! get_option( 'wsl_settings_bouncer_new_users_moderation_level' ) )
	{ 
		update_option( 'wsl_settings_bouncer_new_users_moderation_level', 1 );
	}

	if( ! get_option( 'wsl_settings_bouncer_new_users_membership_default_role' ) )
	{ 
		update_option( 'wsl_settings_bouncer_new_users_membership_default_role', "default" );
	}

	if( ! get_option( 'wsl_settings_bouncer_new_users_restrict_domain_enabled' ) )
	{ 
		update_option( 'wsl_settings_bouncer_new_users_restrict_domain_enabled', 2 );
	} 

	if( ! get_option( 'wsl_settings_bouncer_new_users_restrict_domain_text_bounce' ) )
	{
		update_option( 'wsl_settings_bouncer_new_users_restrict_domain_text_bounce', _wsl__("<strong>This website is restricted to invited readers only.</strong><p>It doesn't look like you have been invited to access this site. If you think this is a mistake, you might want to contact the website owner and request an invitation.<p>", 'wordpress-social-login') );
	}

	if( ! get_option( 'wsl_settings_bouncer_new_users_restrict_email_enabled' ) )
	{ 
		update_option( 'wsl_settings_bouncer_new_users_restrict_email_enabled', 2 );
	} 

	if( ! get_option( 'wsl_settings_bouncer_new_users_restrict_email_text_bounce' ) )
	{
		update_option( 'wsl_settings_bouncer_new_users_restrict_email_text_bounce', _wsl__("<strong>This website is restricted to invited readers only.</strong><p>It doesn't look like you have been invited to access this site. If you think this is a mistake, you might want to contact the website owner and request an invitation.<p>", 'wordpress-social-login') );
	}

	if( ! get_option( 'wsl_settings_bouncer_new_users_restrict_profile_enabled' ) )
	{ 
		update_option( 'wsl_settings_bouncer_new_users_restrict_profile_enabled', 2 );
	}

	if( ! get_option( 'wsl_settings_bouncer_new_users_restrict_profile_text_bounce' ) )
	{
		update_option( 'wsl_settings_bouncer_new_users_restrict_profile_text_bounce', _wsl__("<strong>This website is restricted to invited readers only.</strong><p>It doesn't look like you have been invited to access this site. If you think this is a mistake, you might want to contact the website owner and request an invitation.<p>", 'wordpress-social-login') );
	}

	# contacts import
	if( ! get_option( 'wsl_settings_contacts_import_facebook' ) )
	{ 
		update_option( 'wsl_settings_contacts_import_facebook', 2 );
	}

	if( ! get_option( 'wsl_settings_contacts_import_google' ) )
	{ 
		update_option( 'wsl_settings_contacts_import_google', 2 );
	}

	if( ! get_option( 'wsl_settings_contacts_import_twitter' ) )
	{ 
		update_option( 'wsl_settings_contacts_import_twitter', 2 );
	}

	if( ! get_option( 'wsl_settings_contacts_import_live' ) )
	{ 
		update_option( 'wsl_settings_contacts_import_live', 2 );
	}

	if( ! get_option( 'wsl_settings_contacts_import_linkedin' ) )
	{ 
		update_option( 'wsl_settings_contacts_import_linkedin', 2 );
	}

	if( ! get_option( 'wsl_settings_buddypress_enable_mapping' ) )
	{ 
		update_option( 'wsl_settings_buddypress_enable_mapping', 2 );
	}

	# buddypress profile mapping
	if( ! get_option( 'wsl_settings_buddypress_xprofile_map' ) )
	{ 
		update_option( 'wsl_settings_buddypress_xprofile_map', '' );
	}

	global $wpdb;

	# migrate steam users id to id64. Prior to 2.2
	$sql = "UPDATE {$wpdb->prefix}wslusersprofiles
			SET identifier = REPLACE( identifier, 'http://steamcommunity.com/openid/id/', '' )
			WHERE provider = 'Steam' AND identifier like 'http://steamcommunity.com/openid/id/%' ";
	$wpdb->query( $sql );
}

// --------------------------------------------------------------------
