<?php
/*!
* WordPress Social Login
*
* http://miled.github.io/wordpress-social-login/ | https://github.com/miled/wordpress-social-login
*  (c) 2011-2014 Mohamed Mrassi and contributors | http://wordpress.org/plugins/wordpress-social-login/
*/

class WSL_Test_Session extends WP_UnitTestCase
{
	function setUp()
	{
		parent::setUp();
	}

	function tearDown()
	{
		parent::tearDown();
	}

	function test_wsl_version()
	{
		global $WORDPRESS_SOCIAL_LOGIN_VERSION;

		$this->assertEquals( $_SESSION["wsl::plugin"], "WordPress Social Login " . $WORDPRESS_SOCIAL_LOGIN_VERSION );
	}
}
