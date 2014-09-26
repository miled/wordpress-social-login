<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*    (c) 2011-2013 Mohamed Mrassi and contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

/**
* !!! WARNING: THIS SCRIPT IS UNCOMPLETE AND UNTESTED. PLEASE DO NOT USE IT !!!
* !!! WARNING: THIS SCRIPT IS UNCOMPLETE AND UNTESTED. PLEASE DO NOT USE IT !!!
*
* Uninstall WSL:
*
*   1. Delete wslusersprofiles and wsluserscontacts
*   2. Delete user metadata from usermeta
*   3. Delete registered wsl options
*
* Ref: http://codex.wordpress.org/Function_Reference/register_uninstall_hook
*
* !!! WARNING: THIS SCRIPT IS UNCOMPLETE AND UNTESTED. PLEASE DO NOT USE IT !!!
* !!! WARNING: THIS SCRIPT IS UNCOMPLETE AND UNTESTED. PLEASE DO NOT USE IT !!!
*/

// Exit if accessed directly
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) exit;

// --------------------------------------------------------------------

/*
	global $wpdb;

	// 1. Delete wslusersprofiles and wsluserscontacts

		$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}wslusersprofiles" ); 
		$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}wsluserscontacts" );

	// 2. Delete user metadata from usermeta

		$wpdb->query( "DELETE FROM {$wpdb->prefix}usermeta WHERE meta_key = 'wsl_user'"        );  
		$wpdb->query( "DELETE FROM {$wpdb->prefix}usermeta WHERE meta_key = 'wsl_user_gender'" );  
		$wpdb->query( "DELETE FROM {$wpdb->prefix}usermeta WHERE meta_key = 'wsl_user_age'"    );  
		$wpdb->query( "DELETE FROM {$wpdb->prefix}usermeta WHERE meta_key = 'wsl_user_image'"  );  

	// 3. Delete registered wsl options

		delete_option( 'wsl_database_migration_version' );

		delete_option( 'wsl_settings_development_mode_enabled ' );

		delete_option( 'wsl_settings_welcome_panel_enabled' );

		delete_option( 'wsl_components_core_enabled ' );
		delete_option( 'wsl_components_networks_enabled ' );
		delete_option( 'wsl_components_login-widget_enabled ' );
		delete_option( 'wsl_components_bouncer_enabled' );
		delete_option( 'wsl_components_diagnostics_enabled' );
		delete_option( 'wsl_components_basicinsights_enabled' );
		delete_option( 'wsl_components_users_enabled' );
		delete_option( 'wsl_components_contacts_enabled ' );

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

		delete_option( 'wsl_settings_Facebook_enabled ' );
		delete_option( 'wsl_settings_Google_enabled ' );
		delete_option( 'wsl_settings_Twitter_enabled' );
		delete_option( 'wsl_settings_PixelPin_enabled ' );
		delete_option( 'wsl_settings_Latch_enabled' );
		delete_option( 'wsl_settings_Facebook_app_id' );
		delete_option( 'wsl_settings_Facebook_app_secret' );
		delete_option( 'wsl_settings_Google_app_id' );
		delete_option( 'wsl_settings_Google_app_secret' );
		delete_option( 'wsl_settings_Twitter_app_key' );
		delete_option( 'wsl_settings_Twitter_app_secret ' );
		delete_option( 'wsl_settings_Live_enabled ' );
		delete_option( 'wsl_settings_Live_app_id' );
		delete_option( 'wsl_settings_Live_app_secret' );
		delete_option( 'wsl_settings_Yahoo_enabled' );
		delete_option( 'wsl_settings_Foursquare_enabled ' );
		delete_option( 'wsl_settings_Foursquare_app_id' );
		delete_option( 'wsl_settings_Foursquare_app_secret' );
		delete_option( 'wsl_settings_LinkedIn_enabled ' );
		delete_option( 'wsl_settings_LinkedIn_app_key ' );
		delete_option( 'wsl_settings_LinkedIn_app_secret' );
		delete_option( 'wsl_settings_AOL_enabled' );
		delete_option( 'wsl_settings_Vkontakte_enabled' );
		delete_option( 'wsl_settings_Vkontakte_app_id ' );
		delete_option( 'wsl_settings_Vkontakte_app_secret ' );
		delete_option( 'wsl_settings_LastFM_enabled ' );
		delete_option( 'wsl_settings_LastFM_app_key ' );
		delete_option( 'wsl_settings_LastFM_app_secret' );
		delete_option( 'wsl_settings_Instagram_enabled' );
		delete_option( 'wsl_settings_Instagram_app_id ' );
		delete_option( 'wsl_settings_Instagram_app_secret ' );
		delete_option( 'wsl_settings_Identica_enabled ' );
		delete_option( 'wsl_settings_Identica_app_key ' );
		delete_option( 'wsl_settings_Identica_app_secret' );
		delete_option( 'wsl_settings_Tumblr_enabled ' );
		delete_option( 'wsl_settings_Tumblr_app_key ' );
		delete_option( 'wsl_settings_Tumblr_app_secret' );
		delete_option( 'wsl_settings_Goodreads_enabled' );
		delete_option( 'wsl_settings_Goodreads_app_key' );
		delete_option( 'wsl_settings_Goodreads_app_secret ' );
		delete_option( 'wsl_settings_Stackoverflow_enabled' );
		delete_option( 'wsl_settings_GitHub_enabled ' );
		delete_option( 'wsl_settings_GitHub_app_id' );
		delete_option( 'wsl_settings_GitHub_app_secret' );
		delete_option( 'wsl_settings_500px_enabled' );
		delete_option( 'wsl_settings_500px_app_key' );
		delete_option( 'wsl_settings_500px_app_secret ' );
		delete_option( 'wsl_settings_Skyrock_enabled' );
		delete_option( 'wsl_settings_Skyrock_app_key' );
		delete_option( 'wsl_settings_Skyrock_app_secret ' );
		delete_option( 'wsl_settings_Mixi_enabled ' );
		delete_option( 'wsl_settings_Steam_enabled' );
		delete_option( 'wsl_settings_TwitchTV_enabled ' );
		delete_option( 'wsl_settings_TwitchTV_app_id' );
		delete_option( 'wsl_settings_TwitchTV_app_secret' );
		delete_option( 'wsl_settings_Mailru_enabled ' );
		delete_option( 'wsl_settings_Mailru_app_id' );
		delete_option( 'wsl_settings_Mailru_app_secret' );
		delete_option( 'wsl_settings_Yandex_enabled ' );
		delete_option( 'wsl_settings_Yandex_app_id' );
		delete_option( 'wsl_settings_Yandex_app_secret' );
		delete_option( 'wsl_settings_Odnoklassniki_enabled' );
		delete_option( 'wsl_settings_Odnoklassniki_app_id ' );
		delete_option( 'wsl_settings_Odnoklassniki_app_secret ' );
		delete_option( 'wsl_settings_PixelPin_app_id' );
		delete_option( 'wsl_settings_PixelPin_app_secret' );
*/

// --------------------------------------------------------------------
