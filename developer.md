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

#### wordpress_social_login
...

#### wsl_render_login_form_admin_head_user_profile
...

#### wsl_process_login
...

#### wsl_admin_notification
...

#### wsl_user_custom_avatar
...

#### wsl_database_migration_process
...

## Filters
To use these filters you can simply create a custom function inside any file you want, functions.php in your theme or a custom plugin.

#### wsl_hook_process_login_userdata 
Use this filter to change the user data you want to insert on database. WSL will attempt to create new users using ```wp_insert_user``` and the ```$userdata``` array can contain the following fields : http://codex.wordpress.org/Function_Reference/wp_insert_user#Notes.

```php
function custom_wsl_user_userdata( $userdata ) {
    $userdata['user_pass'] = 'CUSTOM_PASSWORD'; // change and existen field
    $userdata['role']      = 'something';       // attribute a custom role 

    return $userdata;
}
add_filter('wsl_hook_process_login_userdata', 'custom_wsl_user_userdata', 10, 1);
```

#### wsl_hook_process_login_insert_user
Delegate user insert to a custom function. It's imperative to return the user id created.

```php
function trololo_rather_use_my_awsome_custom_function( $userdata ) {
    $wpdb->insert( "{$wpdb->prefix}_users", array( stuff ) ); 
    
    ...
    
    return $user_id;
}
add_filter('wsl_hook_process_login_insert_user', 'trololo_rather_use_my_awsome_custom_function', 10, 1);
```

#### wsl_hook_process_register_success
This filter allows you to trigger any routine right after a user has been created. You could use this filter for example to send him an email, change his sotre data on database, etc.

```php
function custom_function( $user_id ) {
    do stuff;

    return nothing;
}
add_filter('wsl_hook_process_register_success', 'custom_function', 10, 1);
```

#### wsl_hook_process_login_success
This filter allows you to trigger any action you want to do before WSL creates a wp session for him and redirect him back to website home page (or whatever callback is set). 

```php
function custom_function( $user_id ) {
    do stuff;
 
    return nothing;
}
add_filter('wsl_hook_process_login_success', 'custom_function', 10, 1);
```

#### wsl_hook_alter_render_login_form 
This filter allows you to delegate the generation of WSL widget all together to a custom function. 

#### wsl_hook_social_icon_set  
This filter allows you to change the base url used for social networks icons sets.

#### wsl_hook_profile_widget 
allow users to generate their own. < fuzzy
