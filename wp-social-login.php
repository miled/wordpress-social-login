<?php
/*
Plugin Name: WordPress Social Login
Plugin URI: http://wordpress.org/extend/plugins/wordpress-social-login/
Description: This plugin allow your visitors to register, login and comment with their accounts on social networks and identities providers such as Facebook, Twitter, Foursquare and Google. [<strong>Note</strong>:This plugin is still in Alpha Stage!!] 
Version: 1.1.3
Author: Miled
Author URI: http://wordpress.org/extend/plugins/wordpress-social-login/
License: GPL2
*/

/**
 * Check technical requirements before activating the plugin.
 * Wordpress 3.0 or newer required, 3.1 would be better
 * PHP 5.2 or newer
 * CURL Required
 */
function wsl_activate()
{
	if ( ! function_exists( 'register_post_status' ) || ! function_exists( 'curl_version' ) || version_compare( PHP_VERSION, '5.2', '<' ) ) {
		deactivate_plugins( basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ) );

		if ( ! function_exists( 'register_post_status' ) ){
			die( sprintf( __( "WordPress Social Login requires WordPress 3.0 or newer." ) ) );
		}

		if ( !function_exists( 'curl_version' ) ){
			die( sprintf( __( "WordPress Social Login requires the <a href='http://www.php.net/manual/en/intro.curl.php'>PHP libcurl extension</a> to be installed." ) ) ); 
		} 

		if ( version_compare( PHP_VERSION, '5.2.0', '<' ) ){
			die( sprintf( __( "WordPress Social Login requires WordPress 5.2 or newer." ) ) );
		}
	}

	do_action( 'wsl_activate' );
}

register_activation_hook( __FILE__, 'wsl_activate' );

/**
 * This file only need to be included for versions before 3.1.
 * Deprecated since version 3.1, the functions are included by default
 */
if ( ! function_exists ('email_exists') ){
	require_once( ABSPATH . WPINC . '/registration.php' );
}

require_once( dirname( dirname( dirname( dirname( __FILE__ )))) . '/wp-load.php' );

define( 'WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL', plugins_url() . '/' . basename( dirname( __FILE__ ) ) ); 
define( 'WORDPRESS_SOCIAL_LOGIN_HYBRIDAUTH_ENDPOINT_URL', WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . '/hybridauth/' ); 

/**
 * Include required files
 **/ 
require_once(dirname (__FILE__) . '/includes/hybridauth.settings.php'); 
require_once(dirname (__FILE__) . '/includes/plugin.settings.php'); 
require_once(dirname (__FILE__) . '/includes/ui.php'); 
 
// ------------------------

function wsl_process_login()
{
	if( ! isset( $_REQUEST[ 'action' ] ) || $_REQUEST[ 'action' ] !=  "wordpress_social_login" ){
		return;
	}

	if ( isset( $_REQUEST[ 'redirect_to' ] ) && $_REQUEST[ 'redirect_to' ] != '' ){
		$redirect_to = $_REQUEST[ 'redirect_to' ];

		// Redirect to https if user wants ssl
		if ( isset( $secure_cookie ) && $secure_cookie && false !== strpos( $redirect_to, 'wp-admin') ){
			$redirect_to = preg_replace( '|^http://|', 'https://', $redirect_to );
		}
	}
	else {
		$redirect_to = admin_url();
	}

	try{
		// load hybridauth
		require_once( dirname(__FILE__) . "/hybridauth/Hybrid/Auth.php" );

		// selected provider name 
		$provider = @ trim( strip_tags( $_REQUEST["provider"] ) );

		// build required configuratoin for this provider
		if( ! get_option( 'wsl_settings_' . $provider . '_enabled' ) ){
			throw new Exception( 'Unknown or disabled provider' );
		}

		$config = array();
		$config["base_url"]  = plugins_url() . '/' . basename( dirname( __FILE__ ) ) . '/hybridauth/';
		$config["providers"] = array();
		$config["providers"][$provider] = array();
		$config["providers"][$provider]["enabled"] = true;

		// provider application id ?
		if( get_option( 'wsl_settings_' . $provider . '_app_id' ) ){
			$config["providers"][$provider]["keys"]["id"] = get_option( 'wsl_settings_' . $provider . '_app_id' );
		}

		// provider application key ?
		if( get_option( 'wsl_settings_' . $provider . '_app_key' ) ){
			$config["providers"][$provider]["keys"]["key"] = get_option( 'wsl_settings_' . $provider . '_app_key' );
		}

		// provider application secret ?
		if( get_option( 'wsl_settings_' . $provider . '_app_secret' ) ){
			$config["providers"][$provider]["keys"]["secret"] = get_option( 'wsl_settings_' . $provider . '_app_secret' );
		}

		// create an instance for Hybridauth
		$hybridauth = new Hybrid_Auth( $config );

		// try to authenticate the selected $provider
		if( $hybridauth->isConnectedWith( $provider ) ){
			$adapter = $hybridauth->getAdapter( $provider );

			$hybridauth_user_profile = $adapter->getUserProfile();
		}
		else{
			throw new Exception( 'User inconnected!' );
		}

		$user_login = $provider . "_" . md5( $hybridauth_user_profile->identifier ) ;
		$user_email = $hybridauth_user_profile->email;

		// generate an email if none
		if( ! $user_email ){
			$user_email = $user_login . "@example.com";
		}
	}
	catch( Exception $e ){
		die( $e->getMessage() ); 
	}

	// Get user by meta
	$user_id = wsl_get_user_by_meta( $provider, $hybridauth_user_profile->identifier );

	// if user metadata found
	if( $user_id ){
		$user_data  = get_userdata( $user_id );
		$user_login = $user_data->user_login;
	}

	// User not found by provider identity, check by email
	elseif( $user_id = email_exists( $user_email ) ){
		$user_data  = get_userdata( $user_id );
		$user_login = $user_data->user_login;
	}

	// Create new user and associate provider identity
	else{
		$userdata = array(
			'user_login'    => $user_login,
			'user_email'    => $user_email,

			'first_name'    => $hybridauth_user_profile->firstName,
			'last_name'     => $hybridauth_user_profile->lastName,
			'user_nicename' => $hybridauth_user_profile->displayName,
			'display_name'  => $hybridauth_user_profile->displayName,
			'user_url'      => $hybridauth_user_profile->profileURL,
			'description'   => $hybridauth_user_profile->description,

			'user_pass'     => wp_generate_password()
		);

		// Create a new user
		$user_id = wp_insert_user( $userdata );

		print_r( $user_id );

		// update user metadata
		if( $user_id && is_integer( $user_id ) ){
			update_user_meta( $user_id, $provider, $hybridauth_user_profile->identifier ); 
		}
		else{
			die( "error!" );
		}
	}
 
	wp_set_auth_cookie( $user_id );

	wp_safe_redirect( $redirect_to );

	exit();
}

add_action( 'init', 'wsl_process_login' );

function wsl_get_user_by_meta( $provider, $user_uid )
{
	global $wpdb;

	$sql = "SELECT user_id FROM $wpdb->usermeta WHERE meta_key = '%s' AND meta_value = '%s'";

	return $wpdb->get_var( $wpdb->prepare( $sql, $provider, $user_uid ) );
}
