<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*    (c) 2011-2014 Mohamed Mrassi and contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 

// --------------------------------------------------------------------

function wsl_component_networks_whyhello()
{
	// HOOKABLE: 
	do_action( "wsl_component_networks_whyhello_start" );
?>
<div class="postbox " id="linksubmitdiv"> 
	<div class="inside">
		<div id="submitlink" class="submitbox"> <h3 style="cursor: default;"><?php _wsl_e("Welcome to WordPress Social Login", 'wordpress-social-login') ?></h3>
			<div id="minor-publishing"> 

				<div style="display:none;"><input type="submit" value="Save" class="button" id="save" name="save"></div>

				<div id="misc-publishing-actions">
					<div class="misc-pub-section"> 
						<p style="line-height: 19px;font-size: 13px;" >
							<?php _wsl_e('<b>WordPress Social Login</b> allows your website visitors and customers to register on using their existing social account ID, eliminating the need to fill out registration forms and remember usernames and passwords', 'wordpress-social-login') ?>.
						</p>
						<p style="line-height: 19px;">
							<?php _wsl_e('By default, we have enabled <b>Facebook</b>, <b>Google</b> and <b>Twitter</b>, however you may add even more networks from the section bellow', 'wordpress-social-login') ?>.
						</p>  
						<p style="line-height: 19px;">
							<?php _wsl_e('For further information, we recommend to read the online <b><a href="http://hybridauth.sourceforge.net/wsl/index.html" target="_blank">WSL user guide</a></b>', 'wordpress-social-login') ?>.
						</p> 
						<p style="line-height: 19px;">
							<?php _wsl_e('If you run into any issue, then refer to <b><a href="options-general.php?page=wordpress-social-login&amp;wslp=help">Help &amp; Support</a></b> to konw how to reach me', 'wordpress-social-login') ?>.
						</p> 
					</div>
				</div> 
			</div>
		</div>
	</div>
</div>
<?php
	// HOOKABLE: 
	do_action( "wsl_component_networks_whyhello_end" );	
} 

// --------------------------------------------------------------------	
