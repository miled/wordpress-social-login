<?php
/*!
* WordPress Social Login
*
* https://miled.github.io/wordpress-social-login/ | https://github.com/miled/wordpress-social-login
*   (c) 2011-2018 Mohamed Mrassi and contributors | https://wordpress.org/plugins/wordpress-social-login/
*/

// ------------------------------------------------------------------------
//	Generic WSL End Point
// ------------------------------------------------------------------------
//  Note: The way we handle errors is a bit messy and should be reworked
// ------------------------------------------------------------------------

session_start();

require_once '../includes/services/wsl.session.php';
require_once 'library/src/autoload.php';

// Fix a strange behavior when some provider call back ha endpoint
// with /index.php?hauth.done={provider}?{args}...
if (isset($_SERVER["QUERY_STRING"]) && strrpos($_SERVER["QUERY_STRING"], '?')) {
    $url = Hybridauth\HttpClient\Util::getCurrentUrl(true);

    $fixed_url = $url;
    $fixed_url = str_ireplace('/?' , '###', $fixed_url);
    $fixed_url = str_ireplace('?'  , '&'  , $fixed_url);
    $fixed_url = str_ireplace('###', '/?' , $fixed_url);

    Hybridauth\HttpClient\Util::redirect($fixed_url);
}

$provider_id     = $_REQUEST['hauth_done']; 
$provider_config = wsl_get_provider_config_from_session_storage($provider_id);
$callback_url    = $provider_config['current_page'];
$wp_abspath      = $_SESSION['wsl:consts:ABSPATH'];

try { 
    $hybridauth = new Hybridauth\Hybridauth($provider_config);

    $adapter = $hybridauth->authenticate($provider_id);

    Hybridauth\HttpClient\Util::redirect($callback_url);
}
catch( Exception $e ){
    // Attempt to Load WordPress Core
    $wp_load_path = $wp_abspath . '/wp-load.php';

    if( file_exists( $wp_load_path )){
        define( 'WP_USE_THEMES', false );
        include_once $wp_load_path;
    }

    // Well then
    else{
        function wsl_process_login_render_error_page($exception){
            echo $exception->getMessage(); 
            die();
        }
    }

    return wsl_process_login_render_error_page($e);
}
