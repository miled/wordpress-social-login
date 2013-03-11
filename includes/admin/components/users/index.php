<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*    (c) 2011-2013 Mohamed Mrassi and contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

/**
* Wannabe Users Manager module
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 

// --------------------------------------------------------------------

function wsl_component_users()
{
	// HOOKABLE: 
	do_action( "wsl_component_users_start" );

	include "wsl.components.users.list.php";
	include "wsl.components.users.profile.php";

	if( isset( $_REQUEST["uid"] ) && $_REQUEST["uid"] ){
		$user_id = (int) $_REQUEST["uid"];

		wsl_component_users_profile( $user_id );
	}
	else{
		wsl_component_users_list();
	}

	// HOOKABLE: 
	do_action( "wsl_component_users_end" );
}

wsl_component_users();

// --------------------------------------------------------------------	
