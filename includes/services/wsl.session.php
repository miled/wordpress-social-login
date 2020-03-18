<?php
/*!
* WordPress Social Login
*
* https://miled.github.io/wordpress-social-login/ | https://github.com/miled/wordpress-social-login
*   (c) 2011-2020 Mohamed Mrassi and contributors | https://wordpress.org/plugins/wordpress-social-login/
*/

// --------------------------------------------------------------------

if( session_id() )
{
    wsl_init_php_session_storage();
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
