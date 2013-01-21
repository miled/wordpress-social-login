<?php
function wsl_process_login()
{
	if( ! isset( $_REQUEST[ 'action' ] ) ){
		return null;
	}

	if( $_REQUEST[ 'action' ] !=  "wordpress_social_login" && $_REQUEST[ 'action' ] !=  "wordpress_social_link" ){
		return null;
	}

	// dont be silly
	if(  $_REQUEST[ 'action' ] ==  "wordpress_social_link" && ! is_user_logged_in() ){
		return wsl_render_notices_pages( "Bouncer say don't be silly!" );
	}

	if(  $_REQUEST[ 'action' ] ==  "wordpress_social_link" && get_option( 'wsl_settings_bouncer_linking_accounts_enabled' ) != 1 ){
		return wsl_render_notices_pages( "Bouncer say this makes no sense." );
	}

	// Bouncer :: Allow authentication 
	if( get_option( 'wsl_settings_bouncer_authentication_enabled' ) == 2 ){
		return wsl_render_notices_pages( "WSL is disabled!" ); 
	}

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
	// print_r( get_option( 'wsl_settings_redirect_url' ) );
	// print_r( $_REQUEST[ 'redirect_to' ] );
 
// die( " ==> $redirect_to" );
	try{
		# Hybrid_Auth already used?
		if ( class_exists('Hybrid_Auth', false) ) {
			return wsl_render_notices_pages( "Error: Another plugin seems to be using HybridAuth Library and made WordPress Social Login unusable. We recommand to find this plugin and to kill it with fire!" ); 
		} 

		// load hybridauth
		require_once( dirname(__FILE__) . "/../../hybridauth/Hybrid/Auth.php" );

		// selected provider name 
		$provider = @ trim( strip_tags( $_REQUEST["provider"] ) );

		// build required configuratoin for this provider
		if( ! get_option( 'wsl_settings_' . $provider . '_enabled' ) ){
			throw new Exception( 'Unknown or disabled provider' );
		}

		$config = array();
		$config["base_url"]  = plugins_url() . '/' . basename( dirname( __FILE__ ) ) . '/hybridauth/';
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

					// launch contact import if enabled
					wsl_import_user_contacts( $provider, $adapter, $user_id );

					// store user hybridauth user profile if needed
					wsl_store_hybridauth_user_data( $user_id, $provider, $hybridauth_user_profile );

					wp_safe_redirect( $redirect_to );

					exit();
				} 

				// check if connected user is linked account
				$linked_account = wsl_get_user_linked_account_by_provider_and_identifier( $provider, $hybridauth_user_profile->identifier );

				// if linked account found, we connect the actual user 
				if( $linked_account ){
					if( count( $linked_account ) > 1 ){
						return wsl_render_notices_pages( "This $provider is linked to many accounts!" );
					}

					$user_id = $linked_account[0]->user_id;

					if( ! $user_id ){
						return wsl_render_notices_pages( "Something wrong!" );
					}

					wp_set_auth_cookie( $user_id );

					wp_safe_redirect( $redirect_to );

					exit();
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
					return wsl_render_notices_pages( "registration is now closed!" ); 
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

		$user_email = $hybridauth_user_profile->email; 
	}
	catch( Exception $e ){
		die( "Unspecified error. #" . $e->getCode() ); 
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

	// if user found
	if( $user_id ){
		$user_data  = get_userdata( $user_id );
		$user_login = $user_data->user_login; 
	} 

	// Create new user and associate provider identity
	else{
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

		// Bouncer :: Passcode
		if( get_option( 'wsl_settings_bouncer_new_users_passcode_enabled' ) == 1 ){ 
			# toDo
		}

		// Bouncer :: Registration agreement
		if( get_option( 'wsl_settings_bouncer_agreement_text_submit_button' ) == 1 ){ 
			# toDo
		}

		// HOOKABLE: change the user data
		if( apply_filters( 'wsl_hook_process_login_userdata', $userdata ) ){
			$userdata = apply_filters( 'wsl_hook_process_login_userdata', $userdata );
		}

		// HOOKABLE: delegate user insert
		$user_id = apply_filters( 'wsl_hook_process_login_insert_user', $userdata );

		// Create a new user
		if( ! $user_id || ! is_integer( $user_id ) ){
			$user_id = wp_insert_user( $userdata );
		}

		// update user metadata
		if( $user_id && is_integer( $user_id ) ){
			update_user_meta( $user_id, $provider, $hybridauth_user_profile->identifier );
		}
		else if (is_wp_error($user_id)) { //- http://wordpress.org/support/topic/plugin-wordpress-social-login-error-with-vkontake-provider?replies=1#post-2796109
			echo $user_id->get_error_message();
		}
		else{
			die( "An error occurred while creating a new user!" );
		}

		// Send notifications 
		if ( get_option( 'wsl_settings_users_notification' ) == 1 ){
			wsl_admin_notification( $user_id, $provider );
		}
	}

	$user_age = $hybridauth_user_profile->age;

	// not that precise you say... well welcome to my world
	if( ! $user_age && (int) $hybridauth_user_profile->birthYear ){
		$user_age = (int) date("Y") - (int) $hybridauth_user_profile->birthYear;
	}

	update_user_meta ( $user_id, 'wsl_user'       , $provider );
	update_user_meta ( $user_id, 'wsl_user_gender', $hybridauth_user_profile->gender );
	update_user_meta ( $user_id, 'wsl_user_age'   , $user_age );
	update_user_meta ( $user_id, 'wsl_user_image' , $hybridauth_user_profile->photoURL );

	// launch contact import if enabled
	wsl_import_user_contacts( $provider, $adapter, $user_id );

	// store user hybridauth user profile if needed
	wsl_store_hybridauth_user_data( $user_id, $provider, $hybridauth_user_profile );

	// HOOKABLE: any last words?
	apply_filters( 'wsl_hook_process_login_success', $user_id );

	wp_set_auth_cookie( $user_id );

	wp_safe_redirect( $redirect_to );

	exit();
}

add_action( 'init', 'wsl_process_login' );

function wsl_get_user_by_meta( $provider, $user_uid )
{
	global $wpdb;

	$sql = "SELECT user_id FROM `{$wpdb->prefix}usermeta` WHERE meta_key = '%s' AND meta_value = '%s'";

	return $wpdb->get_var( $wpdb->prepare( $sql, $provider, $user_uid ) );
}

function wsl_get_user_data_by_id( $field, $user_id )
{
	global $wpdb;

	$sql = "SELECT %s FROM `{$wpdb->prefix}users` WHERE ID = '%s'";

	return $wpdb->get_var( $wpdb->prepare( $sql, $field, $user_id ) );
}

function wsl_get_user_linked_account_by_provider_and_identifier( $provider, $identifier )
{
	global $wpdb;

	$sql = "SELECT * FROM `{$wpdb->prefix}wslusersprofiles` where provider = '$provider' and identifier = '$identifier'";
	$rs  = $wpdb->get_results( $sql );

	return $rs;
}

function wsl_store_hybridauth_user_data( $user_id, $provider, $profile )
{
	global $wpdb;

	$sql = "SELECT id, object_sha FROM `{$wpdb->prefix}wslusersprofiles` where user_id = '$user_id' and provider = '$provider'";
	$rs  = $wpdb->get_results( $sql );

	$profile_sha = sha1( serialize( $profile ) );

	// if $profile didnt change, no need for update
	if( $rs && $rs[0]->object_sha == $profile_sha ){
		return;
	}

	$table_data = array(
		"user_id"    => $user_id,
		"provider"   => $provider,
		"object_sha" => $profile_sha
	);

	foreach( $profile as $key => $value ) {
		$table_data[ strtolower($key) ] = (string) $value;
	}

	// if $profile updated we re/store on database
	if( $rs && $rs[0]->object_sha != $profile_sha ){
		$wpdb->update( "{$wpdb->prefix}wslusersprofiles", $table_data, array( "id" => $rs[0]->id ) ); 
	}
	else{
		$wpdb->insert( "{$wpdb->prefix}wslusersprofiles", $table_data ); 
	}
}

function wsl_import_user_contacts( $provider, $adapter, $user_id )
{
	// Contacts import
	if(
		get_option( 'wsl_settings_contacts_import_facebook' ) == 1 && strtolower( $provider ) == "facebook" ||
		get_option( 'wsl_settings_contacts_import_google' )   == 1 && strtolower( $provider ) == "google"   ||
		get_option( 'wsl_settings_contacts_import_twitter' )  == 1 && strtolower( $provider ) == "twitter"  ||
		get_option( 'wsl_settings_contacts_import_live' )     == 1 && strtolower( $provider ) == "live"     ||
		get_option( 'wsl_settings_contacts_import_linkedin' ) == 1 && strtolower( $provider ) == "linkedin" 
	){
		global $wpdb;

		// grab the user's friends list
		$user_contacts = $adapter->getUserContacts();

		// update contact only one time per provider, this behaviour may change depend on the feedback reviced
		if( $user_contacts ){
			$sq = "SELECT id FROM `{$wpdb->prefix}wsluserscontacts` where user_id = '$user_id' and provider = '$provider' limit 1";
			$rs = $wpdb->get_results( $sq );

			if( ! $rs ){
				foreach( $user_contacts as $contact ){
					$wpdb->insert(
						"{$wpdb->prefix}wsluserscontacts", 
							array( 
								"user_id" 		=> $user_id,
								"provider" 		=> $provider,
								"identifier" 	=> $contact->identifier,
								"full_name" 	=> $contact->displayName,
								"email" 		=> $contact->email,
								"profile_url" 	=> $contact->profileURL,
								"photo_url" 	=> $contact->photoURL,
							)
						); 
				}
			}
		}
	} 
}
