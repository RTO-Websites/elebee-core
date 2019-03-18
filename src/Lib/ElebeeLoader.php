<?php
/**
 * Register all actions and filters for the theme
 *
 * @since   0.1.0
 *
 * @package ElebeeCore\Lib
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/ElebeeLoader.html
 */

namespace ElebeeCore\Lib;


\defined( 'ABSPATH' ) || exit;

/**
 * Register all actions and filters for the theme.
 *
 * Maintain a list of all hooks that are registered throughout
 * the theme, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 *
 * @since   0.1.0
 *
 * @package ElebeeCore\Lib
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/ElebeeLoader.html
 */
class ElebeeLoader {

    /**
     * The array of actions registered with WordPress.
     *
     * @since 0.1.0
     * @var array The actions registered with WordPress to fire when the theme loads.
     */
    protected $actions;

    /**
     * The array of filters registered with WordPress.
     *
     * @since 0.1.0
     * @var array The filters registered with WordPress to fire when the theme loads.
     */
    protected $filters;

    /**
     * Initialize the collections used to maintain the actions and filters.
     *
     * @since    0.1.0
     */
    public function __construct() {

        $this->actions = [];
        $this->filters = [];

    }

    /**
     * Add a new action to the collection to be registered with WordPress.
     *
     * @since 0.1.0
     *
     * @param string        $hook         The name of the WordPress action that is being registered.
     * @param object|string $component    A reference to the instance of the object on which the action is defined.
     * @param string        $callback     The name of the function definition on the $component.
     * @param int           $priority     (optional) The priority at which the function should be fired.
     * @param int           $acceptedArgs (optional) The number of arguments that should be passed to the $callback.
     *
     * @return void
     */
    public function addAction( string $hook, $component, string $callback, int $priority = 10, int $acceptedArgs = 1 ) {

        $this->actions = $this->add( $this->actions, $hook, $component, $callback, $priority, $acceptedArgs );

    }

    /**
     * Add a new filter to the collection to be registered with WordPress.
     *
     * @since 0.1.0
     *
     * @param string        $hook         The name of the WordPress filter that is being registered.
     * @param object|string $component    A reference to the instance of the object on which the filter is defined.
     * @param string        $callback     The name of the function definition on the $component.
     * @param int           $priority     (optional) The priority at which the function should be fired.
     * @param int           $acceptedArgs (optional) The number of arguments that should be passed to the $callback.
     *
     * @return void
     */
    public function addFilter( string $hook, $component, string $callback, int $priority = 10, int $acceptedArgs = 1 ) {

        $this->filters = $this->add( $this->filters, $hook, $component, $callback, $priority, $acceptedArgs );

    }

    /**
     * A utility function that is used to register the actions and hooks into a single
     * collection.
     *
     * @since 0.1.0
     *
     * @param array         $hooks        The collection of hooks that is being registered (that is, actions or filters).
     * @param string        $hook         The name of the WordPress filter that is being registered.
     * @param object|string $component    A reference to the instance of the object on which the filter is defined.
     * @param string        $callback     The name of the function definition on the $component.
     * @param int           $priority     (optional) The priority at which the function should be fired.
     * @param int           $acceptedArgs (optional) The number of arguments that should be passed to the $callback.
     * @return array The collection of actions and filters registered with WordPress.
     */
    private function add( array $hooks, string $hook, $component, string $callback, int $priority, int $acceptedArgs ) {

        $hooks[] = [
            'hook' => $hook,
            'component' => $component,
            'callback' => $callback,
            'priority' => $priority,
            'acceptedArgs' => $acceptedArgs,
        ];

        return $hooks;

    }

    /**
     * Register the filters and actions with WordPress.
     *
     * @since    0.1.0
     *
     * @return void
     */
    public function run() {

        foreach ( $this->filters as $hook ) {
            add_filter( $hook['hook'], [ $hook['component'], $hook['callback'] ], $hook['priority'], $hook['acceptedArgs'] );
        }

        foreach ( $this->actions as $hook ) {
            add_action( $hook['hook'], [ $hook['component'], $hook['callback'] ], $hook['priority'], $hook['acceptedArgs'] );
        }

    }

}
