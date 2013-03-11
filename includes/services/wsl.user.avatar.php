<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*    (c) 2011-2013 Mohamed Mrassi and contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

/** 
* Displaying the user avatar when available on the comment section
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------

/** 
 * wsl_user_custom_avatar is borrowed from http://wordpress.org/extend/plugins/oa-social-login/ 
 * thanks a million mate
 */
function wsl_user_custom_avatar($avatar, $mixed, $size, $default, $alt)
{
	//Check if avatars are enabled
	if( get_option ( 'wsl_settings_users_avatars' ) )
	{
		//Current comment
		global $comment;

		//Chosen user
		$user_id = null;

		//Check if we have an user identifier
		if (is_numeric ($mixed))
		{
			if ($mixed > 0)
			{
				$user_id = $mixed;
			}
		}
		//Check if we are in a comment
		elseif (is_object ($comment) AND property_exists ($comment, 'user_id') AND !empty ($comment->user_id))
		{
			$user_id = $comment->user_id;
		}
		//Check if we have an email
		elseif (is_string ($mixed) && ($user = get_user_by ('email', $mixed)))
		{
			$user_id = $user->ID;
		}
		//Check if we have an user object
		else if (is_object ($mixed))
		{
			if (property_exists ($mixed, 'user_id') AND is_numeric ($mixed->user_id))
			{
				$user_id = $mixed->user_id;
			}
		}

		//User found?
		if ( $user_id )
		{
			$user_thumbnail = get_user_meta ( $user_id, 'wsl_user_image', true );

			if ( $user_thumbnail )
			{
				return '<img src="' . $user_thumbnail . '" class="avatar avatar-wordpress-social-login avatar-' . $size . ' photo" height="' . $size . '" width="' . $size . '" />'; 
			}
		}
	}

	return $avatar;
}

add_filter( 'get_avatar', 'wsl_user_custom_avatar', 10, 5 );

// --------------------------------------------------------------------
