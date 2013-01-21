<div style="margin:20px;margin-top:20px;"> 
<?php
	if( isset( $_REQUEST["uid"] ) && (int) $_REQUEST["uid"] ){
		$user_id = (int) $_REQUEST["uid"];
	}
	else{
?>
	<form method="post" id="wsl_setup_form" action="options.php"> 
	<?php settings_fields( 'wsl-settings-group-contacts-import' ); ?>
	
	<div class="metabox-holder columns-2" id="post-body">
	<div  id="post-body-content"> 

	<div id="namediv" class="stuffbox">
		<h3>
			<label for="name">Settings</label>
		</h3>
		<div class="inside"> 
			<p>
				<b>WordPress Social Login</b> is now introducing <b>Contacts Import</b> as a new feature. When enabled, users authenticating through WordPress Social Login will be asked for the authorisation to import their contact list. Note that some social networks do not provide certains of their users information like contacts emails, photos and or profile urls.
			</p>
			<h4>Enable contacts import for :</h4> 
			<table width="100%" border="0" cellpadding="5" cellspacing="2" style="border-top:1px solid #ccc;border-bottom:1px solid #ccc">   
			  <tr>
				<td align="right"><strong>Facebook :</strong></td>
				<td>
					<select name="wsl_settings_contacts_import_facebook" <?php if( ! get_option( 'wsl_settings_Facebook_enabled' ) ) echo "disabled" ?> >
						<option <?php if( get_option( 'wsl_settings_contacts_import_facebook' ) == 1 ) echo "selected"; ?> value="1">Enabled</option>
						<option <?php if( get_option( 'wsl_settings_contacts_import_facebook' ) == 2 ) echo "selected"; ?> value="2">Disabled</option> 
					</select> 
				</td> 
				<td align="right" style="border-left:1px solid #ccc" ><strong>Google :</strong></td>
				<td>
					<select name="wsl_settings_contacts_import_google" <?php if( ! get_option( 'wsl_settings_Google_enabled' ) ) echo "disabled" ?> >
						<option <?php if( get_option( 'wsl_settings_contacts_import_google' ) == 1 ) echo "selected"; ?> value="1">Enabled</option>
						<option <?php if( get_option( 'wsl_settings_contacts_import_google' ) == 2 ) echo "selected"; ?> value="2">Disabled</option> 
					</select> 
				</td> 
				<td align="right" style="border-left:1px solid #ccc" ><strong>Twitter :</strong></td>
				<td>
					<select name="wsl_settings_contacts_import_twitter" <?php if( ! get_option( 'wsl_settings_Twitter_enabled' ) ) echo "disabled" ?> >
						<option <?php if( get_option( 'wsl_settings_contacts_import_twitter' ) == 1 ) echo "selected"; ?> value="1">Enabled</option>
						<option <?php if( get_option( 'wsl_settings_contacts_import_twitter' ) == 2 ) echo "selected"; ?> value="2">Disabled</option> 
					</select> 
				</td>
				<td align="right" style="border-left:1px solid #ccc" ><strong>Windows Live :</strong></td>
				<td>
					<select name="wsl_settings_contacts_import_live" <?php if( ! get_option( 'wsl_settings_Live_enabled' ) ) echo "disabled" ?> >
						<option <?php if( get_option( 'wsl_settings_contacts_import_live' ) == 1 ) echo "selected"; ?> value="1">Enabled</option>
						<option <?php if( get_option( 'wsl_settings_contacts_import_live' ) == 2 ) echo "selected"; ?> value="2">Disabled</option> 
					</select> 
				</td>
				<td align="right" style="border-left:1px solid #ccc" ><strong>LinkedIn :</strong></td>
				<td>
					<select name="wsl_settings_contacts_import_linkedin" <?php if( ! get_option( 'wsl_settings_LinkedIn_enabled' ) ) echo "disabled" ?> >
						<option <?php if( get_option( 'wsl_settings_contacts_import_linkedin' ) == 1 ) echo "selected"; ?> value="1">Enabled</option>
						<option <?php if( get_option( 'wsl_settings_contacts_import_linkedin' ) == 2 ) echo "selected"; ?> value="2">Disabled</option> 
					</select> 
				</td>
			  </tr> 
			</table>  
			<p>
				<b  style="color:#CB4B16;">Notes:</b> 
				<ul style="margin-left:40px;margin-top:0px;">
					<li>To enable contacts import from these social network, you need first to enabled them on the <a href="options-general.php?page=wordpress-social-login&wslp=networks"><b>Networks</b></a> tab and register the required application.</li> 
					<li><b>WSL</b> will try to import as much information about a user contacts as he was able to pull from the social networks APIs.</li> 
					<li>All contacts data are sotred into your database on the table: <code><?php echo "`{$wpdb->prefix}wsluserscontacts`" ?></code>.</li> 
				</ul> 
			</p> 
		</div>
	</div>

	<br style="clear:both;" />
	<div style="margin-left:5px;margin-top:-20px;"> 
		<input type="submit" class="button-primary" value="Save Settings" /> 
	</div>

	</div> 
	</div> 
	</form> 
	
	<br  style="clear:both;" />
	<hr /> 
	<h3>Users contacts list preview</h3>
<?php
	} // if( isset( $_REQUEST["uid"] ) && (int) $_REQUEST["uid"] ){ 
	
	if( $user_id ){
		$display_name = wsl_get_user_data_by_user_id( "display_name", $user_id );
?> 
	<h3><?php echo $display_name ?> contact's list</h3>
<?php
	}
?> 
<table cellspacing="0" class="wp-list-table widefat fixed users">
	<thead>
		<tr> 
			<th width="100"><span>Provider</span></th>
			<th><span>User</span></th> 
			<th><span>Contact Name</span></th> 
			<th><span>Contact Email</span></th> 
			<th><span>Contact Profile Url</span></th> 
		</tr>
	</thead> 
	<tfoot>
		<tr> 
			<th><span>Provider</span></th>
			<th><span>User</span></th> 
			<th><span>Contact Name</span></th> 
			<th><span>Contact Email</span></th> 
			<th><span>Contact Profile Url</span></th> 
		</tr>
	</tfoot> 
	<tbody id="the-list">
<?php 
	$sql = "SELECT * FROM `{$wpdb->prefix}wsluserscontacts` order by rand() limit 10"; 

	if( $user_id ){
		$sql = "SELECT * FROM `{$wpdb->prefix}wsluserscontacts` WHERE user_id = '$user_id'"; 
	}

	$rs1 = $wpdb->get_results( $sql );  
	
	// have contacts?
	if( ! $rs1 ){
		?>
			<tr class="no-items"><td colspan="5" class="colspanchange">No contacts found.</td></tr>
		<?php
	}
	else{
		$i = 0; 
		foreach( $rs1 as $item ){
			$provider   = $item->provider;
			$user_id    = $item->user_id; 
			$contact_id = $item->id;
?>
			<tr class="<?php if( ++$i % 2 ) echo "alternate" ?>"> 
				<td><img src="<?php $provider = wsl_get_contact_data_by_user_id( "provider", $contact_id); echo $assets_base_url . strtolower( $provider ) . '.png' ?>" style="vertical-align:top;width:16px;height:16px;" /> <?php echo $provider ?></td> 
				<td>
					<?php 
						// check if user exists
						if( wsl_get_user_by_meta_key_and_user_id( "wsl_user", $user_id) ){
					?>
						<?php $wsl_user_image = wsl_get_user_by_meta_key_and_user_id( "wsl_user_image", $user_id); if( $wsl_user_image ) { ?>
							<img width="32" height="32" class="avatar avatar-32 photo" src="<?php echo $wsl_user_image ?>" > 
						<?php } else { ?>
							<img width="32" height="32" class="avatar avatar-32 photo" src="http://1.gravatar.com/avatar/d4ed6debc848ece02976aba03e450d60?s=32" > 
						<?php } ?> 
						<strong><a href="user-edit.php?user_id=<?php echo $user_id ?>"><?php echo wsl_get_user_by_meta_key_and_user_id( "nickname", $user_id) ?></a></strong> 
						(<?php echo wsl_get_user_by_meta_key_and_user_id( "last_name", $user_id) ?> <?php echo wsl_get_user_by_meta_key_and_user_id( "first_name", $user_id) ?>)
					<?php 
						}
						else{
							echo "User removed";
						}
					?>
					<br>
				</td>
				<td>
					<?php $photo_url = wsl_get_contact_data_by_user_id( "photo_url", $contact_id); if( $photo_url ) { ?>
						<img width="32" height="32" class="avatar avatar-32 photo" src="<?php echo $photo_url ?>" > 
					<?php } else { ?>
						<img width="32" height="32" class="avatar avatar-32 photo" src="http://1.gravatar.com/avatar/d4ed6debc848ece02976aba03e450d60?s=32" > 
					<?php } ?>
					<strong><?php echo wsl_get_contact_data_by_user_id( "full_name", $contact_id) ?></strong>
					<br>
				</td> 
				<td>
					<?php $email = wsl_get_contact_data_by_user_id( "email", $contact_id); if( $email ) { ?>
						<a href="mailto:<?php echo $email ?>"><?php echo $email ?></a>
					<?php } else { ?>
						-
					<?php } ?>
				</td>
				<td>
					<?php $profile_url = wsl_get_contact_data_by_user_id( "profile_url", $contact_id); if( $profile_url ) { ?>
						<a href="<?php echo $profile_url ?>" target="_blank"><?php echo str_ireplace( array("http://www.", "https://www.", "http://","https://"), array('','','','',''), $profile_url ) ?></a>
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
</div>
