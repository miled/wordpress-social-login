<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*    (c) 2011-2014 Mohamed Mrassi and contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

/**
* Wannabe Contact Manager module
*/

// --------------------------------------------------------------------

function wsl_component_contacts()
{
	// HOOKABLE: 
	do_action( "wsl_component_contacts_start" );

	include "wsl.components.contacts.list.php";
	include "wsl.components.contacts.settings.php";

	if( isset( $_REQUEST["uid"] ) && $_REQUEST["uid"] )
	{
		$user_id = (int) $_REQUEST["uid"];

		wsl_component_contacts_list( $user_id );
	}
	else
	{
		wsl_component_contacts_settings();
	}

	// HOOKABLE: 
	do_action( "wsl_component_contacts_end" );
}

wsl_component_contacts();

// --------------------------------------------------------------------	
