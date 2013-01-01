<style>
q:before, q:after, blockquote:before, blockquote:after {
    content: "";
}
q:before, q:after, blockquote:before, blockquote:after {
    content: "";
}
blockquote {
    border-left: 5px solid #EEEEEE;
    margin-bottom: 18px;
    padding-left: 15px;
}
</style>
 
<div style="margin-left:10px;">
<h3>1. How to install</h3> 

<ol>
	<li>Download, Unzip and drop the extention on <b>/wp-content/plugins/</b> directory,</li>
	<li>As admistration, activate the plugin through the 'Plugins' menu in WordPress,</li>  
	<li>Visit the <strong>Settings\ WP Social Login</strong> to get started,</li>  
	<li>Run the <b>requirements test</b> to make sure your server settings meet this plugin requirements,</li>
</ol>  

<div style="text-align: center;"><img src="http://hybridauth.sourceforge.net/userguide/img/screenshot-4.jpg" ></div>

<br /> 

<h3>2. How to configure</h3> 

<p style="margin-left:25px;margin:10px;"> 
Except few, each provider will require that you create an external application linking your Web site to theirs apis. These external applications ensures that users are logging into the proper Web site and allows identities providers to send the user back to the correct Web site after successfully authenticating their Accounts. 
</p>

<ol style="list-style:circle inside;margin-left:25px;"> 
	<li>Visit the <strong>Providers setup</strong> section to setup the social networks you want to use.</li>
</ol>

<br />

<div style="text-align: center;"><img src="http://s.wordpress.org/extend/plugins/wordpress-social-login/screenshot-3.png" ></div>
 
<blockquote>
<b>Notes:</b>
<br />
<ul style="">
	<li>To correctly setup these Identity Providers please carefully follow the help section of each one.</li>
	<li>If the <b>Allow users to sign on with provider?</b> is set to <b style="color:red">NO</b> then users will not be able to login with that provider on you website.</li>
	<li>To enable russian, cyrillic or arabic usernames, you might need <a href="http://wordpress.org/extend/plugins/wordpress-special-characters-in-usernames/" target="_blank">WordPress Special Characters in Usernames</a> plugin.</li>
	<li>Some social networks like Twitter and LinkedIn does NOT give out their user's email. A random email will then be generated for them.</li>
	<li>WSL will try to link existing blog accounts to social network users profiles by matching their verified emails. Currently this only works for Facebook, Google, Yahaoo and Foursquare.</li>
</ul>
</blockquote>
 


<br />

<h3>3. Comment view</h3> 
<p style="margin-left:25px;margin:10px;"> 
	When you finish configuring the extention, any enabled provider icon will appear after on top of the comment form. under <b>Leave a Reply</b> message.
</p>

<div style="text-align: center;"><img src="http://s.wordpress.org/extend/plugins/wordpress-social-login/screenshot-1.png" ></div>

<br /> 

<h3>4. Login/Register views</h3> 
<p style="margin-left:25px;margin:10px;"> 
	Same as Comment view. <b>WordPress Social Login</b> will render the list of enabled provider on the Login and Register forms.
</p>

<div style="text-align: center;"><img src="http://s.wordpress.org/extend/plugins/wordpress-social-login/screenshot-2.png" ></div>

<br /> 

<h3>5. Login flow</h3> 
<p style="margin-left:25px;margin:10px;"> 
	When a user click an icon, a popup will apprear where it will be redirected to the provider authentication web page.
	If he grant access for your website, he will redirected back to your website.
</p> 

<ul style="list-style:circle inside;margin-left:25px;">
	<li>If the user do not exist, this extension will try to create a new accout for him</li>
	<li>Else, if an account does exist for him, then i will automatically logged in to the website.</li>
	<li>Some social networks like Twitter and LinkedIn does NOT give out their user's email. A <b>random email</b> will then be then generated.</li>
</ul> 

<br /> 

<h3>6. Custom integration</h3> 
<p style="margin-left:25px;margin:10px;"> 
	<b>WordPress Social Login</b> will attempts to work with the default WordPress comment, login and registration forms. 

	<br /> 
	<br /> 
	If you want to add the social login widget to another location in your theme, you can insert the following code in that location:

	<pre style="width: 400px;background-color: #FFFFE0;border:1px solid #E6DB55; border-radius: 3px;padding: 10px;margin-left:10px;"> &lt;?php do_action( 'wordpress_social_login' ); ?&gt; </pre> 
	
</p>
<p style="margin-left:25px;margin:10px;"> 
Also, if you are a developer or designer then you can customize it to your heart's content: 
</p>
<blockquote>
<ul>
	<li>The default css styles are found at <strong>/wordpress-social-login/assets/css/style.css</strong></li> 
	<li>Social icons are found at <strong>/wordpress-social-login/assets/img/32x32/</strong></li> 
	<li>The widget view can be found at <strong>/wordpress-social-login/includes/plugin.ui.php</strong>, function <strong>wsl_render_login_form()</strong></li> 
	<li>The popup and loading screens are found at <strong>/wordpress-social-login/authenticate.php</strong></li> 
</ul> 
</blockquote>

<br />
<h3>7. Help & Support</h3> 
<p style="margin-left:25px;margin:10px;"> 
	If you run into any issue, join us on the <b><a href="https://groups.google.com/d/forum/hybridauth-plugins" target="_blank">discussion group</a></b> or feel free to contact me at <b><a href="mailto:hybridauth@gmail.com">hybridauth@gmail.com</a></b>  
</p>

</div>