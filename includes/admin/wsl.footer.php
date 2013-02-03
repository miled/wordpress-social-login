<?php
/*!
* WordPress Social Login
*
* http://hybridauth.sourceforge.net/wsl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*   (c) 2013 Mohamed Mrassi and other contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

/**
* Display Admin Pages footer
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------
?>
</div> <!-- ./wsl_admin_tab_content -->  
<div class="clear"></div>
<?php wsl_admin_localize_widget(); ?>

<script>
// check for new versions and updates
jQuery.getScript("http://hybridauth.sourceforge.net/wsl/wsl.version.check.and.updates.php?v=<?php echo $WORDPRESS_SOCIAL_LOGIN_VERSION ?>");
</script>
