<?php
function wsl_render_settings()
{ 
	if ( ! wsl_check_requirements() ){
		include "wsl.fail.php";

		exit();
	}

	GLOBAL $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG;
	GLOBAL $WORDPRESS_SOCIAL_LOGIN_VERSION;

	$wslp   = "networks";
	$wsldwp = 0;

	if( isset( $_REQUEST["wslp"] ) ){
		$wslp = trim( strtolower( strip_tags( $_REQUEST["wslp"] ) ) );
	}

	if( isset( $_REQUEST["wsldwp"] ) && (int) $_REQUEST["wsldwp"] ){
		$wsldwp = (int) $_REQUEST["wsldwp"];

		update_option( "wsl_settings_welcome_panel_enabled", $WORDPRESS_SOCIAL_LOGIN_VERSION );
	}

	$tabs = array(
		"overview"     => array( "label" => "Overview"       , "enabled" => false, "visible" => false ),
		"networks"     => array( "label" => "Networks"       , "enabled" => true,  "visible" => true   , "default" => true ),
		"login-widget" => array( "label" => "Widget"         , "enabled" => true,  "visible" => true  ), 
		"bouncer"      => array( "label" => "Bouncer"        , "enabled" => true,  "visible" => true  ),
		"share"        => array( "label" => "Sharing"        , "enabled" => false, "visible" => false ),
		"users"        => array( "label" => "Users"          , "enabled" => true,  "visible" => true  ),
		"contacts"     => array( "label" => "Contacts"       , "enabled" => true,  "visible" => true  ),
		"diagnostics"  => array( "label" => "Diagnostics"    , "enabled" => true,  "visible" => false  , "pull-right" => true ),
		"help"         => array( "label" => "Help & Support" , "enabled" => true,  "visible" => true   , "pull-right" => true ),
	);

	

	if( isset( $tabs[$wslp] ) && $tabs[$wslp]["enabled"] ){
		include "wsl.header.php";
		include "wsl.$wslp.php";
	} 
}
