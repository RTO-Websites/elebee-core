<?php
/**
 * Visitor.php
 *
 * @since   0.2.0
 *
 * @package ElebeeCore\Lib
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/Util/Visitor.html
 */

namespace ElebeeCore\Lib\Util;


defined( 'ABSPATH' ) || exit;

/**
 * Interface Visitor
 *
 * @since   0.2.0
 *
 * @package ElebeeCore\Lib
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/Util/Visitor.html
 */
interface Visitor {

    /**
     * @since 0.2.0
     *
     * @param Visitee $visitee
     * @return void
     */
    public function visit( Visitee $visitee );

}
