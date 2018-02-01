<?php
/**
 * Setting.php
 */

namespace ElebeeCore\Lib\ThemeCustomizer;


/**
 * Class Setting
 *
 * @package ElebeeCore
 * @author RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @since 0.2.0
 */
class Setting extends ThemeCustommizerElement {

    /**
     * @var Control
     * @ignore
     */
    private $control;

    /**
     * Setting constructor.
     *
     * @param $id
     * @param array $settingArgs
     * @param array $controlArgs (optional)
     */
    public function __construct( $id, array $settingArgs, array $controlArgs = [] ) {

        parent::__construct( $id, $settingArgs );
        $this->control = new Control( $id . '_control', $controlArgs );
        $this->getControl()->setArg( 'settings', $this->getId() );

    }

    /**
     * Get the associated control.
     *
     * @return Control
     */
    public function getControl() {

        return $this->control;

    }

    /**
     * {@inheritdoc}
     */
    public function register( \WP_Customize_Manager $wpCustomize ) {

        $wpCustomize->add_setting( $this->getId(), $this->getArgs() );
        $this->control->register( $wpCustomize );

    }

}