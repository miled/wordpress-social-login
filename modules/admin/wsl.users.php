<?php 
	$sql = "SELECT meta_value, user_id FROM `{$wpdb->prefix}usermeta` where meta_key = 'wsl_user'";
	$rs1 = $wpdb->get_results( $sql );  
?> 
<div style="margin:20px;margin-top:20px;">
	<!--
	<p class="search-box">
		<label for="user-search-input" class="screen-reader-text">Search Users:</label>
		<input type="search" value="" name="s" id="user-search-input">
		<input type="submit" value="Search Users" class="button" id="search-submit" name="">
	</p>
	<br />
	<br /> 
	--> 
	<table cellspacing="0" class="wp-list-table widefat fixed users">
		<thead>
			<tr> 
				<th width="100"><span>Providers</span></th>  
				<th><span>Username</span></th> 
				<th><span>Full Name</span></th> 
				<th><span>Email</span></th> 
				<th><span>Profile Url</span></th> 
				<th width="100"><span>Nb. Contacts</span></th> 
			</tr>
		</thead> 
		<tfoot>
			<tr> 
				<th><span>Providers</span></th>  
				<th><span>Username</span></th> 
				<th><span>Full Name</span></th> 
				<th><span>Email</span></th> 
				<th><span>Profile Url</span></th> 
				<th><span>Nb. Contacts</span></th> 
			</tr>
		</tfoot> 
		<tbody data-wp-lists="list:user" id="the-list">
			<?php  
				// have users?
				if( ! $rs1 ){
					?>
						<tr class="no-items"><td colspan="6" class="colspanchange">No users found.</td></tr>
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
							<?php $user_email = wsl_get_user_data_by_user_id( "user_email", $user_id); if( $user_email ) { ?>
								<?php if( ! strstr( $user_email, "@example.com" ) ) { ?>
									<a href="mailto:<?php echo $user_email ?>"><?php echo $user_email ?></a>
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
						<td align="right">
							<?php
								$sql = "SELECT count( * ) as counts FROM `{$wpdb->prefix}wsluserscontacts` where user_id = '$user_id'";
								$rs  = $wpdb->get_results( $sql );

								if( $rs && $rs[0]->counts ){
									echo '<b style="color:#CB4B16;">' . $rs[0]->counts . '</b><br /><a href="options-general.php?page=wordpress-social-login&wslp=contacts&uid=' . $user_id . '">Show List</a>';
								}
								else{
									echo "0";
								}
							?>
						</td> 
					</tr> 
			<?php 
					}
				}// have users?
			?> 
		</tbody>
	</table> 
</div>
