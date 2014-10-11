$(function () { 
	prettyPrint()
	
	// $('body').append( "<a href='https://github.com/hybridauth/WordPress-Social-Login'><img id='ribbon' alt='Fork me on GitHub' style='position: fixed; top: 37px; right: 0pt; z-index: 2; border: 0pt none; margin: 0pt; padding: 0pt;' src='https://s3.amazonaws.com/github/ribbons/forkme_right_red_aa0000.png' /></a>" )

	// $('#page').prepend( '<div class="alert-message info" style="margin-bottom:10px"><p style="line-height: 24px;"><b>Important:</b><br />This documentation has been updated to reflect the latest changes in the coming release of WordPress Social Login <b>2.2.1</b>. Things are partly different from the current version <b>2.1.6</b>.<br />To download WSL <b>2.2.1</b> and for more information, refer to <a href="release-preview-2.2.1.html" style="color:white;font-weight: bold; font-size: 15px;">&laquo; Preview the Upcoming Release and suggest any final changes &raquo;</b></a></p></div>' ) 

	$('#content img').each(function() {
		$(this).wrap("<a target='_blank' href='" + $(this).attr("src") + "'</a>")
	})
})
