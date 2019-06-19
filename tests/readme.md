#### WSL Test unit

This is still a work in progress.

##### How to run

.1 Checkout WordPress dev suite 

```
$ mkdir /tmp/wordpress-tests 
$ cd /tmp/wordpress-tests 
$ svn co http://svn.automattic.com/wordpress-tests/trunk/
```

.2 Install WordPress site.

    rename wp-tests-config-sample.php to wp-tests-config.php
    edit wp-tests-config.php and set a test database

.3 Install PHPunit

```
$ wget https://phar.phpunit.de/phpunit.phar
$ php phpunit.phar
```

.4 Setup and run WSL tests

```
$ export WP_TESTS_DIR=/tmp/wordpress-tests 
$ cd wp-content/plugins/wordpress-social-login 
$ phpunit
```
