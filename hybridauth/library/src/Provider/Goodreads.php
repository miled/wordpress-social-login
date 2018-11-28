<?php
/*!
* Hybridauth
* https://hybridauth.github.io | https://github.com/hybridauth/hybridauth
*  (c) 2017 Hybridauth authors | https://hybridauth.github.io/license.html
*/

namespace Hybridauth\Provider;

use Hybridauth\Adapter\OAuth1;
use Hybridauth\Exception\UnexpectedApiResponseException;
use Hybridauth\Data;
use Hybridauth\User;

use Hybridauth\Exception\Exception;
use Hybridauth\Exception\InvalidApplicationCredentialsException;
use Hybridauth\Exception\AuthorizationDeniedException;
use Hybridauth\Exception\InvalidOauthTokenException;
use Hybridauth\Exception\InvalidAccessTokenException;
use Hybridauth\HttpClient;
use Hybridauth\Thirdparty\OAuth\OAuthConsumer;
use Hybridauth\Thirdparty\OAuth\OAuthRequest;
use Hybridauth\Thirdparty\OAuth\OAuthSignatureMethodHMACSHA1;
use Hybridauth\Thirdparty\OAuth\OAuthUtil;


/**
 * Goodreads OAuth1 provider adapter.
 */
class Goodreads extends OAuth1
{
    // protected $oauth1Version = '1.0';
    
    /**
    * {@inheritdoc}
    */
    protected $apiBaseUrl = 'https://www.goodreads.com/';

    /**
    * {@inheritdoc}
    */
    protected $authorizeUrl = 'https://www.goodreads.com/oauth/authorize';

    /**
    * {@inheritdoc}
    */
    protected $requestTokenUrl = 'https://www.goodreads.com/oauth/request_token';

    /**
    * {@inheritdoc}
    */
    protected $accessTokenUrl = 'https://www.goodreads.com/oauth/access_token';

    /**
    * {@inheritdoc}
    */
    protected $apiDocumentation = 'https://www.goodreads.com/api/index';

    /**
    * {@inheritdoc}
    */
    public function getUserProfile()
    {
        $this->apiRequest('api/auth_user');

        $response = $this->httpClient->getResponseBody();

        $response = @ new \SimpleXMLElement( $response );

        if (! $response || ! isset($response->user) ){
            throw new UnexpectedApiResponseException('Provider API returned an unexpected response.');
        }

        $userProfile = new User\Profile();

		$userProfile->identifier  = (string) $response->user['id'];
		$userProfile->displayName = (string) $response->user->name;
		$userProfile->profileURL  = (string) $response->user->link; 

        // try to grab more information about the user if possible
        try {
            $this->apiRequest('user/show/' . $userProfile->identifier . '.xml');
            
            $response = $this->httpClient->getResponseBody();

            $response = @ new \SimpleXMLElement( $response );

            if ($response && isset($response->user) ){
                $userProfile->photoURL    = (string) $response->user->image_url; 
                $userProfile->webSiteURL  = (string) $response->user->website; 
                $userProfile->description = (string) $response->user->about; 
                $userProfile->country     = (string) $response->user->location; 
                $userProfile->gender      = (string) $response->user->gender; 
                $userProfile->age         = (string) $response->user->age; 
            }
        }
        // these extra information are not mandatory so keep it quite
        catch (\Exception $e) {
        }

        return $userProfile;
    }

    /**
    * {@inheritdoc}
    */
    protected function oauthRequest($uri, $method = 'GET', $parameters = [], $headers = [])
    {
        $headers['Content-Length'] = 0; // needs this

        $request = OAuthRequest::from_consumer_and_token(
            $this->OAuthConsumer,
            $this->consumerToken,
            $method,
            $uri,
            $parameters
        );

        $request->sign_request(
            $this->sha1Method,
            $this->OAuthConsumer,
            $this->consumerToken
        );

        //$uri        = $request->get_normalized_http_url(); // doesn't like
        //$parameters = $request->parameters;                // these
        $headers    = array_replace($request->to_header(), (array) $headers);

        $response = $this->httpClient->request(
            $uri,
            $method,
            $parameters,
            $headers
        );

        $this->validateApiResponse('Signed API request has returned an error');

        return $response;
    }
}
