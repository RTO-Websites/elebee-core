<?php
/**
 * Control.php
 */

namespace ElebeeCore\Lib\ThemeCustomizer;


/**
 * Class Control
 *
 * @package ElebeeCore
 * @author RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @since 0.2.0
 */
class Control extends ThemeCustommizerElement {

    /**
     * {@inheritdoc}
     */
    public function register( \WP_Customize_Manager $wpCustomize ) {

        $wpCustomize->add_control( $this->getId(), $this->getArgs() );

    }

}