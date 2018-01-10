<?php
/*!
* HybridAuth
* http://hybridauth.sourceforge.net | https://Steemconnect.com/hybridauth/hybridauth
*  (c) 2009-2018 HybridAuth authors | hybridauth.sourceforge.net/licenses.html
*/

/**
 * Hybrid_Providers_Steemconnect
 */
class Hybrid_Providers_Steemconnect extends Hybrid_Provider_Model_OAuth2
{
	// default permissions
	// (no scope) => public read-only access (includes public user profile info, public repo info, and gists).
	public $scope = "login";

	/**
	* IDp wrappers initializer
	*/
	function initialize()
	{
		parent::initialize();

		// Provider api end-points
		$this->api->api_base_url  = "https://v2.steemconnect.com/api/";
		$this->api->authorize_url = "https://v2.steemconnect.com/oauth2/authorize";
		$this->api->token_url     = "https://v2.steemconnect.com/api/oauth2/token";

		$this->api->curl_authenticate_method  = "GET";
	}

	/**
	* load the user profile from the IDp api client
	*/
	function getUserProfile()
	{
		$data = $this->api->api( "me" );

		if ( ! isset( $data->_id ) ){
			throw new Exception( "User profile request failed! {$this->providerId} returned an invalid response.", 6 );
		}

    $profile = json_decode($data->account->json_metadata, true)['profile'];

		$this->user->profile->identifier  = $data->_id;
		$this->user->profile->displayName = isset($profile['name']) ? $profile['name'] : '';
		$this->user->profile->description = isset($profile['about']) ? $profile['about'] : '';
		$this->user->profile->photoURL    = isset($profile['profile_image']) ? $profile['profile_image'] : '';
		$this->user->profile->profileURL  = 'https://steemit.com/@' . $data->_id;
		// Steemconnect does not provide any email. Temporarly setting a fake...
		$this->user->profile->email       = $data->_id . '@fake-steemconnect-email.com';
		$this->user->profile->webSiteURL  = isset($profile['website']) ? $profile['website'] : '';
		$this->user->profile->region      = isset($profile['location']) ? $profile['location'] : '';

		return $this->user->profile;
	}
}
