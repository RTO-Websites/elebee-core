<?php
/**
 * WidgetBase.php
 *
 * @since   0.1.0
 *
 * @package ElebeeCore\Lib
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/WidgetBase.html
 */

namespace ElebeeCore\Lib;


use Elementor\Widget_Base;
use Elementor\User;

defined( 'ABSPATH' ) || exit;

/**
 * Class WidgetBase
 *
 * @since   0.1.0
 *
 * @package ElebeeCore\Lib
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/WidgetBase.html
 */
abstract class WidgetBase extends Widget_Base {

    /**
     * @since 0.1.0
     * @var ElebeeLoader
     */
    private $loader;

    /**
     * WidgetBase constructor.
     *
     * @since 0.1.0
     *
     * @param array $data
     * @param array $args
     * @throws \Exception
     */
    public function __construct( array $data = [], array $args = null ) {

        parent::__construct( $data, $args );

        $this->loadDependencies();
        $this->defineAdminHooks();
        $this->definePublicHooks();
        $this->loader->run();

    }

    /**
     * @since 0.1.0
     *
     * @return void
     */
    private function loadDependencies() {

        $this->loader = new ElebeeLoader();

    }

    /**
     * @since 0.1.0
     *
     * @return void
     */
    public function defineAdminHooks() {


    }

    /**
     * @since 0.1.0
     *
     * @return void
     */
    public function definePublicHooks() {

        $this->getLoader()->addAction( 'elementor/frontend/before_enqueue_scripts', $this, 'enqueueStyles' );
        $this->getLoader()->addAction( 'elementor/frontend/before_enqueue_scripts', $this, 'enqueueScripts' );

        if ( $this->isPreviewMode() ) {
            add_action( 'wp_enqueue_scripts', [ $this, 'enqueueStyles' ] );
            add_action( 'wp_enqueue_scripts', [ $this, 'enqueueScripts' ] );
            wp_enqueue_scripts();
        }

    }

    /**
     * @since 0.1.0
     *
     * @return bool
     */
    public function isPreviewMode(): bool {

        if ( !User::is_current_user_can_edit() ) {
            return false;
        }

        if ( !isset( $_GET['elementor-preview'] ) ) {
            return false;
        }

        return true;

    }

    /**
     * @since 0.1.0
     *
     * @return void
     */
    public abstract function enqueueStyles();

    /**
     * @since 0.1.0
     *
     * @return void
     */
    public abstract function enqueueScripts();

    /**
     * @since 0.1.0
     *
     * @return ElebeeLoader
     */
    public function getLoader(): ElebeeLoader {

        return $this->loader;

    }

    /**
     * Retrieve the list of categories the widget belongs to.
     *
     * Used to determine where to display the widget in the editor.
     *
     * Note that currently Elementor supports only one category.
     * When multiple categories passed, Elementor uses the first one.
     *
     * @since  0.1.0
     *
     * @return array Widget categories.
     */
    public function get_categories() {

        return [ 'rto-elements' ];

    }

}