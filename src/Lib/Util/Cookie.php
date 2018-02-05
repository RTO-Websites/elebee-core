<?php

/**
 * Cookie.php
 */

namespace ElebeeCore\Lib\Util;


/**
 * Class Cookie
 *
 * @package ElebeeCore
 * @author RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @since 0.2.0
 */
class Cookie {

    /**
     * Read a cookie.
     *
     * @param string $name
     *
     * @return mixed
     */
    public static function read( $name ) {

        /*
         * Dont't use filter_input in this place.
         * It will always be null if the cookie was set in this process.
         */
        return isset( $_COOKIE[$name] ) ? $_COOKIE[$name] : null;

    }

    /**
     * Write a cookie.
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