<?php
/*!
* WordPress Social Login
*
* http://miled.github.io/wordpress-social-login/ | https://github.com/miled/wordpress-social-login
*  (c) 2011-2014 Mohamed Mrassi and contributors | http://wordpress.org/plugins/wordpress-social-login/
*/

class WSL_Test_Users extends WP_UnitTestCase
{
	protected $someUserID      = null;
	protected $someUserLogin   = 'wslusertest';
	protected $someUserMail    = 'wp-user@domain.ltd';
	protected $someUserIDP     = 'Google';
	protected $someUserProfile = null;

	function setUp()
	{
		parent::setUp();

		$this->someUserID = wp_create_user( $this->someUserLogin, wp_generate_password(), $this->someUserMail );

		include_once WORDPRESS_SOCIAL_LOGIN_ABS_PATH . 'hybridauth/Hybrid/User_Profile.php';

		$this->someUserProfile = new Hybrid_User_Profile();

		$this->someUserProfile->identifier    = 'identifier';
		$this->someUserProfile->firstName     = 'firstName';
		$this->someUserProfile->lastName      = 'lastName';
		$this->someUserProfile->displayName   = 'display Name';
		$this->someUserProfile->photoURL      = '';
		$this->someUserProfile->profileURL    = '';
		$this->someUserProfile->email         = 'email@domain.ltd';
		$this->someUserProfile->emailVerified = 'email-verified@domain.ltd';
	}

	function tearDown()
	{
		parent::tearDown();
	}

	/*
	* make sure we can found a wordpress user by email
	*/
	function test_wsl_wp_email_exists()
	{
		$this->assertEquals( $this->someUserID, wsl_wp_email_exists( $this->someUserMail ) );
	}

	/*
	* make sure users social profiles setter and getters works correctly
	*/
	function test_store_user_social_profile()
	{
		$insert_id = wsl_store_hybridauth_user_profile( $this->someUserID, $this->someUserIDP, $this->someUserProfile );

		$profile = (array) wsl_get_stored_hybridauth_user_profiles_by_user_id( $this->someUserID );
		$this->assertEquals( 1                                     , count( $profile ) );
		$this->assertEquals( $this->someUserID                     , $profile[0]->user_id );
		$this->assertEquals( $this->someUserIDP                    , $profile[0]->provider );
		$this->assertEquals( $this->someUserProfile->identifier    , $profile[0]->identifier );
		$this->assertEquals( $this->someUserProfile->email         , $profile[0]->email );
		$this->assertEquals( $this->someUserProfile->emailVerified , $profile[0]->emailverified );

		$profile = (array) wsl_get_stored_hybridauth_user_id_by_email_verified( $this->someUserProfile->emailVerified );
		$this->assertEquals( 1, count( $profile ) );

		$profile = (array) wsl_get_stored_hybridauth_user_id_by_provider_and_provider_uid( $this->someUserIDP, $this->someUserProfile->identifier );
		$this->assertEquals( 1, count( $profile ) );

		$user_id = wsl_get_stored_hybridauth_user_id_by_provider_and_provider_uid( $this->someUserIDP, $this->someUserProfile->identifier );
		$this->assertEquals( $this->someUserID, $user_id );

		$count = wsl_get_wsl_users_count();
		$this->assertEquals( 1, $count );

		$count = wsl_get_stored_hybridauth_user_profiles_count();
		$this->assertEquals( 1, $count );
	}

	/*
	* make sure users social profiles are deleted when the associated wordpress user is deleted
	*/
	function test_delete_user_social_profile()
	{
		$insert_id = wsl_store_hybridauth_user_profile( $this->someUserID, $this->someUserIDP, $this->someUserProfile );

		$profile = (array) wsl_get_stored_hybridauth_user_profiles_by_user_id( $this->someUserID );
		$this->assertEquals( 1, count( $profile ) );

		wp_delete_user( $this->someUserID );

		$profile = (array) wsl_get_stored_hybridauth_user_profiles_by_user_id( $this->someUserID );
		$this->assertEquals( 0, count( $profile ) );
	}
}
