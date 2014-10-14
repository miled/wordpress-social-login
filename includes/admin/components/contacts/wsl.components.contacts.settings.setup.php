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

function wsl_component_contacts_settings_setup()
{
	// HOOKABLE: 
	do_action( "wsl_component_contacts_settings_setup_start" );

	$sections = array(
		'google'   => 'wsl_component_contacts_settings_setup_google',
		'facebook' => 'wsl_component_contacts_settings_setup_facebook', 
		'twitter'  => 'wsl_component_contacts_settings_setup_twitter', 
		'live'     => 'wsl_component_contacts_settings_setup_live', 
		'linkedin' => 'wsl_component_contacts_settings_setup_linkedin', 
	);

	$sections = apply_filters( 'wsl_component_buddypress_setup_alter_sections', $sections );
?>
<div>
	<?php
		foreach( $sections as $section => $action )
		{
			do_action( $action . '_start' );

			do_action( $action );

			do_action( $action . '_end' );
		}
	?>

	<br />

	<div style="margin-left:5px;margin-top:-20px;"> 
		<input type="submit" class="button-primary" value="<?php _wsl_e("Save Settings", 'wordpress-social-login') ?>" /> 
	</div>
</div>
<?php
	// HOOKABLE: 
	do_action( "wsl_component_contacts_settings_setup_end" );
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
			<?php _wsl_e( 'To import Google\'s users contacts list you will have to go to <a href="https://console.developers.google.com" target="_blank">https://console.developers.google.com</a>, then <b>APIs &amp; auth</b> &gt; <b>APIs</b> and enable <em style="color:#0147bb;">“Contacts API”</em>', 'wordpress-social-login') ?>
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

add_action( 'wsl_component_contacts_settings_setup_google', 'wsl_component_contacts_settings_setup_google' );

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

add_action( 'wsl_component_contacts_settings_setup_facebook', 'wsl_component_contacts_settings_setup_facebook' );

// --------------------------------------------------------------------	

function wsl_component_contacts_settings_setup_twitter()
{
?>
<div class="stuffbox">
	<h3>
		<label><?php _wsl_e("Twitter", 'wordpress-social-login') ?></label>
	</h3>
	<div class="inside"> 
		<select name="wsl_settings_contacts_import_twitter" <?php if( ! get_option( 'wsl_settings_Twitter_enabled' ) ) echo "disabled" ?> >
			<option <?php if( get_option( 'wsl_settings_contacts_import_twitter' ) == 1 ) echo "selected"; ?> value="1"><?php _wsl_e("Enabled", 'wordpress-social-login') ?></option>
			<option <?php if( get_option( 'wsl_settings_contacts_import_twitter' ) == 2 ) echo "selected"; ?> value="2"><?php _wsl_e("Disabled", 'wordpress-social-login') ?></option> 
		</select>
	</div>
</div>
<?php
}

add_action( 'wsl_component_contacts_settings_setup_twitter', 'wsl_component_contacts_settings_setup_twitter' );

// --------------------------------------------------------------------	

function wsl_component_contacts_settings_setup_live()
{
?>
<div class="stuffbox">
	<h3>
		<label><?php _wsl_e("Windows Live", 'wordpress-social-login') ?></label>
	</h3>
	<div class="inside"> 
		<select name="wsl_settings_contacts_import_live" <?php if( ! get_option( 'wsl_settings_Live_enabled' ) ) echo "disabled" ?> >
			<option <?php if( get_option( 'wsl_settings_contacts_import_live' ) == 1 ) echo "selected"; ?> value="1"><?php _wsl_e("Enabled", 'wordpress-social-login') ?></option>
			<option <?php if( get_option( 'wsl_settings_contacts_import_live' ) == 2 ) echo "selected"; ?> value="2"><?php _wsl_e("Disabled", 'wordpress-social-login') ?></option> 
		</select>
	</div>
</div>
<?php
}

add_action( 'wsl_component_contacts_settings_setup_live', 'wsl_component_contacts_settings_setup_live' );

// --------------------------------------------------------------------	

function wsl_component_contacts_settings_setup_linkedin()
{
?>
<div class="stuffbox">
	<h3>
		<label><?php _wsl_e("LinkedIn", 'wordpress-social-login') ?></label>
	</h3>
	<div class="inside"> 
		<select name="wsl_settings_contacts_import_linkedin" <?php if( ! get_option( 'wsl_settings_LinkedIn_enabled' ) ) echo "disabled" ?> >
			<option <?php if( get_option( 'wsl_settings_contacts_import_linkedin' ) == 1 ) echo "selected"; ?> value="1"><?php _wsl_e("Enabled", 'wordpress-social-login') ?></option>
			<option <?php if( get_option( 'wsl_settings_contacts_import_linkedin' ) == 2 ) echo "selected"; ?> value="2"><?php _wsl_e("Disabled", 'wordpress-social-login') ?></option> 
		</select>
	</div>
</div>
<?php
}

add_action( 'wsl_component_contacts_settings_setup_linkedin', 'wsl_component_contacts_settings_setup_linkedin' );

// --------------------------------------------------------------------	
