<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*    (c) 2011-2014 Mohamed Mrassi and contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

/**
* Widget Customization
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 

// --------------------------------------------------------------------

function wsl_component_loginwidget_sidebar()
{
	// HOOKABLE: 
	do_action( "wsl_component_loginwidget_sidebar_start" );

	$sections = array(
		'what_is_this'        => 'wsl_component_loginwidget_sidebar_what_is_this',
		'auth_widget_preview' => 'wsl_component_loginwidget_sidebar_auth_widget_preview',
		'custom_integration'  => 'wsl_component_loginwidget_sidebar_custom_integration',
	);

	$sections = apply_filters( 'wsl_component_loginwidget_sidebar_alter_sections', $sections );

	foreach( $sections as $section => $action )
	{
		?>
			<div class="postbox">
				<div class="inside">
					<?php
						do_action( $action . '_start' );

						do_action( $action );

						do_action( $action . '_end' );
					?>
				</div>
			</div>
		<?php
	}

	// HOOKABLE: 
	do_action( "wsl_component_loginwidget_sidebar_end" );
}

// --------------------------------------------------------------------	

function wsl_component_loginwidget_sidebar_what_is_this()
{
?>
<h3 style="cursor: default;"><?php _wsl_e("Widget Customization", 'wordpress-social-login') ?></h3>

<div style="padding:0 20px;">
	<p>
		<?php _wsl_e("On this section you can fully customize <b>WordPress Social Login Widget</b> and define the way you want it to look and behave", 'wordpress-social-login') ?>.
	</p>

	<p>
		<?php _wsl_e("<b>WordPress Social Login Widget</b> will be generated into the comments, login and register forms enabling your website vistors and customers to login via social networks", 'wordpress-social-login') ?>.
	</p>

	<p>
		<?php _wsl_e('For more information about the WSL Widget, refer to the online userguide <b><a href="http://miled.github.io/wordpress-social-login/widget.html" target="_blank">Widget Customization</a></b> and <b><a href="http://miled.github.io/wordpress-social-login/themes.html" target="_blank">Widget Themes</a></b>', 'wordpress-social-login') ?>. 
	</p>
</div> 
<?php
}

add_action( 'wsl_component_loginwidget_sidebar_what_is_this', 'wsl_component_loginwidget_sidebar_what_is_this' );

// --------------------------------------------------------------------	

function wsl_component_loginwidget_sidebar_auth_widget_preview()
{
?>
<h3 style="cursor: default;"><?php _wsl_e("Widget preview", 'wordpress-social-login') ?></h3>

<div style="padding:0 20px;">
	<p>
		<?php _wsl_e("This is a preview of what should be on the comments area", 'wordpress-social-login') ?>. 
		<br />

		<strong><?php _wsl_e("Do not try to connect with the Widget here, it won't work", 'wordpress-social-login') ?></strong>.
	</p>

	<div style="width: 380px; padding: 10px; border: 1px solid #ddd; background-color: #fff;">
		<?php echo wsl_render_login_form(); ?>
	</div> 
</div> 
<?php
}

add_action( 'wsl_component_loginwidget_sidebar_auth_widget_preview', 'wsl_component_loginwidget_sidebar_auth_widget_preview' );

// --------------------------------------------------------------------	

function wsl_component_loginwidget_sidebar_custom_integration()
{
?>
<h3 style="cursor: default;"><?php _wsl_e("Custom integration", 'wordpress-social-login') ?></h3>

<div style="padding:0 20px;">
	<p>
		<?php _wsl_e("If this widget does not show up on your custom theme or if you want to add it to another location in your website, you can insert the following code in that location", 'wordpress-social-login') ?>: 
		<pre style="width: 380px;background-color: #FFFFE0;border:1px solid #E6DB55; border-radius: 3px;padding: 10px;margin-top:15px;margin-left:10px;"> &lt;?php do_action( 'wordpress_social_login' ); ?&gt; </pre> 
		<?php _wsl_e("For posts and pages, you may used this shortcode", 'wordpress-social-login') ?>:
		<pre style="width: 380px;background-color: #EDEFF4;border:1px solid #6B84B4; border-radius: 3px;padding: 10px;margin-top:15px;margin-left:10px;">[wordpress_social_login]</pre> 
	</p>

	<p>
		<b><?php _wsl_e('Note', 'wordpress-social-login') ?>:</b>
		<br />
		<?php _wsl_e('WSL Widget will only show up for non connected users', 'wordpress-social-login') ?>.
	</p>

	<p>
		<b><?php _wsl_e('Tip', 'wordpress-social-login') ?>:</b>
		<br />
		<?php _wsl_e('You can use <a href="http://wordpress.org/extend/plugins/html-javascript-adder/" target="_blank">HTML Javascript Adder</a> plugin in combination with WSL to display the Widget in your website sidebar by using the shortcode [wordpress_social_login]', 'wordpress-social-login') ?>.
	</p>
</div> 
<?php
}

add_action( 'wsl_component_loginwidget_sidebar_custom_integration', 'wsl_component_loginwidget_sidebar_custom_integration' );

// --------------------------------------------------------------------	
