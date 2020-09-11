/*!
* WordPress Social Login
*
* http://miled.github.io/wordpress-social-login/ | https://github.com/miled/wordpress-social-login
*  (c) 2011-2020 Mohamed Mrassi and contributors | http://wordpress.org/plugins/wordpress-social-login/
*/

/**
* Manage authentications via a popup
*
* Based on http://wordpress.org/extend/plugins/social-connect/
*/

/**
* bind on click event to idps icons
*/
(function($){
	$(function(){
		$(document).on( 'click', 'a.wp-social-login-provider', function(){
			popupurl = $( '#wsl_popup_base_url' ).val();

			provider = $(this).attr("data-provider");

			var width  = 768;
			var height = 480;
			var top    = ( screen.height / 2 ) - ( height / 2 ) - 50;
			var left   = ( screen.width  / 2 ) - ( width  / 2 );

			window.open( popupurl + '&provider=' + provider, 'hybridauth_social_sing_on', 'location=1,status=0,scrollbars=0,height=' + height + ',width=' + width + ',top=' + top + ',left=' + left);
		});
	});
})(jQuery);

/**
* generate login wp form
*/
window.wsl_wordpress_social_login = function( config ){
	jQuery( '#loginform' ).unbind( 'submit.simplemodal-login' );

	var form_id = '#loginform';

	if( ! jQuery( '#loginform' ).length )
	{
		if( jQuery( '#registerform' ).length )
		{
			form_id = '#registerform';
		}

		else {
			var login_uri = jQuery( '#wsl_login_form_uri' ).val();

			jQuery('body').append( '<form id="loginform" method="post" action="' + login_uri + '"></form>' );

			jQuery('#loginform').append( '<input type="hidden" id="redirect_to" name="redirect_to" value="' + window.location.href + '">' );
		}
	}

	jQuery.each( config, function( key, value ){
		jQuery( '#' + key ).remove();

		jQuery( form_id ).append( '<input type="hidden" id="' + key + '" name="' + key + '" value="' + value + '">' );
	});

	if( jQuery( '#simplemodal-login-form' ).length )
	{
		var current_url = window.location.href;

		jQuery( '#redirect_to' ).remove();

		jQuery( form_id ).append( '<input type="hidden" id="redirect_to" name="redirect_to" value="' + current_url + '">' );
	}

	jQuery( form_id ).submit();
}
