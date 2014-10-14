<?php
/*!
* WordPress Social Login
*
* http://miled.github.io/wordpress-social-login/index.html | http://github.com/hybridauth/WordPress-Social-Login
*    (c) 2011-2014 Mohamed Mrassi and contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

/**
* WSL tools
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 

// --------------------------------------------------------------------

function wsl_component_tools()
{
	// HOOKABLE:
	do_action( "wsl_component_tools_start" );

	$action = isset( $_REQUEST['do'] ) ? $_REQUEST['do'] : null ;

	if( in_array( $action, array( 'requirements', 'sysinfo', 'uninstall' , 'repair' ) ) )
	{
		if( isset( $_REQUEST['_wpnonce'] ) && wp_verify_nonce( $_REQUEST['_wpnonce'] ) )
		{
			include "wsl.components.tools.actions.php";
			
			do_action( 'wsl_component_tools_do_' . $action );
		}
		else
		{
			?>
				<div style="margin: 4px 0 20px;" class="fade error wsl-error-db-tables">
					<p>
						<?php _wsl_e('The URL nonce is not valid', 'wordpress-social-login') ?>! 
					</p>
				</div>	
			<?php
		}
	}
	else
	{
		$sections = array(
			'requirements'       => 'wsl_component_tools_requirements'       ,
			'system_information' => 'wsl_component_tools_system_information' ,
			'repair_wsl_tables'  => 'wsl_component_tools_repair_wsl_tables'  ,
			'development_mode'   => 'wsl_component_tools_development_mode'   ,
			'uninstall'          => 'wsl_component_tools_uninstall'          ,
		);

		$sections = apply_filters( 'wsl_component_tools_alter_sections', $sections );
	?>
		<div class="metabox-holder columns-2" id="post-body">
			<?php
				foreach( $sections as $section => $action )
				{
					do_action( $action . '_start' );

					do_action( $action );

					do_action( $action . '_end' );
				}
			?>
		</div> 
	<?php
	}

	// HOOKABLE: 
	do_action( "wsl_component_tools_end" );
}

// --------------------------------------------------------------------	

function wsl_component_tools_requirements()
{
?>
<div class="stuffbox">
	<h3>
		<label><?php _wsl_e("Requirements test", 'wordpress-social-login') ?></label>
	</h3>
	<div class="inside"> 
		<p>
			<?php _wsl_e('In order for <b>WordPress Social Login</b> to work properly, your server should meet certain requirements. These "requirements" and "services" are usually offered by default by most "modern" web hosting providers, however some complications may occur with <b>shared hosting</b> and, or <b>custom wordpress installations</b>', 'wordpress-social-login') ?>. 
		</p>
		<p>
			<?php _wsl_e("The minimum server requirements are", 'wordpress-social-login') ?>:
		</p>
		<ul style="margin-left:60px;">
			<li><?php _wsl_e("PHP >= 5.2.0 installed", 'wordpress-social-login') ?></li> 
			<li><?php _wsl_e("WSL Endpoint URLs reachable", 'wordpress-social-login') ?></li>
			<li><?php _wsl_e("PHP's default SESSION handling", 'wordpress-social-login') ?></li>
			<li><?php _wsl_e("PHP/CURL/SSL Extension enabled", 'wordpress-social-login') ?></li> 
			<li><?php _wsl_e("PHP/JSON Extension enabled", 'wordpress-social-login') ?></li> 
			<li><?php _wsl_e("PHP/REGISTER_GLOBALS Off", 'wordpress-social-login') ?></li> 
			<li><?php _wsl_e("jQuery installed on WordPress backoffice", 'wordpress-social-login') ?></li> 
		</ul>

		<a class="button-secondary" href="<?php echo wp_nonce_url( 'options-general.php?page=wordpress-social-login&wslp=tools&do=requirements'); ?>"><?php _wsl_e("Run WordPress Social Login requirements test", 'wordpress-social-login') ?></a>  
	</div>
</div>
<?php
}

add_action( 'wsl_component_tools_requirements', 'wsl_component_tools_requirements' );

// --------------------------------------------------------------------	

function wsl_component_tools_system_information()
{
?>
<div class="stuffbox">
	<h3>
		<label><?php _wsl_e("System information", 'wordpress-social-login') ?></label>
	</h3>
	<div class="inside"> 
		<p>
			<?php _wsl_e('Please include these information when posting support requests. It will help me immensely to better understand any issues', 'wordpress-social-login') ?>. 
		</p>

		<a class="button-secondary"  href="<?php echo wp_nonce_url( 'options-general.php?page=wordpress-social-login&wslp=tools&do=sysinfo'); ?>"><?php _wsl_e("Display your system information", 'wordpress-social-login') ?></a>  
	</div>
</div>
<?php
}

add_action( 'wsl_component_tools_system_information', 'wsl_component_tools_system_information' );

// --------------------------------------------------------------------	

function wsl_component_tools_development_mode()
{
	$wsl_settings_development_mode_enabled = get_option( 'wsl_settings_development_mode_enabled' ); 
?>
<div class="stuffbox">
	<h3>
		<label><?php _wsl_e("Development mode", 'wordpress-social-login') ?></label>
	</h3>
	<div class="inside"> 
		<p>
			<?php _wsl_e('When <b>Development Mode</b> is enabled, this plugin will display a debugging area on the footer of admin interfaces. <b>Development Mode</b> will also try generate and display a technical reports when something goes wrong. This report can help you figure out the root of the issues you may runs into', 'wordpress-social-login') ?>.
		</p>

		<p>
			<?php _wsl_e('Please, do not enable <b>Development Mode</b>, unless you are a developer or you have basic PHP knowledge', 'wordpress-social-login') ?>.
		</p>

		<p>
			<?php _wsl_e('For security reasons, <b>Development Mode</b> will auto switch to <b>Disabled</b> each time the plugin is <b>reactivated</b>', 'wordpress-social-login') ?>.
		</p>

		<p>
			<?php _wsl_e('It\'s highly recommended to keep the <b>Development Mode</b> <b style="color:#da4f49">Disabled</b> on production as it would be a security risk otherwise', 'wordpress-social-login') ?>.
		</p>

		<form method="post" id="wsl_setup_form" action="options.php" <?php if( ! $wsl_settings_development_mode_enabled ) { ?>onsubmit="return confirm('Do you really want to enable Development Mode?\n\nPlease confirm that you have read and understood the abovementioned by clicking OK.');"<?php } ?>>  
			<?php settings_fields( 'wsl-settings-group-development' ); ?>

			<select name="wsl_settings_development_mode_enabled">
				<option <?php if(   $wsl_settings_development_mode_enabled ) echo "selected"; ?> value="1"><?php _wsl_e("Enabled", 'wordpress-social-login') ?></option>
				<option <?php if( ! $wsl_settings_development_mode_enabled ) echo "selected"; ?> value="0"><?php _wsl_e("Disabled", 'wordpress-social-login') ?></option> 
			</select>

			<input type="submit" class="button-secondary" style="background-color: #da4f49;border-color: #bd362f;text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);color: #ffffff;" value="<?php _wsl_e("Save Settings", 'wordpress-social-login') ?>" />
		</form>
	</div>
</div>
<?php
}

add_action( 'wsl_component_tools_development_mode', 'wsl_component_tools_development_mode' );

// --------------------------------------------------------------------	

function wsl_component_tools_repair_wsl_tables()
{
?>
<div class="stuffbox">
	<h3>
		<label><?php _wsl_e("Repair WSL tables", 'wordpress-social-login') ?></label>
	</h3>
	<div class="inside"> 
		<p>
			<?php _wsl_e('This will attempt recreate WSL databases tables if they do not exist and will also add any missing field', 'wordpress-social-login') ?>. 
		</p>

		<a class="button-secondary" href="<?php echo wp_nonce_url( 'options-general.php?page=wordpress-social-login&wslp=tools&do=repair'); ?>"><?php _wsl_e("Repair WordPress Social Login databases tables", 'wordpress-social-login') ?></a>
	</div>
</div>
<?php
}

add_action( 'wsl_component_tools_repair_wsl_tables', 'wsl_component_tools_repair_wsl_tables' );

// --------------------------------------------------------------------	

function wsl_component_tools_uninstall()
{
?>
<div class="stuffbox">
	<h3>
		<label><?php _wsl_e("Uninstall", 'wordpress-social-login') ?></label>
	</h3>
	<div class="inside"> 
		<p>
			<?php _wsl_e('Thi will permanently delete all Wordpress Social Login tables and stored options from your WordPress database', 'wordpress-social-login') ?>. 
		</p>

		<a class="button-secondary" href="<?php echo wp_nonce_url( 'options-general.php?page=wordpress-social-login&wslp=tools&do=uninstall'); ?>" onClick="return confirm('Do you really want to Delete all Wordpress Social Login tables and options?\n\nPlease confirm that you have read and understood the abovementioned by clicking OK.');" style="background-color: #da4f49;border-color: #bd362f;text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);color: #ffffff;"><?php _wsl_e("Delete all Wordpress Social Login tables and options", 'wordpress-social-login') ?></a>
	</div>
</div>
<?php
}

add_action( 'wsl_component_tools_uninstall', 'wsl_component_tools_uninstall' );

// --------------------------------------------------------------------	

wsl_component_tools();
