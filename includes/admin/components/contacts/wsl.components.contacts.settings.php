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

function wsl_component_contacts_settings()
{
	// HOOKABLE: 
	do_action( "wsl_component_contacts_settings_start" );
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
	do_action( "wsl_component_contacts_settings_end" );
}

// --------------------------------------------------------------------	
