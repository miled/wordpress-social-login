<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*    (c) 2011-2014 Mohamed Mrassi and contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

/** 
* User data functions (database related)
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------

function wsl_get_stored_hybridauth_user_id_by_provider_and_provider_uid( $provider, $provider_uid )
{
	global $wpdb;

	$sql = "SELECT user_id FROM `{$wpdb->prefix}wslusersprofiles` WHERE provider = '%s' AND identifier = '%s'";

	return $wpdb->get_var( $wpdb->prepare( $sql, $provider, $provider_uid ) );
}

// --------------------------------------------------------------------

function wsl_get_stored_hybridauth_user_profile_by_provider_and_provider_uid( $provider, $provider_uid )
{
	global $wpdb;

	$sql = "SELECT * FROM `{$wpdb->prefix}wslusersprofiles` WHERE provider = '%s' AND identifier = '%s'";

	return $wpdb->get_results( $wpdb->prepare( $sql, $provider, $provider_uid ) );
}

// --------------------------------------------------------------------

function wsl_get_stored_hybridauth_user_profile_id_by_provider_and_provider_uid( $provider, $provider_uid )
{
	global $wpdb;

	$sql = "SELECT id FROM `{$wpdb->prefix}wslusersprofiles` WHERE provider = '%s' AND identifier = '%s'";

	return $wpdb->get_results( $wpdb->prepare( $sql, $provider, $provider_uid ) );
}

// --------------------------------------------------------------------

function wsl_get_stored_hybridauth_user_profiles_by_user_id( $user_id )
{
	global $wpdb;

	$sql = "SELECT * FROM `{$wpdb->prefix}wslusersprofiles` where user_id = '$user_id'";
	$rs  = $wpdb->get_results( $sql );

	return $rs;
}

// --------------------------------------------------------------------

function wsl_store_hybridauth_user_profile( $user_id, $provider, $profile )
{
	global $wpdb;
	
	$wpdb->show_errors(); 

	$sql = "SELECT id, object_sha FROM `{$wpdb->prefix}wslusersprofiles` where user_id = '$user_id' and provider = '$provider'";
	$rs  = $wpdb->get_results( $sql );

	$profile_sha = sha1( serialize( $profile ) );

	$table_data = array(
		"id"         => 'null',
		"user_id"    => $user_id,
		"provider"   => $provider,
		"object_sha" => $profile_sha
	);
	
	if( $rs ){
		$table_data['id'] = $rs[0]->id;
	}

	$fields = array( 
		'identifier', 
		'profileurl', 
		'websiteurl', 
		'photourl', 
		'displayname', 
		'description', 
		'firstname', 
		'lastname', 
		'gender', 
		'language', 
		'age', 
		'birthday', 
		'birthmonth', 
		'birthyear', 
		'email', 
		'emailverified', 
		'phone', 
		'address', 
		'country', 
		'region', 
		'city', 
		'zip'
	);

	foreach( $profile as $key => $value ) {
		$key = strtolower($key);

		if( in_array( $key, $fields ) ){
			$table_data[ $key ] = (string) $value;
		}
	}

	$rs = $wpdb->replace( "{$wpdb->prefix}wslusersprofiles", $table_data ); 
}

// --------------------------------------------------------------------

/**
* Contacts import
*
* We update contact list only one time per provider, this behaviour may change depend on wsl users feedback 
*/
function wsl_store_hybridauth_user_contacts( $user_id, $provider, $adapter )
{
	// component contact should be enabled
	if( ! wsl_is_component_enabled( 'contacts' ) ){
		return;
	}

	// check if import is enabled for the given provider
	if( ! (
		get_option( 'wsl_settings_contacts_import_facebook' ) == 1 && strtolower( $provider ) == "facebook" ||
		get_option( 'wsl_settings_contacts_import_google' )   == 1 && strtolower( $provider ) == "google"   ||
		get_option( 'wsl_settings_contacts_import_twitter' )  == 1 && strtolower( $provider ) == "twitter"  ||
		get_option( 'wsl_settings_contacts_import_live' )     == 1 && strtolower( $provider ) == "live"     ||
		get_option( 'wsl_settings_contacts_import_linkedin' ) == 1 && strtolower( $provider ) == "linkedin" 
	) ){
		return;
	}

	global $wpdb;

	// check if the user already have contacts. we only import once
	$nb_contacts = $wpdb->get_var( "SELECT COUNT(`id`) FROM {$wpdb->prefix}wsluserscontacts WHERE user_id = '$user_id' AND provider = '$provider' " );
	
	if( $nb_contacts ){
		return;
	}

	// all check: start import process
	$user_contacts = null;

	// grab the user's friends list
	try{
		$user_contacts = $adapter->getUserContacts();
	}
	catch( Exception $e ){ 
		// well.. we can't do much.
	}

	// if no contact found
	if( ! $user_contacts ){
		return;
	}

	foreach( $user_contacts as $contact ){
		$wpdb->insert(
			"{$wpdb->prefix}wsluserscontacts", 
				array( 
					"user_id" 		=> $user_id,
					"provider" 		=> $provider,
					"identifier" 	=> $contact->identifier,
					"full_name" 	=> $contact->displayName,
					"email" 		=> $contact->email,
					"profile_url" 	=> $contact->profileURL,
					"photo_url" 	=> $contact->photoURL,
				)
			); 
	}
}

// --------------------------------------------------------------------

/**
* Buddypress Profile mappings
*
* map hybridauth profile to buddypress xprofile table
*/
function wsl_buddypress_xprofile_mapping( $user_id, $provider, $hybridauth_user_profile )
{
	// component Buddypress should be enabled
	if( ! wsl_is_component_enabled( 'buddypress' ) ){
		return;
	}
	
	// make sure buddypress is loaded. 
	// > is this a legit way to check?
	if( ! function_exists( 'xprofile_set_field_data' ) ){
		return;
	}

	// check if profiles mapping is enabled
	$wsl_settings_buddypress_enable_mapping = get_option( 'wsl_settings_buddypress_enable_mapping' );
	
	if( $wsl_settings_buddypress_enable_mapping != 1 ){
		return;
	}

	// get current mapping
	$wsl_settings_buddypress_xprofile_map = get_option( 'wsl_settings_buddypress_xprofile_map' );

	$hybridauth_fields = array(  
		'identifier'   ,
		'profileURL'   ,
		'webSiteURL'   ,
		'photoURL'     ,
		'displayName'  ,
		'description'  ,
		'firstName'    ,
		'lastName'     ,
		'gender'       ,
		'language'     ,
		'age'          ,
		'birthDay'     ,
		'birthMonth'   ,
		'birthYear'    ,
		'email'        , 
		'phone'        ,
		'address'      ,
		'country'      ,
		'region'       ,
		'city'         ,
		'zip'          ,
	);
	
	$hybridauth_user_profile = (array) $hybridauth_user_profile;

	// all check: start mapping process
	if( $wsl_settings_buddypress_xprofile_map ){
		foreach( $wsl_settings_buddypress_xprofile_map as $buddypress_field_id => $field_name ){
			// if data can be found in hybridauth profile
			if( in_array( $field_name, $hybridauth_fields ) ){
				$value = $hybridauth_user_profile[ $field_name ];

				xprofile_set_field_data( $buddypress_field_id, $user_id, $value );
			}

			// if eq provider
			if( $field_name == 'provider' ){
				xprofile_set_field_data( $buddypress_field_id, $user_id, $provider );
			}

			// if eq birthDate
			if( $field_name == 'birthDate' ){
				$value = 
					str_pad( (int) $hybridauth_user_profile[ 'birthYear'  ], 4, '0', STR_PAD_LEFT )
					. '-' . 
					str_pad( (int) $hybridauth_user_profile[ 'birthMonth' ], 2, '0', STR_PAD_LEFT )
					. '-' . 
					str_pad( (int) $hybridauth_user_profile[ 'birthDay'   ], 2, '0', STR_PAD_LEFT )
					. ' 00:00:00';

				xprofile_set_field_data( $buddypress_field_id, $user_id, $value );
			}
		}
	}
}

// --------------------------------------------------------------------

function wsl_delete_stored_hybridauth_user_data( $user_id )
{
    global $wpdb;

    $sql = "DELETE FROM `{$wpdb->prefix}wslusersprofiles` where user_id = '$user_id'";
    $wpdb->query( $sql );

    $sql = "DELETE FROM `{$wpdb->prefix}wsluserscontacts` where user_id = '$user_id'";
    $wpdb->query( $sql );

	delete_user_meta( $user_id, 'wsl_current_provider'   );
	delete_user_meta( $user_id, 'wsl_current_user_image' );
}

add_action( 'delete_user', 'wsl_delete_stored_hybridauth_user_data' );

// --------------------------------------------------------------------
