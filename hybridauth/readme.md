# HybridAuth Library

HybridAuth is an Open-source social sign on PHP Library. HybridAuth goal is to act as an abstract api between your application and various social apis and identities providers such as Facebook, Twitter, MySpace and Google.
http://hybridauth.sourceforge.net

### Used version

"hybridauth/hybridauth": "dev-master" 2.3.0-dev 2014-09-22 18:16 UTC

### Changes made

hybridauth/Hybrid/thirdparty/Facebook/base_facebook.php
	L-145 add CURLOPT_SSL_VERIFYPEER => false

hybridauth/Hybrid/Providers/Google.php
	L-114 Reassign emailVerified

hybridauth/Hybrid/Providers/Yahoo.php
	Overwritten with the openID adapter

hybridauth/Hybrid/Providers/Vkontakte.php
	L-79 Image size
	L-93 Image size

hybridauth/Hybrid/Providers/Reddit.php
	Add an unsupported provider

hybridauth/Hybrid/Providers/PixelPin.php
	Add an unsupported provider

hybridauth/Hybrid/Providers/Mixi.php
	Add an unsupported provider

hybridauth/Hybrid/Providers/Latch.php
	Add an unsupported provider

/hybridauth/Hybrid/Provider_Model_OpenID.php
	Merge PR #324

hybridauth/Hybrid/Providers/WordPress.php
	Add an unsupported provider

### Important

If you have updated the library manually then do not leave hybridauth install.php in this directory. The install file is not needed and present a security risk.
HybridAuth is compatible with PHP 5.3.

## License
Except where otherwise noted, HybridAuth is released under dual licence MIT and GPL.
