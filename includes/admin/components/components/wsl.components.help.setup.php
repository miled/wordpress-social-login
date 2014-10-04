<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*    (c) 2011-2014 Mohamed Mrassi and contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

/**
* Components Manager 
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 

// --------------------------------------------------------------------

function wsl_component_components_setup()
{
	// HOOKABLE: 
	do_action( "wsl_component_components_setup_start" );

	GLOBAL $WORDPRESS_SOCIAL_LOGIN_COMPONENTS;
?>
<div style="padding: 15px; margin-bottom: 8px; border: 1px solid #ddd; background-color: #fff;box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
	<?php _wsl_e( "By default, only the three WSL core components are enabled. You can selectively enable or disable any of the non-core components by using the form below. Your WSL installation will continue to function. However, the features of the disabled components will no longer be accessible", 'wordpress-social-login' ) ?>.
</div>

<form action="" method="post">
	<table class="widefat fixed plugins" cellspacing="0">
		<thead>
			<tr>
				<th scope="col" class="manage-column column-label" style="width: 190px;"><?php _wsl_e( "Component", 'wordpress-social-login' ) ?></th>
				<th scope="col" class="manage-column column-description"><?php _wsl_e( "Description", 'wordpress-social-login' ) ?></th>
				<th scope="col" class="manage-column column-action" style="width: 120px;">&nbsp;</th>
			</tr>
		</thead>

		<tfoot>
			<tr>
				<th scope="col" class="manage-column column-label" style="width: 190px;"><?php _wsl_e( "Component", 'wordpress-social-login' ) ?></th>
				<th scope="col" class="manage-column column-description"><?php _wsl_e( "Description", 'wordpress-social-login' ) ?></th>
				<th scope="col" class="manage-column column-action" style="width: 120px;">&nbsp;</th>
			</tr>
		</tfoot>

		<tbody id="the-list"> 
			<?php
				$plugin_tr_class  = '';
				$plugin_notices   = '';
				$plugin_enablable = true;

				foreach( $WORDPRESS_SOCIAL_LOGIN_COMPONENTS as $name => $settings ){ 
					if( $name == "core" ){
						continue;
					}

					$plugin_tr_class = $settings["enabled"] ? "active" : "inactive"; 

					if( $name == "buddypress" ){
						if( ! function_exists( 'bp_has_profile' ) ){
							$plugin_enablable = false;

							$plugin_tr_class = 'active update';

							$plugin_notices = _wsl__( '<a href="https://buddypress.org/" target="_blank">BuddyPress</a> Plugin was not found on your website', 'wordpress-social-login' );
						}
					}
			?>
				<tr id="<?php echo $name ?>" class="<?php echo $name ?> <?php echo $plugin_tr_class ?>"> 
					<td class="component-label" style="width: 190px;"> &nbsp;
						<?php if( $settings["type"] == "core" ): ?>
							<div class="icon16 icon-generic"></div>
						<?php elseif( $settings["type"] == "plugin" ): ?>
							<div class="icon16 icon-plugins"></div>
						<?php else: ?>
							<div class="icon16 icon-appearance"></div>
						<?php endif; ?>
						
						<strong><?php _wsl_e( $settings["label"], 'wordpress-social-login' ) ?></strong> 
					</td>
					<td class="column-description">
						<p><?php _wsl_e( $settings["description"], 'wordpress-social-login' ) ?></p>
						
						<?php if( $plugin_notices ): ?>
							<p><em><?php echo $plugin_notices ?>.</em></p>
						<?php endif; ?>
					</td>
					<td class="column-action" align="right" style="width: 120px;">
						<p>
							<?php if( $plugin_enablable ): ?>
								<?php if( $settings["type"] == "core" && $settings["enabled"] ): ?> 
									<a class="button-secondary" style="color:#000000" href="options-general.php?page=wordpress-social-login&wslp=<?php echo $name ?>"><?php _wsl_e( "View", 'wordpress-social-login' ) ?></a> 
								<?php endif; ?>

								<?php if( $settings["type"] != "core" ): ?>
									<?php if( $settings["enabled"] ): ?> 
										<a class="button-secondary" href="options-general.php?page=wordpress-social-login&wslp=components&disable=<?php echo $name ?>"><?php _wsl_e( "Disable", 'wordpress-social-login' ) ?></a>
									<?php else: ?>
										<a class="button-primary" style="color:#ffffff" href="options-general.php?page=wordpress-social-login&wslp=components&enable=<?php echo $name ?>"><?php _wsl_e( "Enable", 'wordpress-social-login' ) ?>&nbsp;</a>
									<?php endif; ?>
								<?php endif; ?>
							<?php endif; ?>
							&nbsp;
						</p>
					</td>
				</tr>
			<?php
				} 
			?>
		</tbody>
	</table>
</form> 
<?php
	// HOOKABLE: 
	do_action( "wsl_component_components_setup_end" );
}

// --------------------------------------------------------------------	
