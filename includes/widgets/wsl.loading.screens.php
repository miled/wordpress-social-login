<?php
/*!
* WordPress Social Login
*
* http://miled.github.io/wordpress-social-login/ | https://github.com/miled/wordpress-social-login
*  (c) 2011-2015 Mohamed Mrassi and contributors | http://wordpress.org/plugins/wordpress-social-login/
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
	function wsl_render_redirect_to_provider_loading_screen( $provider )
	{
		$assets_base_url  = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . 'assets/img/';
?>
<!DOCTYPE html>
	<head>
		<meta name="robots" content="NOINDEX, NOFOLLOW">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title><?php _wsl_e("Redirecting...", 'wordpress-social-login') ?> - <?php bloginfo('name'); ?></title>
		<style type="text/css">
			html {
				background: #f1f1f1;
			}
			body {
				background: #fff;
				color: #444;
				font-family: "Open Sans", sans-serif;
				margin: 2em auto;
				padding: 1em 2em;
				max-width: 700px;
				-webkit-box-shadow: 0 1px 3px rgba(0,0,0,0.13);
				box-shadow: 0 1px 3px rgba(0,0,0,0.13);
			}
			#loading-screen {
				margin-top: 50px;
			}
			#loading-screen div{
				line-height: 20px;
				padding: 8px;
				background-color: #f2f2f2;
				border: 1px solid #ccc;
				padding: 10px;
				text-align:center;
				box-shadow: 0 1px 3px rgba(0,0,0,0.13);
				margin-top:25px;
			}
		</style>
		<script>
			function init()
			{
				window.location.replace( window.location.href + "&redirect_to_provider=true" );
			}
		</script>
	</head>
	<body id="loading-screen" onload="init();">
		<table width="100%" border="0">
			<tr>
				<td align="center"><img src="<?php echo $assets_base_url ?>loading.gif" /></td>
			</tr>
			<tr>
				<td align="center">
					<div>
						<?php echo sprintf( _wsl__( "Contacting <b>%s</b>, please wait...", 'wordpress-social-login'), _wsl__( ucfirst( $provider ), 'wordpress-social-login') )  ?>
					</div>
				</td>
			</tr>
		</table>
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
		* If Authentication displayis undefined or eq Popup ($wsl_settings_use_popup==1)
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
		<title><?php _wsl_e("Redirecting...", 'wordpress-social-login') ?> - <?php bloginfo('name'); ?></title>
		<style type="text/css">
			html {
				background: #f1f1f1;
			}
			body {
				background: #fff;
				color: #444;
				font-family: "Open Sans", sans-serif;
				margin: 2em auto;
				padding: 1em 2em;
				max-width: 700px;
				-webkit-box-shadow: 0 1px 3px rgba(0,0,0,0.13);
				box-shadow: 0 1px 3px rgba(0,0,0,0.13);
			}
			#loading-screen {
				margin-top: 50px;
			}
			#loading-screen div{
				line-height: 20px;
				padding: 8px;
				background-color: #f2f2f2;
				border: 1px solid #ccc;
				padding: 10px;
				text-align:center;
				box-shadow: 0 1px 3px rgba(0,0,0,0.13);
				margin-top:25px;
			}
		</style>
		<script>
			function init()
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
	<body id="loading-screen" onload="init();">
		<table width="100%" border="0">
			<tr>
				<td align="center"><img src="<?php echo $assets_base_url ?>loading.gif" /></td>
			</tr>
			<tr>
				<td align="center">
					<div>
						<?php echo _wsl_e( "Processing, please wait...", 'wordpress-social-login');  ?>
					</div>
				</td>
			</tr>
		</table>

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
