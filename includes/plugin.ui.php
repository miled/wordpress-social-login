<?php
function wsl_render_login_form()
{
	GLOBAL $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG;

	$wsl_settings_connect_with_label = get_option( 'wsl_settings_connect_with_label' );
	
	if( empty( $wsl_settings_connect_with_label ) ){
		$wsl_settings_connect_with_label = "Connect with:";
	}
?>

<!--
   wsl_render_login_form
   WordPress Social Login Plugin ( <?php echo $_SESSION["wsl::plugin"] ?> ) 
   http://wordpress.org/extend/plugins/wordpress-social-login/
-->
	<span id="wp-social-login-connect-with"><?php echo $wsl_settings_connect_with_label ?></span>
	<div id="wp-social-login-connect-options">
<?php 
	$nok = true;

	// display provider icons
	foreach( $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG AS $item ){
		$provider_id     = @ $item["provider_id"];
		$provider_name   = @ $item["provider_name"];

		$social_icon_set = get_option( 'wsl_settings_social_icon_set' );

		if( empty( $social_icon_set ) ){
			$social_icon_set = "wpzoom/";
		}
		else{
			$social_icon_set .= "/";
		}

		$assets_base_url  = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . '/assets/img/32x32/' . $social_icon_set; 
		$current_page_url = 'http';
		if ($_SERVER["HTTPS"] == "on") {$current_page_url .= "s";}
		$current_page_url .= "://";
		if ($_SERVER["SERVER_PORT"] != "80") {
		$current_page_url .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} else {
		$current_page_url .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		} 
 
		if( get_option( 'wsl_settings_' . $provider_id . '_enabled' ) ){
			if( get_option( 'wsl_settings_use_popup' ) == 1 || ! get_option( 'wsl_settings_use_popup' ) ){
				?>
				<a href="javascript:void(0);" title="Connect with <?php echo $provider_name ?>" class="wsl_connect_with_provider" provider="<?php echo $provider_id ?>">
					<img alt="<?php echo $provider_name ?>" title="<?php echo $provider_name ?>" src="<?php echo $assets_base_url . strtolower( $provider_id ) . '.png' ?>" />
				</a>
				<?php
			}
			elseif( get_option( 'wsl_settings_use_popup' ) == 2 ){ 
				?>
				<a href="<?php echo WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL; ?>/authenticate.php?provider=<?php echo $provider_id ?>&redirect_to=<?php echo urlencode($current_page_url) ?>" title="Connect with <?php echo $provider_name ?>" class="wsl_connect_with_provider" >
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
			<strong style="color:red;">WordPress Social Login is not configured yet!</strong>
			<br />
			Please visit the <strong>Settings\ WP Social Login</strong> administration page to configure this plugin.
			<br />
			For more information please refer to the plugin <a href="http://hybridauth.sourceforge.net/userguide/Plugin_WordPress_Social_Login.html">online user guide</a> 
			or contact us at <a href="http://hybridauth.sourceforge.net/">hybridauth.sourceforge.net</a>
		</p>
		<style>
			#wp-social-login-connect-with{display:none;}
		</style>
		<?php
	}

	// provide popup url for hybridauth callback
	?>
		<input id="wsl_popup_base_url" type="hidden" value="<?php echo WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL; ?>/authenticate.php?" />
		<input type="hidden" id="wsl_login_form_uri" value="<?php echo site_url( 'wp-login.php', 'login_post' ); ?>" />
	</div> 
<!-- /wsl_render_login_form -->

<?php
}

function wsl_render_login_form_login()
{
	wsl_render_login_form();
}

add_action( 'login_form', 'wsl_render_login_form_login' );
add_action( 'register_form', 'wsl_render_login_form_login' );
add_action( 'after_signup_form', 'wsl_render_login_form_login' );
add_action( 'wordpress_social_login', 'wsl_render_login_form_login' );


function wsl_shortcode_handler ($args)
{
	if ( ! is_user_logged_in () ){
		wsl_render_login_form();
	}
}

add_shortcode ( 'wordpress_social_login', 'wsl_shortcode_handler' );

function wsl_render_comment_form()
{
	if( comments_open() && ! is_user_logged_in() ) {
		wsl_render_login_form();
	}
}

add_action( 'comment_form_top', 'wsl_render_comment_form' );

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

function wsl_add_stylesheets(){
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

/**
 * Display custom avatars
 * borrowed from http://wordpress.org/extend/plugins/oa-social-login/
 *
 * Improved by <jlnd>
 * http://wordpress.org/support/profile/jlnd
 *
 * fix by <seand11>, <kekrug>
 * http://wordpress.org/support/topic/plugin-wordpress-social-login-avatars-getting-cached-and-users-seeing-other-avatars-as-their-own
 *
 * thanks a million
 */
function wsl_user_custom_avatar($avatar, $id_or_email, $size, $default, $alt) {
	global $comment;

	if( get_option ('wsl_settings_users_avatars') && !empty ($avatar)) {
		if(!empty ($id_or_email)) {
			if ( is_numeric($id_or_email) ) {
				$user_id = (int) $id_or_email;
			}
			elseif ( is_string( $id_or_email ) && ( $user = get_user_by( 'email', $id_or_email ) ) ) {
				$user_id = $user->ID;
			}
			elseif ( is_object( $id_or_email ) && ! empty( $id_or_email->user_id ) ) {
				$user_id = (int) $id_or_email->user_id;
			}
		}

		//Check if we are in a comment
		if (!is_null ($comment) && !empty ($comment->user_id)) {
			$user_id = $comment->user_id;
		}

		// Get the thumbnail provided by WordPress Social Login
		if ($user_id) {
			if (($user_thumbnail = get_user_meta ($user_id, 'wsl_user_image', true)) !== false) {
				if (strlen (trim ($user_thumbnail)) > 0) {
					$user_thumbnail = preg_replace ('#src=([\'"])([^\\1]+)\\1#Ui', "src=\\1" . $user_thumbnail . "\\1", $avatar);

					return $user_thumbnail;
				}
			}
		}
	}

	// No avatar found. Return unfiltered.
	return $avatar;
}

add_filter ( 'get_avatar', 'wsl_user_custom_avatar', 10, 5);
