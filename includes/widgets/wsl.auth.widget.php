<?php
/*!
* WordPress Social Login
*
* http://miled.github.io/wordpress-social-login/ | https://github.com/miled/wordpress-social-login
*  (c) 2011-2014 Mohamed Mrassi and contributors | http://wordpress.org/plugins/wordpress-social-login/
*/

/**
* Authentication widgets generator
*
* http://miled.github.io/wordpress-social-login/widget.html
* http://miled.github.io/wordpress-social-login/themes.html
* http://miled.github.io/wordpress-social-login/developer-api-actions.html
* http://miled.github.io/wordpress-social-login/developer-api-filters.html
*/

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------
/**
* Generate the HTML content of WSL Widget
*/
function wsl_render_login_form()
{
	// WSL Widget won't show up for connected users
	if( is_user_logged_in() && ! is_admin() )
	{
		return;
	}

	// Bouncer :: Allow authentication 
	if( get_option( 'wsl_settings_bouncer_authentication_enabled' ) == 2 )
	{
		return;
	}

	ob_start();

	// HOOKABLE: This action runs just before generating the WSL Widget.
	do_action( 'wsl_render_login_form_start' );

	GLOBAL $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG;

	// Icon set. If eq 'none', we show text instead
	$social_icon_set = get_option( 'wsl_settings_social_icon_set' );

	// wpzoom icons set, is shown by default
	if( empty( $social_icon_set ) )
	{
		$social_icon_set = "wpzoom/";
	}

	$assets_base_url  = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . '/assets/img/32x32/' . $social_icon_set . '/'; 

	// get the current page url, which we will use to redirect the user to,
	// unless Widget::Force redirection is set to 'yes', then this will be ignored and Widget::Redirect URL will be used instead
	$current_page_url = wsl_get_current_url();

	// build the authentication url which will call for wsl_process_login() : action=wordpress_social_authenticate
	$authenticate_base_url = site_url( 'wp-login.php', 'login_post' ) . ( strpos( site_url( 'wp-login.php', 'login_post' ), '?' ) ? '&' : '?' ) . "action=wordpress_social_authenticate&";

	// Connect with caption
	$connect_with_label = _wsl__( get_option( 'wsl_settings_connect_with_label' ), 'wordpress-social-login' );

	// HOOKABLE:
	$connect_with_label = apply_filters( 'wsl_render_login_form_alter_connect_with_label', $connect_with_label, $current_page_url );
?>

<!--
	wsl_render_login_form
	WordPress Social Login Plugin <?php echo wsl_get_version(); ?>.
	http://wordpress.org/extend/plugins/wordpress-social-login/
-->

<?php 
	// Widget::Custom CSS
	$widget_css = get_option( 'wsl_settings_authentication_widget_css' );

	// HOOKABLE:
	$widget_css = apply_filters( 'wsl_render_login_form_alter_widget_css', $widget_css, $current_page_url );

	// show the custom widget css if not empty
	if( ! empty( $widget_css ) )
	{
?> 
<style>
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
		$provider_id   = isset( $item["provider_id"]   ) ? $item["provider_id"]   : '' ;
		$provider_name = isset( $item["provider_name"] ) ? $item["provider_name"] : '' ;

		// provider enabled?
		if( get_option( 'wsl_settings_' . $provider_id . '_enabled' ) )
		{
			// build authentication url
			$authenticate_url = $authenticate_base_url . "provider=" . $provider_id . "&redirect_to=" . urlencode( $current_page_url );

			// http://codex.wordpress.org/Function_Reference/esc_url
			$authenticate_url = esc_url( $authenticate_url );

			// in case, Widget::Authentication display is set to 'popup', then we overwrite 'authenticate_url'
			// > /assets/js/connect.js will take care of the rest
			if( $wsl_settings_use_popup == 1 )
			{ 
				$authenticate_url= "javascript:void(0);";
			}

			// HOOKABLE: allow user to rebuilt the auth url
			$authenticate_url = apply_filters( 'wsl_render_login_form_alter_authenticate_url', $authenticate_url, $provider_id, $current_page_url, $wsl_settings_use_popup );

			// HOOKABLE: allow use of other icon sets
			$provider_icon_markup = apply_filters( 'wsl_render_login_form_alter_provider_icon_markup', $provider_id, $provider_name, $authenticate_url );

			if( $provider_icon_markup != $provider_id )
			{
				echo $provider_icon_markup;
			}
			else
			{
?>

		<a rel="nofollow" href="<?php echo $authenticate_url; ?>" title="<?php echo sprintf( _wsl__("Connect with %s", 'wordpress-social-login'), $provider_name ) ?>" class="wp-social-login-provider wp-social-login-provider-<?php echo strtolower( $provider_id ); ?>" data-provider="<?php echo $provider_id ?>"> 
			<?php if( $social_icon_set == 'none' ){ echo $provider_name; } else { ?><img alt="<?php echo $provider_name ?>" title="<?php echo sprintf( _wsl__("Connect with %s", 'wordpress-social-login'), $provider_name ) ?>" src="<?php echo $assets_base_url . strtolower( $provider_id ) . '.png' ?>" /><?php } ?>	
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
			<?php _wsl_e( '<strong>WordPress Social Login is not configured yet</strong>.<br />Please navigate to <strong>Settings &gt; WP Social Login</strong> to configure this plugin.<br />For more information, refer to the <a href="http://miled.github.io/wordpress-social-login">online user guide</a>.', 'wordpress-social-login') ?>.
		</p>
		<style>#wp-social-login-connect-with{display:none;}</style>
<?php
	}

	// provide popup url for hybridauth callback
	if( $wsl_settings_use_popup == 1 )
	{
	?>

		<input id="wsl_popup_base_url" type="hidden" value="<?php echo esc_url( $authenticate_base_url ) ?>" />
		<input type="hidden" id="wsl_login_form_uri" value="<?php echo esc_url( site_url( 'wp-login.php', 'login_post' ) ); ?>" />

	<?php
	} 
?>
	</div> 

	<div class="wp-social-login-widget-clearing"></div>
</div> 

<!-- wsl_render_login_form -->

<?php
	// HOOKABLE: This action runs just after generating the WSL Widget.
	do_action( 'wsl_render_login_form_end' );

	// Display WSL debugging are bellow the widget.  
	// wsl_display_dev_mode_debugging_area(); // ! keep this line commented unless you know what you are doing :) 

	return ob_get_clean();
}

// --------------------------------------------------------------------

/**
* WSL Widget shortcode tag
*
* Ref: http://codex.wordpress.org/Function_Reference/add_shortcode
*/
function wsl_shortcode_handler()
{
	return wsl_render_login_form();
}

add_shortcode( 'wordpress_social_login', 'wsl_shortcode_handler' );

// --------------------------------------------------------------------

/**
* WSL Widget action 
*/
function wsl_render_login_form_login()
{
	echo wsl_render_login_form();
}

add_action( 'wordpress_social_login', 'wsl_render_login_form_login' );

// --------------------------------------------------------------------

/**
* Display on comment area
*/
function wsl_render_wsl_widget_in_comment_form()
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
			echo wsl_render_login_form();
		}
	}
}

add_action( 'comment_form_top', 'wsl_render_wsl_widget_in_comment_form' );

// --------------------------------------------------------------------

/**
* Display on login form
*/
function wsl_render_wsl_widget_in_wp_login_form()
{
	$wsl_settings_widget_display = get_option( 'wsl_settings_widget_display' );
	
	if( $wsl_settings_widget_display == 1 || $wsl_settings_widget_display == 3 )
	{
		echo wsl_render_login_form();
	}
}

add_action( 'login_form'                      , 'wsl_render_wsl_widget_in_wp_login_form' );
add_action( 'bp_before_account_details_fields', 'wsl_render_wsl_widget_in_wp_login_form' );
add_action( 'bp_before_sidebar_login_form'    , 'wsl_render_wsl_widget_in_wp_login_form' );

// --------------------------------------------------------------------

/**
* Display on login & register form
*/
function wsl_render_wsl_widget_in_wp_register_form()
{
	$wsl_settings_widget_display = get_option( 'wsl_settings_widget_display' );

	if( $wsl_settings_widget_display == 1 || $wsl_settings_widget_display == 3 )
	{
		echo wsl_render_login_form();
	}
}

add_action( 'register_form'    , 'wsl_render_wsl_widget_in_wp_register_form' );
add_action( 'after_signup_form', 'wsl_render_wsl_widget_in_wp_register_form' );

// --------------------------------------------------------------------

/**
* Enqueue WSL CSS file
*/
function wsl_add_stylesheets()
{
	if( ! wp_style_is( 'wsl-widget', 'registered' ) )
	{
		wp_register_style( "wsl-widget", WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . "/assets/css/widget.css" ); 
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
	if( get_option( 'wsl_settings_use_popup' ) != 1 )
	{
		return null;
	}

	if( ! wp_script_is( 'wsl-widget', 'registered' ) )
	{
		wp_register_script( "wsl-widget", WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . "/assets/js/widget.js" );
	}

	wp_enqueue_script( "jquery" );
	wp_enqueue_script( "wsl-widget" );
}

add_action( 'wp_enqueue_scripts'   , 'wsl_add_javascripts' ); 
add_action( 'login_enqueue_scripts', 'wsl_add_javascripts' );

// --------------------------------------------------------------------
