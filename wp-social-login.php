<?php
/*
Plugin Name: WordPress Social Login
Plugin URI: http://wordpress.org/extend/plugins/wordpress-social-login/
Description: Allow your visitors to login and comment with social networks and identities providers such as Facebook, Twitter and Google. [BETA Stage!!] 
Version: 1.1.9
Author: Miled
Author URI: http://wordpress.org/extend/plugins/wordpress-social-login/
License: GPL2
*/

@ session_start(); 

$_SESSION["wsl::plugin"] = "WordPress Social Login 1.1.9"; 

/**
 * Check technical requirements before activating the plugin.
 * Wordpress 3.0 or newer required, 3.1 would be better
 * PHP 5.2 or newer
 * CURL Required
 */
function wsl_activate()
{
	if ( ! function_exists ('register_post_status') )
	{
		deactivate_plugins (basename (dirname (__FILE__)) . '/' . basename (__FILE__));
		die( "This plugin requires WordPress 3.0 or newer. Please update your WordPress installation to activate this plugin." );
	}

	if ( ! session_id() )
	{
		deactivate_plugins (basename (dirname (__FILE__)) . '/' . basename (__FILE__));
		die( "This plugin requires the <a href='http://www.php.net/manual/en/book.session.php'>PHP Sessions</a> to be enabled." );
	}

	if ( ! function_exists ( 'curl_version' ) )
	{
		deactivate_plugins (basename (dirname (__FILE__)) . '/' . basename (__FILE__));
		die( "This plugin requires the <a href='http://www.php.net/manual/en/intro.curl.php'>PHP libcurl extension</a> be installed." );
	}

	if ( ! version_compare( PHP_VERSION, '5.2.0', '>=' ) )
	{
		deactivate_plugins (basename (dirname (__FILE__)) . '/' . basename (__FILE__));
		die( "This plugin requires the <a href='http://php.net/'>PHP 5.2</a> be installed." ); 
	}

	if ( extension_loaded('oauth') )
	{
		deactivate_plugins (basename (dirname (__FILE__)) . '/' . basename (__FILE__));
		die( "This plugin requires the <a href='http://php.net/manual/en/book.oauth.php'>PHP PECL OAuth extension</a> be disabled." ); 
	}

	do_action( 'wsl_activate' );
}

register_activation_hook( __FILE__, 'wsl_activate' );

/**
 * Add a settings link to the Plugins page
 * http://www.whypad.com/posts/wordpress-add-settings-link-to-plugins-page/785/
 */
function wsl_add_settings_link( $links, $file ){ 
	static $this_plugin;

	if ( ! $this_plugin ) $this_plugin = plugin_basename(__FILE__);

	if ( $file == $this_plugin ){
		$settings_link = '<a href="options-general.php?page=wordpress-social-login">Settings</a>';
		array_unshift( $links, $settings_link );
	}

	return $links;
}

add_filter( 'plugin_action_links', 'wsl_add_settings_link', 10, 2 ); 

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

/* Includes */ 
require_once( dirname (__FILE__) . '/includes/hybridauth.settings.php' ); 
require_once( dirname (__FILE__) . '/includes/plugin.init.php'         ); 
require_once( dirname (__FILE__) . '/includes/plugin.settings.php'     ); 
require_once( dirname (__FILE__) . '/includes/plugin.auth.php'         );  
require_once( dirname (__FILE__) . '/includes/plugin.ui.php'           );  
