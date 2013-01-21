<?php
/* 
function wsl_get_user_by_meta_key_and_user_id( $meta_key, $user_id ){
	global $wpdb;

	$sql = "SELECT meta_value FROM `{$wpdb->prefix}usermeta` where meta_key = '$meta_key' and user_id = '$user_id'";
	$rs  = $wpdb->get_results( $sql );

	return $rs[0]->meta_value;
}

function wsl_get_user_data_by_user_id( $field, $user_id ){
	global $wpdb;
	
	$sql = "SELECT $field as data_field FROM `{$wpdb->prefix}users` where ID = '$user_id'";
	$rs  = $wpdb->get_results( $sql );

	return $rs[0]->data_field;
} 
*/

function wsl_get_user_linked_account_by_user_id( $user_id )
{
	global $wpdb;

	$sql = "SELECT * FROM `{$wpdb->prefix}wslusersprofiles` where user_id = '$user_id'";
	$rs  = $wpdb->get_results( $sql );

	return $rs;
}

function wsl_get_contact_data_by_user_id( $field, $contact_id ){
	global $wpdb;
	
	$sql = "SELECT $field as data_field FROM `{$wpdb->prefix}wsluserscontacts` where ID = '$contact_id'";
	$rs  = $wpdb->get_results( $sql );

	return $rs[0]->data_field;
}
