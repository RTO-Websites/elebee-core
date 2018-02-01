<?php
/**
 * @since 1.0.0
 * @author RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 */

namespace ElebeeCore\Lib;


abstract class Visitee {

    public function accept( Visitor $visitor ) {

        $visitor->visit( $this );

    }

}