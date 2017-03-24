<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*    (c) 2011-2014 Mohamed Mrassi and contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

// ------------------------------------------------------------------------
//	Generic WSL End Point
// ------------------------------------------------------------------------

require_once "autoload.php";
require_once "session.php";

$config = get_provider_config_from_session_storage($provider);
$params = get_provider_params_from_session_storage($provider);

$hybridauth = new \Hybridauth\Hybridauth($config, $params);

try {
    $adapter = $hybridauth->authenticate( $provider );

    $url = $config['current_page'];
	
	\Hybridauth\HttpClient\Util::redirect($url);
}
catch( Exception $e ){
    echo $e->getMessage();
}
