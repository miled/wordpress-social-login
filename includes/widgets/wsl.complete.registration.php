<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*    (c) 2011-2013 Mohamed Mrassi and contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

/**
* Page for users completing their registration (currently used only by Bouncer::Email Validation
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------

function wsl_process_login_complete_registration( $provider, $redirect_to, $hybridauth_user_email, $hybridauth_user_login )
{
	// print_r( "$provider, $redirect_to, $hybridauth_user_email, $hybridauth_user_login" );

	$assets_base_url = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . '/assets/img/16x16/';

	// check posted user email & login
	$request_user_login          = @ $_REQUEST["user_login"];
	$request_user_email          = @ $_REQUEST["user_email"];

	$request_user_login          = sanitize_user( $request_user_login );
	$request_user_email          = sanitize_email( $request_user_email );

	$request_user_login_exists   = username_exists ( $request_user_login );
	$request_user_email_exists   = email_exists ( $request_user_email ); 

	$request_user_login_validate = validate_username ( $request_user_login );
	$request_user_email_validate = filter_var( $request_user_email, FILTER_VALIDATE_EMAIL ) ;

	if( empty( $request_user_login ) ) $request_user_login_validate = false;
	if( empty( $request_user_email ) ) $request_user_email_validate = false;

	if( empty( $request_user_login ) ) $request_user_login = $hybridauth_user_login;
	if( empty( $request_user_email ) ) $request_user_email = $hybridauth_user_email;

	$shall_pass = true;
	$shall_pass_errors = array();

	// well until brain become able to compute again..
	if( get_option( 'wsl_settings_bouncer_profile_completion_require_email' ) == 1 ){
		if( ! $request_user_email ){
			$shall_pass = false;

			$shall_pass_errors[ get_option( 'wsl_settings_bouncer_profile_completion_text_email_invalid' ) ] = true;
		}

		if( ! $request_user_email_validate ){
			$shall_pass = false;

			$shall_pass_errors[ get_option( 'wsl_settings_bouncer_profile_completion_text_email_invalid' ) ] = true;
		}

		if( $request_user_email_exists ){
			$shall_pass = false;

			$shall_pass_errors[ get_option( 'wsl_settings_bouncer_profile_completion_text_email_exists' ) ] = true;
		}
	}

	if( get_option( 'wsl_settings_bouncer_profile_completion_change_username' ) == 1 ){
		if( ! $request_user_login ){
			$shall_pass = false;

			$shall_pass_errors[ get_option( 'wsl_settings_bouncer_profile_completion_text_username_invalid' ) ] = true;
		}

		if( ! $request_user_login_validate ){
			$shall_pass = false;

			$shall_pass_errors[ get_option( 'wsl_settings_bouncer_profile_completion_text_username_invalid' ) ] = true;
		}

		if( $request_user_login_exists ){
			$shall_pass = false;

			$shall_pass_errors[ get_option( 'wsl_settings_bouncer_profile_completion_text_username_exists' ) ] = true;
		}
	}

	if( ! $shall_pass ){
?> 
<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo get_bloginfo('name'); ?></title>
<head>
<style> 
body.login{background:0 repeat scroll 0 0 #fbfbfb;min-width:0}body,#wpbody,.form-table .pre,.ui-autocomplete li a{color:#333}body{font-family:sans-serif;font-size:12px;line-height:1.4em;min-width:600px}html,body{height:100%;margin:0;padding:0}#login{margin:auto;padding:114px 0 0;width:320px}div.updated,.login .message{background-color:#ffffe0;border-color:#e6db55}.message{margin:0 0 16px 8px;padding:12px;border-radius:3px 3px 3px 3px;border-style:solid;border-width:1px}.info{font-family:sans-serif;font-size:12px;line-height:1.4em}.login form{background:0 repeat scroll 0 0 #fff;border:1px solid #e5e5e5;box-shadow:0 4px 10px -1px rgba(200,200,200,.7);font-weight:400;margin-left:8px;padding:26px 24px 46px;border-radius:3px 3px 3px 3px}.login label{color:#777;font-size:14px;cursor:pointer;vertical-align:middle}input[type="text"]{background:0 repeat scroll 0 0 #fbfbfb;border:1px solid #e5e5e5;box-shadow:1px 1px 2px rgba(200,200,200,.2) inset;color:#555;font-size:24px;font-weight:200;line-height:1;margin-bottom:16px;margin-right:6px;margin-top:2px;outline:0 none;padding:3px;width:100%}.button-primary{display:inline-block;text-decoration:none;font-size:12px;line-height:23px;height:24px;margin:0;padding:0 10px 1px;cursor:pointer;border-width:1px;border-style:solid;-webkit-border-radius:3px;-webkit-appearance:none;border-radius:3px;white-space:nowrap;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;background-color:#21759b;background-image:-webkit-gradient(linear,left top,left bottom,from(#2a95c5),to(#21759b));background-image:-webkit-linear-gradient(top,#2a95c5,#21759b);background-image:-moz-linear-gradient(top,#2a95c5,#21759b);background-image:-ms-linear-gradient(top,#2a95c5,#21759b);background-image:-o-linear-gradient(top,#2a95c5,#21759b);background-image:linear-gradient(to bottom,#2a95c5,#21759b);border-color:#21759b;border-bottom-color:#1e6a8d;-webkit-box-shadow:inset 0 1px 0 rgba(120,200,230,.5);box-shadow:inset 0 1px 0 rgba(120,200,230,.5);color:#fff;text-decoration:none;text-shadow:0 1px 0 rgba(0,0,0,.1);float:right;height:36px;}#login{width:580px}.error{margin:0 0 16px 8px;padding:12px;border-radius:3px 3px 3px 3px;border-style:solid;border-width:1px;background-color: #FFEBE8;border:1px solid #CC0000;}
</style>
<script>
function init() {
	if( document.getElementById('user_login') ) document.getElementById('user_login').focus()
	if( document.getElementById('user_email') ) document.getElementById('user_email').focus()
}
</script>
<body class="login" onload="init();">
<!--
   wsl_process_login_complete_registration
   WordPress Social Login Plugin ( <?php echo $_SESSION["wsl::plugin"] ?> ) 
   http://wordpress.org/extend/plugins/wordpress-social-login/
-->
	<div id="login">
		<?php
			if( ! isset( $_REQUEST["bouncer_profile_completion"] ) ){ 
				?><p class="message"><?php echo get_option( 'wsl_settings_bouncer_profile_completion_text_notice' ); ?></p><?php
			}
			elseif( $shall_pass_errors ){ 
				foreach( $shall_pass_errors as $k => $v ){
					?><p class="error"><?php echo $k; ?></p><?php
				}
			} 
		?>
		<form method="post" action="<?php echo site_url( 'wp-login.php', 'login_post' ); ?>" id="loginform" name="loginform"> 
			<?php if( get_option( 'wsl_settings_bouncer_profile_completion_change_username' ) == 1 ){ ?>
			<p>
			<label for="user_login"><?php echo get_option( 'wsl_settings_bouncer_profile_completion_text_username' ); ?><br><input type="text" name="user_login" id="user_login" class="input" value="<?php echo $hybridauth_user_login ?>" size="25" /></label>
			</p>
			<?php } ?>

			<?php if( get_option( 'wsl_settings_bouncer_profile_completion_require_email' ) == 1 ){ ?>
			<p>
			<label for="user_email"><?php echo get_option( 'wsl_settings_bouncer_profile_completion_text_email' ); ?><br><input type="text" name="user_email" id="user_email" class="input" value="<?php echo $request_user_email ?>" size="25" /></label>
			</p>
			<?php } ?>

			<table width="100%" border="0">
				<tr>
					<td valign="bottom">
						<span class="info">
							<img src="<?php echo $assets_base_url . strtolower( $provider ) . '.png' ?>" style="vertical-align: top;width:16px;height:16px;" />
							<?php echo get_option( 'wsl_settings_bouncer_profile_completion_text_connected_with' ); ?> <b><?php echo ucfirst($provider) ?></b>.
						</span>
					</td>
					<td>
						<input type="submit" value="<?php echo get_option( 'wsl_settings_bouncer_profile_completion_text_submit_button' ); ?>" class="button button-primary button-large" id="wp-submit" name="wp-submit"> 
					</td>
				</tr>
			</table>
			<input type="hidden" id="redirect_to" name="redirect_to" value="<?php echo $redirect_to ?>"> 
			<input type="hidden" id="provider" name="provider" value="<?php echo $provider ?>"> 
			<input type="hidden" id="action" name="action" value="wordpress_social_login">
			<input type="hidden" id="bouncer_profile_completion" name="bouncer_profile_completion" value="1">
		</form>
	</div> 
</body>
</html> 
<?php
		die();
	}

	return array( $shall_pass, $request_user_login, $request_user_email );
}

// --------------------------------------------------------------------
