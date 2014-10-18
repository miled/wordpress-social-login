<?php
/*!
* WordPress Social Login
*
* http://miled.github.io/wordpress-social-login/ | https://github.com/miled/wordpress-social-login
*  (c) 2011-2014 Mohamed Mrassi and contributors | http://wordpress.org/plugins/wordpress-social-login/
*/

/**
* Documentation and stuff
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 

// --------------------------------------------------------------------

function wsl_component_help_reference()
{
	// HOOKABLE: 
	do_action( "wsl_component_help_reference_start" );
?>
<div class="stuffbox" style="padding:20px">
	<h3 style="padding-left:0px"><?php _wsl_e("Documentation", 'wordpress-social-login') ?></h3>
	<p>
		<?php _wsl_e('The complete <b>User Guide</b> can be found at
		<a href="http://miled.github.io/wordpress-social-login/index.html" target="_blank">miled.github.io/wordpress-social-login/index.html</a>', 'wordpress-social-login') ?>
	</p>

	<hr />
	
	<h3 style="padding-left:0px"><?php _wsl_e("FAQs", 'wordpress-social-login') ?></h3>
	<p>
		<?php _wsl_e('A list of <b>Frequently asked questions</b> can be found at
		<a href="http://miled.github.io/wordpress-social-login/faq.html" target="_blank">miled.github.io/wordpress-social-login/faq.html</a>', 'wordpress-social-login') ?>
	</p>

	<hr />
	
	<h3 style="padding-left:0px"><?php _wsl_e("Support", 'wordpress-social-login') ?></h3>
	<p>
		<?php _wsl_e('To get help and support, here is how you can reach me <a href="http://miled.github.io/wordpress-social-login/support.html" target="_blank">miled.github.io/wordpress-social-login/support.html</a>', 'wordpress-social-login') ?>
	</p>
 
	<hr />
	
	<h3 style="padding-left:0px"><?php _wsl_e("Authors", 'wordpress-social-login') ?></h3>
	<p>
		<?php _wsl_e('WordPress Social Login was created by <a href="http://profiles.wordpress.org/miled/" target="_blank">Mohamed Mrassi</a> (a.k.a Miled) and <a href="https://miled.github.io/wordpress-social-login/graphs/contributors" target="_blank">contributors</a>', 'wordpress-social-login') ?>.
	</p>
 
	<hr />
	
	<h3 style="padding-left:0px"><?php _wsl_e("License", 'wordpress-social-login') ?></h3>
	<p>
		<?php _wsl_e("Except where otherwise noted, <b>WordPress Social Login</b> is distributed under the terms of the MIT license reproduced here", 'wordpress-social-login') ?>.
	</p>

	<p>
		<?php _wsl_e("In case you're not familiar with The MIT License, it can be summed in three basic things", 'wordpress-social-login') ?>:
	</p>

	<ul style="margin-left:45px;line-height: 20px;">
		<li><?php _wsl_e("The MIT License (MIT) is compatible with The GNU Public License (GPL) but it is more liberal", 'wordpress-social-login') ?>.</li>
		<li><?php _wsl_e("Do no hold the plugin authors liable. This software is provided AS IS, WITHOUT WARRANTY OF ANY KIND", 'wordpress-social-login') ?>.</li>
		<li><?php _wsl_e("You are allowed to use this plugin for whatever purpose, including in commercial projects, as long as the copyright header inside the code is left intact", 'wordpress-social-login') ?>.</li>
	</ul>

<div class="fade updated" style="line-height: 22px;padding: 22px;font-family: monospace;">
    The MIT License (MIT)
    <br />
    <br />Copyright (C) 2011-2014 Mohamed Mrassi and contributors.
    <br />
    <br />Permission is hereby granted, free of charge, to any person obtaining
    <br />a copy of this software and associated documentation files (the
    <br />"Software"), to deal in the Software without restriction, including
    <br />without limitation the rights to use, copy, modify, merge, publish,
    <br />distribute, sublicense, and/or sell copies of the Software, and to
    <br />permit persons to whom the Software is furnished to do so, subject to
    <br />the following conditions:
    <br />
    <br />The above copyright notice and this permission notice shall be
    <br />included in all copies or substantial portions of the Software.
    <br />
    <br />THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
    <br />EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
    <br />MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
    <br />NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
    <br />LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
    <br />OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
    <br />WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
</div>
</div>
<?php
	// HOOKABLE: 
	do_action( "wsl_component_help_reference_end" );
}

// --------------------------------------------------------------------	
