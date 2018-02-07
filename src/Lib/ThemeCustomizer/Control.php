<?php
/**
 * Control.php
 *
 * @since   0.2.0
 *
 * @package ElebeeCore\Lib\ThemeCustomizer
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/ThemeCustomizer/Control.html
 */

namespace ElebeeCore\Lib\ThemeCustomizer;


defined( 'ABSPATH' ) || exit;

/**
 * Class Control
 *
 * @since   0.2.0
 *
 * @package ElebeeCore\Lib\ThemeCustomizer
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/ThemeCustomizer/Control.html
 */
class Control extends ThemeCustommizerElement {

    /**
     * @since 0.2.0
     */
    public function register( \WP_Customize_Manager $wpCustomize ) {

        $wpCustomize->add_control( $this->getId(), $this->getArgs() );

    }

}