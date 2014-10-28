## [WordPress Social Login](https://wordpress.org/plugins/wordpress-social-login/) 2.2.3
 
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
- Contacts import from google, facebook, live and linkedin.
- User profiles and contacts management.
- Compatible with WordPress 3.0+, BuddyPress and bbPress.
- ACL-based security model.
- Provides a direct access to social networks apis.
- Modular design easily extended.
- Comprehensive documentation.

#### What's new on WSL 2.2.3

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
* WSL now trigger WordPress do_login action hooks whenever a user connect.
* Authentication display now fall back to In Page when WSL is used on a mobile device.
* WSL Diagnostics has been reworked and can check the minimum system requirements and for few common issues. 
* Added new tool "Repair WSL tables".
* Added Debug mode.
* Added Authentication Playground.
* Added new hooks in the authentication widget and auth process.
* Function wsl_render_login_form() renamed to wsl_render_auth_widget()
* Css file style.css renamed to widget.css
* Depreciated hooks from versions prior 2.2.2 has been removed.
* wsl_user_custom_avatar and wsl_bp_user_custom_avatar are now pluggable and can be redefined.
* wsl_render_notice_page and wsl_render_error_page has slightly changed.
* Fix an issue where redirect_to get overwritten in some cases.
* Fix an issue with redirect_to where the call back url is encoded twice.
* Minor bugfixes

#### What's next

- [ ] Accounts linking/mapping
- [ ] User moderation will be fully implemented and no longer needs TML.
- [ ] Widget shortcode will support arguments
- [ ] Add usermeat shortcode
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