<?php
/**
 * @since 0.1.0
 * @author RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 */

namespace ElebeeCore\Lib;


abstract class Hooking {

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the theme.
     *
     * @since    0.1.0
     * @access   private
     * @var      ElebeeLoader $loader Maintains and registers all hooks for the theme.
     */
    private $loader;

    /**
     * ThemeSupportFeaturedImage constructor.
     * @param $loader
     */
    public function __construct() {

        $this->loadDependencies();
        $this->defineAdminHooks();
        $this->definePublicHooks();

    }

    /**
     * Load the required dependencies for this theme.
     *
     * Include the following files that make up the theme:
     *
     * - ElebeeLoader. Orchestrates the hooks of the theme.
     * - ElebeeI18n. Defines internationalization functionality.
     * - ElebeeAdmin. Defines all hooks for the admin area.
     * - ElebeePublic. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    0.1.0
     * @access   private
     */
    private function loadDependencies() {

        $this->loader = new ElebeeLoader();

    }

    /**
     * The reference to the class that orchestrates the hooks with the theme.
     *
     * @since     0.1.0
     * @access    public
     * @return    ElebeeLoader    Orchestrates the hooks of the theme.
     */
    public function getLoader() {

        return $this->loader;

    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the theme.
     *
     * @since    0.1.0
     * @access   public
     */
    public abstract function defineAdminHooks();

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the theme.
     *
     * @since    0.1.0
     * @access   public
     */
    public abstract function definePublicHooks();

}