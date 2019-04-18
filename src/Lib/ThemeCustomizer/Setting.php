<?php
/**
 * Setting.php
 *
 * @since   0.2.0
 *
 * @package ElebeeCore\Lib\ThemeCustomizer
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/ThemeCustomizer/Setting.html
 */

namespace ElebeeCore\Lib\ThemeCustomizer;


\defined( 'ABSPATH' ) || exit;

/**
 * Class Setting
 *
 * @since   0.2.0
 *
 * @package ElebeeCore\Lib\ThemeCustomizer
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/ThemeCustomizer/Setting.html
 */
class Setting extends Element {

    /**
     * @since 0.2.0
     * @var Control
     */
    private $control;

    /**
     * Setting constructor.
     *
     * @since 0.2.0
     *
     * @param string $id
     * @param array  $settingArgs
     * @param array  $controlArgs (optional)
     */
    public function __construct( string $id, array $settingArgs, array $controlArgs = [] ) {

        parent::__construct( $id, $settingArgs );
        $this->control = new Control( $id . '_control', $controlArgs );
        $this->getControl()->setArg( 'settings', $this->getId() );

    }

    /**
     * Get the associated control.
     *
     * @since 0.2.0
     *
     * @return Control
     */
    public function getControl(): Control {

        return $this->control;

    }

    /**
     * @since 0.2.0
     */
    public function register( \WP_Customize_Manager $wpCustomize ) {

        $wpCustomize->add_setting( $this->getId(), $this->getArgs() );
        $this->control->register( $wpCustomize );

    }

}