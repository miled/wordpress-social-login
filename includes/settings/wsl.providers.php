<?php
/*!
* WordPress Social Login
*
* https://miled.github.io/wordpress-social-login/ | https://github.com/miled/wordpress-social-login
*   (c) 2011-2020 Mohamed Mrassi and contributors | https://wordpress.org/plugins/wordpress-social-login/
*/

/**
* List of supported providers by Hybridauth Library
*
*   ARRAY(
*      'provider_id'         : String  - Alphanumeric(a-zA-Z0-9) code/name of a provider
*      'provider_name'       : String  - Real provider name.
*      'require_client_id'   : Boolean - If a provider uses OAuth 2. Defaults to false.
*      'callback'            : Boolean - If the provide require to set a callback url. Defaults to false.
*      'new_app_link'        : String  - If the provide require to create a new application on his developer site.
*      'default_network'     : Boolean - If true, it will shows up by default on Admin > WordPress Social Login > Networks
*   )
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------

$WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG = ARRAY(
	ARRAY(
		"provider_id"       => "Facebook",
		"provider_name"     => "Facebook",
		"require_client_id" => true,
		"callback"          => true,
		"new_app_link"      => "https://developers.facebook.com/apps",

		"default_network"   => true,
	),
	ARRAY(
		"provider_id"       => "Google",
		"provider_name"     => "Google",
		"callback"          => true,
		"require_client_id" => true,
		"new_app_link"      => "https://console.developers.google.com",

		"default_network"   => true,
	),
	ARRAY(
		"provider_id"       => "Twitter",
		"provider_name"     => "Twitter",
		"callback"          => true,
		"new_app_link"      => "https://dev.twitter.com/apps",

		"default_network"  => true,
	),
	ARRAY(
		"provider_id"       => "WordPress",
		"provider_name"     => "WordPress",
		"require_client_id" => true,
		"callback"          => true,
		"new_app_link"      => "https://developer.wordpress.com/apps/new/",
	),
	ARRAY(
		"provider_id"       => "Yahoo",
		"provider_name"     => "Yahoo!",
		"require_client_id" => true,
		"callback"          => true,
		"new_app_link"      => "https://developer.yahoo.com/apps/create/",
	),
	ARRAY(
		"provider_id"       => "LinkedIn",
		"provider_name"     => "LinkedIn",
		"require_client_id" => true,
		"callback"          => true,
		"new_app_link"      => "https://www.linkedin.com/secure/developer",
	),
	ARRAY(
		"provider_id"       => "Disqus",
		"provider_name"     => "Disqus",
		"require_client_id" => true,
		"callback"          => true,
		"new_app_link"      => "https://disqus.com/api/applications/",
	),
	ARRAY(
		"provider_id"       => "Instagram",
		"provider_name"     => "Instagram",
		"callback"          => true,
		"require_client_id" => true,
		"new_app_link"      => "http://instagr.am/developer/clients/manage/",
	),
	ARRAY(
		"provider_id"       => "Reddit",
		"provider_name"     => "Reddit",
		"require_client_id" => true,
		"callback"          => true,
		"new_app_link"      => "https://ssl.reddit.com/prefs/apps",
	),
	ARRAY(
		"provider_id"       => "Foursquare",
		"provider_name"     => "Foursquare",
		"callback"          => true,
		"require_client_id" => true,
		"new_app_link"      => "https://www.foursquare.com/oauth/",
	),
	ARRAY(
		"provider_id"       => "Tumblr",
		"provider_name"     => "Tumblr",
		"new_app_link"      => "http://www.tumblr.com/oauth/apps",
	),
	ARRAY(
		"provider_id"       => "Stackoverflow",
		"provider_name"     => "Stackoverflow",
	),
	ARRAY(
		"provider_id"       => "StackExchange",
		"provider_name"     => "StackExchange",
		"callback"          => true,
		"require_client_id" => true,
		"new_app_link"      => "https://stackapps.com/apps/oauth/register",
	),
	ARRAY(
		"provider_id"       => "GitHub",
		"provider_name"     => "GitHub",
		"require_client_id" => true,
		"callback"          => true,
		"new_app_link"      => "https://github.com/settings/applications/new",
	),
	ARRAY(
		"provider_id"       => "Dribbble",
		"provider_name"     => "Dribbble",
		"require_client_id" => true,
		"custom_callback"   => true,
		"new_app_link"      => "https://dribbble.com/account/applications/new",
	),
	ARRAY(
		"provider_id"       => "Steam",
		"provider_name"     => "Steam",
		"new_app_link"      => "https://steamcommunity.com/dev/apikey",
		"require_api_key"   => true,
	),
	ARRAY(
		"provider_id"       => "TwitchTV",
		"provider_name"     => "Twitch.tv",
		"require_client_id" => true,
		"callback"          => true,
		"new_app_link"      => "http://www.twitch.tv/settings?section=applications",
	),
	ARRAY(
		"provider_id"       => "Vkontakte",
		"provider_name"     => "ВКонтакте",
		"callback"          => true,
		"require_client_id" => true,
		"new_app_link"      => "http://vk.com/developers.php",
	),
	ARRAY(
		"provider_id"       => "Mailru",
		"provider_name"     => "Mailru",
		"require_client_id" => true,
		"callback"          => true,
		"new_app_link"      => "http://api.mail.ru/",
	),
	ARRAY(
		"provider_id"       => "Odnoklassniki",
		"provider_name"     => "Odnoklassniki",
		"require_client_id" => "both",
		"callback"          => true,
		"new_app_link"      => "http://dev.odnoklassniki.ru/",
	),
	ARRAY(
		"provider_id"       => "WindowsLive",
		"provider_name"     => "Windows Live",
		"require_client_id" => true,
		"new_app_link"      => "https://account.live.com/developers/applications/create",
	),
	ARRAY(
		"provider_id"       => "Authentiq",
		"provider_name"     => "Authentiq",
		"require_client_id" => true,
		"callback"          => true,
		"new_app_link"      => "https://dashboard.authentiq.com/",
	),
	ARRAY(
		"provider_id"       => "EventBrite",
		"provider_name"     => "EventBrite",
		"require_client_id" => true,
		"callback"          => true,
		"new_app_link"      => "http://www.eventbrite.com/myaccount/apps/",
	),
	ARRAY(
		"provider_id"       => "Steemconnect",
		"provider_name"     => "Steemconnect",
		"require_client_id" => true,
		"callback"          => true,
		"new_app_link"      => "https://v2.steemconnect.com/dashboard",
    ),

	ARRAY(
		"provider_id"       => "Amazon",
		"provider_name"     => "Amazon",
		"require_client_id" => true,
		"callback"          => true,
		"new_app_link"      => "https://developer.amazon.com/home.html",
	),
	ARRAY(
		"provider_id"       => "BitBucket",
		"provider_name"     => "BitBucket",
		"require_client_id" => true,
		"callback"          => true,
		"new_app_link"      => "https://api.bitbucket.org/",
	),
	ARRAY(
		"provider_id"       => "Discord",
		"provider_name"     => "Discord",
		"require_client_id" => true,
		"callback"          => true,
		"new_app_link"      => "https://discordapp.com/developers/applications/",
	),
	ARRAY(
		"provider_id"       => "GitLab",
		"provider_name"     => "GitLab",
		"require_client_id" => true,
		"callback"          => true,
		"new_app_link"      => "https://docs.gitlab.com/ee/integration/oauth_provider.html",
	),
	ARRAY(
		"provider_id"       => "Spotify",
		"provider_name"     => "Spotify",
		"require_client_id" => true,
		"callback"          => true,
		"new_app_link"      => "https://developer.spotify.com/dashboard/",
	),
	ARRAY(
		"provider_id"       => "WeChat",
		"provider_name"     => "WeChat",
		"require_client_id" => true,
		"callback"          => true,
		"new_app_link"      => "https://open.wechat.com/",
	),
	ARRAY(
		"provider_id"       => "Yandex",
		"provider_name"     => "Yandex",
		"require_client_id" => true,
		"callback"          => true,
		"new_app_link"      => "https://tech.yandex.com/direct/doc/dg/concepts/register-docpage/#request",
	),

    // ARRAY(
		// "provider_id"       => "Goodreads",
		// "provider_name"     => "Goodreads",
		// "callback"          => true,
		// "new_app_link"      => "https://www.goodreads.com/api/keys",
	// ),
);

// --------------------------------------------------------------------
