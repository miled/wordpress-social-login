<?php
/*!
* WordPress Social Login
*
* http://miled.github.io/wordpress-social-login/ | https://github.com/miled/wordpress-social-login
*  (c) 2011-2015 Mohamed Mrassi and contributors | http://wordpress.org/plugins/wordpress-social-login/
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------

function wsl_component_contacts_list( $user_id )
{
	// HOOKABLE:
	do_action( "wsl_component_contacts_list_start" );

	$assets_base_url = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . 'assets/img/16x16/';

	$user_data = get_userdata( $user_id );

	if( ! $user_data )
	{
		?>
			<div style="padding: 15px; margin-bottom: 8px; border: 1px solid #ddd; background-color: #fff;box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
				<?php _wsl_e( "WordPress user not found!", 'wordpress-social-login' ); ?>.
			</div>
		<?php

		return;
	}

	add_thickbox();

	$pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
	$limit = 50; // number of rows in page
	$offset = ( $pagenum - 1 ) * $limit;
	$num_of_pages = 0;
	$total = wsl_get_stored_hybridauth_user_contacts_count_by_user_id( $user_id );
	$num_of_pages = ceil( $total / $limit );

	$user_contacts = wsl_get_stored_hybridauth_user_contacts_by_user_id( $user_id, $offset, $limit );

	$actions = array(
		'edit_details'  => '<a class="button button-secondary thickbox" href="' . admin_url( 'user-edit.php?user_id=' . $user_id . '&TB_iframe=true&width=1150&height=550' ) . '">' . _wsl__( 'Edit user details', 'wordpress-social-login' ) . '</a>',
		'show_profiles' => '<a class="button button-secondary" href="' . admin_url( 'options-general.php?page=wordpress-social-login&wslp=users&uid=' . $user_id ) . '">' . _wsl__( 'Show user social profiles', 'wordpress-social-login' ) . '</a>',
	);

	// HOOKABLE:
	$actions = apply_filters( 'wsl_component_users_profile_alter_actions_list', $actions, $user_id );

?>
<div style="padding: 15px; margin-bottom: 8px; border: 1px solid #ddd; background-color: #fff;box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
 	<h3 style="margin:0;"><?php echo sprintf( _wsl__("%s's contacts list", 'wordpress-social-login'), $user_data->display_name ); ?></h3>

	<p style="float: <?php if( is_rtl() ) echo 'left'; else echo 'right'; ?>;margin-top:-23px">
		<?php
			echo implode( ' ', $actions );
		?>
	</p>
</div>

<style>
	.widefatop td, .widefatop th { border: 1px solid #DDDDDD; }
	.widefatop th label { font-weight: bold; }
</style>

<table cellspacing="0" class="wp-list-table widefat fixed users">
	<thead>
		<tr>
			<th width="100"><span><?php _wsl_e("Provider", 'wordpress-social-login') ?></span></th>
			<th><span><?php _wsl_e("Contact Name", 'wordpress-social-login') ?></span></th>
			<th><span><?php _wsl_e("Contact Email", 'wordpress-social-login') ?></span></th>
			<th><span><?php _wsl_e("Contact Profile Url", 'wordpress-social-login') ?></span></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th width="100"><span><?php _wsl_e("Provider", 'wordpress-social-login') ?></span></th>
			<th><span><?php _wsl_e("Contact Name", 'wordpress-social-login') ?></span></th>
			<th><span><?php _wsl_e("Contact Email", 'wordpress-social-login') ?></span></th>
			<th><span><?php _wsl_e("Contact Profile Url", 'wordpress-social-login') ?></span></th>
		</tr>
	</tfoot>
	<tbody id="the-list">
		<?php
			$i = 0;

			// have contacts?
			if( ! $user_contacts )
			{
				?>
					<tr class="no-items"><td colspan="4" class="colspanchange"><?php _wsl_e("No contacts found", 'wordpress-social-login') ?>.</td></tr>
				<?php
			}
			else
			foreach( $user_contacts as $item )
			{
				?>
					<tr class="<?php if( ++$i % 2 ) echo "alternate" ?>">
						<td nowrap>
							<img src="<?php echo $assets_base_url . strtolower( $item->provider ) . '.png' ?>" style="vertical-align:top;width:16px;height:16px;" /> <?php _wsl_e($item->provider, 'wordpress-social-login') ?>
						</td>
						<td>
							<?php if( $item->photo_url ) { ?>
								<img width="32" height="32" class="avatar avatar-32 photo" align="middle" src="<?php echo $item->photo_url ?>" >
							<?php } else { ?>
								<img width="32" height="32" class="avatar avatar-32 photo" align="middle" src="http://www.gravatar.com/avatar/<?php echo md5( strtolower( trim( $item->email ) ) ); ?>" >
							<?php } ?>

							<strong><?php echo $item->full_name ? $item->full_name : '-'; ?></strong>
						</td>
						<td>
							<?php if( $item->email ) { ?>
								<a href="mailto:<?php echo $item->email; ?>"><?php echo $item->email; ?></a>
							<?php } else { ?>
								-
							<?php } ?>
						</td>
						<td>
							<?php if( $item->profile_url ) { ?>
								<a href="<?php echo $item->profile_url ?>" target="_blank"><?php echo str_ireplace( array("http://www.", "https://www.", "http://","https://"), array('','','','',''), $item->profile_url ) ?></a>
							<?php } else { ?>
								-
							<?php } ?>
						</td>
					</tr>
				<?php
			}
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
	) );

	if( $page_links )
	{
		echo '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0">' . $page_links . '</div></div>';
	}

	// HOOKABLE:
	do_action( "wsl_component_contacts_list_end" );
}

// --------------------------------------------------------------------
