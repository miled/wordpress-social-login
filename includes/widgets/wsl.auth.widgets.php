<?php
/*!
* WordPress Social Login
*
* https://miled.github.io/wordpress-social-login/ | https://github.com/miled/wordpress-social-login
*   (c) 2011-2020 Mohamed Mrassi and contributors | https://wordpress.org/plugins/wordpress-social-login/
*/

/**
* Authentication widgets generator
*
* http://miled.github.io/wordpress-social-login/widget.html
* http://miled.github.io/wordpress-social-login/themes.html
* http://miled.github.io/wordpress-social-login/developer-api-widget.html
*/

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------

/**
* Generate the HTML content of WSL Widget
*
* Note:
*   WSL shortcode arguments are still experimental and might change in future versions.
*
*   [wordpress_social_login
*        auth_mode="login"
*        caption="Connect with"
*        enable_providers="facebook|google"
*        restrict_content="wsl_user_logged_in"
*        assets_base_url="http://example.com/wp-content/uploads/2022/01/"
*   ]
*
*   Overall, WSL widget work with these simple rules :
*      1. Shortcode arguments rule over the defaults
*      2. Filters hooks rule over shortcode arguments
*      3. Bouncer rules over everything
*/
function wsl_render_auth_widget( $args = array() )
{
	$auth_mode = isset( $args['mode'] ) && $args['mode'] ? $args['mode'] : 'login';

	// validate auth-mode
	if( ! in_array( $auth_mode, array( 'login', 'link', 'test' ) ) )
	{
		return;
	}

	// auth-mode eq 'login' => display wsl widget only for NON logged in users
	// > this is the default mode of wsl widget.
	if( $auth_mode == 'login' && is_user_logged_in() )
	{
		return;
	}

	// auth-mode eq 'link' => display wsl widget only for LOGGED IN users
	// > this will allows users to manually link other social network accounts to their WordPress account
	if( $auth_mode == 'link' && ! is_user_logged_in() )
	{
		return;
	}

	// auth-mode eq 'test' => display wsl widget only for LOGGED IN users only on dashboard
	// > used in Authentication Playground on WSL admin dashboard
	if( $auth_mode == 'test' && ! is_user_logged_in() && ! is_admin() )
	{
		return;
	}

	// Bouncer :: Allow authentication?
	if( get_option( 'wsl_settings_bouncer_authentication_enabled' ) == 2 )
	{
		return;
	}

	// HOOKABLE: This action runs just before generating the WSL Widget.
	do_action( 'wsl_render_auth_widget_start' );

	GLOBAL $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG;

	ob_start();

	// Icon set. If eq 'none', we show text instead
	$social_icon_set = get_option( 'wsl_settings_social_icon_set' );

	// wpzoom icons set, is shown by default
	if( empty( $social_icon_set ) )
	{
		$social_icon_set = "wpzoom/";
	}

	$assets_base_url  = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . 'assets/img/32x32/' . $social_icon_set . '/';

	$assets_base_url  = isset( $args['assets_base_url'] ) && $args['assets_base_url'] ? $args['assets_base_url'] : $assets_base_url;

	// HOOKABLE:
	$assets_base_url = apply_filters( 'wsl_render_auth_widget_alter_assets_base_url', $assets_base_url );

	// get the current page url, which we will use to redirect the user to,
	// unless Widget::Force redirection is set to 'yes', then this will be ignored and Widget::Redirect URL will be used instead
	$redirect_to = wsl_get_current_url();

	// Use the provided redirect_to if it is given and this is the login page.
	if ( in_array( $GLOBALS["pagenow"], array( "wp-login.php", "wp-register.php" ) ) && !empty( $_REQUEST["redirect_to"] ) )
	{
		$redirect_to = $_REQUEST["redirect_to"];
	}

	// build the authentication url which will call for wsl_process_login() : action=wordpress_social_authenticate
	$authenticate_base_url = add_query_arg(
		array(
			'action' => 'wordpress_social_authenticate',
			'mode'   => 'login',
		),
		site_url( 'wp-login.php', 'login_post' )
	);

	// if not in mode login, we overwrite the auth base url
	// > admin auth playground
	if( $auth_mode == 'test' )
	{
		$authenticate_base_url = add_query_arg(
            array(
                'action' => 'wordpress_social_authenticate',
                'mode'   => 'test',
            ),
            home_url()
        );
	}

	// > account linking
	elseif( $auth_mode == 'link' )
	{
		$authenticate_base_url = add_query_arg(
            array(
                'action' => 'wordpress_social_authenticate',
                'mode'   => 'link',
            ),
            home_url()
        );
	}

	// Connect with caption
	$connect_with_label = _wsl__( get_option( 'wsl_settings_connect_with_label' ), 'wordpress-social-login' );

	$connect_with_label = isset( $args['caption'] ) ? $args['caption'] : $connect_with_label;

	// HOOKABLE:
	$connect_with_label = apply_filters( 'wsl_render_auth_widget_alter_connect_with_label', $connect_with_label );
?>

<!--
	wsl_render_auth_widget
	WordPress Social Login <?php echo wsl_get_version(); ?>.
	http://wordpress.org/plugins/wordpress-social-login/
-->
<?php
	// Widget::Custom CSS
	$widget_css = get_option( 'wsl_settings_authentication_widget_css' );

	// HOOKABLE:
	$widget_css = apply_filters( 'wsl_render_auth_widget_alter_widget_css', $widget_css, $redirect_to );

	// show the custom widget css if not empty
	if( ! empty( $widget_css ) )
	{
?>

<style type="text/css">
<?php
	echo
		preg_replace(
			array( '%/\*(?:(?!\*/).)*\*/%s', '/\s{2,}/', "/\s*([;{}])[\r\n\t\s]/", '/\\s*;\\s*/', '/\\s*{\\s*/', '/;?\\s*}\\s*/' ),
				array( '', ' ', '$1', ';', '{', '}' ),
					$widget_css );
?>
</style>
<?php
	}
?>

<div class="wp-social-login-widget">

	<div class="wp-social-login-connect-with"><?php echo $connect_with_label; ?></div>

	<div class="wp-social-login-provider-list">
<?php
	// Widget::Authentication display
	$wsl_settings_use_popup = get_option( 'wsl_settings_use_popup' );

	// if a user is visiting using a mobile device, WSL will fall back to more in page
	$wsl_settings_use_popup = function_exists( 'wp_is_mobile' ) ? wp_is_mobile() ? 2 : $wsl_settings_use_popup : $wsl_settings_use_popup;

	$no_idp_used = true;

	// display provider icons
	foreach( $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG AS $item )
	{
		$provider_id    = isset( $item["provider_id"]    ) ? $item["provider_id"]   : '' ;
		$provider_name  = isset( $item["provider_name"]  ) ? $item["provider_name"] : '' ;

		// provider enabled?
		if( get_option( 'wsl_settings_' . $provider_id . '_enabled' ) )
		{
			// restrict the enabled providers list
			if( isset( $args['enable_providers'] ) )
			{
				$enable_providers = explode( '|', $args['enable_providers'] ); // might add a couple of pico seconds

				if( ! in_array( strtolower( $provider_id ), $enable_providers ) )
				{
					continue;
				}
			}

			// build authentication url
			$authenticate_url = add_query_arg(
				array(
					'provider'    => $provider_id,
					'redirect_to' => urlencode( $redirect_to ),
				),
				$authenticate_base_url
			);

			// http://codex.wordpress.org/Function_Reference/esc_url
			$authenticate_url = esc_url( $authenticate_url );

			// in case, Widget::Authentication display is set to 'popup', then we overwrite 'authenticate_url'
			// > /assets/js/connect.js will take care of the rest
			if( $wsl_settings_use_popup == 1 &&  $auth_mode != 'test' )
			{
				$authenticate_url= "javascript:void(0);";
			}

			// HOOKABLE: allow user to rebuilt the auth url
			$authenticate_url = apply_filters( 'wsl_render_auth_widget_alter_authenticate_url', $authenticate_url, $provider_id, $auth_mode, $redirect_to, $wsl_settings_use_popup );

			// HOOKABLE: allow use of other icon sets
			$provider_icon_markup = apply_filters( 'wsl_render_auth_widget_alter_provider_icon_markup', $provider_id, $provider_name, $authenticate_url );

			if( $provider_icon_markup != $provider_id )
			{
				echo $provider_icon_markup;
			}
			else
			{
?>

		<a rel="nofollow" href="<?php echo $authenticate_url; ?>" title="<?php echo sprintf( _wsl__("Connect with %s", 'wordpress-social-login'), $provider_name ) ?>" class="wp-social-login-provider wp-social-login-provider-<?php echo strtolower( $provider_id ); ?>" data-provider="<?php echo $provider_id ?>" role="button">
			<?php if( $social_icon_set == 'none' ){ echo apply_filters( 'wsl_render_auth_widget_alter_provider_name', $provider_name ); } else { ?><img alt="<?php echo $provider_name ?>" src="<?php echo $assets_base_url . strtolower( $provider_id ) . '.png' ?>" aria-hidden="true" /><?php } ?>

		</a>
<?php
			}

			$no_idp_used = false;
		}
	}

	// no provider enabled?
	if( $no_idp_used )
	{
?>
		<p style="background-color: #FFFFE0;border:1px solid #E6DB55;padding:5px;">
			<?php _wsl_e( '<strong>WordPress Social Login is not configured yet</strong>.<br />Please navigate to <strong>Settings &gt; WP Social Login</strong> to configure this plugin.<br />For more information, refer to the <a rel="nofollow" href="http://miled.github.io/wordpress-social-login">online user guide</a>.', 'wordpress-social-login') ?>.
		</p>
		<style>#wp-social-login-connect-with{display:none;}</style>
<?php
	}
?>

	</div>

	<div class="wp-social-login-widget-clearing"></div>

</div>

<?php
	// provide popup url for hybridauth callback
	if( $wsl_settings_use_popup == 1 )
	{
?>
<input type="hidden" id="wsl_popup_base_url" value="<?php echo esc_url( $authenticate_base_url ) ?>" />
<input type="hidden" id="wsl_login_form_uri" value="<?php echo esc_url( site_url( 'wp-login.php', 'login_post' ) ); ?>" />

<?php
	}

	// HOOKABLE: This action runs just after generating the WSL Widget.
	do_action( 'wsl_render_auth_widget_end' );
?>
<!-- wsl_render_auth_widget -->

<?php
	// Display WSL debugging area bellow the widget.
	// wsl_display_dev_mode_debugging_area(); // ! keep this line commented unless you know what you are doing :)

	return ob_get_clean();
}

// --------------------------------------------------------------------

/**
* WSL wordpress_social_login action
*
* Ref: http://codex.wordpress.org/Function_Reference/add_action
*/
function wsl_action_wordpress_social_login( $args = array() )
{
	echo wsl_render_auth_widget( $args );
}

add_action( 'wordpress_social_login', 'wsl_action_wordpress_social_login' );

// --------------------------------------------------------------------

/**
* WSL wordpress_social_login shortcode
*
* Note:
*   WSL shortcode arguments are still experimental and might change in future versions.
*
* Ref: http://codex.wordpress.org/Function_Reference/add_shortcode
*/
function wsl_shortcode_wordpress_social_login( $args = array(), $content = null )
{
	$restrict_content = isset( $args['restrict_content'] ) && $args['restrict_content'] ? true : false;

	if( 'wp_user_logged_in' == $restrict_content && is_user_logged_in() )
	{
		return do_shortcode( $content );
	}

	if( 'wsl_user_logged_in' == $restrict_content && wsl_get_stored_hybridauth_user_profiles_by_user_id( get_current_user_id() ) )
	{
		return do_shortcode( $content );
	}

	return wsl_render_auth_widget( $args );
}

add_shortcode( 'wordpress_social_login', 'wsl_shortcode_wordpress_social_login' );

// --------------------------------------------------------------------

/**
* WSL wordpress_social_login_meta shortcode
*
* Note:
*   This shortcode is experimental and might change in future versions.
*
*   [wordpress_social_login_meta
*        user_id="215"
*        meta="wsl_current_user_image"
*        display="html"
*        css_class="my_style_is_better"
*   ]
*/
function wsl_shortcode_wordpress_social_login_meta( $args = array() )
{
	// wordpress user id default to current user connected
	$user_id = isset( $args['user_id'] ) && $args['user_id'] ? $args['user_id'] : get_current_user_id();

	// display default to plain text
	$display = isset( $args['display'] ) && $args['display'] ? $args['display'] : 'plain';

	// when display is set to html, css_class will be used for the main dom el
	$css_class = isset( $args['css_class'] ) && $args['css_class'] ? $args['css_class'] : '';

	// wsl user meta to display
	$meta = isset( $args['meta'] ) && $args['meta'] ? $args['meta'] : null;

	if( ! is_numeric( $user_id ) )
	{
		return;
	}

	if( ! $meta )
	{
		return;
	}

	$assets_base_url  = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . 'assets/img/16x16/';

	$assets_base_url  = isset( $args['assets_base_url'] ) && $args['assets_base_url'] ? $args['assets_base_url'] : $assets_base_url;

	$return = '';

	if( 'current_avatar' == $meta )
	{
		if( 'plain' == $display )
		{
			$return = wsl_get_user_custom_avatar( $user_id );
		}
		else
		{
			$return = '<img class="wordpress_social_login_meta_user_avatar ' . $css_class . '" src="' . wsl_get_user_custom_avatar( $user_id ) . '" />';
		}
	}

	if( 'current_provider' == $meta )
	{
		$provider = get_user_meta( $user_id, 'wsl_current_provider', true );

		if( 'plain' == $display )
		{
			$return = $provider;
		}
		else
		{
			$return = '<img class="wordpress_social_login_meta_user_provider ' . $css_class . '" src="' . $assets_base_url . strtolower( $provider ) . '.png"> ' . $provider;
		}
	}

	if( 'user_identities' == $meta )
	{
		ob_start();

		$linked_accounts = wsl_get_stored_hybridauth_user_profiles_by_user_id( $user_id );

		if( $linked_accounts )
		{
			?><table class="wp-social-login-linked-accounts-list <?php echo $css_class; ?>"><?php

			foreach( $linked_accounts AS $item )
			{
				$identity = $item->profileurl;
				$photourl = $item->photourl;

				if( ! $identity )
				{
					$identity = $item->identifier;
				}

				?><tr><td><?php if( $photourl ) { ?><img  style="vertical-align: top;width:16px;height:16px;" src="<?php echo $photourl ?>"> <?php } else { ?><img src="<?php echo $assets_base_url . strtolower(  $item->provider ) . '.png' ?>" /> <?php } ?><?php echo ucfirst( $item->provider ); ?> </td><td><?php echo $identity; ?></td></tr><?php

				echo "\n";
			}

			?></table><?php
		}

		$return = ob_get_clean();

		if( 'plain' == $display )
		{
			$return = strip_tags( $return );
		}
	}

	return $return;
}

add_shortcode( 'wordpress_social_login_meta', 'wsl_shortcode_wordpress_social_login_meta' );

// --------------------------------------------------------------------

/**
* Display on comment area
*/
function wsl_render_auth_widget_in_comment_form()
{
	$wsl_settings_widget_display = get_option( 'wsl_settings_widget_display' );

	if( comments_open() )
	{
		if(
			!  $wsl_settings_widget_display
		||
			$wsl_settings_widget_display == 1
		||
			$wsl_settings_widget_display == 2
		)
		{
			echo wsl_render_auth_widget();
		}
	}
}

add_action( 'comment_form_top'              , 'wsl_render_auth_widget_in_comment_form' );
add_action( 'comment_form_must_log_in_after', 'wsl_render_auth_widget_in_comment_form' );

// --------------------------------------------------------------------

/**
* Display on login form
*/
function wsl_render_auth_widget_in_wp_login_form()
{
	$wsl_settings_widget_display = get_option( 'wsl_settings_widget_display' );

	if( $wsl_settings_widget_display == 1 || $wsl_settings_widget_display == 3 )
	{
		echo wsl_render_auth_widget();
	}
}

add_action( 'login_form'                      , 'wsl_render_auth_widget_in_wp_login_form' );
add_action( 'bp_before_account_details_fields', 'wsl_render_auth_widget_in_wp_login_form' );
add_action( 'bp_before_sidebar_login_form'    , 'wsl_render_auth_widget_in_wp_login_form' );

// --------------------------------------------------------------------

/**
* Display on login & register form
*/
function wsl_render_auth_widget_in_wp_register_form()
{
	$wsl_settings_widget_display = get_option( 'wsl_settings_widget_display' );

	if( $wsl_settings_widget_display == 1 || $wsl_settings_widget_display == 3 )
	{
		echo wsl_render_auth_widget();
	}
}

add_action( 'register_form'    , 'wsl_render_auth_widget_in_wp_register_form' );
add_action( 'after_signup_form', 'wsl_render_auth_widget_in_wp_register_form' );

// --------------------------------------------------------------------

/**
* Enqueue WSL CSS file
*/
function wsl_add_stylesheets()
{
	if( ! wp_style_is( 'wsl-widget', 'registered' ) )
	{
		wp_register_style( "wsl-widget", WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . "assets/css/style.css" );
	}

	wp_enqueue_style( "wsl-widget" );
}

add_action( 'wp_enqueue_scripts'   , 'wsl_add_stylesheets' );
add_action( 'login_enqueue_scripts', 'wsl_add_stylesheets' );

// --------------------------------------------------------------------

/**
* Enqueue WSL Javascript, only if we use popup
*/
function wsl_add_javascripts()
{
	$wsl_settings_use_popup = get_option( 'wsl_settings_use_popup' );

    // if a user is visiting using a mobile device, WSL will fall back to more in page
	$wsl_settings_use_popup = function_exists( 'wp_is_mobile' ) ? wp_is_mobile() ? 2 : $wsl_settings_use_popup : $wsl_settings_use_popup;

	if( $wsl_settings_use_popup != 1 )
	{
		return null;
	}

	if( ! wp_script_is( 'wsl-widget', 'registered' ) )
	{
		wp_register_script( "wsl-widget", WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . "assets/js/widget.js" );
	}

	wp_enqueue_script( "jquery" );
	wp_enqueue_script( "wsl-widget" );
}

add_action( 'wp_enqueue_scripts'   , 'wsl_add_javascripts' );
add_action( 'login_enqueue_scripts', 'wsl_add_javascripts' );

// --------------------------------------------------------------------
