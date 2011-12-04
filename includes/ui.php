<?php
function wsl_render_login_form()
{
	GLOBAL $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG;

	// display "Or connect with" message, or not.. ?
?>
	<span id="wp-social-login-connect-with">Connect with:</span>
	<div id="wp-social-login-connect-options">
<?php 

	// display provider icons
	foreach( $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG AS $item ){
		$provider_id     = @ $item["provider_id"];
		$provider_name   = @ $item["provider_name"];

		$assets_base_url = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . '/assets/img/32x32/';
 
		if( get_option( 'wsl_settings_' . $provider_id . '_enabled' ) ){
			?>
			<a href="javascript:void(0);" title="Connect with <?php echo $provider_name ?>" class="wsl_connect_with_provider" provider="<?php echo $provider_id ?>">
				<img alt="<?php echo $provider_name ?>" title="<?php echo $provider_name ?>" src="<?php echo $assets_base_url . strtolower( $provider_id ) . '.png' ?>" />
			</a>
			<?php
		} 
	} 

	// provide popup url for hybridauth callback
	?>
		<input id="wsl_popup_base_url" type="hidden" value="<?php echo WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL; ?>/authenticate.php?" />
		<input type="hidden" id="wsl_login_form_uri" value="<?php echo site_url( 'wp-login.php', 'login_post' ); ?>" />
	</div>
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
