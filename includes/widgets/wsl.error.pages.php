<?php
/*!
* WordPress Social Login
*
* http://miled.github.io/wordpress-social-login/ | https://github.com/miled/wordpress-social-login
*  (c) 2011-2015 Mohamed Mrassi and contributors | http://wordpress.org/plugins/wordpress-social-login/
*/

/**
* Generate WSL notices end errors pages.
*/

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------

/**
* Display a simple notice to the user and kill WordPress execution
*
* This function is mainly used by bouncer
*
* Note:
*   In case you want to customize the content generated, you may redefine this function
*   Just make sure the script DIES at the end.
*
*   The $message to display for users is passed as a parameter.
*/
if( ! function_exists( 'wsl_render_notice_page' ) )
{
	function wsl_render_notice_page( $message )
	{
		$assets_base_url = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . 'assets/img/';
?>
<!DOCTYPE html>
	<head>
		<meta name="robots" content="NOINDEX, NOFOLLOW">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
		<title><?php bloginfo('name'); ?></title>
		<style type="text/css">
			body {
				background: #f3f6f8;
				color: #324155;
				font-family: -apple-system,BlinkMacSystemFont,"Segoe UI","Roboto","Oxygen-Sans","Ubuntu","Cantarell","Helvetica Neue",sans-serif;
				font-size: 16px;
				line-height: 1.6;
			}
			div {
				position: absolute;
				top: 50%;
				left: 50%;
				transform: translate(-50%, -50%);
			}
			img {
				display: block;
				margin: 0 auto;
			}
			h1 {
				font-size: 1.4em;
				font-family: -apple-system,BlinkMacSystemFont,"Segoe UI","Roboto","Oxygen-Sans","Ubuntu","Cantarell","Helvetica Neue",sans-serif;
				font-weight: 400;
				line-height: 1.6em;
				margin: 1em 0 .5em;
				transition: all .5s ease;
                text-align: center;
			}
            p{
                text-align: center;
            }
			a {
				text-decoration: none;
			}
		</style>
	<head>
	<body>
		<div>
			<img src="<?php echo $assets_base_url ?>error-52.png" />

            <p><?php echo nl2br( $message ); ?></p>
		</div>
	</body>
</html>
<?php
		die();
	}
}

// --------------------------------------------------------------------

/**
* Display an error page to the user and kill WordPress execution
*
* This function differ than wsl_render_notice_page as it have some extra parameters and also should allow debugging
*
* This function is used when WSL fails to authenticated a user with social networks
*
* Note:
*   In case you want to customize the content generated, you may redefine this function
*   Just make sure the script DIES at the end.
*
*   The $message to display for users is passed as a parameter and it's required.
*/
if( ! function_exists( 'wsl_render_error_page' ) )
{
	function wsl_render_error_page( $message, $notes = null, $provider = null, $api_error = null, $php_exception = null )
	{
		$assets_base_url = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . 'assets/img/';
?>
<!DOCTYPE html>
	<head>
		<meta name="robots" content="NOINDEX, NOFOLLOW">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
		<title><?php bloginfo('name'); ?> - <?php _wsl_e("Oops! We ran into an issue", 'wordpress-social-login') ?>.</title>
		<style type="text/css">
			body {
				background: #f3f6f8;
				color: #324155;
				font-family: -apple-system,BlinkMacSystemFont,"Segoe UI","Roboto","Oxygen-Sans","Ubuntu","Cantarell","Helvetica Neue",sans-serif;
				font-size: 16px;
				line-height: 1.6;
			}
			div {
				position: absolute;
				top: 50%;
				left: 50%;
				transform: translate(-50%, -50%);
			}
			img {
				display: block;
				margin: 0 auto;
			}
			h1 {
				font-size: 1.4em;
				font-family: -apple-system,BlinkMacSystemFont,"Segoe UI","Roboto","Oxygen-Sans","Ubuntu","Cantarell","Helvetica Neue",sans-serif;
				font-weight: 400;
				line-height: 1.6em;
				margin: 1em 0 .5em;
				transition: all .5s ease;
                text-align: center;
			}
            p{
                text-align: center;
            }
			a {
				text-decoration: none;
			}
		</style>
	</head>
	<body>
		<div>
			<img src="<?php echo $assets_base_url ?>error-60.png" />

			<h1><?php _wsl_e("We're unable to complete your request", 'wordpress-social-login') ?>!</h1>

            <p><?php echo htmlentities($api_error); ?></p>

            <?php
                // any hint or extra note?
                if( $notes )
                {
                    ?>
                        <p><?php _wsl_e( $notes, 'wordpress-social-login'); ?></p>
                    <?php
                }
            ?>

            <p><a href="<?php echo home_url(); ?>">&xlarr; <?php _wsl_e("Back to home", 'wordpress-social-login') ?></a></p>
		</div>

		<?php
			// Development mode on?
			if( get_option( 'wsl_settings_development_mode_enabled' ) )
			{
                ?><style>div {position: unset; transform: none;}</style><?php

                wsl_render_error_page_debug_section( $php_exception );
			}
		?>
	</body>
</html>
<?php
	# keep these 2 LOC
		do_action( 'wsl_clear_user_php_session' );

		die();
	}
}

// --------------------------------------------------------------------

/**
* Display an extra debugging section to the error page, in case Mode Dev is on
*/
function wsl_render_error_page_debug_section( $php_exception = null )
{
?>
<hr />

<?php wsl_display_dev_mode_debugging_area(); ?>

<h3>Backtrace</h3>
<pre><?php echo wsl_generate_backtrace(); ?></pre>

<h3>Exception</h3>
<pre><?php print_r( $php_exception ); ?></pre>

<br />

<small>
	<?php _wsl_e("<strong>Note:</strong> This debugging area can be disabled from the plugin settings by setting <b>Development mode</b> to <b>Disabled</b>", 'wordpress-social-login'); ?>.
</small>
<?php
}

// --------------------------------------------------------------------
