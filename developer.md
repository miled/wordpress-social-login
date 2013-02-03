# Developer API !! NOT FINAL !! 

## Shortcodes 

#### Render WSL authentication widget
If you want to add the WSL to another location in your theme, you can insert the following code in that location: 
```php
do_action( 'wordpress_social_login' );
```
To display the widget inside regular posts and pages, insert the following:
```
[wordpress_social_login]
```

## Constants
<table>
<tr><td><code>WORDPRESS_SOCIAL_LOGIN_ABS_PATH</code></td><td>The absolute path to **WordPress Social Login** plugin.</td></tr>
<tr><td><code>WORDPRESS_SOCIAL_LOGIN_REL_PATH</code></td><td>The relative path to **WordPress Social Login** plugin.</td></tr>
<tr><td><code>WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL</code></td><td>The absolute url to **WordPress Social Login** plugin.</td></tr> 
<tr><td><code>WORDPRESS_SOCIAL_LOGIN_HYBRIDAUTH_ENDPOINT_URL</code></td><td>The absolute path to **Hybridauth** endpoint.</td></tr>
</table>

## Globals
<table>
<tr><td><code>WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG</code></td><td>List of supported providers
```php
    array( 
        "provider_id"       => string , // Provider ID 
        "provider_name"     => string , // Provider Name
        "require_client_id" => boolean, // true if does requires an app_id. false if it requires a key.
        "new_app_link"      => link   , // link to the provider developper page
        "default_network"   => boolean  // whether this provider is enabled by default or not
    )
```
</td></tr>
<tr><td><code>WORDPRESS_SOCIAL_LOGIN_ADMIN_MODULES_CONFIG</code></td><td>List of WSL moduldes</td></tr>
</table>


## Options
#### wsl-settings-group
Social networks settings. <code>provider_id</code>s are defined on <code>wsl.providers.php</code>, <code>$WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG</code>
<table>
<tr><td>wsl_settings_<code>provider_id</code>_enabled</td><td>Boolean. Whether <code>provider_id</code> is enabled or not.</td></tr>
<tr><td>wsl_settings_<code>provider_id</code>_app_id</td><td><code>provider_id</code> application ID if any.</td></tr>
<tr><td>wsl_settings_<code>provider_id</code>_app_key</td><td><code>provider_id</code> application key if any.</td></tr>
<tr><td>wsl_settings_<code>provider_id</code>_app_secret</td><td><code>provider_id</code> application secret if any.</td></tr>
</table>

#### wsl-settings-group-customize
WordPress Social Login widget customization settings.
<table>
<tr><td><code>wsl_settings_connect_with_label</code></td><td>"Connect with" caption.</td></tr>
<tr><td><code>wsl_settings_social_icon_set</code></td><td>Absolute URL to social networks icons.</td></tr>
<tr><td><code>wsl_settings_users_avatars</code></td><td>Boolean. Whether to use to display users avatars from social networks or not.</td></tr>
<tr><td><code>wsl_settings_use_popup</code></td><td>Boolean. Whether to use a popup when authenticating.</td></tr>
<tr><td><code>wsl_settings_widget_display</code></td><td>Boolean. Whether to display the widget or not.</td></tr>
<tr><td><code>wsl_settings_users_notification</code></td><td>Define the notification to send. 1 es to notify blog admin. 0 No notification.</td></tr>
<tr><td><code>wsl_settings_authentication_widget_css</code></td><td>Injected widget CSS</td></tr>
</tr>
</table>

#### wsl-settings-group-contacts-import
<table>
<tr><td>wsl_settings_contacts_import_<code>provider</code>_enabled</td><td>Boolean. Whether contact import is enabled for <code>provider</code> is enabled or not. Where <code>provider</code> es either Facebook, Google, Twitter, Windows Live or LinkedIn.</td></tr>
</table>

#### wsl-settings-group-bouncer 
<table>
<tr><td><code>wsl_settings_bouncer_registration_enabled</code></td><td>Boolean. Bouncer: WSL Widget : Accept new registration</td></tr>
<tr><td><code>wsl_settings_bouncer_authentication_enabled</code></td><td>Boolean. Bouncer: WSL Widget : Allow authentication</td></tr>

<tr><td><code>wsl_settings_bouncer_email_validation_*</code></td><td>Options related to **Bouncer: Email Validation**</td></tr>
<tr><td><code>wsl_settings_bouncer_new_users_restrict_*</code></td><td>Options related to **Bouncer: Restrictions** (by emails, domains, profiles urls)</td></tr>

<tr><td><code>wsl_settings_development_mode_enabled</code></td><td>Boolean. Whether Dev mode is enabled.</td></tr>
</table>

## Functions
Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.

## Actions

## Filters
