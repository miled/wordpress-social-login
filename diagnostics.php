<?php 
	require_once( dirname( dirname( dirname( dirname( __FILE__ )))) . '/wp-load.php' );
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
</head>
<body>
	<div class="wrap">
		<h2>Social Login Plugin Diagnostics</h2> 
		
		<p>
			<h3>1. URL Rewrite</h3> 
<?php
	# {{{ Rewrite Diagnostics ? 
		$testing = @ $_REQUEST['url'];

		if ( $testing == "http://www.example.com" ) {
			echo "<b style='color:green;'>OK!</b> The rewrite rules on your server appear to be setup correctly for this plugin to work.";
		}
		else{ 
			echo sprintf( '<b style="color:red;">FAIL!</b> Expected "http://www.example.com", received "%s".', $testing );
		} 
	# }}} end Rewrite Diagnostics 
?>
		</p>
 
		<p>
			<h3>2. System Requirements</h3> 
<?php
	# {{{ System Requirements ?
		// check for php 5.2
		echo "<h4>2.1 - PHP 5.2</h4>";

		if ( version_compare( PHP_VERSION, '5.2.0', '>=' ) ){
			echo "<b style='color:green;'>OK!</b> PHP >= 5.2.0 installed.";
		}
		else{ 
			echo "<b style='color:red;'>FAIL!</b> PHP >= 5.2.0 not installed.";
		}

		// PHP Curl extension [http://www.php.net/manual/en/intro.curl.php] 
		echo "<h4>2.2 - CURL Extension</h4>";

		if ( function_exists('curl_init') ) {
			echo "<b style='color:green;'>OK!</b> PHP Curl extension [http://www.php.net/manual/en/intro.curl.php] installed.";
		}
		else{ 
			echo "<b style='color:red;'>FAIL!</b> PHP Curl extension [http://www.php.net/manual/en/intro.curl.php] not installed.";
		} 

		// PHP JSON extension [http://php.net/manual/en/book.json.php]
		echo "<h4>2.3 - JSON Extension</h4>";

		if ( function_exists('json_decode') ) {
			echo "<b style='color:green;'>OK!</b> PHP JSON extension [http://php.net/manual/en/book.json.php] installed.";
		}
		else{ 
			echo "<b style='color:red;'>FAIL!</b> PHP JSON extension [http://php.net/manual/en/book.json.php] is disabled.";
		} 
 
		// OAuth PECL extension is not compatible with this library
		echo "<h4>2.4 - PECL OAuth Extension</h4>";

		if( ! extension_loaded('oauth') ) {
			echo "<b style='color:green;'>OK!</b> PECL OAuth extension [http://php.net/manual/en/book.oauth.php] not installed.";
		}
		else{ 
			echo "<b style='color:red;'>FAIL!</b> PECL OAuth extension [http://php.net/manual/en/book.oauth.php] installed. OAuth PECL extension is not compatible with this library.";
		} 
	# }}} end System Requirements
?>
		</p>

		<hr />

		<p>
			End of Diagnostics!
		</p>
	</div>  
	
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-11037160-1");
pageTracker._trackPageview();
} catch(err) {}</script>
<script type="text/javascript"> var sc_project=7312365; var sc_invisible=1; var sc_security="30da00f3"; </script>
<script type="text/javascript" src="http://www.statcounter.com/counter/counter.js"></script> 

</body>
</html>
