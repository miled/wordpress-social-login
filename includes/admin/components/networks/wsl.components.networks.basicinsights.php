<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*    (c) 2011-2014 Mohamed Mrassi and contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 

// --------------------------------------------------------------------

function wsl_component_networks_basicinsights()
{
	// HOOKABLE: 
	do_action( "wsl_component_networks_basicinsights_start" );

	$assets_base_url = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . '/assets/img/16x16/';

	$total_users     = wsl_get_wordpess_users_count();
	$total_users_wsl = wsl_get_wsl_users_count();

	if( $total_users && $total_users_wsl ){
		$users_conversion = ( 100 * $total_users_wsl ) / $total_users;
?> 
<div class="postbox " id="linksubmitdiv"> 
	<div class="inside">
		<div id="submitlink" class="submitbox"> 
			<h3 style="cursor: default;"><?php _wsl_e("Insights", 'wordpress-social-login') ?></h3>

			<div id="misc-publishing-actions">
				<div style="padding:20px;padding-top:0px;"> 
					<!-- Insights - conversions -->
					<h4 style="border-bottom:1px solid #ccc"><?php _wsl_e("Conversions", 'wordpress-social-login') ?></h4>
					<table width="90%"> 
						<tr>
							<td width="60%"><?php _wsl_e("WP users", 'wordpress-social-login') ?></td><td><?php echo $total_users; ?></td>
						</tr>
						<tr>
							<td><?php _wsl_e("WSL users", 'wordpress-social-login') ?></td><td><?php echo $total_users_wsl; ?></td>
						</tr>
						<tr>
							<td><?php _wsl_e("Conversions", 'wordpress-social-login') ?></td><td style="border-top:1px solid #ccc">+<b><?php echo number_format($users_conversion, 2, '.', ''); ?></b> %</td>
						</tr>
					</table>

					<!-- Insights by provider -->
					<?php
						$data = wsl_get_stored_hybridauth_user_profiles_count_by_field( 'provider' );
					?> 
					<h4 style="border-bottom:1px solid #ccc"><?php _wsl_e("By provider", 'wordpress-social-login') ?></h4>
					<table width="90%">
						<?php 
							$total_profiles_wsl = 0;

							foreach( $data as $item ){
							?>
								<tr>
									<td width="60%">
										<img src="<?php echo $assets_base_url . strtolower( $item->provider ) . '.png' ?>" style="vertical-align:top;width:16px;height:16px;" /> <?php echo $item->provider; ?> 
									</td>
									<td>
										<?php echo $item->items; ?>
									</td>
								</tr>
							<?php
								$total_profiles_wsl += (int) $item->items;
							}
						?> 
						<tr>
							<td align="right">&nbsp;</td><td style="border-top:1px solid #ccc"><b><?php echo $total_profiles_wsl; ?></b> <?php _wsl_e("WSL profiles", 'wordpress-social-login') ?></td>
						</tr>
						<tr>
							<td align="right">&nbsp;</td><td><b><?php echo $total_users_wsl; ?></b> <?php _wsl_e("WSL users", 'wordpress-social-login') ?></td>
						</tr>
					</table> 

					<!-- Insights by gender -->
					<?php 
						$data = wsl_get_stored_hybridauth_user_profiles_count_by_field( 'gender' );  
					?>
					<h4 style="border-bottom:1px solid #ccc"><?php _wsl_e("By gender", 'wordpress-social-login') ?></h4>
					<table width="90%">
						<?php
							foreach( $data as $item ){
								if( ! $item->gender ) $item->gender = "Unknown";
							?>
								<tr>
									<td width="60%">
										<?php echo ucfirst( $item->gender ); ?>
									</td>
									<td>
										<?php echo $item->items; ?>
									</td>
								</tr>
							<?php
							}
						?>
					</table>

					<!-- Insights by age -->
					<?php 
						$data = wsl_get_stored_hybridauth_user_profiles_count_by_field( 'age' );
					?>
					<h4 style="border-bottom:1px solid #ccc"><?php _wsl_e("By age", 'wordpress-social-login') ?></h4>
					<table width="90%">
						<?php
							foreach( $data as $item ){
								if( ! $item->age ) $item->age = "Unknown";
							?>
								<tr>
									<td width="60%">
										<?php echo ucfirst( $item->age ); ?>
									</td>
									<td>
										<?php echo $item->items; ?>
									</td>
								</tr>
							<?php
							}
						?>
					</table>
				</div> 
			</div>  
		</div>
	</div>
</div> 
<?php
	}

	// HOOKABLE: 
	do_action( "wsl_component_networks_basicinsights_end" );	
} 

// --------------------------------------------------------------------	
