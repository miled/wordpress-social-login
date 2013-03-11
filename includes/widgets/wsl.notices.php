<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*    (c) 2011-2013 Mohamed Mrassi and contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

/**
* Kill WordPress execution and display HTML message with error message. 
* similar to wp_die, but with (will have) more features or wp_die not defined
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------

function wsl_render_notices_pages( $message ) 
{
	// HOOKABLE: 
	do_action( 'wsl_hook_alter_render_notices_pages', $message );
?>
<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo get_bloginfo('name'); ?></title>
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
<!--
   wsl_render_notices_pages
   WordPress Social Login Plugin ( <?php echo $_SESSION["wsl::plugin"] ?> ) 
   http://wordpress.org/extend/plugins/wordpress-social-login/
-->
<div id="wsl">
<table width="100%" border="0">
  <tr>
	<td><?php echo $message ; ?></td> 
  </tr>  
</table> 
</div> 
</body>
</html> 
<?php

	die();
}

// --------------------------------------------------------------------
