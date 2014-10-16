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
		<h3 style="cursor: default;"><?php _wsl_e("WordPress Social Login Tools", 'wordpress-social-login') ?></h3>

		<div style="padding:0 20px;">
			<p>
				<?php _wsl_e( '', 'wordpress-social-login') ?>.
			</p>  
		</div> 
	</div>
</div>
<?php
}

// --------------------------------------------------------------------	
