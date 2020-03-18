<?php
/*!
* WordPress Social Login
*
* https://miled.github.io/wordpress-social-login/ | https://github.com/miled/wordpress-social-login
*   (c) 2011-2020 Mohamed Mrassi and contributors | https://wordpress.org/plugins/wordpress-social-login/
*/

session_start();

global $wpdb;

$_SERVER['HTTP_HOST'] = 'localhost';

define( 'WORDPRESS_SOCIAL_LOGIN_ABS_PATH', dirname( __FILE__ ) . '/../' );

$_tests_dir = getenv('WP_TESTS_DIR');

if ( ! $_tests_dir )
{
	$_tests_dir = '/tmp/wordpress-tests-lib';
}

echo "Booting...\n";
echo "PHP:session_id()=" . session_id() . "\n";
echo "WPT:WP_TESTS_DIR=" . $_tests_dir . "\n";
echo "WSL:WORDPRESS_SOCIAL_LOGIN_ABS_PATH=" . realpath( WORDPRESS_SOCIAL_LOGIN_ABS_PATH ) . "\n";

require_once $_tests_dir . '/includes/functions.php';

function _manually_load_plugin()
{
	require_once WORDPRESS_SOCIAL_LOGIN_ABS_PATH . 'wp-social-login.php';
}

tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

require $_tests_dir . '/includes/bootstrap.php';

echo "Activate WSL...\n";

activate_plugin( 'wordpress-social-login/wp-social-login.php' );

echo "Uninstall WSL...\n";

wsl_database_uninstall();

echo "Install WSL...\n";

wsl_database_install();

echo "ReInstall WSL...\n";

wsl_install();

echo "Testing WSL...\n";
