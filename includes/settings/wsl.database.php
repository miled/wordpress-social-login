<?php
/*!
* WordPress Social Login
*
* http://miled.github.io/wordpress-social-login/ | https://github.com/miled/wordpress-social-login
*  (c) 2011-2014 Mohamed Mrassi and contributors | http://wordpress.org/plugins/wordpress-social-login/
*/

/**
* Create WSL database tables upon installation
*
* When WSl is activated, wsl_database_migration_process() will attempt to create or upgrade the required database
* tables.
*
* Currently there is 2 tables used by WSL :
*	- wslusersprofiles:  where we store users profiles
*	- wsluserscontacts:  where we store users contact lists
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------

function wsl_database_install()
{
	global $wpdb;

	// create wsl tables
	$wslusersprofiles = "{$wpdb->prefix}wslusersprofiles";
	$wsluserscontacts = "{$wpdb->prefix}wsluserscontacts";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	$sql = "CREATE TABLE $wslusersprofiles ( 
			id int(11) NOT NULL AUTO_INCREMENT,
			user_id int(11) NOT NULL,
			provider varchar(50) NOT NULL,
			object_sha varchar(45) NOT NULL,
			identifier varchar(255) NOT NULL,
			profileurl varchar(255) NOT NULL,
			websiteurl varchar(255) NOT NULL,
			photourl varchar(255) NOT NULL,
			displayname varchar(150) NOT NULL,
			description varchar(255) NOT NULL,
			firstname varchar(150) NOT NULL,
			lastname varchar(150) NOT NULL,
			gender varchar(10) NOT NULL,
			language varchar(20) NOT NULL,
			age varchar(10) NOT NULL,
			birthday int(11) NOT NULL,
			birthmonth int(11) NOT NULL,
			birthyear int(11) NOT NULL,
			email varchar(255) NOT NULL,
			emailverified varchar(255) NOT NULL,
			phone varchar(75) NOT NULL,
			address varchar(255) NOT NULL,
			country varchar(75) NOT NULL,
			region varchar(50) NOT NULL,
			city varchar(50) NOT NULL,
			zip varchar(25) NOT NULL,
			UNIQUE KEY id (id),
			KEY user_id (user_id),
			KEY provider (provider)
		)"; 
	dbDelta( $sql );

	$sql = "CREATE TABLE $wsluserscontacts (
			id int(11) NOT NULL AUTO_INCREMENT,
			user_id int(11) NOT NULL,
			provider varchar(50) NOT NULL,
			identifier varchar(255) NOT NULL,
			full_name varchar(150) NOT NULL,
			email varchar(255) NOT NULL,
			profile_url varchar(255) NOT NULL,
			photo_url varchar(255) NOT NULL,
			UNIQUE KEY id (id),
			KEY user_id (user_id)
		)"; 
	dbDelta( $sql );
}

// --------------------------------------------------------------------

function wsl_database_uninstall()
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
		delete_option( 'wsl_settings_' . $provider['provider_id'] . '_app_scope' );
	}

	delete_option('wsl_settings_buddypress_xprofile_map' );

	// bye.
}

// --------------------------------------------------------------------
