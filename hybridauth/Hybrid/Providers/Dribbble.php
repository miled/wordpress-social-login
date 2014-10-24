<?php
/*!
* HybridAuth
* http://hybridauth.sourceforge.net | https://github.com/hybridauth/hybridauth
*  (c) 2009-2011 HybridAuth authors | hybridauth.sourceforge.net/licenses.html
*/

/**
 * Hybrid_Providers_Dribbble
 */
class Hybrid_Providers_Dribbble extends Hybrid_Provider_Model_OAuth2
{ 
	// default permissions  
	// (no scope) => public read-only access (includes public user profile info, public repo info, and gists).
	public $scope = "";

	/**
	* IDp wrappers initializer 
	*/
	function initialize() 
	{
		parent::initialize();

		// Provider api end-points
		$this->api->api_base_url  = "https://api.dribbble.com/v1/";
		$this->api->authorize_url = "https://dribbble.com/oauth/authorize";
		$this->api->token_url     = "https://dribbble.com/oauth/token";
	}

	/**
	* load the user profile from the IDp api client
	*/
	function getUserProfile()
	{
		$data = $this->api->api( "user" ); 

		if ( ! isset( $data->id ) ){
			throw new Exception( "User profile request failed! {$this->providerId} returned an invalid response.", 6 );
		}

		$this->user->profile->identifier  = property_exists( $data,'id'         ) ? $data->id         : '';
		$this->user->profile->displayName = property_exists( $data,'name'       ) ? $data->name       : property_exists( $data,'username' ) ? $data->username : '';
		$this->user->profile->profileURL  = property_exists( $data,'html_url'   ) ? $data->html_url   : '';
		$this->user->profile->photoURL    = property_exists( $data,'avatar_url' ) ? $data->avatar_url : '';
		$this->user->profile->webSiteURL  = property_exists( $data,'links'      ) ? ( property_exists( $data->links, 'web' ) ? $data->links->web : '' ) : '';
		$this->user->profile->description = property_exists( $data,'bio'        ) ? $data->bio        : '';
		$this->user->profile->region      = property_exists( $data,'location'   ) ? $data->location   : '';

		return $this->user->profile;
	}
}
