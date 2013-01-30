<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*   (c) 2013 Mohamed Mrassi and other contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

/**
* Documentation and stuff
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 
?>

<div class="wslgn"> 
	<h3><?php _e("Documentation", 'wordpress-social-login') ?></h3>
	<p>
		<?php _e('The complete <b>User Guide</b> and <b>Frequently asked questions</b> can be found at
		<a href="http://hybridauth.sourceforge.net/wsl/index.html" target="_blank">http://hybridauth.sourceforge.net/wsl/index.html</a>', 'wordpress-social-login') ?>
	</p>

	<hr />
	
	<h3><?php _e("Troubleshooting", 'wordpress-social-login') ?></h3>
	<p>
		<?php _e('If you run into any issue, you can access the <b><a class="button-primary" href="options-general.php?page=wordpress-social-login&amp;wslp=diagnostics">WSL Diagnostics</a></b> tabs to check the <b style="color:red">Plugin Requirements</b> or to enable the <b style="color:red">Development mode</b>', 'wordpress-social-login') ?>. 
	</p>

	<hr />
	
	<h3><?php _e("Support", 'wordpress-social-login') ?></h3>
	<p>
		<?php _e("To get help and support, here is where you can reach me", 'wordpress-social-login') ?>:
	</p>
	
	<ul style="margin-left:40px;">
		<li><?php _e('<b>Wordpress support forum</b>: <a href="http://wordpress.org/support/plugin/wordpress-social-login" target="_blank">http://wordpress.org/support/plugin/wordpress-social-login</a>', 'wordpress-social-login') ?></li> 
		<li><?php _e('<b>Email</b>: <a href="mailto:hybridauth@gmail.com">hybridauth@gmail.com</a>', 'wordpress-social-login') ?></li> 
	</ul>

<pre class="wslpre" style="margin-left:20px;">
<?php _e("Please note", 'wordpress-social-login') ?>: 

<?php _e(sprintf('Include your <a class="button-primary" href="%s/services/siteinfo.php" target="_blank">website information</a> when posting support requests', WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL), 'wordpress-social-login') ?>.

<?php _e("If I did not answer your question or I took too long to, then its either", 'wordpress-social-login') ?> :
   * <?php _e("I'm too busy or hibernating irl", 'wordpress-social-login') ?>,
   * <?php _e("It has been asked before on the wordpress support forum", 'wordpress-social-login') ?>, 
   * <?php _e("I didn't understand it", 'wordpress-social-login') ?>, 
   * <?php _e("also, be friendly, questions and requests containing hi, hello and thanks can make<br />     a diffrence :P ", 'wordpress-social-login') ?>
</pre>   
	
	<br />
	<hr />
	
	<h3><?php _e("Credits", 'wordpress-social-login') ?></h3>
	<p>
		<?php _e('WordPress Social Login was created by <a href="http://profiles.wordpress.org/miled/" target="_blank">Mohamed Mrassi</a> (a.k.a Miled) and <a href="https://github.com/hybridauth/WordPress-Social-Login/graphs/contributors" target="_blank">contributors</a>', 'wordpress-social-login') ?>.
		<?php _e("Many other people have also contributed with <br />constructive discussions, support and by submitting patches", 'wordpress-social-login') ?>.
	</p>
 
	<hr />
	
	<h3><?php _e("License", 'wordpress-social-login') ?></h3>
	<p>
		<?php _e("<b>WordPress Social Login</b> is an open source software licenced under The MIT License (MIT)", 'wordpress-social-login') ?>
	</p>
<pre class="wslpre" style="margin-left:20px;">
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

</div>
