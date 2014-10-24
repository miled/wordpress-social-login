$(function () { 
	prettyPrint()

	// why so sad :)
	$('body').append( '<a href="https://github.com/miled/wordpress-social-login" target="_blank"><img alt="Fork me on GitHub" style="position: fixed; z-index: 2; border: 0pt none; margin: 0pt; padding: 0pt; right: 7px; top: 69px;" src="http://hybridauth.com/sf.net/wsl/new/assets/img/github.png" /></a><a href="https://wordpress.org/support/view/plugin-reviews/wordpress-social-login#postform" target="_blank"><img alt="Fork me on GitHub" style="position: fixed; z-index: 2; border: 0pt none; margin: 0pt; padding: 0pt; right: 15px; top: 125px;" src="http://hybridauth.com/sf.net/wsl/new/assets/img/rate.png" /></a>' )

	// am lazy p
	$('#content img').each(function() {
		$(this).wrap("<a target='_blank' href='" + $(this).attr("src") + "'</a>")
	})
	
	$('#content').prepend( '<div class="alert alert-warning"><h4 style="border: 0px none;">Information:</h4><p style="line-height: 24px;">This documentation has been updated to reflect the latest changes in the coming release <b>2.2.3</b>. Things are siltly different from the current version <b>2.2.2</b>.</p></div>' )
})
