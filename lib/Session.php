<?php
namespace phputil;

/**
 * A simple object-oriented wrapper to PHP's session functions.
 *
 * @author	Thiago Delgado Pinto
 */
class Session {
	
	// STATE __________________________________________________________________
	
	/**
	 *  Return {@code true} if a session exists.
	 *  
	 *  @return bool
	 */
	function exists() {
		return PHP_SESSION_ACTIVE == $this->status();
	}
	
	/**
	 *  Return {@code true} if sessions are enabled.
	 *  
	 *  @return bool
	 */
	function enabled() {
		$status = $this->status();
		return PHP_SESSION_ACTIVE == $status || PHP_SESSION_NONE == $status;
	}

	/**
	 *  Return session status, according to the server configuration and current state:
	 *  
	 *  - `PHP_SESSION_ACTIVE`		if sessions are enabled and one exists.
	 *  - `PHP_SESSION_NONE`		if sessions are enabled but none exists.
	 *  - `PHP_SESSION_DISABLED`	if sessions are disabled.
	 *  
	 *  @return int
	 */
	function status() {
		return session_status();
	}
	
	
	/**
	 *  Start new or resume existing session.
	 *  
	 *  @return  bool
	 */
	function start() {
		return session_start();
	}
	
	/**
	 *  Return the current session id.
	 *  
	 *  @return string
	 */
	function id() {
		return session_id();
	}
	
	/**
	 *  Set the current session id. Should be called BEFORE start().
	 *  
	 *  @param	string $newId	The new id. Allowed characters are: [a-zA-Z,-]
	 *  @return string			The name of the current session.
	 */
	function setId( $newId ) {
		return session_id( $newId );
	}
	
	/**
	 *  Return the current session name. It is used in cookies and URLs (e.g. PHPSESSID).
	 *  
	 *  @return string
	 */
	function name() {
		return session_name();
	}
	
	/**
	 *  Set the current session name. Should be called BEFORE start().
	 *  
	 *  @param	string $newId	The new id. Allowed characters are: [a-zA-Z-0-9]
	 *  @return string			The name of the current session.
	 */
	function setName( $newName ) {
		return session_name( $newName );
	}	
	
	/**
	 *  Update the current session id with a newly generated one.
	 *  
	 *  @param	bool $deleteOldSession	Whether to delete the old associated session file or not.
	 *  @return	bool
	 */
	function regenerateId( $deleteOldSession = false ) {
		return session_regenerate_id( $deleteOldSession );
	}
	
	/**
	 *  Close the session with the current data.
	 */
	function close() {
		session_write_close();
	}
	
	/**
	 *  Destroys all of the data associated with the current session. It does not unset
	 *  any of the global variables associated with the session, or unset the session cookie.
	 *  
	 *  @return bool
	 */
	function destroy() {
		return session_destroy();
	}
	
	// DATA ___________________________________________________________________
	
	/**
	 *  Return the value of a given key of null if it doesn't exist.
	 *  
	 *  @param int|string $key	The key of the value.
	 *  @return mixed
	 */
	function get( $key ) {
		return array_key_exists( $key, $_SESSION ) ? $_SESSION[ $key ] : null;
	}
	
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
	function set( $key, $value ) {
		$_SESSION[ $key ] = $value;
		return $this;
	}
	
	/**
	 *  Same as #set.
	 */
	function put( $key, $value ) {
		return $this->set( $key, $value );
	}
	
	/**
	 *  Set all the array keys and values in the session. This method is chainable.
	 *  
	 *  @param array $array	Keys and the corresponding values.
	 *  @return Session		The class instance (this).
	 */
	function putAll( array $array ) {
		foreach ( $array as $key => $value ) {
			$this->set( $key, $value );
		}
		return $this;
	}
	
	/**
	 *  Return true whether the session holds the given key.
	 *  
	 *  @param int|string $key	The key to be checked.
	 *  @return bool
	 */
	function has( $key ) {
		return array_key_exists( $key, $_SESSION );
	}
	
	
	/**
	 *  Remove a given key from the session.
	 *  
	 *  @param int|string $key	The key to be removed.
	 *  @return bool
	 */
	function remove( $key ) {
		if ( array_key_exists( $key, $_SESSION ) ) {
			unset( $_SESSION[ $key ] );
			return true;
		}
		return false;
	}
	
	/**
	 *  Clear the session data.
	 */
	function clear() {
		session_unset();
		$_SESSION = array();
	}	
	
	// COOKIE _________________________________________________________________
	
	/**
	 *  Return {@code true} whether the session use cookies.
	 *  
	 *  @return bool
	 */
	function useCookies() {
		return ini_get( 'session.use_cookies' );
	}
	
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
	function cookieParams() {
		return session_get_cookie_params();
	}
	
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
	function setCookieParams( $lifetime, $path = '/', $domain = '', $secure = false, $httponly = false ) {
		session_set_cookie_params( $lifetime, $path, $domain, $secure, $httponly );
	}
	
	
	/**
	 *  Destroy the session cookie.
	 *  
	 *  @return bool
	 */
	function destroyCookie() {
		if ( ! $this->useCookies() ) {
			return false;
		}
		
		$value = ''; // empty, but it doesn't matter
		$expiration = time() - 12960000; // a time in the past. that's the point.
		$params = $this->cookieParams();
		
		return setcookie( $this->name(), $value, $expiration,
			$params[ 'path' ],
			$params[ 'domain' ],
			$params[ 'secure' ],
			$params[ 'httponly' ]
			);
	}
	
}
?>