<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*    (c) 2011-2014 Mohamed Mrassi and contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

/**
* WordPress Social Login Requirements Test
*
* ToDo: need to move this inside WSL admin UIs
*/

// --------------------------------------------------------------------

// load wp-load.php
require_once( dirname( dirname( dirname( dirname( __FILE__ )))) . '/../wp-load.php' ); 

// only logged in users
if ( ! is_user_logged_in() ) {
	wp_die( 'You do not have sufficient permissions to access this page.' );
}

// only display for admin
if ( ! current_user_can('manage_options') ) {
	wp_die( 'You do not have sufficient permissions to access this page.' );
}

if ( ! session_id() ){
	session_start();
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
<head>
	<meta name="robots" content="NOINDEX, NOFOLLOW">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Requirements Test - WordPress Social Login</title>
	<style>
	html {
		background: #f9f9f9;
	}
	#wsl {
		background: #fff;
		color: #333;
		font-family: sans-serif;
		margin: 2em auto;
		padding: 1em 2em;
		-webkit-border-radius: 3px;
		border-radius: 3px;
		border: 1px solid #dfdfdf;
		max-width: 800px;
		font-size: 14px;
	}  
	</style>
</head>
<body>
	<div id="wsl">
		<h3>WordPress Social Login - Requirements Test</h3>

		<p>
			<b>Important</b>: 
		</p>	
		<ul style="padding-left:15px;">
			<li>Please include your <a href="siteinfo.php" target="_blank"><b>Website Information</b></a> when posting support requests.</li>
			<li>Make sure to check out <b>WSL</b> <a href="http://hybridauth.sourceforge.net/wsl/faq.html" target="_blank"><b>frequently asked questions</b></a>.</li> 
		</ul>
		
		<hr />

		<h5>1. PHP Version</h5> 
		<p>
		<?php 
			if ( version_compare( PHP_VERSION, '5.2.0', '>=' ) ){
				echo "<b style='color:green;'>OK!</b><br />PHP >= 5.2.0 installed.";
			}
			else{ 
				echo "<b style='color:red;'>FAIL!</b><br />PHP >= 5.2.0 not installed.";
			}
		?>
			</p>

			<h5>2. PHP Sessions</h5> 
			<p>
		<?php
			if ( isset( $_SESSION["wsl::plugin"] ) ){
				echo "<b style='color:green;'>OK!</b><br />PHP Sessions are working correctly for {$_SESSION["wsl::plugin"]} to work."; 
			}
			else{
				?>
				<b style='color:red;'>FAIL!</b><br />PHP Sessions are not working correctly or this page has been accessed directly.
		 
				
				<div style="background-color: #FFFFE0;border:1px solid #E6DB55; border-radius: 3px;padding:5px;font-size: 12px;">  
					Most likely an issue with PHP SESSION. The plugin has been made to work with PHP's default SESSION handling (sometimes this occur when the php session is disabled, renamed or when having permissions issues).
					<br /><br />
					If you are using a reverse proxy like Varnish it is possible that WordPress's default user cookies are being stripped. If this is the case, please review your VCL file. You may need to configure this file to allow the needed cookies.
					<br /><br />
					This problem has also been encountered with WP Engine. 
					
					<hr />
				
					If you see an error <strong>"Warning: session_start..."</strong> on the top of this page or in the Error log file, then 

					there is a problem with your PHP server that will prevent this plugin from working with PHP sessions. Sometimes PHP session do not work because of a file permissions problem. The solution is to make a trouble ticket with your web host.
					
					<br />
					<br />
					Alternatively, you can create the sessions folder in your root directory, then add <strong>session_save_path('/path/to/writable/folder')</strong> at the top of the following files:
					
					<br />
					- wp-config.php<br />
					- wp-content/plugins/wordpress-social-login/wp-social-login.php<br />
					- wp-content/plugins/wordpress-social-login/authenticate.php<br />
					- wp-content/plugins/wordpress-social-login/hybridauth/index.php<br />
				</div>
				<?php
			}
		?>
			</p>

			<h5>3. cURL Extension</h5> 
			<p>
		<?php 
			if ( function_exists('curl_init') ) {
				echo "<b style='color:green;'>OK!</b><br />PHP cURL extension installed. [http://www.php.net/manual/en/intro.curl.php]";
		?>
			</p>

			<h5>4. cURL / SSL </h5> 
			<p>
		<?php 
				$curl_version = curl_version();

				if ( $curl_version['features'] & CURL_VERSION_SSL ) {
					echo "<b style='color:green;'>OK!</b><br />PHP cURL/SSL Supported. [http://www.php.net/manual/en/intro.curl.php]";
				}
				else{ 
					echo "<b style='color:red;'>FAIL!</b><br />PHP cURL/SSL Not supported. [http://www.php.net/manual/en/intro.curl.php]";
				}
			}
			else{ 
				echo "<b style='color:red;'>FAIL!</b><br />PHP cURL extension not installed. [http://www.php.net/manual/en/intro.curl.php]";
			}
		?>
		</p>

		<h5>5. JSON Extension</h5> 
		<p>
		<?php 
			if ( function_exists('json_decode') ) {
				echo "<b style='color:green;'>OK!</b><br />PHP JSON extension installed. [http://php.net/manual/en/book.json.php]";
			}
			else{ 
				echo "<b style='color:red;'>FAIL!</b><br />PHP JSON extension is disabled. [http://php.net/manual/en/book.json.php]";
			}
		?>
			</p>

			<h5>6. PHP Register Globals</h5> 
			<p>
		<?php 
			if( ini_get('register_globals') ) { 
				?>
					<b style='color:red;'>FAIL!</b><br />PHP Register Globals are On!
				
					<div style="background-color: #FFFFE0;border:1px solid #E6DB55; border-radius: 3px;padding:5px;font-size: 12px;">  
						<b>What do I do if <b>Register Globals</b> are <b>On</b>?</b>
						<p> 
							If <strong>"PHP Register Globals"</strong> are <b>On</b>, then there is a problem with your PHP server that will prevent this plugin from working properly and will result on an enfinite loop on WSL authentication page. Also, <a href="http://php.net/manual/en/security.globals.php" target="_blank"><b>Register Globals</b> has been DEPRECATED as of PHP 5.3.0 and REMOVED as of PHP 5.4.0.</a>
							<br />
							<br />
							The solution is to make a trouble ticket with your web host to disable it,<br />
							Or, if you have a dedicated server and you know what are you doing then edit php.ini file and turn it Off :
							<br />
							<br />
							<span style="border:1px solid #E6DB55;padding:5px;"> register_globals = Off</span>
						</p>
					</div>
				<?php
			}
			else{ 
				echo "<b style='color:green;'>OK!</b><br />REGISTER_GLOBALS = OFF. [http://php.net/manual/en/security.globals.php]";
			} 
		?>
		</p>  
	</div>
</body>
</html>