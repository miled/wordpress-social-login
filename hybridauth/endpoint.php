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
//  Note: The way we handle errors is a bit messy and should be reworked
// ------------------------------------------------------------------------

require_once "autoload.php";
require_once "session.php";

$provider_id     = filter_input(INPUT_GET, 'hauth_done', FILTER_SANITIZE_SPECIAL_CHARS); 
$provider_config = get_provider_config_from_session_storage($provider_id);
$callback_url    = $provider_config['current_page'];
$wp_abspath      = $_SESSION['wsl:const:ABSPATH'];

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
