<div style="margin:20px;margin-top:20px;"> 
	<div class="metabox-holder columns-2" id="post-body">
	<div  id="post-body-content"> 
	
	<form method="post" id="wsl_setup_form" action="options.php">  
		<div id="namediv" class="stuffbox">
			<h3>
				<label for="name">Auto share comments</label>
			</h3>
			<div class="inside">
				<?php settings_fields( 'wsl-settings-group-contacts-import' ); ?>
				<p>
					Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. 
				</p> 
				<table width="100%" border="0" cellpadding="5" cellspacing="2" style="border-top:1px solid #ccc;-border-bottom:1px solid #ccc">   
				  <tr>
					<td align="right" width="100"><strong>Facebook :</strong></td>
					<td width="100">
						<select name="wsl_settings_contacts_import_facebook" <?php if( ! get_option( 'wsl_settings_Facebook_enabled' ) ) echo "disabled" ?> >
							<option <?php if( ! get_option( 'wsl_settings_contacts_import_facebook' ) ) echo "selected"; ?> value="0">Disabled</option> 
							<option <?php if(   get_option( 'wsl_settings_contacts_import_facebook' ) ) echo "selected"; ?> value="1">Enabled</option>
						</select> 
					</td>  
					<td rowspan="3" align="left" valign="top"  style="border-left:1px solid #ccc">
						<div style="margin-left:20px;margin-right:20px;">
							<b>Message</b>:<br />
							<textarea style="width:100%;height:60px;margin-top:6px;">{user_name} has commented on {post_url}</textarea>
							<br/>You can customize this message by using these variables: <code>{user_name}</code>, <code>{user_email}</code>, <code>{user_provider}</code>, <code>{user_comment}</code>, <code>{post_url}</code>, <code>{site_url}</code>
						</div>
					</td>
				</tr><tr>
					<td align="right"><strong>Twitter :</strong></td>
					<td>
						<select name="wsl_settings_contacts_import_twitter" <?php if( ! get_option( 'wsl_settings_Twitter_enabled' ) ) echo "disabled" ?> >
							<option <?php if( ! get_option( 'wsl_settings_contacts_import_twitter' ) ) echo "selected"; ?> value="0">Disabled</option> 
							<option <?php if(   get_option( 'wsl_settings_contacts_import_twitter' ) ) echo "selected"; ?> value="1">Enabled</option>
						</select> 
					</td> 
				</tr><tr>
					<td align="right"><strong>LinkedIn :</strong></td>
					<td>
						<select name="wsl_settings_contacts_import_linkedin" <?php if( ! get_option( 'wsl_settings_LinkedIn_enabled' ) ) echo "disabled" ?> >
							<option <?php if( ! get_option( 'wsl_settings_contacts_import_linkedin' ) ) echo "selected"; ?> value="0">Disabled</option> 
							<option <?php if(   get_option( 'wsl_settings_contacts_import_linkedin' ) ) echo "selected"; ?> value="1">Enabled</option>
						</select> 
					</td>
				  </tr> 
				</table> 
			</div>
		</div>
		
		<br style="clear:both;" />
		<div style="margin-left:5px;margin-top:-20px;"> 
			<input type="submit" class="button-primary" value="Save Settings" /> 
		</div>
	</form> 
	
	<br style="clear:both;" />
	<hr />
	
	<form method="post" id="wsl_setup_form" action="options.php">  
		<div id="namediv" class="stuffbox">
			<h3>
				<label for="name">Tweet to Download</label>
			</h3>
			<div class="inside">
				<?php settings_fields( 'wsl-settings-group-contacts-import' ); ?>
				<p>
					Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. 
				</p> 
				<table width="100%" border="0" cellpadding="5" cellspacing="2" style="border-top:1px solid #ccc;border-bottom:1px solid #ccc">   
					<tr>
						<td align="right"><strong>User to follow :</strong></td>
						<td ><input type="text" name="wsl_settings_redirect_url" value="@" class="inputgnrc" style="width:180px;" ></td> 
					</tr> 
					<tr>
						<td align="right" width="185"><strong>Goody url :</strong></td>
						<td ><input type="text" name="wsl_settings_redirect_url" value="<?php echo site_url(); ?>/" class="inputgnrc" style="width:650px;" ></td> 
					</tr> 
					<tr>
						<td align="right" valign="top"><strong>Tweet to send :</strong></td>
						<td >
							<textarea style="width:650px;height:60px;">Just downloaded a super mega awesome file from {site_url} @{user_to_follow} #Tweet2Download</textarea>
							<br/>You can customize this message by using these variables: <code>{user_name}</code>, <code>{site_url}</code>, <code>{user_to_follow}</code>
						</td> 
					</tr> 
				</table> 
				<br style="clear:both;" />
				<br/>
				<div style="margin-left:25px;margin-top:-10px;"> 
					<input type="submit" class="button-primary" value="Generate URL" /> 
				</div>
				<br/>
			</div>
		</div> 
	</form> 

	</div> 
	</div>  
</div> 
