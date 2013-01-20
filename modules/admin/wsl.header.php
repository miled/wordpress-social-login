<style> 
h1 {
    color: #333333;
    text-shadow: 1px 1px 1px #FFFFFF; 
    font-size: 2.8em;
    font-weight: 200;
    line-height: 1.2em;
    margin: 0.2em 200px 0.6em 0.2em;
}
h2 .nav-tab {
    color: #21759B;
}
h2 .nav-tab-active {
    color: #464646;
    text-shadow: 1px 1px 1px #FFFFFF;
}
hr{ 
	border-color: #EEEEEE;
	border-style: none none solid;
	border-width: 0 0 1px;
	margin: 2px 0 15px;
} 
.wsldiv { 
    margin: 25px 40px 0 20px; 
}
.wsldiv p{  
	line-height: 1.8em;
}
.wslgn{ 
    margin-left:20px;
}
.wslgn p{ 
	margin-left:20px;
}
.wslpre{ 
    font-size:14m;
	border:1px solid #E6DB55; 
	border-radius: 3px;
	padding:5px;
	width:650px;
}
ul {
    list-style: disc outside none;
}
 
.thumbnails:before,
.thumbnails:after {
  display: table;
  line-height: 0;
  content: "";
}

.thumbnail {
  display: block;
  padding: 4px;
  line-height: 20px;
  border: 1px solid #ddd;
  -webkit-border-radius: 4px;
     -moz-border-radius: 4px;
          border-radius: 4px;
  -webkit-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.055);
     -moz-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.055);
          box-shadow: 0 1px 3px rgba(0, 0, 0, 0.055);
  -webkit-transition: all 0.2s ease-in-out;
     -moz-transition: all 0.2s ease-in-out;
       -o-transition: all 0.2s ease-in-out;
          transition: all 0.2s ease-in-out;
}

a.thumbnail:hover {
  border-color: #0088cc;
  -webkit-box-shadow: 0 1px 4px rgba(0, 105, 214, 0.25);
     -moz-box-shadow: 0 1px 4px rgba(0, 105, 214, 0.25);
          box-shadow: 0 1px 4px rgba(0, 105, 214, 0.25);
}

.thumbnail > img {
  display: block;
  max-width: 100%;
  margin-right: auto;
  margin-left: auto;
}

.thumbnail .caption {
  padding: 9px;
  color: #555555;
}
.span4 {  
	width: 220px; 
}
#wp-social-login-connect-with {  
	font-size: 14px; 
}
#wp-social-login-connect-options {  
	margin:5px; 
}
.wsl_connect_with_provider {  
	text-decoration:none; 
	cursor:wait;
} 
#wsl-w-panel {
    background: linear-gradient(to top, #F5F5F5, #FAFAFA) repeat scroll 0 0 #F5F5F5;
    border-color: #DFDFDF;
    border-radius: 3px 3px 3px 3px;
    border-style: solid;
    border-width: 1px;
    font-size: 13px;
    line-height: 2.1em;
    margin: 20px 0;
    overflow: auto;
    -padding: 23px 10px 12px;
    padding: 5px;
    position: relative;
}
#wsl-w-panel-dismiss {
    font-size: 13px;
    line-height: 1;
    padding: 8px 3px;
    position: absolute;
    right: 10px;
    text-decoration: none;
    top: 0px;
}
#wsl-w-panel-updates-tr {
    display:none;  
}

<?php
	if( $wslp == "overview" ){
		?>
h1 {
	padding-top: 36px;
    margin-bottom: 18px;
    font-size: 81px;
    font-weight: bold;
    letter-spacing: -1px;
    line-height: 1;
	text-align: center;
	width: 100%;
} 
.wsl-about-text{ 
    font-size: 30px;
    line-height: 36px;
    -margin-left: 5%;
    -margin-right: 5%;
	text-align: center;
    font-weight: 300;
    
	
	color: #5e5e5e;
	font-size: 24px;
	margin-left: 40px;
	
	margin-bottom: 10px;
} 
.wsl-about-text-info{  
	color: #6a6a6a ; 
    font-size: 13px;
    line-height: 18px;
	font-weight: bold;
	font-weight: 300;
	margin-top: 0px;
	
	text-align: center;
 
	margin-bottom: 40px;
	margin-left: 40px;
}  
		<?php
	}
?>
</style>

<div class="wsldiv">

<h1>
	WordPress Social Login 
	<small><?php echo $WORDPRESS_SOCIAL_LOGIN_VERSION ?></small>
	<?php
		if( get_option( 'wsl_settings_development_mode_enabled' ) ){
			?>
				<span style="color:red;-font-size: 14px;">(Development mode is enabled!)</span>
			<?php
		}
	?>
</h1>

<?php
	// wsl welcome panel
	// if new user or wsl updated, then display wsl welcome panel
	if( get_option( 'wsl_settings_welcome_panel_enabled' ) != $WORDPRESS_SOCIAL_LOGIN_VERSION ){ 
?>
<!-- 
	if you want to know if a UI was made by devloper, then here is a tip: he will always use tables

	//> wsl-w-panel is shamelessly borrowered and modified from wordpress welcome-panel
-->
<div id="wsl-w-panel">
	<a href="options-general.php?page=wordpress-social-login&wslp=<?php echo $wslp ?>&wsldwp=1" id="wsl-w-panel-dismiss">Dismiss this notice</a>
	
	<table width="100%" border="0" style="margin:0;padding:0;">
		<tr>
			<td width="10" valign="top"></td>
			<td width="300" valign="top">
				<b style="font-size: 16px;">Welcome!</b>
				<p>
					If you are still new to WordPress Social Login, we have provided a few walkthroughs to get you started.
				</p>
			</td>
			<td width="40" valign="top"></td>
			<td width="260" valign="top">
				<b>Get Started</b>

				<ul style="margin-left:25px;">
					<li><a href="http://hybridauth.sourceforge.net/wsl/configure.html" target="_blank">Setup Social Networks</a></li> 
					<li><a href="http://hybridauth.sourceforge.net/wsl/customize.html" target="_blank">Customize WSL Widget</a></li>  
					<li><a href="http://hybridauth.sourceforge.net/wsl/bouncer.html" target="_blank">Advanced Configuration</a></li> 
					<li><a href="http://hybridauth.sourceforge.net/wsl/userdata.html" target="_blank">Manage users and contacts</a></li> 
					<li><a href="http://hybridauth.sourceforge.net/wsl/index.html" target="_blank">WSL User Guide</a> and <a href="http://hybridauth.sourceforge.net/wsl/faq.html" target="_blank">FAQ</a></li>  
				</ul>
			</td>
			<td width="" valign="top">
				<b>What's New  WSL <?php echo $WORDPRESS_SOCIAL_LOGIN_VERSION ?></b>
				<ul style="margin-left:25px;">
					<li>Manage WSL users, more options are to come</li> 
					<li>Import WSL users contact list from Google Gmail, Facebook, Live and LinkedIn</li>  
					<li>An entirely reworked user interface</li> 
					<li>Improving the documentation and guides</li> 
					<li>Introducing a new module, and the long awaited, <a href="http://hybridauth.sourceforge.net/wsl/bouncer.html" target="">The bouncer</a></li> 
					<li>And even more customization options are now available</li>  
				</ul> 
			</td>
		</tr>
		<tr id="wsl-w-panel-updates-tr">
			<td colspan="5" style="border-top:1px solid #ccc;" id="wsl-w-panel-updates-td">
				&nbsp;
			</td>
		</tr>
	</table> 
</div>
<script>
	// check for new versions
	// is this permitted, searched everywhere but i didn't find anything against this
	jQuery.getScript("http://hybridauth.sourceforge.net/wsl/wsl.version.check.and.updates.php?v=<?php echo $WORDPRESS_SOCIAL_LOGIN_VERSION ?>");
</script>
<?php 
	} // wsl welcome panel
?>

<?php
	if( $wslp == "overview" ){
		?>
			<hr style="width:70%;margin-left:16%;" />
			<p class="wsl-about-text">The definitive toolkit to engage your websites vistors and customers on a social level</p>
			<p class="wsl-about-text-info">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. </p>
		<?php
	}
?>
<h2 class="nav-tab-wrapper">
	&nbsp;
<?php
	foreach( $tabs as $name => $settings ){
		if(  $settings["enabled"] && ( $settings["visible"] || $wslp == $name ) ){
			?>
				<a class="nav-tab <?php if( $wslp == $name ) echo "nav-tab-active"; ?>" <?php if( isset( $settings["pull-right"] ) && $settings["pull-right"] ) echo 'style="float:right"'; ?> href="options-general.php?page=wordpress-social-login&wslp=<?php echo $name ?>"><?php echo $settings["label"] ?></a> 
			<?php
		}
	} 
?>
</h2>
