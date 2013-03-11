<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*    (c) 2011-2013 Mohamed Mrassi and contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

/**
* List of supported providers by Hybridauth Library 
*
* Here are defined a 24 idp or so. If you need even more of the Hybridauth additional providers, 
* then you need to download additional providers package at http://hybridauth.sf.net/download.html
* and then copy needed additional providers to the library.
*
* For instance, to get Identica provider working you need to copy 'hybridauth-identica/Providers/Identica.php' 
* to 'plugins/wordpress-social-login/hybridauth/Hybrid/Providers/Identica.php' and then add it to 
* $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG.
*
* After that you just need to configure your application ID, private and secret keys at the plugin
* configuration pages.
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------

$WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG = ARRAY(
	ARRAY( 
		"provider_id"       => "Facebook",
		"provider_name"     => "Facebook", 
		"require_client_id" => true, 
		"new_app_link"      => "https://developers.facebook.com/apps", 

		"default_network"  => true,
		"cat"               => "socialnetworks",
	)
	,
	ARRAY(
		"provider_id"       => "Google",
		"provider_name"     => "Google",
		"callback"          => true,
		"require_client_id" => true,
		"new_app_link"      => "https://code.google.com/apis/console/", 

		"default_network"  => true,
		"cat"               => "socialnetworks",
	) 
	,
	ARRAY( 
		"provider_id"       => "Twitter",
		"provider_name"     => "Twitter", 
		"new_app_link"      => "https://dev.twitter.com/apps", 

		"default_network"  => true,
		
		"cat"               => "microblogging",
	)
	,
	ARRAY( 
		"provider_id"       => "Live",
		"provider_name"     => "Windows Live", 
		"require_client_id" => true,
		"new_app_link"      => "https://manage.dev.live.com/ApplicationOverview.aspx", 

		"cat"               => "pleasedie",
	)
	,
	ARRAY( 
		"provider_id"       => "Yahoo",
		"provider_name"     => "Yahoo!",
		"new_app_link"      => null, 

		"cat"               => "pleasedie",
	)
	,
	ARRAY( 
		"provider_id"       => "MySpace",
		"provider_name"     => "MySpace", 
		"new_app_link"      => "http://www.developer.myspace.com/", 

		"cat"               => "pleasedie",
	)
	,
	ARRAY( 
		"provider_id"       => "Foursquare",
		"provider_name"     => "Foursquare",
		"callback"          => true,
		"require_client_id" => true, 
		"new_app_link"      => "https://www.foursquare.com/oauth/", 

		"cat"               => "microblogging",
	)
	,
	ARRAY( 
		"provider_id"       => "LinkedIn",
		"provider_name"     => "LinkedIn", 
		"new_app_link"      => "https://www.linkedin.com/secure/developer", 

		"cat"               => "professional",
	)
	,
	ARRAY( 
		"provider_id"       => "AOL",
		"provider_name"     => "AOL", 
		"new_app_link"      => null, 

		"cat"               => "pleasedie",
	)
	,
	ARRAY( 
		"provider_id"       => "Vkontakte",
		"provider_name"     => "Vkontakte", 
		"callback"          => true,
		"require_client_id" => true,
		"new_app_link"      => "http://vk.com/developers.php", 

		"cat"               => "socialnetworks",
	)
	,
	ARRAY( 
		"provider_id"       => "LastFM",
		"provider_name"     => "Last.FM", 
		"new_app_link"      => "http://www.lastfm.com/api/account", 

		"cat"               => "media",
	)
	,
	ARRAY( 
		"provider_id"       => "Instagram",
		"provider_name"     => "Instagram", 
		"callback"          => true,
		"require_client_id" => true,
		"new_app_link"      => "http://instagr.am/developer/clients/manage/", 

		"cat"               => "media",
	)
	,
	ARRAY( 
		"provider_id"       => "Identica",
		"provider_name"     => "Identica", 
		"new_app_link"      => "http://identi.ca/settings/oauthapps/new", 

		"cat"               => "microblogging",
	) 
	,
	ARRAY( 
		"provider_id"       => "Tumblr",
		"provider_name"     => "Tumblr", 
		"new_app_link"      => "http://www.tumblr.com/oauth/apps", 

		"cat"               => "microblogging", // o well 
	),
	ARRAY( 
		"provider_id"       => "Goodreads",
		"provider_name"     => "Goodreads", 
		"callback"          => true,
		"new_app_link"      => "http://www.goodreads.com/api", 

		"cat"               => "media",
	),  
	ARRAY( 
		"provider_id"       => "Stackoverflow",
		"provider_name"     => "Stackoverflow", 
		"new_app_link"      => null, 

		"cat"               => "programmers",
	),
	ARRAY( 
		"provider_id"       => "GitHub",
		"provider_name"     => "GitHub", 
		"require_client_id" => true,
		"callback"          => true,
		"new_app_link"      => "https://github.com/settings/applications/new", 

		"cat"               => "programmers",
	),
	ARRAY( 
		"provider_id"       => "500px",
		"provider_name"     => "px500", 
		"new_app_link"      => "http://developers.500px.com/", 

		"cat"               => "media",
	), 
	ARRAY( 
		"provider_id"       => "Skyrock",
		"provider_name"     => "Skyrock", 
		"new_app_link"      => "https://www.skyrock.com/developer/application", 

		"cat"               => "socialnetworks",
	),
	ARRAY( 
		"provider_id"       => "Mixi",
		"provider_name"     => "Mixi", 
		"new_app_link"      => null, 

		"cat"               => "socialnetworks",
	), 
	ARRAY( 
		"provider_id"       => "Steam",
		"provider_name"     => "Steam", 
		"new_app_link"      => null, 

		"cat"               => "gamers",
	),
	ARRAY( 
		"provider_id"       => "TwitchTV",
		"provider_name"     => "Twitch.tv", 
		"require_client_id" => true,
		"callback"          => true,
		"new_app_link"      => "http://www.twitch.tv/settings?section=applications", 

		"cat"               => "gamers",
	),
	ARRAY( 
		"provider_id"       => "Mailru",
		"provider_name"     => "Mailru", 
		"require_client_id" => true,
		"callback"          => true,
		"new_app_link"      => "http://api.mail.ru/", 

		"cat"               => "misc",
	),
	ARRAY( 
		"provider_id"       => "Yandex",
		"provider_name"     => "Yandex", 
		"require_client_id" => true,
		"callback"          => true,
		"new_app_link"      => "http://api.yandex.ru", 

		"cat"               => "misc",
	),
	ARRAY( 
		"provider_id"       => "Odnoklassniki",
		"provider_name"     => "Odnoklassniki", 
		"require_client_id" => true,
		"callback"          => true,
		"new_app_link"      => "http://dev.odnoklassniki.ru/", 

		"cat"               => "socialnetworks",
	),
);

// --------------------------------------------------------------------
