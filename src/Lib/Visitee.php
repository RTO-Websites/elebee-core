<?php
/**
 * Visitee.php
 *
 * @since   0.2.0
 *
 * @package ElebeeCore\Lib
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/Visitee.html
 */

namespace ElebeeCore\Lib;


defined( 'ABSPATH' ) || exit;

/**
 * Class Visitee
 *
 * @since   0.2.0
 *
 * @package ElebeeCore\Lib
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/Visitee.html
 */
abstract class Visitee {

    /**
     * @since 0.2.0
     *
     * @param Visitor $visitor
     * @return void
     */
    public function accept( Visitor $visitor ) {

        $visitor->visit( $this );

    }

}