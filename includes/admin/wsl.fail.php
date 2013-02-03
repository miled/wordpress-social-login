<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*   (c) 2013 Mohamed Mrassi and other contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

/**
* This
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 
?>

<style> 
h1 {
    color: #333333;
    text-shadow: 1px 1px 1px #FFFFFF; 
    font-size: 2.8em;
    font-weight: 200;
    line-height: 1.2em;
    margin: 0.2em 200px 0 0;
} 
hr{ 
	border-color: #EEEEEE;
	border-style: none none solid;
	border-width: 0 0 1px;
	margin: 2px 0 15px;
}
.wsldiv { 
    margin: 30px 70px 0 70px; 
}
.wsldiv p{ 
    ont-size: 14px;
	line-height: 1.8em;
}
.wslpre{ 
    font-size:14m;
	border:1px solid #E6DB55; 
	border-radius: 3px;
	padding:5px;
	width:650px;
}
ul {
    list-style: disc outside none;
}
</style>

<div class="wsldiv">
	<h1><?php _e("WordPress Social Login - FAIL!", 'wordpress-social-login') ?></h1>

	<hr />
	
	<p> 
		<?php _e('Despite the efforts, the plugin <a href="http://profiles.wordpress.org/miled/" target="_blank">author</a> and other <a href="https://github.com/hybridauth/WordPress-Social-Login/graphs/contributors" target="_blank">contributors</a>, put into <b>WordPress Social Login</b> in terms of reliability, portability, <br />and maintenance', 'wordpress-social-login') ?>.
		<b style="color:red;"><?php _e('Your server failed the requirements check for this plugin!', 'wordpress-social-login') ?></b>
	</p> 
	<p> 
		<?php _e('These requirements are usually met by default by most "modern" web hosting providers, however some complications may <br />occur with <b>shared hosting</b> and, or <b>custom wordpress installations</b>', 'wordpress-social-login') ?>.
	</p> 
	<p> 
		<?php _e("To determine what may cause this failure, run the <b>WordPress Social Login Requirements Test</b> by clicking the button bellow", 'wordpress-social-login') ?>:
		
		<br />
		<br />
		<a class="button-primary" href='<?php echo WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL ?>/services/diagnostics.php' target='_blank'><?php _e("Run the plugin requirements test", 'wordpress-social-login') ?></a> 
		<a class="button-primary" href='<?php echo WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL ?>/services/siteinfo.php' target='_blank'><?php _e("System Information", 'wordpress-social-login') ?></a> 
	</p> 
 
	<br /> 
	<hr />
 
	<p>
		<?php _e("<b>WordPress Social Login</b> is an open source software licenced under The MIT License (MIT)", 'wordpress-social-login') ?>
	</p> 

<pre class="wslpre">
	Copyright (C) 2011-2013 Mohamed Mrassi and contributors

	Permission is hereby granted, free of charge, to any person obtaining
	a copy of this software and associated documentation files (the
	"Software"), to deal in the Software without restriction, including
	without limitation the rights to use, copy, modify, merge, publish,
	distribute, sublicense, and/or sell copies of the Software, and to
	permit persons to whom the Software is furnished to do so, subject to
	the following conditions:

	The above copyright notice and this permission notice shall be
	included in all copies or substantial portions of the Software.

	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
	EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
	MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
	NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
	LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
	OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
	WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
</pre>

	<br /> 