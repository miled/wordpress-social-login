<?php
/*
Plugin Name: WordPress Social Login
Plugin URI: http://wordpress.org/extend/plugins/wordpress-social-login/
Description: Allow your visitors to login and comment with social networks and identities providers such as Facebook, Twitter and Google. [Alpha Stage!!] 
Version: 1.1.6
Author: Miled
Author URI: http://wordpress.org/extend/plugins/wordpress-social-login/
License: GPL2
*/

session_start(); 

$_SESSION["wsl::plugin"] = "WordPress Social Login 1.1.6"; 

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
