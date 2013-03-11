<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*    (c) 2011-2013 Mohamed Mrassi and contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

/**
* Authentication widgets generator
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------

function wsl_render_login_form()
{
	if ( is_user_logged_in() && ! is_admin() ){
		return;
	}

	// Bouncer :: Allow authentication 
	if( get_option( 'wsl_settings_bouncer_authentication_enabled' ) == 2 ){
		return;
	}

	// HOOKABLE: want to generate your own widget? fine, but Bouncer rules tho
	if( apply_filters( 'wsl_render_login_form_takeover', null ) ){
		return;
	}

	// HOOKABLE: 
	do_action( 'wsl_render_login_form_start' );

	GLOBAL $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG;

	if( empty( $social_icon_set ) ){
		$social_icon_set = "wpzoom/";
	}
	else{
		$social_icon_set .= "/";
	}
	
	$assets_base_url  = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . '/assets/img/32x32/' . $social_icon_set; 

	// HOOKABLE: allow use of other icon sets
	$assets_base_url = apply_filters( 'wsl_later_hook_assets_base_url', $assets_base_url );

	$wsl_settings_connect_with_label = get_option( 'wsl_settings_connect_with_label' );

	$current_page_url = 'http'; 
	if (isset($_SERVER["HTTPS"]) && ($_SERVER["HTTPS"] == "on")) {
		$current_page_url .= "s";
	}
	$current_page_url .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$current_page_url .= $_SERVER["HTTP_HOST"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	}
	else {
		$current_page_url .= $_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
	} 

	$authenticate_base_url = site_url( 'wp-login.php', 'login_post' ) . ( strpos( site_url( 'wp-login.php', 'login_post' ), '?' ) ? '&' : '?' ) . "action=wordpress_social_authenticate&";

	// overwrite endpoint_url if need'd
	if( get_option( 'wsl_settings_hide_wp_login' ) == 1 ){
		$authenticate_base_url = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . "/services/authenticate.php?";
	}
?>
<!--
   wsl_render_login_form
   WordPress Social Login Plugin ( <?php echo $_SESSION["wsl::plugin"] ?> ) 
   http://wordpress.org/extend/plugins/wordpress-social-login/
-->
<?php 
	$wsl_settings_authentication_widget_css = get_option( 'wsl_settings_authentication_widget_css' );

	// if not empty and not the default
	if( ! empty( $wsl_settings_authentication_widget_css ) ){
?>
<style>
<?php echo $wsl_settings_authentication_widget_css ?>
</style>
<?php 
	}
?>
	<span id="wp-social-login-connect-with"><?php echo $wsl_settings_connect_with_label ?></span>
	<div id="wp-social-login-connect-options">
<?php 
	$nok = true;

	// display provider icons
	foreach( $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG AS $item ){
		$provider_id     = @ $item["provider_id"];
		$provider_name   = @ $item["provider_name"]; 

		$authenticate_url = $authenticate_base_url . "provider=" . $provider_id . "&redirect_to=" . urlencode( $current_page_url );

		if( get_option( 'wsl_settings_' . $provider_id . '_enabled' ) ){
			// HOOKABLE: allow use of other icon sets
			$provider_icon_markup = apply_filters( 'wsl_alter_hook_provider_icon_markup', $provider_id );

			$wsl_settings_use_popup = get_option( 'wsl_settings_use_popup' ) ;

			if( $provider_icon_markup != $provider_id ){
				echo $provider_icon_markup;
			}
			elseif( $wsl_settings_use_popup == 1 ){
				?>
				<a rel="nofollow" href="javascript:void(0);" title="Connect with <?php echo $provider_name ?>" class="wsl_connect_with_provider" data-provider="<?php echo $provider_id ?>">
					<img alt="<?php echo $provider_name ?>" title="<?php echo $provider_name ?>" src="<?php echo $assets_base_url . strtolower( $provider_id ) . '.png' ?>" />
				</a>
				<?php
			}
			elseif( $wsl_settings_use_popup == 2 ){
				?>
				<a rel="nofollow" href="<?php echo esc_url( $authenticate_url ) ?>" title="Connect with <?php echo $provider_name ?>" class="wsl_connect_with_provider" >
					<img alt="<?php echo $provider_name ?>" title="<?php echo $provider_name ?>" src="<?php echo $assets_base_url . strtolower( $provider_id ) . '.png' ?>" />
				</a>
				<?php 
			}

			$nok = false; 
		} 
	} 

	if( $nok ){
		?>
		<p style="background-color: #FFFFE0;border:1px solid #E6DB55;padding:5px;">
			<?php _wsl_e( '<strong style="color:red;">WordPress Social Login is not configured yet!</strong><br />Please visit the <strong>Settings\ WP Social Login</strong> administration page to configure this plugin.<br />For more information please refer to the plugin <a href="http://hybridauth.sourceforge.net/userguide/Plugin_WordPress_Social_Login.html">online user guide</a> or contact us at <a href="http://hybridauth.sourceforge.net/">hybridauth.sourceforge.net</a>' , 'wordpress-social-login') ?> 
		</p>
		<style>
			#wp-social-login-connect-with{display:none;}
		</style>
		<?php
	}

	// provide popup url for hybridauth callback
	if( get_option( 'wsl_settings_use_popup' ) == 1 ){
	?>
		<input id="wsl_popup_base_url" type="hidden" value="<?php echo esc_url( $authenticate_base_url ) ?>" />
		<input type="hidden" id="wsl_login_form_uri" value="<?php echo esc_url( site_url( 'wp-login.php', 'login_post' ) ); ?>" />
	<?php
	}
	?>
	</div> 
<!-- /wsl_render_login_form -->  
<?php

	// HOOKABLE: 
	do_action( 'wsl_render_login_form_end' );
}

// --------------------------------------------------------------------

# {{{ render widget

function wsl_render_login_form_login()
{
	wsl_render_login_form(); 
}

add_action( 'wordpress_social_login', 'wsl_render_login_form_login' );

// --------------------------------------------------------------------

// display on comment area
function wsl_render_comment_form()
{
	if( comments_open() && ! is_user_logged_in() ) {
		if( ! get_option( 'wsl_settings_widget_display' ) || get_option( 'wsl_settings_widget_display' ) == 1 || get_option( 'wsl_settings_widget_display' ) == 2 ){
			wsl_render_login_form();
		}
	}
}

add_action( 'comment_form_top', 'wsl_render_comment_form' );

// --------------------------------------------------------------------

// display on login form
function wsl_render_login_form_login_form()
{
	if( get_option( 'wsl_settings_widget_display' ) == 1 || get_option( 'wsl_settings_widget_display' ) == 3 ){
		wsl_render_login_form();
	} 
}

add_action( 'login_form', 'wsl_render_login_form_login_form' );
add_action ('bp_before_account_details_fields', 'wsl_render_login_form_login_form'); 
add_action ('bp_before_sidebar_login_form', 'wsl_render_login_form_login_form');

// --------------------------------------------------------------------

// display on login & register form
function wsl_render_login_form_login_on_register_and_login()
{
	if( get_option( 'wsl_settings_widget_display' ) == 1 ){
		wsl_render_login_form();
	} 
}

add_action( 'register_form', 'wsl_render_login_form_login_on_register_and_login' );
add_action( 'after_signup_form', 'wsl_render_login_form_login_on_register_and_login' );

# }}}

// --------------------------------------------------------------------

# {{{ shortcode, js and css injectors
function wsl_shortcode_handler($args)
{
	if ( ! is_user_logged_in () ){
		wsl_render_login_form();
	}
}

add_shortcode ( 'wordpress_social_login', 'wsl_shortcode_handler' );

// --------------------------------------------------------------------

function wsl_add_javascripts()
{
	if( get_option( 'wsl_settings_use_popup' ) != 1 ){
		return null;
	}

	if( ! wp_script_is( 'wsl_js', 'registered' ) ) {
		wp_register_script( "wsl_js", WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . "/assets/js/connect.js" );
	}

	wp_print_scripts( "jquery" );
	wp_print_scripts( "wsl_js" );
}

add_action( 'login_head', 'wsl_add_javascripts' );
add_action( 'wp_head', 'wsl_add_javascripts' );

// --------------------------------------------------------------------

function wsl_add_stylesheets()
{
	if( ! wp_style_is( 'wsl_css', 'registered' ) ) {
		wp_register_style( "wsl_css", WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . "/assets/css/style.css" ); 
	}

	if ( did_action( 'wp_print_styles' ) ) {
		wp_print_styles( 'wsl_css' ); 
	}
	else{
		wp_enqueue_style( "social_connect" ); 
	}
}

add_action( 'login_head', 'wsl_add_stylesheets' );
add_action( 'wp_head', 'wsl_add_stylesheets' );
# }}}

// --------------------------------------------------------------------

# {{{ linking new accounts 

// render a new widget on wp-admin/profile.php to permit linking profiles 
// only one linked account per provider is permitted!!
function wsl_render_login_form_admin_head_user_profile_generate_html()
{ 
	if ( ! is_user_logged_in() ){
		return;
	}

	// HOOKABLE: allow users to generate their own
	if( apply_filters( 'wsl_hook_profile_widget', null ) ){
		return;
	}

	# if ob_start()/ob_end_clean() dont work for you then i can do nothing for you
	ob_start();

	global $current_user;
	global $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG;

	get_currentuserinfo();

	$user_id = $current_user->ID;

	$linked_accounts = wsl_get_user_linked_accounts_by_user_id( $user_id );

	// if not WSL user, then nothing to show, yet
	if( ! $linked_accounts ){
		return;
	}

	if( empty( $social_icon_set ) ){
		$social_icon_set = "wpzoom/";
	}
	else{
		$social_icon_set .= "/";
	}

	$assets_base_url  = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . '/assets/img/32x32/' . $social_icon_set; 
?> 
<h3><?php _wsl_e("Social networks", 'wordpress-social-login') ?></h3> 
<table class="form-table">  
	<tr>  
		<td valign="top">
			<table id="wsl-user-profile-injected-table-b">
				<tr>
					<th width="80"><?php _wsl_e("Provider", 'wordpress-social-login') ?></th>
					<th><?php _wsl_e("Identity", 'wordpress-social-login') ?></th> 
				</tr>
				<?php
					foreach( $linked_accounts AS $item ){  
						$identity = $item->profileurl;
						$photourl = $item->photourl;
						
						if( ! $identity ){
							$identity = $item->identifier;
						}
				?>
					<tr>
						<td>
							<?php if( $photourl ) { ?>
								<img src="<?php echo $photourl ?>" style="vertical-align: top;width:16px;height:16px;" > 
							<?php } else { ?>
								<img src="<?php echo $assets_base_url . strtolower(  $item->provider ) . '.png' ?>" style="vertical-align: top;width:16px;height:16px;" />
							<?php } ?> 
							<?php echo ucfirst( $item->provider ); ?>
						</td>
						<td><?php echo $identity; ?></td> 
					</tr>
				<?php 
					}
				?>
			</table>
		</td> 
	</tr> 
	</tr> 
<?php
	// Bouncer :: Allow authentication && Linking accounts is enabled
	if( get_option( 'wsl_settings_bouncer_authentication_enabled' ) == 1 && get_option( 'wsl_settings_bouncer_linking_accounts_enabled' ) == 1 ){ 
		$list_connected_providers = wsl_get_list_connected_providers();
?>	
	<tr>    
		<td valign="top">
			<b><?php _wsl_e("Add more identities", 'wordpress-social-login') ?></b>
			<br />
			<?php
				foreach( $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG AS $item ){
					$provider_id   = @ $item["provider_id"];
					$provider_name = @ $item["provider_name"]; 
					$dispaly       = true;

					// only one linked account per provider is permitted!!
					foreach( $linked_accounts AS $link ){
						if( $link->provider == $provider_id ){
							$dispaly = false;
						}
					}

					if( $dispaly ){ 
						$social_icon_set = get_option( 'wsl_settings_social_icon_set' );

						$current_page_url = admin_url("profile.php");

						if( get_option( 'wsl_settings_' . $provider_id . '_enabled' ) ){ 
							?>
							<a href="<?php echo WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL; ?>/services/authenticate.php?provider=<?php echo $provider_id ?>&link=1&redirect_to=<?php echo urlencode($current_page_url) ?>" title="Connect with <?php echo $provider_name ?>"  style="text-decoration:none;" target="_blank">
								<img alt="<?php echo $provider_name ?>" title="<?php echo $provider_name ?>" src="<?php echo $assets_base_url . strtolower( $provider_id ) . '.png' ?>" />
							</a>
							<?php   
						}  
					}
				}
			?>
		</td> 
	</tr> 
<?php
	} 

	if( $list_connected_providers ){
?>
	<tr> 
		<td>
			<b><?php _wsl_e("Currently connected to:", 'wordpress-social-login') ?></b> 
			<?php echo implode( ', ', $list_connected_providers ); ?>
		</td> 
	</tr> 
<?php
	}
?>

</table>
<?php
	$html = ob_get_contents();

	ob_end_clean();

	return addslashes( preg_replace('/\s+/',' ', $html ) );
}

function wsl_render_login_form_admin_head_user_profile()
{
	// HOOKABLE:
	if( apply_filters( 'wsl_hook_alter_render_login_form_admin_head_user_profile', null ) ){
		return;
	}
?> 
	<style> 
		#wsl-user-profile-injected-table-b
		{
			font-family: "Lucida Sans Unicode", "Lucida Grande", Sans-Serif;
			font-size: 12px;
			background: #fff; 
			border-collapse: collapse;
			text-align: left;
		}
		#wsl-user-profile-injected-table-b th
		{
			font-size: 14px;
			font-weight: normal; 
			padding: 10px 8px;
			border-bottom: 2px solid #ccc;
			width: auto;
		}
		#wsl-user-profile-injected-table-b td
		{
			border-bottom: 1px solid #ccc; 
			padding: 6px 8px;
			width: auto;
		}
		#wsl-user-profile-injected-table-b tbody tr:hover td
		{
			color: #009;
		} 
	</style>
	<script>
		jQuery(document).ready(function($)
		{     
			jQuery( '#user_login' )
			.parent()
				.parent()
					.parent()
					.parent()
						.after( '<?php echo wsl_render_login_form_admin_head_user_profile_generate_html() ?>' );
		});
	</script>
<?php
}

add_action( 'admin_head-profile.php', 'wsl_render_login_form_admin_head_user_profile' );  

# }}} linking new accounts

// --------------------------------------------------------------------
