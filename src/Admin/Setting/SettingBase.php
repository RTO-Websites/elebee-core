<?php
/**
 * SettingBase.php
 *
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 */

namespace ElebeeCore\Admin\Setting;


\defined( 'ABSPATH' ) || exit;

abstract class SettingBase {

    private $name;

    private $title;

    private $default;

    /**
     * SettingBase constructor.
     * @param string $name
     * @param string $title
     * @param mixed  $default
     */
    public function __construct( string $name, string $title, $default = false ) {

        $this->name = $name;
        $this->title = $title;
        $this->default = $default;

    }

    /**
     * @return string
     */
    public function getName() {

        return $this->name;

    }

    /**
     * @return string
     */
    public function getTitle() {

        return $this->title;

    }

    /**
     * @return mixed
     */
    public function getDefault() {

        return $this->default;

    }

    public function register( $page ) {

        add_settings_field(
            $this->getName(),
            $this->getTitle(),
            [ $this, 'render' ],
            $page
        );

        register_setting( $page, $this->getName() );

    }

    /**
     * @return mixed
     */
    public function getOption() {

        return get_option( $this->getName(), $this->getDefault() );

    }

    abstract public function render();

}