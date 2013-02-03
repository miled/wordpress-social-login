<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*   (c) 2013 Mohamed Mrassi and other contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

/** 
* The 10 LOC or so in charge of displaying WSL Admin GUInterfaces
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------

function wsl_render_settings()
{ 
	if ( ! wsl_check_requirements() ){
		include "wsl.fail.php";

		exit;
	}

	GLOBAL $WORDPRESS_SOCIAL_LOGIN_ADMIN_TABS;
	GLOBAL $WORDPRESS_SOCIAL_LOGIN_COMPONENTS;
	GLOBAL $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG;
	GLOBAL $WORDPRESS_SOCIAL_LOGIN_VERSION;
	GLOBAL $wpdb;

	if( isset( $_REQUEST["enable"] ) && isset( $WORDPRESS_SOCIAL_LOGIN_COMPONENTS[ $_REQUEST["enable"] ] ) ){
		$component = $_REQUEST["enable"];

		$WORDPRESS_SOCIAL_LOGIN_COMPONENTS[ $component ][ "enabled" ] = true;

		update_option( "wsl_components_" . $component . "_enabled", 1 );

		wsl_register_components();
	}

	if( isset( $_REQUEST["disable"] ) && isset( $WORDPRESS_SOCIAL_LOGIN_COMPONENTS[ $_REQUEST["disable"] ] ) ){
		$component = $_REQUEST["disable"];

		$WORDPRESS_SOCIAL_LOGIN_COMPONENTS[ $component ][ "enabled" ] = false;

		update_option( "wsl_components_" . $component . "_enabled", 2 );

		wsl_register_components();
	}

	$wslp            = "networks";
	$wsldwp          = 0;
	$assets_base_url = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . '/assets/img/16x16/';

	if( isset( $_REQUEST["wslp"] ) ){
		$wslp = trim( strtolower( strip_tags( $_REQUEST["wslp"] ) ) );
	}

	if( isset( $WORDPRESS_SOCIAL_LOGIN_ADMIN_TABS[$wslp] ) && $WORDPRESS_SOCIAL_LOGIN_ADMIN_TABS[$wslp]["enabled"] ){
		if( isset( $WORDPRESS_SOCIAL_LOGIN_ADMIN_TABS[$wslp]["header_action"] ) && $WORDPRESS_SOCIAL_LOGIN_ADMIN_TABS[$wslp]["header_action"] ){ 
			do_action( $WORDPRESS_SOCIAL_LOGIN_ADMIN_TABS[$wslp]["header_action"] );
		}

		include "wsl.header.php";

		if( isset( $WORDPRESS_SOCIAL_LOGIN_ADMIN_TABS[$wslp]["body_action"] ) && $WORDPRESS_SOCIAL_LOGIN_ADMIN_TABS[$wslp]["body_action"] ){ 
			do_action( $WORDPRESS_SOCIAL_LOGIN_ADMIN_TABS[$wslp]["body_action"] );
		}

		elseif( ! ( isset( $WORDPRESS_SOCIAL_LOGIN_ADMIN_TABS[$wslp]["admin-url"] ) && ! $WORDPRESS_SOCIAL_LOGIN_ADMIN_TABS[$wslp]["admin-url"] ) ){
			include "components/wsl.$wslp.php";

			include "wsl.footer.php";
		}
	}
	else{
		include "wsl.header.php";
		include "wsl.error.php";
	}
}

// --------------------------------------------------------------------

function wsl_render_wp_editor( $name, $content )
{
?>
<div class="postbox"> 
	<div class="wp-editor-textarea">
	<?php 
		wp_editor( 
			$content, $name, 
			array( 'textarea_name' => $name, 'media_buttons' => true, 'tinymce' => array( 'theme_advanced_buttons1' => 'formatselect,forecolor,|,bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,|,link,unlink' ) ) 
		);
	?>
	</div> 
</div>
<?php
}

// --------------------------------------------------------------------
