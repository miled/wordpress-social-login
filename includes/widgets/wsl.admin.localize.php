<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*   (c) 2013 Mohamed Mrassi and other contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

/**
* Users invitational to help us localize WordPress Social Login
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------

function wsl_admin_localize_widget()
{
	global $WORDPRESS_SOCIAL_LOGIN_TEXTS;

	// default endpoint_url
	$assets_url = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL;

	// overwrite endpoint_url if need'd
	if( get_option( 'wsl_settings_base_url' ) ){
		$assets_url = strtolower( get_option( 'wsl_settings_base_url' ) );
	}

	$assets_url .= '/assets/img/';

	$current_user = wp_get_current_user();
	
	$wslp = "networks"; 

	if( isset( $_REQUEST["wslp"] ) ){
		$wslp = $_REQUEST["wslp"];
	}
?> 
<form action="http://hybridauth.sourceforge.net/wsl/i18n.contributions.php" method="post" target="_blank"> 
<input type="hidden" name="wsl_i18n_site_url" value="<?php bloginfo('url'); ?>" />
<input type="hidden" name="wsl_i18n_site_charset" value="<?php bloginfo('charset'); ?>" />
<input type="hidden" name="wsl_i18n_site_language" value="<?php bloginfo('language'); ?>" />
<input type="hidden" name="wsl_i18n_current_page" value="<?php echo $wslp ?>" />
<input type="hidden" name="wsl_version" value="<?php echo wsl_version() ?>" />
<div id="wsl_i18n_cla">
		<h3 style="margin:0px;"><?php _wsl_e( "Contributor License Agreement", 'wordpress-social-login' ) ?></h3> 

		<hr />

		<p>
			<?php _wsl_e( "You are about to submit your contributions to the WordPress Social Login Website to be reviewed for inclusion in future versions", 'wordpress-social-login' ) ?>.

			<?php _wsl_e( "You hereby grant the permission to publish your contribution, in whole or in part, and to made it available  under the <b>MIT License</b>, for the <b>Wordpress community</b> to, freely use or misuse", 'wordpress-social-login' ) ?>.
		</p>

		<hr />
		
		<div> 
			<a class="button-secondary" href="admin.php?page=wordpress-social-login"><?php _wsl_e( "Hell No", 'wordpress-social-login' ) ?></a>
			<input style="float:right" type="submit" value="<?php _wsl_e( "Yes, I agree to contribute my translation", 'wordpress-social-login' ) ?>" class="button-primary"  onClick="wsl_i18n_done()" > 
		</div> 
</div> 
<div id="wsl_i18n_form"> 
	<h3 style="margin:0px;"><?php _wsl_e( "Help us localize WordPress Social Login", 'wordpress-social-login' ) ?></h3> 

	<hr />

	<p><?php _wsl_e( "You can <b>translate as much you pleases</b> as much <b>as you want</b>. You don't have to translate everything in this page, but every word counts. Ignore any string you want or aleardy translated. You could also use this tool to fix any typo you may find or to improve the current language expressions", 'wordpress-social-login' ) ?>.</p>

	<p><?php _wsl_e( "Your name allows us to recognize your contributions and bypass manual review, especially when you've been contributing in the past. So do supply some unique string, even if it's not your real name", 'wordpress-social-login' ) ?>.</p>

	<p><?php _wsl_e( "All the texts on this page are automatically extracted and generated on the form beside. If the translation tool has scapped something you may consider as irrelevant, please leave that particular field empty", 'wordpress-social-login' ) ?>.</p>

	<p><?php _wsl_e( "Your contributions will be sent to the WordPress Social Login website for inclusion in future versions", 'wordpress-social-login' ) ?>.</p>

	<p><?php _wsl_e( "Thanks!", 'wordpress-social-login' ) ?></p>
</div>
<div id="wsl_i18n">  
	<pre id="wsl_i18n_pre"></pre> 
	<hr />
	<table width="100%" cellspacing="2" cellpadding="5" border="0"> 
		<tbody>
			<tr>
			<td align="right"><strong><?php _wsl_e( "Your Name", 'wordpress-social-login' ) ?>:</strong> (<?php _wsl_e( "optional", 'wordpress-social-login' ) ?>)</td>
			<td> 
			<input name="wsl_i18n_name" type="text" value="<?php echo htmlentities( $current_user->display_name . " - " . get_bloginfo('name') ); ?>" class="inputgnrc" style="width:300px;"> 
			</td>
		  </tr>
			<tr>
			<td align="right"><strong><?php _wsl_e( "Comment", 'wordpress-social-login' ) ?>:</strong> (<?php _wsl_e( "optional", 'wordpress-social-login' ) ?>)</td>
			<td> 
			<textarea name="wsl_i18n_comment" style="width:300px;height:40px;"></textarea>
			</td>
		  </tr> 
		  <tr>
			<td align="right"><strong><?php _wsl_e( "Target Language", 'wordpress-social-login' ) ?>:</strong></td>
			<td> 
				<select name="wsl_i18n_locale" style="width:300px;"><option value="en">English (enhancement)</option><option value="af">Afrikaans</option><option value="sq">Albanian</option><option value="ar">Arabic</option><option value="eu">Basque</option><option value="be">Belarusian</option><option value="bn_IN">Bengali (India)</option><option value="bg">Bulgarian</option><option value="ca">Catalan</option><option value="zh_CN">Chinese (Simplified)</option><option value="zh_TW">Chinese (Traditional)</option><option value="cs">Czech</option><option value="da">Danish</option><option value="nl">Dutch</option><option value="eo">Esperanto</option><option value="et">Estonian</option><option value="fi">Finnish</option><option value="fr">French</option><option value="fy_NL">Frisian</option><option value="gl">Galician</option><option value="ka">Georgian</option><option value="de">German</option><option value="el">Greek</option><option value="gu_IN">Gujarati</option><option value="he">Hebrew</option><option value="hi_IN">Hindi</option><option value="hu">Hungarian</option><option value="is">Icelandic</option><option value="id">Indonesian</option><option value="ga_IE">Irish</option><option value="it">Italian</option><option value="ja">Japanese</option><option value="kn">Kannada</option><option value="ko">Korean</option><option value="ku">Kurdish</option><option value="lv">Latvian</option><option value="lt">Lithuanian</option><option value="mk">Macedonian</option><option value="mr">Marathi</option><option value="mn">Mongolian</option><option value="nb_NO">Norwegian (Bokmal)</option><option value="nn_NO">Norwegian (Lengadocian)</option><option value="oc">Occitan (Lengadocian)</option><option value="pl">Polish</option><option value="pt_BR">Portuguese (Brazilian)</option><option value="pt_PT">Portuguese (Portugal)</option><option value="pa_IN">Punjabi</option><option value="ro">Romanian</option><option value="ru">Russian</option><option value="sr">Serbian</option><option value="si">Sinhala</option><option value="sk">Slovak</option><option value="sl">Slovenian</option><option value="es">Spanish</option><option value="es_AR">Spanish (Argentina)</option><option value="sv_SE">Swedish</option><option value="te">Telgu</option><option value="th">Thai</option><option value="tr">Turkish</option><option value="uk">Ukrainian</option></select>
			</td>
		  </tr> 
		</tbody>
	</table> 
	<hr /> 
	<div style="float:right"> 
		<a class="button-primary" href="javascript:void(0);" onClick="wsl_i18n_cla()"><?php _wsl_e( "Submit changes", 'wordpress-social-login' ) ?></a>
	</div>
</div>
</form> 
<script>
	function wsl_i18n_cla(){
		jQuery("#wsl_i18n_form").hide()
		jQuery("#wsl_i18n").hide()
		jQuery("#wsl_i18n_cla").show()
	}

	function wsl_i18n_done(){
		jQuery("#wsl_i18n_form").hide()
		jQuery("#wsl_i18n").hide()
		jQuery("#wsl_i18n_cla").hide()

		jQuery("#wsl_admin_tab_content").show()
		jQuery("#l10n-footer").show()
	}

	function wsl_i18n(){
		if(typeof jQuery=="undefined"){
			alert( "Error: WordPress Social Login require jQuery to be installed on your wordpress in order to works!" )

			return
		}

		jQuery("#wsl_admin_tab_content").hide()
		jQuery("#l10n-footer").hide()
		jQuery("#wsl_i18n_form").show() 
		jQuery("#wsl_i18n").show()
		jQuery("#wsl_i18n_cla").hide()
		
		var __wsl_texts = <?php echo json_encode ( array_keys( $WORDPRESS_SOCIAL_LOGIN_TEXTS ) ); ?>
		
		console.log( __wsl_texts );
		
		jQuery.each( __wsl_texts, function(index, string) {
			if( string.length >= 2 ){ 
				if( string.length >= 100 ) jQuery("#wsl_i18n_pre").append( index + ".\n" + '<textarea style="width:98%;height:60px;" name="wsl_i18n_target_'+ index +'">' + string + '</textarea>' + "\n" )
				if( string.length <  100 ) jQuery("#wsl_i18n_pre").append( index + ".\n" + '<input class="inputgnrc" type="text" style="width:98%;" name="wsl_i18n_target_'+ index +'" value="' + string + '" >' + "\n" )

				jQuery("#wsl_i18n_pre").append( '<textarea style="display:none;" name="wsl_i18n_default_'+ index +'">' + string + '</textarea>' + "\n" )
			} 
		})
	}
</script>
<div id="l10n-footer" style="float: left; display: block; ">
	<br style="clear:both;" />
	<hr />
	<img src="<?php echo $assets_url ?>flags.png">
	<a href="#wsltop" onclick="return wsl_i18n();">
		<?php _wsl_e( "Help us localize this page", 'wordpress-social-login' ) ?>
    </a>
</div>
	<?php

	return;
}

// --------------------------------------------------------------------
