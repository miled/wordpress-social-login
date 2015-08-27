<?php
/*!
* WordPress Social Login
*
* http://miled.github.io/wordpress-social-login/ | https://github.com/miled/wordpress-social-login
*  (c) 2011-2015 Mohamed Mrassi and contributors | http://wordpress.org/plugins/wordpress-social-login/
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
* 
* Note: 
*   You may redefine this function
*/
if( ! function_exists( 'wsl_get_wp_user_custom_avatar' ) )
{
	function wsl_get_wp_user_custom_avatar($html, $mixed, $size, $default, $alt)
	{
		//Check if avatars are enabled
		if( ! get_option( 'wsl_settings_users_avatars' ) )
		{
			return $html;
		}

		//Only Overwrite gravatars
		if( ! stristr( strtolower( $html ), 'gravatar.com' ) )
		{
			return $html;
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
			$wsl_avatar = wsl_get_user_custom_avatar( $user_id );

			if( $wsl_avatar )
			{
				$wsl_html = '<img alt="'. $alt .'" src="' . $wsl_avatar . '" class="avatar avatar-wordpress-social-login avatar-' . $size . ' photo" height="' . $size . '" width="' . $size . '" />';

				// HOOKABLE: 
				return apply_filters( 'wsl_hook_alter_wp_user_custom_avatar', $wsl_html, $user_id, $wsl_avatar, $html, $mixed, $size, $default, $alt );
			}
		}

		return $html;
	}
}

add_filter( 'get_avatar', 'wsl_get_wp_user_custom_avatar', 10, 5 );

// --------------------------------------------------------------------

/**
* Display users avatars from social networks on buddypress
* 
* Note: 
*   You may redefine this function
*/
if( ! function_exists( 'wsl_get_bp_user_custom_avatar' ) )
{
	function wsl_get_bp_user_custom_avatar($html, $args)
	{
		//Buddypress component should be enabled
		if( ! wsl_is_component_enabled( 'buddypress' ) )
		{
			return $html;
		}

		//Check if avatars display is enabled
		if( ! get_option( 'wsl_settings_users_avatars' ) )
		{
			return $html;
		}

		//Check arguments
		if( is_array( $args ) )
		{
			//User Object
			if( ! empty( $args['object'] ) AND strtolower( $args['object'] ) == 'user' )
			{
				//User Identifier
				if( ! empty( $args['item_id'] ) AND is_numeric( $args['item_id'] ) )
				{
					$user_id = $args['item_id'];

					//Only Overwrite gravatars
					# https://wordpress.org/support/topic/buddypress-avatar-overwriting-problem?replies=1
					if( bp_get_user_has_avatar( $user_id ) )
					{
						return $html;
					}

					$wsl_avatar = wsl_get_user_custom_avatar( $user_id );

					//Retrieve Avatar
					if( $wsl_avatar )
					{
						$img_class  = ('class="' .(!empty($args ['class']) ?($args ['class'] . ' ') : '') . 'avatar-wordpress-social-login" ');
						$img_width  = (!empty($args ['width']) ? 'width="' . $args ['width'] . '" ' : 'width="' . bp_core_avatar_full_width() . '" ' );
						$img_height = (!empty($args ['height']) ? 'height="' . $args ['height'] . '" ' : 'height="' . bp_core_avatar_full_height() . '" ' );
						$img_alt    = (!empty( $args['alt'] ) ? 'alt="' . esc_attr( $args['alt'] ) . '" ' : '' );

						//Replace
						$wsl_html = preg_replace('#<img[^>]+>#i', '<img src="' . $wsl_avatar . '" ' . $img_alt . $img_class . $img_height . $img_width . '/>', $html );

						// HOOKABLE: 
						return apply_filters( 'wsl_hook_alter_get_bp_user_custom_avatar', $wsl_html, $user_id, $wsl_avatar, $html, $args );
					}
				}
			}
		}

		return $html;
	}
}

add_filter( 'bp_core_fetch_avatar', 'wsl_get_bp_user_custom_avatar', 10, 2 );

// --------------------------------------------------------------------
