<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*    (c) 2011-2013 Mohamed Mrassi and contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 

// --------------------------------------------------------------------

function wsl_component_networks_addmore()
{
	// HOOKABLE: 
	do_action( "wsl_component_networks_addmore_start" );

	GLOBAL $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG;
?>
<div class="postbox " id="linksubmitdiv"> 
	<div class="inside">
		<div id="submitlink" class="submitbox"> <h3 style="cursor: default;"><?php _wsl_e("Add more providers", 'wordpress-social-login') ?></h3>
 

<table width="100%" border="0"> 
  <tr> 
	<td align="left">
		<p><?php _wsl_e("And you could add even more of them, <b>Just Click</b> and we will guide you through", 'wordpress-social-login') ?> :</p>
		<?php 
			$assets_base_url = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . '/assets/img/32x32/icondock/';

			$nb_used = count( $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG );
			foreach( $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG AS $item ){
				$provider_id                = @ $item["provider_id"];
				$provider_name              = @ $item["provider_name"];
				$provider_cat               = @ $item["cat"];

				if( isset( $item["default_network"] ) && $item["default_network"] ){
					continue;
				}

				if( ! get_option( 'wsl_settings_' . $provider_id . '_enabled' ) ){
					?>
						<a href="options-general.php?page=wordpress-social-login&wslp=networks&enable=<?php echo $provider_id ?>#setup<?php echo strtolower( $provider_id ) ?>"><img src="<?php echo $assets_base_url . strtolower( $provider_id ) . '.png' ?>" alt="<?php echo $provider_name ?>" title="<?php echo $provider_name ?>" /></a>
					<?php

					$nb_used--;
				}
			}

			if( $nb_used == count( $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG ) ){
				_wsl_e("Well! none left.", 'wordpress-social-login');
			}
		?> 
	</td>
  </tr> 
</table> 

		</div>
	</div> 
</div>

<?php
	// HOOKABLE: 
	do_action( "wsl_component_networks_addmore_end" );	
} 

// --------------------------------------------------------------------	
