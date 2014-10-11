<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*    (c) 2011-2014 Mohamed Mrassi and contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

/** 
* WSL Watchdog.
*
* This is an utility to Logs WSL authentication process in database.
*
* 1. Include '/utilities/watchdog.php' in wp-social-login.php
* 2. Log viewer: options-general.php?page=wordpress-social-login&wslp=watchdog 
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------

wsl_watchdog_main();

function wsl_watchdog_main()
{
	global $wpdb;

	$sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}wslwatchdog` ( 
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `session_id` varchar(50) NOT NULL,
			  `user_id` int(11) NOT NULL,
			  `user_ip` varchar(50) NOT NULL,
			  `url` varchar(450) NOT NULL,
			  `provider` varchar(50) NOT NULL,
			  `action_name` varchar(255) NOT NULL,
			  `action_args` text NOT NULL,
			  `is_connected` int(11) NOT NULL,
			  `created_at` varchar(50) NOT NULL,
			  PRIMARY KEY (`id`) 
			)"; 

	$wpdb->query( $sql ); 

	add_action( 'wsl_process_login_start', 'wsl_watchdog_wsl_process_login' );
	add_action( 'wsl_process_login_begin_start', 'wsl_watchdog_wsl_process_login_begin_start' );
	add_action( 'wsl_process_login_end_start', 'wsl_watchdog_wsl_process_login_end_start' );

	add_action( 'wsl_hook_process_login_before_hybridauth_authenticate', 'wsl_watchdog_wsl_hook_process_login_before_hybridauth_authenticate', 10, 2 );
	add_action( 'wsl_hook_process_login_after_hybridauth_authenticate', 'wsl_watchdog_wsl_hook_process_login_after_hybridauth_authenticate', 10, 2 );

	add_action( 'wsl_process_login_end_get_user_data_start', 'wsl_watchdog_wsl_process_login_end_get_user_data_start', 10, 2 );

	add_action( 'wsl_process_login_complete_registration_start', 'wsl_watchdog_wsl_process_login_complete_registration_start', 10, 4 );
	
	add_action( 'wsl_process_login_create_wp_user_start', 'wsl_watchdog_wsl_process_login_create_wp_user_start', 10, 4 );
	add_action( 'wsl_process_login_update_wsl_user_data_start', 'wsl_watchdog_wsl_process_login_update_wsl_user_data_start', 10, 5 );

	add_action( 'wsl_process_login_authenticate_wp_user_start', 'wsl_watchdog_wsl_process_login_authenticate_wp_user_start', 10, 5 );

	add_action( 'wsl_hook_process_login_before_wp_set_auth_cookie', 'wsl_watchdog_wsl_hook_process_login_before_wp_set_auth_cookie', 10, 4 );
	add_action( 'wsl_hook_process_login_before_wp_safe_redirect', 'wsl_watchdog_wsl_hook_process_login_before_wp_safe_redirect', 10, 5 );

	add_action( 'wsl_render_login_form_user_loggedin', 'wsl_watchdog_wsl_render_login_form_user_loggedin' );

	add_action( 'wsl_process_login_render_error_page', 'wsl_watchdog_wsl_process_login_render_error_page', 10, 5 );
	add_action( 'wsl_process_login_render_notice_page', 'wsl_watchdog_wsl_process_login_render_notice_page', 10, 1 );
}

// --------------------------------------------------------------------

function wsl_log_database_insert_db( $action_name, $action_args = array(), $user_id = 0 )
{
	global $wpdb;

	$get_current_user_id = get_current_user_id();
	$provider = wsl_process_login_get_selected_provider();

	if( $get_current_user_id ){
		$provider = get_user_meta( $get_current_user_id, 'wsl_current_provider', true );
	}

	$action_args[] = $_POST;

	$wpdb->insert(
		"{$wpdb->prefix}wslwatchdog", 
			array( 
				"session_id"    => session_id(),
				"user_id" 		=> $user_id ? $user_id : $get_current_user_id,
				"user_ip" 		=> $_SERVER['REMOTE_ADDR'],
				"url" 	        => wsl_get_current_url(), 
				"provider"      => $provider, 
				"action_name" 	=> $action_name,
				"action_args" 	=> json_encode( $action_args ),
				"is_connected"  => $get_current_user_id ? 1 : 0, 
				"created_at"    => microtime( true ), 
			)
		); 
}

// --------------------------------------------------------------------

function wsl_watchdog_wsl_process_login()
{
	wsl_log_database_insert_db( 'wsl_process_login' );
}

// --------------------------------------------------------------------

function wsl_watchdog_wsl_process_login_begin_start()
{
	wsl_log_database_insert_db( 'wsl_process_login_begin_start' );
}

// --------------------------------------------------------------------

function wsl_watchdog_wsl_process_login_end_start()
{
	wsl_log_database_insert_db( 'wsl_process_login_end_start' );
}

// --------------------------------------------------------------------

function wsl_watchdog_wsl_process_login_end_get_user_data_start( $provider, $redirect_to )
{
	wsl_log_database_insert_db( 'wsl_process_login_end_get_user_data_start', array( $provider, $redirect_to ) );
}

// --------------------------------------------------------------------

function wsl_watchdog_wsl_hook_process_login_before_hybridauth_authenticate( $provider, $config )
{
	wsl_log_database_insert_db( 'wsl_hook_process_login_before_hybridauth_authenticate', array( $provider, $config ) );
}

// --------------------------------------------------------------------

function wsl_watchdog_wsl_hook_process_login_after_hybridauth_authenticate( $provider, $config )
{
	wsl_log_database_insert_db( 'wsl_hook_process_login_after_hybridauth_authenticate', array( $provider, $config ) );
}

// --------------------------------------------------------------------

function wsl_watchdog_wsl_process_login_complete_registration_start( $provider, $redirect_to, $hybridauth_user_email, $hybridauth_user_login )
{
	wsl_log_database_insert_db( 'wsl_process_login_complete_registration_start', array( $provider, $redirect_to, $hybridauth_user_email, $hybridauth_user_login ) );
}

// --------------------------------------------------------------------

function wsl_watchdog_wsl_process_login_create_wp_user_start( $provider, $hybridauth_user_profile, $request_user_login, $request_user_email )
{
	wsl_log_database_insert_db( 'wsl_process_login_create_wp_user_start', array( $provider, $hybridauth_user_profile, $request_user_login, $request_user_email ) );
}

// --------------------------------------------------------------------

function wsl_watchdog_wsl_process_login_update_wsl_user_data_start( $is_new_user, $user_id, $provider, $adapter, $hybridauth_user_profile  )
{
	wsl_log_database_insert_db( 'wsl_process_login_update_wsl_user_data_start', array( $is_new_user, $user_id, $provider, $adapter, $hybridauth_user_profile ), $user_id );
}

// --------------------------------------------------------------------

function wsl_watchdog_wsl_process_login_authenticate_wp_user_start( $user_id, $provider, $redirect_to, $adapter, $hybridauth_user_profile  )
{
	wsl_log_database_insert_db( 'wsl_process_login_authenticate_wp_user_start', array( $user_id, $provider, $redirect_to, $adapter, $hybridauth_user_profile ), $user_id );
}

// --------------------------------------------------------------------

function wsl_watchdog_wsl_hook_process_login_before_wp_set_auth_cookie( $user_id, $provider, $hybridauth_user_profile  )
{
	wsl_log_database_insert_db( 'wsl_hook_process_login_before_wp_set_auth_cookie', array( $user_id, $provider, $hybridauth_user_profile ), $user_id );
}

// --------------------------------------------------------------------

function wsl_watchdog_wsl_hook_process_login_before_wp_safe_redirect( $user_id, $provider, $hybridauth_user_profile, $redirect_to )
{
	wsl_log_database_insert_db( 'wsl_hook_process_login_before_wp_safe_redirect', array( $user_id, $provider, $hybridauth_user_profile, $redirect_to ), $user_id );
}

// --------------------------------------------------------------------

function wsl_watchdog_wsl_process_login_render_error_page( $e, $config, $hybridauth, $provider, $adapter )
{
	wsl_log_database_insert_db( 'wsl_process_login_render_error_page', array( $e, $config, $hybridauth, $provider, $adapter ) );
}

// --------------------------------------------------------------------

function wsl_watchdog_wsl_process_login_render_notice_page( $message )
{
	wsl_log_database_insert_db( 'wsl_process_login_render_notice_page', array( $message ) );
}

// --------------------------------------------------------------------

function wsl_watchdog_wsl_render_login_form_user_loggedin()
{
	wsl_log_database_insert_db( 'wsl_render_login_form_user_loggedin' );
}

// --------------------------------------------------------------------
