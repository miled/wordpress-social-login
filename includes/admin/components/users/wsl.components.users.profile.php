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

	$linked_accounts = wsl_get_user_linked_account_by_user_id( $user_id );

	if( ! $linked_accounts ){
		_wsl_e("USER DOES NOT EXIST!", 'wordpress-social-login');

		return;
	}

	$assets_base_url = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . '/assets/img/16x16/';

	?>
	<style>
		table td, table th { border: 1px solid #DDDDDD; }
		table th label { font-weight: bold; }
		.form-table th { width:120px; text-align:right; }
	</style>
	<p>
		<a class="button button-secondary" href="user-edit.php?user_id=<?php echo $user_id ?>">Edit User</a>
		<a class="button button-secondary" href="options-general.php?page=wordpress-social-login&wslp=contacts&uid=<?php echo $user_id ?>">Show User Contacts List</a>
	</p>
	<hr />
	<?php
	foreach( $linked_accounts AS $link ){
		?>
			<h3><?php _wsl_e("User Profile", 'wordpress-social-login'); ?> <small><?php echo sprintf( _wsl__( "as provided by %s", 'wordpress-social-login'), $link->provider ); ?> </small></h3> 

			<table class="form-table"
				<tr><th><label><?php echo $link->provider; ?> Identifier </label></th><td><?php echo $link->identifier 	; ?> <br /><span class="description">The Unique user's ID. Can be an interger for some providers, Email, URL, etc.</span></td></tr>
				<tr><th><label>Wordpress Identifier </label></th><td><?php echo $user_id; ?> <br /><span class="description">The Unique user's ID on your website.</span></td></tr>
				<tr><th><label>Profile URL 	</label></th><td><a href="<?php echo $link->profileurl; ?>"><?php echo $link->profileurl; ?></a> <br /><span class="description">URL link to profile page on the <?php echo $link->provider; ?> web site.</span></td></tr>
				<tr><th><label>Website URL 	</label></th><td><a href="<?php echo $link->websiteurl; ?>"><?php echo $link->websiteurl; ?></a> <br /><span class="description">User website, blog, web page, etc.</span></td></tr>
				<tr><th><label>Photo URL 	</label></th><td><a href="<?php echo $link->photourl; ?>"><?php echo $link->photourl; ?></a> <br /><span class="description">URL link to user photo or avatar.</span></td></tr>
				<tr><th><label>Display name	</label></th><td><?php echo $link->displayname	; ?> <br /><span class="description">User dispaly Name provided by the <?php echo $link->provider; ?> or a concatenation of first and last name.</span></td></tr>
				<tr><th><label>Description	</label></th><td><?php echo $link->description	; ?>
				<tr><th><label>First name	</label></th><td><?php echo $link->firstname	; ?>
				<tr><th><label>Last name 	</label></th><td><?php echo $link->lastname 	; ?>
				<tr><th><label>Gender 		</label></th><td><?php echo $link->gender 		; ?>
				<tr><th><label>Language 	</label></th><td><?php echo $link->language 	; ?>
				<tr><th><label>Age 			</label></th><td><?php echo $link->age 			; ?>
				<tr><th><label>Birth day 	</label></th><td><?php echo $link->birthday 	; ?>
				<tr><th><label>Birth month 	</label></th><td><?php echo $link->birthmonth 	; ?>
				<tr><th><label>Birth year 	</label></th><td><?php echo $link->birthyear 	; ?>
				<tr><th><label>Email 		</label></th><td><?php echo $link->email 		; ?>
				<tr><th><label>Phone 		</label></th><td><?php echo $link->phone 		; ?>
				<tr><th><label>Address 		</label></th><td><?php echo $link->address 		; ?>
				<tr><th><label>Country 		</label></th><td><?php echo $link->country 		; ?>
				<tr><th><label>Region 		</label></th><td><?php echo $link->region 		; ?>
				<tr><th><label>City 		</label></th><td><?php echo $link->city 		; ?>
				<tr><th><label>Zip 			</label></th><td><?php echo $link->zip 			; ?>
				</tr>
			</table>
		<?php
	} 

	// HOOKABLE: 
	do_action( "wsl_component_users_profile_end" );
}

// --------------------------------------------------------------------	
