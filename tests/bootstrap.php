<?php
/*!
* WordPress Social Login
*
* http://miled.github.io/wordpress-social-login/ | https://github.com/miled/wordpress-social-login
*  (c) 2011-2014 Mohamed Mrassi and contributors | http://wordpress.org/plugins/wordpress-social-login/
*/

/**
*
*  $ mkdir /tmp/wordpress-tests 
*  $ cd /tmp/wordpress-tests 
*  $ svn co http://svn.automattic.com/wordpress-tests/trunk/ 
*
*  > rename wp-tests-config-sample.php to wp-tests-config.php
*  > edit wp-tests-config.php and set a test database
*
*  $ export WP_TESTS_DIR=/tmp/wordpress-tests 
*  $ cd wp-content/plugins/wordpress-social-login 
*  $phpunit
*
**/

session_start();

global $wpdb;

$_SERVER['HTTP_HOST'] = 'localhost';

define( 'WORDPRESS_SOCIAL_LOGIN_ABS_PATH', dirname( __FILE__ ) . '/../' );

echo "Booting...\n";
echo "PHP:session_id()=" . session_id() . "\n";
echo "WPT:WP_TESTS_DIR=" . getenv( 'WP_TESTS_DIR' ) . "\n";
echo "WSL:WORDPRESS_SOCIAL_LOGIN_ABS_PATH=" . realpath( WORDPRESS_SOCIAL_LOGIN_ABS_PATH ) . "\n";

require_once getenv( 'WP_TESTS_DIR' ) . '/includes/functions.php';

function _manually_load_plugin()
{
	require_once WORDPRESS_SOCIAL_LOGIN_ABS_PATH . 'wp-social-login.php';
}

tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

require getenv( 'WP_TESTS_DIR' ) . '/includes/bootstrap.php';

echo "Activate WSL...\n";

activate_plugin( 'wordpress-social-login/wp-social-login.php' );

echo "Uninstall WSL...\n";

wsl_database_install();

echo "Install WSL...\n";

wsl_database_uninstall();

echo "ReInstall WSL...\n";

wsl_install();

echo "Testing WSL...\n";
