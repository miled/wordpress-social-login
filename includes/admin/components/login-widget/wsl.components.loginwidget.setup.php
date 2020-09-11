<?php
/*!
* WordPress Social Login
*
* https://miled.github.io/wordpress-social-login/ | https://github.com/miled/wordpress-social-login
*   (c) 2011-2020 Mohamed Mrassi and contributors | https://wordpress.org/plugins/wordpress-social-login/
*/

/**
* Widget Customization
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------

function wsl_component_loginwidget_setup()
{
	// HOOKABLE:
	do_action( "wsl_component_loginwidget_setup_start" );

	$sections = array(
		'basic_settings'    => 'wsl_component_loginwidget_setup_basic_settings',
		'advanced_settings' => 'wsl_component_loginwidget_setup_advanced_settings',
		'custom_css'        => 'wsl_component_loginwidget_setup_custom_css',
	);

	$sections = apply_filters( 'wsl_component_loginwidget_setup_alter_sections', $sections );

	foreach( $sections as $section => $action )
	{
		add_action( 'wsl_component_loginwidget_setup_sections', $action );
	}
?>
<div>
	<?php
		// HOOKABLE:
		do_action( 'wsl_component_loginwidget_setup_sections' );
	?>

	<br />

	<div style="margin-left:5px;margin-top:-20px;">
        <input type="submit" class="button-primary" value="<?php _wsl_e("Save Settings", 'wordpress-social-login') ?>" />

        &nbsp; <a href="javascript:window.scrollTo(0, 0);"><?php _wsl_e("↑ Scroll back to top", 'wordpress-social-login') ?></a>
	</div>
</div>
<?php
}

// --------------------------------------------------------------------

function wsl_component_loginwidget_setup_basic_settings()
{
?>
<div class="stuffbox">
	<h3>
		<label><?php _wsl_e("Basic Settings", 'wordpress-social-login') ?></label>
	</h3>
	<div class="inside">
		<p>
			<?php _wsl_e("<b>Connect with caption :</b> Change the content of the label to display above WSL widget", 'wordpress-social-login') ?>.
		</p>

		<p>
			<?php _wsl_e("<b>Social icon set :</b> WSL provides two set of icons to display on the widget", 'wordpress-social-login') ?>.
			<?php _wsl_e("You can also display the providers names instead of icons. This allow the customization of the widget to a great extent", 'wordpress-social-login') ?>.
		</p>

		<p>
			<?php _wsl_e("<b>Users avatars :</b> Determines whether to show users avatars from social networks or to display the default ones", 'wordpress-social-login') ?>.

			<?php _wsl_e("Avatars display should work right out of the box with most WordPress themes, BuddyPress and bbPress", 'wordpress-social-login') ?>.
		</p>

		<table width="100%" border="0" cellpadding="5" cellspacing="2" style="border-top:1px solid #ccc;">
		  <tr>
			<td width="180" align="right"><strong><?php _wsl_e("Connect with caption", 'wordpress-social-login') ?> :</strong></td>
			<td>
			<input type="text" class="inputgnrc" style="width:535px" value="<?php _wsl_e( get_option( 'wsl_settings_connect_with_label' ), 'wordpress-social-login' ); ?>" name="wsl_settings_connect_with_label" >
			</td>
		  </tr>
		  <tr>
			<td align="right"><strong><?php _wsl_e("Social icon set", 'wordpress-social-login') ?> :</strong></td>
			<td>
				<?php
					$icon_sets = array(
						'wpzoom'   => "WPZOOM social networking icon set",
						'icondock' => "Icondock vector social media icons",
					);

					$icon_sets = apply_filters( 'wsl_component_loginwidget_setup_alter_icon_sets', $icon_sets );

					$wsl_settings_social_icon_set = get_option( 'wsl_settings_social_icon_set' );
				?>
				<select name="wsl_settings_social_icon_set" style="width:535px">
					<?php
						foreach( $icon_sets as $folder => $label )
						{
							?>
								<option <?php if( $wsl_settings_social_icon_set == $folder ) echo "selected"; ?>   value="<?php echo $folder; ?>"><?php _wsl_e( $label, 'wordpress-social-login' ) ?></option>
							<?php
						}
					?>
					<option <?php if( $wsl_settings_social_icon_set == "none" ) echo "selected"; ?>     value="none"><?php _wsl_e("None, display providers names instead of icons", 'wordpress-social-login') ?></option>
				</select>
			</td>
		  </tr>
		  <tr>
			<td align="right"><strong><?php _wsl_e("Users avatars", 'wordpress-social-login') ?> :</strong></td>
			<td>
				<select name="wsl_settings_users_avatars" style="width:535px">
					<option <?php if( ! get_option( 'wsl_settings_users_avatars' ) ) echo "selected"; ?> value="0"><?php _wsl_e("Display the default WordPress avatars", 'wordpress-social-login') ?></option>
					<option <?php if(   get_option( 'wsl_settings_users_avatars' ) ) echo "selected"; ?> value="1"><?php _wsl_e("Display users avatars from social networks when available", 'wordpress-social-login') ?></option>
				</select>
			</td>
		  </tr>
		</table>
	</div>
</div>
<?php
}

// --------------------------------------------------------------------

function wsl_component_loginwidget_setup_advanced_settings()
{
?>
<div class="stuffbox">
	<h3>
		<label><?php _wsl_e("Advanced Settings", 'wordpress-social-login') ?></label>
	</h3>
	<div class="inside">
		<p>
			<?php _wsl_e("<b>Redirect URL :</b> By default and after they authenticate, users will be automatically redirected to the page where they come from. If WSL wasn't able to identify where they come from (or if they used wp-login page to connect), then they will be redirected to <code>Redirect URL</code> instead", 'wordpress-social-login') ?>.
		</p>

		<p>
			<?php _wsl_e("<b>Force redirection :</b> When set to <b>Yes</b>, users will be <b>always</b> redirected to <code>Redirect URL</code>", 'wordpress-social-login') ?>.
		</p>

		<p>
			<?php _wsl_e("<b>Authentication display :</b> Determines how the authentication dialog is rendered. You can chose to open the dialog in a <b>popup</b> or to <b>in page</b>. If a user is visiting using a mobile device, WSL will fall back to more <b>in page</b>", 'wordpress-social-login') ?>.
		</p>

		<p>
			<?php _wsl_e("<b>Widget display :</b> Determines where you want to show the authentication widget", 'wordpress-social-login') ?>.
		</p>

		<p>
			<?php _wsl_e("<b>Notification :</b> Determines whether you want to receive a notification by mail when a new user is logged in via WSL", 'wordpress-social-login') ?>.
		</p>

		<table width="100%" border="0" cellpadding="5" cellspacing="2" style="border-top:1px solid #ccc;">
		  <tr>
			<td width="180" align="right"><strong><?php _wsl_e("Redirect URL", 'wordpress-social-login') ?> :</strong></td>
			<td>
				<input type="text" name="wsl_settings_redirect_url" class="inputgnrc" style="width:535px" value="<?php echo get_option( 'wsl_settings_redirect_url' ); ?>">
			</td>
		  </tr>
		  <tr>
			<td align="right"><strong><?php _wsl_e("Force redirection", 'wordpress-social-login') ?> :</strong></td>
			<td>
				<select name="wsl_settings_force_redirect_url" style="width:100px">
					<option <?php if( get_option( 'wsl_settings_force_redirect_url' ) == 1 ) echo "selected"; ?> value="1"><?php _wsl_e("Yes", 'wordpress-social-login') ?></option>
					<option <?php if( get_option( 'wsl_settings_force_redirect_url' ) == 2 ) echo "selected"; ?> value="2"><?php _wsl_e("No", 'wordpress-social-login') ?></option>
				</select>
			</td>
		  </tr>
		  <tr>
			<td align="right"><strong><?php _wsl_e("Authentication display", 'wordpress-social-login') ?> :</strong></td>
			<td>
				<select name="wsl_settings_use_popup" style="width:100px">
					<option <?php if( get_option( 'wsl_settings_use_popup' ) == 1 ) echo "selected"; ?> value="1"><?php _wsl_e("Popup", 'wordpress-social-login') ?></option>
					<option <?php if( get_option( 'wsl_settings_use_popup' ) == 2 ) echo "selected"; ?> value="2"><?php _wsl_e("In Page", 'wordpress-social-login') ?></option>
				</select>
			</td>
		  </tr>
		  <tr>
			<td align="right"><strong><?php _wsl_e("Widget display", 'wordpress-social-login') ?> :</strong></td>
			<td>
				<?php
					$widget_display = array(
						4 => "Do not display the widget anywhere, I'll use shortcodes",
						1 => "Display the widget in the comments area, login and register forms",
						3 => "Display the widget only in the login and register forms",
						2 => "Display the widget only in the comments area",
					);

					$widget_display = apply_filters( 'wsl_component_loginwidget_setup_alter_widget_display', $widget_display );

					$wsl_settings_widget_display = get_option( 'wsl_settings_widget_display' );
				?>
				<select name="wsl_settings_widget_display" style="width:535px">
					<?php
						foreach( $widget_display as $display => $label )
						{
							?>
								<option <?php if( $wsl_settings_widget_display == $display ) echo "selected"; ?>   value="<?php echo $display; ?>"><?php _wsl_e( $label, 'wordpress-social-login' ) ?></option>
							<?php
						}
					?>
				</select>
			</td>
		  </tr>
		  <tr>
			<td align="right"><strong><?php _wsl_e("Notification", 'wordpress-social-login') ?> :</strong></td>
			<td>
				<?php
					$users_notification = array(
						1 => "Notify ONLY the blog admin of a new user",
					);

					$users_notification = apply_filters( 'wsl_component_loginwidget_setup_alter_users_notification', $users_notification );

					$wsl_settings_users_notification = get_option( 'wsl_settings_users_notification' );
				?>
				<select name="wsl_settings_users_notification" style="width:535px">
					<option <?php if( $wsl_settings_users_notification == 0 ) echo "selected"; ?> value="0"><?php _wsl_e("No notification", 'wordpress-social-login') ?></option>
					<?php
						foreach( $users_notification as $type => $label )
						{
							?>
								<option <?php if( $wsl_settings_users_notification == $type ) echo "selected"; ?>   value="<?php echo $type; ?>"><?php _wsl_e( $label, 'wordpress-social-login' ) ?></option>
							<?php
						}
					?>
				</select>
			</td>
		  </tr>
		</table>
	</div>
</div>
<?php
}

// --------------------------------------------------------------------

function wsl_component_loginwidget_setup_custom_css()
{
?>
<style>
	.com { color: #6c7c7c; }
	.lit { color: #195f91; }
	.pun, .opn, .clo { color: #93a1a1; }
	.fun { color: #dc322f; }
	.str, .atv { color: #D14; }
	.kwd, .prettyprint .tag { color: #1e347b; }
	.typ, .atn, .dec, .var { color: teal; }
	.pln { color: #48484c; }
	.prettyprint {
	  padding: 8px;
	  background-color: #f7f7f9;
	  border: 1px solid #e1e1e8;
	}
	.prettyprint.linenums {
	  -webkit-box-shadow: inset 40px 0 0 #fbfbfc, inset 41px 0 0 #ececf0;
		 -moz-box-shadow: inset 40px 0 0 #fbfbfc, inset 41px 0 0 #ececf0;
			  box-shadow: inset 40px 0 0 #fbfbfc, inset 41px 0 0 #ececf0;
	}
	ol.linenums {
	  margin: 0 0 0 33px; /* IE indents via margin-left */
	}
	ol.linenums li {
	  padding-left: 12px;
	  color: #bebec5;
	  text-shadow: 0 1px 0 #fff;
	  margin-bottom: 0;
	}
	.prettyprint code {
		background-color: #ffd88f;
		border-radius: 4px;
		color: #c7254e;
		font-size: 90%;
		padding: 2px 4px;
		text-shadow: 0 1px 0 #ffcf75;
	}
</style>
<div class="stuffbox">
	<h3>
		<label><?php _wsl_e("Custom CSS", 'wordpress-social-login') ?></label>
	</h3>
	<div class="inside">
		<p>
			<?php _wsl_e("To customize the default widget styles you can either: change the css in the <b>text area</b> bellow or add it to your website <b>theme</b> files", 'wordpress-social-login') ?>.
		</p>

		<textarea style="width:100%;height:120px;margin-top:6px;" name="wsl_settings_authentication_widget_css" dir="ltr"><?php echo get_option( 'wsl_settings_authentication_widget_css' );  ?></textarea>

		<br />

		<p>
			<?php _wsl_e("The basic widget markup is the following", 'wordpress-social-login') ?>:
		</p>

		<pre class="prettyprint linenums" dir="ltr"><ol class="linenums"><li class="L0"><span class="tag">&lt;div</span><span class="pln"> </span><span class="atn">class</span><span class="pun">=</span><span class="atv">"</span><code><span class="atv">wp-social-login-widget</span></code><span class="atv">"</span><span class="tag">&gt;</span></li><li class="L1"><span class="pln">&nbsp;</span></li><li class="L2"><span class="pln">    </span><span class="tag">&lt;div</span><span class="pln"> </span><span class="atn">class</span><span class="pun">=</span><span class="atv">"</span><code><span class="atv">wp-social-login-connect-with</span></code><span class="atv">"</span><span class="tag">&gt;</span><span class="pln">{connect_with_caption}</span><span class="tag">&lt;/div&gt;</span></li><li class="L3"><span class="pln">&nbsp;</span></li><li class="L4"><span class="pln">    </span><span class="tag">&lt;div</span><span class="pln"> </span><span class="atn">class</span><span class="pun">=</span><span class="atv">"</span><code><span class="atv">wp-social-login-provider-list</span></code><span class="atv">"</span><span class="tag">&gt;</span></li><li class="L5"><span class="pln">    </span></li><li class="L6"><span class="pln">        </span><span class="tag">&lt;a</span><span class="pln"> </span><span class="atn">class</span><span class="pun">=</span><span class="atv">"</span><code><span class="atv">wp-social-login-provider wp-social-login-provider-facebook</span></code><span class="atv">"</span><span class="tag">&gt;</span></li><li class="L7"><span class="pln">            </span><span class="tag">&lt;img</span><span class="pln"> </span><span class="atn">src</span><span class="pun">=</span><span class="atv">"{provider_icon_facebook}"</span><span class="pln"> </span><span class="tag">/&gt;</span></li><li class="L8"><span class="pln">        </span><span class="tag">&lt;/a&gt;</span></li><li class="L9"><span class="pln">&nbsp;</span></li><li class="L0"><span class="pln">        </span><span class="tag">&lt;a</span><span class="pln"> </span><span class="atn">class</span><span class="pun">=</span><span class="atv">"</span><code><span class="atv">wp-social-login-provider wp-social-login-provider-google</span></code><span class="atv">"</span><span class="tag">&gt;</span></li><li class="L1"><span class="pln">            </span><span class="tag">&lt;img</span><span class="pln"> </span><span class="atn">src</span><span class="pun">=</span><span class="atv">"{provider_icon_google}"</span><span class="pln"> </span><span class="tag">/&gt;</span></li><li class="L2"><span class="pln">        </span><span class="tag">&lt;/a&gt;</span></li><li class="L3"><span class="pln">&nbsp;</span></li><li class="L4"><span class="pln">        </span><span class="tag">&lt;a</span><span class="pln"> </span><span class="atn">class</span><span class="pun">=</span><span class="atv">"</span><code><span class="atv">wp-social-login-provider wp-social-login-provider-twitter</span></code><span class="atv">"</span><span class="tag">&gt;</span></li><li class="L5"><span class="pln">            </span><span class="tag">&lt;img</span><span class="pln"> </span><span class="atn">src</span><span class="pun">=</span><span class="atv">"{provider_icon_twitter}"</span><span class="pln"> </span><span class="tag">/&gt;</span></li><li class="L6"><span class="pln">        </span><span class="tag">&lt;/a&gt;</span></li><li class="L7"><span class="pln">&nbsp;</span></li><li class="L8"><span class="pln">    </span><span class="tag">&lt;/div&gt;</span><span class="pln"> </span><span class="com">&lt;!-- / div.wp-social-login-connect-options --&gt;</span></li><li class="L9"><span class="pln">&nbsp;</span></li><li class="L0"><span class="pln">    </span><span class="tag">&lt;div</span><span class="pln"> </span><span class="atn">class</span><span class="pun">=</span><span class="atv">"</span><code><span class="atv">wp-social-login-widget-clearing</span></code><span class="atv">"</span><span class="tag">&gt;&lt;/div&gt;</span></li><li class="L1"><span class="pln">&nbsp;</span></li><li class="L2"><span class="tag">&lt;/div&gt;</span><span class="pln"> </span><span class="com">&lt;!-- / div.wp-social-login-widget --&gt;</span></li></ol></pre>

	</div>
</div>
<?php
}

// --------------------------------------------------------------------
