<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*    (c) 2011-2014 Mohamed Mrassi and contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

session_id() or session_start();

function set_provider_config_in_session_storage($provider, $config){
	$provider = strtolower($provider);

	$_SESSION['wsl:' . $provider . ':config'] = $config;
}

function get_provider_config_from_session_storage($provider){
	$provider = strtolower($provider);

	return $_SESSION['wsl:' . $provider . ':config'];
}

//

function set_provider_params_in_session_storage($provider, $params){
	$provider = strtolower($provider);

	$_SESSION['wsl:' . $provider . ':params'] = $config;
}

function get_provider_params_from_session_storage($provider){
	$provider = strtolower($provider);

	return $_SESSION['wsl:' . $provider . ':params'];
}
