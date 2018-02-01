<?php
/**
 * @since 0.1.0
 * @author RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 */

namespace ElebeeCore\Lib;


use Elementor\Widget_Base;
use Elementor\User;

abstract class ElebeeWidget extends Widget_Base {

    /**
     * @var ElebeeLoader
     */
    private $loader;

    /**
     * ElebeeWidget constructor.
     * @param array $data
     * @param null $args
     */
    public function __construct( $data = [], $args = null ) {

        parent::__construct( $data, $args );

        $this->loadDependencies();
        $this->defineAdminHooks();
        $this->definePublicHooks();
        $this->loader->run();

    }

    /**
     *
     */
    private function loadDependencies() {

        $this->loader = new ElebeeLoader();

    }

    /**
     *
     */
    public function defineAdminHooks() {


    }

    /**
     *
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

    public function isPreviewMode() {

        if ( !User::is_current_user_can_edit() ) {
            return false;
        }

        if ( !isset( $_GET['elementor-preview'] ) ) {
            return false;
        }

        return true;

    }

    /**
     *
     */
    public abstract function enqueueStyles();

    /**
     *
     */
    public abstract function enqueueScripts();

    /**
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
     * @since 0.1.0
     *
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories() {

        return [ 'rto-elements' ];

    }

}