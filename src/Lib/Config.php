<?php
/**
 * @since 1.0.0
 * @author hterhoeven
 * @licence MIT
 */

namespace ElebeeCore\Lib;


/**
 * Class Config
 *
 * @since 0.2.0
 * @package ElebeeCore\Lib
 */
class Config {

    /**
     * @since 0.2.0
     * @param $header
     * @return mixed
     */
    function disableRedirectGuess( $header ) {

        global $wp_query;

        if ( is_404() )
            unset( $wp_query->query_vars['name'] );

        return $header;

    }

}