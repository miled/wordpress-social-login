<?php
	session_start();

	// let display a loading message. should be better than a white screen
	if( isset( $_GET["provider"] ) && ! isset( $_GET["redirect_to_provider"] )){
		// selected provider 
		$provider = @ trim( strip_tags( $_GET["provider"] ) ); 
?>
<table width="100%" border="0">
  <tr>
    <td align="center" height="200px" valign="middle"><img src="assets/img/loading.gif" /></td>
  </tr>
  <tr>
    <td align="center"><br /><h3>Loading...</h3><br /></td> 
  </tr>
  <tr>
    <td align="center">Contacting <b><?php echo ucfirst( $provider ) ; ?></b>, please wait...</td> 
  </tr> 
</table>
<script> 
	setTimeout( function(){window.location.href = window.location.href + "&redirect_to_provider=ture"}, 750 );
</script>
<?php
		die();
	} // end display loading 

	// if user select a provider to login with 
	// and redirect_to_provider eq ture
	if( isset( $_GET["provider"] ) && isset( $_GET["redirect_to_provider"] )){
		try{
			// load hybridauth
			require_once( dirname(__FILE__) . "/hybridauth/Hybrid/Auth.php" );

			// load wp-load.php
			require_once( dirname( dirname( dirname( dirname( __FILE__ )))) . '/wp-load.php' );

			// selected provider name 
			$provider = @ trim( strip_tags( $_GET["provider"] ) );

			// build required configuratoin for this provider
			if( ! get_option( 'wsl_settings_' . $provider . '_enabled' ) ){
				throw new Exception( 'Unknown or disabled provider' );
			}

			$config = array();
			$config["base_url"]  = plugins_url() . '/' . basename( dirname( __FILE__ ) ) . '/hybridauth/';
			$config["providers"] = array();
			$config["providers"][$provider] = array();
			$config["providers"][$provider]["enabled"] = true;

			// provider application id ?
			if( get_option( 'wsl_settings_' . $provider . '_app_id' ) ){
				$config["providers"][$provider]["keys"]["id"] = get_option( 'wsl_settings_' . $provider . '_app_id' );
			}

			// provider application key ?
			if( get_option( 'wsl_settings_' . $provider . '_app_key' ) ){
				$config["providers"][$provider]["keys"]["key"] = get_option( 'wsl_settings_' . $provider . '_app_key' );
			}

			// provider application secret ?
			if( get_option( 'wsl_settings_' . $provider . '_app_secret' ) ){
				$config["providers"][$provider]["keys"]["secret"] = get_option( 'wsl_settings_' . $provider . '_app_secret' );
			}

			// create an instance for Hybridauth
			$hybridauth = new Hybrid_Auth( $config );

			// try to authenticate the selected $provider
			$adapter = $hybridauth->authenticate( $provider );

			// further testing
			if( get_option( 'wsl_settings_development_mode_enabled' ) ){
				$profile = $adapter->getUserProfile( $provider );
			}
?>
<html>
<head>
<script>
function init() {
	window.opener.wsl_wordpress_social_login({
		'action'   : 'wordpress_social_login',
		'provider' : '<?php echo $provider ?>'
	});

	window.close();
}
</script>
</head>
<body onload="init();">
</body>
</html>
<?php
		}
		catch( Exception $e ){
			$message = "Unspecified error!"; 

			switch( $e->getCode() ){
				case 0 : $message = "Unspecified error."; break;
				case 1 : $message = "Hybriauth configuration error."; break;
				case 2 : $message = "Provider not properly configured."; break;
				case 3 : $message = "Unknown or disabled provider."; break;
				case 4 : $message = "Missing provider application credentials."; break;
				case 5 : $message = "Authentification failed. The user has canceled the authentication or the provider refused the connection."; break; 
			}
?>
<style> 
HR {
	width:100%;
	border: 0;
	border-bottom: 1px solid #ccc; 
	padding: 50px;
}
</style>
<table width="100%" border="0">
  <tr>
    <td align="center"><br /><br /><img src="assets/img/alert.png" /></td>
  </tr>
  <tr>
    <td align="center"><br /><br /><h3>Something bad happen!</h3><br /></td> 
  </tr>
  <tr>
    <td align="center">&nbsp;<?php echo $message ; ?></td> 
  </tr> 
  
<?php 
	// Development mode on?
	if( get_option( 'wsl_settings_development_mode_enabled' ) ){
?>
  <tr>
    <td align="center"> 
		<div style="padding: 5px;margin: 5px;background: none repeat scroll 0 0 #F5F5F5;border-radius:3px;">
			<div id="bug_report">
				<form method="post" action="http://hybridauth.sourceforge.net/reports/index.php?product=wp-plugin&v=1.1.6">
					<table width="90%" border="0">
						<tr>
							<td align="left" valign="top"> 
								<h3>Expection</h3>
								<pre style="width:800px;"><?php print_r( $e ) ?></pre> 

								<hr />

								<h3>HybridAuth</h3>
								<pre style="width:800px;"><?php print_r( array( $config, $hybridauth, $adapter, $profile ) ) ?></pre> 

								<hr />

								<h3>SERVER</h3>
								<pre style="width:800px;"><?php print_r( $_SERVER ) ?></pre> 
							</td> 
						</tr> 
						<tr>
							<td align="center" valign="top"> 
								<hr /> 
								&nbsp;<b>This plugin is still in alpha</b><br /><br /><b style="color:#cc0000;">But you can make it better by sending the generated error report to the developer!</b>
								<br />
								<br />
								<input type="submit" style="width: 300px;height: 33px;" value="Send the error report" /> 
							</td> 
						</tr>
					</table> 

					<textarea name="report" style="display:none;"><?php echo base64_encode( print_r( array( $e, $config, $hybridauth, $adapter, $profile, $_SERVER ), TRUE ) ) ?></textarea>
				</form> 
				<small>
					Note: This message can be disabled from the plugin settings by setting <b>Development mode</b> to <b>Disabled</b>.
				</small>
			</div>
		</div>
	</td> 
  </tr>
<?php
	} // end Development mode
?>
  
</table> 
<?php 
			// diplay error and RIP
			die();
		}
    }
?>