<?php
function wsl_process_login()
{
	if( ! isset( $_REQUEST[ 'action' ] ) || $_REQUEST[ 'action' ] !=  "wordpress_social_login" ){
		return;
	}

	if ( isset( $_REQUEST[ 'redirect_to' ] ) && $_REQUEST[ 'redirect_to' ] != '' ){
		$redirect_to = $_REQUEST[ 'redirect_to' ];

		// Redirect to https if user wants ssl
		if ( isset( $secure_cookie ) && $secure_cookie && false !== strpos( $redirect_to, 'wp-admin') ){
			$redirect_to = preg_replace( '|^http://|', 'https://', $redirect_to );
		}

		if ( strpos( $redirect_to, 'wp-admin') ){
			$redirect_to = get_option( 'wsl_settings_redirect_url' ); 
		}
	}

	if( empty( $redirect_to ) ){
		$redirect_to = get_option( 'wsl_settings_redirect_url' ); 
	}

	if( empty( $redirect_to ) ){
		$redirect_to = site_url();
	}

	try{
		// load hybridauth
		require_once( dirname(__FILE__) . "/../hybridauth/Hybrid/Auth.php" );

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
		// generate a valid user login
		$user_login = str_replace( ' ', '_', strtolower( $hybridauth_user_profile->displayName ) );

		if( ! validate_username( $user_login ) ){
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

		$userdata = array(
			'user_login'    => $user_login,
			'user_email'    => $user_email,

			'first_name'    => $hybridauth_user_profile->firstName,
			'last_name'     => $hybridauth_user_profile->lastName,
			'user_nicename' => $hybridauth_user_profile->displayName,
			'display_name'  => $hybridauth_user_profile->displayName,
			'user_url'      => $hybridauth_user_profile->profileURL,
			'description'   => $hybridauth_user_profile->description,

			'user_pass'     => wp_generate_password()
		);

		// Create a new user
		$user_id = wp_insert_user( $userdata );

		// update user metadata
		if( $user_id && is_integer( $user_id ) ){
			update_user_meta( $user_id, $provider, $hybridauth_user_profile->identifier ); 
		}
		else{
			die( "An error occurred while creating a new user!" );
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

	wp_set_auth_cookie( $user_id );

	wp_safe_redirect( $redirect_to );

	exit();
}

add_action( 'init', 'wsl_process_login' );

function wsl_get_user_by_meta( $provider, $user_uid )
{
	global $wpdb;

	$sql = "SELECT user_id FROM $wpdb->usermeta WHERE meta_key = '%s' AND meta_value = '%s'";

	return $wpdb->get_var( $wpdb->prepare( $sql, $provider, $user_uid ) );
}
