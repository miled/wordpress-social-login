<form method="post" id="wsl_setup_form" action="options.php"> 
<?php settings_fields( 'wsl-settings-group-bouncer' ); ?> 

<div class="metabox-holder columns-2" id="post-body">

<table width="100%">
<tbody>
<tr valign="top">
<td>
							
<div  id="post-body-content"> 
 
	<div id="namediv" class="stuffbox">
		<h3>
			<label for="name">WSL Widget</label>
		</h3>
		<div class="inside"> 
			<p> 
				Here you can tell Bouncer if you are accepting new users registration and authentication into your website or not any more. Note that Bouncer only works for WSL and will not interfere with users authenticating through the regulars wordpress Login and Register pages with their usernames and passwords (to to achieve that kind of restrictions, you may need to use another plugin(s) in combination with WSL).
			</p> 
			<table width="100%" border="0" cellpadding="5" cellspacing="2" style="border-top:1px solid #ccc;">  
			  <tr>
				<td width="200" align="right"><strong>Accept new registration :</strong></td>
				<td> 
					<select name="wsl_settings_bouncer_registration_enabled">
						<option <?php if( get_option( 'wsl_settings_bouncer_registration_enabled' ) == 1 ) echo "selected"; ?> value="1">Yes</option>
						<option <?php if( get_option( 'wsl_settings_bouncer_registration_enabled' ) == 2 ) echo "selected"; ?> value="2">No</option> 
					</select>
				</td>
			  </tr> 
			  <tr>
				<td width="200" align="right"><strong>Allow authentication :</strong></td>
				<td> 
					<select name="wsl_settings_bouncer_authentication_enabled">
						<option <?php if( get_option( 'wsl_settings_bouncer_authentication_enabled' ) == 1 ) echo "selected"; ?> value="1">Yes</option>
						<option <?php if( get_option( 'wsl_settings_bouncer_authentication_enabled' ) == 2 ) echo "selected"; ?> value="2">No</option> 
					</select>
				</td>
			  </tr> 
			</table>
		</div>
	</div>

	<div id="namediv" class="stuffbox">
		<h3>
			<label for="name">Email validation</label>
		</h3>
		<div class="inside"> 
			<p>
				Some social networks like Twitter and LinkedIn do not provide their user's email. When <b>Email Validation</b> is enabled, Bouncer will ask your users to provide their email address and username. If <b>Email Validation</b> is disabled then a random email will be then generated for them instead.
			</p> 
			<table width="100%" border="0" cellpadding="5" cellspacing="2" style="border-top:1px solid #ccc;">  
			  <tr>
				<td width="200" align="right"><strong>Enabled :</strong></td>
				<td> 
					<select name="wsl_settings_bouncer_email_validation_enabled">
						<option <?php if( get_option( 'wsl_settings_bouncer_email_validation_enabled' ) == 1 ) echo "selected"; ?> value="1">Yes</option>
						<option <?php if( get_option( 'wsl_settings_bouncer_email_validation_enabled' ) == 2 ) echo "selected"; ?> value="2">No</option> 
					</select>
				</td>
			  </tr> 
			  <tr>
				<td width="200" align="right"><strong>Notice text :</strong></td>
				<td> 
					<input type="text" class="inputgnrc"  style="width:100%;" value="<?php echo get_option( 'wsl_settings_bouncer_email_validation_text_notice' ); ?>" name="wsl_settings_bouncer_email_validation_text_notice" >  
				</td>
			  </tr>  
			  <tr>
				<td width="200" align="right"><strong>Submit button text :</strong></td>
				<td> 
					<input type="text" class="inputgnrc"  style="width:100%;" value="<?php echo get_option( 'wsl_settings_bouncer_email_validation_text_submit_button' ); ?>" name="wsl_settings_bouncer_email_validation_text_submit_button" >  
				</td>
			  </tr>  
			  <tr>
				<td width="200" align="right"><strong>Connected with text :</strong></td>
				<td> 
					<input type="text" class="inputgnrc"  style="width:100%;" value="<?php echo get_option( 'wsl_settings_bouncer_email_validation_text_connected_with' ); ?>" name="wsl_settings_bouncer_email_validation_text_connected_with" >  
				</td>
			  </tr>   
			  <tr>
				<td width="200" align="right"><strong>E-mail text :</strong></td>
				<td> 
					<input type="text" class="inputgnrc"  style="width:100%;" value="<?php echo get_option( 'wsl_settings_bouncer_email_validation_text_email' ); ?>" name="wsl_settings_bouncer_email_validation_text_email" >  
				</td>
			  </tr>  
			  <tr>
				<td width="200" align="right"><strong>Username text :</strong></td>
				<td> 
					<input type="text" class="inputgnrc"  style="width:100%;" value="<?php echo get_option( 'wsl_settings_bouncer_email_validation_text_username' ); ?>" name="wsl_settings_bouncer_email_validation_text_username" >  
				</td>
			  </tr>  
			  <tr>
				<td width="200" align="right"><strong>Invalid E-mail error text :</strong></td>
				<td> 
					<input type="text" class="inputgnrc"  style="width:100%;" value="<?php echo get_option( 'wsl_settings_bouncer_email_validation_text_email_invalid' ); ?>" name="wsl_settings_bouncer_email_validation_text_email_invalid" >  
				</td>
			  </tr>  
			  <tr>
				<td width="200" align="right"><strong>Invalid Username error text :</strong></td>
				<td> 
					<input type="text" class="inputgnrc"  style="width:100%;" value="<?php echo get_option( 'wsl_settings_bouncer_email_validation_text_username_invalid' ); ?>" name="wsl_settings_bouncer_email_validation_text_username_invalid" >  
				</td>
			  </tr>  
			  <tr>
				<td width="200" align="right"><strong>Registered E-mail error text :</strong></td>
				<td> 
					<input type="text" class="inputgnrc"  style="width:100%;" value="<?php echo get_option( 'wsl_settings_bouncer_email_validation_text_email_exists' ); ?>" name="wsl_settings_bouncer_email_validation_text_email_exists" >  
				</td>
			  </tr>  
			  <tr>
				<td width="200" align="right"><strong>Registered Username error text :</strong></td>
				<td> 
					<input type="text" class="inputgnrc"  style="width:100%;" value="<?php echo get_option( 'wsl_settings_bouncer_email_validation_text_username_exists' ); ?>" name="wsl_settings_bouncer_email_validation_text_username_exists" >  
				</td>
			  </tr>  
			</table>  
		</div>
	</div>

	<div id="namediv" class="stuffbox">
		<h3>
			<label for="name">Moderation</label>
		</h3>
		<div class="inside"> 
			<p>
				When enabled, new regsitred users will only be able to authenticate and comment but not to access or edit their profile information.
				Basically they will have "No role for this site". Note: Improving this feature is planned but low priority. 
				<span style="color:#CB4B16;"><b>Note</b></span>: If <b>Moderation</b> is enabled then <b>Membership level</b> will be ignored.
			</p> 
			<table width="100%" border="0" cellpadding="5" cellspacing="2" style="border-top:1px solid #ccc;">  
			  <tr>
				<td width="200" align="right"><strong>Enabled :</strong></td>
				<td> 
					<select name="wsl_settings_bouncer_new_users_moderation_enabled">
						<option <?php if( get_option( 'wsl_settings_bouncer_new_users_moderation_enabled' ) == 1 ) echo "selected"; ?> value="1">Yes</option>
						<option <?php if( get_option( 'wsl_settings_bouncer_new_users_moderation_enabled' ) == 2 ) echo "selected"; ?> value="2">No</option> 
					</select>
				</td>
			  </tr>  
			</table>  
		</div>
	</div>

	<div id="namediv" class="stuffbox">
		<h3>
			<label for="name">Membership level</label>
		</h3>
		<div class="inside"> 
			<p>
				Here you can define the default role for new users authenticating through WSL. The <code>Administrator</code> and <code>Editor</code> roles are not available for safety reasons. For more information about wordpress users roles and capabilities refer to <a href="http://codex.wordpress.org/Roles_and_Capabilities#Capability_vs._Role_Table" target="_blank">http://codex.wordpress.org/Roles_and_Capabilities#Capability_vs._Role_Table</a>.  
				<span style="color:#CB4B16;"><b>Note</b></span>: If <b>Moderation</b> is enabled then this option will be ignored.
			</p> 
			<table width="100%" border="0" cellpadding="5" cellspacing="2" style="border-top:1px solid #ccc;">  
			  <tr>
				<td width="200" align="right"><strong>New User Default Role :</strong></td>
				<td> 
					<select name="wsl_settings_bouncer_new_users_membership_default_role">
						<optgroup label="Safe:">
							<option value="default"     <?php if( get_option( 'wsl_settings_bouncer_new_users_membership_default_role' ) == "default" ) echo "selected"; ?> >&mdash; Wordpress User Default Role  &mdash;</option> 
							<option value="subscriber"  <?php if( get_option( 'wsl_settings_bouncer_new_users_membership_default_role' ) == "subscriber" ) echo "selected"; ?> >Subscriber</option> 
						</optgroup>
	
						<optgroup label="Be careful with these:">  
							<option value="author"      <?php if( get_option( 'wsl_settings_bouncer_new_users_membership_default_role' ) == "author" ) echo "selected"; ?> >Author</option>
							<option value="contributor" <?php if( get_option( 'wsl_settings_bouncer_new_users_membership_default_role' ) == "contributor" ) echo "selected"; ?> >Contributor</option> 
						</optgroup>
					</select>  
				</td>
			  </tr>  
			</table>  
		</div>
	</div>
<!-- 
	<div id="namediv" class="stuffbox">
		<h3>
			<label for="name">Passcode</label>
		</h3>
		<div class="inside"> 
			<p>
				When enabled, users will not able to register through WSL unless they provide a <b>Passcode</b>. 
				This will ONLY occur once, when registring, after that users will be able to authenticate seamlessly.
				Also, You can read our <a>online documenation</a> that highlights how this feature works.
			</p> 
			<table width="100%" border="0" cellpadding="5" cellspacing="2" style="border-top:1px solid #ccc;">  
			  <tr>
				<td width="200" align="right"><strong>Enabled :</strong></td>
				<td> 
					<select name="wsl_settings_bouncer_new_users_passcode_enabled">
						<option <?php if( get_option( 'wsl_settings_bouncer_new_users_passcode_enabled' ) == 1 ) echo "selected"; ?> value="1">Yes</option>
						<option <?php if( get_option( 'wsl_settings_bouncer_new_users_passcode_enabled' ) == 2 ) echo "selected"; ?> value="2">No</option> 
					</select>
				</td>
			  </tr>
			  <tr>
				<td width="200" align="right"><strong>Passcode :</strong></td>
				<td> 
					<input type="text" class="inputgnrc"  style="width:100%;" value="<?php echo get_option( 'wsl_settings_bouncer_new_users_passcode' ); ?>" name="wsl_settings_bouncer_new_users_passcode" >  
				</td>
			  </tr>
			  <tr>
				<td width="200" align="right"><strong>Notice text :</strong></td>
				<td> 
					<input type="text" class="inputgnrc"  style="width:100%;" value="<?php echo get_option( 'wsl_settings_bouncer_new_users_passcode_text_notice' ); ?>" name="wsl_settings_bouncer_new_users_passcode_text_notice" >  
				</td>
			  </tr>  
			  <tr>
				<td width="200" align="right"><strong>Passcode text :</strong></td>
				<td> 
					<input type="text" class="inputgnrc"  style="width:100%;" value="<?php echo get_option( 'wsl_settings_bouncer_new_users_passcode_text_passcode' ); ?>" name="wsl_settings_bouncer_new_users_passcode_text_passcode" >  
				</td>
			  </tr>  
			  <tr>
				<td width="200" align="right"><strong>Invalid Passcode error text :</strong></td>
				<td> 
					<input type="text" class="inputgnrc"  style="width:100%;" value="<?php echo get_option( 'wsl_settings_bouncer_new_users_passcode_text_error' ); ?>" name="wsl_settings_bouncer_new_users_passcode_text_error" >  
				</td>
			  </tr>  
			  <tr>
				<td width="200" align="right"><strong>Submit button text :</strong></td>
				<td> 
					<input type="text" class="inputgnrc"  style="width:100%;" value="<?php echo get_option( 'wsl_settings_bouncer_new_users_passcode_text_submit_button' ); ?>" name="wsl_settings_bouncer_new_users_passcode_text_submit_button" >  
				</td>
			  </tr>  
			</table>  
		</div>
	</div>

	<div id="namediv" class="stuffbox">
		<h3>
			<label for="name">Disclaimer, License Agreement, Privacy Policy</label>
		</h3>
		<div class="inside"> 
			<p>
				When enabled, this will add a final step on the registration process and new users won't be able to get in unless they agree with your Disclaimer, License Agreement or Privacy Policy.
			</p> 
			<table width="100%" border="0" cellpadding="5" cellspacing="2" style="border-top:1px solid #ccc;">  
			  <tr> 
				<td width="200" align="right"><strong>Enabled :</strong></td>
				<td> 
					<select name="wsl_settings_bouncer_new_users_agreement_enabled">
						<option <?php if( get_option( 'wsl_settings_bouncer_new_users_agreement_enabled' ) == 1 ) echo "selected"; ?> value="1">Yes</option>
						<option <?php if( get_option( 'wsl_settings_bouncer_new_users_agreement_enabled' ) == 2 ) echo "selected"; ?> value="2">No</option> 
					</select>
				</td>
			  </tr>   
			  <tr>
				<td width="200" align="right" valign="top"><strong>Agreement text :</strong></td>
				<td> 
					<textarea style="width:100%;height:60px;margin-top:6px;" name="wsl_settings_bouncer_new_users_agreement_text"><?php echo get_option( 'wsl_settings_bouncer_new_users_agreement_text' ); ?></textarea> 
				</td>
			  </tr>   
			  <tr>
				<td width="200" align="right"><strong>Agreement button text :</strong></td>
				<td> 
					<input type="text" class="inputgnrc"  style="width:100%;" value="<?php echo get_option( 'wsl_settings_bouncer_agreement_text_submit_button' ); ?>" name="wsl_settings_bouncer_agreement_text_submit_button" >  
				</td>
			  </tr>  
			</table>  
		</div>
	</div>
-->
	<div id="namediv" class="stuffbox">
		<h3>
			<label for="name">Filters by emails domains name</label>
		</h3>
		<div class="inside"> 
			<p>
				Restrict registration to a limited number of domains name. <span style="color:#CB4B16;"><b>Note</b></span>: This filter will only kick in for social networks providing their users emails. <b>Email Validation</b> will not be affected by this.
				Insert one email address per line and try to keep this list short. On <code>Bounce text</code> insert the text you want to display for rejected users. 
				ex: <code>gmail.com</code>, without '@'.
			</p>
			<table width="100%" border="0" cellpadding="5" cellspacing="2" style="border-top:1px solid #ccc;">  
			  <tr>
				<td width="200" align="right"><strong>Enabled :</strong></td>
				<td> 
					<select name="wsl_settings_bouncer_new_users_restrict_domain_enabled">
						<option <?php if( get_option( 'wsl_settings_bouncer_new_users_restrict_domain_enabled' ) == 1 ) echo "selected"; ?> value="1">Yes</option>
						<option <?php if( get_option( 'wsl_settings_bouncer_new_users_restrict_domain_enabled' ) == 2 ) echo "selected"; ?> value="2">No</option> 
					</select>
				</td>
			  </tr>   
			  <tr>
				<td width="200" align="right" valign="top"><strong>Emails list :</strong></td>
				<td>
					<textarea style="width:100%;height:60px;margin-top:6px;" name="wsl_settings_bouncer_new_users_restrict_domain_list"><?php echo get_option( 'wsl_settings_bouncer_new_users_restrict_domain_list' ); ?></textarea> 
				</td>
			  </tr>  
			  <tr>
				<td width="200" align="right" valign="top"><strong>Bounce text :</strong></td>
				<td> 
					<textarea style="width:100%;height:60px;margin-top:6px;" name="wsl_settings_bouncer_new_users_restrict_domain_text_bounce"><?php echo get_option( 'wsl_settings_bouncer_new_users_restrict_domain_text_bounce' ); ?></textarea>  
				</td>
			  </tr>  
			</table>  
		</div>
	</div>

	<div id="namediv" class="stuffbox">
		<h3>
			<label for="name">Filters by e-mails addresses</label>
		</h3>
		<div class="inside"> 
			<p>
				Restrict registration to a limited number of emails addresses.
				<span style="color:#CB4B16;"><b>Note</b></span>: This filter will only kick in for social networks providing their users emails. <b>Email Validation</b> will not be affected by this.
				Insert one email address per line and try to keep this list short. On <code>Bounce text</code> insert the text you want to display for rejected users.
				ex: <code>hybridauth@gmail.com</code>
			</p>
			<table width="100%" border="0" cellpadding="5" cellspacing="2" style="border-top:1px solid #ccc;">  
			  <tr>
				<td width="200" align="right"><strong>Enabled :</strong></td>
				<td> 
					<select name="wsl_settings_bouncer_new_users_restrict_email_enabled">
						<option <?php if( get_option( 'wsl_settings_bouncer_new_users_restrict_email_enabled' ) == 1 ) echo "selected"; ?> value="1">Yes</option>
						<option <?php if( get_option( 'wsl_settings_bouncer_new_users_restrict_email_enabled' ) == 2 ) echo "selected"; ?> value="2">No</option> 
					</select>
				</td>
			  </tr>   
			  <tr>
				<td width="200" align="right" valign="top"><strong>E-mails list :</strong></td>
				<td> 
					<textarea style="width:100%;height:60px;margin-top:6px;" name="wsl_settings_bouncer_new_users_restrict_email_list"><?php echo get_option( 'wsl_settings_bouncer_new_users_restrict_email_list' ); ?></textarea>  
				</td>
			  </tr>  
			  <tr>
				<td width="200" align="right" valign="top"><strong>Bounce text :</strong></td>
				<td> 
					<textarea style="width:100%;height:60px;margin-top:6px;" name="wsl_settings_bouncer_new_users_restrict_email_text_bounce"><?php echo get_option( 'wsl_settings_bouncer_new_users_restrict_email_text_bounce' ); ?></textarea>  
				</td>
			  </tr>  
			</table>  
		</div>
	</div>

	<div id="namediv" class="stuffbox">
		<h3>
			<label for="name">Filters by profile urls</label>
		</h3>
		<div class="inside"> 
			<p>
				Restrict registration to a limited number of profile urls.
				<span style="color:#CB4B16;"><b>Note</b></span>: If a social network provide the user email, then use "Filters by e-mails addresses" instead. Providers like Facebook provide multiples profiles URLs per user and WSL won't be able to reconize them.
				Insert one email address per line and try to keep this list short. On <code>Bounce text</code> insert the text you want to display for rejected users.
				ex: <code>http://twitter.com/HybridAuth</code>, <code>https://plus.google.com/u/0/108839241301472312344</code>
			</p>
			<table width="100%" border="0" cellpadding="5" cellspacing="2" style="border-top:1px solid #ccc;">  
			  <tr>
				<td width="200" align="right"><strong>Enabled :</strong></td>
				<td> 
					<select name="wsl_settings_bouncer_new_users_restrict_profile_enabled">
						<option <?php if( get_option( 'wsl_settings_bouncer_new_users_restrict_profile_enabled' ) == 1 ) echo "selected"; ?> value="1">Yes</option>
						<option <?php if( get_option( 'wsl_settings_bouncer_new_users_restrict_profile_enabled' ) == 2 ) echo "selected"; ?> value="2">No</option> 
					</select>
				</td>
			  </tr>   
			  <tr>
				<td width="200" align="right" valign="top"><strong>Profile urls :</strong></td>
				<td>
					<textarea style="width:100%;height:60px;margin-top:6px;" name="wsl_settings_bouncer_new_users_restrict_profile_list"><?php echo get_option( 'wsl_settings_bouncer_new_users_restrict_profile_list' ); ?></textarea>  
				</td>
			  </tr>  
			  <tr>
				<td width="200" align="right" valign="top"><strong>Bounce text :</strong></td>
				<td> 
					<textarea style="width:100%;height:60px;margin-top:6px;" name="wsl_settings_bouncer_new_users_restrict_profile_text_bounce"><?php echo get_option( 'wsl_settings_bouncer_new_users_restrict_profile_text_bounce' ); ?></textarea>  
				</td>
			  </tr>  
			</table>  
		</div>
	</div>

	<br />
	<div style="margin-left:5px;margin-top:-20px;"> 
		<input type="submit" class="button-primary" value="Save Settings" /> 
	</div>

</div>

</td>
<td width="10"></td>
<td width="400">


<div class="postbox " id="linksubmitdiv"> 
	<div class="inside">
		<div id="submitlink" class="submitbox"> 
			<h3 style="cursor: default;">What's This?</h3>
			<div id="minor-publishing">  
				<div id="misc-publishing-actions"> 
					<div style="padding:20px;padding-top:0px;">
						<h4 style="cursor: default;border-bottom:1px solid #ccc;font-size: 13px;">Hey, meet our friend, the Bouncer</h4>

						<p style="margin:10px;font-size: 13px;" align="justify"> 
						Ever been in trouble with one of <a href="http://www.flickr.com/search/?q=bouncer+doorman&z=e" target="_blank">these guys</a>? 
						Well, this module have more or less the same role, and he will try his best to piss your users off until they meet your requirements.
						</p>

						<p style="margin:10px;font-size: 13px;" align="justify"> 
						This feature is most suited for small businesses and folks running a closed-door blog between friends or coworkers.
						</p>

						<h4 style="cursor: default;border-bottom:1px solid #ccc;">Available settings</h4>
						
						<ul style="margin:30px;margin-top:0px;margin-bottom:0px;">
							<li>Enable/Disable Registration</li>
							<li>Enable/Disable Authentication</li>
							<li>E-mail validation</li>
							<li>Users moderation</li>
							<li>Users roles</li>
							<!-- <li>Registration agreement</li>
							<li>Passcode</li> -->
							<li>Restrictions (by emails, domains, profiles urls)</li> 
						</ul>

						<h4 style="cursor: default;border-bottom:1px solid #ccc;">IMPORTANT!</h4>

						<p style="margin:10px;" align="justify"> 
							All the settings on this page without exception are only valid for users authenticating through <b>WordPress Social Login Widget</b>.
						</p> 
						<p style="margin:10px;" align="justify"> 
						Users authenticating through the regulars Wordpress Login and Register pages with their usernames and passwords WILL NOT be affected.
						</p>
					</div> 
				</div> 
			</div> 
		</div>
	</div>
</div> 

</td>
</tr>
</table>

</div> 


</form>
