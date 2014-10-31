<?php
/*!
* WordPress Social Login
*
* http://miled.github.io/wordpress-social-login/ | https://github.com/miled/wordpress-social-login
*  (c) 2011-2014 Mohamed Mrassi and contributors | http://wordpress.org/plugins/wordpress-social-login/
*/

/** 
* WSL logging agent
*
* This is an utility to Logs WSL authentication process to a file or database.
*
* Note:
*   Things ain't optimized here but will do for now.
*/

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------

function wsl_watchdog_init()
{
	if( ! get_option( 'wsl_settings_debug_mode_enabled' ) )
	{
		return;
	}

	define( 'WORDPRESS_SOCIAL_LOGIN_DEBUG_API_CALLS', true );

	add_action( 'wsl_process_login_start', 'wsl_watchdog_wsl_process_login' );
	add_action( 'wsl_process_login_begin_start', 'wsl_watchdog_wsl_process_login_begin_start' );
	add_action( 'wsl_process_login_end_start', 'wsl_watchdog_wsl_process_login_end_start' );

	add_action( 'wsl_hook_process_login_before_hybridauth_authenticate', 'wsl_watchdog_wsl_hook_process_login_before_hybridauth_authenticate', 10, 2 );
	add_action( 'wsl_hook_process_login_after_hybridauth_authenticate', 'wsl_watchdog_wsl_hook_process_login_after_hybridauth_authenticate', 10, 2 );

	add_action( 'wsl_process_login_end_get_user_data_start', 'wsl_watchdog_wsl_process_login_end_get_user_data_start', 10, 2 );

	add_action( 'wsl_process_login_complete_registration_start', 'wsl_watchdog_wsl_process_login_complete_registration_start', 10, 3 );

	add_action( 'wsl_process_login_create_wp_user_start', 'wsl_watchdog_wsl_process_login_create_wp_user_start', 10, 4 );
	add_action( 'wsl_hook_process_login_alter_wp_insert_user_data', 'wsl_watchdog_wsl_hook_process_login_alter_wp_insert_user_data', 10, 3 );
	add_action( 'wsl_process_login_update_wsl_user_data_start', 'wsl_watchdog_wsl_process_login_update_wsl_user_data_start', 10, 5 );

	add_action( 'wsl_process_login_authenticate_wp_user_start', 'wsl_watchdog_wsl_process_login_authenticate_wp_user_start', 10, 5 );

	add_action( 'wsl_hook_process_login_before_wp_set_auth_cookie', 'wsl_watchdog_wsl_hook_process_login_before_wp_set_auth_cookie', 10, 4 );
	add_action( 'wsl_hook_process_login_before_wp_safe_redirect', 'wsl_watchdog_wsl_hook_process_login_before_wp_safe_redirect', 10, 5 );

	add_action( 'wsl_process_login_render_error_page', 'wsl_watchdog_wsl_process_login_render_error_page', 10, 4 );
	add_action( 'wsl_process_login_render_notice_page', 'wsl_watchdog_wsl_process_login_render_notice_page', 10, 1 );

	add_action( 'wsl_log_provider_api_call', 'wsl_watchdog_wsl_log_provider_api_call', 10, 8 );
}

// --------------------------------------------------------------------

function wsl_watchdog_log_action( $action_name, $action_args = array(), $user_id = 0 )
{
	$provider = wsl_process_login_get_selected_provider();

	if( ! $provider )
	{
		if( isset( $_REQUEST['hauth_start'] ) ) $provider = $_REQUEST['hauth_start'];
		if( isset( $_REQUEST['hauth_done'] ) ) $provider = $_REQUEST['hauth_done'];
	}

	$action_args[] = "Backtrace: " . wsl_generate_backtrace();
	$action_args[] = 'USER: ' . get_current_user() . '. PID: ' . getmypid() . '. MEM: ' . ceil( memory_get_usage() / 1024 ) . 'KB.';

	if( get_option( 'wsl_settings_debug_mode_enabled' ) == 1 )
	{
		return wsl_watchdog_log_to_file( $action_name, $action_args, $user_id, $provider );
	}

	wsl_watchdog_log_to_database( $action_name, $action_args, $user_id, $provider );
}

// --------------------------------------------------------------------

function wsl_watchdog_log_to_file( $action_name, $action_args = array(), $user_id = 0, $provider = '' )
{
	$wp_upload_dir = wp_upload_dir();
	wp_mkdir_p( $wp_upload_dir['basedir'] . '/wordpress-social-login' );
	$wsl_path = $wp_upload_dir['basedir'] . '/wordpress-social-login';
	@file_put_contents( $wsl_path . '/.htaccess', "Deny from all" );
	@file_put_contents( $wsl_path . '/index.html', "" );

	$extra = '';
	if( in_array( $action_name, array( 'dbg:provider_api_call', 'wsl_hook_process_login_alter_wp_insert_user_data', 'wsl_process_login_update_wsl_user_data_start', 'wsl_process_login_authenticate_wp_user' ) ) )
	$extra = print_r( $action_args, true );

	$log_path = $wsl_path . '/auth-log-' . date ('d.m.Y') . '.log';
	file_put_contents( $log_path, "\n" . implode( ' -- ', array( session_id(), date('d-m-Y H:i:s'), $_SERVER['REMOTE_ADDR'], $provider, $user_id, $action_name, wsl_get_current_url(), $extra ) ), FILE_APPEND );
}

// --------------------------------------------------------------------

function wsl_watchdog_log_to_database( $action_name, $action_args = array(), $user_id = 0, $provider = '' )
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

	$wpdb->insert(
		"{$wpdb->prefix}wslwatchdog", 
			array( 
				"session_id"    => session_id(),
				"user_id"       => $user_id,
				"user_ip"       => $_SERVER['REMOTE_ADDR'],
				"url"           => wsl_get_current_url(), 
				"provider"      => $provider, 
				"action_name"   => $action_name,
				"action_args"   => json_encode( $action_args ),
				"is_connected"  => get_current_user_id() ? 1 : 0, 
				"created_at"    => microtime( true ), 
			)
		);
}

// --------------------------------------------------------------------

function wsl_watchdog_wsl_process_login()
{
	wsl_watchdog_log_action( 'wsl_process_login' );
}

// --------------------------------------------------------------------

function wsl_watchdog_wsl_process_login_begin_start()
{
	wsl_watchdog_log_action( 'wsl_process_login_begin_start' );
}

// --------------------------------------------------------------------

function wsl_watchdog_wsl_process_login_end_start()
{
	wsl_watchdog_log_action( 'wsl_process_login_end_start' );
}

// --------------------------------------------------------------------

function wsl_watchdog_wsl_process_login_end_get_user_data_start( $provider, $redirect_to )
{
	wsl_watchdog_log_action( 'wsl_process_login_end_get_user_data_start', array( $provider, $redirect_to ) );
}

// --------------------------------------------------------------------

function wsl_watchdog_wsl_hook_process_login_before_hybridauth_authenticate( $provider, $config )
{
	wsl_watchdog_log_action( 'wsl_hook_process_login_before_hybridauth_authenticate', array( $provider, $config ) );
}

// --------------------------------------------------------------------

function wsl_watchdog_wsl_hook_process_login_after_hybridauth_authenticate( $provider, $config )
{
	wsl_watchdog_log_action( 'wsl_hook_process_login_after_hybridauth_authenticate', array( $provider, $config ) );
}

// --------------------------------------------------------------------

function wsl_watchdog_wsl_process_login_complete_registration_start( $provider, $redirect_to, $hybridauth_user_profile )
{
	wsl_watchdog_log_action( 'wsl_process_login_complete_registration_start', array( $provider, $redirect_to, $hybridauth_user_profile ) );
}

// --------------------------------------------------------------------

function wsl_watchdog_wsl_process_login_create_wp_user_start( $provider, $hybridauth_user_profile, $request_user_login, $request_user_email )
{
	wsl_watchdog_log_action( 'wsl_process_login_create_wp_user_start', array( $provider, $hybridauth_user_profile, $request_user_login, $request_user_email ) );
}

// --------------------------------------------------------------------

function wsl_watchdog_wsl_hook_process_login_alter_wp_insert_user_data( $userdata, $provider, $hybridauth_user_profile )
{
	wsl_watchdog_log_action( 'wsl_hook_process_login_alter_wp_insert_user_data', array( $userdata, $provider, $hybridauth_user_profile ) );

	return $userdata;
}

// --------------------------------------------------------------------

function wsl_watchdog_wsl_process_login_update_wsl_user_data_start( $is_new_user, $user_id, $provider, $adapter, $hybridauth_user_profile  )
{
	wsl_watchdog_log_action( 'wsl_process_login_update_wsl_user_data_start', array( $is_new_user, $user_id, $provider, $adapter, $hybridauth_user_profile ), $user_id );
}

// --------------------------------------------------------------------

function wsl_watchdog_wsl_process_login_authenticate_wp_user_start( $user_id, $provider, $redirect_to, $adapter, $hybridauth_user_profile  )
{
	wsl_watchdog_log_action( 'wsl_process_login_authenticate_wp_user_start', array( $user_id, $provider, $redirect_to, $adapter, $hybridauth_user_profile ), $user_id );
}

// --------------------------------------------------------------------

function wsl_watchdog_wsl_hook_process_login_before_wp_set_auth_cookie( $user_id, $provider, $hybridauth_user_profile  )
{
	wsl_watchdog_log_action( 'wsl_hook_process_login_before_wp_set_auth_cookie', array( $user_id, $provider, $hybridauth_user_profile ), $user_id );
}

// --------------------------------------------------------------------

function wsl_watchdog_wsl_hook_process_login_before_wp_safe_redirect( $user_id, $provider, $hybridauth_user_profile, $redirect_to )
{
	wsl_watchdog_log_action( 'wsl_hook_process_login_before_wp_safe_redirect', array( $user_id, $provider, $hybridauth_user_profile, $redirect_to ), $user_id );
}

// --------------------------------------------------------------------

function wsl_watchdog_wsl_process_login_render_error_page( $e, $config, $provider, $adapter )
{
	wsl_watchdog_log_action( 'wsl_process_login_render_error_page', array( $e, $config, $provider, $adapter ) );
}

// --------------------------------------------------------------------

function wsl_watchdog_wsl_process_login_render_notice_page( $message )
{
	wsl_watchdog_log_action( 'wsl_process_login_render_notice_page', array( $message ) );
}

// --------------------------------------------------------------------

function wsl_watchdog_wsl_log_provider_api_call( $client, $url, $method, $post_data, $http_code, $http_info, $http_response )
{
	wsl_watchdog_log_action( 'dbg:provider_api_call', array( $client, $url, $method, $post_data, $http_code, $http_info, $http_response ) );
}

// --------------------------------------------------------------------
