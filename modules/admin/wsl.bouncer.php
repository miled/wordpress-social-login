<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*   (c) 2013 Mohamed Mrassi and other contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

/**
* The Bouncer our friend whos trying to be funneh
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;  
?>

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
			<label for="name"><?php _e("WSL Widget", 'wordpress-social-login') ?></label>
		</h3>
		<div class="inside"> 
			<p> 
				<?php _e("Here you can tell Bouncer if you are accepting new users registration and authentication into your website or not any more. Note that Bouncer only works for WSL and will not interfere with users authenticating through the regulars wordpress Login and Register pages with their usernames and passwords (to to achieve that kind of restrictions, you may need to use another plugin(s) in combination with WSL).", 'wordpress-social-login') ?>
			</p> 
			<table width="100%" border="0" cellpadding="5" cellspacing="2" style="border-top:1px solid #ccc;">  
			  <tr>
				<td width="200" align="right"><strong><?php _e("Accept new registration", 'wordpress-social-login') ?> :</strong></td>
				<td> 
					<select name="wsl_settings_bouncer_registration_enabled">
						<option <?php if( get_option( 'wsl_settings_bouncer_registration_enabled' ) == 1 ) echo "selected"; ?> value="1"><?php _e("Yes", 'wordpress-social-login') ?></option>
						<option <?php if( get_option( 'wsl_settings_bouncer_registration_enabled' ) == 2 ) echo "selected"; ?> value="2"><?php _e("No", 'wordpress-social-login') ?></option> 
					</select>
				</td>
			  </tr> 
			  <tr>
				<td width="200" align="right"><strong><?php _e("Allow authentication", 'wordpress-social-login') ?> :</strong></td>
				<td> 
					<select name="wsl_settings_bouncer_authentication_enabled">
						<option <?php if( get_option( 'wsl_settings_bouncer_authentication_enabled' ) == 1 ) echo "selected"; ?> value="1"><?php _e("Yes", 'wordpress-social-login') ?></option>
						<option <?php if( get_option( 'wsl_settings_bouncer_authentication_enabled' ) == 2 ) echo "selected"; ?> value="2"><?php _e("No", 'wordpress-social-login') ?></option> 
					</select>
				</td>
			  </tr> 
			</table>
		</div>
	</div>

	<div id="namediv" class="stuffbox">
		<h3>
			<label for="name"><?php _e("Email validation", 'wordpress-social-login') ?></label>
		</h3>
		<div class="inside hideinside"> 
			<p>
				<?php _e("Some social networks like Twitter and LinkedIn do not provide their user's email. When <b>Email Validation</b> is enabled, Bouncer will ask your users to provide their email address and username. If <b>Email Validation</b> is disabled then a random email will be then generated for them instead.", 'wordpress-social-login') ?>
			</p> 
			<table width="100%" border="0" cellpadding="5" cellspacing="2" style="border-top:1px solid #ccc;">  
			  <tr>
				<td width="200" align="right"><strong><?php _e("Enabled", 'wordpress-social-login') ?> :</strong></td>
				<td> 
					<select name="wsl_settings_bouncer_email_validation_enabled">
						<option <?php if( get_option( 'wsl_settings_bouncer_email_validation_enabled' ) == 1 ) echo "selected"; ?> value="1"><?php _e("Yes", 'wordpress-social-login') ?></option>
						<option <?php if( get_option( 'wsl_settings_bouncer_email_validation_enabled' ) == 2 ) echo "selected"; ?> value="2"><?php _e("No", 'wordpress-social-login') ?></option> 
					</select>
				</td>
			  </tr> 
			  <tr>
				<td width="200" align="right"><strong><?php _e("Notice text", 'wordpress-social-login') ?> :</strong></td>
				<td> 
					<input type="text" class="inputgnrc"  style="width:100%;" value="<?php echo get_option( 'wsl_settings_bouncer_email_validation_text_notice' ); ?>" name="wsl_settings_bouncer_email_validation_text_notice" >  
				</td>
			  </tr>  
			  <tr>
				<td width="200" align="right"><strong><?php _e("Submit button text", 'wordpress-social-login') ?> :</strong></td>
				<td> 
					<input type="text" class="inputgnrc"  style="width:100%;" value="<?php echo get_option( 'wsl_settings_bouncer_email_validation_text_submit_button' ); ?>" name="wsl_settings_bouncer_email_validation_text_submit_button" >  
				</td>
			  </tr>  
			  <tr>
				<td width="200" align="right"><strong><?php _e("Connected with text", 'wordpress-social-login') ?> :</strong></td>
				<td> 
					<input type="text" class="inputgnrc"  style="width:100%;" value="<?php echo get_option( 'wsl_settings_bouncer_email_validation_text_connected_with' ); ?>" name="wsl_settings_bouncer_email_validation_text_connected_with" >  
				</td>
			  </tr>   
			  <tr>
				<td width="200" align="right"><strong><?php _e("E-mail text", 'wordpress-social-login') ?> :</strong></td>
				<td> 
					<input type="text" class="inputgnrc"  style="width:100%;" value="<?php echo get_option( 'wsl_settings_bouncer_email_validation_text_email' ); ?>" name="wsl_settings_bouncer_email_validation_text_email" >  
				</td>
			  </tr>  
			  <tr>
				<td width="200" align="right"><strong><?php _e("Username text", 'wordpress-social-login') ?> :</strong></td>
				<td> 
					<input type="text" class="inputgnrc"  style="width:100%;" value="<?php echo get_option( 'wsl_settings_bouncer_email_validation_text_username' ); ?>" name="wsl_settings_bouncer_email_validation_text_username" >  
				</td>
			  </tr>  
			  <tr>
				<td width="200" align="right"><strong><?php _e("Invalid E-mail error text", 'wordpress-social-login') ?> :</strong></td>
				<td> 
					<input type="text" class="inputgnrc"  style="width:100%;" value="<?php echo get_option( 'wsl_settings_bouncer_email_validation_text_email_invalid' ); ?>" name="wsl_settings_bouncer_email_validation_text_email_invalid" >  
				</td>
			  </tr>  
			  <tr>
				<td width="200" align="right"><strong><?php _e("Invalid Username error text", 'wordpress-social-login') ?> :</strong></td>
				<td> 
					<input type="text" class="inputgnrc"  style="width:100%;" value="<?php echo get_option( 'wsl_settings_bouncer_email_validation_text_username_invalid' ); ?>" name="wsl_settings_bouncer_email_validation_text_username_invalid" >  
				</td>
			  </tr>  
			  <tr>
				<td width="200" align="right"><strong><?php _e("Registered E-mail error text", 'wordpress-social-login') ?> :</strong></td>
				<td> 
					<input type="text" class="inputgnrc"  style="width:100%;" value="<?php echo get_option( 'wsl_settings_bouncer_email_validation_text_email_exists' ); ?>" name="wsl_settings_bouncer_email_validation_text_email_exists" >  
				</td>
			  </tr>  
			  <tr>
				<td width="200" align="right"><strong><?php _e("Registered Username error text", 'wordpress-social-login') ?> :</strong></td>
				<td> 
					<input type="text" class="inputgnrc"  style="width:100%;" value="<?php echo get_option( 'wsl_settings_bouncer_email_validation_text_username_exists' ); ?>" name="wsl_settings_bouncer_email_validation_text_username_exists" >  
				</td>
			  </tr>  
			</table>  
		</div>
	</div>

	<div id="namediv" class="stuffbox">
		<h3>
			<label for="name"><?php _e("Moderation", 'wordpress-social-login') ?></label>
		</h3>
		<div class="inside hideinside"> 
			<p>
				<?php _e("When enabled, new regsitred users will only be able to authenticate and comment but not to access or edit their profile information.", 'wordpress-social-login') ?>
				<?php _e("Basically they will have 'No role for this site'. <b>Note</b> : If <b>Moderation</b> is enabled then <b>Membership level</b> will be ignored.", 'wordpress-social-login') ?>
			</p> 
			<table width="100%" border="0" cellpadding="5" cellspacing="2" style="border-top:1px solid #ccc;">  
			  <tr>
				<td width="200" align="right"><strong><?php _e("Enabled", 'wordpress-social-login') ?> :</strong></td>
				<td> 
					<select name="wsl_settings_bouncer_new_users_moderation_enabled">
						<option <?php if( get_option( 'wsl_settings_bouncer_new_users_moderation_enabled' ) == 1 ) echo "selected"; ?> value="1"><?php _e("Yes", 'wordpress-social-login') ?></option>
						<option <?php if( get_option( 'wsl_settings_bouncer_new_users_moderation_enabled' ) == 2 ) echo "selected"; ?> value="2"><?php _e("No", 'wordpress-social-login') ?></option> 
					</select>
				</td>
			  </tr>  
			</table>  
		</div>
	</div>

	<div id="namediv" class="stuffbox">
		<h3>
			<label for="name"><?php _e("Membership level", 'wordpress-social-login') ?></label>
		</h3>
		<div class="inside hideinside"> 
			<p>
				<?php _e("Here you can define the default role for new users authenticating through WSL. The <code>Administrator</code> and <code>Editor</code> roles are not available for safety reasons. For more information about wordpress users roles and capabilities refer to http://codex.wordpress.org/Roles_and_Capabilities#Capability_vs._Role_Table.", 'wordpress-social-login') ?>
				<?php _e("<b>Note</b>: If <b>Moderation</b> is enabled then this option will be ignored.", 'wordpress-social-login') ?> 
			</p> 
			<table width="100%" border="0" cellpadding="5" cellspacing="2" style="border-top:1px solid #ccc;">  
			  <tr>
				<td width="200" align="right"><strong><?php _e("New User Default Role", 'wordpress-social-login') ?> :</strong></td>
				<td> 
					<select name="wsl_settings_bouncer_new_users_membership_default_role">
						<optgroup label="<?php _e("Safe", 'wordpress-social-login') ?>:">
							<option value="default"     <?php if( get_option( 'wsl_settings_bouncer_new_users_membership_default_role' ) == "default" ) echo "selected"; ?> ><?php _e("&mdash; Wordpress User Default Role  &mdash;", 'wordpress-social-login') ?></option> 
							<option value="subscriber"  <?php if( get_option( 'wsl_settings_bouncer_new_users_membership_default_role' ) == "subscriber" ) echo "selected"; ?> ><?php _e("Subscriber", 'wordpress-social-login') ?></option> 
						</optgroup>

						<optgroup label="<?php _e("Be careful with these", 'wordpress-social-login') ?>:">  
							<option value="author"      <?php if( get_option( 'wsl_settings_bouncer_new_users_membership_default_role' ) == "author" ) echo "selected"; ?> ><?php _e("Author", 'wordpress-social-login') ?></option>
							<option value="contributor" <?php if( get_option( 'wsl_settings_bouncer_new_users_membership_default_role' ) == "contributor" ) echo "selected"; ?> ><?php _e("Contributor", 'wordpress-social-login') ?></option> 
						</optgroup>
					</select>  
				</td>
			  </tr>  
			</table>  
		</div>
	</div>

	<div id="namediv" class="stuffbox">
		<h3>
			<label for="name"><?php _e("Filters by emails domains name", 'wordpress-social-login') ?></label>
		</h3>
		<div class="inside hideinside"> 
			<p>
				<?php _e("Restrict registration to a limited number of domains name.", 'wordpress-social-login') ?>
				<?php _e("Insert one email address per line and try to keep this list short. On <code>Bounce text</code> insert the text you want to display for rejected users. ex: <code>gmail.com</code>, without '@'.", 'wordpress-social-login') ?>
			</p>
			<table width="100%" border="0" cellpadding="5" cellspacing="2" style="border-top:1px solid #ccc;">  
			  <tr>
				<td width="200" align="right"><strong><?php _e("Enabled", 'wordpress-social-login') ?> :</strong></td>
				<td> 
					<select name="wsl_settings_bouncer_new_users_restrict_domain_enabled">
						<option <?php if( get_option( 'wsl_settings_bouncer_new_users_restrict_domain_enabled' ) == 1 ) echo "selected"; ?> value="1"><?php _e("Yes", 'wordpress-social-login') ?></option>
						<option <?php if( get_option( 'wsl_settings_bouncer_new_users_restrict_domain_enabled' ) == 2 ) echo "selected"; ?> value="2"><?php _e("No", 'wordpress-social-login') ?></option> 
					</select>
				</td>
			  </tr>   
			  <tr>
				<td width="200" align="right" valign="top"><strong><?php _e("Emails list", 'wordpress-social-login') ?> :</strong></td>
				<td>
					<textarea style="width:100%;height:60px;margin-top:6px;" name="wsl_settings_bouncer_new_users_restrict_domain_list"><?php echo get_option( 'wsl_settings_bouncer_new_users_restrict_domain_list' ); ?></textarea> 
				</td>
			  </tr>  
			  <tr>
				<td width="200" align="right" valign="top"><strong><?php _e("Bounce text", 'wordpress-social-login') ?> :</strong></td>
				<td> 
					<textarea style="width:100%;height:60px;margin-top:6px;" name="wsl_settings_bouncer_new_users_restrict_domain_text_bounce"><?php echo get_option( 'wsl_settings_bouncer_new_users_restrict_domain_text_bounce' ); ?></textarea>  
				</td>
			  </tr>  
			</table>  
		</div>
	</div>

	<div id="namediv" class="stuffbox">
		<h3>
			<label for="name"><?php _e("Filters by e-mails addresses", 'wordpress-social-login') ?></label>
		</h3>
		<div class="inside hideinside"> 
			<p>
				<?php _e("Restrict registration to a limited number of emails addresses.", 'wordpress-social-login') ?> 
				<?php _e("Insert one email address per line and try to keep this list short. On <code>Bounce text</code> insert the text you want to display for rejected users. ex: <code>hybridauth@gmail.com</code>", 'wordpress-social-login') ?> 
			</p>
			<table width="100%" border="0" cellpadding="5" cellspacing="2" style="border-top:1px solid #ccc;">  
			  <tr>
				<td width="200" align="right"><strong><?php _e("Enabled", 'wordpress-social-login') ?> :</strong></td>
				<td> 
					<select name="wsl_settings_bouncer_new_users_restrict_email_enabled">
						<option <?php if( get_option( 'wsl_settings_bouncer_new_users_restrict_email_enabled' ) == 1 ) echo "selected"; ?> value="1"><?php _e("Yes", 'wordpress-social-login') ?></option>
						<option <?php if( get_option( 'wsl_settings_bouncer_new_users_restrict_email_enabled' ) == 2 ) echo "selected"; ?> value="2"><?php _e("No", 'wordpress-social-login') ?></option> 
					</select>
				</td>
			  </tr>   
			  <tr>
				<td width="200" align="right" valign="top"><strong><?php _e("E-mails list", 'wordpress-social-login') ?> :</strong></td>
				<td> 
					<textarea style="width:100%;height:60px;margin-top:6px;" name="wsl_settings_bouncer_new_users_restrict_email_list"><?php echo get_option( 'wsl_settings_bouncer_new_users_restrict_email_list' ); ?></textarea>  
				</td>
			  </tr>  
			  <tr>
				<td width="200" align="right" valign="top"><strong><?php _e("Bounce text", 'wordpress-social-login') ?> :</strong></td>
				<td> 
					<textarea style="width:100%;height:60px;margin-top:6px;" name="wsl_settings_bouncer_new_users_restrict_email_text_bounce"><?php echo get_option( 'wsl_settings_bouncer_new_users_restrict_email_text_bounce' ); ?></textarea>  
				</td>
			  </tr>  
			</table>  
		</div>
	</div>

	<div id="namediv" class="stuffbox">
		<h3>
			<label for="name"><?php _e("Filters by profile urls", 'wordpress-social-login') ?></label>
		</h3>
		<div class="inside hideinside"> 
			<p>
				<?php _e("Restrict registration to a limited number of profile urls.", 'wordpress-social-login') ?>
				<?php _e("<b>Note</b>: If a social network provide the user email, then use 'Filters by e-mails addresses' instead. Providers like Facebook provide multiples profiles URLs per user and WSL won't be able to reconize them.", 'wordpress-social-login') ?>
				<?php _e("Insert one email address per line and try to keep this list short. On <code>Bounce text</code> insert the text you want to display for rejected users. ex: <code>http://twitter.com/HybridAuth</code>, <code>https://plus.google.com/u/0/108839241301472312344</code>", 'wordpress-social-login') ?>
			</p>
			<table width="100%" border="0" cellpadding="5" cellspacing="2" style="border-top:1px solid #ccc;">  
			  <tr>
				<td width="200" align="right"><strong><?php _e("Enabled", 'wordpress-social-login') ?> :</strong></td>
				<td> 
					<select name="wsl_settings_bouncer_new_users_restrict_profile_enabled">
						<option <?php if( get_option( 'wsl_settings_bouncer_new_users_restrict_profile_enabled' ) == 1 ) echo "selected"; ?> value="1"><?php _e("Yes", 'wordpress-social-login') ?></option>
						<option <?php if( get_option( 'wsl_settings_bouncer_new_users_restrict_profile_enabled' ) == 2 ) echo "selected"; ?> value="2"><?php _e("No", 'wordpress-social-login') ?></option> 
					</select>
				</td>
			  </tr>   
			  <tr>
				<td width="200" align="right" valign="top"><strong><?php _e("Profile urls", 'wordpress-social-login') ?> :</strong></td>
				<td>
					<textarea style="width:100%;height:60px;margin-top:6px;" name="wsl_settings_bouncer_new_users_restrict_profile_list"><?php echo get_option( 'wsl_settings_bouncer_new_users_restrict_profile_list' ); ?></textarea>  
				</td>
			  </tr>  
			  <tr>
				<td width="200" align="right" valign="top"><strong><?php _e("Bounce text", 'wordpress-social-login') ?> :</strong></td>
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
			<h3 style="cursor: default;"><?php _e("What's This?", 'wordpress-social-login') ?></h3>
			<div id="minor-publishing">  
				<div id="misc-publishing-actions"> 
					<div style="padding:20px;padding-top:0px;">
						<h4 style="cursor: default;border-bottom:1px solid #ccc;font-size: 13px;"><?php _e("Hey, meet our friend, the Bouncer", 'wordpress-social-login') ?></h4>

						<p style="margin:10px;font-size: 13px;" align="justify"> 
						<?php _e('Ever been in trouble with one of <a href="http://www.flickr.com/search/?q=bouncer+doorman&z=e" target="_blank">these guys</a>? Well, this module have more or less the same role, and he will try his best to piss your users off until they meet your requirements.', 'wordpress-social-login') ?>
						</p>

						<p style="margin:10px;font-size: 13px;" align="justify"> 
						<?php _e("This feature is most suited for small businesses and folks running a closed-door blog between friends or coworkers.", 'wordpress-social-login') ?>
						</p>

						<h4 style="cursor: default;border-bottom:1px solid #ccc;"><?php _e("Available settings", 'wordpress-social-login') ?></h4>
						
						<ul style="margin:30px;margin-top:0px;margin-bottom:0px;">
							<li><?php _e("Enable/Disable Registration", 'wordpress-social-login') ?></li>
							<li><?php _e("Enable/Disable Authentication", 'wordpress-social-login') ?></li>
							<li><?php _e("E-mail validation", 'wordpress-social-login') ?></li>
							<li><?php _e("Users moderation", 'wordpress-social-login') ?></li>
							<li><?php _e("Users roles", 'wordpress-social-login') ?></li> 
							<li><?php _e("Restrictions (by emails, domains, profiles urls)", 'wordpress-social-login') ?></li> 
						</ul>

						<h4 style="cursor: default;border-bottom:1px solid #ccc;"><?php _e("IMPORTANT!", 'wordpress-social-login') ?></h4>

						<p style="margin:10px;" align="justify"> 
							<?php _e("All the settings on this page without exception are only valid for users authenticating through <b>WordPress Social Login Widget", 'wordpress-social-login') ?></b>.
						</p> 
						<p style="margin:10px;" align="justify"> 
						<?php _e("Users authenticating through the regulars Wordpress Login and Register pages with their usernames and passwords WILL NOT be affected.", 'wordpress-social-login') ?>
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
