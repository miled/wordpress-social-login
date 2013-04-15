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

function wsl_component_networks_basicinsights()
{
	// HOOKABLE: 
	do_action( "wsl_component_networks_basicinsights_start" );

	GLOBAL $wpdb;

	$sql = "SELECT count( * ) as items FROM `{$wpdb->prefix}users`"; 
	$rs1 = $wpdb->get_results( $sql );  

	$sql = "SELECT count( * ) as items FROM `{$wpdb->prefix}usermeta` where meta_key = 'wsl_user'"; 
	$rs2 = $wpdb->get_results( $sql );  

	$total_users      = (int) $rs1[0]->items;
	$total_users_wsl  = (int) $rs2[0]->items;
	$users_conversion = ( 100 * $total_users_wsl ) / $total_users;

	if( $total_users_wsl && wsl_is_component_enabled( "basicinsights" ) ){
?> 
<div class="postbox " id="linksubmitdiv"> 
	<div class="inside">
		<div id="submitlink" class="submitbox"> 
			<h3 style="cursor: default;"><?php _wsl_e("Insights", 'wordpress-social-login') ?></h3>

				<div id="misc-publishing-actions">
					<div style="padding:20px;padding-top:0px;"> 
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
						<?php 
							$sql = "SELECT meta_value, count( * ) as items FROM `{$wpdb->prefix}usermeta` where meta_key = 'wsl_user' group by meta_value order by items desc ";

							$rs1 = $wpdb->get_results( $sql );  

							$assets_base_url = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . '/assets/img/16x16/';
						?> 
						<h4 style="border-bottom:1px solid #ccc"><?php _wsl_e("By provider", 'wordpress-social-login') ?></h4>
						<table width="90%">
							<?php 
								foreach( $rs1 as $item ){
									if( ! $item->meta_value ) $item->meta_value = "Unknown"; 
								?>
									<tr>
										<td width="60%">
											<img src="<?php echo $assets_base_url . strtolower( $item->meta_value ) . '.png' ?>" style="vertical-align:top;width:16px;height:16px;" /> <?php echo $item->meta_value; ?> 
										</td>
										<td>
											<?php echo $item->items; ?>
										</td>
									</tr>
								<?php
								}
							?> 
							<tr>
								<td align="right">&nbsp;</td><td style="border-top:1px solid #ccc"><b><?php echo $total_users_wsl; ?></b> <?php _wsl_e("WSL users", 'wordpress-social-login') ?></td>
							</tr>
						</table> 
						<?php 
							$sql = "SELECT meta_value, count( * ) as items FROM `{$wpdb->prefix}usermeta` where meta_key = 'wsl_user_gender' group by meta_value order by items desc "; 

							$rs = $wpdb->get_results( $sql ); 
						?>
						<h4 style="border-bottom:1px solid #ccc"><?php _wsl_e("By gender", 'wordpress-social-login') ?></h4>
						<table width="90%">
							<?php
								foreach( $rs as $item ){
									if( ! $item->meta_value ) $item->meta_value = "Unknown";
								?>
									<tr>
										<td width="60%">
											<?php echo ucfirst( $item->meta_value ); ?>
										</td>
										<td>
											<?php echo $item->items; ?>
										</td>
									</tr>
								<?php
								}
							?>
						</table>
						<?php 
							$sql = "SELECT meta_value, count( * ) as items FROM `{$wpdb->prefix}usermeta` where meta_key = 'wsl_user_age' group by meta_value order by items desc limit 21"; 

							$rs = $wpdb->get_results( $sql ); 
						?>
						<h4 style="border-bottom:1px solid #ccc"><?php _wsl_e("By age", 'wordpress-social-login') ?></h4>
						<table width="90%">
							<?php
								$t_ages = 0;
								$n_ages = 0;

								foreach( $rs as $item ){
									if( ! $item->meta_value ){
										$item->meta_value = "Unknown";
									}
									else{
										$t_ages += (int) $item->meta_value;
										$n_ages++;
									}
								?>
									<tr>
										<td width="60%">
											<?php echo $item->meta_value; ?>
										</td>
										<td>
											<?php echo $item->items; ?>
										</td>
									</tr>
								<?php
								}

								if( $n_ages ) $a_ages = $t_ages/$n_ages;
							?>
						</td>
						</tr>
						<tr>
							<td align="right">&nbsp;</td><td style="border-top:1px solid #ccc"><b><?php echo number_format($a_ages, 1, '.', ''); ?></b> <?php _wsl_e("yrs in average", 'wordpress-social-login') ?></td>
						</tr>
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
