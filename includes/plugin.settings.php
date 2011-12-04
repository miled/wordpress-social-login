<?php
function wsl_render_settings()
{
	GLOBAL $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG;
?>
<style type="text/css"> 
#wsl_setup_form .inputgnrc, #wsl_setup_form select {
    font-size: 15px;
    padding: 6px 3px; 
    border: 1px solid #CCCCCC;
    border-radius: 4px 4px 4px 4px;
    color: #444444;
    font-family: arial;
    font-size: 16px;
    margin: 0;
    padding: 3px;
    width: 300px;
} 
#wsl_setup_form .inputsave {
    font-size: 15px;
    padding: 6px 3px;  
    color: #444444;
    font-family: arial;
    font-size: 18px;
    margin: 0;
    padding: 3px;
    width: 400px;
	height: 40px;
} 
#wsl_setup_form ul {
    list-style: none outside none; 
}
#wsl_setup_form .cgfparams ul {
    padding: 0;
	margin: 0;
}
#wsl_setup_form ul li {
    color: #555555;
    font-size: 13px;
    margin-bottom: 10px;
    padding: 0;
}
#wsl_setup_form ul li label {
    color: #000000;
    display: block;
    font-size: 14px;
    font-weight: bold;
	padding-bottom: 5px;
}
#wsl_setup_form .cfg { 
	background: #f5f5f5;
	float: left;
	border-radius: 2px 2px 2px 2px;
	border: 1px solid #AAAAAA;
	margin: 0 0 0 30px;
}
#wsl_setup_form .cgfparams {
   padding: 20px;
   float: left;
}
#wsl_setup_form .cgftip {
   border-left: 1px solid #aaa;
   margin-left: 340px;
   padding: 20px;
   min-height: 202px; 
   width: 770px;
   width: 600px; 
   padding-top: 1px;


   width: 450px; 
} 

/* tobe fixed .. */
#footer {
    display:none; 
}
#wsl_setup_form p {
	font-size: 14px;
}
</style> 
<form method="post" id="wsl_setup_form" action="options.php">

	<?php settings_fields( 'wsl-settings-group' ); ?>
	
	<h2 style="margin-bottom: 15px;">WordPress Social Login Settings</h2>  

	<h3 style="border-bottom: 1px solid #CCCCCC;margin:10px;">Overview</h3> 
	<p style="margin-left:25px;margin:10px;"> 
		This plugin allow your visitors to login and comment usiing their accounts on Facebook, Google, Yahoo, Twitter, Windows Live, Myspace, Foursquare, Linkedin, and AOL.
	</p>
	<p style="margin-left:25px;margin:10px;"> 
		This plugin's purposes are to:
	</p>

	<ul style="list-style:circle inside;margin-left:25px;">
		<li>Provide an alternative to existing commercial and proprietary services,</li>
		<li>If you are a designer or a web developer then you can improve, extend and customize it to your heart's content.</li> 
	</ul>

	<br />

	<h3 style="border-bottom: 1px solid #CCCCCC;margin:10px;">Important to know</h3> 
	<p style="margin-left:25px;margin:10px;"> 
		Well, it's quite a story, please bear with me :
	</p>
	<ul style="list-style:circle inside;margin-left:25px;">
		<li>This plugin is an <strong>Open Source</strong> project made on top of an open source Library for an open source CMS,</li>
		<li>This plugin is still in <strong>Alpha Stage</strong> and as such, should be used for <strong>testing only</strong> until a real stable release come to life,</li>
		<li>This plugin is tested only on a <strong>default</strong> wordpress installation without any extra add-ons or tweak,</li>
		<li>Basically this plugin can be extended to support many others providers such as Gowalla, Last.fm, Vimeo, Viadeo, Tumblr, QQ, Sina and maybe more,</li> 
		<li>As an open source project and alpha stage plugin, We Appreciate Your <b>Feedback</b>. So far, working with the wordpress community is no fun :D, no jk :)</li> 
		<li>If you run into any issue, or have a feature request, the best way to reach me is at <b>hybridauth@gmail.com</b> or on <a href="https://groups.google.com/forum/#%21forum/hybridauth-plugins">https://groups.google.com/forum/#!forum/hybridauth-plugins</a></li>
		<li>
			<b>Custom integration:</b>
			<p style="margin-left:25px;margin:10px;">
				WordPress Social Login will attempts to work with the default WordPress comment, login and registration forms. If you want to add the social login widget to another location in your theme, you can insert the following code in that location:
			</p>

			<pre style="width: 400px;background-color: #FFFFE0;border:1px solid #E6DB55; border-radius: 3px;padding: 10px;margin-left:10px;"> &lt;?php do_action( 'wordpress_social_login' ); ?&gt; </pre> 
		</li>
	</ul>
	
	<br /> 

	<h3 style="border-bottom: 1px solid #CCCCCC;margin:10px;">Not scared yet? Let's get started!</h3>
	<p style="margin-left:25px;margin:10px;text-align:center;"> 
		<br />
		<br />
		<a class="button-primary" href='<?php echo WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL ?>/help/index.html#settings' target='_blank'>Read the plugin user guide</a>
		
		&nbsp;&nbsp;&nbsp; 
		
		<a class="button-primary" href='<?php echo WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL ?>/diagnostics.php?url=http://www.example.com' target='_blank'>Run the plugin diagnostics</a>
	</p>

	<br />
 
	<h3 style="border-bottom: 1px solid #CCCCCC;margin:10px;">Providers setup</h3> 

	<ul style="list-style:circle inside;margin-left:25px;">
		<li style="color: #000000;font-size: 14px;">To correctly setup these Identity Providers please carefully follow the help section of each one.</li>
		<li style="color: #000000;font-size: 14px;">If a <b>Provider Satus</b> is set to <b style="color:red">NO</b> then users will not be able to login with that provider on you website.</li>
	</ul>

<?php
	$nb_provider = 0;
	
	foreach( $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG AS $item ):
		$provider_id                = @ $item["provider_id"];
		$provider_name              = @ $item["provider_name"];

		$require_client_id          = @ $item["require_client_id"];
		$provide_email              = @ $item["provide_email"];
		
		$provider_new_app_link      = @ $item["new_app_link"];
		$provider_userguide_section = @ $item["userguide_section"];

		$provider_callback_url      = "" ;

		if( isset( $item["callback"] ) && $item["callback"] ){
			$provider_callback_url  = '<span style="color:green">' . WORDPRESS_SOCIAL_LOGIN_HYBRIDAUTH_ENDPOINT_URL	 . '?hauth.done=' . $provider_id . '</span>';
		}

		$setupsteps = 0;

		$assets_base_url = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . '/assets/img/16x16/';
?> 
	<h3 style="margin-left:30px;"><img alt="<?php echo $provider_name ?>" title="<?php echo $provider_name ?>" src="<?php echo $assets_base_url . strtolower( $provider_id ) . '.png' ?>" style="vertical-align: top;" /> <?php echo $provider_name ?></h3> 
	<div> 
		<div class="cfg">
		   <div class="cgfparams">
			  <ul>
				 <li><label>Allow users to sign on with <?php echo $provider_name ?>?</label>
					<select name="<?php echo 'wsl_settings_' . $provider_id . '_enabled' ?>">
						<option value="1" <?php if(   get_option( 'wsl_settings_' . $provider_id . '_enabled' ) ) echo "selected"; ?> >Yes</option>
						<option value="0" <?php if( ! get_option( 'wsl_settings_' . $provider_id . '_enabled' ) ) echo "selected"; ?> >No</option>
					</select>
				</li>
				
				<?php if ( $provider_new_app_link ){ ?>
					<?php if ( $require_client_id ){ // key or id ? ?>
						<li><label>Application ID</label>
						<input type="text" class="inputgnrc"
						value="<?php echo get_option( 'wsl_settings_' . $provider_id . '_app_id' ); ?>"
						name="<?php echo 'wsl_settings_' . $provider_id . '_app_id' ?>" ></li>
					<?php } else { ?>
						<li><label>Application Key</label>
						<input type="text" class="inputgnrc" 
							value="<?php echo get_option( 'wsl_settings_' . $provider_id . '_app_key' ); ?>"
							name="<?php echo 'wsl_settings_' . $provider_id . '_app_key' ?>" ></li>
					<?php }; ?>	 

					<li><label>Application Secret</label>
					<input type="text" class="inputgnrc"
						value="<?php echo get_option( 'wsl_settings_' . $provider_id . '_app_secret' ); ?>" 
						name="<?php echo 'wsl_settings_' . $provider_id . '_app_secret' ?>" ></li>
				<?php } // if require registration ?>
			  </ul> 
		   </div>
		   <div class="cgftip">
				<?php if ( $provider_new_app_link  ) : ?> 
					<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> Go to <a href="<?php echo $provider_new_app_link ?>" target ="_blanck"><?php echo $provider_new_app_link ?></a> and <b>create a new application</b>.</p>

					<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> Fill out any required fields such as the application name and description.</p>

					<?php if ( $provider_id == "google" ) : ?>
						<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> On the <b>"Create Client ID"</b> popup switch to advanced settings by clicking on <b>(more options)</b>.</p>
					<?php endif; ?>	

					<?php if ( $provider_callback_url ) : ?>
						<p>
							<?php echo "<b>" . ++$setupsteps . "</b>." ?> Provide this URL as the Callback URL for your application:
							<br />
							<?php echo $provider_callback_url ?>
						</p>
					<?php endif; ?> 

					<?php if ( $provider_id == "MySpace" ) : ?>
						<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> Put your website domain in the <b>External Url</b> and <b>External Callback Validation</b> fields. This should match with the current hostname <em style="color:#CB4B16;"><?php echo $_SERVER["SERVER_NAME"] ?></em>.</p>
					<?php endif; ?> 

					<?php if ( $provider_id == "Live" ) : ?>
						<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> Put your website domain in the <b>Redirect Domain</b> field. This should match with the current hostname <em style="color:#CB4B16;"><?php echo $_SERVER["SERVER_NAME"] ?></em>.</p>
					<?php endif; ?> 

					<?php if ( $provider_id == "Facebook" ) : ?>
						<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> Put your website domain in the <b>Site Url</b> field. This should match with the current hostname <em style="color:#CB4B16;"><?php echo $_SERVER["SERVER_NAME"] ?></em>.</p> 
					<?php endif; ?>	

					<?php if ( $provider_id == "LinkedIn" ) : ?>
						<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> Put your website domain in the <b>Integration URL</b> field. This should match with the current hostname <em style="color:#CB4B16;"><?php echo $_SERVER["SERVER_NAME"] ?></em>.</p> 
						<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> Set the <b>Application Type</b> to <em style="color:#CB4B16;">Web Application</em>.</p> 
					<?php endif; ?>	

					<?php if ( $provider_id == "Twitter" ) : ?>
						<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> Put your website domain in the <b>Application Website</b> and <b>Application Callback URL</b> fields. This should match with the current hostname <em style="color:#CB4B16;"><?php echo $_SERVER["SERVER_NAME"] ?></em>.</p> 
						<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> Set the <b>Application Type</b> to <em style="color:#CB4B16;">Browser</em>.</p> 
						<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> Set the <b>Default Access Type</b> to <em style="color:#CB4B16;">Read, Write, & Direct Messages</em>.</p> 
					<?php endif; ?>	
					
					<p><?php echo "<b>" . ++$setupsteps . "</b>." ?> Once you have registered, copy and past the created application credentials into this setup page.</p>  
				<?php else: ?>	
					<p>No registration required for OpenID based providers</p> 
				<?php endif; ?> 
		   </div>
		</div>   
	</div> 
	<br style="clear:both;"/> 
	<br />
<?php
	endforeach;
?>
	<br /> 
	<div style="text-align:center">
		Thanks for scrolling this far down! now click the big button to complete the installation.
		<br />
		<br />
		<input type="submit" class="inputsave" value="Setup WordPress Social Login" /> 
	</div> 
</div>
</form>
<div class="clear"></div>
<?php
}
