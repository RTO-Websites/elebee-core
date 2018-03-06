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


use ElebeeCore\Lib\Hooking;
use Elementor\Controls_Stack;

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
abstract class ExtensionBase extends Hooking {

    private $hook;

    private $priority;

    private $argsCount;

    public function __construct( string $hook, int $priority  = 10, int $argsCount = 1 ) {

        $this->hook = $hook;
        $this->priority = $priority;
        $this->argsCount = $argsCount;

        parent::__construct();

    }

    /**
     * @since  0.2.0
     *
     * @param Controls_Stack $element
     * @return void
     */
    public abstract function extend( Controls_Stack $element );

    public function defineAdminHooks() {
        // TODO: Implement defineAdminHooks() method.
    }


    public function definePublicHooks() {

        $this->getLoader()->addAction( $this->hook, $this, 'extend', $this->priority, $this->argsCount );

    }


}