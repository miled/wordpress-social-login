<?php
/*!
* WordPress Social Login
*
* http://miled.github.io/wordpress-social-login/ | https://github.com/miled/wordpress-social-login
*  (c) 2011-2014 Mohamed Mrassi and contributors | http://wordpress.org/plugins/wordpress-social-login/
*/

/**
* BuddyPress integration.
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 

// --------------------------------------------------------------------

function wsl_component_contacts_settings_sidebar()
{
	$sections = array(
		'what_is_this' => 'wsl_component_contacts_settings_sidebar_what_is_this',
	);

	$sections = apply_filters( 'wsl_component_contacts_settings_sidebar_alter_sections', $sections );

	foreach( $sections as $section => $action )
	{
		add_action( 'wsl_component_contacts_settings_sidebar_sections', $action );
	}

	// HOOKABLE: 
	do_action( 'wsl_component_contacts_settings_sidebar_sections' );
}

// --------------------------------------------------------------------	

function wsl_component_contacts_settings_sidebar_what_is_this()
{
?>
<div class="postbox">
	<div class="inside">
		<h3><?php _wsl_e("User contacts import", 'wordpress-social-login') ?></h3>

		<div style="padding:0 20px;">
			<p>
				<?php _wsl_e( 'WordPress Social Login also allow you to import users contact list from Google Gmail, Facebook, Windows Live, LinkedIn  and Vkontakte', 'wordpress-social-login') ?>.
			</p> 

			<p>
				<?php _wsl_e( 'When enabled, users authenticating through WordPress Social Login will be asked for the authorisation to import their contact list. Note that some social networks do not provide certain of their users information like contacts emails, photos and or profile urls', 'wordpress-social-login') ?>.
			</p> 
			<hr />
			<p>
				<b><?php _wsl_e("Notes", 'wordpress-social-login') ?>:</b> 
			</p> 
			
			<ul style="margin-left:15px;margin-top:0px;">
				<li><?php _wsl_e('To enable contacts import from these social network, you need first to enabled them on the <a href="options-general.php?page=wordpress-social-login&wslp=networks"><b>Networks</b></a> tab and register the required application', 'wordpress-social-login') ?>.</li> 
				<li><?php _wsl_e("<b>WSL</b> will try to import as much information about a user contacts as he was able to pull from the social networks APIs.", 'wordpress-social-login') ?></li> 
				<li><?php _wsl_e('All contacts data are sotred into your database on the table: <code>`wsluserscontacts`</code>', 'wordpress-social-login') ?>.</li> 
			</ul> 
		</div> 
	</div>
</div>
<?php
}

// --------------------------------------------------------------------	
