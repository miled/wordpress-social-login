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

function wsl_component_users_list()
{
	// HOOKABLE: 
	do_action( "wsl_component_users_list_start" );

	GLOBAL $wpdb;

	$assets_base_url = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . '/assets/img/16x16/';

	$pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
	$limit = 25; // number of rows in page
	$offset = ( $pagenum - 1 ) * $limit;

	$total = $wpdb->get_var( "SELECT COUNT(`id`) FROM {$wpdb->prefix}wslusersprofiles" );
	$num_of_pages = ceil( $total / $limit );

	$sql = "SELECT * FROM `{$wpdb->prefix}wslusersprofiles` GROUP BY user_id LIMIT $offset, $limit";
	$rs1 = $wpdb->get_results( $sql );
?>
<div style="padding: 15px; margin-bottom: 8px; border: 1px solid #ddd; background-color: #fff;box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
	<p style="float: right; margin: -5px;">
		<a class="button button-secondary" href="users.php"><?php _wsl_e("Show all the existing users for your site", 'wordpress-social-login'); ?></a>
	</p>

	<?php _wsl_e( "This screen only list the users who have connected via the WSL widget", 'wordpress-social-login' ) ?>.
</div>

<table cellspacing="0" class="wp-list-table widefat fixed users">
	<thead>
		<tr> 
			<th width="100"><span><?php _wsl_e("Providers", 'wordpress-social-login') ?></span></th>  
			<th width="60"><span><?php _wsl_e("Avatar", 'wordpress-social-login') ?></span></th> 
			<th><span><?php _wsl_e("Username", 'wordpress-social-login') ?></span></th> 
			<th><span><?php _wsl_e("Full Name", 'wordpress-social-login') ?></span></th> 
			<th><span><?php _wsl_e("E-mail", 'wordpress-social-login') ?></span></th> 
			<th><span><?php _wsl_e("Profile URL", 'wordpress-social-login') ?></span></th> 
			<th width="80"><span><?php _wsl_e("Contacts", 'wordpress-social-login') ?></span></th> 
			<th width="140"><span><?php _wsl_e("Actions", 'wordpress-social-login') ?></span></th>
		</tr>
	</thead> 
	<tfoot>
		<tr> 
			<th width="100"><span><?php _wsl_e("Providers", 'wordpress-social-login') ?></span></th>  
			<th width="60"><span><?php _wsl_e("Avatar", 'wordpress-social-login') ?></span></th> 
			<th><span><?php _wsl_e("Username", 'wordpress-social-login') ?></span></th> 
			<th><span><?php _wsl_e("Full Name", 'wordpress-social-login') ?></span></th> 
			<th><span><?php _wsl_e("E-mail", 'wordpress-social-login') ?></span></th> 
			<th><span><?php _wsl_e("Profile URL", 'wordpress-social-login') ?></span></th> 
			<th width="80"><span><?php _wsl_e("Contacts", 'wordpress-social-login') ?></span></th> 
			<th width="140"><span><?php _wsl_e("Actions", 'wordpress-social-login') ?></span></th>
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
					$user_id = $items->user_id; 
					$wsl_user_image = $items->photourl; 

					$user_data = get_userdata( $user_id );
					
					if( ! $user_data ){
						continue;
					}

					$linked_accounts = wsl_get_stored_hybridauth_user_profiles_by_user_id( $user_id );  
				?>
					<tr class="<?php if( ++$i % 2 ) echo "alternate" ?> tr-contacts"> 
						<td>
							<?php 
								foreach( $linked_accounts AS $link ){
								?> 
									<img src="<?php echo $assets_base_url . strtolower( $link->provider ) . '.png' ?>" style="vertical-align:top;width:16px;height:16px;" /> <?php echo $link->provider ?><br />
								<?php
									if( $link->photourl ){
										$wsl_user_image = $link->photourl;
									}
								} 
							?> 
						</td> 
						<td>
							<?php if( $wsl_user_image ) { ?>
								<img width="32" height="32" class="avatar avatar-32 photo" src="<?php echo $wsl_user_image ?>" > 
							<?php } else { ?>
								<img width="32" height="32" class="avatar avatar-32 photo" src="http://www.gravatar.com/avatar/<?php echo md5( strtolower( trim( $user_data->user_email ) ) ); ?>" > 
							<?php } ?>
						</td>
						<td>
							<strong><a href="options-general.php?page=wordpress-social-login&wslp=users&uid=<?php echo $user_id ?>"><?php echo $user_data->user_login; ?></a></strong>
						</td>
						<td><?php echo $user_data->display_name; ?></td>
						<td>
							<?php if( ! strstr( $user_data->user_email, "@example.com" ) ) { ?>
								<a href="mailto:<?php echo $user_data->user_email; ?>"><?php echo $user_data->user_email; ?></a>
							<?php } else { ?>
								-
							<?php } ?>
						</td>
						<td>
							<?php if( $user_data->user_url ) { ?> 
								<a href="<?php echo $user_data->user_url; ?>" target="_blank"><?php echo str_ireplace( array("http://www.", "https://www.", "http://","https://"), array('','','','',''), $user_data->user_url ) ?></a>
							<?php } else { ?>
								-
							<?php } ?>
						</td> 
						<td align="center">
							<?php
								$sql = "SELECT count( * ) as counts FROM `{$wpdb->prefix}wsluserscontacts` where user_id = '$user_id'";
								$rs  = $wpdb->get_results( $sql );

								if( $rs && $rs[0]->counts ){
									?>
										<a href="options-general.php?page=wordpress-social-login&wslp=contacts&uid=<?php echo $user_id ?>"><?php echo $rs[0]->counts; ?></a>
									<?php
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

<?php  
	$page_links = paginate_links( array(
		'base' => add_query_arg( 'pagenum', '%#%' ),
		'format' => '',
		'prev_text' => __( '&laquo;', 'text-domain' ),
		'next_text' => __( '&raquo;', 'text-domain' ),
		'total' => $num_of_pages,
		'current' => $pagenum
	));

	if ( $page_links ) {
		echo '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0">' . $page_links . '</div></div>';
	}
?> 	
<?php
	// HOOKABLE: 
	do_action( "wsl_component_users_list_end" );
}

// --------------------------------------------------------------------	
