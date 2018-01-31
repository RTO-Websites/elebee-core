<?php
/**
 * @since 0.2.0
 * @author RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 */

namespace ElebeeCore\Lib\PostTypeSupport;


use ElebeeCore\Lib\Hooking;

abstract class PostTypeSupport extends Hooking {

    /**
     * @var string
     */
    private $hook;

    /**
     * ThemeSupport constructor.
     * @param string $hook
     */
    public function __construct( string $hook ) {

        $this->hook = $hook;

        parent::__construct();

    }

    /**
     * @return string
     */
    public function getHook() {

        return $this->hook;

    }

    public function defineAdminHooks() {

        // TODO: Implement defineAdminHooks() method.

    }

    public function definePublicHooks() {

        $this->getLoader()->addAction( $this->getHook(), $this, 'hookCallback' );

    }

    /**
     *
     */
    public abstract function hookCallback();

    /**
     *
     */
    public function addPostTypeSupport() {

        $this->getLoader()->run();

    }

}