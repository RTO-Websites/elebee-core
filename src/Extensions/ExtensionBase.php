<?php
/**
 * @since   0.2.0
 *
 * @package ElebeeCore\Admin
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Extensions/ExtensionBase.html
 */

namespace ElebeeCore\Extensions;


use ElebeeCore\Lib\Hooking;
use Elementor\Element_Base;

abstract class ExtensionBase extends Hooking {

    /**
     * @since  0.2.0
     *
     * @param Element_Base $element
     * @return void
     */
    public abstract function extend( Element_Base $element );

}