<?php
namespace phputil;

/**
 * Server session.
 *
 * @author	Thiago Delgado Pinto
 */
interface Session {
	
	//
	// STATE
	//

	/**
	 *  Return session status, according to the server configuration and current state:
	 *  
	 *  - `PHP_SESSION_ACTIVE`		if sessions are enabled and one exists.
	 *  - `PHP_SESSION_NONE`		if sessions are enabled but none exists.
	 *  - `PHP_SESSION_DISABLED`	if sessions are disabled.
	 *  
	 *  @return int
	 */
	function status();
	
	/**
	 *  Return {@code true} if status is PHP_SESSION_ACTIVE.
	 *  
	 *  @return bool
	 */
	function statusIsActive();
	
	/**
	 *  Return {@code true} if status is PHP_SESSION_NONE.
	 *  
	 *  @return bool
	 */
	function statusIsNone();
	
	/**
	 *  Return {@code true} if status is PHP_SESSION_DISABLED.
	 *  
	 *  @return bool
	 */
	function statusIsDisabled();
	
	/**
	 *  Start new or resume existing session.
	 *  
	 *  @return  bool
	 */
	function start();
	
	/**
	 *  Return the current session id.
	 *  
	 *  @return string
	 */
	function id();
	
	/**
	 *  Set the current session id. Should be called BEFORE start().
	 *  
	 *  @param	string $newId	The new id. Allowed characters are: [a-zA-Z,-]
	 *  @return string			The name of the current session.
	 */
	function setId( $newId );
	
	/**
	 *  Return the current session name. It is used in cookies and URLs (e.g. PHPSESSID).
	 *  
	 *  @return string
	 */
	function name();
	
	/**
	 *  Set the current session name. Should be called BEFORE start().
	 *  
	 *  @param	string $newId	The new id. Allowed characters are: [a-zA-Z-0-9]
	 *  @return string			The name of the current session.
	 */
	function setName( $newName );
	
	/**
	 *  Update the current session id with a newly generated one.
	 *  
	 *  @param	bool $deleteOldSession	Whether to delete the old associated session file or not.
	 *  @return	bool
	 */
	function regenerateId( $deleteOldSession = false );
	
	/**
	 *  Close the session with the current data.
	 */
	function close();
	
	/**
	 *  Destroys all of the data associated with the current session. It does not unset
	 *  any of the global variables associated with the session, or unset the session cookie.
	 *  
	 *  @return bool
	 */
	function destroy();
	
	//
	// DATA
	//
	
	/**
	 *  Return the value of a given key of null if it doesn't exist.
	 *  
	 *  @param int|string $key	The key of the value.
	 *  @return mixed
	 */
	function get( $key );
	
	/**
	 *  Set the value for a given key. Return the current session instance
	 *  to allow chained calls.
	 *  
	 *  Example: $session->set( 'name', 'Bob' )->set( 'surname', 'Marley' );
	 *  
	 *  @param int|string	$key	The key of the value.
	 *  @param mixed		$value	The value to be set.
	 *  @return Session				The class instance (this).
	 */
	function set( $key, $value );
	
	/**
	 *  Same as #set.
	 */
	function put( $key, $value );
	
	/**
	 *  Set all the array keys and values in the session. This method is chainable.
	 *  
	 *  @param array $array	Keys and the corresponding values.
	 *  @return Session		The class instance (this).
	 */
	function putAll( array $array );
	
	/**
	 *  Return true whether the session holds the given key.
	 *  
	 *  @param int|string $key	The key to be checked.
	 *  @return bool
	 */
	function has( $key );
	
	/**
	 *  Remove a given key from the session.
	 *  
	 *  @param int|string $key	The key to be removed.
	 *  @return bool
	 */
	function remove( $key );
	
	/**
	 *  Clear the session data.
	 */
	function clear();
	
	//
	// COOKIES
	//
	
	/**
	 *  Return {@code true} whether the session use cookies.
	 *  
	 *  @return bool
	 */
	function useCookies();
	
	/**
	 *  Return an array with the cookie parameters:
	 *  
	 *  - `lifetime`	: The lifetime of the cookie in seconds.
	 *  - `path`		: The path where information is stored.
	 *  - `domain'		: The domain of the cookie.
	 *  - `secure`		: The cookie should only be sent over secure connections.
	 *  - `httponly`	: The cookie can only be accessed through the HTTP protocol.
	 *  
	 *  @return array
	 */
	function cookieParams();
	
	/**
	 *  Set the session cookie parameters, defined in the php.ini file.
	 *  The effect of this function only lasts for the duration of the script.
	 *  Thus, you need to call this method for every request and before start() is called.
	 *  
	 *  @param int		$lifetime	Lifetime of the session cookie, defined in seconds.
	 *  
	 *  @param string	$path		Path on the domain where the cookie will work. Use a
	 *  							single slash ('/') for all paths on the domain.
	 *  
	 *  @param string	$domain		Cookie domain, for example 'www.php.net'. To make
	 *  							cookies visible on all subdomains then the domain must
	 *  							be prefixed with a dot like '.php.net'.
	 *  
	 *  @param bool		$secure		If TRUE cookie will only be sent over secure connections.
	 *  
	 *  @param bool		$httponly	If set to TRUE then PHP will attempt to send the httponly
	 *  							flag when setting the session cookie.
	 *  
	 */
	function setCookieParams( $lifetime, $path = '/', $domain = '', $secure = false, $httponly = false );
	
	/**
	 *  Destroy the session cookie.
	 *  
	 *  @return bool
	 */
	function destroyCookie();
}
?>