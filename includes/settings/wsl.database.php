<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*    (c) 2011-2013 Mohamed Mrassi and contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

/**
* Functions & utililies related to wsl database installation and migrations
*
* After WSl activated, wsl_database_migration_process will attempt to create or upgrade the required database
* tables.
*
* Currently there is 2 tables used by WSL :
*	- wslusersprofiles:  where sotred the users profile as provided by Hybridauth
*	- wsluserscontacts:  where sotred the users contact list as provided by Hybridauth
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------

global $wsl_database_migration_version;

$wsl_database_migration_version = 4;

// --------------------------------------------------------------------

function wsl_database_migration_hook ()
{
    wsl_database_migration_process();
}

add_action( 'plugins_loaded', 'wsl_database_migration_process' );

// --------------------------------------------------------------------

function wsl_database_migration_process()
{
    global $wpdb;
    global $wsl_database_migration_version;
	
    $wsluserscontacts = "{$wpdb->prefix}wsluserscontacts";
    $wslusersprofiles = "{$wpdb->prefix}wslusersprofiles";
    $installed_ver    = get_option( "wsl_database_migration_version" );

    if( $installed_ver != $wsl_database_migration_version ) { 
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$sql = "CREATE TABLE " . $wsluserscontacts . " (
					id int(11) NOT NULL AUTO_INCREMENT,
					user_id int(11) NOT NULL,
					provider varchar(50) NOT NULL,
					identifier varchar(255) NOT NULL,
					full_name varchar(255) NOT NULL,
					email varchar(255) NOT NULL,
					profile_url varchar(255) NOT NULL,
					photo_url varchar(255) NOT NULL,
					PRIMARY KEY (id)
				);"; 
		dbDelta( $sql );

		$sql = "CREATE TABLE " . $wslusersprofiles . " ( 
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`user_id` int(11) NOT NULL,
				`provider` varchar(255) NOT NULL,
				`object_sha` varchar(255) NOT NULL COMMENT 'to check if hybridauth user profile object has changed from last time, if yes we update the user profile here ',
				`identifier` varchar(255) NOT NULL,
				`profileurl` varchar(255) NOT NULL,
				`websiteurl` varchar(255) NOT NULL,
				`photourl` varchar(255) NOT NULL,
				`displayname` varchar(255) NOT NULL,
				`description` varchar(255) NOT NULL,
				`firstname` varchar(255) NOT NULL,
				`lastname` varchar(255) NOT NULL,
				`gender` varchar(255) NOT NULL,
				`language` varchar(255) NOT NULL,
				`age` varchar(255) NOT NULL,
				`birthday` varchar(255) NOT NULL,
				`birthmonth` varchar(255) NOT NULL,
				`birthyear` varchar(255) NOT NULL,
				`email` varchar(255) NOT NULL,
				`emailverified` varchar(255) NOT NULL,
				`phone` varchar(255) NOT NULL,
				`address` varchar(255) NOT NULL,
				`country` varchar(255) NOT NULL,
				`region` varchar(255) NOT NULL,
				`city` varchar(255) NOT NULL,
				`zip` varchar(255) NOT NULL,
				PRIMARY KEY (`id`)
			)"; 
		dbDelta( $sql ); 

		update_option( "wsl_database_migration_version", $wsl_database_migration_version );
	}

    add_option( "wsl_database_migration_version", $wsl_database_migration_version );
}

// --------------------------------------------------------------------
