$(function () { 
	prettyPrint();

	$('body').append( '<a href="https://github.com/miled/wordpress-social-login" target="_blank"><img alt="Fork me on GitHub" style="position: fixed; z-index: 2; border: 0pt none; margin: 0pt; padding: 0pt; right: 7px; top: 69px;" src="http://miled.github.io/wordpress-social-login/assets/img/github.png" /></a><a href="https://wordpress.org/support/view/plugin-reviews/wordpress-social-login#postform" target="_blank"><img alt="Fork me on GitHub" style="position: fixed; z-index: 2; border: 0pt none; margin: 0pt; padding: 0pt; right: 15px; top: 125px;" src="http://miled.github.io/wordpress-social-login/assets/img/rate.png" /></a>' )

	$('#content img').each(function() {
		$(this).wrap("<a target='_blank' href='" + $(this).attr("src") + "'</a>")
	})

	$('#content').prepend( '<div class="alert alert-success" style="border: 0 none; background-color: #21b2a6;"><p style=" color: #fff; line-height: 24px; text-align:center; margin-bottom: 0px;"><b style="border: 0px none; margin-top:0;">Announcing an upcoming major release:</b><br />A <span style="color: #c7254e">beta</span> update of WordPress Social Login 3.0.3 is currently available for <a href="http://miled.github.io/wordpress-social-login/betas/wordpress-social-login-3.0.3.zip" style="color: white; text-decoration: underline;">Download</a>.<br />Feedback and bug fixes are welcome either on <a href="https://github.com/miled/wordpress-social-login" target="_blank" style="color: white; text-decoration: underline;">Github</a> or in the discussion thread at <a href="https://wordpress.org/support/topic/announcing-new-upcoming-release/" target="_blank" style="color: white; text-decoration: underline;">Wordpress.org</a></span>.</div>' ) 
	
	//-

	window.addEventListener("scroll", function() {
		if ( window.scrollY > 50 ) {
			$('.navbar').css( 'opacity', 0.8 )
		}
		else {
			$('.navbar').css( 'opacity', 1 )
		}
	},false)
})
