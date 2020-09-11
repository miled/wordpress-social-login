<?php
/*!
* WordPress Social Login
*
* https://miled.github.io/wordpress-social-login/ | https://github.com/miled/wordpress-social-login
*   (c) 2011-2020 Mohamed Mrassi and contributors | https://wordpress.org/plugins/wordpress-social-login/
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------

function wsl_component_contacts_settings_setup()
{
	$sections = array(
		'google'    => 'wsl_component_contacts_settings_setup_google',
		'facebook'  => 'wsl_component_contacts_settings_setup_facebook',
		'twitter'   => 'wsl_component_contacts_settings_setup_twitter',
		'linkedin'  => 'wsl_component_contacts_settings_setup_linkedin',
		'live'      => 'wsl_component_contacts_settings_setup_live',
		'vkontakte' => 'wsl_component_contacts_settings_setup_vkontakte',
	);

	$sections = apply_filters( 'wsl_component_buddypress_setup_alter_sections', $sections );

	foreach( $sections as $section => $action )
	{
		add_action( 'wsl_component_contacts_settings_setup_sections', $action );
	}
?>
<div>
	<?php
		// HOOKABLE:
		do_action( 'wsl_component_contacts_settings_setup_sections' );
	?>

	<br />

	<div style="margin-left:5px;margin-top:-20px;">
        <input type="submit" class="button-primary" value="<?php _wsl_e("Save Settings", 'wordpress-social-login') ?>" />

        &nbsp; <a href="javascript:window.scrollTo(0, 0);"><?php _wsl_e("↑ Scroll back to top", 'wordpress-social-login') ?></a>
	</div>
</div>
<?php
}

// --------------------------------------------------------------------

function wsl_component_contacts_settings_setup_google()
{
?>
<div class="stuffbox">
	<h3>
		<label><?php _wsl_e("Google", 'wordpress-social-login') ?></label>
	</h3>
	<div class="inside">
		<p class="description">
			<?php _wsl_e( 'To import Google\'s users contacts list you will have to go to <a href="https://console.developers.google.com" target="_blank">https://console.developers.google.com</a>, then <b>APIs &amp; auth</b> &gt; <b>APIs</b> and enable <b>Contacts API</b>', 'wordpress-social-login') ?>
		</p>
		<hr />
		<select name="wsl_settings_contacts_import_google" <?php if( ! get_option( 'wsl_settings_Google_enabled' ) ) echo "disabled" ?> >
			<option <?php if( get_option( 'wsl_settings_contacts_import_google' ) == 1 ) echo "selected"; ?> value="1"><?php _wsl_e("Enabled", 'wordpress-social-login') ?></option>
			<option <?php if( get_option( 'wsl_settings_contacts_import_google' ) == 2 ) echo "selected"; ?> value="2"><?php _wsl_e("Disabled", 'wordpress-social-login') ?></option>
		</select>
	</div>
</div>
<?php
}

// --------------------------------------------------------------------

function wsl_component_contacts_settings_setup_facebook()
{
?>
<div class="stuffbox">
	<h3>
		<label><?php _wsl_e("Facebook", 'wordpress-social-login') ?></label>
	</h3>
	<div class="inside">
		<p class="description">
			<?php _wsl_e( 'When enabled, Facebook\'s users will be asked for an extra permission to get access for their friends lists', 'wordpress-social-login') ?>
		</p>
		<hr />
		<select name="wsl_settings_contacts_import_facebook" <?php if( ! get_option( 'wsl_settings_Facebook_enabled' ) ) echo "disabled" ?> >
			<option <?php if( get_option( 'wsl_settings_contacts_import_facebook' ) == 1 ) echo "selected"; ?> value="1"><?php _wsl_e("Enabled", 'wordpress-social-login') ?></option>
			<option <?php if( get_option( 'wsl_settings_contacts_import_facebook' ) == 2 ) echo "selected"; ?> value="2"><?php _wsl_e("Disabled", 'wordpress-social-login') ?></option>
		</select>
	</div>
</div>
<?php
}

// --------------------------------------------------------------------

function wsl_component_contacts_settings_setup_twitter()
{
?>
<div class="stuffbox">
	<h3>
		<label><?php _wsl_e("Twitter", 'wordpress-social-login') ?></label>
	</h3>
	<div class="inside">
		<p class="description">
			<?php _wsl_e( 'This will only import the Twitter\'s users followed by the connected user on your website', 'wordpress-social-login') ?>
		</p>
		<hr />
		<select name="wsl_settings_contacts_import_twitter" <?php if( ! get_option( 'wsl_settings_Twitter_enabled' ) ) echo "disabled" ?> >
			<option <?php if( get_option( 'wsl_settings_contacts_import_twitter' ) == 1 ) echo "selected"; ?> value="1"><?php _wsl_e("Enabled", 'wordpress-social-login') ?></option>
			<option <?php if( get_option( 'wsl_settings_contacts_import_twitter' ) == 2 ) echo "selected"; ?> value="2"><?php _wsl_e("Disabled", 'wordpress-social-login') ?></option>
		</select>
	</div>
</div>
<?php
}

// --------------------------------------------------------------------

function wsl_component_contacts_settings_setup_linkedin()
{
?>
<div class="stuffbox">
	<h3>
		<label><?php _wsl_e("LinkedIn", 'wordpress-social-login') ?></label>
	</h3>
	<div class="inside">
		<p class="description">
			<?php _wsl_e( 'To import LinkedIn\'s users contacts list you will have to go to <a href="https://www.linkedin.com/secure/developer" target="_blank">https://www.linkedin.com/secure/developer</a>, then <b>Default scope</b> and check <b>r_network</b>', 'wordpress-social-login') ?>
		</p>
		<hr />
		<select name="wsl_settings_contacts_import_linkedin" <?php if( ! get_option( 'wsl_settings_LinkedIn_enabled' ) ) echo "disabled" ?> >
			<option <?php if( get_option( 'wsl_settings_contacts_import_linkedin' ) == 1 ) echo "selected"; ?> value="1"><?php _wsl_e("Enabled", 'wordpress-social-login') ?></option>
			<option <?php if( get_option( 'wsl_settings_contacts_import_linkedin' ) == 2 ) echo "selected"; ?> value="2"><?php _wsl_e("Disabled", 'wordpress-social-login') ?></option>
		</select>
	</div>
</div>
<?php
}

// --------------------------------------------------------------------

function wsl_component_contacts_settings_setup_live()
{
?>
<div class="stuffbox">
	<h3>
		<label><?php _wsl_e("Windows Live", 'wordpress-social-login') ?></label>
	</h3>
	<div class="inside">
		<hr />
		<select name="wsl_settings_contacts_import_live" <?php if( ! get_option( 'wsl_settings_Live_enabled' ) ) echo "disabled" ?> >
			<option <?php if( get_option( 'wsl_settings_contacts_import_live' ) == 1 ) echo "selected"; ?> value="1"><?php _wsl_e("Enabled", 'wordpress-social-login') ?></option>
			<option <?php if( get_option( 'wsl_settings_contacts_import_live' ) == 2 ) echo "selected"; ?> value="2"><?php _wsl_e("Disabled", 'wordpress-social-login') ?></option>
		</select>
	</div>
</div>
<?php
}

// --------------------------------------------------------------------

function wsl_component_contacts_settings_setup_vkontakte()
{
?>
<div class="stuffbox">
	<h3>
		<label><?php _wsl_e("Vkontakte", 'wordpress-social-login') ?></label>
	</h3>
	<div class="inside">
		<hr />
		<select name="wsl_settings_contacts_import_vkontakte" <?php if( ! get_option( 'wsl_settings_Vkontakte_enabled' ) ) echo "disabled" ?> >
			<option <?php if( get_option( 'wsl_settings_contacts_import_vkontakte' ) == 1 ) echo "selected"; ?> value="1"><?php _wsl_e("Enabled", 'wordpress-social-login') ?></option>
			<option <?php if( get_option( 'wsl_settings_contacts_import_vkontakte' ) == 2 ) echo "selected"; ?> value="2"><?php _wsl_e("Disabled", 'wordpress-social-login') ?></option>
		</select>
	</div>
</div>
<?php
}

// --------------------------------------------------------------------
