<?php
/*
Plugin Name: WordPress Social Login
Plugin URI: http://wordpress.org/extend/plugins/wordpress-social-login/
Description: Allow your visitors to comment and login with social networks such as Twitter, Facebook, Google, Yahoo and more.
Version: 1.2.4
Author: Miled
Author URI: http://wordpress.org/extend/plugins/wordpress-social-login/
License: GPL2
*/

@ session_start(); 

$WORDPRESS_SOCIAL_LOGIN_VERSION = "2.0.2"; // i know

$_SESSION["wsl::plugin"] = "WordPress Social Login " . $WORDPRESS_SOCIAL_LOGIN_VERSION; 

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

	do_action( 'wsl_activate' );
}

register_activation_hook( __FILE__, 'wsl_activate' );

/**
 * Add a settings link to the Plugins page
 * http://www.whypad.com/posts/wordpress-add-settings-link-to-plugins-page/785/
 */
function wsl_add_settings_link( $links, $file )
{ 
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

/* includes */ 

# Settings
require_once( dirname (__FILE__) . '/modules/settings/wsl.providers.php' );              // list of provider supported by hybridauth library
require_once( dirname (__FILE__) . '/modules/settings/wsl.database.php' );               // functions & utililies related to wsl database installation and migrations
require_once( dirname (__FILE__) . '/modules/settings/wsl.initialization.php' );         // check wsl requirements and register wsl settings 
require_once( dirname (__FILE__) . '/modules/settings/wsl.compatibilities.php' );        // check and upgrade compatibilities from old wsl versions 

# Services
require_once( dirname (__FILE__) . '/modules/services/wsl.authentication.php' );         // 
require_once( dirname (__FILE__) . '/modules/services/wsl.mail.notification.php' );      // 
require_once( dirname (__FILE__) . '/modules/services/wsl.user.avatar.php' );            // 
require_once( dirname (__FILE__) . '/modules/services/wsl.user.data.php' );              // 

# UIs
require_once( dirname (__FILE__) . '/modules/admin/wsl.admin.ui.php' );                  // 
require_once( dirname (__FILE__) . '/modules/widgets/wsl.auth.widget.php' );             // 
require_once( dirname (__FILE__) . '/modules/widgets/wsl.complete.registration.php' );   // 
require_once( dirname (__FILE__) . '/modules/widgets/wsl.bouncer.disclaimer.php' );      // 
require_once( dirname (__FILE__) . '/modules/widgets/wsl.bouncer.passcode.php' );        // 
require_once( dirname (__FILE__) . '/modules/widgets/wsl.bouncer.welcome.php' );         // 
require_once( dirname (__FILE__) . '/modules/widgets/wsl.notices.php' );                 // 

/* hooks */ 

register_activation_hook( __FILE__, 'wsl_database_migration_hook' );
