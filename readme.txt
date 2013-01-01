=== WordPress Social Login ===
Contributors: miled
Tags:  facebook, google, yahoo, twitter, windows live, myspace, foursquare, linkedin, gowalla, last.fm, instagram, goodreads, tumblr, aol, vkontakte, stackoverflow, github, 500px, skyrock, mixi.jp, steam, login, register, comment, social, social networks, social login
Requires at least: 3.0
Tested up to: 3.2.1
Stable tag: 1.2.3 
License: MIT License
License URI: http://www.opensource.org/licenses/MIT

WordPress Social Login allow your visitors to comment and login with social networks such as Twitter, Facebook, Google, Yahoo and more.

== Description ==

Using <strong>WordPress Social Login</strong>, your blog's users will be able to login and comment using their accounts on Facebook, Google, Yahoo, Twitter and many more.

<strong>WordPress Social Login is:</strong>
<ul>
 <li>Open Source,</li> 
 <li>Free, Unlimited,</li> 
 <li>White label, Customizable,</li> 
 <li>Social sign on solution,</li> 
 <li>With data kept in house</li> 
</ul>  

<strong>Getting started is as simple as :</strong>
<ol>
 <li>Install WordPress Social Login plugin,</li> 
 <li>Setup the social networks you want to use,</li>  
 <li>Customize the way you want it to look and behave.</li>  
</ol> 

<strong>Supported services :</strong>
<ol>
 <li>Facebook</li>  
 <li>Google</li>  
 <li>Yahoo</li>  
 <li>Twitter</li>  
 <li>Windows Live</li>  
 <li>Myspace</li>  
 <li>Foursquare</li>  
 <li>Linkedin</li>  
 <li>Tumblr</li>  
 <li>Gowalla</li>  
 <li>Last.fm</li>  
 <li>Instagram</li>  
 <li>Goodreads</li>  
 <li>AOL</li>  
 <li>Vkontakte</li>  
 <li>Stackoverflow</li>  
 <li>Github</li>  
 <li>500px</li>  
 <li>Skyrock</li>  
 <li>Mixi.jp</li>  
 <li>Steam</li>  
 <li>Mail.ru</li>  
 <li>Yandex</li>  
 <li>Odnoklassniki</li>  
</ol> 
	
<strong>Credits:</strong>

- Social Connect authors : http://wordpress.org/extend/plugins/social-connect/
- HybridAuth Library: http://hybridauth.sourceforge.net/ 

<strong>Special thanks to:</strong>

* [thenbrent](http://profiles.wordpress.org/users/thenbrent/) the talented developer behind the Social Connect plugin
* [Macky Franklin](http://www.mackyfranklin.com) for all the great help
* [Pat Anvil](http://patanvil.com) for adding Goodreads
* [Nicolas Quienot](https://github.com/niQo) for adding Skyrock
* [Ayrat Belyaev](https://github.com/xbreaker) for adding Mail.ru, Yandex and Odnoklassniki

== Installation ==

1. Upload `wordpress-social-login` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Visit the "Settings | WP Social Login" administration page to configure social media providers.

== Frequently Asked Questions ==

= Do I need to add template tags to my theme? =

WordPress Social Login will attempts to work with the default WordPress comment, login and registration forms.

If you want to add the social login widget to another location in your theme, you can insert the following code in that location:

`<?php do_action( 'wordpress_social_login' ); ?>`

== Screenshots ==

1. **Comment** - Buttons for 3rd party services are also provided on the comment form.
2. **Login** - On the login and registration form, buttons for 3rd party services are provided.
3. **Setup** - To correctly setup these Identity Providers please carefully follow the help section of each one.

== Changelog ==

= 1.2.3 =

* Update Hybridauth Library to 2.1.1-dev (fix fb login issue and others stuff)
* remove PHP PECL OAuth extension conflict

= 1.2.2 =

* add Mail.ru, Yandex and Odnoklassniki
* add more customization options
* improve user guide
* a bunch of fixes and small improvements

= 1.1.9 =

* some fixes and small improvements

= 1.1.8 =

* add goodreads

= 1.1.7 =

* Hotfixes some minor issuees
* add Insights

= 1.1.6 =

* Add gowalla, last.fm and tumblr
* Add a new icon set
* Add devlepement mode
* Update hybridauth to 2.0.10

= 1.1.5 =

* Import users avatars
* Fix an issue with unique emails.
* Others small fixes

= 1.1.4 =

* A numbers of bug fixes and improvments

= 1.1.3 =

* Fix a bug with hybridauth settings

= 1.1.1 =

* Fix a bug when displaying not enbaled provider on the login form

= 1.1.0 =

* Initial release.
