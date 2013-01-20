<?php
/**
 * Display custom avatars
 * borrowed from http://wordpress.org/extend/plugins/oa-social-login/
 *
 * thanks a million mate
 */
function wsl_user_custom_avatar($avatar, $id_or_email, $size, $default, $alt)
{
	//Check if we are in a comment
	if( get_option ( 'wsl_settings_users_avatars' ) )
	{
		//Current comment
		global $comment;

		//Chosen user
		$user_id = null;

		//Check if we are in a comment
		if (is_object ($comment) AND property_exists ($comment, 'user_id') AND !empty ($comment->user_id))
		{
			$user_id = $comment->user_id;
		}
		//Check if we have an user identifier
		elseif (is_numeric ($mixed))
		{
			if ($mixed > 0)
			{
				$user_id = $mixed;
			}
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
				return '<img alt="" src="' . $user_thumbnail . '" class="avatar avatar-wordpress-social-login avatar-' . $size . ' photo" height="' . $size . '" width="' . $size . '" />'; 
			}
		}
	}

	return $avatar;
}

add_filter( 'get_avatar', 'wsl_user_custom_avatar', 10, 5 );
