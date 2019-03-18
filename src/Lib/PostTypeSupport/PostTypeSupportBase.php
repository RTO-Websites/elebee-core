<?php
/**
 * PostTypeSupport.php
 *
 * @since   0.2.0
 *
 * @package ElebeeCore\Lib\PostTypeSupport
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/PostTypeSupport/PostTypeSupportBase.html
 */

namespace ElebeeCore\Lib\PostTypeSupport;


use ElebeeCore\Lib\Util\Hooking;

\defined( 'ABSPATH' ) || exit;

/**
 * Class PostTypeSupport
 *
 * @since   0.2.0
 *
 * @package ElebeeCore\Lib\PostTypeSupport
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/PostTypeSupport/PostTypeSupportBase.html
 */
abstract class PostTypeSupportBase extends Hooking {

    /**
     * @since 0.2.0
     * @var string
     */
    private $hook;

    /**
     * ThemeSupport constructor.
     *
     * @since 0.2.0
     *
     * @param string $hook
     */
    public function __construct( string $hook ) {

        $this->hook = $hook;

        parent::__construct();

    }

    /**
     * @since 0.2.0
     *
     * @return string
     */
    public function getHook() {

        return $this->hook;

    }

    /**
     * @since 0.2.0
     */
    public function defineAdminHooks() {

        // TODO: Implement defineAdminHooks() method.

    }

    /**
     * @since 0.2.0
     */
    public function definePublicHooks() {

        $this->getLoader()->addAction( $this->getHook(), $this, 'hookCallback' );

    }

    /**
     * @since 0.2.0
     *
     * @return void
     */
    abstract public function hookCallback();

    /**
     * @since 0.2.0
     *
     * @return void
     */
    public function addPostTypeSupport() {

        $this->getLoader()->run();

    }

}