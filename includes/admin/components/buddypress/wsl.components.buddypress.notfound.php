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

function wsl_component_buddypress_notfound()
{
	// HOOKABLE:
	do_action( "wsl_component_buddypress_notfound_start" );
?> 
	<div class="metabox-holder columns-2" id="post-body">
		<div  id="post-body-content"> 

			<div id="namediv" class="stuffbox">
				<h3>
					<label for="name"><?php _wsl_e("BuddyPress plugin not found!", 'wordpress-social-login') ?></label>
				</h3>
				<div class="inside">
					<p>
						<?php _wsl_e( '<a href="https://buddypress.org/" target="_blank">BuddyPress</a><b style="color:red;font-size: 15px;">*</b> was not found on your website. The plugin is be either not installed or disabled. If you think this is a mistake, please email us at hybridauth@gmail.com or report this issue on the wordpress support website', 'wordpress-social-login' ); ?>.
					</p>

					<p>
						<em><b style="color:red;font-size: 14px;">*</b> <?php _wsl_e( 'BuddyPress is a powerful plugin that takes your WordPress powered site beyond the blog with social-network features like user profiles, activity streams, user groups, and more. For more information, visit https://buddypress.org/', 'wordpress-social-login' ); ?>.<em>
					</p>
				</div>
			</div> 
		
		</div> 
	</div> 
<?php
	// HOOKABLE: 
	do_action( "wsl_component_buddypress_notfound_end" );
}

// --------------------------------------------------------------------	
