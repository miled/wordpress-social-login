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
	// return;

	// HOOKABLE: 
	do_action( "wsl_component_components_gallery_start" ); 

	add_thickbox();

	$components = array(
		array( 
			'name'           => 'Export User Data', 
			'description'    => 'Simple add-on that allows you to export WSL users social profiles and contacts lists to a csv or html file.', 
			'developer_name' => 'WordPress Social Login', 
			'developer_link' => 'http://miled.github.io/wordpress-social-login/', 
			'download_link'  => 'https://github.com/miled', 
		),
		array( 
			'name'           => 'Manual accounts linking', 
			'description'    => 'As an administrator you will be able to manuallly link WSL social profiles to the existing WordPress users.', 
			'developer_name' => 'WordPress Social Login', 
			'developer_link' => 'http://miled.github.io/wordpress-social-login/', 
			'download_link'  => 'https://github.com/miled', 
		),
		array( 
			'name'           => 'BuddyPress Auto-Friend', 
			'description'    => 'Automatically create friendships for WSL users. Requires both BuddyPress and Contacts components.', 
			'developer_name' => 'WordPress Social Login', 
			'developer_link' => 'http://miled.github.io/wordpress-social-login/', 
			'download_link'  => 'https://github.com/miled', 
		),
		array( 
			'name'           => 'Emails Bans & Profiles URLs', 
			'description'    => 'This add-on will make Bouncer even more mean and will help you kick the bad guys out.', 
			'developer_name' => 'WordPress Social Login', 
			'developer_link' => 'http://miled.github.io/wordpress-social-login/', 
			'download_link'  => 'https://github.com/miled', 
		),
	);
?> 

<br />

<h2><?php _wsl_e( "Other Components available", 'wordpress-social-login' ) ?></h2>

<p>These components and add-ons can extend the functionality of WordPress Social Login.</p>

<style>
.wsl_component_div{
	width: 30%;
	min-height: 140px; 
	padding: 10px; 
	border: 1px solid #ddd; 
	background-color: #fff;
	float:left;
	margin-bottom: 20px;
	margin-right: 20px;
	
	position: relative;
}
.wsl_component_div h3{
	
	border-bottom: 1px solid #ddd; 
	padding-bottom: 5px; 
	margin-bottom: 0px; 
}
.wsl_component_about_div{
	height: 2px; 
	overflow: hidden; 
	min-height: 93px; 
}
</style>

<?php
	foreach( $components as $item )
	{
		?>
			<div class="wsl_component_div">
				<h3 style="margin:0px;"><?php _wsl_e( $item['name'], 'wordpress-social-login' ) ?></h3>
				
				<div class="wsl_component_about_div">
					<p>
						<?php _wsl_e( $item['description'], 'wordpress-social-login' ) ?>
						<br />
						<?php echo sprintf( _wsl__( '<em>By <a href="%s">%s</a></em>' , 'wordpress-social-login' ), $item['developer_link'], $item['developer_name'] ); ?>
					</p>
				</div>

				<a class="button button-secondary thickbox" href="#TB_inline?width=600&height=350&inlineId=not-yet-id" target="_blank"><?php _wsl_e( "Get this Component", 'wordpress-social-login' ) ?></a> 
			</div>	
		<?php
	}
?> 

<div class="wsl_component_div">
	<h3 style="margin:0px;"><?php _wsl_e( "Build yours", 'wordpress-social-login' ) ?></h3>

	<div class="wsl_component_about_div">
		<p><?php _wsl_e( "Want to build your own custom <b>WordPress Social Login</b> component? It's pretty easy. Just refer to the online developer documentation.", 'wordpress-social-login' ) ?></p>
	</div>

	<a class="button button-primary" href="http://miled.github.io/wordpress-social-login/documentation.html" target="_blank"><?php _wsl_e( "WSL Developer API", 'wordpress-social-login' ) ?></a> 
	<a class="button button-secondary thickbox" href="#TB_inline?width=600&height=350&inlineId=not-yet-id" target="_blank"><?php _wsl_e( "Submit your WSL Component", 'wordpress-social-login' ) ?></a> 
</div>

<div id="not-yet-id" style="display:none;">
    <h5>Not yet p</h5>
</div>

<?php
	// HOOKABLE: 
	do_action( "wsl_component_components_gallery_end" );
}

// --------------------------------------------------------------------	
