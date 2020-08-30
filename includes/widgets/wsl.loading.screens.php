<?php
/*!
* WordPress Social Login
*
* https://miled.github.io/wordpress-social-login/ | https://github.com/miled/wordpress-social-login
*   (c) 2011-2020 Mohamed Mrassi and contributors | https://wordpress.org/plugins/wordpress-social-login/
*/

/**
* Generate WSL loading screens.
*/

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------

/**
* Display a loading screen while the WSL is redirecting the user to a given provider for authentication
*
* Note:
*   In case you want to customize the content generated, you may redefine this function
*   This function should redirect to the current url PLUS '&redirect_to_provider=true', see javascript function init() defined bellow
*   And make sure the script DIES at the end.
*
*   The $provider name is passed as a parameter.
*/
if( ! function_exists( 'wsl_render_redirect_to_provider_loading_screen' ) )
{
	function wsl_render_redirect_to_provider_loading_screen( $provider_id )
	{
		$assets_base_url = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . 'assets/img/';

        $provider_name = wsl_get_provider_name_by_id( $provider_id );
?>
<!DOCTYPE html>
	<head>
		<meta name="robots" content="NOINDEX, NOFOLLOW">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
		<title><?php _wsl_e("Redirecting...", 'wordpress-social-login') ?> - <?php bloginfo('name'); ?></title>
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
				margin-bottom: 64px;
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
		</style>
		<script>
			function on_load()
			{
				setTimeout(function(){ window.location.replace( window.location.href + "&redirect_to_provider=true" ); }, 250);
			}
		</script>
	</head>
	<body onload="on_load();">
		<div>
			<img src="<?php echo $assets_base_url ?>spinner.gif" />

			<h1><?php echo sprintf( _wsl__( "Contacting <b>%s</b>, please wait...", 'wordpress-social-login'), _wsl__( $provider_name, 'wordpress-social-login') )  ?></h1>
		</div>
	</body>
</html>
<?php
		die();
	}
}

/**
* Display a loading screen after a user come back from provider and while WSL is procession his profile, contacts, etc.
*
* Note:
*   In case you want to customize the content generated, you may redefine this function
*   Just make sure the script DIES at the end.
*/
if( ! function_exists( 'wsl_render_return_from_provider_loading_screen' ) )
{
	function wsl_render_return_from_provider_loading_screen( $provider, $authenticated_url, $redirect_to, $wsl_settings_use_popup )
	{
		/*
		* If Authentication display is undefined or eq Popup ($wsl_settings_use_popup==1)
		* > create a from with javascript in parent window and submit it to wp-login.php ($authenticated_url)
		* > with action=wordpress_social_authenticated, then close popup
		*
		* If Authentication display eq In Page ($wsl_settings_use_popup==2)
		* > create a from in page then submit it to wp-login.php with action=wordpress_social_authenticated
		*/

		$assets_base_url  = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . 'assets/img/';
?>
<!DOCTYPE html>
	<head>
		<meta name="robots" content="NOINDEX, NOFOLLOW">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
		<title><?php _wsl_e("Redirecting...", 'wordpress-social-login') ?> - <?php bloginfo('name'); ?></title>
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
				margin-bottom: 64px;
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
			form {
				display: none;
			}
		</style>
		<script>
			function onLoad()
			{
				<?php
					if( $wsl_settings_use_popup == 1 || ! $wsl_settings_use_popup ){
						?>
							if( window.opener )
							{
								window.opener.wsl_wordpress_social_login({
									'action'   : 'wordpress_social_authenticated',
									'provider' : '<?php echo $provider ?>'
								});

								window.close();
							}
							else
							{
								document.loginform.submit();
							}
						<?php
					}
					elseif( $wsl_settings_use_popup == 2 ){
						?>
							document.loginform.submit();
						<?php
					}
				?>
			}
		</script>
	</head>
	<body onload="onLoad();">
		<div>
			<img src="<?php echo $assets_base_url ?>spinner.gif" />

			<h1><?php echo _wsl_e( "Processing, please wait...", 'wordpress-social-login');  ?></h1>
		</div>

		<form name="loginform" method="post" action="<?php echo $authenticated_url; ?>">
			<input type="hidden" id="redirect_to" name="redirect_to" value="<?php echo esc_url( $redirect_to ); ?>">
			<input type="hidden" id="provider" name="provider" value="<?php echo $provider ?>">
			<input type="hidden" id="action" name="action" value="wordpress_social_authenticated">
		</form>
	</body>
</html>
<?php
		die();
	}
}

// --------------------------------------------------------------------
