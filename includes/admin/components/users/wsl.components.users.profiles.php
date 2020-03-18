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

function wsl_component_users_profiles( $user_id )
{
	// HOOKABLE:
	do_action( "wsl_component_users_profiles_start" );

	$assets_base_url = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . 'assets/img/16x16/';

	$linked_accounts = wsl_get_stored_hybridauth_user_profiles_by_user_id( $user_id );

	// is it a WSL user?
	if( ! $linked_accounts )
	{
?>
<div style="padding: 15px; margin-bottom: 8px; border: 1px solid #ddd; background-color: #fff;box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
	<?php _wsl_e( "This's not a WSL user!", 'wordpress-social-login' ); ?>.
</div>
<?php
		return;
	}

	# http://hybridauth.sourceforge.net/userguide/Profile_Data_User_Profile.html
	$ha_profile_fields = array(
		array( 'field' => 'identifier'  , 'label' => _wsl__( "Provider user ID" , 'wordpress-social-login'), 'description' => _wsl__( "The Unique user's ID on the connected provider. Depending on the provider, this field can be an number, Email, URL, etc", 'wordpress-social-login') ),
		array( 'field' => 'profileURL'  , 'label' => _wsl__( "Profile URL"      , 'wordpress-social-login'), 'description' => _wsl__( "Link to the user profile on the provider web site"                                                                      , 'wordpress-social-login') ),
		array( 'field' => 'webSiteURL'  , 'label' => _wsl__( "Website URL"      , 'wordpress-social-login'), 'description' => _wsl__( "User website, blog or web page"                                                                                         , 'wordpress-social-login') ),
		array( 'field' => 'photoURL'    , 'label' => _wsl__( "Photo URL"        , 'wordpress-social-login'), 'description' => _wsl__( "Link to user picture or avatar on the provider web site"                                                                , 'wordpress-social-login') ),
		array( 'field' => 'displayName' , 'label' => _wsl__( "Display name"     , 'wordpress-social-login'), 'description' => _wsl__( "User Display name. If not provided by social network, WSL will return a concatenation of the user first and last name"  , 'wordpress-social-login') ),
		array( 'field' => 'description' , 'label' => _wsl__( "Description"      , 'wordpress-social-login'), 'description' => _wsl__( "A short about me"                                                                                                       , 'wordpress-social-login') ),
		array( 'field' => 'firstName'   , 'label' => _wsl__( "First name"       , 'wordpress-social-login'), 'description' => _wsl__( "User's first name"                                                                                                      , 'wordpress-social-login') ),
		array( 'field' => 'lastName'    , 'label' => _wsl__( "Last name"        , 'wordpress-social-login'), 'description' => _wsl__( "User's last name"                                                                                                       , 'wordpress-social-login') ),
		array( 'field' => 'gender'      , 'label' => _wsl__( "Gender"           , 'wordpress-social-login'), 'description' => _wsl__( "User's gender. Values are 'female', 'male' or blank"                                                                    , 'wordpress-social-login') ),
		array( 'field' => 'language'    , 'label' => _wsl__( "Language"         , 'wordpress-social-login'), 'description' => _wsl__( "User's language"                                                                                                        , 'wordpress-social-login') ),
		array( 'field' => 'age'         , 'label' => _wsl__( "Age"              , 'wordpress-social-login'), 'description' => _wsl__( "User' age. Note that WSL do not calculate this field. We return it as it was provided"                                  , 'wordpress-social-login') ),
		array( 'field' => 'birthDay'    , 'label' => _wsl__( "Birth day"        , 'wordpress-social-login'), 'description' => _wsl__( "The day in the month in which the person was born. Not to confuse it with 'Birth date'"                                 , 'wordpress-social-login') ),
		array( 'field' => 'birthMonth'  , 'label' => _wsl__( "Birth month"      , 'wordpress-social-login'), 'description' => _wsl__( "The month in which the person was born"                                                                                 , 'wordpress-social-login') ),
		array( 'field' => 'birthYear'   , 'label' => _wsl__( "Birth year"       , 'wordpress-social-login'), 'description' => _wsl__( "The year in which the person was born"                                                                                  , 'wordpress-social-login') ),
		array( 'field' => 'email'       , 'label' => _wsl__( "Email"            , 'wordpress-social-login'), 'description' => _wsl__( "User's email address. Note: some providers like Facebook and Google can provide verified emails. Users with the same verified email will be automatically linked", 'wordpress-social-login') ),
		array( 'field' => 'phone'       , 'label' => _wsl__( "Phone"            , 'wordpress-social-login'), 'description' => _wsl__( "User's phone number"                                                                                                    , 'wordpress-social-login') ),
		array( 'field' => 'address'     , 'label' => _wsl__( "Address"          , 'wordpress-social-login'), 'description' => _wsl__( "User's address"                                                                                                         , 'wordpress-social-login') ),
		array( 'field' => 'country'     , 'label' => _wsl__( "Country"          , 'wordpress-social-login'), 'description' => _wsl__( "User's country"                                                                                                         , 'wordpress-social-login') ),
		array( 'field' => 'region'      , 'label' => _wsl__( "Region"           , 'wordpress-social-login'), 'description' => _wsl__( "User's state or region"                                                                                                 , 'wordpress-social-login') ),
		array( 'field' => 'city'        , 'label' => _wsl__( "City"             , 'wordpress-social-login'), 'description' => _wsl__( "User's city"                                                                                                            , 'wordpress-social-login') ),
		array( 'field' => 'zip'         , 'label' => _wsl__( "Zip"              , 'wordpress-social-login'), 'description' => _wsl__( "User's zipcode"                                                                                                         , 'wordpress-social-login') ),
	);

	$user_data = get_userdata( $user_id );

	add_thickbox();

	$actions = array(
		'edit_details'  => '<a class="button button-secondary thickbox" href="' . admin_url( 'user-edit.php?user_id=' . $user_id . '&TB_iframe=true&width=1150&height=550' ) . '">' . _wsl__( 'Edit user details', 'wordpress-social-login' ) . '</a>',
		'show_contacts'  => '<a class="button button-secondary" href="' . admin_url( 'options-general.php?page=wordpress-social-login&wslp=contacts&uid=' . $user_id ) . '">' . _wsl__( 'Show user contacts list', 'wordpress-social-login' ) . '</a>',
	);

	// HOOKABLE:
	$actions = apply_filters( 'wsl_component_users_profiles_alter_actions_list', $actions, $user_id );
?>
<style>
	table td, table th { border: 1px solid #DDDDDD; }
	table th label { font-weight: bold; }
	.form-table th { width:120px; text-align:right; }
	p.description { font-size: 11px ! important; margin:0 ! important;}
</style>

<script>
	function confirmDeleteWSLUser()
	{
		return confirm( <?php echo json_encode( _wsl__("Are you sure you want to delete the user's social profiles and contacts?\n\nNote: The associated WordPress user won't be deleted.", 'wordpress-social-login') ) ?> );
	}
</script>

<div style="margin-top: 15px;padding: 15px; margin-bottom: 8px; border: 1px solid #ddd; background-color: #fff;box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
 	<h3 style="margin:0;"><?php echo sprintf( _wsl__("%s's social profiles", 'wordpress-social-login'), $user_data->display_name ) ?></h3>

	<p style="float: <?php if( is_rtl() ) echo 'left'; else echo 'right'; ?>;margin-top:-23px">
		<?php
			echo implode( ' ', $actions );
		?>
	</p>
</div>

<div style="padding: 20px; border: 1px solid #ddd; background-color: #fff;">
	<table class="wp-list-table widefat">
		<tr><th width="200"><label><?php _wsl_e("Wordpress User ID", 'wordpress-social-login'); ?></label></th><td><?php echo $user_data->ID; ?></td></tr>
		<tr><th width="200"><label><?php _wsl_e("Username", 'wordpress-social-login'); ?></label></th><td><?php echo $user_data->user_login; ?></td></tr>
		<tr><th><label><?php _wsl_e("Display name", 'wordpress-social-login'); ?></label></th><td><?php echo $user_data->display_name; ?></td></tr>
		<tr><th><label><?php _wsl_e("E-mail", 'wordpress-social-login'); ?></label></th><td><a href="mailto:<?php echo $user_data->user_email; ?>" target="_blank"><?php echo $user_data->user_email; ?></a></td></tr>
		<tr><th><label><?php _wsl_e("Website", 'wordpress-social-login'); ?></label></th><td><a href="<?php echo $user_data->user_url; ?>" target="_blank"><?php echo $user_data->user_url; ?></a></td></tr>
		<tr><th><label><?php _wsl_e("Registered", 'wordpress-social-login'); ?></label></th><td><?php echo $user_data->user_registered; ?></td></tr>
		</tr>
	 </table>
</div>

<?php
	foreach( $linked_accounts AS $link )
	{
?>
<div style="margin-top:15px;padding: 5px 20px 20px; border: 1px solid #ddd; background-color: #fff;">

<h4><img src="<?php echo $assets_base_url . strtolower( $link->provider ) . '.png' ?>" style="vertical-align:top;width:16px;height:16px;" /> <?php _wsl_e("User profile", 'wordpress-social-login'); ?> <small><?php echo sprintf( _wsl__( "as provided by %s", 'wordpress-social-login'), $link->provider ); ?> </small></h4>

<table class="wp-list-table widefat">
	<?php
		$profile_fields = (array) $link;

		foreach( $ha_profile_fields as $item )
		{
			$item['field'] = strtolower( $item['field'] );
		?>
			<tr>
				<th width="200">
					<label><?php echo $item['label']; ?></label>
				</th>
				<td>
					<?php
						if( isset( $profile_fields[ $item['field'] ] ) && $profile_fields[ $item['field'] ] )
						{
							$field_value = $profile_fields[ $item['field'] ];

							if( in_array( $item['field'], array( 'profileurl', 'websiteurl', 'email' ) ) )
							{
								?>
									<a href="<?php if( $item['field'] == 'email' ) echo 'mailto:'; echo $field_value; ?>" target="_blank"><?php echo $field_value; ?></a>
								<?php
							}
							elseif( $item['field'] == 'photourl' )
							{
								?>
									<a href="<?php echo $field_value; ?>" target="_blank"><img width="36" height="36" align="left" src="<?php echo $field_value; ?>" style="margin-right: 5px;" > <?php echo $field_value; ?></a>
								<?php
							}
							else
							{
								echo $field_value;
							}

							?>
								<p class="description">
									<?php echo $item['description']; ?>.
								</p>
							<?php
						}
					?>
				</td>
			</tr>
		<?php
		}
	?>
</table>
</div>
<?php
	}

	// HOOKABLE:
	do_action( "wsl_component_users_profiles_end" );
}

// --------------------------------------------------------------------
