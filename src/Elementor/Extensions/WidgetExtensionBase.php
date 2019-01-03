<?php
/**
 * @since   0.3.2
 *
 * @package ElebeeCore\Elementor\Extensions
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Elementor/Extensions/WidgetExtensionBase.html
 */

namespace ElebeeCore\Elementor\Extensions;


use ElebeeCore\Lib\ElebeeLoader;
use Elementor\Controls_Stack;
use Elementor\Widget_Base;

\defined( 'ABSPATH' ) || exit;

/**
 * Class ExtensionBase
 *
 * @since   0.3.2
 *
 * @package ElebeeCore\Elementor\Extensions
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Elementor/Extensions/WidgetExtensionBase.html
 */
abstract class WidgetExtensionBase {

    /**
     * @since 0.3.2
     */
    const NEW_SECTION_BEFORE = 'before_section_start';

    /**
     * @since 0.3.2
     */
    const NEW_SECTION_AFTER = 'after_section_end';

    /**
     * @since 0.3.2
     */
    const EXTEND_SECTION_BEFORE = 'after_section_start';

    /**
     * @since 0.3.2
     */
    const EXTEND_SECTION_AFTER = 'before_section_end';

    /**
     * @since 0.3.2
     * @var string
     */
    private $tab;

    /**
     * @since 0.3.2
     * @var string
     */
    private $widgetId;

    /**
     * @since 0.3.2
     * @var string
     */
    private $sectionId;

    /**
     * @since 0.3.2
     * @var string
     */
    private $position;

    /**
     * @since 0.3.2
     * @var string
     */
    private $priority;

    /**
     * @since 0.3.2
     * @var bool
     */
    private $separatorBefore;

    /**
     * @since 0.3.2
     * @var ElebeeLoader
     */
    private $loader;

    /**
     * WidgetExtensionBase constructor.
     *
     * @since 0.3.2
     */
    public function __construct() {

        $this->tab = '';
        $this->widgetId = '';
        $this->sectionId = '';
        $this->position = '';
        $this->priority = '';
        $this->separatorBefore = false;

        $this->loadDependencies();
        $this->defineEditorHooks();
        $this->definePublicHooks();

    }

    /**
     * @since 0.3.2
     *
     * @return void
     */
    private function loadDependencies() {

        $this->loader = new ElebeeLoader();

    }

    /**
     * @since 0.3.2
     *
     * @return void
     */
    private function defineEditorHooks() {

        $this->getLoader()->addFilter( 'elementor/widget/print_template', $this, 'extendContentTemplate', 10, 2 );

    }

    /**
     * @since 0.3.2
     *
     * @return void
     */
    private function definePublicHooks() {

        $this->getLoader()->addFilter( 'elementor/widget/render_content', $this, 'extendRender', 10, 2 );

    }

    /**
     * @since 0.3.2
     *
     * @return string
     */
    public function getTab(): string {

        return $this->tab;

    }

    /**
     * @since 0.3.2
     *
     * @return string
     */
    public function getWidgetId(): string {

        return $this->widgetId;

    }

    /**
     * @since 0.3.2
     *
     * @return string
     */
    public function getSectionId(): string {

        return $this->sectionId;

    }

    /**
     * @since 0.3.2
     *
     * @return string
     */
    public function getPosition(): string {

        return $this->position;

    }

    /**
     * @since 0.3.2
     *
     * @return string
     */
    public function getPriority(): string {

        return $this->priority;

    }

    /**
     * @since 0.3.2
     *
     * @return bool
     */
    public function isSeparatorBefore(): bool {

        return $this->separatorBefore;

    }

    /**
     * @since 0.3.2
     *
     * @return ElebeeLoader
     */
    public function getLoader(): ElebeeLoader {

        return $this->loader;

    }

    /**
     * @since 0.3.2
     *
     * @param string $widgetId
     * @return bool
     */
    public function isRegisteredTo( string $widgetId ): bool {

        return $widgetId === $this->widgetId;

    }

    /**
     * @since 0.3.2
     *
     * @param string $tab
     * @param string $widgetId
     * @param string $sectionId
     * @param string $position
     * @param bool   $separatorBefore
     * @param int    $priority
     * @return void
     */
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

    /**
     * @since 0.3.2
     *
     * @param Controls_Stack $element
     * @return void
     */
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

    /**
     * @since 0.3.2
     *
     * @param Controls_Stack $element
     * @return void
     */
    public abstract function startControlsSection( Controls_Stack $element );

    /**
     * @since 0.3.2
     *
     * @param Controls_Stack $element
     * @return void
     */
    public abstract function addControls( Controls_Stack $element );

    /**
     * @since 0.3.2
     *
     * @param string      $widgetContent
     * @param Widget_Base $widget
     * @return string
     */
    public abstract function extendRender( string $widgetContent, Widget_Base $widget = null ): string;

    /**
     * @since 0.3.2
     *
     * @param string      $widgetContent
     * @param Widget_Base $widget
     * @return string
     */
    public abstract function extendContentTemplate( string $widgetContent, Widget_Base $widget = null ): string;

}