<?php
/*
Plugin Name: WordPress Social Login
Plugin URI: http://wordpress.org/extend/plugins/wordpress-social-login/
Description: Allow your visitors to comment and login with social networks such as Twitter, Facebook, Google, Yahoo and more.
Version: 2.1.3
Author: Miled
Author URI: http://hybridauth.sourceforge.net
License: MIT License
Text Domain: wordpress-social-login
Domain Path: languages
*/

/*
****************************************************************************************************
* Hi and thanks for taking the time to check out WSL code.
*
* Please, don't hesitate to:
*
*	- Report bugs and issues.
*	- Contribute: Code, Reviews, Ideas and Design.
*	- Point out stupidity, smells and inconsistencies in the code.
*	- Criticize.
*
*
* If you want to contribute, please consider these general guide lines:
*
*	- Don't hesitate to delete code that doesn't make sense or looks redundant.
*	- Feel free to create new functions and files when needed.
*	- Use 'if' and 'foreach' as little as possible.
*	- No 'switch'. No 'for'.
*	- Avoid over-commenting.
*
*
* Coding Style :
*
* - Redable code.
* - Same name convention of wordpress: these long long self explanatory functions and variables.
* - Use tabs(8 chars): as devlopers we read and look at code 1/3 of the day and using clear 
* 	indentations could make life a bit easier.
*
**********
* License
****************************************************************************
*	Copyright (C) 2011-2013 Mohamed Mrassi and contributors
*
*	Permission is hereby granted, free of charge, to any person obtaining
*	a copy of this software and associated documentation files (the
*	"Software"), to deal in the Software without restriction, including
*	without limitation the rights to use, copy, modify, merge, publish,
*	distribute, sublicense, and/or sell copies of the Software, and to
*	permit persons to whom the Software is furnished to do so, subject to
*	the following conditions:
*
*	The above copyright notice and this permission notice shall be
*	included in all copies or substantial portions of the Software.
*
*	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
*	EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
*	MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
*	NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
*	LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
*	OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
*	WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
****************************************************************************/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------

@ session_start(); // shhhtt keept it a secret

$WORDPRESS_SOCIAL_LOGIN_VERSION = "2.1.4"; // I know

$_SESSION["wsl::plugin"] = "WordPress Social Login " . $WORDPRESS_SOCIAL_LOGIN_VERSION; // a useless piece of data stored for checking some stuff

// -------------------------------------------------------------------- 

/* Constants */ 

define( 'WORDPRESS_SOCIAL_LOGIN_ABS_PATH'				, WP_PLUGIN_DIR . '/wordpress-social-login'          );
define( 'WORDPRESS_SOCIAL_LOGIN_REL_PATH'				, dirname( plugin_basename( __FILE__ ) )             );
define( 'WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL'				, WP_PLUGIN_URL . '/wordpress-social-login'          );
define( 'WORDPRESS_SOCIAL_LOGIN_HYBRIDAUTH_ENDPOINT_URL', WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . '/hybridauth/' );

// --------------------------------------------------------------------

/**
* Check technical requirements before activating the plugin. 
* Wordpress 3.0 or newer required
*/
function wsl_activate()
{
	if ( ! function_exists ('register_post_status') ){
		deactivate_plugins (basename (dirname (__FILE__)) . '/' . basename (__FILE__));
		wp_die( __( "This plugin requires WordPress 3.0 or newer. Please update your WordPress installation to activate this plugin.", 'wordpress-social-login' ) );
	}

	do_action( 'wsl_activate' );
}

register_activation_hook( __FILE__, 'wsl_activate' );

// --------------------------------------------------------------------

/**
* Add a settings link to the Plugins page
*
* http://www.whypad.com/posts/wordpress-add-settings-link-to-plugins-page/785/
*/
function wsl_add_settings_link( $links, $file )
{
	static $this_plugin;

	if ( ! $this_plugin ) $this_plugin = plugin_basename(__FILE__);

	if ( $file == $this_plugin ){
		$settings_link = '<a href="options-general.php?page=wordpress-social-login">' . __( "Settings" ) . '</a>';

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

/**
* Loads the plugin's translated strings.
*
* http://codex.wordpress.org/Function_Reference/load_plugin_textdomain
*/
if ( function_exists ('load_plugin_textdomain') ){
	// B. Please. It's on purpose.
	load_plugin_textdomain ( 'wordpress-social-login', false, WORDPRESS_SOCIAL_LOGIN_REL_PATH . '/languages/' );
}

// --------------------------------------------------------------------

/**
* _e() wrapper
*
* This function is used for the localization widget to generate translations per page. 
* If you are using Poedit for example you could search for texts by _wsl_e and _wsl__ instead
*
* If you are new to wp/i18n, then check out this video youtube.com/watch?v=aGN-hbMCPMg
*
* And PLEASE, if you translated something on wsl then consider shareing it by droping an email
* even if it's one word or one sentence.
*/
function _wsl_e($text, $domain)
{
	global $WORDPRESS_SOCIAL_LOGIN_TEXTS; 

	$local = __($text, $domain);

	$WORDPRESS_SOCIAL_LOGIN_TEXTS[ preg_replace('/\s+/', ' ', strip_tags( $local ) ) ] = $text;

	echo $local;
}

// --------------------------------------------------------------------

/**
* __() wrapper
* 
* This function is used for the localization widget to generate translations per page. 
* If you are using Poedit for example you could search for texts by _wsl_e and _wsl__ instead
*
* If you are new to wp/i18n, then check out this video youtube.com/watch?v=aGN-hbMCPMg
*
* And PLEASE, if you translated something on wsl then consider shareing it by droping an email
* even if it's one word or one sentence.
*/
function _wsl__($text, $domain)
{
	global $WORDPRESS_SOCIAL_LOGIN_TEXTS;

	$local = __($text, $domain);

	$WORDPRESS_SOCIAL_LOGIN_TEXTS[ preg_replace('/\s+/', ' ', strip_tags( $local ) ) ] = $text;

	return $local;
}

// --------------------------------------------------------------------

/**
* Return the current used WSL version
*/
function wsl_version()
{
	global $WORDPRESS_SOCIAL_LOGIN_VERSION;

	return $WORDPRESS_SOCIAL_LOGIN_VERSION;
}

// -------------------------------------------------------------------- 

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
	require_once( dirname (__FILE__) . '/includes/admin/wsl.admin.ui.php'            ); // The LOC in charge of displaying WSL Admin GUInterfaces 
}

// --------------------------------------------------------------------

/* hooks */

register_activation_hook( __FILE__, 'wsl_database_migration_hook' );

// --------------------------------------------------------------------
