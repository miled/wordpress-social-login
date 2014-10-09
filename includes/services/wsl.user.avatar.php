<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*    (c) 2011-2014 Mohamed Mrassi and contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

/** 
* Displaying the user avatar when available on the comment section and top bar 
*
* These functions are borrowed from http://wordpress.org/extend/plugins/oa-social-login/ 
*/

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------

/** 
* Display users avatars from social networks when available
*/
function wsl_user_custom_avatar($avatar, $mixed, $size, $default, $alt)
{
	//Check if avatars are enabled
	if( ! get_option( 'wsl_settings_users_avatars' ) )
	{
		return $avatar;
	}

	//Current comment
	global $comment;

	//Chosen user
	$user_id = null;

	//Check if we have an user identifier
	if(is_numeric($mixed))
	{
		if($mixed > 0)
		{
			$user_id = $mixed;
		}
	}

	//Check if we are in a comment
	elseif(is_object($comment) AND property_exists($comment, 'user_id') AND !empty($comment->user_id))
	{
		$user_id = $comment->user_id;
	}

	//Check if we have an email
	elseif(is_string($mixed) &&($user = get_user_by('email', $mixed)))
	{
		$user_id = $user->ID;
	}

	//Check if we have an user object
	else if(is_object($mixed))
	{
		if(property_exists($mixed, 'user_id') AND is_numeric($mixed->user_id))
		{
			$user_id = $mixed->user_id;
		}
	}

	//User found?
	if( $user_id )
	{
		$user_thumbnail = wsl_get_user_custom_avatar( $user_id );

		if( $user_thumbnail )
		{
			return '<img src="' . $user_thumbnail . '" class="avatar avatar-wordpress-social-login avatar-' . $size . ' photo" height="' . $size . '" width="' . $size . '" />'; 
		}
	}

	return $avatar;
}

add_filter( 'get_avatar', 'wsl_user_custom_avatar', 10, 5 );

// --------------------------------------------------------------------

/**
* Display users avatars from social networks on buddypress
*/
function wsl_bp_user_custom_avatar($text, $args)
{
	//Buddypress component should be enabled
	if( ! wsl_is_component_enabled( 'buddypress' ) ){
		return $text;
	}

	//Check if avatars display is enabled
	if( ! get_option( 'wsl_settings_users_avatars' ) )
	{
		return $text;
	}

	//Check arguments
	if(is_array($args))
	{
		//User Object
		if( ! empty( $args['object'] ) AND strtolower( $args ['object'] ) == 'user' )
		{
			//User Identifier
			if( ! empty( $args ['item_id'] ) AND is_numeric( $args ['item_id'] ) )
			{
				$user_data = get_userdata( $args ['item_id'] );

				//Retrieve user
				if( $user_data !== false )
				{ 
					$user_thumbnail = wsl_get_user_custom_avatar( $args['item_id'] );

					//Retrieve Avatar
					if( $user_thumbnail !== false)
					{
						//Thumbnail retrieved
						if( strlen( trim( $user_thumbnail ) ) > 0 )
						{
							//Build Image tags
							$img_alt = "";

							$img_class  = ('class="' .(!empty($args ['class']) ?($args ['class'] . ' ') : '') . 'avatar-wordpress-social-login" ');
							$img_width  = (!empty($args ['width']) ? 'width="' . $args ['width'] . '" ' : '');
							$img_height = (!empty($args ['height']) ? 'height="' . $args ['height'] . '" ' : '');

							//Replace
							$text = preg_replace('#<img[^>]+>#i', '<img src="' . $user_thumbnail . '" ' . $img_alt . $img_class . $img_height . $img_width . '/>', $text);
						}
					}
				}
			}
		}
	} 

	return $text;
}

add_filter( 'bp_core_fetch_avatar', 'wsl_bp_user_custom_avatar', 10, 2 );

// --------------------------------------------------------------------
