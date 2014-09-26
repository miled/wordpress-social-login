<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*    (c) 2011-2013 Mohamed Mrassi and contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

/** 
* User data functions (database related)
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------

/**
* Return all user prodile stored on wslusersprofiles
*/
function wsl_get_user_linked_account_by_user_id( $user_id )
{
	global $wpdb;

	$sql = "SELECT * FROM `{$wpdb->prefix}wslusersprofiles` where user_id = '$user_id'";
	$rs  = $wpdb->get_results( $sql );

	return $rs;
}

// --------------------------------------------------------------------

/**
* Return a contact data
*/
function wsl_get_contact_data_by_user_id( $field, $contact_id ){
	global $wpdb;

	$sql = "SELECT $field as data_field FROM `{$wpdb->prefix}wsluserscontacts` where ID = '$contact_id'";
	$rs  = $wpdb->get_results( $sql );

	return ( empty( $rs ) ? '' : $rs[0]->data_field );
}

// --------------------------------------------------------------------

function wsl_get_user_by_meta( $provider, $user_uid )
{
	global $wpdb;

	$sql = "SELECT user_id FROM `{$wpdb->prefix}usermeta` WHERE meta_key = '%s' AND meta_value = '%s'";

	return $wpdb->get_var( $wpdb->prepare( $sql, $provider, $user_uid ) );
}

// --------------------------------------------------------------------

function wsl_get_user_data_by_id( $field, $user_id )
{
	global $wpdb;

	$sql = "SELECT %s FROM `{$wpdb->prefix}users` WHERE ID = '%s'";

	return $wpdb->get_var( $wpdb->prepare( $sql, $field, $user_id ) );
}

// --------------------------------------------------------------------

function wsl_get_user_linked_account_by_provider_and_identifier( $provider, $identifier )
{
	global $wpdb;

	$sql = "SELECT * FROM `{$wpdb->prefix}wslusersprofiles` where provider = '$provider' and identifier = '$identifier'";
	$rs  = $wpdb->get_results( $sql );

	return $rs;
}

// --------------------------------------------------------------------

function wsl_store_hybridauth_user_data( $user_id, $provider, $profile )
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
* We import a user contact per provider only once.
*
* We update contact list only one time per provider, this behaviour may change depend on wsl users feedback 
*/
function wsl_import_user_contacts( $user_id, $provider, $adapter )
{
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
	
	$user_contacts = null;

	// grab the user's friends list
	try{
		$user_contacts = $adapter->getUserContacts();
	}
	catch( Exception $e ){ 
		// well.. we can't do much.
	}

	if( ! $user_contacts ){
		return;
	}

	$wpdb->query( 
		$wpdb->prepare( "DELETE FROM `{$wpdb->prefix}wsluserscontacts` WHERE user_id = '%d' AND provider = '%s'", $user_id, $provider ) 
	);

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

function wsl_get_list_connected_providers()
{
	GLOBAL $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG;

	// load Hybrid_Auth
	if ( ! class_exists('Hybrid_Auth', false) ){
		require_once WORDPRESS_SOCIAL_LOGIN_ABS_PATH . "/hybridauth/Hybrid/Auth.php"; 
	}

	$config = array();
	
	foreach( $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG AS $item ){
		$provider_id = isset( $item["provider_id"] ) ? $item["provider_id"] : ''; 

		$config["providers"][$provider_id]["enabled"] = true;
	}

	$hybridauth = new Hybrid_Auth( $config );  

	return Hybrid_Auth::getConnectedProviders(); 
}

// --------------------------------------------------------------------

function wsl_get_user_linked_accounts_by_user_id( $user_id )
{
	global $wpdb;

	$sql = "SELECT * FROM `{$wpdb->prefix}wslusersprofiles` where user_id = '$user_id'";
	$rs  = $wpdb->get_results( $sql );

	return $rs;
}

// --------------------------------------------------------------------

function wsl_get_user_linked_accounts_field_by_id( $id, $field )
{
	global $wpdb;

	$sql = "SELECT $field as data_field FROM `{$wpdb->prefix}wslusersprofiles` where id = '$id'";
	$rs  = $wpdb->get_results( $sql );

	return ( empty( $rs ) ? '' : $rs[0]->data_field );
}

// --------------------------------------------------------------------

function wsl_get_user_by_meta_key_and_user_id( $meta_key, $user_id )
{
	global $wpdb;

	$sql = "SELECT meta_value FROM `{$wpdb->prefix}usermeta` where meta_key = '$meta_key' and user_id = '$user_id'";
	$rs  = $wpdb->get_results( $sql );

	return ( empty( $rs ) ? '' : $rs[0]->meta_value );
}

// --------------------------------------------------------------------

function wsl_get_user_data_by_user_id( $field, $user_id )
{
	global $wpdb;
	
	$sql = "SELECT $field as data_field FROM `{$wpdb->prefix}users` where ID = '$user_id'";
	$rs  = $wpdb->get_results( $sql );

	return ( empty( $rs ) ? '' : $rs[0]->data_field );
}

// --------------------------------------------------------------------

function wsl_delete_userprofiles( $user_id )
{
    global $wpdb;

    $sql = "DELETE FROM `{$wpdb->prefix}wslusersprofiles` where user_id = '$user_id'";
    $wpdb->query( $sql );
}

add_action( 'delete_user', 'wsl_delete_userprofiles' );

// --------------------------------------------------------------------

function wsl_delete_usercontacts( $user_id )
{
    global $wpdb;

    $sql = "DELETE FROM `{$wpdb->prefix}wsluserscontacts` where user_id = '$user_id'";
    $wpdb->query( $sql );
}

add_action( 'delete_user', 'wsl_delete_usercontacts' );

// --------------------------------------------------------------------
