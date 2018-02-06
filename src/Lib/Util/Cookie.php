<?php

/**
 * Cookie.php
 *
 * @since   0.2.0
 *
 * @package ElebeeCore\Lib\Util
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/Util/Cookie.html
 */

namespace ElebeeCore\Lib\Util;


defined( 'ABSPATH' ) || exit;

/**
 * Class Cookie
 *
 * @since   0.2.0
 *
 * @package ElebeeCore\Lib\Util
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/Util/Cookie.html
 */
class Cookie {

    /**
     * Read a cookie.
     *
     * @since   0.2.0
     *
     * @param string $name
     * @return string|null
     */
    public static function read( $name ) : string {

        /*
         * Dont't use filter_input in this place.
         * It will always be null if the cookie was set in this process.
         */
        return isset( $_COOKIE[$name] ) ? $_COOKIE[$name] : null;

    }

    /**
     * Write a cookie.
     *
     * @since   0.2.0
     *
     * @param string $name
     * @param mixed $value
     * @param int $expire
     * @param string $path
     *
     * @return void
     */
    public static function write( $name, $value, $expire = 86400, $path = '/' ) {

        $_COOKIE[$name] = $value;
        setcookie( $name, $value, time() + $expire, $path );

    }

}