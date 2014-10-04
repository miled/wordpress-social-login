<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*    (c) 2011-2014 Mohamed Mrassi and contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

/**
* Generate WSL notices end errors pages.
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------

/**
* Display a simple notice to the user and kill WordPress execution.
*
* This function is mainly used by bouncer
*
* Note: 
*   In case you want to customize the content generated, you may define this function in 'wordpress-social-login-custom.php'
*   Just make sure the script DIES at the end. 
*
*   The $message to display for users is passed as a parameter.
*/
if( ! function_exists( 'wsl_render_notice_page' ) )
{
	function wsl_render_notice_page( $message )
	{
		$assets_base_url = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . '/assets/img/'; 
?>
<!DOCTYPE html>
	<head>
		<meta name="robots" content="NOINDEX, NOFOLLOW">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title><?php bloginfo('name'); ?></title>
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
			h1 {
				border-bottom: 1px solid #dadada;
				clear: both;
				color: #666;
				font: 24px "Open Sans", sans-serif;
				margin: 30px 0 0 0;
				padding: 0;
				padding-bottom: 7px;
			}
			#notice-page {
				margin-top: 50px;
			}
			#notice-page p {
				font-size: 14px;
				line-height: 1.5;
				margin: 25px 0 20px;
			}
			#notice-page code {
				font-family: Consolas, Monaco, monospace;
			}
			.notice-message {
				line-height: 26px;
				padding: 8px;
				background-color: #f2f2f2;
				border: 1px solid #ccc;
				padding: 10px;
				text-align:center;
				box-shadow: 0 1px 3px rgba(0,0,0,0.13);
				margin-top:25px;
			}
			ul li {
				margin-bottom: 10px;
				font-size: 14px ;
			}
			a {
				color: #21759B;
				text-decoration: none;
			}
			a:hover {
				color: #D54E21;
			}
		</style>
	<head>  
	<body id="notice-page"> 
		<table width="100%" border="0">
			<tr>
				<td align="center"><img src="<?php echo $assets_base_url ?>alert.png" /></td>
			</tr>
			<tr>
				<td align="center">
					<div class="notice-message">
						<?php echo nl2br( $message ); ?> 
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

// --------------------------------------------------------------------

/**
* Display an error page to the user and kill WordPress execution.
* 
* This function differ than wsl_render_notice_page as it have some extra parameters and also should allow debugging
*
* This function is used when WSL fails to authenticated a user with social networks
* 
* Note: 
*   In case you want to customize the content generated, you may define this function in 'wordpress-social-login-custom.php'
*   Just make sure the script DIES at the end. 
*
*   The $message to display for users is passed as a parameter and is required.
*/
if( ! function_exists( 'wsl_render_error_page' ) )
{
	function wsl_render_error_page( $message, $notes = null, $php_exception = null, $php_extras_vars_to_debug = array() )
	{
		$assets_base_url = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . '/assets/img/'; 
?>
<!DOCTYPE html>
	<head>
		<meta name="robots" content="NOINDEX, NOFOLLOW">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title><?php bloginfo('name'); ?> - <?php _wsl_e("Oops! We ran into an issue", 'wordpress-social-login') ?>.</title>
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
			h4 {
				color: #666;
				font: 20px "Open Sans", sans-serif;
				margin: 0;
				padding: 0;
				padding-bottom: 7px;
			}
			#error-page {
				margin-top: 50px;
			}
			#error-page p {
				font-size: 14px;
				line-height: 1.5;
				margin: 15px 0;
				line-height: 25px; 
				padding: 10px;
				text-align:left;
			}
			#error-page pre {
				max-width: 680px;
				overflow: scroll;
				padding: 5px;
				background: none repeat scroll 0 0 #F5F5F5;
				border-radius:3px;
				font-family: Consolas, Monaco, monospace;
			}
			.error-message {
				line-height: 26px;
				padding: 8px;
				background-color: #f2f2f2;
				border: 1px solid #ccc;
				padding: 10px;
				text-align:center;
				box-shadow: 0 1px 3px rgba(0,0,0,0.13);
				margin-top:25px;
			}
			a {
				color: #21759B;
				text-decoration: none;
			}
			a:hover {
				color: #D54E21;
			}
		</style>
	</head>  
	<body id="error-page">
		<table width="100%" border="0">
			<tr>
				<td align="center"><img src="<?php echo $assets_base_url ?>alert.png" /></td>
			</tr>

			<tr>
				<td align="center"><h4><?php _wsl_e("Oops! We ran into an issue", 'wordpress-social-login') ?>.</h4></td> 
			</tr>

			<tr>
				<td>
					<div class="error-message">
						<?php echo $message ; ?> 
					</div>

					<?php
						// any hint or extra note?
						if( $notes ) {
							?>
								<p><?php _wsl_e( $notes, 'wordpress-social-login'); ?></p>
							<?php
						}
					?>
				</td> 
			</tr> 
		</table>

		<?php 
			// Development mode on?
			if( get_option( 'wsl_settings_development_mode_enabled' ) ){
				wsl_render_error_page_debug_section( $php_exception, $php_extras_vars_to_debug );
			}
		?>
	</body>
</html> 
<?php
		die();
	}
}

// --------------------------------------------------------------------

/**
* Display an extra debugging section to the error page, in case Mode Dev is on
*/
function wsl_render_error_page_debug_section( $php_exception, $php_extras_vars_to_debug )
{
?>
<hr />

<h3>Backtrace</h3>
<pre><?php debug_print_backtrace(); ?></pre>

<h3>PHP Exception</h3>
<pre><?php print_r( $php_exception ) ?></pre> 

<?php
	// try to provide the previous if any
	// Exception::getPrevious (PHP 5 >= 5.3.0) http://php.net/manual/en/exception.getprevious.php
	if ( version_compare( PHP_VERSION, '5.3.0', '>=' ) ) { 
		if ( $php_exception->getPrevious() ) {
		?>
			<h3>Previous Exception</h3>
			<pre><?php print_r( $php_exception->getPrevious() ) ?></pre> 
		<?php
		}
	}						
?>

<h3>Extras vars to debug</h3>
<pre><?php print_r( $php_extras_vars_to_debug ) ?></pre>

<?php wsl_display_dev_mode_debugging_area(); ?>

<br />

<small>
	<?php _wsl_e("<strong>Note:</strong> This debugging area can be disabled from the plugin settings by setting <b>Development mode</b> to <b>Disabled</b>", 'wordpress-social-login'); ?>.
</small>
<?php
}

// --------------------------------------------------------------------
