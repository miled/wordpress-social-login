<?php 
/*!
* HybridAuth
* http://hybridauth.sourceforge.net | http://github.com/hybridauth/hybridauth
* (c) 2009-2012, HybridAuth authors | http://hybridauth.sourceforge.net/licenses.html 
*/

/**
 * Hybrid_Providers_Steam provider adapter based on OpenID protocol
 *
 * http://hybridauth.sourceforge.net/userguide/IDProvider_info_Steam.html
 *
 * This class has been entirely reworked for the new Steam API (http://steamcommunity.com/dev)
 */
class Hybrid_Providers_Steam extends Hybrid_Provider_Model_OpenID
{
	var $openidIdentifier = "http://steamcommunity.com/openid";

	/**
	* finish login step 
	*/
	function loginFinish()
	{
		parent::loginFinish();

		$this->user->profile->identifier = str_ireplace( "http://steamcommunity.com/openid/id/", "", $this->user->profile->identifier );

		if( ! $this->user->profile->identifier )
		{
			throw new Exception( "Authentication failed! {$this->providerId} returned an invalid user ID.", 5 );
		}

		// if api key is provided, we attempt to use steam web api
		if( isset( Hybrid_Auth::$config['providers']['Steam']['keys']['key'] ) && Hybrid_Auth::$config['providers']['Steam']['keys']['key'] )
		{
			$userProfile = $this->getUserProfileWebAPI( Hybrid_Auth::$config['providers']['Steam']['keys']['key'] );
		}

		// otherwise we fallback to community data
		else
		{
			$userProfile = $this->getUserProfileLegacyAPI();
		}

		// fetch user profile
		foreach( $userProfile as $k => $v )
		{
			$this->user->profile->$k = $v ? $v : $this->user->profile->$k;
		}

		// store user profile
		Hybrid_Auth::storage()->set( "hauth_session.{$this->providerId}.user", $this->user );
	}

	function getUserProfileWebAPI( $apiKey )
	{
		$apiUrl = 'http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=' . $apiKey . '&steamids=' . $this->user->profile->identifier;

		$data = $this->httpRequest( $apiUrl );
		$data = json_decode( $data['response'] );

		// not sure if correct
		$data = isset( $data->response->players[0] ) ? $data->response->players[0] : null;

		$userProfile = array();

		$userProfile['displayName'] = property_exists( $data, 'personaname'   ) ? $data->personaname    : '';
		$userProfile['firstName'  ] = property_exists( $data, 'realname'      ) ? $data->realname       : '';
		$userProfile['photoURL'   ] = property_exists( $data, 'avatarfull'    ) ? $data->avatarfull     : '';
		$userProfile['profileURL' ] = property_exists( $data, 'profileurl'    ) ? $data->profileurl     : '';
		$userProfile['country'    ] = property_exists( $data, 'loccountrycode') ? $data->loccountrycode : '';

		return $userProfile;
	}

	function getUserProfileLegacyAPI()
	{
		$apiUrl = 'http://steamcommunity.com/profiles/' . $this->user->profile->identifier . '/?xml=1';

		$data = $this->httpRequest( $apiUrl );
		$data = @ new SimpleXMLElement( $data['response'] );

		$userProfile = array();

		$userProfile['displayName' ] = property_exists( $data, 'steamID'     ) ? (string) $data->steamID     : '';
		$userProfile['firstName'   ] = property_exists( $data, 'realname'    ) ? (string) $data->realname    : '';
		$userProfile['photoURL'    ] = property_exists( $data, 'avatarFull'  ) ? (string) $data->avatarFull  : '';
		$userProfile['description' ] = property_exists( $data, 'summary'     ) ? (string) $data->summary     : '';
		$userProfile['region'      ] = property_exists( $data, 'location'    ) ? (string) $data->location    : '';
		$userProfile['profileURL'  ] = property_exists( $data, 'customURL'   )
			? "http://steamcommunity.com/id/{$data->customURL}/"
			: "http://steamcommunity.com/profiles/{$this->user->profile->identifier}/";

		return $userProfile;
	}

	function httpRequest( $url )
	{
		$ch = curl_init();

		$curl_options = array(
			CURLOPT_URL            => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_USERAGENT      => "WordPress Social Login (https://wordpress.org/plugins/wordpress-social-login/)",
			CURLOPT_MAXREDIRS      => 3,
			CURLOPT_TIMEOUT        => 30
		);

		curl_setopt_array($ch, $curl_options);

		$data = curl_exec($ch);

		return array(
			'response' => $data,
			'info'     => curl_getinfo($ch),
			'error'    => curl_error($ch),
		);
	}
}
