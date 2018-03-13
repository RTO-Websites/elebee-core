<?php
/**
 * @since   0.2.0
 *
 * @package ElebeeCore\Extensions
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Extensions/ExtensionBase.html
 */

namespace ElebeeCore\Extensions;


use ElebeeCore\Lib\ElebeeLoader;
use ElebeeCore\Lib\Hook\ActionHook;
use ElebeeCore\Lib\Hooking;
use Elementor\Controls_Stack;
use Elementor\Element_Base;
use Elementor\Widget_Base;

defined( 'ABSPATH' ) || exit;

/**
 * Class ExtensionBase
 *
 * @since   0.2.0
 *
 * @package ElebeeCore\Extensions
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Extensions/ExtensionBase.html
 */
abstract class ExtensionBase {

    private $hookList;

    private $loader;

    private $registerdTo;

    private $tab;

    public function __construct( string $tab ) {

        $this->hookList = [];
        $this->loader = new ElebeeLoader();
        $this->registerdTo = [];
        $this->tab = $tab;

    }

    /**
     * @return ElebeeLoader
     */
    public function getLoader(): ElebeeLoader {

        return $this->loader;

    }

    public function getTab(): string {

        return $this->tab;

    }

    public function isRegisteredTo( $widgetId ) {

        return in_array( $widgetId, $this->registerdTo );

    }

    public function addRegistration( string $widgetId = '', string $sectionId = '', string $position = '', int $priority = 10 ) {

        if ( $this->isRegisteredTo( $widgetId ) ) {
            return;
        }

        $this->registerdTo[] = $widgetId;

        if ( $widgetId === '' || $sectionId === '' || $position === '' ) {
            $hookName = 'elementor/element/after_section_end';
        } else {
            $hookName = 'elementor/element/' . $widgetId . '/' . $sectionId . '/' . $position;
        }

        $this->loader->addAction( $hookName, $this, 'extendControlStack', $priority, 1 );

    }

    public function register() {

        $this->loader->run();

    }

    /**
     * @since  0.2.0
     *
     * @param Element_Base $element
     * @return void
     */
    public abstract function extendControlStack( Controls_Stack $element );

}