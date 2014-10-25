<?php
/*!
* HybridAuth
* http://hybridauth.sourceforge.net | https://github.com/hybridauth/hybridauth
*  (c) 2009-2011 HybridAuth authors | hybridauth.sourceforge.net/licenses.html
*/

/**
 * Hybrid_Providers_WordPress 
 */
class Hybrid_Providers_WordPress extends Hybrid_Provider_Model_OAuth2
{ 
	// default permissions  
	public $scope = "";

	/**
	* IDp wrappers initializer 
	*/
	function initialize() 
	{
		parent::initialize();

		// Provider api end-points
		$this->api->api_base_url  = "https://public-api.wordpress.com/rest/v1/";
		$this->api->authorize_url = "https://public-api.wordpress.com/oauth2/authenticate";
		$this->api->token_url     = "https://public-api.wordpress.com/oauth2/token";
		
		if( $this->token( "access_token" ) )
		{
			$this->api->curl_header = array( 'Authorization: Bearer ' . $this->token( "access_token" ) );
		}
	}

	// --------------------------------------------------------------------

	/**
	* begin login step 
	*/
	function loginBegin()
	{
		// redirect the user to the provider authentication url
		Hybrid_Auth::redirect( $this->api->authorizeUrl( array( "scope" => $this->scope, "state" => md5( mt_rand() ) ) ) ); 
	}

	// --------------------------------------------------------------------

	/**
	* load the user profile from the IDp api client
	*/
	function getUserProfile()
	{
		$response = $this->api->api( "me/" ); 

		if ( ! isset( $response->ID ) ){ 
			throw new Exception( "User profile request failed! {$this->providerId} returned an invalid response.", 6 );
		}

		$this->user->profile->identifier  = (property_exists($response,'ID'))?$response->ID:""; 
		$this->user->profile->displayName = (property_exists($response,'display_name'))?$response->display_name:""; 
		$this->user->profile->photoURL    = (property_exists($response,'avatar_URL'))?$response->avatar_URL:"";
		$this->user->profile->profileURL  = (property_exists($response,'profile_URL'))?$response->profile_URL:"";
		$this->user->profile->email       = (property_exists($response,'email'))?$response->email:"";
		$this->user->profile->language    = (property_exists($response,'language'))?$response->language:"";

		if( ! $this->user->profile->displayName ){
			$this->user->profile->displayName = (property_exists($response,'username'))?$response->username:"";
		}

		if( property_exists($response,'email_verified') && property_exists($response,'email') && $response->email_verified == 1 ){
			$this->user->profile->emailVerified = $response->email;
		}

		if( property_exists($response,'primary_blog') ){
			$primary_blog = $response->primary_blog;

			$response = $this->api->api( 'sites/' . $primary_blog ); 

			if( isset( $response->URL ) ){
				$this->user->profile->webSiteURL = $response->URL;
			}
		}

		return $this->user->profile;
	}
}
