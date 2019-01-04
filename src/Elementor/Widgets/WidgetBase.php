<?php
/**
 * WidgetBase.php
 *
 * @since   0.1.0
 *
 * @package ElebeeCore\Elementor\Widgets
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Elementor/Widgets/WidgetBase.html
 */

namespace ElebeeCore\Elementor\Widgets;


use ElebeeCore\Lib\ElebeeLoader;
use Elementor\Widget_Base;

\defined( 'ABSPATH' ) || exit;

/**
 * Class WidgetBase
 *
 * @since   0.1.0
 *
 * @package ElebeeCore\Elementor\Widgets
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Elementor/Widgets/WidgetBase.html
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

        $this->getLoader()->addAction( 'elementor/preview/enqueue_styles', $this, 'enqueueStyles' );
        $this->getLoader()->addAction( 'elementor/preview/enqueue_scripts', $this, 'enqueueScripts' );

    }

    /**
     * @since 0.1.0
     *
     * @return void
     */
    public function definePublicHooks() {

        $this->getLoader()->addAction( 'elementor/frontend/before_enqueue_scripts', $this, 'enqueueStyles' );
        $this->getLoader()->addAction( 'elementor/frontend/before_enqueue_scripts', $this, 'enqueueScripts' );

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