<?php
namespace Hybridauth\Provider;

use Hybridauth\Exception\UnexpectedApiResponseException;
use Hybridauth\Adapter\OAuth2;
use Hybridauth\Data;
use Hybridauth\User;

class EventBrite extends OAuth2
{
	protected $scope 			= '';
	protected $apiBaseUrl 		= 'https://www.eventbriteapi.com/v3/';
	protected $authorizeUrl 	= 'https://www.eventbrite.com/oauth/authorize';
	protected $accessTokenUrl 	= 'https://www.eventbrite.com/oauth/token';
	protected $apiDocumentation = 'https://www.eventbrite.fr/developer/v3/api_overview';
	protected $accessTokenName 	= 'token';

	public function getUserProfile()
	{
        $res = $this->apiRequest('users/me/');

        $data = new Data\Collection($res);

		if ( !$data->exists('id'))
		{
            throw new UnexpectedApiResponseException('Provider API returned an unexpected response.');
        }

		$userProfile = new User\Profile();

        $userProfile->identifier  = $data->get('id');
        $userProfile->displayName = $data->get('name');
        $userProfile->firstName   = $data->get('first_name');
        $userProfile->lastName    = $data->get('last_name');
        $userProfile->profileURL  = $data->get('link');
        $userProfile->webSiteURL  = $data->get('website');
        $userProfile->gender      = $data->get('gender');
        $userProfile->language    = $data->get('locale');
        $userProfile->description = $data->get('about');

		$emails = $data->get('emails');
        $email  = $emails[0];

        $userProfile->email = $email->email;

        $userProfile->region = $data->filter('hometown')->get('name');

		if( $image_id = $data->get('image_id') ){
			try{
				$res   = $this->apiRequest('media/' . $image_id);
				$idata = new Data\Collection($res);
				$userProfile->photoURL = $idata->get('url');
			}
			catch(Exception $e){
			}
        }

		$userProfile->emailVerified = (bool)$email->verified ? $userProfile->email : '';

        return $userProfile;
	}
}
