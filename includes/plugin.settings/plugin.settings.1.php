<style>
.wsl_aside { 
    float: right;
    margin: 6px; 
    margin-top:0px;
    margin-right:10px;
    position: relative;
    width: 250px;
    z-index: 200;
} 
.wsl_help {
    line-height: 1;
    padding: 8px;
	
	background-color: #FFFFE0;
	border:1px solid #E6DB55; 
	border-radius: 3px;
	padding: 10px; 
}
.wsl_notice {
    line-height: 1;
    padding: 8px; 
	background-color: #EDEFF4;
	border:1px solid #6B84B4; 
	border-radius: 3px;
	padding: 10px;      
}
.wsl_alert {
    line-height: 1;
    padding: 8px; 
	background-color: #FFEBE8;
	border:1px solid #CC0000; 
	border-radius: 3px;
	padding: 10px;      
}
.wsl_donate {
    line-height: 1;
    padding: 8px;
	background-color: #eaffdc;
	border:1px solid #60cf4e;  
	border-radius: 3px;
	padding: 10px;      
}
</style>

<div class="wsl_help wsl_aside">
    <h3 style="margin: 0 0 5px;">Need Help?</h3>

	<p style="line-height: 19px;" align="justify">
		If you are still new to things, we recommend that you read the <b><a href="options-general.php?page=wordpress-social-login&wslp=2">Plugin User Guide</a></b>
		and to make sure your server settings meet this <b><a href="options-general.php?page=wordpress-social-login&amp;wslp=3">Plugin Requirements</a></b>.
	</p>
</div> 

<div style="clear:both" class="wsl_notice wsl_aside">
    <h3 style="margin: 0 0 5px;">Need Support?</h3>

	<p style="line-height: 19px;">
		If you run into any issue, please join us on the <b><a href="https://groups.google.com/forum/#!forum/hybridauth-plugins">discussion group</a></b> or feel free to contact me at <b><a href="mailto:hybridauth@gmail.com">hybridauth@gmail.com</a></b>
	</p>
</div>

<?php 
	$nok = true;

	foreach( $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG AS $item ){
		$provider_id = @ $item["provider_id"];
		
		if( get_option( 'wsl_settings_' . $provider_id . '_enabled' ) ){
			$nok = false;
		}
	}

	if( $nok ){
?>
<div style="clear:both" class="wsl_alert wsl_aside">
    <h3 style="margin: 0 0 5px;">Important</h3>

	<p style="line-height: 19px;">
		<b>No provider registered yet!</b> 
		<br />
		Please go to <b><a href="options-general.php?page=wordpress-social-login&wslp=4">Providers setup</a></b> to get started.
	</p>
</div>
<?php
	}
?>

<div style="clear:both;padding-bottom: 0;" class="wsl_donate wsl_aside">
    <h3 style="margin: 0 0 5px;">Donate</h3>

	<p style="line-height: 19px;">
		If you like this plugin and find it useful, help keep this plugin actively developed 

		<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
		<input type="hidden" name="cmd" value="_donations">
		<input type="hidden" name="business" value="hybridauth@gmail.com">
		<input type="hidden" name="lc" value="US">
		<input type="hidden" name="item_name" value="HybridAuth Project">
		<input type="hidden" name="no_note" value="0">
		<input type="hidden" name="currency_code" value="USD">
		<input type="hidden" name="bn" value="PP-DonationsBF:btn_donate_LG.gif:NonHostedGuest">
		<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
		<img alt="" border="0" src="https://www.paypalobjects.com/fr_XC/i/scr/pixel.gif" width="1" height="1">
		</form>
	</p>
</div> 

<?php  
	if( get_option( 'wsl_settings_development_mode_enabled' ) ){
?>
<div style="clear:both" class="wsl_alert wsl_aside">
    <h3 style="margin: 0 0 5px;">Warning</h3>

	<p style="line-height: 19px;">
		<b>Development Mode is On</b> 
		<br />
		Its recommend to <b style="color:red">disable</b> the <a href="options-general.php?page=wordpress-social-login&wslp=3">development mode </a> on production. 
	</p>
</div>
<?php
	}
?>

<p style="margin:10px;font-size: 14px;line-height: 22px;" align="justify">
	<strong>WordPress Social Login</strong> allow your visitors to login and comment using their accounts on Facebook, Google, Yahoo, Twitter, Windows Live, Myspace, Foursquare, Linkedin, Gowalla, Last.fm, Tumblr and AOL. 
</p>
 
<div style="margin-top:20px;">
	<p style="margin:10px;font-size: 14px;">  
		WordPress Social Login is:
	</p>

	<ul style="list-style:circle inside;margin-left:65px;font-size: 14px;">
		<li>open source,</li> 
		<li>free, unlimited,</li> 
		<li>white label, customizable,</li> 
		<li>social sign on solution,</li> 
		<li>with data kept in house</li> 
	</ul>

	<p style="margin:10px;margin-top:20px;font-size: 14px;">  
		To get started:
	</p>

	<ol style="margin-left:65px;font-size: 14px;">
		<li><strong>Setup</strong> the social networks you want to use,</li> 
		<li><strong>Customize</strong> the way you want it to look and behave.</li> 
<!--		
		<li>Get an <strong>insight</strong> into users informations and track trends in site registrations</li> 
-->
	</ol>

	<p style="margin:10px;margin-top:20px;font-size: 14px;">  
		and that is it!
	</p> 
</div>