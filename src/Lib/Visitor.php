<?php
/**
 * @since 0.2.0
 * @author RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 */

namespace ElebeeCore\Lib;


interface Visitor {

    public function visit( Visitee $visitee );

}
