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

    private $loader;

    private $tab;

    private $widgetId;

    private $sectionId;

    private $position;

    private $priority;

    public function __construct( string $tab ) {

        $this->loader = new ElebeeLoader();
        $this->tab = $tab;
        $this->widgetId = '';
        $this->sectionId = '';
        $this->position = '';
        $this->priority = '';

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

    /**
     * @return string
     */
    public function getWidgetId(): string {

        return $this->widgetId;

    }

    /**
     * @return string
     */
    public function getSectionId(): string {

        return $this->sectionId;

    }

    /**
     * @return string
     */
    public function getPosition(): string {

        return $this->position;

    }

    /**
     * @return string
     */
    public function getPriority(): string {

        return $this->priority;

    }

    public function isRegisteredTo( $widgetId ) {

        return $widgetId === $this->widgetId;

    }

    public function addRegistration( string $widgetId = '', string $sectionId = '', string $position = '', int $priority = 10 ) {

        $this->widgetId = $widgetId;
        $this->sectionId = $sectionId;
        $this->position = $position;
        $this->priority = $priority;

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