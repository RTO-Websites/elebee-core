<?php
/**
 * ThemeSupport.php
 *
 * @since   0.2.0
 *
 * @package ElebeeCore\Lib\ThemeSupport
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/ThemeSupport/ThemeSupportBase.html
 */

namespace ElebeeCore\Lib\ThemeSupport;


use ElebeeCore\Lib\Util\Hooking;

\defined( 'ABSPATH' ) || exit;

/**
 * Class ThemeSupport
 *
 * @since   0.2.0
 *
 * @package ElebeeCore\Lib\ThemeSupport
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/ThemeSupport/ThemeSupportBase.html
 */
abstract class ThemeSupportBase extends Hooking {

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
    public function __construct( string $hook = 'after_setup_theme' ) {

        $this->hook = $hook;

        parent::__construct();

    }

    /**
     * @since 0.2.0
     *
     * @return string
     */
    public function getHook(): string {

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

        $this->getLoader()->addAction( $this->getHook(), $this, 'addThemeSupport' );

    }

    /**
     * @since 0.2.0
     *
     * @return void
     */
    abstract public function addThemeSupport();

}