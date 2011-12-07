<?php
function wsl_render_settings()
{
	GLOBAL $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG;

	$wslp = @ (int) $_REQUEST["wslp"];

	if( $wslp < 1 || $wslp > 6 ){
		$wslp = 1;
	}

	include "plugin.settings.0.php";

	include "plugin.settings.$wslp.php"; 
}
