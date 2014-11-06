<?php
/*!
* WordPress Social Login
*
* http://miled.github.io/wordpress-social-login/ | https://github.com/miled/wordpress-social-login
*  (c) 2011-2014 Mohamed Mrassi and contributors | http://wordpress.org/plugins/wordpress-social-login/
*/

class WSL_Test_Globals extends WP_UnitTestCase
{
	function setUp()
	{
		parent::setUp();
	}

	function tearDown()
	{
		parent::tearDown();
	}

	function test_version()
	{
		global $WORDPRESS_SOCIAL_LOGIN_VERSION;

		$this->assertGreaterThanOrEqual( 3, strlen( $WORDPRESS_SOCIAL_LOGIN_VERSION ) );
	}

	function test_providers()
	{
		global $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG;

		$this->assertGreaterThan( 25, count( $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG ) );
	}

	function test_components()
	{
		global $WORDPRESS_SOCIAL_LOGIN_COMPONENTS;

		$this->assertEquals( 7, count( $WORDPRESS_SOCIAL_LOGIN_COMPONENTS ) );
	}

	function test_tabs()
	{
		global $WORDPRESS_SOCIAL_LOGIN_ADMIN_TABS;

		$this->assertEquals( 11, count( $WORDPRESS_SOCIAL_LOGIN_ADMIN_TABS ) );
	}
}
