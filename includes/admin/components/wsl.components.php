<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*   (c) 2013 Mohamed Mrassi and other contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

/**
* Components Manager 
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 

// --------------------------------------------------------------------

add_thickbox();
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
<br />
<h2><?php _wsl_e( "Other Components available", 'wordpress-social-login' ) ?></h2>

<style>
	.wsl_addon_div{
		width: 350px; 
		height: 125px; 
		padding: 10px; 
		border: 1px solid #ddd; 
		background-color: #fff;
		float:left;
		margin-bottom: 20px;
		margin-right: 20px;
		
		position: relative;
	}
	
.wsl_addon_div .button-secondary {
    bottom: 8px;
    left: 8px;
    position: absolute; 
}

.wsl_addon_div .button-primary {
    bottom: 8px;
    right: 8px;
    position: absolute;  
}
</style>

<div class="wsl_addon_div">
	<h3 style="margin:0px;"><?php _wsl_e( "WordPress Social Login for BuddyPress", 'wordpress-social-login' ) ?></h3>
	<hr />
	<p><?php _wsl_e( "Make WordPress Social Login compatible with BuddyPress", 'wordpress-social-login' ) ?>.</p> 
	<p><?php _wsl_e( "Widget integration, xProfiles mapping and more", 'wordpress-social-login' ) ?>.</p> 
	<div>
		<a class="button button-primary thickbox" href="plugin-install.php?tab=plugin-information&plugin=wsl-buddypress&TB_iframe=true"><?php _wsl_e( "Install Now", 'wordpress-social-login' ) ?></a>
		<a class="button button-secondary" href="http://wordpress.org/extend/plugins/wsl-buddypress/" target="_blank"><?php _wsl_e( "Visit plugin site", 'wordpress-social-login' ) ?></a> 
	</div>
</div>

<div class="wsl_addon_div">
	<h3 style="margin:0px;"><?php _wsl_e( "Build yours", 'wordpress-social-login' ) ?></h3>
	<hr />
	<p><?php _wsl_e( "Looking to build your own custom <b>WordPress Social Login</b> extenstion or component? Well, it's pretty easy. Just RTFM :)", 'wordpress-social-login' ) ?></p>
 
	<div>
		<a class="button button-primary"   href="http://hybridauth.sourceforge.net/wsl/developer.html" target="_blank"><?php _wsl_e( "WSL Developer API", 'wordpress-social-login' ) ?></a> 
		<a class="button button-secondary" href="https://github.com/hybridauth/WordPress-Social-Login" target="_blank"><?php _wsl_e( "WSL on Github", 'wordpress-social-login' ) ?></a> 
	</div>
</div> 
