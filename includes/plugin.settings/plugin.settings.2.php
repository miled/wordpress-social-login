<div style="margin-left:10px;">
<h3>1. Install</h3> 

<ol>
	<li>Download, Unzip and drop the extention on <b>/wp-content/plugins/</b> directory,</li>
	<li>As admistration, activate the plugin through the 'Plugins' menu in WordPress,</li>  
	<li>Visit the <strong>Settings\ WP Social Login</strong> to get started,</li>  
	<li>Run the <b><a href="options-general.php?page=wordpress-social-login&wslp=3">requirements test</a></b> to make sure your server settings meet this plugin requirements,</li>
</ol>  

<br />

<h3>2. Configure</h3> 

<p style="margin-left:25px;margin:10px;"> 
Except few, each provider will require that you create an external application linking your Web site to theirs apis. These external applications ensures that users are logging into the proper Web site and allows identities providers to send the user back to the correct Web site after successfully authenticating their Accounts. 
</p>

<ol style="list-style:circle inside;margin-left:25px;"> 
	<li>Visit the <strong>Providers setup</strong> section to setup the social networks you want to use.</li>
</ol>

<br />

<div style="text-align: center;"><img src="<?php echo WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL ?>/screenshot-3.png" ></div>

<br />

<h3>3. Comment view</h3> 
<p style="margin-left:25px;margin:10px;"> 
	When you finish configuring the extention, any enabled provider icon will appear after on top of the comment form. under <b>Leave a Reply</b> message.
</p>

<div style="text-align: center;"><img src="<?php echo WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL ?>/screenshot-1.png" ></div>

<h3>4. Admin view</h3> 
<p style="margin-left:25px;margin:10px;"> 
	Same as Comment view.
</p>

<div style="text-align: center;"><img src="<?php echo WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL ?>/screenshot-2.png" ></div>

<br />

<h3>5. Login flow</h3> 
<p style="margin-left:25px;margin:10px;"> 
	When a user click an icon, a popup will apprear where it will be redirected to the provider authentication web page.
	If he grant access for your website, he will redirected back to your website.
</p> 

<ul style="list-style:circle inside;margin-left:25px;">
	<li>If the user do not exist, this extension will try to create a new accout for him</li>
	<li>Else, if an account does exist for him, then i will automatically logged in to the website.</li>
</ul> 

<br />

<h3>6. Custom integration</h3> 
<p style="margin-left:25px;margin:10px;"> 
	WordPress Social Login will attempts to work with the default WordPress comment, login and registration forms. 

	<br /> 
	<br /> 
	If you want to add the social login widget to another location in your theme, you can insert the following code in that location:

	<pre style="width: 400px;background-color: #FFFFE0;border:1px solid #E6DB55; border-radius: 3px;padding: 10px;margin-left:10px;"> &lt;?php do_action( 'wordpress_social_login' ); ?&gt; </pre> 
</p>
</div>