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

require_once( "Hybrid/Logger.php" );
require_once( "Hybrid/Storage.php" );
require_once( "Hybrid/Error.php" );
require_once( "Hybrid/Auth.php" );
require_once( "Hybrid/Exception.php" ); 
require_once( "Hybrid/Endpoint.php" ); 

//-

require_once( "WSL_Endpoint.php" );

WSL_Hybrid_Endpoint::process();
