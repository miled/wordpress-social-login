<?php
/*!
* WordPress Social Login
*
* https://miled.github.io/wordpress-social-login | http://github.com/miled/wordpress-social-login
*  (c) 2011-2014 Mohamed Mrassi and contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

class WSL_Hybrid_Endpoint extends Hybrid_Endpoint
{
	public static function process( $request = NULL )
	{
		// Setup request variable
		Hybrid_Endpoint::$request = $request;

		if ( is_null(Hybrid_Endpoint::$request) ){
			// Fix a strange behavior when some provider call back ha endpoint
			// with /index.php?hauth.done={provider}?{args}... 
			// >here we need to recreate the $_REQUEST
			if ( strrpos( $_SERVER["QUERY_STRING"], '?' ) ) {
				$_SERVER["QUERY_STRING"] = str_replace( "?", "&", $_SERVER["QUERY_STRING"] );

				parse_str( $_SERVER["QUERY_STRING"], $_REQUEST );
			}

			Hybrid_Endpoint::$request = $_REQUEST;
		}

		// If we get a hauth.start
		if ( isset( WSL_Hybrid_Endpoint::$request["hauth_start"] ) && WSL_Hybrid_Endpoint::$request["hauth_start"] ) {
			return WSL_Hybrid_Endpoint::processAuthStart();
		}

		// Else if hauth.done
		elseif ( isset( WSL_Hybrid_Endpoint::$request["hauth_done"] ) && WSL_Hybrid_Endpoint::$request["hauth_done"] ) {
			return WSL_Hybrid_Endpoint::processAuthDone();
		}
		
		parent::process( $request );
	}

	public static function processAuthStart()
	{
		WSL_Hybrid_Endpoint::authInit();

		try {
			parent::processAuthStart();
		}
		catch( Exception $e ){
			WSL_Hybrid_Endpoint::dieError( "412 Precondition Failed", $e->getMessage() . "<br />For more information refer to WSL <a href='http://miled.github.io/wordpress-social-login/troubleshooting.html' target='_blank'>Troubleshooting</a>" );
		}
	}

	public static function processAuthDone()
	{
		WSL_Hybrid_Endpoint::authInit();

		try {
			parent::processAuthDone();
		}
		catch( Exception $e ){
			WSL_Hybrid_Endpoint::dieError( "410 Gone", $e->getMessage() . "<br />For more information refer to WSL <a href='http://miled.github.io/wordpress-social-login/troubleshooting.html' target='_blank'>Troubleshooting</a>" );
		}
	}

	public static function authInit()
	{
		$storage = new Hybrid_Storage();

		header( 'X-Hybridauth-Version: ' . $storage->config( "version" ) );
		header( 'X-Hybridauth-Time: ' . time() );
		header( 'X-Hybridauth-Init: ' . strlen( json_encode( $storage->config( "CONFIG" ) ) ) );

		if ( ! WSL_Hybrid_Endpoint::$initDone ){
			WSL_Hybrid_Endpoint::$initDone = TRUE;

			// Check if Hybrid_Auth session already exist
			if ( ! $storage->config( "CONFIG" ) ) {
				WSL_Hybrid_Endpoint::dieError( "406 Not Acceptable", "The session identifier is missing.<br />For more information refer to WSL <a href='http://miled.github.io/wordpress-social-login/troubleshooting.html' target='_blank'>Troubleshooting</a>. <img style='width: 35px; height: 35px; position: absolute; bottom: 20px; right: 20px;' src='data:image/png;base64,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' />" );
			}

			# Init Hybrid_Auth
			try{
				Hybrid_Auth::initialize( $storage->config( "CONFIG" ) ); 
			}
			catch ( Exception $e ){
				WSL_Hybrid_Endpoint::dieError( '500 Internal Server Error', 'An error occurred while attempting to initialize Hybridauth' );
			}
		}
	}

	public static function dieError( $code, $message )
	{
		#{{{
		# This 7 LLOC should be executed only once every three millennium.
		# It means either : 1. Php Sessions ain't working as expected. 2. A crawler got lost. 3. Someone is having fun forging urls.
		# If wp-load.php does exists in another directory, change it manually. From now on, you're on your own. Goodbye.
		display_wsl_error( $code, $message );

		$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );

		if( file_exists( $parse_uri[0] . 'wp-load.php' ) )
		{
			require_once( $parse_uri[0] . 'wp-load.php' );

			if( get_option( 'wsl_settings_development_mode_enabled' ) )
			{
				wsl_display_dev_mode_debugging_area();
			}
		}

		die();
		#}}}
	}
}

/**
* Display a nicer error page.
*/
function display_wsl_error( $code, $message )
{
	header( 'HTTP/1.0 '. $code );
?>
<!DOCTYPE html>
	<head>
		<meta name="robots" content="noindex, nofollow">
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>Oops</title>
		<style type="text/css">
			* {
				margin: 0;
				padding: 0;
			}
			body {
				background: #333;
			}
			h1 {
				color: white;
				font: 45px 'Open Sans';
				padding: 30px;
			}
			p {
				color: white;
				font: 15px 'Open Sans';
				padding: 0 30px;
			}
			a {
				color: white;
			}
		</style>
	<head>  
	<body id="notice-page"> 
		<h1>WordPress Social Login Endpoint.</h1>

		<p>
			<?php echo (int) substr( $code, 0, 3 ); ?>. <?php echo $message; ?>
		</p>
	</body>
</html> 
<?php
}
