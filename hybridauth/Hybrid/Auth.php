<?php
/**
* HybridAuth
* http://hybridauth.sourceforge.net | http://github.com/hybridauth/hybridauth
* (c) 2009-2014, HybridAuth authors | http://hybridauth.sourceforge.net/licenses.html
*/

/**
 * Hybrid_Auth class
 * 
 * Hybrid_Auth class provide a simple way to authenticate users via OpenID and OAuth.
 * 
 * Generally, Hybrid_Auth is the only class you should instantiate and use throughout your application.
 */
class Hybrid_Auth 
{
	public static $version = "2.4.1-wsl-fork";

	public static $config  = array();

	public static $store   = NULL;

	public static $error   = NULL;

	// --------------------------------------------------------------------

	/**
	* Try to start a new session of none then initialize Hybrid_Auth
	* 
	* Hybrid_Auth constructor will require either a valid config array or
	* a path for a configuration file as parameter. To know more please 
	* refer to the Configuration section:
	* http://hybridauth.sourceforge.net/userguide/Configuration.html
	*/
	function __construct( $config )
	{ 
		Hybrid_Auth::initialize( $config ); 
	}

	// --------------------------------------------------------------------

	/**
	* Try to initialize Hybrid_Auth with given $config hash or file
	*/
	public static function initialize( $config )
	{
		if( ! is_array( $config ) && ! file_exists( $config ) ){
			throw new Exception( "Hybriauth config does not exist on the given path.", 1 );
		}

		if( ! is_array( $config ) ){
			$config = include $config;
		}

		// build some need'd paths
		$path_base = realpath( dirname( __FILE__ ) )  . "/"; 

		# load hybridauth required files
		require_once $path_base . "Error.php";
		require_once $path_base . "Exception.php";

		require_once $path_base . "Provider_Adapter.php";

		require_once $path_base . "Provider_Model.php";
		require_once $path_base . "Provider_Model_OpenID.php";
		require_once $path_base . "Provider_Model_OAuth1.php";
		require_once $path_base . "Provider_Model_OAuth2.php";

		require_once $path_base . "User.php";
		require_once $path_base . "User_Profile.php";
		require_once $path_base . "User_Contact.php";
		require_once $path_base . "User_Activity.php";

		require_once $path_base . "Storage.php";

		// hash given config
		Hybrid_Auth::$config = $config;

		// instance of errors mng
		Hybrid_Auth::$error = new Hybrid_Error();

		// start session storage mng
		Hybrid_Auth::$store = new Hybrid_Storage();

		if( Hybrid_Error::hasError() ){
			$m = Hybrid_Error::getErrorMessage();
			$c = Hybrid_Error::getErrorCode();

			Hybrid_Error::clearError();

			throw new Exception( $m, $c );
		}

		// Endof initialize 
	}

	// --------------------------------------------------------------------

	/**
	* Hybrid storage system accessors
	*
	* Users sessions are stored using HybridAuth storage system ( HybridAuth 2.0 handle PHP Session only) and can be accessed directly by
	* Hybrid_Auth::storage()->get($key) to retrieves the data for the given key, or calling
	* Hybrid_Auth::storage()->set($key, $value) to store the key => $value set.
	*/
	public static function storage()
	{
		return Hybrid_Auth::$store;
	}

	// --------------------------------------------------------------------

	/**
	* Get hybridauth session data. 
	*/
	function getSessionData()
	{ 
		return Hybrid_Auth::storage()->getSessionData();
	}

	// --------------------------------------------------------------------

	/**
	* restore hybridauth session data. 
	*/
	function restoreSessionData( $sessiondata = NULL )
	{ 
		Hybrid_Auth::storage()->restoreSessionData( $sessiondata );
	}

	// --------------------------------------------------------------------

	/**
	* Try to authenticate the user with a given provider. 
	*
	* If the user is already connected we just return and instance of provider adapter,
	* ELSE, try to authenticate and authorize the user with the provider. 
	*
	* $params is generally an array with required info in order for this provider and HybridAuth to work,
	*  like :
	*          hauth_return_to: URL to call back after authentication is done
	*        openid_identifier: The OpenID identity provider identifier
	*           google_service: can be "Users" for Google user accounts service or "Apps" for Google hosted Apps
	*/
	public static function authenticate( $providerId, $params = NULL )
	{
		// if user not connected to $providerId then try setup a new adapter and start the login process for this provider
		if( ! Hybrid_Auth::storage()->get( "hauth_session.$providerId.is_logged_in" ) ){
			$provider_adapter = Hybrid_Auth::setup( $providerId, $params );

			$provider_adapter->login();
		}

		// else, then return the adapter instance for the given provider
		else{
			return Hybrid_Auth::getAdapter( $providerId );
		}
	}

	// --------------------------------------------------------------------

	/**
	* Return the adapter instance for an authenticated provider
	*/ 
	public static function getAdapter( $providerId = NULL )
	{
		if( ! Hybrid_Auth::$store ) {
			require_once realpath( dirname( __FILE__ ) )  . "/Storage.php";

			Hybrid_Auth::$store = new Hybrid_Storage();
		}

		if( ! Hybrid_Auth::$config ) {
			Hybrid_Auth::initialize( Hybrid_Auth::storage()->config( "CONFIG" ) );
		}

		return Hybrid_Auth::setup( $providerId );
	}

	// --------------------------------------------------------------------

	/**
	* Return the latest api error
	*/ 
	public static function getLatestApiError()
	{
		return Hybrid_Error::getErrorMessage();
	}

	// --------------------------------------------------------------------

	/**
	* Setup an adapter for a given provider
	*/ 
	public static function setup( $providerId, $params = NULL )
	{
		if( ! $params ){ 
			$params = Hybrid_Auth::storage()->get( "hauth_session.$providerId.id_provider_params" );
		}

		if( ! $params ){ 
			$params = ARRAY();
		}

		if( is_array($params) && ! isset( $params["hauth_return_to"] ) ){
			$params["hauth_return_to"] = Hybrid_Auth::getCurrentUrl();
		}

		# instantiate a new IDProvider Adapter
		$provider = new Hybrid_Provider_Adapter();

		$provider->factory( $providerId, $params );

		return $provider;
	} 

	// --------------------------------------------------------------------

	/**
	* Check if the current user is connected to a given provider
	*/
	public static function isConnectedWith( $providerId )
	{
		return (bool) Hybrid_Auth::storage()->get( "hauth_session.{$providerId}.is_logged_in" );
	}

	// --------------------------------------------------------------------

	/**
	* Return array listing all authenticated providers
	*/ 
	public static function getConnectedProviders()
	{
		$idps = array();

		foreach( Hybrid_Auth::$config["providers"] as $idpid => $params ){
			if( Hybrid_Auth::isConnectedWith( $idpid ) ){
				$idps[] = $idpid;
			}
		}

		return $idps;
	}

	// --------------------------------------------------------------------

	/**
	* Return array listing all enabled providers as well as a flag if you are connected.
	*/ 
	public static function getProviders()
	{
		$idps = array();

		foreach( Hybrid_Auth::$config["providers"] as $idpid => $params ){
			if($params['enabled']) {
				$idps[$idpid] = array( 'connected' => false );

				if( Hybrid_Auth::isConnectedWith( $idpid ) ){
					$idps[$idpid]['connected'] = true;
				}
			}
		}

		return $idps;
	}

	// --------------------------------------------------------------------

	/**
	* A generic function to logout all connected provider at once 
	*/ 
	public static function logoutAllProviders()
	{
		$idps = Hybrid_Auth::getConnectedProviders();

		foreach( $idps as $idp ){
			$adapter = Hybrid_Auth::getAdapter( $idp );

			$adapter->logout();
		}
	}

	// --------------------------------------------------------------------

	/**
	* Utility function, redirect to a given URL with php header or using javascript location.href
	*/
	public static function redirect( $url, $mode = "PHP" )
	{
		if( $mode == "PHP" ){
			header( "Location: $url" ) ;
		}
		elseif( $mode == "JS" ){
			echo '<html>';
			echo '<head>';
			echo '<script type="text/javascript">';
			echo 'function redirect(){ window.top.location.href="' . $url . '"; }';
			echo '</script>';
			echo '</head>';
			echo '<body onload="redirect()">';
			echo 'Redirecting, please wait...';
			echo '</body>';
			echo '</html>'; 
		}

		die();
	}

	// --------------------------------------------------------------------

	/**
	* Utility function, return the current url. TRUE to get $_SERVER['REQUEST_URI'], FALSE for $_SERVER['PHP_SELF']
	*/
	public static function getCurrentUrl( $request_uri = true ) 
	{
		$wsl_is_https_on = false;

		if( ! empty ( $_SERVER ['SERVER_PORT'] ) )
		{
			if(trim ( $_SERVER ['SERVER_PORT'] ) == '443')
			{
				$wsl_is_https_on = true;
			}
		}

		if ( ! empty ( $_SERVER ['HTTP_X_FORWARDED_PROTO'] ) )
		{
			if(strtolower (trim ($_SERVER ['HTTP_X_FORWARDED_PROTO'])) == 'https')
			{
				$wsl_is_https_on = true;
			}
		}

		if( ! empty ( $_SERVER ['HTTPS'] ) )
		{
			if ( strtolower( trim($_SERVER ['HTTPS'] ) ) == 'on' OR trim ($_SERVER ['HTTPS']) == '1')
			{
				$wsl_is_https_on = true;
			}
		}

		//Extract parts
		$request_uri = (isset ($_SERVER ['REQUEST_URI']) ? $_SERVER ['REQUEST_URI'] : $_SERVER ['PHP_SELF']);
		$request_protocol = ( $wsl_is_https_on ? 'https' : 'http');
		$request_host = (isset ($_SERVER ['HTTP_X_FORWARDED_HOST']) ? $_SERVER ['HTTP_X_FORWARDED_HOST'] : (isset ($_SERVER ['HTTP_HOST']) ? $_SERVER ['HTTP_HOST'] : $_SERVER ['SERVER_NAME']));

		//Port of this request
		$request_port = '';

		//We are using a proxy
		if( isset( $_SERVER ['HTTP_X_FORWARDED_PORT'] ) )
		{
			// SERVER_PORT is usually wrong on proxies, don't use it!
			$request_port = intval($_SERVER ['HTTP_X_FORWARDED_PORT']);
		}
		//Does not seem like a proxy
		elseif( isset( $_SERVER ['SERVER_PORT'] ) )
		{
			$request_port = intval($_SERVER ['SERVER_PORT']);
		}

		//Remove standard ports
		$request_port = (!in_array($request_port, array (80, 443)) ? $request_port : '');

		//Build url
		$current_url = $request_protocol . '://' . $request_host . ( ! empty ($request_port) ? (':'.$request_port) : '');

		if( $request_uri )
		{
			$current_url .= $request_uri;
		}
		else
		{
			$current_url .= $_SERVER ['PHP_SELF'];
		}

		//Done
		return $current_url;
	}
}
