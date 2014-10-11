<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*    (c) 2011-2014 Mohamed Mrassi and contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

/**
* WSL Watchdog - Log viewer.
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 

// --------------------------------------------------------------------

function wsl_component_watchdog()
 {
	// HOOKABLE: 
	do_action( "wsl_component_watchdog_start" ); 
	
	$assets_base_url = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . '/assets/img/16x16/';
?>
<style>
	.widefatop td, .widefatop th { border: 1px solid #DDDDDD; }
	.widefatop th label { font-weight: bold; }  
</style>

<div style="padding: 5px 20px; border: 1px solid #ddd; background-color: #fff;">

	<h3><?php _wsl_e("Latest WSL activity", 'wordpress-social-login') ?></h3>
	
	<!--
		<p style="float: right;margin-top:-45px">
			<a class="button button-secondary" style="background-color: #da4f49;border-color: #bd362f;text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);color: #ffffff;" href="" onClick="return confirm('Are you sure?');"><?php _wsl_e("Delete WSL Log", 'wordpress-social-login'); ?></a>
		</p>
	-->

	<hr />

	<?php 
		global $wpdb;

		$list_sessions = $wpdb->get_results( "SELECT user_ip, session_id, provider, max(id) FROM `{$wpdb->prefix}wslwatchdog` GROUP BY session_id, provider LIMIT 50" );  

		// have contacts?
		if( ! $list_sessions ){
			_wsl_e("<p>No log found!</p>", 'wordpress-social-login');
			_wsl_e("<p class='description'>To log WSL authentication process in database, include '/utilities/watchdog.php' in 'wp-social-login.php'.</p>", 'wordpress-social-login');
		}
		else{
			// echo '<pre>';
			// print_r( $list_sessions );
			foreach( $list_sessions as $seesion_data ){
				$user_ip    = $seesion_data->user_ip;
				$session_id = $seesion_data->session_id;
				$provider   = $seesion_data->provider;

				if( ! $provider ){
					continue; 
				}

				?>
					<div style="padding: 15px; margin-bottom: 8px; border: 1px solid #ddd; background-color: #fff;box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
						<img src="<?php echo $assets_base_url . strtolower( $provider ) . '.png' ?>" style="vertical-align:top;width:16px;height:16px;" /> <?php echo sprintf( _wsl__("<b>%s</b> : %s - %s", 'wordpress-social-login'), $provider, $user_ip, $session_id ) ?>
					</div>
				<?php
				
				$list_calls = $wpdb->get_results( "SELECT * FROM `{$wpdb->prefix}wslwatchdog` WHERE session_id = '$session_id' AND provider = '$provider' ORDER BY id ASC LIMIT 500" );  

				$abandon    = false;
				$newattempt = false;
				$newsession = true;
				$exectime   = 0;
				$oexectime  = 0;
			?>
				<table class="wp-list-table widefat widefatop">
					<tr>
						<th>#</th>
						<th>Action</th>
						<th>Action Args</th>
						<th>Time</th>
						<th>Exec</th>
						<th>User</th>
					</tr>
			<?php
				foreach( $list_calls as $call_data ){

					$exectime  = (float) $call_data->created_at - ( $oexectime ? $oexectime : (float) $call_data->created_at );
					$oexectime = (float) $call_data->created_at;

					if( $abandon && 'wsl_process_login' == $call_data->action_name ){
						$abandon = false;
						$newattempt = true;
					}

					if(  'wsl_process_login' == $call_data->action_name && ! stristr( $call_data->url, 'redirect_to_provider=true' ) && ! stristr( $call_data->url, 'action=wordpress_social_authenticated' ) ){
						$newattempt = true;
					}

					if( $abandon ){
						continue; 
					}

					if( $newattempt && ! $newsession ){
						?>
							</table>
							<h5>New attempt</h5>
							<table class="wp-list-table widefat widefatop">
								<tr>
									<th>#</th>
									<th>Action</th>
									<th>Action Args</th>
									<th>Time</th>
									<th>Exec</th>
									<th>User</th>
								</tr>
						<?php

						$exectime = 0;
						$oexectime = 0;
					}
					
					$call_data->action_args = json_decode( $call_data->action_args );

					$newattempt = false;
					
					$action_name_uid = uniqid();
					
					$action_desc = 'N.A.';
					?>
					<tr  style="<?php if( 'wsl_render_login_form_user_loggedin' == $call_data->action_name || $call_data->action_name == 'wsl_hook_process_login_before_wp_set_auth_cookie' ) echo 'background-color:#edfff7;'; ?><?php if( 'wsl_process_login_complete_registration_start' == $call_data->action_name ) echo 'background-color:#fefff0;'; ?><?php if( 'wsl_process_login_render_error_page' == $call_data->action_name || $call_data->action_name == 'wsl_process_login_render_notice_page' ) echo 'background-color:#fffafa;'; ?>">
						<td nowrap width="10">
							<?php echo $call_data->id; ?>
						</td>
						<td nowrap width="350">
							<span style="color:#<?php 
											if( 'wsl_hook_process_login_before_wp_safe_redirect' == $call_data->action_name ){
												echo 'a6354b';
											}

											if( 'wsl_hook_process_login_before_wp_set_auth_cookie' == $call_data->action_name ){
												echo '9035a6';
											}

											if( 'wsl_process_login_render_error_page' == $call_data->action_name ){
												echo 'f50505';

												$newattempt = true;
											}

											if( 'wsl_process_login_render_notice_page' == $call_data->action_name ){
												echo 'fa1797';
												
												$newattempt = true;
											}

											if( 'wsl_render_login_form_user_loggedin' == $call_data->action_name ){
												echo '12b0fa';

												$abandon = true;
											} 
										?>"
										><?php echo $call_data->action_name; ?></span>
						</td>
						<td>
							<span style="float:right;"><a style="font-size:25px" href="javascript:void(0);" onClick="action_args_toggle( '<?php echo $action_name_uid; ?>' )">+</a></span>
							<a href="javascript:alert('<?php echo $call_data->url; ?>');">
								<small>
									<?php
										echo substr( $call_data->url, 0, 100 );
										echo strlen( $call_data->url ) > 100 ? '...' : '';
									?>
								</small>
							</a>
							<pre style="display:none; overflow:scroll; background-color:#fcfcfc; color:#808080;font-size:11px;max-width:750px;" class="action_args_<?php echo $action_name_uid; ?>"><?php echo htmlentities( print_r( $call_data->action_args, true ) ); ?></pre>
						</td>
						<td nowrap width="115">
							<?php echo date( "Y-m-d h:i:s", $call_data->created_at ); ?>
						</td>
						<td nowrap width="10" style="<?php if( $exectime > 0.5 ) echo 'color: #f44 !important;'; ?>">
							<?php echo number_format( $exectime, 3, '.', '' ); ?>
						</td>
						<td nowrap width="40">
							<?php if( $call_data->user_id ) echo '<a href="options-general.php?page=wordpress-social-login&wslp=users&uid=' . $call_data->user_id . '">#' . $call_data->user_id . '</a>'; ?>
						</td>
					</tr>
				<?php
					$newsession = false;
				}
			?>
				</table>
			<?php
				echo '<br />';
			}
		}
	?>
	<script>
		function action_args_toggle( action ){
			jQuery('.action_args_' + action ).toggle();
			
			return false;
		}
	</script>
</div>
<?php
	// HOOKABLE: 
	do_action( "wsl_component_watchdog_end" );
}

wsl_component_watchdog();

// --------------------------------------------------------------------
