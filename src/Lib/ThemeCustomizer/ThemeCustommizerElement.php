<?php

/**
 * ThemeCustommizerElement.php
 *
 * @since   0.2.0
 *
 * @package ElebeeCore\Lib\ThemeCustomizer
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/ThemeCustomizer/ThemeCustommizerElement.html
 */

namespace ElebeeCore\Lib\ThemeCustomizer;


\defined( 'ABSPATH' ) || exit;

/**
 * Class ThemeCustommizerElement
 *
 * @since   0.2.0
 *
 * @package ElebeeCore\Lib\ThemeCustomizer
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/ThemeCustomizer/ThemeCustommizerElement.html
 */
abstract class ThemeCustommizerElement {

    /**
     * @since 0.2.0
     * @var string
     */
    private $id;

    /**
     * @since 0.2.0
     * @var array
     */
    private $args;

    /**
     * ThemeCustommizerElement constructor.
     *
     * @since 0.2.0
     *
     * @param string $id
     * @param array  $args
     */
    public function __construct( string $id, array $args ) {

        $this->id = $id;
        $this->args = $args;

    }

    /**
     * Get the id.
     *
     * @since 0.2.0
     *
     * @return string
     */
    public function getId() {

        return $this->id;

    }

    /**
     * Get the all arguments.
     *
     * @since 0.2.0
     *
     * @return array
     */
    public function getArgs() {

        return $this->args;

    }

    /**
     * Set argument.
     *
     * @since 0.2.0
     *
     * @param string $name
     * @param string $value
     *
     * @return void
     */
    public function setArg( string $name, string $value ) {

        $this->args[$name] = $value;

    }

    /**
     * Register the customizer element with the WP_Csutomize_Manager
     *
     * @since 0.2.0
     *
     * @param \WP_Customize_Manager $wpCustomize
     * @return void
     */
    abstract public function register( \WP_Customize_Manager $wpCustomize );

}