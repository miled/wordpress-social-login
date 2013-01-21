<?php
	// Site Info 
	// borrowed from http://wordpress.org/extend/plugins/easy-digital-downloads/

	// load wp-load.php
	require_once( dirname( dirname( dirname( dirname( __FILE__ )))) . '/../wp-load.php' ); 

	// only logged in users
	if ( ! is_user_logged_in() ) {
		wsl_render_notices_pages( 'You do not have sufficient permissions to access this page.' );
	}

	// only display for admin
	if ( ! is_admin() ) {
		wsl_render_notices_pages( 'You do not have sufficient permissions to access this page.' );
	}

	session_start();
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
Please include this information when posting support requests (you know it works:  Ctrl + A, Ctrl + C then Ctrl + V into the email)
</p>
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

PHP MEMORY LIMIT:         <?php echo ini_get( 'memory_limit' ) . "\n"; ?>
PHP POST MAX SIZE:        <?php echo ini_get( 'post_max_size' ) . "\n"; ?>

SESSION:                  <?php echo isset( $_SESSION ) ? 'Enabled' : 'Disabled'; echo "\n"; ?>
SESSION:WSL               <?php echo $_SESSION["wsl::plugin"]; echo "\n"; ?>
SESSION NAME:             <?php echo esc_html( ini_get( 'session.name' ) ); echo "\n"; ?>
COOKIE PATH:              <?php echo esc_html( ini_get( 'session.cookie_path' ) ); echo "\n"; ?>
SAVE PATH:                <?php echo esc_html( ini_get( 'session.save_path' ) ); echo "\n"; ?>
USE COOKIES:              <?php echo ini_get( 'session.use_cookies' ) ? 'On' : 'Off'; echo "\n"; ?>
USE ONLY COOKIES:         <?php echo ini_get( 'session.use_only_cookies' ) ? 'On' : 'Off'; echo "\n"; ?>

DISPLAY ERRORS:           <?php echo ini_get( 'display_errors' ) ? 'On (' . ini_get( 'display_errors' ) . ')' : 'N/A'; echo "\n"; ?> 
CURL:                     <?php echo function_exists( 'curl_init'   ) ? "Supported" : "Not supported"; echo "\n"; ?>
FSOCKOPEN:                <?php echo function_exists( 'fsockopen'   ) ? "Supported" : "Not supported"; echo "\n"; ?>
JSON:                     <?php echo function_exists( 'json_decode' ) ? "Supported" : "Not supported"; echo "\n"; ?>

ACTIVE PLUGINS:

<?php
$plugins = get_plugins();
$active_plugins = get_option( 'active_plugins', array() );

foreach ( $plugins as $plugin_path => $plugin ): 
echo $plugin['Name']; echo $plugin['Name']; ?>: <?php echo $plugin['Version'] ."\n"; 
endforeach; ?>

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
<table width="100%" border="0">
  <tr>
	<td><?php echo $message ; ?></td> 
  </tr>  
</table> 
</div> 
</body>
</html> 
