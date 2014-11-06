<?php
/*!
* WordPress Social Login
*
* http://miled.github.io/wordpress-social-login/ | https://github.com/miled/wordpress-social-login
*  (c) 2011-2014 Mohamed Mrassi and contributors | http://wordpress.org/plugins/wordpress-social-login/
*/

/**
* Authenticate users via social networks.
*
* Ref: http://miled.github.io/wordpress-social-login/developer-api-authentication.html
** 
* Side note: I don't usually over-comment codes, but this is the main WSL script and I had to since
*            many users with diffrent "skill levels" may want to understand how this piece of code works.
** 
* To sum things up, here is how WSL works (bit hard to explain, so bare with me):
*
* Let assume a user come to page at our website and he click on of the providers icons in order connect.
*
*  - If &action=wordpress_social_authenticate is found in the current url, then WSL will display a loading screen,
*  - That loading screen will refresh it self adding &redirect_to_provider=ture to the url, which will trigger the next step,
*  - Next, WSL will instantiate Hybridauth main class, build the required provider config then initiate the auth protocol /hybridauth/?hauth.start=PROVIDER_ID,
*  - Hybridauth will redirect the user to the selected provider site to ask for his consent (authorisation to access his profile),
*  - If the user gives his authorisation for your application, the provider will redirect the user back to Hybridauth entry point /hybridauth/?hauth.done=PROVIDER_ID,
*  - Hybridauth will redirect the user to the given callback url.
*  - In that callback url, WSL will display a second loading screen This loading screen will generate and submit a form with a hidden input &action= wordpress_social_authenticated to the current url which will trigger the second part of the auth process,
*  - WSL will grab the user profile from the provider, attempt to identify him and create a new WordPress user if he doesn't exist. In this step, and when enabled, WSL will also import the user contacts and map his profile data to Buddypress xporfiles tables,
*  - Finally, WSL will authenticate the user within WordPress (give him a sweet cookie) and redirect him back to Redirect URL
**
* Functions execution order is the following:
*
*     do_action('init')
*     .       wsl_process_login()
*     .       .       wsl_process_login_begin()
*     .       .       .       wsl_render_redirect_to_provider_loading_screen()
*     .       .       .       Hybrid_Auth::authenticate()
*     .       .       .       wsl_render_return_from_provider_loading_screen()
*     .       .
*     .       .       wsl_process_login_end()
*     .       .       .       wsl_process_login_get_user_data()
*     .       .       .       .       wsl_process_login_request_user_social_profile()
*     .       .       .       .       .       Hybrid_Auth::getUserProfile()
*     .       .       .       .
*     .       .       .       .       wsl_process_login_complete_registration()
*     .       .       .
*     .       .       .       wsl_process_login_create_wp_user()
*     .       .       .
*     .       .       .       wsl_process_login_update_wsl_user_data()
*     .       .       .       .       wsl_store_hybridauth_user_profile()
*     .       .       .       .       wsl_buddypress_xprofile_mapping()
*     .       .       .       .       wsl_store_hybridauth_user_contacts()
*     .       .       .
*     .       .       .       wsl_process_login_authenticate_wp_user()
*/

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------

/**
* Entry point to the authentication process
*
* This function runs after WordPress has finished loading but before any headers are sent.
* This function will analyse the current URL parameters and start the login process whenever an
* WSL action is found: $_REQUEST['action'] eq wordpress_social_*
* 
* Example of valid origin url:
*    wp-login.php
*       ?action=wordpress_social_authenticate                        // current step
*       &mode=login                                                  // auth mode
*       &provider=Twitter                                            // selected provider
*       &redirect_to=http%3A%2F%2Fexample.com%2Fwordpress%2F%3Fp%3D1 // where the user come from
*
* Ref: http://codex.wordpress.org/Plugin_API/Action_Reference/init
*/
function wsl_process_login()
{
	// > check for wsl actions
	$action = isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : null;

	if( ! in_array( $action, array( "wordpress_social_authenticate", "wordpress_social_profile_completion", "wordpress_social_account_linking", "wordpress_social_authenticated" ) ) )
	{
		return false;
	}

	// authentication mode
	$auth_mode = wsl_process_login_get_auth_mode();

	// start loggin the auth process, if debug mode is enabled
	wsl_watchdog_init();

	// halt, if mode login and user already logged in
	if( 'login' == $auth_mode && is_user_logged_in() )
	{
		global $current_user;

		get_currentuserinfo();

		return wsl_process_login_render_notice_page( sprintf( _wsl__( "You are already logged in as %s. Do you want to <a href='%s'>log out</a>?", 'wordpress-social-login' ), $current_user->display_name, wp_logout_url( home_url() ) ) );
	}

	// halt, if mode link and user not logged in
	if( 'link' == $auth_mode && ! is_user_logged_in() )
	{
		return wsl_process_login_render_notice_page( sprintf( _wsl__( "You have to be logged in to be able to link your existing account. Do you want to <a href='%s'>login</a>?", 'wordpress-social-login' ), wp_login_url( home_url() ) ) );
	}

	// halt, if mode test and not admin 
	if( 'test' == $auth_mode && ! current_user_can('manage_options') )
	{
		return wsl_process_login_render_notice_page( _wsl__( 'You do not have sufficient permissions to access this page.', 'wordpress-social-login' ) );
	}

	// Bouncer :: Allow authentication?
	if( get_option( 'wsl_settings_bouncer_authentication_enabled' ) == 2 )
	{
		return wsl_process_login_render_notice_page( _wsl__( "Authentication through social networks is currently disabled.", 'wordpress-social-login' ) );
	}

	add_action( 'wsl_clear_user_php_session', 'wsl_process_login_clear_user_php_session' );

	// HOOKABLE:
	do_action( "wsl_process_login_start" );

	// if action=wordpress_social_authenticate
	// > start the first part of authentication (redirect the user to the selected provider)
	if( $action == "wordpress_social_authenticate" )
	{
		return wsl_process_login_begin();
	}

	// if action=wordpress_social_authenticated or action=wordpress_social_profile_completion
	// > finish the authentication process (create new user if doesn't exist in database, then log him in within wordpress)
	wsl_process_login_end();
}

add_action( 'init', 'wsl_process_login' );

// --------------------------------------------------------------------

/**
* Start the first part of authentication
* 
* Steps:
*     1. Display a loading screen while hybridauth is redirecting the user to the selected provider
*     2. Build the hybridauth config for the selected provider (keys, scope, etc) 
*     3. Instantiate the class Hybrid_Auth and redirect the user to provider to ask for authorisation for this website
*     4. Display a loading screen after user come back from provider as we redirect the user back to Widget::Redirect URL
*/
function wsl_process_login_begin()
{
	// HOOKABLE:
	do_action( "wsl_process_login_begin_start" );

	$config     = null;
	$hybridauth = null;
	$provider   = null;
	$adapter    = null; 

	// check if php session are working as expected by wsl
	if( ! wsl_process_login_check_php_session() )
	{
		return wsl_process_login_render_notice_page( sprintf( _wsl__( 'The session identifier is missing.<br />For more information refer to WSL <a href="http://miled.github.io/wordpress-social-login/troubleshooting.html#session-error" target="_blank">Troubleshooting</a>.', 'wordpress-social-login' ), site_url() ) );
	}

	// HOOKABLE: selected provider name
	$provider = wsl_process_login_get_selected_provider();

	if( ! $provider )
	{
		return wsl_process_login_render_notice_page( _wsl__( 'Bouncer says this makes no sense.', 'wordpress-social-login' ) ); 
	}

	/* 1. Display a loading screen while hybridauth is redirecting the user to the selected provider */

	// the loading screen should reflesh it self with a new arg in url: &redirect_to_provider=ture
	if( ! isset( $_REQUEST["redirect_to_provider"] ) )
	{
		do_action( 'wsl_clear_user_php_session' );

		return wsl_render_redirect_to_provider_loading_screen( $provider );
	}

	/*  2. Build the hybridauth config for the selected provider (keys, scope, etc) */

	// provider enabled?
	if( ! get_option( 'wsl_settings_' . $provider . '_enabled' ) )
	{
		return wsl_process_login_render_notice_page( _wsl__( "Unknown or disabled provider.", 'wordpress-social-login' ) );
	}

	$config = wsl_process_login_build_provider_config( $provider );

	/* 3. Instantiate the class Hybrid_Auth and redirect the user to provider to ask for authorisation for this website */

	// load hybridauth main class
	if( ! class_exists('Hybrid_Auth', false) )
	{
		require_once WORDPRESS_SOCIAL_LOGIN_ABS_PATH . "/hybridauth/Hybrid/Auth.php"; 
	}

	// HOOKABLE:
	do_action( "wsl_hook_process_login_before_hybridauth_authenticate", $provider, $config );

	try
	{
		// create an instance oh hybridauth with the generated config 
		$hybridauth = new Hybrid_Auth( $config );

		// start the authentication process via hybridauth
		// > if not already connected hybridauth::authenticate() will redirect the user to the provider
		// > where he will be asked for his consent (most providers ask for consent only once). 
		// > after that, the provider will redirect the user back to this same page (and this same line). 
		// > if the user is successfully connected to provider, then this time hybridauth::authenticate()
		// > will just return the provider adapter
		$adapter = $hybridauth->authenticate( $provider );
	}

	// if hybridauth fails to authenticate the user, then we display an error message
	catch( Exception $e )
	{
		return wsl_process_login_render_error_page( $e, $config, $provider );
	}

	// HOOKABLE:
	do_action( "wsl_hook_process_login_after_hybridauth_authenticate", $provider, $config, $hybridauth, $adapter );

	/* 4. Display a loading screen after user come back from provider as we redirect the user back to Widget::Redirect URL */

	// get Widget::Authentication display
	$wsl_settings_use_popup = get_option( 'wsl_settings_use_popup' );

	// authentication mode
	$auth_mode = wsl_process_login_get_auth_mode();

	$redirect_to = isset( $_REQUEST[ 'redirect_to' ] ) ? $_REQUEST[ 'redirect_to' ] : site_url();

	// build the authenticateD, which will make wsl_process_login() fire the next step wsl_process_login_end()
	$authenticated_url = site_url( 'wp-login.php', 'login_post' ) . ( strpos( site_url( 'wp-login.php', 'login_post' ), '?' ) ? '&' : '?' ) . "action=wordpress_social_authenticated&provider=" . $provider . '&mode=' . $auth_mode;

	// display a loading screen
	return wsl_render_return_from_provider_loading_screen( $provider, $authenticated_url, $redirect_to, $wsl_settings_use_popup );
}

// --------------------------------------------------------------------

/**
* Finish the authentication process 
* 
* Steps:
*     1. Get the user profile from provider
*     2. Create new wordpress user if he didn't exist in database
*     3. Store his Hybridauth profile, contacts and BP mapping
*     4. Authenticate the user within wordpress
*/
function wsl_process_login_end()
{
	// HOOKABLE:
	do_action( "wsl_process_login_end_start" );

	// HOOKABLE: set a custom Redirect URL
	$redirect_to = wsl_process_login_get_redirect_to();

	// HOOKABLE: selected provider name
	$provider = wsl_process_login_get_selected_provider();

	// authentication mode
	$auth_mode = wsl_process_login_get_auth_mode();

	$is_new_user             = false; // is it a new or returning user
	$user_id                 = ''   ; // wp user id 
	$adapter                 = ''   ; // hybriauth adapter for the selected provider
	$hybridauth_user_profile = ''   ; // hybriauth user profile 
	$requested_user_login    = ''   ; // username typed by users in Profile Completion
	$requested_user_email    = ''   ; // email typed by users in Profile Completion

	// provider is enabled?
	if( ! get_option( 'wsl_settings_' . $provider . '_enabled' ) )
	{
		return wsl_process_login_render_notice_page( _wsl__( "Unknown or disabled provider.", 'wordpress-social-login' ) );
	}

	if( 'test' == $auth_mode )
	{
		$redirect_to = admin_url( 'options-general.php?page=wordpress-social-login&wslp=auth-paly&provider=' . $provider );

		return wp_safe_redirect( $redirect_to );
	}

	if( 'link' == $auth_mode )
	{
		// a social account cant be associated with more than one wordpress account.

		$hybridauth_user_profile = wsl_process_login_request_user_social_profile( $provider );

		$user_id = (int) wsl_get_stored_hybridauth_user_id_by_provider_and_provider_uid( $provider, $hybridauth_user_profile->identifier );

		if( $user_id && $user_id != get_current_user_id() )
		{
			return wsl_process_login_render_notice_page( sprintf( _wsl__( "Your <b>%s ID</b> is already linked to another account on this website.", 'wordpress-social-login'), $provider ) );
		}

		$user_id = get_current_user_id();

		// doesn't hurt to double check
		if( ! $user_id )
		{
			return wsl_process_login_render_notice_page( _wsl__( "Sorry, we couldn't link your account.", 'wordpress-social-login' ) );
		}
	}
	elseif( 'login' != $auth_mode )
	{
		return wsl_process_login_render_notice_page( _wsl__( 'Bouncer says no.', 'wordpress-social-login' ) ); 
	}

	if( 'login' == $auth_mode )
	{
		// returns user data after he authenticate via hybridauth 
		list
		( 
			$user_id                ,
			$adapter                ,
			$hybridauth_user_profile,
			$requested_user_login   ,
			$requested_user_email   ,
		)
		= wsl_process_login_get_user_data( $provider, $redirect_to );

		// if no associated user were found in wslusersprofiles, create new WordPress user
		if( ! $user_id )
		{
			$user_id = wsl_process_login_create_wp_user( $provider, $hybridauth_user_profile, $requested_user_login, $requested_user_email );

			$is_new_user = true;
		}
	}

	// if user is found in wslusersprofiles but the associated WP user account no longer exist
	// > this should never happen! but just in case: we delete the user wslusersprofiles/wsluserscontacts entries and we reset the process
	$wp_user = get_userdata( $user_id );

	if( ! $wp_user )
	{
		wsl_delete_stored_hybridauth_user_data( $user_id );

		return wsl_process_login_render_notice_page( sprintf( _wsl__( "Sorry, we couldn't connect you. <a href=\"%s\">Please try again</a>.", 'wordpress-social-login' ), site_url( 'wp-login.php', 'login_post' ) ) );
	}

	// store user hybridauth profile (wslusersprofiles), contacts (wsluserscontacts) and buddypress mapping 
	wsl_process_login_update_wsl_user_data( $is_new_user, $user_id, $provider, $adapter, $hybridauth_user_profile, $wp_user );

	// finally create a wordpress session for the user
	wsl_process_login_authenticate_wp_user( $user_id, $provider, $redirect_to, $adapter, $hybridauth_user_profile, $wp_user );
}

// --------------------------------------------------------------------

/**
* Returns user data after he authenticate via hybridauth 
*
* Steps:
*    1. Grab the user profile from hybridauth
*    2. Run Bouncer::Filters if enabled (domains, emails, profiles urls)
*    3. Check if user exist in database by looking for the couple (Provider name, Provider user ID) or verified email
*    4. Deletegate detection of user id to custom functions / hooks
*    5. If Bouncer::Profile Completion is enabled and user didn't exist, we require the user to complete the registration (user name & email) 
*/
function wsl_process_login_get_user_data( $provider, $redirect_to )
{
	// HOOKABLE:
	do_action( "wsl_process_login_get_user_data_start", $provider, $redirect_to );

	$user_id                  = null;
	$config                   = null;
	$hybridauth               = null;
	$adapter                  = null;
	$hybridauth_user_profile  = null;
	$requested_user_login     = '';
	$requested_user_email     = '';

	/* 1. Grab the user profile from social network */ 

	if( ! ( isset( $_SESSION['wsl::userprofile'] ) && $_SESSION['wsl::userprofile'] && $hybridauth_user_profile = json_decode( $_SESSION['wsl::userprofile'] ) ) )
	{
		$hybridauth_user_profile = wsl_process_login_request_user_social_profile( $provider );

		$_SESSION['wsl::userprofile'] = json_encode( $hybridauth_user_profile );
	}

	$adapter = wsl_process_login_get_provider_adapter( $provider );

	$hybridauth_user_email = sanitize_email( $hybridauth_user_profile->email ); 

	/* 2. Run Bouncer::Filters if enabled (domains, emails, profiles urls) */

	// Bouncer::Filters by emails domains name
	if( get_option( 'wsl_settings_bouncer_new_users_restrict_domain_enabled' ) == 1 )
	{
		if( empty( $hybridauth_user_email ) )
		{
			return wsl_process_login_render_notice_page( _wsl__( get_option( 'wsl_settings_bouncer_new_users_restrict_domain_text_bounce' ), 'wordpress-social-login') );
		}

		$list = get_option( 'wsl_settings_bouncer_new_users_restrict_domain_list' );
		$list = preg_split( '/$\R?^/m', $list ); 

		$current = strstr( $hybridauth_user_email, '@' );

		$shall_pass = false;

		foreach( $list as $item )
		{
			if( trim( strtolower( "@$item" ) ) == strtolower( $current ) )
			{
				$shall_pass = true;
			}
		}

		if( ! $shall_pass )
		{
			return wsl_process_login_render_notice_page( _wsl__( get_option( 'wsl_settings_bouncer_new_users_restrict_domain_text_bounce' ), 'wordpress-social-login') );
		}
	}

	// Bouncer::Filters by e-mails addresses
	if( get_option( 'wsl_settings_bouncer_new_users_restrict_email_enabled' ) == 1 )
	{
		if( empty( $hybridauth_user_email ) )
		{
			return wsl_process_login_render_notice_page( _wsl__( get_option( 'wsl_settings_bouncer_new_users_restrict_email_text_bounce' ), 'wordpress-social-login') );
		}

		$list = get_option( 'wsl_settings_bouncer_new_users_restrict_email_list' );
		$list = preg_split( '/$\R?^/m', $list ); 

		$shall_pass = false;

		foreach( $list as $item )
		{
			if( trim( strtolower( $item ) ) == strtolower( $hybridauth_user_email ) )
			{
				$shall_pass = true;
			}
		}

		if( ! $shall_pass )
		{
			return wsl_process_login_render_notice_page( _wsl__( get_option( 'wsl_settings_bouncer_new_users_restrict_email_text_bounce' ), 'wordpress-social-login') );
		}
	}

	// Bouncer::Filters by profile urls
	if( get_option( 'wsl_settings_bouncer_new_users_restrict_profile_enabled' ) == 1 )
	{ 
		$list = get_option( 'wsl_settings_bouncer_new_users_restrict_profile_list' );
		$list = preg_split( '/$\R?^/m', $list ); 

		$shall_pass = false;

		foreach( $list as $item )
		{
			if( trim( strtolower( $item ) ) == strtolower( $hybridauth_user_profile->profileURL ) )
			{
				$shall_pass = true;
			}
		}

		if( ! $shall_pass )
		{
			return wsl_process_login_render_notice_page( _wsl__( get_option( 'wsl_settings_bouncer_new_users_restrict_profile_text_bounce' ), 'wordpress-social-login') );
		}
	}

	/* 3. Check if user exist in database by looking for the couple (Provider name, Provider user ID) or verified email */

	// check if user already exist in wslusersprofiles
	$user_id = (int) wsl_get_stored_hybridauth_user_id_by_provider_and_provider_uid( $provider, $hybridauth_user_profile->identifier );

	// if not found in wslusersprofiles, then check his verified email 
	if( ! $user_id && ! empty( $hybridauth_user_profile->emailVerified ) )
	{
		// check if the verified email exist in wp_users
		$user_id = (int) wsl_wp_email_exists( $hybridauth_user_profile->emailVerified );

		// check if the verified email exist in wslusersprofiles
		if( ! $user_id )
		{
			$user_id = (int) wsl_get_stored_hybridauth_user_id_by_email_verified( $hybridauth_user_profile->emailVerified );
		}
	}

	/* 4 Deletegate detection of user id to custom filters hooks */

	// HOOKABLE:
	$user_id = apply_filters( 'wsl_hook_process_login_alter_user_id', $user_id, $provider, $hybridauth_user_profile );

	/* 5. If Bouncer::Profile Completion is enabled and user didn't exist, we require the user to complete the registration (user name & email) */

	if( ! $user_id )
	{
		// Bouncer :: Accept new registrations?
		if( get_option( 'wsl_settings_bouncer_registration_enabled' ) == 2 )
		{
			return wsl_process_login_render_notice_page( _wsl__( "Registration is now closed.", 'wordpress-social-login' ) );
		}

		// Bouncer::Accounts linking/mapping
		// > > not implemented yet! Planned for WSL 2.3
		if( get_option( 'wsl_settings_bouncer_accounts_linking_enabled' ) == 1 )
		{
			do
			{
				list
				( 
					$shall_pass,
					$user_id,
					$requested_user_login,
					$requested_user_email
				) 
				= wsl_process_login_new_users_gateway( $provider, $redirect_to, $hybridauth_user_profile );
			}
			while( ! $shall_pass );
		}

		// Bouncer::Profile Completion
		// > > in WSL 2.3 Profile Completion will be reworked and merged with Accounts linking
		elseif(
				( get_option( 'wsl_settings_bouncer_profile_completion_require_email' ) == 1 && empty( $hybridauth_user_email ) ) 
			|| 
				get_option( 'wsl_settings_bouncer_profile_completion_change_username' ) == 1
		)
		{
			do
			{
				list
				( 
					$shall_pass, 
					$requested_user_login, 
					$requested_user_email 
				)
				= wsl_process_login_complete_registration( $provider, $redirect_to, $hybridauth_user_profile );
			}
			while( ! $shall_pass );
		}
	}

	/* 6. returns user data */

	return array( 
		$user_id,
		$adapter,
		$hybridauth_user_profile, 
		$requested_user_login, 
		$requested_user_email, 
	);
}

// --------------------------------------------------------------------

/**
* Create a new wordpress user
*
* Ref: http://codex.wordpress.org/Function_Reference/wp_insert_user
*/
function wsl_process_login_create_wp_user( $provider, $hybridauth_user_profile, $requested_user_login, $requested_user_email )
{
	// HOOKABLE:
	do_action( "wsl_process_login_create_wp_user_start", $provider, $hybridauth_user_profile, $requested_user_login, $requested_user_email );

	$user_login = '';
	$user_email = '';

	// if coming from "complete registration form" 
	if( $requested_user_login )
	{
		$user_login = $requested_user_login;
	}

	if( $requested_user_email )
	{
		$user_email = $requested_user_email;
	}

	if( ! $user_login )
	{
		// attempt to generate user_login from hybridauth user profile display name
		$user_login = $hybridauth_user_profile->displayName;

		// sanitize user login
		$user_login = sanitize_user( $user_login, true );

		// remove spaces and dots
		$user_login = trim( str_replace( array( ' ', '.' ), '_', $user_login ) );
		$user_login = trim( str_replace( '__', '_', $user_login ) );

		// if user profile display name is not provided
		if( empty( $user_login ) )
		{
			$user_login = strtolower( $provider ) . "_user";
		}

		// user name should be unique
		if( username_exists( $user_login ) )
		{
			$i = 1;
			$user_login_tmp = $user_login;

			do
			{
				$user_login_tmp = $user_login . "_" . ($i++);
			}
			while( username_exists ($user_login_tmp));

			$user_login = $user_login_tmp;
		}
	}

	if( ! $user_email )
	{
		$user_email = $hybridauth_user_profile->email;

		// generate an email if none
		if( ! isset ( $user_email ) OR ! is_email( $user_email ) )
		{
			$user_email = strtolower( $provider . "_user_" . $user_login ) . '@example.com';
		}

		// email should be unique
		if( wsl_wp_email_exists ( $user_email ) )
		{
			do
			{
				$user_email = md5( uniqid( wp_rand( 10000, 99000 ) ) ) . '@example.com';
			}
			while( wsl_wp_email_exists( $user_email ) );
		} 
	}

	$display_name = $hybridauth_user_profile->displayName;

	if( $requested_user_login )
	{
		$display_name = sanitize_user( $requested_user_login, true );
	}

	if( empty( $display_name ) )
	{
		$display_name = strtolower( $provider ) . "_user";
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

	// Bouncer::Membership level  
	$wsl_settings_bouncer_new_users_membership_default_role = get_option( 'wsl_settings_bouncer_new_users_membership_default_role' );

	// if level eq "default", we set role to wp default user role
	if( $wsl_settings_bouncer_new_users_membership_default_role == "default" )
	{
		$userdata['role'] = get_option('default_role');
	}

	// if level not eq "default" or 'wslnorole' nor empty, we set role to the selected role in bouncer settings
	elseif( $wsl_settings_bouncer_new_users_membership_default_role && $wsl_settings_bouncer_new_users_membership_default_role != 'wslnorole' )
	{
		$userdata['role'] = $wsl_settings_bouncer_new_users_membership_default_role;
	}

	// Bouncer::User Moderation 
	// > if Bouncer::User Moderation is enabled (Yield to Theme My Login), then we overwrite the user role to 'pending'
	# http://www.jfarthing.com/development/theme-my-login/user-moderation/
	if( get_option( 'wsl_settings_bouncer_new_users_moderation_level' ) > 100 )
	{
		$userdata['role'] = "pending";
	}

	// HOOKABLE: change the user data
	$userdata = apply_filters( 'wsl_hook_process_login_alter_wp_insert_user_data', $userdata, $provider, $hybridauth_user_profile );

	// DEPRECIATED: as of 2.2.3
	// $userdata = apply_filters( 'wsl_hook_process_login_alter_userdata', $userdata, $provider, $hybridauth_user_profile );

	// HOOKABLE: This action runs just before creating a new wordpress user.
	do_action( 'wsl_hook_process_login_before_wp_insert_user', $userdata, $provider, $hybridauth_user_profile );

	// DEPRECIATED: as of 2.2.3
	// do_action( 'wsl_hook_process_login_before_insert_user', $userdata, $provider, $hybridauth_user_profile );

	// HOOKABLE: This action runs just before creating a new wordpress user, it delegate user insert to a custom function.
	$user_id = apply_filters( 'wsl_hook_process_login_delegate_wp_insert_user', $userdata, $provider, $hybridauth_user_profile );

	// Create a new WordPress user
	if( ! $user_id || ! is_integer( $user_id ) )
	{
		$user_id = wp_insert_user( $userdata );
	}

	// do not continue without user_id
	if( ! $user_id || ! is_integer( $user_id ) )
	{
		if( is_wp_error( $user_id ) )
		{
			return wsl_process_login_render_notice_page( _wsl__( "An error occurred while creating a new user: " . $user_id->get_error_message(), 'wordpress-social-login' ) );
		}

		return wsl_process_login_render_notice_page( _wsl__( "An error occurred while creating a new user!", 'wordpress-social-login' ) );
	}

	// Send notifications 
	if( get_option( 'wsl_settings_users_notification' ) == 1 )
	{
		wsl_admin_notification( $user_id, $provider );
	}

	// HOOKABLE: This action runs just after a wordpress user has been created
	// > Note: At this point, the user has been added to wordpress database, but NOT CONNECTED.
	do_action( 'wsl_hook_process_login_after_wp_insert_user', $user_id, $provider, $hybridauth_user_profile );

	// DEPRECIATED: as of 2.2.3
	// do_action( 'wsl_hook_process_login_after_create_wp_user', $user_id, $provider, $hybridauth_user_profile );

	// returns the user created user id
	return $user_id;
}

// --------------------------------------------------------------------

/**
* Store WSL user data
*
* Steps:
*     1. Store Hybridauth user profile
*     2. Import user contacts
*     3. Launch BuddyPress Profile mapping
*/
function wsl_process_login_update_wsl_user_data( $is_new_user, $user_id, $provider, $adapter, $hybridauth_user_profile, $wp_user )
{
	// HOOKABLE:
	do_action( "wsl_process_login_update_wsl_user_data_start", $is_new_user, $user_id, $provider, $adapter, $hybridauth_user_profile, $wp_user );

	// store user hybridauth user profile in table wslusersprofiles
	// > wsl will only sotre the user profile if it has changed since last login.
	wsl_store_hybridauth_user_profile( $user_id, $provider, $hybridauth_user_profile );

	// map hybridauth user profile to buddypress xprofile table, if enabled
	// > Profile mapping will only work with new users. Profile mapping for returning users will implemented in future version of WSL.
	if( $is_new_user )
	{
		wsl_buddypress_xprofile_mapping( $user_id, $provider, $hybridauth_user_profile );
	}

	// import user contacts into wslusersprofiles, if enabled
	// > wsl will only import the contacts list once per user per provider.
	wsl_store_hybridauth_user_contacts( $user_id, $provider, $adapter );
}

// --------------------------------------------------------------------

/**
* Authenticate a user within wordpress
*
* Ref: http://codex.wordpress.org/Function_Reference/wp_set_auth_cookie
* Ref: http://codex.wordpress.org/Function_Reference/wp_safe_redirect
*/
function wsl_process_login_authenticate_wp_user( $user_id, $provider, $redirect_to, $adapter, $hybridauth_user_profile, $wp_user )
{
	// HOOKABLE:
	do_action( "wsl_process_login_authenticate_wp_user_start", $user_id, $provider, $redirect_to, $adapter, $hybridauth_user_profile, $wp_user );

	// update some fields in usermeta for the current user
	update_user_meta( $user_id, 'wsl_current_provider', $provider );

	if(  $hybridauth_user_profile->photoURL )
	{
		update_user_meta( $user_id, 'wsl_current_user_image', $hybridauth_user_profile->photoURL );
	}

	// Bouncer::User Moderation
	// > When Bouncer::User Moderation is enabled, WSL will check for the current user role. If equal to 'pending', then Bouncer will do the following : 
	// 	1. Halt the authentication process, 
	// 	2. Skip setting the authentication cookies for the user, 
	// 	3. Reset the Redirect URL to the appropriate Theme My Login page.
	$wsl_settings_bouncer_new_users_moderation_level = get_option( 'wsl_settings_bouncer_new_users_moderation_level' );

	// current user role
	$role = current( $wp_user->roles );

	// if role eq 'pending', we halt the authentication and we redirect the user to the appropriate url (pending=activation or pending=approval)
	if( $role == 'pending' )
	{
		// E-mail Confirmation
		if( $wsl_settings_bouncer_new_users_moderation_level == 101 )
		{
			$redirect_to = site_url( 'wp-login.php', 'login_post' ) . ( strpos( site_url( 'wp-login.php', 'login_post' ), '?' ) ? '&' : '?' ) . "pending=activation";

			// send a new e-mail/activation notification - if TML not enabled, we ensure WSL to keep it quiet
			@ Theme_My_Login_User_Moderation::new_user_activation_notification( $user_id );
		}

		// Admin Approval
		elseif( $wsl_settings_bouncer_new_users_moderation_level == 102 )
		{
			$redirect_to = site_url( 'wp-login.php', 'login_post' ) . ( strpos( site_url( 'wp-login.php', 'login_post' ), '?' ) ? '&' : '?' ) . "pending=approval";
		}
	}

	// otherwise, we connect the user with in wordpress (we give him a cookie)
	else
	{
		// HOOKABLE: This action runs just before logging the user in (before creating a WP cookie)
		do_action( "wsl_hook_process_login_before_wp_set_auth_cookie", $user_id, $provider, $hybridauth_user_profile );

		// DEPRECIATED: as of 2.2.3
		// do_action( 'wsl_hook_process_login_before_set_auth_cookie', $user_id, $provider, $hybridauth_user_profile );

		// Set WP auth cookie
		wp_set_auth_cookie( $user_id, true );

		// let keep it std
		do_action( 'wp_login', $wp_user->user_login, $wp_user );
	}

	// HOOKABLE: This action runs just before redirecting the user back to $redirect_to
	// > Note: If you have enabled User Moderation, then the user is NOT NECESSARILY CONNECTED
	// > within wordpress at this point (in case the user $role == 'pending').
	// > To be sure the user is connected, use wsl_hook_process_login_before_wp_set_auth_cookie instead.
	do_action( "wsl_hook_process_login_before_wp_safe_redirect", $user_id, $provider, $hybridauth_user_profile, $redirect_to );

	// DEPRECIATED: as of 2.2.3
	// do_action( 'wsl_hook_process_login_before_set_auth_cookie', $user_id, $provider, $hybridauth_user_profile );

	do_action( 'wsl_clear_user_php_session' );

	// Display WSL debugging instead of redirecting the user
	// > this will give a complete report on what wsl did : database queries and fired hooks 
	// wsl_display_dev_mode_debugging_area(); die(); // ! keep this line commented unless you know what you are doing :)

	// That's it. We done. 
	wp_safe_redirect( $redirect_to );

	// for good measures
	die();
}

// --------------------------------------------------------------------

/**
*  Build required hybridauth configuration for the given provider
*/
function wsl_process_login_build_provider_config( $provider )
{
	$config = array();
	$config["base_url"] = WORDPRESS_SOCIAL_LOGIN_HYBRIDAUTH_ENDPOINT_URL; 
	$config["providers"] = array();
	$config["providers"][$provider] = array();
	$config["providers"][$provider]["enabled"] = true;
	$config["providers"][$provider]["keys"] = array( 'id' => null, 'key' => null, 'secret' => null );

	// provider application id ?
	if( get_option( 'wsl_settings_' . $provider . '_app_id' ) )
	{
		$config["providers"][$provider]["keys"]["id"] = get_option( 'wsl_settings_' . $provider . '_app_id' );
	}

	// provider application key ?
	if( get_option( 'wsl_settings_' . $provider . '_app_key' ) )
	{
		$config["providers"][$provider]["keys"]["key"] = get_option( 'wsl_settings_' . $provider . '_app_key' );
	}

	// provider application secret ?
	if( get_option( 'wsl_settings_' . $provider . '_app_secret' ) )
	{
		$config["providers"][$provider]["keys"]["secret"] = get_option( 'wsl_settings_' . $provider . '_app_secret' );
	}

	// set custom endpoint?
	if( in_array( strtolower( $provider ), array( 'dribbble' ) ) )
	{
		$config["providers"][$provider]["endpoint"] = WORDPRESS_SOCIAL_LOGIN_HYBRIDAUTH_ENDPOINT_URL . 'endpoints/' . strtolower( $provider ) . '.php';
	}

	// set default scope
	if( get_option( 'wsl_settings_' . $provider . '_app_scope' ) )
	{
		$config["providers"][$provider]["scope"] = get_option( 'wsl_settings_' . $provider . '_app_scope' );
	}

	// set custom config for facebook
	if( strtolower( $provider ) == "facebook" )
	{
		$config["providers"][$provider]["display"] = "popup";
		$config["providers"][$provider]["trustForwarded"] = true;

		// switch to fb::display 'page' if wsl auth in page
		if( get_option( 'wsl_settings_use_popup') == 2 )
		{
			$config["providers"][$provider]["display"] = "page";
		}
	}

	// set custom config for google
	if( strtolower( $provider ) == "google" )
	{
		// if contacts import enabled, we request an extra permission 'https://www.google.com/m8/feeds/'
		if( wsl_is_component_enabled( 'contacts' ) && get_option( 'wsl_settings_contacts_import_google' ) == 1 )
		{
			$config["providers"][$provider]["scope"] .= " https://www.google.com/m8/feeds/";
		}
	}

	$provider_scope = isset( $config["providers"][$provider]["scope"] ) ? $config["providers"][$provider]["scope"] : '' ; 

	// HOOKABLE: allow to overwrite scopes
	$config["providers"][$provider]["scope"] = apply_filters( 'wsl_hook_alter_provider_scope', $provider_scope, $provider );

	// HOOKABLE: allow to overwrite hybridauth config for the selected provider
	$config["providers"][$provider] = apply_filters( 'wsl_hook_alter_provider_config', $config["providers"][$provider], $provider );

	return $config;
}

// --------------------------------------------------------------------

/**
*  Grab the user profile from social network 
*/
function wsl_process_login_request_user_social_profile( $provider )
{
	$adapter                 = null;
	$config                  = null;
	$hybridauth_user_profile = null;

	try
	{
		// get idp adapter
		$adapter = wsl_process_login_get_provider_adapter( $provider );

		$config = $adapter->config;

		// if user authenticated successfully with social network
		if( $adapter->isUserConnected() )
		{
			// grab user profile via hybridauth api
			$hybridauth_user_profile = $adapter->getUserProfile();
		}
		
		// if user not connected to provider (ie: session lost, url forged)
		else
		{
			return wsl_process_login_render_notice_page( sprintf( _wsl__( "Sorry, we couldn't connect you with <b>%s</b>. <a href=\"%s\">Please try again</a>.", 'wordpress-social-login' ), $provider, site_url( 'wp-login.php', 'login_post' ) ) );
		} 
	}

	// if things didn't go as expected, we dispay the appropriate error message
	catch( Exception $e )
	{
		return wsl_process_login_render_error_page( $e, $config, $provider, $adapter );
	}

	return $hybridauth_user_profile;
}

// --------------------------------------------------------------------

/**
* Returns hybriauth idp adapter.
*/
function wsl_process_login_get_provider_adapter( $provider )
{
	if( ! class_exists( 'Hybrid_Auth', false ) )
	{
		require_once WORDPRESS_SOCIAL_LOGIN_ABS_PATH . "/hybridauth/Hybrid/Auth.php";
	}

	return Hybrid_Auth::getAdapter( $provider );
}

// --------------------------------------------------------------------

/**
* Returns redirect_to (callback url)
*
* By default, once a user  authenticate, he will be automatically redirected to the page where he come from (referer).
* If WSL wasn't able to identify the referer url (or if the user come wp-login.php), then they will be redirected to 
* Widget::Redirect URL instead. 
*
* When Widget::Force redirection is set to Yes, users will be always redirected to Widget::Redirect URL. 
*
* Note: Widget::Redirect URL can be customised using the filter 'wsl_hook_process_login_alter_redirect_to'
*/
function wsl_process_login_get_redirect_to()
{
	// force redirection?
	$wsl_settings_redirect_url = get_option( 'wsl_settings_redirect_url' );

	if( get_option( 'wsl_settings_force_redirect_url' ) == 1 )
	{
		$redirect_to = apply_filters( 'wsl_hook_process_login_alter_redirect_to', $wsl_settings_redirect_url );

		return $redirect_to;
	}

	// get a valid $redirect_to
	if( isset( $_REQUEST[ 'redirect_to' ] ) && $_REQUEST[ 'redirect_to' ] != '' )
	{
		$redirect_to = $_REQUEST[ 'redirect_to' ];

		// Redirect to https if user wants ssl
		if( isset( $secure_cookie ) && $secure_cookie && false !== strpos( $redirect_to, 'wp-admin') )
		{
			$redirect_to = preg_replace( '|^http://|', 'https://', $redirect_to );
		}

		// we don't go there..
		if( strpos( $redirect_to, 'wp-admin') )
		{
			$redirect_to = $wsl_settings_redirect_url; 
		}

		// nor there..
		if( strpos( $redirect_to, 'wp-login.php') )
		{
			$redirect_to = $wsl_settings_redirect_url; 
		}
	}

	if( empty( $redirect_to ) )
	{
		$redirect_to = $wsl_settings_redirect_url; 
	}

	if( empty( $redirect_to ) )
	{
		$redirect_to = site_url();
	}

	$redirect_to = apply_filters( 'wsl_hook_process_login_alter_redirect_to', $redirect_to );

	return $redirect_to;
}

// --------------------------------------------------------------------

/**
* Display an error message in case user authentication fails
*/
function wsl_process_login_render_error_page( $e, $config = null, $provider = null, $adapter = null )
{
	// HOOKABLE:
	do_action( "wsl_process_login_render_error_page", $e, $config, $provider, $adapter );

	$assets_base_url  = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . '/assets/img/'; 

	$message  = _wsl__("Unspecified error!", 'wordpress-social-login'); 
	$notes    = ""; 
	$apierror = substr( $e->getMessage(), 0, 145 );

	switch( $e->getCode() )
	{
		case 0 : $message = _wsl__("Unspecified error.", 'wordpress-social-login'); break;
		case 1 : $message = _wsl__("WordPress Social Login is not properly configured.", 'wordpress-social-login'); break;
		case 2 : $message = sprintf( __wsl__("WordPress Social Login is not properly configured.<br /> <b>%s</b> need to be properly configured.", 'wordpress-social-login'), $provider ); break;
		case 3 : $message = _wsl__("Unknown or disabled provider.", 'wordpress-social-login'); break;
		case 4 : $message = sprintf( _wsl__("WordPress Social Login is not properly configured.<br /> <b>%s</b> requires your application credentials.", 'wordpress-social-login'), $provider ); 
			 $notes   = sprintf( _wsl__("<b>What does this error mean ?</b><br />Most likely, you didn't setup the correct application credentials for this provider. These credentials are required in order for <b>%s</b> users to access your website and for WordPress Social Login to work.", 'wordpress-social-login'), $provider ) . _wsl__('<br />Instructions for use can be found in the <a href="http://miled.github.io/wordpress-social-login/networks.html" target="_blank">User Manual</a>.', 'wordpress-social-login'); 
			 break;
		case 5 : $message = sprintf( _wsl__("Authentication failed. Either you have cancelled the authentication or <b>%s</b> refused the connection.", 'wordpress-social-login'), $provider ); break; 
		case 6 : $message = sprintf( _wsl__("Request failed. Either you have cancelled the authentication or <b>%s</b> refused the connection.", 'wordpress-social-login'), $provider ); break;
		case 7 : $message = _wsl__("You're not connected to the provider.", 'wordpress-social-login'); break;
		case 8 : $message = _wsl__("Provider does not support this feature.", 'wordpress-social-login'); break;
	}

	if( is_object( $adapter ) )
	{
		$adapter->logout();
	}

	// provider api response
	if( class_exists( 'Hybrid_Error', false ) && Hybrid_Error::getApiError() )
	{
		$tmp = Hybrid_Error::getApiError();

		$apierror = $apierror . "\n" . '<br />' . $tmp;

		// network issue
		if( trim( $tmp ) == '0.' )
		{
			$apierror = "Could not establish connection to provider API";
		}
	}

	return wsl_render_error_page( $message, $notes, $provider, $apierror, $e );
}

// --------------------------------------------------------------------

/**
* Display an notice message
*/
function wsl_process_login_render_notice_page( $message )
{
	// HOOKABLE:
	do_action( "wsl_process_login_render_notice_page", $message );

	return wsl_render_notice_page( $message );
}

// --------------------------------------------------------------------

/**
* Returns the selected provider from _REQUEST, default to null
*/
function wsl_process_login_get_selected_provider()
{
	$provider = isset( $_REQUEST["provider"] ) ? sanitize_text_field( $_REQUEST["provider"] ) : null;

	return apply_filters( 'wsl_hook_process_login_alter_provider', $provider ) ;
}

// --------------------------------------------------------------------

/**
* Returns the selected auth mode from _REQUEST, default to login
*/
function wsl_process_login_get_auth_mode()
{
	$auth_mode = isset( $_REQUEST["mode"] ) ? sanitize_text_field( $_REQUEST["mode"] ) : 'login';

	return apply_filters( 'wsl_hook_process_login_alter_auth_mode', $auth_mode ) ;
}

// --------------------------------------------------------------------

/**
* Clear the stored data by hybridauth and wsl in php session
*/
function wsl_process_login_clear_user_php_session()
{
	$_SESSION["HA::STORE"]        = array(); // used by hybridauth library. to clear as soon as the auth process ends.
	$_SESSION["HA::CONFIG"]       = array(); // used by hybridauth library. to clear as soon as the auth process ends.
	$_SESSION["wsl::userprofile"] = array(); // used by wsl to temporarily store the user profile so we don't make unnecessary calls to social apis.
}

// --------------------------------------------------------------------

/**
* Check Php session
*/
function wsl_process_login_check_php_session()
{
	if( isset( $_SESSION["wsl::plugin"] ) && $_SESSION["wsl::plugin"] )
	{
		return true;
	}
}

// --------------------------------------------------------------------
