<?php
/*!
* HybridAuth
* http://hybridauth.sourceforge.net | https://github.com/hybridauth/hybridauth
*  (c) 2009-2014 HybridAuth authors | hybridauth.sourceforge.net/licenses.html
*/

/**
 * Hybrid_Providers_Reddit
 */
class Hybrid_Providers_Reddit extends Hybrid_Provider_Model_OAuth2
{ 
	// default permissions 
	//full list at http://www.reddit.com/dev/api/oauth
	public $scope = "identity";

	/**
	* IDp wrappers initializer 
	*/
	function initialize() 
	{
		parent::initialize();
		
		// Provider api end-points
		$this->api->api_base_url  = "http://www.reddit.com/api/v1/";
		$this->api->authorize_url = "https://ssl.reddit.com/api/v1/authorize";	   
		$this->api->token_url     = "https://ssl.reddit.com/api/v1/access_token";
	}

	/**
	* begin login step 
	*/
	function loginBegin()
	{
		// redirect the user to the provider authentication url
		Hybrid_Auth::redirect( $this->api->authorizeUrl( array( "scope" => $this->scope, "duration" => "temporary", "state" => str_shuffle("abcdefghijkl123456789") ) ) ); 

	}
	
	/**
	* finish login step 
	*/
	function loginFinish()
	{
		$error = (array_key_exists('error',$_REQUEST))?$_REQUEST['error']:"";

		// check for errors
		if ( $error ){ 
			throw new Exception( "Authentication failed! {$this->providerId} returned an error: " . htmlentities( $error ), 5 );
		}

		// try to authenticate user
		$code = (array_key_exists('code',$_REQUEST))?$_REQUEST['code']:"";

		try{
			$this->authenticate( $code ); 
		}
		catch( Exception $e ){
			throw new Exception( "Authentication failed! {$this->providerId} returned an error", 5 );
		}

		// check if authenticated
		if ( ! $this->api->access_token ){ 
			throw new Exception( "Authentication failed! {$this->providerId} returned an invalid access_token", 5 );
		}

		// store tokens
		$this->token( "access_token" , $this->api->access_token  );
		$this->token( "refresh_token", $this->api->refresh_token );
		$this->token( "expires_in"   , $this->api->access_token_expires_in );
		$this->token( "expires_at"   , $this->api->access_token_expires_at );

		// set user connected locally
		$this->setUserConnected();
	}

	function authenticate( $code )
	{
		$params = array(
			"client_id"     => $this->api->client_id, 
			"grant_type"    => "authorization_code",
			"redirect_uri"  => $this->api->redirect_uri,
			"code"          => $code
		);

		$http_headers = array();
		$http_headers['Authorization'] = 'Basic ' . base64_encode( $this->api->client_id .  ':' . $this->api->client_secret);
		
		$response = $this->request( $this->api->token_url, http_build_query($params, '', '&'), 'POST', $http_headers );
		
		$response = $this->parseRequestResult( $response );

		if( ! $response || ! isset( $response->access_token ) ){
			throw new Exception( "The Authorization Service has return: " . $response->error );
		}

		if( isset( $response->access_token  ) ) $this->api->access_token            = $response->access_token;
		if( isset( $response->refresh_token ) ) $this->api->refresh_token           = $response->refresh_token; 
		if( isset( $response->expires_in    ) ) $this->api->access_token_expires_in = $response->expires_in; 
		
		// calculate when the access token expire 
		if( isset( $response->expires_in ) ) {
			$this->api->access_token_expires_at = time() + $response->expires_in; 
		}
		else {
		    $this->api->access_token_expires_at = time() + 3600; 
		}

		return $response;  
	}

	private function request( $url, $params = array(), $type="GET", $http_headers = null )
	{
		if( $type == "GET" ){
			$url = $url . ( strpos( $url, '?' ) ? '&' : '?' ) . http_build_query($params, '', '&');
		}

		$this->http_info = array();
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL            , $url );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER , 1 );
		curl_setopt($ch, CURLOPT_TIMEOUT        , $this->api->curl_time_out );
		curl_setopt($ch, CURLOPT_USERAGENT      , $this->api->curl_useragent );
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , $this->api->curl_connect_time_out );
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER , $this->api->curl_ssl_verifypeer );
		curl_setopt($ch, CURLOPT_HTTPHEADER     , $this->api->curl_header );

        if (is_array($http_headers)) {
            $header = array();
            foreach($http_headers as $key => $parsed_urlvalue) {
                $header[] = "$key: $parsed_urlvalue";
            }

			curl_setopt($ch, CURLOPT_HTTPHEADER, $header );
        }
		else{
			curl_setopt($ch, CURLOPT_HTTPHEADER, $this->api->curl_header );
		}
		
		if($this->api->curl_proxy){
			curl_setopt( $ch, CURLOPT_PROXY        , $this->api->curl_proxy);
		}

		if( $type == "POST" ){
			curl_setopt($ch, CURLOPT_POST, 1); 
			if($params) curl_setopt( $ch, CURLOPT_POSTFIELDS, $params );
		}

		$response = curl_exec($ch);

		$this->http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$this->http_info = array_merge($this->http_info, curl_getinfo($ch));

		curl_close ($ch);

		return $response; 
	}

	private function parseRequestResult( $result )
	{
		if( json_decode( $result ) ) return json_decode( $result );

		parse_str( $result, $output );

		$result = new StdClass();

		foreach( $output as $k => $v )
			$result->$k = $v;

		return $result;
	}
  	
	/**
	* load the user profile from the IDp api client
	*
	* https://github.com/reddit/reddit/wiki/OAuth2
	* https://github.com/adoy/PHP-OAuth2/blob/master/src/OAuth2/Client.php#L315
	*/
	function getUserProfile()
	{ 
		$http_headers = array();
		$http_headers['Authorization'] = 'Bearer ' . $this->api->access_token;
		
		$response = $this->request( "https://oauth.reddit.com/api/v1/me.json?access_token=" . $this->api->access_token, array(), 'GET', $http_headers ); 
		
		$response = $this->parseRequestResult( $response );

		if ( ! $response || ! isset( $response->name ) ){
			throw new Exception( "User profile request failed! {$this->providerId} returned an invalid response.", 6 );
		}

		$this->user->profile->identifier  = @ $response->name; 
		$this->user->profile->displayName = @ $response->name; 
		$this->user->profile->profileURL  = "https://www.reddit.com/user/" . $this->user->profile->identifier . "/";  

		if( $this->user->profile->identifier ){
			return $this->user->profile;
		}
	}
}
