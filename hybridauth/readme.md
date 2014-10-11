# HybridAuth Library

HybridAuth is an Open-source social sign on PHP Library. HybridAuth goal is to act as an abstract api between your application and various social apis and identities providers such as Facebook, Twitter, MySpace and Google.
http://hybridauth.sourceforge.net

### Used version

"hybridauth/hybridauth": "dev-master" 2.3.0-dev 2014-09-22 18:16 UTC

### Changes made

###### hybridauth/Hybrid/Storage.php 
* L-13 Remove that stupid interface and make Hybridauth compatible with PHP 5.2 again

###### hybridauth/Hybrid/thirdparty/OAuth/OAuth1Client.php 
* L-236 API errors responses

###### hybridauth/Hybrid/thirdparty/OAuth/OAuth2Client.php 
* L-240 API errors responses

###### hybridauth/Hybrid/thirdparty/LinkedIn/LinkedIn.php 
* L-712 API errors responses

###### hybridauth/Hybrid/thirdparty/Facebook/base_facebook.php
* L-145 add CURLOPT_SSL_VERIFYPEER => false
* L-1378 API errors responses

###### hybridauth/Hybrid/Providers/Facebook.php
* L-18 Default score
* L-190 Reassign emailVerified 

###### hybridauth/Hybrid/Providers/Google.php
* L-19  Default score
* L-114 Reassign emailVerified 

###### hybridauth/Hybrid/Providers/LinkedIn.php
* L-117 Get LinkedIn members headline as description. Summary no longuer work
* L-132 Get full avatar form LinkedIn

###### hybridauth/Hybrid/Providers/Yahoo.php
* Overwritten with the openID adapter
* L-26 Assign email as user identifier

###### hybridauth/Hybrid/Providers/Vkontakte.php
* L-79 Image size
* L-93 Image size

###### hybridauth/Hybrid/Providers/Steam.php
* L-29 fall back to curl if file_get_contents didn't work
* L-53 Image size
* L-56 user region

###### hybridauth/Hybrid/Providers/Reddit.php
* Add an unsupported provider

###### hybridauth/Hybrid/Providers/PixelPin.php
* Add an unsupported provider

###### hybridauth/Hybrid/Providers/Mixi.php
* Add an unsupported provider

###### hybridauth/Hybrid/Providers/Latch.php
* Add an unsupported provider

###### /hybridauth/Hybrid/Provider_Model_OpenID.php
* Merge PR #324
* Fix genders

###### hybridauth/Hybrid/Providers/WordPress.php
* Add an unsupported provider

### Important 
* If you have updated the library manually then do not leave hybridauth install.php in this directory. The install file is not needed and present a security risk.
* HybridAuth requires PHP 5.3 (see changes).

## License
Except where otherwise noted, HybridAuth is released under dual licence MIT and GPL.
