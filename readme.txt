=== WordPress Social Login ===
Contributors: miled
Tags:  twitter, facebook, google, yahoo, linkedin, myspace, foursquare, aol, login, register, comment
Requires at least: 3.0
Tested up to: 3.2.1
Stable tag: 1.1.3

Allow your visitors to login and comment with social networks and identities providers such as Facebook, Twitter and Google. [Alpha Stage!!] 

== Description ==

This plugin allow your visitors to login and comment with social networks and identities providers such as Facebook, Twitter and Google.

Currenty Supported Providers are : Facebook, Google, Yahoo, Twitter, Windows Live, Myspace, Foursquare, Linkedin, and AOL. 

Credits:

- Social Connect plugin : http://wordpress.org/extend/plugins/social-connect/
- HybridAuth Open Source Library: http://hybridauth.sourceforge.net/
- Social icon set: http://www.wpzoom.com/wpzoom/500-free-icons-wpzoom-social-networking-icon-set/

== Installation ==

1. Upload `wordpress-social-login` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Visit the "Settings | WP Social Login" administration page to configure social media providers.

== Frequently Asked Questions ==
= Do I need to add template tags to my theme? =

WordPress Social Login will attempts to work with the default WordPress comment, login and registration forms.

If you want to add the social login widget to another location in your theme, you can insert the following code in that location: 

`<?php do_action( 'wordpress_social_login' ); ?>`

= Where can I report bugs & get support? =

As an open source project and alpha stage plugin, We Appreciate Your Feedback! 

Also to get **help and support**, join us on the [discussion group](https://groups.google.com/forum/#!forum/hybridauth-plugins) or email me at &lt;hybridauth (at) gmail (dot) com&gt;

== Screenshots ==

1. **Comment** - Buttons for 3rd party services are also provided on the comment form.
2. **Login** - On the login and registration form, buttons for 3rd party services are provided.
3. **Setup** - To correctly setup these Identity Providers please carefully follow the help section of each one.

== Changelog ==

= 1.1.3 =

* Fix a bug with hybridauth settings

= 1.1.1 =

* Fix a bug when displaying not enbaled provider on the login form

= 1.1.0 =

* Initial release.
