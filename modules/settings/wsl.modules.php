<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*   (c) 2013 Mohamed Mrassi and other contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

/**
* List of WSL admin moduldes
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------

$WORDPRESS_SOCIAL_LOGIN_ADMIN_MODULES_CONFIG = ARRAY( 
	"overview"     => array( "label" => __("Overview"      , 'wordpress-social-login') , "enabled" => false, "visible" => false ),
	"networks"     => array( "label" => __("Networks"      , 'wordpress-social-login') , "enabled" => true,  "visible" => true   , "default" => true ),
	"login-widget" => array( "label" => __("Widget"        , 'wordpress-social-login') , "enabled" => true,  "visible" => true  ), 
	"bouncer"      => array( "label" => __("Bouncer"       , 'wordpress-social-login') , "enabled" => true,  "visible" => true  ),
	"share"        => array( "label" => __("Sharing"       , 'wordpress-social-login') , "enabled" => false, "visible" => false ),
	"users"        => array( "label" => __("Users"         , 'wordpress-social-login') , "enabled" => true,  "visible" => true  ),
	"contacts"     => array( "label" => __("Contacts"      , 'wordpress-social-login') , "enabled" => true,  "visible" => true  ),
	"diagnostics"  => array( "label" => __("Diagnostics"   , 'wordpress-social-login') , "enabled" => true,  "visible" => false  , "pull-right" => true , "welcome-panel" => false ),
	"help"         => array( "label" => __("Help & Support", 'wordpress-social-login') , "enabled" => true,  "visible" => true   , "pull-right" => true , "welcome-panel" => false ),

	"advanced"     => array( "label" => __("Advanced"      , 'wordpress-social-login') , "enabled" => true,  "visible" => false  , "pull-right" => true , "welcome-panel" => false ),
);

// --------------------------------------------------------------------
