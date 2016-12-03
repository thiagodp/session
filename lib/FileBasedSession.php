<?php
namespace phputil;

/**
 * File-based session. Uses PHP's default session implementation.
 *
 * @author	Thiago Delgado Pinto
 */
class FileBasedSession implements Session {
	
	//
	// STATE
	//

	/** @inheritDoc */
	function status() {
		return session_status();
	}
	
	/** @inheritDoc */
	function statusIsActive() {
		return PHP_SESSION_ACTIVE === $this->status();
	}
	
	/** @inheritDoc */
	function statusIsNone() {
		return PHP_SESSION_NONE === $this->status();
	}
	
	/** @inheritDoc */
	function statusIsDisabled() {
		return PHP_SESSION_DISABLED === $this->status();
	}	
	
	/** @inheritDoc */
	function start() {
		return session_start();
	}
	
	/** @inheritDoc */
	function id() {
		return session_id();
	}
	
	/** @inheritDoc */
	function setId( $newId ) {
		return session_id( $newId );
	}
	
	/** @inheritDoc */
	function name() {
		return session_name();
	}
	
	/** @inheritDoc */
	function setName( $newName ) {
		return session_name( $newName );
	}	
	
	/** @inheritDoc */
	function regenerateId( $deleteOldSession = false ) {
		return session_regenerate_id( $deleteOldSession );
	}
	
	/** @inheritDoc */
	function close() {
		session_write_close();
	}
	
	//
	// DATA
	//
	
	/** @inheritDoc */
	function destroy() {
		return session_destroy();
	}
	
	/** @inheritDoc */
	function get( $key ) {
		if ( ! isset( $_SESSION ) ) { return null; }
		return array_key_exists( $key, $_SESSION ) ? $_SESSION[ $key ] : null;
	}
	
	/** @inheritDoc */
	function getAll() {
		return isset( $_SESSION ) ? $_SESSION : array();
	}
	
	/** @inheritDoc */
	function set( $key, $value ) {
		if ( ! isset( $_SESSION ) ) { return $this; }
		$_SESSION[ $key ] = $value;
		return $this;
	}
	
	/** @inheritDoc */
	function put( $key, $value ) {
		return $this->set( $key, $value );
	}
	
	/** @inheritDoc */
	function putAll( array $array ) {
		foreach ( $array as $key => $value ) {
			$this->set( $key, $value );
		}
		return $this;
	}
	
	/** @inheritDoc */
	function has( $key ) {
		if ( ! isset( $_SESSION ) ) { return false; }
		return array_key_exists( $key, $_SESSION );
	}
	
	/** @inheritDoc */
	function remove( $key ) {
		if ( ! isset( $_SESSION ) ) { return false; }
		if ( array_key_exists( $key, $_SESSION ) ) {
			unset( $_SESSION[ $key ] );
			return true;
		}
		return false;
	}
	
	/** @inheritDoc */
	function clear() {
		if ( ! isset( $_SESSION ) ) { return; }
		session_unset();
		$_SESSION = array();
	}
	
	//
	// COOKIES
	//
	
	/** @inheritDoc */
	function useCookies() {
		return ini_get( 'session.use_cookies' );
	}
	
	/** @inheritDoc */
	function cookieParams() {
		return session_get_cookie_params();
	}
	
	/** @inheritDoc */
	function setCookieParams( $lifetime, $path = '/', $domain = '', $secure = false, $httponly = false ) {
		session_set_cookie_params( $lifetime, $path, $domain, $secure, $httponly );
	}
	
	/** @inheritDoc */
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