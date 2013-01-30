<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*   (c) 2013 Mohamed Mrassi and other contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

/**
* Here's where the dragon resides.
*
* Authenticate users via social networks. 
*
* to sum things up, here is how stuff works  when a wild visitor appear and click on a provider icon:
*
*	[icons links]                                  icons will redirect the user to services/authenticate.php;
*		=> [services/authenticate.php]             services/authenticate.php will attempt to authenticate him throught Hybridauth Library;
*			=> [Hybridauth] <=> [Provider]         Hybridauth and the Provider will have some little chat on their own;
*				=> [services/authenticate.php]     If the visitor consent and agrees to authenticate, then horray for you;
*					=> [wp-login.php]              authenticate.php will then redirect the user to back wp-login.php where wsl_process_login() is fired;
*						=> [callback URL]          If things goes as expected, the wsl_process_login will log the user on your website and redirect him (again lolz) there.
*
* when wsl_process_login() is triggered, it will attempt to reconize the user.
* If he exist on the database as WSL user, then fine we cut things short.
* If not, attempt to reconize users based on his email (this only when users authenticate through Facebook, Google, Yahoo or Foursquare as these provides verified emails). 
* Otherwise create new account for him.
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------

function wsl_process_login()
{
	if( ! wsl_process_login_checks() ){
		return null;
	}

	// HOOKABLE: 
	do_action( "wsl_hook_process_login_before_start" );

	// HOOKABLE: 
	$redirect_to = apply_filters("wsl_process_login_get_redirect_to", wsl_process_login_get_redirect_to() ) ;

	// HOOKABLE: 
	$provider = apply_filters("wsl_process_login_get_provider", wsl_process_login_get_provider() ) ;

	// authenticate user via a social network ( $provider )
	list( 
		$user_id                    , // ..
		$adapter                    , // ..
		$hybridauth_user_profile    , // ..
		$hybridauth_user_id         , // ..
		$hybridauth_user_email      , // ..
		$request_user_login         , // .. 
		$request_user_email         , // ..  
	)
	= wsl_process_login_hybridauth_authenticate( $provider, $redirect_to );

	// if user found on database
	if( $user_id ){
		$user_data  = get_userdata( $user_id );
		$user_login = $user_data->user_login; 
		$user_email = $hybridauth_user_profile->email; 
	}
	
	// otherwise, create new user and associate provider identity
	else{ 
		list(
			$user_id    , // ..
			$user_login , // ..
			$user_email , // ..
		)
		= wsl_process_login_create_wp_user( $provider, $hybridauth_user_profile, $request_user_login, $request_user_email );
	}

	// finally create a wp session for the user
	return wsl_process_login_authenticate_wp_user( $user_id, $provider, $redirect_to, $adapter, $hybridauth_user_profile );
}

add_action( 'init', 'wsl_process_login' );

// --------------------------------------------------------------------

function wsl_process_login_checks()
{
	if( ! isset( $_REQUEST[ 'action' ] ) ){
		return false;
	}

	if( $_REQUEST[ 'action' ] != "wordpress_social_login" && $_REQUEST[ 'action' ] !=  "wordpress_social_link" ){
		return false;
	}

	// dont be silly
	if(  $_REQUEST[ 'action' ] == "wordpress_social_link" && ! is_user_logged_in() ){
		wsl_render_notices_pages( __("Bouncer say don't be silly!", 'wordpress-social-login') );

		return false;
	}

	if(  $_REQUEST[ 'action' ] == "wordpress_social_link" && get_option( 'wsl_settings_bouncer_linking_accounts_enabled' ) != 1 ){
		wsl_render_notices_pages( __("Bouncer say this makes no sense.", 'wordpress-social-login') );
		
		return false;
	}

	// Bouncer :: Allow authentication 
	if( get_option( 'wsl_settings_bouncer_authentication_enabled' ) == 2 ){
		wsl_render_notices_pages( __("WSL is disabled!", 'wordpress-social-login') ); 
		
		return false;
	}

	return true;
}

// --------------------------------------------------------------------

function wsl_process_login_get_redirect_to()
{
	if ( isset( $_REQUEST[ 'redirect_to' ] ) && $_REQUEST[ 'redirect_to' ] != '' ){
		$redirect_to = $_REQUEST[ 'redirect_to' ];

		// Redirect to https if user wants ssl
		if ( isset( $secure_cookie ) && $secure_cookie && false !== strpos( $redirect_to, 'wp-admin') ){
			$redirect_to = preg_replace( '|^http://|', 'https://', $redirect_to );
		}

		if ( strpos( $redirect_to, 'wp-admin') && ! is_user_logged_in() ){
			$redirect_to = get_option( 'wsl_settings_redirect_url' ); 
		}

		if ( strpos( $redirect_to, 'wp-login.php') ){
			$redirect_to = get_option( 'wsl_settings_redirect_url' ); 
		}
	}

	if( get_option( 'wsl_settings_redirect_url' ) != site_url() ){
		$redirect_to = get_option( 'wsl_settings_redirect_url' );
	}

	if( empty( $redirect_to ) ){
		$redirect_to = get_option( 'wsl_settings_redirect_url' ); 
	}

	if( empty( $redirect_to ) ){
		$redirect_to = site_url();
	}

	return $redirect_to;
}

// --------------------------------------------------------------------

function wsl_process_login_get_provider()
{
	// selected provider name 
	$provider = @ trim( strip_tags( $_REQUEST["provider"] ) );
	
	return $provider;
}

// --------------------------------------------------------------------

function wsl_process_login_hybridauth_authenticate( $provider, $redirect_to )
{
	try{
		# Hybrid_Auth already used?
		if ( class_exists('Hybrid_Auth', false) ) {
			return wsl_render_notices_pages( __("Error: Another plugin seems to be using HybridAuth Library and made WordPress Social Login unusable. We recommand to find this plugin and to kill it with fire!", 'wordpress-social-login') ); 
		} 

		// load hybridauth
		require_once( dirname(__FILE__) . "/../../hybridauth/Hybrid/Auth.php" );

		// build required configuratoin for this provider
		if( ! get_option( 'wsl_settings_' . $provider . '_enabled' ) ){
			throw new Exception( 'Unknown or disabled provider' );
		}

		$config = array(); 
		$config["providers"] = array();
		$config["providers"][$provider] = array();
		$config["providers"][$provider]["enabled"] = true;

		// provider application id ?
		if( get_option( 'wsl_settings_' . $provider . '_app_id' ) ){
			$config["providers"][$provider]["keys"]["id"] = get_option( 'wsl_settings_' . $provider . '_app_id' );
		}

		// provider application key ?
		if( get_option( 'wsl_settings_' . $provider . '_app_key' ) ){
			$config["providers"][$provider]["keys"]["key"] = get_option( 'wsl_settings_' . $provider . '_app_key' );
		}

		// provider application secret ?
		if( get_option( 'wsl_settings_' . $provider . '_app_secret' ) ){
			$config["providers"][$provider]["keys"]["secret"] = get_option( 'wsl_settings_' . $provider . '_app_secret' );
		}

		// create an instance for Hybridauth
		$hybridauth = new Hybrid_Auth( $config );

		// try to authenticate the selected $provider
		if( $hybridauth->isConnectedWith( $provider ) ){
			$adapter = $hybridauth->getAdapter( $provider );

			$hybridauth_user_profile = $adapter->getUserProfile();

			// check hybridauth user email
			$hybridauth_user_id      = (int) wsl_get_user_by_meta( $provider, $hybridauth_user_profile->identifier ); 
			$hybridauth_user_email   = sanitize_email( $hybridauth_user_profile->email ); 
			$hybridauth_user_login   = sanitize_user( $hybridauth_user_profile->displayName );

			$request_user_login      = "";
			$request_user_email      = "";

		# {{{ linking new accounts
			// Bouncer :: Accounts Linking is enabled
			if( get_option( 'wsl_settings_bouncer_linking_accounts_enabled' ) == 1 ){ 
				// if user is linking account
				// . we DO import contacts
				// . we DO store the user profile
				// 
				// . we DONT create another entry on user table 
				// . we DONT create nor update his data on usermeata table 
				if(  $_REQUEST[ 'action' ] ==  "wordpress_social_link" ){
					global $current_user; 

					get_currentuserinfo(); 
					$user_id = $current_user->ID; 
					
					return wsl_process_login_authenticate_wp_user_linked_account( $user_id, $provider, $redirect_to, $adapter, $hybridauth_user_profile );
				}

				// check if connected user is linked account
				$linked_account = wsl_get_user_linked_account_by_provider_and_identifier( $provider, $hybridauth_user_profile->identifier );

				// if linked account found, we connect the actual user 
				if( $linked_account ){
					if( count( $linked_account ) > 1 ){
						return wsl_render_notices_pages( __("This $provider is linked to many accounts!", 'wordpress-social-login') );
					}

					$user_id = $linked_account[0]->user_id;

					if( ! $user_id ){
						return wsl_render_notices_pages( __("Something wrong!", 'wordpress-social-login') );
					}

					return wsl_process_login_authenticate_wp_user( $user_id, $provider, $redirect_to, $adapter, $hybridauth_user_profile );
				}
			}
		# }}} linking new accounts

		# {{{ module Bouncer
			// Bouncer :: Filters by emails domains name
			if( get_option( 'wsl_settings_bouncer_new_users_restrict_domain_enabled' ) == 1 ){ 
				if( empty( $hybridauth_user_email ) ){
					return wsl_render_notices_pages( get_option( 'wsl_settings_bouncer_new_users_restrict_domain_text_bounce' ) );
				}

				$list = get_option( 'wsl_settings_bouncer_new_users_restrict_domain_list' );
				$list = preg_split( '/$\R?^/m', $list ); 

				$current = strstr( $hybridauth_user_email, '@' );

				$shall_pass = false;
				foreach( $list as $item ){
					if( trim( strtolower( "@$item" ) ) == strtolower( $current ) ){
						$shall_pass = true;
					}
				}

				if( ! $shall_pass ){
					return wsl_render_notices_pages( get_option( 'wsl_settings_bouncer_new_users_restrict_domain_text_bounce' ) );
				}
			}

			// Bouncer :: Filters by e-mails addresses
			if( get_option( 'wsl_settings_bouncer_new_users_restrict_email_enabled' ) == 1 ){ 
				if( empty( $hybridauth_user_email ) ){
					return wsl_render_notices_pages( get_option( 'wsl_settings_bouncer_new_users_restrict_email_text_bounce' ) );
				}

				$list = get_option( 'wsl_settings_bouncer_new_users_restrict_email_list' );
				$list = preg_split( '/$\R?^/m', $list ); 

				$shall_pass = false;
				foreach( $list as $item ){
					if( trim( strtolower( $item ) ) == strtolower( $hybridauth_user_email ) ){
						$shall_pass = true;
					}
				}

				if( ! $shall_pass ){
					return wsl_render_notices_pages( get_option( 'wsl_settings_bouncer_new_users_restrict_email_text_bounce' ) );
				}
			}

			// Bouncer :: Filters by profile urls
			if( get_option( 'wsl_settings_bouncer_new_users_restrict_profile_enabled' ) == 1 ){ 
				$list = get_option( 'wsl_settings_bouncer_new_users_restrict_profile_list' );
				$list = preg_split( '/$\R?^/m', $list ); 

				$shall_pass = false;
				foreach( $list as $item ){
					if( trim( strtolower( $item ) ) == strtolower( $hybridauth_user_profile->profileURL ) ){
						$shall_pass = true;
					}
				}

				if( ! $shall_pass ){
					return wsl_render_notices_pages( get_option( 'wsl_settings_bouncer_new_users_restrict_profile_text_bounce' ) );
				}
			}

			// if user do not exist
			if( ! $hybridauth_user_id ){
				// Bouncer :: Accept new registrations
				if( get_option( 'wsl_settings_bouncer_registration_enabled' ) == 2 ){
					return wsl_render_notices_pages( __("registration is now closed!", 'wordpress-social-login') ); 
				}

				// Bouncer :: Email Validation // require emails!
				if( get_option( 'wsl_settings_bouncer_email_validation_enabled' ) == 1 ){ 
					if( empty( $hybridauth_user_email ) ){ 
						do
						{
							list( $request_user_login, $request_user_email ) = wsl_process_login_complete_registration( $provider, $redirect_to, $hybridauth_user_email, $hybridauth_user_login );  
						}
						while( empty( $request_user_email ) );
					}
				}
			}  
		# }}} module Bouncer
		}
		else{
			throw new Exception( 'User not connected with ' . $provider . '!' );
		} 
	}
	catch( Exception $e ){ 
		return wsl_render_notices_pages( sprintf( __("Unspecified error. #%d", 'wordpress-social-login'), $e->getCode() ) ); 
	}

	$user_id = null;

	// if the user email is verified, then try to map to legacy account if exist
	// > Currently only Facebook, Google, Yahaoo and Foursquare do provide the verified user email.
	if ( ! empty( $hybridauth_user_profile->emailVerified ) ){
		$user_id = (int) email_exists( $hybridauth_user_profile->emailVerified );
	}

	// try to get user by meta if not
	if( ! $user_id ){
		$user_id = (int) wsl_get_user_by_meta( $provider, $hybridauth_user_profile->identifier ); 
	}

	return array( 
		$user_id,
		$adapter,
		$hybridauth_user_profile,
		$hybridauth_user_id,
		$hybridauth_user_email, 
		$request_user_login, 
		$request_user_email, 
	);
}

// --------------------------------------------------------------------

function wsl_process_login_create_wp_user( $provider, $hybridauth_user_profile, $request_user_login, $request_user_email )
{
	// HOOKABLE: any action to fire right before a user created on database
	do_action( 'wsl_hook_process_login_before_create_wp_user' );

	$user_login = null;
	$user_email = null;

	// if coming from "complete registration form"
	if( $request_user_email && $request_user_login ){
		$user_login = $request_user_login;
		$user_email = $request_user_email;
	}

	# else, validate/generate the login and user email
	else{
		// generate a valid user login
		$user_login = trim( str_replace( ' ', '_', strtolower( $hybridauth_user_profile->displayName ) ) );
		$user_email = $hybridauth_user_profile->email;

		if( empty( $user_login ) ){
			$user_login = trim( $hybridauth_user_profile->lastName . " " . $hybridauth_user_profile->firstName );
		}

		if( empty( $user_login ) ){
			$user_login = strtolower( $provider ) . "_user_" . md5( $hybridauth_user_profile->identifier );
		}

		// user name should be unique
		if ( username_exists ( $user_login ) ){
			$i = 1;
			$user_login_tmp = $user_login;

			do
			{
				$user_login_tmp = $user_login . "_" . ($i++);
			} while (username_exists ($user_login_tmp));

			$user_login = $user_login_tmp;
		}

		// generate an email if none
		if ( ! isset ( $user_email ) OR ! is_email( $user_email ) ){
			$user_email = strtolower( $provider . "_user_" . $user_login ) . "@example.com";
		}

		// email should be unique
		if ( email_exists ( $user_email ) ){
			do
			{
				$user_email = md5(uniqid(wp_rand(10000,99000)))."@example.com";
			} while( email_exists( $user_email ) );
		} 

		$user_login = sanitize_user ($user_login, true);

		if( ! validate_username( $user_login ) ){
			$user_login = strtolower( $provider ) . "_user_" . md5( $hybridauth_user_profile->identifier );
		}
	}

	$display_name = $hybridauth_user_profile->displayName;
	
	if( $request_user_login || empty ( $display_name ) ){
		$display_name = $user_login;
	}

	$userdata = array(
		'user_login'    => $user_login,
		'user_email'    => $user_email,

		'display_name'  => $display_name,
		
		'first_name'    => $hybridauth_user_profile->firstName,
		'last_name'     => $hybridauth_user_profile->lastName, 
		'user_url'      => $hybridauth_user_profile->profileURL,
		'description'   => $hybridauth_user_profile->description,

		'user_pass'     => wp_generate_password()
	);

	// Bouncer :: Membership level
	if( get_option( 'wsl_settings_bouncer_new_users_membership_default_role' ) != "default" ){ 
		$userdata['role'] = get_option( 'wsl_settings_bouncer_new_users_membership_default_role' );
	}

	// Bouncer :: Moderation // will overwrite what Bouncer :: Membership level did
	if( get_option( 'wsl_settings_bouncer_new_users_moderation_enabled' ) == 1 ){ 
		$userdata['role'] = "";
	}

	// HOOKABLE: change the user data
	if( apply_filters( 'wsl_hook_process_login_userdata', $userdata ) ){
		$userdata = apply_filters( 'wsl_hook_process_login_alter_userdata', $userdata );
	}

	// HOOKABLE: any action to fire right before a user created on database
	do_action( 'wsl_hook_process_login_before_insert_user', $user_id );

	// HOOKABLE: delegate user insert to a custom function
	$user_id = apply_filters( 'wsl_hook_process_login_alter_insert_user', $userdata );

	// Create a new user
	if( ! $user_id || ! is_integer( $user_id ) ){
		$user_id = wp_insert_user( $userdata );
	}

	// update user metadata
	if( $user_id && is_integer( $user_id ) ){
		update_user_meta( $user_id, $provider, $hybridauth_user_profile->identifier );
	}
	else if (is_wp_error($user_id)) {
		echo $user_id->get_error_message();
	}
	else{
		return wsl_render_notices_pages( __("An error occurred while creating a new user!", 'wordpress-social-login') );
	} 

	// Send notifications 
	if ( get_option( 'wsl_settings_users_notification' ) == 1 ){
		wsl_admin_notification( $user_id, $provider );
	}

	// HOOKABLE: any action to fire right after a user created on database
	do_action( 'wsl_hook_process_login_after_create_wp_user', $user_id );

	return array( 
		$user_id,
		$user_login,
		$user_email 
	);
}

// --------------------------------------------------------------------

function wsl_process_login_authenticate_wp_user( $user_id, $provider, $redirect_to, $adapter, $hybridauth_user_profile )
{
	// calculate user age
	$user_age = $hybridauth_user_profile->age;

	// not that precise you say... well welcome to my world
	if( ! $user_age && (int) $hybridauth_user_profile->birthYear ){
		$user_age = (int) date("Y") - (int) $hybridauth_user_profile->birthYear;
	}

	// update some stuff
	update_user_meta ( $user_id, 'wsl_user'       , $provider );
	update_user_meta ( $user_id, 'wsl_user_gender', $hybridauth_user_profile->gender );
	update_user_meta ( $user_id, 'wsl_user_age'   , $user_age );
	update_user_meta ( $user_id, 'wsl_user_image' , $hybridauth_user_profile->photoURL );

	// launch contact import if enabled
	wsl_import_user_contacts( $provider, $adapter, $user_id );

	// store user hybridauth user profile if needed
	wsl_store_hybridauth_user_data( $user_id, $provider, $hybridauth_user_profile );

	// HOOKABLE: 
	do_action( "wsl_hook_process_login_before_set_auth_cookie", $user_id, $hybridauth_user_profile );

	// That's it. create a session for user_id and redirect him to redirect_to
	wp_set_auth_cookie( $user_id );

	// HOOKABLE: 
	do_action( "wsl_hook_process_login_before_redirect", $user_id, $hybridauth_user_profile );

	wp_safe_redirect( $redirect_to );

	exit(); 
}

// --------------------------------------------------------------------

function wsl_process_login_authenticate_wp_user_linked_account( $user_id, $provider, $redirect_to, $hybridauth_user_profile )
{
	// launch contact import if enabled
	wsl_import_user_contacts( $provider, $adapter, $user_id );

	// store user hybridauth user profile if needed
	wsl_store_hybridauth_user_data( $user_id, $provider, $hybridauth_user_profile );

	// HOOKABLE: 
	do_action( "wsl_hook_process_login_linked_account_before_redirect", $user_id, $hybridauth_user_profile );

	wp_safe_redirect( $redirect_to );

	exit(); 
}

// --------------------------------------------------------------------
