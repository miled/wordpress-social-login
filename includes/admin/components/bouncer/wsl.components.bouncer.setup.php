<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*    (c) 2011-2013 Mohamed Mrassi and contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

/**
* Widget Customization
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 

// --------------------------------------------------------------------

function wsl_component_bouncer_setup()
{
	// HOOKABLE:
	do_action( "wsl_component_bouncer_setup_start" );
?>
<div  id="post-body-content"> 
 
	<div id="namediv" class="stuffbox">
		<h3>
			<label for="name"><?php _wsl_e("WSL Widget", 'wordpress-social-login') ?></label>
		</h3>
		<div class="inside"> 
			<p> 
				<?php _wsl_e("Here you can tell Bouncer if you are accepting new users registration and authentication into your website or not any more. Note that Bouncer only works for WSL and will not interfere with users authenticating through the regulars wordpress Login and Register pages with their usernames and passwords (to to achieve that kind of restrictions, you may need to use another plugin(s) in combination with WSL).", 'wordpress-social-login') ?>
			</p> 
			<table width="100%" border="0" cellpadding="5" cellspacing="2" style="border-top:1px solid #ccc;">  
			  <tr>
				<td width="200" align="right"><strong><?php _wsl_e("Accept new registration", 'wordpress-social-login') ?> :</strong></td>
				<td> 
					<select name="wsl_settings_bouncer_registration_enabled">
						<option <?php if( get_option( 'wsl_settings_bouncer_registration_enabled' ) == 1 ) echo "selected"; ?> value="1"><?php _wsl_e("Yes", 'wordpress-social-login') ?></option>
						<option <?php if( get_option( 'wsl_settings_bouncer_registration_enabled' ) == 2 ) echo "selected"; ?> value="2"><?php _wsl_e("No", 'wordpress-social-login') ?></option> 
					</select>
				</td>
			  </tr> 
			  <tr>
				<td width="200" align="right"><strong><?php _wsl_e("Allow authentication", 'wordpress-social-login') ?> :</strong></td>
				<td> 
					<select name="wsl_settings_bouncer_authentication_enabled">
						<option <?php if( get_option( 'wsl_settings_bouncer_authentication_enabled' ) == 1 ) echo "selected"; ?> value="1"><?php _wsl_e("Yes", 'wordpress-social-login') ?></option>
						<option <?php if( get_option( 'wsl_settings_bouncer_authentication_enabled' ) == 2 ) echo "selected"; ?> value="2"><?php _wsl_e("No", 'wordpress-social-login') ?></option> 
					</select>
				</td>
			  </tr> 
			</table>
		</div>
	</div>

	<div id="namediv" class="stuffbox">
		<h3>
			<label for="name"><?php _wsl_e("Profile Completion", 'wordpress-social-login') ?></label>
		</h3>
		<div class="inside hideinside"> 
			<p>
				<?php _wsl_e("Select required fields. If a social network doesn't return them, Bouncer will then ask your visitors to fill additional form to provide them when registering.", 'wordpress-social-login') ?> 
			</p>
			<p class="description">
				<?php _wsl_e("You may activate <b>Profile Completion</b> for both <b>E-mail</b> and <b>Username</b>, but keep in mind, the idea behind <b>social login</b> is to avoid forms and remove all the hassle of registration", 'wordpress-social-login') ?>.
			</p>
			<table width="100%" border="0" cellpadding="5" cellspacing="2" style="border-top:1px solid #ccc;">  
			  <tr>
				<td width="200" align="right"><strong><?php _wsl_e("Require E-mail", 'wordpress-social-login') ?> :</strong></td>
				<td> 
					<select name="wsl_settings_bouncer_profile_completion_require_email">
						<option <?php if( get_option( 'wsl_settings_bouncer_profile_completion_require_email' ) == 1 ) echo "selected"; ?> value="1"><?php _wsl_e("Yes", 'wordpress-social-login') ?></option>
						<option <?php if( get_option( 'wsl_settings_bouncer_profile_completion_require_email' ) == 2 ) echo "selected"; ?> value="2"><?php _wsl_e("No", 'wordpress-social-login') ?></option> 
					</select>
				</td>
			  </tr>
			  <tr>
				<td width="200" align="right"><strong><?php _wsl_e("Allow Username change", 'wordpress-social-login') ?> :</strong></td>
				<td>
					<select name="wsl_settings_bouncer_profile_completion_change_username">
						<option <?php if( get_option( 'wsl_settings_bouncer_profile_completion_change_username' ) == 1 ) echo "selected"; ?> value="1"><?php _wsl_e("Yes", 'wordpress-social-login') ?></option>
						<option <?php if( get_option( 'wsl_settings_bouncer_profile_completion_change_username' ) == 2 ) echo "selected"; ?> value="2"><?php _wsl_e("No", 'wordpress-social-login') ?></option> 
					</select>
				</td>
			  </tr>
			</table>  
		</div>
	</div>

	<div id="namediv" class="stuffbox">
		<h3>
			<label for="name"><?php _wsl_e("User Moderation", 'wordpress-social-login') ?></label>
		</h3>
		<div class="inside hideinside"> 
			<p> 
				<?php _wsl_e("<b>User Moderation</b> will define how <b>Bouncer</b> should behave with new regsitred users:", 'wordpress-social-login') ?>
			</p>
			<ul style="margin-left:30px">
				<li><?php _wsl_e("<b>None</b>: No moderation required.", 'wordpress-social-login') ?></li>
				<li><?php _wsl_e('<b>E-mail Confirmation</b>: New users will need to be confirm their e-mail address before they may log in', 'wordpress-social-login') ?>.</li>
				<li><?php _wsl_e('<b>Admin Approval</b>: New users will need to be approved by an administrator before they may log in', 'wordpress-social-login') ?>.</li>
			</ul> 
			<p class="description">
				<?php _wsl_e('Both <b>Admin Approval</b> and <b>E-mail Confirmation</b> requires <a href="http://wordpress.org/extend/plugins/theme-my-login/" target="_blank">Theme My Login</a> plugin to be installed. As there is no point for <b>WordPress Social Login</b> to reinvent the wheel', 'wordpress-social-login') ?>.
			</p> 
			<p class="description">
				<?php _wsl_e('<b>User Moderation</b> was purposely made compatible with the <a href="http://wordpress.org/extend/plugins/theme-my-login/" target="_blank">Theme My Login</a> for a number reasons: That plugin is good at what he does, a hell of a lot of people are using it and many have asked for it', 'wordpress-social-login') ?>.
			</p> 
			<table width="100%" border="0" cellpadding="5" cellspacing="2" style="border-top:1px solid #ccc;">  
			  <tr>
				<td width="200" align="right"><strong><?php _wsl_e("User Moderation", 'wordpress-social-login') ?> :</strong></td>
				<td> 
					<select name="wsl_settings_bouncer_new_users_moderation_level">
						<option <?php if( get_option( 'wsl_settings_bouncer_new_users_moderation_level' ) == 1 )   echo "selected"; ?> value="1"><?php _wsl_e("None", 'wordpress-social-login') ?></option> 
						<option <?php if( get_option( 'wsl_settings_bouncer_new_users_moderation_level' ) == 101 ) echo "selected"; ?> value="101"><?php _wsl_e("E-mail Confirmation &mdash; Yield to Theme My Login plugin", 'wordpress-social-login') ?></option> 
						<option <?php if( get_option( 'wsl_settings_bouncer_new_users_moderation_level' ) == 102 ) echo "selected"; ?> value="102"><?php _wsl_e("Admin Approval &mdash; Yield to Theme My Login plugin", 'wordpress-social-login') ?></option> 
					</select>
				</td>
			  </tr>
			</table>
		</div>
	</div>

	<div id="namediv" class="stuffbox">
		<h3>
			<label for="name"><?php _wsl_e("Membership level", 'wordpress-social-login') ?></label>
		</h3>
		<div class="inside hideinside"> 
			<p>
				<?php _wsl_e('Here you can define the default role for new users authenticating through WSL. The <code>Administrator</code> and <code>Editor</code> roles are not available for safety reasons', 'wordpress-social-login') ?>.
			</p> 
			<p>
				<?php _wsl_e('For more information about wordpress users roles and capabilities refer to <a href="http://codex.wordpress.org/Roles_and_Capabilities#Capability_vs._Role_Table" target="_blank">http://codex.wordpress.org/Roles_and_Capabilities</a>', 'wordpress-social-login') ?>.
			</p> 
			<p>
				<?php _wsl_e('If <b>User Moderation</b> is set to <code>Admin Approval</code> then <b>Membership level</b> will be ignored', 'wordpress-social-login') ?>.
			</p> 
			<table width="100%" border="0" cellpadding="5" cellspacing="2" style="border-top:1px solid #ccc;">  
			  <tr>
				<td width="200" align="right"><strong><?php _wsl_e("New User Default Role", 'wordpress-social-login') ?> :</strong></td>
				<td> 
					<select name="wsl_settings_bouncer_new_users_membership_default_role">
						<optgroup label="<?php _wsl_e("Safe", 'wordpress-social-login') ?>:">
							<option value="default"     <?php if( get_option( 'wsl_settings_bouncer_new_users_membership_default_role' ) == "default" ) echo "selected"; ?> ><?php _wsl_e("&mdash; Wordpress User Default Role &mdash;", 'wordpress-social-login') ?></option> 
							<option value="wslnorole"   <?php if( get_option( 'wsl_settings_bouncer_new_users_membership_default_role' ) == "wslnorole" ) echo "selected"; ?> ><?php _wsl_e("&mdash; No role for this site  &mdash;", 'wordpress-social-login') ?></option> 
							<option value="subscriber"  <?php if( get_option( 'wsl_settings_bouncer_new_users_membership_default_role' ) == "subscriber" ) echo "selected"; ?> ><?php _wsl_e("Subscriber", 'wordpress-social-login') ?></option> 
						</optgroup>

						<optgroup label="<?php _wsl_e("Be careful with these", 'wordpress-social-login') ?>:">  
							<option value="author"      <?php if( get_option( 'wsl_settings_bouncer_new_users_membership_default_role' ) == "author" ) echo "selected"; ?> ><?php _wsl_e("Author", 'wordpress-social-login') ?></option>
							<option value="contributor" <?php if( get_option( 'wsl_settings_bouncer_new_users_membership_default_role' ) == "contributor" ) echo "selected"; ?> ><?php _wsl_e("Contributor", 'wordpress-social-login') ?></option> 
						</optgroup>
					</select>  
				</td>
			  </tr>  
			</table>  
		</div>
	</div>

	<div id="namediv" class="stuffbox">
		<h3>
			<label for="name"><?php _wsl_e("Filters by emails domains name", 'wordpress-social-login') ?></label>
		</h3>
		<div class="inside hideinside">
			<p>
				<?php _wsl_e("Restrict registration to a limited number of domains name.", 'wordpress-social-login') ?>
				<?php _wsl_e("Insert one email address per line and try to keep this list short. On <code>Bounce text</code> insert the text you want to display for rejected users. ex: <code>gmail.com</code>, without '@'.", 'wordpress-social-login') ?>
			</p>
			<table width="100%" border="0" cellpadding="5" cellspacing="2" style="border-top:1px solid #ccc;">  
			  <tr>
				<td width="200" align="right"><strong><?php _wsl_e("Enabled", 'wordpress-social-login') ?> :</strong></td>
				<td> 
					<select name="wsl_settings_bouncer_new_users_restrict_domain_enabled">
						<option <?php if( get_option( 'wsl_settings_bouncer_new_users_restrict_domain_enabled' ) == 1 ) echo "selected"; ?> value="1"><?php _wsl_e("Yes", 'wordpress-social-login') ?></option>
						<option <?php if( get_option( 'wsl_settings_bouncer_new_users_restrict_domain_enabled' ) == 2 ) echo "selected"; ?> value="2"><?php _wsl_e("No", 'wordpress-social-login') ?></option> 
					</select>
				</td>
			  </tr>   
			  <tr>
				<td width="200" align="right" valign="top"><p><strong><?php _wsl_e("Domains list", 'wordpress-social-login') ?> :</strong></p></td>
				<td>
					<textarea style="width:100%;height:60px;margin-top:6px;" name="wsl_settings_bouncer_new_users_restrict_domain_list"><?php echo get_option( 'wsl_settings_bouncer_new_users_restrict_domain_list' ); ?></textarea> 
				</td>
			  </tr>  
			  <tr>
				<td width="200" align="right" valign="top"><p><strong><?php _wsl_e("Bounce text", 'wordpress-social-login') ?> :</strong></p></td>
				<td> 
					<?php 
						wsl_render_wp_editor( "wsl_settings_bouncer_new_users_restrict_domain_text_bounce", get_option( 'wsl_settings_bouncer_new_users_restrict_domain_text_bounce' ) ); 
					?>
				</td>
			  </tr>
			</table>  
		</div>
	</div>

	<div id="namediv" class="stuffbox">
		<h3>
			<label for="name"><?php _wsl_e("Filters by e-mails addresses", 'wordpress-social-login') ?></label>
		</h3>
		<div class="inside hideinside"> 
			<p>
				<?php _wsl_e("Restrict registration to a limited number of emails addresses.", 'wordpress-social-login') ?> 
				<?php _wsl_e("Insert one email address per line and try to keep this list short. On <code>Bounce text</code> insert the text you want to display for rejected users. ex: <code>hybridauth@gmail.com</code>", 'wordpress-social-login') ?> 
			</p>
			<table width="100%" border="0" cellpadding="5" cellspacing="2" style="border-top:1px solid #ccc;">  
			  <tr>
				<td width="200" align="right"><strong><?php _wsl_e("Enabled", 'wordpress-social-login') ?> :</strong></td>
				<td> 
					<select name="wsl_settings_bouncer_new_users_restrict_email_enabled">
						<option <?php if( get_option( 'wsl_settings_bouncer_new_users_restrict_email_enabled' ) == 1 ) echo "selected"; ?> value="1"><?php _wsl_e("Yes", 'wordpress-social-login') ?></option>
						<option <?php if( get_option( 'wsl_settings_bouncer_new_users_restrict_email_enabled' ) == 2 ) echo "selected"; ?> value="2"><?php _wsl_e("No", 'wordpress-social-login') ?></option> 
					</select>
				</td>
			  </tr>   
			  <tr>
				<td width="200" align="right" valign="top"><p><strong><?php _wsl_e("E-mails list", 'wordpress-social-login') ?> :</strong></p></td>
				<td> 
					<textarea style="width:100%;height:60px;margin-top:6px;" name="wsl_settings_bouncer_new_users_restrict_email_list"><?php echo get_option( 'wsl_settings_bouncer_new_users_restrict_email_list' ); ?></textarea>  
				</td>
			  </tr>  
			  <tr>
				<td width="200" align="right" valign="top"><p><strong><?php _wsl_e("Bounce text", 'wordpress-social-login') ?> :</strong></p></td>
				<td> 
					<?php 
						wsl_render_wp_editor( "wsl_settings_bouncer_new_users_restrict_email_text_bounce", get_option( 'wsl_settings_bouncer_new_users_restrict_email_text_bounce' ) ); 
					?>
				</td>
			  </tr>  
			</table>  
		</div>
	</div>

	<div id="namediv" class="stuffbox">
		<h3>
			<label for="name"><?php _wsl_e("Filters by profile urls", 'wordpress-social-login') ?></label>
		</h3>
		<div class="inside hideinside"> 
			<p>
				<?php _wsl_e("Restrict registration to a limited number of profile urls.", 'wordpress-social-login') ?>
				<?php _wsl_e("<b>Note</b>: If a social network provide the user email, then use 'Filters by e-mails addresses' instead. Providers like Facebook provide multiples profiles URLs per user and WSL won't be able to reconize them.", 'wordpress-social-login') ?>
				<?php _wsl_e("Insert one email address per line and try to keep this list short. On <code>Bounce text</code> insert the text you want to display for rejected users. ex: <code>http://twitter.com/HybridAuth</code>, <code>https://plus.google.com/u/0/108839241301472312344</code>", 'wordpress-social-login') ?>
			</p>
			<table width="100%" border="0" cellpadding="5" cellspacing="2" style="border-top:1px solid #ccc;">  
			  <tr>
				<td width="200" align="right"><strong><?php _wsl_e("Enabled", 'wordpress-social-login') ?> :</strong></td>
				<td> 
					<select name="wsl_settings_bouncer_new_users_restrict_profile_enabled">
						<option <?php if( get_option( 'wsl_settings_bouncer_new_users_restrict_profile_enabled' ) == 1 ) echo "selected"; ?> value="1"><?php _wsl_e("Yes", 'wordpress-social-login') ?></option>
						<option <?php if( get_option( 'wsl_settings_bouncer_new_users_restrict_profile_enabled' ) == 2 ) echo "selected"; ?> value="2"><?php _wsl_e("No", 'wordpress-social-login') ?></option> 
					</select>
				</td>
			  </tr>   
			  <tr>
				<td width="200" align="right" valign="top"><p><strong><?php _wsl_e("Profile urls", 'wordpress-social-login') ?> :</strong></p></td>
				<td>
					<textarea style="width:100%;height:60px;margin-top:6px;" name="wsl_settings_bouncer_new_users_restrict_profile_list"><?php echo get_option( 'wsl_settings_bouncer_new_users_restrict_profile_list' ); ?></textarea>  
				</td>
			  </tr>  
			  <tr>
				<td width="200" align="right" valign="top"><p><strong><?php _wsl_e("Bounce text", 'wordpress-social-login') ?> :</strong></p></td>
				<td> 
					<?php 
						wsl_render_wp_editor( "wsl_settings_bouncer_new_users_restrict_profile_text_bounce", get_option( 'wsl_settings_bouncer_new_users_restrict_profile_text_bounce' ) ); 
					?>
				</td>
			  </tr>  
			</table>  
		</div>
	</div>

	<br />

	<div style="margin-left:5px;margin-top:-20px;"> 
		<input type="submit" class="button-primary" value="<?php _wsl_e("Save Settings", 'wordpress-social-login') ?>" /> 
	</div>
</div>
<?php
	// HOOKABLE: 
	do_action( "wsl_component_bouncer_setup_end" );
}

// --------------------------------------------------------------------	
