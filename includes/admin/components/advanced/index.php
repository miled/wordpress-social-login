<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*    (c) 2011-2013 Mohamed Mrassi and contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

/**
* Advanced Advanced Settings
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------

function wsl_component_advanced()
{
	// HOOKABLE: 
	do_action( "wsl_component_advanced_start" ); 

	if( ! get_option( 'wsl_settings_base_url' ) ){
		update_option( 'wsl_settings_base_url', WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL );
	}

	if( ! get_option( 'wsl_settings_base_path' ) ){
		update_option( 'wsl_settings_base_path', WORDPRESS_SOCIAL_LOGIN_ABS_PATH );
	}

	if( ! get_option( 'wsl_settings_hybridauth_endpoint_url' ) ){
		update_option( 'wsl_settings_hybridauth_endpoint_url', WORDPRESS_SOCIAL_LOGIN_HYBRIDAUTH_ENDPOINT_URL );
	}

	if( ! get_option( 'wsl_settings_top_bar_menu' ) ){
		update_option( 'wsl_settings_top_bar_menu', 1 );
	}
?>
<form method="post" id="wsl_setup_form" action="options.php"> 
<?php settings_fields( 'wsl-settings-group-advanced-settings' ); ?>

<div class="metabox-holder columns-2" id="post-body">
<div  id="post-body-content"> 

<div id="namediv" class="stuffbox">
	<h3>
		<label for="name"><?php _wsl_e("Advanced Settings", 'wordpress-social-login') ?></label>
	</h3>

	<br />
	<h2 style="text-align:center;font-size: 17px;">
		<?php _wsl_e("<b>Please</b> for the love of <b>God</b>, stay out of Advanced.. unless you are Advanced and you know what you are doing", 'wordpress-social-login') ?>.
	</h2> 
	<br />

	<div class="inside"> 
		<table width="100%" border="0" cellpadding="5" cellspacing="2" style="border-top:1px solid #ccc;">  
		  <tbody>

		  <tr>
			<td width="200" align="right"><strong><?php _wsl_e("WSL Base URL", 'wordpress-social-login') ?> :</strong></td>
			<td> 
				<input type="text" name="wsl_settings_base_url" value="<?php echo get_option( 'wsl_settings_base_url' ); ?>" class="inputgnrc">  
			</td>
		  </tr> 

		  <tr>
			<td width="200" align="right"><strong><?php _wsl_e("WSL Base PATH", 'wordpress-social-login') ?> :</strong></td>
			<td> 
				<input type="text" name="wsl_settings_base_path" value="<?php echo get_option( 'wsl_settings_base_path' ); ?>" class="inputgnrc">  
			</td>
		  </tr> 

		  <tr>
			<td width="200" align="right"><strong><?php _wsl_e("Hybridauth endpoint URL", 'wordpress-social-login') ?> :</strong></td>
			<td> 
				<input type="text" name="wsl_settings_hybridauth_endpoint_url" value="<?php echo get_option( 'wsl_settings_hybridauth_endpoint_url' ); ?>" class="inputgnrc">  
			</td>
		  </tr> 
		  
		  <tr>
			<td width="200" align="right"><strong><?php _wsl_e("WSL top bar menu", 'wordpress-social-login') ?> :</strong></td>
			<td>
				<select name="wsl_settings_top_bar_menu">
					<option <?php if( get_option( 'wsl_settings_top_bar_menu' ) == 1 ) echo "selected"; ?> value="1"><?php _wsl_e("Yes", 'wordpress-social-login') ?></option>
					<option <?php if( get_option( 'wsl_settings_top_bar_menu' ) == 2 ) echo "selected"; ?> value="2"><?php _wsl_e("No", 'wordpress-social-login') ?></option> 
				</select>
			</td>
		  </tr> 
		  
		</tbody>
		</table>
	</div>
</div>

<br style="clear:both;" />
<div style="margin-left:5px;margin-top:-20px;"> 
	<input type="submit" class="button-primary" value="<?php _wsl_e("Save Settings", 'wordpress-social-login') ?>" /> 
</div>

</div> 
</div> 
</form>  
<?php
	// HOOKABLE: 
	do_action( "wsl_component_advanced_end" );
}

wsl_component_advanced();

// --------------------------------------------------------------------	
