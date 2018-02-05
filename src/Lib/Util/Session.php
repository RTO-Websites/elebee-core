<?php

/**
 * Session.php
 */

namespace ElebeeCore\Lib\Util;


/**
 * Class Session
 *
 * @package ElebeeCore
 * @author RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @since 0.2.0
 */
class Session {

    /**
     * Start the session.
     *
     * @param int $lifetime (optional) (default: 0)
     */
    public static function start( $lifetime = 0 ) {

        if ( self::status() === PHP_SESSION_NONE ) {
            session_set_cookie_params( $lifetime );
            session_start();
        }

    }

    /**
     * Get a session variable.
     *
     * @param $var
     *
     * @return mixed|null
     */
    public static function getVar( $var ) {

        if ( isset( $_SESSION[$var] ) ) {
            return $_SESSION[$var];
        }

        return null;

    }

    /**
     * Set a session variable.
     *
     * @param $var
     * @param $value
     *
     * @return void
     */
    public static function setVar( $var, $value ) {

        $_SESSION[$var] = $value;

    }

    /**
     * @return int
     */
    public static function status() {

        return session_status();

    }

    /**
     * @return bool
     */
    public static function isActive() {

        return self::status() === PHP_SESSION_ACTIVE;

    }

}