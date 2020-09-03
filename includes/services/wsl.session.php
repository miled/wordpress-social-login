<?php
/*!
* WordPress Social Login
*
* https://miled.github.io/wordpress-social-login/ | https://github.com/miled/wordpress-social-login
*   (c) 2011-2020 Mohamed Mrassi and contributors | https://wordpress.org/plugins/wordpress-social-login/
*/

// --------------------------------------------------------------------

/**
* Attempts to initialize a PHP session when needed
*/
function wsl_init_php_session()
{
	// > check for wsl actions/page
	$action = isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : null;
	$page = isset( $_GET['page'] ) ? $_GET['page'] : null;

    if(
        ! in_array( $action, array( "wordpress_social_authenticate", "wordpress_social_profile_completion", "wordpress_social_account_linking", "wordpress_social_authenticated" ) )
        && ! in_array( $page, array( "wordpress-social-login" ) )
    )
	{
		return false;
	}

    if ( headers_sent() )
    {
        wp_die( __( "HTTP headers already sent to browser and WSL won't be able to start/resume PHP session.", 'wordpress-social-login' ) );
    }

    if ( ! session_id() && ! defined( 'DOING_CRON' ) )
    {
        session_start();
    }

    if( session_id() )
    {
        wsl_init_php_session_storage();
    }
}

// --------------------------------------------------------------------

function wsl_init_php_session_storage()
{
    global $WORDPRESS_SOCIAL_LOGIN_VERSION;

    $_SESSION["wsl::plugin"] = "WordPress Social Login " . $WORDPRESS_SOCIAL_LOGIN_VERSION;

    if( defined( 'ABSPATH' ) )
    {
        $_SESSION['wsl:consts:ABSPATH'] = ABSPATH;
    }
}

// --------------------------------------------------------------------

function wsl_set_provider_config_in_session_storage($provider, $config)
{
	$provider = strtolower($provider);

	$_SESSION['wsl:' . $provider . ':config'] = (array) $config;
}

// --------------------------------------------------------------------

function wsl_get_provider_config_from_session_storage($provider)
{
	$provider = strtolower($provider);

    if(isset($_SESSION['wsl:' . $provider . ':config']))
    {
        return (array) $_SESSION['wsl:' . $provider . ':config'];
    }
}

// --------------------------------------------------------------------
