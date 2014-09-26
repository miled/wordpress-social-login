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
	$assets_base_url  = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . '/assets/img/'; 
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
#error-page {
	margin-top: 50px;
}
#error-page p {
	font-size: 14px;
	line-height: 1.5;
	margin: 25px 0 20px;
}
#error-page code {
	font-family: Consolas, Monaco, monospace;
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
<body id="error-page"> 
	<table width="100%" border="0">
		<tr>
			<td align="center"><img src="<?php echo $assets_base_url ?>alert.png" /></td>
		</tr>
		<tr>
			<td align="center">
				<div style="line-height: 20px;padding: 8px;background-color: #f2f2f2;border: 1px solid #ccc;padding: 10px;text-align:center;box-shadow: 0 1px 3px rgba(0,0,0,0.13);">
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

// --------------------------------------------------------------------
