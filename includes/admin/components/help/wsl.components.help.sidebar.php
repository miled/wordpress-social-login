<?php
/*!
* WordPress Social Login
*
* http://miled.github.io/wordpress-social-login/index.html | http://github.com/hybridauth/WordPress-Social-Login
*    (c) 2011-2014 Mohamed Mrassi and contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

/**
* WSL Tools
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 

// --------------------------------------------------------------------

function wsl_component_help_sidebar()
{
	// HOOKABLE: 
	do_action( "wsl_component_help_sidebar_start" );
?>
<div class="postbox">
	<div class="inside">
		<h3><?php _wsl_e("About WordPress Social Login", 'wordpress-social-login') ?> <?php echo wsl_get_version(); ?></h3>

		<div style="padding:0 ;">
			<p>
				<?php _wsl_e('WordPress Social Login is a free and open source plugin made by the community, for the community', 'wordpress-social-login') ?>.
			</p> 
			<p>
				<?php _wsl_e('Basically, WordPress Social Login allow your website visitors and customers to register and login via social networks such as twitter, facebook and google but it has much more to offer', 'wordpress-social-login') ?>.
			</p> 
			<p>
				<?php _wsl_e('For more information about WordPress Social Login, refer to our online user guide', 'wordpress-social-login') ?>.
			</p> 
		</div> 
	</div> 
</div> 
<div class="postbox">
	<div class="inside">
		<h3><?php _wsl_e("Main features", 'wordpress-social-login') ?></h3>

		<div style="padding:0;margin-left:23px">
			<ul>
				<li><?php _wsl_e("No premium features", 'wordpress-social-login') ?>.</li>
				<li><?php _wsl_e("Absolute privacy of your website users data", 'wordpress-social-login') ?>.</li>
				<li><?php _wsl_e("Wide variety of identities providers", 'wordpress-social-login') ?>.</li>
				<li><?php _wsl_e("A highly customizable and fully extensible widgets", 'wordpress-social-login') ?>.</li>
				<li><?php _wsl_e("Easy-to-use and clean user interface", 'wordpress-social-login') ?>.</li>
				<li><?php _wsl_e("Contacts import from google, facebook, live and linkedin", 'wordpress-social-login') ?>.</li>
				<li><?php _wsl_e("User profiles and contacts management", 'wordpress-social-login') ?>.</li>
				<li><?php _wsl_e("Compatible with WordPress 3+, BuddyPress and bbPress", 'wordpress-social-login') ?>.</li>
				<li><?php _wsl_e("ACL-based security model", 'wordpress-social-login') ?>.</li>
				<li><?php _wsl_e("Provides a direct access to social networks apis", 'wordpress-social-login') ?>.</li>
				<li><?php _wsl_e("Comprehensive documentation", 'wordpress-social-login') ?>.</li>
			</ul>
		</div> 
	</div> 
</div> 
<div class="postbox">
	<div class="inside">
		<h3><?php _wsl_e("Thanks", 'wordpress-social-login') ?></h3>

		<div style="padding:0;">
			<p>
				<?php _wsl_e('Big thanks to everyone who have contributed to this plugin and the to WordPress community by submitting patches, ideas, reviews or by helping in the plugin support forum', 'wordpress-social-login') ?>.
			</p> 
		</div> 
	</div> 
</div> 
<?php
	// HOOKABLE: 
	do_action( "wsl_component_help_sidebar_end" );
}

// --------------------------------------------------------------------	
