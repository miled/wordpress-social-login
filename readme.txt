=== WordPress Social Login ===
Contributors: miled
Tags: login, comment, social networks, social login, facebook, google, twitter, reddit, linkedin, instagram, vkontakte, github, steam, twitch.tv
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

1. **Comment** - Buttons for 3rd party services are also provided on the comment form.
2. **Login** - On the login and registration form, buttons for 3rd party services are provided.
3. **Setup** - To correctly setup these Identity Providers please carefully follow the help section of each one.
4. **Widget** - WordPress Social Login widget customization
5. **Bouncer** - WordPress Social Login advanced configuration
6. **Components** - WordPress Social Login components manager

== Changelog ==

= 2.2.3 = 

* WSL is now compatible with PHP 5.2 again.
* WSL is now compatible with WordPress 3.0+ again.
* WSL now support authentications through Dribbble.com.
* Steam provider has been entirely reworked and now fully support the new Web API.
* Steam users IDs is now converted to SteamID64 rather than http://steamcommunity.com/openid/id/{USER_STEAMID64}.
* LinkedIn provider has been enhanced. WSL can now get the full LinkedIn's members avatars and headline.
* WSL can now import users contacts from Vkontakte.
* WSL admin interfaces have been reworked and can be now extended with hooks.
* Profile completion form has received a visual update.
* Bouncer Membership level can be now set to any user role.
* WSL now provide an easier access to social networks apis.
* Authentication display now fall back to In Page when WSL is used on a mobile device.
* WSL Diagnostics has been reworked and can check the minimum system requirements and for few common issues. 
* Added new tool "Repair WSL tables".
* Added Debug mode.
* Added new hooks in the authentication widget and auth process.
* Depreciated hooks from versions prior 2.2.2 has been removed.
* wsl_user_custom_avatar and wsl_bp_user_custom_avatar are now pluggable and can be redefined.
* wsl_render_notice_page and wsl_render_error_page has slightly changed.
* Fix an issue where redirect_to get overwritten in some cases.
* Fix an issue with redirect_to where the call back url is encoded twice.
* Minor bugfixes

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
