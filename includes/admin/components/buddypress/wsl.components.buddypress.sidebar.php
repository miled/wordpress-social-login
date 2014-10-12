<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*    (c) 2011-2014 Mohamed Mrassi and contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

/**
* BuddyPress integration.
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 

// --------------------------------------------------------------------

function wsl_component_buddypress_sidebar()
{
	// HOOKABLE: 
	do_action( "wsl_component_buddypress_sidebar_start" );

	$sections = array(
		'what_is_this' => 'wsl_component_buddypress_sidebar_what_is_this',
	);

	$sections = apply_filters( 'wsl_component_buddypress_sidebar_alter_sections', $sections );


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
	do_action( "wsl_component_buddypress_sidebar_end" );
}

// --------------------------------------------------------------------	

function wsl_component_buddypress_sidebar_what_is_this()
{
?>
<h3 style="cursor: default;"><?php _wsl_e("BuddyPress integration", 'wordpress-social-login') ?></h3>

<div style="padding:0 20px;">
	<p>
		<?php _wsl_e( 'WSL can be now fully integrated with your <a href="https://buddypress.org" target="_blank">BuddyPress</a> installation. When enabled, user avatars display should work right out of the box with most WordPress themes and your BuddyPress installation', 'wordpress-social-login') ?>.
	</p> 

	<p>
		<?php _wsl_e( 'WSL also comes with BuddyPress xProfiles mappings. When this feature is enabled, WSL will try to automatically fill in Buddypress users profiles from their social networks profiles', 'wordpress-social-login') ?>.
	</p> 
</div> 
<?php
}

add_action( 'wsl_component_buddypress_sidebar_what_is_this', 'wsl_component_buddypress_sidebar_what_is_this' );

// --------------------------------------------------------------------	
