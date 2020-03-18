<?php
/*!
* WordPress Social Login
*
* https://miled.github.io/wordpress-social-login/ | https://github.com/miled/wordpress-social-login
*   (c) 2011-2020 Mohamed Mrassi and contributors | https://wordpress.org/plugins/wordpress-social-login/
*/

class WSL_Test_Components extends WP_UnitTestCase
{
	function setUp()
	{
		parent::setUp();
	}

	function tearDown()
	{
		parent::tearDown();
	}

	function test_component_core_enabled()
	{
		$this->assertTrue( wsl_is_component_enabled( 'core' ) );
	}

	function test_component_networks_enabled()
	{
		$this->assertTrue( wsl_is_component_enabled( 'networks' ) );
	}

	function test_component_loginwidget_enabled()
	{
		$this->assertTrue( wsl_is_component_enabled( 'login-widget' ) );
	}

	function test_component_bouncer_enabled()
	{
		$this->assertTrue( wsl_is_component_enabled( 'bouncer' ) );
	}
}
