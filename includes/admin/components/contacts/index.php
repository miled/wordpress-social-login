<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*    (c) 2011-2014 Mohamed Mrassi and contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

/**
* Wannabe Contact Manager module
*/

// --------------------------------------------------------------------

function wsl_component_contacts()
{
	// HOOKABLE: 
	do_action( "wsl_component_contacts_start" );

	$assets_base_url = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . '/assets/img/16x16/';

	$user_id = isset( $_REQUEST['uid'] ) ? (int) $_REQUEST['uid'] : 0;

	if( ! $user_id ){
?>
	<form method="post" id="wsl_setup_form" action="options.php"> 
	<?php settings_fields( 'wsl-settings-group-contacts-import' ); ?>
	
	<div class="metabox-holder columns-2" id="post-body">
	<div  id="post-body-content"> 

	<div id="namediv" class="stuffbox">
		<div class="inside"> 
			<p>
				<?php _wsl_e("<b>WordPress Social Login</b> is now introducing <b>Contacts Import</b> as a new feature. When enabled, users authenticating through WordPress Social Login will be asked for the authorisation to import their contact list. Note that some social networks do not provide certains of their users information like contacts emails, photos and or profile urls", 'wordpress-social-login') ?>.
			</p>
			<h4><?php _wsl_e("Enable contacts import for", 'wordpress-social-login') ?> :</h4> 
			<table width="100%" border="0" cellpadding="5" cellspacing="2" style="border-top:1px solid #ccc;border-bottom:1px solid #ccc">   
			  <tr>
				<td align="right"><strong>Facebook :</strong></td>
				<td>
					<select name="wsl_settings_contacts_import_facebook" <?php if( ! get_option( 'wsl_settings_Facebook_enabled' ) ) echo "disabled" ?> >
						<option <?php if( get_option( 'wsl_settings_contacts_import_facebook' ) == 1 ) echo "selected"; ?> value="1"><?php _wsl_e("Enabled", 'wordpress-social-login') ?></option>
						<option <?php if( get_option( 'wsl_settings_contacts_import_facebook' ) == 2 ) echo "selected"; ?> value="2"><?php _wsl_e("Disabled", 'wordpress-social-login') ?></option> 
					</select> 
				</td> 
				<td align="right" style="border-left:1px solid #ccc" ><strong>Google :</strong></td>
				<td>
					<select name="wsl_settings_contacts_import_google" <?php if( ! get_option( 'wsl_settings_Google_enabled' ) ) echo "disabled" ?> >
						<option <?php if( get_option( 'wsl_settings_contacts_import_google' ) == 1 ) echo "selected"; ?> value="1"><?php _wsl_e("Enabled", 'wordpress-social-login') ?></option>
						<option <?php if( get_option( 'wsl_settings_contacts_import_google' ) == 2 ) echo "selected"; ?> value="2"><?php _wsl_e("Disabled", 'wordpress-social-login') ?></option> 
					</select> 
				</td> 
				<td align="right" style="border-left:1px solid #ccc" ><strong>Twitter :</strong></td>
				<td>
					<select name="wsl_settings_contacts_import_twitter" <?php if( ! get_option( 'wsl_settings_Twitter_enabled' ) ) echo "disabled" ?> >
						<option <?php if( get_option( 'wsl_settings_contacts_import_twitter' ) == 1 ) echo "selected"; ?> value="1"><?php _wsl_e("Enabled", 'wordpress-social-login') ?></option>
						<option <?php if( get_option( 'wsl_settings_contacts_import_twitter' ) == 2 ) echo "selected"; ?> value="2"><?php _wsl_e("Disabled", 'wordpress-social-login') ?></option> 
					</select> 
				</td>
				<td align="right" style="border-left:1px solid #ccc" ><strong>Windows Live :</strong></td>
				<td>
					<select name="wsl_settings_contacts_import_live" <?php if( ! get_option( 'wsl_settings_Live_enabled' ) ) echo "disabled" ?> >
						<option <?php if( get_option( 'wsl_settings_contacts_import_live' ) == 1 ) echo "selected"; ?> value="1"><?php _wsl_e("Enabled", 'wordpress-social-login') ?></option>
						<option <?php if( get_option( 'wsl_settings_contacts_import_live' ) == 2 ) echo "selected"; ?> value="2"><?php _wsl_e("Disabled", 'wordpress-social-login') ?></option> 
					</select> 
				</td>
				<td align="right" style="border-left:1px solid #ccc" ><strong>LinkedIn :</strong></td>
				<td>
					<select name="wsl_settings_contacts_import_linkedin" <?php if( ! get_option( 'wsl_settings_LinkedIn_enabled' ) ) echo "disabled" ?> >
						<option <?php if( get_option( 'wsl_settings_contacts_import_linkedin' ) == 1 ) echo "selected"; ?> value="1"><?php _wsl_e("Enabled", 'wordpress-social-login') ?></option>
						<option <?php if( get_option( 'wsl_settings_contacts_import_linkedin' ) == 2 ) echo "selected"; ?> value="2"><?php _wsl_e("Disabled", 'wordpress-social-login') ?></option> 
					</select> 
				</td>
			  </tr> 
			</table>  
			<p>
				<b><?php _wsl_e("Notes", 'wordpress-social-login') ?>:</b> 
				<ul style="margin-left:40px;margin-top:0px;">
					<li><?php _wsl_e('To enable contacts import from these social network, you need first to enabled them on the <a href="options-general.php?page=wordpress-social-login&wslp=networks"><b>Networks</b></a> tab and register the required application', 'wordpress-social-login') ?>.</li> 
					<li><?php _wsl_e("<b>WSL</b> will try to import as much information about a user contacts as he was able to pull from the social networks APIs.", 'wordpress-social-login') ?></li> 
					<li><?php _wsl_e('All contacts data are sotred into your database on the table: <code>`wsluserscontacts`</code>', 'wordpress-social-login') ?>.</li> 
				</ul> 
			</p> 
		</div>
	</div>

	<br style="clear:both;" />
	<div style="margin-left:5px;margin-top:-20px;"> 
		<input type="submit" class="button-primary" value="<?php _wsl_e("Save Settings", 'wordpress-social-login') ?>" /> 
	</div>

	</div> 
	</div> 
	</form> 
<?php
		// HOOKABLE: 
		do_action( "wsl_component_contacts_end" );

		return;
	} // No user selected

	//--

	$user_data = get_userdata( $user_id );

	if( ! $user_data ){
		?>
			<div style="padding: 15px; margin-bottom: 8px; border: 1px solid #ddd; background-color: #fff;box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
				<?php _wsl_e( "WordPress user not found!", 'wordpress-social-login' ); ?>. 
			</div>
		<?php

		return;
	}

	$pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
	$limit = 25; // number of rows in page
	$offset = ( $pagenum - 1 ) * $limit;
	$num_of_pages = 0;
	$total = wsl_get_stored_hybridauth_user_contacts_count_by_user_id( $user_id );
	$num_of_pages = ceil( $total / $limit );
?> 
	<div style="padding: 15px; margin-bottom: 8px; border: 1px solid #ddd; background-color: #fff;box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
		<p style="float: right; margin: 0px;margin-top: -4px;">
			<a class="button button-secondary" href="user-edit.php?user_id=<?php echo $user_id ?>"><?php _wsl_e("Edit user details", 'wordpress-social-login'); ?></a>
			<a class="button button-secondary" href="options-general.php?page=wordpress-social-login&wslp=users&uid=<?php echo $user_id ?>"><?php _wsl_e("Show user WSL profiles", 'wordpress-social-login'); ?></a>
		</p>

		<?php echo sprintf( _wsl__("<b>%s</b> contact's list", 'wordpress-social-login'), $user_data->display_name ) ?>.
		<?php echo sprintf( _wsl__("This user have <b>%d</b> contacts in his list in total", 'wordpress-social-login'), $total ) ?>.
	</div>

	<style>
		.widefatop td, .widefatop th { border: 1px solid #DDDDDD; }
		.widefatop th label { font-weight: bold; }  
	</style>

	<h3><?php _wsl_e("Wordpress user profile", 'wordpress-social-login'); ?></h3>

	<table class="wp-list-table widefat widefatop">
		<tr><th width="200"><label><?php _wsl_e("Wordpress User ID", 'wordpress-social-login'); ?></label></th><td><?php echo $user_data->ID; ?></td></tr> 
		<tr><th width="200"><label><?php _wsl_e("Username", 'wordpress-social-login'); ?></label></th><td><?php echo $user_data->user_login; ?></td></tr> 
		<tr><th><label><?php _wsl_e("Display name", 'wordpress-social-login'); ?></label></th><td><?php echo $user_data->display_name; ?></td></tr> 
		<tr><th><label><?php _wsl_e("E-mail", 'wordpress-social-login'); ?></label></th><td><a href="mailto:<?php echo $user_data->user_email; ?>" target="_blank"><?php echo $user_data->user_email; ?></a></td></tr> 
		<tr><th><label><?php _wsl_e("Website", 'wordpress-social-login'); ?></label></th><td><a href="<?php echo $user_data->user_url; ?>" target="_blank"><?php echo $user_data->user_url; ?></a></td></tr>   
		<tr><th><label><?php _wsl_e("Registered", 'wordpress-social-login'); ?></label></th><td><?php echo $user_data->user_registered; ?></td></tr>  
		</tr>
	</table>

	<hr />

	<h3><?php _wsl_e("List of contacts", 'wordpress-social-login'); ?></h3>

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
				$data = wsl_get_stored_hybridauth_user_contacts_by_user_id( $user_id, $offset, $limit );

				// have contacts?
				if( ! $data ){
					?>
						<tr class="no-items"><td colspan="5" class="colspanchange"><?php _wsl_e("No contacts found", 'wordpress-social-login') ?>.</td></tr>
					<?php
				}
				else{
					$i = 0; 
					foreach( $data as $item ){
						?>
							<tr class="<?php if( ++$i % 2 ) echo "alternate" ?>"> 
								<td nowrap>
									<img src="<?php echo $assets_base_url . strtolower( $item->provider ) . '.png' ?>" style="vertical-align:top;width:16px;height:16px;" /> <?php echo $item->provider ?>
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

	if ( $page_links ) {
		echo '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0">' . $page_links . '</div></div>';
	}

	// HOOKABLE: 
	do_action( "wsl_component_contacts_end" );
}

wsl_component_contacts();

// --------------------------------------------------------------------	
