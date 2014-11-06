<?php
/*!
* WordPress Social Login
*
* http://miled.github.io/wordpress-social-login/ | https://github.com/miled/wordpress-social-login
*  (c) 2011-2014 Mohamed Mrassi and contributors | http://wordpress.org/plugins/wordpress-social-login/
*/

/**
* Page for users completing their registration (currently used only by Bouncer::Email Validation)
*
* Note:
* 	1. This file (function) is seduled for deletion in 2.3.
* 	2. Profile Completion will be merged with Accounts linking and replaced with a new feature "New Users Gateway"
*/

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------

function wsl_process_login_complete_registration( $provider, $redirect_to, $hybridauth_user_profile )
{
	// HOOKABLE:
	do_action( "wsl_process_login_complete_registration_start", $provider, $redirect_to, $hybridauth_user_profile );

	$hybridauth_user_email       = sanitize_email( $hybridauth_user_profile->email ); 
	$hybridauth_user_login       = sanitize_user( $hybridauth_user_profile->displayName, true );
	$hybridauth_user_avatar      = $hybridauth_user_profile->photoURL;

	$request_user_login          = isset( $_REQUEST["user_login"] ) ? $_REQUEST["user_login"] : '';
	$request_user_email          = isset( $_REQUEST["user_email"] ) ? $_REQUEST["user_email"] : '';

	$request_user_login          = sanitize_user( $request_user_login, true );
	$request_user_email          = sanitize_email( $request_user_email );

	$request_user_login          = trim( str_replace( array( ' ', '.' ), '_', $request_user_login ) );
	$request_user_login          = trim( str_replace( '__', '_', $request_user_login ) ); 

	$request_user_login_exists   = username_exists ( $request_user_login );
	$request_user_email_exists   = wsl_wp_email_exists ( $request_user_email ); 

	$request_user_login_validate = validate_username ( $request_user_login );
	$request_user_email_validate = filter_var( $request_user_email, FILTER_VALIDATE_EMAIL ) ;

	if( empty( $request_user_login ) ) $request_user_login_validate = false;
	if( empty( $request_user_email ) ) $request_user_email_validate = false;

	if( empty( $request_user_login ) ) $request_user_login = $hybridauth_user_login;
	if( empty( $request_user_email ) ) $request_user_email = $hybridauth_user_email;

	$shall_pass = true;
	$shall_pass_errors = array();

	// well until brain become able to compute again..
	if( get_option( 'wsl_settings_bouncer_profile_completion_require_email' ) == 1 )
	{
		if( ! $request_user_email )
		{
			$shall_pass = false;

			$shall_pass_errors[ _wsl__("E-mail is not valid!", 'wordpress-social-login') ] = true;
		}

		if( ! $request_user_email_validate )
		{
			$shall_pass = false;

			$shall_pass_errors[ _wsl__("E-mail is not valid!", 'wordpress-social-login') ] = true;
		}

		if( $request_user_email_exists )
		{
			$shall_pass = false;

			$shall_pass_errors[ _wsl__("That E-mail is already registered!", 'wordpress-social-login') ] = true;
		}
	}

	if( get_option( 'wsl_settings_bouncer_profile_completion_change_username' ) == 1 )
	{
		if( ! $request_user_login )
		{
			$shall_pass = false;

			$shall_pass_errors[ _wsl__("Username is not valid!", 'wordpress-social-login') ] = true;
		}

		if( ! $request_user_login_validate )
		{
			$shall_pass = false;

			$shall_pass_errors[ _wsl__("Username is not valid!", 'wordpress-social-login') ] = true;
		}

		if( $request_user_login_exists )
		{
			$shall_pass = false;

			$shall_pass_errors[ _wsl__("That Username is already registered!", 'wordpress-social-login') ] = true;
		}
	}

	if( ! $shall_pass )
	{
?> 
<!DOCTYPE html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title><?php echo get_bloginfo('name'); ?></title>
	<head>
		<style> 
			html, body {
				height: 100%;
				margin: 0;
				padding: 0;
			}
			body {
				background: none repeat scroll 0 0 #f1f1f1;
				font-size: 14px;
				color: #444;
				font-family: "Open Sans",sans-serif;
			}
			hr {
				border-color: #eeeeee;
				border-style: none none solid;
				border-width: 0 0 1px;
				margin: 2px 0 0;
			}
			h4 {
				font-size: 14px;
				margin-bottom: 10px;
			}
			#login {
				width: 585px;
				margin: auto;
				padding: 114px 0 0;	
			}
			#login-panel {
				background: none repeat scroll 0 0 #fff;
				box-shadow: 0 1px 3px rgba(0, 0, 0, 0.13);
				margin: 2em auto;
				box-sizing: border-box;
				display: inline-block;
				padding: 70px 0 15px;
				position: relative;
				text-align: center;
				width: 100%;
			}
			#avatar {
				margin-left: 213px;
				top: -82px;
				padding: 4px;
				position: absolute;
			}
			#avatar img {
				background: none repeat scroll 0 0 #fff;
				border: 3px solid #f1f1f1;
				border-radius: 75px !important;
				box-shadow: 0 1px 3px rgba(0, 0, 0, 0.13);
				height: 145px;
				width: 145px;
			}
			#welcome {
				margin: 15px 20px 15px;
			}
			#idp-icon {
				position: absolute;
				margin-top: 2px;
				margin-left: -19px;
			}
			#login-form{
				margin: 0;
				padding: 0;
			}
			.button-primary {
				background-color: #21759b;
				background-image: linear-gradient(to bottom, #2a95c5, #21759b);
				border-color: #21759b #21759b #1e6a8d;
				border-radius: 3px;
				border-style: solid;
				border-width: 1px;
				box-shadow: 0 1px 0 rgba(120, 200, 230, 0.5) inset;
				box-sizing: border-box;
				color: #fff;
				cursor: pointer;
				display: inline-block;
				float: none;
				font-size: 13px;
				height: 32px;
				line-height: 23px;
				margin: 0;
				padding: 0 10px 1px;
				text-decoration: none;
				text-shadow: 0 1px 0 rgba(0, 0, 0, 0.1);
				white-space: nowrap;
			}
			button-primary:focus, .button-primary:hover{
				background:#1e8cbe;
				border-color:#0074a2;
				-webkit-box-shadow:inset 0 1px 0 rgba(120,200,230,.6);
				box-shadow:inset 0 1px 0 rgba(120,200,230,.6);
				color:#fff
			}
			input[type="text"]{
				border: 1px solid #e5e5e5;
				box-shadow: 1px 1px 2px rgba(200, 200, 200, 0.2) inset;
				color: #555;
				font-size: 17px;
				height: 30px;
				line-height: 1;
				margin-bottom: 16px;
				margin-right: 6px;
				margin-top: 2px;
				outline: 0 none;
				padding: 3px;
				width: 99%;
			}
			input[type="text"]:focus{
				border-color:#5b9dd9;
				-webkit-box-shadow:0 0 2px rgba(30,140,190,.8);
				box-shadow:0 0 2px rgba(30,140,190,.8)
			}
			input[type="submit"]{
				float:right;
			}
			label{
				color:#777;
				font-size:14px;
				cursor:pointer;
				vertical-align:middle;
				text-align: left;
			}
			table {
				width:485px;
				margin-left:auto; 
				margin-right:auto;
			}
			table p{
				margin-top:0;
				margin-bottom:0;
			}
			#mapping-complete-info {
				
			}
			#error {
				background-color: #fff;
				border: 1px solid #dd3d36;
				border-left: 4px solid #dd3d36;
				box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.1);
				margin: 0 21px;
				margin: 0;
				margin-bottom:8px;
				padding: 12px;	
				text-align: left;
			}
			.back-to-options {
				float: left;
				margin: 7px 0px;
			}
			.back-to-home {
				font-size: 12px;
				margin-top: -18px;
			}
			.back-to-home a {
				color: #999;
				text-decoration: none;
			}
		</style>
		<script>
			function init()
			{
				if( document.getElementById('user_login') ) document.getElementById('user_login').focus()
				if( document.getElementById('user_email') ) document.getElementById('user_email').focus()
			}
		</script>
	</head>
	<body onload="init();"> 
		<div id="login">
			<div id="login-panel">
				<div id="avatar">
					<img src="<?php echo $hybridauth_user_avatar; ?>">
				</div>

				<div id="welcome">
					<p>
						<?php printf( _wsl__( "Hi %s, you're one step away from completing your account.", 'wordpress-social-login' ), htmlentities( $hybridauth_user_profile->displayName ) ); ?>
					</p>
					<p>
						<?php _wsl_e( "Please, fill in your information in the form below to continue", 'wordpress-social-login' ); ?>.
					</p>
				</div>

				<form method="post" action="<?php echo site_url( 'wp-login.php', 'login_post' ); ?>" id="login-form"> 
					<table id="mapping-complete-info" border="0">
						<tr>
							<td>
								<?php
									if( isset( $_REQUEST["bouncer_profile_completion"] ) && $shall_pass_errors )
									{ 
										echo '<div id="error">';

										foreach( $shall_pass_errors as $k => $v )
										{
											?><p><?php echo $k; ?></p><?php
										}

										echo '</div>';
									} 
								?>
							</td>
						</tr>
						<tr>
							<td valign="bottom"  width="50%" style="text-align:left;">
								<?php
									if( get_option( 'wsl_settings_bouncer_profile_completion_change_username' ) == 1 )
									{
								?>
									<p>
										<label for="user_login"><?php _wsl_e( "Username", 'wordpress-social-login' ); ?><br><input type="text" name="user_login" id="user_login" class="input" value="<?php echo $request_user_login; ?>" size="25" /></label>
									</p>
								<?php
									}

									if( get_option( 'wsl_settings_bouncer_profile_completion_require_email' ) == 1 )
									{
								?>
									<p>
										<label for="user_email"><?php _wsl_e( "E-mail", 'wordpress-social-login' ); ?><br><input type="text" name="user_email" id="user_email" class="input" value="<?php echo $request_user_email ?>" size="25" /></label>
									</p>
								<?php
									}
								?>

								<input type="submit" value="<?php _wsl_e( "Continue", 'wordpress-social-login' ); ?>" class="button-primary" > 
							</td>
						</tr>
					</table> 

					<input type="hidden" id="redirect_to" name="redirect_to" value="<?php echo $redirect_to ?>"> 
					<input type="hidden" id="provider" name="provider" value="<?php echo $provider ?>"> 
					<input type="hidden" id="action" name="action" value="wordpress_social_profile_completion">
					<input type="hidden" id="bouncer_profile_completion" name="bouncer_profile_completion" value="1">
				</form>
			</div>

			<p class="back-to-home">
				<a href="<?php echo site_url(); ?>">&#8592; <?php printf( _wsl__( "Back to %s", 'wordpress-social-login' ), get_bloginfo('name') ); ?></a>
			</p>
		</div>

		<?php 
			// Development mode on?
			if( get_option( 'wsl_settings_development_mode_enabled' ) )
			{
				wsl_display_dev_mode_debugging_area();
			}
		?> 
	</body>
</html>
<?php
		die();
	}

	return array( $shall_pass, $request_user_login, $request_user_email );
}

// --------------------------------------------------------------------
