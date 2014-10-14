<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*    (c) 2011-2014 Mohamed Mrassi and contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

// ------------------------------------------------------------------------
//	WSL End Point
// ------------------------------------------------------------------------

//-

$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );

if( file_exists( $parse_uri[0] . 'wp-load.php' ) )
{
	include_once( $parse_uri[0] . 'wp-load.php' );

	if( defined( 'WORDPRESS_SOCIAL_LOGIN_DEBUG_API_CALLS' ) )
	{
		do_action( 'wsl_log_provider_api_call', 'Hybridauth://endpoint', null, null, null, null, $_SERVER["QUERY_STRING"], null, __FILE__, __LINE__, debug_backtrace () );
	}
}

if( defined( 'WORDPRESS_SOCIAL_LOGIN_CUSTOM_ENDPOINT' ) && ! isset( $_REQUEST['hauth_start'] ) ) 
{
	$_SERVER["QUERY_STRING"] = 'hauth_done=' . WORDPRESS_SOCIAL_LOGIN_CUSTOM_ENDPOINT . '&' . str_ireplace( '?', '&', $_SERVER["QUERY_STRING"] );

	parse_str( $_SERVER["QUERY_STRING"], $_REQUEST );
}

//-

require_once( "Hybrid/Logger.php"    );
require_once( "Hybrid/Storage.php"   );
require_once( "Hybrid/Error.php"     );
require_once( "Hybrid/Auth.php"      );
require_once( "Hybrid/Exception.php" );
require_once( "Hybrid/Endpoint.php"  );

//-

require_once( "WSL_Endpoint.php" );

WSL_Hybrid_Endpoint::process();
