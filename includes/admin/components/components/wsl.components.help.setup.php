<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*    (c) 2011-2013 Mohamed Mrassi and contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
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
				foreach( $WORDPRESS_SOCIAL_LOGIN_COMPONENTS as $name => $settings ){ 
					if( $name == "core" ){
						continue;
					}
			?>
				<tr id="<?php echo $name ?>" class="<?php echo $name ?> <?php if( $settings["enabled"] ) echo "active"; else  echo "inactive"; ?>"> 
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
					</td>
					<td class="column-action" align="right" style="width: 120px;">
						<p>
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
