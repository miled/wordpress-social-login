<?php
/*!
* WordPress Social Login
*
* https://miled.github.io/wordpress-social-login/ | https://github.com/miled/wordpress-social-login
*   (c) 2011-2020 Mohamed Mrassi and contributors | https://wordpress.org/plugins/wordpress-social-login/
*/

class WSL_Test_Widget extends WP_UnitTestCase
{
	function setUp()
	{
		parent::setUp();
	}

	function tearDown()
	{
		parent::tearDown();
	}

	function test_has_action()
	{
		$test = has_action( 'wordpress_social_login', 'wsl_action_wordpress_social_login' );
		$this->assertTrue( (bool) $test );
	}

	function test_shortcode_exists()
	{
		$test = shortcode_exists( 'wordpress_social_login' );
		$this->assertTrue( (bool) $test );
	}

	function test_did_actions()
	{
		wsl_render_auth_widget();

		$this->assertEquals( 1, did_action( 'wsl_render_auth_widget_start' ) );
		$this->assertEquals( 1, did_action( 'wsl_render_auth_widget_end' ) );
	}

	/*
	* hacky way of checking for correct css selectors
	*/
	function test_has_content()
	{
		$test = wsl_render_auth_widget();

		$this->assertTrue( (bool) $test );

		$this->assertEquals( 1, substr_count( $test, '"wp-social-login-widget"'          ) );
		$this->assertEquals( 1, substr_count( $test, '"wp-social-login-connect-with"'    ) );
		$this->assertEquals( 1, substr_count( $test, '"wp-social-login-provider-list"'   ) );
		$this->assertEquals( 3, substr_count( $test, '"wp-social-login-provider '        ) );
		$this->assertEquals( 1, substr_count( $test, ' wp-social-login-provider-google'  ) );
		$this->assertEquals( 1, substr_count( $test, '"wp-social-login-widget-clearing"' ) );
	}
}
