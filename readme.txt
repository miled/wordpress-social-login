=== WordPress Social Login ===
Contributors: miled
Tags: login, register, comment, social login, social networks, facebook, google, twitter, reddit, linkedin, instagram, vkontakte, github, steam, dribbble, twitch.tv
Requires at least: 3.0
Tested up to: 4.0
Stable tag: 2.2.3
License: MIT License
License URI: http://opensource.org/licenses/MIT
Donate link: https://www.redcross.org/quickdonate/index.jsp

WordPress Social Login allow your visitors to comment and login with social networks such as Twitter, Facebook, Google, Yahoo and more.

== Description ==

<strong>Wordpress Social Login</strong> allow your website readers and customers to register on using their existing social accounts IDs, eliminating the need to fill out registration forms and remember usernames and passwords. 

Wordpress Social Login also allow you to import users contact list from Google Gmail, Facebook, Windows Live and LinkedIn.

Wordpress Social Login gives you absolute control over users access to your website and comes a list of rules and restrictions for you to setup.

<strong>Easy to customize and integrate</strong> <br>
Wordpress Social Login come with a simple but flexible and fully customizable authentication widget. And if you are a developer or designer then you can customize it to your heart's content.

<strong>Wide variety of providers</strong> <br>
Depending on the audience you're targeting, you can choose from a wide variety of providers and services including: Social networks, Microblogging platforms, Professional networks, Media, Photo sharing, Programmers and Gamers networks.

<strong>Currently supported providers</strong><br>
Facebook, Google, Twitter, Windows Live, Yahoo!, LinkedIn, Reddit, Disqus, Tumblr, Stackoverflow, GitHub, Dribbble, Instagram, 500px, Foursquare, Steam, Twitch.tv, Mixi, Vkontakte, Mail.ru, Yandex, Odnoklassniki, Goodreads, Skyrock, Last.fm, AOL and PixelPin.

<strong>Free, unlimited and white-label</strong> <br>
Wordpress Social Login is open-source and completely free. The source code is publicly available on [GitHub](https://github.com/hybridauth/WordPress-Social-Login) for anyone to help and contribute. You are free to use a Wordpress Social Login in commercial projects as long as the copyright header is left intact.

<strong>Special thanks to:</strong>

* [Thenbrent](http://profiles.wordpress.org/users/thenbrent/) the talented developer behind the Social Connect plugin.
* [Pat Anvil](http://patanvil.com) for adding Goodreads.
* [Ayrat Belyaev](https://github.com/xbreaker) for adding Mail.ru, Yandex and Odnoklassniki.
* [Social Login plugin](https://wordpress.org/plugins/oa-social-login/) for many borrowed functions.
* [Query Monitor plugin](https://wordpress.org/plugins/query-monitor/) - A must have for Wordpress plugins developers.

Big thanks to everyone who have contributed to WordPress Social Login by submitting Patches, Ideas, Reviews and by Helping in the support forum.

== Installation ==

= The hard way =

1. Download, Unzip and drop the extension on /wp-content/plugins/ directory,
1. As administrator, activate the plugin through the 'Plugins' menu in WordPress,
1. Goto the Settings > WP Social Login to get started.

= The easy way =

1. As administrator, goto 'Plugins' then Click on 'Add New',
2. Search for 'WordPress Social Login' then Click on 'Install Now',
3. Wait for it to download, Unpack and to install,
4. Activate the plugin by Clicking on 'Activate Plugin'
5. Goto the Settings > WP Social Login to get started.


== Frequently Asked Questions ==

The user guide and frequently asked questions can be found at http://miled.github.io/wordpress-social-login/

== Screenshots ==

1. WSL attempts to work with the default WordPress comment, login and registration forms.
2. WSL come with a simple but flexible and fully customizable authentication widget.
3. WSL widget can be easily integrated into most WordPress themes and many popular plugins using hooks and shortcodes.
4. WSL social networks setup.
5. WSL widget customization.
6. WSL advanced configuration.
7. WSL Buddypress Integration.
8. WSL components and add-ons manager.

== Changelog ==

= 2.2.3 = 

WSL 2.2.3 fixes a critical issue found on WSL 2.2.2 that could potentially allow multiple accounts and prevent contacts import. We recommend that users upgrade to this latest version.

WSL 2.2.3 also include a number of new features, and fixes several stability issues. See below for details.

<strong>Developers Release Notes</strong>

> These release notes are aimed at developers.

This release did focus on code health and flexibility and it was necessary to move some code around, and to remove few functions and hooks. We know, it sucks to break WSL API at such short notice, but it was indispensable and unavoidable as we're trying to move the project forward.

As announced on WSL Support Forum, this is by no means a drastic change to the API. In fact, we tried our best to keep the said changes to a strict minimum, and the vast majority of WSL users will not be affected.

Those breaking changes are:

* Deprecated hooks, prior to 2.2.2, have been removed.
* Deprecated css selectors, prior to 2.2.2, have been removed.
* Deprecated internal functions have been removed.
* Few internal functions have been either removed, renamed or slightly changed.
* Few pluggable functions has slightly changed.
* Steam's users identifiers are converted to a new format.

Please update the WSL hooks you were using accordingly to the new developer API:

http://miled.github.io/wordpress-social-login/developer-api-migrating-2.2.html
http://miled.github.io/wordpress-social-login/developer-api-authentication.html
http://miled.github.io/wordpress-social-login/developer-api-widget.html
http://miled.github.io/wordpress-social-login/developer-api-functions.html

On this release we have reworked Steam provider to fully support their new Web API, and we decided to change Steam's users identifiers to SteamID64. When updated, WSL 2.2.3 will automatically convert all the existing steam users identifiers in wslusersprofiles to the new format.

It's worth mentioning that in upcoming releases and before we hit WSL 3.0, we're planning to rework other parts of the codebase; for instance user database functions will be re-factored using an ORM, and profile completion will be replaced by new module.

We explicitly discourage you from using, or hacking, any internal function (i.e., Not publicly documented on WSL dev API), as they are now subject to change in subsequent releases. If it wasn't possible to achieve some required functionality in a proper way through the already available and documented WSL hooks, please ask for support before resorting to hacks.

Upon reaching WSL 3.0 as a major milestone, our priorities will flip to maintenance and stability (i.e, repair code health, enhance code coverage) rather than developing new features. This massive rewrite aims to make WSL more modular easily extended through plugins and add-ons (e.g., http://miled.github.io/wsl-users-converter/).

<strong>List of changes</strong>

* WSL is now compatible with PHP 5.2 again.
* WSL is now compatible with WordPress 3.0+ again.
* WSL now display social apis errors when authentication fails.
* WSL now support authentications through Dribbble.com.
* Steam provider has been entirely reworked and now fully support the new Web API.
* Steam users IDs is now converted to SteamID64 rather than http://steamcommunity.com/openid/id/{USER_STEAMID64}.
* LinkedIn provider has been enhanced. WSL can now get the full LinkedIn's members avatars and headline (fix).
* Changed facebook api endpoints to graph api v2.0
* WSL can now import users contacts from vkontakte.
* Profile completion form has received a visual update.
* WSL now provide an easier access to social networks apis.
* WSL now trigger WordPress do_login action hooks whenever a user connect.
* Authentication display now fall back to In Page when WSL is used on a mobile device.
* WSL admin interfaces have been reworked and can be now extended with hooks.
* Bouncer Membership level can be now set to any user role.
* WSL Diagnostics has been reworked and can check the minimum system requirements and for few common issues. 
* Added "Repair WSL tables" under Tools tab.
* Added "Debug mode" under Tools tab (namely whatchdog).
* Added "Authentication Playground" under Tools tab (namely auth-paly).
* Added "Uninstall" under Tools tab.
* Added new hooks in the authentication widget and auth process.
* Made WSL more RTL friendly.
* Added 403 pages under wsl folders (silence is.. highly overrated).
* PHP file wsl.auth.widget.php renamed to wsl.auth.widgets.php
* Function wsl_render_login_form() renamed to wsl_render_auth_widget()
* wsl_user_custom_avatar and wsl_bp_user_custom_avatar are now pluggable and can be redefined.
* wsl_render_notice_page and wsl_render_error_page has slightly changed.
* Fix a critical issue found on WSL 2.2.2 with wslusersprofiles.
* Fix an issue where redirect_to get overwritten in some cases.
* Fix an issue with redirect_to where the callback url was encoded twice.
* Fix several stability issues.
* Added testunit to the project (early version).
* Deprecated hooks, prior to 2.2.2, have been removed.
* Deprecated internal functions have been removed.
* Deprecated css selectors, in wsl widget, have been removed.
* Updated the API documentation for WSL authentication process
* Updated the API documentation for WSL widget generator
* Updated the API documentation for pluggable WSL functions 
* Added a list of code snippets to WSL documentation
* Added documentation for social apis access (through php code)
* Added documentation for basic troubleshooting (common issues)
* Added documentation for advanced troubleshooting (internal tools)

= 2.2.2 = 

* Fix an issue where wsl did not display the right user avatar.
* Fix an issue where providers icons wasn't showing in sites using https.
* Fix an issue with basic insights, where users counts by age wasn't showing.
* Add WordPress to list of supported provider.

= 2.2.1 = 

* WSL can be fully integrated with your BuddyPress installation: display of users avatars and xprofiles mapping.
* WSL is now updated to work with the latest apis changes of the supported social networks.
* WSL Widget is now more flexible than before and can be fully customized to fit you website theme.
* Introducing new providers : Reddit, Disqus and PixelPin.
* WSL Hooks has been reworked and few hooks have been depreciated in favour of new ones. For more information, see: http://hybridauth.sourceforge.net/wsl/developer.html
* A number of bugfixes, small enhancements and visual updates.

= 2.1.3 = 

* In a similar way to WordPress plugins, WSL uses Components,
* Email Validation is replaced with Profile Completion,
* User Moderation made compatible with Theme My Login plugin,
* A number of enhancements and new options now available but who care

= 2.0.3 = 

* Managing WSL users,
* Import WSL users contact list from Google Gmail, Facebook, Live and LinkedIn,
* An entirely reworked user interface,
* Improving the documentation and guides,
* Introducing a new module, and the long awaited, The bouncer,
* Added Twitch.tv and Steam.

= 1.2.4 =

* WSL admin ui Relooking
* Code re-factoring
* add Widget display on advanced settings
* RIP Gowalla
* WordPress Social Login is now licensed under The MIT License only (should be GPL compatible) 

= 0.0.0 =

* I'm too old to remember
