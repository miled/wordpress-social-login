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

		parent::processAuthStart();
	}

	public static function processAuthDone()
	{
		WSL_Hybrid_Endpoint::authInit();

		parent::processAuthDone();
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
				header( 'HTTP/1.0 406 Not Acceptable' );

				WSL_Hybrid_Endpoint::dieError( 'The session identifier is missing.<br />Please check WordPress Social Login <a href="http://miled.github.io/wordpress-social-login/overview.html" target="_blank">Minimum system requirements</a> and <a href="http://miled.github.io/wordpress-social-login/faq.html" target="_blank">FAQ</a>.' );
			}

			# Init Hybrid_Auth
			try{
				Hybrid_Auth::initialize( $storage->config( "CONFIG" ) ); 
			}
			catch ( Exception $e ){
				header( 'HTTP/1.0 500 Internal Server Error' );
				WSL_Hybrid_Endpoint::dieError( 'An error occurred while attempting to initialize Hybridauth' );
			}
		}
	}

	public static function dieError( $message )
	{
		#{{{
		# This 7 LLOC should be executed only once every three millennium.
		# It means either : 1. Php Sessions ain't working as expected. 2. A crawler got lost. 3. Someone is having fun forging urls.
		# If wp-load.php does exists in another directory, change it manually. From now on, you're on your own. Goodbye.
		display_wsl_error( $message );

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
function display_wsl_error( $message )
{
?>
<!DOCTYPE html>
	<head>
		<meta name="robots" content="NOINDEX, NOFOLLOW">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>WSL Endpoint</title>
		<style type="text/css">
			html {
				background: #f1f1f1;
			}
			a {
				color: #21759B;
				text-decoration: none;
			}
			#notice-page {
				background: #fff;
				color: #444;
				font-family: "Open Sans", sans-serif;
				margin: 2em auto;
				max-width: 700px;
				-webkit-box-shadow: 0 1px 3px rgba(0,0,0,0.13);
				box-shadow: 0 1px 3px rgba(0,0,0,0.13);
				margin-top: 50px;
				text-align:center;
				padding: 20px;
			}
		</style>
	<head>  
	<body id="notice-page"> 
		<?php echo nl2br( $message ); ?>
	</body>
</html> 
<?php
}