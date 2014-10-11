<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*    (c) 2011-2014 Mohamed Mrassi and contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

/**
* Components Manager 
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 

// --------------------------------------------------------------------

function wsl_component_components_gallery()
{
	// not for today
	// return;

	// HOOKABLE: 
	do_action( "wsl_component_components_gallery_start" ); 

	//..

	// HOOKABLE: 
	do_action( "wsl_component_components_gallery_end" );
}

// --------------------------------------------------------------------	
