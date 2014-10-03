<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*    (c) 2011-2014 Mohamed Mrassi and contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

/**
* Components Manager 
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 

// --------------------------------------------------------------------

function wsl_component_components_gallery()
{
	// not for today
	return;

	// HOOKABLE: 
	do_action( "wsl_component_components_gallery_start" ); 

	add_thickbox();
?> 
<br />

<h2><?php _wsl_e( "Other Components available", 'wordpress-social-login' ) ?></h2>

<style>
.wsl_addon_div{
	width: 350px; 
	height: 145px; 
	padding: 10px; 
	border: 1px solid #ddd; 
	background-color: #fff;
	float:left;
	margin-bottom: 20px;
	margin-right: 20px;
	
	position: relative;
}
.wsl_addon_div .button-secondary {
    bottom: 8px;
    left: 8px;
    position: absolute; 
}
.wsl_addon_div .button-primary {
    bottom: 8px;
    right: 8px;
    position: absolute;  
}
</style>

<div class="wsl_addon_div">
	<h3 style="margin:0px;"><?php _wsl_e( "Build yours", 'wordpress-social-login' ) ?></h3>
	<hr />
	<p><?php _wsl_e( "Looking to build your own custom <b>WordPress Social Login</b> extension or component? Well, it's pretty easy. Just refer to WSL Developer Docs and API.", 'wordpress-social-login' ) ?></p>
	<div>
		<a class="button button-primary"   href="http://hybridauth.sourceforge.net/wsl/developer.html" target="_blank"><?php _wsl_e( "WSL Developer API", 'wordpress-social-login' ) ?></a> 
		<a class="button button-secondary" href="https://github.com/hybridauth/WordPress-Social-Login" target="_blank"><?php _wsl_e( "WSL on Github", 'wordpress-social-login' ) ?></a> 
	</div>
</div>
<?php
	// HOOKABLE: 
	do_action( "wsl_component_components_gallery_end" );
}

// --------------------------------------------------------------------	
