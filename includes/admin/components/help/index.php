<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*    (c) 2011-2014 Mohamed Mrassi and contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

/**
* Documentation and stuff
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 

// --------------------------------------------------------------------

function wsl_component_help()
{
	// HOOKABLE: 
	do_action( "wsl_component_help_start" ); 
?>
<div style="padding: 20px; border: 1px solid #ddd; background-color: #fff;">
	<div style="width: 460px; float: right; line-height: 20px;padding: 8px;background-color: #f2f2f2;border: 1px solid #ccc;padding: 10px;text-align:center;box-shadow: 0 1px 3px rgba(0,0,0,0.13);">
		<h3><?php _wsl_e("Troubleshooting", 'wordpress-social-login') ?></h3>
		<p>
			<b><a class="button-primary" href="options-general.php?page=wordpress-social-login&wslp=diagnostics"><?php _wsl_e('WSL Diagnostics', 'wordpress-social-login') ?></a></b>
			
			<b><a class="button-primary" href="options-general.php?page=wordpress-social-login&wslp=diagnostics#devmode"><?php _wsl_e('Development mode', 'wordpress-social-login') ?></a></b>
			
			<b><a class="button-primary" href="<?php echo WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL ?>/utilities/siteinfo.php"><?php _wsl_e('System information', 'wordpress-social-login') ?></a></b>
		</p> 
		<p>
			<?php _wsl_e('If you run into any issue, you can access the <b>WordPress Social Login Diagnostics</b> to check the <b>Plugin Requirements</b> or to enable the <b>Development mode</b>', 'wordpress-social-login') ?>.
		</p> 
		<p>
			<?php _wsl_e('Remember to include your System information when posting support requests', 'wordpress-social-login') ?>.
		</p> 
	</div>
	
	<h3><?php _wsl_e("Documentation", 'wordpress-social-login') ?></h3>
	<p>
		<?php _wsl_e('The complete <b>User Guide</b> can be found at
		<a href="http://hybridauth.sourceforge.net/wsl/index.html" target="_blank">hybridauth.sourceforge.net/wsl/index.html</a>', 'wordpress-social-login') ?>
	</p>

	<hr />
	
	<h3><?php _wsl_e("FAQs", 'wordpress-social-login') ?></h3>
	<p>
		<?php _wsl_e('A list of <b>Frequently asked questions</b> can be found at
		<a href="http://hybridauth.sourceforge.net/wsl/faq.html" target="_blank">hybridauth.sourceforge.net/wsl/faq.html</a>', 'wordpress-social-login') ?>
	</p>

	<hr />
	
	<h3><?php _wsl_e("Support", 'wordpress-social-login') ?></h3>
	<p>
		<?php _wsl_e('To get help and support refer to <a href="http://hybridauth.sourceforge.net/wsl/support.html" target="_blank">http://hybridauth.sourceforge.net/wsl/support.html</a>', 'wordpress-social-login') ?>
	</p>
 
	<hr />
	
	<h3><?php _wsl_e("Credits", 'wordpress-social-login') ?></h3>
	<p>
		<?php _wsl_e('WordPress Social Login was created by <a href="http://profiles.wordpress.org/miled/" target="_blank">Mohamed Mrassi</a> (a.k.a Miled) and <a href="https://github.com/hybridauth/WordPress-Social-Login/graphs/contributors" target="_blank">contributors</a>', 'wordpress-social-login') ?>.
		<?php _wsl_e("Many other people have also contributed with <br />constructive discussions, support and by submitting patches", 'wordpress-social-login') ?>.
	</p>
 
	<hr />
	
	<h3><?php _wsl_e("License", 'wordpress-social-login') ?></h3>
	<p>
		<?php _wsl_e("<b>WordPress Social Login</b> is an open source software licenced under The MIT License (MIT)", 'wordpress-social-login') ?>
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
<?php
	// HOOKABLE: 
	do_action( "wsl_component_help_end" );
}

wsl_component_help();

// --------------------------------------------------------------------

function wsl_component_help_translate()
{
?>	
<style>
#l10n-footer { 
	display:none !important;
}
#wsl_div_warn { 
	padding: 10px;  
	border: 1px solid #ddd; 
	background-color: #fff; 
	
	width: 750px;
	margin: 0px auto;
	margin-top:30px;
}
</style>
<div id="wsl_div_warn">
	<h3 style="margin:0px;"><?php _wsl_e('Help us translate WordPress Social Login into your language', 'wordpress-social-login') ?></h3> 

	<hr />

	<p>
		<?php _wsl_e( "We're calling on the help of WordPress Social Login users to translate this plugin into their own language or improve current translations where necessary. If you are interested in helping us out, please drop a mail at hybridauth@gmail.com. You can also sent us a pull request on <a href='https://github.com/hybridauth/WordPress-Social-Login'>https://github.com/hybridauth/WordPress-Social-Login</a>", 'wordpress-social-login') ?>. 
	</p>

	<p>
		<?php _wsl_e("If you are interested and you want to help, but you are new to WP/i18n, then we recommend check out this video", 'wordpress-social-login') ?>:
	</p>

	<hr />

	<div style="text-align:center"><iframe width="560" height="315" src="//www.youtube.com/embed/aGN-hbMCPMg" frameborder="0" allowfullscreen></iframe></div>
	
	<br />
	
	<small>
		<?php _wsl_e("<b>Note:</b> WSL uses <code>_wsl_e()</code> instead of <code>_e()</code> and <code>_wsl__</code> instead of <code>__()</code>", 'wordpress-social-login') ?>.
	</small> 

	<br />

</div>  
<?php
}

// --------------------------------------------------------------------	
