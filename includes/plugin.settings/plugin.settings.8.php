<iframe id="google_group_embed" src="javascript:void(0)" scrolling="no" frameborder="0" width="99%" style="margin-top: -24px;"></iframe> 
<script type="text/javascript"> 
var init = function() {
var forumEmbed = document.getElementById('google_group_embed');
var adjust = function() { 
	var height = document.all ? document.body.offsetHeight : window.innerHeight;
	forumEmbed.height = Math.max(height - 200, 470);   
};
var groupUrl = 'http://groups.google.com/forum/embed/?place=forum/hybridauth-plugins&showsearch=true&showpopout=false&parenturl=' + encodeURIComponent(window.location.href);
forumEmbed.src = groupUrl;
adjust();
window.onresize = adjust;
}(); 
</script> 