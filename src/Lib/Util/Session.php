<?php

/**
 * Session.php
 *
 * @since   0.2.0
 *
 * @package ElebeeCore\Lib\Util
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/Util/Session.html
 */

namespace ElebeeCore\Lib\Util;


/**
 * Class Session
 *
 * @since   0.2.0
 *
 * @package ElebeeCore\Lib\Util
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/Util/Session.html
 */
class Session {

    /**
     * Start the session.
     *
     * @since   0.2.0
     *
     * @param int $lifetime (optional) (default: 0)
     * @return void
     */
    public static function start( int $lifetime = 0 ) {

        if ( self::status() === PHP_SESSION_NONE ) {
            session_set_cookie_params( $lifetime );
            session_start();
        }

    }

    /**
     * Get a session variable.
     *
     * @since   0.2.0
     *
     * @param string $var
     * @return mixed|null
     */
    public static function getVar( string $var ) {

        if ( isset( $_SESSION[$var] ) ) {
            return $_SESSION[$var];
        }

        return null;

    }

    /**
     * Set a session variable.
     *
     * @since   0.2.0
     *
     * @param string $var
     * @param mixed  $value
     * @return void
     */
    public static function setVar( string $var, $value ) {

        $_SESSION[$var] = $value;

    }

    /**
     * @since   0.2.0
     *
     * @return int
     */
    public static function status(): int {

        return session_status();

    }

    /**
     * @since   0.2.0
     *
     * @return bool
     */
    public static function isActive(): bool {

        return self::status() === PHP_SESSION_ACTIVE;

    }

}