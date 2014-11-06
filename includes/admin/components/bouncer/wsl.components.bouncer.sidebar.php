<?php
/*!
* WordPress Social Login
*
* http://miled.github.io/wordpress-social-login/ | https://github.com/miled/wordpress-social-login
*  (c) 2011-2014 Mohamed Mrassi and contributors | http://wordpress.org/plugins/wordpress-social-login/
*/

/**
* Widget Customization
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 

// --------------------------------------------------------------------

function wsl_component_bouncer_sidebar()
{
	$sections = array(
		'what_is_this' => 'wsl_component_bouncer_sidebar_what_is_this',
	);

	$sections = apply_filters( 'wsl_component_bouncer_sidebar_alter_sections', $sections );

	foreach( $sections as $section => $action )
	{
		add_action( 'wsl_component_bouncer_sidebar_sidebar_sections', $action );
	}

	// HOOKABLE: 
	do_action( 'wsl_component_bouncer_sidebar_sidebar_sections' );
}

// --------------------------------------------------------------------	

function wsl_component_bouncer_sidebar_what_is_this()
{
?>
<div class="postbox">
	<div class="inside">
		<h3><?php _wsl_e("What's This?", 'wordpress-social-login') ?></h3>

		<div style="padding:0 20px;">
			<h4 style="cursor: default;border-bottom:1px solid #ccc;font-size: 13px;"><?php _wsl_e("Hey, meet our friend, the Bouncer", 'wordpress-social-login') ?></h4>

			<p style="margin:10px;font-size: 13px;">
			<?php _wsl_e('Ever been in trouble with one of <a href="http://www.flickr.com/search/?q=bouncer+doorman&z=e" target="_blank">these guys</a>? Well, this module have more or less the same role, and he will try his best to piss your users off until they meet your requirements.', 'wordpress-social-login') ?>
			</p>

			<p style="margin:10px;font-size: 13px;">
			<?php _wsl_e("This feature is most suited for small businesses and folks running a closed-door blog between friends or coworkers.", 'wordpress-social-login') ?>
			</p>

			<h4 style="cursor: default;border-bottom:1px solid #ccc;"><?php _wsl_e("IMPORTANT!", 'wordpress-social-login') ?></h4>

			<p style="margin:10px;">
				<?php _wsl_e("All the settings on this page without exception are only valid for users authenticating through <b>WordPress Social Login Widget", 'wordpress-social-login') ?></b>.
			</p> 
			<p style="margin:10px;">
			<?php _wsl_e("Users authenticating through the regulars Wordpress Login and Register pages with their usernames and passwords WILL NOT be affected.", 'wordpress-social-login') ?>
			</p>
		</div> 
	</div> 
</div> 
<?php
}

// --------------------------------------------------------------------	
