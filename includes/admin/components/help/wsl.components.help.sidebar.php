<?php
/*!
* WordPress Social Login
*
* https://miled.github.io/wordpress-social-login/ | https://github.com/miled/wordpress-social-login
*   (c) 2011-2020 Mohamed Mrassi and contributors | https://wordpress.org/plugins/wordpress-social-login/
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

		<div style="padding:0 20px;">
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
		<h3><?php _wsl_e("Thanks", 'wordpress-social-login') ?></h3>

		<div style="padding:0 20px;">
			<p>
				<?php _wsl_e('Big thanks to everyone who have contributed to WordPress Social Login by submitting Patches, Ideas, Reviews and by Helping in the support forum', 'wordpress-social-login') ?>.
			</p> 
		</div> 
	</div> 
</div>
<?php
	// HOOKABLE: 
	do_action( "wsl_component_help_sidebar_end" );
}

// --------------------------------------------------------------------	
