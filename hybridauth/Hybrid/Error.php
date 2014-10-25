<?php
/**
* HybridAuth
* http://hybridauth.sourceforge.net | http://github.com/hybridauth/hybridauth
* (c) 2009-2014, HybridAuth authors | http://hybridauth.sourceforge.net/licenses.html
*/

/**
 * Errors manager
 * 
 * HybridAuth errors are stored in Hybrid::storage() and not displayed directly to the end user 
 */
class Hybrid_Error
{
	/**
	* Store error in session
	*
	* @param String $message
	* @param Number $code
	*/
	public static function setError( $message, $code = NULL )
	{
		Hybrid_Auth::storage()->set( "hauth_session.error.status"  , 1         );
		Hybrid_Auth::storage()->set( "hauth_session.error.message" , $message  );
		Hybrid_Auth::storage()->set( "hauth_session.error.code"    , $code     );
	}

	/**
	* Clear the last error
	*/
	public static function clearError()
	{
		Hybrid_Auth::storage()->delete( "hauth_session.error.status"   );
		Hybrid_Auth::storage()->delete( "hauth_session.error.message"  );
		Hybrid_Auth::storage()->delete( "hauth_session.error.code"     );
	}

	/**
	* Checks to see if there is a an error. 
	* 
	* @return boolean True if there is an error.
	*/
	public static function hasError()
	{ 
		return (bool) Hybrid_Auth::storage()->get( "hauth_session.error.status" );
	}

	/**
	* return error message 
	*/
	public static function getErrorMessage()
	{ 
		return Hybrid_Auth::storage()->get( "hauth_session.error.message" );
	}

	/**
	* return error code  
	*/
	public static function getErrorCode()
	{ 
		return Hybrid_Auth::storage()->get( "hauth_session.error.code" );
	}

	/**
	* set api error
	*/
	public static function setApiError( $error )
	{ 
		return Hybrid_Auth::storage()->set( "hauth_session.error.apierror", $error );
	}

	/**
	* set api error
	*/
	public static function deleteApiError()
	{ 
		return Hybrid_Auth::storage()->delete( "hauth_session.error.apierror" );
	}

	/**
	* return api error
	*/
	public static function getApiError()
	{ 
		return Hybrid_Auth::storage() ? Hybrid_Auth::storage()->get( "hauth_session.error.apierror" ) : '';
	}
}
