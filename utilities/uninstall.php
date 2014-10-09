<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*    (c) 2011-2014 Mohamed Mrassi and contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

/**
* ! Moved to utilities until further notice.
*
* Uninstall WSL:
*
*   1. Delete wslusersprofiles, wsluserscontacts and wslwatchdog
*   2. Delete user metadata from usermeta
*   3. Delete registered options
*
* Ref: http://codex.wordpress.org/Function_Reference/register_uninstall_hook
*/

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// Exit if accessed directly
if( !defined( 'WP_UNINSTALL_PLUGIN' ) ) exit; 

// --------------------------------------------------------------------

	global $wpdb;

// 1. Delete wslusersprofiles, wsluserscontacts and wslwatchdog

	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}wslusersprofiles" ); 
	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}wsluserscontacts" );
	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}wslwatchdog" );

// 2. Delete user metadata from usermeta

	$wpdb->query( "DELETE FROM $wpdb->usermeta WHERE meta_key = 'wsl_current_provider'"   );
	$wpdb->query( "DELETE FROM $wpdb->usermeta WHERE meta_key = 'wsl_current_user_image'" );

// 3. Delete registered options

	delete_option( 'wsl_database_migration_version' );

	delete_option( 'wsl_settings_development_mode_enabled ' );

	delete_option( 'wsl_settings_welcome_panel_enabled' );

	delete_option( 'wsl_components_core_enabled ' );
	delete_option( 'wsl_components_networks_enabled ' );
	delete_option( 'wsl_components_login-widget_enabled ' );
	delete_option( 'wsl_components_bouncer_enabled' );
	delete_option( 'wsl_components_diagnostics_enabled' );
	delete_option( 'wsl_components_users_enabled' );
	delete_option( 'wsl_components_contacts_enabled ' );
	delete_option( 'wsl_components_buddypress_enabled ' );

	delete_option( 'wsl_settings_redirect_url ' );
	delete_option( 'wsl_settings_force_redirect_url ' );
	delete_option( 'wsl_settings_connect_with_label ' );
	delete_option( 'wsl_settings_use_popup' );
	delete_option( 'wsl_settings_widget_display ' );
	delete_option( 'wsl_settings_authentication_widget_css' ); 
	delete_option( 'wsl_settings_social_icon_set' );
	delete_option( 'wsl_settings_users_avatars' );
	delete_option( 'wsl_settings_users_notification ' );

	delete_option( 'wsl_settings_bouncer_registration_enabled ' );
	delete_option( 'wsl_settings_bouncer_authentication_enabled ' );
	delete_option( 'wsl_settings_bouncer_linking_accounts_enabled ' );
	delete_option( 'wsl_settings_bouncer_profile_completion_require_email ' );
	delete_option( 'wsl_settings_bouncer_profile_completion_change_email' );
	delete_option( 'wsl_settings_bouncer_profile_completion_change_username ' );  
	delete_option( 'wsl_settings_bouncer_new_users_moderation_level ' );
	delete_option( 'wsl_settings_bouncer_new_users_membership_default_role' );
	delete_option( 'wsl_settings_bouncer_new_users_restrict_domain_enabled' );
	delete_option( 'wsl_settings_bouncer_new_users_restrict_domain_text_bounce' );
	delete_option( 'wsl_settings_bouncer_new_users_restrict_email_enabled ' );
	delete_option( 'wsl_settings_bouncer_new_users_restrict_email_text_bounce ' );
	delete_option( 'wsl_settings_bouncer_new_users_restrict_profile_enabled ' );
	delete_option( 'wsl_settings_bouncer_new_users_restrict_profile_text_bounce ' );
	delete_option( 'wsl_settings_bouncer_new_users_restrict_domain_list ' );
	delete_option( 'wsl_settings_bouncer_new_users_restrict_email_list' );
	delete_option( 'wsl_settings_bouncer_new_users_restrict_profile_list' );

	delete_option( 'wsl_settings_contacts_import_facebook ' );
	delete_option( 'wsl_settings_contacts_import_google ' );
	delete_option( 'wsl_settings_contacts_import_twitter' );
	delete_option( 'wsl_settings_contacts_import_live ' );
	delete_option( 'wsl_settings_contacts_import_linkedin ' );

// --------------------------------------------------------------------
