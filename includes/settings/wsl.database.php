<?php
/*!
* WordPress Social Login
*
* http://miled.github.io/wordpress-social-login/ | https://github.com/miled/wordpress-social-login
*  (c) 2011-2014 Mohamed Mrassi and contributors | http://wordpress.org/plugins/wordpress-social-login/
*/

/**
* Functions & utilities related to WSL database installation and migrations
*
* When WSl is activated, wsl_database_migration_process() will attempt to create or upgrade the required database
* tables.
*
* Currently there is 2 tables used by WSL :
*	- wslusersprofiles:  where we store users profiles
*	- wsluserscontacts:  where we store users contact lists
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
// --------------------------------------------------------------------

function wsl_database_migration_hook()
{
	wsl_database_migration_process();
}

// --------------------------------------------------------------------

function wsl_database_migration_process()
{
	global $wpdb;

	// update/migrate wsl-settings
	wsl_check_compatibilities();

	// wsl tables names
	$wsluserscontacts = "{$wpdb->prefix}wsluserscontacts";
	$wslusersprofiles = "{$wpdb->prefix}wslusersprofiles";

	// create wsl tables
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	$sql = "CREATE TABLE " . $wslusersprofiles . " ( 
			id int(11) NOT NULL AUTO_INCREMENT,
			user_id int(11) NOT NULL,
			provider varchar(50) NOT NULL,
			object_sha varchar(255) NOT NULL,
			identifier varchar(255) NOT NULL,
			profileurl varchar(255) NOT NULL,
			websiteurl varchar(255) NOT NULL,
			photourl varchar(255) NOT NULL,
			displayname varchar(150) NOT NULL,
			description varchar(255) NOT NULL,
			firstname varchar(150) NOT NULL,
			lastname varchar(150) NOT NULL,
			gender varchar(10) NOT NULL,
			language varchar(20) NOT NULL,
			age varchar(10) NOT NULL,
			birthday int(11) NOT NULL,
			birthmonth int(11) NOT NULL,
			birthyear int(11) NOT NULL,
			email varchar(255) NOT NULL,
			emailverified varchar(255) NOT NULL,
			phone varchar(75) NOT NULL,
			address varchar(255) NOT NULL,
			country varchar(75) NOT NULL,
			region varchar(50) NOT NULL,
			city varchar(50) NOT NULL,
			zip varchar(25) NOT NULL,
			UNIQUE KEY id (id),
			KEY idp_uid (provider,identifier),
			KEY user_id (user_id)
		)"; 
	dbDelta( $sql );

	$sql = "CREATE TABLE " . $wsluserscontacts . " (
			id int(11) NOT NULL AUTO_INCREMENT,
			user_id int(11) NOT NULL,
			provider varchar(50) NOT NULL,
			identifier varchar(255) NOT NULL,
			full_name varchar(150) NOT NULL,
			email varchar(255) NOT NULL,
			profile_url varchar(255) NOT NULL,
			photo_url varchar(255) NOT NULL,
			UNIQUE KEY id (id),
			KEY user_id (user_id)
		)"; 
	dbDelta( $sql );
}

// --------------------------------------------------------------------
