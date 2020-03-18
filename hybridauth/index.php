<?php
/*!
* WordPress Social Login
*
* https://miled.github.io/wordpress-social-login/ | https://github.com/miled/wordpress-social-login
*   (c) 2011-2020 Mohamed Mrassi and contributors | https://wordpress.org/plugins/wordpress-social-login/
*/

// ------------------------------------------------------------------------
//	Handles (or rather attemps to handdle) LEGACY WSL End Points (of v2)
// ------------------------------------------------------------------------
// This is supposedly a temporary measure until users could migrate their 
// existing callbacks, and it's to be removed in a subsequent release.
// ------------------------------------------------------------------------

if ( headers_sent() ) {
    die("HTTP headers already sent to browser and WSL won't be able to start/resume PHP session.");
}

if ( ! session_start() ) {
    die("WSL couldn't start new PHP session.");
}

$ha_abspath = __DIR__ . '/../';

if( ! file_exists( $ha_abspath . 'library/src/autoload.php' ) ){
    die("WSL couldn't find required files.");
}

require_once $ha_abspath . 'library/src/autoload.php';

$hauth_done = filter_input(INPUT_GET, 'hauth_done', FILTER_SANITIZE_SPECIAL_CHARS);

if ( ! empty( $hauth_done ) ) {
	$url = Hybridauth\HttpClient\Util::getCurrentUrl(false);

	$url = str_ireplace( '/hybridauth/index.php', '/hybridauth/callbacks/', $url );

	$url .= strtolower( $hauth_done ) .  '.php?';

	unset( $_GET['hauth_done'] );

	foreach ($_GET as $key => $value) {
		$url .= $key . '=' . urlencode( $value ) . '&';
	}

	Hybridauth\HttpClient\Util::redirect($url);
}

$end_points_test = filter_input(INPUT_GET, 'end_points_test', FILTER_SANITIZE_SPECIAL_CHARS);

if ( empty( $end_points_test ) ) {
	header("HTTP/1.0 403 Forbidden");
	die;
}
