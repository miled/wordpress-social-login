<?php
/*!
* WordPress Social Login
*
* http://miled.github.io/wordpress-social-login/ | https://github.com/miled/wordpress-social-login
*  (c) 2011-2014 Mohamed Mrassi and contributors | http://wordpress.org/plugins/wordpress-social-login/
*/

/**
* BuddyPress integration.
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 

// --------------------------------------------------------------------

function wsl_component_tools_sidebar()
{
	$sections = array(
		'what_is_this' => 'wsl_component_tools_sidebar_what_is_this',
	);

	$sections = apply_filters( 'wsl_component_tools_sidebar_alter_sections', $sections );

	foreach( $sections as $section => $action )
	{
		add_action( 'wsl_component_tools_sidebar_sections', $action );
	}

	// HOOKABLE: 
	do_action( 'wsl_component_tools_sidebar_sections' );
}

// --------------------------------------------------------------------	

function wsl_component_tools_sidebar_what_is_this()
{
?>
<div class="postbox">
	<div class="inside">
		<h3><?php _wsl_e("WordPress Social Login Tools", 'wordpress-social-login') ?></h3>

		<div style="padding:0 20px;">
			<p>
				<?php _wsl_e( 'Here you can found a set of tools to help you find and hopefully fix any issue you may encounter', 'wordpress-social-login') ?>.
			</p>
			<p>
				<?php _wsl_e( 'You can also delete all Wordpress Social Login tables and stored options from the <b>Uninstall</b> section down below', 'wordpress-social-login') ?>.
			</p>
		</div> 
	</div>
</div>
<?php
}

// --------------------------------------------------------------------	
