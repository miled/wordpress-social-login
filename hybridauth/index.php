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

/**
* If for whatever reason you want to debug apis call made by hybridauth during the auth process, you can add the block of code below.
*
* <code>
*    include_once( '/path/to/file/wp-load.php' );
*    define( 'WORDPRESS_SOCIAL_LOGIN_DEBUG_API_CALLS', true );
*    add_action( 'wsl_log_provider_api_call', 'wsl_watchdog_wsl_log_provider_api_call', 10, 8 );
*    do_action( 'wsl_log_provider_api_call', 'ENDPOINT', 'Hybridauth://endpoint', null, null, null, null, $_SERVER["QUERY_STRING"] );
* </code>
*/

//- Re-parse the QUERY_STRING for custom endpoints.
if( defined( 'WORDPRESS_SOCIAL_LOGIN_CUSTOM_ENDPOINT' ) && ! isset( $_REQUEST['hauth_start'] ) ) 
{
	$_SERVER["QUERY_STRING"] = 'hauth_done=' . WORDPRESS_SOCIAL_LOGIN_CUSTOM_ENDPOINT . '&' . str_ireplace( '?', '&', $_SERVER["QUERY_STRING"] );

	parse_str( $_SERVER["QUERY_STRING"], $_REQUEST );
}

//- Hybridauth required includes
require_once( "Hybrid/Storage.php"   );
require_once( "Hybrid/Error.php"     );
require_once( "Hybrid/Auth.php"      );
require_once( "Hybrid/Exception.php" );
require_once( "Hybrid/Endpoint.php"  );


//- Custom WSL endpoint class
require_once( "endpoints/WSL_Endpoint.php" );


//- Entry point to the End point 
WSL_Hybrid_Endpoint::process();
