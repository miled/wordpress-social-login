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

function wsl_component_users_list()
{
	// HOOKABLE: 
	do_action( "wsl_component_users_list_start" );

	GLOBAL $wpdb;

	$assets_base_url = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . '/assets/img/16x16/';

	$sql = "SELECT meta_value, user_id FROM `{$wpdb->prefix}usermeta` where meta_key = 'wsl_user'";
	$rs1 = $wpdb->get_results( $sql );
?>
<div style="margin-top:20px;">
	<table cellspacing="0" class="wp-list-table widefat fixed users">
		<thead>
			<tr> 
				<th width="100"><span><?php _wsl_e("Providers", 'wordpress-social-login') ?></span></th>  
				<th><span><?php _wsl_e("Username", 'wordpress-social-login') ?></span></th> 
				<th><span><?php _wsl_e("Full Name", 'wordpress-social-login') ?></span></th> 
				<th><span><?php _wsl_e("E-mail", 'wordpress-social-login') ?></span></th> 
				<th><span><?php _wsl_e("Profile URL", 'wordpress-social-login') ?></span></th> 
				<th width="60"><span><?php _wsl_e("Contacts", 'wordpress-social-login') ?></span></th> 
				<th width="140"><span><?php _wsl_e("Actions", 'wordpress-social-login') ?></span></th>
			</tr>
		</thead> 
		<tfoot>
			<tr> 
				<th width="100"><span><?php _wsl_e("Providers", 'wordpress-social-login') ?></span></th>  
				<th><span><?php _wsl_e("Username", 'wordpress-social-login') ?></span></th> 
				<th><span><?php _wsl_e("Full Name", 'wordpress-social-login') ?></span></th> 
				<th><span><?php _wsl_e("E-mail", 'wordpress-social-login') ?></span></th> 
				<th><span><?php _wsl_e("Profile URL", 'wordpress-social-login') ?></span></th> 
				<th><span><?php _wsl_e("Contacts", 'wordpress-social-login') ?></span></th>
				<th><span><?php _wsl_e("Actions", 'wordpress-social-login') ?></span></th>
			</tr>
		</tfoot> 
		<tbody data-wp-lists="list:user" id="the-list">
			<?php  
				// have users?
				if( ! $rs1 ){
					?>
						<tr class="no-items"><td colspan="6" class="colspanchange"><?php _wsl_e("No users found", 'wordpress-social-login') ?>.</td></tr>
					<?php
				}
				else{
					$i = 0;
					foreach( $rs1 as $items ){
						$provider = $items->meta_value; 
						$user_id = $items->user_id; 
			?>
					<tr class="<?php if( ++$i % 2 ) echo "alternate" ?> tr-contacts"> 
						<td>
							<img src="<?php echo $assets_base_url . strtolower( $provider ) . '.png' ?>" style="vertical-align:top;width:16px;height:16px;" /> <?php echo $provider ?>
							<?php
								# linked accounts
								$linked_accounts = wsl_get_user_linked_account_by_user_id( $user_id );

								foreach( $linked_accounts AS $link ){
									if( $link->provider != $provider ){
										?> 
											<br />
											<img src="<?php echo $assets_base_url . strtolower( $link->provider ) . '.png' ?>" style="vertical-align:top;width:16px;height:16px;" /> <?php echo $link->provider ?>
										<?php
									}
								} 
							?> 
						</td> 
						<td>
							<?php $wsl_user_image = wsl_get_user_by_meta_key_and_user_id( "wsl_user_image", $user_id); if( $wsl_user_image ) { ?>
								<img width="32" height="32" class="avatar avatar-32 photo" src="<?php echo $wsl_user_image ?>" > 
							<?php } else { ?>
								<img width="32" height="32" class="avatar avatar-32 photo" src="http://1.gravatar.com/avatar/d4ed6debc848ece02976aba03e450d60?s=32" > 
							<?php } ?>
							<strong><a href="user-edit.php?user_id=<?php echo $user_id ?>"><?php echo wsl_get_user_by_meta_key_and_user_id( "nickname", $user_id) ?></a></strong>
							<br>
						</td>
						<td><?php echo wsl_get_user_by_meta_key_and_user_id( "last_name", $user_id) ?> <?php echo wsl_get_user_by_meta_key_and_user_id( "first_name", $user_id) ?></td>
						<td>
							<?php $user_wsl_email = wsl_get_user_data_by_user_id( "user_wsl_email", $user_id); if( $user_wsl_email ) { ?>
								<?php if( ! strstr( $user_wsl_email, "@example.com" ) ) { ?>
									<a href="mailto:<?php echo $user_wsl_email ?>"><?php echo $user_wsl_email ?></a>
								<?php } else { ?>
									-
								<?php } ?>
							<?php } ?>
						</td>
						<td>
							<?php $user_url = wsl_get_user_data_by_user_id( "user_url", $user_id); if( $user_url ) { ?> 
								<a href="<?php echo $user_url ?>" target="_blank"><?php echo str_ireplace( array("http://www.", "https://www.", "http://","https://"), array('','','','',''), $user_url ) ?></a>
							<?php } else { ?>
								-
							<?php } ?>
						</td> 
						<td align="center">
							<?php
								$sql = "SELECT count( * ) as counts FROM `{$wpdb->prefix}wsluserscontacts` where user_id = '$user_id'";
								$rs  = $wpdb->get_results( $sql );

								if( $rs && $rs[0]->counts ){
									echo '<b style="color:#CB4B16;">' . $rs[0]->counts . '</b>';
								}
								else{
									echo "0";
								}
							?>
						</td> 
						<td>
							<a class="button button-secondary" href="options-general.php?page=wordpress-social-login&wslp=users&uid=<?php echo $user_id ?>">Profile</a>
							<a class="button button-secondary" href="options-general.php?page=wordpress-social-login&wslp=contacts&uid=<?php echo $user_id ?>">Contacts</a>
						</td> 
					</tr> 
			<?php 
					}
				}// have users?
			?> 
		</tbody>
	</table> 
</div>
<?php
	// HOOKABLE: 
	do_action( "wsl_component_users_list_end" );
}

// --------------------------------------------------------------------	
