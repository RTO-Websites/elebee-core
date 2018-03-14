<?php
/**
 * @since   0.3.2
 *
 * @package ElebeeCore\Extensions
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Extensions/WidgetExtensionBase.html
 */

namespace ElebeeCore\Extensions;


use ElebeeCore\Lib\ElebeeLoader;
use Elementor\Controls_Stack;
use Elementor\Widget_Base;

defined( 'ABSPATH' ) || exit;

/**
 * Class ExtensionBase
 *
 * @since   0.3.2
 *
 * @package ElebeeCore\Extensions
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Extensions/WidgetExtensionBase.html
 */
abstract class WidgetExtensionBase {

    const NEW_SECTION_BEFORE = 'before_section_start';

    const NEW_SECTION_AFTER = 'after_section_end';

    const EXTEND_SECTION_BEFORE = 'after_section_start';

    const EXTEND_SECTION_AFTER = 'before_section_end';

    private $tab;

    private $widgetId;

    private $sectionId;

    private $position;

    private $priority;

    private $separatorBefore;

    private $loader;

    public function __construct() {

        $this->tab = '';
        $this->widgetId = '';
        $this->sectionId = '';
        $this->position = '';
        $this->priority = '';
        $this->separatorBefore = false;
        $this->loader = new ElebeeLoader();
        $this->definePublicHooks();

    }

    public function definePublicHooks() {

        $this->getLoader()->addFilter( 'elementor/widget/render_content', $this, 'extendRender', 10, 2 );
        $this->getLoader()->addFilter( 'elementor/widget/print_template', $this, 'extendContentTemplate', 10, 2 );

    }

    /**
     * @return string
     */
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

    /**
     * @return bool
     */
    public function isSeparatorBefore(): bool {

        return $this->separatorBefore;

    }

    /**
     * @return ElebeeLoader
     */
    public function getLoader(): ElebeeLoader {

        return $this->loader;

    }

    public function isRegisteredTo( $widgetId ): bool {

        return $widgetId === $this->widgetId;

    }

    public function register( string $tab, string $widgetId = '', string $sectionId = '', string $position = '', bool $separatorBefore = false, int $priority = 10 ) {

        $this->tab = $tab;
        $this->widgetId = $widgetId;
        $this->sectionId = $sectionId;
        $this->position = $position;
        $this->separatorBefore = $separatorBefore;

        $this->priority = $priority;

        if ( $widgetId === '' || $sectionId === '' || $position === '' ) {
            $hookName = 'elementor/element/after_section_end';
        } else {
            $hookName = 'elementor/element/' . $widgetId . '/' . $sectionId . '/' . $position;
        }

        $this->loader->addAction( $hookName, $this, 'extendControlStack', $priority, 1 );
        $this->loader->run();

    }

    public function extendControlStack( Controls_Stack $element ) {

        $newSection = ( $this->getPosition() == self::NEW_SECTION_BEFORE || $this->getPosition() == self::NEW_SECTION_AFTER );

        if ( $newSection ) {
            $this->startControlsSection( $element );
        }

        $this->addControls( $element );

        if ( $newSection ) {
            $element->end_controls_section();
        }

    }

    public abstract function startControlsSection( Controls_Stack $element );

    public abstract function addControls( Controls_Stack $element );

    public abstract function extendRender( string $widgetContent, Widget_Base $widget = null ): string;

    public abstract function extendContentTemplate( string $widgetContent, Widget_Base $widget = null ): string;

}