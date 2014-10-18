<?php
/*!
* WordPress Social Login
*
* http://miled.github.io/wordpress-social-login/ | https://github.com/miled/wordpress-social-login
*  (c) 2011-2014 Mohamed Mrassi and contributors | http://wordpress.org/plugins/wordpress-social-login/
*/

/** 
* Attempt to recognize other Social Plugins users 
*
* These functions obviously need to be optimized and lots of tests.
*/

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------

/**
* Social Connect
*
* SC store its users id in usermeta
*
* https://wordpress.org/plugins/social-connect/
*/
function wsl_get_user_social_connect( $provider, $identifier )
{
	if( ! class_exists( 'WP_User_Query', false ) )
	{
		return;
	}

	$user_query = new WP_User_Query(array(
		'meta_key'   => 'social_connect_' . strtolower( $provider ) . '_id',
		'meta_value' => identifier,
	));

	$user_data = $user_query->get_results();

	if( count( $user_data ) == 1 )
	{
		return $user_data[0]->ID;
	}
}

// --------------------------------------------------------------------

/**
* Super Socializer
* 
* The champ sotre its users id in usermeta
*
* https://wordpress.org/plugins/super-socializer/
*/
function wsl_get_user_the_champ( $provider, $identifier )
{
	if( ! class_exists( 'WP_User_Query', false ) )
	{
		return;
	}

	$user_query = new WP_User_Query(array(
		'meta_query' => array(
			'relation' => 'AND',
			0 => array(
				'key'     => 'thechamp_social_id',
				'value'   => $identifier, 
			),
			1 => array(
				'key'     => 'thechamp_provider',
				'value'   =>  strtolower( $provider ), 
			)
		)
	));

	$user_data = $user_query->get_results();

	if( count( $user_data ) == 1 )
	{
		return $user_data[0]->ID;
	}
}

// --------------------------------------------------------------------

/**
*
*/
function wsl_get_user_nextend( $provider, $identifier )
{
	if( in_array( $provider, array( 'Facebook', 'Google', 'Twitter' ) ) )
	{
		return;
	}

}

// --------------------------------------------------------------------

/**
*
*/
function wsl_get_user_fb_auto( $provider, $identifier )
{
	if( 'Facebook' != $provider )
	{
		return;
	}

	if( ! class_exists( 'WP_User_Query', false ) )
	{
		return;
	}

	$user_query = new WP_User_Query(array(
		'meta_key'   => 'facebook_uid',
		'meta_value' => $identifier,
	));

	$user_data = $user_query->get_results();

	if( count( $user_data ) == 1 )
	{
		return $user_data[0]->ID;
	}
}

// --------------------------------------------------------------------

/**
*
*/
function wsl_get_user_fb_all( $provider, $identifier )
{
	if( 'Facebook' != $provider )
	{
		return;
	}

}

// --------------------------------------------------------------------

/**
*
*/
function wsl_get_user_login_radius( $provider, $identifier )
{
	if( ! class_exists( 'WP_User_Query', false ) )
	{
		return;
	}

	$user_query = new WP_User_Query(array(
		'meta_key'   => strtolower( $provider ) . 'Lrid',
		'meta_value' => $identifier,
	));

	$user_data = $user_query->get_results();

	if( count( $user_data ) == 1 )
	{
		return $user_data[0]->ID;
	}
}

// --------------------------------------------------------------------
