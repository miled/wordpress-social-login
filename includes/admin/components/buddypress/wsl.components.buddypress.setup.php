<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*    (c) 2011-2014 Mohamed Mrassi and contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

/**
* BuddyPress integration.
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 

// --------------------------------------------------------------------

function wsl_component_buddypress_setup()
{
	// HOOKABLE: 
	do_action( "wsl_component_buddypress_setup_start" );

	// http://hybridauth.sourceforge.net/userguide/Profile_Data_User_Profile.html 
	$ha_profile_fields = array(
		array( 'field' => 'provider'    , 'label' => "Provider name"            , 'description' => "The the provider or social network name the user used to connected"                                     ),
		array( 'field' => 'identifier'  , 'label' => "Provider user Identifier" , 'description' => "The Unique user's ID on the connected provider. Depending on the provider, this field can be an number, Email, URL, etc"  ),
		array( 'field' => 'profileURL'  , 'label' => "Profile URL"              , 'description' => "Link to the user profile on the provider web site"                                                      ),
		array( 'field' => 'webSiteURL'  , 'label' => "Website URL"              , 'description' => "User website, blog or web page"                                                                         ),
		array( 'field' => 'photoURL'    , 'label' => "Photo URL"                , 'description' => "Link to user picture or avatar on the provider web site"                                                ),
		array( 'field' => 'displayName' , 'label' => "Display name"             , 'description' => "User Display name. If not provided by social network, WSL will return a concatenation of the user first and last name" ),
		array( 'field' => 'description' , 'label' => "Description"              , 'description' => "A short about me"                                                                                       ),
		array( 'field' => 'firstName'   , 'label' => "First name"               , 'description' => "User's first name"                                                                                      ),
		array( 'field' => 'lastName'    , 'label' => "Last name"                , 'description' => "User's last name"                                                                                       ),
		array( 'field' => 'gender'      , 'label' => "Gender"                   , 'description' => "User's gender. Values are 'female', 'male' or blank"                                                    ),
		array( 'field' => 'language'    , 'label' => "Language"                 , 'description' => "User's language"                                                                                        ),
		array( 'field' => 'age'         , 'label' => "Age"                      , 'description' => "User' age. Note that WSL do not calculate this field. We return it as it was provided"                  ),
		array( 'field' => 'birthDay'    , 'label' => "Birth day"                , 'description' => "The day in the month in which the person was born. Not to confuse it with 'Birth date'"                 ),
		array( 'field' => 'birthMonth'  , 'label' => "Birth month"              , 'description' => "The month in which the person was born"                                                                 ),
		array( 'field' => 'birthYear'   , 'label' => "Birth year"               , 'description' => "The year in which the person was born"                                                                  ),
		array( 'field' => 'birthDate'   , 'label' => "Birth date"               , 'description' => "Complete birthday in which the person was born. Format: YYYY-MM-DD"                                     ),
		array( 'field' => 'email'       , 'label' => "Email"                    , 'description' => "User email. Not all of provider grant access to the user email"                                         ),
		array( 'field' => 'phone'       , 'label' => "Phone"                    , 'description' => "User's phone number"                                                                                    ),
		array( 'field' => 'address'     , 'label' => "Address"                  , 'description' => "User's address"                                                                                         ),
		array( 'field' => 'country'     , 'label' => "Country"                  , 'description' => "User's country"                                                                                         ),
		array( 'field' => 'region'      , 'label' => "Region"                   , 'description' => "User's state or region"                                                                                 ),
		array( 'field' => 'city'        , 'label' => "City"                     , 'description' => "User's city"                                                                                            ),
		array( 'field' => 'zip'         , 'label' => "Zip"                      , 'description' => "User's zipcode"                                                                                         ),
	);

	$wsl_settings_buddypress_enable_mapping = get_option( 'wsl_settings_buddypress_enable_mapping' );
	$wsl_settings_buddypress_xprofile_map = get_option( 'wsl_settings_buddypress_xprofile_map' );

	// echo "<pre>";
	
	// print_r( $_POST );
	// print_r( $_GET );
	// print_r( $_REQUEST ); 
	// print_r( $wsl_settings_buddypress_xprofile_map ); 
	
	// echo "</pre>";
?>

<div  id="post-body-content">

	<div class="stuffbox">
		<h3>
			<label for="name"><?php _wsl_e("Users avatars", 'wordpress-social-login') ?></label>
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
	
	<div class="stuffbox">
		<h3>
			<label for="name"><?php _wsl_e("Profile mappings", 'wordpress-social-login') ?></label>
		</h3>
		<div class="inside"> 
			<p> 
				<?php _wsl_e("When <b>Enable profile mapping</b> is enabled, WSL will let try to automatically fill in Buddypress users X-Profile data from social networks profiles", 'wordpress-social-login') ?>.
			</p>

			<p>
				<?php _wsl_e('<b>Notes</b>', 'wordpress-social-login') ?>:
			</p> 

			<p class="description">
				1. <?php _wsl_e('<b>Profile mapping</b> will only work with new users. Profile mapping for returning users will implemented in future version of WSL', 'wordpress-social-login') ?>.
				<br />
				2. <?php _wsl_e('Not all the mapped fields will be filled. Some providers and social networks do not give away many information about their users', 'wordpress-social-login') ?>.
				<br />
				3. <?php _wsl_e('WSL can only map <b>Single Fields</b>: Multi-line Text Areax, Text Box, URL, Date Selector and Number', 'wordpress-social-login') ?>.
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
			<label for="name"><?php _wsl_e("Fields Map", 'wordpress-social-login') ?></label>
		</h3>
		<div class="inside">
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
												<td width="150" align="right" valign="top">
													<strong><?php echo $field->name; ?> :</strong> 
												</td>
												<td valign="top">
													<?php
														if( ! in_array( $field->type, array( 'textarea', 'textbox', 'url', 'datebox', 'number' ) ) ){
															_wsl_e("<b>WSL</b> can not map this field. Supported field types are: <em>Multi-line Text Areax, Text Box, URL, Date Selector and Number</em>.", 'wordpress-social-login');
														}
														else{
														
															$map = isset( $wsl_settings_buddypress_xprofile_map[$field->id] ) ? $wsl_settings_buddypress_xprofile_map[$field->id] : 0;
													?>
														<select name="wsl_settings_buddypress_xprofile_map[<?php echo $field->id; ?>]" style="width:255px" id="bb_profile_mapping_selector_<?php echo $field->id; ?>" onChange="showMappingConfirm( <?php echo $field->id; ?> );">
																<option value=""></option>
															<?php foreach( $ha_profile_fields as $item ): ?>
																<option value="<?php echo $item['field']; ?>" <?php if( $item['field'] == $map ) echo "selected"; ?> ><?php _wsl_e( $item['label'], 'wordpress-social-login'); ?></option>
															<?php endforeach; ?>
														</select>
														
														<?php
															foreach( $ha_profile_fields as $item ){
														?>
															<p style="<?php if( $item['field'] != $map || $map == 0 ) echo "display:none;"; ?>" class="bb_profile_mapping_confirm_<?php echo $field->id; ?>" id="bb_profile_mapping_confirm_<?php echo $field->id; ?>_<?php echo $item['field']; ?>">
																<?php echo sprintf( _wsl__( "WSL <b>%s</b> is mapped to Buddypress <b>%s</b> field", 'wordpress-social-login' ), _wsl__( $item['label'], 'wordpress-social-login'), $field->name ); ?>.
																<br />
																<em><b><?php _wsl_e( $item['label'], 'wordpress-social-login' ); ?>:</b> <?php _wsl_e( $item['description'], 'wordpress-social-login' ); ?>.</em>
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
			<br>  
		</div>
	</div>

	<br />

	<div style="margin-left:5px;margin-top:-20px;"> 
		<input type="submit" class="button-primary" value="<?php _wsl_e("Save Settings", 'wordpress-social-login') ?>" /> 
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
	// HOOKABLE: 
	do_action( "wsl_component_buddypress_setup_end" );
}

// --------------------------------------------------------------------	
