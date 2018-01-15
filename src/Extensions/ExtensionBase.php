<?php
/**
 * @since 0.2.0
 * @author RTO GmbH <info@rto.de>
 * @licence MIT
 */

namespace ElebeeCore\Extensions;


use ElebeeCore\Lib\Hooking;
use Elementor\Element_Base;

abstract class ExtensionBase {

    use Hooking;

    /**
     * @param Element_Base $element
     */
    public abstract function extend( Element_Base $element );

}