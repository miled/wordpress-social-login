<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*   (c) 2013 Mohamed Mrassi and other contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

/** 
* The 10 LOC or so in charge of displaying WSL Admin GUInterfaces
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------

function wsl_render_settings()
{ 
	if ( ! wsl_check_requirements() ){
		include "wsl.fail.php";

		exit;
	}

	GLOBAL $WORDPRESS_SOCIAL_LOGIN_ADMIN_MODULES_CONFIG;
	GLOBAL $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG;
	GLOBAL $WORDPRESS_SOCIAL_LOGIN_VERSION;
	GLOBAL $wpdb;

	$wslp            = "networks";
	$wsldwp          = 0;
	$assets_base_url = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . '/assets/img/16x16/';

	if( isset( $_REQUEST["wslp"] ) ){
		$wslp = trim( strtolower( strip_tags( $_REQUEST["wslp"] ) ) );
	}

	if( isset( $_REQUEST["wsldwp"] ) && (int) $_REQUEST["wsldwp"] ){
		$wsldwp = (int) $_REQUEST["wsldwp"];

		update_option( "wsl_settings_welcome_panel_enabled", $WORDPRESS_SOCIAL_LOGIN_VERSION );
	}

	if( isset( $WORDPRESS_SOCIAL_LOGIN_ADMIN_MODULES_CONFIG[$wslp] ) && $WORDPRESS_SOCIAL_LOGIN_ADMIN_MODULES_CONFIG[$wslp]["enabled"] ){
		include "wsl.header.php";

		if( ! ( isset( $WORDPRESS_SOCIAL_LOGIN_ADMIN_MODULES_CONFIG[$wslp]["welcome-panel"] ) && ! $WORDPRESS_SOCIAL_LOGIN_ADMIN_MODULES_CONFIG[$wslp]["welcome-panel"] ) ){
			include "wsl.welcome.panel.php";
		}

		include "wsl.$wslp.php";
	} 
}

// --------------------------------------------------------------------
