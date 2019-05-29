<?php
/*!
 * Hybridauth
 * https://hybridauth.github.io | https://github.com/hybridauth/hybridauth
 *  (c) 2017 Hybridauth authors | https://hybridauth.github.io/license.html
 */

namespace Hybridauth\Provider;

use Hybridauth\Adapter\OAuth2;
use Hybridauth\Data;
use Hybridauth\Exception\UnexpectedApiResponseException;
use Hybridauth\User;

/**
 * LinkedIn v2 OAuth2 provider adapter.
 */
class LinkedIn2 extends OAuth2
{
    /**
     * {@inheritdoc}
     */
    public $scope = 'r_liteprofile r_emailaddress w_member_social';

    /**
     * {@inheritdoc}
     */
    protected $apiBaseUrl = 'https://api.linkedin.com/v2/';

    /**
     * {@inheritdoc}
     */
    protected $authorizeUrl = 'https://www.linkedin.com/oauth/v2/authorization';

    /**
     * {@inheritdoc}
     */
    protected $accessTokenUrl = 'https://www.linkedin.com/oauth/v2/accessToken';

    /**
     * {@inheritdoc}
     */
    protected $apiDocumentation = 'https://developer.linkedin.com/docs/oauth2';

    /**
     * {@inheritdoc}
     */
    public function getUserProfile()
    {
        $fields = [
            'id',
            'firstName',
            'lastName',
            'profilePicture'
        ];

        if ($this->config->get('photo_size') === 'original') {
            $fields[] = 'picture-urls::(original)';
        }

        $response  = $this->apiRequest('me?projection=(' . implode(',', $fields) . '(displayImage~:playableStreams))', 'GET', array() );
        $userEmail = $this->apiRequest('emailAddress?q=members&projection=(elements*(handle~))', 'GET', array() );

        $data = new Data\Collection($response);

        $user_country  = $data->filter( 'firstName' )->filter( 'preferredLocale' )->get( 'country' );
        $user_language = $data->filter( 'firstName' )->filter( 'preferredLocale' )->get( 'language' );

        if ( $user_country && $user_language ) {

            $user_key = $user_language . '_' . $user_country;

            $firstName = $data->filter('firstName')->filter( 'localized' )->get( $user_key );
            $lastName = $data->filter('lastName')->filter( 'localized' )->get( $user_key );
            $photoURL = $data->filter( 'profilePicture' )->filter( 'displayImage~' )->filter( 'elements' )->filter( '0' )->filter( 'identifiers' )->filter( '0' )->get( 'identifier' );
            $email = $userEmail->elements[0]->{'handle~'}->emailAddress;
            $country = $data->filter('firstName')->filter('preferredLocale')->get('country');

            $connections = $data->filter( 'profilePicture' )->filter( 'displayImage~' )->filter( 'paging' )->get( 'count' );

        } else {

            $firstName   = '';
            $lastName    = '';
            $photoURL    = '';
            $email       = '';
            $country     = '';
            $connections = '';

        }

        if (!$data->exists('id')) {
            throw new UnexpectedApiResponseException('Provider API returned an unexpected response.');
        }

        $userProfile = new User\Profile();

        $userProfile->identifier  = $data->get('id');
        $userProfile->firstName   = $firstName;
        $userProfile->lastName    = $lastName;
        $userProfile->photoURL    = $photoURL;
        $userProfile->profileURL  = '';
        $userProfile->email       = $email;
        $userProfile->description = '';
        $userProfile->country     = $country;

        $userProfile->emailVerified = $userProfile->email;

        $userProfile->displayName = trim($userProfile->firstName . ' ' . $userProfile->lastName);

        $userProfile->data['connections'] = $connections;

        return $userProfile;
    }

    /**
     * {@inheritdoc}
     *
     * @see https://developer.linkedin.com/docs/share-on-linkedin
     */
    public function setUserStatus($status)
    {
        $status = is_string($status) ? ['comment' => $status] : $status;
        if (!isset($status['visibility'])) {
            $status['visibility']['code'] = 'anyone';
        }

        $headers = [
            'Content-Type' => 'application/json',
            'x-li-format'  => 'json',
        ];

        $response = $this->apiRequest('me?projection/shares?format=json', 'POST', $status, $headers);

        return $response;
    }
}