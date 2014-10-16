<?php
/*!
* WordPress Social Login
*
* http://miled.github.io/wordpress-social-login/index.html | http://github.com/hybridauth/WordPress-Social-Login
*    (c) 2011-2014 Mohamed Mrassi and contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
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

	if ( ! wsl_check_requirements() )
	{
		wsl_admin_ui_fail();

		exit;
	}

	GLOBAL $WORDPRESS_SOCIAL_LOGIN_ADMIN_TABS;
	GLOBAL $WORDPRESS_SOCIAL_LOGIN_COMPONENTS;
	GLOBAL $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG;
	GLOBAL $WORDPRESS_SOCIAL_LOGIN_VERSION;

	if( isset( $_REQUEST["enable"] ) && isset( $WORDPRESS_SOCIAL_LOGIN_COMPONENTS[ $_REQUEST["enable"] ] ) )
	{
		$component = $_REQUEST["enable"];

		$WORDPRESS_SOCIAL_LOGIN_COMPONENTS[ $component ][ "enabled" ] = true;

		update_option( "wsl_components_" . $component . "_enabled", 1 );

		wsl_register_components();
	}

	if( isset( $_REQUEST["disable"] ) && isset( $WORDPRESS_SOCIAL_LOGIN_COMPONENTS[ $_REQUEST["disable"] ] ) )
	{
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

	if( isset( $WORDPRESS_SOCIAL_LOGIN_ADMIN_TABS[$wslp] ) && $WORDPRESS_SOCIAL_LOGIN_ADMIN_TABS[$wslp]["enabled"] )
	{
		if( isset( $WORDPRESS_SOCIAL_LOGIN_ADMIN_TABS[$wslp]["header_action"] ) && $WORDPRESS_SOCIAL_LOGIN_ADMIN_TABS[$wslp]["header_action"] )
		{ 
			do_action( $WORDPRESS_SOCIAL_LOGIN_ADMIN_TABS[$wslp]["header_action"] );
		}

		wsl_admin_ui_header( $wslp );

		if( isset( $WORDPRESS_SOCIAL_LOGIN_ADMIN_TABS[$wslp]["body_action"] ) && $WORDPRESS_SOCIAL_LOGIN_ADMIN_TABS[$wslp]["body_action"] )
		{ 
			do_action( $WORDPRESS_SOCIAL_LOGIN_ADMIN_TABS[$wslp]["body_action"] );
		}

		else
		{
			include "components/$wslp/index.php"; 
		}
	}
	else
	{
		wsl_admin_ui_header();

		wsl_admin_ui_error();
	}

	wsl_admin_ui_footer();

	// HOOKABLE: 
	do_action( "wsl_admin_init_end" );
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
	<?php 
		wsl_admin_ui_css();
	?>
</style>

<a name="wsltop"></a>
<div class="wsl-container">

<?php
	global $wpdb;

	$db_check_profiles = $wpdb->get_var( "SHOW TABLES LIKE '{$wpdb->prefix}wslusersprofiles'" ) === $wpdb->prefix . 'wslusersprofiles' ? 1 : 0;
	$db_check_contacts = $wpdb->get_var( "SHOW TABLES LIKE '{$wpdb->prefix}wsluserscontacts'" ) === $wpdb->prefix . 'wsluserscontacts' ? 1 : 0;

	if( ! $db_check_profiles || ! $db_check_contacts )
	{
		?>
			<div class="fade error wsl-error-db-tables" style="margin: 4px 0 20px;">
				<p>
					<?php _wsl_e('WSL has detected that some of its database tables does not exist. This need to fix in order for this plugin to function correctly', 'wordpress-social-login') ?>.
				</p>
				<p>
					<a class="button-secondary" href="options-general.php?page=wordpress-social-login&wslp=tools#repair-tables"><?php _wsl_e('Repair WSL tables', 'wordpress-social-login') ?></a>
				</p>
			</div>
		<?php
	}

	$itsec_tweaks = get_option( 'itsec_tweaks' );

	if( $itsec_tweaks && $itsec_tweaks['long_url_strings'] )
	{
		?>
			<div class="fade error wsl-error-itsec-long-url" style="margin: 4px 0 20px;">
				<p>
					<?php _wsl_e('WSL has detected that you are running iThemes Security plugin (formerly Better WP Security) with "Prevent long URL strings" option enabled. This will prevent Facebook, Yahoo, Steam and many others providers from working', 'wordpress-social-login') ?>.
				</p>
			</div>
		<?php
	}

	if( get_option( 'wsl_settings_development_mode_enabled' ) ){
		?>
			<div class="fade error wsl-error-dev-mode-on" style="margin: 4px 0 20px;">
				<p>
					<?php _wsl_e('<b>Warning:</b> You are now running WordPress Social Login with DEVELOPMENT MODE enabled. This mode is not intend for live websites as it might raise serious security risks', 'wordpress-social-login') ?>.
				</p>
				<p>
					<a class="button-secondary" href="options-general.php?page=wordpress-social-login&wslp=tools#dev-mode"><?php _wsl_e('Change this mode', 'wordpress-social-login') ?></a>
					<a class="button-secondary" href="http://miled.github.io/wordpress-social-login/devmode.html" target="_blank"><?php _wsl_e('Read about the development mode', 'wordpress-social-login') ?></a>
				</p>
			</div>
		<?php
	}

	if( get_option( 'wsl_settings_debug_mode_enabled' ) ){
		?>
			<div class="fade updated wsl-error-debug-mode-on" style="margin: 4px 0 20px;">
				<p>
					<?php _wsl_e('<b>Note:</b> You are now running WordPress Social Login with DEBUG MODE enabled. This mode is not intend for live websites as it might add to loading time and store unnecessary data on your server', 'wordpress-social-login') ?>.
				</p>
				<p>
					<a class="button-secondary" href="options-general.php?page=wordpress-social-login&wslp=tools#debug-mode"><?php _wsl_e('Change this mode', 'wordpress-social-login') ?></a>
					<a class="button-secondary" href="options-general.php?page=wordpress-social-login&wslp=watchdog"><?php _wsl_e('View WSL logs', 'wordpress-social-login') ?></a>
					<a class="button-secondary" href="http://miled.github.io/wordpress-social-login/debugmode.html" target="_blank"><?php _wsl_e('Read about the debug mode', 'wordpress-social-login') ?></a>
				</p>
			</div>
		<?php
	}
?>

	<h1>
		WordPress Social Login

		<small><?php echo $WORDPRESS_SOCIAL_LOGIN_VERSION ?></small>
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

<script>
	// check for new versions and updates
	jQuery.getScript("http://miled.github.io/wordpress-social-login/wsl.version.check.and.updates.php?v=<?php echo $WORDPRESS_SOCIAL_LOGIN_VERSION ?>");
</script>
<?php
	// HOOKABLE: 
	do_action( "wsl_admin_ui_footer_end" );

	if( get_option( 'wsl_settings_development_mode_enabled' ) )
	{ 
		wsl_display_dev_mode_debugging_area();
 	}
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
<div id="wsl_div_warn">
	<h3 style="margin:0px;"><?php _wsl_e('Oops! We ran into an issue.', 'wordpress-social-login') ?></h3> 

	<hr />

	<p>
		<?php _wsl_e('Unknown or Disabled <b>Component</b>! Check the list of enabled components or the typed URL', 'wordpress-social-login') ?> .
	</p>

	<p>
		<?php _wsl_e("If you believe you've found a problem with <b>WordPress Social Login</b>, be sure to let us know so we can fix it", 'wordpress-social-login') ?>.
	</p>

	<hr />

	<div>
		<a class="button-secondary" href="http://miled.github.io/wordpress-social-login/support.html" target="_blank"><?php _wsl_e( "Report as bug", 'wordpress-social-login' ) ?></a>
		<a class="button-primary" href="options-general.php?page=wordpress-social-login&wslp=components" style="float:right"><?php _wsl_e( "Check enabled components", 'wordpress-social-login' ) ?></a>
	</div> 
</div>  
<?php
	// HOOKABLE: 
	do_action( "wsl_admin_ui_error_end" );
}

// --------------------------------------------------------------------

/**
* Renders WSL #FAIL page
*/
function wsl_admin_ui_fail()
{
	// HOOKABLE: 
	do_action( "wsl_admin_ui_fail_start" );
?>
<style> 
	<?php 
		wsl_admin_ui_css();
	?>
</style> 

<div class="wsl-container">
	<h1><?php _e("WordPress Social Login - FAIL!", 'wordpress-social-login') ?></h1>

	<hr />

	<p> 
		<?php _e('Despite the efforts, put into <b>WordPress Social Login</b> in terms of reliability, portability, and maintenance by this plugin <a href="http://profiles.wordpress.org/miled/" target="_blank">author</a> and <a href="https://github.com/hybridauth/WordPress-Social-Login/graphs/contributors" target="_blank">contributors</a>', 'wordpress-social-login') ?>. 
		<b style="color:red;"><?php _e('Your server failed the requirements check for this plugin', 'wordpress-social-login') ?>.</b>
	</p> 

	<p> 
		<?php _e('These requirements are usually met by default by most "modern" web hosting providers, however some complications may occur with <b>shared hosting</b> and, or <b>custom wordpress installations</b>', 'wordpress-social-login') ?>.
	</p> 
	<p>
		<?php _wsl_e("The minimum server requirements are", 'wordpress-social-login') ?>:
	</p>
	<ul style="margin-left:60px;">
		<li><?php _wsl_e("PHP >= 5.2.0 installed", 'wordpress-social-login') ?></li> 
		<li><?php _wsl_e("WSL Endpoint URLs reachable", 'wordpress-social-login') ?></li>
		<li><?php _wsl_e("PHP's default SESSION handling", 'wordpress-social-login') ?></li>
		<li><?php _wsl_e("PHP/CURL/SSL Extension enabled", 'wordpress-social-login') ?></li> 
		<li><?php _wsl_e("PHP/JSON Extension enabled", 'wordpress-social-login') ?></li> 
		<li><?php _wsl_e("PHP/REGISTER_GLOBALS Off", 'wordpress-social-login') ?></li> 
		<li><?php _wsl_e("jQuery installed on WordPress backoffice", 'wordpress-social-login') ?></li> 
	</ul>
</div>

<script>
	jQuery.getScript("http://miled.github.io/wordpress-social-login/wsl.version.check.and.updates.php?v=<?php echo $WORDPRESS_SOCIAL_LOGIN_VERSION ?>&fail=true");
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
	if( isset( $_REQUEST["wsldwp"] ) && (int) $_REQUEST["wsldwp"] )
	{
		$wsldwp = (int) $_REQUEST["wsldwp"];

		update_option( "wsl_settings_welcome_panel_enabled", wsl_get_version() );

		return;
	}

	// if new user or wsl updated, then we display wsl welcome panel
	if( get_option( 'wsl_settings_welcome_panel_enabled' ) == wsl_get_version() )
	{ 
		return;
	}
	
	$wslp = "networks"; 

	if( isset( $_REQUEST["wslp"] ) )
	{
		$wslp = $_REQUEST["wslp"];
	}	
?> 
<!-- 
	if you want to know if a UI was made by developer, then here is a tip: he will always use tables

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
					<li><?php _wsl_e('<a href="http://miled.github.io/wordpress-social-login/overview.html?" target="_blank">Plugin Overview</a>', 'wordpress-social-login') ?></li>
					<li><?php _wsl_e('<a href="http://miled.github.io/wordpress-social-login/configure.html?" target="_blank">Setup and Configuration</a>', 'wordpress-social-login') ?></li>
					<li><?php _wsl_e('<a href="http://miled.github.io/wordpress-social-login/customize.html?" target="_blank">Customize WSL Widgets</a>', 'wordpress-social-login') ?></li>
					<li><?php _wsl_e('<a href="http://miled.github.io/wordpress-social-login/userdata.html?" target="_blank">Manage users and contacts</a>', 'wordpress-social-login') ?></li> 
					<!--
					<li><?php _wsl_e('<a href="http://miled.github.io/wordpress-social-login/index.html?" target="_blank">WSL User Guide</a> and <a href="http://miled.github.io/wordpress-social-login/faq.html?" target="_blank">FAQ</a>', 'wordpress-social-login') ?></li>  
					-->
				</ul>
			</td>
			<td width="" valign="top">
				<br />
				<p>
					<b><?php echo sprintf( _wsl__( "What's new on WSL %s", 'wordpress-social-login'), wsl_get_version() ) ?></b>
				</p>

				<!-- 2.2.2
				<ul style="margin-left:25px;">
					<li><?php _wsl_e('WSL can be now fully integrated with your <a href="https://buddypress.org" target="_blank">BuddyPress</a> installation', 'wordpress-social-login') ?>.</li>
					<li><?php _wsl_e('WSL Widget is now more flexible than before and can be <a href="http://miled.github.io/wordpress-social-login/themes.html?" target="_blank">fully customized</a> to fit you website theme', 'wordpress-social-login') ?>.</li>
					<li><?php _wsl_e('WSL is now updated to work with the latest apis changes of the supported social networks', 'wordpress-social-login') ?>.</li>
					<li><?php _wsl_e('Introducing four new providers : <a href="https://wordpress.com" target="_blank">WordPress.com</a>, <a href="https://disqus.com" target="_blank">Disqus</a>, <a href="https://www.reddit.com" target="_blank">Reddit</a>and <a href="http://pixelpin.co.uk/" target="_blank">PixelPin</a>', 'wordpress-social-login') ?>.</li> 
					<li><?php _wsl_e('WSL <a href="http://miled.github.io/wordpress-social-login/developer.html" target="_blank">Hooks</a> has been reworked and few hooks have been depreciated in favour of new ones', 'wordpress-social-login') ?>.</li>
					<li><?php _wsl_e('A number of bugfixes, small enhancements and visual updates', 'wordpress-social-login') ?>.</li>
				</ul>
				 -->
				<ul style="margin-left:25px;">
					<li><?php _wsl_e('Add support for <a href="https://dribbble.com" target="_blank">Dribbble.com</a>', 'wordpress-social-login') ?>.</li> 
					<li><?php _wsl_e('<a href="http://store.steampowered.com/" target="_blank">Steam</a> provider has been reworked to fully support the Steam Web API', 'wordpress-social-login') ?>.</li> 
					<li><?php _wsl_e('lots of other things to be listed here..', 'wordpress-social-login') ?>.</li> 
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
* Renders wsl localization note
*/
function wsl_admin_help_us_localize_note()
{
	return; // nothing, until I decide otherwise.. 

	$assets_url = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . '/assets/img/'; 

	?> 
		<div id="l10n-footer" style="float: left; display: block; ">
			<br style="clear:both;" />
			<hr />
			<img src="<?php echo $assets_url ?>flags.png">
			<a href="options-general.php?page=wordpress-social-login&wslp=help&wslhelp=translate"><?php _wsl_e( "Help us translate WordPress Social Login into your language", 'wordpress-social-login' ) ?></a>
		</div>
	<?php
}

// --------------------------------------------------------------------

/**
* Renders an editor in a page in the typical fashion used in Posts and Pages.
* wp_editor was implemented in wp 3.3. if not found we fallback to a regular textarea
*
* Utility.
*/
function wsl_render_wp_editor( $name, $content )
{
	if( ! function_exists( 'wp_editor' ) )
	{
		?>
			<textarea style="width:100%;height:100px;margin-top:6px;" name="<?php echo $name ?>"><?php echo htmlentities( $content ); ?></textarea> 
		<?php
		return;
	}
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
}

// --------------------------------------------------------------------

/**
* Display WordPress Social Login on settings as submenu 
*/
function wsl_admin_menu()
{
	add_options_page('WP Social Login', 'WP Social Login', 'manage_options', 'wordpress-social-login', 'wsl_admin_init' );

	add_action( 'admin_init', 'wsl_register_setting' );
}

add_action('admin_menu', 'wsl_admin_menu' ); 

// --------------------------------------------------------------------

/**
* Display WordPress Social Login on sidebar 
*/
function wsl_admin_menu_sidebar()
{
	add_menu_page( 'WP Social Login', 'WP Social Login', 'manage_options', 'wordpress-social-login', 'wsl_admin_init' ); 
}
 
add_action('admin_menu', 'wsl_admin_menu_sidebar');

// --------------------------------------------------------------------

/**
* To be moved to a css file.
*/
function wsl_admin_ui_css()
{
?>
.wsl-container { 
	margin: 25px 40px 0 20px; 
	margin-left:20px;
}
.wsl-container .inside {
    font-size: 13px;
    line-height: 1.4em;
}
.wsl-container h1 {
	color: #333333;
	text-shadow: 1px 1px 1px #FFFFFF; 
	font-size: 2.8em;
	font-weight: 200;
	line-height: 1.2em;
	margin: 0.2em 200px 0.6em 0.2em;
}
.wsl-container h2 .nav-tab {
	color: #21759B;
}
.wsl-container h2 .nav-tab-active {
	color: #464646;
	text-shadow: 1px 1px 1px #FFFFFF;
}
.wsl-container hr{ 
	border-color: #EEEEEE;
	border-style: none none solid;
	border-width: 0 0 1px;
	margin: 2px 0 15px;
} 
.wsl-container p{  
	line-height: 1.8em; 
}
.wsl-container .wslpre{ 
	font-size:14m;
	border:1px solid #E6DB55; 
	border-radius: 3px;
	padding:5px;
	width:650px;
}
.wsl-container ul {
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
.wsl-container .span4 {  
	width: 220px; 
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
#wsl_div_warn { 
	padding: 10px;  
	border: 1px solid #ddd; 
	background-color: #fff; 
	width: 55%;
	margin: 0px auto;
	margin-top:30px;
}
.wsl-container .button-danger {
	background-color: #da4f49;
	background-image: linear-gradient(to bottom, #ce3f38, #bd362f);
	border-color: #bd362f;
	border-radius: 3px;
	border-style: solid;
	border-width: 1px;
	box-sizing: border-box;
	color: #fff;
	cursor: pointer;
	display: inline-block;
	float: none;
	font-size: 13px;
	height: 30px;
	margin: 0;
	padding: 5px;
	text-decoration: none;
	text-shadow: 0 1px 0 rgba(0, 0, 0, 0.1);
	white-space: nowrap; 
}
.wsl-container .button-danger:focus, .wsl-container .button-danger:hover{
	background:#d3534c;
	border-color:#ce3f38;
	-webkit-box-shadow:inset 0 1px 0 rgba(206,63,56,.6);
	box-shadow:inset 0 1px 0 rgba(206,63,56,.6);
	color:#fff
}
<?php
}

// --------------------------------------------------------------------
