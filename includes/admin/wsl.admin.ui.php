<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*    (c) 2011-2013 Mohamed Mrassi and contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

/** 
* The LOC in charge of displaying WSL Admin GUInterfaces
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------

/**
* Generate wsl admin pages 
*
* wp-admin/options-general.php?page=wordpress-social-login&.. 
*/
function wsl_admin_init()
{
	// HOOKABLE: 
	do_action( "wsl_admin_init_start" );

	if ( ! wsl_check_requirements() ){
		wsl_admin_ui_fail();

		exit;
	}

	GLOBAL $WORDPRESS_SOCIAL_LOGIN_ADMIN_TABS;
	GLOBAL $WORDPRESS_SOCIAL_LOGIN_COMPONENTS;
	GLOBAL $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG;
	GLOBAL $WORDPRESS_SOCIAL_LOGIN_VERSION;
	GLOBAL $wpdb;

	if( isset( $_REQUEST["enable"] ) && isset( $WORDPRESS_SOCIAL_LOGIN_COMPONENTS[ $_REQUEST["enable"] ] ) ){
		$component = $_REQUEST["enable"];

		$WORDPRESS_SOCIAL_LOGIN_COMPONENTS[ $component ][ "enabled" ] = true;

		update_option( "wsl_components_" . $component . "_enabled", 1 );

		wsl_register_components();
	}

	if( isset( $_REQUEST["disable"] ) && isset( $WORDPRESS_SOCIAL_LOGIN_COMPONENTS[ $_REQUEST["disable"] ] ) ){
		$component = $_REQUEST["disable"];

		$WORDPRESS_SOCIAL_LOGIN_COMPONENTS[ $component ][ "enabled" ] = false;

		update_option( "wsl_components_" . $component . "_enabled", 2 );

		wsl_register_components();
	}

	$wslp            = "networks";
	$wsldwp          = 0;
	$assets_base_url = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . '/assets/img/16x16/';

	if( isset( $_REQUEST["wslp"] ) ){
		$wslp = trim( strtolower( strip_tags( $_REQUEST["wslp"] ) ) );
	}

	if( isset( $WORDPRESS_SOCIAL_LOGIN_ADMIN_TABS[$wslp] ) && $WORDPRESS_SOCIAL_LOGIN_ADMIN_TABS[$wslp]["enabled"] ){
		if( isset( $WORDPRESS_SOCIAL_LOGIN_ADMIN_TABS[$wslp]["header_action"] ) && $WORDPRESS_SOCIAL_LOGIN_ADMIN_TABS[$wslp]["header_action"] ){ 
			do_action( $WORDPRESS_SOCIAL_LOGIN_ADMIN_TABS[$wslp]["header_action"] );
		}

		wsl_admin_ui_header( $wslp );

		if( isset( $WORDPRESS_SOCIAL_LOGIN_ADMIN_TABS[$wslp]["body_action"] ) && $WORDPRESS_SOCIAL_LOGIN_ADMIN_TABS[$wslp]["body_action"] ){ 
			do_action( $WORDPRESS_SOCIAL_LOGIN_ADMIN_TABS[$wslp]["body_action"] );
		}

		elseif( ! ( isset( $WORDPRESS_SOCIAL_LOGIN_ADMIN_TABS[$wslp]["admin-url"] ) && ! $WORDPRESS_SOCIAL_LOGIN_ADMIN_TABS[$wslp]["admin-url"] ) ){
			include "components/$wslp/index.php";

			wsl_admin_ui_footer();
		}
	}
	else{
		wsl_admin_ui_header();

		wsl_admin_ui_error();
	}
	
	// HOOKABLE: 
	do_action( "wsl_admin_init_end" );
}

// --------------------------------------------------------------------

/**
* Renders an editor in a page in the typical fashion used in Posts and Pages.
*/
function wsl_render_wp_editor( $name, $content )
{
	// HOOKABLE: 
	do_action( "wsl_render_wp_editor_start" );
?>
<div class="postbox"> 
	<div class="wp-editor-textarea" style="background-color: #FFFFFF;">
	<?php 
		wp_editor( 
			$content, $name, 
			array( 'textarea_name' => $name, 'media_buttons' => true, 'tinymce' => array( 'theme_advanced_buttons1' => 'formatselect,forecolor,|,bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,|,link,unlink' ) ) 
		);
	?>
	</div> 
</div>
<?php
	// HOOKABLE: 
	do_action( "wsl_render_wp_editor_end" );
}

// --------------------------------------------------------------------

/**
* Render wsl admin pages hearder (label and tabs)
*/
function wsl_admin_ui_header( $wslp = null )
{
	// HOOKABLE: 
	do_action( "wsl_admin_ui_header_start" );

	GLOBAL $WORDPRESS_SOCIAL_LOGIN_VERSION;
	GLOBAL $WORDPRESS_SOCIAL_LOGIN_ADMIN_TABS;
?>
<style>
h1 {
	color: #333333;
	text-shadow: 1px 1px 1px #FFFFFF; 
	font-size: 2.8em;
	font-weight: 200;
	line-height: 1.2em;
	margin: 0.2em 200px 0.6em 0.2em;
}
h2 .nav-tab {
	color: #21759B;
}
h2 .nav-tab-active {
	color: #464646;
	text-shadow: 1px 1px 1px #FFFFFF;
}
hr{ 
	border-color: #EEEEEE;
	border-style: none none solid;
	border-width: 0 0 1px;
	margin: 2px 0 15px;
} 
.wsldiv { 
	margin: 25px 40px 0 20px; 
}
.wsldiv p{  
	line-height: 1.8em;
}
.wslgn{ 
	margin-left:20px;
}
.wslgn p{ 
	margin-left:20px;
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
 
.thumbnails:before,
.thumbnails:after {
  display: table;
  line-height: 0;
  content: "";
}

.thumbnail {
  display: block;
  padding: 4px;
  line-height: 20px;
  border: 1px solid #ddd;
  -webkit-border-radius: 4px;
	 -moz-border-radius: 4px;
		  border-radius: 4px;
  -webkit-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.055);
	 -moz-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.055);
		  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.055);
  -webkit-transition: all 0.2s ease-in-out;
	 -moz-transition: all 0.2s ease-in-out;
	   -o-transition: all 0.2s ease-in-out;
		  transition: all 0.2s ease-in-out;
}

a.thumbnail:hover {
  border-color: #0088cc;
  -webkit-box-shadow: 0 1px 4px rgba(0, 105, 214, 0.25);
	 -moz-box-shadow: 0 1px 4px rgba(0, 105, 214, 0.25);
		  box-shadow: 0 1px 4px rgba(0, 105, 214, 0.25);
}

.thumbnail > img {
  display: block;
  max-width: 100%;
  margin-right: auto;
  margin-left: auto;
}

.thumbnail .caption {
  padding: 9px;
  color: #555555;
}
.span4 {  
	width: 220px; 
}
#wp-social-login-connect-with {  
	font-size: 14px; 
}
#wp-social-login-connect-options {  
	margin:5px; 
}
.wsl_connect_with_provider {  
	text-decoration:none; 
	cursor:not-allowed;
} 
#wsl-w-panel {
	background: linear-gradient(to top, #F5F5F5, #FAFAFA) repeat scroll 0 0 #F5F5F5;
	border-color: #DFDFDF;
	border-radius: 3px 3px 3px 3px;
	border-style: solid;
	border-width: 1px;
	font-size: 13px;
	line-height: 2.1em;
	margin: 20px 0;
	overflow: auto;
	-padding: 23px 10px 12px;
	padding: 5px;
	position: relative;
}
#wsl-w-panel-dismiss:before {
    background: url("images/xit.gif") no-repeat scroll 0 17% transparent;
    content: " ";
    height: 100%;
    left: -12px;
    position: absolute;
    width: 10px;
	margin: -2px 0;
}
#wsl-w-panel-dismiss:hover:before {
    background-position: 100% 17%;
}
#wsl-w-panel-dismiss {
	font-size: 13px;
	line-height: 1;
	padding: 8px 3px;
	position: absolute;
	right: 10px;
	text-decoration: none;
	top: 0px;
}
#wsl-w-panel-updates-tr {
	display:none;  
} 
.hideinside {
	/* display:none; */
} 

.wp-editor-textarea{
  width:98%;
  padding:1%;
  font-family:"Trebuchet MS", Arial, verdana, sans-serif;
}
.wp-editor-textarea textarea{
  height:100px;
}

.wp-editor-textarea input {
	width: auto !important;
}

#wsl_i18n_pre {
	height: 800px; 
	overflow-x: hidden;
	overflow-y: scroll;
}  
#wsl_i18n {
	width:530px; 
	width: 560px;
	display:none;
	padding: 10px; 
	border: 1px solid #ddd; 
	background-color: #fff;  
	float:left;
	margin-left: 20px;
	padding: 0 10px 10px; 
} 
#wsl_i18n_form {
	width:420px; 
	width:340px; 
	display:none;
	padding: 10px; 
	border: 1px solid #ddd; 
	background-color: #fff;
	float:left; 
}
#wsl_i18n_cla {
	display:none;
	padding: 10px;  
	border: 1px solid #ddd; 
	background-color: #fff; 
	
	width: 50%;
	margin: 0px auto;
	margin-top:50px;
}
</style>
<a name="wsltop"></a>
<div class="wsldiv">
<h1>
	WordPress Social Login

	<small><?php echo $WORDPRESS_SOCIAL_LOGIN_VERSION ?></small>

	<?php
		if( get_option( 'wsl_settings_development_mode_enabled' ) ){
			?>
				<span style="color:red;-font-size: 14px;">(<?php _wsl_e("Development mode is enabled!", 'wordpress-social-login') ?>)</span>
			<?php
		}
	?>
</h1>

<h2 class="nav-tab-wrapper">
	&nbsp;
	<?php
		foreach( $WORDPRESS_SOCIAL_LOGIN_ADMIN_TABS as $name => $settings ){
			if( $settings["enabled"] && ( $settings["visible"] || $wslp == $name ) ){
				if( isset( $settings["admin-url"] ) ){
					?><a class="nav-tab <?php if( $wslp == $name ) echo "nav-tab-active"; ?>" <?php if( isset( $settings["pull-right"] ) && $settings["pull-right"] ) echo 'style="float:right"'; ?> href="<?php echo $settings["admin-url"] ?>"><?php echo $settings["label"] ?></a><?php
				}
				else{
					?><a class="nav-tab <?php if( $wslp == $name ) echo "nav-tab-active"; ?>" <?php if( isset( $settings["pull-right"] ) && $settings["pull-right"] ) echo 'style="float:right"'; ?> href="options-general.php?page=wordpress-social-login&wslp=<?php echo $name ?>"><?php echo $settings["label"] ?></a><?php
				}
			}
		}
	?>
</h2>

<div id="wsl_admin_tab_content">
<?php
	// HOOKABLE: 
	do_action( "wsl_admin_ui_header_end" );
}

// --------------------------------------------------------------------

/**
* Renders wsl admin pages footer
*/
function wsl_admin_ui_footer()
{
	// HOOKABLE: 
	do_action( "wsl_admin_ui_footer_start" );

	GLOBAL $WORDPRESS_SOCIAL_LOGIN_VERSION;
?>
</div> <!-- ./wsl_admin_tab_content -->  
<div class="clear"></div>
<?php wsl_admin_localize_widget(); ?>

<script>
	// check for new versions and updates
	jQuery.getScript("http://hybridauth.sourceforge.net/wsl/wsl.version.check.and.updates.php?v=<?php echo $WORDPRESS_SOCIAL_LOGIN_VERSION ?>");
</script>
<?php
	// HOOKABLE: 
	do_action( "wsl_admin_ui_footer_end" );
}

// --------------------------------------------------------------------

/**
* Renders wsl admin error page
*/
function wsl_admin_ui_error()
{
	// HOOKABLE: 
	do_action( "wsl_admin_ui_error_start" );
?>
<style>
#wsl_div_warn { 
	padding: 10px;  
	border: 1px solid #ddd; 
	background-color: #fff; 
	
	width: 55%;
	margin: 0px auto;
	margin-top:30px;
}
</style>
<div id="wsl_div_warn">
	<h3 style="margin:0px;"><?php _wsl_e('Something wrong!', 'wordpress-social-login') ?></h3> 

	<hr />

	<p>
		<?php _wsl_e('Unknown or Disabled <b>Component</b>! Check the list of enabled components or the typed URL', 'wordpress-social-login') ?> .
	</p>

	<p>
		<?php _wsl_e("If you believe you've found a problem with <b>WordPress Social Login</b>, be sure to let us know so we can fix it", 'wordpress-social-login') ?>.
	</p>

	<hr />

	<div>
		<a class="button-secondary" href="http://hybridauth.sourceforge.net/wsl/support.html" target="_blank"><?php _wsl_e( "Report as bug", 'wordpress-social-login' ) ?></a>
		<a class="button-primary" href="options-general.php?page=wordpress-social-login&wslp=components" style="float:right"><?php _wsl_e( "Check enabled components", 'wordpress-social-login' ) ?></a>
	</div> 
</div> 

<?php
	// HOOKABLE: 
	do_action( "wsl_admin_ui_error_end" );
}

// --------------------------------------------------------------------

/**
* Renders wsl #FAIL page
*/
function wsl_admin_ui_fail()
{
	// HOOKABLE: 
	do_action( "wsl_admin_ui_fail_start" );
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
<script>
	jQuery.getScript("http://hybridauth.sourceforge.net/wsl/wsl.version.check.and.updates.php?v=<?php echo $WORDPRESS_SOCIAL_LOGIN_VERSION ?>&fail=true");
</script>
<?php
	// HOOKABLE: 
	do_action( "wsl_admin_ui_fail_end" );
}

// --------------------------------------------------------------------

/**
* Renders wsl admin welcome panel
*/
function wsl_admin_welcome_panel()
{
	if( isset( $_REQUEST["wsldwp"] ) && (int) $_REQUEST["wsldwp"] ){
		$wsldwp = (int) $_REQUEST["wsldwp"];

		update_option( "wsl_settings_welcome_panel_enabled", wsl_version() );

		return;
	}

	// if new user or wsl updated, then we display wsl welcome panel
	if( get_option( 'wsl_settings_welcome_panel_enabled' ) == wsl_version() ){ 
		return;
	}
?> 
<!-- 
	if you want to know if a UI was made by devloper, then here is a tip: he will always use tables

	//> wsl-w-panel is shamelessly borrowered and modified from wordpress welcome-panel
-->
<div id="wsl-w-panel">
	<a href="options-general.php?page=wordpress-social-login&wslp=<?php echo $wslp ?>&wsldwp=1" id="wsl-w-panel-dismiss"><?php _e("Dismiss", 'wordpress-social-login') ?></a>
	
	<table width="100%" border="0" style="margin:0;padding:0;">
		<tr>
			<td width="10" valign="top"></td>
			<td width="300" valign="top">
				<b style="font-size: 16px;"><?php _wsl_e("Welcome!", 'wordpress-social-login') ?></b>
				<p>
					<?php _wsl_e("If you are still new to WordPress Social Login, we have provided a few walkthroughs to get you started", 'wordpress-social-login') ?>.
				</p>
			</td>
			<td width="40" valign="top"></td>
			<td width="260" valign="top">
				<br />
				<p>
					<b><?php _wsl_e("Get Started", 'wordpress-social-login') ?></b>
				</p>
				<ul style="margin-left:25px;">
					<li><?php _wsl_e('<a href="http://hybridauth.sourceforge.net/wsl/configure.html" target="_blank">Setup and Configuration</a>', 'wordpress-social-login') ?></li>
					<li><?php _wsl_e('<a href="http://hybridauth.sourceforge.net/wsl/customize.html" target="_blank">Customize WSL Widgets</a>', 'wordpress-social-login') ?></li>
					<li><?php _wsl_e('<a href="http://hybridauth.sourceforge.net/wsl/userdata.html" target="_blank">Manage users and contacts</a>', 'wordpress-social-login') ?></li> 
					<li><?php _wsl_e('<a href="http://hybridauth.sourceforge.net/wsl/index.html" target="_blank">WSL User Guide</a> and <a href="http://hybridauth.sourceforge.net/wsl/faq.html" target="_blank">FAQ</a>', 'wordpress-social-login') ?></li>  
				</ul>
			</td>
			<td width="" valign="top">
				<br />
				<p>
					<b><?php echo sprintf( _wsl__( "What's new on WSL %s", 'wordpress-social-login'), wsl_version() ) ?></b>
				</p>
				<ul style="margin-left:25px;">
					<li><?php _wsl_e('In a similar way to WordPress plugins, WSL uses <a href="options-general.php?page=wordpress-social-login&wslp=components">Components</a>', 'wordpress-social-login') ?>,</li>  
					<li><?php _wsl_e('<b>Email Validation</b> is replaced with <b>Profile Completion</b>', 'wordpress-social-login') ?>,</li>
					<li><?php _wsl_e('<b>User Moderation</b> made compatible with <a href="http://wordpress.org/extend/plugins/theme-my-login/" target="_blank">Theme My Login</a> plugin', 'wordpress-social-login') ?>,</li>  
					<li><?php _wsl_e('A number of enhancements and new options now available', 'wordpress-social-login') ?>.</li>
				</ul>
			</td>
		</tr>
		<tr id="wsl-w-panel-updates-tr">
			<td colspan="5" style="border-top:1px solid #ccc;" id="wsl-w-panel-updates-td">
				&nbsp;
			</td>
		</tr>
	</table> 
</div>
<?php 
}

// --------------------------------------------------------------------

/**
* Renders wsl localization widget
*/
function wsl_admin_localize_widget()
{
	global $WORDPRESS_SOCIAL_LOGIN_TEXTS;

	// default endpoint_url
	$assets_url = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL;

	// overwrite endpoint_url if need'd
	if( get_option( 'wsl_settings_base_url' ) ){
		$assets_url = strtolower( get_option( 'wsl_settings_base_url' ) );
	}

	$assets_url .= '/assets/img/';

	$current_user = wp_get_current_user();
	
	$wslp = "networks"; 

	if( isset( $_REQUEST["wslp"] ) ){
		$wslp = $_REQUEST["wslp"];
	}
?> 
<form action="http://hybridauth.sourceforge.net/wsl/i18n.contributions.php" method="post" target="_blank"> 
<input type="hidden" name="wsl_i18n_site_url" value="<?php bloginfo('url'); ?>" />
<input type="hidden" name="wsl_i18n_site_charset" value="<?php bloginfo('charset'); ?>" />
<input type="hidden" name="wsl_i18n_site_language" value="<?php bloginfo('language'); ?>" />
<input type="hidden" name="wsl_i18n_current_page" value="<?php echo $wslp ?>" />

<input type="hidden" name="wsl_version" value="<?php echo wsl_version() ?>" />

<div id="wsl_i18n_cla">
		<h3 style="margin:0px;"><?php _wsl_e( "Contributor License Agreement", 'wordpress-social-login' ) ?></h3> 

		<hr />

		<p>
			<?php _wsl_e( "You are about to submit your contributions to the WordPress Social Login Website to be reviewed for inclusion in future versions", 'wordpress-social-login' ) ?>.

			<?php _wsl_e( "You hereby grant the permission to publish your contribution, in whole or in part, and to made it available  under the <b>MIT License</b>, for the <b>Wordpress community</b> to, freely use or misuse", 'wordpress-social-login' ) ?>.
		</p>

		<hr />
		
		<div> 
			<a class="button-secondary" href="admin.php?page=wordpress-social-login"><?php _wsl_e( "Hell No", 'wordpress-social-login' ) ?></a>
			<input style="float:right" type="submit" value="<?php _wsl_e( "Yes, I agree to contribute my translation", 'wordpress-social-login' ) ?>" class="button-primary"  onClick="wsl_i18n_done()" > 
		</div> 
</div> 
<div id="wsl_i18n_form"> 
	<h3 style="margin:0px;"><?php _wsl_e( "Help us translate this page into your language", 'wordpress-social-login' ) ?></h3> 

	<hr />

	<p><?php _wsl_e( "You can <b>translate as much you please as much as you want</b>. You don't have to translate everything in this page. Ignore any string you want or aleardy translated. You could also use this tool to fix any typo you may find or to improve the current language expressions", 'wordpress-social-login' ) ?>.</p>

	<p><?php _wsl_e( "All the texts on this page are automatically extracted and generated on the form beside. If the translation tool has scapped something you may consider as irrelevant, please leave that particular field empty", 'wordpress-social-login' ) ?>.</p>

	<p><?php _wsl_e( "Your contributions will be sent to the WordPress Social Login website for inclusion in future versions", 'wordpress-social-login' ) ?>.</p> 
</div>
<div id="wsl_i18n">  
	<pre id="wsl_i18n_pre"></pre> 
	<hr />
	<table width="100%" cellspacing="2" cellpadding="5" border="0"> 
		<tbody>
		  <tr>
			<td align="right"><strong><?php _wsl_e( "Target Language", 'wordpress-social-login' ) ?></strong></td>
			<td> 
				<select name="wsl_i18n_locale" style="width:400px;"><option value="en">English (enhancement)</option><option value="af">Afrikaans</option><option value="sq">Albanian</option><option value="ar">Arabic</option><option value="eu">Basque</option><option value="be">Belarusian</option><option value="bn_IN">Bengali (India)</option><option value="bg">Bulgarian</option><option value="ca">Catalan</option><option value="zh_CN">Chinese (Simplified)</option><option value="zh_TW">Chinese (Traditional)</option><option value="cs">Czech</option><option value="da">Danish</option><option value="nl">Dutch</option><option value="eo">Esperanto</option><option value="et">Estonian</option><option value="fi">Finnish</option><option value="fr">French</option><option value="fy_NL">Frisian</option><option value="gl">Galician</option><option value="ka">Georgian</option><option value="de">German</option><option value="el">Greek</option><option value="gu_IN">Gujarati</option><option value="he">Hebrew</option><option value="hi_IN">Hindi</option><option value="hu">Hungarian</option><option value="is">Icelandic</option><option value="id">Indonesian</option><option value="ga_IE">Irish</option><option value="it">Italian</option><option value="ja">Japanese</option><option value="kn">Kannada</option><option value="ko">Korean</option><option value="ku">Kurdish</option><option value="lv">Latvian</option><option value="lt">Lithuanian</option><option value="mk">Macedonian</option><option value="mr">Marathi</option><option value="mn">Mongolian</option><option value="nb_NO">Norwegian (Bokmal)</option><option value="nn_NO">Norwegian (Lengadocian)</option><option value="oc">Occitan (Lengadocian)</option><option value="pl">Polish</option><option value="pt_BR">Portuguese (Brazilian)</option><option value="pt_PT">Portuguese (Portugal)</option><option value="pa_IN">Punjabi</option><option value="ro">Romanian</option><option value="ru">Russian</option><option value="sr">Serbian</option><option value="si">Sinhala</option><option value="sk">Slovak</option><option value="sl">Slovenian</option><option value="es">Spanish</option><option value="es_AR">Spanish (Argentina)</option><option value="sv_SE">Swedish</option><option value="te">Telgu</option><option value="th">Thai</option><option value="tr">Turkish</option><option value="uk">Ukrainian</option></select>
			</td>
		  </tr> 
		  <tr>
			<td align="right"><strong><?php _wsl_e( "Your Name", 'wordpress-social-login' ) ?></strong><br />(<?php _wsl_e( "optional", 'wordpress-social-login' ) ?>)</td>
			<td> 
			<input name="wsl_i18n_name" type="text" value="<?php echo htmlspecialchars( $current_user->display_name . " <" . get_bloginfo('admin_email') . ">" ); ?>" class="inputgnrc" style="width:400px;"> 
			</td>
		  </tr>
			<tr>
			<td align="right"><strong><?php _wsl_e( "Comment", 'wordpress-social-login' ) ?></strong><br />(<?php _wsl_e( "optional", 'wordpress-social-login' ) ?>)</td>
			<td> 
			<textarea name="wsl_i18n_comment" style="width:400px;height:60px;"></textarea>
			</td>
		  </tr> 
		</tbody>
	</table> 
	<hr /> 
	<div style="float:right"> 
		<a class="button-primary" href="javascript:void(0);" onClick="wsl_i18n_cla()"><?php _wsl_e( "Submit changes", 'wordpress-social-login' ) ?></a>
	</div>
</div>
</form>
<script>
	function wsl_i18n_cla(){
		jQuery("#wsl_i18n_form").hide()
		jQuery("#wsl_i18n").hide()
		jQuery("#wsl_i18n_cla").show()
	}

	function wsl_i18n_done(){
		jQuery("#wsl_i18n_form").hide()
		jQuery("#wsl_i18n").hide()
		jQuery("#wsl_i18n_cla").hide()

		jQuery("#wsl_admin_tab_content").show()
		jQuery("#l10n-footer").show()
	}

	function wsl_i18n(){
		if(typeof jQuery=="undefined"){
			alert( "Error: WordPress Social Login require jQuery to be installed on your wordpress in order to works!" )

			return
		}

		jQuery("#wsl_admin_tab_content").hide()
		jQuery("#l10n-footer").hide()
		jQuery("#wsl_i18n_form").show() 
		jQuery("#wsl_i18n").show()
		jQuery("#wsl_i18n_cla").hide()

		var __wsl_texts = <?php echo json_encode ( array_keys( $WORDPRESS_SOCIAL_LOGIN_TEXTS ) ); ?>

		jQuery.each( __wsl_texts, function(index, string) {
			if( string.length >= 2 ){ 
				if( string.length >= 100 ) jQuery("#wsl_i18n_pre").append( index + ".\n" + '<textarea id="wsl_i18n_target_'+ index +'" class="wsl_i18n_input" style="width:98%;height:60px;" name="wsl_i18n_target_'+ index +'">' + string + '</textarea>' + "\n" )
				if( string.length <  100 ) jQuery("#wsl_i18n_pre").append( index + ".\n" + '<input    id="wsl_i18n_target_'+ index +'" class="wsl_i18n_input inputgnrc" type="text" style="width:98%;" name="wsl_i18n_target_'+ index +'" value="' + string + '" >' + "\n" )

				jQuery("#wsl_i18n_pre").append( '<textarea id="wsl_i18n_default_'+ index +'" style="display:none;" name="wsl_i18n_default_'+ index +'">' + string + '</textarea>' + "\n" ) 
			}

			jQuery("#wsl_i18n_target_" + index).change(function() {
				jQuery(this).css( "border-color", "#C09853" )

				if( jQuery(this).val() ){
					jQuery(this).css( "border-color", "#468847" )
				}

				if( jQuery(this).val() == jQuery("#wsl_i18n_default_" + index).val() ){
					jQuery(this).css( "border-color", "#DFDFDF" )
				}

				if( jQuery(this).val() && jQuery(this).val().length <  2 ){
					jQuery(this).css( "border-color", "#B94A48" )
				}
			})
		})
	}
</script>
<div id="l10n-footer" style="float: left; display: block; ">
	<br style="clear:both;" />
	<hr />
	<img src="<?php echo $assets_url ?>flags.png">
	<a href="#wsltop" onclick="return wsl_i18n();">
		<?php _wsl_e( "Help us translate this page into your language", 'wordpress-social-login' ) ?>
    </a>
</div>
	<?php

	return;
}

// --------------------------------------------------------------------
