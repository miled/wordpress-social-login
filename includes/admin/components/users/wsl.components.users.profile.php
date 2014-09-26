<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*    (c) 2011-2013 Mohamed Mrassi and contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 

// --------------------------------------------------------------------

function wsl_component_users_profile( $user_id )
{
	// HOOKABLE: 
	do_action( "wsl_component_users_profile_start" );

	$assets_base_url = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . '/assets/img/16x16/';

	$linked_accounts = wsl_get_user_linked_account_by_user_id( $user_id );

	if( ! $linked_accounts ){
		echo '<br />';

		_wsl_e( "This's not a WSL user!", 'wordpress-social-login');

		return;
	}

	$user_info = get_userdata( $user_id ); 
?>
	<style>
		table td, table th { border: 1px solid #DDDDDD; }
		table th label { font-weight: bold; }
		.form-table th { width:120px; text-align:right; }
	</style>

	<p style="float:right">
		<a class="button button-secondary" href="user-edit.php?user_id=<?php echo $user_id ?>"><?php _wsl_e("Edit User", 'wordpress-social-login'); ?></a>
		<a class="button button-secondary" href="options-general.php?page=wordpress-social-login&wslp=contacts&uid=<?php echo $user_id ?>"><?php _wsl_e("Show User Contacts List", 'wordpress-social-login'); ?></a>
	</p>
	
	<h3><?php _wsl_e("Wordpress user profile", 'wordpress-social-login'); ?></h3>

	<table class="wp-list-table widefat">
		<tr><th width="200"><label><?php _wsl_e("User ID", 'wordpress-social-login'); ?></label></th><td><?php echo $user_info->ID; ?></td></tr> 
		<tr><th width="200"><label><?php _wsl_e("Username", 'wordpress-social-login'); ?></label></th><td><?php echo $user_info->user_login; ?></td></tr> 
		<tr><th><label><?php _wsl_e("Display name", 'wordpress-social-login'); ?></label></th><td><?php echo $user_info->display_name; ?></td></tr> 
		<tr><th><label><?php _wsl_e("E-mail", 'wordpress-social-login'); ?></label></th><td><?php echo $user_info->user_email; ?></td></tr> 
		<tr><th><label><?php _wsl_e("Website", 'wordpress-social-login'); ?></label></th><td><?php echo $user_info->user_url; ?></td></tr>   
		<tr><th><label><?php _wsl_e("Registered", 'wordpress-social-login'); ?></label></th><td><?php echo $user_info->user_registered; ?></td></tr>  
		</tr>
	</table>

	<hr />
<?php
	foreach( $linked_accounts AS $link ){
?>
	<h3><?php _wsl_e("User profile", 'wordpress-social-login'); ?> <small><?php echo sprintf( _wsl__( "as provided by %s", 'wordpress-social-login'), $link->provider ); ?> </small></h3> 

	<table class="wp-list-table widefat">
		<tr><th width="200"><label><?php echo sprintf( _wsl__( "%s identifier", 'wordpress-social-login'), $link->provider ); ?></label></th><td><?php echo $link->identifier 	; ?> <br /><span class="description">The Unique user's ID. Can be an interger for some providers, Email, URL, etc.</span></td></tr>
		<tr><th><label><?php _wsl_e("Wordpress Identifier", 'wordpress-social-login'); ?></label></th><td><?php echo $user_id; ?> <br /><span class="description">The Unique user's ID on your website.</span></td></tr>
		<tr><th><label><?php _wsl_e("Profile URL" , 'wordpress-social-login'); ?></label></th><td><a href="<?php echo $link->profileurl; ?>"><?php echo $link->profileurl; ?></a> <br /><span class="description">URL link to profile page on the <?php echo $link->provider; ?> web site.</span></td></tr>
		<tr><th><label><?php _wsl_e("Website URL" , 'wordpress-social-login'); ?></label></th><td><a href="<?php echo $link->websiteurl; ?>"><?php echo $link->websiteurl; ?></a> <br /><span class="description">User website, blog, web page, etc.</span></td></tr>
		<tr><th><label><?php _wsl_e("Photo URL"   , 'wordpress-social-login'); ?></label></th><td><?php if( $link->photourl ){ ?><a href="<?php echo $link->photourl; ?>"><img width="48" height="48" align="left" src="<?php echo $link->photourl ?>" style="margin-right: 5px;" > <?php echo $link->photourl; ?></a> <br /><span class="description">URL link to user photo or avatar.</span><?php } ?></td></tr>
		<tr><th><label><?php _wsl_e("Display name", 'wordpress-social-login'); ?></label></th><td><?php echo $link->displayname	; ?> <br /><span class="description">User dispaly Name provided by the <?php echo $link->provider; ?> or a concatenation of first and last name.</span></td></tr>
		<tr><th><label><?php _wsl_e("Description" , 'wordpress-social-login'); ?></label></th><td><?php echo $link->description	; ?></td></tr>
		<tr><th><label><?php _wsl_e("First name"  , 'wordpress-social-login'); ?></label></th><td><?php echo $link->firstname	; ?></td></tr>
		<tr><th><label><?php _wsl_e("Last name"   , 'wordpress-social-login'); ?></label></th><td><?php echo $link->lastname 	; ?></td></tr>
		<tr><th><label><?php _wsl_e("Gender"      , 'wordpress-social-login'); ?></label></th><td><?php echo $link->gender 		; ?></td></tr>
		<tr><th><label><?php _wsl_e("Language"    , 'wordpress-social-login'); ?></label></th><td><?php echo $link->language 	; ?></td></tr>
		<tr><th><label><?php _wsl_e("Age"         , 'wordpress-social-login'); ?></label></th><td><?php echo $link->age         ; ?></td></tr>
		<tr><th><label><?php _wsl_e("Birth day"   , 'wordpress-social-login'); ?></label></th><td><?php echo $link->birthday 	; ?></td></tr>
		<tr><th><label><?php _wsl_e("Birth month" , 'wordpress-social-login'); ?></label></th><td><?php echo $link->birthmonth 	; ?></td></tr>
		<tr><th><label><?php _wsl_e("Birth year"  , 'wordpress-social-login'); ?></label></th><td><?php echo $link->birthyear 	; ?></td></tr>
		<tr><th><label><?php _wsl_e("Email"       , 'wordpress-social-login'); ?></label></th><td><?php echo $link->email 		; ?></td></tr>
		<tr><th><label><?php _wsl_e("Phone"       , 'wordpress-social-login'); ?></label></th><td><?php echo $link->phone 		; ?></td></tr>
		<tr><th><label><?php _wsl_e("Address"     , 'wordpress-social-login'); ?></label></th><td><?php echo $link->address     ; ?></td></tr>
		<tr><th><label><?php _wsl_e("Country"     , 'wordpress-social-login'); ?></label></th><td><?php echo $link->country     ; ?></td></tr>
		<tr><th><label><?php _wsl_e("Region"      , 'wordpress-social-login'); ?></label></th><td><?php echo $link->region 		; ?></td></tr>
		<tr><th><label><?php _wsl_e("City"        , 'wordpress-social-login'); ?></label></th><td><?php echo $link->city 		; ?></td></tr>
		<tr><th><label><?php _wsl_e("Zip"         , 'wordpress-social-login'); ?></label></th><td><?php echo $link->zip         ; ?></td></tr> 
	</table>
<?php
	}

	// HOOKABLE: 
	do_action( "wsl_component_users_profile_end" );
}

// --------------------------------------------------------------------	
