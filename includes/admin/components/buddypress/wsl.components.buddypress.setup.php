<?php
/*!
* WordPress Social Login
*
* https://miled.github.io/wordpress-social-login/ | https://github.com/miled/wordpress-social-login
*   (c) 2011-2020 Mohamed Mrassi and contributors | https://wordpress.org/plugins/wordpress-social-login/
*/

/**
* BuddyPress integration.
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------

function wsl_component_buddypress_setup()
{
	$sections = array(
		'user_avatar'     => 'wsl_component_buddypress_setup_user_avatar',
		'profile_mapping' => 'wsl_component_buddypress_setup_profile_mapping',
	);

	$sections = apply_filters( 'wsl_component_buddypress_setup_alter_sections', $sections );

	foreach( $sections as $section => $action )
	{
		add_action( 'wsl_component_buddypress_setup_sections', $action );
	}
?>
<div>
	<?php
		// HOOKABLE:
		do_action( 'wsl_component_buddypress_setup_sections' );
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

function wsl_component_buddypress_setup_user_avatar()
{
?>
<div class="stuffbox">
	<h3>
		<label><?php _wsl_e("Users avatars", 'wordpress-social-login') ?></label>
	</h3>
	<div class="inside">
		<p>
			<?php
				if( get_option( 'wsl_settings_users_avatars' ) == 1 ){
					_wsl_e("<b>Users avatars</b> is currently set to: <b>Display users avatars from social networks when available</b>", 'wordpress-social-login');
				}
				else{
					_wsl_e("<b>Users avatars</b> is currently set to: <b>Display the default WordPress avatars</b>", 'wordpress-social-login');
				}
			?>.
		</p>

		<p class="description">
			<?php _wsl_e("To change this setting, go to <b>Widget</b> &gt; <b>Basic Settings</b> &gt <b>Users avatars</b>, then select the type of avatars that you want to display for your users", 'wordpress-social-login') ?>.
		</p>
	</div>
</div>
<?php
}

// --------------------------------------------------------------------

function wsl_component_buddypress_setup_profile_mapping()
{
	$assets_base_url = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . 'assets/img/';

	$wsl_settings_buddypress_enable_mapping = get_option( 'wsl_settings_buddypress_enable_mapping' );
	$wsl_settings_buddypress_xprofile_map   = get_option( 'wsl_settings_buddypress_xprofile_map' );

	# http://hybridauth.sourceforge.net/userguide/Profile_Data_User_Profile.html
	$ha_profile_fields = array(
		array( 'field' => 'provider'    , 'label' => _wsl__( "Provider name"            , 'wordpress-social-login'), 'description' => _wsl__( "The the provider or social network name the user used to connected"                                                     , 'wordpress-social-login') ),
		array( 'field' => 'identifier'  , 'label' => _wsl__( "Provider user Identifier" , 'wordpress-social-login'), 'description' => _wsl__( "The Unique user's ID on the connected provider. Depending on the provider, this field can be an number, Email, URL, etc", 'wordpress-social-login') ),
		array( 'field' => 'profileURL'  , 'label' => _wsl__( "Profile URL"              , 'wordpress-social-login'), 'description' => _wsl__( "Link to the user profile on the provider web site"                                                                      , 'wordpress-social-login') ),
		array( 'field' => 'webSiteURL'  , 'label' => _wsl__( "Website URL"              , 'wordpress-social-login'), 'description' => _wsl__( "User website, blog or web page"                                                                                         , 'wordpress-social-login') ),
		array( 'field' => 'photoURL'    , 'label' => _wsl__( "Photo URL"                , 'wordpress-social-login'), 'description' => _wsl__( "Link to user picture or avatar on the provider web site"                                                                , 'wordpress-social-login') ),
		array( 'field' => 'displayName' , 'label' => _wsl__( "Display name"             , 'wordpress-social-login'), 'description' => _wsl__( "User Display name. If not provided by social network, WSL will return a concatenation of the user first and last name"  , 'wordpress-social-login') ),
		array( 'field' => 'description' , 'label' => _wsl__( "Description"              , 'wordpress-social-login'), 'description' => _wsl__( "A short about me"                                                                                                       , 'wordpress-social-login') ),
		array( 'field' => 'firstName'   , 'label' => _wsl__( "First name"               , 'wordpress-social-login'), 'description' => _wsl__( "User's first name"                                                                                                      , 'wordpress-social-login') ),
		array( 'field' => 'lastName'    , 'label' => _wsl__( "Last name"                , 'wordpress-social-login'), 'description' => _wsl__( "User's last name"                                                                                                       , 'wordpress-social-login') ),
		array( 'field' => 'gender'      , 'label' => _wsl__( "Gender"                   , 'wordpress-social-login'), 'description' => _wsl__( "User's gender. Values are 'female', 'male' or blank"                                                                    , 'wordpress-social-login') ),
		array( 'field' => 'language'    , 'label' => _wsl__( "Language"                 , 'wordpress-social-login'), 'description' => _wsl__( "User's language"                                                                                                        , 'wordpress-social-login') ),
		array( 'field' => 'age'         , 'label' => _wsl__( "Age"                      , 'wordpress-social-login'), 'description' => _wsl__( "User' age. Note that WSL do not calculate this field. We return it as it was provided"                                  , 'wordpress-social-login') ),
		array( 'field' => 'birthDay'    , 'label' => _wsl__( "Birth day"                , 'wordpress-social-login'), 'description' => _wsl__( "The day in the month in which the person was born. Not to confuse it with 'Birth date'"                                 , 'wordpress-social-login') ),
		array( 'field' => 'birthMonth'  , 'label' => _wsl__( "Birth month"              , 'wordpress-social-login'), 'description' => _wsl__( "The month in which the person was born"                                                                                 , 'wordpress-social-login') ),
		array( 'field' => 'birthYear'   , 'label' => _wsl__( "Birth year"               , 'wordpress-social-login'), 'description' => _wsl__( "The year in which the person was born"                                                                                  , 'wordpress-social-login') ),
		array( 'field' => 'birthDate'   , 'label' => _wsl__( "Birth date"               , 'wordpress-social-login'), 'description' => _wsl__( "Complete birthday in which the person was born. Format: YYYY-MM-DD"                                                     , 'wordpress-social-login') ),
		array( 'field' => 'email'       , 'label' => _wsl__( "Email"                    , 'wordpress-social-login'), 'description' => _wsl__( "User's email address. Not all of provider grant access to the user email"                                               , 'wordpress-social-login') ),
		array( 'field' => 'phone'       , 'label' => _wsl__( "Phone"                    , 'wordpress-social-login'), 'description' => _wsl__( "User's phone number"                                                                                                    , 'wordpress-social-login') ),
		array( 'field' => 'address'     , 'label' => _wsl__( "Address"                  , 'wordpress-social-login'), 'description' => _wsl__( "User's address"                                                                                                         , 'wordpress-social-login') ),
		array( 'field' => 'country'     , 'label' => _wsl__( "Country"                  , 'wordpress-social-login'), 'description' => _wsl__( "User's country"                                                                                                         , 'wordpress-social-login') ),
		array( 'field' => 'region'      , 'label' => _wsl__( "Region"                   , 'wordpress-social-login'), 'description' => _wsl__( "User's state or region"                                                                                                 , 'wordpress-social-login') ),
		array( 'field' => 'city'        , 'label' => _wsl__( "City"                     , 'wordpress-social-login'), 'description' => _wsl__( "User's city"                                                                                                            , 'wordpress-social-login') ),
		array( 'field' => 'zip'         , 'label' => _wsl__( "Zip"                      , 'wordpress-social-login'), 'description' => _wsl__( "User's zipcode"                                                                                                         , 'wordpress-social-login') ),
	);
?>
<div class="stuffbox">
	<h3>
		<label><?php _wsl_e("Profile mappings", 'wordpress-social-login') ?></label>
	</h3>
	<div class="inside">
		<p>
			<?php _wsl_e("When <b>Profile mapping</b> is enabled, WSL will try to automatically fill in Buddypress users profiles from their social networks profiles", 'wordpress-social-login') ?>.
		</p>

		<p>
			<b><?php _wsl_e('Notes', 'wordpress-social-login') ?>:</b>
		</p>

		<p class="description">
			1. <?php _wsl_e('<b>Profile mapping</b> will only work for new users. Profile mapping for returning users will implemented in future version of WSL', 'wordpress-social-login') ?>.
			<br />
			2. <?php _wsl_e('Not all the mapped fields will be filled. Some providers and social networks do not give away many information about their users', 'wordpress-social-login') ?>.
			<br />
			3. <?php _wsl_e('WSL can only map <b>Single Fields</b>. Supported fields types are: Multi-line Text Areax, Text Box, URL, Date Selector and Number', 'wordpress-social-login') ?>.
		</p>

		<table width="100%" border="0" cellpadding="5" cellspacing="2" style="border-top:1px solid #ccc;">
		  <tr>
			<td width="200" align="right"><strong><?php _wsl_e("Enable profile mapping", 'wordpress-social-login') ?> :</strong></td>
			<td>
				<select name="wsl_settings_buddypress_enable_mapping" id="wsl_settings_buddypress_enable_mapping" style="width:100px" onChange="toggleMapDiv();">
					<option <?php if( $wsl_settings_buddypress_enable_mapping == 1 ) echo "selected"; ?> value="1"><?php _wsl_e("Yes", 'wordpress-social-login') ?></option>
					<option <?php if( $wsl_settings_buddypress_enable_mapping == 2 ) echo "selected"; ?> value="2"><?php _wsl_e("No", 'wordpress-social-login') ?></option>
				</select>
			</td>
		  </tr>
		</table>
		<br>
	</div>
</div>

<div id="xprofilemapdiv" class="stuffbox" style="<?php if( $wsl_settings_buddypress_enable_mapping == 2 ) echo "display:none;"; ?>">
	<h3>
		<label><?php _wsl_e("Fields Map", 'wordpress-social-login') ?></label>
	</h3>

	<div class="inside">
		<p>
			<?php _wsl_e("Here you can create a new map by placing WSL users profiles fields to the appropriate destination fields", 'wordpress-social-login') ?>.
			<?php _wsl_e('The left column shows the available <b>WSL users profiles fields</b>: These select boxes are called <b>source</b> fields', 'wordpress-social-login') ?>.
			<?php _wsl_e('The right column shows the list of <b>Buddypress profiles fields</b>: Those are the <b>destination</b> fields', 'wordpress-social-login') ?>.
			<?php _wsl_e('If you don\'t want to map a particular Buddypress field, then leave the source for that field blank', 'wordpress-social-login') ?>.
		</p>

		<hr />

		<?php
			if ( bp_has_profile() )
			{
				while ( bp_profile_groups() )
				{
					global $group;

					bp_the_profile_group();
					?>
						<h4><?php echo sprintf( _wsl__("Fields Group '%s'", 'wordpress-social-login'), $group->name ) ?> :</h4>

						<table width="100%" border="0" cellpadding="5" cellspacing="2" style="border-top:1px solid #ccc;">
							<?php
								while ( bp_profile_fields() )
								{
									global $field;

									bp_the_profile_field();
									?>
										<tr>
											<td width="270" align="right" valign="top">
												<?php
													$map = isset( $wsl_settings_buddypress_xprofile_map[$field->id] ) ? $wsl_settings_buddypress_xprofile_map[$field->id] : 0;
													$can_map_it = true;

													if( ! in_array( $field->type, array( 'textarea', 'textbox', 'url', 'datebox', 'number' ) ) ){
														$can_map_it = false;
													}
												?>
												<select name="wsl_settings_buddypress_xprofile_map[<?php echo $field->id; ?>]" style="width:255px" id="bb_profile_mapping_selector_<?php echo $field->id; ?>" onChange="showMappingConfirm( <?php echo $field->id; ?> );" <?php if( ! $can_map_it ) echo "disabled"; ?>>
													<option value=""></option>
													<?php
														if( $can_map_it ){
															foreach( $ha_profile_fields as $item ){
															?>
																<option value="<?php echo $item['field']; ?>" <?php if( $item['field'] == $map ) echo "selected"; ?> ><?php echo $item['label']; ?></option>
															<?php
															}
														}
													?>
												</select>
											</td>
											<td valign="top" align="center" width="50">
												<img src="<?php echo $assets_base_url; ?>arr_right.png" />
											</td>
											<td valign="top">
												<strong><?php echo $field->name; ?></strong>
												<?php
													if( ! $can_map_it ){
													?>
														<p class="description">
															<?php _wsl_e("<b>WSL</b> can not map this field. Supported field types are: <em>Multi-line Text Areax, Text Box, URL, Date Selector and Number</em>", 'wordpress-social-login'); ?>.
														</p>
													<?php
													}
													else{
													?>
														<?php
															foreach( $ha_profile_fields as $item ){
														?>
															<p class="description bb_profile_mapping_confirm_<?php echo $field->id; ?>" style="margin-left:0;<?php if( $item['field'] != $map ) echo "display:none;"; ?>" id="bb_profile_mapping_confirm_<?php echo $field->id; ?>_<?php echo $item['field']; ?>">
																<?php echo sprintf( _wsl__( "WSL <b>%s</b> is mapped to Buddypress <b>%s</b> field", 'wordpress-social-login' ), $item['label'], $field->name ); ?>.
																<br />
																<em><b><?php echo $item['label']; ?>:</b> <?php echo $item['description']; ?>.</em>
															</p>
														<?php
															}
														?>
													<?php
													}
												?>
											</td>
										</tr>
									<?php
								}
							?>
						</table>
					<?php
				}
			}
		?>
	</div>
</div>
<script>
	function toggleMapDiv(){
		if(typeof jQuery=="undefined"){
			alert( "Error: WordPress Social Login require jQuery to be installed on your wordpress in order to work!" );

			return false;
		}

		var em = jQuery( "#wsl_settings_buddypress_enable_mapping" ).val();

		if( em == 2 ) jQuery( "#xprofilemapdiv" ).hide();
		else jQuery( "#xprofilemapdiv" ).show();
	}

	function showMappingConfirm( field ){
		if(typeof jQuery=="undefined"){
			alert( "Error: WordPress Social Login require jQuery to be installed on your wordpress in order to work!" );

			return false;
		}

		var el = jQuery( "#bb_profile_mapping_selector_" + field ).val();

		jQuery( ".bb_profile_mapping_confirm_" + field ).hide();

		jQuery( "#bb_profile_mapping_confirm_" + field + "_" + el ).show();
	}
</script>
<?php
}

// --------------------------------------------------------------------
