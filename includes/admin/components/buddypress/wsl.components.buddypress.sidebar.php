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
?>
<div class="postbox " id="linksubmitdiv"> 
	<div class="inside">
		<div id="submitlink" class="submitbox"> 
			<h3 style="cursor: default;"><?php _wsl_e("BuddyPress integration", 'wordpress-social-login') ?></h3>
			<div id="minor-publishing">  
				<div id="misc-publishing-actions"> 
					<div style="padding:20px;padding-top:0px;">

						<p style="margin:10px;font-size: 13px;"> 
							<?php _wsl_e( 'WSL can be now fully integrated with your <a href="https://buddypress.org" target="_blank">BuddyPress</a> installation. When enabled, user avatars display should work right out of the box with most WordPress themes and your BuddyPress installation', 'wordpress-social-login') ?>.
						</p> 

						<p style="margin:10px;font-size: 13px;"> 
							<?php _wsl_e( 'WSL also comes with BuddyPress xProfiles mappings. When this feature is enabled, WSL will try to automatically fill in Buddypress users profiles from their social networks profiles', 'wordpress-social-login') ?>.
						</p> 
						
					</div> 
				</div> 
			</div> 
		</div>
	</div>
</div> 
<?php
	// HOOKABLE: 
	do_action( "wsl_component_buddypress_sidebar_end" );
}

// --------------------------------------------------------------------	
