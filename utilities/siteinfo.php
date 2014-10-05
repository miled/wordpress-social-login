<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*    (c) 2011-2014 Mohamed Mrassi and contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

/**
* Current WP Site Info
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
?>
<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Website Information</title>
<head>  
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
<h3>Website Information</h3>
 
<p>
<b>Important</b>: 
</p>
<ul style="padding-left:15px;">
<li>Please include this information when posting support requests. It will help me immensely to better understand any issues.</li>
<li>These information should be communicated to the plugin developer privately via email : Miled &lt;<a href="mailto:hybridauth@gmail.com">hybridauth@gmail.com</a>&gt;</li>
<li>Make sure to check out <b>WSL</b> <a href="http://hybridauth.sourceforge.net/wsl/faq.html" target="_blank"><b>frequently asked questions</b></a>.</li> 
</ul>

<textarea readonly="readonly" style="height: 500px;overflow: auto;white-space: pre;width: 790px;">
SITE_URL:                 <?php echo site_url() . "\n"; ?>
PLUGIN_URL:               <?php echo plugins_url() . "\n"; ?>

HTTP_HOST:                <?php echo $_SERVER['HTTP_HOST'] . "\n"; ?>
SERVER_PORT:              <?php echo isset( $_SERVER['SERVER_PORT'] ) ? 'On (' . $_SERVER['SERVER_PORT'] . ')' : 'N/A'; echo "\n"; ?>
HTTP_X_FORWARDED_PROTO:   <?php echo isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) ? 'On (' . $_SERVER['HTTP_X_FORWARDED_PROTO'] . ')' : 'N/A'; echo "\n"; ?>

MULTI-SITE:               <?php echo is_multisite() ? 'Yes' . "\n" : 'No' . "\n" ?>

WSL VERSION:              <?php echo $WORDPRESS_SOCIAL_LOGIN_VERSION . "\n"; ?>
WORDPRESS VERSION:        <?php echo get_bloginfo( 'version' ) . "\n"; ?>

PHP VERSION:              <?php echo PHP_VERSION . "\n"; ?>
MYSQL VERSION:            <?php echo mysql_get_server_info() . "\n"; ?>
WEB SERVER INFO:          <?php echo $_SERVER['SERVER_SOFTWARE'] . "\n"; ?>

SESSION:                  <?php echo isset( $_SESSION ) ? 'Enabled' : 'Disabled'; echo "\n"; ?>
SESSION:WSL               <?php echo $_SESSION["wsl::plugin"]; echo "\n"; ?>
SESSION:NAME:             <?php echo esc_html( ini_get( 'session.name' ) ); echo "\n"; ?>

COOKIE PATH:              <?php echo esc_html( ini_get( 'session.cookie_path' ) ); echo "\n"; ?>
SAVE PATH:                <?php echo esc_html( ini_get( 'session.save_path' ) ); echo "\n"; ?>
USE COOKIES:              <?php echo ini_get( 'session.use_cookies' ) ? 'On' : 'Off'; echo "\n"; ?>
USE ONLY COOKIES:         <?php echo ini_get( 'session.use_only_cookies' ) ? 'On' : 'Off'; echo "\n"; ?>

PHP/CURL:                 <?php echo function_exists( 'curl_init'   ) ? "Supported" : "Not supported"; echo "\n"; ?>
<?php if( function_exists( 'curl_init' ) ): ?>
PHP/CURL/VER:             <?php $v = curl_version(); echo $v['version']; echo "\n"; ?>
PHP/CURL/SSL:             <?php $v = curl_version(); echo $v['ssl_version']; echo "\n"; ?><?php endif; ?>
PHP/FSOCKOPEN:            <?php echo function_exists( 'fsockopen'   ) ? "Supported" : "Not supported"; echo "\n"; ?>
PHP/JSON:                 <?php echo function_exists( 'json_decode' ) ? "Supported" : "Not supported"; echo "\n"; ?>

ACTIVE PLUGINS:

<?php  
if( function_exists("get_plugins") ):
	$plugins = get_plugins();
	foreach ( $plugins as $plugin_path => $plugin ): 
		echo $plugin['Name']; echo $plugin['Name']; ?>: <?php echo $plugin['Version'] ."\n"; 
	endforeach;
else:
	$active_plugins = get_option( 'active_plugins', array() );
	foreach ( $active_plugins as $plugin ): 
		echo $plugin ."\n"; 
	endforeach;
endif; ?>

CURRENT THEME:

<?php
if ( get_bloginfo( 'version' ) < '3.4' ) {
	$theme_data = get_theme_data( get_stylesheet_directory() . '/style.css' );
	echo $theme_data['Name'] . ': ' . $theme_data['Version'];
} else {
	$theme_data = wp_get_theme();
	echo $theme_data->Name . ': ' . $theme_data->Version;
}
?>


# EOF</textarea> 
</div> 
</body>
</html> 
