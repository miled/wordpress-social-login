<?php
/*!
* WordPress Social Login
*
* http://miled.github.io/wordpress-social-login/ | https://github.com/miled/wordpress-social-login
*  (c) 2011-2015 Mohamed Mrassi and contributors | http://wordpress.org/plugins/wordpress-social-login/
*/

/** 
* User data functions (database related)
*
* Notes:
*   1. This entire file will be rewroked in future versions based on a lightweight ORM.
*   2. The current code is loosely commented: functions names should be self-explanatory.
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------

/**
* Checks whether the given email exists in WordPress users tables.
*
* This function is not loaded by default in wp 3.0
*
* https://core.trac.wordpress.org/browser/tags/4.0/src/wp-includes/user.php#L1565
*/
function wsl_wp_email_exists( $email )
{
	if( function_exists('email_exists') )
	{
		return email_exists( $email );
	}

	if( $user = get_user_by( 'email', $email ) )
	{
		return $user->ID;
	}
}

// --------------------------------------------------------------------

function wsl_get_wordpess_users_count()
{
	global $wpdb;

	$sql = "SELECT COUNT( * ) AS items FROM `{$wpdb->prefix}users`"; 

	return $wpdb->get_var( $sql );
}

// --------------------------------------------------------------------

function wsl_get_wsl_users_count()
{
	global $wpdb;

	$sql = "SELECT COUNT( distinct user_id ) AS items FROM `{$wpdb->prefix}wslusersprofiles`"; 

	return $wpdb->get_var( $sql );
}

// --------------------------------------------------------------------

function wsl_get_user_custom_avatar( $user_id )
{
	$user_avatar = get_user_meta( $user_id, 'wsl_current_user_image', true );

	// prior to 2.2
	if( ! $user_avatar )
	{
		$user_avatar = get_user_meta( $user_id, 'wsl_user_image', true );
	}

	return $user_avatar;
}

// --------------------------------------------------------------------

function wsl_get_stored_hybridauth_user_profiles_count()
{
	global $wpdb;

	$sql = "SELECT COUNT(`id`) FROM `{$wpdb->prefix}wslusersprofiles`"; 

	return $wpdb->get_var( $sql );
}

// --------------------------------------------------------------------

function wsl_get_stored_hybridauth_user_profiles_count_by_field( $field )
{
	global $wpdb;

	$sql = "SELECT $field, COUNT( * ) AS items FROM `{$wpdb->prefix}wslusersprofiles` GROUP BY $field ORDER BY items DESC";

	return $wpdb->get_results( $sql );
}

// --------------------------------------------------------------------

function wsl_get_stored_hybridauth_user_profiles_grouped_by_user_id( $offset, $limit )
{
	global $wpdb;

	$sql = "SELECT * FROM `{$wpdb->prefix}wslusersprofiles` GROUP BY user_id LIMIT %d, %d";

	return $wpdb->get_results( $wpdb->prepare( $sql, $offset, $limit ) );
}

// --------------------------------------------------------------------

function wsl_get_stored_hybridauth_user_contacts_count_by_user_id( $user_id )
{
	global $wpdb;

	$sql = "SELECT COUNT( * ) FROM `{$wpdb->prefix}wsluserscontacts` where user_id = %d";

	return $wpdb->get_var( $wpdb->prepare( $sql, $user_id ) );
}

// --------------------------------------------------------------------

function wsl_get_stored_hybridauth_user_contacts_by_user_id( $user_id, $offset, $limit )
{
	global $wpdb;

	$sql = "SELECT * FROM `{$wpdb->prefix}wsluserscontacts` where user_id = %d LIMIT %d, %d";

	return $wpdb->get_results( $wpdb->prepare( $sql, $user_id, $offset, $limit ) );
}

// --------------------------------------------------------------------

function wsl_get_stored_hybridauth_user_id_by_provider_and_provider_uid( $provider, $provider_uid )
{
	global $wpdb;

	$sql = "SELECT user_id FROM `{$wpdb->prefix}wslusersprofiles` WHERE provider = %s AND identifier = %s";

	return $wpdb->get_var( $wpdb->prepare( $sql, $provider, $provider_uid ) );
}

// --------------------------------------------------------------------

function wsl_get_stored_hybridauth_user_id_by_email_verified( $email_verified )
{
	global $wpdb;

	$sql = "SELECT user_id FROM `{$wpdb->prefix}wslusersprofiles` WHERE emailverified = %s";

	return $wpdb->get_var( $wpdb->prepare( $sql, $email_verified ) );
}

// --------------------------------------------------------------------

function wsl_get_stored_hybridauth_user_profile_by_provider_and_provider_uid( $provider, $provider_uid )
{
	global $wpdb;

	$sql = "SELECT * FROM `{$wpdb->prefix}wslusersprofiles` WHERE provider = %s AND identifier = %s";

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

	$sql = "SELECT * FROM `{$wpdb->prefix}wslusersprofiles` where user_id = %d order by provider";

	return $wpdb->get_results( $wpdb->prepare( $sql, $user_id ) );
}

// --------------------------------------------------------------------

function wsl_store_hybridauth_user_profile( $user_id, $provider, $profile )
{
	global $wpdb;
	
	$wpdb->show_errors(); 

	$sql = "SELECT id, object_sha FROM `{$wpdb->prefix}wslusersprofiles` where user_id = %d and provider = %s and identifier = %s";
	
	$rs  = $wpdb->get_results( $wpdb->prepare( $sql, $user_id, $provider, $profile->identifier ) );

	// we only sotre the user profile if it has changed since last login.
	$object_sha = sha1( serialize( $profile ) );

	// checksum
	if( ! empty( $rs ) && $rs[0]->object_sha == $object_sha )
	{
		return;
	}

	$table_data = array(
		"id"         => 'null',
		"user_id"    => $user_id,
		"provider"   => $provider,
		"object_sha" => $object_sha
	);

	if(  ! empty( $rs ) )
	{
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

	foreach( $profile as $key => $value )
	{
		$key = strtolower($key);

		if( in_array( $key, $fields ) )
		{
			$table_data[ $key ] = (string) $value;
		}
	}

	$wpdb->replace( "{$wpdb->prefix}wslusersprofiles", $table_data ); 

	return $wpdb->insert_id;
}

// --------------------------------------------------------------------

function wsl_store_hybridauth_user_contacts( $user_id, $provider, $adapter )
{
	// component contact should be enabled
	if( ! wsl_is_component_enabled( 'contacts' ) )
	{
		return;
	}

	// check if import is enabled for the given provider
	if(
		! (
			get_option( 'wsl_settings_contacts_import_facebook' )  == 1 && strtolower( $provider ) == "facebook"   ||
			get_option( 'wsl_settings_contacts_import_google' )    == 1 && strtolower( $provider ) == "google"     ||
			get_option( 'wsl_settings_contacts_import_twitter' )   == 1 && strtolower( $provider ) == "twitter"    ||
			get_option( 'wsl_settings_contacts_import_linkedin' )  == 1 && strtolower( $provider ) == "linkedin"   || 
			get_option( 'wsl_settings_contacts_import_live' )      == 1 && strtolower( $provider ) == "live"       ||
			get_option( 'wsl_settings_contacts_import_vkontakte' ) == 1 && strtolower( $provider ) == "vkontakte"
		)
	)
	{
		return;
	}

	global $wpdb;

	$user_contacts = null;

	// we only import contacts once
	$sql = "SELECT COUNT(`id`) FROM {$wpdb->prefix}wsluserscontacts WHERE user_id = %d AND provider = %s ";

	$nb_contacts = $wpdb->get_var( $wpdb->prepare( $sql, $user_id, $provider ) );

	if( $nb_contacts )
	{
		return;
	}

	// attempt to grab the user's friends list via social network api
	try
	{
		$user_contacts = $adapter->getUserContacts();
	}
	catch( Exception $e )
	{ 
		// well.. we can't do much.
	}

	if( ! $user_contacts )
	{
		return;
	}

	foreach( $user_contacts as $contact )
	{
		$wpdb->insert(
			"{$wpdb->prefix}wsluserscontacts", 
				array( 
					"user_id"     => $user_id,
					"provider"    => $provider,
					"identifier"  => $contact->identifier,
					"full_name"   => $contact->displayName,
					"email"       => $contact->email,
					"profile_url" => $contact->profileURL,
					"photo_url"   => $contact->photoURL,
				)
			); 
	}
}

// --------------------------------------------------------------------

function wsl_buddypress_xprofile_mapping( $user_id, $provider, $hybridauth_user_profile )
{
	// component Buddypress should be enabled
	if( ! wsl_is_component_enabled( 'buddypress' ) )
	{
		return;
	}

	do_action('bp_setup_globals');

	// make sure buddypress is loaded. 
	// > is this a legit way to check?
	if( ! function_exists( 'xprofile_set_field_data' ) )
	{
		return;
	}

	// check if profiles mapping is enabled
	$wsl_settings_buddypress_enable_mapping = get_option( 'wsl_settings_buddypress_enable_mapping' );
	
	if( $wsl_settings_buddypress_enable_mapping != 1 )
	{
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
	if( $wsl_settings_buddypress_xprofile_map )
	{
		foreach( $wsl_settings_buddypress_xprofile_map as $buddypress_field_id => $field_name )
		{
			// if data can be found in hybridauth profile
			if( in_array( $field_name, $hybridauth_fields ) )
			{
				$value = $hybridauth_user_profile[ $field_name ];

				xprofile_set_field_data( $buddypress_field_id, $user_id, $value );
			}

			// if eq provider
			if( $field_name == 'provider' )
			{
				xprofile_set_field_data( $buddypress_field_id, $user_id, $provider );
			}

			// if eq birthDate
			if( $field_name == 'birthDate' )
			{
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

	$sql = "DELETE FROM `{$wpdb->prefix}wslusersprofiles` where user_id = %d";
	$wpdb->query( $wpdb->prepare( $sql, $user_id ) );

	$sql = "DELETE FROM `{$wpdb->prefix}wsluserscontacts` where user_id = %d";
	$wpdb->query( $wpdb->prepare( $sql, $user_id ) );

	delete_user_meta( $user_id, 'wsl_current_provider'   );
	delete_user_meta( $user_id, 'wsl_current_user_image' );
}

add_action( 'delete_user', 'wsl_delete_stored_hybridauth_user_data' );

// --------------------------------------------------------------------
