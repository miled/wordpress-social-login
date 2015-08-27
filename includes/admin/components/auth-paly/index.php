<?php
/*!
* WordPress Social Login
*
* http://miled.github.io/wordpress-social-login/ | https://github.com/miled/wordpress-social-login
*  (c) 2011-2015 Mohamed Mrassi and contributors | http://wordpress.org/plugins/wordpress-social-login/
*/

/**
* Authentication Playground
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/*!
	Important

	Direct access to providers apis is newly introduced into WSL and we are still experimenting, so they may change in future releases.
*/

// --------------------------------------------------------------------

function wsl_component_authtest()
{
	// HOOKABLE:
	do_action( "wsl_component_authtest_start" );

	$adapter      = null;
	$provider_id  = isset( $_REQUEST["provider"] ) ? $_REQUEST["provider"] : null;
	$user_profile = null;

	$assets_base_url = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . 'assets/img/';

	if( ! class_exists( 'Hybrid_Auth', false ) )
	{
		require_once WORDPRESS_SOCIAL_LOGIN_ABS_PATH . "hybridauth/Hybrid/Auth.php";
	}

	try
	{
		$provider = Hybrid_Auth::getAdapter( $provider_id );

		// make as few call as possible
		if( ! ( isset( $_SESSION['wsl::userprofile'] ) && $_SESSION['wsl::userprofile'] && $user_profile = json_decode( $_SESSION['wsl::userprofile'] ) ) )
		{
			$user_profile = $provider->getUserProfile();

			$_SESSION['wsl::userprofile'] = json_encode( $user_profile );
		}

		$adapter = $provider->adapter;
	}
	catch( Exception $e )
	{
	}

	$ha_profile_fields = array(
		array( 'field' => 'identifier'  , 'label' => _wsl__( "Provider user ID" , 'wordpress-social-login') ),
		array( 'field' => 'profileURL'  , 'label' => _wsl__( "Profile URL"      , 'wordpress-social-login') ),
		array( 'field' => 'webSiteURL'  , 'label' => _wsl__( "Website URL"      , 'wordpress-social-login') ),
		array( 'field' => 'photoURL'    , 'label' => _wsl__( "Photo URL"        , 'wordpress-social-login') ),
		array( 'field' => 'displayName' , 'label' => _wsl__( "Display name"     , 'wordpress-social-login') ),
		array( 'field' => 'description' , 'label' => _wsl__( "Description"      , 'wordpress-social-login') ),
		array( 'field' => 'firstName'   , 'label' => _wsl__( "First name"       , 'wordpress-social-login') ),
		array( 'field' => 'lastName'    , 'label' => _wsl__( "Last name"        , 'wordpress-social-login') ),
		array( 'field' => 'gender'      , 'label' => _wsl__( "Gender"           , 'wordpress-social-login') ),
		array( 'field' => 'language'    , 'label' => _wsl__( "Language"         , 'wordpress-social-login') ),
		array( 'field' => 'age'         , 'label' => _wsl__( "Age"              , 'wordpress-social-login') ),
		array( 'field' => 'birthDay'    , 'label' => _wsl__( "Birth day"        , 'wordpress-social-login') ),
		array( 'field' => 'birthMonth'  , 'label' => _wsl__( "Birth month"      , 'wordpress-social-login') ),
		array( 'field' => 'birthYear'   , 'label' => _wsl__( "Birth year"       , 'wordpress-social-login') ),
		array( 'field' => 'email'       , 'label' => _wsl__( "Email"            , 'wordpress-social-login') ),
		array( 'field' => 'phone'       , 'label' => _wsl__( "Phone"            , 'wordpress-social-login') ),
		array( 'field' => 'address'     , 'label' => _wsl__( "Address"          , 'wordpress-social-login') ),
		array( 'field' => 'country'     , 'label' => _wsl__( "Country"          , 'wordpress-social-login') ),
		array( 'field' => 'region'      , 'label' => _wsl__( "Region"           , 'wordpress-social-login') ),
		array( 'field' => 'city'        , 'label' => _wsl__( "City"             , 'wordpress-social-login') ),
		array( 'field' => 'zip'         , 'label' => _wsl__( "Zip"              , 'wordpress-social-login') ),
	);
?>
<style>
	.widefat td, .widefat th { border: 1px solid #DDDDDD; }
	.widefat th label { font-weight: bold; }

	.wp-social-login-provider-list { padding: 10px; }
	.wp-social-login-provider-list a {text-decoration: none; }
	.wp-social-login-provider-list img{ border: 0 none; }
</style>

<div class="metabox-holder columns-2" id="post-body">
	<table width="100%">
		<tr valign="top">
			<td>
				<?php if( ! $adapter ): ?>
					<div style="padding: 15px; margin-bottom: 8px; border: 1px solid #ddd; background-color: #fff;box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
						<p><?php _wsl_e("Connect with a provider to get started", 'wordpress-social-login') ?>.</p>
					</div>
				<?php else: ?>
					<div class="stuffbox">
						<h3>
							<label><?php _wsl_e("Connected adapter specs", 'wordpress-social-login') ?></label>
						</h3>
						<div class="inside">
							<table class="wp-list-table widefat">
								<tr>
									<th width="200"><label><?php _wsl_e("Provider", 'wordpress-social-login') ?></label></th>
									<td><?php echo $adapter->providerId; ?></td>
								</tr>

								<?php if( isset( $adapter->openidIdentifier ) ): ?>
									<tr>
										<th width="200"><label><?php _wsl_e("OpenID Identifier", 'wordpress-social-login') ?></label></th>
										<td><?php echo $adapter->openidIdentifier; ?></td>
									</tr>
								<?php endif; ?>

								<?php if( isset( $adapter->scope ) ): ?>
									<tr>
										<th width="200"><label><?php _wsl_e("Scope", 'wordpress-social-login') ?></label></th>
										<td><?php echo $adapter->scope; ?></td>
									</tr>
								<?php endif; ?>

								<?php if( isset( $adapter->config['keys'] ) ): ?>
									<tr>
										<th width="200"><label><?php _wsl_e("Application keys", 'wordpress-social-login') ?></label></th>
										<td><div style="max-width:650px"><?php echo json_encode( $adapter->config['keys'] ); ?></div></td>
									</tr>
								<?php endif; ?>

								<?php if( $adapter->token("access_token") ): ?>
									<tr>
										<th width="200"><label><?php _wsl_e("Access token", 'wordpress-social-login') ?></label></th>
										<td><div style="max-width:650px"><?php echo $adapter->token("access_token"); ?></div></td>
									</tr>
								<?php endif; ?>

								<?php if( $adapter->token("access_token_secret") ): ?>
									<tr>
										<th width="200"><label><?php _wsl_e("Access token secret", 'wordpress-social-login') ?></label></th>
										<td><?php echo $adapter->token("access_token_secret"); ?></td>
									</tr>
								<?php endif; ?>

								<?php if( $adapter->token("expires_in") ): ?>
									<tr>
										<th width="200"><label><?php _wsl_e("Access token expires in", 'wordpress-social-login') ?></label></th>
										<td><?php echo (int) $adapter->token("expires_at") - time(); ?> <?php _wsl_e("second(s)", 'wordpress-social-login') ?></td>
									</tr>
								<?php endif; ?>

								<?php if( $adapter->token("expires_at") ): ?>
									<tr>
										<th width="200"><label><?php _wsl_e("Access token expires at", 'wordpress-social-login') ?></label></th>
										<td><?php echo date( DATE_W3C, $adapter->token("expires_at" ) ); ?></td>
									</tr>
								<?php endif; ?>
							</table>
						</div>
					</div>

					<?php
						$console = false;

						if( ! isset( $adapter->openidIdentifier ) ):
					?>
						<div class="stuffbox">
							<h3>
								<label><?php _wsl_e("Connected adapter console", 'wordpress-social-login') ?></label>
							</h3>
							<div class="inside">
								<?php
									$path   = isset( $adapter->api->api_base_url ) ? $adapter->api->api_base_url : '';
									$path   = isset( $_REQUEST['console-path']   ) ? $_REQUEST['console-path']   : $path;
									$method = isset( $_REQUEST['console-method'] ) ? $_REQUEST['console-method'] : '';
									$query  = isset( $_REQUEST['console-query']  ) ? $_REQUEST['console-query']  : '';

									$response = '';

									if( $path && in_array( $method, array( 'GET', 'POST' ) ) )
									{
										$console = true;

										try
										{
											if( $method == 'GET' )
											{
												$response = $adapter->api->get( $path . ( $query ? '?' . $query : '' ) );
											}
											else
											{
												$response = $adapter->api->get( $path, $query );
											}

											$response = $response ? $response : Hybrid_Error::getApiError();
										}
										catch( Exception $e )
										{
											$response = "ERROR: " . $e->getMessage();
										}
									}
								?>
								<form action="" method="post"/>
									<table class="wp-list-table widefat">
										<tr>
											<th width="200"><label><?php _wsl_e("Path", 'wordpress-social-login') ?></label></th>
											<td><input type="text" style="width:96%" name="console-path" value="<?php echo htmlentities( $path ); ?>"><a href="https://apigee.com/providers" target="_blank"><img src="<?php echo $assets_base_url . 'question.png' ?>" style="vertical-align: text-top;" /></a></td>
										</tr>
										<tr>
											<th width="200"><label><?php _wsl_e("Method", 'wordpress-social-login') ?></label></th>
											<td><select style="width:100px" name="console-method"><option value="GET" <?php if( $method == 'GET' ) echo 'selected'; ?>>GET</option><!-- <option value="POST" <?php if( $method == 'POST' ) echo 'selected'; ?>>POST</option>--></select></td>
										</tr>
										<tr>
											<th width="200"><label><?php _wsl_e("Query", 'wordpress-social-login') ?></label></th>
											<td><textarea style="width:100%;height:60px;margin-top:6px;" name="console-query"><?php echo htmlentities( $query ); ?></textarea></td>
										</tr>
									</table>

									<br />

									<input type="submit" value="<?php _wsl_e("Submit", 'wordpress-social-login') ?>" class="button">
								</form>
							</div>
						</div>

						<?php if( $console ): ?>
							<div class="stuffbox">
								<h3>
									<label><?php _wsl_e("API Response", 'wordpress-social-login') ?></label>
								</h3>
								<div class="inside">
									<textarea rows="25" cols="70" wrap="off" style="width:100%;height:400px;margin-bottom:15px;font-family: monospace;font-size: 12px;"><?php echo htmlentities( print_r( $response, true ) ); ?></textarea>
								</div>
							</div>
						<?php if( 0 ): ?>
							<div class="stuffbox">
								<h3>
									<label><?php _wsl_e("Code PHP", 'wordpress-social-login') ?></label>
								</h3>
								<div class="inside">
<textarea rows="25" cols="70" wrap="off" style="width:100%;height:210px;margin-bottom:15px;font-family: monospace;font-size: 12px;"
>include_once WORDPRESS_SOCIAL_LOGIN_ABS_PATH . 'hybridauth/Hybrid/Auth.php';

/*!
	Important

	Direct access to providers apis is newly introduced into WSL and we are still experimenting, so they may change in future releases.
*/

try
{
    $<?php echo strtolower( $adapter->providerId ); ?> = Hybrid_Auth::getAdapter( '<?php echo htmlentities( $provider_id ); ?>' );

<?php if( $method == 'GET' ): ?>
    $response = $<?php echo strtolower( $adapter->providerId ); ?>->api()->get( '<?php echo htmlentities( $path . ( $query ? '?' . $query : '' ) ); ?>' );
<?php else: ?>
    $response = $<?php echo strtolower( $adapter->providerId ); ?>->api()->post( '<?php echo htmlentities( $path ); ?>', (array) $query );
<?php endif; ?>
}
catch( Exception $e )
{
    echo "Ooophs, we got an error: " . $e->getMessage();
}</textarea>
								</div>
							</div>
							<div class="stuffbox">
								<h3>
									<label><?php _wsl_e("Connected adapter debug", 'wordpress-social-login') ?></label>
								</h3>
								<div class="inside">
									<textarea rows="25" cols="70" wrap="off" style="width:100%;height:400px;margin-bottom:15px;font-family: monospace;font-size: 12px;"><?php echo htmlentities( print_r( $adapter, true ) ); ?></textarea>
								</div>
							</div>
							<div class="stuffbox">
								<h3>
									<label><?php _wsl_e("PHP Session", 'wordpress-social-login') ?></label>
								</h3>
								<div class="inside">
									<textarea rows="25" cols="70" wrap="off" style="width:100%;height:350px;margin-bottom:15px;font-family: monospace;font-size: 12px;"><?php echo htmlentities( print_r( $_SESSION, true ) ); ?></textarea>
								</div>
							</div>
						<?php endif; ?>
						<?php endif; ?>
					<?php endif; ?>

					<?php if( ! $console ): ?>
						<div class="stuffbox">
							<h3>
								<label><?php _wsl_e("Connected user social profile", 'wordpress-social-login') ?></label>
							</h3>
							<div class="inside">
								<table class="wp-list-table widefat">
									<?php
										$user_profile = (array) $user_profile;

										foreach( $ha_profile_fields as $item )
										{
											$item['field'] = $item['field'];
										?>
											<tr>
												<th width="200">
													<label><?php echo $item['label']; ?></label>
												</th>
												<td>
													<?php
														if( isset( $user_profile[ $item['field'] ] ) && $user_profile[ $item['field'] ] )
														{
															$field_value = $user_profile[ $item['field'] ];

															if( in_array( strtolower( $item['field'] ), array( 'profileurl', 'websiteurl', 'email' ) ) )
															{
																?>
																	<a href="<?php if( $item['field'] == 'email' ) echo 'mailto:'; echo $field_value; ?>" target="_blank"><?php echo $field_value; ?></a>
																<?php
															}
															elseif( strtolower( $item['field'] ) == 'photourl' )
															{
																?>
																	<a href="<?php echo $field_value; ?>" target="_blank"><img width="36" height="36" align="left" src="<?php echo $field_value; ?>" style="margin-right: 5px;" > <?php echo $field_value; ?></a>
																<?php
															}
															else
															{
																echo $field_value;
															}
														}
													?>
												</td>
											</tr>
										<?php
										}
									?>
								</table>
							</div>
						</div>
					<?php endif; ?>
				<?php endif; ?>
			</td>
			<td width="10"></td>
			<td width="400">
				<div class="postbox">
					<div class="inside">
						<h3><?php _wsl_e("Authentication Playground", 'wordpress-social-login') ?></h3>

						<div style="padding:0 20px;">
							<p>
								<?php _wsl_e('Authentication Playground will let you authenticate with the enabled social networks without creating any new user account', 'wordpress-social-login') ?>.
							</p>
							<p>
								<?php _wsl_e('This tool will also give you a direct access to social networks apis via a lightweight console', 'wordpress-social-login') ?>.
							</p>
						</div>
					</div>
				</div>

				</style>
				<div class="postbox">
					<div class="inside">
						<div style="padding:0 20px;">
							<p>
								<?php _wsl_e("Connect with", 'wordpress-social-login') ?>:
							</p>

							<div style="width: 380px; padding: 10px; border: 1px solid #ddd; background-color: #fff;">
								<?php do_action( 'wordpress_social_login', array( 'mode' => 'test', 'caption' => '' ) ); ?>
							</div>
						</div>
					</div>
				</div>
			</td>
		</tr>
	</table>
</div>
<?php
	// HOOKABLE:
	do_action( "wsl_component_authtest_end" );
}

wsl_component_authtest();

// --------------------------------------------------------------------
