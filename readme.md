## [WordPress Social Login](https://wordpress.org/plugins/wordpress-social-login/) 2.2.3 [![Build Status](https://travis-ci.org/miled/wordpress-social-login.svg?branch=master)](https://travis-ci.org/miled/wordpress-social-login) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/miled/wordpress-social-login/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/miled/wordpress-social-login/?branch=master)

**WordPress Social Login** is a free and open source plugin made by the community, for the community.

Basically, WordPress Social Login allow your website visitors and customers to register and login via social networks such as twitter, facebook and google but it has much more to offer.

For more information about WordPress Social Login, refer to our [online user guide](http://miled.github.io/wordpress-social-login/).

    Note: This repository is for development only. 
    The official release is distributed through WordPress website.
![WSL Authentication Widget](https://raw.githubusercontent.com/miled/wordpress-social-login/master/screenshot-1.png)

### Key Features

- No premium features.
- One-click social login.
- Absolute privacy of your website users data.
- Wide variety of identities providers (25+ IDP).
- A highly customizable and fully extensible widgets.
- Easy-to-use and clean user interfaces.
- Contacts import from google, facebook, live, linkedin and vkontakte.
- User profiles and contacts management.
- Compatible with WordPress 3.0+, BuddyPress and bbPress.
- ACL-based security model.
- Provides a direct access to social networks apis.
- Modular design easily extended.
- Comprehensive documentation.

#### What's new on WSL 2.2.3

WSL 2.2.3 fixes a critical issue found on WSL 2.2.2 that could potentially allow multiple accounts and prevent contacts import. We recommend that users upgrade to this latest version.

WSL 2.2.3 also include a number of new features, and fixes several stability issues. See readme.txt > Changelog for details.

##### Developers Release Notes

    These release notes are aimed at developers.

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

* http://miled.github.io/wordpress-social-login/developer-api-migrating-2.2.html
* http://miled.github.io/wordpress-social-login/developer-api-authentication.html
* http://miled.github.io/wordpress-social-login/developer-api-widget.html
* http://miled.github.io/wordpress-social-login/developer-api-functions.html

On this release we have reworked Steam provider to fully support their new Web API, and we decided to change Steam's users identifiers to SteamID64. When updated, WSL 2.2.3 will automatically convert all the existing steam users identifiers in wslusersprofiles to the new format.

It's worth mentioning that in upcoming releases and before we hit WSL 3.0, we're planning to rework other parts of the codebase; for instance user database functions will be re-factored using an ORM, and profile completion will be replaced by new module.

We explicitly discourage you from using, or hacking, any internal function (i.e., Not publicly documented on WSL dev API), as they are now subject to change in subsequent releases. If it wasn't possible to achieve some required functionality in a proper way through the already available and documented WSL hooks, please ask for support before resorting to hacks.

Upon reaching WSL 3.0 as a major milestone, our priorities will flip to maintenance and stability (i.e, repair code health, enhance code coverage) rather than developing new features. This massive rewrite aims to make WSL more modular easily extended through plugins and add-ons (e.g., http://miled.github.io/wsl-users-converter/).

#### What's next

- [ ] Accounts linking/mapping
- [ ] Rework userdata api
- [ ] Widget shortcode will support arguments
- [ ] Add a meta shortcode
- [ ] Add soundcloud developers.soundcloud.com/docs
- [ ] Add levels or conditionals to Dev mode (i.g., only display is_admin) 
- [ ] ..

#### License 

MIT — That's short for do whatever you want.

#### Thanks

Big thanks to everyone who have contributed to WordPress Social Login by submitting Patches, Ideas, Reviews and by Helping in the support forum. 

#### Screenshots
![screenshot](https://raw.githubusercontent.com/miled/wordpress-social-login/master/screenshot-2.png)
===
![screenshot](https://raw.githubusercontent.com/miled/wordpress-social-login/master/screenshot-3.png)
===
![screenshot](http://miled.github.io/wordpress-social-login/assets/img/theme_fontawesome.png)
===
![screenshot](https://raw.githubusercontent.com/miled/wordpress-social-login/master/screenshot-4.png)
=
![screenshot](https://raw.githubusercontent.com/miled/wordpress-social-login/master/screenshot-5.png)
===
![screenshot](https://raw.githubusercontent.com/miled/wordpress-social-login/master/screenshot-6.png)
===
![screenshot](https://raw.githubusercontent.com/miled/wordpress-social-login/master/screenshot-7.png)
===
![screenshot](https://raw.githubusercontent.com/miled/wordpress-social-login/master/screenshot-8.png)