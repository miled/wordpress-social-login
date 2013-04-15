<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*    (c) 2011-2013 Mohamed Mrassi and contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

/**
* .. Here's where the dragon resides ..
*
* Authenticate users via social networks. to sum things up, here is how stuff works:
*
*	[icons links]                                  A wild visitor appear and click on one of these providers icons which will redirect him to wp-login.php 
*		=> [wp-login.php]                          wp-login.php will call wsl_process_login() and attempt to authenticate the user throught Hybridauth Library;
*			=> [Hybridauth] <=> [Provider]         Hybridauth and the Provider will have some little chat on their own;
*				=> [Provider]                      If the visitor consent and agrees to authenticate, then horray for you;
*					=> [wp-login.php]              Provider will then redirect the user to back wp-login.php where wsl_process_login() is fired;
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

	if( $_REQUEST[ 'action' ] == "wordpress_social_authenticate" ){
		wsl_process_login_auth();
	}
	else{
		wsl_process_login_reauth();
	}
}

add_action( 'init', 'wsl_process_login' );

// --------------------------------------------------------------------

function wsl_process_login_checks()
{
	if( ! isset( $_REQUEST[ 'action' ] ) ){
		return false;
	}

	if( ! in_array( $_REQUEST[ 'action' ], array( "wordpress_social_login", "wordpress_social_link", "wordpress_social_authenticate" ) ) ){
		return false;
	}

	// dont be silly
	if(  $_REQUEST[ 'action' ] == "wordpress_social_link" && ! is_user_logged_in() ){
		wsl_render_notices_pages( _wsl__("Bouncer say don't be silly!", 'wordpress-social-login') );

		return false;
	}

	if(  $_REQUEST[ 'action' ] == "wordpress_social_link" && get_option( 'wsl_settings_bouncer_linking_accounts_enabled' ) != 1 ){
		wsl_render_notices_pages( _wsl__("Bouncer say this makes no sense.", 'wordpress-social-login') );
		
		return false;
	}

	// Bouncer :: Allow authentication 
	if( get_option( 'wsl_settings_bouncer_authentication_enabled' ) == 2 ){
		wsl_render_notices_pages( _wsl__("WSL is disabled!", 'wordpress-social-login') ); 
		
		return false;
	}

	return true;
}

// --------------------------------------------------------------------

function wsl_process_login_auth()
{
	$assets_base_url  = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . '/assets/img/'; 

	// let display a loading message. should be better than a white screen
	if( isset( $_REQUEST["provider"] ) && ! isset( $_REQUEST["redirect_to_provider"] ) ){
		wsl_process_login_render_loading_page();
	}

	// if user select a provider to login with 
	// and redirect_to_provider eq ture
	if( ! ( isset( $_REQUEST["provider"] ) && isset( $_REQUEST["redirect_to_provider"] ) ) ){ 
		wsl_render_notices_pages( _wsl__("Bouncer says this makes no sense.", 'wordpress-social-login') ); 

		return false;
	}

	try{
		// Bouncer :: Accounts Linking is enabled
		if( get_option( 'wsl_settings_bouncer_linking_accounts_enabled' ) != 1 && isset( $_REQUEST["link"] ) ){
			wp_die( _wsl__("Bouncer say you are doin it wrong.", 'wordpress-social-login') );
		}

		if( ! isset( $_REQUEST["link"] ) && is_user_logged_in() ){
			global $current_user;
			get_currentuserinfo(); 

			wp_die( sprintf( _wsl__("You are already logged in as <b>%</b>.", 'wordpress-social-login'), $current_user->display_name ) );
		}

		# Hybrid_Auth already used?
		if ( class_exists('Hybrid_Auth', false) ) {
			return wsl_render_notices_pages( _wsl__("Error: Another plugin seems to be using HybridAuth Library and made WordPress Social Login unusable. We recommand to find this plugin and to kill it with fire!", 'wordpress-social-login') ); 
		}

		// load hybridauth
		require_once WORDPRESS_SOCIAL_LOGIN_ABS_PATH . "/hybridauth/Hybrid/Auth.php";

		// selected provider name 
		$provider = @ trim( strip_tags( $_REQUEST["provider"] ) );

		// build required configuratoin for this provider
		if( ! get_option( 'wsl_settings_' . $provider . '_enabled' ) ){
			throw new Exception( _wsl__( 'Unknown or disabled provider', 'wordpress-social-login') );
		}

		// default endpoint_url/callback_url
		$endpoint_url = WORDPRESS_SOCIAL_LOGIN_HYBRIDAUTH_ENDPOINT_URL;
		$callback_url = null; // autogenerated by hybridauth

		// overwrite endpoint_url if need'd
		if( get_option( 'wsl_settings_base_url' ) ){
			$endpoint_url = ''; // fixme!
			$callback_url = ''; // fixme!
		}

		// check hybridauth_base_url
		if( ! strstr( $endpoint_url, "http://" ) && ! strstr( $endpoint_url, "https://" ) ){
			throw new Exception( 'Invalid base_url: ' . $endpoint_url, 9 );
		}

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

		// reset scope for if facebook
		if( strtolower( $provider ) == "facebook" ){
			$config["providers"][$provider]["scope"]   = "email, user_about_me, user_birthday, user_hometown, user_website"; 
			$config["providers"][$provider]["display"] = "popup";
		}

		// reset scope for if google
		if( strtolower( $provider ) == "google" ){
			$config["providers"][$provider]["scope"]   = "https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email";  
		}

		// Contacts import
		if( get_option( 'wsl_settings_contacts_import_facebook' ) == 1 && strtolower( $provider ) == "facebook" ){
			$config["providers"][$provider]["scope"]   = "email, user_about_me, user_birthday, user_hometown, user_website, read_friendlists";
		}

		if( get_option( 'wsl_settings_contacts_import_google' ) == 1 && strtolower( $provider ) == "google" ){
			$config["providers"][$provider]["scope"]   = "https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email https://www.google.com/m8/feeds/";
		}

		// create an instance for Hybridauth
		$hybridauth = new Hybrid_Auth( $config );

		// try to authenticate the selected $provider
		$params  = array();

		// if callback_url defined, overwrite Hybrid_Auth::getCurrentUrl(); 
		if( $callback_url ){
			$params["hauth_return_to"] = $callback_url;
		}

		$adapter = $hybridauth->authenticate( $provider, $params );

		// further testing
		if( get_option( 'wsl_settings_development_mode_enabled' ) ){
			$profile = $adapter->getUserProfile( $provider );
		}

		if( get_option( 'wsl_settings_use_popup' ) == 1 || ! get_option( 'wsl_settings_use_popup' ) ){
			?>
				<html><head><script>
				function init() {
					window.opener.wsl_wordpress_social_login({
						'action'   : 'wordpress_social_login',
						'provider' : '<?php echo $provider ?>'
					});

					window.close()
				}
				</script></head><body onload="init();"></body></html>
			<?php
		}
		elseif( get_option( 'wsl_settings_use_popup' ) == 2 ){
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
				<?php
					if( isset( $_REQUEST["link"] ) && (int) $_REQUEST["link"] ){
				?>
					<input type="hidden" id="action" name="action" value="wordpress_social_link">
				<?php
					} else {
				?>
					<input type="hidden" id="action" name="action" value="wordpress_social_login">
				<?php
					}
				?>
				</form></body></html> 
			<?php
		}
	}
	catch( Exception $e ){
		wsl_process_login_render_error_page( $e, $config, $hybridauth, $adapter, $profile );
	} 

	die();
}

// --------------------------------------------------------------------

function wsl_process_login_render_error_page( $e, $config, $hybridauth, $adapter, $profile )
{
	$assets_base_url  = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . '/assets/img/'; 

	$message = _wsl__("Unspecified error!", 'wordpress-social-login'); 
	$hint    = ""; 

	switch( $e->getCode() ){
		case 0 : $message = _wsl__("Unspecified error.", 'wordpress-social-login'); break;
		case 1 : $message = _wsl__("Hybriauth configuration error.", 'wordpress-social-login'); break;
		case 2 : $message = _wsl__("Provider not properly configured.", 'wordpress-social-login'); break;
		case 3 : $message = _wsl__("Unknown or disabled provider.", 'wordpress-social-login'); break;
		case 4 : $message = _wsl__("Missing provider application credentials.", 'wordpress-social-login'); 
				 $hint    = sprintf( _wsl__("<b>What does this error mean ?</b><br />Most likely, you didn't setup the correct application credentials for this provider. These credentials are required in order for <b>%s</b> users to access your website and for WordPress Social Login to work.", 'wordpress-social-login'), $provider ) . _wsl__('<br />Instructions for use can be found in the <a href="http://hybridauth.sourceforge.net/wsl/configure.html" target="_blank">User Manual</a>.', 'wordpress-social-login'); 
				 break;
		case 5 : $message = _wsl__("Authentification failed. The user has canceled the authentication or the provider refused the connection.", 'wordpress-social-login'); break; 
		case 6 : $message = _wsl__("User profile request failed. Most likely the user is not connected to the provider and he should to authenticate again.", 'wordpress-social-login'); 
				 if( is_object( $adapter ) ) $adapter->logout();
				 break;
		case 7 : $message = _wsl__("User not connected to the provider.", 'wordpress-social-login'); 
				 if( is_object( $adapter ) ) $adapter->logout();
				 break;
		case 8 : $message = _wsl__("Provider does not support this feature.", 'wordpress-social-login'); break;

		case 9 : $message = $e->getMessage(); break;
	}

	@ session_destroy();
?>
<!DOCTYPE html>
<head>
<meta name="robots" content="NOINDEX, NOFOLLOW">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title></title>
<style> 
HR {
	width:100%;
	border: 0;
	border-bottom: 1px solid #ccc; 
	padding: 50px;
}
html {
    background: #f9f9f9;
}
#wsl {
	background: #fff;
	color: #333;
	font-family: sans-serif;
	margin: 2em auto;
	padding: 1em 2em;
	-webkit-border-radius: 3px;
	border-radius: 3px;
	border: 1px solid #dfdfdf;
	max-width: 700px;
	font-size: 14px;
}  
</style>
<head>  
<body>
<div id="wsl">
<table width="100%" border="0">
	<tr>
	<td align="center"><br /><img src="<?php echo $assets_base_url ?>alert.png" /></td>
	</tr>
	<tr>
	<td align="center"><br /><h4><?php _wsl_e("Something bad happen!", 'wordpress-social-login') ?></h4><br /></td> 
	</tr>
	<tr>
	<td align="center">
		<p style="line-height: 20px;padding: 8px;background-color: #FFEBE8;border:1px solid #CC0000;border-radius: 3px;padding: 10px;text-align:center;">
			<?php echo $message ; ?> 
		</p>
	</td> 
	</tr> 

	<?php if( $hint ) { ?>
	  <tr>
		<td align="center">
			<p style="line-height: 25px;padding: 8px;border-top:1px solid #ccc;padding: 10px;text-align:left;"> 
				<?php echo $hint ; ?>
			</p>
		</td> 
	  </tr> 
	<?php } ?>
  
	<?php 
		// Development mode on?
		if( get_option( 'wsl_settings_development_mode_enabled' ) ){
			wsl_process_login_render_debug_section( $e, $config, $hybridauth, $adapter, $profile );
		}
	?>
</table>  
</div> 
</body>
</html> 
<?php
	die();
}

// --------------------------------------------------------------------

function wsl_process_login_render_debug_section( $e, $config, $hybridauth, $adapter, $profile )
{
?>
  <tr>
    <td align="center"> 
		<div style="padding: 5px;margin: 5px;background: none repeat scroll 0 0 #F5F5F5;border-radius:3px;">
			<div id="bug_report">
				<form method="post" action="http://hybridauth.sourceforge.net/reports/index.php?product=wp-plugin&v=<?php echo $_SESSION["wsl::plugin"] ?>">
					<table width="90%" border="0">
						<tr>
							<td align="left" valign="top"> 
								<h3>Expection</h3>
								<pre style="width:800px;"><?php print_r( $e ) ?></pre> 

								<hr />

								<h3>HybridAuth</h3>
								<pre style="width:800px;"><?php print_r( array( $config, $hybridauth, $adapter, $profile ) ) ?></pre>
							</td> 
						</tr> 
						<tr>
							<td align="center" valign="top"> 
								<input type="submit" style="width: 300px;height: 33px;" value="Send the error report to the developer" /> 
							</td> 
						</tr>
					</table> 

					<textarea name="report" style="display:none;"><?php echo base64_encode( print_r( array( $e, $config, $hybridauth, $adapter, $profile, $_SERVER ), TRUE ) ) ?></textarea>
				</form> 
				<small>
					<?php _wsl_e("Note: This message can be disabled from the plugin settings by setting <b>Development mode</b> to <b>Disabled</b>", 'wordpress-social-login') ?>.
				</small>
			</div>
		</div>
	</td> 
  </tr>
<?php
}

// --------------------------------------------------------------------

function wsl_process_login_render_loading_page()
{
	$assets_base_url  = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . '/assets/img/'; 

	// selected provider 
	$provider = @ trim( strip_tags( $_REQUEST["provider"] ) ); 

	if( isset( $_REQUEST["link"] ) && (int) $_REQUEST["link"] ){
		// todo
	}
	else{
		$_SESSION["HA::STORE"] = ARRAY();
	}
?>
<!DOCTYPE html>
<head>
<meta name="robots" content="NOINDEX, NOFOLLOW">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php _wsl_e("Redirecting...", 'wordpress-social-login') ?></title>
<head> 
<script>
function init(){
	setTimeout( function(){window.location.href = window.location.href + "&redirect_to_provider=true"}, 750 );
}
</script>
<style>
html {
    background: #f9f9f9;
}
#wsl {
	background: #fff;
	color: #333;
	font-family: sans-serif;
	margin: 2em auto;
	padding: 1em 2em;
	-webkit-border-radius: 3px;
	border-radius: 3px;
	border: 1px solid #dfdfdf;
	max-width: 700px;
	font-size: 14px;
}  
</style>
</head>
<body onload="init();">
<div id="wsl">
<table width="100%" border="0">
  <tr>
    <td align="center" height="40px"><br /><br /><?php echo sprintf( _wsl__( "Contacting <b>%s</b>, please wait...", 'wordpress-social-login'), ucfirst( $provider ) )  ?></td> 
  </tr> 
  <tr>
    <td align="center" height="80px" valign="middle"><img src="<?php echo $assets_base_url ?>loading.gif" /></td>
  </tr> 
</table> 
</div> 
</body>
</html> 
<?php
	die();
}

// --------------------------------------------------------------------

function wsl_process_login_reauth()
{
	// HOOKABLE: 
	do_action( "wsl_hook_process_login_before_start" );

	// HOOKABLE: 
	$redirect_to = apply_filters("wsl_hook_process_login_alter_redirect_to", wsl_process_login_get_redirect_to() ) ;

	// HOOKABLE: 
	$provider = apply_filters("wsl_hook_process_login_alter_provider", wsl_process_login_get_provider() ) ;

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

	// There was a bug when *_create_wp_user returned WP_Error, so just in case
	if( ! is_integer( $user_id ) ){
		return wsl_render_notices_pages( _wsl__("Invalid user_id returned by create_wp_user.", 'wordpress-social-login') );
	}

	// finally create a wp session for the user
	return wsl_process_login_authenticate_wp_user( $user_id, $provider, $redirect_to, $adapter, $hybridauth_user_profile );
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
			return wsl_render_notices_pages( _wsl__("Error: Another plugin seems to be using HybridAuth Library and made WordPress Social Login unusable. We recommand to find this plugin and to kill it with fire!", 'wordpress-social-login') ); 
		} 

		// load hybridauth
		require_once WORDPRESS_SOCIAL_LOGIN_ABS_PATH . "/hybridauth/Hybrid/Auth.php";

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
						return wsl_render_notices_pages( _wsl__("This $provider is linked to many accounts!", 'wordpress-social-login') );
					}

					$user_id = $linked_account[0]->user_id;

					if( ! $user_id ){
						return wsl_render_notices_pages( _wsl__("Something wrong!", 'wordpress-social-login') );
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
			throw new Exception( 'User not connected with ' . $provider . '!' );
		} 
	}
	catch( Exception $e ){ 
		return wsl_render_notices_pages( sprintf( _wsl__("Unspecified error. #%d", 'wordpress-social-login'), $e->getCode() ) ); 
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

	// Bouncer :: User Moderation : None
	if( get_option( 'wsl_settings_bouncer_new_users_moderation_level' ) == 1 ){ 
		// well do nothing..
	}

	// Bouncer :: User Moderation : Yield to Theme My Login plugin
	if( get_option( 'wsl_settings_bouncer_new_users_moderation_level' ) > 100 ){ 
		$userdata['role'] = "pending";
	}

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

	// do not continue without user_id or we'll edit god knows what
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

function wsl_process_login_authenticate_wp_user( $user_id, $provider, $redirect_to, $adapter, $hybridauth_user_profile )
{
	// There was a bug when this function received non-integer user_id and updated random users, let's be safe
	if( !is_integer( $user_id ) ){
		return wsl_render_notices_pages( _wsl__("Invalid user_id", 'wordpress-social-login') );
	}

	// calculate user age
	$user_age = $hybridauth_user_profile->age;

	// not that precise you say... well welcome to my world
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

	// launch contact import if enabled
	wsl_import_user_contacts( $provider, $adapter, $user_id );

	// store user hybridauth user profile if needed
	wsl_store_hybridauth_user_data( $user_id, $provider, $hybridauth_user_profile );

	// Bouncer :: User Moderation : E-mail Confirmation — Yield to Theme My Login plugin
	if( get_option( 'wsl_settings_bouncer_new_users_moderation_level' ) == 101 ){
		$redirect_to = site_url( 'wp-login.php', 'login_post' ) . ( strpos( site_url( 'wp-login.php', 'login_post' ), '?' ) ? '&' : '?' ) . "pending=activation";

		@ Theme_My_Login_User_Moderation::new_user_activation_notification( $user_id );
	}

	// Bouncer :: User Moderation : Admin Approval — Yield to Theme My Login plugin
	elseif( get_option( 'wsl_settings_bouncer_new_users_moderation_level' ) == 102 ){
		$redirect_to = site_url( 'wp-login.php', 'login_post' ) . ( strpos( site_url( 'wp-login.php', 'login_post' ), '?' ) ? '&' : '?' ) . "pending=approval";
	}
	
	// otherwise, let him go..
	else{
		// HOOKABLE: 
		do_action( "wsl_hook_process_login_before_set_auth_cookie", $user_id, $provider, $hybridauth_user_profile );

		// That's it. create a session for user_id and redirect him to redirect_to
		wp_set_auth_cookie( $user_id );
	}

	// HOOKABLE: 
	do_action( "wsl_hook_process_login_before_redirect", $user_id, $provider, $hybridauth_user_profile );

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
	do_action( "wsl_hook_process_login_linked_account_before_redirect", $user_id, $provider, $hybridauth_user_profile );

	wp_safe_redirect( $redirect_to );

	exit(); 
}

// --------------------------------------------------------------------
