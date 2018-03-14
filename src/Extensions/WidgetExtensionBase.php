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
abstract class WidgetExtensionBase extends ExtensionBase {

    public abstract function extendRender( string $widgetContent, Widget_Base $widget = null ): string;
    public abstract function extendContentTemplate( string $widgetContent, Widget_Base $widget = null ): string;

}