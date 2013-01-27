<?php 
class Hybrid_Providers_Steam extends Hybrid_Provider_Model_OpenID
{
	var $openidIdentifier = "http://steamcommunity.com/openid";

	/**
	* finish login step 
	*/
	function loginFinish()
	{
		parent::loginFinish();

		$uid = str_replace( "http://steamcommunity.com/openid/id/", "", $this->user->profile->identifier );

		if( $uid ){ 
			$data = curl_gets_url( "http://steamcommunity.com/profiles/$uid/?xml=1" );
		
			$data = @ new SimpleXMLElement( $data );

			if ( ! is_object( $data ) ){
				return false;
			}

			$this->user->profile->displayName  = (string) $data->{'steamID'};
			$this->user->profile->photoURL     = (string) $data->{'avatarMedium'};
			$this->user->profile->description  = (string) $data->{'summary'};
			
			$realname = (string) $data->{'realname'}; 

			if( $realname ){
				$this->user->profile->displayName = $realname;
			}
			
			$customURL = (string) $data->{'customURL'};

			if( $customURL ){
				$this->user->profile->profileURL = "http://steamcommunity.com/id/$customURL/";
			}

			// restore the user profile
			Hybrid_Auth::storage()->set( "hauth_session.{$this->providerId}.user", $this->user );
		}
	}
}

function curl_gets_url( $curl_url ){ 
	$ch = curl_init();
	$curl_options = array(
	CURLOPT_URL => $curl_url,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_MAXREDIRS => 3,
	CURLOPT_TIMEOUT => 10
	);
	curl_setopt_array($ch, $curl_options);
	$data = curl_exec($ch);

	return $data;
}
