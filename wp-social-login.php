<?php
/*
Plugin Name: WordPress Social Login
Plugin URI: http://wordpress.org/extend/plugins/wordpress-social-login/
Description: Allow your visitors to comment and login with social networks such as Twitter, Facebook, Google, Yahoo and more.
Version: 2.0.3
Author: Miled
Author URI: http://hybridauth.sourceforge.net
License: MIT License
Text Domain: wordpress-social-login
Domain Path: languages
*/

/*
Copyright (C) 2013 Mohamed Mrassi and other contributors

Permission is hereby granted, free of charge, to any person obtaining
a copy of this software and associated documentation files (the
"Software"), to deal in the Software without restriction, including
without limitation the rights to use, copy, modify, merge, publish,
distribute, sublicense, and/or sell copies of the Software, and to
permit persons to whom the Software is furnished to do so, subject to
the following conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------

@ session_start(); 

$WORDPRESS_SOCIAL_LOGIN_VERSION = "2.1.0"; // i know

$_SESSION["wsl::plugin"] = "WordPress Social Login " . $WORDPRESS_SOCIAL_LOGIN_VERSION; 

// --------------------------------------------------------------------

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
		wp_die( __( "This plugin requires WordPress 3.0 or newer. Please update your WordPress installation to activate this plugin.", 'wordpress-social-login') );
	}

	do_action( 'wsl_activate' );
}

register_activation_hook( __FILE__, 'wsl_activate' );

// --------------------------------------------------------------------

/**
 * Add a settings link to the Plugins page
 * http://www.whypad.com/posts/wordpress-add-settings-link-to-plugins-page/785/
 */
function wsl_add_settings_link( $links, $file )
{ 
	static $this_plugin;

	if ( ! $this_plugin ) $this_plugin = plugin_basename(__FILE__);

	if ( $file == $this_plugin ){
		$settings_link = '<a href="options-general.php?page=wordpress-social-login">' . __("Settings") . '</a>';
		array_unshift( $links, $settings_link );
	}

	return $links;
}

add_filter( 'plugin_action_links', 'wsl_add_settings_link', 10, 2 ); 

// --------------------------------------------------------------------

/**
 * This file only need to be included for versions before 3.1.
 * Deprecated since version 3.1, the functions are included by default
 */
if ( ! function_exists ('email_exists') ){
	require_once( ABSPATH . WPINC . '/registration.php' );
}

// --------------------------------------------------------------------

/* Localization */ 

if ( function_exists ('load_plugin_textdomain') ){
	load_plugin_textdomain ( 'wordpress-social-login', false, WORDPRESS_SOCIAL_LOGIN_REL_PATH . '/languages/' );
}

// --------------------------------------------------------------------

function _wsl_e($text, $domain)
{
	global $WORDPRESS_SOCIAL_LOGIN_TEXTS; 

	$WORDPRESS_SOCIAL_LOGIN_TEXTS[ preg_replace('/\s+/', ' ', strip_tags( $text ) ) ] = $text;

	return _e($text, $domain);
}

// --------------------------------------------------------------------

function _wsl__($text, $domain)
{
	global $WORDPRESS_SOCIAL_LOGIN_TEXTS;

	$WORDPRESS_SOCIAL_LOGIN_TEXTS[ preg_replace('/\s+/', ' ', strip_tags( $text ) ) ] = $text;

	return __($text, $domain);
}

// --------------------------------------------------------------------

function wsl_version()
{
	global $WORDPRESS_SOCIAL_LOGIN_VERSION;

	return $WORDPRESS_SOCIAL_LOGIN_VERSION;
}

// -------------------------------------------------------------------- 

/* Constants */ 

define( 'WORDPRESS_SOCIAL_LOGIN_ABS_PATH'				, WP_PLUGIN_DIR . '/wordpress-social-login'             );
define( 'WORDPRESS_SOCIAL_LOGIN_REL_PATH'				, dirname( plugin_basename( __FILE__ ) ) 				);
define( 'WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL'				, WP_PLUGIN_URL . '/wordpress-social-login'             );
define( 'WORDPRESS_SOCIAL_LOGIN_HYBRIDAUTH_ENDPOINT_URL', WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . '/hybridauth/' 	);

/* includes */

# Settings
require_once( dirname (__FILE__) . '/includes/settings/wsl.providers.php' 			 ); // List of provider supported by hybridauth library 
require_once( dirname (__FILE__) . '/includes/settings/wsl.database.php'             ); // Functions & utililies related to wsl database installation and migrations
require_once( dirname (__FILE__) . '/includes/settings/wsl.initialization.php'       ); // Check wsl requirements and register wsl settings, list of components and admin tabs
require_once( dirname (__FILE__) . '/includes/settings/wsl.compatibilities.php'      ); // Check and upgrade compatibilities from old wsl versions 

# Services
require_once( dirname (__FILE__) . '/includes/services/wsl.authentication.php'       ); // Authenticate users via social networks. 
require_once( dirname (__FILE__) . '/includes/services/wsl.mail.notification.php'    ); // Email notifications to send. so far only the admin one is implemented
require_once( dirname (__FILE__) . '/includes/services/wsl.user.avatar.php'          ); // Displaying the user avatar when available on the comment section
require_once( dirname (__FILE__) . '/includes/services/wsl.user.data.php'            ); // User data functions (database related)

# WSL Widgets or so we call them
require_once( dirname (__FILE__) . '/includes/widgets/wsl.auth.widget.php'           ); // Authentication widget generators (yep where the icons are displayed)
require_once( dirname (__FILE__) . '/includes/widgets/wsl.complete.registration.php' ); // Page for users completing their registration (currently used only by Bouncer::Email Validation
require_once( dirname (__FILE__) . '/includes/widgets/wsl.notices.php'               ); // Kill WordPress execution and display HTML message with error message. in similar fashion to wp_die

# WSL Admin UIs
if( is_admin() ){
	require_once( dirname (__FILE__) . '/includes/admin/wsl.admin.ui.php'            ); // The 10 LOC in charge of displaying WSL Admin GUInterfaces
	require_once( dirname (__FILE__) . '/includes/widgets/wsl.admin.localize.php'    ); // Users invitational to help us localize WordPress Social Login
	require_once( dirname (__FILE__) . '/includes/widgets/wsl.admin.welcome.php'     ); // WSL welcome panel
}

// --------------------------------------------------------------------

/* hooks */

register_activation_hook( __FILE__, 'wsl_database_migration_hook' );

// --------------------------------------------------------------------
