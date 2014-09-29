<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*    (c) 2011-2014 Mohamed Mrassi and contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

/**
* Authenticate users via social networks. 
*
* To sum things up, here is how things works in this script (bit hard to explain, so bare with me):
*
*   [icons links]                            A wild visitor appear and click on one of these providers. Obviously he will redirected to yourblog.com/wp-login.php (with specific args in the url: &action=wordpress_social_authenticate&..)
*       => [wp-login.php]                    wp-login.php will first call wsl_process_login(). wsl_process_login() will attempt to authenticate the user through Hybridauth Library;
*           => [Hybridauth] <=> [Provider]   Hybridauth and the Provider will have some little chat on their own. 
*               => [Provider]                If the visitor consent and agrees to authenticate, then horray for you;
*                   => [wp-login.php]        Provider will then redirect the user to back wp-login.php and wsl_process_login() will kick in;
*                       => [callback URL]    If things goes as expected, the wsl_process_login will log the user in your website and redirect him to where he come from (or Redirect URL).
*
* when wsl_process_login() is triggered, it will attempt to recognize the user.
* If he exist on the database as WSL user, then fine we cut things short.
* If not, attempt to recognize users based on his email (this only when users authenticate through a provider who give an verified email, ex: Facebook, Google, Yahoo, Foursquare). 
* Otherwise create new account for him. 
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------

/**
* Entry point to the authentication process
*
* This function will be called by wp-login.php
* 
* Example of valid origin url:
*   wp-login.php
*       ?action=wordpress_social_authenticate
*       &provider=Twitter
*       &redirect_to=http%3A%2F%2Fhybridauth.com%2Fwordpress%2F%3Fp%3D1
*       &redirect_to_provider=true
*/
function wsl_process_login()
{
	// check for the call origin
	// > action should be either 'wordpress_social_authenticate', 'wordpress_social_profile_completion' or 'wordpress_social_authenticated'
	if( ! isset( $_REQUEST[ 'action' ] ) ){
		return false;
	}

	if( ! in_array( $_REQUEST[ 'action' ], array( "wordpress_social_authenticate", "wordpress_social_profile_completion", "wordpress_social_authenticated" ) ) ){
		return false;
	}

	// Bouncer :: Allow authentication?
	if( get_option( 'wsl_settings_bouncer_authentication_enabled' ) == 2 ){
		wsl_render_notices_pages( _wsl__( "WSL is disabled!", 'wordpress-social-login' ) ); 

		return false;
	}

	// if action=wordpress_social_authenticate
	// > start the first part of authentication (redirect the user to the selected provider)
	if( $_REQUEST[ 'action' ] == "wordpress_social_authenticate" ){
		wsl_process_login_begin();
	}

	// if action=wordpress_social_authenticated
	// > finish the authentication process (create new user if doesn't exist in database, then log him in within wordpress)
	else{
		wsl_process_login_end();
	}
}

add_action( 'init', 'wsl_process_login' );

// --------------------------------------------------------------------

/**
* Start the first part of authentication
* 
* Steps:
*     1. Instantiate the class Hybrid_Auth
*     2. Build the config for the selected provider (keys, scope, etc)
*     3. Redirect the user to provider
*/
function wsl_process_login_begin()
{
	// HOOKABLE: a hook in case someone need it
	do_action( "wsl_hook_process_login_begin_start" );

	$config     = null;
	$hybridauth = null;
	$provider   = null;
	$adapter    = null;
	$profile    = null;

	// first, we display a loading message (should be better than a white screen)
	// then we reload the page with a new arg : redirect_to_provider=true
	if( ! isset( $_REQUEST["redirect_to_provider"] ) ){
		wsl_process_login_render_loading_page();
	}

	// check url args
	// provider is required and redirect_to_provider eq true
	if( ! isset( $_REQUEST['provider'] ) || ! isset( $_REQUEST['redirect_to_provider'] )  || $_REQUEST['redirect_to_provider'] != 'true' ){
		return wsl_render_notices_pages( _wsl__( 'Bouncer says this makes no sense.', 'wordpress-social-login' ) ); 
	}

	try{
		// user already logged in?
		if( is_user_logged_in() ){
			global $current_user;

			get_currentuserinfo(); 

			wp_die( sprintf( _wsl__( "You are already logged in as <b>%s</b>.", 'wordpress-social-login' ), $current_user->display_name ) );
		}

		// load Hybrid_Auth
		if ( ! class_exists('Hybrid_Auth', false) ){
			require_once WORDPRESS_SOCIAL_LOGIN_ABS_PATH . "/hybridauth/Hybrid/Auth.php"; 
		}

		// selected provider name 
		$provider = wsl_process_login_get_selected_provider();

		// provider enabled?
		if( ! get_option( 'wsl_settings_' . $provider . '_enabled' ) ){
			return wsl_render_notices_pages( _wsl__( "Unknown or disabled provider.", 'wordpress-social-login' ) );
		}

		// default endpoint_url/callback_url
		$endpoint_url = WORDPRESS_SOCIAL_LOGIN_HYBRIDAUTH_ENDPOINT_URL;
		$callback_url = null; // specific to hybridauth, kept null for future use

		// check hybridauth_base_url
		if( ! strstr( $endpoint_url, "http://" ) && ! strstr( $endpoint_url, "https://" ) ){
			return wsl_render_notices_pages( sprintf( _wsl__( "Invalid base_url: %s.", 'wordpress-social-login' ), $endpoint_url ) );
		}

		// build required configuration for this provider
		$config = array();
		$config["base_url"]  = $endpoint_url; 
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

		// set default scope and display mode for facebook
		if( strtolower( $provider ) == "facebook" ){
			$config["providers"][$provider]["scope"]   = "email, user_about_me, user_birthday, user_hometown, user_website"; 
			$config["providers"][$provider]["display"] = "popup";
			$config["providers"][$provider]["trustForwarded"] = true;

			// switch to fb::display 'page' if wsl auth in page
			if ( get_option( 'wsl_settings_use_popup') == 2 ) {
				$config["providers"][$provider]["display"] = "page";
			}
		}

		// set default scope for google
		if( strtolower( $provider ) == "google" ){
			$config["providers"][$provider]["scope"] = "https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/plus.profile.emails.read";  
		}

		// if contacts import enabled for facebook, we request an extra permission 'read_friendlists'
		if( get_option( 'wsl_settings_contacts_import_facebook' ) == 1 && strtolower( $provider ) == "facebook" ){
			$config["providers"][$provider]["scope"] = "email, user_about_me, user_birthday, user_hometown, user_website, read_friendlists";
		}

		// if contacts import enabled for google, we request an extra permission 'https://www.google.com/m8/feeds/'
		if( get_option( 'wsl_settings_contacts_import_google' ) == 1 && strtolower( $provider ) == "google" ){
			$config["providers"][$provider]["scope"] = "https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/plus.profile.emails.read https://www.google.com/m8/feeds/";
		}

		// HOOKABLE: allow to overwrite scopes (some people have asked for a way to lower the number of permissions requested)
		# https://developers.facebook.com/docs/facebook-login/permissions
		# https://developers.google.com/+/domains/authentication/scopes
		$provider_scope = isset( $config["providers"][$provider]["scope"] ) ? $config["providers"][$provider]["scope"] : '' ; 
		$config["providers"][$provider]["scope"] = apply_filters( 'wsl_hook_alter_provider_scope', $provider_scope, $provider );

		// create an instance for Hybridauth
		$hybridauth = new Hybrid_Auth( $config );

		// try to authenticate the selected $provider
		$params  = array();

		// if callback_url defined it will overwrite Hybrid_Auth::getCurrentUrl(); 
		if( $callback_url ){
			$params["hauth_return_to"] = $callback_url;
		}

		// start the authentication process via hybridauth
		// > if not already connected hybridauth::authenticate() will redirect the user to the provider
		// > where he will be asked for his consent (most providers ask for consent only once). 
		// > after that, the provider will redirect the user back to this same page (and this same line). 
		// > if the user is successfully connected to provider, then this time hybridauth::authenticate()
		// > will just return the provider adapter
		$adapter = $hybridauth->authenticate( $provider, $params );

		// get Widget::Authentication display
		$wsl_settings_use_popup = get_option( 'wsl_settings_use_popup' );

		// if Authentication display is undefined or eq Popup 
		// > create a from with javascript in parent window and submit it to wp-login.php
		// > (with action=wordpress_social_authenticated), then close popup
		if( $wsl_settings_use_popup == 1 || ! $wsl_settings_use_popup ){
			?>
				<html><head><script>
				function init() {
					window.opener.wsl_wordpress_social_login({
						'action'   : 'wordpress_social_authenticated',
						'provider' : '<?php echo $provider ?>'
					});

					window.close()
				}
				</script></head><body onload="init();"></body></html>
			<?php
		}

		// if Authentication display eq In Page
		// > create a from in page then submit it to wp-login.php (with action=wordpress_social_authenticated)
		elseif( $wsl_settings_use_popup == 2 ){
			$redirect_to = site_url();

			if( isset( $_REQUEST[ 'redirect_to' ] ) ){
				$redirect_to = urldecode( $_REQUEST[ 'redirect_to' ] );
			}
			?>
				<html><head><script>
				function init() { document.loginform.submit() }
				</script></head><body onload="init();">
				<form name="loginform" method="post" action="<?php echo site_url( 'wp-login.php', 'login_post' ); ?>">
					<input type="hidden" id="redirect_to" name="redirect_to" value="<?php echo $redirect_to ?>"> 
					<input type="hidden" id="provider" name="provider" value="<?php echo $provider ?>"> 
					<input type="hidden" id="action" name="action" value="wordpress_social_authenticated">
				</form></body></html> 
			<?php
		}
	}

	// if hybridauth fails to authenticate the user, then we display an error message
	catch( Exception $e ){
		wsl_process_login_render_error_page( $e, $config, $hybridauth, $provider, $adapter );
	}

	die();
}

// --------------------------------------------------------------------

/**
* Finish the authentication process 
* 
* Steps:
*     1. Get the user profile from provider
*     2. Create new wordpress user if he didn't exist in database
*     3. Import the user contacts, if enabled
*     4. Authenticate the user within wordpress
*/
function wsl_process_login_end()
{
	// HOOKABLE: a hook in case someone need it
	do_action( "wsl_hook_process_login_end_start" );

	// HOOKABLE: set a custom Redirect URL
	$redirect_to = apply_filters("wsl_hook_process_login_alter_redirect_to", wsl_process_login_get_redirect_to() ) ;

	// HOOKABLE: reset the provider id
	$provider = apply_filters("wsl_hook_process_login_alter_provider", wsl_process_login_get_selected_provider() ) ;

	// is it a new or returning user
	$is_new_user = false;

	// call wsl_process_login_end_get_userdata(), which will try to:
	//    1. grab the user profile from hybridauth,
	//    2. check if he exist in database (returns user_id if it does)
	//    3. run Bouncer::Filters if enabled (domains, emails, profiles urls)
	//    4. complete user profile (user name & email) if Profile Completion is enabled
	list( 
		$user_id                , // user_id>0 if found on database
		$adapter                , // hybriauth adapter for the selected provider
		$hybridauth_user_profile, // hybriauth user profile
		$hybridauth_user_id     , // id in table wslusersprofiles
		$hybridauth_user_email  , // user email as provided by the provider
		$request_user_login     , // username typed by users in Profile Completion
		$request_user_email     , // email typed by users in Profile Completion
	)
	= wsl_process_login_end_get_userdata( $provider, $redirect_to );

	// if user found on database, we get his wordpress data
	if( $user_id ){
		$user_data  = get_userdata( $user_id );

		$user_login = $user_data->user_login; 
		$user_email = $hybridauth_user_profile->email; 
	}

	// otherwise, create new user within wordpress
	else{ 
		list(
			$user_id    , // id in table users
			$user_login , // login
			$user_email , // email
		)
		= wsl_process_login_create_wp_user( $provider, $hybridauth_user_profile, $request_user_login, $request_user_email );

		$is_new_user = true;
	}

	// we check if it is a valid id, so just in case
	if( ! is_integer( $user_id ) ){
		return wsl_render_notices_pages( _wsl__("Invalid user_id returned by create_wp_user.", 'wordpress-social-login') );
	}

	// store user hybridauth user profile in table wslusersprofiles
	wsl_store_hybridauth_user_data( $user_id, $provider, $hybridauth_user_profile );

	// map hybridauth profile to buddypress xprofile table, if enabled
	// > Profile mapping will only work with new users. Profile mapping for returning users will implemented in future version of WSL.
	if( $is_new_user ){
		wsl_buddypress_xprofile_mapping( $user_id, $provider, $hybridauth_user_profile );
	}

	// launch contact import, if enabled
	wsl_import_user_contacts( $user_id, $provider, $adapter );

	// finally create a wordpress session for the user
	return wsl_process_login_authenticate_wp_user( $user_id, $provider, $redirect_to, $adapter, $hybridauth_user_profile );
}

// --------------------------------------------------------------------

/**
* Returns user data after he authenticate via hybridauth 
*
* Steps:
*    1. grab the user profile from hybridauth,
*    2. check if he exist in database (returns user_id if it does)
*    3. run Bouncer::Filters if enabled (domains, emails, profiles urls)
*    4. complete user profile (user name & email) if Profile Completion is enabled
*/
function wsl_process_login_end_get_userdata( $provider, $redirect_to )
{
	$user_id    = null;
	$config     = null;
	$hybridauth = null; 
	$adapter    = null;

	try{
		// load Hybrid_Auth
		if ( ! class_exists('Hybrid_Auth', false) ) {
			require_once WORDPRESS_SOCIAL_LOGIN_ABS_PATH . "/hybridauth/Hybrid/Auth.php"; 
		} 

		// provider is enabled?
		if( ! get_option( 'wsl_settings_' . $provider . '_enabled' ) ){
			return wsl_render_notices_pages( _wsl__( "Unknown or disabled provider.", 'wordpress-social-login' ) );
		}

		// build required configuration for this provider
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
			$hybridauth_user_id    = (int) wsl_get_user_by_meta( $provider, $hybridauth_user_profile->identifier ); 
			$hybridauth_user_email = sanitize_email( $hybridauth_user_profile->email ); 
			$hybridauth_user_login = sanitize_user( $hybridauth_user_profile->displayName, true );

			$request_user_login    = '';
			$request_user_email    = '';

			# {{{ module Bouncer
			// Bouncer::Filters by emails domains name
			if( get_option( 'wsl_settings_bouncer_new_users_restrict_domain_enabled' ) == 1 ){ 
				if( empty( $hybridauth_user_email ) ){
					return wsl_render_notices_pages( _wsl__( get_option( 'wsl_settings_bouncer_new_users_restrict_domain_text_bounce' ), 'wordpress-social-login') );
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
					return wsl_render_notices_pages( _wsl__( get_option( 'wsl_settings_bouncer_new_users_restrict_domain_text_bounce' ), 'wordpress-social-login') );
				}
			}

			// Bouncer::Filters by e-mails addresses
			if( get_option( 'wsl_settings_bouncer_new_users_restrict_email_enabled' ) == 1 ){ 
				if( empty( $hybridauth_user_email ) ){
					return wsl_render_notices_pages( _wsl__( get_option( 'wsl_settings_bouncer_new_users_restrict_email_text_bounce' ), 'wordpress-social-login') );
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
					return wsl_render_notices_pages( _wsl__( get_option( 'wsl_settings_bouncer_new_users_restrict_email_text_bounce' ), 'wordpress-social-login') );
				}
			}

			// Bouncer ::Filters by profile urls
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
					return wsl_render_notices_pages( _wsl__( get_option( 'wsl_settings_bouncer_new_users_restrict_profile_text_bounce' ), 'wordpress-social-login') );
				}
			}

			// if user do not exist
			if( ! $hybridauth_user_id ){
				// Bouncer :: Accept new registrations
				if( get_option( 'wsl_settings_bouncer_registration_enabled' ) == 2 ){
					return wsl_render_notices_pages( _wsl__("registration is now closed!", 'wordpress-social-login') ); 
				}

				// Bouncer :: Profile Completion
				if(
					( get_option( 'wsl_settings_bouncer_profile_completion_require_email' ) == 1 && empty( $hybridauth_user_email ) ) || 
					get_option( 'wsl_settings_bouncer_profile_completion_change_username' ) == 1
				){
					do
					{
						list( $shall_pass, $request_user_login, $request_user_email ) = wsl_process_login_complete_registration( $provider, $redirect_to, $hybridauth_user_email, $hybridauth_user_login );
					}
					while( ! $shall_pass );
				}
			}
			# }}} module Bouncer
		}
		else{
			return wsl_render_notices_pages( sprintf( _wsl__( "User not connected with <b>%s</b>", 'wordpress-social-login' ), $provider ) ); 
		} 
	}
	catch( Exception $e ){
		return wsl_process_login_render_error_page( $e, $config, $hybridauth, $provider, $adapter );
	}

	// if the user email is verified, then try to map to legacy account if exist
	// > Only few idps (like Facebook, Google, Yahoo and Foursquare) provides a verified user email.
	if ( ! empty( $hybridauth_user_profile->emailVerified ) ){
		$user_id = (int) email_exists( $hybridauth_user_profile->emailVerified );
	}

	# http://boku.ru/files/wsl/commit-56b8b84
	// try to guess (reliably) user profile with filters
	// this allows for looking at other stuff (such as associated OpenID)
	$user_id = apply_filters( 'wsl_hook_process_login_reliably_guess_user', $user_id, $provider, $hybridauth_user_profile );

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

/**
* Create new wordpress user
*/
function wsl_process_login_create_wp_user( $provider, $hybridauth_user_profile, $request_user_login, $request_user_email )
{
	// HOOKABLE: any action to fire right before a user created on database
	do_action( 'wsl_hook_process_login_before_create_wp_user' );

	$user_login = '';
	$user_email = '';

	// if coming from "complete registration form" 
	if( $request_user_login ){
		$user_login = $request_user_login;
	}

	if( $request_user_email ){
		$user_email = $request_user_email;
	}

	if ( ! $user_login ){
		// generate a valid user login
		$user_login = sanitize_user( $hybridauth_user_profile->displayName, true );

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

			do{
				$user_login_tmp = $user_login . "_" . ($i++);
			} while (username_exists ($user_login_tmp));

			$user_login = $user_login_tmp;
		}

		$user_login = sanitize_user($user_login, true );

		if( ! validate_username( $user_login ) ){
			$user_login = strtolower( $provider ) . "_user_" . md5( $hybridauth_user_profile->identifier );
		}
	}

	if ( ! $user_email ){
		$user_email = $hybridauth_user_profile->email;

		// generate an email if none
		if ( ! isset ( $user_email ) OR ! is_email( $user_email ) ){
			$user_email = strtolower( $provider . "_user_" . $user_login ) . "@example.com";
		}

		// email should be unique
		if ( email_exists ( $user_email ) ){
			do{
				$user_email = md5(uniqid(wp_rand(10000,99000)))."@example.com";
			} while( email_exists( $user_email ) );
		} 
	}

	$display_name = $hybridauth_user_profile->displayName;

	if( $request_user_login ){
		$display_name = sanitize_user( $request_user_login, true );
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

	# {{{ module Bouncer 
	# http://www.jfarthing.com/development/theme-my-login/user-moderation/
	// Bouncer::Membership level 
	// when enabled (!= 'default'), it defines the new user role
	$wsl_settings_bouncer_new_users_membership_default_role = get_option( 'wsl_settings_bouncer_new_users_membership_default_role' );

	if( $wsl_settings_bouncer_new_users_membership_default_role != "default" ){ 
		$userdata['role'] = $wsl_settings_bouncer_new_users_membership_default_role;
	}

	// Bouncer::User Moderation 
	// > if enabled (Yield to Theme My Login), then we overwrite the user role to 'pending'
	// > (If User Moderation is set to Admin Approval then Membership level will be ignored)
	if( get_option( 'wsl_settings_bouncer_new_users_moderation_level' ) > 100 ){ 
		// Theme My Login : User Moderation
		// > Upon activation of this module, a new user role will be created, titled “Pending”. This role has no privileges by default.
		// > When a user confirms their e-mail address or when you approve a user, they are automatically assigned to the default user role for the blog/site.
		// http://www.jfarthing.com/development/theme-my-login/user-moderation/
		$userdata['role'] = "pending";
	}
	# }}} module Bouncer

	// HOOKABLE: change the user data
	$userdata = apply_filters( 'wsl_hook_process_login_alter_userdata', $userdata, $provider, $hybridauth_user_profile );

	// HOOKABLE: any action to fire right before a user created on database
	do_action( 'wsl_hook_process_login_before_insert_user', $userdata, $provider, $hybridauth_user_profile );

	// HOOKABLE: delegate user insert to a custom function
	$user_id = apply_filters( 'wsl_hook_process_login_alter_insert_user', $userdata, $provider, $hybridauth_user_profile );

	// Create a new user
	if( ! $user_id || ! is_integer( $user_id ) ){
		$user_id = wp_insert_user( $userdata );
	}

	// update user metadata
	if( $user_id && is_integer( $user_id ) ){
		update_user_meta( $user_id, $provider, $hybridauth_user_profile->identifier );
	}

	// do not continue without user_id
	else {
		if( is_wp_error( $user_id ) ){
			return wsl_render_notices_pages( _wsl__("An error occurred while creating a new user!" . $user_id->get_error_message(), 'wordpress-social-login') );
		}

		return wsl_render_notices_pages( _wsl__("An error occurred while creating a new user!", 'wordpress-social-login') );
	}

	// Send notifications 
	if ( get_option( 'wsl_settings_users_notification' ) == 1 ){
		wsl_admin_notification( $user_id, $provider );
	}

	// HOOKABLE: any action to fire right after a user created on database
	do_action( 'wsl_hook_process_login_after_create_wp_user', $user_id, $provider, $hybridauth_user_profile );

	return array( 
		$user_id,
		$user_login,
		$user_email 
	);
}

// --------------------------------------------------------------------

/**
*
*/
function wsl_process_login_authenticate_wp_user( $user_id, $provider, $redirect_to, $adapter, $hybridauth_user_profile )
{
	// There was a bug when this function received non-integer user_id and updated random users, let's be safe
	if( !is_integer( $user_id ) ){
		return wsl_render_notices_pages( _wsl__("Invalid user_id", 'wordpress-social-login') );
	}

	// calculate user age
	$user_age = (int) preg_replace('/\D/', '', $hybridauth_user_profile->age );

	// not that precise you say... well welcome to my world
	// if you want to improve this, you are more than welcome tho
	if( ! $user_age && (int) $hybridauth_user_profile->birthYear ){
		$user_age = (int) date("Y") - (int) $hybridauth_user_profile->birthYear;
	}

	// update some stuff
	$newdata['user_id']     = $user_id; //not to be changed
	$newdata['user']        = $provider;
	$newdata['user_gender'] = $hybridauth_user_profile->gender;
	$newdata['user_age']    = $user_age;
	$newdata['user_image']  = $hybridauth_user_profile->photoURL;

	// HOOKABLE: 
	$newdata = apply_filters( 'wsl_hook_process_login_alter_update_userdata', $newdata, $hybridauth_user_profile, $provider );

	update_user_meta ( $user_id, 'wsl_user'       , $newdata['user'] );
	update_user_meta ( $user_id, 'wsl_user_gender', $newdata['user_gender'] );
	update_user_meta ( $user_id, 'wsl_user_age'   , $newdata['user_age'] );
	update_user_meta ( $user_id, 'wsl_user_image' , $newdata['user_image'] );

	# {{{ module Bouncer
	# http://www.jfarthing.com/development/theme-my-login/user-moderation/
	# https://wordpress.org/support/topic/bouncer-user-moderation-blocks-logins-when-enabled#post-4331601
	$role = ''; 
	$wsl_settings_bouncer_new_users_moderation_level = get_option( 'wsl_settings_bouncer_new_users_moderation_level' );

	// get user role 
	if( $wsl_settings_bouncer_new_users_moderation_level > 100 ){
		$role = current( get_userdata( $user_id )->roles );
	}

	// if role eq 'pending', we halt the authentication and we redirect the user to the appropriate url (pending=activation or pending=approval)
	if( $role == 'pending' ){
		// Bouncer::User Moderation : E-mail Confirmation
		if( $wsl_settings_bouncer_new_users_moderation_level == 101 ){
			$redirect_to = site_url( 'wp-login.php', 'login_post' ) . ( strpos( site_url( 'wp-login.php', 'login_post' ), '?' ) ? '&' : '?' ) . "pending=activation";

			// send a new e-mail/activation notification 
			@ Theme_My_Login_User_Moderation::new_user_activation_notification( $user_id );
		}

		// Bouncer::User Moderation : Admin Approval
		elseif( $wsl_settings_bouncer_new_users_moderation_level == 102 ){
			$redirect_to = site_url( 'wp-login.php', 'login_post' ) . ( strpos( site_url( 'wp-login.php', 'login_post' ), '?' ) ? '&' : '?' ) . "pending=approval";
		}
	}
	# }}} module Bouncer

	// otherwise, we connect the user with in wordpress (we give him a cookie)
	else{
		// HOOKABLE: custom actions to execute before logging the user in (before creating a WP cookie)
		do_action( "wsl_hook_process_login_before_set_auth_cookie", $user_id, $provider, $hybridauth_user_profile );

		# http://codex.wordpress.org/Function_Reference/wp_set_auth_cookie
		wp_set_auth_cookie( $user_id, true );
	}

	// HOOKABLE: custom actions to execute before redirecting the user back to $redirect_to  
	// > Note: If you enabled User Moderation, then the user is NOT NECESSARILY CONNECTED at this point (in case $role == 'pending'),
	// > and in this case, it is better to use 'wsl_hook_process_login_before_set_auth_cookie' instead, to be sure that
	// > the user is connected and not waiting for validation.
	do_action( "wsl_hook_process_login_before_redirect", $user_id, $provider, $hybridauth_user_profile );

	// That's it. We done.
	wp_safe_redirect( $redirect_to );

	// for good measures
	die(); 
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

	if( get_option( 'wsl_settings_force_redirect_url' ) == 1 ){
		return $wsl_settings_redirect_url;
	}

	// get a valid $redirect_to
	if ( isset( $_REQUEST[ 'redirect_to' ] ) && $_REQUEST[ 'redirect_to' ] != '' ){
		$redirect_to = $_REQUEST[ 'redirect_to' ];

		// Redirect to https if user wants ssl
		if ( isset( $secure_cookie ) && $secure_cookie && false !== strpos( $redirect_to, 'wp-admin') ){
			$redirect_to = preg_replace( '|^http://|', 'https://', $redirect_to );
		}

		// we don't go there..
		if ( strpos( $redirect_to, 'wp-admin') ){
			$redirect_to = $wsl_settings_redirect_url; 
		}

		// nor there..
		if ( strpos( $redirect_to, 'wp-login.php') ){
			$redirect_to = $wsl_settings_redirect_url; 
		}
	}

	if( empty( $redirect_to ) ){
		$redirect_to = $wsl_settings_redirect_url; 
	}

	if( empty( $redirect_to ) ){
		$redirect_to = site_url();
	}

	return $redirect_to;
}

// --------------------------------------------------------------------

/**
* Returns the selected provider from _REQUEST
*/
function wsl_process_login_get_selected_provider()
{
	return ( isset( $_REQUEST["provider"] ) ? sanitize_text_field( $_REQUEST["provider"] ) : null );
}

// --------------------------------------------------------------------

/**
* Redirection to providers for authentication : Loading screen
*/
function wsl_process_login_render_loading_page()
{
	$assets_base_url  = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . '/assets/img/'; 

	// selected provider 
	$provider = wsl_process_login_get_selected_provider();

	// erase HA session
	$_SESSION["HA::STORE"] = ARRAY();
?>
<!DOCTYPE html>
<head>
<meta name="robots" content="NOINDEX, NOFOLLOW">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 
<title><?php _wsl_e("Redirecting...", 'wordpress-social-login') ?> - <?php bloginfo('name'); ?></title>
<style type="text/css">
html {
	background: #f1f1f1;
}
body {
	background: #fff;
	color: #444;
	font-family: "Open Sans", sans-serif;
	margin: 2em auto;
	padding: 1em 2em;
	max-width: 700px;
	-webkit-box-shadow: 0 1px 3px rgba(0,0,0,0.13);
	box-shadow: 0 1px 3px rgba(0,0,0,0.13);
}
#loading-page {
	margin-top: 50px;
}
#loading-page div{
	line-height: 20px;
	padding: 8px;
	background-color: #f2f2f2;
	border: 1px solid #ccc;
	padding: 10px;
	text-align:center;
	box-shadow: 0 1px 3px rgba(0,0,0,0.13);
	margin-top:25px;
}
</style>
<script>
function init(){
	setTimeout( function(){window.location.href = window.location.href + "&redirect_to_provider=true"}, 250 );
}
</script>
</head>  
<body id="loading-page" onload="init();"> 
	<table width="100%" border="0">
		<tr>
			<td align="center"><img src="<?php echo $assets_base_url ?>/loading.gif" /></td>
		</tr>
		<tr>
			<td align="center">
				<div>
					<?php echo sprintf( _wsl__( "Contacting <b>%s</b>, please wait...", 'wordpress-social-login'), ucfirst( $provider ) )  ?>
				</div>
			</td> 
		</tr> 
	</table>   
</body>
</html> 
<?php
	die();
}

// --------------------------------------------------------------------

/**
* Display an error message in case authentication fails
*/
function wsl_process_login_render_error_page( $e, $config, $hybridauth, $provider, $adapter )
{
	$assets_base_url  = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . '/assets/img/'; 

	$message = _wsl__("Unspecified error!", 'wordpress-social-login');

	$hint    = ""; 

	switch( $e->getCode() ){
		case 0 : $message = _wsl__("Unspecified error.", 'wordpress-social-login'); break;
		case 1 : $message = _wsl__("WordPress Social Login is not properly configured.", 'wordpress-social-login'); break;
		case 2 : $message = sprintf( __wsl__("WordPress Social Login is not properly configured.<br /> <b>%s</b> need to be properly configured.", 'wordpress-social-login'), $provider ); break;
		case 3 : $message = _wsl__("Unknown or disabled provider.", 'wordpress-social-login'); break;
		case 4 : $message = sprintf( _wsl__("WordPress Social Login is not properly configured.<br /> <b>%s</b> requires your application credentials.", 'wordpress-social-login'), $provider ); 
				 $hint    = sprintf( _wsl__("<b>What does this error mean ?</b><br />Most likely, you didn't setup the correct application credentials for this provider. These credentials are required in order for <b>%s</b> users to access your website and for WordPress Social Login to work.", 'wordpress-social-login'), $provider ) . _wsl__('<br />Instructions for use can be found in the <a href="http://hybridauth.sourceforge.net/wsl/configure.html" target="_blank">User Manual</a>.', 'wordpress-social-login'); 
				 break;
		case 5 : $message = sprintf( _wsl__("Authentication failed. Either you have cancelled the authentication or <b>%s</b> refused the connection.", 'wordpress-social-login'), $provider ); break; 
		case 6 : $message = sprintf( _wsl__("Request failed. Either you have cancelled the authentication or <b>%s</b> refused the connection.", 'wordpress-social-login'), $provider ); break;
		case 7 : $message = _wsl__("You're not connected to the provider.", 'wordpress-social-login'); break;
		case 8 : $message = _wsl__("Provider does not support this feature.", 'wordpress-social-login'); break;

		case 9 : $message = $e->getMessage(); break;
	}

	if( is_object( $adapter ) ){
		$adapter->logout();
	}

	@ session_destroy();
?>
<!DOCTYPE html>
<head>
<meta name="robots" content="NOINDEX, NOFOLLOW">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php bloginfo('name'); ?> - <?php _wsl_e("Oops! We ran into an issue", 'wordpress-social-login') ?>.</title>
<style type="text/css">
html {
	background: #f1f1f1;
}
body {
	background: #fff;
	color: #444;
	font-family: "Open Sans", sans-serif;
	margin: 2em auto;
	padding: 1em 2em;
	max-width: 700px;
	-webkit-box-shadow: 0 1px 3px rgba(0,0,0,0.13);
	box-shadow: 0 1px 3px rgba(0,0,0,0.13);
}
#error-page {
	margin-top: 50px;
}
#error-page p {
	font-size: 14px;
	line-height: 1.5;
	margin: 25px 0 20px;
	line-height: 25px; 
	padding: 10px;
	text-align:left;
}
#error-page pre {
	max-width: 680px;
	overflow: scroll;
	padding: 5px;
	background: none repeat scroll 0 0 #F5F5F5;
	border-radius:3px;
	font-family: Consolas, Monaco, monospace;
}
a {
	color: #21759B;
	text-decoration: none;
}
a:hover {
	color: #D54E21;
}
</style>
</head>  
<body id="error-page"> 
	<table width="100%" border="0">
		<tr>
			<td align="center"><img src="<?php echo $assets_base_url ?>alert.png" /></td>
		</tr>
		<tr>
			<td align="center"><h4><?php _wsl_e("Oops! We ran into an issue", 'wordpress-social-login') ?>.</h4></td> 
		</tr>
		<tr>
			<td>
				<div style="line-height: 20px;padding: 8px;background-color: #f2f2f2;border: 1px solid #ccc;padding: 10px;text-align:center;box-shadow: 0 1px 3px rgba(0,0,0,0.13);">
					<?php echo $message ; ?> 
				</div>
			</td> 
		</tr> 

		<?php if( $hint ) { ?>
		  <tr>
			<td>
				<p>
					<?php echo $hint ; ?>
				</p>
			</td> 
		  </tr> 
		<?php } ?>

		<?php 
			// Development mode on?
			if( get_option( 'wsl_settings_development_mode_enabled' ) ){
				wsl_process_login_render_debug_section( $e, $config, $hybridauth, $adapter );
			}
		?>
	</table>
</body>
</html> 
<?php
	die();
}

// --------------------------------------------------------------------

/**
* Display the debug section in case Mode Dev is on
*/
function wsl_process_login_render_debug_section( $e, $config, $hybridauth, $adapter )
{
	global $wpdb;
?>
<tr>
    <td align="center"> 
		<div style="margin: 5px;"> 
			<table width="90%" border="0">
				<tr>
					<td align="left" valign="top"> 
						<h3>Backtrace</h3>
						<pre><?php debug_print_backtrace(); ?></pre>

						<h3>HybridAuth Exception</h3>
						<pre><?php print_r( $e ) ?></pre> 

						<?php
							// try to provide the previous if any
							// Exception::getPrevious (PHP 5 >= 5.3.0) http://php.net/manual/en/exception.getprevious.php
							if ( version_compare( PHP_VERSION, '5.3.0', '>=' ) ) { 
								if ( $e->getPrevious() ) {
								?>
									<h3>HybridAuth - Previous Exception</h3>
									<pre><?php print_r( $e->getPrevious() ) ?></pre> 
								<?php
								}
							}						
						?>

						<h3>HybridAuth Class</h3>
						<pre><?php print_r( array( $config, $hybridauth, $adapter  ) ) ?></pre>

						<h3>$wpdb</h3>
						<pre><?php print_r( $wpdb ) ?></pre>
					</td> 
				</tr>  
			</table>

			<br />

			<small>
				<?php _wsl_e("<strong>Note:</strong> This debugging area can be disabled from the plugin settings by setting <b>Development mode</b> to <b>Disabled</b>.", 'wordpress-social-login'); ?>.
			</small>
		</div> 
	</td> 
</tr>
<?php
}

// --------------------------------------------------------------------
